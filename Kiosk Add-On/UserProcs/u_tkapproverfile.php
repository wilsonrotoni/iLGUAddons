<?php
 
if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf" || $_SESSION["theme"]=="gps") { 
	$objRs = new recordset(null,$objConnection);
$objRs->queryopen("SELECT u_font FROM u_prglobalsetting");
		while ($objRs->queryfetchrow("NAME")) {
			$page->businessobject->csspath = "../Addons/GPS/PayRoll Add-On/UserPrograms/".$objRs->fields["u_font"]."/";
		}
}
if ($_SESSION["menunavigator"]=="successfactors") { 
	$page->businessobject->showpageheader = false;
}
 

//$page->businessobject->events->add->customAction("onCustomActionGPSKiosk");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSKiosk");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSKiosk");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSKiosk");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSKiosk");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSKiosk");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSKiosk");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSKiosk");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSKiosk");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSKiosk");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSKiosk");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSKiosk");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSKiosk");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSKiosk");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSKiosk");
	
function onCustomActionGPSKiosk($action) {
	return true;
}

function onBeforeDefaultGPSKiosk() { 
	return true;
}

function onAfterDefaultGPSKiosk() { 
	return true;
}

function onPrepareAddGPSKiosk(&$override) { 
	return true;
}

function onBeforeAddGPSKiosk() { 
	return true;
}

function onAfterAddGPSKiosk() { 
	return true;
}

function onPrepareEditGPSKiosk(&$override) { 
	return true;
}

function onBeforeEditGPSKiosk() { 
	return true;
}

function onAfterEditGPSKiosk() {
	return true;
}

function onPrepareUpdateGPSKiosk(&$override) { 
	return true;
}

function onBeforeUpdateGPSKiosk() { 
	return true;
}

function onAfterUpdateGPSKiosk() { 
	return true;
}

function onPrepareDeleteGPSKiosk(&$override) { 
	return true;
}

function onBeforeDeleteGPSKiosk() { 
	return true;
}

function onAfterDeleteGPSKiosk() { 
	return true;
}
	 
//$page->toolbar->setaction("new",false);
//$page->toolbar->setaction("find",false);
//$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("print",false);

//$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->seteditable("name",false);

$page->businessobject->items->setcfl("u_admin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_adminname","OpenCFLfs()");

$objGrids[0]->columnwidth("u_stageno",12);
$objGrids[0]->columnwidth("u_stagename",40);
$objGrids[0]->columnwidth("u_valid",8);
//$objGrids[0]->columninput("u_valid","type","checkbox");
//$objGrids[0]->columninput("u_valid","value",1);
//$objGrids[0]->columninput("u_stagename","type","text");

$objGrids[0]->columnvisibility("u_stagedesc",false);
$objGrids[0]->columnvisibility("u_valid",false);
$objGrids[0]->width = 1100;
$objGrids[0]->height = 220;
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->dataentry = false;
$objGrids[0]->columninput("u_stagename","type","select");
$objGrids[0]->columninput("u_stagename","options",array("loadudflinktable","users:userid:CONCAT(username,' - ',userid):usertype = 'E'",":[Select]"));

$objGrids[1]->columnwidth("u_stagename",40);
$objGrids[1]->width = 1100;
$objGrids[1]->height = 220;
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->columndataentry("u_stagename","type","select");
$objGrids[1]->columndataentry("u_stagename","options",array("loadudflinktable","users:userid:CONCAT(username,' - ',userid):usertype = 'E'",":[Select]"));



?> 

