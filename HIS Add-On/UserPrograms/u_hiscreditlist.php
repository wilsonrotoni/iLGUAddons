<?php
	$progid = "u_hiscreditlist";

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
	
	unset($enumdocstatus["D"]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISREQUESTINLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hiscreditlist";
	$page->formid = "./UDO.php?&objectcode=U_HISCREDITS";
	$page->objectname = "In-Patient";
	
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");

	$objrs = new recordset(null,$objConnection);
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		if ($userdepartment!="") $schema["u_department"]["attributes"] = "disabled";
	}

	switch ($page->getitemstring("u_trxtype")) {
		case "RADIOLOGY":
			$pageHeader = "Radiology - Cash Sales List";
			break;	
		case "LABORATORY":
			$pageHeader = "Laboratory - Cash Sales List";
			break;	
		case "HEARTSTATION":
			$pageHeader = "Heart Station - Cash Sales List";
			break;	
		case "PHARMACY":
			$pageHeader = "Pharmacy - Cash Sales List";
			break;	
		case "CSR":
			$pageHeader = "CSR - Cash Sales List";
			break;	
		case "DIALYSYS":
			$pageHeader = "Dialysis - Request List";
			break;	
		case "PULMONARY":
			$pageHeader = "Pulmonary - Request List";
			break;	
		case "OP":
			$pageHeader = "Out-Patient - Request List";
			break;	
		default:
			$pageHeader = "Request List";
			break;
	}

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_startdate");
	$objGrid->addcolumn("u_starttime");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_department");
	$objGrid->addcolumn("u_departmentname");
	$objGrid->addcolumn("u_reftype");
	$objGrid->addcolumn("u_refno");
	$objGrid->addcolumn("u_patientid");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_itemcode");
	$objGrid->addcolumn("u_itemdesc");
	$objGrid->addcolumn("u_quantity");
	$objGrid->addcolumn("u_remarks");
	$objGrid->addcolumn("u_prepaid","u_prepaiddesc");
	$objGrid->addcolumn("u_payrefno");
	//$objGrid->addcolumn("docstatus","u_docstatusname");
	$objGrid->addcolumn("u_template");
	$objGrid->columntitle("u_startdate","Date");
	$objGrid->columntitle("u_starttime","Time");
	$objGrid->columntitle("docno","No.");
	$objGrid->columntitle("u_department","Rendering Section");
	$objGrid->columntitle("u_departmentname","Rendering Section");
	$objGrid->columntitle("u_reftype","Visit");
	$objGrid->columntitle("u_refno","Visit No.");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_itemcode","Item Code");
	$objGrid->columntitle("u_itemdesc","Item Description");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("u_quantity","Qty");
	$objGrid->columntitle("u_prepaid","Term");
	$objGrid->columntitle("u_payrefno","Receipt No.");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("u_startdate",8);
	$objGrid->columnwidth("u_starttime",5);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_department",18);
	$objGrid->columnwidth("u_departmentname",18);
	$objGrid->columnwidth("u_reftype",4);
	$objGrid->columnwidth("u_refno",10);
	$objGrid->columnwidth("u_patientid",10);
	$objGrid->columnwidth("u_patientname",25);
	$objGrid->columnwidth("u_itemcode",10);
	$objGrid->columnwidth("u_itemdesc",30);
	$objGrid->columnwidth("u_quantity",7);
	$objGrid->columnwidth("u_remarks",20);
	$objGrid->columnwidth("u_prepaid",6);
	$objGrid->columnwidth("u_payrefno",10);
	$objGrid->columnwidth("docstatus",9);
	$objGrid->columnalignment("u_quantity","right");
	$objGrid->columnsortable("u_startdate",true);
	$objGrid->columnsortable("u_starttime",true);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_departmentname",true);
	$objGrid->columnsortable("u_patientid",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_itemcode",true);
	$objGrid->columnsortable("u_itemdesc",true);
	$objGrid->columnsortable("docstatus",true);
//	$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hisrequests()");
	$objGrid->columnlnkbtn("u_refno","OpenLnkBtnRefNos()");
	$objGrid->columnvisibility("u_department",false);
	$objGrid->columnvisibility("u_patientid",false);
	$objGrid->columnvisibility("u_itemcode",false);
	$objGrid->columnvisibility("u_template",false);
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "docno";
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
				case "u_startdate":
					//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
					if ($groupby!=$objGrid->getitemstring($row,"docno")) {
						$groupby = $objGrid->getitemstring($row,"docno");
						$isgroup = false;
					} else {
						$isgroup = true;
						$label = "";
					}	
					break;
				case "u_starttime":
				case "docno":
				case "u_reftype":
				case "u_refno":
				case "u_requesttime":
				case "u_patientname":
				case "u_departmentname":
					if ($isgroup) $label = "";
					break;
			}
		//}
	}
	
	require("./inc/formactions.php");
	
	
	if (!isset($httpVars['df_docstatus'])) {
		$page->setitem("docstatus","o");
		$page->setitem("u_department",$userdepartment);
	}
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_u_department']);
	$filterExp = genSQLFilterString("A.U_PATIENTNAME",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	//$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.u_requestdate, A.u_requesttime, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	$objrs->queryopenext("select A.DOCNO, A.U_STARTDATE, A.U_STARTTIME, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REFTYPE, A.U_REFNO, A.U_PREPAID, A.U_PAYREFNO, A.U_REMARKS, B.NAME AS U_DEPARTMENTNAME, E.U_ITEMCODE, E.U_ITEMDESC, E.U_QUANTITY, C.U_TEMPLATE, A.U_AMOUNT, A.DOCSTATUS from U_HISCREDITS A, U_HISCREDITITEMS E, U_HISSECTIONS B, U_HISITEMS C WHERE E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID AND C.CODE=E.U_ITEMCODE AND B.CODE=A.U_DEPARTMENT AND A.U_TRXTYPE IN ('".$page->getitemstring("u_trxtype")."') AND A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$objGrid->setitem(null,"u_startdate",formatDateToHttp($objrs->fields["U_STARTDATE"]));
		$objGrid->setitem(null,"u_starttime",formatTimeToHttp($objrs->fields["U_STARTTIME"]));
		$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_departmentname",$objrs->fields["U_DEPARTMENTNAME"]);
		$objGrid->setitem(null,"u_reftype",$objrs->fields["U_REFTYPE"]);
		$objGrid->setitem(null,"u_refno",$objrs->fields["U_REFNO"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["U_PATIENTID"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["U_PATIENTNAME"]);
		$objGrid->setitem(null,"u_itemcode",$objrs->fields["U_ITEMCODE"]);
		$objGrid->setitem(null,"u_itemdesc",$objrs->fields["U_ITEMDESC"]);
		$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
		$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["U_QUANTITY"]));
		$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
		$objGrid->setitem(null,"u_prepaid",$objrs->fields["U_PREPAID"]);
		$objGrid->setitem(null,"u_prepaiddesc",iif($objrs->fields["U_PREPAID"]==0,"Charge","Cash"));
		$objGrid->setitem(null,"u_payrefno",iif($objrs->fields["U_AMOUNT"]==0,"FOC",$objrs->fields["U_PAYREFNO"]));
		$objGrid->setitem(null,"u_docstatusname",slgetdisplayenumdocstatus($objrs->fields["DOCSTATUS"]));
		$objGrid->setitem(null,"u_template",$objrs->fields["U_TEMPLATE"]);
		$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
		if (!$page->paging_fetch()) break;
	}	
	
	$page->resize->addgrid("T1",20,150,false);
	
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
		focusInput("u_patientname");
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
			case "u_patientname":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		/*switch (column) {
			case "u_mgh":
			case "u_expired":
				clearTable("T1");
				formPageReset(); break;
		}*/
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
			case "df_u_patientname":
			case "df_docstatus":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_department");
			inputs.push("u_patientname");
			inputs.push("u_trxtype");
			inputs.push("docstatus");
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
	  <td width="168" ><label <?php genCaptionHtml($schema["u_department"],"") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_department"],array("loadudflinktable","u_hissections:code:name:u_type in ('".$page->getitemstring("u_trxtype")."')",":[All]")) ?> ></select></td>
	  <td width="168">&nbsp;</td>
		<td align=left width="168">&nbsp;</td>
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
