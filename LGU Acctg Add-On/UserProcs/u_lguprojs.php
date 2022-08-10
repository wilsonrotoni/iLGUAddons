<?php

include_once("./sls/enumyear.php");

unset($enumyear["-"]);


//$page->businessobject->events->add->customAction("onCustomActionLGUAcctg");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUAcctg");
$page->businessobject->events->add->afterDefault("onAfterDefaultLGUAcctg");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUAcctg");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUAcctg");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUAcctg");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUAcctg");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUAcctg");
//$page->businessobject->events->add->afterEdit("onAfterEditLGUAcctg");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateLGUAcctg");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateLGUAcctg");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateLGUAcctg");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteLGUAcctg");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteLGUAcctg");
//$page->businessobject->events->add->afterDelete("onAfterDeleteLGUAcctg");

function onCustomActionLGUAcctg($action) {
	return true;
}

function onBeforeDefaultLGUAcctg() { 
	return true;
}

function onAfterDefaultLGUAcctg() { 
	global $page;
	$page->setitem("u_yr",date('Y'));
	return true;
}

function onPrepareAddLGUAcctg(&$override) { 
	return true;
}

function onBeforeAddLGUAcctg() { 
	return true;
}

function onAfterAddLGUAcctg() { 
	return true;
}

function onPrepareEditLGUAcctg(&$override) { 
	return true;
}

function onBeforeEditLGUAcctg() { 
	return true;
}

function onAfterEditLGUAcctg() { 
	return true;
}

function onPrepareUpdateLGUAcctg(&$override) { 
	return true;
}

function onBeforeUpdateLGUAcctg() { 
	return true;
}

function onAfterUpdateLGUAcctg() { 
	return true;
}

function onPrepareDeleteLGUAcctg(&$override) { 
	return true;
}

function onBeforeDeleteLGUAcctg() { 
	return true;
}

function onAfterDeleteLGUAcctg() { 
	return true;
}
$page->businessobject->items->seteditable("u_profitcentername",false);
$page->businessobject->items->setcfl("u_profitcenter","OpenCFLfs()");

$page->businessobject->items->setcfl("u_adsdate","Calendar");
$page->businessobject->items->setcfl("u_opendate","Calendar");
$page->businessobject->items->setcfl("u_noadate","Calendar");
$page->businessobject->items->setcfl("u_signdate","Calendar");

$addoptions = false;

?> 

