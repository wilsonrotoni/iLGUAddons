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

$page->businessobject->items->seteditable("u_faname",false);
$page->businessobject->items->seteditable("u_itemdesc",false);
$page->businessobject->items->seteditable("u_faclass",false);
$page->businessobject->items->seteditable("u_acquidate",false);
$page->businessobject->items->seteditable("u_accumdepreacct",false);
$page->businessobject->items->seteditable("u_depreacct",false);
$page->businessobject->items->seteditable("u_accumdepre",false);
$page->businessobject->items->seteditable("u_empid",false);
$page->businessobject->items->seteditable("u_empname",false);
$page->businessobject->items->seteditable("u_cost",false);
$page->businessobject->items->seteditable("u_salvagevalue",false);
$page->businessobject->items->seteditable("u_remainlife",false);
$page->businessobject->items->seteditable("u_bookvalue",false);
$page->businessobject->items->seteditable("u_srcdoc",false);
$page->businessobject->items->seteditable("u_srcname",false);
$page->businessobject->items->seteditable("u_branch",false);
$page->businessobject->items->seteditable("u_profitcenter",false);
$page->businessobject->items->seteditable("u_projcode",false);
$page->businessobject->items->seteditable("u_department",false);
$page->businessobject->items->seteditable("u_depredate",false);

//$page->businessobject->items->setcfl("u_profitcenter","OpenCFLprofitcenterdistributionrules()");
//$page->businessobject->items->setcfl("u_projcode","OpenCFLprojects()");
$page->businessobject->items->setcfl("u_acquidate","Calendar");
$page->businessobject->items->setcfl("u_depredate","Calendar");

$page->businessobject->items->seteditable("u_jvno",false);
$page->businessobject->items->seteditable("u_serialno",false);
$page->businessobject->items->seteditable("u_mfrserialno",false);
$page->businessobject->items->seteditable("u_property1",false);
$page->businessobject->items->seteditable("u_property2",false);
$page->businessobject->items->seteditable("u_property3",false);
$page->businessobject->items->seteditable("u_property4",false);
$page->businessobject->items->seteditable("u_property5",false);

$page->businessobject->items->seteditable("u_tftoempname",false);

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_facode","OpenCFLmasterdata()");
$page->businessobject->items->setcfl("u_tftoempid","OpenCFLemployees()");
$page->businessobject->items->setcfl("u_tftoprofitcenter","OpenCFLprofitcenterdistributionrules()");
$page->businessobject->items->setcfl("u_tftoprojcode","OpenCFLprojects()");

$addoptions=false;
$deleteoption=false;
$canceloption = false;

$page->toolbar->setaction("update",false);
?> 

