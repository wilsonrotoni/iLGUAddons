<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_patientname",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_department",false);
		if ($page->getitemstring("docstatus")!="D") {
			$page->businessobject->items->seteditable("u_reqdate",false);
			$page->businessobject->items->seteditable("u_suppno",false);
			$page->businessobject->items->seteditable("u_replenish",false);
			$page->businessobject->items->seteditable("u_itemclass",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			
			$objGrids[0]->columninput("u_quantity","type","label");
			$objGrids[0]->columninput("u_quantitypu","type","label");
			$objGrids[0]->columninput("u_unitprice","type","label");
			$objGrids[0]->columninput("u_isinvuom","type","label");
			
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",false);
			$canceloption = false;
		}
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("find",false);
			$page->toolbar->setaction("new",false);
		}
	} else {
		if ($page->getitemstring("u_replenish")==0) {
			$page->businessobject->items->seteditable("u_itemclass",false);
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
		case "WAREHOUSE": $pageHeader = "Warehouse Purchase Requests - Stocks"; break;
	}	

?>