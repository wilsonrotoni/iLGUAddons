<?php
 

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

$postdata = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $postdata;
	global $userdepartment;
	if ($page->getitemstring("u_meal")!="") $postdata["u_meal"] = $page->getitemstring("u_meal");
	if ($page->getitemstring("u_department")!="" ) $postdata["u_department"] = $page->getitemstring("u_department");
	else $page->setitem("u_department",$userdepartment);
	
	if ($page->getitemstring("u_department")!="" && $page->getitemstring("u_meal")!="") {
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select docno from u_hisdietaries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_date='".currentdateDB()."' and u_department='".$page->getitemstring("u_department")."' and u_meal='".$page->getitemstring("u_meal")."'");
		if ($objRs->queryfetchrow()) {
			$page->setkey($objRs->fields[0]);
			modeEdit();
			return false;
		}
	}
	
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	global $postdata;
	global $userdepartment;
	global $objGrids;
	$page->setitem("u_date",currentdate());
	$page->setitem("u_time",currenttime());
	if (isset($postdata["u_meal"])) $page->setitem("u_meal",$postdata["u_meal"]);
	if (isset($postdata["u_department"])) $page->setitem("u_department",$postdata["u_department"]);
	else $page->setitem("u_department",$userdepartment);
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select a.U_BEDNO, a.DOCNO, a.U_PATIENTID, a.U_PATIENTNAME, a.U_AGE_Y, a.U_RELIGION, a.U_BMISTATUS, a.U_DIETTYPE, a.U_DIETTYPEDESC, a.U_DIETREMARKS, a.U_DOCTORID, b.NAME AS U_DOCTORNAME from u_hisips a LEFT JOIN u_hisdoctors b on b.code=a.u_doctorid where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_department='".$page->getitemstring("u_department")."' and docstatus='Active' and u_expired=0");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_bedno",$objRs->fields["U_BEDNO"]);
		$objGrids[0]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
		$objGrids[0]->setitem(null,"u_patientid",$objRs->fields["U_PATIENTID"]);
		$objGrids[0]->setitem(null,"u_patientname",$objRs->fields["U_PATIENTNAME"]);
		$objGrids[0]->setitem(null,"u_age",$objRs->fields["U_AGE_Y"]);
		$objGrids[0]->setitem(null,"u_religion",$objRs->fields["U_RELIGION"]);
		$objGrids[0]->setitem(null,"u_bmistatus",$objRs->fields["U_BMISTATUS"]);
		$objGrids[0]->setitem(null,"u_diettype",$objRs->fields["U_DIETTYPE"]);
		$objGrids[0]->setitem(null,"u_diettypedesc",$objRs->fields["U_DIETTYPEDESC"]);
		$objGrids[0]->setitem(null,"u_dietremarks",$objRs->fields["U_DIETREMARKS"]);
		$objGrids[0]->setitem(null,"u_doctorid",$objRs->fields["U_DOCTORID"]);
		$objGrids[0]->setitem(null,"u_doctorname",$objRs->fields["U_DOCTORNAME"]);
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

$page->businessobject->items->seteditable("u_editby",false);
$page->businessobject->items->seteditable("u_date",false);
$page->businessobject->items->seteditable("u_time",false);

$objGrids[0]->columncfl("u_diettypedesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_bedno",7);
$objGrids[0]->columnwidth("u_patientname",25);
$objGrids[0]->columnwidth("u_diettypedesc",30);
$objGrids[0]->columnwidth("u_remarks",20);
$objGrids[0]->columnwidth("u_bmistatus",10);
$objGrids[0]->columnwidth("u_age",3);
$objGrids[0]->columnwidth("u_religion",10);
//$objGrids[0]->columninput("u_diettype","type","select");
//$objGrids[0]->columninput("u_diettype","options",array("loadudflinktable","u_hisdiettypes:code:name",""));
$objGrids[0]->columninput("u_diettypedesc","type","text");
$objGrids[0]->columninput("u_remarks","type","text");
$objGrids[0]->columnvisibility("u_religion",false);
$objGrids[0]->columnvisibility("u_refno",false);
$objGrids[0]->columnvisibility("u_patientid",false);
$objGrids[0]->columnvisibility("u_doctorid",false);
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->dataentry = false;

$objrs = new recordset(null,$objConnection);
$userdepartment = "";
/*
$objrs->queryopen("select DEPARTMENT FROM EMPLOYEES WHERE USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select A.ROLEID AS DEPARTMENT,B.U_TYPE AS TYPE FROM USERS A LEFT JOIN U_HISSECTIONS B ON B.CODE=A.ROLEID WHERE A.USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	if ($objrs->fields["DEPARTMENT"]!="" && $objrs->fields["TYPE"]=="NURSE") {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		$page->businessobject->items->seteditable("u_department",false);
	}	
}

$addoptions = false;
$deleteoption = false;
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("new",false);

?> 

