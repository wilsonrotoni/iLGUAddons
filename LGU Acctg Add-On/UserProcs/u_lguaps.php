<?php
 
 include_once("./sls/housebankaccounts.php"); 
include_once("./sls/banks.php");

//$page->businessobject->events->add->customAction("onCustomActionLGUGPSAcctg");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUGPSAcctg");
$page->businessobject->events->add->afterDefault("onAfterDefaultLGUGPSAcctg");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUGPSAcctg");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUGPSAcctg");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUGPSAcctg");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUGPSAcctg");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUGPSAcctg");
//$page->businessobject->events->add->afterEdit("onAfterEditLGUGPSAcctg");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateLGUGPSAcctg");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateLGUGPSAcctg");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateLGUGPSAcctg");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteLGUGPSAcctg");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteLGUGPSAcctg");
//$page->businessobject->events->add->afterDelete("onAfterDeleteLGUGPSAcctg");

function onCustomActionLGUGPSAcctg($action) {
	return true;
}

function onBeforeDefaultLGUGPSAcctg() { 
	return true;
}

function onAfterDefaultLGUGPSAcctg() {
	global $page;
	//$page->setitem("u_date",currentdate());
	return true;
}

function onPrepareAddLGUGPSAcctg(&$override) { 
	return true;
}

function onBeforeAddLGUGPSAcctg() { 
	return true;
}

function onAfterAddLGUGPSAcctg() { 
	return true;
}

function onPrepareEditLGUGPSAcctg(&$override) { 
	return true;
}

function onBeforeEditLGUGPSAcctg() { 
	return true;
}

function onAfterEditLGUGPSAcctg() { 
	return true;
}

function onPrepareUpdateLGUGPSAcctg(&$override) { 
	return true;
}

function onBeforeUpdateLGUGPSAcctg() { 
	return true;
}

function onAfterUpdateLGUGPSAcctg() { 
	return true;
}

function onPrepareDeleteLGUGPSAcctg(&$override) { 
	return true;
}

function onBeforeDeleteLGUGPSAcctg() { 
	return true;
}

function onAfterDeleteLGUGPSAcctg() { 
	return true;
}

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_bpcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcenter","OpenCFLfs()");
$page->businessobject->items->setcfl("u_osno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);

$objGrids[0]->columncfl("u_glacctno","OpenCFLfs()");
$objGrids[0]->columncfl("u_glacctname","OpenCFLfs()");
$objGrids[0]->columncfl("u_slcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_sldesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_glacctno",20);
$objGrids[0]->columnwidth("u_evat",5);

$objMaster->reportaction = "QS";
?> 

