<?php

include_once("./utils/companies.php"); 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hishealthins.php");
 
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

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");
$page->businessobject->events->add->fetchDBRow("onFetchDBRowGPSHIS");


$u_trxtype="";
$u_trxsrc="";

function onFetchDBRowGPSHIS($objrs) {
	global $canceloption;
	switch ($objrs->dbtable) {
		case "u_hisposins":
			if ($objrs->fields["U_XMTALNO"]!="") $canceloption = false;
			break;
	}
	
}


function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	global $u_trxsrc;
	$objRs = new recordset(null,$objConnection);
	$u_trxtype = $page->getitemstring("u_trxtype");
	$u_trxsrc = $page->getitemstring("u_trxsrc");
	if ($page->getitemstring("u_trxtype")=="") {
		$page->objectdoctype = "INCOMINGPAYMENT";
	} else {
		$page->objectdoctype = "CREDITMEMO";
	}	
	
	if ($u_trxtype=="PHARMACY") {
		$objRs->queryopen("select u_pharmapos from u_hissetup");
		if ($objRs->queryfetchrow()) {
			if ($objRs->fields[0]==0) {
				header ('Location: accessdenied.php?&requestorId=$requestorId&messageText=This page is only available if Medicines and Supplies Cash Sales Payments is in Pharmacy.'); 
	
			}
		}
	}	
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	global $u_trxsrc;
	$u_hissetupdata = getu_hissetup("U_OPDPRICELIST,U_DFLTOPDISCCODE");
	$page->setitem("u_disccode",$u_hissetupdata["U_DFLTOPDISCCODE"]);
	$page->setitem("u_pricelist",$u_hissetupdata["U_OPDPRICELIST"]);
	$page->setitem("u_docdate",currentdate());
	$page->setitem("u_doctime",currenttime());
	$page->setitem("u_trxtype",$u_trxtype);
	$page->setitem("u_trxsrc",$u_trxsrc);
	$page->setitem("u_reftype","WI");
	if ($u_trxtype=="PHARMACY") {
		$page->setitem("u_payreftype","MEDSUP");
	} else {
		$page->setitem("u_payreftype","CHRG");
	}
	
	$objRs = new recordset(null,$objConnection);
	if ($u_trxtype=="") {
		$objRs->queryopen("select u_inpayseries from users where userid='".$_SESSION["userid"]."' and u_inpayseries<>''");
		if ($objRs->queryfetchrow("NAME")) {
			$page->setitem("docseries", $objRs->fields["u_inpayseries"]);
			$page->setitem("docno", getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("docseries"),formatDateToDB(currentdate()),$objConnection,false));
		}	
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

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T3":
			if ($column=="u_inscode") {
				$objRs->queryopen("select name from u_hishealthins where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}	
			break;
		case "T4":
			if ($column=="u_reftype") {
				switch ($label) {
					case "CHRG": $label = "Charges"; break;
				}
			}	
			break;
	}
}

if ($page->getitemstring("u_trxtype")=="") {
	$page->objectdoctype = "INCOMINGPAYMENT";
	$pageHeader = "Cash Sales - Official Receipt";
} else {
	$page->objectdoctype = "CREDITMEMO";
	$pageHeader = "Cash Sales - Credit Memo";
}	

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_payrefno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");

$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("docseries",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_dueamount",false);
$page->businessobject->items->seteditable("u_chngamount",false);
$page->businessobject->items->seteditable("u_disccode",true);
$page->businessobject->items->seteditable("u_colltype",false);
$page->businessobject->items->seteditable("u_cashierid",false);

$page->businessobject->items->setvisible("u_cancelledby",false);
$page->businessobject->items->setvisible("u_arcmdocno",false);
$page->businessobject->items->setvisible("u_apcmdocno",false);
$page->businessobject->items->setvisible("u_jvcndocno",false);
$page->businessobject->items->setvisible("u_cancelledremarks",false);

$objGrids[0]->columnwidth("u_creditcard",20);
$objGrids[0]->columnwidth("u_creditcardno",20);
$objGrids[0]->columnwidth("u_creditcardname",25);
$objGrids[0]->columnwidth("u_approvalno",12);
$objGrids[0]->automanagecolumnwidth = false;

$objGrids[1]->columnwidth("u_itemcode",15);
$objGrids[1]->columnwidth("u_itemdesc",30);
$objGrids[1]->columnwidth("u_quantity",8);
$objGrids[1]->columnwidth("u_uom",5);
$objGrids[1]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[1]->columncfl("u_itemdesc","OpenCFLfs()");
//$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
//$objGrids[0]->columncfl("u_itemdesc","OpenCFLitems()");
$objGrids[1]->columnattributes("u_uom","disabled");
//$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[1]->columnattributes("u_linetotal","disabled");
//$objGrids[1]->columnvisibility("u_discperc",true);
//$objGrids[1]->columnvisibility("u_scdiscamount",true);

$objGrids[2]->columntitle("u_inscode","Health Benefit");
$objGrids[2]->columntitle("u_amount","Amount");
$objGrids[2]->columnwidth("u_inscode",25);
$objGrids[2]->columnwidth("u_claimno",15);
$objGrids[2]->columnwidth("u_memberid",15);
$objGrids[2]->columnwidth("u_membername",25);
$objGrids[2]->columnwidth("u_membertype",20);
$objGrids[2]->columnwidth("u_jvdocno",15);
$objGrids[2]->columnwidth("u_jvcndocno",15);
$objGrids[2]->columnwidth("u_xmtalno",15);
$objGrids[2]->columnwidth("u_xmtaldate",15);
$objGrids[2]->columnvisibility("u_hmo",false);
$objGrids[2]->columncfl("u_memberid","OpenCFLfs()");
$objGrids[2]->columndataentry("u_inscode","options",array("loadu_hishealthins4","",":"));
$objGrids[2]->columnlnkbtn("u_jvdocno","OpenLnkBtnJournalVouchers()");
$objGrids[2]->columnlnkbtn("u_jvcndocno","OpenLnkBtnJournalVouchers()");
$objGrids[2]->automanagecolumnwidth = false;

$objGrids[3]->columninput("u_selected","type","checkbox");
$objGrids[3]->columninput("u_selected","value",1);
$objGrids[3]->columninput("u_credited","type","text");
$objGrids[3]->columninput("u_credited","disabled","true");
$objGrids[3]->columnlnkbtn("u_refno","OpenLnkBtnCreditRefNo()");
$objGrids[3]->dataentry = false;

$addoptions = false;
$deleteoption = false;
$canceloption = true;
?> 

