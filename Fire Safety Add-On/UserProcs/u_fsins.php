<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSFireSafety");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSFireSafety");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSFireSafety");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSFireSafety");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSFireSafety");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSFireSafety");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSFireSafety");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSFireSafety");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSFireSafety");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSFireSafety");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSFireSafety");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSFireSafety");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSFireSafety");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSFireSafety");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSFireSafety");

function onCustomActionGPSFireSafety($action) {
	return true;
}

function onBeforeDefaultGPSFireSafety() { 
	return true;
}

function onAfterDefaultGPSFireSafety() { 
	return true;
}

function onPrepareAddGPSFireSafety(&$override) { 
	return true;
}

function onBeforeAddGPSFireSafety() { 
	return true;
}

function onAfterAddGPSFireSafety() { 
	return true;
}

function onPrepareEditGPSFireSafety(&$override) { 
	return true;
}

function onBeforeEditGPSFireSafety() { 
	return true;
}

function onAfterEditGPSFireSafety() { 
	return true;
}

function onPrepareUpdateGPSFireSafety(&$override) { 
	return true;
}

function onBeforeUpdateGPSFireSafety() { 
	return true;
}

function onAfterUpdateGPSFireSafety() { 
	return true;
}

function onPrepareDeleteGPSFireSafety(&$override) { 
	return true;
}

function onBeforeDeleteGPSFireSafety() { 
	return true;
}

function onAfterDeleteGPSFireSafety() { 
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
$objMaster->reportaction = "QS";
?> 

