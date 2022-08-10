<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_tfindate",false);
		$page->businessobject->items->seteditable("u_tfintime",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_amount",false);
		$page->businessobject->items->seteditable("u_tfno",false);
		//if ($page->getitemstring("docstatus")=="C") {
			$page->businessobject->items->seteditable("u_fromdepartment",false);
			$page->businessobject->items->seteditable("u_todepartment",false);
			//$objGrids[0]->columninput("u_result","type","label");
			//$objGrids[0]->columninput("u_remarks","type","label");
			//$objGrids[1]->setaction("add",false);
			$page->toolbar->setaction("update",false);
			$closeoption = false;
		//}
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Returns - Nursing Services"; break;
		case "LABORATORY": $pageHeader = "Blood Returns - Nursing Services"; break;
		case "CSR": $pageHeader = "CSR Returns - Nursing Services"; break;
		case "IP": $pageHeader = "Medicines & Supplies Receipts"; break;
		case "OP": $pageHeader = "Medicines & Supplies Receipts"; break;
	}
	
?>