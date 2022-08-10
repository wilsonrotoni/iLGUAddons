<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSRPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSRPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSRPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSRPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSRPTAS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSRPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSRPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSRPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSRPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSRPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSRPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSRPTAS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSRPTAS");

$appdata= array();

function onDrawGridColumnLabelGPSRPTAS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T103":
			//if ($column=="edit") {
			//	$label = "[Edit]";
			//}
			break;
	}
}


function onCustomActionGPSRPTAS($action) {
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	global $page;
	global $appdata;
	$page->privatedata["getprevarpno"] = $page->getitemstring("getprevarpno");
	return true;
}
function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
	global $objGrids;

	$page->setitem("docstatus","Encoding");
	$page->setitem("u_approveddate",currentdate());
	$page->setitem("u_assesseddate",currentdate());
	$page->setitem("u_recommenddate",currentdate());
	$page->setitem("u_recordeddate",currentdate());
	$page->setitem("u_recordedby",$_SESSION["username"]);
        
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select U_MUNICIPALITY, U_CITY, U_PROVINCE from U_LGUSETUP");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_city",$objRs->fields["U_CITY"]);
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
	}
	
	$objRs->queryopen("select code,u_default from U_RPASSESSORS");
	while ($objRs->queryfetchrow("NAME")) {
                if($objRs->fields["u_default"]=="2")$page->setitem("u_approvedby",$objRs->fields["code"]);
                else if($objRs->fields["u_default"]=="1")$page->setitem("u_recommendby",$objRs->fields["code"]);
	}
	
	$objGrids[1]->clear();
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select a.code, a.u_group from u_rpbldgprops a inner join u_rpbldgpropgroups b on b.code=a.u_group where b.u_common=1 and a.u_default=1 order by b.u_seqno, a.u_group, a.u_seqno, a.code");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_selected",0);
		$objGrids[1]->setitem(null,"u_prop",$objRs->fields["code"]);
	}
        $objRs_uGRYear = new recordset(null,$objConnection);
	$objRs_uGRYear->queryopen("select A.CODE from U_GRYEARS A WHERE A.U_ISACTIVE = 1");
	if ($objRs_uGRYear->queryfetchrow("NAME")) {
		$page->setitem("u_revisionyear",$objRs_uGRYear->fields["CODE"]);
	}
	return true;
}

function onPrepareAddGPSRPTAS(&$override) { 
	return true;
}

function onBeforeAddGPSRPTAS() { 
	return true;
}

function onAfterAddGPSRPTAS() { 
	return true;
}

function onPrepareEditGPSRPTAS(&$override) { 
	return true;
}

function onBeforeEditGPSRPTAS() { 
	return true;
}

function onAfterEditGPSRPTAS() { 
	global $page;
	global $objConnection;
	global $objGridA;
	global $objGridB;
	global $objGridC;
	global $objGridD;
	
	$objGridA->clear();
	$objGridB->clear();
	$objGridC->clear();
	$objGridD->clear();
	
	$objRs = new recordset(null,$objConnection);
//	$objRs->setdebug();
	$objRs->queryopen("SELECT A.DOCNO, C.NAME AS U_ACTUALUSE, A.U_FLOOR, B.U_ITEMDESC, B.U_SQM, B.U_STARTYEAR, B.U_QUANTITY, B.U_UNITVALUE, B.U_COMPLETEPERC, B.U_BASEVALUE FROM U_RPFAAS2A A LEFT JOIN U_RPFAAS2B B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID LEFT JOIN U_RPACTUSES C ON C.CODE=A.U_ACTUALUSE  WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	$basevalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridB->addrow();
		$objGridB->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridB->setitem(null,"u_floor",$objRs->fields["U_FLOOR"]);
		$objGridB->setitem(null,"u_itemdesc",$objRs->fields["U_ITEMDESC"]);
		$objGridB->setitem(null,"u_actualuse",$objRs->fields["U_ACTUALUSE"]);
		$objGridB->setitem(null,"u_sqm",formatNumericMeasure($objRs->fields["U_SQM"]));
		$objGridB->setitem(null,"u_startyear",$objRs->fields["U_STARTYEAR"]);
		$objGridB->setitem(null,"u_quantity",$objRs->fields["U_QUANTITY"]);
		$objGridB->setitem(null,"u_unitvalue",formatNumericPrice($objRs->fields["U_UNITVALUE"]));
		$objGridB->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]));
		$objGridB->setitem(null,"u_completeperc",formatNumericPercent($objRs->fields["U_COMPLETEPERC"]));
		$basevalue+=$objRs->fields["U_BASEVALUE"];
	}
	$objGridB->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue));
	
	$objRs->queryopen("SELECT A.U_TAXABLE,A.DOCNO, C.NAME AS U_ACTUALUSE, A.U_FLOOR, A.U_STRUCTURETYPE, A.U_SUBCLASS, D.NAME AS U_CLASS, A.U_SQM, A.U_STARTYEAR, A.U_AGE, A.U_UNITVALUE, A.U_COMPLETEPERC, A.U_FLOORBASEVALUE FROM U_RPFAAS2A A LEFT JOIN U_RPACTUSES C ON C.CODE=A.U_ACTUALUSE LEFT JOIN U_RPIMPROVEMENTS D ON D.CODE=A.U_CLASS WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	$sqm=0;
	$basevalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"u_taxable",$objRs->fields["U_TAXABLE"]);
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridA->setitem(null,"u_floor",$objRs->fields["U_FLOOR"]);
		$objGridA->setitem(null,"u_structuretype",$objRs->fields["U_STRUCTURETYPE"]);
		$objGridA->setitem(null,"u_subclass",$objRs->fields["U_SUBCLASS"]);
		$objGridA->setitem(null,"u_class",$objRs->fields["U_CLASS"]);
		$objGridA->setitem(null,"u_actualuse",$objRs->fields["U_ACTUALUSE"]);
		$objGridA->setitem(null,"u_sqm",formatNumericMeasure($objRs->fields["U_SQM"]));
		$objGridA->setitem(null,"u_startyear",$objRs->fields["U_STARTYEAR"]);
		$objGridA->setitem(null,"u_age",$objRs->fields["U_AGE"]);
		$objGridA->setitem(null,"u_unitvalue",formatNumericPrice($objRs->fields["U_UNITVALUE"]));
		$objGridA->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_FLOORBASEVALUE"]));
		$objGridA->setitem(null,"u_completeperc",formatNumericPercent($objRs->fields["U_COMPLETEPERC"]));
		$sqm+=$objRs->fields["U_SQM"];
		$basevalue+=$objRs->fields["U_FLOORBASEVALUE"];
	}
	$objGridA->setdefaultvalue("u_sqm",formatNumericAmount($sqm));
	$objGridA->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue));
	
	$objRs->queryopen("SELECT A.DOCNO, A.U_FLOOR AS U_ITEMDESC, A.U_FLOORBASEVALUE AS U_BASEVALUE, A.U_FLOORADJPERC AS U_ADJPERC, A.U_FLOORDEPREVALUE AS U_DEPREVALUE, A.U_FLOORADJVALUE AS U_ADJVALUE, A.U_FLOORADJMARKETVALUE AS U_ADJMARKETVALUE FROM U_RPFAAS2A A WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."' UNION ALL SELECT A.DOCNO, B.U_ITEMDESC, B.U_BASEVALUE, B.U_ADJPERC, B.U_DEPREVALUE, B.U_ADJVALUE, B.U_ADJMARKETVALUE FROM U_RPFAAS2A A INNER JOIN U_RPFAAS2B B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID  WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	$basevalue=0;
	$deprevalue=0;
	$adjvalue=0;
	$adjmarketvalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridD->addrow();
		$objGridD->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridD->setitem(null,"u_itemdesc",$objRs->fields["U_ITEMDESC"]);
		$objGridD->setitem(null,"u_adjperc",formatNumericAmount($objRs->fields["U_ADJPERC"]));
		$objGridD->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]));
		$objGridD->setitem(null,"u_deprevalue",formatNumericAmount($objRs->fields["U_DEPREVALUE"]));
		$objGridD->setitem(null,"u_adjvalue",formatNumericAmount($objRs->fields["U_ADJVALUE"]));
		$objGridD->setitem(null,"u_adjmarketvalue",formatNumericAmount($objRs->fields["U_ADJMARKETVALUE"]));
		$basevalue+=$objRs->fields["U_BASEVALUE"];
		$deprevalue+=$objRs->fields["U_DEPREVALUE"];
		$adjvalue+=$objRs->fields["U_ADJVALUE"];
		$adjmarketvalue+=$objRs->fields["U_ADJMARKETVALUE"];
	}
	$objGridD->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue));
	$objGridD->setdefaultvalue("u_deprevalue",formatNumericAmount($deprevalue));
	$objGridD->setdefaultvalue("u_adjvalue",formatNumericAmount($adjvalue));
	$objGridD->setdefaultvalue("u_adjmarketvalue",formatNumericAmount($adjmarketvalue));

	$objRs->queryopen("SELECT A.U_TAXABLE,C.NAME AS U_ACTUALUSE, A.U_MARKETVALUE, A.U_ASSLVL, A.U_ASSVALUE FROM U_RPFAAS2C A LEFT JOIN U_RPACTUSES C ON C.CODE=A.U_ACTUALUSE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='".$page->getitemstring("docid")."'");
	$marketvalue=0;
	$assvalue=0;
	$value=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridC->addrow();
		$objGridC->setitem(null,"u_taxable",$objRs->fields["U_TAXABLE"]);
		$objGridC->setitem(null,"u_actualuse",$objRs->fields["U_ACTUALUSE"]);
		$objGridC->setitem(null,"u_marketvalue",formatNumericAmount($objRs->fields["U_MARKETVALUE"]));
		$objGridC->setitem(null,"u_asslvl",formatNumericPercent($objRs->fields["U_ASSLVL"]));
		$objGridC->setitem(null,"u_assvalue",formatNumericAmount($objRs->fields["U_ASSVALUE"]));
		$marketvalue+=$objRs->fields["U_MARKETVALUE"];
		if($objRs->fields["U_TAXABLE"]==1){
                    $value=$objRs->fields["U_ASSVALUE"];
                }else{
                    $value = 0;
                }
                $assvalue+=$value;
	}
	$objGridC->setdefaultvalue("u_marketvalue",formatNumericAmount($marketvalue));
	$objGridC->setdefaultvalue("u_assvalue",formatNumericAmount($assvalue));
	
	return true;
}

function onPrepareUpdateGPSRPTAS(&$override) { 
	return true;
}

function onBeforeUpdateGPSRPTAS() { 
	return true;
}

function onAfterUpdateGPSRPTAS() { 
	return true;
}

function onPrepareDeleteGPSRPTAS(&$override) { 
	return true;
}

function onBeforeDeleteGPSRPTAS() { 
	return true;
}

function onAfterDeleteGPSRPTAS() { 
	return true;
}

$page->businessobject->items->seteditable("u_municipality",false);
$page->businessobject->items->seteditable("u_city",false);
$page->businessobject->items->seteditable("u_province",false);

$page->businessobject->items->seteditable("u_isauction",false);
$page->businessobject->items->seteditable("u_cancelled",false);
$page->businessobject->items->seteditable("u_expqtr",false);
$page->businessobject->items->seteditable("u_expyear",false);
$page->businessobject->items->seteditable("u_bilyear",false);
$page->businessobject->items->seteditable("u_lastpaymode",false);
$page->businessobject->items->seteditable("u_recordedby",false);
$page->businessobject->items->seteditable("u_recordeddate",false);

$page->businessobject->items->setvisible("u_pinchanged",false);
$page->businessobject->items->setcfl("u_ownertin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_landtdno","OpenCFLfs()");

$page->businessobject->items->setcfl("u_assesseddate","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");
$page->businessobject->items->setcfl("u_releaseddate","Calendar");
$page->businessobject->items->setcfl("u_recommenddate","Calendar");
$objGrids[2]->columntitle("u_prevarpno","Reference No.");
$objGrids[2]->columncfl("u_prevarpno","OpenCFLfs()");
$objGrids[2]->columncfl("u_prevtdno","OpenCFLfs()");

$objGrids[2]->columnwidth("u_prevpin",20);
$objGrids[2]->columnwidth("u_prevarpno",20);
$objGrids[2]->columnwidth("u_prevtdno",18);
$objGrids[2]->columntitle("u_prevarpno2","ARP No.");
$objGrids[2]->dataentry = true;

$objGridA = new grid("T101");
$objGridA->addcolumn("u_taxable");
$objGridA->addcolumn("u_floor");
$objGridA->addcolumn("u_structuretype");
$objGridA->addcolumn("u_subclass");
$objGridA->addcolumn("u_class");
$objGridA->addcolumn("u_actualuse");
$objGridA->addcolumn("u_sqm");
$objGridA->addcolumn("u_startyear");
$objGridA->addcolumn("u_age");
$objGridA->addcolumn("u_unitvalue");
$objGridA->addcolumn("u_completeperc");
$objGridA->addcolumn("u_basevalue");
$objGridA->addcolumn("docno");
$objGridA->addcolumn("edit");
$objGridA->columntitle("u_taxable","Taxable");
$objGridA->columntitle("u_floor","Floor");
$objGridA->columntitle("u_structuretype","Structure Type");
$objGridA->columntitle("u_subclass","Class");
$objGridA->columntitle("u_class","Classification");
$objGridA->columntitle("u_actualuse","Actual Use");
$objGridA->columntitle("u_sqm","Area (sqm.)");
$objGridA->columntitle("u_startyear","Year");
$objGridA->columntitle("u_age","Age");
$objGridA->columntitle("u_unitvalue","Unit Value");
$objGridA->columntitle("u_completeperc","% Completed");
$objGridA->columntitle("u_basevalue","Base Market Value");
$objGridA->columntitle("edit","");
$objGridA->columnwidth("u_taxable",6);
$objGridA->columnwidth("u_floor",6);
$objGridA->columnwidth("u_structuretype",12);
$objGridA->columnwidth("u_subclass",10);
$objGridA->columnwidth("u_class",16);
$objGridA->columnwidth("u_actualuse",12);
$objGridA->columnwidth("u_sqm",10);
$objGridA->columnwidth("u_startyear",7);
$objGridA->columnwidth("u_age",5);
$objGridA->columnwidth("u_unitvalue",16);
$objGridA->columnwidth("u_completeperc",12);
$objGridA->columnwidth("u_basevalue",20);
$objGridA->columnwidth("edit",8);
$objGridA->columnalignment("u_sqm","right");
$objGridA->columnalignment("u_startyear","right");
$objGridA->columnalignment("u_age","right");
$objGridA->columnalignment("u_unitvalue","right");
$objGridA->columnalignment("u_completeperc","right");
$objGridA->columnalignment("u_basevalue","right");
$objGridA->columndataentry("u_taxable","type","label");
$objGridA->columndataentry("u_floor","type","label");
$objGridA->columndataentry("u_structuretype","type","label");
$objGridA->columndataentry("u_class","type","label");
$objGridA->columndataentry("u_subclass","type","label");
$objGridA->columndataentry("u_actualuse","type","label");
$objGridA->columndataentry("u_sqm","type","label");
$objGridA->columndataentry("u_startyear","type","label");
$objGridA->columndataentry("u_age","type","label");
$objGridA->columndataentry("u_unitvalue","type","label");
$objGridA->columndataentry("u_completeperc","type","label");
$objGridA->columndataentry("u_basevalue","type","label");
$objGridA->columnvisibility("docno",false);
$objGridA->columnvisibility("edit",false);
$objGridA->dataentry = true;
$objGridA->showactionbar = false;
$objGridA->setaction("reset",false);
$objGridA->setaction("add",false);
$objGridA->height = 55;
$objGridA->columninput("edit","type","link");
$objGridA->columninput("edit","caption","[Edit]");
$objGridA->columninput("edit","dataentrycaption","[Add]");

$objGridB = new grid("T102");
$objGridB->addcolumn("u_floor");
$objGridB->addcolumn("u_actualuse");
$objGridB->addcolumn("u_itemdesc");
$objGridB->addcolumn("u_sqm");
$objGridB->addcolumn("u_startyear");
$objGridB->addcolumn("u_quantity");
$objGridB->addcolumn("u_unitvalue");
$objGridB->addcolumn("u_completeperc");
$objGridB->addcolumn("u_basevalue");
$objGridB->addcolumn("docno");
$objGridB->columntitle("u_floor","Floor");
$objGridB->columntitle("u_actualuse","Actual Use");
$objGridB->columntitle("u_itemdesc","Item");
$objGridB->columntitle("u_sqm","Area (sqm.)");
$objGridB->columntitle("u_startyear","Year");
$objGridB->columntitle("u_age","Age");
$objGridB->columntitle("u_quantity","Quantity");
$objGridB->columntitle("u_unitvalue","Unit Value");
$objGridB->columntitle("u_completeperc","% Completed");
$objGridB->columntitle("u_basevalue","Base Market Value");
$objGridB->columnwidth("u_itemdesc",30);
$objGridB->columnwidth("u_sqm",11);
$objGridB->columnwidth("u_quantity",6);
$objGridB->columnwidth("u_unitvalue",16);
$objGridB->columnwidth("u_basevalue",17);
$objGridB->columnalignment("u_sqm","right");
$objGridB->columnalignment("u_startyear","right");
$objGridB->columnalignment("u_quantity","right");
$objGridB->columnalignment("u_unitvalue","right");
$objGridB->columnalignment("u_completeperc","right");
$objGridB->columnalignment("u_basevalue","right");
$objGridB->columndataentry("u_floor","type","label");
$objGridB->columndataentry("u_itemdesc","type","label");
$objGridB->columndataentry("u_actualuse","type","label");
$objGridB->columndataentry("u_sqm","type","label");
$objGridB->columndataentry("u_startyear","type","label");
$objGridB->columndataentry("u_quantity","type","label");
$objGridB->columndataentry("u_unitvalue","type","label");
$objGridB->columndataentry("u_completeperc","type","label");
$objGridB->columndataentry("u_basevalue","type","label");
$objGridB->columnvisibility("docno",false);
$objGridB->dataentry = true;
$objGridB->automanagecolumnwidth = true;
$objGridB->showactionbar = false;
$objGridB->setaction("reset",false);
$objGridB->setaction("add",false);
$objGridB->height = 55;

$objGridC = new grid("T103");
$objGridC->addcolumn("u_taxable");
$objGridC->addcolumn("u_actualuse");
$objGridC->addcolumn("u_marketvalue");
$objGridC->addcolumn("u_asslvl");
$objGridC->addcolumn("u_assvalue");
$objGridC->addcolumn("docno");
$objGridC->columntitle("u_taxable","Taxable");
$objGridC->columntitle("u_actualuse","Actual Use");
$objGridC->columntitle("u_marketvalue","Market Value");
$objGridC->columntitle("u_asslvl","Assessment Level");
$objGridC->columntitle("u_assvalue","Assessed Value");
$objGridC->columntitle("edit","");
$objGridC->columnwidth("u_taxable",5);
$objGridC->columnwidth("u_actualuse",20);
$objGridC->columnwidth("u_marketvalue",20);
$objGridC->columnwidth("u_asslvl",16);
$objGridC->columnwidth("u_assvalue",16);
$objGridC->columnalignment("u_marketvalue","right");
$objGridC->columnalignment("u_asslvl","right");
$objGridC->columnalignment("u_assvalue","right");
$objGridC->columndataentry("u_taxable","type","label");
$objGridC->columndataentry("u_actualuse","type","label");
$objGridC->columndataentry("u_marketvalue","type","label");
$objGridC->columndataentry("u_asslvl","type","label");
$objGridC->columndataentry("u_assvalue","type","label");
$objGridC->columnvisibility("docno",false);
$objGridC->dataentry = true;
$objGridC->showactionbar = false;
$objGridC->setaction("reset",false);
$objGridC->setaction("add",false);
$objGridC->height = 55;


$objGridD = new grid("T104");
$objGridD->addcolumn("u_itemdesc");
$objGridD->addcolumn("u_adjperc");
$objGridD->addcolumn("u_basevalue");
$objGridD->addcolumn("u_deprevalue");
$objGridD->addcolumn("u_adjvalue");
$objGridD->addcolumn("u_adjmarketvalue");
$objGridD->columntitle("u_itemdesc","Floor/Item");
$objGridD->columntitle("u_adjperc","% Adjustment");
$objGridD->columntitle("u_basevalue","Base Market Value");
$objGridD->columntitle("u_deprevalue","Depreciation Value");
$objGridD->columntitle("u_adjvalue","Value Adjustment");
$objGridD->columntitle("u_adjmarketvalue","Adjusted Market Value");
$objGridD->columnwidth("u_itemdesc",30);
$objGridD->columnwidth("u_adjperc",11);
$objGridD->columnwidth("u_basevalue",20);
$objGridD->columnwidth("u_deprevalue",16);
$objGridD->columnwidth("u_adjvalue",16);
$objGridD->columnwidth("u_adjmarketvalue",12);
$objGridD->columnalignment("u_basevalue","right");
$objGridD->columnalignment("u_adjperc","right");
$objGridD->columnalignment("u_adjvalue","right");
$objGridD->columnalignment("u_adjmarketvalue","right");
$objGridD->columndataentry("u_itemdesc","type","label");
$objGridD->columndataentry("u_basevalue","type","label");
$objGridD->columndataentry("u_deprevalue","type","label");
$objGridD->columndataentry("u_adjperc","type","label");
$objGridD->columndataentry("u_adjvalue","type","label");
$objGridD->columndataentry("u_adjmarketvalue","type","label");
$objGridD->dataentry = true;
$objGridD->showactionbar = false;
$objGridD->setaction("reset",false);
$objGridD->setaction("add",false);
$objGridD->height = 55;

$objGrids[1]->columnwidth("u_selected",3);
$objGrids[1]->columninput("u_selected","type","checkbox");
$objGrids[1]->columninput("u_selected","value",1);
$objGrids[1]->columndataentry("u_selected","type","checkbox");
$objGrids[1]->columndataentry("u_selected","value",1);

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("update",false);

$objMaster->reportaction = "QS";
?> 

