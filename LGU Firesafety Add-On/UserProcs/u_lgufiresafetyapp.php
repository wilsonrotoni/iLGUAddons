<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUFiresafety");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUFiresafety");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUFiresafety");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUFiresafety");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUFiresafety");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUFiresafety");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUFiresafety");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUFiresafety");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUFiresafety");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUFiresafety");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUFiresafety");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUFiresafety");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUFiresafety");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUFiresafety");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUFiresafety");

function onCustomActionGPSLGUFiresafety($action) {
	return true;
}

function onBeforeDefaultGPSLGUFiresafety() { 
	return true;
}

function onAfterDefaultGPSLGUFiresafety() { 
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
        
        
	$page->setitem("u_date",currentdate());
	$page->setitem("docstatus","Encoding");
        
        $objGrids[0]->clear();
	$objGrids[1]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name from u_lgufirereqs order by code asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_reqcode",$objRs->fields["code"]);
		$objGrids[1]->setitem(null,"u_reqdesc",$objRs->fields["name"]);
		
	}
        
	return true;
}

function onPrepareAddGPSLGUFiresafety(&$override) { 
	return true;
}

function onBeforeAddGPSLGUFiresafety() { 
	return true;
}

function onAfterAddGPSLGUFiresafety() { 
	return true;
}

function onPrepareEditGPSLGUFiresafety(&$override) { 
	return true;
}

function onBeforeEditGPSLGUFiresafety() { 
	return true;
}

function onAfterEditGPSLGUFiresafety() { 
	return true;
}

function onPrepareUpdateGPSLGUFiresafety(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUFiresafety() { 
	return true;
}

function onAfterUpdateGPSLGUFiresafety() { 
	return true;
}

function onPrepareDeleteGPSLGUFiresafety(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUFiresafety() { 
	return true;
}

function onAfterDeleteGPSLGUFiresafety() { 
	return true;
}

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_expdate","Calendar");
$page->businessobject->items->setcfl("u_dateinspect","Calendar");

$page->businessobject->items->setcfl("u_bplappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_inspector","OpenCFLfs()");


$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");

$objGrids[1]->columnwidth("u_chck",2);
$objGrids[1]->columntitle("u_chck","*");
$objGrids[1]->columnwidth("u_reqdesc",40);
$objGrids[1]->columnwidth("u_remarks",25);
$objGrids[1]->columninput("u_chck","type","checkbox");
$objGrids[1]->columninput("u_chck","value",1);
$objGrids[1]->columninput("u_remarks","type","text");
$objGrids[1]->columnvisibility("u_reqcode",false);
$objGrids[1]->dataentry = false;

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
?> 

