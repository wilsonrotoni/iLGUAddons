<?php

include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisdoctorservicetypes.php");
include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hishealthins.php");
include_once("./sls/pricelists.php");

$enumdocstatus["Active"] = "Active";
$enumdocstatus["Discharged"] = "Discharged";
$enumdocstatus["Cancelled"] = "Cancelled";

unset($enumdocstatus["O"]); 

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

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSProjects");
$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSHIS");

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
	$u_hissetupdata = getu_hissetup("U_DFLTIPD,U_DFLTIPPAYTERM,U_DFLTIPBILLING, U_DFLTIPDISCCODE");
	$page->setitem("docstatus","Active");
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
	$page->setitem("u_department",$u_hissetupdata["U_DFLTIPD"]);
	$page->setitem("u_paymentterm",$u_hissetupdata["U_DFLTIPPAYTERM"]);
	$page->setitem("u_billing",$u_hissetupdata["U_DFLTIPBILLING"]);
	$page->setitem("u_prepaid",iif($u_hissetupdata["U_DFLTIPBILLING"]=="1",0,1));
	$page->setitem("u_disccode",$u_hissetupdata["U_DFLTIPDISCCODE"]);
	$page->setitem("u_admittedby",$_SESSION["userid"]);
	$page->setitem("u_patientid",$page->getvarstring("patientidtoreg"));
	//$objGrids[1]->setdefaultvalue("u_startdate",currentdate());
	//$objGrids[1]->setdefaultvalue("u_starttime",currenttime());
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
	global $objGridDietary;
	global $objGrids;
	global $objGridPendingDocs;
	global $objGridPendingMedSups;
	
	$objRs = new recordset(null,$objConnection);
	
	$objRs->queryopen("select u_requestdate, u_meal, u_diettype, u_remarks from u_hisdiets where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='IP' and u_refno='".$page->getitemstring("docno")."' order by u_requestdate");
	$date = "";
	while ($objRs->queryfetchrow("NAME")) {
		if ($date!=$objRs->fields["u_requestdate"]) {
			$date=$objRs->fields["u_requestdate"];
			$objGridDietary->addrow();
			$objGridDietary->setitem(null,"date",formatDateToHttp($objRs->fields["u_requestdate"]));
		}
		if ($objRs->fields["u_meal"]=="Breakfast") $objGridDietary->setitem(null,"breakfast",$objRs->fields["u_diettype"] . iif($objRs->fields["u_remarks"]!="",", ".$objRs->fields["u_remarks"],""));
		if ($objRs->fields["u_meal"]=="Lunch") $objGridDietary->setitem(null,"lunch",$objRs->fields["u_diettype"] . iif($objRs->fields["u_remarks"]!="",", ".$objRs->fields["u_remarks"],""));
		if ($objRs->fields["u_meal"]=="Dinner") $objGridDietary->setitem(null,"dinner",$objRs->fields["u_diettype"] . iif($objRs->fields["u_remarks"]!="",", ".$objRs->fields["u_remarks"],""));
		$objGridDietary->setitem(null,"rowstat","E");
	}
	$objGrids[1]->setdefaultvalue("u_startdate",currentdate());
	$objGrids[1]->setdefaultvalue("u_starttime",currenttime());
	
	retrievePatientPendingItemsGPSHIS('IP',$page->getitemstring("docno"));
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


function onDrawGridColumnLabelGPSProjects($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T2":
			if ($column=="u_pricelist") {
				$label = slgetdisplaypricelists($label);
			}
			break;
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
		case "T9":
			if ($column=="u_billtermby") {
				$objRs->queryopen("select username from users where userid='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["username"];
			}	
			break;
		case "T10":
			if ($column=="u_doctorid") {
				$objRs->queryopen("select name from u_hisdoctors where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}	
			break;
		case "T13":
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
		case "T11":	
			$filerExp = " ORDER BY U_VSDATE DESC, U_VSTIME DESC";
			break;
			
	}
	return true;
}
$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_expiredate","Calendar");
$page->businessobject->items->setcfl("u_vsdate","Calendar");
$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");
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
$page->businessobject->items->seteditable("u_prevdiettype",false);
$page->businessobject->items->seteditable("u_prevdietremarks",false);

$page->businessobject->items->seteditable("u_billing",false);
$page->businessobject->items->seteditable("u_prepaid",false);

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

$page->businessobject->items->seteditable("u_doctorid",false);
$page->businessobject->items->seteditable("u_doctorservice",false);

$page->businessobject->items->seteditable("u_department",false);
$page->businessobject->items->seteditable("u_pricelist",false);

$page->businessobject->items->setrequired("docno",true);
$page->businessobject->items->setrequired("u_startdate",true);
$page->businessobject->items->setrequired("u_starttime",true);
$page->businessobject->items->setrequired("u_doctorid",true);
$page->businessobject->items->setrequired("u_doctorservice",true);
$page->businessobject->items->setrequired("u_admittedby",true);
$page->businessobject->items->setrequired("u_pricelist",true);
//$page->businessobject->items->setrequired("u_remarks",true);

$objGrids[0]->columncfl("u_icdcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_icddesc","OpenCFLfs()");
//$objGrids[0]->columncfl("u_doctorid","OpenCFLfs()");

$objGrids[1]->columncfl("u_roomno","OpenCFLfs()");
$objGrids[1]->columncfl("u_bedno","OpenCFLfs()");
$objGrids[1]->columnwidth("u_roomno",8);
$objGrids[1]->columnwidth("u_bedno",10);
$objGrids[1]->columnwidth("u_pricelist",20);
$objGrids[1]->columnwidth("u_rateuom",5);
$objGrids[1]->columnwidth("u_quantity",8);
$objGrids[1]->columnwidth("u_rate",10);
$objGrids[1]->columnwidth("u_amount",10);
$objGrids[1]->columnwidth("u_starttime",10);
$objGrids[1]->columnwidth("u_endtime",8);
$objGrids[1]->columnvisibility("u_isroomshared",false);
$objGrids[1]->columnvisibility("u_startdatetime",false);
$objGrids[1]->columnvisibility("u_enddatetime",false);
$objGrids[1]->columnvisibility("u_specialpriceref",false);
$objGrids[1]->columnattributes("u_pricelist","disabled");
//$objGrids[1]->columnattributes("u_rate","disabled");
$objGrids[1]->columnattributes("u_rateuom","disabled");
$objGrids[1]->columnattributes("u_quantity","disabled");
$objGrids[1]->columnattributes("u_amount","disabled");
$objGrids[1]->columnattributes("u_startdate","disabled");
$objGrids[1]->columnattributes("u_starttime","disabled");
$objGrids[1]->columnattributes("u_enddate","disabled");
$objGrids[1]->columnattributes("u_endtime","disabled");
$objGrids[1]->automanagecolumnwidth = false;

$objGrids[2]->columnlnkbtn("u_refno","OpenLnkBtnu_hislabtests()");
$objGrids[2]->dataentry = false;
$objGrids[2]->automanagecolumnwidth = false;

$objGrids[3]->columnlnkbtn("u_refno","OpenLnkBtnu_hisconsultancyfees()");
$objGrids[3]->columnwidth("u_refno",12);
$objGrids[3]->columnwidth("u_amount",15);
$objGrids[3]->dataentry = false;
$objGrids[3]->automanagecolumnwidth = false;

$objGrids[4]->columnlnkbtn("u_refno","OpenLnkBtnu_hismedsups()");
$objGrids[4]->dataentry = false;
$objGrids[4]->automanagecolumnwidth = false;

$objGrids[5]->columnlnkbtn("u_refno","OpenLnkBtnu_hismiscs()");
$objGrids[5]->dataentry = false;
$objGrids[5]->automanagecolumnwidth = false;

$objGrids[6]->columnlnkbtn("u_refno","OpenLnkBtnu_hissplrooms()");
$objGrids[6]->columnwidth("u_refno",12);
$objGrids[6]->columnwidth("u_roomno",8);
$objGrids[6]->columnwidth("u_bedno",10);
$objGrids[6]->columnwidth("u_rate",8);
$objGrids[6]->columnwidth("u_uom",5);
$objGrids[6]->columnwidth("u_quantity",8);
$objGrids[6]->columnwidth("u_starttime",10);
$objGrids[6]->columnwidth("u_endtime",8);
$objGrids[6]->columnvisibility("u_startdatetime",false);
$objGrids[6]->columnvisibility("u_enddatetime",false);
$objGrids[6]->columnattributes("u_enddate","disabled");
$objGrids[6]->columnattributes("u_endtime","disabled");
$objGrids[6]->dataentry = false;
$objGrids[6]->automanagecolumnwidth = false;

$objGrids[7]->columnvisibility("u_hmo",false);
$objGrids[7]->columnwidth("u_memberid",15);
$objGrids[7]->columnwidth("u_membertype",20);
$objGrids[7]->columnwidth("u_membername",40);
$objGrids[7]->columncfl("u_memberid","OpenCFLfs()");
$objGrids[7]->columndataentry("u_inscode","options",array("loadu_hishealthins2","",":"));
$objGrids[7]->columnattributes("u_memberid","disabled");
$objGrids[7]->columnattributes("u_membertype","disabled");
$objGrids[7]->columnattributes("u_membername","disabled");
$objGrids[7]->automanagecolumnwidth = false;

$objGrids[8]->columnwidth("u_paymentterm",20);
$objGrids[8]->columnwidth("u_disccode",20);
$objGrids[8]->columnwidth("u_remarks",40);
$objGrids[8]->columnwidth("u_prepaid",5);
$objGrids[8]->columnwidth("u_billing",6);
$objGrids[8]->columntitle("u_prepaid","Term");
$objGrids[8]->columnvisibility("u_prepaidlab",false);
$objGrids[8]->columnvisibility("u_prepaidmedsup",false);
$objGrids[8]->columnvisibility("u_prepaidmisc",false);
$objGrids[8]->columnvisibility("u_prepaidsplroom",false);
$objGrids[8]->columnvisibility("u_prepaidpf",false);
$objGrids[8]->dataentry = false;
$objGrids[8]->automanagecolumnwidth = false;

$objGrids[9]->columnwidth("u_default",3);
$objGrids[9]->columnwidth("u_doctorid",50);
$objGrids[9]->columnwidth("u_service",30);
$objGrids[9]->columndataentry("u_default","type","checkbox");
$objGrids[9]->columndataentry("u_default","value",1);
$objGrids[9]->columndataentry("u_rod","type","checkbox");
$objGrids[9]->columndataentry("u_rod","value",1);

$objGrids[10]->columnwidth("u_vsdate",10);
$objGrids[10]->columnwidth("u_bt_c",8);
$objGrids[10]->columnwidth("u_bt_f",8);
$objGrids[10]->columnwidth("u_bp_s",8);
$objGrids[10]->columnwidth("u_bp_d",8);
$objGrids[10]->columnwidth("u_rr",8);
$objGrids[10]->columnwidth("u_hr",8);
$objGrids[10]->columnwidth("u_o2sat",8);
$objGrids[10]->dataentry = false;
$objGrids[10]->automanagecolumnwidth = false;
$objGrids[10]->width=720;

$objGrids[11]->columncfl("u_name","OpenCFLfs()");

$objGrids[12]->columnwidth("u_docdate",9);
$objGrids[12]->columnwidth("u_doctime",6);
$objGrids[12]->columnwidth("u_docno",10);
$objGrids[12]->columnwidth("u_refno",10);
$objGrids[12]->columnwidth("u_docdesc",17);
$objGrids[12]->columnwidth("u_amount",10);
$objGrids[12]->columnwidth("u_runbal",10);
$objGrids[12]->columnwidth("u_docstatus",10);
$objGrids[12]->columntitle("u_runbal","Running Bal");
$objGrids[12]->columnlnkbtn("u_docno","OpenLnkBtnTrxNo()");
$objGrids[12]->dataentry = false;
$objGrids[12]->automanagecolumnwidth = false;

$objGrids[13]->columnwidth("u_name",30);
$objGrids[13]->columnwidth("u_frequency",25);
$objGrids[13]->columnwidth("u_quantity",25);

$objGrids[14]->columncfl("u_date","Calendar");
$objGrids[14]->columnwidth("u_date",12);
$objGrids[14]->columnwidth("u_action",100);


$objGridDietary = new grid("T111");
$objGridDietary->addcolumn("date");
$objGridDietary->addcolumn("breakfast");
$objGridDietary->addcolumn("lunch");
$objGridDietary->addcolumn("dinner");
$objGridDietary->columntitle("date","Date");
$objGridDietary->columntitle("breakfast","Breakfast");
$objGridDietary->columntitle("lunch","Lunch Date");
$objGridDietary->columntitle("dinner","Dinner");
$objGridDietary->columnwidth("code",15);
$objGridDietary->columnwidth("breakfast",15);
$objGridDietary->columnwidth("lunch",15);
$objGridDietary->columnwidth("dinner",15);
$objGridDietary->dataentry = false;

include_once("../Addons/GPS/HIS Add-On/UserProcs/utils/u_hispatientpendingitems.php");

$addoptions = false;
$deleteoption = false;
?> 

