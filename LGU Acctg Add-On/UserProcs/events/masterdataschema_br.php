<?php 

	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./series.php");

	function onAddEventmasterdataschema_brGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_lgulegacygen":
			
				$obju_LGULegacyGenSum = new masterdataschema_br(null,$objConnection,"u_lgulegacygensum");
				if (!$obju_LGULegacyGenSum->getbykey($objTable->getudfvalue("u_date"))) {
					$obju_LGULegacyGenSum->prepareadd();
					$obju_LGULegacyGenSum->code = $objTable->getudfvalue("u_date");
					$obju_LGULegacyGenSum->name = "Abstract of General - " . formatDateToHttp($objTable->getudfvalue("u_date"));
					$obju_LGULegacyGenSum->setudfvalue("u_date",$objTable->getudfvalue("u_date"));
				} else {
					if ($obju_LGULegacyGenSum->getudfvalue("u_status")=="P") {
						return raiseError($obju_LGULegacyGenSum->name . " was already posted.");
					}
				}
				for ($ctr=1;$ctr<=40;$ctr++) {
					$field = "u_amount" . str_pad( $ctr, 2, "0", STR_PAD_LEFT);
					$obju_LGULegacyGenSum->setudfvalue($field,$obju_LGULegacyGenSum->getudfvalue($field)+$objTable->getudfvalue($field));
				}
				$obju_LGULegacyGenSum->setudfvalue("u_total",$obju_LGULegacyGenSum->getudfvalue("u_total")+$objTable->getudfvalue("u_total"));
				if ($obju_LGULegacyGenSum->rowstat=="N") $actionReturn = $obju_LGULegacyGenSum->add();
				else $obju_LGULegacyGenSum->update($obju_LGULegacyGenSum->code, $obju_LGULegacyGenSum->rcdversion);
				
				break;
			case "u_lgulegacyrpt":
			
				$obju_LGULegacyGenSum = new masterdataschema_br(null,$objConnection,"u_lgulegacyrptsum");
				if (!$obju_LGULegacyGenSum->getbykey($objTable->getudfvalue("u_date"))) {
					$obju_LGULegacyGenSum->prepareadd();
					$obju_LGULegacyGenSum->code = $objTable->getudfvalue("u_date");
					$obju_LGULegacyGenSum->name = "Abstract of Real Property Tax - " . formatDateToHttp($objTable->getudfvalue("u_date"));
					$obju_LGULegacyGenSum->setudfvalue("u_date",$objTable->getudfvalue("u_date"));
				} else {
					if ($obju_LGULegacyGenSum->getudfvalue("u_status")=="P") {
						return raiseError($obju_LGULegacyGenSum->name . " was already posted.");
					}
				}
				$obju_LGULegacyGenSum->setudfvalue("u_paid",$obju_LGULegacyGenSum->getudfvalue("u_paid")+$objTable->getudfvalue("u_paid"));
				$obju_LGULegacyGenSum->setudfvalue("u_basic1",$obju_LGULegacyGenSum->getudfvalue("u_basic1")+$objTable->getudfvalue("u_basic1"));
				$obju_LGULegacyGenSum->setudfvalue("u_basic2",$obju_LGULegacyGenSum->getudfvalue("u_basic2")+$objTable->getudfvalue("u_basic2"));
				$obju_LGULegacyGenSum->setudfvalue("u_basic3",$obju_LGULegacyGenSum->getudfvalue("u_basic3")+$objTable->getudfvalue("u_basic3"));
				$obju_LGULegacyGenSum->setudfvalue("u_basicpen1",$obju_LGULegacyGenSum->getudfvalue("u_basicpen1")+$objTable->getudfvalue("u_basicpen1"));
				$obju_LGULegacyGenSum->setudfvalue("u_basicpen2",$obju_LGULegacyGenSum->getudfvalue("u_basicpen2")+$objTable->getudfvalue("u_basicpen2"));
				$obju_LGULegacyGenSum->setudfvalue("u_basicpen3",$obju_LGULegacyGenSum->getudfvalue("u_basicpen3")+$objTable->getudfvalue("u_basicpen3"));
				$obju_LGULegacyGenSum->setudfvalue("u_basicdisc",$obju_LGULegacyGenSum->getudfvalue("u_basicdisc")+$objTable->getudfvalue("u_basicdisc"));
				$obju_LGULegacyGenSum->setudfvalue("u_basictotal",$obju_LGULegacyGenSum->getudfvalue("u_basictotal")+$objTable->getudfvalue("u_basictotal"));
				$obju_LGULegacyGenSum->setudfvalue("u_sef1",$obju_LGULegacyGenSum->getudfvalue("u_sef1")+$objTable->getudfvalue("u_sef1"));
				$obju_LGULegacyGenSum->setudfvalue("u_sef2",$obju_LGULegacyGenSum->getudfvalue("u_sef2")+$objTable->getudfvalue("u_sef2"));
				$obju_LGULegacyGenSum->setudfvalue("u_sef3",$obju_LGULegacyGenSum->getudfvalue("u_sef3")+$objTable->getudfvalue("u_sef3"));
				$obju_LGULegacyGenSum->setudfvalue("u_sefpen1",$obju_LGULegacyGenSum->getudfvalue("u_sefpen1")+$objTable->getudfvalue("u_sefpen1"));
				$obju_LGULegacyGenSum->setudfvalue("u_sefpen2",$obju_LGULegacyGenSum->getudfvalue("u_sefpen2")+$objTable->getudfvalue("u_sefpen2"));
				$obju_LGULegacyGenSum->setudfvalue("u_sefpen3",$obju_LGULegacyGenSum->getudfvalue("u_sefpen3")+$objTable->getudfvalue("u_sefpen3"));
				$obju_LGULegacyGenSum->setudfvalue("u_sefdisc",$obju_LGULegacyGenSum->getudfvalue("u_sefdisc")+$objTable->getudfvalue("u_sefdisc"));
				$obju_LGULegacyGenSum->setudfvalue("u_seftotal",$obju_LGULegacyGenSum->getudfvalue("u_seftotal")+$objTable->getudfvalue("u_seftotal"));
				$obju_LGULegacyGenSum->setudfvalue("u_brgyshare",$obju_LGULegacyGenSum->getudfvalue("u_brgyshare")+$objTable->getudfvalue("u_brgyshare"));
				if ($obju_LGULegacyGenSum->rowstat=="N") $actionReturn = $obju_LGULegacyGenSum->add();
				else $obju_LGULegacyGenSum->update($obju_LGULegacyGenSum->code, $obju_LGULegacyGenSum->rcdversion);
				
				break;
		}	
		//if ($actionReturn) $actionReturn = raiseError("onAddEventmasterdataschema_brFTWheeltek");		
		return $actionReturn;
	}
	
	function onBeforeUpdateEventmasterdataschema_brGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_lgulegacygensum":
				if ($objTable->fields["U_STATUS"]!=$objTable->getudfvalue("u_status") && $objTable->getudfvalue("u_status")=="P") {
					$objRs = new recordset(null,$objConnection);
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
							
					$settings = getBusinessObjectSettings("JOURNALVOUCHER");


					$objJvHdr->prepareadd();
					$objJvHdr->objectcode = "JOURNALVOUCHER";
					$objJvHdr->sbo_post_flag = $settings["autopost"];
					$objJvHdr->jeposting = $settings["jeposting"];
					$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
					$objJvHdr->docdate = $objTable->getudfvalue("u_date");
					$objJvHdr->docduedate = $objJvHdr->docdate;
					$objJvHdr->taxdate = $objJvHdr->docdate;
					//$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
					$objJvHdr->docseries = getSeries($objJvHdr->objectcode,'AOC',$objConnection);
					$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
					$objJvHdr->currency = $_SESSION["currency"];
					$objJvHdr->docstatus = "C";
					$objJvHdr->reference1 = "";
					$objJvHdr->reference2 = "General Collection";
					
					$objJvHdr->remarks = $objTable->name;
					
					$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');

					$gldata= getBranchGLAcctNo($thisbranch,"U_ABSTRACTCLEARACCT");
					if ($gldata["formatcode"]=="") {
						return raiseError("G/L Acct [Abstract Clearing] is not maintained.");
					}				
		
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->projcode = "";
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $gldata["formatcode"];
					$objJvDtl->itemname = $gldata["acctname"];
					
					$objJvDtl->debit = $objTable->getudfvalue("u_total");
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();

					if ($actionReturn) {
						for ($ctr=1;$ctr<=40;$ctr++) {
							$field = "u_amount" . str_pad( $ctr, 2, "0", STR_PAD_LEFT);
							$glacctno = "";
							$glacctname = "";
							$amount = floatval($objTable->getudfvalue($field));
							if ($amount>0) {
								$objRs->queryopen("select u_glacctno, u_glacctname from u_lgulegacygenmap where code='$field' and u_glacctno<>''");
								if ($objRs->queryfetchrow("NAME")) {
									$glacctno = $objRs->fields["u_glacctno"];
									$glacctname = $objRs->fields["u_glacctname"];
									
									
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
									
								} else return raiseError("Missing G/L Mapping for [".$field."]");
							}
							if (!$actionReturn) break;	
						}
					}
										
					if ($actionReturn && ($objJvHdr->totaldebit!=0 || $objJvHdr->totalcredit!=0)) {
						$objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
						$actionReturn = $objJvHdr->add();				
					}	
					
					//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventmasterdataschema_brGPSLGUAcctg");		
				}
				break;
			case "u_lgulegacyrptsum":
				if ($objTable->fields["U_STATUS"]!=$objTable->getudfvalue("u_status") && $objTable->getudfvalue("u_status")=="P") {
					$objRs = new recordset(null,$objConnection);
					$objJvHdr = new journalvouchers(null,$objConnection);
					$objJvDtl = new journalvoucheritems(null,$objConnection);
							
					$settings = getBusinessObjectSettings("JOURNALVOUCHER");


					$objJvHdr->prepareadd();
					$objJvHdr->objectcode = "JOURNALVOUCHER";
					$objJvHdr->sbo_post_flag = $settings["autopost"];
					$objJvHdr->jeposting = $settings["jeposting"];
					$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
					$objJvHdr->docdate = $objTable->getudfvalue("u_date");
					$objJvHdr->docduedate = $objJvHdr->docdate;
					$objJvHdr->taxdate = $objJvHdr->docdate;
					//$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
					$objJvHdr->docseries = getSeries($objJvHdr->objectcode,'AOC',$objConnection);
					$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
					$objJvHdr->currency = $_SESSION["currency"];
					$objJvHdr->docstatus = "C";
					$objJvHdr->reference1 = "";
					$objJvHdr->reference2 = "Real Property Tax Collection";
					
					$objJvHdr->remarks = $objTable->name;
					
					$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');

					$gldata= getBranchGLAcctNo($thisbranch,"U_ABSTRACTCLEARACCT");
					if ($gldata["formatcode"]=="") {
						return raiseError("G/L Acct [Abstract Clearing] is not maintained.");
					}				
		
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->projcode = "";
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $gldata["formatcode"];
					$objJvDtl->itemname = $gldata["acctname"];
					
					$objJvDtl->debit = $objTable->getudfvalue("u_paid") - (($objTable->getudfvalue("u_basicdisc") + $objTable->getudfvalue("u_sefdisc"))*2);
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();

					if ($actionReturn) {
					
						for ($group=1;$group<=4;$group++) {
							if ($group==1) {
								$prefix = "u_basic";
							} elseif ($group==2) {
								$prefix = "u_basicpen";
							} elseif ($group==3) {
								$prefix = "u_sef";
							} elseif ($group==4) {
								$prefix = "u_sefpen";
							}	
							for ($ctr=1;$ctr<=3;$ctr++) {
								$field = $prefix . $ctr;
								$glacctno = "";
								$glacctname = "";
								$amount = floatval($objTable->getudfvalue($field));
								if ($amount>0) {
									$objRs->queryopen("select u_glacctno, u_glacctname from u_lgulegacyrptmap where code='$field' and u_glacctno<>''");
									if ($objRs->queryfetchrow("NAME")) {
										$glacctno = $objRs->fields["u_glacctno"];
										$glacctname = $objRs->fields["u_glacctname"];
										
										
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
										
									} else return raiseError("Missing G/L Mapping for [".$field."]");
								}
								if (!$actionReturn) break;	
							}
							if ($actionReturn && ($group==2 || $group==4)) {
								if ($group==2) $field = "u_basicdisc";
								else $field = "u_sefdisc";
								$glacctno = "";
								$glacctname = "";
								$amount = floatval($objTable->getudfvalue($field));
								if ($amount>0) {
									$objRs->queryopen("select u_glacctno, u_glacctname from u_lgulegacyrptmap where code='$field' and u_glacctno<>''");
									if ($objRs->queryfetchrow("NAME")) {
										$glacctno = $objRs->fields["u_glacctno"];
										$glacctname = $objRs->fields["u_glacctname"];
										
										
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
										
									} else return raiseError("Missing G/L Mapping for [".$field."]");
								}
							}
							if (!$actionReturn) break;	
						}	
					}

					if ($actionReturn && ($objJvHdr->totaldebit!=0 || $objJvHdr->totalcredit!=0)) {
						$objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
						$actionReturn = $objJvHdr->add();				
					}	
					
					//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventmasterdataschema_brGPSLGUAcctg");		
				}
				break;				
		}		

		
		return $actionReturn;
	}
	
	function onDeleteEventmasterdataschema_brGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		//if ($actionReturn) $actionReturn = raiseError("onDeleteEventmasterdataschema_brFTWheeltek");		
		return $actionReturn;
	}	
?>
