<?php
	$progid = "u_hisoplist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/departments.php");
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
	
	$page->objectcode = "U_HISOPLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisoplist";
	$page->formid = "./UDO.php?&objectcode=U_HISOPS";
	$page->objectname = "In-Patient";

	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("indicator");
	$objGrid->addcolumn("u_allergies");
	$objGrid->addcolumn("u_confidential");
	$objGrid->addcolumn("u_predischarge");
	$objGrid->addcolumn("u_startdate");
	$objGrid->addcolumn("u_starttime");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_department");
	$objGrid->addcolumn("u_departmentname");
	$objGrid->addcolumn("u_patientid");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_req");
	$objGrid->addcolumn("u_chrg");
	$objGrid->addcolumn("u_paymentterm");
	$objGrid->addcolumn("docstatus");
	$objGrid->addcolumn("u_billing");
	$objGrid->columntitle("indicator","");
	$objGrid->columntitle("u_allergies","");
	$objGrid->columntitle("u_confidential","");
	$objGrid->columntitle("u_predischarge","");
	$objGrid->columntitle("u_startdate","Date");
	$objGrid->columntitle("u_starttime","Time");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_departmentname","Section");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_paymentterm","Payment Term");
	$objGrid->columntitle("u_req","");
	$objGrid->columntitle("u_chrg","");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("u_allergies",-1);
	$objGrid->columnwidth("u_confidential",-1);
	$objGrid->columnwidth("u_predischarge",-1);
	$objGrid->columnwidth("u_startdate",9);
	$objGrid->columnwidth("u_starttime",5);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_departmentname",18);
	$objGrid->columnwidth("u_patientid",10);
	$objGrid->columnwidth("u_patientname",35);
	$objGrid->columnwidth("u_paymentterm",13);
	$objGrid->columnwidth("docstatus",9);
	$objGrid->columnwidth("u_req",6);
	$objGrid->columnwidth("u_chrg",5);
	$objGrid->columnsortable("u_startdate",true);
	$objGrid->columnsortable("u_starttime",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_departmentname",true);
	$objGrid->columnsortable("u_patientid",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_paymentterm",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columninput("u_req","type","link");
	$objGrid->columninput("u_req","caption","Request");
	$objGrid->columninput("u_chrg","type","link");
	$objGrid->columninput("u_chrg","caption","Charge");
	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	$objGrid->columnvisibility("u_department",false);
	$objGrid->columnvisibility("u_billing",false);
	//$objGrid->automanagecolumnwidth = false;
	
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
					case "Expired": $style="background-color:gray";break;
					case "XMGH": $style="background-color:lime";break;
				}
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
		}
		
	}

	require("./inc/formactions.php");
	
	
	$objrs = new recordset(null,$objConnection);
	$userdepartment = "";
	/*
	$objrs->queryopen("select DEPARTMENT FROM EMPLOYEES WHERE USERID='".$_SESSION["userid"]."'");
	*/
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		//if ($userdepartment!="") $schema["u_department"]["attributes"] = "disabled";
	}
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","Active");
		//$page->setitem("u_department",$userdepartment);
	}
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
	$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterString("A.U_EXPIRED",$filterExp,$httpVars['df_u_expired']);
	//$filterExp = genSQLFilterString("A.U_MGH",$filterExp,$httpVars['df_u_mgh']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);

	$objrs->setdebug();
	$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_DEPARTMENT, B.NAME AS U_DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM, A.U_EXPIRED, A.U_MGH, A.U_PREDISCHARGE, A.U_BILLING, A.U_FORCEBILLING, A.U_DISCHARGED, A.U_UNTAGMGHREMARKS, A.U_ALLERGIES, A.U_CONFIDENTIAL from U_HISOPS A, U_HISSECTIONS B WHERE B.CODE=A.U_DEPARTMENT AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$indicator=$objrs->fields["DOCSTATUS"];
		if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
		elseif ($objrs->fields["U_MGH"]==1 && $objrs->fields["U_DISCHARGED"]==0) $indicator="MGH";
		elseif ($objrs->fields["U_UNTAGMGHREMARKS"]!="" && $objrs->fields["U_DISCHARGED"]==0) $indicator="XMGH";
		
		$objGrid->setitem(null,"indicator",$indicator);
		$objGrid->setitem(null,"u_startdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
		$objGrid->setitem(null,"u_starttime",substr($objrs->fields["U_STARTTIME"],0,5));
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_department",$objrs->fields["U_DEPARTMENT"]);
		$objGrid->setitem(null,"u_departmentname",$objrs->fields["U_DEPARTMENTNAME"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["U_PATIENTID"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["U_PATIENTNAME"]);
		$objGrid->setitem(null,"u_paymentterm",$objrs->fields["U_PAYMENTTERM"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_allergies",$objrs->fields["U_ALLERGIES"]);
		$objGrid->setitem(null,"u_confidential",$objrs->fields["U_CONFIDENTIAL"]);
		$objGrid->setitem(null,"u_billing",$objrs->fields["U_BILLING"]);
		if ($objrs->fields["U_PREDISCHARGE"]==1 || ($objrs->fields["U_BILLING"]==0 && $objrs->fields["U_FORCEBILLING"]==0)) {
			$objGrid->setitem(null,"u_predischarge",1);
		} else {
			$objGrid->setitem(null,"u_predischarge",$objrs->fields["U_PREDISCHARGE"]);
		}	
		$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		if (!$page->paging_fetch()) break;
	}	
	
	$page->resize->addgrid("T1",20,170,false);
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

<script language="JavaScript">

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				return formView(null,"<?php echo $page->formid; ?>");
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
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	
	
	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
					case "u_req":
						var trxtype = "OP";
						//return formView(null,"./UDO.php?&objectcode=U_HISREQUESTS&df_u_trxtype="+getInput("u_trxtype"));
						var targetObjectId = 'U_HISREQUESTS';
						OpenLnkBtn(1024,570,'./udo.php?objectcode=U_HISREQUESTS' + '&df_u_trxtype='+trxtype + '&targetId=' + targetObjectId ,targetObjectId);
						break;
					case "u_chrg":
						if (getTableInput(table,"u_billing",row)=="1") {
							var trxtype = "OP";
							var targetObjectId = 'U_HISCHARGES';
							//return formView(null,"./UDO.php?&objectcode=U_HISCHARGES&df_u_trxtype="+getInput("u_trxtype"));
							OpenLnkBtn(1024,570,'./udo.php?objectcode=U_HISCHARGES' + '&df_u_trxtype='+trxtype + '&targetId=' + targetObjectId ,targetObjectId);
						} else {
							page.statusbar.showError("Patient is only allowed to make cash transactions.");
							return false;
						}	
						break;
				}
				break;
			default:
				switch (column) {
					case "u_mgh":
					case "u_expired":
						clearTable("T1");
						formPageReset(); break;
				}
				break;
		}		
		return true;
	}	
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
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
			inputs.push("u_patientname");
			inputs.push("u_mgh");
			inputs.push("u_expired");
			inputs.push("docstatus");
		return inputs;
	}
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		formSearch('','<?php echo $page->paging->formid; ?>');
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
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Out-Patient Registration - List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_department"],"") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_department"],array("loadudflinktable","u_hissections:code:name:u_type in ('OP','ER','IP','DIALYSIS')",":[All]")) ?> ></select></td>
	  <td width="168"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="168"><select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_patientname"],"") ?>>Patient Name</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_patientname"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
	  <td align=left><label <?php genCaptionHtml($schema["u_expired"],"") ?>>Expired</label></td>
	  <td align=left>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_expired"],"") ?> /><label class="lblobjs">All</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_expired"],"0") ?> /><label class="lblobjs">No</b></label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["u_expired"],"1") ?> /><label class="lblobjs">Yes</b></label></td>
	  </tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr ><td><label style="background-color:green" title="Active">&nbsp;</label><label style="background-color:orange" title="Pre-Discharge">&nbsp;</label><label style="background-color:gray" title="Expired">&nbsp;</label><label style="background-color:red" title="Allergies">&nbsp;</label><label style="background-color:black" title="Confidential">&nbsp;</label></td></tr>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
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
