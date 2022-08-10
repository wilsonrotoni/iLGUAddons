<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUReceipts");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUReceipts");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUReceipts");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUReceipts");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUReceipts");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUReceipts");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUReceipts");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUReceipts");
$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUReceipts");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUReceipts");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUReceipts");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUReceipts");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUReceipts");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUReceipts");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUReceipts");

function onCustomActionGPSLGUReceipts($action) {
	return true;
}

function onBeforeDefaultGPSLGUReceipts() { 
	return true;
}

function onAfterDefaultGPSLGUReceipts() { 
       
	return true;
}

function onPrepareAddGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeAddGPSLGUReceipts() { 
	return true;
}

function onAfterAddGPSLGUReceipts() { 
	return true;
}

function onPrepareEditGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeEditGPSLGUReceipts() { 
	return true;
}

function onAfterEditGPSLGUReceipts() { 
        global $objConnection;
	global $page;
	global $objGridB;
        
	$objGridB->clear();
        
        $objRs = new recordset(null,$objConnection);
        $receiptonhand=0;
//        $objRs->queryopen("SELECT B.CREATEDBY,A.U_FORM,B.U_STATUS,B.DOCNO,A.U_RECEIPTFRM,A.U_RECEIPTTO,A.U_NOOFRECEIPT,(A.U_AVAILABLE - COUNT(B.DOCNO)) AS AVAILABLE,SUM(B.U_PAIDAMOUNT) AS COLLECTEDAMOUNT
//                            FROM U_LGURECEIPTCASHIERISSUE A
//                            LEFT JOIN U_LGUPOS B ON B.DOCNO BETWEEN A.U_RECEIPTFRM AND A.U_RECEIPTTO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH
//                            LEFT JOIN U_LGUFORMS C ON A.U_FORM = C.CODE AND A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH
//                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_CASHIER = '".$page->getitemstring("code")."'  AND B.U_PROFITCENTER = 'RP' AND C.U_PROFITCENTER = 'RP' AND B.U_STATUS NOT IN ('CN','D')
//                            GROUP BY A.DOCNO
//                            UNION ALL
//                            SELECT B.CREATEDBY,A.U_FORM,B.U_STATUS,B.DOCNO,A.U_RECEIPTFRM,A.U_RECEIPTTO,A.U_NOOFRECEIPT,(A.U_AVAILABLE - COUNT(B.DOCNO)) AS AVAILABLE,SUM(B.U_PAIDAMOUNT)  AS COLLECTEDAMOUNT
//                            FROM U_LGURECEIPTCASHIERISSUE A
//                            LEFT JOIN U_LGUPOS B ON B.DOCNO BETWEEN A.U_RECEIPTFRM AND A.U_RECEIPTTO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH
//                            LEFT JOIN U_LGUFORMS C ON A.U_FORM = C.CODE AND A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH
//                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_CASHIER = '".$page->getitemstring("code")."' AND B.U_PROFITCENTER != 'RP' AND C.U_PROFITCENTER != 'RP'  AND B.U_STATUS NOT IN ('CN','D')
//                            GROUP BY A.DOCNO
//                            ORDER BY AVAILABLE DESC");
        $objRs->queryopen("SELECT AA.COMPANY,AA.BRANCH,AA.U_CASHIER,AA.BOOKLET,B.U_FORM,B.U_RECEIPTFRM,B.U_RECEIPTTO,AA.U_ISSUEDOCNO,beginqty,issueqty,useqty,returnqty,endqty,COLLECTEDAMT FROM (
                                SELECT COMPANY,BRANCH,A.U_CASHIER,A.BOOKLET,A.U_ISSUEDOCNO,sum(A.beginqty) AS beginqty,sum(A.issueqty) AS issueqty ,sum(A.useqty) AS useqty,sum(A.returnqty) AS returnqty,sum(A.endqty) AS endqty,SUM(COLLECTEDAMT) AS COLLECTEDAMT FROM(
                                  SELECT COMPANY,BRANCH,u_cashier,U_DOCSERIES as bookLet,DOCNO AS U_ISSUEDOCNO,
                                    sum(u_available) as beginqty,
                                    sum(u_available) as issueqty,
                                    0 as useqty,
                                    0 as returnqty,
                                    sum(u_available) as endqty,
                                    0 AS COLLECTEDAMT
                                  FROM U_LGURECEIPTCASHIERISSUE
                                  WHERE U_CASHIER = '".$page->getitemstring("code")."'  AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' GROUP BY u_cashier,bookLet,DOCNO
                                  UNION ALL
                                  SELECT COMPANY,BRANCH,CREATEDBY,U_DOCSERIES as bookLet,U_ISSUEDOCNO,
                                    0 as beginqty,
                                    0 as issueqty,
                                    sum(1) as useqty,
                                    0 as returnqty,
                                    sum(-1) as endqty,
                                    sum(U_PAIDAMOUNT) AS COLLECTEDAMT FROM U_LGUPOS
                                    WHERE CREATEDBY = '".$page->getitemstring("code")."' AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' GROUP BY CREATEDBY,bookLet,U_ISSUEDOCNO
                                  UNION ALL
                                  SELECT COMPANY,BRANCH,u_cashier,U_DOCSERIES as bookLet,U_ISSUEDOCNO,
                                    0 as beginqty,
                                    0 as issueqty,
                                    0 as useqty,
                                    sum(u_quantity) as returnqty,
                                    sum(u_quantity * -1) as endqty,
                                    0 AS COLLECTEDAMT
                                  FROM U_LGURECEIPTCASHIERRETURN
                                  WHERE  U_CASHIER = '".$page->getitemstring("code")."' AND COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' GROUP BY u_cashier,bookLet,U_ISSUEDOCNO ORDER BY U_ISSUEDOCNO) AS A GROUP BY u_cashier,bookLet,U_ISSUEDOCNO )  AS AA
                            INNER JOIN U_LGURECEIPTITEMS B ON AA.BOOKLET = B.U_REFNO AND AA.COMPANY = B.COMPANY AND AA.BRANCH = B.BRANCH");
        
        while ($objRs->queryfetchrow("NAME")) {
            
		$objGridB->addrow();
		$objGridB->setitem(null,"u_form",$objRs->fields["U_FORM"]);
		$objGridB->setitem(null,"u_receiptfrm",$objRs->fields["U_RECEIPTFRM"]);
		$objGridB->setitem(null,"u_receiptto",$objRs->fields["U_RECEIPTTO"]);
		$objGridB->setitem(null,"u_receiptcnt",$objRs->fields["issueqty"]);
		$objGridB->setitem(null,"u_usedqty",$objRs->fields["useqty"]);
		$objGridB->setitem(null,"u_returnqty",$objRs->fields["returnqty"]);
		$objGridB->setitem(null,"u_available",$objRs->fields["endqty"]);
		$objGridB->setitem(null,"u_amount",formatNumericAmount($objRs->fields["COLLECTEDAMT"]));
		$receiptonhand+=$objRs->fields["endqty"];
		
	}
        $page->setitem("u_totalreceipts",$receiptonhand);
	return true;
}

function onPrepareUpdateGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUReceipts() { 
	return true;
}

function onAfterUpdateGPSLGUReceipts() { 
	return true;
}

function onPrepareDeleteGPSLGUReceipts(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUReceipts() { 
	return true;
}

function onAfterDeleteGPSLGUReceipts() { 
	return true;
}

$page->businessobject->items->setcfl("code","OpenCFLfs()");
$page->businessobject->items->seteditable("u_totalreceipts",false);
$page->businessobject->items->seteditable("name",false);

//
//
//$objGridA = new grid("T101");
//$objGridA->addcolumn("u_form");
//$objGridA->addcolumn("u_receiptfrm");
//$objGridA->addcolumn("u_receiptto");
//$objGridA->addcolumn("u_receiptcnt");
//$objGridA->addcolumn("u_amount");
//$objGridA->columntitle("u_form","Form Type");
//$objGridA->columntitle("u_receiptfrm","Receipt From");
//$objGridA->columntitle("u_receiptto","Receipt To");
//$objGridA->columntitle("u_receiptcnt","No of Receipt");
//$objGridA->columntitle("u_amount","Collected Amount");
//$objGridA->columnwidth("u_form",20);
//$objGridA->columnwidth("u_receiptfrm",20);
//$objGridA->columnwidth("u_receiptto",20);
//$objGridA->columnwidth("u_receiptcnt",11);
//$objGridA->columnwidth("u_amount",11);
//$objGridA->columnalignment("u_form","left");
//$objGridA->columnalignment("u_receiptfrm","left");
//$objGridA->columnalignment("u_receiptto","left");
//$objGridA->showactionbar = false;
//$objGridA->setaction("reset",false);
//$objGridA->setaction("add",false);
//$objGridA->height = 400;

$objGridB = new grid("T102");
$objGridB->addcolumn("u_form");
$objGridB->addcolumn("u_receiptfrm");
$objGridB->addcolumn("u_receiptto");
$objGridB->addcolumn("u_receiptcnt");
$objGridB->addcolumn("u_usedqty");
$objGridB->addcolumn("u_returnqty");
$objGridB->addcolumn("u_available");
$objGridB->addcolumn("u_amount");
$objGridB->columntitle("u_form","Accountable Form");
$objGridB->columntitle("u_receiptfrm","Receipt From");
$objGridB->columntitle("u_receiptto","Receipt To");
$objGridB->columntitle("u_receiptcnt","Issued Qty");
$objGridB->columntitle("u_usedqty","Used Qty");
$objGridB->columntitle("u_available","Available Qty");
$objGridB->columntitle("u_returnqty","Returned Qty");
$objGridB->columntitle("u_amount","Collected Amount");
$objGridB->columnwidth("u_form",20);
$objGridB->columnwidth("u_receiptfrm",15);
$objGridB->columnwidth("u_receiptto",15);
$objGridB->columnwidth("u_receiptcnt",11);
$objGridB->columnwidth("u_usedqty",11);
$objGridB->columnwidth("u_available",11);
$objGridB->columnwidth("u_returnqty",11);
$objGridB->columnwidth("u_amount",15);
$objGridB->columnalignment("u_form","left");
$objGridB->columnalignment("u_receiptfrm","left");
$objGridB->columnalignment("u_receiptto","left");
$objGridB->columnalignment("u_receiptcnt","right");
$objGridB->columnalignment("u_usedqty","right");
$objGridB->columnalignment("u_available","right");
$objGridB->columnalignment("u_returnqty","right");
$objGridB->columnalignment("u_amount","right");
//$objGridA->columnvisibility("docno",false);
$objGridB->dataentry = false;
$objGridB->showactionbar = false;
$objGridB->setaction("reset",false);
$objGridB->setaction("add",false);
$objGridB->width = 1200;

$objGridC = new grid("T103");
$objGridC->addcolumn("u_form");
$objGridC->addcolumn("u_receiptfrm");
$objGridC->addcolumn("u_receiptto");
$objGridC->addcolumn("u_quantity");
$objGridC->columntitle("u_form","Accountable Form");
$objGridC->columntitle("u_receiptfrm","Receipt From");
$objGridC->columntitle("u_receiptto","Receipt To");
$objGridC->columntitle("u_quantity","Quantity");
$objGridC->columnwidth("u_form",20);
$objGridC->columnwidth("u_receiptfrm",15);
$objGridC->columnwidth("u_receiptto",15);
$objGridC->columnwidth("u_quantity",11);
$objGridC->columnalignment("u_form","left");
$objGridC->columnalignment("u_receiptfrm","left");
$objGridC->columnalignment("u_receiptto","left");
$objGridC->columnalignment("u_quantity","right");
//$objGridA->columnvisibility("docno",false);
$objGridC->dataentry = false;
$objGridC->showactionbar = false;
$objGridC->setaction("reset",false);
$objGridC->setaction("add",false);
$objGridC->width = 900;

$addoptions = false;
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
//$page->businessobject->items->setcfl("u_purchaseddate","Calendar");
//$objGrids[0]->columnvisibility("u_issuedto",false);
//$objGrids[0]->columnwidth("u_form",15);
$pageHeader = "Issue/Return Receipts";
?> 

