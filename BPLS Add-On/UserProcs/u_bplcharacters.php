<?php
 
include_once("../Addons/GPS/BPLS Add-On/UserProcs/sls/u_sanitarykinds.php");
//$page->businessobject->events->add->customAction("onCustomActionGPSBPLS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSBPLS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSBPLS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSBPLS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSBPLS");

function onCustomActionGPSBPLS($action) {
	return true;
}

function onBeforeDefaultGPSBPLS() { 
	return true;
}

function onAfterDefaultGPSBPLS() { 
	return true;
}

function onPrepareAddGPSBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSBPLS() { 
	return true;
}

function onAfterAddGPSBPLS() { 
	return true;
}

function onPrepareEditGPSBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSBPLS() { 
	return true;
}

function onAfterEditGPSBPLS() { 
	return true;
}

function onPrepareUpdateGPSBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSBPLS() { 
	return true;
}

function onAfterUpdateGPSBPLS() { 
	return true;
}

function onPrepareDeleteGPSBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSBPLS() { 
	return true;
}

function onAfterDeleteGPSBPLS() { 
	return true;
}

function onDrawGridColumnLabelGPSBPLS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T2":
			if ($column=="u_category") {
				$objRs->queryopen("select name from u_bplcategories where code='".$label."'");
				if ($objRs->queryfetchrow()) $label = $objRs->fields[0];
			}	
			break;
	}
}
	
$objGrids[0]->height= 160;
$objGrids[0]->columntitle("u_amount","Sanitary Fee");
$objGrids[1]->columntitle("u_amount","Fee");

$objGrids[2]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[2]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[2]->columnwidth("u_feecode",15);
$objGrids[2]->columnwidth("u_feedesc",35);
$objGrids[2]->columnwidth("u_provincial",7);
$objGrids[2]->columnwidth("u_municipal",7);
$objGrids[2]->columnwidth("u_barangay",7);
$objGrids[2]->width= 700;

$addoptions = false;

?> 

