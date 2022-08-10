<?php

	
	$objRs->queryopen("select A.U_PAYMENTVALIDATOR from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["paymentvalidator"] = $objRs->fields["U_PAYMENTVALIDATOR"];
		
	}
       
	$objGrids[0]->columninput("u_selected","type","checkbox");
	$objGrids[0]->columninput("u_selected","value",1);
        
	if (isEditMode()) {
                
                if ($page->getitemstring("docstatus") == "Pending" && $page->privatedata["paymentvalidator"] = 1) {
                    $page->toolbar->addbutton("approve","Approve","u_approveGPSRPTAS()","left");
                    $page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSRPTAS()","left");
                } else {
                    $objGrids[0]->columnattributes("u_selected","disabled");
                    $page->businessobject->items->seteditable("u_approverremarks",false);
                }
	}else{
                if ($page->privatedata["paymentvalidator"] == 1) {
                    $page->toolbar->addbutton("approve","Approve","u_approveGPSRPTAS()","left");
                    $page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSRPTAS()","left");
                    $page->toolbar->addbutton("pending","Pending","u_pendingGPSRPTAS()","left");
                    $page->businessobject->items->seteditable("u_approverremarks",true);
                }
               
        }
        

	$objMaster->reportaction = "QS";
        
	
?> 

