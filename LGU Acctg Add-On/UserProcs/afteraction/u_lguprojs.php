<?php

$deleteoption = false;
if (isEditMode()) {
	$page->businessobject->items->seteditable("u_yr",false);
	$page->businessobject->items->seteditable("u_appcode",false);
	$page->businessobject->items->seteditable("u_profitcenter",false);
	
	if ($page->getitemstring("u_status")=="Revised") {
		$page->businessobject->items->seteditable("u_annex",false);
		$page->businessobject->items->seteditable("name",false);
		$page->businessobject->items->seteditable("u_procmode",false);
		$page->businessobject->items->seteditable("u_sourcefunds",false);
		$page->businessobject->items->seteditable("u_schedule",false);
		$page->businessobject->items->seteditable("u_estmooe",false);
		$page->businessobject->items->seteditable("u_estco",false);
		$page->businessobject->items->seteditable("u_remarks",false);
	} elseif ($page->getitemstring("u_refappcode")!="") {
		$page->businessobject->items->seteditable("u_annex",false);
		$page->businessobject->items->seteditable("name",false);
	}

	
}



?>