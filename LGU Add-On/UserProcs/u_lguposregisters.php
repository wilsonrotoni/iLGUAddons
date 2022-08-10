<?php

//$page->businessobject->events->add->customAction("onCustomActionGPSLGU");
//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGU");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGU");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGU");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGU");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGU");
//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGU");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGU");
$page->businessobject->events->add->afterEdit("onAfterEditGPSLGU");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGU");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGU");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGU");
//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGU");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGU");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGU");

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

    $objRs = new recordset(null, $objConnection);
    $objRs2 = new recordset(null, $objConnection);
    //$objRs->queryopen("select CODE, U_DAILY from U_LGUPOSTERMINALS where BRANCH='".$_SESSION["branch"]."' AND CODE='".$_SESSION['userid']."'");
    //if ($objRs->queryfetchrow("NAME")) {
    //$terminalid = $objRs->fields["CODE"];
    $objRs2->queryopen("select CODE,U_USERID from U_LGUPOSREGISTERS where BRANCH='" . $_SESSION["branch"] . "' AND U_USERID = '" . $_SESSION["userid"] . "' and U_STATUS='O'");
    if ($objRs2->queryfetchrow("NAME")) {
        $page->setkey($objRs2->fields["CODE"]);
        modeEdit();
        return false;
        //header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently opened by user ['.$objRs2->fields["U_USERID"].'].'); 
    } else {
        $page->setitem("code", currentdateDB() . "-" . date('H:i') . "-" . $_SESSION["userid"]);
        $page->setitem("name", currentdateDB() . "-" . date('H:i') . "-" . $_SESSION["userid"]);
//			$page->setitem("u_terminalid",$terminalid);
        $page->setitem("u_daily", $objRs->fields["U_DAILY"]);
        $page->setitem("u_userid", $_SESSION["userid"]);
        $page->setitem("u_date", currentdate());
        if ($objRs->fields["U_DAILY"] == "0") {
            $page->setitem("u_date", currentdate());
        } else {
            $objRs2->queryopen("select U_DATE from U_LGUPOSREGISTERS where BRANCH='" . $_SESSION["branch"] . "' AND  CODE='" . $_SESSION['userid'] . "' and U_STATUS='C' ORDER BY U_DATE DESC LIMIT 1");
            if ($objRs2->queryfetchrow("NAME")) {
                $page->setitem("u_date", formatDateToHttp(dateadd("d", 1, $objRs2->fields["U_DATE"])));
            }
        }
        $objRs2->queryopen("select U_CUTOFFTIME from  U_LGUSETUP  where BRANCH='" . $_SESSION["branch"] . "' AND COMPANY='" . $_SESSION["company"] . "' ");
        if ($objRs2->queryfetchrow("NAME")) {
            if (date('H:i') <= $objRs2->fields["U_CUTOFFTIME"]) {
                $page->setitem("u_postingdate", currentdate());
            } else {
                $page->setitem("u_postingdate", formatDateToHttp(dateadd("d", 1, formatDateTodb($page->getitemstring("u_date")))));
            }
        }
        $page->setitem("u_time", date('H:i'));
    }
    //} else {
//		header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=No Terminal/Series maintained for USER['.$_SESSION['userid'].'].'); 
    //}
    $objGrids[0]->clear();
    //$objGrids[1]->clear();
    $objRs->queryopen("select U_DENOMINATION from U_LGUPOSDENOMINATIONS ORDER BY U_DENOMINATION DESC");
    while ($objRs->queryfetchrow("NAME")) {
        $objGrids[0]->addrow();
        $objGrids[0]->setitem(null, "u_denomination", formatNumericAmount($objRs->fields["U_DENOMINATION"]));
        $objGrids[0]->setitem(null, "u_count", 0);

        //$objGrids[1]->addrow();
        //$objGrids[1]->setitem(null,"u_denomination",formatNumericAmount($objRs->fields["U_DENOMINATION"]));
        //$objGrids[1]->setitem(null,"u_count",0);
    }
//        $objGrids[3]->clear();
//        $objRs->queryopen("SELECT * FROM (SELECT A.U_CASHIER,A.U_FORM,A.U_RECEIPTFRM,A.U_RECEIPTTO,A.U_NOOFRECEIPT,(A.U_AVAILABLE - COUNT(B.DOCNO)) AS AVAILABLE,SUM(B.U_PAIDAMOUNT) AS COLLECTEDAMOUNT
//                            FROM U_LGURECEIPTCASHIERISSUE A
//                            LEFT JOIN U_LGUPOS B ON B.DOCNO BETWEEN A.U_RECEIPTFRM AND A.U_RECEIPTTO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.U_CASHIER = B.CREATEDBY  AND B.U_STATUS NOT IN ('CN','D')
//                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_CASHIER = '".$page->getitemstring("u_userid")."'
//                            GROUP BY A.DOCNO
//                            ORDER BY U_DATEISSUED,AVAILABLE DESC)
//                            AS X
//                           WHERE AVAILABLE > 0");
//	while ($objRs->queryfetchrow("NAME")) {
//		$objGrids[3]->addrow();
//		$objGrids[3]->setitem(null,"u_form",$objRs->fields["U_FORM"]);
//		$objGrids[3]->setitem(null,"u_receiptfrm",$objRs->fields["U_RECEIPTFRM"]);
//		$objGrids[3]->setitem(null,"u_receiptto",$objRs->fields["U_RECEIPTTO"]);
//		$objGrids[3]->setitem(null,"u_noofreceipt",$objRs->fields["U_NOOFRECEIPT"]);
//		$objGrids[3]->setitem(null,"u_available",$objRs->fields["AVAILABLE"]);
//
//	}
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
    global $objConnection;
    global $page;
    global $objGrids;

    global $objGridA;


    $objRs = new recordset(null, $objConnection);
    $objGridA->clear();
    $objRs->queryopen("SELECT A.DOCNO,A.U_CASHIER,A.U_DOCSERIESNAME,A.U_STARTNO,A.U_LASTNO,A.U_NEXTNO,A.U_ISDEFAULT,A.U_NUMLEN,A.U_PREFIX,A.U_SUFFIX,A.U_NOOFLINES,A.U_AUTOSERIES
                                FROM U_TERMINALSERIES A
                                WHERE A.COMPANY = '" . $_SESSION["company"] . "' AND A.BRANCH = '" . $_SESSION["branch"] . "' AND A.U_CASHIER = '" . $page->getitemstring("u_userid") . "' and U_REGISTERID = '" . $page->getitemstring("code") . "' ");

    while ($objRs->queryfetchrow("NAME")) {
        $objGridA->addrow();
        $objGridA->setitem(null, "docno", $objRs->fields["DOCNO"]);
        $objGridA->setitem(null, "u_isdefault", $objRs->fields["U_ISDEFAULT"]);
        $objGridA->setitem(null, "u_cashierid", $objRs->fields["U_CASHIER"]);
        $objGridA->setitem(null, "u_series", $objRs->fields["U_DOCSERIESNAME"]);
        $objGridA->setitem(null, "u_numlen", $objRs->fields["U_NUMLEN"]);
        $objGridA->setitem(null, "u_prefix", $objRs->fields["U_PREFIX"]);
        $objGridA->setitem(null, "u_startno", $objRs->fields["U_STARTNO"]);
        $objGridA->setitem(null, "u_nextno", $objRs->fields["U_NEXTNO"]);
        $objGridA->setitem(null, "u_lastno", $objRs->fields["U_LASTNO"]);
        $objGridA->setitem(null, "u_suffix", $objRs->fields["U_SUFFIX"]);
        $objGridA->setitem(null, "u_autoseries", $objRs->fields["U_AUTOSERIES"]);
        $objGridA->setitem(null, "u_nooflines", $objRs->fields["U_NOOFLINES"]);
    }

    if ($page->getitemstring("u_status") == "O") {



        $objGrids[1]->clear();
        $objRs->queryopen("select U_DENOMINATION from U_LGUPOSDENOMINATIONS ORDER BY U_DENOMINATION DESC");
        while ($objRs->queryfetchrow("NAME")) {

            $objGrids[1]->addrow();
            $objGrids[1]->setitem(null, "u_denomination", formatNumericAmount($objRs->fields["U_DENOMINATION"]));
            $objGrids[1]->setitem(null, "u_count", 0);
        }

//                $objGrids[4]->clear();
//                $objRs->queryopen("SELECT * FROM (SELECT A.U_TIME,A.U_DATEISSUED,A.U_CASHIER,A.U_FORM,A.U_RECEIPTFRM,A.U_RECEIPTTO,A.U_NOOFRECEIPT,(A.U_AVAILABLE - COUNT(B.DOCNO)) AS AVAILABLE,SUM(B.U_PAIDAMOUNT) AS COLLECTEDAMOUNT,COUNT(B.DOCNO) AS ISSUED
//                            FROM U_LGURECEIPTCASHIERISSUE A
//                            LEFT JOIN U_LGUPOS B ON B.DOCNO BETWEEN A.U_RECEIPTFRM AND A.U_RECEIPTTO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.U_CASHIER = B.CREATEDBY  AND B.U_STATUS NOT IN ('CN','D')
//                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_CASHIER = '".$page->getitemstring("u_userid")."'
//                            GROUP BY A.DOCNO
//                            ORDER BY U_DATEISSUED,AVAILABLE DESC )
//                                    AS X
//                            WHERE AVAILABLE > 0
//
//                            UNION ALL
//
//                            SELECT * FROM (SELECT A.U_TIME,A.U_DATEISSUED,A.U_CASHIER,A.U_FORM,A.U_RECEIPTFRM,A.U_RECEIPTTO,A.U_NOOFRECEIPT,(A.U_AVAILABLE - COUNT(B.DOCNO)) AS AVAILABLE,SUM(B.U_PAIDAMOUNT) AS COLLECTEDAMOUNT,COUNT(B.DOCNO) AS ISSUED
//                            FROM U_LGURECEIPTCASHIERISSUE A
//                            LEFT JOIN U_LGUPOS B ON B.DOCNO BETWEEN A.U_RECEIPTFRM AND A.U_RECEIPTTO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.U_CASHIER = B.CREATEDBY  AND B.U_STATUS NOT IN ('CN','D')
//                             WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_CASHIER = '".$page->getitemstring("u_userid")."'
//                            GROUP BY A.DOCNO
//                            ORDER BY U_DATEISSUED,AVAILABLE DESC )
//                                    AS X
//                            WHERE AVAILABLE <= 0 AND '".formatDateTodb($page->getitemstring("u_date"))."' >= X.U_DATEISSUED  AND '".formatTimeToDB($page->getitemstring("u_time"))."' <= X.U_TIME ");
//                
//                while ($objRs->queryfetchrow("NAME")) {
//                        $objGrids[4]->addrow();
//                        $objGrids[4]->setitem(null,"u_form",$objRs->fields["U_FORM"]);
//                        $objGrids[4]->setitem(null,"u_receiptfrm",$objRs->fields["U_RECEIPTFRM"]);
//                        $objGrids[4]->setitem(null,"u_receiptto",$objRs->fields["U_RECEIPTTO"]);
//                        $objGrids[4]->setitem(null,"u_noofreceipt",$objRs->fields["U_NOOFRECEIPT"]);
//                        $objGrids[4]->setitem(null,"u_available",$objRs->fields["AVAILABLE"]);
//
//                }

        $u_salesamount = 0;
        $u_cashamount = 0;
        //var_dump($page->getitemdate("u_date"));
        $u_cashdp = 0;
        $u_checkdp = 0;
        $u_creditcarddp = 0;
        $u_tfdp = 0;
        $dailyExp = "";
        if ($page->getitemstring("u_daily") == "1")
            $dailyExp = " AND DOCDATE='" . $page->getitemdate("u_date") . "'";
        $objRs->setdebug(true);
        /* $objRs->queryopen("select SUM(CASHAMOUNT) AS CASHAMOUNT,SUM(CHEQUEAMOUNT) AS CHEQUEAMOUNT, SUM(CREDITCARDAMOUNT) AS CREDITCARDAMOUNT, SUM(TFAMOUNT) AS TFAMOUNT  FROM COLLECTIONS WHERE COMPANY='".$_SESSION["company"]."' AND BRANCHCODE='".$_SESSION["branch"]."' AND COLLFOR='RS' AND U_POSSTATUS in ('O','') AND DOCSTAT IN ('O','C') $dailyExp");
          if ($objRs->queryfetchrow("NAME")) {
          $u_cashdp = $objRs->fields["CASHAMOUNT"];
          $u_checkdp = $objRs->fields["CHEQUEAMOUNT"];
          $u_creditcarddp = $objRs->fields["CREDITCARDAMOUNT"];
          $u_tfdp = $objRs->fields["TFAMOUNT"];
          } */
        $dailyExp = "";
        if ($page->getitemstring("u_daily") == "1")
            $dailyExp = " AND U_DATE='" . $page->getitemdate("u_date") . "'";
        $objRs->queryopen("select COUNT(*) AS SEQNOCOUNT, MIN(DOCNO) AS STARTSEQNO, MAX(DOCNO) AS ENDSEQNO, SUM(U_CASHAMOUNT) AS U_CASHAMOUNT,SUM(U_CHEQUEAMOUNT) AS U_CHEQUEAMOUNT,SUM(U_CREDITCARDAMOUNT) AS U_CREDITCARDAMOUNT,SUM(U_OTHERAMOUNT) AS U_OTHERAMOUNT,SUM(U_TFAMOUNT) AS U_TFAMOUNT,SUM(U_DPAMOUNT) AS U_DPAMOUNT,SUM(U_ARAMOUNT) AS U_ARAMOUNT FROM U_LGUPOS WHERE COMPANY='" . $_SESSION["company"] . "' AND BRANCH='" . $_SESSION["branch"] . "' AND CREATEDBY='" . $page->getitemstring("u_userid") . "' AND U_STATUS IN ('D','O') $dailyExp");
        /*
          $objRs->queryopen("select POSPAYCODE,CASHCARDNAME AS POSPAYDESC,POSPAYTYPE from CASHCARDS where POSPAYCODE<>'' AND POSPAYTYPE>=0
          UNION
          select POSPAYCODE,CREDITCARDNAME AS POSPAYDESC,1 AS POSPAYTYPE from CREDITCARDS where POSPAYCODE<>''
          ORDER BY CAST(POSPAYTYPE AS UNSIGNED INTEGER), POSPAYCODE");
          while ($objRs->queryfetchrow("NAME")) {
         */
        if ($objRs->queryfetchrow("NAME")) {
            $page->setitem("u_startseqno", $objRs->fields["STARTSEQNO"]);
            $page->setitem("u_endseqno", $objRs->fields["ENDSEQNO"]);
            $page->setitem("u_seqnocount", $objRs->fields["SEQNOCOUNT"]);
            if ($objRs->fields["U_CASHAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "Cash");
                $objGrids[2]->setitem(null, "u_paycodedesc", "Cash");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_CASHAMOUNT"] + $u_cashdp));
                $u_salesamount += $objRs->fields["U_CASHAMOUNT"];
                $u_cashamount = $objRs->fields["U_CASHAMOUNT"] + $u_cashdp;
                $u_cashdp = 0;
            }
            if ($objRs->fields["U_CHEQUEAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "Check");
                $objGrids[2]->setitem(null, "u_paycodedesc", "Check");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_CHEQUEAMOUNT"] + $u_checkdp));
                $u_salesamount += $objRs->fields["U_CHEQUEAMOUNT"];
                $u_checkdp = 0;
            }
            if ($objRs->fields["U_CREDITCARDAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "Credit Card");
                $objGrids[2]->setitem(null, "u_paycodedesc", "Credit Card");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_CREDITCARDAMOUNT"] + $u_creditcarddp));
                $u_salesamount += $objRs->fields["U_CREDITCARDAMOUNT"];
                $u_creditcarddp = 0;
            }
            if ($objRs->fields["U_OTHERAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "Other");
                $objGrids[2]->setitem(null, "u_paycodedesc", "Other");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_OTHERAMOUNT"]));
                $u_salesamount += $objRs->fields["U_OTHERAMOUNT"];
            }
            if ($objRs->fields["U_TFAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "Bank Transfer");
                $objGrids[2]->setitem(null, "u_paycodedesc", "Bank Transfer");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_TFAMOUNT"] + $u_tfdp));
                $u_salesamount += $objRs->fields["U_TFAMOUNT"];
                $u_tfdp = 0;
            }
            if ($objRs->fields["U_DPAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "Customer Deposit");
                $objGrids[2]->setitem(null, "u_paycodedesc", "Customer Deposit");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_DPAMOUNT"]));
                $u_salesamount += $objRs->fields["U_DPAMOUNT"];
            }
            if ($objRs->fields["U_ARAMOUNT"] != 0) {
                $objGrids[2]->addrow();
                $objGrids[2]->setitem(null, "u_paycode", "A/R");
                $objGrids[2]->setitem(null, "u_paycodedesc", "A/R");
                $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($objRs->fields["U_ARAMOUNT"]));
                $u_salesamount += $objRs->fields["U_ARAMOUNT"];
            }
        }
        $page->setitem("u_salesamount", formatNumericAmount($u_salesamount));
        $page->setitem("u_cashamount", formatNumericAmount($u_cashamount));
        if ($u_cashdp != 0) {
            $objGrids[2]->addrow();
            $objGrids[2]->setitem(null, "u_paycode", "Cash");
            $objGrids[2]->setitem(null, "u_paycodedesc", "Cash");
            $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($u_cashdp));
            $u_cashamount = $u_cashdp;
        }
        if ($u_checkdp != 0) {
            $objGrids[2]->addrow();
            $objGrids[2]->setitem(null, "u_paycode", "Check");
            $objGrids[2]->setitem(null, "u_paycodedesc", "Check");
            $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($u_checkdp));
        }
        if ($u_creditcarddp != 0) {
            $objGrids[2]->addrow();
            $objGrids[2]->setitem(null, "u_paycode", "Credit Card");
            $objGrids[2]->setitem(null, "u_paycodedesc", "Credit Card");
            $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($u_creditcarddp));
        }
        if ($u_tfdp != 0) {
            $objGrids[2]->addrow();
            $objGrids[2]->setitem(null, "u_paycode", "Bank Transfer");
            $objGrids[2]->setitem(null, "u_paycodedesc", "Bank Transfer");
            $objGrids[2]->setitem(null, "u_amount", formatNumericAmount($u_tfdp));
        }

        $page->setitem("u_closeamount", formatNumericAmount($page->getitemdecimal("u_openamount") + $page->getitemdecimal("u_cashamount")));
        $page->setitem("u_cashvariance", formatNumericAmount($page->getitemdecimal("u_closecashamount") - ($page->getitemdecimal("u_openamount") + $page->getitemdecimal("u_cashamount"))));
    }
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

$page->businessobject->items->seteditable("u_terminalid", false);
$page->businessobject->items->seteditable("u_userid", false);
//$page->businessobject->items->seteditable("u_date",false);
//$page->businessobject->items->seteditable("u_time",false);
$page->businessobject->items->seteditable("u_status", false);
$page->businessobject->items->seteditable("u_closedate", false);
$page->businessobject->items->seteditable("u_closetime", false);
$page->businessobject->items->seteditable("u_postingdate", false);
$page->businessobject->items->seteditable("u_isremitted", false);
$page->businessobject->items->seteditable("u_openamount", false);
$page->businessobject->items->seteditable("u_closeamount", false);
$page->businessobject->items->seteditable("u_salesamount", false);
$page->businessobject->items->seteditable("u_cashvariance", false);
$page->businessobject->items->seteditable("u_daily", false);


$page->businessobject->items->seteditable("u_startseqno", false);
$page->businessobject->items->seteditable("u_endseqno", false);
$page->businessobject->items->seteditable("u_seqnocount", false);



$objGridA = new grid("T101");
$objGridA->addcolumn("docno");
$objGridA->addcolumn("u_isdefault");
$objGridA->addcolumn("u_series");
$objGridA->addcolumn("u_numlen");
$objGridA->addcolumn("u_prefix");
$objGridA->addcolumn("u_startno");
$objGridA->addcolumn("u_nextno");
$objGridA->addcolumn("u_lastno");
$objGridA->addcolumn("u_suffix");
$objGridA->addcolumn("u_autoseries");
$objGridA->addcolumn("u_nooflines");
//$objGridA->addcolumn("edit");

$objGridA->columntitle("docno", "Docno");
$objGridA->columntitle("u_isdefault", "Default");
$objGridA->columntitle("u_series", "Series Name");
$objGridA->columntitle("u_numlen", "Digits");
$objGridA->columntitle("u_prefix", "Prefix");
$objGridA->columntitle("u_startno", "Start No");
$objGridA->columntitle("u_nextno", "Next No");
$objGridA->columntitle("u_lastno", "Last No");
$objGridA->columntitle("u_suffix", "Suffix");
$objGridA->columntitle("u_autoseries", "Auto Series");
$objGridA->columntitle("u_nooflines", "No of Lines");
//$objGridA->columntitle("edit","");

$objGridA->columnwidth("u_isdefault", 6);
$objGridA->columnwidth("u_series", 15);
$objGridA->columnwidth("u_numlen", 6);
$objGridA->columnwidth("u_prefix", 11);
$objGridA->columnwidth("u_startno", 11);
$objGridA->columnwidth("u_nextno", 11);
$objGridA->columnwidth("u_lastno", 11);
$objGridA->columnwidth("u_suffix", 11);
$objGridA->columnwidth("u_autoseries", 11);
$objGridA->columnwidth("u_nooflines", 11);
//$objGridA->columnwidth("edit",5);

$objGridA->columnalignment("u_numlen", "right");
$objGridA->columnalignment("u_startno", "right");
$objGridA->columnalignment("u_nextno", "right");
$objGridA->columnalignment("u_lastno", "right");
$objGridA->columnalignment("u_nooflines", "right");
$objGridA->columnvisibility("docno", false);

//$objGridA->columndataentry("u_kind","type","label");
//$objGridA->columndataentry("u_totalnumber","type","label");
//$objGridA->columndataentry("u_unitvalue","type","label");
//$objGridA->columndataentry("u_basevalue","type","label");
//$objGridA->columnvisibility("docno",false);
$objGridA->dataentry = false;
$objGridA->showactionbar = false;
$objGridA->setaction("reset", false);
$objGridA->setaction("add", false);

$objGrids[0]->columndataentry("u_count", "type", "label");
$objGrids[0]->columnattributes("u_denomination", "disabled");
$objGrids[0]->columnwidth("u_count", 10);
$objGrids[0]->columnwidth("u_denomination", 8);
$objGrids[0]->automanagecolumnwidth = true;
$objGrids[0]->setaction("add", false);
$objGrids[0]->setaction("reset", false);
$objGrids[0]->width = 200;

$objGrids[1]->columndataentry("u_count", "type", "label");
$objGrids[1]->columnattributes("u_denomination", "disabled");
$objGrids[1]->columnwidth("u_count", 10);
$objGrids[1]->columnwidth("u_denomination", 8);
$objGrids[1]->automanagecolumnwidth = true;
$objGrids[1]->setaction("add", false);
$objGrids[1]->setaction("reset", false);
$objGrids[1]->width = 200;

$objGrids[2]->columndataentry("u_paycodedesc", "type", "label");
$objGrids[2]->columnattributes("u_amount", "disabled");
$objGrids[2]->columnvisibility("u_paycode", false);
$objGrids[2]->columntitle("u_paycodedesc", "Payment Means");
$objGrids[2]->columnwidth("u_paycodedesc", 26);
$objGrids[2]->columnwidth("u_amount", 15);
$objGrids[2]->automanagecolumnwidth = false;
$objGrids[2]->setaction("add", false);
$objGrids[2]->setaction("reset", false);
$objGrids[2]->width = 360;

$objGrids[3]->columntitle("u_form", "Form");
$objGrids[3]->columntitle("u_noofreceipt", "Qty");
$objGrids[3]->columnwidth("u_form", 10);
$objGrids[3]->columnwidth("u_receiptfrm", 10);
$objGrids[3]->columnwidth("u_receiptto", 10);
$objGrids[3]->columnwidth("u_noofreceipt", 7);
$objGrids[3]->columnwidth("u_available", 7);
$objGrids[3]->automanagecolumnwidth = false;
$objGrids[3]->setaction("add", false);
$objGrids[3]->setaction("reset", false);
$objGrids[3]->width = 430;
$objGrids[3]->height = 200;

$objGrids[4]->columntitle("u_form", "Form");
$objGrids[4]->columntitle("u_noofreceipt", "Qty");
$objGrids[4]->columnwidth("u_form", 10);
$objGrids[4]->columnwidth("u_receiptfrm", 10);
$objGrids[4]->columnwidth("u_receiptto", 10);
$objGrids[4]->columnwidth("u_noofreceipt", 7);
$objGrids[4]->columnwidth("u_available", 7);
$objGrids[4]->automanagecolumnwidth = false;
$objGrids[4]->setaction("add", false);
$objGrids[4]->setaction("reset", false);
$objGrids[4]->width = 430;
//$objGrids[0]->dataentry = false;






$addoptions = false;
$page->toolbar->setaction("add", false);
$page->toolbar->setaction("new", false);
//$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("print",false);
$page->toolbar->setaction("find", false);
$page->toolbar->setaction("navigation", false);

//$page->setvar("AddonGoBack","u_Home");

$objMaster->reportaction = "QS";
?> 

