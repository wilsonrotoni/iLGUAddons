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

function onPrepareChildEditGPSLGUAcctg($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T1":	
			$filerExp = " ORDER BY U_ITEMNAME";
			break;
			
	}
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
	
	$page->setitem("docstatus","Draft");
	$page->setitem("u_yr",date('Y'));
	$page->setitem("u_date",currentdate());
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

$page->businessobject->items->setcfl("u_projcode","OpenCFLfs()");
$page->businessobject->items->setcfl("u_date","Calendar");

$page->businessobject->items->seteditable("name",false);
$page->businessobject->items->seteditable("docstatus",false);

//$objGrids[0]->columndataentry("u_department","type","select");
//$objGrids[0]->columndataentry("u_department","options",array("loaddepartments","",":[Select]"));

$objGrids[0]->columncfl("u_itemno","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemname","OpenCFLfs()");

/*
$objGrids[0]->columnlnkbtn("u_yr","OpenLnkBtnu_GLDeptBudget()");
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
$objGrids[0]->columnlnkbtn("u_m12","OpenLnkBtnu_GLDeptBudget()");
*/

//$objGrids[0]->columntitle("u_expgroupno","#");
//$objGrids[0]->columntitle("u_expclass","Class");
//$objGrids[0]->columntitle("u_m1","Monthly");
$objGrids[0]->columnvisibility("u_q1",false);
$objGrids[0]->columnvisibility("u_q2",false);
$objGrids[0]->columnvisibility("u_q3",false);
$objGrids[0]->columnvisibility("u_q4",false);
$objGrids[0]->columninput("u_yr","type","text");
$objGrids[0]->columninput("u_m1","type","text");
$objGrids[0]->columninput("u_m2","type","text");
$objGrids[0]->columninput("u_m3","type","text");
$objGrids[0]->columninput("u_m4","type","text");
$objGrids[0]->columninput("u_m5","type","text");
$objGrids[0]->columninput("u_m6","type","text");
$objGrids[0]->columninput("u_m7","type","text");
$objGrids[0]->columninput("u_m8","type","text");
$objGrids[0]->columninput("u_m9","type","text");
$objGrids[0]->columninput("u_m10","type","text");
$objGrids[0]->columninput("u_m11","type","text");
$objGrids[0]->columninput("u_m12","type","text");

$objGrids[0]->columntitle("u_m1","Jan");
$objGrids[0]->columntitle("u_m2","Feb");
$objGrids[0]->columntitle("u_m3","Mar");
$objGrids[0]->columntitle("u_m4","Apr");
$objGrids[0]->columntitle("u_m5","May");
$objGrids[0]->columntitle("u_m6","Jun");
$objGrids[0]->columntitle("u_m7","Jul");
$objGrids[0]->columntitle("u_m8","Aug");
$objGrids[0]->columntitle("u_m9","Sep");
$objGrids[0]->columntitle("u_m10","Oct");
$objGrids[0]->columntitle("u_m11","Nov");
$objGrids[0]->columntitle("u_m12","Dec");
$objGrids[0]->columntitle("u_yr","Annual");

$objGrids[0]->columnwidth("u_itemno",10);
$objGrids[0]->columnwidth("u_itemname",45);
$objGrids[0]->columnwidth("u_uom",8);
$objGrids[0]->columnwidth("u_m1",7);
$objGrids[0]->columnwidth("u_m2",7);
$objGrids[0]->columnwidth("u_m3",7);
$objGrids[0]->columnwidth("u_m4",7);
$objGrids[0]->columnwidth("u_m5",7);
$objGrids[0]->columnwidth("u_m6",7);
$objGrids[0]->columnwidth("u_m7",7);
$objGrids[0]->columnwidth("u_m8",7);
$objGrids[0]->columnwidth("u_m9",7);
$objGrids[0]->columnwidth("u_m10",7);
$objGrids[0]->columnwidth("u_m11",7);
$objGrids[0]->columnwidth("u_m12",7);
$objGrids[0]->columnwidth("u_yr",7);

$objGrids[0]->columnattributes("u_uom","disabled");
$objGrids[0]->columnattributes("u_totalamount","disabled");
//$objGrids[0]->columnwidth("u_expgroupno",3);
//$objGrids[0]->columnwidth("u_expclass",5);
//$objGrids[0]->columnattributes("u_glacctname","disabled");

$objGrids[0]->automanagecolumnwidth = false;

$objGrids[1]->columncfl("u_glacctno","OpenCFLfs()");
$objGrids[1]->columncfl("u_glacctname","OpenCFLfs()");

$objGrids[1]->columncfl("u_slcode","OpenCFLfs()");
$objGrids[1]->columncfl("u_sldesc","OpenCFLfs()");

$objGrids[1]->columninput("u_yr","type","text");
$objGrids[1]->columninput("u_m1","type","text");
$objGrids[1]->columninput("u_m2","type","text");
$objGrids[1]->columninput("u_m3","type","text");
$objGrids[1]->columninput("u_m4","type","text");
$objGrids[1]->columninput("u_m5","type","text");
$objGrids[1]->columninput("u_m6","type","text");
$objGrids[1]->columninput("u_m7","type","text");
$objGrids[1]->columninput("u_m8","type","text");
$objGrids[1]->columninput("u_m9","type","text");
$objGrids[1]->columninput("u_m10","type","text");
$objGrids[1]->columninput("u_m11","type","text");
$objGrids[1]->columninput("u_m12","type","text");

$objGrids[1]->columnwidth("u_glacctno",12);
$objGrids[1]->columnwidth("u_glacctname",45);
$objGrids[1]->columnwidth("u_slcode",6);
$objGrids[1]->columnwidth("u_sldesc",45);
$objGrids[1]->columnwidth("u_m1",11);
$objGrids[1]->columnwidth("u_m2",11);
$objGrids[1]->columnwidth("u_m3",11);
$objGrids[1]->columnwidth("u_m4",11);
$objGrids[1]->columnwidth("u_m5",11);
$objGrids[1]->columnwidth("u_m6",11);
$objGrids[1]->columnwidth("u_m7",11);
$objGrids[1]->columnwidth("u_m8",11);
$objGrids[1]->columnwidth("u_m9",11);
$objGrids[1]->columnwidth("u_m10",11);
$objGrids[1]->columnwidth("u_m11",11);
$objGrids[1]->columnwidth("u_m12",11);
$objGrids[1]->columnwidth("u_yr",13);

$objGrids[1]->automanagecolumnwidth = false;

$addoptions = false;

$objMaster->reportaction="QS";
?> 

