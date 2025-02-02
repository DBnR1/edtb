<?php
/**
 * Ajax backend file to check if a new version of ED ToolBox is available
 *
 * No description
 *
 * @package EDTB\Backend
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

/** @array $data */
$data['notifications'] = '';
$data['notifications_data'] = 'false';

$currentVersion = $settings['edtb_version'];
$lastCheck = edtbCommon('last_update_check', 'unixtime');
$timeFrame = time() - 5 * 60 * 60;

if ($lastCheck < $timeFrame) {
    if ($jsonFile = file_get_contents('http://data.edtb.xyz/version.json')) {
        $jsonData = json_decode($jsonFile);

        $newestVersion = $jsonData->{'currentVersion'};

        // update latest_version value
        edtbCommon('latest_version', 'value', true, $newestVersion);
    } else {
        $error = error_get_last();
        write_log('Error: ' . $error['message'], __FILE__, __LINE__);
    }
    // update last_update_check time
    edtbCommon('last_update_check', 'unixtime', true, time());
}

$newestVersion = edtbCommon('latest_version', 'value');

if (version_compare($currentVersion, $newestVersion) < 0) {
    // get last_update_check value
    $ignoreVersion = edtbCommon('last_update_check', 'value');

    if ($newestVersion != $ignoreVersion) {
        if ($jsonFile = file_get_contents('http://data.edtb.xyz/version.json')) {
            $jsonData = json_decode($jsonFile);

            $shortDesc = $jsonData->{'short'};
            $longDesc = $jsonData->{'versionInformation'};
            $data['notifications'] .= '<a href="javascript:void(0)" title="New version available" onclick="$(\'#notice_new\').fadeToggle(\'fast\')"><img src="/style/img/upgrade.png" class="icon26" alt="Upgrade"></a>';
            $data['notifications_data'] = $shortDesc . '<br><br><br>' . $longDesc;
            $data['notifications_data'] .= '<br><br><strong><a href="javascript:void(0)" onclick="ignore_version(\'' . $newestVersion . '\')">Click here if you want to ignore this version</a></strong>';
        }
    }
}

/**
 * Display notification if user hasn't updated data in a while
 */
/*$lastUpdate = edtb_common("last_data_update", "unixtime");
$now = time()-(7*24*60*60); // 7 days

if ($now > $lastUpdate && $lastUpdate != "1")
{
    $data["notifications"] .= '<a href="javascript:void(0)" title="Notice" onclick="$(\'#notice\').fadeToggle(\'fast\')"><img src="/style/img/notice.png" style="height: 26px; width: 26px" alt="Notice"></a>';
}*/

if ($data['notifications'] === '') {
    $data['notifications'] = 'false';
}
