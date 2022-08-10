<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/RPTAS Add-On/UserProcs/sls/u_faas1aclass.php");

	
	if ($_GET["filler"] <> "") {
		$optfiller = explode(":",$_GET["filler"]);
		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
		echo @$filler;
	}
	
	loadu_faas1aclass(array($_GET["u_gryear"]),$_GET["value"]);

?>