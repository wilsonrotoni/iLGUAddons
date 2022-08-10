<?php
 
if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf" || $_SESSION["theme"]=="gps") { 
	$objRs = new recordset(null,$objConnection);
$objRs->queryopen("SELECT u_font FROM u_prglobalsetting");
		while ($objRs->queryfetchrow("NAME")) {
			$page->businessobject->csspath = "../Addons/GPS/PayRoll Add-On/UserPrograms/".$objRs->fields["u_font"]."/";
		}
}
if ($_SESSION["menunavigator"]=="successfactors") { 
	$page->businessobject->showpageheader = false;
}


//$page->businessobject->events->add->customAction("onCustomActionGPSKiosk");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSKiosk");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSKiosk");

$page->businessobject->events->add->prepareAdd("onPrepareAddGPSKiosk");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSKiosk");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSKiosk");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSKiosk");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSKiosk");
$page->businessobject->events->add->afterEdit("onAfterEditGPSKiosk");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSKiosk");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSKiosk");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSKiosk");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSKiosk");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSKiosk");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSKiosk");

function onCustomActionGPSKiosk($action) {
	return true;
}

function onBeforeDefaultGPSKiosk() { 
	return true;
}

function onAfterDefaultGPSKiosk() { 
	global $objConnection;
	global $page;
	$page->setitem("u_appdate",currentdate());
	if($_SESSION["menunavigator"] == "topnav") {
		$page->setitem("code","XXXXX");
	} else {
		$page->setitem("code", getNextSeriesNoByBranch($page->objectcode,"PRIMARY",formatDateToDB(currentdate()),$objConnection,false));
	}
	$objRs = new recordset(NULL,$objConnection);
	$objRs2 = new recordset(NULL,$objConnection);
	$objRs3 = new recordset(NULL,$objConnection);
	$objRs->queryopen("SELECT a.u_branch FROM u_premploymentdeployment a WHERE a.code = '".$_SESSION["userid"]."' AND a.u_stat = 'Main'");
		if ($objRs->recordcount() > 0) {
			while ($objRs->queryfetchrow()) {
				$page->setitem("u_profitcenter",$objRs->fields[0]);
				$page->setitem("u_empid",$_SESSION["userid"]);
				$page->setitem("u_empname",$_SESSION["username"]);
				$page->setitem("u_tastatus","Successful");
				$page->setitem("name","Request Time Adj. - ".$_SESSION["username"]);
				$page->setitem("u_status",0);
			}
		}
		
	$objRs2->queryopen("SELECT u_biometrixsetup FROM u_prglobalsetting");
	while ($objRs2->queryfetchrow("NAME")) {
		if($objRs2->fields["u_biometrixsetup"] != "Unique") {
			$objRs3->queryopen("SELECT u_branches,u_biometrix_id FROM u_premploymentinfobiometrix a WHERE a.code = '".$_SESSION["userid"]."'");
		} else {
			$objRs3->queryopen("SELECT '' as u_branches,u_biometrixid FROM u_premploymentinfo a WHERE a.code = '".$_SESSION["userid"]."'");
		}
			if ($objRs3->recordcount() > 0) {
				while ($objRs3->queryfetchrow()) {
					$page->setitem("u_branches",$objRs3->fields[0]);
					$page->setitem("u_biometrixid",$objRs3->fields[1]);
				}
			}
		}
	$page->toolbar->addbutton("a","Add Request","formSubmit('a')","right");
	return true;
}

function onPrepareAddGPSKiosk(&$override) { 
	global $page;
	$page->toolbar->addbutton("a","Add Request","formSubmit('a')","right");
	return true;
}

function onBeforeAddGPSKiosk() { 
	global $objConnection;
	global $objMaster;
	global $page;
		$objMaster->code = getNextSeriesNoByBranch($page->objectcode,"PRIMARY",formatDateToDB(currentdate()),$objConnection,true);
	return true;
}

function onAfterAddGPSKiosk() { 
	return true;
}

function onPrepareEditGPSKiosk(&$override) { 
	return true;
}

function onBeforeEditGPSKiosk() { 
	return true;
}

function onAfterEditGPSKiosk() { 
	global $page;
	global $pageHeaderStatusText;
	global $objGridAH;
	
	$counter = 0;
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT b.u_stagedesc as username,IF(a.u_status='A','Approved','Denied') as xstatus,DATE_FORMAT(a.datecreated,'%W, %M %d, %Y [ %r ]') as datecreated,a.u_remarks FROM u_tkapproverhistory a INNER JOIN u_tkapproverfileassigned b ON b.u_stagename = a.createdby INNER JOIN u_premploymentinfo c ON c.company =a.company AND c.branch = a.branch AND c.u_employstatus2 = b.code AND c.code = '".$page->getitemstring("u_empid")."' WHERE a.company = '".$_SESSION["company"]."' AND a.branch = '".$_SESSION["branch"]."' AND a.u_requestno = '".$page->getitemstring("code")."'");
		if($objRs->recordcount() > 0) {
			while ($objRs->queryfetchrow("NAME")) {
				$objGridAH->addrow();
				$objGridAH->setitem(null,"approver",$objRs->fields["username"]);
				$objGridAH->setitem(null,"status",$objRs->fields["xstatus"]);
				$objGridAH->setitem(null,"datecreated",$objRs->fields["datecreated"]);
				$objGridAH->setitem(null,"remarks",$objRs->fields["u_remarks"]);
				$objGridAH->setitem(null,"rowstat","E");
				$counter++;
			}
		}
	
	if($counter == 0) {
		$page->toolbar->addbutton("del","Delete","formSubmit('d')","left");
	} else {
		$page->toolbar->addbutton("ah","Approved History","formSubmit('ah')","left");
	}
	
	if($page->getitemstring("u_status") == 1) {
		$page->toolbar->addbutton("cn","Cancelled","formSubmit('ccn')","left");
	}
	
	if($page->getitemstring("u_status") == 0){
		$pageHeaderStatusText .= "<font color='black'>** P E N D I N G ** &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>";
		$page->toolbar->setaction("print",false);
		$page->toolbar->addbutton("?","New Request","formSubmit('?')","right");
		$page->toolbar->addbutton("sc","Update Request","formSubmit('sc')","right");
	} else if($page->getitemstring("u_status") == 1) {
		$pageHeaderStatusText .= "<font color='black'>** A P P R O V E D ** &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>";
		$page->toolbar->setaction("print",true);
	} else if($page->getitemstring("u_status") == -1) {
		$pageHeaderStatusText .= "<font color='black'>** C A N C E L L E D ** &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>";
		$page->toolbar->setaction("print",false);
	} else if($page->getitemstring("u_status") == 2) {
		$pageHeaderStatusText .= "<font color='black'>** D E N I E D ** &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>";
		$page->toolbar->setaction("print",false);
	}
	
	if ($page->getitemstring("u_status")!=0) {
		$page->setvar("formAccess","R");
	}
	
	return true;
}

function onPrepareUpdateGPSKiosk(&$override) { 
	return true;
}

function onBeforeUpdateGPSKiosk() { 
	return true;
}

function onAfterUpdateGPSKiosk() { 
	return true;
}

function onPrepareDeleteGPSKiosk(&$override) { 
	return true;
}

function onBeforeDeleteGPSKiosk() { 
	return true;
}

function onAfterDeleteGPSKiosk() { 
	return true;
}

$objRs->queryopen("SELECT u_offon_pf FROM u_prglobalsetting");
while ($objRs->queryfetchrow("NAME")) {
	if($objRs->fields["u_offon_pf"] == 1) {
		$page->businessobject->items->setvisible("u_profitcenter",false);
		$page->businessobject->items->setvisible("u_branches",false);
		$page->businessobject->items->setvisible("u_biometrixid",false);
	} else {
		$page->businessobject->items->setvisible("u_profitcenter",true);
		$page->businessobject->items->setvisible("u_branches",true);
		$page->businessobject->items->setvisible("u_biometrixid",true);
		
	}
}

$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->seteditable("name",false);
$page->businessobject->items->seteditable("u_biometrixid",false);
$page->businessobject->items->seteditable("u_empid",false);
//	$page->businessobject->items->seteditable("u_empname",false);
$page->businessobject->items->seteditable("u_appdate",false);

$page->businessobject->items->setcfl("u_appdate","Calendar");

$objRs2 = new recordset(null,$objConnection);
$objRs2->queryopen("select suser from users where userid='".$_SESSION["userid"]."'");
while ($objRs2->queryfetchrow("NAME")) {
	if($objRs2->fields["suser"] == "Y") {
		$page->businessobject->items->setcfl("u_empname","OpenCFLfs()");
		$page->businessobject->items->seteditable("u_empname",true);
	} else {
		$page->businessobject->items->seteditable("u_empname",false);
	}
}

$objGrids[0]->columnwidth("u_date",12);
$objGrids[0]->columnwidth("u_type",10);
$objGrids[0]->columnwidth("u_time",12);
$objGrids[0]->columncfl("u_date","Calendar");
$objGrids[0]->automanagecolumnwidth = false;

$objGridAH = new grid("T10");
$objGridAH->addcolumn("approver");
$objGridAH->addcolumn("status");
$objGridAH->addcolumn("datecreated");
$objGridAH->addcolumn("remarks");

$objGridAH->columntitle("approver","Approver");
$objGridAH->columntitle("status","Status");
$objGridAH->columntitle("datecreated","Date Created");
$objGridAH->columntitle("remarks","Remarks");
	
$objGridAH->columnwidth("approver",20);
$objGridAH->columnwidth("status",5);
$objGridAH->columnwidth("datecreated",26);
$objGridAH->columnwidth("remarks",37);

$objGridAH->automanagecolumnwidth = false;
$objGridAH->dataentry = false;
$objGridAH->width = 770;
$objGridAH->height = 130;

$addoptions = false;
	 
//$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("new",false);

?> 

