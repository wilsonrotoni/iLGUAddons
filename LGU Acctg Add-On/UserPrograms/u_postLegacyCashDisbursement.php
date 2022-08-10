<?php
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/payments.php");
	include_once("./classes/customers.php");
	include_once("./classes/paymentaccouns.php");
	include_once("./classes/paymentcheques.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./series.php");

$actionReturn = true;

$objRsMain = new recordset(null,$objConnection);
$objRs2 = new recordset(null,$objConnection);
$objRs3 = new recordset(null,$objConnection);

$obju_LGULegacyCash = new masterdataschema_br("u_lgulegacycash",$objConnection,"u_lgulegacycash");
$objPayments = new payments(null,$objConnection);
$objPaymentAccounts = new paymentaccounts(null,$objConnection);
$objPaymentCheques = new paymentcheques(null,$objConnection);
$objCustomers = new customers(null,$objConnection);

$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");

$objConnection->beginwork();

$ctrlaccts = array();
$ctrlaccts["1-03-05-040"] = "C";

$objRsMain->queryopen("select U_JEVNO, U_ADVNO, U_DATE, U_PAYEE from u_lgulegacycash where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_jevno<>'' and u_status='O' group by u_jevno order by u_jevno");
while ($objRsMain->queryfetchrow("NAME")) {
	var_dump($objRsMain->fields);
	echo "<br>";
	
	$isctrlacct = false;
	
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			


	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objRsMain->fields["U_DATE"];
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	//$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $_SESSION["branch"]."-".date('Y-m',strtotime($objJvHdr->docdate))."-".$objRsMain->fields["U_JEVNO"];
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = "";
	$objJvHdr->reference2 = "Cash Disbursement";
	
	$objJvHdr->remarks = $objRsMain->fields["U_PAYEE"];
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');

	if ($actionReturn) {
		$objRs2->queryopen("select A.CODE, A.U_GLACCTNO, B.ACCTNAME AS U_GLACCTNAME, A.U_DEBIT, A.U_CREDIT, A.U_DEBIT01, A.U_DEBIT02, A.U_DEBIT03, A.U_DEBIT04, A.U_DEBIT05, A.U_DEBIT06, A.U_DEBIT07, A.U_DEBIT08, A.U_DEBIT09, A.U_DEBIT10, A.U_DEBIT11, A.U_DEBIT12, A.U_DEBIT13, A.U_DEBIT14, A.U_DEBIT15, A.U_CREDIT01, A.U_CREDIT02, A.U_CREDIT03, A.U_CREDIT04, A.U_CREDIT05, A.U_CREDIT06, A.U_CREDIT07, A.U_CREDIT08, A.U_CREDIT09, A.U_CREDIT10, A.U_CREDIT11, A.U_CREDIT12, A.U_CREDIT13, A.U_CREDIT14, A.U_CREDIT15 from u_lgulegacycash A LEFT JOIN chartofaccounts B on B.formatcode=A.u_glacctno where A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.u_jevno='".$objRsMain->fields["U_JEVNO"]."' and A.u_status='O'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($obju_LGULegacyCash->getbykey($objRs2->fields["CODE"])) {
				var_dump($objRs2->fields);
				echo "<br>";
				
				/*if ($objRs2->fields["U_GLACCTNO"]!="") {
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
				}*/
				
				if ($actionReturn) {
					for ($ctr=1;$ctr<=15;$ctr++) {
						$field = "U_DEBIT" . str_pad( $ctr, 2, "0", STR_PAD_LEFT);
						$glacctno = "";
						$glacctname = "";
						$amount = floatval($objRs2->fields[$field]);
						if ($amount!=0) {
							$objRs3->queryopen("select u_glacctno, u_glacctname from u_lgulegacycashmap where code='$field' and u_glacctno<>''");
							if ($objRs3->queryfetchrow("NAME")) {
								if ($objRs3->fields["u_glacctno"]=="5-01-02-020/030") {
									$glacctno = "5-01-02-020";
									$glacctname = "Representation Allowance (RA)";
									$amount = round($objRs2->fields[$field]/2,2);
									
									$objJvDtl->prepareadd();
									$objJvDtl->docid = $objJvHdr->docid;
									$objJvDtl->objectcode = $objJvHdr->objectcode;
									$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
									$objJvDtl->refbranch = $_SESSION["branch"];
									$objJvDtl->projcode = "";
									$objJvDtl->itemtype = "A";
									$objJvDtl->itemno = $glacctno;
									$objJvDtl->itemname = $glacctname;
									
									$objJvDtl->debit = $amount;
									$objJvDtl->grossamount = $objJvDtl->debit;
									$objJvHdr->totaldebit += $objJvDtl->debit ;
									$objJvHdr->totalcredit += $objJvDtl->credit ;
									$objJvDtl->privatedata["header"] = $objJvHdr;
									$actionReturn = $objJvDtl->add();
									
									if ($actionReturn) {
										$glacctno = "5-01-02-030";
										$glacctname = "Transportation Allowance (TA)";
										
										$objJvDtl->prepareadd();
										$objJvDtl->docid = $objJvHdr->docid;
										$objJvDtl->objectcode = $objJvHdr->objectcode;
										$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
										$objJvDtl->refbranch = $_SESSION["branch"];
										$objJvDtl->projcode = "";
										$objJvDtl->itemtype = "A";
										$objJvDtl->itemno = $glacctno;
										$objJvDtl->itemname = $glacctname;
										
										$objJvDtl->debit = $objRs2->fields[$field]- $amount;
										$objJvDtl->grossamount = $objJvDtl->debit;
										$objJvHdr->totaldebit += $objJvDtl->debit ;
										$objJvHdr->totalcredit += $objJvDtl->credit ;
										$objJvDtl->privatedata["header"] = $objJvHdr;
										$actionReturn = $objJvDtl->add();
									}
									
								} else {
									$glacctno = $objRs3->fields["u_glacctno"];
									$glacctname = $objRs3->fields["u_glacctname"];
									
									$objJvDtl->prepareadd();
									$objJvDtl->docid = $objJvHdr->docid;
									$objJvDtl->objectcode = $objJvHdr->objectcode;
									$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
									$objJvDtl->refbranch = $_SESSION["branch"];
									$objJvDtl->projcode = "";
									$objJvDtl->itemtype = "A";
									$objJvDtl->itemno = $glacctno;
									$objJvDtl->itemname = $glacctname;
									
									if (isset($ctrlaccts[$glacctno])) {
										$isctrlacct = true;
										if ($objCustomers->getbysql("CUSTNAME='".$objRsMain->fields["U_PAYEE"]."'")) {
											$objJvDtl->itemtype = "C";
											$objJvDtl->itemno = $objCustomers->custno;
											$objJvDtl->itemname = $objCustomers->custname;
											$objJvDtl->reftype = "OUTDOWNPAYMENT";
											$objJvDtl->refno = $objRsMain->fields["U_ADVNO"];
										} else $actionReturn = raiseError("Unable to find Customer Name [".$objRsMain->fields["U_PAYEE"]."]");
									}
									
									if ($actionReturn) {
										if ($objRs2->fields[$field]>0) {
											$objJvDtl->debit = $objRs2->fields[$field];
											$objJvDtl->grossamount = $objJvDtl->debit;
										} else {
											$objJvDtl->credit = bcmul($objRs2->fields[$field],-1,14);
											$objJvDtl->grossamount = $objJvDtl->credit;
										}	
										$objJvHdr->totaldebit += $objJvDtl->debit ;
										$objJvHdr->totalcredit += $objJvDtl->credit ;
										$objJvDtl->privatedata["header"] = $objJvHdr;
										$actionReturn = $objJvDtl->add();
									}	
								}								
							} else $actionReturn = raiseError("Missing G/L Mapping for [".$field."]");
						}
						if (!$actionReturn) break;	
					}
				}
		
				if ($actionReturn) {
					for ($ctr=1;$ctr<=15;$ctr++) {
						$field = "U_CREDIT" . str_pad( $ctr, 2, "0", STR_PAD_LEFT);
						$glacctno = "";
						$glacctname = "";
						$amount = floatval($objRs2->fields[$field]);
						if ($amount!=0) {
							$objRs3->queryopen("select u_glacctno, u_glacctname from u_lgulegacycashmap where code='$field' and u_glacctno<>''");
							if ($objRs3->queryfetchrow("NAME")) {
								if ($objRs3->fields["u_glacctno"]=="5-01-02-020/030") {
									$glacctno = "5-01-02-020";
									$glacctname = "Representation Allowance (RA)";
									$amount = round($objRs2->fields[$field]/2,2);
									
									$objJvDtl->prepareadd();
									$objJvDtl->docid = $objJvHdr->docid;
									$objJvDtl->objectcode = $objJvHdr->objectcode;
									$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
									$objJvDtl->refbranch = $_SESSION["branch"];
									$objJvDtl->projcode = "";
									$objJvDtl->itemtype = "A";
									$objJvDtl->itemno = $glacctno;
									$objJvDtl->itemname = $glacctname;
									
									$objJvDtl->credit = $amount;
									$objJvDtl->grossamount = $objJvDtl->credit;
									$objJvHdr->totaldebit += $objJvDtl->debit ;
									$objJvHdr->totalcredit += $objJvDtl->credit ;
									$objJvDtl->privatedata["header"] = $objJvHdr;
									$actionReturn = $objJvDtl->add();
									
									if ($actionReturn) {
										$glacctno = "5-01-02-030";
										$glacctname = "Transportation Allowance (TA)";
										
										$objJvDtl->prepareadd();
										$objJvDtl->docid = $objJvHdr->docid;
										$objJvDtl->objectcode = $objJvHdr->objectcode;
										$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
										$objJvDtl->refbranch = $_SESSION["branch"];
										$objJvDtl->projcode = "";
										$objJvDtl->itemtype = "A";
										$objJvDtl->itemno = $glacctno;
										$objJvDtl->itemname = $glacctname;
										
										$objJvDtl->credit = $objRs2->fields[$field]- $amount;
										$objJvDtl->grossamount = $objJvDtl->credit;
										$objJvHdr->totaldebit += $objJvDtl->debit ;
										$objJvHdr->totalcredit += $objJvDtl->credit ;
										$objJvDtl->privatedata["header"] = $objJvHdr;
										$actionReturn = $objJvDtl->add();
									}
									
								} else {
									$glacctno = $objRs3->fields["u_glacctno"];
									$glacctname = $objRs3->fields["u_glacctname"];
									
									$objJvDtl->prepareadd();
									$objJvDtl->docid = $objJvHdr->docid;
									$objJvDtl->objectcode = $objJvHdr->objectcode;
									$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
									$objJvDtl->refbranch = $_SESSION["branch"];
									$objJvDtl->projcode = "";
									$objJvDtl->itemtype = "A";
									$objJvDtl->itemno = $glacctno;
									$objJvDtl->itemname = $glacctname;

									if (isset($ctrlaccts[$glacctno])) {
										if ($objCustomers->getbysql("CUSTNAME='".$objRsMain->fields["U_PAYEE"]."'")) {
											$objJvDtl->itemtype = "C";
											$objJvDtl->itemno = $objCustomers->custno;
											$objJvDtl->itemname = $objCustomers->custname;
											$objJvDtl->reftype = "OUTDOWNPAYMENT";
											$objJvDtl->refno = $objRsMain->fields["U_ADVNO"];
										} else $actionReturn = raiseError("Unable to find Customer Name [".$objRsMain->fields["U_PAYEE"]."]");
									}
									
									if ($actionReturn) {
										if ($objRs2->fields[$field]>0) {
											$objJvDtl->credit = $objRs2->fields[$field];
											$objJvDtl->grossamount = $objJvDtl->credit;
										} else {
											$objJvDtl->debit = bcmul($objRs2->fields[$field],-1,14);
											$objJvDtl->grossamount = $objJvDtl->debit;
										}	
										$objJvHdr->totaldebit += $objJvDtl->debit ;
										$objJvHdr->totalcredit += $objJvDtl->credit ;
										$objJvDtl->privatedata["header"] = $objJvHdr;
										$actionReturn = $objJvDtl->add();
									}	
								}								
							} else $actionReturn = raiseError("Missing G/L Mapping for [".$field."]");
						}
						if (!$actionReturn) break;	
					}
				}
				
				if (!$isctrlacct && $actionReturn && $objRs2->fields["U_GLACCTNO"]!="") {
					if ($objRs2->fields["U_DEBIT"]!=0) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->projcode = "";
						$objJvDtl->itemtype = "A";
						$objJvDtl->itemno = $objRs2->fields["U_GLACCTNO"];
						$objJvDtl->itemname = $objRs2->fields["U_GLACCTNAME"];
						
						if (isset($ctrlaccts[$objRs2->fields["U_GLACCTNO"]])) {
							if ($objCustomers->getbysql("CUSTNAME='".$objRsMain->fields["U_PAYEE"]."'")) {
								$objJvDtl->itemtype = "C";
								$objJvDtl->itemno = $objCustomers->custno;
								$objJvDtl->itemname = $objCustomers->custname;
								$objJvDtl->reftype = "OUTDOWNPAYMENT";
								$objJvDtl->refno = $objRsMain->fields["U_ADVNO"];
							} else $actionReturn = raiseError("Unable to find Customer Name [".$objRsMain->fields["U_PAYEE"]."]");
						}
						
						if ($actionReturn) {
							if ($objRs2->fields["U_DEBIT"]>0) {
								$objJvDtl->debit = $objRs2->fields["U_DEBIT"];
								$objJvDtl->grossamount = $objJvDtl->debit;
							} else {
								$objJvDtl->credit = bcmul($objRs2->fields["U_DEBIT"],-1,14);
								$objJvDtl->grossamount = $objJvDtl->credit;
							}	
							$objJvHdr->totaldebit += $objJvDtl->debit ;
							$objJvHdr->totalcredit += $objJvDtl->credit ;
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->add();
						}	
					}
					if ($actionReturn && $objRs2->fields["U_CREDIT"]!=0) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->projcode = "";
						$objJvDtl->itemtype = "A";
						$objJvDtl->itemno = $objRs2->fields["U_GLACCTNO"];
						$objJvDtl->itemname = $objRs2->fields["U_GLACCTNAME"];
						
						if (isset($ctrlaccts[$objRs2->fields["U_GLACCTNO"]])) {
							if ($objCustomers->getbysql("CUSTNAME='".$objRsMain->fields["U_PAYEE"]."'")) {
								$objJvDtl->itemtype = "C";
								$objJvDtl->itemno = $objCustomers->custno;
								$objJvDtl->itemname = $objCustomers->custname;
								$objJvDtl->reftype = "OUTDOWNPAYMENT";
								$objJvDtl->refno = $objRsMain->fields["U_ADVNO"];
							} else $actionReturn = raiseError("Unable to find Customer Name [".$objRsMain->fields["U_PAYEE"]."]");
						}
						
						if ($actionReturn) {
							if ($objRs2->fields["U_CREDIT"]>0) {
								$objJvDtl->credit = $objRs2->fields["U_CREDIT"];
								$objJvDtl->grossamount = $objJvDtl->credit;
							} else {
								$objJvDtl->debit = bcmul($objRs2->fields["U_CREDIT"],-1,14);
								$objJvDtl->grossamount = $objJvDtl->debit;
							}	
							$objJvHdr->totaldebit += $objJvDtl->debit ;
							$objJvHdr->totalcredit += $objJvDtl->credit ;
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->add();
						}	
					}
				}
				if ($actionReturn) {
					$obju_LGULegacyCash->setudfvalue("u_status","P");
					$obju_LGULegacyCash->setudfvalue("u_jvdocno",$objJvHdr->docno);
					$actionReturn = $obju_LGULegacyCash->update($obju_LGULegacyCash->code,$obju_LGULegacyCash->rcdversion);
				}
			} else $actionReturn = raiseError("Unable to find Legacy Cash Disbursement record [".$objRsMain->fields["CODE"]."]."); 	
			if (!$actionReturn) break;
		}
	}
	
	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
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