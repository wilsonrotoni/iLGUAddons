<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

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
	global $objConnection;
	global $page;
	global $objRs;
	global $objGridSummary;
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select b.u_reftype, b.u_refno, b.u_startdate, min(b.u_date) as u_chargestartdate, max(b.u_date) as u_chargeenddate, sum(b.u_amount) as u_amount from u_hisguaranteeletters a inner join u_hisguaranteelettercharges b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$page->getitemstring("docno") ."' group by b.u_reftype, b.u_refno order by b.u_startdate asc, b.u_refno asc");
	//var_dump($objRs->sqls);
	$objGridSummary->clear();
	while ($objRs->queryfetchrow("NAME")) {
		$objGridSummary->addrow();
		$objGridSummary->setitem(null,"u_startdate",formatDateToHttp($objRs->fields["u_startdate"]));
		$objGridSummary->setitem(null,"u_chargestartdate",formatDateToHttp($objRs->fields["u_chargestartdate"]));
		$objGridSummary->setitem(null,"u_chargeenddate",formatDateToHttp($objRs->fields["u_chargeenddate"]));
		$objGridSummary->setitem(null,"u_reftype",$objRs->fields["u_reftype"]);
		$objGridSummary->setitem(null,"u_refno",$objRs->fields["u_refno"]);
		$objGridSummary->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
	}

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

$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");
$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");

$page->businessobject->items->seteditable("docstatus",false);

$objGrids[0]->columncfl("u_date","Calendar");
$objGrids[0]->columncfl("u_refno","OpenCFLfs()");
$objGrids[0]->columnwidth("u_date",12);
$objGrids[0]->columnwidth("u_reftype",15);
$objGrids[0]->columnwidth("u_refno",15);

$objGridSummary = new grid("T10");
$objGridSummary->addcolumn("u_reftype");
$objGridSummary->addcolumn("u_refno");
$objGridSummary->addcolumn("u_startdate");
$objGridSummary->addcolumn("u_chargestartdate");
$objGridSummary->addcolumn("u_chargeenddate");
$objGridSummary->addcolumn("u_amount");
$objGridSummary->columntitle("u_reftype","Ref Type");
$objGridSummary->columntitle("u_refno","Ref No.");
$objGridSummary->columntitle("u_startdate","Ref Date");
$objGridSummary->columntitle("u_chargestartdate","Charges Start Date");
$objGridSummary->columntitle("u_chargeenddate","Charges End Date");
$objGridSummary->columntitle("u_amount","Amount");
$objGridSummary->columnwidth("u_reftype",10);
$objGridSummary->columnwidth("u_refno",15);
$objGridSummary->columnwidth("u_startdate",10);
$objGridSummary->columnwidth("u_chargestartdate",15);
$objGridSummary->columnwidth("u_chargeenddate",15);
$objGridSummary->columnwidth("u_amount",10);
$objGridSummary->columnalignment("u_amount","right");
$objGridSummary->dataentry = false;

$addoptions = false;

?> 

