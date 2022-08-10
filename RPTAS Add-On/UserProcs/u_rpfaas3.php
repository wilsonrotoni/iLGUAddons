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
	$objRs->queryopen("SELECT A.U_TAXABLE,A.DOCNO, A.U_MACHINE, A.U_BRAND, A.U_MODEL, A.U_CAPACITY, A.U_ACQDATE, A.U_CONDITION, A.U_ESTLIFE, A.U_REMLIFE, A.U_INSYEAR, A.U_INITYEAR FROM U_RPFAAS3A A WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"u_taxable",$objRs->fields["U_TAXABLE"]);
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridA->setitem(null,"u_machine",$objRs->fields["U_MACHINE"]);
		$objGridA->setitem(null,"u_brand",$objRs->fields["U_BRAND"]);
		$objGridA->setitem(null,"u_model",$objRs->fields["U_MODEL"]);
		$objGridA->setitem(null,"u_capacity",$objRs->fields["U_CAPACITY"]);
		$objGridA->setitem(null,"u_acqdate",$objRs->fields["U_ACQDATE"]);
		$objGridA->setitem(null,"u_condition",$objRs->fields["U_CONDITION"]);
		$objGridA->setitem(null,"u_estlife",$objRs->fields["U_ESTLIFE"]);
		$objGridA->setitem(null,"u_remlife",$objRs->fields["U_REMLIFE"]);
		$objGridA->setitem(null,"u_insyear",$objRs->fields["U_INSYEAR"]);
		$objGridA->setitem(null,"u_inityear",$objRs->fields["U_INITYEAR"]);
	}
	
	$objRs->queryopen("SELECT A.DOCNO, A.U_ORGCOST, A.U_CNVFACTOR, A.U_QUANTITY, A.U_RCN, A.U_USEYEAR, A.U_DEPRERATEFR, A.U_DEPRERATETO, A.U_DEPREPERC, A.U_DEPREVALUE, A.U_REMVALUE FROM U_RPFAAS3A A WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	$deprevalue=0;
	$remvalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridB->addrow();
		$objGridB->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridB->setitem(null,"u_orgcost",formatNumericAmount($objRs->fields["U_ORGCOST"]));
		$objGridB->setitem(null,"u_cnvfactor",$objRs->fields["U_CNVFACTOR"]);
		$objGridB->setitem(null,"u_quantity",$objRs->fields["U_QUANTITY"]);
		$objGridB->setitem(null,"u_rcn",$objRs->fields["U_RCN"]);
		$objGridB->setitem(null,"u_useyear",$objRs->fields["U_USEYEAR"]);
		$objGridB->setitem(null,"u_depreratefr",formatNumericPercent($objRs->fields["U_DEPRERATEFR"]));
		$objGridB->setitem(null,"u_deprerateto",formatNumericPercent($objRs->fields["U_DEPRERATETO"]));
		$objGridB->setitem(null,"u_depreperc",formatNumericPercent($objRs->fields["U_DEPREPERC"]));
		$objGridB->setitem(null,"u_deprevalue",formatNumericAmount($objRs->fields["U_DEPREVALUE"]));
		$objGridB->setitem(null,"u_remvalue",formatNumericAmount($objRs->fields["U_REMVALUE"]));
		$deprevalue+=$objRs->fields["U_DEPREVALUE"];
		$remvalue+=$objRs->fields["U_REMVALUE"];
	}
	$objGridA->setdefaultvalue("u_deprevalue",formatNumericAmount($deprevalue));
	$objGridA->setdefaultvalue("u_remvalue",formatNumericAmount($remvalue));
	
	$objRs->queryopen("SELECT A.U_TAXABLE,C.NAME AS U_ACTUALUSE, A.U_MARKETVALUE, A.U_ASSLVL, A.U_ASSVALUE FROM U_RPFAAS3B A INNER JOIN U_RPACTUSES C ON C.CODE=A.U_ACTUALUSE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='".$page->getitemstring("docid")."'");
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
        
        $objRs->queryopen("SELECT A.U_MACHINE,A.U_BRAND,C.U_YEAR,C.U_MARKETVALUE,C.U_DEPREVALUE,C.U_ADJMARKETVALUE,C.U_ASSLVL,C.U_ASSVALUE FROM U_RPFAAS3A A  INNER JOIN U_RPFAAS3C C ON A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH AND A.DOCID = C.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridD->addrow();
		$objGridD->setitem(null,"u_kind",$objRs->fields["U_MACHINE"]);
		$objGridD->setitem(null,"u_brand",$objRs->fields["U_BRAND"]);
		$objGridD->setitem(null,"u_year",$objRs->fields["U_YEAR"]);
		$objGridD->setitem(null,"u_marketvalue",formatNumericPercent($objRs->fields["U_MARKETVALUE"]));
		$objGridD->setitem(null,"u_deprevalue",formatNumericPercent($objRs->fields["U_DEPREVALUE"]));
		$objGridD->setitem(null,"u_adjmarketvalue",formatNumericPercent($objRs->fields["U_ADJMARKETVALUE"]));
		$objGridD->setitem(null,"u_asslvl",formatNumericAmount($objRs->fields["U_ASSLVL"]));
		$objGridD->setitem(null,"u_assvalue",formatNumericAmount($objRs->fields["U_ASSVALUE"]));

	}
	
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
$page->businessobject->items->setcfl("u_bldgtdno","OpenCFLfs()");

$page->businessobject->items->setcfl("u_assesseddate","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");
$page->businessobject->items->setcfl("u_releaseddate","Calendar");
$page->businessobject->items->setcfl("u_recommenddate","Calendar");

$objGrids[1]->columncfl("u_prevarpno","OpenCFLfs()");
$objGrids[1]->columncfl("u_prevtdno","OpenCFLfs()");

$objGrids[1]->columntitle("u_prevarpno","Reference No.");
$objGrids[1]->columnwidth("u_prevpin",20);
$objGrids[1]->columnwidth("u_prevarpno",20);
$objGrids[1]->columnwidth("u_prevtdno",18);

$objGridA = new grid("T101");
$objGridA->addcolumn("u_taxable");
$objGridA->addcolumn("u_machine");
$objGridA->addcolumn("u_brand");
$objGridA->addcolumn("u_model");
$objGridA->addcolumn("u_capacity");
$objGridA->addcolumn("u_acqdate");
$objGridA->addcolumn("u_condition");
$objGridA->addcolumn("u_estlife");
$objGridA->addcolumn("u_remlife");
$objGridA->addcolumn("u_insyear");
$objGridA->addcolumn("u_inityear");
$objGridA->addcolumn("docno");
$objGridA->addcolumn("edit");
$objGridA->columntitle("u_taxable","Taxable");
$objGridA->columntitle("u_machine","Kind of Machine");
$objGridA->columntitle("u_brand","Brand");
$objGridA->columntitle("u_model","Model");
$objGridA->columntitle("u_capacity","Capacity/HP");
$objGridA->columntitle("u_acqdate","Date Acquired");
$objGridA->columntitle("u_condition","Condition When Acquired");
$objGridA->columntitle("u_estlife","Estimated Life");
$objGridA->columntitle("u_remlife","Remaining Life");
$objGridA->columntitle("u_insyear","Year Installed");
$objGridA->columntitle("u_inityear","Year of Initial Operation");
$objGridA->columntitle("edit","");
$objGridA->columnwidth("u_taxable",5);
$objGridA->columnwidth("u_machine",13);
$objGridA->columnwidth("u_capacity",13);
$objGridA->columnwidth("u_acqdate",12);
$objGridA->columnwidth("u_condition",22);
$objGridA->columnwidth("u_estlife",13);
$objGridA->columnwidth("u_remlife",13);
$objGridA->columnwidth("u_insyear",13);
$objGridA->columnwidth("u_inityear",25);
$objGridA->columnwidth("edit",5);
$objGridA->columndataentry("u_taxable","type","label");
$objGridA->columndataentry("u_machine","type","label");
$objGridA->columndataentry("u_brand","type","label");
$objGridA->columndataentry("u_model","type","label");
$objGridA->columndataentry("u_capacity","type","label");
$objGridA->columndataentry("u_acqdate","type","label");
$objGridA->columndataentry("u_condition","type","label");
$objGridA->columndataentry("u_estlife","type","label");
$objGridA->columndataentry("u_remlife","type","label");
$objGridA->columndataentry("u_insyear","type","label");
$objGridA->columndataentry("u_inityear","type","label");
$objGridA->columnvisibility("docno",false);
$objGridA->columnvisibility("edit",false);
$objGridA->dataentry = true;
$objGridA->automanagecolumnwidth = true;
$objGridA->showactionbar = false;
$objGridA->setaction("reset",false);
$objGridA->setaction("add",false);
$objGridA->height = 78;
$objGridA->columninput("edit","type","link");
$objGridA->columninput("edit","caption","[Edit]");
$objGridA->columninput("edit","dataentrycaption","[Add]");

$objGridB = new grid("T102");
$objGridB->addcolumn("u_orgcost");
$objGridB->addcolumn("u_cnvfactor");
$objGridB->addcolumn("u_quantity");
$objGridB->addcolumn("u_rcn");
$objGridB->addcolumn("u_useyear");
$objGridB->addcolumn("u_depreratefr");
$objGridB->addcolumn("u_deprerateto");
$objGridB->addcolumn("u_depreperc");
$objGridB->addcolumn("u_deprevalue");
$objGridB->addcolumn("u_remvalue");
$objGridB->addcolumn("docno");
$objGridB->columntitle("u_orgcost","Original Cost");
$objGridB->columntitle("u_cnvfactor","Conversion Factor");
$objGridB->columntitle("u_quantity","No. of Units");
$objGridB->columntitle("u_rcn","RCN");
$objGridB->columntitle("u_useyear","No. of Years Used");
$objGridB->columntitle("u_depreratefr","Depre Rate From");
$objGridB->columntitle("u_deprerateto","Depre Rate To");
$objGridB->columntitle("u_depreperc","% Depreciation");
$objGridB->columntitle("u_deprevalue","Depreciation Value");
$objGridB->columntitle("u_remvalue","Depreciated Value");
$objGridB->columnwidth("u_orgcost",12);
$objGridB->columnwidth("u_cnvfactor",16);
$objGridB->columnwidth("u_quantity",11);
$objGridB->columnwidth("u_useyear",15);
$objGridB->columnwidth("u_depreratefr",14);
$objGridB->columnwidth("u_deprerateto",14);
$objGridB->columnwidth("u_depreperc",13);
$objGridB->columnwidth("u_deprevalue",16);
$objGridB->columnwidth("u_remvalue",16);
$objGridB->columnalignment("u_orgcost","right");
$objGridB->columnalignment("u_useyear","right");
$objGridB->columnalignment("u_depreratefr","right");
$objGridB->columnalignment("u_deprerateto","right");
$objGridB->columnalignment("u_depreperc","right");
$objGridB->columnalignment("u_deprevalue","right");
$objGridB->columnalignment("u_remvalue","right");
$objGridB->columndataentry("u_orgcost","type","label");
$objGridB->columndataentry("u_cnvfactor","type","label");
$objGridB->columndataentry("u_quantity","type","label");
$objGridB->columndataentry("u_rcn","type","label");
$objGridB->columndataentry("u_useyear","type","label");
$objGridB->columndataentry("u_depreratefr","type","label");
$objGridB->columndataentry("u_deprerateto","type","label");
$objGridB->columndataentry("u_depreperc","type","label");
$objGridB->columndataentry("u_deprevalue","type","label");
$objGridB->columndataentry("u_remvalue","type","label");
$objGridB->columnvisibility("docno",false);
$objGridB->dataentry = true;
$objGridB->automanagecolumnwidth = true;
$objGridB->showactionbar = false;
$objGridB->setaction("reset",false);
$objGridB->setaction("add",false);
$objGridB->height = 78;

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
$objGridC->height = 78;
//
$objGridD = new grid("T104");
$objGridD->addcolumn("u_kind");
$objGridD->addcolumn("u_brand");
$objGridD->addcolumn("u_year");
$objGridD->addcolumn("u_marketvalue");
$objGridD->addcolumn("u_deprevalue");
$objGridD->addcolumn("u_adjmarketvalue");
$objGridD->addcolumn("u_asslvl");
$objGridD->addcolumn("u_assvalue");

$objGridD->columntitle("u_kind","Kind");
$objGridD->columntitle("u_brand","Brand");
$objGridD->columntitle("u_year","Year");
$objGridD->columntitle("u_marketvalue","Market Value");
$objGridD->columntitle("u_deprevalue","Depreciation Value");
$objGridD->columntitle("u_adjmarketvalue","Adjusted Market Value");
$objGridD->columntitle("u_asslvl","Assessment Level");
$objGridD->columntitle("u_assvalue","Assessed Value");

//$objGridD->columnwidth("u_kind",30);
//$objGridD->columnwidth("u_brand",30);
//$objGridD->columnwidth("u_year",5);
//$objGridD->columnwidth("u_marketvalue",25);
//$objGridD->columnwidth("u_deprevalue",25);
//$objGridD->columnwidth("u_adjmarketvalue",25);
//$objGridD->columnwidth("u_asslvl",16);
//$objGridD->columnwidth("u_assvalue",16);
//$objGridD->columnalignment("u_marketvalue","right");
//$objGridD->columnalignment("u_asslvl","right");
//$objGridD->columnalignment("u_assvalue","right");
//$objGridD->columndataentry("u_taxable","type","label");
//$objGridD->columndataentry("u_actualuse","type","label");
//$objGridD->columndataentry("u_marketvalue","type","label");
//$objGridD->columndataentry("u_asslvl","type","label");
//$objGridD->columndataentry("u_assvalue","type","label");
//$objGridD->columnvisibility("docno",false);
$objGridD->dataentry = false;
//$objGridD->automanagecolumnwidth = true;
//$objGridD->showactionbar = false;
//$objGridD->setaction("reset",false);
//$objGridD->setaction("add",false);
$objGridD->height = 330;





$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("update",false);

$objMaster->reportaction = "QS";
?> 

