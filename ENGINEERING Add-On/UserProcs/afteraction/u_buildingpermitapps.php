<?php

	$objRs->queryopen("select A.U_BUILDINGENCODER, A.U_BUILDINGASSESSOR, A.U_BUILDINGAPPROVER,A.SUSER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["buildingencoder"] = $objRs->fields["U_BUILDINGENCODER"];
		$page->privatedata["buildingassessor"] = $objRs->fields["U_BUILDINGASSESSOR"];
		$page->privatedata["buildingapprover"] = $objRs->fields["U_BUILDINGAPPROVER"];
		$page->privatedata["suser"] = $objRs->fields["SUSER"];
	}
	$objRs2 = new recordset(null,$objConnection);
        $objRs2->queryopen("SELECT A.U_CTCBASICFEE,B.NAME AS U_CTCBASICNAME,B.U_AMOUNT AS U_CTCBASICAMOUNT,A.U_CTCGROSSFEE,C.NAME AS U_CTCGROSSFEENAME,B.U_PENALTYCODE AS U_CTCPENFEECODE,B.U_PENALTYDESC AS U_CTCPENFEENAME
                                  ,A.U_TRANSFERTAXFEE,D.NAME AS U_TRANSFERTAXFEENAME,D.U_PENALTYCODE AS U_TRANSFERTAXPENFEECODE,D.U_PENALTYDESC AS U_TRANSFERTAXPENFEENAME
                                  ,A.U_TRANSFERTAXINTFEE,E.NAME AS U_TRANSFERTAXINTFEENAME
                                  ,A.U_FRANCHISETAXFEE,G.NAME AS U_FRANCHISETAXFEENAME,G.U_PENALTYCODE AS U_FRANCHISETAXPENFEECODE,G.U_PENALTYDESC AS U_FRANCHISETAXPENFEENAME
                                  ,A.U_FRANCHISETAXINTFEE,H.NAME AS U_FRANCHISETAXINTFEENAME
                                  ,A.U_PROCESSINGFEE,F.NAME AS U_PROCESSINGFEENAME,F.U_AMOUNT AS U_PROCESSINGFEEAMOUNT,
                                  A.U_OCCUPATIONALFEE,I.NAME AS U_OCCUPATIONALNAME,
                                  A.U_DOCUMENTARYFEE, J.NAME AS U_DOCUMENTARYNAME,
                                  A.U_BPLPFOFEE, K.NAME AS U_HEALTHNAME,
								                  A.U_ZONINGLOCATIONALFEE, L.NAME AS U_ZONINGLOCATIONALNAME,
                                  A.U_ACCESSORYFEECODE,M.NAME AS U_ACCESSORYFEENAME,
                                  A.U_BUILDINGPERMITFEECODE,N.NAME AS U_BUILDINGPERMITFEENAME,
                                  A.U_MECHANICALFEECODE,O.NAME AS U_MECHANICALFEENAME,
                                  A.U_LINEGRADEFEECODE,P.NAME AS U_LINEGRADEFEENAME,
                                  A.U_FILINGFEECODE,Q.NAME AS U_FILINGFEENAME,
                                  A.U_PROCESSINGFEECODE,R.NAME AS U_PROCESSINGFEENAME,
                                  A.U_INSPECTIONFEECODE,S.NAME AS U_INSPECTIONFEENAME
                                FROM U_LGUSETUP A
                                LEFT JOIN U_LGUFEES B ON B.CODE=A.U_CTCBASICFEE
                                LEFT JOIN U_LGUFEES C ON C.CODE=A.U_CTCGROSSFEE
                                LEFT JOIN U_LGUFEES D ON D.CODE=A.U_TRANSFERTAXFEE
                                LEFT JOIN U_LGUFEES E ON E.CODE=A.U_TRANSFERTAXINTFEE
                                LEFT JOIN U_LGUFEES F ON F.CODE=A.U_PROCESSINGFEE
                                LEFT JOIN U_LGUFEES G ON G.CODE=A.U_FRANCHISETAXFEE
                                LEFT JOIN U_LGUFEES H ON H.CODE=A.U_FRANCHISETAXINTFEE
                                LEFT JOIN U_LGUFEES I ON I.CODE=A.U_OCCUPATIONALFEE
                                LEFT JOIN U_LGUFEES J ON J.CODE=A.U_DOCUMENTARYFEE
                                LEFT JOIN U_LGUFEES K ON K.CODE=A.U_BPLPFOFEE
                                LEFT JOIN U_LGUFEES L ON L.CODE=A.U_ZONINGLOCATIONALFEE
                                LEFT JOIN U_LGUFEES M ON M.CODE=A.U_ACCESSORYFEECODE
                                LEFT JOIN U_LGUFEES N ON N.CODE=A.U_BUILDINGPERMITFEECODE
                                LEFT JOIN U_LGUFEES O ON O.CODE=A.U_MECHANICALFEECODE
                                LEFT JOIN U_LGUFEES P ON P.CODE=A.U_LINEGRADEFEECODE
                                LEFT JOIN U_LGUFEES Q ON Q.CODE=A.U_FILINGFEECODE
                                LEFT JOIN U_LGUFEES R ON R.CODE=A.U_PROCESSINGFEECODE
                                LEFT JOIN U_LGUFEES S ON S.CODE=A.U_INSPECTIONFEECODE");
        
	if ($objRs2->queryfetchrow("NAME")) {
		$page->privatedata["ctcbasicfeecode"] = $objRs2->fields["U_CTCBASICFEE"];
		$page->privatedata["ctcbasicfeename"] = $objRs2->fields["U_CTCBASICNAME"];
		$page->privatedata["ctcbasicfeeamount"] = $objRs2->fields["U_CTCBASICAMOUNT"];
		$page->privatedata["ctcpenfeecode"] = $objRs2->fields["U_CTCPENFEECODE"];
		$page->privatedata["ctcpenfeename"] = $objRs2->fields["U_CTCPENFEENAME"];
		$page->privatedata["ctcgrossfeecode"] = $objRs2->fields["U_CTCGROSSFEE"];
		$page->privatedata["ctcgrossfeename"] = $objRs2->fields["U_CTCGROSSFEENAME"];
		$page->privatedata["occupationalfeecode"] = $objRs2->fields["U_OCCUPATIONALFEE"];
		$page->privatedata["occupationalname"] = $objRs2->fields["U_OCCUPATIONALNAME"];
		$page->privatedata["documentaryfee"] = $objRs2->fields["U_DOCUMENTARYFEE"];
		$page->privatedata["documentaryname"] = $objRs2->fields["U_DOCUMENTARYNAME"];
		$page->privatedata["healthfee"] = $objRs2->fields["U_BPLPFOFEE"];
		$page->privatedata["healthname"] = $objRs2->fields["U_HEALTHNAME"];
		$page->privatedata["zoninglocationalfeecode"] = $objRs2->fields["U_ZONINGLOCATIONALFEE"];
		$page->privatedata["zoninglocationalfeename"] = $objRs2->fields["U_ZONINGLOCATIONALNAME"];
		$page->privatedata["accessorycode"] = $objRs2->fields["U_ACCESSORYFEECODE"];
		$page->privatedata["accessoryname"] = $objRs2->fields["U_ACCESSORYFEENAME"];
		$page->privatedata["buildingpermitfeecode"] = $objRs2->fields["U_BUILDINGPERMITFEECODE"];
		$page->privatedata["buildingpermitfeename"] = $objRs2->fields["U_BUILDINGPERMITFEENAME"];
		$page->privatedata["linegradefeecode"] = $objRs2->fields["U_LINEGRADEFEECODE"];
		$page->privatedata["linegradefeename"] = $objRs2->fields["U_LINEGRADEFEENAME"];
		$page->privatedata["filingfeecode"] = $objRs2->fields["U_FILINGFEECODE"];
		$page->privatedata["filingfeename"] = $objRs2->fields["U_FILINGFEENAME"];
		$page->privatedata["processingfeecode"] = $objRs2->fields["U_PROCESSINGFEECODE"];
		$page->privatedata["processingfeename"] = $objRs2->fields["U_PROCESSINGFEENAME"];
		$page->privatedata["inspectionfeecode"] = $objRs2->fields["U_INSPECTIONFEECODE"];
		$page->privatedata["inspectionfeename"] = $objRs2->fields["U_INSPECTIONFEENAME"];
		
		
		
                $page->privatedata["year"] = date('Y');
    	}
	
	if ($page->privatedata["suser"]=="Y" ){
				
				$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
	}

	
	if ($page->privatedata["buildingencoder"]=="1" || $page->privatedata["buildingassessor"]=="1" || $page->privatedata["buildingapprover"]=="1") {
		if ($page->getitemstring("docstatus")!="Approved" && $page->getitemstring("docstatus")!= "Disapproved" ) { 
				
				$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
			
			
		}	
		
		
		
		
		if ($page->privatedata["buildingencoder"]=="1" || $page->privatedata["buildingapprover"]=="1") {
		if ($page->getitemstring("docstatus")!="Encoding") { 
				
				$page->toolbar->addbutton("forassessment","For Assessment","u_forAssessmentGPSBPLS()","left");
				 } else if ($page->getitemstring("docstatus")=="Assessed") { 
                                if ($page->privatedata["buildingapprover"]=="1") {
   				$page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSENGINEERING()","left");
    			$page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSENGINEERING()","left");
      				//$page->businessobject->items->seteditable("u_approverremarks",true);
                                }	
                        } else if ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved"  ) { 
                                //$objGrids[0]->dataentry = false;
                            //   $objGrids[4]->dataentry = false;
                              //  $page->businessobject->items->seteditable("u_reqappno",false);
                                if ($page->privatedata["buildingapprover"]=="1") {
                                        $page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSENGINEERING()","left");
                                        //$page->businessobject->items->seteditable("u_approverremarks",true);
                                } 
                        } else if ( $page->getitemstring("docstatus")=="Paid" || $page->getitemstring("docstatus")=="Printing" ){
                              //  $objGrids[0]->dataentry = false;
                                //$objGrids[4]->dataentry = false;
                               // $page->businessobject->items->seteditable("u_reqappno",false);
                                //$page->toolbar->addbutton("billadjustments","AUDITED GROSS","u_BillAdjustmentsGPSBPLS()","left");
                        }
                }
			
			
		}	


	if (isEditMode()) {	
	//	$page->businessobject->items->seteditable("u_apptype",false);
	//	$page->businessobject->items->seteditable("u_ownership",false);
		//$page->businessobject->items->seteditable("u_APPNO",false);
		//$page->businessobject->items->seteditable("u_AREANO",false);
		
		if ($page->getitemstring("docstatus")=="Encoding") {
			if ($page->privatedata["buildingencoder"]=="1") $page->toolbar->addbutton("forassessment","For Assessment","u_forAssessmentGPSENGINEERING()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessing") { 
			if ($page->privatedata["buildingassessor"]=="1") $page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSENGINEERING()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
	$objGrids[6]->dataentry = false;
			$objGrids[7]->dataentry = false;
			//$objGrids[8]->dataentry = false;
			if ($page->privatedata["buildingapprover"]=="1") {
				$page->toolbar->addbutton("approve","Approve","u_approveGPSENGINEERING()","left");
				$page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSENGINEERING()","left");
			}	
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$objGrids[6]->dataentry = false;
			$objGrids[7]->dataentry = false;
			//$objGrids[8]->dataentry = false;
			$page->businessobject->items->seteditable("u_APPNO",false);
		$page->businessobject->items->seteditable("u_AREANO",false);
			//$page->setvar("formAccess","R");
			if ($page->privatedata["buildingapprover"]=="1") {
				$page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSENGINEERING()","left");
//				$page->businessobject->items->seteditable("u_approverremarks",true);
			}	
		}
	} //else {
		//if ($page->getitemstring("u_apptype")=="RENEW" || $page->getitemstring("u_apptype")=="UPDATE") {
		//	$page->businessobject->items->seteditable("u_apptype",false);
		//	$page->businessobject->items->seteditable("u_franchiseseries",false);
		//}
		//if ($page->getitemstring("u_franchiseseries")!="-1") {
		//	$page->businessobject->items->seteditable("u_franchiseno",false);
		//}
	//}

	
	//$page->toolbar->setaction("print",false);
	
	$objMaster->reportaction = "QS";
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

