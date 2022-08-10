<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/documentschema_br.php");
	require_once("./classes/users.php");
	require_once("./classes/usermsgs.php");
	require_once("./series.php");

	function executeTaskadmissionfeesGPSHIS() {
		global $objConnection;
		$result = array(1,"");
		$actionReturn = true;
		
		$actionReturn = executeTaskpostadmissionGPSHIS();		
		//if ($actionReturn) $actionReturn = raiseError("here");
		
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
		}
		var_dump($result);
		return $result;
	}
	
	function executeTaskpostadmissionGPSHIS() {
		global $objConnection;
		$actionReturn = true;
		$rs = new recordset(null,$objConnection);
		$obju_HisMiscs = new documentschema_br(null,$objConnection,"u_hismiscs");
		$obju_HisIPs = new documentschema_br(null,$objConnection,"u_hisips");
		$rs->queryopen("select a.docno, a.u_startdate from u_hisips a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docstatus='Active' and a.u_migrated=0");
		while ($rs->queryfetchrow("NAME")) {
			if ($obju_HisIPs->getbykey($rs->fields["docno"])) {
				if ($actionReturn) {
					if (!$obju_HisMiscs->getbysql("U_REFTYPE='IP' AND U_REFNO='".$rs->fields["docno"]."' AND U_STARTDATE='".$rs->fields["u_startdate"]."' AND U_BATCHCODE='Admission Fees' AND DOCSTATUS NOT IN ('CN')")) {
						$actionReturn = onCustomEventdocumentschema_brAdmissionFeesGPSHIS($obju_HisIPs);
					}
				}	
			}	
			if (!$actionReturn) break;
		}
		//$obju_HISRoomRs = new documentschema_br(null,$objConnection,"u_hisroomrs");
		if ($actionReturn) $actionReturn = raiseError("executeTaskpostadmissionGPSHIS");
		return $actionReturn;
	}
	
?>