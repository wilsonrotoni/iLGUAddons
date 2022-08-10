<?php
   global $ctrerror;
function onBeforeAddEventdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_rpfaas1":
			switch ($objTable->getudfvalue("u_effqtr")) {
				case "1": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-01-01"); break;
				case "2": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-04-01"); break;
				case "3": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-07-01"); break;
				case "4": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-10-01"); break;
			}
			break;
		case "u_rpfaas2":
			switch ($objTable->getudfvalue("u_effqtr")) {
				case "1": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-01-01"); break;
				case "2": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-04-01"); break;
				case "3": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-07-01"); break;
				case "4": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-10-01"); break;
			}
			break;
		case "u_rpfaas3":
			switch ($objTable->getudfvalue("u_effqtr")) {
				case "1": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-01-01"); break;
				case "2": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-04-01"); break;
				case "3": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-07-01"); break;
				case "4": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-10-01"); break;
			}
			break;
		 case "u_rptaxbill":
				 $objTable->setudfvalue("u_savestatus",1);
				break;
                case "u_rptaxes":
                     $objTable->setudfvalue("u_dueamount",$objTable->getudfvalue("u_totaltaxamount"));
                                               
//                        $objRs_uLGUSetup = new recordset(null,$objConnection);
//                        $objRs_uLGUSetup->queryopen("select A.u_rptasessortreasurylink from U_LGUSETUP A");
//                        if ($objRs_uLGUSetup->fields["u_rptasessortreasurylink"]==0) {
//                            $obju_RPtaxes = new documentschema_br(null,$objConnection,"u_rptaxes");
//		
//                                if ($obju_RPtaxes->getbykey($objTable->getudfvalue("docid"))) {
//                                    $objTable->setudfvalue("u_billdate",$obju_RPtaxes->getudfvalue("u_assdate"));
//                                }else  return raiseError("Error updating record. Try again, if problem persist, check the connection");
//                                if (!$actionReturn) break;
//                            
//                        }	
                    break;
	}
	return $actionReturn;
}
 
function onAddEventdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_rpfaas1":
			$actionReturn = onCustomEventupdateprevlandassessmentdocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas1a":
			$actionReturn = onCustomEventupdatelandassessvaluedocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas2":
			$actionReturn = onCustomEventupdateprevbldgassessmentdocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas2a":
			$actionReturn = onCustomEventupdatebldgassessvaluedocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas3":
			$actionReturn = onCustomEventupdateprevmachineassessmentdocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas3a":
			$actionReturn = onCustomEventupdatemachineassessvaluedocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rptaxbill":
			$actionReturn = onCustomEventTaxBillcreatebilldocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpnotice":
			$actionReturn = onCustomEventupdatefaasNoticeNo123documentschema_brGPSLGU($objTable);
			break;
		case "u_rptaxarps":
                        
		case "u_rptaxes":
			if ($objTable->docstatus!="D") {
//                                
				$actionReturn = onCustomEventcreatebilldocumentschema_brGPSRPTAS($objTable);
//				$actionReturn = onCustomEventcreatePosdocumentschema_brGPSRPTAS($objTable);
//				$actionReturn = onCustomEventcreatePosdocumentschema_brGPSRPTAS($objTable);
////				if ($actionReturn) $actionReturn = onCustomEventupdatefaasdocumentschema_brGPSRPTAS($objTable);
			}	
			break;
		case "u_rpupdpays":
			if ($actionReturn) $actionReturn = onCustomEventupdatefaasbilyeardocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpvalidatepayments":
                    if ($objTable->docstatus!="Pending") {
                        if ($actionReturn) $actionReturn = onCustomEventValidateRPTPaymentsdocumentschema_brGPSRPTAS($objTable);
                    }
			break;
	}
	return $actionReturn;
}

function onBeforeUpdateEventdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_rpfaas1":
		case "u_rpfaas2":
		case "u_rpfaas3":
			switch ($objTable->getudfvalue("u_effqtr")) {
				case "1": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-01-01"); break;
				case "2": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-04-01"); break;
				case "3": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-07-01"); break;
				case "4": $objTable->setudfvalue("u_effdate",$objTable->getudfvalue("u_effyear") . "-10-01"); break;
			}
			if ($objTable->getudfvalue("u_prevarpno")!="" && $objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				if ($objTable->getudfvalue("u_effdate")<=$objTable->getudfvalue("u_preveffdate")) {
					$page->setitem("docstatus","Encoding");
					return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$objTable->getudfvalue("u_preveffdate")."].");
				}	
			}	
			if ($objTable->getudfvalue("u_assessedby")!="" && $objTable->getudfvalue("u_assesseddate")=="") {
				$objTable->setudfvalue("u_assesseddate",currentdateDB());
			}
			if ($objTable->getudfvalue("u_recommendby")!="" && $objTable->getudfvalue("u_recommenddate")=="") {
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			}
			if ($objTable->getudfvalue("u_approvedby")!="" && $objTable->getudfvalue("u_approveddate")=="") {
				$objTable->setudfvalue("u_approveddate",currentdateDB());
			}
                        if($objTable->dbtable=="u_rpfaas2" || $objTable->dbtable=="u_rpfaas3" ){
                            if ($objTable->getudfvalue("u_trxcode")=="CN" && $objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
                                $objTable->setudfvalue("u_cancelled",1);
                            }
                        }
                        
			break;
	}
	if ($actionReturn) {
		switch ($objTable->dbtable) {
			case "u_rpfaas1":
				if ($objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
					if ($objTable->getudfvalue("u_prevarpno")!="") $actionReturn = onCustomEventcancellandassessmentdocumentschema_brGPSRPTAS($objTable);
					if ($actionReturn) $actionReturn = onCustomEventcancellandassessment2documentschema_brGPSRPTAS($objTable);
					if (!$actionReturn) $page->setitem("docstatus","Encoding");
				}
                                if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
                                        $docseriestd = getSeries("U_RPFAASTD",$objTable->getudfvalue("u_barangay"));
                                        $docseriesarpno = getSeries("U_RPFAASARPNO",$objTable->getudfvalue("u_barangay"));
                                        if ($objTable->getudfvalue("u_tdno")=="?") {
                                                $refno = getNextSeriesNoByBranch("U_RPFAASTD",$docseriestd,$objTable->getudfvalue("u_approveddate"));
                                                $objTable->setudfvalue("u_tdno",$refno);
                                        }
                                        if ($objTable->getudfvalue("u_varpno")=="?") {
                                                $refno2 = getNextSeriesNoByBranch("U_RPFAASARPNO",$docseriesarpno,$objTable->getudfvalue("u_approveddate"));
                                                $objTable->setudfvalue("u_varpno",$refno2);
                                        }
                                }
				break;
			case "u_rpfaas2":
				if ($objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
					if ($objTable->getudfvalue("u_prevarpno")!="") $actionReturn = onCustomEventcancelbldgassessmentdocumentschema_brGPSRPTAS($objTable);
                                        if ($actionReturn) $actionReturn = onCustomEventcancelbldgassessment2documentschema_brGPSRPTAS($objTable);
					if (!$actionReturn) $page->setitem("docstatus","Encoding");
                                        
				}
                                if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
                                        $docseriestd = getSeries("U_RPFAASTD",$objTable->getudfvalue("u_barangay"));
                                        $docseriesarpno = getSeries("U_RPFAASARPNO",$objTable->getudfvalue("u_barangay"));
                                        if ($objTable->getudfvalue("u_tdno")=="?") {
                                                $refno = getNextSeriesNoByBranch("U_RPFAASTD",$docseriestd,$objTable->getudfvalue("u_approveddate"));
                                                $objTable->setudfvalue("u_tdno",$refno);
                                        }
                                        if ($objTable->getudfvalue("u_varpno")=="?") {
                                                $refno2 = getNextSeriesNoByBranch("U_RPFAASARPNO",$docseriesarpno,$objTable->getudfvalue("u_approveddate"));
                                                $objTable->setudfvalue("u_varpno",$refno2);
                                        }
                                }
				break;
			case "u_rpfaas3":
				if ($objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
					if ($objTable->getudfvalue("u_prevarpno")!="") $actionReturn = onCustomEventcancelmachineassessmentdocumentschema_brGPSRPTAS($objTable);
					if ($actionReturn) $actionReturn = onCustomEventcancelmachineassessment2documentschema_brGPSRPTAS($objTable);
					if (!$actionReturn) $page->setitem("docstatus","Encoding");
				}
                                if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
                                        $docseriestd = getSeries("U_RPFAASTD",$objTable->getudfvalue("u_barangay"));
                                        $docseriesarpno = getSeries("U_RPFAASARPNO",$objTable->getudfvalue("u_barangay"));
                                        if ($objTable->getudfvalue("u_tdno")=="?") {
                                                $refno = getNextSeriesNoByBranch("U_RPFAASTD",$docseriestd,$objTable->getudfvalue("u_approveddate"));
                                                $objTable->setudfvalue("u_tdno",$refno);
                                        }
                                        if ($objTable->getudfvalue("u_varpno")=="?") {
                                                $refno2 = getNextSeriesNoByBranch("U_RPFAASARPNO",$docseriesarpno,$objTable->getudfvalue("u_approveddate"));
                                                $objTable->setudfvalue("u_varpno",$refno2);
                                        }
                                }
				break;
		}	
	}	
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}


function onUpdateEventdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
                case "u_rpfaas1":
                case "u_rpfaas2":
                case "u_rpfaas3":
                      $actionReturn = onCustomEventupdatenoticesdocumentschema_brGPSRPTAS($objTable);
                        break;
		case "u_rpfaas1a":
			$actionReturn = onCustomEventupdatelandassessvaluedocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas2a":
			$actionReturn = onCustomEventupdatebldgassessvaluedocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpfaas3a":
			$actionReturn = onCustomEventupdatemachineassessvaluedocumentschema_brGPSRPTAS($objTable);
			break;
		case "u_rpupdpays":
			if ($actionReturn) $actionReturn = onCustomEventupdatefaasbilyeardocumentschema_brGPSRPTAS($objTable);
			break;
                case "u_rpnotice":
			$actionReturn = onCustomEventupdatefaasNoticeNo123documentschema_brGPSLGU($objTable);
			break;
		case "u_rptaxes":
			if ($objTable->docstatus=="C" && $objTable->fields["DOCSTATUS"]=="O") {
//                            if ($actionReturn)  $actionReturn = onCustomEventcreatebilldocumentschema_brGPSRPTAS($objTable);
                            if ($actionReturn)  $actionReturn = onCustomEventcreatePosdocumentschema_brGPSRPTAS($objTable);
                            if ($actionReturn)  $actionReturn = onCustomEventupdatefaas123documentschema_brGPSLGU($objTable);
//				if ($actionReturn) $actionReturn = onCustomEventupdatefaasdocumentschema_brGPSRPTAS($objTable);
			}	
			break;
		case "u_rpnoticeofcancel":
			if ($objTable->docstatus=="C" && $objTable->fields["DOCSTATUS"]=="O") {
                            if ($actionReturn)  $actionReturn = onCustomEventcancelfaasdocumentschema_brGPSRPTAS($objTable);
			}	
			break;
                case "u_rpvalidatepayments":
                    if (($objTable->docstatus=="Approved" || $objTable->docstatus=="Disapproved" )&& $objTable->fields["DOCSTATUS"]=="Pending") {
                        if ($actionReturn) $actionReturn = onCustomEventValidateRPTPaymentsdocumentschema_brGPSRPTAS($objTable);
                    }
                    break;
	}
	return $actionReturn;
}

function onCustomEventcancellandassessmentdocumentschema_brGPSRPTAS($objTable,$previous=true) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
	if ($previous) {
		$obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas1");
		
		if ($obju_RPFaas->getbykey($objTable->getudfvalue("u_prevarpno"))) {
			if ($objTable->getudfvalue("u_effdate")>$obju_RPFaas->getudfvalue("u_effdate")) {
                           
				$u_expdate = dateadd("d",-1,$objTable->getudfvalue("u_effdate"));
				$u_month = intval(date('m',strtotime($u_expdate)));
				$u_year = intval(date('Y',strtotime($u_expdate)));
				$obju_RPFaas->setudfvalue("u_expdate",$u_expdate);
				$obju_RPFaas->setudfvalue("u_expqtr",ceil($u_month/3));
				$obju_RPFaas->setudfvalue("u_expyear",$u_year);
				$obju_RPFaas->setudfvalue("u_cancelled",1);
				$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
			} else return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$obju_RPFaas->getudfvalue("u_effdate")."].");
		} else return raiseError("Unable to find previous ARP No. [".$objTable->getudfvalue("u_prevarpno")."].");
	} else {
	}		
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}	

function onCustomEventcancelfaasdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
        $obju_RPFaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
        $obju_RPFaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
        $obju_RPFaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
		
		if ($obju_RPFaas1->getbykey($objTable->getudfvalue("u_appno"))) {
                    $obju_RPFaas1->setudfvalue("u_expdate",$objTable->getudfvalue("u_datecancel"));
                    $obju_RPFaas1->setudfvalue("u_expqtr",4);
                    $obju_RPFaas1->setudfvalue("u_expyear",$objTable->getudfvalue("u_endyear"));
                    $obju_RPFaas1->setudfvalue("u_cancelled",1);
                    $actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion);
		} 
                
		if ($obju_RPFaas2->getbykey($objTable->getudfvalue("u_appno"))) {
                    $obju_RPFaas2->setudfvalue("u_expdate",$objTable->getudfvalue("u_datecancel"));
                    $obju_RPFaas2->setudfvalue("u_expqtr",4);
                    $obju_RPFaas2->setudfvalue("u_expyear",$objTable->getudfvalue("u_endyear"));
                    $obju_RPFaas2->setudfvalue("u_cancelled",1);
                    $actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion);
		} 
                
		if ($obju_RPFaas3->getbykey($objTable->getudfvalue("u_appno"))) {
                    $obju_RPFaas3->setudfvalue("u_expdate",$objTable->getudfvalue("u_datecancel"));
                    $obju_RPFaas3->setudfvalue("u_expqtr",4);
                    $obju_RPFaas3->setudfvalue("u_expyear",$objTable->getudfvalue("u_endyear"));
                    $obju_RPFaas3->setudfvalue("u_cancelled",1);
                    $actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion);
		} 
			
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}	
function onCustomEventcancelbldgassessmentdocumentschema_brGPSRPTAS($objTable,$previous=true) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
	if ($previous) {
		$obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas2");
		
		if ($obju_RPFaas->getbykey($objTable->getudfvalue("u_prevarpno"))) {
			if ($objTable->getudfvalue("u_effdate")>$obju_RPFaas->getudfvalue("u_effdate")) {
				$u_expdate = dateadd("d",-1,$objTable->getudfvalue("u_effdate"));
				$u_month = intval(date('m',strtotime($u_expdate)));
				$u_year = intval(date('Y',strtotime($u_expdate)));
				$obju_RPFaas->setudfvalue("u_expdate",$u_expdate);
				$obju_RPFaas->setudfvalue("u_expqtr",ceil($u_month/3));
				$obju_RPFaas->setudfvalue("u_expyear",$u_year);
				$obju_RPFaas->setudfvalue("u_cancelled",1);
				$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
			} else return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$obju_RPFaas->getudfvalue("u_effdate")."].");
		} else return raiseError("Unable to find previous ARP No. [".$objTable->getudfvalue("u_prevarpno")."].");
	} else {
	}		
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}	

function onCustomEventcancelmachineassessmentdocumentschema_brGPSRPTAS($objTable,$previous=true) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
	if ($previous) {
		$obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas3");
		
		if ($obju_RPFaas->getbykey($objTable->getudfvalue("u_prevarpno"))) {
			if ($objTable->getudfvalue("u_effdate")>$obju_RPFaas->getudfvalue("u_effdate")) {
				$u_expdate = dateadd("d",-1,$objTable->getudfvalue("u_effdate"));
				$u_month = intval(date('m',strtotime($u_expdate)));
				$u_year = intval(date('Y',strtotime($u_expdate)));
				$obju_RPFaas->setudfvalue("u_expdate",$u_expdate);
				$obju_RPFaas->setudfvalue("u_expqtr",ceil($u_month/3));
				$obju_RPFaas->setudfvalue("u_expyear",$u_year);
				$obju_RPFaas->setudfvalue("u_cancelled",1);
				$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
			} else return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$obju_RPFaas->getudfvalue("u_effdate")."].");
		} else return raiseError("Unable to find previous ARP No. [".$objTable->getudfvalue("u_prevarpno")."].");
	} else {
	}		
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}	

function onCustomEventcancellandassessment2documentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
	
	$obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$objRs->queryopen("select u_prevarpno from u_rpfaas1p where company='".$objTable->company."' and branch='".$objTable->branch."' and docid='".$objTable->docid."'");
	while ($objRs->queryfetchrow("NAME")) {
		if ($obju_RPFaas->getbykey($objRs->fields["u_prevarpno"])) {
			if ($objTable->getudfvalue("u_effdate")>$obju_RPFaas->getudfvalue("u_effdate")) {
				$u_expdate = dateadd("d",-1,$objTable->getudfvalue("u_effdate"));
				$u_month = intval(date('m',strtotime($u_expdate)));
				$u_year = intval(date('Y',strtotime($u_expdate)));
				$obju_RPFaas->setudfvalue("u_expdate",$u_expdate);
				$obju_RPFaas->setudfvalue("u_expqtr",ceil($u_month/3));
				$obju_RPFaas->setudfvalue("u_expyear",$u_year);
				$obju_RPFaas->setudfvalue("u_cancelled",1);
				$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
			} else return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$obju_RPFaas->getudfvalue("u_effdate")."].");
		} else return raiseError("Unable to find previous ARP No. [".$objTable->getudfvalue("u_prevarpno")."].");
	}		
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessment2documentschema_brGPSRPTAS()");
	return $actionReturn;
	
}

function onCustomEventcancelbldgassessment2documentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
	
	$obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$objRs->queryopen("select u_prevarpno from u_rpfaas2p where company='".$objTable->company."' and branch='".$objTable->branch."' and docid='".$objTable->docid."'");
	while ($objRs->queryfetchrow("NAME")) {
		if ($obju_RPFaas->getbykey($objRs->fields["u_prevarpno"])) {
			if ($objTable->getudfvalue("u_effdate")>$obju_RPFaas->getudfvalue("u_effdate")) {
				$u_expdate = dateadd("d",-1,$objTable->getudfvalue("u_effdate"));
				$u_month = intval(date('m',strtotime($u_expdate)));
				$u_year = intval(date('Y',strtotime($u_expdate)));
				$obju_RPFaas->setudfvalue("u_expdate",$u_expdate);
				$obju_RPFaas->setudfvalue("u_expqtr",ceil($u_month/3));
				$obju_RPFaas->setudfvalue("u_expyear",$u_year);
				$obju_RPFaas->setudfvalue("u_cancelled",1);
				$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
			} else return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$obju_RPFaas->getudfvalue("u_effdate")."].");
		} else return raiseError("Unable to find previous ARP No. [".$objTable->getudfvalue("u_prevarpno")."].");
	}		
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessment2documentschema_brGPSRPTAS()");
	return $actionReturn;
	
}

function onCustomEventcancelmachineassessment2documentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;

	$objRs = new recordset (NULL,$objConnection);
	
	$obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas3");
	$objRs->queryopen("select u_prevarpno from u_rpfaas3p where company='".$objTable->company."' and branch='".$objTable->branch."' and docid='".$objTable->docid."'");
	while ($objRs->queryfetchrow("NAME")) {
		if ($obju_RPFaas->getbykey($objRs->fields["u_prevarpno"])) {
			if ($objTable->getudfvalue("u_effdate")>$obju_RPFaas->getudfvalue("u_effdate")) {
				$u_expdate = dateadd("d",-1,$objTable->getudfvalue("u_effdate"));
				$u_month = intval(date('m',strtotime($u_expdate)));
				$u_year = intval(date('Y',strtotime($u_expdate)));
				$obju_RPFaas->setudfvalue("u_expdate",$u_expdate);
				$obju_RPFaas->setudfvalue("u_expqtr",ceil($u_month/3));
				$obju_RPFaas->setudfvalue("u_expyear",$u_year);
				$obju_RPFaas->setudfvalue("u_cancelled",1);
				$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
			} else return raiseError("New assessment [".$objTable->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$obju_RPFaas->getudfvalue("u_effdate")."].");
		} else return raiseError("Unable to find previous ARP No. [".$objTable->getudfvalue("u_prevarpno")."].");
	}		
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcancellandassessment2documentschema_brGPSRPTAS()");
	return $actionReturn;
	
}

function onCustomEventupdateprevlandassessmentdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue("u_prevarpno")=="") return true;
	if ($objTable->getudfvalue("u_trxcode")=="SG") return true;
	if ($objTable->docstatus == "Approved") return true;

	$objRs = new recordset (NULL,$objConnection);
        $obju_RPNotice = new documentschema_br(null,$objConnection,"u_rpnotice");
	$obju_RPNoticeItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
        
	$obju_RPFaas1aOld = new documentschema_br(null,$objConnection,"u_rpfaas1a");
	$obju_RPFaas1aNew = new documentschema_br(null,$objConnection,"u_rpfaas1a");

	$obju_RPFaas1bOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
	$obju_RPFaas1bNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");

	$obju_RPFaas1cOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
	$obju_RPFaas1cNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
        
        $obju_RPFaas1dOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
	$obju_RPFaas1dNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
        
        $obju_RPFaas1eOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas1e");
	$obju_RPFaas1eNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1e");
	
	$obju_RPFaas1aOld->queryopen($obju_RPFaas1aOld->selectstring()." AND U_ARPNO='".$objTable->getudfvalue("u_prevarpno")."'");
	while ($obju_RPFaas1aOld->queryfetchrow()) {
		$obju_RPFaas1aNew->prepareadd();
		$obju_RPFaas1aNew->docid = getNextIDByBranch("u_rpfaas1a",$objConnection);
		$obju_RPFaas1aNew->docno = getNextNoByBranch("u_rpfaas1a","",$objConnection);
		$obju_RPFaas1aNew->copyudfsvalue($obju_RPFaas1aOld);
		$obju_RPFaas1aNew->setudfvalue("u_arpno",$objTable->docno);
		$obju_RPFaas1aNew->setudfvalue("u_gryear",$objTable->getudfvalue("u_revisionyear"));
		if ($actionReturn) {
			$obju_RPFaas1bOld->queryopen($obju_RPFaas1bOld->selectstring()." AND DOCID='".$obju_RPFaas1aOld->docid."'");
			while ($obju_RPFaas1bOld->queryfetchrow()) {
				$obju_RPFaas1bNew->prepareadd();
				$obju_RPFaas1bNew->docid = $obju_RPFaas1aNew->docid;
				$obju_RPFaas1bNew->lineid = getNextIDByBranch("u_rpfaas1b",$objConnection);
				$obju_RPFaas1bNew->copyudfsvalue($obju_RPFaas1bOld);
				$actionReturn = $obju_RPFaas1bNew->add();
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) {
			$obju_RPFaas1cOld->queryopen($obju_RPFaas1cOld->selectstring()." AND DOCID='".$obju_RPFaas1aOld->docid."'");
			while ($obju_RPFaas1cOld->queryfetchrow()) {
				$obju_RPFaas1cNew->prepareadd();
				$obju_RPFaas1cNew->docid = $obju_RPFaas1aNew->docid;
				$obju_RPFaas1cNew->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
				$obju_RPFaas1cNew->copyudfsvalue($obju_RPFaas1cOld);
				$actionReturn = $obju_RPFaas1cNew->add();
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) {
			$obju_RPFaas1dOld->queryopen($obju_RPFaas1dOld->selectstring()." AND DOCID='".$obju_RPFaas1aOld->docid."'");
			while ($obju_RPFaas1dOld->queryfetchrow()) {
				$obju_RPFaas1dNew->prepareadd();
				$obju_RPFaas1dNew->docid = $obju_RPFaas1aNew->docid;
				$obju_RPFaas1dNew->lineid = getNextIDByBranch("u_rpfaas1d",$objConnection);
				$obju_RPFaas1dNew->copyudfsvalue($obju_RPFaas1dOld);
				$actionReturn = $obju_RPFaas1dNew->add();
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) {
			$obju_RPFaas1eOld->queryopen($obju_RPFaas1eOld->selectstring()." AND DOCID='".$obju_RPFaas1aOld->docid."'");
			while ($obju_RPFaas1eOld->queryfetchrow()) {
				$obju_RPFaas1eNew->prepareadd();
				$obju_RPFaas1eNew->docid = $obju_RPFaas1aNew->docid;
				$obju_RPFaas1eNew->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
				$obju_RPFaas1eNew->copyudfsvalue($obju_RPFaas1eOld);
				$actionReturn = $obju_RPFaas1eNew->add();
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) $actionReturn = $obju_RPFaas1aNew->add();
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdateprevlandassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}

function onCustomEventupdateprevbldgassessmentdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue("u_prevarpno")=="") return true;
        if ($objTable->docstatus == "Approved") return true;
        
	$objRs = new recordset (NULL,$objConnection);
	$obju_RPFaas2Old = new documentschema_br(null,$objConnection,"u_rpfaas2");

	$obju_RPFaas2cOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas2c");
	$obju_RPFaas2cNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas2c");

	$obju_RPFaas2bOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
	$obju_RPFaas2bNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");

	$obju_RPFaas2dOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
	$obju_RPFaas2dNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
	
	$obju_RPFaas2aOld = new documentschema_br(null,$objConnection,"u_rpfaas2a");
	$obju_RPFaas2aNew = new documentschema_br(null,$objConnection,"u_rpfaas2a");

	$obju_RPFaas2eOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");
	$obju_RPFaas2eNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");
	
	$obju_RPFaas2aOld->queryopen($obju_RPFaas2aOld->selectstring()." AND U_ARPNO='".$objTable->getudfvalue("u_prevarpno")."'");
	while ($obju_RPFaas2aOld->queryfetchrow()) {
		$obju_RPFaas2aNew->prepareadd();
		$obju_RPFaas2aNew->docid = getNextIDByBranch("u_rpfaas2a",$objConnection);
		$obju_RPFaas2aNew->docno = getNextNoByBranch("u_rpfaas2a","",$objConnection);
		$obju_RPFaas2aNew->copyudfsvalue($obju_RPFaas2aOld);
		$obju_RPFaas2aNew->setudfvalue("u_arpno",$objTable->docno);
		$obju_RPFaas2aNew->setudfvalue("u_gryear",$objTable->getudfvalue("u_revisionyear"));
		if ($actionReturn) {
			$obju_RPFaas2bOld->queryopen($obju_RPFaas2bOld->selectstring()." AND DOCID='".$obju_RPFaas2aOld->docid."'");
			while ($obju_RPFaas2bOld->queryfetchrow()) {
				$obju_RPFaas2bNew->prepareadd();
				$obju_RPFaas2bNew->copyudfsvalue($obju_RPFaas2bOld);
				$obju_RPFaas2bNew->docid = $obju_RPFaas2aNew->docid;
				$obju_RPFaas2bNew->lineid = getNextIDByBranch("u_rpfaas2b",$objConnection);
				$actionReturn = $obju_RPFaas2bNew->add();
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) {
			$obju_RPFaas2eOld->queryopen($obju_RPFaas2eOld->selectstring()." AND DOCID='".$obju_RPFaas2aOld->docid."'");
			while ($obju_RPFaas2eOld->queryfetchrow()) {
				$obju_RPFaas2eNew->prepareadd();
				$obju_RPFaas2eNew->copyudfsvalue($obju_RPFaas2eOld);
				$obju_RPFaas2eNew->docid = $obju_RPFaas2aNew->docid;
				$obju_RPFaas2eNew->lineid = getNextIDByBranch("u_rpfaas2e",$objConnection);
				$actionReturn = $obju_RPFaas2eNew->add();
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) $actionReturn = $obju_RPFaas2aNew->add();
	}
	
	if ($actionReturn) {
		if ($obju_RPFaas2Old->getbykey($objTable->getudfvalue("u_prevarpno"))) {
			if ($actionReturn) {
				$obju_RPFaas2cOld->queryopen($obju_RPFaas2cOld->selectstring()." AND DOCID='".$obju_RPFaas2aOld->docid."'");
				while ($obju_RPFaas2cOld->queryfetchrow()) {
					$obju_RPFaas2cNew->prepareadd();
					$obju_RPFaas2cNew->copyudfsvalue($obju_RPFaas2cOld);
					$obju_RPFaas2cNew->docid = $objTable->docid;
					$obju_RPFaas2cNew->lineid = getNextIDByBranch("u_rpfaas2c",$objConnection);
					$actionReturn = $obju_RPFaas2cNew->add();
					if (!$actionReturn) break;
				}	
			}	
			if ($actionReturn) {
				$actionReturn = $obju_RPFaas2dNew->executesql("delete from u_rpfaas2d where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
				if ($actionReturn) {
					$obju_RPFaas2dOld->queryopen($obju_RPFaas2dOld->selectstring()." AND DOCID='".$obju_RPFaas2Old->docid."'");
					while ($obju_RPFaas2dOld->queryfetchrow()) {
						$obju_RPFaas2dNew->prepareadd();
						$obju_RPFaas2dNew->copyudfsvalue($obju_RPFaas2dOld);
						$obju_RPFaas2dNew->docid = $objTable->docid;
						$obju_RPFaas2dNew->lineid = getNextIDByBranch("u_rpfaas2d",$objConnection);
						$actionReturn = $obju_RPFaas2dNew->add();
						if (!$actionReturn) break;
					}	
				}	
			}	
		} else return raiseError("Unable to find previous ARP No.[".$objTable->getudfvalue("u_prevarpno")."]");	
	}	
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdateprevbldgassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}

function onCustomEventupdateprevmachineassessmentdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue("u_prevarpno")=="") return true;
        if ($objTable->docstatus == "Approved") return true;
        
	$objRs = new recordset (NULL,$objConnection);
	$obju_RPFaas3Old = new documentschema_br(null,$objConnection,"u_rpfaas3");

	$obju_RPFaas3bOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");
	$obju_RPFaas3bNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");

	$obju_RPFaas3aOld = new documentschema_br(null,$objConnection,"u_rpfaas3a");
	$obju_RPFaas3aNew = new documentschema_br(null,$objConnection,"u_rpfaas3a");

	$obju_RPFaas3aOld->queryopen($obju_RPFaas3aOld->selectstring()." AND U_ARPNO='".$objTable->getudfvalue("u_prevarpno")."'");
	while ($obju_RPFaas3aOld->queryfetchrow()) {
		$obju_RPFaas3aNew->prepareadd();
		$obju_RPFaas3aNew->docid = getNextIDByBranch("u_rpfaas3a",$objConnection);
		$obju_RPFaas3aNew->docno = getNextNoByBranch("u_rpfaas3a","",$objConnection);
		$obju_RPFaas3aNew->copyudfsvalue($obju_RPFaas3aOld);
		$obju_RPFaas3aNew->setudfvalue("u_arpno",$objTable->docno);
		$obju_RPFaas3aNew->setudfvalue("u_gryear",$objTable->getudfvalue("u_revisionyear"));

		if ($actionReturn) $actionReturn = $obju_RPFaas3aNew->add();
	}
	
	if ($actionReturn) {
		if ($obju_RPFaas3Old->getbykey($objTable->getudfvalue("u_prevarpno"))) {
		$actionReturn = $obju_RPFaas3bNew->executesql("delete from u_rpfaas3b where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
		if ($actionReturn) {
				$obju_RPFaas3bOld->queryopen($obju_RPFaas3bOld->selectstring()." AND DOCID='".$obju_RPFaas3aOld->docid."'");
				while ($obju_RPFaas3bOld->queryfetchrow()) {
					$obju_RPFaas3bNew->prepareadd();
					$obju_RPFaas3bNew->copyudfsvalue($obju_RPFaas3bOld);
					$obju_RPFaas3bNew->docid = $objTable->docid;
					$obju_RPFaas3bNew->lineid = getNextIDByBranch("u_rpfaas3b",$objConnection);
					$actionReturn = $obju_RPFaas3bNew->add();
					if (!$actionReturn) break;
				}	
			}	

		} else return raiseError("Unable to find previous ARP No.[".$objTable->getudfvalue("u_prevarpno")."]");	
	}	
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdateprevbldgassessmentdocumentschema_brGPSRPTAS()");
	return $actionReturn;
	
}

function onCustomEventupdatelandassessvaluedocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$obju_RPFaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
        $obju_RPNotice = new documentschema_br(null,$objConnection,"u_rpnotice");
        $obju_RPNoticeItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
        
	if ($obju_RPFaas1->getbykey($objTable->getudfvalue("u_arpno")))	{
		$objRs->queryopen("SELECT SUM(U_ASSVALUE) AS U_ASSVALUE,SUM(U_SQM) AS U_SQM,SUM(U_MARKETVALUE) AS U_MARKETVALUE FROM U_RPFAAS1A WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_ARPNO='".$objTable->getudfvalue("u_arpno")."' AND U_TAXABLE = 1");
		if ($objRs->queryfetchrow("NAME")) {
			$obju_RPFaas1->setudfvalue("u_totalareasqm", $objRs->fields["U_SQM"]);
			$obju_RPFaas1->setudfvalue("u_assvalue", $objRs->fields["U_ASSVALUE"]);
			$obju_RPFaas1->setudfvalue("u_marketvalue", $objRs->fields["U_MARKETVALUE"]);
			$actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion);
		}
                $objRs->queryopen("SELECT MAX(U_ASSVALUE),B.NAME AS U_CLASS FROM U_RPFAAS1A A LEFT JOIN U_RPLANDS B ON A.U_CLASS = B.CODE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$objTable->getudfvalue("u_arpno")."'");
		if ($objRs->queryfetchrow("NAME")) {
			$obju_RPFaas1->setudfvalue("u_class", $objRs->fields["U_CLASS"]);
			$actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion);
		}
                if ($obju_RPNotice->getbykey($obju_RPFaas1->getudfvalue("u_noticeno"))) {
                        $assvalue = 0;
                        $marketvalue = 0;
                        $obju_RPNotice->setudfvalue("u_declaredowner", addslashes($obju_RPFaas1->getudfvalue("u_ownercompanyname")));
                        $obju_RPNotice->setudfvalue("u_tin", $obju_RPFaas1->getudfvalue("u_ownertin"));
                        
                        if ($obju_RPNoticeItems->getbysql("DOCID = '$obju_RPNotice->docid'")) {
                            if ($obju_RPFaas1->docno == $obju_RPNoticeItems->getudfvalue("u_docno")) {
                                $obju_RPNoticeItems->setudfvalue("u_class",$obju_RPFaas1->getudfvalue("u_class"));
                                $obju_RPNoticeItems->setudfvalue("u_marketvalue",$obju_RPFaas1->getudfvalue("u_marketvalue"));
                                $obju_RPNoticeItems->setudfvalue("u_assvalue",$obju_RPFaas1->getudfvalue("u_assvalue"));
                                $obju_RPNoticeItems->setudfvalue("u_tdno",$obju_RPFaas1->getudfvalue("u_varpno"));
                                if ($obju_RPFaas1->getudfvalue("u_subdivision") != "") $obju_RPNoticeItems->setudfvalue("u_location",$obju_RPFaas1->getudfvalue("u_subdivision"));
                                else $obju_RPNoticeItems->setudfvalue("u_location",$obju_RPFaas1->getudfvalue("u_barangay"));
                                $actionReturn = $obju_RPNoticeItems->update($obju_RPNoticeItems->docid,$obju_RPNoticeItems->lineid,$obju_RPNoticeItems->rcdversion);
                            }
                            $assvalue += $obju_RPNoticeItems->getudfvalue("u_assvalue");
                            $marketvalue += $obju_RPNoticeItems->getudfvalue("u_marketvalue");
                        }
                        $obju_RPNotice->setudfvalue("u_totalassvalue", $assvalue);
                        $obju_RPNotice->setudfvalue("u_totalmarketvalue", $marketvalue);
                        $actionReturn = $obju_RPNotice->update($obju_RPNotice->docno,$obju_RPNotice->rcdversion);
                }
	}
        
        
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatelandassessvaluedocumentschema_brGPSRPTAS()");
	return $actionReturn;
}
function onCustomEventupdatenoticesdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
        $obju_RPNotice = new documentschema_br(null,$objConnection,"u_rpnotice");
        $obju_RPNoticeItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
       
        if ($obju_RPNotice->getbykey($objTable->getudfvalue("u_noticeno"))) {
                if ($obju_RPNoticeItems->getbysql("DOCID = '$obju_RPNotice->docid'")) {
                    if ($objTable->docno == $obju_RPNoticeItems->getudfvalue("u_docno")) {
                        $obju_RPNoticeItems->setudfvalue("u_class",$objTable->getudfvalue("u_class"));
                        $obju_RPNoticeItems->setudfvalue("u_marketvalue",$objTable->getudfvalue("u_marketvalue"));
                        $obju_RPNoticeItems->setudfvalue("u_assvalue",$objTable->getudfvalue("u_assvalue"));
                        $obju_RPNoticeItems->setudfvalue("u_tdno",$objTable->getudfvalue("u_varpno"));
                        if ($objTable->getudfvalue("u_subdivision") != "") $obju_RPNoticeItems->setudfvalue("u_location",$objTable->getudfvalue("u_subdivision"));
                        else $obju_RPNoticeItems->setudfvalue("u_location",$objTable->getudfvalue("u_barangay"));
                        $actionReturn = $obju_RPNoticeItems->update($obju_RPNoticeItems->docid,$obju_RPNoticeItems->lineid,$obju_RPNoticeItems->rcdversion);
                    }
                }
        }
//	if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatenoticesdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}


function onCustomEventupdatebldgassessvaluedocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	$obju_RPFaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_RPFaas2c = new documentlinesschema_br(null,$objConnection,"u_rpfaas2c");
        
        $obju_RPNotice = new documentschema_br(null,$objConnection,"u_rpnotice");
        $obju_RPNoticeItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
	if ($obju_RPFaas2->getbykey($objTable->getudfvalue("u_arpno")))	{
		$assvalue = 0;
		$marketvalue = 0;
		$sqm = 0;
		$value = 0;
		$actionReturn = $objRs->executesql("DELETE FROM U_RPFAAS2C WHERE COMPANY='$obju_RPFaas2->company' AND BRANCH='$obju_RPFaas2->branch' AND DOCID='$obju_RPFaas2->docid'",false);
		if ($actionReturn) {
                        $objRs->queryopen("SELECT A.U_GRYEAR,A.U_WITHDECIMAL,A.U_TAXABLE,A.U_CLASS AS U_ACTUALUSE, SUM(A.U_ADJMARKETVALUE) AS U_ADJMARKETVALUE,SUM(A.U_SQM) + COALESCE(SUM(B.U_SQM),0) AS U_SQM FROM U_RPFAAS2A A LEFT JOIN (SELECT SUM(U_SQM) AS U_SQM,DOCID,COMPANY,BRANCH FROM U_RPFAAS2B GROUP BY DOCID) B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$objTable->getudfvalue("u_arpno")."' AND A.U_ASSLVLBY = 'C'  GROUP BY A.U_CLASS,A.U_TAXABLE
                                            UNION ALL 
                                            SELECT A.U_GRYEAR,A.U_WITHDECIMAL,A.U_TAXABLE,A.U_ACTUALUSE, SUM(A.U_ADJMARKETVALUE) AS U_ADJMARKETVALUE,SUM(A.U_SQM) + COALESCE(SUM(B.U_SQM),0) AS U_SQM FROM U_RPFAAS2A A LEFT JOIN (SELECT SUM(U_SQM) AS U_SQM,DOCID,COMPANY,BRANCH FROM U_RPFAAS2B GROUP BY DOCID) B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$objTable->getudfvalue("u_arpno")."'  AND A.U_ASSLVLBY = 'AC'  GROUP BY A.U_ACTUALUSE,A.U_TAXABLE ");
			while ($objRs->queryfetchrow("NAME")) {
				$asslvl=0;
				$objRs2->queryopen("SELECT U_ASSESSLEVEL FROM U_RPIMPROVEMENTFMVS WHERE CODE='".$objRs->fields["U_ACTUALUSE"]."' AND ".$objRs->fields["U_ADJMARKETVALUE"].">=U_FMVOVER AND (".$objRs->fields["U_ADJMARKETVALUE"]."<=U_FMVBUTNOTOVER OR U_FMVBUTNOTOVER=0) AND U_GRYEAR = '".$objRs->fields["U_GRYEAR"]."' ORDER BY U_FMVOVER DESC");
				if ($objRs2->queryfetchrow("NAME")) {
					$asslvl= $objRs2->fields["U_ASSESSLEVEL"];
				}
				
				$obju_RPFaas2c->prepareadd();
				$obju_RPFaas2c->docid = $obju_RPFaas2->docid;
				$obju_RPFaas2c->lineid = getNextIdByBranch("u_rpfaas2c",$objConnection);
				$obju_RPFaas2c->setudfvalue("u_taxable", $objRs->fields["U_TAXABLE"]);
				$obju_RPFaas2c->setudfvalue("u_actualuse", $objRs->fields["U_ACTUALUSE"]);
				$obju_RPFaas2c->setudfvalue("u_marketvalue", $objRs->fields["U_ADJMARKETVALUE"]);
				$obju_RPFaas2c->setudfvalue("u_asslvl",$asslvl);
				if( $objRs->fields["U_TAXABLE"]==1){
						if($objRs->fields["U_WITHDECIMAL"]==1){
							$obju_RPFaas2c->setudfvalue("u_assvalue", round(round($objRs->fields["U_ADJMARKETVALUE"]*($asslvl/100),0),-1));
							$value = round(round($objRs->fields["U_ADJMARKETVALUE"]*($asslvl/100),0),-1);
						}else{
							$value = round($objRs->fields["U_ADJMARKETVALUE"]*($asslvl/100),-1);
							$obju_RPFaas2c->setudfvalue("u_assvalue", round($objRs->fields["U_ADJMARKETVALUE"]*($asslvl/100),-1));
						}
				}else{
					$value = 0;
					$obju_RPFaas2c->setudfvalue("u_assvalue", round(round($objRs->fields["U_ADJMARKETVALUE"]*($asslvl/100),0),-1));
				}
                                if($objRs->fields["U_SQM"] >= 0 ) $sqm += $objRs->fields["U_SQM"];
                                else $sqm += 0;
				$marketvalue += $objRs->fields["U_ADJMARKETVALUE"];
				$assvalue += $value;
				$actionReturn = $obju_RPFaas2c->add();
				if (!$actionReturn) return false;
			}	
			if ($actionReturn) {
				$obju_RPFaas2->setudfvalue("u_totalareasqm", $sqm);
				$obju_RPFaas2->setudfvalue("u_marketvalue", $marketvalue);
				$obju_RPFaas2->setudfvalue("u_assvalue", $assvalue);
				$actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion);
			}
			if ($actionReturn) {
					$objRs->queryopen("SELECT MAX(U_ASSVALUE),B.NAME AS U_CLASS FROM U_RPFAAS2C A LEFT JOIN U_RPACTUSES B ON A.U_ACTUALUSE = B.CODE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='".$obju_RPFaas2->docid."'");
					if ($objRs->queryfetchrow("NAME")) {
							$obju_RPFaas2->setudfvalue("u_class", $objRs->fields["U_CLASS"]);
							$actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion);
					}
			}
                        if ($actionReturn) {
                                 if ($obju_RPNotice->getbykey($obju_RPFaas2->getudfvalue("u_noticeno"))) {
                                        $assvalue = 0;
                                        $marketvalue = 0;
                                        $obju_RPNotice->setudfvalue("u_declaredowner", $obju_RPFaas2->getudfvalue("u_ownercompanyname"));
                                        $obju_RPNotice->setudfvalue("u_tin", $obju_RPFaas2->getudfvalue("u_ownertin"));

                                        if ($obju_RPNoticeItems->getbysql("DOCID = '$obju_RPNotice->docid'")) {
                                            if ($obju_RPFaas2->docno == $obju_RPNoticeItems->getudfvalue("u_docno")) {
                                                $obju_RPNoticeItems->setudfvalue("u_class",$obju_RPFaas2->getudfvalue("u_class"));
                                                $obju_RPNoticeItems->setudfvalue("u_marketvalue",$obju_RPFaas2->getudfvalue("u_marketvalue"));
                                                $obju_RPNoticeItems->setudfvalue("u_assvalue",$obju_RPFaas2->getudfvalue("u_assvalue"));
                                                $obju_RPNoticeItems->setudfvalue("u_tdno",$obju_RPFaas2->getudfvalue("u_varpno"));
                                                if ($obju_RPFaas2->getudfvalue("u_subdivision") != "") $obju_RPNoticeItems->setudfvalue("u_location",$obju_RPFaas2->getudfvalue("u_subdivision"));
                                                else $obju_RPNoticeItems->setudfvalue("u_location",$obju_RPFaas2->getudfvalue("u_barangay"));
                                                $actionReturn = $obju_RPNoticeItems->update($obju_RPNoticeItems->docid,$obju_RPNoticeItems->lineid,$obju_RPNoticeItems->rcdversion);
                                            }
                                            $assvalue += $obju_RPNoticeItems->getudfvalue("u_assvalue");
                                            $marketvalue += $obju_RPNoticeItems->getudfvalue("u_marketvalue");
                                        }
                                        $obju_RPNotice->setudfvalue("u_totalassvalue", $assvalue);
                                        $obju_RPNotice->setudfvalue("u_totalmarketvalue", $marketvalue);
                                        $actionReturn = $obju_RPNotice->update($obju_RPNotice->docno,$obju_RPNotice->rcdversion);
                                }
                        }
		}
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdateassessvaluedocumentschema_brGPSRPTAS()");
	return $actionReturn;
}

function onCustomEventupdatemachineassessvaluedocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	$objRs3c = new recordset (NULL,$objConnection);
	$obju_RPFaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
	$obju_RPFaas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");
	$obju_RPFaas3c = new documentlinesschema_br(null,$objConnection,"u_rpfaas3c");
        
	$obju_RPNotice = new documentschema_br(null,$objConnection,"u_rpnotice");
        $obju_RPNoticeItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
        
	if ($obju_RPFaas3->getbykey($objTable->getudfvalue("u_arpno")))	{
                $assvalue = 0;
                $totalmarketvalue = 0;
                $value = 0;
		$actionReturn = $objRs->executesql("DELETE FROM U_RPFAAS3B WHERE COMPANY='$obju_RPFaas3->company' AND BRANCH='$obju_RPFaas3->branch' AND DOCID='$obju_RPFaas3->docid'",false);
		if ($actionReturn) {
			$objRs->queryopen("SELECT A.U_WITHDECIMAL,A.U_ORGCOST,A.DOCID,A.U_ESTLIFE,A.U_TAXABLE,A.U_MACHINE, A.U_ACTUALUSE, A.U_REMVALUE FROM U_RPFAAS3A A WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$objTable->getudfvalue("u_arpno")."'");
			while ($objRs->queryfetchrow("NAME")) {
				$asslvl=0;
				$objRs2->queryopen("SELECT U_ASSESSLEVEL FROM U_RPMACHINERIES WHERE CODE='".$objRs->fields["U_ACTUALUSE"]."'");
				if ($objRs2->queryfetchrow("NAME")) {
					$asslvl= $objRs2->fields["U_ASSESSLEVEL"];
				}
				
				$obju_RPFaas3b->prepareadd();
				$obju_RPFaas3b->docid = $obju_RPFaas3->docid;
				$obju_RPFaas3b->lineid = getNextIdByBranch("u_rpfaas3b",$objConnection);
				$obju_RPFaas3b->name = $objRs->fields["u_itemdesc"];
				$obju_RPFaas3b->setudfvalue("u_taxable", $objRs->fields["U_TAXABLE"]);
				$obju_RPFaas3b->setudfvalue("u_machine", $objRs->fields["U_MACHINE"]);
				$obju_RPFaas3b->setudfvalue("u_actualuse", $objRs->fields["U_ACTUALUSE"]);
				$obju_RPFaas3b->setudfvalue("u_marketvalue", $objRs->fields["U_REMVALUE"]);
				$obju_RPFaas3b->setudfvalue("u_asslvl", $asslvl);
                                if( $objRs->fields["U_TAXABLE"]==1){
                                    if($objRs->fields["U_WITHDECIMAL"]==1){
                                        $obju_RPFaas3b->setudfvalue("u_assvalue", round(round($objRs->fields["U_REMVALUE"]*($asslvl/100),0),-1));
                                        $value = round(round($objRs->fields["U_REMVALUE"]*($asslvl/100),0),-1);
                                    }else{
                                        $obju_RPFaas3b->setudfvalue("u_assvalue", round($objRs->fields["U_REMVALUE"]*($asslvl/100),-1));
                                        $value = round($objRs->fields["U_REMVALUE"]*($asslvl/100),-1);
                                    }
                                }else{
                                    $obju_RPFaas3b->setudfvalue("u_assvalue", round(round($objRs->fields["U_REMVALUE"]*($asslvl/100),0),-1));
                                    $value = 0;
                                }
				$totalmarketvalue += $objRs->fields["U_REMVALUE"];
				$assvalue += $value;
				$actionReturn = $obju_RPFaas3b->add();
				if (!$actionReturn) return false;
						if($actionReturn){
							$actionReturn = $objRs3c->executesql("DELETE FROM U_RPFAAS3C WHERE COMPANY='$objTable->company' AND BRANCH='$objTable->branch' AND DOCID=".$objRs->fields["DOCID"]."",false);
							if ($actionReturn) {
								$ctr = $objRs->fields["U_ESTLIFE"];
								$marketvalue = $objRs->fields["U_REMVALUE"];
								$marketvaluedepreval = $objRs->fields["U_REMVALUE"] * .05;
								$depreval = $objRs->fields["U_ORGCOST"] * .05;
								$count = 0;
								for($xxx=0;$xxx<$ctr;$xxx++){
									
									$obju_RPFaas3c->prepareadd();
									$obju_RPFaas3c->docid = $objRs->fields["DOCID"];
									$obju_RPFaas3c->lineid = getNextIdByBranch("u_rpfaas3c",$objConnection);
									$obju_RPFaas3c->setudfvalue("u_year",$obju_RPFaas3->getudfvalue("u_effyear") + $xxx);
									$obju_RPFaas3c->setudfvalue("u_marketvalue",$marketvalue);
									$obju_RPFaas3c->setudfvalue("u_deprevalue",$depreval);
									$obju_RPFaas3c->setudfvalue("u_adjmarketvalue",$marketvalue-$depreval);
									$obju_RPFaas3c->setudfvalue("u_asslvl",$asslvl);
									$obju_RPFaas3c->setudfvalue("u_assvalue",round(round(($marketvalue-$depreval)*($asslvl/100),0),-1));
									$actionReturn = $obju_RPFaas3c->add();
									if(($ctr-$xxx)<=5){
										if(($ctr-$xxx)==5){
												$marketvalue = $marketvalue - $depreval;
												$depreval = 0;
										}
									}else{
										$marketvalue = $marketvalue - $depreval;
									}
								}
							}
						}
			}	
			if ($actionReturn) {
				$obju_RPFaas3->setudfvalue("u_marketvalue", $totalmarketvalue);
				$obju_RPFaas3->setudfvalue("u_assvalue", $assvalue);
				$actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion);
			}
                        
                        if ($actionReturn) {
                                $objRs->queryopen("SELECT MAX(U_ASSVALUE),B.NAME AS U_CLASS FROM U_RPFAAS3B A LEFT JOIN U_RPACTUSES B ON A.U_ACTUALUSE = B.CODE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='".$obju_RPFaas3->docid."'");
                                if ($objRs->queryfetchrow("NAME")) {
                                        $obju_RPFaas3->setudfvalue("u_class", $objRs->fields["U_CLASS"]);
                                        $actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion);
                                }
                        }
                        if ($actionReturn) {
                                if ($obju_RPNotice->getbykey($obju_RPFaas3->getudfvalue("u_noticeno"))) {
                                       $assvalue = 0;
                                       $marketvalue = 0;
                                       $obju_RPNotice->setudfvalue("u_declaredowner", $obju_RPFaas3->getudfvalue("u_ownercompanyname"));
                                       $obju_RPNotice->setudfvalue("u_tin", $obju_RPFaas3->getudfvalue("u_ownertin"));

                                       if ($obju_RPNoticeItems->getbysql("DOCID = '$obju_RPNotice->docid'")) {
                                           if ($obju_RPFaas3->docno == $obju_RPNoticeItems->getudfvalue("u_docno")) {
                                               $obju_RPNoticeItems->setudfvalue("u_class",$obju_RPFaas3->getudfvalue("u_class"));
                                               $obju_RPNoticeItems->setudfvalue("u_marketvalue",$obju_RPFaas3->getudfvalue("u_marketvalue"));
                                               $obju_RPNoticeItems->setudfvalue("u_assvalue",$obju_RPFaas3->getudfvalue("u_assvalue"));
                                               $obju_RPNoticeItems->setudfvalue("u_tdno",$obju_RPFaas3->getudfvalue("u_varpno"));
                                               if ($obju_RPFaas3->getudfvalue("u_subdivision") != "") $obju_RPNoticeItems->setudfvalue("u_location",$obju_RPFaas3->getudfvalue("u_subdivision"));
                                               else $obju_RPNoticeItems->setudfvalue("u_location",$obju_RPFaas3->getudfvalue("u_barangay"));
                                               $actionReturn = $obju_RPNoticeItems->update($obju_RPNoticeItems->docid,$obju_RPNoticeItems->lineid,$obju_RPNoticeItems->rcdversion);
                                           }
                                           $assvalue += $obju_RPNoticeItems->getudfvalue("u_assvalue");
                                           $marketvalue += $obju_RPNoticeItems->getudfvalue("u_marketvalue");
                                       }
                                       $obju_RPNotice->setudfvalue("u_totalassvalue", $assvalue);
                                       $obju_RPNotice->setudfvalue("u_totalmarketvalue", $marketvalue);
                                       $actionReturn = $obju_RPNotice->update($obju_RPNotice->docno,$obju_RPNotice->rcdversion);
                               }
                        }
                        
                        
		}
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdateassessvaluedocumentschema_brGPSRPTAS()");
	return $actionReturn;
}

function onCustomEventupdatefaasbilyeardocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	
	$objRs->queryopen("select u_arpno,u_kind, u_qtrfr2, u_yrfr2, u_qtrto2, u_yrto2, u_yrpaid2,u_assvalue from u_rpupdpayarps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
	while ($objRs->queryfetchrow("NAME")) {
			switch ($objRs->fields["u_kind"]) {
					case "L": $obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas1"); break;
					case "B": $obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas2"); break;
					case "M": $obju_RPFaas = new documentschema_br(null,$objConnection,"u_rpfaas3"); break;
			}
		if ($obju_RPFaas->getbykey($objRs->fields["u_arpno"])) {
			$obju_RPFaas->setudfvalue("u_assvalue",$objRs->fields["u_assvalue"]);
			$obju_RPFaas->setudfvalue("u_effqtr",$objRs->fields["u_qtrfr2"]);
			$obju_RPFaas->setudfvalue("u_effyear",$objRs->fields["u_yrfr2"]);
			$obju_RPFaas->setudfvalue("u_expqtr",$objRs->fields["u_qtrto2"]);
			$obju_RPFaas->setudfvalue("u_expyear",$objRs->fields["u_yrto2"]);
			$obju_RPFaas->setudfvalue("u_bilyear",$objRs->fields["u_yrpaid2"]);
			$actionReturn = $obju_RPFaas->update($obju_RPFaas->docno,$obju_RPFaas->rcdversion);
		} else return raiseError("Unable to find Arp No. [".$objRs->fields["u_arpno"]."].");
		if (!$actionReturn) break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatefaasdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}

function onCustomEventValidateRPTPaymentsdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$obju_RPTaxes = new documentschema_br(null,$objConnection,"u_rptaxes");
        
	$objRs->queryopen("select u_refno from u_rpvalidatepaymentitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_selected = 1 and docid='$objTable->docid'");
	while ($objRs->queryfetchrow("NAME")) {
			
		if ($obju_RPTaxes->getbykey($objRs->fields["u_refno"])) {
			$obju_RPTaxes->setudfvalue("u_isvalidated",1);
			$actionReturn = $obju_RPTaxes->update($obju_RPTaxes->docno,$obju_RPTaxes->rcdversion);
		} else return raiseError("Unable to find RPT Payment No. [".$objRs->fields["u_refno"]."].");
		if (!$actionReturn) break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatefaasdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}

function onCustomEventupdatefaasdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
       
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_migrated")==1) return true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objRs1 = new recordset (NULL,$objConnection);
	$obju_RPFaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$obju_RPFaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_RPFaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
        
	$objRs1->queryopen("select u_rptaassessortreasurylink from u_lgusetup ");
	while ($objRs1->queryfetchrow("NAME")) {
            if($objRs1->fields["u_rptaassessortreasurylink"]==1 || ($objRs1->fields["u_rptaassessortreasurylink"]==0 && $objTable->getudfvalue("u_withfaas")==1))
                      
                    $objRs->queryopen("select u_arpno, u_kind, max(u_yrto) as u_yrto from u_rptaxarps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1 group by u_arpno order by u_yrto");
                            while ($objRs->queryfetchrow("NAME")) {
                                    switch ($objRs->fields["u_kind"]) {
                                            case "LAND": 
                                                    if ($obju_RPFaas1->getbykey($objRs->fields["u_arpno"])) {
                                                            $obju_RPFaas1->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                            $actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion);
                                                    } else return raiseError("Unable to find land Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                            case "BUILDING": 
                                                    if ($obju_RPFaas2->getbykey($objRs->fields["u_arpno"])) {
                                                            $obju_RPFaas2->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                            $actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion);
                                                    } else return raiseError("Unable to find building Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                            case "MACHINERY": 
                                                    if ($obju_RPFaas3->getbykey($objRs->fields["u_arpno"])) {
                                                            $obju_RPFaas3->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                            $actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion);
                                                    } else return raiseError("Unable to find machinery Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                    }
                                    if (!$actionReturn) break;
                            }
        }
      
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatefaasdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}

function onCustomEventcreatebilldocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
      
        
        $objRsGbillFee = new recordset (null,$objConnection);
        $objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	global $ctrerror;
        $ctr=0;
	$div=1;
        $ctrerror = 1;
        
	if ($objTable->getudfvalue("u_paymode")=="Q") $div=4;

	$objRsFees = new recordset(null,$objConnection);
	$objRsFees->queryopen("select A.U_EPSF AS U_EPSFCODE, G.NAME AS U_EPSFDESC, A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
							LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
							LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE
                                                        LEFT JOIN U_LGUFEES G ON G.CODE=A.U_EPSF");
	if (!$objRsFees->queryfetchrow("NAME")) {
		return raiseError("No setup found for real property tax fees.");
	}
       
                $objRsGbill = new recordset (NULL,$objConnection);
                $objRsGbill->queryopen("select B.U_BILLDATE AS U_BILLDATE,A.U_MIGRATED,A.U_ISUPLOAD from U_RPTAXES A 
                                        LEFT JOIN U_RPTAXARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                        WHERE B.U_SELECTED = 1 AND A.DOCID = ".$objTable->docid." GROUP BY B.U_BILLDATE");
                while ($objRsGbill->queryfetchrow("NAME")) {

                       $ctr++;
                       $objLGUBills->prepareadd();
                       if ($objTable->getudfvalue("u_paymode")=="A") $objLGUBills->docno = $objTable->docno;
                       else  $objLGUBills->docno = $objTable->docno."-".$ctr;
                       $objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
                       $objLGUBills->docstatus = "O";
                       $objLGUBills->setudfvalue("u_profitcenter","RP");
                       $objLGUBills->setudfvalue("u_module","Real Property Tax");
                       $objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
                       $objLGUBills->setudfvalue("u_appno",$objTable->docno);
                       $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_tin"));
                       $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_declaredowner"));
                       $objLGUBills->setudfvalue("u_migrated",$objRsGbill->fields["U_MIGRATED"]);
                       $objLGUBills->setudfvalue("u_isupload",$objRsGbill->fields["U_ISUPLOAD"]);
                       
                       if($objRsGbill->fields["U_MIGRATED"]==1){
                            $objLGUBills->setudfvalue("u_migrateddate",currentdateDB());
                       }
                       if($objRsGbill->fields["U_ISUPLOAD"]==1){
                            $objLGUBills->setudfvalue("u_uploadeddate",currentdateDB());
                       }
                       
                           $objRsGbillFee->queryopen("select B.U_BILLDATE AS U_BILLDATE,GROUP_CONCAT(DISTINCT B.U_PAYMODE) AS U_PAYMODE,MIN(B.U_YRFR) AS U_YRFR,MAX(U_YRTO) AS U_YRTO,SUM(B.U_EPSF) AS U_EPSF,SUM(B.U_TAXDUE) AS U_TAXDUE,SUM(B.U_SEF) AS U_SEF,SUM(B.U_PENALTY) + SUM(B.U_PENALTYADJ) AS U_PENALTY,SUM(B.U_SEFPENALTY) + SUM(B.U_SEFPENALTYADJ) AS U_SEFPENALTY,SUM(B.U_SEFDISC) + SUM(B.U_SEFDISCADJ) AS U_SEFDISC ,SUM(B.U_TAXDISC) + SUM(B.U_TAXDISCADJ) AS U_TAXDISC from U_RPTAXES A
                                                               LEFT JOIN U_RPTAXARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                               WHERE B.U_SELECTED = 1 AND A.DOCID = ".$objTable->docid." AND B.U_BILLDATE = '".$objRsGbill->fields["U_BILLDATE"]."' GROUP BY B.U_BILLDATE");
                                   while($objRsGbillFee->queryfetchrow("NAME")){

                                       $objLGUBills->setudfvalue("u_docdate",$objRsGbillFee->fields["U_BILLDATE"]);
                                       $objLGUBills->setudfvalue("u_remarks","Real Property Tax - ".$objRsGbillFee->fields["U_PAYMODE"].", ".$objRsGbillFee->fields["U_YRFR"].iif($objRsGbillFee->fields["U_YRFR"]!=$objRsGbillFee->fields["U_YRTO"],"-".$objRsGbillFee->fields["U_YRTO"],"") );
                                       IF($ctr == 1){
                                                               $objLGUBills->setudfvalue("u_duedate",$objTable->getudfvalue("u_assdate"));  
                                       }ELSE{

                                       switch(intval(date('m',strtotime($objRsGbillFee->fields["U_BILLDATE"])))) {
                                                       case 1:
                                                       case 2:
                                                       case 3:
                                                               $objLGUBills->setudfvalue("u_duedate",date('Y')."-03-31");
                                                               break;
                                                       case 4:
                                                       case 5:
                                                       case 6:
                                                               $objLGUBills->setudfvalue("u_duedate",date('Y')."-06-30");
                                                               break;
                                                       case 7:
                                                       case 8:
                                                       case 9:
                                                               $objLGUBills->setudfvalue("u_duedate",date('Y')."-09-30");
                                                               break;
                                                       case 10:
                                                       case 11:
                                                       case 12:
                                                               $objLGUBills->setudfvalue("u_duedate",date('Y')."-12-31");
                                                               break;
                                               } 
                                      }

                                       $totalamount=0;
                                       //var_dump($objRs->fields);
                                       if (($objRsGbillFee->fields["U_EPSF"])>0) {
                                               if ($objRsFees->fields["U_EPSFCODE"]=="") return raiseError("No setup found for EPSF Tax.");
                                               $objLGUBillItems->prepareadd();
                                               $objLGUBillItems->docid = $objLGUBills->docid;
                                               $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                               $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_EPSFCODE"]);
                                               $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_EPSFDESC"]);
                                               $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_EPSF"],2));
                                               $totalamount+=round($objRsGbillFee->fields["U_EPSF"],2);
                                               $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                               $actionReturn = $objLGUBillItems->add();
                                       }	
                                       if (($objRsGbillFee->fields["U_TAXDUE"]-$objRsGbillFee->fields["U_TAXDISC"])>0) {
                                               if ($objRsFees->fields["U_RPPROPTAXFEECODE"]=="") return raiseError("No setup found for Real Property Tax.");
                                               $objLGUBillItems->prepareadd();
                                               $objLGUBillItems->docid = $objLGUBills->docid;
                                               $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                               $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXFEECODE"]);
                                               $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXFEEDESC"]);
                                               $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_TAXDUE"]-$objRsGbillFee->fields["U_TAXDISC"],2));
                                               $totalamount+=round($objRsGbillFee->fields["U_TAXDUE"]-$objRsGbillFee->fields["U_TAXDISC"],2);
                                               $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                               $actionReturn = $objLGUBillItems->add();
                                       }	
                                       if (($objRsGbillFee->fields["U_SEF"]-$objRsGbillFee->fields["U_SEFDISC"])>0) {
                                               if ($objRsFees->fields["U_RPSEFFEECODE"]=="") return raiseError("No setup found for SEF.");
                                               $objLGUBillItems->prepareadd();
                                               $objLGUBillItems->docid = $objLGUBills->docid;
                                               $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                               $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFFEECODE"]);
                                               $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFFEEDESC"]);
                                               $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_SEF"]-$objRsGbillFee->fields["U_SEFDISC"],2));
                                               $totalamount+=round($objRsGbillFee->fields["U_SEF"]-$objRsGbillFee->fields["U_SEFDISC"],2);
                                               $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                               $actionReturn = $objLGUBillItems->add();
                                       }	
                                       if ($objTable->getudfvalue("u_taxidleland")>0) {
                                               if ($objRsFees->fields["U_RPSEFFEECODE"]=="") return raiseError("No setup found for Idle Land.");
                                               $objLGUBillItems->prepareadd();
                                               $objLGUBillItems->docid = $objLGUBills->docid;
                                               $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                               $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPIDLELANDFEECODE"]);
                                               $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPIDLELANDFEEDESC"]);
                                               $objLGUBillItems->setudfvalue("u_amount",round($objTable->getudfvalue("u_taxidleland")/$div,2));
                                               $totalamount+=round($objTable->getudfvalue("u_taxidleland")/$div,2);
                                               $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                               $actionReturn = $objLGUBillItems->add();
                                       }	
                                       if (($objRsGbillFee->fields["U_PENALTY"])>0) {
                                               if ($objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]=="") return raiseError("No setup found for Real Property Tax - Penalty.");
                                               $objLGUBillItems->prepareadd();
                                               $objLGUBillItems->docid = $objLGUBills->docid;
                                               $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                               $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]);
                                               $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXPENALTYFEEDESC"]);		
                                               $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_PENALTY"],2));
                                               $totalamount+=round($objRsGbillFee->fields["U_PENALTY"],2);
                                               $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                               $actionReturn = $objLGUBillItems->add();
                                       }	
                                       if (($objRsGbillFee->fields["U_SEFPENALTY"])>0) {
                                               if ($objRsFees->fields["U_RPSEFPENALTYFEECODE"]=="") return raiseError("No setup found for SEF - Penalty.");
                                               $objLGUBillItems->prepareadd();
                                               $objLGUBillItems->docid = $objLGUBills->docid;
                                               $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                               $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFPENALTYFEECODE"]);
                                               $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFPENALTYFEEDESC"]);
                                               $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_SEFPENALTY"],2));
                                               $totalamount+=round($objRsGbillFee->fields["U_SEFPENALTY"],2);
                                               $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                               $actionReturn = $objLGUBillItems->add();
                                       }	
                                       if ($actionReturn) {
                                                
                                              
                                               $objLGUBills->setudfvalue("u_totalamount",$totalamount);
                                               $objLGUBills->setudfvalue("u_dueamount",$totalamount);
                                               
                                               $actionReturn = $objLGUBills->add();
                                               $objRsRP = new recordset (NULL,$objConnection);
                                               $objRsRP->queryopen("select B.u_billno AS U_BILLNO from U_RPTAXES A 
                                                                                               LEFT JOIN U_RPTAXARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                                                               WHERE B.U_SELECTED = 1 AND A.DOCID = ".$objTable->docid." GROUP BY B.U_BILLNO");
                                               while ($objRsRP->queryfetchrow("NAME")) {
                                                   if($objRsRP->fields["U_BILLNO"]!=""){
                                                      $actionReturn = $objRs->executesql("UPDATE U_LGUBILLS SET DOCSTATUS = 'CN' WHERE DOCNO = '".$objRsRP->fields["U_BILLNO"]."' and company = 'lgu' and branch = 'main'",false);
                                                   }

                                                    if (!$actionReturn) break;
                                               }
                                       } 
                                       if (!$actionReturn) break;
                                   }

                       if (!$actionReturn) break;
                   }
           // }
        //}
       
        //if ($ctr == 0) $ctrerror+=$ctrerror;
        //if ($ctr == 0) $actionReturn = raiseError("No Details found. Please try again.");
          
       
	return $actionReturn;
}
function onCustomEventTaxBillcreatebilldocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
      
        
        $objRsGbillFee = new recordset (null,$objConnection);
        $objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
        
	$objRsFees = new recordset(null,$objConnection);
	$objRsFees->queryopen("select A.U_EPSF AS U_EPSFCODE, G.NAME AS U_EPSFDESC,A.U_RPHOUSING AS U_SHTCODE, H.NAME AS U_SHTDESC, H.U_PENALTYCODE AS U_SHTPENALTYFEECODE, I.NAME AS U_SHTPENALTYFEEDESC, A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
							LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
							LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE
                                                        LEFT JOIN U_LGUFEES G ON G.CODE=A.U_EPSF
                                                        LEFT JOIN U_LGUFEES H ON H.CODE=A.U_RPHOUSING
                                                        LEFT JOIN U_LGUFEES I ON I.CODE=H.U_PENALTYCODE");
	if (!$objRsFees->queryfetchrow("NAME")) {
		return raiseError("No setup found for real property tax fees.");
	}
        
                $objLGUBills->prepareadd();
                $objLGUBills->docno = $objTable->docno;
                $objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
                $objLGUBills->docstatus = "O";
                $objLGUBills->setudfvalue("u_profitcenter","RP");
                $objLGUBills->setudfvalue("u_module","Real Property Tax Bill");
                $objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
                $objLGUBills->setudfvalue("u_appno",$objTable->docno);
                $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_tin"));
                $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_declaredowner"));
                $objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_assdate"));
                $objLGUBills->setudfvalue("u_duedate",$objTable->getudfvalue("u_duedate"));
                       
                    $objRsGbillFee->queryopen("SELECT U_BILLDATE, U_PAYMODE,U_YRFR,U_YRTO,SUM(U_IDLE) AS U_IDLE,SUM(U_EPSF) AS U_EPSF,SUM(U_SHT) AS U_SHT,SUM(U_SHTPENALTY) AS U_SHTPENALTY,SUM(U_TAXDUE) AS U_TAXDUE,SUM(U_SEF) AS U_SEF,SUM(U_PENALTY) AS U_PENALTY,SUM(U_SEFPENALTY) AS U_SEFPENALTY,SUM(U_SEFDISC)  AS U_SEFDISC ,SUM(U_TAXDISC) AS U_TAXDISC FROM(
                                                SELECT B.U_BILLDATE AS U_BILLDATE,GROUP_CONCAT(DISTINCT B.U_PAYMODE) AS U_PAYMODE,MIN(B.U_YRFR) AS U_YRFR,MAX(U_YRTO) AS U_YRTO,SUM(B.U_IDLE) AS U_IDLE,SUM(B.U_EPSF) AS U_EPSF,SUM(B.U_SHT) AS U_SHT,SUM(B.U_SHTPENALTY) AS U_SHTPENALTY,SUM(B.U_TAXDUE) AS U_TAXDUE,SUM(B.U_SEF) AS U_SEF,SUM(B.U_PENALTY) AS U_PENALTY,SUM(B.U_SEFPENALTY) AS U_SEFPENALTY,SUM(B.U_SEFDISC)  AS U_SEFDISC ,SUM(B.U_TAXDISC) AS U_TAXDISC from U_RPTAXBILL A
                                                LEFT JOIN U_RPTAXBILLARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                WHERE B.U_SELECTED = 1 AND A.DOCID =  ".$objTable->docid."
                                                union all
                                                SELECT B.U_BILLDATE AS U_BILLDATE,GROUP_CONCAT(DISTINCT B.U_PAYMODE) AS U_PAYMODE,MIN(B.U_YRFR) AS U_YRFR,MAX(U_YRTO) AS U_YRTO,SUM(B1.U_IDLE * -1) AS U_IDLE,SUM(B1.U_EPSF * -1) AS U_EPSF,SUM(B1.U_SHT * -1) AS U_SHT,SUM(0) AS U_SHTPENALTY,SUM(B1.U_TAXDUE * -1) AS U_TAXDUE,SUM(B1.U_SEF * -1) AS U_SEF,SUM(B1.U_PENALTY * -1) AS U_PENALTY,SUM(B1.U_SEFPENALTY * -1) AS U_SEFPENALTY,SUM(0)  AS U_SEFDISC ,SUM(0) AS U_TAXDISC from U_RPTAXBILL A
                                                INNER JOIN U_RPTAXBILLARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                INNER JOIN U_RPTAXBILLCREDITS B1 ON A.DOCID=B1.DOCID AND A.BRANCH = B1.BRANCH AND A.COMPANY = B1.COMPANY AND B.U_DOCNO = B1.U_DOCNO AND  B1.U_YEAR >= B.U_YRFR AND B1.U_YEAR <=  B.U_YRTO
                                                WHERE B1.U_SELECTED = 1 AND B.U_SELECTED = 1 AND A.DOCID =  ".$objTable->docid.") AS X");
                    
                         while($objRsGbillFee->queryfetchrow("NAME")){

                                $objLGUBills->setudfvalue("u_remarks","Real Property Tax - ".$objRsGbillFee->fields["U_PAYMODE"].", ".$objRsGbillFee->fields["U_YRFR"].iif($objRsGbillFee->fields["U_YRFR"]!=$objRsGbillFee->fields["U_YRTO"],"-".$objRsGbillFee->fields["U_YRTO"],"") );
                                $totalamount=0;	
                                if (($objRsGbillFee->fields["U_EPSF"])>0) {
                                        if ($objRsFees->fields["U_EPSFCODE"]=="") return raiseError("No setup found for EPSF Tax.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_EPSFCODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_EPSFDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_EPSF"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_EPSF"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if (($objRsGbillFee->fields["U_SHT"])>0) {
                                        if ($objRsFees->fields["U_SHTCODE"]=="") return raiseError("No setup found for Socialized Housing Tax.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_SHTCODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_SHTDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_SHT"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_SHT"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if (($objRsGbillFee->fields["U_SHTPENALTY"])>0) {
                                        if ($objRsFees->fields["U_SHTPENALTYFEECODE"]=="") return raiseError("No setup found for Socialized Housing Tax Penalty.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_SHTPENALTYFEECODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_SHTPENALTYFEEDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_SHTPENALTY"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_SHTPENALTY"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if (($objRsGbillFee->fields["U_TAXDUE"]-$objRsGbillFee->fields["U_TAXDISC"])>0) {
                                        if ($objRsFees->fields["U_RPPROPTAXFEECODE"]=="") return raiseError("No setup found for Real Property Tax.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXFEECODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXFEEDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_TAXDUE"]-$objRsGbillFee->fields["U_TAXDISC"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_TAXDUE"]-$objRsGbillFee->fields["U_TAXDISC"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if (($objRsGbillFee->fields["U_SEF"]-$objRsGbillFee->fields["U_SEFDISC"])>0) {
                                        if ($objRsFees->fields["U_RPSEFFEECODE"]=="") return raiseError("No setup found for SEF.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFFEECODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFFEEDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_SEF"]-$objRsGbillFee->fields["U_SEFDISC"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_SEF"]-$objRsGbillFee->fields["U_SEFDISC"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if ($objTable->getudfvalue("U_IDLE")>0) {
                                        if ($objRsFees->fields["U_RPIDLELANDFEECODE"]=="") return raiseError("No setup found for Idle Land.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPIDLELANDFEECODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPIDLELANDFEEDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_IDLE"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_IDLE"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if (($objRsGbillFee->fields["U_PENALTY"])>0) {
                                        if ($objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]=="") return raiseError("No setup found for Real Property Tax - Penalty.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXPENALTYFEEDESC"]);		
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_PENALTY"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_PENALTY"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if (($objRsGbillFee->fields["U_SEFPENALTY"])>0) {
                                        if ($objRsFees->fields["U_RPSEFPENALTYFEECODE"]=="") return raiseError("No setup found for SEF - Penalty.");
                                        $objLGUBillItems->prepareadd();
                                        $objLGUBillItems->docid = $objLGUBills->docid;
                                        $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                        $objLGUBillItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFPENALTYFEECODE"]);
                                        $objLGUBillItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFPENALTYFEEDESC"]);
                                        $objLGUBillItems->setudfvalue("u_amount",round($objRsGbillFee->fields["U_SEFPENALTY"],2));
                                        $totalamount+=round($objRsGbillFee->fields["U_SEFPENALTY"],2);
                                        $objLGUBillItems->privatedata["header"] = $objLGUBills;
                                        $actionReturn = $objLGUBillItems->add();
                                }	
                                if ($actionReturn) {

                                        $objLGUBills->setudfvalue("u_totalamount",$totalamount);
                                        $objLGUBills->setudfvalue("u_dueamount",$totalamount);
                                        $actionReturn = $objLGUBills->add();
                                } 
                                if (!$actionReturn) break;
                         }
                         
	return $actionReturn;
}

/*
function onUpdateEventdocumentschema_brGPSRPTAS($objTable) {
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
function onBeforeDeleteEventdocumentschema_brGPSRPTAS($objTable) {
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
function onDeleteEventdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

function onCustomEventcreatePosdocumentschema_brGPSRPTAS($objTable) {
	global $objConnection;
	$actionReturn = true;
        
       
        $objRs = new recordset (NULL,$objConnection);
	$objLGUPos = new documentschema_br(null,$objConnection,"u_lgupos");
	$objLGUPosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
	$objLGUPosDps = new documentlinesschema_br(null,$objConnection,"u_lguposdps");
        
	$objRpTaxBalance = new masterdataschema_br(null,$objConnection,"u_taxbalance");
	
	$objRsFees = new recordset(null,$objConnection);
	$objRsFees->queryopen("select A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A 
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
							LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
							LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE");
	if (!$objRsFees->queryfetchrow("NAME")) {
		return raiseError("No setup found for real property tax fees.");
	}
        $objRsGbill = new recordset (NULL,$objConnection);
        $objRsGbill->queryopen("select U_DOCDATE,U_DUEDATE from U_LGUBILLS WHERE DOCNO = ".$objTable->docno."  AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'");

        while ($objRsGbill->queryfetchrow("NAME")) {
         
                    $objLGUPos->prepareadd();
                    $objLGUPos->docid = getNextIdByBranch("u_lgupos",$objConnection);
                    $objLGUPos->docno = $objTable->getudfvalue("u_ornumber");
//                    $objLGUPos->docseries = -1;
//                    $objLGUPos->docstatus = "";
                    $objLGUPos->setudfvalue("u_issuedocno",$objTable->getudfvalue("u_issuedocno"));
                    $objLGUPos->setudfvalue("u_seriesdocno",$objTable->getudfvalue("u_seriesdocno"));
                    $objLGUPos->setudfvalue("u_docseries",$objTable->getudfvalue("u_docseries"));
                    $objLGUPos->setudfvalue("u_tfcountry","PH");
                    $objLGUPos->setudfvalue("u_custname",$objTable->getudfvalue("u_paidby"));
                    $objLGUPos->setudfvalue("u_custno",$objTable->getudfvalue("u_tin"));
                    $objLGUPos->setudfvalue("u_date",$objTable->getudfvalue("u_ordate"));
                    $objLGUPos->setudfvalue("u_userid",$_SESSION['userid']);
                    $objLGUPos->setudfvalue("u_terminalid",$objTable->getudfvalue("u_terminalid"));
                    $objLGUPos->setudfvalue("u_status","O");
                    
                    $objRs = new recordset(null,$objConnection);
                    $objRs->queryopen("SELECT COUNT(*) AS RPQUANTITY FROM U_RPTAXARPS where BRANCH='".$_SESSION["branch"]."' AND COMPANY='".$_SESSION['company']."' AND DOCID = '".$objTable->docid."' AND U_SELECTED = 1");
                    if ($objRs->queryfetchrow("NAME")){$objLGUPos->setudfvalue("u_totalquantity",$objRs->fields["RPQUANTITY"]);}
                    
                    $objLGUPos->setudfvalue("u_billno",$objTable->docno);
                    $objLGUPos->setudfvalue("u_profitcenter","RP");
                    $objLGUPos->setudfvalue("u_module","Real Property Tax");
                    $objLGUPos->setudfvalue("u_billdate",$objRsGbill->fields["U_DOCDATE"]);
                    $objLGUPos->setudfvalue("u_billduedate",$objRsGbill->fields["U_DUEDATE"]);
                    $objLGUPos->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
                    $objLGUPos->setudfvalue("u_doccnt",1);

                            $objRsGbillFee = new recordset (null,$objConnection);
                            $objRsGbillFee->queryopen("select 1 as cnt,B.U_ITEMCODE,B.U_ITEMDESC,B.U_AMOUNT from U_LGUBILLS A
                                                            INNER JOIN U_LGUBILLITEMS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                            WHERE A.DOCNO = ".$objTable->docno." AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' ");
                    $totalamount=0;
                    $dpamount=0;
                    $ctr=0;
                    
                    while($objRsGbillFee->queryfetchrow("NAME")){
                        
                            if ($objRsFees->fields["U_RPPROPTAXFEECODE"]=="") return raiseError("No setup found for Real Property Tax.");
                            $objLGUPosItems->prepareadd();
                            $objLGUPosItems->docid = $objLGUPos->docid;
                            $objLGUPosItems->lineid = getNextIdByBranch("u_lgupositems",$objConnection);
                            $objLGUPosItems->setudfvalue("u_itemcode",$objRsGbillFee->fields["U_ITEMCODE"]);
                            $objLGUPosItems->setudfvalue("u_itemdesc",$objRsGbillFee->fields["U_ITEMDESC"]);
                            $objLGUPosItems->setudfvalue("u_quantity",1);
                            $objLGUPosItems->setudfvalue("u_selected",1);
                            $objLGUPosItems->setudfvalue("u_unitprice",round($objRsGbillFee->fields["U_AMOUNT"],2));
                            $objLGUPosItems->setudfvalue("u_linetotal",round($objRsGbillFee->fields["U_AMOUNT"],2));
                            $objLGUPosItems->setudfvalue("u_price",round($objRsGbillFee->fields["U_AMOUNT"],2));
                            $totalamount+=round($objRsGbillFee->fields["U_AMOUNT"],2);
                            $ctr+=$objRsGbillFee->fields["cnt"];
                            $objLGUPosItems->privatedata["header"] = $objLGUPos;
                            $actionReturn = $objLGUPosItems->add();

                            if (!$actionReturn) break;
                    }
                            if ($actionReturn) {
                                $objRsRpTaxCredit = new recordset (NULL,$objConnection);
                                $objRsRpTaxCredit->queryopen("select B.U_ORREFNO,SUM(B.U_TAXDUE) + SUM(B.U_SEF) + SUM(B.U_PENALTY) + SUM(B.U_SEFPENALTY)  AS U_AMOUNT from U_RPTAXES A 
                                                                    LEFT JOIN U_RPTAXCREDITS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                                    WHERE B.U_SELECTED = 1 AND A.DOCNO = ".$objTable->docno." AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");

                                    while ($objRsRpTaxCredit->queryfetchrow("NAME")) {
                                        if ($objRsRpTaxCredit->fields["U_ORREFNO"]!=""){
                                            
                                            $objLGUPosDps->prepareadd();
                                            $objLGUPosDps->docid = $objLGUPos->docid;
                                            $objLGUPosDps->lineid = getNextIdByBranch("u_lguposdps",$objConnection);
                                            $objLGUPosDps->setudfvalue("u_selected",1);
                                            $objLGUPosDps->setudfvalue("u_refno",$objRsRpTaxCredit->fields["U_ORREFNO"]);
                                            $objLGUPosDps->setudfvalue("u_remarks","");
                                            $objLGUPosDps->setudfvalue("u_totalamount",$objRsRpTaxCredit->fields["U_AMOUNT"]);
                                            $objLGUPosDps->setudfvalue("u_balanceamount",$objRsRpTaxCredit->fields["U_AMOUNT"]);
                                            $objLGUPosDps->setudfvalue("u_amount",$objRsRpTaxCredit->fields["U_AMOUNT"]);
                                            $dpamount = $objRsRpTaxCredit->fields["U_AMOUNT"];
                                            
                                            $objRsOrname = new recordset (NULL,$objConnection);
                                            $objRsOrname->queryopen("select U_CUSTNAME FROM U_LGUPOS WHERE DOCNO = ".$objRsRpTaxCredit->fields["U_ORREFNO"]." AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'");
                                                while ($objRsOrname->queryfetchrow("NAME")) {
                                                    if($objRsOrname->fields["U_CUSTNAME"]!=""){
                                                         $objLGUPosDps->setudfvalue("u_custname",$objRsOrname->fields["U_CUSTNAME"]);
                                                    }else{
                                                          $objLGUPosDps->setudfvalue("u_custname",$objTable->getudfvalue("u_paidby"));
                                                    }
                                                }
                                            $objLGUPosDps->privatedata["header"] = $objLGUPos;
                                            $actionReturn = $objLGUPosDps->add();
                                    }
                                    
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            //CREATE NEW TAX BALANCE
                            if ($actionReturn) {
                                    if($objTable->getudfvalue("u_partialpay")==1 && $objTable->getudfvalue("u_withfaas")==1){
                                        $objRsRpTaxArps = new recordset (NULL,$objConnection);
                                        $objRsRpTaxArps->queryopen("select B.U_CLASS,B.U_ARPNO,B.LINEID,B.U_TDNO,B.U_YRFR,B.U_YRTO,((B.U_LINETOTAL-B.U_DPAMOUNT) / 2) AS U_TAXDUE,((B.U_LINETOTAL-B.U_DPAMOUNT) / 2) AS U_SEF,B.U_PENALTY,B.U_SEFPENALTY from U_RPTAXES A 
                                                                                    LEFT JOIN U_RPTAXARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                                                    WHERE B.U_SELECTED = 1 AND B.U_LINETOTAL <> B.U_DPAMOUNT AND A.DOCNO = ".$objTable->docno." AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");
                                            while ($objRsRpTaxArps->queryfetchrow("NAME")) {
                                                $objRpTaxBalance->prepareadd();
                                                $objRpTaxBalance->code = $objRsRpTaxArps->fields["U_TDNO"] . "-" . $objRsRpTaxArps->fields["LINEID"];
                                                $objRpTaxBalance->name = $objRsRpTaxArps->fields["U_TDNO"] . "-" . $objRsRpTaxArps->fields["LINEID"];
                                                $objRpTaxBalance->setudfvalue("u_tdno",$objRsRpTaxArps->fields["U_TDNO"]);
                                                $objRpTaxBalance->setudfvalue("u_taxdue",$objRsRpTaxArps->fields["U_TAXDUE"]);
                                                $objRpTaxBalance->setudfvalue("u_sef",$objRsRpTaxArps->fields["U_SEF"]);
                                                $objRpTaxBalance->setudfvalue("u_tin",$objTable->getudfvalue("u_tin"));
                                                $objRpTaxBalance->setudfvalue("u_status","O");
                                                $objRpTaxBalance->setudfvalue("u_orrefno",$objTable->getudfvalue("u_ornumber"));
                                                $objRpTaxBalance->setudfvalue("u_class",$objRsRpTaxArps->fields["U_CLASS"]);
                                                $objRpTaxBalance->setudfvalue("u_arpno",$objRsRpTaxArps->fields["U_ARPNO"]);
                                                $objRpTaxBalance->setudfvalue("u_yrfr",$objRsRpTaxArps->fields["U_YRFR"]);
                                                $objRpTaxBalance->setudfvalue("u_yrto",$objRsRpTaxArps->fields["U_YRTO"]);
                                                $actionReturn = $objRpTaxBalance->add();
                                            }

                                    }
                            }
                            // UPDATE TAXBALANCE TABLE
                            if ($actionReturn) {
                                    $objRsTaxBalance = new recordset (NULL,$objConnection);
                                    $objRsTaxBalance->queryopen("select B.U_BALREFNO from U_RPTAXES A 
                                                                                    LEFT JOIN U_RPTAXARPS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                                                    WHERE B.U_SELECTED = 1 AND U_ISBALANCE = 1 AND A.DOCNO = ".$objTable->docno." AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");
                                    while ($objRsTaxBalance->queryfetchrow("NAME")) {
                                        if($objRsTaxBalance->fields["U_BALREFNO"]!=""){
                                           $actionReturn = $objRs->executesql("UPDATE U_TAXBALANCE SET U_STATUS = 'C' WHERE CODE = '".$objRsTaxBalance->fields["U_BALREFNO"]."' AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'",false);
                                        }
                                    if (!$actionReturn) break;
                                    }
                            }
                            // UPDATE TAXCREDITS TABLE
                            if ($actionReturn) {
                                    $objRsTaxCredit = new recordset (NULL,$objConnection);
                                    $objRsTaxCredit->queryopen("select B.U_APPREFNO from U_RPTAXES A 
                                                                                    LEFT JOIN U_RPTAXCREDITS B ON A.DOCID=B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY
                                                                                    WHERE B.U_SELECTED = 1 AND A.DOCNO = ".$objTable->docno." AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."'");
                                    while ($objRsTaxCredit->queryfetchrow("NAME")) {
                                        if($objRsTaxCredit->fields["U_APPREFNO"]!=""){
                                           $actionReturn = $objRs->executesql("UPDATE U_TAXCREDITS SET U_STATUS = 'C' WHERE CODE = '".$objRsTaxCredit->fields["U_APPREFNO"]."' AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'",false);
                                        }
                                    if (!$actionReturn) break;
                                    }
                            }

                            
                            if ($actionReturn) {
                                  
                                    $objLGUPos->setudfvalue("u_penaltyamount",0);
                                    $objLGUPos->setudfvalue("u_paidamount",$totalamount);
                                    $objLGUPos->setudfvalue("u_totalamount",$totalamount);
                                    $objLGUPos->setudfvalue("u_dpamount",$dpamount);
                                    $objLGUPos->setudfvalue("u_totalbefdisc",$totalamount);
                                    $objLGUPos->setudfvalue("u_cashamount",$totalamount - $dpamount );
                                    $objLGUPos->setudfvalue("u_collectedcashamount",$totalamount - $dpamount);
                                    $actionReturn = $objLGUPos->add();

                            } 
                
                if (!$actionReturn) break;
            }
            
	return $actionReturn;
}

function onCustomEventupdatefaas123documentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
		
	
	$obju_RPFaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$obju_RPFaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_RPFaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
      
        $objRs1 = new recordset (NULL,$objConnection);
	$objRs1->queryopen("select U_RPTASESSORTREASURYLINK from u_lgusetup ");
	while ($objRs1->queryfetchrow("NAME")) {
                $objRs = new recordset (NULL,$objConnection);
                $objRs->queryopen("select r.u_arpno, r.u_kind, max(r.u_yrto) as u_yrto,min(u_yrfr) as u_yrfr,u_withfaas from u_lgupos a inner join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch inner join u_rptaxes c on b.u_appno = c.docno inner join u_rptaxarps r on c.docid = r.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$objTable->getudfvalue("u_ornumber")."' and r.u_selected=1 and r.u_isbalance=0 group by r.u_arpno order by r.u_yrto");
                        while ($objRs->queryfetchrow("NAME")) {
                            if($objRs1->fields["U_RPTASESSORTREASURYLINK"]==1 || ($objRs1->fields["U_RPTASESSORTREASURYLINK"]==0 && $objRs->fields["u_withfaas"]==1))
                                  
                                switch ($objRs->fields["u_kind"]) {
                                            case "LAND": 
                                                    if ($obju_RPFaas1->getbykey($objRs->fields["u_arpno"])) {
                                                        $obju_RPFaas1->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                        $actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion); 
                                                    } else return raiseError("Unable to find land Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                            case "BUILDING": 
                                                    if ($obju_RPFaas2->getbykey($objRs->fields["u_arpno"])) {
                                                        $obju_RPFaas2->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                        $actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion); 
                                                    } else return raiseError("Unable to find building Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                            case "MACHINERY": 
                                                    if ($obju_RPFaas3->getbykey($objRs->fields["u_arpno"])) {
                                                        $obju_RPFaas3->setudfvalue("u_bilyear",$objRs->fields["u_yrto"]);
                                                        $actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion); 
                                                    } else return raiseError("Unable to find machinery Arp No. [".$objRs->fields["u_arpno"]."].");
                                                    break;
                                    }
                            if (!$actionReturn) break;
                        }
        }
      
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatefaasdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}

function onCustomEventupdatefaasNoticeNo123documentschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
		
	$obju_RPFaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$obju_RPFaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_RPFaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
      
        $objRs = new recordset (NULL,$objConnection);
        $objRs->queryopen("select u_docno,u_kind from u_rpnoticeitems where u_selected = 1 and company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid ='".$objTable->docid."' ");
                while ($objRs->queryfetchrow("NAME")) {
                        switch ($objRs->fields["u_kind"]) {
                                    case "L": 
                                            if ($obju_RPFaas1->getbykey($objRs->fields["u_docno"])) {
                                                $obju_RPFaas1->setudfvalue("u_noticeno",$objTable->docno);
                                                $actionReturn = $obju_RPFaas1->update($obju_RPFaas1->docno,$obju_RPFaas1->rcdversion); 
                                            } else return raiseError("Unable to find land Arp No. [".$objRs->fields["u_arpno"]."].");
                                            break;
                                    case "B": 
                                            if ($obju_RPFaas2->getbykey($objRs->fields["u_docno"])) {
                                                $obju_RPFaas2->setudfvalue("u_noticeno",$objTable->docno);
                                                $actionReturn = $obju_RPFaas2->update($obju_RPFaas2->docno,$obju_RPFaas2->rcdversion); 
                                            } else return raiseError("Unable to find building Arp No. [".$objRs->fields["u_arpno"]."].");
                                            break;
                                    case "M": 
                                            if ($obju_RPFaas3->getbykey($objRs->fields["u_docno"])) {
                                                $obju_RPFaas3->setudfvalue("u_noticeno",$objTable->docno);
                                                $actionReturn = $obju_RPFaas3->update($obju_RPFaas3->docno,$obju_RPFaas3->rcdversion); 
                                            } else return raiseError("Unable to find machinery Arp No. [".$objRs->fields["u_arpno"]."].");
                                            break;
                            }
                   if (!$actionReturn) break;
                }
      
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatefaasdocumentschema_brGPSRPTAS()");
	return $actionReturn;
}


?>