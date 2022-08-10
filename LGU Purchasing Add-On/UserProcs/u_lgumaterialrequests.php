<?php
 

//$page->businessobject->events->add->customAction("onCustomActionLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUPurchasing");
//$page->businessobject->events->add->afterDefault("onAfterDefaultLGUPurchasing");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUPurchasing");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUPurchasing");
//$page->businessobject->events->add->afterEdit("onAfterEditLGUPurchasing");

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


$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");

$page->businessobject->items->setcfl("u_profitcenter","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcentername","OpenCFLfs()");
$page->businessobject->items->setcfl("u_projcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_requestedbyname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_reviewedby","OpenCFLfs()");
$page->businessobject->items->setcfl("u_approvedby","OpenCFLfs()");
$page->businessobject->items->seteditable("u_projcode",false);
//$page->businessobject->items->setcfl("u_profitcentername","OpenCFLfs()");

$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnattributes("u_openquantity","disabled");

//$objGrids[0]->columnwidth("u_itemcode",20);
//$objGrids[0]->columnwidth("u_itemdesc",50);
//$objGrids[0]->columnwidth("u_glacctno",20);

//
//$objGrids[1]->columnattributes("u_cost","disabled");
//$objGrids[1]->columncfl("u_glacctno","OpenCFLfs()");

//$objGrids[0]->automanagecolumnwidth = false;

$pageHeader = "Material Request";

?> 

