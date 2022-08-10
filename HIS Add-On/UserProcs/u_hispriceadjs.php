<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");

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

$u_trxtype="";

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	$u_trxtype = $page->getitemstring("u_trxtype");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $userdepartment;
	global $u_trxtype;
	global $edtopt;
	$u_hissetupdata = getu_hissetup("U_DFLTPHARMADEPT,U_DFLTLABDEPT,U_DFLTRADIODEPT,U_DFLTPHARMATFS, U_DFLTIPD, U_DFLTOPD, U_DFLTHEARTSTATIONDEPT, U_STOCKLINK, U_PREPAIDIPLAB, U_PREPAIDOPLAB, U_PHAVATPOS");
	
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
	$page->setitem("u_creditby",$_SESSION["userid"]);
	$page->setitem("u_trxtype",$u_trxtype);
	if ($u_trxtype!="BILLING") {
		if ($userdepartment!="") $page->setitem("u_department",$userdepartment);
		elseif ($page->getitemstring("u_trxtype")=="XRAY") $page->setitem("u_department",$u_hissetupdata["U_DFLTRADIODEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="CTSCAN") $page->setitem("u_department",$u_hissetupdata["U_DFLTRADIODEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="ULTRASOUND") $page->setitem("u_department",$u_hissetupdata["U_DFLTRADIODEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") $page->setitem("u_department",$u_hissetupdata["U_DFLTHEARTSTATIONDEPT"]);
		else $page->setitem("u_department",$u_hissetupdata["U_DFLTLABDEPT"]);
		if ($userdepartment!="") $page->businessobject->items->seteditable("u_department",false);
	}
		
	$page->setitem("u_stocklink",$u_hissetupdata["U_STOCKLINK"]);	
	
	$page->privatedata["prepaidip"] = $u_hissetupdata["U_PREPAIDIPLAB"];
	$page->privatedata["prepaidop"] = $u_hissetupdata["U_PREPAIDOPLAB"];

	$page->privatedata["phavatpos"] = $u_hissetupdata["U_PHAVATPOS"];

	if ($page->getitemstring("u_trxtype")=="IP") {
		$page->setitem("u_prepaid",$page->privatedata["prepaidip"]);
	} else $page->setitem("u_prepaid",$page->privatedata["prepaidop"]);

	if ($edtopt=="testpreview") {
		$page->setitem("docseries",-1);
		$page->setitem("docno",-1);
		$page->setitem("docstatus","-");
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
	global $objConnection;
	global $page;
	global $objGrids;
	global $objGrid;
	
	$objRs = new recordset(null,$objConnection);
	
	$objRs->queryopen("select * from u_hislabsubtests where u_labtestno='".$page->getitemstring("docno")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"code",$objRs->fields["CODE"]);
		$objGrid->setitem(null,"name",$objRs->fields["NAME"]);
		$objGrid->setitem(null,"startdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
		$objGrid->setitem(null,"starttime",$objRs->fields["U_STARTTIME"]);
		$objGrid->setitem(null,"enddate",formatDateToHttp($objRs->fields["U_ENDDATE"]));
		$objGrid->setitem(null,"endtime",$objRs->fields["U_ENDTIME"]);
		$objGrid->setitem(null,"rowstat","E");
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

$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_reqdate","Calendar");
$page->businessobject->items->setcfl("u_specimendate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_chargeno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_paymentterm",false);
$page->businessobject->items->seteditable("u_prepaid",false);
$page->businessobject->items->seteditable("u_adjustedby",false);

$page->businessobject->items->seteditable("u_amount",false);


$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columncfl("u_doctorname","OpenCFLfs()");
$objGrids[0]->columnwidth("u_itemgroup",15);
$objGrids[0]->columnwidth("u_itemcode",15);
$objGrids[0]->columnwidth("u_itemdesc",40);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columnwidth("u_unitprice",10);
$objGrids[0]->columnwidth("u_actprice",10);
$objGrids[0]->columnwidth("u_adjprice",10);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_vatamount",8);
$objGrids[0]->columnwidth("u_doctorname",30);
$objGrids[0]->columnwidth("u_remarks",30);
$objGrids[0]->columntitle("u_quantity","Qty");
$objGrids[0]->columnattributes("u_uom","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->columnvisibility("u_itemcode",false);
$objGrids[0]->columnvisibility("u_itemgroup",false);
$objGrids[0]->columnvisibility("u_vatamount",false);
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columninput("u_actprice","type","text");
$objGrids[0]->columndataentry("u_selected","type","checkbox");
$objGrids[0]->columndataentry("u_selected","value",1);
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->dataentry = false;

$objrs = new recordset(null,$objConnection);
$userdepartment = "";
/*
$objrs->queryopen("select DEPARTMENT FROM EMPLOYEES WHERE USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	$userdepartment = $objrs->fields["DEPARTMENT"];
	
}

$page->toolbar->setaction("navigation",false);

$addoptions = false;
$deleteoption = false;
$canceloption = true;

//$page->bodyclass = "yui-skin-sam";
?> 

