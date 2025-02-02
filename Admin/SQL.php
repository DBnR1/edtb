<?php
/**
 * Run SQL statements
 *
 * No description
 *
 * @package EDTB\Admin
 * @author Mauri Kujala <contact@edtb.xyz>
 * @copyright Copyright (C) 2016, Mauri Kujala
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */

 /*
 * ED ToolBox, a companion web app for the video game Elite Dangerous
 * (C) 1984 - 2016 Frontier Developments Plc.
 * ED ToolBox or its creator are not affiliated with Frontier Developments Plc.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 */

/** @var string $notify */
$notify = '';

/** @require Theme class */
require_once $_SERVER['DOCUMENT_ROOT'] . '/style/Theme.php';

/**
 * initiate page header
 */
$header = new Header();

/** @var string page_title */
$header->pageTitle = 'SQL';

/**
 * display the header
 */
$header->displayHeader();


/**
 * execute SQL queries
 */
if (isset($_POST['code'])) {
    $code = $_POST['code'];

    /**
     * blacklist certain commands for security purposes
     */
    $blacklist = [
        'DROP',
        'DELETE',
        'ROUTINE',
        'EXECUTE',
        'DATABASE',
        'SERVER',
        'EMPTY',
        'TRUNCATE',
        'TRIGGER'
    ];

    $continue = true;

    $pattern = '/"(.*?)"/';
    $haystack = preg_replace($pattern, '', $code);
    $pattern = "/'(.*?)'/";
    $haystack = preg_replace($pattern, '', $haystack);
    $pattern = '/`(.*?)`/';
    $haystack = preg_replace($pattern, '', $haystack);

    foreach ($blacklist as $find) {
        if (strripos($haystack, $find)) {
            $continue = false;
            $notify = '<div class="notify_deleted">Query contains a forbidden command.</div>';

            break;
        }
    }

    /**
     * if the query is safe to execute, get on with it
     */
    if ($continue !== false) {
        $queries = explode('>>BREAK<<', $code);

        foreach ($queries as $query) {
            if (!$mysqli->query($query)) {
                $error = $mysqli->error;
                $notify = '<div class="notify_deleted">Execution failed:<br>' . $error . '</div>';
            } else {
                if ($rows = $mysqli->query($query)->num_rows) {
                    $error = $mysqli->info;
                    $notify = '<div class="notify_success">Query succesfully executed.<br>' . $error . '<br>Rows: ' .
                        number_format($rows) . '</div>';
                } else {
                    $error = $mysqli->info;
                    $notify = '<div class="notify_success">Query succesfully executed.<br>' . $error . '</div>';
                }
            }
        }
    }
}
?>
<!-- codemirror -->
    <link type="text/css" rel="stylesheet" href="/source/Vendor/codemirror/lib/codemirror.css">
    <script type="text/javascript" src="/source/Vendor/codemirror/lib/codemirror.js"></script>
    <script type="text/javascript" src="/source/Vendor/codemirror/mode/sql/sql.js"></script>

    <div class="entries">
        <div class="entries_inner" style="margin-bottom: 20px">
            <h2>
                <img src="/style/img/sql24.png" alt="Settings" class="icon24">Execute SQL
            </h2>
            <hr>
            <?= $notify ?>
            <div style="padding: 5px; margin-bottom: 10px">
                You can use this form to perform SQL statements. Certain commands, such as<br>
                <strong>DELETE</strong>, <strong>TRUNCATE</strong> and <strong>DROP</strong> are not available here.<br>
                To do multiple statements, use <code>>>BREAK<<</code> to separate statements<br><br>

                For more complete database management, use the included db manager (<a
                        href="/Admin/db_manager.php">Adminer</a>)<br>
                or a database manager of your choice.
            </div>
            <form method="post" action="SQL.php">
            <textarea title="SQL" id="codes" name="code">
<?php
if (isset($_POST['code'])) {
    echo $code;
} else {
    echo '/*
*        SQL statement goes here...
*/
';
}
?>
</textarea>
                <input type="submit" class="button" value="Submit">
            </form>
            <script type="text/javascript">
                var editor = CodeMirror.fromTextArea(document.getElementById("codes"), {
                    lineNumbers: true,
                    mode: "text/x-mysql"
                });
            </script>
        </div>
    </div>
<?php
/**
 * initiate page footer
 */
$footer = new Footer();

/**
 * display the footer
 */
$footer->displayFooter();
