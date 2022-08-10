<?php
 	$enumdocstatus["P"] = "Passed";
 	$enumdocstatus["F"] = "Failed";
 	$enumdocstatus["FA"] = "For Approval";
 	$enumdocstatus["A"] = "Approved";
 	$enumdocstatus["D"] = "Disapproved";

 	$deleteoption = false;
 	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_appno",false);
		$page->businessobject->items->seteditable("u_businessname",false);
		if ($page->getitemstring("docstatus")=="O") {
			$canceloption = true;
		} else {
			$page->businessobject->items->seteditable("u_inspectdate",false);
			$page->businessobject->items->seteditable("u_owner",false);
			$page->businessobject->items->seteditable("u_manager",false);
			$page->businessobject->items->seteditable("u_address",false);
			$page->businessobject->items->seteditable("u_category",false);
			$page->businessobject->items->seteditable("u_inspector",false);
			$page->businessobject->items->seteditable("u_noofpersonnel",false);
			$page->businessobject->items->seteditable("u_nowithhealthcert",false);
			$page->businessobject->items->seteditable("u_oth1",false);
			$page->businessobject->items->seteditable("u_oth2",false);
			$page->businessobject->items->seteditable("u_oth3",false);
			$page->businessobject->items->seteditable("u_cop",false);
			$page->businessobject->items->seteditable("u_mop",false);
			$page->businessobject->items->seteditable("u_tp",false);
			$page->businessobject->items->seteditable("u_hf",false);
			$page->businessobject->items->seteditable("u_ws",false);
			$page->businessobject->items->seteditable("u_lwm",false);
			$page->businessobject->items->seteditable("u_swm",false);
			$page->businessobject->items->seteditable("u_wof",false);
			$page->businessobject->items->seteditable("u_pof",false);
			$page->businessobject->items->seteditable("u_vc",false);
			$page->businessobject->items->seteditable("u_cnt",false);
			$page->businessobject->items->seteditable("u_pc",false);
			$page->businessobject->items->seteditable("u_ham",false);
			$page->businessobject->items->seteditable("u_coau",false);
			$page->businessobject->items->seteditable("u_scoau",false);
			$page->businessobject->items->seteditable("u_dc",false);
			$page->businessobject->items->seteditable("u_mic",false);
			$page->businessobject->items->seteditable("u_oth1rcm",false);
			$page->businessobject->items->seteditable("u_oth2rcm",false);
			$page->businessobject->items->seteditable("u_oth3rcm",false);
			$page->businessobject->items->seteditable("u_coprcm",false);
			$page->businessobject->items->seteditable("u_moprcm",false);
			$page->businessobject->items->seteditable("u_tprcm",false);
			$page->businessobject->items->seteditable("u_hfrcm",false);
			$page->businessobject->items->seteditable("u_wsrcm",false);
			$page->businessobject->items->seteditable("u_lwmrcm",false);
			$page->businessobject->items->seteditable("u_swmrcm",false);
			$page->businessobject->items->seteditable("u_wofrcm",false);
			$page->businessobject->items->seteditable("u_pofrcm",false);
			$page->businessobject->items->seteditable("u_vcrcm",false);
			$page->businessobject->items->seteditable("u_cntrcm",false);
			$page->businessobject->items->seteditable("u_pcrcm",false);
			$page->businessobject->items->seteditable("u_hamrcm",false);
			$page->businessobject->items->seteditable("u_coaurcm",false);
			$page->businessobject->items->seteditable("u_scoaurcm",false);
			$page->businessobject->items->seteditable("u_dcrcm",false);
			$page->businessobject->items->seteditable("u_micrcm",false);
		}
		/*
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
			*/

	}

	
?> 

