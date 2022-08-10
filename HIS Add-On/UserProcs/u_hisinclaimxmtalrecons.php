<?php

include_once("../Addons/GPS/HIS Add-On/Userprocs/sls/u_hishealthins.php"); 

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

$data = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $data;
	$data["u_docdate"] = $page->getitemstring("u_docdate");
	if ($data["u_docdate"]=="") $data["u_docdate"] = currentdate();
	$data["u_xmtalno"] = $page->getitemstring("u_xmtalno");
	$data["u_refno"] = $page->getitemstring("u_refno");
	$data["u_preparedby"] = $page->getitemstring("u_preparedby");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $objGrids;
	$page->setitem("u_docdate",$data["u_docdate"]);
	$page->setitem("u_xmtalno",$data["u_xmtalno"]);
	$page->setitem("u_refno",$data["u_refno"]);
	$page->setitem("u_preparedby",$data["u_preparedby"]);
	
	$objRs = new recordset(null,$objConnection);
	
	$objGrids[0]->clear();
	$hf = 0;
	$pf = 0;
	$total = 0;
	
	$objRs->setdebug();
	
	if ($page->getitemstring("u_xmtalno")!="") {
		$objRs->queryopen("select a.u_inscode as u_inscode_1, a.u_insname as u_insname_1, a.u_membergroup as u_membergroup_1, a.u_reftype as u_reftype_1, a.u_package as u_package_1, a.u_startdate as u_startdate_1, a.u_enddate as u_enddate_1, b.u_refno, b.u_refdate, b.u_reftype, b.u_inscode, b.u_patientid, b.u_patientname, b.u_gender, b.u_age, b.u_memberid, b.u_membername, b.u_membertype, b.u_startdate, b.u_enddate, b.u_icdcode, b.u_remarks, b.u_room, b.u_med, b.u_lab, b.u_or, b.u_pf, b.u_total from u_hisinclaimxmtals a inner join u_hisinclaimxmtalitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_status='' where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$page->getitemstring("u_xmtalno")."' and a.docstatus not in ('CN')");
	}	
	//var_dump($objRs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_inscode",$objRs->fields["u_inscode_1"]);
		$page->setitem("u_insname",$objRs->fields["u_insname_1"]);
		$page->setitem("u_membergroup",$objRs->fields["u_membergroup_1"]);
		$page->setitem("u_reftype",$objRs->fields["u_reftype_1"]);
		$page->setitem("u_package",$objRs->fields["u_package_1"]);
		$page->setitem("u_startdate",formatDateToHttp($objRs->fields["u_startdate_1"]));
		$page->setitem("u_enddate",formatDateToHttp($objRs->fields["u_enddate_1"]));
		
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_selected",0);
		$objGrids[0]->setitem(null,"u_reconremarks","");
		$objGrids[0]->setitem(null,"u_refno",$objRs->fields["u_refno"]);
		$objGrids[0]->setitem(null,"u_refdate",formatDateToHttp($objRs->fields["u_refdate"]));
		$objGrids[0]->setitem(null,"u_reftype",$objRs->fields["u_reftype"]);
		$objGrids[0]->setitem(null,"u_inscode",$objRs->fields["u_inscode"]);
		$objGrids[0]->setitem(null,"u_patientid",$objRs->fields["u_patientid"]);
		$objGrids[0]->setitem(null,"u_patientname",$objRs->fields["u_patientname"]);
		$objGrids[0]->setitem(null,"u_gender",$objRs->fields["u_gender"]);
		$objGrids[0]->setitem(null,"u_age",$objRs->fields["u_age"]);
		$objGrids[0]->setitem(null,"u_memberid",$objRs->fields["u_memberid"]);
		$objGrids[0]->setitem(null,"u_membername",$objRs->fields["u_membername"]);
		$objGrids[0]->setitem(null,"u_membertype",$objRs->fields["u_membertype"]);
		$objGrids[0]->setitem(null,"u_startdate",formatDateToHttp($objRs->fields["u_startdate"]));
		$objGrids[0]->setitem(null,"u_enddate",formatDateToHttp($objRs->fields["u_enddate"]));
		$objGrids[0]->setitem(null,"u_icdcode",$objRs->fields["u_icdcode"]);
		$objGrids[0]->setitem(null,"u_remarks",$objRs->fields["u_remarks"]);
		if ($page->getitemstring("u_package")=="0") {
			$objGrids[0]->setitem(null,"u_room",formatNumericAmount($objRs->fields["u_room"]));
			$objGrids[0]->setitem(null,"u_med",formatNumericAmount($objRs->fields["u_med"]));
			$objGrids[0]->setitem(null,"u_lab",formatNumericAmount($objRs->fields["u_lab"]));
			$objGrids[0]->setitem(null,"u_or",formatNumericAmount($objRs->fields["u_or"]));
			$objGrids[0]->setitem(null,"u_pf",formatNumericAmount($objRs->fields["u_pf"]));
			$objGrids[0]->setitem(null,"u_total",formatNumericAmount($objRs->fields["u_room"]+$objRs->fields["u_med"]+$objRs->fields["u_lab"]+$objRs->fields["u_or"]+$objRs->fields["u_pf"]));
		} else {
			$objGrids[0]->setitem(null,"u_room",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_med",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_lab",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_or",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_pf",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_total",formatNumericAmount($objRs->fields["u_total"]));
		}	
	}
	return true;
}

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
			if ($column=="u_inscode") {
				$objRs->queryopen("select name from u_hishealthins where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}	
			break;
	}
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

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_xmtalno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalhfamount",false);
$page->businessobject->items->seteditable("u_totalpfamount",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columntitle("u_name","Patient Name");
$objGrids[0]->columnwidth("u_reconremarks",30);
$objGrids[0]->columnwidth("u_refno",12);
$objGrids[0]->columnwidth("u_refdate",9);
$objGrids[0]->columnwidth("u_inscode",15);
$objGrids[0]->columnwidth("u_memberid",18);
$objGrids[0]->columnwidth("u_membername",25);
$objGrids[0]->columnwidth("u_patientid",12);
$objGrids[0]->columnwidth("u_patientname",25);
$objGrids[0]->columnwidth("u_icdcode",30);
$objGrids[0]->columnwidth("u_remarks",15);
$objGrids[0]->columnwidth("u_age",5);
$objGrids[0]->columnwidth("u_gender",5);
$objGrids[0]->columnwidth("u_room",15);
$objGrids[0]->columnwidth("u_med",15);
$objGrids[0]->columnwidth("u_lab",15);
$objGrids[0]->columnwidth("u_or",15);
$objGrids[0]->columnwidth("u_pf",15);
$objGrids[0]->columnwidth("u_total",15);
$objGrids[0]->columninput("u_reconremarks","type","text");
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columnlnkbtn("u_refno","OpenLnkBtnRefNoGPSHIS()");
$objGrids[0]->columnattributes("u_reconremarks","disabled");
$objGrids[0]->dataentry = false;

$addoptions = false;
$deleteoption = false;
$canceloption = false;

//$page->toolbar->setaction("update",false);
$page->toolbar->setaction("new",false);

?> 

