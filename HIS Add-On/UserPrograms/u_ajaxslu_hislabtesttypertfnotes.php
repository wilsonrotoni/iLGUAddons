<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hislabtesttypertfnotes.php");

	
	if ($_GET["filler"] <> "") {
		$optfiller = explode(":",$_GET["filler"]);
		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
		echo @$filler;
	}

	loadu_hislabtesttypertfnotes(array($_GET["u_type"],$_GET["u_doctorid"]),$_GET["value"]);

?>