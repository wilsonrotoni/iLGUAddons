<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

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

$data = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $data;
	$data["u_docdate"] = $page->getitemstring("u_docdate");
	//if ($data["u_docdate"]=="") $data["u_docdate"] = currentdate();
	$data["u_department"] = $page->getitemstring("u_department");
	$data["u_meal"] = $page->getitemstring("u_meal");
	$data["u_diettype"] = $page->getitemstring("u_diettype");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $objGrids;
	$page->setitem("u_docdate",$data["u_docdate"]);
	$page->setitem("u_department",$data["u_department"]);
	$page->setitem("u_meal",$data["u_meal"]);
	$page->setitem("u_diettype",$data["u_diettype"]);
	
	$objRs = new recordset(null,$objConnection);
	
	$objGrids[0]->clear();
	$hf = 0;
	$pf = 0;
	$total = 0;
	
	$filterExp = "";
	$filterExp = genSQLFilterDate("a.U_REQUESTDATE",$filterExp,$page->getitemstring("u_docdate"));
	$filterExp = genSQLFilterString("a.U_DEPARTMENT",$filterExp,$page->getitemstring("u_department"));
	$filterExp = genSQLFilterString("a.U_MEAL",$filterExp,$page->getitemstring("u_meal"));
	$filterExp = genSQLFilterString("a.U_DIETTYPE",$filterExp,$page->getitemstring("u_diettype"));
	if ($filterExp!="") $filterExp = " AND " . $filterExp;
	//$objRs->setdebug();
	$objRs->queryopen("select a.DOCNO, a.U_DEPARTMENT, a.U_REQUESTDATE, a.U_REQUESTTIME, a.U_REFTYPE, a.U_REFNO, a.U_PATIENTID, a.U_PATIENTNAME, a.U_AGE, a.U_RELIGION, a.U_HEIGHT_FT, a.U_HEIGHT_IN, a.U_WEIGHT_KG, a.U_MEAL, a.U_DIETTYPE, a.U_REMARKS from u_hisdiets a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','C') $filterExp ORDER BY a.U_REQUESTDATE, a.U_DEPARTMENT");
	//var_dump($objRs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_selected",1);
		$objGrids[0]->setitem(null,"u_department",$objRs->fields["U_DEPARTMENT"]);
		$objGrids[0]->setitem(null,"u_requestno",$objRs->fields["DOCNO"]);
		$objGrids[0]->setitem(null,"u_requestdate",formatDateToHttp($objRs->fields["U_REQUESTDATE"]));
		$objGrids[0]->setitem(null,"u_requesttime",substr($objRs->fields["U_REQUESTTIME"],0,5));
		$objGrids[0]->setitem(null,"u_reftype",$objRs->fields["U_REFTYPE"]);
		$objGrids[0]->setitem(null,"u_refno",$objRs->fields["U_REFNO"]);
		$objGrids[0]->setitem(null,"u_patientid",$objRs->fields["U_PATIENTID"]);
		$objGrids[0]->setitem(null,"u_patientname",$objRs->fields["U_PATIENTNAME"]);
		$objGrids[0]->setitem(null,"u_age",$objRs->fields["U_AGE"]);
		$objGrids[0]->setitem(null,"u_religion",$objRs->fields["U_RELIGION"]);
		$objGrids[0]->setitem(null,"u_height_ft",$objRs->fields["U_HEIGHT_FT"]);
		$objGrids[0]->setitem(null,"u_height_in",$objRs->fields["U_HEIGHT_IN"]);
		$objGrids[0]->setitem(null,"u_weight_kg",formatNumericAmount($objRs->fields["U_WEIGHT_KG"]));
		$objGrids[0]->setitem(null,"u_meal",$objRs->fields["U_MEAL"]);
		$objGrids[0]->setitem(null,"u_diettype",$objRs->fields["U_DIETTYPE"]);
		$objGrids[0]->setitem(null,"u_remarks",$objRs->fields["U_REMARKS"]);
	}
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

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalhfamount",false);
$page->businessobject->items->seteditable("u_totalpfamount",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columnwidth("u_requesttime",5);
$objGrids[0]->columnwidth("u_patientid",12);
$objGrids[0]->columnwidth("u_patientname",30);
$objGrids[0]->columnwidth("u_department",20);
$objGrids[0]->columnwidth("u_religion",10);
$objGrids[0]->columnwidth("u_age",3);
$objGrids[0]->columnwidth("u_weight_kg",10);
$objGrids[0]->columnwidth("u_height_ft",10);
$objGrids[0]->columnwidth("u_height_in",10);
$objGrids[0]->columnwidth("u_meal",8);
$objGrids[0]->columnwidth("u_diettype",15);
$objGrids[0]->columnwidth("u_remarks",20);
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columnvisibility("u_requestno",false);
$objGrids[0]->columnvisibility("u_reftype",false);
$objGrids[0]->columnvisibility("u_refno",false);
$objGrids[0]->dataentry = false;

$addoptions = false;
$deleteoption = false;

$page->toolbar->setaction("update",false);
$page->toolbar->setaction("new",false);


?> 

