<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
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

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSProjects");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $objConnection;
	global $objGrids;
	$page->setitem("u_date",currentdate());
	$page->setitem("u_time",currenttime());
	if ($objGrids[0]->recordcount==0) {
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select code,name from u_hissections where u_type in ('ER','OP','IP','NURSE','PHARMACY','CSR','LABORATORY','RADIOLOGY','SPLROOM','PULMONARY','HEARTSTATION','PMR','DIALYSIS','DIETARY')");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_department",$objRs->fields["code"]);
			$objGrids[0]->setitem(null,"u_creditlimit",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_prepaid",-1);
		}
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
	global $objConnection;
	global $objGrids;
	global $page;
	$page->setitem("u_date",currentdate());
	$page->setitem("u_time",currenttime());
	/*
	if ($objGrids[0]->recordcount==0) {
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select code,name from u_hissections where u_type in ('ER','OP','IP','NURSE','PHARMACY','CSR','LABORATORY','RADIOLOGY','SPLROOM','PULMONARY','HEARTSTATION','PMR','DIALYSIS','DIETARY')");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_department",$objRs->fields["code"]);
			$objGrids[0]->setitem(null,"u_prepaid",-1);
		}
	}
	*/	
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

function onDrawGridColumnLabelGPSProjects($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
			if ($column=="u_department") {
				$objRs->queryopen("select name from u_hissections where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}	
			break;
	}
}

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_reftype",false);
$page->businessobject->items->seteditable("u_refno",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_time",false);
$page->businessobject->items->seteditable("u_date",false);

$page->businessobject->items->seteditable("u_billing",false);

$objGrids[0]->columntitle("u_department","Section");
$objGrids[0]->columntitle("u_prepaid","");
$objGrids[0]->columnwidth("u_department",25);
$objGrids[0]->columnwidth("u_prepaid",18);
$objGrids[0]->columninput("u_prepaid","type","select");
$objGrids[0]->columninput("u_prepaid","options",array("loadudfenums","u_hisbilltermsupdsections:prepaid",""));
$objGrids[0]->columninput("u_creditlimit","type","text");
$objGrids[0]->width= 489;
$objGrids[0]->height=230;
$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;

$addoptions = false;
$deleteoption = false;
$page->toolbar->setaction("new",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("find",false);
?> 

