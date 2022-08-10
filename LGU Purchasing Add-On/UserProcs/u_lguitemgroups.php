<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUPurchasing");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUPurchasing");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUPurchasing");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUPurchasing");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUPurchasing");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUPurchasing");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUPurchasing");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUPurchasing");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUPurchasing");

function onCustomActionGPSLGUPurchasing($action) {
	return true;
}

function onBeforeDefaultGPSLGUPurchasing() { 
	return true;
}

function onAfterDefaultGPSLGUPurchasing() { 
	return true;
}

function onPrepareAddGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeAddGPSLGUPurchasing() { 
	return true;
}

function onAfterAddGPSLGUPurchasing() { 
	return true;
}

function onPrepareEditGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeEditGPSLGUPurchasing() { 
	return true;
}

function onAfterEditGPSLGUPurchasing() { 
	return true;
}

function onPrepareUpdateGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUPurchasing() { 
	return true;
}

function onAfterUpdateGPSLGUPurchasing() { 
	return true;
}

function onPrepareDeleteGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUPurchasing() { 
	return true;
}

function onAfterDeleteGPSLGUPurchasing() { 
	return true;
}

$objGrid->columncfl("u_expenseglacctno","OpenCFLfs()");
$objGrid->columncfl("u_expenseglacctname","OpenCFLfs()");

$objGrid->columnwidth("u_expenseglacctno",20);
?> 

