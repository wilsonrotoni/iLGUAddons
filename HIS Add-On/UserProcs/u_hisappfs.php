<?php

	include_once("./sls/countries.php");
	include_once("./sls/banks.php");
	include_once("./sls/housebankaccounts.php"); 
//include_once("../Addons/GPS/HIS Add-On/Userprocs/sls/u_hishealthins.php"); 

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

$groupby = "";
$isgroup = false;

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$data = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $data;
	global $page;
	$data["u_docdate"] = $page->getitemstring("u_docdate");
	//if ($data["u_docdate"]=="") $data["u_docdate"] = currentdate();
	$data["u_preparedby"] = $page->getitemstring("u_preparedby");
	$data["u_gltype"] = $page->getitemstring("u_gltype");
	$data["u_glacctno"] = $page->getitemstring("u_glacctno");
	$data["u_glacctname"] = $page->getitemstring("u_glacctname");
	$data["u_bank"] = $page->getitemstring("u_bank");
	$data["u_bankacctno"] = $page->getitemstring("u_bankacctno");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $u_trxtype;
	global $objGrids;
	
	$page->privatedata["docstatus"] = "";
	
	$page->setitem("u_docdate",$data["u_docdate"]);
	
	$objRs = new recordset(null,$objConnection);
	
	$objGrids[0]->clear();
	$u_totalamount = 0;
	$u_totalwtax = 0;
	$u_totalpayment = 0;
	$u_totalbalance = 0;
	
	//$objRs->setdebug();
	if ($page->getitemstring("u_docdate")!="") {
		$page->setitem("u_preparedby",$data["u_preparedby"]);
		$page->setitem("u_glacctno",$data["u_glacctno"]);
		$page->setitem("u_glacctname",$data["u_glacctname"]);
		$page->setitem("u_gltype",$data["u_gltype"]);
		$page->setitem("u_bank",$data["u_bank"]);
		$page->setitem("u_bankacctno",$data["u_bankacctno"]);
		$objRs->queryopen("select a.objectcode, a.docno, a.docdate, a.bpcode, a.bpname, a.othertrxtype, a.totalamount, a.wtaxamount, a.wtaxcode, a.remarks from apinvoices a, suppliers b where a.bpcode=b.suppno and b.suppgroup='4' and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docdate<='".$page->getitemdate("u_docdate")."' and a.dueamount>0 
								union all
							select a.objectcode, a.docno, a.docdate, a.bpcode, a.bpname, a.othertrxtype, a.totalamount*-1 as totalamount, a.wtaxamount*-1 as wtaxamount, a.wtaxcode, a.remarks from apcreditmemos a, suppliers b where a.bpcode=b.suppno and b.suppgroup='4' and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docdate<='".$page->getitemdate("u_docdate")."' and a.dueamount>0 
			order by bpname, docdate, docno");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_selected",1);
			$objGrids[0]->setitem(null,"u_refdate",formatDateToHttp($objRs->fields["docdate"]));
			$objGrids[0]->setitem(null,"u_refno",$objRs->fields["docno"]);
			$objGrids[0]->setitem(null,"u_reftype",$objRs->fields["objectcode"]);
			$objGrids[0]->setitem(null,"u_doctorid",$objRs->fields["bpcode"]);
			$objGrids[0]->setitem(null,"u_doctorname",$objRs->fields["bpname"]);
			$objGrids[0]->setitem(null,"u_wtaxcode",$objRs->fields["wtaxcode"]);
			$objGrids[0]->setitem(null,"u_trxtype",$objRs->fields["othertrxtype"]);
			$objGrids[0]->setitem(null,"u_remarks",$objRs->fields["remarks"]);
			$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["totalamount"]));
			$objGrids[0]->setitem(null,"u_wtax",formatNumericAmount($objRs->fields["wtaxamount"]));
			$objGrids[0]->setitem(null,"u_payment",formatNumericAmount($objRs->fields["totalamount"]-$objRs->fields["wtaxamount"]));
			$u_totalamount+=$objRs->fields["totalamount"];
			$u_totalwtax+=$objRs->fields["wtaxamount"];
			$u_totalpayment+=$objRs->fields["totalamount"]-$objRs->fields["wtaxamount"];
		}
	}
	$page->setitem("u_totalamount",formatNumericAmount($u_totalamount));
	$page->setitem("u_totalwtax",formatNumericAmount($u_totalwtax));
	$page->setitem("u_totalpayment",formatNumericAmount($u_totalpayment));
	$page->setitem("u_totalbalance",formatNumericAmount(0));
	return true;
}


function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $groupby;
	global $isgroup;
	global $page;
	global $objGrids;
	switch ($column) {
		case "u_custno":
			//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
			if ($groupby!=$objGrids[0]->getitemstring($row,"u_custno")) {
				$groupby = $objGrids[0]->getitemstring($row,"u_custno");
				$isgroup = false;
			} else {
				$isgroup = true;
				$label = "";
			}	
			break;
		case "u_custname":
			if ($isgroup) $label = "";
			break;
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

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_glacctno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_glacctname","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totaldeduction",false);

$objGrids[0]->columnwidth("u_selected",2);
$objGrids[0]->columnwidth("u_doctorid",8);
$objGrids[0]->columnwidth("u_doctorname",30);
$objGrids[0]->columnwidth("u_wtaxcode",10);
$objGrids[0]->columnwidth("u_refdate",9);
$objGrids[0]->columnwidth("u_refno",12);
$objGrids[0]->columnwidth("u_reftype",15);
$objGrids[0]->columnwidth("u_trxtype",20);
$objGrids[0]->columnwidth("u_wtax",8);
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columnlnkbtn("u_refno","OpenLnkBtnRefNo()");
$objGrids[0]->columnvisibility("u_wtaxcode",false);
$objGrids[0]->columnvisibility("u_trxtype",false);
$objGrids[0]->columnvisibility("u_reftype",false);
$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;

$addoptions = true;
$deleteoption = false;

//$page->toolbar->setaction("update",false);
$page->toolbar->setaction("new",false);

?> 

