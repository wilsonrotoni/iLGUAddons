<?php
 include_once("./classes/masterdataschema_br.php"); 
 include_once("./classes/documentlinesschema_br.php"); 
include_once("./classes/masterdatalinesschema_br.php");
 include_once("./classes/collections.php"); 

include_once("./JimacDataTrxSync.php");
require_once("./classes/smsoutlog.php");

function onBeforeAddEventdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
                case "u_terminalseries":
                        $objRs = new recordset(null,$objConnection);
                        $objRs->queryopen("select * from u_terminalseries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_registerid = '".$objTable->getudfvalue("u_registerid")."' and u_issuedocno = '".$objTable->getudfvalue("u_issuedocno")."' ");
                        while ($objRs->queryfetchrow("NAME")) {
                                return raiseError("Series [".$objTable->getudfvalue("u_docseriesname")."] is already registered.");
                        }
                break;
            
		case "u_lgupos":
                        var_dump("BeforeAdd");
                        return false;
			if ($objTable->getudfvalue("u_docseries")!="-1" && $objTable->getudfvalue("u_docseries")!="") {
				$obju_POSTerminals = new masterdataschema_br(null,$objConnection,"u_lguposterminals");
				$obju_POSRegisters = new masterdataschema_br(null,$objConnection,"u_lguposregisters");
                                $obju_POSTerminalSeries = new masterdatalinesschema_br(null,$objConnection,"u_lguposterminalseries");
                                $obju_TerminalSeries = new documentschema_br(null,$objConnection,"u_terminalseries");
                                $objLGUReceiptItems = new documentlinesschema_br(null,$objConnection,"U_LGURECEIPTITEMS");
                                
                                if ($obju_POSRegisters->getbysql("U_USERID = '".$objTable->getudfvalue("u_userid")."' AND U_STATUS = 'O'")) {
                                    
                                        if ($obju_TerminalSeries->getbysql("U_REGISTERID = '".$obju_POSRegisters->code."' AND U_ISSUEDOCNO = '".$objTable->getudfvalue("u_issuedocno")."'")) {
                                                $numlen = $obju_TerminalSeries->getudfvalue("u_numlen");
                                                $prefix = $obju_TerminalSeries->getudfvalue("u_prefix");
                                                $suffix = $obju_TerminalSeries->getudfvalue("u_suffix");
                                                $nextno = $obju_TerminalSeries->getudfvalue("u_nextno");	

                                                $prefix = str_replace("{POS}", $objTable->getudfvalue("u_terminalid"), $prefix);
                                                $suffix = str_replace("{POS}", $objTable->getudfvalue("u_terminalid"), $suffix);
                                                $prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
                                                $suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
                                                $prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
                                                $suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
                                                $prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
                                                $suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);

                                                $objTable->docno = $prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT) . $suffix; 
                                                $objTable->setudfvalue("u_docnos",$objTable->docno);
                                                $obju_TerminalSeries->setudfvalue("u_nextno",floatval($obju_TerminalSeries->getudfvalue("u_nextno"))+1);	

                                                if ($obju_TerminalSeries->getudfvalue("u_nooflines")>0) {
                                                        $seriescnt = intval($objTable->getudfvalue("u_totalquantity") / $obju_TerminalSeries->getudfvalue("u_nooflines"));
                                                        if (($objTable->getudfvalue("u_totalquantity") % $obju_TerminalSeries->getudfvalue("u_nooflines"))!=0) $seriescnt++;

                                                        if ($seriescnt>1) {
                                                                for ($xxx=2;$xxx<=$seriescnt;$xxx++) {
                                                                        $numlen = $obju_TerminalSeries->getudfvalue("u_numlen");
                                                                        $prefix = $obju_TerminalSeries->getudfvalue("u_prefix");
                                                                        $suffix = $obju_TerminalSeries->getudfvalue("u_suffix");
                                                                        $nextno = $obju_TerminalSeries->getudfvalue("u_nextno");	

                                                                        $prefix = str_replace("{POS}", $objTable->getudfvalue("u_terminalid"), $prefix);
                                                                        $suffix = str_replace("{POS}", $objTable->getudfvalue("u_terminalid"), $suffix);
                                                                        $prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
                                                                        $suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
                                                                        $prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
                                                                        $suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
                                                                        $prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
                                                                        $suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);

                                                                        $objTable->setudfvalue("u_docnos",$objTable->getudfvalue("u_docnos") . "/". ($prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT) . $suffix));
                                                                        $objTable->setudfvalue("u_doccnt",intval($objTable->getudfvalue("u_doccnt"))+1);

                                                                        $obju_TerminalSeries->setudfvalue("u_nextno",floatval($obju_TerminalSeries->getudfvalue("u_nextno"))+1);
                                                                }
                                                        }
                                                }

                                                $actionReturn = $obju_TerminalSeries->update($obju_TerminalSeries->docno,$obju_TerminalSeries->rcdversion);
                                                if (!$actionReturn) break;
                                                var_dump("5");
                                        }
                                }
			}
                        
			if ($actionReturn && $objTable->getudfvalue("u_status")!="D") {
				$obju_LGUPOS = new documentschema_br(null,$objConnection,"u_lgupos");
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select * from u_lguposdps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
				while ($objRs->queryfetchrow("NAME")) {
					if ($obju_LGUPOS->getbykey($objRs->fields["U_REFNO"])) {
						$obju_LGUPOS->setudfvalue("u_creditedamount", $obju_LGUPOS->getudfvalue("u_creditedamount") + $objRs->fields["U_AMOUNT"]);
						if ($obju_LGUPOS->getudfvalue("u_paidamount")<$obju_LGUPOS->getudfvalue("u_creditedamount")) {
							return raiseError("Customer Deposit [".$objRs->fields["U_REFNO"]."] was over applied by [".($obju_LGUPOS->getudfvalue("u_creditedamount") - $obju_LGUPOS->getudfvalue("u_paidamount"))."].");
						}
						$actionReturn = $obju_LGUPOS->update($obju_LGUPOS->docno,$obju_LGUPOS->rcdversion);
						if (!$actionReturn) break;
					} else return raiseError("Invalid Customer Deposit No.[".$objRs->fields["U_REFNO"]."]");
				}
			}
			
			if ($actionReturn && $objTable->getudfvalue("u_status")!="D") {
                     
                                if ($objTable->getudfvalue("u_partialpay")=="0") {
                                    var_dump("2");
                                    //if ($actionReturn) $actionReturn = onCustomEventUpdateLGUBillsdocumentschema_brGPSLGU($objTable);
                                    //if ($actionReturn) $actionReturn = onCustomEventUpdateMultipleLGUBillsdocumentschema_brGPSLGU($objTable);
                                } elseif ($objTable->getudfvalue("u_partialpay")=="1") {
                                    if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostDPGPSLGU($objTable);
                                }
				if ($actionReturn && $objTable->getudfvalue("u_contactno")!="") {
                                    var_dump("3");
				$obju_LGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
				$obju_BPLApps = new documentschema_br(null,$objConnection,"u_bplapps");
//				$obju_LguSetup = new documentschema_br(null,$objConnection,"u_lgusetup");
                                $objRs_uLGUSetup = new recordset(null,$objConnection);
                                $objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE from U_LGUSETUP A");
                                    if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
                                            return raiseError("No setup found for municipality and province found.");
                                    }
                                    
				$objSMSOutLog = new smsoutlog(null,$objConnection);
				if ($obju_LGUBills->getbykey($objTable->getudfvalue("u_billno"))) {
                                    var_dump("4");
					if ($obju_BPLApps->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
						$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
						$objSMSOutLog->deviceid = "sun";
						$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_contactno");
						$objSMSOutLog->remark = "Payment";
						$objSMSOutLog->message = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\n Your payment of " . formatNumericAmount($objTable->getudfvalue("u_paidamount")) . " was received on " . date('M d, Y',strtotime($objTable->getudfvalue("u_date"))) . " with OR#" . $objTable->docno . ".\r\nREF#" . $obju_BPLApps->docno . ", ".$obju_BPLApps->getudfvalue("u_businessname")."\n\r This is an automated message \n\r Please dont reply";
						
                                                $actionReturn = $objSMSOutLog->add();
					}
				}		
				//var_dump($objSMSOutLog->message);
                                }
			}
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSLGU()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostDPGPSLGU($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	/*
	$objCollections = new collections(null,$objConnection) ;
	$objCollectionsCheques = new collectionscheques(null,$objConnection) ;
	$objCollectionsCreditCards = new collectionscreditcards(null,$objConnection) ;
	$objCollectionsCashCards = new collectionscashcards(null,$objConnection) ;
	$objCollectionsInvoices = new collectionsinvoices(null,$objConnection) ;
	$objRs = new recordset(null,$objConnection) ;

	$custno = $objTable->getudfvalue("u_custno");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$settings = getBusinessObjectSettings("INCOMINGPAYMENT");
	if ($delete) {
		if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				if ($obju_HISIPMedSups->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_DOCTYPE='DP' AND U_DOCNO='".$objTable->docno."'")) {
					$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
					$obju_HISIPMedSups->setudfvalue("u_balance",0);
					$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
					$actionReturn = $obju_HISIPMedSups->update($obju_HISIPMedSups->docid,$obju_HISIPMedSups->lineid,$obju_HISIPMedSups->rcdversion);
				} else $actionReturn = raiseError("Unable to find Transaction No.[".$objTable->docno."] in Reference No.[".$objTable->getudfvalue("u_refno")."]");	
			} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
		}	
			
	
		if ($actionReturn) {
			if ($objCollections->getbykey($_SESSION["branch"], $objTable->docno)) {
				if ($objCollections->collfor=="DP") {
					if ($actionReturn) {
						$bpdata = array();
						$bpdata["bpcode"] = $objCollections->bpcode;
						$bpdata["parentbpcode"] = "";
						$bpdata["parentbptype"] = "P";
						$bpdata["balance"] = $objCollections->paidamount * -1;
						$actionReturn = onCustomEventcollectionsUpdateCustomerBalances($bpdata);
					}	
				}
				if ($actionReturn) {
					$objCollections->cleared = -99;
					$objCollections->cancelleddate = $objCollections->docdate;
					$objCollections->cancelledby = $_SESSION["userid"];
					$objCollections->docstat = 'CN';
					if ($actionReturn) $actionReturn = $objCollections->update($objCollections->branchcode,$objCollections->docno,$objCollections->rcdversion);
				}		
				//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostDPGPSHIS");
				return $actionReturn;
			} else return raiseError("Unable to Find Collection Record.");
		}	
	} else {
		if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				$obju_HISIPMedSups->prepareadd();
				$obju_HISIPMedSups->docid = $obju_HISIPs->docid;
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
				} else {
					$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisoptrxs",$objConnection);
				}	
				$obju_HISIPMedSups->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
				$obju_HISIPMedSups->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
				//$obju_HISIPMedSups->setudfvalue("u_doctime",$objTable->getudfvalue("u_starttime"));
				$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
				if ($objTable->getudfvalue("u_payrefno")!="") {
					$obju_HISIPMedSups->setudfvalue("u_reftype","CHRG");
					$obju_HISIPMedSups->setudfvalue("u_refno",$objTable->getudfvalue("u_payrefno"));
				}	
				$obju_HISIPMedSups->setudfvalue("u_docdesc","Partial Payment");
				$obju_HISIPMedSups->setudfvalue("u_docno",$objTable->docno);
				$obju_HISIPMedSups->setudfvalue("u_doctype","DP");
				
				$obju_HISIPMedSups->setudfvalue("u_amount",$objTable->getudfvalue("u_dueamount")*-1);
				$obju_HISIPMedSups->setudfvalue("u_balance",$objTable->getudfvalue("u_dueamount")*-1);
				$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
				$actionReturn = $obju_HISIPMedSups->add();
			} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
		}	
	}	
	$objCollections->docdate = $objTable->getudfvalue("u_docdate");
	$objCollections->bpcode = $custno;
	$objCollections->bpname = $customerdata["CUSTNAME"];
	$objCollections->paidamount = $objTable->getudfvalue("u_dueamount");
	$objCollections->docno = $objTable->docno;
	$objCollections->othertrxtype = $objTable->getudfvalue("u_colltype");
	$objTable->setudfvalue("u_ardocno",$objCollections->docno);
	$objCollections->setudfvalue("u_cashierid",$_SESSION["userid"]);
	$objCollections->setudfvalue("u_doctime",$objTable->getudfvalue("u_doctime"));
	$objCollections->setudfvalue("u_colltype",$objTable->getudfvalue("u_colltype"));
	if ($actionReturn) $actionReturn = isPostingDateValid($objCollections->docdate,$objCollections->docduedate,$objCollections->taxdate);
	if ($actionReturn) {
		$objCollections->docid = getNextIdByBranch('collections',$objConnection);
		$objCollections->sbo_post_flag = $settings["autopost"];
		$objCollections->jeposting = $settings["jeposting"];
		$objCollections->objectcode = "INCOMINGPAYMENT";
		$objCollections->changeamount = 0;
		$objCollections->doctype = "C";
		if ($objTable->getudfvalue("u_payreftype")!="SI") {
			$objCollections->collfor = "DP";
			$objCollections->balanceamount = $objCollections->paidamount;
			$objCollections->dueamount = 0;
		} else {
			$objCollections->collfor = "SI";
			$objCollections->balanceamount = 0;
			$objCollections->dueamount = $objCollections->paidamount;
		}	
		$objCollections->bpcurrency = $customerdata["CURRENCY"];
		$objCollections->currency = $customerdata["CURRENCY"];
		$objCollections->docduedate = $objCollections->docdate;
		$objCollections->taxdate = $objCollections->docdate;
		$objCollections->branchcode = $_SESSION["branch"];
		$objCollections->colltype = "CSH";
		if ($objTable->getudfvalue("u_recvamount")>0) {
			$objCollections->cashacct = -1;
			$objCollections->collectedamount = $objCollections->paidamount;
			$objCollections->cashamount = $objTable->getudfvalue("u_recvamount") - $objTable->getudfvalue("u_chngamount");
		}
		if ($objTable->getudfvalue("u_checkamount")>0) {
			$objCollections->chequeacct = -2;
			$objCollections->chequeamount = $objTable->getudfvalue("u_checkamount");
		}
		if ($objTable->getudfvalue("u_creditcardamount")>0) {
			$objCollections->creditcardamount = $objTable->getudfvalue("u_creditcardamount");
		}
		if ($objTable->getudfvalue("u_creditamount")>0) {
			$objCollections->otheramount = $objTable->getudfvalue("u_creditamount");
		}
		//$objCollections->acctbranch = $_SESSION["branch"];
		$objCollections->valuedate = $objCollections->docdate;
		$objCollections->cleared = 1;
		$objCollections->postdate = $objCollections->docdate ." ". date('H:i:s');
		$objCollections->journalremark = "Incoming Payment - " . $objCollections->bpcode;
		
		if ($actionReturn && $objCollections->collfor == "SI") {
			$objCollectionsInvoices->prepareadd();
			$objCollectionsInvoices->docid = getNextIdByBranch('collectionsinvoices',$objConnection);
			$objCollectionsInvoices->docno = $objCollections->docno;
			$objCollectionsInvoices->reftype = "ARINVOICE";
			$objCollectionsInvoices->refbranch = $objCollections->branchcode;
			$objCollectionsInvoices->refno = $objTable->getudfvalue("u_payrefno");
			$objCollectionsInvoices->bpcode = $objCollections->bpcode;
			$objCollectionsInvoices->bpname = $objCollections->bpname;
			$objCollectionsInvoices->currency = $objCollections->currency;
			$objCollectionsInvoices->objectcode = $objCollections->objectcode;
			$objCollectionsInvoices->privatedata["header"] = $objCollections;
			$objCollectionsInvoices->amount = $objCollections->paidamount;
			$objCollectionsInvoices->balanceamount = $objCollectionsInvoices->amount;
			if ($actionReturn) $actionReturn = $objCollectionsInvoices->add();
		}
								
		if ($actionReturn && $objTable->getudfvalue("u_checkamount")>0) {
			$objCollectionsCheques->prepareadd();
			$objCollectionsCheques->docid = getNextIdByBranch('collectionscheques',$objConnection);
			$objCollectionsCheques->docno = $objCollections->docno;
			$objCollectionsCheques->objectcode = $objCollections->objectcode;
			$objCollectionsCheques->checkdate = $objCollections->docdate;
			$objCollectionsCheques->checkno = $objTable->getudfvalue("u_checkno");
			$objCollectionsCheques->bank = $objTable->getudfvalue("u_bank");
			$objCollectionsCheques->bankbranch = "";
			$objCollectionsCheques->amount = $objTable->getudfvalue("u_checkamount");
			$objCollectionsCheques->privatedata["header"] = $objCollections;
			if ($actionReturn) $actionReturn = $objCollectionsCheques->add();
		}

		if ($actionReturn && $objTable->getudfvalue("u_creditcardamount")>0) {
			$objRs->queryopen("select * from u_hisposcreditcards where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'"); 
			while ($objRs->queryfetchrow("NAME")) {
				$objCollectionsCreditCards->docid = getNextIdByBranch('collectionscreditcards',$objConnection);
				$objCollectionsCreditCards->docno = $objCollections->docno;
				$objCollectionsCreditCards->objectcode = $objCollections->objectcode;
				$objCollectionsCreditCards->creditcard = $objRs->fields["U_CREDITCARD"];
				$objCollectionsCreditCards->creditcardno = $objRs->fields["U_CREDITCARDNO"];
				$objCollectionsCreditCards->creditcardname = $objRs->fields["U_CREDITCARDNAME"];
				$objCollectionsCreditCards->cardexpiretext = $objRs->fields["U_EXPIREDATE"];
				$objCollectionsCreditCards->cardexpiredate = getmonthendDB("20" . substr($objCollectionsCreditCards->cardexpiretext, 3, 2) . "-" . substr($objCollectionsCreditCards->cardexpiretext, 0, 2) . "-01");
				$objCollectionsCreditCards->approvalno = $objRs->fields["U_APPROVALNO"];
				$objCollectionsCreditCards->amount = $objRs->fields["U_AMOUNT"];
				$objCollectionsCreditCards->privatedata["header"] = $objCollections;
				$actionReturn = $objCollectionsCreditCards->add();				
				if (!$actionReturn) break;
			}
		}
		
		if ($actionReturn && $objTable->getudfvalue("u_creditamount")>0) {
			$objRs->queryopen("select u_posarcashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Sales - A/R");
			
			$objCollectionsCashCards->docid = getNextIdByBranch("collectionscashcards",$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objRs->fields["u_posarcashcard"];
			$objCollectionsCashCards->refno = "-"; 
			$objCollectionsCashCards->amount = $objTable->getudfvalue("u_creditamount");
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			$actionReturn = $objCollectionsCashCards->add();
		}
		if ($actionReturn) $actionReturn = $objCollections->add();
	}		
	*/
	return $actionReturn;
}



function onAddEventdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
                case "u_lguupdpays":
                            $obju_LGUPOS = new documentschema_br(null,$objConnection,"u_lgupos");
                            $objRs = new recordset(null,$objConnection);
                            $objRs->queryopen("select u_ornumber,u_remittancedate from u_lguupdpaysdetails where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
                            while ($objRs->queryfetchrow("NAME")) {
                                    if ($obju_LGUPOS->getbykey($objRs->fields["u_ornumber"])) {
                                            $obju_LGUPOS->setudfvalue("u_remittancedate",$objRs->fields["u_remittancedate"]);
                                            $actionReturn = $obju_LGUPOS->update($obju_LGUPOS->docno,$obju_LGUPOS->rcdversion);
                                            if (!$actionReturn) break;
                                    } //else return raiseError("Invalid Receipt No.[".$objRs->fields["u_ornumber"]."]");
                            }
                            break;
		case "u_lgupos":
                        var_dump("onAdd");
                        return false;
                        if ($objTable->getudfvalue("u_docseries")!="-1") {
                            $objLGUReceiptItems = new documentlinesschema_br(null,$objConnection,"U_LGURECEIPTITEMS");
                            $issuedto = '';
                                if($objTable->getudfvalue("u_module") == "CTC Barangay") $issuedto = $objTable->getudfvalue("u_custname");
                                else $issuedto = $objTable->createdby;
                                if ($objLGUReceiptItems->getbysql("U_REFNO = '".$objTable->getudfvalue("u_docseries")."' and u_issuedto = '".$issuedto."'")) {
                                        $objLGUReceiptItems->setudfvalue("u_available", floatval($objLGUReceiptItems->getudfvalue("u_available") - $objTable->getudfvalue("u_doccnt")));
                                        if($objLGUReceiptItems->getudfvalue("u_available") < 0 ){
                                            return raiseError("Series [".$objLGUReceiptItems->getudfvalue("U_REFNO")."] available OR exceeded. ");
                                        }
                                        $actionReturn = $objLGUReceiptItems->update($objLGUReceiptItems->docid,$objLGUReceiptItems->lineid,$objLGUReceiptItems->rcdversion);
                                        if (!$actionReturn) break;
                                }
                        }
                        
			if ($objTable->getudfvalue("u_autopost")==1 && $objTable->getudfvalue("u_status")!="D") {
				$actionReturn = onCustomeventPostPOSdocumentschema_brGPSLGU($objTable);
                               
			}
                        
			if ($objTable->getudfvalue("u_autoqueue")==1) {
				$obju_LGUQue = new documentschema_br(null,$objConnection,"u_lguque");
				$obju_LGUPOSTerminals = new masterdataschema_br(null,$objConnection,"u_lguposterminals");
				$obju_LGUQueGroups = new masterdataschema_br(null,$objConnection,"u_lguquegroups");
				$objSMSOutLog = new smsoutlog(null,$objConnection);
				$objRs = new recordset(null,$objConnection);
				if ($obju_LGUPOSTerminals->getbykey($objTable->getudfvalue("u_terminalid"))) {
					if ($obju_LGUQueGroups->getbykey($obju_LGUPOSTerminals->getudfvalue("u_quegroup"))) {
						$obju_LGUQue->prepareadd();
						$obju_LGUQue->docno = getNextNoByBranch("u_lguque","",$objConnection);
						$obju_LGUQue->docid = getNextIdByBranch("u_lguque",$objConnection);
						$obju_LGUQue->setudfvalue("u_date",currentdateDB());
						$obju_LGUQue->setudfvalue("u_ctr",$objTable->getudfvalue("u_terminalid"));
						$obju_LGUQue->setudfvalue("u_group",$obju_LGUPOSTerminals->getudfvalue("u_quegroup"));
                                                $obju_LGUQue->setudfvalue("u_monitor",$obju_LGUQueGroups->getudfvalue("u_monitor"));
                                                $obju_LGUQue->setudfvalue("u_groupcount",$obju_LGUQueGroups->getudfvalue("u_groupcount"));
						$obju_LGUQue->setudfvalue("u_ref",getNextIdByBranch("u_lguque".$obju_LGUPOSTerminals->getudfvalue("u_quegroup").date('Ymd'),$objConnection));
						if ($actionReturn) $actionReturn = $obju_LGUQue->add();
						if ($actionReturn) {
                                                        if ($obju_LGUQue->getudfvalue("u_ref")>=$obju_LGUQueGroups->getudfvalue("u_maxno")) {
                                                                $actionReturn = $obju_LGUQueGroups->executesql("delete from docids where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='u_lguque".$obju_LGUPOSTerminals->getudfvalue("u_quegroup").date('Ymd')."'",false);
                                                        }
                                                }
						if ($actionReturn) {
							$objRs->queryopen("select a.U_MOBILENO, a.U_TAGNO, b.NAME as U_GROUPNAME from u_lguquetags a inner join u_lguquegroups b on b.code=a.u_group where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_group='".$obju_LGUPOSTerminals->getudfvalue("u_quegroup")."' and a.u_date='".currentdateDB()."' and a.u_tagno>".$obju_LGUQue->getudfvalue("u_ref")." and (mod(a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref").",b.u_notifyevery)=0 or a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref")."=b.u_notifyon or a.u_tagno-1=b.u_notifyon-2)");
							while ($objRs->queryfetchrow("NAME")) {
								$objSMSOutLog->prepareadd();
								$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
								$objSMSOutLog->deviceid = "sun";
								$objSMSOutLog->mobilenumber = $objRs->fields["U_MOBILENO"];
								$objSMSOutLog->remark = "";
								$objSMSOutLog->message = "Now serving ".$objRs->fields["U_GROUPNAME"]." Queing No. " . $obju_LGUQue->getudfvalue("u_ref") . ". You are at No. " . $objRs->fields["U_TAGNO"] . ".";
								$actionReturn = $objSMSOutLog->add();
								if (!$actionReturn) break;
							}
						}
					}	
				}	
			}
                        if ($objTable->getudfvalue("u_status")!="D") {
				if ($actionReturn) $actionReturn = onCustomEventupdatefaasdocumentschema_brGPSLGU($objTable);
				if ($actionReturn) $actionReturn = onCustomEventPostAcctgSharingdocumentschema_brGPSLGU($objTable);
			}
                        if ($actionReturn && $objTable->getudfvalue("u_status")!="D" &&  $objTable->getudfvalue("u_module") == "Real Property Tax Bill") {
                                if ($actionReturn) $actionReturn = onCustomEventPostRPTLedgerdocumentschema_brGPSLGU($objTable);
                        }
                        if ($actionReturn && $objTable->getudfvalue("u_status")!="D" &&  $objTable->getudfvalue("u_module") == "Business Permit") {
                            var_dump("1");
                               // if ($actionReturn) $actionReturn = onCustomEventPostBusinessPermitLedgerdocumentschema_brGPSLGU($objTable);
                        }
                        break;
	}
        
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	global $page;
	switch ($objTable->dbtable) {
		
		case "u_lgupos":
			if ($actionReturn) {
                                
				if ($objTable->fields["U_STATUS"]!=$objTable->getudfvalue("u_status") && $objTable->getudfvalue("u_status")=="O") {
					$obju_LGUPOS = new documentschema_br(null,$objConnection,"u_lgupos");
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select * from u_lguposdps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
					while ($objRs->queryfetchrow("NAME")) {
						if ($obju_LGUPOS->getbykey($objRs->fields["U_REFNO"])) {
							$obju_LGUPOS->setudfvalue("u_creditedamount", $obju_LGUPOS->getudfvalue("u_creditedamount") + $objRs->fields["U_AMOUNT"]);
							if ($obju_LGUPOS->getudfvalue("u_paidamount")<$obju_LGUPOS->getudfvalue("u_creditedamount")) {
								return raiseError("Customer Deposit [".$objRs->fields["U_REFNO"]."] was over applied by [".($obju_LGUPOS->getudfvalue("u_creditedamount") - $obju_LGUPOS->getudfvalue("u_paidamount"))."].");
							}
							$actionReturn = $obju_LGUPOS->update($obju_LGUPOS->docno,$obju_LGUPOS->rcdversion);
							if (!$actionReturn) break;
						} else return raiseError("Invalid Customer Deposit No.[".$objRs->fields["U_REFNO"]."]");
					}
					
                                            if ($objTable->getudfvalue("u_partialpay")=="0") {
                                                if ($actionReturn) $actionReturn = onCustomEventUpdateLGUBillsdocumentschema_brGPSLGU($objTable);
                                                if ($actionReturn) $actionReturn = onCustomEventUpdateMultipleLGUBillsdocumentschema_brGPSLGU($objTable);
                                            } elseif ($objTable->getudfvalue("u_partialpay")=="1") {
                                                if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostDPGPSLGU($objTable);
                                            }
			
				} elseif ($objTable->docstatus=="CN" && $objTable->getudfvalue("u_status")=="O") {
                                    $obju_PaymentHistory = new documentschema_br(null,$objConnection,"u_paymenthistory");
                                    $obju_BplLedger = new documentschema_br(null,$objConnection,"u_bplledger");
                                        if (isset($page)) {
                                                if ($page->getcolumnstring("T51",0,"userid")!="") {
                                                        $objRs = new recordset(null,$objConnection);
                                                        $objRs1 = new recordset(null,$objConnection);
                                                        $objRs2 = new recordset(null,$objConnection);
                                                        $objRs->queryopen("select username, u_cancelpayflag from users where userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
                                                        if (!$objRs->queryfetchrow()) {
                                                                $page->setcolumn("T51",0,"userid","");
                                                                $page->setcolumn("T51",0,"password","");
                                                                return raiseError("Invalid user or password.");
                                                        }	
                                                        if ($objRs->fields[1]==0) {
                                                                $page->setcolumn("T51",0,"userid","");
                                                                $page->setcolumn("T51",0,"password","");
                                                                return raiseError("You are not allowed to cancel this document.");
                                                        }
                                                        $objTable->setudfvalue("u_status","CN");
                                                        $objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
                                                        $objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
                                                        $objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
                                                        $objRs1->queryopen("select docno from u_paymenthistory where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_orno='$objTable->docno'");
                                                        while ($objRs1->queryfetchrow("NAME")) {
                                                            if ($obju_PaymentHistory->getbykey($objRs1->fields["docno"])) {
                                                                $obju_PaymentHistory->setudfvalue("u_iscancelled", 1);
                                                                $obju_PaymentHistory->setudfvalue("u_cancelleddate",currentdateDB());
                                                                $obju_PaymentHistory->setudfvalue("u_cancelledby", $page->getcolumnstring("T51",0,"userid"));
                                                                $actionReturn = $obju_PaymentHistory->update($obju_PaymentHistory->docno,$obju_PaymentHistory->rcdversion);
                                                                if (!$actionReturn) break;
                                                            }
                                                        }
                                                        $objRs2->queryopen("select docno from u_bplledger where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_orno='$objTable->docno'");
                                                        while ($objRs2->queryfetchrow("NAME")) {
                                                            if ($obju_BplLedger->getbykey($objRs2->fields["docno"])) {
                                                                $obju_BplLedger->setudfvalue("u_iscancelled", 1);
                                                                $obju_BplLedger->setudfvalue("u_cancelleddate",currentdateDB());
                                                                $obju_BplLedger->setudfvalue("u_cancelledby", $page->getcolumnstring("T51",0,"userid"));
                                                                $actionReturn = $obju_BplLedger->update($obju_BplLedger->docno,$obju_BplLedger->rcdversion);
                                                                if (!$actionReturn) break;
                                                            }
                                                        }
                                                        $page->setcolumn("T51",0,"userid","");
                                                        $page->setcolumn("T51",0,"password","");
                                                        $page->setcolumn("T51",0,"cancelreason","");
                                                        $page->setcolumn("T51",0,"remarks","");
                                                }	
                                        }
					$obju_LGUPOS = new documentschema_br(null,$objConnection,"u_lgupos");
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select * from u_lguposdps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
					while ($objRs->queryfetchrow("NAME")) {
						if ($obju_LGUPOS->getbykey($objRs->fields["U_REFNO"])) {
							$obju_LGUPOS->setudfvalue("u_creditedamount", $obju_LGUPOS->getudfvalue("u_creditedamount") - $objRs->fields["U_AMOUNT"]);
							$actionReturn = $obju_LGUPOS->update($obju_LGUPOS->docno,$obju_LGUPOS->rcdversion);
							if (!$actionReturn) break;
						} else return raiseError("Invalid Customer Deposit No.[".$objRs->fields["U_REFNO"]."]");
					}
                                        //UPDATE LGUPOSACCTGSHARING TABLE
                                        $obju_LGUPosAcct = new documentschema_br(null,$objConnection,"u_lguposacctgsharing");
                                        if($actionReturn){
                                            $objRs->queryopen("select * from u_lguposacctgsharing where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_orno='$objTable->docno' ");
                                            while ($objRs->queryfetchrow("NAME")) {
                                                if ($obju_LGUPosAcct->getbykey($objRs->fields["DOCNO"])) {
                                                    $obju_LGUPosAcct->setudfvalue("u_status",$objTable->getudfvalue("u_status"));
                                                    $actionReturn = $obju_LGUPosAcct->update($obju_LGUPosAcct->docno,$obju_LGUPosAcct->rcdversion);
                                                    if (!$actionReturn) break;
                                                }
                                            }	
                                        }
					
                                    if ($objTable->getudfvalue("u_partialpay")=="0") {
                                        if ($actionReturn) $actionReturn = onCustomEventUpdateLGUBillsdocumentschema_brGPSLGU($objTable,true);
                                        if ($actionReturn) $actionReturn = onCustomEventUpdateMultipleLGUBillsdocumentschema_brGPSLGU($objTable,true);
					
                                    }
				}	
			}
			
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSLGU()");
	return $actionReturn;
}



function onUpdateEventdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
                case "u_lguupdpays":
                                        $obju_LGUPOS = new documentschema_br(null,$objConnection,"u_lgupos");
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select u_ornumber,u_remittancedate from u_lguupdpaysdetails where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
					while ($objRs->queryfetchrow("NAME")) {
						if ($obju_LGUPOS->getbykey($objRs->fields["u_ornumber"])) {
							$obju_LGUPOS->setudfvalue("u_remittancedate",$objRs->fields["u_remittancedate"]);
							$actionReturn = $obju_LGUPOS->update($obju_LGUPOS->docno,$obju_LGUPOS->rcdversion);
							if (!$actionReturn) break;
						} //else return raiseError("Invalid Receipt No.[".$objRs->fields["u_ornumber"]."]");
					}
                            break;
                        
		case "u_lgupos":
			if ($objTable->getudfvalue("u_autopost")==1 && $objTable->fields["U_STATUS"]=="D" && $objTable->getudfvalue("u_status")!="D") {
				$actionReturn = onCustomeventPostPOSdocumentschema_brGPSLGU($objTable);
                                
			
			} elseif ($objTable->getudfvalue("u_autopost")==1 && $objTable->fields["U_STATUS"]!="CN" && $objTable->getudfvalue("u_status")=="CN") {
				$actionReturn = onCustomeventPostPOSdocumentschema_brGPSLGU($objTable,true);
				//if ($actionReturn) $actionReturn = raiseError("onUpdateEventdocumentschema_brGPSLGU()");
				if (!$actionReturn) $page->setitem("u_status","O");
			}
                        if ($actionReturn) $actionReturn = onCustomEventupdatefaasdocumentschema_brGPSLGU($objTable);
			break;
	}
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/


function onCustomeventPostPOSdocumentschema_brGPSLGU($objTable,$cancelled=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	
	return true;

	$cashpaycode = "";
	$checkpaycode = "";
	$tfpaycode = "";
	$objRs->queryopen("select POSPAYCODE from CASHCARDS where POSPAYTYPE=0 and POSPAYCODE!=''");
	if ($objRs->queryfetchrow("NAME")) $cashpaycode = $objRs->fields["POSPAYCODE"];

	$objRs->queryopen("select POSPAYCODE from CASHCARDS where POSPAYTYPE=2 and POSPAYCODE!=''");
	if ($objRs->queryfetchrow("NAME")) $checkpaycode = $objRs->fields["POSPAYCODE"];

	$objRs->queryopen("select POSPAYCODE from CASHCARDS where POSPAYTYPE=4 and POSPAYCODE!=''");
	if ($objRs->queryfetchrow("NAME")) $arpaycode = $objRs->fields["POSPAYCODE"];

	$objRs->queryopen("select POSPAYCODE from CASHCARDS where POSPAYTYPE=11 and POSPAYCODE!=''");
	if ($objRs->queryfetchrow("NAME")) $tfpaycode = $objRs->fields["POSPAYCODE"];

	$objRs->queryopen("select POSPAYCODE from CASHCARDS where POSPAYTYPE=12 and POSPAYCODE!=''");
	if ($objRs->queryfetchrow("NAME")) $dppaycode = $objRs->fields["POSPAYCODE"];
	$returned = false;
	if ($cancelled) {
		if ($objTable->getudfvalue("u_trxtype")!="S") {
			$objTable->setudfvalue("u_trxtype","S");
			$returned = true;
		} else $objTable->setudfvalue("u_trxtype","R");
		//$objTable->setudfvalue("u_cashamount",$objTable->getudfvalue("u_cashamount")*-1);
		//$objTable->setudfvalue("u_dpamount",$objTable->getudfvalue("u_dpamount")*-1);
	}
		
	$data = array();
	$data2 = array();
	$lineid = 0;
	$lineid2 = 0;
	$posnegated = false;
	$replacementamount = 0;
	$objRs->queryopen("select A.DOCNO, A.U_DATE, A.U_TERMINALID, A.U_CUSTNO, A.U_TOTALAMOUNT, A.U_CASHAMOUNT, A.U_TFBANK, A.U_TFBANKACCTNO, A.U_TFREFNO, A.U_TFAMOUNT, B.U_ITEMCODE, B.U_ITEMDESC, B.U_UOM, B.U_QUANTITY, B.U_UNITPRICE, B.U_PRICE, B.U_DISCAMOUNT, B.U_LINETOTAL, B.U_NUMPERUOM, B.U_ITEMMANAGEBY, B.U_SERIALNO, B.U_FREEBIE from U_POS A, U_POSITEMS B WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND B.U_SELECTED=1 AND B.U_TOFOLLOW=0 AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->docno."'");
	$data["header"]["RECORDACTION"] = "A";
	$data["header"]["RECORDTYPE"] = $objTable->getudfvalue("u_trxtype");
	$data["header"]["BRANCH"] = $_SESSION["branch"];
	$data["header"]["TERMINAL"] = $objTable->getudfvalue("u_terminalid");
	$data["header"]["TRANSDATE"] = $objTable->getudfvalue("u_date");
	$data["header"]["TRANSDUEDATE"] = $objTable->getudfvalue("u_date");
	$data["header"]["BUSINESSDATE"] = $objTable->getudfvalue("u_date");
	$data["header"]["TRANSNO"] = $objTable->docno . iif($cancelled,"-CN","");
	$data["header"]["CUSTNO"] = $objTable->getudfvalue("u_custno");
	$data["header"]["REFINVOICENO"] = $objTable->docno;
	$data["header"]["POINTSEARNED"] = 0;
	$data["header"]["POINTSREDEEMED"] = 0;
	$data["header"]["VATINCLUSIVE"] = 1;
	$data["header"]["ISBARCODE"] = 0;
	$data["header"]["AUTOCASHPAYMENT"] = 0;
	$data["header"]["REPLACEMENT"] = 0;
	$data["header"]["REMARKS"] = "";
	$data["header"]["OVERAGE"] = "";
	$data["header"]["TRANSNOISUNIQUE"] = 1;
	$data["header"]["CANCELLED"] = $cancelled;
	
	while ($objRs->queryfetchrow("NAME")) {
		/*if ($cancelled) {
			$objRs->fields["U_QUANTITY"] = $objRs->fields["U_QUANTITY"]*-1;
			$objRs->fields["U_LINETOTAL"] = $objRs->fields["U_LINETOTAL"]*-1;
		}*/
		//
		if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
			$objRs->fields["U_QUANTITY"] = $objRs->fields["U_QUANTITY"]*-1;
			$objRs->fields["U_LINETOTAL"] = $objRs->fields["U_LINETOTAL"]*-1;
		} elseif ($cancelled && $returned) {
			$objRs->fields["U_QUANTITY"] = $objRs->fields["U_QUANTITY"]*-1;
			$objRs->fields["U_LINETOTAL"] = $objRs->fields["U_LINETOTAL"]*-1;
		}
		
		//if ($objTable->getudfvalue("u_trxtype")=="S") {
			if ($objRs->fields["U_QUANTITY"]>0) {
				//$data["header"]["PAYCODE"]["OVERAGE"] = "0004";
				//$data["header"]["PAYCODE"]["SHORTAGE"] = "0006";
				//$data["header"]["OBJECTSOURCE"] = "JIMAC";
				
				$data["lines"][$lineid]["ITEMCODE"] = $objRs->fields["U_ITEMCODE"];
				$data["lines"][$lineid]["BARCODE"] = "";
				$data["lines"][$lineid]["ITEMDESC"] = $objRs->fields["U_ITEMDESC"];
				$data["lines"][$lineid]["UOM"] = $objRs->fields["U_UOM"];
				$data["lines"][$lineid]["QUANTITY"] = floatval($objRs->fields["U_QUANTITY"]);
				$data["lines"][$lineid]["PRICE"] = floatval($objRs->fields["U_UNITPRICE"]);
				if ($objRs->fields["U_FREEBIE"]=="1") $data["lines"][$lineid]["PRICE"] = 0;
				$data["lines"][$lineid]["WAREHOUSE"] = "STORE";//$_SESSION["branch"];
				//$data["lines"][$lineid]["DISCOUNT"] = floatval($objRs->fields["U_DISCAMOUNT"])*-1;
				$data["lines"][$lineid]["DISCOUNT"] = 0;
				//if ($objRs->fields["U_FREEBIE"]=="1") $data["lines"][$lineid]["DISCOUNT"] = 0;
				$data["lines"][$lineid]["TOTALDISCOUNT"] = floatval($objRs->fields["U_LINETOTAL"]) - ($data["lines"][$lineid]["QUANTITY"] * $data["lines"][$lineid]["PRICE"]);
				if ($objRs->fields["U_FREEBIE"]=="1") $data["lines"][$lineid]["TOTALDISCOUNT"] = 0;
				//var_dump(array($data["lines"][$lineid]["PRICE"],$data["lines"][$lineid]["TOTALDISCOUNT"]));
				$data["lines"][$lineid]["SERVICECHARGE"] = 0;
				$data["lines"][$lineid]["VATAMOUNT"] = 0;
				$data["lines"][$lineid]["DISCVATAMOUNT"] = 0;
				$data["lines"][$lineid]["SCVATAMOUNT"] = 0;
				$data["lines"][$lineid]["QTYPERUOM"] = $objRs->fields["U_NUMPERUOM"];
				$data["lines"][$lineid]["STDCOSTPRICE"] = 0;
				if ($objRs->fields["U_ITEMMANAGEBY"]=="2") {
					$data["lines"][$lineid]["SERIALNUMBERS"][0]["SERIALNO"] = $objRs->fields["U_SERIALNO"];
					$data["lines"][$lineid]["SERIALNUMBERS"][0]["MFRSERIALNO"] = "";
				}
				
				//if ($cancelled) $replacementamount += floatval($objRs->fields["U_QUANTITY"]) * floatval($objRs->fields["U_PRICE"]);
				
				$lineid++;
				/*
				if (!isset($data["payments"])) {
					$cashamount = floatval($objRs->fields["U_CASHAMOUNT"]);
					if ($cashamount > 0) {
						$data["payments"][$lineid2]["POSPAYTYPE"] = "0";
						$data["payments"][$lineid2]["PAYCODE"] = "0001";
						$data["payments"][$lineid2]["AMOUNT"] = $cashamount;
						$lineid2++;
					}	
					
				}		*/		
			} else {
				$posnegated = true;

				$data2["header"]["RECORDACTION"] = "A";
				$data2["header"]["RECORDTYPE"] = iif($objTable->getudfvalue("u_trxtype")=="S","R","S");
				$data2["header"]["BRANCH"] = $_SESSION["branch"];
				$data2["header"]["TERMINAL"] = $objTable->getudfvalue("u_terminalid");
				$data2["header"]["TRANSDATE"] = 	 $objTable->getudfvalue("u_date");
				$data2["header"]["TRANSDUEDATE"] = $objTable->getudfvalue("u_date");
				$data2["header"]["BUSINESSDATE"] = $objTable->getudfvalue("u_date");
				$data2["header"]["TRANSNO"] = $objTable->docno . "/R" . iif($cancelled,"-CN","");;
				$data2["header"]["CUSTNO"] = $objTable->getudfvalue("u_custno");
				$data2["header"]["REFINVOICENO"] = $objTable->docno;
				$data2["header"]["POINTSEARNED"] = 0;
				$data2["header"]["POINTSREDEEMED"] = 0;
				$data2["header"]["VATINCLUSIVE"] = 1;
				$data2["header"]["ISBARCODE"] = 0;
				$data2["header"]["AUTOCASHPAYMENT"] = 0;
				$data2["header"]["REPLACEMENT"] = 0;
				//$data["header"]["TOTALAMOUNT"] = floatval($objRs->fields["U_TOTALAMOUNT"]);
				$data2["header"]["REMARKS"] = "";
				$data2["header"]["OVERAGE"] = "";
				$data2["header"]["TRANSNOISUNIQUE"] = 1;
								
				$data2["lines"][$lineid2]["ITEMCODE"] = $objRs->fields["U_ITEMCODE"];
				$data2["lines"][$lineid2]["BARCODE"] = "";
				$data2["lines"][$lineid2]["ITEMDESC"] = $objRs->fields["U_ITEMDESC"];
				$data2["lines"][$lineid2]["UOM"] = $objRs->fields["U_UOM"];
				$data2["lines"][$lineid2]["QUANTITY"] = floatval($objRs->fields["U_QUANTITY"])*-1;
				$data2["lines"][$lineid2]["PRICE"] = floatval($objRs->fields["U_UNITPRICE"]);
				if ($objRs->fields["U_FREEBIE"]=="1") $data2["lines"][$lineid2]["PRICE"] = 0;
				$data2["lines"][$lineid2]["WAREHOUSE"] = "STORE";//$_SESSION["branch"];
				$data2["lines"][$lineid2]["DISCOUNT"] = floatval($objRs->fields["U_UNITPRICE"] - $objRs->fields["U_PRICE"])*-1;
				if ($objRs->fields["U_FREEBIE"]=="1") $data2["lines"][$lineid2]["DISCOUNT"] = 0;
				
				//$data2["lines"][$lineid2]["TOTALDISCOUNT"] = floatval($objRs->fields["U_LINETOTAL"]) - ($data2["lines"][$lineid2]["QUANTITY"] * $data2["lines"][$lineid2]["PRICE"]);
				//if ($objRs->fields["U_FREEBIE"]=="1") 
				$data2["lines"][$lineid2]["TOTALDISCOUNT"] = 0;
				$data2["lines"][$lineid2]["SERVICECHARGE"] = 0;
				$data2["lines"][$lineid2]["VATAMOUNT"] = 0;
				$data2["lines"][$lineid2]["DISCVATAMOUNT"] = 0;
				$data2["lines"][$lineid2]["SCVATAMOUNT"] = 0;
				$data2["lines"][$lineid2]["QTYPERUOM"] = $objRs->fields["U_NUMPERUOM"];
				$data2["lines"][$lineid2]["STDCOSTPRICE"] = 0;
				if ($objRs->fields["U_ITEMMANAGEBY"]=="2") {
					$data2["lines"][$lineid2]["SERIALNUMBERS"][0]["SERIALNO"] = $objRs->fields["U_SERIALNO"];
					$data2["lines"][$lineid2]["SERIALNUMBERS"][0]["MFRSERIALNO"] = "";
				}
				//if (!$cancelled) 
				$replacementamount += (floatval($objRs->fields["U_QUANTITY"])*-1) * floatval($objRs->fields["U_PRICE"]);
				$lineid2++;
			}	
		/*} else {
			$data["lines"][$lineid]["ITEMCODE"] = $objRs->fields["U_ITEMCODE"];
			$data["lines"][$lineid]["BARCODE"] = "";
			$data["lines"][$lineid]["ITEMDESC"] = $objRs->fields["U_ITEMDESC"];
			$data["lines"][$lineid]["UOM"] = $objRs->fields["U_UOM"];
			$data["lines"][$lineid]["QUANTITY"] = floatval($objRs->fields["U_QUANTITY"])*-1;
			$data["lines"][$lineid]["PRICE"] = floatval($objRs->fields["U_UNITPRICE"]);
			if ($objRs->fields["U_FREEBIE"]=="1") $data["lines"][$lineid]["PRICE"] = 0;
			$data["lines"][$lineid]["WAREHOUSE"] = "STORE";//$_SESSION["branch"];
			//$data["lines"][$lineid]["DISCOUNT"] = floatval($objRs->fields["U_DISCAMOUNT"])*-1;
			$data["lines"][$lineid]["DISCOUNT"] = 0;
			$data["lines"][$lineid]["TOTALDISCOUNT"] = floatval($objRs->fields["U_LINETOTAL"]*-1) - ($data["lines"][$lineid]["QUANTITY"] * $data["lines"][$lineid]["PRICE"]);
			if ($objRs->fields["U_FREEBIE"]=="1") $data["lines"][$lineid]["TOTALDISCOUNT"] = 0;
			$data["lines"][$lineid]["SERVICECHARGE"] = 0;
			$data["lines"][$lineid]["VATAMOUNT"] = 0;
			$data["lines"][$lineid]["DISCVATAMOUNT"] = 0;
			$data["lines"][$lineid]["SCVATAMOUNT"] = 0;
			$data["lines"][$lineid]["QTYPERUOM"] = $objRs->fields["U_NUMPERUOM"];
			$data["lines"][$lineid]["STDCOSTPRICE"] = 0;
			if ($objRs->fields["U_ITEMMANAGEBY"]=="2") {
				$data["lines"][$lineid]["SERIALNUMBERS"][0]["SERIALNO"] = $objRs->fields["U_SERIALNO"];
				$data["lines"][$lineid]["SERIALNUMBERS"][0]["MFRSERIALNO"] = "";
			}
			$lineid++;
		}	*/
	}
	$paycount=0;
	$paycount2=0;

	/*if ($objTable->getudfvalue("u_trxtype")=="S") {*/
		if (($objTable->getudfvalue("u_cashamount")+$objTable->getudfvalue("u_chequeamount"))>0) {
			$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_cashamount")+$objTable->getudfvalue("u_chequeamount");
			$paycount++;
		} elseif (($objTable->getudfvalue("u_cashamount")+$objTable->getudfvalue("u_chequeamount"))<0) {
			if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
				$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
				$data["payments"][$paycount]["AMOUNT"] = ($objTable->getudfvalue("u_cashamount")+$objTable->getudfvalue("u_chequeamount"))*-1;
				$paycount++;
			} else if($cancelled && $returned){
				$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
				$data["payments"][$paycount]["AMOUNT"] = ($objTable->getudfvalue("u_cashamount")+$objTable->getudfvalue("u_chequeamount"))*-1;
				$paycount++;
			} else {
				$data2["payments"][$paycount2]["PAYCODE"] = $cashpaycode;
				$data2["payments"][$paycount2]["AMOUNT"] = ($objTable->getudfvalue("u_cashamount")+$objTable->getudfvalue("u_chequeamount"))*-1;
				$paycount2++;
			}				
		}
		
		if ($objTable->getudfvalue("u_tfamount")>0) {
			$data["payments"][$paycount]["PAYCODE"] = $tfpaycode;
			$data["payments"][$paycount]["PAYCODEREFNO"] = $objTable->getudfvalue("u_tfrefno");
			$data["payments"][$paycount]["COUNTRY"] = $objTable->getudfvalue("u_tfcountry");
			$data["payments"][$paycount]["BANK"] = $objTable->getudfvalue("u_tfbank");
			$data["payments"][$paycount]["BANKACCTNO"] = $objTable->getudfvalue("u_tfbankacctno");
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_tfamount");
			$paycount++;
		} elseif ($objTable->getudfvalue("u_tfamount")<0) {
			if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
				$data["payments"][$paycount]["PAYCODE"] = $tfpaycode;
				$data["payments"][$paycount]["PAYCODEREFNO"] = $objTable->getudfvalue("u_tfrefno");
				$data["payments"][$paycount]["COUNTRY"] = $objTable->getudfvalue("u_tfcountry");
				$data["payments"][$paycount]["BANK"] = $objTable->getudfvalue("u_tfbank");
				$data["payments"][$paycount]["BANKACCTNO"] = $objTable->getudfvalue("u_tfbankacctno");
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_tfamount")*-1;
				$paycount++;
			} else if($cancelled && $returned){
				$data["payments"][$paycount]["PAYCODE"] = $tfpaycode;
				$data["payments"][$paycount]["PAYCODEREFNO"] = $objTable->getudfvalue("u_tfrefno");
				$data["payments"][$paycount]["COUNTRY"] = $objTable->getudfvalue("u_tfcountry");
				$data["payments"][$paycount]["BANK"] = $objTable->getudfvalue("u_tfbank");
				$data["payments"][$paycount]["BANKACCTNO"] = $objTable->getudfvalue("u_tfbankacctno");
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_tfamount")*-1;
				$paycount++;
			} else {	
				$data2["payments"][$paycount2]["PAYCODE"] = $tfpaycode;
				$data2["payments"][$paycount2]["PAYCODEREFNO"] = $objTable->getudfvalue("u_tfrefno");
				$data2["payments"][$paycount2]["COUNTRY"] = $objTable->getudfvalue("u_tfcountry");
				$data2["payments"][$paycount2]["BANK"] = $objTable->getudfvalue("u_tfbank");
				$data2["payments"][$paycount2]["BANKACCTNO"] = $objTable->getudfvalue("u_tfbankacctno");
				$data2["payments"][$paycount2]["AMOUNT"] = $objTable->getudfvalue("u_tfamount")*-1;
				$paycount2++;
			}	
		}
		
		if ($objTable->getudfvalue("u_dpamount")>0 ) {
			$data["payments"][$paycount]["PAYCODE"] = $dppaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_dpamount");
			$paycount++;
		} elseif ($objTable->getudfvalue("u_dpamount")<0) {
			if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
				$data["payments"][$paycount]["PAYCODE"] = $dppaycode;
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_dpamount")*-1;
				$paycount++;
			} else if($cancelled && $returned){
				$data["payments"][$paycount]["PAYCODE"] = $dppaycode;
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_dpamount")*-1;
				$paycount++;
			} else {	
				$data2["payments"][$paycount2]["PAYCODE"] = $dppaycode;
				$data2["payments"][$paycount2]["AMOUNT"] = $objTable->getudfvalue("u_dpamount")*-1;
				$paycount2++;
			}	
		}
		
		if ($objTable->getudfvalue("u_aramount")>0) {
			$data["payments"][$paycount]["PAYCODE"] = $arpaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_aramount");
			$paycount++;
		} elseif ($objTable->getudfvalue("u_aramount")<0) {
			if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
				$data["payments"][$paycount]["PAYCODE"] = $arpaycode;
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_aramount")*-1;
				$paycount++;
			} else if($cancelled && $returned){
				$data["payments"][$paycount]["PAYCODE"] = $arpaycode;
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_aramount")*-1;
				$paycount++;
			} else {
				$data2["payments"][$paycount2]["PAYCODE"] = $arpaycode;
				$data2["payments"][$paycount2]["AMOUNT"] = $objTable->getudfvalue("u_aramount")*-1;
				$paycount2++;
			}	
		}
		
		if ($replacementamount!=0) {
			$data["payments"][$paycount]["PAYCODE"] = "RPLCE";
			$data["payments"][$paycount]["AMOUNT"] = $replacementamount;
			$paycount++;

			$data2["payments"][$paycount2]["PAYCODE"] = "RPLCE";
			$data2["payments"][$paycount2]["AMOUNT"] = $replacementamount;
			$paycount2++;
		}

		$objRs2->queryopen("select U_CASHCARD,U_REFNO,U_AMOUNT from U_POSCASHCARDS where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($objTable->getudfvalue("u_otheramount")>0) {
				$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CASHCARD"];
				$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_REFNO"];
				$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"];
				$paycount++;
			} elseif ($objTable->getudfvalue("u_otheramount")<0) {
				if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
					$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CASHCARD"];
					$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_REFNO"];
					$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
					$paycount++;
				} else if($cancelled && $returned){
					$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CASHCARD"];
					$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_REFNO"];
					$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
					$paycount++;
				} else {	
					$data2["payments"][$paycount2]["PAYCODE"] = $objRs2->fields["U_CASHCARD"];
					$data2["payments"][$paycount2]["PAYCODEREFNO"] = $objRs2->fields["U_REFNO"];
					$data2["payments"][$paycount2]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
					$paycount2++;
				}	
			}
		}

		/*
		$objRs2->queryopen("select U_CHECKDATE, U_BANK, U_CHECKNO, U_AMOUNT from U_POSCHEQUES where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($objTable->getudfvalue("u_chequeamount")>0) {
				$data["payments"][$paycount]["PAYCODE"] = $checkpaycode;
				$data["payments"][$paycount]["CHECKDATE"] = $objRs2->fields["U_CHECKDATE"];
				$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CHECKNO"];
				$data["payments"][$paycount]["BANK"] = $objRs2->fields["U_BANK"];
				$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"];
				$paycount++;
			}  elseif ($objTable->getudfvalue("u_chequeamount")<0) {
				if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
					$data["payments"][$paycount]["PAYCODE"] = $checkpaycode;
					$data["payments"][$paycount]["CHECKDATE"] = $objRs2->fields["U_CHECKDATE"];
					$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CHECKNO"];
					$data["payments"][$paycount]["BANK"] = $objRs2->fields["U_BANK"];
					$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
					$paycount++;
				} else {	
					$data2["payments"][$paycount2]["PAYCODE"] = $checkpaycode;
					$data2["payments"][$paycount2]["CHECKDATE"] = $objRs2->fields["U_CHECKDATE"];
					$data2["payments"][$paycount2]["PAYCODEREFNO"] = $objRs2->fields["U_CHECKNO"];
					$data2["payments"][$paycount2]["BANK"] = $objRs2->fields["U_BANK"];
					$data2["payments"][$paycount2]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
					$paycount2++;
				}	
			}
		}
		*/
		/*
		if ($objTable->getudfvalue("u_chequeamount")>0) {
			$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_chequeamount");
			$paycount++;
		} elseif ($objTable->getudfvalue("u_chequeamount")<0) {
			if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {
				$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
				$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_chequeamount")*-1;
				$paycount++;
			} else {
				$data2["payments"][$paycount2]["PAYCODE"] = $cashpaycode;
				$data2["payments"][$paycount2]["AMOUNT"] = $objTable->getudfvalue("u_chequeamount")*-1;
				$paycount2++;
			}				
		}
		*/
		$objRs2->queryopen("select U_CREDITCARD, U_CREDITCARDNO, U_CREDITCARDNAME, U_EXPIREDATE, U_APPROVALNO, U_AMOUNT from U_POSCREDITCARDS where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($objTable->getudfvalue("u_creditcardamount")>0) {
				$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CREDITCARD"];
				$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CREDITCARDNO"];
				$data["payments"][$paycount]["CREDITCARDEXPIREDATE"] = $objRs2->fields["U_EXPIREDATE"];
				$data["payments"][$paycount]["CREDITCARDAPPROVALNO"] = $objRs2->fields["U_APPROVALNO"];
				$data["payments"][$paycount]["CREDITCARDHOLDERNAME"] = $objRs2->fields["U_CREDITCARDNAME"];
				$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"];
				$paycount++;
			} elseif ($objTable->getudfvalue("u_creditcardamount")<0) {
				if (!$cancelled && $objTable->getudfvalue("u_trxtype")=="R") {	
					$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CREDITCARD"];
					$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CREDITCARDNO"];
					$data["payments"][$paycount]["CREDITCARDEXPIREDATE"] = $objRs2->fields["U_EXPIREDATE"];
					$data["payments"][$paycount]["CREDITCARDAPPROVALNO"] = $objRs2->fields["U_APPROVALNO"];
					$data["payments"][$paycount]["CREDITCARDHOLDERNAME"] = $objRs2->fields["U_CREDITCARDNAME"];
					$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"] * -1;
					$paycount++;
				} else if($cancelled && $returned){
					$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CREDITCARD"];
					$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CREDITCARDNO"];
					$data["payments"][$paycount]["CREDITCARDEXPIREDATE"] = $objRs2->fields["U_EXPIREDATE"];
					$data["payments"][$paycount]["CREDITCARDAPPROVALNO"] = $objRs2->fields["U_APPROVALNO"];
					$data["payments"][$paycount]["CREDITCARDHOLDERNAME"] = $objRs2->fields["U_CREDITCARDNAME"];
					$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"] * -1;
					$paycount++;
				} else {
					$data2["payments"][$paycount2]["PAYCODE"] = $objRs2->fields["U_CREDITCARD"];
					$data2["payments"][$paycount2]["PAYCODEREFNO"] = $objRs2->fields["U_CREDITCARDNO"];
					$data2["payments"][$paycount2]["CREDITCARDEXPIREDATE"] = $objRs2->fields["U_EXPIREDATE"];
					$data2["payments"][$paycount2]["CREDITCARDAPPROVALNO"] = $objRs2->fields["U_APPROVALNO"];
					$data2["payments"][$paycount2]["CREDITCARDHOLDERNAME"] = $objRs2->fields["U_CREDITCARDNAME"];
					$data2["payments"][$paycount2]["AMOUNT"] = $objRs2->fields["U_AMOUNT"] * -1;
					$paycount2++;
				}	
			}
		}
	/*} else {
		if ($objTable->getudfvalue("u_cashamount")!=0) {
			$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_cashamount")*-1;
			$paycount++;
		}

		$objRs2->queryopen("select U_CASHCARD,U_REFNO,U_AMOUNT from U_POSCASHCARDS where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($cancelled) {
				$objRs2->fields["U_AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
			}
			
			$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CASHCARD"];
			$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_REFNO"];
			$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
			$paycount++;
		}
		
		if ($cancelled) {
			$objRs2->queryopen("select sum(U_AMOUNT) as U_AMOUNT from U_POSCHEQUES where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
			if ($objRs2->queryfetchrow("NAME")) {
				$data["payments"][$paycount]["PAYCODE"] = $cashpaycode;
				$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"];
				$paycount++;
			}	
		} else {
			$objRs2->queryopen("select U_CHECKDATE, U_BANK, U_CHECKNO, U_AMOUNT from U_POSCHEQUES where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
			while ($objRs2->queryfetchrow("NAME")) {
				if ($cancelled) {
					$objRs2->fields["U_AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
				}
				
				$data["payments"][$paycount]["PAYCODE"] = $checkpaycode;
				$data["payments"][$paycount]["CHECKDATE"] = $objRs2->fields["U_CHECKDATE"];
				$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CHECKNO"];
				$data["payments"][$paycount]["BANK"] = $objRs2->fields["U_BANK"];
				$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
				$paycount++;
			}
		}
		
		$objRs2->queryopen("select U_CREDITCARD, U_CREDITCARDNO, U_CREDITCARDNAME, U_EXPIREDATE, U_APPROVALNO, U_AMOUNT from U_POSCREDITCARDS where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and DOCID='".$objTable->docid."'");
		while ($objRs2->queryfetchrow("NAME")) {
			if ($cancelled) {
				$objRs2->fields["U_AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
			}
			$data["payments"][$paycount]["PAYCODE"] = $objRs2->fields["U_CREDITCARD"];
			$data["payments"][$paycount]["PAYCODEREFNO"] = $objRs2->fields["U_CREDITCARDNO"];
			$data["payments"][$paycount]["CREDITCARDEXPIREDATE"] = $objRs2->fields["U_EXPIREDATE"];
			$data["payments"][$paycount]["CREDITCARDAPPROVALNO"] = $objRs2->fields["U_APPROVALNO"];
			$data["payments"][$paycount]["CREDITCARDHOLDERNAME"] = $objRs2->fields["U_CREDITCARDNAME"];
			$data["payments"][$paycount]["AMOUNT"] = $objRs2->fields["U_AMOUNT"]*-1;
			$paycount++;
		}
		
		if ($objTable->getudfvalue("u_aramount")!=0) {
			$data["payments"][$paycount]["PAYCODE"] = $arpaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_aramount")*-1;
			$paycount++;
		}
		if ($objTable->getudfvalue("u_dpamount")!=0) {
			$data["payments"][$paycount]["PAYCODE"] = $dppaycode;
			$data["payments"][$paycount]["AMOUNT"] = $objTable->getudfvalue("u_dpamount")*-1;
			$paycount++;
		}
	}*/
	//var_dump(array($actionReturn,$lineid));
	if ($actionReturn && ($lineid>0 || $lineid2>0)) {
		//var_dump($posnegated);
		//var_dump($data["header"]["TRANSNO"]);
		//$page->console->insertVar(array($data,$data2));
		//var_dump($data,$data2);
		//var_dump($data2["payments"]);
		if ($posnegated) $actionReturn = processJimacPOSNEGATED(null,array($data,$data2),false);
		else {
			//if ($objTable->getudfvalue("u_trxtype")=="S"){
				if ($cancelled) {
					$data["header"]["TOTALAMOUNT"] = floatval($objTable->getudfvalue("u_totalamount"))*-1;
				} else $data["header"]["TOTALAMOUNT"] = floatval($objTable->getudfvalue("u_totalamount"));
			//} else {
			//	$data["header"]["TOTALAMOUNT"] = floatval($objTable->getudfvalue("u_totalamount"))*-1;
			//}
			$actionReturn = processJimacPOS(null,$data,false);
		}	
		//var_dump(array($data["header"]["TRANSNO"],$actionReturn));
		
	}	
	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostPOSdocumentschema_brGPSLGU()");
	return $actionReturn;
}


function onCustomEventupdatefaasdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	
//	if ($objTable->docstatus=="CN") return true;
	
	
	
	$obju_RPFaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$obju_RPFaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_RPFaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
	$obju_RpTaxBalance = new masterdataschema_br(null,$objConnection,"u_taxbalance");
	$obju_TaxCredits = new masterdataschema_br(null,$objConnection,"u_taxcredits");
        
        $objRs1 = new recordset (NULL,$objConnection);
	$objRs1->queryopen("select U_RPTASESSORTREASURYLINK from u_lgusetup ");
	while ($objRs1->queryfetchrow("NAME")) {
            $objRs = new recordset (NULL,$objConnection);
            if ($objTable->getudfvalue("u_module") == "Real Property Tax Bill") {
                if ($objTable->getudfvalue("u_status")=="CN") {
                    $objRs->queryopen("select r.u_docno as u_arpno, p.u_kind, max(r.u_yrto) as u_yrto,min(u_yrfr) as u_yrfr,min(r.u_payqtr) as u_qtrfr,max(r.u_payqtr) as u_qtrto,r.u_paymode from u_lgupos a 
                                        inner join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch
                                        inner join u_rptaxbill c on b.u_appno = c.docno and b.company = c.company and b.branch = c.branch
                                        inner join u_rptaxbillarps r on c.docid = r.docid and c.company = r.company and c.branch = r.branch
                                        inner join u_rptaxbillpins p on c.docid = p.docid and r.u_docno = p.u_docno
                                        where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='$objTable->docno' and r.u_selected=1 
                                        group by r.u_docno,r.u_paymode order by r.u_yrto desc");
                } else {
                     $objRs->queryopen("select r.u_docno as u_arpno, p.u_kind, max(r.u_yrto) as u_yrto,min(u_yrfr) as u_yrfr,min(r.u_payqtr) as u_qtrfr,max(r.u_payqtr) as u_qtrto,r.u_paymode from u_lgupos a 
                                        inner join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch
                                        inner join u_rptaxbill c on b.u_appno = c.docno and b.company = c.company and b.branch = c.branch
                                        inner join u_rptaxbillarps r on c.docid = r.docid and c.company = r.company and c.branch = r.branch
                                        inner join u_rptaxbillpins p on c.docid = p.docid and r.u_docno = p.u_docno
                                        where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='$objTable->docno' and r.u_selected=1 
                                        group by r.u_docno,r.u_paymode order by r.u_yrto");
                }
            } else {
                 $objRs->queryopen("select r.u_arpno, r.u_kind, max(r.u_yrto) as u_yrto,min(u_yrfr) as u_yrfr,u_withfaas from u_lgupos a inner join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch inner join u_rptaxes c on b.u_appno = c.docno inner join u_rptaxarps r on c.docid = r.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='$objTable->docno' and r.u_selected=1 and r.u_isbalance=0 group by r.u_arpno order by r.u_yrto");
            }
           
                    while ($objRs->queryfetchrow("NAME")) {
                                switch ($objRs->fields["u_kind"]) {
                                            case "LAND": 
                                                    if ($obju_RPFaas1->getbykey($objRs->fields["u_arpno"])) {
                                                        if ($objTable->getudfvalue("u_status")=="CN") {
                                                            $obju_RPFaas1->setudfvalue("u_bilyear",$objRs->fields["u_yrfr"]-1);
                                                            $obju_RPFaas1->setudfvalue("u_bilqtr",4);
                                                            $obju_RPFaas1->setudfvalue("u_lastpaymode","A");
                                                            
                                                                if ($objRs->fields["u_paymode"] == "S") {
                                                                        if ($objRs->fields["u_qtrfr"] == 2) {
                                                                            $obju_RPFaas1->setudfvalue("u_bilqtr",4);
                                                                            $obju_RPFaas1->setudfvalue("u_lastpaymode","A");
                                                                        } else {
                                                                            $obju_RPFaas1->setudfvalue("u_bilqtr",$objRs->fields["u_qtrfr"] - 2);
                                                                            $obju_RPFaas1->setudfvalue("u_lastpaymode","S");
                                                                        }
                                                                } else if ($objRs->fields["u_paymode"] == "Q") {
                                                                        if ($objRs->fields["u_qtrfr"] == 1) {
                                                                            $obju_RPFaas1->setudfvalue("u_bilqtr",4);
                                                                            $obju_RPFaas1->setudfvalue("u_lastpaymode","A");
                                                                        } else {
                                                                            $obju_RPFaas1->setudfvalue("u_bilqtr",$objRs->fields["u_qtrfr"] - 1);
                                                                            $obju_RPFaas1->setudfvalue("u_lastpaymode","Q");
                                                                        }
                                                                }
                                                        } else {
                                                            if ($obju_RPFaas1->getudfvalue("u_bilyear")>=$objRs->fields["u_yrto"] && $obju_RPFaas1->getudfvalue("u_bilqtr")>=$objRs->fields["u_qtrto"]) {
                                                                return raiseError("Property Land ARP No. [".$obju_RPFaas1->getudfvalue("u_varpno")."] already paid.");
                                                            } else {
                                                                $obju_RPFaas1->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                                $obju_RPFaas1->setudfvalue("u_bilqtr",$objRs->fields["u_qtrto"]);
                                                                $obju_RPFaas1->setudfvalue("u_lastpaymode",$objRs->fields["u_paymode"]);
                                                            }
                                                            
                                                        }
                                                            $actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion);
                                                    } else return raiseError("Unable to find land Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                            case "BUILDING": 
                                                    if ($obju_RPFaas2->getbykey($objRs->fields["u_arpno"])) {
                                                            if($objTable->getudfvalue("u_status")=="CN"){
                                                                $obju_RPFaas2->setudfvalue("u_bilyear",$objRs->fields["u_yrfr"]-1);
                                                                $obju_RPFaas2->setudfvalue("u_bilqtr",4);
                                                                $obju_RPFaas2->setudfvalue("u_lastpaymode","A");

                                                                    if ($objRs->fields["u_paymode"] == "S") {
                                                                            if ($objRs->fields["u_qtrfr"] == 2) {
                                                                                $obju_RPFaas2->setudfvalue("u_bilqtr",4);
                                                                                $obju_RPFaas2->setudfvalue("u_lastpaymode","A");
                                                                            } else {
                                                                                $obju_RPFaas2->setudfvalue("u_bilqtr",$objRs->fields["u_qtrfr"] - 2);
                                                                                $obju_RPFaas2->setudfvalue("u_lastpaymode","S");
                                                                            }
                                                                    } else if ($objRs->fields["u_paymode"] == "Q") {
                                                                            if ($objRs->fields["u_qtrfr"] == 1) {
                                                                                $obju_RPFaas2->setudfvalue("u_bilqtr",4);
                                                                                $obju_RPFaas2->setudfvalue("u_lastpaymode","A");
                                                                            } else {
                                                                                $obju_RPFaas2->setudfvalue("u_bilqtr",$objRs->fields["u_qtrfr"] - 1);
                                                                                $obju_RPFaas2->setudfvalue("u_lastpaymode","Q");
                                                                            }
                                                                    }
                                                            }else{
                                                                if ($obju_RPFaas2->getudfvalue("u_bilyear")>=$objRs->fields["u_yrto"] && $obju_RPFaas2->getudfvalue("u_bilqtr")>=$objRs->fields["u_qtrto"]) {
                                                                    return raiseError("Property Building ARP No. [".$obju_RPFaas2->getudfvalue("u_varpno")."] already paid.");
                                                                } else {
                                                                    $obju_RPFaas2->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                                    $obju_RPFaas2->setudfvalue("u_bilqtr",$objRs->fields["u_qtrto"]);
                                                                    $obju_RPFaas2->setudfvalue("u_lastpaymode",$objRs->fields["u_paymode"]);
                                                                }
                                                            }
                                                            $actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion);
                                                    } else return raiseError("Unable to find building Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                            case "MACHINERY": 
                                                    if ($obju_RPFaas3->getbykey($objRs->fields["u_arpno"])) {
                                                            if($objTable->getudfvalue("u_status")=="CN"){
                                                                $obju_RPFaas3->setudfvalue("u_bilyear",$objRs->fields["u_yrfr"]-1);
                                                                $obju_RPFaas3->setudfvalue("u_bilqtr",4);
                                                                $obju_RPFaas3->setudfvalue("u_lastpaymode","A");

                                                                    if ($objRs->fields["u_paymode"] == "S") {
                                                                            if ($objRs->fields["u_qtrfr"] == 2) {
                                                                                $obju_RPFaas3->setudfvalue("u_bilqtr",4);
                                                                                $obju_RPFaas3->setudfvalue("u_lastpaymode","A");
                                                                            } else {
                                                                                $obju_RPFaas3->setudfvalue("u_bilqtr",$objRs->fields["u_qtrfr"] - 2);
                                                                                $obju_RPFaas3->setudfvalue("u_lastpaymode","S");
                                                                            }
                                                                    } else if ($objRs->fields["u_paymode"] == "Q") {
                                                                            if ($objRs->fields["u_qtrfr"] == 1) {
                                                                                $obju_RPFaas3->setudfvalue("u_bilqtr",4);
                                                                                $obju_RPFaas3->setudfvalue("u_lastpaymode","A");
                                                                            } else {
                                                                                $obju_RPFaas3->setudfvalue("u_bilqtr",$objRs->fields["u_qtrfr"] - 1);
                                                                                $obju_RPFaas3->setudfvalue("u_lastpaymode","Q");
                                                                            }
                                                                    }
                                                            }else{
                                                                if ($obju_RPFaas3->getudfvalue("u_bilyear")>=$objRs->fields["u_yrto"] && $obju_RPFaas3->getudfvalue("u_bilqtr")>=$objRs->fields["u_qtrto"]) {
                                                                    return raiseError("Property Machinery ARP No. [".$obju_RPFaas3->getudfvalue("u_varpno")."] already paid.");
                                                                } else {
                                                                $obju_RPFaas3->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                                $obju_RPFaas3->setudfvalue("u_bilqtr",$objRs->fields["u_qtrto"]);
                                                                $obju_RPFaas3->setudfvalue("u_lastpaymode",$objRs->fields["u_paymode"]);
                                                                }
                                                            }
                                                            $actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion);
                                                    } else return raiseError("Unable to find machinery Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                    }
                                   
                                    if (!$actionReturn) break;
                            }
                                    if($objTable->getudfvalue("u_status")=="CN"){
                                        $objRs2 = new recordset (NULL,$objConnection);
                                        $objRs2->queryopen("SELECT r.u_apprefno from u_lgupos a inner join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch inner join u_rptaxbill c on b.u_appno = c.docno inner join u_rptaxbillcredits r on c.docid = r.docid and c.company = r.company and c.branch = r.branch where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='$objTable->docno' and r.u_selected=1 order by r.u_year");
                                            while ($objRs2->queryfetchrow("NAME")) {
                                                       if($obju_TaxCredits->getbykey($objRs2->fields["u_apprefno"])){
                                                           $obju_TaxCredits->setudfvalue("u_status","O");
                                                           $actionReturn = $obju_TaxCredits->update($obju_TaxCredits->code,$obju_TaxCredits->rcdversion);
                                                       }
                                                   if (!$actionReturn) break;
                                            }
                                    } else {
                                        $objRs2 = new recordset (NULL,$objConnection);
                                        $objRs2->queryopen("SELECT r.u_apprefno from u_lgupos a inner join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch inner join u_rptaxbill c on b.u_appno = c.docno inner join u_rptaxbillcredits r on c.docid = r.docid and c.company = r.company and c.branch = r.branch where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='$objTable->docno' and r.u_selected=1 order by r.u_year");
                                            while ($objRs2->queryfetchrow("NAME")) {
                                                       if($obju_TaxCredits->getbykey($objRs2->fields["u_apprefno"])){
                                                           $obju_TaxCredits->setudfvalue("u_status","C");
                                                           $actionReturn = $obju_TaxCredits->update($obju_TaxCredits->code,$obju_TaxCredits->rcdversion);
                                                       }
                                              if (!$actionReturn) break;
                                            }
                                    } 
        }
      
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatefaasdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}
function onCustomEventUpdateLGUBillsdocumentschema_brGPSLGU($objTable,$delete=false) {
	global $objConnection;
        global $page;
	$actionReturn = true;
        
	if ($objTable->getudfvalue("u_billno")=="") return true;
        
	$objRs = new recordset(null,$objConnection);
	$obju_Rptaxes = new documentschema_br(null,$objConnection,"u_rptaxes");
        $obju_BPLApps = new documentschema_br(null,$objConnection,"u_bplapps");
        $obju_LGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
        $obju_LGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");	

        if ($obju_LGUBills->getbykey($objTable->getudfvalue("u_billno"))) {
            if ($delete) {
                    $objRs->queryopen("select U_ITEMCODE, U_ITEMDESC, sum(U_LINETOTAL) as U_AMOUNT,U_BASELINEID from u_lgupositems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' group by u_itemcode, u_itemdesc, u_baselineid");
                    while ($objRs->queryfetchrow("NAME")) {
                            if ($obju_LGUBillItems->getbysql("DOCID='$obju_LGUBills->docid' AND U_ITEMCODE='".$objRs->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs->fields["U_BASELINEID"]."'")) {
                                $obju_LGUBillItems->setudfvalue("u_settledamount", $obju_LGUBillItems->getudfvalue("u_settledamount") - $objRs->fields["U_AMOUNT"]);
                                $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                if (!$actionReturn) break;
                            } //else return raiseError("Invalid Bill Item [".$objRs->fields["U_ITEMDESC"]."]");
                    }
                    if ($actionReturn) {
                            $obju_LGUBills->setudfvalue("u_dueamount",$obju_LGUBills->getudfvalue("u_dueamount")+($objTable->getudfvalue("u_paidamount")-$objTable->getudfvalue("u_penaltyamount")));
                            $obju_LGUBills->docstatus = "O";
                            $obju_LGUBills->setudfvalue("u_paiddate","");
                            $obju_LGUBills->setudfvalue("u_settledamount",$obju_LGUBills->getudfvalue("u_settledamount")-($objTable->getudfvalue("u_paidamount")-$objTable->getudfvalue("u_penaltyamount")));
                            $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                    }
                    if ($actionReturn && $obju_LGUBills->getudfvalue("u_appno")!="") {
                        if ($obju_LGUBills->getudfvalue("u_module")=="Business Permit") {
                                if ($obju_BPLApps->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
                                        if ($obju_LGUBills->getudfvalue("u_preassbill")==1) {
                                            $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate=null, u_payrefno='' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);
                                            if ($actionReturn) $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate=null, u_payrefno='' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);

                                        } elseif ($obju_LGUBills->fields["DOCSTATUS"]=="C") {
                                                $obju_BPLApps->setudfvalue("u_paycount",$obju_BPLApps->getudfvalue("u_paycount")-1);
                                                if ($obju_BPLApps->getudfvalue("u_paycount")==0 && ($obju_BPLApps->docstatus=="Printing" || $obju_BPLApps->docstatus=="Paid")) {
                                                    $obju_BPLApps->docstatus="Approved";
                                                    $obju_BPLApps->setudfvalue("u_printed",0);
                                                    $actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
                                                }
                                        }	
                                } else return raiseError("Unable to find Application No.[".$obju_LGUBills->getudfvalue("u_appno")."].");
                        }			
                    }
            } else {
                    $objRs->queryopen("select U_ITEMCODE, U_ITEMDESC, sum(U_LINETOTAL) as U_AMOUNT,U_BASELINEID from u_lgupositems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' group by u_itemcode, u_itemdesc, u_baselineid");
                    while ($objRs->queryfetchrow("NAME")) {
                        if ($obju_LGUBillItems->getbysql("DOCID='$obju_LGUBills->docid' AND U_ITEMCODE='".$objRs->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs->fields["U_BASELINEID"]."'")) {
                            $obju_LGUBillItems->setudfvalue("u_settledamount", $obju_LGUBillItems->getudfvalue("u_settledamount") + $objRs->fields["U_AMOUNT"]);
                                if ($obju_LGUBillItems->getudfvalue("u_settledamount")>$obju_LGUBillItems->getudfvalue("u_amount")) {
                                    return raiseError("[".$objTable->getudfvalue("u_orno")."] Bill Item [".$objRs->fields["U_ITEMDESC"]."] was overpaid by [".($obju_LGUBillItems->getudfvalue("u_settledamount") - $obju_LGUBillItems->getudfvalue("u_amount"))."].");
                                }
                                    $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                    if (!$actionReturn) break;
                        } //else return raiseError("Invalid Bill Item [".$objRs->fields["U_ITEMDESC"]."]");
                    }
                    
                    if ($actionReturn) {
                        $obju_LGUBills->setudfvalue("u_dueamount",bcsub($obju_LGUBills->getudfvalue("u_dueamount"),($objTable->getudfvalue("u_paidamount")-$objTable->getudfvalue("u_penaltyamount")),2));
                            if ($obju_LGUBills->getudfvalue("u_dueamount")>0) {
                                    $obju_LGUBills->docstatus = "O";
                                    $obju_LGUBills->setudfvalue("u_paiddate","");
                            } elseif ($obju_LGUBills->getudfvalue("u_dueamount")<0) {
                                    return raiseError("Bill is overpaid by [".$obju_LGUBills->getudfvalue("u_dueamount")."].");
                            } else {
                                    $obju_LGUBills->docstatus = "C";
                                    $obju_LGUBills->setudfvalue("u_paiddate",$objTable->getudfvalue("u_docdate"));
                            }
                                $obju_LGUBills->setudfvalue("u_settledamount",$obju_LGUBills->getudfvalue("u_settledamount")+($objTable->getudfvalue("u_paidamount")-$objTable->getudfvalue("u_penaltyamount")));
                                $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                    }
                    if ($actionReturn && $obju_LGUBills->getudfvalue("u_module")=="Business Permit") {
                           if ($actionReturn && $obju_LGUBills->getudfvalue("u_appno")!="") {
                                if ($obju_BPLApps->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
                                    if ($obju_LGUBills->getudfvalue("u_preassbill")==1) {
                                            $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate='".$objTable->getudfvalue("u_date")."', u_payrefno='".$objTable->docno."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);
                                            if ($actionReturn) $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate='".$objTable->getudfvalue("u_date")."', u_payrefno='".$objTable->docno."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);
                                    } elseif ($obju_LGUBills->docstatus=="C") {
                                            $obju_BPLApps->setudfvalue("u_paycount",$obju_BPLApps->getudfvalue("u_paycount")+1);
                                            if ($obju_BPLApps->docstatus=="Approved") {
                                                    $obju_BPLApps->docstatus="Paid";
                                            }
                                    }
                                        $actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
                                    }// else return raiseError("Unable to find Application No.[".$obju_LGUBills->getudfvalue("u_appno")."].");	
                            }
                    }
                            
                        }
                        
                        //} else return raiseError("Payment amount [".$objTable->getudfvalue("u_paidamount")."] is not equal to bill due amount [".$obju_LGUBills->getudfvalue("u_dueamount")."].");
                } else return raiseError("Unable to retrieve Bill No. [".$objTable->getudfvalue("u_billno")."].");	
                
	return $actionReturn;
}


function onCustomEventUpdateMultipleLGUBillsdocumentschema_brGPSLGU($objTable,$delete=false) {
	global $objConnection;
        global $page;
	$actionReturn = true;
        
	
	$objRs = new recordset(null,$objConnection);
	$objRs1 = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	$obju_Rptaxes = new documentschema_br(null,$objConnection,"u_rptaxes");
        $obju_BPLApps = new documentschema_br(null,$objConnection,"u_bplapps");
        $obju_LGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
        $obju_LGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");	
        
	$objRs->queryopen("select u_billno from u_lguposbillnos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
	while ($objRs->queryfetchrow("NAME")) {
                if ($obju_LGUBills->getbykey($objRs->fields["u_billno"])) {
                        if ($delete) {
                                $objRs1->queryopen("select U_ITEMCODE, U_ITEMDESC, sum(U_LINETOTAL) as U_AMOUNT,U_BASELINEID from u_lgupositems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_basedocno = '".$objRs->fields["u_billno"]."' group by u_itemcode, u_itemdesc,u_baselineid");
                                    while ($objRs1->queryfetchrow("NAME")) {
                                        if ($obju_LGUBillItems->getbysql("DOCID='$obju_LGUBills->docid' AND U_ITEMCODE='".$objRs1->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs1->fields["U_BASELINEID"]."'")) {
                                                $obju_LGUBillItems->setudfvalue("u_settledamount", $obju_LGUBillItems->getudfvalue("u_settledamount") - $objRs1->fields["U_AMOUNT"]);
                                                $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                                if (!$actionReturn) break;
                                        } //else return raiseError("Invalid Bill Item [".$objRs->fields["U_ITEMDESC"]."]");
                                    }
                                if ($actionReturn) {
                                        $objRs2->queryopen("select sum(U_LINETOTAL) as U_PAIDAMOUNT from u_lgupositems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_basedocno = '".$objRs->fields["u_billno"]."' group by u_basedocno");
                                        while ($objRs2->queryfetchrow("NAME")) {
                                            $obju_LGUBills->setudfvalue("u_dueamount",$obju_LGUBills->getudfvalue("u_dueamount")+$objRs2->fields["U_PAIDAMOUNT"]);
                                            $obju_LGUBills->docstatus = "O";
                                            $obju_LGUBills->setudfvalue("u_paiddate","");
                                            $obju_LGUBills->setudfvalue("u_settledamount",bcsub($obju_LGUBills->getudfvalue("u_settledamount"),$objRs2->fields["U_PAIDAMOUNT"],2));
                                            $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                                        }
                                }
                                if ($actionReturn && $obju_LGUBills->getudfvalue("u_appno")!="") {
                                    if ($obju_LGUBills->getudfvalue("u_module")=="Business Permit") {
                                            if ($obju_BPLApps->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
                                                    if ($obju_LGUBills->getudfvalue("u_preassbill")==1) {
                                                            $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate=null, u_payrefno='' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);
                                                            if ($actionReturn) $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate=null, u_payrefno='' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);

                                                    } elseif ($obju_LGUBills->fields["DOCSTATUS"]=="C") {
                                                            $obju_BPLApps->setudfvalue("u_paycount",$obju_BPLApps->getudfvalue("u_paycount")-1);
                                                            if ($obju_BPLApps->getudfvalue("u_paycount")==0 && ($obju_BPLApps->docstatus=="Printing" || $obju_BPLApps->docstatus=="Paid")) {
                                                                    $obju_BPLApps->docstatus="Approved";
                                                                    $obju_BPLApps->setudfvalue("u_printed",0);
                                                                    $actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
                                                            }
                                                    }	
                                            } else return raiseError("Unable to find Application No.[".$obju_LGUBills->getudfvalue("u_appno")."].");
                                    }			
                                }
                        } else { 
                                $objRs2->queryopen("select U_ITEMCODE, U_ITEMDESC, sum(U_LINETOTAL) as U_AMOUNT,U_BASELINEID from u_lgupositems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_basedocno = '".$objRs->fields["u_billno"]."' group by u_itemcode, u_itemdesc,u_baselineid");
                                while ($objRs2->queryfetchrow("NAME")) {
                                     if ($obju_LGUBillItems->getbysql("DOCID='$obju_LGUBills->docid' AND U_ITEMCODE='".$objRs2->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs2->fields["U_BASELINEID"]."'")) {
                                            $obju_LGUBillItems->setudfvalue("u_settledamount", $obju_LGUBillItems->getudfvalue("u_settledamount") + $objRs2->fields["U_AMOUNT"]);
                                            if ($obju_LGUBillItems->getudfvalue("u_settledamount")>$obju_LGUBillItems->getudfvalue("u_amount")) {
                                                    if($obju_LGUBills->getudfvalue("u_migrated")==1){
                                                        $obju_LGUBills->setudfvalue("u_migratedremarks","[".$objTable->getudfvalue("u_orno")."]Bill Item [".$objRs2->fields["U_ITEMDESC"]."] was overpaid by [".($obju_LGUBillItems->getudfvalue("u_settledamount") - $obju_LGUBillItems->getudfvalue("u_amount"))."].");
                                                    } else {
                                                        return raiseError("[".$objTable->getudfvalue("u_orno")."] Bill Item [".$objRs2->fields["U_ITEMDESC"]."] was overpaid by [".($obju_LGUBillItems->getudfvalue("u_settledamount") - $obju_LGUBillItems->getudfvalue("u_amount"))."].");
                                                    }
                                            }
                                                $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                                if (!$actionReturn) break;
                                    } //else return raiseError("Invalid Bill Item [".$objRs->fields["U_ITEMDESC"]."]");
                                }
                                if ($actionReturn) {
                                    $objRs2->queryopen("select sum(U_LINETOTAL) as U_PAIDAMOUNT from u_lgupositems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_basedocno = '".$objRs->fields["u_billno"]."' group by u_basedocno");
                                    while ($objRs2->queryfetchrow("NAME")) {
//                                            $obju_LGUBills->setudfvalue("u_dueamount",bcsub($obju_LGUBills->getudfvalue("u_dueamount"),($objRs2->fields["U_PAIDAMOUNT"]-$objTable->getudfvalue("u_penaltyamount")),2));
                                            $obju_LGUBills->setudfvalue("u_dueamount",bcsub($obju_LGUBills->getudfvalue("u_dueamount"),$objRs2->fields["U_PAIDAMOUNT"],2));
                                            if ($obju_LGUBills->getudfvalue("u_dueamount")>0) {
                                                    $obju_LGUBills->docstatus = "O";
                                                    $obju_LGUBills->setudfvalue("u_paiddate","");
                                            } elseif ($obju_LGUBills->getudfvalue("u_dueamount")<0) {
                                                    if($obju_LGUBills->getudfvalue("u_migrated")==1) {
                                                            $obju_LGUBills->setudfvalue("u_migratedremarks","[".$objTable->getudfvalue("u_orno")."] Bill is overpaid by [".$obju_LGUBills->getudfvalue("u_dueamount")."].");
                                                            $objTable->setudfvalue("u_status","CN");
                                                    } else{
                                                            if ($obju_Rptaxes->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
                                                                $obju_Rptaxes->docstatus="O";
                                                                $actionReturn = $obju_Rptaxes->update($obju_Rptaxes->docno,$obju_Rptaxes->rcdversion);
                                                            }
                                                            return raiseError("Bill is overpaid by [".$obju_LGUBills->getudfvalue("u_dueamount")."].");
                                                    }
                                            } else {
                                                if ($obju_Rptaxes->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
                                                    $obju_Rptaxes->setudfvalue("u_dueamount",bcsub($obju_Rptaxes->getudfvalue("u_dueamount"),$obju_LGUBills->getudfvalue("u_settledamount"),2));
                                                    if ($obju_Rptaxes->getudfvalue("u_dueamount")>0) {
                                                            $obju_Rptaxes->docstatus = "O";
                                                    }else{
                                                            $obju_Rptaxes->docstatus = "C";
                                                    }
                                                            $actionReturn = $obju_Rptaxes->update($obju_Rptaxes->docno,$obju_Rptaxes->rcdversion);
                                                }
                                                            $obju_LGUBills->docstatus = "C";
                                                            $obju_LGUBills->setudfvalue("u_paiddate",$objTable->getudfvalue("u_docdate"));
                                            }
                                                    $obju_LGUBills->setudfvalue("u_settledamount",$obju_LGUBills->getudfvalue("u_settledamount")+$objRs2->fields["U_PAIDAMOUNT"]);
                                                    $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                                    }
                                }
                                if ($actionReturn && $obju_LGUBills->getudfvalue("u_module")=="Business Permit") {
                                       if ($actionReturn && $obju_LGUBills->getudfvalue("u_appno")!="") {
                                                if ($obju_BPLApps->getbykey($obju_LGUBills->getudfvalue("u_appno"))) {
                                                        if ($obju_LGUBills->getudfvalue("u_preassbill")==1) {
                                                                $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate='".$objTable->getudfvalue("u_date")."', u_payrefno='".$objTable->docno."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);
                                                                if ($actionReturn) $actionReturn = $obju_BPLApps->executesql("update u_bplappreq2s set u_issdate='".$objTable->getudfvalue("u_date")."', u_payrefno='".$objTable->docno."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$obju_BPLApps->docid."' and u_amount>0",false);
                                                        } elseif ($obju_LGUBills->docstatus=="C") {
                                                                $obju_BPLApps->setudfvalue("u_paycount",$obju_BPLApps->getudfvalue("u_paycount")+1);
                                                                if ($obju_BPLApps->docstatus=="Approved") {
                                                                        $obju_BPLApps->docstatus="Paid";
                                                                }
                                                        }
                                                        $actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
                                                }// else return raiseError("Unable to find Application No.[".$obju_LGUBills->getudfvalue("u_appno")."].");	
                                        }
                                }
                            
                        }
                        
                        //} else return raiseError("Payment amount [".$objTable->getudfvalue("u_paidamount")."] is not equal to bill due amount [".$obju_LGUBills->getudfvalue("u_dueamount")."].");
                } else return raiseError("Unable to retrieve Bill No. [".$objRs->fields["u_billno"]."].");
            
        }
      
	return $actionReturn;
}

function onCustomEventPostRPTLedgerdocumentschema_brGPSLGU($objTable,$delete=false) {
	global $objConnection;
        global $page;
	$actionReturn = true;
        
	$obju_RPPaymentHistory = new documentschema_br(null,$objConnection,"u_paymenthistory");
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("SELECT
                            1 AS qid,
                            D.u_pinno
                            ,D.u_docno as u_refno
                            ,E.U_ARPNO AS U_TDNO
                            ,A.docno as CurrentOR
                            ,C.U_DECLAREDOWNER as u_declaredowner
                            ,A.u_custname as PaidBy
                            ,A.u_date as CurrentDate
                            ,A.u_paidamount as Paidamount
                            ,E.u_barangay as barangay
                            ,if(E.u_kind = 'LAND','L',if(E.u_kind = 'BUILDING','B','M')) as Type
                            ,E.u_assvalue
                            ,IF(d.u_yrfr = d.u_yrto,d.u_yrfr,concat(d.u_yrfr,'-',d.u_yrto)) as taxyear
                            ,d.u_yrfr
                            ,d.u_yrto
                            ,D.u_taxdue as BasicTax
                            ,D.u_sef  as SEF
                            ,D.u_penalty as BasicPenalty
                            ,D.u_sefpenalty as SEFPenalty
                            ,D.U_TAXDISC as BasicDiscount
                            ,D.U_SEFDISC  as SEFDiscount
                            ,D.U_LINETOTAL
                            ,D.u_paymode
                            ,IF(D.U_PAYMODE = 'A',1,D.U_PAYQTR) AS PAYQTRFROM
                            ,D.U_PAYQTR AS PAYQTRTO
                            ,a.CreatedBy
                            ,U.USERNAME
                            ,if(E.u_kind = 'LAND',R1.U_CLASS,if(E.u_kind = 'BUILDING',R2.U_CLASS,R3.U_CLASS)) as U_CLASS
                            from U_LGUPOS A
                            INNER JOIN USERS U ON A.CREATEDBY = U.USERID
                            INNER join U_LGUBILLS B on A.u_billno = B.docno and A.company = B.company and A.branch = B.branch
                            INNER join U_RPTAXBILL C on B.u_appno = C.docno AND B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH
                            INNER JOIN U_RPTAXBILLARPS D ON D.COMPANY = C.COMPANY AND D.BRANCH = C.BRANCH AND D.DOCID = C.DOCID AND D.U_SELECTED = 1
                            INNER JOIN U_RPTAXBILLPINS E ON E.COMPANY = C.COMPANY AND E.BRANCH = C.BRANCH AND E.DOCID = C.DOCID AND D.U_DOCNO = E.U_DOCNO AND E.U_SELECTED = 1
                            LEFT JOIN U_RPFAAS1 R1 ON D.U_DOCNO = R1.DOCNO AND D.COMPANY = R1.COMPANY AND D.BRANCH = R1.BRANCH
                            LEFT JOIN U_RPFAAS2 R2 ON D.U_DOCNO = R2.DOCNO AND D.COMPANY = R2.COMPANY AND D.BRANCH = R2.BRANCH
                            LEFT JOIN U_RPFAAS3 R3 ON D.U_DOCNO = R3.DOCNO AND D.COMPANY = R3.COMPANY AND D.BRANCH = R3.BRANCH
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."'
                            UNION ALL
                            SELECT
                            2 AS qid,
                            D.u_pinno
                            ,D.u_docno as u_refno
                            ,CONCAT(E.U_ARPNO,'(EPSF)') AS U_TDNO
                            ,A.docno as CurrentOR
                            ,C.U_DECLAREDOWNER as u_declaredowner
                            ,A.u_custname as PaidBy
                            ,A.u_date as CurrentDate
                            ,A.u_paidamount as Paidamount
                            ,concat(E.u_barangay,'(EPSF)') as barangay
                            ,if(E.u_kind = 'LAND','L',if(E.u_kind = 'BUILDING','B','M')) as Type
                            ,E.u_assvalue
                            ,IF(min(d.u_yrfr) = max(d.u_yrto),min(d.u_yrfr),concat(min(d.u_yrfr),'-',max(d.u_yrto))) as taxyear
                            ,MIN(d.u_yrfr) as u_yrfr
                            ,MAX(d.u_yrto) as u_yrto
                            ,SUM(D.U_EPSF) as BasicTax
                            ,0 as SEF
                            ,0 as BasicPenalty
                            ,0 as SEFPenalty
                            ,0 as BasicDiscount
                            ,0 as SEFDiscount
                            ,SUM(D.U_EPSF) as U_LINETOTAL
                            ,D.u_paymode
                            ,IF(D.U_PAYMODE = 'A',1,MIN(D.U_PAYQTR)) AS PAYQTRFROM
                            ,MAX(D.U_PAYQTR) AS PAYQTRFROM
                            ,a.CreatedBy
                            ,U.USERNAME
                            ,if(E.u_kind = 'LAND',R1.U_CLASS,if(E.u_kind = 'BUILDING',R2.U_CLASS,R3.U_CLASS)) as U_CLASS
                            from U_LGUPOS A
                            INNER JOIN USERS U ON A.CREATEDBY = U.USERID
                            INNER join U_LGUBILLS B on A.u_billno = B.docno and A.company = B.company and A.branch = B.branch
                            INNER join U_RPTAXBILL C on B.u_appno = C.docno AND B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH
                            INNER JOIN U_RPTAXBILLARPS D ON D.COMPANY = C.COMPANY AND D.BRANCH = C.BRANCH AND D.DOCID = C.DOCID AND D.U_SELECTED = 1 AND U_EPSF > 0
                            INNER JOIN U_RPTAXBILLPINS E ON E.COMPANY = C.COMPANY AND E.BRANCH = C.BRANCH AND E.DOCID = C.DOCID AND D.U_DOCNO = E.U_DOCNO AND E.U_SELECTED = 1
                            LEFT JOIN U_RPFAAS1 R1 ON D.U_DOCNO = R1.DOCNO AND D.COMPANY = R1.COMPANY AND D.BRANCH = R1.BRANCH
                            LEFT JOIN U_RPFAAS2 R2 ON D.U_DOCNO = R2.DOCNO AND D.COMPANY = R2.COMPANY AND D.BRANCH = R2.BRANCH
                            LEFT JOIN U_RPFAAS3 R3 ON D.U_DOCNO = R3.DOCNO AND D.COMPANY = R3.COMPANY AND D.BRANCH = R3.BRANCH
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."'
                            GROUP BY D.U_DOCNO
                            UNION ALL
                            SELECT
                            3 AS qid,
                            D.u_pinno
                            ,D.u_docno as u_refno
                            ,CONCAT(E.U_ARPNO,'(SHT)') AS U_TDNO
                            ,A.docno as CurrentOR
                            ,C.U_DECLAREDOWNER as u_declaredowner
                            ,A.u_custname as PaidBy
                            ,A.u_date as CurrentDate
                            ,A.u_paidamount as Paidamount
                            ,concat(E.u_barangay,'(SHT)') as barangay
                            ,if(E.u_kind = 'LAND','L',if(E.u_kind = 'BUILDING','B','M')) as Type
                            ,E.u_assvalue
                            ,IF(min(d.u_yrfr) = max(d.u_yrto),min(d.u_yrfr),concat(min(d.u_yrfr),'-',max(d.u_yrto))) as taxyear
                            ,MIN(d.u_yrfr) as u_yrfr
                            ,MAX(d.u_yrto) as u_yrto
                            ,SUM(D.U_SHT) as BasicTax
                            ,0 as SEF
                            ,0 as BasicPenalty
                            ,0 as SEFPenalty
                            ,0 as BasicDiscount
                            ,0 as SEFDiscount
                            ,SUM(D.U_SHT) as U_LINETOTAL
                            ,D.u_paymode
                            ,IF(D.U_PAYMODE = 'A',1,MIN(D.U_PAYQTR)) AS PAYQTRFROM
                            ,MAX(D.U_PAYQTR) AS PAYQTRFROM
                            ,a.CreatedBy
                            ,U.USERNAME
                            ,if(E.u_kind = 'LAND',R1.U_CLASS,if(E.u_kind = 'BUILDING',R2.U_CLASS,R3.U_CLASS)) as U_CLASS
                            from U_LGUPOS A
                            INNER JOIN USERS U ON A.CREATEDBY = U.USERID
                            INNER JOIN U_LGUBILLS B on A.u_billno = B.docno and A.company = B.company and A.branch = B.branch
                            INNER JOIN U_RPTAXBILL C on B.u_appno = C.docno AND B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH
                            INNER JOIN U_RPTAXBILLARPS D ON D.COMPANY = C.COMPANY AND D.BRANCH = C.BRANCH AND D.DOCID = C.DOCID AND D.U_SELECTED = 1 AND U_SHT > 0
                            INNER JOIN U_RPTAXBILLPINS E ON E.COMPANY = C.COMPANY AND E.BRANCH = C.BRANCH AND E.DOCID = C.DOCID AND D.U_DOCNO = E.U_DOCNO AND E.U_SELECTED = 1
                            LEFT JOIN U_RPFAAS1 R1 ON D.U_DOCNO = R1.DOCNO AND D.COMPANY = R1.COMPANY AND D.BRANCH = R1.BRANCH
                            LEFT JOIN U_RPFAAS2 R2 ON D.U_DOCNO = R2.DOCNO AND D.COMPANY = R2.COMPANY AND D.BRANCH = R2.BRANCH
                            LEFT JOIN U_RPFAAS3 R3 ON D.U_DOCNO = R3.DOCNO AND D.COMPANY = R3.COMPANY AND D.BRANCH = R3.BRANCH
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."'
                            GROUP BY D.U_DOCNO
                            UNION ALL
                            SELECT
                            4 AS qid,
                            E.u_pin AS U_PINNO
                            ,D1.u_docno AS u_refno
                            ,CONCAT(D1.U_TDNO,'(TAX CREDIT)') AS U_TDNO
                            ,A.docno as CurrentOR
                            ,C.U_DECLAREDOWNER as u_declaredowner
                            ,A.u_custname as PaidBy
                            ,A.u_date as CurrentDate
                            ,A.u_paidamount as Paidamount
                            ,E.u_barangay as barangay
                            ,if(E.u_kind = 'LAND','L',if(E.u_kind = 'BUILDING','B','M')) as Type
                            ,E.u_assvalue
                            ,IF(min(d1.U_YEAR) = max(d1.U_YEAR),min(d1.U_YEAR),concat(min(d1.U_YEAR),'-',max(d1.U_YEAR))) as taxyear
                            ,MIN(D1.U_YEAR) AS u_yrfr
                            ,MAX(D1.U_YEAR) AS u_yrto
                            ,SUM(D1.u_taxdue) * -1 as BasicTax
                            ,SUM(D1.u_sef) * -1  as SEF
                            ,SUM(D1.u_penalty) * -1 as BasicPenalty
                            ,SUM(D1.u_sefpenalty) * -1 as SEFPenalty
                            ,0 as BasicDiscount
                            ,0  as SEFDiscount
                            ,SUM(D1.u_taxdue + D1.u_sef + D1.u_penalty + D1.u_sefpenalty) * -1 as U_LINETOTAL
                            ,'A' AS U_PAYMODE
                            ,'1' AS PAYQTRFROM
                            ,'4' AS PAYQTRTO
                            ,a.CreatedBy
                            ,U.USERNAME
                            ,if(E.u_kind = 'LAND',R1.U_CLASS,if(E.u_kind = 'BUILDING',R2.U_CLASS,R3.U_CLASS)) as U_CLASS
                            from U_LGUPOS A
                            INNER JOIN USERS U ON A.CREATEDBY = U.USERID
                            INNER join U_LGUBILLS B on A.u_billno = B.docno and A.company = B.company and A.branch = B.branch
                            INNER join U_RPTAXBILL C on B.u_appno = C.docno AND B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH
                            INNER JOIN U_RPTAXBILLCREDITS D1 ON D1.COMPANY = C.COMPANY AND D1.BRANCH = C.BRANCH AND D1.DOCID = C.DOCID AND D1.U_SELECTED = 1
                            INNER JOIN U_RPTAXBILLPINS E ON E.COMPANY = C.COMPANY AND E.BRANCH = C.BRANCH AND E.DOCID = C.DOCID AND D1.U_DOCNO = E.U_DOCNO AND E.U_SELECTED = 1
                            LEFT JOIN U_RPFAAS1 R1 ON D1.U_DOCNO = R1.DOCNO AND D1.COMPANY = R1.COMPANY AND D1.BRANCH = R1.BRANCH
                            LEFT JOIN U_RPFAAS2 R2 ON D1.U_DOCNO = R2.DOCNO AND D1.COMPANY = R2.COMPANY AND D1.BRANCH = R2.BRANCH
                            LEFT JOIN U_RPFAAS3 R3 ON D1.U_DOCNO = R3.DOCNO AND D1.COMPANY = R3.COMPANY AND D1.BRANCH = R3.BRANCH
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."'
                            GROUP BY D1.U_DOCNO
                            UNION ALL
                            SELECT
                            5 AS qid,
                            E.u_pin
                            ,D1.u_docno as u_refno
                            ,CONCAT(D1.U_TDNO,'(EPSF TAX CREDIT)') AS U_TDNO
                            ,A.docno as CurrentOR
                            ,C.U_DECLAREDOWNER as u_declaredowner
                            ,A.u_custname as PaidBy
                            ,A.u_date as CurrentDate
                            ,A.u_paidamount as Paidamount
                            ,concat(E.u_barangay,'(EPSF TAX CREDIT)') as barangay
                            ,if(E.u_kind = 'LAND','L',if(E.u_kind = 'BUILDING','B','M')) as Type
                            ,E.u_assvalue
                            ,IF(min(d1.U_YEAR) = max(d1.U_YEAR),min(d1.U_YEAR),concat(min(d1.U_YEAR),'-',max(d1.U_YEAR))) as taxyear
                            ,MIN(D1.U_YEAR) AS u_yrfr
                            ,MAX(D1.U_YEAR) AS u_yrto
                            ,SUM(D1.U_EPSF) * -1 as BasicTax
                            ,0 as SEF
                            ,0 as BasicPenalty
                            ,0 as SEFPenalty
                            ,0 as BasicDiscount
                            ,0 as SEFDiscount
                            ,SUM(D1.U_EPSF) * -1  as U_LINETOTAL
                            ,'A' AS u_paymode
                            ,'1' AS PAYQTRFROM
                            ,'4' AS PAYQTRTO
                            ,a.CreatedBy
                            ,U.USERNAME
                            ,if(E.u_kind = 'LAND',R1.U_CLASS,if(E.u_kind = 'BUILDING',R2.U_CLASS,R3.U_CLASS)) as U_CLASS
                            from U_LGUPOS A
                            INNER JOIN USERS U ON A.CREATEDBY = U.USERID
                            INNER JOIN U_LGUBILLS B on A.u_billno = B.docno and A.company = B.company and A.branch = B.branch
                            INNER JOIN U_RPTAXBILL C on B.u_appno = C.docno AND B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH
                            INNER JOIN U_RPTAXBILLCREDITS D1 ON D1.COMPANY = C.COMPANY AND D1.BRANCH = C.BRANCH AND D1.DOCID = C.DOCID AND D1.U_SELECTED = 1 AND D1.U_EPSF > 0
                            INNER JOIN U_RPTAXBILLPINS E ON E.COMPANY = C.COMPANY AND E.BRANCH = C.BRANCH AND E.DOCID = C.DOCID AND D1.U_DOCNO = E.U_DOCNO AND E.U_SELECTED = 1
                            LEFT JOIN U_RPFAAS1 R1 ON D1.U_DOCNO = R1.DOCNO AND D1.COMPANY = R1.COMPANY AND D1.BRANCH = R1.BRANCH
                            LEFT JOIN U_RPFAAS2 R2 ON D1.U_DOCNO = R2.DOCNO AND D1.COMPANY = R2.COMPANY AND D1.BRANCH = R2.BRANCH
                            LEFT JOIN U_RPFAAS3 R3 ON D1.U_DOCNO = R3.DOCNO AND D1.COMPANY = R3.COMPANY AND D1.BRANCH = R3.BRANCH
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."'
                            GROUP BY D1.U_DOCNO ");
            while ($objRs->queryfetchrow("NAME")) {
                        $obju_RPPaymentHistory->prepareadd();
                        $obju_RPPaymentHistory->docno = getNextNoByBranch("u_paymenthistory","",$objConnection);
                        $obju_RPPaymentHistory->docid = getNextIdByBranch("u_paymenthistory",$objConnection);
                        $obju_RPPaymentHistory->setudfvalue("u_refno",$objRs->fields["u_refno"]);
                        $obju_RPPaymentHistory->setudfvalue("u_tdno",$objRs->fields["U_TDNO"]);
                        $obju_RPPaymentHistory->setudfvalue("u_payyear",$objRs->fields["taxyear"]);
                        $obju_RPPaymentHistory->setudfvalue("u_orno",$objRs->fields["CurrentOR"]);
                        $obju_RPPaymentHistory->setudfvalue("u_paiddate",$objRs->fields["CurrentDate"]);
                        $obju_RPPaymentHistory->setudfvalue("u_startyear",$objRs->fields["u_yrfr"]);
                        $obju_RPPaymentHistory->setudfvalue("u_startqtr",$objRs->fields["PAYQTRFROM"]);
                        $obju_RPPaymentHistory->setudfvalue("u_endyear",$objRs->fields["u_yrto"]);
                        $obju_RPPaymentHistory->setudfvalue("u_endqtr",$objRs->fields["PAYQTRTO"]);
                        $obju_RPPaymentHistory->setudfvalue("u_assvalue",$objRs->fields["u_assvalue"]);
                        $obju_RPPaymentHistory->setudfvalue("u_basic",$objRs->fields["BasicTax"]);
                        $obju_RPPaymentHistory->setudfvalue("u_basicdisc",$objRs->fields["BasicDiscount"]);
                        $obju_RPPaymentHistory->setudfvalue("u_basicpen",$objRs->fields["BasicPenalty"]);
                        $obju_RPPaymentHistory->setudfvalue("u_sef",$objRs->fields["SEF"]);
                        $obju_RPPaymentHistory->setudfvalue("u_sefdisc",$objRs->fields["SEFDiscount"]);
                        $obju_RPPaymentHistory->setudfvalue("u_sefpen",$objRs->fields["SEFPenalty"]);
                        $obju_RPPaymentHistory->setudfvalue("u_totalamount",$objRs->fields["U_LINETOTAL"]);
                        $obju_RPPaymentHistory->setudfvalue("u_userid",$objRs->fields["CreatedBy"]);
                        $obju_RPPaymentHistory->setudfvalue("u_class",$objRs->fields["U_CLASS"]);
                        $obju_RPPaymentHistory->setudfvalue("u_paidby",$objRs->fields["PaidBy"]);
                        $obju_RPPaymentHistory->setudfvalue("u_issuedby",$objRs->fields["USERNAME"]);
                        $actionReturn = $obju_RPPaymentHistory->add();
                        if (!$actionReturn) break;
            }
      
	return $actionReturn;
}

function onCustomEventPostBusinessPermitLedgerdocumentschema_brGPSLGU($objTable) {
	global $objConnection;
        global $page;
	$actionReturn = true;
        
	$obju_BusinessLedger = new documentschema_br(null,$objConnection,"u_bplledger");
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("SELECT B.U_BASEDOCNO,B.U_BASELINEID,A.CREATEDBY,D.U_DOCDATE,D.U_DUEDATE,F.U_YEAR,F.DOCNO,A.DOCNO as U_ORNO, A.U_DATE,E.U_BUSINESSLINE,B.U_ITEMCODE,E.U_TAXBASE,IF(E.U_PENALTY = 0,E.U_AMOUNT,0) AS U_AMOUNTDUE,B.U_LINETOTAL AS U_AMOUNTPAID ,IF(E.U_PENALTY = 1,B.U_LINETOTAL,0)AS U_PENALTYAMT,D.U_PAYMODE,D.U_PAYQTR,F.U_APPNO,B.U_ITEMDESC,E.U_SEQNO FROM U_LGUPOS A
                            INNER JOIN U_LGUPOSITEMS B ON A.DOCID = B.DOCID AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH  
                            INNER JOIN U_LGUPOSBILLNOS C ON A.DOCID = C.DOCID AND A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH
                            INNER JOIN U_LGUBILLS D ON C.U_BILLNO = D.DOCNO AND C.COMPANY = B.COMPANY AND C.BRANCH = D.BRANCH
                            INNER JOIN U_LGUBILLITEMS E ON D.DOCID = E.DOCID AND D.COMPANY = E.COMPANY AND D.BRANCH = E.BRANCH AND B.U_BASELINEID = E.LINEID 
                            LEFT JOIN U_BPLAPPS F ON D.U_APPNO = F.DOCNO AND D.COMPANY = F.COMPANY AND D.BRANCH = F.BRANCH
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."' AND B.U_PENALTY <> 1 AND  E.U_PENALTY <> 1
                            ");
            while ($objRs->queryfetchrow("NAME")) {
                        $obju_BusinessLedger->prepareadd();
                        $obju_BusinessLedger->docno = getNextNoByBranch("u_bplledger","",$objConnection);
                        $obju_BusinessLedger->docid = getNextIdByBranch("u_bplledger",$objConnection);
                        $obju_BusinessLedger->setudfvalue("u_acctno",$objRs->fields["U_APPNO"]);
                        $obju_BusinessLedger->setudfvalue("u_userid",$objRs->fields["CREATEDBY"]);
                        $obju_BusinessLedger->setudfvalue("u_assdate",$objRs->fields["U_DOCDATE"]);
                        $obju_BusinessLedger->setudfvalue("u_duedate",$objRs->fields["U_DUEDATE"]);
                        $obju_BusinessLedger->setudfvalue("u_payyear",$objRs->fields["U_YEAR"]);
                        $obju_BusinessLedger->setudfvalue("u_revisionyear","2008");
                        $obju_BusinessLedger->setudfvalue("u_businessid",$objRs->fields["DOCNO"]);
                        $obju_BusinessLedger->setudfvalue("u_ordate",$objRs->fields["U_DATE"]);
                        $obju_BusinessLedger->setudfvalue("u_orno",$objRs->fields["U_ORNO"]);
                        $obju_BusinessLedger->setudfvalue("u_businesslineid",$objRs->fields["U_BUSINESSLINE"]);
                        $obju_BusinessLedger->setudfvalue("u_feeid",$objRs->fields["U_ITEMCODE"]);
                        $obju_BusinessLedger->setudfvalue("u_feedesc",$objRs->fields["U_ITEMDESC"]);
                        $obju_BusinessLedger->setudfvalue("u_taxbase",$objRs->fields["U_TAXBASE"]);
                        $obju_BusinessLedger->setudfvalue("u_amountdue",$objRs->fields["U_AMOUNTDUE"]);
                        
                        $penaltyamt = 0;
                        $objRs_LguFees = new recordset(null,$objConnection);
                        $objRs_LguFees->queryopen("SELECT u_penaltycode FROM u_lgufees where code = '".$objRs->fields["U_ITEMCODE"]."'");
                        if ($objRs_LguFees->queryfetchrow("NAME")) {
                                $objRs_Penalties = new recordset(null,$objConnection);
                                $objRs_Penalties->queryopen("SELECT E.U_BUSINESSLINE,B.U_LINETOTAL,B.U_ITEMCODE,B.U_ITEMDESC,B.U_BASELINEID
                                                             FROM U_LGUPOS A
                                                                    INNER JOIN U_LGUPOSITEMS B ON A.DOCID = B.DOCID AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH
                                                                    INNER JOIN U_LGUPOSBILLNOS C ON A.DOCID = C.DOCID AND A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH
                                                                    INNER JOIN U_LGUBILLS D ON C.U_BILLNO = D.DOCNO AND C.COMPANY = B.COMPANY AND C.BRANCH = D.BRANCH
                                                                    INNER JOIN U_LGUBILLITEMS E ON D.DOCID = E.DOCID AND D.COMPANY = E.COMPANY AND D.BRANCH = E.BRANCH AND B.U_BASELINEID = E.LINEID AND E.U_PENALTY = 1
                                                                    LEFT JOIN U_BPLAPPS F ON D.U_APPNO = F.DOCNO AND D.COMPANY = F.COMPANY AND D.BRANCH = F.BRANCH
                                                                    WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."' AND B.U_ITEMCODE = '".$objRs_LguFees->fields["u_penaltycode"]."' AND B.U_BUSINESSLINE = '".$objRs->fields["U_BUSINESSLINE"]."' and  B.U_BASEDOCNO = '".$objRs->fields["U_BASEDOCNO"]."'
                                                            UNION ALL
                                                            SELECT B.U_BUSINESSLINE,B.U_LINETOTAL,B.U_ITEMCODE,B.U_ITEMDESC,B.U_BASELINEID 
                                                            FROM U_LGUPOS A
                                                                    INNER JOIN U_LGUPOSITEMS B ON A.DOCID = B.DOCID AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH
                                                                    WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."' AND B.U_PENALTY = 1 AND B.U_ITEMCODE = '".$objRs_LguFees->fields["u_penaltycode"]."' AND B.U_BUSINESSLINE = '".$objRs->fields["U_BUSINESSLINE"]."' AND B.U_BASELINEID = '".$objRs->fields["U_BASELINEID"]."' ");
                                                            if ($objRs_Penalties->queryfetchrow("NAME")) {
                                                                    $penaltyamt = $objRs_Penalties->fields["U_LINETOTAL"];
                                                                    if (!$actionReturn) break;
                                                            }
                            if (!$actionReturn) break;
                        }
                        $obju_BusinessLedger->setudfvalue("u_surcharge",$penaltyamt);
                        $obju_BusinessLedger->setudfvalue("u_amountpaid",$objRs->fields["U_AMOUNTPAID"] + $penaltyamt);
                        $obju_BusinessLedger->setudfvalue("u_quarter",$objRs->fields["U_PAYQTR"]);
                        $obju_BusinessLedger->setudfvalue("u_paymode",$objRs->fields["U_PAYMODE"]);
                        $obju_BusinessLedger->setudfvalue("u_rownumber",$objRs->fields["U_SEQNO"]);
                        $actionReturn = $obju_BusinessLedger->add();
                        if (!$actionReturn) break;
            }
      
	return $actionReturn;
}


function onCustomEventPostAcctgSharingdocumentschema_brGPSLGU($objTable) {
    
    global $objConnection;
    global $page;
    $actionReturn = true;
        
    $obju_LGUPosAcct = new documentschema_br(null,$objConnection,"u_lguposacctgsharing");
    $objRs = new recordset(null,$objConnection);
   // $objRs->queryopen("select a.U_MOBILENO, a.U_TAGNO, b.NAME as U_GROUPNAME from u_lguquetags a inner join u_lguquegroups b on b.code=a.u_group where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_group='".$obju_LGUPOSTerminals->getudfvalue("u_quegroup")."' and a.u_date='".currentdateDB()."' and a.u_tagno>".$obju_LGUQue->getudfvalue("u_ref")." and (mod(a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref").",b.u_notifyevery)=0 or a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref")."=b.u_notifyon or a.u_tagno-1=b.u_notifyon-2)");
    $objRs->queryopen("SELECT A.U_STATUS,A.CREATEDBY,C.LINEID,A.U_DATE,A.DOCNO,C.U_ITEMCODE,C.U_ITEMDESC,SUM(C.U_LINETOTAL) AS U_LINETOTAL,B.U_BSHARE,B.U_MSHARE,B.U_PSHARE,B.U_NSHARE,B.U_BGLCODE,B.U_MGLCODE,B.U_PGLCODE,B.U_NGLCODE 
                        FROM U_LGUPOS A
                        INNER JOIN U_LGUPOSITEMS C ON A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH AND A.DOCID = C.DOCID AND C.U_LINETOTAL > 0
                        INNER JOIN U_LGUFEES B ON C.U_ITEMCODE = B.CODE
                        WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCNO = '".$objTable->docno."' GROUP BY C.U_ITEMCODE");
        while ($objRs->queryfetchrow("NAME")) {
                $linetotal = 0 ;
                if($actionReturn){
                    if($objRs->fields["U_PSHARE"] > 0 && $objRs->fields["U_PGLCODE"] != ""){
                        if($objRs->fields["U_PGLCODE"] == "") return raiseError("Provincal Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                        $obju_LGUPosAcct->prepareadd();
                        $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                        $obju_LGUPosAcct->docno = "P-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                        $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                        $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                        $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                        $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_PGLCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_sharetype","P");
                        $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                        $obju_LGUPosAcct->setudfvalue("u_amount",($objRs->fields["U_PSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                        $linetotal = $linetotal + (($objRs->fields["U_PSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                        $actionReturn = $obju_LGUPosAcct->add();
                        if (!$actionReturn) break;
                    }
                }
                if($actionReturn){
                    if($objRs->fields["U_NSHARE"] > 0 && $objRs->fields["U_NGLCODE"] != ""){
                        if($objRs->fields["U_NGLCODE"] == "") return raiseError("National Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                        $obju_LGUPosAcct->prepareadd();
                        $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                        $obju_LGUPosAcct->docno = "N-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                        $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                        $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                        $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                        $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_NGLCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_sharetype","N");
                        $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                        $obju_LGUPosAcct->setudfvalue("u_amount",($objRs->fields["U_NSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                        $linetotal = $linetotal + (($objRs->fields["U_NSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                        $actionReturn = $obju_LGUPosAcct->add();
                        if (!$actionReturn) break;
                    }
                }
                if($actionReturn){
                    if($objRs->fields["U_BSHARE"] > 0 && $objRs->fields["U_BGLCODE"] != ""){
                        if($objRs->fields["U_BGLCODE"] == "") return raiseError("Barangay Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                        $obju_LGUPosAcct->prepareadd();
                        $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                        $obju_LGUPosAcct->docno = "B-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                        $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                        $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                        $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                        $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_BGLCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_sharetype","B");
                        $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                        $obju_LGUPosAcct->setudfvalue("u_amount",($objRs->fields["U_BSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                        $linetotal = $linetotal + (($objRs->fields["U_BSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                        $actionReturn = $obju_LGUPosAcct->add();
                        if (!$actionReturn) break;
                    }
                }
                if($actionReturn){
                    if($objRs->fields["U_MSHARE"] > 0 && $objRs->fields["U_MGLCODE"] != ""){
                        if($objRs->fields["U_MGLCODE"] == "") return raiseError("Municipal Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                        $obju_LGUPosAcct->prepareadd();
                        $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                        $obju_LGUPosAcct->docno = "M-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                        $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                        $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                        $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                        $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                        $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_MGLCODE"]);
                        $obju_LGUPosAcct->setudfvalue("u_sharetype","M");
                        $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                        $obju_LGUPosAcct->setudfvalue("u_amount",$objRs->fields["U_LINETOTAL"] - $linetotal);
                        $actionReturn = $obju_LGUPosAcct->add();
                        if (!$actionReturn) break;
                    }
                }
        }
    return $actionReturn;
}

?>