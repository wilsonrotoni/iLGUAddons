<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_facode",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_newlifem",false);
		
		//if ($page->getitemstring("docstatus")=="C") {
		//	$page->setvar("formAccess","R");
		//}
		//$page->toolbar->setaction("new",false);
		//$page->toolbar->setaction("update",false);
	}

	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_famgmnt from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
	}

	//$addoptions = false;
	//$deleteoption = false
?>