<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/BPLS Add-On/UserProcs/sls/u_bplsubdivisions.php");

	if ($_GET["filler"] <> "") {
		$optfiller = explode(":",$_GET["filler"]);
		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
		echo @$filler;
	}
	
	loadu_bplsubdivisions(array($_GET["u_subdivisions"]),$_GET["value"]);

?>