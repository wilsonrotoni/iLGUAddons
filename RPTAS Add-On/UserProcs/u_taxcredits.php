<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSRPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSRPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSRPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSRPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSRPTAS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSRPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSRPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSRPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSRPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSRPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSRPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSRPTAS");

function onCustomActionGPSRPTAS($action) {
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	return true;
}

function onPrepareAddGPSRPTAS(&$override) { 
	return true;
}

function onBeforeAddGPSRPTAS() { 
	return true;
}

function onAfterAddGPSRPTAS() { 
	return true;
}

function onPrepareEditGPSRPTAS(&$override) { 
	return true;
}

function onBeforeEditGPSRPTAS() { 
	return true;
}

function onAfterEditGPSRPTAS() { 
	return true;
}

function onPrepareUpdateGPSRPTAS(&$override) { 
	return true;
}

function onBeforeUpdateGPSRPTAS() { 
	return true;
}

function onAfterUpdateGPSRPTAS() { 
	return true;
}

function onPrepareDeleteGPSRPTAS(&$override) { 
	return true;
}

function onBeforeDeleteGPSRPTAS() { 
	return true;
}

function onAfterDeleteGPSRPTAS() { 
	return true;
}
$page->businessobject->items->setcfl("u_tdno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_tin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_docno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_orrefno","OpenCFLfs()");
$page->businessobject->items->seteditable("u_docno",false);
$page->businessobject->items->seteditable("u_assvalue",false);

//$objGrid->columncfl("u_tdno","OpenCFLfs()");
//$objGrid->columncfl("u_tin","OpenCFLfs()");
//$objGrid->columncfl("u_orrefno","OpenCFLfs()");
//
//$objGrid->columnwidth("u_year",6);
//$objGrid->columnwidth("u_tdno",15);
//$objGrid->columnwidth("u_tin",20);
//$objGrid->columnwidth("u_orrefno",15);
//
//$objGrid->columnvisibility("u_status",false);
//$objGrid->columnvisibility("name",false);
//$objGrid->columnvisibility("code",false);
//$objGrid->width = 1020;

?> 

