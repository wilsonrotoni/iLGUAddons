<?php

	$objRs->queryopen("select A.U_BPLENCODER,A.U_FIREDEPT, A.U_BPLASSESSOR, A.U_BPLAPPROVER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["bplencoder"] = $objRs->fields["U_BPLENCODER"];
		$page->privatedata["bplassessor"] = $objRs->fields["U_BPLASSESSOR"];
		$page->privatedata["bplapprover"] = $objRs->fields["U_BPLAPPROVER"];
		$page->privatedata["firedept"] = $objRs->fields["U_FIREDEPT"];
	}
    
                if ($page->privatedata["firedept"]=="1" || $page->privatedata["fireapprover"]=="1") {
                        $page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
                }   
    

	$photopath ="";
	if (isEditMode()) {	
		
		
		if ($page->getitemstring("docstatus")=="Encoding" ) {
			if ($page->privatedata["firedept"]=="1" || $page->privatedata["fireapprover"]=="1") $page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSFiresafety()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
			if ($page->privatedata["fireapprover"]=="1") {
				$page->toolbar->addbutton("approve","Approve","u_approveGPSFiresafety()","left");
				$page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSFiresafety()","left");
				$page->businessobject->items->seteditable("u_remarks",true);
			}	
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$objGrids[0]->dataentry = false;
			if ($page->privatedata["fireapprover"]=="1") {
				
				$page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSFiresafety()","left");
				$page->businessobject->items->seteditable("u_approverremarks",true);
			}
                        
                }
                
                
                
		
      
	
	}


	
	
	$objMaster->reportaction = "QS";
//        if($page->getitemstring("u_retired") == "1" ){
//            $pageHeaderStatusText = "Status: Retired";
//        }else{
        $pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
//        }
//	
//?> 

