<?php
 


function onBeforeAddEventdocumentschema_brGPSQUEUING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_queuing":
                    if ($actionReturn && $objTable->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_cahsiername")!="") {
				
                        $obju_Que = new documentschema_br(null,$objConnection,"u_queuing");
				
				if ($obju_Que->getbykey($objTable->getudfvalue("u_cashiername"))) {
                                    
                                    $obju_Que->setudfvalue(docstatus,0);
				    $actionReturn = $obju_Que->update($obju_Que->u_cashiername,$obju_Que->rcdversion);
				} 
			
			}
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentschema_brGPSQUEUING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_queuing":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeUpdateEventdocumentschema_brGPSQUEUING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_queuing":
			break;
	}
	return $actionReturn;
}
*/

/*
function onUpdateEventdocumentschema_brGPSQUEUING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_queuing":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSQUEUING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_queuing":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSQUEUING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_queuing":
			break;
	}
	return $actionReturn;
}
*/

?>