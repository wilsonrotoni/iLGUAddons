<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkreuploadrequest";
	
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
	$page->paging->formid = "./UDP.php?objectcode=u_tkreuploadrequest";
	$page->formid = "./UDO.php?objectcode=u_tkreuploadrequest";
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		$actionReturn = true;
		$objRs = new recordset(null,$objConnection);
		
		$objRsOB = new recordset(null,$objConnection);
		$obju_DTRx = new masterdataschema_br(null,$objConnection,"u_tktemporarydtr");
		$obju_DTRTime = new masterdataschema_br(null,$objConnection,"u_tktemporarydtrtime");
		$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");
		
		$objRsTimeAdj = new recordset(null,$objConnection);
		$obju_TimeKeeping = new masterdataschema_br(NULL,$objConnection,"u_tkattendanceentry");
		
		$objRsLeave = new recordset(null,$objConnection);
		
		if(strtolower($action)=="u_update") {
			$objConnection->beginwork();
			if($page->getitemstring("type_req") == "OB") {
				$objRsOB->queryopen("SELECT u_profitcenter,
											u_empid,
											u_tkdate,
											u_tk_wd,
											u_tk_ot,
											code,
											u_assfromtime,
											u_asstotime,
											u_status 
									  
									  FROM u_tkrequestformass 
									  WHERE company = '".$_SESSION["company"]."'
									  	AND branch = '".$_SESSION["branch"]."'
										AND u_tk_wd > 0
										AND u_assfromtime != ''
										AND u_asstotime != ''
										AND u_tkdate != ''
									    AND u_status = 1");
									  
					while ($objRsOB->queryfetchrow("NAME")) {
						if ($obju_DTRx->getbysql("CODE='".$objRsOB->fields["u_profitcenter"].$objRsOB->fields["u_empid"].str_replace("-","",$objRsOB->fields["u_tkdate"])."'")) {
							$obju_DTRx->setudfvalue("u_profitcenter",$objRsOB->fields["u_profitcenter"]);
							$obju_DTRx->setudfvalue("u_empid",$objRsOB->fields["u_empid"]);
							$obju_DTRx->setudfvalue("u_tkdate",$objRsOB->fields["u_tkdate"]);
							$obju_DTRx->setudfvalue("u_tk_wd",$objRsOB->fields["u_tk_wd"]);
							$obju_DTRx->setudfvalue("u_tk_excesstime",$objRsOB->fields["u_tk_ot"]);
							$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
							$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
							$obju_DTRx->setudfvalue("u_status","O");
							$obju_DTRx->setudfvalue("u_destination","O.B :".$objRsOB->fields["code"]);
							$actionReturn = $obju_DTRx->update($obju_DTRx->code,$obju_DTRx->rcdversion);
							if(!$actionReturn) break;
							
								if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
									$obju_DTRTime->setudfvalue("u_dates",$objRsOB->fields["u_tkdate"]);
									$obju_DTRTime->setudfvalue("u_time_from",$objRsOB->fields["u_assfromtime"]);
									$obju_DTRTime->setudfvalue("u_time_to",$objRsOB->fields["u_asstotime"]);
									$actionReturn = $obju_DTRTime->update($obju_DTRTime->code,$obju_DTRTime->rcdversion);
									if(!$actionReturn) break;
								} else {
									$obju_DTRTime->prepareadd();
									$obju_DTRTime->code = $obju_DTRx->code;
									$obju_DTRTime->name = $obju_DTRTime->code;
									$obju_DTRTime->setudfvalue("u_dates",$objRsOB->fields["u_tkdate"]);
									$obju_DTRTime->setudfvalue("u_time_from",$objRsOB->fields["u_assfromtime"]);
									$obju_DTRTime->setudfvalue("u_time_to",$objRsOB->fields["u_asstotime"]);
									$actionReturn = $obju_DTRTime->add();
									if(!$actionReturn) break;
								}
						} else {
							$obju_DTRx->code = $objRsOB->fields["u_profitcenter"].$objRsOB->fields["u_empid"].str_replace("-","",$objRsOB->fields["u_tkdate"]); //a.u_profitcenter,a.u_empid,REPLACE(b.u_tkdate,'-','')
							$obju_DTRx->name = $obju_DTRx->code;
							$obju_DTRx->setudfvalue("u_profitcenter",$objRsOB->fields["u_profitcenter"]);
							$obju_DTRx->setudfvalue("u_empid",$objRsOB->fields["u_empid"]);
							$obju_DTRx->setudfvalue("u_tkdate",$objRsOB->fields["u_tkdate"]);
							$obju_DTRx->setudfvalue("u_tk_wd",$objRsOB->fields["u_tk_wd"]);
							$obju_DTRx->setudfvalue("u_tk_excesstime",$objRsOB->fields["u_tk_ot"]);
							$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
							$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
							$obju_DTRx->setudfvalue("u_status","O");
							$obju_DTRx->setudfvalue("u_destination","O.B :".$objRsOB->fields["code"]);
							$actionReturn = $obju_DTRx->add();	
							if(!$actionReturn) break;
							
								if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
									$obju_DTRTime->setudfvalue("u_dates",$objRsOB->fields["u_tkdate"]);
									$obju_DTRTime->setudfvalue("u_time_from",$objRsOB->fields["u_assfromtime"]);
									$obju_DTRTime->setudfvalue("u_time_to",$objRsOB->fields["u_asstotime"]);
									$actionReturn = $obju_DTRTime->update($obju_DTRTime->code,$obju_DTRTime->rcdversion);
									if(!$actionReturn) break;
								} else {
									$obju_DTRTime->prepareadd();
									$obju_DTRTime->code = $obju_DTRx->code;
									$obju_DTRTime->name = $obju_DTRTime->code;
									$obju_DTRTime->setudfvalue("u_dates",$objRsOB->fields["u_tkdate"]);
									$obju_DTRTime->setudfvalue("u_time_from",$objRsOB->fields["u_assfromtime"]);
									$obju_DTRTime->setudfvalue("u_time_to",$objRsOB->fields["u_asstotime"]);
									$actionReturn = $obju_DTRTime->add();
									if(!$actionReturn) break;
								}
						}
						
						$objRs->queryopen("SELECT b.code
											FROM u_tkschedulecolumntorow b
											WHERE company = '".$_SESSION["company"]."'
									  			AND branch = '".$_SESSION["branch"]."'
										      AND b.u_empid = '".$objRsOB->fields["u_empid"]."'
											  AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objRsOB->fields["u_tkdate"]."'");
							while ($objRs->queryfetchrow("NAME")) {
								if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
									$obju_Schedule_Row->setudfvalue("u_status","Present");
									if($actionReturn) $actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
								}
							}
					}
						
			}
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			
			//onFormAction("edit",$filter);
		}
		return $actionReturn;
	}
	
	$filter="";

	$schema["type_req"] = createSchema("type_req");
	
	function loadu_batch($p_selected) {
		$status1= array('OB' => 'Official Business');
		$_html = "";		
		$_selected = "";
		reset($status1);
		while (list($key, $val) = each($status1)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
		
	require("./inc/formactions.php");
		
	
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
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>

<script language="JavaScript">
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_type_req":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("type_req");
		return inputs;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "type_req":
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
	  <td class="labelPageHeader" >&nbsp;Re-Upload Official Business&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
      <td width="168"><label <?php genCaptionHtml($schema["type_req"],"") ?>>Type of Request</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["type_req"],array("loadu_batch","",":")) ?> ></select></td>
    </tr>
    <tr>
      <td width="168">&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSubmit('u_update');return false;">Update</a></td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>&nbsp;</td></tr>
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
</body>
</html>

<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . $page->toolbar->generateQueryString(). "&toolbarscript=./GenericGridMasterDataToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
