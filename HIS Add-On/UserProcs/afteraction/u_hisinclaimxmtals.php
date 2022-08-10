<?php
	
	if ($page->getitemstring("u_package")=="1") {
		$objGrids[0]->columnvisibility("u_room",false);
		$objGrids[0]->columnvisibility("u_med",false);
		$objGrids[0]->columnvisibility("u_lab",false);
		$objGrids[0]->columnvisibility("u_or",false);
		$objGrids[0]->columnvisibility("u_pf",false);
		$page->businessobject->items->setvisible("u_totalhfamount",false);
		$page->businessobject->items->setvisible("u_totalpfamount",false);
	}
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_inscode",false);
		$page->businessobject->items->seteditable("u_package",false);
		$page->businessobject->items->seteditable("u_membergroup",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_enddate",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_preparedby",false);
		$page->businessobject->items->seteditable("u_reftype",false);
		$objGrids[0]->columnvisibility("u_selected",false);
		if ($page->getitemstring("docstatus")!="CN") {
			$canceloption = true;
		} else $page->toolbar->setaction("update",false);
	} else {
	    if ($page->getitemstring("u_inscode")=="") {
		     $objGrids[0]->columnvisibility("u_selected",false);
		} else {
		     $objGrids[0]->columnvisibility("u_inscode",false);
		}
	}
?>