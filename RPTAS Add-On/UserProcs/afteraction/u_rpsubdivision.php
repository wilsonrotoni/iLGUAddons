<?php

	if (isEditMode()) {
		if ($page->getitemstring("docstatus")!="D") {
                    
                    if($page->getitemstring("docstatus") == "O"){
                            $page->businessobject->items->seteditable("u_pin",false);
                            $page->businessobject->items->seteditable("u_tdno",false);
//                            $objGrids[0]->columninput("u_ownername","type","text");
                            $objGrids[0]->columninput("u_pin","type","text");
//                            $objGrids[0]->columninput("u_ownertin","type","text");
                            $page->toolbar->setaction("update",true);
                            $page->toolbar->addbutton("u_subdivide","Subdivide","u_subdivideGPSRPTAS()","left");
                            $page->toolbar->setaction("find",false);
                            $page->toolbar->setaction("navigation",false);
//                            $page->toolbar->setaction("update",T);
                        }else{
                            $page->businessobject->items->seteditable("u_pin",false);
                            $page->businessobject->items->seteditable("u_tdno",false);
                            $page->businessobject->items->seteditable("u_subdno",false);
                             $page->toolbar->setaction("update",false); 
                        }
                        
		} 		
	} 
	
	//$deleteoption = false;
?>