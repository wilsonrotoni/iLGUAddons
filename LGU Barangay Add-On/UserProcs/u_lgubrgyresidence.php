<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUBarangay");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUBarangay");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUBarangay");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUBarangay");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUBarangay");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUBarangay");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUBarangay");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUBarangay");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUBarangay");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUBarangay");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUBarangay");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUBarangay");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUBarangay");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUBarangay");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUBarangay");

function onCustomActionGPSLGUBarangay($action) {
	return true;
}

function onBeforeDefaultGPSLGUBarangay() { 
	return true;
}

function onAfterDefaultGPSLGUBarangay() { 
        global $objConnection;
	global $page;
	global $objGrids;
        
        $page->setitem("u_appdate",currentdate());
	$page->setitem("docstatus","Very Good");
	return true;
}

function onPrepareAddGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeAddGPSLGUBarangay() { 
	return true;
}

function onAfterAddGPSLGUBarangay() { 
	return true;
}

function onPrepareEditGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeEditGPSLGUBarangay() { 
	return true;
}

function onAfterEditGPSLGUBarangay() { 
	return true;
}

function onPrepareUpdateGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUBarangay() { 
	return true;
}

function onAfterUpdateGPSLGUBarangay() { 
        global $httpVars;
	//var_dump($httpVars["pf_photodata"]);
	if ($httpVars["pf_photodata"]!="") {
		mkdirr($httpVars["pf_photopath"]);
		$filename = realpath($httpVars["pf_photopath"]) ."\photo.png";
		//var_dump($filename);
		//var_dump($filename);
		$src = $httpVars["pf_photodata"];
		$src  = substr($src, strpos($src, ",") + 1);
		$decoded = base64_decode($src);
		
		$fp = fopen($filename,'wb');
		fwrite($fp, $decoded);
		fclose($fp);
	}
	return true;
}

function onPrepareDeleteGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUBarangay() { 
	return true;
}

function onAfterDeleteGPSLGUBarangay() { 
	return true;
}
$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_idexpdate","Calendar");
$page->businessobject->items->setcfl("u_idissueddate","Calendar");
$page->businessobject->items->setcfl("u_dateofbirth","Calendar");
$page->businessobject->items->setcfl("u_residentsince","Calendar");

$page->businessobject->items->seteditable("u_age",false);
$page->businessobject->items->seteditable("u_voterid",false);
$page->businessobject->items->seteditable("u_preceinctno",false);
$page->businessobject->items->seteditable("u_voteraddress",false);
$objGrids[0]->dataentry = false;
?> 

