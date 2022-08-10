<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSPOSAddon");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSPOSAddon");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSPOSAddon");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSPOSAddon");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSPOSAddon");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSPOSAddon");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSPOSAddon");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSPOSAddon");
$page->businessobject->events->add->afterEdit("onAfterEditGPSPOSAddon");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSPOSAddon");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSPOSAddon");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSPOSAddon");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSPOSAddon");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSPOSAddon");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSPOSAddon");

function onCustomActionGPSPOSAddon($action) {
	return true;
}

function onBeforeDefaultGPSPOSAddon() { 
	return true;
}

function onAfterDefaultGPSPOSAddon() { 
	return true;
}

function onPrepareAddGPSPOSAddon(&$override) { 
	return true;
}

function onBeforeAddGPSPOSAddon() { 
	return true;
}

function onAfterAddGPSPOSAddon() { 
	return true;
}

function onPrepareEditGPSPOSAddon(&$override) { 
	return true;
}

function onBeforeEditGPSPOSAddon() { 
	return true;
}

function onAfterEditGPSPOSAddon() {
        global $objConnection;
	global $page;
	global $objGridA;
        
	$objGridA->clear();
        
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
        $objRs->queryopen("SELECT A.DOCNO,A.U_CASHIER,A.U_DOCSERIESNAME,A.U_STARTNO,A.U_LASTNO,A.U_NEXTNO,A.U_ISDEFAULT,A.U_NUMLEN,A.U_PREFIX,A.U_SUFFIX,A.U_NOOFLINES,A.U_AUTOSERIES
                            FROM U_TERMINALSERIES A
                            WHERE A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.U_CASHIER = '".$page->getitemstring("code")."' and A.U_NEXTNO < A.U_LASTNO ");
        
        while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridA->setitem(null,"u_isdefault",$objRs->fields["U_ISDEFAULT"]);
		$objGridA->setitem(null,"u_cashierid",$objRs->fields["U_CASHIER"]);
		$objGridA->setitem(null,"u_series",$objRs->fields["U_DOCSERIESNAME"]);
		$objGridA->setitem(null,"u_numlen",$objRs->fields["U_NUMLEN"]);
		$objGridA->setitem(null,"u_prefix",$objRs->fields["U_PREFIX"]);
		$objGridA->setitem(null,"u_startno",$objRs->fields["U_STARTNO"]);
		$objGridA->setitem(null,"u_nextno",$objRs->fields["U_NEXTNO"]);
		$objGridA->setitem(null,"u_lastno",$objRs->fields["U_LASTNO"]);
		$objGridA->setitem(null,"u_suffix",$objRs->fields["U_SUFFIX"]);
		$objGridA->setitem(null,"u_autoseries",$objRs->fields["U_AUTOSERIES"]);
		$objGridA->setitem(null,"u_nooflines",$objRs->fields["U_NOOFLINES"]);
		
	}
	return true;
}

function onPrepareUpdateGPSPOSAddon(&$override) { 
	return true;
}

function onBeforeUpdateGPSPOSAddon() { 
	return true;
}

function onAfterUpdateGPSPOSAddon() { 
	return true;
}

function onPrepareDeleteGPSPOSAddon(&$override) { 
	return true;
}

function onBeforeDeleteGPSPOSAddon() { 
	return true;
}

function onAfterDeleteGPSPOSAddon() { 
	return true;
}


$page->businessobject->items->setcfl("code","OpenCFLfs()");

$objGridA = new grid("T101");
$objGridA->addcolumn("docno");
$objGridA->addcolumn("u_isdefault");
$objGridA->addcolumn("u_cashierid");
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

$objGridA->columntitle("docno","Docno");
$objGridA->columntitle("u_isdefault","Default");
$objGridA->columntitle("u_cashierid","Cashier Id");
$objGridA->columntitle("u_series","Series Name");
$objGridA->columntitle("u_numlen","Digits");
$objGridA->columntitle("u_prefix","Prefix");
$objGridA->columntitle("u_startno","Start No");
$objGridA->columntitle("u_nextno","Next No");
$objGridA->columntitle("u_lastno","Last No");
$objGridA->columntitle("u_suffix","Suffix");
$objGridA->columntitle("u_autoseries","Auto Series");
$objGridA->columntitle("u_nooflines","No of Lines");
//$objGridA->columntitle("edit","");

$objGridA->columnwidth("u_isdefault",6);
$objGridA->columnwidth("u_cashierid",20);
$objGridA->columnwidth("u_series",30);
$objGridA->columnwidth("u_numlen",6);
$objGridA->columnwidth("u_prefix",15);
$objGridA->columnwidth("u_startno",11);
$objGridA->columnwidth("u_nextno",11);
$objGridA->columnwidth("u_lastno",11);
$objGridA->columnwidth("u_suffix",15);
$objGridA->columnwidth("u_autoseries",11);
$objGridA->columnwidth("u_nooflines",11);
//$objGridA->columnwidth("edit",5);

$objGridA->columnalignment("u_numlen","right");
$objGridA->columnalignment("u_startno","right");
$objGridA->columnalignment("u_nextno","right");
$objGridA->columnalignment("u_lastno","right");
$objGridA->columnalignment("u_nooflines","right");
$objGridA->columnvisibility("docno",false);

//$objGridA->columndataentry("u_kind","type","label");
//$objGridA->columndataentry("u_totalnumber","type","label");
//$objGridA->columndataentry("u_unitvalue","type","label");
//$objGridA->columndataentry("u_basevalue","type","label");
//$objGridA->columnvisibility("docno",false);
$objGridA->dataentry = false;
$objGridA->showactionbar = false;
$objGridA->setaction("reset",false);
$objGridA->setaction("add",false);
//$objGridA->columninput("edit","type","link");
//$objGridA->columninput("edit","caption","[Edit]");
//$objGridA->columninput("edit","dataentrycaption","[Add]");

//$objGridA->height = 60;

$objGrids[0]->columncfl("u_lastno","OpenCFLfs()");
$objGrids[0]->columncfl("u_nextno","OpenCFLfs()");
$objGrids[0]->columncfl("u_cashier","OpenCFLfs()");
$objGrids[0]->columntitle("u_cashier","Cashier ID");
$objGrids[0]->columnwidth("u_cashier",20);
$objGrids[0]->columnwidth("u_lastno",11);
$objGrids[0]->columnwidth("u_nextno",11);
$objGrids[0]->columnwidth("u_numlen",6);
$objGrids[0]->columnwidth("u_autoseries",11);
$objGrids[0]->columnwidth("u_autopost",11);
$objGrids[0]->columndataentry("u_isdefault","type","checkbox");
$objGrids[0]->columndataentry("u_isdefault","value",1);
$objGrids[0]->columndataentry("u_daily","type","checkbox");
$objGrids[0]->columndataentry("u_daily","value",1);
$objGrids[0]->columndataentry("u_autoseries","type","checkbox");
$objGrids[0]->columndataentry("u_autoseries","value",1);

$addoptions = false;

$page->toolbar->setaction("navigation",false);
$pageHeader = "Cashier/Terminal-Series";
?> 

