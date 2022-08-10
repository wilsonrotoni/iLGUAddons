<?php

    $enumdocstatus["AP"] = "Approved";
    $enumdocstatus["DA"] = "Disapproved";
    $enumdocstatus["Paid"] = "Paid";

if (isEditMode()) {
    
        if (($page->getitemstring("docstatus")=="AP" || $page->getitemstring("docstatus")=="DA" ) ) { 
            $page->businessobject->items->seteditable("u_docdate",false);
            $page->businessobject->items->seteditable("u_apptype",false);
            $page->businessobject->items->seteditable("u_bldgno",false);
            $page->businessobject->items->seteditable("u_contractorname",false);
            $page->businessobject->items->seteditable("u_contractoraddress",false);
            $page->businessobject->items->seteditable("u_contractortelno",false);
            $page->businessobject->items->seteditable("u_ownername",false);
            $page->businessobject->items->seteditable("u_owneraddress",false);
            $page->businessobject->items->seteditable("u_ownertelno",false);
            $page->businessobject->items->seteditable("u_projname",false);
            $page->businessobject->items->seteditable("u_area",false);
            $page->businessobject->items->seteditable("u_projaddress",false);
            $page->businessobject->items->seteditable("u_duration",false);
            $page->businessobject->items->seteditable("u_amount",false);
            $objGrids[0]->dataentry = false;
            $deleteoption = false;
            $page->toolbar->addbutton("reassessment","Reassessment","u_reassessmentGPSLGUBPLS()","left");
        } else {
            if ($page->getitemstring("docstatus")!="Paid"){
                $page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSLGUBPLS()","left");
                $page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSLGUBPLS()","left");
            }
            
        }
        
} else {
    $page->toolbar->addbutton("searchbpas","Search Application - F4","u_OpenPopSearchBPASApp()","left");
    $page->toolbar->addbutton("u_submit","Save","formSubmit()","right");
} 


$objRs = new recordset(null,$objConnection);
$objRs->queryopen("select  A.U_BPLCONTRACTORSTAX, B.NAME AS U_CONTRACTORSTAXFEEDESC
    from U_LGUSETUP A
    LEFT JOIN U_LGUFEES B ON B.CODE=A.U_BPLCONTRACTORSTAX                                             
    ");

if ($objRs->queryfetchrow("NAME")) {
    $page->privatedata["contractorstaxfeecode"] = $objRs->fields["U_BPLCONTRACTORSTAX"];
    $page->privatedata["contractorstaxfeedesc"] = $objRs->fields["U_CONTRACTORSTAXFEEDESC"];
}
$objMaster->reportaction = "QS";

?> 

