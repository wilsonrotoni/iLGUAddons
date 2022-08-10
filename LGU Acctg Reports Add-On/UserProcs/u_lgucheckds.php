<?php

include_once("./sls/housebankaccounts.php"); 
include_once("./sls/banks.php");

//$page->businessobject->events->add->customAction("onCustomActionLGUGPSAcctg");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUGPSAcctg");
//$page->businessobject->events->add->afterDefault("onAfterDefaultLGUGPSAcctg");

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

function onCustomActionLGUGPSAcctgReports($action) {
	return true;
}

function onBeforeDefaultLGUGPSAcctgReports() { 
	return true;
}

function onAfterDefaultLGUGPSAcctgReports() {
	global $page;
	//$page->setitem("u_date",currentdate());
	$page->setitem("u_refundglacctno","1-01-01-010");
	$page->setitem("u_refundglacctname","Cash Local Treasury");
	return true;
}

function onPrepareAddLGUGPSAcctgReports(&$override) { 
	return true;
}

function onBeforeAddLGUGPSAcctgReports() { 
	return true;
}

function onAfterAddLGUGPSAcctgReports() { 
	return true;
}

function onPrepareEditLGUGPSAcctgReports(&$override) { 
	return true;
}

function onBeforeEditLGUGPSAcctgReports() { 
	return true;
}

function onAfterEditLGUGPSAcctgReports() { 
	return true;
}

function onPrepareUpdateLGUGPSAcctgReports(&$override) { 
	return true;
}

function onBeforeUpdateLGUGPSAcctgReports() { 
	return true;
}

function onAfterUpdateLGUGPSAcctgReports() { 
	return true;
}

function onPrepareDeleteLGUGPSAcctgReports(&$override) { 
	return true;
}

function onBeforeDeleteLGUGPSAcctgReports() { 
	return true;
}

function onAfterDeleteLGUGPSAcctgReports() { 
	return true;
}

$objGrids[3]->columnlnkbtn("u_osno","openobligation()");

?> 

