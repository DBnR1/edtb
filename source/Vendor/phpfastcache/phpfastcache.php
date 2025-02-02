<?php
/*
 * khoaofgod@gmail.com
 * Website: http://www.phpfastcache.com
 * Example at our website, any bugs, problems, please visit http://faster.phpfastcache.com
 */

require_once(dirname(__FILE__)."/abstract.php");
require_once(dirname(__FILE__)."/driver.php");

// short function
if (!function_exists("__c")) {
    function __c($storage = "", $option = array())
    {
        return phpFastCache($storage, $option);
    }
}

// main function
if (!function_exists("phpFastCache")) {
    function phpFastCache($storage = "auto", $config = array())
    {
        $storage = strtolower($storage);
        if (empty($config)) {
            $config = phpFastCache::$config;
        }

        if ($storage == "" || $storage == "auto") {
            $storage = phpFastCache::getAutoClass($config);
        }


        $instance = md5(json_encode($config).$storage);
        if (!isset(phpFastCache_instances::$instances[$instance])) {
            $class = "phpfastcache_".$storage;
            phpFastCache::required($storage);
            phpFastCache_instances::$instances[$instance] = new $class($config);
        }

        return phpFastCache_instances::$instances[$instance];
    }
}

class phpFastCache_instances
{
    public static $instances = array();
}


// main class
class phpFastCache
{
    public static $disabled = false;
    public static $config = array(
        "storage"       =>  "", // blank for auto
        "default_chmod" =>  0777, // 0777 , 0666, 0644
        /*
         * Fall back when old driver is not support
         */
        "fallback"  => "files",

        "securityKey"   =>  "auto",
        "htaccess"      => true,
        "path"      =>  "cache",

        "memcache"        =>  array(
            array("127.0.0.1",11211,1),
            //  array("new.host.ip",11211,1),
        ),

        "redis"         =>  array(
            "host"  => "127.0.0.1",
            "port"  =>  "",
            "password"  =>  "",
            "database"  =>  "",
            "timeout"   =>  ""
        ),

        "ssdb"         =>  array(
            "host"  => "127.0.0.1",
            "port"  =>  8888,
            "password"  =>  "",
            "timeout"   =>  ""
        ),

        "extensions"    =>  array(),
    );

    protected static $tmp = array();
    public $instance;

    public function __construct($storage = "", $config = array())
    {
        if (empty($config)) {
            $config = phpFastCache::$config;
        }
        $config['storage'] = $storage;

        $storage = strtolower($storage);
        if ($storage == "" || $storage == "auto") {
            $storage = self::getAutoClass($config);
        }

        $this->instance = phpFastCache($storage, $config);
    }




    public function __call($name, $args)
    {
        return call_user_func_array(array($this->instance, $name), $args);
    }


    /*
     * Cores
     */

    public static function getAutoClass($config)
    {
        $driver = "files";
        $path = self::getPath(false, $config);
        if (is_writeable($path)) {
            $driver = "files";
        } elseif (extension_loaded('apc') && ini_get('apc.enabled') && strpos(PHP_SAPI, "CGI") === false) {
            $driver = "apc";
        } elseif (class_exists("memcached")) {
            $driver = "memcached";
        } elseif (extension_loaded('wincache') && function_exists("wincache_ucache_set")) {
            $driver = "wincache";
        } elseif (extension_loaded('xcache') && function_exists("xcache_get")) {
            $driver = "xcache";
        } elseif (function_exists("memcache_connect")) {
            $driver = "memcache";
        } elseif (class_exists("Redis")) {
            $driver = "redis";
        } else {
            $driver = "files";
        }


        return $driver;
    }

    public static function getPath($skipCreatePath = false, $config)
    {
        if (!isset($config['path']) || $config['path'] == '') {

            // revision 618
            if (self::isPHPModule()) {
                $tmpDir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
                $path = $tmpDir;
            } else {
                $path = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], "/")."/../" : rtrim(dirname(__FILE__), "/")."/";
            }

            if (self::$config['path'] != "") {
                $path = $config['path'];
            }
        } else {
            $path = $config['path'];
        }

        $securityKey = $config['securityKey'];
        if ($securityKey == "" || $securityKey == "auto") {
            $securityKey = self::$config['securityKey'];
            if ($securityKey == "auto" || $securityKey == "") {
                $securityKey = isset($_SERVER['HTTP_HOST']) ? ltrim(strtolower($_SERVER['HTTP_HOST']), "www.") : "default";
                $securityKey = preg_replace("/[^a-zA-Z0-9]+/", "", $securityKey);
            }
        }
        if ($securityKey != "") {
            $securityKey.= "/";
        }

        $fullPath = $path."/".$securityKey;
        $fullPathx = md5($fullPath);




        if ($skipCreatePath  == false && !isset(self::$tmp[$fullPathx])) {
            if (!@file_exists($fullPath) || !@is_writable($fullPath)) {
                if (!@file_exists($fullPath)) {
                    @mkdir($fullPath, self::__setChmodAuto($config));
                }
                if (!@is_writable($fullPath)) {
                    @chmod($fullPath, self::__setChmodAuto($config));
                }
                if (!@file_exists($fullPath) || !@is_writable($fullPath)) {
                    throw new Exception("PLEASE CREATE OR CHMOD ".$fullPath." - 0777 OR ANY WRITABLE PERMISSION!", 92);
                }
            }


            self::$tmp[$fullPathx] = true;
            self::htaccessGen($fullPath, $config['htaccess']);
        }

        return realpath($fullPath);
    }


    public static function __setChmodAuto($config)
    {
        if (!isset($config['default_chmod']) || $config['default_chmod'] == "" || is_null($config['default_chmod'])) {
            return 0777;
        } else {
            return $config['default_chmod'];
        }
    }

    protected static function getOS()
    {
        $os = array(
            "os" => PHP_OS,
            "php" => PHP_SAPI,
            "system"    => php_uname(),
            "unique"    => md5(php_uname().PHP_OS.PHP_SAPI)
        );
        return $os;
    }

    public static function isPHPModule()
    {
        if (PHP_SAPI == "apache2handler") {
            return true;
        } else {
            if (strpos(PHP_SAPI, "handler") !== false) {
                return true;
            }
        }
        return false;
    }

    protected static function htaccessGen($path, $create = true)
    {
        if ($create == true) {
            if (!is_writeable($path)) {
                try {
                    chmod($path, 0777);
                } catch (Exception $e) {
                    throw new Exception("PLEASE CHMOD ".$path." - 0777 OR ANY WRITABLE PERMISSION!", 92);
                }
            }
            if (!@file_exists($path."/.htaccess")) {
                //   echo "write me";
                $html = "order deny, allow \r\n
deny from all \r\n
allow from 127.0.0.1";

                $f = @fopen($path."/.htaccess", "w+");
                if (!$f) {
                    throw new Exception("PLEASE CHMOD ".$path." - 0777 OR ANY WRITABLE PERMISSION!", 92);
                }
                fwrite($f, $html);
                fclose($f);
            }
        }
    }


    public static function setup($name, $value = "")
    {
        if (is_array($name)) {
            self::$config = $name;
        } else {
            self::$config[$name] = $value;
        }
    }

    public static function debug($something)
    {
        echo "Starting Debugging ...<br>\r\n ";
        if (is_array($something)) {
            echo "<pre>";
            print_r($something);
            echo "</pre>";
            var_dump($something);
        } else {
            echo $something;
        }
        echo "\r\n<br> Ended";
        exit;
    }

    public static function required($class)
    {
        require_once(dirname(__FILE__)."/drivers/".$class.".php");
    }
}
