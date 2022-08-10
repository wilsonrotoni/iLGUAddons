<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestdate",false);
		$page->businessobject->items->seteditable("u_requesttime",false);
		$page->businessobject->items->seteditable("u_requestdepartment",false);
		$page->businessobject->items->seteditable("u_department",false);
		if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("docstatus")=="CN" || $page->getitemstring("u_payrefno")!="") {
			$page->businessobject->items->seteditable("u_isstat",false);
			$page->businessobject->items->seteditable("u_pricelist",false);
			$page->businessobject->items->seteditable("u_disccode",false);
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_requestby",false);
			
			$objGrids[0]->columninput("u_isstat","type","label");
			$objGrids[0]->columninput("u_quantity","type","label");
			$objGrids[0]->columninput("u_remarks","type","label");		
			
			$objGrids[0]->dataentry = false;
			$objGrids[2]->dataentry = false;
			
			if (floatval($page->getitemstring("u_otheramount"))==0) $canceloption = false; 
			
			if ($page->getitemstring("u_packageno")!="") {
				$page->businessobject->items->seteditable("u_duedate",false);
				$page->businessobject->items->seteditable("u_duetime",false);
				$page->toolbar->setaction("update",false);
			}
		}	

		if ($partialserve) {
			$page->businessobject->items->seteditable("u_isstat",false);
			$page->businessobject->items->seteditable("u_pricelist",false);
			$page->businessobject->items->seteditable("u_disccode",false);
			$page->businessobject->items->seteditable("u_department",false);
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_requestdepartment",false);
			$page->businessobject->items->seteditable("u_requestby",false);
			$objGrids[0]->columninput("u_isstat","type","label");
			$objGrids[0]->columninput("u_quantity","type","label");
			$objGrids[0]->columninput("u_remarks","type","label");
			$objGrids[0]->dataentry = false;
			$objGrids[2]->dataentry = false;
			
			$canceloption = false;
			if ($page->getitemstring("u_payrefno")=="") $closeoption = true;
			if (!$canceloption && !$closeoption) $page->toolbar->setaction("update",false);
		}
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_doctorid",false);
		$page->businessobject->items->seteditable("u_reqdate",false);
		$page->businessobject->items->seteditable("u_reqtime",false);
		
		if ($page->getitemstring("docstatus")=="CN") {
			$page->toolbar->setaction("update",false);
		}
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
		
		if ($canceloption && $page->getitemstring("u_prepaid")=="0") $page->businessobject->items->seteditable("u_prepaid",true);
	} else {
		$page->toolbar->addbutton("templates","Templates","u_templatesGPSHIS()","left");
		$page->toolbar->addbutton("packages","Packages","u_packagesGPSHIS()","left");
	}
	
	if ($page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") {
		$page->businessobject->items->seteditable("u_department",false);
	}
	
	$objGrids[0]->columndataentry("u_type","options",array("loadu_hislabtesttypesbysection",$page->getitemstring("u_department"),":"));
	
	$page->toolbar->setaction("navigation",false);
		
	switch ($page->getitemstring("u_trxtype")) {
		case "LABORATORY": $pageHeader = "Laboratory - Request for Cash Sales"; break;
		case "RADIOLOGY": $pageHeader = "Radiology - Requests for Cash Sales"; break;
		case "HEARTSTATION": $pageHeader = "Heart Station - Requests for Cash Sales"; break;
		default: $pageHeader = "Requests"; break;
	}		

	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";
?>