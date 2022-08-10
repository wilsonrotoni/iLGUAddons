<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/smsoutlog.php");
	require_once("./series.php");
	require_once("./utils/companies.php");

	function executeTasksmsparserGPSHIS() {
		$result = array(1,"");
		$actionReturn = true;
		echo "executeTasksmsparserGPSHIS():running\r\n";
		
		$objCon = new connection('Page Connection'); 
		$objCon->connect();
		//$objCon->selectdb("sms_lms_ho");
											
		$objRs= new recordset(null,$objCon);
		$objRs2= new recordset(null,$objCon);
		$objRs3= new recordset(null,$objCon);
		$objSMSOutLog = new smsoutlog(null,$objCon);
		
		$objRs->queryopen("SELECT * FROM smsinlog where status in ('','For Reply')");
		//$objRs->shareddatatype = "COMPANY";
		//$objRs->logtrxrows = false;
		while ($objRs->queryfetchrow("NAME")) {
			$actionReturn = true;
			$objCon->beginwork();	
			$mobilenumber1 = $objRs->fields["MOBILENUMBER"];
			$mobilenumber2 = "0" . substr($mobilenumber1,3,15);
			if (is_numeric($mobilenumber2) && strlen($mobilenumber2)==11) {
				//$objRs2->setdebug();
				$data = processTasksmsparserSMSInLogGPSHIS($objCon,$objSMSOutLog,$objRs);
				if ($data[0]) $actionReturn = $objRs->executesql("update smsinlog set status='".$data[1]."' where trxid='".$objRs->fields["TRXID"]."'",true); 
			} else $actionReturn = $objRs->executesql("update smsinlog set status='Invalid', remark='Invalid Mobile' where trxid='".$objRs->fields["TRXID"]."'",true);
			if ($actionReturn) $objCon->commit();
			else $objCon->rollback();
		}
		
		
		//if ($actionReturn) $actionReturn = raiseError("executeTasksmsparserGPSWheeltekSMS()");
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
			//$objCon->rollback();
		} //else $objCon->commit();
		//var_dump($result);
		return $result;
	}
	
	function processTasksmsparserSMSInLogGPSHIS($objConnection,$objSMS,$objMessage) {
		$result = array(true,"Replied");
		
		$actionReturn = true;
		echo "processTasksmsparserSMSInLogGPSHIS():running\r\n";
		
		$objRs = new recordset(null,$objConnection);
		
		//$mobilenumber2 = "0" . substr($mobilenumber1,3,15);
		$mobileno = "0" . substr($objMessage->fields["MOBILENUMBER"],3,15);
		$message = split(" ",trim(str_replace("\n","",str_replace("\r","",$objMessage->fields["MESSAGE"]))));
		//var_dump($mobileno);
		$type = strtoupper(array_shift($message));
		switch ($type) {
			case "HELP":
				if ($actionReturn) {
					$objSMS->prepareadd();
					$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
					$objSMS->deviceid = "sun";
					$objSMS->mobilenumber = $mobileno;
					$objSMS->remark = "HELP";
					$objSMS->message = "SEND CHARGES or\r\n";
					$objSMS->message .= "SEND CHARGES REFNO or \r\n";
					$objSMS->message .= "SEND CHARGES FIRSTNAME\r\n";
					$result[0] = $objSMS->add();
				}	
				break;
			case "CHARGES":
				processTasksmsparserChargesGPSHIS($objConnection,$message,$objSMS,$mobileno,$result);
				break;
				
			case "BILL":
				processTasksmsparserBillGPSHIS($objConnection,$message,$objSMS,$mobileno,$result);
				break;
				
			case "QUEING":
				$mode = strtoupper(trim(array_shift($message)));
				$type = $objRs->fields["code"];
				$typedesc = $objRs->fields["name"];
				switch ($mode) {
					case "APPLY":
					case "CANCEL":
					case "BAL":
						$result[1] = "Forwarded";
						break;
					case "YES":
					case "NO":
						if ($objEmployee->fields["U_APPROVER"]==1) {
							$result[1] = "Forwarded";
						} else {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "Leave Disposition";
							$objSMS->message = "You are not allowed to approve/disapprove leave applications.\r\n";
							$result[0] = $objSMS->add();
						}	
						break;
					case "LIST":
						$schema["u_leavetype"] = $type;
						$schema["u_leavetypedesc"] = $typedesc;
						$schema["u_empno"] = $objEmployee->fields["U_EMPID"];
						if (count($message)>0) $schema["u_disposition"] = array_shift($message);
						
						if (substr($schema["u_disposition"],01)=="A") $schema["u_disposition"] = "Approved";
						elseif (substr($schema["u_disposition"],01)=="P") $schema["u_disposition"] = "Pending";
						else $schema["u_disposition"] = "";
						
						processTasksmsparserListLeaveApplicationGPSHIS($objConnection,$message,$objSMS,$mobileno,$result);
						
						break;
						
					case "HELP":
						if ($actionReturn) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = "Whole Day " . $typedesc . " Leave Application:\r\n";
							$objSMS->message .= "SEND ".$type." APPLY ".currentdate()." WD REASON\r\n";
							$result[0] = $objSMS->add();
						}	
						if ($actionReturn) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = "Half Day " . $typedesc . " Leave Application:\r\n";
							$objSMS->message .= "SEND ".$type." APPLY ".currentdate()." AM REASON\r\n";
							$objSMS->message .= "\r\n";
							$objSMS->message .= "SEND ".$type." APPLY ".currentdate()." PM REASON\r\n";
							$result[0] = $objSMS->add();
						}
	
						if ($actionReturn) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = "Multiple Days " . $typedesc . " Leave Application:\r\n";
							$objSMS->message .= "SEND ".$type." APPLY ".currentdate()." WD ".formatDateToHttp(dateadd("d",1,currentdateDB()))." WD REASON\r\n";
							$objSMS->message .= "\r\n";
							$objSMS->message .= "NOTE: Change WD to AM or PM for Half Day Leave.\r\n";
							$result[0] = $objSMS->add();
						}
						if ($actionReturn) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = $typedesc . " Leave Cancellation:\r\n";
							$objSMS->message .= "SEND ".$type." CANCEL REF# REASON\r\n";
							$result[0] = $objSMS->add();
						}
	
						if ($objEmployee->fields["U_APPROVER"]==1) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = $typedesc . " Leave Approval:\r\n";
							$objSMS->message .= "SEND ".$type." YES REF#\r\n";
							$objSMS->message .= "\r\n";
							$objSMS->message .= "SEND ".$type." NO REF# REASON\r\n";
							$result[0] = $objSMS->add();
						}
	
						if ($actionReturn) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = $typedesc . " Leave Balance:\r\n";
							$objSMS->message .= "SEND ".$type." BAL\r\n";
							$result[0] = $objSMS->add();
						}	
						if ($actionReturn) {
							$objSMS->prepareadd();
							$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
							$objSMS->deviceid = strtolower($objEmployee->fields["U_DEVICEID"]);
							$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
							$objSMS->remark = "HELP";
							$objSMS->message = $typedesc . " Leave Listing:\r\n";
							$objSMS->message .= "SEND ".$type." LIST \r\n";
							$objSMS->message .= "SEND ".$type." LIST APPROVED\r\n";
							$objSMS->message .= "SEND ".$type." LIST PENDING\r\n";
							$result[0] = $objSMS->add();
						}	
						break;
					default:
						$objSMS->prepareadd();
						$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
						$objSMS->deviceid = "sun";
						$objSMS->mobilenumber = $objEmployee->fields["U_SMSMOBILENO"];
						$objSMS->remark = "Invalid keyword";
						$objSMS->message = "Invalid keyword ".$mode.".\r\n";
						$objSMS->message .= "SEND ".$type." HELP\r\n";
						$result[0] = $objSMS->add();
						break;
				}
				break;
			default:	
				/*$objSMS->prepareadd();
				$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
				$objSMS->deviceid = "sun";
				$objSMS->mobilenumber = $mobileno;
				$objSMS->remark = "Invalid keyword";
				$objSMS->message = "Invalid keyword ".$type.".\r\n";
				$result[0] = $objSMS->add();*/
				break;
		}
		//var_dump($objEmployee);
		//var_dump($objMessage);
		//if ($actionReturn) $actionReturn = false;
		return $result;
	}
	
	function processTasksmsparserChargesGPSHIS($objConnection,$message,$objSMS,$mobileno,&$result) {

		$objRs = new recordset(null,$objConnection);
		
		$refExp = "";
		if ($message[0]!="") $refExp = " AND (docno='$message[0]' or u_patientname like '%$message[0]%') ";
		
		$objRs->queryopen("select DOCNO, U_PATIENTID, U_PATIENTNAME, U_TOTALCHARGES, U_SMSNETWORK from u_hisips where company='HIS' and branch='HO' and u_smsalert=1 and u_smsmobileno='$mobileno' and docstatus='Active' $refExp");
		if ($objRs->recordcount()>0) {
			while ($objRs->queryfetchrow("NAME")) {
				$objSMS->prepareadd();
				$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
				$objSMS->deviceid = $objRs->fields["U_SMSNETWORK"];
				$objSMS->mobilenumber = $mobileno;
				$objSMS->remark = "Charges";
				$objSMS->message = "Patient: " . $objRs->fields["U_PATIENTNAME"] . ".\r\nCurrent Total Charges is Php ".formatNumericAmount($objRs->fields["U_TOTALCHARGES"])."";
				//$objSMS->message .= "No.: ".intval($objRs->fields["docno"])."\r\n";
				//$objSMS->message .= "From: ". date('M d, Y',strtotime($objRs->fields["u_datefrom"])) . " " . $objRs->fields["u_datefromflag"] . "\r\n";
				//$objSMS->message .= "To: ". date('M d, Y',strtotime($objRs->fields["u_dateto"])) . " " . $objRs->fields["u_datetoflag"] . "\r\n";
				//$objSMS->message .= "No of Days: ". floatval($objRs->fields["u_days"]) . "\r\n";
				//$objSMS->message .= "Reason: ". $objRs->fields["u_reason"] . "\r\n";
				$result[0] = $objSMS->add();
				if (!$result[0]) break;
			}
		} else {
			$objSMS->prepareadd();
			$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
			$objSMS->deviceid = "sun";
			$objSMS->mobilenumber = $mobileno;
			$objSMS->remark = "Charges";
			$objSMS->message = "No. active records found.\r\n";
			$result[0] = $objSMS->add();
		}		
	}

	function processTasksmsparserBillGPSHIS($objConnection,$message,$objSMS,$mobileno,&$result) {

		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		
		$refExp = "";
		if ($message[0]!="") $refExp = " AND (docno='$message[0]' or u_patientname like '%$message[0]%') ";
		
		$objRs->queryopen("select DOCNO, U_PATIENTID, U_PATIENTNAME, U_TOTALCHARGES, U_SMSNETWORK from u_hisips where company='HIS' and branch='HO' $refExp and u_smsalert=1 and u_smsmobileno='$mobileno' and u_billno<>''");
		if ($objRs->recordcount()>0) {
			while ($objRs->queryfetchrow("NAME")) {
				$objRs2->queryopen("select sum(U_AMOUNT) as U_TOTALAMOUNT, sum(U_DUEAMOUNT ) as U_DUEAMOUNT from (select 'CM' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, a.U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' AS U_BILLNO, a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT*-1 as U_AMOUNT, a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hiscredits a where a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.U_BALANCE>0 union all select 'DP' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,'' AS U_BILLNO,a.DOCNO,a.U_DISCONBILL,0 AS  U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_DUEAMOUNT*-1 AS U_AMOUNT, a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hispos a where a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and u_prepaid in (2) and docstatus not in ('CN') and a.U_BALANCE>0 union all select b.U_FEETYPE as U_DOCTYPE, b.U_DOCTORID, c.NAME as U_DOCTORNAME, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID, a.U_DOCDATE AS U_STARTDATE,a.U_DOCTIME AS U_STARTTIME,'' AS U_BILLNO,concat(a.DOCNO,b.U_SUFFIX) as DOCNO,0 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, b.U_AMOUNT AS U_AMOUNT, b.U_NETAMOUNT-b.U_PAIDAMOUNT AS U_DUEAMOUNT from u_hisbills a, u_hisbillfees b left join u_hisdoctors c on c.code=b.u_doctorid where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and docstatus not in ('D','CN') and (b.U_NETAMOUNT-b.U_PAIDAMOUNT)<>0 union all select b.U_FEETYPE as U_DOCTYPE, b.U_DOCTORID, c.NAME as U_DOCTORNAME, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID, a.U_DOCDATE AS U_STARTDATE,a.U_DOCTIME AS U_STARTTIME,a.DOCNO as U_BILLNO, concat(a.DOCNO,b.U_SUFFIX) as DOCNO,0 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, b.U_PAIDAMOUNT*-1 AS U_AMOUNT, b.U_PAIDAMOUNT*-1 AS U_DUEAMOUNT from u_hisbills a, u_hisbillfees b left join u_hisdoctors c on c.code=b.u_doctorid  where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='HIS' and a.branch='HO' and a.u_patientid='".$objRs->fields["U_PATIENTID"]."' and docstatus = 'CN' and b.U_PAIDAMOUNT<>0 union all select 'CHRG' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' AS U_BILLNO, a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT as U_AMOUNT, a.U_AMOUNT AS U_DUEAMOUNT from u_hischarges a where a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.U_PREPAID=0 AND a.U_BILLNO='' and a.DOCSTATUS not in ('CN')) as x");
				//var_dump("select sum(U_AMOUNT) as U_TOTALAMOUNT, sum(U_DUEAMOUNT ) as U_DUEAMOUNT, from (select 'CM' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, a.U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' AS U_BILLNO, a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT*-1 as U_AMOUNT, a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hiscredits a where a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.U_BALANCE>0 union all select 'DP' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,'' AS U_BILLNO,a.DOCNO,a.U_DISCONBILL,0 AS  U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_DUEAMOUNT*-1 AS U_AMOUNT, a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hispos a where a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and u_prepaid in (2) and docstatus not in ('CN') and a.U_BALANCE>0 union all select b.U_FEETYPE as U_DOCTYPE, b.U_DOCTORID, c.NAME as U_DOCTORNAME, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID, a.U_DOCDATE AS U_STARTDATE,a.U_DOCTIME AS U_STARTTIME,'' AS U_BILLNO,concat(a.DOCNO,b.U_SUFFIX) as DOCNO,0 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, b.U_AMOUNT AS U_AMOUNT, b.U_NETAMOUNT-b.U_PAIDAMOUNT AS U_DUEAMOUNT from u_hisbills a, u_hisbillfees b left join u_hisdoctors c on c.code=b.u_doctorid where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and docstatus not in ('D','CN') and (b.U_NETAMOUNT-b.U_PAIDAMOUNT)<>0 union all select b.U_FEETYPE as U_DOCTYPE, b.U_DOCTORID, c.NAME as U_DOCTORNAME, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID, a.U_DOCDATE AS U_STARTDATE,a.U_DOCTIME AS U_STARTTIME,a.DOCNO as U_BILLNO, concat(a.DOCNO,b.U_SUFFIX) as DOCNO,0 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, b.U_PAIDAMOUNT*-1 AS U_AMOUNT, b.U_PAIDAMOUNT*-1 AS U_DUEAMOUNT from u_hisbills a, u_hisbillfees b left join u_hisdoctors c on c.code=b.u_doctorid  where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='HIS' and a.branch='HO' and a.u_patientid='".$objRs->fields["U_PATIENTID"]."' and docstatus = 'CN' and b.U_PAIDAMOUNT<>0 union all select 'CHRG' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' AS U_BILLNO, a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT as U_AMOUNT, a.U_AMOUNT AS U_DUEAMOUNT from u_hischarges a where a.company='HIS' and a.branch='HO' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.U_PREPAID=0 AND a.U_BILLNO='' and a.DOCSTATUS not in ('CN')) as x");
				while ($objRs2->queryfetchrow("NAME")) {
					$objSMS->prepareadd();
					$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
					$objSMS->deviceid = $objRs->fields["U_SMSNETWORK"];
					$objSMS->mobilenumber = $mobileno;
					$objSMS->remark = "Bill";
					$objSMS->message = "Patient: " . $objRs->fields["U_PATIENTNAME"] . ".\r\nTotal Bill is Php ".formatNumericAmount($objRs2->fields["U_TOTALAMOUNT"]).". Balance is Php ".formatNumericAmount($objRs2->fields["U_DUEAMOUNT"]);
					//$objSMS->message .= "No.: ".intval($objRs->fields["docno"])."\r\n";
					//$objSMS->message .= "From: ". date('M d, Y',strtotime($objRs->fields["u_datefrom"])) . " " . $objRs->fields["u_datefromflag"] . "\r\n";
					//$objSMS->message .= "To: ". date('M d, Y',strtotime($objRs->fields["u_dateto"])) . " " . $objRs->fields["u_datetoflag"] . "\r\n";
					//$objSMS->message .= "No of Days: ". floatval($objRs->fields["u_days"]) . "\r\n";
					//$objSMS->message .= "Reason: ". $objRs->fields["u_reason"] . "\r\n";
					$result[0] = $objSMS->add();
					if (!$result[0]) break;
				}
			}	
		} else {
			$objSMS->prepareadd();
			$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
			$objSMS->deviceid = "sun";
			$objSMS->mobilenumber = $mobileno;
			$objSMS->remark = "Bill";
			$objSMS->message = "No. bill records found.\r\n";
			$result[0] = $objSMS->add();
		}		
	}

	
?>