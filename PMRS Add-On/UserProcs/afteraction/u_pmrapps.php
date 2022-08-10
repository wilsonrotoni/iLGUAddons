<?php

	$objRs->queryopen("select A.U_PMRENCODER, A.U_PMRASSESSOR, A.U_PMRAPPROVER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["pmrencoder"] = $objRs->fields["U_PMRENCODER"];
		$page->privatedata["pmrassessor"] = $objRs->fields["U_PMRASSESSOR"];
		$page->privatedata["pmrapprover"] = $objRs->fields["U_PMRAPPROVER"];
	}
        
	$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");

	if (isEditMode()) {	
		if ($page->getitemstring("docstatus")=="Encoding") {
			$page->toolbar->addbutton("forassessment","For Assessment","u_forAssessmentGPSPMRS()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessing") { 
			$page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSPMRS()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
			$page->toolbar->addbutton("approve","Approve","u_approveGPSPMRS()","left");
			$page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSPMRS()","left");
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$objGrids[0]->dataentry = false;
			$objGrids[4]->dataentry = false;
                        if ($page->privatedata["pmrapprover"]=="1") {
				$page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSPMR()","left");
				
			}
		}
	}

	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select A.U_PMRRENTALFEE, B.NAME AS U_PMRRENTALFEEDESC, A.U_PMRRIGHTSFEE, C.NAME AS U_PMRRIGHTSFEEDESC from U_LGUSETUP A 
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_PMRRENTALFEE
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_PMRRIGHTSFEE
							");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["rentalfeecode"] = $objRs->fields["U_PMRRENTALFEE"];
		$page->privatedata["rentalfeedesc"] = $objRs->fields["U_PMRRENTALFEEDESC"];
		$page->privatedata["rightsfeecode"] = $objRs->fields["U_PMRRIGHTSFEE"];
		$page->privatedata["rightsfeedesc"] = $objRs->fields["U_PMRRIGHTSFEEDESC"];
	}
	
	//$page->toolbar->setaction("print",false);
	
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

