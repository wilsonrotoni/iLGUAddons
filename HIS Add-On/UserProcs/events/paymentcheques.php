<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./utils/suppliers.php");
	include_once("./utils/wtaxes.php");
	include_once("./utils/trxlog.php");

	function onUpdateEventpaymentchequesGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->released!=$objTable->fields["RELEASED"] && $objTable->released==1 && $objTable->privatedata["header"]->getudfvalue("u_cano")!="") {
			$actionReturn = onCustomEventpaymentsCashAdvanceReleasedGPSHIS($objTable);
		}

		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventpaymentchequesGPSHIS");
		return $actionReturn;
	}
	
	function onCustomEventpaymentsCashAdvanceReleasedGPSHIS($objTable) {
		global $objConnection;
		$actionReturn=true;
		
		$obju_HISCashAdvances = new documentschema_br(null,$objConnection,"u_hiscashadvances");
		if ($obju_HISCashAdvances->getbykey($objTable->privatedata["header"]->getudfvalue("u_cano"))) {
			$obju_HISCashAdvances->setudfvalue("u_cvdate",$objTable->releaseddate);
			$actionReturn = $obju_HISCashAdvances->update($obju_HISCashAdvances->docno,$obju_HISCashAdvances->rcdversion);
		} else raiseError("Unable to find Cash Advance No. [".$objTable->getudfvalue("u_cano")."].");
						
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventpaymentsCashAdvanceReleasedGPSHIS()");
		return $actionReturn;
	}	

?>
