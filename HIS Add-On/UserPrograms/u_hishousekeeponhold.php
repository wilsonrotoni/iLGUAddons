<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_hishousekeeponhold";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/enummonth.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	
	unset($enumyear["-"]);
	
	$page->objectcode = $progid;
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		$actionReturn = true;
		if(strtolower($action)=="u_update") {
			$obju_HISRooms = new masterdataschema_br(null,$objConnection,"u_hisrooms");
			$obju_HISRoomBeds = new masterdatalinesschema_br(null,$objConnection,"u_hisroombeds");
			
			$objConnection->beginwork();
			
			for($i=0;$i<= $objGrid->recordcount;$i++) {
				if($page->getitemstring("oldstatusT1r".$i) != $page->getitemstring("statusT1r".$i)) {
					if ($obju_HISRooms->getbykey($page->getcolumnstring("T1",$i,"roomno"))) {
						if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$page->getcolumnstring("T1",$i,"bedno")."'")) {
							if ($obju_HISRoomBeds->getudfvalue("u_status")==$page->getitemstring("oldstatusT1r".$i)) {
								$obju_HISRoomBeds->setudfvalue("u_status",$page->getitemstring("statusT1r".$i));
								$obju_HISRoomBeds->setudfvalue("u_refno","");
								$obju_HISRoomBeds->setudfvalue("u_patientname",$page->getitemstring("remarksT1r".$i));
								$obju_HISRoomBeds->setudfvalue("u_date",currentdateDB());
								$obju_HISRoomBeds->setudfvalue("u_time",date('H:i'));
								$actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
								if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
							} else return raiseError("Bed No.[".$page->getcolumnstring("T1",$i,"bedno")."] status is mismatched.");
						} else return raiseError("Unable to find Bed No.[".$page->getcolumnstring("T1",$i,"bedno")."].");
					} else return raiseError("Unable to find Room No.[".$page->getcolumnstring("T1",$i,"roomno")."].");				
				}
				if (!$actionReturn) break;
			}
			
			//if ($actionReturn) $actionReturn = raiseError("onFormAction");
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			
			onFormAction("edit",$filter);
		}
		return $actionReturn;
	}
	$filter="";
	
	$enumstatus= array('Vacant' => 'Vacant', 'On-Hold' => 'On-Hold');
	
	function loadenumstatus($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumstatus;
		reset($enumstatus);
		while (list($key, $val) = each($enumstatus)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
		
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("roomno");
	$objGrid->addcolumn("roomname");
	$objGrid->addcolumn("bedno");
	$objGrid->addcolumn("oldstatus");
	$objGrid->addcolumn("status");
	$objGrid->addcolumn("remarks");
	$objGrid->addcolumn("date");
	$objGrid->addcolumn("time");
	$objGrid->columntitle("roomno","Room No.");
	$objGrid->columnwidth("roomno",10);
	$objGrid->columntitle("roomname","Description");
	$objGrid->columnwidth("roomname",20);
	$objGrid->columntitle("bedno","Bed No.");
	$objGrid->columnwidth("bedno",10);
	$objGrid->columntitle("oldstatus","Status");
	$objGrid->columnwidth("oldstatus",10);
	$objGrid->columntitle("status","New Status");
	$objGrid->columnwidth("status",10);
	$objGrid->columntitle("remarks","Remarks");
	$objGrid->columnwidth("remarks",30);
	$objGrid->columntitle("date","Date");
	$objGrid->columnwidth("date",12);
	$objGrid->columntitle("time","Time");
	$objGrid->columnwidth("time",10);
	//$objGrid->columnvisibility("oldstatus",false);
	$objGrid->columninput("status","type","select");
	$objGrid->columninput("status","options","loadenumstatus");
	$objGrid->columninput("remarks","disabled","{status}=='Vacant'");
	
	$objGrid->columninput("remarks","type","text");

	$objGrid->dataentry = false;
	//$objGrid->selectionmode = 2;
	$objGrid->automanagecolumnwidth = false;

	$schema["faclass"] = createSchema("faclass");
	$schema["year"] = createSchema("year");
	$schema["month"] = createSchema("month");
	$schema["docdate"] = createSchemaDate("docdate");
	
	if (!isset($httpVars["df_month"])) {
		$page->setitem("month",date('m'));
	}
	if (!isset($httpVars["df_year"])) {
		$page->setitem("year",date('Y'));
	}
	
	require("./inc/formactions.php");
	
	$objRs = new recordset(null,$objConnection);
	
	$filterExp = "";
	//$filterExp = genSQLFilterString("A.U_FACLASS",$filterExp,$page->getitemstring("faclass"));
	//$filterExp = genSQLFilterNumeric("C.U_YEAR",$filterExp,$page->getitemstring("year"));
	//$filterExp = genSQLFilterNumeric("C.U_MONTH",$filterExp,$page->getitemstring("month"));
	
	if ($filterExp !="") $filterExp = " and " . $filterExp;
	
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
		$objRs->setdebug();
		$objRs->queryopen("select A.CODE AS U_ROOMNO, C.NAME AS U_ROOMNAME, B.U_BEDNO, B.U_DATE, B.U_TIME, B.U_STATUS, B.U_PATIENTNAME from U_HISROOMS A, U_HISROOMBEDS B, U_HISROOMTYPES C where B.CODE=A.CODE and C.CODE=A.U_ROOMTYPE AND B.U_STATUS IN ('Vacant','On-Hold') $filterExp ORDER BY B.U_BEDNO");
		//var_dump($objRs->sqls);
		while ($objRs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"chxbox",1);
			$objGrid->setitem(null,"roomno",$objRs->fields["U_ROOMNO"]);
			$objGrid->setitem(null,"roomname",$objRs->fields["U_ROOMNAME"]);
			$objGrid->setitem(null,"bedno",$objRs->fields["U_BEDNO"]);
			$objGrid->setitem(null,"oldstatus",$objRs->fields["U_STATUS"]);
			$objGrid->setitem(null,"status",$objRs->fields["U_STATUS"]);
			$objGrid->setitem(null,"remarks",$objRs->fields["U_PATIENTNAME"]);
			$objGrid->setitem(null,"date",formatDateToHttp($objRs->fields["U_DATE"]));
			$objGrid->setitem(null,"time",$objRs->fields["U_TIME"]);
		}
	}	
	
	//$page->resize->addgrid("T1",20,100,false);
	//$schema["gdate"]["attributes"]="disabled";
	//$httpVars["df_gdate"] = date("m/d/Y");
	$page->resize->addgridobject($objGrid,10,55,false);
	
	$page->toolbar->setaction("print",false);
	$page->toolbar->setaction("update",false);
	$page->toolbar->addbutton("roomcleaned","Update","formSubmit('u_update')","right");
	
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
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
<SCRIPT language=JavaScript src="js/lnkbtncommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onElementChange(element,column,table,row) {
		var result;
		switch(table) {
			case "T1":
				switch (column) {
					case "status":
						disableTableInput("T1","remarks",row);
						if (getTableInput("T1","status",row)=="On-Hold") {
							enableTableInput("T1","remarks",row);
							focusTableInput("T1","remarks",row);
						} else {
							setTableInput("T1","remarks","",row);
						}
						break;
				}
				break;
			default:
				break;
		}					
		return true;
	}	

	function onElementValidate(element,column,table,row) {
		return true;
	}	


	function onFormSubmit(action) {
		switch (action) {
			case "u_update":
				if(getTableRowCount("T1")==0) { 
					setStatusMsg("No Item(s) to process!"); 
					return false;
				}
				
				if(getTableRowCount("T1")>0) {
					for(i=1;i<=getTableRowCount("T1");i++) {
						if(getTableInput("T1","status",i)=="On-Hold" && getTableInput("T1","remarks",i)==""){
							page.statusbar.showError("Remarks is required.");
							focusTableInput("T1","remarks",i)
							return false;
						}
					}
				}
				break;
			default:	
				break;
		}
				
		return true;
	}	
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {	
			case "df_suppno": 
				params["params"] = "BPTYPE:S";
				break;
		}	
		return params;
	}
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				/*if (isTableInputChecked(table,"chxbox",row)) {
					focusTableInput(table,"depredate",row,500);				
				}*/	
				break;
		}		
		return params;
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
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
<tr>
<td>
	<?php $objGrid->draw(true) ?>
</td>
</tr>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
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
