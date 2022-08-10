<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSBPLS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSBPLS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSBPLS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSBPLS");
//$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSBPLS");

//function onDrawGridColumnLabelGPSBPLS($tablename,$column,$row,&$label) {
//	global $objRs;
//	global $trxrunbal;
//	switch ($tablename) {
//		case "T1":
//                    if ($column=="u_businessline") {
//                            $objRs->queryopen("select name from u_bpllines where code='".$label."'");
//                            if ($objRs->queryfetchrow()) $label = $objRs->fields[0];
//                    }	
//	}
//}

function onCustomActionGPSBPLS($action) {
	return true;
}

function onBeforeDefaultGPSBPLS() { 
	return true;
}

function onAfterDefaultGPSBPLS() { 
	global $page;
	$page->setitem("u_date",currentdate());
	return true;
}

function onPrepareAddGPSBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSBPLS() { 
	return true;
}

function onAfterAddGPSBPLS() { 
	return true;
}

function onPrepareEditGPSBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSBPLS() { 
	return true;
}

function onAfterEditGPSBPLS() { 
	return true;
}

function onPrepareUpdateGPSBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSBPLS() { 
	return true;
}

function onAfterUpdateGPSBPLS() { 
	return true;
}

function onPrepareDeleteGPSBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSBPLS() { 
	return true;
}

function onAfterDeleteGPSBPLS() { 
	return true;
}

$page->businessobject->items->setcfl("u_pin","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_arpno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_date","Calendar");


$page->businessobject->items->seteditable("u_authorizedby",false);
$page->businessobject->items->seteditable("u_date",false);
$page->businessobject->items->seteditable("u_accountno",false);
$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("u_businessname",false);
$page->businessobject->items->seteditable("u_apprefno",false);
$page->businessobject->items->seteditable("u_baddress",false);
$page->businessobject->items->seteditable("u_paymode",false);
$page->businessobject->items->seteditable("u_tin",false);
$page->businessobject->items->seteditable("u_basetypenm",false);

$objGrids[0]->dataentry = false;
$objGrids[0]->columntitle("u_businesslinecode","Code");
$objGrids[0]->columnwidth("u_businesslinecode",8);
$objGrids[0]->columnwidth("u_businessline",25);
$objGrids[0]->columnwidth("u_taxclass",25);
$objGrids[0]->columnwidth("u_unauditedgross",15);
$objGrids[0]->columnwidth("u_btaxamount",15);
$objGrids[0]->columnwidth("u_auditedgross",15);
$objGrids[0]->columnwidth("u_auditedbtaxamount",15);
$objGrids[0]->columnwidth("u_paidamount",15);
$objGrids[0]->columnwidth("u_paidqtr",5);

$objGrids[0]->columninput("u_auditedgross","type","text");
$objGrids[0]->columninput("u_auditedbtaxamount","type","text");
$objGrids[0]->automanagecolumnwidth = false;

$addoptions = false;
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
?> 

