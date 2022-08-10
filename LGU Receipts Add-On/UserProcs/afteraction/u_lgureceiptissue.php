<?php
 
        $page->toolbar->setaction("add",false);
        $page->toolbar->setaction("update",false);
      
	if (isEditMode()) {
            
            $objRs->queryopen("select * from USERS A WHERE USERID='".$page->getitemstring("code")."' and ISVALID = 1");
            if ($objRs->queryfetchrow("NAME")) {
                    $page->toolbar->addbutton("u_issuemultiplereceipt","Issue Multiple Receipts","formIssueMultipleReceipts()","left");
                    $page->toolbar->addbutton("u_issuereceipt","Issue Receipt","formIssueReceipts()","left");
                    $page->toolbar->addbutton("u_returnreceipt","Return Receipt","formReturnReceipts()","left");

            } else {
                   if($page->getitemstring("u_isbrgy") == 1) {
                        $page->toolbar->addbutton("u_issuemultiplereceipt","Issue Multiple Receipts","formIssueMultipleReceipts()","left");
                        $page->toolbar->addbutton("u_issuereceipt","Issue Receipt","formIssueReceipts()","left");
                        $page->toolbar->addbutton("u_returnreceipt","Return Receipt","formReturnReceipts()","left");
                   } 
            }
//		if ($page->getitemstring("u_status")=="O") {
//			$page->toolbar->addbutton("u_closeregister","Close Register","u_closeRegisterGPSPOSAsddon()","right");
//			$objGrids[1]->columninput("u_count","type","text");
//		} else 	{
//			$page->toolbar->setaction("update",false);
//		}
	}else {
            $page->toolbar->addbutton("u_submit","Register Cashier","formSubmit()","left");
        } 

?> 

