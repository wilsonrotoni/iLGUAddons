	<?php

	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_tkapprovedhistory";
	
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
	include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	require_once("../common/phpmailer/class.phpmailer.php");
	include_once("./classes/emailsync.php");
	
	$page->objectcode = $progid;
	$page->paging->formid = "./UDP.php?objectcode=u_tkapprovedhistory";
	$page->formid = "./UDO.php?objectcode=u_tkapprovedhistory";
	
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		global $enc;
		
		$actionReturn = true;
		$obju_ASS = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformass");
		$obju_LEAVE = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformleave");
		$obju_LOAN = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformloan");
		$obju_OT = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformot");
		$obju_TIMEADJ = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformtimeadjustment");
		$obju_OFF = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformoffset");
		$obju_OFFH = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformoffsethrs");
		$obju_OBH = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformobset");
		$obju_NOT = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformnote");
		$obju_OBA = new masterdataschema_br(NULL,$objConnection,"u_tkrequestformoball");

		
		$obju_History = new masterdataschema_br(NULL,$objConnection,"u_tkapproverhistory");
		$objRsGlobalSetting = new recordset(null,$objConnection);
		$objRsTo = new recordset(NULL, $objConnection);
		$objRsFrom = new recordset(NULL, $objConnection);
		
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
			for($i=1;$i<= $objGrid->recordcount;$i++) {
				//var_dump($page->getcolumnstring("checkedT1r".$i));
				//var_dump($page->getcolumnstring("requestnoT1r".$i));
				if($page->getitemstring("triptype") == 1) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						if ($obju_OT->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
								if($page->getitemstring("status") == 'A') {
									if($page->getcolumnstring("statusT1r".$i) == '0') {
										$obju_OT->setudfvalue("u_status",1);
									}
								} else if($page->getitemstring("status") == 'D') {
									$obju_OT->setudfvalue("u_status",2);
								}
								$actionReturn = $obju_OT->update($obju_OT->code,$obju_OT->rcdversion);
								if(!$actionReturn) break;
								
								$obju_History->prepareadd();
								$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
								$obju_History->name = $obju_History->code;
								$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
								$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
								$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
								$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
								$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
								$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
								$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
								if($actionReturn) $actionReturn = $obju_History->add();
								
								$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}
				} else if($page->getitemstring("triptype") == 2) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_LEAVE->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
								if($page->getitemstring("status") == 'A') {
									if($page->getcolumnstring("statusT1r".$i) == '0') {
										$obju_LEAVE->setudfvalue("u_status",1);
									}
								} else if($page->getitemstring("status") == 'D') {
									$obju_LEAVE->setudfvalue("u_status",2);
								}
								$actionReturn = $obju_LEAVE->update($obju_LEAVE->code,$obju_LEAVE->rcdversion);
								if(!$actionReturn) break;
								
								$obju_History->prepareadd();
								$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
								$obju_History->name = $obju_History->code;
								$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
								$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
								$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
								$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
								$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
								$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
								$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
								if($actionReturn) $actionReturn = $obju_History->add();
								
								$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 3) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_LOAN->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
								if($page->getitemstring("status") == 'A') {
									if($page->getcolumnstring("statusT1r".$i) == '0') {
										$obju_LOAN->setudfvalue("u_status",1);
									}
								} else if($page->getitemstring("status") == 'D') {
									$obju_LOAN->setudfvalue("u_status",2);
								}
								$actionReturn = $obju_LOAN->update($obju_LOAN->code,$obju_LOAN->rcdversion);
								if(!$actionReturn) break;
								
								$obju_History->prepareadd();
								$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
								$obju_History->name = $obju_History->code;
								$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
								$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
								$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
								$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
								$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
								$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
								$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
								if($actionReturn) $actionReturn = $obju_History->add();
								
								$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 4) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_TIMEADJ->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
								if($page->getitemstring("status") == 'A') {
									if($page->getcolumnstring("statusT1r".$i) == '0') {
										$obju_TIMEADJ->setudfvalue("u_status",1);
									}
								} else if($page->getitemstring("status") == 'D') {
									$obju_TIMEADJ->setudfvalue("u_status",2);
								}
								$actionReturn = $obju_TIMEADJ->update($obju_TIMEADJ->code,$obju_TIMEADJ->rcdversion);
								if(!$actionReturn) break;
								
								$obju_History->prepareadd();
								$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
								$obju_History->name = $obju_History->code;
								$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
								$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
								$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
								$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
								$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
								$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
								$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
								if($actionReturn) $actionReturn = $obju_History->add();
								
								$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 5) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_ASS->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
								if($page->getitemstring("status") == 'A') {
									if($page->getcolumnstring("statusT1r".$i) == '0') {
										$obju_ASS->setudfvalue("u_status",1);
									}
								} else if($page->getitemstring("status") == 'D') {
									$obju_ASS->setudfvalue("u_status",2);
								}
								
								$actionReturn = $obju_ASS->update($obju_ASS->code,$obju_ASS->rcdversion);
								if(!$actionReturn) break;
								
								$obju_History->prepareadd();
								$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
								$obju_History->name = $obju_History->code;
								$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
								$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
								$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
								$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
								$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
								$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
								$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
								if($actionReturn) $actionReturn = $obju_History->add();
								
								$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 6) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_OFF->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
								if($page->getitemstring("status") == 'A') {
									if($page->getcolumnstring("statusT1r".$i) == '0') {
										$obju_OFF->setudfvalue("u_status",1);
									}
								} else if($page->getitemstring("status") == 'D') {
									$obju_OFF->setudfvalue("u_status",2);
								}
								$actionReturn = $obju_OFF->update($obju_OFF->code,$obju_OFF->rcdversion);
								if(!$actionReturn) break;
								
								$obju_History->prepareadd();
								$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
								$obju_History->name = $obju_History->code;
								$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
								$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
								$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
								$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
								$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
								$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
								$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
								if($actionReturn) $actionReturn = $obju_History->add();
								
								$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 7) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_OFFH->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
							if($page->getitemstring("status") == 'A') {
								if($page->getcolumnstring("statusT1r".$i) == '0') {
									$obju_OFFH->setudfvalue("u_status",1);
								}
							} else if($page->getitemstring("status") == 'D') {
								$obju_OFFH->setudfvalue("u_status",2);
							}
							$actionReturn = $obju_OFFH->update($obju_OFFH->code,$obju_OFFH->rcdversion);
							if(!$actionReturn) break;
							
							$obju_History->prepareadd();
							$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
							$obju_History->name = $obju_History->code;
							$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
							$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
							$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
							$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
							$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
							$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
							$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
							if($actionReturn) $actionReturn = $obju_History->add();
							
							$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 8) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_OBH->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
							if($page->getitemstring("status") == 'A') {
								if($page->getcolumnstring("statusT1r".$i) == '0') {
									$obju_OBH->setudfvalue("u_status",1);
								}
							} else if($page->getitemstring("status") == 'D') {
								$obju_OBH->setudfvalue("u_status",2);
							}
							$actionReturn = $obju_OBH->update($obju_OBH->code,$obju_OBH->rcdversion);
							if(!$actionReturn) break;
							
							$obju_History->prepareadd();
							$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
							$obju_History->name = $obju_History->code;
							$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
							$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
							$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
							$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
							$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
							$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
							$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
							if($actionReturn) $actionReturn = $obju_History->add();
							
							$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 9) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_NOT->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
							if($page->getitemstring("status") == 'A') {
								if($page->getcolumnstring("statusT1r".$i) == '0') {
									$obju_NOT->setudfvalue("u_status",1);
								}
							} else if($page->getitemstring("status") == 'D') {
								$obju_NOT->setudfvalue("u_status",2);
							}
							$actionReturn = $obju_NOT->update($obju_NOT->code,$obju_NOT->rcdversion);
							if(!$actionReturn) break;
							
							$obju_History->prepareadd();
							$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
							$obju_History->name = $obju_History->code;
							$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
							$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
							$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
							$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
							$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
							$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
							$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
							if($actionReturn) $actionReturn = $obju_History->add();
							
							$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				} else if($page->getitemstring("triptype") == 10) {
					if($page->getcolumnstring("checkedT1r".$i) == "Y"){
						 if ($obju_OBA->getbysql("CODE='".$page->getcolumnstring("requestnoT1r".$i)."'")) {
							if($page->getitemstring("status") == 'A') {
								if($page->getcolumnstring("statusT1r".$i) == '0') {
									$obju_OBA->setudfvalue("u_status",1);
								}
							} else if($page->getitemstring("status") == 'D') {
								$obju_OBA->setudfvalue("u_status",2);
							}
							$actionReturn = $obju_OBA->update($obju_OBA->code,$obju_OBA->rcdversion);
							if(!$actionReturn) break;
							
							$obju_History->prepareadd();
							$obju_History->code = getNextIdByBranch($obju_History->dbtable,$objConnection);
							$obju_History->name = $obju_History->code;
							$obju_History->setudfvalue("u_requestno",$page->getcolumnstring("requestnoT1r".$i));
							$obju_History->setudfvalue("u_date",formatDateToDB($page->getcolumnstring("dateT1r".$i)));
							$obju_History->setudfvalue("u_status",$page->getitemstring("status"));
							$obju_History->setudfvalue("u_empid",$page->getcolumnstring("empidT1r".$i));
							$obju_History->setudfvalue("u_empname",$page->getcolumnstring("empnameT1r".$i));
							$obju_History->setudfvalue("u_remarks",$page->getcolumnstring("userremarksT1r".$i));
							$obju_History->setudfvalue("u_typeofrequest",$page->getcolumnstring("remarksT1r".$i).$page->getcolumnstring("typeofrequestT1r".$i));
							if($actionReturn) $actionReturn = $obju_History->add();
							
							$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
								if ($objRsGlobalSetting->recordcount() >= 1) {
									$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rEcopy.html');
									$objRsTo->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$_SESSION['userid']."'");
									if($objRsTo->queryfetchrow()) { $ToAddress = $objRsTo->fields[0]; }
									
									$objRsFrom->queryopen("SELECT u_email FROM u_hremploymentinfo WHERE code = '".$page->getcolumnstring("empidT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $CCAddress = $objRsFrom->fields[0]; }
									
									$objRsFrom->queryopen("SELECT b.u_email FROM u_tkapproverfileassigned a INNER JOIN u_hremploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.code = '".$page->getcolumnstring("approvercodeT1r".$i)."' AND a.u_stageno = '".$page->getcolumnstring("nextstagenoT1r".$i)."'");
									if($objRsFrom->queryfetchrow()) { $FromAddress = $objRsFrom->fields[0]; }
									
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
											$mail->AddCC($CCAddress);
										} else {
											$mail->AddAddress($CCAddress);
										}
										
										$mail->AddCC($ToAddress);
										
										$mail->IsHTML(true);
										
										$html_msg = str_replace("{Approver}",$_SESSION['username'],$html_msg);
										$html_msg = str_replace("{requestno}",$page->getcolumnstring("requestnoT1r".$i),$html_msg);
										$html_msg = str_replace("{empid}",$page->getcolumnstring("empidT1r".$i),$html_msg);
										$html_msg = str_replace("{empname}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{Requestor}",$page->getcolumnstring("empnameT1r".$i),$html_msg);
										$html_msg = str_replace("{rtype}",$page->getcolumnstring("typeofrequestT1r".$i),$html_msg);
										$html_msg = str_replace("{appdate}",$page->getcolumnstring("dateT1r".$i),$html_msg);
										$html_msg = str_replace("{remarks}",$page->getcolumnstring("remarksT1r".$i),$html_msg);
										
										if($page->getitemstring("status") == 'A') {
											if($page->getcolumnstring("statusT1r".$i) == '0') {
												$html_msg = str_replace("{cstatus}","Approved",$html_msg);				
											} else {
												$html_msg = str_replace("{cstatus}","Pending",$html_msg);
											}
										} else if($page->getitemstring("status") == 'D') {
											$html_msg = str_replace("{cstatus}","Rejected",$html_msg);
										}
										
										if($page->getitemstring("status") == 'A') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Approved";					
										} else if($page->getitemstring("status") == 'D') {
											$mail->Subject = "NOTIFICATION: REQUEST NO. ".$page->getcolumnstring("requestnoT1r".$i)." is Rejected";
										}
										
										$mail->Body = $html_msg;
										  
										if(!$mail->Send()) {
											return raiseError("Mailer Error: ".$mail->ErrorInfo);
										}
										
									} catch (phpmailerException $e) {
										return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
									}
								}
								
								
						} else return raiseError("Unable to find Request No.[".$page->getcolumnstring("requestnoT1r".$i)."].");		
					}	
				}
			} 
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

	$schema["dept"] = createSchemaUpper("dept");
	$schema["triptype"] = createSchemaUpper("triptype");
	$schema["status"] = createSchemaUpper("status");
	$schema["sdate"] = createSchemaDate("sdate");
	$schema["date1"] = createSchemaUpper("date1");
	$schema["date2"] = createSchemaUpper("date2");
	
	$schema["dept"]["attributes"] = "disabled";
	$schema["triptype"]["attributes"] = "disabled";
	$schema["date1"]["attributes"] = "disabled";
	$schema["date2"]["attributes"] = "disabled";
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("requestno");
	$objGrid->addcolumn("date");
	$objGrid->addcolumn("status");
	$objGrid->addcolumn("empid");
	$objGrid->addcolumn("empname");
	$objGrid->addcolumn("remarks");
	$objGrid->addcolumn("typeofrequest");
	$objGrid->addcolumn("userremarks");
	$objGrid->addcolumn("approvercode");
	$objGrid->addcolumn("nextstageno");
	
	$objGrid->columntitle("requestno","Request No.");
	$objGrid->columntitle("date","Request Date");
	$objGrid->columntitle("status","Status");
	$objGrid->columntitle("empid","ID");
	$objGrid->columntitle("empname","Name");
	$objGrid->columntitle("remarks","Remarks");
	$objGrid->columntitle("typeofrequest","Type of Request");
	$objGrid->columntitle("userremarks","Remarks");
	
	$objGrid->columnwidth("requestno",10);
	$objGrid->columnwidth("date",9);
	$objGrid->columnwidth("status",1);
	$objGrid->columnwidth("empid",8);
	$objGrid->columnwidth("empname",25);
	$objGrid->columnwidth("remarks",30);
	$objGrid->columnwidth("typeofrequest",12);
	$objGrid->columnwidth("userremarks",30);
	
	$objGrid->columninput("userremarks","type","text");
	
	$objGrid->columnvisibility("status",false);
	$objGrid->columnvisibility("approvercode",false);
	$objGrid->columnvisibility("nextstageno",false);
	
	$objGrid->selectionmode = 2;
	$objGrid->automanagecolumnwidth = false;
	
	$objGrid->width = 1150;
	$objGrid->height = 280;
	
	$type_req = $_GET["dates"];
	$req_date = $_GET["rtypes"];
	
	if($type_req != "") {
		$page->setitem("date1",formatDateToHttp($req_date));
		$page->setitem("date2",formatDateToHttp($req_date));
		$page->setitem("triptype",$type_req);
		$page->setitem("sdate",currentdate());
		
		$page->restoreSavedValues();
	} else {
		$page->restoreSavedValues();
	}
	
	function loadu_batch($p_selected) {
		$status1= array('A' => 'Approved','D' => 'Denied',);
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
	
	function loadu_trip($p_selected) {
		$status1= array('1' => 'Over Time','2' => 'Leave','3' => 'Loan','4' => 'Time Adjustment','5' => 'Official Business','6' => 'Off-set','7' => 'Off-set Hrs','8' => 'Official Business Hrs', '9' => 'Note', '10' => 'Official Business All');
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
	
	function loadu_dept($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("SELECT b.code,b.name FROM u_premploymentdeployment a INNER JOIN u_prprofitcenter b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_branch WHERE a.code = '".$_SESSION["userid"]."'");
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
		if (getInput("date1") != "" && getInput("date2") != "" ) {
			showRequestLists();
		}
	}

	function onElementClick(element,column,table,row) {
		var result;
		switch(table) {
			case "T1":
				switch (column) {
					case "checked":
						if (row==0) {
							if (isTableInputChecked(table,column)) { 
								checkedTableInput(table,column,-1);
								computeTotalFees();
							} else {
								uncheckedTableInput(table,column,-1); 
								computeTotalFees();
							}
						}	
						break;
				}
				break;
			default:
				break;
		}					
		return true;
	}
	
	function onFormSubmit(action) {
		if(action == "u_update"){
			if (isInputEmpty("triptype")) return false;
			if (isInputEmpty("sdate")) return false;
			if (isInputEmpty("status")) return false;
		}
		return true;
	}
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				if (elementFocused.substring(0,16)=="df_userremarksT1") {
					focusTableInput(table,"userremarks",row);
				}
				break;
		}		
		return params;
	}
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_triptype":
			case "df_date1":
			case "df_date2":
			case "df_sdate":
			case "df_dept":
			case "df_status":return false;
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
			inputs.push("status");
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
	
	function onElementValidate(element,column,table,row) {
		switch(table) {
			default:
				switch (column) {
					case "triptype":
					case "date1":
					case "date2":
					//case "sdate":
					case "dept":
					//case "status":
						if (getInput("date1") != "" && getInput("date2") != "" ) {
							showRequestLists();
						}
					break;
				}
				break;
		}				
		return true;
	}
	
	function showRequestLists(){
		var result,result2, data = new Array();
		clearTable("T1",true);
		if (getInput("triptype") == 1 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Date : ',DATE_FORMAT(a.u_otdate,'%m/%d/%Y'),' OT Time From',a.u_otfromtime,' & ',a.u_ottotime, ' Reason : ',a.u_otreason) as 'remarks','Over Time' as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory WHERE u_status = 'A' GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"' AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0");
			
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Over Time. Try Again");
				return false;
			}
			
		} else if (getInput("triptype") == 2 ) {
		result2 = page.executeFormattedQuery("CALL leave_running_balance('"+getGlobal("company")+"','"+getGlobal("branch")+"')");
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,if(ll.u_leavestatus = 'WP',CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'),' Remaining Balance :',ROUND(IFNULL(xx.balance,0),2)),CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y'))) as 'remarks',CONCAT(a.u_leavetype,' - ',a.u_leavereason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformleave a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 LEFT OUTER JOIN tmp_leavebalance xx ON xx.empid = a.u_empid AND xx.leavetype = a.u_leavetype LEFT OUTER JOIN u_tkleavemasterfiles ll ON ll.code = xx.leavetype WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_leavestatus = 'Successful' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Request Leave. Try Again");	
				return false;
			}
			
		} else if (getInput("triptype") == 3 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Loan Dates : ',DATE_FORMAT(a.u_loanfrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_loanto,'%m/%d/%y')) as 'remarks',CONCAT(u_loantype,' - ',u_loanreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Request Loan. Try Again");
				return false;
			}
		
		} else if (getInput("triptype") == 4 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date,'%M %d,%Y'),' ',IF(aa.u_type = 'I','Type : In','Type : Out'),' Time :',aa.u_time,'<br>') as 'remarks',CONCAT('Time Adj - ',u_tareason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformtimeadjustment a INNER JOIN u_tkrequestformtimeadjustmentitems aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0 AND a.u_tastatus = 'Successful' GROUP BY a.code");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Time Adjustment. Try Again");
				return false;
			}
		
		} else if (getInput("triptype") == 5 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y')) as 'remarks',CONCAT('Official Business',' - ',u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Official Business. Try Again");
				return false;
			}
		
		} else if (getInput("triptype") == 6 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date_filter,'%M %d,%Y'),' Type :',aa.u_type_filter,' Hr :',ROUND(aa.u_hours,2),'<br>') as 'remarks',CONCAT('Off-set',' - ',u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0 GROUP BY a.code");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Off-set. Try Again");
				return false;
			}
			
		} else if (getInput("triptype") == 7 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Work Hrs :',ROUND(a.u_offhrs,2),' Off-set Dates : ',DATE_FORMAT(a.u_offdate,'%m/%d/%y')) as 'remarks',CONCAT('Off-set',' - ',u_offreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformoffsethrs a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Off-Set Hrs. Try Again");
				return false;
			}
			
		} else if (getInput("triptype") == 8 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date_filter,'%M %d,%Y'),' Type :',aa.u_type_filter,' Hr :',ROUND(aa.u_hours,2),'<br>') as 'remarks',CONCAT('OB Hrs',' - ',a.u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformobset a INNER JOIN u_tkrequestformobsetlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0 GROUP BY a.code");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Official Business Hrs. Try Again");	
				return false;
			}

		} else if (getInput("triptype") == 9 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,CONCAT('Request Note :', a.u_note) as 'remarks','Request Note' as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformnote a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0 GROUP BY a.code");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Request Note. Try Again");
				return false;
			}
			
		} else if (getInput("triptype") == 10 ) {
		result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,IF(c.u_noofapp - IFNULL(c1.u_stageno,0),1,0) as vstatus,GROUP_CONCAT(DATE_FORMAT(aa.u_date_filter,'%M %d,%Y'),' Type :',IF(aa.u_type_filter = 'AM','OB - In',IF(aa.u_type_filter = 'PM','OB - Out','OB - Days')),' Hr :',ROUND(aa.u_ob_tot_hrs,2),'<br>') as 'remarks',CONCAT('OB All',' - ',a.u_assreason) as 'typeofrequest',c.code as 'approvercode',(c1.u_stageno+1) as 'nextstageno' FROM u_tkrequestformoball a INNER JOIN u_tkrequestformoballlist aa ON aa.company = a.company AND aa.branch = a.branch AND aa.code = a.code INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid LEFT JOIN (SELECT u_requestno,COUNT(code) as counters FROM u_tkapproverhistory GROUP BY u_requestno) as x ON x.u_requestno = a.code INNER JOIN u_tkapproverfile c ON c.company = b.company AND c.branch = b.branch AND c.code = b.u_employstatus2 INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company AND c1.branch = c.branch AND c1.code = c.code AND c1.u_stageno = IFNULL(x.counters,0)+1 WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND c1.u_stagename = '"+getGlobal("userid")+"'  AND a.u_appdate BETWEEN '"+formatDateToDB(getInput("date1"))+"' AND '"+formatDateToDB(getInput("date2"))+"' AND a.u_status = 0 GROUP BY a.code");
		
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						data["requestno"] = result.childNodes.item(xxxi).getAttribute("code");
						data["date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
						data["status"] = result.childNodes.item(xxxi).getAttribute("vstatus");
						data["empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
						data["empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
						data["remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
						data["typeofrequest"] = result.childNodes.item(xxxi).getAttribute("typeofrequest");
						data["userremarks"] = "";
						data["approvercode"] = result.childNodes.item(xxxi).getAttribute("approvercode");
						data["nextstageno"] = result.childNodes.item(xxxi).getAttribute("nextstageno");
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Official Business ALL. Try Again");	
				return false;
			}

			
		} else {
			page.statusbar.showError("Error retrieving For No Records. Try Again, if problem persists, check the connection.");	
			return false;
		}
		return true;
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
	  <td class="labelPageHeader" >&nbsp;Approved Form&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td width="168"><label <?php genCaptionHtml($schema["triptype"],"") ?>>Type of Request</label></td>
	  		<td  align=left><select <?php genSelectHtml($schema["triptype"],array("loadu_trip","","")) ?> ></select></td>
    </tr>
    <tr>
	  <td width="168"><label <?php genCaptionHtml($schema["status"],"") ?>>Status Type</label></td>
	  <td  align=left><select <?php genSelectHtml($schema["status"],array("loadu_batch","",":")) ?> ></select>
      		&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["sdate"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["dept"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["date1"]) ?> />
            <input type="hidden" size="12" <?php genInputTextHtml($schema["date2"]) ?> /></td>
	</tr>
    <tr>
	  <td width="168">&nbsp;</td>
	  <td align=left><a class="button" href="" onClick="formSubmit('u_update');return false;">Update</a></td>
	</tr>
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw(true) ?></td></tr>
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
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . $page->toolbar->generateQueryString(). "&toolbarscript=./GenericGridMasterDataToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
