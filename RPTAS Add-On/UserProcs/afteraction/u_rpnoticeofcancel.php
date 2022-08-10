<?php


	if (isEditMode()) {
                      
                       if ($page->getitemstring("docstatus") != "C") {
                            $page->toolbar->addbutton("cancel","Cancel TD","CancelFAASGPSRPTAS()","left");
                       } else {
                            $page->businessobject->items->seteditable("u_date",false);
                            $page->businessobject->items->seteditable("u_tdno",false);
                            $page->businessobject->items->seteditable("u_datecancel",false);
                            $page->businessobject->items->seteditable("u_endyear",false);
                            $page->businessobject->items->seteditable("u_reason",false);
                            $page->businessobject->items->seteditable("u_owneraddress",false);
    //			$page->businessobject->items->seteditable("u_tin",false);
    //			$page->businessobject->items->seteditable("u_declaredowner",false);
    //			$page->businessobject->items->seteditable("u_year",false);
    //			$page->businessobject->items->seteditable("u_remarks",false);
    //                        $objGrids[0]->dataentry = false;
    //			$objGrids[0]->columnattributes("u_selected","disabled");
                            $page->toolbar->setaction("update",false);
                            $deleteoption = false;
                            
                       }
                        
                        
 		
	} 
	//$deleteoption = false;
?>