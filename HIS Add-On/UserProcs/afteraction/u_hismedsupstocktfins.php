<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_tfno",false);
		$page->businessobject->items->seteditable("u_tfindate",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_tfno",false);
			$page->businessobject->items->seteditable("u_fromdepartment",false);
			$page->businessobject->items->seteditable("u_todepartment",false);
			//$objGrids[0]->columninput("u_result","type","label");
			//$objGrids[0]->columninput("u_remarks","type","label");
			//$objGrids[1]->setaction("add",false);
			if ($page->getitemstring("docstatus")=="CN") {
				$page->toolbar->setaction("update",false);
			}	
			$closeoption = false;
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("find",false);
		}
	} else {
		if ($page->getitemstring("u_trxtype")=="WAREHOUSE" || $page->getitemstring("u_trxtype")=="CSR" || $page->getitemstring("u_trxtype")=="PHARMACY") {
			$objGrids[0]->columninput("u_quantity","type","text");
			$objGrids[0]->columninput("u_qtypu","type","text");
			$objGrids[0]->dataentry = true;
			$page->businessobject->items->seteditable("u_fromdepartment",true);
		}	
	}
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Transfer-In - Stocks"; break;
		case "LABORATORY": $pageHeader = "Laboratory Transfer-In - Stocks"; break;
		case "CSR": $pageHeader = "CSR Transfer-In - Stocks"; break;
		case "IP": $pageHeader = "Nursing Station Transfer-In - Stocks"; break;
		case "OP": $pageHeader = "Out-Patient Transfer-In - Stocks"; break;
		case "RADIOLOGY": $pageHeader = "Radiology Transfer-In - Stocks"; break;
		case "HEARTSTATION": $pageHeader = "Heart Station Transfer-In - Stocks"; break;
		case "DIETARY": $pageHeader = "Dietary Transfer-In - Stocks"; break;
		case "HOUSEKEEPING": $pageHeader = "Housekeeping Transfer-In - Stocks"; break;
		case "WAREHOUSE": $pageHeader = "Warehouse Transfer-In - Stocks"; break;
	}

	$page->toolbar->setaction("navigation",false);
	
?>