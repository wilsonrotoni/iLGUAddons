<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_amount",false);
		
		$objGrids[0]->dataentry = false;
		$objGrids[1]->dataentry = false;
		$page->toolbar->setaction("update",false);
		$page->setvar("formAccess","R");
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	//$deleteoption=true;
	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";
?>