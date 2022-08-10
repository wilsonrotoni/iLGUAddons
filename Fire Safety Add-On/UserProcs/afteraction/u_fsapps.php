<?php
 	$enumdocstatus["FI"] = "For Inspection";
 	$enumdocstatus["AP"] = "Approved";
        $enumdocstatus["DA"] = "Disapproved";
        $enumdocstatus["Paid"] = "Paid";
	
 	if (isEditMode()) {
		if (($page->getitemstring("docstatus")=="AP" || $page->getitemstring("docstatus")=="DA" ) ) { 
                    $page->businessobject->items->seteditable("u_docdate",false);
                    $page->businessobject->items->seteditable("u_appnature",false);
                    $page->businessobject->items->seteditable("u_bldgno",false);
                    $page->businessobject->items->seteditable("u_corpname",false);
                    $page->businessobject->items->seteditable("u_owneraddress",false);
                    $page->businessobject->items->seteditable("u_lastname",false);
                    $page->businessobject->items->seteditable("u_firstname",false);
                    $page->businessobject->items->seteditable("u_middlename",false);
                    $page->businessobject->items->seteditable("u_projtype",false);
                    $page->businessobject->items->seteditable("u_ownertelno",false);
                    $page->businessobject->items->seteditable("u_projname",false);
                    $page->businessobject->items->seteditable("u_lotarea",false);
                    $page->businessobject->items->seteditable("u_totflrareabldg",false);
                    $page->businessobject->items->seteditable("u_surveycount",false);
                    $page->businessobject->items->seteditable("u_duration",false);
                    $page->businessobject->items->seteditable("u_amount",false);

                    $page->setvar("formAccess","R");
                    $objGrids[0]->dataentry = false;
                    $deleteoption = false;
                    $page->toolbar->addbutton("reassessment","Reassessment","u_reassessmentGPSLGUFireSafety()","left");
                } else {
                    if ($page->getitemstring("docstatus")!="Paid"){
                        $page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSLGUFireSafety()","left");
                        $page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSLGUFireSafety()","left");
                    } else {
                        $page->businessobject->items->seteditable("u_docdate",false);
                        $page->businessobject->items->seteditable("u_appnature",false);
                        $page->businessobject->items->seteditable("u_appnatureothers",false);
                        $page->businessobject->items->seteditable("u_bbldgno",false);
                        $page->businessobject->items->seteditable("u_corpname",false);
                        $page->businessobject->items->seteditable("u_owneraddress",false);
                        $page->businessobject->items->seteditable("u_lastname",false);
                        $page->businessobject->items->seteditable("u_firstname",false);
                        $page->businessobject->items->seteditable("u_middlename",false);
                        $page->businessobject->items->seteditable("u_projtype",false);
                        $page->businessobject->items->seteditable("u_ownertelno",false);
                        $page->businessobject->items->seteditable("u_lotarea",false);
                        $page->businessobject->items->seteditable("u_totflrareabldg",false);
                        $page->businessobject->items->seteditable("u_noofflrs",false);
                        $page->businessobject->items->seteditable("u_duration",false);
                        //$page->businessobject->items->seteditable("u_appno",false);
                        $page->businessobject->items->seteditable("u_bldgappno",false);
                        
                        $page->businessobject->items->seteditable("u_year",false);
                        $page->businessobject->items->seteditable("u_classification",false);
                        $page->businessobject->items->seteditable("u_isonlineapp",false);
                        $page->businessobject->items->seteditable("u_authrep",false);
                        $page->businessobject->items->seteditable("u_corpname",false);
                        $page->businessobject->items->seteditable("u_corpaddress",false);
                        $page->businessobject->items->seteditable("u_contractorname",false);
                        $page->businessobject->items->seteditable("u_projectname",false);
                        
                        $page->businessobject->items->seteditable("u_approvedby",false);
//                        $page->businessobject->items->seteditable("u_receivedby",false);
                        $page->businessobject->items->seteditable("u_assessedby",false);
                        $page->businessobject->items->seteditable("u_approveddate",false);
//                        $page->businessobject->items->seteditable("u_receiveddate",false);
                        $page->businessobject->items->seteditable("u_assesseddate",false);
                        
                       
                        $objGrids[0]->columnattributes("u_check","disabled");
                        $objGrids[0]->columnattributes("u_amount","disabled");
                        $objGrids[1]->columnattributes("u_check","disabled");
                        $objGrids[1]->columnattributes("u_remarks","disabled");
                        $page->toolbar->addbutton("u_submit","Save","formSubmit()","right");
                    }
                }
                
	}else {
            $page->toolbar->addbutton("searchbpas","Search Application - F4","u_OpenPopSearchBPASApp()","left");
            $page->toolbar->addbutton("u_submit","Save","formSubmit()","right");
        }


            $objRs = new recordset(null,$objConnection);
            $objRs->queryopen("select A.U_ZONINGCLEARANCEFEECODE, B.NAME AS U_ZONINGCLEARANCEFEEDESC, A.U_ZONINGPROCESSINGFEECODE, C.NAME AS U_ZONINGPROCESSINGFEEDESC, A.U_ZONINGSURVEYFEECODE, D.NAME AS U_ZONINGSURVEYFEEDESC, A.U_ZONINGLANDUSEFEECODE, E.NAME AS U_ZONINGLANDUSEFEEDESC
                                                    from U_LGUSETUP A
                                                            LEFT JOIN U_LGUFEES B ON B.CODE=A.u_zoningclearancefeecode
                                                            LEFT JOIN U_LGUFEES C ON C.CODE=A.u_zoningprocessingfeecode
                                                            LEFT JOIN U_LGUFEES D ON D.CODE=A.u_zoningsurveyfeecode
                                                            LEFT JOIN U_LGUFEES E ON D.CODE=A.u_zoninglandusefeecode
                                                            ");


            if ($objRs->queryfetchrow("NAME")) {
                    $page->privatedata["zoningclearancefeecode"] = $objRs->fields["U_ZONINGCLEARANCEFEECODE"];
                    $page->privatedata["zoningclearancefeedesc"] = $objRs->fields["U_ZONINGCLEARANCEFEEDESC"];
                    $page->privatedata["zoningprocessingfeecode"] = $objRs->fields["U_ZONINGPROCESSINGFEECODE"];
                    $page->privatedata["zoningprocessingfeedesc"] = $objRs->fields["U_ZONINGPROCESSINGFEEDESC"];
                    $page->privatedata["zoningsurveyfeecode"] = $objRs->fields["U_ZONINGSURVEYFEECODE"];
                    $page->privatedata["zoningsurveyfeedesc"] = $objRs->fields["U_ZONINGSURVEYFEEDESC"];
                    $page->privatedata["zoninglandusefeecode"] = $objRs->fields["U_ZONINGLANDUSEFEECODE"];
                    $page->privatedata["zoninglandusefeedesc"] = $objRs->fields["U_ZONINGLANDUSEFEEDESC"];
            }

            $objMaster->reportaction = "QS";

	
?> 

