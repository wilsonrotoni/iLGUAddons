<?php

include_once("../Addons/GPS/HIS Add-On/Userprocs/sls/u_hishealthins.php"); 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$data = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	
	$page->objectdoctype = "CREDITMEMO";
	
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $objGrids;
	
	$page->privatedata["docstatus"] = "";
	
	return true;
}

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
			switch ($column) {
				case "u_feetype":
					/*switch ($label) {
						case "Hospital Fees": $label = "HF"; break;
						case "Professional Fees": $label = "PF"; break;
						case "Professional Materials": $label = "PM"; break;
					}*/
					break;
					
			}
	}
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
	global $page;
	$page->privatedata["docstatus"] = $page->getitemstring("docstatus");
	
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

$page->objectdoctype = "CREDITMEMO";

$page->businessobject->items->setcfl("u_docdate","Calendar");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_variance",false);

$objGrids[0]->columntitle("u_feetype","Tp");
$objGrids[0]->columntitle("u_wtaxperc","WTax %");
$objGrids[0]->columnwidth("u_refno",11);
$objGrids[0]->columnwidth("u_refdate",9);
$objGrids[0]->columnwidth("u_refno2",10);
$objGrids[0]->columnwidth("u_patientname",20);
$objGrids[0]->columnwidth("u_reftype",12);
$objGrids[0]->columnwidth("u_feetype",2);
$objGrids[0]->columnwidth("u_doctorname",17);
$objGrids[0]->columnwidth("u_discperc",5);
$objGrids[0]->columnwidth("u_wtaxperc",5);
//$objGrids[0]->columnwidth("u_applied",15);
$objGrids[0]->columninput("u_discperc","type","text");
$objGrids[0]->columninput("u_wtaxperc","type","text");
$objGrids[0]->columninput("u_applied","type","text");
$objGrids[0]->columncfl("u_refno","OpenCFLfs()");
//$objGrids[0]->columnlnkbtn("u_refno","OpenLnkBtnRefNoGPSHIS()");
$objGrids[0]->columnvisibility("u_reftype",false);
$objGrids[0]->columnvisibility("u_feetype",false);
$objGrids[0]->columnattributes("u_feetype","disabled");
$objGrids[0]->columninput("u_discperc","disabled","{u_balance}>0");
$objGrids[0]->columninput("u_wtaxperc","disabled","{u_balance}>0");
//$objGrids[0]->columnattributes("u_wtaxperc","disabled");
//$objGrids[0]->columnattributes("u_applied","disabled");
//$objGrids[0]->automanagecolumnwidth = false;

$addoptions = true;
$deleteoption = false;

//$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("new",false);

?> 

