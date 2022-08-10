<?php

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUPurchasing");
//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUPurchasing");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUPurchasing");
//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUPurchasing");
//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUPurchasing");
$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUPurchasing");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUPurchasing");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUPurchasing");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUPurchasing");

function onCustomActionGPSLGUPurchasing($action) {
    return true;
}

function onBeforeDefaultGPSLGUPurchasing() {
    return true;
}

function onAfterDefaultGPSLGUPurchasing() {
    return true;
}

function onPrepareAddGPSLGUPurchasing(&$override) {
    return true;
}

function onBeforeAddGPSLGUPurchasing() {
    return true;
}

function onAfterAddGPSLGUPurchasing() {
    return true;
}

function onPrepareEditGPSLGUPurchasing(&$override) {
    return true;
}

function onBeforeEditGPSLGUPurchasing() {
    return true;
}

function onAfterEditGPSLGUPurchasing() {
    global $page;
    global $objConnection;
    global $objGridA;
    $objGridA->clear();

    $objRs = new recordset(null, $objConnection);
    $objRs->queryopen("SELECT DOCNO,U_STATUS,U_BIDDER,U_BIDAMOUNT,U_BIDSEC,U_BIDCOMPANY,U_ORNO,U_ORDATE,U_BIDSECAMOUNT,U_REQBIDSECAMOUNT,U_ISSUFFICIENT,U_REMARKS FROM u_lgubidabstractasread  WHERE COMPANY='" . $_SESSION["company"] . "' AND BRANCH='" . $_SESSION["branch"] . "' AND U_BIDNO='" . $page->getitemstring("docno") . "' AND U_BIDTYPE = 'INFRA'");
    while ($objRs->queryfetchrow("NAME")) {
        $objGridA->addrow();
        $objGridA->setitem(null, "docno", $objRs->fields["DOCNO"]);
        $objGridA->setitem(null, "u_status", $objRs->fields["U_STATUS"]);
        $objGridA->setitem(null, "u_bidder", $objRs->fields["U_BIDDER"]);
        $objGridA->setitem(null, "u_bidamount", formatNumericAmount($objRs->fields["U_BIDAMOUNT"]));
        $objGridA->setitem(null, "u_bidsec", $objRs->fields["U_BIDSEC"]);
        $objGridA->setitem(null, "u_bidcompany", $objRs->fields["U_BIDCOMPANY"]);
        $objGridA->setitem(null, "u_orno", $objRs->fields["U_ORNO"]);
        $objGridA->setitem(null, "u_date", $objRs->fields["U_ORDATE"]);
        $objGridA->setitem(null, "u_bidsecamount", formatNumericAmount($objRs->fields["U_BIDSECAMOUNT"]));
        $objGridA->setitem(null, "u_reqbidsec", formatNumericAmount($objRs->fields["U_REQBIDSECAMOUNT"]));
        $objGridA->setitem(null, "u_issufficient", $objRs->fields["U_ISSUFFICIENT"]);
        $objGridA->setitem(null, "u_remarks", $objRs->fields["U_REMARKS"]);
    }
    return true;
}

function onPrepareUpdateGPSLGUPurchasing(&$override) {
    return true;
}

function onBeforeUpdateGPSLGUPurchasing() {
    return true;
}

function onAfterUpdateGPSLGUPurchasing() {
    return true;
}

function onPrepareDeleteGPSLGUPurchasing(&$override) {
    return true;
}

function onBeforeDeleteGPSLGUPurchasing() {
    return true;
}

function onAfterDeleteGPSLGUPurchasing() {
    return true;
}

//$objGrid->columncfl("u_glacctno","OpenCFLfs()");
//$objGrid->columncfl("u_glacctname","OpenCFLfs()");
//
//$objGrid->columnwidth("u_glacctno",20);


$page->businessobject->items->seteditable("u_philgepsno", false);
$page->businessobject->items->seteditable("docstatus", false);
$page->businessobject->items->setcfl("u_opt1code", "OpenCFLfs()");
$page->businessobject->items->setcfl("u_opt3code", "OpenCFLfs()");
$page->businessobject->items->setcfl("u_opt5code", "OpenCFLfs()");
$page->businessobject->items->setcfl("u_opt6code", "OpenCFLfs()");
$page->businessobject->items->setcfl("u_barangay", "OpenCFLfs()");

$page->businessobject->items->setcfl("u_prebiddate", "Calendar");
$page->businessobject->items->setcfl("u_deadlinedate", "Calendar");
$page->businessobject->items->setcfl("u_openingdate", "Calendar");
$page->businessobject->items->setcfl("u_bidvaliduntil", "Calendar");
$page->businessobject->items->setcfl("u_itb1stday", "Calendar");


$objGrids[0]->columnwidth("u_keypersonel", 30);
$objGrids[0]->columnwidth("u_generalexperience", 10);
$objGrids[0]->columnwidth("u_relevantexperience", 10);
$objGrids[0]->columnalignment("u_generalexperience", "right");
$objGrids[0]->columnalignment("u_relevantexperience", "right");

$objGrids[1]->columnwidth("u_equipment", 30);
$objGrids[1]->columnwidth("u_capacity", 10);
$objGrids[1]->columnwidth("u_noofunits", 10);
$objGrids[1]->columnalignment("u_noofunits", "right");
$objGrids[1]->columnalignment("u_capacity", "right");

$objGridA = new grid("T101");
$objGridA->addcolumn("docno");
$objGridA->addcolumn("edit");
$objGridA->addcolumn("u_status");
$objGridA->addcolumn("u_bidder");
$objGridA->addcolumn("u_bidamount");
$objGridA->addcolumn("u_bidsec");
$objGridA->addcolumn("u_bidcompany");
$objGridA->addcolumn("u_orno");
$objGridA->addcolumn("u_date");
$objGridA->addcolumn("u_bidsecamount");
$objGridA->addcolumn("u_reqbidsec");
$objGridA->addcolumn("u_issufficient");
$objGridA->addcolumn("u_remarks");

$objGridA->columntitle("u_status", "Status");
$objGridA->columntitle("u_bidder", "Name of Bidder");
$objGridA->columntitle("u_bidamount", "Total Amount of Bid as Read");
$objGridA->columntitle("u_bidsec", "Form of Bid Security");
$objGridA->columntitle("u_bidcompany", "Bank/Company");
$objGridA->columntitle("u_orno", "Or Number");
$objGridA->columntitle("u_date", "Date");
$objGridA->columntitle("u_bidsecamount", "Bid Security Amount");
$objGridA->columntitle("u_reqbidsec", "Required Bid Security");
$objGridA->columntitle("u_issufficient", "Sufficient/Insufficient");
$objGridA->columntitle("u_remarks", "Remarks");
$objGridA->columntitle("edit", "");
$objGridA->columntitle("docno", "");

$objGridA->columnwidth("u_status", 11);
$objGridA->columnwidth("u_bidder", 40);
$objGridA->columnwidth("u_bidamount", 25);
$objGridA->columnwidth("u_bidsec", 30);
$objGridA->columnwidth("u_bidcompany", 28);
$objGridA->columnwidth("u_orno", 15);
$objGridA->columnwidth("u_date", 11);
$objGridA->columnwidth("u_bidsecamount", 20);
$objGridA->columnwidth("u_reqbidsec", 30);
$objGridA->columnwidth("u_issufficient", 20);
$objGridA->columnwidth("u_remarks", 30);
$objGridA->columnwidth("edit", 10);

$objGridA->columnalignment("u_bidamount", "right");
$objGridA->columnalignment("u_reqbidsec", "right");
$objGridA->columnalignment("u_bidsecamount", "right");


$objGridA->columndataentry("docno", "type", "label");
$objGridA->columndataentry("u_status", "type", "label");
$objGridA->columndataentry("u_bidder", "type", "label");
$objGridA->columndataentry("u_bidamount", "type", "label");
$objGridA->columndataentry("u_bidsec", "type", "label");
$objGridA->columndataentry("u_bidcompany", "type", "label");
$objGridA->columndataentry("u_orno", "type", "label");
$objGridA->columndataentry("u_date", "type", "label");
$objGridA->columndataentry("u_bidsecamount", "type", "label");
$objGridA->columndataentry("u_reqbidsec", "type", "label");
$objGridA->columndataentry("u_issufficient", "type", "label");
$objGridA->columndataentry("u_remarks", "type", "label");
//$objGridA->columnvisibility("docno", false);

$objGridA->dataentry = true;
$objGridA->showactionbar = false;
$objGridA->automanagecolumnwidth = false;
$objGridA->setaction("reset", false);
$objGridA->setaction("add", false);

$objGridA->width = 1380;
$objGridA->height = 260;

$objGridA->columninput("edit", "type", "link");
$objGridA->columninput("edit", "caption", "[Edit]");
$objGridA->columninput("edit", "dataentrycaption", "[Add Bidder]");


$page->toolbar->setaction("find", false);
$page->toolbar->setaction("navigation", false);

$addoptions = false;
?> 

