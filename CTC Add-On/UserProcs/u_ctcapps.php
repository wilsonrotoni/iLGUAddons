<?php

$page->businessobject->events->add->customAction("onCustomActionCTC");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultCTC");
$page->businessobject->events->add->afterDefault("onAfterDefaultCTC");

//$page->businessobject->events->add->prepareAdd("onPrepareAddCTC");
//$page->businessobject->events->add->beforeAdd("onBeforeAddCTC");
//$page->businessobject->events->add->afterAdd("onAfterAddCTC");
//$page->businessobject->events->add->prepareEdit("onPrepareEditCTC");
//$page->businessobject->events->add->beforeEdit("onBeforeEditCTC");
//$page->businessobject->events->add->afterEdit("onAfterEditCTC");
//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateCTC");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateCTC");
$page->businessobject->events->add->afterUpdate("onAfterUpdateCTC");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteCTC");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteCTC");
//$page->businessobject->events->add->afterDelete("onAfterDeleteCTC");
$appdata = array();
include_once("../Addons/GPS/LGU Add-On/UserProcs/sls/u_lguposterminalseries.php");

function onCustomActionCTC($action) {
    return true;
}

function onBeforeDefaultCTC() {
    global $page;
    global $appdata;
    //  $appdata["docno"] = $page->getitemstring("docno");
    $appdata["u_apptype"] = $page->getitemstring("u_apptype");
    return true;
}

function onAfterDefaultCTC() {
    global $objConnection;
    global $page;
    global $objGrids;
    global $appdata;

    $autoseries = 1;
    $autopost = 0;
    $lineid = 0;

    $page->setitem("u_docdate", currentdate());
    $page->setitem("u_ordate", currentdate());
    $page->setitem("u_apptype", "I");
    $page->setitem("u_citizenship", "Filipino");
    $page->setitem("u_userid", $_SESSION["userid"]);

    $objRs2 = new recordset(null, $objConnection);
    $objRs2->queryopen("select CODE,U_TERMINALID,U_USERID from U_LGUPOSREGISTERS where BRANCH='" . $_SESSION["branch"] . "' AND  U_USERID ='" . $_SESSION["userid"] . "' and U_STATUS='O'");
    if ($objRs2->queryfetchrow("NAME")) {
        
    } else {
        header('Location: accessdenied.php?&requestorId=' . $page->getvarstring("requestorId") . '&messageText=Cash Register is currently closed.');
        return;
    }

    $objRs = new recordset(null, $objConnection);
    $objRs->queryopen("SELECT A.CODE, B.U_AUTOSERIES,B.U_ISSUEDOCNO,B.DOCNO AS U_SERIESDOCNO,B.U_DOCSERIES,B.U_DOCSERIESNAME  FROM U_LGUPOSREGISTERS A
                            INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID
                            LEFT JOIN U_LGUFORMS C ON B.U_DOCSERIESNAME = C.CODE
                            WHERE A.BRANCH='" . $_SESSION["branch"] . "' AND A.COMPANY = '" . $_SESSION["company"] . "' AND A.U_USERID='" . $_SESSION['userid'] . "' AND A.U_STATUS = 'O' AND C.U_ISCTC = 1 AND B.U_NEXTNO <= B.U_LASTNO AND B.U_DOCSERIESNAME IN ('AF 0016','AF 0017')  ORDER BY B.U_ISDEFAULT DESC");
    if ($objRs->queryfetchrow("NAME")) {
        $autoseries = $objRs->fields["U_AUTOSERIES"];
        $seriesdocno = $objRs->fields["U_SERIESDOCNO"];
        $series = $objRs->fields["U_DOCSERIESNAME"];
        $docseries = $objRs->fields["U_DOCSERIES"];
        $issuedocno = $objRs->fields["U_ISSUEDOCNO"];
        if ($series == "AF 0016") $page->setitem("u_apptype", "I");
        else if ($series == "AF 0017") $page->setitem("u_apptype", "C");
        else $page->setitem("u_apptype", "");
    } else {
        header('Location: accessdenied.php?&requestorId=' . $page->getvarstring("requestorId") . '&messageText=No Terminal/Series maintained for USER [' . $_SESSION['userid'] . '].');
        return;
    }

    if ($autoseries == 1) {
        $obju_POSTerminals = new masterdataschema_br(null, $objConnection, "u_lguposterminals");
        $obju_TerminalSeries = new documentschema_br(null, $objConnection, "u_terminalseries");
//		$obju_POSTerminalSeries = new masterdatalinesschema_br(null,$objConnection,"u_lguposterminalseries");
        if ($obju_TerminalSeries->getbykey($seriesdocno)) {
            $numlen = $obju_TerminalSeries->getudfvalue("u_numlen");
            $prefix = $obju_TerminalSeries->getudfvalue("u_prefix");
            $suffix = $obju_TerminalSeries->getudfvalue("u_suffix");
            $nextno = $obju_TerminalSeries->getudfvalue("u_nextno");
            $lastno = $obju_TerminalSeries->getudfvalue("u_lastno");

            $prefix = str_replace("{POS}", $page->getitemstring("u_terminalid"), $prefix);
            $suffix = str_replace("{POS}", $page->getitemstring("u_terminalid"), $suffix);
            $prefix = str_replace("[m]", str_pad(date('m'), 2, "0", STR_PAD_LEFT), $prefix);
            $suffix = str_replace("[m]", str_pad(date('m'), 2, "0", STR_PAD_LEFT), $suffix);
            $prefix = str_replace("[Y]", date('Y'), $prefix);
            $suffix = str_replace("[Y]", date('Y'), $suffix);
            $prefix = str_replace("[y]", str_pad(substr(date('Y'), 2), 2, "0", STR_PAD_LEFT), $prefix);
            $suffix = str_replace("[y]", str_pad(substr(date('Y'), 2), 2, "0", STR_PAD_LEFT), $suffix);

            $docno = $prefix . str_pad($nextno, $numlen, "0", STR_PAD_LEFT) . $suffix;
            $page->setitem("u_seriesdocno", $series);
            $page->setitem("u_docseries", $docseries);
            $page->setitem("u_issuedocno", $issuedocno);
            $page->setitem("u_orno", $docno);
        }
    } else {
        $page->setitem("docseries", -1);
        $page->setitem("docno", "");
        $page->setitem("u_date", "");
    }
    return true;
}

function onPrepareAddCTC(&$override) {
    return true;
}

function onBeforeAddCTC() {
    return true;
}

function onAfterAddCTC() {
    return true;
}

function onPrepareEditCTC(&$override) {
    return true;
}

function onBeforeEditCTC() {
    return true;
}

function onAfterEditCTC() {
    return true;
}

function onPrepareUpdateCTC(&$override) {
    return true;
}

function onBeforeUpdateCTC() {
    return true;
}

function onAfterUpdateCTC() {
    return true;
}

function onPrepareDeleteCTC(&$override) {
    return true;
}

function onBeforeDeleteCTC() {
    return true;
}

function onAfterDeleteCTC() {
    return true;
}

$objGrids[0]->columncfl("u_feecode", "OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc", "OpenCFLfs()");
//$objGrids[0]->dataentry = false;

$page->businessobject->items->setcfl("u_checkdate", "Calendar");
$page->businessobject->items->setcfl("u_ordate", "Calendar");
$page->businessobject->items->setcfl("u_dateofbirth", "Calendar");
$page->businessobject->items->setcfl("u_docdate", "Calendar");
$page->businessobject->items->seteditable("u_docdate", false);
$page->businessobject->items->seteditable("u_ordate", false);
$page->businessobject->items->seteditable("u_orno", false);
$page->businessobject->items->seteditable("u_checkno", false);
$page->businessobject->items->seteditable("u_checkdate", false);
$page->businessobject->items->seteditable("u_checkbank", false);
$page->businessobject->items->seteditable("docseries", false);
$page->businessobject->items->seteditable("docstatus", false);
$page->businessobject->items->seteditable("u_docseries", false);
$addoptions = false;
$page->toolbar->setaction("add", false);
$page->toolbar->setaction("navigation", false);
$page->toolbar->setaction("update", false);
$pageHeader = "Community Tax Application";
?> 

