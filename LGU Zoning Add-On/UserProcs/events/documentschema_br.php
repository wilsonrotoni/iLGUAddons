<?php

function onBeforeAddEventdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_zoningclrins":
			$obju_FSApps = new documentschema_br(null,$objConnection,"u_zoningclrapps");
			if ($obju_FSApps->getbykey($objTable->getudfvalue("u_appno"))) {
				$obju_FSApps->docstatus = "FI";
				$obju_FSApps->setudfvalue("u_insno",$objTable->docno);
				$actionReturn = $obju_FSApps->update($obju_FSApps->docno,$obju_FSApps->rcdversion);
			} else return raiseError("Unable to find Application No.[".$objTable->getudfvalue("u_appno")."].");
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_zoningclrins":
			if ($objTable->fields["U_INSPECTORREMARKS"]!=$objTable->getudfvalue("u_inspectorremarks") || $objTable->fields["U_INSPECTDATE"]!=$objTable->getudfvalue("u_inspectdate") || $objTable->fields["U_INSPECTTIME"]!=$objTable->getudfvalue("u_inspecttime")) {
				if ($objTable->getudfvalue("u_updatefrommobileapp")=="0") {
					$objTable->setudfvalue("u_inspectorremarkhistory",currentdate()." ".currenttime()." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_inspectorremarks")."\r\n\r\n".$objTable->getudfvalue("u_inspectorremarkhistory"));
				} else {
					$objTable->setudfvalue("u_inspectorremarkhistory",formatDateToHttp($objTable->getudfvalue("u_inspectdate"))." ".formatTimeToHttp($objTable->getudfvalue("u_inspecttime"))." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_inspectorremarks")."\r\n\r\n".$objTable->getudfvalue("u_inspectorremarkhistory"));
					$objTable->setudfvalue("u_updatefrommobileapp",0);
				}
			}
			if ($objTable->fields["U_INSPECTBYSTATUS"]!=$objTable->getudfvalue("u_inspectbystatus")) {
				if ($objTable->getudfvalue("u_inspectbystatus")=="Passed") {
					$objTable->docstatus = "P";
				} elseif ($objTable->getudfvalue("u_inspectbystatus")=="Failed") {
					$objTable->docstatus = "F";
				}
			}
			if ($objTable->fields["U_RECOMMENDBYSTATUS"]!=$objTable->getudfvalue("u_recommendbystatus")) {
				//var_dump($objTable->getudfvalue("u_recommendbystatus"));
				if ($objTable->getudfvalue("u_recommendbystatus")=="For Approval") {
					$objTable->docstatus = "FA";
				} else {
					$objTable->docstatus = "D";
				}
				$objTable->setudfvalue("u_recommendby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			}
			if ($objTable->fields["U_DISPOSITIONBYSTATUS"]!=$objTable->getudfvalue("u_dispositionbystatus")) {
				if ($objTable->getudfvalue("u_dispositionbystatus")=="Approved") {
					$objTable->docstatus = "A";
				} else {
					$objTable->docstatus = "D";
				}
				$objTable->setudfvalue("u_dispositionby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_dispositiondate",currentdateDB());
			}
			
			/*if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="FA") {
				$objTable->setudfvalue("u_recommendbystatus","Approved");
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="A") {
				$objTable->setudfvalue("u_dispositionby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_dispositionbystatus","Approved");
				$objTable->setudfvalue("u_dispositiondate",currentdateDB());
			}*/

			break;
                        
                        case "u_locationalclrapps":
                                if ($objTable->docstatus=="AP" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->fields["DOCSTATUS"]=="O") {
                                            if ($objTable->getudfvalue("u_decisionno") == "") {
						$refno = getNextSeriesNoByBranch("U_ZONINGDECISIONNO","",$objTable->getudfvalue("u_approveddate"));
						$objTable->setudfvalue("u_decisionno",$refno);
                                            }
                                            $actionReturn = onCustomEventcreatebillLocationalTaxdocumentschema_brGPSLGUZoning($objTable);
                                }
                                 /***** Re-Assessment*****/
                                        if  ($objTable->docstatus=="O" && ($objTable->fields["DOCSTATUS"]=="AP" || $objTable->fields["DOCSTATUS"]=="DP")) { 
                                                $objTable->docstatus="O";
                                                if ($actionReturn && $objTable->fields["DOCSTATUS"]=="AP") {
                                                    $objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                                        while(true){
                                                            if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND DOCSTATUS = 'O' AND U_PREASSBILL=0 AND u_bplupdatebill = 0 AND U_MODULE IN('Zoning Locational Clearance Tax')")) {
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
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSLGUZoning");
	return $actionReturn;
}


/*
function onUpdateEventdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/
function onCustomEventcreatebillLocationalTaxdocumentschema_brGPSLGUZoning($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

        $objRs_uBilyears = new recordset(null,$objConnection);
                $objRs_uBilyears->queryopen("SELECT u_year FROM U_LOCATIONALCLRAPPFEES a INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_LINETOTAL>0 GROUP BY A.U_YEAR ");
	
	while ($objRs_uBilyears->queryfetchrow("NAME")) {
                    $objLGUBills->prepareadd();
                    $objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
                    $objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
                    $objLGUBills->docstatus = "O";
                    $objLGUBills->setudfvalue("u_profitcenter","ZONING");
                    $objLGUBills->setudfvalue("u_module","Zoning Locational Clearance Tax");
                    $objLGUBills->setudfvalue("u_paymode","A");
                   	$objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_bldgappno"));
                    $objLGUBills->setudfvalue("u_appno",$objTable->docno);
                    
                    if ($objTable->getudfvalue("u_corpname") != ""){
                        $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_corpname"));
                    } else {
                        $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_lastname") . ", ". $objTable->getudfvalue("u_firstname") . " ". $objTable->getudfvalue("u_middlename"));
                    }
                    
                 	$objLGUBills->setudfvalue("u_payqtr",4);
                 	$objLGUBills->setudfvalue("u_remarks","Zoning (Locational Clearance Tax) - ".$objRs_uBilyears->fields["u_year"]);
                 	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
			
                        $objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_LINETOTAL AS U_AMOUNT, B.U_INSTALLMENT,A.U_SEQNO FROM U_LOCATIONALCLRAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_LINETOTAL>0 AND A.U_YEAR = '".$objRs_uBilyears->fields["u_year"]."' ");
                        $totalamount=0;
                        while ($objRs->queryfetchrow("NAME")) {
                                $objLGUBillItems->prepareadd();
                                $objLGUBillItems->docid = $objLGUBills->docid;
                                $objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                $objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
                                $objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
                                $objLGUBillItems->setudfvalue("u_seqno",$objRs->fields["U_SEQNO"]);
                             	$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
                                $totalamount+=$objRs->fields["U_AMOUNT"];
                                    
                                if ($objLGUBillItems->getudfvalue("u_amount")<= 0) return raiseError("Invalid amount [".$objLGUBillItems->getudfvalue("u_amount")."] for fee code [".$objRs->fields["U_FEECODE"]."].");
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
                       
                
        }
	
	return $actionReturn;
}

?>