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
        global $objConnection;
	global $page;
	global $objGrids;
        
        $page->setitem("u_doctype","I");
        $page->setitem("u_date",currentdate());
        $page->setitem("u_duedate",currentdate());
        
        $objRs_UserProfitCenter = new recordset(null,$objConnection);
	$objRs_UserProfitCenter->queryopen("select U_PROFITCENTER,U_PROFITCENTERNAME from U_USERPROFITCENTERS WHERE CODE = '".$_SESSION["userid"]."' AND U_ISDEFAULT = 1");
	if ($objRs_UserProfitCenter->queryfetchrow("NAME")) {
            $page->setitem("u_profitcenter",$objRs_UserProfitCenter->fields["U_PROFITCENTER"]);
            $page->setitem("u_profitcentername",$objRs_UserProfitCenter->fields["U_PROFITCENTERNAME"]);
            $page->setitem("u_pdprofitcenter",$objRs_UserProfitCenter->fields["U_PROFITCENTER"]);
        }
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


$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_duedate","Calendar");
$page->businessobject->items->setcfl("u_bpcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bpname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcenter","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcentername","OpenCFLfs()");
$page->businessobject->items->setcfl("u_projcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_obrno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_requestedbyname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_reviewedby","OpenCFLfs()");
$page->businessobject->items->setcfl("u_approvedby","OpenCFLfs()");

$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columncfl("u_whscode","OpenCFLfs()");
$objGrids[0]->columnwidth("u_basetypenm",25);
$objGrids[0]->columnwidth("u_basedocno",25);
$objGrids[0]->columnwidth("u_itemsubgroup",15);
$objGrids[0]->columnwidth("u_whscode",15);
$objGrids[0]->columnvisibility("u_openquantity",false);
$objGrids[0]->columnvisibility("u_linestatus",false);
//$objGrids[0]->columnattributes("u_cost","disabled");
$objGrids[0]->columnattributes("u_openquantity","disabled");
$objGrids[0]->columntitle("u_unitissue","UoM");
$objGrids[0]->columntitle("u_openquantity","Remaining Qty.");
$objGrids[0]->columnlnkbtn("u_itemcode","OpenItems()");

$objGrids[0]->columnvisibility("u_remarks",false);
$objGrids[1]->columnvisibility("u_remarks",false);

$objGrids[1]->columntitle("u_unitissue","UoM");

$objGrids[1]->columnvisibility("u_openquantity",false);
$objGrids[1]->columnvisibility("u_linestatus",false);
$objGrids[1]->columntitle("u_openquantity","Remaining Qty.");
$objGrids[1]->columncfl("u_glacctno","OpenCFLfs()");



//$page->toolbar->addbutton("cf","Copy From","formSubmit('cf')","left","Purchase Order");
 
?> 


