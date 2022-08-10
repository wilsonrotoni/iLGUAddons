<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/smsoutlog.php");
	require_once("./series.php");
	require_once("./utils/companies.php");

	function executeTasksmsalertchargesGPSHIS() {
		$result = array(1,"");
		$actionReturn = true;
		echo "executeTasksmsalertchargesrGPSHIS():running\r\n";
		
		$objCon = new connection('Page Connection'); 
		$objCon->connect();
		//$objCon->selectdb("sms_lms_ho");
											
		$objRs= new recordset(null,$objCon);
		$objSMS = new smsoutlog(null,$objCon);
		
		$objRs->queryopen("select DOCNO, U_PATIENTID, U_PATIENTNAME, U_TOTALCHARGES, U_SMSMOBILENO, U_SMSNETWORK from u_hisips where company='HIS' and branch='HO' and u_smsalert=1 and u_smsalertdailycharges=1 and u_smsmobileno<>'' and docstatus='Active'");
		if ($objRs->recordcount()>0) {
			while ($objRs->queryfetchrow("NAME")) {
				$objSMS->prepareadd();
				$objSMS->trxid = getNextIDByBranch("smsoutlog",$objConnection);
				$objSMS->deviceid = $objRs->fields["U_SMSNETWORK"];
				$objSMS->mobilenumber = $objRs->fields["U_SMSMOBILENO"];
				$objSMS->remark = "Charges";
				$objSMS->message = "Patient: " . $objRs->fields["U_PATIENTNAME"] . ".\r\Current Total Charges is Php ".formatNumericAmount($objRs->fields["U_TOTALCHARGES"])."";
				//$objSMS->message .= "No.: ".intval($objRs->fields["docno"])."\r\n";
				//$objSMS->message .= "From: ". date('M d, Y',strtotime($objRs->fields["u_datefrom"])) . " " . $objRs->fields["u_datefromflag"] . "\r\n";
				//$objSMS->message .= "To: ". date('M d, Y',strtotime($objRs->fields["u_dateto"])) . " " . $objRs->fields["u_datetoflag"] . "\r\n";
				//$objSMS->message .= "No of Days: ". floatval($objRs->fields["u_days"]) . "\r\n";
				//$objSMS->message .= "Reason: ". $objRs->fields["u_reason"] . "\r\n";
				$actionReturn = $objSMS->add();
				if (!$actionReturn) break;
			}
		}
		//if ($actionReturn) $actionReturn = raiseError("executeTasksmsparserGPSWheeltekSMS()");
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
			//$objCon->rollback();
		} //else $objCon->commit();
		var_dump($result);
		return $result;
	}
	

	
?>