<?php
/*
*    ED ToolBox, a companion web app for the video game Elite Dangerous
*    (C) 1984 - 2015 Frontier Developments Plc.
*    ED ToolBox or its creator are not affiliated with Frontier Developments Plc.
*
*    Copyright (C) 2015 Mauri Kujala (contact@edtb.xyz)
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
$notify = "";
$pagetitle = "Variable Editor";
require_once("" . $_SERVER["DOCUMENT_ROOT"] . "/style/header.php");
if (isset($_POST["code"]))
{
	$code = $_POST["code"];
	if (file_put_contents($ini_file, $code))
	{
		$notify = "<div class='notify_success'>Settings succesfully edited.</div>";
	}
	else
	{
		$notify = "<div class='notify_deleted'>Edit unsuccesfull.</div>";
	}
}

$pagetitle = "INI Editor";
$ini = file_get_contents($ini_file);
?>
<!-- codemirror -->
<link type="text/css" rel="stylesheet" href="/source/codemirror/lib/codemirror.css">
<script type="text/javascript" src="/source/codemirror/lib/codemirror.js"></script>
<script type="text/javascript" src="/source/codemirror/mode/properties/properties.js"></script>
<?php echo $notify;?>
<div class="entries">
	<div class="entries_inner" style="margin-bottom:20px;">
		<form method="post" action="ini_editor.php">
			<textarea id="codes" name="code"><?php echo $ini?></textarea>
			<input type="submit" class="button" value="Submit changes" />
		</form>
		<script type="text/javascript">
			var editor = CodeMirror.fromTextArea(document.getElementById("codes"),
			{
				lineNumbers: true,
				mode: "text/x-ini"
			});
		</script>
	</div>
</div>
<?php
require_once("../style/footer.php");