<?php
/**
 * Login to FD API
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

/** @require Theme class */
require_once $_SERVER['DOCUMENT_ROOT'] . '/style/Theme.php';

/**
 * initiate page header
 */
$header = new Header();

/** @var string page_title */
$header->pageTitle = 'Companion API login';

/**
 * display the header
 */
$header->displayHeader();

/**
 * send login details
 */
if (isset($_GET['login'], $_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        exec('"' . $settings['curl_exe'] . '" -c "' . $settings['cookie_file'] . '" -H "User-Agent: ' . $settings['agent'] .
            '" -d email=' . $email . ' -d password="' . urlencode($password) . '" "https://companion.orerve.net/user/login" -k',
            $out);
    }

    if (!empty($out)) {
        write_log('Error: API login failed, possibly mistyped password or email.', __FILE__, __LINE__);
    }
}

/**
 * send verification code
 */
if (isset($_GET['sendcode'])) {
    $code = $_POST['code'];

    if (!empty($code)) {
        exec('"' . $settings['curl_exe'] . '" -b "' . $settings['cookie_file'] . '" -c "' . $settings['cookie_file'] .
            '" -H "User-Agent: ' . $settings['agent'] . '" -d code=' . $code . ' "https://companion.orerve.net/user/confirm" -k',
            $out);
    }

    if (!empty($out)) {
        $error = json_encode($out);
        write_log('Error: ' . $error, __FILE__, __LINE__);
    }
}
?>
    <div class="entries">
        <div class="entries_inner">
            <?php
            if (isset($_GET['login']) && !isset($_GET['sendcode'])) {
                ?>
                <div class="input" style="display: block">
                    <form method="post" action="/Admin/API_login.php?sendcode">
                        <div class="input-inner">
                            <table>
                                <tr>
                                    <td class="heading">Companion API Verification Code</td>
                                </tr>
                                <tr>
                                    <td class="dark">
                                        <span class="left"><img src="/style/img/about.png" class="icon32" alt="Info"/></span>
                                        If your email and password were correct,<br/>
                                        you should now have received a verification code to your email.<br/>
                                        Copy and paste it here, then click Send.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dark">
                                        <input class="textbox" type="text" name="code" placeholder="Verification Code"
                                               style="width: 410px" required autofocus/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dark">
                                        <button type="submit" class="button">Send</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                <?php
            } elseif (isset($_GET['sendcode'], $_POST['code'])) {
                echo notice('The companion api is now connected.<br>Click the refresh icon to initialize. Then return to using ED ToolBox normally. Note that it may take a while for the API to initialize.<br><a id="api_refresh" href="javascript:void(0)" onclick="refresh_api()" title="Refresh API data"><img src="/style/img/refresh_24.png" class="icon24" alt="Refresh"></a>',
                    'API connected');
            } else {
                /**
                 * check if cookies are good (when are they not?)
                 */
                exec('"' . $settings['curl_exe'] . '" -b "' . $settings['cookie_file'] . '" -c "' . $settings['cookie_file'] .
                    '" -H "User-Agent: ' . $settings['agent'] . '" "https://companion.orerve.net/profile" -k', $out);

                if (!empty($out)) {
                    echo notice('The companion api is already connected.<br>Click the refresh icon to refresh. Note that it may take a while for the API to initialize.<br><a id="api_refresh" href="javascript:void(0)" onclick="refresh_api()" title="Refresh API data"><img src="/style/img/refresh_24.png" class="icon24" alt="Refresh"></a>',
                        'API already connected');
                } else {
                    ?>
                    <div class="input" style="display: block">
                        <form method="post" action="/Admin/API_login.php?login">
                            <div class="input-inner">
                                <table style="width: 340px">
                                    <tr>
                                        <td class="heading">Companion API Login</td>
                                    </tr>
                                    <tr>
                                        <td class="dark">
                                            <span class="left"><img src="/style/img/about.png" alt="Info" class="icon32"/></span>
                                            Provide the email address and password you use to login to <strong>Elite
                                                Dangerous</strong>.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="dark">
                                            <input class="textbox" type="text" name="email" placeholder="ED account email address"
                                                   style="width: 330px" required autofocus/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="dark">
                                            <input class="textbox" type="password" name="password"
                                                   placeholder="ED account password" style="width: 330px" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="dark">
                                            <button type="submit" class="button">Log in</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
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
