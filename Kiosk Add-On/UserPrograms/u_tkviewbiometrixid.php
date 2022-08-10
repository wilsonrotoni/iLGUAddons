<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkviewbiometrixid";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/enummonth.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	
	$page->restoreSavedValues();
	
	$page->objectcode = $progid;
	$page->paging->formid = "./UDP.php?objectcode=u_tkviewbiometrixid";
	$page->formid = "./UDO.php?objectcode=u_tkviewbiometrixid";
	
	$filter="";

	/* Filter Date */
	$schema["date1"] = createSchemaDate("date1");
	$schema["date2"] = createSchemaDate("date2");
	
	/* Approver Filter Date */
	$schema["adate1"] = createSchemaDate("adate1");
	$schema["adate2"] = createSchemaDate("adate2");
	$schema["apf"] = createSchema("apf");
	$schema["aempid"] = createSchema("aempid");
	
	$objGridP = new grid("T10",$httpVars,true);
	$objGridP->addcolumn("bioid");
	$objGridP->addcolumn("biodate");
	$objGridP->addcolumn("biodayname");
	$objGridP->addcolumn("biotime");
	$objGridP->addcolumn("biotypeofmode");
	$objGridP->addcolumn("biodevice");
	
	$objGridP->columntitle("bioid","Biometrics ID");
    $objGridP->columntitle("biodate","Date");
    $objGridP->columntitle("biodayname","Day Name");
    $objGridP->columntitle("biotime","Time");
    $objGridP->columntitle("biotypeofmode","Type of Mode");
    $objGridP->columntitle("biodevice","Device \ File Uploaded");
	
    $objGridP->columnwidth("bioid",15);
	$objGridP->columnwidth("biodate",10);
	$objGridP->columnwidth("biodayname",7);
	$objGridP->columnwidth("biotime",8);
	$objGridP->columnwidth("biotypeofmode",10);
	$objGridP->columnwidth("biodevice",20);
	
	$objGridP->automanagecolumnwidth = false;
	$objGridP->width = 1100;
	$objGridP->height = 280;
	
	$objGridA = new grid("T20",$httpVars,true);
	$objGridA->addcolumn("biopf");
	$objGridA->addcolumn("bioid");
    $objGridA->addcolumn("bioname");
	$objGridA->addcolumn("biodate");
	$objGridA->addcolumn("biodayname");
	$objGridA->addcolumn("biotime");
	$objGridA->addcolumn("biotypeofmode");
	$objGridA->addcolumn("biodevice");
	
	$objGridA->columntitle("biopf","Profit Center");
	$objGridA->columntitle("bioid","Biometrics ID");
    $objGridA->columntitle("bioname","Employee Name");
    $objGridA->columntitle("biodate","Date");
    $objGridA->columntitle("biodayname","Day Name");
    $objGridA->columntitle("biotime","Time");
    $objGridA->columntitle("biotypeofmode","Type of Mode");
    $objGridA->columntitle("biodevice","Device \ File Uploaded");
	
    $objGridA->columnwidth("biopf",20);
	$objGridA->columnwidth("bioid",15);
    $objGridA->columnwidth("bioname",25);
	$objGridA->columnwidth("biodate",10);
	$objGridA->columnwidth("biodayname",7);
	$objGridA->columnwidth("biotime",8);
	$objGridA->columnwidth("biotypeofmode",10);
	$objGridA->columnwidth("biodevice",25);
	
	$objGridA->automanagecolumnwidth = false;
	$objGridA->width = 1100;
	$objGridA->height = 280;
		
	require("./inc/formactions.php");
	
	function loadu_empid($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("SELECT a.code,CONCAT(a.name,' - ',a.code) FROM u_premploymentinfo a
							INNER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code
							INNER JOIN u_tkrestrictuserscs c ON c.company = a.company AND c.branch = a.branch AND c.u_costcenter = b.u_branch
							WHERE c.code = '".$_SESSION["userid"]."' AND a.code != '".$_SESSION["userid"]."'
							GROUP BY a.code");
			if ($obj->recordcount() > 0) {
				while ($obj->queryfetchrow()) {
					$_selected = "";
					if ($p_selected == $obj->fields[0]) $_selected = "selected";
					$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
				}
			}	
			$obj->queryclose();
		echo @$_html;
	}
	
	function loadu_pf($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("SELECT a.code,CONCAT(a.name,' - ',a.code) FROM u_prprofitcenter a
							INNER JOIN u_tkrestrictuserscs c ON c.company = a.company AND c.branch = a.branch AND c.u_costcenter = a.code
							WHERE c.code = '".$_SESSION["userid"]."'
							GROUP BY a.code");
			if ($obj->recordcount() > 0) {
				while ($obj->queryfetchrow()) {
					$_selected = "";
					if ($p_selected == $obj->fields[0]) $_selected = "selected";
					$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
				}
			}	
			$obj->queryclose();
		echo @$_html;
	}
		
	$filterExp = "";
	
	$page->toolbar->setaction("update",false);
	//$page->toolbar->setaction("print",false);
	//$page->toolbar->addbutton("approved","Update","formSubmit('u_update')","right");
	if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf" || $_SESSION["theme"]=="gps") { 
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("SELECT u_font FROM u_prglobalsetting");
		while ($objRs->queryfetchrow("NAME")) {
			$csspath =  "../Addons/GPS/PayRoll Add-On/UserPrograms/".$objRs->fields["u_font"]."/";
		}
	}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath; ?>css/tabber.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath; ?>css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>	
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<script language="JavaScript">

	function onPageLoad() {
		if("<?php echo $_SESSION["menunavigator"] ?>" == "topnav"){
			alert("SESSION expired.. system autolog-out..");
			logOut();
		}
	}
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "date1":
			case "date2":
				if (getInput("date1") != "" && getInput("date2") != "" ) {
					showPBioRow();
				}
			break;
			case "adate1":
			case "adate2":
			case "apf":
			case "aempid":
				if (getInput("adate1") != "" && getInput("adate2") != "" ) {
					showABioRow();
				}
			break;
		}
		return true;
	}	
	
	function onReportGetParams(formattype,params) {
		if (params==null) params = new Array();
		params["source"] = "aspx";
		params["action"] = "processReport.aspx";
		params["querystring"] += generateQueryString("date1",formatDateToDB(getInput("date1")));
		params["querystring"] += generateQueryString("date2",formatDateToDB(getInput("date2")));
		params["querystring"] += generateQueryString("empid",getGlobal("userid"));
		params["reportfile"] = getVar("approotpath") +  "AddOns\\GPS\\HRISRpt Add-On\\UserRpts\\\RowData.rpt"; 	
		return params;
	}
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_date1":
			case "df_date2":
			case "df_adate1":
			case "df_adate2":
			case "df_apf":
			case "df_aempid":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("date1");
			inputs.push("date2");
			inputs.push("adate1");
			inputs.push("adate2");
			inputs.push("apf");
			inputs.push("aempid");
		return inputs;
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "date1":
			case "date2":
			case "adate1":
			case "adate2":
			case "apf":
			case "aempid":
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
	
	function showPBioRow() {
		var result;
		var data = new Array();
		clearTable("T10",true);
			result = page.executeFormattedQuery("SELECT a.u_biometrixid,a.u_date,DAYNAME(a.u_date) as days,fx.name as times,a.u_type,CONCAT(a.u_branch,' : ',a.u_filename) as u_branch FROM u_tkattendanceentry a INNER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(a.u_time,5) WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_date BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' ORDER BY a.u_date,a.u_time");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["bioid"] = result.childNodes.item(xxxi).getAttribute("u_biometrixid");
							data["biodate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_date"));
							data["biodayname"] = result.childNodes.item(xxxi).getAttribute("days");
							data["biotime"] = result.childNodes.item(xxxi).getAttribute("times");
							if(result.childNodes.item(xxxi).getAttribute("u_type") == "I") {
								data["biotypeofmode"] = "In";
							} else if(result.childNodes.item(xxxi).getAttribute("u_type") == "O") {
								data["biotypeofmode"] = "Out";
							}
							data["biodevice"] = result.childNodes.item(xxxi).getAttribute("u_branch");
							insertTableRowFromArray("T10",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving Kiosk Bio Row Data. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
	function showABioRow() {
		var result;
		var data = new Array();
		clearTable("T20",true);
			result = page.executeFormattedQuery("SELECT zz.name as pf,a.u_biometrixid,xxx.name as empname,a.u_date,DAYNAME(a.u_date) as days,fx.name as times,a.u_type,CONCAT(a.u_branch,' : ',a.u_filename) as u_branch FROM u_tkattendanceentry a INNER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(a.u_time,5) INNER JOIN u_premploymentinfo xxx ON xxx.company = a.company AND xxx.branch = a.branch AND xxx.code = a.u_empid INNER JOIN u_premploymentdeployment xx ON xx.company = xxx.company AND xx.branch = xxx.branch AND xx.code = xxx.code INNER JOIN u_tkrestrictuserscs ss ON ss.company = a.company AND ss.branch = a.branch AND ss.u_costcenter = xx.u_branch AND ss.code = '"+getGlobal("userid")+"' INNER JOIN u_prprofitcenter zz ON zz.company = a.company AND zz.branch = a.branch AND zz.code = xx.u_branch WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND IF('"+getInput("aempid")+"' = '',''='',xxx.code = '"+getInput("aempid")+"') AND IF('"+getInput("apf")+"' = '',''='',xx.u_branch = '"+getInput("apf")+"') AND a.u_date BETWEEN '"+formatDateToDB(getInput("adate1"))+"' AND '"+formatDateToDB(getInput("adate2"))+"' AND a.u_empid != '"+getGlobal("userid")+"' ORDER BY a.u_date,a.u_time");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["biopf"] = result.childNodes.item(xxxi).getAttribute("pf");
							data["bioid"] = result.childNodes.item(xxxi).getAttribute("u_biometrixid");
							data["bioname"] = result.childNodes.item(xxxi).getAttribute("empname");
							data["biodate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_date"));
							data["biodayname"] = result.childNodes.item(xxxi).getAttribute("days");
							data["biotime"] = result.childNodes.item(xxxi).getAttribute("times");
							if(result.childNodes.item(xxxi).getAttribute("u_type") == "I") {
								data["biotypeofmode"] = "In";
							} else if(result.childNodes.item(xxxi).getAttribute("u_type") == "O") {
								data["biotypeofmode"] = "Out";
							}
							data["biodevice"] = result.childNodes.item(xxxi).getAttribute("u_branch");
							insertTableRowFromArray("T20",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving Approver Bio Row Data. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;View Biometrics Row Data&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td>
<div class="tabber" id="tab1">
    <div class="tabbertab" title="Biometrics Data List">	
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width="168"><label <?php genCaptionHtml($schema["date1"],"") ?>>Date From</label></td>
          <td  align=left><input type="text" size="12" <?php genInputTextHtml($schema["date1"]) ?> /></td>
        </tr>
        <tr>
          <td width="168"><label <?php genCaptionHtml($schema["date2"],"") ?>>Date To</label></td>
          <td  align=left><input type="text" size="12" <?php genInputTextHtml($schema["date2"]) ?> /></td>
        </tr>
        <tr>
          <td colspan="2"><?php $objGridP->draw(true) ?></td>
        </tr>
    </table>
    </div>
    <?php 
		$objRsApprover = new recordset(null,$objConnection);
		$objRsApprover->queryopen("SELECT u_stagename FROM u_tkapproverfileassigned WHERE u_stagename = '".$_SESSION["userid"]."'");
		if ($objRsApprover->recordcount() > 0) {
	?>
    <div class="tabbertab" title="Biometrics Data List ( Approver )">	
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr><td width="168"><label <?php genCaptionHtml($schema["apf"],"") ?>>Cost Center</label></td>
            <td  align=left><select <?php genSelectHtml($schema["apf"],array("loadu_pf","",":"),"",null,null,"width:250px") ?> ></select></td>
        </tr>
        <tr><td width="168"><label <?php genCaptionHtml($schema["aempid"],"") ?>>Name : ID</label></td>
            <td  align=left><select <?php genSelectHtml($schema["aempid"],array("loadu_empid","",":"),"",null,null,"width:250px") ?> ></select></td>
        </tr>
        <tr>
          <td width="168"><label <?php genCaptionHtml($schema["adate1"],"") ?>>Date From</label></td>
          <td  align=left><input type="text" size="12" <?php genInputTextHtml($schema["adate1"]) ?> /></td>
        </tr>
        <tr>
          <td width="168"><label <?php genCaptionHtml($schema["adate2"],"") ?>>Date To</label></td>
          <td  align=left><input type="text" size="12" <?php genInputTextHtml($schema["adate2"]) ?> /></td>
        </tr>
        <tr>
          <td colspan="2"><?php $objGridA->draw(true) ?></td>
        </tr>
    </table>
    </div>
    <?php 
		}
	?>
</div>
</td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>&nbsp;</td></tr>
<?php //$page->writeRecordLimit(); ?>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
<?php require("./bofrms/ajaxprocess.php"); ?>		
</FORM>
<?php $page->writePopupMenuHTML(true);?>
<?php require("./inc/rptobjs.php") ?>
</body>
</html>

<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . $page->toolbar->generateQueryString(). "&toolbarscript=./GenericGridMasterDataToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
