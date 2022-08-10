<?php
 
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_cashierid",false);
		if ($page->getitemstring("u_mode")=="0" && $page->getitemstring("u_status")!="C") {
			$page->toolbar->addbutton("u_closeregister","Close Register","u_closeRegisterGPSPOSAsddon()","right");
			$objGrids[1]->columninput("u_count","type","text");
		} else {
			$page->businessobject->items->seteditable("u_opendate",false);
			$page->businessobject->items->seteditable("u_opentime",false);
			$page->businessobject->items->seteditable("u_closedate",false);
			$page->businessobject->items->seteditable("u_closetime",false);
			$objGrids[7]->dataentry = false;
			$objGrids[8]->dataentry = false;
		}	
	} else {
		if ($page->getitemstring("u_mode")=="0") {
			$page->businessobject->items->seteditable("u_closedate",false);
			$page->businessobject->items->seteditable("u_closetime",false);
			$page->toolbar->addbutton("u_closeregister","Open Register","u_openRegisterGPSPOSAsddon()","right");
		} else {
			$page->businessobject->items->seteditable("u_openamount",true);
			$page->toolbar->setaction("add",true);
			$objGrids[1]->columninput("u_count","type","text");
		}	
	
		$objGrids[0]->columninput("u_count","type","text");
	}

	if ($page->getitemstring("u_mode")=="1") {
		$page->businessobject->items->setvisible("u_terminalid",false);
		$page->businessobject->items->setvisible("u_cashierid",false);
		$page->businessobject->items->setvisible("u_status",false);
	}
?> 

