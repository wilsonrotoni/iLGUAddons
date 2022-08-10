<?php


        

	$objGrids[0]->columninput("u_selected","type","checkbox");
	$objGrids[0]->columninput("u_selected","value",1);
        

     
	if (isEditMode()) {
            if ($page->getitemstring("docstatus")!="D") {
                $page->businessobject->items->seteditable("u_date",false);
                $page->businessobject->items->seteditable("u_tin",false);
                $page->businessobject->items->seteditable("u_declaredowner",false);
                $page->businessobject->items->seteditable("u_yearfr",false);
                $page->businessobject->items->seteditable("u_yearto",false);
    //			$page->businessobject->items->seteditable("u_remarks",false);
                $objGrids[0]->dataentry = false;
    //			$objGrids[0]->columnattributes("u_selected","disabled");
    //                        $page->toolbar->setaction("update",false);
                $deleteoption = false;
                $movetodraft = true;
            }
	} 
	
?>