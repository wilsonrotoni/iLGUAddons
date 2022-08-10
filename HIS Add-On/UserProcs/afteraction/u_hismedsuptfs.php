<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_intransit",false);
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_tfdate",false);
		$page->businessobject->items->seteditable("u_tftime",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_amount",false);
		$page->businessobject->items->seteditable("u_fromdepartment",false);
		$page->businessobject->items->seteditable("u_todepartment",false);
		if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("docstatus")=="CN") {
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",false);
			$canceloption = false;
		}
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Deliveries - Nursing Services"; break;
		case "LABORATORY": $pageHeader = "Blood Deliveries - Nursing Services"; break;
		case "CSR": $pageHeader = "CSR Deliveries - Nursing Services"; break;
		case "IP": $pageHeader = "Medicines & Supplies Returns/Transfers"; break;
		case "OP": $pageHeader = "Medicines & Supplies Returns/Transfers"; break;
		case "SPLROOM": $pageHeader = "Medicines & Supplies Returns/Transfers"; break;
	}
	
?>