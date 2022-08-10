<?php
 	$enumdocstatus["FI"] = "For Inspection";
 	$enumdocstatus["AP"] = "Approved";
 	$enumdocstatus["DA"] = "Disapproved";
 	$enumdocstatus["RT"] = "Retired";
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
        $page->toolbar->addbutton("bldgledger","PAYMENT HISTORY","u_PaymentHistoryGPSGLGUBuilding()","left");
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
                    $page->businessobject->items->seteditable("u_zoningappno",false);
                    $page->businessobject->items->seteditable("u_isonlineapp",false);
                    $objGrids[0]->dataentry = false;
                    $objGrids[1]->columninput("u_quantity","type","label");
                    $objGrids[2]->columninput("u_quantity","type","label");
                    $objGrids[3]->columninput("u_quantity","type","label");
                    $objGrids[4]->columninput("u_quantity","type","label");
                    $objGrids[5]->columninput("u_quantity","type","label");
//                    $page->toolbar->setaction("update",false);
                    $deleteoption = false;
                    if (($page->getitemstring("docstatus")=="AP" || $page->getitemstring("docstatus")=="DA" ) && $page->getitemstring("u_bplappno") == ""  ) { 
//                        $canceloption = true;
			$movetodraft = true;
                    }
                }  else {
                    $page->businessobject->items->seteditable("docno",true);
                    $page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSLGUBuilding()","left");
                    $page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSLGUBuilding()","left");
                }
	}
        
?> 

