<?php

include_once("./sls/users.php");

//$objLicMgr->checkAddon("GPS.Audit Trail","Standard",true);

$httpVars["formNoNew"] = true; 
$httpVars["formNoAdd"] = true; 
$httpVars["formNoUpdate"] = true; 

$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("find",false);
$page->restoreSavedValues();	

$objGrid = new grid("T1");
$objGrid->addcolumn(array("name"=>"datetime","description"=>"Date/Time","width"=>"20"));
$objGrid->addcolumn(array("name"=>"userid","description"=>"User ID","width"=>"15"));
$objGrid->addcolumn(array("name"=>"username","description"=>"User Name","width"=>"25"));
$objGrid->addcolumn(array("name"=>"u_menuid","description"=>"Menu ID","width"=>"15","align"=>"right"));
$objGrid->addcolumn(array("name"=>"u_menuname","description"=>"Menu Name","width"=>"25","align"=>"right"));
$objGrid->addcolumn(array("name"=>"u_code","description"=>"Code/Document No.","width"=>"25","align"=>"right"));
$objGrid->addcolumn(array("name"=>"u_action","description"=>"Action","width"=>"10","align"=>"right"));
$objGrid->columnlnkbtn("u_action","OpenLnkBtnu_audittrailGPSAuditTrail()");
$page->resize->addgridobject($objGrid,20,170);

$objMaster->reportaction = "QS";
$httpVars["formPrintShowOnNew"] = true;
$page->businessobject->events->add->prepareEdit("onFormEditGPSAuditTrail");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSAuditTrail");

$visualrecs = 15;
$displayedrecs = 0;
$page->restoreSavedValues();	

function onAfterDefaultGPSAuditTrail() {
	global $httpVars;
	global $page;
	
	$httpVars["df_branchcode"] = ($httpVars["df_branchcode"]=="") ? $_SESSION['branch'] : $httpVars["df_branchcode"];
} # end onAfterDefaultGPSAuditTrail

function onFormEditGPSAuditTrail() {
	global $objMaster;
	global $httpVars;
	global $page;
	global $objGrid;
	
	$httpVars["formSavedBy"]="";
	
	$objAudittrailsetup = new masterdataschema(NULL,$objConnection,"u_audittrailsetup");
	
	$sqlins = " where date_format(datecreated,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
	
	if($httpVars["df_userid"]<>"") $sqlins .= " and createdby='".$httpVars["df_userid"]."' ";
	if($httpVars["df_branchcode"]<>"") $sqlins .= " and branch='".$httpVars["df_branchcode"]."' ";
	if($httpVars["df_u_menuid"]<>"") $sqlins .= " and u_menucommand='".$httpVars["df_u_menuid"]."' ";
	if($httpVars["df_document"]<>"") $sqlins .= " and u_code='".$httpVars["df_document"]."' ";
	
	$sql = "select COMPANY, BRANCH, DOCID, DOCNO, DOCSERIES, DOCSTATUS, DATECREATED, CREATEDBY, LASTUPDATED, LASTUPDATEDBY, RCDVERSION, U_OBJECTCODE, U_PROGID, U_PROGID2, U_ACTION, U_MENUCOMMAND, U_MENUNAME, U_CODE, U_SESSIONID, U_IP from u_audittrail ".$sqlins;
	
	if(($httpVars["df_u_menuid"]=="" || $httpVars["df_u_menuid"]=="Logins") && $httpVars["df_document"]=="") {
		$cont = true;
		if($objAudittrailsetup->getbysql("CODE='Logins'")) {
			if($objAudittrailsetup->getudfvalue("u_log")=="No") $cont = false;
		}
		if($cont) {
			$sqlins = " where date_format(login,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
			if($httpVars["df_userid"]<>"") $sqlins .= " and userid='".$httpVars["df_userid"]."' ";
			$sql .= " union select '', '', 0, '', '', '', LOGIN, USERID, LOGIN, USERID, 0, 'LOGIN', '', '', '', 'Login', 'User Login', '', SESSIONID, IP from logins ".$sqlins;
			
			$sqlins = " where date_format(logout,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
			if($httpVars["df_userid"]<>"") $sqlins .= " and userid='".$httpVars["df_userid"]."' ";
			$sql .= " union select '', '', 0, '', '', '', LOGOUT, USERID, LOGOUT, USERID, 0, 'LOGOUT', '', '', '', 'Logout', 'User Logout', '', SESSIONID, IP from logins ".$sqlins;
		}
	}
	
	if(($httpVars["df_u_menuid"]=="" || $httpVars["df_u_menuid"]=="Reports") && $httpVars["df_document"]=="") {
		$cont = true;
		if($objAudittrailsetup->getbysql("CODE='Reports'")) {
			if($objAudittrailsetup->getudfvalue("u_log")=="No") $cont = false;
		}
		if($cont) {
			$sqlins = " where date_format(reportts,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
			if($httpVars["df_branchcode"]<>"") $sqlins .= " and branch='".$httpVars["df_branchcode"]."' ";
			if($httpVars["df_userid"]<>"") $sqlins .= " and userid='".$httpVars["df_userid"]."' ";
			$sql .= " union select COMPANY, BRANCH, 0, '', '', '', REPORTTS, USERID, REPORTTS, USERID, 0, 'REPORT', '', '', '', REPORTID, 'Report View', '', SESSIONID, REMOTEIP from reportlogs ".$sqlins;
		}
	}
	
	$objGrid->sortby = "datecreated";
	
	$obj = new recordset(NULL,$objConnection);
	$obj->queryopenext($sql, strtoupper($objGrid->sortby),$filterstr,$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//echo $sql;
	$page->paging_recordcount($obj->recordcount());
	while ($obj->queryfetchrow("NAME")) {
		$i++;
		$data = array();
		$data['df_datetimeT1r'.$i] = $obj->fields["DATECREATED"];
		$data['df_useridT1r'.$i] = $obj->fields["CREATEDBY"];
		$data['df_usernameT1r'.$i] = slgetdisplayusers($obj->fields["CREATEDBY"]);
		$data['df_u_menuidT1r'.$i] = $obj->fields["U_MENUCOMMAND"];
		$data['df_u_menunameT1r'.$i] = $obj->fields["U_MENUNAME"];
		$data['df_u_codeT1r'.$i] = $obj->fields["U_CODE"];
		$data['df_u_actionT1r'.$i] = $obj->fields["U_ACTION"];
		$data['sf_keysT1r'.$i] = $obj->fields["COMPANY"]."`".$obj->fields["BRANCH"]."`".$obj->fields["DOCID"];
		$data['df_rowstatT1r'.$i] = "E";
		$objGrid->addrowdata($data);
		if (!$page->paging_fetch()) break;
	}
} # end onFormEditGPSAuditTrail

?>