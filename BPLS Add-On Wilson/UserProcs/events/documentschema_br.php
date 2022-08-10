<?php
 
include_once("./classes/usermsgs.php");
require_once("./classes/smsoutlog.php");


function onBeforeAddEventdocumentschema_brGPSBPLS($objTable) {
	global $page;
	global $objConnection;
	$actionReturn = true;
   
	switch ($objTable->dbtable) {
		
		case "u_bplapps":
                      
			if($objTable->getudfvalue("u_appno")=="") {
				if ($objTable->getudfvalue("u_lastname")!="") {
					$prefix = substr(ltrim($objTable->getudfvalue("u_lastname")),0,1);
				} else if ($objTable->getudfvalue("u_corpname")!="") {
					$prefix = substr(ltrim($objTable->getudfvalue("u_corpname")),0,1);
				} else {
					$prefix = substr(ltrim($objTable->getudfvalue("u_tradename")),0,1);
				}
				$acctno = strtoupper($prefix."-".str_pad(getNextIdByBranch("u_bplacctno".$prefix,$objConnection),5,"0",STR_PAD_LEFT));
				$objTable->setudfvalue("u_appno",$acctno);
				$objTable->setudfvalue("u_businessname",$objTable->getudfvalue("u_tradename")." ".$acctno);
			}
			if ($objTable->docstatus=="Assessing") $objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			if ($actionReturn && $objTable->docstatus=="Encoding" && $objTable->getudfvalue("u_preassbill")==1) {
				$actionReturn = onCustomEventcreatepreassbilldocumentschema_brGPSBPLS($objTable);
				if (!$actionReturn) $page->setitem("u_preassbill",0);
			}
			if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
					if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
					$objTable->setudfvalue("u_approveddatetime",date('Y-m-d h:i:s'));
					$objTable->setudfvalue("u_approvedby",$_SESSION["userid"]);
					 /***** For Bacoor  Bill sequence number in Mayor's Permit*****/
					if ($objTable->getudfvalue("u_billseqno") == "") {
						$refno = getNextSeriesNoByBranch("U_BPLBILLSEQNO","",$objTable->getudfvalue("u_decisiondate"));
						$objTable->setudfvalue("u_billseqno",$refno);
					}
					$actionReturn = onCustomEventupdateZoningCLRAppsdocumentschema_brGPSBPLS($objTable,true);
					$actionReturn = onCustomEventupdateBuildingAppsdocumentschema_brGPSBPLS($objTable,true);
			}
			if ($actionReturn) $actionReturn = onCustomEventupdatebplmddocumentschema_brGPSBPLS($objTable);
                       
                        
                        
                break;
                
	}
	return $actionReturn;
}



function onAddEventdocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	global  $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
                    break;
                case "u_bpladdledger":
                    if ($actionReturn) $actionReturn = onCustomEventInsertPaymentHistorydocumentschema_brGPSBPLS($objTable);
                    break;
                case "u_bplupdgross" :
                        if ($actionReturn) $actionReturn = onCustomEventAuditedGrossdocumentschema_brGPSBPLS($objTable);
                    break;
                case "u_bplapps":
                    if ($actionReturn) {
//                            $actionReturn = onCustomEventupdatebplmddocumentschema_brGPSBPLS($objTable,true);
                            $objRs_uLGUSetup = new recordset(null,$objConnection);
                            $objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE,A.U_bplcombinereqassessment from U_LGUSETUP A");
                            if (!$objRs_uLGUSetup->queryfetchrow("NAME")) return raiseError("No setup found for municipality and province found.");
                            
                                /***** Approved Business Application *****/
//                                var_dump($objTable->fields["DOCSTATUS"]);
//                                var_dump($objTable->docstatus=="Approved");
//                                return false;
				if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
                                    if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
                                    
                                     /***** For Bacoor  Bill sequence number in Mayor's Permit*****/
                                    if ($objTable->getudfvalue("u_billseqno") == "") {
                                        $refno = getNextSeriesNoByBranch("U_BPLBILLSEQNO","",$objTable->getudfvalue("u_decisiondate"));
                                        $objTable->setudfvalue("u_billseqno",$refno);
                                    }
                                        if($objRs_uLGUSetup->fields["U_bplcombinereqassessment"]==1){
                                            $objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                            while(true){
                                                if ($objLGUBills->getbysql("u_custno='".$objTable->getudfvalue("u_appno")."' AND DOCSTATUS = 'O' AND U_PREASSBILL=0 AND u_bplupdatebill = 0 AND U_MODULE IN('Business Permit','Business Permit(Fire Dept)')")) {
                                                        $objLGUBills->docstatus =  "CN";
                                                        $actionReturn = $objLGUBills->update($objLGUBills->docno,$objLGUBills->rcdversion);
                                                } else {
                                                        break;
                                                }
                                                if(!$actionReturn)  break;
                                            }
                                            $actionReturn = onCustomEventcreatebillCombineAppandReqdocumentschema_brGPSBPLS($objTable);
                                            $actionReturn = onCustomEventcreatebillforEnvironmentaldocumentschema_brGPSBPLS($objTable);
                                            
                                        }else{
                                            $actionReturn = onCustomEventcreatebilldocumentschema_brGPSBPLS($objTable);
                                            $actionReturn = onCustomEventcreateReqAppBilldocumentschema_brGPSBPLS($objTable);
                                            $actionReturn = onCustomEventcreatebillforEnvironmentaldocumentschema_brGPSBPLS($objTable);
                                        }
					$actionReturn = onCustomEventcreatefirebilldocumentschema_brGPSBPLS($objTable);
                                   
				} elseif ($objTable->docstatus=="Disapproved" && $objTable->fields["DOCSTATUS"]=="Encoding") {
                                    if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
					if ($actionReturn) {
                                            $objSMSOutLog = new smsoutlog(null,$objConnection);
                                            $objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
                                            $objSMSOutLog->deviceid = "sun";
                                            $objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
                                            $objSMSOutLog->remark = "Business Permit";
                                            $objSMSOutLog->message = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\n Business Permit App No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was disapproved.";
                                            $actionReturn = $objSMSOutLog->add();
					}
				}	
			}
                    break;

	}
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	global  $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
                case "u_bplupdateapps":
                    if ($actionReturn) {
//				$actionReturn = onCustomEventupdatebplmddocumentschema_brGPSBPLS($objTable,true);
				if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]=="Encoding") {
					if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
					$actionReturn = onCustomEventcreatebillforupdatedocumentschema_brGPSBPLS($objTable);
				} 	
			}
                        
			if ($objTable->docstatus=="Encoding" && $objTable->fields["DOCSTATUS"]=="Approved" ) {
			
				$objTable->docstatus="Encoding";
				if ($actionReturn && $objTable->fields["DOCSTATUS"]=="Approved") {
//                                    echo 'wilson';
					$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                        while(true){
					if ($objLGUBills->getbysql("DOCSTATUS = 'O' AND U_SETTLEDAMOUNT = 0 AND U_APPNO='".$objTable->getudfvalue("u_appno")."' AND U_PREASSBILL=0 AND u_bplupdatebill = 1 AND U_MODULE='Business Permit'")) {
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
			break;
                    
		case "u_bplapps":
			if ($objTable->docstatus=="Assessing" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assesseddatetime",date('Y-m-d h:i:s'));
				$objTable->setudfvalue("u_assessedby",$_SESSION["userid"]);
			} elseif ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_approveddatetime",date('Y-m-d h:i:s'));
				$objTable->setudfvalue("u_approvedby",$_SESSION["userid"]);
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_disapproveddatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Printing" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_paiddatetime",date('Y-m-d h:i:s'));
			} 		
			if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
                                    if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
                                    
                                     /***** For Bacoor  Bill sequence number in Mayor's Permit*****/
                                    if ($objTable->getudfvalue("u_billseqno") == "") {
                                        $refno = getNextSeriesNoByBranch("U_BPLBILLSEQNO","",$objTable->getudfvalue("u_decisiondate"));
                                        $objTable->setudfvalue("u_billseqno",$refno);
                                    }
                        }
                        /***** Re-Assessment*****/
			if  (($objTable->docstatus=="Encoding" && $objTable->getudfvalue("u_approverremarks")!=$objTable->fields["U_APPROVERREMARKS"]) || 
                            ($objTable->docstatus=="Encoding" && ($objTable->fields["DOCSTATUS"]=="Approved" || $objTable->fields["DOCSTATUS"]=="Disapproved"))) { 
				
                                /***** Notify assessing personel if the appliation was not approved or for reassessment *****/
                                $msgid = date('Y-m-d H:i:s') . ".BPLApproverRemarks." . $objRs->fields["USERID"] . " " .$_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $_SERVER["REMOTE_ADDR"];
				$objUserMsgs =  new usermsgs(null,$objConnection);
				$objUserMsgs->prepareadd();
				$objUserMsgs->msgid = $msgid;
				$objUserMsgs->userid = $objTable->getudfvalue("u_assessedby");
				$objUserMsgs->msgfrom = $_SESSION["userid"];
				$objUserMsgs->msgtype = "IBX";
				$objUserMsgs->msgsubtype = "RA";  
				$objUserMsgs->priority=1;
				$objUserMsgs->msgsubject = "Business permit approver remarks for application no. ".$objTable->docno."." ;
				$objUserMsgs->msgbody = $objTable->getudfvalue("u_approverremarks");
				$objUserMsgs->status = "U";
				$objUserMsgs->msgdate = date('Y-m-d');
				$objUserMsgs->msgtime = date('H:i:s');
				$actionReturn = $objUserMsgs->add();
				$objTable->docstatus="Encoding";
                                
				if ($actionReturn && $objTable->fields["DOCSTATUS"]=="Approved") {
                                    $objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                        while(true){
                                            if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND DOCSTATUS = 'O' AND U_PREASSBILL=0 AND u_bplupdatebill = 0 AND U_MODULE IN('Business Permit','Business Permit(Fire Dept)')")) {
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
                        if ($objTable->docstatus=="Encoding" && $objTable->getudfvalue("u_preassbill") == 0 && $objTable->fields["U_PREASSBILL"] == 1){
				$msgid = date('Y-m-d H:i:s') . ".BPLPreassessment." . $objRs->fields["USERID"] . " " .$_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $_SERVER["REMOTE_ADDR"];
				$objUserMsgs =  new usermsgs(null,$objConnection);
				$objUserMsgs->prepareadd();
				$objUserMsgs->msgid = $msgid;
				$objUserMsgs->userid = $objTable->createdby;
				$objUserMsgs->msgfrom = $_SESSION["userid"];
				$objUserMsgs->msgtype = "IBX";
				$objUserMsgs->msgsubtype = "RA";  
				$objUserMsgs->priority=1;
				$objUserMsgs->msgsubject = "Business permit re-assessmnet for pre-assment bill." ;
				$objUserMsgs->msgbody = "Application no. ".$objTable->docno."";
				$objUserMsgs->status = "U";
				$objUserMsgs->msgdate = date('Y-m-d');
				$objUserMsgs->msgtime = date('H:i:s');
				$actionReturn = $objUserMsgs->add();
				//$objTable->docstatus="Assessing";
				if ($actionReturn){
					$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                   //     while(true){
					if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND U_PREASSBILL = 1 AND U_MODULE='Business Permit'")) {
						$actionReturn = $objLGUBills->executesql("DELETE FROM U_LGUBILLITEMS WHERE COMPANY='$objLGUBills->company' AND BRANCH='$objLGUBills->branch' AND DOCID='$objLGUBills->docid'",true);
						if ($actionReturn) $actionReturn = $objLGUBills->delete();
					} else {
						//$page->docstatus=$objTable->fields["DOCSTATUS"];
                                                break;
						return raiseError("Unable to find Bill Document to remove.");
					}	
                                       // if(!$actionReturn)  break;

                                 //   }
                                }
			}
			if ($actionReturn && $objTable->docstatus=="Encoding" && $objTable->fields["U_PREASSBILL"]==0 && $objTable->getudfvalue("u_preassbill")==1) {
				$actionReturn = onCustomEventcreatepreassbilldocumentschema_brGPSBPLS($objTable);
				if (!$actionReturn) $page->setitem("u_preassbill",0);
			}	
			if ($actionReturn) {
                            
                            $actionReturn = onCustomEventupdatebplmddocumentschema_brGPSBPLS($objTable,true);
                            $actionReturn = onCustomEventupdateZoningCLRAppsdocumentschema_brGPSBPLS($objTable,true);
                            $actionReturn = onCustomEventupdateBuildingAppsdocumentschema_brGPSBPLS($objTable,true);
                            $objRs_uLGUSetup = new recordset(null,$objConnection);
                            $objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE,A.U_bplcombinereqassessment from U_LGUSETUP A");
                            if (!$objRs_uLGUSetup->queryfetchrow("NAME")) return raiseError("No setup found for municipality and province found.");
                            
                                /***** Approved Business Application *****/
				if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]=="Encoding") {
                                    if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
                                    
                                     /***** For Bacoor  Bill sequence number in Mayor's Permit*****/
                                    if ($objTable->getudfvalue("u_billseqno") == "") {
                                        $refno = getNextSeriesNoByBranch("U_BPLBILLSEQNO","",$objTable->getudfvalue("u_decisiondate"));
                                        $objTable->setudfvalue("u_billseqno",$refno);
                                    }
                                        if($objRs_uLGUSetup->fields["U_bplcombinereqassessment"]==1){
                                            $objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                            while(true){
                                                if ($objLGUBills->getbysql("u_custno='".$objTable->getudfvalue("u_appno")."' AND DOCSTATUS = 'O' AND U_PREASSBILL=0 AND u_bplupdatebill = 0 AND U_MODULE IN('Business Permit','Business Permit(Fire Dept)')")) {
                                                        $objLGUBills->docstatus =  "CN";
                                                        $actionReturn = $objLGUBills->update($objLGUBills->docno,$objLGUBills->rcdversion);
                                                } else {
                                                        break;
                                                }
                                                if(!$actionReturn)  break;
                                            }
                                            $actionReturn = onCustomEventcreatebillCombineAppandReqdocumentschema_brGPSBPLS($objTable);
                                            $actionReturn = onCustomEventcreatebillforEnvironmentaldocumentschema_brGPSBPLS($objTable);
                                            
                                        }else{
                                            $actionReturn = onCustomEventcreatebilldocumentschema_brGPSBPLS($objTable);
                                            $actionReturn = onCustomEventcreateReqAppBilldocumentschema_brGPSBPLS($objTable);
                                            $actionReturn = onCustomEventcreatebillforEnvironmentaldocumentschema_brGPSBPLS($objTable);
                                        }
					$actionReturn = onCustomEventcreatefirebilldocumentschema_brGPSBPLS($objTable);
                                   
				} elseif ($objTable->docstatus=="Disapproved" && $objTable->fields["DOCSTATUS"]=="Encoding") {
                                    if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
					if ($actionReturn) {
                                            $objSMSOutLog = new smsoutlog(null,$objConnection);
                                            $objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
                                            $objSMSOutLog->deviceid = "sun";
                                            $objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
                                            $objSMSOutLog->remark = "Business Permit";
                                            $objSMSOutLog->message = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\n Business Permit App No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was disapproved.";
                                            $actionReturn = $objSMSOutLog->add();
					}
				}	
			}	
			break;
	}
	return $actionReturn;
}


function onUpdateEventdocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_bplapps":
                        if ($objTable->docstatus=="Delete" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				if ($actionReturn) $actionReturn = $objTable->delete();
			}
			break;
	}
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSBPLS($objTable) {
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
function onDeleteEventdocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

function onCustomEventcreatepreassbilldocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}	

	$objLGUBills->prepareadd();
	$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
	$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
	$objLGUBills->docstatus = "O";
	$objLGUBills->setudfvalue("u_profitcenter","BPL");
	$objLGUBills->setudfvalue("u_module","Business Permit");
	$objLGUBills->setudfvalue("u_paymode","A");
	$objLGUBills->setudfvalue("u_preassbill",1);
	$objLGUBills->setudfvalue("u_appno",$objTable->docno);
	if ($objTable->getudfvalue("u_apptype")=="NEW") $objLGUBills->setudfvalue("u_custno",$objTable->docno);
	elseif ($objTable->getudfvalue("u_bpno")!="") $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_bpno"));
	else $objLGUBills->setudfvalue("u_custno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_appdate"));
	$startdate = getmonthstartDB($objTable->getudfvalue("u_appdate"));
	$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
//	$objLGUBills->setudfvalue("u_duedate",$objTable->getudfvalue("u_appdate"));
	$objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
	$objLGUBills->setudfvalue("u_alertmobileno",$objTable->getudfvalue("u_alertmobileno"));
	$totalamount=0;

	$objRs->queryopen("SELECT A.U_REQDESC, A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_BPLAPPREQ2S A LEFT JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0");
	while ($objRs->queryfetchrow("NAME")) {
		if ($objRs->fields["U_FEECODE"]=="") {
			return raiseError("Fee Code must define for Requirement [".$objRs->fields["U_REQDESC"]."].");
		}
		//var_dump($objRs->fields);
		$objLGUBillItems->prepareadd();
		$objLGUBillItems->docid = $objLGUBills->docid;
		$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
		$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
		$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
		$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
		$totalamount+=$objRs->fields["U_AMOUNT"];
		$objLGUBillItems->privatedata["header"] = $objLGUBills;
		$actionReturn = $objLGUBillItems->add();
		if (!$actionReturn) break;
	}
	if ($actionReturn) {
		$objRs->queryopen("SELECT A.U_REQDESC, A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_BPLAPPREQ3S A LEFT JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0");
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["U_FEECODE"]=="") {
				return raiseError("Fee Code must define for Requirement [".$objRs->fields["U_REQDESC"]."].");
			}
			//var_dump($objRs->fields);
			$objLGUBillItems->prepareadd();
			$objLGUBillItems->docid = $objLGUBills->docid;
			$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
			$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
			$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
			$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
			$totalamount+=$objRs->fields["U_AMOUNT"];
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn) {
		$objLGUBills->setudfvalue("u_totalamount",$totalamount);
		$objLGUBills->setudfvalue("u_dueamount",$totalamount);
		$actionReturn = $objLGUBills->add();
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcreatepreassbilldocumentschema_brGPSBPLS()");

	return $actionReturn;
}

function onCustomEventcreatebillforupdatedocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}	

	$ctr=0;
	$div=1;
//	if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
//	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;
	$alertmsg="";
	$alertmsg2="";
	while (true) {
		$ctr++;
		$objLGUBills->prepareadd();
		$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
		$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
		$objLGUBills->docstatus = "O";
		$objLGUBills->setudfvalue("u_profitcenter","BPL");
		$objLGUBills->setudfvalue("u_module","Business Permit");
		$objLGUBills->setudfvalue("u_paymode","A");
		$objLGUBills->setudfvalue("u_bplupdatebill",1);
		$objLGUBills->setudfvalue("u_appno",$objTable->docno);
                $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_appno"));
		$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
                $objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
                $objLGUBills->setudfvalue("u_duedate",$objTable->getudfvalue("u_decisiondate"));
                $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing , ".date('Y') );
				
		$objLGUBills->setudfvalue("u_alertmobileno","");
	               //if ($objTable->getudfvalue("u_apptype")=="RETIRED") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT FROM U_BPLUPDATEAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0");
		$totalamount=0;
		while ($objRs->queryfetchrow("NAME")) {
			//var_dump($objRs->fields);
//			if ($ctr>1 && $objRs->fields["U_INSTALLMENT"]==0) continue;
			$objLGUBillItems->prepareadd();
			$objLGUBillItems->docid = $objLGUBills->docid;
			$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
			$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
			$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
			$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
                        $totalamount+=$objRs->fields["U_AMOUNT"];
//				if ($objRs->fields["U_INSTALLMENT"]==0) {
					
//				} else {
                                    //dito hinahati yung business tax based on paymode
//					$objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
//					$totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
//				}	
			//}	
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
		}
		if ($actionReturn) {
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
			$actionReturn = $objLGUBills->add();
//			if ($objTable->getudfvalue("u_paymode")=="A") {
//				$alertmsg = "Business Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . " on " . date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate"))).".";
//			} elseif ($alertmsg=="") {
//				$alertmsg = "Business Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . ".";
//				$alertmsg2 = "Payment of " . formatNumericAmount($totalamount) . " is due on ". date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate"))).".";
//			}
		}
		if (!$actionReturn) break;
		 break;
//		elseif ($objTable->getudfvalue("u_paymode")=="S" && $ctr==2) break;
//		elseif ($objTable->getudfvalue("u_paymode")=="Q" && $ctr==4) break;
	}
	
//	if ($actionReturn && $alertmsg!="") {
//		$objSMSOutLog = new smsoutlog(null,$objConnection);
//		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
//		$objSMSOutLog->deviceid = "sun";
//		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
//		$objSMSOutLog->remark = "Business Permit";
//		//var_dump(strlen($alertmsg));
//		$objSMSOutLog->message = $alertmsg;
//		$actionReturn = $objSMSOutLog->add();
//		//var_dump($objSMSOutLog->message);
//	}
//	if ($actionReturn && $alertmsg2!="") {
//		$objSMSOutLog = new smsoutlog(null,$objConnection);
//		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
//		$objSMSOutLog->deviceid = "sun";
//		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
//		$objSMSOutLog->remark = "Business Permit";
//		//var_dump(strlen($alertmsg));
//		$objSMSOutLog->message = $alertmsg2;
//		$actionReturn = $objSMSOutLog->add();
//		//var_dump($objSMSOutLog->message);
//	}

	//var_dump($alertmsg);	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatebplmddocumentschema_brGPSBPLS()");

	return $actionReturn;
}

function onCustomEventcreatebillCombineAppandReqdocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE,A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}
	
        $objRs_uBilyears = new recordset(null,$objConnection);
	$objRs_uBilyears->queryopen("SELECT cast(u_year as signed) as u_year FROM U_BPLAPPFEES a where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 GROUP BY A.U_YEAR ");
	while ($objRs_uBilyears->queryfetchrow("NAME")) {
                $ctr=0;
                $div=1;
                if ($objRs_uBilyears->fields["u_year"] == $objTable->getudfvalue("u_year")) {
                    if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
                    elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;
                } else {
                    
                }
                $alertmsg="";
                $alertmsg2="";
                    while (true) {
                        $ctr++;
                        $objLGUBills->prepareadd();
                        $objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
                        $objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
                        $objLGUBills->docstatus = "O";
                        $objLGUBills->setudfvalue("u_profitcenter","BPL");
                        $objLGUBills->setudfvalue("u_module","Business Permit");
                        if ($objRs_uBilyears->fields["u_year"] == $objTable->getudfvalue("u_year")) $objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
                        else $objLGUBills->setudfvalue("u_paymode","A");
                        $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_appno"));
                        $objLGUBills->setudfvalue("u_appno",$objTable->docno);
                        $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
                        //if ($objTable->getudfvalue("u_apptype")=="RENEW") {
			switch ($ctr) {
				case 1:
                                        if ($objTable->getudfvalue("u_paymode")=="A"){
                                            $objLGUBills->setudfvalue("u_payqtr",4);
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".$objRs_uBilyears->fields["u_year"] );
                                        } else if ($objTable->getudfvalue("u_paymode")=="S"){
                                            $objLGUBills->setudfvalue("u_payqtr",2);
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - 1st Semi-Annual, ".$objRs_uBilyears->fields["u_year"] );
                                        } else {
                                            $objLGUBills->setudfvalue("u_payqtr",1);
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q1, ".$objRs_uBilyears->fields["u_year"] );
                                        }
                                        if ($objRs_uBilyears->fields["u_year"] != $objTable->getudfvalue("u_year")) {
                                             $objLGUBills->setudfvalue("u_payqtr",4);
                                             $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".$objRs_uBilyears->fields["u_year"] );
                                        }
                                        
                                        $objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
                                        $objLGUBills->setudfvalue("u_duedate",date('Y')."-12-31");
                                        break;
				case 2:
					$objLGUBills->setudfvalue("u_docdate",$objRs_uBilyears->fields["u_year"]."-04-01");
					$startdate = getmonthstartDB($objRs_uBilyears->fields["u_year"]."-04-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                                if ($objTable->getudfvalue("u_paymode")=="S") {
                                                        $objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,getmonthstartDB($objRs_uBilyears->fields["u_year"]."-07-01")));
                                                        $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - 2nd Semi-Annual, ".$objRs_uBilyears->fields["u_year"] );
                                                        $objLGUBills->setudfvalue("u_payqtr",4);
                                                }  elseif ($objTable->getudfvalue("u_paymode")=="Q")  {
                                                        $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q2, ".$objRs_uBilyears->fields["u_year"] );
                                                        $objLGUBills->setudfvalue("u_payqtr",2);
                                                }
					break;
				case 3:
					$objLGUBills->setudfvalue("u_docdate",$objRs_uBilyears->fields["u_year"]."-07-01");
					$startdate = getmonthstartDB($objRs_uBilyears->fields["u_year"]."-07-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        $objLGUBills->setudfvalue("u_payqtr",3);
					$objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q3, ".$objRs_uBilyears->fields["u_year"] );
					break;
				case 4:
					$objLGUBills->setudfvalue("u_docdate",$objRs_uBilyears->fields["u_year"]."-10-01");
					$startdate = getmonthstartDB($objRs_uBilyears->fields["u_year"]."-10-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        $objLGUBills->setudfvalue("u_payqtr",4);
					$objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q4, ".$objRs_uBilyears->fields["u_year"] );
					break;
			}	
		
                        $objLGUBills->setudfvalue("u_alertmobileno",$objTable->getudfvalue("u_alertmobileno"));
                               //if ($objTable->getudfvalue("u_apptype")=="RETIRED") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
                        $objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT,0 AS U_PENALTY,U_TAXBASE,U_BUSINESSLINE,U_SEQNO FROM U_BPLAPPFEES A
                                            INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE 
                                            WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 AND B.U_ENVIRONMENTAL = 0 AND A.U_YEAR = '".$objRs_uBilyears->fields["u_year"]."'
                                            UNION ALL 
                                            SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT,0 AS U_PENALTY,0 AS U_TAXBASE,'' AS U_BUSINESSLINE,U_SEQNO  FROM U_BPLREQAPPFEES A
                                            INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE
                                            WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 AND B.U_ENVIRONMENTAL = 0
                                            UNION ALL
                                            SELECT B.U_PENALTYCODE AS U_FEECODE,B.U_PENALTYDESC AS U_FEEDESC,(A.U_SURCHARGE + A.U_INTEREST) AS U_AMOUNT, 0 AS U_INSTALLMENT,1 AS U_PENALTY,U_TAXBASE,U_BUSINESSLINE,U_SEQNO  FROM U_BPLAPPFEES A
                                            INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE
                                            WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  (A.U_SURCHARGE + A.U_INTEREST) >0 AND B.U_ENVIRONMENTAL = 0 AND A.U_YEAR = '".$objRs_uBilyears->fields["u_year"]."'");
                        $totalamount=0;
                        while ($objRs->queryfetchrow("NAME")) {
                                if ($ctr>1 && $objRs->fields["U_INSTALLMENT"]==0) continue;
                                
                                $objLGUBillItems->prepareadd();
                                $objLGUBillItems->docid = $objLGUBills->docid;
                                $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                $objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
                                $objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
                                $objLGUBillItems->setudfvalue("u_penalty",$objRs->fields["U_PENALTY"]);
                                $objLGUBillItems->setudfvalue("u_taxbase",$objRs->fields["U_TAXBASE"]);
                                $objLGUBillItems->setudfvalue("u_seqno",$objRs->fields["U_SEQNO"]);
                                $objLGUBillItems->setudfvalue("u_businessline",$objRs->fields["U_BUSINESSLINE"]);
                                    if ($objRs->fields["U_INSTALLMENT"]==0) {
                                            $objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
                                            $totalamount+=$objRs->fields["U_AMOUNT"];
                                    } else {
                                            $objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
                                            $totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
                                    }	
                                $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                $actionReturn = $objLGUBillItems->add();
                                if (!$actionReturn) break;
                        }
                        if ($actionReturn && $totalamount > 0) {
                                $objLGUBills->setudfvalue("u_totalamount",$totalamount);
                                $objLGUBills->setudfvalue("u_dueamount",$totalamount);
                                $actionReturn = $objLGUBills->add();
                                if ($objTable->getudfvalue("u_paymode")=="A") {
                                        $alertmsg = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\nBusiness Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . " on " . date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate")))."";
                                } elseif ($alertmsg=="") {
                                        $alertmsg =  "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\nBusiness Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . "";
                                        $alertmsg2 = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\nPayment of " . formatNumericAmount($totalamount) . " is due on ". date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate"))).".\n\r This is an automated message \n\r Please dont reply";
                                }
                        }
                        if (!$actionReturn) break;
                        if ($objRs_uBilyears->fields["u_year"] != $objTable->getudfvalue("u_year") && $ctr==1) break;
                        if ($objTable->getudfvalue("u_paymode")=="A" && $ctr==1) break;
                        elseif ($objTable->getudfvalue("u_paymode")=="S" && $ctr==2) break;
                        elseif ($objTable->getudfvalue("u_paymode")=="Q" && $ctr==4) break;
                }
	}
        
	

	if ($actionReturn && $alertmsg!="" && strlen($objTable->getudfvalue("u_alertmobileno"))==11) {

		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
		$objSMSOutLog->deviceid = "sun";
		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
		$objSMSOutLog->remark = "Business Permit";
		//var_dump(strlen($alertmsg));
		$objSMSOutLog->message = $alertmsg;
		$actionReturn = $objSMSOutLog->add();
		//var_dump($objSMSOutLog->message);
	}
	if ($actionReturn && $alertmsg2!="" && strlen($objTable->getudfvalue("u_alertmobileno"))==11) {
	
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
		$objSMSOutLog->deviceid = "sun";
		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
		$objSMSOutLog->remark = "Business Permit";
		//var_dump(strlen($alertmsg));
		$objSMSOutLog->message = $alertmsg2;
		$actionReturn = $objSMSOutLog->add();
		//var_dump($objSMSOutLog->message);
	}

	//var_dump($alertmsg);	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatebplmddocumentschema_brGPSBPLS()");

	return $actionReturn;
}
function onCustomEventcreatebillforEnvironmentaldocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE,A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}
        
	
        
        $objRs_uBilyears = new recordset(null,$objConnection);
	$objRs_uBilyears->queryopen("SELECT u_year FROM U_BPLAPPFEES a INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0  AND B.U_ENVIRONMENTAL = 1 GROUP BY A.U_YEAR ");
	while ($objRs_uBilyears->queryfetchrow("NAME")) {
                $ctr=0;
                $alertmsg="";
                $alertmsg2="";
                
                $div=1;
                if ($objRs_uBilyears->fields["u_year"] == $objTable->getudfvalue("u_year")) {
                    if ($objTable->getudfvalue("u_envpaymode")=="S") $div=2;
                    elseif ($objTable->getudfvalue("u_envpaymode")=="Q") $div=4;
                }
                while (true) {
                    $ctr++;
                    $objLGUBills->prepareadd();
                    $objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
                    $objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
                    $objLGUBills->docstatus = "O";
                    $objLGUBills->setudfvalue("u_profitcenter","BPL");
                    $objLGUBills->setudfvalue("u_module","Business Permit");
                    if ($objRs_uBilyears->fields["u_year"] == $objTable->getudfvalue("u_year")) $objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_envpaymode"));
                    else $objLGUBills->setudfvalue("u_paymode","A");
                    $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_appno"));
                    $objLGUBills->setudfvalue("u_appno",$objTable->docno);
                    $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
			switch ($ctr) {
				case 1:
                                    
                                        if ($objTable->getudfvalue("u_envpaymode")=="A"){
                                            $objLGUBills->setudfvalue("u_payqtr",4);
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - ".$objRs_uBilyears->fields["u_year"]);
                                        } else if ($objTable->getudfvalue("u_envpaymode")=="S"){
                                            $objLGUBills->setudfvalue("u_payqtr",2);
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - S1, ".$objRs_uBilyears->fields["u_year"] );
                                        } else {
                                            $objLGUBills->setudfvalue("u_payqtr",1);
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q1, ".$objRs_uBilyears->fields["u_year"] );
                                        }
                                        if ($objRs_uBilyears->fields["u_year"] != $objTable->getudfvalue("u_year")) {
                                             $objLGUBills->setudfvalue("u_payqtr",4);
                                             $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - ".$objRs_uBilyears->fields["u_year"] );
                                        }
                                        
                                        $objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
                                        $objLGUBills->setudfvalue("u_duedate",$objRs_uBilyears->fields["u_year"]."-12-31");
                                        
                                        break;
				case 2:
					$objLGUBills->setudfvalue("u_docdate",$objRs_uBilyears->fields["u_year"]."-04-01");
					$startdate = getmonthstartDB($objRs_uBilyears->fields["u_year"]."-04-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        
                                        if ($objTable->getudfvalue("u_envpaymode")=="S") {
                                                $objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,getmonthstartDB($objRs_uBilyears->fields["u_year"]."-07-01")));
                                                $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - S2, ".$objRs_uBilyears->fields["u_year"] );
                                                $objLGUBills->setudfvalue("u_payqtr",4);
                                        } else if ($objTable->getudfvalue("u_envpaymode")=="Q") {
                                                $objLGUBills->setudfvalue("u_payqtr",2);
                                                $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q2, ".$objRs_uBilyears->fields["u_year"] );
                                        }
                                            
					break;
				case 3:
					$objLGUBills->setudfvalue("u_docdate",$objRs_uBilyears->fields["u_year"]."-07-01");
					$startdate = getmonthstartDB($objRs_uBilyears->fields["u_year"]."-07-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        $objLGUBills->setudfvalue("u_payqtr",3);
                                        $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q3, ".$objRs_uBilyears->fields["u_year"] );
					break;
				case 4:
					$objLGUBills->setudfvalue("u_docdate",$objRs_uBilyears->fields["u_year"]."-10-01");
					$startdate = getmonthstartDB($objRs_uBilyears->fields["u_year"]."-10-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        $objLGUBills->setudfvalue("u_payqtr",4);
                                        $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q4, ".$objRs_uBilyears->fields["u_year"] );
					break;
			}
                        if ($objTable->getudfvalue("u_envpaymode")=="A") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - ".$objRs_uBilyears->fields["u_year"] );
                        $objLGUBills->setudfvalue("u_alertmobileno",$objTable->getudfvalue("u_alertmobileno"));
                               //if ($objTable->getudfvalue("u_apptype")=="RETIRED") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
                        $objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT,A.U_SEQNO FROM U_BPLAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 AND B.U_ENVIRONMENTAL = 1 AND A.U_YEAR = '".$objRs_uBilyears->fields["u_year"]."' ");
                        $totalamount=0;
                        while ($objRs->queryfetchrow("NAME")) {
                                if ($ctr>1 && $objRs->fields["U_INSTALLMENT"]==0) continue;
                                $objLGUBillItems->prepareadd();
                                $objLGUBillItems->docid = $objLGUBills->docid;
                                $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                $objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
                                $objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
                                $objLGUBillItems->setudfvalue("u_seqno",$objRs->fields["U_SEQNO"]);
                                    if ($objRs->fields["U_INSTALLMENT"]==0) {
                                            $objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
                                            $totalamount+=$objRs->fields["U_AMOUNT"];
                                    } else {
                                            $objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
                                            $totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
                                    }	
                                $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                $actionReturn = $objLGUBillItems->add();
                                if (!$actionReturn) break;
                        }
                        if ($actionReturn && $totalamount > 0) {
                                $objLGUBills->setudfvalue("u_totalamount",$totalamount);
                                $objLGUBills->setudfvalue("u_dueamount",$totalamount);
                                
                                $actionReturn = $objLGUBills->add();
                        }
                        if (!$actionReturn) break;
                        if ($objRs_uBilyears->fields["u_year"] != $objTable->getudfvalue("u_year") && $ctr==1) break;
                        if ($objTable->getudfvalue("u_envpaymode")=="A" && $ctr==1) break;
                        elseif ($objTable->getudfvalue("u_envpaymode")=="S" && $ctr==2) break;
                        elseif ($objTable->getudfvalue("u_envpaymode")=="Q" && $ctr==4) break;
                }
        }
	
	return $actionReturn;
}
function onCustomEventcreatebilldocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE,A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}	

	$ctr=0;
	$div=1;
	if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;
	$alertmsg="";
	$alertmsg2="";
	while (true) {
		$ctr++;
		$objLGUBills->prepareadd();
		$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
		$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
		$objLGUBills->docstatus = "O";
		$objLGUBills->setudfvalue("u_profitcenter","BPL");
		$objLGUBills->setudfvalue("u_module","Business Permit");
		$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
		$objLGUBills->setudfvalue("u_appno",$objTable->docno);
//		if ($objTable->getudfvalue("u_apptype")=="NEW") $objLGUBills->setudfvalue("u_custno",$objTable->docno);
//		elseif ($objTable->getudfvalue("u_bpno")!="") $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_bpno"));
//		else $objLGUBills->setudfvalue("u_custno",$objTable->docno);
                $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_appno"));
		$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
		//if ($objTable->getudfvalue("u_apptype")=="RENEW") {
			switch ($ctr) {
				case 1:
                                    
					if ($objTable->getudfvalue("u_paymode")=="S"){
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - 1st Semi-Annual, ".date('Y') );
                                        }elseif($objTable->getudfvalue("u_paymode")=="Q"){
                                            $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q1, ".date('Y') );
                                        }
                                        if ($objTable->getudfvalue("u_apptype")=="RENEW") {
                                            $objLGUBills->setudfvalue("u_docdate",date('Y')."-01-01");
                                            $startdate = getmonthstartDB(date('Y')."-01-01");
                                            $objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        }else{
                                            $objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
                                            $objLGUBills->setudfvalue("u_duedate",dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
                                        }
                                        
                                        break;
				case 2:
					$objLGUBills->setudfvalue("u_docdate",date('Y')."-04-01");
					$startdate = getmonthstartDB(date('Y')."-04-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
                                        if ($objTable->getudfvalue("u_paymode")=="S") $objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,getmonthstartDB(date('Y')."-07-01")));
					if ($objTable->getudfvalue("u_paymode")=="S") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - 2nd Semi-Annual, ".date('Y') );
					elseif ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q2, ".date('Y') );
					break;
				case 3:
					$objLGUBills->setudfvalue("u_docdate",date('Y')."-07-01");
					$startdate = getmonthstartDB(date('Y')."-07-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
					if ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q3, ".date('Y') );
					break;
				case 4:
					$objLGUBills->setudfvalue("u_docdate",date('Y')."-10-01");
					$startdate = getmonthstartDB(date('Y')."-10-01");
					$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRs_uLGUSetup->fields["U_BPLDUEDAY"]-1,$startdate));
					if ($objTable->getudfvalue("u_paymode")=="Q") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - Q4, ".date('Y') );
					break;
			}
		//} else {
		//	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
		//	$objLGUBills->setudfvalue("u_duedate",dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
		//}			
		if ($objTable->getudfvalue("u_paymode")=="A") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
 		$objLGUBills->setudfvalue("u_alertmobileno",$objTable->getudfvalue("u_alertmobileno"));
	               //if ($objTable->getudfvalue("u_apptype")=="RETIRED") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT FROM U_BPLAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0");
		$totalamount=0;
		while ($objRs->queryfetchrow("NAME")) {
			//var_dump($objRs->fields);
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
		//	} else {
				if ($objRs->fields["U_INSTALLMENT"]==0) {
					$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
					$totalamount+=$objRs->fields["U_AMOUNT"];
				} else {
					$objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
					$totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
				}	
			//}	
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
		}
		if ($actionReturn) {
                       
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
			$actionReturn = $objLGUBills->add();
			if ($objTable->getudfvalue("u_paymode")=="A") {
				$alertmsg = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\nBusiness Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . " on " . date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate")))."";
			} elseif ($alertmsg=="") {
				$alertmsg =  "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\nBusiness Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . "";
				$alertmsg2 = "From Municipality of ".ucwords($objRs_uLGUSetup->fields["U_MUNICIPALITY"]).",".ucwords($objRs_uLGUSetup->fields["U_PROVINCE"])."\r\nPayment of " . formatNumericAmount($totalamount) . " is due on ". date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate"))).".\n\r This is an automated message \n\r Please dont reply";
			}
		}
		if (!$actionReturn) break;
		if ($objTable->getudfvalue("u_paymode")=="A" && $ctr==1) break;
		elseif ($objTable->getudfvalue("u_paymode")=="S" && $ctr==2) break;
		elseif ($objTable->getudfvalue("u_paymode")=="Q" && $ctr==4) break;
	}

	if ($actionReturn && $alertmsg!="" && strlen($objTable->getudfvalue("u_alertmobileno"))==11) {

		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
		$objSMSOutLog->deviceid = "sun";
		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
		$objSMSOutLog->remark = "Business Permit";
		//var_dump(strlen($alertmsg));
		$objSMSOutLog->message = $alertmsg;
		$actionReturn = $objSMSOutLog->add();
		//var_dump($objSMSOutLog->message);
	}
	if ($actionReturn && $alertmsg2!="" && strlen($objTable->getudfvalue("u_alertmobileno"))==11) {
	
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
		$objSMSOutLog->deviceid = "sun";
		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
		$objSMSOutLog->remark = "Business Permit";
		//var_dump(strlen($alertmsg));
		$objSMSOutLog->message = $alertmsg2;
		$actionReturn = $objSMSOutLog->add();
		//var_dump($objSMSOutLog->message);
	}

	//var_dump($alertmsg);	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatebplmddocumentschema_brGPSBPLS()");

	return $actionReturn;
}
function onCustomEventcreatefirebilldocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}	

	$ctr=0;
	$div=1;
	while (true) {
		$ctr++;
		$objLGUBills->prepareadd();
		$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
		$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
		$objLGUBills->docstatus = "O";
		$objLGUBills->setudfvalue("u_profitcenter","BPL");
		$objLGUBills->setudfvalue("u_module","Business Permit(Fire Dept)");
		$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
		$objLGUBills->setudfvalue("u_appno",$objTable->docno);
		if ($objTable->getudfvalue("u_apptype")=="NEW") $objLGUBills->setudfvalue("u_custno",$objTable->docno);
		elseif ($objTable->getudfvalue("u_bpno")!="") $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_bpno"));
		else $objLGUBills->setudfvalue("u_custno",$objTable->docno);
		$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
		$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
	        $objLGUBills->setudfvalue("u_duedate",dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
          			
		$objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Fire Dept Assessment) - ".date('Y') );
 		$objLGUBills->setudfvalue("u_alertmobileno",$objTable->getudfvalue("u_alertmobileno"));
	               //if ($objTable->getudfvalue("u_apptype")=="RETIRED") $objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing - ".date('Y') );
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT FROM U_BPLAPPFIREFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0");
		$totalamount=0;
		while ($objRs->queryfetchrow("NAME")) {
			//var_dump($objRs->fields);
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
		//	} else {
				if ($objRs->fields["U_INSTALLMENT"]==0) {
					$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
					$totalamount+=$objRs->fields["U_AMOUNT"];
				} else {
					$objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
					$totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
				}	
			//}	
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
		}
		if ($actionReturn) {
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
                        if($totalamount>0){
                                $actionReturn = $objLGUBills->add();
                        }
//			if ($objTable->getudfvalue("u_paymode")=="A") {
//				$alertmsg = "Business Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . " on " . date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate"))).".";
//			} elseif ($alertmsg=="") {
//				$alertmsg = "Business Permit No. " . $objTable->docno . " for " . $objTable->getudfvalue("u_businessname") . " was approved with Total Amount Due " . formatNumericAmount($objTable->getudfvalue("u_asstotal")) . ".";
//				$alertmsg2 = "Payment of " . formatNumericAmount($totalamount) . " is due on ". date('M d, Y',strtotime($objLGUBills->getudfvalue("u_duedate"))).".";
//			}
		}
		if (!$actionReturn) break;
		if ($ctr==1) break;
		
	}

//	if ($actionReturn && $alertmsg!="" && strlen($objTable->getudfvalue("u_alertmobileno"))==11) {
//
//		$objSMSOutLog = new smsoutlog(null,$objConnection);
//		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
//		$objSMSOutLog->deviceid = "sun";
//		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
//		$objSMSOutLog->remark = "Business Permit";
//		//var_dump(strlen($alertmsg));
//		$objSMSOutLog->message = $alertmsg;
//		$actionReturn = $objSMSOutLog->add();
//		//var_dump($objSMSOutLog->message);
//	}
//	if ($actionReturn && $alertmsg2!="" && strlen($objTable->getudfvalue("u_alertmobileno"))==11) {
//	
//		$objSMSOutLog = new smsoutlog(null,$objConnection);
//		$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objConnection);
//		$objSMSOutLog->deviceid = "sun";
//		$objSMSOutLog->mobilenumber = $objTable->getudfvalue("u_alertmobileno");
//		$objSMSOutLog->remark = "Business Permit";
//		//var_dump(strlen($alertmsg));
//		$objSMSOutLog->message = $alertmsg2;
//		$actionReturn = $objSMSOutLog->add();
//		//var_dump($objSMSOutLog->message);
//	}

	//var_dump($alertmsg);	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatebplmddocumentschema_brGPSBPLS()");

	return $actionReturn;
}
function onCustomEventcreateReqAppBilldocumentschema_brGPSBPLS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_BPLDUEDAY from U_LGUSETUP A");
	if (!$objRs_uLGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for business permit due day.");
	}	
	$ctr=0;
	$div=1;
	while (true) {
		$ctr++;
		$objLGUBills->prepareadd();
		$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
		$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
		$objLGUBills->docstatus = "O";
		$objLGUBills->setudfvalue("u_profitcenter","BPL");
		$objLGUBills->setudfvalue("u_module","Business Permit");
		$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
		$objLGUBills->setudfvalue("u_appno",$objTable->docno);
		if ($objTable->getudfvalue("u_apptype")=="NEW") $objLGUBills->setudfvalue("u_custno",$objTable->docno);
		elseif ($objTable->getudfvalue("u_bpno")!="") $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_bpno"));
		else $objLGUBills->setudfvalue("u_custno",$objTable->docno);
		$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
		$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
	        $objLGUBills->setudfvalue("u_duedate",dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
		$objLGUBills->setudfvalue("u_remarks","Business Permit & Licensing(Requirements Application Assessment) - ".date('Y') );
 		$objLGUBills->setudfvalue("u_alertmobileno",$objTable->getudfvalue("u_alertmobileno"));
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT, B.U_INSTALLMENT FROM U_BPLREQAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0");
		$totalamount=0;
		while ($objRs->queryfetchrow("NAME")) {
			//var_dump($objRs->fields);
			if ($ctr>1 && $objRs->fields["U_INSTALLMENT"]==0) continue;
			$objLGUBillItems->prepareadd();
			$objLGUBillItems->docid = $objLGUBills->docid;
			$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
			$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
			$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
                        if ($objRs->fields["U_INSTALLMENT"]==0) {
                                $objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
                                $totalamount+=$objRs->fields["U_AMOUNT"];
                        } else {
                                $objLGUBillItems->setudfvalue("u_amount",round($objRs->fields["U_AMOUNT"]/$div,2));
                                $totalamount+=round($objRs->fields["U_AMOUNT"]/$div,2);
                        }	
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
		}
		if ($actionReturn) {
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
			$actionReturn = $objLGUBills->add();
		}
                
		if (!$actionReturn) break;
		if ($ctr==1) break;
		
	}

	return $actionReturn;
}

function onCustomEventupdatebplmddocumentschema_brGPSBPLS($objTable,$edit=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objBPLMDs = new masterdataschema_br(null,$objConnection,"u_bplmds");
      

	$objTable->setudfvalue("u_expdate", substr($objTable->getudfvalue("u_appdate"),0,4)."-12-31");
	$objTable->setudfvalue("u_year", substr($objTable->getudfvalue("u_appdate"),0,4));

	if (!$edit) $actionReturn = $objBPLMDs->getbysql("NAME='".addslashes($objTable->getudfvalue("u_businessname"))."'",0);
	else $actionReturn = $objBPLMDs->getbysql("NAME='".addslashes($objTable->fields["U_BUSINESSNAME"])."'",0);
	
	if ($actionReturn && $objTable->rowstat=="N" && $objTable->getudfvalue("u_apptype")=="NEW") {
            //if($objBPLMDs->u_retired != 1){
              	return raiseError("Business Name [".$objTable->getudfvalue("u_businessname")."] is already registered. Select Renew instead.");  
            //}
	}
	if (!$actionReturn) { 
		$objBPLMDs->prepareadd();
		$objBPLMDs->code = $objTable->getudfvalue("u_appno");
	}
	
	$objBPLMDs->name = $objTable->getudfvalue("u_businessname");
	$objBPLMDs->setudfvalue("u_tradename", $objTable->getudfvalue("u_tradename"));
	$objBPLMDs->setudfvalue("u_lastname", $objTable->getudfvalue("u_lastname"));
	$objBPLMDs->setudfvalue("u_firstname", $objTable->getudfvalue("u_firstname"));
	$objBPLMDs->setudfvalue("u_middlename", $objTable->getudfvalue("u_middlename"));
	$objBPLMDs->setudfvalue("u_bbldgno", $objTable->getudfvalue("u_bbldgno"));
	$objBPLMDs->setudfvalue("u_bbldgname", $objTable->getudfvalue("u_bbldgname"));
	$objBPLMDs->setudfvalue("u_bunitno", $objTable->getudfvalue("u_bunitno"));
	$objBPLMDs->setudfvalue("u_bstreet", $objTable->getudfvalue("u_bstreet"));
	$objBPLMDs->setudfvalue("u_bbrgy", $objTable->getudfvalue("u_bbrgy"));
	$objBPLMDs->setudfvalue("u_bfloorno", $objTable->getudfvalue("u_bfloorno"));
	$objBPLMDs->setudfvalue("u_bblock", $objTable->getudfvalue("u_bblock"));
	$objBPLMDs->setudfvalue("u_orgtype", $objTable->getudfvalue("u_orgtype"));
	$objBPLMDs->setudfvalue("u_bvillage", $objTable->getudfvalue("u_bvillage"));
	$objBPLMDs->setudfvalue("u_btelno", $objTable->getudfvalue("u_btelno"));
	$objBPLMDs->setudfvalue("u_apprefno", $objTable->docno);
	$objBPLMDs->setudfvalue("u_bldgapprefno", $objTable->getudfvalue("u_reqappno"));
	$objBPLMDs->setudfvalue("u_zoningapprefno", $objTable->getudfvalue("u_reqappno"));
	$objBPLMDs->setudfvalue("u_apprefno", $objTable->docno);
	$objBPLMDs->setudfvalue("u_appdate", $objTable->getudfvalue("u_appdate"));
	$objBPLMDs->setudfvalue("u_expdate", $objTable->getudfvalue("u_expdate"));
	$objBPLMDs->setudfvalue("u_year", $objTable->getudfvalue("u_year"));
	
         if ($objTable->fields["U_APPTYPE"]=="RETIRED" && $objBPLMDs->fields["U_RETIRED"] == 0) {
                $objBPLMDs->setudfvalue("u_retireddate",currentdateDB());
                $objBPLMDs->setudfvalue("u_retired",1);
                $objTable->setudfvalue("u_retireddate",currentdateDB());
                $objTable->setudfvalue("u_retired", 1);
	}
        
//        if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
//		$objBPLMDs->setudfvalue("u_regdate",currentdateDB());
//	}
        
	if ($objTable->getudfvalue("u_firstyear")=="") {
		$objBPLMDs->setudfvalue("u_firstyear",date('Y'));
	}
	if ($objBPLMDs->getudfvalue("u_regdate")=="") {
		$objBPLMDs->setudfvalue("u_regdate",$objTable->getudfvalue("u_appdate"));
	}
	if ($objBPLMDs->getudfvalue("u_firstyear")==$objTable->getudfvalue("u_year") && $objTable->getudfvalue("u_apptype")!="NEW") {
		$objBPLMDs->setudfvalue("u_firstyear",$objTable->getudfvalue("u_year")-1);
	}
	if ($objBPLMDs->getudfvalue("u_firstyear")!=$objTable->getudfvalue("u_year") && $objTable->getudfvalue("u_apptype")=="NEW") {
		$objBPLMDs->setudfvalue("u_firstyear",$objTable->getudfvalue("u_year"));
	}
        
	if ($objBPLMDs->rowstat=="N" && $objTable->getudfvalue("u_apptype") == "NEW") $actionReturn = $objBPLMDs->add();
	else $actionReturn = $objBPLMDs->update($objBPLMDs->code,$objBPLMDs->rcdversion);
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatebplmddocumentschema_brGPSBPLS()");
	return $actionReturn;
}
function onCustomEventupdateZoningCLRAppsdocumentschema_brGPSBPLS($objTable,$edit=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
        $objZoningApps = new documentschema_br(null,$objConnection,"u_zoningclrapps");
        if($objZoningApps->getbykey($objTable->fields["U_REQAPPNO"])){
            if ($objZoningApps->getudfvalue("u_acctno") != '' && $objZoningApps->getudfvalue("u_acctno") != $objTable->getudfvalue("u_appno")) {
                return raiseError("AccountNo doesnt match with Zoning application.");
            }
            $objZoningApps->setudfvalue("u_bplappno",$objTable->docno);
            $objZoningApps->setudfvalue("u_acctno",$objTable->getudfvalue("u_appno"));
            $objZoningApps->setudfvalue("u_businessname", $objTable->getudfvalue("u_tradename"));
            $objZoningApps->setudfvalue("u_lastname", $objTable->getudfvalue("u_lastname"));
            $objZoningApps->setudfvalue("u_firstname", $objTable->getudfvalue("u_firstname"));
            $objZoningApps->setudfvalue("u_middlename", $objTable->getudfvalue("u_middlename"));
            $objZoningApps->setudfvalue("u_owneraddress", $objTable->getudfvalue("u_owneraddress"));
            $objZoningApps->setudfvalue("u_contactno", $objTable->getudfvalue("u_telno"));
            $objZoningApps->setudfvalue("u_bbrgy", $objTable->getudfvalue("u_bbrgy"));
            $objZoningApps->setudfvalue("u_bbldgno", $objTable->getudfvalue("u_bbldgno"));
            $objZoningApps->setudfvalue("u_bblock", $objTable->getudfvalue("u_bblock"));
            $objZoningApps->setudfvalue("u_blotno", $objTable->getudfvalue("u_blotno"));
            $objZoningApps->setudfvalue("u_bstreet", $objTable->getudfvalue("u_bstreet"));
            $objZoningApps->setudfvalue("u_bphaseno", $objTable->getudfvalue("u_bphaseno"));
            $objZoningApps->setudfvalue("u_baddressno", $objTable->getudfvalue("u_baddressno"));
            $objZoningApps->setudfvalue("u_bvillage", $objTable->getudfvalue("u_bvillage"));
            $objZoningApps->setudfvalue("u_bbldgname", $objTable->getudfvalue("u_bbldgname"));
            $objZoningApps->setudfvalue("u_bunitno", $objTable->getudfvalue("u_bunitno"));
            $objZoningApps->setudfvalue("u_bfloorno", $objTable->getudfvalue("u_bfloorno"));
            $objZoningApps->setudfvalue("u_btelno", $objTable->getudfvalue("u_btelno"));
            $actionReturn = $objZoningApps->update($objZoningApps->docno,$objZoningApps->rcdversion);
            
        }
        
	return $actionReturn;
}
function onCustomEventupdateBuildingAppsdocumentschema_brGPSBPLS($objTable,$edit=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
        $objBuildingApps = new documentschema_br(null,$objConnection,"u_bldgapps");
        if($objBuildingApps->getbykey($objTable->fields["U_REQAPPNO"])){
            if ($objBuildingApps->getudfvalue("u_acctno") != '' && $objBuildingApps->getudfvalue("u_acctno") != $objTable->getudfvalue("u_appno")) {
                return raiseError("AccountNo doesnt match with Building application.");
            }
            $objBuildingApps->setudfvalue("u_bplappno",$objTable->docno);
            $objBuildingApps->setudfvalue("u_acctno",$objTable->getudfvalue("u_appno"));
            $objBuildingApps->setudfvalue("u_businessname", $objTable->getudfvalue("u_tradename"));
            $objBuildingApps->setudfvalue("u_lastname", $objTable->getudfvalue("u_lastname"));
            $objBuildingApps->setudfvalue("u_firstname", $objTable->getudfvalue("u_firstname"));
            $objBuildingApps->setudfvalue("u_middlename", $objTable->getudfvalue("u_middlename"));
            $objBuildingApps->setudfvalue("u_owneraddress", $objTable->getudfvalue("u_owneraddress"));
            $objBuildingApps->setudfvalue("u_contactno", $objTable->getudfvalue("u_telno"));
            $objBuildingApps->setudfvalue("u_bbrgy", $objTable->getudfvalue("u_bbrgy"));
            $objBuildingApps->setudfvalue("u_bbldgno", $objTable->getudfvalue("u_bbldgno"));
            $objBuildingApps->setudfvalue("u_bblock", $objTable->getudfvalue("u_bblock"));
            $objBuildingApps->setudfvalue("u_blotno", $objTable->getudfvalue("u_blotno"));
            $objBuildingApps->setudfvalue("u_bstreet", $objTable->getudfvalue("u_bstreet"));
            $objBuildingApps->setudfvalue("u_bphaseno", $objTable->getudfvalue("u_bphaseno"));
            $objBuildingApps->setudfvalue("u_baddressno", $objTable->getudfvalue("u_baddressno"));
            $objBuildingApps->setudfvalue("u_bvillage", $objTable->getudfvalue("u_bvillage"));
            $objBuildingApps->setudfvalue("u_bbldgname", $objTable->getudfvalue("u_bbldgname"));
            $objBuildingApps->setudfvalue("u_bunitno", $objTable->getudfvalue("u_bunitno"));
            $objBuildingApps->setudfvalue("u_bfloorno", $objTable->getudfvalue("u_bfloorno"));
            $objBuildingApps->setudfvalue("u_btelno", $objTable->getudfvalue("u_btelno"));
            $actionReturn = $objBuildingApps->update($objBuildingApps->docno,$objBuildingApps->rcdversion);
            
        }
        
	return $actionReturn;
}


function onCustomEventAuditedGrossdocumentschema_brGPSBPLS($objTable,$edit=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	$obju_LGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$obju_LGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
        
	$obju_BPLApps = new documentschema_br(null,$objConnection,"u_bplapps");
	$obju_BPLAppLines = new documentlinesschema_br(null,$objConnection,"u_bplapplines");

        $objRs_uAuditedGross = new recordset(null,$objConnection);
	$objRs_uAuditedGross->queryopen("SELECT A.U_BUSINESSLINECODE,A.U_TAXCLASS,A.U_UNAUDITEDGROSS,A.U_BTAXAMOUNT,A.U_AUDITEDGROSS,A.U_AUDITEDBTAXAMOUNT,A.U_PAIDAMOUNT,A.U_PAIDQTR FROM U_BPLUPDGROSSLINES a INNER JOIN U_BPLUPDGROSS B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AUDITEDGROSS > 0  AND A.U_AUDITEDBTAXAMOUNT > 0 ");
        while ($objRs_uAuditedGross->queryfetchrow("NAME")) {
            $ctr=0;
            $div=1;
            $amount=0;
            $paidamount = $objRs_uAuditedGross->fields["U_PAIDAMOUNT"];
            $qtr = $objRs_uAuditedGross->fields["U_PAIDQTR"] ;
            if ($objTable->getudfvalue("u_paymode")=="S"){   $div=2;  $qtr = $objRs_uAuditedGross->fields["U_PAIDQTR"] +  2;}
            if ($objTable->getudfvalue("u_paymode")=="Q"){   $div=4;  $qtr = $objRs_uAuditedGross->fields["U_PAIDQTR"] +  1;}
            $auditedbtaxamount = $objRs_uAuditedGross->fields["U_AUDITEDBTAXAMOUNT"] / $div;
            
            while (true) {
                $ctr++;
                    switch ($ctr) {
                        case 2:
                            if ($ctr >= $qtr) {
                                if ($qtr == $ctr)  $amount = ($auditedbtaxamount * $qtr) - $paidamount;
                                else $amount = $auditedbtaxamount;
                                    $objRs->queryopen("SELECT A.DOCNO,B.U_ITEMCODE,B.DOCID,B.LINEID,B.U_AMOUNT,B.U_TAXBASE,B.U_BUSINESSLINE FROM U_LGUBILLS A
                                            INNER JOIN U_LGUBILLITEMS B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                            WHERE A.U_APPNO = '".$objTable->getudfvalue("u_apprefno")."' AND DOCSTATUS = 'O' AND U_ITEMCODE = '0001' AND U_PAYQTR = '".$ctr."'  AND U_BUSINESSLINE = '".$objRs_uAuditedGross->fields["U_BUSINESSLINECODE"]."'
                                            AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");
                                        if ($objRs->queryfetchrow("NAME")) {
                                            if ($obju_LGUBillItems->getbysql("DOCID=".$objRs->fields["DOCID"]." AND U_ITEMCODE='".$objRs->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs->fields["LINEID"]."'")) {
                                                $obju_LGUBillItems->setudfvalue("u_amount", round($amount,2));
                                                $obju_LGUBillItems->setudfvalue("u_taxbase", $objRs_uAuditedGross->fields["U_AUDITEDGROSS"]);
                                                $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                                if (!$actionReturn) break;
                                            }
                                            if ($actionReturn) {
                                                if ($obju_LGUBills->getbykey($objRs->fields["DOCNO"])) {
                                                    $objRs2->queryopen("select sum(U_AMOUNT - U_SETTLEDAMOUNT) as U_DUEAMOUNT from u_lgubillitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_LGUBills->docid'  group by docid");
                                                    while ($objRs2->queryfetchrow("NAME")) {
                                                        $obju_LGUBills->setudfvalue("u_dueamount",$objRs2->fields["U_DUEAMOUNT"]);
                                                        $obju_LGUBills->setudfvalue("u_totalamount",$objRs2->fields["U_DUEAMOUNT"]);
                                                        $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                                                    }
                                                }
                                            }
                                        }
                            }
                            break;
                        case 3:
                            if ($ctr >= $qtr) {
                                if ($qtr == $ctr)  $amount = ($auditedbtaxamount * $qtr) - $paidamount;
                                else $amount = $auditedbtaxamount;
                                    $objRs->queryopen("SELECT A.DOCNO,B.U_ITEMCODE,B.DOCID,B.LINEID,B.U_AMOUNT,B.U_TAXBASE,B.U_BUSINESSLINE FROM U_LGUBILLS A
                                            INNER JOIN U_LGUBILLITEMS B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                            WHERE A.U_APPNO = '".$objTable->getudfvalue("u_apprefno")."' AND DOCSTATUS = 'O' AND U_ITEMCODE = '0001' AND U_PAYQTR = '".$ctr."' AND U_BUSINESSLINE = '".$objRs_uAuditedGross->fields["U_BUSINESSLINECODE"]."'
                                            AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");
                                        if ($objRs->queryfetchrow("NAME")) {
                                            if ($obju_LGUBillItems->getbysql("DOCID=".$objRs->fields["DOCID"]." AND U_ITEMCODE='".$objRs->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs->fields["LINEID"]."'")) {
                                                $obju_LGUBillItems->setudfvalue("u_amount", round($amount,2));
                                                $obju_LGUBillItems->setudfvalue("u_taxbase", $objRs_uAuditedGross->fields["U_AUDITEDGROSS"]);
                                                $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                                if (!$actionReturn) break;
                                            }
                                            if ($actionReturn) {
                                                if ($obju_LGUBills->getbykey($objRs->fields["DOCNO"])) {
                                                    $objRs2->queryopen("select sum(U_AMOUNT - U_SETTLEDAMOUNT) as U_DUEAMOUNT from u_lgubillitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_LGUBills->docid'  group by docid");
                                                    while ($objRs2->queryfetchrow("NAME")) {
                                                        $obju_LGUBills->setudfvalue("u_dueamount",$objRs2->fields["U_DUEAMOUNT"]);
                                                        $obju_LGUBills->setudfvalue("u_totalamount",$objRs2->fields["U_DUEAMOUNT"]);
                                                        $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                                                    }
                                                }
                                            }
                                        }   
                            }
                            break; 
                        case 4:
                            if ($ctr >= $qtr) {
                                if ($qtr == $ctr && $objTable->getudfvalue("u_paymode")== "A") $amount = $auditedbtaxamount - $paidamount;  
                                else if ($qtr == $ctr && $objTable->getudfvalue("u_paymode")== "S") $amount = ($auditedbtaxamount * 2) - $paidamount;
                                else if ($qtr == $ctr && $objTable->getudfvalue("u_paymode")== "Q") $amount = ($auditedbtaxamount * $qtr) - $paidamount;
                                else $amount = $auditedbtaxamount;
                                    $objRs->queryopen("SELECT A.DOCNO,B.U_ITEMCODE,B.DOCID,B.LINEID,B.U_AMOUNT,B.U_TAXBASE,B.U_BUSINESSLINE FROM U_LGUBILLS A
                                            INNER JOIN U_LGUBILLITEMS B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                            WHERE A.U_APPNO = '".$objTable->getudfvalue("u_apprefno")."'  AND U_ITEMCODE = '0001' AND U_PAYQTR = '".$ctr."' AND U_BUSINESSLINE = '".$objRs_uAuditedGross->fields["U_BUSINESSLINECODE"]."'
                                            AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");
                                    if ($objRs->queryfetchrow("NAME")) {
                                        if ($obju_LGUBillItems->getbysql("DOCID=".$objRs->fields["DOCID"]." AND U_ITEMCODE='".$objRs->fields["U_ITEMCODE"]."' AND LINEID = '".$objRs->fields["LINEID"]."'")) {
                                                $obju_LGUBillItems->setudfvalue("u_amount", round($amount,2));
                                                $obju_LGUBillItems->setudfvalue("u_taxbase", $objRs_uAuditedGross->fields["U_AUDITEDGROSS"]);
                                                $actionReturn = $obju_LGUBillItems->update($obju_LGUBillItems->docid,$obju_LGUBillItems->lineid,$obju_LGUBillItems->rcdversion);
                                                if (!$actionReturn) break;
                                        }
                                        if ($actionReturn) {
                                            if ($obju_LGUBills->getbykey($objRs->fields["DOCNO"])) {
                                                $objRs2->queryopen("select sum(U_AMOUNT - U_SETTLEDAMOUNT) as U_DUEAMOUNT from u_lgubillitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_LGUBills->docid'  group by docid");
                                                while ($objRs2->queryfetchrow("NAME")) {
                                                    $obju_LGUBills->setudfvalue("u_dueamount",$objRs2->fields["U_DUEAMOUNT"]);
                                                    $obju_LGUBills->setudfvalue("u_totalamount",$objRs2->fields["U_DUEAMOUNT"]);
                                                    if ($obju_LGUBills->getudfvalue("u_totalamount") > $obju_LGUBills->getudfvalue("u_settledamount"))  $obju_LGUBills->docstatus = "O";
                                                    else  $obju_LGUBills->docstatus = "C";
                                                    $actionReturn = $obju_LGUBills->update($obju_LGUBills->docno,$obju_LGUBills->rcdversion);
                                                }
                                            }
                                        }
                                    }
                            }
                            break;  
                    }   
                if ($ctr==4) break;
            }
            
            if ($obju_BPLApps->getbykey($objTable->getudfvalue("u_apprefno"))) {
                if ($obju_BPLAppLines->getbysql("DOCID='$obju_BPLApps->docid' AND U_BUSINESSLINE='".$objRs_uAuditedGross->fields["U_BUSINESSLINECODE"]."' AND U_YEAR = '".$objTable->getudfvalue("u_year")."' ")) {
                    $obju_BPLAppLines->setudfvalue("u_nonessential", $objRs_uAuditedGross->fields["U_AUDITEDGROSS"]);
                    $obju_BPLAppLines->setudfvalue("u_btaxlinetotal", $objRs_uAuditedGross->fields["U_AUDITEDBTAXAMOUNT"]);
                    $actionReturn = $obju_BPLAppLines->update($obju_BPLAppLines->docid,$obju_BPLAppLines->lineid,$obju_BPLAppLines->rcdversion);
                    if (!$actionReturn) break;
                } 
            }
	}
	return $actionReturn;
}

function onCustomEventInsertPaymentHistorydocumentschema_brGPSBPLS($objTable) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_BusinessLedger = new documentschema_br(null, $objConnection, "u_bplledger");
    $obju_LGUPOS = new documentschema_br(null, $objConnection, "u_lgupos");
    $objRs = new recordset(null, $objConnection);
    $objRs_Total = new recordset(null, $objConnection);

    $objRs_Total->queryopen("SELECT SUM(B.U_AMOUNTPAID) as U_AMOUNTPAID,B.U_ORNO  FROM U_BPLADDLEDGER A
                        INNER JOIN U_BPLADDLEDGERLINES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID
                        WHERE A.COMPANY = '" . $_SESSION["company"] . "' AND A.BRANCH = '" . $_SESSION["branch"] . "' AND A.DOCNO = '" . $objTable->docno . "' GROUP BY B.U_ORNO ");
    while ($objRs_Total->queryfetchrow("NAME")) {
        if ($obju_LGUPOS->getbykey($objRs_Total->fields["U_ORNO"])) {
            if ($obju_LGUPOS->getudfvalue("u_totalamount") != $objRs_Total->fields["U_AMOUNTPAID"]) {
                return raiseError("Receipt number['" . $objRs_Total->fields["U_ORNO"] . "'] paid amount[" . $obju_LGUPOS->getudfvalue("u_totalamount") . "] is not equal to total grid amount[" . $objRs_Total->fields["U_AMOUNTPAID"] . "]");
            }
        }
    }

    $objRs->queryopen("SELECT A.U_ACCOUNTNO, B.U_CASHIER,A.U_DATE AS U_ASSDATE, B.U_YEAR AS U_PAYYEAR, A.U_APPREFNO,B.U_ORDATE, B.U_ORNO, B.U_BUSINESSLINE, B.U_FEEID, B.U_FEEDESC, B.U_TAXBASE, B.U_AMOUNTPAID, B.U_PAYQTR, B.U_PAYMODE, B.U_ROWNUMBER  FROM U_BPLADDLEDGER A
                            INNER JOIN U_BPLADDLEDGERLINES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID
                            WHERE A.COMPANY = '" . $_SESSION["company"] . "' AND A.BRANCH = '" . $_SESSION["branch"] . "' AND A.DOCNO = '" . $objTable->docno . "' ");
    while ($objRs->queryfetchrow("NAME")) {
        $obju_BusinessLedger->prepareadd();
        $obju_BusinessLedger->docno = getNextNoByBranch("u_bplledger", "", $objConnection);
        $obju_BusinessLedger->docid = getNextIdByBranch("u_bplledger", $objConnection);
        $obju_BusinessLedger->setudfvalue("u_acctno", $objRs->fields["U_ACCOUNTNO"]);
        $obju_BusinessLedger->setudfvalue("u_userid", $objRs->fields["U_CASHIER"]);
        $obju_BusinessLedger->setudfvalue("u_assdate", $objRs->fields["U_ASSDATE"]);
        $obju_BusinessLedger->setudfvalue("u_duedate", $objRs->fields["U_ASSDATE"]);
        $obju_BusinessLedger->setudfvalue("u_payyear", $objRs->fields["U_PAYYEAR"]);
        $obju_BusinessLedger->setudfvalue("u_revisionyear", "2008");
        $obju_BusinessLedger->setudfvalue("u_businessid", $objRs->fields["U_APPREFNO"]);
        $obju_BusinessLedger->setudfvalue("u_ordate", $objRs->fields["U_ORDATE"]);
        $obju_BusinessLedger->setudfvalue("u_orno", $objRs->fields["U_ORNO"]);
        $obju_BusinessLedger->setudfvalue("u_businesslineid", $objRs->fields["U_BUSINESSLINE"]);
        $obju_BusinessLedger->setudfvalue("u_feeid", $objRs->fields["U_FEEID"]);
        $obju_BusinessLedger->setudfvalue("u_feedesc", $objRs->fields["U_FEEDESC"]);
        $obju_BusinessLedger->setudfvalue("u_taxbase", $objRs->fields["U_TAXBASE"]);
        $obju_BusinessLedger->setudfvalue("u_amountdue", $objRs->fields["U_AMOUNTPAID"]);
        $obju_BusinessLedger->setudfvalue("u_surcharge", 0);
        $obju_BusinessLedger->setudfvalue("u_amountpaid", $objRs->fields["U_AMOUNTPAID"]);
        $obju_BusinessLedger->setudfvalue("u_quarter", $objRs->fields["U_PAYQTR"]);
        $obju_BusinessLedger->setudfvalue("u_paymode", $objRs->fields["U_PAYMODE"]);
        $obju_BusinessLedger->setudfvalue("u_rownumber", $objRs->fields["U_ROWNUMBER"]);
        $actionReturn = $obju_BusinessLedger->add();
        if (!$actionReturn)
            break;
    }

    $objRs2 = new recordset(null, $objConnection);
    $objRs2->queryopen("SELECT  B.U_ORNO  FROM U_BPLADDLEDGER A
                            INNER JOIN U_BPLADDLEDGERLINES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID
                            WHERE A.COMPANY = '" . $_SESSION["company"] . "' AND A.BRANCH = '" . $_SESSION["branch"] . "' AND A.DOCNO = '" . $objTable->docno . "' GROUP BY B.U_ORNO ");
    while ($objRs2->queryfetchrow("NAME")) {
        if ($obju_LGUPOS->getbykey($objRs2->fields["U_ORNO"])) {
            $obju_LGUPOS->setudfvalue("u_insertpaidhistory", 1);
            $actionReturn = $obju_LGUPOS->update($obju_LGUPOS->docno, $obju_LGUPOS->rcdversion);
        }
    }
    return $actionReturn;
}

?>