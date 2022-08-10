<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/documentschema_br.php");

	function onAddEventusermsgsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		switch ($objTable->objectcode) {
			case "u_hiscashadvances":
				if ($objTable->approvalstatus!="") {
					$obju_HISCashAdvances = new documentschema_br(null,$objConnection,"u_hiscashadvances");
					if ($obju_HISCashAdvances->getbykey($objTable->docno)) {
						$obju_HISCashAdvances->docstatus="O";
						$actionReturn = $obju_HISCashAdvances->update($obju_HISCashAdvances->docno,$obju_HISCashAdvances->rcdversion);
					} else return raiseError("Unable to Find Cash Advances No [$objTable->docno].");
					//if ($actionReturn) $actionReturn = raiseError("onAddEventusermsgsGPSHIS");
				}	
				break;
		}
		return $actionReturn;
	}


?>
