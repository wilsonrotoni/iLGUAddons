<?php

	$paging = "udp.php?objectcode=".$progid;

	$doctorid = $_SESSION["userid"];//"D00000001";	
	if ($doctorid=="") $doctorid = $_GET["doctorid"];
	
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
		global $doctorid;
		$objRs = new recordset(nul,$objConnection);
		if($_SESSION["groupid"] == "APPROVER" ) {
			$objRs->queryopen("select NAME from u_premploymentinfo");
		} else {
			$objRs->queryopen("select NAME from u_premploymentinfo where CODE='".$doctorid."'");
		}
		if ($objRs->queryfetchrow("NAME")) {
			echo $objRs->fields["NAME"] . " - " ;
		}
	}
	
	function onGetCalendarScrollQueryString() {
		global $doctorid;
		return "&doctorid=" . $doctorid;
	}

	/*
	function onDrawCalendarToolbar() {
		global $objConnection;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select CODE, NAME from u_hisdoctors");
		
		echo "<td align=\"center\">";
		echo "<select name=\"doctorid\">\n";
		while ($objRs->queryfetchrow("NAME")) {
			echo "	<option value=\"".$objRs->fields["CODE"]."\"$sel>".$objRs->fields["NAME"]."</option>\n";
		}
		echo "</select>\n\n";
		echo "</td>";
	}
	*/
	function onDrawCalendarFilter() {
		global $objConnection;
		global $doctorid;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select CODE, NAME from u_premploymentinfo WHERE code ='".$_SESSION["userid"]."'");
		
		echo "<select id=\"doctorid\" name=\"doctorid\">\n";
		//echo "	<option value=\"".""."\">"."[Select]"."</option>\n";		
		while ($objRs->queryfetchrow("NAME")) {
			$selected="";
			if ($doctorid==$objRs->fields["CODE"]) $selected="selected";
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
		global $doctorid;
		
		$objRs = new recordset(nul,$objConnection);

		$eventdata = null;

		//$objRs->setdebug();
		$objRs->queryopen("SELECT x.u_days,x.code,x.time1,x.date_now,x.u_empid,x.status_now,x.timeto,x.timefrom,x.sort
							FROM (SELECT *
									FROM (SELECT x.u_days,
			 							 x.xsort as code,
										 'Request' as time1,
										 x.date_now,
										 '$doctorid' as u_empid,
										 '' as status_now,
										 'Form' as timefrom,
										 x.request_type as timeto,
										 x.date_now as sort

								FROM (SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Leave' as Request_type,
										 2 as xsort
									
									FROM u_tkrequestformleave a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
									  AND c1.branch = c.branch
									  AND c1.code = c.code
									  AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
									  AND a.u_leavestatus = 'Successful'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'O.T.' as Request_type,
										 1 as xsort
									
									FROM u_tkrequestformot a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tktimemasterfiles t1 ON t1.company = a.company
									  AND t1.branch = a.branch
									  AND t1.code = a.u_otfromtime
									LEFT JOIN u_tktimemasterfiles t2 ON t2.company = a.company
									  AND t2.branch = a.branch
									  AND t2.code = a.u_ottotime
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'O.B' as Request_type,
										 5 as xsort
									
									FROM u_tkrequestformass a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tktimemasterfiles t1 ON t1.company = a.company
									  AND t1.branch = a.branch
									  AND t1.code = a.u_assfromtime
									LEFT JOIN u_tktimemasterfiles t2 ON t2.company = a.company
									  AND t2.branch = a.branch
									  AND t2.code = a.u_asstotime
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Loan' as Request_type,
										 3 as sort
									
									FROM u_tkrequestformloan a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
									  AND c1.branch = c.branch
									  AND c1.code = c.code
									  AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Time Adj.' as Request_type,
										 4 as sort

									FROM u_tkrequestformtimeadjustment a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
									  AND a.u_tastatus = 'Successful'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Off-Set' as Request_type,
										 6 as xsort
									
									FROM u_tkrequestformoffset a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
								 UNION ALL
								    SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Off-Set Hrs' as Request_type,
										 7 as xsort
									
									FROM u_tkrequestformoffsethrs a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'OB-Hrs' as Request_type,
										 6 as xsort
									
									FROM u_tkrequestformobset a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE a.u_empid = '$doctorid') as x 
									GROUP BY x.date_now,x.request_type) as rr
								UNION ALL
									SELECT a.u_days,
										   a.u_scheduleassign as code,
										   if(a.u_scheduleassign = 'RESTDAY','Restday',CONCAT(t1.name,' - ',t2.name)) as time1,
										   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) as date_now,
										   a.u_empid,
										   'X' as status_now,
										   IFNULL(t1.name,'Rest') as timefrom,
										   if(a.u_scheduleassign = 'RESTDAY','Restday','Schedule') as timeto,
										   0 as sort
									FROM u_tkschedulecolumntorow a
									INNER JOIN u_premploymentdeployment aa ON aa.company = a.company
									  AND aa.branch = a.branch
									  AND aa.code = a.u_empid
									  AND aa.u_branch = a.u_profitcenter
									LEFT OUTER JOIN u_tkschedulemasterfiles b ON b.company = a.company
									  AND a.branch = b.branch
									  AND a.u_scheduleassign = b.code
									LEFT OUTER JOIN u_tktimemasterfiles t1 ON t1.company = b.company
									  AND t1.branch = b.branch
									  AND t1.code = b.u_fromtime
									LEFT OUTER JOIN u_tktimemasterfiles t2 ON t2.company = b.company
									  AND t2.branch = b.branch
									  AND t2.code = b.u_totime
									WHERE a.u_empid = '$doctorid'
									  AND a.u_scheduleassign NOT IN('','Leave')
								UNION ALL
									SELECT day(u_dates) as DAY,
										   '', 
										   u_holidaydesc as descs,
										   u_dates,
										   '',
										   'H',
										   IF(u_tagging = 'S','Special','Legal'),
										   IF(u_tagging = 'S','Holiday S','Holiday L'),
										   1 as sort
										   
									from u_tkholidaylist
								UNION ALL
									SELECT *
									FROM (SELECT x.u_days,
			 							 x.xsort as code,
										 x.request_type as time1,
										 x.date_now,
										 '$doctorid' as u_empid,
										 CONCAT('For Approval <br>&nbsp;&nbsp;',x.request_type) as status_now,
										 'Form' as timefrom,
										 'Approver' as timeto,
										 x.date_now as sort

									FROM (SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Leave' as Request_type,
										 2 as xsort
									
									FROM u_tkrequestformleave a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
									  AND c1.branch = c.branch
									  AND c1.code = c.code
									  AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
									  AND a.u_leavestatus = 'Successful'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Over Time' as Request_type,
										 1 as xsort
									
									FROM u_tkrequestformot a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tktimemasterfiles t1 ON t1.company = a.company
									  AND t1.branch = a.branch
									  AND t1.code = a.u_otfromtime
									LEFT JOIN u_tktimemasterfiles t2 ON t2.company = a.company
									  AND t2.branch = a.branch
									  AND t2.code = a.u_ottotime
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'O.B' as Request_type,
										 5 as xsort
									
									FROM u_tkrequestformass a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									LEFT JOIN u_tktimemasterfiles t1 ON t1.company = a.company
									  AND t1.branch = a.branch
									  AND t1.code = a.u_assfromtime
									LEFT JOIN u_tktimemasterfiles t2 ON t2.company = a.company
									  AND t2.branch = a.branch
									  AND t2.code = a.u_asstotime
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Loan' as Request_type,
										 3 as sort
									
									FROM u_tkrequestformloan a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
									AND c1.branch = c.branch
									AND c1.code = c.code
									AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Time Adj.' as Request_type,
										 4 as sort

									FROM u_tkrequestformtimeadjustment a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
									  AND a.u_tastatus = 'Successful'
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Off-Set' as Request_type,
										 6 as xsort
									
									FROM u_tkrequestformoffset a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
								 UNION ALL
								    SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'Off-Set Hrs' as Request_type,
										 7 as xsort
									
									FROM u_tkrequestformoffsethrs a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.u_appdate as date_now,
										 'OB-Hrs' as Request_type,
										 8 as xsort
									
									FROM u_tkrequestformobset a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
										AND c1.branch = c.branch
										AND c1.code = c.code
										AND c1.u_stageno = IFNULL(x.counters,0)+1
									WHERE c1.u_stagename = '$doctorid'
									  AND a.u_status = 0) as x 
									GROUP BY x.date_now,x.request_type) as zz) as x
							WHERE month(x.date_now)=$month AND year(date_now)=$year
							ORDER BY x.date_now,x.sort");
		while ($objRs->queryfetchrow("NAME")) {
			$day = intval(date('d',strtotime($objRs->fields["date_now"])));
			$sort = $objRs->fields["sort"];
			$eventdata[$day]["id"][] = $objRs->fields["code"];
	
			# set title string; limit char length and append ellipsis if necessary
			/*if($objRs->fields["timeto"] == "Time Adj") {
				$title = "&nbsp;<b><font color='#FFA500'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Loan") {
				$title = "&nbsp;<b><font color='#FF0000'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "O.T.") {
				$title = "&nbsp;<b><font color='#0000A0'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Leave") {
				$title = "&nbsp;<b><font color='#800517'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Restday") {
				$title = "&nbsp;<b><font color='#00FF00'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Days") {
				$title = "&nbsp;<b><font color='#00FF00'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else {
				$title = "&nbsp;<b><font color='#000000'>".stripslashes($objRs->fields["time1"])."</font></b>";	
			}*/
			$title = "&nbsp;<b><font color='#000000'>".stripslashes($objRs->fields["time1"])."</font></b>";
			
			$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title; 
			
			if($objRs->fields["status_now"] == "X") {
				//$timestr = "<div align=\"center\" class=\"time_str\">".$objRs->fields["timefrom"]."<br>".$objRs->fields["timeto"]."</div>\n";
				$timestr = "<div align=\"center\" class=\"time_str\">".$objRs->fields["timeto"]."</div>\n";
			} else {
				//$timestr = "<div align=\"center\" class=\"time_str\">".$objRs->fields["timefrom"]."<br>".$objRs->fields["timeto"]."</div>\n";	
				$timestr = "<div align=\"center\" class=\"time_str\">".$objRs->fields["timeto"]."</div>\n";
			}
			
			//$eventdata[$day]["color"][] = "#000000";
			
			$eventdata[$day]["timestr"][] = $timestr;

			$eventdata[$day]["onclick"][] = "OpenPopupu_TKOpenRequests('".$objRs->fields["timeto"]."','".$objRs->fields["code"]."','".$objRs->fields["sort"]."','".$objRs->fields["status_now"]."')";
			
			
		}
				
		return $eventdata;
	}

	define("TITLE_CHAR_LIMIT", 80);		
	define("CELL_WIDTH", 200);		
	define("COLOR_EMPTY", "#808080");	
	define("CELL_HEIGHT", 50);	
	
	$objGridRequestList = new grid("T10");
	$objGridRequestList->addcolumn("req_docno");
	$objGridRequestList->addcolumn("req_appdate");
	$objGridRequestList->addcolumn("req_date");
	$objGridRequestList->addcolumn("req_status");
	$objGridRequestList->addcolumn("req_approved");
	$objGridRequestList->addcolumn("req_remarks");
	
	$objGridRequestList->columntitle("req_docno","Request No.");
	$objGridRequestList->columntitle("req_appdate","Application Date");
	$objGridRequestList->columntitle("req_date","Request Date");
	$objGridRequestList->columntitle("req_remarks","Request Remarks");
	$objGridRequestList->columntitle("req_status","Status");
	$objGridRequestList->columntitle("req_approved","Next Approver");
	
	$objGridRequestList->columnwidth("req_docno",12);
	$objGridRequestList->columnwidth("req_appdate",12);
	$objGridRequestList->columnwidth("req_date",12);
	$objGridRequestList->columnwidth("req_remarks",50);
	$objGridRequestList->columnwidth("req_status",10);
	$objGridRequestList->columnwidth("req_approved",20);

	$objGridRequestList->automanagecolumnwidth = false;
	
	$objGridRequestList->width = 830;
	$objGridRequestList->height = 200;
	
	include_once("./calendar.php");
	
?>
<script>
	var sf_keys;
	function OpenPopupu_TKOpenRequests(types,keys,sortz,codes) {
		sf_keys = keys;
		if(types == "Time Adj.") {
			showRequestLists(types,sortz);
		} else if(types == "Loan") {
			showRequestLists(types,sortz);
		} else if(types == "O.T.") {
			showRequestLists(types,sortz);
		} else if(types == "Leave") {
			showRequestLists(types,sortz);
		} else if(types == "Off-Set") {
			showRequestLists(types,sortz);
		} else if(types == "O.B") {
			showRequestLists(types,sortz);
		} else if(types == "Off-Set Hrs") {
			showRequestLists(types,sortz);
		} else if(types == "OB-Hrs") {
			showRequestLists(types,sortz);
		} else if(types == "Approver") {
			var checklockec = page.executeFormattedSearch("SELECT b.u_approvedlocked FROM u_prpayrollperioddates b WHERE b.company = '"+getGlobal("company")+"' AND b.branch = '"+getGlobal("branch")+"' AND '"+sortz+"' BETWEEN b.u_from_date AND b.u_to_date AND RIGHT(b.code,2) = 'SM'");
			if(checklockec == "LOCK") {
					page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
					return false;
			} else if(checklockec == "") {
					page.statusbar.showError("No Payroll Period Maintain For this Date.. Please Contact the Adminitrator or H.R.");		
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
				result = page.executeFormattedQuery("SELECT code,request_type FROM (SELECT code,'O.B' as request_type FROM u_tkrequestformass UNION ALL SELECT code,'Leave' as request_type FROM u_tkrequestformleave UNION ALL SELECT code,'Loan' as request_type FROM u_tkrequestformloan UNION ALL SELECT code,'Off-Set' as request_type FROM u_tkrequestformoffset UNION ALL SELECT code,'Off-Set Hrs' as request_type FROM u_tkrequestformoffsethrs UNION ALL SELECT code,'O.T' as request_type FROM u_tkrequestformot UNION ALL SELECT code,'Time Adj' as request_type FROM u_tkrequestformtimeadjustment UNION ALL SELECT code,'OB-Hrs' as request_no FROM u_tkrequestformobset) as x WHERE code = '"+getTableInput(p_tableId,"req_docno",p_rowIdx)+"'");
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
	
	function showRequestLists(type_request,daysdate){
		var result, data = new Array();
		clearTable("T10",true);
		showPopupFrame("popupFrameDetail");
		if (type_request == 'O.T.' ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_otdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Date : ',DATE_FORMAT(a.u_otdate,'%m/%d/%Y'),' OT Time From',a.u_otfromtime,' & ',a.u_ottotime, ' Reason : ',a.u_otreason) as 'remarks' FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory WHERE u_status = 'A' GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_otdate,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,a.u_otdate,a.u_appdate)");
			
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'), ' Reason : ',a.u_leavereason) as 'remarks' FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND a.u_leavestatus = 'Successful' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)"); 
		
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_loanfrom as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Loan Dates : ',DATE_FORMAT(a.u_loanfrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_loanto,'%m/%d/%y'), ' Reason : ',a.u_loanreason) as 'remarks' FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_loanfrom,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,a.u_loanfrom,a.u_appdate)");
		
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Time Adjustment For Incorrect/Missing Time In/Out Reason : ',a.u_tareason) as 'remarks' FROM u_tkrequestformtimeadjustment a INNER JOIN u_tkrequestformtimeadjustmentitems aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_tastatus = 'Successful' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date,a.u_appdate)");
		
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_tkdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y'), ' Reason : ',a.u_assreason) as 'remarks' FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_tkdate,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,a.u_tkdate,a.u_appdate)");
		
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Total Off-set Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks' FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,a.u_offdate as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Work Hrs :',ROUND(a.u_offhrs,2),' Off-set Dates : ',DATE_FORMAT(a.u_offdate,'%m/%d/%y'), ' Reason : ',a.u_offreason) as 'remarks' FROM u_tkrequestformoffsethrs a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,a.u_offdate,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,a.u_offdate,a.u_appdate)");
		
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
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate as appdate,aa.u_date_filter as u_appdate,IF(a.u_status = 1,'Approved',IF(a.u_status = 2,'Denied',IF(a.u_status = -1,'Cancelled','Pending'))) as vstatus,cc.name as 'approver',CONCAT('Total OB Hrs :',SUM(aa.u_hours), ' Reason : ',a.u_assreason) as 'remarks' FROM u_tkrequestformobset a INNER JOIN u_tkrequestformobsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 LEFT JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN u_premploymentinfo cc ON cc.company = a.company AND cc.branch = a.branch AND cc.code = c1.u_stagename WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_empid = '"+getGlobal("userid")+"' AND IF(a.u_status = 1,aa.u_date_filter,a.u_appdate) = '"+daysdate+"' GROUP BY a.code,IF(a.u_status = 1,aa.u_date_filter,a.u_appdate)");
		
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
	
	
	
	function onDateClick(date,day,month,year) {
		//var doctorid=document.getElementById('doctorid').value;
		var doctorid=getVar('doctorid');
		//OpenPopup(500,120,'./UDP.php?objectcode=u_tkeditbiometrix&empid='+doctorid+'&dates='+date,'');
	}
			parent.document.getElementById('iframeBody').style.overflow = "auto";

</script>
<?php	
//	$htmltoolbarbottom = "";
?>
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
