<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_prpayslipformper";
	
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
	$page->paging->formid = "./UDP.php?objectcode=u_prpayslipformper";
	$page->formid = "./UDO.php?objectcode=u_prpayslipform";
	
	$filter="";

	$schema["batchcode"] = createSchemaUpper("batchcode");
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("code");
	$objGrid->addcolumn("u_payrolldue");
	$objGrid->addcolumn("u_empid");
	$objGrid->addcolumn("u_empname");
	$objGrid->addcolumn("u_totalamount");
	
	$objGrid->columntitle("code","Batch No.");
	$objGrid->columntitle("u_payrolldue","Payroll Due");
	$objGrid->columntitle("u_empid","Employee No.");
	$objGrid->columntitle("u_empname","Employee Name");
	$objGrid->columntitle("u_totalamount","Total Net Pay Amount");
	
	$objGrid->columnwidth("code",25);
	$objGrid->columnwidth("u_payrolldue",20);
	$objGrid->columnwidth("u_empid",20);
	$objGrid->columnwidth("u_empname",35);
	$objGrid->columnwidth("u_totalamount",20);
	
	$objGrid->columnalignment("u_totalamount","right");
	
	$objGrid->selectionmode = 2;
	$objGrid->automanagecolumnwidth = false;
	
	$objGrid->columnsortable("code",true);
	$objGrid->columnsortable("u_empid",true);
	$objGrid->columnsortable("u_empname",true);
	
	$objGrid->width = 1200;
	$objGrid->height = 300;
	
	$objGrid->columnlnkbtn("code","OpenLnkBtnu_refnoprint()");
	
	function loadu_batch($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select U_BATCHCODE,CONCAT('PayDay For These Cut-Off : ',DATE_FORMAT(u_payrolldue,'%M %d,%Y')) as NAME from U_PRPAYSLIPFORM WHERE u_status = 'A' AND u_empid = '".$_SESSION["userid"]."' group by U_BATCHCODE");
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
	
	$filterExp = genSQLFilterString("u_batchcode",$filterExp,$httpVars['df_batchcode']);
	
	if ($filterExp !="") { 
		$filterExp = " AND " . $filterExp;
	}
	
	$objrs = new recordset(null,$objConnection);
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
		$objRs->setdebug();
		if($filterExp != "") {
			$objrs->queryopenext("SELECT code,u_payrolldue,u_empid,u_empname,u_netpay FROM u_prpayslipform WHERE company = '$company' AND branch = '$branch' AND u_status = 'A' AND u_empid = '".$_SESSION["userid"]."' $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		} else {
			$objrs->queryopenext("SELECT code,u_payrolldue,u_empid,u_empname,u_netpay FROM u_prpayslipform WHERE company = '$company' AND branch = '$branch' AND u_status = 'A' AND u_batchcode = ''  $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");	
		}
		
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"code",$objrs->fields["code"]);
			$objGrid->setitem(null,"u_empid",$objrs->fields["u_empid"]);
			$objGrid->setitem(null,"u_payrolldue",$objrs->fields["u_payrolldue"]);
			$objGrid->setitem(null,"u_empname",$objrs->fields["u_empname"]);
			$objGrid->setitem(null,"u_totalamount",formatNumericAmount($objrs->fields["u_netpay"]));
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
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>	
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		if("<?php echo $_SESSION["menunavigator"] ?>" == "topnav"){
			alert("SESSION expired.. system autolog-out..");
			logOut();
		}
	}

	function onElementClick(element,column,table,row) {
		var result;
		switch(table) {
			case "T1":
				switch (column) {
					case "checked":
						if (row==0) {
							if (isTableInputChecked(table,column)) { 
								checkedTableInput(table,column,-1);
								computeTotalFees();
							} else {
								uncheckedTableInput(table,column,-1); 
								computeTotalFees();
							}
						}	
						break;
				}
				break;
			default:
				break;
		}					
		return true;
	}
	
	function onFormSubmit(action) {
		if(action == "u_update"){
			if (isInputEmpty("batchcode")) return false;
		}
		return true;
	}
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				computeTotalFees();
				break;
		}		
		return params;
	}
	
	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				return formView(null,"<?php echo $page->formid; ?>");
				break;
		}		
		return false;
	}
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_batchcode":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("batchcode");
		return inputs;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "batchcode":
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
	
	function OpenLnkBtnu_refnoprint(targetId) {
		OpenLnkBtn(1300,580,'./udo.php?objectcode=u_prpayslipform'  + '&opt=viewonly' +  '&targetId=' + targetId ,targetId);
	
	}
	
	function computeTotalFees() {
		var rc1 =  getTableRowCount("T1"), totalamount=0.000;
		
		for (i = 1; i <= rc1; i++) {
			if (isTableRowDeleted("T1",i)==false) {
				if(getTableInput("T1","checked",i) == "1") {
					totalamount += getTableInputNumeric("T1","u_totalamount",i);
				}
			}
		}
		setInputAmount("amt",totalamount);
	}
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Kiosk Payslip List Per Batch Per User&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	  <td width="168"><label <?php genCaptionHtml($schema["batchcode"],"") ?>>Batch Code</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["batchcode"],array("loadu_batch","",":")) ?> ></select></td>
	</tr>
    <tr>
	  <td width="168">&nbsp;</td>
	  <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;</td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw(true) ?></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr ><td align="right">&nbsp;</td></tr>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
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
