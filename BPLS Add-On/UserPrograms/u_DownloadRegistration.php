<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/utility/system.php");
	include_once("./classes/masterdataschema.php");
	include_once("./sls/countries.php");
	include_once("./sls/enumportalproducts.php");
	include_once("./inc/formutils.php");
	//include_once("./inc/formaccess.php");
	$progid = "DownloadRegistration";
	
	//unset($enumportalmodules['PURCHASING'],$enumportalmodules['INVENTORY'],$enumportalmodules['G/L']);
	
	$enumdescribesyou = array(
                         'RESEARCH' => 'Evaluating eBT for research', 
						 'PURCHASE'    => 'Evaluating eBT for purchase',
						 'DEVELOPMENT'    => 'Evaluating eBT for development'
						 );
	$enumhelpme = array(
                         'TRAINING' => 'Training', 
						 'SUPPORT'    => 'Support',
						 'IMPLEMENT'    => 'Implementation Services',
						 'INTEGRATE'    => 'Integration',
						 'SDK'    => 'Software Development Kit',
						 'NONE'    => 'Non of the above'
						 );

	$enumregtypes = array(
                         '0' => 'Download and Install', 
						 '1' => 'Cloud Computing');
						 

	if (!$_SESSION["addons"]) {
		$_SESSION["addons"] = getAddOns(getAvailableAddOns(),false);
	}	

	function loadenumregtypes($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumregtypes;
		reset($enumregtypes);
		while (list($key, $val) = each($enumregtypes)) {
			$_selected = "";
			if ($p_selected == strval($key)) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	function loadenumdescribesyou($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumdescribesyou;
		reset($enumdescribesyou);
		while (list($key, $val) = each($enumdescribesyou)) {
			$_selected = "";
			if ($p_selected == strval($key)) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	function loadenumhelpme($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumhelpme;
		reset($enumdescribesyou);
		while (list($key, $val) = each($enumhelpme)) {
			$_selected = "";
			if ($p_selected == strval($key)) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
	
	$messagesucess = "Registration was successfull. An email was sent for the activation or download."
?>

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

<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<script>

function formSubmit(action) {
	switch (action) {
		case "register":
			if (isInputEmpty("firstname")) return false;
			if (isInputEmpty("lastname")) return false;
			if (isInputEmpty("email")) return false;
			if (isInputEmpty("country")) return false;
			if (isInputEmpty("describeyou")) return false;
			if (isInputEmpty("helpme")) return false;
			document.formData.formAction.value = "register";
			document.formData.submit();
			break;
	}	
	return false;
}
</script>

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<FORM name="formData" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?">

<?php

require("./inc/formobjs.php");

	function onFormAction($action) {
		global $httpVars;
		global $objConnection;
		global $page;
		global $obju_Registration;
		$actionReturn = true;
		
		switch ($action) {
			case "register":
				$objConnection->beginwork();
				
				if (!$obju_Registration->getbykey(trim($page->getitemstring("u_email")))) {
					$obju_Registration->prepareadd();
					$obju_Registration->code = trim($page->getitemstring("u_email"));
					$obju_Registration->name = strtoupper($page->getitemstring("u_firstname")) . " " . strtoupper($page->getitemstring("u_lastname"));
					$obju_Registration->assignhttpdatafields();
					//$page->console->insertVar($obju_Registration->userfields);
					$actionReturn = $obju_Registration->add();
				} else $actionReturn = raiseError("This email is already registered.");
				//if ($actionReturn) $actionReturn = raiseError("error");
				if ($actionReturn) {
					$objConnection->commit();
					$obju_Registration->prepareadd();
					$httpVars = array_merge($httpVars,$obju_Registration->sethttpfields());
				} else $objConnection->rollback();
				break;
		}			
		return $actionReturn;
		
	} # end onFormAdd()

	function onFormDefault() {
		global $httpVars;
		global $objConnection;
		global $page;
		global $obju_Registration;
		$obju_Registration->prepareadd();
		$httpVars = array_merge($httpVars,$obju_Registration->sethttpfields());
	}


	saveErrorMsg();
	
	$page->settimeout(0);
	$obju_Registration = new masterdataschema ("Registration",$objConnection,"u_registration");	
	
	$schema_about = array();
	$schema_about["u_firstname"] = createSchema("u_firstname","First Name");
	$schema_about["u_lastname"] = createSchema("u_lastname","Last Name");
	$schema_about["u_email"] = createSchema("u_email","Email");
	$schema_about["u_contactno"] = createSchema("u_contactno","Phone");
	$schema_about["u_companyname"] = createSchema("u_companyname","Company");
	$schema_about["u_country"] = createSchema("u_country","Country");


	$schema_about["u_contactme"] = createSchema("u_contactme","Please have a eBT expert contact me.");
	$schema_about["u_describeyou"] = createSchema("u_describeyou","What best describes you?");
	$schema_about["u_sendmepromos"] = createSchema("u_sendmepromos","Please send me newsletters, offers and promos.");
	$schema_about["u_questions"] = createSchema("u_questions","Include any questions or comments:");
	$schema_about["u_helpme"] = createSchema("u_helpme","I would like help from eBT with:");

	$schema_about["u_iscloud"] = createSchema("u_iscloud","Cloud Computing");
	
	$schema_about["u_firstname"]["required"] = true;
	$schema_about["u_lastname"]["required"] = true;
	$schema_about["u_email"]["required"] = true;
	$schema_about["u_country"]["required"] = true;

	$schema_about["u_describeyou"]["required"] = true;
	$schema_about["u_helpme"]["required"] = true;

	require("./inc/formactions.php");
	
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
	<tr><td>&nbsp;</td>
	</tr>
	<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
       	<tr>
       	          <td class="labelPageHeader" >&nbsp;Registration&nbsp;</td>
	  	</tr>
	</table></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
       	<tr><td colspan="4"><label class="lblobjs"><b>About You</b></label></td>
		</tr>
       	<tr><td width="260">&nbsp;<label <?php genCaptionHtml($schema_about["u_firstname"],"") ?> >First name</label></td>
            <td width="200"><input type="text" size="30" <?php genInputTextHtml($schema_about["u_firstname"]) ?> /></td>
			<td width="200">&nbsp;<label <?php genCaptionHtml($schema_about["u_lastname"],"") ?> >Last name</label></td>
            <td ><input type="text" size="30" <?php genInputTextHtml($schema_about["u_lastname"]) ?> /></td>			
		</tr>
       	<tr><td >&nbsp;<label <?php genCaptionHtml($schema_about["u_email"],"") ?> >Email</label></td>
            <td  align=left><input type="text"  size="30" <?php genInputTextHtml($schema_about["u_email"]) ?> /></td>
			<td >&nbsp;<label <?php genCaptionHtml($schema_about["u_contactno"],"") ?> >Phone</label></td>
            <td  align=left><input type="text"  size="30" <?php genInputTextHtml($schema_about["u_contactno"]) ?> /></td>			
		</tr>
        <tr><td >&nbsp;<label <?php genCaptionHtml($schema_about["u_companyname"],"") ?> >Company</label></td>
    	    <td ><input type="text"   size="30" <?php genInputTextHtml($schema_about["u_companyname"]) ?> /></td>
			<td >&nbsp;</td>
    	    <td ></td>			
        </tr>
        <tr><td >&nbsp;<label <?php genCaptionHtml($schema_about["u_country"],"") ?> >Country</label></td>
    	    <td ><select <?php genSelectHtml($schema_about["u_country"],array("loadallcountries","",":-- Select one --")) ?> ></select></td>
			<td >&nbsp;</td>
    	    <td ></td>			
        </tr>
		<tr><td colspan="4">&nbsp;</td>
       	<tr>
       	  <td colspan="1"><label class="lblobjs"><b>eBT Package</b></label></td>
       	  <td colspan="3"><input type="radio" <?php genInputRadioHtml($schema_about["u_product"],"NOW") ?> /><label class="lblobjs">eBT Now (Freeware)</label>&nbsp;&nbsp;<input type="radio" <?php genInputRadioHtml($schema_about["u_product"],"FREE") ?> /><label class="lblobjs">eBT Lite (Freeware)</label></td>
     	  </tr>
       	<tr><td colspan="1"><label class="lblobjs"><b>Your eBT Implementation</b></label></td>
		    <td colspan="3"><input type="radio" <?php genInputRadioHtml($schema_about["u_iscloud"],"1") ?> /><label class="lblobjs">Cloud Computing</label>&nbsp;&nbsp;<input type="radio" <?php genInputRadioHtml($schema_about["u_iscloud"],"0") ?> /><label class="lblobjs">Download and Install locally</label></td>
		</tr>
 		<tr><td >&nbsp;<label <?php genCaptionHtml($schema_about["u_describeyou"],"") ?> >What best describes you?</label></td>
    	    <td ><select <?php genSelectHtml($schema_about["u_describeyou"],array("loadenumdescribesyou","",":-- Select one --")) ?> ></select></td>
			<td >&nbsp;</td>
    	    <td ></td>			
        </tr>        
 		<tr><td >&nbsp;<label <?php genCaptionHtml($schema_about["u_helpme"],"") ?> >I would like help from eBT with:</label></td>
    	    <td ><select <?php genSelectHtml($schema_about["u_helpme"],array("loadenumhelpme","",":-- Select one --")) ?> ></select></td>
			<td >&nbsp;</td>
    	    <td ></td>			
        </tr>        
        <tr>
          <td valign="top">&nbsp;<label <?php genCaptionHtml($schema_about["u_questions"],"") ?> >Include any questions or comments:</label></td>
          <td colspan="3"><TEXTAREA <?php genTextAreaHtml($schema_about["u_questions"]) ?>  rows="5" cols="48"><?php echo @$httpVars["df_u_questions"] ?></TEXTAREA></td>
        </tr>
		<tr><td colspan="4">&nbsp;</td>		
        <tr>
          <td colspan="4">&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_about["u_contactme"],"1") ?> /><label <?php genCaptionHtml($schema_about["u_contactme"],"") ?> >Please have a eBT expert contact me.</label></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema_about["u_sendmepromos"],"1") ?> /><label <?php genCaptionHtml($schema_about["u_sendmepromos"],"") ?> >Please send me newsletters, offers and promos.</label></td>
        </tr>

		
	</table></td></tr>	
	<tr><td>&nbsp;</td></tr>
	<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td>&nbsp;<a class="button" href="" onClick="formSubmit('register');return false;" >Send Registration</a></td></tr>
		</table>
	</td></tr>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
</body>
<?php restoreErrorMsg(); ?>
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>

