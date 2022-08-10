<?php
	$progid = "u_listBudgetRequests";

	$progaccessid = "u_lguppmp";
	
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/docgroups.php");
	include_once("./sls/users.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");

	$enumpayfor = array();
	$enumpayfor["D"] = "Debit Memo";
	$enumpayfor["C"] = "Credit Memo";
	
	function loadenumpayfor($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumpayfor;
		reset($enumpayfor);
		while (list($key, $val) = each($enumpayfor)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	$enumdocstatus = array();
	
	$enumdocstatus["Draft"] = "Draft";
	$enumdocstatus["For Approval"] = "For Approval";
	$enumdocstatus["For Revision"] = "For Revision";
	$enumdocstatus["Approved"] = "Approved";
	$enumdocstatus["Disapproved"] = "Disapproved";


	$page->restoreSavedValues();	
	
	$page->objectcode = "u_tcsacctlist";
	$page->paging->formid = "./UDP.php?&objectcode=u_listBudgetRequests";
	$page->formid = "./UDO.php?&objectcode=U_LGUPPMP";
	$page->objectname = "Budget Requests List";
	
	$schema["u_profitcenter"] = createSchemaUpper("u_profitcenter");
	$schema["u_profitcentername"] = createSchemaUpper("u_profitcentername");
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_bpname"] = createSchemaUpper("u_bpname");
	$schema["u_trxtype"] = createSchemaUpper("u_trxtype");
	$schema["u_trxsource"] = createSchemaUpper("u_trxsource");
	$schema["showdetails"] = createSchemaUpper("showdetails");
	$schema["u_year"] = createSchema("u_year");
	$schema["u_dateto"] = createSchemaDate("u_dateto");
	$schema["u_discondatefr"] = createSchemaDate("u_discondatefr");
	$schema["u_discondateto"] = createSchemaDate("u_discondateto");
	$schema["u_recondatefr"] = createSchemaDate("u_recondatefr");
	$schema["u_recondateto"] = createSchemaDate("u_recondateto");
	$schema["u_lapsedatefr"] = createSchemaDate("u_lapsedatefr");
	$schema["u_lapsedateto"] = createSchemaDate("u_lapsedateto");

	$objrs = new recordset(null,$objConnection);

	$objGrid = new grid("T1",$httpVars,true);
	//$objGrid->addcolumn("u_time");
	$objGrid->addcolumn("u_yr");
	$objGrid->addcolumn("u_profitcenter");
	$objGrid->addcolumn("u_profitcentername");
	$objGrid->addcolumn("u_totalamount");
	$objGrid->addcolumn("docstatus","docstatusname");
	$objGrid->columntitle("u_yr","Year");
	$objGrid->columntitle("u_profitcenter","Profit Center");
	$objGrid->columntitle("u_profitcentername","Profit Center Name");
	$objGrid->columntitle("u_totalamount","Total Amount");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("u_yr",5);
	$objGrid->columnwidth("u_profitcenter",10);
	$objGrid->columnwidth("u_profitcentername",50);
	$objGrid->columnwidth("u_totalamount",12);
	$objGrid->columnwidth("docstatus",10);
	$objGrid->columnalignment("u_totalamount","right");
	$objGrid->columnsortable("u_profitcenter",true);
	$objGrid->selectionmode=2;
	//$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_yr, u_profitcenter";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	$groupby = "";
	$isgroup = false;
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		global $groupby;
		global $isgroup;
		global $lookupSortBy;
		global $objGrid;
		global $page;
		//if ($lookupSortBy=="docno") {
			switch ($column) {
				case "u_date":
					//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
					if ($groupby!=$objGrid->getitemstring($row,"docno")) {
						$groupby = $objGrid->getitemstring($row,"docno");
						$isgroup = false;
					} else {
						$isgroup = true;
						$label = "";
					}	
					break;
				case "u_profitcenter":
				case "u_profitcentername":
				case "u_acctno":
				case "docno":
				case "u_bpname":
				case "u_trxtype":
				case "docstatus":
				case "u_totalamount":
					if ($isgroup) $label = "";
					break;
			}
		//}
	}
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $page;
		global $objGrid;
		$actionReturn = true;
		
		$obju_LGUPPMP = new documentschema_br(null,$objConnection,"u_lguppmp");
		
		$objConnection->beginwork();
		for($i=0;$i<= $objGrid->recordcount;$i++) {
			if($page->getitemstring("checkedT1r".$i) == "1") {
				if ($obju_LGUPPMP->getbysql("U_YR='".$page->getcolumnstring("T1",$i,"u_yr")."' AND U_PROFITCENTER='".$page->getcolumnstring("T1",$i,"u_profitcenter")."'")) {
					$obju_LGUPPMP->docstatus = $action;
					$actionReturn = $obju_LGUPPMP->update($obju_LGUPPMP->docno,$obju_LGUPPMP->rcdversion);
					if (!$actionReturn) break;
				} else $actionReturn = raiseError("Unable to find Budget Request for [".$page->getcolumnstring("T1",$i,"u_yr")."-".$page->getcolumnstring("T1",$i,"u_profitcenter")."].");
//				var_dump(array($page->getcolumnstring("T1",$i,"u_yr"),$page->getcolumnstring("T1",$i,"u_profitcenter")));
			}
			if (!$actionReturn) break;
		}
		//if ($actionReturn)  $actionReturn = raiseError("here");
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();
		
		return $actionReturn;
	}
	
		
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_u_year'])) {
		$page->setitem("u_year",date('Y'));
		$page->setitem("showdetails",1);
		$page->setitem("u_trxsource","M");
	}

	if ($page->getitemstring("showdetails")=="1") {
		$objGrid->columnvisibility("u_datefr",true);
		$objGrid->columnvisibility("u_itemname",true);
		$objGrid->columnvisibility("u_checkamount",true);
		$objGrid->columnvisibility("u_remarks",true);
	}
	
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_YR",$filterExp,$httpVars['df_u_year']);//,null,null,true
	$filterExp = genSQLFilterString("A.U_PROFITCENTER",$filterExp,$httpVars['df_u_profitcenter']);//,null,null,true
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_docno']);
	$filterExp = genSQLFilterString("A.U_PROFITCENTERNAME",$filterExp,$httpVars['df_u_bpname'],null,null,true);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	$objrs->setdebug();
	
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
	
		$objrs->queryopenext("select A.DOCNO, A.U_YR, A.U_PROFITCENTER, A.U_PROFITCENTERNAME, A.U_TOTALAMOUNT, A.DOCSTATUS
			from U_LGUPPMP A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			
			$objGrid->setitem(null,"checked",0);
			$objGrid->setitem(null,"u_yr",$objrs->fields["U_YR"]);
			$objGrid->setitem(null,"u_profitcenter",$objrs->fields["U_PROFITCENTER"]);
			$objGrid->setitem(null,"u_profitcentername",$objrs->fields["U_PROFITCENTERNAME"]);
			$objGrid->setitem(null,"u_totalamount",formatNumericAmount($objrs->fields["U_TOTALAMOUNT"]));
			$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
			$objGrid->setitem(null,"docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
			$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
			if (!$page->paging_fetch()) break;
		}	
	}
		
	$page->resize->addgrid("T1",20,190,false);
	
	
	function onPopupMenuDraw($objPopupMenu) {
		global $page;
		switch ($page->getitemstring("docstatus")) {
			case "Draft":
				$objPopupMenu->addline("popupTable");
				$objPopupMenu->additem("popupTable","Update Status to For Approval","formSubmit('For Approval')");
				break;
			case "For Approval":
				$objPopupMenu->addline("popupTable");
				$objPopupMenu->additem("popupTable","Update Status to Approved","formSubmit('Approved')");
				break;
		}		
	}
	
	//$rptcols = 6; 
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
<SCRIPT language=JavaScript src="<?php autocaching('js/formobjs.js'); ?>" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function OpenLnkBtnRefNos(targetObjectId) {
		var row = targetObjectId.indexOf("T1r");		
		if (getTableInput("T1",'u_preparedby',targetObjectId.substring(row+3,targetObjectId.length))=="IP") {
			OpenLnkBtnu_hisips(targetObjectId);
		} else {
			OpenLnkBtnu_hisops(targetObjectId);
		}
		
	}
	

	function onPageLoad() {
		focusInput("docno");
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				/*if (getTableInput("T1","docstatus",p_rowIdx)=="O") {
					if (getTableInput("T1","u_prepaid",p_rowIdx)=="1" && getTableInput("T1","u_payrefno",p_rowIdx)=="") {
						page.statusbar.showWarning("Request is prepaid and not yet paid.");
					} else {
						var targetObjectId = '';
						OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
					}	
					//setKey("keys",getTableKey("T1","keys",p_rowIdx));
					//return formView(null,"<?php echo $page->formid; ?>");
				}*/	
							
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				if (getInput("u_isalert")=="1") {
					var targetObjectId = 'u_yblrsos';
					OpenLnkBtn(1024,570,'./udo.php?objectcode=u_yblrsos' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				} else {
					return formView(null,"<?php echo $page->formid; ?>");
				}	
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "u_yblrsos":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "docstatus":
			case "u_trxtype":
				clearTable("T1");
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_profitcenter":
			case "u_year":
			case "u_discondatefr":
			case "u_recondatefr":
			case "u_lapsedatefr":
			case "u_dateto":
			case "u_discondateto":
			case "u_recondateto":
			case "u_lapsedateto":
			case "docno":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
					case "checked": 
						if (row==0) {
							if (isTableInputChecked("T1","checked")) {
								checkedTableInput("T1","checked",-1);
							} else uncheckedTableInput("T1","checked",-1);					
						}	
						break;
				}
				break;
			default:
				switch (column) {
					case "showdetails":
					case "u_trxsource":
						formSearchNow();
						break;
				}
				break;
		}		
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_trxtype":
			case "df_showdetails":
			case "df_docno":
			case "df_u_bpname":
			case "df_u_department":
			case "df_u_profitcenter":
			case "docstatus":
			case "df_u_year":
			case "df_u_discondatefr":
			case "df_u_recondatefr":
			case "df_u_lapsedatefr":
			case "df_u_dateto":
			case "df_u_discondateto":
			case "df_u_recondateto":
			case "df_u_lapsedateto":
			case "df_u_trxsource":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_trxtype");
			inputs.push("showdetails");
			inputs.push("docno");
			inputs.push("u_bpname");
			inputs.push("u_department");
			inputs.push("u_profitcenter");
			inputs.push("u_trxtype");
			inputs.push("docstatus");
			inputs.push("u_year");
			inputs.push("u_dateto");
			inputs.push("u_discondatefr");
			inputs.push("u_discondateto");
			inputs.push("u_recondatefr");
			inputs.push("u_recondateto");
			inputs.push("u_lapsedatefr");
			inputs.push("u_lapsedateto");
			inputs.push("u_trxsource");
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
			case "u_profitcenter":
			case "u_year":
			case "u_dateto":
			case "u_discondatefr":
			case "u_discondateto":
			case "u_recondatefr":
			case "u_recondateto":
			case "u_lapsedatefr":
			case "u_lapsedateto":
			case "docno":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
			case "u_department":
			case "u_trxtype":
			case "showdetails":
			case "docstatus":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				}
				break;
		}
	}	
	
	function OpenLnkBtnu_hisrequests(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
		
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
<input type="hidden" id="df_u_isalert" name="df_u_isalert" value="<?php echo $page->getitemstring("u_isalert");  ?>">
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
     <td ><label <?php genCaptionHtml($schema["docno"],"") ?>>Request No.</label></td>
	  <td  align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["docno"]) ?> /></td>
	  <td width="128"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="268">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:196px") ?> ></select></td>
      </tr>
	<tr>
	  <td width="128"><label <?php genCaptionHtml($schema["u_profitcenter"],"") ?>>Profit Center</label></td>
	  <td align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["u_profitcenter"]) ?> /></td>
<td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td> 	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_bpname"],"") ?>>Name</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_bpname"]) ?> /></td>
	 <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
	<tr>
	   <td ><label <?php genCaptionHtml($schema["u_year"],"") ?>>Year</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_year"]) ?> /></td>
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
<tr><td><?php $objGrid->draw() ?></td></tr>
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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString()  . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
