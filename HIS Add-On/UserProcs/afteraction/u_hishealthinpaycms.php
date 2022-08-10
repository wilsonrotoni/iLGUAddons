<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_custgroup",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		if ($page->getitemstring("docstatus")!="CN" && $page->getitemstring("docstatus")!="D") {
			$canceloption=true;
		} 
		if ($page->getitemstring("docstatus")!="D") {
			$page->businessobject->items->seteditable("u_inscode",false);
			$page->businessobject->items->seteditable("u_preparedby",false);
			$objGrids[0]->columninput("u_discperc","type","label");
			$objGrids[0]->columninput("u_wtaxperc","type","label");
			$objGrids[0]->columninput("u_applied","type","label");
			$objGrids[0]->dataentry = false;
		}
	}
	
?>