<?php
    $objRs = new recordset(null,$objConnection);
    $objRs->queryopen("SELECT U_GRPOENCODER,U_GRPOAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
    if ($objRs->queryfetchrow("NAME")) {
            $page->privatedata["encoder"] = $objRs->fields["U_GRPOENCODER"];
            $page->privatedata["approver"] = $objRs->fields["U_GRPOAPPROVER"];
    }
    
    if ($page->getitemstring("docstatus")=="O") {
        
            $httpVars["copydocumentfrom"] = true;
            $httpVars["copydocumentfromCaption"] = "Copy From ";
            $page->popup->addgroup("popupCopyDocumentFrom");
            $page->popup->additem("popupCopyDocumentFrom","Purchase Order","CopyFromPOGPSLGUPurchasing()");
            $page->popup->additem("popupCopyDocumentFrom","Progress Billing","CopyFromSplitPOGPSLGUPurchasing()");
    }

    if (isEditMode()) {
            if ($page->getitemstring("docstatus")!="D") {
                    $page->businessobject->items->seteditable("u_date",false);
                    $page->businessobject->items->seteditable("u_duedate",false);
                    $page->businessobject->items->seteditable("u_refno",false);
                    $page->businessobject->items->seteditable("u_bptype",false);
                    $page->businessobject->items->seteditable("u_bpcode",false);
                    $page->businessobject->items->seteditable("u_bpname",false);
                    $page->businessobject->items->seteditable("u_profitcenter",false);
                    $page->businessobject->items->seteditable("u_profitcentername",false);
                    $page->businessobject->items->seteditable("u_doctype",false);
                    $page->businessobject->items->seteditable("u_checkamount",false);
                    //$page->businessobject->items->seteditable("u_jevseries",false);
                    //$page->businessobject->items->seteditable("u_jevno",false);
                    $page->businessobject->items->seteditable("u_remarks",false);
                    //$page->businessobject->items->seteditable("u_tf",false);
                    //$page->businessobject->items->seteditable("u_tfbank",false);
                    //$page->businessobject->items->seteditable("u_tfbankacctno",false);
                    $objGrids[0]->setaction("add",false);
                    $objGrids[0]->setaction("edit",false);
                    $objGrids[0]->setaction("delete",false);
                    $objGrids[1]->setaction("add",false);
                    $objGrids[1]->setaction("edit",false);
                    $objGrids[1]->setaction("delete",false);

                    $objGrids[0]->columnvisibility("u_openquantity",true);
                    $objGrids[1]->columnvisibility("u_openquantity",true);
                    $deleteoption = false;

                    if (($page->getitemstring("docstatus")=="O" && $page->privatedata["approver"]==1)) {
                            $canceloption = true;
                            $movetodraft = true;
                    }

            }
            if ($page->getitemstring("docstatus")!="C") {
                 $objGrids[0]->addbutton("u_addeditremarks","[Add/Edit Line Remarks]","u_AddEditRemarksGPSLGUPurchasing()","left");
                 $objGrids[1]->addbutton("u_addeditremarks","[Add/Edit Line Remarks]","u_AddEditRemarksGPSLGUPurchasing()","left");
            }
    }
?> 

