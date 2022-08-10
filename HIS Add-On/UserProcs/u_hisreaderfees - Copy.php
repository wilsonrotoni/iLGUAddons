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
$u_trxtype="";

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $data;
	global $page;
	global $u_trxtype;
	$u_trxtype = $page->getitemstring("u_trxtype");
	$data["u_docdate"] = $page->getitemstring("u_docdate");
	if ($data["u_docdate"]=="") $data["u_docdate"] = currentdate();
	$data["u_department"] = $page->getitemstring("u_department");
	$data["u_reftype"] = $page->getitemstring("u_reftype");
	$data["u_startdate"] = $page->getitemstring("u_startdate");
	$data["u_enddate"] = $page->getitemstring("u_enddate");
	$data["u_refno"] = $page->getitemstring("u_refno");
	$data["u_preparedby"] = $page->getitemstring("u_preparedby");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $u_trxtype;
	global $objGrids;
	$page->setitem("u_trxtype",$u_trxtype);
	$page->setitem("u_docdate",$data["u_docdate"]);
	$page->setitem("u_department",$data["u_department"]);
	$page->setitem("u_reftype",$data["u_reftype"]);
	$page->setitem("u_startdate",$data["u_startdate"]);
	$page->setitem("u_enddate",$data["u_enddate"]);
	$page->setitem("u_refno",$data["u_refno"]);
	$page->setitem("u_preparedby",$data["u_preparedby"]);
	
	$objRs = new recordset(null,$objConnection);
	
	$objGrids[0]->clear();
	$u_totalhf = 0;
	$u_totalpf = 0;
	$u_totalamount = 0;
	$u_discamount = 0;
	
	
	$filterExp = "";
	if ($page->getitemstring("u_trxtype")=="HEARTSTATION") {
		$filterExp = genSQLFilterString("D.U_PROFITCENTER",$filterExp,$page->getitemstring("u_department"));
	} else {
		$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$page->getitemstring("u_department"));
	}	
	$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$page->getitemstring("u_startdate"),$page->getitemstring("u_enddate"));
	$filterExp = genSQLFilterString("A.U_REFTYPE",$filterExp,$page->getitemstring("u_reftype"));
	if ($filterExp!="") $filterExp = " AND " . $filterExp;

	//$objRs->setdebug();
	if ($page->getitemstring("u_department")!="") {
		if ($page->getitemstring("u_trxtype")=="HEARTSTATION") {
			$objRs->queryopen("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PAYREFNO, A.U_REFTYPE, A.U_REFNO, A.U_PAYMENTTERM, A.U_PATIENTID, A.U_PATIENTNAME, IF(A.U_DOCTORID2<>'',A.U_DOCTORID2,A.U_DOCTORID) AS U_DOCTORID, C.NAME AS U_DOCTORNAME, B.U_ITEMCODE, B.U_ITEMDESC, 
			IF(B.U_ISSTAT=1,B.U_STATUNITPRICE, B.U_UNITPRICE)*(B.U_QUANTITY-B.U_RTQTY) AS U_AMOUNT, 
			IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1, IF(B.U_ISSTAT=1,B.U_SCSTATDISCAMOUNT, D.U_SCDISCAMOUNT),0)*(B.U_QUANTITY-B.U_RTQTY) AS U_DISCAMOUNT, 
			IF(D.U_RFAMOUNT<>0, D.U_RFAMOUNT,(D.U_RFPERC/100)*(IF(B.U_ISSTAT=1,B.U_STATUNITPRICE, B.U_UNITPRICE) - IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1, IF(B.U_ISSTAT=1,B.U_SCSTATDISCAMOUNT, D.U_SCDISCAMOUNT),0)))*(B.U_QUANTITY-B.U_RTQTY)*IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1,.8,1) AS U_PF FROM U_HISCHARGES A INNER JOIN U_HISCHARGEITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND (B.U_QUANTITY-B.U_RTQTY)>0 INNER JOIN U_HISITEMS D ON D.CODE=B.U_ITEMCODE LEFT JOIN U_HISDOCTORS C ON C.CODE=IF(A.U_DOCTORID2<>'',A.U_DOCTORID2,A.U_DOCTORID) LEFT JOIN U_HISOPS E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCNO=A.U_REFNO AND A.U_REFTYPE='OP'  LEFT JOIN U_HISIPS F ON F.COMPANY=A.COMPANY AND F.BRANCH=A.BRANCH AND F.DOCNO=A.U_REFNO AND A.U_REFTYPE='IP'  WHERE A.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','D') $filterExp");
		} elseif ($page->getitemstring("u_trxtype")=="RADIOLOGY") {
			$objRs->queryopen("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PAYREFNO, A.U_REFTYPE, A.U_REFNO, A.U_PAYMENTTERM, A.U_PATIENTID, A.U_PATIENTNAME, A.U_DOCTORID2 AS U_DOCTORID, C.NAME AS U_DOCTORNAME, B.U_ITEMCODE, B.U_ITEMDESC, 
			IF(B.U_ISSTAT=1,B.U_STATUNITPRICE, B.U_UNITPRICE)*(B.U_QUANTITY-B.U_RTQTY) AS U_AMOUNT, 
			IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1, IF(B.U_ISSTAT=1,B.U_SCSTATDISCAMOUNT, D.U_SCDISCAMOUNT),0)*(B.U_QUANTITY-B.U_RTQTY) AS U_DISCAMOUNT, 
			IF(D.U_RFAMOUNT<>0, D.U_RFAMOUNT,(D.U_RFPERC/100)*(IF(B.U_ISSTAT=1,B.U_STATUNITPRICE, B.U_UNITPRICE) - IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1, IF(B.U_ISSTAT=1,B.U_SCSTATDISCAMOUNT, D.U_SCDISCAMOUNT),0)))*(B.U_QUANTITY-B.U_RTQTY)*IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1,.8,1) AS U_PF
			FROM U_HISCHARGES A INNER JOIN U_HISCHARGEITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND (B.U_QUANTITY-B.U_RTQTY)>0 INNER JOIN U_HISITEMS D ON D.CODE=B.U_ITEMCODE AND D.U_TEMPLATE<>'' LEFT JOIN U_HISDOCTORS C ON C.CODE=A.U_DOCTORID2 LEFT JOIN U_HISOPS E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCNO=A.U_REFNO AND A.U_REFTYPE='OP'  LEFT JOIN U_HISIPS F ON F.COMPANY=A.COMPANY AND F.BRANCH=A.BRANCH AND F.DOCNO=A.U_REFNO AND A.U_REFTYPE='IP'  WHERE A.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','D') $filterExp");
		} elseif ($page->getitemstring("u_trxtype")=="LABORATORY") {
			$objRs->queryopen("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PAYREFNO, A.U_REFTYPE, A.U_REFNO, A.U_PAYMENTTERM, A.U_PATIENTID, A.U_PATIENTNAME, A.U_DOCTORID2 AS U_DOCTORID, C.NAME AS U_DOCTORNAME, B.U_ITEMCODE, B.U_ITEMDESC, 
			IF(B.U_ISSTAT=1,B.U_STATUNITPRICE, B.U_UNITPRICE)*(B.U_QUANTITY-B.U_RTQTY) AS U_AMOUNT, 
			IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1, IF(B.U_ISSTAT=1,B.U_SCSTATDISCAMOUNT, D.U_SCDISCAMOUNT),0)*(B.U_QUANTITY-B.U_RTQTY) AS U_DISCAMOUNT, 
			IF(D.U_RFAMOUNT<>0, D.U_RFAMOUNT,(D.U_RFPERC/100)*(IF(B.U_ISSTAT=1,B.U_STATUNITPRICE, B.U_UNITPRICE) - IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1, IF(B.U_ISSTAT=1,B.U_SCSTATDISCAMOUNT, D.U_SCDISCAMOUNT),0)))*(B.U_QUANTITY-B.U_RTQTY)*IF(A.U_SCDISC=1 AND D.U_RFALLOWSCDISC=1,.8,1) AS U_PF
			FROM U_HISCHARGES A INNER JOIN U_HISCHARGEITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND B.U_TEMPLATE<>'' AND (B.U_QUANTITY-B.U_RTQTY)>0 INNER JOIN U_HISITEMS D ON D.CODE=B.U_ITEMCODE AND D.U_ISRF=1 LEFT JOIN U_HISDOCTORS C ON C.CODE=A.U_DOCTORID2 LEFT JOIN U_HISOPS E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCNO=A.U_REFNO AND A.U_REFTYPE='OP'  LEFT JOIN U_HISIPS F ON F.COMPANY=A.COMPANY AND F.BRANCH=A.BRANCH AND F.DOCNO=A.U_REFNO AND A.U_REFTYPE='IP'  WHERE A.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','D') $filterExp");
		/*} elseif ($page->getitemstring("u_trxtype")=="LABORATORY") {
			$objRs->queryopen("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PAYREFNO, A.U_REFTYPE, A.U_REFNO, A.U_PAYMENTTERM, A.U_PATIENTID, A.U_PATIENTNAME, G.U_DOCTORID2 AS U_DOCTORID, C.NAME AS U_DOCTORNAME, B.U_TEMPLATE AS U_ITEMCODE, D.NAME AS U_ITEMDESC, SUM(B.U_PRICE*(B.U_QUANTITY-B.U_RTQTY)) AS U_AMOUNT, SUM(B.U_DISCAMOUNT*(B.U_QUANTITY-B.U_RTQTY)) AS U_DISCAMOUNT FROM U_HISCHARGES A INNER JOIN U_HISCHARGEITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND B.U_TEMPLATE<>'' AND (B.U_QUANTITY-B.U_RTQTY)>0 INNER JOIN U_HISLABTESTTYPES D ON D.CODE=B.U_TEMPLATE LEFT JOIN U_HISLABTESTS G ON G.U_REQUESTNO=A.DOCNO AND G.U_TYPE=B.U_TEMPLATE LEFT JOIN U_HISDOCTORS C ON C.CODE=G.U_DOCTORID2 LEFT JOIN U_HISOPS E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCNO=A.U_REFNO AND A.U_REFTYPE='OP'  LEFT JOIN U_HISIPS F ON F.COMPANY=A.COMPANY AND F.BRANCH=A.BRANCH AND F.DOCNO=A.U_REFNO AND A.U_REFTYPE='IP'  WHERE A.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','D') $filterExp GROUP BY A.DOCNO, B.U_TEMPLATE");*/
		}	
	/*
	
	-)
	*/
//		$objRs->queryopen("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PAYREFNO, A.U_REFTYPE, A.U_REFNO, A.U_PAYMENTTERM, A.U_PATIENTID, A.U_PATIENTNAME,A.U_DOCTORID2 AS U_DOCTORID, C.NAME AS U_DOCTORNAME, B.U_ITEMCODE, B.U_ITEMDESC, B.U_PRICE*(B.U_QUANTITY-B.U_RTQTY) AS U_AMOUNT, B.U_DISCAMOUNT*(B.U_QUANTITY-B.U_RTQTY) AS U_DISCAMOUNT FROM U_HISCHARGES A INNER JOIN U_HISCHARGEITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID LEFT JOIN U_HISDOCTORS C ON C.CODE=A.U_DOCTORID2 WHERE A.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','D') $filterExp");
		//var_dump($objRs->sqls);
		//var_dump($objRs->recordcount());
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			//$objGrids[0]->setitem(null,"u_selected",0);
			$objGrids[0]->setitem(null,"u_docno",$objRs->fields["DOCNO"]);
			$objGrids[0]->setitem(null,"u_docdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
			$objGrids[0]->setitem(null,"u_doctime",formatTimeToHttp($objRs->fields["U_STARTTIME"]));
			$objGrids[0]->setitem(null,"u_payrefno",$objRs->fields["U_PAYREFNO"]);
			$objGrids[0]->setitem(null,"u_reftype",$objRs->fields["U_REFTYPE"]);
			$objGrids[0]->setitem(null,"u_refno",$objRs->fields["U_REFNO"]);
			$objGrids[0]->setitem(null,"u_patientid",$objRs->fields["U_PATIENTID"]);
			$objGrids[0]->setitem(null,"u_patientname",$objRs->fields["U_PATIENTNAME"]);
			$objGrids[0]->setitem(null,"u_paymentterm",$objRs->fields["U_PAYMENTTERM"]);
			$objGrids[0]->setitem(null,"u_doctorid",$objRs->fields["U_DOCTORID"]);
			$objGrids[0]->setitem(null,"u_doctorname",$objRs->fields["U_DOCTORNAME"]);
			$objGrids[0]->setitem(null,"u_itemcode",$objRs->fields["U_ITEMCODE"]);
			$objGrids[0]->setitem(null,"u_itemdesc",$objRs->fields["U_ITEMDESC"]);
			$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["U_AMOUNT"]));
			$objGrids[0]->setitem(null,"u_discamount",formatNumericAmount($objRs->fields["U_DISCAMOUNT"]));
			$objGrids[0]->setitem(null,"u_pf",formatNumericAmount($objRs->fields["U_PF"]));
			$objGrids[0]->setitem(null,"u_hf",formatNumericAmount($objRs->fields["U_AMOUNT"]-$objRs->fields["U_DISCAMOUNT"]-$objRs->fields["U_PF"]));
			$u_totalamount+=$objRs->fields["U_AMOUNT"];
			$u_discamount+=$objRs->fields["U_DISCAMOUNT"];
			$u_totalpf+=$objRs->fields["U_PF"];
			$u_totalhf+=$objRs->fields["U_AMOUNT"]-$objRs->fields["U_DISCAMOUNT"]-$objRs->fields["U_PF"];
		}
	}
	$page->setitem("u_totalamount",formatNumericAmount($u_totalamount));
	$page->setitem("u_discamount",formatNumericAmount($u_discamount));
	$page->setitem("u_totalhf",formatNumericAmount($u_totalhf));
	$page->setitem("u_totalpf",formatNumericAmount($u_totalpf));
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
$page->businessobject->items->seteditable("u_totalhf",false);
$page->businessobject->items->seteditable("u_totalpf",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$page->businessobject->items->setvisible("u_discamount",false);

$objGrids[0]->columnwidth("u_docdate",8);
$objGrids[0]->columnwidth("u_doctime",5);
$objGrids[0]->columnwidth("u_docno",8);
$objGrids[0]->columnwidth("u_payrefno",7);
$objGrids[0]->columnwidth("u_reftype",1);
$objGrids[0]->columnwidth("u_refno",8);
$objGrids[0]->columnwidth("u_amount",7);
$objGrids[0]->columnwidth("u_discamount",7);
$objGrids[0]->columnwidth("u_pf",8);
$objGrids[0]->columnwidth("u_hf",7);
$objGrids[0]->columnwidth("u_patientid",12);
$objGrids[0]->columnwidth("u_patientname",25);
$objGrids[0]->columnwidth("u_paymentterm",11);
$objGrids[0]->columnwidth("u_doctorname",30);
$objGrids[0]->columnwidth("u_itemdesc",20);
$objGrids[0]->columnwidth("u_total",15);
$objGrids[0]->columntitle("u_payrefno","OR/CM");
$objGrids[0]->columntitle("u_reftype","");
$objGrids[0]->columntitle("u_refno","Reg No.");
$objGrids[0]->columncfl("u_doctorname","OpenCFLfs()");
$objGrids[0]->columninput("u_doctorname","type","text");
$objGrids[0]->columninput("u_pf","type","text");
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columnlnkbtn("u_docno","OpenLnkBtnu_hischarges()");
$objGrids[0]->columnlnkbtn("u_refno","OpenLnkBtnRefNoGPSHIS()");
$objGrids[0]->columnvisibility("u_patientid",false);
$objGrids[0]->columnvisibility("u_itemcode",false);
$objGrids[0]->columnvisibility("u_doctorid",false);
$objGrids[0]->columnvisibility("u_healthins",false);
$objGrids[0]->columnvisibility("u_paymentterm",false);
//$objGrids[0]->columnvisibility("u_discamount",false);
$objGrids[0]->dataentry = false;

$addoptions = true;
$deleteoption = false;

//$page->toolbar->setaction("update",false);
$page->toolbar->setaction("new",false);

?> 

