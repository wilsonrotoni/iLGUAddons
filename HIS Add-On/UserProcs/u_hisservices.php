<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	if ($page->settings->data["autogenerate"]==1) {
		$page->setitem("u_series",getSeries($page->getobjectdoctype(),"Auto",$objConnection,false));
		if ($page->getitemstring("u_series")!=-1) {
			$page->setitem("code", getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,false));
		}	

	} else {
		$page->setitem("u_series",-1);
		$page->setitem("code", "");
	}	
	getPricelistsGPSHIS('C');	
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
		$objMaster->code = getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,true);
	}	
	return true;
}

function onAfterAddGPSHIS() { 
	$actionReturn = updatePricelistsGPSHIS();
	return $actionReturn;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	getPricelistsGPSHIS('C');
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	$actionReturn = updatePricelistsGPSHIS();
	return $actionReturn;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	$actionReturn = updatePricelistsGPSHIS(true);
	return $actionReturn;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

$page->objectdoctype = "ITEM";

include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hispricelists.php");

$addoptions = false;

?> 

