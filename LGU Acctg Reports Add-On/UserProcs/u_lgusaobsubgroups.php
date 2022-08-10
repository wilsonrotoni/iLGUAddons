<?php
 
include_once("./sls/enumyear.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUAcctgReports");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUAcctgReports");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUAcctgReports");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUAcctgReports");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUAcctgReports");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUAcctgReports");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUAcctgReports");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUAcctgReports");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUAcctgReports");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUAcctgReports");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUAcctgReports");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUAcctgReports");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUAcctgReports");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUAcctgReports");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUAcctgReports");

$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSLGUAcctgReports");

function onPrepareChildEditGPSLGUAcctgReports($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T1":	
			$filerExp = " ORDER BY  U_GLACCTNAME";
			break;
			
	}
	return true;
}
function onCustomActionGPSLGUAcctgReports($action) {
	return true;
}

function onBeforeDefaultGPSLGUAcctgReports() { 
	return true;
}

function onAfterDefaultGPSLGUAcctgReports() { 

	global $page;
	
	$page->setitem("u_yr",date('Y'));
	return true;
}

function onPrepareAddGPSLGUAcctgReports(&$override) { 
	return true;
}

function onBeforeAddGPSLGUAcctgReports() { 
	return true;
}

function onAfterAddGPSLGUAcctgReports() { 
	return true;
}

function onPrepareEditGPSLGUAcctgReports(&$override) { 
	return true;
}

function onBeforeEditGPSLGUAcctgReports() { 
	return true;
}

function onAfterEditGPSLGUAcctgReports() { 
	return true;
}

function onPrepareUpdateGPSLGUAcctgReports(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUAcctgReports() { 
	return true;
}

function onAfterUpdateGPSLGUAcctgReports() { 
	return true;
}

function onPrepareDeleteGPSLGUAcctgReports(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUAcctgReports() { 
	return true;
}

function onAfterDeleteGPSLGUAcctgReports() { 
	return true;
}

$objGrids[0]->columncfl("u_glacctno","OpenCFLfs()");
$objGrids[0]->columncfl("u_glacctname","OpenCFLfs()");

$objGrids[0]->columntitle("u_expclass","Class");
$objGrids[0]->columnwidth("u_expclass",10);
$objGrids[0]->columnwidth("u_glacctno",15);
$objGrids[0]->columnwidth("u_glacctname",45);
$objGrids[0]->automanagecolumnwidth = true;
?> 

