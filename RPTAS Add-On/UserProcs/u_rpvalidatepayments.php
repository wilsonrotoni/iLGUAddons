<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSLGU");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGU");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGU");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGU");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGU");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGU");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGU");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGU");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGU");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGU");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGU");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGU");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGU");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGU");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGU");

function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
	global $page;
	global $objGrids;
	if ($table=="T1" && $column=="u_selected" && ($page->getitemstring("docstatus")=="O" || $page->getitemstring("docstatus")=="Pending")) {
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
}

function onCustomActionGPSLGU($action) {
        
	return true;
}

function onBeforeDefaultGPSLGU() { 
	return true;
}

function onAfterDefaultGPSLGU() { 
        global $objConnection;
	global $page;
	global $objGrids;
        
//        $page->setitem("u_remittancedate",currentdate());
//        $page->setitem("u_asofdate",currentdate());
//        $page->setitem("u_cashierby",$_SESSION["userid"]);
	return true;
}

function onPrepareAddGPSLGU(&$override) { 
	return true;
}

function onBeforeAddGPSLGU() { 
	return true;
}

function onAfterAddGPSLGU() { 
	return true;
}

function onPrepareEditGPSLGU(&$override) { 
	return true;
}

function onBeforeEditGPSLGU() { 
	return true;
}

function onAfterEditGPSLGU() { 
	return true;
}

function onPrepareUpdateGPSLGU(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGU() { 
	return true;
}

function onAfterUpdateGPSLGU() { 
	return true;
}

function onPrepareDeleteGPSLGU(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGU() { 
	return true;
}

function onAfterDeleteGPSLGU() { 
	return true;
}

//$page->paging->formid = "./UDP.php?&objectcode=u_lguupdpays";

unset($enumdocstatus["D"],$enumdocstatus["C"],$enumdocstatus["CN"]);
$enumdocstatus["Approved"]="Approved";
$enumdocstatus["Disapproved"]="Disapproved";
$enumdocstatus["Pending"]="Pending";

$page->businessobject->items->setcfl("u_ordate","Calendar");
//$page->businessobject->items->setcfl("u_asofdate","Calendar");
//$page->businessobject->items->setcfl("u_ordateto","Calendar");
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalamount",false);
$page->businessobject->items->seteditable("u_selectedtotalamount",false);

$objGrids[0]->dataentry = false;
$objGrids[0]->columnwidth("u_paidby",50);
$objGrids[0]->columnwidth("u_tdno",20);
$objGrids[0]->columnwidth("u_ornumber",12);
$objGrids[0]->columnvisibility("u_refno",false);

$page->toolbar->setaction("add",false);
$page->toolbar->setaction("update",false);

?> 

