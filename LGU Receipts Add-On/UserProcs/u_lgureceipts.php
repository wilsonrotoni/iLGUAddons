<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUReceipts");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUReceipts");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUReceipts");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUReceipts");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUReceipts");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUReceipts");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUReceipts");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUReceipts");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUReceipts");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUReceipts");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUReceipts");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUReceipts");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUReceipts");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUReceipts");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUReceipts");

function onCustomActionGPSLGUReceipts($action) {
	return true;
}

function onBeforeDefaultGPSLGUReceipts() { 
	return true;
}

function onAfterDefaultGPSLGUReceipts() { 
        global $objConnection;
	global $page;
	global $objGrids;
	$page->setitem("u_purchaseddate",currentdate());
	return true;
}

function onPrepareAddGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeAddGPSLGUReceipts() { 
	return true;
}

function onAfterAddGPSLGUReceipts() { 
	return true;
}

function onPrepareEditGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeEditGPSLGUReceipts() { 
	return true;
}

function onAfterEditGPSLGUReceipts() { 
	return true;
}

function onPrepareUpdateGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUReceipts() { 
	return true;
}

function onAfterUpdateGPSLGUReceipts() { 
	return true;
}

function onPrepareDeleteGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUReceipts() { 
	return true;
}

function onAfterDeleteGPSLGUReceipts() { 
	return true;
}

$page->businessobject->items->setcfl("u_purchaseddate","Calendar");
$page->businessobject->items->seteditable("u_noofreceipt",false);
$page->businessobject->items->seteditable("u_receiptto",false);
$objGrids[0]->columntitle("u_form","Accountable Form");
$objGrids[0]->columnvisibility("u_docno",false);
$objGrids[0]->columnvisibility("u_refno",false);
$objGrids[0]->columnvisibility("u_issuedto",false);
$objGrids[0]->columnvisibility("u_available",false);
$objGrids[0]->columnvisibility("u_issuedocno",false);
$objGrids[0]->columnvisibility("u_issuectr",false);
$objGrids[0]->columnvisibility("u_ctr",false);
$objGrids[0]->columnattributes("u_noofreceipt","disabled");
$objGrids[0]->columnwidth("u_issuedto",30);
$objGrids[0]->columnwidth("u_receiptfrm",15);
$objGrids[0]->columnwidth("u_receiptto",15);
$objGrids[0]->columnwidth("u_docno",15);
$objGrids[0]->columnwidth("u_refno",15);
$objGrids[0]->columnwidth("u_form",15);
$objGrids[0]->columnwidth("u_purchaseddate",15);
$objGrids[0]->width = 900;

$objGrids[0]->columncfl("u_purchaseddate","Calendar");

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

$pageHeader = "Official Receipt Master Data"
?> 

