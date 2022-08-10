<?php

include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hispaymentterms.php");
 

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
	global $userreftype;
	global $u_trxtype;
	$u_hissetupdata = getu_hissetup("U_DFLTIPD,U_DFLTOPD,U_DFLTPHARMADEPT,U_DFLTLABDEPT,U_DFLTCSRDEPT, U_PREPAIDIPMEDSUP, U_PREPAIDOPMEDSUP, U_OPDPRICELIST,U_DFLTOPDISCCODE,U_DFLTOPPAYTERM,U_DFLTOPBILLING,U_STOCKLINK");
	
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
	$page->setitem("u_trxtype",$u_trxtype);
	if ($userdepartment!="") $page->setitem("u_fromdepartment",$userdepartment);
	elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTIPD"]);
	elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTOPD"]);
	elseif ($page->getitemstring("u_trxtype")=="PHARMACY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTPHARMADEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="LABORATORY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTLABDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="CSR") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTCSRDEPT"]);
	
	if ($userreftype!="") $page->setitem("u_reftype",$userreftype);
	elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_reftype","IP");
	elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_reftype","OP");

	$page->privatedata["prepaidip"] = $u_hissetupdata["U_PREPAIDIPMEDSUP"];
	$page->privatedata["prepaidop"] = $u_hissetupdata["U_PREPAIDOPMEDSUP"];

	if ($page->getitemstring("u_trxtype")=="IP") {
		$page->setitem("u_prepaid",$page->privatedata["prepaidip"]);
	} elseif ($page->getitemstring("u_trxtype")=="OP") { 
		$page->setitem("u_prepaid",$page->privatedata["prepaidop"]);
	}

	if ($page->getitemstring("u_trxtype")=="PHARMACY" || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="CSR" || $page->getitemstring("u_trxtype")=="HEARTSTATION" || $page->getitemstring("u_trxtype")=="RADIOLOGY") {
		$page->setitem("u_todepartment",$page->getitemstring("u_fromdepartment"));
		$page->setitem("u_prepaid",1);
	}
	
	$page->privatedata["dfltpricelist"] = $u_hissetupdata["U_OPDPRICELIST"];
	$page->privatedata["dfltpaymentterm"] = $u_hissetupdata["U_DFLTOPPAYTERM"];
	if ($u_hissetupdata["U_DFLTOPPAYTERM"]!="") {
		$u_hispaymenttermdata = getu_hispaymentterms($u_hissetupdata["U_DFLTOPPAYTERM"],"U_PREPAIDMEDSUP");
		$page->privatedata["dfltprepaid"] = $u_hispaymenttermdata["U_PREPAIDMEDSUP"];
	} else $page->privatedata["dfltprepaid"] = 1;
	
	$page->privatedata["dfltdisccode"] = $u_hissetupdata["U_DFLTOPDISCCODE"];
	$page->setitem("u_stocklink",$u_hissetupdata["U_STOCKLINK"]);
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
	global $page;
	global $objGrids;
	/*
  	$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/HIS/Laboratory Tests/".$page->getitemstring("docno")."/Attachments/";
	if (is_dir($dirname)) {
		$dir_handle = opendir($dirname);
		if ($dir_handle) {
			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (is_file($dirname.$file)) {
						$path_parts = pathinfo($dirname.$file);
						if (strtolower($path_parts["extension"])=="gif" || strtolower($path_parts["extension"])=="jpg" || strtolower($path_parts["extension"])=="png") {			
							$objGrids[1]->addrow();
							$objGrids[1]->setitem(null,"u_picturename",substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[1]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[1]->setitem(null,"rowstat","E");
						}
					}
			  }
			}
			closedir($dir_handle);
		}	
	}
	*/		   	
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
$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_amount",false);
$page->businessobject->items->seteditable("u_payrefno",false);
$page->businessobject->items->seteditable("u_paymentterm",false);
$page->businessobject->items->seteditable("u_prepaid",false);

$objGrids[0]->columnwidth("u_itemcode",15);
$objGrids[0]->columnwidth("u_itemdesc",30);
$objGrids[0]->columnwidth("u_whscode",15);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnlnkbtn("u_itemcode","OpenLnkBtnu_hisitems()");
//$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
//$objGrids[0]->columncfl("u_itemdesc","OpenCFLitems()");
$objGrids[0]->columnattributes("u_uom","disabled");
//$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->columnvisibility("u_whscode",false);

//$objGrids[0]->columnvisibility("u_discperc",true);
//$objGrids[0]->columnvisibility("u_prediscprice",true);
//$objGrids[0]->columnvisibility("u_predisclinetotal",true);

/*
$objGrids[1]->width = 300;
$objGrids[1]->columnwidth("u_picturename",30);
$objGrids[1]->columnwidth("u_filepath",10);
$objGrids[1]->columnvisibility("u_filepath",false);
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->setaction("reset",false);
*/

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
	if ($userdepartment!="") $page->businessobject->items->seteditable("u_fromdepartment",false);
	//if ($userreftype!="") $page->businessobject->items->seteditable("u_reftype",false);
}


$addoptions = false;
$deleteoption = false;
$canceloption = true;
?> 

