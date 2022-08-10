<?php
        $objRs1 = new recordset(null,$objConnection);
        $objRs1->queryopen("select A.U_ITDEPT from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["itdept"] = $objRs1->fields["U_ITDEPT"];
                $page->privatedata["userid"] = $_SESSION["userid"];
	}
        
        $objRs2 = new recordset(null,$objConnection);
        $objRs2->queryopen("SELECT A.U_BURIALPERMITFEE, B.NAME AS U_BURIALPERMITFEENAME
                                FROM U_LGUSETUP A     
                                LEFT JOIN U_LGUFEES B ON B.CODE=A.U_BURIALPERMITFEE ");
        
	if ($objRs2->queryfetchrow("NAME")) {
		$page->privatedata["burialpermitfeecode"] = $objRs2->fields["U_BURIALPERMITFEE"];
		$page->privatedata["burialpermitfeename"] = $objRs2->fields["U_BURIALPERMITFEENAME"];
                $page->privatedata["year"] = date('Y');
    	}
        
	if (isEditMode()) {	
                $page->businessobject->items->seteditable("docstatus",false);
                $page->businessobject->items->seteditable("u_docseries",false);
                $page->businessobject->items->seteditable("u_totalamount",false);
	} else {
             $page->toolbar->addbutton("u_submit","F4 - Post Transaction","PostTransaction()","left");
        }

	$objMaster->reportaction = "QS";
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

