<?php
	$progid = "u_histrxlist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/docgroups.php");
	include_once("./sls/users.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	unset($enumdocstatus["D"]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISTRXLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_histrxlist";
	$page->formid = "./UDO.php?&objectcode=U_HISCHARGES";
	$page->objectname = "In-Patient";
	
	$schema["u_refno"] = createSchemaUpper("u_refno");
	$schema["u_doctype"] = createSchemaUpper("u_doctype");
	$schema["u_datefr"] = createSchemaDate("u_datefr");
	$schema["u_dateto"] = createSchemaDate("u_dateto");
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["u_requestdepartment"] = createSchemaUpper("u_requestdepartment");
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");
	$schema["u_verified"] = createSchemaUpper("u_verified");

	$objrs = new recordset(null,$objConnection);
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		if ($userdepartment!="" && $page->getitemstring("u_doctype")!="CTC") $schema["u_department"]["attributes"] = "disabled";
	}

	switch ($page->getitemstring("u_trxtype")) {
		case "RADIOLOGY":
			$pageHeader = "Radiology - Transaction List";
			break;	
		case "LABORATORY":
			$pageHeader = "Laboratory - Transaction List";
			break;	
		case "HEARTSTATION":
			$pageHeader = "Heart Station - Transaction List";
			break;	
		case "PHARMACY":
			$pageHeader = "Pharmacy - Transaction List";
			break;	
		case "CSR":
			$pageHeader = "CSR - Transaction List";
			break;	
		case "DIALYSIS":
			$pageHeader = "Dialysis - Transaction List";
			break;	
		case "PULMONARY":
			$pageHeader = "Pulmonary - Transaction List";
			break;	
		case "OP":
			$pageHeader = "Out Patient - Transaction List";
			break;	
		case "IP":
			$pageHeader = "In Patient - Transaction List";
			break;	
		case "NURSE":
			$pageHeader = "Nurse Station - Transaction List";
			break;	
		case "SPLROOM":
			$pageHeader = "Special Room - Transaction List";
			break;	
		case "PMR":
			$pageHeader = "PMR - Transaction List";
			break;	
		case "DIETARY":
			$pageHeader = "Dietary - Transaction List";
			break;	
		case "MEDREC":
			$pageHeader = "Medical Records - Transaction List";
			break;	
		case "BILLING":
			$pageHeader = "Billing - Transaction List";
			break;	
		default:
			$pageHeader = "Transaction List";
			break;
	}

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_requestdate");
	$objGrid->addcolumn("u_requesttime");
	$objGrid->addcolumn("u_duedate");
	$objGrid->addcolumn("u_duetime");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_requestdepartment");
	$objGrid->addcolumn("u_requestdepartmentname");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_dueamount");
	$objGrid->addcolumn("u_itemcode");
	$objGrid->addcolumn("u_itemdesc");
	$objGrid->addcolumn("u_result");
	$objGrid->addcolumn("u_price");
	$objGrid->addcolumn("u_quantity");
	$objGrid->addcolumn("u_openqty");
	$objGrid->addcolumn("u_chrgqty");
	$objGrid->addcolumn("u_rtqty");
	$objGrid->addcolumn("u_remarks");
	$objGrid->addcolumn("u_department");
	$objGrid->addcolumn("u_departmentname");
	$objGrid->addcolumn("u_reftype");
	$objGrid->addcolumn("u_refno");
	$objGrid->addcolumn("u_patientid");
	$objGrid->addcolumn("u_paymentterm");
	$objGrid->addcolumn("u_prepaid","u_prepaiddesc");
	$objGrid->addcolumn("u_payrefno");
	$objGrid->addcolumn("docstatus","u_docstatusname");
	$objGrid->addcolumn("lastupdated");
	$objGrid->addcolumn("u_template");
	$objGrid->addcolumn("u_linetotal");
	$objGrid->addcolumn("u_requestdate2");
	$objGrid->addcolumn("u_requesttime2");
	$objGrid->addcolumn("u_birthdate");
	$objGrid->addcolumn("u_gender");
	$objGrid->addcolumn("u_age");
	$objGrid->addcolumn("u_disccode");
	$objGrid->addcolumn("u_pricelist");
	$objGrid->columntitle("u_requestdate","Date");
	$objGrid->columntitle("u_requesttime","Time");
	$objGrid->columntitle("u_duedate","Due Date");
	$objGrid->columntitle("u_duetime","Time");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_department","Rendering Section");
	$objGrid->columntitle("u_departmentname","Rendering Section");
	$objGrid->columntitle("u_requestdepartment","Requesting Section");
	$objGrid->columntitle("u_requestdepartmentname","Requesting Section");
	$objGrid->columntitle("u_reftype","Visit");
	$objGrid->columntitle("u_refno","Visit No.");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_dueamount","Amount");
	$objGrid->columntitle("u_itemcode","Item Code");
	$objGrid->columntitle("u_itemdesc","Item Description");
	$objGrid->columntitle("u_result","");
	$objGrid->columntitle("u_price","Price");
	$objGrid->columntitle("u_quantity","Qty");
	$objGrid->columntitle("u_openqty","Open Qty");
	$objGrid->columntitle("u_chrgqty","Ch/Ren Qty");
	$objGrid->columntitle("u_rtqty","Rt/Cr Qty");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("u_paymentterm","Payment Term");
	$objGrid->columntitle("u_prepaid","Term");
	$objGrid->columntitle("u_payrefno","Receipt No.");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columntitle("lastupdated","Last Updated");
	$objGrid->columnwidth("u_requestdate",8);
	$objGrid->columnwidth("u_requesttime",5);
	$objGrid->columnwidth("u_duedate",8);
	$objGrid->columnwidth("u_duetime",5);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_department",18);
	$objGrid->columnwidth("u_departmentname",18);
	$objGrid->columnwidth("u_requestdepartment",18);
	$objGrid->columnwidth("u_requestdepartmentname",18);
	$objGrid->columnwidth("u_reftype",4);
	$objGrid->columnwidth("u_refno",8);
	$objGrid->columnwidth("u_patientid",10);
	$objGrid->columnwidth("u_patientname",25);
	$objGrid->columnwidth("u_itemcode",10);
	$objGrid->columnwidth("u_itemdesc",30);
	$objGrid->columnwidth("u_result",5);
	$objGrid->columnwidth("u_price",8);
	$objGrid->columnwidth("u_quantity",7);
	$objGrid->columnwidth("u_openqty",7);
	$objGrid->columnwidth("u_remarks",20);
	$objGrid->columnwidth("u_paymentterm",12);
	$objGrid->columnwidth("u_prepaid",6);
	$objGrid->columnwidth("u_payrefno",10);
	$objGrid->columnwidth("u_openqty",7);
	$objGrid->columnwidth("u_chrgqty",9);
	$objGrid->columnwidth("u_rtqty",8);
	$objGrid->columnwidth("docstatus",9);
	$objGrid->columnwidth("lastupdated",15);
	$objGrid->columnalignment("u_result","center");
	$objGrid->columnalignment("u_dueamount","right");
	$objGrid->columnalignment("u_price","right");
	$objGrid->columnalignment("u_quantity","right");
	$objGrid->columnalignment("u_openqty","right");
	$objGrid->columnalignment("u_chrgqty","right");
	$objGrid->columnalignment("u_rtqty","right");
	$objGrid->columnsortable("u_requestdate",true);
	$objGrid->columnsortable("u_requesttime",true);
	$objGrid->columnsortable("u_duedate",true);
	$objGrid->columnsortable("u_duetime",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_departmentname",true);
	$objGrid->columnsortable("u_requestdepartmentname",true);
	$objGrid->columnsortable("u_patientid",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_itemcode",true);
	$objGrid->columnsortable("u_itemdesc",true);
	$objGrid->columnsortable("docstatus",true);
//	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	$objGrid->columninput("u_result","type","link");
	$objGrid->columninput("u_result","caption","Result");
	$objGrid->columninput("u_result","tooltip","Enter Result");
	$objGrid->columnlnkbtn("u_refno","OpenLnkBtnRefNos()");
	$objGrid->columnvisibility("u_paymentterm",false);
	$objGrid->columnvisibility("u_price",false);
	$objGrid->columnvisibility("u_duedate",false);
	$objGrid->columnvisibility("u_duetime",false);
	$objGrid->columnvisibility("u_department",false);
	$objGrid->columnvisibility("u_departmentname",true);
	$objGrid->columnvisibility("u_requestdepartment",false);
	$objGrid->columnvisibility("u_requestdepartmentname",false);
	$objGrid->columnvisibility("u_patientid",false);
	$objGrid->columnvisibility("u_itemcode",false);
	$objGrid->columnvisibility("u_template",false);
	$objGrid->columnvisibility("u_openqty",false);
	$objGrid->columnvisibility("u_chrgqty",false);
	$objGrid->columnvisibility("u_rtqty",false);
	$objGrid->columnvisibility("u_dueamount",false);
	$objGrid->columnvisibility("u_linetotal",false);
	$objGrid->columnvisibility("u_result",false);
	$objGrid->automanagecolumnwidth = false;

	if ($lookupSortBy == "") {
		$lookupSortBy = "docno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	$groupby = "";
	$isgroup = false;
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		global $groupby;
		global $isgroup;
		global $lookupSortBy;
		global $objGrid;
		global $page;
		//if ($lookupSortBy=="docno") {
			switch ($column) {
				case "u_requestdate":
					//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
					if ($groupby!=$objGrid->getitemstring($row,"docno")) {
						$groupby = $objGrid->getitemstring($row,"docno");
						$isgroup = false;
					} else {
						$isgroup = true;
						$label = "";
					}	
					break;
				case "u_duedate":
				case "u_duetime":
				case "docno":
				case "u_reftype":
				case "u_refno":
				case "u_requesttime":
				case "u_patientname":
				case "u_dueamount":
				case "u_requestdepartmentname":
				case "u_departmentname":
				case "u_prepaid":
				case "u_payrefno":
				case "docstatus":
					if ($isgroup) $label = "";
					break;
			}
		//}
	}
	
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","O");
		$page->setitem("u_doctype","INREQ");
		$page->setitem("u_department",$userdepartment);
		if ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") {
			//$page->setitem("docstatus","");
			$page->setitem("u_verified",0);
		}
	}
	
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":
			$objGrid->columnvisibility("u_requestdepartmentname",true);
			$objGrid->columnvisibility("u_duedate",true);
			$objGrid->columnvisibility("u_duetime",true);
			$objGrid->columnvisibility("u_openqty",true);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hisrequests()");
			break;
		case "REQ":
			$objGrid->columnvisibility("u_requestdepartmentname",true);
			$objGrid->columnvisibility("u_duedate",true);
			$objGrid->columnvisibility("u_duetime",true);
			$objGrid->columnvisibility("u_chrgqty",true);
			$objGrid->columnvisibility("u_rtqty",true);
			break;
		case "CHRG":
			$objGrid->columnvisibility("u_rtqty",true);
			if ($page->getitemstring("u_trxtype")=="LABORATORY") {
				$objGrid->columnvisibility("u_result",true);
			}	
			break;
		case "PC":
			$objGrid->columnwidth("u_quantity",4);
			$objGrid->columnvisibility("u_rtqty",true);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hischarges()");
			$objGrid->columnwidth("u_itemdesc",20);
			break;
		case "CTC":
			$objGrid->columnvisibility("u_requestdepartmentname",true);
			$objGrid->columnvisibility("u_duedate",true);
			$objGrid->columnvisibility("u_duetime",true);
			$objGrid->columnvisibility("u_openqty",true);
			$objGrid->columnvisibility("u_dueamount",true);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hisrequests()");
			$objGrid->columnvisibility("u_openqty",false);
			$objGrid->columnvisibility("u_payrefno",false);
			$objGrid->columnvisibility("u_prepaid",false);
			$objGrid->columnvisibility("docstatus",false);
			break;
	}

	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY":
		case "CSR":
			$objGrid->columnvisibility("u_duedate",false);
			$objGrid->columnvisibility("u_duetime",false);
			$objGrid->columnvisibility("u_paymentterm",true);
			$objGrid->columnvisibility("u_price",true);
		
			break;
	}
	
	
	//var_dump($httpVars['df_u_requestdepartment']);
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.u_requestdate, A.u_requesttime, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	//var_dump($page->getitemstring("u_nursed"));
	$objrs->setdebug();
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":		
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
			if ($httpVars['df_u_datefr']!=$httpVars['df_u_dateto']) {
				$filterExp = genSQLFilterDate("A.U_DUEDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
			} else {
				$filterExp = genSQLFilterDate("A.U_DUEDATE",$filterExp,$httpVars['df_u_datefr']);
			}
			$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
			$filterExp = genSQLFilterString("A.U_REQUESTDEPARTMENT",$filterExp,$httpVars['df_u_requestdepartment']);
			$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
			
			$qtyExp = " AND E.U_QUANTITY > (E.U_CHRGQTY + E.U_RTQTY)";
			if ($httpVars['df_docstatus']=="C") $qtyExp = "";
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$objrs->queryopenext("select A.DOCNO, A.U_REQUESTDATE, A.U_REQUESTTIME, A.U_DUEDATE, A.U_DUETIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, B.NAME AS U_DEPARTMENTNAME, A.U_PAYMENTTERM, D.NAME AS U_REQUESTDEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_PRICE, E.U_QUANTITY, E.U_CHRGQTY, E.U_RTQTY, E.U_REMARKS, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS from U_HISREQUESTS A, U_HISREQUESTITEMS E, U_HISSECTIONS B, U_HISITEMS C, U_HISSECTIONS D WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND D.CODE=A.U_REQUESTDEPARTMENT AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND B.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.DOCSTATUS NOT IN ('CN') $qtyExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "REQ":		
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
			if ($httpVars['df_u_datefr']!=$httpVars['df_u_dateto']) {
				$filterExp = genSQLFilterDate("A.U_REQUESTDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
			} else {
				$filterExp = genSQLFilterDate("A.U_REQUESTDATE",$filterExp,$httpVars['df_u_datefr']);
			}	
			$filterExp = genSQLFilterString("A.U_REQUESTDEPARTMENT",$filterExp,$httpVars['df_u_department']);
			$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);

			if ($filterExp !="") $filterExp = " AND " . $filterExp;
		
			$objrs->queryopenext("select A.DOCNO, A.U_REQUESTDATE, A.U_REQUESTTIME, A.U_DUEDATE, A.U_DUETIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, D.NAME AS U_DEPARTMENTNAME, A.U_PAYMENTTERM, B.NAME AS U_REQUESTDEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_PRICE, E.U_QUANTITY, E.U_CHRGQTY, E.U_RTQTY, E.U_REMARKS, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS, A.LASTUPDATED from U_HISREQUESTS A, U_HISREQUESTITEMS E, U_HISSECTIONS B, U_HISITEMS C, U_HISSECTIONS D WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND D.CODE=A.U_DEPARTMENT AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_REQUESTDEPARTMENT AND B.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $qtyExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "CHRG":		
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
			if ($httpVars['df_u_datefr']!=$httpVars['df_u_dateto']) {
				$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
			} else {
				$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_datefr']);
			}	
			$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
			$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);

			$qtyExp = "";
			if ($httpVars['df_docstatus']=="O") $qtyExp = " AND E.U_QUANTITY > E.U_RTQTY";
			
			$verifiedExp = "";
			if ($httpVars['df_u_verified']=="0") $verifiedExp = " AND F.U_ENDDATE IS NULL";
			else if ($httpVars['df_u_verified']=="1") $verifiedExp = " AND F.U_ENDDATE IS NOT NULL";

			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			if ($page->getitemstring("u_trxtype")=="LABORATORY") {	
				$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, B.NAME AS U_DEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, A.U_PAYMENTTERM, E.U_QUANTITY, E.U_PRICE, E.U_RTQTY, E.U_REMARKS, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS, A.LASTUPDATED, A.U_REQDATE, A.U_REQTIME, A.U_BIRTHDATE, A.U_GENDER, A.U_AGE, A.U_DISCCODE, A.U_PRICELIST from U_HISCHARGES A INNER JOIN U_HISCHARGEITEMS E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID INNER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT AND B.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') INNER JOIN U_HISITEMS C ON C.CODE=E.U_ITEMCODE LEFT JOIN U_HISLABTESTS F ON F.COMPANY=A.COMPANY AND F.BRANCH=A.BRANCH AND F.U_REQUESTNO=A.DOCNO WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $qtyExp $verifiedExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			} else {
				$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, B.NAME AS U_DEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, A.U_PAYMENTTERM, E.U_QUANTITY, E.U_PRICE, E.U_RTQTY, E.U_REMARKS, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS, A.LASTUPDATED from U_HISCHARGES A, U_HISCHARGEITEMS E, U_HISSECTIONS B, U_HISITEMS C WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND B.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $qtyExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			}	
			break;
		case "CM":		
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
			if ($httpVars['df_u_datefr']!=$httpVars['df_u_dateto']) {
				$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
			} else {
				$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_datefr']);
			}	
			$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
			$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);

			if ($filterExp !="") $filterExp = " AND " . $filterExp;
		
			$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, B.NAME AS U_DEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, A.U_PAYMENTTERM, E.U_PRICE, E.U_QUANTITY, E.U_REMARKS, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS, A.LASTUPDATED from U_HISCREDITS A, U_HISCREDITITEMS E, U_HISSECTIONS B, U_HISITEMS C WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND B.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $qtyExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "PC":		
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
			if ($httpVars['df_u_datefr']!=$httpVars['df_u_dateto']) {
				$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
			} else {
				$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_datefr']);
			}	
			$filterExp = genSQLFilterString("C.U_PROFITCENTER",$filterExp,$httpVars['df_u_department']);
			$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);

			if ($filterExp !="") $filterExp = " AND " . $filterExp;

			$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, B.NAME AS U_DEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, A.U_PAYMENTTERM, E.U_QUANTITY, E.U_PRICE, E.U_RTQTY, E.U_REMARKS, E.U_LINETOTAL, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS, A.LASTUPDATED from U_HISCHARGES A, U_HISCHARGEITEMS E, U_HISSECTIONS B, U_HISITEMS C, U_HISSECTIONS D WHERE D.CODE=C.U_PROFITCENTER AND E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND D.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $verifiedExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "CTC":		
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
			if ($httpVars['df_u_datefr']!=$httpVars['df_u_dateto']) {
				$filterExp = genSQLFilterDate("A.U_DUEDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
			} else {
				$filterExp = genSQLFilterDate("A.U_DUEDATE",$filterExp,$httpVars['df_u_datefr']);
			}	
			$filterExp = genSQLFilterString("A.U_REQUESTDEPARTMENT",$filterExp,$httpVars['df_u_department']);
			$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$objrs->queryopenext("select A.DOCNO, A.U_REQUESTDATE, A.U_REQUESTTIME, A.U_DUEDATE, A.U_DUETIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, B.NAME AS U_DEPARTMENTNAME, D.NAME AS U_REQUESTDEPARTMENTNAME, A.U_PAYMENTTERM, E.U_ITEMCODE, E.U_ITEMDESC, E.U_PRICE, E.U_QUANTITY, E.U_CHRGQTY, E.U_RTQTY, E.U_REMARKS, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS, A.LASTUPDATED, A.U_AMOUNT AS U_DUEAMOUNT from U_HISREQUESTS A, U_HISREQUESTITEMS E, U_HISSECTIONS B, U_HISITEMS C, U_HISSECTIONS D WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND D.CODE=A.U_REQUESTDEPARTMENT AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_PREPAID IN (1,2)  AND A.U_PAYREFNO='' AND A.U_AMOUNT>0 AND A.DOCSTATUS NOT IN ('C','CN') $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
	}		
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		switch ($page->getitemstring("u_doctype")) {
			case "INREQ":		
				$objGrid->setitem(null,"u_requestdate",formatDateToHttp($objrs->fields["U_REQUESTDATE"]));
				$objGrid->setitem(null,"u_requesttime",formatTimeToHttp($objrs->fields["U_REQUESTTIME"]));
				$objGrid->setitem(null,"u_duedate",formatDateToHttp($objrs->fields["U_DUEDATE"]));
				$objGrid->setitem(null,"u_duetime",formatTimeToHttp($objrs->fields["U_DUETIME"]));
				$objGrid->setitem(null,"u_openqty",formatNumericQuantity($objrs->fields["U_QUANTITY"] - ($objrs->fields["U_CHRGQTY"]+$objrs->fields["U_RTQTY"])));
				break;
			case "REQ":		
				$objGrid->setitem(null,"u_requestdate",formatDateToHttp($objrs->fields["U_REQUESTDATE"]));
				$objGrid->setitem(null,"u_requesttime",formatTimeToHttp($objrs->fields["U_REQUESTTIME"]));
				$objGrid->setitem(null,"u_duedate",formatDateToHttp($objrs->fields["U_DUEDATE"]));
				$objGrid->setitem(null,"u_duetime",formatTimeToHttp($objrs->fields["U_DUETIME"]));
				$objGrid->setitem(null,"u_chrgqty",formatNumericQuantity($objrs->fields["U_CHRGQTY"]));
				$objGrid->setitem(null,"u_rtqty",formatNumericQuantity($objrs->fields["U_RTQTY"]));
				break;
			case "CHRG":		
				$objGrid->setitem(null,"u_requestdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
				$objGrid->setitem(null,"u_requesttime",formatTimeToHttp($objrs->fields["U_STARTTIME"]));
				$objGrid->setitem(null,"u_rtqty",formatNumericQuantity($objrs->fields["U_RTQTY"]));
				$objGrid->setitem(null,"u_requestdate2",formatDateToHttp($objrs->fields["U_REQDATE"]));
				$objGrid->setitem(null,"u_requesttime2",formatTimeToHttp($objrs->fields["U_REQTIME"]));
				$objGrid->setitem(null,"u_birthdate",formatDateToHttp($objrs->fields["U_BIRTHDATE"]));
				$objGrid->setitem(null,"u_gender",$objrs->fields["U_GENDER"]);
				$objGrid->setitem(null,"u_age",$objrs->fields["U_AGE"]);
				$objGrid->setitem(null,"u_disccode",$objrs->fields["U_DISCCODE"]);
				$objGrid->setitem(null,"u_pricelist",$objrs->fields["U_PRICELIST"]);
				break;
			case "CTC":		
				$objGrid->setitem(null,"u_requestdate",formatDateToHttp($objrs->fields["U_REQUESTDATE"]));
				$objGrid->setitem(null,"u_requesttime",formatTimeToHttp($objrs->fields["U_REQUESTTIME"]));
				$objGrid->setitem(null,"u_duedate",formatDateToHttp($objrs->fields["U_DUEDATE"]));
				$objGrid->setitem(null,"u_duetime",formatTimeToHttp($objrs->fields["U_DUETIME"]));
				$objGrid->setitem(null,"u_dueamount",formatNumericQuantity($objrs->fields["U_DUEAMOUNT"]));
				break;
			case "PC":		
				$objGrid->setitem(null,"u_requestdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
				$objGrid->setitem(null,"u_requesttime",formatTimeToHttp($objrs->fields["U_STARTTIME"]));
				$objGrid->setitem(null,"u_linetotal",formatNumericQuantity($objrs->fields["U_LINETOTAL"]));
				break;
			default:	
				$objGrid->setitem(null,"u_requestdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
				$objGrid->setitem(null,"u_requesttime",formatTimeToHttp($objrs->fields["U_STARTTIME"]));
				break;
		}		
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_requestdepartmentname",$objrs->fields["U_REQUESTDEPARTMENTNAME"]);
		$objGrid->setitem(null,"u_departmentname",$objrs->fields["U_DEPARTMENTNAME"]);
		$objGrid->setitem(null,"u_reftype",$objrs->fields["U_REFTYPE"]);
		$objGrid->setitem(null,"u_refno",$objrs->fields["U_REFNO"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["U_PATIENTID"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["U_PATIENTNAME"]);
		$objGrid->setitem(null,"u_paymentterm",$objrs->fields["U_PAYMENTTERM"]);
		$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
		$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_price",formatNumericAmount($objrs->fields["U_PRICE"]));
		$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["U_QUANTITY"]));
		$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
		$objGrid->setitem(null,"u_prepaid",$objrs->fields["U_PREPAID"]);
		$objGrid->setitem(null,"u_prepaiddesc",iif($objrs->fields["U_PREPAID"]==0,"Charge","Cash"));
		$objGrid->setitem(null,"u_payrefno",iif($objrs->fields["U_AMOUNT"]==0,"FOC",$objrs->fields["U_PAYREFNO"]));
		$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
		$objGrid->setitem(null,"u_template",$objrs->fields["U_TEMPLATE"]);
		$objGrid->setitem(null,"lastupdated",formatDateTime($objrs->fields["LASTUPDATED"]));
		$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		if (!$page->paging_fetch()) break;
	}	
	
	$page->resize->addgrid("T1",20,210,false);
	
	$page->toolbar->setaction("update",false);
	
	if ($page->getitemstring("u_doctype")!="INREQ" && $page->getitemstring("u_doctype")!="PC") {
		$page->toolbar->addbutton("u_new","New","u_newGPSHIS()","right");
	}	
	
	//$rptcols = 6; 
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatients.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatientregs.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function OpenLnkBtnRefNos(targetObjectId) {
		var row = targetObjectId.indexOf("T1r");		
		if (getTableInput("T1",'u_reftype',targetObjectId.substring(row+3,targetObjectId.length))=="IP") {
			OpenLnkBtnu_hisips(targetObjectId);
		} else {
			OpenLnkBtnu_hisops(targetObjectId);
		}
		
	}

	function onPageLoad() {
		hideInput("u_requestdepartment");
		if (getInput("u_doctype")=="INREQ") {
			showInput("u_requestdepartment");
		}	
		hideInput("u_verified");
		hideElementById("ocf_u_verified_all",true);
		hideElementById("ocf_u_verified_yes",true);
		hideElementById("ocf_u_verified_no",true);
		if (getInput("u_doctype")=="CHRG" && (getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="RADIOLOGY" || getInput("u_trxtype")=="HEARTSTATION")) {
			showInput("u_verified");
			showElementById("ocf_u_verified_all",true);
			showElementById("ocf_u_verified_yes",true);
			showElementById("ocf_u_verified_no",true);
		}	
		if (getInput("u_doctype")=="CTC") {
			hideInput("docstatus");
		}
		
		//focusInput("u_refno");
		focusInput("u_patientname");
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				switch (getInput("u_doctype")) {
					case "INREQ":
						if (getTableInput("T1","docstatus",p_rowIdx)=="O") {
							if (getTableInput("T1","u_prepaid",p_rowIdx)!="0" && getTableInput("T1","u_payrefno",p_rowIdx)=="") {
								page.statusbar.showWarning("Request term is cash and was not paid yet.");
							} else {
								var targetObjectId = '';
								OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
							}	
						}	
						break;
					case "REQ":
						setKey("keys",getTableKey("T1","keys",p_rowIdx));
						return formView(null,"./UDO.php?&objectcode=U_HISREQUESTS");
						break;	
					case "CHRG":
						setKey("keys",getTableKey("T1","keys",p_rowIdx));
						return formView(null,"./UDO.php?&objectcode=U_HISCHARGES");
						break;	
					case "CM":
						setKey("keys",getTableKey("T1","keys",p_rowIdx));
						return formView(null,"./UDO.php?&objectcode=U_HISCREDITS");
						break;	
					case "CTC":
						var targetObjectId = '';
						OpenLnkBtn(1024,410,'./udo.php?objectcode=u_hischargeprepaidrequests' + '' + '&targetId=' + targetObjectId ,targetObjectId);					
						break;
					case "PC":
						var targetObjectId = '';
						OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtestrfs&df_u_examno='+getTableInput("T1","docno",p_rowIdx)+'&df_u_itemcode=' +getTableInput("T1","u_itemcode",p_rowIdx) + '' + '&targetId=' + targetObjectId ,targetObjectId);
						break;	
				}		
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "u_hispatients":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "u_department":
			case "u_requestdepartment":
			case "docstatus":
				clearTable("T1");
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_refno":
			case "u_patientname":
			case "u_dateto":
				clearTable("T1");
				formPageReset(); 
				break;
			case "u_datefr":
				setInput("u_dateto",getInput("u_datefr"));
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch(column) {
					case "u_result":
						var targetObjectId = 'u_hislabtests';
						OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtests' + '&df_u_requestno='+getTableInput(table,"docno",row)+'&df_u_type=' +getTableInput(table,"u_template",row) + '&targetId=' + targetObjectId ,targetObjectId);
						break;	
				}
				break;
			default:
				switch (column) {
					case "u_doctype":
						clearTable("T1");
						formPageReset(); 
						if ((getInput("u_doctype")=="CHRG" || getInput("u_doctype")=="CM") && (getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="RADIOLOGY" || getInput("u_trxtype")=="HEARTSTATION")) {
							setInput("docstatus","");
							setInput("u_verified","0");
						}
						formSearchNow();
						break;	
					case "u_verified":
						clearTable("T1");
						formPageReset(); 
						formSearchNow();
						break;	
				}		
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
			case "df_u_requestdepartment":
			case "df_u_refno":
			case "df_u_patientname":
			case "df_docstatus":
			case "df_doctype":
			case "df_date":
			case "df_verified":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_department");
			inputs.push("u_requestdepartment");
			inputs.push("u_refno");
			inputs.push("u_patientname");
			inputs.push("u_trxtype");
			inputs.push("docstatus");
			inputs.push("u_doctype");
			inputs.push("u_datefr");
			inputs.push("u_dateto");
			inputs.push("u_verified");
		return inputs;
	}
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_refno":
			case "u_patientname":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
		}
	}	
	
	function OpenLnkBtnu_hisrequests(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
		
	}

	function OpenLnkBtnu_hischarges(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
		
	}
	
	function OpenLnkBtnPriceList(targetId) {
		OpenLnkBtn(800,600,'./udp.php?objectcode=u_hispricelist&df_u_trxtype=' + getInput("u_trxtype") + '&targetId=' + targetId ,targetId);
	}
	
	function u_newGPSHIS() {
		switch (getInput("u_doctype")) {
			case "REQ":
				setKey("keys","");
				return formView(null,"./UDO.php?&objectcode=U_HISREQUESTS");
				break;
			case "CHRG":
				setKey("keys","");
				return formView(null,"./UDO.php?&objectcode=U_HISCHARGES");
				break;
			case "CM":
				setKey("keys","");
				return formView(null,"./UDO.php?&objectcode=U_HISCREDITS");
				break;
		}
	}
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" id="df_u_trxtype" name="df_u_trxtype" value="<?php echo $page->getitemstring("u_trxtype");  ?>">
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_doctype"],"") ?>>Type</label></td>
	  <td  align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"INREQ") ?> /><label class="lblobjs">Incoming Requests</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"REQ") ?> /><label class="lblobjs">Requests/Cash Sales</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"CHRG") ?> /><label class="lblobjs">Charged/Rendered</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"CM") ?> /><label class="lblobjs">Returns/Credits</b></label><?PHP if($page->getitemstring("u_trxtype")=="HEARTSTATION") { ?>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"PC") ?> /><label class="lblobjs">Services</b></label><?php } ?><?PHP if($page->getitemstring("u_trxtype")=="BILLING") { ?>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"CTC") ?> /><label class="lblobjs">Cash to Charge</b></label><?php } ?></td>
	  <td ><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="148">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:108px") ?> ></select></td>
	</tr>
	<tr>
	   <td ><label <?php genCaptionHtml($schema["u_department"],"") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_department"],array("loadudflinktable",iif($page->getitemstring("u_doctype")!="CTC","u_hissections:code:name:u_type in ('".$page->getitemstring("u_trxtype")."')","u_hissections:code:name:u_type<>''"),":[All]")) ?> ></select></td>
	  <td ><label <?php genCaptionHtml($schema["u_datefr"],"") ?>>Date From</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_datefr"]) ?> /></td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_refno"],"") ?>>Registration No.</label></td>
	  <td align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["u_refno"]) ?> /></td>
	  <td ><label <?php genCaptionHtml($schema["u_dateto"],"") ?>>Date To</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_dateto"]) ?> /></td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_patientname"],"") ?>>Patient Name</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_patientname"]) ?> /></td>
	  <td ><label <?php genCaptionHtml($schema["u_requestdepartment"],"") ?>>Requested From</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_requestdepartment"],array("loadudflinktable","u_hissections:code:name:u_type <> ''",":[All]"),null,null,null,"width:108px") ?> ></select></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
	  <td align=left><label <?php genCaptionHtml($schema["u_verified"],"") ?>>Result Released</label></td>
	  <td align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_verified"],"") ?> /><label <?php genOptionCaptionHtml(createSchema("u_verified_all")) ?>>All</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_verified"],"0") ?> /><label <?php genOptionCaptionHtml(createSchema("u_verified_no")) ?>>No</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_verified"],"1") ?> /><label <?php genOptionCaptionHtml(createSchema("u_verified_yes")) ?>>Yes</b></label></td>
	  </tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr ><td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>&nbsp;</td>
			<td align="right">
				[<a class="links" href="" onClick="OpenLnkBtnPriceList();return false">Price Inquiry</a>]
			</td>
		</tr>
	</table>	
</td></tr>
<?php if ($requestorId == "")	require("./GenericGridMasterDataToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&currentAction=" . $currentAction . $page->toolbar->generateQueryString() . "&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=./GenericGridMasterDataToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
