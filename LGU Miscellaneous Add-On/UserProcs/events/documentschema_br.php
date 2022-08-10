<?php
 


function onBeforeAddEventdocumentschema_brGPSLGUMiscellaneous($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_burialapps":
                        
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


function onBeforeUpdateEventdocumentschema_brGPSLGUMiscellaneous($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_burialapps":
                break;
	}
	return $actionReturn;
}
function onAddEventdocumentschema_brGPSLGUMiscellaneous($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_burialapps":
                        
                   
                        if ($objTable->docstatus!="D") {
                            $actionReturn = onCustomEventcreatePOSdocumentschema_brLGUMiscellaneous($objTable);
                        }
                    break;
	}
	return $actionReturn;
}
function onCustomEventcreatePOSdocumentschema_brLGUMiscellaneous($objTable) {
	global $objConnection;
	$actionReturn = true;
	$totalamount=0;
        
	$objRs = new recordset (NULL,$objConnection);
	$obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
	$obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
        
        $obju_Pos->prepareadd();
        $obju_Pos->docseries = -1;
        $obju_Pos->docno = $objTable->getudfvalue("u_orno");
        $obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
        $obju_Pos->setudfvalue("u_docnos",$objTable->getudfvalue("u_orno"));
        $obju_Pos->setudfvalue("u_custname",$objTable->getudfvalue("u_custname"));
        $obju_Pos->setudfvalue("u_custno","Cash");
        $obju_Pos->setudfvalue("u_date",$objTable->getudfvalue("u_ordate"));
        $obju_Pos->setudfvalue("u_profitcenter","Misc");
        $obju_Pos->setudfvalue("u_module","Burial Permit Fee");
        $obju_Pos->setudfvalue("u_doccnt",1);
        
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

	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_BURIALAPPFEES A LEFT JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'  AND A.U_AMOUNT>0");
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
        if ($actionReturn) {
              $obju_Pos->setudfvalue("u_paidamount",$totalamount);
              $obju_Pos->setudfvalue("u_totalamount",$totalamount);
              $obju_Pos->setudfvalue("u_cashamount",$totalamount);
              $obju_Pos->setudfvalue("u_chequeamount",0);
              $obju_Pos->setudfvalue("u_collectedcashamount",$totalamount);
              $actionReturn = $obju_Pos->add();
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