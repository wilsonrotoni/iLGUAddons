<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->toolbar->setaction("update",false);
	} else {
	}
?>