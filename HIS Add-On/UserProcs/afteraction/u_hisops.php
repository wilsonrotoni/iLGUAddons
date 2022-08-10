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
		
		if ($page->getitemstring("docstatus")=="Admitted") {
			$page->setvar("formAccess","R");
		}
		
		if ($page->getvarstring("formType")!="lnkbtn") {
			/*if ($page->getitemstring("u_mgh")=="1" && $page->getitemstring("docstatus")=="Active") {
				$page->toolbar->addbutton("btncancelmgh","Cancell May Go Home","u_cancelmghGPSHIS()","left");
				$page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
			} else*/
			//if ($page->getitemstring("u_expired")=="1" && $page->getitemstring("docstatus")=="Active") {
			if ($page->getitemstring("docstatus")=="Active") {
				if ($expireoption) $page->toolbar->addbutton("btndischarge","Discharge","u_showDischargedGPSHIS()","left");
				if ($page->getitemstring("u_expired")=="0" && $dischargeoption) $page->toolbar->addbutton("btnexpired","Expired","u_showExpiredGPSHIS()","left");
			} /*else {
				if ($page->getitemstring("docstatus")=="Active") {
					if ($page->getitemstring("u_mgh")=="0" && $page->getitemstring("u_expired")=="0") {
						$page->toolbar->addbutton("btnmgh","May Go Home","u_mghGPSHIS()","left");
						$page->toolbar->addbutton("btnexpired","Expired","u_showExpiredGPSHIS()","left");
						//$page->toolbar->addbutton("admit","Admit Patient","showPopupFrame('popupFrameAdmit',true)","left");
					}	
				}
			}
			*/
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