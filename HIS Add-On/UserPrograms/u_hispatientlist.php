<?php
	$progid = "u_hispatientlist";

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
	
	$page->objectcode = "U_HISPATIENTS";
	$page->paging->formid = "./UDP.php?&objectcode=u_hispatientlist";
	$page->formid = "./UDO.php?&objectcode=U_HISIPS";
	$page->objectname = "Patients";
	

	require("./inc/formactions.php");
	
	$schema["u_patientid"] = createSchemaUpper("u_patientid");
	$schema["u_lastname"] = createSchemaUpper("u_lastname");
	$schema["u_firstname"] = createSchemaUpper("u_firstname");
	$schema["u_middlename"] = createSchemaUpper("u_middlename");
	$schema["u_birthdate"] = createSchemaDate("u_birthdate");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("indicator");
	$objGrid->addcolumn("code");
	$objGrid->addcolumn("u_lastname");
	$objGrid->addcolumn("u_firstname");
	$objGrid->addcolumn("u_extname");
	$objGrid->addcolumn("u_middlename");
	$objGrid->addcolumn("u_birthdate");
	$objGrid->addcolumn("u_visitcount");
	$objGrid->addcolumn("u_expired");
	$objGrid->addcolumn("u_active","u_activedesc");
	$objGrid->columntitle("indicator","");
	$objGrid->columntitle("code","Patient ID");
	$objGrid->columntitle("u_lastname","Last Name");
	$objGrid->columntitle("u_firstname","First Name");
	$objGrid->columntitle("u_extname","Ext");
	$objGrid->columntitle("u_middlename","Middle Name");
	$objGrid->columntitle("u_paymentterm","Payment Term");
	$objGrid->columntitle("u_birthdate","Birth Date");
	$objGrid->columntitle("u_visitcount","No of Visits");
	$objGrid->columntitle("u_active","Active");
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("code",10);
	$objGrid->columnwidth("u_lastname",15);
	$objGrid->columnwidth("u_firstname",15);
	$objGrid->columnwidth("u_extname",15);
	$objGrid->columnwidth("u_middlename",15);
	$objGrid->columnwidth("u_birthdate",10);
	$objGrid->columnwidth("u_visitcount",12);
	$objGrid->columnwidth("u_active",6);
	$objGrid->columnsortable("code",true);
	$objGrid->columnsortable("u_lastname",true);
	$objGrid->columnsortable("u_firstname",true);
	$objGrid->columnsortable("u_extname",true);
	$objGrid->columnsortable("u_middlename",true);
	$objGrid->columnsortable("u_birthdate",true);
	$objGrid->columnsortable("u_visitcount",true);
	$objGrid->columnsortable("u_active",true);
	$objGrid->columnvisibility("u_expired",false);
	$objGrid->columnlnkbtn("code","OpenLnkBtnu_hispatients()");
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "code";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "indicator":
				switch ($label) {
					case "Expired": $style="background-color:gray";break;
				}
				$label="&nbsp";	
				break;
		}
		
	}
		
	$filterExp = "";
	
	$filterExp = genSQLFilterString("A.U_LASTNAME",$filterExp,$httpVars['df_u_lastname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_FIRSTNAME",$filterExp,$httpVars['df_u_firstname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_MIDDLENAME",$filterExp,$httpVars['df_u_middlename'],null,null,true);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	$filterExp = genSQLFilterString("A.CODE",$filterExp,$httpVars['df_u_patientid']);
	$filterExp = genSQLFilterDate("A.U_BIRTHDATE",$filterExp,$httpVars['df_u_birthdate']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
	$objrs->queryopenext("select A.CODE, A.U_LASTNAME, A.U_FIRSTNAME, A.U_MIDDLENAME,A.U_EXTNAME, A.U_BIRTHDATE, A.U_VISITCOUNT, A.U_EXPIRED, A.U_ACTIVE from U_HISPATIENTS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$indicator="";
		if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
		
		$objGrid->setitem(null,"indicator",$indicator);
		$objGrid->setitem(null,"code",$objrs->fields["CODE"]);
		$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
		$objGrid->setitem(null,"u_firstname",$objrs->fields["U_FIRSTNAME"]);
		$objGrid->setitem(null,"u_extname",$objrs->fields["U_EXTNAME"]);
		$objGrid->setitem(null,"u_middlename",$objrs->fields["U_MIDDLENAME"]);
		$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
		$objGrid->setitem(null,"u_birthdate",formatDateToHttp($objrs->fields["U_BIRTHDATE"]));
		$objGrid->setitem(null,"u_visitcount",$objrs->fields["U_VISITCOUNT"]);
		$objGrid->setitem(null,"u_expired",$objrs->fields["U_EXPIRED"]);
		$objGrid->setitem(null,"u_active",$objrs->fields["U_ACTIVE"]);
		$objGrid->setitem(null,"u_activedesc",iif($objrs->fields["U_ACTIVE"]==1,"Yes","No"));
		$objGrid->setkey(null,$objrs->fields["CODE"]); 
		if (!$page->paging_fetch()) break;
	}	

	resetTabindex();
	setTabindex($schema["u_lastname"]);
	setTabindex($schema["u_firstname"]);
	setTabindex($schema["u_middlename"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,190,false);
	
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
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatients.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		focusInput("u_lastname");
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				//setKey("keys",getTableKey("T1","keys",p_rowIdx));
				if (getTableInput("T1","u_expired",p_rowIdx)=="1") {
					page.statusbar.showError("Patient already expired. Cannot add new registration.");
				} else if (getTableInput("T1","u_active",p_rowIdx)=="0") {
					page.statusbar.showError("Patient record is not active. Cannot add new registration.");
				} else {
					if (getInput("u_trxtype")=="IP") {
						return formView(null,"./UDO.php?&objectcode=U_HISIPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					} else {
						return formView(null,"./UDO.php?&objectcode=U_HISOPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					}	
				}	
				//var targetObjectId = 'u_hisips';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetObjectId ,targetObjectId);
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
			case "doctype": 
			case "docgroup": 
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_patientid":
			case "u_lastname":
			case "u_firstname":
			case "u_middlename":
			case "u_birthdate": 
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_patientid":
			case "df_u_lastname":
			case "df_u_firstname":
			case "df_u_middlename":
			case "df_u_birthdate":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_patientid");
			inputs.push("u_lastname");
			inputs.push("u_firstname");
			inputs.push("u_middlename");
			inputs.push("u_birthdate");
			inputs.push("u_trxtype");
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
			case "u_lastname":
			case "u_firstname":
			case "u_middlename":
			case "u_birthdate":
			case "u_patientid":
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
<input type="hidden" <?php genInputHiddenDFHtml("u_trxtype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Patient List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_lastname"],"") ?>>Last Name</label></td>
	  <td  align=left><input type="text" <?php genInputTextHtml($schema["u_lastname"]) ?> /></td>
	  <td width="168"><label <?php genCaptionHtml($schema["u_patientid"],"") ?>>Patient ID</label></td>
		<td align=left width="168"><input type="text" size="15" <?php genInputTextHtml($schema["u_patientid"]) ?> /></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_firstname"],"") ?>>First Name</label></td>
	  <td  align=left><input type="text" <?php genInputTextHtml($schema["u_firstname"]) ?> /></td>
	  <td ><label <?php genCaptionHtml($schema["u_birthdate"],"") ?>>Birth Date</label></td>
	  <td  align=left><input type="text" size="15" <?php genInputTextHtml($schema["u_birthdate"]) ?> /></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_middlename"],"") ?>>Middle Name</label></td>
	  <td  align=left><input type="text" <?php genInputTextHtml($schema["u_middlename"]) ?> /></td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	</tr>
	
	

	<tr>
	  <td >&nbsp;</td>
	  <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Add</a></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr ><td><label style="background-color:gray" title="Expired">&nbsp;</label></td></tr>
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
