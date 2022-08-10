<?php
        $objRs1 = new recordset(null,$objConnection);
        $objRs1->queryopen("select A.U_ITDEPT from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["itdept"] = $objRs1->fields["U_ITDEPT"];
                $page->privatedata["userid"] = $_SESSION["userid"];
	}
        
        
	if (isEditMode()) {
//                $objGrids[0]->dataentry = false;
                $page->businessobject->items->seteditable("docstatus",false);
                $page->businessobject->items->seteditable("u_docseries",false);
                $page->businessobject->items->seteditable("u_totalamount",false);
	} else {
             $page->toolbar->addbutton("u_submit","F4 - Post Transaction","PostTransaction()","left");
        }

	$objMaster->reportaction = "QS";
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

