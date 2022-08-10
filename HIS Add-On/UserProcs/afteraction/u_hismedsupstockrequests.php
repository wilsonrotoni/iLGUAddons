<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_patientname",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_fromdepartment",false);
		if ($page->getitemstring("docstatus")=="C") {
			$page->businessobject->items->seteditable("u_reqtype",false);
			$page->businessobject->items->seteditable("u_reqdate",false);
			$page->businessobject->items->seteditable("u_replenish",false);
			$page->businessobject->items->seteditable("u_withinstock",false);
			$page->businessobject->items->seteditable("u_itemclass",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			$page->businessobject->items->seteditable("u_todepartment",false);
			$objGrids[0]->columninput("u_quantity","type","label");
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
	
	if ($page->getitemstring("u_trxtype")=="PHARMACY" || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="CSR") {
		//$page->businessobject->items->seteditable("u_todepartment",false);
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
		case "PHARMACY": $pageHeader = "Pharmacy Requests - Stocks"; break;
		case "LABORATORY": $pageHeader = "Laboratory Requests - Stocks"; break;
		case "CSR": $pageHeader = "CSR Requests - Stocks"; break;
		case "RADIOLOGY": $pageHeader = "Radiology Requests - Stock"; break;
		case "HEARTSTATION": $pageHeader = "Heart Station Requests - Stocks"; break;
		case "DIETARY": $pageHeader = "Dietary Requests - Stocks"; break;
		case "HOUSEKEEPING": $pageHeader = "Housekeeping Requests - Stocks"; break;
	}	

?>