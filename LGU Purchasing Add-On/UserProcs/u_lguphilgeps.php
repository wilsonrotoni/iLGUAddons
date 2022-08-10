<?php
 

//$page->businessobject->events->add->customAction("onCustomActionLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultLGUPurchasing");
$page->businessobject->events->add->afterDefault("onAfterDefaultLGUPurchasing");

//$page->businessobject->events->add->prepareAdd("onPrepareAddLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddLGUPurchasing");

//$page->businessobject->events->add->prepareEdit("onPrepareEditLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditLGUPurchasing");
$page->businessobject->events->add->afterEdit("onAfterEditLGUPurchasing");

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
//        $page->setitem("u_duedate",currentdate());
        
        $objGrids[2]->clear();
	$objGrids[3]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name, u_position from u_lgubacmembers order by u_position ");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[3]->addrow();
		$objGrids[3]->setitem(null,"u_empname",$objRs->fields["name"]);
		$objGrids[3]->setitem(null,"u_position",$objRs->fields["u_position"]);
	}

	$objRs->queryopen("select code, name, u_venue from u_lgubacactivities order by u_seqno asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[2]->addrow();
		$objGrids[2]->setitem(null,"u_activity",$objRs->fields["code"]);
		$objGrids[2]->setitem(null,"u_venue",$objRs->fields["u_venue"]);
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
        global $page;
	global $objConnection;
	global $objGridA;
	global $objGridB;
	
	$objGridA->clear();
	$objGridB->clear();
	
	$objRs = new recordset(null,$objConnection);
//	$objRs->setdebug();
	$objRs->queryopen("SELECT DOCNO,U_TITLE,U_BARANGAY,U_ABC,U_BIDDOCSFEE,U_PREBIDDATE,U_DEADLINEDATE,U_OPENINGDATE,U_BSCASH,U_BSTOTAL FROM U_LGUBIDDOCINFRA  WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_PHILGEPSNO='".$page->getitemstring("docno")."'");
//	var_dump($objRs->sqls);
	
	while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridA->setitem(null,"u_title",$objRs->fields["U_TITLE"]);
		$objGridA->setitem(null,"u_barangay",$objRs->fields["U_BARANGAY"]);
		$objGridA->setitem(null,"u_abc",formatNumericAmount($objRs->fields["U_ABC"]));
		$objGridA->setitem(null,"u_biddocsfee",formatNumericAmount($objRs->fields["U_BIDDOCSFEE"]));
		$objGridA->setitem(null,"u_prebiddate",$objRs->fields["U_PREBIDDATE"]);
		$objGridA->setitem(null,"u_deadlinedate",$objRs->fields["U_DEADLINEDATE"]);
		$objGridA->setitem(null,"u_openingdate",$objRs->fields["U_OPENINGDATE"]);
		$objGridA->setitem(null,"u_bscash",formatNumericAmount($objRs->fields["U_BSCASH"]));
		$objGridA->setitem(null,"u_bstotal",formatNumericAmount($objRs->fields["U_BSTOTAL"]));
		$sqm+=$objRs->fields["U_SQM"];
		$basevalue+=$objRs->fields["U_BASEVALUE"];
	}
	$objRs = new recordset(null,$objConnection);
//	$objRs->setdebug();
	$objRs->queryopen("SELECT DOCNO,U_TITLE,U_EUIU,U_ABC,U_BIDDOCSFEE,U_PREBIDDATE,U_DEADLINEDATE,U_OPENINGDATE,U_BSCASH,U_BSTOTAL FROM U_LGUBIDDOCGOODS  WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_PHILGEPSNO='".$page->getitemstring("docno")."'");
//	var_dump($objRs->sqls);
	
	while ($objRs->queryfetchrow("NAME")) {
		$objGridB->addrow();
		$objGridB->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridB->setitem(null,"u_title",$objRs->fields["U_TITLE"]);
		$objGridB->setitem(null,"u_euiu",$objRs->fields["U_EUIU"]);
		$objGridB->setitem(null,"u_abc",formatNumericAmount($objRs->fields["U_ABC"]));
		$objGridB->setitem(null,"u_biddocsfee",formatNumericAmount($objRs->fields["U_BIDDOCSFEE"]));
		$objGridB->setitem(null,"u_prebiddate",$objRs->fields["U_PREBIDDATE"]);
		$objGridB->setitem(null,"u_deadlinedate",$objRs->fields["U_DEADLINEDATE"]);
		$objGridB->setitem(null,"u_openingdate",$objRs->fields["U_OPENINGDATE"]);
		$objGridB->setitem(null,"u_bscash",formatNumericAmount($objRs->fields["U_BSCASH"]));
		$objGridB->setitem(null,"u_bstotal",formatNumericAmount($objRs->fields["U_BSTOTAL"]));
		$sqm+=$objRs->fields["U_SQM"];
		$basevalue+=$objRs->fields["U_BASEVALUE"];
	}
//        $objGridA->setdefaultvalue("u_sqm",formatNumericMeasure($sqm));
//	$objGridA->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue));
//        
//        $totalnumber=0;
//	$basevalue2=0;
//        $objRs->queryopen("SELECT A.U_TAXABLE,B.U_PLANTTYPE, B.U_CLASS AS U_CLASSLEVEL, B.U_TOTALCOUNT, B.U_UNITVALUE, B.U_MARKETVALUE FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1D B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
//        while ($objRs->queryfetchrow("NAME")) {
//		$objGridD->addrow();
//		$objGridD->setitem(null,"u_kind",$objRs->fields["U_PLANTTYPE"]." - ".$objRs->fields["U_CLASSLEVEL"] );
//		$objGridD->setitem(null,"u_totalnumber",formatNumericMeasure($objRs->fields["U_TOTALCOUNT"]));
//		$objGridD->setitem(null,"u_unitvalue",formatNumericPrice($objRs->fields["U_UNITVALUE"]));
//		$objGridD->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_MARKETVALUE"]));
//		$totalnumber+=$objRs->fields["U_TOTALCOUNT"];
//		$basevalue2+=$objRs->fields["U_MARKETVALUE"];
//	}
//	$objGridD->setdefaultvalue("u_totalnumber",formatNumericMeasure($totalnumber));
//	$objGridD->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue2));
//	
//	$objRs->queryopen("SELECT A.U_BASEVALUE, B.U_ADJTYPE, B.U_ADJPERC, B.U_ADJVALUE FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1C B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
//	$adjvalue=0;
////	$adjmarketvalue=0;
//	while ($objRs->queryfetchrow("NAME")) {
//		$objGridB->addrow();
//		$objGridB->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]));
//		$objGridB->setitem(null,"u_adjfactor",$objRs->fields["U_ADJTYPE"]);
//		$objGridB->setitem(null,"u_adjperc",formatNumericPercent($objRs->fields["U_ADJPERC"]));
//		$objGridB->setitem(null,"u_adjvalue",formatNumericAmount($objRs->fields["U_ADJVALUE"]));
//		$objGridB->setitem(null,"u_marketvalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]+$objRs->fields["U_ADJVALUE"]));
//		$adjvalue+=$objRs->fields["U_ADJVALUE"];
////		$adjmarketvalue+=formatNumericAmount($objRs->fields["U_BASEVALUE"]+$objRs->fields["U_ADJVALUE"]);
//	}
//	$objGridB->setdefaultvalue("u_adjvalue",formatNumericAmount($adjvalue));
////	$objGridB->setdefaultvalue("u_marketvalue",formatNumericAmount($adjmarketvalue));
//	
//	$objRs->queryopen("SELECT A.U_TAXABLE,A.DOCNO, C.NAME AS U_ACTUALUSE, A.U_MARKETVALUE, A.U_ASSLVL, A.U_ASSVALUE FROM U_RPFAAS1A A LEFT JOIN U_RPACTUSES C ON C.CODE=A.U_ACTUALUSE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."' ");
//	$marketvalue=0;
//        $value = 0;
//	$assvalue=0;
//	while ($objRs->queryfetchrow("NAME")) {
//		$objGridC->addrow();
//		$objGridC->setitem(null,"docno",$objRs->fields["DOCNO"]);
//		$objGridC->setitem(null,"u_actualuse",$objRs->fields["U_ACTUALUSE"]);
//		$objGridC->setitem(null,"u_marketvalue",formatNumericAmount($objRs->fields["U_MARKETVALUE"]));
//		$objGridC->setitem(null,"u_asslvl",formatNumericPercent($objRs->fields["U_ASSLVL"]));
//                if($objRs->fields["U_TAXABLE"]==1){
//                    $value = $objRs->fields["U_ASSVALUE"];
//                }else{
//                    $value = 0;
//                }
//		$objGridC->setitem(null,"u_assvalue",formatNumericAmount($objRs->fields["U_ASSVALUE"]));
//		$marketvalue+=$objRs->fields["U_MARKETVALUE"];
//		$assvalue+=$value;
//	}
//	$objGridC->setdefaultvalue("u_marketvalue",formatNumericAmount($marketvalue));
//	$objGridC->setdefaultvalue("u_assvalue",formatNumericAmount($assvalue));
	
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

$page->businessobject->items->setcfl("u_profitcenter","OpenCFLfs()");
$page->businessobject->items->setcfl("u_profitcentername","OpenCFLfs()");
$page->businessobject->items->setcfl("u_requestedbyname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_reviewedby","OpenCFLfs()");
$page->businessobject->items->setcfl("u_approvedby","OpenCFLfs()");
$page->businessobject->items->setcfl("u_projcode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_prrefno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);

$objGrids[0]->columntitle("u_unitissue","UoM");
$objGrids[0]->columntitle("u_cost","Line Total");
$objGrids[0]->columntitle("u_openquantity","Remaining Qty.");
$objGrids[0]->columntitle("u_unitissue","UoM");

$objGrids[0]->columnvisibility("u_glacctno",false);
$objGrids[0]->columnvisibility("u_openquantity",false);
$objGrids[0]->columnvisibility("u_linestatus",false);
$objGrids[0]->columnvisibility("u_remarks",false);

$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_basetypenm",25);
$objGrids[0]->columnwidth("u_basedocno",25);
$objGrids[0]->columnwidth("u_openquantity",12);
$objGrids[0]->columnwidth("u_itemsubgroup",15);
$objGrids[0]->columnattributes("u_openquantity","disabled");

$objGrids[1]->columntitle("u_unitissue","UoM");
$objGrids[1]->columntitle("u_cost","Line Total");
$objGrids[1]->columntitle("u_unitissue","UoM");
$objGrids[1]->columnvisibility("u_openquantity",false);
$objGrids[1]->columnvisibility("u_linestatus",false);
$objGrids[1]->columnvisibility("u_remarks",false);
$objGrids[1]->columncfl("u_glacctno","OpenCFLfs()");

$objGrids[2]->columnwidth("u_date",15);
$objGrids[2]->columnwidth("u_activity",30);
$objGrids[2]->columncfl("u_date","Calendar");
$objGrids[2]->columninput("u_date","type","text");
$objGrids[2]->columninput("u_time","type","text");
$objGrids[2]->columninput("u_venue","type","text");
$objGrids[2]->automanagecolumnwidth = true;
$objGrids[2]->dataentry = false;
$objGrids[2]->height = 120;


$objGrids[3]->automanagecolumnwidth = true;
$objGrids[3]->dataentry = false;
$objGrids[3]->height = 120;
$objGrids[3]->columnwidth("u_selected",2);
$objGrids[3]->columninput("u_selected","type","checkbox");
$objGrids[3]->columninput("u_selected","value",1);



$objGridA = new grid("T101");
$objGridA->addcolumn("u_title");
$objGridA->addcolumn("u_barangay");
$objGridA->addcolumn("u_abc");
$objGridA->addcolumn("u_biddocsfee");
$objGridA->addcolumn("u_prebiddate");
$objGridA->addcolumn("u_deadlinedate");
$objGridA->addcolumn("u_openingdate");
$objGridA->addcolumn("u_bscash");
$objGridA->addcolumn("u_bstotal");
$objGridA->addcolumn("docno");
$objGridA->addcolumn("edit");

$objGridA->columntitle("u_title","Title");
$objGridA->columntitle("u_barangay","Barangay");
$objGridA->columntitle("u_abc","ABC");
$objGridA->columntitle("u_biddocsfee","BidDocs Fee");
$objGridA->columntitle("u_prebiddate","Pre Bid");
$objGridA->columntitle("u_deadlinedate","Deadline");
$objGridA->columntitle("u_openingdate","Opening");
$objGridA->columntitle("u_bscash","Bid Surety Cash");
$objGridA->columntitle("u_bstotal","Bid Surety Total");
$objGridA->columntitle("edit","");

$objGridA->columnwidth("u_title",30);
$objGridA->columnwidth("u_barangay",15);
$objGridA->columnwidth("u_abc",15);
$objGridA->columnwidth("u_biddocsfee",15);
$objGridA->columnwidth("u_prebiddate",11);
$objGridA->columnwidth("u_deadlinedate",11);
$objGridA->columnwidth("u_openingdate",11);
$objGridA->columnwidth("u_bscash",15);
$objGridA->columnwidth("u_bstotal",15);
$objGridA->columnwidth("edit",5);

$objGridA->columnalignment("u_abc","right");
$objGridA->columnalignment("u_biddocsfee","right");
$objGridA->columnalignment("u_bscash","right");
$objGridA->columnalignment("u_bstotal","right");


$objGridA->columndataentry("u_title","type","label");
$objGridA->columndataentry("u_barangay","type","label");
$objGridA->columndataentry("u_abc","type","label");
$objGridA->columndataentry("u_biddocsfee","type","label");
$objGridA->columndataentry("u_prebiddate","type","label");
$objGridA->columndataentry("u_deadlinedate","type","label");
$objGridA->columndataentry("u_openingdate","type","label");
$objGridA->columndataentry("u_bscash","type","label");
$objGridA->columndataentry("u_bstotal","type","label");
$objGridA->columnvisibility("docno",false);

$objGridA->dataentry = true;
$objGridA->showactionbar = false;
$objGridA->setaction("reset",false);
$objGridA->setaction("add",false);
$objGridA->height = 90;

$objGridA->columninput("edit","type","link");
$objGridA->columninput("edit","caption","[Edit]");
$objGridA->columninput("edit","dataentrycaption","[Add]");


$objGridB = new grid("T102");
$objGridB->addcolumn("u_title");
$objGridB->addcolumn("u_euiu");
$objGridB->addcolumn("u_abc");
$objGridB->addcolumn("u_biddocsfee");
$objGridB->addcolumn("u_prebiddate");
$objGridB->addcolumn("u_deadlinedate");
$objGridB->addcolumn("u_openingdate");
$objGridB->addcolumn("u_bscash");
$objGridB->addcolumn("u_bstotal");
$objGridB->addcolumn("docno");
$objGridB->addcolumn("edit");

$objGridB->columntitle("u_title","Title");
$objGridB->columntitle("u_euiu","EUIU");
$objGridB->columntitle("u_abc","ABC");
$objGridB->columntitle("u_biddocsfee","BidDocs Fee");
$objGridB->columntitle("u_prebiddate","Pre Bid");
$objGridB->columntitle("u_deadlinedate","Deadline");
$objGridB->columntitle("u_openingdate","Opening");
$objGridB->columntitle("u_bscash","Bid Surety Cash");
$objGridB->columntitle("u_bstotal","Bid Surety Total");
$objGridB->columntitle("edit","");

$objGridB->columnwidth("u_title",30);
$objGridB->columnwidth("u_euiu",15);
$objGridB->columnwidth("u_abc",15);
$objGridB->columnwidth("u_biddocsfee",15);
$objGridB->columnwidth("u_prebiddate",11);
$objGridB->columnwidth("u_deadlinedate",11);
$objGridB->columnwidth("u_openingdate",11);
$objGridB->columnwidth("u_bscash",15);
$objGridB->columnwidth("u_bstotal",15);
$objGridB->columnwidth("edit",5);

$objGridB->columnalignment("u_abc","right");
$objGridB->columnalignment("u_biddocsfee","right");
$objGridB->columnalignment("u_bscash","right");
$objGridB->columnalignment("u_bstotal","right");


$objGridB->columndataentry("u_title","type","label");
$objGridB->columndataentry("u_euiu","type","label");
$objGridB->columndataentry("u_abc","type","label");
$objGridB->columndataentry("u_biddocsfee","type","label");
$objGridB->columndataentry("u_prebiddate","type","label");
$objGridB->columndataentry("u_deadlinedate","type","label");
$objGridB->columndataentry("u_openingdate","type","label");
$objGridB->columndataentry("u_bscash","type","label");
$objGridB->columndataentry("u_bstotal","type","label");
$objGridB->columnvisibility("docno",false);

$objGridB->dataentry = true;
$objGridB->showactionbar = false;
$objGridB->setaction("reset",false);
$objGridB->setaction("add",false);
$objGridB->height = 90;

$objGridB->columninput("edit","type","link");
$objGridB->columninput("edit","caption","[Edit]");
$objGridB->columninput("edit","dataentrycaption","[Add]");


$objGridC = new grid("T103");
$objGridC->addcolumn("u_bpname");
$objGridC->addcolumn("u_bidsec");
$objGridC->addcolumn("u_bidbank");
$objGridC->addcolumn("u_orno");
$objGridC->addcolumn("u_date");
$objGridC->addcolumn("u_bidsecamount");
$objGridC->addcolumn("u_reqbidsec");
$objGridC->addcolumn("u_issufficient");
$objGridC->addcolumn("u_remarks");
$objGridC->addcolumn("docno");
$objGridC->addcolumn("edit");

$objGridC->columntitle("u_title","Title");
$objGridC->columntitle("u_euiu","EUIU");
$objGridC->columntitle("u_abc","ABC");
$objGridC->columntitle("u_biddocsfee","BidDocs Fee");
$objGridC->columntitle("u_prebiddate","Pre Bid");
$objGridC->columntitle("u_deadlinedate","Deadline");
$objGridC->columntitle("u_openingdate","Opening");
$objGridC->columntitle("u_bscash","Bid Surety Cash");
$objGridC->columntitle("u_bstotal","Bid Surety Total");
$objGridC->columntitle("edit","");

$objGridC->columnwidth("u_title",30);
$objGridC->columnwidth("u_euiu",15);
$objGridC->columnwidth("u_abc",15);
$objGridC->columnwidth("u_biddocsfee",15);
$objGridC->columnwidth("u_prebiddate",11);
$objGridC->columnwidth("u_deadlinedate",11);
$objGridC->columnwidth("u_openingdate",11);
$objGridC->columnwidth("u_bscash",15);
$objGridC->columnwidth("u_bstotal",15);
$objGridC->columnwidth("edit",5);

$objGridC->columnalignment("u_abc","right");
$objGridC->columnalignment("u_biddocsfee","right");
$objGridC->columnalignment("u_bscash","right");
$objGridC->columnalignment("u_bstotal","right");


$objGridC->columndataentry("u_title","type","label");
$objGridC->columndataentry("u_euiu","type","label");
$objGridC->columndataentry("u_abc","type","label");
$objGridC->columndataentry("u_biddocsfee","type","label");
$objGridC->columndataentry("u_prebiddate","type","label");
$objGridC->columndataentry("u_deadlinedate","type","label");
$objGridC->columndataentry("u_openingdate","type","label");
$objGridC->columndataentry("u_bscash","type","label");
$objGridC->columndataentry("u_bstotal","type","label");
$objGridC->columnvisibility("docno",false);

$objGridC->dataentry = true;
$objGridC->showactionbar = false;
$objGridC->setaction("reset",false);
$objGridC->setaction("add",false);
$objGridC->height = 90;

$objGridC->columninput("edit","type","link");
$objGridC->columninput("edit","caption","[Edit]");
$objGridC->columninput("edit","dataentrycaption","[Add Bidder]");




?> 

