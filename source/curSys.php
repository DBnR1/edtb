<?php
/**
 * Get current system
 *
 * This script parses the netLog file to determine the user's current location and fetches
 * related information from the database and puts that information to global variable $curSys
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

/** @require configs */
require_once __DIR__ . '/config.inc.php';
/** @require functions */
require_once __DIR__ . '/functions.php';
/** @array curSys */
$curSys = [];

if (is_dir($settings['log_dir']) && is_readable($settings['log_dir'])) {
    /**
     * select the newest file
     */
    if (!$files = scandir($settings['log_dir'], SCANDIR_SORT_DESCENDING)) {
        $error = error_get_last();
        write_log('Error: ' . $error['message'], __FILE__, __LINE__);
    }
    $newestFile = $files[0];

    /**
     * read file to an array
     */
    if (!$line = file($settings['log_dir'] . '/' . $newestFile)) {
        $error = error_get_last();
        write_log('Error: ' . $error['message'], __FILE__, __LINE__);
    } else {
        // reverse array
        $lines = array_reverse($line);

        foreach ($lines as $lineNum => $line) {
            $pos = strpos($line, 'System:');
            /**
             * skip lines that contain "ProvingGround" because they are CQC systems
             */
            $pos2 = strrpos($line, 'ProvingGround');

            if ($pos !== false && $pos2 === false) {
                /**
                 * Regular expression filter to find the system name
                 */
                preg_match_all("/\System:\"(.*?)\"/", $line, $matches);
                $cssystemname = $matches[1][0];
                $curSys['name'] = $cssystemname;

                /**
                 * Regular expression filter to find the visited time
                 */
                preg_match_all("/\{(.*?)\} System:/", $line, $matches2);
                $visitedTime = $matches2[1][0];

                /**
                 * Regular expression filter to find the system's coordinates
                 */
                preg_match_all("/\StarPos:\((.*?)\)/", $line, $matches3);
                $curSys['coordinates'] = $matches3[1][0];
                $coordParts = explode(',', $curSys['coordinates']);

                $curSys['x'] = $coordParts[0];
                $curSys['y'] = $coordParts[1];
                $curSys['z'] = $coordParts[2];

                $curSys['name'] = $curSys['name'] ?? '';
                $curSys['esc_name'] = $mysqli->real_escape_string($curSys['name']);

                /**
                 * define defaults
                 */
                $curSys['id'] = -1;
                $curSys['population'] = '';
                $curSys['allegiance'] = '';
                $curSys['economy'] = '';
                $curSys['government'] = '';
                $curSys['ruling_faction'] = '';
                $curSys['state'] = 'unknown';
                $curSys['security'] = 'unknown';
                $curSys['power'] = '';
                $curSys['power_state'] = '';
                $curSys['needs_permit'] = '';
                $curSys['updated_at'] = '';
                $curSys['simbad_ref'] = '';
                $curSys['users_own'] = false;

                $sysName = $mysqli->real_escape_string($curSys['name']);

                /**
                 * fetch data from edtb_systems
                 */
                $query = "  SELECT id, x, y, z, ruling_faction, population, government, allegiance, state,
                            security, economy, power, power_state, needs_permit, updated_at, simbad_ref
                            FROM edtb_systems
                            WHERE name = '$sysName'
                            LIMIT 1";

                $result = $mysqli->query($query) or write_log($mysqli->error, __FILE__, __LINE__);
                $exists = $result->num_rows;

                if ($exists > 0) {
                    $obj = $result->fetch_object();

                    $curSys['coordinates'] = $obj->x . ',' . $obj->y . ',' . $obj->z;
                    $curSys['id'] = $obj->id;
                    $curSys['population'] = $obj->population;
                    $curSys['allegiance'] = $obj->allegiance;
                    $curSys['economy'] = $obj->economy;
                    $curSys['government'] = $obj->government;
                    $curSys['ruling_faction'] = $obj->ruling_faction;
                    $curSys['state'] = $obj->state;
                    $curSys['security'] = $obj->security;
                    $curSys['power'] = $obj->power;
                    $curSys['power_state'] = $obj->power_state;
                    $curSys['needs_permit'] = $obj->needs_permit;
                    $curSys['updated_at'] = $obj->updated_at;
                    $curSys['simbad_ref'] = $obj->simbad_ref;

                    /**
                     * If not found, try user_systems_own
                     */
                } else {
                    $query = "  SELECT x, y, z
                                FROM user_systems_own
                                WHERE name = '$sysName'
                                LIMIT 1";

                    $result = $mysqli->query($query) or write_log($mysqli->error, __FILE__, __LINE__);

                    $oexists = $result->num_rows;

                    /**
                     * If it's found, but we have no-cordinates for some reason
                     * get any known coordinates, but mark users_own true
                     * to prevent EDSM submission
                     */
                    if ($oexists > 0 &&
                        (empty($curSys['x']) || empty($curSys['y']) || empty($curSys['z']))
                    ) {
                        $obj = $result->fetch_object();

                        $curSys['x'] = $obj->x;
                        $curSys['y'] = $obj->y;
                        $curSys['z'] = $obj->z;
                        $curSys['coordinates'] = $curSys['x'] . ',' . $curSys['y'] . ',' . $curSys['z'];
                        $curSys['users_own'] = true;
                    }
                }

                $result->close();

                /**
                 * If the system isn't in our database, add it to user_systems_own
                 */
                if ($exists === 0 && $oexists === 0) {
                    $stmt = "   INSERT INTO user_systems_own
                                (name, x, y, z)
                                VALUES
                                ('" . $curSys['esc_name'] . "',
                                '" . $curSys['x'] . "',
                                '" . $curSys['y'] . "',
                                '" . $curSys['z'] . "')";

                    $mysqli->query($stmt) or write_log($mysqli->error, __FILE__, __LINE__);
                }

                /**
                 * fetch previous system
                 */
                $prevSystem = edtbCommon('last_system', 'value');

                if ($prevSystem !== $cssystemname && !empty($cssystemname)) {
                    /**
                     * add system to user_visited_systems
                     */
                    $query = '  SELECT system_name
                                FROM user_visited_systems
                                ORDER BY id
                                DESC LIMIT 1';

                    $result = $mysqli->query($query) or write_log($mysqli->error, __FILE__, __LINE__);
                    $obj = $result->fetch_object();

                    $visitedOn = date('Y-m-d') . ' ' . $visitedTime;

                    if ($obj->system_name !== $curSys['name'] && !empty($curSys['name'])) {
                        $query = "  INSERT INTO user_visited_systems (system_name, visit)
                                    VALUES
                                    ('$sysName',
                                    '$visitedOn')";

                        $mysqli->query($query) or write_log($mysqli->error, __FILE__, __LINE__);

                        /**
                         * update coordinates for systems on jump
                         * in case of out dated coordinates or other changes in ED
                         * except where we have retrieved from our own DB
                         */
                        if ($curSys['users_own'] === false) {
                            $stmt = "   UPDATE user_systems_own
                                        SET
                                        x = '" . $curSys['x'] . "',
                                        y = '" . $curSys['y'] . "',
                                        z = '" . $curSys['z'] . "'
                                        WHERE name = '" . $curSys['esc_name'] . "'";

                            $mysqli->query($stmt) or write_log($mysqli->error, __FILE__, __LINE__);
                        }

                        /**
                         * export to EDSM
                         */
                        if ($settings['edsm_api_key'] !== '' &&
                            $settings['edsm_export'] === 'true' &&
                            $settings['edsm_cmdr_name'] !== '' &&
                            $curSys['users_own'] === false
                        ) {
                            // figure out the visited time in UTC
                            $dateUTC = new DateTime('now', new DateTimeZone('UTC'));
                            $visitedTimeSplit = explode(':', $visitedTime);
                            $dateLocal = new DateTime();
                            $dateUTC->setTime($dateUTC->format('G'), $visitedTimeSplit[1], $visitedTimeSplit[2]);
                            $visitedTimeUTC = $dateUTC->format('Y-m-d H:i:s');

                            $exportData = [
                                'commanderName' => $settings['edsm_cmdr_name'],
                                'apiKey' => $settings['edsm_api_key'],
                                'systemName' => $curSys['name'],
                                'dateVisited' => $visitedTimeUTC,
                                'fromSoftwareVersion' => $settings['edtb_version'],
                                'fromSoftware' => 'ED ToolBox',
                                'x' => $curSys['x'],
                                'y' => $curSys['y'],
                                'z' => $curSys['z'],
                            ];
                            $exportURL = 'https://www.edsm.net/api-logs-v1/set-log?';
                            $exportURL .= http_build_query($exportData);
                            $export = file_get_contents($exportURL);

                            if (!$export) {
                                write_log('EDSM export failed', __FILE__, __LINE__);
                            } else {
                                $exports = json_decode($export);

                                if ($exports->{'msgnum'} != '100') {
                                    write_log($export, __FILE__, __LINE__);
                                }
                            }
                        }

                        $newSystem = true;
                    }
                    $result->close();

                    // update latest system
                    edtbCommon('last_system', 'value', true, $curSys['name']);

                    $newSystem = true;
                } else {
                    $newSystem = false;
                }

                break;
            }
        }
    }
} else {
    write_log('Error: ' . $settings['log_dir'] . " doesn't exist or is not readable", __FILE__, __LINE__);
}
