<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_meal",false);
		$page->businessobject->items->seteditable("u_department",false);
		
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select max(docid) from u_hisdietaries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_department='".$page->getitemstring("u_department")."'");
		if ($objRs->queryfetchrow()) {
			if ($objRs->fields[0]>$page->getitemstring("docid")) {
				$objGrids[0]->columninput("u_diettypedesc","type","label");
				$objGrids[0]->columninput("u_remarks","type","label");
				$page->toolbar->setaction("update",false);
			}	
			return false;
		}
		
	}

?>