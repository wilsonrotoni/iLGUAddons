<?php
 

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
	global $page;
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_phicno from u_hisphicsetup");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_pan",$objRs->fields["u_phicno"]);
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

$page->businessobject->items->setcfl("u_birthdate","Calendar");
$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_expiredate","Calendar");

$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_hci_fr_name","OpenCFLfs()");
$page->businessobject->items->setcfl("u_hci_to_name","OpenCFLfs()");
$page->businessobject->items->setcfl("u_doctorname","OpenCFLfs()");

$page->businessobject->items->seteditable("u_age",false);

$page->businessobject->items->seteditable("u_pan",false);


$page->businessobject->items->seteditable("u_hci_fr_name",true);
$page->businessobject->items->seteditable("u_hci_fr_street",true);
$page->businessobject->items->seteditable("u_hci_fr_city",true);
$page->businessobject->items->seteditable("u_hci_fr_province",true);
$page->businessobject->items->seteditable("u_hci_fr_zip",true);

$page->businessobject->items->seteditable("u_hci_to_name",true);
$page->businessobject->items->seteditable("u_hci_to_street",true);
$page->businessobject->items->seteditable("u_hci_to_city",true);
$page->businessobject->items->seteditable("u_hci_to_province",true);
$page->businessobject->items->seteditable("u_hci_to_zip",true);


$page->businessobject->items->seteditable("u_oth_a_cr",false);
$page->businessobject->items->seteditable("u_oth_b_cr",false);
$page->businessobject->items->seteditable("u_oth_c_cr",false);
$page->businessobject->items->seteditable("u_oth_d_cr",false);
$page->businessobject->items->seteditable("u_oth_e_cr",false);
$page->businessobject->items->seteditable("u_oth_f_cr",false);
$page->businessobject->items->seteditable("u_oth_g_cr",false);

$page->businessobject->items->seteditable("u_1stcrcode",false);
$page->businessobject->items->seteditable("u_1stcr",false);
$page->businessobject->items->seteditable("u_1stcrpf",false);
$page->businessobject->items->seteditable("u_1stcrhc",false);

$page->businessobject->items->seteditable("u_2ndcrcode",false);
$page->businessobject->items->seteditable("u_2ndcr",false);
$page->businessobject->items->seteditable("u_2ndcrpf",false);
$page->businessobject->items->seteditable("u_2ndcrhc",false);

$page->businessobject->items->seteditable("u_otheramt",false);
$page->businessobject->items->seteditable("u_otherpf",false);
$page->businessobject->items->seteditable("u_otherhc",false);
$page->businessobject->items->seteditable("u_totalamt",false);
$page->businessobject->items->seteditable("u_totalpf",false);
$page->businessobject->items->seteditable("u_totalhc",false);
$page->businessobject->items->seteditable("u_consumedamt",false);
$page->businessobject->items->seteditable("u_consumedpf",false);
$page->businessobject->items->seteditable("u_consumedhc",false);

$page->businessobject->items->seteditable("u_claimhc",false);
$page->businessobject->items->seteditable("u_balhc",false);

$page->businessobject->items->seteditable("u_actpf",false);
$page->businessobject->items->seteditable("u_bdiscpf",false);
$page->businessobject->items->seteditable("u_claimpf",false);
$page->businessobject->items->seteditable("u_balpf",false);

$page->businessobject->items->seteditable("u_xmtalno",false);
$page->businessobject->items->seteditable("u_xmtaldate",false);

$page->businessobject->items->setautocomplete("u_hci_to_name","OpenAutoCompleteu_hishealthcareinsbyname()");
$page->businessobject->items->setautocomplete("u_hci_fr_name","OpenAutoCompleteu_hishealthcareinsbyname()");

$objGrids[0]->columntitle("u_icdcode","ICD - 10");

$objGrids[0]->columnwidth("u_is1stcr",3);
$objGrids[0]->columnwidth("u_is2ndcr",3);
$objGrids[0]->columnwidth("u_icdgroup",30);
$objGrids[0]->columnwidth("u_icdcode",10);
$objGrids[0]->columnwidth("u_rvscode",10);
$objGrids[0]->columnwidth("u_rvsdesc",30);
$objGrids[0]->columnwidth("u_date",16);
$objGrids[0]->columnwidth("u_repeatproc",9);
$objGrids[0]->columnwidth("u_repeatproctype",30);

$objGrids[0]->columndataentry("u_is1stcr","type","checkbox");
$objGrids[0]->columndataentry("u_is1stcr","value",1);
$objGrids[0]->columndataentry("u_is2ndcr","type","checkbox");
$objGrids[0]->columndataentry("u_is2ndcr","value",1);
$objGrids[0]->columndataentry("u_repeatproc","type","checkbox");
$objGrids[0]->columndataentry("u_repeatproc","value",1);

$objGrids[0]->columndataentry("u_claimable","type","checkbox");
$objGrids[0]->columndataentry("u_claimable","value",1);

//$objGrids[0]->columninput("u_is1stcr","type","checkbox");
//$objGrids[0]->columninput("u_is1stcr","value",1);
//$objGrids[0]->columninput("u_is1stcr","disabled","true");
//$objGrids[0]->columninput("u_is2ndcr","type","checkbox");
//$objGrids[0]->columninput("u_is2ndcr","value",1);
//$objGrids[0]->columninput("u_is2ndcr","disabled","true");

$objGridClaimedBenefits = new grid("T120");
$objGridClaimedBenefits->addcolumn("u_claimdate");
$objGridClaimedBenefits->addcolumn("u_claimdays");
$objGridClaimedBenefits->addcolumn("u_is1stcr");
$objGridClaimedBenefits->addcolumn("u_is2ndcr");
$objGridClaimedBenefits->addcolumn("u_icdgroup");
$objGridClaimedBenefits->addcolumn("u_icdcode");
$objGridClaimedBenefits->addcolumn("u_rvsdesc");
$objGridClaimedBenefits->addcolumn("u_rvscode");
$objGridClaimedBenefits->addcolumn("u_date");
$objGridClaimedBenefits->addcolumn("u_laterality");
$objGridClaimedBenefits->addcolumn("u_repeatproc");
$objGridClaimedBenefits->addcolumn("u_repeatcycle");
$objGridClaimedBenefits->columntitle("u_claimdate","Date Claimed");
$objGridClaimedBenefits->columntitle("u_claimdays","Days");
$objGridClaimedBenefits->columntitle("u_is1stcr","1st");
$objGridClaimedBenefits->columntitle("u_is2ndcr","2nd");
$objGridClaimedBenefits->columntitle("u_icdgroup","Diagnosis");
$objGridClaimedBenefits->columntitle("u_icdcode","ICD");
$objGridClaimedBenefits->columntitle("u_rvscode","RVS");
$objGridClaimedBenefits->columntitle("u_rvsdesc","Related Procedures");
$objGridClaimedBenefits->columntitle("u_date","Date");
$objGridClaimedBenefits->columntitle("u_laterality","Laterality");
$objGridClaimedBenefits->columntitle("u_repeatproc","Repetitive");
$objGridClaimedBenefits->columntitle("u_repeatcycle","Cycle");
$objGridClaimedBenefits->columnwidth("u_claimdate",11);
$objGridClaimedBenefits->columnwidth("u_claimdays",4);
$objGridClaimedBenefits->columnwidth("u_is1stcr",3);
$objGridClaimedBenefits->columnwidth("u_is2ndcr",3);
$objGridClaimedBenefits->columnwidth("u_icdgroup",23);
$objGridClaimedBenefits->columnwidth("u_icdcode",7);
$objGridClaimedBenefits->columnwidth("u_rvscode",5);
$objGridClaimedBenefits->columnwidth("u_rvsdesc",25);
$objGridClaimedBenefits->columnwidth("u_laterality",8);
$objGridClaimedBenefits->columnwidth("u_date",9);
$objGridClaimedBenefits->columnwidth("u_repeatproc",9);
$objGridClaimedBenefits->columnwidth("u_repeatcycle",4);
$objGridClaimedBenefits->automanagecolumnwidth = false;
$objGridClaimedBenefits->width = 920;
$objGridClaimedBenefits->height = 200;
$objGridClaimedBenefits->dataentry = false;
	
 	  	  	 
$objGrids[0]->columncfl("u_icdgroup","OpenCFLfs()");
$objGrids[0]->columncfl("u_icdcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_rvscode","OpenCFLfs()");
$objGrids[0]->columncfl("u_rvsdesc","OpenCFLfs()");
$objGrids[0]->columncfl("u_date","Calendar");
$objGrids[0]->columntitle("u_is1stcr","#");
$objGrids[0]->columntitle("u_is2ndcr","#");
$objGrids[0]->columntitle("u_1stcr","1st Case");
$objGrids[0]->columntitle("u_2ndcr","2nd Case");
$objGrids[0]->columntitle("u_date","Date of Proc");
$objGrids[0]->columntitle("u_lastclaimno","No.");
$objGrids[0]->columntitle("u_lastclaimdays","Days");
$objGrids[0]->columntitle("u_interval","Interval");
$objGrids[0]->columntitle("u_lastclaimllatdate","L Lat. Date");
$objGrids[0]->columntitle("u_lastclaimllatno","No.");
$objGrids[0]->columntitle("u_lastclaimllatdays","Days");
$objGrids[0]->columntitle("u_lastclaimrlatdate","R Lat. Date");
$objGrids[0]->columntitle("u_lastclaimrlatno","No.");
$objGrids[0]->columntitle("u_lastclaimrlatdays","Days");
$objGrids[0]->columntitle("u_latinterval","Lat. Int.");
$objGrids[0]->columnwidth("u_1stcr",9);
$objGrids[0]->columnwidth("u_2ndcr",9);
$objGrids[0]->columnwidth("u_rvscode",8);
$objGrids[0]->columnwidth("u_laterality",8);
$objGrids[0]->columnwidth("u_date",11);
$objGrids[0]->columnwidth("u_lastclaimdate",15);
$objGrids[0]->columnwidth("u_lastclaimno",10);
$objGrids[0]->columnwidth("u_lastclaimdays",5);
$objGrids[0]->columnwidth("u_interval",7);
$objGrids[0]->columnwidth("u_lastclaimllatdate",10);
$objGrids[0]->columnwidth("u_lastclaimllatno",10);
$objGrids[0]->columnwidth("u_lastclaimllatdays",5);
$objGrids[0]->columnwidth("u_lastclaimrlatdate",10);
$objGrids[0]->columnwidth("u_lastclaimrlatno",10);
$objGrids[0]->columnwidth("u_lastclaimrlatdays",5);
$objGrids[0]->columnwidth("u_latinterval",7);
$objGrids[0]->columnwidth("u_claimable",8);
$objGrids[0]->columnalignment("u_is1stcr","center");
$objGrids[0]->columnalignment("u_is2ndcr","center");
$objGrids[0]->columnalignment("u_claimable","center");
$objGrids[0]->columnattributes("u_repeatproc","dsabled");
$objGrids[0]->columnattributes("u_repeatcycle","disabled");
$objGrids[0]->columnattributes("u_repeatproctype","disabled");
$objGrids[0]->columnattributes("u_repeatdates","disabled");
$objGrids[0]->columnattributes("u_interval","disabled");
$objGrids[0]->columnattributes("u_latinterval","disabled");
$objGrids[0]->columnattributes("u_claimable","disabled");
$objGrids[0]->columnvisibility("u_maxcycle",false);
//$objGrids[0]->columnattributes("u_is1stcr","disabled");
//$objGrids[0]->columnattributes("u_is2ndcr","disabled");

$objGrids[0]->automanagecolumnwidth = false;

//$objGrids[0]->columnvisibility("u_withlateral",true);

$objGrids[1]->columncfl("u_doctorid","OpenCFLfs()");
$objGrids[1]->columncfl("u_doctorname","OpenCFLfs()");
$objGrids[1]->columncfl("u_doctorservice","OpenCFLfs()");

$objGrids[1]->columnwidth("u_doctorname",30);
$objGrids[1]->columnwidth("u_doctorservice",20);
$objGrids[1]->columnwidth("u_doctorphicno",15);
$objGrids[1]->columnwidth("u_bdiscamt",12);
$objGrids[1]->columnwidth("u_actpf",10);
$objGrids[1]->columnwidth("u_bdiscpf",10);
$objGrids[1]->columnwidth("u_balpf",10);
$objGrids[1]->columnwidth("u_claimpf",10);
$objGrids[1]->columntitle("u_bdiscpf","Disc bef PHIC");
$objGrids[1]->columnvisibility("u_doctorid",false);
$objGrids[1]->columnattributes("u_balpf","disabled");
$objGrids[1]->automanagecolumnwidth = false;


$objGrids[2]->columntitle("u_case","Case");
$objGrids[2]->columnwidth("u_case",18);
$objGrids[2]->columnwidth("u_cycles",10);
$objGrids[2]->columnwidth("u_dates",18);
$objGrids[2]->height = 35;
$objGrids[2]->width = 420;
$objGrids[2]->automanagecolumnwidth = false;


$page->businessobject->resettabindex();
$page->businessobject->items->settabindex("u_lastname");
$page->businessobject->items->settabindex("u_firstname");
$page->businessobject->items->settabindex("u_middlename");
$page->businessobject->items->settabindex("u_extname");
$page->businessobject->items->settabindex("u_phicno");

$page->businessobject->items->settabindex("u_birthdate");
$page->businessobject->items->settabindex("u_gender");

$page->businessobject->items->settabindex("u_membertype");
$page->businessobject->items->settabindex("u_ismember");
$page->businessobject->items->settabindex("u_memberid");
$page->businessobject->items->settabindex("u_membername");

$page->businessobject->items->settabindex("u_informantname");
$page->businessobject->items->settabindex("u_informantrelationship");
$page->businessobject->items->settabindex("u_informantreasonsign");


$addoptions = false;
?> 

