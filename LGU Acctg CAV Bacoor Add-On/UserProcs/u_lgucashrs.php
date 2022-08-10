<?php


//$page->businessobject->events->add->customAction("onCustomActionLGUGPSAcctgCAVBacoor");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->afterDefault("onAfterDefaultLGUGPSAcctgCAVBacoor");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUGPSAcctgCAVBacoor");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->afterEdit("onAfterEditLGUGPSAcctgCAVBacoor");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateLGUGPSAcctgCAVBacoor");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteLGUGPSAcctgCAVBacoor");
//$page->businessobject->events->add->afterDelete("onAfterDeleteLGUGPSAcctgCAVBacoor");

function onCustomActionLGUGPSAcctgCAVBacoor($action) {
	return true;
}

function onBeforeDefaultLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onAfterDefaultLGUGPSAcctgCAVBacoor() {
	global $page;
	//$page->setitem("u_date",currentdate());
	return true;
}

function onPrepareAddLGUGPSAcctgCAVBacoor(&$override) { 
	return true;
}

function onBeforeAddLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onAfterAddLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onPrepareEditLGUGPSAcctgCAVBacoor(&$override) { 
	return true;
}

function onBeforeEditLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onAfterEditLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onPrepareUpdateLGUGPSAcctgCAVBacoor(&$override) { 
	return true;
}

function onBeforeUpdateLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onAfterUpdateLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onPrepareDeleteLGUGPSAcctgCAVBacoor(&$override) { 
	return true;
}

function onBeforeDeleteLGUGPSAcctgCAVBacoor() { 
	return true;
}

function onAfterDeleteLGUGPSAcctgCAVBacoor() { 
	return true;
}


$objGrids[0]->columnwidth("u_remarks","30");
$objGrids[0]->columnvisibility("u_debit",true);
$objGrids[0]->columnvisibility("u_credit",true);
$objGrids[0]->columnattributes("u_debit","");
$objGrids[0]->columnattributes("u_credit","");
$objGrids[0]->columnattributes("u_amount","disabled");


?> 

