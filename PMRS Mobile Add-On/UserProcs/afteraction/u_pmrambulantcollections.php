<?php

//	$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
//	$objGridA->columninput("u_taxable","type","checkbox");
//	$objGridA->columninput("u_taxable","value",1);
	if (isEditMode()) {
		
		if ($page->getitemstring("docstatus")!="D") {
                    $page->businessobject->items->seteditable("u_terminalid",false);
                    $page->businessobject->items->seteditable("u_userid",false);
                    $objGrids[0]->dataentry = true ;
		} else {
                    $page->businessobject->items->seteditable("u_terminalid",true);
                    $page->businessobject->items->seteditable("u_userid",true);
                }  
                if ($page->getitemstring("docstatus")=="C") {
                    $page->businessobject->items->seteditable("u_date",false);
                    $page->toolbar->setaction("update",false);
                    $objGrids[0]->dataentry = false ;
		} 
                
	}
	
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
	
?>