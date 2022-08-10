<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
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
	global $objConnection;
	global $page;
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select docno from u_hislabtestrfs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_examno='".$page->getitemstring("u_examno")."' and u_itemcode='".$page->getitemstring("u_itemcode")."'");
	if ($objRs->queryfetchrow()) {
		$page->setkey($objRs->fields[0]);
		modeEdit();
		return false;
	}

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

$page->businessobject->items->seteditable("u_examno",false);
$page->businessobject->items->seteditable("u_refno",false);
$page->businessobject->items->seteditable("u_reftype",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_itemcode",false);
$page->businessobject->items->seteditable("u_itemdesc",false);
$page->businessobject->items->seteditable("u_amount",false);

$objGrids[0]->columnwidth("u_apdocno",15);
$objGrids[0]->columnattributes("u_doctorname","disabled");
$objGrids[0]->columnattributes("u_apdocno","disabled");
$addoptions = false;
$deleteoption = false;
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);

?> 

