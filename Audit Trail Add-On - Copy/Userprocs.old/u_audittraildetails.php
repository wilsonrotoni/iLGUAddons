<?php

$objLicMgr->checkAddon("GPS.Audit Trail","Standard",true);

$httpVars["formNoNew"] = true; 
$httpVars["formNoAdd"] = true; 
$httpVars["formNoUpdate"] = true; 
$httpVars["formAccess"] = "R"; 

$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("find",false);

$objMaster->reportaction = "QS";
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSAuditTrail");

function onAfterDefaultGPSAuditTrail() {
	global $httpVars;
	global $page;
	global $objMaster;
	
	$keys = split("`",$httpVars["keys"]);
	$objMaster->getbysql("COMPANY='".$keys[0]."' and BRANCH='".$keys[1]."' and DOCID='".$keys[2]."'",0);
	
	$httpVars = array_merge($httpVars,$objMaster->sethttpfields());
} # end onAfterDefaultGPSAuditTrail


?>