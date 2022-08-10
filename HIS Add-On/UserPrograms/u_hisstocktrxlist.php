<?php
	$progid = "u_hisstocktrxlist";

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
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISSTOCKTRXLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisstocktrxlist";
	$page->formid = "./UDO.php?&objectcode=U_HISCHARGES";
	$page->objectname = "In-Patient";
	
	$schema["u_doctype"] = createSchemaUpper("u_doctype");
	$schema["u_date"] = createSchemaDate("u_date");
	$schema["u_reqdate"] = createSchemaDate("u_reqdate");
	$schema["u_todepartment"] = createSchemaUpper("u_todepartment");
	$schema["u_fromdepartment"] = createSchemaUpper("u_fromdepartment");
	$schema["u_itemdesc"] = createSchemaUpper("u_itemdesc");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");
	$schema["u_verified"] = createSchemaUpper("u_verified");
	$schema["u_showdetails"] = createSchema("u_showdetails");

	$objrs = new recordset(null,$objConnection);
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		if ($userdepartment!="" && $page->getitemstring("u_doctype")!="CTC") $schema["u_todepartment"]["attributes"] = "disabled";
	}

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_docdate");
	$objGrid->addcolumn("u_reqdate");
	$objGrid->addcolumn("docno");
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":
			$objGrid->addcolumn("u_fromdepartment");
			$objGrid->addcolumn("u_fromdepartmentname");
			break;
		case "PR":
			$objGrid->addcolumn("u_suppname");
			break;
		case "TFOUT":
		case "GI":
			$objGrid->addcolumn("u_todepartment");
			$objGrid->addcolumn("u_todepartmentname");
			break;
	}			
	$objGrid->addcolumn("u_reqtype","u_reqtypedesc");
	$objGrid->addcolumn("u_itemcode");
	$objGrid->addcolumn("u_itemdesc");
	$objGrid->addcolumn("u_instock");
	$objGrid->addcolumn("u_quantity");
	$objGrid->addcolumn("u_uom");
	$objGrid->addcolumn("u_remarks");
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":
			$objGrid->addcolumn("u_todepartment");
			$objGrid->addcolumn("u_todepartmentname");
			break;
		case "TFOUT":
		case "GI":
			$objGrid->addcolumn("u_fromdepartment");
			$objGrid->addcolumn("u_fromdepartmentname");
			break;
	}			
	$objGrid->addcolumn("docstatus","u_docstatusname");
	$objGrid->columntitle("u_docdate","Date");
	$objGrid->columntitle("u_reqdate","Req Date");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_suppname","Supplier");
	$objGrid->columntitle("u_reqtype","Type");
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":
			$objGrid->columntitle("u_todepartment","Transfering Section");
			$objGrid->columntitle("u_todepartmentname","Transfering Section");
			break;
		case "TFOUT":
		case "GI":
			$objGrid->columntitle("u_todepartment","To Section");
			$objGrid->columntitle("u_todepartmentname","To Section");
			break;
	}			
	$objGrid->columntitle("u_fromdepartment","Requesting Section");
	$objGrid->columntitle("u_fromdepartmentname","Requesting Section");
	$objGrid->columntitle("u_itemcode","Item Code");
	$objGrid->columntitle("u_itemdesc","Item Description");
	$objGrid->columntitle("u_instock","In-Stock");
	$objGrid->columntitle("u_quantity","Qty");
	$objGrid->columntitle("u_uom","Unit");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("u_docdate",8);
	$objGrid->columnwidth("u_reqdate",8);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_suppname",25);
	$objGrid->columnwidth("u_todepartment",18);
	$objGrid->columnwidth("u_todepartmentname",18);
	$objGrid->columnwidth("u_fromdepartment",18);
	$objGrid->columnwidth("u_fromdepartmentname",18);
	$objGrid->columnwidth("u_reqtype",10);
	$objGrid->columnwidth("u_itemcode",10);
	$objGrid->columnwidth("u_itemdesc",30);
	$objGrid->columnwidth("u_instock",9);
	$objGrid->columnwidth("u_quantity",7);
	$objGrid->columnwidth("u_uom",5);
	$objGrid->columnwidth("u_remarks",20);
	$objGrid->columnwidth("docstatus",9);
	$objGrid->columnalignment("u_instock","right");
	$objGrid->columnalignment("u_quantity","right");
	$objGrid->columnsortable("u_docdate",true);
	$objGrid->columnsortable("u_reqdate",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_suppname",true);
	$objGrid->columnsortable("u_todepartmentname",true);
	$objGrid->columnsortable("u_fromdepartmentname",true);
	$objGrid->columnsortable("u_itemcode",true);
	$objGrid->columnsortable("u_itemdesc",true);
	$objGrid->columnsortable("docstatus",true);
//	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	//$objGrid->columnlnkbtn("u_refno","OpenLnkBtnRefNos()");
	$objGrid->columnvisibility("u_reqdate",false);
	$objGrid->columnvisibility("u_todepartment",false);
	$objGrid->columnvisibility("u_todepartmentname",true);
	$objGrid->columnvisibility("u_fromdepartment",false);
	$objGrid->columnvisibility("u_fromdepartmentname",false);
	$objGrid->columnvisibility("u_itemcode",false);
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
				case "u_docdate":
					//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
					if ($groupby!=$objGrid->getitemstring($row,"docno")) {
						$groupby = $objGrid->getitemstring($row,"docno");
						$isgroup = false;
					} else {
						$isgroup = true;
						$label = "";
					}	
					break;
				case "u_reqdate":
				//case "u_reqtype":
				case "docno":
				case "u_remarks":
				case "u_fromdepartmentname":
				case "u_todepartmentname":
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
		$page->setitem("u_showdetails",1);
		$page->setitem("u_todepartment",$userdepartment);
		if ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") {
			//$page->setitem("docstatus","");
			$page->setitem("u_verified",0);
		}
	}
	
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":
			$objGrid->columnvisibility("u_fromdepartmentname",true);
			$objGrid->columnvisibility("u_reqdate",true);
			$objGrid->columnvisibility("u_duetime",true);
			$objGrid->columnvisibility("u_openqty",true);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hismedsupstockrequests()");
			break;
		case "PR":
			$objGrid->columnvisibility("u_reqtype",false);
			$objGrid->columnvisibility("u_instock",false);
			$objGrid->columnvisibility("u_suppname",true);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hismedsupprs()");
			break;
		case "TFOUT":
			$objGrid->columnvisibility("u_reqtype",false);
			$objGrid->columnvisibility("u_instock",false);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hismedsupstocktfs()");
			break;
		case "GI":
			$objGrid->columnvisibility("u_reqtype",false);
			$objGrid->columnvisibility("u_instock",false);
			$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hismedsupstocks()");
			break;
	}

	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY":
		case "CSR":
			$objGrid->columnvisibility("u_reqdate",false);
			$objGrid->columnvisibility("u_duetime",false);
		
			break;
	}
	
	$showDetailsExp = "";
	if ($page->getitemstring("u_showdetails")=="0") {
		$objGrid->columnvisibility("u_reqtype",false);
		$objGrid->columnvisibility("u_itemcode",false);
		$objGrid->columnvisibility("u_itemdesc",false);
		$objGrid->columnvisibility("u_instock",false);
		$objGrid->columnvisibility("u_quantity",false);
		$objGrid->columnvisibility("u_uom",false);
		$showDetailsExp = " GROUP BY A.DOCNO";
	}
	
	//var_dump($httpVars['df_u_fromdepartment']);
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.u_docdate, A.u_requesttime, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_TODEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	//var_dump($page->getitemstring("u_nursed"));
	$objrs->setdebug();
	switch ($page->getitemstring("u_doctype")) {
		case "INREQ":		
			$filterExp = "";
			$filterExp = genSQLFilterDate("A.U_DOCDATE",$filterExp,$httpVars['df_u_date']);
			$filterExp = genSQLFilterDate("A.U_REQDATE",$filterExp,$httpVars['df_u_reqdate']);
			$filterExp = genSQLFilterString("A.U_TODEPARTMENT",$filterExp,$httpVars['df_u_todepartment']);
			$filterExp = genSQLFilterString("A.U_FROMDEPARTMENT",$filterExp,$httpVars['df_u_fromdepartment']);
			$filterExp = genSQLFilterString("E.U_ITEMDESC",$filterExp,$httpVars['df_u_itemdesc'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$objrs->queryopenext("select A.DOCNO, A.U_DOCDATE, A.U_REQDATE, E.U_REQTYPE, B.NAME AS U_TODEPARTMENTNAME, D.NAME AS U_FROMDEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_INSTOCK, E.U_QUANTITY, E.U_UOM, A.U_REMARKS, A.DOCSTATUS from U_HISMEDSUPSTOCKREQUESTS A, U_HISMEDSUPSTOCKREQUESTITEMS E, U_HISSECTIONS B, U_HISITEMS C, U_HISSECTIONS D WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND E.U_LINESTATUS='' AND D.CODE=A.U_FROMDEPARTMENT AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_TODEPARTMENT AND B.U_TYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.DOCSTATUS NOT IN ('CN') $filterExp $showDetailsExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "PR":		
			$filterExp = "";
			$filterExp = genSQLFilterDate("A.U_DOCDATE",$filterExp,$httpVars['df_u_date']);
			$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_todepartment']);
			$filterExp = genSQLFilterString("E.U_ITEMDESC",$filterExp,$httpVars['df_u_itemdesc'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$objrs->queryopenext("select A.DOCNO, A.U_DOCDATE, A.U_SUPPNAME, B.NAME AS U_DEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_QUANTITY, E.U_UOM, A.U_REMARKS, A.DOCSTATUS from U_HISMEDSUPPRS A, U_HISMEDSUPPRITEMS E, U_HISSECTIONS B, U_HISITEMS C WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND A.U_TRXTYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp $showDetailsExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "TFOUT":		
			$filterExp = "";
			$filterExp = genSQLFilterDate("A.U_TFDATE",$filterExp,$httpVars['df_u_date']);
			//$filterExp = genSQLFilterString("A.U_TODEPARTMENT",$filterExp,$httpVars['df_u_todepartment']);
			$filterExp = genSQLFilterString("A.U_FROMDEPARTMENT",$filterExp,$httpVars['df_u_todepartment']);
			$filterExp = genSQLFilterString("E.U_ITEMDESC",$filterExp,$httpVars['df_u_itemdesc'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$objrs->queryopenext("select A.DOCNO, A.U_TFDATE, B.NAME AS U_TODEPARTMENTNAME, D.NAME AS U_FROMDEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_QUANTITY, E.U_UOM, A.U_REMARKS, A.DOCSTATUS from U_HISMEDSUPSTOCKTFS A, U_HISMEDSUPSTOCKTFITEMS E, U_HISSECTIONS B, U_HISITEMS C, U_HISSECTIONS D WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND D.CODE=A.U_FROMDEPARTMENT AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_TODEPARTMENT AND A.U_TRXTYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp $showDetailsExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
		case "GI":		
			$filterExp = "";
			$filterExp = genSQLFilterDate("A.U_DOCDATE",$filterExp,$httpVars['df_u_date']);
		//	$filterExp = genSQLFilterString("A.U_TODEPARTMENT",$filterExp,$httpVars['df_u_todepartment']);
			$filterExp = genSQLFilterString("A.U_FROMDEPARTMENT",$filterExp,$httpVars['df_u_toepartment']);
			$filterExp = genSQLFilterString("E.U_ITEMDESC",$filterExp,$httpVars['df_u_itemdesc'],null,null,true);
			$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$objrs->queryopenext("select A.DOCNO, A.U_DOCDATE, IFNULL(B.WAREHOUSENAME,'Multiple Sections') AS U_TODEPARTMENTNAME, D.WAREHOUSENAME AS U_FROMDEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_QUANTITY, E.U_UOM, A.U_REMARKS, A.DOCSTATUS from U_HISMEDSUPSTOCKS A INNER JOIN U_HISMEDSUPSTOCKITEMS E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID INNER JOIN U_HISITEMS C ON C.CODE=E.U_ITEMCODE INNER JOIN WAREHOUSES D ON D.WAREHOUSE=A.U_FROMDEPARTMENT LEFT JOIN WAREHOUSES B ON B.WAREHOUSE=A.U_TODEPARTMENT WHERE A.U_TRXTYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp $showDetailsExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			break;
	}		
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		switch ($page->getitemstring("u_doctype")) {
			case "INREQ":		
				$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_DOCDATE"]));
				$objGrid->setitem(null,"u_reqdate",formatDateToHttp($objrs->fields["U_REQDATE"]));
				$objGrid->setitem(null,"u_instock",formatNumericQuantity($objrs->fields["U_INSTOCK"]));
		
				$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
				$objGrid->setitem(null,"u_fromdepartmentname",$objrs->fields["U_FROMDEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_todepartmentname",$objrs->fields["U_TODEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
				$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
				$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
				$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["U_QUANTITY"]));
				$objGrid->setitem(null,"u_uom",$objrs->fields["U_UOM"]);
				$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
				$objGrid->setitem(null,"u_reqtype",$objrs->fields["U_REQTYPE"]);
				$objGrid->setitem(null,"u_reqtypedesc",iif($objrs->fields["U_REQTYPE"]=="TF","For Stock",iif($objrs->fields["U_REQTYPE"]=="GI","For Expense","Unknown")));
				$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
				$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		
				break;
			case "PR":		
				$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_DOCDATE"]));
		
				$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
				$objGrid->setitem(null,"u_suppname",$objrs->fields["U_SUPPNAME"]);
				$objGrid->setitem(null,"u_fromdepartmentname",$objrs->fields["U_DEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_todepartmentname",$objrs->fields["U_DEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
				$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
				$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
				$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["U_QUANTITY"]));
				$objGrid->setitem(null,"u_uom",$objrs->fields["U_UOM"]);
				$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
				$objGrid->setitem(null,"u_reqtype",$objrs->fields["U_REQTYPE"]);
				$objGrid->setitem(null,"u_reqtypedesc",iif($objrs->fields["U_REQTYPE"]=="TF","For Stock",iif($objrs->fields["U_REQTYPE"]=="GI","For Expense","Unknown")));
				$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
				$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		
				break;
			case "TFOUT":		
				$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_TFDATE"]));
		
				$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
				$objGrid->setitem(null,"u_fromdepartmentname",$objrs->fields["U_FROMDEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_todepartmentname",$objrs->fields["U_TODEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
				$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
				$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
				$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["U_QUANTITY"]));
				$objGrid->setitem(null,"u_uom",$objrs->fields["U_UOM"]);
				$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
				$objGrid->setitem(null,"u_reqtype",$objrs->fields["U_REQTYPE"]);
				$objGrid->setitem(null,"u_reqtypedesc",iif($objrs->fields["U_REQTYPE"]=="TF","For Stock",iif($objrs->fields["U_REQTYPE"]=="GI","For Expense","Unknown")));
				$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
				$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		
				break;
			case "GI":		
				$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_DOCDATE"]));
		
				$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
				$objGrid->setitem(null,"u_fromdepartmentname",$objrs->fields["U_FROMDEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_todepartmentname",$objrs->fields["U_TODEPARTMENTNAME"]);
				$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
				$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
				$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
				$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["U_QUANTITY"]));
				$objGrid->setitem(null,"u_uom",$objrs->fields["U_UOM"]);
				$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
				$objGrid->setitem(null,"u_reqtype",$objrs->fields["U_REQTYPE"]);
				$objGrid->setitem(null,"u_reqtypedesc",iif($objrs->fields["U_REQTYPE"]=="TF","For Stock",iif($objrs->fields["U_REQTYPE"]=="GI","For Expense","Unknown")));
				$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
				$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		
				break;
		}		
		if (!$page->paging_fetch()) break;
	}	
	
	$page->resize->addgrid("T1",20,190,false);
	
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
<SCRIPT language=JavaScript src="<?php autocaching('js/formobjs.js'); ?>" type=text/javascript></SCRIPT>
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
		hideInput("u_fromdepartment");
		if (getInput("u_doctype")=="INREQ") {
			showInput("u_fromdepartment");
		}	
		if (getInput("u_doctype")=="TFOUT" || getInput("u_doctype")=="GI") {
			hideInput("u_reqdate");
		}	
		focusInput("u_itemdesc");
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
							var targetObjectId = '';
							if (getTableInput("T1","u_reqtype",p_rowIdx)=="TF") {
								OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hismedsupstocktfs' + '' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
							} else {
								OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hismedsupstocks' + '' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
							}	
						}	
						break;
					case "PR":
						setKey("keys",getTableKey("T1","keys",p_rowIdx));
						return formView(null,"./UDO.php?&objectcode=U_HISMEDSUPPRS");
						break;	
					case "TFOUT":
						setKey("keys",getTableKey("T1","keys",p_rowIdx));
						return formView(null,"./UDO.php?&objectcode=U_HISMEDSUPSTOCKTFS");
						break;	
					case "GI":
						setKey("keys",getTableKey("T1","keys",p_rowIdx));
						return formView(null,"./UDO.php?&objectcode=U_HISMEDSUPSTOCKS");
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
			case "u_todepartment":
			case "u_fromdepartment":
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
			case "u_itemdesc":
			case "u_date":
			case "u_reqdate":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (table) {
			default:
				switch (column) {
					case "u_doctype":
						clearTable("T1");
						formPageReset(); 
						if (getInput("u_doctype")=="TFOUT" || getInput("u_doctype")=="GI") {
							setInput("docstatus","");
						} else if (getInput("u_doctype")=="PR") {
							setInput("docstatus","D");
						} else {
							setInput("docstatus","O");
						}
						formSearchNow();
						break;	
					case "u_verified":
					case "u_showdetails":
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
			case "df_u_todepartment":
			case "df_u_fromdepartment":
			case "df_u_itemdesc":
			case "df_docstatus":
			case "df_u_doctype":
			case "df_u_date":
			case "df_u_reqdate":
			case "df_u_showdetails":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_todepartment");
			inputs.push("u_fromdepartment");
			inputs.push("u_itemdesc");
			inputs.push("u_trxtype");
			inputs.push("docstatus");
			inputs.push("u_doctype");
			inputs.push("u_date");
			inputs.push("u_reqdate");
			inputs.push("u_showdetails");
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
			case "u_itemdesc":
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
	
	function OpenLnkBtnu_hismedsupstockrequests(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismedsupstockrequests' + '' + '&targetId=' + targetId ,targetId);
		
	}

	function OpenLnkBtnu_hismedsupprs(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismedsupprs' + '' + '&targetId=' + targetId ,targetId);
		
	}

	function OpenLnkBtnu_hismedsupstocktfs(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismedsupstocktfs' + '' + '&targetId=' + targetId ,targetId);
		
	}

	function OpenLnkBtnu_hismedsupstocks(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismedsupstocks' + '' + '&targetId=' + targetId ,targetId);
		
	}

	function OpenLnkBtnPriceList(targetId) {
		OpenLnkBtn(800,600,'./udp.php?objectcode=u_hispricelist&df_u_trxtype=' + getInput("u_trxtype") + '&targetId=' + targetId ,targetId);
	}
	
	function u_newGPSHIS() {
		switch (getInput("u_doctype")) {
			case "TFOUT":
				setKey("keys","");
				return formView(null,"./UDO.php?&objectcode=U_HISMEDSUPSTOCKTFS");
				break;
			case "PR":
				setKey("keys","");
				return formView(null,"./UDO.php?&objectcode=U_HISMEDSUPPRS");
				break;
			case "GI":
				setKey("keys","");
				return formView(null,"./UDO.php?&objectcode=U_HISMEDSUPSTOCKS");
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
	  <td width="168"><label <?php genCaptionHtml($schema["u_doctype"],"") ?>>Type</label></td>
	  <td  align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"INREQ") ?> /><label class="lblobjs">Incoming Requests</b></label>&nbsp;&nbsp;&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"PR") ?> /><label class="lblobjs">Purchase Requests</b></label>&nbsp;&nbsp;&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"TFOUT") ?> /><label class="lblobjs">Transfer Outs</b></label>&nbsp;&nbsp;&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_doctype"],"GI") ?> /><label class="lblobjs">Issuances</b></label></td>
	  <td ><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="148">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:108px") ?> ></select></td>
	</tr>
	<tr>
	   <td ><label <?php genCaptionHtml($schema["u_todepartment"],"") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_todepartment"],array("loadudflinktable",iif($page->getitemstring("u_doctype")!="CTC","u_hissections:code:name:u_type in ('".$page->getitemstring("u_trxtype")."')","u_hissections:code:name"),":[All]")) ?> ></select></td>
	  <td ><label <?php genCaptionHtml($schema["u_date"],"") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_date"]) ?> /></td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_itemdesc"],"") ?>>Item Description</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_itemdesc"]) ?> /></td>
	  <td ><label <?php genCaptionHtml($schema["u_reqdate"],"") ?>>Date Required</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_reqdate"]) ?> /></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php genInputRadioHtml($schema["u_showdetails"],"1") ?> /><label class="lblobjs">Show Items</b></label></td>
	  <td ><label <?php genCaptionHtml($schema["u_fromdepartment"],"") ?>>Requested From</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_fromdepartment"],array("loadudflinktable","u_hissections:code:name",":[All]"),null,null,null,"width:108px") ?> ></select></td>
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
