<?php
 


function onBeforeAddEventmasterdataschema_brGPSPMRSMobile($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_pmrambulantvendors":
			$objTable->code = str_pad( getNextId("u_pmrambulantvendors",$objConnection), 5, "0", STR_PAD_LEFT);
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventmasterdataschema_brGPSPMRSMobile()");
	return $actionReturn;
}


/*
function onAddEventmasterdataschema_brGPSPMRSMobile($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sims":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventmasterdataschema_brGPSPMRSMobile($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_pmrambulantvendors":
			$objRs = new recordset(null,$objConnection);
			if ($objTable->fields["NAME"]!=$objTable->name) {
				$actionReturn = $objRs->executesql("update u_pmrambulantcollections set u_vendorname='".addslashes($objTable->name)."' where u_vendorcode='".$objTable->code."'",false);
			}
			break;
	}
	return $actionReturn;
}


/*
function onUpdateEventmasterdataschema_brGPSPMRSMobile($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sims":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeDeleteEventmasterdataschema_brGPSPMRSMobile($objTable) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset(null,$objConnection);
	switch ($objTable->dbtable) {
		case "u_projects":
			$objRs->queryopen("select docno from u_pmrvendorcollections where u_vendorcode='$objTable->code' limit 1");
			if ($objRs->queryfetchrow("NAME")) {
				$actionReturn = raiseError("Ambulant Vendor [$objTable->code] is currently being used.");
			}
			break;
	}
	return $actionReturn;
}


/*
function onDeleteEventmasterdataschema_brGPSPMRSMobile($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sims":
			break;
	}
	return $actionReturn;
}
*/

?>
