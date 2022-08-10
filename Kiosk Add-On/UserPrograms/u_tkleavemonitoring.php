<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkleavemonitoring";
	
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
	$page->paging->formid = "./UDP.php?objectcode=u_tkleavemonitoring";
	$page->formid = "./UDO.php?objectcode=u_tkleavemonitoring";
	
	$filter="";

	$schema["empid"] = createSchema("empid");
	$schema["department"] = createSchema("department");
	$schema["date1"] = createSchemaDate("date1");
	$schema["date2"] = createSchemaDate("date2");
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("empid");
	$objGrid->addcolumn("empname");
	$objGrid->addcolumn("dateleave");
	$objGrid->addcolumn("reason");
	
	$objGrid->columntitle("empid","Employee ID");
	$objGrid->columntitle("empname","Employee Name");
	$objGrid->columntitle("dateleave","Date Leave");
	$objGrid->columntitle("reason","Reason");
	
	$objGrid->columnwidth("empid",15);
	$objGrid->columnwidth("empname",25);
	$objGrid->columnwidth("dateleave",12);
	$objGrid->columnwidth("reason",50);
	
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "a.u_empid, b.u_date_filter";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	function loadu_empid($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_PREMPLOYMENTINFO group by NAME");
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
	
	function loadu_dept($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_PRDEPARTMENT group by CODE");
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
		
	$filterExp = genSQLFilterString("b.u_date_filter",$filterExp,formatDateToDB($httpVars['df_date1']),formatDateToDB($httpVars['df_date2']));
	$filterExp = genSQLFilterString("a.u_empid",$filterExp,$httpVars['df_empid']);
	$filterExp = genSQLFilterString("c.u_deptcode",$filterExp,$httpVars['df_department']);
	
	if ($filterExp !="") { 
		$filterExp = " AND " . $filterExp;
	}
	
	$objrs = new recordset(null,$objConnection);
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
		if($filterExp !="") {
		$objrs->queryopenext("SELECT a.u_empid,
									   a.u_empname,
									   b.u_date_filter,
									   a.u_leavereason
								
								FROM u_tkrequestformleave a
								INNER JOIN u_tkrequestformleavegrid b ON b.company = a.company
								  AND b.branch = a.branch
								  AND b.code = a.code
								INNER JOIN u_premploymentdeployment c ON c.company = a.company
								  AND c.branch = a.branch
								  AND c.code = a.u_empid
								  AND c.u_branch = a.u_profitcenter 
								WHERE a.company = '$company' 
								  AND a.branch = '$branch' 
								  AND a.u_status = '1' $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		} else {
		$objrs->queryopenext("SELECT a.u_empid,
									   a.u_empname,
									   b.u_date_filter,
									   a.u_leavereason
								
								FROM u_tkrequestformleave a
								INNER JOIN u_tkrequestformleavegrid b ON b.company = a.company
								  AND b.branch = a.branch
								  AND b.code = a.code
								INNER JOIN u_premploymentdeployment c ON c.company = a.company
								  AND c.branch = a.branch
								  AND c.code = a.u_empid
								  AND c.u_branch = a.u_profitcenter 
								WHERE a.company = '' 
								  AND a.branch = '' 
								  AND a.u_status = 'X'",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");	
		}
		
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"empid",$objrs->fields["u_empid"]);
			$objGrid->setitem(null,"empname",$objrs->fields["u_empname"]);
			$objGrid->setitem(null,"dateleave",formatDateToHttp($objrs->fields["u_date_filter"]));
			$objGrid->setitem(null,"reason",$objrs->fields["u_leavereason"]);
			if (!$page->paging_fetch()) break;
		}	
	}
	
	$rptcols = 6; 
	$page->toolbar->setaction("update",false);
	$page->toolbar->setaction("print",false);
	//$page->toolbar->addbutton("approved","Update","formSubmit('u_update')","right");
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
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>

<script language="JavaScript">
	
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
	
	/*function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setInput("afdocno",getTableInput(p_tableId,"docno",p_rowIdx));
				setInput("studentname",getTableInput(p_tableId,"u_studentname",p_rowIdx));
				showPopupFrame("popupFrameUserForm");
				
				break;
		}		
		return false;
	}*/
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_date1":
			case "df_date2":
			case "df_department":
			case "df_empid":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("empid");
			inputs.push("date1");
			inputs.push("date2");
			inputs.push("department");
		return inputs;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
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
	  <td class="labelPageHeader" >&nbsp;Leave Monitoring&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td ><label <?php genCaptionHtml($schema["date1"],"") ?>>Date From</label></td>
	  <td align=left><input type="text" size="12" <?php genInputTextHtml($schema["date1"]) ?> /></td>
      <td width="168"><label <?php genCaptionHtml($schema["empid"],"") ?>>Employee Name</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["empid"],array("loadu_empid","",":")) ?> ></select></td>
    </tr>
    <tr>
	  <td ><label <?php genCaptionHtml($schema["date2"],"") ?>>Date To</label></td>
	  <td align=left><input type="text" size="12" <?php genInputTextHtml($schema["date2"]) ?> /></td>
	  <td width="168"><label <?php genCaptionHtml($schema["department"],"") ?>>Department</label></td>
	  <td align=left><select <?php genSelectHtml($schema["department"],array("loadu_dept","",":")) ?> ></select>
      	  &nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;</td>
	</tr>
</table></td></tr>		
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw(true) ?></td></tr>
<?php $page->resize->addgridobject($objGrid,20,250) ?>
<?php $page->writeRecordLimit(); ?>
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

<script>
resizeObjects();
</script>
