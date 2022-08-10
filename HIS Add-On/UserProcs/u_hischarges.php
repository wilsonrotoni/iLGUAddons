<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->fetchDBRow("onFetchDBRowGPSHIS");

$u_trxtype="";

function onFetchDBRowGPSHIS($objrs) {
	global $canceloption;
	switch ($objrs->dbtable) {
		case "u_hischargeitems":
			if ($objrs->fields["U_RTQTY"]>0) $canceloption = false;
			break;
	}
	
}

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	$u_trxtype = $page->getitemstring("u_trxtype");
	$page->privatedata["requestbyreftype"] = $page->getvarstring("requestbyreftype");
	$page->privatedata["requestbyrefno"] = $page->getvarstring("requestbyrefno");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $userdepartment;
	global $u_trxtype;
	global $edtopt;
	$u_hissetupdata = getu_hissetup("U_DFLTPHARMADEPT,U_DFLTLABDEPT,U_DFLTRADIODEPT,U_DFLTPHARMATFS, U_DFLTIPD, U_DFLTOPD, U_DFLTHEARTSTATIONDEPT, U_STOCKLINK, U_PREPAIDIPLAB, U_PREPAIDOPLAB, U_PHAVATPOS");
	
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
	$page->setitem("u_trxtype",$u_trxtype);
	$page->setitem("u_chargeby",$_SESSION["userid"]);
	
	if ($userdepartment!="" && $page->getitemstring("u_trxtype")!="BILLING") $page->setitem("u_department",$userdepartment);
	elseif ($page->getitemstring("u_trxtype")=="XRAY") $page->setitem("u_department",$u_hissetupdata["U_DFLTRADIODEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="CTSCAN") $page->setitem("u_department",$u_hissetupdata["U_DFLTRADIODEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="ULTRASOUND") $page->setitem("u_department",$u_hissetupdata["U_DFLTRADIODEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") $page->setitem("u_department",$u_hissetupdata["U_DFLTHEARTSTATIONDEPT"]);
	elseif ($page->getitemstring("u_trxtype")=="LABORATORY")  $page->setitem("u_department",$u_hissetupdata["U_DFLTLABDEPT"]);
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_stocklink from u_hissections where code='".$page->getitemstring("u_department")."'");
	if ($objRs->queryfetchrow("NAME")) $page->setitem("u_stocklink",$objRs->fields["u_stocklink"]);	

	$page->privatedata["prepaidip"] = $u_hissetupdata["U_PREPAIDIPLAB"];
	$page->privatedata["prepaidop"] = $u_hissetupdata["U_PREPAIDOPLAB"];

	$page->privatedata["phavatpos"] = $u_hissetupdata["U_PHAVATPOS"];
	
	if ($page->getitemstring("u_trxtype")=="IP") {
		$page->setitem("u_prepaid",$page->privatedata["prepaidip"]);
	} else $page->setitem("u_prepaid",$page->privatedata["prepaidop"]);

	if ($edtopt=="testpreview") {
		$page->setitem("docseries",-1);
		$page->setitem("docno",-1);
		$page->setitem("docstatus","-");
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
	global $objConnection;
	global $page;
	global $objGrids;
	global $objGrid;
	
	$objRs = new recordset(null,$objConnection);
	
	$objRs->queryopen("select * from u_hislabsubtests where u_labtestno='".$page->getitemstring("docno")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"code",$objRs->fields["CODE"]);
		$objGrid->setitem(null,"name",$objRs->fields["NAME"]);
		$objGrid->setitem(null,"startdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
		$objGrid->setitem(null,"starttime",$objRs->fields["U_STARTTIME"]);
		$objGrid->setitem(null,"enddate",formatDateToHttp($objRs->fields["U_ENDDATE"]));
		$objGrid->setitem(null,"endtime",$objRs->fields["U_ENDTIME"]);
		$objGrid->setitem(null,"rowstat","E");
	}
	
  	$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/HIS/Examinations/".$page->getitemstring("docno")."/Attachments/";
	if (is_dir($dirname)) {
		$dir_handle = opendir($dirname);
		if ($dir_handle) {
			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (is_file($dirname.$file)) {
						$path_parts = pathinfo($dirname.$file);
						if (strtolower($path_parts["extension"])=="gif" || strtolower($path_parts["extension"])=="jpg" || strtolower($path_parts["extension"])=="png") {			
							$objGrids[1]->addrow();
							$objGrids[1]->setitem(null,"u_picturename",substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[1]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[1]->setitem(null,"u_filetype","img");
							$objGrids[1]->setitem(null,"rowstat","E");
						} elseif (strtolower($path_parts["extension"])=="mp4") {			
							$objGrids[1]->addrow();
							$objGrids[1]->setitem(null,"u_picturename",substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[1]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[1]->setitem(null,"u_filetype","video");
							$objGrids[1]->setitem(null,"rowstat","E");
						} else {			
							$objGrids[1]->addrow();
							$objGrids[1]->setitem(null,"u_picturename",substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[1]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[1]->setitem(null,"u_filetype","");
							$objGrids[1]->setitem(null,"rowstat","E");
						}
					}
			  }
			}
			closedir($dir_handle);
		}	
	}		   	
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

$page->businessobject->items->setcfl("u_startdate","Calendar");
$page->businessobject->items->setcfl("u_reqdate","Calendar");
$page->businessobject->items->setcfl("u_specimendate","Calendar");
$page->businessobject->items->setcfl("u_enddate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_requestno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_gender",false);
$page->businessobject->items->seteditable("u_birthdate",false);
$page->businessobject->items->seteditable("u_age",false);
$page->businessobject->items->seteditable("u_paymentterm",false);
$page->businessobject->items->seteditable("u_prepaid",false);
$page->businessobject->items->seteditable("u_payrefno",false);
//$page->businessobject->items->seteditable("u_specimen",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$page->businessobject->items->seteditable("u_pfnetamount",false);
$page->businessobject->items->seteditable("u_pfapno",false);

$page->businessobject->items->seteditable("u_statperc",false);
$page->businessobject->items->seteditable("u_unitprice",false);
$page->businessobject->items->seteditable("u_price",false);
$page->businessobject->items->seteditable("u_amount",false);


$page->businessobject->items->setvisible("u_cancelledby",true);
$page->businessobject->items->setvisible("u_cancelledremarks",true);

$schema["u_template"] = createSchema("u_template");

$objGrid = new grid("T101");
$objGrid->addcolumn("code");
$objGrid->addcolumn("name");
$objGrid->addcolumn("startdate");
$objGrid->addcolumn("starttime");
$objGrid->addcolumn("enddate");
$objGrid->addcolumn("endtime");
$objGrid->columntitle("code","Code");
$objGrid->columntitle("name","Description");
$objGrid->columntitle("startdate","Start Date");
$objGrid->columntitle("starttime","Start Time");
$objGrid->columntitle("enddate","End Date");
$objGrid->columntitle("endtime","End Time");
$objGrid->columnwidth("code",25);
$objGrid->columnwidth("name",30);
$objGrid->columnwidth("startdate",10);
$objGrid->columnwidth("starttime",6);
$objGrid->columnwidth("enddate",10);
$objGrid->columnwidth("endtime",6);
$objGrid->columnvisibility("code",false);
$objGrid->dataentry = true;
$objGrid->setaction("reset",false);
$objGrid->setaction("add",false);

$objGrids[0]->columnwidth("u_template",12);
$objGrids[0]->columntitle("u_seq","Seq 1");
$objGrids[0]->columntitle("u_seq2","Seq 2");
$objGrids[0]->columnwidth("u_seq",5);
$objGrids[0]->columnwidth("u_seq2",5);
$objGrids[0]->columnwidth("u_test",20);
$objGrids[0]->columnwidth("u_group",20);
$objGrids[0]->columnwidth("u_result",15);
$objGrids[0]->columnwidth("u_normalrange",15);
$objGrids[0]->columnwidth("u_formularesult",15);
$objGrids[0]->columnwidth("u_formulanormalrange",15);
$objGrids[0]->columnwidth("u_units",8);
$objGrids[0]->columnwidth("u_remarks",30);
$objGrids[0]->columntitle("u_formulanormalrange","Normal Range");
$objGrids[0]->columntitle("u_formulaunits","Units");
$objGrids[0]->columnattributes("u_seq","disabled");
$objGrids[0]->columnattributes("u_test","disabled");
$objGrids[0]->columnvisibility("u_formula",false);
$objGrids[0]->columninput("u_template","type","text");
$objGrids[0]->columninput("u_result","type","text");
$objGrids[0]->columninput("u_remarks","type","text");
$objGrids[0]->columninput("u_normalrange","type","text");
$objGrids[0]->columninput("u_formulanormalrange","type","text");
$objGrids[0]->columninput("u_formularesult","type","text");
$objGrids[0]->columninput("u_formulaunits","type","text");
//$objGrids[0]->columnattributes("u_normalrange","disabled");
//$objGrids[0]->columnattributes("u_units","disabled");
$objGrids[0]->columninput("u_units","type","text");
$objGrids[0]->columncfl("u_template","OpenCFLfs()");
$objGrids[0]->columncfl("u_result","OpenCFLfs()");
$objGrids[0]->columnlnkbtn("u_test","OpenLnkBtnu_hisexamcases()");
$objGrids[0]->dataentry = false;

$objGrids[0]->columnwidth("u_selected",4);
$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columnalignment("u_selected","center");

$objGrids[0]->columnwidth("u_print",4);
$objGrids[0]->columninput("u_print","type","checkbox");
$objGrids[0]->columninput("u_print","value",1);
$objGrids[0]->columnalignment("u_print","center");

$objGrids[1]->width = 300;
$objGrids[1]->columnwidth("u_picturename",30);
$objGrids[1]->columnwidth("u_filepath",10);
$objGrids[1]->columnvisibility("u_filepath",false);
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->setaction("reset",false);

$objGrids[2]->columntitle("u_prevclosedate","Previous Verified Date");
$objGrids[2]->columntitle("u_prevclosetime","Time");
$objGrids[2]->columnwidth("u_by",30);
$objGrids[2]->columnwidth("u_remarks",30);
$objGrids[2]->columnwidth("u_prevclosedate",22);
$objGrids[2]->columnwidth("u_prevclosetime",6);
$objGrids[2]->dataentry = false;

$objGrids[3]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[3]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[3]->columncfl("u_doctorname","OpenCFLfs()");
$objGrids[3]->columnwidth("u_itemgroup",15);
$objGrids[3]->columnwidth("u_itemcode",15);
$objGrids[3]->columnwidth("u_itemdesc",40);
$objGrids[3]->columnwidth("u_uom",5);
$objGrids[3]->columnwidth("u_statperc",5);
$objGrids[3]->columnwidth("u_quantity",8);
$objGrids[3]->columnwidth("u_vatamount",8);
$objGrids[3]->columnwidth("u_statunitprice",17);
$objGrids[3]->columnwidth("u_quantity",7);
$objGrids[3]->columnwidth("u_isfoc",3);
$objGrids[3]->columnwidth("u_price",14);
$objGrids[3]->columnwidth("u_doctorname",30);
$objGrids[3]->columnwidth("u_remarks",30);
$objGrids[3]->columnwidth("u_ispackage",7);
$objGrids[3]->columnwidth("u_isautodisc",8);
$objGrids[3]->columnwidth("u_iscashdisc",8);
$objGrids[3]->columnwidth("u_isbilldisc",8);
$objGrids[3]->columnwidth("u_packagedprice",12);
$objGrids[3]->columntitle("u_ispackage","Package");
$objGrids[3]->columntitle("u_quantity","Qty");
$objGrids[3]->columntitle("u_isfoc","FOC");
$objGrids[3]->columntitle("u_price","Price After Disc");
$objGrids[3]->columnattributes("u_uom","disabled");
$objGrids[3]->columnattributes("u_linetotal","disabled");
$objGrids[3]->columnattributes("u_ispackage","disabled");
$objGrids[3]->columnattributes("u_iscashdisc","disabled");
$objGrids[3]->columnattributes("u_isbilldisc","disabled");
$objGrids[3]->columnattributes("u_chrgqty","disabled");
$objGrids[3]->columnattributes("u_rtqty","disabled");
$objGrids[3]->columnalignment("u_isautodisc","center");
$objGrids[3]->columnalignment("u_isfoc","center");
$objGrids[3]->columninput("u_selected","type","checkbox");
$objGrids[3]->columninput("u_selected","value",1);
$objGrids[3]->columninput("u_isstat","type","checkbox");
$objGrids[3]->columninput("u_isstat","value",1);
$objGrids[3]->columninput("u_quantity","type","text");
$objGrids[3]->columninput("u_remarks","type","text");
$objGrids[3]->columndataentry("u_selected","type","checkbox");
$objGrids[3]->columndataentry("u_selected","value",1);
$objGrids[3]->columndataentry("u_isautodisc","type","checkbox");
$objGrids[3]->columndataentry("u_isautodisc","value",1);
$objGrids[3]->columndataentry("u_isstat","type","checkbox");
$objGrids[3]->columndataentry("u_isstat","value",1);
$objGrids[3]->columndataentry("u_isfoc","type","checkbox");
$objGrids[3]->columndataentry("u_isfoc","value",1);
$objGrids[3]->columnvisibility("u_itemcode",false);
$objGrids[3]->columnvisibility("u_chrgqty",false);
$objGrids[3]->automanagecolumnwidth = false;

$objGrids[4]->columnwidth("u_packagecode",15);
$objGrids[4]->columnwidth("u_itemcode",15);
$objGrids[4]->columnwidth("u_itemdesc",30);
$objGrids[4]->columnwidth("u_uom",5);
$objGrids[4]->columnwidth("u_qtyperpack",11);
$objGrids[4]->columnwidth("u_quantity",7);
$objGrids[4]->columntitle("u_packageqty","No of Package");
$objGrids[4]->columnattributes("u_packagecode","disabled");
$objGrids[4]->columnattributes("u_packageqty","disabled");
$objGrids[4]->columnattributes("u_itemcode","disabled");
$objGrids[4]->columnattributes("u_itemdesc","disabled");
$objGrids[4]->columnattributes("u_quantity","disabled");
$objGrids[4]->columnattributes("u_uom","disabled");
$objGrids[4]->columnattributes("u_qtyperpack","disabled");
$objGrids[4]->columnattributes("u_chrgqty","disabled");
$objGrids[4]->columnattributes("u_rtqty","disabled");
$objGrids[4]->columnattributes("u_selected","disabled");
$objGrids[4]->columnvisibility("u_itemcode",false);
$objGrids[4]->columninput("u_selected","type","checkbox");
$objGrids[4]->columninput("u_selected","value",1);
$objGrids[4]->automanagecolumnwidth = false;
$objGrids[4]->dataentry = false;


$objrs = new recordset(null,$objConnection);
$userdepartment = "";
/*
$objrs->queryopen("select DEPARTMENT FROM EMPLOYEES WHERE USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select ROLEID AS DEPARTMENT FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	$userdepartment = $objrs->fields["DEPARTMENT"];
	if ($userdepartment!="" && $page->getitemstring("u_trxtype")!="BILLING") $page->businessobject->items->seteditable("u_department",false);
}

$page->toolbar->setaction("navigation",false);

$addoptions = false;
$deleteoption = false;
$canceloption = true;

//$page->bodyclass = "yui-skin-sam";
?> 

