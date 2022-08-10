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
		$stmt = odbc_prepare($connect, "CALL calendar_shown('".$_SESSION['company']."','".$_SESSION['branch']."','".$empid."','".$pf."','".$month."','".$year."')");
		
		$success = odbc_execute($stmt);
		if ($success) {
			while($result = odbc_fetch_array($stmt) ) {
				$day = intval(date('d',strtotime($result["date_now"])));
				$sort = $result["sort"];
				$eventdata[$day]["id"][] = $result["code"];
				
				$title = "&nbsp;<b><font color='#000000'>".stripslashes($result["time1"])."</font></b>";
				
				$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title; 
				
				if($result["status_now"] == "X") {
					$timestr = "<div align=\"center\" class=\"time_str\">".$result["timeto"]."</div>\n";
				} else {
					$timestr = "<div align=\"center\" class=\"time_str\">".$result["timeto"]."</div>\n";
				}
				
				$objRsColor->queryopen("SELECT u_r_schedule,
											   u_r_approver,
											   u_r_holiday,
											   
											   u_r_ot,
											   u_r_leave,
											   u_r_offset,
											   u_r_ob,
											   u_r_timeadj,
											   u_r_loan,
											   u_r_note,
											   u_r_rms,
											   u_r_app,
											   u_r_memo,
											   
											   u_r_ot2,
											   u_r_leave2,
											   u_r_offset2,
											   u_r_ob2,
											   u_r_timeadj2,
											   u_r_loan2,
											   u_r_note2,
											   u_r_rms2,
											   u_r_app2,
											   u_r_memo2,
											   
											   u_r_ot3,
											   u_r_leave3,
											   u_r_offset3,
											   u_r_ob3,
											   u_r_timeadj3,
											   u_r_loan3,
											   u_r_note3,
											   u_r_rms3,
											   u_r_app3,
											   u_r_memo3,
											   
											   u_r_ot4,
											   u_r_leave4,
											   u_r_offset4,
											   u_r_ob4,
											   u_r_timeadj4,
											   u_r_loan4,
											   u_r_note4,
											   u_r_rms4,
											   u_r_app4,
											   u_r_memo4
										
										FROM u_prglobalsetting");
					while ($objRsColor->queryfetchrow("NAME")) {
						if($result["timeto"] == "Time Adj.") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_timeadj"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_timeadj2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_timeadj3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_timeadj4"];
							}
						} else if($result["timeto"] == "Loan") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_loan"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_loan2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_loan3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_loan4"];
							}
						} else if($result["timeto"] == "O.T.") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ot"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ot2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ot3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ot4"];
							}
						} else if($result["timeto"] == "Leave") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_leave"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_leave2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_leave3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_leave4"];
							}
						} else if($result["timeto"] == "O.B") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob4"];
							}
						} else if($result["timeto"] == "OB-Hrs") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob4"];
							}
						} else if($result["timeto"] == "OB-All") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob2"];
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_ob4"];
							}
						} else if($result["timeto"] == "Note") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_note"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_note2"];	
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_note3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_note4"];
							}
						} else if($result["timeto"] == "APP") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_app"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_app2"];	
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_app3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_app4"];
							}
						} else if($result["timeto"] == "Memo") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_memo"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_memo2"];	
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_memo3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_memo4"];
							}
						} else if($result["timeto"] == "POR") {
							if($result["status_type"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_rms"];
							} else if($result["status_type"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_rms2"];	
							} else if($result["status_type"] == 2) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_rms3"];
							} else if($result["status_type"] == -1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_rms4"];
							}
						} else if($result["timeto"] == "Restday") {
							$eventdata[$day]["color"][] = $objRsColor->fields["u_r_schedule"];
						} else if($result["timeto"] == "Days") {
							$eventdata[$day]["color"][] = $objRsColor->fields["u_r_schedule"];
						} else if($result["timeto"] == "Off-Set") {
							if($objRs->fields["time1"] == 0) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_offset"];
							} else if($objRs->fields["time1"] == 1) {
								$eventdata[$day]["color"][] = $objRsColor->fields["u_r_offset2"];
							}
						} else if($result["timeto"] == "Approver") {
							$eventdata[$day]["color"][] = $objRsColor->fields["u_r_approver"];	
						} else if($result["timeto"] == "Holiday S") {
							$eventdata[$day]["color"][] = $objRsColor->fields["u_r_holiday"];	
						} else if($result["timeto"] == "Holiday L") {
							$eventdata[$day]["color"][] = $objRsColor->fields["u_r_holiday"];	
						} else {
							$eventdata[$day]["color"][] = $objRsColor->fields["u_r_schedule"];	
						}
					}
				
				
				$eventdata[$day]["timestr"][] = $timestr;
	
				$eventdata[$day]["onclick"][] = "OpenPopupu_TKOpenRequests('".$result["timeto"]."','".$result["code"]."','".$result["sort"]."','".$result["status_now"]."','".$result["time1"]."','".$result["status_type"]."')";
					
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
	
	$objGridRequestList = new grid("T10");
	$objGridRequestList->addcolumn("req_docno");
	$objGridRequestList->addcolumn("req_appdate");
	$objGridRequestList->addcolumn("req_date");
	$objGridRequestList->addcolumn("req_status");
	$objGridRequestList->addcolumn("req_approved");
	$objGridRequestList->addcolumn("req_remarks");
	$objGridRequestList->addcolumn("req_aremarks");
	
	$objGridRequestList->columntitle("req_docno","Request No.");
	$objGridRequestList->columntitle("req_appdate","Application Date");
	$objGridRequestList->columntitle("req_date","Request Date");
	$objGridRequestList->columntitle("req_remarks","Request Remarks");
	$objGridRequestList->columntitle("req_status","Status");
	$objGridRequestList->columntitle("req_approved","Next Approver / Remarks");
	$objGridRequestList->columntitle("req_aremarks","Approver Remarks");
	
	$objGridRequestList->columnwidth("req_docno",12);
	$objGridRequestList->columnwidth("req_appdate",10);
	$objGridRequestList->columnwidth("req_date",10);
	$objGridRequestList->columnwidth("req_remarks",32);
	$objGridRequestList->columnwidth("req_status",8);
	$objGridRequestList->columnwidth("req_approved",18);
	$objGridRequestList->columnwidth("req_aremarks",50);

	$objGridRequestList->automanagecolumnwidth = false;
	
	$objGridRequestList->width = 830;
	$objGridRequestList->height = 200;
	
	include_once("../Addons/GPS/Kiosk Add-On/UserPrograms/calendar.php");
	
?>
<script>
	var sf_keys;
	function OpenPopupu_TKOpenRequests(types,keys,sortz,codes,modes,status_type) {
		sf_keys = keys;
		if(types == "Time Adj.") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Loan") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "O.T.") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Leave") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Off-Set") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "O.B") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Off-Set Hrs") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "OB-Hrs") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Note") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "OB-All") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "APP") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Memo") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "POR") {
			showRequestLists(types,sortz,status_type);
		} else if(types == "Approver") {
			var checklockec = page.executeFormattedSearch("SELECT b.u_approvedlocked FROM u_prpayrollperiod a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.u_payfrequency = a.u_typeofperiod WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND '"+sortz+"' BETWEEN b.u_from_date AND b.u_to_date AND c.code = '"+getGlobal("userid")+"'");
			if(checklockec == "LOCK") {
					page.statusbar.showError("This Period is already Locked.. Please Contact the Administrator or H.R.");		
					return false;
			} else if(checklockec == "") {
					page.statusbar.showError("No Payroll Period Maintain For this Date.. Please Contact the Administrator or H.R.");		
					return false;
			} else if(checklockec == "UNLOCK") {
				formView(null,'./UDP.php?objectcode=u_tkapprovedhistory' + '' + '&dates='+keys+'&rtypes='+sortz);
				//OpenLnkBtn(1200,600,'./UDP.php?objectcode=u_tkapprovedhistory' + '' + '&dates='+keys+'&rtypes='+sortz,'');
			}
				
		}
		
	}
	
	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T10":
				result = page.executeFormattedQuery("SELECT code,request_type FROM (SELECT code,'O.B' as request_type FROM u_tkrequestformass UNION ALL SELECT code,'Leave' as request_type FROM u_tkrequestformleave UNION ALL SELECT code,'Loan' as request_type FROM u_tkrequestformloan UNION ALL SELECT code,'Off-Set' as request_type FROM u_tkrequestformoffset UNION ALL SELECT code,'Off-Set Hrs' as request_type FROM u_tkrequestformoffsethrs UNION ALL SELECT code,'O.T' as request_type FROM u_tkrequestformot UNION ALL SELECT code,'Time Adj' as request_type FROM u_tkrequestformtimeadjustment UNION ALL SELECT code,'OB-Hrs' as request_no FROM u_tkrequestformobset UNION ALL SELECT code,'Note' as request_no FROM u_tkrequestformnote UNION ALL SELECT code,'OB-All' as request_no FROM u_tkrequestformoball UNION ALL SELECT code,'RMS' as request_no FROM u_tkrequestformrms UNION ALL SELECT code,'App' as request_no FROM u_tkrequestformappointment UNION ALL SELECT code,'Memo' as request_no FROM u_tkrequestformmemo) as x WHERE code = '"+getTableInput(p_tableId,"req_docno",p_rowIdx)+"'");
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
							} else if(result.childNodes.item(0).getAttribute("request_type") == "Memo") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformmemo' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "App") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformappointment' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
							} else if(result.childNodes.item(0).getAttribute("request_type") == "RMS") {
								OpenLnkBtn(900,450,'./UDO.php?objectcode=u_tkrequestformrms' + '&opt=viewonly' + '&formAction=e&sf_keys='+codeID,'')
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
	
	function showRequestLists(type_request,daysdate,status_type){
		var result, data = new Array();
		clearTable("T10",true);
		showPopupFrame("popupFrameDetail");
		if (type_request == 'O.T.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_otdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Date : ',DATE_FORMAT(a.u_otdate,'%m/%d/%Y'),' OT Time From',a.u_otfromtime,' & ',a.u_ottotime, ' Reason : ',a.u_otreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory WHERE u_status = 'A' GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_otdate,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,a.u_otdate,a.u_appdate)");
			
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Leave' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'), ' Reason : ',a.u_leavereason) as 'remarks',qq.remarks_ap FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus5 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_leavestatus = 'Successful' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)"); 
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Loan' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_loanfrom as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Loan Dates : ',DATE_FORMAT(a.u_loanfrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_loanto,'%m/%d/%y'), ' Reason : ',a.u_loanreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus8 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_loanfrom,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,a.u_loanfrom,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'Time Adj.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Time Adjustment For Incorrect/Missing Time In/Out Reason : ',a.u_tareason) as 'remarks',qq.remarks_ap FROM u_tkrequestformtimeadjustment a INNER JOIN u_tkrequestformtimeadjustmentitems aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus6 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_tastatus = 'Successful' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'O.B' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_tkdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y'), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus3 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_tkdate,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,a.u_tkdate,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		
		} else if (type_request == 'Off-Set' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Total Off-set Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus4 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'Off-Set Hrs' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_offdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Work Hrs :',ROUND(a.u_offhrs,2),' Off-set Dates : ',DATE_FORMAT(a.u_offdate,'%m/%d/%y'), ' Reason : ',a.u_offreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformoffsethrs a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus4 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_offdate,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"'  GROUP BY a.code,IF(a.u_status = 1,a.u_offdate,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
			
		} else if (type_request == 'OB-Hrs' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Total OB Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformobset a INNER JOIN u_tkrequestformobsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus3 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"'  GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}

		} else if (type_request == 'Note' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_appdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus, IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Request Note :', a.u_note) as 'remarks',qq.remarks_ap FROM u_tkrequestformnote a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus7 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate = '"+daysdate+"' AND a.u_status = '"+status_type+"'  GROUP BY a.code, a.u_appdate");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		} else if (type_request == 'OB-All' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Total OB All Request :',COUNT(aa.code), ' Reason : ',a.u_assreason) as 'remarks',qq.remarks_ap FROM u_tkrequestformoball a INNER JOIN u_tkrequestformoballlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus3 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code	WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}

		} else if (type_request == 'APP' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_appdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver', CONCAT('Request Appointment : ',m1.name,' : Branch :',m2.name,' Position :',m3.name,' Section :',m4.name) as 'remarks', qq.remarks_ap FROM u_tkrequestformappointment a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_approver LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code  = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code LEFT JOIN u_prlabortype m1 ON m1.company = a.company AND m1.branch = a.branch AND m1.code = a.u_empstatus LEFT JOIN u_prjcategory m2 ON m2.company = a.company AND m2.branch = a.branch AND m2.code = a.u_branches LEFT JOIN u_prposition m3 ON m3.company = a.company AND m3.branch = a.branch AND m3.code = a.u_position LEFT JOIN u_prsection m4 ON m4.company = a.company AND m4.branch = a.branch AND m4.code = a.u_section WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code, a.u_appdate");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		} else if (type_request == 'POR' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_appdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Request Personnel : Position : ', m3.name) as 'remarks',qq.remarks_ap FROM u_tkrequestformrms a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN u_prposition m3 ON m3.company = a.company AND m3.branch = a.branch AND m3.code = a.u_position LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus10 LEFT JOIN u_tkapproverfile app ON app.company = m3.company AND app.branch = m3.branch AND app.code = m3.u_approver LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code IN(c.code,app.code) AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code,a.u_appdate");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
						data["req_aremarks"] = result.childNodes.item(xxxi).getAttribute("remarks_ap");
						insertTableRowFromArray("T10",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}

		} else if (type_request == 'Memo' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_appdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,IFNULL(cc.name,xx.u_remarks) as 'approver',CONCAT('Request Memo : Description :', m3.name,' Level : ',m4.name) as 'remarks',qq.remarks_ap FROM u_tkrequestformmemo a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN u_hrsanctionmasterfile m3 ON m3.company = a.company AND m3.branch = a.branch AND m3.code = a.u_sanctiontype LEFT JOIN u_hrsanctionlevelmasterfile m4 ON m4.company = a.company AND m4.branch = a.branch AND m4.code = a.u_level LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code LEFT JOIN (SELECT u_requestno,u_remarks FROM u_tkapproverhistory WHERE u_status = 'D' GROUP BY u_requestno) as xx ON xx.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus11 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename LEFT JOIN (SELECT u_requestno, GROUP_CONCAT(approver_remarks SEPARATOR '<br>') as remarks_ap FROM (SELECT a.u_requestno,CONCAT(b.name,' : ',u_remarks) as approver_remarks FROM u_tkapproverhistory a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.createdby WHERE a.u_empid = '"+getGlobal("userid")+"' GROUP BY a.u_requestno,a.code) as x GROUP BY u_requestno) as qq ON qq.u_requestno = a.code WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_appdate = '"+daysdate+"' AND a.u_status = '"+status_type+"' GROUP BY a.code, a.u_appdate");
		
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
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver")
						} else {
							data["req_approved"] = result.childNodes.item(xxxi).getAttribute("approver");
						}
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
