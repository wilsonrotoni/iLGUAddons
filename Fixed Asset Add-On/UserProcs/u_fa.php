<?php
 
	require_once("./sls/brancheslist.php"); 
	require_once("./sls/departments.php"); 
	
//$page->businessobject->events->add->customAction("onCustomActionGPSFixedAsset");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSFixedAsset");
//$page->businessobject->events->add->afterDefault("onAfterDefaultGPSFixedAsset");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSFixedAsset");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSFixedAsset");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSFixedAsset");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSFixedAsset");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSFixedAsset");
$page->businessobject->events->add->afterEdit("onAfterEditGPSFixedAsset");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSFixedAsset");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSFixedAsset");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSFixedAsset");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSFixedAsset");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSFixedAsset");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSFixedAsset");

function onCustomActionGPSFixedAsset($action) {
	return true;
}

function onBeforeDefaultGPSFixedAsset() { 
	return true;
}

function onAfterDefaultGPSFixedAsset() { 
	return true;
}

function onPrepareAddGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeAddGPSFixedAsset() { 
	return true;
}

function onAfterAddGPSFixedAsset() { 
	return true;
}

function onPrepareEditGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeEditGPSFixedAsset() { 
	return true;
}

function onAfterEditGPSFixedAsset() { 
	global $page;
	global $objGrids;
	
  	$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/Fixed Asset/".$page->getitemstring("code")."/".$page->getitemstring("u_empid")."/Attachments/";
	if (is_dir($dirname)) {
		$dir_handle = opendir($dirname);
		if ($dir_handle) {
			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (is_file($dirname.$file)) {
						$path_parts = pathinfo($dirname.$file);
						if (strtolower($path_parts["extension"])=="gif" || strtolower($path_parts["extension"])=="jpg" || strtolower($path_parts["extension"])=="png") {			
							$objGrids[3]->addrow();
							$objGrids[3]->setitem(null,"u_picturename",substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[3]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[3]->setitem(null,"rowstat","E");
						}
					}
			  }
			}
			closedir($dir_handle);
		}	
	}		   	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select sum(u_amount) as u_amount, sum(u_cost) as u_cost from u_fachilds where code='".$page->getitemstring("code")."'");
	$objRs->queryfetchrow();
	$objGrids[0]->setdefaultvalue("u_amount",formatNumericAmount($objRs->fields[0]));
	$objGrids[0]->setdefaultvalue("u_cost",formatNumericAmount($objRs->fields[1]));
	
	
	return true;
}

function onPrepareUpdateGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeUpdateGPSFixedAsset() { 
	return true;
}

function onAfterUpdateGPSFixedAsset() { 
	return true;
}

function onPrepareDeleteGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeDeleteGPSFixedAsset() { 
	return true;
}

function onAfterDeleteGPSFixedAsset() { 
	return true;
}

$schema["u_branchname"] = createSchema("u_branchname");
$schema["u_departmentname"] = createSchema("u_departmentname");
$schema["u_branchname"]["attributes"] = "disabled";
$schema["u_departmentname"]["attributes"] = "disabled";

$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->seteditable("name",false);
$page->businessobject->items->seteditable("u_itemdesc",false);
$page->businessobject->items->seteditable("u_faclass",false);
$page->businessobject->items->seteditable("u_acquidate",false);
//$page->businessobject->items->seteditable("u_accumdepreacct",false);
//$page->businessobject->items->seteditable("u_depreacct",false);
$page->businessobject->items->seteditable("u_accumdepre",false);
$page->businessobject->items->seteditable("u_empid",false);
$page->businessobject->items->seteditable("u_empname",false);
$page->businessobject->items->seteditable("u_cost",false);
$page->businessobject->items->seteditable("u_salvagevalue",false);
$page->businessobject->items->seteditable("u_remainlife",false);
$page->businessobject->items->seteditable("u_bookvalue",false);
$page->businessobject->items->seteditable("u_srcdoc",false);
$page->businessobject->items->seteditable("u_srcname",false);
$page->businessobject->items->seteditable("u_branch",false);
$page->businessobject->items->seteditable("u_department",false);
$page->businessobject->items->seteditable("u_depredate",false);
$page->businessobject->items->seteditable("u_deptweightperc",false);

$page->businessobject->items->setcfl("u_profitcenter","OpenCFLprofitcenterdistributionrules()");
$page->businessobject->items->setcfl("u_projcode","OpenCFLprojects()");
$page->businessobject->items->setcfl("u_acquidate","Calendar");
$page->businessobject->items->setcfl("u_depredate","Calendar");

$addoptions=false;
$deleteoption=false;

$objGrids[0]->columnwidth("u_acquidate",14);
$objGrids[0]->columnwidth("u_itemcode",15);
$objGrids[0]->columnwidth("u_itemdesc",30);
$objGrids[0]->columnwidth("u_srcdoc",15);
$objGrids[0]->columnwidth("u_srcname",20);
$objGrids[0]->columnwidth("u_serialno",20);
$objGrids[0]->columnwidth("u_mfrserialno",20);
$objGrids[0]->columnwidth("u_property1",15);
$objGrids[0]->columnwidth("u_property2",15);
$objGrids[0]->columnwidth("u_property3",15);
$objGrids[0]->columnwidth("u_property4",15);
$objGrids[0]->columnwidth("u_property5",15);
$objGrids[0]->columnwidth("u_property6",15);
$objGrids[0]->columnwidth("u_property7",15);
$objGrids[0]->columnwidth("u_property8",15);
$objGrids[0]->columnwidth("u_property9",15);
$objGrids[0]->columnwidth("u_property10",15);
$objGrids[0]->columndataentry("u_acquidate","type","label");
$objGrids[0]->columndataentry("u_itemcode","type","label");
$objGrids[0]->columndataentry("u_itemdesc","type","label");
$objGrids[0]->columndataentry("u_srcdoc","type","label");
$objGrids[0]->columndataentry("u_srcname","type","label");
$objGrids[0]->columndataentry("u_serialno","type","label");
$objGrids[0]->columndataentry("u_mfrserialno","type","label");
$objGrids[0]->columndataentry("u_property1","type","label");
$objGrids[0]->columndataentry("u_property2","type","label");
$objGrids[0]->columndataentry("u_property3","type","label");
$objGrids[0]->columndataentry("u_property4","type","label");
$objGrids[0]->columndataentry("u_property5","type","label");
$objGrids[0]->columndataentry("u_property6","type","label");
$objGrids[0]->columndataentry("u_property7","type","label");
$objGrids[0]->columndataentry("u_property8","type","label");
$objGrids[0]->columndataentry("u_property9","type","label");
$objGrids[0]->columndataentry("u_property10","type","label");
$objGrids[0]->columnattributes("u_cost","disabled");
$objGrids[0]->columnattributes("u_amount","disabled");

$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->dataentry = true;
$objGrids[0]->showactionbar = false;

$objGrids[1]->columnlnkbtn("u_jvno","OpenLnkBtnJournalVouchers()");
$objGrids[1]->dataentry = false;
$objGrids[1]->automanagecolumnwidth = false;

$objGrids[2]->columnwidth("u_empid",10);
$objGrids[2]->columnwidth("u_assigneddate",11);
$objGrids[2]->columnwidth("u_department",20);
$objGrids[2]->columnwidth("u_profitcenter",15);
$objGrids[2]->columnwidth("u_projcode",15);
$objGrids[2]->dataentry = false;
$objGrids[2]->automanagecolumnwidth = false;

$objGrids[3]->width = 300;
$objGrids[3]->columnwidth("u_picturename",30);
$objGrids[3]->columnwidth("u_filepath",10);
$objGrids[3]->columnvisibility("u_filepath",false);
$objGrids[3]->automanagecolumnwidth = false;

$objGrids[4]->columncfl("u_glacctno","OpenCFLchartofaccounts()");
$objGrids[4]->columncfl("u_projcode","OpenCFLprojects()");
$objGrids[4]->columnwidth("u_glacctno",30);
$objGrids[4]->columndataentry("u_department","type","select");
$objGrids[4]->columndataentry("u_department","options",array("loaddepartments","",":[Select]"));
$objGrids[4]->columnattributes("u_glacctname","disabled");

$page->toolbar->setaction("new",false);
$page->toolbar->setaction("add",false);
?> 

