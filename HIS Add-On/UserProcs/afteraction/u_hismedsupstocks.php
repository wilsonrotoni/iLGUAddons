<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("u_cos",false);
		$page->businessobject->items->seteditable("u_cosstartdate",false);
		$page->businessobject->items->seteditable("u_cosenddate",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_totalamount",false);
		$page->businessobject->items->seteditable("u_fromdepartment",false);
		$page->businessobject->items->seteditable("u_todepartment",false);
		$objGrids[0]->columninput("u_quantity","type","label");
		$objGrids[0]->columninput("u_qtypu","type","label");
		$page->businessobject->items->seteditable("u_multipledepartment",false);
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("find",false);
		}
		
		if ($page->getitemstring("docstatus")=="C") {
			$objGrids[0]->dataentry = false;
			$canceloption = true;
		}
		
		if ($page->getitemstring("docstatus")=="CN") {
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",false);
		}	
		
	} else {
		if ($page->getitemstring("u_type")=="Sales") {
			$page->toolbar->addbutton("generateinstock","Generate In-Stock Template","formSubmit('generateinstock')","left");
		}	

	}
	
	if ($page->getitemstring("u_trxtype")!="WAREHOUSE") {
		//$page->businessobject->items->seteditable("u_todepartment",false);
		
	}	
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Issuances - Stocks"; break;
		case "LABORATORY": $pageHeader = "Laboratory Issuances - Stocks"; break;
		case "CSR": $pageHeader = "CSR Issuances - Stocks"; break;
		case "IP": $pageHeader = "Nursing Station Issuances - Stocks"; break;
		case "OP": $pageHeader = "Out-Patient Issuances - Stocks"; break;
		case "RADIOLOGY": $pageHeader = "Radiology Issuances - Stocks"; break;
		case "HEARTSTATION": $pageHeader = "Heart Station Issuances - Stocks"; break;
		case "DIETARY": $pageHeader = "Dietary Issuances - Stocks"; break;
		case "HOUSEKEEPING": $pageHeader = "Housekeeping Issuances - Stocks"; break;
		case "WAREHOUSE": $pageHeader = "Warehouse Issuances - Stocks"; break;
	}
	
	$page->toolbar->setaction("navigation",false);
	$page->toolbar->setaction("new",false);
	
	
?>