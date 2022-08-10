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

$page->businessobject->events->add->fetchDBRow("onFetchDBRowGPSHIS");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$u_trxtype="";
$partialserve = false;
function onFetchDBRowGPSHIS($objrs) {
	global $canceloption;
	global $closeoption;
	global $partialserve;
	global $page;
	switch ($objrs->dbtable) {
		case "u_hisrequestitems":
			if ($objrs->fields["U_CHRGQTY"]>0 || $objrs->fields["U_RTQTY"]>0) {
				 $partialserve = true;
			}	
			break;
	}
	
}


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
	$page->setitem("u_duedate",currentdate());
	$page->setitem("u_duetime",currenttime());
	$page->setitem("u_requestby",$_SESSION["userid"]);
	$page->setitem("u_trxtype",$u_trxtype);
	$page->setitem("u_reftype","OP");
	
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
	
	if ($page->getitemstring("u_requestdepartment")!="") {
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select u_allowintsecreq from u_hissections where code='".$page->getitemstring("u_requestdepartment")."'");
		if ($objRs->queryfetchrow("NAME")) {
			$page->setitem("u_allowintsecreq",$objRs->fields["u_allowintsecreq"]);
			if ($objRs->fields["u_allowintsecreq"]=="0") {
				$page->setitem("u_department",$page->getitemstring("u_requestdepartment"));
				$page->businessobject->items->seteditable("u_department",false);
				$page->setitem("u_prepaid",1);
			}
		}
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
		case "T4":
			if ($column=="u_department") {
				$objRs->queryopen("select name from u_hissections where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}
			break;
		case "T5":
			if ($column=="u_doctorid") {
				$objRs->queryopen("select name from u_hisdoctors where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}
			break;
	}
}

$page->businessobject->items->setcfl("u_requestdate","Calendar");
$page->businessobject->items->setcfl("u_duedate","Calendar");
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
$page->businessobject->items->seteditable("u_requestby",false);

$page->businessobject->items->setvisible("u_cancelledby",true);
$page->businessobject->items->setvisible("u_cancelledremarks",true);
$page->businessobject->items->setvisible("u_isstat",false);

$objrs = new recordset(null,$objConnection);
$userdepartment = "";
$userreftype = "";
/*
$objrs->queryopen("select A.U_SECTION,B.U_TYPE FROM EMPLOYEES A, U_HISSECTIONS B WHERE B.CODE=A.U_SECTION AND A.USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select A.ROLEID AS U_SECTION,B.U_TYPE FROM USERS A, U_HISSECTIONS B WHERE B.CODE=A.ROLEID AND A.USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	$userdepartment = $objrs->fields["U_SECTION"];

	/*$userreftype = $objrs->fields["U_TYPE"];
	switch ($userreftype) {
		case "ER": 
		case "DIALYSIS": 
		case "ADMITTING": 
			$userreftype = ""; break;
	}*/		

	if ($userdepartment!="") $page->businessobject->items->seteditable("u_requestdepartment",false);
	if ($userreftype!="") $page->businessobject->items->seteditable("u_reftype",false);
}


$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columncfl("u_doctorname","OpenCFLfs()");
$objGrids[0]->columnwidth("u_itemgroup",15);
$objGrids[0]->columnwidth("u_itemcode",15);
$objGrids[0]->columnwidth("u_itemdesc",40);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columnwidth("u_statperc",5);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_vatamount",8);
$objGrids[0]->columnwidth("u_statunitprice",17);
$objGrids[0]->columnwidth("u_quantity",7);
$objGrids[0]->columnwidth("u_isfoc",3);
$objGrids[0]->columnwidth("u_price",14);
$objGrids[0]->columnwidth("u_ispackage",7);
$objGrids[0]->columnwidth("u_isautodisc",8);
$objGrids[0]->columnwidth("u_iscashdisc",8);
$objGrids[0]->columnwidth("u_isbilldisc",8);
$objGrids[0]->columnwidth("u_doctorname",30);
$objGrids[0]->columnwidth("u_remarks",30);
$objGrids[0]->columntitle("u_ispackage","Package");
$objGrids[0]->columntitle("u_quantity","Qty");
$objGrids[0]->columntitle("u_isfoc","FOC");
$objGrids[0]->columntitle("u_price","Price After Disc");
$objGrids[0]->columnattributes("u_uom","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->columnattributes("u_ispackage","disabled");
$objGrids[0]->columnattributes("u_iscashdisc","disabled");
$objGrids[0]->columnattributes("u_isbilldisc","disabled");
$objGrids[0]->columnattributes("u_chrgqty","disabled");
$objGrids[0]->columnattributes("u_rtqty","disabled");
$objGrids[0]->columnalignment("u_isautodisc","center");
$objGrids[0]->columnalignment("u_isfoc","center");
$objGrids[0]->columnalignment("u_selected","center");
$objGrids[0]->columnwidth("u_selected",4);
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columninput("u_isstat","type","checkbox");
$objGrids[0]->columninput("u_isstat","value",1);
$objGrids[0]->columninput("u_quantity","type","text");
$objGrids[0]->columninput("u_remarks","type","text");
//$objGrids[0]->columnautocomplete("u_itemdesc","OpenAutoCompleteu_hisitemsbyname()");
$objGrids[0]->columndataentry("u_selected","type","checkbox");
$objGrids[0]->columndataentry("u_selected","value",1);
$objGrids[0]->columndataentry("u_isautodisc","type","checkbox");
$objGrids[0]->columndataentry("u_isautodisc","value",1);
$objGrids[0]->columndataentry("u_isstat","type","checkbox");
$objGrids[0]->columndataentry("u_isstat","value",1);
$objGrids[0]->columndataentry("u_isfoc","type","checkbox");
$objGrids[0]->columndataentry("u_isfoc","value",1);
$objGrids[0]->columnvisibility("u_itemcode",false);
$objGrids[0]->columnvisibility("u_isstat",false);
$objGrids[0]->columnvisibility("u_statperc",false);
$objGrids[0]->columnvisibility("u_chrgqty",false);
$objGrids[0]->columnvisibility("u_rtqty",false);
$objGrids[0]->automanagecolumnwidth = false;

$objGrids[1]->columnwidth("u_packagecode",15);
$objGrids[1]->columnwidth("u_itemcode",15);
$objGrids[1]->columnwidth("u_itemdesc",30);
$objGrids[1]->columnwidth("u_uom",5);
$objGrids[1]->columnwidth("u_qtyperpack",11);
$objGrids[1]->columnwidth("u_quantity",7);
$objGrids[1]->columntitle("u_packageqty","No of Package");
$objGrids[1]->columnattributes("u_packagecode","disabled");
$objGrids[1]->columnattributes("u_packageqty","disabled");
$objGrids[1]->columnattributes("u_itemcode","disabled");
$objGrids[1]->columnattributes("u_itemdesc","disabled");
$objGrids[1]->columnattributes("u_quantity","disabled");
$objGrids[1]->columnattributes("u_uom","disabled");
$objGrids[1]->columnattributes("u_qtyperpack","disabled");
$objGrids[1]->columnattributes("u_chrgqty","disabled");
$objGrids[1]->columnattributes("u_rtqty","disabled");
$objGrids[1]->columnvisibility("u_department",false);
$objGrids[1]->dataentry = false;
$objGrids[1]->automanagecolumnwidth = false;

$objGrids[2]->dataentry = false;
$objGrids[2]->columnlnkbtn("u_reqno","OpenLnkBtnRequestNo()");


$addoptions = false;
$deleteoption = false;
$canceloption = true;
$closeoption = false;

//$page->bodyclass = "yui-skin-sam";
?> 

