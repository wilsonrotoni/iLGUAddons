<?php
 

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
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSHIS");

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	if ($page->settings->data["autogenerate"]==1) {
		//$page->setitem("u_series",getDefaultSeries($page->objectcode,$objConnection));
		$page->setitem("u_series",getSeries($page->getobjectdoctype(),"Auto",$objConnection,false));
		if ($page->getitemstring("u_series")!=-1) {
			$page->setitem("code", getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("u_series"),formatDateToDB(currentdate()),$objConnection,false));
		}	
	} else {
		$page->setitem("u_series",-1);
		$page->setitem("code", "");
	}	
	
	//getPricelistsGPSHIS();	
	
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
	$actionReturn = true;
	return $actionReturn;
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

	//getPricelistsGPSHIS();

	$objRs->queryopen("select a.CODE, a.NAME, a.U_FORMAT, a.U_REMARKS, b.NAME AS U_DOCTORNAME from u_hislabtesttypenotes a left outer join u_hisdoctors b on b.code=a.u_doctorid where a.u_type='".$page->getitemstring("code")."'");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"code",$objRs->fields["CODE"]);
		$objGrid->setitem(null,"doctorname",iif($objRs->fields["U_DOCTORNAME"]=="","ALL",$objRs->fields["U_DOCTORNAME"]));
		$objGrid->setitem(null,"template",$objRs->fields["NAME"]);
		$objGrid->setitem(null,"format",$objRs->fields["U_FORMAT"]);
		$objGrid->setitem(null,"remarks",$objRs->fields["U_REMARKS"]);
		$objGrid->setitem(null,"rowstat","E");
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

function onPrepareChildEditGPSHIS($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T1":	
			$filerExp = " ORDER BY U_MTEMPLATE, U_TEMPLATE, U_SEQ, U_SEQ2, U_GENDER DESC, U_GROUP";
			break;
			
	}
}

//$page->objectdoctype = "ITEM";

$objGrids[0]->columntitle("u_seq","Seq 1");

$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_itemdesc",30);
$objGrids[0]->columnwidth("u_mtemplate",8);
$objGrids[0]->columnwidth("u_template",7);
$objGrids[0]->columnwidth("u_seq",5);
$objGrids[0]->columnwidth("u_seq2",5);
$objGrids[0]->columnwidth("u_gender",5);
$objGrids[0]->columnwidth("u_agefr",5);
$objGrids[0]->columnwidth("u_ageto",5);
$objGrids[0]->columnwidth("u_group",20);
$objGrids[0]->columnwidth("u_test",20);
$objGrids[0]->columnwidth("u_units",8);
$objGrids[0]->columnwidth("u_print",4);
$objGrids[0]->columnwidth("u_normalrange",12);
$objGrids[0]->columnwidth("u_formula",10);
$objGrids[0]->columnwidth("u_formulanormalrange",12);
$objGrids[0]->columnwidth("u_formulaunits",8);
$objGrids[0]->columntitle("u_agefr","Fr Age");
$objGrids[0]->columntitle("u_formulanormalrange","Normal Range");
$objGrids[0]->columntitle("u_formulaunits","Units");
$objGrids[0]->columnvisibility("u_itemcode",false);
$objGrids[0]->columnlnkbtn("u_test","OpenLnkBtnu_hisexamcases()");

$objGrids[0]->columndataentry("u_print","type","checkbox");
$objGrids[0]->columndataentry("u_print","value",1);
$objGrids[0]->columnalignment("u_print","center");

$objGrid = new grid("T101");
$objGrid->addcolumn("code");
$objGrid->addcolumn("doctorname");
$objGrid->addcolumn("template");
$objGrid->addcolumn("format");
$objGrid->addcolumn("remarks");
$objGrid->columntitle("code","Code");
$objGrid->columntitle("doctorname","Doctor");
$objGrid->columntitle("template","Template");
$objGrid->columntitle("format","Format");
$objGrid->columnwidth("code",25);
$objGrid->columnwidth("doctorname",30);
$objGrid->columnwidth("format",6);
$objGrid->columnwidth("template",30);
$objGrid->columnvisibility("code",false);
$objGrid->columnvisibility("remarks",false);
$objGrid->addbutton("u_addrtfnotes","[Add RTF Notes]","u_addrtfnotesGPSHIS()","left");
$objGrid->addbutton("u_addnotes","[Add Notes]","u_addnotesGPSHIS()","left");
$objGrid->width = 500;
$objGrid->dataentry = true;
$objGrid->setaction("reset",false);
$objGrid->setaction("add",false);

//include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hispricelists.php");

$addoptions = false;

?> 

