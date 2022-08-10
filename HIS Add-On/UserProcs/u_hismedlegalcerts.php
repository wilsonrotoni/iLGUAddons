<?php

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
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
	global $page;
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	
	$page->setitem("u_docdate",currentdate());
	
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
	global $objConnection;
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

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_icdcode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_rvscode","OpenCFLfs()");

$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_address",false);
$page->businessobject->items->seteditable("u_gender",false);
$page->businessobject->items->seteditable("u_age",false);
$page->businessobject->items->seteditable("u_civilstatus",false);
$page->businessobject->items->seteditable("u_roomno",false);
$page->businessobject->items->seteditable("u_startdate",false);
$page->businessobject->items->seteditable("u_enddate",false);
$page->businessobject->items->seteditable("u_endtime",false);
$page->businessobject->items->seteditable("u_initialremarks",false);
$page->businessobject->items->seteditable("u_orproc",false);
//$page->businessobject->items->seteditable("u_doctorid",false);
//$page->businessobject->items->seteditable("u_reftype",false);

$addoptions = false;
$deleteoption = false;

?> 

