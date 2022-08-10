<?php
	$progid = "u_hispatientmedsupstocklist";

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
	
	unset($enumdocstatus["D"],$enumdocstatus["CN"],$enumdocstatus["O"],$enumdocstatus["C"]);
	$enumdocstatus["Active"] = "Active";
	$enumdocstatus["Discharged"] = "Discharged";
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_HISIPS";
	$page->paging->formid = "./UDP.php?&objectcode=u_hispatientmedsupstocklist";
	$page->formid = "./UDO.php?&objectcode=U_HISIPS";
	$page->objectname = "In-Patient";
	
	$schema["u_department"] = createSchemaUpper("u_department");
	$schema["u_patientname"] = createSchemaUpper("u_patientname");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_expired"] = createSchemaUpper("u_expired");
	$schema["u_mgh"] = createSchemaUpper("u_mgh");

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_departmentname");
	//$objGrid->addcolumn("u_bedno");
	//$objGrid->addcolumn("u_roomdesc");
	$objGrid->addcolumn("u_patientname");
	$objGrid->addcolumn("u_itemdesc");
	$objGrid->addcolumn("u_quantity");
	$objGrid->addcolumn("u_uom");
	$objGrid->columntitle("u_departmentname","Section");
	$objGrid->columntitle("u_bedno","Room/Bed No.");
	$objGrid->columntitle("u_roomdesc","Room Description");
	$objGrid->columntitle("u_patientid","Patient ID");
	$objGrid->columntitle("u_patientname","Patient Name");
	$objGrid->columntitle("u_itemdesc","Item Description");
	$objGrid->columntitle("u_quantity","Qty");
	$objGrid->columntitle("u_uom","UoM");
	$objGrid->columnwidth("u_departmentname",18);
	$objGrid->columnwidth("u_bedno",12);
	$objGrid->columnwidth("u_roomdesc",22);
	$objGrid->columnwidth("u_patientname",35);
	$objGrid->columnwidth("u_itemdesc",35);
	$objGrid->columnwidth("u_quantity",9);
	$objGrid->columnwidth("u_uom",5);
	$objGrid->columnalignment("u_quantity","right");
	$objGrid->columnsortable("u_departmentname",true);
	$objGrid->columnsortable("u_bedno",true);
	$objGrid->columnsortable("u_roomdesc",true);
	$objGrid->columnsortable("u_patientname",true);
	$objGrid->columnsortable("u_itemdesc",true);
	$objGrid->columnsortable("u_quantity",true);
	//$objGrid->columnlnkbtn("u_patientid","OpenLnkBtnu_hispatients()");
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_patientname";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	require("./inc/formactions.php");

	$trxtype = $page->getitemstring("u_trxtype");	
	$filterExp = "";
	$filterExp = genSQLFilterString("x.u_department",$filterExp,$httpVars['df_u_department']);
	$filterExp = genSQLFilterString("x.u_patientname",$filterExp,$httpVars['df_u_patientname'],null,null,true);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	var_dump($trxtype);
	$objrs->queryopenext("select x.u_department, s.name as u_departmentname, u_reftype, u_refno, u_patientid, u_patientname, u_itemdesc, u_itemcode, u_price, u_uom, sum(u_quantity) as u_quantity from ( select a.u_todepartment as u_department, u_reftype, u_refno, u_patientid, u_patientname, b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'  and a.u_trxtype in ('$trxtype') union all select a.u_todepartment as u_department, u_reftype, u_refno, u_patientid, u_patientname, b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'  and a.u_intransit=0 and a.u_trxtype not in ('IP','OP') union all select a.u_department, u_reftype, u_refno, u_patientid, u_patientname, b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'  and a.u_trxtype in ('$trxtype') union all select a.u_fromdepartment as u_department, u_reftype, u_refno, u_patientid, u_patientname, b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'   and a.docstatus='C' and a.u_trxtype in ('$trxtype') ) as x left join u_hissections s on s.code=x.u_department group by x.u_department, x.u_reftype, x.u_refno, x.u_patientid, x.u_patientname, x.u_itemcode, x.u_itemdesc having u_quantity>0 $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//var_dump($objrs->sqls);
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		//$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
		$objGrid->setitem(null,"u_departmentname",$objrs->fields["u_departmentname"]);
		$objGrid->setitem(null,"u_bedno",$objrs->fields["u_bedno"]);
		$objGrid->setitem(null,"u_roomdesc",$objrs->fields["u_roomdesc"]);
		$objGrid->setitem(null,"u_patientid",$objrs->fields["u_patientid"]);
		$objGrid->setitem(null,"u_patientname",$objrs->fields["u_patientname"]);
		$objGrid->setitem(null,"u_itemdesc",$objrs->fields["u_itemdesc"]);
		$objGrid->setitem(null,"u_quantity",formatNumericQuantity($objrs->fields["u_quantity"]));
		$objGrid->setitem(null,"u_uom",$objrs->fields["u_uom"]);
		//$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
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

<script language="JavaScript">

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
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_department":
			case "df_u_patientname":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_department");
			inputs.push("u_patientname");
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
<input type="text" <?php genInputHiddenDFHtml("u_trxtype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader; ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_department"],"") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_department"],array("loadudflinktable","u_hissections:code:name:u_type in ('IP')",":[All]")) ?> ></select></td>
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
