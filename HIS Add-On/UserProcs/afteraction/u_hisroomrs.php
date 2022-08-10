<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_roomno",false);
		$page->businessobject->items->seteditable("u_bedno",false);
		if ($page->getitemstring("docstatus")!="O") {
			$page->businessobject->items->seteditable("u_patientid",false);
			$page->businessobject->items->seteditable("u_lastname",false);
			$page->businessobject->items->seteditable("u_firstname",false);
			$page->businessobject->items->seteditable("u_middlename",false);
			$page->businessobject->items->seteditable("u_extname",false);
			$page->businessobject->items->seteditable("u_hours",false);
			$page->businessobject->items->seteditable("u_remarks",false);
		} else {
			$canceloption=true;
		}
	} else {
	}
?>