<?php
 

include_once("./sls/enumyear.php");
//$page->businessobject->events->add->customAction("onCustomActionGPSBPLS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSBPLS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSBPLS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSBPLS");
//$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSBPLS");

//function onDrawGridColumnLabelGPSBPLS($tablename,$column,$row,&$label) {
//	global $objRs;
//	global $trxrunbal;
//	switch ($tablename) {
//		case "T1":
//                    if ($column=="u_businessline") {
//                            $objRs->queryopen("select name from u_bpllines where code='".$label."'");
//                            if ($objRs->queryfetchrow()) $label = $objRs->fields[0];
//                    }	
//	}
//}

function onCustomActionGPSBPLS($action) {
	return true;
}

function onBeforeDefaultGPSBPLS() { 
	return true;
}

function onAfterDefaultGPSBPLS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	$page->setitem("u_date",currentdate());
        
//        $objRs = new recordset(null,$objConnection);
////	$objRs->queryopen("select A.LINEID, A.BRANCH, A.U_SRCDOC, A.U_SRCNAME, A.U_SRCLINE, A.U_SRCWHS, A.U_ACQUIDATE, A.U_ITEMCODE, A.U_ITEMDESC, A.U_CONTRAGLACCTNO, A.U_AMOUNT, A.U_COST, A.U_PROFITCENTER, A.U_PROJCODE, A.U_SERIALNO, A.U_MFRSERIALNO, A.U_PROPERTY1, A.U_PROPERTY2, A.U_PROPERTY3, A.U_PROPERTY4, A.U_PROPERTY5, A.U_ASSETTYPE, A.U_ASSETCODE, B.U_ISFIXEDASSET, B.U_FACLASS from u_faxmtal A LEFT JOIN ITEMS B ON B.ITEMCODE=A.U_ITEMCODE where A.U_STATUS='' $branchExp");
//	$objRs->queryopen("select * from u_bplledger where docno = '00330456'");
//	while ($objRs->queryfetchrow("NAME")) {
//            
//                    $objGrids[0]->addrow();
//                    $objGrids[0]->setitem(null,"u_ordate",formatDate($objRs->fields["u_ordate"]));
//                    $objGrids[0]->setitem(null,"u_orno",$objRs->fields["u_orno"]);
//                    $objGrids[0]->setitem(null,"u_year",$objRs->fields["u_payyear"]);
//                    $objGrids[0]->setitem(null,"u_feeid",$objRs->fields["u_feeid"]);
//                    $objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
//                    $objGrids[0]->setitem(null,"u_businessline",$objRs->fields["u_businesslineid"]);
//                    $objGrids[0]->setitem(null,"u_taxbase",formatNumericAmount($objRs->fields["u_taxbase"]));
//                    $objGrids[0]->setitem(null,"u_amountpaid",formatNumericAmount($objRs->fields["u_amountpaid"]));
//                    $objGrids[0]->setitem(null,"u_paymode",$objRs->fields["u_paymode"]);
//                    $objGrids[0]->setitem(null,"u_payqtr",$objRs->fields["u_payqtr"]);
//                    $objGrids[0]->setitem(null,"u_cashier",$objRs->fields["createdby"]);
//	}
	return true;
}

function onPrepareAddGPSBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSBPLS() { 
	return true;
}

function onAfterAddGPSBPLS() { 
	return true;
}

function onPrepareEditGPSBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSBPLS() { 
	return true;
}

function onAfterEditGPSBPLS() { 
	return true;
}

function onPrepareUpdateGPSBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSBPLS() { 
	return true;
}

function onAfterUpdateGPSBPLS() { 
	return true;
}

function onPrepareDeleteGPSBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSBPLS() { 
	return true;
}

function onAfterDeleteGPSBPLS() { 
	return true;
}

$page->businessobject->items->setcfl("u_orno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_arpno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_date","Calendar");


$page->businessobject->items->seteditable("u_authorizedby",false);
$page->businessobject->items->seteditable("u_date",false);
$page->businessobject->items->seteditable("u_accountno",false);
$page->businessobject->items->seteditable("u_businessname",false);
$page->businessobject->items->seteditable("u_apprefno",false);
$page->businessobject->items->seteditable("u_baddress",false);
$page->businessobject->items->seteditable("u_totalpaid",false);

$objGrids[0]->dataentry = true;
$objGrids[0]->columntitle("u_businesslinecode","Code");
$objGrids[0]->columntitle("u_feeid","Fee ID");
$objGrids[0]->columntitle("u_feedesc","Fee Description");
$objGrids[0]->columntitle("u_taxbase","Tax Base");
$objGrids[0]->columntitle("u_year","For Year");
$objGrids[0]->columnwidth("u_ordate",12);
$objGrids[0]->columnwidth("u_orno",12);
$objGrids[0]->columnwidth("u_feeid",12);
$objGrids[0]->columnwidth("u_feedesc",30);
$objGrids[0]->columnwidth("u_taxbase",15);
$objGrids[0]->columnwidth("u_amountpaid",15);
$objGrids[0]->columnwidth("u_paymode",15);
$objGrids[0]->columnwidth("u_payqtr",10);
$objGrids[0]->columnwidth("u_businessline",25);

$objGrids[0]->columninput("u_year","type","text");
$objGrids[0]->columninput("u_taxbase","type","text");
$objGrids[0]->columninput("u_amountpaid","type","text");
$objGrids[0]->columninput("u_paymode","type","text");
$objGrids[0]->columninput("u_payqtr","type","text");
//$objGrids[0]->columninput("u_businessline","type","text");

//$objGrids[0]->columninput("u_paymode","type","select");
//$objGrids[0]->columninput("u_paymode","options",array("loadudfenums","u_bpladdledgerlines:paymode","[Select]"));

//$objGrids[0]->columninput("u_businessline","type","select");
//$objGrids[0]->columninput("u_businessline","options",array("loadudflinktable","u_bpllines:code:name","[Select]"));

$objGrids[0]->columnattributes("u_cashier","disabled");
$objGrids[0]->columnattributes("u_ordate","disabled");
$objGrids[0]->columnattributes("u_feedesc","disabled");

$objGrids[0]->columncfl("u_orno","OpenCFLfs()");
$objGrids[0]->columncfl("u_feeid","OpenCFLfs()");
//$objGrids[0]->columncfl("u_businessline","OpenCFLfs()");

$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");

//$objGrids[0]->columninput("u_year","type","select");
//$objGrids[0]->columninput("u_year","options","loadenumyear","","[Select]");

$objGrids[0]->columnvisibility("u_businesslinecode",false);
$objGrids[0]->columnvisibility("u_rownumber",false);

$objGrids[0]->columninput("u_auditedgross","type","text");
$objGrids[0]->columninput("u_auditedbtaxamount","type","text");



$objGrids[0]->automanagecolumnwidth = false;


$addoptions = false;
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
?> 

