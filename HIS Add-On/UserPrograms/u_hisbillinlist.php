<?php
	$progid = "u_hisbillinlist";

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
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISIPS";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisbillinlist";
	$page->formid = "./UDO.php?&objectcode=U_HISBILLS";
	$page->objectname = "In-Patient";
	
	$schema["u_reftype"] = createSchemaUpper("u_reftype");
	$schema["u_refno2"] = createSchemaUpper("u_refno2");
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");
	$schema["u_paymentterm"] = createSchemaUpper("u_paymentterm");
	$schema["u_healthins"] = createSchemaUpper("u_healthins");
	$schema["u_startdatefr"] = createSchemaDate("u_startdatefr");
	$schema["u_startdateto"] = createSchemaDate("u_startdateto");
	$schema["u_queue"] = createSchemaUpper("u_queue");
	$schema["u_terminalid"] = createSchemaUpper("u_terminalid");

	$objrs = new recordset(null,$objConnection);
	$dfltreg="IP";
	$objrs->queryopen("select U_DFLTREG FROM U_HISSETUP WHERE CODE='SETUP'");
	if ($objrs->queryfetchrow("NAME")) {
		$dfltreg = $objrs->fields["U_DFLTREG"];
	}
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		//$userdepartment = $objrs->fields["DEPARTMENT"];
		//if ($userdepartment!="") $schema["u_department"]["attributes"] = "disabled";
	}

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("indicator");
	$objGrid->addcolumn("u_expires");
	$objGrid->addcolumn("u_allergies");
	$objGrid->addcolumn("u_confidential");
	$objGrid->addcolumn("u_predischarge");
	$objGrid->addcolumn("u_startdate");
	$objGrid->addcolumn("u_starttime");
	$objGrid->addcolumn("u_type");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_paymentterm");
	$objGrid->addcolumn("u_healthins");
	$objGrid->addcolumn("u_billno");
	$objGrid->addcolumn("u_mghdate");
	$objGrid->addcolumn("u_mghtime");
	$objGrid->addcolumn("u_overstay");
	$objGrid->addcolumn("u_creditlimit");
	$objGrid->addcolumn("u_totalcharges");
	$objGrid->addcolumn("u_availablecreditlimit");
	$objGrid->addcolumn("u_availablecreditperc");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_patientid");
	$objGrid->addcolumn("u_departmentname");
	$objGrid->addcolumn("u_bedno");
	$objGrid->addcolumn("u_roomdesc");
	//$objGrid->addcolumn("docstatus");
	$objGrid->columntitle("indicator","");
	$objGrid->columntitle("u_expires","");
	$objGrid->columntitle("u_allergies","");
	$objGrid->columntitle("u_confidential","");
	$objGrid->columntitle("u_predischarge","");
	$objGrid->columntitle("u_startdate","Date");
	$objGrid->columntitle("u_starttime","Time");
	$objGrid->columntitle("u_type","Type");
	$objGrid->columntitle("u_mghdate","MGH Date");
	$objGrid->columntitle("u_mghtime","Time");
	$objGrid->columntitle("u_overstay","OS");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_departmentname","Nurse Section");
	$objGrid->columntitle("u_bedno","Room/Bed No.");
	$objGrid->columntitle("u_roomdesc","Room Description");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_paymentterm","Payment Term");
	$objGrid->columntitle("u_healthins","Company");
	$objGrid->columntitle("u_billno","Bill No");
	$objGrid->columntitle("u_creditlimit","Credit Limit");
	$objGrid->columntitle("u_totalcharges","Total Charges");
	$objGrid->columntitle("u_availablecreditlimit","Available Limit");
	$objGrid->columntitle("u_availablecreditperc","%");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("u_expires",-1);
	$objGrid->columnwidth("u_allergies",-1);
	$objGrid->columnwidth("u_confidential",-1);
	$objGrid->columnwidth("u_predischarge",-1);
	$objGrid->columnwidth("u_startdate",9);
	$objGrid->columnwidth("u_starttime",5);
	$objGrid->columnwidth("u_mghdate",9);
	$objGrid->columnwidth("u_mghtime",5);
	$objGrid->columnwidth("u_overstay",2);
	$objGrid->columnwidth("u_type",4);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_departmentname",18);
	$objGrid->columnwidth("u_bedno",12);
	$objGrid->columnwidth("u_roomdesc",22);
	$objGrid->columnwidth("u_patientid",10);
	$objGrid->columnwidth("u_patientname",30);
	$objGrid->columnwidth("u_paymentterm",12);
	$objGrid->columnwidth("u_healthins",25);
	$objGrid->columnwidth("u_billno",14);
	$objGrid->columnwidth("u_creditlimit",12);
	$objGrid->columnwidth("u_totalcharges",12);
	$objGrid->columnwidth("u_availablecreditlimit",15);
	$objGrid->columnwidth("u_availablecreditperc",10);
	$objGrid->columnwidth("docstatus",9);
	$objGrid->columnalignment("u_creditlimit","right");
	$objGrid->columnalignment("u_totalcharges","right");
	$objGrid->columnalignment("u_availablecreditlimit","right");
	$objGrid->columnalignment("u_availablecreditperc","right");
	$objGrid->columnalignment("u_overstay","right");
	$objGrid->columnsortable("u_startdate",true);
	$objGrid->columnsortable("u_starttime",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_departmentname",true);
	$objGrid->columnsortable("u_bedno",true);
	$objGrid->columnsortable("u_roomdesc",true);
	$objGrid->columnsortable("u_patientid",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_billno",true);
	$objGrid->columnsortable("u_healthins",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columnsortable("u_creditlimit",true);
	$objGrid->columnsortable("u_totalcharges",true);
	$objGrid->columnsortable("u_availablecreditlimit",true);
	$objGrid->columnsortable("u_availablecreditperc",true);
	$objGrid->columnvisibility("u_mghdate",false);
	$objGrid->columnvisibility("u_mghtime",false);
	$objGrid->columnvisibility("u_overstay",false);
	$objGrid->columnlnkbtn("docno","OpenLnkBtnRefNo()");
	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_availablecreditperc";
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
					case "OVERSTAY": $style="background-color:blue";break;
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
			case "u_overstay":
				if ($label=="0") $label="&nbsp";	
				break;
		}
		
	}
	
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","Active");
		//$page->setitem("u_department",$userdepartment);
		$page->setitem("u_reftype",$dfltreg);
	}
	$filterExp = "";
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_u_refno2']);
	$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
	$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterString("A.U_EXPIRED",$filterExp,$httpVars['df_u_expired']);
	$filterExp = genSQLFilterString("A.U_PAYMENTTERM",$filterExp,$httpVars['df_u_paymentterm']);
	$filterExp = genSQLFilterString("A.U_HEALTHINS",$filterExp,$httpVars['df_u_healthins'],null,null,true);
	$filterExp = genSQLFilterDate("A.U_STARTDATE",$filterExp,$httpVars['df_u_startdatefr'],$httpVars['df_u_startdateto']);
	//$filterExp = genSQLFilterString("A.U_MGH",$filterExp,$httpVars['df_u_mgh']);
	if ($page->getitemstring("u_reftype")=="IP") {
		$objGrid->columnvisibility("u_overstay",true);
		$objGrid->columnvisibility("u_mghdate",true);
		$objGrid->columnvisibility("u_mghtime",true);
		$filterExp = genSQLFilterString("A.U_MGH",$filterExp,$httpVars['df_u_mgh']);
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
	if ($page->getitemstring("u_reftype")=="IP") {
		$objrs->queryopenext("select 'IP' AS U_TYPE, A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_HEALTHINS, A.U_BILLNO, A.U_EXPIRED, A.U_MGH, A.U_MGHDATE, A.U_MGHTIME, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, A.U_UNTAGMGHREMARKS, A.U_ALLERGIES, A.U_CONFIDENTIAL, A.U_CREDITLIMIT, A.U_TOTALCHARGES, A.U_AVAILABLECREDITLIMIT, A.U_AVAILABLECREDITPERC, A.U_BILLNO from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND (A.U_BILLING=1 OR A.U_FORCEBILLING=1 OR A.U_BEDNO<>'') $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} else {
		$objrs->queryopenext("select 'OP' AS U_TYPE, A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, '' as U_BEDNO, '' as U_ROOMDESC, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_HEALTHINS, A.U_BILLNO, A.U_EXPIRED, 1 as U_MGH, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, '' as U_UNTAGMGHREMARKS, A.U_ALLERGIES, A.U_CONFIDENTIAL, A.U_CREDITLIMIT, A.U_TOTALCHARGES, A.U_AVAILABLECREDITLIMIT, A.U_AVAILABLECREDITPERC, A.U_BILLNO from U_HISOPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND (A.U_BILLING=1 OR A.U_FORCEBILLING=1) $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	}
//	$objrs->queryopenext("select 'OP' AS U_TYPE, A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, '' as U_BEDNO, '' as U_ROOMDESC, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_HEALTHINS, A.U_BILLNO, A.U_EXPIRED, 1 as U_MGH, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, '' as U_UNTAGMGHREMARKS, A.U_ALLERGIES, A.U_CONFIDENTIAL, A.U_CREDITLIMIT, A.U_TOTALCHARGES, A.U_AVAILABLECREDITLIMIT, A.U_AVAILABLECREDITPERC, A.U_BILLNO from U_HISOPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND (A.U_BILLING=1 OR A.U_FORCEBILLING=1) $filterExp union all select 'IP' AS U_TYPE, A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_HEALTHINS, A.U_BILLNO, A.U_EXPIRED, A.U_MGH, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, A.U_UNTAGMGHREMARKS, A.U_ALLERGIES, A.U_CONFIDENTIAL, A.U_CREDITLIMIT, A.U_TOTALCHARGES, A.U_AVAILABLECREDITLIMIT, A.U_AVAILABLECREDITPERC, A.U_BILLNO from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND (A.U_BILLING=1 OR A.U_FORCEBILLING=1 OR A.U_BEDNO<>'') $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$indicator=$objrs->fields["DOCSTATUS"];
		//if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
		//else
		$objGrid->setitem(null,"u_overstay",0);
		if ($objrs->fields["U_MGH"]==1 && $objrs->fields["U_DISCHARGED"]==0) {
			if (datedifference("d",$objrs->fields["U_MGHDATE"],currentdateDB())>0) $indicator="OVERSTAY";
			else $indicator="MGH";
			$objGrid->setitem(null,"u_overstay",datedifference("d",$objrs->fields["U_MGHDATE"],currentdateDB()));
		} elseif ($objrs->fields["U_UNTAGMGHREMARKS"]!="" && $objrs->fields["U_DISCHARGED"]==0) $indicator="XMGH";
		
		$objGrid->setitem(null,"indicator",$indicator);
		$objGrid->setitem(null,"u_startdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
		$objGrid->setitem(null,"u_starttime",formatTimeToHttp($objrs->fields["U_STARTTIME"]));
		$objGrid->setitem(null,"u_mghdate",formatDateToHttp($objrs->fields["U_MGHDATE"]));
		$objGrid->setitem(null,"u_mghtime",formatTimeToHttp($objrs->fields["U_MGHTIME"]));
		$objGrid->setitem(null,"u_type",$objrs->fields["U_TYPE"]);
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_departmentname",$objrs->fields["U_DEPARTMENTNAME"]);
		$objGrid->setitem(null,"u_bedno",$objrs->fields["U_BEDNO"]);
		$objGrid->setitem(null,"u_roomdesc",$objrs->fields["U_ROOMDESC"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["U_PATIENTID"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["U_PATIENTNAME"]);
		$objGrid->setitem(null,"u_paymentterm",$objrs->fields["U_PAYMENTTERM"]);
		$objGrid->setitem(null,"u_healthins",$objrs->fields["U_HEALTHINS"]);
		$objGrid->setitem(null,"u_billno",$objrs->fields["U_BILLNO"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_expires",$objrs->fields["U_EXPIRED"]);
		$objGrid->setitem(null,"u_allergies",$objrs->fields["U_ALLERGIES"]);
		$objGrid->setitem(null,"u_confidential",$objrs->fields["U_CONFIDENTIAL"]);
		$objGrid->setitem(null,"u_creditlimit",formatNumericAmount($objrs->fields["U_CREDITLIMIT"]));
		$objGrid->setitem(null,"u_totalcharges",formatNumericAmount($objrs->fields["U_TOTALCHARGES"]));
		$objGrid->setitem(null,"u_availablecreditlimit",formatNumericAmount($objrs->fields["U_AVAILABLECREDITLIMIT"]));
		$objGrid->setitem(null,"u_availablecreditperc",formatNumericPercent($objrs->fields["U_AVAILABLECREDITPERC"])."%");
		$objGrid->setitem(null,"u_billno",$objrs->fields["U_BILLNO"]);
		if ($objrs->fields["U_MGH"]==1 && ($objrs->fields["U_PREDISCHARGE"]==1 || ($objrs->fields["U_BILLING"]==0 && $objrs->fields["U_FORCEBILLING"]==0))) {
			$objGrid->setitem(null,"u_predischarge",1);
		} else {
			$objGrid->setitem(null,"u_predischarge",$objrs->fields["U_PREDISCHARGE"]);
		}	
		$objGrid->setkey(null,$objrs->fields["U_BILLNO"]); 
		if (!$page->paging_fetch()) break;
	}	
	
	$objRs = new recordset(null,$objConnection); 
	$objRs->queryopen("select A.CODE, IFNULL((B.NEXTID-1),0) AS U_QUEUENO from U_HISQUETERMINALS A LEFT JOIN DOCIDS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND DOCTYPE='u_hisque2".date('Ymd')."' where A.BRANCH='".$_SESSION["branch"]."' AND A.NAME='".$_SERVER['REMOTE_ADDR']."' AND A.U_QUEGROUP='2'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_terminalid",$objRs->fields["CODE"]);
		//$page->setitem("u_autoqueue",0);
		$page->setitem("u_queue", $objRs->fields["U_QUEUENO"]);
		//$page->businessobject->items->setvisible("u_autoqueue",false);
	}
	
	$page->resize->addgrid("T1",20,210,false);
	
	//$rptcols = 6; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
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
<SCRIPT language=JavaScript src="js/popupmenu.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js"></SCRIPT>
<SCRIPT language=JavaScript src="<?php autocaching('js/ajax.js'); ?>"></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatients.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function OpenLnkBtnRefNo(targetId) {
		switch (getTableElementValue(targetId,"T1","u_type")) {
			case "OP":
				OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hisops' + '' + '&targetId=' + targetId ,targetId);
				break;
			case "IP":
				OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetId ,targetId);
				break;
		}	
	}
	
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
		//focusInput("u_refno2");
		focusInput("u_patientname");
		if (getInput("u_terminalid")!="") {
			showPopupFrame("popupFrameQueue",true,null,false);
		}
		
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				setInput("u_reftype",getTableInput("T1","u_type",p_rowIdx));
				setInput("u_refno",getTableInput("T1","docno",p_rowIdx));
				formEntry(null,"<?php echo $page->formid; ?>");
				//return formView(null,"<?php echo $page->formid; ?>");
				//var targetObjectId = 'u_hispatients';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
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
			case "docstatus":
			case "u_paymentterm":
				clearTable("T1");
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_patientname":
			case "u_refno2":
			case "u_startdatefr":
			case "u_startdateto":
			case "u_healthins":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (column) {
			case "u_mgh":
				clearTable("T1");
				formPageReset(); 
				formSearchNow();
				break;
			case "u_reftype":
				clearTable("T1");
				if (getInput("u_reftype")=="IP") setInput("u_mgh",1);
				formPageReset(); 
				formSearchNow();
				break;	
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_reftype":
			case "df_u_refno2":
			case "df_u_mgh":
			case "df_u_patientname":
			case "df_u_docstatus":
			case "df_u_startdatefr":
			case "df_u_startdateto":
			case "df_u_paymentterm":
			case "df_u_healthins":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_reftype");
			inputs.push("u_refno2");
			inputs.push("u_mgh");
			inputs.push("u_patientname");
			inputs.push("docstatus");
			inputs.push("u_startdatefr");
			inputs.push("u_startdateto");
			inputs.push("u_paymentterm");
			inputs.push("u_healthins");
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
			case "u_patientname":
			case "u_refno2":
			case "u_startdatefr":
			case "u_startdateto":
			case "u_healthins":
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
	
	function getNextQueGPSHIS(docno) {
		var msgcnt = 0;	
		showAjaxProcess();
		try {
			//alert('querying');
			http = getHTTPObject(); 
			//alert( "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"));
			http.open("GET", "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"), false);
			http.send(null);
			var result = parseInt(http.responseText.trim());
			hideAjaxProcess();
			if (result>0) {
				setInput("u_queue",result);
			} else alert(http.responseText.trim());
			http.send(null);
		} catch (theError) {
			hideAjaxProcess();
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
<input type="hidden" <?php genInputHiddenDFHtml("u_refno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_terminalid") ?> >
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
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
	  <td width="168" ><label <?php genCaptionHtml($schema["u_reftype"],"") ?>>Reg. Type</label></td>
	  <td  align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_reftype"],"OP") ?> /><label class="lblobjs">Out-Patient</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_reftype"],"IP") ?> /><label class="lblobjs">In-Patient</b></label></td>
	  <td width="168"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="168">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_startdatefr"],"") ?>>Reg. Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_startdatefr"]) ?> /><label <?php genCaptionHtml($schema["u_startdateto"],"") ?>>To</label>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_startdateto"]) ?> /></td>
	  <td align=left><label <?php genCaptionHtml($schema["u_mgh"],"") ?>>May Go Home</label></td>
	  <td align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_mgh"],"") ?> /><label <?php genOptionCaptionHtml(createSchema("u_mgh_all")) ?>>All</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_mgh"],"0") ?> /><label <?php genOptionCaptionHtml(createSchema("u_mgh_no")) ?>>No</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_mgh"],"1") ?> /><label <?php genOptionCaptionHtml(createSchema("u_mgh_yes")) ?>>Yes</b></label></td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_refno2"],"") ?>>Reg. No.</label></td>
	  <td align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["u_refno2"]) ?> /></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_patientname"],"") ?>>Patient Name</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_patientname"]) ?> /></td>
	  <td ><label <?php genCaptionHtml($schema["u_paymentterm"],"") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_paymentterm"],array("loadudflinktable","u_hispaymentterms:code:name",":[All]")) ?> ></select></td>
	</tr>
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
	  <td ><label <?php genCaptionHtml($schema["u_healthins"],"") ?>>Company</label></td>
	  <td align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_healthins"]) ?> /></td>
	  </tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr ><td><label style="background-color:green" title="Active">&nbsp;</label><label style="background-color:yellow" title="May Go Home">&nbsp;</label><label style="background-color:lime" title="Untag May Go Home">&nbsp;</label><label style="background-color:blue" title="Overstay">&nbsp;</label><label style="background-color:orange" title="Pre-Discharge">&nbsp;</label><label style="background-color:gray" title="Expired">&nbsp;</label><label style="background-color:red" title="Allergies">&nbsp;</label><label style="background-color:black" title="Confidential">&nbsp;</label></td></tr>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
<div <?php genPopWinHDivHtml("popupFrameQueue","Queueing",10,13,230,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php genCaptionHtml($schema["u_queue"],"") ?>>Current Number</label></td>
			<td >&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_queue"]) ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td >&nbsp;</td>
            <td align="right"><a class="button" href="" onClick="getNextQueGPSHIS();return false;" >Get Next Number</a></td>
        </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>	
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
