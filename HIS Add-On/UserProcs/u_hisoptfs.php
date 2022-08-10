<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSProjects");

$postdata = array();
function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $postdata;
	$postdata["u_refno"] = $page->getitemstring("u_refno");
	$postdata["u_patientid"] = $page->getitemstring("u_patientid");
	$postdata["u_patientname"] = $page->getitemstring("u_patientname");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $postdata;
	$page->setitem("u_refno",$postdata["u_refno"]);
	$page->setitem("u_patientid",$postdata["u_patientid"]);
	$page->setitem("u_patientname",$postdata["u_patientname"]);
	retrievePatientPendingItemsGPSHIS("OP",$postdata["u_refno"]);
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
	return true;
}

function onAfterAddGPSHIS() { 
	return true;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	return true;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	return true;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

function onDrawGridColumnLabelGPSProjects($tablename,$column,$row,&$label) {
	switch ($tablename) {
		default:
			if (setGridColumnLabelPatientPendingItemsGPSHIS($tablename,$column,$row,$label)) break;	
			break;
	}
}

$page->businessobject->items->setcfl("u_roomno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bedno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_refno",false);
$page->businessobject->items->seteditable("u_rateuom",false);
$page->businessobject->items->seteditable("u_department",false);
$page->businessobject->items->seteditable("u_pricelist",false);

$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);

include_once("../Addons/GPS/HIS Add-On/UserProcs/utils/u_hispatientpendingitems.php");

$addoptions = false;
?> 

