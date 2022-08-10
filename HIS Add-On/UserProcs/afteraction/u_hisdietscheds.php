<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_meal",false);
		$page->businessobject->items->seteditable("u_diettype",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$objGrids[0]->columnvisibility("u_selected",false);
		
	} else {
	}
?>