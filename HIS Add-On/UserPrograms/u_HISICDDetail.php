<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./series.php");
	include_once("./classes/chartofaccounts.php");
	include_once("./classes/masterdataschema.php");
	include_once("./schema/masterdataschema.php");
	include_once("./sls/areas.php");
	include_once("./sls/countries.php");
	include_once("./sls/provinces.php");
	include_once("./sls/cities.php");
	include_once("./sls/barangays.php");
	include_once("./sls/enumwhsmanagebymethod.php");
	include_once("./sls/enumwhstffifoagemethod.php");
	include_once("./sls/enumwhsdropship.php");
	include_once("./sls/enumcashflowactivities.php");
	include_once("./sls/chartofaccountsubsidiarygroups.php");
	include_once("./utils/companies.php");
	include_once("./utils/branches.php");
	include_once("./utils/chartofaccounts.php");
	include_once("./inc/formutils.php");
	include_once("./boschema/warehouse.php");
	$progaccessid="u_hisicdtree";
	$progid="u_hisicdDetail";
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
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<STYLE>
</STYLE>

<HEAD><TITLE>Project Activity</TITLE>
</HEAD>

<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/chartofaccounts.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatechartofaccounts.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatechartofaccounts.js" type=text/javascript></SCRIPT>
<script type="text/javascript">
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
var tabberOptions = {
  'manualStartup':true,
  'onLoad': function(argsObj) {},
  'onClick': function(argsObj) { return true;},
  'addLinkId': true
};
</script>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>

<script>

	function formUpdateOptions(element) {
		popupUpdateOptions(browserInnerWidth() - 211,browserInnerHeight());
		return false;
	}

	function onFormSubmit(p_action) {
		if ((p_action == "a") || (p_action == "sc")) {
			if (isInputEmpty("code")) return false;	
			if (isInputEmpty("name")) return false;	
		}
		return true;
	}
	
	function onFormSubmitReturn(action,success,error) {
		var fielddesc= getInput("fielddesc");
		if (success) {
			try {
				switch (action) {
					case 'a':
						window.opener.refreshTreeviewNode("T",'');
						break;
					case 'sc':
					case 'd':
						window.opener.refreshTreeviewNode("F",getInput("refreshcode"));
						break;	
				}	
			} catch(theError) {
			}	
			window.close();
		}
		
	}
	
	
</script>
<?php
	$updateoptions=false;

	function onFormEdit($opt=null) {
		global $httpVars;
		global $page;
		global $obju_HISICDs;
		global $objGrid;
		
		$sf_keys = split("`",$httpVars["sf_keys"]);
		switch ($opt) {
			default: 
				//$keyexp = "ACCTCODE = '" . $sf_keys[0] . "' OR ACCTCODE = '_SYS". str_pad($sf_keys[0], 11, "0", STR_PAD_LEFT) ."'";
				$keyexp = "CODE = '" . $sf_keys[0] . "'";
				if (intval($sf_keys[1])>0) $keyexp .= " and RCDVERSION = " . intval($sf_keys[1]);	
				$orderexp = "";	break;
		}
		//$obju_HISICDs->setdebug();
		$actionReturn = $obju_HISICDs->getbysql($keyexp . $orderexp);
		if ($actionReturn) {
			if ($obju_HISICDs->formatcode=="") $obju_HISICDs->formatcode=$obju_HISICDs->acctcode;
			$httpVars = array_merge($httpVars,$obju_HISICDs->sethttpfields());
		} else {
			switch ($opt) {
				case "prev": modeEdit("first");	break;
				case "next": modeEdit("last"); break;
				default : modeDefault(); break;
			}
		}	

		return $actionReturn;
	}
	
	function onFormAdd() {
		global $httpVars;
		global $objConnection;
		global $obju_HISICDs;
		$objConnection->beginwork();
		$obju_HISICDs->prepareadd();
		$obju_HISICDs->assignhttpdatafields();
		
		$actionReturn = $obju_HISICDs->add();
		
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();

		if ($actionReturn) {
			$httpVars = array_merge($httpVars,$obju_HISICDs->sethttpfields());	//update httpvars if key is modified
			modeEdit();	// execute edit mode routine
		}	
		
		return $actionReturn;
	}

	function onFormSaveChanges() {
		global $httpVars;
		global $page;
		global $objConnection;
		global $obju_HISICDs;
		
		$objConnection->beginwork();
		
		$sf_keys = split("`",$httpVars["sf_keys"]);
		$actionReturn = $obju_HISICDs->prepareupdate($sf_keys[0], $sf_keys[1]);
		if ($actionReturn) {
			$obju_HISICDs->assignhttpdatafields();
			if ($actionReturn) $actionReturn = $obju_HISICDs->update($sf_keys[0], $sf_keys[1]);
		} 
		
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();

		if ($actionReturn) {
			$httpVars = array_merge($httpVars,$obju_HISICDs->sethttpfields());	//update httpvars if key is modified
			modeEdit();	// execute edit mode routine
		}	
			
		return $actionReturn;
	}
	
	function onFormDelete() {
		global $page;
		global $httpVars;
		global $objConnection;
		global $obju_HISICDs;
		
		$objConnection->beginwork();
		
		$parentacct ="";
		$sf_keys = split("`",$httpVars["sf_keys"]);
		$actionReturn = $obju_HISICDs->getbykey($sf_keys[0], $sf_keys[1]);
		if ($actionReturn) {
			switch ($obju_HISICDs->getudfvalue("u_level")) {
				case "1":
					break;
				case "2":
					$parentacct = $obju_HISICDs->getudfvalue("u_chapter");
					break;
				default:
					$parentacct = $obju_HISICDs->getudfvalue("u_block");
					break;
			}
			switch ($obju_HISICDs->getudfvalue("u_level")) {
				case "1":
					$page->setitem("refreshcode","");
					break;
				case "2":
					$page->setitem("refreshcode",$obju_HISICDs->getudfvalue("u_chapter"));
					break;
				default:
					$page->setitem("refreshcode",$obju_HISICDs->getudfvalue("u_block"));
					break;
			}
			if ($actionReturn) $actionReturn = $obju_HISICDs->delete();
		} 
		
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();

		if ($actionReturn) {
			$httpVars["sf_keys"] = $parentacct;
			modeDefault();
		} else {
			modeEdit();
		}	
		
		
		return $actionReturn;
	}

	function onFormDefault() {
		global $httpVars;
		global $page;
		global $obju_HISICDs;
		$sf_keys = split("`",$httpVars["sf_keys"]);
		$actionReturn = $obju_HISICDs->getbysql("CODE='".$sf_keys[0]."'");
		if ($actionReturn) {
			$chapter = $obju_HISICDs->getudfvalue("u_chapter");
			$block = $obju_HISICDs->getudfvalue("u_block");
			$level = $obju_HISICDs->getudfvalue("u_level");
			$activitycategory = $obju_HISICDs->getudfvalue("u_activitycategory");
			$obju_HISICDs->clear();
			switch ($level) {
				case "1":
					$obju_HISICDs->setudfvalue("u_chapter",$sf_keys[0]);
					$obju_HISICDs->setudfvalue("u_level",2);
					break;
				case "2":
					$obju_HISICDs->setudfvalue("u_chapter",$chapter);
					$obju_HISICDs->setudfvalue("u_block",$sf_keys[0]);
					$obju_HISICDs->setudfvalue("u_level",3);
					break;
				default:
					$obju_HISICDs->setudfvalue("u_chapter",$chapter);
					$obju_HISICDs->setudfvalue("u_block",$block);
					$obju_HISICDs->setudfvalue("u_level",3);
					break;
			}
			$httpVars = array_merge($httpVars,$obju_HISICDs->sethttpfields());
		}
	}
	
	$schema["u_chapter"] = createSchema("u_chapter","Chapter");
	$schema["u_block"] = createSchema("u_block","Block");
	$schema["u_level"] = createSchema("u_level","Level");
	$schema["u_classplace"] = createSchema("u_classplace","Place in Classification Tree");
	$schema["u_terminalnode"] = createSchema("u_terminalnode","Terminal Node");
	$schema["u_mortality1"] = createSchema("u_mortality1","Mortality 1");
	$schema["u_mortality2"] = createSchema("u_mortality2","Mortality 2");
	$schema["u_mortality3"] = createSchema("u_mortality3","Mortality 3");
	$schema["u_mortality4"] = createSchema("u_mortality4","Mortality 4");
	$schema["u_morbidity"] = createSchema("u_morbidity","Morbidity");
	
	$obju_HISICDs = new masterdataschema(NULL,$objConnection,"u_hisicds");
	$page->objectcode = "u_hisicds";
	$page->getsettings();
	$page->settings->setschema($boschema_warehouse);
	
	$page->popup->addgroup("popupUpdateOptions");
	$page->popup->additem("popupUpdateOptions","Remove","formDelete()");
	$updateoptions = true;

	$page->settings->load();	
	
	require("./inc/formactions.php");
	
	$schema_masterdataschema["code"]["required"] = true;
	$schema_masterdataschema["name"]["required"] = true;
	
	$page->toolbar->setaction("find",false);
	$page->toolbar->setaction("navigation",false);
	$page->toolbar->setaction("print",false);
	
	//$page->resize->addtabpage("tab1","tab1General");
	//$page->resize->addtab("tab1",-1,160);
	
	$schema["u_chapter"]["attributes"] = "disabled";
	$schema["u_block"]["attributes"] = "disabled";
	
	if (isEditMode()) {
		$schema_masterdataschema["code"]["attributes"] = "disabled";
	}
		
	saveErrorMsg();
	
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
<input type="hidden" <?php genInputHiddenDFHtml("refreshcode") ?> >
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
			<?php if ($page->getitemstring("u_level")=="1" || $page->getitemstring("u_level")=="2") { ?>
				<input type="hidden" <?php genInputHiddenDFHtml("u_level") ?> >
			<?php } ?>
	  	</tr>
	</table></td></tr>
	<tr><td>&nbsp;</td></tr>
        <tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
			<?php if ($page->getitemstring("u_level")=="1") { ?>
				<tr><td width="220" ><label <?php genCaptionHtml($schema_masterdataschema["code"]) ?>>Chapter</label></td>
					<td >&nbsp;<input type="text" <?php genInputTextHtml($schema_masterdataschema["code"]) ?> /></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml($schema_masterdataschema["name"]) ?> >Description</label></td>
					<td >&nbsp;<input type="text" size="100" <?php genInputTextHtml($schema_masterdataschema["name"]) ?> /></td>
				</tr>
			<?php } elseif ($page->getitemstring("u_level")=="2") { ?>
				<tr><td width="220" ><label <?php genCaptionHtml($schema_masterdataschema["code"]) ?>>Block</label></td>
					<td >&nbsp;<input type="text" <?php genInputTextHtml($schema_masterdataschema["code"]) ?> /></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml($schema_masterdataschema["name"]) ?> >Description</label></td>
					<td >&nbsp;<input type="text" size="100" <?php genInputTextHtml($schema_masterdataschema["name"]) ?> /></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml($schema["u_chapter"]) ?>>Chapter</label></td>
					<td >&nbsp;<select <?php genSelectHtml($schema["u_chapter"],array("loadudflinktable","u_hisicds where code='".$page->getitemstring("u_chapter")."':code:name"),null,null,null,"width:725px") ?>></select></td>
				</tr>
			<?php } else { ?>			
				<tr><td width="220" ><label <?php genCaptionHtml($schema_masterdataschema["code"]) ?>>Code</label></td>
					<td >&nbsp;<input type="text" <?php genInputTextHtml($schema_masterdataschema["code"]) ?> /></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml($schema_masterdataschema["name"]) ?> >Description</label></td>
					<td >&nbsp;<input type="text" size="100" <?php genInputTextHtml($schema_masterdataschema["name"]) ?> /></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml($schema["u_chapter"]) ?>>Chapter</label></td>
					<td >&nbsp;<select <?php genSelectHtml($schema["u_chapter"],array("loadudflinktable","u_hisicds where code='".$page->getitemstring("u_chapter")."':code:name"),null,null,null,"width:725px") ?>></select></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml($schema["u_block"]) ?>>Block</label></td>
					<td >&nbsp;<select <?php genSelectHtml($schema["u_block"],array("loadudflinktable","u_hisicds where code='".$page->getitemstring("u_block")."':code:name"),null,null,null,"width:725px") ?>></select></td>
				</tr>
				
				<tr><td ><label <?php genCaptionHtml("u_level") ?>>Level</label></td>
					<td >&nbsp;<select <?php genSelectHtml("u_level",array("loadudfenums","u_hisicds:level")) ?>></select></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_classplace") ?>>Place in Classification Tree</label></td>
					<td >&nbsp;<select <?php genSelectHtml("u_classplace",array("loadudfenums","u_hisicds:classplace")) ?>></select></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_terminalnode") ?>>Terminal Node</label></td>
					<td >&nbsp;<select <?php genSelectHtml("u_terminalnode",array("loadudfenums","u_hisicds:terminalnode")) ?>></select></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_mortality1") ?>>Mortality 1</label></td>
					<td>&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["u_mortality1"]) ?>/></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_mortality2") ?>>Mortality 2</label></td>
					<td>&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["u_mortality2"]) ?>/></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_mortality3") ?>>Mortality 3</label></td>
					<td>&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["u_mortality3"]) ?>/></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_mortality4") ?>>Mortality 4</label></td>
					<td>&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["u_mortality4"]) ?>/></td>
				</tr>
				<tr><td ><label <?php genCaptionHtml("u_morbidity") ?>>Morbidity</label></td>
					<td>&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["u_morbidity"]) ?>/></td>
				</tr>
			<?php } ?>			
        </table></td></tr>	
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center">
<table width="100%" cellpadding="1" cellspacing="0" border="0">
	<tr> 
		<td align="right">
		<?php if ($formType=="popup") { ?>
			<?php if ($httpVars["formAccess"]!="R") { ?>
			<?php if($currentAction=="e") { ?>
				<a class="button" href="" onClick="formDelete();return false;" >Remove</a>&nbsp;
			<?php } ?>
			<a class="button" href="" onClick="formSubmit();return false;" ><?php if($currentAction=="e") echo "Update"; else echo "Add"; ?></a>&nbsp;
			<?php } ?>
			<a class="button" href="" onClick="window.close();return false;" >Close</a>
		<?php } ?>
		</td>
	</tr>
</table>
	</td></tr>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php $page->writePopupMenuHTML();?>
</BODY>
</HTML>
<?php
	$page->writeEndScript();	
	restoreErrorMsg();
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&updateoptions=$updateoptions&formNoFind=1&currentAction=" . $currentAction . "&toolbarscript=./udp.php?objectcode=u_ProjectActivityTreeToolbar";
?>
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
