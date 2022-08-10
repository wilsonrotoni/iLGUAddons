<?php
	
	$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
	$objGridA->columninput("u_taxable","type","checkbox");
	$objGridA->columninput("u_taxable","value",1);
	$objGridC->columninput("u_taxable","type","checkbox");
	$objGridC->columninput("u_taxable","value",1);
        
	if (isEditMode()) {
		if ($page->getitemstring("docstatus")=="Encoding") {
			$page->toolbar->addbutton("forrecommend","For Recommendation","u_forRecommendGPSRPTAS()","left");
			if ($page->getitemstring("u_prevpin")!="") {
				$page->businessobject->items->setvisible("u_pinchanged",true);
			}	
			if ($page->getitemstring("u_pinchanged")=="1") {
				$page->businessobject->items->seteditable("u_prefix",true);
				$page->businessobject->items->seteditable("u_suffix",true);
			}	
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
			$page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSRPTAS()","left");
		} elseif ($page->getitemstring("docstatus")=="Recommended") { 
			$page->toolbar->addbutton("approve","Approve","u_approveGPSRPTAS()","left");
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) {
                        if($page->getitemstring("u_cancelled")!=1){
                            $page->toolbar->addbutton("forcancelation","For Cancellation","u_forCancelationGPSRPTAS()","left");
                        }
                        $page->businessobject->items->seteditable("u_barangay",false);
			$page->businessobject->items->seteditable("u_taxable",false);
			$page->businessobject->items->seteditable("u_effqtr",false);
			$page->businessobject->items->seteditable("u_effyear",false);
                        $page->businessobject->items->seteditable("u_tdno",false);
                        $page->businessobject->items->seteditable("u_varpno",false);
			//$page->toolbar->deletebutton("u_submit");
		}

		if ($page->getitemstring("docstatus")=="Assessed" || $page->getitemstring("docstatus")=="Recommended" || $page->getitemstring("docstatus")=="Approved") { 
			if ($page->getitemstring("u_isauction") == 1) {
                            $page->toolbar->addbutton("unhold","Uncheck Auction","showPopupAuctionTD()","right"); 
                        } else {
                            $page->toolbar->addbutton("onhold","Check Auction","u_AuctionGPSRPTAS()","right"); 
                        }
		}
                $page->businessobject->items->seteditable("u_prefix",true);
                $page->businessobject->items->seteditable("u_suffix",true);
                $page->toolbar->addbutton("PaymentHistory","Payment History","u_ViewPaymentHistory()","left");
		$objGridA->columnvisibility("edit",true);
		//$objGridA->setaction("add",true);
	}
	
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
	
?>