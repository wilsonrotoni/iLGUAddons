<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

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
$appdata= array();
include_once("../Addons/GPS/LGU Add-On/UserProcs/sls/u_lguposterminalseries.php"); 
function onCustomActionGPSRPTAS($action) {
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
        global $page;
	global $appdata;
      //  $appdata["docno"] = $page->getitemstring("docno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
        
	$autoseries = 1;
	$autopost = 0;
	$lineid = 0;
        
        $page->setitem("searchby","L");
	$page->setitem("u_date",currentdate());	
	$page->setitem("u_year",date('Y'));	
	
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

$schema["searchby"] = createSchemaUpper("searchby");
$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_datecancel","Calendar");
$page->businessobject->items->setcfl("u_tdno","OpenCFLfs()");
$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("u_area",false);
$page->businessobject->items->seteditable("u_ownername",false);
$page->businessobject->items->seteditable("u_barangay",false);
$page->businessobject->items->seteditable("u_kind",false);
$page->businessobject->items->seteditable("u_assvalue",false);
$page->businessobject->items->seteditable("u_effyear",false);
//$page->businessobject->items->seteditable("docseries",false);
$page->businessobject->items->seteditable("docstatus",false);
$addoptions = false;
$deleteoption = false;
//$page->toolbar->setaction("add",false);
//$page->toolbar->setaction("navigation",false);
//$page->toolbar->setaction("update",false);

$objMaster->reportaction = "QS";
?> 

