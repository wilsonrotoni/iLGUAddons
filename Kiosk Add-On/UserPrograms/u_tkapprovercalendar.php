<?php

	$paging = "udp.php?objectcode=".$progid;

	$empid = $_SESSION["userid"];//"D00000001";	
	if ($empid=="") $empid = $_GET["empid"];
	
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

	
	function onDrawCalendarHeader() {
		global $objConnection;
		global $empid;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select NAME from u_premploymentinfo where CODE='".$empid."'");
		if ($objRs->queryfetchrow("NAME")) {
			echo $objRs->fields["NAME"] . " - " ;
		}
	}
	
	function onGetCalendarScrollQueryString() {
		global $empid;
		return "&empid=" . $empid;
	}

	/*
	function onDrawCalendarToolbar() {
		global $objConnection;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select CODE, NAME from u_hisdoctors");
		
		echo "<td align=\"center\">";
		echo "<select name=\"empid\">\n";
		while ($objRs->queryfetchrow("NAME")) {
			echo "	<option value=\"".$objRs->fields["CODE"]."\"$sel>".$objRs->fields["NAME"]."</option>\n";
		}
		echo "</select>\n\n";
		echo "</td>";
	}
	*/
	function onDrawCalendarFilter() {
		global $objConnection;
		global $pf;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select CODE, NAME from U_PRPROFITCENTER");
		
		echo "<select id=\"pf\" name=\"pf\">\n";
		echo "	<option value=\"".""."\">"."[Select]"."</option>\n";		
		while ($objRs->queryfetchrow("NAME")) {
			$selected="";
			if ($pf==$objRs->fields["CODE"]) $selected="selected";
			echo "	<option value=\"".$objRs->fields["CODE"]."\" $selected>".$objRs->fields["NAME"]."</option>\n";
		}
		echo "</select>\n\n";
	}
	
	function onGetCalendarRestDays($month, $year) {
		$restdata = array("0"=>"#FF0000");
		return $restdata;
	}
			
	function onGetCalendarEvents($month, $year) {
		global $objConnection;
		global $empid;
		global $pf;
	
		$objRsColor = new recordset(null,$objConnection);

		$eventdata = null;
		
		$connect = odbc_connect("mysql_localhost_".$_SESSION["dbname"], "root",  $_SESSION['dbpassword']); 
		//$objRs->setdebug();
		$stmt = odbc_prepare($connect, "CALL calendar_approver('".$_SESSION['company']."','".$_SESSION['branch']."','".$empid."','".$pf."','".$month."','".$year."')");
		
		$success = odbc_execute($stmt);
		if ($success) {
			while($result = odbc_fetch_array($stmt) ) {
				$day = intval(date('d',strtotime($result["date_now"])));
				$sort = $result["sort"];
				$eventdata[$day]["id"][] = $result["code"];
				
				$title = "&nbsp;<b><font color='#000000'>".stripslashes($result["time1"])."</font></b>";
				
				$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title; 
				
				$timestr = "<div align=\"center\" class=\"time_str\">".$result["timeto"]."</div>\n";
				
				$objRsColor->queryopen("SELECT u_r_approver FROM u_prglobalsetting");
					while ($objRsColor->queryfetchrow("NAME")) {
						$eventdata[$day]["color"][] = $objRsColor->fields["u_r_approver"];
					}
				
				$eventdata[$day]["timestr"][] = $timestr;
	
				$eventdata[$day]["onclick"][] = "OpenPopupu_TKARequests('".$result["timeto"]."','".$result["sort"]."','".$result["status_type"]."','".$result["department"]."')";
					
			}
			
		}
		odbc_result_all($success );
		odbc_free_result($success );
		odbc_close($connect);		
		return $eventdata;
	}

	define("TITLE_CHAR_LIMIT", 80);		
	define("CELL_WIDTH", 200);		
	define("COLOR_EMPTY", "#808080");	
	define("CELL_HEIGHT", 70);	
	
	$objGridARequestList = new grid("T10");
	$objGridARequestList->addcolumn("req_docno");
	$objGridARequestList->addcolumn("req_empid");
	$objGridARequestList->addcolumn("req_date");
	$objGridARequestList->addcolumn("req_remarks");
	$objGridARequestList->addcolumn("req_aremarks");
	
	$objGridARequestList->columntitle("req_docno","Request No.");
	$objGridARequestList->columntitle("req_empid","Employee");
	$objGridARequestList->columntitle("req_date","Request Date");
	$objGridARequestList->columntitle("req_remarks","Request Remarks");
	$objGridARequestList->columntitle("req_aremarks","Approver Remarks");
	
	$objGridARequestList->columnwidth("req_docno",12);
	$objGridARequestList->columnwidth("req_empid",30);
	$objGridARequestList->columnwidth("req_date",10);
	$objGridARequestList->columnwidth("req_remarks",32);
	$objGridARequestList->columnwidth("req_aremarks",50);

	$objGridARequestList->automanagecolumnwidth = false;
	
	$objGridARequestList->width = 830;
	$objGridARequestList->height = 200;
	
	include_once("../Addons/GPS/Kiosk Add-On/UserPrograms/calendar2.php");
	
?>
<script>
	function OpenPopupu_TKARequests(types,sortz,status_type,department) {
		if(types == "Time Adj.") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "Loan") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "O.T.") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "Leave") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "Off-Set") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "O.B") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "Off-Set Hrs") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "OB-Hrs") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "Note") {
			showRequestLists(types,sortz,status_type,department);
		} else if(types == "OB-All") {
			showRequestLists(types,sortz,status_type,department);
		} else {
			alert(1);
		}
	}
	
	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T10":
				result = page.executeFormattedQuery("SELECT code,request_type FROM (SELECT code,'O.B' as request_type FROM u_tkrequestformass UNION ALL SELECT code,'Leave' as request_type FROM u_tkrequestformleave UNION ALL SELECT code,'Loan' as request_type FROM u_tkrequestformloan UNION ALL SELECT code,'Off-Set' as request_type FROM u_tkrequestformoffset UNION ALL SELECT code,'Off-Set Hrs' as request_type FROM u_tkrequestformoffsethrs UNION ALL SELECT code,'O.T' as request_type FROM u_tkrequestformot UNION ALL SELECT code,'Time Adj' as request_type FROM u_tkrequestformtimeadjustment UNION ALL SELECT code,'OB-Hrs' as request_no FROM u_tkrequestformobset UNION ALL SELECT code,'Note' as request_no FROM u_tkrequestformnote UNION ALL SELECT code,'OB-All' as request_no FROM u_tkrequestformoball) as x WHERE code = '"+getTableInput(p_tableId,"req_docno",p_rowIdx)+"'");
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							codeID = result.childNodes.item(0).getAttribute("code");
							sf_keys = codeID;
							if(result.childNodes.item(0).getAttribute("request_type") == "O.T") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformot' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'');
							} else if(result.childNodes.item(0).getAttribute("request_type") == "O.B") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformass' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'');
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Leave") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformleave' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Off-Set") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformoffset' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Off-Set Hrs") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformoffsethrs' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Time Adj") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformtimeadjustment' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Time Adj") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformloan' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "OB-Hrs") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformobset' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Note") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformnote' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "OB-All") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformoball' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							}
						}
					} else {
						page.statusbar.showError("Error retrieving Other Fees Name. Try Again, if problem persists, check the connection.");	
						return false;
					}
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(Id) {
		var params = new Array();
		params["keys"] = sf_keys;
		return params;
	}
	
	function showRequestLists(type_request,daysdate,status_type,department){
		var result, data = new Array();
		clearTable("T10",true);
		showPopupFrame("popupFrameADetail");
		if (type_request == 'O.T.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,a.u_otdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Date : ',DATE_FORMAT(a.u_otdate,'%m/%d/%Y'),' OT Time From',a.u_otfromtime,' & ',a.u_ottotime, ' Reason : ',a.u_otreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory WHERE u_status = 'A' GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,a.u_otdate,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,a.u_otdate,a.u_appdate)");
			
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Leave' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'), ' Reason : ',a.u_leavereason) as 'remarks',qq.remarks_ap FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND a.u_leavestatus = 'Successful' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)"); 
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Loan' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,a.u_loanfrom as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Loan Dates : ',DATE_FORMAT(a.u_loanfrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_loanto,'%m/%d/%y'), ' Reason : ',a.u_loanreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,a.u_loanfrom,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,a.u_loanfrom,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'Time Adj.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,aa.u_date as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Time Adjustment For Incorrect/Missing Time In/Out Reason : ',a.u_tareason) as 'remarks',qq.remarks_ap FROM u_tkrequestformtimeadjustment a INNER JOIN u_tkrequestformtimeadjustmentitems aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_tastatus = 'Successful' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,aa.u_date,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'O.B' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,a.u_tkdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y'), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,a.u_tkdate,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,a.u_tkdate,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'Off-Set' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Total Off-set Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Off-Set Hrs' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,a.u_offdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Work Hrs :',ROUND(a.u_offhrs,2),' Off-set Dates : ',DATE_FORMAT(a.u_offdate,'%m/%d/%y'), ' Reason : ',a.u_offreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformoffsethrs a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,a.u_offdate,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"'  GROUP BY a.code,IF(a.u_status = 1,a.u_offdate,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'OB-Hrs' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Total OB Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformobset a INNER JOIN u_tkrequestformobsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"'  GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}

		} else if (type_request == 'Note' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,a.u_appdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus, IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Request Note :', a.u_note) as 'remarks',qq.remarks_ap FROM u_tkrequestformnote a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND a.u_appdate = '"+daysdate+"' AND a.u_status = '"+status_type+"'  GROUP BY a.code, a.u_appdate");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		} else if (type_request == 'OB-All' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_empname as name,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Total OB All Request :',COUNT(aa.code), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformoball a INNER JOIN u_tkrequestformoballlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = b.code GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code INNER JOIN u_premploymentdeployment nn ON nn.company = a.company AND nn.branch = a.branch AND nn.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND nn.u_deptcode = '"+department+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["req_docno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["req_empid"] = result.childNodes.item(xxxi).getAttribute("name");
						data["req_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["req_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
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
	
	function onDateClick(date,day,month,year) {
		//var empid=document.getElementById('empid').value;
		var empid=getVar('empid');
		//OpenPopup(500,120,'./UDP.php?objectcode=u_tkeditbiometrix&empid='+empid+'&dates='+date,'');
	}
	
	function submitMonthYear() {
		document.monthYear.method = "post";
		document.monthYear.action = 
			"<?php echo $paging ?>&month=" + document.monthYear.month.value + 
			"&year=" + document.monthYear.year.value;
		document.monthYear.submit();
	}
			parent.document.getElementById('iframeBody').style.overflow = "auto";

</script>
<?php	
//	$htmltoolbarbottom = "";
?>
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
