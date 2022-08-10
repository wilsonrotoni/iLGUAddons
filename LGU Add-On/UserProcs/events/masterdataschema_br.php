<?php
 

function onBeforeAddEventmasterdataschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lguposregisters":
                        $objRs = new recordset(null,$objConnection);
                        $objRs->queryopen("select CODE FROM U_LGUPOSREGISTERS WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND CREATEDBY='".$objTable->getudfvalue("u_userid")."' AND U_SALESAMOUNT > 0 AND U_ISREMITTED = 0");
                        while ($objRs->queryfetchrow("NAME")) {
                                return raiseError("Unable to Open Register. Unremitted transactions exist.");
                        }
                        break;
	}
	return $actionReturn;
}


/*
function onAddEventmasterdataschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventmasterdataschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lguposregisters":
			$objRs = new recordset(null,$objConnection);
			$objRs2 = new recordset(null,$objConnection);
                        $obju_LGUPOS = new documentschema_br(null,$objConnection,"u_lgupos");
			if ($objTable->fields["U_STATUS"]!=$objTable->getudfvalue("u_status") && $objTable->getudfvalue("u_status")=="C") {
                                $objRs->queryopen("select SUM(U_CASHAMOUNT) + SUM(U_CHEQUEAMOUNT) + SUM(U_CREDITCARDAMOUNT) + SUM(U_OTHERAMOUNT) + SUM(U_TFAMOUNT) + SUM(U_DPAMOUNT) + SUM(U_ARAMOUNT) AS U_TOTALAMOUNT FROM U_LGUPOS WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND CREATEDBY='".$objTable->getudfvalue("u_userid")."' AND U_STATUS IN ('D','O')");
                                    if ($objRs->queryfetchrow("NAME")) {
                                        if($objRs->fields["U_TOTALAMOUNT"] > 0) {
                                            if($objTable->getudfvalue("u_salesamount")!=$objRs->fields["U_TOTALAMOUNT"]) return raiseError("Sales Total is not tally. Please refresh the page.");
                                        }
                                    }
				$objTable->setudfvalue("u_closedate",currentdateDB());
				$objTable->setudfvalue("u_closetime",date('H:i'));
				$dailyExp = "";
				if ($objTable->getudfvalue("u_daily")=="1") $dailyExp = " AND DOCDATE='".$objTable->getudfvalue("u_date")."'";
				//$actionReturn = $objRs->executesql("update COLLECTIONS set U_POSSTATUS='C' WHERE COMPANY='".$_SESSION["company"]."' AND BRANCHCODE='".$_SESSION["branch"]."' AND COLLFOR='RS' AND U_POSSTATUS in ('O','') AND DOCSTAT IN ('O','C') $dailyExp",false);

				$dailyExp = "";
				//if ($objTable->getudfvalue("u_daily")=="1") $dailyExp = " AND U_DATE='".$objTable->getudfvalue("u_date")."'";
				$actionReturn = $obju_LGUPOS->executesql("update U_LGUPOS set U_STATUS='C',U_REGISTERID='".$objTable->code."' WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND CREATEDBY='".$objTable->getudfvalue("u_userid")."' AND U_STATUS IN ('O','D') $dailyExp",false);
				$actionReturn = $obju_LGUPOS->executesql("update U_LGUPOS set U_REGISTERID='".$objTable->code."' WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND CREATEDBY='".$objTable->getudfvalue("u_userid")."' AND U_STATUS  = 'CN' AND U_REGISTERID = '' $dailyExp",false);
                        }
                        if ($objTable->fields["U_ISREMITTED"]!=$objTable->getudfvalue("u_isremitted") && $objTable->getudfvalue("u_isremitted")=="1") {
                            $actionReturn = $obju_LGUPOS->executesql("update U_LGUPOS set U_REMITTANCEDATE = '".$objTable->getudfvalue("u_postingdate")."' WHERE U_STATUS IN ('CN','C') AND U_REGISTERID='".$objTable->code."' AND  COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND CREATEDBY='".$objTable->getudfvalue("u_userid")."'",false);
                        }
                        if ($objTable->fields["U_STATUS"] == "C" && $objTable->fields["U_STATUS"]!=$objTable->getudfvalue("u_status") && $objTable->getudfvalue("u_status")=="O") {
                            $objTable->setudfvalue("u_closedate","");
                            $objTable->setudfvalue("u_closetime",""); 
                            $actionReturn = $objRs->executesql("delete from u_lguposregisterclosedenominations WHERE code='".$objTable->code."' AND COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' ",false);
                            $actionReturn = $obju_LGUPOS->executesql("update U_LGUPOS set U_STATUS='O' WHERE U_REGISTERID='".$objTable->code."' AND COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND CREATEDBY='".$objTable->getudfvalue("u_userid")."' AND U_STATUS IN ('C') ",false);
                            if (!$actionReturn) break;
                            $objRs2->queryopen("select U_CUTOFFTIME from  U_LGUSETUP  where BRANCH='".$_SESSION["branch"]."' AND COMPANY='".$_SESSION["company"]."' ");
                            if ($objRs2->queryfetchrow("NAME")) {
                                if (date('H:i')<=$objRs2->fields["U_CUTOFFTIME"]) {
                                    $objTable->setudfvalue("u_postingdate",currentdateDB());
                                } else {
                                    $objTable->setudfvalue("u_postingdate",dateadd("d",1,currentdateDB()));
                                }
                            }
                            if (!$actionReturn) break;
                            
                        }
			break;
	}
        
	return $actionReturn;
}


/*
function onUpdateEventmasterdataschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventmasterdataschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventmasterdataschema_brGPSLGU($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

?>