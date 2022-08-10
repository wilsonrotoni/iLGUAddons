<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_series",false);
		//$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("code",false);
		
		$obju_HISMDRPs = new masterdataschema(null,$objConnection,"u_hismdrps");
		if ($obju_HISMDRPs->getbykey($page->getitemstring("code"))) {
			$page->businessobject->items->seteditable("u_salespricing",false);
			$page->businessobject->items->seteditable("u_scdiscamount",false);
		}
		
	} else {
		if ($page->getitemstring("u_series")!=-1) {
			$page->businessobject->items->seteditable("code",false);
		}
	}
?>