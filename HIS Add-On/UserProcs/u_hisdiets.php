<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	$u_hissetupdata = getu_hissetup("U_DIETARY_CUTOFF1,U_DIETARY_CUTOFF2,U_DIETARY_CUTOFF3");
	
	if ($u_hissetupdata["U_DIETARY_CUTOFF1"]!="" && date('H:i')<=$u_hissetupdata["U_DIETARY_CUTOFF1"]) $page->setitem("u_meal","Breakfast");
	else if ($u_hissetupdata["U_DIETARY_CUTOFF2"]!="" && date('H:i')<=$u_hissetupdata["U_DIETARY_CUTOFF2"]) $page->setitem("u_meal","Lunch");
	else $page->setitem("u_meal","Dinner");
	
	$page->setitem("u_requestdate",currentdate());
	$page->setitem("u_requesttime",date('H:i'));
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
	global $objGrids;
	global $page;
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

$page->businessobject->items->setcfl("u_requestdate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_icdcode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_rvscode","OpenCFLfs()");

$page->businessobject->items->seteditable("u_reftype",false);
$page->businessobject->items->seteditable("u_refno",false);
$page->businessobject->items->seteditable("u_department",false);
$page->businessobject->items->seteditable("u_requestdate",false);
$page->businessobject->items->seteditable("u_requesttime",false);
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_bedno",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_religion",false);
$page->businessobject->items->seteditable("u_age",false);
$page->businessobject->items->seteditable("u_height_ft",false);
$page->businessobject->items->seteditable("u_height_in",false);
$page->businessobject->items->seteditable("u_weight_kg",false);

$addoptions = false;
$deleteoption = false;
?> 

