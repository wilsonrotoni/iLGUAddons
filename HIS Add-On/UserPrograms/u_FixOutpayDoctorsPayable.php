<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/payments.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs3 = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	$objPayments = new payments(null,$objConnection);

	$ctr=0;
	
	if ($actionReturn) {
		if ($objPayments->getbykey($branch,$_GET["docno"])) {
			$ctr++;
			if ($actionReturn && $objPayments->docstat!="D" && $objPayments->collfor=="SI") {
				if ($actionReturn) $actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('outpay',$objPayments);
				if ($actionReturn) {
					///$objConnection->rollback();
					$objConnection->commit();
					printf("Records where successfully updated.");
				} else { 	
					$objConnection->rollback();
					echo $_SESSION["errormessage"];
				}
			}
		}
	}	
	

	
?>
