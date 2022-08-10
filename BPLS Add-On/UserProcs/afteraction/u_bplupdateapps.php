<?php

	$objRs->queryopen("select A.U_BPLENCODER, A.U_BPLASSESSOR, A.U_BPLAPPROVER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["bplencoder"] = $objRs->fields["U_BPLENCODER"];
		$page->privatedata["bplassessor"] = $objRs->fields["U_BPLASSESSOR"];
		$page->privatedata["bplapprover"] = $objRs->fields["U_BPLAPPROVER"];
	}
	
//	if ($page->privatedata["bplencoder"]=="1" || $page->privatedata["bplassessor"]=="1" || $page->privatedata["bplapprover"]=="1") {
//		$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
//	}

	
	
	if (isEditMode()) {	
		
	
		
		if ($page->getitemstring("docstatus")=="Encoding") {
			$page->toolbar->addbutton("generatebill","Generate Bill","u_generatebillGPSBPLS()","left");
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$objGrids[0]->dataentry = false;
			$objGrids[1]->dataentry = false;
			
				$page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSBPLS()","left");
//				$page->businessobject->items->seteditable("u_approverremarks",true);
			
                        
		}
               
		
		
	}

	

	$objMaster->reportaction = "QS";
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

