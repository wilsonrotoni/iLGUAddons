<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSRPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSRPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSRPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSRPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSRPTAS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSRPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSRPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSRPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSRPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSRPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSRPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSRPTAS");

function onCustomActionGPSRPTAS($action) {
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	
        $objRs_uGRYear = new recordset(null,$objConnection);
	$objRs_uGRYear->queryopen("select A.CODE from U_GRYEARS A WHERE A.U_ISACTIVE = 1");
	if ($objRs_uGRYear->queryfetchrow("NAME")) {
		$page->setitem("u_gryear",$objRs_uGRYear->fields["CODE"]);
	}
        
	$objGrids[1]->clear();
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select a.code, a.u_group from u_rpbldgprops a inner join u_rpbldgpropgroups b on b.code=a.u_group where b.u_common=0 and a.u_default=1 order by b.u_seqno, a.u_group, a.u_seqno, a.code");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_selected",0);
		$objGrids[1]->setitem(null,"u_prop",$objRs->fields["code"]);
	}
        $page->setitem("u_withdecimal",1);
        
        
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

$schema["isasslvlclass"] = createSchemaUpper("isasslvlclass");

$page->businessobject->items->seteditable("u_arpno",false);
$page->businessobject->items->seteditable("u_basevalue",false);
$page->businessobject->items->seteditable("u_adjvalue",false);
$page->businessobject->items->seteditable("u_marketvalue",false);
$page->businessobject->items->seteditable("u_asslvl",false);
$page->businessobject->items->seteditable("u_assvalue",false);
$page->businessobject->items->seteditable("u_floorbasevalue",false);
$page->businessobject->items->seteditable("u_flooradjvalue",false);
$page->businessobject->items->seteditable("u_flooradjmarketvalue",false);

$objGrids[0]->columnwidth("u_itemdesc",30);
$objGrids[0]->columnwidth("u_sqm",8);
$objGrids[0]->columnwidth("u_quantity",4);
$objGrids[0]->columnwidth("u_unitvalue",8);
$objGrids[0]->columnwidth("u_basevalue",16);
$objGrids[0]->columnwidth("u_deprevalue",16);
$objGrids[0]->columnwidth("u_adjmarketvalue",18);
$objGrids[0]->columnattributes("u_basevalue","disabled");
$objGrids[0]->columnattributes("u_adjvalue","disabled");
$objGrids[0]->columnattributes("u_adjmarketvalue","disabled");
$objGrids[0]->columnattributes("u_adjvalue","disabled");
$objGrids[0]->width = 1000;
$objGrids[0]->height = 110;
$objGrids[0]->automanagecolumnwidth = false;

$objGrids[1]->columnwidth("u_selected",3);
$objGrids[1]->columninput("u_selected","type","checkbox");
$objGrids[1]->columninput("u_selected","value",1);
$objGrids[1]->columndataentry("u_selected","type","checkbox");
$objGrids[1]->columndataentry("u_selected","value",1);
$objGrids[1]->width = 950;
$objGrids[1]->height = 110;

$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);

$addoptions = false;
?> 

