<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_docdate",false);
		
		$objGrids[1]->columninput("u_thisamount","type","label");
		$objGrids[2]->columninput("u_thisamount","type","label");
		$objGrids[3]->columninput("u_thisamount","type","label");
		$objGrids[4]->columninput("u_thisamount","type","label");
		$objGrids[5]->columninput("u_thisamount","type","label");
		
	
		if ($page->getitemstring("docstatus")=="O" && $page->getitemstring("u_xmtalno")=="") {
			$canceloption = true;
		} else $page->toolbar->setaction("update",false);	
		
	} else {
	}
?>