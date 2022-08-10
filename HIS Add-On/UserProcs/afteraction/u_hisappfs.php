<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_docdate",false);
		if ($page->getitemstring("docstatus")!="D") {
			$page->businessobject->items->seteditable("u_gltype",false);
			$page->businessobject->items->seteditable("u_glacctno",false);
			$page->businessobject->items->seteditable("u_glacctname",false);
			$page->businessobject->items->seteditable("u_bank",false);
			$page->businessobject->items->seteditable("u_bankacctno",false);
			$page->businessobject->items->seteditable("u_preparedby",false);
			$objGrids[0]->columnvisibility("u_selected",false);
		}
	}
	
	if ($page->getitemstring("u_gltype")=="A") {
		$page->businessobject->items->seteditable("u_bank",false);
		$page->businessobject->items->seteditable("u_bankacctno",false);
	} else {
		$page->businessobject->items->seteditable("u_glacctno",false);
		$page->businessobject->items->seteditable("u_glacctname",false);
	}
	
	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";
	
?>