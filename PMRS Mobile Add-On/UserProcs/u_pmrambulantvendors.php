<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSPMRSMobile");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSPMRSMobile");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSPMRSMobile");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSPMRSMobile");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSPMRSMobile");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSPMRSMobile");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSPMRSMobile");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSPMRSMobile");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSPMRSMobile");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSPMRSMobile");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSPMRSMobile");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSPMRSMobile");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSPMRSMobile");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSPMRSMobile");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSPMRSMobile");

function onCustomActionGPSPMRSMobile($action) {
	return true;
}

function onBeforeDefaultGPSPMRSMobile() { 
	return true;
}

function onAfterDefaultGPSPMRSMobile() { 
	return true;
}

function onPrepareAddGPSPMRSMobile(&$override) { 
	return true;
}

function onBeforeAddGPSPMRSMobile() { 
	return true;
}

function onAfterAddGPSPMRSMobile() { 
	return true;
}

function onPrepareEditGPSPMRSMobile(&$override) { 
	return true;
}

function onBeforeEditGPSPMRSMobile() { 
	return true;
}

function onAfterEditGPSPMRSMobile() { 
	return true;
}

function onPrepareUpdateGPSPMRSMobile(&$override) { 
	return true;
}

function onBeforeUpdateGPSPMRSMobile() { 
	return true;
}

function onAfterUpdateGPSPMRSMobile() { 
	return true;
}

function onPrepareDeleteGPSPMRSMobile(&$override) { 
	return true;
}

function onBeforeDeleteGPSPMRSMobile() { 
	return true;
}

function onAfterDeleteGPSPMRSMobile() { 
	return true;
}
/*
$objGrid->columnwidth("code",15);
$objGrid->columnwidth("name",50);
$objGrid->columnwidth("u_icdgroup",25);
$objGrid->columnwidth("u_casetype",9);
$objGrid->columnwidth("u_chapter",6);
$objGrid->columnwidth("u_level",4);
$objGrid->columnwidth("u_classplace",10);
$objGrid->columnwidth("u_terminalnode",13);
$objGrid->columnwidth("u_mortality1",10);
$objGrid->columnwidth("u_mortality2",10);
$objGrid->columnwidth("u_mortality3",10);
$objGrid->columnwidth("u_mortality4",10);
$objGrid->columnwidth("u_morbidity",8);
*/
//$objGrid->columndataentry("u_block","type","text");
//$objGrid->columndataentry("u_chapter","type","text");
//$objGrid->columndataentry("u_active","type","checkbox");
//$objGrid->columndataentry("u_active","value",1);
//$objGrid->columnwidth("u_active",12);
$objGrid->columnvisibility("code",false);
//$objGrid->columnvisibility("name",false);
//$objGrid->automanagecolumnwidth = false;

$pagingoption = true;
$pagingsearchfield = "name";
?> 

