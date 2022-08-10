<?php

	//if ($page->privatedata["bplencoder"]=="1" && $page->getitemstring("u_preassbill")=="0" && $page->getitemstring("docstatus")=="Encoding") {
		//$page->toolbar->addbutton("u_payment","Payment","u_paymentGPSRPTAS()","left");
	//}

	$page->privatedata["year"] = date('Y');
	
	$objGrids[0]->columninput("u_selected","type","checkbox");
	$objGrids[0]->columninput("u_selected","value",1);
	if (isEditMode()) {
		if ($page->getitemstring("docstatus")!="D") {
			$objGrids[0]->columnattributes("u_selected","disabled");
                        $page->businessobject->items->seteditable("docstatus",false);
                        $page->businessobject->items->seteditable("u_date",false);
                        $page->businessobject->items->seteditable("u_refno",false);
			
//			$page->toolbar->addbutton("u_payment","Payment","u_paymentGPSRPTAS()","left");
		}
                if ($page->getitemstring("docstatus")=="O") {
                    $page->toolbar->addbutton("cancel","Cancel","u_forCancelGPSPMS()","left");
                }
	} 
	
	//$deleteoption = false;
?>