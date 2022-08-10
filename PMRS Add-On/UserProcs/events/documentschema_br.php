<?php
 


function onBeforeAddEventdocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_pmrapps":
			if ($objTable->docstatus=="Assessing") $objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			$actionReturn = onCustomEventupdatepmrmddocumentschema_brGPSPMRS($objTable);
			break;
	}
	return $actionReturn;
}



function onAddEventdocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_pmrpay":
                    if ($objTable->docstatus!="D") {
				$actionReturn = onCustomEventcreateformultibilldocumentschema_brGPSPMRS($objTable);
			}	
			
			break;
	}
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;

        $objRs = new recordset (NULL,$objConnection);
	switch ($objTable->dbtable) {
		case "u_pmrapps":
			if ($objTable->docstatus=="Assessing" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assesseddatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_approveddatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_disapproveddatetime",date('Y-m-d h:i:s'));
			//} elseif ($objTable->docstatus=="Printing" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
			//	$objTable->setudfvalue("u_paiddatetime",date('Y-m-d h:i:s'));
			}
                        
                        if (($objTable->docstatus=="Assessing" && ($objTable->fields["DOCSTATUS"]=="Approved" || $objTable->fields["DOCSTATUS"]=="Disapproved"))) {
				
				$objTable->docstatus="Assessing";
				if ($actionReturn && $objTable->fields["DOCSTATUS"]=="Approved") {
					$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                        while(true){
					if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND DOCSTATUS = 'O'  AND U_PROFITCENTER='PMR'")) {
						$actionReturn = $objLGUBills->executesql("DELETE FROM U_LGUBILLITEMS WHERE COMPANY='$objLGUBills->company' AND BRANCH='$objLGUBills->branch' AND DOCID='$objLGUBills->docid'",true);
						if ($actionReturn) $actionReturn = $objLGUBills->delete();
					} else {
						//$page->docstatus=$objTable->fields["DOCSTATUS"];
                                                break;
						//return raiseError("Unable to find Bill Document to remove.");
					}
                                        if(!$actionReturn)  break;

                                    }
                                }
			}
			
			$actionReturn = onCustomEventupdatepmrmddocumentschema_brGPSPMRS($objTable);
			if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
				$actionReturn = onCustomEventcreatebilldocumentschema_brGPSPMRS($objTable);
				if (!$actionReturn) $page->setitem("docstatus","Assessed");
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
			}	
			break;
                case "u_pmrpay":
                        if ($objTable->docstatus=="CN") {
                                $actionReturn = $objRs->executesql("UPDATE U_LGUBILLS SET DOCSTATUS = 'CN' WHERE U_APPNO = '".$objTable->docno."' AND U_SETTLEDAMOUNT = 0 ",false);
                                $actionReturn = $objRs->executesql("UPDATE U_LGUBILLS SET DOCSTATUS = 'O' WHERE DOCNO IN (SELECT B.U_BILLNO FROM U_PMRPAY A INNER JOIN U_PMRPAYITEMS B ON A.DOCID = B.DOCID WHERE A.DOCNO = '$objTable->docno' AND B.U_SELECTED = 1)",false);
                        }
                        break;
	}
	return $actionReturn;
}

function onCustomEventcreatebilldocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	
	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_PMRFISCAL,A.U_PMRRIGHTSFEE, A.U_PMRRIGHTSPAYMODE, A.U_PMRDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for public market rental fees.");
	}	
	
	//if ($objTable->getudfvalue("u_apptype")=="NEW") $custno = $objTable->docno;
	//elseif ($objTable->getudfvalue("u_bpno")!="") $custno = $objTable->getudfvalue("u_bpno");
	//else 
        
        $calendarmonth = 13 - date('m',strtotime($objTable->getudfvalue("u_decisiondate")));
        
	$custno = $objTable->docno;
	$custname = $objTable->getudfvalue("u_lastname").", ".$objTable->getudfvalue("u_firstname").iif($objTable->getudfvalue("u_middlename")!=""," " . $objTable->getudfvalue("u_middlename"),"");
	
//	if ($objTable->getudfvalue("u_apptype")=="NEW") {
		$docdate = $objTable->getudfvalue("u_decisiondate");
////	} else {
//		if ($objTable->getudfvalue("u_prevexpdate")!="") $docdate = dateadd("d",1,$objTable->getudfvalue("u_prevexpdate"));
//		else $docdate = $objTable->getudfvalue("u_decisiondate");
//		//$docdate = $objTable->getudfvalue("u_prevexpdate");
//	}			  
	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, IFNULL(B.U_INSTALLMENT,0) AS U_INSTALLMENT FROM U_PMRAPPFEES A LEFT JOIN U_PMRFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND A.U_FEECODE='".$objRs_uLGUSetup->fields["U_PMRRIGHTSFEE"]."' AND A.U_AMOUNT>0");
	if ($objRs->queryfetchrow("NAME")) {
		$amount = $objRs->fields["U_AMOUNT"];
		$month=0;
		if ($objTable->getudfvalue("u_rightspaymode")=="30P12MA6M") {
			for ($ctr=1;$ctr<=13;$ctr++) {
				$totalamount=0;
				$objLGUBills->prepareadd();
				$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
				$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
				$objLGUBills->docstatus = "O";
				$objLGUBills->setudfvalue("u_profitcenter","PMR");
				$objLGUBills->setudfvalue("u_module","Public Market Rental");
				$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
				$objLGUBills->setudfvalue("u_appno",$objTable->docno);
				$objLGUBills->setudfvalue("u_custno",$custno);
				$objLGUBills->setudfvalue("u_custname",$custname);
				if ($ctr==1) {
					$objLGUBills->setudfvalue("u_docdate",$docdate);
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_PMRDUEDAY"]-1,getmonthstartDB($docdate)));
					$month+=6;
					$amount2 = round($amount*.30,2);
					$amount -= $amount2;
				} else {
					$amount2 = round($amount/12,2);
					$objLGUBills->setudfvalue("u_docdate",getmonthstartDB(dateadd("m",$month,$docdate)));
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_PMRDUEDAY"]-1,$objLGUBills->getudfvalue("u_docdate")));
					$month++;
				}					
				//$objLGUBills->setudfvalue("u_duedate",date('Y')."-".str_pad( $month, 2, "0", STR_PAD_LEFT)."-20");
				$objLGUBills->setudfvalue("u_remarks","Public Market Rental - ".date('M, Y',strtotime($objLGUBills->getudfvalue("u_docdate")) ));
				
				$objLGUBillItems->prepareadd();
				$objLGUBillItems->docid = $objLGUBills->docid;
				$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
				$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
				$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
				$objLGUBillItems->setudfvalue("u_amount",$amount2);
				$totalamount+=$amount2;
				$objLGUBillItems->privatedata["header"] = $objLGUBills;
				$actionReturn = $objLGUBillItems->add();
				if ($actionReturn) {
					$objLGUBills->setudfvalue("u_totalamount",$totalamount);
					$objLGUBills->setudfvalue("u_dueamount",$totalamount);
					$actionReturn = $objLGUBills->add();
				}
				if (!$actionReturn) break;
				//var_dump(array($objLGUBills->getudfvalue("u_duedate"),$totalamount));
			}
		} elseif ($objTable->getudfvalue("u_rightspaymode")=="12M") {
			for ($ctr=1;$ctr<=13;$ctr++) {
				$totalamount=0;
				$objLGUBills->prepareadd();
				$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
				$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
				$objLGUBills->docstatus = "O";
				$objLGUBills->setudfvalue("u_profitcenter","PMR");
				$objLGUBills->setudfvalue("u_module","Public Market Rental");
				$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
				$objLGUBills->setudfvalue("u_appno",$objTable->docno);
				$objLGUBills->setudfvalue("u_custno",$custno);
				$objLGUBills->setudfvalue("u_custname",$custname);
				if ($ctr==1) {
					$objLGUBills->setudfvalue("u_docdate",$docdate);
					$objLGUBills->setudfvalue("u_duedate",$docdate);
				} else {
					$objLGUBills->setudfvalue("u_docdate",getmonthstartDB(dateadd("m",$month,$docdate)));
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_PMRDUEDAY"]-1,$objLGUBills->getudfvalue("u_docdate")));
				}					
				$month++;
				$amount2 = round($amount/12,2);
				//$objLGUBills->setudfvalue("u_duedate",date('Y')."-".str_pad( $month, 2, "0", STR_PAD_LEFT)."-20");
				//$objLGUBills->setudfvalue("u_remarks","Public Market Rental - ".date('M, Y',strtotime($objLGUBills->getudfvalue("u_docdate")) ));
				$objLGUBills->setudfvalue("u_remarks","Public Market Rental - ".date('M, Y',strtotime($objLGUBills->getudfvalue("u_docdate")))." (".$objTable->getudfvalue("u_publicmarket")."-".$objTable->getudfvalue("u_stallno").")");
				$objLGUBillItems->prepareadd();
				$objLGUBillItems->docid = $objLGUBills->docid;
				$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
				$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
				$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
				$objLGUBillItems->setudfvalue("u_amount",$amount2);
				$totalamount+=$amount2;
				$objLGUBillItems->privatedata["header"] = $objLGUBills;
				$actionReturn = $objLGUBillItems->add();
				if ($actionReturn) {
					$objLGUBills->setudfvalue("u_totalamount",$totalamount);
					$objLGUBills->setudfvalue("u_dueamount",$totalamount);
					$actionReturn = $objLGUBills->add();
				}
				if (!$actionReturn) break;
				//var_dump(array($objLGUBills->getudfvalue("u_duedate"),$totalamount));
			}
		} else {
			$amount2 = $amount;
			$totalamount=0;
			$objLGUBills->prepareadd();
			$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
			$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
			$objLGUBills->docstatus = "O";
			$objLGUBills->setudfvalue("u_profitcenter","PMR");
			$objLGUBills->setudfvalue("u_module","Public Market Rental");
			$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
			$objLGUBills->setudfvalue("u_appno",$objTable->docno);
			$objLGUBills->setudfvalue("u_custno",$custno);
			$objLGUBills->setudfvalue("u_custname",$custname);
			$objLGUBills->setudfvalue("u_docdate",$docdate);
			$objLGUBills->setudfvalue("u_duedate",$docdate);
			$objLGUBills->setudfvalue("u_remarks","Public Market Rental - ".date('M, Y',strtotime($objLGUBills->getudfvalue("u_docdate")))." (".$objTable->getudfvalue("u_publicmarket")."-".$objTable->getudfvalue("u_stallno").")");
			$objLGUBillItems->prepareadd();
			$objLGUBillItems->docid = $objLGUBills->docid;
			$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
			$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
			$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
			$objLGUBillItems->setudfvalue("u_amount",$amount2);
			$totalamount+=$amount2;
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if ($actionReturn) {
				$objLGUBills->setudfvalue("u_totalamount",$totalamount);
				$objLGUBills->setudfvalue("u_dueamount",$totalamount);
				$actionReturn = $objLGUBills->add();
			}
		}			
	}
	
	if (!$actionReturn) return $actionReturn;
	//return raiseError("onCustomEventcreatebilldocumentschema_brGPSPMRS");
	
	$ctr=0;
	$div=1;
	$month=0;
	/*if ($objTable->getudfvalue("u_paymode")=="M") {
		if ($objTable->getudfvalue("u_apptype")=="NEW") {
			$month = intval(date('m',strtotime($objTable->getudfvalue("u_decisiondate"))))-1;
		} else {
			//$month = intval(date('m',strtotime($objTable->getudfvalue("u_prevexpdate"))));
		}			
		$div=12;
	}*/
	//var_dump(array($docdate));
	while (true) {
		$ctr++;
		if ($ctr==1) {
			$docdate2 = $docdate;
			$duedate2 = $docdate;
			$month++;
		} else {
                   $docdate2 = dateadd("m",$month,getmonthstartDB($docdate));
                   $duedate2 = dateadd("d",$objRs_uLGUSetup->fields["U_PMRDUEDAY"]-1,$docdate2);
			$month++;
		}					
		
//                if (!$objLGUBills->getbysql("U_DOCDATE='$docdate2' AND U_CUSTNO='$custno' AND DOCSTATUS='O'")) {
			$objLGUBills->prepareadd();
			$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
			$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
			$objLGUBills->docstatus = "O";
			$objLGUBills->setudfvalue("u_profitcenter","PMR");
			$objLGUBills->setudfvalue("u_module","Public Market Rental");
			$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
			$objLGUBills->setudfvalue("u_appno",$objTable->docno);
			$objLGUBills->setudfvalue("u_custno",$custno);
			$objLGUBills->setudfvalue("u_custname",$custname);
			//if ($objTable->getudfvalue("u_apptype")=="RENEW") {
			/*	switch ($ctr) {
					case 1:
						$objLGUBills->setudfvalue("u_docdate",date('Y')."-01-01");
						$objLGUBills->setudfvalue("u_duedate",date('Y')."-01-20");
						if ($objTable->getudfvalue("u_paymode")=="S") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - 1st Semi-Annual, ".date('Y') );
						elseif ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q1, ".date('Y') );
						break;
					case 2:
						$objLGUBills->setudfvalue("u_docdate",date('Y')."-04-01");
						$objLGUBills->setudfvalue("u_duedate",date('Y')."-04-20");
						if ($objTable->getudfvalue("u_paymode")=="S") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - 2nd Semi-Annual, ".date('Y') );
						elseif ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q2, ".date('Y') );
						break;
					case 3:
						$objLGUBills->setudfvalue("u_docdate",date('Y')."-07-01");
						$objLGUBills->setudfvalue("u_duedate",date('Y')."-07-20");
						if ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q3, ".date('Y') );
						break;
					case 4:
						$objLGUBills->setudfvalue("u_docdate",date('Y')."-10-01");
						$objLGUBills->setudfvalue("u_duedate",date('Y')."-10-20");
						if ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q4, ".date('Y') );
						break;
				}*/
			//} else {
			//	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
			//	$objLGUBills->setudfvalue("u_duedate",dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
			//}			
			$objLGUBills->setudfvalue("u_docdate",$docdate2);
			$objLGUBills->setudfvalue("u_duedate",$duedate2);
			$objTable->setudfvalue("u_expdate", getmonthendDB($duedate2));
	
			if ($objTable->getudfvalue("u_paymode")=="A") $objLGUBills->setudfvalue("u_remarks","Public Market Rental - ".date('Y') );
			else if ($objTable->getudfvalue("u_paymode")=="M") $objLGUBills->setudfvalue("u_remarks","Public Market Rental - ".date('M, Y',strtotime($objLGUBills->getudfvalue("u_docdate")) ));
			$totalamount=0;
//		} else $totalamount = $objLGUBills->getudfvalue("u_totalamount");	
		
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, IFNULL(B.U_INSTALLMENT,0) AS U_INSTALLMENT FROM U_PMRAPPFEES A LEFT JOIN U_PMRFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND A.U_FEECODE<>'".$objRs_uLGUSetup->fields["U_PMRRIGHTSFEE"]."' AND  A.U_AMOUNT>0");
		while ($objRs->queryfetchrow("NAME")) {
			if ($ctr>1 && $objRs->fields["U_INSTALLMENT"]==0) continue;
			$objLGUBillItems->prepareadd();
			$objLGUBillItems->docid = $objLGUBills->docid;
			$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
			$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
			$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
			//if ($objTable->getudfvalue("u_paymode")=="S" && $ctr==2) {
			//	$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"] - round($objRs->fields["U_AMOUNT"]/$div,2)*($ctr-1));
			//} elseif ($objTable->getudfvalue("u_paymode")=="Q" && $ctr==4) {
			//	$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"] - round($objRs->fields["U_AMOUNT"]/$div,2)*($ctr-1));
			//} else {
			//	if ($objRs->fields["U_INSTALLMENT"]==0) {
					$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
			//	} else {
			//		$objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
			//	}	
			//}	
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
			//$totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
			$totalamount+=round($objRs->fields["U_AMOUNT"],2);
		}
		if ($actionReturn) {
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
			if ($objLGUBills->rowstat=="N") $actionReturn = $objLGUBills->add();
			else $actionReturn = $objLGUBills->update($objLGUBills->docno,$objLGUBills->rcdversion);
		}
		if (!$actionReturn) break;
                
                if($objRs_uLGUSetup->fields["U_PMRFISCAL"]==1){
                    if ($objTable->getudfvalue("u_paymode")=="A" && $ctr==1) break;
                    elseif ($objTable->getudfvalue("u_paymode")=="M" && $ctr==12) break;
                }else{
                     if ($objTable->getudfvalue("u_paymode")=="A" && $ctr==1) break;
                     elseif ($objTable->getudfvalue("u_paymode")=="M" && $ctr==$calendarmonth) break;
                }
		
		//elseif ($objTable->getudfvalue("u_paymode")=="Q" && $ctr==4) break;
	}	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatepmrmddocumentschema_brGPSPMRS()");
	//var_dump($objTable->getudfvalue("u_expdate"));
	return $actionReturn;
}

function onCustomEventupdatepmrmddocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset (NULL,$objConnection);
	$objPMRMDs = new masterdataschema_br(null,$objConnection,"u_pmrmds");

	$objTable->setudfvalue("u_year", substr($objTable->getudfvalue("u_appdate"),0,4));
	$actionReturn = $objPMRMDs->getbykey($objTable->getudfvalue("u_publicmarket")."-".$objTable->getudfvalue("u_stallno"));
	if (!$actionReturn) { //&& $objTable->getudfvalue("u_apptype")=="NEW")
		$objPMRMDs->prepareadd();
		$objPMRMDs->code = $objTable->getudfvalue("u_publicmarket")."-".$objTable->getudfvalue("u_stallno");
	}
	
	//$objPMRMDs->name = $objTable->getudfvalue("u_businessname");
	//$objPMRMDs->setudfvalue("u_tradename", $objTable->getudfvalue("u_tradename"));
	$objPMRMDs->setudfvalue("u_lastname", $objTable->getudfvalue("u_lastname"));
	$objPMRMDs->setudfvalue("u_firstname", $objTable->getudfvalue("u_firstname"));
	$objPMRMDs->setudfvalue("u_middlename", $objTable->getudfvalue("u_middlename"));
	$objPMRMDs->setudfvalue("u_apprefno", $objTable->docno);
	$objPMRMDs->setudfvalue("u_appdate", $objTable->getudfvalue("u_appdate"));
	$objPMRMDs->setudfvalue("u_expdate", $objTable->getudfvalue("u_expdate"));
	$objPMRMDs->setudfvalue("u_year", $objTable->getudfvalue("u_year"));
	if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
		$objPMRMDs->setudfvalue("u_regdate",currentdateDB());
	}	
	if ($objTable->getudfvalue("u_firstyear")=="") {
		$objPMRMDs->setudfvalue("u_firstyear",date('Y'));
	}

	if ($objPMRMDs->rowstat=="N") $actionReturn = $objPMRMDs->add();
	else $actionReturn = $objPMRMDs->update($objPMRMDs->code,$objPMRMDs->rcdversion);
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatepmrmddocumentschema_brGPSPMRS()");
	return $actionReturn;
}

/*
function onUpdateEventdocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/


function onCustomEventcreateformultibilldocumentschema_brGPSPMRS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	$ctr=0;
	$div=1;
	/*if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;*/

	$objRsu_LGUSetup = new recordset(null,$objConnection);
	$objRsu_LGUSetup->queryopen("select A.U_PMRDUEDAY from U_LGUSETUP A");
	if (!$objRsu_LGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for market due day.");
	}	
	$objLGUBills->prepareadd();
	$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
	$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
	$objLGUBills->docstatus = "O";
	$objLGUBills->setudfvalue("u_profitcenter","PMR");
	$objLGUBills->setudfvalue("u_module","Public Market Rental");
	$objLGUBills->setudfvalue("u_pmrpays",1);
        $objLGUBills->setudfvalue("u_appno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_custname"));
	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_date"));
	
        $startdate = getmonthstartDB($objTable->getudfvalue("u_date"));
	$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRsu_LGUSetup->fields["U_PMRDUEDAY"]-1,$startdate));
        //$objLGUBills->setudfvalue("u_remarks","MTOP - ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
	//$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
//	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_MTOPAPPFEES A where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$objRs->queryopen("SELECT U_FEECODE,U_AMOUNT,U_FEEDESC,U_REMARKS FROM (SELECT b.u_itemcode AS U_FEECODE,sum(b.u_amount) AS U_AMOUNT,b.u_itemdesc AS U_FEEDESC,CONCAT('Public Market Rental - ',MONTHNAME(MIN(U_DOCDATE)),'-',MONTHNAME(MAX(U_DOCDATE)),', ','".$objTable->getudfvalue("u_date")."') AS U_REMARKS FROM u_lgubills A INNER JOIN  u_lgubillitems b ON a.docid = b.docid AND a.company = b.company AND a.branch = b.branch WHERE a.docno IN(SELECT B.U_BILLNO FROM U_PMRPAY A INNER JOIN U_PMRPAYITEMS B ON A.DOCID = B.DOCID WHERE A.DOCNO = '$objTable->docno' AND B.U_SELECTED = 1 ) AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND b.u_amount <> 0 GROUP BY b.u_itemcode) AS A;");
	$totalamount=0;
	$remarks='';
        
	while ($objRs->queryfetchrow("NAME")) {
		/*if ($ctr!=1) {
			if ($objRs->fields["U_YEARLY"]!=1) continue;
		}*/	
//		var_dump($objRs->fields);
		$objLGUBillItems->prepareadd();
		$objLGUBillItems->docid = $objLGUBills->docid;
		$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
		$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
		$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
		$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
		$objLGUBillItems->privatedata["header"] = $objLGUBills;
		$actionReturn = $objLGUBillItems->add();
		if (!$actionReturn) break;
		$totalamount+=round($objRs->fields["U_AMOUNT"],2);
		$remarks=$objRs->fields["U_REMARKS"];
	}
	if ($actionReturn) {
		$objLGUBills->setudfvalue("u_remarks",$remarks);
		$objLGUBills->setudfvalue("u_totalamount",$totalamount);
		$objLGUBills->setudfvalue("u_dueamount",$totalamount);
		$actionReturn = $objLGUBills->add();
                $actionReturn = $objRs->executesql("UPDATE U_LGUBILLS SET DOCSTATUS = 'CN' WHERE DOCNO IN (SELECT B.U_BILLNO FROM U_PMRPAY A INNER JOIN U_PMRPAYITEMS B ON A.DOCID = B.DOCID WHERE A.DOCNO = '$objTable->docno' AND B.U_SELECTED = 1)",false);
	}


	return $actionReturn;
}


?>