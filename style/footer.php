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
			?>
			<div class="rightpanel-content">
				<?php
				// add and edit log
				include_once("" . $_SERVER["DOCUMENT_ROOT"] . "/add/log.php");
				//

				// add and edit stations
				//include_once("/add/stationData.php");
				//

				// calculate coordinates
				include_once("" . $_SERVER["DOCUMENT_ROOT"] . "/add/coord.php");
				//

				// edit system
				//include_once("/add/systemData.php");
				//

				// add/edit bookmakrs
				include_once("" . $_SERVER["DOCUMENT_ROOT"] . "/add/bookmark.php");
				//
				((is_null($___mysqli_res = mysqli_close($link))) ? false : $___mysqli_res);
				?>
				<!-- calculate distances -->
				<div class="input" id="distance" style="text-align:center;">
					<div class="input-inner">
						<div class="suggestions" id="suggestions_2" style="margin-left:8px;margin-top:110px;"></div>
						<div class="suggestions" id="suggestions_6" style="margin-left:8px;margin-top:232px;"></div>
						<table>
							<tr>
								<td class="systeminfo_station_name" colspan="2">Calculate Distances</td>
							</tr>
							<tr>
								<td class="station_info_price_info2" style="width:99%;">
									<input class="textbox" type="text" name="from_system_name" placeholder="From system" id="system_2" style="width:97%;" oninput="showResult(this.value, '2')" />
								</td>
								<td class="station_info_price_info2">
									<input class="textbox" type="text" name="from_coor[]" placeholder="From x.x" id="coordsx_2" />
									<input class="textbox" type="text" name="from_coor[]" placeholder="From y.y" id="coordsy_2" />
									<input class="textbox" type="text" name="from_coor[]" placeholder="From z.z" id="coordsz_2" />
								</td>
							</tr>
							<tr>
								<td class="station_info_price_info2">
									<input class="textbox" type="text" name="to_system_name" placeholder="To system" id="system_6" style="width:97%;" oninput="showResult(this.value, '6')" />
								</td>
								<td class="station_info_price_info2">
									<input class="textbox" type="text" name="to_coor[]" placeholder="To x.x" id="coordsx_6" />
									<input class="textbox" type="text" name="to_coor[]" placeholder="To y.y" id="coordsy_6" />
									<input class="textbox" type="text" name="to_coor[]" placeholder="To z.z" id="coordsz_6" />
								</td>
							</tr>
							<tr>
								<td colspan="2" class="station_info_price_info2">
									<input class="textbox" type="text" name="displ" placeholder="Select two systems to calculate the distance between them" id="dist_display" style="width:98%;text-align:center;" readonly="readonly" />
								</td>
							</tr>
							<tr>
								<td colspan="2" class="station_info_price_info2">
									<input class="button" type="submit" value="Calculate distance" onclick='calcDist(document.getElementById("coordsx_2").value, document.getElementById("coordsy_2").value, document.getElementById("coordsz_2").value, document.getElementById("coordsx_6").value, document.getElementById("coordsy_6").value,document.getElementById("coordsz_6").value,  document.getElementById("system_2").value, document.getElementById("system_6").value);' />
								</td>
							</tr>
						</table>
					</div>
				</div>
				<!-- search systems and stations-->
				<div class="input" id="search_system" style="text-align:center;">
					<div class="input-inner">
						<div class="suggestions" id="suggestions_8" style="margin-left:8px;margin-top:73px;"></div>
						<div class="suggestions" id="suggestions_9" style="margin-left:223px;margin-top:73px;"></div>
						<table>
							<tr>
								<td class="systeminfo_station_name" colspan="2">Search Systems and Stations</td>
							</tr>
							<tr>
								<td class="station_info_price_info2" style="width:200px;">
									<input class="textbox" type="text" name="system_name" placeholder="System" id="system_22" style="width:96%;" oninput="showResult(this.value, '8', 'yes')" autofocus="autofocus" />
								</td>
								<td class="station_info_price_info2" style="width:200px;">
									<input class="textbox" type="text" name="station_name" placeholder="Station" id="station_1" style="width:96%;" oninput="showResult(this.value, '9', 'yes', 'yes')" />
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- tooltips -->
		<div class="tooltip" id="help_addlog" style="position:fixed;top:70px;left:370px;">
			<img class="callout" alt="co" src="style/img/callout_black.gif" style="top:-14px;left:8px;" />Click the Elite emblem to add log entries
		</div>
		<div class="tooltip" id="help_edit" style="position:fixed;top:150px;left:370px;">
			<img class="callout" alt="co" src="style/img/callout_black.gif" style="top:-14px;left:20px;" />Click the date to open editing window
		</div>
		<div class="tooltip" id="help_search" style="position:fixed;top:70px;left:440px;">
			<img class="callout" alt="co" src="style/img/callout_black.gif" style="top:-14px;left:20px;" />Click the page title text to open a search dialog for systems and stations
		</div>
		<div class="tooltip" id="help_bm" style="position:fixed;top:70px;left:6px;">
			<img class="callout" alt="co" src="style/img/callout_black.gif" style="top:-14px;left:5px;" />Click the allegiance icon to bookmark system
		</div>
		<div class="tooltip" id="help_calc" style="position:fixed;top:70px;left:50px;">
			<img class="callout" alt="co" src="style/img/callout_black.gif" style="top:-14px;left:20px;" />Click the system name to calculate distances
		</div>
		<div class="tooltip" id="help_links" style="position:fixed;top:70px;left:250px;">
			<img class="callout" alt="co" src="style/img/callout_black.gif" style="top:-14px;left:20px;" />Click the date and time to open external links
		</div>
		<!-- -->
    </body>
</html>
