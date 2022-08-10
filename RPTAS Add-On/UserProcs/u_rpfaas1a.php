<?php
 
include_once("../Addons/GPS/RPTAS Add-On/UserProcs/sls/u_faas1aclass.php");

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
        
        $objRs_uGRYear = new recordset(null,$objConnection);
	$objRs_uGRYear->queryopen("select A.CODE from U_GRYEARS A WHERE A.U_ISACTIVE = 1");
	if ($objRs_uGRYear->queryfetchrow("NAME")) {
		$page->setitem("u_gryear",$objRs_uGRYear->fields["CODE"]);
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

$objGrids[0]->columnattributes("u_basevalue","disabled");
$objGrids[0]->width = 1100;
$objGrids[0]->height = 180;
$objGrids[0]->columncfl("u_subclass","OpenCFLfs()");
$objGrids[0]->columnattributes("u_unitvalue","disabled");
$objGrids[0]->columnattributes("u_unitvaluehas","disabled");
$objGrids[0]->columnattributes("u_sqmhas","disabled");

$objGrids[0]->columntitle("u_sqm","Area(sqm)");
$objGrids[0]->columntitle("u_unitvalue","Unit Value(sqm)");
$objGrids[0]->columntitle("u_sqmhas","Area(has)");
$objGrids[0]->columntitle("u_unitvaluehas","Unit Value(has)");
$objGrids[0]->columnwidth("u_unit",10);
$objGrids[0]->columnwidth("u_subclass",25);
$objGrids[0]->columnwidth("u_class",20);
$objGrids[0]->columnwidth("u_basevalue",15);
$objGrids[0]->automanagecolumnwidth = false;

$objGrids[1]->columncfl("u_adjfactor","OpenCFLfs()");
$objGrids[1]->columnattributes("u_adjperc","disabled");

$objGrids[1]->columnwidth("u_adjtype",30);
$objGrids[1]->width = 950;
$objGrids[1]->height = 180;

$objGrids[2]->columnattributes("u_marketvalue","disabled");
$objGrids[2]->columnattributes("u_totalcount","disabled");
$objGrids[2]->columnwidth("u_planttype",25);
$objGrids[2]->columnwidth("u_nonproductive",15);
$objGrids[2]->width = 950;
$objGrids[2]->height = 180;

$objGrids[3]->columnattributes("u_basevalue","disabled");
$objGrids[3]->columnattributes("u_adjunitvalue","disabled");
$objGrids[3]->columnwidth("u_strip",15);
$objGrids[3]->width = 950;
$objGrids[3]->height = 180;

//$objGrids[4]->columncfl("u_kind","OpenCFLfs()");
$objGrids[4]->columncfl("u_description","OpenCFLfs()");
$objGrids[4]->columnattributes("u_basevalue","disabled");
//$objGrids[4]->columnattributes("u_unitvalue","disabled");
$objGrids[4]->columnwidth("u_kind",18);
$objGrids[4]->width = 950;
$objGrids[4]->height = 180;


$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);

$addoptions = false;
?> 

