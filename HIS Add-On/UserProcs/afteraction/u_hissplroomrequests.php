<?php
	
	if (isEditMode()) {
		if ($page->getitemstring("docstatus")=="O") {
			$canceloption = true;
		} else {	
			$page->businessobject->items->seteditable("u_reftype",false);
			$page->businessobject->items->seteditable("u_refno",false);
			$page->businessobject->items->seteditable("u_requestno",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			$page->businessobject->items->seteditable("u_startdate",false);
			$page->businessobject->items->seteditable("u_starttime",false);
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_itemcode",false);
			$page->businessobject->items->seteditable("u_itemdesc",false);
			$page->toolbar->setaction("update",false);
		}
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