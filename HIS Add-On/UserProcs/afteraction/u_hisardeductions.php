<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_custgroup",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		if ($page->getitemstring("docstatus")!="D") {
			$page->businessobject->items->seteditable("u_glacctno",false);
			$page->businessobject->items->seteditable("u_glacctname",false);
			$page->businessobject->items->seteditable("u_preparedby",false);
			$objGrids[0]->columninput("u_deduction","type","label");
		}
	}
	
	
?>