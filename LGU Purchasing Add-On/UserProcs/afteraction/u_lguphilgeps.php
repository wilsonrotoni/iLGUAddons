<?php

    $objRs = new recordset(null,$objConnection);
    $objRs->queryopen("SELECT U_PHILGEPSENCODER, U_PHILGEPSAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
    if ($objRs->queryfetchrow("NAME")) {
            $page->privatedata["encoder"] = $objRs->fields["U_PHILGEPSENCODER"];
            $page->privatedata["approver"] = $objRs->fields["U_PHILGEPSAPPROVER"];
    }
    if ($page->getitemstring("docstatus")=="O") {
            $httpVars["copydocumentfrom"] = true;
            $httpVars["copydocumentfromCaption"] = "Copy From ";
            $page->popup->addgroup("popupCopyDocumentFrom");
            $page->popup->additem("popupCopyDocumentFrom","Purchase Request","CopyFromPRGPSLGUPurchasing()");
    }
  
    if (isEditMode()) {
	if ($page->getitemstring("docstatus")!="D") {
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_duedate",false);
		$page->businessobject->items->seteditable("u_procmode",false);
		$page->businessobject->items->seteditable("u_poprofitcenter",false);
		$page->businessobject->items->seteditable("docstatus",false);
		$page->businessobject->items->seteditable("u_profitcenter",false);
		$page->businessobject->items->seteditable("u_profitcentername",false);
		//$page->businessobject->items->seteditable("u_checkbank",false);
		//$page->businessobject->items->seteditable("u_checkbankacctno",false);
		//$page->businessobject->items->seteditable("u_checkno",false);
		$page->businessobject->items->seteditable("u_doctype",false);
		//$page->businessobject->items->seteditable("u_jevseries",false);
		//$page->businessobject->items->seteditable("u_jevno",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_proctype",false);
//		$page->businessobject->items->seteditable("u_lvatperc",false);
//		$page->businessobject->items->seteditable("u_levatperc",false);
//		$page->businessobject->items->seteditable("u_lvatamount",false);
//		$page->businessobject->items->seteditable("u_levatamount",false);
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
                $page->toolbar->addbutton("printtomerge","Print to Merge","u_printToMergeGPSLGUPurchasing()","left");

        }
        if ($page->getitemstring("docstatus")!="C") {
             $objGrids[0]->addbutton("u_addeditremarks","[Add/Edit Line Remarks]","u_AddEditRemarksGPSLGUPurchasing()","left");
             $objGrids[1]->addbutton("u_addeditremarks","[Add/Edit Line Remarks]","u_AddEditRemarksGPSLGUPurchasing()","left");
        }
    }
?> 

