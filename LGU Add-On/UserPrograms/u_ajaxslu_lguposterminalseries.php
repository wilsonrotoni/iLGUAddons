<?php

	include_once("../common/classes/recordset.php");
 	include_once("../Addons/GPS/LGU Add-On/UserProcs/sls/u_lguposterminalseries.php");

//	if ($_GET["filler"] <> "") {
//		$optfiller = explode(":",$_GET["filler"]);
//		$filler = "<option value=\"" . $optfiller[0]  . "\">" . $optfiller[1] . "</option>";
//		echo @$filler;
//	}
	
	loadu_lguposterminalseries(array($_GET["u_cashier"]),$_GET["value"],$_GET["profitcenter"]);
?>