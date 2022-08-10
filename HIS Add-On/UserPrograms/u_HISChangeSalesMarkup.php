<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_HISChangeSalesMarkup";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/companies.php");
	$post = false;
	$validate = false;
	$page->objectcode = $progid;
	$httpVars["df_errormessages"] = "";

		
	function onFormAction($action) {
		global $httpVars;
		global $objConnection;
		global $branch;
		global $branchdata;
		global $page;
		
		$actionReturn = true;
		
		if ($action!="u_process") return true;
		
		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		$objConnection->beginwork();
		
		$companydata = getcurrentcompanydata("PURCHASINGPRICELIST");


		$itemgroup=$page->getitemstring("itemgroup");
		$opd=$page->getitemdecimal("opd");
		$ipd=$page->getitemdecimal("ipd");
		//var_dump(array($companydata["PURCHASINGPRICELIST"],$itemgroup,$opd,$ipd));

		if ($actionReturn) {
			$objRs->queryopen("select code, name from u_hisitems where u_salespricing=1 and u_group='$itemgroup'");
			while ($objRs->queryfetchrow("NAME")) {
				$actionReturn = $objRs2->executesql("update u_hisitems set u_salesmarkup='$ipd', u_salesmarkup2='$opd' where code='".$objRs->fields["code"]."'",false);
				if (!$actionReturn) break;
				$actionReturn = $objRs2->executesql("delete from itempricelists where itemcode='".$objRs->fields["code"]."' and pricelist<>'".$companydata["PURCHASINGPRICELIST"]."'",false);
				if (!$actionReturn) break;
				$objRs2->queryopen("select price from itempricelists where itemcode='".$objRs->fields["code"]."' and pricelist='".$companydata["PURCHASINGPRICELIST"]."'");
				if ($objRs2->queryfetchrow("NAME")) {
					
					$price = round($objRs2->fields["price"]*(1+($opd/100)),2);
					$actionReturn = $objRs2->executesql("insert into ITEMPRICELISTS (ITEMCODE,PRICELIST,PRICE,DATECREATED,CREATEDBY,LASTUPDATED,LASTUPDATEDBY,RCDVERSION,CURRENCY,ADDONRATECOMPUTEMETHOD,ADDONRATE,ADDONAMT,REBATE,DOWNPAYMENT,AOC,PRICETYPE,MANUALPRICING,STDPRICE,REMARKS)VALUES ('".$objRs->fields["code"]."','1','".$price."','2014-04-04 16:05:48','manager','2014-04-09 17:43:31','manager','1','PHP','-1','0.000000','0.000000','0.000000','0.000000','0.000000','P','0','0.000000','');
",false);
					if ($actionReturn) {
						$price = round($objRs2->fields["price"]*(1+($ipd/100)),2);
						$actionReturn = $objRs2->executesql("insert into ITEMPRICELISTS (ITEMCODE,PRICELIST,PRICE,DATECREATED,CREATEDBY,LASTUPDATED,LASTUPDATEDBY,RCDVERSION,CURRENCY,ADDONRATECOMPUTEMETHOD,ADDONRATE,ADDONAMT,REBATE,DOWNPAYMENT,AOC,PRICETYPE,MANUALPRICING,STDPRICE,REMARKS) select '".$objRs->fields["code"]."',pricelist,'".$price."','2014-04-04 16:05:48','manager','2014-04-09 17:43:31','manager','1','PHP','-1','0.000000','0.000000','0.000000','0.000000','0.000000','P','0','0.000000','' from pricelists where bptype='C' and pricelist<>'1'",false);
					}
					
				}
				
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();


		return $actionReturn;
	}
	
	function onFormDefault() {
		global $page;
		global $branchdata;
		
	}
	
	$page->reportlayouts = true;
	$page->settings->load();

	//var_dump($branchdata);
	
	$objrs = new recordset(NULL,$objConnection);
	$objrs2 = new recordset(NULL,$objConnection);
	$objrs->logtrx = false;
	
	$actionReturn=true;
	
		
	
	require("./inc/formactions.php");
	
	$schema_batchpost["itemgroup"] = createSchema("itemgroup");
	$schema_batchpost["opd"] = createSchemaPercent("opd","",0);
	$schema_batchpost["ipd"] = createSchemaPercent("ipd","",0);
	
	
	$schema_batchpost["itemgroup"]["required"] = true;
	$schema_batchpost["opd"]["required"] = true;
	$schema_batchpost["ipd"]["required"] = true;
	
	saveErrorMsg();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
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
<SCRIPT language=JavaScript src="cfls/directories.js"></SCRIPT>
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
	
	function onPageLoad() {
	}
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "datefr":
				setInput("dateto",getInput("datefr"));
				break;
		}
	}
	
	function onFormSubmit(action) {
		if(action=="u_process") {
			if (isInputEmpty("itemgroup")) return false; 
			if (isInputNegative("opd")) return false; 
			if (isInputNegative("ipd")) return false; 
		}
		showAjaxProcess();
		return true;
	}	

	function onFormSubmitReturn(action,sucess,error) {
		if (sucess)	alert('Process ended successfully.');
		else alert(error);
	}

	function onElementValidate(element,column,table,row) {
		return true;
	}
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		return params;
	}	
	
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
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
	
	<tr>
	  <td align=left>&nbsp;</td>
	  <td align=left colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>

		  
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["itemgroup"],"") ?> >Item Group:</label></td>
	  <td align=left colspan="2">&nbsp;<select <?php genSelectHtml($schema_batchpost["itemgroup"],array("loadudflinktable","itemgroups:itemgroup:itemgroupname",":")) ?> /></select></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["opd"],"") ?> >OPD Markup %:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema_batchpost["opd"]) ?> />&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["ipd"],"") ?> >IPD Markup %:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema_batchpost["ipd"]) ?> />&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="2">&nbsp;<a class="button" href="" onClick="formSubmit('u_process');return false;">Execute</a></td>
	  <td >&nbsp;</td>
	  </tr>
	
</table></td></tr>			
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
	//$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_DataMigrationToolbar.php");
?>
<?php 
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
