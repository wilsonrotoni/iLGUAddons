<?php
 

/*
function onBeforeAddEventdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgubrgyresidence":
			break;
	}
	return $actionReturn;
}
*/

/*
function onAddEventdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgubrgyresidence":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeUpdateEventdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgubrgyresidence":
			break;
	}
        if($actionReturn){
            switch ($objTable->dbtable) {
                
            }
        }
	return $actionReturn;
}
*/


function onUpdateEventdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgubrgyresidence":
                        break;
		case "u_lgubrgycert":
                        if ($objTable->docstatus=="C" && $objTable->fields["DOCSTATUS"]=="O") {
                            if ($actionReturn)  $actionReturn = onCustomEventcreatePosdocumentschema_brGPSLGUBarangay($objTable);
                        }
			break;
	}
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgubrgyresidence":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgubrgyresidence":
			break;
	}
	return $actionReturn;
}
*/

function onCustomEventcreatePosdocumentschema_brGPSLGUBarangay($objTable) {
	global $objConnection;
	$actionReturn = true;
        
       
        $objRs = new recordset (NULL,$objConnection);
	$objLGUPos = new documentschema_br(null,$objConnection,"u_lgubrgypos");
	$objLGUPosItems = new documentlinesschema_br(null,$objConnection,"u_lgubrgypositems");
	//$objLGUPosDps = new documentlinesschema_br(null,$objConnection,"u_lguposdps");
        
	//$objRpTaxBalance = new masterdataschema_br(null,$objConnection,"u_taxbalance");
        //$obju_LGUPOSTerminals = new masterdataschema_br(null,$objConnection,"u_lguposterminals");
	
//	$objRsFees = new recordset(null,$objConnection);
//	$objRsFees->queryopen("select A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A 
//							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
//							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
//							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
//							LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
//							LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE");
//	if (!$objRsFees->queryfetchrow("NAME")) {
//		return raiseError("No setup found for real property tax fees.");
//	}
        $objRsGbill = new recordset (NULL,$objConnection);
        $objRsGbill->queryopen("select U_DOCDATE,U_DUEDATE from U_LGUBILLS WHERE DOCNO = ".$objTable->docno."  AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'");

        while ($objRsGbill->queryfetchrow("NAME")) {
         
                           $objLGUPos->prepareadd();
                           $objLGUPos->docid = getNextIdByBranch("u_lgupos",$objConnection);
                           $objLGUPos->docno = $objTable->getudfvalue("u_ornumber");
                           $objLGUPos->docseries = -1;
                           $objLGUPos->docstatus = "";
                           $objLGUPos->setudfvalue("u_tfcountry","PH");
                           $objLGUPos->setudfvalue("u_custname",$objTable->getudfvalue("u_paidby"));
                           $objLGUPos->setudfvalue("u_custno",$objTable->getudfvalue("u_tin"));
                           $objLGUPos->setudfvalue("u_date",$objTable->getudfvalue("u_ordate"));
                           $objLGUPos->setudfvalue("u_status","O");
                           $objRs = new recordset(null,$objConnection);
                           $objRs->queryopen("select CODE, U_AUTOSERIES, U_AUTOPOST from U_LGUPOSTERMINALS where BRANCH='".$_SESSION["branch"]."' AND NAME='".$_SERVER['REMOTE_ADDR']."'");
                           if ($objRs->queryfetchrow("NAME")){$objLGUPos->setudfvalue("u_terminalid",$objRs->fields["CODE"]);}
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
                                    $objLGUPos->setudfvalue("u_totalquantity",$ctr);
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

?> 

