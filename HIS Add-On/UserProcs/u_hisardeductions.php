<?php

//include_once("../Addons/GPS/HIS Add-On/Userprocs/sls/u_hishealthins.php"); 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
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

$groupby = "";
$isgroup = false;

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$data = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $data;
	global $page;
	$data["u_docdate"] = $page->getitemstring("u_docdate");
	//if ($data["u_docdate"]=="") $data["u_docdate"] = currentdate();
	$data["u_custgroup"] = $page->getitemstring("u_custgroup");
	$data["u_custgroupname"] = $page->getitemstring("u_custgroupname");
	$data["u_preparedby"] = $page->getitemstring("u_preparedby");
	$data["u_glacctno"] = $page->getitemstring("u_glacctno");
	$data["u_glacctname"] = $page->getitemstring("u_glacctname");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $data;
	global $u_trxtype;
	global $objGrids;
	
	$page->privatedata["docstatus"] = "";
	
	$page->setitem("u_docdate",$data["u_docdate"]);
	$page->setitem("u_custgroup",$data["u_custgroup"]);
	$page->setitem("u_custgroupname",$data["u_custgroupname"]);
	$page->setitem("u_preparedby",$data["u_preparedby"]);
	$page->setitem("u_glacctno",$data["u_glacctno"]);
	$page->setitem("u_glacctname",$data["u_glacctname"]);
	
	$objRs = new recordset(null,$objConnection);
	
	$objGrids[0]->clear();
	$u_totalbalance = 0;
	
	//$objRs->setdebug();
	if ($page->getitemstring("u_custgroup")!="") {
		$objRs->queryopen("select a.custno, a.custname, b.othertrxtype, sum(b.balanceamount) as balanceamount from customers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.itemtype='C' and b.itemno=a.custno and b.balanceamount<>0 inner join journalvouchers c on c.company=b.company and c.branch=b.branch and c.docid=b.docid and c.docdate<='".$page->getitemdate("u_docdate")."' where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.custgroup='".$page->getitemstring("u_custgroup")."' group by a.custno, b.othertrxtype having balanceamount<>0 order by a.custname, c.docdate, c.docno");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_selected",0);
			$objGrids[0]->setitem(null,"u_custno",$objRs->fields["custno"]);
			$objGrids[0]->setitem(null,"u_custname",$objRs->fields["custname"]);
			$objGrids[0]->setitem(null,"u_trxtype",$objRs->fields["othertrxtype"]);
			$objGrids[0]->setitem(null,"u_balance",formatNumericAmount($objRs->fields["balanceamount"]));
			$objGrids[0]->setitem(null,"u_deduction",formatNumericAmount(0));
			$u_totalbalance+=$objRs->fields["balanceamount"];
		}
	}
	$page->setitem("u_totalbalance",formatNumericAmount($u_totalbalance));
	$page->setitem("u_totaldeduction",formatNumericAmount(0));
	return true;
}


function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $groupby;
	global $isgroup;
	global $page;
	global $objGrids;
	switch ($column) {
		case "u_custno":
			//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
			if ($groupby!=$objGrids[0]->getitemstring($row,"u_custno")) {
				$groupby = $objGrids[0]->getitemstring($row,"u_custno");
				$isgroup = false;
			} else {
				$isgroup = true;
				$label = "";
			}	
			break;
		case "u_custname":
			if ($isgroup) $label = "";
			break;
	}
	
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
	global $page;
	$page->privatedata["docstatus"] = $page->getitemstring("docstatus");
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

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_glacctno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_glacctname","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totaldeduction",false);

//$objGrids[0]->columnwidth("u_selected",2);
$objGrids[0]->columnwidth("u_custno",12);
$objGrids[0]->columnwidth("u_custname",47);
$objGrids[0]->columnwidth("u_trxtype",15);
$objGrids[0]->columninput("u_deduction","type","text");
//$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columnlnkbtn("u_balance","OpenLnkBtnARs()","Show List of A/R");
$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;

$objGridARs = new grid("T10");
//$objGridARs->addcolumn("docdate");
//$objGridARs->addcolumn("docno");
//$objGridARs->addcolumn("refno");
$objGridARs->addcolumn("feetype");
$objGridARs->addcolumn("amount");
$objGridARs->addcolumn("balance");
$objGridARs->columntitle("docdate","Date");	
$objGridARs->columntitle("docno","No.");	
$objGridARs->columntitle("refno","Ref No.");	
$objGridARs->columntitle("feetype","Particulars");	
$objGridARs->columntitle("amount","Total");	
$objGridARs->columntitle("balance","Balance");	
$objGridARs->columnwidth("docdate",9);	
$objGridARs->columnwidth("docno",10);	
$objGridARs->columnwidth("refno",10);	
$objGridARs->columnwidth("feetype",25);	
$objGridARs->columnwidth("amount",8);	
$objGridARs->columnwidth("balance",8);	
$objGridARs->columnalignment("amount","right");
$objGridARs->columnalignment("balance","right");
$objGridARs->columnlnkbtn("docno","OpenLnkBtnDocNo()");
$objGridARs->automanagecolumnwidth = false;
$objGridARs->width=376;
$objGridARs->height = 150;

$addoptions = true;
$deleteoption = false;

//$page->toolbar->setaction("update",false);
$page->toolbar->setaction("new",false);

?> 

