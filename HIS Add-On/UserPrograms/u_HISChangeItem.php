<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_HISChangeItem";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("../Addons/GPS/HIS Add-On/UserTasks/dailyroomandboardfees.php");
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
		$datefr=$page->getitemdate("datefr");
		$dateto=$page->getitemdate("dateto");
		$itemcodefr=$page->getitemstring("itemcodefr");
		$itemcodeto=$page->getitemstring("itemcodeto");
		$itemdescto=addslashes($page->getitemstring("itemdescto"));
		if ($datefr!="" || $dateto !="") {
			if ($dateto=="") $dateto = $datefr;
		}

		$filterExp = "";
		$filterExp = genSQLFilterString("a.U_DEPARTMENT",$filterExp,$httpVars['df_department']);
		$filterExp = genSQLFilterString("b.U_ITEMCODE",$filterExp,$httpVars['df_itemcodefr']);
		$filterExp = genSQLFilterDate("a.U_STARTDATE",$filterExp,$page->getitemdate("datefr"),$page->getitemdate("dateTO"));
		if ($filterExp !="") $filterExp = " AND " . $filterExp;

		$filterExp2 = "";
		$filterExp2 = genSQLFilterString("a.U_DEPARTMENT",$filterExp2,$httpVars['df_department']);
		$filterExp2 = genSQLFilterString("b.U_ITEMCODE",$filterExp2,$httpVars['df_itemcodefr']);
		$filterExp2 = genSQLFilterDate("a.U_REQUESTDATE",$filterExp2,$page->getitemdate("datefr"),$page->getitemdate("dateTO"));
		if ($filterExp2 !="") $filterExp2 = " AND " . $filterExp2;

		$filterExp3 = "";
		$filterExp3 = genSQLFilterString("b.DRCODE",$filterExp3,$httpVars['df_department']);
		$filterExp3 = genSQLFilterString("b.ITEMCODE",$filterExp3,$httpVars['df_itemcodefr']);
		$filterExp3 = genSQLFilterDate("a.DOCDATE",$filterExp3,$page->getitemdate("datefr"),$page->getitemdate("dateTO"));
		if ($filterExp3 !="") $filterExp3 = " AND " . $filterExp3;
		
		//$objRs->setdebug();	
		//$objRs2->setdebug();	

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid from u_hisrequests a inner join u_hisrequestitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp2 group by a.docno");
			while ($objRs->queryfetchrow()) {
				$actionReturn = $objRs2->executesql("update u_hisrequestitems set u_itemcode='".$itemcodeto."',u_itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid from u_hischarges a inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp group by a.docno");
			while ($objRs->queryfetchrow()) {
				$actionReturn = $objRs2->executesql("update u_hischargeitems set u_itemcode='".$itemcodeto."',u_itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid from u_hiscredits a inner join u_hiscredititems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp group by a.docno");
			while ($objRs->queryfetchrow()) {
				$actionReturn = $objRs2->executesql("update u_hiscredititems set u_itemcode='".$itemcodeto."',u_itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from arinvoices a inner join arinvoiceitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update arinvoices set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR','SI') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR','SI') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update arinvoiceitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from arcreditmemos a inner join arcreditmemoitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update arinvoices set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update arcreditmemoitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from salesdeliveries a inner join salesdeliveryitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update salesdeliveries set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('DN') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('DN') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update salesdeliveryitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from salesreturns a inner join salesreturnitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update salesreturns set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('RT') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('RT') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update salesreturnitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}
				
		//var_dump($objRs->sqls);
		//var_dump($objRs2->sqls);
		//var_dump($datefr);
		//var_dump($dateto);
		//if ($actionReturn) $actionReturn = raiseError("onFormAction()");
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
	
	$schema_batchpost["datefr"] = createSchemaDate("datefr");
	$schema_batchpost["dateto"] = createSchemaDate("dateto");
	$schema_batchpost["department"] = createSchema("department");
	$schema_batchpost["itemcodefr"] = createSchema("itemcodefr");
	$schema_batchpost["itemdescfr"] = createSchema("itemdescfr");
	$schema_batchpost["itemcodeto"] = createSchema("itemcodeto");
	$schema_batchpost["itemdescto"] = createSchema("itemdescto");

	$schema_batchpost["itemcodefr"]["cfl"] = "OpenCFLfs()";
	$schema_batchpost["itemcodeto"]["cfl"] = "OpenCFLfs()";
	$schema_batchpost["itemdescfr"]["cfl"] = "OpenCFLfs()";
	$schema_batchpost["itemdescto"]["cfl"] = "OpenCFLfs()";
	
	//$schema_batchpost["itemdescfr"]["attributes"] = "disabled";
///	$schema_batchpost["itemdescto"]["attributes"] = "disabled";
	//$schema_batchpost["datefr"]["cfl"] = "Calendar";	
	//$schema_batchpost["dateto"]["cfl"] = "Calendar";	

	$schema_batchpost["department"]["required"] = true;
	$schema_batchpost["itemcodefr"]["required"] = true;
	$schema_batchpost["itemcodeto"]["required"] = true;
	
	saveErrorMsg();
	//var_dump($sql);
	//var_dump($objGrid->recordcount);
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
			if (isInputEmpty("department")) return false; 
			if (isInputEmpty("itemcodefr")) return false; 
			if (isInputEmpty("itemcodeto")) return false; 
			if (getInput("itemcodefr")==getInput("itemcodeto")) {
				page.statusbar.showWarning("You cannot change same item.");
				return false;
			}
		}
		showAjaxProcess();
		return true;
	}	

	function onFormSubmitReturn(action,sucess,error) {
		if (sucess)	alert('Process ended successfully.');
		else alert(error);
	}

	function onElementValidate(element,column,table,row) {
		switch(table) {
			default:	
				switch(column) {
					case "itemcodefr":
					case "itemdescfr":
						if (getInput(column)!="") {
							if (column=="itemcodefr") result = page.executeFormattedQuery("select code, name from u_hisitems where code='"+utils.addslashes(getInput(column))+"'");	 
							else result = page.executeFormattedQuery("select code, name from u_hisitems where name like '"+utils.addslashes(getInput(column))+"%'");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("itemcodefr",result.childNodes.item(0).getAttribute("code"));
									setInput("itemdescfr",result.childNodes.item(0).getAttribute("name"));
								} else {
									setInput("itemcodefr","");
									setInput("itemdescfr","");
									page.statusbar.showError("Invalid Item.");	
									return false;
								}
							} else {
								setInput("itemcodefr","");
									setInput("itemdescfr","");
									page.statusbar.showError("Error retrieving item. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("itemcodefr","");
							setInput("itemdescfr","");
						}
						break;
					case "itemcodeto":
					case "itemdescto":
						if (getInput(column)!="") {
							if (column=="itemcodeto") result = page.executeFormattedQuery("select code, name from u_hisitems where code='"+utils.addslashes(getInput(column))+"'");	 
							else result = page.executeFormattedQuery("select code, name from u_hisitems where name like '"+utils.addslashes(getInput(column))+"%'");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("itemcodeto",result.childNodes.item(0).getAttribute("code"));
									setInput("itemdescto",result.childNodes.item(0).getAttribute("name"));
								} else {
									setInput("itemcodeto","");
									setInput("itemdescto","");
									page.statusbar.showError("Invalid Item.");	
									return false;
								}
							} else {
								setInput("itemcodeto","");
								setInput("itemdescto","");
								page.statusbar.showError("Error retrieving item. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("itemcodeto","");
							setInput("itemdescto","");
						}
						break;
				}
				break;
		}
		return true;
	}
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {
			case "df_itemcodefr":
			case "df_itemcodeto":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems")); 
				break;
			case "df_itemdescfr":
			case "df_itemdescto":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems")); 
				break;
		}
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
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["department"],"") ?> >Section:</label></td>
	  <td align=left colspan="2">&nbsp;<select <?php genSelectHtml($schema_batchpost["department"],array("loadudflinktable","u_hissections:code:name",":")) ?> /></select></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["itemcodefr"],"") ?> >From Item Code:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["itemcodefr"]) ?> />&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema_batchpost["itemdescfr"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["itemcodeto"],"") ?> >Change To Item Code:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["itemcodeto"]) ?> />&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema_batchpost["itemdescto"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["datefr"],"") ?> >From Date:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["datefr"]) ?> /></td>
		<td >&nbsp;</td>
   	</tr>
	
	<tr>
	  <td><label <?php genCaptionHtml($schema_batchpost["dateto"],"") ?> >To Date:</label></td>
	  <td colspan="2">&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["dateto"]) ?> /></td>
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
