<?php
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/smsoutlog.php");
	include_once("./series.php");
	
	$objRs = new recordset(null,$objConnection);
	$objSMSOutLog = new smsoutlog(null,$objConnection);

	$objConnection->beginwork();
	
	$objRs->queryopen("select docno, u_custname, u_contactno from u_lgupos where u_date>='2016-01-01' and u_contactno<>''");
	while ($objRs->queryfetchrow("NAME")) {
		echo  $objRs->fields["docno"] . ", " . $objRs->fields["u_custname"] . ", " . $objRs->fields["u_contactno"] . "<br>";
		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
		$objSMSOutLog->deviceid = "sun";
		$objSMSOutLog->mobilenumber = $objRs->fields["u_contactno"];
		$objSMSOutLog->remark = "Payment";
		$objSMSOutLog->message = "In behalf of Municipality of Agoo, We would like to thank you for being diligent and good tax payer.";
		$actionReturn = $objSMSOutLog->add();
		if (!$actionReturn) break;
	}
	if ($actionReturn) {
		$objConnection->commit();
	} else {
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}	
?>	