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
//$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

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
	$page->setitem("code","?");
	//$u_hissetupdata = getu_hissetup("U_DFLTPHARMADEPT,U_DFLTLABDEPT,U_DFLTPHARMATFS,U_DFLTPULMONARYDEPT,U_DFLTIPD,U_DFLTOPD,U_STOCKLINK,U_PREPAIDIPMISC,U_PREPAIDOPMISC");
	
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
	global $page;
	global $objGrids;
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

$objGrids[0]->columnwidth("u_itemcode",20);
$objGrids[0]->columnwidth("u_itemdesc",50);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnattributes("u_uom","disabled");

$addoptions = false;
$deleteoption = false;
$closeoption = true;
?> 

