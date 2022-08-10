<?php

    $enumdocstatus["AP"] = "Approved";
    $enumdocstatus["DA"] = "Disapproved";
    
    $objRs = new recordset(null, $objConnection);
    $objRs->queryopen("SELECT U_GOODSISSUEAPPROVE FROM USERS WHERE USERID='" . $_SESSION["userid"] . "'");
    if ($objRs->queryfetchrow("NAME")) {
        $page->privatedata["goodsissueapprove"] = $objRs->fields["U_GOODSISSUEAPPROVE"];
    }
    $page->toolbar->setaction("add",false);
    if (isEditMode()) {
        if ($page->getitemstring("docstatus") == "D") {
            if ($page->privatedata["goodsissueapprove"] == 1) {
                    $page->toolbar->addbutton("approve","Approve","u_approveGPSLGUPurchasingBayambang()","left");
                    $page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSLGUPurchasingBayambang()","left");
            }
        } else if ($page->getitemstring("docstatus") == "AP" || $page->getitemstring("docstatus") == "DA") {
                $addoptions = false;
                $page->toolbar->setaction("update",false);
                $page->toolbar->addbutton("Add","Add","u_AddGPSLGUPurchasingBayambang()","right");
                $page->toolbar->addbutton("movetodraft","Move to Draft","u_SaveAsDraftGPSLGUPurchasingBayambang()","right");
        } else {
            
        }
    } else {
         $page->toolbar->addbutton("saveasdraft","Save as Draft","u_SaveAsDraftGPSLGUPurchasingBayambang()","right");
    }
?> 

