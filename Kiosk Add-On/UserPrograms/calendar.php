<?php
define("MAX_TITLES_DISPLAYED", 8);

// Title character limit.  Adjust this value so event
// titles don't take more space than you want them to
// on calendar month-view.
if(!defined('TITLE_CHAR_LIMIT')) define("TITLE_CHAR_LIMIT", 37);

if(!defined('CELL_WIDTH')) define("CELL_WIDTH", 95);
if(!defined('CELL_HEIGHT')) define("CELL_HEIGHT", 80);

if(!defined('COLOR_DAY')) define("COLOR_DAY", "#EDECD8");
if(!defined('COLOR_EMPTY')) define("COLOR_EMPTY", "#EEEEEE");
if(!defined('COLOR_TODAY')) define("COLOR_TODAY", "#FFFF99");

// Template name.  e.g. "default" would indicate that
// the "default.php" file is to be used.
define("TEMPLATE_NAME", "default");

// Allows you to specify the weekstart, or the day
// the calendar starts with.  The value must be
// a numeric value from 0-6.  Zero indicates that the
// weekstart is to be Sunday, 1 indicates that it is
// Monday, 2 Tuesday, 3 Wednesday... For most users
// it will be zero or one.
define("WEEK_START", 0);

// Allows you to specify the format in which time
// values are output.  Currently there are two
// formats available: "12hr", which displays
// hours 1-12 with an am/pm, and "24hr" which
// display hours 00-23 with no am/pm.
define("TIME_DISPLAY_FORMAT", "24hr");

// This directive allows you to specify a number 
// of hours by which the current time will be 
// offset.  The current time is used to highlight
// the present day on the month-view calendar, and 
// it is sometimes necessary to adjust the current 
// time, so that the present day does not roll-over 
// too early, or too late, for your intended 
// audience.  Both positive and negative integer 
// values are valid.
define("CURR_TIME_OFFSET", 0);

include_once("./inc/formutils.php");

if (!isset($paging)) $paging = "calendar.php?";

$lang['months'] = array(
	"January", 		"February",		"March", 		"April", 
	"May", 			"June", 		"July", 		"August", 
	"September", 	"October", 		"November",		"December"
);

$lang['days'] 		= array(
	"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
);

$lang['abrvdays'] 	= array(
	"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"
);

// eventdisplay.php
$lang['otheritems']	   = "Also On This Day:";
$lang['deleteconfirm'] = "Are you sure you want to delete this item?";
$lang['postedby'] 	   = "Posted by";

// calendar.php
$lang['login']    = "Login";
$lang['logout']   = "Logout";
$lang['adminlnk'] = "User Admin";
$lang['changepw'] = "Change Password";

// eventform.php
$lang['cancel'] = "Cancel";

function monthPullDown($month, $montharray)
{
	echo "<select name=\"month\">\n";

	$selected[$month - 1] = ' selected="selected"';

	for($i=0;$i < 12; $i++) {
		$val = $i + 1;
		$sel = (isset($selected[$i])) ? $selected[$i] : "";
		echo "	<option value=\"$val\"$sel>$montharray[$i]</option>\n";
	}
	echo "</select>\n\n";
}

# ###################################################################

function yearPullDown($year)
{
	echo "<select name=\"year\">\n";

	$selected[$year] = ' selected="selected"';
	$years_before_and_after = 3;
	$start_year = $year - $years_before_and_after;
	$end_year   = $year + $years_before_and_after;

	for($i=$start_year;$i <= $end_year; $i++) {
		$sel = (isset($selected[$i])) ? $selected[$i] : "";
		echo "	<option value=\"$i\"$sel>$i</option>\n";
	}
	echo "</select>\n\n";
}

# ###################################################################

function dayPullDown($day)
{
	echo "<select name=\"day\">\n";

	$selected[$day] = ' selected="selected"';

	for($i=1;$i <= 31; $i++) {
		$sel = (isset($selected[$i])) ? $selected[$i] : "";
		echo "	<option value=\"$i\"$sel>$i</option>\n";
	}
	echo "</select>\n\n";
}

# ###################################################################

function hourPullDown($hour, $namepre)
{
	echo "\n<select name=\"" . $namepre . "_hour\">\n";
	
	$selected[$hour] = ' selected="selected"';

	for($i=0;$i <= 12; $i++) {
		$sel = (isset($selected[$i])) ? $selected[$i] : "";
		echo "	<option value=\"$i\"$sel>$i</option>\n";
	}
	echo "</select>\n\n";
}

# ###################################################################

function minPullDown($min, $namepre)
{
	echo "\n<select name=\"" . $namepre . "_min\">\n";
	
	$selected[$min] = ' selected="selected"';

	for($i=0;$i < 60; $i+=5) {
		$disp_min = sprintf("%02d", $i);
		$sel = (isset($selected[$i])) ? $selected[$i] : "";
		echo "\t<option value=\"$i\"$sel>$disp_min</option>\n";
	}

	echo "</select>\n\n";
}

# ###################################################################

function amPmPullDown($pm, $namepre)
{
	$sel = ' selected="selected"';
	$am  = null;
	if ($pm) { $pm = $sel; } else { $am = $sel; }

	echo "\n<select name=\"" . $namepre . "_am_pm\">\n";
	echo "	<option value=\"0\"$am>am</option>\n";
	echo "	<option value=\"1\"$pm>pm</option>\n";
	echo "</select>\n\n";
}

# ###################################################################

function javaScript()
{
	global $paging;
?>
	<script language="javascript">
	function submitMonthYear() {
		document.monthYear.method = "post";
		alert(document.monthYear.month.value);
		document.monthYear.action = 
			"<?php echo $paging ?>&month=" + document.monthYear.month.value + 
			"&year=" + document.monthYear.year.value;
		document.monthYear.submit();
	}
	
	function dateClick(date, day, month, year) {
		try { if (onDateClick) onDateClick(date, day, month, year); }
		catch (theError) {}
	}
	
	function openPosting(pId) {
		eval(
		"page" + pId + " = window.open('eventdisplay.php?id=" + pId + 
		"', 'mssgDisplay', 'toolbar=0,scrollbars=1,location=0,statusbar=0," +
		"menubar=0,resizable=1,width=340,height=400');"
		);
	}
	
	function loginPop(month, year) {
		eval("logpage = window.open('login.php?month=" + month + "&year=" + 
		year + "', 'mssgDisplay', 'toolbar=0,scrollbars=1,location=0," +
		"statusbar=0,menubar=0,resizable=1,width=340,height=400');"
		);
	}
	</script>
<?php
}

# ###################################################################

function footprint($auth, $m, $y) 
{
	global $lang;

	echo "
	<br><br><span class=\"footprint\">
	phpEventGallery <span style=\"color: #666\">by ikemcg at </span> 
	<a href=\"http://www.ikemcg.com/pec\" target=\"new\">
	ikemcg.com</a><br />\n[ ";
	
	if ( $auth == 2 ) {
		echo "
		<a href=\"useradmin.php\">" . $lang['adminlnk'] . "</a> |
		<a href=\"login.php?action=logout&month=$m&year=$y\">" 
		. $lang['logout'] . "</a>";
	} elseif ( $auth == 1 ) {
		echo "
		<a href=\"useradmin.php?flag=changepw\">" . $lang['changepw'] . "</a> |
		<a href=\"login.php?action=logout&month=$m&year=$y\">"
		 . $lang['logout'] . " </a>";
	} else {
		echo "<a href=\"javascript:loginPop($m, $y)\">"
		. $lang['login'] . "</a>";
	}
	echo " ]</span>";
}

# ###################################################################

function scrollArrows($m, $y)
{
	global $paging;
	// set variables for month scrolling
	$nextyear  = ($m != 12) ? $y : $y + 1;
	$prevyear  = ($m != 1)  ? $y : $y - 1;
	$prevmonth = ($m == 1)  ? 12 : $m - 1;
	$nextmonth = ($m == 12) ? 1  : $m + 1;

	if (function_exists('onGetCalendarScrollQueryString')) $querystring = onGetCalendarScrollQueryString();
	
	return "
	<a href=\"".$paging."&month=" . $prevmonth . "&year=" . $prevyear . $querystring . "\">
	<img src=\"imgs/leftArrow.gif\" border=\"0\"></a>
	<a href=\"".$paging."&month=" . $nextmonth . "&year=" . $nextyear . $querystring . "\">
	<img src=\"imgs/rightArrow.gif\" border=\"0\"></a>
	";
}

# ###################################################################

function writeCalendar($month, $year)
{

	$str = getDayNameHeader();
	if (function_exists('onGetCalendarEvents')) $eventdata = onGetCalendarEvents($month, $year);

	if (function_exists('onGetCalendarRestDays')) $restdata = onGetCalendarRestDays($month, $year);
	
	//var_dump($eventdata);

	# get first row position of first day of month.
	$weekpos = getFirstDayOfMonthPosition($month, $year);

	# get user permission level
	$auth = (isset($_SESSION['authdata'])) 
		? $_SESSION['authdata']['userlevel'] 
		: false;

	# get number of days in month
	$days = date("t", mktime(0,0,0,$month,1,$year));

	# initialize day variable to zero, unless $weekpos is zero
	if ($weekpos == 0) $day = 1; else $day = 0;
	
	# initialize today's date variables for color change
	$timestamp = mktime() + CURR_TIME_OFFSET * 3600;
	$d = date('j', $timestamp); 
	$m = date('n', $timestamp); 
	$y = date('Y', $timestamp);

	# lookup for testing whether day is today
	$today["$y-$m-$d"] = 1;

	# loop writes empty cells until it reaches position of 1st day of 
	# month ($wPos).  It writes the days, then fills the last row with empty 
	# cells after last day
	while($day <= $days) {
		$str .="<tr>\n";
		
		# write row
		for($i=0;$i < 7; $i++) {
			# if cell is a day of month
			if($day > 0 && $day <= $days) {
				# set css class today if cell represents current date
				$wday = date("w", mktime(0,0,0,$month,$day,$year));
				$color="#00FF00";
				
				if (isset($eventdata[$day]["color"][0])) $color = $eventdata[$day]["color"][0];
				
				if (isset($restdata[$wday])) $color = $restdata[$wday];

				$class = (isset($today["$year-$month-$day"])) ? 'today' : 'day';
				$bgcolor = (isset($today["$year-$month-$day"])) ? COLOR_TODAY : COLOR_DAY;

				$str .= "
				<td valign=\"top\" style=\"color:".$color."\" bgcolor=\"".$bgcolor."\" width=\"".CELL_WIDTH."\" height=\"".CELL_HEIGHT."\">
				<span class=\"day_number\" bgcolor=\"".$bgcolor."\" width=\"".CELL_WIDTH."\" height=\"".CELL_HEIGHT."\">\n";
				
				//if ($auth) {
					$str .= "
					<a href=\"javascript: dateClick('".formatDateToHttp("$year-$month-$day")."',$day, $month, $year)\">
					$day</a>";
				//} else {
				//	$str .= "$day";
				//}	
				$str .= "</span><br>";
				
				if (isset($eventdata[$day]["title"])) {
					// enforce title limit
					$eventcount = count($eventdata[$day]["title"]);
	
					if (MAX_TITLES_DISPLAYED < $eventcount) {
						$eventcount = MAX_TITLES_DISPLAYED;
					}
					
					// write title link if day's postings 
					for($j=0;$j < $eventcount;$j++) {
						if ($j!=0) $str .= "<div></div>";
						$str .= "<div>"
						. "<table border='0' cellpadding='0' cellspacing='0' width=\"100%\"><tr >";
						if ($eventdata[$day]["timestr"][$j]!="-") {
							$str .= "<td width=\"40\" valign=\"top\" style=\"background-color:".$eventdata[$day]["color"][$j].";border-right: 1px solid gray;border-bottom: 1px solid gray\">".$eventdata[$day]["timestr"][$j]."</td>";
							
							if ($eventdata[$day]["href"][$j]!="") {
								$str .= "<td class=\"title_txt\" style=\"color:".$color."\"><a href=\"".$eventdata[$day]["href"][$j]."\">".$eventdata[$day]["title"][$j]."</a></td>";
							} elseif ($eventdata[$day]["onclick"][$j]!="") {
								$str .= "<td class=\"title_txt\" style=\"color:".$color."\"><a href=\"\" onclick=\"".$eventdata[$day]["onclick"][$j].";return false;\">".$eventdata[$day]["title"][$j]."</a></td>";
							} else {
								$str .= "<td class=\"title_txt\">".$eventdata[$day]["title"][$j]."</td>";
							}	
						} else {
							if ($eventdata[$day]["href"][$j]!="") {
								$str .= "<td class=\"title_txt\" style=\"color:".$color."\"><a href=\"".$eventdata[$day]["href"][$j]."\">".$eventdata[$day]["title"][$j]."</a></td>";
							} elseif ($eventdata[$day]["onclick"][$j]!="") {
								$str .= "<td class=\"title_txt\" style=\"color:".$color."\"><a href=\"\" onclick=\"".$eventdata[$day]["onclick"][$j].";return false;\">".$eventdata[$day]["title"][$j]."</a></td>";
							} else {
								$str .= "<td class=\"title_txt\" style=\"color:".$color."\">".$eventdata[$day]["title"][$j]."</td>";
							}	
						}
						$str .=  "</tr></table>"
						. "</div>";
						//						<span class=\"title_txt\">
						//-<a href=\"javascript:openPosting("
						//. $eventdata[$day]["id"][$j] . ")\">"
						//. $eventdata[$day]["title"][$j] . "</a></span>"
						//. $eventdata[$day]["timestr"][$j];

					}
				}

				$str .= "</td>\n";
				$day++;
			} elseif($day == 0)  {
     			$str .= "
				<td valign=\"top\" bgcolor=\"".COLOR_EMPTY."\" width=\"".CELL_WIDTH."\" height=\"".CELL_HEIGHT."\">&nbsp;</td>\n";
				$weekpos--;
				if ($weekpos == 0) $day++;
     		} else {
				$str .= "
				<td valign=\"top\" bgcolor=\"".COLOR_EMPTY."\" width=\"".CELL_WIDTH."\" height=\"".CELL_HEIGHT."\">&nbsp;</td>\n";
			}
     	}
		$str .= "</tr>\n\n";
	}
	$str .= "</table>\n\n";
	return $str;
}

# ###################################################################

function getDayNameHeader()
{
	global $lang;

	// adjust day name order if weekstart not Sunday
	if (WEEK_START != 0) {
		for($i=0; $i < WEEK_START; $i++) {
			$tempday = array_shift($lang['abrvdays']);
			array_push($lang['abrvdays'], $tempday);
		}
	}

	$s = "<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\">\n<tr>\n";

	foreach($lang['abrvdays'] as $day) {
		$s .= "\t<td class=\"column_header\">&nbsp;$day</td>\n";
	}

	$s .= "</tr>\n\n";
	return $s;
}

# ###################################################################

function getEventDataArray($month, $year)
{
	$eventdata = null;
	mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error());
	
	$sql = "SELECT id, d, title, start_time, end_time, ";
	
	if (TIME_DISPLAY_FORMAT == "12hr") {
		$sql .= "TIME_FORMAT(start_time, '%l:%i%p') AS stime, ";
		$sql .= "TIME_FORMAT(end_time, '%l:%i%p') AS etime ";
	} elseif (TIME_DISPLAY_FORMAT == "24hr") {
		$sql .= "TIME_FORMAT(start_time, '%H:%i') AS stime, ";
		$sql .= "TIME_FORMAT(end_time, '%H:%i') AS etime ";
	} else {
		echo "Bad time display format, check your configuration file.";
	}
	
	$sql .= "
		FROM " . DB_TABLE_PREFIX . "mssgs WHERE m = $month AND y = $year
		ORDER BY start_time";
	
	$result = mysql_query($sql) or die(mysql_error());
	
	while($row = mysql_fetch_assoc($result)) {
		$day = $row["d"];
		$eventdata[$day]["id"][] = $row["id"];

		# set title string; limit char length and append ellipsis if necessary
		$title = stripslashes($row["title"]);
		$eventdata[$day]["title"][] = (strlen($title) > TITLE_CHAR_LIMIT)
			? substr($title, 0, TITLE_CHAR_LIMIT) . '...'
			: $title; 
		
		# set time string
		if (!($row["start_time"] == "55:55:55" 
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
		}
		$eventdata[$day]["timestr"][] = $timestr;
	}
	return $eventdata;
}

# ###################################################################

function getFirstDayOfMonthPosition($month, $year)
{
	$weekpos = date("w", mktime(0,0,0,$month,1,$year));

	// adjust position if weekstart not Sunday
	if (WEEK_START != 0) {
		if ($weekpos < WEEK_START) {
			$weekpos = $weekpos + 7 - WEEK_START;
		} else {
			$weekpos = $weekpos - WEEK_START;
		}
	}
	return $weekpos;
}

# ###################################################################

# testing whether var set necessary to suppress notices when E_NOTICES on
$month = 
	(isset($_GET['month'])) ? (int) $_GET['month'] : null;
$year =
	(isset($_GET['year'])) ? (int) $_GET['year'] : null;

# set month and year to present if month 
# and year not received from query string
$m = (!$month) ? date("n") : $month;
$y = (!$year)  ? date("Y") : $year;

$scrollarrows = scrollArrows($m, $y);
//$auth 		  = auth();

$imgfile1 = "../Addons/GPS/Kiosk Add-On/UserPrograms/images/bg.jpg";

if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf" || $_SESSION["theme"]=="gps") { 
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT u_font FROM u_prglobalsetting");
	while ($objRs->queryfetchrow("NAME")) {
		$csspath =  "../Addons/GPS/PayRoll Add-On/UserPrograms/".$objRs->fields["u_font"]."/";
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta name="viewport" content="user-scalable=no, width=device-width" />
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
	<title></title>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/masterdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/document.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="lnkbtns/docnos.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatedocseries.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<STYLE TYPE="text/css">
<!--
/**************************************************************
********* Formatting For Month Table and Text *****************
**************************************************************/

A		{ font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; text-decoration:none }
SPAN	{ font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif }
TD     	{ font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif }
BODY	{ bgcolor = #9CC4E7; }

/**** month and year header at top of calendar ****/
.date_header			{ font-size:16px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; font-weight:bold }

/**** color and size of calendar cells.  ****/
.day_cell				{ background-color:#EDECD8; }
.empty_day_cell			{ background-color:#EEEEEE; }
.today_cell				{ background-color:#FFFF99; }

/**** day number in upper left corner of each cell ****/
.day_number				{ font-size:11px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-seri }

/**** title_txt is the text for each post on main page ****/
.title_txt				{ font-size:9px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif }

.title_txt A:link		{ color:#000; text-decoration:none }
.title_txt A:active		{ color:#00F; text-decoration:none }
.title_txt A:visited	{ color:#00F; text-decoration:none }
.title_txt A:hover		{ color:#00F; text-decoration:none }


/**** time line under title text when event time given ****/
.time_str				{ font-size:9px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif }

/**** column headers or days of the week ****/
.column_header			{ background-color:#2663E2; font-size:12px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; color:#FFFFFF; font-weight:bold }

/**** footprint text ****/
.footprint				{ font-size:10px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; color:#000; font-weight:bold; }
.footprint A			{ font-size:10px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; color:#00F; font-weight:bold; }
/*// end hiding -->*/

.verticalLine {
	border-left: 1px solid black;
    
}
/*border-left:thick solid #ff0000;*/
</STYLE>
	
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td>
		<?php echo $scrollarrows ?>
		<span class="date_header">
		&nbsp;<?php if (function_exists('onDrawCalendarHeader')) onDrawCalendarHeader(); ?><?php echo $lang['months'][$m-1] ?>&nbsp;<?php echo $y ?></span>
	</td>

	<!-- form tags must be outside of <td> tags -->
	<form name="monthYear">
	<?php require("./inc/formobjs.php") ?>
	<?php if (function_exists('onDrawCalendarToolbar')) { ?>
		<?php onDrawCalendarToolbar(); ?>
		<td align="right">
	<?php } else { ?>
		<td align="right" colspan="2">
	<?php } ?>
	<?php if (function_exists('onDrawCalendarFilter')) onDrawCalendarFilter(); ?>
	<?php monthPullDown($m, $lang['months']); yearPullDown($y); ?>
	<input type="button" value="GO" onClick="submitMonthYear()">
	</td>
	</form>

</tr>

<tr>
	<td colspan="3" bgcolor="#000000">
	<?php echo writeCalendar($m, $y); ?></td>
</tr>
</table>

<div <?php genPopWinHDivHtml("popupFrameDetail","List of Request",50,200,870,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="01">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2"><?php $objGridRequestList->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
</body>
</html>
