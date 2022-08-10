<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSPMRS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSPMRS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSPMRS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSPMRS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSPMRS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSPMRS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSPMRS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSPMRS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSPMRS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSPMRS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSPMRS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSPMRS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSPMRS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSPMRS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSPMRS");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSPMRS");

$appdata= array();

function onDrawGridColumnLabelGPSPMRS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
	}
}

function onCustomActionGPSPMRS($action) {
	return true;
}

function onBeforeDefaultGPSPMRS() { 
	global $page;
	global $appdata;
	$appdata["u_pmno"] = $page->getitemstring("u_pmno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");

	return true;
}

function onAfterDefaultGPSPMRS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
	
	$page->setitem("u_appdate",currentdate());
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_year",date('Y'));
	
	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.U_PMRRENTALPAYMODE, A.U_PMRRIGHTSPAYMODE,A.U_MUNICIPALITY,A.U_PROVINCE from U_LGUSETUP A");
	if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
		$page->setitem("u_paymode",$objRs_uLGUSetup->fields["U_PMRRENTALPAYMODE"]);
		$page->setitem("u_rightspaymode",$objRs_uLGUSetup->fields["U_PMRRIGHTSPAYMODE"]);
		$page->setitem("u_city",$objRs_uLGUSetup->fields["U_MUNICIPALITY"]);
		$page->setitem("u_province",$objRs_uLGUSetup->fields["U_PROVINCE"]);
	}	
	
	//$page->setitem("u_viewstatus","Encoding");
	if ($appdata["u_pmno"]!="") {
		$page->setitem("u_pmno",$appdata["u_pmno"]);
		if ($appdata["u_apptype"]=="RENEW") {
			$page->setitem("u_apptype","RENEW");
		}
		$obju_BPLApps = new documentschema_br(null,$objConnection,"u_pmrapps");
		$obju_BPLMDs = new masterdataschema_br(null,$objConnection,"u_pmrmds");
		//if ($obju_BPLMDs->getbykey($appdata["u_pmno"])) {
			if ($obju_BPLApps->getbykey($appdata["u_pmno"])) {
				$page->setitem("u_publicmarket",$obju_BPLApps->getudfvalue("u_publicmarket"));
				$page->setitem("u_stallno",$obju_BPLApps->getudfvalue("u_stallno"));
			}	
		//}	
	}
	
	$objGrids[0]->clear();
	$objGrids[1]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name from u_pmrreqs order by u_seqno asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_reqcode",$objRs->fields["code"]);
		$objGrids[1]->setitem(null,"u_reqdesc",$objRs->fields["name"]);
	}

	$total=0;	
	$objRs->queryopen("select code, name, u_amount from u_pmrfees order by name asc");
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

function onPrepareAddGPSPMRS(&$override) { 
	return true;
}

function onBeforeAddGPSPMRS() { 
	return true;
}

function onAfterAddGPSPMRS() { 
	return true;
}

function onPrepareEditGPSPMRS(&$override) { 
	return true;
}

function onBeforeEditGPSPMRS() { 
	return true;
}

function onAfterEditGPSPMRS() { 
	return true;
}

function onPrepareUpdateGPSPMRS(&$override) { 
	return true;
}

function onBeforeUpdateGPSPMRS() { 
	return true;
}

function onAfterUpdateGPSPMRS() { 
	return true;
}

function onPrepareDeleteGPSPMRS(&$override) { 
	return true;
}

function onBeforeDeleteGPSPMRS() { 
	return true;
}

function onAfterDeleteGPSPMRS() { 
	return true;
}

$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_decisiondate","Calendar");
$page->businessobject->items->setcfl("u_ctcdate","Calendar");
$page->businessobject->items->setcfl("u_bpno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_tradename","OpenCFLfs()");
$page->businessobject->items->setcfl("u_stallno","OpenCFLfs()");

$page->businessobject->items->seteditable("u_section",false);
$page->businessobject->items->seteditable("u_year",false);

/*
$page->businessobject->items->seteditable("u_lastname",false);
$page->businessobject->items->seteditable("u_firstname",false);
$page->businessobject->items->seteditable("u_middlename",false);
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
$objGrids[1]->columninput("u_check","type","checkbox");
$objGrids[1]->columninput("u_check","value",1);
$objGrids[1]->columninput("u_remarks","type","text");
$objGrids[1]->columnvisibility("u_reqcode",false);
$objGrids[1]->dataentry = false;

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

$objMaster->reportaction = "QS";


//$page->toolbar->setaction("new",false);

?> 

