<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
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


function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	$page->setitem("code","-");
	$page->setitem("u_startdate",currentdate());
	$page->setitem("u_starttime",currenttime());
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
	global $page;
	global $objGrids;
	
  	$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/HIS/Laboratory Sub-Tests/".$page->getitemstring("code")."/Attachments/";
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
$page->businessobject->items->setcfl("u_enddate","Calendar");

$schema["u_template"] = createSchema("u_template");

$objGrids[0]->columntitle("u_seq","Seq 1");
$objGrids[0]->columntitle("u_seq2","Seq 2");
$objGrids[0]->columnwidth("u_seq",5);
$objGrids[0]->columnwidth("u_seq2",5);
$objGrids[0]->columnwidth("u_test",20);
$objGrids[0]->columnwidth("u_group",20);
$objGrids[0]->columnwidth("u_result",15);
$objGrids[0]->columnwidth("u_normalrange",15);
$objGrids[0]->columnwidth("u_formularesult",15);
$objGrids[0]->columnwidth("u_formulanormalrange",20);
$objGrids[0]->columnwidth("u_units",8);
$objGrids[0]->columnwidth("u_remarks",15);
$objGrids[0]->columnattributes("u_seq","disabled");
$objGrids[0]->columnattributes("u_test","disabled");
$objGrids[0]->columnattributes("u_normalrange","disabled");
$objGrids[0]->columnattributes("u_units","disabled");
$objGrids[0]->columnvisibility("u_formula",false);
$objGrids[0]->columninput("u_result","type","text");
$objGrids[0]->columninput("u_remarks","type","text");
$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;

$objGrids[0]->columnwidth("u_print",6);
$objGrids[0]->columninput("u_print","type","checkbox");
$objGrids[0]->columninput("u_print","value",1);

$objGrids[1]->width = 300;
$objGrids[1]->columnwidth("u_picturename",30);
$objGrids[1]->columnwidth("u_filepath",10);
$objGrids[1]->columnvisibility("u_filepath",false);
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->setaction("reset",false);

$addoptions = false;
$deleteoption = false;

$page->toolbar->setaction("new",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("find",false);

//$page->bodyclass = "yui-skin-sam";
?> 

