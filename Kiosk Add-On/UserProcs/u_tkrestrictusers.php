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

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSKiosk");

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

function onDrawGridColumnLabelGPSKiosk($tablename,$column,$row,&$label) {
	global $objRs;
	global $page;
	switch ($tablename) {
		case "T1":
			if ($column=="u_section") {
				$objRs->queryopen("SELECT name FROM u_prsection WHERE code = '".$label."'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} else if ($column=="u_costcenter") {
				$objRs->queryopen("SELECT name FROM u_prprofitcenter WHERE code = '".$label."'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}
			break;
		default:
			break;
	}
}

$page->businessobject->items->setcfl("name","OpenCFLfs()");
$page->businessobject->items->setcfl("u_username","OpenCFLfs()");
$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->seteditable("u_userid",false);
?> 

