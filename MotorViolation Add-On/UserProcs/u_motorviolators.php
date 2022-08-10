<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSMotorViolation");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSMotorViolation");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSMotorViolation");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSMotorViolation");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSMotorViolation");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSMotorViolation");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSMotorViolation");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSMotorViolation");
$page->businessobject->events->add->afterEdit("onAfterEditGPSMotorViolation");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSMotorViolation");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSMotorViolation");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSMotorViolation");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSMotorViolation");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSMotorViolation");
$page->businessobject->events->add->afterDelete("onAfterDeleteGPSMotorViolation");


function onCustomActionGPSMotorViolation($action) {
	
	 global $objConnection;
	global $page;
	global $objGrids;
	
	return true;
}

function onBeforeDefaultGPSMotorViolation() { 
global $page;
	global $appdata;
	

	return true;
}
function onAfterDefaultGPSMotorViolation() { 
global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
$page->setitem("u_appdate",currentdate());



	//global $page;
//global $objConnection;
	//global $objGridA;
	//global $objGridB;
	//global $objGridC;
	
	//$objGridA->clear();
	//$objGridB->clear();
	//$objGridC->clear();
	
	//$objRs = new recordset(null,$objConnection);
	//$objRs->queryopen("SELECT  B.U_APPDATE, C.U_FEEDESC,C.U_OFFENSE,B.U_VEHICLETYPE,B.U_PLATENO,B.U_TICKETBY,B.U_TOTALAMOUNT FROM U_MOTORVIOLATORS A inner JOIN U_MOTORVIOLATIONAPPS B ON A.COMPANY=B.COMPANY and a.code=b.u_licenseno AND A.BRANCH=B.BRANCH INNER JOIN U_MOTORVIOLATIONAPPFEES C ON B.DOCID=C.DOCID AND C.COMPANY=B.COMPANY AND C.BRANCH=B.BRANCH WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.code='".$page->getitemstring("code")."' order by b.u_appdate asc");
	//$adjvalue=0;
	//while ($objRs->queryfetchrow("NAME")) {
		//$objGridB->addrow();
		//$objGridB->setitem(null,"u_appdate",($objRs->fields["U_APPDATE"]));
		//$objGridB->setitem(null,"u_feedesc",($objRs->fields["U_FEEDESC"]));
		//$objGridB->setitem(null,"u_offense",($objRs->fields["U_OFFENSE"]));
		//$objGridB->setitem(null,"u_vehicletype",($objRs->fields["U_VEHICLETYPE"]));
		//$objGridB->setitem(null,"u_plateno",($objRs->fields["U_PLATENO"]));
		//$objGridB->setitem(null,"u_ticketby",($objRs->fields["U_TICKETBY"]));
		//$objGridB->setitem(null,"u_totalamount",formatNumericAmount($objRs->fields["U_TOTALAMOUNT"]));
	
	//}
	
	return true;
}



function onPrepareAddGPSMotorViolation(&$override) { 
	return true;
}

function onBeforeAddGPSMotorViolation() { 
	return true;
}

function onAfterAddGPSMotorViolation() { 
	return true;
}

function onPrepareEditGPSMotorViolation(&$override) { 



	
	
	
	return true;
}

function onBeforeEditGPSMotorViolation() { 
$objGrid->addcolumn("action");
	$objGrid->columntitle("action","Action");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","");
	$objGrid->columnwidth("action",25);
	$objGrid->columnvisibility("action",false);
	return true;
}

function onAfterEditGPSMotorViolation() { 
global $page;
	global $objConnection;
	//global $objGridA;
	global $objGridB;
	//global $objGridC;
	
	//$objGridA->clear();
	$objGridB->clear();
	//$objGridC->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT D.DOCSTATUS, B.U_APPDATE, C.U_FEEDESC,C.U_FEECODE,C.U_OFFENSE,B.U_VEHICLETYPE, 
	B.U_PLATENO,B.U_TICKETBY,D.U_TOTALAMOUNT,C.U_DISCOUNT,E.U_DATE,E.U_PENALTYAMOUNT
	FROM U_MOTORVIOLATORS A
	inner JOIN U_MOTORVIOLATIONAPPS B ON A.COMPANY=B.COMPANY and a.code=b.u_licenseno AND A.BRANCH=B.BRANCH
	INNER JOIN U_MOTORVIOLATIONAPPFEES C ON B.DOCID=C.DOCID AND C.COMPANY=B.COMPANY AND C.BRANCH=B.BRANCH
	INNER JOIN U_LGUBILLS D ON A.CODE=D.U_CUSTNO AND C.COMPANY=D.COMPANY AND C.BRANCH=D.BRANCH
	INNER JOIN U_LGUPOS E ON D.DOCNO=E.U_BILLNO AND D.COMPANY=E.COMPANY AND D.BRANCH=E.BRANCH
	
	WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.code='".$page->getitemstring("code")."' order by c.u_feecode asc");
	//var_dump($objrs->sqls);	
	$adjvalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridB->addrow();
		$objGridB->setitem(null,"docstatus",($objRs->fields["DOCSTATUS"]));
		$objGridB->setitem(null,"u_appdate",($objRs->fields["U_APPDATE"]));
		$objGridB->setitem(null,"u_feedesc",($objRs->fields["U_FEEDESC"]));
		$objGridB->setitem(null,"u_feecode",($objRs->fields["U_FEECODE"]));
		$objGridB->setitem(null,"u_offense",($objRs->fields["U_OFFENSE"]));
		$objGridB->setitem(null,"u_vehicletype",($objRs->fields["U_VEHICLETYPE"]));
		$objGridB->setitem(null,"u_plateno",($objRs->fields["U_PLATENO"]));
		$objGridB->setitem(null,"u_ticketby",($objRs->fields["U_TICKETBY"]));
		$objGridB->setitem(null,"u_discount",formatNumericAmount($objRs->fields["U_DISCOUNT"]));
		//$objGridB->setitem(null,"u_amount",formatNumericAmount($objRs->fields["U_AMOUNT"]));
		$objGridB->setitem(null,"u_totalamount",formatNumericAmount($objRs->fields["U_TOTALAMOUNT"]));
		$objGridB->setitem(null,"u_date",formatNumericAmount($objRs->fields["U_DATE"]));
		$objGridB->setitem(null,"u_penaltyamount",formatNumericAmount($objRs->fields["U_PENALTYAMOUNT"]));
		$objGridB->setitem(null,"u_date",($objRs->fields["U_DATE"]));
		
			
			if ($objrs->fields["DOCSTATUS"]==('Open')) {
				//$objGrid->setitem(null,"docstatus","Expired");
				$objGridB->setitem(null,"docstatus","PENDING");
			}elseif ($objrs->fields["DOCSTATUS"]==('Closed')) {
				//$objGrid->setitem(null,"docstatus","Expired");
				$objGridB->setitem(null,"docstatus","PAID");
			} 
		
	}
	return true;
}

function onPrepareUpdateGPSMotorViolation(&$override) { 
	return true;
}

function onBeforeUpdateGPSMotorViolation() { 
	return true;
}

function onAfterUpdateGPSMotorViolation() { 
	return true;
}

function onPrepareDeleteGPSMotorViolation(&$override) { 
	return true;
}

function onBeforeDeleteGPSMotorViolation() { 
	return true;
}

function onAfterDeleteGPSMotorViolation() { 
	return true;
}
$page->businessobject->items->setcfl("u_birthday","Calendar");
$page->businessobject->items->setcfl("u_appdate","Calendar");

//$page->businessobject->items->seteditable("u_firstname",false);
//$page->businessobject->items->seteditable("u_lastname",false);
//$page->businessobject->items->seteditable("u_middlename",false);
$page->businessobject->items->seteditable("u_appdate",false);

//$objGridB->columncfl("u_feedesc","OpenCFLfs()");


$objGridB = new grid("T102");
$objGridB->addcolumn("docstatus");
$objGridB->addcolumn("u_appdate");
$objGridB->addcolumn("u_feedesc");
$objGridB->addcolumn("u_feecode");
$objGridB->addcolumn("u_offense");
$objGridB->addcolumn("u_vehicletype");
$objGridB->addcolumn("u_ticketby");
$objGridB->addcolumn("u_plateno");
$objGridB->addcolumn("u_totalamount");
$objGridB->addcolumn("u_discount");
$objGridB->addcolumn("u_penaltyamount");
$objGridB->addcolumn("u_date");

$objGridB->columntitle("docstatus","Status");
$objGridB->columntitle("u_appdate","App.Date");
$objGridB->columntitle("u_feedesc","Violation Name");
$objGridB->columntitle("u_feecode","Code");
$objGridB->columntitle("u_offense","Offense");
$objGridB->columntitle("u_vehicletype","Vehicle");
$objGridB->columntitle("u_ticketby","Ticket By");
$objGridB->columntitle("u_plateno","Plate No.");
$objGridB->columntitle("u_totalamount","Amount");
$objGridB->columntitle("u_discount","Discount");
$objGridB->columntitle("u_penaltyamount","Penalty");
$objGridB->columntitle("u_date","Paid Date");

$objGridB->columnwidth("docstatus",8);
$objGridB->columnwidth("u_appdate",10);
$objGridB->columnwidth("u_feedesc",30);
$objGridB->columnwidth("u_feecode",5);
$objGridB->columnwidth("u_offense",10);
$objGridB->columnwidth("u_vehicletype",10);
$objGridB->columnwidth("u_ticketby",15);
$objGridB->columnwidth("u_plateno",10);
$objGridB->columnwidth("u_totalamount",10);
$objGridB->columnwidth("u_discount",10);
$objGridB->columnwidth("u_penaltyamount",10);
$objGridB->columnwidth("u_date",10);

$objGridB->columnalignment("docstatus","left");
$objGridB->columnalignment("u_appdate","left");
$objGridB->columnalignment("u_feedesc","left");
$objGridB->columnalignment("u_offense","left");
$objGridB->columnalignment("u_feecode","left");
$objGridB->columnalignment("u_vehicletype","left");
$objGridB->columnalignment("u_ticketby","left");
$objGridB->columnalignment("u_plateno","left");
$objGridB->columnalignment("u_totalamount","left");
$objGridB->columnalignment("u_discount","left");
$objGridB->columnalignment("u_penaltyamount","left");
$objGridB->columnalignment("u_date","left");


$objGridB->columndataentry("u_appdate","type","label");
$objGridB->columndataentry("u_ticketby","type","label");
$objGridB->columndataentry("u_plateno","type","label");

//$objGridB->dataentry = true;
$objGridB->showactionbar = false;
$objGridB->setaction("reset",false);
$objGridB->setaction("add",false);
$objGridB->height = 60;


?> 

