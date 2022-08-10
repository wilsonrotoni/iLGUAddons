<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_series",false);
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("code",false);
		//$objGrid->setaction("add",true);
		
		$page->toolbar->addbutton("u_preview","Preview","u_previewGPSHIS()","left");
		
	} else {
		if ($page->getitemstring("u_series")!=-1) {
			$page->businessobject->items->seteditable("code",false);
		}
	}
	
	$objGrids[0]->addbutton("u_duplicate","[Duplicate]","u_duplicateGPSHIS()","left");
	
	$page->toolbar->setaction("print",false);
	
?>