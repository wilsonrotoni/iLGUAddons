<?php

function onBeforeAddEventdocumentschema_brGPSSanitary($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sanitaryins":
			$obju_SanitaryApps = new documentschema_br(null,$objConnection,"u_sanitaryapps");
			if ($obju_SanitaryApps->getbykey($objTable->getudfvalue("u_appno"))) {
				$obju_SanitaryApps->docstatus = "C";
				$obju_SanitaryApps->setudfvalue("u_insno",$objTable->docno);
				$actionReturn = $obju_SanitaryApps->update($obju_SanitaryApps->docno,$obju_SanitaryApps->rcdversion);
			} else return raiseError("Unable to find application no.[".$objTable->getudfvalue("u_appno")."].");
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentschema_brGPSSanitary($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sanitaryapps":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentschema_brGPSSanitary($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sanitaryins":
			if ($objTable->fields["U_INSPECTORREMARKS"]!=$objTable->getudfvalue("u_inspectorremarks") || $objTable->fields["U_INSPECTDATE"]!=$objTable->getudfvalue("u_inspectdate") || $objTable->fields["U_INSPECTTIME"]!=$objTable->getudfvalue("u_inspecttime")) {
				if ($objTable->getudfvalue("u_updatefrommobileapp")=="0") {
					$objTable->setudfvalue("u_inspectorremarkhistory",currentdate()." ".currenttime()." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_inspectorremarks")."\r\n\r\n".$objTable->getudfvalue("u_inspectorremarkhistory"));
				} else {
					$objTable->setudfvalue("u_inspectorremarkhistory",formatDateToHttp($objTable->getudfvalue("u_inspectdate"))." ".formatTimeToHttp($objTable->getudfvalue("u_inspecttime"))." ".$_SESSION["userid"]."\r\n".$objTable->getudfvalue("u_inspectorremarks")."\r\n\r\n".$objTable->getudfvalue("u_inspectorremarkhistory"));
					$objTable->setudfvalue("u_updatefrommobileapp",0);
				}
			}
			if ($objTable->fields["U_INSPECTBYSTATUS"]!=$objTable->getudfvalue("u_inspectbystatus")) {
				if ($objTable->getudfvalue("u_inspectbystatus")!="") {
					if ($objTable->getudfvalue("u_inspectbystatus")=="Passed") {
						$objTable->docstatus = "P";
					} elseif ($objTable->getudfvalue("u_inspectbystatus")=="Failed") {
						$objTable->docstatus = "F";
					}	
				} else {
					$objTable->docstatus = "O";
				}
			}
			/*if ($objTable->fields["U_RECOMMENDBYSTATUS"]!=$objTable->getudfvalue("u_recommendbystatus")) {
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
			*/
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
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSSanitary");
	return $actionReturn;
}


/*
function onUpdateEventdocumentschema_brGPSSanitary($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sanitaryapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSSanitary($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sanitaryapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSSanitary($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_sanitaryapps":
			break;
	}
	return $actionReturn;
}
*/

?>