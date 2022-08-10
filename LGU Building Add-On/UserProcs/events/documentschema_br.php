<?php

function onBeforeAddEventdocumentschema_brGPSLGUBuilding($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_bldgins":
			$obju_FSApps = new documentschema_br(null,$objConnection,"u_bldgapps");
			if ($obju_FSApps->getbykey($objTable->getudfvalue("u_appno"))) {
				$obju_FSApps->docstatus = "FI";
				$obju_FSApps->setudfvalue("u_insno",$objTable->docno);
				$actionReturn = $obju_FSApps->update($obju_FSApps->docno,$obju_FSApps->rcdversion);
			} else return raiseError("Unable to find Application No.[".$objTable->getudfvalue("u_appno")."].");
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentschema_brGPSLGUBuilding($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentschema_brGPSLGUBuilding($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_bldgins":
			if ($objTable->fields["U_INSPECTORREMARKS"]!=$objTable->getudfvalue("u_inspectorremarks") || $objTable->fields["U_INSPECTDATE"]!=$objTable->getudfvalue("u_inspectdate") || $objTable->fields["U_INSPECTTIME"]!=$objTable->getudfvalue("u_inspecttime")) {
				if ($objTable->getudfvalue("u_updatefrommobileapp")=="0") {
					$objTable->setudfvalue("u_inspectorremarkhistory",currentdate()." ".currenttime()." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_inspectorremarks")."\r\n\r\n".$objTable->getudfvalue("u_inspectorremarkhistory"));
				} else {
					$objTable->setudfvalue("u_inspectorremarkhistory",formatDateToHttp($objTable->getudfvalue("u_inspectdate"))." ".formatTimeToHttp($objTable->getudfvalue("u_inspecttime"))." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_inspectorremarks")."\r\n\r\n".$objTable->getudfvalue("u_inspectorremarkhistory"));
					$objTable->setudfvalue("u_updatefrommobileapp",0);
				}
			}
			if ($objTable->fields["U_INSPECTBYSTATUS"]!=$objTable->getudfvalue("u_inspectbystatus")) {
				if ($objTable->getudfvalue("u_inspectbystatus")=="Passed") {
					$objTable->docstatus = "P";
				} elseif ($objTable->getudfvalue("u_inspectbystatus")=="Failed") {
					$objTable->docstatus = "F";
				}
			}
			if ($objTable->fields["U_RECOMMENDBYSTATUS"]!=$objTable->getudfvalue("u_recommendbystatus")) {
				//var_dump($objTable->getudfvalue("u_recommendbystatus"));
				if ($objTable->getudfvalue("u_recommendbystatus")=="For Approval") {
					$objTable->docstatus = "FA";
				} else {
					$objTable->docstatus = "D";
				}
				$objTable->setudfvalue("u_recommendby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			}
			if ($objTable->fields["U_DISPOSITIONBYSTATUS"]!=$objTable->getudfvalue("u_dispositionbystatus")) {
				if ($objTable->getudfvalue("u_dispositionbystatus")=="Approved") {
					$objTable->docstatus = "A";
				} else {
					$objTable->docstatus = "D";
				}
				$objTable->setudfvalue("u_dispositionby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_dispositiondate",currentdateDB());
			}
			
			/*if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="FA") {
				$objTable->setudfvalue("u_recommendbystatus","Approved");
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="A") {
				$objTable->setudfvalue("u_dispositionby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_dispositionbystatus","Approved");
				$objTable->setudfvalue("u_dispositiondate",currentdateDB());
			}*/

			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSLGUBuilding");
	return $actionReturn;
}


/*
function onUpdateEventdocumentschema_brGPSLGUBuilding($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSLGUBuilding($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSLGUBuilding($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

?>