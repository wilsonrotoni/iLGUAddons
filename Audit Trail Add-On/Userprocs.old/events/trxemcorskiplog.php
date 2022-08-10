<?php
 
	include_once("../Addons/GPS/Audit Trail Add-On/UserProcs/inc/u_audittrail.php");
	
	function onBeforeAddEventtrxemcorskiplogGPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Add",$objTable);			
		return $actionReturn;
	}
	
	function onBeforeUpdateEventtrxemcorskiplogGPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Update",$objTable);			
		return $actionReturn;
	}
	
	function onBeforeDeleteEventtrxemcorskiplogGPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Delete",$objTable);			
		return $actionReturn;
	}
	
?>