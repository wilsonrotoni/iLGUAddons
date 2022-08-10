<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_patientid",false);
		//$page->businessobject->items->seteditable("u_startdate",false);
		//$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_paymentterm",false);
		$page->businessobject->items->seteditable("u_disccode",false);
		if ($page->getitemstring("u_discharged")=="1") {
			$page->businessobject->items->seteditable("u_typeofdischarge",false);
			$page->businessobject->items->seteditable("u_resultimproved",false);
			$page->businessobject->items->seteditable("u_resultrecovered",false);
			$page->businessobject->items->seteditable("u_healthcareins_to",false);
		}
		if ($page->getitemstring("u_expired")=="1") {
			$page->businessobject->items->seteditable("u_expiredate",false);
			$page->businessobject->items->seteditable("u_expiretime",false);
			$page->businessobject->items->seteditable("u_typeofexpire",false);
			$page->businessobject->items->seteditable("u_expiredhours",false);
			$page->businessobject->items->seteditable("u_resultautopsied",false);
		}
		
		if ($page->getvarstring("formType")!="lnkbtn") {
			if ($page->getitemstring("u_nursed")=="1" && $page->getitemstring("u_mgh")=="0" && $page->getitemstring("u_expired")=="0" && $page->getitemstring("u_trxtype")=="IP") {
				$page->toolbar->addbutton("tfroom","Room Transfer","u_roomtfGPSHIS()","left");
			}	
			
			if ($page->getitemstring("u_nursed")=="0" && $page->getitemstring("u_trxtype")=="IP" && $page->getitemstring("docstatus")=="Active") {
				$page->toolbar->addbutton("btncancel","Cancel","u_cancelGPSHIS()","right");
			}				
			if ($page->getitemstring("u_mgh")=="1" && ($page->getitemstring("docstatus")=="Active" || $page->getitemstring("docstatus")=="For Discharge")) {
				if ($page->getitemstring("u_trxtype")=="NURSE" && $page->getitemstring("docstatus")=="Active") $page->toolbar->addbutton("btncancelmgh","Cancell May Go Home","u_cancelmghGPSHIS()","left");
				if ($page->getitemstring("u_trxtype")=="NURSE") {
					//$page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
					
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select c.code,c.u_minhours,c.u_minhours18 from u_hisphicclaims a inner join u_hisphicclaimdiagnosis b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_is1stcr=1 inner join u_hisphicicds c on c.code=b.u_icdcode where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_billno='".$page->getitemstring("u_billno")."'");
					if ($objRs->queryfetchrow("NAME")) {
						$admithrs = datedifference("h",$page->getitemdate("u_startdate")." ".formatTimeToDB($page->getitemstring("u_starttime")),date('Y-m-d H:i:s'));
						//var_dump(array($objRs->fields["code"],$objRs->fields["u_minhours"],$objRs->fields["u_minhours18"],$admithrs));
						if ($page->getitemstring("u_age_y")>18) {
							if ($admithrs>=$objRs->fields["u_minhours18"]) {
								$page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
							}
						} else {
							if ($admithrs>=$objRs->fields["u_minhours"]) {
								$page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
							}
						}
					} else {
						$page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
					}
					
				}	
			//} elseif ($page->getitemstring("u_expired")=="1" && $page->getitemstring("docstatus")=="Active") {
			//	if ($page->getitemstring("u_trxtype")=="NURSE") $page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
			} else {
				if ($page->getitemstring("docstatus")=="Active") {
					if ($page->getitemstring("u_trxtype")=="NURSE") $page->toolbar->addbutton("btnmgh","May Go Home","u_mghGPSHIS()","left");
					if ($page->getitemstring("u_nursed")=="1" && $page->getitemstring("u_mgh")=="0" && $page->getitemstring("u_expired")=="0") {
						if ($page->getitemstring("u_trxtype")=="NURSE") $page->toolbar->addbutton("btnexpired","Expired","u_showExpiredGPSHIS()","left");
						//$page->toolbar->addbutton("admit","Admit Patient","showPopupFrame('popupFrameAdmit',true)","left");
					}	
				} else {
					if ($_SESSION["roleid"]!="FIN-PHIC") {
						$page->setvar("formAccess","R");
					}	
				}
			}
			
			if ($page->getvarstring("editoption")=="u_nursed") {
				$page->toolbar->addbutton("btnNursed","Nurse Station Receiving","u_nursedGPSHIS()","left");
			}
		} else {
			$page->setvar("formAccess","R");
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}	
		$page->toolbar->addbutton("u_hisclinicgynes","Gynecologic","OpenLnkBtnu_hisclinicgynesGPSHIS()","right");
		$page->toolbar->addbutton("u_hisclinicobs","Obstetrical","OpenLnkBtnu_hisclinicobsGPSHIS()","right");
		if ($_SESSION["roleid"]!="DOCTOR") {
			$page->toolbar->addbutton("doctorsdiagnosis","Doctor's diagnosis","showPopupFrame('popupFrameDoctorsDiagnosis',true)","left");
		}	
	}
	
?>