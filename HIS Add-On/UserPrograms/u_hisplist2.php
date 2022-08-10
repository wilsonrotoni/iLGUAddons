<?php
	$progid = "u_hisplist";

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
	
	unset($enumdocstatus["D"],$enumdocstatus["CN"],$enumdocstatus["O"],$enumdocstatus["C"]);
	$enumdocstatus["Active"] = "Active";
	$enumdocstatus["Discharged"] = "Discharged";
	$enumdocstatus["Admitted"] = "Admitted";
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISPLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisplist";
	$page->formid = "./UDO.php?&objectcode=U_HISIPS";
	$page->objectname = "In-Patient";
	
	$schema["u_reftype"] = createSchemaUpper("u_reftype");
	$schema["u_refno"] = createSchemaUpper("u_refno");
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");

	$objrs = new recordset(null,$objConnection);
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		if ($page->getitemstring("u_trxtype")=="NURSE" && $userdepartment!="NSG-NICU") {
			if ($userdepartment!="") $schema["u_department"]["attributes"] = "disabled";
		}	
	}

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("indicator");
	$objGrid->addcolumn("u_expires");
	$objGrid->addcolumn("u_allergies");
	$objGrid->addcolumn("u_confidential");
	$objGrid->addcolumn("u_predischarge");
	$objGrid->addcolumn("u_req");
	$objGrid->addcolumn("u_chrg");
	$objGrid->addcolumn("u_cm");
	$objGrid->addcolumn("u_predisc");
	$objGrid->addcolumn("u_tf");
	$objGrid->addcolumn("u_rmtf");
	$objGrid->addcolumn("u_patientid");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_bedno");
	$objGrid->addcolumn("u_age_y");
	$objGrid->addcolumn("u_diettype");
	$objGrid->addcolumn("u_startdate");
	$objGrid->addcolumn("u_starttime");
	$objGrid->addcolumn("u_enddate");
	$objGrid->addcolumn("u_endtime");
	$objGrid->addcolumn("u_doctorname");
	$objGrid->addcolumn("u_roomdesc");
	$objGrid->addcolumn("u_paymentterm");
	$objGrid->addcolumn("u_prepaid");
	$objGrid->addcolumn("u_departmentname");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_department");
	$objGrid->addcolumn("docstatus");
	$objGrid->addcolumn("u_billing");
	$objGrid->addcolumn("u_mgh");
	$objGrid->addcolumn("u_billno");
	$objGrid->addcolumn("u_nursed");
	$objGrid->addcolumn("u_expired");
	$objGrid->addcolumn("u_roomno");
	$objGrid->addcolumn("u_pricelist");
	$objGrid->columntitle("indicator","");
	$objGrid->columntitle("u_expires","");
	$objGrid->columntitle("u_allergies","");
	$objGrid->columntitle("u_confidential","");
	$objGrid->columntitle("u_predischarge","");
	$objGrid->columntitle("u_startdate","Date");
	$objGrid->columntitle("u_starttime","Time");
	$objGrid->columntitle("u_enddate","Discharged Date");
	$objGrid->columntitle("u_endtime","Time");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_departmentname","Section");
	$objGrid->columntitle("u_bedno","Room/Bed");
	$objGrid->columntitle("u_doctorname","Attending Physician");
	$objGrid->columntitle("u_roomdesc","Room Description");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_paymentterm","Payment Term");
	$objGrid->columntitle("u_prepaid","Term");
	$objGrid->columntitle("u_age_y","Age");
	$objGrid->columntitle("u_diettype","Diet");
	$objGrid->columntitle("u_req","");
	$objGrid->columntitle("u_chrg","");
	$objGrid->columntitle("u_cm","");
	$objGrid->columntitle("u_predisc","");
	$objGrid->columntitle("u_tf","");
	$objGrid->columntitle("u_rmtf","");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("u_expires",-1);
	$objGrid->columnwidth("u_allergies",-1);
	$objGrid->columnwidth("u_confidential",-1);
	$objGrid->columnwidth("u_predischarge",-1);
	$objGrid->columnwidth("u_startdate",8);
	$objGrid->columnwidth("u_starttime",5);
	$objGrid->columnwidth("u_enddate",14);
	$objGrid->columnwidth("u_endtime",5);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_departmentname",18);
	$objGrid->columnwidth("u_bedno",9);
	$objGrid->columnwidth("u_doctorname",22);
	$objGrid->columnwidth("u_roomdesc",22);
	$objGrid->columnwidth("u_patientid",9);
	$objGrid->columnwidth("u_patientname",30);
	$objGrid->columnwidth("u_paymentterm",13);
	$objGrid->columnwidth("u_prepaid",10);
	$objGrid->columnwidth("u_age_y",2);
	$objGrid->columnwidth("u_diettype",17);
	$objGrid->columnwidth("docstatus",9);
	//$objGrid->columnwidth("u_req",6);
	//$objGrid->columnwidth("u_chrg",5);
	//$objGrid->columnwidth("u_cm",5);
	$objGrid->columnwidth("u_req",1);
	$objGrid->columnwidth("u_chrg",1);
	$objGrid->columnwidth("u_cm",1);
	$objGrid->columnwidth("u_predisc",2);
	$objGrid->columnwidth("u_tf",4);
	$objGrid->columnwidth("u_rmtf",12);
	$objGrid->columnsortable("u_startdate",true);
	$objGrid->columnsortable("u_starttime",true);
	$objGrid->columnsortable("u_enddate",true);
	$objGrid->columnsortable("u_endtime",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_departmentname",true);
	$objGrid->columnsortable("u_bedno",true);
	$objGrid->columnsortable("u_roomdesc",true);
	$objGrid->columnsortable("u_patientid",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_paymentterm",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columninput("u_req","type","link");
	$objGrid->columninput("u_req","caption","Ca");
	$objGrid->columninput("u_req","tooltip","Create a Request/Cash Slip");
	$objGrid->columninput("u_chrg","type","link");
	$objGrid->columninput("u_chrg","caption","Ch");
	$objGrid->columninput("u_chrg","tooltip","Create a Charge/Render Slip");
	$objGrid->columninput("u_cm","type","link");
	$objGrid->columninput("u_cm","caption","Cr");
	$objGrid->columninput("u_cm","tooltip","Create a Return/Credit Slip");
	$objGrid->columninput("u_predisc","type","link");
	$objGrid->columninput("u_predisc","caption","PD?");
	$objGrid->columninput("u_predisc","tooltip","Tag patient as pre-discharge");
	$objGrid->columninput("u_tf","type","link");
	$objGrid->columninput("u_tf","caption","Admit");
	$objGrid->columninput("u_tf","tooltip","Admit the patient");
	$objGrid->columninput("u_rmtf","type","link");
	$objGrid->columninput("u_rmtf","caption","Room Transfer");
	$objGrid->columninput("u_rmtf","tooltip","Transfer the patient to another room");
	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	$objGrid->columnlnkbtn("u_req","OpenLnkBtnRequests()","Show List of Request/Cash Slips");
	$objGrid->columnlnkbtn("u_chrg","OpenLnkBtnCharges()","Show List of Charge/Render Slips");
	$objGrid->columnlnkbtn("u_cm","OpenLnkBtnCredits()","Show List of Return/Credit Slips");
	$objGrid->columnvisibility("u_department",false);
	$objGrid->columnvisibility("u_billing",false);
	$objGrid->columnvisibility("u_prepaid",false);
	$objGrid->columnvisibility("u_mgh",false);
	$objGrid->columnvisibility("u_billno",false);
	$objGrid->columnvisibility("u_tf",false);
	$objGrid->columnvisibility("u_rmtf",false);
	$objGrid->columnvisibility("u_predisc",false);
	$objGrid->columnvisibility("u_nursed",false);
	$objGrid->columnvisibility("u_expired",false);
	$objGrid->columnvisibility("u_roomno",false);
	$objGrid->columnvisibility("u_doctorname",false);
	$objGrid->columnvisibility("u_pricelist",false);
	$objGrid->columnvisibility("u_diettype",false);
	$objGrid->columnvisibility("u_prepaid",false);
	$objGrid->columnvisibility("u_enddate",false);
	$objGrid->columnvisibility("u_endtime",false);
	$objGrid->automanagecolumnwidth = false;
	
	$objGridRequests = new grid("T10");
	$objGridRequests->addcolumn(createSchemaDate("docdate"));
	$objGridRequests->addcolumn("doctime");
	$objGridRequests->addcolumn(createSchemaDate("duedate"));
	$objGridRequests->addcolumn("duetime");
	$objGridRequests->addcolumn("docno");
	$objGridRequests->addcolumn("departmentname");
	$objGridRequests->addcolumn("itemdesc");
	$objGridRequests->addcolumn("quantity");
	$objGridRequests->addcolumn("uom");
	$objGridRequests->addcolumn("chrgqty");
	$objGridRequests->addcolumn("rtqty");
	$objGridRequests->addcolumn("docstatus");	
	$objGridRequests->addcolumn("trxtype");	
	$objGridRequests->columntitle("docdate","Date");	
	$objGridRequests->columntitle("doctime","Time");	
	$objGridRequests->columntitle("duedate","Due Date");	
	$objGridRequests->columntitle("duetime","Time");	
	$objGridRequests->columntitle("docno","CA No");	
	$objGridRequests->columntitle("departmentname","Charge/Render Section");	
	$objGridRequests->columntitle("itemdesc","Item Description");	
	$objGridRequests->columntitle("quantity","Qty");	
	$objGridRequests->columntitle("uom","Unit");	
	$objGridRequests->columntitle("chrgqty","Chrg/Ren Qty");	
	$objGridRequests->columntitle("rtqty","Rtn/Cr Qty");	
	$objGridRequests->columntitle("docstatus","Status");	
	$objGridRequests->columnwidth("docdate",11);	
	$objGridRequests->columnwidth("doctime",4);	
	$objGridRequests->columnwidth("duedate",11);	
	$objGridRequests->columnwidth("duetime",4);	
	$objGridRequests->columnwidth("docno",10);	
	$objGridRequests->columnwidth("departmentname",19);	
	$objGridRequests->columnwidth("itemdesc",30);	
	$objGridRequests->columnwidth("quantity",7);	
	$objGridRequests->columnwidth("chrgqty",11);	
	$objGridRequests->columnwidth("rtqty",9);	
	$objGridRequests->columnwidth("uom",5);	
	$objGridRequests->columnwidth("docstatus",7);	
	$objGridRequests->columnalignment("quantity","right");
	$objGridRequests->columnalignment("chrgqty","right");
	$objGridRequests->columnalignment("rtqty","right");
	$objGridRequests->columncfl("docdate","Calendar");
	$objGridRequests->columncfl("duedate","Calendar");
	$objGridRequests->columncfl("itemdesc","OpenCFLfs()");
	$objGridRequests->columndataentry("doctime","type","label");
	$objGridRequests->columndataentry("duetime","type","label");
	$objGridRequests->columndataentry("docno","type","label");
	$objGridRequests->columndataentry("docstatus","type","label");
	$objGridRequests->columndataentry("quantity","type","label");
	$objGridRequests->columndataentry("rtqty","type","label");
	$objGridRequests->columndataentry("uom","type","label");
	$objGridRequests->columndataentry("departmentname","type","select");
	$objGridRequests->columndataentry("departmentname","options",array("loadudflinktable","u_hissections:code:name:u_type <> ''",":[All]"));
	$objGridRequests->columnlnkbtn("docno","OpenLnkBtnRequestDocNo()","Show Request/Cash Slip");
	$objGridRequests->columnvisibility("trxtype",false);
	$objGridRequests->automanagecolumnwidth = false;
	$objGridRequests->width=959;
	$objGridRequests->height = 200;
	$objGridRequests->showfilterbar = true;
	$objGridRequests->filterbaraction = "showRequests()";
	$objGridRequests->filterbartable = "T110";

	$objGridCharges = new grid("T11");
	$objGridCharges->addcolumn(createSchemaDate("docdate"));
	$objGridCharges->addcolumn("doctime");
	$objGridCharges->addcolumn("docno");
	$objGridCharges->addcolumn("departmentname");
	$objGridCharges->addcolumn("itemdesc");
	$objGridCharges->addcolumn("quantity");
	$objGridCharges->addcolumn("uom");
	$objGridCharges->addcolumn("rtqty");
	$objGridCharges->addcolumn("docstatus");	
	$objGridCharges->addcolumn("trxtype");	
	$objGridCharges->columntitle("docdate","Date");	
	$objGridCharges->columntitle("doctime","Time");	
	$objGridCharges->columntitle("docno","CH No");	
	$objGridCharges->columntitle("departmentname","Charge/Render Section");	
	$objGridCharges->columntitle("itemdesc","Item Description");	
	$objGridCharges->columntitle("quantity","Qty");	
	$objGridCharges->columntitle("uom","Unit");	
	$objGridCharges->columntitle("rtqty","Rtn/Cr Qty");	
	$objGridCharges->columntitle("docstatus","Status");	
	$objGridCharges->columnwidth("docdate",11);	
	$objGridCharges->columnwidth("doctime",4);	
	$objGridCharges->columnwidth("docno",10);	
	$objGridCharges->columnwidth("departmentname",19);	
	$objGridCharges->columnwidth("itemdesc",30);	
	$objGridCharges->columnwidth("quantity",7);	
	$objGridCharges->columnwidth("rtqty",9);	
	$objGridCharges->columnwidth("uom",5);	
	$objGridCharges->columnwidth("docstatus",7);	
	$objGridCharges->columnalignment("quantity","right");
	$objGridCharges->columnalignment("rtqty","right");
	$objGridCharges->columncfl("docdate","Calendar");
	$objGridCharges->columncfl("itemdesc","OpenCFLfs()");
	$objGridCharges->columndataentry("doctime","type","label");
	$objGridCharges->columndataentry("docno","type","label");
	$objGridCharges->columndataentry("docstatus","type","label");
	$objGridCharges->columndataentry("quantity","type","label");
	$objGridCharges->columndataentry("rtqty","type","label");
	$objGridCharges->columndataentry("uom","type","label");
	$objGridCharges->columndataentry("departmentname","type","select");
	$objGridCharges->columndataentry("departmentname","options",array("loadudflinktable","u_hissections:code:name:u_type <> ''",":[All]"));
	$objGridCharges->columnlnkbtn("docno","OpenLnkBtnChargeDocNo()","Show Charge/Render Slip");
	$objGridCharges->columnvisibility("trxtype",false);
	$objGridCharges->automanagecolumnwidth = false;
	$objGridCharges->width=959;
	$objGridCharges->height = 200;
	$objGridCharges->showfilterbar = true;
	$objGridCharges->filterbaraction = "showCharges()";
	$objGridCharges->filterbartable = "T111";

	$objGridCredits = new grid("T12");
	$objGridCredits->addcolumn("docdate");
	$objGridCredits->addcolumn("doctime");
	$objGridCredits->addcolumn("docno");
	$objGridCredits->addcolumn("departmentname");
	$objGridCredits->addcolumn("itemdesc");
	$objGridCredits->addcolumn("quantity");
	$objGridCredits->addcolumn("uom");
	$objGridCredits->addcolumn("trxtype");	
	$objGridCredits->columntitle("docdate","Date");	
	$objGridCredits->columntitle("doctime","Time");	
	$objGridCredits->columntitle("docno","CR No");	
	$objGridCredits->columntitle("departmentname","Return/Credit Section");	
	$objGridCredits->columntitle("itemdesc","Item Description");	
	$objGridCredits->columntitle("quantity","Qty");	
	$objGridCredits->columntitle("uom","Unit");	
	$objGridCredits->columnwidth("docdate",9);	
	$objGridCredits->columnwidth("doctime",4);	
	$objGridCredits->columnwidth("docno",10);	
	$objGridCredits->columnwidth("departmentname",19);	
	$objGridCredits->columnwidth("itemdesc",30);	
	$objGridCredits->columnwidth("quantity",7);	
	$objGridCredits->columnwidth("uom",7);	
	$objGridCredits->columnalignment("quantity","right");
	$objGridCredits->columnalignment("rtqty","right");
	$objGridCredits->columnlnkbtn("docno","OpenLnkBtnCreditDocNo()","Show Return/Credit Slip");
	$objGridCredits->columnvisibility("trxtype",false);
	$objGridCredits->automanagecolumnwidth = false;
	$objGridCredits->width=799;
	$objGridCredits->height = 100;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "docno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "indicator":
				switch ($label) {
					case "Active": $style="background-color:green";break;
					case "MGH": $style="background-color:yellow";break;
					//case "Expired": $style="background-color:gray";break;
					case "XMGH": $style="background-color:lime";break;
					case "PAID": $style="background-color:orange";break;
				}
				$label="&nbsp";	
				break;
			case "u_expires":
				if ($label=="1") $style="background-color:gray";
				$label="&nbsp";	
				break;
			case "u_allergies":
				if ($label=="1") $style="background-color:red";
				$label="&nbsp";	
				break;
			case "u_confidential":
				if ($label=="1") $style="background-color:black";
				$label="&nbsp";	
				break;
			case "u_predischarge":
				if ($label=="1") $style="background-color:orange";
				$label="&nbsp";	
				break;
			case "u_prepaid":
				switch ($label) {
					case "0": $label="Charge"; break;
					case "1": $label="Cash"; break;
					case "2": $label="Partial Pay"; break;
				}	
				break;
		}
		
	}
	
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","Active");
		switch ($page->getitemstring("u_trxtype")) {
			case "NURSE":
				$page->setitem("u_department",$userdepartment);
				$page->setitem("u_reftype","IP");
				break;
			case "OP":
				$page->setitem("u_department",$userdepartment);
				$page->setitem("u_reftype","OP");
				break;
			default:
				$page->setitem("u_reftype","IP");
				break;	
		}
		
	}

	switch ($page->getitemstring("u_trxtype")) {
		case "SPLROOM','ER":
		case "DIALYSIS":
		case "DIETARY":
		case "PULMONARY":
			if ($page->getitemstring("u_reftype")=="IP") {
				$objGrid->columnvisibility("u_diettype",true);
			}	
			break;
		case "NURSE":
			if ($page->getitemstring("u_reftype")=="IP" && $userdepartment!="NSG-NICU") {
				$schema["u_reftype"]["attributes"] = "disabled";
			}	
			$objGrid->columnvisibility("u_predisc",true);
			$objGrid->columnvisibility("u_diettype",true);
			$objGrid->columnvisibility("u_doctorname",true);
			$objGrid->columnvisibility("u_roomdesc",false);
	
			break;
		case "OP":
			break;
		case "BILLING":
			$objGrid->columnvisibility("u_prepaid",true);
			break;
		case "MEDREC":
			if ($page->getitemstring("docstatus")=="" || $page->getitemstring("docstatus")=="Discharged") {
				$objGrid->columnvisibility("u_enddate",true);
				$objGrid->columnvisibility("u_endtime",true);
				$objGrid->columnlnkbtn("u_enddate","showDischargedDetails()");
			}	
			break;
	}
	
	
	$filterExp = "";
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_u_refno']);
	$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
	$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterString("A.U_EXPIRED",$filterExp,$httpVars['df_u_expired']);
	if ($page->getitemstring("u_reftype")=="IP") {
		$filterExp = genSQLFilterString("A.U_MGH",$filterExp,$httpVars['df_u_mgh']);
	}	
	if ($page->getitemstring("u_trxtype")=="NURSE" && $userdepartment!="NSG-NICU") {
		$filterExp = genSQLFilterString("A.U_NURSED",$filterExp,1);
	}
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	//$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	
	if ($page->getitemstring("u_reftype")=="OP" && $page->getitemstring("u_trxtype")=="IP") {
		$objGrid->columnvisibility("u_tf",true);
	}
	if ($page->getitemstring("u_reftype")=="IP" && $page->getitemstring("u_trxtype")=="IP") {
		$objGrid->columnvisibility("u_rmtf",true);
	}
	
	//	$objrs->setdebug();
	if ($page->getitemstring("u_reftype")=="IP") {
		$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_ENDDATE, A.U_ENDTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_AGE_Y, C.NAME AS U_DIETTYPEDESC, A.U_PRICELIST, A.U_ROOMNO, A.U_BEDNO, A.U_ROOMDESC, A.U_DEPARTMENT, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_EXPIRED, A.U_MGH, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, A.U_UNTAGMGHREMARKS, A.U_ALLERGIES, A.U_CONFIDENTIAL, A.U_PREPAID, A.U_BILLNO, A.U_NURSED, A.U_REQCNT, A.U_CHRGCNT, A.U_CMCNT, D.NAME AS U_DOCTORNAME from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT LEFT OUTER JOIN U_HISDIETTYPES C ON C.CODE=A.U_DIETTYPE LEFT OUTER JOIN U_HISDOCTORS D ON D.CODE=A.U_DOCTORID WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch'  $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump($objrs->sqls);
	} else {
		$objGrid->columnvisibility("u_roomdesc",false);
		$objGrid->columnvisibility("u_bedno",false);
	
		$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_STARTTIME, A.U_ENDDATE, A.U_ENDTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_AGE_Y, A.U_PRICELIST, '' AS U_ROOMNO, A.U_DEPARTMENT, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_EXPIRED, 0 as U_MGH, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, A.U_ALLERGIES, A.U_CONFIDENTIAL, A.U_PREPAID, A.U_BILLNO, 0 AS NURSED,  A.U_REQCNT, A.U_CHRGCNT, A.U_CMCNT, D.NAME AS U_DOCTORNAME from U_HISOPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT LEFT OUTER JOIN U_HISDOCTORS D ON D.CODE=A.U_DOCTORID  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch'  $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	}	
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$indicator=$objrs->fields["DOCSTATUS"];
		//if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
		//else
		if ($objrs->fields["U_MGH"]==1 && $objrs->fields["U_DISCHARGED"]==0) $indicator="MGH";
		elseif ($objrs->fields["U_UNTAGMGHREMARKS"]!="" && $objrs->fields["U_DISCHARGED"]==0) $indicator="XMGH";
		
		$objGrid->setitem(null,"indicator",$indicator);
		$objGrid->setitem(null,"u_startdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
		$objGrid->setitem(null,"u_starttime",formatTimeToHttp($objrs->fields["U_STARTTIME"]));
		$objGrid->setitem(null,"u_enddate",formatDateToHttp($objrs->fields["U_ENDDATE"]));
		$objGrid->setitem(null,"u_endtime",formatTimeToHttp($objrs->fields["U_ENDTIME"]));
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_department",$objrs->fields["U_DEPARTMENT"]);
		$objGrid->setitem(null,"u_departmentname",$objrs->fields["U_DEPARTMENTNAME"]);
		$objGrid->setitem(null,"u_pricelist",$objrs->fields["U_PRICELIST"]);
		$objGrid->setitem(null,"u_roomno",$objrs->fields["U_ROOMNO"]);
		$objGrid->setitem(null,"u_bedno",$objrs->fields["U_BEDNO"]);
		$objGrid->setitem(null,"u_age_y",$objrs->fields["U_AGE_Y"]);
		$objGrid->setitem(null,"u_doctorname",$objrs->fields["U_DOCTORNAME"]);
		$objGrid->setitem(null,"u_roomdesc",$objrs->fields["U_ROOMDESC"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["U_PATIENTID"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["U_PATIENTNAME"]);
		$objGrid->setitem(null,"u_paymentterm",$objrs->fields["U_PAYMENTTERM"]);
		$objGrid->setitem(null,"u_prepaid",$objrs->fields["U_PREPAID"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_diettype",$objrs->fields["U_DIETTYPEDESC"]);
		$objGrid->setitem(null,"u_expires",$objrs->fields["U_EXPIRED"]);
		$objGrid->setitem(null,"u_allergies",$objrs->fields["U_ALLERGIES"]);
		$objGrid->setitem(null,"u_confidential",$objrs->fields["U_CONFIDENTIAL"]);
		$objGrid->setitem(null,"u_billing",$objrs->fields["U_BILLING"]);
		$objGrid->setitem(null,"u_prepaid",$objrs->fields["U_PREPAID"]);
		$objGrid->setitem(null,"u_mgh",$objrs->fields["U_MGH"]);
		$objGrid->setitem(null,"u_billno",$objrs->fields["U_BILLNO"]);
		$objGrid->setitem(null,"u_nursed",$objrs->fields["U_NURSED"]);
		$objGrid->setitem(null,"u_expired",$objrs->fields["U_EXPIRED"]);
		$objGrid->setitem(null,"u_req",iif($objrs->fields["U_REQCNT"]>0,"?",""));
		$objGrid->setitem(null,"u_chrg",iif($objrs->fields["U_CHRGCNT"]>0,"?",""));
		$objGrid->setitem(null,"u_cm",iif($objrs->fields["U_CMCNT"]>0,"?",""));
		if ($page->getitemstring("u_reftype")=="IP") {
			if ($objrs->fields["U_MGH"]==1 && ($objrs->fields["U_PREDISCHARGE"]==1 || ($objrs->fields["U_BILLING"]==0 && $objrs->fields["U_FORCEBILLING"]==0))) {
				$objGrid->setitem(null,"u_predischarge",1);
			} else {
				$objGrid->setitem(null,"u_predischarge",$objrs->fields["U_PREDISCHARGE"]);
			}	
		} else {
			if ($objrs->fields["U_PREDISCHARGE"]==1 || ($objrs->fields["U_BILLING"]==0 && $objrs->fields["U_FORCEBILLING"]==0)) {
				$objGrid->setitem(null,"u_predischarge",1);
			} else {
				$objGrid->setitem(null,"u_predischarge",$objrs->fields["U_PREDISCHARGE"]);
			}	
		}	
		$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		if (!$page->paging_fetch()) break;
	}	
	
	$page->resize->addgrid("T1",20,210,false);
	
	//$rptcols = 6; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
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

a.links {
text-decoration: none !important;

}
</style>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatients.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		hideInput("u_mgh");
		hideElementById("ocf_u_mgh_all",true);
		hideElementById("ocf_u_mgh_yes",true);
		hideElementById("ocf_u_mgh_no",true);
		if (getInput("u_reftype")=="IP") {
			showInput("u_mgh");
			showElementById("ocf_u_mgh_all",true);
			showElementById("ocf_u_mgh_yes",true);
			showElementById("ocf_u_mgh_no",true);
		}	
		focusInput("u_patientname");
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				if (getInput("u_reftype")=="IP") return formView(null,"./UDO.php?&objectcode=U_HISIPS");
				else return formView(null,"./UDO.php?&objectcode=U_HISOPS");
				//var targetObjectId = 'u_hispatients';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {	
			case "df_itemdescT111": 
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_hisitems where u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
				break;
		}
		return params;
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
		switch (table) {
			case "T110":
				switch (column) {
					case "departmentname":
						showRequests();
						break;
				}
				break;
			case "T111":
				switch (column) {
					case "departmentname":
						showCharges();
						break;
				}
				break;
			default:
				switch (column) {
					case "u_department":
					case "docstatus":
						clearTable("T1");
						formPageReset(); 
						break;	
				}
				break;
		}		
		return true;
	}	

	function onElementValidate(element,column,table,row) {
		switch (table) {
			case "T110":
				switch (column) {
					case "docdate":
					case "duedate":
						showRequests();
						break;
					case "itemdesc":
						if (getTableInput(table,column)!="") {
							var result = page.executeFormattedQuery("select name from u_hisitems where u_active=1 and name like '"+getTableInput(table,column)+"%'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setTableInput(table,"itemdesc",result.childNodes.item(0).getAttribute("name"));
									showRequests();
								} else {
									setTableInput(table,"itemdesc","");
									page.statusbar.showError("Invalid Item.");	
									return false;
								}
							} else {
								setTableInput(table,"itemdesc","");
								page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
								return false;
							}	
						} else showRequests();
	
						break;
				}
				break;
			case "T111":
				switch (column) {
					case "docdate":
						showCharges();
						break;
					case "itemdesc":
						if (getTableInput(table,column)!="") {
							var result = page.executeFormattedQuery("select name from u_hisitems where u_active=1 and name like '"+getTableInput(table,column)+"%'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setTableInput(table,"itemdesc",result.childNodes.item(0).getAttribute("name"));
									showCharges();
								} else {
									setTableInput(table,"itemdesc","");
									page.statusbar.showError("Invalid Item.");	
									return false;
								}
							} else {
								setTableInput(table,"itemdesc","");
								page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
								return false;
							}	
						} else showCharges();
	
						break;
				}
				break;
			default:
				switch (column) {
					case "page_vr_limit":
					case "u_patientname":
					case "u_refno":
						clearTable("T1");
						formPageReset(); break;
				}
		}		
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
					case "u_req":
						if (getTableInput(table,"u_mgh",row)=="0") {
							if (getTableInput(table,"u_billno",row)=="") {
								var targetObjectId = 'U_HISREQUESTS';
								OpenLnkBtn(1024,570,'./udo.php?objectcode=U_HISREQUESTS' + '&df_u_trxtype='+getInput("u_trxtype")+ '&targetId=' + targetObjectId ,targetObjectId);
							} else {
								page.statusbar.showError("Patient was already billed. Partial Payments not allowed.");
								return false;
							}	
						} else {
							page.statusbar.showError("Patient was already tag May Go Home. Partial Payments not allowed.");
							return false;
						}	
						
						break;
					case "u_chrg":
						if (getTableInput(table,"u_prepaid",row)=="0" || getInput("u_trxtype")=="BILLING") {
							if (getTableInput(table,"u_mgh",row)=="0" || getInput("u_trxtype")=="BILLING") {
								if (getTableInput(table,"u_billno",row)=="") {
									var targetObjectId = 'U_HISCHARGES';
									OpenLnkBtn(1024,500,'./udo.php?objectcode=U_HISCHARGES' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
								} else {
									if (window.confirm("Patient as already billed. Charges will not be included as part of deduction. Continue?")==false)	return false;
									var targetObjectId = 'U_HISCHARGES';
									OpenLnkBtn(1024,500,'./udo.php?objectcode=U_HISCHARGES' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
								}	
							} else {
								page.statusbar.showError("Patient was already tag May Go Home. Partial Payments not allowed.");
								return false;
							}	
						} else {
							page.statusbar.showError("Patient is only allowed to make cash transactions.");
							return false;
						}	
						break;
					case "u_cm":
						if (getTableInput(table,"u_mgh",row)=="0" || getInput("u_trxtype")=="BILLING") {
							if (getTableInput(table,"u_billno",row)=="") {
								var targetObjectId = 'U_HISCREDITS';
								OpenLnkBtn(1024,570,'./udo.php?objectcode=U_HISCREDITS' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
							} else {
								page.statusbar.showError("Patient was already billed. Returns/Credits not allowed.");
								return false;
							}	
						} else {
							page.statusbar.showError("Patient was already tag May Go Home. Returns/Credits not allowed.");
							return false;
						}	
						break;
					case "u_predisc":
						if (window.confirm("Tag or Untag Pre-discharge patient "+getTableInput(table,"u_patientname",row)+". Continue?")==false)	return false;
						tagPreDischargeGPSHIS(getTableInput(table,"docno",row));
						break;	
					case "u_tf":
						if (getTableInput(table,"u_billno",row)=="") {
							var targetObjectId = 'u_hisoptfs';
							OpenLnkBtn(1024,340,'./udo.php?objectcode=u_hisoptfs' + '' + '&targetId=' + targetObjectId ,targetObjectId);
						} else {
							page.statusbar.showError("Bill already generated cannot admit patient.");
							return false;
						}	
						break;	
					case "u_rmtf":
						if (getTableInput(table,"u_nursed",row)=="1") {
							//if (getTableInput(table,"u_mgh",row)=="0") {
								if (getTableInput(table,"u_expired",row)=="0") {
									var targetObjectId = 'u_hisroomtfs';
									OpenLnkBtn(1024,480,'./udo.php?objectcode=u_hisroomtfs' + '' + '&targetId=' + targetObjectId ,targetObjectId);
								} else {
									page.statusbar.showError("Patient cannot be transfered if patient expired.");
									return false;
								}
							//} else {
							//	page.statusbar.showError("Patient cannot be transfered if patient was tag as may go home.");
							//	return false;
							//}
						} else {
							page.statusbar.showError("Patient cannot be transfered if nurse station have not receive the patient.");
							return false;
						}
						break;
				}
				break;
			case "T10":
				switch (column) {
					case "u_discharged":
						if (!isTableInputChecked("T10","u_discharged")) {
							setTableInput("T10","u_enddate","");
							setTableInput("T10","u_endtime","");
						} else focusTableInput("T10","u_enddate");
						break;
				}	
				break;	
			default:
				switch (column) {
					case "u_mgh":
					case "u_expired":
						clearTable("T1");
						formPageReset(); break;
					case "u_reftype":
						clearTable("T1");
						formPageReset(); 
						formSearchNow();
						/*hideInput("u_mgh");
						hideElementById("ocf_u_mgh_all",true);
						hideElementById("ocf_u_mgh_yes",true);
						hideElementById("ocf_u_mgh_no",true);
						if (getInput("u_reftype")=="IP") {
							showInput("u_mgh");
							showElementById("ocf_u_mgh_all",true);
							showElementById("ocf_u_mgh_yes",true);
							showElementById("ocf_u_mgh_no",true);
						}
						*/
						break;	
					case "showreqdtls":
						showRequests();
						break;	
					case "showchrgdtls":
						/*if (isInputChecked("showchrgdtls")) {
							showTableColumn("T11","itemdesc");
							showTableColumn("T11","quantity");
							showTableColumn("T11","uom");
							showTableColumn("T11","rtqty");
						} else {
							hideTableColumn("T11","itemdesc");
							hideTableColumn("T11","quantity");
							hideTableColumn("T11","uom");
							hideTableColumn("T11","rtqty");
						}*/
						showCharges();
						break;	
					case "showcmdtls":
						showCredits();
						break;	
				}
				break;
		}		
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
			case "df_u_refno":
			case "df_u_patientname":
			case "df_u_mgh":
			case "df_u_expired":
			case "df_docstatus":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_department");
			inputs.push("u_refno");
			inputs.push("u_patientname");
			inputs.push("u_mgh");
			inputs.push("u_reftype");
			inputs.push("u_expired");
			inputs.push("docstatus");
			inputs.push("u_trxtype");
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
		switch (table) {
			case "T110":
				switch (column) {
					case "itemdesc":
					case "docdate":
					case "duedate":
						var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
						if (sc_press=="ENTER") {
							acceptText();
							showRequests();	
							focusTableInput(table,column);
						}	
						break;
				}		
				break;
			case "T111":
				switch (column) {
					case "itemdesc":
					case "docdate":
						var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
						if (sc_press=="ENTER") {
							acceptText();
							showCharges();	
							focusTableInput(table,column);
						}	
						break;
				}		
				break;
			default:	 
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
				break;
		}		
	}	
	function onSelectRow(table,row) {
		var imgpath = "", params = new Array();
		switch (table) {
			case "T1":
				showAjaxProcess();
				if (isPopupFrameOpen("popupFrameDischarged") && row>0) {
					setTableInput('T10',"docno",getTableInput(table,'docno',row));
					setTableInput('T10',"u_patientname",getTableInput(table,'u_patientname',row));
					setTableInput('T10',"u_startdate",getTableInput(table,'u_startdate',row));
					setTableInput('T10',"u_starttime",getTableInput(table,'u_starttime',row));
					setTableInput('T10',"u_enddate",getTableInput(table,'u_enddate',row));
					setTableInput('T10',"u_endtime",getTableInput(table,'u_endtime',row));
					if (getTableInput("T1","docstatus",getTableSelectedRow("T1"))=="Discharged") {
						checkedTableInput('T10','u_discharged');
						enableTableInput('T10','u_discharged');
						enableTableInput('T10','u_enddate');
						enableTableInput('T10','u_endtime');
					} else {
						uncheckedTableInput('T10','u_discharged');
						disableTableInput('T10','u_discharged');
						disableTableInput('T10','u_enddate');
						disableTableInput('T10','u_endtime');
					}
					
					setTimeout("focusTableInput('T10','u_enddate')",1000);
				}
				if (isPopupFrameOpen("popupFrameRequests") && row>0) {
					showRequests(false);
				}
				if (isPopupFrameOpen("popupFrameCharges") && row>0) {
					showCharges(false);
				}
				if (isPopupFrameOpen("popupFrameCredits") && row>0) {
					showCredits(false);
				}
				hideAjaxProcess();
				break;
		}		
		return params;
	}
	
	function OpenLnkBtnRequests(retrieve) {
		if (retrieve==null) retrieve = false;
		showPopupFrame("popupFrameRequests");
		if (retrieve) {
			if (getTableSelectedRow("T1")>0) showRequests();
		}
	}
	function OpenLnkBtnCharges(retrieve) {
		if (retrieve==null) retrieve = false;
		showPopupFrame("popupFrameCharges");
		if (retrieve) {
			if (getTableSelectedRow("T1")>0) showCharges();
		}
	}
	function OpenLnkBtnCredits(retrieve) {
		if (retrieve==null) retrieve = false;
		showPopupFrame("popupFrameCredits");
		if (retrieve) {
			if (getTableSelectedRow("T1")>0) showCredits();
		}
	}
	
	function showRequests(showprocess) {
		if (showprocess==null) showprocess = true;
		var row = getTableSelectedRow("T1"), data = new Array();
		var groupby = '', isgroup = false, docdateExp = "", duedateExp = "", departmentExp = "", itemdescExp = "";
		if (row==0) return;

		if (getTableInput("T110","docdate")!="") docdateExp = " and a.u_startdate='"+formatDateToDB(getTableInput("T110","docdate"))+"'";
		if (getTableInput("T110","duedate")!="") docdateExp = " and a.u_duedate='"+formatDateToDB(getTableInput("T110","duedate"))+"'";
		if (getTableInput("T110","departmentname")!="") departmentExp = " and a.u_department='"+getTableInput("T110","departmentname")+"'";
		if (getTableInput("T110","itemdesc")!="") {
			if (isInputChecked("showreqdtls")) itemdescExp = " and b.u_itemdesc='"+getTableInput("T110","itemdesc")+"'";
			else page.statusbar.showWarning("Click show details to filter by item.");
		}	

		if (showprocess) showAjaxProcess();
		clearTable("T10",true);
		if (isInputChecked("showreqdtls")) {
			var result = page.executeFormattedQuery("select a.docno, a.u_requestdate, a.u_requesttime, a.u_duedate, a.u_duetime, a.u_department, d.name as u_departmentname, a.docstatus, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_chrgqty, b.u_rtqty from u_hisrequests a left join u_hissections d on d.code=a.u_department inner join u_hisrequestitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getTableInput("T1","docno",row)+"'"+docdateExp+duedateExp+departmentExp+itemdescExp+" order by a.u_requestdate, a.u_requesttime");	 
		} else {
			var result = page.executeFormattedQuery("select a.docno, a.u_requestdate, a.u_requesttime, a.u_duedate, a.u_duetime, a.u_department, d.name as u_departmentname, a.docstatus from u_hisrequests a left join u_hissections d on d.code=a.u_department where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getTableInput("T1","docno",row)+"'"+docdateExp+duedateExp+departmentExp+itemdescExp+" order by a.u_requestdate, a.u_requesttime");	 
		}	
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					var data = new Array();
					if (groupby!=result.childNodes.item(xxxi).getAttribute("docno")) {
						groupby = result.childNodes.item(xxxi).getAttribute("docno");
					} else {
						data["docno.text"] = "";
						data["docdate.text"] = "";
						data["doctime.text"] = "";
						data["duedate.text"] = "";
						data["duetime.text"] = "";
						data["departmentname.text"] = "";
						data["docstatus.text"] = "";
					}	
				
					data["docno"] = result.childNodes.item(xxxi).getAttribute("docno");
					data["docdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_requestdate"));
					data["doctime"] = result.childNodes.item(xxxi).getAttribute("u_requesttime").substring(0,5);
					data["duedate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_duedate"));
					data["duetime"] = result.childNodes.item(xxxi).getAttribute("u_duetime").substring(0,5);
					data["departmentname"] = result.childNodes.item(xxxi).getAttribute("u_departmentname");
					if (isInputChecked("showreqdtls")) {
						data["itemdesc"] = result.childNodes.item(xxxi).getAttribute("u_itemdesc");
						data["quantity"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("u_quantity"));
						data["chrgqty"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("u_chrgqty"));
						data["rtqty"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("u_rtqty"));
						if (parseFloat(result.childNodes.item(xxxi).getAttribute("u_chrgqty"))==0) data["chrgqty.text"]="";
						if (parseFloat(result.childNodes.item(xxxi).getAttribute("u_rtqty"))==0) data["rtqty.text"]="";
						data["uom"] = result.childNodes.item(xxxi).getAttribute("u_uom");
					}
					switch (result.childNodes.item(xxxi).getAttribute("docstatus")) {
						case "O":data["docstatus"]="Open";break;
						case "C":data["docstatus"]="Closed";break;
						case "CN":data["docstatus"]="Cancelled";break;
					}
					insertTableRowFromArray("T10",data);
				}
			}
		} else {
			if (showprocess) hideAjaxProcess();
			page.statusbar.showError("Error retrieving Requests/Cash Sales. Try Again, if problem persists, check the connection.");	
			return false;
		}
		if (showprocess) hideAjaxProcess();
	}
	
	function showCharges(showprocess) {
		if (showprocess==null) showprocess = true;
		var row = getTableSelectedRow("T1"), data = new Array();
		var groupby = '', isgroup = false, docdateExp = "", departmentExp = "", itemdescExp = "";
		if (row==0) return;
		
		if (getTableInput("T111","docdate")!="") docdateExp = " and a.u_startdate='"+formatDateToDB(getTableInput("T111","docdate"))+"'";
		if (getTableInput("T111","departmentname")!="") departmentExp = " and a.u_department='"+getTableInput("T111","departmentname")+"'";
		if (getTableInput("T111","itemdesc")!="") {
			if (isInputChecked("showchrgdtls")) itemdescExp = " and b.u_itemdesc='"+getTableInput("T111","itemdesc")+"'";
			else page.statusbar.showWarning("Click show details to filter by item.");
		}	
		
		if (showprocess) showAjaxProcess();
		clearTable("T11",true);
		if (isInputChecked("showchrgdtls")) {
			var result = page.executeFormattedQuery("select a.docno, a.u_startdate, a.u_starttime, a.u_department, d.name as u_departmentname, a.docstatus, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_rtqty from u_hischarges a left join u_hissections d on d.code=a.u_department inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getTableInput("T1","docno",row)+"'"+docdateExp+departmentExp+itemdescExp+" order by a.u_startdate, a.u_starttime");	 
		} else {
			var result = page.executeFormattedQuery("select a.docno, a.u_startdate, a.u_starttime, a.u_department, d.name as u_departmentname, a.docstatus from u_hischarges a left join u_hissections d on d.code=a.u_department where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getTableInput("T1","docno",row)+"'"+docdateExp+departmentExp+itemdescExp+" order by a.u_startdate, a.u_starttime");	 
		}	
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					var data = new Array();
					if (groupby!=result.childNodes.item(xxxi).getAttribute("docno")) {
						groupby = result.childNodes.item(xxxi).getAttribute("docno");
					} else {
						data["docno.text"] = "";
						data["docdate.text"] = "";
						data["doctime.text"] = "";
						data["departmentname.text"] = "";
						data["docstatus.text"] = "";
					}	
				
					data["docno"] = result.childNodes.item(xxxi).getAttribute("docno");
					data["docdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_startdate"));
					data["doctime"] = result.childNodes.item(xxxi).getAttribute("u_starttime").substring(0,5);
					data["departmentname"] = result.childNodes.item(xxxi).getAttribute("u_departmentname");
					if (isInputChecked("showchrgdtls")) {
						data["itemdesc"] = result.childNodes.item(xxxi).getAttribute("u_itemdesc");
						data["quantity"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("u_quantity"));
						data["rtqty"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("u_rtqty"));
						if (parseFloat(result.childNodes.item(xxxi).getAttribute("u_rtqty"))==0) data["rtqty.text"]="";
						data["uom"] = result.childNodes.item(xxxi).getAttribute("u_uom");
					}
					switch (result.childNodes.item(xxxi).getAttribute("docstatus")) {
						case "O":data["docstatus"]="Open";break;
						case "C":data["docstatus"]="Closed";break;
						case "CN":data["docstatus"]="Cancelled";break;
					}
					insertTableRowFromArray("T11",data);
				}
			}
		} else {
			if (showprocess) hideAjaxProcess();
			page.statusbar.showError("Error retrieving Charged/Rendered. Try Again, if problem persists, check the connection.");	
			return false;
		}
		if (showprocess) hideAjaxProcess();
	}

	function showCredits(showprocess) {
		if (showprocess==null) showprocess = true;
		var row = getTableSelectedRow("T1"), data = new Array();
		var groupby = '', isgroup = false;
		if (row==0) return;

		if (showprocess) showAjaxProcess();
		clearTable("T12",true);
		if (isInputChecked("showcmdtls")) {
			var result = page.executeFormattedQuery("select a.docno, a.u_startdate, a.u_starttime, a.u_department, d.name as u_departmentname, b.u_itemdesc, b.u_quantity, b.u_uom from u_hiscredits a left join u_hissections d on d.code=a.u_department inner join u_hiscredititems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getTableInput("T1","docno",row)+"' order by a.u_startdate, a.u_starttime");	 
		} else {
			var result = page.executeFormattedQuery("select a.docno, a.u_startdate, a.u_starttime, a.u_department, d.name as u_departmentname from u_hiscredits a left join u_hissections d on d.code=a.u_department where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getTableInput("T1","docno",row)+"' order by a.u_startdate, a.u_starttime");	 
		}	
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					var data = new Array();
					if (groupby!=result.childNodes.item(xxxi).getAttribute("docno")) {
						groupby = result.childNodes.item(xxxi).getAttribute("docno");
					} else {
						data["docno.text"] = "";
						data["docdate.text"] = "";
						data["doctime.text"] = "";
						data["departmentname.text"] = "";
					}	
				
					data["docno"] = result.childNodes.item(xxxi).getAttribute("docno");
					data["docdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_startdate"));
					data["doctime"] = result.childNodes.item(xxxi).getAttribute("u_starttime").substring(0,5);
					data["departmentname"] = result.childNodes.item(xxxi).getAttribute("u_departmentname");
					if (isInputChecked("showcmdtls")) {
						data["itemdesc"] = result.childNodes.item(xxxi).getAttribute("u_itemdesc");
						data["quantity"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("u_quantity"));
						data["uom"] = result.childNodes.item(xxxi).getAttribute("u_uom");
					}
					insertTableRowFromArray("T12",data);
				}
			}
		} else {
			if (showprocess) hideAjaxProcess();
			page.statusbar.showError("Error retrieving Returns/Credits. Try Again, if problem persists, check the connection.");	
			return false;
		}
		if (showprocess) hideAjaxProcess();
	}
	
	function OpenLnkBtnRequestDocNo(targetId) {
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisrequests' + '&targetId=' + targetId ,targetId);
	}	

	function OpenLnkBtnChargeDocNo(targetId) {
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '&targetId=' + targetId ,targetId);
	}	

	function OpenLnkBtnCreditDocNo(targetId) {
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hiscredits' + '&targetId=' + targetId ,targetId);
	}	
	
	function showDietaryGPSHIS(meal) {
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisdietaries' + '&df_u_meal='+meal+'&targetId=' + targetId ,targetId);
	}

	function OpenLnkBtnPriceList(targetId) {
		OpenLnkBtn(800,600,'./udp.php?objectcode=u_hispricelist&df_u_trxtype=' + getInput("u_trxtype") + '&targetId=' + targetId ,targetId);
	}
	
	function tagPreDischargeGPSHIS(docno) {
		var msgcnt = 0;	
		try {
			//alert('querying');
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxtagpredischarge&docno="+docno, false);
			http.send(null);
			var result = http.responseText.trim();
			alert(result);
			if (result=="Patient was successfully untag for predischarge." || result=="Patient was successfully tag as predischarge.") {
				formSearchNow();
			}
			/*http.onreadystatechange = function () {
				if (http.readyState == 4) {
					var result = http.responseText.trim();
					alert(result);
					if (result=="Patient was successfully untag for predischarge." || result=="Patient was successfully tag as predischarge.") {
						formSearchNow();
					}
				} 
			}*/
			http.send(null);
		} catch (theError) {
		}	
	}

	function showDischargedDetails() {
		showPopupFrame('popupFrameDischarged',true);
		focusTableInput('T10','u_enddate');

	}
	
	function u_updatedischargeddetailsGPSHIS()	{
		if (getTableInput("T1","docstatus",getTableSelectedRow("T1"))=="Discharged") {
			if (isTableInputChecked("T10","u_discharged")) {
				if (isTableInputEmpty("T10","u_enddate")) return false;
				if (isTableInputEmpty("T10","u_endtime")) return false;
			} else {
				setTableInput("T10","u_enddate","");
				setTableInput("T10","u_endtime","");
			}
			//alert('');
			try {
				showAjaxProcess();
				http = getHTTPObject(); 
				http.open("GET", "udp.php?objectcode=u_ajaxupdatedischargedetails&docno="+getTableInput("T10","docno")+"&u_reftype="+getInput("u_reftype")+"&u_discharged="+getTableInput("T10","u_discharged")+"&u_enddate="+getTableInput("T10","u_enddate")+"&u_endtime="+getTableInput("T10","u_endtime"), false);
				http.send(null);
				var result = http.responseText.trim();
				hideAjaxProcess();
				alert(result);
				if (result=="Patient discharge records was successfully updated.") {
					formSearchNow();
				}
				/*http.onreadystatechange = function () {
					if (http.readyState == 4) {
						var result = http.responseText.trim();
						alert(result);
						if (result=="Patient was successfully untag for predischarge." || result=="Patient was successfully tag as predischarge.") {
							formSearchNow();
						}
					} 
				}*/
				http.send(null);
			} catch (theError) {
			}	
			
		} else page.statusbar.showWarning('You can only update discharged patient.');
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
<input type="hidden" <?php genInputHiddenDFHtml("u_trxtype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Patient Registration - List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_reftype"],"") ?>>Registration</label></td>
	  <td  align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_reftype"],"OP") ?> /><label class="lblobjs">Out-Patient</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_reftype"],"IP") ?> /><label class="lblobjs">In-Patient</b></label></td>
	  <td width="168"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="168">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_department"],"") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_department"],array("loadudflinktable","u_hissections:code:name:u_type in ('NURSE','ER','OP','IP','DIALYSIS','PMR','DIETARY')",":[All]")) ?> ></select></td>
	  <td align=left><label <?php genCaptionHtml($schema["u_mgh"],"") ?>>May Go Home</label></td>
	  <td align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_mgh"],"") ?> /><label <?php genOptionCaptionHtml(createSchema("u_mgh_all")) ?>>All</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_mgh"],"0") ?> /><label <?php genOptionCaptionHtml(createSchema("u_mgh_no")) ?>>No</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_mgh"],"1") ?> /><label <?php genOptionCaptionHtml(createSchema("u_mgh_yes")) ?>>Yes</b></label></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_refno"],"") ?>>Registration No.</label></td>
	  <td align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["u_refno"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_patientname"],"") ?>>Patient Name</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_patientname"]) ?> /></td>
	  <td align=left><label <?php genCaptionHtml($schema["u_expired"],"") ?>>Expired</label></td>
	  <td align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_expired"],"") ?> /><label class="lblobjs">All</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_expired"],"0") ?> /><label class="lblobjs">No</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_expired"],"1") ?> /><label class="lblobjs">Yes</b></label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr ><td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
	<label style="background-color:green" title="Active">&nbsp;</label><label style="background-color:yellow" title="May Go Home">&nbsp;</label><label style="background-color:lime" title="Untag May Go Home">&nbsp;</label><label style="background-color:orange" title="Pre-Discharge">&nbsp;</label><label style="background-color:gray" title="Expired">&nbsp;</label><label style="background-color:red" title="Allergies">&nbsp;</label><label style="background-color:black" title="Confidential">&nbsp;</label>&nbsp;&nbsp;<label class="lblobjs">[<a class="links" href="" onClick="OpenLnkBtnRequests(true);return false">Ca - Request/Cash</a>], [<a class="links" href="" onClick="OpenLnkBtnCharges(true);return false">Ch - Charge/Render</a>], [<a class="links" href="" onClick="OpenLnkBtnCredits(true);return false">Cr - Return/Credit</a>], [PD - Tag/Untag Predischarge]</label><?php if ($page->getitemstring("u_trxtype")=="NURSE" || $_SESSION["groupid"]=="SYSADMIN") { ?> &nbsp;&nbsp;&nbsp;&nbsp;[<a class="links" href="" onClick="showDietaryGPSHIS('Breakfast');return false">Breakfast</a>, </label><a class="links" href="" onClick="showDietaryGPSHIS('Lunch');return false">Lunch</a>, </label><a class="links" href="" onClick="showDietaryGPSHIS('Dinner');return false">Dinner</a>]</label><?php } ?>
			</td>
			<td align="right">
				[<a class="links" href="" onClick="OpenLnkBtnPriceList();return false">Price Inquiry</a>]
			</td>
		</tr>
	</table>	
</td></tr>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
<div <?php genPopWinHDivHtml("popupFrameRequests","List of Request/Cash Slips",240,150,1000,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2"><input type="checkbox" <?php genInputCheckboxHtml(createSchema("showreqdtls"),"1") ?> /><label <?php genCaptionHtml(createSchema("showreqdtls"),"") ?> >Show Details</label></td>
			<td width="80"><label <?php genCaptionHtml(createSchema("showreqstatus"),"") ?> >Status</label></td>
			<td width="80">&nbsp;<select <?php genSelectHtml(createSchema("showreqstatus"),"") ?> /></select></td>
		</tr>
		<tr><td colspan="4"><?php $objGridRequests->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="4"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameCharges","List of Charge/Render Slips",270,190,1000,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2"><input type="checkbox" <?php genInputCheckboxHtml(createSchema("showchrgdtls"),"1") ?> /><label <?php genCaptionHtml(createSchema("showchrgdtls"),"") ?> >Show Details</label></td></tr>
		<tr><td colspan="2"><?php $objGridCharges->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameCredits","List of Return/Credit Slips",300,230,840,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2"><input type="checkbox" <?php genInputCheckboxHtml(createSchema("showcmdtls"),"1") ?> /><label <?php genCaptionHtml(createSchema("showcmdtls"),"") ?> >Show Details</label></td></tr>
		<tr><td colspan="2"><?php $objGridCredits->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameDischarged","Discharge Details",10,30,600,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
		  <td><label <?php genCaptionHtml(createSchema("docno"),"T10") ?>>Registration No.</label></td>
		  <td align=left>&nbsp;<input type="text" size="12" disabled <?php genInputTextHtml(createSchema("docno"),"T10") ?>/></td>
		  </tr>
		<tr>
		  <td width="188"><label <?php genCaptionHtml(createSchema("u_patientname"),"T10") ?>>Patient Name</label></td>
		  <td align=left>&nbsp;<input type="text" size="50" disabled <?php genInputTextHtml(createSchema("u_patientname"),"T10") ?>/></td>
		  </tr>
		<tr><td ><label <?php genCaptionHtml(createSchema("u_startdate"),"T10") ?>>Registration Date /</label><label <?php genCaptionHtml(createSchema("u_starttime"),"T10") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" disabled <?php genInputTextHtml(createSchemaDate("u_startdate"),"T10") ?>/>&nbsp;<input type="text" size="6" disabled <?php genInputTextHtml(createSchema("u_starttime"),"T10") ?>/></td>
		</tr>
		<tr><td ><input type="checkbox" <?php genInputCheckboxHtml(createSchema("u_discharged"),1,"T10") ?> /><label <?php genCaptionHtml(createSchema("u_enddate"),"T10") ?>>Discharge Date /</label><label <?php genCaptionHtml(createSchema("u_endtime"),"T10") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml(createSchemaDate("u_enddate"),"T10") ?>/>&nbsp;<input type="text" size="6" <?php genInputTextHtml(createSchema("u_endtime","",null,null,"time"),"T10") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_updatedischargeddetailsGPSHIS();return false;" >Update</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>

<?php require("./bofrms/ajaxprocess.php"); ?>
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
