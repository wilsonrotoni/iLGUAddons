<?php
 
	require_once("./sls/brancheslist.php"); 
	require_once("./sls/departments.php"); 
//$page->businessobject->events->add->customAction("onCustomActionGPSFixedAsset");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSFixedAsset");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSFixedAsset");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSFixedAsset");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSFixedAsset");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSFixedAsset");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSFixedAsset");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSFixedAsset");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSFixedAsset");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSFixedAsset");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSFixedAsset");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSFixedAsset");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSFixedAsset");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSFixedAsset");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSFixedAsset");

function onCustomActionGPSFixedAsset($action) {
	return true;
}

function onBeforeDefaultGPSFixedAsset() { 
	return true;
}

function onAfterDefaultGPSFixedAsset() { 
	global $page;
	$page->setitem("u_docdate",currentdate());
	return true;
}

function onPrepareAddGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeAddGPSFixedAsset() { 
	return true;
}

function onAfterAddGPSFixedAsset() { 
	return true;
}

function onPrepareEditGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeEditGPSFixedAsset() { 
	return true;
}

function onAfterEditGPSFixedAsset() { 
	return true;
}

function onPrepareUpdateGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeUpdateGPSFixedAsset() { 
	return true;
}

function onAfterUpdateGPSFixedAsset() { 
	return true;
}

function onPrepareDeleteGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeDeleteGPSFixedAsset() { 
	return true;
}

function onAfterDeleteGPSFixedAsset() { 
	return true;
}

$objGrids[0]->columnwidth("u_itemcode",20);
$objGrids[0]->columnwidth("u_itemdesc",20);
$objGrids[0]->columnwidth("u_contraglacctno",20);
$objGrids[0]->columnwidth("u_accumdepre",15);
$objGrids[0]->columnwidth("u_profitcenter",20);
$objGrids[0]->columnwidth("u_projcode",20);
$objGrids[0]->columnwidth("u_empid",10);
$objGrids[0]->columnwidth("u_empname",20);
$objGrids[0]->columnwidth("u_branch",20);
$objGrids[0]->columnwidth("u_department",20);
$objGrids[0]->columnwidth("u_serialno",20);
$objGrids[0]->columnwidth("u_mfrserialno",20);
$objGrids[0]->columnwidth("u_property1",15);
$objGrids[0]->columnwidth("u_property2",15);
$objGrids[0]->columnwidth("u_property3",15);
$objGrids[0]->columnwidth("u_property4",15);
$objGrids[0]->columnwidth("u_property5",15);
$objGrids[0]->columnwidth("u_property6",15);
$objGrids[0]->columnwidth("u_property7",15);
$objGrids[0]->columnwidth("u_property8",15);
$objGrids[0]->columnwidth("u_property9",15);
$objGrids[0]->columnwidth("u_property10",15);
$objGrids[0]->columnwidth("u_depredate",15);

//$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[0]->columnattributes("u_empname","disabled");

$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
$objGrids[0]->columncfl("u_contraglacctno","OpenCFLchartofaccounts()");
$objGrids[0]->columncfl("u_profitcenter","OpenCFLprofitcenterdistributionrules()");
$objGrids[0]->columncfl("u_projcode","OpenCFLprojects()");
$objGrids[0]->columncfl("u_empid","OpenCFLemployees()");

$objGrids[0]->columndataentry("u_branch","type","select");
$objGrids[0]->columndataentry("u_branch","options",array("loadbrancheslist","",":[Select]"));
$objGrids[0]->columndataentry("u_department","type","select");
$objGrids[0]->columndataentry("u_department","options",array("loaddepartments","",":[Select]"));

$objGrids[0]->columnvisibility("u_assettype",false);
$objGrids[0]->columnvisibility("u_assetcode",false);

$page->businessobject->items->setcfl("u_docdate","Calendar");

$page->businessobject->items->seteditable("u_jvno",false);

//$addoptions = false;
$deleteoption = false;
//$page->toolbar->setaction("update",false);
?> 

