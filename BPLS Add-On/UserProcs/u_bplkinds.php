<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSBPLS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSBPLS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSBPLS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSBPLS");

//$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSBPLS");

function onCustomActionGPSBPLS($action) {
	return true;
}

function onBeforeDefaultGPSBPLS() { 
	return true;
}

function onAfterDefaultGPSBPLS() { 
	return true;
}

function onPrepareAddGPSBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSBPLS() { 
	return true;
}

function onAfterAddGPSBPLS() { 
	return true;
}

function onPrepareEditGPSBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSBPLS() { 
	return true;
}

function onAfterEditGPSBPLS() { 
	return true;
}

function onPrepareUpdateGPSBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSBPLS() { 
	return true;
}

function onAfterUpdateGPSBPLS() { 
	return true;
}

function onPrepareDeleteGPSBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSBPLS() { 
	return true;
}

function onAfterDeleteGPSBPLS() { 
	return true;
}

function onDrawGridColumnLabelGPSBPLS($tablename,$column,$row,&$label) {
}

$objGrid->columnwidth("code",50);	
$objGrid->columnvisibility("name",false);


?> 

