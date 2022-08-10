<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprocs/sls/u_hishealthins.php");

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
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	//$page->setitem("code",getNextNoByBranch('U_HISPATIENTS',0,$objConnection,false));
	if ($page->settings->data["autogenerate"]==1) {
		$page->setitem("u_series",getSeries($page->objectcode,"Auto",$objConnection,false));
		if ($page->getitemstring("u_series")!=-1) {
			$page->setitem("u_type","Auto");
			$page->setitem("code", getNextSeriesNoByBranch($page->objectcode,$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,false));
		}	

	} else {
		$page->setitem("u_series",-1);
		$page->setitem("code", "");
	}	
	
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
		$objMaster->code = getNextSeriesNoByBranch($page->objectcode,$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,true);
	}	
	return true;
}

function onAfterAddGPSHIS() { 
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

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	global $page;
	global $objGrids;
	global $objGridExams;
	
	$objRs = new recordset(null,$objConnection);
	//$objRs->setdebug();
	$objRs->queryopen("select a.docno, a.u_enddate, a.u_endtime, a.u_department, c.name as u_departmentname, b.u_itemdesc, e.name as u_doctorname2 from u_hischarges a inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_template<>'' left join u_hissections c on c.code=a.u_department left join u_hisdoctors e on e.code=a.u_doctorid2 where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_patientid='".$page->getitemstring("code") ."' and a.u_enddate is not null");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridExams->addrow();
		$objGridExams->setitem(null,"date",formatDateToHttp($objRs->fields["u_enddate"]));
		$objGridExams->setitem(null,"time",formatTimeToHttp($objRs->fields["u_endtime"]));
		$objGridExams->setitem(null,"docno",$objRs->fields["docno"]);
		$objGridExams->setitem(null,"doctorname2",$objRs->fields["u_doctorname2"]);
		$objGridExams->setitem(null,"department",$objRs->fields["u_department"]);
		$objGridExams->setitem(null,"departmentname",$objRs->fields["u_departmentname"]);
		$objGridExams->setitem(null,"itemdesc",$objRs->fields["u_itemdesc"]);
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

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	return true;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T1":
			if ($column=="u_reftype") {
				switch ($label) {
					case "IP": $label = "In-Patient"; break;
					case "OP": $label = "Out-Patient"; break;
				}
			}	
			break;
		case "T2":
			if ($column=="u_inscode") $label = slgetdisplayu_hishealthins($label);
			break;
	}
}

$page->businessobject->items->setcfl("u_birthdate","Calendar");
$page->businessobject->items->setcfl("u_barangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_nationality","OpenCFLfs()");
$page->businessobject->items->setcfl("u_religion","OpenCFLfs()");
$page->businessobject->items->setcfl("u_city","OpenCFLfs()");
$page->businessobject->items->setcfl("u_province","OpenCFLfs()");
$page->businessobject->items->setcfl("u_fatherbarangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_fathercity","OpenCFLfs()");
$page->businessobject->items->setcfl("u_fatherprovince","OpenCFLfs()");
$page->businessobject->items->setcfl("u_motherbarangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_mothercity","OpenCFLfs()");
$page->businessobject->items->setcfl("u_motherprovince","OpenCFLfs()");
$page->businessobject->items->setcfl("u_spousebarangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_spousecity","OpenCFLfs()");
$page->businessobject->items->setcfl("u_spouseprovince","OpenCFLfs()");
$page->businessobject->items->setcfl("u_employerbarangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_employercity","OpenCFLfs()");
$page->businessobject->items->setcfl("u_employerprovince","OpenCFLfs()");
$page->businessobject->items->setcfl("u_contactbarangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_contactcity","OpenCFLfs()");
$page->businessobject->items->setcfl("u_contactprovince","OpenCFLfs()");
$page->businessobject->items->setcfl("u_responsiblebarangay","OpenCFLfs()");
$page->businessobject->items->setcfl("u_responsiblecity","OpenCFLfs()");
$page->businessobject->items->setcfl("u_responsibleprovince","OpenCFLfs()");

$page->businessobject->items->seteditable("code",false);
$page->businessobject->items->seteditable("name",false);
$page->businessobject->items->seteditable("u_expired",false);

$objGrids[0]->columnwidth("u_reftype",12);
$objGrids[0]->columnwidth("u_refno",12);
$objGrids[0]->columnwidth("u_starttime",10);
$objGrids[0]->columnwidth("u_endtime",10);
$objGrids[0]->columnwidth("u_expiretime",10);
$objGrids[0]->columnwidth("u_status",15);
$objGrids[0]->columnlnkbtn("u_refno","OpenLnkBtnu_hispatientregs()");
$objGrids[0]->dataentry = false;

$objGrids[1]->columnvisibility("u_hmo",false);
$objGrids[1]->columnwidth("u_memberid",15);
$objGrids[1]->columnwidth("u_membertype",20);
$objGrids[1]->columnwidth("u_membername",40);
$objGrids[1]->columnwidth("u_memberextname",5);
$objGrids[1]->columnwidth("u_memberbirthdate",12);
$objGrids[1]->columncfl("u_memberid","OpenCFLfs()");
$objGrids[1]->columncfl("u_memberbirthdate","Calendar");
$objGrids[1]->columntitle("u_memberfirstname","First Name");
$objGrids[1]->columntitle("u_memberextname","Ext.");
$objGrids[1]->columntitle("u_membermiddlename","Middle Name");
$objGrids[1]->columndataentry("u_inscode","options",array("loadu_hishealthins2","",":"));
$objGrids[1]->automanagecolumnwidth = false;

$objGrids[2]->columnvisibility("u_refcount",false);
$objGrids[2]->dataentry = false;

$objGrids[3]->columncfl("u_icdcode","OpenCFLfs()");
//$objGrids[3]->columncfl("u_icddesc","OpenCFLfs()");
$objGrids[3]->columnwidth("u_icdcode",12);
$objGrids[3]->columnwidth("u_icddesc",35);
$objGrids[3]->columnattributes("u_icddesc","disabled");
$objGrids[3]->width=416;

$objGridExams = new grid("T10");
$objGridExams->addcolumn("date");
$objGridExams->addcolumn("time");
$objGridExams->addcolumn("docno");
$objGridExams->addcolumn("department","departmentname");
$objGridExams->addcolumn("itemdesc");
$objGridExams->addcolumn("doctorid2","doctorname2");
$objGridExams->columntitle("date","Date");
$objGridExams->columntitle("time","Time");
$objGridExams->columntitle("docno","No.");
$objGridExams->columntitle("department","Section");
$objGridExams->columntitle("itemdesc","Description");
$objGridExams->columntitle("doctorid2","Reader");
$objGridExams->columnwidth("date",10);
$objGridExams->columnwidth("time",6);
$objGridExams->columnwidth("docno",12);
$objGridExams->columnwidth("department",15);
$objGridExams->columnwidth("itemdesc",35);
$objGridExams->columnwidth("doctorid2",25);
$objGridExams->columnlnkbtn("docno","OpenLnkBtnExamDocNo()");
$objGridExams->dataentry = false;


$page->businessobject->resettabindex();
$page->businessobject->items->settabindex("u_lastname");
$page->businessobject->items->settabindex("u_firstname");
$page->businessobject->items->settabindex("u_middlename");
$page->businessobject->items->settabindex("u_extname");
$page->businessobject->items->settabindex("u_gender");
$page->businessobject->items->settabindex("u_civilstatus");
$page->businessobject->items->settabindex("u_birthdate");
$page->businessobject->items->settabindex("u_address");
$page->businessobject->items->settabindex("u_city");
$page->businessobject->items->settabindex("u_province");
$page->businessobject->items->settabindex("u_telno");
$page->businessobject->items->settabindex("u_occupation");
$page->businessobject->items->settabindex("u_nationality");
$page->businessobject->items->settabindex("u_religion");
$addoptions=false;
//$deleteoption=false;

?> 

