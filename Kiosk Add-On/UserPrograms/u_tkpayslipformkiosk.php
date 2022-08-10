<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkpayslipformkiosk";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/enummonth.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	
	$page->restoreSavedValues();
	
	$page->objectcode = $progid;
	$page->paging->formid = "./UDP.php?objectcode=u_tkpayslipformkiosk";
	$page->formid = "./UDO.php?objectcode=u_prpayslipform";
	
	$schema["datedue"] = createSchemaDate("datedue");
	$schema["currency"] = createSchemaUpper("currency");
	$schema["u_userid"] = createSchemaAny("u_userid");
	$schema["u_password"] = createSchemaAny("u_password");
	
	$schema["u_userid"]["attributes"] = "disabled";
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("code");
	$objGrid->addcolumn("month_type");
	$objGrid->addcolumn("payrolldue");
	$objGrid->addcolumn("currency");
	
	$objGrid->columntitle("month_type","Month Type");
	$objGrid->columntitle("payrolldue","Payroll Due");
	$objGrid->columntitle("currency","Currency");
	
	$objGrid->columnwidth("month_type",15);
	$objGrid->columnwidth("payrolldue",12);
	$objGrid->columnwidth("currency",20);
	
	$objGrid->columnvisibility("code",false);
	
	$objGrid->automanagecolumnwidth = false;
	$objGrid->width = 700;
	$objGrid->height = 300;
	
	function loadu_batch($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("SELECT currency,CONCAT(currency,' : ',currencyname) FROM currencies");
			if ($obj->recordcount() > 0) {
				while ($obj->queryfetchrow()) {
					$_selected = "";
					if ($p_selected == $obj->fields[0]) $_selected = "selected";
					$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
				}
			}	
			$obj->queryclose();
		echo @$_html;
	}
	
	require("./inc/formactions.php");
		
	$filterExp = "";
	
	$filterExp = genSQLFilterString("a.u_payrolldue",$filterExp,formatDateToDB($httpVars['df_datedue']));
	$filterExp = genSQLFilterString("a.u_currency",$filterExp,($httpVars['df_currency']));
	
	if ($filterExp !="") { 
		$filterExp = " AND " . $filterExp;
	}
	
	$objrs = new recordset(null,$objConnection);
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
		$objRs->setdebug();
		$objrs->queryopenext("SELECT a.code,a.u_payrolldue,a.u_currency,IF(c.u_terms = 1,'Mid-Month','Month-End') as months
								FROM u_prpayslipform a
								INNER JOIN u_premploymentinfo b ON b.company = a.company
								  AND b.branch = a.branch
								  AND b.code = a.u_empid
								INNER JOIN u_prpayrollperioddates c ON c.company = a.company
								  AND c.branch = a.branch
								  AND c.u_cut_date = a.u_payrolldue
								  AND RIGHT(c.code,2) = b.u_payfrequency WHERE a.company = '$company' AND a.branch = '$branch' AND a.u_status = 'A' AND a.u_empid = '".$_SESSION["userid"]."' $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"code",$objrs->fields["code"]);
			$objGrid->setitem(null,"month_type",$objrs->fields["months"]);
			$objGrid->setitem(null,"payrolldue",formatDateToHttp($objrs->fields["u_payrolldue"]));
			$objGrid->setitem(null,"currency",$objrs->fields["u_currency"]);
			$objGrid->setkey(null,$objrs->fields["code"]);
		}	
	}

	$page->toolbar->setaction("update",false);
	$page->toolbar->setaction("print",false);
	//$page->toolbar->addbutton("approved","Delete","formSubmit('u_update')","right");
	if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf" || $_SESSION["theme"]=="gps") { 
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("SELECT u_font FROM u_prglobalsetting");
		while ($objRs->queryfetchrow("NAME")) {
			$csspath =  "../Addons/GPS/PayRoll Add-On/UserPrograms/".$objRs->fields["u_font"]."/";
		}
	}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
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
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		if("<?php echo $_SESSION["menunavigator"] ?>" == "topnav"){
			alert("SESSION expired.. system autolog-out..");
			logOut();
		}
	}
	
	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setInput("u_userid",getGlobal("userid"));
				setInput("u_password","");
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				showPopupFrame("popupFrameAuthentication",true);
				return false;
				break;
		}		
		return false;
	}
	
	function AuthenticationProcess(id) {
		if(id == "u_show"){
			var result = page.executeFormattedQuery("SELECT groupid FROM users WHERE userid='"+getInput("u_userid")+"' and hpwd= md5('"+getInput("u_password")+"') and groupid = 'KIOSK'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						return formView(null,"<?php echo $page->formid; ?>");
					} else {
						page.statusbar.showError("Invalid user or password. For KIOSK PERSONAL ONLY");
						return false;
					}
				} else {
					page.statusbar.showError("Error retrieving.. Try Again, if problem persists, check the connection.");	
					return false;
				}
		}
		return true;
	}
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				break;
		}		
		return params;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_datedue":
			case "df_currency":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("datedue");
			inputs.push("currency");
		return inputs;
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "datedue":
			case "currency":
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
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Kiosk Payslip&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	  <td width="168"><label <?php genCaptionHtml($schema["datedue"],"") ?>>Payroll Due</label></td>
	  <td ><input type="text" size="12" <?php genInputTextHtml($schema["datedue"]) ?> /></td>
	</tr>
    <tr>
	  <td width="168"><label <?php genCaptionHtml($schema["currency"],"") ?>>Currency</label></td>
	  <td ><select <?php genSelectHtml($schema["currency"],array("loadu_batch","",":")) ?> ></select></td>
	</tr>
    <tr>
	  <td width="168">&nbsp;</td>
	  <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;</td>
	</tr>
    <tr>
	  <td colspan="2">&nbsp;<?php $objGrid->draw(true) ?></td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>&nbsp;</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr ><td align="right">&nbsp;</td></tr>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
<div <?php genPopWinHDivHtml("popupFrameAuthentication","Authentication",10,30,350,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="128"><label <?php genCaptionHtml($schema["u_userid"]) ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml($schema["u_userid"]) ?> /></td>
		</tr>
		<tr><td width="128"><label <?php genCaptionHtml($schema["u_password"]) ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml($schema["u_password"]) ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="AuthenticationProcess('u_show');return false;">Proceed</a></td>
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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . $page->toolbar->generateQueryString(). "&toolbarscript=./GenericGridMasterDataToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>