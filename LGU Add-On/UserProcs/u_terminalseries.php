<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGU");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGU");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGU");

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
        global $page;
      
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

$page->businessobject->items->setcfl("u_startno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_receiptto","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_receiptlineid","OpenCFLfs()");
$page->businessobject->items->seteditable("u_cashier",false);
$page->businessobject->items->seteditable("u_terminalid",false);
$page->businessobject->items->seteditable("u_lastno",false);
$page->businessobject->items->seteditable("u_docseriesname",false);
////$page->businessobject->items->seteditable("u_form",false);
//$page->businessobject->items->seteditable("u_noofreceipt",false);
//$page->businessobject->items->seteditable("u_available",false);
//$page->businessobject->items->setcfl("u_dateissued","Calendar");
//$objGrids[0]->columnvisibility("u_issuedto",false);
//$objGrids[0]->columnwidth("u_form",15);
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
$page->toolbar->setaction("find",false);

?> 
