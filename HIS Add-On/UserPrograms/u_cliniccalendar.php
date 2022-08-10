<?php

	$paging = "udp.php?objectcode=".$progid;

	$doctorid = $_POST["doctorid"];//"D00000001";	
	if ($doctorid=="") $doctorid = $_GET["doctorid"];
	
	function onDrawCalendarHeader() {
		global $objConnection;
		global $doctorid;
		$objRs = new recordset(nul,$objConnection);
		$objRs->queryopen("select NAME from u_hisdoctors where CODE='".$doctorid."'");
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
		$objRs->queryopen("select CODE, NAME from u_hisdoctors");
		
		echo "<select id=\"doctorid\" name=\"doctorid\">\n";
		echo "	<option value=\"".""."\">"."[Select]"."</option>\n";		
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

		$objRs->queryopen("select day(HOLIDAYDATE) as DAY, HOLIDAYDESC from holidays where month(HOLIDAYDATE)=$month and year(HOLIDAYDATE)=$year");
		while ($objRs->queryfetchrow("NAME")) {
			$day = $objRs->fields["DAY"];
			$eventdata[$day]["id"][] = 0;
	
			# set title string; limit char length and append ellipsis if necessary
			$title = stripslashes($objRs->fields["HOLIDAYDESC"]);
			$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title; 
			
			$timestr = "";
			# set time string
			/*if (!($row["start_time"] == "55:55:55" 
				&& $row["end_time"] == "55:55:55")) {
				$starttime 
					= ($row["start_time"] == "55:55:55") ? "- -" : $row["stime"];
				$endtime 
					= ($row["end_time"] == "55:55:55") ? "- -" : $row["etime"];
				
				$timestr = "
				<div align=\"right\" class=\"time_str\">
				($starttime - $endtime)&nbsp;</div>\n";
			} else {
				$timestr = "<br />";
			}*/
			$eventdata[$day]["color"][] = "#FF0000";
			
			$eventdata[$day]["timestr"][] = $timestr;
			
			//$eventdata[$day]["onclick"][] = "OpenPopupu_LeaveApplications('".$objRs->fields["DOCNO"]."')";
		}
		//$objRs->setdebug();
		$objRs->queryopen("select DOCID, DOCNO, U_STARTDATE, U_STARTTIME, U_ENDDATE, U_ENDTIME, U_PATIENTNAME from u_hisconsultancyrequests where u_doctorid='$doctorid' and docstatus='O' and month(u_startdate)=$month and year(u_startdate)=$year  ORDER BY U_STARTDATE, U_STARTTIME, U_ENDDATE, U_ENDTIME");
		while ($objRs->queryfetchrow("NAME")) {
			$day = intval(date('d',strtotime($objRs->fields["U_STARTDATE"])));
			$eventdata[$day]["id"][] = $objRs->fields["DOCID"];
	
			# set title string; limit char length and append ellipsis if necessary
			//iif($objRs->fields["U_DISPOSITION"]=="Pending","<u><i><b>Pending</b></i></u> ","") 
			$title = stripslashes($objRs->fields["U_PATIENTNAME"]);
			$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title; 
			$timestr = "<div align=\"right\" class=\"time_str\">".substr($objRs->fields["U_STARTTIME"], 0, 5)."<br/>".substr($objRs->fields["U_ENDTIME"], 0, 5)."</div>\n";
			# set time string
			/*
			if ($objRs->fields["U_DETAILISHOLIDAY"]==1) {
				$eventdata[$day]["color"][] = "#FF0000";
				
			} else*/
			//$eventdata[$day]["color"][] = "#FF0000";
			$eventdata[$day]["color"][] = "#000000";

			$eventdata[$day]["timestr"][] = $timestr;
			
			$eventdata[$day]["onclick"][] = "OpenPopupu_HISConsultancyRequests('".$objRs->fields["DOCNO"]."')";
		}
		/*
		$objRs->queryopen("select d.NAME, a.DOCNO,b.LINEID, day(b.U_DETAILDATE) as DAY, concat(a.U_LEAVETYPE,' - ',a.U_REASON) as REMARKS, b.U_DETAILTYPEFDL, b.U_DETAILTYPEAML, b.U_DETAILTYPEPML, b.U_DETAILISHOLIDAY, a.U_DISPOSITION from u_leaveapplications a, u_leaveapplicationdays b, u_employees c, u_employees d where d.code = a.u_empno and c.code=a.u_approvingofficer and c.u_username='".$_SESSION["userid"]."' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.u_cancelled=0 and a.u_disposition in ('Approved','Pending') and 
				month(b.u_detaildate)=$month and year(b.u_detaildate)=$year and b.U_DETAILISHOLIDAY=0");
		while ($objRs->queryfetchrow("NAME")) {
			$day = $objRs->fields["DAY"];
			$eventdata[$day]["id"][] = $objRs->fields["LINEID"];
	
			# set title string; limit char length and append ellipsis if necessary
			$title = iif($objRs->fields["U_DISPOSITION"]=="Pending","<u><i><b>Pending</b></i></u> ","") . stripslashes($objRs->fields["REMARKS"]);
			if ($objRs->fields["U_DETAILISHOLIDAY"]!=1) $eventdata[$day]["title"][] = "<b>".stripslashes($objRs->fields["NAME"]) . "</b><br>". ((strlen($title) > TITLE_CHAR_LIMIT) ? substr($title, 0, TITLE_CHAR_LIMIT) . '...'	: $title); 
			else $eventdata[$day]["title"][] = "";
			
			if ($objRs->fields["U_DETAILTYPEPML"]==1) {
				$timestr = "
				<div align=\"right\" class=\"time_str\">13:00<br/>17:00</div>\n";
			} elseif ($objRs->fields["U_DETAILTYPEAML"]==1) {
				$timestr = "
				<div align=\"right\" class=\"time_str\">08:00<br/>12:00</div>\n";
			} elseif ($objRs->fields["U_DETAILTYPEFDL"]==1) {
				$timestr = "
				<div align=\"right\" class=\"time_str\">08:00<br/>17:00</div>\n";
			} else $timestr = "";
			# set time string
			if ($objRs->fields["U_DETAILISHOLIDAY"]==1) {
				$eventdata[$day]["color"][] = "#FF0000";
				
			} else $eventdata[$day]["color"][] = "#000000";
				
			
			$eventdata[$day]["timestr"][] = $timestr;
			
			$eventdata[$day]["onclick"][] = "OpenPopupu_LeaveApplications('".$objRs->fields["DOCNO"]."')";
		}
				*/
		return $eventdata;
	}

	define("TITLE_CHAR_LIMIT", 50);		
	define("CELL_WIDTH", 120);		
//	define("COLOR_EMPTY", "#FF0000");		
//	define("CELL_HEIGHT", 80);		
	
	include_once("./calendar.php");
	
?>
<script>
	var sf_keys;
	function OpenPopupu_HISConsultancyRequests(keys) {
		sf_keys = keys;
		//OpenLnkBtn(900,540,'./UDO.php?objectcode=u_LeaveApplications&formAction=e&sf_keys='+keys,'');
		OpenLnkBtn(900,540,'./UDO.php?objectcode=u_HISConsultancyRequests&formAction=e&sf_keys='+keys,'');
	}
	
	function onLnkBtnGetParams(Id) {
		var params = new Array();
		params["keys"] = sf_keys;
		return params;
	}
	
	function onDateClick(date,day,month,year) {
		//var doctorid=document.getElementById('doctorid').value;
		var doctorid=getVar('doctorid');
		OpenPopup(900,540,'./UDO.php?objectcode=u_HISConsultancyRequests&doctorid='+doctorid+'&calendardate='+date,'');
	}
			parent.document.getElementById('iframeBody').style.overflow = "auto";

</script>
<?php	
//	$htmltoolbarbottom = "";
?>
<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>
