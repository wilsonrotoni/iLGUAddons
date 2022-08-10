<?php
	$progid = "u_hisbilllist";

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
	
	$enumu_reftype= array('OP' => 'Out Patient', 'IP' => 'In Patient');
	
	unset($enumdocstatus["D"]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISBILLLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisbilllist";
	$page->formid = "./UDO.php?&objectcode=U_HISBILLS";
	$page->objectname = "In-Patient";
	
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_reftype"] = createSchemaUpper("u_reftype");
	$schema["u_refno"] = createSchemaUpper("u_refno");
	$schema["u_withbal"] = createSchemaUpper("u_withbal");
	$schema["u_docdatefr"] = createSchemaDate("u_docdatefr");
	$schema["u_docdateto"] = createSchemaDate("u_docdateto");

	function loadenumu_reftype($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumu_reftype;
		reset($enumu_reftype);
		while (list($key, $val) = each($enumu_reftype)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
	
	$objrs = new recordset(null,$objConnection);
	$dfltreg="IP";
	$objrs->queryopen("select U_DFLTREG FROM U_HISSETUP WHERE CODE='SETUP'");
	if ($objrs->queryfetchrow("NAME")) {
		$dfltreg = $objrs->fields["U_DFLTREG"];
	}
	

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_docdate");
	$objGrid->addcolumn("u_doctime");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_reftype");
	$objGrid->addcolumn("u_refno");
	$objGrid->addcolumn("u_patientid");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_healthins");
	$objGrid->addcolumn("u_dueamount");
	$objGrid->addcolumn("u_paidamount");
	$objGrid->addcolumn("u_balamount");
	$objGrid->addcolumn("u_remarks");
	$objGrid->addcolumn("docstatus","u_docstatusname");
	$objGrid->columntitle("u_docdate","Date");
	$objGrid->columntitle("u_doctime","Time");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_reftype","Visit");
	$objGrid->columntitle("u_refno","Visit No.");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_healthins","Company");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columntitle("u_dueamount","Due Amount");
	$objGrid->columntitle("u_paidamount","Paid Amount");
	$objGrid->columntitle("u_balamount","Balance");
	$objGrid->columnwidth("u_docdate",8);
	$objGrid->columnwidth("u_doctime",5);
	$objGrid->columnwidth("docno",15);
	$objGrid->columnwidth("u_reftype",4);
	$objGrid->columnwidth("u_refno",10);
	$objGrid->columnwidth("u_patientid",10);
	$objGrid->columnwidth("u_patientname",25);
	$objGrid->columnwidth("u_healthins",20);
	$objGrid->columnwidth("u_remarks",20);
	$objGrid->columnwidth("docstatus",9);
	$objGrid->columnwidth("u_dueamount",10);
	$objGrid->columnwidth("u_paidamount",10);
	$objGrid->columnwidth("u_balamount",10);
	$objGrid->columnalignment("u_dueamount","right");
	$objGrid->columnalignment("u_paidamount","right");
	$objGrid->columnalignment("u_balamount","right");
	$objGrid->columnsortable("u_startdate",true);
	$objGrid->columnsortable("u_starttime",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_patientid",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_healthins",true);
	$objGrid->columnsortable("u_itemcode",true);
	$objGrid->columnsortable("u_itemdesc",true);
	$objGrid->columnsortable("docstatus",true);
//	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hisrequests()");
	$objGrid->columnlnkbtn("u_refno","OpenLnkBtnRefNos()");
	$objGrid->columnvisibility("u_patientid",false);
	$objGrid->columnvisibility("u_itemcode",false);
	$objGrid->columnvisibility("u_template",false);
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "docno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "u_dueamount": 
			case "u_paidamount": 
			case "u_balamount": 
				if ($label=="0.00") $label="";
				break;
		}
	}
	
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","O");
		$page->setitem("u_reftype",$dfltreg);
	}
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_REFTYPE",$filterExp,$httpVars['df_u_reftype']);
	$filterExp = genSQLFilterString("A.U_REFNO",$filterExp,$httpVars['df_u_refno']);
	$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterDate("A.U_DOCDATE",$filterExp,$httpVars['df_u_docdatefr'],$httpVars['df_u_docdateto']);
	
	//$qtyExp = " AND E.U_QUANTITY > (E.U_CHRGQTY + E.U_RTQTY)";
	if ($httpVars['df_u_withbal']=="1" && $httpVars['df_docstatus']=="CN") $withbalExp = " AND A.U_PAIDAMOUNT<>0";
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.u_startdate, A.u_starttime, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	$objrs->queryopenext("select A.DOCNO, A.U_DOCDATE, A.U_DOCTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_HEALTHINS, A.U_REFTYPE, A.U_REFNO, A.DOCSTATUS, A.U_DUEAMOUNT, A.U_PAIDAMOUNT, A.U_REMARKS from U_HISBILLS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $withbalExp $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_DOCDATE"]));
		$objGrid->setitem(null,"u_doctime",formatTimeToHttp($objrs->fields["U_DOCTIME"]));
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_reftype",$objrs->fields["U_REFTYPE"]);
		$objGrid->setitem(null,"u_refno",$objrs->fields["U_REFNO"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["U_PATIENTID"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["U_PATIENTNAME"]);
		$objGrid->setitem(null,"u_healthins",$objrs->fields["U_HEALTHINS"]);
		$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
		$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
		$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_dueamount",formatNumericAmount($objrs->fields["U_DUEAMOUNT"]));
		$objGrid->setitem(null,"u_paidamount",formatNumericAmount($objrs->fields["U_PAIDAMOUNT"]));
		$objGrid->setitem(null,"u_balamount",formatNumericAmount($objrs->fields["U_DUEAMOUNT"]-$objrs->fields["U_PAIDAMOUNT"]));
		$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
		$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
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
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatientregs.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function OpenLnkBtnRefNos(targetObjectId) {
		var row = targetObjectId.indexOf("T1r");		
		if (getTableInput("T1",'u_reftype',targetObjectId.substring(row+3,targetObjectId.length))=="IP") {
			OpenLnkBtnu_hisips(targetObjectId);
		} else {
			OpenLnkBtnu_hisops(targetObjectId);
		}
		
	}
	

	function onPageLoad() {
		//focusInput("u_refno");
		focusInput("u_patientname");
		if (getInput("docstatus")!="CN") {
			hideInput("u_withbal");
		}
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
			case "u_reftype":
				clearTable("T1");
				formPageReset(); 
				break;	
			case "docstatus":
				if (getInput("docstatus")!="CN") {
					hideInput("u_withbal");
				} else {
					showInput("u_withbal");
					checkedInput("u_withbal");
				}
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
			case "u_docdatefr":
			case "u_docdateto":
			case "u_refno":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (column) {
			case "u_withbal":
				clearTable("T1");
				formPageReset(); 
				break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_reftype":
			case "df_u_refno":
			case "df_u_patientname":
			case "df_u_withbal":
			case "df_docstatus":
			case "df_u_docdatefr":
			case "df_u_docdateto":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_reftype");
			inputs.push("u_refno");
			inputs.push("u_patientname");
			inputs.push("u_trxtype");
			inputs.push("u_withbal");
			inputs.push("docstatus");
			inputs.push("u_docdatefr");
			inputs.push("u_docdateto");
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
<input type="hidden" id="df_u_trxtype" name="df_u_trxtype" value="<?php echo $page->getitemstring("u_trxtype");  ?>">
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
	  <td width="168" ><label <?php genCaptionHtml($schema["u_reftype"],"") ?>>Registration Type</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_reftype"],array("loadenumu_reftype","",":[All]")) ?> ></select></td>
	  <td width="168"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
		<td align=left width="168">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_docdatefr"],"") ?>>Bill Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_docdatefr"]) ?> /><label <?php genCaptionHtml($schema["u_docdateto"],"") ?>>To</label>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_docdateto"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["u_withbal"],"1") ?> /><label <?php genCaptionHtml($schema["u_withbal"],"") ?> >w/ Payment</label></td>
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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
