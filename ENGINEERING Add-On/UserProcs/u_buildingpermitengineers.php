<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSENGINEERING");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSENGINEERING");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSENGINEERING");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSENGINEERING");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSENGINEERING");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSENGINEERING");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSENGINEERING");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSENGINEERING");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSENGINEERING");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSENGINEERING");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSENGINEERING");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSENGINEERING");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSENGINEERING");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSENGINEERING");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSENGINEERING");

function onCustomActionGPSENGINEERING($action) {
	return true;
}

function onBeforeDefaultGPSENGINEERING() { 
	return true;
}

function onAfterDefaultGPSENGINEERING() { 
	return true;
}

function onPrepareAddGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeAddGPSENGINEERING() { 
	return true;
}

function onAfterAddGPSENGINEERING() { 
	return true;
}

function onPrepareEditGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeEditGPSENGINEERING() { 
	return true;
}

function onAfterEditGPSENGINEERING() { 
	return true;
}

function onPrepareUpdateGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeUpdateGPSENGINEERING() { 
	return true;
}

function onAfterUpdateGPSENGINEERING() { 
	return true;
}

function onPrepareDeleteGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeDeleteGPSENGINEERING() { 
	return true;
}

function onAfterDeleteGPSENGINEERING() { 
	return true;
}


$objGrids[0]->columnwidth("u_name",50);
$objGrids[0]->columnwidth("u_prcno",15);
$objGrids[0]->columnwidth("u_ptrno",15);
$objGrids[0]->columnwidth("u_prcvalidity",15);
$objGrids[0]->columncfl("u_prcvalidity","Calendar");
$objGrids[0]->columnwidth("u_dateissued",20);
$objGrids[0]->columncfl("u_dateissued","Calendar");
$objGrids[0]->columnwidth("u_issuedat",20);
$objGrids[0]->columnwidth("u_tinno",15);


?> 

