<?php
	$progid = "u_hisbilltermsupdlist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/docgroups.php");
	include_once("./sls/users.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	unset($enumdocstatus["D"],$enumdocstatus["CN"],$enumdocstatus["O"],$enumdocstatus["C"]);
	$enumdocstatus["IP"] = "In-Patients";
	$enumdocstatus["OP"] = "Out-Patients";
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISIPS";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisbilltermsupdlist";
	$page->formid = "./UDO.php?&objectcode=U_HISBILLTERMSUPDS";
	$page->objectname = "Bill Term Updates";
	

	require("./inc/formactions.php");
	
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["u_refno"] = createSchemaUpper("u_refno");
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","IP");
	}
	$filterExp = "";
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_u_refno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);
	if ($page->getitemstring("docstatus")=="IP") {
		$objrs->queryopen("select A.DOCNO, A.U_PATIENTID, A.U_PATIENTNAME, A.U_DISCCODE, C.NAME AS U_DISCNAME, A.U_PAYMENTTERM, A.U_BILLING, A.U_PREPAID, B.NAME AS U_PAYMENTTERMNAME, A.U_BILLREMARKS, A.U_BILLTERMNO, A.U_TOTALCHARGES, U_BILLTERMBY from U_HISIPS A LEFT JOIN U_HISPAYMENTTERMS B ON B.CODE=A.U_PAYMENTTERM LEFT JOIN U_HISHEALTHINS C ON C.CODE=A.U_DISCCODE  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.DOCSTATUS NOT IN ('Discharged') $filterExp" . $page->paging_getlimit());
	} else {
		$objrs->queryopen("select A.DOCNO, A.U_PATIENTID, A.U_PATIENTNAME, A.U_DISCCODE, C.NAME AS U_DISCNAME, A.U_PAYMENTTERM, A.U_BILLING, A.U_PREPAID, B.NAME AS U_PAYMENTTERMNAME, A.U_BILLREMARKS, A.U_BILLTERMNO, A.U_TOTALCHARGES, U_BILLTERMBY from U_HISOPS A LEFT JOIN U_HISPAYMENTTERMS B ON B.CODE=A.U_PAYMENTTERM LEFT JOIN U_HISHEALTHINS C ON C.CODE=A.U_DISCCODE  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.DOCSTATUS NOT IN ('Discharged') $filterExp" . $page->paging_getlimit());
	}	
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->resize->addgrid("T1",20,180,false);
	$page->paging_recordcount($objrs->recordcount());
	
	$rptcols = 6; 
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

<script language="JavaScript">

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				var targetObjectId = 'u_hispatients';
				OpenLnkBtn(1024,540,'./udo.php?objectcode=u_hisbilltermsupds' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				/*		
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				return formView(null,"<?php echo $page->formid; ?>");
				//var targetObjectId = 'u_hispatients';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				*/
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
			case "df_u_refno":
			case "df_u_patientname":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
			case "df_u_refno":
			case "df_u_patientname":
			case "df_docstatus":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_department");
			inputs.push("u_patientname");
			inputs.push("u_refno");
			inputs.push("docstatus");
		return inputs;
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
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		formSearch('<?php echo $page->paging->formid; ?>');
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<?php genTableVarsHtml("T1") ?>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;<?php  echo $pageHeader ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["docstatus"],"") ?>>In/Out Patients</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","","")) ?> ></select></td>
	  <td width="168">&nbsp;</td>
		<td align=left width="168"></td>
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
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>
	<div <?php genTableHDivHtml('HdrT1',false,1000) ?> ><table class="tableBox" style="border-bottom:0px;border-top:0px;border-right:0px;border-left:0px"  width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr class="tableBoxHead">
		<td width="120">&nbsp;No.&nbsp;</td>
		<td width="120">&nbsp;Patient ID&nbsp;</td>
		<td width="240">&nbsp;Patient Name&nbsp;</td>
		<td width="116">&nbsp;Term&nbsp;</td>
		<td width="116" align="right">&nbsp;Total Bill&nbsp;</td>
		<td width="96">&nbsp;Discount&nbsp;</td>
		<td width="96">&nbsp;By&nbsp;</td>
		<td >&nbsp;Remarks&nbsp;</td>
  	</tr>
	</table></div>  
 <div <?php genTableHDivHtml('T1',true,1000,280,'',"'divHdrT1'") ?> > 
<table id="T1" class="tableBox" style="border-bottom:0px;border-top:0px;border-right:0px;border-left:0px" width="100%" border="0" cellspacing="0" cellpadding="0">
<?php  
	$ctr=0;
	while ($objrs->queryfetchrow('NAME')) {
		$ctr = $ctr + 1;
		$httpVars["df_rowstatT1r" . $ctr] = "E";
		$httpVars["df_docnoT1r" . $ctr] = $objrs->fields["DOCNO"];
		$httpVars["df_u_patientidT1r" . $ctr] = $objrs->fields["U_PATIENTID"];
		$httpVars["df_u_patientnameT1r" . $ctr] = $objrs->fields["U_PATIENTNAME"];
		$httpVars["df_u_paymenttermT1r" . $ctr] = $objrs->fields["U_PAYMENTTERM"];
		$httpVars["df_u_disccodeT1r" . $ctr] = $objrs->fields["U_DISCCODE"];
		$httpVars["df_u_billingT1r" . $ctr] = $objrs->fields["U_BILLING"];
		$httpVars["df_u_prepaidT1r" . $ctr] = $objrs->fields["U_PREPAID"];
		$httpVars["sf_keysT1r" . $ctr] = $objrs->fields["U_BILLTERMNO"] . '`0';
?> 
<tr  class="tableBoxRow" <?php genTableRowEventHtml("T1",$ctr) ?>>
<td width="120">&nbsp;<?php echo $objrs->fields["DOCNO"] ?>&nbsp;</td>
<td width="120"><?php genTableRowStatusHtml("T1r" . $ctr) ?>&nbsp;<?php echo @$objrs->fields["U_PATIENTID"]?>&nbsp;<input type="hidden" <?php genInputHiddenDFHtml("docno","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_patientid","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_patientname","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_paymentterm","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_disccode","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_billing","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_prepaid","T1r" . $ctr) ?> ></td>
<td width="240">&nbsp;<?php echo $objrs->fields["U_PATIENTNAME"] ?>&nbsp;</td>
<td width="116">&nbsp;<?php echo $objrs->fields["U_PAYMENTTERMNAME"] ?>&nbsp;</td>
<td width="116" align="right">&nbsp;<?php echo formatNumericAmount($objrs->fields["U_TOTALCHARGES"]) ?>&nbsp;</td>
<td width="96">&nbsp;<?php echo $objrs->fields["U_DISCNAME"] ?>&nbsp;</td>
<td width="96">&nbsp;<?php echo $objrs->fields["U_BILLTERMBY"] ?>&nbsp;</td>
<td >&nbsp;<?php echo $objrs->fields["U_BILLREMARKS"] ?>&nbsp;</td>
</tr>
<?php  
		if (!$page->paging_fetch()) break;

//		if ($page->ispagebreak()) break;
	}
 ?> 
 </table>
 </div>
</td></tr>
<?php $page->writeRecordLimit(); ?>
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
