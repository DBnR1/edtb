<?php
/*
*    ED ToolBox, a companion web app for the video game Elite Dangerous
*    (C) 1984 - 2015 Frontier Developments Plc.
*    ED ToolBox or its creator are not affiliated with Frontier Developments Plc.
*
*    Copyright (C) 2016 Mauri Kujala (contact@edtb.xyz)
*
*    This program is free software; you can redistribute it and/or
*    modify it under the terms of the GNU General Public License
*    as published by the Free Software Foundation; either version 2
*    of the License, or (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once("" . $_SERVER["DOCUMENT_ROOT"] . "/source/functions.php");
Header("content-type: application/json");

if (!is_numeric($coordx))
{
	// get last known coordinates
	$last_coords = last_known_system();

	$coordx = $last_coords["x"];
	$coordy = $last_coords["y"];
	$coordz = $last_coords["z"];
}

$data = "";
$data_start = '{"categories":{';
if ($settings["galmap_show_visited_systems"] == "true")
{
	$data_start .= '"Allegiances":{"1":{"name":"Empire","color":"e7d884"},"2":{"name":"Federation","color":"FFF8E6"},"3":{"name":"Alliance","color":"09b4f4"},"20":{"name":"Anarchy","color":"B704E3"},"21":{"name":"Independent","color":"34242F"}},';
}
$data_start .= '"Other":{"5":{"name":"Current location","color":"FF0000"},';

if ($settings["galmap_show_bookmarks"] == "true")
{
	$data_start .= '"6":{"name":"Bookmarked systems","color":"F7E707"},';
}
if ($settings["galmap_show_pois"] == "true")
{
	$data_start .= '"7":{"name":"Points of interest, unvisited","color":"E87C09"},"8":{"name":"Points of interest, visited","color":"00FF1E"},';
}
if ($settings["galmap_show_rares"] == "true")
{
	$data_start .= '"10":{"name":"Rare commodities","color":"8B9F63"},';
}
$data_start .= '"11":{"name":"Logged systems","color":"2938F8"},"12":{"name":"Rest","color":"8c8c8c"}}}, "systems":[';

$last_row = "";

// fetch visited systems data for the map
if ($settings["galmap_show_visited_systems"] == "true")
{
	$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT
														user_visited_systems.system_name AS system_name, user_visited_systems.visit,
														edtb_systems.x, edtb_systems.y, edtb_systems.z, edtb_systems.id AS sysid, edtb_systems.allegiance
														FROM user_visited_systems
														LEFT JOIN edtb_systems ON user_visited_systems.system_name = edtb_systems.name
														GROUP BY user_visited_systems.system_name
														ORDER BY user_visited_systems.visit ASC");

	while ($row = mysqli_fetch_array($result))
	{
		// coordinates for distance calculations
		$vs_coordx = $row["x"];
		$vs_coordy = $row["y"];
		$vs_coordz = $row["z"];

		$name = $row["system_name"];
		$sysid = $row["sysid"];

		/*
		*	if coords are not set, see if user has calculated them
		*/

		if (!is_numeric($vs_coordx) && !is_numeric($vs_coordy) && !is_numeric($vs_coordz))
		{
			$cb_res = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT x, y, z
																FROM user_systems_own
																WHERE name = '" . mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $name) . "'
																LIMIT 1");

			$cb_arr = mysqli_fetch_assoc($cb_res);

			$vs_coordx = $cb_arr["x"] == "" ? "" : $cb_arr["x"];
			$vs_coordy = $cb_arr["y"] == "" ? "" : $cb_arr["y"];
			$vs_coordz = $cb_arr["z"] == "" ? "" : $cb_arr["z"];
		}

		if ($vs_coordx != "")
		{
			$info = "";
			$allegiance = $row["allegiance"];
			$visit = $row["visit"];
			$visit_og = $row["visit"];

			if ($allegiance == "Federation")
			{
				$cat = ',"cat": [2]';
			}
			else if ($allegiance == "Alliance")
			{
				$cat = ',"cat": [3]';
			}
			else if ($allegiance == "Empire")
			{
				$cat = ',"cat": [1]';
			}
			else if ($allegiance == "Anarchy")
			{
				$cat = ',"cat": [20]';
			}
			else if ($allegiance == "Independent")
			{
				$cat = ',"cat": [21]';
			}
			else
			{
				$cat = ',"cat": [12]';
			}

			if ($name == $current_system)
			{
				$cat = ',"cat": [5]';
			}

			if ($coordx != "")
			{
				$distance_from_current = sqrt(pow(($vs_coordx-($coordx)), 2)+pow(($vs_coordy-($coordy)), 2)+pow(($vs_coordz-($coordz)), 2));
			}
			else
			{
				$distance_from_current = 0;
			}

			$logged = mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id
																				FROM user_log
																				WHERE system_id = '" . $sysid . "'
																				LIMIT 1"));

			if ($logged == 1 && $name != $current_system)
			{
				$cat = ',"cat": [11]';
			}

			$info .= '<b>Distance</b><br />' . number_format($distance_from_current, 2) . ' ly<br /><br />';

			if (isset($visit))
			{
				$visit = date_create($visit);
				$visit_date = date_modify($visit, "+1286 years");

				$visit = date_format($visit_date, "d.m.Y, H:i");

				$visit_unix = strtotime($visit_og);
				$visit_ago = get_timeago($visit_unix, true);

				$info .= '<b>First visit</b><br />' . $visit . ' (' . $visit_ago . ')<br />';
			}

			if (isset($name) && isset($vs_coordx))
			{
				$data = '{"name": "' . $name  . '"' . $cat . ',"coords": {"x": ' . $vs_coordx . ',"y": ' . $vs_coordy . ',"z": ' . $vs_coordz . '},"infos":' . json_encode($info) . '}' . $last_row . '';
			}
			else
			{
				$data = $last_row;
			}

			$last_row = "," . $data . "";
		}
	}
}

// fetch point of interest data for the map
if ($settings["galmap_show_pois"] == "true")
{
	$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT user_poi.poi_name, user_poi.system_name,
														user_poi.x, user_poi.y, user_poi.z, user_poi.text,
														user_poi_categories.name AS category_name
														FROM user_poi
														LEFT JOIN user_poi_categories ON user_poi.category_id = user_poi_categories.id
														WHERE user_poi.x != ''");

	while ($row = mysqli_fetch_array($result))
	{
		$info = "";
		$name = $row["system_name"];
		//$disp_name = $row["poi_name"] != "" ? $row["poi_name"] : $row["system_name"];
		$disp_name = $row["system_name"];
		$poi_name = $row["poi_name"];
		$text = $row["text"];
		$category_name = $row["category_name"];

		$poi_coordx = $row["x"];
		$poi_coordy = $row["y"];
		$poi_coordz = $row["z"];

		if ($coordx != "")
		{
			$distance_from_current = sqrt(pow(($poi_coordx-($coordx)), 2)+pow(($poi_coordy-($coordy)), 2)+pow(($poi_coordz-($coordz)), 2));
		}
		else
		{
			$distance_from_current = 0;
		}

		$visitres = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, visit
																FROM user_visited_systems
																WHERE system_name = '" . mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $name) . "'
																ORDER BY visit ASC
																LIMIT 1");
		$visited = mysqli_num_rows($visitres);

		if ($visited > 0)
		{
			$cat = ',"cat": [8]';
		}
		else
		{
			$cat = ',"cat": [7]';
		}

		$info .= '<b>Distance</b><br />' . number_format($distance_from_current, 2) . ' ly<br /><br />';
		$info .= $category_name == "" ? "" : '<b>Category</b><br />' . $category_name . '<br /><br />';
		$info .= $poi_name == "" ? "" : '<b>Name</b><br />' . $poi_name . '<br /><br />';
		$info .= $text == "" ? "" : '<b>Comment</b><br />' . $text . '<br /><br />';

		if ($visited > 0)
		{
			$visarr = mysqli_fetch_assoc($visitres);

			$visit = $visarr["visit"];
			$visit_og = $visarr["visit"];

			if (isset($visit))
			{
				$visit = date_create($visit);
				$visit_date = date_modify($visit, "+1286 years");

				$visit = date_format($visit_date, "d.m.Y, H:i");

				$visit_unix = strtotime($visit_og);
				$visit_ago = get_timeago($visit_unix, true);

				$info .= '<b>First visit</b><br />' . $visit . ' (' . $visit_ago . ')<br /><br />';
			}
		}

		$data = '{"name": "' . $disp_name  . '"' . $cat . ',"coords": {"x": ' . $poi_coordx . ',"y": ' . $poi_coordy . ',"z": ' . $poi_coordz . '},"infos":' . json_encode($info) . '}' . $last_row . '';

		$last_row = "," . $data . "";
	}
}

// fetch bookmark data for the map
if ($settings["galmap_show_bookmarks"] == "true")
{
	$bm_result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT user_bookmarks.comment, user_bookmarks.added_on,
															edtb_systems.name AS system_name, edtb_systems.x, edtb_systems.y, edtb_systems.z,
															user_bm_categories.name AS category_name
															FROM user_bookmarks
															LEFT JOIN edtb_systems ON user_bookmarks.system_id = edtb_systems.id
															LEFT JOIN user_bm_categories ON user_bookmarks.category_id = user_bm_categories.id
															WHERE edtb_systems.x != ''");


	while ($bm_row = mysqli_fetch_array($bm_result))
	{
		$info = "";
		$bm_system_name = $bm_row["system_name"];
		$bm_comment = $bm_row["comment"];
		$bm_added_on = $bm_row["added_on"];
		$bm_category_name = $bm_row["category_name"];

		// coordinates for distance calculations
		$bm_coordx = $bm_row["x"];
		$bm_coordy = $bm_row["y"];
		$bm_coordz = $bm_row["z"];

		if ($coordx != "")
		{
			$distance_from_current = sqrt(pow(($bm_coordx-($coordx)), 2)+pow(($bm_coordy-($coordy)), 2)+pow(($bm_coordz-($coordz)), 2));
		}
		else
		{
			$distance_from_current = 0;
		}

		$info .= '<b>Distance</b><br />' . number_format($distance_from_current, 2) . ' ly<br /><br />';

		if (isset($bm_added_on))
		{
			$bm_added_on_og = $bm_added_on;
			$bm_added_on = gmdate("Y-m-d\TH:i:s\Z", $bm_added_on);
			$bm_added_on = date_create($bm_added_on);
			$bm_added_on_date = date_modify($bm_added_on, "+1286 years");

			$bm_added_on = date_format($bm_added_on_date, "d.m.Y, H:i");

			$bm_added_on_ago = get_timeago($bm_added_on_og, true);

			$info .= '<b>Bookmarked on</b><br />' . $bm_added_on . ' (' . $bm_added_on_ago . ')<br /><br />';
		}
		$info .= $bm_category_name == "" ? "" : '<b>Category</b><br />' . $bm_category_name . '<br /><br />';
		$info .= $bm_comment == "" ? "" : '<b>Comment</b><br />' . $bm_comment . '<br /><br />';

		$data = '{"name": "' . $bm_system_name  . '","cat": [6],"coords": {"x": ' . $bm_coordx . ',"y": ' . $bm_coordy . ',"z": ' . $bm_coordz . '},"infos":' . json_encode($info) . '}' . $last_row . '';
		$last_row = "," . $data . "";
	}
}

// fetch rares data for the map
if ($settings["galmap_show_rares"] == "true")
{
	$rare_result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT
																edtb_rares.item, edtb_rares.station, edtb_rares.system_name, edtb_rares.ls_to_star,
																edtb_systems.x, edtb_systems.y, edtb_systems.z
																FROM edtb_rares
																LEFT JOIN edtb_systems ON edtb_rares.system_name = edtb_systems.name
																WHERE edtb_rares.system_name != ''");

	while ($rare_row = mysqli_fetch_array($rare_result))
	{
		$info = "";
		$rare_item = $rare_row["item"];
		$rare_station = $rare_row["station"];
		$rare_system = $rare_row["system_name"];
		$rare_dist_to_star = number_format($rare_row["ls_to_star"]);

		//$rare_disp_name = "" . $rare_item . " - " . $rare_system . "";
		$rare_disp_name = $rare_system;

		// coordinates for distance calculations
		$rare_coordx = $rare_row["x"];
		$rare_coordy = $rare_row["y"];
		$rare_coordz = $rare_row["z"];

		if ($coordx != "")
		{
			$rare_distance_from_current = sqrt(pow(($rare_coordx-($coordx)), 2)+pow(($rare_coordy-($coordy)), 2)+pow(($rare_coordz-($coordz)), 2));
		}
		else
		{
			$rare_distance_from_current = 0;
		}

		$info .= '<b>Distance</b><br />' . number_format($rare_distance_from_current, 2) . ' ly<br /><br />';
		$info .= '<b>Rare commodity</b><br />' . $rare_item . '<br /><br />';
		$info .= '<b>Station</b><br />' . $rare_station . '<br /><br />';
		$info .= '<b>Distance from star</b><br />' . number_format($rare_dist_to_star) . ' ls';

		$data = '{"name": "' . $rare_disp_name  . '","cat": [10],"coords": {"x": ' . $rare_coordx . ',"y": ' . $rare_coordy . ',"z": ' . $rare_coordz . '},"infos":"' . $info . '"}' . $last_row . '';

		$last_row = "," . $data . "";
	}
}

$data = "" . $data_start . "" . $data . "]}";
$map_json = "" . $_SERVER["DOCUMENT_ROOT"] . "/map_points.json";
file_put_contents($map_json, $data);

((is_null($___mysqli_res = mysqli_close($link))) ? false : $___mysqli_res);