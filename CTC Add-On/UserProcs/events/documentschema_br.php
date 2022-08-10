<?php
 


function onBeforeAddEventdocumentschema_brGPSCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
                        
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentschema_brCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
		  $objTable->setudfvalue("u_forpayment",1);
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentschema_brGPSCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
                break;
	}
	return $actionReturn;
}
function onAddEventdocumentschema_brGPSCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
                        if ($objTable->docstatus!="D") {
                            $actionReturn = onCustomEventcreatePOSdocumentschema_brCTC($objTable);
                        }
                    break;
		case "u_ctcbrgyapps":
                        if ($objTable->docstatus!="D") {
                            $actionReturn = onCustomEventcreatePOSforBrgydocumentschema_brCTC($objTable);
                        }
                    break;
	}
	return $actionReturn;
}
function onCustomEventcreatePOSdocumentschema_brCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	$totalamount=0;
        
	$objRs = new recordset (NULL,$objConnection);
	$obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
	$obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
	$obju_PosCheques = new documentlinesschema_br(null,$objConnection,"u_lguposcheques");
        
        $obju_Pos->prepareadd();
        $obju_Pos->docseries = -1;
        $obju_Pos->docno = $objTable->getudfvalue("u_orno");
        $obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
        $obju_Pos->setudfvalue("u_docnos",$objTable->getudfvalue("u_orno"));
        $obju_Pos->setudfvalue("u_custname",$objTable->getudfvalue("u_custname"));
        $obju_Pos->setudfvalue("u_custno","Cash");
        $obju_Pos->setudfvalue("u_date",$objTable->getudfvalue("u_ordate"));
        $obju_Pos->setudfvalue("u_profitcenter","CTC");
        $obju_Pos->setudfvalue("u_module","Community Tax Certificate");
        $obju_Pos->setudfvalue("u_doccnt",1);
        $obju_Pos->setudfvalue("u_isonlinepayment",$objTable->getudfvalue("u_isonlinepayment"));
        $obju_Pos->setudfvalue("u_ctctype",$objTable->getudfvalue("u_apptype"));
        $obju_Pos->setudfvalue("u_ctcgender",$objTable->getudfvalue("u_gender"));
        $obju_Pos->setudfvalue("u_ctccivilstatus",$objTable->getudfvalue("u_civilstatus"));
        $obju_Pos->setudfvalue("u_ctccitizenship",$objTable->getudfvalue("u_citizenship"));
        $obju_Pos->setudfvalue("u_ctcpob",$objTable->getudfvalue("u_placeofbirth"));
        $obju_Pos->setudfvalue("u_ctcdob",$objTable->getudfvalue("u_dateofbirth"));
        $obju_Pos->setudfvalue("u_ctcoccupation",$objTable->getudfvalue("u_occupation"));
        $obju_Pos->setudfvalue("u_ctcgross",$objTable->getudfvalue("u_gross"));
        $obju_Pos->setudfvalue("u_address",$objTable->getudfvalue("u_address"));
        
        $obju_Pos->setudfvalue("u_docseries",$objTable->getudfvalue("u_docseries"));
        $obju_Pos->setudfvalue("u_userid",$objTable->getudfvalue("u_userid"));
        $obju_Pos->setudfvalue("u_seriesdocno",$objTable->getudfvalue("u_seriesdocno"));
        $obju_Pos->setudfvalue("u_issuedocno",$objTable->getudfvalue("u_issuedocno"));

        $obju_Pos->setudfvalue("u_paidamount",$ar["AMOUNT"]);
        $obju_Pos->setudfvalue("u_totalamount",$ar["AMOUNT"]);
        $obju_Pos->setudfvalue("u_totalbefdisc",$ar["AMOUNT"]);
        $obju_Pos->setudfvalue("u_trxtype","S");
        $obju_Pos->setudfvalue("u_cashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
        $obju_Pos->setudfvalue("u_chequeamount",iif($ar["PAYMODE_CT"]!="CSH",$ar["AMOUNT"],0));
        $obju_Pos->setudfvalue("u_collectedcashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));

	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_CTCAPPFEES A LEFT JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'  AND A.U_AMOUNT>0");
	while ($objRs->queryfetchrow("NAME")) {
                        $obju_PosItems->prepareadd();
                        $obju_PosItems->docid = $obju_Pos->docid;
                        $obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
                        $obju_PosItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
                        $obju_PosItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
                        $obju_PosItems->setudfvalue("u_quantity",1);
                        $obju_PosItems->setudfvalue("u_unitprice",$objRs->fields["U_AMOUNT"]);
                        $obju_PosItems->setudfvalue("u_price",$objRs->fields["U_AMOUNT"]);
                        $obju_PosItems->setudfvalue("u_linetotal",$objRs->fields["U_AMOUNT"]);
                        $totalamount+=$objRs->fields["U_AMOUNT"];
                        $obju_PosItems->privatedata["header"] = $obju_Pos;
                        $actionReturn = $obju_PosItems->add();
        }
        if($objTable->getudfvalue("u_ischeque") == 1) {
                        $obju_PosCheques->prepareadd();
                        $obju_PosCheques->docid = $obju_Pos->docid;
                        $obju_PosCheques->lineid = getNextIDByBranch("u_lguposcheques",$objConnection);
                        $obju_PosCheques->setudfvalue("u_amount",$totalamount);
                        $obju_PosCheques->setudfvalue("u_bank",$objTable->getudfvalue("u_checkbank"));
                        $obju_PosCheques->setudfvalue("u_checkdate",$objTable->getudfvalue("u_checkdate"));
                        $obju_PosCheques->setudfvalue("u_checkno",$objTable->getudfvalue("u_checkno"));
                        $obju_PosCheques->privatedata["header"] = $obju_Pos;
                        $actionReturn = $obju_PosCheques->add();
        }
        
        if ($actionReturn) {
              $obju_Pos->setudfvalue("u_paidamount",$totalamount);
              $obju_Pos->setudfvalue("u_totalamount",$totalamount);
              $obju_Pos->setudfvalue("u_totalbefdisc",$totalamount);
              $obju_Pos->setudfvalue("u_cashamount",iif($objTable->getudfvalue("u_ischeque")=="0",$totalamount,0));
              $obju_Pos->setudfvalue("u_chequeamount",iif($objTable->getudfvalue("u_ischeque")!="0",$totalamount,0));
              $obju_Pos->setudfvalue("u_collectedcashamount",iif($objTable->getudfvalue("u_ischeque")=="0",$totalamount,0));
              $actionReturn = $obju_Pos->add();
        }
	return $actionReturn;
}
function onCustomEventcreatePOSforBrgydocumentschema_brCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	$totalamount=0;
        $feecode = '';
        $feedesc = '';
	$objRs = new recordset (NULL,$objConnection);
	$objRs1 = new recordset (NULL,$objConnection);
	$obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
	$obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
        
        
        $objRs->queryopen("SELECT A.U_RECEIPTLINEID, A.U_RECEIPTFRM, A.U_RECEIPTTO, A.U_FORM, A.U_AVAILABLEQTY, A.U_USEDQTY, A.U_DOCSERIES, A.U_AMOUNT FROM U_CTCBRGYAPPLINES A where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'  AND A.U_AMOUNT>0");
	while ($objRs->queryfetchrow("NAME")) {
                        $startno = $objRs->fields["U_RECEIPTTO"] - $objRs->fields["U_AVAILABLEQTY"] + 1;
                        $endno =   $objRs->fields["U_RECEIPTTO"] - ($objRs->fields["U_AVAILABLEQTY"]-$objRs->fields["U_USEDQTY"]);
                        $obju_Pos->prepareadd();
                        $obju_Pos->docseries = -1;
                        $obju_Pos->docno = str_pad( $startno, 7, "0", STR_PAD_LEFT) . '-' . str_pad( $endno, 7, "0", STR_PAD_LEFT);
                        $obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
                        $obju_Pos->setudfvalue("u_docnos",str_pad( $startno, 7, "0", STR_PAD_LEFT) . '-' . str_pad( $endno, 7, "0", STR_PAD_LEFT));
                        $obju_Pos->setudfvalue("u_custname",$objTable->getudfvalue("u_barangay"));
                        $obju_Pos->setudfvalue("u_custno","Cash");
                        $obju_Pos->setudfvalue("u_date",$objTable->getudfvalue("u_date"));
                        $obju_Pos->setudfvalue("u_remittancedate",$objTable->getudfvalue("u_date"));
                        $obju_Pos->setudfvalue("u_profitcenter","CTC");
                        $obju_Pos->setudfvalue("u_module","CTC Barangay");
                        $obju_Pos->setudfvalue("u_doccnt",$objRs->fields["U_USEDQTY"]);
                        $obju_Pos->setudfvalue("u_docseries",-1);
                        
                        
                        $objRs1->queryopen("SELECT B.CODE AS U_FEECODE,B.NAME AS U_FEEDESC FROM U_LGUSETUP A INNER JOIN U_LGUFEES B ON A.U_CTCBRGYFEE = B.CODE");
                        while ($objRs1->queryfetchrow("NAME")) {
                                $obju_PosItems->prepareadd();
                                $obju_PosItems->docid = $obju_Pos->docid;
                                $obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
                                $obju_PosItems->setudfvalue("u_itemcode",$objRs1->fields["U_FEECODE"]);
                                $obju_PosItems->setudfvalue("u_itemdesc",$objRs1->fields["U_FEEDESC"]);
                                $obju_PosItems->setudfvalue("u_quantity",1);
                                $obju_PosItems->setudfvalue("u_unitprice",$objRs->fields["U_AMOUNT"]);
                                $obju_PosItems->setudfvalue("u_price",$objRs->fields["U_AMOUNT"]);
                                $obju_PosItems->setudfvalue("u_linetotal",$objRs->fields["U_AMOUNT"]);
                                $totalamount=$objRs->fields["U_AMOUNT"];
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
                        }
        }
        
        
       
	return $actionReturn;
}



/*
function onUpdateEventdocumentschema_brCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brCTC($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_ctcapps":
			break;
	}
	return $actionReturn;
}
*/

?>