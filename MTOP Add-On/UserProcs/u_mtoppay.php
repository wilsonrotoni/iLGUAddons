<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSMTOP");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSMTOP");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSMTOP");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSMTOP");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSMTOP");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSMTOP");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSMTOP");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSMTOP");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSMTOP");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSMTOP");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSMTOP");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSMTOP");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSMTOP");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSMTOP");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSMTOP");
function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
	global $page;
	global $objGrids;
	if ($table=="T1" && $column=="u_selected" && $page->getitemstring("docstatus")=="D") {
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
}

function onCustomActionGPSMTOP($action) {
	return true;
}

function onBeforeDefaultGPSMTOP() { 
	return true;
}

function onAfterDefaultGPSMTOP() { 
        global $page;
	$page->setitem("u_year",date('Y'));
	$page->setitem("u_assdate",currentdate());
	$page->setitem("docstatus","D");
	return true;
}

function onPrepareAddGPSMTOP(&$override) { 
	return true;
}

function onBeforeAddGPSMTOP() { 
	return true;
}

function onAfterAddGPSMTOP() { 
	return true;
}

function onPrepareEditGPSMTOP(&$override) { 
	return true;
}

function onBeforeEditGPSMTOP() { 
	return true;
}

function onAfterEditGPSMTOP() { 
	return true;
}

function onPrepareUpdateGPSMTOP(&$override) { 
	return true;
}

function onBeforeUpdateGPSMTOP() { 
	return true;
}

function onAfterUpdateGPSMTOP() { 
	return true;
}

function onPrepareDeleteGPSMTOP(&$override) { 
	return true;
}

function onBeforeDeleteGPSMTOP() { 
	return true;
}

function onAfterDeleteGPSMTOP() { 
	return true;
}

//$page->objectdoctype = "U_MTOPPAY";

$page->businessobject->items->setcfl("u_assdate","Calendar");
//
//$page->businessobject->items->seteditable("u_yearfrom",false);
//$page->businessobject->items->seteditable("u_arpno",false);
////$page->businessobject->items->seteditable("u_tdno",false);
//$page->businessobject->items->seteditable("u_lotno",false);
//$page->businessobject->items->seteditable("u_tctno",false);
//$page->businessobject->items->seteditable("u_declaredowner",false);
//$page->businessobject->items->seteditable("u_location",false);
//$page->businessobject->items->seteditable("u_rate",false);
//$page->businessobject->items->seteditable("u_sefrate",false);
//$page->businessobject->items->seteditable("u_seftax",false);
$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("u_totalamount",false);
//$page->businessobject->items->seteditable("u_tax",false);
//$page->businessobject->items->seteditable("u_totaltaxbefdisc",false);
//$page->businessobject->items->seteditable("u_totaltaxamount",false);
//$page->businessobject->items->seteditable("u_prevyeartaxdue",false);
//$page->businessobject->items->seteditable("u_prevyearpenalty",false);
//$page->businessobject->items->seteditable("u_tax",false);
//$page->businessobject->items->seteditable("u_sefpenalty",false);
//$page->businessobject->items->seteditable("u_penalty",false);
//$page->businessobject->items->seteditable("u_discamount",false);
//$page->businessobject->items->seteditable("u_sefdiscamount",false);
//$page->businessobject->items->seteditable("u_kind",false);

$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->columnwidth("u_selected",1);
//$objGrids[0]->columnwidth("u_kind",10);
//$objGrids[0]->columnwidth("u_pinno",20);
//$objGrids[0]->columnwidth("u_arpno",18);
//$objGrids[0]->columnwidth("u_tdno",16);
//$objGrids[0]->columnwidth("u_noofyrs",8);
//$objGrids[0]->columnwidth("u_yrfr",8);
//$objGrids[0]->columnwidth("u_yrto",8);
//$objGrids[0]->columnwidth("u_penalty",8);
//$objGrids[0]->columnwidth("u_sef",8);
//$objGrids[0]->columnwidth("u_taxdue",8);
//$objGrids[0]->columnwidth("u_taxdisc",6);
//$objGrids[0]->columnwidth("u_sefdisc",6);
//$objGrids[0]->columnwidth("u_sefpenalty",8);
//$objGrids[0]->columnwidth("u_discperc",8);
//$objGrids[0]->columnwidth("u_taxtotal",8);
//$objGrids[0]->columnwidth("u_seftotal",8);
//$objGrids[0]->columnwidth("u_penaltyadj",8);
//$objGrids[0]->columnwidth("u_sefpenaltyadj",11);
//$objGrids[0]->columnwidth("u_taxdiscadj",9);
//$objGrids[0]->columnwidth("u_sefdiscadj",9);
//$objGrids[0]->columnwidth("u_billdate",8);
//$objGrids[0]->columnwidth("u_paymode",30);
//$objGrids[0]->columnvisibility("u_taxdisc",true);
//$objGrids[0]->columnvisibility("u_sefdisc",true);
//$objGrids[0]->columnvisibility("u_penaltyadj",true);
//$objGrids[0]->columnvisibility("u_sefpenaltyadj",true);
//$objGrids[0]->columnvisibility("u_taxdiscadj",true);
//$objGrids[0]->columnvisibility("u_sefdiscadj",true);
//$objGrids[0]->columnvisibility("u_billdate",true);
//$objGrids[0]->columntitle("u_noofyrs","Years");
//$objGrids[0]->columntitle("u_yrfr","From");
//$objGrids[0]->columntitle("u_yrto","To");
//$objGrids[0]->columnlnkbtn("u_pinno","openupdpays()");
//$objGrids[0]->columntitle("u_billdate","Bill Date");

//
//$objGrids[1]->dataentry = false;
//$objGrids[1]->columnwidth("u_kind",10);
//$objGrids[1]->columnwidth("u_pin",20);
//$objGrids[1]->columnwidth("u_arpno",18);
//$objGrids[1]->columnwidth("u_tdno",16);
//$objGrids[1]->height = 100;
//$objGrids[1]->width = 800;
//$addoptions = false;

$objMaster->reportaction = "QS";


?> 

