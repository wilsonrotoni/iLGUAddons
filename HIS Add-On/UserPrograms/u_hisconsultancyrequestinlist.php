<?php
	$progid = "u_hisconsultancyrequestinlist";

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
	
	unset($enumdocstatus["D"]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISCONSULTANCYREQUESTINLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisconsultancyrequestinlist";
	$page->formid = "./UDO.php?&objectcode=U_HISCONSULTANCYFEES";
	$page->objectname = "Consultancy Fees";
	

	require("./inc/formactions.php");
	
	$schema["u_doctorid"] = createSchemaUpper("u_doctorid");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	
	$objrs = new recordset(null,$objConnection);
	$userdepartment = "";
/*
$objrs->queryopen("select DEPARTMENT FROM EMPLOYEES WHERE USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		if ($userdepartment!="") $schema["u_doctorid"]["attributes"] = "disabled";
	}
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","O");
		$page->setitem("u_doctorid",$userdepartment);
	}
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_DOCTORID",$filterExp,$httpVars['df_u_doctorid']);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs->queryopen("select A.DOCNO, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, B.NAME AS U_DOCTORNAME, A.U_ITEMDESC, A.DOCSTATUS from U_HISCONSULTANCYREQUESTS A, U_HISDOCTORS B WHERE B.CODE=A.U_DOCTORID AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->resize->addgrid("T1",20,140,false);
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
				if (getTableInput("T1","docstatus",p_rowIdx)=="O") {
					var targetObjectId = '';
					OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisconsultancyfees' + '' + '&targetId=' + targetObjectId ,targetObjectId);
					//setKey("keys",getTableKey("T1","keys",p_rowIdx));
					//return formView(null,"<?php echo $page->formid; ?>");
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
			case "u_doctorid":
			case "docstatus":
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_doctorid":
			case "df_docstatus":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_doctorid");
			inputs.push("docstatus");
		return inputs;
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
	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_doctorid"],"") ?>>Doctor</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["u_doctorid"],array("loadudflinktable","u_hisdoctors:code:name",":[All]")) ?> ></select></td>
	  <td width="168"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="168"><select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></select></td>
	</tr>
	

	
	
	<tr>
	  <td >&nbsp;</td>
	  <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>
	<div <?php genTableHDivHtml('HdrT1',false,1000) ?> ><table class="tableBox" style="border-bottom:0px;border-top:0px;border-right:0px;border-left:0px"  width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr class="tableBoxHead">
		<td width="96">&nbsp;Request No.&nbsp;</td>
		<td width="184">&nbsp;Doctor&nbsp;</td>
		<td width="240">&nbsp;Description&nbsp;</td>
		<td width="120">&nbsp;Patient ID&nbsp;</td>
		<td width="240">&nbsp;Patient Name&nbsp;</td>
        <td >&nbsp;Status&nbsp;</td>
  	</tr>
	</table></div>  
 <div <?php genTableHDivHtml('T1',true,1000,280,'',"'divHdrT1'") ?> > 
<table id="T1" class="tableBox" style="border-bottom:0px;border-top:0px;border-right:0px;border-left:0px" width="100%" border="0" cellspacing="0" cellpadding="0">
<?php  
	$ctr=0;
	while ($objrs->queryfetchrow('NAME')) {
		$ctr = $ctr + 1;
		$httpVars["df_rowstatT1r" . $ctr] = "E";
		$httpVars["df_docstatusT1r" . $ctr] = $objrs->fields["DOCSTATUS"];
		$httpVars["df_docnoT1r" . $ctr] = $objrs->fields["DOCNO"];
		$httpVars["sf_keysT1r" . $ctr] = $objrs->fields["DOCNO"] . '`0';
?> 
<tr  class="tableBoxRow" <?php genTableRowEventHtml("T1",$ctr) ?>>
<td width="96">&nbsp;<?php echo $objrs->fields["DOCNO"] ?>&nbsp;</td>
<td width="184">&nbsp;<?php echo $objrs->fields["U_DOCTORNAME"] ?>&nbsp;</td>
<td width="240"><?php genTableRowStatusHtml("T1r" . $ctr) ?>&nbsp;<?php echo @$objrs->fields["U_ITEMDESC"]?>&nbsp;<input type="hidden" <?php genInputHiddenDFHtml("docstatus","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("docno","T1r" . $ctr) ?> ></td>
<td width="120">&nbsp;<?php echo $objrs->fields["U_PATIENTID"] ?>&nbsp;</td>
<td width="240">&nbsp;<?php echo $objrs->fields["U_PATIENTNAME"] ?>&nbsp;</td>
<td >&nbsp;<?php echo $enumdocstatus[$objrs->fields["DOCSTATUS"]] ?>&nbsp;</td>
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
