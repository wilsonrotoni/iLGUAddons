<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkassperday";
	
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
	$page->paging->formid = "./UDP.php?objectcode=u_tkassperday";
	$page->formid = "./UDO.php?objectcode=u_tkassperday";
	
	$filter="";

	/* Filter Date */
	$schema["from_date"] = createSchemaDate("from_date");
	$schema["to_date"] = createSchemaDate("to_date");
	
	/* Filter Date Request */
	$schema["rfrom_date"] = createSchemaDate("rfrom_date");
	$schema["rto_date"] = createSchemaDate("rto_date");
	
	/* Approver Filter Date */
	$schema["afrom_date"] = createSchemaDate("afrom_date");
	$schema["ato_date"] = createSchemaDate("ato_date");
	$schema["apf"] = createSchema("apf");
	$schema["aempid"] = createSchema("aempid");
	
	if($_SESSION["usertype"] != "") {
		$page->setitem("id",$_SESSION["userid"]);
	}
	
	/* DTR Form */
	
	$schema["id"] = createSchema("id");
	
	$schema["late_hrs"] = createSchemaAmount("late_hrs");
	$schema["late_mins"] = createSchemaAmount("late_mins");
	$schema["ut_hrs"] = createSchemaAmount("ut_hrs");
	$schema["ut_mins"] = createSchemaAmount("ut_mins");
	$schema["absent_hrs"] = createSchemaAmount("absent_hrs");
	$schema["absent_day"] = createSchemaAmount("absent_day");
	
	$schema["regwh_hrs"] = createSchemaAmount("regwh_hrs");
	$schema["regnd_hrs"] = createSchemaAmount("regnd_hrs");
	$schema["regot_hrs"] = createSchemaAmount("regot_hrs");
	$schema["regotnd_hrs"] = createSchemaAmount("regotnd_hrs");
	$schema["regworwh_hrs"] = createSchemaAmount("regworwh_hrs");
	$schema["regwornd_hrs"] = createSchemaAmount("regwornd_hrs");
	$schema["regworot_hrs"] = createSchemaAmount("regworot_hrs");
	$schema["regworotnd_hrs"] = createSchemaAmount("regworotnd_hrs");
	
	$schema["shwh_hrs"] = createSchemaAmount("shwh_hrs");
	$schema["shnd_hrs"] = createSchemaAmount("shnd_hrs");
	$schema["shot_hrs"] = createSchemaAmount("shot_hrs");
	$schema["shotnd_hrs"] = createSchemaAmount("shotnd_hrs");
	$schema["shworwh_hrs"] = createSchemaAmount("shworwh_hrs");
	$schema["shwornd_hrs"] = createSchemaAmount("shwornd_hrs");
	$schema["shworot_hrs"] = createSchemaAmount("shworot_hrs");
	$schema["shworotnd_hrs"] = createSchemaAmount("shworotnd_hrs");
	
	$schema["rlwh_hrs"] = createSchemaAmount("rlwh_hrs");
	$schema["rlnd_hrs"] = createSchemaAmount("rlnd_hrs");
	$schema["rlot_hrs"] = createSchemaAmount("rlot_hrs");
	$schema["rlotnd_hrs"] = createSchemaAmount("rlotnd_hrs");
	$schema["rlworwh_hrs"] = createSchemaAmount("rlworwh_hrs");
	$schema["rlwornd_hrs"] = createSchemaAmount("rlwornd_hrs");
	$schema["rlworot_hrs"] = createSchemaAmount("rlworot_hrs");
	$schema["rlworotnd_hrs"] = createSchemaAmount("rlworotnd_hrs");
	
	$schema["id"]["attributes"] = "disabled";

	$schema["late_hrs"]["attributes"] = "disabled";
	$schema["late_mins"]["attributes"] = "disabled";
	$schema["ut_hrs"]["attributes"] = "disabled";
	$schema["ut_mins"]["attributes"] = "disabled";
	$schema["absent_hrs"]["attributes"] = "disabled";
	$schema["absent_day"]["attributes"] = "disabled";
	
	$schema["regwh_hrs"]["attributes"] = "disabled";
	$schema["regnd_hrs"]["attributes"] = "disabled";
	$schema["regot_hrs"]["attributes"] = "disabled";
	$schema["regotnd_hrs"]["attributes"] = "disabled";
	$schema["regworwh_hrs"]["attributes"] = "disabled";
	$schema["regwornd_hrs"]["attributes"] = "disabled";
	$schema["regworot_hrs"]["attributes"] = "disabled";
	$schema["regworotnd_hrs"]["attributes"] = "disabled";
	
	$schema["shwh_hrs"]["attributes"] = "disabled";
	$schema["shnd_hrs"]["attributes"] = "disabled";
	$schema["shot_hrs"]["attributes"] = "disabled";
	$schema["shotnd_hrs"]["attributes"] = "disabled";
	$schema["shworwh_hrs"]["attributes"] = "disabled";
	$schema["shwornd_hrs"]["attributes"] = "disabled";
	$schema["shworot_hrs"]["attributes"] = "disabled";
	$schema["shworotnd_hrs"]["attributes"] = "disabled";
	
	$schema["rlwh_hrs"]["attributes"] = "disabled";
	$schema["rlnd_hrs"]["attributes"] = "disabled";
	$schema["rlot_hrs"]["attributes"] = "disabled";
	$schema["rlotnd_hrs"]["attributes"] = "disabled";
	$schema["rlworwh_hrs"]["attributes"] = "disabled";
	$schema["rlwornd_hrs"]["attributes"] = "disabled";
	$schema["rlworot_hrs"]["attributes"] = "disabled";
	$schema["rlworotnd_hrs"]["attributes"] = "disabled";
	
/* DTR Form Approver */
	
	$schema["a_id"] = createSchema("a_id");
	
	$schema["a_late_hrs"] = createSchemaAmount("a_late_hrs");
	$schema["a_late_mins"] = createSchemaAmount("a_late_mins");
	$schema["a_ut_hrs"] = createSchemaAmount("a_ut_hrs");
	$schema["a_ut_mins"] = createSchemaAmount("a_ut_mins");
	$schema["a_absent_hrs"] = createSchemaAmount("a_absent_hrs");
	$schema["a_absent_day"] = createSchemaAmount("a_absent_day");
	
	$schema["a_regwh_hrs"] = createSchemaAmount("a_regwh_hrs");
	$schema["a_regnd_hrs"] = createSchemaAmount("a_regnd_hrs");
	$schema["a_regot_hrs"] = createSchemaAmount("a_regot_hrs");
	$schema["a_regotnd_hrs"] = createSchemaAmount("a_regotnd_hrs");
	$schema["a_regworwh_hrs"] = createSchemaAmount("a_regworwh_hrs");
	$schema["a_regwornd_hrs"] = createSchemaAmount("a_regwornd_hrs");
	$schema["a_regworot_hrs"] = createSchemaAmount("a_regworot_hrs");
	$schema["a_regworotnd_hrs"] = createSchemaAmount("a_regworotnd_hrs");
	
	$schema["a_shwh_hrs"] = createSchemaAmount("a_shwh_hrs");
	$schema["a_shnd_hrs"] = createSchemaAmount("a_shnd_hrs");
	$schema["a_shot_hrs"] = createSchemaAmount("a_shot_hrs");
	$schema["a_shotnd_hrs"] = createSchemaAmount("a_shotnd_hrs");
	$schema["a_shworwh_hrs"] = createSchemaAmount("a_shworwh_hrs");
	$schema["a_shwornd_hrs"] = createSchemaAmount("a_shwornd_hrs");
	$schema["a_shworot_hrs"] = createSchemaAmount("a_shworot_hrs");
	$schema["a_shworotnd_hrs"] = createSchemaAmount("a_shworotnd_hrs");
	
	$schema["a_rlwh_hrs"] = createSchemaAmount("a_rlwh_hrs");
	$schema["a_rlnd_hrs"] = createSchemaAmount("a_rlnd_hrs");
	$schema["a_rlot_hrs"] = createSchemaAmount("a_rlot_hrs");
	$schema["a_rlotnd_hrs"] = createSchemaAmount("a_rlotnd_hrs");
	$schema["a_rlworwh_hrs"] = createSchemaAmount("a_rlworwh_hrs");
	$schema["a_rlwornd_hrs"] = createSchemaAmount("a_rlwornd_hrs");
	$schema["a_rlworot_hrs"] = createSchemaAmount("a_rlworot_hrs");
	$schema["a_rlworotnd_hrs"] = createSchemaAmount("a_rlworotnd_hrs");
	
	$schema["a_id"]["attributes"] = "disabled";

	$schema["a_late_hrs"]["attributes"] = "disabled";
	$schema["a_late_mins"]["attributes"] = "disabled";
	$schema["a_ut_hrs"]["attributes"] = "disabled";
	$schema["a_ut_mins"]["attributes"] = "disabled";
	$schema["a_absent_hrs"]["attributes"] = "disabled";
	$schema["a_absent_day"]["attributes"] = "disabled";
	
	$schema["a_regwh_hrs"]["attributes"] = "disabled";
	$schema["a_regnd_hrs"]["attributes"] = "disabled";
	$schema["a_regot_hrs"]["attributes"] = "disabled";
	$schema["a_regotnd_hrs"]["attributes"] = "disabled";
	$schema["a_regworwh_hrs"]["attributes"] = "disabled";
	$schema["a_regwornd_hrs"]["attributes"] = "disabled";
	$schema["a_regworot_hrs"]["attributes"] = "disabled";
	$schema["a_regworotnd_hrs"]["attributes"] = "disabled";
	
	$schema["a_shwh_hrs"]["attributes"] = "disabled";
	$schema["a_shnd_hrs"]["attributes"] = "disabled";
	$schema["a_shot_hrs"]["attributes"] = "disabled";
	$schema["a_shotnd_hrs"]["attributes"] = "disabled";
	$schema["a_shworwh_hrs"]["attributes"] = "disabled";
	$schema["a_shwornd_hrs"]["attributes"] = "disabled";
	$schema["a_shworot_hrs"]["attributes"] = "disabled";
	$schema["a_shworotnd_hrs"]["attributes"] = "disabled";
	
	$schema["a_rlwh_hrs"]["attributes"] = "disabled";
	$schema["a_rlnd_hrs"]["attributes"] = "disabled";
	$schema["a_rlot_hrs"]["attributes"] = "disabled";
	$schema["a_rlotnd_hrs"]["attributes"] = "disabled";
	$schema["a_rlworwh_hrs"]["attributes"] = "disabled";
	$schema["a_rlwornd_hrs"]["attributes"] = "disabled";
	$schema["a_rlworot_hrs"]["attributes"] = "disabled";
	$schema["a_rlworotnd_hrs"]["attributes"] = "disabled";
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("profitcenter");
	$objGrid->addcolumn("dates");
	$objGrid->addcolumn("daysname");
	$objGrid->addcolumn("schedule");
	$objGrid->addcolumn("time_from");
	$objGrid->addcolumn("time_to");
	
	$objGrid->columntitle("profitcenter","Profit Center");
	$objGrid->columntitle("dates","Date");
	$objGrid->columntitle("daysname","Days");
	$objGrid->columntitle("schedule","Schedule Assign");
	$objGrid->columntitle("time_from","Time From");
	$objGrid->columntitle("time_to","Time To");
	
	$objGrid->columnwidth("profitcenter",25);
	$objGrid->columnwidth("dates",10);
	$objGrid->columnwidth("daysname",10);
	$objGrid->columnwidth("schedule",25);
	$objGrid->columnwidth("time_from",12);
	$objGrid->columnwidth("time_to",12);
	
	$objGrid->columnvisibility("profitcenter",false);
	
	$objGrid->automanagecolumnwidth = false;
	
	$objGrid->width = 700;
	$objGrid->height = 280;
	
	$objGridR = new grid("T2",$httpVars,true);
	$objGridR->addcolumn("requestlist");
	$objGridR->addcolumn("pending");
	$objGridR->addcolumn("approved");
	$objGridR->addcolumn("denied");
	$objGridR->addcolumn("cancelled");
	$objGridR->addcolumn("total");
	
	$objGridR->columntitle("requestlist","Request List");
	$objGridR->columntitle("pending","Pending");
	$objGridR->columntitle("approved","Approved");
	$objGridR->columntitle("denied","Denied");
	$objGridR->columntitle("cancelled","Cancelled");
	$objGridR->columntitle("total","Total");
	
	$objGridR->columnwidth("requestlist",25);
	$objGridR->columnwidth("pending",10);
	$objGridR->columnwidth("approved",10);
	$objGridR->columnwidth("denied",10);
	$objGridR->columnwidth("cancelled",10);
	$objGridR->columnwidth("total",10);
	
	$objGridR->width = 700;
	$objGridR->height = 280;
	$objGridR->automanagecolumnwidth = false;
	
	$objGridL = new grid("T3",$httpVars,true);
	$objGridL->addcolumn("leavetype");
	$objGridL->addcolumn("leavetypedesc");
	$objGridL->addcolumn("balance");
	
	$objGridL->columntitle("leavetypedesc","Leave Type");
	$objGridL->columntitle("balance","Balance");
	
	$objGridL->columnwidth("leavetypedesc",25);
	$objGridL->columnwidth("balance",10);
	$objGridL->columnvisibility("leavetype",false);
	$objGridL->columnalignment("balance","right");
	$objGridL->width = 350;
	$objGridL->height = 280;
	$objGridL->automanagecolumnwidth = false;
	
	$objGridA = new grid("T4",$httpVars,true);
	$objGridA->addcolumn("profitcenter");
	$objGridA->addcolumn("empid");
	$objGridA->addcolumn("empname");
	$objGridA->addcolumn("dates");
	$objGridA->addcolumn("daysname");
	$objGridA->addcolumn("schedule");
	$objGridA->addcolumn("time_from");
	$objGridA->addcolumn("time_to");
	
	$objGridA->columntitle("profitcenter","Profit Center");
	$objGridA->columntitle("empid","ID & Name");
	$objGridA->columntitle("empname","ID & Name");
	$objGridA->columntitle("dates","Date");
	$objGridA->columntitle("daysname","Days");
	$objGridA->columntitle("schedule","Schedule Assign");
	$objGridA->columntitle("time_from","Time From");
	$objGridA->columntitle("time_to","Time To");
	
	$objGridA->columnwidth("profitcenter",25);
	$objGridA->columnwidth("empid",25);
	$objGridA->columnwidth("empname",25);
	$objGridA->columnwidth("dates",10);
	$objGridA->columnwidth("daysname",10);
	$objGridA->columnwidth("schedule",25);
	$objGridA->columnwidth("time_from",10);
	$objGridA->columnwidth("time_to",10);
	
	$objGridA->columnvisibility("empid",false);
	
	$objGridA->automanagecolumnwidth = false;
	
	$objGridA->width = 1200;
	$objGridA->height = 280;
	
	$objGridLL = new grid("T30",$httpVars,true);
	$objGridLL->addcolumn("leavetype");
	$objGridLL->addcolumn("leavedate");
	$objGridLL->addcolumn("leaveday_in");
	$objGridLL->addcolumn("leaveday_out");
	$objGridLL->addcolumn("leaveday_bal");
	
	$objGridLL->columntitle("leavetype","Leave Type");
	$objGridLL->columntitle("leavedate","Date");
    $objGridLL->columntitle("leaveday_in","Leave In");
    $objGridLL->columntitle("leaveday_out","Leave Out");
    $objGridLL->columntitle("leaveday_bal","Balance");
	
    $objGridLL->columnwidth("leavetype",20);
	$objGridLL->columnwidth("leavedate",10);
	$objGridLL->columnwidth("leaveday_in",7);
	$objGridLL->columnwidth("leaveday_out",7);
	$objGridLL->columnwidth("leaveday_bal",7);
	
	$objGridLL->columnalignment("leaveday_in","right");
	$objGridLL->columnalignment("leaveday_out","right");
	$objGridLL->columnalignment("leaveday_bal","right");
	
	$objGridLL->automanagecolumnwidth = false;
	
	$objGridLL->width = 500;
	$objGridLL->height = 280;
	
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
		
	require("./inc/formactions.php");
		
	$filterExp = "";
	
	$rptcols = 6; 
	$page->toolbar->setaction("update",false);
	$page->toolbar->setaction("print",false);
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
		} else {
			leaveShowTotal();
		}
	}
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "from_date":
			case "to_date":
				if (getInput("from_date") != "" && getInput("to_date") != "" ) {
					compDTRperDayList();
				}
			break;
			case "rfrom_date":
			case "rto_date":
				if (getInput("rfrom_date") != "" && getInput("rto_date") != "" ) {
					requestShowTotal();
				}
			break;
			case "afrom_date":
			case "ato_date":
			case "apf":
			case "aempid":
				if (getInput("afrom_date") != "" && getInput("ato_date") != "" ) {
					compDTRperDayListA();
				}
			break;
		}
		return true;
	}	
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				//computeTotalFees();
				break;
		}		
		return params;
	}
	
	function onReportGetParams(formattype,params) {
		if (params==null) params = new Array();
		params["source"] = "aspx";
		params["action"] = "processReport.aspx";
		params["querystring"] += generateQueryString("date1",formatDateToDB(getInput("from_date")));
		params["querystring"] += generateQueryString("date2",formatDateToDB(getInput("to_date")));
		params["querystring"] += generateQueryString("empid",getGlobal("userid"));
		params["reportfile"] = getVar("approotpath") +  "AddOns\\GPS\\TPHReports Add-On\\UserRpts\\\DTRperDay.rpt"; 	
		return params;
	}
	
	/*function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setInput("afdocno",getTableInput(p_tableId,"docno",p_rowIdx));
				setInput("studentname",getTableInput(p_tableId,"u_studentname",p_rowIdx));
				showPopupFrame("popupFrameUserForm");
				
				break;
		}		
		return false;
	}*/
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_to_date":
			case "df_from_date":
			case "df_rto_date":
			case "df_rfrom_date":
			case "df_ato_date":
			case "df_afrom_date":
			case "df_apf":
			case "df_aempid":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("from_date");
			inputs.push("to_date");
			inputs.push("rfrom_date");
			inputs.push("rto_date");
			inputs.push("afrom_date");
			inputs.push("ato_date");
			inputs.push("apf");
			inputs.push("aempid");
		return inputs;
	}
	

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "from_date":
			case "to_date":
			case "rfrom_date":
			case "rto_date":
			case "afrom_date":
			case "ato_date":
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
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				compDTRperDay(getTableInput(table,"profitcenter",row),getGlobal("userid"),formatDateToDB(getTableInput(table,"dates",row)));
				break;
			case "T3":
				showPopupFrame("popupFrameLeaveLedger");
				compLeaveLL(getTableInput(table,"leavetype",row));
				break;
			case "T4":
				showPopupFrame("popupFrameDTRDays");
				compDTRperDayA(getTableInput(table,"profitcenter",row),getTableInput(table,"empid",row),getTableInput(table,"empname",row),formatDateToDB(getTableInput(table,"dates",row)));
				break;
		}		
		return params;
	}
	
	function compDTRperDayA(pf,empid,empname,dates) {
		var result;
			result = page.executeFormattedQuery("SELECT u_dtrdates,u_profitcenter,u_empid,u_fromtime,u_totime,u_rd_wd,u_rd_whnd,u_rd_woth,u_rd_wothnd,u_rd_whwor,u_rd_whndwor,u_rd_wothwor,u_rd_wothndwor,u_rd_rwh,u_rd_abs,u_rd_abs/8 as absent_days,u_rd_late,u_rd_late*60 as late_mins,u_rd_ut,u_rd_ut*60 as ut_mins,u_rd_leave,u_sh_wd,u_sh_whnd,u_sh_woth,u_sh_wothnd,u_sh_whwor,u_sh_whndwor,u_sh_wothwor,u_sh_wothndwor,u_rl_wd,u_rl_whnd,u_rl_woth,u_rl_wothnd,u_rl_whwor,u_rl_whndwor,u_rl_wothwor,u_rl_wothndwor  FROM u_tkdtrperday a INNER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND a.u_empid = b.code WHERE a.u_empid ='"+empid+"' AND a.u_dtrdates ='"+dates+"' AND b.u_branch ='"+pf+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("a_id",empname);
						setInput("a_late_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_late"),"amount"));
						setInput("a_late_mins",formatNumber(result.childNodes.item(0).getAttribute("late_mins"),"amount"));
						setInput("a_ut_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_ut"),"amount"));
						setInput("a_ut_mins",formatNumber(result.childNodes.item(0).getAttribute("ut_mins"),"amount"));
						setInput("a_absent_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_abs"),"amount"));
						setInput("a_absent_day",formatNumber(result.childNodes.item(0).getAttribute("absent_days"),"amount"));
						
						setInput("a_regwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wd"),"amount"));
						setInput("a_regnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_whnd"),"amount"));
						setInput("a_regot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_woth"),"amount"));
						setInput("a_regotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wothnd"),"amount"));
						setInput("a_regworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_whwor"),"amount"));
						setInput("a_regwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_whndwor"),"amount"));
						setInput("a_regworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wothwor"),"amount"));
						setInput("a_regworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wothndwor"),"amount"));
						
						setInput("a_shwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wd"),"amount"));
						setInput("a_shnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_whnd"),"amount"));
						setInput("a_shot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_woth"),"amount"));
						setInput("a_shotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wothnd"),"amount"));
						setInput("a_shworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_whwor"),"amount"));
						setInput("a_shwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_whndwor"),"amount"));
						setInput("a_shworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wothwor"),"amount"));
						setInput("a_shworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wothndwor"),"amount"));
						
						setInput("a_rlwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wd"),"amount"));
						setInput("a_rlnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_whnd"),"amount"));
						setInput("a_rlot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_woth"),"amount"));
						setInput("a_rlotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wothnd"),"amount"));
						setInput("a_rlworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_whwor"),"amount"));
						setInput("a_rlwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_whndwor"),"amount"));
						setInput("a_rlworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wothwor"),"amount"));
						setInput("a_rlworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wothndwor"),"amount"));
						}
					} else {
						hideAjaxProcess();
						page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
						return false;
					}
		return true;
	}
	
	function compDTRperDay(pf,empid,dates) {
		var result;
			result = page.executeFormattedQuery("SELECT u_dtrdates,u_profitcenter,u_empid,u_fromtime,u_totime,u_rd_wd,u_rd_whnd,u_rd_woth,u_rd_wothnd,u_rd_whwor,u_rd_whndwor,u_rd_wothwor,u_rd_wothndwor,u_rd_rwh,u_rd_abs,u_rd_abs/8 as absent_days,u_rd_late,u_rd_late*60 as late_mins,u_rd_ut,u_rd_ut*60 as ut_mins,u_rd_leave,u_sh_wd,u_sh_whnd,u_sh_woth,u_sh_wothnd,u_sh_whwor,u_sh_whndwor,u_sh_wothwor,u_sh_wothndwor,u_rl_wd,u_rl_whnd,u_rl_woth,u_rl_wothnd,u_rl_whwor,u_rl_whndwor,u_rl_wothwor,u_rl_wothndwor  FROM u_tkdtrperday a INNER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND a.u_empid = b.code WHERE a.u_empid ='"+empid+"' AND a.u_dtrdates ='"+dates+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("late_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_late"),"amount"));
						setInput("late_mins",formatNumber(result.childNodes.item(0).getAttribute("late_mins"),"amount"));
						setInput("ut_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_ut"),"amount"));
						setInput("ut_mins",formatNumber(result.childNodes.item(0).getAttribute("ut_mins"),"amount"));
						setInput("absent_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_abs"),"amount"));
						setInput("absent_day",formatNumber(result.childNodes.item(0).getAttribute("absent_days"),"amount"));
						
						setInput("regwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wd"),"amount"));
						setInput("regnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_whnd"),"amount"));
						setInput("regot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_woth"),"amount"));
						setInput("regotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wothnd"),"amount"));
						setInput("regworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_whwor"),"amount"));
						setInput("regwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_whndwor"),"amount"));
						setInput("regworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wothwor"),"amount"));
						setInput("regworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rd_wothndwor"),"amount"));
						
						setInput("shwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wd"),"amount"));
						setInput("shnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_whnd"),"amount"));
						setInput("shot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_woth"),"amount"));
						setInput("shotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wothnd"),"amount"));
						setInput("shworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_whwor"),"amount"));
						setInput("shwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_whndwor"),"amount"));
						setInput("shworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wothwor"),"amount"));
						setInput("shworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_sh_wothndwor"),"amount"));
						
						setInput("rlwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wd"),"amount"));
						setInput("rlnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_whnd"),"amount"));
						setInput("rlot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_woth"),"amount"));
						setInput("rlotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wothnd"),"amount"));
						setInput("rlworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_whwor"),"amount"));
						setInput("rlwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_whndwor"),"amount"));
						setInput("rlworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wothwor"),"amount"));
						setInput("rlworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("u_rl_wothndwor"),"amount"));
						}
					} else {
						hideAjaxProcess();
						page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
						return false;
					}
		return true;
	}
	
	function compLeaveLL(leavetype) {
		var result;
		var data = new Array();
		clearTable("T30",true);
			result = page.executeFormattedQuery("SELECT x.u_date_filter,x.name,x.opening,x.leavedays,x.balance FROM (SELECT x.u_date_filter,x.name,x.modes,IF(x.name != @name,@balance:=0,0) as checking,@name:=x.name,@bal:=x.balance as opening,@pymt:=x.u_leavedays as leavedays,@balance:=(@balance+@bal)-@pymt as balance FROM (SELECT a.u_appdate as u_date_filter,e.name,a.u_leavetype,a.u_leavereason,0 as balance,SUM(bb.u_leavedays) as u_leavedays,'B' as modes FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid bb ON bb.company = a.company AND bb.branch = a.branch AND bb.code = a.code INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid INNER JOIN u_tkleavemasterfiles e ON e.company = a.company AND e.branch = a.branch AND e.code = a.u_leavetype WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c.code = '"+getGlobal("userid")+"' AND a.u_status = 1 GROUP BY a.code UNION ALL SELECT u_dates,'Reset',u_leavetype,'' as reason,(-1*u_balance),0,'A' FROM u_tkleaveresetbalance WHERE u_empid = '"+getGlobal("userid")+"' UNION ALL SELECT a.u_date_filter,e.name,a.u_typeofleave,'' as reason,a.u_balance,0 as leavedays,'A' as modes FROM u_hremploymentinfoleavebal a INNER JOIN u_tkleavemasterfiles e ON e.company = a.company AND e.branch = a.branch AND e.code = a.u_typeofleave INNER JOIN u_prglobalsetting xx ON xx.company = a.company AND xx.branch = a.branch WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"') as x,(SELECT @balance:=0.00,@pymt:=0.00,@bal:=0.00,@name:='') r WHERE x.u_leavetype IN(SELECT a.code FROM u_prglobalsetting b INNER JOIN u_tkleavemasterfiles a ON b.company = a.company AND b.branch = a.branch LEFT JOIN (SELECT branch,company,MAX(u_leavetype) as status1,code as codes FROM u_tkleavemasterfiles GROUP BY u_leavetype) as x ON x.company = a.company AND x.branch = a.branch) AND x.u_leavetype = '"+leavetype+"' ORDER BY x.u_leavetype,x.u_date_filter,x.modes) as x");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["leavetype"] = result.childNodes.item(xxxi).getAttribute("name");
							data["leavedate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_date_filter"));
							data["leaveday_in"] = formatNumber(result.childNodes.item(xxxi).getAttribute("opening"),"amount");
							data["leaveday_out"] = formatNumber(result.childNodes.item(xxxi).getAttribute("leavedays"),"amount");
							data["leaveday_bal"] = formatNumber(result.childNodes.item(xxxi).getAttribute("balance"),"amount");
							insertTableRowFromArray("T30",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving Leave Ledger. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
	function compDTRperDayList() {
		var result;
		var data = new Array();
		clearTable("T1",true);
			result = page.executeFormattedQuery("SELECT z.u_daysname,IFNULL(xxxx.name,'No Schedule') as u_scheduletype,fx.name as u_timein,tx.name as u_timeout2,if(ifnull(z.u_status,'') = 'Leave',1,0) as leave_days,zz.name as prof,x.u_profitcenter,x.u_dtrdates as datedue FROM u_tkdtrperday x LEFT OUTER JOIN u_tkschedulecolumntorow z ON z.company = x.company AND z.branch = x.branch AND z.u_empid = x.u_empid AND z.u_profitcenter = x.u_profitcenter AND CONCAT(z.u_year,'-',z.u_month,'-',IF(LENGTH(z.u_days) =1,CONCAT('0',z.u_days),z.u_days)) = x.u_dtrdates AND z.u_status != '' LEFT OUTER JOIN u_premploymentdeployment xxx ON xxx.company = z.company AND xxx.branch = z.branch AND xxx.code = x.u_empid LEFT OUTER JOIN u_prprofitcenter zz ON zz.company = z.company AND zz.branch = z.branch AND zz.code = x.u_profitcenter LEFT OUTER JOIN u_tkschedulemasterfiles xxxx ON xxxx.company = z.company AND xxxx.branch = z.branch AND xxxx.code = z.u_scheduleassign LEFT OUTER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(x.u_fromtime,5) LEFT OUTER JOIN u_tktimemasterfiles tx ON tx.code = LEFT(x.u_totime,5) WHERE x.company = '$company' AND x.branch = '$branch' AND x.u_empid = '"+getGlobal("userid")+"' AND x.u_dtrdates BETWEEN '"+formatDateToDB(getInput("from_date"))+"' AND '"+formatDateToDB(getInput("to_date"))+"' ORDER BY datedue");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["profitcenter"] = result.childNodes.item(xxxi).getAttribute("profitcenter");
							data["dates"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("datedue"));
							data["daysname"] = result.childNodes.item(xxxi).getAttribute("u_daysname");
							data["schedule"] = result.childNodes.item(xxxi).getAttribute("u_scheduletype");
							data["time_from"] = result.childNodes.item(xxxi).getAttribute("u_timein");
							data["time_to"] = result.childNodes.item(xxxi).getAttribute("u_timeout2");
							insertTableRowFromArray("T1",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
	function requestShowTotal() {
		var result;
		var data = new Array();
		clearTable("T2",true);
			result = page.executeFormattedQuery("SELECT req_type,SUM(pending) as pending,SUM(approved) as approved,SUM(denied) as denied,SUM(cancelled) as cancelled,SUM(total) as total FROM (SELECT 'Req. Official Business' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformass WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Leave' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformleave WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Loan' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformloan WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Note' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformnote WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Official Business Hrs' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformobset WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Off-set' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformoffset WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Over Time' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformot WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Time Adjustment' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformtimeadjustment WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"' UNION ALL SELECT 'Req. Official Business All' as req_type,IF(u_status =0,1,0) as pending,IF(u_status =1,1,0) as approved,IF(u_status =2,1,0) as denied,IF(u_status =-1,1,0) as cancelled,1 as total FROM u_tkrequestformoball WHERE company = '"+getGlobal("company")+"' AND branch = '"+getGlobal("branch")+"' AND u_empid = '"+getGlobal("userid")+"' AND u_appdate BETWEEN '"+formatDateToDB(getInput("rfrom_date"))+"' AND '"+formatDateToDB(getInput("rto_date"))+"') as x GROUP BY x.req_type;");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["requestlist"] = result.childNodes.item(xxxi).getAttribute("req_type");
							data["pending"] = result.childNodes.item(xxxi).getAttribute("pending");
							data["approved"] = result.childNodes.item(xxxi).getAttribute("approved");
							data["denied"] = result.childNodes.item(xxxi).getAttribute("denied");
							data["cancelled"] = result.childNodes.item(xxxi).getAttribute("cancelled");
							data["total"] = result.childNodes.item(xxxi).getAttribute("total");
							insertTableRowFromArray("T2",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
	function leaveShowTotal() {
		var result,result2;
		var data = new Array();
		clearTable("T3",true);
			result2 = page.executeFormattedQuery("CALL leave_running_balance('"+getGlobal("company")+"','"+getGlobal("branch")+"')");
			result = page.executeFormattedQuery("SELECT a.leavetype,b.name,a.balance FROM tmp_leavebalance a INNER JOIN u_tkleavemasterfiles b ON b.code = a.leavetype WHERE a.empid = '"+getGlobal("userid")+"' AND b.u_leavestatus != 'LWOP'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["leavetype"] = result.childNodes.item(xxxi).getAttribute("leavetype");
							data["leavetypedesc"] = result.childNodes.item(xxxi).getAttribute("name");
							data["balance"] = formatNumber(result.childNodes.item(xxxi).getAttribute("balance"),"amount");
							insertTableRowFromArray("T3",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
	function compDTRperDayListA() {
		var result;
		var data = new Array();
		clearTable("T4",true);
			result = page.executeFormattedQuery("SELECT z.u_daysname,IFNULL(xxxx.name,'No Schedule') as u_scheduletype,fx.name as u_timein,tx.name as u_timeout2,if(ifnull(z.u_status,'') = 'Leave',1,0) as leave_days,zz.name as prof,x.u_profitcenter,x.u_dtrdates as datedue FROM u_tkdtrperday x LEFT OUTER JOIN u_tkschedulecolumntorow z ON z.company = x.company AND z.branch = x.branch AND z.u_empid = x.u_empid AND z.u_profitcenter = x.u_profitcenter AND CONCAT(z.u_year,'-',z.u_month,'-',IF(LENGTH(z.u_days) =1,CONCAT('0',z.u_days),z.u_days)) = x.u_dtrdates AND z.u_status != '' LEFT OUTER JOIN u_premploymentdeployment xxx ON xxx.company = z.company AND xxx.branch = z.branch AND xxx.code = x.u_empid LEFT OUTER JOIN u_prprofitcenter zz ON zz.company = z.company AND zz.branch = z.branch AND zz.code = x.u_profitcenter LEFT OUTER JOIN u_tkschedulemasterfiles xxxx ON xxxx.company = z.company AND xxxx.branch = z.branch AND xxxx.code = z.u_scheduleassign LEFT OUTER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(x.u_fromtime,5) LEFT OUTER JOIN u_tktimemasterfiles tx ON tx.code = LEFT(x.u_totime,5) WHERE x.company = '$company' AND x.branch = '$branch' AND IF('"+getInput("aempid")+"' = '',''='',xxx.code = '"+getInput("aempid")+"') AND IF('"+getInput("apf")+"' = '',''='',xxx.u_branch = '"+getInput("apf")+"' AND x.u_dtrdates BETWEEN '"+formatDateToDB(getInput("afrom_date"))+"' AND '"+formatDateToDB(getInput("ato_date"))+"' ORDER BY datedue");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
							data["profitcenter"] = result.childNodes.item(xxxi).getAttribute("profitcenter");
							data["empid"] = result.childNodes.item(xxxi).getAttribute("empid");
							data["empname"] = result.childNodes.item(xxxi).getAttribute("empname");
							data["dates"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("datedue"));
							data["daysname"] = result.childNodes.item(xxxi).getAttribute("u_daysname");
							data["schedule"] = result.childNodes.item(xxxi).getAttribute("u_scheduletype");
							data["time_from"] = result.childNodes.item(xxxi).getAttribute("u_timein");
							data["time_to"] = result.childNodes.item(xxxi).getAttribute("u_timeout2");
							insertTableRowFromArray("T4",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
					return false;
				}

		return true;
	}
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data" action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Kiosk Payroll Dashboard&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
	<td>
	<div class="tabber" id="tab1">
    	<div class="tabbertab" title="Status Info">	
        	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td width="141"><label <?php genCaptionHtml($schema["rto_date"],"") ?>>Request From & To Date</label>
                  				  &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["rfrom_date"]) ?> />
                                  &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["rto_date"]) ?> />&nbsp;</td>
                </tr>
            	<tr>
                	<td><label class="lblobjs"><b>&nbsp;Request List Summary</b></label></td>
                    <td><label class="lblobjs"><b>&nbsp;Leave Balance Summary</b></label></td>
                </tr>
                <tr>
                	<td><?php $objGridR->draw(true) ?></td>
                    <td><?php $objGridL->draw(true) ?></td>
                </tr>
            </table>
        </div>
		<div class="tabbertab" title="Attendance Per Days">	
			<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="141"><label <?php genCaptionHtml($schema["to_date"],"") ?>>Payroll From & To Date</label></td>
                  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["from_date"]) ?> />
                                  &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["to_date"]) ?> />&nbsp;</td>
                  <td rowspan="2">
                      <table class="tableFreeFormRounded" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td valign="top">
                                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Hrs</label></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Night Diff Hrs</label></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Over Time Hrs</label></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Night Diff & Over Time Hrs</label></td>
                                    </tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp; Work in Rest Day</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Hrs</label></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Night Diff Hrs</label></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Over Time Hrs</label></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label class="lblobjs">&nbsp;Night Diff & Over Time Hrs</label></td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top">
                                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Regular</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regwh_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regnd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regot_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regotnd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regworwh_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regwornd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regworot_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["regworotnd_hrs"]) ?> /></td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top">
                                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Special</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shwh_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shnd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shot_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shotnd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shworwh_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shwornd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shworot_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["shworotnd_hrs"]) ?> /></td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top">
                                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Legal</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlwh_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlnd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlot_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlotnd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlworwh_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlwornd_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlworot_hrs"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["rlworotnd_hrs"]) ?> /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Other Info</b></label></td></tr>
                                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                                    <tr>
                                        <td width="168"><label <?php genCaptionHtml($schema["late_hrs"],"") ?>>&nbsp;Late ( Hrs / Mins )</label></td>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["late_hrs"]) ?> />
                                             &nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["late_mins"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label <?php genCaptionHtml($schema["ut_hrs"],"") ?>>&nbsp;Under Time ( Hrs / Mins )</label></td>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["ut_hrs"]) ?> />
                                             &nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["ut_mins"]) ?> /></td>
                                    </tr>
                                    <tr>
                                        <td width="168"><label <?php genCaptionHtml($schema["absent_hrs"],"") ?>>Absent ( Hrs / Days )</label></td>
                                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["absent_hrs"]) ?> />
                                             &nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["absent_day"]) ?> /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                      </table>
                  </td>
                </tr>
                <tr>
                  <td colspan="2"><?php $objGrid->draw(true) ?></td>
                </tr>
            </table>  
		</div>
        <?php 
			$objRsApprover = new recordset(null,$objConnection);
			$objRsApprover->queryopen("SELECT u_stagename FROM u_tkapproverfileassigned WHERE u_stagename = '".$_SESSION["userid"]."'");
			if ($objRsApprover->recordcount() > 0) {
		?>
        <div class="tabbertab" title="Daily Time Record Monitoring ( Approver )">
        	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr><td width="168"><label <?php genCaptionHtml($schema["apf"],"") ?>>Cost Center</label></td>
                    <td  align=left><select <?php genSelectHtml($schema["apf"],array("loadu_pf","",":"),"",null,null,"width:250px") ?> ></select></td>
                </tr>
                <tr><td width="168"><label <?php genCaptionHtml($schema["aempid"],"") ?>>Name : ID</label></td>
                	<td  align=left><select <?php genSelectHtml($schema["aempid"],array("loadu_empid","",":"),"",null,null,"width:250px") ?> ></select></td>
                </tr>
            	<tr>
                	<td width="141"><label <?php genCaptionHtml($schema["ato_date"],"") ?>>Payroll From & To Date</label></td>
                  	<td align=left><input type="text" size="12" <?php genInputTextHtml($schema["afrom_date"]) ?> />
                                  &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["ato_date"]) ?> />&nbsp;</td>
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
	</td>
</tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>&nbsp;</td></tr>
<?php //$page->resize->addgridobject($objGrid,320,400) ?>
<?php if ($requestorId == "") { ?>
<tr><td>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
<div <?php genPopWinHDivHtml("popupFrameDTRDays","DTR Per Days",200,50,550,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table class="tableFreeFormRounded" width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr>
            <td><label <?php genCaptionHtml($schema["a_id"],"") ?>>Name & ID</label></td>
            <td  colspan="4"><input type="text" size="50" <?php genInputTextHtml($schema["a_id"]) ?> /></td>
        </tr>
        <tr>
            <td valign="top" colspan="2">
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Hrs</label></td>
                    </tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Night Diff Hrs</label></td>
                    </tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Over Time Hrs</label></td>
                    </tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Night Diff & Over Time Hrs</label></td>
                    </tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp; Work in Rest Day</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Hrs</label></td>
                    </tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Night Diff Hrs</label></td>
                    </tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Over Time Hrs</label></td>
                    </tr>
                    <tr>
                        <td width="168"><label class="lblobjs">&nbsp;Night Diff & Over Time Hrs</label></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Regular</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regwh_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regnd_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regot_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regotnd_hrs"]) ?> /></td>
                    </tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regworwh_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regwornd_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regworot_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_regworotnd_hrs"]) ?> /></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Special</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shwh_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shnd_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shot_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shotnd_hrs"]) ?> /></td>
                    </tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shworwh_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shwornd_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shworot_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_shworotnd_hrs"]) ?> /></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Legal</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlwh_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlnd_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlot_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlotnd_hrs"]) ?> /></td>
                    </tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlworwh_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlwornd_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlworot_hrs"]) ?> /></td>
                    </tr>
                    <tr>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_rlworotnd_hrs"]) ?> /></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"><label class="lblobjs"><b>&nbsp;Other Info</b></label></td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr>
                        <td width="168"><label <?php genCaptionHtml($schema["a_late_hrs"],"") ?>>&nbsp;Late ( Hrs / Mins )</label></td>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_late_hrs"]) ?> />
                             &nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_late_mins"]) ?> /></td>
                    </tr>
                    <tr>
                        <td width="168"><label <?php genCaptionHtml($schema["a_ut_hrs"],"") ?>>&nbsp;Under Time ( Hrs / Mins )</label></td>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_ut_hrs"]) ?> />
                             &nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_ut_mins"]) ?> /></td>
                    </tr>
                    <tr>
                        <td width="168"><label <?php genCaptionHtml($schema["a_absent_hrs"],"") ?>>Absent ( Hrs / Days )</label></td>
                        <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_absent_hrs"]) ?> />
                             &nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["a_absent_day"]) ?> /></td>
                    </tr>
                </table>
            </td>
        </tr>
     </table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameLeaveLedger","Leave Ledger",200,50,550,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
	<td><?php $objGridLL->draw(true) ?></td>
	<td width="10">&nbsp;</td>
</tr>
</table>
</div>
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
