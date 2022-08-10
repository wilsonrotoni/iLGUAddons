<?php
	
	if (isEditMode()) {
		if ($page->getitemstring("docstatus")=="C") {
			$page->businessobject->items->seteditable("u_meal",false);
			$page->businessobject->items->seteditable("u_diettype",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			$page->toolbar->setaction("update",false);
		}	
	}
	
	$page->toolbar->setaction("navigation",false);
	$page->toolbar->setaction("find",false);
	$page->toolbar->setaction("new",false);

?>