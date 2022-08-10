<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSPMRS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSPMRS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSPMRS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSPMRS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSPMRS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSPMRS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSPMRS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSPMRS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSPMRS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSPMRS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSPMRS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSPMRS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSPMRS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSPMRS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSPMRS");
function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
	global $page;
	global $objGrids;
	if ($table=="T1" && $column=="u_selected" && $page->getitemstring("docstatus")=="O") {
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
}
function onCustomActionGPSPMRS($action) {
	return true;
}

function onBeforeDefaultGPSPMRS() { 
	return true;
}

function onAfterDefaultGPSPMRS() { 
        global $page;
	$page->setitem("u_year",date('Y'));
	$page->setitem("u_date",currentdate());
	$page->setitem("docstatus","O");
	return true;
}

function onPrepareAddGPSPMRS(&$override) { 
	return true;
}

function onBeforeAddGPSPMRS() { 
	return true;
}

function onAfterAddGPSPMRS() { 
	return true;
}

function onPrepareEditGPSPMRS(&$override) { 
	return true;
}

function onBeforeEditGPSPMRS() { 
	return true;
}

function onAfterEditGPSPMRS() { 
	return true;
}

function onPrepareUpdateGPSPMRS(&$override) { 
	return true;
}

function onBeforeUpdateGPSPMRS() { 
	return true;
}

function onAfterUpdateGPSPMRS() { 
	return true;
}

function onPrepareDeleteGPSPMRS(&$override) { 
	return true;
}

function onBeforeDeleteGPSPMRS() { 
	return true;
}

function onAfterDeleteGPSPMRS() { 
	return true;
}

$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("u_custname",false);
$page->businessobject->items->seteditable("u_stallno",false);
$page->businessobject->items->seteditable("u_bldg",false);
$page->businessobject->items->seteditable("u_totalamount",false);
$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");

$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->dataentry = false;

$page->toolbar->setaction("update",false);
$objMaster->reportaction = "QS";
?> 

