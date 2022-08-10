<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSENGINEERING");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSENGINEERING");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSENGINEERING");

$page->businessobject->events->add->prepareAdd("onPrepareAddGPSENGINEERING");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSENGINEERING");
$page->businessobject->events->add->afterAdd("onAfterAddGPSENGINEERING");

$page->businessobject->events->add->prepareEdit("onPrepareEditGPSENGINEERING");
$page->businessobject->events->add->beforeEdit("onBeforeEditGPSENGINEERING");
$page->businessobject->events->add->afterEdit("onAfterEditGPSENGINEERING");

$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSENGINEERING");
$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSENGINEERING");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSENGINEERING");

$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSENGINEERING");
$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSENGINEERING");
$page->businessobject->events->add->afterDelete("onAfterDeleteGPSENGINEERING");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSENGINEERING");

$appdata= array();

function onDrawGridColumnLabelGPSENGINEERING($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
	}
}


function onCustomActionGPSENGINEERING($action) {
	return true;
}

function onBeforeDefaultGPSENGINEERING() { 
	global $page;
	global $appdata;
	return true;
}

function onAfterDefaultGPSENGINEERING() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
	
	if ($appdata["u_bpno"]!="") {
		$page->setitem("u_bpno",$appdata["u_bpno"]);
		//if ($appdata["u_apptype"]=="RENEW") {
			//$page->setitem("u_apptype","RENEW");
		}
		$obju_BPLApps = new documentschema_br(null,$objConnection,"u_buildingpermitapps");
		$obju_BPLMDs = new masterdataschema_br(null,$objConnection,"u_buildingpermitmds");
		//if ($obju_BPLApps->getbykey($appdata["u_mvno"])) {
			if ($obju_BPLMDs->getbykey($appdata["u_mvno"])) {
				$page->setitem("u_appno",$obju_BPLMDs->getudfvalue("u_appno"));
				$page->setitem("u_firstname",$obju_BPLMDs->getudfvalue("u_firstname"));
				$page->setitem("u_lastname",$obju_BPLMDs->getudfvalue("u_lastname"));
				$page->setitem("u_middlename",$obju_BPLMDs->getudfvalue("u_middlename"));
				
				//$page->setitem("u_licenseno",$obju_BPLApps->getudfvalue("u_licenseno"));
				//$page->setitem("u_firstname",$obju_BPLApps->getudfvalue("u_firstname"));
				//$page->setitem("u_lastname",$obju_BPLApps->getudfvalue("u_lastname"));
				//$page->setitem("u_middlename",$obju_BPLApps->getudfvalue("u_middlename"));
			//}	
		}	
	
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_apptype","NEW");
	$page->setitem("u_appdate",currentdate());
	$page->setitem("u_dateissued1",currentdate());
	$page->setitem("u_dateissued2",currentdate());
	$page->setitem("u_archidateissued1",currentdate());
	$page->setitem("u_archidateissued2",currentdate());
	


	$objGrids[0]->clear();
	//$objGrids[1]->clear();
	//$objGrids[2]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT CODE,NAME FROM U_BUILDINGSCOPEOFWORK ORDER BY U_SEQNO ");
	//var_dump($objrs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_scopeofwork",$objRs->fields["CODE"]);
		$objGrids[0]->setitem(null,"u_scopeofwork",$objRs->fields["NAME"]);
        //$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
	}
	
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT CODE,NAME FROM U_BUILDINGREQUIREMENTS ORDER BY U_SEQNO ");
	//var_dump($objrs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[3]->addrow();
		$objGrids[3]->setitem(null,"u_reqcode",$objRs->fields["CODE"]);
		$objGrids[3]->setitem(null,"u_reqdesc",$objRs->fields["NAME"]);
		//$objGrids[3]->setitem(null,"u_reqdesc",$objRs->fields["NAME"]);
        //$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
	}
	
	
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT CODE,NAME FROM U_BUILDINGREQUIREDPLANS ORDER BY U_SEQNO ");
	//var_dump($objrs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[4]->addrow();
		$objGrids[4]->setitem(null,"u_reqplancode",$objRs->fields["CODE"]);
		$objGrids[4]->setitem(null,"u_reqplandesc",$objRs->fields["NAME"]);
		//$objGrids[3]->setitem(null,"u_reqdesc",$objRs->fields["NAME"]);
        //$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
	}
	
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT CODE,NAME,U_AMOUNT FROM U_BUILDINGPERMITFEES ");
	//var_dump($objrs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[7]->addrow();
		$objGrids[7]->setitem(null,"u_feecode",$objRs->fields["CODE"]);
		$objGrids[7]->setitem(null,"u_feedesc",$objRs->fields["NAME"]);
		$objGrids[7]->setitem(null,"u_amount",$objRs->fields["U_AMOUNT"]);
		//$objGrids[3]->setitem(null,"u_reqdesc",$objRs->fields["NAME"]);
        //$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
	$total+=$objRs->fields["U_AMOUNT"];
	}
	$page->setitem("u_buildingfeestotal",formatNumericAmount($total));
	

	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT A.CODE,A.NAME,B.U_METERS,B.U_FEE,B.U_AMOUNT FROM U_BUILDINGLINEGRADE A INNER JOIN U_BUILDINGLINEGRADEFEES B ON A.CODE=B.CODE ");
	//var_dump($objrs->sqls);
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[11]->addrow();
		$objGrids[11]->setitem(null,"u_meters",$objRs->fields["U_METERS"]);
	//	$objGrids[11]->setitem(null,"u_amount",$objRs->fields["U_AMOUNT"]);
		$objGrids[11]->setitem(null,"u_totallinegrade",$objRs->fields["U_AMOUNT"]);
		//$objGrids[3]->setitem(null,"u_reqdesc",$objRs->fields["NAME"]);
        //$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
	$total+=$objRs->fields["U_AMOUNT"];
	}
	$page->setitem("u_buildingfeestotal",formatNumericAmount($total));
	
	
	
	
	
	
	
	
	
	return true;
}

function onPrepareAddGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeAddGPSENGINEERING() { 
	return true;
}

function onAfterAddGPSENGINEERING() { 
	return true;
}

function onPrepareEditGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeEditGPSENGINEERING() { 
	
	return true;
}

function onAfterEditGPSENGINEERING() { 
	
	
	return true;
}

function onPrepareUpdateGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeUpdateGPSENGINEERING() { 
	return true;
}

function onAfterUpdateGPSENGINEERING() { 
	return true;
}

function onPrepareDeleteGPSENGINEERING(&$override) { 
	return true;
}

function onBeforeDeleteGPSENGINEERING() { 
	return true;
}

function onAfterDeleteGPSENGINEERING() { 
	return true;
}


$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_dateissued1","Calendar");
$page->businessobject->items->setcfl("u_dateissued2","Calendar");
$page->businessobject->items->setcfl("u_archidateissued1","Calendar");
$page->businessobject->items->setcfl("u_archidateissued2","Calendar");
$page->businessobject->items->setcfl("u_proposeddateofconstruction","Calendar");
$page->businessobject->items->setcfl("u_expecteddatecompletion","Calendar");

$page->businessobject->items->seteditable("u_appno",false);
$page->businessobject->items->setcfl("u_mechappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_elecappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_zoningappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_plumbingappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_fencingappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_electronicsappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_archiappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_civilstrucappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_sanitaryappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_demolitionappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_excavationappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_occupancyappno","OpenCFLfs()");


$page->businessobject->items->setcfl("u_civilengr","OpenCFLfs()");
$page->businessobject->items->setcfl("u_electricalengr","OpenCFLfs()");
$page->businessobject->items->setcfl("u_sanitarymasterplumber","OpenCFLfs()");
$page->businessobject->items->setcfl("u_mechanicalengr","OpenCFLfs()");
$page->businessobject->items->setcfl("u_electronicsengr","OpenCFLfs()");

$page->businessobject->items->setcfl("u_buildingpermitdateissued","Calendar");
$page->businessobject->items->setcfl("u_fsecnodateissued","Calendar");


$objGrids[0]->columnwidth("u_check",2);
$objGrids[0]->columnwidth("u_scopeofwork",25);
$objGrids[0]->columninput("u_check","type","checkbox");
$objGrids[0]->columninput("u_check","value",1);
$objGrids[0]->columnwidth("u_remarks",30);
$objGrids[0]->columninput("u_remarks","type","text");
$objGrids[0]->dataentry = false;
//$objGrids[4]->automanagecolumnwidth = true;


$objGrids[1]->columncfl("u_charactercode","OpenCFLfs()");
$objGrids[1]->columncfl("u_characterdesc","OpenCFLfs()");
$objGrids[1]->columnwidth("u_charactercode",20);
$objGrids[1]->columnwidth("u_characterdesc",40);
$objGrids[1]->columncfl("u_characterkind","OpenCFLfs()");
$objGrids[1]->columnwidth("u_characterkind",40);

//$objGrids[0]->columnvisibility("u_scopeofwork",true);
//$objGrids[1]->dataentry = false;

$objGrids[1]->automanagecolumnwidth = true;


$objGrids[3]->columnwidth("u_check",2);
$objGrids[3]->columnwidth("u_reqcode",15);
$objGrids[3]->columnwidth("u_reqdesc",40);
$objGrids[3]->columninput("u_reqfile","type","text");
$objGrids[3]->columninput("u_check","type","checkbox");
$objGrids[3]->columninput("u_check","value",1);
//$objGrids[0]->columnvisibility("u_scopeofwork",true);
$objGrids[3]->dataentry = false;
$objGrids[3]->automanagecolumnwidth = true;


$objGrids[4]->columnwidth("u_check",2);
$objGrids[4]->columnwidth("u_reqplancode",15);
$objGrids[4]->columnwidth("u_reqplandesc",40);
$objGrids[4]->columninput("u_reqplanfile","type","text");
$objGrids[4]->columninput("u_check","type","checkbox");
$objGrids[4]->columninput("u_check","value",1);
//$objGrids[0]->columnvisibility("u_scopeofwork",true);
$objGrids[4]->dataentry = false;
$objGrids[4]->automanagecolumnwidth = true;



$objGrids[5]->columnwidth("u_occupancyclassified",20);
$objGrids[5]->columnwidth("u_numberofunits",10);
$objGrids[5]->columnwidth("u_numberofstorey",10);
$objGrids[5]->columnwidth("u_lotarea",15);
$objGrids[5]->columnwidth("u_totalfloorarea",10);
$objGrids[5]->columnwidth("u_totalestcost",15);
$objGrids[5]->columnwidth("u_height",15);

$objGrids[6]->columncfl("u_divisioncode","OpenCFLfs()");
$objGrids[6]->columncfl("u_divisionname","OpenCFLfs()");
$objGrids[6]->columncfl("u_area","OpenCFLfs()");
$objGrids[6]->columnwidth("u_divisioncode",10);
$objGrids[6]->columnwidth("u_divisionname",15);
$objGrids[6]->columnwidth("u_area",20);
$objGrids[6]->columnwidth("u_fee",10);

$objGrids[6]->automanagecolumnwidth = true;

$objGrids[7]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[7]->columncfl("u_feedesc","OpenCFLfs()");

//$objGrids[8]->columncfl("u_engrcode","OpenCFLfs()");
//$objGrids[8]->columncfl("u_engrdesc","OpenCFLfs()");
//$objGrids[8]->columncfl("u_name","OpenCFLfs()");
$objGrids[8]->columnvisibility("u_engrcode",false);
//$objGrids[8]->columnwidth("u_engrcode",15);
$objGrids[8]->columnwidth("u_engrdesc",30);
$objGrids[8]->columnwidth("u_name",50);
$objGrids[8]->columnwidth("u_prcno",15);
$objGrids[8]->columnwidth("u_ptrno",15);
$objGrids[8]->columnwidth("u_prcvalidity",15);
$objGrids[8]->columncfl("u_prcvalidity","Calendar");
$objGrids[8]->columnwidth("u_dateissued",20);
$objGrids[8]->columncfl("u_dateissued","Calendar");
$objGrids[8]->columnwidth("u_issuedat",20);
$objGrids[8]->columnwidth("u_tinno",15);
$objGrids[8]->automanagecolumnwidth = true;

//$objGrids[9]->dataentry = false;
$objGrids[9]->automanagecolumnwidth=true;
$objGrids[9]->width = 620;
$objGrids[9]->columnwidth("u_amount",10);
$objGrids[9]->columnwidth("u_feecode",10);
$objGrids[9]->columnwidth("u_feedesc",20);

$objGrids[10]->columncfl("u_name","OpenCFLfs()");
$objGrids[10]->columncfl("u_code","OpenCFLfs()");
$objGrids[10]->columncfl("u_area","OpenCFLfs()");
$objGrids[10]->columnwidth("u_name",20);
$objGrids[10]->columnwidth("u_area",20);
$objGrids[10]->columnwidth("u_code",10);
$objGrids[10]->columnwidth("u_fee",10);
$objGrids[10]->columnwidth("u_unit",10);
$objGrids[10]->columnwidth("u_amount",15);

//$objGrids[11]->columnwidth("u_fee",15);
$objGrids[11]->columnwidth("u_meters",15);;
$objGrids[11]->columnwidth("u_totalgrade",15);
//$objGrids[11]->columncfl("u_meters","2.4");
	

$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = true;


$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("new",false);

?> 

