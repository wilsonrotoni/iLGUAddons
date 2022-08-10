<?php

$deleteoption = false;
if (isEditMode()) {
	$page->businessobject->items->seteditable("u_yr",false);
	$page->businessobject->items->seteditable("u_appcode",false);
	
	if (($page->getitemstring("docstatus")=="O")) {
		$movetodraft = true;
		$objGrids[0]->dataentry = false;
	} elseif (($page->getitemstring("docstatus")=="D")) {
		$deleteoption = true;
		//$updatedraft = true;
		$addoptions = true;
	}
	
}



?>