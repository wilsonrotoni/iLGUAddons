<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUAcctg");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUAcctg");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUAcctg");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUAcctg");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUAcctg");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUAcctg");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUAcctg");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUAcctg");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUAcctg");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUAcctg");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUAcctg");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUAcctg");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUAcctg");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUAcctg");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUAcctg");

function onCustomActionGPSLGUAcctg($action) {
	return true;
}

function onBeforeDefaultGPSLGUAcctg() { 
	return true;
}

function onAfterDefaultGPSLGUAcctg() { 
	return true;
}

function onPrepareAddGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeAddGPSLGUAcctg() { 
	return true;
}

function onAfterAddGPSLGUAcctg() { 
	return true;
}

function onPrepareEditGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeEditGPSLGUAcctg() { 
	return true;
}

function onAfterEditGPSLGUAcctg() { 
	return true;
}

function onPrepareUpdateGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUAcctg() { 
	return true;
}

function onAfterUpdateGPSLGUAcctg() { 
	return true;
}

function onPrepareDeleteGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUAcctg() { 
	return true;
}

function onAfterDeleteGPSLGUAcctg() { 
	return true;
}

//$objGrid->automanagecolumnwidth = false;

$objGrid->columntitle("code","#");
$objGrid->columnwidth("code",7);
$objGrid->columnwidth("u_date",8);
$objGrid->columnwidth("u_orno",7);
$objGrid->columnwidth("u_name",25);

$objGrid->columnvisibility("name",false);

$objGrid->dataentry = false;

$pagingoption = true;
$pagingsearchfield = "code";
?> 

