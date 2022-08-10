<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
	return true;
}

function onAfterAddGPSHIS() { 
	return true;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	return true;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	return true;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

$objGrid->automanagecolumnwidth = false;

$objGrid->columncfl("code","OpenCFLfs()");
$objGrid->columncfl("name","OpenCFLfs()");

$objGrid->columnwidth("code",8);
$objGrid->columntitle("code","RVS Code");
$objGrid->columnwidth("name",35);
$objGrid->columntitle("name","Description");

$objGrid->columnwidth("u_interval",7);
$objGrid->columnwidth("u_latinterval",15);

$objGrid->columnwidth("u_withlateral",7);
$objGrid->columnwidth("u_repeatproc",9);
$objGrid->columnwidth("u_repeatproctype",30);
$objGrid->columntitle("u_withlateral","Lateral");
$objGrid->columnalignment("u_withlateral","center");
$objGrid->columndataentry("u_withlateral","type","checkbox");
$objGrid->columndataentry("u_withlateral","value",1);
$objGrid->columndataentry("u_repeatproc","type","checkbox");
$objGrid->columndataentry("u_repeatproc","value",1);

$objGrid->columnwidth("u_maxcycle",8);
$objGrid->columnwidth("u_1stcr",9);
$objGrid->columnwidth("u_1stpf",9);
$objGrid->columnwidth("u_1sthc",9);
$objGrid->columntitle("u_1stcr","1st Case");
$objGrid->columntitle("u_1stpf","PF");
$objGrid->columntitle("u_1sthc","HCI");

$objGrid->columnwidth("u_2ndcr",9);
$objGrid->columnwidth("u_2ndpf",9);
$objGrid->columnwidth("u_2ndhc",9);
$objGrid->columntitle("u_2ndcr","2nd Case");
$objGrid->columntitle("u_2ndpf","PF");
$objGrid->columntitle("u_2ndhc","HCI");

$pagingoption = true;
$pagingsearchfield = "name";
?> 

