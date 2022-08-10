<?php
 
include_once("./sls/enumyear.php");

unset($enumyear["-"]);
//$page->businessobject->events->add->customAction("onCustomActionGPSLGUAcctg");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUAcctg");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUAcctg");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUAcctg");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUAcctg");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUAcctg");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUAcctg");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUAcctg");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUAcctg");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUAcctg");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUAcctg");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUAcctg");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUAcctg");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUAcctg");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUAcctg");

$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSLGUAcctg");
$page->businessobject->events->add->drawPopupMenu("onDrawPopupMenuGPSLGUAcctg");

function onDrawPopupMenuGPSLGUAcctg($objPopupMenu) {
	if (isEditMode()) {
		$objPopupMenu->addline("popupTable");
		$objPopupMenu->additem("popupTable","Download","OpenPopupDownloadGPSLGUAcctg()");
	}
}

function onPrepareChildEditGPSLGUAcctg($objGrid,$objTable,$tablename,&$override,&$filerExp) {
/*	switch ($tablename) {
		case "T1":	
			$filerExp = " ORDER BY U_REFCODE";
			break;
			
	}*/
	return true;
}

function onCustomActionGPSLGUAcctg($action) {
	return true;
}

function onBeforeDefaultGPSLGUAcctg() { 
	return true;
}

function onAfterDefaultGPSLGUAcctg() { 
	global $objConnection;
	global $objGrids;
	global $page;
	
	$page->setitem("docstatus","Open");
	$page->setitem("u_yr",date('Y'));
	$page->setitem("u_preparedby",$_SESSION["username"]);
	$objGrids[0]->clear();
	
	$objRs = new recordset(null,$objConnection);
	/*$objRs->queryopen("select formatcode,acctname, u_acctcode, 1 as seqno from chartofaccounts where postable=1 and budget=1 and u_expclass='PS' union all 
						select formatcode,acctname, u_acctcode, 2 as seqno from chartofaccounts where postable=1 and budget=1 and u_expclass='MOOE' union all 
						select formatcode,acctname, u_acctcode, 3 as seqno from chartofaccounts where postable=1 and budget=1 and u_expclass='FE' union all 
						select formatcode,acctname, u_acctcode, 4 as seqno from chartofaccounts where postable=1 and budget=1 and u_expclass='CO' order by seqno, u_acctcode");  
	*/
	/*$objRs->queryopen("select formatcode,acctname, u_acctcode, u_expgroupno, u_expclass from chartofaccounts where postable=1 and budget=1 order by u_expgroupno, acctname");  
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_expgroupno",$objRs->fields["u_expgroupno"]);
		$objGrids[0]->setitem(null,"u_expclass",$objRs->fields["u_expclass"]);
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
	}*/
	
	return true;
}

function onPrepareAddGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeAddGPSLGUAcctg() { 
	return true;
}

function onAfterAddGPSLGUAcctg() { 
	return true;
}

function onPrepareEditGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeEditGPSLGUAcctg() { 
	return true;
}

function onAfterEditGPSLGUAcctg() { 
	return true;
}

function onPrepareUpdateGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUAcctg() { 
	return true;
}

function onAfterUpdateGPSLGUAcctg() { 
	return true;
}

function onPrepareDeleteGPSLGUAcctg(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUAcctg() { 
	return true;
}

function onAfterDeleteGPSLGUAcctg() { 
	return true;
}

$page->businessobject->items->seteditable("name",false);
$page->businessobject->items->seteditable("docstatus",false);

//$objGrids[0]->columndataentry("u_department","type","select");
//$objGrids[0]->columndataentry("u_department","options",array("loaddepartments","",":[Select]"));

//$objGrids[0]->columncfl("u_itemno","OpenCFLfs()");
//$objGrids[0]->columncfl("u_itemname","OpenCFLfs()");

$objGrids[0]->columnwidth("u_refcode",27);
$objGrids[0]->columnwidth("u_description",30);
$objGrids[0]->columnwidth("u_start",12);
$objGrids[0]->columnwidth("u_completion",12);
$objGrids[0]->columnwidth("u_expectedoutput",30);
$objGrids[0]->columnwidth("u_funding",10);
$objGrids[0]->columnwidth("u_cctc",10);
$objGrids[0]->columnwidth("u_bold",2);

$objGrids[0]->columndataentry("u_bold","type","checkbox");
$objGrids[0]->columndataentry("u_bold","value",1);
$objGrids[0]->columnalignment("u_bold","center");

$objGrids[0]->columninput("u_bold","type","checkbox");
$objGrids[0]->columninput("u_bold","value",1);

$objGrids[0]->automanagecolumnwidth = false;

$addoptions = false;

$objMaster->reportaction="QS";
?> 

