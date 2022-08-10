<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_patientname",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_fromdepartment",false);
		if ($page->getitemstring("docstatus")=="C") {
			$page->businessobject->items->seteditable("u_reftype",false);
			$page->businessobject->items->seteditable("u_refno",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			$page->businessobject->items->seteditable("u_todepartment",false);
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",false);
			$canceloption = false;
		}
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("find",false);
			$page->toolbar->setaction("new",false);
		}
	}
	
	if ($page->getitemstring("u_trxtype")=="PHARMACY" || $page->getitemstring("u_trxtype")=="LABORATORY"  || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="CSR") {
		$page->businessobject->items->seteditable("u_todepartment",false);
	}
	if ($page->getitemstring("u_trxtype")=="IP" || $page->getitemstring("u_trxtype")=="OP") {
		$page->businessobject->items->seteditable("u_reftype",false);
	}
	if ($page->getitemstring("u_reftype")=="WI") {
		$page->businessobject->items->seteditable("u_refno",false);
	} else {	
		$page->businessobject->items->seteditable("u_patientname",false);
	}	

	$page->toolbar->setaction("navigation",false);
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Requests - Cash Sales"; break;
		case "LABORATORY": $pageHeader = "Blood Requests - Cash Sales"; break;
		case "RADIOLOGY": $pageHeader = "Radiology - Cash Sales"; break;
		case "CSR": $pageHeader = "CSR Requests - Cash Sales"; break;
		case "IP": $pageHeader = "Medicines & Supplies Requests"; break;
		case "OP": $pageHeader = "Medicines & Supplies Requests"; break;
	}	

?>