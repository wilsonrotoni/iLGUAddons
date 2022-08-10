<?php
 

//$page->businessobject->events->add->customAction("onCustomActionCTC");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultCTC");
$page->businessobject->events->add->afterDefault("onAfterDefaultCTC");

//$page->businessobject->events->add->prepareAdd("onPrepareAddCTC");
//$page->businessobject->events->add->beforeAdd("onBeforeAddCTC");
//$page->businessobject->events->add->afterAdd("onAfterAddCTC");

//$page->businessobject->events->add->prepareEdit("onPrepareEditCTC");
//$page->businessobject->events->add->beforeEdit("onBeforeEditCTC");
//$page->businessobject->events->add->afterEdit("onAfterEditCTC");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateCTC");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateCTC");
$page->businessobject->events->add->afterUpdate("onAfterUpdateCTC");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteCTC");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteCTC");
//$page->businessobject->events->add->afterDelete("onAfterDeleteCTC");
$appdata= array();
include_once("../Addons/GPS/LGU Add-On/UserProcs/sls/u_lguposterminalseries.php"); 
function onCustomActionCTC($action) {
	return true;
}

function onBeforeDefaultCTC() { 
        global $page;
	global $appdata;
      //  $appdata["docno"] = $page->getitemstring("docno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	return true;
}

function onAfterDefaultCTC() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
        
	$autoseries = 1;
	$autopost = 0;
	$lineid = 0;
        
	$page->setitem("u_date",currentdate());	
	
	return true;
}

function onPrepareAddCTC(&$override) { 
	return true;
}

function onBeforeAddCTC() { 
	return true;
}

function onAfterAddCTC() { 
	return true;
}

function onPrepareEditCTC(&$override) { 
	return true;
}

function onBeforeEditCTC() { 
	return true;
}

function onAfterEditCTC() { 
	return true;
}

function onPrepareUpdateCTC(&$override) { 
	return true;
}

function onBeforeUpdateCTC() { 
	return true;
}

function onAfterUpdateCTC() { 
	return true;
}

function onPrepareDeleteCTC(&$override) { 
	return true;
}

function onBeforeDeleteCTC() { 
	return true;
}

function onAfterDeleteCTC() { 
	return true;
}

$objGrids[0]->columncfl("u_receiptlineid","OpenCFLfs()");
$objGrids[0]->columncfl("u_receiptfrm","OpenCFLfs()");
$objGrids[0]->columncfl("u_receiptto","OpenCFLfs()");

$objGrids[0]->columntitle("u_usedqty","Used Qty");
$objGrids[0]->columntitle("u_availableqty","Available Qty");

$objGrids[0]->columnwidth("u_receiptfrm",15);
$objGrids[0]->columnwidth("u_receiptto",15);
$objGrids[0]->columnwidth("u_receiptlineid",15);
$objGrids[0]->columnwidth("u_amount",18);

$objGrids[0]->columnvisibility("u_docseries",false);
$objGrids[0]->columnattributes("u_availableqty","disabled");
$objGrids[0]->columnattributes("u_form","disabled");
//$objGrids[0]->columnwidth("u_amount",18);
$objGrids[0]->automanagecolumnwidth = false;

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->seteditable("u_totalamount",false);
$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
$pageHeader = "Community Tax Barangay";


?> 

