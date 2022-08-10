<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestdate",false);
		$page->businessobject->items->seteditable("u_requesttime",false);
		if ($page->getitemstring("docstatus")=="O" && $page->getitemstring("u_rendered")=="0") {
			$canceloption = true;
		} else if ($page->getitemstring("u_rendered")=="1") {	
			$page->businessobject->items->seteditable("u_type",false);
			$page->businessobject->items->seteditable("u_department",false);
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_requestdepartment",false);
			$page->businessobject->items->seteditable("u_requestby",false);
			$objGrids[0]->dataentry = false;
			$page->toolbar->setaction("update",false);
		}
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_doctorid",false);
		$page->businessobject->items->seteditable("u_reqdate",false);
		$page->businessobject->items->seteditable("u_reqtime",false);
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	if ($page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") {
		$page->businessobject->items->seteditable("u_department",false);
	}
	
	$objGrids[0]->columndataentry("u_type","options",array("loadu_hislabtesttypesbysection",$page->getitemstring("u_department"),":"));
	
	$page->toolbar->setaction("navigation",false);
		
	switch ($page->getitemstring("u_trxtype")) {
		case "LABORATORY": $pageHeader = "Laboratories Request - Cash Sales"; break;
		case "RADIOLOGY": $pageHeader = "X-Ray/CT-Scan/Ultrasounds Requests - Cash Sales"; break;
		case "HEARTSTATION": $pageHeader = "Heart Stations Requests - Cash Sales"; break;
	}		

?>