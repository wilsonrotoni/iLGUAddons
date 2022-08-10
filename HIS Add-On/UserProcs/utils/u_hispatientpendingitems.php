<?php

function setGridColumnLabelPatientPendingItemsGPSHIS($tablename,$column,$row,&$label) {
	global $u_HISSectionsList;
	switch ($tablename) {
		case "T113":
			if ($column=="department") $label = $u_HISSectionsList[$label];
			return true;
			break;
	}
	return false;
}

function retrievePatientPendingItemsGPSHIS($reftype,$refno,$mode="0") { 
	global $objConnection;
	global $objGridPendingDocs;
	//$mode = 0 display data in grids
	//		= 1 validate
	$objRs = new recordset(null,$objConnection);

	$objGridPendingDocs->clear();
	$objRs->queryopen("select D.NAME AS REQUESTDEPARTMENTNAME, A.DOCNO, A.U_REQUESTDATE, A.U_REFTYPE, A.U_REFNO, A.U_PATIENTID, A.U_PATIENTNAME, A.U_PREPAID, A.U_PAYREFNO,
B.NAME AS DEPARTMENTNAME, E.U_ITEMDESC, E.U_QUANTITY, E.U_CHRGQTY, E.U_RTQTY, E.U_UOM, A.U_TRXTYPE
from U_HISREQUESTS A,U_HISREQUESTITEMS E, U_HISSECTIONS B, U_HISSECTIONS D
WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND E.U_QUANTITY>(E.U_CHRGQTY+E.U_RTQTY) AND D.CODE=A.U_REQUESTDEPARTMENT AND B.CODE=A.U_DEPARTMENT AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_REFTYPE='$reftype' AND A.U_REFNO='".$refno."' AND A.DOCSTATUS IN ('O') AND A.U_PREPAID IN (0,2) ORDER BY U_REQUESTDATE");		

	while ($objRs->queryfetchrow("NAME")) {
		$objGridPendingDocs->addrow();
		$objGridPendingDocs->setitem(null,"docdate",formatDateToHttp($objRs->fields["U_REQUESTDATE"]));
		$objGridPendingDocs->setitem(null,"requestdepartmentname",$objRs->fields["REQUESTDEPARTMENTNAME"]);
		$objGridPendingDocs->setitem(null,"departmentname",$objRs->fields["DEPARTMENTNAME"]);
		$objGridPendingDocs->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridPendingDocs->setitem(null,"itemdesc",$objRs->fields["U_ITEMDESC"]);
		$objGridPendingDocs->setitem(null,"quantity",formatNumericQuantity($objRs->fields["U_QUANTITY"]));
		$objGridPendingDocs->setitem(null,"uom",$objRs->fields["U_UOM"]);
		$objGridPendingDocs->setitem(null,"chrgqty",formatNumericQuantity($objRs->fields["U_CHRGQTY"]));
		$objGridPendingDocs->setitem(null,"rtqty",formatNumericQuantity($objRs->fields["U_RTQTY"]));
		$objGridPendingDocs->setitem(null,"trxtype",$objRs->fields["U_TRXTYPE"]);
	}
	return true;
}

$objGridPendingDocs = new grid("T112");
$objGridPendingDocs->addcolumn("docdate");
$objGridPendingDocs->addcolumn("docno");
$objGridPendingDocs->addcolumn("requestdepartmentname");
$objGridPendingDocs->addcolumn("departmentname");
$objGridPendingDocs->addcolumn("itemdesc");
$objGridPendingDocs->addcolumn("quantity");
$objGridPendingDocs->addcolumn("uom");
$objGridPendingDocs->addcolumn("chrgqty");
$objGridPendingDocs->addcolumn("rtqty");
$objGridPendingDocs->addcolumn("trxtype");
$objGridPendingDocs->columntitle("docdate","Date");
$objGridPendingDocs->columntitle("requestdepartmentname","Requesting Section");
$objGridPendingDocs->columntitle("departmentname","Section");
$objGridPendingDocs->columntitle("docno","No.");
$objGridPendingDocs->columntitle("itemdesc","Item Description");
$objGridPendingDocs->columntitle("quantity","Qty");
$objGridPendingDocs->columntitle("uom","UoM");
$objGridPendingDocs->columntitle("chrgqty","Ren Qty");
$objGridPendingDocs->columntitle("rtqty","Cr Qty");
$objGridPendingDocs->columnwidth("docdate",9);
$objGridPendingDocs->columnwidth("requestdepartmentname",20);
$objGridPendingDocs->columnwidth("departmentname",20);
$objGridPendingDocs->columnwidth("docno",10);
$objGridPendingDocs->columnwidth("itemdesc",42);
$objGridPendingDocs->columnwidth("quantity",8);
$objGridPendingDocs->columnwidth("uom",5);
$objGridPendingDocs->columnwidth("chrgqty",8);
$objGridPendingDocs->columnwidth("rtqty",8);
$objGridPendingDocs->columnlnkbtn("docno","OpenLnkBtnReqNoGPSHIS()");
$objGridPendingDocs->columnalignment("quantity","right");
$objGridPendingDocs->columnalignment("chrgqty","right");
$objGridPendingDocs->columnalignment("rtqty","right");
$objGridPendingDocs->columnvisibility("trxtype",false);
$objGridPendingDocs->dataentry = false;
$objGridPendingDocs->automanagecolumnwidth = false;
$objGridPendingDocs->width = 800;
$objGridPendingDocs->height = 240;


include_once("../Addons/GPS/HIS Add-On/UserProcs/utils/u_hissections.php");
initArrayu_HISSectionsListGPSHIS();

?> 

