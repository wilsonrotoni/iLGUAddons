<?php

	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");

	function onBeforeAddEventjournalentriesGPSLGUAcctg(&$objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->doctype) {
			case "JV":
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("SELECT U_PROVINCE, U_MUNICIPALITY, U_ASSISTANT, U_ACCOUNTANT, U_TREASURER, U_MAYOR FROM JOURNALVOUCHERS WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND DOCNO='".$objTable->docno."'");
				if ($objRs->queryfetchrow("NAME")) {
					$objTable->setudfvalue("u_province",$objRs->fields["U_PROVINCE"]);
					$objTable->setudfvalue("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
					$objTable->setudfvalue("u_assistant",$objRs->fields["U_ASSISTANT"]);
					$objTable->setudfvalue("u_accountant",$objRs->fields["U_ACCOUNTANT"]);
					$objTable->setudfvalue("u_treasurer",$objRs->fields["U_TREASURER"]);
					$objTable->setudfvalue("u_mayor",$objRs->fields["U_MAYOR"]);
				}
				break;
		}
		//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventjournalentriesGPSLGUAcctg()");
		return $actionReturn;
	}
	
?>
