<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/documentschema_br.php");

	$actionReturn = true;
	$msg = "";
	if(isset($_GET['docno'])){
		if ($_GET['u_reftype']=="IP") $obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		else $obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
		$objConnection->beginwork();
		if ($obju_HISIPs->getbykey($_GET['docno'])) {
			if ($_GET['u_discharged']=="0") {
				$obju_HISIPs->setudfvalue("u_discharged",0);
				$obju_HISIPs->setudfvalue("u_enddate","");
				$obju_HISIPs->setudfvalue("u_endtime","");
				$obju_HISIPs->docstatus = "Active";
				$msg = "Patient discharge records was successfully updated.";
			} else {
				$obju_HISIPs->setudfvalue("u_enddate",formatDateToDB($_GET['u_enddate']));
				$obju_HISIPs->setudfvalue("u_endtime",formatTimeToDB($_GET['u_endtime']));
				$msg = "Patient discharge records was successfully updated.";
			}
			if ($actionReturn) $actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
		} else $msg = "Cannot update, unknown reference " . $_GET['docno'];		
	} else $msg = "Cannot update, unknown reference";

	if ($actionReturn) echo $msg;
	else echo $_SESSION["errormessage"];		
	
?>
