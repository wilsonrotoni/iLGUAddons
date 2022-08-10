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
	
	
	return true;
}

function onBeforeDefaultGPSMotorViolation() { 
	global $page;
	global $appdata;
	$appdata["u_mvno"] = $page->getitemstring("u_mvno");
	$appdata["code"] = $page->getitemstring("code");
	
	



	return true;
}

function onAfterDefaultGPSMotorViolation() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
        
        
	$page->setitem("u_appdate",currentdate());
	$page->setitem("docstatus","D");
                        
	if ($appdata["u_mvno"]!="") {
		$page->setitem("u_mvno",$appdata["u_mvno"]);
		//if ($appdata["u_apptype"]=="RENEW") {
			//$page->setitem("u_apptype","RENEW");
		}
		$obju_BPLApps = new documentschema_br(null,$objConnection,"u_motorviolationapps");
		$obju_BPLMDs = new masterdataschema_br(null,$objConnection,"u_motorviolators");
		//if ($obju_BPLApps->getbykey($appdata["u_mvno"])) {
			if ($obju_BPLMDs->getbykey($appdata["u_mvno"])) {
				$page->setitem("u_licenseno",$obju_BPLMDs->getudfvalue("u_licenseno"));
				$page->setitem("u_firstname",$obju_BPLMDs->getudfvalue("u_firstname"));
				$page->setitem("u_lastname",$obju_BPLMDs->getudfvalue("u_lastname"));
				$page->setitem("u_middlename",$obju_BPLMDs->getudfvalue("u_middlename"));
				
				//$page->setitem("u_licenseno",$obju_BPLApps->getudfvalue("u_licenseno"));
				//$page->setitem("u_firstname",$obju_BPLApps->getudfvalue("u_firstname"));
				//$page->setitem("u_lastname",$obju_BPLApps->getudfvalue("u_lastname"));
				//$page->setitem("u_middlename",$obju_BPLApps->getudfvalue("u_middlename"));
			//}	
		}	
	
	
	
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
	return true;
}

function onAfterEditGPSMotorViolation() { 


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
	 global $page;
	global $appdata;
      //  $appdata["docno"] = $page->getitemstring("docno");
	$appdata["u_appdate"] = $page->getitemstring("u_appdate");
	return true;
}

function onAfterDeleteGPSMotorViolation() { 
	return true;
}

$page->businessobject->items->seteditable("u_firstname",false);
$page->businessobject->items->seteditable("u_lastname",false);
$page->businessobject->items->seteditable("u_middlename",false);
$page->businessobject->items->seteditable("u_totalamount",false);




//$objGrids[0]->columnwidth("code",50);
//$objGrids[0]->columnwidth("name",5);
$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[0]->columnvisibility("u_total",false);


$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_birthday","Calendar");
$page->businessobject->items->setcfl("u_licenseno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_ticketby","OpenCFLfs()");





?> 

