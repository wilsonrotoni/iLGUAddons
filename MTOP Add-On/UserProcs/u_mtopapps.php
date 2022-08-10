<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSMTOP");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSMTOP");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSMTOP");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSMTOP");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSMTOP");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSMTOP");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSMTOP");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSMTOP");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSMTOP");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSMTOP");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSMTOP");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSMTOP");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSMTOP");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSMTOP");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSMTOP");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSMTOP");

$appdata= array();

function onDrawGridColumnLabelGPSMTOP($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
	}
}

function onCustomActionGPSMTOP($action) {
	return true;
}

function onBeforeDefaultGPSMTOP() { 
	global $page;
	global $appdata;
	$appdata["u_fcno"] = $page->getitemstring("u_fcno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	return true;
}

function onAfterDefaultGPSMTOP() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
	
	$page->setitem("u_appdate",currentdate());
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_year",date('Y'));
	$page->setitem("u_apptype","NEW");
	//$page->setitem("u_franchiseno",str_replace($page->getitemstring("u_year")."-","",$page->getitemstring("docno")));
	//$page->setitem("u_viewstatus","Encoding");
	if ($appdata["u_fcno"]!="") {
		$page->setitem("u_franchiseno",$appdata["u_fcno"]);
		//if ($appdata["u_apptype"]=="RENEW") {
			$page->setitem("u_apptype",$appdata["u_apptype"]);
		//}
		$obju_BPLApps = new documentschema_br(null,$objConnection,"u_mtopapps");
		$obju_BPLMDs = new masterdataschema_br(null,$objConnection,"u_mtopmds");
		
		if ($obju_BPLMDs->getbykey($appdata["u_fcno"])) {
			if ($obju_BPLApps->getbykey($obju_BPLMDs->getudfvalue("u_apprefno"))) {
				$page->setitem("u_special",$obju_BPLApps->getudfvalue("u_special"));
				$page->setitem("u_toda",$obju_BPLApps->getudfvalue("u_toda"));
				$page->setitem("u_brand",$obju_BPLApps->getudfvalue("u_brand"));
				$page->setitem("u_plateno",$obju_BPLApps->getudfvalue("u_plateno"));
				$page->setitem("u_chassisno",$obju_BPLApps->getudfvalue("u_chassisno"));
				$page->setitem("u_engineno",$obju_BPLApps->getudfvalue("u_engineno"));
				$page->setitem("u_lastname",$obju_BPLApps->getudfvalue("u_lastname"));
				$page->setitem("u_firstname",$obju_BPLApps->getudfvalue("u_firstname"));
				$page->setitem("u_middlename",$obju_BPLApps->getudfvalue("u_middlename"));
				$page->setitem("u_street",$obju_BPLApps->getudfvalue("u_street"));
				$page->setitem("u_brgy",$obju_BPLApps->getudfvalue("u_brgy"));
				$page->setitem("u_city",$obju_BPLApps->getudfvalue("u_city"));
				$page->setitem("u_province",$obju_BPLApps->getudfvalue("u_province"));
				$page->setitem("u_telno",$obju_BPLApps->getudfvalue("u_telno"));
				$page->setitem("u_email",$obju_BPLApps->getudfvalue("u_email"));
                                //$page->setitem("u_year",$obju_BPLApps->getudfvalue("u_year"));
                                echo substr($obju_BPLApps->getudfvalue("u_expdate"),0,4);
				if ($appdata["u_apptype"]=="RENEW") {
					$page->setitem("u_year",intval(substr($obju_BPLApps->getudfvalue("u_expdate"),0,4))+1);
				} else {
					$page->setitem("u_year",$obju_BPLApps->getudfvalue("u_year"));
				}	
				$page->setitem("u_prevexpdate",formatDateToHttp($obju_BPLApps->getudfvalue("u_expdate")));
			}	
		}	
	}
	
	$objGrids[0]->clear();
	$objGrids[1]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name from u_mtopreqs order by u_seqno asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_reqcode",$objRs->fields["code"]);
		$objGrids[1]->setitem(null,"u_reqdesc",$objRs->fields["name"]);
                $objGrids[1]->setitem(null,"u_amount",formatNumericAmount(0));
	}

	$total=0;	
	$objRs->queryopen("select code, name, u_amount from u_mtopfees order by u_seqno asc, name asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
		$objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
		$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
		$total+=$objRs->fields["u_amount"];
	}
	$page->setitem("u_asstotal",formatNumericAmount($total));
	
	return true;
}

function onPrepareAddGPSMTOP(&$override) { 
	return true;
}

function onBeforeAddGPSMTOP() { 
	return true;
}

function onAfterAddGPSMTOP() { 
	return true;
}

function onPrepareEditGPSMTOP(&$override) { 
	return true;
}

function onBeforeEditGPSMTOP() { 
	return true;
}

function onAfterEditGPSMTOP() { 
	return true;
}

function onPrepareUpdateGPSMTOP(&$override) { 
	return true;
}

function onBeforeUpdateGPSMTOP() { 
	return true;
}

function onAfterUpdateGPSMTOP() { 
	return true;
}

function onPrepareDeleteGPSMTOP(&$override) { 
	return true;
}

function onBeforeDeleteGPSMTOP() { 
	return true;
}

function onAfterDeleteGPSMTOP() { 
	return true;
}

$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_decisiondate","Calendar");
$page->businessobject->items->setcfl("u_franchiseno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_toda","OpenCFLfs()");
$page->businessobject->items->setcfl("u_brgy","OpenCFLfs()");
$page->businessobject->items->setcfl("u_dbrgy","OpenCFLfs()");

$page->businessobject->items->seteditable("u_franchiseno",false);
$page->businessobject->items->seteditable("u_year",false);
/*
$page->businessobject->items->seteditable("u_lastname",false);
$page->businessobject->items->seteditable("u_firstname",false);
$page->businessobject->items->seteditable("u_middlename",false);
$page->businessobject->items->seteditable("u_gender",false);
$page->businessobject->items->seteditable("u_street",false);
$page->businessobject->items->seteditable("u_brgy",false);
$page->businessobject->items->seteditable("u_city",false);
$page->businessobject->items->seteditable("u_province",false);
$page->businessobject->items->seteditable("u_telno",false);
$page->businessobject->items->seteditable("u_email",false);
*/

$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");


$objGrids[1]->columnwidth("u_check",2);
$objGrids[1]->columnwidth("u_reqdesc",40);
$objGrids[1]->columnwidth("u_remarks",25);
$objGrids[1]->columnwidth("u_office",25);
$objGrids[1]->columnwidth("u_payrefno",10);
$objGrids[1]->columninput("u_check","type","checkbox");
$objGrids[1]->columninput("u_check","value",1);
$objGrids[1]->columninput("u_payrefno","type","text");
$objGrids[1]->columninput("u_remarks","type","text");
$objGrids[1]->columninput("u_amount","type","text");
$objGrids[1]->columninput("u_issdate","type","text");
$objGrids[1]->columnvisibility("u_reqcode",false);
$objGrids[1]->dataentry = false;

$objGrids[1]->automanagecolumnwidth = true;

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("new",false);

?> 

