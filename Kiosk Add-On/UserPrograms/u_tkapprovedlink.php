	<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkapprovedlink";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/marketingdocumentitems.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/enummonth.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	//include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	require_once("../common/phpmailer/class.phpmailer.php");
	include_once("./classes/emailsync.php");
	
	$page->objectcode = $progid;
	$page->paging->formid = "./UDP.php?objectcode=u_tkapprovedlink";
	$page->formid = "./UDO.php?objectcode=u_tkapprovedlink";
	
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		global $enc;
		
		$actionReturn = true;
		
		if($page->getitemstring("triptype") == 1) {
			$requesttable = 'u_tkrequestformot';
		} else if($page->getitemstring("triptype") == 2) {
			$requesttable = 'u_tkrequestformleave';
		} else if($page->getitemstring("triptype") == 3) {
			$requesttable = 'u_tkrequestformloan';
		} else if($page->getitemstring("triptype") == 4) {
			$requesttable = 'u_tkrequestformtimeadjustment';
		} else if($page->getitemstring("triptype") == 5) {
			$requesttable = 'u_tkrequestformass';
		} else if($page->getitemstring("triptype") == 6) {
			$requesttable = 'u_tkrequestformoffset';
		} else if($page->getitemstring("triptype") == 7) {
			$requesttable = 'u_tkrequestformoffsethrs';
		} else if($page->getitemstring("triptype") == 8) {
			$requesttable = 'u_tkrequestformobset';
		} else if($page->getitemstring("triptype") == 9) {
			$requesttable = 'u_tkrequestformnote';
		} else if($page->getitemstring("triptype") == 10) {
			$requesttable = 'u_tkrequestformoball';
		}
		
		$obju_REQUEST = new masterdataschema_br(NULL,$objConnection,$requesttable);

		$obju_History = new masterdataschema_br(NULL,$objConnection,"u_tkapproverhistory");
		$objRsGlobalSetting = new recordset(null,$objConnection);
		$objRsTo = new recordset(NULL, $objConnection);
		$objRsFrom = new recordset(NULL, $objConnection);
		$objRsEmp = new recordset(NULL, $objConnection);
		$objRsFromNext = new recordset(NULL, $objConnection);
		
		$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {	
				$objEmailSync = new emailsync(null,$objConnection);
				$objEmailSync->queryopen();
				
				if(!$objEmailSync->queryfetchrow()) {
					return raiseError("No Email Synchronization Setup detected.");
				}
				
				$objEmailSync->password = $enc->decrypt($objEmailSync->password);
			}
		
		if(strtolower($action)=="u_update") {
			$objConnection->beginwork();
			if ($obju_REQUEST->getbysql("CODE='".$page->getitemstring("requestno")."'")) {
				if($page->getitemstring("status") == 'A') {
					if($page->getitemstring("xstatus") == '0') {
						$obju_REQUEST->setudfvalue("u_status",1);
					}
				} else if($page->getitemstring("status") == 'D') {
					$obju_REQUEST->setudfvalue("u_status",2);
				}
				$actionReturn = $obju_REQUEST->update($obju_REQUEST->code,$obju_REQUEST->rcdversion);
				if(!$actionReturn) break;
				
				if ($obju_History->getbysql("U_REQUESTNO='".$page->getitemstring("requestno")."' AND CREATEDBY = '".$_SESSION["userid"]."'")) {
				} else {
					$obju_History->prepareadd();
					$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
					$obju_History->name = $obju_History->code;
					$obju_History->setudfvalue("u_requestno",$page->getitemstring("requestno"));
					$obju_History->setudfvalue("u_date",formatDateToDB($page->getitemstring("date")));
					$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
					$obju_History->setudfvalue("u_empid",$page->getitemstring("empid"));
					$obju_History->setudfvalue("u_empname",$page->getitemstring("empname"));
					$obju_History->setudfvalue("u_remarks",$page->getitemstring("userremarks"));
					$obju_History->setudfvalue("u_typeofrequest",$page->getitemstring("remarks").$page->getitemstring("typeofrequest"));
					if($actionReturn) $actionReturn = $obju_History->add();
				}
				
				$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
				if ($objRsGlobalSetting->recordcount() >= 1) {
					$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
					$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
					if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
					
					$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getitemstring("empid")."'");
					if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
					
					$objRsFromNext->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getitemstring("approvercode")."' AND a.u_stageno = '".$page->getitemstring("nextstageno")."'");
					if($objRsFromNext->queryfetchrow()) { $FromAddress = $objRsFromNext->fields[0]; }
					
					$objRsEmp->queryopen("SELECT CONCAT(LEFT(u_fname,1),'. ',u_lname) FROM u_premploymentinfo WHERE code = '".$page->getitemstring("empid")."'");
					if($objRsEmp->queryfetchrow()) { $EmpName = $objRsEmp->fields[0]; }
					
					try {
						$mail = new PHPMailer();
						$mail->IsSMTP(); // telling the class to use SMTP
						//$mail->CharSet = "text/html; charset=UTF-8;";
						$mail->SMTPAuth = false;
						if ($objEmailSync->authmethod == 1) {
							$mail->SMTPAuth = true;
							$mail->Username = $objEmailSync->username;
							$mail->Password = $objEmailSync->password;
						}
							
						$mail->SMTPSecure = strtolower($objEmailSync->security); 
						$mail->Host = $objEmailSync->servername;
						$mail->Port = $objEmailSync->port;
						$mail->SetFrom($objEmailSync->username);
						if($FromAddress != "") {
							$mail->AddAddress($ToAddress);
						} else {
							$mail->AddAddress($CCAddress);
						}
						
						//$mail->AddCC($ToAddress);
						
						$mail->IsHTML(true);
						
						$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
						$html_msg = str_replace("{requestno}",$page->getitemstring("requestno"),$html_msg);
						$html_msg = str_replace("{empid}",$page->getitemstring("empid"),$html_msg);
						$html_msg = str_replace("{empname}",$page->getitemstring("empname"),$html_msg);
						$html_msg = str_replace("{Requestor}",$page->getitemstring("empname"),$html_msg);
						$html_msg = str_replace("{rtype}",$page->getitemstring("typeofrequest"),$html_msg);
						$html_msg = str_replace("{appdate}",$page->getitemstring("date"),$html_msg);
						$html_msg = str_replace("{remarks}",$page->getitemstring("remarks"),$html_msg);
						
						if($page->getitemstring("status") == 'A') {
							if($page->getitemstring("xstatus") == '0') {
								$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
							} else {
								$html_msg = str_replace("{cstatus}","Pending",$html_msg);
							}
						} else if($page->getitemstring("status") == 'D') {
							$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
						}
						
						if($page->getitemstring("status") == 'A') {
							$mail->Subject = "Notification: ".$EmpName." Request No. ".$page->getitemstring("requestno")." is Approved";
						} else if($page->getitemstring("status") == 'D') {
							$mail->Subject = "Notification: ".$EmpName." Request No. ".$page->getitemstring("requestno")." is Rejected";
						}
						
						$mail->Body = $html_msg;
						  
						if(!$mail->Send()) {
							return raiseError("Mailer Error: ".$mail->ErrorInfo);
						}
						
					} catch (phpmailerException $e) {
						return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
					}
				}
				
			} else return raiseError("Unable to find Request No.[".$page->getitemstring("requestno")."].");		
						
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			
			//onFormAction("edit",$filter);
		}
		return $actionReturn;
	}
	
	
	
	function onFormDefault() {
		global $page;
		global $branchdata;
		
	}

	$schema["dept"] = createSchema("dept");
	$schema["reqno"] = createSchema("reqno");
	$schema["triptype"] = createSchema("triptype");
	$schema["status"] = createSchema("status");
	$schema["sdate"] = createSchema("sdate");
	$schema["date1"] = createSchema("date1");
	$schema["date2"] = createSchema("date2");
	
	$schema["requestno"] = createSchema("requestno");
	$schema["date"] = createSchemaDate("date");
	$schema["xstatus"] = createSchema("xstatus");
	$schema["empid"] = createSchema("empid");
	$schema["empname"] = createSchema("empname");
	$schema["remarks"] = createSchema("remarks");
	$schema["typeofrequest"] = createSchema("typeofrequest");
	$schema["userremarks"] = createSchema("userremarks");
	$schema["approvercode"] = createSchema("approvercode");
	$schema["nextstageno"] = createSchema("nextstageno");
	
	$schema["dept"]["attributes"] = "disabled";
	$schema["triptype"]["attributes"] = "disabled";
	$schema["date1"]["attributes"] = "disabled";
	$schema["date2"]["attributes"] = "disabled";
	
	$schema["requestno"]["attributes"] = "disabled";
	$schema["date"]["attributes"] = "disabled";
	$schema["status"]["attributes"] = "disabled";
	$schema["empid"]["attributes"] = "disabled";
	$schema["empname"]["attributes"] = "disabled";
	$schema["remarks"]["attributes"] = "disabled";
	$schema["typeofrequest"]["attributes"] = "disabled";
	$schema["approvercode"]["attributes"] = "disabled";
	$schema["nextstageno"]["attributes"] = "disabled";
	
	$type_req = $_GET["type_req"];
	$req_date = $_GET["req_date"];
	$req_no = $_GET["reqno"];
	$statusmode = $_GET["modes"];
	
	if($type_req != "") {
		$objRsID = new recordset(null,$objConnection);
		$objRsID->queryopen("SELECT company,branch,code,name FROM u_premploymentinfo WHERE code = '".$_GET["id"]."'");
			while ($objRsID->queryfetchrow("NAME")) {
				$_SESSION['company'] = $objRsID->fields["company"];
				$_SESSION['branch'] = $objRsID->fields["branch"];
				$_SESSION['userid'] = $objRsID->fields["code"];
				$_SESSION['username'] = $objRsID->fields["name"];
				$_SESSION["theme"] = "sp";
				$_SESSION["groupid"] = "KIOSK";
				$_SESSION["menunavigator"] = "sidenav";
				$_SESSION["menu"] = "KIOSK";
			}
			
		$page->setitem("date1",formatDateToHttp($req_date));
		$page->setitem("date2",formatDateToHttp($req_date));
		$page->setitem("triptype",$type_req);
		$page->setitem("reqno",$req_no);
		$page->setitem("status",$statusmode);
		$page->setitem("sdate",currentdate());
		
		$page->restoreSavedValues();
		
		$connect = odbc_connect("mysql_localhost_".$_SESSION["dbname"], "root",  $_SESSION['dbpassword']); 
		$stmt = odbc_prepare($connect, "CALL leave_running_balance('".$_SESSION['company']."','".$_SESSION['branch']."')");
		$success = odbc_execute($stmt);
		odbc_result_all($success);
		odbc_free_result($success);
		odbc_close($connect);
		
		$objRsQuery = new recordset(null,$objConnection);
		if ($page->getitemstring("triptype") == 1 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Date : ',DATE_FORMAT(a.u_otdate,'%m/%d/%Y'),' OT Time From',a.u_otfromtime,' & ',a.u_ottotime, ' Reason : ',a.u_otreason) as 'remarks','Over Time' as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory WHERE u_status = 'A' GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."' AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."'");
			
		} else if ($page->getitemstring("triptype") == 2 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,if(ll.u_leavestatus = 'WP',CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'),' Remaining Balance :',ROUND(IFNULL(xx.balance,0),2)),CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'))) as 'remarks',CONCAT(a.u_leavetype,' - ',a.u_leavereason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid ab ON ab.company = a.company AND ab.branch = a.branch AND ab.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus5 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN tmp_leavebalance xx ON xx.empid = a.u_empid AND xx.leavetype = a.u_leavetype LEFT OUTER JOIN u_tkleavemasterfiles ll ON ll.code = xx.leavetype WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND a.u_leavestatus = 'Successful' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."' GROUP BY a.code");
			
		} else if ($page->getitemstring("triptype") == 3 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Loan Dates : ',DATE_FORMAT(a.u_loanfrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_loanto,'%m/%d/%y')) as 'remarks',CONCAT(u_loantype,' - ',u_loanreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus8 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."'");
		
		} else if ($page->getitemstring("triptype") == 4 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date,'%M %d,%Y'),' ',IF(aa.u_type = 'I','Type : In','Type : Out'),' Time :',aa.u_time,'<br>') as 'remarks',CONCAT('Time Adj - ',u_tareason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformtimeadjustment a INNER JOIN u_tkrequestformtimeadjustmentitems aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus6 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.u_tastatus = 'Successful' AND a.code = '".$page->getitemstring("reqno")."' GROUP BY a.code");
		
		} else if ($page->getitemstring("triptype") == 5 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y')) as 'remarks',CONCAT('Official Business',' - ',u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus3 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."'");
		
		} else if ($page->getitemstring("triptype") == 6 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date_filter,'%M %d,%Y'),' Type :',aa.u_type_filter,' Hr :',ROUND(aa.u_hours,2),'<br>') as 'remarks',CONCAT('Off-set',' - ',u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus4 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."' GROUP BY a.code");
			
		} else if ($page->getitemstring("triptype") == 7 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Work Hrs :',ROUND(a.u_offhrs,2),' Off-set Dates : ',DATE_FORMAT(a.u_offdate,'%m/%d/%y')) as 'remarks',CONCAT('Off-set',' - ',u_offreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformoffsethrs a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus4 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."'");
			
		} else if ($page->getitemstring("triptype") == 8 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date_filter,'%M %d,%Y'),' Type :',aa.u_type_filter,' Hr :',ROUND(aa.u_hours,2),'<br>') as 'remarks',CONCAT('OB Hrs',' - ',a.u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformobset a INNER JOIN u_tkrequestformobsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus3 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."' GROUP BY a.code");

		} else if ($page->getitemstring("triptype") == 9 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Request Note :', a.u_note) as 'remarks','Request Note' as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformnote a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus7 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."' GROUP BY a.code");
			
		} else if ($page->getitemstring("triptype") == 10 ) {
			$objRsQuery->queryopen("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date_filter,'%M %d,%Y'),' Type :',IF(aa.u_type_filter = 'AM','OB - In',IF(aa.u_type_filter = 'PM','OB - Out','OB - Days')),' Hr :',ROUND(aa.u_ob_tot_hrs,2),'<br>') as 'remarks',CONCAT('OB All',' - ',a.u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformoball a INNER JOIN u_tkrequestformoballlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus3 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '".$_SESSION['company']."' AND a.branch = '".$_SESSION['branch']."' AND c1.u_stagename = '".$_SESSION['userid']."'  AND a.u_appdate BETWEEN '".formatDateToDB($page->getitemstring("date1"))."' AND '".formatDateToDB($page->getitemstring("date2"))."' AND a.u_status = 0 AND a.code = '".$page->getitemstring("reqno")."' GROUP BY a.code");
			
		}
		if($objRsQuery->recordcount() > 0) {
			while ($objRsQuery->queryfetchrow("NAME")) {
				$page->setitem("dept","PENDING");
				$page->setitem("requestno",$objRsQuery->fields["code"]);
				$page->setitem("date",formatDateToHttp($objRsQuery->fields["u_appdate"]));
				$page->setitem("xstatus",$objRsQuery->fields["vstatus"]);
				$page->setitem("empid",$objRsQuery->fields["u_empid"]);
				$page->setitem("empname",$objRsQuery->fields["u_empname"]);
				$page->setitem("remarks",$objRsQuery->fields["remarks"]);
				$page->setitem("typeofrequest",$objRsQuery->fields["typeofrequest"]);
				$page->setitem("userremarks","");
				$page->setitem("approvercode",$objRsQuery->fields["approvercode"]);
				$page->setitem("nextstageno",$objRsQuery->fields["nextstageno"]);
			}
		} else {
			$page->setitem("dept","ALREADY");
			$page->setitem("requestno","");
			$page->setitem("date","");
			$page->setitem("xstatus","");
			$page->setitem("empid","");
			$page->setitem("empname","");
			$page->setitem("remarks","");
			$page->setitem("typeofrequest","");
			$page->setitem("userremarks","");
			$page->setitem("approvercode","");
			$page->setitem("nextstageno","");	
		}
		
	} else {
		$page->setitem("dept","SUCCESS");
	}
	
	require("./inc/formactions.php");

	$page->toolbar->setaction("update",false);
	$page->toolbar->setaction("print",false);

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

<script language="JavaScript">

	function onPageLoad() {
		if(getInput("dept") == "ALREADY") {
			alert("Already Process.. Try another Request..");
			window.close();
		} else if(getInput("dept") == "SUCCESS") {
			alert("Successful Process..");
		}
	}
	
	function onFormSubmit(action) {
		if(action == "u_update"){
			if (isInputEmpty("triptype")) return false;
			if (isInputEmpty("sdate")) return false;
			if (isInputEmpty("status")) return false;
		}
		return true;
	}
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_triptype":
			case "df_date1":
			case "df_date2":
			case "df_sdate":
			case "df_dept":
			case "df_reqno":
			case "df_status":
			case "df_requestno":
			case "df_date":
			case "df_xstatus":
			case "df_empid":
			case "df_empname":
			case "df_remarks":
			case "df_typeofrequest":
			case "df_userremarks":
			case "df_approvercode":
			case "df_nextstageno":return false;
		}
		return true;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("triptype");
			inputs.push("date1");
			inputs.push("date2");
			inputs.push("sdate");
			inputs.push("dept");
			inputs.push("reqno");
			inputs.push("status");
			inputs.push("requestno");
			inputs.push("date");
			inputs.push("xstatus");
			inputs.push("empid");
			inputs.push("empname");
			inputs.push("remarks");
			inputs.push("typeofrequest");
			inputs.push("userremarks");
			inputs.push("approvercode");
			inputs.push("nextstageno");
		return inputs;
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "triptype":
			case "date1":
			case "date2":
			//case "sdate":
			case "dept":
			//case "status":
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
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" >
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Approved Link Email <?php if($statusmode == "A") { echo "[ Approved ]"; } else if($statusmode == "D") { echo "[ Denied ]"; } ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  	<td colspan="2">&nbsp;<input type="hidden" size="12" <?php genInputTextHtml($schema["sdate"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["dept"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["date1"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["date2"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["triptype"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["status"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["reqno"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["xstatus"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["typeofrequest"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["approvercode"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["nextstageno"]) ?> /></td>
	</tr>
    <tr>
    	<td width="160"><label <?php genCaptionHtml($schema["requestno"],"") ?>>Request No.</label></td>
        <td>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["requestno"]) ?> /></td>
    </tr>
    <tr>
    	<td width="160"><label <?php genCaptionHtml($schema["date"],"") ?>>Application Date</label></td>
        <td>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["date"]) ?> /></td>
    </tr>
    <tr>
    	<td width="160"><label <?php genCaptionHtml($schema["empid"],"") ?>>ID & Name</label></td>
        <td>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["empid"]) ?> />
        		  <input type="text" size="50" <?php genInputTextHtml($schema["empname"]) ?> /></td>
    </tr>
    <tr>
    	<td width="160"><label <?php genCaptionHtml($schema["remarks"],"") ?>>Remarks</label></td>
        <td>&nbsp;<TEXTAREA <?php genInputTextHtml($schema["remarks"]) ?> rows="2" cols="50"><?php echo $httpVars['df_remarks'] ?></TEXTAREA></td>
    </tr>
    <tr>
    	<td width="160"><label <?php genCaptionHtml($schema["userremarks"],"") ?>>Approver Remarks</label></td>
        <td>&nbsp;<TEXTAREA <?php genInputTextHtml($schema["userremarks"]) ?> rows="2" cols="50"><?php echo $httpVars['df_userremarks'] ?></TEXTAREA></td>
    </tr>
    <tr>
      	<td width="160">&nbsp;</td>
	  	<td align=left>
        <?php if($statusmode == "A") { ?>
        <a class="button" href="" onClick="formSubmit('u_update');return false;">Process</a>
        <?php } else if($statusmode == "D") { ?>
        <a class="button" href="" onClick="formSubmit('u_update');return false;">Process</a>
        <?php } ?>
        </td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td>&nbsp;</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr ><td></td></tr>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
<?php require("./bofrms/ajaxprocess.php"); ?>		
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>

<?php $page->writeEndScript(); ?>
