<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hispaymentterms.php");
include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hislabtesttypes.php");

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
	global $userreftype;
	global $u_trxtype;
	$u_hissetupdata = getu_hissetup("U_DFLTPHARMADEPT,U_DFLTLABDEPT,U_DFLTPHARMATFS,U_DFLTIPD,U_DFLTOPD,U_DFLTRADIODEPT,U_DFLTHEARTSTATIONDEPT,U_STOCKLINK, U_PREPAIDIPLAB, U_PREPAIDOPLAB,U_OPDPRICELIST,U_DFLTOPPAYTERM");
	
	$page->setitem("u_requestdate",currentdate());
	$page->setitem("u_requesttime",currenttime());
	$page->setitem("u_trxtype",$u_trxtype);
	
	if ($userdepartment!="") $page->setitem("u_requestdepartment",$userdepartment);
	elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_requestdepartment",$u_hissetupdata["U_DFLTIPD"]);
	elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_requestdepartment",$u_hissetupdata["U_DFLTOPD"]);
	elseif ($page->getitemstring("u_trxtype")=="LABORATORY") $page->setitem("u_requestdepartment",$u_hissetupdata["U_DFLTLABDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="RADIOLOGY") $page->setitem("u_requestdepartment",$u_hissetupdata["U_DFLTRADIODEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") $page->setitem("u_requestdepartment",$u_hissetupdata["U_DFLTHEARTSTATIONDEPT"]);

	if ($userreftype!="") $page->setitem("u_reftype",$userreftype);
	elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_reftype","OP");
	elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_reftype","IP");
	
	$page->privatedata["prepaidip"] = $u_hissetupdata["U_PREPAIDIPLAB"];
	$page->privatedata["prepaidop"] = $u_hissetupdata["U_PREPAIDOPLAB"];

	if ($page->getitemstring("u_trxtype")=="IP") {
		$page->setitem("u_prepaid",$page->privatedata["prepaidip"]);
	} else $page->setitem("u_prepaid",$page->privatedata["prepaidop"]);
	
	if ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION" ) {
		$page->setitem("u_department",$page->getitemstring("u_requestdepartment"));
		$page->setitem("u_prepaid",1);
	}
	
	$page->privatedata["dfltpricelist"] = $u_hissetupdata["U_OPDPRICELIST"];
	$page->privatedata["dfltpaymentterm"] = $u_hissetupdata["U_DFLTOPPAYTERM"];
	if ($u_hissetupdata["U_DFLTOPPAYTERM"]!="") {
		$u_hispaymenttermdata = getu_hispaymentterms($u_hissetupdata["U_DFLTOPPAYTERM"],"U_PREPAIDLAB");
		$page->privatedata["dfltprepaid"] = $u_hispaymenttermdata["U_PREPAIDLAB"];
	} else $page->privatedata["dfltprepaid"] = 1;
	
	$page->privatedata["dfltdisccode"] = $u_hissetupdata["U_DFLTOPDISCCODE"];
	
	
	$page->setitem("u_requestby",$_SESSION["userid"]);
	
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
		case "T1":
			if ($column=="u_type") {
				$objRs->queryopen("select name from u_hisitems where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_rendered") {
				if ($label=="1") $label="Yes";
				else $label="No";
			}
			break;
		case "T3":
			if ($column=="u_ispackage") {
				if ($label=="1") $label="Yes";
				else $label="No";
			}
			break;
	}
}

$page->businessobject->items->setcfl("u_requestdate","Calendar");
$page->businessobject->items->setcfl("u_reqdate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");
$page->businessobject->items->setcfl("u_birthdate","Calendar");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_gender",false);
$page->businessobject->items->seteditable("u_birthdate",false);
$page->businessobject->items->seteditable("u_age",false);
$page->businessobject->items->seteditable("u_paymentterm",false);
$page->businessobject->items->seteditable("u_prepaid",false);
$page->businessobject->items->seteditable("u_payrefno",false);
$page->businessobject->items->seteditable("u_amount",false);

$objrs = new recordset(null,$objConnection);
$userdepartment = "";
$userreftype = "";
/*
$objrs->queryopen("select A.U_SECTION,B.U_TYPE FROM EMPLOYEES A, U_HISSECTIONS B WHERE B.CODE=A.U_SECTION AND A.USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select A.ROLEID AS U_SECTION,B.U_TYPE FROM USERS A, U_HISSECTIONS B WHERE B.CODE=A.ROLEID AND A.USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	$userdepartment = $objrs->fields["U_SECTION"];
	$userreftype = $objrs->fields["U_TYPE"];
	switch ($userreftype) {
		case "ER": 
		case "DIALYSIS": 
		case "ADMITTING": 
			$userreftype = ""; break;
	}		

	if ($userdepartment!="") $page->businessobject->items->seteditable("u_requestdepartment",false);
	if ($userreftype!="") $page->businessobject->items->seteditable("u_reftype",false);
}

$objGrids[0]->columnwidth("u_statperc",6);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_vatamount",8);
$objGrids[0]->columnwidth("u_statunitprice",17);
$objGrids[0]->columnwidth("u_rendered",8);
$objGrids[0]->columnattributes("u_rendered","disabled");

//$objGrids[0]->columnvisibility("u_prediscprice",true);
//$objGrids[0]->columnvisibility("u_predisclinetotal",true);

$objGrids[1]->columnwidth("u_itemcode",15);
$objGrids[1]->columnwidth("u_itemdesc",30);
$objGrids[1]->columnwidth("u_quantity",8);
$objGrids[1]->columnwidth("u_uom",5);
$objGrids[1]->columnwidth("u_vatamount",8);
$objGrids[1]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[1]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[1]->columnlnkbtn("u_itemcode","OpenLnkBtnu_hisitems()");
$objGrids[1]->columnattributes("u_uom","disabled");
$objGrids[1]->columnattributes("u_linetotal","disabled");

$objGrids[2]->columnwidth("u_itemcode",15);
$objGrids[2]->columnwidth("u_itemdesc",30);
$objGrids[2]->columnwidth("u_quantity",8);
$objGrids[2]->columnwidth("u_uom",5);
$objGrids[2]->columnwidth("u_vatamount",8);
$objGrids[2]->columnwidth("u_ispackage",7);
$objGrids[2]->columntitle("u_ispackage","Package");
$objGrids[2]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[2]->columncfl("u_itemdesc","OpenCFLfs()");
//$objGrids[2]->columncfl("u_itemcode","OpenCFLitems()");
//$objGrids[2]->columncfl("u_itemdesc","OpenCFLitems()");
$objGrids[2]->columnattributes("u_uom","disabled");
//$objGrids[2]->columnattributes("u_itemdesc","disabled");
$objGrids[2]->columnattributes("u_linetotal","disabled");
$objGrids[2]->columnattributes("u_ispackage","disabled");

$objGrids[3]->columnwidth("u_packagecode",15);
$objGrids[3]->columnwidth("u_itemcode",15);
$objGrids[3]->columnwidth("u_itemdesc",30);
$objGrids[3]->columnwidth("u_uom",5);
$objGrids[3]->columnwidth("u_qtyperpack",11);
$objGrids[3]->columnwidth("u_quantity",7);
$objGrids[3]->columntitle("u_packageqty","No of Package");
$objGrids[3]->columnattributes("u_packagecode","disabled");
$objGrids[3]->columnattributes("u_packageqty","disabled");
$objGrids[3]->columnattributes("u_itemcode","disabled");
$objGrids[3]->columnattributes("u_itemdesc","disabled");
$objGrids[3]->columnattributes("u_quantity","disabled");
$objGrids[3]->columnattributes("u_uom","disabled");
$objGrids[3]->columnattributes("u_qtyperpack","disabled");

$addoptions = false;
$deleteoption = false;

//$page->bodyclass = "yui-skin-sam";
?> 

