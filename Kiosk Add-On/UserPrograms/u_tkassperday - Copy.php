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
	$schema["due_date"] = createSchemaDate("due_date");
	$schema["filter_date"] = createSchema("filter_date");
	$schema["showtype"] = createSchema("showtype");
	$schema["terms"] = createSchema("terms");
	$schema["payroll_type"] = createSchema("payroll_type");
	
	$schema["from_date"]["attributes"] = "disabled";
	$schema["to_date"]["attributes"] = "disabled";
	$schema["due_date"]["attributes"] = "disabled";
	
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
	//$schema["payroll_type"]["attributes"] = "visible";
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
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("profitcenter");
	$objGrid->addcolumn("dates");
	$objGrid->addcolumn("daysname");
	$objGrid->addcolumn("time_from");
	$objGrid->addcolumn("time_to");
	$objGrid->addcolumn("empid");
	$objGrid->addcolumn("late_hrs");
	$objGrid->addcolumn("late_mins");
	$objGrid->addcolumn("ut_hrs");
	$objGrid->addcolumn("ut_mins");
	$objGrid->addcolumn("absent_hrs");
	$objGrid->addcolumn("absent_day");
	
	$objGrid->addcolumn("regwh_hrs");
	$objGrid->addcolumn("regnd_hrs");
	$objGrid->addcolumn("regot_hrs");
	$objGrid->addcolumn("regotnd_hrs");
	$objGrid->addcolumn("regworwh_hrs");
	$objGrid->addcolumn("regwornd_hrs");
	$objGrid->addcolumn("regworot_hrs");
	$objGrid->addcolumn("regworotnd_hrs");
	
	$objGrid->addcolumn("shwh_hrs");
	$objGrid->addcolumn("shnd_hrs");
	$objGrid->addcolumn("shot_hrs");
	$objGrid->addcolumn("shotnd_hrs");
	$objGrid->addcolumn("shworwh_hrs");
	$objGrid->addcolumn("shwornd_hrs");
	$objGrid->addcolumn("shworot_hrs");
	$objGrid->addcolumn("shworotnd_hrs");
	
	$objGrid->addcolumn("rlwh_hrs");
	$objGrid->addcolumn("rlnd_hrs");
	$objGrid->addcolumn("rlot_hrs");
	$objGrid->addcolumn("rlotnd_hrs");
	$objGrid->addcolumn("rlworwh_hrs");
	$objGrid->addcolumn("rlwornd_hrs");
	$objGrid->addcolumn("rlworot_hrs");
	$objGrid->addcolumn("rlworotnd_hrs");
	
	$objGrid->columntitle("profitcenter","Profit Center");
	$objGrid->columntitle("dates","Date");
	$objGrid->columntitle("daysname","Days");
	$objGrid->columntitle("time_from","Time From");
	$objGrid->columntitle("time_to","Time To");
	$objGrid->columntitle("late_hrs","Late");
	$objGrid->columntitle("late_mins","Late Mins");
	$objGrid->columntitle("ut_hrs","U.T");
	$objGrid->columntitle("ut_mins","U.T Mins");
	$objGrid->columntitle("absent_hrs","Absent");
	$objGrid->columntitle("absent_day","Absent Days");
	
	$objGrid->columntitle("regwh_hrs","Reg.");
	$objGrid->columntitle("regnd_hrs","Reg. N.D");
	$objGrid->columntitle("regot_hrs","Reg. O.T");
	$objGrid->columntitle("regotnd_hrs","Reg. N.D & O.T");
	$objGrid->columntitle("regworwh_hrs","Reg. W.O.R");
	$objGrid->columntitle("regwornd_hrs","Reg. W.O.R N.D");
	$objGrid->columntitle("regworot_hrs","Reg. W.O.R O.T");
	$objGrid->columntitle("regworotnd_hrs","Reg. W.O.R N.D & O.T");
	
	$objGrid->columntitle("shwh_hrs","S.H");
	$objGrid->columntitle("shnd_hrs","S.H N.D");
	$objGrid->columntitle("shot_hrs","S.H O.T");
	$objGrid->columntitle("shotnd_hrs","S.H N.D & O.T");
	$objGrid->columntitle("shworwh_hrs","S.H W.O.R");
	$objGrid->columntitle("shwornd_hrs","S.H W.O.R N.D");
	$objGrid->columntitle("shworot_hrs","S.H W.O.R O.T");
	$objGrid->columntitle("shworotnd_hrs","S.H W.O.R N.D & O.T");
	
	$objGrid->columntitle("rlwh_hrs","R.L");
	$objGrid->columntitle("rlnd_hrs","R.L N.D");
	$objGrid->columntitle("rlot_hrs","R.L O.T");
	$objGrid->columntitle("rlotnd_hrs","R.L N.D & O.T");
	$objGrid->columntitle("rlworwh_hrs","R.L W.O.R");
	$objGrid->columntitle("rlwornd_hrs","R.L W.O.R N.D");
	$objGrid->columntitle("rlworot_hrs","R.L W.O.R O.T");
	$objGrid->columntitle("rlworotnd_hrs","R.L W.O.R N.D & O.T");
	
	$objGrid->columnwidth("profitcenter",25);
	$objGrid->columnwidth("dates",12);
	$objGrid->columnwidth("time_from",12);
	$objGrid->columnwidth("time_to",12);
	$objGrid->columnwidth("late_hrs",15);
	$objGrid->columnwidth("late_mins",15);
	$objGrid->columnwidth("ut_hrs",12);
	$objGrid->columnwidth("ut_mins",12);
	$objGrid->columnwidth("absent_hrs",12);
	$objGrid->columnwidth("absent_day",12);
	
	$objGrid->columnwidth("regwh_hrs",15);
	$objGrid->columnwidth("regnd_hrs",15);
	$objGrid->columnwidth("regot_hrs",15);
	$objGrid->columnwidth("regotnd_hrs",15);
	$objGrid->columnwidth("regworwh_hrs",15);
	$objGrid->columnwidth("regwornd_hrs",15);
	$objGrid->columnwidth("regworot_hrs",15);
	$objGrid->columnwidth("regworotnd_hrs",18);
	
	$objGrid->columnwidth("shwh_hrs",15);
	$objGrid->columnwidth("shnd_hrs",15);
	$objGrid->columnwidth("shot_hrs",15);
	$objGrid->columnwidth("shotnd_hrs",15);
	$objGrid->columnwidth("shworwh_hrs",15);
	$objGrid->columnwidth("shwornd_hrs",15);
	$objGrid->columnwidth("shworot_hrs",15);
	$objGrid->columnwidth("shworotnd_hrs",18);
	
	$objGrid->columnwidth("rlwh_hrs",15);
	$objGrid->columnwidth("rlnd_hrs",15);
	$objGrid->columnwidth("rlot_hrs",15);
	$objGrid->columnwidth("rlotnd_hrs",15);
	$objGrid->columnwidth("rlworwh_hrs",15);
	$objGrid->columnwidth("rlwornd_hrs",15);
	$objGrid->columnwidth("rlworot_hrs",15);
	$objGrid->columnwidth("rlworotnd_hrs",18);
	
	$objGrid->columnvisibility("empid",false);
	$objGrid->columnvisibility("profitcenter",false);
	//$objGrid->columnvisibility("ut_mins",false);
	//$objGrid->columnvisibility("absent_day",false);
	
	$objGrid->automanagecolumnwidth = false;
	
	$objGrid->width = 550;
	$objGrid->height = 280;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "empid,datedue";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	$objGridBio = new grid("T15",$httpVars,true);
	$objGridBio->addcolumn("profitcenter");
	$objGridBio->addcolumn("u_date");
	$objGridBio->addcolumn("u_dayname");
	$objGridBio->addcolumn("time");
	$objGridBio->addcolumn("typeofmode");
	$objGridBio->addcolumn("u_biometrixid");
	$objGridBio->addcolumn("branches");
	
	$objGridBio->columntitle("profitcenter","Profit Center");
	$objGridBio->columntitle("u_date","Date");
	$objGridBio->columntitle("u_dayname","Days");
	$objGridBio->columntitle("time","Time");
	$objGridBio->columntitle("typeofmode","Type of Mode");
	$objGridBio->columntitle("u_biometrixid","Biometrix ID");
	$objGridBio->columntitle("branches","Machine ID");
	
	$objGridBio->columnwidth("profitcenter",25);
	$objGridBio->columnwidth("u_date",12);
	$objGridBio->columnwidth("u_dayname",15);
	$objGridBio->columnwidth("time",12);
	$objGridBio->columnwidth("typeofmode",12);
	$objGridBio->columnwidth("u_biometrixid",12);
	$objGridBio->columnwidth("branches",12);
	
	$objGridBio->columnvisibility("u_biometrixid",false);
	$objGridBio->columnvisibility("branches",false);
	$objGridBio->columnvisibility("profitcenter",false);
	
	$objGridBio->automanagecolumnwidth = false;
	
	$objGridBio->width = 480;
	$objGridBio->height = 280;
	
	function loadu_showtype($p_selected) {
		$status1= array('Days' => 'Per Days', 'All' => 'Grand Total');
		$_html = "";	
		$_selected = "";
		reset($status1);
		while (list($key, $val) = each($status1)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
	
	function loadu_conf($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE,NAME from U_PRPAYROLLCONFIG group by CODE");
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
	
	function loadu_per($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE,CONCAT(CODE,'-',NAME) from U_PREMPLOYMENTINFO group by CODE");
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
	
	function loadu_fd($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE,NAME from U_TKBATCHPROCESSING group by CODE");
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
	
	$filterExp = genSQLFilterString("x.datedue",$filterExp,formatDateToDB($httpVars['df_from_date']),formatDateToDB($httpVars['df_to_date']));
	if($_SESSION['usertype'] != "E") {
		$filterExp = genSQLFilterString("xxx.code",$filterExp,$httpVars['df_id']);
	}
	
	if ($filterExp !="") { 
		$filterExp = " AND " . $filterExp;
	}
	
	$objrs = new recordset(null,$objConnection);
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
		if($filterExp != "") {
			if($_SESSION["usertype"] != "") {
				$objrs->queryopenext("SELECT xxx.code as u_empid,
										   xxx.name as u_empname,
										   z.u_daysname,
										   IFNULL(xxxx.name,'No Schedule') as u_scheduletype,
										   fx.name as u_timein,
										   tx.name as u_timeout2,
										   if(ifnull(z.u_status,'') = 'Leave',1,0) as leave_days,
										   zz.name as prof,
										   x.*
							
								FROM tmp_dtr_days x
								LEFT OUTER JOIN u_tkschedulecolumntorow z ON z.u_empid = x.empid 
									AND z.u_profitcenter = x.profitcenter 
									AND CONCAT(z.u_year,'-',z.u_month,'-',IF(LENGTH(z.u_days) =1,CONCAT('0',z.u_days),z.u_days)) = x.datedue
									AND z.u_status != ''
									AND z.company = '$company'
									AND z.branch = '$branch'
								LEFT OUTER JOIN u_premploymentinfo xxx ON xxx.code = x.empid
									AND xxx.company = z.company
									AND xxx.branch = z.branch
								LEFT OUTER JOIN u_prprofitcenter zz ON zz.code = x.profitcenter
								LEFT OUTER JOIN u_premploymentdeployment xx ON xx.code = xxx.code
  									AND xx.u_branch = x.profitcenter
			  					LEFT OUTER JOIN u_tkschedulemasterfiles xxxx ON xxxx.code = z.u_scheduleassign
								LEFT OUTER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(x.from_date,5)
								LEFT OUTER JOIN u_tktimemasterfiles tx ON tx.code = LEFT(x.to_date,5)
								WHERE xxx.code = '".$_SESSION['userid']."' $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			} else {
				$objrs->queryopenext("SELECT xxx.code as u_empid,
										   xxx.name as u_empname,
										   z.u_daysname,
										   IFNULL(xxxx.name,'No Schedule') as u_scheduletype,
										   fx.name as u_timein,
										   tx.name as u_timeout2,
										   if(ifnull(z.u_status,'') = 'Leave',1,0) as leave_days,
										   zz.name as prof,
										   x.*
							
								FROM tmp_dtr_days x
								LEFT OUTER JOIN u_tkschedulecolumntorow z ON z.u_empid = x.empid 
									AND z.u_profitcenter = x.profitcenter 
									AND CONCAT(z.u_year,'-',z.u_month,'-',IF(LENGTH(z.u_days) =1,CONCAT('0',z.u_days),z.u_days)) = x.datedue
									AND z.u_status != ''
									AND z.company = '$company'
									AND z.branch = '$branch'
								LEFT OUTER JOIN u_premploymentinfo xxx ON xxx.code = x.empid
									AND xxx.company = z.company
									AND xxx.branch = z.branch
								LEFT OUTER JOIN u_prprofitcenter zz ON zz.code = x.profitcenter
								LEFT OUTER JOIN u_premploymentdeployment xx ON xx.code = xxx.code
  									AND xx.u_branch = x.profitcenter
			  					LEFT OUTER JOIN u_tkschedulemasterfiles xxxx ON xxxx.code = z.u_scheduleassign
								LEFT OUTER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(x.from_date,5)
								LEFT OUTER JOIN u_tktimemasterfiles tx ON tx.code = LEFT(x.to_date,5)
								WHERE '' = '' $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			}
		} else {
			$objrs->queryopenext("SELECT xxx.code as u_empid,
										   xxx.name as u_empname,
										   z.u_daysname,
										   IFNULL(xxxx.name,'No Schedule') as u_scheduletype,
										   fx.name as u_timein,
										   tx.name as u_timeout2,
										   if(ifnull(z.u_status,'') = 'Leave',1,0) as leave_days,
										   zz.name as prof,
										   x.*
							
								FROM tmp_dtr_days x
								LEFT OUTER JOIN u_tkschedulecolumntorow z ON z.u_empid = x.empid 
									AND z.u_profitcenter = x.profitcenter 
									AND CONCAT(z.u_year,'-',z.u_month,'-',IF(LENGTH(z.u_days) =1,CONCAT('0',z.u_days),z.u_days)) = x.datedue
									AND z.u_status != ''
									AND z.company = '$company'
									AND z.branch = '$branch'
								LEFT OUTER JOIN u_premploymentinfo xxx ON xxx.code = x.empid
									AND xxx.company = z.company
									AND xxx.branch = z.branch
								LEFT OUTER JOIN u_prprofitcenter zz ON zz.code = x.profitcenter
								LEFT OUTER JOIN u_premploymentdeployment xx ON xx.code = xxx.code
  									AND xx.u_branch = x.profitcenter
			  					LEFT OUTER JOIN u_tkschedulemasterfiles xxxx ON xxxx.code = z.u_scheduleassign
								LEFT OUTER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(x.from_date,5)
								LEFT OUTER JOIN u_tktimemasterfiles tx ON tx.code = LEFT(x.to_date,5)
								WHERE xxx.code = '' $filterExp",$objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		}
		
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"profitcenter",$objrs->fields["PROFITCENTER"]);
			$objGrid->setitem(null,"dates",formatDateToHttp($objrs->fields["DATEDUE"]));
			$objGrid->setitem(null,"daysname",$objrs->fields["u_daysname"]);
			$objGrid->setitem(null,"time_from",$objrs->fields["u_timein"]);
			$objGrid->setitem(null,"time_to",$objrs->fields["u_timeout2"]);
			$objGrid->setitem(null,"late_hrs",formatNumericAmount($objrs->fields["LATE_HRS"]));
			$objGrid->setitem(null,"late_mins",formatNumericAmount($objrs->fields["LATE_MINS"]));
			$objGrid->setitem(null,"ut_hrs",formatNumericAmount($objrs->fields["UT_HRS"]));
			$objGrid->setitem(null,"ut_mins",formatNumericAmount($objrs->fields["UT_MINS"]));
			$objGrid->setitem(null,"absent_hrs",formatNumericAmount($objrs->fields["ABSENT_HRS"]));
			$objGrid->setitem(null,"absent_day",formatNumericAmount($objrs->fields["ABSENT_DAY"]));
			$objGrid->setitem(null,"regwh_hrs",formatNumericAmount($objrs->fields["REGWH_HRS"]));
			$objGrid->setitem(null,"regnd_hrs",formatNumericAmount($objrs->fields["REGND_HRS"]));
			$objGrid->setitem(null,"regot_hrs",formatNumericAmount($objrs->fields["REGOT_HRS"]));
			$objGrid->setitem(null,"regotnd_hrs",formatNumericAmount($objrs->fields["REGOTND_HRS"]));
			$objGrid->setitem(null,"regworwh_hrs",formatNumericAmount($objrs->fields["REGWORWH_HRS"]));
			$objGrid->setitem(null,"regwornd_hrs",formatNumericAmount($objrs->fields["REGWORND_HRS"]));
			$objGrid->setitem(null,"regworot_hrs",formatNumericAmount($objrs->fields["REGWOROT_HRS"]));
			$objGrid->setitem(null,"regworotnd_hrs",formatNumericAmount($objrs->fields["REGWOROTND_HRS"]));
		
			$objGrid->setitem(null,"shwh_hrs",formatNumericAmount($objrs->fields["SHWH_HRS"]));
			$objGrid->setitem(null,"shnd_hrs",formatNumericAmount($objrs->fields["SHND_HRS"]));
			$objGrid->setitem(null,"shot_hrs",formatNumericAmount($objrs->fields["SHOT_HRS"]));
			$objGrid->setitem(null,"shotnd_hrs",formatNumericAmount($objrs->fields["SHOTND_HRS"]));
			$objGrid->setitem(null,"shworwh_hrs",formatNumericAmount($objrs->fields["SHWORWH_HRS"]));
			$objGrid->setitem(null,"shwornd_hrs",formatNumericAmount($objrs->fields["SHWORND_HRS"]));
			$objGrid->setitem(null,"shworot_hrs",formatNumericAmount($objrs->fields["SHWOROT_HRS"]));
			$objGrid->setitem(null,"shworotnd_hrs",formatNumericAmount($objrs->fields["SHWOROTND_HRS"]));
			
			$objGrid->setitem(null,"rlwh_hrs",formatNumericAmount($objrs->fields["RLWH_HRS"]));
			$objGrid->setitem(null,"rlnd_hrs",formatNumericAmount($objrs->fields["RLND_HRS"]));
			$objGrid->setitem(null,"rlot_hrs",formatNumericAmount($objrs->fields["RLOT_HRS"]));
			$objGrid->setitem(null,"rlotnd_hrs",formatNumericAmount($objrs->fields["RLOTND_HRS"]));
			$objGrid->setitem(null,"rlworwh_hrs",formatNumericAmount($objrs->fields["RLWORWH_HRS"]));
			$objGrid->setitem(null,"rlwornd_hrs",formatNumericAmount($objrs->fields["RLWORND_HRS"]));
			$objGrid->setitem(null,"rlworot_hrs",formatNumericAmount($objrs->fields["RLWOROT_HRS"]));
			$objGrid->setitem(null,"rlworotnd_hrs",formatNumericAmount($objrs->fields["RLWOROTND_HRS"]));
		}	
	}
	
	
	$rptcols = 6; 
	$page->toolbar->setaction("update",false);
	//$page->toolbar->setaction("print",false);
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
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<script language="JavaScript">

	function onPageLoad() {
		if("<?php echo $_SESSION["menunavigator"] ?>" == "topnav"){
			alert("SESSION expired.. system autolog-out..");
			logOut();
		}
	}
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "filter_date":
				addDueGPSKiosk(); break;
		}
		return true;
	}	
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				computeTotalFees();
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
			case "df_filter_date":
			case "df_due_date":
			case "df_to_date":
			case "df_from_date":
			case "df_id":
			case "df_showtype":
			case "df_payroll_type":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("due_date");
			inputs.push("from_date");
			inputs.push("to_date");
			inputs.push("filter_date");
			inputs.push("id");
			inputs.push("showtype");
			inputs.push("payroll_type");
		return inputs;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "due_date":
			case "from_date":
			case "to_date":
			case "filter_date":
			case "id":
			case "showtype":
			case "payroll_type":
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
				if(getInput("showtype") == "Days") {
					compDTRperDay(getTableInput(table,"profitcenter",row),getInput("id"),formatDateToDB(getTableInput(table,"dates",row)));
				} else if(getInput("showtype") == "All") {
					compDTRperAll(getTableInput(table,"profitcenter",row),getInput("id"),formatDateToDB(getInput("from_date")),formatDateToDB(getInput("to_date")));
				}
				break;
		}		
		return params;
	}
	
	function addDueGPSKiosk() {
		var result, data = new Array();
		showAjaxProcess();
		result = page.executeFormattedQuery("SELECT u_from,u_to,u_payrolldue,u_terms FROM u_tkbatchprocessing a WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getInput("filter_date")+"'");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					setInput("from_date",formatDateToHttp(result.childNodes.item(0).getAttribute("u_from")));
					setInput("to_date",formatDateToHttp(result.childNodes.item(0).getAttribute("u_to")));
					setInput("due_date",formatDateToHttp(result.childNodes.item(0).getAttribute("u_payrolldue")));
					setInput("terms",result.childNodes.item(0).getAttribute("u_terms"));
				}
			} else {
				hideAjaxProcess();
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		hideAjaxProcess();
		return true;
	}
	
	function compDTRperDay(pf,empid,dates) {
		var result;
		var data = new Array();
			result = page.executeFormattedQuery("SELECT late_hrs,late_mins,ut_hrs,ut_mins,absent_hrs,absent_day,regwh_hrs,regnd_hrs,regot_hrs,regotnd_hrs,regworwh_hrs,regwornd_hrs,regworot_hrs,regworotnd_hrs,shwh_hrs,shnd_hrs,shot_hrs,shotnd_hrs,shworwh_hrs,shwornd_hrs,shworot_hrs,shworotnd_hrs,rlwh_hrs,rlnd_hrs,rlot_hrs,rlotnd_hrs,rlworwh_hrs,rlwornd_hrs,rlworot_hrs,rlworotnd_hrs FROM tmp_dtr_days a WHERE a.empid ='"+empid+"' AND a.datedue ='"+dates+"' AND a.profitcenter ='"+pf+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("late_hrs",formatNumber(result.childNodes.item(0).getAttribute("late_hrs"),"amount"));
						setInput("late_mins",formatNumber(result.childNodes.item(0).getAttribute("late_mins"),"amount"));
						setInput("ut_hrs",formatNumber(result.childNodes.item(0).getAttribute("ut_hrs"),"amount"));
						setInput("ut_mins",formatNumber(result.childNodes.item(0).getAttribute("ut_mins"),"amount"));
						setInput("absent_hrs",formatNumber(result.childNodes.item(0).getAttribute("absent_hrs"),"amount"));
						setInput("absent_day",formatNumber(result.childNodes.item(0).getAttribute("absent_day"),"amount"));
						
						setInput("regwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("regwh_hrs"),"amount"));
						setInput("regnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regnd_hrs"),"amount"));
						setInput("regot_hrs",formatNumber(result.childNodes.item(0).getAttribute("regot_hrs"),"amount"));
						setInput("regotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regotnd_hrs"),"amount"));
						setInput("regworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("regworwh_hrs"),"amount"));
						setInput("regwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regwornd_hrs"),"amount"));
						setInput("regworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("regworot_hrs"),"amount"));
						setInput("regworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regworotnd_hrs"),"amount"));
						
						setInput("shwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("shwh_hrs"),"amount"));
						setInput("shnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shnd_hrs"),"amount"));
						setInput("shot_hrs",formatNumber(result.childNodes.item(0).getAttribute("shot_hrs"),"amount"));
						setInput("shotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shotnd_hrs"),"amount"));
						setInput("shworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("shworwh_hrs"),"amount"));
						setInput("shwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shwornd_hrs"),"amount"));
						setInput("shworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("shworot_hrs"),"amount"));
						setInput("shworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shworotnd_hrs"),"amount"));
						
						setInput("rlwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlwh_hrs"),"amount"));
						setInput("rlnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlnd_hrs"),"amount"));
						setInput("rlot_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlot_hrs"),"amount"));
						setInput("rlotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlotnd_hrs"),"amount"));
						setInput("rlworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlworwh_hrs"),"amount"));
						setInput("rlwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlwornd_hrs"),"amount"));
						setInput("rlworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlworot_hrs"),"amount"));
						setInput("rlworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlworotnd_hrs"),"amount"));
						}
					} else {
						hideAjaxProcess();
						page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
						return false;
					}
			return true;
		}
		
		function compDTRperAll(pf,empid,from,to) {
		var result;
		var data = new Array();
			result = page.executeFormattedQuery("SELECT SUM(late_hrs) as 'late_hrs',SUM(late_mins) as 'late_mins',SUM(ut_hrs) as 'ut_hrs',SUM(ut_mins) as 'ut_mins',SUM(absent_hrs) as 'absent_hrs',SUM(absent_day) as 'absent_day',SUM(regwh_hrs) as 'regwh_hrs',SUM(regnd_hrs) as 'regnd_hrs',SUM(regot_hrs) as 'regot_hrs',SUM(regotnd_hrs) as 'regotnd_hrs',SUM(regworwh_hrs) as 'regworwh_hrs',SUM(regwornd_hrs) as 'regwornd_hrs',SUM(regworot_hrs) as 'regworot_hrs',SUM(regworotnd_hrs) as 'regworotnd_hrs',SUM(shwh_hrs) as 'shwh_hrs',SUM(shnd_hrs) as 'shnd_hrs',SUM(shot_hrs) as 'shot_hrs',SUM(shotnd_hrs) as 'shotnd_hrs',SUM(shworwh_hrs) as 'shworwh_hrs',SUM(shwornd_hrs) as 'shwornd_hrs',SUM(shworot_hrs) as 'shworot_hrs',SUM(shworotnd_hrs) as 'shworotnd_hrs',SUM(rlwh_hrs) as 'rlwh_hrs',SUM(rlnd_hrs) as 'rlnd_hrs',SUM(rlot_hrs) as 'rlot_hrs',SUM(rlotnd_hrs) as 'rlotnd_hrs',SUM(rlworwh_hrs) as 'rlworwh_hrs',SUM(rlwornd_hrs) as 'rlwornd_hrs',SUM(rlworot_hrs) as 'rlworot_hrs',SUM(rlworotnd_hrs) as 'rlworotnd_hrs' FROM tmp_dtr_days a WHERE a.empid ='"+empid+"' AND a.datedue BETWEEN '"+from+"' AND '"+to+"' AND a.profitcenter ='"+pf+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("late_hrs",formatNumber(result.childNodes.item(0).getAttribute("late_hrs"),"amount"));
						setInput("late_mins",formatNumber(result.childNodes.item(0).getAttribute("late_mins"),"amount"));
						setInput("ut_hrs",formatNumber(result.childNodes.item(0).getAttribute("ut_hrs"),"amount"));
						setInput("ut_mins",formatNumber(result.childNodes.item(0).getAttribute("ut_mins"),"amount"));
						setInput("absent_hrs",formatNumber(result.childNodes.item(0).getAttribute("absent_hrs"),"amount"));
						setInput("absent_day",formatNumber(result.childNodes.item(0).getAttribute("absent_day"),"amount"));
						
						setInput("regwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("regwh_hrs"),"amount"));
						setInput("regnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regnd_hrs"),"amount"));
						setInput("regot_hrs",formatNumber(result.childNodes.item(0).getAttribute("regot_hrs"),"amount"));
						setInput("regotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regotnd_hrs"),"amount"));
						setInput("regworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("regworwh_hrs"),"amount"));
						setInput("regwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regwornd_hrs"),"amount"));
						setInput("regworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("regworot_hrs"),"amount"));
						setInput("regworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("regworotnd_hrs"),"amount"));
						
						setInput("shwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("shwh_hrs"),"amount"));
						setInput("shnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shnd_hrs"),"amount"));
						setInput("shot_hrs",formatNumber(result.childNodes.item(0).getAttribute("shot_hrs"),"amount"));
						setInput("shotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shotnd_hrs"),"amount"));
						setInput("shworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("shworwh_hrs"),"amount"));
						setInput("shwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shwornd_hrs"),"amount"));
						setInput("shworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("shworot_hrs"),"amount"));
						setInput("shworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("shworotnd_hrs"),"amount"));
						
						setInput("rlwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlwh_hrs"),"amount"));
						setInput("rlnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlnd_hrs"),"amount"));
						setInput("rlot_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlot_hrs"),"amount"));
						setInput("rlotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlotnd_hrs"),"amount"));
						setInput("rlworwh_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlworwh_hrs"),"amount"));
						setInput("rlwornd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlwornd_hrs"),"amount"));
						setInput("rlworot_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlworot_hrs"),"amount"));
						setInput("rlworotnd_hrs",formatNumber(result.childNodes.item(0).getAttribute("rlworotnd_hrs"),"amount"));
						}
					} else {
						hideAjaxProcess();
						page.statusbar.showError("Error retrieving DTR Days. Try Again, if problem persists, check the connection.");	
						return false;
					}

			return true;
		}
		
		function showItems() {
		var data = new Array();
			showAjaxProcess();
			clearTable("T15",true);
			var result = page.executeFormattedQuery("SELECT a.u_profitcenter,a.u_date,DAYNAME(a.u_date) as days, fx.name as u_time,a.u_type,a.u_biometrixid,a.u_branch FROM u_tkattendanceentry a LEFT OUTER JOIN u_tktimemasterfiles fx ON fx.code = LEFT(a.u_time,5) WHERE a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_empid='"+getGlobal("userid")+"' AND a.u_date BETWEEN '"+formatDateToDB(getInput("from_date"))+"' AND '"+formatDateToDB(getInput("to_date"))+"'");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["profitcenter"] = result.childNodes.item(xxxi).getAttribute("u_profitcenter");
						data["u_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_date"));
						data["u_dayname"] = result.childNodes.item(xxxi).getAttribute("days");
						data["time"] = result.childNodes.item(xxxi).getAttribute("u_time");
						data["typeofmode"] = result.childNodes.item(xxxi).getAttribute("u_type");
						data["u_biometrixid"] = result.childNodes.item(xxxi).getAttribute("u_biometrixid");
						data["branches"] = result.childNodes.item(xxxi).getAttribute("u_branch");
						insertTableRowFromArray("T15",data);
					}
				}
			} else {
				if (showprocess) hideAjaxProcess();
				page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
				return false;
			}
			hideAjaxProcess();
		}
		
		function OpenBioMetrixGPSKiosk() {
			showItems()
			showPopupFrame("popupFrameBiometrix");
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
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;DTR Per Day Form&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td width="141"><label <?php genCaptionHtml($schema["filter_date"],"") ?>>Date & Conf.</label></td>
	  <td align=left>&nbsp;<select <?php genSelectHtml($schema["filter_date"],array("loadu_fd","",":")) ?> ></select>
      				 &nbsp;<input type="hidden" size="8" <?php genInputTextHtml($schema["payroll_type"]) ?> />
                     <input type="hidden" size="12" <?php genInputTextHtml($schema["terms"]) ?> /></td>
      <td rowspan="6">
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
	  <td width="141"><label <?php genCaptionHtml($schema["showtype"],"") ?>>Show Type</label></td>
	  <td align=left>&nbsp;<select <?php genSelectHtml($schema["showtype"],array("loadu_showtype","","")) ?> ></select></td>
	</tr>
    <tr>
	  <td width="141"><label <?php genCaptionHtml($schema["id"],"") ?>>ID & Name</label></td>
	  <td align=left>&nbsp;<select <?php genSelectHtml($schema["id"],array("loadu_per","","")) ?> ></select></td>
	</tr>
    <tr>
	  <td width="141"><label <?php genCaptionHtml($schema["due_date"],"") ?>>Payroll Due Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["due_date"]) ?> /></td>
	</tr>
    <tr>
	  <td width="141"><label <?php genCaptionHtml($schema["to_date"],"") ?>>Payroll From & To Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["from_date"]) ?> />
      				  &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["to_date"]) ?> />
                      &nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;</td>
	</tr>
    <tr>
	  <td colspan="2"><?php $objGrid->draw(true) ?></td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>&nbsp;</td></tr>
<?php //$page->resize->addgridobject($objGrid,320,400) ?>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
<div <?php genPopWinHDivHtml("popupFrameBiometrix","Biometrix History",200,50,550,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td><?php $objGridBio->draw(true) ?></td></tr>
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
<?php require("./inc/rptobjs.php") ?>
</body>
</html>

<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . $page->toolbar->generateQueryString(). "&toolbarscript=./GenericGridMasterDataToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
