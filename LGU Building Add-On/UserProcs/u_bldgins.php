<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUBuilding");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUBuilding");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUBuilding");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUBuilding");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUBuilding");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUBuilding");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUBuilding");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUBuilding");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUBuilding");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUBuilding");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUBuilding");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUBuilding");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUBuilding");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUBuilding");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUBuilding");

function onCustomActionGPSLGUBuilding($action) {
	return true;
}

function onBeforeDefaultGPSLGUBuilding() { 
	return true;
}

function onAfterDefaultGPSLGUBuilding() {
        global $objConnection;
	global $page;
        $page->setitem("u_issdate",currentdate());
	return true;
}

function onPrepareAddGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeAddGPSLGUBuilding() { 
	return true;
}

function onAfterAddGPSLGUBuilding() { 
	return true;
}

function onPrepareEditGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeEditGPSLGUBuilding() { 
	return true;
}

function onAfterEditGPSLGUBuilding() { 
	return true;
}

function onPrepareUpdateGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUBuilding() { 
	return true;
}

function onAfterUpdateGPSLGUBuilding() { 
	return true;
}

function onPrepareDeleteGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUBuilding() { 
	return true;
}

function onAfterDeleteGPSLGUBuilding() { 
	return true;
}

$page->businessobject->items->setcfl("u_issdate","Calendar");
//$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_inspectdate","Calendar");
$page->businessobject->items->setcfl("u_appno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_inspectbystatus",false);
$page->businessobject->items->seteditable("u_recommendbystatus",false);
$page->businessobject->items->seteditable("u_dispositionbystatus",false);
$page->businessobject->items->seteditable("u_recommenddate",false);
$page->businessobject->items->seteditable("u_dispositiondate",false);

$addoptions = false;

?> 

