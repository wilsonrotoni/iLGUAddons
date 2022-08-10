<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");


function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	if ($page->settings->data["autogenerate"]==1) {
		$page->setitem("u_series",getSeries($page->objectcode,"HMO",$objConnection,false));
		if ($page->getitemstring("u_series")!=-1) {
			$page->setitem("u_type","HMO");
			$page->setitem("code", getNextSeriesNoByBranch($page->objectcode,$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,false));
		}	

	} else {
		$page->setitem("u_series",-1);
		$page->setitem("code", "");
	}	
	
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
	global $objConnection;
	global $objMaster;
	global $page;
	if ($page->getitemstring("u_series")!=-1) {
		$objMaster->code = getNextSeriesNoByBranch($page->objectcode,$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,true);
	}	
	return true;
}

function onAfterAddGPSHIS() { 
	return true;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $objGrid;
	$objRs = new recordset(null,$objConnection);
	
	$objRs->queryopen("select * from u_hishealthincts where u_inscode='".$page->getitemstring("code")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"code",$objRs->fields["CODE"]);
		$objGrid->setitem(null,"name",$objRs->fields["NAME"]);
		$objGrid->setitem(null,"u_rvufr",$objRs->fields["U_RVUFR"]);
		$objGrid->setitem(null,"u_rvuto",$objRs->fields["U_RVUTO"]);
		$objGrid->setitem(null,"rowstat","E");
	}
	
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	return true;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	return true;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

$page->businessobject->items->setcfl("u_glacctno","OpenCFLchartofaccounts()");

$page->businessobject->items->seteditable("u_glacctname",false);

$objGrids[0]->columncfl("u_icdcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_icddesc","OpenCFLfs()");

$objGrids[0]->columnwidth("u_icdcode",30);
$objGrids[0]->columnwidth("u_icddesc",80);

$objGrid = new grid("T101");
$objGrid->addcolumn("code");
$objGrid->addcolumn("name");
$objGrid->addcolumn("u_rvufr");
$objGrid->addcolumn("u_rvuto");
$objGrid->columntitle("code","Code");
$objGrid->columntitle("name","Description");
$objGrid->columntitle("u_rvufr","From RVU");
$objGrid->columntitle("u_rvuto","To RVU");
$objGrid->columnwidth("code",25);
$objGrid->columnwidth("name",30);
$objGrid->columnwidth("u_rvufr",10);
$objGrid->columnwidth("u_rvuto",10);
$objGrid->dataentry = true;
$objGrid->setaction("reset",false);
$objGrid->setaction("add",false);


$addoptions = false;
$deleteoption = false;

//$page->bodyclass = "yui-skin-sam";
?> 

