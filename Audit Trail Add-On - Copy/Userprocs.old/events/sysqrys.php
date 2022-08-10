<?php
 
	include_once("../Addons/GPS/Audit Trail Add-On/UserProcs/inc/u_audittrail.php");
	
	function onBeforeAddEventsysqrysGPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Add",$objTable);			
		return $actionReturn;
	}
	
	function onBeforeUpdateEventsysqrysGPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Update",$objTable);			
		return $actionReturn;
	}
	
	function onBeforeDeleteEventsysqrysGPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Delete",$objTable);			
		return $actionReturn;
	}
	
?>