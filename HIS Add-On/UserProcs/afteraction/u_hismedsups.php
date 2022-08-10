<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_pricelist",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_description",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_amount",false);
		$objGrids[0]->dataentry = false;
		if ($page->getitemstring("u_returned")=="1") {
			$canceloption = false;
		}
		
		if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("docstatus")=="CN") {
			$page->businessobject->items->seteditable("u_department",false);
			$page->businessobject->items->seteditable("u_reqdate",false);
			$page->businessobject->items->seteditable("u_reqtime",false);
			$page->businessobject->items->seteditable("u_enddate",false);
			$page->businessobject->items->seteditable("u_endtime",false);
			//$objGrids[0]->columninput("u_result","type","label");
			//$objGrids[0]->columninput("u_remarks","type","label");
			//$objGrids[1]->setaction("add",false);
			$page->toolbar->setaction("update",false);
			$closeoption = false;
		}
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	if ($page->getitemstring("u_trxtype")=="PHARMACY"  || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="CSR" || $page->getitemstring("u_trxtype")=="RADIOLOGY") {
		/*$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_pricelist",false);
		$page->businessobject->items->seteditable("u_disccode",false);
		
		$objGrids[0]->dataentry = false;*/
	} else {
		$page->businessobject->items->setvisible("u_requestno",false);
	}
	
	if ($page->getitemstring("u_trxtype")=="IP"  || $page->getitemstring("u_trxtype")=="OP") {
		$page->businessobject->items->seteditable("u_reftype",false);
	}
	
	switch ($page->getitemstring("u_trxtype")) {
		case "PHARMACY": $pageHeader = "Pharmacy Issuances"; break;
		case "LABORATORY": $pageHeader = "Blood Issuances"; break;
		case "RADIOLOGY": $pageHeader = "Radiology Issuances"; break;
		case "CSR": $pageHeader = "CSR Issuances"; break;
		case "IP": $pageHeader = "Medicines & Supplies Issuances"; break;
		case "OP": $pageHeader = "Medicines & Supplies Issuances"; break;
	}
	
	$page->toolbar->setaction("navigation",false);	
	
	
?>