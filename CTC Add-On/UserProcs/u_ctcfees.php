<?php
 

//$page->businessobject->events->add->customAction("onCustomActionCTC");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultCTC");
//$page->businessobject->events->add->afterDefault("onAfterDefaultCTC");

//$page->businessobject->events->add->prepareAdd("onPrepareAddCTC");
//$page->businessobject->events->add->beforeAdd("onBeforeAddCTC");
//$page->businessobject->events->add->afterAdd("onAfterAddCTC");

//$page->businessobject->events->add->prepareEdit("onPrepareEditCTC");
//$page->businessobject->events->add->beforeEdit("onBeforeEditCTC");
//$page->businessobject->events->add->afterEdit("onAfterEditCTC");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateCTC");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateCTC");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateCTC");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteCTC");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteCTC");
//$page->businessobject->events->add->afterDelete("onAfterDeleteCTC");

function onCustomActionCTC($action) {
	return true;
}

function onBeforeDefaultCTC() { 
	return true;
}

function onAfterDefaultCTC() { 
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


$objGrid->columncfl("code","OpenCFLfs()");
$objGrid->columncfl("name","OpenCFLfs()");


?> 

