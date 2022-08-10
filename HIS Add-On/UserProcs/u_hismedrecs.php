<?php

include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisdoctorservicetypes.php");

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

$postdata = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $postdata;
	$postdata["u_reftype"] = $page->getitemstring("u_reftype");
	$postdata["u_refno"] = $page->getitemstring("u_refno");
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select DOCNO from u_hismedrecs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='".$postdata["u_reftype"]."' and u_refno='".$postdata["u_refno"]."'");	
	if ($objRs->queryfetchrow("NAME")) {
		$page->setkey($objRs->fields["DOCNO"]);
		modeEdit();
		return false;	
	}
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $postdata;
	
	$page->setitem("u_docdate",currentdate());
	
	if ($postdata["u_reftype"]!="") {
		$objRs = new recordset(null,$objConnection);
		
		if ($postdata["u_reftype"]=="IP") {
			$objRs->queryopen("select * from u_hisips where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$postdata["u_refno"]."'");	
		} else {
			$objRs->queryopen("select * from u_hisops where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$postdata["u_refno"]."'");	
		}	
		if ($objRs->queryfetchrow("NAME")) {
			$page->setitem("u_reftype",$postdata["u_reftype"]);
			$page->setitem("u_refno",$postdata["u_refno"]);
			$page->setitem("u_patientid",$objRs->fields["U_PATIENTID"]);
			$page->setitem("u_patientname",$objRs->fields["U_PATIENTNAME"]);
			$page->setitem("u_doctorid",$objRs->fields["U_DOCTORID"]);
			$page->setitem("u_doctorservice",$objRs->fields["U_DOCTORSERVICE"]);
		}	
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
	global $objGrids;
	global $page;
  	$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/HIS/Medical Records/".$page->getitemstring("docno")."/Attachments/";
	if (is_dir($dirname)) {
		$dir_handle = opendir($dirname);
		if ($dir_handle) {
			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (is_file($dirname.$file)) {
						$path_parts = pathinfo($dirname.$file);
						if (strtolower($path_parts["extension"])=="gif" || strtolower($path_parts["extension"])=="jpg" || strtolower($path_parts["extension"])=="png") {			
							$objGrids[0]->addrow();
							$objGrids[0]->setitem(null,"u_picturename","Medical Records/".substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[0]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[0]->setitem(null,"u_filetype","img");
							$objGrids[0]->setitem(null,"rowstat","E");
						} elseif (strtolower($path_parts["extension"])=="mp4") {			
							$objGrids[0]->addrow();
							$objGrids[0]->setitem(null,"u_picturename","Medical Records/".substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[0]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[0]->setitem(null,"u_filetype","video");
							$objGrids[0]->setitem(null,"rowstat","E");
						} else {			
							$objGrids[0]->addrow();
							$objGrids[0]->setitem(null,"u_picturename","Medical Records/".substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[0]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[0]->setitem(null,"u_filetype","");
							$objGrids[0]->setitem(null,"rowstat","E");
						}
					}
			  }
			}
			closedir($dir_handle);
		}	
	}		   	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select docno from u_hislabtests where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='".$page->getitemstring("u_reftype")."' and u_refno='".$page->getitemstring("u_refno")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/HIS/Examinations/".$objRs->fields["docno"]."/Attachments/";
		if (is_dir($dirname)) {
			$dir_handle = opendir($dirname);
			if ($dir_handle) {
				while($file = readdir($dir_handle)) {
					if ($file != "." && $file != "..") {
						if (is_file($dirname.$file)) {
							$path_parts = pathinfo($dirname.$file);
							if (strtolower($path_parts["extension"])=="gif" || strtolower($path_parts["extension"])=="jpg" || strtolower($path_parts["extension"])=="png") {			
								$objGrids[0]->addrow();
								$objGrids[0]->setitem(null,"u_picturename","Examinations/".substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
								$objGrids[0]->setitem(null,"u_filepath",$dirname.$file);
								$objGrids[0]->setitem(null,"u_filetype","img");
								$objGrids[0]->setitem(null,"rowstat","E");
							} elseif (strtolower($path_parts["extension"])=="mp4") {			
								$objGrids[0]->addrow();
								$objGrids[0]->setitem(null,"u_picturename","Examinations/".substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
								$objGrids[0]->setitem(null,"u_filepath",$dirname.$file);
								$objGrids[0]->setitem(null,"u_filetype","video");
								$objGrids[0]->setitem(null,"rowstat","E");
							} else {			
								$objGrids[0]->addrow();
								$objGrids[0]->setitem(null,"u_picturename","Examinations/".substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
								$objGrids[0]->setitem(null,"u_filepath",$dirname.$file);
								$objGrids[0]->setitem(null,"u_filetype","");
								$objGrids[0]->setitem(null,"rowstat","E");
							}
						}
				  }
				}
				closedir($dir_handle);
			}	
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

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_patientid","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_icdcode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_rvscode","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_casetype",false);
$page->businessobject->items->seteditable("u_icdcode",false);
$page->businessobject->items->seteditable("u_icddesc",false);
$page->businessobject->items->seteditable("u_rvscode",false);
$page->businessobject->items->seteditable("u_rvsdesc",false);
$page->businessobject->items->seteditable("u_rvu",false);
$page->businessobject->items->seteditable("u_procedures",false);

$objGrids[0]->width = 300;
$objGrids[0]->columnwidth("u_picturename",80);
$objGrids[0]->columnwidth("u_filepath",10);
$objGrids[0]->columnvisibility("u_filepath",false);
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->setaction("reset",false);
$objGrids[0]->addbutton("delete","[Delete]","deleteAttachmentGPSHIS()","right");

$objGrids[1]->columncfl("u_icdcode","OpenCFLfs()");
$objGrids[1]->columncfl("u_icddesc","OpenCFLfs()");

$objGrids[2]->columncfl("u_rvscode","OpenCFLfs()");
$objGrids[2]->columncfl("u_rvsdesc","OpenCFLfs()");
$objGrids[2]->columncfl("u_date","Calendar");
$objGrids[2]->columnattributes("u_rvu","disabled");
$objGrids[2]->columnwidth("u_date",12);

$addoptions = false;
$deleteoption = false;

$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("new",false);


?> 

