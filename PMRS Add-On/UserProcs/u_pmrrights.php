<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSPMRS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSPMRS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSPMRS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSPMRS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSPMRS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSPMRS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSPMRS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSPMRS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSPMRS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSPMRS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSPMRS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSPMRS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSPMRS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSPMRS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSPMRS");

function onCustomActionGPSPMRS($action) {
	return true;
}

function onBeforeDefaultGPSPMRS() { 
	return true;
}

function onAfterDefaultGPSPMRS() { 
	return true;
}

function onPrepareAddGPSPMRS(&$override) { 
	return true;
}

function onBeforeAddGPSPMRS() { 
	return true;
}

function onAfterAddGPSPMRS() { 
	return true;
}

function onPrepareEditGPSPMRS(&$override) { 
	return true;
}

function onBeforeEditGPSPMRS() { 
	return true;
}

function onAfterEditGPSPMRS() { 
	return true;
}

function onPrepareUpdateGPSPMRS(&$override) { 
	return true;
}

function onBeforeUpdateGPSPMRS() { 
	return true;
}

function onAfterUpdateGPSPMRS() { 
	return true;
}

function onPrepareDeleteGPSPMRS(&$override) { 
	return true;
}

function onBeforeDeleteGPSPMRS() { 
	return true;
}

function onAfterDeleteGPSPMRS() { 
	return true;
}

$objGrid->columnvisibility("name",false);
$objGrid->columntitle("code","Description");
$objGrid->columnwidth("code",50);


?> 

