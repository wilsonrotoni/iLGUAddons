<?php
 
	require_once("../common/phpmailer/class.phpmailer.php");
	include_once("./classes/emailsync.php");
	include("../common/mpdf56/mpdf.php");

function onBeforeAddEventmasterdataschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkapproverhistory":
			$objRsLastApproved = new recordset(null,$objConnection);
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			$objRsLastApproved->queryopen("SELECT a.code
											FROM u_hremploymentinfo a
											INNER JOIN u_premploymentinfo b ON b.company = a.company
											  AND b.branch = a.branch
											  AND b.code = a.code
											LEFT JOIN (SELECT u_empid,COUNT(code) as counters
														  FROM u_tkapproverhistory
															WHERE u_status = 'A'
															  AND u_requestno = '".$objTable->getudfvalue("u_requestno")."'
															  GROUP BY u_requestno) as x ON x.u_empid = a.code
											INNER JOIN u_tkapproverfile c ON c.company = b.company
											  AND c.branch = b.branch
											  AND c.code = b.u_employstatus2
							 				WHERE c.u_noofapp != IFNULL(x.counters,0)+1
											  AND a.code = '".$objTable->getudfvalue("u_empid")."'");
			if ($objRsLastApproved->recordcount() >= 1) {
				if ($objRsGlobalSetting->recordcount() >= 1) {
					if($_SESSION["userid"] != "") {
						$actionReturn = onCustomEventmasterdataschema_brEmailPushApproverGPSKiosk($objTable);
					} else {
						return raiseError('End of Session.. Please Log-Out');
					}
				}
			}
		break;
		case "u_tkrequestformoball":
			$objRs = new recordset(null,$objConnection);
			$objRsS = new recordset(null,$objConnection);
			$objRsV = new recordset(null,$objConnection);
			$obju_DTRx = new masterdataschema_br(null,$objConnection,"u_tktemporarydtr");
			$obju_DTRTime = new masterdataschema_br(null,$objConnection,"u_tktemporarydtrtime");
			$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");
			$obju_TimeKeeping = new masterdataschema_br(NULL,$objConnection,"u_tkattendanceentry");
			$objRsKioskLocked = new recordset(null,$objConnection);
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			$objRs->queryopen("SELECT u_date_filter,u_type_filter,LEFT(u_obfromtime,5) as u_obfromtime,LEFT(u_obtotime,5) as u_obtotime,u_ob_tot_hrs,u_ob_wd,u_ob_ot FROM u_tkrequestformoballlist WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND code = '".$objTable->code."'");
			if ($objRs->recordcount() > 0) {
				while ($objRs->queryfetchrow("NAME")) {
					$objRsKioskLocked->queryopen("SELECT b.u_kiosklocked 
													FROM u_premploymentinfo a 
													INNER JOIN u_prpayrollperioddates b ON b.company = a.company 
														AND b.branch = a.branch 
														AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency
													WHERE a.company = '".$_SESSION["company"]."' 
														AND a.branch = '".$_SESSION["company"]."' 
														AND a.code = '".$objTable->getudfvalue("u_empid")."'
														AND b.u_kiosklocked = 'LOCK'
														AND '".$objRs->fields["u_date_filter"]."' BETWEEN b.u_from_date AND b.u_to_date");
					if ($objRsKioskLocked->recordcount() > 0) {
						return raiseError($error_msg.' : This Period is already Locked.. Please Contact the Adminitrator or H.R.');
					}
					
					if ($objRs->fields["u_type_filter"] == "Days") {
						if ($objTable->getudfvalue("u_status") != -1) {
							$objRsV->queryopen("SELECT u_status FROM u_tkschedulecolumntorow WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."' AND u_empid = '".$objTable->getudfvalue("u_empid")."' AND CONCAT(u_year,'-',u_month,'-',IF(LENGTH(u_days) = 1,CONCAT('0',u_days),u_days)) = '".$objRs->fields["u_date_filter"]."'");
								if ($objRsV->recordcount() > 0) {
									while ($objRsV->queryfetchrow("NAME")) {
										if ($objRsV->fields["u_status"] == "Present") {
											return raiseError($error_msg.' : Date is already Present. Please Try Again Other Day');
										} else if ($objRsV->fields["u_status"] == "LWOP") {
											return raiseError($error_msg.' : Date is already Leave ( LWOP ). Please Try Again Other Day');
										} else if ($objRsV->fields["u_status"] == "WP") {
											return raiseError($error_msg.' : Date is already Leave ( WP ). Please Try Again Other Day');
										}
									}
								}
						  }
						  
						  if ($objTable->getudfvalue("u_status") == 1) {
							  	$codeid = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]);
								if ($obju_DTRx->getbysql("CODE='".$codeid."'")) {
									return raiseError($error_msg.' : Date is already Added to Temporary DTR. Please Try Again Other Day');
								} else {
									$obju_DTRx->code = $codeid;
									$obju_DTRx->name = $obju_DTRx->code;
									$obju_DTRx->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
									$obju_DTRx->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
									$obju_DTRx->setudfvalue("u_tkdate",$objRs->fields["u_date_filter"]);
									$obju_DTRx->setudfvalue("u_tk_wd",$objRs->fields["u_ob_wd"]);
									$obju_DTRx->setudfvalue("u_tk_excesstime",$objRs->fields["u_ob_ot"]);
									$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
									$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
									$obju_DTRx->setudfvalue("u_status","O");
									$obju_DTRx->setudfvalue("u_destination","O.B :".$objTable->code);
									$obju_DTRx->setudfvalue("u_lunch",0);
									$obju_DTRx->setudfvalue("u_snack",0);
									$actionReturn = $obju_DTRx->add();
									if(!$actionReturn) break;
									
										if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
											return raiseError($error_msg.' : This Date is already Added to Temporary DTR Time. Please Try Again Other Day');
										} else {
											$obju_DTRTime->prepareadd();
											$obju_DTRTime->code = $obju_DTRx->code;
											$obju_DTRTime->name = $obju_DTRTime->code;
											$obju_DTRTime->setudfvalue("u_dates",$objRs->fields["u_date_filter"]);
											$obju_DTRTime->setudfvalue("u_time_from",$objRs->fields["u_obfromtime"]);
											$obju_DTRTime->setudfvalue("u_time_to",$objRs->fields["u_obtotime"]);
											$obju_DTRTime->setudfvalue("u_time_lunch_from","00:00:00");
											$obju_DTRTime->setudfvalue("u_time_lunch_to","00:00:00");
											$obju_DTRTime->setudfvalue("u_time_snack_from","00:00:00");
											$obju_DTRTime->setudfvalue("u_time_snack_to","00:00:00");
											$actionReturn = $obju_DTRTime->add();
											if(!$actionReturn) break;
										}
								}
								
									if($actionReturn) {
										$objRsS->queryopen("SELECT code FROM u_tkschedulecolumntorow WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."' AND u_empid = '".$objTable->getudfvalue("u_empid")."' AND CONCAT(u_year,'-',u_month,'-',IF(LENGTH(u_days) = 1,CONCAT('0',u_days),u_days)) = '".$objRs->fields["u_date_filter"]."'");
										if ($objRsS->recordcount() > 0) {
											while ($objRsS->queryfetchrow("NAME")) {
												if ($obju_Schedule_Row->getbysql("CODE='".$objRsS->fields["code"]."'")) {
													$obju_Schedule_Row->setudfvalue("u_status","Present");
													$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
													if(!$actionReturn) break;
												}
											}
										} else {
											return raiseError($error_msg.' : Date has No Schedule. Please Try to Add Schedule');
										}
									}
							}
					} else if ($objRs->fields["u_type_filter"] == "PM") {
						 if ($objTable->getudfvalue("u_status") == 1) {
							$codepm = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]).str_replace(":","",$objRs->fields["u_obtotime"])."O";
							if ($obju_TimeKeeping->getbysql("CODE='".$codepm."'")) {
								
							} else {
								$obju_TimeKeeping->prepareadd();
								$obju_TimeKeeping->code = $codepm;
								$obju_TimeKeeping->name = $obju_TimeKeeping->code;
								$obju_TimeKeeping->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
								$obju_TimeKeeping->setudfvalue("u_biometrixid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_branch","000");
								$obju_TimeKeeping->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_date",$objRs->fields["u_date_filter"]);
								$obju_TimeKeeping->setudfvalue("u_time",$objRs->fields["u_obtotime"]);
								$obju_TimeKeeping->setudfvalue("u_type","O");
								$obju_TimeKeeping->setudfvalue("u_other","O");
								$obju_TimeKeeping->setudfvalue("u_filename","OB-PM");
								
								$actionReturn = $obju_TimeKeeping->add();
								if(!$actionReturn) break;
							}
						 }
					} else if ($objRs->fields["u_type_filter"] == "AM") {
						if ($objTable->getudfvalue("u_status") == 1) {
							$codepm = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]).str_replace(":","",$objRs->fields["u_obfromtime"])."I";
							if ($obju_TimeKeeping->getbysql("CODE='".$codepm."'")) {
								
							} else {
								$obju_TimeKeeping->prepareadd();
								$obju_TimeKeeping->code = $codepm;
								$obju_TimeKeeping->name = $obju_TimeKeeping->code;
								$obju_TimeKeeping->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
								$obju_TimeKeeping->setudfvalue("u_biometrixid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_branch","000");
								$obju_TimeKeeping->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_date",$objRs->fields["u_date_filter"]);
								$obju_TimeKeeping->setudfvalue("u_time",$objRs->fields["u_obfromtime"]);
								$obju_TimeKeeping->setudfvalue("u_type","I");
								$obju_TimeKeeping->setudfvalue("u_other","O");
								$obju_TimeKeeping->setudfvalue("u_filename","OB-AM");
								$actionReturn = $obju_TimeKeeping->add();
								if(!$actionReturn) break;
							}
						}
					}
				} 
			} else {
				return raiseError('No Data Records.. Please Try Again');
			}
			
			
			
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {
				if($_SESSION["userid"] != "") {
					$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
				} else {
					return raiseError('End of Session.. Please Log-Out');
				}
			}
		break;
		case "u_tkrequestformass":
			$objRs = new recordset(null,$objConnection);
			$objRsV = new recordset(null,$objConnection);
			$objRsT = new recordset(null,$objConnection);
			$objRsE = new recordset(null,$objConnection);
			$obju_DTRx = new masterdataschema_br(null,$objConnection,"u_tktemporarydtr");
			$obju_DTRTime = new masterdataschema_br(null,$objConnection,"u_tktemporarydtrtime");
			$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			if ($objTable->getudfvalue("u_tk_wd") == 0) {
				return raiseError($error_msg.'Error in Zero Working Hrs. Please Try Again');
			}
			
			$objRsKioskLocked = new recordset(null,$objConnection);
			$objRsKioskLocked->queryopen("SELECT b.u_kiosklocked 
											FROM u_premploymentinfo a 
											INNER JOIN u_prpayrollperioddates b ON b.company = a.company 
												AND b.branch = a.branch 
												AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency
											WHERE a.company = '".$_SESSION["company"]."' 
												AND a.branch = '".$_SESSION["company"]."' 
												AND a.code = '".$objTable->getudfvalue("u_empid")."'
												AND b.u_kiosklocked = 'LOCK'
												AND '".$objTable->getudfvalue("u_tkdate")."' BETWEEN b.u_from_date AND b.u_to_date");
			if ($objRsKioskLocked->recordcount() > 0) {
				return raiseError($error_msg.' : This Period is already Locked.. Please Contact the Adminitrator or H.R.');
			}
			
			$objRsE->queryopen("SELECT b.u_whpd
									FROM u_premploymentinfo b
									   WHERE b.company = '".$_SESSION["company"]."'
										AND b.branch = '".$_SESSION["branch"]."' 
										AND b.code = '".$objTable->getudfvalue("u_empid")."'");
			if ($objRsE->recordcount() > 0) {
				while ($objRsE->queryfetchrow("NAME")) {
					if ($objTable->getudfvalue("u_tk_wd") < $objRsE->fields["u_whpd"]) {
						return raiseError($error_msg.' : Error in Less in your Working Hrs. Please Try Again');
					}
				}
			} else {
				return raiseError($error_msg.' : Error Working Hrs is zero in to the Master File.. Please Try Again');
			}
			
			if ($objTable->getudfvalue("u_status") != -1) {
				$objRsV->queryopen("SELECT b.u_status,b.u_scheduleassign
									FROM u_tkschedulecolumntorow b
									   WHERE b.company = '".$_SESSION["company"]."'
										AND b.branch = '".$_SESSION["branch"]."' 
										AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'
										AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
										AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objTable->getudfvalue("u_tkdate")."'");
					if ($objRsV->recordcount() > 0) {
					while ($objRsV->queryfetchrow("NAME")) {
						if ($objRsV->fields["u_status"] == "Present") {
							return raiseError($error_msg.' : This Date is already Present. Please Try Again Other Day');
						} else if ($objRsV->fields["u_status"] == "LWOP") {
							return raiseError($error_msg.' : This Date is already Leave ( LWOP ). Please Try Again Other Day');
						} else if ($objRsV->fields["u_status"] == "WP") {
							return raiseError($error_msg.' : This Date is already Leave ( WP ). Please Try Again Other Day');
						} else {
							if ($objRsV->fields["u_scheduleassign"] == "") {
								return raiseError($error_msg.' : This Date is have No Schedule. Please Try Again Other Day');
							}
						}
					}
				} else {
					return raiseError($error_msg.' : This Date is have No Schedule Taging Please Try to Add Schedule');
				}
			}
			
			
			if ($objTable->getudfvalue("u_status") == 1) {
				if ($obju_DTRx->getbysql("CODE='".$objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objTable->getudfvalue("u_tkdate"))."'")) {
					$obju_DTRx->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
					$obju_DTRx->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
					$obju_DTRx->setudfvalue("u_tkdate",$objTable->getudfvalue("u_tkdate"));
					$obju_DTRx->setudfvalue("u_tk_wd",$objTable->getudfvalue("u_tk_wd"));
					$obju_DTRx->setudfvalue("u_tk_excesstime",$objTable->getudfvalue("u_tk_ot"));
					$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
					$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
					$obju_DTRx->setudfvalue("u_status","O");
					$obju_DTRx->setudfvalue("u_destination","O.B :".$objTable->code);
					$actionReturn = $obju_DTRx->update($obju_DTRx->code,$obju_DTRx->rcdversion);
					if(!$actionReturn) break;
					
						if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
							$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
							$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
							$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
							$actionReturn = $obju_DTRTime->update($obju_DTRTime->code,$obju_DTRTime->rcdversion);
							if(!$actionReturn) break;
						} else {
							$obju_DTRTime->prepareadd();
							$obju_DTRTime->code = $obju_DTRx->code;
							$obju_DTRTime->name = $obju_DTRTime->code;
							$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
							$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
							$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
							$actionReturn = $obju_DTRTime->add();
							if(!$actionReturn) break;
						}
				} else {
					$obju_DTRx->code = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objTable->getudfvalue("u_tkdate")); //a.u_profitcenter,a.u_empid,REPLACE(b.u_tkdate,'-','')
					$obju_DTRx->name = $obju_DTRx->code;
					$obju_DTRx->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
					$obju_DTRx->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
					$obju_DTRx->setudfvalue("u_tkdate",$objTable->getudfvalue("u_tkdate"));
					$obju_DTRx->setudfvalue("u_tk_wd",$objTable->getudfvalue("u_tk_wd"));
					$obju_DTRx->setudfvalue("u_tk_excesstime",$objTable->getudfvalue("u_tk_ot"));
					$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
					$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
					$obju_DTRx->setudfvalue("u_status","O");
					$obju_DTRx->setudfvalue("u_destination","O.B :".$objTable->code);
					$actionReturn = $obju_DTRx->add();	
					if(!$actionReturn) break;
					
						if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
							$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
							$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
							$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
							$actionReturn = $obju_DTRTime->update($obju_DTRTime->code,$obju_DTRTime->rcdversion);
							if(!$actionReturn) break;
						} else {
							$obju_DTRTime->prepareadd();
							$obju_DTRTime->code = $obju_DTRx->code;
							$obju_DTRTime->name = $obju_DTRTime->code;
							$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
							$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
							$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
							$actionReturn = $obju_DTRTime->add();
							if(!$actionReturn) break;
						}
				}
				
					if($actionReturn) {
						$objRs->queryopen("SELECT b.code
										FROM u_tkschedulecolumntorow b
										WHERE b.company = '".$_SESSION["company"]."'
										  AND b.branch = '".$_SESSION["branch"]."' 
										  AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'
										  AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
										  AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objTable->getudfvalue("u_tkdate")."'");
						if ($objRs->recordcount() > 0) {
							while ($objRs->queryfetchrow("NAME")) {
								if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
									$obju_Schedule_Row->setudfvalue("u_status","Present");
									$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
								}
							}
						} else {
							return raiseError($error_msg.' : This Date is have No Schedule Taging Please Try to Add Schedule');
						}
					}
			}
			
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {
				if($_SESSION["userid"] != "") {
					$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
				} else {
					return raiseError('End of Session.. Please Log-Out');
				}
			}
		break;
		case "u_tkrequestformot":
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			if ($objTable->getudfvalue("u_othrs") == 0) {
				return raiseError($error_msg.' : Error in Zero OT Hrs. Please Try Again');
			}
			
			$objRsKioskLocked = new recordset(null,$objConnection);
			$objRsKioskLocked->queryopen("SELECT b.u_kiosklocked 
											FROM u_premploymentinfo a 
											INNER JOIN u_prpayrollperioddates b ON b.company = a.company 
												AND b.branch = a.branch 
												AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency
											WHERE a.company = '".$_SESSION["company"]."' 
												AND a.branch = '".$_SESSION["company"]."' 
												AND a.code = '".$objTable->getudfvalue("u_empid")."'
												AND b.u_kiosklocked = 'LOCK'
												AND '".$objTable->getudfvalue("u_otdate")."' BETWEEN b.u_from_date AND b.u_to_date");
			if ($objRsKioskLocked->recordcount() > 0) {
				return raiseError($error_msg.' : This Period is already Locked.. Please Contact the Adminitrator or H.R.');
			}
			
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {
				if($_SESSION["userid"] != "") {
					$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
				} else {
					return raiseError('End of Session.. Please Log-Out');
				}
			}
		break;
		case "u_tkrequestformtimeadjustment":
			$objRs = new recordset(null,$objConnection);
			$objRs2 = new recordset(null,$objConnection);
			$obju_TimeKeeping = new masterdataschema_br(NULL,$objConnection,"u_tkattendanceentry");
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			$objRs2->queryopen("SELECT code FROM u_tkrequestformtimeadjustmentitems WHERE code = '$objTable->code'");
			if ($objRs2->recordcount() == 0) {
				return raiseError($error_msg.' : Error No Time Adjustment Details');
			}
			
			if ($objTable->getudfvalue("u_status") == 1) {
				$objRs->queryopen("SELECT CONCAT(REPLACE(b.u_date,'-',''),REPLACE(b.u_time,':','')) as code,
											   b.u_date,
											   b.u_time,
											   b.u_type
											FROM u_tkrequestformtimeadjustmentitems b
												WHERE b.code = '$objTable->code'");
					while ($objRs->queryfetchrow("NAME")) {
						if ($obju_TimeKeeping->getbysql("CODE='".$objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"]."'")) {
							
						} else {
							$obju_TimeKeeping->prepareadd();
							$obju_TimeKeeping->code = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"];
							$obju_TimeKeeping->name = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"];
							$obju_TimeKeeping->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
							$obju_TimeKeeping->setudfvalue("u_biometrixid",$objTable->getudfvalue("u_biometrixid"));
							$obju_TimeKeeping->setudfvalue("u_branch",$objTable->getudfvalue("u_branches"));
							$obju_TimeKeeping->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
							$obju_TimeKeeping->setudfvalue("u_date",$objRs->fields["u_date"]);
							$obju_TimeKeeping->setudfvalue("u_time",$objRs->fields["u_time"]);
							$obju_TimeKeeping->setudfvalue("u_type",$objRs->fields["u_type"]);
							$obju_TimeKeeping->setudfvalue("u_other","O");
							$obju_TimeKeeping->setudfvalue("u_filename","TA-R");
							
							$actionReturn = $obju_TimeKeeping->add();
							if(!$actionReturn) break;
						}
					}
			}
			
			if($objTable->getudfvalue("u_tastatus") == "Successful") {
				$objRsGlobalSetting = new recordset(null,$objConnection);
				$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
				if ($objRsGlobalSetting->recordcount() >= 1) {
					if($_SESSION["userid"] != "") {
						$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
					} else {
						return raiseError('End of Session.. Please Log-Out');
					}
				}
			}
		break;
		case "u_tkrequestformloan":
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {
				if($_SESSION["userid"] != "") {
					$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
				} else {
					return raiseError('End of Session.. Please Log-Out');
				}
			}
		break;
		case "u_tkrequestformleave":
			$objRs = new recordset(null,$objConnection);
			$objRs2 = new recordset(null,$objConnection);
			$objRs3 = new recordset(null,$objConnection);
			$objRs4 = new recordset(null,$objConnection);
			$objRsKioskLocked = new recordset(null,$objConnection);
			$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			$objRs2->queryopen("SELECT u_date_filter FROM u_tkrequestformleavegrid WHERE company = '".$_SESSION["company"]."'  AND branch = '".$_SESSION["branch"]."' AND code = '$objTable->code'");
				if ($objRs2->recordcount() > 0) {
					while ($objRs2->queryfetchrow("NAME")) {
						$objRsKioskLocked->queryopen("SELECT b.u_kiosklocked 
														FROM u_premploymentinfo a 
														INNER JOIN u_prpayrollperioddates b ON b.company = a.company 
															AND b.branch = a.branch 
															AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency
														WHERE a.company = '".$_SESSION["company"]."' 
															AND a.branch = '".$_SESSION["company"]."' 
															AND a.code = '".$objTable->getudfvalue("u_empid")."'
															AND b.u_kiosklocked = 'LOCK'
															AND '".$objRs2->fields["u_date_filter"]."' BETWEEN b.u_from_date AND b.u_to_date");
						if ($objRsKioskLocked->recordcount() > 0) {
							return raiseError($error_msg.' : This Period is already Locked.. Please Contact the Adminitrator or H.R.');
						}
						
						$objRs3->queryopen("SELECT a.code FROM u_tkrequestformleavegrid a INNER JOIN u_tkrequestformleave b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.company = '".$_SESSION["company"]."'  AND a.branch = '".$_SESSION["branch"]."' AND b.u_status IN(0,1) AND a.u_date_filter = '".$objRs2->fields["u_date_filter"]."' AND b.u_empid = '".$objTable->getudfvalue("u_empid")."' AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'");
							if ($objRs3->recordcount() != 0) {
								return raiseError($error_msg.' : This Date ['.$objRs2->fields["u_date_filter"].'] is already Added 2 Another Leave Request');
							}
						
						$objRs4->queryopen("SELECT b.code FROM u_tkschedulecolumntorow b WHERE b.company = '".$_SESSION["company"]."'  AND b.branch = '".$_SESSION["branch"]."' AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objRs2->fields["u_date_filter"]."' AND b.u_empid = '".$objTable->getudfvalue("u_empid")."' AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."' AND b.u_scheduleassign like '%RESTDAY%'");
							if ($objRs4->recordcount() != 0) {
								return raiseError($error_msg.' : This Date ['.$objRs2->fields["u_date_filter"].'] is your Restday.. Cannot Add Leave on your Rest Day');
							}
					}
				} else {
					return raiseError('Error No Leave Details');
				}
			
			if ($objTable->getudfvalue("u_status") == 1) {
				$objRs->queryopen("SELECT IFNULL(b.code,'X') as code,bb.u_scheduleassign
									FROM u_tkrequestformleavegrid bb
									LEFT OUTER JOIN u_tkschedulecolumntorow b ON b.company = bb.company
										AND b.branch = bb.branch
										AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
										AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'
										AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = bb.u_date_filter
									WHERE bb.code = '$objTable->code'");
						while ($objRs->queryfetchrow("NAME")) {
							if($objRs->fields["code"] != 'X') {
								if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
									$obju_Schedule_Row->setudfvalue("u_scheduleassign",$objRs->fields["u_scheduleassign"]);
									$obju_Schedule_Row->setudfvalue("u_status",$objTable->getudfvalue("u_leavedaystatus"));
									$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
									if (!$actionReturn) break;
								}
							}
						}
			}
			
			if($objTable->getudfvalue("u_leavestatus") == "Successful") {
				$objRsGlobalSetting = new recordset(null,$objConnection);
				$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
				if ($objRsGlobalSetting->recordcount() >= 1) {
					if($_SESSION["userid"] != "") {
						if($actionReturn) $actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
					} else {
						return raiseError('End of Session.. Please Log-Out');
					}
				}
			}
			break;
		case "u_tkrequestformoffset":
			$objRsItems = new recordset(null,$objConnection);
			$objRsGlobalConditions = new recordset(null,$objConnection);
			$objRsItemsConditions = new recordset(null,$objConnection);
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$objRsKioskLocked = new recordset(null,$objConnection);
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {
				if($_SESSION["userid"] != "") {
					$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
				} else {
					return raiseError('End of Session.. Please Log-Out');
				}
			}
			
			$objRsItems->queryopen("SELECT a.u_date_filter FROM u_tkrequestformoffsetlist a WHERE a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='$objTable->code'");
				while ($objRsItems->queryfetchrow("NAME")) {
					$objRsKioskLocked->queryopen("SELECT b.u_kiosklocked 
													FROM u_premploymentinfo a 
													INNER JOIN u_prpayrollperioddates b ON b.company = a.company 
														AND b.branch = a.branch 
														AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency
													WHERE a.company = '".$_SESSION["company"]."' 
														AND a.branch = '".$_SESSION["company"]."' 
														AND a.code = '".$objTable->getudfvalue("u_empid")."'
														AND b.u_kiosklocked = 'LOCK'
														AND '".$objRsItems->fields["u_date_filter"]."' BETWEEN b.u_from_date AND b.u_to_date");
					if ($objRsKioskLocked->recordcount() > 0) {
						return raiseError($error_msg.' : This Period is already Locked.. Please Contact the Adminitrator or H.R.');
					}
					
					$objRsGlobalConditions->queryopen("SELECT a.u_offset_con,(-1*a.u_offset_consecutive) as cons FROM u_prglobalsetting a WHERE a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' AND a.u_offset_con != 0");
						if ($objRsGlobalConditions->recordcount() == 0) {
							while ($objRsGlobalConditions->queryfetchrow("NAME")) {
								$objRsItemsConditions->queryopen("SELECT IFNULL(x.amt,0) as amtx
														FROM (SELECT SUM(b.u_hours) as amt
														FROM u_tkrequestformoffset a
														INNER JOIN u_tkrequestformoffsetlist b ON b.company = a.company
														  AND b.branch = a.branch
														  AND b.code = a.code
														WHERE a.company='".$_SESSION["company"]."' 
														  AND a.branch='".$_SESSION["branch"]."'
														  AND a.u_empid = '".$objTable->getudfvalue("u_empid")."'
														  AND a.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'
														  AND b.u_date_filter BETWEEN DATE_ADD('".$objRsItems->fields["u_date_filter"]."',INTERVAL ".$objRsGlobalConditions->fields["cons"]." DAY) AND '".$objRsItems->fields["u_date_filter"]."') as x");
									while ($objRsItemsConditions->queryfetchrow("NAME")) {
										if(($objRsGlobalConditions->fields["u_offset_con"]-$objRsItemsConditions->fields["amtx"]) < 0) {
											return raiseError($error_msg.' : Max Offset Consecutive. For This Date : '.$objRsItems->fields["u_date_filter"]);
										}
									}
							}
						}
				}
			break;
		case "u_tkrequestformobset":
			$objRsGlobalSetting = new recordset(null,$objConnection);
			$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
			if ($objRsGlobalSetting->recordcount() >= 1) {
				if($_SESSION["userid"] != "") {
					$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
				} else {
					return raiseError('End of Session.. Please Log-Out');
				}
			}
			break;
	}
	return $actionReturn;
}


/*
function onAddEventmasterdataschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {	
		case "u_tkrequestformleave":
		break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventmasterdataschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkrequestformleave":
			$objRs = new recordset(null,$objConnection);
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			if($objTable->getudfvalue("u_status") != -1) {
				if($objTable->getudfvalue("u_leavestatus") == "Successful") {
					$objRsGlobalSetting = new recordset(null,$objConnection);
					$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
					if ($objRsGlobalSetting->recordcount() >= 1) {
						if($objTable->getudfvalue("u_status") == 0) {
							$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
						}
					}
				}
			}
			break;
		case "u_tkapproverfile":
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("SELECT GROUP_CONCAT(CONCAT(a.u_stageno,' - ',b.name) SEPARATOR ' / ') as name FROM u_tkapproverfileassigned a INNER JOIN u_premploymentinfo b ON b.code = a.u_stagename WHERE a.code = '".$objTable->code."'");
					while ($objRs->queryfetchrow("NAME")) {
						//var_dump($objRs->fields["name"]);
						$objTable->name = $objRs->fields["name"];	
					}
				
			break;
	}
	return $actionReturn;
}



function onUpdateEventmasterdataschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkrequestformleave":
			if ($objTable->getudfvalue("u_status") == 1) {
				$objRs = new recordset(null,$objConnection);
				$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");	
				$objRs->queryopen("SELECT IFNULL(b.code,'X') as code,bb.u_scheduleassign
									FROM u_tkrequestformleavegrid bb
									LEFT OUTER JOIN u_tkschedulecolumntorow b ON b.company = bb.company
										AND b.branch = bb.branch
										AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
										AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = bb.u_date_filter
									WHERE bb.code = '$objTable->code'");
					if ($objRs->recordcount() > 0) {
						while ($objRs->queryfetchrow("NAME")) {
							if($objRs->fields["code"] != 'X') {
								if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
									$obju_Schedule_Row->setudfvalue("u_scheduleassign",$objRs->fields["u_scheduleassign"]);
									$obju_Schedule_Row->setudfvalue("u_status",$objTable->getudfvalue("u_leavedaystatus"));
									$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
									if (!$actionReturn) break;
								}
							}
						}
					}
			}
			
			if ($objTable->getudfvalue("u_status") == -1) {
				$objRs = new recordset(null,$objConnection);
				$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");	
				$objRs->queryopen("SELECT IFNULL(b.code,'X') as code,bb.u_scheduleassign
									FROM u_tkrequestformleavegrid bb
									LEFT OUTER JOIN u_tkschedulecolumntorow b ON b.company = bb.company
										AND b.branch = bb.branch
										AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
										AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = bb.u_date_filter
									WHERE bb.code = '$objTable->code'");
					if ($objRs->recordcount() > 0) {
						while ($objRs->queryfetchrow("NAME")) {
							if($objRs->fields["code"] != 'X') {
								if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
									$obju_Schedule_Row->setudfvalue("u_scheduleassign","");
									$obju_Schedule_Row->setudfvalue("u_status","Absent");
									$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
									if (!$actionReturn) break;
								}
							}
						}
					}
			}
			break;
		case "u_tkrequestformloan":
			if ($objTable->getudfvalue("u_status") == 1) {
				$obju_MasterEDA = new masterdataschema_br(null,$objConnection,"u_prdeductionentry");
				$obju_MasterEDA_deductions = new masterdatalinesschema_br(null,$objConnection,"u_prdeductionentryitems");
				$code = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid");
					if ($obju_MasterEDA->getbysql("CODE='".$code."'")) {
						$obju_MasterEDA_deductions->prepareadd();
						$obju_MasterEDA_deductions->code = $code;
						$obju_MasterEDA_deductions->lineid = getNextIdByBranch("u_prdeductionentryitems",$objConnection);
						$obju_MasterEDA_deductions->setudfvalue("u_deductiontype",$objTable->getudfvalue("u_loantype"));
						$obju_MasterEDA_deductions->setudfvalue("u_sdate",$objTable->getudfvalue("u_loanfrom"));
						$obju_MasterEDA_deductions->setudfvalue("u_enddate",$objTable->getudfvalue("u_loanto"));
						$obju_MasterEDA_deductions->setudfvalue("u_amount",$objTable->getudfvalue("u_balance"));
						$obju_MasterEDA_deductions->setudfvalue("u_amortamt",$objTable->getudfvalue("u_amort"));
						$obju_MasterEDA_deductions->setudfvalue("u_balance",$objTable->getudfvalue("u_balance"));
						$obju_MasterEDA_deductions->setudfvalue("u_entrydate",$objTable->getudfvalue("u_appdate"));
						$obju_MasterEDA_deductions->setudfvalue("u_ded1st",1);
						$obju_MasterEDA_deductions->setudfvalue("u_ded2nd",1);
						$obju_MasterEDA_deductions->setudfvalue("u_dedhold",0);
						$obju_MasterEDA_deductions->setudfvalue("u_dedrefno",$objTable->code);

						if ($actionReturn) $actionReturn = $obju_MasterEDA_deductions->add(); 
						if (!$actionReturn) break;
						
						if ($actionReturn) $actionReturn = $obju_MasterEDA->update($obju_MasterEDA->code,$obju_MasterEDA->rcdversion);
						if (!$actionReturn) break;
					} else {
						$obju_MasterEDA->prepareadd();
						$obju_MasterEDA->code = $code;
						$obju_MasterEDA->name = $objTable->getudfvalue("u_empname");
						$obju_MasterEDA->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
						$obju_MasterEDA->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
						$obju_MasterEDA->setudfvalue("u_dedchkno",0);
						if ($actionReturn) $actionReturn = $obju_MasterEDA->add();
						if (!$actionReturn) break;
						
						$obju_MasterEDA_deductions->prepareadd();
						$obju_MasterEDA_deductions->code = $code;
						$obju_MasterEDA_deductions->lineid = getNextIdByBranch("u_prdeductionentryitems",$objConnection);
						$obju_MasterEDA_deductions->setudfvalue("u_deductiontype",$objTable->getudfvalue("u_loantype"));
						$obju_MasterEDA_deductions->setudfvalue("u_sdate",$objTable->getudfvalue("u_loanfrom"));
						$obju_MasterEDA_deductions->setudfvalue("u_enddate",$objTable->getudfvalue("u_loanto"));
						$obju_MasterEDA_deductions->setudfvalue("u_amount",$objTable->getudfvalue("u_balance"));
						$obju_MasterEDA_deductions->setudfvalue("u_amortamt",$objTable->getudfvalue("u_amort"));
						$obju_MasterEDA_deductions->setudfvalue("u_balance",$objTable->getudfvalue("u_balance"));
						$obju_MasterEDA_deductions->setudfvalue("u_entrydate",$objTable->getudfvalue("u_appdate"));
						$obju_MasterEDA_deductions->setudfvalue("u_ded1st",1);
						$obju_MasterEDA_deductions->setudfvalue("u_ded2nd",1);
						$obju_MasterEDA_deductions->setudfvalue("u_dedhold",0);
						$obju_MasterEDA_deductions->setudfvalue("u_dedrefno",$objTable->code);

						if ($actionReturn) $actionReturn = $obju_MasterEDA_deductions->add(); 
						if (!$actionReturn) break;
					}
				
			}
			break;
		case "u_tkrequestformoball":
			$objRs = new recordset(null,$objConnection);
			$objRsS = new recordset(null,$objConnection);
			$objRsV = new recordset(null,$objConnection);
			$obju_DTRx = new masterdataschema_br(null,$objConnection,"u_tktemporarydtr");
			$obju_DTRTime = new masterdataschema_br(null,$objConnection,"u_tktemporarydtrtime");
			$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");
			$obju_TimeKeeping = new masterdataschema_br(NULL,$objConnection,"u_tkattendanceentry");
			$error_msg = 'Request No. ['.$objTable->code.']';
			
			$objRs->queryopen("SELECT u_date_filter,u_type_filter,LEFT(u_obfromtime,5) as u_obfromtime,LEFT(u_obtotime,5) as u_obtotime,u_ob_tot_hrs,u_ob_wd,u_ob_ot FROM u_tkrequestformoballlist WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND code = '".$objTable->code."'");
			if ($objRs->recordcount() > 0) {
				while ($objRs->queryfetchrow("NAME")) {
					if ($objRs->fields["u_type_filter"] == "Days") {
						if ($objTable->getudfvalue("u_status") != -1) {
							$objRsV->queryopen("SELECT u_status FROM u_tkschedulecolumntorow WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."' AND u_empid = '".$objTable->getudfvalue("u_empid")."' AND CONCAT(u_year,'-',u_month,'-',IF(LENGTH(u_days) = 1,CONCAT('0',u_days),u_days)) = '".$objRs->fields["u_date_filter"]."'");
								if ($objRsV->recordcount() > 0) {
									while ($objRsV->queryfetchrow("NAME")) {
										if ($objRsV->fields["u_status"] == "Present") {
											return raiseError($error_msg.' : Date is already Present. Please Try Again Other Day');
										} else if ($objRsV->fields["u_status"] == "LWOP") {
											return raiseError($error_msg.' : Date is already Leave ( LWOP ). Please Try Again Other Day');
										} else if ($objRsV->fields["u_status"] == "WP") {
											return raiseError($error_msg.' : Date is already Leave ( WP ). Please Try Again Other Day');
										}
									}
								}
						  }
						  
						  if ($objTable->getudfvalue("u_status") == 1) {
							  	$codeid = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]);
								if ($obju_DTRx->getbysql("CODE='".$codeid."'")) {
									return raiseError($error_msg.' : Date is already Added to Temporary DTR. Please Try Again Other Day');
								} else {
									$obju_DTRx->code = $codeid;
									$obju_DTRx->name = $obju_DTRx->code;
									$obju_DTRx->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
									$obju_DTRx->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
									$obju_DTRx->setudfvalue("u_tkdate",$objRs->fields["u_date_filter"]);
									$obju_DTRx->setudfvalue("u_tk_wd",$objRs->fields["u_ob_wd"]);
									$obju_DTRx->setudfvalue("u_tk_excesstime",$objRs->fields["u_ob_ot"]);
									$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
									$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
									$obju_DTRx->setudfvalue("u_status","O");
									$obju_DTRx->setudfvalue("u_destination","O.B :".$objTable->code);
									$obju_DTRx->setudfvalue("u_lunch",0);
									$obju_DTRx->setudfvalue("u_snack",0);
									$actionReturn = $obju_DTRx->add();
									if(!$actionReturn) break;
									
										if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
											return raiseError($error_msg.' : Date is already Added to Temporary DTR Time. Please Try Again Other Day');
										} else {
											$obju_DTRTime->prepareadd();
											$obju_DTRTime->code = $obju_DTRx->code;
											$obju_DTRTime->name = $obju_DTRTime->code;
											$obju_DTRTime->setudfvalue("u_dates",$objRs->fields["u_date_filter"]);
											$obju_DTRTime->setudfvalue("u_time_from",$objRs->fields["u_obfromtime"]);
											$obju_DTRTime->setudfvalue("u_time_to",$objRs->fields["u_obtotime"]);
											$obju_DTRTime->setudfvalue("u_time_lunch_from","");
											$obju_DTRTime->setudfvalue("u_time_lunch_to","");
											$obju_DTRTime->setudfvalue("u_time_snack_from","");
											$obju_DTRTime->setudfvalue("u_time_snack_to","");
											$actionReturn = $obju_DTRTime->add();
											if(!$actionReturn) break;
										}
								}
								
									if($actionReturn) {
										$objRsS->queryopen("SELECT code FROM u_tkschedulecolumntorow WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."' AND u_empid = '".$objTable->getudfvalue("u_empid")."' AND CONCAT(u_year,'-',u_month,'-',IF(LENGTH(u_days) = 1,CONCAT('0',u_days),u_days)) = '".$objRs->fields["u_date_filter"]."'");
											if ($objRsS->recordcount() > 0) {
												while ($objRsS->queryfetchrow("NAME")) {
													if ($obju_Schedule_Row->getbysql("CODE='".$objRsS->fields["code"]."'")) {
														$obju_Schedule_Row->setudfvalue("u_status","Present");
														$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
														if(!$actionReturn) break;
													}
												}
											} else {
												return raiseError($error_msg.' : Date has No Schedule. Please Try to Add Schedule');
											}
									}
							}
							
							if ($objTable->getudfvalue("u_status") == -1) {
								$codeid = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]);
								if ($obju_DTRx->getbysql("CODE='".$codeid."'")) {
										if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
											$actionReturn = $obju_DTRTime->delete();
											if(!$actionReturn) break;
										} else {
											return raiseError("Can't Find DTR Times");
										}
									
									if($actionReturn) $actionReturn = $obju_DTRx->delete();
									if(!$actionReturn) break;
									
								} else {
									return raiseError("Can't Find DTR Days");
								}
								
								if($actionReturn) {
									$objRsS->queryopen("SELECT code FROM u_tkschedulecolumntorow WHERE company = '".$_SESSION["company"]."' AND branch = '".$_SESSION["branch"]."' AND u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."' AND u_empid = '".$objTable->getudfvalue("u_empid")."' AND CONCAT(u_year,'-',u_month,'-',IF(LENGTH(u_days) = 1,CONCAT('0',u_days),u_days)) = '".$objRs->fields["u_date_filter"]."'");
									while ($objRsS->queryfetchrow("NAME")) {
										if ($obju_Schedule_Row->getbysql("CODE='".$objRsS->fields["code"]."'")) {
											$obju_Schedule_Row->setudfvalue("u_status","Absent");
											$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
										}
									}
								}	
							}
					} else if ($objRs->fields["u_type_filter"] == "PM") {
						 if ($objTable->getudfvalue("u_status") == 1) {
							$codepm = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]).str_replace(":","",$objRs->fields["u_obtotime"])."O";
							if ($obju_TimeKeeping->getbysql("CODE='".$codepm."'")) {
								
							} else {
								$obju_TimeKeeping->prepareadd();
								$obju_TimeKeeping->code = $codepm;
								$obju_TimeKeeping->name = $obju_TimeKeeping->code;
								$obju_TimeKeeping->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
								$obju_TimeKeeping->setudfvalue("u_biometrixid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_branch","000");
								$obju_TimeKeeping->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_date",$objRs->fields["u_date_filter"]);
								$obju_TimeKeeping->setudfvalue("u_time",$objRs->fields["u_obtotime"]);
								$obju_TimeKeeping->setudfvalue("u_type","O");
								$obju_TimeKeeping->setudfvalue("u_other","O");
								$obju_TimeKeeping->setudfvalue("u_filename","OB-PM");
								
								$actionReturn = $obju_TimeKeeping->add();
								if(!$actionReturn) break;
							}
						 }
						 
						 if ($objTable->getudfvalue("u_status") == -1) {
							 $codepm = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]).str_replace(":","",$objRs->fields["u_obtotime"])."O";
							if($obju_TimeKeeping->getbysql("CODE='".$codepm."' AND U_FILENAME = 'OB-PM'")) {
										$actionReturn = $obju_TimeKeeping->delete();
										if(!$actionReturn) break;
									} else {
										return raiseError($error_msg." : Can't Find Attendance Entry [ Official Business PM ]");
									}
						 }
					} else if ($objRs->fields["u_type_filter"] == "AM") {
						if ($objTable->getudfvalue("u_status") == 1) {
							$codepm = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]).str_replace(":","",$objRs->fields["u_obfromtime"])."I";
							if ($obju_TimeKeeping->getbysql("CODE='".$codepm."'")) {
								
							} else {
								$obju_TimeKeeping->prepareadd();
								$obju_TimeKeeping->code = $codepm;
								$obju_TimeKeeping->name = $obju_TimeKeeping->code;
								$obju_TimeKeeping->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
								$obju_TimeKeeping->setudfvalue("u_biometrixid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_branch","000");
								$obju_TimeKeeping->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
								$obju_TimeKeeping->setudfvalue("u_date",$objRs->fields["u_date_filter"]);
								$obju_TimeKeeping->setudfvalue("u_time",$objRs->fields["u_obfromtime"]);
								$obju_TimeKeeping->setudfvalue("u_type","I");
								$obju_TimeKeeping->setudfvalue("u_other","O");
								$obju_TimeKeeping->setudfvalue("u_filename","OB-AM");
								
								$actionReturn = $obju_TimeKeeping->add();
								if(!$actionReturn) break;
							}
						}
						
						if ($objTable->getudfvalue("u_status") == -1) {
							 $codepm = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objRs->fields["u_date_filter"]).str_replace(":","",$objRs->fields["u_obfromtime"])."I";
							if($obju_TimeKeeping->getbysql("CODE='".$codepm."' AND U_FILENAME = 'OB-AM'")) {
										$actionReturn = $obju_TimeKeeping->delete();
										if(!$actionReturn) break;
									} else {
										return raiseError($error_msg." : Can't Find Attendance Entry [ Official Business AM ]");
									}
						 }
					}
				} 
			} else {
				return raiseError('No Data Records.. Please Try Again');
			}
			
			if($objTable->getudfvalue("u_status") != -1) {
				$objRsGlobalSetting = new recordset(null,$objConnection);
				$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
				if ($objRsGlobalSetting->recordcount() >= 1) {
					if($_SESSION["userid"] != "") {
						if($objTable->getudfvalue("u_status") == 0) {
							$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
						}
					} else {
						return raiseError('End of Session.. Please Log-Out');
					}
				}
			}
		break;
		case "u_tkrequestformtimeadjustment":
			$objRs = new recordset(null,$objConnection);
			$obju_TimeKeeping = new masterdataschema_br(NULL,$objConnection,"u_tkattendanceentry");
			
			if ($objTable->getudfvalue("u_status") == 1) {
				$objRs->queryopen("SELECT CONCAT(REPLACE(b.u_date,'-',''),REPLACE(b.u_time,':','')) as code,
											   b.u_date,
											   b.u_time,
											   b.u_type
											FROM u_tkrequestformtimeadjustmentitems b
												WHERE b.code = '$objTable->code'");
					while ($objRs->queryfetchrow("NAME")) {
						if ($obju_TimeKeeping->getbysql("CODE='".$objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"]."'")) {
							
						} else {
							$obju_TimeKeeping->prepareadd();
							$obju_TimeKeeping->code = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"];
							$obju_TimeKeeping->name = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"];
							$obju_TimeKeeping->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
							$obju_TimeKeeping->setudfvalue("u_biometrixid",$objTable->getudfvalue("u_biometrixid"));
							$obju_TimeKeeping->setudfvalue("u_branch",$objTable->getudfvalue("u_branches"));
							$obju_TimeKeeping->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
							$obju_TimeKeeping->setudfvalue("u_date",$objRs->fields["u_date"]);
							$obju_TimeKeeping->setudfvalue("u_time",$objRs->fields["u_time"]);
							$obju_TimeKeeping->setudfvalue("u_type",$objRs->fields["u_type"]);
							$obju_TimeKeeping->setudfvalue("u_other","O");
							$obju_TimeKeeping->setudfvalue("u_filename","TA-R");
							
							$actionReturn = $obju_TimeKeeping->add();
							if(!$actionReturn) break;
						}
					}
			}
			
			if ($objTable->getudfvalue("u_status") == -1) {
				$objRs->queryopen("SELECT CONCAT(REPLACE(b.u_date,'-',''),REPLACE(b.u_time,':','')) as code,
											   b.u_date,
											   b.u_time,
											   b.u_type
											FROM u_tkrequestformtimeadjustmentitems b
												WHERE b.code = '$objTable->code'");
					while ($objRs->queryfetchrow("NAME")) {
						$code = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").$objRs->fields["code"].$objRs->fields["u_type"];
						if($obju_TimeKeeping->getbysql("CODE='".$code."' AND U_FILENAME = 'TA-R'")) {
							$actionReturn = $obju_TimeKeeping->delete();
							if(!$actionReturn) break;
						} else {
							return raiseError("Can't Find Attendance Entry [ Time Adjustment Request ]");
						}
					}
			}
			
			if($objTable->getudfvalue("u_tastatus") == "Successful") {
				if($objTable->getudfvalue("u_status") != -1) {
					$objRsGlobalSetting = new recordset(null,$objConnection);
					$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
					if ($objRsGlobalSetting->recordcount() >= 1) {
						if($_SESSION["userid"] != "") {
							if($objTable->getudfvalue("u_status") == 0) {
								$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
							}
						} else {
							return raiseError('End of Session.. Please Log-Out');
						}
					}
				}
			}
			break;
		case "u_tkrequestformass":
			$objRs = new recordset(null,$objConnection);
			$objRsV = new recordset(null,$objConnection);
			$obju_DTRx = new masterdataschema_br(null,$objConnection,"u_tktemporarydtr");
			$obju_DTRTime = new masterdataschema_br(null,$objConnection,"u_tktemporarydtrtime");
			$obju_Schedule_Row = new masterdataschema_br(null,$objConnection,"u_tkschedulecolumntorow");
			
			if ($objTable->getudfvalue("u_status") != 2) {
				$objRsV->queryopen("SELECT b.u_status,b.u_scheduleassign
									FROM u_tkschedulecolumntorow b
									   WHERE b.company = '".$_SESSION["company"]."'
										AND b.branch = '".$_SESSION["branch"]."' 
										AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'
										AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
										AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objTable->getudfvalue("u_tkdate")."'");
					if ($objRsV->recordcount() > 0) {
					while ($objRsV->queryfetchrow("NAME")) {
						if ($objRsV->fields["u_status"] == "Present") {
							return raiseError('Request No. ['.$objTable->code.'] : This Date is already Present. Please Try Again Other Day');
						} else if ($objRsV->fields["u_status"] == "LWOP") {
							return raiseError('Request No. ['.$objTable->code.'] : This Date is already Leave ( LWOP ). Please Try Again Other Day');
						} else if ($objRsV->fields["u_status"] == "WP") {
							return raiseError('Request No. ['.$objTable->code.'] : This Date is already Leave ( WP ). Please Try Again Other Day');
						} else {
							if ($objRsV->fields["u_scheduleassign"] == "") {
								return raiseError('Request No. ['.$objTable->code.'] : This Date is have No Schedule. Please Try Again Other Day');
							}
						}
					}
				} else {
					return raiseError('This Date is have No Schedule Taging Please Try to Add Schedule');
				}
			}
		
			if ($objTable->getudfvalue("u_status") == 1) {
				if($objTable->getudfvalue("u_tk_wd") != 0) {
					if ($obju_DTRx->getbysql("CODE='".$objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objTable->getudfvalue("u_tkdate"))."'")) {
						$obju_DTRx->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
						$obju_DTRx->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
						$obju_DTRx->setudfvalue("u_tkdate",$objTable->getudfvalue("u_tkdate"));
						$obju_DTRx->setudfvalue("u_tk_wd",$objTable->getudfvalue("u_tk_wd"));
						$obju_DTRx->setudfvalue("u_tk_excesstime",$objTable->getudfvalue("u_tk_ot"));
						$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
						$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
						$obju_DTRx->setudfvalue("u_status","O");
						$obju_DTRx->setudfvalue("u_destination","O.B :".$objTable->code);
						$actionReturn = $obju_DTRx->update($obju_DTRx->code,$obju_DTRx->rcdversion);
						if(!$actionReturn) break;
						
							if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
								$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
								$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
								$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
								$actionReturn = $obju_DTRTime->update($obju_DTRTime->code,$obju_DTRTime->rcdversion);
								if(!$actionReturn) break;
							} else {
								$obju_DTRTime->prepareadd();
								$obju_DTRTime->code = $obju_DTRx->code;
								$obju_DTRTime->name = $obju_DTRTime->code;
								$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
								$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
								$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
								$actionReturn = $obju_DTRTime->add();
								if(!$actionReturn) break;
							}
						
					} else {
						$obju_DTRx->code = $objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objTable->getudfvalue("u_tkdate")); //a.u_profitcenter,a.u_empid,REPLACE(b.u_tkdate,'-','')
						$obju_DTRx->name = $obju_DTRx->code;
						$obju_DTRx->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
						$obju_DTRx->setudfvalue("u_empid",$objTable->getudfvalue("u_empid"));
						$obju_DTRx->setudfvalue("u_tkdate",$objTable->getudfvalue("u_tkdate"));
						$obju_DTRx->setudfvalue("u_tk_wd",$objTable->getudfvalue("u_tk_wd"));
						$obju_DTRx->setudfvalue("u_tk_excesstime",$objTable->getudfvalue("u_tk_ot"));
						$obju_DTRx->setudfvalue("u_tk_late",formatNumeric(0,"amount"));
						$obju_DTRx->setudfvalue("u_tk_ut",formatNumeric(0,"amount"));
						$obju_DTRx->setudfvalue("u_status","O");
						$obju_DTRx->setudfvalue("u_destination","O.B :".$objTable->code);
						$actionReturn = $obju_DTRx->add();	
						if(!$actionReturn) break;
						
							if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
								$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
								$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
								$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
								$actionReturn = $obju_DTRTime->update($obju_DTRTime->code,$obju_DTRTime->rcdversion);
								if(!$actionReturn) break;
							} else {
								$obju_DTRTime->prepareadd();
								$obju_DTRTime->code = $obju_DTRx->code;
								$obju_DTRTime->name = $obju_DTRTime->code;
								$obju_DTRTime->setudfvalue("u_dates",$objTable->getudfvalue("u_tkdate"));
								$obju_DTRTime->setudfvalue("u_time_from",$objTable->getudfvalue("u_assfromtime"));
								$obju_DTRTime->setudfvalue("u_time_to",$objTable->getudfvalue("u_asstotime"));
								$actionReturn = $obju_DTRTime->add();
								if(!$actionReturn) break;
							}
					}
					
					
						if($actionReturn) {
							$objRs->queryopen("SELECT b.code
											FROM u_tkschedulecolumntorow b
											WHERE b.company = '".$_SESSION["company"]."'
											  AND b.branch = '".$_SESSION["branch"]."' 
											  AND b.u_profitcenter = '".$objTable->getudfvalue("u_profitcenter")."'
											  AND b.u_empid = '".$objTable->getudfvalue("u_empid")."'
											  AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objTable->getudfvalue("u_tkdate")."'");
							if ($objRs->recordcount() > 0) {
								while ($objRs->queryfetchrow("NAME")) {
									if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
										$obju_Schedule_Row->setudfvalue("u_status","Present");
										$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
									}
								}
							} else {
								return raiseError('This Date is have No Schedule Taging Please Try to Add Schedule');
							}
						}	
					
				} else {
					return raiseError("Can't Approved Because of Zero Working Hrs. Request No. [".$objTable->code."]");	
				}
			}
			
			if ($objTable->getudfvalue("u_status") == -1) {
				if ($obju_DTRx->getbysql("CODE='".$objTable->getudfvalue("u_profitcenter").$objTable->getudfvalue("u_empid").str_replace("-","",$objTable->getudfvalue("u_tkdate"))."'")) {
						if($obju_DTRTime->getbysql("CODE='".$obju_DTRx->code."'")) {
							$actionReturn = $obju_DTRTime->delete();
							if(!$actionReturn) break;
						} else {
							return raiseError("Can't Find DTR Times");
						}
					
					if($actionReturn) $actionReturn = $obju_DTRx->delete();
					if(!$actionReturn) break;
					
				} else {
					return raiseError("Can't Find DTR Days");
				}
				
				
					if($actionReturn) {
						$objRs->queryopen("SELECT b.code
										FROM u_tkschedulecolumntorow b
										WHERE b.u_empid = '".$objTable->getudfvalue("u_empid")."'
											AND CONCAT(b.u_year,'-',b.u_month,'-',IF(LENGTH(b.u_days) = 1,CONCAT('0',b.u_days),b.u_days)) = '".$objTable->getudfvalue("u_tkdate")."'");
						while ($objRs->queryfetchrow("NAME")) {
							if ($obju_Schedule_Row->getbysql("CODE='".$objRs->fields["code"]."'")) {
								$obju_Schedule_Row->setudfvalue("u_status","Absent");
								$actionReturn = $obju_Schedule_Row->update($obju_Schedule_Row->code,$obju_Schedule_Row->rcdversion);
							}
						}
					}	
			}
			
			break;
		case "u_tkrequestformobset":
			if($objTable->getudfvalue("u_status") != -1) {
				$objRsGlobalSetting = new recordset(null,$objConnection);
				$objRsGlobalSetting->queryopen("SELECT u_emailcheck FROM u_prglobalsetting WHERE u_emailcheck = 1");
				if ($objRsGlobalSetting->recordcount() >= 1) {
					if($_SESSION["userid"] != "") {
						$actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);
					} else {
						return raiseError('End of Session.. Please Log-Out');
					}
				}
			}
			break;
		case "u_tkapproverfile":
			$objRsDelete = new recordset("transactions",$objConnection);
			$objRs = new recordset(null,$objConnection);
			$objRs->queryopen("SELECT code FROM u_premploymentinfo a WHERE a.u_employstatus2 = '".$objTable->code."'");
				while ($objRs->queryfetchrow("NAME")) {
					$company = $_SESSION["company"];
					$branch = $_SESSION["branch"];
					$empid = $objRs->fields["code"];
					if ($actionReturn) $actionReturn = $objRsDelete->executesql("DELETE FROM u_tkapproverhistory WHERE company='$company' AND branch='$branch' AND u_requestno IN(SELECT code FROM u_tkrequestformass WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformleave WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformloan WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformoffset WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformoffsethrs WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformot WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformtimeadjustment WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformobset WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0 UNION ALL SELECT code FROM u_tkrequestformoball WHERE company='$company' AND branch='$branch' AND u_empid = '$empid' AND u_status = 0)
",false);
				}
			
			break;
	}
	return $actionReturn;
}


/*
function onBeforeDeleteEventmasterdataschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkattendanceentry":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventmasterdataschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkattendanceentry":
			break;
	}
	return $actionReturn;
}
*/

function onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable,$delete=false) {
	global $httpVars;
	global $objConnection;
	global $page;
	global $enc;
	
	$actionReturn = true;

	$objRs = new recordset(NULL, $objConnection);
	$objRsOffset = new recordset(null,$objConnection);
	$objEmailSync = new emailsync(null,$objConnection);
	$objEmailSync->queryopen();
	if(!$objEmailSync->queryfetchrow()) {
		return raiseError("No Email Synchronization Setup detected.");
	}
	
	$objEmailSync->password = $enc->decrypt($objEmailSync->password);
	
	$objRs->queryopen("SELECT a.u_email as 'From',
							  cc.u_email as 'To' ,
							  ccc.code as 'ApproverID',
							  ccc.name as 'ApproverName',
							  gs.u_linksystem as 'Link',
							  gs.u_companyname as 'comp',
							  REPLACE(gs.u_linksystem,'index.php','') as modes,
							  CONCAT(LEFT(b.u_fname,1),'. ',b.u_lname) as 'EmpName'
							  
							FROM u_hremploymentinfo a
							INNER JOIN u_premploymentinfo b ON b.company = a.company
							  AND b.branch = a.branch
							  AND b.code = a.code
							LEFT JOIN (SELECT u_empid,COUNT(code) as counters
										  FROM u_tkapproverhistory
											WHERE u_status = 'A'
											  AND u_requestno = '".$objTable->code."'
											  GROUP BY u_requestno) as x ON x.u_empid = a.code
							INNER JOIN u_tkapproverfile c ON c.company = b.company
							  AND c.branch = b.branch
							  AND c.code = b.u_employstatus2
							INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
							  AND c1.branch = c.branch
							  AND c1.code = c.code
							  AND c1.u_stageno = IFNULL(x.counters,0)+1
							INNER JOIN u_hremploymentinfo cc ON cc.company = c1.company
							  AND cc.branch = c1.branch
							  AND cc.code = c1.u_stagename
							INNER JOIN u_premploymentinfo ccc ON ccc.company = cc.company
							  AND ccc.branch = cc.branch
							  AND ccc.code = cc.code
							INNER JOIN u_prglobalsetting gs ON gs.company = a.company
							  AND gs.branch = a.branch
							WHERE a.code = '".$objTable->getudfvalue("u_empid")."'");
	if ($objRs->recordcount() > 0) {
		if ($objRs->queryfetchrow("NAME")) {
			$toaddress = $objRs->fields["To"];
			$fromaddress = $objRs->fields["From"];	
			
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
			
				$mail->AddAddress($toaddress);
				
				$mail->IsHTML(true);
				
				$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rAcopy.html');
				
				if($objTable->dbtable == 'u_tkrequestformot') {
					$req_type = 1;
				} else if($objTable->dbtable == 'u_tkrequestformleave') {
					$req_type = 2;
				} else if($objTable->dbtable == 'u_tkrequestformloan') {
					$req_type = 3;
				} else if($objTable->dbtable == 'u_tkrequestformtimeadjustment') {
					$req_type = 4;
				} else if($objTable->dbtable == 'u_tkrequestformass') {
					$req_type = 5;
				} else if($objTable->dbtable == 'u_tkrequestformoffset') {
					$req_type = 6;
				} else if($objTable->dbtable == 'u_tkrequestformoffsethrs') {
					$req_type = 7;
				} else if($objTable->dbtable == 'u_tkrequestformobset') {
					$req_type = 8;
				} else if($objTable->dbtable == 'u_tkrequestformnote') {
					$req_type = 9;
				} else if($objTable->dbtable == 'u_tkrequestformoball') {
					$req_type = 10;
				}
				
				$approved = "<a href='".$objRs->fields["modes"]."UDP.php?objectcode=u_tkapprovedlink&id=".$objRs->fields["ApproverID"]."&modes=A&type_req=".$req_type."&req_date=".$objTable->getudfvalue("u_appdate")."&reqno=".$objTable->code."'><button>Approved</button></a>";
				$denied = "<a href='".$objRs->fields["modes"]."UDP.php?objectcode=u_tkapprovedlink&id=".$objRs->fields["ApproverID"]."&modes=D&type_req=".$req_type."&req_date=".$objTable->getudfvalue("u_appdate")."&reqno=".$objTable->code."'><button>Denied</button></a>";
				
				$html_msg = str_replace("{Requestor}",$_SESSION['username'],$html_msg);
				$html_msg = str_replace("{Approver}",$objRs->fields["ApproverName"],$html_msg);
				$html_msg = str_replace("{requestno}",$objTable->code,$html_msg);
				$html_msg = str_replace("{empid}",$objTable->getudfvalue("u_empid"),$html_msg);
				$html_msg = str_replace("{empname}",$objTable->getudfvalue("u_empname"),$html_msg);
				$html_msg = str_replace("{appdate}",$objTable->getudfvalue("u_appdate"),$html_msg);
				$html_msg = str_replace("{comp}",$objRs->fields["comp"],$html_msg);
				$html_msg = str_replace("{link}",$objRs->fields["Link"],$html_msg);
				$html_msg = str_replace("{approved}",$approved,$html_msg);
				$html_msg = str_replace("{denied}",$denied,$html_msg);	
				
				if($objTable->dbtable == "u_tkrequestformass") {
					$html_msg = str_replace("{rtype}","Office Business",$html_msg);
					$remarks = "Work Hrs : ".$objTable->getudfvalue("u_tk_wd")." O.B. Dates : ".$objTable->getudfvalue("u_tkdate");
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Office Business";
				} else if($objTable->dbtable == "u_tkrequestformot") {
					$html_msg = str_replace("{rtype}","Over Time",$html_msg);
					$remarks = "OT Hrs :".$objTable->getudfvalue("u_othrs")." OT Date : ".$objTable->getudfvalue("u_otdate")." OT Time From ".$objTable->getudfvalue("u_otfromtime")." & ".$objTable->getudfvalue("u_ottotime")." Reason : ".$objTable->getudfvalue("u_otreason");
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Over Time";
				} else if($objTable->dbtable == "u_tkrequestformtimeadjustment") {
					$html_msg = str_replace("{rtype}","Time Adjustment",$html_msg);
					$objRsOffset->queryopen("SELECT GROUP_CONCAT(DATE_FORMAT(a.u_date,'%M %d,%Y'),' ',IF(a.u_type = 'Type : I','In','Type : Out'),' Time :',a.u_time,'<br>') as code FROM u_tkrequestformtimeadjustmentitems a WHERE a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='$objTable->code'");
					if ($objRsOffset->queryfetchrow("NAME")) {
						$remarks = "Time Adjustment For Incorrect/Missing Time In/Out <br>".$objRsOffset->fields["code"].$objTable->getudfvalue("u_assreason");
					}
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Time Adjustment";
				} else if($objTable->dbtable == "u_tkrequestformloan") {
					$html_msg = str_replace("{rtype}","Loan",$html_msg);
					$remarks = "Loan Dates : ".$objTable->getudfvalue("u_loanfrom")." and ".$objTable->getudfvalue("u_loanto");
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Loan";
				} else if($objTable->dbtable == "u_tkrequestformleave") {
					$html_msg = str_replace("{rtype}","Leave : ".$objTable->getudfvalue("u_leavetype"),$html_msg);
					$remarks = "Leave Days : ".$objTable->getudfvalue("u_leavedays")." Leave Dates : ".$objTable->getudfvalue("u_leavefrom")." and ".$objTable->getudfvalue("u_leaveto")." Reason : ".$objTable->getudfvalue("u_leavereason");
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Leave : ".$objTable->getudfvalue("u_leavetype");
				} else if($objTable->dbtable == "u_tkrequestformoffset") {
					$html_msg = str_replace("{rtype}","Off-Set",$html_msg);
					$objRsOffset->queryopen("SELECT GROUP_CONCAT(DATE_FORMAT(a.u_date_filter,'%M %d,%Y'),' Type :',a.u_type_filter,' Hr :',ROUND(a.u_hours,2) SEPARATOR '<br>') as code FROM u_tkrequestformoffsetlist a WHERE a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='$objTable->code'");
					if ($objRsOffset->queryfetchrow("NAME")) {
						$remarks = "Off-set <br>".$objRsOffset->fields["code"]."<br>Reason : ".$objTable->getudfvalue("u_offreason");
					}
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Off-Set";	
				} else if($objTable->dbtable == "u_tkrequestformobset") {
					$html_msg = str_replace("{rtype}","Official Business Hrs",$html_msg);
					$objRsOffset->queryopen("SELECT GROUP_CONCAT(DATE_FORMAT(a.u_date_filter,'%M %d,%Y'),' Type :',a.u_type_filter,' Hr :',ROUND(a.u_hours,2) SEPARATOR '<br>') as code FROM u_tkrequestformobsetlist a WHERE a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='$objTable->code'");
					if ($objRsOffset->queryfetchrow("NAME")) {
						$remarks = "OB Hrs <br>".$objRsOffset->fields["code"]."<br>Reason : ".$objTable->getudfvalue("u_assreason");
					}
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Official Business Hrs";
				}  else if($objTable->dbtable == "u_tkrequestformoball") {
					$html_msg = str_replace("{rtype}","Official Business ALL",$html_msg);
					$objRsOffset->queryopen("SELECT GROUP_CONCAT(DATE_FORMAT(a.u_date_filter,'%M %d,%Y'),' Type :',a.u_type_filter,' Hr :',ROUND(a.u_ob_tot_hrs,2) SEPARATOR '<br>') as code FROM u_tkrequestformoballlist a WHERE a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='$objTable->code' GROUP BY a.code");
					if ($objRsOffset->queryfetchrow("NAME")) {
						$remarks = "OB ALL <br>".$objRsOffset->fields["code"]."<br>Reason : ".$objTable->getudfvalue("u_assreason");
					}
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = "Official Business ALL";
				}
				
				$mail->Subject = "Action Required Request ".$reqtype." ".$objRs->fields["EmpName"];
				
				$mail->Body = $html_msg; 
				  
				if(!$mail->Send()) {
					return raiseError("Mailer Error: ".$mail->ErrorInfo);
				}
				
			} catch (phpmailerException $e) {
				return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
			}
			
		} 
		
	} else return raiseError("No Email Detected For This [".$objTable->getudfvalue("u_empid")."]");
	
	return $actionReturn;
}

#if ($actionReturn) $actionReturn = onCustomEventmasterdataschema_brEmailPushGPSKiosk($objTable);

function onCustomEventmasterdataschema_brEmailPushApproverGPSKiosk($objTable,$delete=false) {
	global $httpVars;
	global $objConnection;
	global $page;
	global $enc;
	
	$actionReturn = true;

	$objRs = new recordset(NULL, $objConnection);
	$objRs2 = new recordset(NULL, $objConnection);
	$objEmailSync = new emailsync(null,$objConnection);
	$objEmailSync->queryopen();
	if(!$objEmailSync->queryfetchrow()) {
		return raiseError("No Email Synchronization Setup detected.");
	}
	
	$objEmailSync->password = $enc->decrypt($objEmailSync->password);
	
	$objRs->queryopen("SELECT a.u_email as 'From',
							  cc.u_email as 'To' ,
							  ccc.name as 'ApproverName',
							  gs.u_linksystem as 'Link',
							  gs.u_companyname as 'comp',
							  CONCAT(LEFT(b.u_fname,1),'. ',b.u_lname) as 'EmpName'
							  
							FROM u_hremploymentinfo a
							INNER JOIN u_premploymentinfo b ON b.company = a.company
							  AND b.branch = a.branch
							  AND b.code = a.code
							LEFT JOIN (SELECT u_empid,COUNT(code) as counters
										  FROM u_tkapproverhistory
											WHERE u_status = 'A'
											  AND u_requestno = '".$objTable->getudfvalue("u_requestno")."'
											  GROUP BY u_requestno) as x ON x.u_empid = a.code
							INNER JOIN u_tkapproverfile c ON c.company = b.company
							  AND c.branch = b.branch
							  AND c.code = b.u_employstatus2
							INNER JOIN u_tkapproverfileassigned c1 ON c1.company = c.company
							  AND c1.branch = c.branch
							  AND c1.code = c.code
							  AND c1.u_stageno = IFNULL(x.counters,0)+2
							INNER JOIN u_hremploymentinfo cc ON cc.company = c1.company
							  AND cc.branch = c1.branch
							  AND cc.code = c1.u_stagename
							INNER JOIN u_premploymentinfo ccc ON ccc.company = cc.company
							  AND ccc.branch = cc.branch
							  AND ccc.code = cc.code
							INNER JOIN u_prglobalsetting gs ON gs.company = a.company
							  AND gs.branch = a.branch
							WHERE a.code = '".$objTable->getudfvalue("u_empid")."'");
	if ($objRs->recordcount() > 0) {
		if ($objRs->queryfetchrow("NAME")) {
			$toaddress = $objRs->fields["To"];
			$fromaddress = $objRs->fields["From"];	
			
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
			
				$mail->AddAddress($toaddress);
				
				$mail->IsHTML(true);
				
				$html_msg = file_get_contents('../Addons/GPS/TK Add-On/UserHTML/rAcopy.html');
				
				$html_msg = str_replace("{Requestor}",$_SESSION['username'],$html_msg);
				$html_msg = str_replace("{Approver}",$objRs->fields["ApproverName"],$html_msg);
				$html_msg = str_replace("{requestno}",$objTable->getudfvalue("u_requestno"),$html_msg);
				$html_msg = str_replace("{empid}",$objTable->getudfvalue("u_empid"),$html_msg);
				$html_msg = str_replace("{empname}",$objTable->getudfvalue("u_empname"),$html_msg);
				$html_msg = str_replace("{appdate}",$objTable->getudfvalue("u_date"),$html_msg);
				$html_msg = str_replace("{comp}",$objRs->fields["comp"],$html_msg);
				$html_msg = str_replace("{link}",$objRs->fields["Link"],$html_msg);
				
				$objRs2->queryopen("SELECT code,request_type FROM (SELECT code,'Office Business' as request_type FROM u_tkrequestformass UNION ALL SELECT code,'Leave' as request_type FROM u_tkrequestformleave UNION ALL SELECT code,'Loan' as request_type FROM u_tkrequestformloan UNION ALL SELECT code,'Off-Set' as request_type FROM u_tkrequestformoffset UNION ALL SELECT code,'Off-Set Hrs' as request_type FROM u_tkrequestformoffsethrs UNION ALL SELECT code,'Over Time' as request_type FROM u_tkrequestformot UNION ALL SELECT code,'Time Adjustment' as request_type FROM u_tkrequestformtimeadjustment UNION ALL SELECT code,'Official Business Hrs' as request_no FROM u_tkrequestformobset UNION ALL SELECT code,'Official Business ALL' as request_no FROM u_tkrequestformoball) as x WHERE code = '".$objTable->getudfvalue("u_requestno")."'");
				
				if ($objRs2->queryfetchrow("NAME")) {
					$html_msg = str_replace("{rtype}",$objRs2->fields["request_type"],$html_msg);
					$remarks = $objTable->getudfvalue("u_typeofrequest");
					$html_msg = str_replace("{remarks}",$remarks,$html_msg);
					$reqtype = $objRs2->fields["request_type"];
				}
				
				$mail->Subject = "Action Required Request ".$reqtype." ".$objRs->fields["EmpName"];
				
				$mail->Body = $html_msg; 
				  
				if(!$mail->Send()) {
					return raiseError("Mailer Error: ".$mail->ErrorInfo);
				}
				
			} catch (phpmailerException $e) {
				return raiseError("Unable to send email to [".$toaddress ."]: " . $e->errorMessage());
			}
			
		} 
		
	} else return raiseError("No Email Detected For This [".$objTable->getudfvalue("u_empid")."]");
	
	return $actionReturn;
}

?>



