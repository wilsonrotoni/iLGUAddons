<?php
 

//$page->businessobject->events->add->customAction("onCustomActionLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUPurchasing");
$page->businessobject->events->add->afterDefault("onAfterDefaultLGUPurchasing");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUPurchasing");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUPurchasing");
//$page->businessobject->events->add->afterEdit("onAfterEditLGUPurchasing");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateLGUPurchasing");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateLGUPurchasing");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateLGUPurchasing");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteLGUPurchasing");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteLGUPurchasing");
//$page->businessobject->events->add->afterDelete("onAfterDeleteLGUPurchasing");

function onCustomActionLGUPurchasing($action) {
	return true;
}

function onBeforeDefaultLGUPurchasing() { 
	return true;
}

function onAfterDefaultLGUPurchasing() { 
        global $page;
        
        $page->setitem("u_date",currentdate());
        $page->setitem("u_time",date('H:i'));
	return true;
}

function onPrepareAddLGUPurchasing(&$override) { 
	return true;
}

function onBeforeAddLGUPurchasing() { 
	return true;
}

function onAfterAddLGUPurchasing() { 
	return true;
}

function onPrepareEditLGUPurchasing(&$override) { 
	return true;
}

function onBeforeEditLGUPurchasing() { 
	return true;
}

function onAfterEditLGUPurchasing() { 
	return true;
}

function onPrepareUpdateLGUPurchasing(&$override) { 
	return true;
}

function onBeforeUpdateLGUPurchasing() { 
	return true;
}

function onAfterUpdateLGUPurchasing() { 
	return true;
}

function onPrepareDeleteLGUPurchasing(&$override) { 
	return true;
}

function onBeforeDeleteLGUPurchasing() { 
	return true;
}

function onAfterDeleteLGUPurchasing() { 
	return true;
}


$page->businessobject->items->setcfl("u_ordate","Calendar");
$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_datecalc","Calendar");

$page->businessobject->items->setcfl("u_bidder","OpenCFLfs()");
$page->businessobject->items->seteditable("u_bidno",false);
$page->businessobject->items->seteditable("u_bidtype",false);
//$page->businessobject->items->setcfl("u_profitcentername","OpenCFLfs()");


//$objGrids[0]->columnwidth("u_itemcode",20);
//$objGrids[0]->columnwidth("u_itemdesc",50);
//$objGrids[0]->columnwidth("u_glacctno",20);

//
//$objGrids[1]->columnattributes("u_cost","disabled");
//$objGrids[1]->columncfl("u_glacctno","OpenCFLfs()");

//$objGrids[0]->automanagecolumnwidth = false;

//$pageHeader = "Material Request";
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);

$addoptions = false;

?> 

