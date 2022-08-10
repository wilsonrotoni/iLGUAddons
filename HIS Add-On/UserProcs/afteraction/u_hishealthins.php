<?php
	
	if (isEditMode()) {
		$objGrid->setaction("add",true);
		
		$page->businessobject->items->seteditable("u_series",false);
		$page->businessobject->items->seteditable("u_hmo",false);
		$page->businessobject->items->seteditable("code",false);
	} else {
		if ($page->getitemstring("u_series")!=-1) {
			$page->businessobject->items->seteditable("code",false);
		}
	}
	
	if ($page->getitemstring("u_hmo")!="2") {
		$page->businessobject->items->seteditable("u_priority",false);
	}
?>