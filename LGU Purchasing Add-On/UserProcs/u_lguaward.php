<?php
 

//$page->businessobject->events->add->customAction("onCustomActionLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUPurchasing");
$page->businessobject->events->add->afterDefault("onAfterDefaultLGUPurchasing");

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
        global $objConnection;
	global $page;
	global $objGrids;
        
        $page->setitem("u_doctype","I");
        $page->setitem("u_date",currentdate());
//        $page->setitem("u_duedate",currentdate());
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
$page->businessobject->items->setcfl("u_contracteffdate","Calendar");
$page->businessobject->items->setcfl("u_contractexpdate","Calendar");

$page->businessobject->items->setcfl("u_projcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bpcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bpname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcenter","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcentername","OpenCFLfs()");
$page->businessobject->items->setcfl("u_requestedbyname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_reviewedby","OpenCFLfs()");
$page->businessobject->items->setcfl("u_approvedby","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);


$objGrids[0]->columntitle("u_unitissue","UoM");
$objGrids[0]->columntitle("u_cost","Line Total");
$objGrids[0]->columntitle("u_openquantity","Remaining Qty.");
$objGrids[0]->columntitle("u_unitissue","UoM");

$objGrids[0]->columnvisibility("u_glacctno",false);
$objGrids[0]->columnvisibility("u_openquantity",false);
$objGrids[0]->columnvisibility("u_linestatus",false);
$objGrids[0]->columnvisibility("u_remarks",false);
$objGrids[0]->columnvisibility("u_contractamt",false);

$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_basetypenm",25);
$objGrids[0]->columnwidth("u_basedocno",25);
$objGrids[0]->columnwidth("u_contractamt",18);
$objGrids[0]->columnwidth("u_openquantity",12);
$objGrids[0]->columnwidth("u_itemsubgroup",15);
$objGrids[0]->columnattributes("u_openquantity","disabled");


$objGrids[0]->columnattributes("u_basetypenm","disabled");
$objGrids[0]->columnattributes("u_basedocno","disabled");
$objGrids[0]->columnattributes("u_basedocid","disabled");
$objGrids[0]->columnattributes("u_baselineid","disabled");
$objGrids[1]->columnattributes("u_basetypenm","disabled");
$objGrids[1]->columnattributes("u_basedocno","disabled");
$objGrids[1]->columnattributes("u_basedocid","disabled");
$objGrids[1]->columnattributes("u_baselineid","disabled");

$objGrids[1]->columntitle("u_unitissue","UoM");
$objGrids[1]->columntitle("u_cost","Line Total");
$objGrids[1]->columntitle("u_unitissue","UoM");
$objGrids[1]->columnvisibility("u_openquantity",false);
$objGrids[1]->columnvisibility("u_linestatus",false);
$objGrids[1]->columnvisibility("u_remarks",false);
$objGrids[1]->columncfl("u_glacctno","OpenCFLfs()");


?> 

