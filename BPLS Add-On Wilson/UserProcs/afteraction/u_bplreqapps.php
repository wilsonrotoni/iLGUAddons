<?php

	$objRs->queryopen("select A.U_ENGIDEPT,A.U_PLANDEPT,A.U_RHUDEPT,A.U_FIREDEPT from USERS A WHERE USERID='".$_SESSION["userid"]."'");

        if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["engidept"] = $objRs->fields["U_ENGIDEPT"];
		$page->privatedata["plandept"] = $objRs->fields["U_PLANDEPT"];
		$page->privatedata["rhudept"] = $objRs->fields["U_RHUDEPT"];
	}
        
        $objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select   A.U_SANITARYINSPECTIONFEE, E.NAME AS U_SANITARYINSPECTIONFEEDESC,
                                    A.U_BUILDINGPERMITFEECODE, B.NAME AS U_BUILDINGPERMITFEEDESC,
                                    A.U_MECHANICALFEECODE, C.NAME AS U_MECHANICALFEEDESC,
                                    A.U_PLUMBINGFEECODE, D.NAME AS U_PLUMBINGFEEDESC,
                                    A.U_ELECTRICALFEECODE, G.NAME AS U_ELECTRICALFEEDESC,
                                    A.U_SIGNAGEFEECODE, F.NAME AS U_SIGNAGEFEEDESC
                             from U_LGUSETUP A
                             LEFT JOIN U_LGUFEES B ON B.CODE=A.U_BUILDINGPERMITFEECODE
                             LEFT JOIN U_LGUFEES C ON C.CODE=A.U_MECHANICALFEECODE
                             LEFT JOIN U_LGUFEES D ON D.CODE=A.U_PLUMBINGFEECODE
                             LEFT JOIN U_LGUFEES E ON E.CODE=A.U_SANITARYINSPECTIONFEE
                             LEFT JOIN U_LGUFEES F ON F.CODE=A.U_SIGNAGEFEECODE
                             LEFT JOIN U_LGUFEES G ON G.CODE=A.U_ELECTRICALFEECODE                                                    
							");
        
         if ($objRs->queryfetchrow("NAME")) {
		
		$page->privatedata["sanitarypermitcode"] = $objRs->fields["U_SANITARYINSPECTIONFEE"];
		$page->privatedata["sanitarypermitdesc"] = $objRs->fields["U_SANITARYINSPECTIONFEEDESC"];
		$page->privatedata["buildingfeecode"] = $objRs->fields["U_BUILDINGPERMITFEECODE"];
		$page->privatedata["buildingfeedesc"] = $objRs->fields["U_BUILDINGPERMITFEEDESC"];
		$page->privatedata["mechanicalfeecode"] = $objRs->fields["U_MECHANICALFEECODE"];
		$page->privatedata["mechanicalfeedesc"] = $objRs->fields["U_MECHANICALFEEDESC"];
		$page->privatedata["plumbingfeecode"] = $objRs->fields["U_PLUMBINGFEECODE"];
		$page->privatedata["plumbingfeedesc"] = $objRs->fields["U_PLUMBINGFEEDESC"];
		$page->privatedata["electricalfeecode"] = $objRs->fields["U_ELECTRICALFEECODE"];
		$page->privatedata["electricalfeedesc"] = $objRs->fields["U_ELECTRICALFEEDESC"];
		$page->privatedata["signagefeecode"] = $objRs->fields["U_SIGNAGEFEECODE"];
		$page->privatedata["signagefeedesc"] = $objRs->fields["U_SIGNAGEFEEDESC"];
		
	}
         $page->toolbar->addbutton("u_submit","F4 - Save","formSubmit()","left");
         if (isEditMode()) {
            if($page->privatedata["engidept"] == "1" && $page->getitemstring("docstatus")){
            $page->toolbar->addbutton("forapprovalbuilding","Building Approve","u_ApprovedBuildingGPSBPLS()","left");
            }
            if($page->privatedata["plandept"] == "1" && $page->getitemstring("docstatus")){
               $page->toolbar->addbutton("forapprovalzoning","Zoning Approve","u_ApprovedZoningGPSBPLS()","left");
            }
            if($page->privatedata["rhudept"] == "1" && $page->getitemstring("docstatus")){
               $page->toolbar->addbutton("forapprovalcho","CHO Approve","u_ApprovedCHOGPSBPLS()","left");
            }

         }
        
	$objMaster->reportaction = "QS";
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

