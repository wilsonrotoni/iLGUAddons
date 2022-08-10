<?php
 

//$page->businessobject->events->add->customAction("onCustomActionLGURPTAS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGURPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultLGURPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGURPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGURPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddLGURPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGURPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGURPTAS");
//$page->businessobject->events->add->afterEdit("onAfterEditLGURPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateLGURPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateLGURPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateLGURPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteLGURPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteLGURPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteLGURPTAS");

//function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
//	global $page;
//	global $objGrids;
//	if ($table=="T1" && $column=="u_selected" && $page->getitemstring("docstatus")=="O") {
//		$checked["name"] = $column;
//		$checked["attributes"] = "";
//		$checked["input"]["value"] = 1;
//		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
//		$ownerdraw = true;
//	}
//}

function onCustomActionLGURPTAS($action) {
	return true;
}

function onBeforeDefaultLGURPTAS() { 
	return true;
}

function onAfterDefaultLGURPTAS() { 
        global $objConnection;
	global $page;
	global $objGrids;
        
        $page->setitem("u_date",currentdate());
        $page->setitem("u_year",date('Y'));
        $page->setitem("searchby","L");
        
      
	return true;
}

function onPrepareAddLGURPTAS(&$override) { 
	return true;
}

function onBeforeAddLGURPTAS() { 
	return true;
}

function onAfterAddLGURPTAS() { 
	return true;
}

function onPrepareEditLGURPTAS(&$override) { 
	return true;
}

function onBeforeEditLGURPTAS() { 
	return true;
}

function onAfterEditLGURPTAS() { 
	return true;
}

function onPrepareUpdateLGURPTAS(&$override) { 
	return true;
}

function onBeforeUpdateLGURPTAS() { 
	return true;
}

function onAfterUpdateLGURPTAS() { 
	return true;
}

function onPrepareDeleteLGURPTAS(&$override) { 
	return true;
}

function onBeforeDeleteLGURPTAS() { 
	return true;
}

function onAfterDeleteLGURPTAS() { 
	return true;
}

$schema["searchby"] = createSchemaUpper("searchby");
$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_tin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_declaredowner","OpenCFLfs()");

$objGrids[0]->columndataentry("u_selected","type","checkbox");
$objGrids[0]->columndataentry("u_selected","value",1);

$objGrids[0]->columncfl("u_docno","OpenCFLfs()");
$objGrids[0]->columncfl("u_tdno","OpenCFLfs()");
//$objGrids[0]->columncfl("u_pin","OpenCFLfs()")
//
$objGrids[0]->columntitle("u_tdno","ARP No.");
$objGrids[0]->columnlnkbtn("u_docno","openupdpays()");;
$objGrids[0]->columnwidth("u_docno",10);
$objGrids[0]->columnwidth("u_location",35);
$objGrids[0]->columnwidth("u_class",20);
$objGrids[0]->columnwidth("u_kind",12);
$objGrids[0]->columnwidth("u_tdno",20);
$objGrids[0]->columnwidth("u_pin",22);
$objGrids[0]->columnwidth("u_trxcode",20);
$page->businessobject->items->seteditable("u_totalmarketvalue",false);
$page->businessobject->items->seteditable("u_totalassvalue",false);



//$page->toolbar->addbutton("cf","Copy From","formSubmit('cf')","left","Purchase Order");
$objMaster->reportaction = "QS";
?> 


