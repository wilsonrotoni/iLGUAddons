<?php

include_once("./sls/branchgroups.php");

$objLicMgr->checkAddon("GPS.Audit Trail","Standard",true);

$page->settings->boschema["T0"]["code"]["required"] = true;
$page->settings->boschema["T0"]["name"]["attributes"] = "disabled";

$objGrids[0]->columnwidth('u_fieldname',30);
$objGrids[0]->columninput("u_log","type","checkbox");
$objGrids[0]->columninput("u_log","value",1);
$objGrids[0]->columnalignment("u_log","center");
$objGrids[0]->dataentry = false;

$page->resize->addgridobject($objGrids[0],10,180);

$addoptions = false;
//$deleteoptions = false;
$objMaster->reportaction = "QS";

$page->businessobject->events->add->afterEdit("onAfterEditGPSAuditTrail");

function onGridColumnHeaderDraw($table,$column,&$ownerdraw) {
	global $objGrids;
	
	if($table=="T1" && $column=="u_log") {
		$ownerdraw = true;
		$checked["name"] = "checked";
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		$i = $objGrids[0]->columnnames[$column] - 1;
		$objGrids[0]->genGridDataHtml($column,false,$table,NULL,$objGrids[1]->data["df_" . $column . $table],$objGrids[0]->columns[$i]["lnkbtn"],$objGrids[0]->columns[$i]);
	}
} # end onGridColumnHeaderDraw

function onAfterEditGPSAuditTrail() {
	global $httpVars;
	global $page;
	global $series;
	global $objGrids;

	$page->settings->boschema["T0"]["code"]["attributes"] = "disabled";
} # end onAfterEditGPSAuditTrail

?>