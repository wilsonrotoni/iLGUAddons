<?php
 
                if ($page->getitemstring("u_kind")=="M") {
                    $objGrids[0]->columninput("u_assvalue","type","text");
                }

	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_pin",false);
                
		$objGrids[0]->columninput("u_qtrfr2","type","text");
		$objGrids[0]->columninput("u_qtrto2","type","text");
		$objGrids[0]->columninput("u_yrfr2","type","text");
		$objGrids[0]->columninput("u_yrto2","type","text");
		$objGrids[0]->columninput("u_yrpaid2","type","text");
		$page->toolbar->setaction("update",false);
	}
	
?>