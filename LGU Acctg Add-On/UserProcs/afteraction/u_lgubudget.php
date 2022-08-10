<?php
 
if (isEditMode()) {
	$page->businessobject->items->seteditable("u_yr",false);
	$page->businessobject->items->seteditable("u_profitcenter",false);
	
	if ($page->getitemstring("u_released")=="1") {
		$deleteoption = false;
		$objGrids[0]->columninput("u_yr","type","label");
		$objGrids[0]->columninput("u_m1","type","label");
		$objGrids[0]->columninput("u_m2","type","label");
		$objGrids[0]->columninput("u_m3","type","label");
		$objGrids[0]->columninput("u_m4","type","label");
		$objGrids[0]->columninput("u_m5","type","label");
		$objGrids[0]->columninput("u_m6","type","label");
		$objGrids[0]->columninput("u_m7","type","label");
		$objGrids[0]->columninput("u_m8","type","label");
		$objGrids[0]->columninput("u_m9","type","label");
		$objGrids[0]->columninput("u_m10","type","label");
		$objGrids[0]->columninput("u_m11","type","label");
		$objGrids[0]->columninput("u_m12","type","label");	
		
		$objGrids[0]->dataentry=false;	
		//if (($page->getitemstring("u_status")=="O")) {
		//	$movetodraft = true;
		//}

		$page->businessobject->items->seteditable("u_preparedby",false);
		$page->businessobject->items->seteditable("u_reviewdby",false);
		$page->businessobject->items->seteditable("u_approvedby",false);
	}	
}

?> 

