<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/collections.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./utils/suppliers.php");
	include_once("./utils/wtaxes.php");
	include_once("./utils/trxlog.php");

	function onAddEventpaymentsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->docstat!="D" && $objTable->getudfvalue("u_cano")!="") {
			$actionReturn = onCustomEventpaymentsCashAdvancesGPSHIS($objTable);
		}
		if ($actionReturn && $objTable->docstat!="D" && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventpaymentsBillPaymentGPSHIS($objTable);
			if ($actionReturn) $actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('outpay',$objTable);
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onAddEventpaymentsGPSHIS");
		return $actionReturn;
	}

	function onUpdateEventpaymentsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->docstat!=$objTable->fields["DOCSTAT"] && $objTable->fields["DOCSTAT"]=="D" && $objTable->getudfvalue("u_cano")!="") {
			$actionReturn = onCustomEventpaymentsCashAdvancesGPSHIS($objTable);
		}

		if ($actionReturn && $objTable->docstat!=$objTable->fields["DOCSTAT"] && $objTable->fields["DOCSTAT"]=="D" && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventpaymentsBillPaymentGPSHIS($objTable);
			if ($actionReturn) $actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('outpay',$objTable);
		} elseif ($actionReturn && $objTable->docstat!=$objTable->fields["DOCSTAT"] && $objTable->docstat=="CN" && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventpaymentsBillPaymentGPSHIS($objTable,true);
			if ($actionReturn) $actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('outpay',$objTable,true);
		}
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventpaymentsGPSHIS");
		return $actionReturn;
	}
	
	function onDeleteEventpaymentsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->docstat!="D" && $objTable->getudfvalue("u_cano")!="") {
			$actionReturn = onCustomEventpaymentsCashAdvancesGPSHIS($objTable,true);
		}
		if ($actionReturn && $objTable->docstat!="D" && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventpaymentsBillPaymentGPSHIS($objTable,true);
			if ($actionReturn) $actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('outpay',$objTable,true);
		}
		
		
		return $actionReturn;
	}
	
	function onCustomEventpaymentsCashAdvancesGPSHIS($objTable,$delete=false) {
		global $objConnection;
		$actionReturn=true;
		
		if ($delete) $reverse = true;
		if ($objTable->docstat=="CN") $reverse = true;
		
		$obju_HISCashAdvances = new documentschema_br(null,$objConnection,"u_hiscashadvances");
		if ($obju_HISCashAdvances->getbykey($objTable->getudfvalue("u_cano"))) {
			if ($obju_HISCashAdvances->getudfvalue("u_amount")!=$objTable->paidamount) return raiseError("Paid Amount [".$objTable->paidamount."] and Cash Advance [".$obju_HISCashAdvances->getudfvalue("u_amount")."] must be the same.");
			if ($obju_HISCashAdvances->getudfvalue("u_empid")!=$objTable->bpcode) return raiseError("BP Code [".$objTable->bpcode."] and Cash Advance Employee No. [".$obju_HISCashAdvances->getudfvalue("u_empid")."] must be the same.");
			if (!$reverse) {
				$obju_HISCashAdvances->setudfvalue("u_cvno",$objTable->docno);
				//$obju_HISCashAdvances->setudfvalue("u_cvdate",$objTable->docdate);
				$obju_HISCashAdvances->docstatus = "C";
			} else {
				$obju_HISCashAdvances->setudfvalue("u_cvno","");
				$obju_HISCashAdvances->setudfvalue("u_cvdate","");
				$obju_HISCashAdvances->docstatus = "O";
			}	
			$actionReturn = $obju_HISCashAdvances->update($obju_HISCashAdvances->docno,$obju_HISCashAdvances->rcdversion);
		} else raiseError("Unable to find Cash Advance No. [".$objTable->getudfvalue("u_cano")."].");
						
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventpaymentsCashAdvancesGPSHIS()");
		return $actionReturn;
	}	

	function onCustomEventpaymentsBillPaymentGPSHIS($objTable,$delete=false) {
		global $objConnection;
		$actionReturn=true;
		
		if ($delete) $reverse = true;
		if ($objTable->docstat=="CN") $reverse = true;
		
		$objRs = new recordset(null,$objConnection);
		$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
		$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
		$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");
		$obju_HISPOs = new documentschema_br(null,$objConnection,"u_hispos");
		
		
		if ($actionReturn) {
			$objRs->queryopen("select c.docno,d.u_feetype,d.u_doctorid, xb.amount
	  from payments xa
		inner join paymentinvoices xb on xb.company=xa.company and xb.branch=xa.branchcode and xb.docno = xa.docno and xb.reftype='JOURNALVOUCHER'
		inner join journalvoucheritems a ON a.company = xb.company and a.branch = xb.branch and a.docno=xb.refno
		inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
		inner join u_hisbills c on c.company=a.company and c.branch=a.branch and c.docno=b.otherdocno
		inner join u_hisbillfees d on c.company=a.company and c.branch=a.branch and d.docid=c.docid and d.u_doctorid=a.otherbpcode and d.u_feetype=a.othertrxtype
	  where xa.company='".$_SESSION["company"]."' and xa.branchcode='".$_SESSION["branch"]."' and xa.docno='".$objTable->docno."'");
			while ($objRs->queryfetchrow("NAME")) {
				//var_dump($objRs->fields);
				if ($obju_HISBills->getbykey($objRs->fields["docno"])) {
					if ($obju_HISBillFees->getbysql("DOCID='$obju_HISBills->docid' AND U_FEETYPE='".$objRs->fields["u_feetype"]."' AND U_DOCTORID='".$objRs->fields["u_doctorid"]."'")) {
						if (!$delete) {
							$obju_HISBillFees->setudfvalue("u_paidamount",$obju_HISBillFees->getudfvalue("u_paidamount")-$objRs->fields["amount"]);
							$obju_HISBills->setudfvalue("u_paidamount",$obju_HISBills->getudfvalue("u_paidamount")-$objRs->fields["amount"]);
						} else {
							$obju_HISBillFees->setudfvalue("u_paidamount",$obju_HISBillFees->getudfvalue("u_paidamount")+$objRs->fields["amount"]);
							$obju_HISBills->setudfvalue("u_paidamount",$obju_HISBills->getudfvalue("u_paidamount")+$objRs->fields["amount"]);
						}	
						if ($obju_HISBills->docstatus!="CN") {
							if (bcsub($obju_HISBills->getudfvalue("u_dueamount"),$obju_HISBills->getudfvalue("u_paidamount"),14)==0) $obju_HISBills->docstatus = "C";
							else $obju_HISBills->docstatus = "O";
						}	
						$obju_HISBillFees->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
						if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
					} else return raiseError("Unable to find Bill No.[".$objRs->fields["docno"]."] Fee Type [".$objRs->fields["u_feetype"]."] Doctor ID [".$objRs->fields["u_doctorid"]."]");	
				} else return raiseError("Unable to find Bill No.[".$objRs->fields["docno"]."]");	
				if (!$actionReturn) break;
			}
		}	

		if ($actionReturn) {
			$objRs->queryopen("select c.docno, xb.amount
	  from payments xa
		inner join paymentinvoices xb on xb.company=xa.company and xb.branch=xa.branchcode and xb.docno = xa.docno and xb.reftype='ARCREDITMEMO'
		inner join arcreditmemos a ON a.company = xb.company and a.branch = xb.branch and a.docno=xb.refno
		inner join u_hiscredits c on c.company=xb.company and c.branch=xb.branch and c.docno=xb.refno
	  where xa.company='".$_SESSION["company"]."' and xa.branchcode='".$_SESSION["branch"]."' and xa.docno='".$objTable->docno."'");
			while ($objRs->queryfetchrow("NAME")) {
				//var_dump($objRs->fields);
				if ($obju_HISCredits->getbykey($objRs->fields["docno"])) {
					if (!$delete) {
						$obju_HISCredits->setudfvalue("u_credited",$obju_HISCredits->getudfvalue("u_credited")+$objRs->fields["amount"]);
						$obju_HISCredits->setudfvalue("u_balance",$obju_HISCredits->getudfvalue("u_balance")-$objRs->fields["amount"]);
					} else {
						$obju_HISCredits->setudfvalue("u_credited",$obju_HISCredits->getudfvalue("u_credited")-$objRs->fields["amount"]);
						$obju_HISCredits->setudfvalue("u_balance",$obju_HISCredits->getudfvalue("u_balance")+$objRs->fields["amount"]);
					}	
					if (bcsub($obju_HISCredits->getudfvalue("u_balance"),0,14)==0) $obju_HISCredits->docstatus = "C";
					else $obju_HISCredits->docstatus = "O";
					$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
				} else return raiseError("Unable to find Credit No.[".$objRs->fields["docno"]."]");	
				if (!$actionReturn) break;
			}
		}	

		if ($actionReturn) {
			$objRs->queryopen("select c.docno, xb.amount
	  from payments xa
		inner join paymentinvoices xb on xb.company=xa.company and xb.branch=xa.branchcode and xb.docno = xa.docno and xb.reftype='DOWNPAYMENT'
		inner join collections a ON a.company = xb.company and a.branchcode = xb.branch and a.docno=xb.refno
		inner join u_hispos c on c.company=xb.company and c.branch=xb.branch and c.docno=xb.refno
	  where xa.company='".$_SESSION["company"]."' and xa.branchcode='".$_SESSION["branch"]."' and xa.docno='".$objTable->docno."'");
			while ($objRs->queryfetchrow("NAME")) {
				//var_dump($objRs->fields);
				if ($obju_HISPOs->getbykey($objRs->fields["docno"])) {
					if (!$delete) {
						$obju_HISPOs->setudfvalue("u_credited",$obju_HISPOs->getudfvalue("u_credited")+$objRs->fields["amount"]);
						$obju_HISPOs->setudfvalue("u_balance",$obju_HISPOs->getudfvalue("u_balance")-$objRs->fields["amount"]);
					} else {
						$obju_HISPOs->setudfvalue("u_credited",$obju_HISPOs->getudfvalue("u_credited")-$objRs->fields["amount"]);
						$obju_HISPOs->setudfvalue("u_balance",$obju_HISPOs->getudfvalue("u_balance")+$objRs->fields["amount"]);
					}	
					//if (bcsub($obju_HISPOs->getudfvalue("u_balance"),0,14)==0) $obju_HISPOs->docstatus = "C";
					//else $obju_HISPOs->docstatus = "O";
					//var_dump(array($obju_HISPOs->docno,$obju_HISPOs->getudfvalue("u_balance")));
					$actionReturn = $obju_HISPOs->update($obju_HISPOs->docno,$obju_HISPOs->rcdversion);
				} else return raiseError("Unable to find O.R. No.[".$objRs->fields["docno"]."]");	
				if (!$actionReturn) break;
			}
		}	
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventpaymentsBillPaymentGPSHIS()");
		return $actionReturn;
	}	

?>
