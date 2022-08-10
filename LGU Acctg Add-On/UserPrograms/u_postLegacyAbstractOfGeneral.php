<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_postLegacyAbstractOfGeneral";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/branches.php");
	include_once("./sls/brancheslist.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	$page->restoreSavedValues();	


	$post = false;
	$validate = false;
	$page->objectcode = $progid;
	$httpVars["df_errormessages"] = "";
	
$boschema_validatetransaction= 
	array(
		"Names" => 
			array("T0" => "Header", "T1" => "List"),
		"T0" => 
			array(	"pctype" => array("name" => "pctype", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Funds Type" ),
					"refdate_fr" => array("name" => "refdate_fr", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Ref. Date From" ),
					"refdate_to" => array("name" => "refdate_to", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Ref. Date To" )
			),
		"T1" => 
			array(	"branchcode" => array("name" => "branchcode", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Branch", "width"=>10 ),
					"branchname" => array("name" => "branchname", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Branch Name", "width"=>30 ),
					"migratedate" => array("name" => "migratedate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Opening Date", "width"=>12 ),
					"u_lockdate" => array("name" => "u_lockdate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Locked Sales Date", "width"=>20 ),
					"u_locklenddate" => array("name" => "u_locklenddate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Locked Lending Date", "width"=>20 ),
					"u_lockbankdate" => array("name" => "u_lockbankdate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Locked Banking Date", "width"=>20 ),
					"u_lockinpaydate" => array("name" => "u_lockinpaydate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Locked In Pay Date", "width"=>20 ),
					"u_lockoutpaydate" => array("name" => "u_lockoutpaydate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Locked Out Pay Date", "width"=>20 ),
					"u_lockadvdate" => array("name" => "u_lockadvdate", "viewable" => true, "editable" => true, "visible" => true, "enabled" => true,
						"description" => "Locked Future Date", "width"=>18, "displayedcolumn"=>"u_lockadvdatedesc" )
			)
	);	
		

	function onFormAction($action) {
		global $httpVars;
		global $objConnection;
		global $branch;
		global $companydata;
		global $page;
		
		
		if ($action!="u_update") return true;
		
		$actionReturn = true;
		
		$objBranches = new branches(null,$objConnection);
		$objBranches->shareddatatype = "BRANCH";
		$objBranches->shareddatacode = $page->getitemstring('branch');
		
		$objConnection->beginwork();
		
		if ($objBranches->getbykey($_SESSION["company"],$page->getitemstring('branch'))) {
			if ($page->getitemstring('locksalesdate')=="1") $objBranches->setudfvalue("u_lockdate",formatDateToDB($page->getitemstring('lockdate')));
			if ($page->getitemstring('locklenddate')=="1") $objBranches->setudfvalue("u_locklenddate",formatDateToDB($page->getitemstring('lockdate')));
			if ($page->getitemstring('lockbankdate')=="1") $objBranches->setudfvalue("u_lockbankdate",formatDateToDB($page->getitemstring('lockdate')));
			if ($page->getitemstring('lockinpaydate')=="1") $objBranches->setudfvalue("u_lockinpaydate",formatDateToDB($page->getitemstring('lockdate')));
			if ($page->getitemstring('lockoutpaydate')=="1") $objBranches->setudfvalue("u_lockoutpaydate",formatDateToDB($page->getitemstring('lockdate')));
			$objBranches->setudfvalue("u_lockadvdate",$page->getitemstring('lockadvdate'));
			$actionReturn = $objBranches->update($_SESSION["company"],$objBranches->branchcode,$objBranches->rcdversion);
		} else 	$actionReturn = raiseError("Unable to retrieve branch record.");
		//if ($actionReturn) $actionReturn = raiseError("rey");
		
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();

		return $actionReturn;
	}
	
	function onFormDefault() {
		global $page;
		//$page->setitem("branch",$_SESSION["branch"]);
	}
	
	$page->reportlayouts = true;
	$page->paging->formid = "./UDP.php?&objectcode=u_postLegacyAbstractOfGeneral";
	$page->settings->load();

	//var_dump($branchdata);
	
	$lockdate = formatDateToDB($page->getitemstring('lockdate'));
	
	$page->settings->setschema($boschema_validatetransaction);
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumnsfrompagesettings($page->settings);
	
	require("./inc/formactions.php");
	
	$schema_batchpost["lockdate"] = createSchemaDate("lockdate");
	$schema_batchpost["lockadvdate"] = createSchema("lockadvdate");
	$schema_batchpost["locksalesdate"] = createSchema("locksalesdate");
	$schema_batchpost["locklenddate"] = createSchema("locklenddate");
	$schema_batchpost["lockbankdate"] = createSchema("lockbankdate");
	$schema_batchpost["lockinpaydate"] = createSchema("lockinpaydate");
	$schema_batchpost["lockoutpaydate"] = createSchema("lockoutpaydate");
	$schema_batchpost["branch"] = createSchema("branch");

	$schema_batchpost["lockdate"]["cfl"] = "Calendar";	
		
	$obj = new recordset(null,$objConnection);
	$obj->queryopen("SELECT BRANCHCODE, BRANCHNAME, MIGRATEDATE, U_LOCKDATE, U_LOCKLENDDATE, U_LOCKBANKDATE, U_LOCKINPAYDATE, U_LOCKOUTPAYDATE, U_LOCKADVDATE from BRANCHES WHERE COMPANYCODE='$company'" . $page->paging_getlimit());
	$page->paging_recordcount($obj->recordcount());
	$objGrid->clear();
	while ($obj->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"branchcode",$obj->fields["BRANCHCODE"]);
		$objGrid->setitem(null,"branchname",$obj->fields["BRANCHNAME"]);
		$objGrid->setitem(null,"migratedate",formatDate($obj->fields["MIGRATEDATE"]));
		$objGrid->setitem(null,"u_lockdate",formatDate($obj->fields["U_LOCKDATE"]));
		$objGrid->setitem(null,"u_locklenddate",formatDate($obj->fields["U_LOCKLENDDATE"]));
		$objGrid->setitem(null,"u_lockbankdate",formatDate($obj->fields["U_LOCKBANKDATE"]));
		$objGrid->setitem(null,"u_lockinpaydate",formatDate($obj->fields["U_LOCKINPAYDATE"]));
		$objGrid->setitem(null,"u_lockoutpaydate",formatDate($obj->fields["U_LOCKOUTPAYDATE"]));
		$objGrid->setitem(null,"u_lockadvdate",$obj->fields["U_LOCKADVDATE"]);
		$objGrid->setitem(null,"u_lockadvdatedesc",iif($obj->fields["U_LOCKADVDATE"]==1,"Yes","No"));
		
		if (!$page->paging_fetch()) break;
	}	
	
	$page->popup->addgroup("popupPrintTo");
	$page->popup->additem("popupPrintTo","Export to Excel","formPosting('excel')");
	$page->popup->additem("popupPrintTo","Export to PDF","formPosting('pdf')");
	$page->popup->additem("popupPrintTo","Print","formPosting('printer')");

	saveErrorMsg();
	//var_dump($sql);
	//var_dump($objGrid->recordcount);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo $page->theme ; ?>.css">
<STYLE>
</STYLE>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<script type="text/javascript">
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
var tabberOptions = {
  'manualStartup':true,
  'onLoad': function(argsObj) {},
  'onClick': function(argsObj) {return true;},
  'addLinkId': true
};
</script>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>

<script>

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	function onFormSubmit(action) {
		if(action=="u_update") {
			if (isInputEmpty("branch")) return false; 
			if (isInputEmpty("lockdate")) return false; 
			if (window.confirm("Validation: Lock Branch Date ["+getInput("lockdate")+"]. \n Do you want to continue?")==false)	return false;
		}
		showAjaxProcess();
		return true;
	}	
	
	function onEditRow(p_tableId,p_rowIdx) {
		return false;
	}

	function onDeleteRow(p_tableId,p_rowIdx) {
		return false;
	}

	function onSelectRow(p_tableId,p_idx) {
		var params = new Array();
		switch (p_tableId) {
			case "T1":
				params["focus"] = false;
				break;
		}
		return params;
	}

	function onElementChange(element,column,table,row) {
		switch (column) {
			case "branch":
				//var lockdate = formatDateToHttp(page.executeFormattedSearch("select u_lockdate from branches where companycode='" + getGlobal("company") + "' and branchcode='"+getInput("branch")+"'"));
				//setInput("lockdate",lockdate);
					
				break;
		}
		return true;
	}
	
	function onPageResize(width,height) {
		
		var divHTab =document.getElementById('divHdrT1');
		divHTab.style.width = browserInnerWidth() - 49 + 'px';
		var divDataTab =document.getElementById('divT1');
		divDataTab.style.width = browserInnerWidth() - 30 + 'px';
		divDataTab.style.height = (browserInnerHeight() - divHTab.offsetHeight - 135) + 'px';
		
		var divDataTab =document.getElementById('df_errormessages');
		divDataTab.style.width = browserInnerWidth() - 30 + 'px';
		divDataTab.style.height = (browserInnerHeight() - divHTab.offsetHeight - 125) + 'px';		
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("branch");
			inputs.push("lockdate");
			inputs.push("locksalesdate");
			inputs.push("locklenddate");
			inputs.push("lockbankdate");
			inputs.push("lockinpaydate");
			inputs.push("lockoutpaydate");
			inputs.push("lockadvdate");
		return inputs;
	}

</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="resizeObjects()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<input type="hidden" id="batchpostingmode" name="batchpostingmode" value="<?php echo $companydata["BATCHPOSTINGMODE"];  ?>">	
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td width="160"><label <?php genCaptionHtml($schema_batchpost["branch"],"") ?> >Branch</label></td>
		<td align=left colspan="2"><select <?php genSelectHtml($schema_batchpost["branch"],array("loadbrancheslist","",":[Select]")) ?> ></select></td>
    	<td >&nbsp;</td>
	  	<td width="376">&nbsp;</td>
	</tr>
	<tr><td ><label <?php genCaptionHtml($schema_batchpost["lockdate"],"") ?> >Lock Date</label></td>
		<td align=left colspan="2"><input type="text" size="15" <?php genInputTextHtml($schema_batchpost["lockdate"]) ?> />&nbsp;<a class="button" href="" onClick="formSubmit('u_update');return false;">Update</a></td>
		<td >&nbsp;</td>
   		<td >&nbsp;</td>
	</tr>
	<tr>
	  <td >&nbsp;</td>
	  <td align=left colspan="2">&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["locksalesdate"],1) ?> /><label <?php genCaptionHtml($schema_batchpost["locksalesdate"],"") ?> >Lock Sales Date</label>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["locklenddate"],1) ?> /><label <?php genCaptionHtml($schema_batchpost["locklenddate"],"") ?> >Lock Lending Date</label>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["lockbankdate"],1) ?> /><label <?php genCaptionHtml($schema_batchpost["lockbankdate"],"") ?> >Lock Banking Date</label>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["lockinpaydate"],1) ?> /><label <?php genCaptionHtml($schema_batchpost["lockinpaydate"],"") ?> >Lock InPay Date</label>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["lockoutpaydate"],1) ?> /><label <?php genCaptionHtml($schema_batchpost["lockoutpaydate"],"") ?> >Lock OutPay Date</label>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["lockadvdate"],1) ?> /><label <?php genCaptionHtml($schema_batchpost["lockadvdate"],"") ?> >Lock Future Date</label></td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	
</table></td></tr>			
<tr><td>
		<?php $objGrid->draw(true) ?>
		<?php $page->writeRecordLimit(); ?>
</td></tr>
<?php if ($requestorId == "") { ?>
	<tr><td>&nbsp;</td></tr>
	<?php //require("./sboBatchPostingToolbar.php");  ?>
<?php } ?>    
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php require("./bofrms/ajaxprocess.php"); ?>
<?php $page->writePopupMenuHTML();?>
</body>

</html>
<?php $page->writeEndScript(); ?>
<?php	
	restoreErrorMsg();

	//$parentref = "parent.";
	//$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_ValidateTransactionsToolbar.php");
?>
<?php 
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
