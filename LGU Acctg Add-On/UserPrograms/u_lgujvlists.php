<?php
	$progid = "u_lgujvlists";

	$progaccessid = "u_lgujvs";
	
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
	
	$enumdocstatus["D"] = "Draft";
	$enumdocstatus["O"] = "Open";
	//$enumdocstatus["Done"] = "Done";
	//$enumdocstatus["C"] = "Closed";
	//$enumdocstatus["CN"] = "Cancelled";


	$page->restoreSavedValues();	
	
	$page->objectcode = "u_tcsacctlist";
	$page->paging->formid = "./UDP.php?&objectcode=u_lgujvlists";
	$page->formid = "./UDO.php?&objectcode=U_LGUJVS";
	$page->objectname = "Journal Voucher Lists";
	
	$schema["u_jevno"] = createSchemaUpper("u_jevno");
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_remarks"] = createSchema("u_remarks");
	$schema["u_trxtype"] = createSchemaUpper("u_trxtype");
	$schema["u_trxsource"] = createSchemaUpper("u_trxsource");
	$schema["showdetails"] = createSchemaUpper("showdetails");
	$schema["u_datefr"] = createSchemaDate("u_datefr");
	$schema["u_dateto"] = createSchemaDate("u_dateto");
	$schema["u_discondatefr"] = createSchemaDate("u_discondatefr");
	$schema["u_discondateto"] = createSchemaDate("u_discondateto");
	$schema["u_recondatefr"] = createSchemaDate("u_recondatefr");
	$schema["u_recondateto"] = createSchemaDate("u_recondateto");
	$schema["u_lapsedatefr"] = createSchemaDate("u_lapsedatefr");
	$schema["u_lapsedateto"] = createSchemaDate("u_lapsedateto");

	$objrs = new recordset(null,$objConnection);

	$objGrid = new grid("T1",$httpVars);
	//$objGrid->addcolumn("u_time");
	$objGrid->addcolumn("u_date");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_jevno");
	$objGrid->addcolumn("u_remarks");
	$objGrid->addcolumn("u_totalamount");
	$objGrid->addcolumn("docstatus","docstatusname");
	$objGrid->columntitle("u_date","Date");
	$objGrid->columntitle("docno","JV No.");
	$objGrid->columntitle("u_acctno","Account No.");
	$objGrid->columntitle("u_jevno","JEV No.");
	$objGrid->columntitle("u_trxsource","Trx. Source");
	$objGrid->columntitle("u_trxno","Trx No.");
	$objGrid->columntitle("u_trxtype","Type of Adj.");
	$objGrid->columntitle("u_totalamount","Total Amount");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columntitle("u_datefr","Prd From");
	$objGrid->columntitle("u_itemname","Service / Charges");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("u_dueamount","Due Amount");
	$objGrid->columnwidth("docno",20);
	$objGrid->columnwidth("u_acctno",10);
	$objGrid->columnwidth("u_jevno",20);
	$objGrid->columnwidth("u_trxsource",15);
	$objGrid->columnwidth("u_trxno",10);
	$objGrid->columnwidth("u_totalamount",15);
	$objGrid->columnwidth("u_trxtype",15);
	$objGrid->columnwidth("u_datefr",8);
	$objGrid->columnwidth("u_itemname",25);
	$objGrid->columnwidth("u_remarks",50);
	$objGrid->columnwidth("u_date",8);
	$objGrid->columnwidth("docstatus",10);
	$objGrid->columnalignment("u_dueamount","right");
	$objGrid->columnsortable("u_jevno",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnvisibility("u_datefr",false);
	$objGrid->columnvisibility("u_trxsource",false);
	$objGrid->columnvisibility("u_trxno",false);
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_date, docno";
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
				case "u_jevno":
				case "u_acctno":
				case "docno":
				case "u_remarks":
				case "u_trxtype":
				case "docstatus":
				case "u_totalamount":
					if ($isgroup) $label = "";
					break;
			}
		//}
	}
		
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_u_datefr'])) {
		$page->setitem("u_datefr",getmonthstart(currentdateDB()));
		$page->setitem("u_dateto",getmonthend(currentdateDB()));
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
	$filterExp = genSQLFilterString("A.U_JEVNO",$filterExp,$httpVars['df_u_jevno']);
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_docno']);
	$filterExp = genSQLFilterString("A.U_REMARKS",$filterExp,$httpVars['df_u_remarks'],null,null,true);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	//$filterExp = genSQLFilterString("A.U_TRXTYPE",$filterExp,$httpVars['df_u_trxtype']);
	$filterExp = genSQLFilterDate("A.U_DATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.u_discondate, A.u_starttime, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	//if ($page->getitemstring("showdetails")=="1") {
	//	$objrs->queryopenext("select A.DOCNO, A.U_ACCTNO, A.U_TELNO, A.U_CUSTNAME, A.U_DATE, A.U_TRXTYPE, A.U_TOTALAMOUNT, A.DOCSTATUS, A.U_TRXSOURCE, A.U_TRXNO, B.U_ITEMNAME, B.U_DATEFR, B.U_REMARKS, B.U_LINETOTAL AS U_REFAMOUNT 
	//		from U_TCSADJS A INNER JOIN U_TCSADJITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID 
	//	WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//} else {	
		$objrs->queryopenext("select A.DOCNO, A.U_JEVNO, A.U_DATE, A.U_TOTALDEBIT, A.DOCSTATUS, A.U_REMARKS
			from U_LGUJVS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//}
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$objGrid->setitem(null,"u_date",formatDateToHttp($objrs->fields["U_DATE"]));
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_acctno",$objrs->fields["U_ACCTNO"]);
		$objGrid->setitem(null,"u_jevno",$objrs->fields["U_JEVNO"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_trxtype",$objrs->fields["U_TRXTYPE"]);
		$objGrid->setitem(null,"u_trxtypename",$enumpayfor[$objrs->fields["U_TRXTYPE"]]);
		$objGrid->setitem(null,"u_trxsource",$objrs->fields["U_TRXSOURCE"]);
		$objGrid->setitem(null,"u_trxno",$objrs->fields["U_TRXNO"]);
		$objGrid->setitem(null,"u_totalamount",formatNumericAmount($objrs->fields["U_TOTALDEBIT"]));
		$objGrid->setitem(null,"u_datefr",formatDateToHttp($objrs->fields["U_DATEFR"]));
		$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
		$objGrid->setitem(null,"docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
		$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		if (!$page->paging_fetch()) break;
	}	
	
	$page->resize->addgrid("T1",20,190,false);
	
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
			case "u_jevno":
			case "u_datefr":
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
		switch (column) {
			case "showdetails":
			case "u_trxsource":
				formSearchNow();
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
			case "df_u_remarks":
			case "df_u_department":
			case "u_jevno":
			case "docstatus":
			case "df_u_datefr":
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
			inputs.push("u_remarks");
			inputs.push("u_department");
			inputs.push("u_jevno");
			inputs.push("u_trxtype");
			inputs.push("docstatus");
			inputs.push("u_datefr");
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
			case "u_jevno":
			case "u_datefr":
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
     <td ><label <?php genCaptionHtml($schema["docno"],"") ?>>JV No.</label></td>
	  <td  align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["docno"]) ?> /></td>
	  <td width="128"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="268">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:196px") ?> ></select></td>
      </tr>
	<tr>
	  <td width="128"><label <?php genCaptionHtml($schema["u_jevno"],"") ?>>JEV No.</label></td>
	  <td align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["u_jevno"]) ?> /></td>
<td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td> 	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_remarks"],"") ?>>Remarks</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_remarks"]) ?> /></td>
	 <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
	<tr>
	   <td ><label <?php genCaptionHtml($schema["u_datefr"],"") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_datefr"]) ?> /><label <?php genCaptionHtml($schema["u_dateto"],"") ?>>To</label>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_dateto"]) ?> /></td>
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
