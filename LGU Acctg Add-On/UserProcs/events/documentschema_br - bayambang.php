<?php
 include_once("./classes/masterdataschema_br.php"); 
 include_once("./classes/documentlinesschema_br.php"); 
	include_once("./utils/journalentries.php");


function onBeforeAddEventdocumentschema_brGPSLGUAcctg($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgucashrs":
			if ($objTable->docstatus=="O") {
				if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
					$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
					if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
						$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
						$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
						$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
						
						
						$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
						$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
						$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
						$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
						$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
						$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
						$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
						$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
										
						$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
						$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
	
						$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
					}			
				}
				
				if ($actionReturn) {
					$objTable->docseries = -1;
					$objTable->docno = $objTable->getudfvalue("u_jevno");
					$actionReturn = onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg($objTable);
				}	
				
			}				
			break;
		case "u_lgubankdps":
			if ($objTable->docstatus=="O") {
				if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
					$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
					if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
						$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
						$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
						$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
						
						
						$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
						$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
						$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
						$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
						$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
						$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
						$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
						$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
										
						$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
						$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
	
						$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
					}			
				}
				if ($actionReturn && $objTable->getudfvalue("u_jevauto")!= -1) {
					$objTable->docseries = -1;
					$objTable->docno = $objTable->getudfvalue("u_jevno");
					$actionReturn = onCustomeventPostBankDepositdocumentschema_brGPSLGUAcctg($objTable);
				}
			}				
			break;
		case "u_lgucashds":
			if ($actionReturn) {
				if ($objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucashdosnos");
					if ($actionReturn) $actionReturn = onCustomeventPostCashDisbursementdocumentschema_brGPSLGUAcctg($objTable);
					
				}
			}
			break;
		case "u_lgucheckds":
			if ($actionReturn) {
				if ($objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucheckdosnos");
					if ($actionReturn) $actionReturn = onCustomeventPostCheckDisbursementdocumentschema_brGPSLGUAcctg($objTable);
					
				}
			}
			break;
		case "u_lgufunds":
			if ($actionReturn) {
				if ($objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgufundosnos");
					if ($actionReturn) $actionReturn = onCustomeventPostFundVoucherdocumentschema_brGPSLGUAcctg($objTable);
					
				}
			}
			break;
		case "u_lguaps":
			if ($actionReturn) {
				if ($objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventPostAPdocumentschema_brGPSLGUAcctg($objTable);
					
				}
			}
			break;
		case "u_lgujvs":
			$objTable->setudfvalue("u_jevno",$objTable->docno);
			if ($actionReturn) {
				if ($objTable->docstatus=="O") {
					if ($actionReturn) $actionReturn = onCustomeventPostJVdocumentschema_brGPSLGUAcctg($objTable);
				}
			}
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSLGUAcctg()");
	return $actionReturn;
}


function onAddEventdocumentschema_brGPSLGUAcctg($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lguobligationslips":
			//$actionReturn = onCustomeventCheckOBRBudgetdocumentschema_brGPSLGUAcctg($objTable);
			break;
	}		
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSLGUAcctg($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lguppmp":
			if ($objTable->getudfvalue("u_decisionremarks")!="") {
				$objTable->setudfvalue("u_history",currentdate()." ".currenttime()." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_decisionremarks")."\r\n\r\n".$objTable->getudfvalue("u_history"));
				$objTable->setudfvalue("u_decisionremarks","");
			}
			if ($objTable->docstatus!=$objTable->fields["DOCSTATUS"] && $objTable->docstatus=="Approved") {
				$actionReturn = onCustomeventPostBudgetdocumentschema_brGPSLGUAcctg($objTable);
			} 
			break;
		case "u_lgucashrs":
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) {
						$objTable->docseries = -1;
						$objTable->docno = $objTable->getudfvalue("u_jevno");
						$actionReturn = onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg($objTable);
					}	
					
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn) $actionReturn = onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
					if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
						$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
						while ($objJvDtl->queryfetchrow()) {
							if ($objJvDtl->getudfvalue("itemtype")!="A") {
								if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
									return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
								}
							}
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->delete();
							if (!$actionReturn) return false;
						}					
						if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
						if ($actionReturn) $actionReturn = $objJvHdr->delete();
					} else {
						return raiseError("Unable to find Station JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
					}				
				}
			}
			break;
		case "u_lgubankdps":
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn && $objTable->getudfvalue("u_jevauto")!= -1) {
						$objTable->docseries = -1;
						$objTable->docno = $objTable->getudfvalue("u_jevno");
						$actionReturn = onCustomeventPostBankDepositdocumentschema_brGPSLGUAcctg($objTable);
					}			
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn && $objTable->getudfvalue("u_jevauto")!= -1) $actionReturn = onCustomeventPostBankDepositdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					if ($objTable->getudfvalue("u_jevauto")!= -1) {
						$objJvHdr = new journalvouchers(null,$objConnection);
						$objJvDtl = new journalvoucheritems(null,$objConnection);
						if ($objTable->getudfvalue("u_jevno")!="") {
							if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
								$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
								while ($objJvDtl->queryfetchrow()) {
									if ($objJvDtl->getudfvalue("itemtype")!="A") {
										if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
											return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
										}
									}
									$objJvDtl->privatedata["header"] = $objJvHdr;
									$actionReturn = $objJvDtl->delete();
									if (!$actionReturn) return false;
								}					
								if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
								if ($actionReturn) $actionReturn = $objJvHdr->delete();
							} else {
								return raiseError("Unable to find JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
							}
						}	
					}				
						
				}
			}
			break;
		case "u_lgucashds":
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucashdosnos");
					if ($actionReturn) $actionReturn = onCustomeventPostCashDisbursementdocumentschema_brGPSLGUAcctg($objTable);
					
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucashdosnos",true);
					if ($actionReturn) $actionReturn = onCustomeventPostCashDisbursementdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
					if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
						$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
						while ($objJvDtl->queryfetchrow()) {
							if ($objJvDtl->getudfvalue("itemtype")!="A") {
								if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
									return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
								}
							}
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->delete();
							if (!$actionReturn) return false;
						}					
						if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
						if ($actionReturn) $actionReturn = $objJvHdr->delete();
					} else {
						return raiseError("Unable to find Station JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
					}				
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucashdosnos",true);
				}
			}
			break;
		case "u_lgucheckds":
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucheckdosnos");
					if ($actionReturn) $actionReturn = onCustomeventPostCheckDisbursementdocumentschema_brGPSLGUAcctg($objTable);
					
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucheckdosnos",true);
					if ($actionReturn) $actionReturn = onCustomeventPostCheckDisbursementdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
					if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
						$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
						while ($objJvDtl->queryfetchrow()) {
							if ($objJvDtl->getudfvalue("itemtype")!="A") {
								if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
									return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
								}
							}
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->delete();
							//var_dump($actionReturn);
							if (!$actionReturn) return false;
						}					
						if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
						//var_dump($actionReturn);
						if ($actionReturn) $actionReturn = $objJvHdr->delete();
					//} else {
					//	return raiseError("Unable to find Station JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
					}	
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgucheckdosnos",true);
				}
			}
			break;
		case "u_lgufunds":
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgufundosnos");
					if ($actionReturn) $actionReturn = onCustomeventPostFundVoucherdocumentschema_brGPSLGUAcctg($objTable);
					
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgufundosnos",true);
					if ($actionReturn) $actionReturn = onCustomeventPostFundVoucherdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
					if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
						$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
						while ($objJvDtl->queryfetchrow()) {
							if ($objJvDtl->getudfvalue("itemtype")!="A") {
								if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
									return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
								}
							}
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->delete();
							if (!$actionReturn) return false;
						}					
						if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
						if ($actionReturn) $actionReturn = $objJvHdr->delete();
					//} else {
					//	return raiseError("Unable to find Station JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
					}	
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,"u_lgufundosnos",true);
				}
			}
			break;
		case "u_lguaps":
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($objTable->getudfvalue("u_jevauto")==1 && $objTable->getudfvalue("u_jevno")=="?") {
						$obju_LGUJEVSeries = new masterdataschema_br(null,$objConnection,"u_lgujevseries");
						if ($obju_LGUJEVSeries->getbykey($objTable->getudfvalue("u_jevseries"))) {
							$numlen = $obju_LGUJEVSeries->getudfvalue("u_numlen");
							$prefix = $obju_LGUJEVSeries->getudfvalue("u_prefix");
							$nextno = $obju_LGUJEVSeries->getudfvalue("u_nextno");	
							
							
							$prefix = str_replace("{B}", $_SESSION["branch"], $prefix);
							$prefix = str_replace("{Series}", $objTable->getudfvalue("u_jevseries"), $prefix);
							$prefix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[m]", str_pad( date('m',strtotime($objTable->getudfvalue("u_date"))), 2, "0", STR_PAD_LEFT), $suffix);
							$prefix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $prefix);
							$suffix = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_date"))), $suffix);
							$prefix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $prefix);
							$suffix = str_replace("[y]", str_pad(substr(date('Y',strtotime($objTable->getudfvalue("u_date"))),2), 2, "0", STR_PAD_LEFT), $suffix);
							$objTable->setudfvalue("u_jevno",$prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT));
							$obju_LGUJEVSeries->setudfvalue("u_nextno",floatval($obju_LGUJEVSeries->getudfvalue("u_nextno"))+1);	
		
							$actionReturn = $obju_LGUJEVSeries->update($obju_LGUJEVSeries->code,$obju_LGUJEVSeries->rcdversion);
						}			
					}
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable);
					if ($actionReturn) $actionReturn = onCustomeventPostAPdocumentschema_brGPSLGUAcctg($objTable);
					
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
					if ($actionReturn) $actionReturn = onCustomeventPostAPdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
					if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
						$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
						while ($objJvDtl->queryfetchrow()) {
							if ($objJvDtl->getudfvalue("itemtype")!="A") {
								if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
									return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
								}
							}
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->delete();
							if (!$actionReturn) return false;
						}					
						if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
						if ($actionReturn) $actionReturn = $objJvHdr->delete();
					} else {
						return raiseError("Unable to find JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
					}				
					if ($actionReturn) $actionReturn = onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,true);
				}
			}
			break;
		case "u_lgujvs":
			$objTable->setudfvalue("u_jevno",$objTable->docno);
			if ($actionReturn) {
				if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="O") {
					if ($actionReturn) $actionReturn = onCustomeventPostJVdocumentschema_brGPSLGUAcctg($objTable);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="CN") {
					if ($actionReturn) $actionReturn = onCustomeventPostJVdocumentschema_brGPSLGUAcctg($objTable,true);
				} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="D") {
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
					if ($objJvHdr->getbykey($objTable->docno)) {
						$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
						while ($objJvDtl->queryfetchrow()) {
							if ($objJvDtl->getudfvalue("itemtype")!="A") {
								if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
									return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
								}
							}
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->delete();
							if (!$actionReturn) return false;
						}					
						if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
						if ($actionReturn) $actionReturn = $objJvHdr->delete();
					} else {
						return raiseError("Unable to find JV No[".$objTable->docno."].");
					}				
				}
			}
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSLGUAcctg()");
	return $actionReturn;
}



function onUpdateEventdocumentschema_brGPSLGUAcctg($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lguobligationslips":
			//$actionReturn = onCustomeventCheckOBRBudgetdocumentschema_brGPSLGUAcctg($objTable);
			break;
	}		
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSLGUAcctg($objTable) {
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
function onDeleteEventdocumentschema_brGPSLGUAcctg($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

function onCustomeventCheckOBRBudgetdocumentschema_brGPSLGUAcctg($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;

	$qtr = ceil(date('n', strtotime($objTable->getudfvalue("u_date"))) / 3);
	$year = date('Y', strtotime($objTable->getudfvalue("u_date")));
	$datefr = "";
	$dateto = "";
	switch ($qtr) {
		case 1: 
			$datefr =  $year . "-01-01";
			$dateto =  $year . "-03-31";
			break;
		case 2: 
			$datefr =  $year . "-04-01";
			$dateto =  $year . "-06-30";
			break;
		case 3: 
			$datefr =  $year . "-07-01";
			$dateto =  $year . "-09-30";
			break;
		case 4: 
			$datefr =  $year . "-10-01";
			$dateto =  $year . "-12-31";
			break;
	}
	$profitcenter = $objTable->getudfvalue("u_profitcenter");
	$budget = $year . "-" . $profitcenter;
	
	$objRs = new recordset(null,$objConnection);
	$objRs->setdebug(true);
	$objRs->queryopen("select a.u_glacctno, a.u_glacctname from u_lguobligationslipaccts a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'
		group by a.u_glacctno");
		
	$objRs->queryopen("select u_glacctno, u_glacctname, u_budgetamount, u_obramount, u_budgetamount - u_obramount as u_available from (
select x.u_glacctno, x.u_glacctname, sum(xb.u_debit-xb.u_credit) as u_obramount, ifnull(xd.u_q2,0) as u_budgetamount from (
select a.u_glacctno, a.u_glacctname from u_lguobligationslipaccts a inner join chartofaccounts b on b.formatcode=a.u_glacctno and b.budget=1 where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'
and a.docid='9954' group by a.u_glacctno) as x
inner join u_lguobligationslips xa on xa.company='".$_SESSION["company"]."' and xa.branch='".$_SESSION["branch"]."' and xa.u_profitcenter='$profitcenter' and xa.u_date between '$datefr' and '$dateto'
inner join u_lguobligationslipaccts xb on xb.company=xa.company and xb.branch=xa.branch and xb.docid=xa.docid and xb.u_glacctno=x.u_glacctno
left join u_lgubudget xc on xc.company='".$_SESSION["company"]."' and xc.branch='".$_SESSION["branch"]."' and xc.code='$budget'
left join u_lgubudgetgls xd on xd.company=xc.company and xd.branch=xc.branch and xd.code=xc.code and xd.u_glacctno=x.u_glacctno
group by x.u_glacctno) as y having u_available<0");
	$errormsg = "";
	while ($objRs->queryfetchrow("NAME")) {
		$errormsg .= "[".$objRs->fields["u_glacctno"]." - ".$objRs->fields["u_glacctname"]." Budget:".formatNumericAmount($objRs->fields["u_budgetamount"])." Obr:".formatNumericAmount($objRs->fields["u_obramount"])." Short:".formatNumericAmount($objRs->fields["u_available"])."] ";
	}	
	if($errormsg!="") $actionReturn = raiseError($errormsg);	

	return $actionReturn;
}

function onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Cash Receipt";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->profitcenter5 = 0;
		$objJvDtl->itemtype = "A";
		$objJvDtl->itemno = $objTable->getudfvalue("u_cashglacctno");
		$objJvDtl->itemname = $objTable->getudfvalue("u_cashglacctname");
		$objJvDtl->debit = $objTable->getudfvalue("u_cashamount");
		$objJvDtl->grossamount = $objJvDtl->debit;
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
	

	if ($actionReturn) {
		$objRs->queryopen("select U_GLACCTNO, U_GLACCTNAME, U_AMOUNT, U_INDEX from u_lgucashraccts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->profitcenter5 = $objRs->fields["U_INDEX"]-1;
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
			if ($objRs->fields["U_AMOUNT"]>0) {
				$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
				$objJvDtl->grossamount = $objJvDtl->debit;
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$objRs->queryopen("select U_BPCODE, U_BPNAME, U_REFNO, U_AMOUNT, U_TF from u_lgucashradvs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->profitcenter5 = 0;
			$objJvDtl->itemtype = "C";
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->itemno = $objRs->fields["U_BPCODE"];
			$objJvDtl->itemname = $objRs->fields["U_BPNAME"];
			if (!$delete) {
				$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->debit;
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
			
			if ($objRs->fields["U_TF"]==1) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->projcode = "";
				$objJvDtl->profitcenter5 = 0;
				$objJvDtl->itemtype = "C";
				$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
				if (!$delete) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				}		
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
				if (!$actionReturn) break;
			}
		}
	}	

	if ($actionReturn) {
		$ctr=0;
		$objRs->queryopen("select A.U_BANK, A.U_BANKACCTNO, A.U_AMOUNT, B.GLACCTNO, C.ACCTNAME AS GLACCTNAME from u_lgucashrbds A left join housebankaccounts B on B.bank=A.u_bank and B.bankacctno=A.u_bankacctno left join chartofaccounts C on C.formatcode=b.glacctno where A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			$ctr++;
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->profitcenter4 = "BD DEBIT";
			$objJvDtl->profitcenter5 = $ctr;
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = $objRs->fields["GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["GLACCTNAME"];
			$objJvDtl->isbankacct = 1;
			$objJvDtl->country = $_SESSION["country"];
			$objJvDtl->bank = $objRs->fields["U_BANK"];
			$objJvDtl->bankacctno = $objRs->fields["U_BANKACCTNO"];
			$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
			$objJvDtl->grossamount = $objJvDtl->debit;
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;

			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->profitcenter4 = "BD CREDIT";
			$objJvDtl->profitcenter5 = $ctr;
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = $objTable->getudfvalue("u_cashglacctno");
			$objJvDtl->itemname = $objTable->getudfvalue("u_cashglacctname");
			$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
			$objJvDtl->grossamount = $objJvDtl->credit;
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
			
		}
	}	

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventPostBankDepositdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Bank Deposit";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	$objJvDtl->prepareadd();
	$objJvDtl->docid = $objJvHdr->docid;
	$objJvDtl->objectcode = $objJvHdr->objectcode;
	$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
	$objJvDtl->refbranch = $_SESSION["branch"];
	$objJvDtl->projcode = "";
	$objJvDtl->profitcenter4 = "BD CREDIT";
	$objJvDtl->profitcenter5 = $ctr;
	$objJvDtl->itemtype = "A";
	$objJvDtl->itemno = $objTable->getudfvalue("u_cashglacctno");
	$objJvDtl->itemname = $objTable->getudfvalue("u_cashglacctname");
	$objJvDtl->credit = $objTable->getudfvalue("u_amount");
	$objJvDtl->grossamount = $objJvDtl->credit;
	$objJvHdr->totaldebit += $objJvDtl->debit ;
	$objJvHdr->totalcredit += $objJvDtl->credit ;
	$objJvDtl->privatedata["header"] = $objJvHdr;
	$actionReturn = $objJvDtl->add();

	if ($actionReturn) {
		$objRs->queryopen("select A.GLACCTNO, B.ACCTNAME AS GLACCTNAME from housebankaccounts A left join chartofaccounts B on B.formatcode=A.glacctno where A.bank='".$objTable->getudfvalue("u_bank")."' and A.bankacctno='".$objTable->getudfvalue("u_bankacctno")."'");
		if ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->profitcenter4 = "BD DEBIT";
			$objJvDtl->profitcenter5 = $ctr;
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = $objRs->fields["GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["GLACCTNAME"];
			$objJvDtl->isbankacct = 1;
			$objJvDtl->country = $_SESSION["country"];
			$objJvDtl->bank = $objTable->getudfvalue("u_bank");
			$objJvDtl->bankacctno = $objTable->getudfvalue("u_bankacctno");
			$objJvDtl->debit = $objTable->getudfvalue("u_amount");
			$objJvDtl->grossamount = $objJvDtl->debit;
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventPostCashDisbursementdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Cash Disbursement";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	$objJvHdr->setudfvalue("u_province",$objTable->getudfvalue("u_province"));
	$objJvHdr->setudfvalue("u_municipality",$objTable->getudfvalue("u_municipality"));
	$objJvHdr->setudfvalue("u_assistant",$objTable->getudfvalue("u_assistant"));
	$objJvHdr->setudfvalue("u_accountant",$objTable->getudfvalue("u_accountant"));
	$objJvHdr->setudfvalue("u_treasurer",$objTable->getudfvalue("u_treasurer"));
	$objJvHdr->setudfvalue("u_mayor",$objTable->getudfvalue("u_mayor"));
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn) {
		$objRs->queryopen("select U_GLACCTNO, U_GLACCTNAME, U_PROFITCENTER, U_AMOUNT from u_lgucashdaccts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter = $objRs->fields["U_PROFITCENTER"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$objRs->queryopen("select U_BPCODE, U_BPNAME, U_REFNO, U_AMOUNT*-1 AS U_AMOUNT from u_lgucashdadvs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = $objTable->getudfvalue("u_bptype");
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_BPCODE"];
			$objJvDtl->itemname = $objRs->fields["U_BPNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]<0) {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$objRs->queryopen("select U_REFNO, U_AMOUNT from u_lgucashdaps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "S";
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			if ($objTable->getudfvalue("u_apbpcode")!="") {
				$objJvDtl->itemno = $objTable->getudfvalue("u_apbpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_apbpname");
			} else {	
				$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
			}	
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn && $objTable->getudfvalue("u_refundamount")<0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = $objTable->getudfvalue("u_refundglacctno");
		$objJvDtl->itemname = $objTable->getudfvalue("u_refundglacctname");
		if (!$delete) {
			$objJvDtl->debit = $objTable->getudfvalue("u_refundamount")*-1;
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = $objTable->getudfvalue("u_refundamount")*-1;
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventUpdateObligationRequestdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_osno")=="") return true;
	
	$objRs = new recordset(null,$objConnection);
	$obju_LGUObligationSlips = new documentschema_br(null,$objConnection,"u_lguobligationslips");
	if ($obju_LGUObligationSlips->getbykey($objTable->getudfvalue("u_osno"))) {
		if ($delete) {
			if ($obju_LGUObligationSlips->docstatus=="C") {
				$obju_LGUObligationSlips->docstatus="O";
				$actionReturn = $obju_LGUObligationSlips->update($obju_LGUObligationSlips->docno,$obju_LGUObligationSlips->rcdversion);
			} else {
				return raiseError("Obligation Reuest was already Opened.");
			}
		} else {
			if ($obju_LGUObligationSlips->docstatus=="O") {
				$obju_LGUObligationSlips->docstatus="C";
				$actionReturn = $obju_LGUObligationSlips->update($obju_LGUObligationSlips->docno,$obju_LGUObligationSlips->rcdversion);
			} else {
				return raiseError("Obligation Reuest was already Closed.");
			}
		}
	} else return raiseError("Unable to retrieve Obligation Request No. [".$objTable->getudfvalue("u_osno")."].");

	return $actionReturn;
	
}

function onCustomeventUpdateMultipleObligationRequestdocumentschema_brGPSLGUAcctg($objTable,$table,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$objRs = new recordset(null,$objConnection);
	$obju_LGUObligationSlips = new documentschema_br(null,$objConnection,"u_lguobligationslips");

	$objRs->queryopen("select u_osno from $table where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
	while ($objRs->queryfetchrow("NAME")) {
		if ($obju_LGUObligationSlips->getbykey($objRs->fields["u_osno"])) {
			if ($delete) {
				if ($obju_LGUObligationSlips->docstatus=="C") {
					$obju_LGUObligationSlips->docstatus="O";
					$actionReturn = $obju_LGUObligationSlips->update($obju_LGUObligationSlips->docno,$obju_LGUObligationSlips->rcdversion);
				} else {
					return raiseError("Obligation Reuest was already Opened.");
				}
			} else {
				if ($obju_LGUObligationSlips->docstatus=="O") {
					$obju_LGUObligationSlips->docstatus="C";
					$actionReturn = $obju_LGUObligationSlips->update($obju_LGUObligationSlips->docno,$obju_LGUObligationSlips->rcdversion);
				} else {
					return raiseError("Obligation Reuest was already Closed.");
				}
			}
		} else return raiseError("Unable to retrieve Obligation Request No. [".$objRs->fields["u_osno"]."].");
	}
	return $actionReturn;
	
}

function onCustomeventPostCheckDisbursementdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Check Disbursement";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	$objJvHdr->setudfvalue("u_province",$objTable->getudfvalue("u_province"));
	$objJvHdr->setudfvalue("u_municipality",$objTable->getudfvalue("u_municipality"));
	$objJvHdr->setudfvalue("u_assistant",$objTable->getudfvalue("u_assistant"));
	$objJvHdr->setudfvalue("u_accountant",$objTable->getudfvalue("u_accountant"));
	$objJvHdr->setudfvalue("u_treasurer",$objTable->getudfvalue("u_treasurer"));
	$objJvHdr->setudfvalue("u_mayor",$objTable->getudfvalue("u_mayor"));
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn && $objTable->getudfvalue("u_tf")==1) {
		$objRs->queryopen("select B.GLACCTNO, C.ACCTNAME AS GLACCTNAME from housebankaccounts B left join chartofaccounts C on C.formatcode=b.glacctno where B.bank='".$objTable->getudfvalue("u_tfbank")."' and B.bankacctno='".$objTable->getudfvalue("u_tfbankacctno")."'");
		if ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["GLACCTNAME"];
			$objJvDtl->isbankacct = 1;
			$objJvDtl->country = $_SESSION["country"];
			$objJvDtl->bank = $objTable->getudfvalue("u_tfbank");
			$objJvDtl->bankacctno = $objTable->getudfvalue("u_tfbankacctno");
			if (!$delete) {
				$objJvDtl->debit = $objTable->getudfvalue("u_checkamount");
				$objJvDtl->grossamount = $objJvDtl->debit;
			} else {
				$objJvDtl->credit = $objTable->getudfvalue("u_checkamount");
				$objJvDtl->grossamount = $objJvDtl->credit;
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}
	}

	if ($actionReturn) {
		if ($objTable->getudfvalue("u_ob")==0) {
			if ($objTable->getudfvalue("u_checkamount")>0) {
				$objRs->queryopen("select B.GLACCTNO, C.ACCTNAME AS GLACCTNAME from housebankaccounts B left join chartofaccounts C on C.formatcode=b.glacctno where B.bank='".$objTable->getudfvalue("u_checkbank")."' and B.bankacctno='".$objTable->getudfvalue("u_checkbankacctno")."'");
				if ($objRs->queryfetchrow("NAME")) {
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->projcode = "";
					$objJvDtl->itemtype = "A";
					$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
					$objJvDtl->itemno = $objRs->fields["GLACCTNO"];
					$objJvDtl->itemname = $objRs->fields["GLACCTNAME"];
					$objJvDtl->isbankacct = 1;
					$objJvDtl->country = $_SESSION["country"];
					$objJvDtl->bank = $objTable->getudfvalue("u_checkbank");
					$objJvDtl->bankacctno = $objTable->getudfvalue("u_checkbankacctno");
					$objJvDtl->reference3 = $objTable->getudfvalue("u_checkno");
					if (!$delete) {
						$objJvDtl->credit = $objTable->getudfvalue("u_checkamount");
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $objTable->getudfvalue("u_checkamount");
						$objJvDtl->grossamount = $objJvDtl->debit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					
					if ($actionReturn) {
						$objRs2->queryopen("select U_CHECKNO, U_CHECKAMOUNT from u_lgucheckdchks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
						while ($objRs2->queryfetchrow("NAME")) {
							$objJvDtl->prepareadd();
							$objJvDtl->docid = $objJvHdr->docid;
							$objJvDtl->objectcode = $objJvHdr->objectcode;
							$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
							$objJvDtl->refbranch = $_SESSION["branch"];
							$objJvDtl->projcode = "";
							$objJvDtl->itemtype = "A";
							$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
							$objJvDtl->itemno = $objRs->fields["GLACCTNO"];
							$objJvDtl->itemname = $objRs->fields["GLACCTNAME"];
							$objJvDtl->isbankacct = 1;
							$objJvDtl->country = $_SESSION["country"];
							$objJvDtl->bank = $objTable->getudfvalue("u_checkbank");
							$objJvDtl->bankacctno = $objTable->getudfvalue("u_checkbankacctno");
							$objJvDtl->reference3 = $objRs2->fields["U_CHECKNO"];
							if (!$delete) {
								$objJvDtl->credit = $objRs2->fields["U_CHECKAMOUNT"];
								$objJvDtl->grossamount = $objJvDtl->credit;
							} else {
								$objJvDtl->debit = $objRs2->fields["U_CHECKAMOUNT"];
								$objJvDtl->grossamount = $objJvDtl->debit;
							}	
							$objJvHdr->totaldebit += $objJvDtl->debit ;
							$objJvHdr->totalcredit += $objJvDtl->credit ;
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->add();
						}
					}
					
					
				}
			}	
		} else {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = "1-03-05-999";
			$objJvDtl->itemname = "Advances - Clearing";
			if (!$delete) {
				$objJvDtl->credit = $objTable->getudfvalue("u_checkamount");
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = $objTable->getudfvalue("u_checkamount");
				$objJvDtl->grossamount = $objJvDtl->debit;
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}	
	}

	if ($actionReturn && $objTable->getudfvalue("u_advanceamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = $objTable->getudfvalue("u_bptype");
		$objJvDtl->lineno = 1;
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
		$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
		if (!$delete) {
			$objJvDtl->debit = $objTable->getudfvalue("u_advanceamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = $objTable->getudfvalue("u_advanceamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
		
	if ($actionReturn) {
		$objRs->queryopen("select U_GLACCTNO, U_GLACCTNAME, U_AMOUNT, U_WTAX, U_PROFITCENTER from u_lgucheckdaccts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' group by U_GLACCTNO, U_PROFITCENTER");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter = $objRs->fields["U_PROFITCENTER"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
			
			if ($objRs->fields["U_WTAX"]>0) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->projcode = "";
				$objJvDtl->itemtype = "A";
				$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
				$objJvDtl->itemno = "2-02-01-010";
				$objJvDtl->itemname = "Due to BIR";
				if (!$delete) {
					$objJvDtl->credit = $objRs->fields["U_WTAX"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objRs->fields["U_WTAX"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				}	
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit;
				$objJvHdr->sllastno++;
				$objJvDtl->lineno = $objJvHdr->sllastno;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
			}
		}
	}	

	if ($actionReturn) {
		$objRs->queryopen("select U_REFNO, U_AMOUNT from u_lgucheckdaps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "S";
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			if ($objTable->getudfvalue("u_apbpcode")!="") {
				$objJvDtl->itemno = $objTable->getudfvalue("u_apbpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_apbpname");
			} else {
				$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
			}	
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

/*
	if ($actionReturn && $objTable->getudfvalue("u_lamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = "2-02-01-010";
		$objJvDtl->itemname = "Due to BIR";
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
*/
 	if ($actionReturn && $objTable->getudfvalue("u_lvatamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->profitcenter5 = "VAT";
		$objJvDtl->itemno = "2-02-01-010";
		$objJvDtl->itemname = "Due to BIR";
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_lvatamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_lvatamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}

 	if ($actionReturn && $objTable->getudfvalue("u_levatamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->profitcenter5 = "E-VAT";
		$objJvDtl->itemno = "2-02-01-010";
		$objJvDtl->itemname = "Due to BIR";
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_levatamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_levatamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}

 	if ($actionReturn && $objTable->getudfvalue("u_levat2amount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->profitcenter5 = "E-WTAX";
		$objJvDtl->itemno = "2-02-01-010";
		$objJvDtl->itemname = "Due to BIR";
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_levat2amount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_levat2amount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}


	if ($actionReturn) {
		$objRs->queryopen("select U_BPCODE, U_BPNAME, U_REFNO, U_AMOUNT*-1 AS U_AMOUNT from u_lgucheckdadvs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = $objTable->getudfvalue("u_bptype");
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_BPCODE"];
			$objJvDtl->itemname = $objRs->fields["U_BPNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn && $objTable->getudfvalue("u_refundamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = $objTable->getudfvalue("u_refundglacctno");
		$objJvDtl->itemname = $objTable->getudfvalue("u_refundglacctname");
		if (!$delete) {
			$objJvDtl->debit = $objTable->getudfvalue("u_refundamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = $objTable->getudfvalue("u_refundamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostCheckDisbursementdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventPostFundVoucherdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Cash Disbursement";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	$objJvHdr->setudfvalue("u_province",$objTable->getudfvalue("u_province"));
	$objJvHdr->setudfvalue("u_municipality",$objTable->getudfvalue("u_municipality"));
	$objJvHdr->setudfvalue("u_assistant",$objTable->getudfvalue("u_assistant"));
	$objJvHdr->setudfvalue("u_accountant",$objTable->getudfvalue("u_accountant"));
	$objJvHdr->setudfvalue("u_treasurer",$objTable->getudfvalue("u_treasurer"));
	$objJvHdr->setudfvalue("u_mayor",$objTable->getudfvalue("u_mayor"));
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn) {
		if ($objTable->getudfvalue("u_ob")==0) {
			$objRs->queryopen("select B.U_GLACCTNO AS GLACCTNO, C.ACCTNAME AS GLACCTNAME from u_lgufundtypes B left join chartofaccounts C on C.formatcode=b.u_glacctno where B.code='".$objTable->getudfvalue("u_fundtype")."'");
			if ($objRs->queryfetchrow("NAME")) {
				if ($objTable->getudfvalue("u_fundamount")>0) {
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->projcode = "";
					$objJvDtl->itemtype = "A";
					$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
					$objJvDtl->itemno = $objRs->fields["GLACCTNO"];
					$objJvDtl->itemname = $objRs->fields["GLACCTNAME"];
					if (!$delete) {
						$objJvDtl->credit = $objTable->getudfvalue("u_fundamount");
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $objTable->getudfvalue("u_fundamount");
						$objJvDtl->grossamount = $objJvDtl->debit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}	
			}
		} else {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = "1-03-05-999";
			$objJvDtl->itemname = "Advances - Clearing";
			if (!$delete) {
				$objJvDtl->credit = $objTable->getudfvalue("u_fundamount");
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = $objTable->getudfvalue("u_fundamount");
				$objJvDtl->grossamount = $objJvDtl->debit;
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}	
	}

	if ($actionReturn && $objTable->getudfvalue("u_advanceamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = $objTable->getudfvalue("u_bptype");
		$objJvDtl->lineno = 1;
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
		$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
		if (!$delete) {
			$objJvDtl->debit = $objTable->getudfvalue("u_advanceamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = $objTable->getudfvalue("u_advanceamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
		
	if ($actionReturn) {
		$objRs->queryopen("select U_GLACCTNO, U_GLACCTNAME, U_PROFITCENTER, SUM(U_AMOUNT) AS U_AMOUNT, SUM(U_WTAX) AS U_WTAX from u_lgufundaccts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' group by U_GLACCTNO, U_PROFITCENTER");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter = $objRs->fields["U_PROFITCENTER"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
			
			if ($objRs->fields["U_WTAX"]>0) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->projcode = "";
				$objJvDtl->itemtype = "A";
				$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
				$objJvDtl->itemno = "2-02-01-010";
				$objJvDtl->itemname = "Due to BIR";
				if (!$delete) {
					$objJvDtl->credit = $objRs->fields["U_WTAX"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objRs->fields["U_WTAX"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				}	
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit;
				$objJvHdr->sllastno++;
				$objJvDtl->lineno = $objJvHdr->sllastno;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
			}
		}
	}	

	if ($actionReturn) {
		$objRs->queryopen("select U_REFNO, U_AMOUNT from u_lgufundaps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "S";
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			if ($objTable->getudfvalue("u_apbpcode")!="") {
				$objJvDtl->itemno = $objTable->getudfvalue("u_apbpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_apbpname");
			} else {
				$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
				$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
			}
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn && $objTable->getudfvalue("u_lamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = "2-02-01-010";
		$objJvDtl->itemname = "Due to BIR";
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}

	if ($actionReturn) {
		$objRs->queryopen("select U_BPCODE, U_BPNAME, U_REFNO, U_AMOUNT from u_lgufundadvs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_selected=1 and u_amount<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "C";
			$objJvDtl->reftype = "JOURNALVOUCHER";
			$objJvDtl->refno = $objRs->fields["U_REFNO"];
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_BPCODE"];
			$objJvDtl->itemname = $objRs->fields["U_BPNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->devit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostFundVoucherdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventPostAPdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Accounts Payable";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	$objJvHdr->setudfvalue("u_province",$objTable->getudfvalue("u_province"));
	$objJvHdr->setudfvalue("u_municipality",$objTable->getudfvalue("u_municipality"));
	$objJvHdr->setudfvalue("u_assistant",$objTable->getudfvalue("u_assistant"));
	$objJvHdr->setudfvalue("u_accountant",$objTable->getudfvalue("u_accountant"));
	$objJvHdr->setudfvalue("u_treasurer",$objTable->getudfvalue("u_treasurer"));
	$objJvHdr->setudfvalue("u_mayor",$objTable->getudfvalue("u_mayor"));
	
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');

	$objJvDtl->prepareadd();
	$objJvDtl->docid = $objJvHdr->docid;
	$objJvDtl->objectcode = $objJvHdr->objectcode;
	$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
	$objJvDtl->refbranch = $_SESSION["branch"];
	$objJvDtl->projcode = "";
	$objJvDtl->itemtype = "S";
	$objJvDtl->lineno = 1;
	$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
	$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
	$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
	if (!$delete) {
		if ($objTable->getudfvalue("u_dueamount")>0) {
			$objJvDtl->credit = $objTable->getudfvalue("u_dueamount")-$objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = bcmul($objTable->getudfvalue("u_dueamount")-$objTable->getudfvalue("u_lamount"),-1,14);
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
	} else {
		if ($objTable->getudfvalue("u_dueamount")>0) {
			$objJvDtl->debit = $objTable->getudfvalue("u_dueamount")-$objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = bcmul($objTable->getudfvalue("u_dueamount")-$objTable->getudfvalue("u_lamount"),-1,14);
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
	}
	$objJvHdr->totaldebit += $objJvDtl->debit ;
	$objJvHdr->totalcredit += $objJvDtl->credit;
	$objJvHdr->sllastno++;
	$objJvDtl->lineno = $objJvHdr->sllastno;
	$objJvDtl->privatedata["header"] = $objJvHdr;
	$actionReturn = $objJvDtl->add();
	
	if ($actionReturn && $objTable->getudfvalue("u_lamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
		$objJvDtl->itemno = "2-02-01-010";
		$objJvDtl->itemname = "Due to BIR";
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_lamount");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit;
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
	
	
	if ($actionReturn) {
		$objRs->queryopen("select U_GLACCTNO, U_GLACCTNAME, U_AMOUNT from u_lguapaccts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
			if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostAPdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventPostJVdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->docno;
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "General Journal";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_bpname");
	$objJvHdr->setudfvalue("u_province",$objTable->getudfvalue("u_province"));
	$objJvHdr->setudfvalue("u_municipality",$objTable->getudfvalue("u_municipality"));
	$objJvHdr->setudfvalue("u_assistant",$objTable->getudfvalue("u_assistant"));
	$objJvHdr->setudfvalue("u_accountant",$objTable->getudfvalue("u_accountant"));
	$objJvHdr->setudfvalue("u_treasurer",$objTable->getudfvalue("u_treasurer"));
	$objJvHdr->setudfvalue("u_mayor",$objTable->getudfvalue("u_mayor"));
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn) {
		$objRs->queryopen("select U_GLACCTNO, U_GLACCTNAME, U_DEBIT, U_CREDIT, U_ISBANK, U_BANK, U_BANKACCTNO from u_lgujvaccts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "A";
			$objJvDtl->profitcenter4 = $objTable->getudfvalue("u_jevseries");
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
			if ($objRs->fields["U_ISBANK"]==1) {
				$objJvDtl->isbankacct = 1;
				$objJvDtl->country = $_SESSION["country"];
				$objJvDtl->bank = $objRs->fields["U_BANK"];
				$objJvDtl->bankacctno = $objRs->fields["U_BANKACCTNO"];
			}			
			if (!$delete) {
				if ($objRs->fields["U_DEBIT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_DEBIT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objRs->fields["U_CREDIT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_DEBIT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_DEBIT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objRs->fields["U_CREDIT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}		
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostJVdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
}

function onCustomeventPostBudgetdocumentschema_brGPSLGUAcctg($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$objRs = new recordset(null, $objConnection);
	$obju_LGUProjs = new masterdataschema_br(null,$objConnection,"u_lguprojs");
	$obju_LGUBudget = new masterdataschema_br(null,$objConnection,"u_lgubudget");
	$obju_LGUBudgetGls = new masterdatalinesschema_br(null,$objConnection,"u_lgubudgetgls");
	
	if (!$obju_LGUBudget->getbykey($objTable->getudfvalue("u_yr")."-".$objTable->getudfvalue("u_profitcenter"))) {
		$obju_LGUBudget->prepareadd();
		$obju_LGUBudget->code = $objTable->getudfvalue("u_yr")."-".$objTable->getudfvalue("u_profitcenter");
		$obju_LGUBudget->name = "";
		$obju_LGUBudget->setudfvalue("u_yr", $objTable->getudfvalue("u_yr"));
		$obju_LGUBudget->setudfvalue("u_profitcenter", $objTable->getudfvalue("u_profitcenter"));
	}
	
	$objRs->setdebug();
	$objRs->queryopen("select xa.U_EXPGROUPNO, xa.U_EXPCLASS, x.U_ITEMNAME, x.U_GLACCTNO, x.U_GLACCTNAME, x.U_M1, x.U_M2, x.U_M3, x.U_M4, x.U_M5, x.U_M6, x.U_M7, x.U_M8, x.U_M9, x.U_M10, x.U_M11, x.U_M12, x.U_YR from (select a.U_ITEMNAME, if(b.u_glacctno<>'',b.u_glacctno, c.u_glacctno) as U_GLACCTNO, if(b.u_glacctno<>'',b.u_glacctname, c.u_glacctname) as U_GLACCTNAME, a.U_M1*a.U_UNITPRICE as U_M1, a.U_M2*a.U_UNITPRICE as U_M2, a.U_M3*a.U_UNITPRICE as U_M3, a.U_M4*a.U_UNITPRICE as U_M4, a.U_M5*a.U_UNITPRICE as U_M5, a.U_M6*a.U_UNITPRICE as U_M6, a.U_M7*a.U_UNITPRICE as U_M7, a.U_M8*a.U_UNITPRICE as U_M8, a.U_M9*a.U_UNITPRICE as U_M9, a.U_M10*a.U_UNITPRICE as U_M10, a.U_M11*a.U_UNITPRICE as U_M11, a.U_M12*a.U_UNITPRICE as U_M12, a.U_TOTALAMOUNT as U_YR from u_lguppmpitems a inner join u_lguitems b on b.code=a.u_itemno inner join u_lguitemgroups c on c.code=b.u_itemgroup where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' union all select '' as U_ITEMNAME, a.U_GLACCTNO, a.U_GLACCTNAME, a.U_M1, a.U_M2, a.U_M3, a.U_M4, a.U_M5, a.U_M6, a.U_M7, a.U_M8, a.U_M9, a.U_M10, a.U_M11, a.U_M12, a.U_YR from u_lguppmpgls a  where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."') as x left join chartofaccounts xa on xa.formatcode=x.U_GLACCTNO");
	//var_dump($objRs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		if ($objRs->fields["U_GLACCTNO"]=="") return raiseError("G/L is missing for Item [".$objRs->fields["U_ITEMNAME"]."]");
		
		if (!$obju_LGUBudgetGls->getbysql("CODE='$obju_LGUBudget->code' AND U_GLACCTNO='".$objRs->fields["U_GLACCTNO"]."'")) {
			$obju_LGUBudgetGls->prepareadd();
			$obju_LGUBudgetGls->code = $obju_LGUBudget->code;
			$obju_LGUBudgetGls->lineid = getNextIdByBranch("u_lgubudgetgls",$objConnection);;
			$obju_LGUBudgetGls->setudfvalue("u_expgroupno",$objRs->fields["U_EXPGROUPNO"]);
			$obju_LGUBudgetGls->setudfvalue("u_expclass",$objRs->fields["U_EXPCLASS"]);
			$obju_LGUBudgetGls->setudfvalue("u_glacctno",$objRs->fields["U_GLACCTNO"]);
			$obju_LGUBudgetGls->setudfvalue("u_glacctname",$objRs->fields["U_GLACCTNAME"]);
		}
		if (!$delete) {	
			$obju_LGUBudgetGls->setudfvalue("u_m1",$obju_LGUBudgetGls->getudfvalue("u_m1")+$objRs->fields["U_M1"]);
			$obju_LGUBudgetGls->setudfvalue("u_m2",$obju_LGUBudgetGls->getudfvalue("u_m2")+$objRs->fields["U_M2"]);
			$obju_LGUBudgetGls->setudfvalue("u_m3",$obju_LGUBudgetGls->getudfvalue("u_m3")+$objRs->fields["U_M3"]);
			$obju_LGUBudgetGls->setudfvalue("u_m4",$obju_LGUBudgetGls->getudfvalue("u_m4")+$objRs->fields["U_M4"]);
			$obju_LGUBudgetGls->setudfvalue("u_m5",$obju_LGUBudgetGls->getudfvalue("u_m5")+$objRs->fields["U_M5"]);
			$obju_LGUBudgetGls->setudfvalue("u_m6",$obju_LGUBudgetGls->getudfvalue("u_m6")+$objRs->fields["U_M6"]);
			$obju_LGUBudgetGls->setudfvalue("u_m7",$obju_LGUBudgetGls->getudfvalue("u_m7")+$objRs->fields["U_M7"]);
			$obju_LGUBudgetGls->setudfvalue("u_m8",$obju_LGUBudgetGls->getudfvalue("u_m8")+$objRs->fields["U_M8"]);
			$obju_LGUBudgetGls->setudfvalue("u_m9",$obju_LGUBudgetGls->getudfvalue("u_m9")+$objRs->fields["U_M9"]);
			$obju_LGUBudgetGls->setudfvalue("u_m10",$obju_LGUBudgetGls->getudfvalue("u_m10")+$objRs->fields["U_M10"]);
			$obju_LGUBudgetGls->setudfvalue("u_m11",$obju_LGUBudgetGls->getudfvalue("u_m11")+$objRs->fields["U_M11"]);
			$obju_LGUBudgetGls->setudfvalue("u_m12",$obju_LGUBudgetGls->getudfvalue("u_m12")+$objRs->fields["U_M12"]);
			$obju_LGUBudgetGls->setudfvalue("u_yr",$obju_LGUBudgetGls->getudfvalue("u_yr")+$objRs->fields["U_YR"]);
		} else {
			$obju_LGUBudgetGls->setudfvalue("u_m1",$obju_LGUBudgetGls->getudfvalue("u_m1")-$objRs->fields["U_M1"]);
			$obju_LGUBudgetGls->setudfvalue("u_m2",$obju_LGUBudgetGls->getudfvalue("u_m2")-$objRs->fields["U_M2"]);
			$obju_LGUBudgetGls->setudfvalue("u_m3",$obju_LGUBudgetGls->getudfvalue("u_m3")-$objRs->fields["U_M3"]);
			$obju_LGUBudgetGls->setudfvalue("u_m4",$obju_LGUBudgetGls->getudfvalue("u_m4")-$objRs->fields["U_M4"]);
			$obju_LGUBudgetGls->setudfvalue("u_m5",$obju_LGUBudgetGls->getudfvalue("u_m5")-$objRs->fields["U_M5"]);
			$obju_LGUBudgetGls->setudfvalue("u_m6",$obju_LGUBudgetGls->getudfvalue("u_m6")-$objRs->fields["U_M6"]);
			$obju_LGUBudgetGls->setudfvalue("u_m7",$obju_LGUBudgetGls->getudfvalue("u_m7")-$objRs->fields["U_M7"]);
			$obju_LGUBudgetGls->setudfvalue("u_m8",$obju_LGUBudgetGls->getudfvalue("u_m8")-$objRs->fields["U_M8"]);
			$obju_LGUBudgetGls->setudfvalue("u_m9",$obju_LGUBudgetGls->getudfvalue("u_m9")-$objRs->fields["U_M9"]);
			$obju_LGUBudgetGls->setudfvalue("u_m10",$obju_LGUBudgetGls->getudfvalue("u_m10")-$objRs->fields["U_M10"]);
			$obju_LGUBudgetGls->setudfvalue("u_m11",$obju_LGUBudgetGls->getudfvalue("u_m11")-$objRs->fields["U_M11"]);
			$obju_LGUBudgetGls->setudfvalue("u_m12",$obju_LGUBudgetGls->getudfvalue("u_m12")-$objRs->fields["U_M12"]);
			$obju_LGUBudgetGls->setudfvalue("u_yr",$obju_LGUBudgetGls->getudfvalue("u_yr")-$objRs->fields["U_YR"]);
		}			
		$obju_LGUBudgetGls->setudfvalue("u_q1",$obju_LGUBudgetGls->getudfvalue("u_m1")+$obju_LGUBudgetGls->getudfvalue("u_m2")+$obju_LGUBudgetGls->getudfvalue("u_m3"));
		$obju_LGUBudgetGls->setudfvalue("u_q2",$obju_LGUBudgetGls->getudfvalue("u_m4")+$obju_LGUBudgetGls->getudfvalue("u_m5")+$obju_LGUBudgetGls->getudfvalue("u_m6"));
		$obju_LGUBudgetGls->setudfvalue("u_q3",$obju_LGUBudgetGls->getudfvalue("u_m7")+$obju_LGUBudgetGls->getudfvalue("u_m8")+$obju_LGUBudgetGls->getudfvalue("u_m9"));
		$obju_LGUBudgetGls->setudfvalue("u_q4",$obju_LGUBudgetGls->getudfvalue("u_m10")+$obju_LGUBudgetGls->getudfvalue("u_m11")+$obju_LGUBudgetGls->getudfvalue("u_m12"));


		$obju_LGUBudget->setudfvalue("u_totalamount",$obju_LGUBudget->getudfvalue("u_totalamount")+$objRs->fields["U_YR"]);
		if (!$delete) {	
			switch ($obju_LGUBudgetGls->getudfvalue("u_expclass")) {
				case "PS": $obju_LGUBudget->setudfvalue("u_totalps",$obju_LGUBudget->getudfvalue("u_totalps")+$objRs->fields["U_YR"]); break;
				case "MOOE": $obju_LGUBudget->setudfvalue("u_totalmooe",$obju_LGUBudget->getudfvalue("u_totalmooe")+$objRs->fields["U_YR"]); break;
				case "FE": $obju_LGUBudget->setudfvalue("u_totalfe",$obju_LGUBudget->getudfvalue("u_totalfe")+$objRs->fields["U_YR"]); break;
				case "CO": $obju_LGUBudget->setudfvalue("u_totalco",$obju_LGUBudget->getudfvalue("u_totalco")+$objRs->fields["U_YR"]); break;
					
			}
		} else {
			switch ($obju_LGUBudgetGls->getudfvalue("u_expclass")) {
				case "PS": $obju_LGUBudget->setudfvalue("u_totalps",$obju_LGUBudget->getudfvalue("u_totalps")-$objRs->fields["U_YR"]); break;
				case "MOOE": $obju_LGUBudget->setudfvalue("u_totalmooe",$obju_LGUBudget->getudfvalue("u_totalmooe")-$objRs->fields["U_YR"]); break;
				case "FE": $obju_LGUBudget->setudfvalue("u_totalfe",$obju_LGUBudget->getudfvalue("u_totalfe")-$objRs->fields["U_YR"]); break;
				case "CO": $obju_LGUBudget->setudfvalue("u_totalco",$obju_LGUBudget->getudfvalue("u_totalco")-$objRs->fields["U_YR"]); break;
					
			}
		}		

		if ($obju_LGUBudgetGls->rowstat=="N") $actionReturn = $obju_LGUBudgetGls->add();
		else $actionReturn = $obju_LGUBudgetGls->update($obju_LGUBudgetGls->code, $obju_LGUBudgetGls->lineid, $obju_LGUBudgetGls->rcdversion);
		
		if (!$actionReturn) break;

		if ($objTable->getudfvalue("u_projcode")!="") {
			if ($obju_LGUProjs->getbysql("CODE='".$objTable->getudfvalue("u_projcode")."'")) {
				switch ($obju_LGUBudgetGls->getudfvalue("u_expclass")) {
					case "MOOE": 
						if (!$delete) {	
							$obju_LGUProjs->setudfvalue("u_actmooe",$obju_LGUProjs->getudfvalue("u_actmooe")+$objRs->fields["U_YR"]);
							$obju_LGUProjs->setudfvalue("u_actbudget",$obju_LGUProjs->getudfvalue("u_actbudget")+$objRs->fields["U_YR"]); 
						} else {
							$obju_LGUProjs->setudfvalue("u_actmooe",$obju_LGUProjs->getudfvalue("u_actmooe")-$objRs->fields["U_YR"]);
							$obju_LGUProjs->setudfvalue("u_actbudget",$obju_LGUProjs->getudfvalue("u_actbudget")-$objRs->fields["U_YR"]); 
						}	
						break;
					case "CO": 
						if (!$delete) {	
							$obju_LGUProjs->setudfvalue("u_actco",$obju_LGUProjs->getudfvalue("u_actco")+$objRs->fields["U_YR"]);
							$obju_LGUProjs->setudfvalue("u_actbudget",$obju_LGUProjs->getudfvalue("u_actbudget")+$objRs->fields["U_YR"]); 
						} else {
							$obju_LGUProjs->setudfvalue("u_actco",$obju_LGUProjs->getudfvalue("u_actco")-$objRs->fields["U_YR"]);
							$obju_LGUProjs->setudfvalue("u_actbudget",$obju_LGUProjs->getudfvalue("u_actbudget")-$objRs->fields["U_YR"]); 
						}	
						break;
					default:
						return raiseError("Invalid expense class [".$obju_LGUBudgetGls->getudfvalue("u_expclass")."] for Program/Project.");
						break;	
				}
				$obju_LGUProjs->update($obju_LGUProjs->code, $obju_LGUProjs->rcdversion);
			} else return raiseError("Unable to find Program/Project [".$objTable->getudfvalue("u_projcode")."].");
		}

		if (!$actionReturn) break;
	}
	
	if ($actionReturn) {
		if ($obju_LGUBudget->rowstat=="N") $actionReturn = $obju_LGUBudget->add();
		else $actionReturn = $obju_LGUBudget->update($obju_LGUBudget->code, $obju_LGUBudget->rcdversion);
	}

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostBudgetdocumentschema_brGPSLGUAcctg()");
	
	return $actionReturn;
	
}

?>
