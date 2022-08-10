<?php
 
	include_once("./classes/itempricelists.php");
	include_once("./classes/items.php");
	include_once("./utils/suppliers.php");
	include_once("./utils/companies.php");
	include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
	
//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$companydata = getcurrentcompanydata("PURCHASINGPRICELIST");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	global $objGridPrices;
	global $objGridStocks;
	global $companydata;

	$u_hissetupdata = getu_hissetup("U_MEDSUPMARKUP");
	
	if ($page->settings->data["autogenerate"]==1) {
		$page->setitem("u_series",getSeries($page->getobjectdoctype(),"Auto",$objConnection,false));
		if ($page->getitemstring("u_series")!=-1) {
			$page->setitem("code", getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,false));
		}	

	} else {
		$page->setitem("u_series",-1);
		$page->setitem("code", "");
	}	

	$page->privatedata["markup"] = $u_hissetupdata["U_MEDSUPMARKUP"];
	$page->privatedata["purchasingpricelist"] = $companydata["PURCHASINGPRICELIST"];
	
	getPricelistsGPSHIS();	
	
	$objGridStocks->clear();
		
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
	global $objConnection;
	global $objMaster;
	global $page;
	if ($page->getitemstring("u_series")!=-1) {
		$objMaster->code = getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,true);
	}	
	return true;
}

function onAfterAddGPSHIS() { 
	$actionReturn = updatePricelistsGPSHIS();
	return $actionReturn;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() {
	global $page;
	global $objGridStocks;
	global $companydata;
	
	$u_hissetupdata = getu_hissetup("U_MEDSUPMARKUP");
	
	if ($page->getitemstring("u_preferredsuppno")!="") {
		$supplierdata = getsupplierdata($page->getitemstring("u_preferredsuppno"),"SUPPNAME");
		$page->setitem("u_preferredsuppname",$supplierdata["SUPPNAME"]);
	}		 
	getPricelistsGPSHIS();
	
	$objGridStocks->clear();
	$instockqty=0;
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select b.warehousename, a.instockqty from stockcardsummary a inner join warehouses b on b.company=a.company and b.branch=a.branch and b.warehouse=a.warehouse where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.itemcode='".$page->getitemstring("code")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridStocks->addrow();
		$objGridStocks->setitem(null,"warehousename",$objRs->fields["warehousename"]);
		$objGridStocks->setitem(null,"instockqty",formatNumericQuantity($objRs->fields["instockqty"]));
		$instockqty+=$objRs->fields["instockqty"];
	}
	$objGridStocks->setdefaultvalue("instockqty",formatNumericAmount($instockqty));
	
	$page->privatedata["markup"] = $u_hissetupdata["U_MEDSUPMARKUP"];
	$page->privatedata["purchasingpricelist"] = $companydata["PURCHASINGPRICELIST"];
	
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	$actionReturn = updatePricelistsGPSHIS();
	return $actionReturn;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	$actionReturn = updatePricelistsGPSHIS(true);
	return $actionReturn;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T3":
			if ($column=="u_department") {
				$objRs->queryopen("select name from u_hissections where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}
			break;
	}
}

include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hispricelists.php");


$page->objectdoctype = "ITEM";
$page->businessobject->items->setcfl("u_preferredsuppno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_brandname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_genericname","OpenCFLfs()");

$page->businessobject->items->seteditable("u_vatable",false);

if (isset($objGrids[2])) {
	$objGrids[2]->columncfl("u_itemcode","OpenCFLfs()");
	$objGrids[2]->columncfl("u_itemdesc","OpenCFLfs()");
	$objGrids[2]->columntitle("u_department","Section");
	$objGrids[2]->columnattributes("u_uom","disabled");
	$objGrids[2]->columnvisibility("u_department",false);
}

$objGridStocks = new grid("T10");
$objGridStocks->addcolumn("warehousename");
$objGridStocks->addcolumn("instockqty");
$objGridStocks->columntitle("warehousename","Section");
$objGridStocks->columntitle("instockqty","In-Stock");
$objGridStocks->columnwidth("warehousename",30);
$objGridStocks->columnwidth("instockqty",12);
$objGridStocks->columndataentry("warehousename","type","label");
$objGridStocks->columnalignment("instockqty","right");
$objGridStocks->columnattributes("instockqty","disabled");
$objGridStocks->dataentry = true;
$objGridStocks->showactionbar = false;
	
$addoptions = false;

?> 

