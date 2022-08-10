<?php

	$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
	$objGridA->columninput("u_taxable","type","checkbox");
	$objGridA->columninput("u_taxable","value",1);
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_pin",false);
		$page->businessobject->items->seteditable("u_trxcode",false);
                
                if ($page->getitemstring("u_trxcode")=="CS") {
                $objGrids[0]->addbutton("u_consolidate","[Consolidate]","u_consolidateGPSRPTAS()","left");
                }
		if ($page->getitemstring("docstatus")=="Encoding") {
			$page->toolbar->addbutton("forrecommend","For Recommendation","u_forRecommendGPSRPTAS()","left");
			if ($page->getitemstring("u_prevpin")!="") {
				$page->businessobject->items->setvisible("u_pinchanged",true);
			}	
			if ($page->getitemstring("u_pinchanged")=="1") {
				$page->businessobject->items->seteditable("u_pin",true);
			}	
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
                    $page->toolbar->addbutton("reassess","Re-Assessment","u_reassessGPSRPTAS()","left");
                    $page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSRPTAS()","left");
		} elseif ($page->getitemstring("docstatus")=="Recommended") { 
			$page->toolbar->addbutton("approve","Approve","u_approveGPSRPTAS()","left");
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$page->businessobject->items->seteditable("u_barangay",false);
			$page->businessobject->items->seteditable("u_taxable",false);
			$page->businessobject->items->seteditable("u_effqtr",false);
			$page->businessobject->items->seteditable("u_effyear",false);
                        $page->businessobject->items->seteditable("u_tdno",false);
                        $page->businessobject->items->seteditable("u_varpno",false);
                        $page->businessobject->items->seteditable("u_idleland",false);
			//$page->toolbar->deletebutton("u_submit");
		}
		
		if ($page->getitemstring("docstatus")=="Assessed" || $page->getitemstring("docstatus")=="Recommended" || $page->getitemstring("docstatus")=="Approved") { 
			if ($page->getitemstring("u_isauction") == 1) {
                            $page->toolbar->addbutton("unhold","Uncheck Auction","showPopupAuctionTD()","right"); 
                        } else {
                            $page->toolbar->addbutton("onhold","Check Auction","u_AuctionGPSRPTAS()","right"); 
                        }
		}
                 if($page->getvarstring("formType")=="popup") {
                    $page->toolbar->setaction("find",false);
                    $page->toolbar->setaction("navigation",false);
                    $page->businessobject->items->seteditable("u_pinno",true);
                   }
                   
		$objGridC->columnvisibility("edit",true);
		//$objGridA->setaction("add",true);
                $page->toolbar->addbutton("PaymentHistory","Payment History","u_ViewPaymentHistory()","left");
                $objGrids[0]->addbutton("u_postprevious","[Post Previous]","u_PrevPostingGPSRPTAS()","left");
	}
	
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
	
?>