<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/documentschema_br.php");

	$actionReturn = true;
	$msg = "";
	if(isset($_GET['docno'])){
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		$objConnection->beginwork();
		if ($obju_HISIPs->getbykey($_GET['docno'])) {
			if ($obju_HISIPs->getudfvalue("u_predischarge")=="1") {
				$obju_HISIPs->setudfvalue("u_predischarge",0);
				$msg = "Patient was successfully untag for predischarge.";
			} else {
				if ($obju_HISIPs->getudfvalue("u_mgh")=="0") {
					$obju_HISIPs->setudfvalue("u_predischarge",1);
					$msg = "Patient was successfully tag as predischarge.";
				} else {
					$actionReturn = raiseError("Cannot untag pre-discharge, patient already tag as may go home.");
				}	
			}
			if ($actionReturn) $actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
		} else $msg = "Cannot untag, unknown reference " . $_GET['docno'];		
	} else $msg = "Cannot untag, unknown reference";

	if ($actionReturn) echo $msg;
	else echo $_SESSION["errormessage"];		
	
?>
