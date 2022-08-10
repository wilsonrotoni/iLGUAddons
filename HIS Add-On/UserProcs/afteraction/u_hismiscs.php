<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_description",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_amount",false);
		$page->businessobject->items->seteditable("u_pricelist",false);
		$page->businessobject->items->seteditable("u_prepaid",false);
		$objGrids[0]->dataentry = false;
		if ($page->getitemstring("u_returned")=="1") {
			$canceloption = false;
		}
		if ($page->getitemstring("u_prepaid")=="1") {
			$canceloption = false;
		}
		//if ($page->getitemstring("docstatus")=="C") {
			$page->businessobject->items->seteditable("u_department",false);
			$page->businessobject->items->seteditable("u_disccode",false);
			$page->businessobject->items->seteditable("u_reqdate",false);
			$page->businessobject->items->seteditable("u_reqtime",false);
			$page->businessobject->items->seteditable("u_enddate",false);
			$page->businessobject->items->seteditable("u_endtime",false);
			//$objGrids[0]->columninput("u_result","type","label");
			//$objGrids[0]->columninput("u_remarks","type","label");
			//$objGrids[1]->setaction("add",false);
			//$page->toolbar->setaction("update",false);
			//$closeoption = false;
		//}
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	if ($page->getitemstring("u_trxtype")=="IP" || $page->getitemstring("u_trxtype")=="OP") {
		$page->businessobject->items->seteditable("u_reftype",false);
		//$page->businessobject->items->seteditable("u_department",false);
	}

	$page->toolbar->setaction("navigation",false);
	
?>