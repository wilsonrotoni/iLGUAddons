<?php
	$progid = "u_hisipdietoutlist";

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
	include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
	
	unset($enumdocstatus["D"],$enumdocstatus["CN"],$enumdocstatus["O"],$enumdocstatus["C"]);
	$enumdocstatus["Active"] = "Active";
	$enumdocstatus["Discharged"] = "Discharged";
	$enumdocstatus["MGH"] = "MGH";
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISIPS";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisipdietoutlist";
	$page->formid = "./UDO.php?&objectcode=U_HISDIETS";
	$page->objectname = "Dietary";
	

	require("./inc/formactions.php");
	
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["u_requestdate"] = createSchemaDate("u_requestdate");
	$schema["u_meal"] = createSchemaUpper("u_meal");
	$schema["u_diettype"] = createSchemaUpper("u_diettype");
	
	if (!isset($httpVars['df_u_requestdate'])) {
		$page->setitem("u_requestdate",currentdate());
		
		$u_hissetupdata = getu_hissetup("U_DIETARY_CUTOFF1,U_DIETARY_CUTOFF2,U_DIETARY_CUTOFF3");
		
		if ($u_hissetupdata["U_DIETARY_CUTOFF1"]!="" && date('H:i')<=$u_hissetupdata["U_DIETARY_CUTOFF1"]) $page->setitem("u_meal","Breakfast");
		else if ($u_hissetupdata["U_DIETARY_CUTOFF2"]!="" && date('H:i')<=$u_hissetupdata["U_DIETARY_CUTOFF2"]) $page->setitem("u_meal","Lunch");
		else $page->setitem("u_meal","Dinner");
		
	}
	$filterExp = "";
	$haveExp = "";
	$joinExp = "";
	$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
	$filterExp = genSQLFilterString("C.U_DIETTYPE",$filterExp,$httpVars['df_u_diettype']);
	if ($httpVars['df_u_meal']=="") {
		$haveExp = " HAVING ISNULL(U_MEAL)";
	} else {
		$haveExp = " HAVING ISNULL(U_MEAL) or U_MEAL='".$httpVars['df_u_meal']."'";
		$joinExp = genSQLFilterString("C.U_MEAL",$filterExp,$httpVars['df_u_meal']);
	}
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	if ($joinExp !="") $joinExp = " AND " . $joinExp;
	$objrs = new recordset(null,$objConnection);
	if ($httpVars['df_u_requestdate']!="") {
		$objrs->queryopen("select A.DOCNO, A.U_DEPARTMENT, A.U_BEDNO, A.U_PATIENTID, A.U_PATIENTNAME, A.U_RELIGION, A.U_AGE_Y, A.U_HEIGHT_FT, A.U_HEIGHT_IN, A.U_WEIGHT_KG, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, C.DOCNO AS U_REQUESTNO, C.U_MEAL, C.U_DIETTYPE, C.U_REQUESTDATE, C.U_REQUESTTIME, C.U_REMARKS from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT LEFT OUTER JOIN U_HISDIETS C ON C.COMPANY=A.COMPANY AND C.BRANCH=A.BRANCH AND C.U_PATIENTID=A.U_PATIENTID AND C.U_REQUESTDATE='".$page->getitemdate("u_requestdate")."' $joinExp WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_NURSED=1 AND A.DOCSTATUS NOT IN ('Discharged') $filterExp" . $page->paging_getlimit() . " $haveExp ORDER BY U_REQUESTTIME, U_PATIENTNAME");
	}	
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->resize->addgrid("T1",20,160,false);
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
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				//return formView(null,"<?php echo $page->formid; ?>");
				var targetObjectId = 'u_hisdiets';
				OpenLnkBtn(1024,250,'./udo.php?objectcode=u_hisdiets' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "u_hisdiets":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "u_department":
			case "u_meal":
			case "u_diettype":
				clearTable("T1");
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_requestdate":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
			case "df_u_meal":
			case "df_u_diettype":
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_department");
			inputs.push("u_requestdate");
			inputs.push("u_meal");
			inputs.push("u_diettype");
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
	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader ?></td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_department"],"") ?>>Section</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["u_department"],array("loadudflinktable","u_hissections:code:name:u_type in ('IP')",":[All]")) ?> ></select></td>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_requestdate"],"") ?>>Request Date</label></td>
	  <td  width="168" align=left><input type="text" size="15" <?php genInputTextHtml($schema["u_requestdate"]) ?> /></td>
	</tr>
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_meal"],"") ?>>Meal</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["u_meal"],array("loadudfenums","u_hisdiets:meal","")) ?> ></select></td>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_diettype"],"") ?>>Type of Diet</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["u_diettype"],array("loadudflinktable","u_hisdiettypes:code:name",":[All]")) ?> ></select></td>
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
		<td width="108">&nbsp;No.&nbsp;</td>
		<td width="160">&nbsp;Section&nbsp;</td>
		<td width="104">&nbsp;Room/Bed No.&nbsp;</td>
		<td width="96">&nbsp;Patient ID&nbsp;</td>
		<td width="240">&nbsp;Patient Name&nbsp;</td>
		<td width="96">&nbsp;Religion&nbsp;</td>
		<td width="40">&nbsp;Age&nbsp;</td>
		<td width="56">&nbsp;Height&nbsp;</td>
		<td width="80">&nbsp;Weight(Kg)&nbsp;</td>
		<td width="48">&nbsp;Time&nbsp;</td>
		<td width="64">&nbsp;Meal&nbsp;</td>
		<td width="120">&nbsp;Diet&nbsp;</td>
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
		$httpVars["df_u_departmentT1r" . $ctr] = $objrs->fields["U_DEPARTMENT"];
		$httpVars["df_u_reftypeT1r" . $ctr] = "IP";
		$httpVars["df_u_refnoT1r" . $ctr] = $objrs->fields["DOCNO"];
		$httpVars["df_u_patientidT1r" . $ctr] = $objrs->fields["U_PATIENTID"];
		$httpVars["df_u_patientnameT1r" . $ctr] = $objrs->fields["U_PATIENTNAME"];
		$httpVars["df_u_bednoT1r" . $ctr] = $objrs->fields["U_BEDNO"];
		$httpVars["df_u_religionT1r" . $ctr] = $objrs->fields["U_RELIGION"];
		$httpVars["df_u_ageT1r" . $ctr] = $objrs->fields["U_AGE_Y"];
		$httpVars["df_u_height_ftT1r" . $ctr] = $objrs->fields["U_HEIGHT_FT"];
		$httpVars["df_u_height_inT1r" . $ctr] = $objrs->fields["U_HEIGHT_IN"];
		$httpVars["df_u_weight_kgT1r" . $ctr] = $objrs->fields["U_WEIGHT_KG"];
		$httpVars["sf_keysT1r" . $ctr] = $objrs->fields["U_REQUESTNO"] . '`0';
?> 
<tr  class="tableBoxRow" <?php genTableRowEventHtml("T1",$ctr) ?>>
<td width="108">&nbsp;<?php echo $objrs->fields["DOCNO"] ?>&nbsp;</td>
<td width="160">&nbsp;<?php echo $objrs->fields["DEPARTMENTNAME"] ?>&nbsp;</td>
<td width="104">&nbsp;<?php echo $objrs->fields["U_BEDNO"] ?>&nbsp;</td>
<td width="96"><?php genTableRowStatusHtml("T1r" . $ctr) ?>&nbsp;<?php echo @$objrs->fields["U_PATIENTID"]?>&nbsp;<input type="hidden" <?php genInputHiddenDFHtml("u_department","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_patientid","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_patientname","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_reftype","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_refno","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_religion","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_bedno","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_age","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_height_ft","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_height_in","T1r" . $ctr) ?> ><input type="hidden" <?php genInputHiddenDFHtml("u_weight_kg","T1r" . $ctr) ?> ></td>
<td width="240">&nbsp;<?php echo $objrs->fields["U_PATIENTNAME"] ?>&nbsp;</td>
<td width="96">&nbsp;<?php echo $objrs->fields["U_RELIGION"] ?>&nbsp;</td>
<td width="40">&nbsp;<?php echo $objrs->fields["U_AGE_Y"] ?>&nbsp;</td>
<td width="56">&nbsp;<?php echo iif($objrs->fields["U_HEIGHT_FT"]<>0,$objrs->fields["U_HEIGHT_FT"] . "'","") . iif($objrs->fields["U_HEIGHT_IN"]<>0,$objrs->fields["U_HEIGHT_IN"] . '"',"") ?>&nbsp;</td>
<td width="80">&nbsp;<?php echo formatNumericAmount($objrs->fields["U_WEIGHT_KG"]) ?>&nbsp;</td>
<td width="48">&nbsp;<?php echo substr($objrs->fields["U_REQUESTTIME"],0,5) ?>&nbsp;</td>
<td width="64">&nbsp;<?php echo $objrs->fields["U_MEAL"] ?>&nbsp;</td>
<td width="120">&nbsp;<?php echo $objrs->fields["U_DIETTYPE"] ?>&nbsp;</td>
<td >&nbsp;<?php echo $objrs->fields["U_REMARKS"] ?>&nbsp;</td>
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
