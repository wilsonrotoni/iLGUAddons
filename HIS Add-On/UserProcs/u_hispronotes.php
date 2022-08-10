<?php
 include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hishealthins.php");
 include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisbilldoctors.php");
 include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hisbillcasecodes.php");
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

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$postdata = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	if ($page->settings->data["autogenerate"]==1) {
		$page->setitem("docseries",getSeries($page->objectcode,"Credit Memo",$objConnection,false));
		if ($page->getitemstring("docseries")!=-1) {
			$page->setitem("u_type","Credit Memo");
			$page->setitem("docno", getNextSeriesNoByBranch($page->objectcode,$page->getitemstring("docseries"),formatDateToDB(currentdate()),$objConnection,false));
		}	

	} else {
		$page->setitem("docseries",-1);
		$page->setitem("docno", "");
	}	
	
	if ($page->getvarstring("btrefno")!="") {
		$objRs = new recordset(null,$objConnection);
		//$objRs->setdebug();
		$objRs->queryopen("select a.u_feetype, a.u_doctorid, c.balanceamount from u_hispronotes a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docno=a.u_jvdocno inner join journalvoucheritems c on c.company=b.company and c.branch=b.branch and c.docid=b.docid and c.itemtype='C' and c.reftype='' and c.balanceamount >= 0 where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$page->getvarstring("btrefno")."' and a.docstatus not in ('CN')");
		if ($objRs->queryfetchrow("NAME")) {
			$page->setitem("u_btrefno",$page->getvarstring("btrefno"));
			$page->setitem("u_btdoctorid",$objRs->fields["u_doctorid"]);
			$page->setitem("u_feetype",$objRs->fields["u_feetype"]);
			$page->setitem("u_doctorid",$objRs->fields["u_doctorid"]);
			$page->setitem("u_amount",formatNumericAmount($objRs->fields["balanceamount"]));
			$page->setitem("u_netamount",formatNumericAmount($objRs->fields["balanceamount"]));
			
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

$objRs = new recordset(null,$objConnection);

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
}

$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_memberid","OpenCFLfs()");

$page->businessobject->items->seteditable("u_billno",false);
$page->businessobject->items->seteditable("u_reftype",false);
$page->businessobject->items->seteditable("u_refno",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_amount",false);
$page->businessobject->items->seteditable("u_netamount",false);

$page->businessobject->items->seteditable("u_xmtalno",false);
$page->businessobject->items->seteditable("u_xmtaldate",false);

$page->businessobject->items->seteditable("u_jvdocno",false);
$page->businessobject->items->seteditable("u_jvcndocno",false);

$page->businessobject->items->seteditable("u_exclaim",false);

$addoptions = false;
$deleteoption = false;
$page->toolbar->setaction("new",false);
$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);
?> 

