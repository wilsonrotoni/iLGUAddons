<?php
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/payments.php");
	include_once("./classes/customers.php");
	include_once("./classes/paymentaccouns.php");
	include_once("./classes/paymentcheques.php");
	include_once("./series.php");

$actionReturn = true;

$objRsMain = new recordset(null,$objConnection);
$objRs2 = new recordset(null,$objConnection);
$objRs3 = new recordset(null,$objConnection);

$obju_LGULegacyCheck = new masterdataschema_br("u_tcslegacypaydtls",$objConnection,"u_lgulegacycheck");
$objPayments = new payments(null,$objConnection);
$objPaymentAccounts = new paymentaccounts(null,$objConnection);
$objPaymentCheques = new paymentcheques(null,$objConnection);
$objCustomers = new customers(null,$objConnection);

$settings = getBusinessObjectSettings("OUTGOINGPAYMENT");

$objConnection->beginwork();

$ctrlaccts = array();
$ctrlaccts["1-03-05-040"] = "C";

$objRsMain->queryopen("select U_JEVNO, U_DATE, U_PAYEE, U_REFNO, U_BANK, U_BANKACCTNO, SUM(U_AMOUNT) AS U_AMOUNT from u_lgulegacycheck where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_jevno<>'' and u_status='O' group by u_jevno order by u_jevno");
while ($objRsMain->queryfetchrow("NAME")) {
	var_dump($objRsMain->fields);
	echo "<br>";
	
	$isctrlacct = false;
	
	$objPayments->prepareadd();
	$objPayments->docseries = -1;
	$objPayments->docid = getNextIdByBranch('payments',$objConnection);
	$objPayments->sbo_post_flag = $settings["autopost"];
	$objPayments->jeposting = $settings["jeposting"];
	$objPayments->docstat = "O";
	$objPayments->objectcode = "OUTGOINGPAYMENT";
	$objPayments->branchcode = $_SESSION["branch"];
	$objPayments->acctbranch = $_SESSION["branch"];
	$objPayments->currency = $_SESSION["currency"];
	$objPayments->docno = $objRsMain->fields["U_REFNO"];
	$objPayments->bpname = $objRsMain->fields["U_PAYEE"];
	$objPayments->docdate = $objRsMain->fields["U_DATE"];
	$objPayments->docdocdate = $objPayments->docdate;
	$objPayments->taxdate = $objPayments->docdate;
	$objPayments->doctype = "A";
		
	if ($actionReturn) {
		$objPaymentCheques->prepareadd();
		$objPaymentCheques->docid = getNextIdByBranch('paymentcheques',$objConnection);
		$objPaymentCheques->objectcode = $objPayments->objectcode;
		$objPaymentCheques->docno = $objPayments->docno;
		$objPaymentCheques->country = $_SESSION["country"];
		$objPaymentCheques->bank = $objRsMain->fields["U_BANK"];
		$objPaymentCheques->bankacctno = $objRsMain->fields["U_BANKACCTNO"];
		$objPaymentCheques->checkdate = $objRsMain->fields["U_DATE"];
		$objPaymentCheques->checkno = $objRsMain->fields["U_REFNO"];
		$objPaymentCheques->amount = $objRsMain->fields["U_AMOUNT"];
		$objPaymentCheques->privatedata["header"] = $objPayments;
		$objPayments->chequeamount = bcadd($objPayments->chequeamount, $objPaymentCheques->amount, 14);
	
		$actionReturn = $objPaymentCheques->add();
	}
		
	if ($actionReturn) {
		$objRs2->queryopen("select A.CODE, A.U_GLACCTNO, B.ACCTNAME AS U_GLACCTNAME, A.U_DEBIT, A.U_CREDIT, A.U_DEBIT01, A.U_DEBIT02, A.U_DEBIT03, A.U_DEBIT04, A.U_DEBIT05, A.U_DEBIT06, A.U_DEBIT07, A.U_DEBIT08, A.U_DEBIT09, A.U_DEBIT10, A.U_DEBIT11, A.U_DEBIT12, A.U_DEBIT13, A.U_DEBIT14, A.U_DEBIT15, A.U_DEBIT16, A.U_DEBIT17, A.U_DEBIT18, A.U_DEBIT19, A.U_DEBIT20, A.U_CREDIT01, A.U_CREDIT02, A.U_CREDIT03, A.U_CREDIT04, A.U_CREDIT05, A.U_CREDIT06, A.U_CREDIT07, A.U_CREDIT08, A.U_CREDIT09, A.U_CREDIT10, A.U_CREDIT11, A.U_CREDIT12, A.U_CREDIT13, A.U_CREDIT14, A.U_CREDIT15 from u_lgulegacycheck A LEFT JOIN chartofaccounts B on B.formatcode=A.u_glacctno where A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.u_jevno='".$objRsMain->fields["U_JEVNO"]."' and A.u_status='O'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($obju_LGULegacyCheck->getbykey($objRs2->fields["CODE"])) {
				var_dump($objRs2->fields);
				echo "<br>";
				
				if ($objRs2->fields["U_GLACCTNO"]!="") {
					if (isset($ctrlaccts[$objRs2->fields["U_GLACCTNO"]])) {
						$isctrlacct = true;
						$objPayments->doctype = $ctrlaccts[$objRs2->fields["U_GLACCTNO"]];
						$objPayments->collfor = "DP";
						if ($objPayments->doctype=="C") {
							if ($objCustomers->getbysql("CUSTNAME='".$objRsMain->fields["U_PAYEE"]."'")) {
								$objPayments->bpcode = $objCustomers->custno;
							} else $actionReturn = raiseError("Unable to find Customer Name [".$objRsMain->fields["U_PAYEE"]."]");
						}
					}
				}
				
				if (!$isctrlacct && $actionReturn) {
					for ($ctr=1;$ctr<=20;$ctr++) {
						$field = "U_DEBIT" . str_pad( $ctr, 2, "0", STR_PAD_LEFT);
						$glacctno = "";
						$glacctname = "";
						$amount = floatval($objRs2->fields[$field]);
						if ($amount>0) {
							$objRs3->queryopen("select u_glacctno, u_glacctname from u_lgulegacycheckmap where code='$field' and u_glacctno<>''");
							if ($objRs3->queryfetchrow("NAME")) {
								$glacctno = $objRs3->fields["u_glacctno"];
								$glacctname = $objRs3->fields["u_glacctname"];
								
								$objPaymentAccounts->prepareadd();
								$objPaymentAccounts->docid = getNextIdByBranch('paymentaccounts',$objConnection);
								$objPaymentAccounts->docno = $objPayments->docno;
								$objPaymentAccounts->objectcode = $objPayments->objectcode;
								$objPaymentAccounts->refbranch = $_SESSION["branch"];
								$objPaymentAccounts->itemtype = "A";
								$objPaymentAccounts->linestatus = "C";
								$objPaymentAccounts->glacctno = $glacctno;
								$objPaymentAccounts->glacctname = $glacctname;
								$objPaymentAccounts->amount = $objRs2->fields[$field];
								$objPaymentAccounts->privatedata["header"] = $objPayments;
								$objPaymentAccounts->grossamount = $objPaymentAccounts->amount;
								$actionReturn = $objPaymentAccounts->add();
								$objPayments->dueamount = bcadd($objPayments->dueamount, $objPaymentAccounts->amount, 14);
								
							} else return raiseError("Missing G/L Mapping for [".$field."]");
						}
						if (!$actionReturn) break;	
					}
				}
		
				if (!$isctrlacct && $actionReturn) {
					for ($ctr=1;$ctr<=15;$ctr++) {
						$field = "U_CREDIT" . str_pad( $ctr, 2, "0", STR_PAD_LEFT);
						$glacctno = "";
						$glacctname = "";
						$amount = floatval($objRs2->fields[$field]);
						if ($amount>0) {
							$objRs3->queryopen("select u_glacctno, u_glacctname from u_lgulegacycheckmap where code='$field' and u_glacctno<>''");
							if ($objRs3->queryfetchrow("NAME")) {
								$glacctno = $objRs3->fields["u_glacctno"];
								$glacctname = $objRs3->fields["u_glacctname"];
								
								$objPaymentAccounts->prepareadd();
								$objPaymentAccounts->objectcode = $objPayments->objectcode;
								$objPaymentAccounts->refbranch = $_SESSION["branch"];
								$objPaymentAccounts->itemtype = "A";
								$objPaymentAccounts->linestatus = "C";
								$objPaymentAccounts->glacctno = $glacctno;
								$objPaymentAccounts->glacctname = $glacctname;
								$objPaymentAccounts->amount = bcmul($objRs2->fields[$field],-1,14);
								$objPaymentAccounts->docno = $objPayments->docno;
								$objPaymentAccounts->privatedata["header"] = $objPayments;
								$objPaymentAccounts->docid = getNextIdByBranch('paymentaccounts',$objConnection);
								$objPaymentAccounts->grossamount = $objPaymentAccounts->amount;
								$actionReturn = $objPaymentAccounts->add();
								$objPayments->dueamount = bcadd($objPayments->dueamount, $objPaymentAccounts->amount, 14);
								
							} else return raiseError("Missing G/L Mapping for [".$field."]");
						}
						if (!$actionReturn) break;	
					}
				}
				
				if (!$isctrlacct && $actionReturn && $objRs2->fields["U_GLACCTNO"]!="") {
					if ($objRs2->fields["U_DEBIT"]>0) {
						$objPaymentAccounts->prepareadd();
						$objPaymentAccounts->objectcode = $objPayments->objectcode;
						$objPaymentAccounts->refbranch = $_SESSION["branch"];
						$objPaymentAccounts->itemtype = "A";
						$objPaymentAccounts->linestatus = "C";
						$objPaymentAccounts->glacctno = $objRs2->fields["U_GLACCTNO"];
						$objPaymentAccounts->glacctname = $objRs2->fields["U_GLACCTNAME"];
						$objPaymentAccounts->amount = $objRs2->fields["U_DEBIT"];
						$objPaymentAccounts->docno = $objPayments->docno;
						$objPaymentAccounts->privatedata["header"] = $objPayments;
						$objPaymentAccounts->docid = getNextIdByBranch('paymentaccounts',$objConnection);
						$objPaymentAccounts->grossamount = $objPaymentAccounts->amount;
						$actionReturn = $objPaymentAccounts->add();
						$objPayments->dueamount = bcadd($objPayments->dueamount, $objPaymentAccounts->amount, 14);
					}
					if ($actionReturn && $objRs2->fields["U_CREDIT"]>0) {
						$objPaymentAccounts->prepareadd();
						$objPaymentAccounts->objectcode = $objPayments->objectcode;
						$objPaymentAccounts->refbranch = $_SESSION["branch"];
						$objPaymentAccounts->itemtype = "A";
						$objPaymentAccounts->linestatus = "C";
						$objPaymentAccounts->glacctno = $objRs2->fields["U_GLACCTNO"];
						$objPaymentAccounts->glacctname = $objRs2->fields["U_GLACCTNAME"];
						$objPaymentAccounts->amount = bcmul($objRs2->fields["U_CREDIT"],-1,14);
						$objPaymentAccounts->docno = $objPayments->docno;
						$objPaymentAccounts->privatedata["header"] = $objPayments;
						$objPaymentAccounts->docid = getNextIdByBranch('paymentaccounts',$objConnection);
						$objPaymentAccounts->grossamount = $objPaymentAccounts->amount;
						$actionReturn = $objPaymentAccounts->add();
						$objPayments->dueamount = bcadd($objPayments->dueamount, $objPaymentAccounts->amount, 14);
					}
				}
				if ($actionReturn) {
					$obju_LGULegacyCheck->setudfvalue("u_status","P");
					$obju_LGULegacyCheck->setudfvalue("u_pvdocno",$objPayments->docno);
					$actionReturn = $obju_LGULegacyCheck->update($obju_LGULegacyCheck->code,$obju_LGULegacyCheck->rcdversion);
				}
			} else $actionReturn = raiseError("Unable to find Legacy Check Disbursement record [".$objRsMain->fields["CODE"]."]."); 	
			if (!$actionReturn) break;
		}
	}
	
	if ($actionReturn) {
		$objPayments->cleared = 1;
		$objPayments->valuedate = $objPayments->docdate;
		$objPayments->paidamount = $objPayments->chequeamount;
		$objPayments->balanceamount = $objPayments->paidamount - $objPayments->dueamount;
		if ($objPayments->docstat!="D") $objPayments->docstat = iif($objPayments->balanceamount==0,"C","O");
		$objPayments->journalremark = "Outgoing Payment - " . $objPayments->bpcode;
		if ($objPayments->docstat=="D") $objPayments->sbo_post_flag=0;
		$actionReturn = $objPayments->add();
	}	
	if (!$actionReturn) break;
}

//if ($actionReturn) $actionReturn = raiseError("force error");
if ($actionReturn) {
//$objConnection->rollback();
	$objConnection->commit();
	echo "Done.";
} else {
	echo $_SESSION["errormessage"];
	$objConnection->rollback();
	
}	

?>