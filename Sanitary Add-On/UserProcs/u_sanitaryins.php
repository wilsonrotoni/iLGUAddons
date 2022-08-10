<?php

//$page->businessobject->events->add->customAction("onCustomActionGPSSanitary");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSSanitary");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSSanitary");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSSanitary");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSSanitary");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSSanitary");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSSanitary");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSSanitary");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSSanitary");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSSanitary");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSSanitary");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSSanitary");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSSanitary");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSSanitary");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSSanitary");

function onCustomActionGPSSanitary($action) {
	return true;
}

function onBeforeDefaultGPSSanitary() { 
	return true;
}

function onAfterDefaultGPSSanitary() { 
	global $page;
	$page->setitem("docstatus","O");
	$page->setitem("u_ratingperc",0);
	$page->setitem("u_inspectbystatus","");
	//$page->setitem("u_inspectdate",currentdate());
	
	return true;
}

function onPrepareAddGPSSanitary(&$override) { 
	return true;
}

function onBeforeAddGPSSanitary() { 
	return true;
}

function onAfterAddGPSSanitary() { 
	return true;
}

function onPrepareEditGPSSanitary(&$override) { 
	return true;
}

function onBeforeEditGPSSanitary() { 
	return true;
}

function onAfterEditGPSSanitary() { 
	return true;
}

function onPrepareUpdateGPSSanitary(&$override) { 
	return true;
}

function onBeforeUpdateGPSSanitary() { 
	return true;
}

function onAfterUpdateGPSSanitary() { 
	return true;
}

function onPrepareDeleteGPSSanitary(&$override) { 
	return true;
}

function onBeforeDeleteGPSSanitary() { 
	return true;
}

function onAfterDeleteGPSSanitary() { 
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

