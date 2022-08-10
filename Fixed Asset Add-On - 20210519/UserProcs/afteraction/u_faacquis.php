<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_docdate",false);
		
		//if ($page->getitemstring("docstatus")=="C") {
		//	$page->setvar("formAccess","R");
		//}
		//$page->toolbar->setaction("new",false);
		//$page->toolbar->setaction("update",false);
		$objGrids[0]->columninput("u_selected","disabled",true);
		$objGrids[0]->columninput("u_cost","disabled",true);
		$objGrids[0]->columninput("u_assettype","disabled",true);
		$objGrids[0]->columninput("u_assetcode","disabled",true);
		$objGrids[0]->columninput("u_contraglacctno","disabled",true);
		$objGrids[0]->columninput("u_profitcenter","disabled",true);
		$objGrids[0]->columninput("u_projcode","disabled",true);
		$objGrids[0]->columninput("u_empid","disabled",true);
		$objGrids[0]->columninput("u_branch","disabled",true);
		$objGrids[0]->columninput("u_department","disabled",true);
	} else {
		$objGrids[0]->columninput("u_cost","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_assettype","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_assetcode","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_contraglacctno","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_profitcenter","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_projcode","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_empid","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_branch","disabled","{u_selected}==0");
		$objGrids[0]->columninput("u_department","disabled","{u_selected}==0");
	}

	//$addoptions = false;
	//$deleteoption = false
?>