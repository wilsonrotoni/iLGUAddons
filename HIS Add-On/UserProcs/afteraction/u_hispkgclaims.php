<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_acthc",false);
		$page->businessobject->items->seteditable("u_pkghc",false);
		$page->businessobject->items->seteditable("u_guarantorcode",false);
		$page->businessobject->items->seteditable("u_memberid",false);
		$page->businessobject->items->seteditable("u_membername",false);
		$objGrids[0]->dataentry = false;
		$deleteoption = false;
		if ($page->getitemstring("docstatus")=="CN") {
			$page->setvar("formAccess","R");
			$page->toolbar->setaction("update",false);
		}
		if ($page->getitemstring("u_billno")!="") {
			$deleteoption = false;
			if ($page->getitemstring("docstatus")!="CN") {
				$canceloption = true;
			}
		} 
	} else {
	}

	if ($page->getvarstring("formType")=="lnkbtn") {
		$page->toolbar->setaction("find",false);
		$page->toolbar->setaction("navigation",false);
		$page->toolbar->setaction("new",false);
	}

	if ($page->getvarstring("edtopt")!="integrated") {
		$page->businessobject->items->setvisible("u_reftype",false);
		$page->businessobject->items->setvisible("u_refno",false);
	}
	
	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";	
?>