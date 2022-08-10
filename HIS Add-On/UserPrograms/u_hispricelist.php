<?php
	$progid = "u_hispricelist";

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
	//include_once("./inc/formaccess.php");
	
	$enumu_reftype= array('OP' => 'Out Patient', 'IP' => 'In Patient');
	
	unset($enumdocstatus["D"]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISPRICELIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hispricelist";
	$page->formid = "./UDO.php?&objectcode=U_HISBILLS";
	$page->objectname = "In-Patient";
	
	$schema["u_pricelist"] = createSchema("u_pricelist");
	$schema["u_itemcode"] = createSchema("u_itemcode");
	$schema["u_itemdesc"] = createSchema("u_itemdesc");
	$schema["u_brandname"] = createSchema("u_brandname");
	$schema["u_genericname"] = createSchema("u_genericname");
	$schema["u_itemgroup"] = createSchema("u_itemgroup");
	/*
	$objrs = new recordset(null,$objConnection);
	$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
	if ($objrs->queryfetchrow("NAME")) {
		$userdepartment = $objrs->fields["DEPARTMENT"];
		if ($page->getitemstring("u_trxtype")=="NURSE") {
			if ($userdepartment!="") $schema["u_pricelist"]["attributes"] = "disabled";
		}	
	}
*/
	$objrs = new recordset(null,$objConnection);

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("code");
	$objGrid->addcolumn("name");
	$objGrid->addcolumn("u_uom");
	$objGrid->addcolumn("u_genericname");
	$objGrid->addcolumn("u_brandname");
	$objGrid->addcolumn("u_price");
	$objGrid->columntitle("code","Code");
	$objGrid->columntitle("name","Description");
	$objGrid->columntitle("u_uom","Unit");
	$objGrid->columntitle("u_genericname","Generic");
	$objGrid->columntitle("u_brandname","Brand");
	$objGrid->columntitle("u_price","Price");
	$objGrid->columnwidth("code",7);
	$objGrid->columnwidth("name",31);
	$objGrid->columnwidth("u_uom",5);
	$objGrid->columnwidth("u_genericname",18);
	$objGrid->columnwidth("u_brandname",12);
	$objGrid->columnwidth("u_price",10);
	$objGrid->columnalignment("u_price","right");
	$objGrid->columnsortable("code",true);
	$objGrid->columnsortable("name",true);
	$objGrid->columnsortable("u_genericname",true);
	$objGrid->columnsortable("u_brandname",true);
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "name";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "u_price": 
				if ($label=="0.00") $label="";
				break;
		}
	}
	
	require("./inc/formactions.php");
	
	
	$filterExp = "";
	$filterExp = genSQLFilterString("A.CODE",$filterExp,$httpVars['df_u_itemcode']);
	$filterExp = genSQLFilterString("A.NAME",$filterExp,$httpVars['df_u_itemdesc'],null,null,true);
	$filterExp = genSQLFilterString("A.U_GROUP",$filterExp,$httpVars['df_u_itemgroup'],null,null,true);
	$filterExp = genSQLFilterString("A.U_BRANDNAME",$filterExp,$httpVars['df_u_brandname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_GENERICNAME",$filterExp,$httpVars['df_u_genericname'],null,null,true);
	
	if ($filterExp !="") $filterExp = " WHERE " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	$objrs->setdebug();
	if ($page->getitemstring("u_pricelist")!="") {
		$objrs->queryopenext("select A.CODE, A.NAME, A.U_UOM, A.U_GROUP, A.U_BRANDNAME, A.U_GENERICNAME, B.PRICE AS U_PRICE from U_HISITEMS A LEFT JOIN ITEMPRICELISTS B ON B.ITEMCODE=A.CODE AND B.PRICELIST='".$page->getitemstring("u_pricelist")."' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			
			$objGrid->setitem(null,"code",$objrs->fields["CODE"]);
			$objGrid->setitem(null,"name",$objrs->fields["NAME"]);
			$objGrid->setitem(null,"u_uom",$objrs->fields["U_UOM"]);
			$objGrid->setitem(null,"u_itemgroup",$objrs->fields["U_GROUP"]);
			$objGrid->setitem(null,"u_brandname",$objrs->fields["U_BRANDNAME"]);
			$objGrid->setitem(null,"u_genericname",$objrs->fields["U_GENERICNAME"]);
			$objGrid->setitem(null,"u_price",formatNumericAmount($objrs->fields["U_PRICE"]));
			$objGrid->setkey(null,$objrs->fields["CODE"]); 
			if (!$page->paging_fetch()) break;
		}	
	}
		
	$page->resize->addgrid("T1",20,210,false);
	
	//$rptcols = 6; 
	$pageHeader = "Price Inquiry";
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
		if (getVar("formSubmitFocusInput")!="") focusInput(getVar("formSubmitFocusInput"));
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
			case "u_pricelist":
			case "u_itemgroup":
				clearTable("T1");
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_itemcode":
			case "u_itemdesc":
			case "u_brandname":
			case "u_genericname":
				setVar("formSubmitFocusInput",column);
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_pricelist":
			case "df_u_itemcode":
			case "df_u_itemdesc":
			case "df_u_genericname":
			case "df_u_itemgroup":
			case "df_u_brandname":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_pricelist");
			inputs.push("u_itemcode");
			inputs.push("u_itemdesc");
			inputs.push("u_brandname");
			inputs.push("u_genericname");
			inputs.push("u_itemgroup");
		return inputs;
	}
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		if (isInputEmpty("u_pricelist")) return false;
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_itemcode":
			case "u_itemdesc":
			case "u_itemgroup":
			case "u_brandname":
			case "u_genericname":
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
	  <td width="88" ><label <?php genCaptionHtml($schema["u_pricelist"],"") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_pricelist"],array("loadudflinktable","pricelists:pricelist:pricelistname:bptype='C'",":[Select]")) ?> ></select></td>
	  <td width="88"><label <?php genCaptionHtml($schema["u_itemgroup"],"") ?>>Group</label></td>
		<td align=left width="168">&nbsp;<select <?php genSelectHtml($schema["u_itemgroup"],array("loadudflinktable","itemgroups:itemgroup:itemgroupname",":[Select]"),null,null,null,"width:128px") ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_itemcode"],"") ?>>Code</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php genInputTextHtml($schema["u_itemcode"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_itemdesc"],"") ?>>Description</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_itemdesc"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	</tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_generic"],"") ?>>Generic</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_genericname"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["u_brandname"],"") ?>>Brand</label></td>
	  <td align=left>&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["u_brandname"]) ?> /></td>
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
