<?php
 

$page->businessobject->events->add->customAction("onCustomActionOnlineBilling");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultOnlineBilling");
$page->businessobject->events->add->afterDefault("onAfterDefaultOnlineBilling");

//$page->businessobject->events->add->prepareAdd("onPrepareAddOnlineBilling");
//$page->businessobject->events->add->beforeAdd("onBeforeAddOnlineBilling");
//$page->businessobject->events->add->afterAdd("onAfterAddOnlineBilling");

//$page->businessobject->events->add->prepareEdit("onPrepareEditOnlineBilling");
//$page->businessobject->events->add->beforeEdit("onBeforeEditOnlineBilling");
//$page->businessobject->events->add->afterEdit("onAfterEditOnlineBilling");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateOnlineBilling");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateOnlineBilling");
$page->businessobject->events->add->afterUpdate("onAfterUpdateOnlineBilling");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteOnlineBilling");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteOnlineBilling");
//$page->businessobject->events->add->afterDelete("onAfterDeleteOnlineBilling");
$appdata= array();
include_once("../Addons/GPS/LGU Add-On/UserProcs/sls/u_lguposterminalseries.php"); 
function onCustomActionOnlineBilling($action) {
	return true;
}

function onBeforeDefaultOnlineBilling() { 
	return true;
}

function onAfterDefaultOnlineBilling() { 
        global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
        
        $page->setitem("u_date",currentdate());	
	
	return true;
}

function onPrepareAddOnlineBilling(&$override) { 
	return true;
}

function onBeforeAddOnlineBilling() { 
	return true;
}

function onAfterAddOnlineBilling() { 
	return true;
}

function onPrepareEditOnlineBilling(&$override) { 
	return true;
}

function onBeforeEditOnlineBilling() { 
	return true;
}

function onAfterEditOnlineBilling() { 
	return true;
}

function onPrepareUpdateOnlineBilling(&$override) { 
	return true;
}

function onBeforeUpdateOnlineBilling() { 
	return true;
}

function onAfterUpdateOnlineBilling() { 
	return true;
}

function onPrepareDeleteOnlineBilling(&$override) { 
	return true;
}

function onBeforeDeleteOnlineBilling() { 
	return true;
}

function onAfterDeleteOnlineBilling() { 
	return true;
}



$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->seteditable("u_docdate",false);
$page->businessobject->items->seteditable("u_ordate",false);
$page->businessobject->items->seteditable("u_orno",false);
$page->businessobject->items->seteditable("docseries",false);
$page->businessobject->items->seteditable("docstatus",false);
$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
//$pageHeader = "Community Tax Application";


?> 

