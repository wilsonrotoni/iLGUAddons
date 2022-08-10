<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGU");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGU");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGU");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGU");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGU");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGU");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGU");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGU");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGU");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGU");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGU");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGU");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGU");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGU");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGU");

function onCustomActionGPSLGU($action) {
	return true;
}

function onBeforeDefaultGPSLGU() { 
	return true;
}

function onAfterDefaultGPSLGU() { 
	return true;
}

function onPrepareAddGPSLGU(&$override) { 
	return true;
}

function onBeforeAddGPSLGU() { 
	return true;
}

function onAfterAddGPSLGU() { 
	return true;
}

function onPrepareEditGPSLGU(&$override) { 
	return true;
}

function onBeforeEditGPSLGU() { 
	return true;
}

function onAfterEditGPSLGU() { 
	return true;
}

function onPrepareUpdateGPSLGU(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGU() { 
	return true;
}

function onAfterUpdateGPSLGU() { 
	return true;
}

function onPrepareDeleteGPSLGU(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGU() { 
	return true;
}

function onAfterDeleteGPSLGU() { 
	return true;
}

$objGrid->columndataentry("u_isburial","type","checkbox");
$objGrid->columndataentry("u_isburial","value",1);

$objGrid->columndataentry("u_isctc","type","checkbox");
$objGrid->columndataentry("u_isctc","value",1);
//
//
//$objGrid->columndataentry("u_penalty","type","checkbox");
//$objGrid->columndataentry("u_penalty","value",1);
//$objGrid->columncfl("u_penaltycode","OpenCFLfs()");
//$objGrid->columncfl("u_penaltydesc","OpenCFLfs()");
//$objGrid->columncfl("u_bglcode","OpenCFLfs()");
//$objGrid->columncfl("u_mglcode","OpenCFLfs()");
//$objGrid->columncfl("u_pglcode","OpenCFLfs()");
//$objGrid->columncfl("u_nglcode","OpenCFLfs()");
//$objGrid->columncfl("u_glacctcode","OpenCFLfs()");
//$objGrid->columnwidth("code",10);
//$objGrid->columnwidth("name",40);
//$objGrid->columnwidth("u_bglcode",15);
//$objGrid->columnwidth("u_mglcode",15);
$objGrid->columnwidth("u_isburial",8);
$objGrid->columnwidth("u_isctc",8);
//$objGrid->columnwidth("u_glacctcode",20);
//$objGrid->columnwidth("u_feesubgroup",30);
//$objGrid->columnwidth("u_penaltycode",15);
//$objGrid->columnwidth("u_penaltydesc",30);
//$objGrid->columnwidth("u_interest",10);
?> 

