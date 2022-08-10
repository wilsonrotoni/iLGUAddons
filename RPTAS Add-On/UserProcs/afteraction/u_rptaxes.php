<?php


        
        $page->privatedata["year"] = date('Y');
        
        $objGrids[0]->addbutton("u_prevposting","[Post Previous]","u_PrevPostingGPSRPTAS()","left");
        $objGrids[0]->addbutton("u_sqpendisc","[Calc Penalty&Disc Semi/Quarter]","u_PenaltyDiscQuarSemiGPSRPTAS()","left");
	$objGrids[0]->columninput("u_selected","type","checkbox");
	$objGrids[0]->columninput("u_selected","value",1);
        
	$objGrids[2]->columninput("u_selected","type","checkbox");
	$objGrids[2]->columninput("u_selected","value",1);
        $objGrids[2]->addbutton("u_prevposting","[Add New]","u_TaxCreditGPSRPTAS()","right");
        
//	$objGrids[3]->columninput("u_selected","type","checkbox");
//	$objGrids[3]->columninput("u_selected","value",1);
        
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("select u_rptasessortreasurylink from u_lgusetup;");
       
        if ($objRs->queryfetchrow("NAME")) {
            $page->privatedata["dataentry"] = $objRs->fields["u_rptasessortreasurylink"];
            if($objRs->fields["u_rptasessortreasurylink"]=='0' && $page->getitemstring("u_withfaas")=="0" ){
                $objGrids[0]->dataentry = true ;
                $objGrids[0]->columnattributes("u_taxdue","disabled");
                $objGrids[0]->columnattributes("u_sef","disabled");
                $objGrids[0]->columnattributes("u_billdate","disabled");
                $objGrids[0]->columnattributes("u_seftotal","disabled");
                $objGrids[0]->columnattributes("u_taxtotal","disabled");
                $objGrids[0]->columnattributes("u_noofyrs","disabled");
                $page->businessobject->items->setvisible("u_withfaas",true);
                $page->businessobject->items->seteditable("u_declaredowner",true);
                $page->businessobject->items->seteditable("u_tin",false);
                $page->businessobject->items->seteditable("u_tdno",false);
                $page->businessobject->items->seteditable("u_changemount",false);
                $page->setitem("u_tin","Cash");
		$page->setitem("u_tdno","Cash Sales");
              
//                $page->businessobject->items->seteditable("u_paymode",false);
              }else{
                $page->businessobject->items->setvisible("u_withfaas",true);
                $objGrids[0]->dataentry = true ;
                $page->businessobject->items->seteditable("u_declaredowner",false);
                $page->businessobject->items->seteditable("u_tin",true);
                $page->businessobject->items->seteditable("u_tdno",true);
                $page->businessobject->items->seteditable("u_changemount",false);
            }
          
        }
	if (isEditMode()) {
                        $page->businessobject->items->seteditable("u_assdate",false);
			$page->businessobject->items->seteditable("u_tin",false);
			$page->businessobject->items->seteditable("u_cashsalesno",false);
			$page->businessobject->items->seteditable("u_pin",false);
			$page->businessobject->items->seteditable("u_tdno",false);
			$page->businessobject->items->seteditable("u_paymode",false);
			$page->businessobject->items->seteditable("u_yearto",false);
			$page->businessobject->items->seteditable("u_advancepay",false);
			$page->businessobject->items->seteditable("u_partialpay",false);
			$page->businessobject->items->seteditable("u_withfaas",false);
			$page->businessobject->items->seteditable("u_yearbreak",false);
			$page->businessobject->items->seteditable("u_nopenaltydisc",false);
			$page->businessobject->items->seteditable("u_nopenalty",false);
			$page->businessobject->items->seteditable("u_nodiscount",false);
                        $objGrids[2]->columnattributes("u_selected","disabled");
                        $objGrids[2]->dataentry = false;
                        $objGrids[0]->dataentry = false;
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
 		
	} else {
		$objGrids[0]->columninput("u_penaltyadj","type","text");
		$objGrids[0]->columninput("u_sefpenaltyadj","type","text");
		$objGrids[0]->columninput("u_taxdiscadj","type","text");
		$objGrids[0]->columninput("u_sefdiscadj","type","text");
		$objGrids[0]->columninput("u_dpamount","type","text");
	}
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
	//$deleteoption = false;
?>