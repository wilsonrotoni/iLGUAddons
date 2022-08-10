<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/documentschema_br.php");
	require_once("./classes/documentlinesschema_br.php");
	require_once("./classes/journalvouchers.php");
	require_once("./classes/journalvoucheritems.php");
	require_once("./classes/users.php");
	require_once("./classes/usermsgs.php");
	require_once("./series.php");

	function executeTaskbilltrxreconsGPSHIS() {
		global $objConnection;
		$result = array(1,"");
		//var_dump($_SESSION["addons"]['Audit Trail Add-On']['status']);
		//$_SESSION["addons"]['Audit Trail Add-On']['status'] = 'Stopped';
		$actionReturn = true;
		
		$objRs = new recordset(null,$objConnection);
		$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
		$obju_HISBillTrxRecons = new documentlinesschema_br(null,$objConnection,"u_hisbilltrxrecons");
		$objJvHdr = new journalvouchers(null,$objConnection);
		$objJvDtl = new journalvoucheritems(null,$objConnection);
		
		$docdatefrom=dateadd("d",-1,currentdateDB());
		$docdateto=$docdatefrom;
		$objRs->queryopen("select min(docdatefrom) from postingperiods where status='O'");
		if ($objRs->queryfetchrow()) {
			$docdatefrom=$objRs->fields[0];
		}
		var_dump(array($docdatefrom,$docdateto));
		$objConnection->beginwork();
		$obju_HISBills->queryopen($obju_HISBills->selectstring(). " AND U_DOCDATE BETWEEN '$docdatefrom' AND '$docdateto' AND DOCSTATUS IN ('O','C')");
		while ($obju_HISBills->queryfetchrow()) {
			if ($objJvHdr->getbykey($obju_HISBills->docno)) {
				var_dump($obju_HISBills->docno);
				$objRs->queryopen("select docno,docdate,dueamount from arinvoices where company='$obju_HISBills->company' and branch='$obju_HISBills->branch' and bpcode='".$obju_HISBills->getudfvalue("u_patientid")."' and bprefno='".$obju_HISBills->getudfvalue("u_refno")."' and totalamount<>dueamount and dueamount<>0 and abs(dueamount)<.1");
				while ($objRs->queryfetchrow("NAME")) {
					var_dump($objRs->fields["docno"]);
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_HISBills->branch;
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $obju_HISBills->getudfvalue("u_patientid");
					$objJvDtl->itemname = $obju_HISBills->getudfvalue("u_patientname");
					$objJvDtl->reftype = "ARINVOICE";
					$objJvDtl->refno = $objRs->fields["docno"];
					if ($objRs->fields["dueamount"]>0) {
						$objJvDtl->credit = $objRs->fields["dueamount"];
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $objRs->fields["dueamount"]*-1;
						$objJvDtl->grossamount = $objJvDtl->debit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					
					if ($actionReturn) {
						$obju_HISBillTrxRecons->prepareadd();
						$obju_HISBillTrxRecons->docid = $obju_HISBills->docid;
						$obju_HISBillTrxRecons->lineid = getNextIdByBranch("u_hisbilltrxrecons",$objConnection);
						$obju_HISBillTrxRecons->setudfvalue("u_docdate",$objRs->fields["docdate"]);
						$obju_HISBillTrxRecons->setudfvalue("u_docno",$objRs->fields["docno"]);
						$obju_HISBillTrxRecons->setudfvalue("u_doctype","ARINVOICE");
						$obju_HISBillTrxRecons->setudfvalue("u_docdesc","Charges");
						$obju_HISBillTrxRecons->setudfvalue("u_amount",$objRs->fields["dueamount"]);
						$obju_HISBillTrxRecons->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillTrxRecons->add();
					}
					
					if (!$actionReturn) break;
				}
				
				if ($actionReturn) {
					$objRs->queryopen("select docno,dueamount from arcreditmemos where company='$obju_HISBills->company' and branch='$obju_HISBills->branch' and bpcode='".$obju_HISBills->getudfvalue("u_patientid")."' and bprefno='".$obju_HISBills->getudfvalue("u_refno")."' and totalamount<>dueamount and dueamount<>0 and abs(dueamount)<.1");
					while ($objRs->queryfetchrow("NAME")) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $obju_HISBills->branch;
						$objJvDtl->itemtype = "C";
						$objJvDtl->itemno = $obju_HISBills->getudfvalue("u_patientid");
						$objJvDtl->itemname = $obju_HISBills->getudfvalue("u_patientname");
						$objJvDtl->reftype = "ARCREDITMEMO";
						$objJvDtl->refno = $objRs->fields["docno"];
						if ($objRs->fields["dueamount"]>0) {
							$objJvDtl->debit = $objRs->fields["dueamount"];
							$objJvDtl->grossamount = $objJvDtl->debit;
						} else {
							$objJvDtl->credit = $objRs->fields["dueamount"]*-1;
							$objJvDtl->grossamount = $objJvDtl->credit;
						}	
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();
						
						if ($actionReturn) {
							$obju_HISBillTrxRecons->prepareadd();
							$obju_HISBillTrxRecons->docid = $obju_HISBills->docid;
							$obju_HISBillTrxRecons->lineid = getNextIdByBranch("u_hisbilltrxrecons",$objConnection);
							$obju_HISBillTrxRecons->setudfvalue("u_docdate",$objRs->fields["docdate"]);
							$obju_HISBillTrxRecons->setudfvalue("u_docno",$objRs->fields["docno"]);
							$obju_HISBillTrxRecons->setudfvalue("u_doctype","ARINVOICE");
							$obju_HISBillTrxRecons->setudfvalue("u_docdesc","Credits");
							$obju_HISBillTrxRecons->setudfvalue("u_amount",$objRs->fields["dueamount"]*-1);
							$obju_HISBillTrxRecons->privatedata["header"] = $obju_HISBills;
							$actionReturn = $obju_HISBillTrxRecons->add();
						}
						
						if (!$actionReturn) break;
					}
				}
								
				if ($actionReturn && $objJvHdr->totaldebit!=$objJvHdr->totalcredit ) {
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_HISBills->branch;
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = "4110303";
					$objJvDtl->itemname = "Miscellaneous Income";
					if ($objJvHdr->totaldebit>$objJvHdr->totalcredit) {
						$objJvDtl->credit = $objJvHdr->totaldebit-$objJvHdr->totalcredit;
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $objJvHdr->totalcredit-$objJvHdr->totaldebit;
						$objJvDtl->grossamount = $objJvDtl->debit;
					}
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}

				if ($actionReturn) $actionReturn = $objJvHdr->update($objJvHdr->docno,$objJvHdr->rcdversion);
				
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