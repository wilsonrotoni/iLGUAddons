<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
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

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $userdepartment;
	global $userreftype;
	$u_hissetupdata = getu_hissetup("U_PREPAIDIPSPLROOM, U_PREPAIDOPSPLROOM");
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
	$page->setitem("u_department",$userdepartment);
	$page->setitem("u_reftype",$userreftype);

	$page->privatedata["prepaidip"] = $u_hissetupdata["U_PREPAIDIPSPLROOM"];
	$page->privatedata["prepaidop"] = $u_hissetupdata["U_PREPAIDOPSPLROOM"];

	if ($page->getitemstring("u_trxtype")=="IP") {
		$page->setitem("u_prepaid",$page->privatedata["prepaidip"]);
	} else $page->setitem("u_prepaid",$page->privatedata["prepaidop"]);

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

$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_reqdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_roomno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bedno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_requestno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_enddate",false);
$page->businessobject->items->seteditable("u_endtime",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_roomdesc",false);
$page->businessobject->items->seteditable("u_rateuom",false);
$page->businessobject->items->seteditable("u_uom",false);
$page->businessobject->items->seteditable("u_amount",false);
$page->businessobject->items->seteditable("u_prepaid",false);
$page->businessobject->items->seteditable("u_payrefno",false);
$page->businessobject->items->seteditable("u_paymentterm",false);

$page->businessobject->items->setvisible("u_enddate",false);
$page->businessobject->items->setvisible("u_endtime",false);
$page->businessobject->items->setvisible("u_quantity",false);
$page->businessobject->items->setvisible("u_rate",false);
$page->businessobject->items->setvisible("u_rateuom",false);
$page->businessobject->items->setvisible("u_uom",false);

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
	if ($userdepartment!="") $page->businessobject->items->seteditable("u_department",false);
	if ($userreftype!="") $page->businessobject->items->seteditable("u_reftype",false);
}

$addoptions = false;

?> 

