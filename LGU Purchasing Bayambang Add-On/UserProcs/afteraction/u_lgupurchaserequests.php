<?php

    $objRs = new recordset(null, $objConnection);
    $objRs->queryopen("SELECT U_TREASURY,U_BUDGET FROM USERS WHERE USERID='" . $_SESSION["userid"] . "'");
    if ($objRs->queryfetchrow("NAME")) {
        $page->privatedata["treasury"] = $objRs->fields["U_TREASURY"];
        $page->privatedata["budget"] = $objRs->fields["U_BUDGET"];
    }

    if (isEditMode()) {
        $page->businessobject->items->seteditable("u_refno",false);
        if ($page->getitemstring("docstatus") != "D") {
            
        } else {
            if ($page->privatedata["budget"] == 1) {
                $page->businessobject->items->seteditable("u_refno",true);
            }
            if ($page->privatedata["treasury"] == 1) {
                if ($page->getitemstring("u_cashavailable") != "1") {
                    $page->toolbar->addbutton("checkcashavail", "Check Cash Available", "u_CheckCashAvailGPSRPTAS()", "left");
                } else {
                    $page->toolbar->addbutton("uncheckcashavail", "Uncheck Cash Available", "u_UnCheckCashAvailGPSRPTAS()", "left");
                }
            }
        }
    }
?> 

