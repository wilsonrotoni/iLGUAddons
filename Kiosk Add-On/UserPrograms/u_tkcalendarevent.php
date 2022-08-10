<?php
	$progid = "u_tkcalendarevent";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/enumjedoctypes.php");
	include_once("./sls/docgroups.php");
	include_once("./sls/users.php");
	include_once("./sls/enumpostflag.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "u_tkcalendarevent";
	$page->paging->formid = "./UDP.php?objectcode=u_tkcalendarevent";
	$page->formid = "./UDO.php?objectcode=u_tkcalendarevent";
	$page->objectname = "Employee List";
	

	require("./inc/formactions.php");
	
	$schema["dates"] = createSchemaDate("dates");
	$schema["empid"] = createSchema("empid");
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("code");
	$objGrid->addcolumn("name");
	$objGrid->addcolumn("desc");

	$objGrid->columntitle("code","Request No.");
	$objGrid->columntitle("name","Event Description");
	$objGrid->columntitle("desc","Request Description");
	
	$objGrid->columnwidth("code",15);
	$objGrid->columnwidth("name",35);
	$objGrid->columnwidth("desc",30);
	$objGrid->automanagecolumnwidth = false;
	
	$objGrid->width = 1200;
	$objGrid->height = 350;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "code";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	$filterExp = "";
	
	$filterExp = genSQLFilterString("name",$filterExp,$httpVars['df_dates']);
	$filterExp = genSQLFilterString("code",$filterExp,$httpVars['df_empid']);
	
	if ($filterExp !="") { 
		$filterExp = " AND " . $filterExp;
	}
	
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
	$objrs->queryopenext("SELECT code,name,u_biometrixid FROM u_premploymentinfo WHERE company = '$company' AND branch = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	//	var_dump($filterExp);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"code",$objrs->fields["code"]);
		$objGrid->setitem(null,"name",$objrs->fields["name"]);
		$objGrid->setitem(null,"desc",$objrs->fields["desc"]);
		$objGrid->setkey(null,$objrs->fields["code"]);
		
		if (!$page->paging_fetch()) break;
	}	
		
	$page->toolbar->setaction("print",false);
	$rptcols = 6; 
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

	/*function onPageLoad() {
		try {
			if (getVar("formSubmitAction")=="a") {
				setInput("u_docno",window.opener.getTableInput("T1","u_daystime",window.opener.getTableSelectedRow("T1")));
			}
		} catch (theError) {
		}
	}*/
	
	/*function onFormSubmit(action) {
		if(action == "new_emp"){
			return formView(null,"./UDO.php?objectcode=u_premploymentinfo");
		}
		return true;
	}*/

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	/*function onElementChange(element,column,table,row) {
		switch (column) {
			case "doctype": 
			case "docgroup": 
				formPageReset(); 
				break;	
		}
		return true;
	}*/	
	
	/*function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				return formView(null,"<?php echo $page->formid; ?>");
				//var targetObjectId = 'u_hispatients';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}*/
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "dates":
			case "empid":
				formPageReset(); break;
				break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_dates":
			case "df_empid"::return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("dates");
			inputs.push("empid");
		return inputs;
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "dates":
			case "empid":
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
	  <td class="labelPageHeader" >&nbsp;Employee Master File List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["dates"],"") ?>>Date Event</label></td>
	  <td  align=left><input type="text" size="12" <?php genInputTextHtml($schema["dates"]) ?> /></td>
	</tr>
    <tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["code"],"") ?>>Code</label></td>
	  <td  align=left><input type="text" size="12" <?php genInputTextHtml($schema["code"]) ?> /></td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr >
<td align="right">&nbsp;</td>
</tr>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
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
