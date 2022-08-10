<?php

include_once("./sls/enumyear.php");
//$page->businessobject->events->add->customAction("onCustomActionGPSLGUBPLS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUBPLS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUBPLS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUBPLS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUBPLS");

$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSBPLS");

function onPrepareChildEditGPSBPLS($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T1":	
		$filerExp = " ORDER BY u_year,u_seqno";
		break;
	}
	return true;
}

function onCustomActionGPSLGUBPLS($action) {
	return true;
}

function onBeforeDefaultGPSLGUBPLS() { 
	return true;
}

function onAfterDefaultGPSLGUBPLS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;

	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.u_MUNICIPALITY, A.u_province from U_LGUSETUP A");
	if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
		$page->setitem("u_bcity",$objRs_uLGUSetup->fields["u_MUNICIPALITY"]);
		$page->setitem("u_bprovince",$objRs_uLGUSetup->fields["u_province"]);
	}
	$page->setitem("u_year",date('Y'));


	$page->setitem("u_docdate",currentdate());
	$page->setitem("u_apptype","Temporary Business Permit");

	$total=0;
	$objRs = new recordset(null,$objConnection);	
	$objRs->queryopen("select code, name, u_amount,u_seqno from u_bpltempfees  order by u_seqno asc ");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_year",date('Y'));
		$objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
		$objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
		$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
		$objGrids[0]->setitem(null,"u_interest",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_surcharge",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_quantity",1);
		$objGrids[0]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
		$total+=$objRs->fields["u_amount"];
	}
	
	$page->setitem("u_totalamount",formatNumericAmount($total));

	return true;
}

function onPrepareAddGPSLGUBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSLGUBPLS() { 
	return true;
}

function onAfterAddGPSLGUBPLS() { 
	return true;
}

function onPrepareEditGPSLGUBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSLGUBPLS() { 
	return true;
}

function onAfterEditGPSLGUBPLS() { 
	return true;
}

function onPrepareUpdateGPSLGUBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUBPLS() { 
	return true;
}

function onAfterUpdateGPSLGUBPLS() { 
	return true;
}

function onPrepareDeleteGPSLGUBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUBPLS() { 
	return true;
}

function onAfterDeleteGPSLGUBPLS() { 
	return true;
}

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_ordate","Calendar");
//$page->businessobject->items->setcfl("u_bldgno","OpenCFLfs()");
$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("u_insno",false);
$page->businessobject->items->seteditable("u_orno",false);
$page->businessobject->items->seteditable("u_ordate",false);
$page->businessobject->items->seteditable("docstatus",false);
//$page->businessobject->items->seteditable("u_bldgno",false);
$page->businessobject->items->seteditable("u_apptype",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");
$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_year",10);
$objGrids[0]->columnwidth("u_feecode",15);
$objGrids[0]->columnwidth("u_feedesc",25);
$objGrids[0]->columnvisibility("u_seqno",false);
$objGrids[0]->columnvisibility("u_seqno",false);
$objGrids[0]->columnvisibility("u_quantity",false);
$objGrids[0]->automanagecolumnwidth= false;

$objGrids[0]->width = 680;
$objGrids[0]->height = 180;

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

$pageHeader = "Contractor's Tax";
?> 

