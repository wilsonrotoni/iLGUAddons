<?php
 
//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

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
	//$page->setitem("code",getNextNoByBranch('U_HISGUARANTORS',0,$objConnection,false));
	if ($page->settings->data["autogenerate"]==1) {
//		$page->setitem("u_series",getDefaultSeries($page->objectcode,$objConnection));
//		$page->setitem("code", getNextSeriesNoByBranch($page->objectcode,$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,false));
		//$page->setitem("u_series",getDefaultSeries($page->objectcode,$objConnection));
		$page->setitem("u_series",getSeries($page->objectcode,"Company",$objConnection,false));
		if ($page->getitemstring("u_series")!=-1) {
			$page->setitem("u_type","Company");
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
	global $page;
	global $objGrids;
	
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

$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->setcfl("u_glacctno","OpenCFLchartofaccounts()");

$page->businessobject->items->seteditable("u_glacctname",false);

$addoptions=false;
//$deleteoption=false;

?> 

