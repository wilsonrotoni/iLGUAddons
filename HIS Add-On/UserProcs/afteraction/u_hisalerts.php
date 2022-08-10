<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_doctime",false);
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_alerttype",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->toolbar->setaction("update",false);
	}
?>