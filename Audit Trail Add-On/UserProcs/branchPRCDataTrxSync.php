<?php 

	include_once("./classes/progids.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/documentschema_br.php");
	
	function processBranchPRCDataGPSAuditTrail($data,$companycode,$branchcode,&$override) {
		global $objConnection;
		//var_dump($data);
		$actionReturn = true;
		
		switch ($data[2]) {
			case "U_AUDITTRAILSETUP":
				$override=true;
				$trxdata = explode("[tbl]",$data[5]);
				
				$objAudittrailsetup = new masterdataschema(NULL,$objConnection,"u_audittrailsetup");
				if(!$objAudittrailsetup->getbysql("CODE='".$trxdata[0]."'")) {
					$objProgid = new progids(NULL,$objConnection);
					$objProgid->getbysql("PROGID='".$trxdata[0]."'");
					
					$objAudittrailsetup->shareddatatype = "COMPANY";
					$objAudittrailsetup->prepareadd();
					$objAudittrailsetup->code = $trxdata[0];
					$objAudittrailsetup->name = $objProgid->progname;
					$objAudittrailsetup->setudfvalue("u_log","Yes");
					if ($actionReturn) $actionReturn = $objAudittrailsetup->add();
				}
				break;
		}
		return $actionReturn;
	}
	
?>