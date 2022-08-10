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
	global $userreftype;
	global $u_trxtype;
	$u_hissetupdata = getu_hissetup("U_DFLTPHARMADEPT, U_DFLTLABDEPT, U_DFLTRADIODEPT, U_DFLTCSRDEPT, U_DFLTWAREHOUSEDEPT, U_DFLTDIETARYDEPT, U_DFLTHEARTSTATIONDEPT, U_DFLTPHARMATFS,U_DFLTIPD,U_DFLTOPD,U_STOCKLINK");
	
	$page->setitem("u_tfdate",currentdate());
	$page->setitem("u_tftime",currenttime());
	$page->setitem("u_trxtype",$u_trxtype);
	if ($userdepartment!="") $page->setitem("u_fromdepartment",$userdepartment);
	elseif ($page->getitemstring("u_trxtype")=="PHARMACY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTPHARMADEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="CSR") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTCSRDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="LABORATORY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTLABDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="RADIOLOGY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTRADIODEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTHEARTSTATIONDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="DIETARY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTDIETARYDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTIPD"]);
	elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTOPD"]);
	elseif ($page->getitemstring("u_trxtype")=="WAREHOUSE") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTWAREHOUSEDEPT"]);

	
	if ($page->getitemstring("u_trxtype")=="PHARMACY" || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="CSR") {
		//$page->setitem("u_todepartment",$u_hissetupdata["U_DFLTIPD"]);
	} else {
		//$page->setitem("u_todepartment",$u_hissetupdata["U_DFLTPHARMADEPT"]);
	}	
	
	$page->setitem("u_intransit",$u_hissetupdata["U_DFLTPHARMATFS"]);
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

$page->objectdoctype = "STOCKTRANSFER";

$page->businessobject->items->setcfl("u_tfdate","Calendar");
$page->businessobject->items->setcfl("u_requestno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columntitle("u_qtypu","Qty (PU)");
$objGrids[0]->columnwidth("u_itemcode",15);
$objGrids[0]->columnwidth("u_itemdesc",50);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_numperuompu",8);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columnwidth("u_uompu",8);
$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
//$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
//$objGrids[0]->columncfl("u_itemdesc","OpenCFLitems()");
$objGrids[0]->columninput("u_quantity","type","text");
$objGrids[0]->columninput("u_qtypu","type","text");
$objGrids[0]->columnattributes("u_uom","disabled");
$objGrids[0]->columnattributes("u_instock","disabled");
$objGrids[0]->columnattributes("u_reqqty","disabled");
//$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->automanagecolumnwidth = false;

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
	if ($userdepartment!="") $page->businessobject->items->seteditable("u_fromdepartment",false);
	if ($userreftype!="") $page->businessobject->items->seteditable("u_reftype",false);
}

$page->toolbar->setaction("navigation",false);

$addoptions = true;

$canceloption = false;
?> 

