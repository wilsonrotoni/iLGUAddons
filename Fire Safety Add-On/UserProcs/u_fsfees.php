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

$objGrid->columncfl("code","OpenCFLfs()");
$objGrid->columncfl("name","OpenCFLfs()");
$objGrid->columndataentry("u_check","type","checkbox");
$objGrid->columndataentry("u_check","value",1);
?> 

