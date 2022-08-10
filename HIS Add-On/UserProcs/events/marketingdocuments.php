<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/documentschema_br.php");

	function onUpdateEventmarketingdocumentsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		$db = $objTable->dbtable;
		$objectcode = $objTable->objectcode;
		$docno = $objTable->docno;
		$docid = $objTable->docid;
		$docduedate = $objTable->docduedate;
		$branch = $objTable->branch;
		$company = $objTable->company;
		
		switch(strtoupper($objectcode)) {
			 case "ARINVOICE" :
			 	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
				if ($obju_HISBills->getbykey($objTable->docno)) {
					if ($obju_HISBills->getudfvalue("u_reftype")=="IP") {
					 	$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					} else {
					 	$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					}
					/*if ($obju_HISIPs->getbykey($obju_HISBills->getudfvalue("u_refno"))) {
						if (floatval($objTable->dueamount)<=0) {
							$obju_HISIPs->setudfvalue("u_predischarge",1);
						} else {
							$obju_HISIPs->setudfvalue("u_predischarge",0);
						}
						if ($actionReturn) $actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					}*/	
				}	
				break;
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventmarketingdocumentsGPSHIS");
		return $actionReturn;
	}
	

?>
