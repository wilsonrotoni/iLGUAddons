<?php
 

//$page->businessobject->events->add->customAction("onCustomActionLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUPurchasing");
//$page->businessobject->events->add->afterDefault("onAfterDefaultLGUPurchasing");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUPurchasing");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUPurchasing");
//$page->businessobject->events->add->afaterEdit("onAfterEditLGUPurchasing");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateLGUPurchasing");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateLGUPurchasing");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateLGUPurchasing");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteLGUPurchasing");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteLGUPurchasing");
//$page->businessobject->events->add->afterDelete("onAfterDeleteLGUPurchasing");

function onCustomActionLGUPurchasing($action) {
	return true;
}

function onBeforeDefaultLGUPurchasing() { 
	return true;
}

function onAfterDefaultLGUPurchasing() { 
	return true;
}

function onPrepareAddLGUPurchasing(&$override) { 
	return true;
}

function onBeforeAddLGUPurchasing() { 
	return true;
}

function onAfterAddLGUPurchasing() { 
	return true;
}

function onPrepareEditLGUPurchasing(&$override) { 
	return true;
}

function onBeforeEditLGUPurchasing() { 
	return true;
}

function onAfterEditLGUPurchasing() { 
	return true;
}

function onPrepareUpdateLGUPurchasing(&$override) { 
	return true;
}

function onBeforeUpdateLGUPurchasing() { 
	return true;
}

function onAfterUpdateLGUPurchasing() { 
	return true;
}

function onPrepareDeleteLGUPurchasing(&$override) { 
	return true;
}

function onBeforeDeleteLGUPurchasing() { 
	return true;
}

function onAfterDeleteLGUPurchasing() { 
	return true;
}


//$page->businessobject->items->setcfl("u_date","Calendar");
//$page->businessobject->items->setcfl("u_contracteffdate","Calendar");
//$page->businessobject->items->setcfl("u_contractexpdate","Calendar");
//
//$page->businessobject->items->setcfl("u_bpcode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_philgepsrefno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_canvassingrefno","OpenCFLfs()");

$objGrids[0]->columncfl("u_profitcenter","OpenCFLfs()");
$objGrids[0]->columncfl("u_profitcentername","OpenCFLfs()");
$objGrids[0]->columnwidth("u_isdefault",10);

$addoptions = false;


?> 

