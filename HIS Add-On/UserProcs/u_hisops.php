<?php

include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisdoctorservicetypes.php");
include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hishealthins.php");

$enumdocstatus["Active"] = "Active";
$enumdocstatus["Discharged"] = "Discharged";
$enumdocstatus["Admitted"] = "Admitted";

unset($enumdocstatus["O"]); 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
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
$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSHIS");

$expireoption = true;
$dischargeoption = true;

$trxrunbal=0;

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $objGrids;
	global $userdepartment;
	$u_hissetupdata = getu_hissetup("U_DFLTOPD,U_OPDPRICELIST,U_DFLTOPPAYTERM,U_DFLTOPBILLING,U_DFLTOPDISCCODE");
	$page->setitem("docstatus","Active");
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
	if ($userdepartment!="") $page->setitem("u_department",$userdepartment);
	else $page->setitem("u_department",$u_hissetupdata["U_DFLTOPD"]);
	$page->setitem("u_paymentterm",$u_hissetupdata["U_DFLTOPPAYTERM"]);
	$page->setitem("u_billing",$u_hissetupdata["U_DFLTOPBILLING"]);
	$page->setitem("u_prepaid",iif($u_hissetupdata["U_DFLTOPBILLING"]=="1",0,1));
	$page->setitem("u_disccode",$u_hissetupdata["U_DFLTOPDISCCODE"]);
	$page->setitem("u_pricelist",$u_hissetupdata["U_OPDPRICELIST"]);
	$page->setitem("u_assistedby",$_SESSION["userid"]);
	$page->setitem("u_arrivedby","Others");
	$page->setitem("u_patientid",$page->getvarstring("patientidtoreg"));
	$objGrids[1]->setdefaultvalue("u_startdate",currentdate());
	$objGrids[1]->setdefaultvalue("u_starttime",currenttime());
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
	
	retrievePatientPendingItemsGPSHIS('OP',$page->getitemstring("docno"));
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


 
$objRs = new recordset(null,$objConnection); 


function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T3":
			if ($column=="u_type") {
				$objRs->queryopen("select name from u_hislabtesttypes where code='".$label."'");
				if ($objRs->queryfetchrow()) $label .= " - " . $objRs->fields[0];
			}	
			if ($column=="u_status") {
				switch ($label) {
					case "O": $label = "Open"; break;
					case "C": $label = "Closed"; break;
				}
			}
			break;
		case "T8":
			if ($column=="u_inscode") {
				$objRs->queryopen("select name from u_hishealthins where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $label .  " - " . $objRs->fields["name"];
			}	
			break;
		case "T12":
			if ($column=="u_balance") {
				$trxrunbal+=formatNumericToDB($label);
			} elseif ($column=="u_runbal") {
				$label = formatNumericAmount($trxrunbal);
			} elseif ($column=="u_department") {
				$objRs->queryopen("select name from u_hissections where code='".$label."'");
				if ($objRs->queryfetchrow("NAME")) $label = $objRs->fields["name"];
			} elseif ($column=="u_docstatus") {
				switch ($label) {
					case "O": $label = "Open"; break;
					case "C": $label = "Closed"; break;
				}
			}
			break;
		default:
			if (setGridColumnLabelPatientPendingItemsGPSHIS($tablename,$column,$row,$label)) break;	
			break;
	}
}

function onPrepareChildEditGPSHIS($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T10":	
			$filerExp = " ORDER BY U_VSDATE DESC, U_VSTIME DESC";
			break;
			
	}
	return true;
}

$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_tfstartdate","Calendar");
$page->businessobject->items->setcfl("u_expiredate","Calendar");
$page->businessobject->items->setcfl("u_vsdate","Calendar");
$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");

$page->businessobject->items->setcfl("u_tfroomno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_tfbedno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_resultrecovered",false);

$page->businessobject->items->seteditable("u_icdcode",false);
$page->businessobject->items->seteditable("u_icddesc",false);
$page->businessobject->items->seteditable("u_rvscode",false);
$page->businessobject->items->seteditable("u_rvsdesc",false);
$page->businessobject->items->seteditable("u_rvu",false);
$page->businessobject->items->seteditable("u_finaldoctorid",false);
$page->businessobject->items->seteditable("u_finaldoctorservice",false);
$page->businessobject->items->seteditable("u_finalremarks",false);
$page->businessobject->items->seteditable("u_billno",false);
$page->businessobject->items->seteditable("u_oldpatient",false);

$page->businessobject->items->seteditable("u_bmi",false);
$page->businessobject->items->seteditable("u_bmistatus",false);

$page->businessobject->items->seteditable("u_billing",false);
$page->businessobject->items->seteditable("u_prepaid",false);

$page->businessobject->items->seteditable("u_tfrateuom",false);

$page->businessobject->items->seteditable("u_billtermby",false);
$page->businessobject->items->seteditable("u_billremarks",false);
$page->businessobject->items->seteditable("u_casetype",false);

$page->businessobject->items->seteditable("u_enddate",false);
$page->businessobject->items->seteditable("u_endtime",false);
$page->businessobject->items->seteditable("u_dischargedby",false);

$page->businessobject->items->seteditable("u_creditlimit",false);
$page->businessobject->items->seteditable("u_totalcharges",false);
$page->businessobject->items->seteditable("u_availablecreditlimit",false);
$page->businessobject->items->seteditable("u_availablecreditperc",false);

//$page->businessobject->items->seteditable("u_tfdepartment",false);
$page->businessobject->items->seteditable("u_tfroomdesc",false);
//$page->businessobject->items->seteditable("u_tfpricelist",false);
$page->businessobject->items->seteditable("u_tfrate",false);

$objGrids[0]->columncfl("u_icdcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_icddesc","OpenCFLfs()");
//$objGrids[0]->columncfl("u_doctorid","OpenCFLfs()");

$objGrids[1]->columncfl("u_roomno","OpenCFLfs()");
$objGrids[1]->columncfl("u_bedno","OpenCFLfs()");
$objGrids[1]->columnwidth("u_roomno",8);
$objGrids[1]->columnwidth("u_bedno",10);
$objGrids[1]->columnwidth("u_rateuom",5);
$objGrids[1]->columnwidth("u_quantity",8);
$objGrids[1]->columnwidth("u_rate",10);
$objGrids[1]->columnwidth("u_amount",10);
$objGrids[1]->columnvisibility("u_isroomshared",false);
$objGrids[1]->columnvisibility("u_startdatetime",false);
$objGrids[1]->columnvisibility("u_enddatetime",false);
$objGrids[1]->columnattributes("u_rateuom","disabled");
$objGrids[1]->columnattributes("u_quantity","disabled");
$objGrids[1]->columnattributes("u_amount","disabled");
$objGrids[1]->columnattributes("u_enddate","disabled");
$objGrids[1]->columnattributes("u_endtime","disabled");

$objGrids[2]->columnlnkbtn("u_refno","OpenLnkBtnu_hislabtests()");
$objGrids[2]->dataentry = false;

$objGrids[3]->columnwidth("u_refno",12);
$objGrids[3]->columnwidth("u_amount",15);
$objGrids[3]->columnlnkbtn("u_refno","OpenLnkBtnu_hisconsultancyfees()");
$objGrids[3]->dataentry = false;

$objGrids[4]->columnlnkbtn("u_refno","OpenLnkBtnu_hismedsups()");
$objGrids[4]->dataentry = false;

$objGrids[5]->columnlnkbtn("u_refno","OpenLnkBtnu_hismiscs()");
$objGrids[5]->dataentry = false;

$objGrids[6]->columnlnkbtn("u_refno","OpenLnkBtnu_hissplrooms()");
$objGrids[6]->columnwidth("u_refno",12);
$objGrids[6]->columnwidth("u_roomno",8);
$objGrids[6]->columnwidth("u_bedno",10);
$objGrids[6]->columnwidth("u_rate",8);
$objGrids[6]->columnwidth("u_uom",5);
$objGrids[6]->columnwidth("u_quantity",8);
$objGrids[6]->columnwidth("u_starttime",10);
$objGrids[6]->columnwidth("u_endtime",10);
$objGrids[6]->columnvisibility("u_startdatetime",false);
$objGrids[6]->columnvisibility("u_enddatetime",false);
$objGrids[6]->columnattributes("u_enddate","disabled");
$objGrids[6]->columnattributes("u_endtime","disabled");
$objGrids[6]->dataentry = false;

$objGrids[7]->columnvisibility("u_hmo",false);
$objGrids[7]->columnwidth("u_memberid",15);
$objGrids[7]->columnwidth("u_membertype",20);
$objGrids[7]->columnwidth("u_membername",40);
$objGrids[7]->columntitle("u_inscode","Health Benefit");
$objGrids[7]->columncfl("u_memberid","OpenCFLfs()");
$objGrids[7]->columndataentry("u_inscode","options",array("loadu_hishealthins2","",":"));
$objGrids[7]->columnattributes("u_memberid","disabled");
$objGrids[7]->columnattributes("u_membertype","disabled");
$objGrids[7]->columnattributes("u_membername","disabled");

$objGrids[8]->columnwidth("u_paymentterm",20);
$objGrids[8]->columnwidth("u_disccode",20);
$objGrids[8]->columnwidth("u_remarks",40);
$objGrids[8]->columnwidth("u_prepaid",5);
$objGrids[8]->columnwidth("u_billing",6);
$objGrids[8]->columnvisibility("u_prepaidlab",false);
$objGrids[8]->columnvisibility("u_prepaidmedsup",false);
$objGrids[8]->columnvisibility("u_prepaidmisc",false);
$objGrids[8]->columnvisibility("u_prepaidsplroom",false);
$objGrids[8]->columnvisibility("u_prepaidpf",false);
$objGrids[8]->dataentry = false;
$objGrids[8]->automanagecolumnwidth = false;

$objGrids[9]->columnwidth("u_vsdate",10);
$objGrids[9]->columnwidth("u_bt_c",8);
$objGrids[9]->columnwidth("u_bt_f",8);
$objGrids[9]->columnwidth("u_bp_s",8);
$objGrids[9]->columnwidth("u_bp_d",8);
$objGrids[9]->columnwidth("u_rr",8);
$objGrids[9]->columnwidth("u_hr",8);
$objGrids[9]->columnwidth("u_o2sat",8);
$objGrids[9]->dataentry = false;
$objGrids[9]->automanagecolumnwidth = false;
$objGrids[9]->width=720;

$objGrids[10]->columncfl("u_name","OpenCFLfs()");

$objGrids[11]->columnwidth("u_docdate",9);
$objGrids[11]->columnwidth("u_doctime",6);
$objGrids[11]->columnwidth("u_docno",10);
$objGrids[11]->columnwidth("u_refno",10);
$objGrids[11]->columnwidth("u_docdesc",17);
$objGrids[11]->columnwidth("u_amount",10);
$objGrids[11]->columnwidth("u_runbal",10);
$objGrids[11]->columnwidth("u_docstatus",10);
$objGrids[11]->columntitle("u_runbal","Running Bal");
$objGrids[11]->columnlnkbtn("u_docno","OpenLnkBtnTrxNo()");
$objGrids[11]->dataentry = false;
$objGrids[11]->automanagecolumnwidth = false;

$objGrids[12]->columnwidth("u_name",30);
$objGrids[12]->columnwidth("u_frequency",25);
$objGrids[12]->columnwidth("u_quantity",25);

$objGrids[13]->columncfl("u_date","Calendar");
$objGrids[13]->columnwidth("u_date",12);
$objGrids[13]->columnwidth("u_action",100);

include_once("../Addons/GPS/HIS Add-On/UserProcs/utils/u_hispatientpendingitems.php");


$objrs = new recordset(null,$objConnection);
$userdepartment = "";
/*
$objrs->queryopen("select DEPARTMENT FROM EMPLOYEES WHERE USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	$userdepartment = $objrs->fields["DEPARTMENT"];
	//if ($userdepartment!="") $page->businessobject->items->seteditable("u_department",false);
}


$addoptions = false;
$deleteoption = false;
?> 

