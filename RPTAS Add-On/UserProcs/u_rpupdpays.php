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

function onCustomActionGPSRPTAS($action) {
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $page;
	$page->setitem("u_date",currentdate());

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

$page->businessobject->items->setcfl("u_pin","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_arpno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_date","Calendar");


$page->businessobject->items->seteditable("u_authorizedby",false);
$page->businessobject->items->seteditable("u_date",false);
$page->businessobject->items->seteditable("u_declaredowner",false);
$page->businessobject->items->seteditable("u_location",false);
$page->businessobject->items->seteditable("u_kind",false);
$page->businessobject->items->seteditable("u_pin",false);
$page->businessobject->items->seteditable("u_tdno",false);
$page->businessobject->items->seteditable("u_tin",false);

$objGrids[0]->dataentry = false;
$objGrids[0]->columntitle("u_arpno","Reference No");
$objGrids[0]->columnwidth("u_arpno",14);
$objGrids[0]->columnwidth("u_tdno",14);
$objGrids[0]->columnwidth("u_qtrfr",6);
$objGrids[0]->columnwidth("u_qtrto",4);
$objGrids[0]->columnwidth("u_yrfr",7);
$objGrids[0]->columnwidth("u_yrto",5);
$objGrids[0]->columnwidth("u_yrpaid",6);

$objGrids[0]->columnwidth("u_qtrfr2",6);
$objGrids[0]->columnwidth("u_qtrto2",4);
$objGrids[0]->columnwidth("u_yrfr2",7);
$objGrids[0]->columnwidth("u_yrto2",5);
$objGrids[0]->columnwidth("u_yrpaid2",6);
//$objGrids[0]->columninput("u_assvalue","type","text");
//$objGrids[0]->columninput("u_qtrfr2","type","text");
//$objGrids[0]->columninput("u_qtrto2","type","text");
//$objGrids[0]->columninput("u_yrfr2","type","text");
//$objGrids[0]->columninput("u_yrto2","type","text");
$objGrids[0]->columninput("u_yrpaid2","type","text");
$addoptions = false;
?> 

