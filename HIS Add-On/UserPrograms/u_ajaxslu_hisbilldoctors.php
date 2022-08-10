<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisbilldoctors.php");

	
	if ($_GET["filler"] <> "") {
		$optfiller = explode(":",$_GET["filler"]);
		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
		echo @$filler;
	}
	
	loadu_hisbilldoctors(array($_GET["u_billno"]),$_GET["value"]);

?>