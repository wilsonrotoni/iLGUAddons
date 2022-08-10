<?php


	if (isEditMode()) {
                      
                       if ($page->getitemstring("docstatus") != "D") {
                            $page->businessobject->items->seteditable("u_date",false);
                            $page->businessobject->items->seteditable("u_tdno",false);
                            $page->businessobject->items->seteditable("u_reason",false);
                            $page->businessobject->items->seteditable("u_orno",false);
                            $page->businessobject->items->seteditable("u_docstamporno",false);
    //			$page->businessobject->items->seteditable("u_year",false);
    //			$page->businessobject->items->seteditable("u_remarks",false);
    //                        $objGrids[0]->dataentry = false;
    //			$objGrids[0]->columnattributes("u_selected","disabled");
                            $deleteoption = false;
                            $movetodraft = true;
                       } else {
                            
                            
                       }
                        
                        
 		
	} 
	//$deleteoption = false;
?>