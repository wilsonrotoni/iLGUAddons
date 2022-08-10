<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./series.php");
	include_once("./classes/paymentterms.php");
	include_once("./classes/paymenttermpricelists.php");
	include_once("./schema/paymentterms.php");
	include_once("./schema/paymenttermpricelists.php");
	include_once("./bogridschema/paymentterm.php");
	include_once("./utils/branches.php");
	include_once("./inc/formutils.php");
	include_once("./sls/enuminstallmentaddonratecomputemethod.php");
	$progid="u_hisicdtree";
	include_once("./inc/formaccess.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo @$_SESSION["theme"] ; ?>.css">
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
<link rel="stylesheet" type="text/css" href="css/treeview.css">
<link rel="stylesheet" type="text/css" href="css/treeview_<?php echo @$_SESSION["theme"] ; ?>.css">
<STYLE>
</STYLE>

<HEAD><TITLE>User Profile</TITLE>
</HEAD>

<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajaxtreeview.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/treeview.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetchartofaccountsdata.js" type=text/javascript></SCRIPT>
<script language="JavaScript">
	function onTreeviewExpanding(treeid,beforeevent)	{
		if (beforeevent)showAjaxProcess();
		else hideAjaxProcess();
	}

	function refreshTreeviewNode(itemtype,parentid) {
		switch (itemtype) {
			case "T":
				expandTreeview(getTreeviewSelectedItemId("tree1"),getTreeviewSelectedId("tree1"));
				break;
			case "F":
				treeitemid = getTreeviewSelectedItemId("tree1");
				treeitemid = treeitemid.substring(0,treeitemid.lastIndexOf('_'));
				try {
					expandTreeview(treeitemid,parentid);
				} catch (e)	{
					collapseTreeview(treeitemid,parentid);
				}
				break;	
		}
	}
	
	function onTreeviewDblClick(treeid,itemid,treeitemid)	{
		var result;
		//result = ajaxxmlgetchartofaccountsdata(itemid,"");	
		//switch (result.getAttribute("itemtype")) {
		//	case "1":
		//	case "F":
				OpenChartOfAccountData(itemid,"userfield");
		//		break;
		//	default:
		//		setStatusMsg("Not allowed on group.",null,1);	
		//}			
	}
	
	function onPageLoad() {
		loadTreeview('tree1',0,'udp.php?objectcode=u_ajaxHISICDTreeview&ajax=1');
	}

	function OpenChartOfAccountData(itemid,elementId) {
		//OpenPopup(557,210,'./udp.php?objectcode=u_HISICDDetail' + '&formAction=e' + '&sf_keys=' + itemid,elementId);
		OpenPopup(1024,330,'./udp.php?objectcode=u_HISICDDetail' + '&formAction=e' + '&sf_keys=' + itemid,elementId);
	}
	
	function addChild() {
		if (treeviewSelectedId['tree1']==undefined) {
			setStatusMsg("No currently selected item.");
			return false;
		}
		//OpenPopup(557,400,'./udp.php?objectcode=u_HISICDDetail' + '' + '&sf_keys=' + treeviewSelectedId['tree1'],"userfield");
		OpenPopup(1024,330,'./udp.php?objectcode=u_HISICDDetail' + '' + '&sf_keys=' + treeviewSelectedId['tree1'],"userfield");
	}

</script>
<?php

	require("./inc/formactions.php");
	
		
	saveErrorMsg();
	$page->resize->addtreeview("tree1",20,60);
	
?>

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<FORM name="formData" autocomplete="off" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<TR>
<TD  WIDTH="100%" ALIGN=LEFT>
<input type="hidden" name="targetId" value="<?php echo $targetId;  ?>">
<input type="hidden" name="lookupSortBy" value="<?php echo $lookupSortBy;  ?>">
<input type="hidden" name="lookupSortAs" value="<?php echo $lookupSortAs;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml($schema_paymentterms["paymentterm"]) ?> >
<!--<?php genTableVarsHtml("T1") ?>-->
<?php require("./inc/formobjs.php") ?>

</TD>
</TR>
</TABLE>

<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
	<tr class="fillerRow10px"><td></td></tr>
	<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
       	<tr>
       	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader ?>&nbsp;</td>
            <td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
       	       	<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	  	</tr>
	</table></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><div id="divtree1" class="treemain" style="width:300px;height:300px">
	    <div id="tree1" class="nodecls"></div>
    </div></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center">
			<?php //if ($requestorId == "")	require("./PaymentTermsToolbar.php");  ?>
	</td></tr>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
<?php require("./bofrms/ajaxprocess.php"); ?>
</FORM>
<?php $page->writePopupMenuHTML();?>
</BODY>
</HTML>
<?php $page->writeEndScript(); ?>
<?php	
	restoreErrorMsg();
	$htmltoolbarbottom = "./bottomtoolbar.php?&requestorId=".$httpVars["requestorId"]."&formAccess=" . $httpVars['formAccess'] . "&currentAction=" . $currentAction . "&toolbarscript=./ChartOfAccountToolbar.php";
?>
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
