<?php
        $objRs1 = new recordset(null,$objConnection);
        $objRs1->queryopen("select A.U_AMNESTYYEAR,A.U_ISVALIDAMNESTY from U_LGUSETUP A ");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["amnestyyear"] = $objRs1->fields["U_AMNESTYYEAR"];
                $page->privatedata["isamnestyvalid"] = $objRs1->fields["U_ISVALIDAMNESTY"];
	}
        if ($page->privatedata["isamnestyvalid"] != 1){
             $page->businessobject->items->seteditable("u_isamnesty",false);
        } else {
             $page->businessobject->items->seteditable("u_isamnesty",true);
        }
        
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("select A.U_MANUALPOSTINGFLAG from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
                $page->privatedata["manualpostingflag"] = $objRs->fields["U_MANUALPOSTINGFLAG"];
	}
        if ($page->privatedata["manualpostingflag"] != 1){
                $page->businessobject->items->seteditable("u_assdate",false);
                $page->businessobject->items->seteditable("u_duedate",false);
        } else {
                $page->businessobject->items->seteditable("u_assdate",true);
                $page->businessobject->items->seteditable("u_duedate",true);
        }
        
        $page->privatedata["year"] = date('Y');
//        $objGrids[0]->addbutton("u_prevposting","[Post Previous]","u_PrevPostingGPSRPTAS()","left");
//        $objGrids[0]->addbutton("u_sqpendisc","[Calc Penalty&Disc Semi/Quarter]","u_PenaltyDiscQuarSemiGPSRPTAS()","left");
	$objGrids[0]->columninput("u_selected","type","checkbox");
	$objGrids[0]->columninput("u_selected","value",1);
        
	$objGrids[1]->columninput("u_selected","type","checkbox");
	$objGrids[1]->columninput("u_selected","value",1);
        
	$objGrids[2]->columninput("u_selected","type","checkbox");
	$objGrids[2]->columninput("u_selected","value",1);
        //$objGrids[2]->addbutton("u_taxcredits","[Add New Tax Credit]","u_TaxCreditGPSRPTAS()","left");
        
	if (isEditMode()) {
                        
                        $page->businessobject->items->seteditable("u_duedate",false);
                        $page->businessobject->items->seteditable("u_assdate",false);
			$page->businessobject->items->seteditable("u_tin",false);
			$page->businessobject->items->seteditable("u_pin",false);
			$page->businessobject->items->seteditable("u_tdno",false);
			$page->businessobject->items->seteditable("u_paymode",false);
			$page->businessobject->items->seteditable("u_yearto",false);
			$page->businessobject->items->seteditable("u_advancepay",false);
			$page->businessobject->items->seteditable("u_yearbreak",false);
			$page->businessobject->items->seteditable("u_nopenaltydisc",false);
			$page->businessobject->items->seteditable("u_nopenalty",false);
			$page->businessobject->items->seteditable("u_nodisc",false);
			$page->businessobject->items->seteditable("u_isamnesty",false);
                        $objGrids[0]->columnattributes("u_selected","disabled");
                        $objGrids[1]->columnattributes("u_selected","disabled");
                        $objGrids[2]->columnattributes("u_selected","disabled");
                       
                        $objGrids[0]->dataentry = false;
                        $objGrids[1]->dataentry = false;
                        $objGrids[2]->dataentry = false;
			$objGrids[0]->columnattributes("u_selected","disabled");
                        $objGrids[0]->columninput("u_penaltyadj","type","label");
			$objGrids[0]->columninput("u_sefpenaltyadj","type","text");
			$objGrids[0]->columninput("u_taxdiscadj","type","text");
			$objGrids[0]->columninput("u_sefdiscadj","type","text");
                        $page->toolbar->setaction("update",false);
                        $deleteoption = false;
                        
                if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("docstatus")=="CN" ) {
                    $page->businessobject->items->seteditable("u_ornumber",false);
                    $page->businessobject->items->seteditable("u_paidby",false);
                    $page->businessobject->items->seteditable("u_paidamount",false);
                    $page->businessobject->items->seteditable("u_ordate",false);
                }
                if ($page->getitemstring("docstatus")=="O") {
                        $page->toolbar->addbutton("u_cancel","Cancel","u_paymentcancelGPSRPTAS()","right");
                        $page->toolbar->addbutton("u_payment","Payment","u_paymentGPSRPTAS()","left");
			$page->toolbar->addbutton("u_cashier","Cashier","u_cashierGPSRPTAS()","left");
                }
                
                $page->toolbar->addbutton("u_cashier","F2 - Cashier","u_cashierGPSRPTAS()","left");
	} else {
               
                $page->toolbar->addbutton("u_searchproperty","F4 - Search Property","u_SearchPropertyGPSRPTAS()","left");
                $page->toolbar->addbutton("u_sqpendisc","S/Q Calc Penalty&Discount","u_ComputePenaltyDiscQuarSemiGPSRPTAS()","left");
                $page->toolbar->addbutton("u_manualposting","Manual Posting","showPopupManualPosting()","left");
//		$objGrids[0]->columninput("u_taxdue","type","text");
//		$objGrids[0]->columninput("u_sef","type","text");
//		$objGrids[0]->columninput("u_sefdisc","type","text");
//		$objGrids[0]->columninput("u_taxdisc","type","text");
//		$objGrids[0]->columninput("u_penalty","type","text");
//		$objGrids[0]->columninput("u_sefpenalty","type","text");
//                        $objGrids[0]->columnattributes("u_taxdue","disabled");
//                        $objGrids[0]->columnattributes("u_sef","disabled");
//                        $objGrids[0]->columnattributes("u_sefdisc","disabled");
//                        $objGrids[0]->columnattributes("u_taxdisc","disabled");
//                        $objGrids[0]->columnattributes("u_penalty","disabled");
//                        $objGrids[0]->columnattributes("u_sefpenalty","disabled");
                       
                    
//		$objGrids[0]->columninput("u_epsf","type","text");
                
	}
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
	//$deleteoption = false;
?>