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
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSKiosk");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSKiosk");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSKiosk");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSKiosk");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSKiosk");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSKiosk");
$page->businessobject->events->add->afterEdit("onAfterEditGPSKiosk");

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
	global $objConnection;
	global $page;
	$objRs = new recordset(null,$objConnection);
	$objRsDays = new recordset(null,$objConnection);
	$objRsCode = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT code,u_stageno FROM u_tkapproverfileassigned WHERE u_stagename = '".$_SESSION["userid"]."' AND code = '".$_GET["codes"]."'");
	while ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_approverby",$objRs->fields["u_stageno"]);
		$page->setitem("u_date",formatDateToHttp($_GET["dates"]));
		$page->setitem("u_typeofrequest",$_GET["rtypes"]);
		$page->setitem("u_profitcenter","GEN");
		$page->setitem("name",$objRs->fields["code"]);
		}
	$objRsDays->queryopen("SELECT DAYNAME('".$_GET["dates"]."') as code");
	while ($objRsDays->queryfetchrow("NAME")) {
		$page->setitem("u_dayname",$objRsDays->fields["code"]);
		$page->setitem("code","APP-".$objRs->fields["u_stageno"].$_SESSION["userid"]);
		}
	
	if($_GET["rtypes"] != '') {
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_typeofrequest",false);
		$page->businessobject->items->seteditable("u_approverby",false);
	}
	$code = "APP-".$objRs->fields["u_stageno"].$_SESSION["userid"];
	$objRsCode->queryopen("SELECT COUNT(code)+1 as code FROM u_tkapprovalform WHERE code like '".$code."%'");
	if ($objRsCode->recordcount() != 0) {
		while ($objRsCode->queryfetchrow("NAME")) {
			$page->setitem("code",$code.$objRsCode->fields["code"]);
			}
	} else {
		$page->setitem("code",$code."1");	
	}
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
	global $page;
	$page->setvar("formAccess","R");
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

$objRs->queryopen("SELECT u_offon_pf FROM u_prglobalsetting");
while ($objRs->queryfetchrow("NAME")) {
	if($objRs->fields["u_offon_pf"] == 1) {
		$page->businessobject->items->setvisible("u_profitcenter",false);
		//$page->businessobject->items->setvisible("u_approverby",false);
	} else {
		$page->businessobject->items->setvisible("u_profitcenter",true);
		//$page->businessobject->items->setvisible("u_approverby",true);
	}
}

$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->seteditable("name",false);
$page->businessobject->items->seteditable("u_dayname",false);

$page->businessobject->items->setcfl("u_date","Calendar");

//$objGrids[0]->selectionmode = 2;
$objGrids[0]->columnwidth("u_empid",15);
$objGrids[0]->columnwidth("u_empname",35);
$objGrids[0]->columnwidth("u_typeofrequest",20);
$objGrids[0]->columnwidth("u_date",12);
$objGrids[0]->columnwidth("u_approved",8);
$objGrids[0]->columnwidth("u_denied",8);
$objGrids[0]->columninput("u_approved","type","checkbox");
$objGrids[0]->columninput("u_approved","value",1);

$objGrids[0]->columninput("u_denied","type","checkbox");
$objGrids[0]->columninput("u_denied","value",1);
$objGrids[0]->columnvisibility("u_status",false);
$objGrids[0]->columnvisibility("u_codes",false);
$objGrids[0]->columncfl("u_empname","OpenCFLfs()");
$objGrids[0]->columnattributes("u_empid","disabled");
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->dataentry = false;

$addoptions = false;
$page->toolbar->setaction("new",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("print",false);

?> 

