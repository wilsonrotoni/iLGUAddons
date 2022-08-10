<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/BPLS Add-On/UserProcs/sls/u_sanitarykinds.php");

	
	if ($_GET["filler"] <> "") {
		$optfiller = explode(":",$_GET["filler"]);
		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
		echo @$filler;
	}
	
	loadu_sanitarykinds(array($_GET["u_kind"]),$_GET["value"]);

?>