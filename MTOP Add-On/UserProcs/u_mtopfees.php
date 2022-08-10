<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSMTOP");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSMTOP");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSMTOP");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSMTOP");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSMTOP");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSMTOP");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSMTOP");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSMTOP");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSMTOP");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSMTOP");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSMTOP");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSMTOP");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSMTOP");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSMTOP");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSMTOP");

function onCustomActionGPSMTOP($action) {
	return true;
}

function onBeforeDefaultGPSMTOP() { 
	return true;
}

function onAfterDefaultGPSMTOP() { 
	return true;
}

function onPrepareAddGPSMTOP(&$override) { 
	return true;
}

function onBeforeAddGPSMTOP() { 
	return true;
}

function onAfterAddGPSMTOP() { 
	return true;
}

function onPrepareEditGPSMTOP(&$override) { 
	return true;
}

function onBeforeEditGPSMTOP() { 
	return true;
}

function onAfterEditGPSMTOP() { 
	return true;
}

function onPrepareUpdateGPSMTOP(&$override) { 
	return true;
}

function onBeforeUpdateGPSMTOP() { 
	return true;
}

function onAfterUpdateGPSMTOP() { 
	return true;
}

function onPrepareDeleteGPSMTOP(&$override) { 
	return true;
}

function onBeforeDeleteGPSMTOP() { 
	return true;
}

function onAfterDeleteGPSMTOP() { 
	return true;
}

$objGrid->columncfl("code","OpenCFLfs()");
$objGrid->columncfl("name","OpenCFLfs()");

$objGrid->columnwidth("code",10);
$objGrid->columnwidth("name",30);
$objGrid->columnwidth("u_new",10);
$objGrid->columnwidth("u_renew",10);
$objGrid->columnwidth("u_update",10);
$objGrid->columndataentry("u_new","type","checkbox");
$objGrid->columndataentry("u_new","value",1);
$objGrid->columndataentry("u_renew","type","checkbox");
$objGrid->columndataentry("u_renew","value",1);
$objGrid->columndataentry("u_update","type","checkbox");
$objGrid->columndataentry("u_update","value",1);

$objGrid->columnvisibility("u_yearly",false);

?> 

