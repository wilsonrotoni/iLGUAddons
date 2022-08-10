<?php
 	$enumdocstatus["FI"] = "For Inspection";
 	$enumdocstatus["AP"] = "Approved";
 	$enumdocstatus["DA"] = "Disapproved";
 	$enumdocstatus["RT"] = "Retired";
        
	$page->toolbar->addbutton("zoningledger","PAYMENT HISTORY","u_PaymentHistoryGPSGLGUZoning()","left");
 	if (isEditMode()) {
            
                if ($page->getitemstring("docstatus") != "D") {
                    $page->businessobject->items->seteditable("u_docdate",false);
                    $page->businessobject->items->seteditable("u_apptype",false);
                    $page->businessobject->items->seteditable("u_bpno",false);
                    $page->businessobject->items->seteditable("u_businessname",false);
                    $page->businessobject->items->seteditable("u_orno",false);
                    $page->businessobject->items->seteditable("u_ordate",false);
                    $page->businessobject->items->seteditable("u_orsfamt",false);
                    $page->businessobject->items->seteditable("u_appnature",false);
                    $page->businessobject->items->seteditable("u_appnatureothers",false);
                    $page->businessobject->items->seteditable("u_isonlineapp",false);
                    $objGrids[0]->dataentry = false;
//                    $page->toolbar->setaction("update",false);
                    if (($page->getitemstring("docstatus")=="AP" || $page->getitemstring("docstatus")=="DA" ) && $page->getitemstring("u_bplappno") == ""  ) { 
//                        $canceloption = true;
			$movetodraft = true;
                    }
                } else {
                    $page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSLGUZoning()","left");
                    $page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSLGUZoning()","left");
                }
	}
        
         
	

	$objMaster->reportaction = "QS";
	
?> 

