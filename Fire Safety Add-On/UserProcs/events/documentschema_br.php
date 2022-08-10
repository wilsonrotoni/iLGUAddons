<?php

function onBeforeAddEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsins":
			$obju_FSApps = new documentschema_br(null,$objConnection,"u_fsapps");
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
function onAddEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsins":
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
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSFireSafety");
	return $actionReturn;
}



function onUpdateEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
        
	switch ($objTable->dbtable) {
		case "u_fsapps":
//                        if ($objTable->fields["DOCSTATUS"]=="O" && $objTable->docstatus=="P") {
//                           // $actionReturn = onCustomEventcreatePOSdocumentschema_brGPSFireSafety($objTable);
//                            if (!$actionReturn) $page->setitem("docstatus","O");
//			} 
                    
                        if ($objTable->docstatus=="AP" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->fields["DOCSTATUS"]=="O") {
                                $actionReturn = onCustomEventcreatebillFireSafetydocumentschema_brGPSLGUFireSafety($objTable);
                        }
                        /***** Re-Assessment*****/
                                if  ($objTable->docstatus=="O" && ($objTable->fields["DOCSTATUS"]=="AP" || $objTable->fields["DOCSTATUS"]=="DP")) { 
                                        $objTable->docstatus="O";
                                        if ($actionReturn && $objTable->fields["DOCSTATUS"]=="AP") {
                                            $objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
                                                while(true){
                                                    if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND DOCSTATUS = 'O' AND U_PREASSBILL=0 AND u_bplupdatebill = 0 AND U_MODULE IN('Fire Safety')")) {
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
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSFireSafety($objTable) {
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
function onDeleteEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

function onCustomEventcreatebillFireSafetydocumentschema_brGPSLGUFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");

        $objRs_uBilyears = new recordset(null,$objConnection);
                $objRs_uBilyears->queryopen("SELECT u_year FROM U_FSAPPFEES a INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 AND U_CHECK = 1 GROUP BY A.U_YEAR ");
	
	while ($objRs_uBilyears->queryfetchrow("NAME")) {
                    $objLGUBills->prepareadd();
                    $objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
                    $objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
                    $objLGUBills->docstatus = "O";
                    $objLGUBills->setudfvalue("u_profitcenter","FIRE");
                    $objLGUBills->setudfvalue("u_module","Fire Safety");
                    $objLGUBills->setudfvalue("u_paymode","A");
                    $objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_bldgappno"));
                    $objLGUBills->setudfvalue("u_appno",$objTable->docno);
                    
                    if ($objTable->getudfvalue("u_corpname") != ""){
                        $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_corpname"));
                    } else {
                        $objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_lastname") . ", ". $objTable->getudfvalue("u_firstname") . " ". $objTable->getudfvalue("u_middlename"));
                    }
                    
                 	$objLGUBills->setudfvalue("u_payqtr",4);
                 	$objLGUBills->setudfvalue("u_remarks","Fire Safety - ".$objRs_uBilyears->fields["u_year"]);
                 	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
			
                        $objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT AS U_AMOUNT, B.U_INSTALLMENT,A.U_SEQNO FROM U_FSAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0  AND A.U_CHECK = 1 AND A.U_YEAR = '".$objRs_uBilyears->fields["u_year"]."' ");
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

function onCustomEventcreatePOSdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
	$obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
	
        $objRsFees = new recordset(null,$objConnection);
	$objRsFees->queryopen("SELECT A.U_BPLFIREINSFEE AS U_BPLFIREFEECODE, B.NAME AS U_BPLFIREFEEDESC
                                FROM U_LGUSETUP A 
                                LEFT JOIN U_LGUFEES B ON B.CODE=A.U_BPLFIREINSFEE ");
	if (!$objRsFees->queryfetchrow("NAME")) {
		return raiseError("No setup found for Fire safety fee.");
	}

	
                $totalamount=$objTable->getudfvalue("u_orfcamt");

                $obju_Pos->prepareadd();
                $obju_Pos->docseries = -1;
                $obju_Pos->docno = $objTable->getudfvalue("u_orno");
                $obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
                $obju_Pos->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
                $obju_Pos->setudfvalue("u_custno",$objTable->docno);
                $obju_Pos->setudfvalue("u_status","C");
                $obju_Pos->setudfvalue("u_date",$objTable->getudfvalue("u_ordate"));
                $obju_Pos->setudfvalue("u_profitcenter","FS");
                $obju_Pos->setudfvalue("u_module","Fire Safety");
                $obju_Pos->setudfvalue("u_paymode",'');
                $obju_Pos->setudfvalue("u_doccnt",1);
                $obju_Pos->setudfvalue("u_trxtype","S");
                
                $obju_PosItems->prepareadd();
                $obju_PosItems->docid = $obju_Pos->docid;
                $obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
                $obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_BPLFIREFEECODE"]);
                $obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_BPLFIREFEEDESC"]);
                $obju_PosItems->setudfvalue("u_quantity",1);
                $obju_PosItems->setudfvalue("u_unitprice",$totalamount);
                $obju_PosItems->setudfvalue("u_price",$totalamount);
                $obju_PosItems->setudfvalue("u_linetotal",$totalamount);
                $obju_PosItems->privatedata["header"] = $obju_Pos;
                $actionReturn = $obju_PosItems->add();
                
            if ($actionReturn) {
              $obju_Pos->setudfvalue("u_paidamount",$totalamount);
              $obju_Pos->setudfvalue("u_totalamount",$totalamount);
              $obju_Pos->setudfvalue("u_cashamount",$totalamount);
              $obju_Pos->setudfvalue("u_chequeamount",0);
              $obju_Pos->setudfvalue("u_collectedcashamount",$totalamount);
              $actionReturn = $obju_Pos->add();
            }
            if (!$actionReturn) return false;
			
		
		
	return $actionReturn;
}
?>