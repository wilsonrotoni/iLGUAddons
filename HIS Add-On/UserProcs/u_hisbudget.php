<?php
 
include_once("./sls/enumyear.php");

unset($enumyear["-"]);
//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
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
	global $objConnection;
	global $objGrids;
	global $page;
	
	$page->setitem("u_yr",date('Y'));
	$objGrids[0]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select formatcode,acctname from chartofaccounts where postable=1 and (formatcode like '4%' or formatcode like '5%' or formatcode like '6%') order by formatcode");  
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_glacctno",$objRs->fields["formatcode"]);
		$objGrids[0]->setitem(null,"u_glacctname",$objRs->fields["acctname"]);
		$objGrids[0]->setitem(null,"u_yr",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m1",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m2",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m3",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m4",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m5",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m6",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m7",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m8",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m9",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m10",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m11",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_m12",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_q1",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_q2",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_q3",formatNumericAmount(0));
		$objGrids[0]->setitem(null,"u_q4",formatNumericAmount(0));
	}
	
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
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

//$page->businessobject->items->setcfl("u_glacctno","()");
$page->businessobject->items->seteditable("name",false);

//$objGrids[0]->columndataentry("u_department","type","select");
//$objGrids[0]->columndataentry("u_department","options",array("loaddepartments","",":[Select]"));

$objGrids[0]->columncfl("u_glacctno","OpenCFLfs()");
$objGrids[0]->columncfl("u_glacctname","OpenCFLfs()");

/*$objGrids[0]->columnlnkbtn("u_yr","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m1","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m2","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m3","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m4","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m5","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m6","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m7","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m8","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m9","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m10","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m11","OpenLnkBtnu_GLDeptBudget()");
$objGrids[0]->columnlnkbtn("u_m12","OpenLnkBtnu_GLDeptBudget()");*/

$objGrids[0]->columntitle("u_yr","Yearly");
$objGrids[0]->columntitle("u_m1","Monthly");
$objGrids[0]->columnvisibility("u_q1",false);
$objGrids[0]->columnvisibility("u_q2",false);
$objGrids[0]->columnvisibility("u_q3",false);
$objGrids[0]->columnvisibility("u_q4",false);
$objGrids[0]->columnvisibility("u_m2",false);
$objGrids[0]->columnvisibility("u_m3",false);
$objGrids[0]->columnvisibility("u_m4",false);
$objGrids[0]->columnvisibility("u_m5",false);
$objGrids[0]->columnvisibility("u_m6",false);
$objGrids[0]->columnvisibility("u_m7",false);
$objGrids[0]->columnvisibility("u_m8",false);
$objGrids[0]->columnvisibility("u_m9",false);
$objGrids[0]->columnvisibility("u_m10",false);
$objGrids[0]->columnvisibility("u_m11",false);
$objGrids[0]->columnvisibility("u_m12",false);
$objGrids[0]->columnattributes("u_m1","disabled");
$objGrids[0]->columnwidth("u_glacctno",20);
$objGrids[0]->columnwidth("u_glacctname",40);
$objGrids[0]->columnwidth("u_yr",15);
//$objGrids[0]->columnattributes("u_glacctname","disabled");

$addoptions = false;
?> 

