<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_enddate",false);
		$page->businessobject->items->seteditable("u_reftype",false);
	}
	
	
?>