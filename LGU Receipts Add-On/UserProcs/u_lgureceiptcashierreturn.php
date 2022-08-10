<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUReceipts");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUReceipts");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUReceipts");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUReceipts");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUReceipts");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUReceipts");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUReceipts");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUReceipts");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUReceipts");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUReceipts");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUReceipts");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUReceipts");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUReceipts");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUReceipts");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUReceipts");

function onCustomActionGPSLGUReceipts($action) {
	return true;
}

function onBeforeDefaultGPSLGUReceipts() { 
	return true;
}

function onAfterDefaultGPSLGUReceipts() {
        global $page;
        
        $page->setitem("u_datereturn",currentdate());
        $page->setitem("u_time",date('H:i'));
	return true;
}

function onPrepareAddGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeAddGPSLGUReceipts() { 
	return true;
}

function onAfterAddGPSLGUReceipts() { 
	return true;
}

function onPrepareEditGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeEditGPSLGUReceipts() { 
	return true;
}

function onAfterEditGPSLGUReceipts() { 
	return true;
}

function onPrepareUpdateGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUReceipts() { 
	return true;
}

function onAfterUpdateGPSLGUReceipts() { 
	return true;
}

function onPrepareDeleteGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUReceipts() { 
	return true;
}

function onAfterDeleteGPSLGUReceipts() { 
	return true;
}

$page->businessobject->items->setcfl("u_receiptfrm","OpenCFLfs()");
$page->businessobject->items->setcfl("u_receiptto","OpenCFLfs()");
$page->businessobject->items->setcfl("u_receiptlineid","OpenCFLfs()");
$page->businessobject->items->seteditable("u_cashier",false);
//$page->businessobject->items->seteditable("u_form",false);
$page->businessobject->items->seteditable("u_quantity",false);
$page->businessobject->items->setcfl("u_datereturn","Calendar");
//$objGrids[0]->columnvisibility("u_issuedto",false);
//
//$objGrids[0]->columntitle("u_form","Accountable Form");
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
$page->toolbar->setaction("find",false);

?> 

