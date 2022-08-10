<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/collections.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentcashcards.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs3 = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);

	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISBillTrxs = new documentlinesschema_br(null,$objConnection,"u_hisbilltrxs");

	$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");

	$objJVHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs3->fields["BRANCH"];
	
	$ctr=0;
	
	if ($actionReturn) {
		$objRs3->queryopen("select a.u_reftype, a.u_patientname, b.u_billno,c.docdate,a.docno,if(a.U_PREPAID=1,a.U_AMOUNT,a.U_AMOUNTBEFDISC) as u_amount, d.debit,d.credit, a.u_startdate, a.u_starttime, a.docstatus from u_hiscredits a
    inner join u_hisips b on b.company=a.company and b.branch=a.branch and b.docno=a.u_refno and b.u_billno<>''
    inner join journalvouchers c on c.company=b.company and c.branch=a.branch and c.docno=b.u_billno
    inner join journalvoucheritems d on d.company=c.company and d.branch=c.branch and d.docid=c.docid and d.itemno='4110303' and debit>0
    where a.u_prepaid=2 and a.u_reftype='IP' and a.u_billno='' and a.u_requesttype='CHRG' order by b.u_billno, a.docno");
		while ($objRs3->queryfetchrow("NAME")) {
			//echo $objRs3->fields["u_jvdocno"] . "<br>";
			if ($obju_HISBills->getbykey($objRs3->fields["u_billno"])) {
				if ($objJVHdr->getbykey($objRs3->fields["u_billno"])) {
					var_dump(array($objRs3->fields["u_billno"],$objRs3->fields["u_patientname"],$objRs3->fields["docno"],$objRs3->fields["u_amount"]));
					
					if ($objJvDtl->getbysql("DOCID='$objJVHdr->docid' AND ITEMTYPE='A' and ITEMNO='4110303'")) {
						var_dump($objJvDtl->debit);
						$objJvDtl->debit -= $objRs3->fields["u_amount"];
						$objJvDtl->grossamount = abs($objJvDtl->debit);
						$objJvDtl->privatedata["header"] = $objJVHdr;
						if ($objJvDtl->debit==0) {
							$actionReturn = $objJvDtl->delete();
						} else if ($objJvDtl->debit>0) {
							$actionReturn = $objJvDtl->update($objJvDtl->lineid,$objJvDtl->rcdversion);
						} else if ($objJvDtl->debit<0) {
							$objJvDtl->credit = $objJvDtl->debit*-1;
							$objJvDtl->debit = 0;
							$actionReturn = $objJvDtl->update($objJvDtl->lineid,$objJvDtl->rcdversion);
						}
					} else $actionReturn = raiseError("Unable to Find Journal [".$objRs3->fields["u_billno"]."] Line with Miscellaneous Income");
					echo "<br>";
					if ($actionReturn) {
						/*if ($obju_HISCredits->getbykey($objRs3->fields["docno"])) {
							$balance = ($objRs3->fields["u_amount"]);
							$obju_HISCredits->setudfvalue("u_billno",$objTable->docno);
							if ($balance>0) {
								if ($obju_HISCredits->getudfvalue("u_balance")==$balance) {
									$obju_HISCredits->setudfvalue("u_balance",0);
									$obju_HISCredits->setudfvalue("u_credited",$obju_HISCredits->getudfvalue("u_credited")+$balance);
								} else return raiseError("Return/Credit No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISCredits->getudfvalue("u_balance")."] during creation of billing document.");
							}
							$obju_HISCredits->docstatus = iif($obju_HISCredits->getudfvalue("u_balance")==0,"C","O");
							$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
						} else return raiseError("Unable to find Return/Credit No [".$objRs->fields["U_DOCNO"]."].");
						*/
					}
					if ($actionReturn) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJVHdr->docid;
						$objJvDtl->objectcode = $objJVHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->itemtype = "C";
						$objJvDtl->itemno = $obju_HISBills->getudfvalue("u_patientid");
						$objJvDtl->itemname = $obju_HISBills->getudfvalue("u_patientname");
						$objJvDtl->remarks = "";
						$objJvDtl->reftype = "ARCREDITMEMO";
						$objJvDtl->refno = $objRs3->fields["docno"];
						$objJvDtl->debit = $objRs3->fields["u_amount"];
						$objJvDtl->grossamount = $objJvDtl->debit;
						$objJvDtl->privatedata["header"] = $objJVHdr;
						$actionReturn = $objJvDtl->add();
					}
					if ($actionReturn) {
						$obju_HISBillTrxs->prepareadd();
						$obju_HISBillTrxs->docid = $obju_HISBills->docid;
						$obju_HISBillTrxs->lineid = getNextIdByBranch("u_hisbilltrxs",$objConnection);
						$obju_HISBillTrxs->setudfvalue("u_docdate",$objRs3->fields["u_startdate"]);
						$obju_HISBillTrxs->setudfvalue("u_doctime",$objRs3->fields["u_starttime"]);
						$obju_HISBillTrxs->setudfvalue("u_docno",$objRs3->fields["docno"]);
						$obju_HISBillTrxs->setudfvalue("u_doctype","CM");
						$obju_HISBillTrxs->setudfvalue("u_docdesc","Returns/Credit");
						$obju_HISBillTrxs->setudfvalue("u_docstatus",$objRs3->fields["docstatus"]);
						$obju_HISBillTrxs->setudfvalue("u_amount",$objRs3->fields["u_amount"]*-1);
						
						$obju_HISBillTrxs->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillTrxs->add();
					}
					if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);					
					if ($actionReturn) {
						if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='JV' and docno='$objJVHdr->docno'",false);
						if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='JV' and docno='$objJVHdr->docno'",false);
						$objJVHdr->sbo_post_flag = 0;
						if ($actionReturn) $actionReturn = $objJVHdr->update($objJVHdr->docno,$objJVHdr->rcdversion);
						//if ($actionReturn) $actionReturn = sboBatchPostJournalVoucher($objJVHdr->objectcode,$objJVHdr->docno,null,true);
					}	
				} else $actionReturn = raiseError("Unable to find bill journal [".$objRs3->fields["u_billno"]."]");
			} else $actionReturn = raiseError("Unable to find bill [".$objRs3->fields["u_billno"]."]");
			if (!$actionReturn) break;
		}			
	}	
	
	if ($actionReturn) {
		//$objConnection->rollback();
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
