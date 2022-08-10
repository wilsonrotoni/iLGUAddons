<?php
 
 include_once("./classes/masterdataschema_br.php");
 include_once("./classes/masterdatalinesschema_br.php");


function onBeforeAddEventdocumentlinesschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisiprooms":
			if ($objTable->getudfvalue("u_enddate")=="") {
				$obju_HISRooms = new masterdataschema_br(null,$objConnection,"u_hisrooms");
				$obju_HISRoomBeds = new masterdatalinesschema_br(null,$objConnection,"u_hisroombeds");
				$obju_HISRoomRs = new documentschema_br(null,$objConnection,"u_hisroomrs");
				if ($obju_HISRooms->getbykey($objTable->getudfvalue("u_roomno"))) {
					if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$objTable->getudfvalue("u_bedno")."'")) {
						//if ($obju_HISRoomBeds->getudfvalue("u_status")=="Vacant" || $obju_HISRoomBeds->getudfvalue("u_status")=="Reserved") {
							if ($obju_HISRoomBeds->getudfvalue("u_status")=="Reserved") {
								if ($obju_HISRoomRs->getbykey($obju_HISRoomBeds->getudfvalue("u_refno"))) {
									$obju_HISRoomRs->docstatus = "C";
									$actionReturn = $obju_HISRoomRs->update($obju_HISRoomRs->docno,$obju_HISRoomRs->rcdversion);
								} else return raiseError("Unable to find Room Reservation No.[".$obju_HISRoomBeds->getudfvalue("u_refno")."].");
							}
							if ($actionReturn) {
								$obju_HISRoomBeds->setudfvalue("u_status","Occupied");
								$obju_HISRoomBeds->setudfvalue("u_refno",$objTable->privatedata["header"]->docno);
								$obju_HISRoomBeds->setudfvalue("u_patientname",$objTable->privatedata["header"]->getudfvalue("u_patientname"));
								$obju_HISRoomBeds->setudfvalue("u_date",$objTable->getudfvalue("u_startdate"));
								$obju_HISRoomBeds->setudfvalue("u_time",$objTable->getudfvalue("u_starttime"));
								$actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
								if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
							}	
						//} else return raiseError("Bed No.[".$objTable->getudfvalue("u_bedno")."] is not vacant.");
					} else return raiseError("Unable to find Bed No.[".$objTable->getudfvalue("u_bedno")."].");
				} else return raiseError("Unable to find Room No.[".$objTable->getudfvalue("u_roomno")."].");
			}	
			break;
		case "u_hisipallergicmeds":
		case "u_hisopallergicmeds":
			$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
			$obju_HISPatientAllergicMeds = new masterdatalinesschema_br(null,$objConnection,"u_hispatientallergicmeds");
			if ($obju_HISPatients->getbykey($objTable->privatedata["header"]->getudfvalue("u_patientid"))) {
				if (!$obju_HISPatientAllergicMeds->getbysql("CODE='".$obju_HISPatients->code."' AND U_NAME='".$objTable->getudfvalue("u_name")."'")) {
					$obju_HISPatientAllergicMeds->code = $obju_HISPatients->code;
					$obju_HISPatientAllergicMeds->lineid = getNextIdByBranch("u_hispatientallergicmeds",$objConnection);
					$obju_HISPatientAllergicMeds->setudfvalue("u_name",$objTable->getudfvalue("u_name"));
				}	
				$obju_HISPatientAllergicMeds->setudfvalue("u_refcount",$obju_HISPatientAllergicMeds->getudfvalue("u_refcount")+1);
				$obju_HISPatientAllergicMeds->privatedata["header"] = $obju_HISPatients;
				if ($obju_HISPatientAllergicMeds->rowstat=="N")	$actionReturn = $obju_HISPatientAllergicMeds->add();
				else $obju_HISPatientAllergicMeds->update($obju_HISPatientAllergicMeds->code,$obju_HISPatientAllergicMeds->lineid,$obju_HISPatientAllergicMeds->rcdversion);
			} else return raiseError("Unable to find Patient Record[".$objTable->getudfvalue("u_patientid")."].");
			break;			
		case "u_hisinclaimxmtalitems":
			if ($objTable->getudfvalue("u_selected")=="1") {
				switch ($objTable->getudfvalue("u_reftype")) {
					case "BILLINS":
						$obju_HISInClaims = new documentschema_br(null,$objConnection,"u_hisinclaims");
						if ($obju_HISInClaims->getbykey($objTable->getudfvalue("u_refno"))) {
							if ($obju_HISInClaims->getudfvalue("u_xmtalno")=="") {
								$obju_HISInClaims->setudfvalue("u_xmtalno",$objTable->privatedata["header"]->docno);
								$obju_HISInClaims->setudfvalue("u_xmtaldate",$objTable->privatedata["header"]->getudfvalue("u_docdate"));
								$actionReturn = $obju_HISInClaims->update($obju_HISInClaims->docno,$obju_HISInClaims->rcdversion);
							} else return raiseError("Claim No.[".$objTable->getudfvalue("u_refno")."] already have transmittal no.[".$obju_HISInClaims->getudfvalue("u_xmtalno")."] dated[".$obju_HISInClaims->getudfvalue("u_xmtaldate")."]."); 
							
						} else return raiseError("Unable to find Claim No.[".$objTable->getudfvalue("u_refno")."].");
						break;
					case "BILLPNS":
						$obju_HISInPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
						if ($obju_HISInPronotes->getbykey($objTable->getudfvalue("u_refno"))) {
							if ($obju_HISInPronotes->getudfvalue("u_xmtalno")=="") {
								$obju_HISInPronotes->setudfvalue("u_xmtalno",$objTable->privatedata["header"]->docno);
								$obju_HISInPronotes->setudfvalue("u_xmtaldate",$objTable->privatedata["header"]->getudfvalue("u_docdate"));
								$actionReturn = $obju_HISInPronotes->update($obju_HISInPronotes->docno,$obju_HISInPronotes->rcdversion);
							} else return raiseError("Claim No.[".$objTable->getudfvalue("u_refno")."] already have transmittal no.[".$obju_HISInPronotes->getudfvalue("u_xmtalno")."] dated[".$obju_HISInPronotes->getudfvalue("u_xmtaldate")."]."); 
							
						} else return raiseError("Unable to find Debit/Credit/Pronote No.[".$objTable->getudfvalue("u_refno")."].");
						break;
					default:
						$obju_HISPOS = new documentschema_br(null,$objConnection,"u_hispos");
						$obju_HISPOSIns = new documentlinesschema_br(null,$objConnection,"u_hisposins");
						if ($obju_HISPOS->getbykey($objTable->getudfvalue("u_refno"))) {
							if ($obju_HISPOSIns->getbysql("DOCID='$obju_HISPOS->docid' AND U_INSCODE='".$objTable->privatedata["header"]->getudfvalue("u_inscode")."'")) {
								if ($obju_HISPOSIns->getudfvalue("u_xmtalno")=="") {
									$obju_HISPOSIns->setudfvalue("u_xmtalno",$objTable->privatedata["header"]->docno);
									$obju_HISPOSIns->setudfvalue("u_xmtaldate",$objTable->privatedata["header"]->getudfvalue("u_docdate"));
									$actionReturn = $obju_HISPOSIns->update($obju_HISPOSIns->docid,$obju_HISPOSIns->lineid,$obju_HISPOSIns->rcdversion);
									if ($actionReturn)$actionReturn = $obju_HISPOS->update($obju_HISPOS->docno,$obju_HISPOS->rcdversion);
								} else return raiseError("Cash Sales/Credit Memo [".$objTable->getudfvalue("u_refno")."] Health Benefit [".$objTable->privatedata["header"]->getudfvalue("u_inscode")."] already have transmittal no.[".$obju_HISPOSIns->getudfvalue("u_xmtalno")."] dated[".$obju_HISPOSIns->getudfvalue("u_xmtaldate")."]."); 
							} else return raiseError("Unable to find Cash Sales/Credit Memo No.[".$objTable->getudfvalue("u_refno")."] Health Benefit [".$objTable->privatedata["header"]->getudfvalue("u_inscode")."].");
							
						} else return raiseError("Unable to find Cash Sales/Credit Memo No.[".$objTable->getudfvalue("u_refno")."].");
						break;	
				}	
			}	
			break;
		case "u_hisinclaimxmtalreconitems":
			if ($objTable->getudfvalue("u_selected")=="1") {
				$obju_HISInClaimXmtals = new documentschema_br(null,$objConnection,"u_hisinclaimxmtals");
				$obju_HISInClaimXmtalItems = new documentlinesschema_br(null,$objConnection,"u_hisinclaimxmtalitems");
				if ($obju_HISInClaimXmtals->getbykey($objTable->privatedata["header"]->getudfvalue("u_xmtalno"))) {
					if ($obju_HISInClaimXmtalItems->getbysql("DOCID=".$obju_HISInClaimXmtals->docid." AND U_REFNO='".$objTable->getudfvalue("u_refno")."'")) {
						$obju_HISInClaimXmtalItems->setudfvalue("u_status",$objTable->privatedata["header"]->getudfvalue("u_claimstatus"));
						$actionReturn = $obju_HISInClaimXmtalItems->update($obju_HISInClaimXmtalItems->docid,$obju_HISInClaimXmtalItems->lineid,$obju_HISInClaimXmtalItems->rcdversion);
						if ($actionReturn) $actionReturn = $obju_HISInClaimXmtals->update($obju_HISInClaimXmtals->docno,$obju_HISInClaimXmtals->rcdversion);
					} else return raiseError("Unable to find Transmittal No.[".$objTable->privatedata["header"]->getudfvalue("u_xmtalno")."] with Ref No [".$objTable->getudfvalue("u_refno")."].");
				
				} else return raiseError("Unable to find Transmittal No.[".$objTable->privatedata["header"]->getudfvalue("u_xmtalno")."].");
				
				if ($actionReturn && $objTable->privatedata["header"]->getudfvalue("u_claimstatus")=="Returned") {
					switch ($objTable->getudfvalue("u_reftype")) {
						case "BILLINS":
							$obju_HISInClaims = new documentschema_br(null,$objConnection,"u_hisinclaims");
							if ($obju_HISInClaims->getbykey($objTable->getudfvalue("u_refno"))) {
								if ($obju_HISInClaims->getudfvalue("u_xmtalno")==$objTable->privatedata["header"]->getudfvalue("u_xmtalno")) {
									$obju_HISInClaims->setudfvalue("u_xmtalno","");
									$obju_HISInClaims->setudfvalue("u_xmtaldate","");
									$actionReturn = $obju_HISInClaims->update($obju_HISInClaims->docno,$obju_HISInClaims->rcdversion);
								} else return raiseError("Claim No.[".$objTable->getudfvalue("u_refno")."] have different transmittal no.[".$obju_HISInClaims->getudfvalue("u_xmtalno")."] dated[".$obju_HISInClaims->getudfvalue("u_xmtaldate")."]."); 
								
							} else return raiseError("Unable to find Claim No.[".$objTable->getudfvalue("u_refno")."].");
							break;
						case "BILLPNS":
							$obju_HISInPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
							if ($obju_HISInPronotes->getbykey($objTable->getudfvalue("u_refno"))) {
								if ($obju_HISInPronotes->getudfvalue("u_xmtalno")==$objTable->privatedata["header"]->getudfvalue("u_xmtalno")) {
									$obju_HISInPronotes->setudfvalue("u_xmtalno","");
									$obju_HISInPronotes->setudfvalue("u_xmtaldate","");
									$actionReturn = $obju_HISInPronotes->update($obju_HISInPronotes->docno,$obju_HISInPronotes->rcdversion);
								} else return raiseError("Claim No.[".$objTable->getudfvalue("u_refno")."] have different transmittal no.[".$obju_HISInPronotes->getudfvalue("u_xmtalno")."] dated[".$obju_HISInPronotes->getudfvalue("u_xmtaldate")."]."); 
								
							} else return raiseError("Unable to find Debit/Credit/Pronote No.[".$objTable->getudfvalue("u_refno")."].");
							break;
						default:
							$obju_HISPOS = new documentschema_br(null,$objConnection,"u_hispos");
							$obju_HISPOSIns = new documentlinesschema_br(null,$objConnection,"u_hisposins");
							if ($obju_HISPOS->getbykey($objTable->getudfvalue("u_refno"))) {
								if ($obju_HISPOSIns->getbysql("DOCID='$obju_HISPOS->docid' AND U_INSCODE='".$objTable->privatedata["header"]->getudfvalue("u_inscode")."'")) {
									if ($obju_HISPOSIns->getudfvalue("u_xmtalno")==$objTable->privatedata["header"]->getudfvalue("u_xmtalno")) {
										$obju_HISPOSIns->setudfvalue("u_xmtalno","");
										$obju_HISPOSIns->setudfvalue("u_xmtaldate","");
										$actionReturn = $obju_HISPOSIns->update($obju_HISPOSIns->docid,$obju_HISPOSIns->lineid,$obju_HISPOSIns->rcdversion);
										if ($actionReturn)$actionReturn = $obju_HISPOS->update($obju_HISPOS->docno,$obju_HISPOS->rcdversion);
									} else return raiseError("Cash Sales/Credit Memo [".$objTable->getudfvalue("u_refno")."] Health Benefit [".$objTable->privatedata["header"]->getudfvalue("u_inscode")."] have different transmittal no.[".$obju_HISPOSIns->getudfvalue("u_xmtalno")."] dated[".$obju_HISPOSIns->getudfvalue("u_xmtaldate")."]."); 
								} else return raiseError("Unable to find Cash Sales/Credit Memo No.[".$objTable->getudfvalue("u_refno")."] Health Benefit [".$objTable->privatedata["header"]->getudfvalue("u_inscode")."].");
								
							} else return raiseError("Unable to find Cash Sales/Credit Memo No.[".$objTable->getudfvalue("u_refno")."].");
							break;	
					}	
				}	
			}	
			break;			
		case "u_hisdietaryregs":
			$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				if ($obju_HISIPs->getudfvalue("u_diettype")!=$objTable->getudfvalue("u_diettype")) {
					$obju_HISIPs->setudfvalue("u_prevdiettype",$obju_HISIPs->getudfvalue("u_diettype"));
					$obju_HISIPs->setudfvalue("u_prevdiettypedesc",$obju_HISIPs->getudfvalue("u_diettypedesc"));
					$obju_HISIPs->setudfvalue("u_prevdietremarks",$obju_HISIPs->getudfvalue("u_dietremarks"));
					$obju_HISIPs->setudfvalue("u_diettype",$objTable->getudfvalue("u_diettype"));
					$obju_HISIPs->setudfvalue("u_diettypedesc",$objTable->getudfvalue("u_diettypedesc"));
					$obju_HISIPs->setudfvalue("u_dietremarks",$objTable->getudfvalue("u_remarks"));
					$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
				}
			} else return raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."].");
			break;
		case "u_hisdietscheditems":
			if ($objTable->getudfvalue("u_selected")=="1") {
				$obju_HISDiets = new documentschema_br(null,$objConnection,"u_hisdiets");
				if ($obju_HISDiets->getbykey($objTable->getudfvalue("u_requestno"))) {
					if ($obju_HISDiets->docstatus=="O") {
						$obju_HISDiets->docstatus="C";
						$actionReturn = $obju_HISDiets->update($obju_HISDiets->docno,$obju_HISDiets->rcdversion);
					} else return raiseError("Request No.[".$objTable->getudfvalue("u_requestno")."] is not open."); 
					
				} else return raiseError("Unable to find Request No.[".$objTable->getudfvalue("u_requestno")."].");
			}	
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentlinesschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisipreasons":
			break;
		case "u_hisiprooms":
			break;
	
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentlinesschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisdietaryregs":
			$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				if ($obju_HISIPs->getudfvalue("u_diettype")!=$objTable->getudfvalue("u_diettype")) {
					$obju_HISIPs->setudfvalue("u_prevdiettype",$obju_HISIPs->getudfvalue("u_diettype"));
					$obju_HISIPs->setudfvalue("u_prevdiettypedesc",$obju_HISIPs->getudfvalue("u_diettypedesc"));
					$obju_HISIPs->setudfvalue("u_prevdietremarks",$obju_HISIPs->getudfvalue("u_dietremarks"));
					$obju_HISIPs->setudfvalue("u_diettype",$objTable->getudfvalue("u_diettype"));
					$obju_HISIPs->setudfvalue("u_diettypedesc",$objTable->getudfvalue("u_diettypedesc"));
					$obju_HISIPs->setudfvalue("u_dietremarks",$objTable->getudfvalue("u_remarks"));
					$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
				}
			} else return raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."].");
			break;
	
	}
	return $actionReturn;
}


/*
function onUpdateEventdocumentlinesschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisipreasons":
			break;
		case "u_hisiprooms":
			break;
	
	}
	return $actionReturn;
}
*/


function onBeforeDeleteEventdocumentlinesschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisipallergicmeds":
		case "u_hisopallergicmeds":
			$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
			$obju_HISPatientAllergicMeds = new masterdatalinesschema_br(null,$objConnection,"u_hispatientallergicmeds");
			if ($obju_HISPatients->getbykey($objTable->privatedata["header"]->getudfvalue("u_patientid"))) {
				if ($obju_HISPatientAllergicMeds->getbysql("CODE='".$obju_HISPatients->code."' AND U_NAME='".$objTable->getudfvalue("u_name")."'")) {
					$obju_HISPatientAllergicMeds->setudfvalue("u_refcount",$obju_HISPatientAllergicMeds->getudfvalue("u_refcount")-1);
					$obju_HISPatientAllergicMeds->privatedata["header"] = $obju_HISPatients;
					if ($obju_HISPatientAllergicMeds->getudfvalue("u_refcount")==0) {
						$obju_HISPatientAllergicMeds->delete();
					} else {
						$obju_HISPatientAllergicMeds->update($obju_HISPatientAllergicMeds->code,$obju_HISPatientAllergicMeds->lineid,$obju_HISPatientAllergicMeds->rcdversion);
					}	
				}	
			} else return raiseError("Unable to find Patient Record[".$objTable->getudfvalue("u_patientid")."].");
			break;			
	}
	return $actionReturn;
}


/*
function onDeleteEventdocumentlinesschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisipreasons":
			break;
		case "u_hisiprooms":
			break;
	
	}
	return $actionReturn;
}
*/

?>
