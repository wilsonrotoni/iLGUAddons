<?php
	
	if (isEditMode()) {
            if ($page->getitemstring("docstatus")!="D") {
                $page->businessobject->items->seteditable("u_docdate",false);
		$objGrids[0]->dataentry = false;
            }
		//$page->toolbar->setaction("new",false);
		//$page->toolbar->setaction("update",false);
	}
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_famgmnt from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
	}
	
	if ($page->privatedata["famgmnt"]=="BR") {
		$objGrids[0]->columnattributes("u_branch","disabled");
		$objGrids[0]->setdefaultvalue("u_branch",$_SESSION["branch"]);
	}	
?>