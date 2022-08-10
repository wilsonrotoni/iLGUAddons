<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/documentschema_br.php");
	require_once("./classes/users.php");
	require_once("./classes/usermsgs.php");
	require_once("./series.php");

	function executeTaskdischargepatientsGPSHIS() {
		global $objConnection;
		$result = array(1,"");
		$actionReturn = true;
		
		$rs = new recordset(null,$objConnection);
		$obju_HISOPs = new documentschema_br(null,$objConnection,"u_hisops");
		$objConnection->beginwork();
		if ($actionReturn) {
			$rs->queryopen("select docno from u_hisops where CEIL((((TIME_TO_SEC(timediff(now(),concat(u_startdate,' ',u_starttime)))/60)/60))) >24 and docstatus='Active' and (u_billno<>'' or (u_billing=0 and u_forcebilling=0))"); 
			while ($rs->queryfetchrow("NAME")) {
				if ($obju_HISOPs->getbykey($rs->fields["docno"])) {
					$obju_HISOPs->docstatus='Discharged';
					$obju_HISOPs->setudfvalue("u_discharged",1);
					$enddate = datetimeadd("h", 22, $obju_HISOPs->getudfvalue("u_startdate")." ".$obju_HISOPs->getudfvalue("u_starttime") );
					$obju_HISOPs->setudfvalue("u_enddate",substr($enddate,0,10));
					$obju_HISOPs->setudfvalue("u_endtime",substr($enddate,11,5));
					$actionReturn = $obju_HISOPs->update($obju_HISOPs->docno,$obju_HISOPs->rcdversion);
				} else $actionReturn = raiseError("Unable to find Outpatient Reg No.[".$rs->fields["docno"]."]");
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();
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