<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/documentschema_br.php");
	require_once("./classes/users.php");
	require_once("./classes/usermsgs.php");
	require_once("./series.php");

	function executeTaskdailyroomandboardfeesGPSHIS() {
		global $objConnection;
		$result = array(1,"");
		$actionReturn = true;
		
		$actionReturn = executeTaskpostroomandboardfeesGPSHIS(date('Y-m-d'));		
		//if ($actionReturn) $actionReturn = raiseError("here");
		
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
		}
		//var_dump($result);
		return $result;
	}
	
	function executeTaskpostroomandboardfeesGPSHIS($date) {
		global $objConnection;
		$actionReturn = true;
		$rs = new recordset(null,$objConnection);
		$obju_HisMiscs = new documentschema_br(null,$objConnection,"u_hischarges");
		$obju_HisIPs = new documentschema_br(null,$objConnection,"u_hisips");
		$obju_HisIPRooms = new documentlinesschema_br(null,$objConnection,"u_hisiprooms");
		$rs->setdebug();
		$rs->queryopen("select a.docno,b.u_roomno,b.u_roomtype, c.name as u_roomtypedesc, b.u_bedno, a.u_mghdate from u_hisips a, u_hisiprooms b, u_hisroomtypes c where c.code=b.u_roomtype and b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_startdate<'".$date."' and (b.u_enddate>='".$date."' or b.u_enddate is null) and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_migrated=0");
		//var_dump($rs->sqls);
		while ($rs->queryfetchrow("NAME")) {
			if ($obju_HisIPs->getbykey($rs->fields["docno"])) {
				//if ($obju_HisIPs->getudfvalue("u_billno")=="" && ($obju_HisIPs->getudfvalue("u_mgh")==0 || $obju_HisIPs->getudfvalue("u_mghdate")=="$date")) {
				
					if ($obju_HisIPRooms->getbysql("DOCID='$obju_HisIPs->docid' AND U_ENDDATE IS NULL")) {
						//echo $obju_HisIPs->docno . " - " . $obju_HisIPs->getudfvalue("u_patientname") . "<br>";
						$quantity=0;
						$amount=0;
						$startdate=$obju_HisIPRooms->getudfvalue("u_startdate");
						$starttime=$obju_HisIPRooms->getudfvalue("u_starttime");
						$roomno=$rs->fields["u_roomno"];
						$roomtype=$rs->fields["u_roomtype"];
						$roomtypedesc=$rs->fields["u_roomtypedesc"];
						$rate = $obju_HisIPRooms->getudfvalue("u_rate");
						//$enddate=max(date('Y-m-d'),$date);
						$enddate=$date;
						$endtime=date('H:i:s');
						if ($obju_HisIPRooms->getudfvalue("u_rateuom")=="Hour") {
							$objRs2->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$enddate." ".$endtime."','".$startdate." ".$starttime."'))/60)/60)))");
							if ($objRs2->queryfetchrow()) {
								$quantity = $objRs2->fields[0];
								$amount = $quantity*$rate;
							}
						} else {
							//$objRs2->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$enddate." ".$endtime."','".$startdate." ".$starttime."'))/60)/60)/24))");
							$quantity = datedifference("d", $startdate,$enddate);
							$amount = $quantity*$rate;
						}						
						$obju_HisIPRooms->setudfvalue("u_quantity",$quantity);
						$obju_HisIPRooms->setudfvalue("u_amount",$amount);
						$obju_HisIPRooms->privatedata["header"]=$obju_HisIPs;
						$actionReturn = $obju_HisIPRooms->update($obju_HisIPRooms->docid,$obju_HisIPRooms->lineid,$obju_HisIPRooms->rcdversion);
						
						if ($actionReturn) {
							if (!$obju_HisMiscs->getbysql("U_REFTYPE='IP' AND U_REFNO='".$rs->fields["docno"]."' AND U_STARTDATE='".$date."' AND U_BATCHCODE='Daily Room & Board Fees' and DOCSTATUS<>'CN'")) {
								$actionReturn = onCustomEventdocumentschema_brDailyRoomAndBoardFeesGPSHIS($obju_HisIPs,$date,null,$roomtype,$roomtypedesc, $rate,$roomno);
							}
						}	
					}
				//} else {
					//echo $obju_HisIPs->docno . " - " . $obju_HisIPs->getudfvalue("u_billno") . " - " . $obju_HisIPs->getudfvalue("u_billdatetime") . " - " .$obju_HisIPs->getudfvalue("u_patientname"). "<br>";
				//}	
			}	
			if (!$actionReturn) break;
		}
		//$obju_HISRoomRs = new documentschema_br(null,$objConnection,"u_hisroomrs");
		//if ($actionReturn) $actionReturn = raiseError("executeTaskpostroomandboardfeesGPSHIS");
		return $actionReturn;
	}
	
?>