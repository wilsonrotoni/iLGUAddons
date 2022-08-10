<?php
 include_once("./classes/masterdataschema_br.php"); 
 include_once("./classes/documentlinesschema_br.php"); 
	include_once("./utils/journalentries.php");


function onBeforeAddEventdocumentschema_brGPSLGUAcctgCAVBacoor($objTable) {
	global $objConnection;
	$actionReturn = true;
	//switch ($objTable->dbtable) {
	//}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSLGUAcctgCAVBacoor()");
	return $actionReturn;
}


function onAddEventdocumentschema_brGPSLGUAcctgCAVBacoor($objTable) {
	global $objConnection;
	$actionReturn = true;
	//switch ($objTable->dbtable) {
	//}		
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSLGUAcctgCAVBacoor($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_lgucheckds":
			if ($objTable->fields["DOCSTATUS"]=="D" && $objTable->docstatus=="O") {
				if ($objTable->getudfvalue("u_refno")=="?") {
					switch ($_SESSION["branch"]) {
						case "100":	$docseries = getSeries("u_lgucheckds","GF"); break;
						case "200":	$docseries = getSeries("u_lgucheckds","SEF"); break;
						case "300":	$docseries = getSeries("u_lgucheckds","TF"); break;
					}	
					$refno = getNextSeriesNoByBranch("u_lgucheckds",$docseries,$objTable->getudfvalue("u_date"));
					$//refno = str_replace("{EC}", $objTable->getudfvalue("u_expclass"), $refno);
					$objTable->docseries = $docseries;
					$objTable->setudfvalue("u_refno",$refno);
				} else {
					$objTable->docseries = -1;
				}
				//$objTable->docno = $objTable->getudfvalue("u_refno");
			}	
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSLGUAcctgCAVBacoor()");
	return $actionReturn;
}



function onUpdateEventdocumentschema_brGPSLGUAcctgCAVBacoor($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	//switch ($objTable->dbtable) {
	//}		
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSLGUAcctgCAVBacoor($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSLGUAcctgCAVBacoor($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_posregisters":
			break;
	}
	return $actionReturn;
}
*/

?>
