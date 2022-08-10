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
			$page->businessobject->items->seteditable("u_assdate",false);
			$page->businessobject->items->seteditable("u_declaredowner",false);
			$page->businessobject->items->seteditable("u_assdate",false);
			$deleteoption = false;
//			$page->toolbar->addbutton("u_payment","Payment","u_paymentGPSRPTAS()","left");
		} else {
			$objGrids[0]->columninput("u_penaltyadj","type","text");
			$objGrids[0]->columninput("u_sefpenaltyadj","type","text");
			$objGrids[0]->columninput("u_taxdiscadj","type","text");
			$objGrids[0]->columninput("u_sefdiscadj","type","text");
		}		
	} else {
		$objGrids[0]->columninput("u_penaltyadj","type","text");
		$objGrids[0]->columninput("u_sefpenaltyadj","type","text");
		$objGrids[0]->columninput("u_taxdiscadj","type","text");
		$objGrids[0]->columninput("u_sefdiscadj","type","text");
	}
	
	//$deleteoption = false;
?>