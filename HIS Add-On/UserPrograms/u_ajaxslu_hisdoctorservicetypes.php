<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisdoctorservicetypes.php");

	
	if ($_GET["filler"] <> "") {
		$optfiller = explode(":",$_GET["filler"]);
		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
		echo @$filler;
	}
	
	loadu_hisdoctorservicetypes(array($_GET["u_doctorid"]),$_GET["value"]);

?>