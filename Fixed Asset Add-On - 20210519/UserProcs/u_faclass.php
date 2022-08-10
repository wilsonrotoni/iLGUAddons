<?php
	require_once("./utils/chartofaccounts.php"); 

//$page->businessobject->events->add->customAction("onCustomActionGPSFixedAsset");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSFixedAsset");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSFixedAsset");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSFixedAsset");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSFixedAsset");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSFixedAsset");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSFixedAsset");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSFixedAsset");
$page->businessobject->events->add->afterEdit("onAfterEditGPSFixedAsset");

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
	$page->setitem("u_purchacctname","");
	$page->setitem("u_depreacctname","");
	$page->setitem("u_accumdepreacctname","");
	$page->setitem("u_lossonsaleacctname","");
	$page->setitem("u_gainonsaleacctname","");
	$page->setitem("u_lossonretireacctname","");
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
	global $page;
	if ($page->getitemstring("u_purchacct")!="") $page->setitem("u_purchacctname",getchartofaccountname($page->getitemstring("u_purchacct")));
	if ($page->getitemstring("u_depreacct")!="") $page->setitem("u_depreacctname",getchartofaccountname($page->getitemstring("u_depreacct")));
	if ($page->getitemstring("u_accumdepreacct")!="") $page->setitem("u_accumdepreacctname",getchartofaccountname($page->getitemstring("u_accumdepreacct")));
	if ($page->getitemstring("u_lossonsaleacct")!="") $page->setitem("u_lossonsaleacctname",getchartofaccountname($page->getitemstring("u_lossonsaleacct")));
	if ($page->getitemstring("u_gainonsaleacct")!="") $page->setitem("u_gainonsaleacctname",getchartofaccountname($page->getitemstring("u_gainonsaleacct")));
	if ($page->getitemstring("u_lossonretireacct")!="") $page->setitem("u_lossonretireacctname",getchartofaccountname($page->getitemstring("u_lossonretireacct")));
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

$schema_masterdataschema["u_purchacctname"] = createSchema("u_purchacctname");
$schema_masterdataschema["u_depreacctname"] = createSchema("u_depreacctname");
$schema_masterdataschema["u_accumdepreacctname"] = createSchema("u_accumdepreacctname");
$schema_masterdataschema["u_lossonsaleacctname"] = createSchema("u_lossonsaleacctname");
$schema_masterdataschema["u_gainonsaleacctname"] = createSchema("u_gainonsaleacctname");
$schema_masterdataschema["u_lossonretireacctname"] = createSchema("u_lossonretireacctname");

$schema_masterdataschema["u_purchacctname"]["attributes"] = "disabled";
$schema_masterdataschema["u_depreacctname"]["attributes"] = "disabled";
$schema_masterdataschema["u_accumdepreacctname"]["attributes"] = "disabled";
$schema_masterdataschema["u_lossonsaleacctname"]["attributes"] = "disabled";
$schema_masterdataschema["u_gainonsaleacctname"]["attributes"] = "disabled";
$schema_masterdataschema["u_lossonretireacctname"]["attributes"] = "disabled";

$page->businessobject->items->setcfl("u_purchacct","OpenCFLchartofaccounts()");
$page->businessobject->items->setcfl("u_depreacct","OpenCFLchartofaccounts()");
$page->businessobject->items->setcfl("u_accumdepreacct","OpenCFLchartofaccounts()");
$page->businessobject->items->setcfl("u_lossonsaleacct","OpenCFLchartofaccounts()");
$page->businessobject->items->setcfl("u_gainonsaleacct","OpenCFLchartofaccounts()");
$page->businessobject->items->setcfl("u_lossonretireacct","OpenCFLchartofaccounts()");

$addoptions = false;
?> 

