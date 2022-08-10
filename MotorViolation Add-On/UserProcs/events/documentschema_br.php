<?php
function onBeforeAddEventdocumentschema_brGPSMotorViolation($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_motorviolationapps":
		
		//if ($objTable->docstatus=="O") $objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
		//	$actionReturn = onCustomEventupdatemtopmddocumentschema_brGPSMTOP($objTable);
			break;
                        
		
	}
	return $actionReturn;
}




//function onAddEventdocumentschema_brGPSMotorViolation($objTable) {
	//global $objConnection;
	//$actionReturn = true;
	//switch ($objTable->dbtable) {
		//case "u_motorviolationapps":
                        
                   
                      // if ($objTable->docstatus=="D") $objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
							//$actionReturn = onCustomEventcreatebilldocumentschema_brGPSMotorViolation($objTable);
                        
                    //break;
	//}
	//return $actionReturn;
//}



function onBeforeUpdateEventdocumentschema_brGPSMotorViolation($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_motorviolationapps":
                if ($objTable->docstatus=="C" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assesseddatetime",date('Y-m-d h:i:s'));
				} elseif ($objTable->docstatus=="O" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_reassessdatetime",date('Y-m-d h:i:s'));
			
			}	
			
		 
				 /***** Re-Assessment*****/
			if  (($objTable->docstatus=="O" && $objTable->getudfvalue("u_remarks")!=$objTable->fields["U_REMARKS"]) || 
                            ($objTable->docstatus=="O" && ($objTable->fields["DOCSTATUS"]=="C"))) { 	
				
                               if ($objTable->docstatus=="O" && ($objTable->fields["DOCSTATUS"]=="C")) {
				if ($actionReturn && $objTable->fields["DOCSTATUS"]=="C") {
					$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
					if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND U_PREASSBILL=0 AND U_MODULE='PLTO' AND U_DUEAMOUNT>0")) {
						$actionReturn = $objLGUBills->executesql("DELETE FROM U_LGUBILLITEMS WHERE COMPANY='$objLGUBills->company' AND BRANCH='$objLGUBills->branch' AND DOCID='$objLGUBills->docid'",true);
						if ($actionReturn) $actionReturn = $objLGUBills->delete();
					} else {
						$page->docstatus=$objTable->fields["DOCSTATUS"];
						return raiseError("Unable to find Unpaid Bill Document to remove.");
					}	

				}
			}	
							}
			
			if ($objTable->docstatus=="C" && $objTable->fields["DOCSTATUS"]=="O") {
				if ($objTable->getudfvalue("u_assesseddatetime")=="") $objTable->setudfvalue("u_assesseddatetime",currentdateDB());
				if ($actionReturn) $actionReturn = onCustomEventcreatebilldocumentschema_brGPSMotorViolation($objTable);
				if (!$actionReturn) $page->setitem("docstatus","C");
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
			}	
			if ($actionReturn) $actionReturn = onCustomEventupdatemtopmddocumentschema_brGPSMTOP($objTable);
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSMTOP()");
	return $actionReturn;
}


function onCustomEventcreatebilldocumentschema_brGPSMotorViolation($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	$ctr=0;
	$div=1;
	/*if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;*/

	//$objRsu_LGUSetup = new recordset(null,$objConnection);
	//$objRsu_LGUSetup->queryopen("select A.U_MTOPVALIDITY, A.U_MTOPDUEDAY from U_LGUSETUP A");
	//if (!$objRsu_LGUSetup->queryfetchrow("NAME")) {
		//return raiseError("No setup found for franchise validity and due day.");
	//}	
	$objLGUBills->prepareadd();
	$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
	$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
	$objLGUBills->docstatus = "O";
	$objLGUBills->setudfvalue("u_profitcenter","PLTO");
	$objLGUBills->setudfvalue("u_module","PLTO");
	$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
	$objLGUBills->setudfvalue("u_appno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_licenseno"));
	if ($objTable->getudfvalue("u_lastname")!="") {
		$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_lastname").", ".$objTable->getudfvalue("u_firstname")." ".$objTable->getudfvalue("u_middlename")));
	} else {
		$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_dlastname").", ".$objTable->getudfvalue("u_dfirstname")." ".$objTable->getudfvalue("u_dmiddlename")));
	}			
	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_assesseddatetime"));
	//$startdate = getmonthstartDB($objTable->getudfvalue("u_assesseddatetime"));
	$objLGUBills->setudfvalue("u_duedate",dateadd("d", 3,$objTable->fields["U_ASSESSEDDATETIME"]));
	//$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRsu_LGUSetup->fields["U_MTOPDUEDAY"]-1,$startdate));
	//$objLGUBills->setudfvalue("u_duedate",$objLGUBills->getudfvalue("u_appdate"));//dateadd("d",3,$objTable->getudfvalue("u_appdate")));
	//if (intval($objTable->getudfvalue("u_type"))==0 || intval($objTable->getudfvalue("u_type"))==1) {
		//$objTable->setudfvalue("u_expdate",date('Y',strtotime($objLGUBills->getudfvalue("u_docdate")))."-12-31");	
	//} else {
		//if ($objTable->getudfvalue("u_type")=="NEW") {
		//	$duedate2 = dateadd("yyyy",$objRsu_LGUSetup->fields["U_MTOPVALIDITY"],$objTable->getudfvalue("u_decisiondate"));
		//} elseif ($objTable->getudfvalue("u_type")=="RENEW") {
			//$duedate2 = dateadd("yyyy",$objRsu_LGUSetup->fields["U_MTOPVALIDITY"],$objTable->getudfvalue("u_prevexpdate"));
		//} else {
		//	$duedate2 = $objTable->getudfvalue("u_prevexpdate");
		//}
		//$objTable->setudfvalue("u_expdate",$duedate2);	
	//}	
	//	var_dump(array($objLGUBills->getudfvalue("u_docdate"),$objLGUBills->getudfvalue("u_duedate")));
	$objLGUBills->setudfvalue("u_remarks","PLTO - ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
	//$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_MOTORVIOLATIONAPPFEES A where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$totalamount=0;
	while ($objRs->queryfetchrow("NAME")) {
		/*if ($ctr!=1) {
			if ($objRs->fields["U_YEARLY"]!=1) continue;
		}*/	
		//var_dump($objRs->fields);
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
	}
	if ($actionReturn) {
		$objLGUBills->setudfvalue("u_totalamount",$totalamount);
		$objLGUBills->setudfvalue("u_dueamount",$totalamount);
		$actionReturn = $objLGUBills->add();
	}

	
/*	
	while (true) {
		$ctr++;
		$objLGUBills->prepareadd();
		$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
		$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
		$objLGUBills->docstatus = "O";
		$objLGUBills->setudfvalue("u_profitcenter","MTOP");
		$objLGUBills->setudfvalue("u_module","MTOP");
		$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
		$objLGUBills->setudfvalue("u_appno",$objTable->docno);
		$objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_franchiseno"));
		if ($objTable->getudfvalue("u_lastname")!="") {
			$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_lastname").", ".$objTable->getudfvalue("u_firstname")." ".$objTable->getudfvalue("u_middlename")));
		} else {
			$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_dlastname").", ".$objTable->getudfvalue("u_dfirstname")." ".$objTable->getudfvalue("u_dmiddlename")));
		}			
		if ($ctr==1) {
			$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
			$objLGUBills->setudfvalue("u_duedate",$objLGUBills->getudfvalue("u_docdate"));//dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
		} else {	
			$objLGUBills->setudfvalue("u_docdate",dateadd("yyyy",$ctr-1,$objTable->getudfvalue("u_year")."-01-01"));
			$objLGUBills->setudfvalue("u_duedate",dateadd("d",19,$objLGUBills->getudfvalue("u_docdate")));
		}	
		$objTable->setudfvalue("u_expdate",date('Y',strtotime($objLGUBills->getudfvalue("u_docdate")))."-12-31");	
		//	var_dump(array($objLGUBills->getudfvalue("u_docdate"),$objLGUBills->getudfvalue("u_duedate")));
		$objLGUBills->setudfvalue("u_remarks","MTOP - ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
		$totalamount=0;
		while ($objRs->queryfetchrow("NAME")) {
			if ($ctr!=1) {
				if ($objRs->fields["U_YEARLY"]!=1) continue;
			}	
			//var_dump($objRs->fields);
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
		}
		if ($actionReturn) {
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
			$actionReturn = $objLGUBills->add();
		}
		if ($ctr==$objRsu_LGUSetup->fields["U_MTOPVALIDITY"]) break;
	}	
*/
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcreatebilldocumentschema_brGPSMTOP()");

	return $actionReturn;
}

?> 

