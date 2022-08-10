<?php
 	$enumdocstatus["P"] = "Passed";
 	$enumdocstatus["F"] = "Failed";
 	$enumdocstatus["FA"] = "For Approval";
 	$enumdocstatus["A"] = "Approved";
 	$enumdocstatus["D"] = "Disapproved";

 	$deleteoption = false;
 	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_issdate",false);
		$page->businessobject->items->seteditable("u_appno",false);
		
		if ($page->getitemstring("docstatus")!="C") {
			$canceloption = true;
		}
		
		switch ($page->getitemstring("docstatus")) {
			case "O":
				$page->toolbar->addbutton("passed","Passed","passedGPSLGUBuilding()","left");
				$page->toolbar->addbutton("failed","Failed","failedGPSLGUBuilding()","left");
				break;
			case "P":
				$page->toolbar->addbutton("forApproval","Recommend for Approval","recommendforApprovalGPSLGUBuilding()","left");
				$page->toolbar->addbutton("disapproved","Disapproved","recommenddisapprovedGPSLGUBuilding()","left");
				break;
			case "FA":
				$page->toolbar->addbutton("approved","Approved","approvedGPSLGUBuilding()","left");
				$page->toolbar->addbutton("disapproved","Disapproved","disapprovedGPSLGUBuilding()","left");
				break;
		}			
			

	}

	
?> 

