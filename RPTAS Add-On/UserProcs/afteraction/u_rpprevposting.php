<?php
        $objGrids[0]->columninput("u_withpenalty","type","checkbox");
        $objGrids[0]->columninput("u_withpenalty","value",1);
	if (isEditMode()) {
            if($page->getitemstring("docstatus")!="C"){
                $page->toolbar->addbutton("newfaas","Submit Posting","u_newfaas()","left");
            }
            
	}
                if($page->getvarstring("formType")=="popup") {
                   $page->toolbar->setaction("navigation",false);
                }
	
?>