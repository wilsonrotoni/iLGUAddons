<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("u_patientid",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_docduedate",false);
		$page->businessobject->items->seteditable("u_pnamount",false);
		$page->businessobject->items->seteditable("u_guarantorcode",false);
		$page->businessobject->items->seteditable("u_feetype",false);
		$page->businessobject->items->seteditable("u_doctorid",false);
		$page->businessobject->items->seteditable("u_casecode",false);
		$page->businessobject->items->seteditable("u_caserate",false);
		$page->businessobject->items->seteditable("u_exclaim",false);
		if ($page->getitemstring("docstatus")=="O" && $page->getitemstring("u_xmtalno")=="" && $page->getitemdecimal("u_btamount")==0) {
			$canceloption = true;
		} else $page->toolbar->setaction("update",false);	

		//if ($page->getitemstring("u_claimno")!="") {
			//$page->setvar("formAccess","R");
			//$page->toolbar->setaction("update",false);	
		//	$canceloption = false;
		//}
	}

	if ($page->getitemstring("u_btrefno")!="") {
		$page->businessobject->items->seteditable("u_feetype",false);
		$page->businessobject->items->seteditable("u_doctorid",false);
	}
	
	
	if ($page->getitemstring("u_feetype")=="HF") {
		$page->businessobject->items->seteditable("u_doctorid",false);
	}
	
	switch ($page->getitemstring("u_hmo")) {
		case "1":
		case "4":
			$page->businessobject->items->seteditable("u_membertype",false);
			$page->businessobject->items->seteditable("u_caserate",false);
			break;
		case "-1":
		case "2":
		case "3":
		case "5":
			$page->businessobject->items->seteditable("u_memberid",false);
			$page->businessobject->items->seteditable("u_membername",false);
			$page->businessobject->items->seteditable("u_membertype",false);
			$page->businessobject->items->seteditable("u_caserate",false);
			break;
		case "6":
			$page->businessobject->items->seteditable("u_memberid",false);
			$page->businessobject->items->seteditable("u_membername",false);
			$page->businessobject->items->seteditable("u_membertype",false);
			break;
			
	}
	
	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";	
?>