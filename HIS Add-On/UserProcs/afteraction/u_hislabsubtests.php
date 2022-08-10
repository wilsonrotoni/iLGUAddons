<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("name",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_starttime",false);
		$objGrids[1]->addbutton("delete","[Delete]","deleteAttachmentGPSHIS()","right");
		$page->toolbar->addbutton("sltemplate","Notes Template","u_sltemplateGPSHIS()","left");
	} else {
		$objGrids[1]->setaction("add",false);
	}
?>