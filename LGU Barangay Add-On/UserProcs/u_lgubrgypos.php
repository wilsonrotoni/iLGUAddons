<?php
include_once("./utils/branches.php"); 
include_once("./utils/companies.php"); 
include_once("./classes/masterdataschema_br.php"); 
include_once("./sls/housebankaccounts.php"); 
include_once("./sls/banks.php"); 

//$page->businessobject->events->add->customAction("onCustomActionGPSPOS");

$postdata = array();

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSPOS");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSPOS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSPOS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSPOS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSPOS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSPOS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSPOS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSPOS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSPOS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSPOS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSPOS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSPOS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSPOS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSPOS");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSPOS");

function onDrawGridColumnLabelGPSPOS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T3":
			if ($column=="u_creditcard") {
				$objRs->queryopen("select creditcardname from creditcards where pospaycode='".$label."'");
				if ($objRs->queryfetchrow()) $label = $objRs->fields[0];
			}	
	}
}

function onCustomActionGPSPOS($action) {
	return true;
}

function onBeforeDefaultGPSPOS() { 
	global $page;
	$page->privatedata["billtopay"] = $page->getitemstring("u_billtopay");
	return true;
}

function onAfterDefaultGPSPOS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	
	$autoseries = 1;
	$autopost = 0;
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	$objRs->queryopen("select CODE, U_AUTOSERIES, U_AUTOPOST from U_LGUPOSTERMINALS where BRANCH='".$_SESSION["branch"]."' AND NAME='".$_SERVER['REMOTE_ADDR']."'");
	if ($objRs->queryfetchrow("NAME")) {
		$terminalid = $objRs->fields["CODE"];
		$autoseries = $objRs->fields["U_AUTOSERIES"];
		$autopost = $objRs->fields["U_AUTOPOST"];
		$objRs2->queryopen("select CODE,U_TERMINALID,U_USERID from U_LGUPOSREGISTERS where BRANCH='".$_SESSION["branch"]."' AND  U_TERMINALID='$terminalid' and U_STATUS='O'");
		if ($objRs2->queryfetchrow("NAME")) {
			if ($objRs2->fields["U_USERID"]==$_SESSION["userid"]) {
				$page->setitem("u_terminalid",$objRs2->fields["U_TERMINALID"]);
			} else {
				header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently opened by user ['.$objRs2->fields["U_USERID"].'].'); 
				return;
			}	
		} else {
			header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently closed.'); 
			return;
		}	
	} else {
		header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=No Terminal maintained for IP Address ['.$_SERVER['REMOTE_ADDR'].'].'); 
		return;
	}
	
	
	$branchdata = getcurrentbranchdata("POSCUSTNO,COUNTRY");
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select CUSTNO,CUSTNAME from CUSTOMERS where COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and CUSTNO='".$branchdata["POSCUSTNO"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_custno",$objRs->fields["CUSTNO"]);
		$page->setitem("u_custname",$objRs->fields["CUSTNAME"]);
	}	
	$page->setitem("u_date",currentdate());
	$page->setitem("paymenttype","Cash");
	$page->setitem("totalamountdisplay",formatNumericAmount(0.00));
	$page->setitem("totalamountpaymentdisplay",formatNumericAmount(0.00));
	$page->setitem("u_autopost",$autopost);
	$page->setitem("u_autoqueue",0);
	if ($autoseries==1) {
		$obju_POSTerminals = new masterdataschema_br(null,$objConnection,"u_lguposterminals");
		if ($obju_POSTerminals->getbykey($page->getitemstring("u_terminalid"))) {
			$numlen = $obju_POSTerminals->getudfvalue("u_numlen");
			$prefix = $obju_POSTerminals->getudfvalue("u_prefix");
			$suffix = $obju_POSTerminals->getudfvalue("u_suffix");
			$nextno = $obju_POSTerminals->getudfvalue("u_nextno");	
			
			$prefix = str_replace("{POS}", $page->getitemstring("u_terminalid"), $prefix);
			$suffix = str_replace("{POS}", $page->getitemstring("u_terminalid"), $suffix);
			$prefix = str_replace("[m]", str_pad( date('m'), 2, "0", STR_PAD_LEFT), $prefix);
			$suffix = str_replace("[m]", str_pad( date('m'), 2, "0", STR_PAD_LEFT), $suffix);
			$prefix = str_replace("[Y]", date('Y'), $prefix);
			$suffix = str_replace("[Y]", date('Y'), $suffix);
			$prefix = str_replace("[y]", str_pad(substr(date('Y'),2), 2, "0", STR_PAD_LEFT), $prefix);
			$suffix = str_replace("[y]", str_pad(substr(date('Y'),2), 2, "0", STR_PAD_LEFT), $suffix);
							
			$docno = $prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT) . $suffix;
			$page->setitem("docno",$docno);
		}
	} else {
		$page->setitem("docseries",-1);
		$page->setitem("docno","");
		$page->setitem("u_date","");
	}	
	$page->setitem("u_tfcountry",$branchdata["COUNTRY"]);
	
	$objGrids[4]->clear();
	// and U_CUSTNO='".$branchdata["POSCUSTNO"]."'
	$objRs->queryopen("select DOCNO, U_CUSTNAME, U_PAIDAMOUNT, U_CREDITEDAMOUNT from U_LGUPOS where COMPANY='".$_SESSION["company"]."' and BRANCH ='".$_SESSION["branch"]."' AND U_PAIDAMOUNT>U_CREDITEDAMOUNT AND U_PARTIALPAY=1 AND U_STATUS NOT IN ('D','CN')");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[4]->addrow();
		$objGrids[4]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
		$objGrids[4]->setitem(null,"u_custname",$objRs->fields["U_CUSTNAME"]);
		//$objGrids[4]->setitem(null,"u_remarks",$objRs->fields["REMARKS"]);
		$objGrids[4]->setitem(null,"u_totalamount",formatNumericAmount($objRs->fields["U_PAIDAMOUNT"]));
		$objGrids[4]->setitem(null,"u_balanceamount",formatNumericAmount($objRs->fields["U_PAIDAMOUNT"]-$objRs->fields["U_CREDITEDAMOUNT"]));
		$objGrids[4]->setitem(null,"u_amount",formatNumericAmount(0));
	}
	/*$objRs->queryopen("select DOCNO, BPNAME, REMARKS, PAIDAMOUNT,CREDITEDAMOUNT from COLLECTIONS where COMPANY='".$_SESSION["company"]."' and BRANCHCODE='".$_SESSION["branch"]."' and CUSTNO='".$branchdata["POSCUSTNO"]."' AND PAIDAMOUNT>CREDITEDAMOUNT AND COLLFOR='RS'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[4]->addrow();
		$objGrids[4]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
		$objGrids[4]->setitem(null,"u_custname",$objRs->fields["BPNAME"]);
		$objGrids[4]->setitem(null,"u_remarks",$objRs->fields["REMARKS"]);
		$objGrids[4]->setitem(null,"u_totalamount",formatNumericAmount($objRs->fields["PAIDAMOUNT"]));
		$objGrids[4]->setitem(null,"u_balanceamount",formatNumericAmount($objRs->fields["PAIDAMOUNT"]-$objRs->fields["CREDITEDAMOUNT"]));
		$objGrids[4]->setitem(null,"u_amount",formatNumericAmount(0));
	}*/
	
	
	return true;
}

function onPrepareAddGPSPOS(&$override) { 
	return true;
}

function onBeforeAddGPSPOS() { 
	return true;
}

function onAfterAddGPSPOS() { 
	return true;
}

function onPrepareEditGPSPOS(&$override) { 
	return true;
}

function onBeforeEditGPSPOS() { 
	return true;
}

function onAfterEditGPSPOS() { 
	global $objGrids;
	$objGrids[4]->clear();
	return true;
}

function onPrepareUpdateGPSPOS(&$override) { 
	return true;
}

function onBeforeUpdateGPSPOS() { 
	return true;
}

function onAfterUpdateGPSPOS() { 
	return true;
}

function onPrepareDeleteGPSPOS(&$override) { 
	return true;
}

function onBeforeDeleteGPSPOS() { 
	return true;
}

function onAfterDeleteGPSPOS() { 
	return true;
}

$companydata = getcurrentcompanydata("VATINCLUSIVE");
$page->vatinclusive = $companydata["VATINCLUSIVE"];
$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_custno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_salespersonname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_billno","OpenCFLfs()");

$page->businessobject->items->seteditable("docno",false);
//$page->businessobject->items->seteditable("u_date",false);
$page->businessobject->items->seteditable("u_totalbefdisc",false);
$page->businessobject->items->seteditable("u_totalquantity",false);
$page->businessobject->items->seteditable("u_vatamount",false);
$page->businessobject->items->seteditable("u_totalamount",false);
$page->businessobject->items->seteditable("u_paidamount",false);
$page->businessobject->items->seteditable("u_dueamount",false);

$page->businessobject->items->seteditable("u_cashamount",false);
$page->businessobject->items->seteditable("u_chequeamount",false);
$page->businessobject->items->seteditable("u_creditcardamount",false);
$page->businessobject->items->seteditable("u_otheramount",false);
$page->businessobject->items->seteditable("u_tfamount",false);
$page->businessobject->items->seteditable("u_dpamount",false);
$page->businessobject->items->seteditable("u_aramount",false);

$page->businessobject->items->seteditable("u_changecashamount",false);
$page->businessobject->items->seteditable("u_docnos",false);


$objGrids[0]->columntitle("u_quantity","Qty");
$objGrids[0]->columntitle("u_promocode","Promo");
$objGrids[0]->columntitle("u_freebie","Free");
$objGrids[0]->columnwidth("u_barcode",17);
$objGrids[0]->columnwidth("u_itemcode",17);
$objGrids[0]->columnwidth("u_itemdesc",40);
$objGrids[0]->columnwidth("u_itemclass",26);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columnwidth("u_quantity",7);
$objGrids[0]->columnwidth("u_discperc",6);
$objGrids[0]->columnwidth("u_discamount",8);
$objGrids[0]->columnwidth("u_serialno",15);
$objGrids[0]->columnwidth("u_promocode",7);
$objGrids[0]->columnwidth("u_freebie",4);
$objGrids[0]->columnwidth("u_tofollow",8);
$objGrids[0]->columnwidth("u_selected",2);
$objGrids[0]->columnwidth("u_package",8);

$objGrids[0]->columnvisibility("u_barcode",false);
//$objGrids[0]->columnvisibility("u_itemcode",false);
$objGrids[0]->columnvisibility("u_price",false);
$objGrids[0]->columnvisibility("u_vatcode",false);
$objGrids[0]->columnvisibility("u_vatrate",false);
$objGrids[0]->columnvisibility("u_vatamount",false);
//$objGrids[0]->columnvisibility("u_discperc",false);
//$objGrids[0]->columnvisibility("u_discamount",false);
$objGrids[0]->columnattributes("u_itemclass","disabled");
//$objGrids[0]->columnattributes("u_unitprice","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->columnattributes("u_uom","disabled");
$objGrids[0]->columnattributes("u_numperuom","disabled");
$objGrids[0]->columnattributes("u_tofollow","disabled");
//$objGrids[0]->columnattributes("u_serialno","");
$objGrids[0]->columnattributes("u_freebie","");
$objGrids[0]->columncfl("u_barcode","OpenCFLitembarcodes()");
$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnlnkbtn("u_itemcode","OpenLnkBtnItems()");
$objGrids[0]->columnalignment("u_freebie","center");
$objGrids[0]->columnalignment("u_tofollow","center");
$objGrids[0]->columnalignment("u_package","center");

$objGrids[0]->columndataentry("u_freebie","type","checkbox");
$objGrids[0]->columndataentry("u_freebie","value",1);
$objGrids[0]->columndataentry("u_tofollow","type","checkbox");
$objGrids[0]->columndataentry("u_tofollow","value",1);
$objGrids[0]->columndataentry("u_selected","type","checkbox");
$objGrids[0]->columndataentry("u_selected","value",1);
$objGrids[0]->columndataentry("u_package","type","checkbox");
$objGrids[0]->columndataentry("u_package","value",1);



$objGrids[0]->automanagecolumnwidth=false;
//
//$objGrids[1]->columncfl("u_checkdate","Calendar");
//$objGrids[1]->columnwidth("u_checkdate",12);
//$objGrids[1]->columnwidth("u_bank",20);
//$objGrids[1]->columnwidth("u_checkno",10);
//$objGrids[1]->columnwidth("u_amount",12);
//$objGrids[1]->automanagecolumnwidth=false;
//$objGrids[1]->width = 545;
//$objGrids[1]->height = 150;
//
//$objGrids[2]->columnwidth("u_creditcard",20);
//$objGrids[2]->columnwidth("u_creditcardno",20);
//$objGrids[2]->columnwidth("u_creditcardname",30);
//$objGrids[2]->columnwidth("u_expiredate",6);
//$objGrids[2]->columnwidth("u_approvalno",12);
//$objGrids[2]->columnwidth("u_amount",12);
//$objGrids[2]->automanagecolumnwidth=false;
//$objGrids[2]->width = 600;
//$objGrids[2]->height = 150;
//
//$objGrids[3]->columnwidth("u_cashcard",30);
//$objGrids[3]->columnwidth("u_refno",20);
//$objGrids[3]->columnwidth("u_amount",12);
//$objGrids[3]->automanagecolumnwidth=false;
//$objGrids[3]->width = 545;
//$objGrids[3]->height = 150;
//
//$objGrids[4]->columntitle("u_totalamount","Amount");
//$objGrids[4]->columntitle("u_balanceamount","Balance");
//$objGrids[4]->columntitle("u_amount","Applied");
//$objGrids[4]->columnwidth("u_selected",2);
//$objGrids[4]->columnwidth("u_refno",12);
//$objGrids[4]->columnwidth("u_custname",14);
//$objGrids[4]->columnwidth("u_remarks",13);
//$objGrids[4]->columnwidth("u_totalamount",8);
//$objGrids[4]->columnwidth("u_balanceamount",8);
//$objGrids[4]->columnwidth("u_amount",8);
//$objGrids[4]->columninput("u_selected","type","checkbox");
//$objGrids[4]->columninput("u_selected","value",1);
//$objGrids[4]->columninput("u_amount","type","text");
//$objGrids[4]->columnattributes("u_amount","disabled");
//$objGrids[4]->dataentry = false;
//$objGrids[4]->automanagecolumnwidth=false;
//$objGrids[4]->width = 630;
//$objGrids[4]->height = 150;

$page->businessobject->items->setvisible("u_orno",false);
$page->businessobject->items->setvisible("u_salespersonname",false);

$objGrids[0]->columnvisibility("u_freebie",false);
$objGrids[0]->columnvisibility("u_tofollow",false);
$objGrids[0]->columnvisibility("u_selected",false);
$objGrids[0]->columnvisibility("u_uom",false);
$objGrids[0]->columnvisibility("u_numperuom",false);
$objGrids[0]->columnvisibility("u_discperc",false);
$objGrids[0]->columnvisibility("u_discamount",false);
$objGrids[0]->columnvisibility("u_srp",false);
$objGrids[0]->columnvisibility("u_itemcost",false);
$objGrids[0]->columnvisibility("u_freebieamount",false);
$objGrids[0]->columnvisibility("u_freebielimit",false);
$objGrids[0]->columnvisibility("u_package",false);

$objGridFreebies = new grid("T101");
$objGridFreebies->addcolumn("u_itemcode");
$objGridFreebies->addcolumn("u_itemdesc");
$objGridFreebies->addcolumn("u_itemclass");
$objGridFreebies->addcolumn("u_uom");
$objGridFreebies->addcolumn("u_serialno");
$objGridFreebies->addcolumn(createSchemaAmount("u_srp","SRP"));
$objGridFreebies->addcolumn("u_tofollow");
$objGridFreebies->addcolumn("u_numperuom");
$objGridFreebies->addcolumn("u_itemmanageby");
$objGridFreebies->addcolumn("u_vatcode");
$objGridFreebies->columntitle("u_itemcode","Item Code");
$objGridFreebies->columntitle("u_itemdesc","Description");
$objGridFreebies->columntitle("u_itemclass","Item Class");
$objGridFreebies->columntitle("u_uom","Unit");
$objGridFreebies->columntitle("u_serialno","Serial No.");
$objGridFreebies->columntitle("u_srp","SRP");
$objGridFreebies->columntitle("u_tofollow","To Follow");
$objGridFreebies->columnwidth("u_itemcode",9);
$objGridFreebies->columnwidth("u_itemcode",17);
$objGridFreebies->columnwidth("u_itemdesc",40);
$objGridFreebies->columnwidth("u_itemclass",26);
$objGridFreebies->columnwidth("u_uom",5);
$objGridFreebies->columnwidth("u_serialno",15);
$objGridFreebies->columnwidth("u_srp",10);
$objGridFreebies->columnwidth("u_tofollow",8);
$objGridFreebies->columnvisibility("u_itemclass",false);
$objGridFreebies->columnvisibility("u_numperuom",false);
$objGridFreebies->columnvisibility("u_itemmanageby",false);
$objGridFreebies->columnvisibility("u_vatcode",false);
$objGridFreebies->columnalignment("u_tofollow","center");
$objGridFreebies->dataentry = true;
//$objGridFreebies->automanagecolumnwidth = false;
$objGridFreebies->columncfl("u_itemcode","OpenCFLitems()");
$objGridFreebies->columncfl("u_itemdesc","OpenCFLitems()");

$objGridFreebies->width = 855;
$objGridFreebies->height = 150;

$objGridFreebies->columninput("u_tofollow","type","checkbox");
$objGridFreebies->columninput("u_tofollow","value",1);

$objGridFreebies->columndataentry("u_tofollow","type","checkbox");
$objGridFreebies->columndataentry("u_tofollow","value",1);


if ($page->getvarstring("formType")!="popup") $page->resize->addinput("totalamountdisplay",330,-1);

$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);

//$page->setvar("AddonGoBack","u_Home");

$addoptions = false;
$deleteoption = false;
$page->toolbar->addbutton("u_payment","F4 - Payment","u_paymentGPSPOS()","right");
//$page->toolbar->addbutton("u_return","Return","u_returnGPSPOS()","left");
//$page->toolbar->addbutton("u_sales","Sales","u_salesGPSPOS()","left");

$page->businessobject->showpageheader = false;
		
$objMaster->reportaction = "QS";		
?> 

