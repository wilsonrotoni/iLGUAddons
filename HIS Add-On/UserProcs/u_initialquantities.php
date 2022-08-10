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

$u_trxtype="";

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	$u_trxtype = $page->getitemstring("u_trxtype");
	return true;
}

function onAfterDefaultGPSHIS() {
	global $page;
	global $u_trxtype;

	$page->setitem("u_trxtype",$u_trxtype);
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

$page->businessobject->items->seteditable("docstatus",false);

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_suppno","OpenCFLfs()");

$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columntitle("u_varqtypu","Adj (PU)");
$objGrids[0]->columntitle("u_varqtyiu","Adj (IU)");
$objGrids[0]->columnwidth("u_qtyperuom",7);
$objGrids[0]->columnwidth("u_itemcode",8);
$objGrids[0]->columnwidth("u_itemdesc",35);
$objGrids[0]->columnwidth("u_itemclass",15);
$objGrids[0]->columnwidth("u_sysqtypu",10);
$objGrids[0]->columnwidth("u_sysqtyiu",10);
$objGrids[0]->columnwidth("u_actqtypu",10);
$objGrids[0]->columnwidth("u_actqtyiu",10);
$objGrids[0]->columnwidth("u_varqtypu",10);
$objGrids[0]->columnwidth("u_varqtyiu",10);
$objGrids[0]->columnwidth("u_sysuompu",6);
$objGrids[0]->columnwidth("u_sysuomiu",6);
$objGrids[0]->columnwidth("u_actuompu",6);
$objGrids[0]->columnwidth("u_actuomiu",6);
$objGrids[0]->columnwidth("u_varuompu",6);
$objGrids[0]->columnwidth("u_varuomiu",6);
$objGrids[0]->columnwidth("u_glacctno",15);
$objGrids[0]->columninput("u_actqtypu","type","text");
$objGrids[0]->columninput("u_actqtyiu","type","text");
$objGrids[0]->columninput("u_glacctno","type","text");
$objGrids[0]->columninput("u_itemcost","type","text");
$objGrids[0]->columnlnkbtn("u_itemcode","OpenLnkBtnItems()");
$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth=false;


$addoptions = false;
$deleteoption = false;

$page->toolbar->setaction("navigation",false);

?> 

