<?php
	$progid = "u_tkrequestlist_reports";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/enumjedoctypes.php");
	include_once("./sls/docgroups.php");
	include_once("./sls/users.php");
	include_once("./sls/enumpostflag.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_SISSLEDGER";
	$page->paging->formid = "./UDP.php?objectcode=u_tkrequestlist_reports";
	$page->objectname = "Request List";
	

	require("./inc/formactions.php");
	
	$schema["u_empid"] = createSchemaUpper("u_empid");
	$schema["u_approver"] = createSchemaUpper("u_approver");
	$schema["u_date1"] = createSchemaDate("u_date1");
	$schema["u_date2"] = createSchemaDate("u_date2");
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_requestdate");
	$objGrid->addcolumn("u_requestno");
	$objGrid->addcolumn("u_requestpf");
	$objGrid->addcolumn("u_requestemp");
	$objGrid->addcolumn("u_requesttype");
	$objGrid->addcolumn("u_requestremarks");
	
	$objGrid->columntitle("u_requestdate","Application Date");
	$objGrid->columntitle("u_requestno","Request No.");
	$objGrid->columntitle("u_requestpf","Profit Center");
	$objGrid->columntitle("u_requestemp","Name");
	$objGrid->columntitle("u_requesttype","Request Type");
	$objGrid->columntitle("u_requestremarks","Remarks");
	
	$objGrid->columnwidth("u_requestdate",12);
	$objGrid->columnwidth("u_requestno",20);
	$objGrid->columnwidth("u_requestpf",20);
	$objGrid->columnwidth("u_requestemp",30);
	$objGrid->columnwidth("u_requesttype",20);
	$objGrid->columnwidth("u_requestremarks",50);
	
	$objGrid->columnlnkbtn("u_requestno","OpenLnkBtnu_refnostatus()","Show List of Approver No.");
	$objGrid->automanagecolumnwidth = false;
	
	$objGridDetail = new grid("T10");
	$objGridDetail->addcolumn("datecreated");
	$objGridDetail->addcolumn("status");
	$objGridDetail->addcolumn("approver");
	
	$objGridDetail->columntitle("datecreated","Date & Time");
	$objGridDetail->columntitle("status","Status");
	$objGridDetail->columntitle("approver","Approver Name");
	
	$objGridDetail->columnwidth("datecreated",12);	
	$objGridDetail->columnwidth("status",15);
	$objGridDetail->columnwidth("approver",30);

	$objGridDetail->automanagecolumnwidth = false;
	$objGridDetail->width=959;
	$objGridDetail->height = 200;
		
	$filterExp = "";
	
	$page->resize->addgrid("T1",20,190,false);
	
	$rptcols = 6; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
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
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		try {
			if (getVar("formSubmitAction")=="a") {
				setInput("u_docno",window.opener.getTableInput("T1","u_daystime",window.opener.getTableSelectedRow("T1")));
			}
		} catch (theError) {
		}
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "doctype": 
			case "docgroup": 
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "u_studentno":
			case "u_date":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_u_studentno":
			case "df_u_date":return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_studentno");
			inputs.push("u_date");
		return inputs;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_studentno":
			case "u_date":
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
		var imgpath = "", params = new Array();
		switch (table) {
			case "T1":
				if (isPopupFrameOpen("popupFrameDetail") && row>0) {
					showDetail(false);
				}
				break;
		}		
		return params;
	}
	
	function OpenLnkBtnu_refnostatus(retrieve) {
		/*OpenLnkBtn(1200,500,'./udo.php?objectcode=u_sisenrolleds' + '&opt=viewonly' + '&targetId=' + targetId ,targetId);*/
		if (retrieve==null) retrieve = false;
		showPopupFrame("popupFrameDetail");
		if (retrieve) {
			if (getTableSelectedRow("T1")>0) showDetail();
		}
	}
	
	function showRequestLists(type_request,daysdate1,daysdate2){
		var result, data = new Array();
		clearTable("T10",true);
		if (type_request == 'O.T.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_otdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Date : ',DATE_FORMAT(a.u_otdate,'%m/%d/%Y'),' OT Time From',a.u_otfromtime,' & ',a.u_ottotime, ' Reason : ',a.u_otreason) as 'remarks' FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory WHERE u_status = 'A' GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
			
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Leave' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'), ' Reason : ',a.u_leavereason) as 'remarks' FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_leavestatus = 'Successful' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate"); 
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Loan' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_loanfrom as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Loan Dates : ',DATE_FORMAT(a.u_loanfrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_loanto,'%m/%d/%y'), ' Reason : ',a.u_loanreason) as 'remarks' FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "Cancelled";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = "Denied"
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);

					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'Time Adj.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Time Adjustment For Incorrect/Missing Time In/Out Reason : ',a.u_tareason) as 'remarks' FROM u_tkrequestformtimeadjustment a INNER JOIN u_tkrequestformtimeadjustmentitems aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_tastatus = 'Successful' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'O.B' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_tkdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y'), ' Reason : ',a.u_assreason) as 'remarks' FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'Off-Set' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Total Off-set Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks' FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Off-Set Hrs' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_offdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Work Hrs :',ROUND(a.u_offhrs,2),' Off-set Dates : ',DATE_FORMAT(a.u_offdate,'%m/%d/%y'), ' Reason : ',a.u_offreason) as 'remarks' FROM u_tkrequestformoffsethrs a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'OB-Hrs' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Total OB Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks' FROM u_tkrequestformobset a INNER JOIN u_tkrequestformobsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+daysdate1+"' AND '"+daysdate2+"' AND a.u_status != 1 GROUP BY a.code,a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_appdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("appdate"));
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Approved") {
							data["req_approved"] = "Complete";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Cancelled") {
							data["req_approved"] = "";
						} else if(result.childNodes.item(xxxi).getAttribute("vstatus") == "Denied") {
							data["req_approved"] = ""
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else {
			page.statusbar.showError("Error retrieving For No Records. Try Again, if problem persists, check the connection.");	
			return false;
		}
		return true;
	}
	
	function showDetail(showprocess) {
		if (showprocess==null) showprocess = true;
		var row = getTableSelectedRow("T1"), data = new Array();
		if (row==0) return;

		if (showprocess) showAjaxProcess();
		clearTable("T10",true);
		if (getTableInput("T1","u_refno",row) != "") {
			var result = page.executeFormattedQuery("SELECT 'Drop' as 'types',a.u_otherdate as 'adate',a.docno as 'documentno',a.u_totaldropdue as 'amounts' FROM u_sisothertrans a WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_assentdocno = '"+getTableInput("T1","u_refno",row)+"' AND docseries = '96' UNION ALL SELECT 'Add' as 'type',a.u_otherdate as 'Date',a.docno as 'Document No.',a.u_totaldropdue as 'Amount' FROM u_sisothertrans a WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_assentdocno = '"+getTableInput("T1","u_refno",row)+"' AND docseries = '98' UNION ALL SELECT 'Changing' as 'type',a.u_otherdate as 'Date',a.docno as 'Document No.',a.u_totaldropdue as 'Amount' FROM u_sisothertrans a WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_assentdocno = '"+getTableInput("T1","u_refno",row)+"' AND docseries = '97'");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					data["docno"] = result.childNodes.item(xxxi).getAttribute("documentno");
					data["docdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("adate"));
					data["type"] = result.childNodes.item(xxxi).getAttribute("types")
					data["totalamount"] = result.childNodes.item(xxxi).getAttribute("amounts");
					insertTableRowFromArray("T10",data);
					}
				}
			}
		} else {
			if (showprocess) hideAjaxProcess();
			return false;
		}
		hideAjaxProcess();
		return true;
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Student Ledger&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="168" ><label <?php genCaptionHtml($schema["u_studentno"],"") ?>>Code</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["u_studentno"],array("loadudflinktable","u_sisstudententry:code:CONCAT(code,'-',name)",":[All]")) ?> ></select></td>
	</tr>
    <tr>
	  <td ><label <?php genCaptionHtml($schema["u_date"],"") ?>>Date</label></td>
	  <td  align=left><input type="text" <?php genInputTextHtml($schema["u_date"]) ?> /></td>
	</tr>
    <tr>
	  <td >&nbsp;</td>
	  <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;</td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->resize->addgridobject($objGrid,20,250) ?>
<?php $page->writeRecordLimit(); ?>
<tr ><td>&nbsp;</td></tr>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
<div <?php genPopWinHDivHtml("popupFrameDetail","List of Detail",240,130,1000,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2"><?php $objGridDetail->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<?php require("./bofrms/ajaxprocess.php"); ?>
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
