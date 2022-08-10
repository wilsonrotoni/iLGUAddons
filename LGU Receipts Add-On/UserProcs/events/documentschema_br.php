<?php
 


function onBeforeAddEventdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
        $objRs = new recordset (NULL,$objConnection);
        
	$objLGUReceiptItems = new documentlinesschema_br(null,$objConnection,"U_LGURECEIPTITEMS");
        
	switch ($objTable->dbtable) {
		case "u_lgureceiptcashierreturn":
                    if ($objLGUReceiptItems->getbysql("LINEID='".$objTable->getudfvalue("u_receiptlineid")."'")) {
                            if ($objLGUReceiptItems->getudfvalue("u_issuedocno") != "") {
                                    $objTable->setudfvalue("u_issuedocno",$objLGUReceiptItems->getudfvalue("u_issuedocno") );
                            } else {
                                return raiseError("Unable to return. Series is not yet issued.");
                            }
                            if (!$actionReturn) break;
                    }
                    break;
	}
	return $actionReturn;
}

function onAddEventdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
        $objRs = new recordset (NULL,$objConnection);
        
	$objLGUReceiptItems = new documentlinesschema_br(null,$objConnection,"U_LGURECEIPTITEMS");
	$obju_TerminalSeries = new documentschema_br(null,$objConnection,"u_terminalseries");
        
        switch ($objTable->dbtable) {
                case "u_lgureceiptmultipleissue":
                    $actionReturn = onCustomEventcreateMultipleIssueReceiptdocumentschema_brGPSLGUReceipts($objTable);
                    break;
		case "u_lgureceiptcashierissue":
                    if ($objLGUReceiptItems->getbysql("LINEID='".$objTable->getudfvalue("u_receiptlineid")."'")) {
                            $objLGUReceiptItems->setudfvalue("u_issuedto", $objTable->getudfvalue("u_cashier"));
                            $objLGUReceiptItems->setudfvalue("u_issuedocno", $objTable->docno);
                            $objLGUReceiptItems->setudfvalue("u_issuectr", $objLGUReceiptItems->getudfvalue("u_issuectr") + 1);
                            $objTable->setudfvalue("u_issuectr", $objLGUReceiptItems->getudfvalue("u_issuectr"));
                            $actionReturn = $objLGUReceiptItems->update($objLGUReceiptItems->docid,$objLGUReceiptItems->lineid,$objLGUReceiptItems->rcdversion);
                            if (!$actionReturn) break;
                    } 
                break;
		case "u_lgureceiptcashierreturn":
                    if ($objLGUReceiptItems->getbysql("LINEID='".$objTable->getudfvalue("u_receiptlineid")."'")) {
                            if( $objLGUReceiptItems->getudfvalue("u_issuedto") == $objTable->getudfvalue("u_cashier")) {
//                                    if ($obju_TerminalSeries->getbykey($objTable->getudfvalue("u_issuedocno"))) {
//                                        if ($actionReturn) $actionReturn = $obju_TerminalSeries->delete();
//                                    }
                                    $objLGUReceiptItems->setudfvalue("u_issuedto", "");
                                    $objLGUReceiptItems->setudfvalue("u_issuedocno", "");
                                    $actionReturn = $objLGUReceiptItems->update($objLGUReceiptItems->docid,$objLGUReceiptItems->lineid,$objLGUReceiptItems->rcdversion);
                            }
                            if (!$actionReturn) break;
                    } 
                break;
	}
	return $actionReturn;
}


/*
function onBeforeUpdateEventdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgureceipts":
			break;
	}
	return $actionReturn;
}
*/

/*
function onUpdateEventdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgureceipts":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgureceipts":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgureceipts":
			break;
	}
	return $actionReturn;
}
*/
function onCustomEventcreateMultipleIssueReceiptdocumentschema_brGPSLGUReceipts($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUReceiptIssue = new documentschema_br(null,$objConnection,"u_lgureceiptcashierissue");

	$objRs->queryopen("SELECT A.U_CASHIER,A.U_DATEISSUED,A.U_TIME,B.U_RECEIPTLINEID,B.U_RECEIPTFRM,B.U_RECEIPTTO,B.U_FORM,B.U_DOCSERIES,B.U_NOOFRECEIPT,B.U_AVAILABLE FROM u_lgureceiptmultipleissue A
                            INNER JOIN u_lgureceiptmultipleissueitems B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID
                            where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'
                            GROUP BY U_RECEIPTLINEID");
	while ($objRs->queryfetchrow("NAME")) {
                $objLGUReceiptIssue->prepareadd();
                $objLGUReceiptIssue->docno = getNextNoByBranch("u_lgureceiptcashierissue","",$objConnection);
                $objLGUReceiptIssue->docid = getNextIdByBranch("u_lgureceiptcashierissue",$objConnection);
                $objLGUReceiptIssue->setudfvalue("u_available",$objRs->fields["U_AVAILABLE"]);
                $objLGUReceiptIssue->setudfvalue("u_cashier",$objRs->fields["U_CASHIER"]);
                $objLGUReceiptIssue->setudfvalue("u_dateissued",$objRs->fields["U_DATEISSUED"]);
                $objLGUReceiptIssue->setudfvalue("u_form",$objRs->fields["U_FORM"]);
                $objLGUReceiptIssue->setudfvalue("u_noofreceipt",$objRs->fields["U_NOOFRECEIPT"]);
                $objLGUReceiptIssue->setudfvalue("u_receiptfrm",$objRs->fields["U_RECEIPTFRM"]);
                $objLGUReceiptIssue->setudfvalue("u_receiptto",$objRs->fields["U_RECEIPTTO"]);
                $objLGUReceiptIssue->setudfvalue("u_receiptlineid",$objRs->fields["U_RECEIPTLINEID"]);
                $objLGUReceiptIssue->setudfvalue("u_time",$objRs->fields["U_TIME"]);
                $objLGUReceiptIssue->setudfvalue("u_docseries",$objRs->fields["U_DOCSERIES"]);
                $actionReturn = $objLGUReceiptIssue->add();
                if (!$actionReturn) break;
        }
        
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcreatepreassbilldocumentschema_brGPSBPLS()");

	return $actionReturn;
}
?>