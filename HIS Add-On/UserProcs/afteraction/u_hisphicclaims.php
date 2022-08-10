<?php
	
	$page->businessobject->items->seteditable("u_1stcrpf",true);
	$page->businessobject->items->seteditable("u_1stcrhc",true);
	$page->businessobject->items->seteditable("u_2ndcrpf",true);
	$page->businessobject->items->seteditable("u_2ndcrhc",true);
	$page->businessobject->items->seteditable("u_pan",true);
	
	if (isEditMode()) {
		if ($page->getitemstring("u_xmtalno")!="" || $page->getitemstring("docstatus")=="CN") {
			$page->businessobject->items->seteditable("u_lastname",false);
			$page->businessobject->items->seteditable("u_firstname",false);
			$page->businessobject->items->seteditable("u_middlename",false);
			$page->businessobject->items->seteditable("u_extname",false);
			$page->businessobject->items->seteditable("u_phicno",false);
			$page->businessobject->items->seteditable("u_phicno",false);
			$page->businessobject->items->seteditable("u_acthc",false);
			$page->businessobject->items->seteditable("u_bdischc",false);
			$page->businessobject->items->seteditable("u_birthdate",false);
			$page->businessobject->items->seteditable("u_gender",false);
			$page->businessobject->items->seteditable("u_membertype",false);
			$page->businessobject->items->seteditable("u_ismember",false);
			$page->businessobject->items->seteditable("u_memberid",false);
			$page->businessobject->items->seteditable("u_membername",false);
			$page->businessobject->items->seteditable("u_startdate",false);
			$page->businessobject->items->seteditable("u_starttime",false);
			$page->businessobject->items->seteditable("u_enddate",false);
			$page->businessobject->items->seteditable("u_endtime",false);
			$objGrids[0]->dataentry = false;
			$objGrids[1]->dataentry = false;
			$deleteoption = false;
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

	if ($page->getitemstring("u_expired")=="0") {
		$page->businessobject->items->seteditable("u_expiredate",false);
		$page->businessobject->items->seteditable("u_expiretime",false);
	}
	
	if ($page->getitemstring("u_ismember")=="1") {
		$page->businessobject->items->seteditable("u_memberid",false);
		$page->businessobject->items->seteditable("u_membername",false);
	}
	
	if ($page->getitemstring("u_isreferred")=="0") {
		$page->businessobject->items->seteditable("u_hci_to_name",false);
		$page->businessobject->items->seteditable("u_hci_to_street",false);
		$page->businessobject->items->seteditable("u_hci_to_city",false);
		$page->businessobject->items->seteditable("u_hci_to_province",false);
		$page->businessobject->items->seteditable("u_hci_to_zip",false);
	}

	if ($page->getitemstring("u_istransferred")=="0") {
		$page->businessobject->items->seteditable("u_hci_fr_name",false);
		$page->businessobject->items->seteditable("u_hci_fr_street",false);
		$page->businessobject->items->seteditable("u_hci_fr_city",false);
		$page->businessobject->items->seteditable("u_hci_fr_province",false);
		$page->businessobject->items->seteditable("u_hci_fr_zip",false);
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