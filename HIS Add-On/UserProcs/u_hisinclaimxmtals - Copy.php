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
	$data["u_inscode"] = $page->getitemstring("u_inscode");
	$data["u_package"] = $page->getitemstring("u_package");
	$data["u_membergroup"] = $page->getitemstring("u_membergroup");
	$data["u_reftype"] = $page->getitemstring("u_reftype");
	$data["u_startdate"] = $page->getitemstring("u_startdate");
	$data["u_enddate"] = $page->getitemstring("u_enddate");
	$data["u_preparedby"] = $page->getitemstring("u_preparedby");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $objGrids;
	$page->setitem("u_docdate",$data["u_docdate"]);
	$page->setitem("u_inscode",$data["u_inscode"]);
	$page->setitem("u_package",$data["u_package"]);
	$page->setitem("u_membergroup",$data["u_membergroup"]);
	$page->setitem("u_reftype",$data["u_reftype"]);
	$page->setitem("u_startdate",$data["u_startdate"]);
	$page->setitem("u_enddate",$data["u_enddate"]);
	$page->setitem("u_preparedby",$data["u_preparedby"]);
	if ($page->getitemstring("u_package")=="") $page->setitem("u_package",1);
	
	$objRs = new recordset(null,$objConnection);
	
	$objGrids[0]->clear();
	$hf = 0;
	$pf = 0;
	$total = 0;
	
	$filterExp = "";
	$filterExp = genSQLFilterString("a.U_GUARANTORCODE",$filterExp,$page->getitemstring("u_inscode"));
	$filterExp = genSQLFilterDate("a.U_DOCDATE",$filterExp,$page->getitemstring("u_startdate"),$page->getitemstring("u_enddate"));
	$filterExp = genSQLFilterString("a.U_REFTYPE",$filterExp,$page->getitemstring("u_reftype"));
	if ($filterExp!="") $filterExp = " AND " . $filterExp;

	$filterExp2 = "";
	$filterExp2 = genSQLFilterString("a.U_INSCODE",$filterExp2,$page->getitemstring("u_inscode"));
	$filterExp2 = genSQLFilterDate("a.U_DOCDATE",$filterExp2,$page->getitemstring("u_startdate"),$page->getitemstring("u_enddate"));
	$filterExp2 = genSQLFilterString("a.U_REFTYPE",$filterExp2,$page->getitemstring("u_reftype"));
	if ($filterExp2!="") $filterExp2 = " AND " . $filterExp2;

	$filterExp3 = "";
	$filterExp3 = genSQLFilterString("b.U_INSCODE",$filterExp3,$page->getitemstring("u_inscode"));
	$filterExp3 = genSQLFilterDate("a.U_DOCDATE",$filterExp3,$page->getitemstring("u_startdate"),$page->getitemstring("u_enddate"));
	$filterExp3 = genSQLFilterString("a.U_REFTYPE",$filterExp3,$page->getitemstring("u_reftype"));
	if ($filterExp3!="") $filterExp3 = " AND " . $filterExp3;
	//$objRs->setdebug();
	if ($page->getitemstring("u_package")=="0") {
		if ($page->getitemstring("u_membergroup")!="") {
			$objRs->queryopen("select 'BILLINS' as U_DOCTYPE, a.DOCNO, a.U_DOCDATE, a.U_INSCODE AS U_GUARANTORCODE, a.U_PATIENTID, a.U_PATIENTNAME, a.U_GENDER, a.U_AGE, a.U_MEMBERID, a.U_MEMBERNAME, a.U_MEMBERTYPE, a.U_STARTDATE, a.U_ENDDATE, a.U_ICDCODE, sum(if(b.U_CHRGCODE='ROOM',b.U_INSAMOUNT,0)) as U_ROOM, sum(if(b.U_CHRGCODE='MED',b.U_INSAMOUNT,0)) as U_MED, sum(if(b.U_CHRGCODE='LAB',b.U_INSAMOUNT,0)) as U_LAB, sum(if(b.U_CHRGCODE='OR',b.U_INSAMOUNT,0)) as U_OR, sum(if(b.U_CHRGCODE<>'ROOM' and b.U_CHRGCODE<>'MED' and b.U_CHRGCODE<>'LAB' and b.U_CHRGCODE<>'OR',b.U_INSAMOUNT,0)) as U_PF from u_hisinclaims a, u_hisinclaimhfs b, u_hishealthinmemtypes c where c.code=a.u_membertype and c.u_group='".$page->getitemstring("u_membergroup")."' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','D') and a.U_HMO<>2 and a.u_xmtalno='' $filterExp2 group by a.DOCNO");
		} else {
			$objRs->queryopen("select 'BILLINS' as U_DOCTYPE, a.DOCNO, a.U_DOCDATE, a.U_INSCODE AS U_GUARANTORCODE, a.U_PATIENTID, a.U_PATIENTNAME, a.U_GENDER, a.U_AGE, a.U_MEMBERID, a.U_MEMBERNAME, a.U_MEMBERTYPE, a.U_STARTDATE, a.U_ENDDATE, a.U_ICDCODE, sum(if(b.U_CHRGCODE='ROOM',b.U_INSAMOUNT,0)) as U_ROOM, sum(if(b.U_CHRGCODE='MED',b.U_INSAMOUNT,0)) as U_MED, sum(if(b.U_CHRGCODE='LAB',b.U_INSAMOUNT,0)) as U_LAB, sum(if(b.U_CHRGCODE='OR',b.U_INSAMOUNT,0)) as U_OR, sum(if(b.U_CHRGCODE<>'ROOM' and b.U_CHRGCODE<>'MED' and b.U_CHRGCODE<>'LAB' and b.U_CHRGCODE<>'OR',b.U_INSAMOUNT,0)) as U_PF from u_hisinclaims a, u_hisinclaimhfs b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','D') and a.U_HMO<>2 and a.u_xmtalno='' $filterExp2 group by a.DOCNO");
		}	
	} else {
		if ($page->getitemstring("u_membergroup")!="") {
			$objRs->queryopen("select 'BILLPNS' as U_DOCTYPE, a.DOCNO, a.U_DOCDATE, a.U_GUARANTORCODE, a.U_PATIENTID, a.U_PATIENTNAME, a.U_GENDER, a.U_AGE, a.U_MEMBERID, a.U_MEMBERNAME, a.U_MEMBERTYPE, a.U_STARTDATE, a.U_ENDDATE, a.U_ICDCODE, a.U_PNAMOUNT, a.U_REMARKS from u_hispronotes a, u_hishealthinmemtypes c where c.code=a.u_membertype and c.u_group='".$page->getitemstring("u_membergroup")."' and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','D') and a.U_HMO<>2 and a.u_xmtalno='' $filterExp union all select 'POS' as U_DOCTYPE, a.DOCNO, a.U_DOCDATE, b.U_INSCODE AS U_GUARANTORCODE, a.U_PATIENTID, a.U_PATIENTNAME, '' as U_GENDER, 0 as U_AGE, b.U_MEMBERID, b.U_MEMBERNAME, b.U_MEMBERTYPE, '' as U_STARTDATE, '' as U_ENDDATE, '' as U_ICDCODE, b.U_AMOUNT as U_PNAMOUNT, d.NAME AS U_REMARKS from u_hispos a left outer join u_hissections d on d.code=a.u_department, u_hisposins b, u_hishealthinmemtypes c  where c.code=b.u_membertype and c.u_group='".$page->getitemstring("u_membergroup")."' and  b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','D') and b.U_HMO<>2 and b.u_xmtalno='' $filterExp3");
		} else {
			$objRs->queryopen("select 'BILLPNS' as U_DOCTYPE, a.DOCNO, a.U_DOCDATE, a.U_GUARANTORCODE, a.U_PATIENTID, a.U_PATIENTNAME, a.U_GENDER, a.U_AGE, a.U_MEMBERID, a.U_MEMBERNAME, a.U_MEMBERTYPE, a.U_STARTDATE, a.U_ENDDATE, a.U_ICDCODE, a.U_PNAMOUNT, a.U_REMARKS from u_hispronotes a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','D') and a.U_HMO<>2 and a.u_xmtalno='' $filterExp
			union all select 'POS' as U_DOCTYPE, a.DOCNO, a.U_DOCDATE, b.U_INSCODE AS U_GUARANTORCODE, a.U_PATIENTID, a.U_PATIENTNAME, '' as U_GENDER, 0 as U_AGE, b.U_MEMBERID, b.U_MEMBERNAME, b.U_MEMBERTYPE, '' as U_STARTDATE, '' as U_ENDDATE, '' as U_ICDCODE, b.U_AMOUNT as U_PNAMOUNT, d.NAME AS U_REMARKS from u_hispos a left outer join u_hissections d on d.code=a.u_department, u_hisposins b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.DOCSTATUS NOT IN ('CN','D') and b.U_HMO<>2 and b.u_xmtalno='' $filterExp3");
		}	
		// 
	}	
	//var_dump($objRs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_selected",0);
		$objGrids[0]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
		$objGrids[0]->setitem(null,"u_refdate",formatDateToHttp($objRs->fields["U_DOCDATE"]));
		$objGrids[0]->setitem(null,"u_reftype",$objRs->fields["U_DOCTYPE"]);
		$objGrids[0]->setitem(null,"u_inscode",$objRs->fields["U_GUARANTORCODE"]);
		$objGrids[0]->setitem(null,"u_patientid",$objRs->fields["U_PATIENTID"]);
		$objGrids[0]->setitem(null,"u_patientname",$objRs->fields["U_PATIENTNAME"]);
		$objGrids[0]->setitem(null,"u_gender",$objRs->fields["U_GENDER"]);
		$objGrids[0]->setitem(null,"u_age",$objRs->fields["U_AGE"]);
		$objGrids[0]->setitem(null,"u_memberid",$objRs->fields["U_MEMBERID"]);
		$objGrids[0]->setitem(null,"u_membername",$objRs->fields["U_MEMBERNAME"]);
		$objGrids[0]->setitem(null,"u_membertype",$objRs->fields["U_MEMBERTYPE"]);
		$objGrids[0]->setitem(null,"u_startdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
		$objGrids[0]->setitem(null,"u_enddate",formatDateToHttp($objRs->fields["U_ENDDATE"]));
		$objGrids[0]->setitem(null,"u_icdcode",$objRs->fields["U_ICDCODE"]);
		$objGrids[0]->setitem(null,"u_remarks",$objRs->fields["U_REMARKS"]);
		if ($page->getitemstring("u_package")=="0") {
			$objGrids[0]->setitem(null,"u_room",formatNumericAmount($objRs->fields["U_ROOM"]));
			$objGrids[0]->setitem(null,"u_med",formatNumericAmount($objRs->fields["U_MED"]));
			$objGrids[0]->setitem(null,"u_lab",formatNumericAmount($objRs->fields["U_LAB"]));
			$objGrids[0]->setitem(null,"u_or",formatNumericAmount($objRs->fields["U_OR"]));
			$objGrids[0]->setitem(null,"u_pf",formatNumericAmount($objRs->fields["U_PF"]));
			$objGrids[0]->setitem(null,"u_total",formatNumericAmount($objRs->fields["U_ROOM"]+$objRs->fields["U_MED"]+$objRs->fields["U_LAB"]+$objRs->fields["U_OR"]+$objRs->fields["U_PF"]));
			//$hf += $objRs->fields["U_ROOM"]+$objRs->fields["U_MED"]+$objRs->fields["U_LAB"]+$objRs->fields["U_OR"];
			//$pf += $objRs->fields["U_PF"];
			//$total += $objRs->fields["U_ROOM"]+$objRs->fields["U_MED"]+$objRs->fields["U_LAB"]+$objRs->fields["U_OR"]+$objRs->fields["U_PF"];
		} else {
			$objGrids[0]->setitem(null,"u_room",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_med",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_lab",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_or",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_pf",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_total",formatNumericAmount($objRs->fields["U_PNAMOUNT"]));
			//$total += $objRs->fields["U_PNAMOUNT"];
		}	
	}
	$page->setitem("u_totalhfamount",formatNumericAmount($hf));
	$page->setitem("u_totalpfamount",formatNumericAmount($pf));
	$page->setitem("u_totalamount",formatNumericAmount($total));
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

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalhfamount",false);
$page->businessobject->items->seteditable("u_totalpfamount",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columntitle("u_name","Patient Name");
$objGrids[0]->columnwidth("u_refno",12);
$objGrids[0]->columnwidth("u_refdate",9);
$objGrids[0]->columnwidth("u_inscode",15);
$objGrids[0]->columnwidth("u_memberid",15);
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
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columnlnkbtn("u_refno","OpenLnkBtnRefNoGPSHIS()");
//$objGrids[0]->columnvisibility("u_reftype",true);
$objGrids[0]->dataentry = false;

$addoptions = false;
$deleteoption = false;

//$page->toolbar->setaction("update",false);
$page->toolbar->setaction("new",false);

?> 

