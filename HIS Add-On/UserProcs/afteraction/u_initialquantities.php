<?php
 
 	/*if ($page->getitemstring("u_warehouse")=="0") {
		$objGrids[0]->columninput("u_actqtywh","type","label");
	}
 	if ($page->getitemstring("u_sellingarea")=="0") {
		$objGrids[0]->columninput("u_actqtysa","type","label");
	}*/
	
 	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_ob",false);
		$page->businessobject->items->seteditable("u_showall",false);
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_itemgroup",false);
		$page->businessobject->items->seteditable("u_itemclass",false);
		if ($page->getitemstring("docstatus")=="O") {
			$objGrids[0]->columninput("u_actqtyiu","disabled","{u_actuompu}=={u_actuomiu}");
			$page->toolbar->addbutton("post","Post","postGPSHIS()","left");
			$canceloption = true;
		} else {
			$page->businessobject->items->seteditable("u_date",false);
			$page->businessobject->items->seteditable("u_warehouse",false);
			$page->businessobject->items->seteditable("u_sellingarea",false);
			$page->businessobject->items->seteditable("u_terminal",false);

			$objGrids[0]->columninput("u_actqtypu","type","label");
			$objGrids[0]->columninput("u_actqtyiu","type","label");
			$objGrids[0]->columninput("u_itemcost","type","label");
			//$objGrids[0]->columninput("u_glacctno","type","label");
				
		}	
	}

	
?> 

