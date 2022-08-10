<?php

	$paging = "udp.php?objectcode=".$progid;

	$doctorid = $_SESSION["userid"];//"D00000001";	
	if ($doctorid=="") $doctorid = $_GET["doctorid"];
	
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
							FROM (SELECT DAY(a.u_appdate) as u_days,
										 a.code,
										 'Request' as time1,
										 a.u_appdate as date_now,
										 a.u_empid,
										 'List' as status_now,
										 'Requested' as timefrom,
										 'Leave' as timeto,
										 2 as sort
									
									FROM u_tkrequestformleave a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters 
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									WHERE a.u_empid = '$doctorid'
									  AND a.u_leavestatus = 'Successful'
									GROUP BY a.u_appdate
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.code,
										 'Request' as time1,
										 a.u_appdate as date_now,
										 a.u_empid,
										 'List' as status_now,
										 'Requested' as timefrom,
										 'O.T.' as timeto,
										 2 as sort
									
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
									WHERE a.u_empid = '$doctorid'
									GROUP BY a.u_appdate
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.code,
										 'Request' as time1,
										 a.u_appdate as date_now,
										 a.u_empid,
										 'List' as status_now,
										 'Requested' as timefrom,
										 'O.B.' as timeto,
										 2 as sort
									
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
									WHERE a.u_empid = '$doctorid'
									GROUP BY a.u_appdate
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.code,
										 'Request' as time1,
										 a.u_appdate as date_now,
										 a.u_empid,
										 'List' as status_now,
										 'Requested' as timefrom,
										 'Off-set.' as timeto,
										 2 as sort
									
									FROM u_tkrequestformoffset a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									WHERE a.u_empid = '$doctorid'
									GROUP BY a.u_appdate
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.code,
										 'Request' as time1,
										 a.u_appdate as date_now,
										 a.u_empid,
										 'List' as status_now,
										 'Requested' as timefrom,
										 'Loan' as timeto,
										 2 as sort
									
									FROM u_tkrequestformloan a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									WHERE a.u_empid = '$doctorid'
									GROUP BY a.u_appdate
								UNION ALL
									SELECT DAY(a.u_appdate) as u_days,
										 a.code,
										 'Request' as time1,
										 a.u_appdate as date_now,
										 a.u_empid,
										 'List' as status_now,
										 'Requested' as timefrom,
										 'Time Adj' as timeto,
										 2 as sort

									FROM u_tkrequestformtimeadjustment a
									INNER JOIN u_premploymentinfo b ON b.company = a.company
									  AND b.branch = a.branch
									  AND b.code = a.u_empid
									LEFT JOIN (SELECT u_requestno,COUNT(code) as counters
													FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code
									INNER JOIN u_tkapproverfile c ON c.company = b.company
									  AND c.branch = b.branch
									  AND c.code = b.u_employstatus2
									WHERE a.u_empid = '$doctorid'
									  AND a.u_tastatus = 'Successful'
									GROUP BY a.u_appdate
								UNION ALL
									SELECT a.u_days,
										   a.u_scheduleassign as code,
										   CONCAT(b.name,'<br>&nbsp;&nbsp; Schedule') as time1,
										   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) as date_now,
										   a.u_empid,
										   'X' as status_now,
										   IFNULL(t1.name,'Rest') as timefrom,
										   IFNULL(t2.name,'Day') as timeto,
										   0 as sort
									FROM u_tkschedulecolumntorow a
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
									SELECT day(U_DATES) as DAY,
										   '', 
										   CONCAT('Holiday <br>&nbsp;&nbsp;',U_HOLIDAYDESC) as descs,
										   u_dates,
										   '',
										   'H',
										   IF(u_tagging = 'L','Special','Legal'),
										   'Holiday',
										   1 as sort
										   
									from u_tkholidaylist
								UNION ALL
									SELECT *
									FROM (SELECT x.u_days,
			 							x.xsort as code,
										 CONCAT('For Approval <br>&nbsp;&nbsp;',x.request_type) as time1,
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
									  AND a.u_status = 0) as x 
									GROUP BY x.date_now,x.request_type) as zz) as x
							WHERE month(x.date_now)=$month AND year(date_now)=$year
							ORDER BY x.date_now,x.sort");
		while ($objRs->queryfetchrow("NAME")) {
			$day = intval(date('d',strtotime($objRs->fields["date_now"])));
			$sort = $objRs->fields["sort"];
			$eventdata[$day]["id"][] = $objRs->fields["code"];
	
			# set title string; limit char length and append ellipsis if necessary
			if($objRs->fields["timeto"] == "Time Adj") {
				$title = "&nbsp;<b><font color='#FFA500'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Loan") {
				$title = "&nbsp;<b><font color='#FF0000'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "O.T.") {
				$title = "&nbsp;<b><font color='#0000A0'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Leave") {
				$title = "&nbsp;<b><font color='#800517'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Day") {
				$title = "&nbsp;<b><font color='#00FF00'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else if($objRs->fields["timeto"] == "Days") {
				$title = "&nbsp;<b><font color='#00FF00'>".stripslashes($objRs->fields["time1"])."</font></b>";
			} else {
				$title = "&nbsp;<b><font color='#000000'>".stripslashes($objRs->fields["time1"])."</font></b>";	
			}
			//$title = "&nbsp;<b><font color='#FF0000'>".stripslashes($objRs->fields["time1"])."</font></b>";
			
			$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title; 
			
			if($objRs->fields["status_now"] == "X") {
				$timestr = "<div align=\"center\" class=\"time_str\">".$objRs->fields["timefrom"]."<br>".$objRs->fields["timeto"]."</div>\n";
			} else {
				$timestr = "<div align=\"center\" class=\"time_str\">".$objRs->fields["timefrom"]."<br>".$objRs->fields["timeto"]."</div>\n";	
			}
			
			//$eventdata[$day]["color"][] = "#000000";
			
			$eventdata[$day]["timestr"][] = $timestr;

			$eventdata[$day]["onclick"][] = "OpenPopupu_TKOpenRequests('".$objRs->fields["timeto"]."','".$objRs->fields["code"]."','".$objRs->fields["sort"]."','".$objRs->fields["status_now"]."')";
			
			
		}
				
		return $eventdata;
	}

	define("TITLE_CHAR_LIMIT", 80);		
	define("CELL_WIDTH", 150);		
	define("COLOR_EMPTY", "#808080");	
	define("CELL_HEIGHT", 70);		
	
	include_once("./calendar.php");
	
?>
<script>
	var sf_keys;
	function OpenPopupu_TKOpenRequests(types,keys,sortz,codes) {
		sf_keys = keys;
		if(types == "Time Adj") {
			OpenLnkBtn(900,440,'./UDO.php?objectcode=u_tkrequestformtimeadjustment' + '&opt=viewonly' + '&formAction=e&sf_keys='+keys,'');
		} else if(types == "Loan") {
			OpenLnkBtn(900,340,'./UDO.php?objectcode=u_tkrequestformloan' + '&opt=viewonly' + '&formAction=e&sf_keys='+keys,'');
		} else if(types == "O.T.") {
			OpenLnkBtn(900,340,'./UDO.php?objectcode=u_tkrequestformot' + '&opt=viewonly' + '&formAction=e&sf_keys='+keys,'');
		} else if(types == "Leave") {
			OpenLnkBtn(900,340,'./UDO.php?objectcode=u_tkrequestformleave' + '&opt=viewonly' + '&formAction=e&sf_keys='+keys,'');
		} else if(types == "Approver") {
			formView(null,'./UDP.php?objectcode=u_tkapprovedhistory' + '' + '&dates='+keys+'&rtypes='+sortz);
			//OpenLnkBtn(1200,600,'./UDP.php?objectcode=u_tkapprovedhistory' + '' + '&dates='+keys+'&rtypes='+sortz,'');
		}
		
	}
	
	function onLnkBtnGetParams(Id) {
		var params = new Array();
		params["keys"] = sf_keys;
		return params;
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

<div <?php genPopWinHDivHtml("popupFrameDetail","List of Request",240,130,1000,false) ?>>
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
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
