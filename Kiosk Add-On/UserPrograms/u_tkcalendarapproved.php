<?php

	$paging = "udp.php?objectcode=".$progid;

	$doctorid = $_SESSION["userid"];//"D00000001";	
	if ($doctorid=="") $doctorid = $_GET["doctorid"];
	
	function onDrawCalendarHeader() {
		global $objConnection;
		global $doctorid;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select NAME from u_premploymentinfo where CODE='".$doctorid."'");
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
							FROM (SELECT r.u_days,
								   r.code,
								   CONCAT(zz.fieldvaluedesc,'<br>&nbsp;&nbsp;',b.u_lname) as time1,
								   r.date_now,
								   r.u_empid,
								   'H' as status_now,
								   r.timefrom,
								   r.timeto,
								   r.sort
							
							FROM u_tkapprovalform x
							INNER JOIN u_tkapprovalformlist z ON z.company = x.company
							  AND z.branch = x.branch
							  AND z.code = x.code
							INNER JOIN u_premploymentinfo b ON b.company = z.company
							  AND b.branch = z.branch
							  AND b.code = z.u_empid
							INNER JOIN udfvalues zz ON zz.fieldvalue = x.u_typeofrequest
							  AND zz.tablename = 'u_tkapprovalform'
								AND zz.fieldname = 'typeofrequest'
							INNER JOIN (SELECT a.u_days,
											   b.code,
											   IF(b.u_status = 0,'Request is <br>&nbsp;&nbsp;Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Approved by <br>&nbsp;&nbsp;',q.u_fname),CONCAT('Approved <br>',w.name))) as time1,
											   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) as date_now,
											   a.u_empid,
											  IF(b.u_status = 0,'Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Pending to ',z.u_stagename),'Approved')) as status_now,
												'Done' as timefrom,
								  				'Approver' as timeto,
												2 as sort
										FROM u_tkschedulecolumntorow a
										INNER JOIN u_tkrequestformleave b ON b.company = a.company
										AND b.branch = a.branch
										AND b.u_empid = a.u_empid
										AND CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) BETWEEN b.u_leavefrom AND b.u_leaveto
										LEFT OUTER JOIN u_tkapproverfileassigned z ON z.company = b.company
										AND z.branch = b.branch
										AND z.u_stageno = b.u_status
										LEFT OUTER JOIN u_premploymentinfo q ON q.code = z.u_stagename
										LEFT OUTER JOIN u_tkleavemasterfiles w ON w.code = b.u_leavetype
			
										UNION ALL
										SELECT a.u_days,
											   c.code,
											   IF(c.u_status = 0,'Request is <br>&nbsp;&nbsp;Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Approved by <br>&nbsp;&nbsp;',q.u_fname),CONCAT('O.T <br>&nbsp;&nbsp; Schedule'))) as time1,
											   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)),
											   a.u_empid,
											  IF(c.u_status = 0,'Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Pending to ',z.u_stagename),'Approved')),
												t1.name as timefrom,
												t2.name as timeto,
												2 as sort
										FROM u_tkschedulecolumntorow a
										INNER JOIN u_tkrequestformot c ON c.company = a.company
										  AND c.branch = a.branch
										  AND c.u_empid = a.u_empid
										  AND CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) = c.u_otdate
										LEFT OUTER JOIN u_tkapproverfileassigned z ON z.company = c.company
										  AND z.branch = c.branch
										  AND z.u_stageno = c.u_status
										LEFT OUTER JOIN u_tktimemasterfiles t1 ON t1.company = c.company
										  AND t1.branch = c.branch
										  AND t1.code = c.u_otfromtime
										LEFT OUTER JOIN u_tktimemasterfiles t2 ON t2.company = c.company
										  AND t2.branch = c.branch
										  AND t2.code = c.u_ottotime
										LEFT OUTER JOIN u_premploymentinfo q ON q.code = z.u_stagename
			
										UNION ALL
										SELECT a.u_days,
											   c.code,
											   IF(c.u_status = 0,'Request is <br>&nbsp;&nbsp;Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Approved by <br>&nbsp;&nbsp;',q.u_fname),CONCAT('O.B <br>&nbsp;&nbsp; Schedule'))) as time1,
											   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)),
											   a.u_empid,
											  IF(c.u_status = 0,'Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Pending to ',z.u_stagename),'Approved')),
												t1.name as timefrom,
												t2.name as timeto,
												2 as sort
										FROM u_tkschedulecolumntorow a
										INNER JOIN u_tkrequestformass c ON c.company = a.company
										  AND c.branch = a.branch
										  AND c.u_empid = a.u_empid
										  AND CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) = c.u_tkdate
										LEFT OUTER JOIN u_tkapproverfileassigned z ON z.company = c.company
										  AND z.branch = c.branch
										  AND z.u_stageno = c.u_status
										LEFT OUTER JOIN u_tktimemasterfiles t1 ON t1.company = c.company
										  AND t1.branch = c.branch
										  AND t1.code = c.u_assfromtime
										LEFT OUTER JOIN u_tktimemasterfiles t2 ON t2.company = c.company
										  AND t2.branch = c.branch
										  AND t2.code = c.u_asstotime
										LEFT OUTER JOIN u_premploymentinfo q ON q.code = z.u_stagename
			
										UNION ALL
										SELECT a.u_days,
											   d.code,
											   IF(d.u_status = 0,'Request is <br>&nbsp;&nbsp;Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Approved by <br>&nbsp;&nbsp;',q.u_fname),'Approved')) as time1,
											   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)),
											   a.u_empid,
											  IF(d.u_status = 0,'Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Pending to ',z.u_stagename),'Approved')),
												'Done' as timefrom,
								  				'Approver' as timeto,
												2 as sort
										FROM u_tkschedulecolumntorow a
										INNER JOIN u_tkrequestformloan d ON d.company = a.company
										AND d.branch = a.branch
										AND d.u_empid = a.u_empid
										AND CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) BETWEEN d.u_loanfrom AND d.u_loanto
										LEFT OUTER JOIN u_tkapproverfileassigned z ON z.company = d.company
										AND z.branch = d.branch
										AND z.u_stageno = d.u_status
										LEFT OUTER JOIN u_premploymentinfo q ON q.code = z.u_stagename
			
										UNION ALL
										SELECT a.u_days,
											   e.code,
											   IF(e.u_status = 0,'Request is <br>&nbsp;&nbsp;Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Approved by <br>&nbsp;&nbsp;',q.u_fname),'Approved')) as time1,
											   CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)),
											   a.u_empid,
											  IF(e.u_status = 0,'Cancelled /Denied',IF(z.u_stagename is not null,CONCAT('Pending to ',z.u_stagename),'Approved')),
												'Done' as timefrom,
								  				'Approver' as timeto,
												2 as sort
										FROM u_tkschedulecolumntorow a
										INNER JOIN u_tkrequestformtimeadjustment e ON e.company = a.company
										AND e.branch = a.branch
										AND e.u_empid = a.u_empid
										AND CONCAT(a.u_year,'-',a.u_month,'-',IF(LENGTH(a.u_days) = 1,CONCAT('0',a.u_days),a.u_days)) = e.u_appdate
										LEFT OUTER JOIN u_tkapproverfileassigned z ON z.company = e.company
										AND z.branch = e.branch
										AND z.u_stageno = e.u_status
										LEFT OUTER JOIN u_premploymentinfo q ON q.code = z.u_stagename) as r ON r.code = z.u_codes AND r.u_empid = z.u_empid
							WHERE x.u_approverby = 1
							  AND b.u_employstatus2 = '$doctorid') as x
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

			$eventdata[$day]["onclick"][] = "OpenPopupu_TKOpenRequests('".$objRs->fields["timeto"]."','".$objRs->fields["code"]."','".$objRs->fields["sort"]."')";
			
			
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
	function OpenPopupu_TKOpenRequests(types,keys,sortz) {
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
			OpenLnkBtn(1200,600,'./UDO.php?objectcode=u_tkapprovalform' + '' + '&dates='+keys+'&rtypes='+sortz,'');
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
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
