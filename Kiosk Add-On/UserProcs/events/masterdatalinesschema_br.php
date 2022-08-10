<?php
 

/*
function onBeforeAddEventmasterdatalinesschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkschedulemasterfiletimes":
			break;
	
	}
	return $actionReturn;
}
*/


function onAddEventmasterdatalinesschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkrequestformleavegrid":
			if ($objTable->getudfvalue("u_leavedays") == 0) {
				return raiseError('Error in Zero Leave Days. Please Try Again');
			}
			
			if ($objTable->getudfvalue("u_scheduleassign") == "") {
				return raiseError('Error in Blank Schedule Assign. Please Try Again');
			}
			
			$condition_type = $objTable->getudfvalue("u_am")+$objTable->getudfvalue("u_pm")+$objTable->getudfvalue("u_lv");
			if ($condition_type == 0) {
				return raiseError('Error in No Check For (AM/PM/LEAVE). Please Try Again');
			}
			break;
		case "u_tkrequestformoballlist":
			if ($objTable->getudfvalue("u_type_filter") == "-") {
				return raiseError('Error in Official Business Type. Please Try Again');
			} else if ($objTable->getudfvalue("u_type_filter") == "Days") {
				if($objTable->getudfvalue("u_obfromtime") == "" && $objTable->getudfvalue("u_obtotime") == "") {
					return raiseError('Error in No Time From & To. Please Try Again');
				}
			} else if ($objTable->getudfvalue("u_type_filter") == "PM") {
				if($objTable->getudfvalue("u_obtotime") == "") {
					return raiseError('Error in No Time To. Please Try Again');
				}
			} else if ($objTable->getudfvalue("u_type_filter") == "AM") {
				if($objTable->getudfvalue("u_obfromtime") == "") {
					return raiseError('Error in No Time From. Please Try Again');
				}
			}
			break;
	
	}
	return $actionReturn;
}

/*
function onBeforeUpdateEventmasterdatalinesschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkschedulemasterfiletimes":
			break;
	
	}
	return $actionReturn;
}
*/


/*function onUpdateEventmasterdatalinesschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkapproverfileassigned":
			$objUsers = new users(null,$objConnection);
			$actionReturn = $objUsers->prepareupdate($objTable->fields["U_STAGENAME"]);
			if ($actionReturn) {
				$objUsers->usertype = "E";
				$objUsers->groupid = "KIOSK";
				$actionReturn = $objUsers->update($objUsers->userid,$objUsers->rcdversion);
				//if (!$actionReturn) break;
			}
			
			
			$actionReturn = $objUsers->prepareupdate($objTable->getudfvalue("u_stagename"));
			if ($actionReturn) {
				$objUsers->usertype = "A";
				$objUsers->groupid = "APPROVER";
				$actionReturn = $objUsers->update($objUsers->userid,$objUsers->rcdversion);
				//if (!$actionReturn) break;
			}
			break;
	
	}
	return $actionReturn;
}*/


/*
function onBeforeDeleteEventmasterdatalinesschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkschedulemasterfiletimes":
			break;
	
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventmasterdatalinesschema_brGPSKiosk($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_tkschedulemasterfiletimes":
			break;
	
	}
	return $actionReturn;
}
*/

?> 

