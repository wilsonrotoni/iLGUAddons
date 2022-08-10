<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUZoning");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUZoning");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUZoning");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUZoning");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUZoning");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUZoning");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUZoning");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUZoning");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUZoning");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUZoning");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUZoning");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUZoning");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUZoning");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUZoning");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUZoning");

function onCustomActionGPSLGUZoning($action) {
	return true;
}

function onBeforeDefaultGPSLGUZoning() { 
	return true;
}

function onAfterDefaultGPSLGUZoning() {
    global $objConnection;
    global $page;
    $page->setitem("u_issdate",currentdate());
    return true;
}

function onPrepareAddGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeAddGPSLGUZoning() { 
	return true;
}

function onAfterAddGPSLGUZoning() { 
	return true;
}

function onPrepareEditGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeEditGPSLGUZoning() { 
	return true;
}

function onAfterEditGPSLGUZoning() { 
	return true;
}

function onPrepareUpdateGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUZoning() { 
	return true;
}

function onAfterUpdateGPSLGUZoning() { 
	return true;
}

function onPrepareDeleteGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUZoning() { 
	return true;
}

function onAfterDeleteGPSLGUZoning() { 
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

