<?php
	
	if (isEditMode()) {
		if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("u_payrefno")!="") {
			$page->businessobject->items->seteditable("u_requestdepartment",false);
			$page->businessobject->items->seteditable("u_department",false);
			$page->businessobject->items->seteditable("u_reftype",false);
			$page->businessobject->items->seteditable("u_refno",false);
			$page->businessobject->items->seteditable("u_requestno",false);
			$page->businessobject->items->seteditable("u_description",false);
			$page->businessobject->items->seteditable("u_doctorid",false);
			$page->businessobject->items->seteditable("u_startdate",false);
			$page->businessobject->items->seteditable("u_starttime",false);
			$page->businessobject->items->seteditable("u_enddate",false);
			$page->businessobject->items->seteditable("u_endtime",false);
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_itemcode",false);
			$page->businessobject->items->seteditable("u_itemdesc",false);
			$page->businessobject->items->seteditable("u_disccode",false);
			$page->businessobject->items->seteditable("u_pricelist",false);
			$page->businessobject->items->seteditable("u_unitprice",false);
			$page->businessobject->items->seteditable("u_requestby",false);
			if ($page->getitemstring("docstatus")=="C") {
				$page->businessobject->items->seteditable("u_remarks",false);
			}	
			
			//$page->toolbar->setaction("update",false);
		}
		if ($page->getitemstring("docstatus")=="O" && $page->getitemstring("u_payrefno")=="") $canceloption = true;
		//if ($page->getitemstring("docstatus")=="C") {
			//$objGrids[0]->columninput("u_result","type","label");
			//$objGrids[0]->columninput("u_remarks","type","label");
			//$objGrids[1]->setaction("add",false);
			//$page->toolbar->setaction("update",false);
			$closeoption = false;
		//}
	} else {
	}
	
	if ($page->getitemstring("u_trxtype")=="IP" || $page->getitemstring("u_trxtype")=="OP") {
		$page->businessobject->items->seteditable("u_reftype",false);
		//$page->businessobject->items->seteditable("u_requestdepartment",false);
	}
	
	if ($page->getvarstring("formType")=="popup" || $page->getvarstring("formType")=="lnkbtn") {
		//$page->toolbar->setaction("print",false);
		$page->toolbar->setaction("new",false);
		$page->toolbar->setaction("navigation",false);
		$page->toolbar->setaction("find",false);
	}
?>