<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/documentschema_br.php");
	require_once("./classes/users.php");
	require_once("./classes/usermsgs.php");
	require_once("./series.php");

	function executeTaskcancelroomreservationsGPSHIS() {
		global $objConnection;
		$result = array(1,"");
		$actionReturn = true;
		
		$rs = new recordset(null,$objConnection);
		$obju_HISRoomRs = new documentschema_br(null,$objConnection,"u_hisroomrs");
		
		if ($actionReturn) {
			$rs->queryopen("select * from u_hisroomrs where u_enddatetime < '".date('Y-m-d H:i')."' and docstatus='O'"); 
			while ($rs->queryfetchrow("NAME")) {
				if ($obju_HISRoomRs->getbykey($rs->fields["DOCNO"])) {
					$obju_HISRoomRs->docstatus='CN';
					$actionReturn = $obju_HISRoomRs->update($obju_HISRoomRs->docno,$obju_HISRoomRs->rcdversion);
				} else $actionReturn = raiseError("Unable to find Reservation No.[".$rs->fields["DOCNO"]."]");
				if (!$actionReturn) break;
			}
		}
		
		//if ($actionReturn) $actionReturn = raiseError("here");
		//var_dump($actionReturn);	 
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
		}
		//var_dump($result);
		return $result;
	}
	
?>