<?php
 	$enumdocstatus["P"] = "Passed";
 	$enumdocstatus["F"] = "Failed";
 	$enumdocstatus["FA"] = "For Approval";
 	$enumdocstatus["A"] = "Approved";
 	$enumdocstatus["D"] = "Disapproved";

 	$deleteoption = false;
 	if (isEditMode()) {
		if ($page->getitemstring("docstatus")!="C") {
			$canceloption = true;
		}
		
		switch ($page->getitemstring("docstatus")) {
			case "O":
				$page->toolbar->addbutton("passed","Passed","passedGPSFireSafety()","left");
				$page->toolbar->addbutton("failed","Failed","failedGPSFireSafety()","left");
				break;
			case "P":
				$page->toolbar->addbutton("forApproval","Recommend for Approval","recommendforApprovalGPSFireSafety()","left");
				$page->toolbar->addbutton("disapproved","Disapproved","recommenddisapprovedGPSFireSafety()","left");
				break;
			case "FA":
				$page->toolbar->addbutton("approved","Approved","approvedGPSFireSafety()","left");
				$page->toolbar->addbutton("disapproved","Disapproved","disapprovedGPSFireSafety()","left");
				break;
		}			
			

	}

	
?> 

