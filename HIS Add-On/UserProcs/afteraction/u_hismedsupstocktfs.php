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
		$objGrids[0]->columninput("u_quantity","type","label");
		$objGrids[0]->columninput("u_qtypu","type","label");
		if (($page->getitemstring("u_tfinno")!="" && $page->getitemstring("docstatus")=="C") || $page->getitemstring("docstatus")=="CN" || $page->getitemstring("docstatus")=="O") {
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",false);
			$deleteoption = false;
			$canceloption = false;
		}
		
		if ($page->getitemstring("docstatus")=="C") {
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",true);
			$canceloption = true;
		}
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Transfer-Out - Stocks"; break;
		case "LABORATORY": $pageHeader = "Laboratory Transfer-Out - Stocks"; break;
		case "CSR": $pageHeader = "CSR Transfer-Out - Stocks"; break;
		case "IP": $pageHeader = "Nursing Station Transfer-Out - Stocks"; break;
		case "OP": $pageHeader = "Out-Patient Transfer-Out - Stocks"; break;
		case "RADIOLOGY": $pageHeader = "Radiology Transfer-Out - Stocks"; break;
		case "HEARTSTATION": $pageHeader = "Heart Station Transfer-Out - Stocks"; break;
		case "DIETARY": $pageHeader = "Dietary Transfer-Out - Stocks"; break;
		case "HOUSEKEEPING": $pageHeader = "Housekeeping Transfer-Out - Stocks"; break;
		case "WAREHOUSE": $pageHeader = "Warehouse Transfer-Out - Stocks"; break;
	}
	
?>