<?php
 	$enumdocstatus["FI"] = "For Inspection";
 	$enumdocstatus["P"] = "Paid";
	
 	if (isEditMode()) {
		if ($page->getitemstring("docstatus")=="FI" || $page->getitemstring("docstatus")=="P") {
			$page->businessobject->items->seteditable("u_docdate",false);
			$page->businessobject->items->seteditable("u_apptype",false);
			$page->businessobject->items->seteditable("u_bpno",false);
			$page->businessobject->items->seteditable("u_businessname",false);
			$page->businessobject->items->seteditable("u_orno",false);
			$page->businessobject->items->seteditable("u_ordate",false);
			$page->businessobject->items->seteditable("u_orfcamt",false);
		}else{
                    $page->toolbar->addbutton("pospayment","F4 - Accept Payment","u_AcceptPaymenGPSFireSafety()","left");
                }
                
	}

	
?> 

