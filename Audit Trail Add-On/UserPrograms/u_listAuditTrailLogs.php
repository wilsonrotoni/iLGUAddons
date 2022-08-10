<?php
	$progid = "u_listAuditTrailLogs";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./sls/salespersons.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	$page->restoreSavedValues();	
	$page->reportlayouts = true;
	$page->objectcode = "u_listSMS";
	$page->paging->formid = "./UDP.php?&objectcode=u_listAuditTrailLogs";
	$page->formid = "./UDO.php?&objectcode=U_SMS";
	$page->objectname = "SMS";
	
	$schema["datefrom"] = createSchemaDate("datefrom");
	$schema["dateto"] = createSchemaDate("dateto");
	$schema["u_menuid"] = createSchemaAny("u_menuid");
	$schema["userid"] = createSchemaAny("userid");
	$schema["branchcode"] = createSchemaAny("branchcode");
	$schema["document"] = createSchemaAny("document");
	$schema["logins"] = createSchemaAny("logins");
	$schema["reportview"] = createSchemaAny("reportview");
	
	$schema["datefrom"]["cfl"] = "Calendar";
	$schema["dateto"]["cfl"] = "Calendar";

	$objrs = new recordset(null,$objConnection);
	
	$objGrid = new grid("T1");
	$objGrid->addcolumn(array("name"=>"datetime","description"=>"Date/Time","width"=>"15"));
	$objGrid->addcolumn(array("name"=>"userid","description"=>"User ID","width"=>"30"));
	$objGrid->addcolumn(array("name"=>"username","description"=>"User Name","width"=>"25"));
	$objGrid->addcolumn(array("name"=>"u_menuid","description"=>"Menu ID","width"=>"30","align"=>"right"));
	$objGrid->addcolumn(array("name"=>"u_menuname","description"=>"Menu Name","width"=>"50","align"=>"right"));
	$objGrid->addcolumn(array("name"=>"branch","description"=>"Branch","width"=>"10"));
	//$objGrid->addcolumn(array("name"=>"ip","description"=>"IP","width"=>"13"));
	$objGrid->addcolumn(array("name"=>"u_code","description"=>"Code/Document No.","width"=>"30","align"=>"right"));
	$objGrid->addcolumn(array("name"=>"u_action","description"=>"Action","width"=>"10","align"=>"right"));
	$objGrid->columnlnkbtn("u_action","OpenLnkBtnu_audittrailGPSAuditTrail()");	
	//$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_datetime";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	$groupby = "";
	$isgroup = false;
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		global $groupby;
		global $isgroup;
		global $lookupSortBy;
		global $objGrid;
		global $page;
		//if ($lookupSortBy=="docno") {
		/*	switch ($column) {
				case "u_date":
					//$page->console->insertVar(array($row,$objGrid->getitemstring($row,"docno")));
					if ($groupby!=$objGrid->getitemstring($row,"docno")) {
						$groupby = $objGrid->getitemstring($row,"docno");
						$isgroup = false;
					} else {
						$isgroup = true;
						$label = "";
					}	
					break;
				case "u_acctno":
				case "docno":
				case "u_trxtype":
				case "docstatus":
				case "u_totalamount":
					if ($isgroup) $label = "";
					break;
			}*/
		//}
	}
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $page;
		global $objGrid;
		$actionReturn = true;
		return $actionReturn;
	}
	
		
	require("./inc/formactions.php");
	
	if (!isset($httpVars['df_datefrom'])) {
		$page->setitem("branchcode",$_SESSION['branch']);
		$page->setitem("logins",1);
		$page->setitem("reportview",1);
		//$objGrid->sortby = "u_datetime";
		//$objGrid->sortas = "desc";
	}


	$objAudittrailsetup = new masterdataschema(NULL,$objConnection,"u_audittrailsetup");
	
	$sqlins = " where date_format(datecreated,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
	
	if($httpVars["df_userid"]<>"") $sqlins .= " and createdby='".$httpVars["df_userid"]."' ";
	if($httpVars["df_branchcode"]<>"") $sqlins .= " and branch='".$httpVars["df_branchcode"]."' ";
	if($httpVars["df_u_menuid"]<>"") $sqlins .= " and u_menucommand='".$httpVars["df_u_menuid"]."' ";
	if($httpVars["df_document"]<>"") $sqlins .= " and u_code='".$httpVars["df_document"]."' ";
	
	$sql = "select COMPANY, BRANCH, DOCID, DOCNO, DOCSERIES, DOCSTATUS, DATECREATED, CREATEDBY, LASTUPDATED, LASTUPDATEDBY, RCDVERSION, U_OBJECTCODE, U_PROGID, U_PROGID2, U_ACTION, U_MENUCOMMAND, U_MENUNAME, U_CODE, U_SESSIONID, U_IP from u_audittrail ".$sqlins;
	
	if(($httpVars["df_u_menuid"]=="" || $httpVars["df_u_menuid"]=="Logins") && $httpVars["df_document"]=="" && $page->getitemstring("logins")=="1") {
		$cont = true;
		/*if($objAudittrailsetup->getbysql("CODE='Logins'")) {
			if($objAudittrailsetup->getudfvalue("u_log")=="No") $cont = false;
		}
		if($cont) {*/
			$sqlins = " where date_format(login,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
			if($httpVars["df_userid"]<>"") $sqlins .= " and userid='".$httpVars["df_userid"]."' ";
			$sql .= " union select '', '', 0, '', '', '', LOGIN, USERID, LOGIN, USERID, 0, 'LOGIN', '', '', '', 'Login', 'User Login', '', SESSIONID, IP AS U_IP from logins ".$sqlins;
			
			$sqlins = " where date_format(logout,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
			if($httpVars["df_userid"]<>"") $sqlins .= " and userid='".$httpVars["df_userid"]."' ";
			$sql .= " union select '', '', 0, '', '', '', LOGOUT, USERID, LOGOUT, USERID, 0, 'LOGOUT', '', '', '', 'Logout', 'User Logout', '', SESSIONID, IP AS U_IP from logins ".$sqlins;
		//}
	}
	
	if(($httpVars["df_u_menuid"]=="" || $httpVars["df_u_menuid"]=="Reports") && $httpVars["df_document"]=="" && $page->getitemstring("reportview")=="1") {
		/*$cont = true;
		if($objAudittrailsetup->getbysql("CODE='Reports'")) {
			if($objAudittrailsetup->getudfvalue("u_log")=="No") $cont = false;
		}
		if($cont) {*/
			$sqlins = " where date_format(reportts,'%Y-%m-%d') between '".formatDateToDB($httpVars["df_datefrom"])."' and '".formatDateToDB($httpVars["df_dateto"])."' ";
			if($httpVars["df_branchcode"]<>"") $sqlins .= " and branch='".$httpVars["df_branchcode"]."' ";
			if($httpVars["df_userid"]<>"") $sqlins .= " and userid='".$httpVars["df_userid"]."' ";
			$sql .= " union select COMPANY, BRANCH, 0, '', '', '', REPORTTS, USERID, REPORTTS, USERID, 0, 'REPORT', '', '', '', REPORTID, 'Report View', '', SESSIONID, REMOTEIP AS U_IP from reportlogs ".$sqlins;
		//}
	}
	
	$objGrid->sortby = "datecreated";

	$obj = new recordset(NULL,$objConnection);
	$obj->queryopenext($sql, strtoupper($objGrid->sortby),$filterstr,$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	//echo $sql;
	$page->paging_recordcount($obj->recordcount());
	while ($obj->queryfetchrow("NAME")) {
		/*$i++;
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
*/
		$objGrid->addrow();
		
		$objGrid->setitem(null,"datetime",formatDateTimeToHttp($obj->fields["DATECREATED"]));
		$objGrid->setitem(null,"userid",$obj->fields["CREATEDBY"]);
		$objGrid->setitem(null,"username",slgetdisplayusers($obj->fields["CREATEDBY"]));
		$objGrid->setitem(null,"u_menuid",$obj->fields["U_MENUCOMMAND"]);
		$objGrid->setitem(null,"u_menuname",$obj->fields["U_MENUNAME"]);
		$objGrid->setitem(null,"branch",$obj->fields["BRANCH"]);
		$objGrid->setitem(null,"ip",$obj->fields["U_IP"]);
		$objGrid->setitem(null,"u_code",$obj->fields["U_CODE"]);
		$objGrid->setitem(null,"u_action",$obj->fields["U_ACTION"]);
		$objGrid->setkey(null,$obj->fields["COMPANY"]."`".$obj->fields["BRANCH"]."`".$obj->fields["DOCID"]); 

		if (!$page->paging_fetch()) break;
	}
	
	/*$sqlinsExp = "";
	$sqlinsExp = genSQLFilterString("U_MOBILENO",$sqlinsExp,$httpVars['df_u_mobileno'],null,null,true);//,null,null,true
	$sqlinsExp = genSQLFilterDate("U_DATE",$sqlinsExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
	//$sqlinsExp = genSQLFilterString("B.U_DECISION",$sqlinsExp,$httpVars['df_u_decision']);
	if ($sqlinsExp !="") $sqlinsExp = " WHERE " . $sqlinsExp;
	//var_dump($sqlinsExp);
	$objrs = new recordset(null,$objConnection);
	$objrs->setdebug();
	
	if ($_SESSION["errormessage"]=="") {
	
		$objGrid->clear();
	
		$objrs->queryopenext("select code, u_mobileno, u_datetime, u_message, u_remarks from u_sms $sqlinsExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			
			$objGrid->setitem(null,"u_datetime",formatDateTimeToHttp($objrs->fields["u_datetime"]));
			$objGrid->setitem(null,"u_mobileno",$objrs->fields["u_mobileno"]);
			$objGrid->setitem(null,"u_message",$objrs->fields["u_message"]);
			$objGrid->setitem(null,"u_remarks",$objrs->fields["u_remarks"]);
			$objGrid->setkey(null,$objrs->fields["code"]); 
			if (!$page->paging_fetch()) break;
		}	
	}
	*/
		
	$page->resize->addgrid("T1",20,170,false);
	
	
	function onPopupMenuDraw($objPopupMenu) {
		global $page;
	}
	
	//$page->toolbar->setaction("print",false);
	
	//$rptcols = 6; 
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="<?php autocaching('js/formobjs.js'); ?>" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		focusInput("datefrom");
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	function onReportGetParams(p_formattype,p_params) {
		var params = new Array();
		if (p_params!=null) params = p_params;
		params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);
		
		params["source"] = "aspx";
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			/*if (getPrivate("mode")=="batch") {
				var rc = getTableRowCount("T1"), docno="";
				for (idx = 1; idx <= rc; idx++) {
					if (isTableInputChecked("T1","checked",idx)) {
						if (docno!="") docno += ","; 
						docno += getTableInput("T1","docno",idx);
					}	
				}				
				setTableInput("T101","printedremarks",docno);
				params["querystring"] += generateQueryString("docno",docno);
			} else {
				
			}*/	
			params["querystring"] += generateQueryString("datefrom",formatDateToDB(getInput("datefrom")));
			params["querystring"] += generateQueryString("dateto",formatDateToDB(getInput("dateto")));
			params["querystring"] += generateQueryString("branch",getInput("branchcode"));
			params["querystring"] += generateQueryString("userid",getInput("userid"));
			params["querystring"] += generateQueryString("menuid",getInput("u_menuid"));
			params["querystring"] += generateQueryString("logins",getInput("logins"));
			params["querystring"] += generateQueryString("reportview",getInput("reportview"));
		}	
		//params["recordselectionformula"]="{payments.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}
	

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				//setKey("keys",getTableKey("T1","keys",p_rowIdx));
				//return formView(null,"./UDO.php?&objectcode=U_SIMS");
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "u_menuid":
			case "userid":
				clearTable("T1");
				formPageReset(); 
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "branchcode":
			case "document":
			case "datefrom":
			case "dateto":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (table) {
			default:
				switch (column) {
					case "logins":
					case "reportview":
						clearTable("T1");
						formPageReset();
						break;
				}
				break;
		}		
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_datefrom":
			case "df_dateto":
			case "df_branchcode":
			case "df_document":
			case "df_userid":
			case "df_u_menuid":
			case "df_logins":
			case "df_reportview":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("datefrom");
			inputs.push("dateto");
			inputs.push("branchcode");
			inputs.push("document");
			inputs.push("userid");
			inputs.push("u_menuid");
			inputs.push("logins");
			inputs.push("reportview");
		return inputs;
	}
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "datefrom":
			case "dateto":
			case "document":
			case "userid":
			case "u_menuid":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
			case "branchcode":
			case "userid":
			case "u_menuid":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				}
				break;
		}
	}	
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {
			case "df_u_project":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name from u_projects")); 
				break;
			case "df_u_bdoname":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name from u_bdos")); 
				break;
			case "df_u_sellername":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name from u_sellers")); 
				break;
		}
		return params;
	}

function OpenLnkBtnu_audittrailGPSAuditTrail(targetObjectId) {
	OpenLnkBtn(1000,600,'./udo.php?&objectcode=u_audittraildetails&keys=' + getTableKey("T1","keys",targetObjectId.substring(14,targetObjectId.length)) + '&targetId=' + targetObjectId ,targetObjectId);
}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	   <td width="128"><label <?php genCaptionHtml($schema["u_mobileno"],"") ?>>Date Modified</label></td>
	  <td align=left>&nbsp;<input type"text" size="15" <?php genInputTextHtml($schema["datefrom"]) ?> />-<input type"text" size="15" <?php genInputTextHtml($schema["dateto"]) ?> /></td>
	  <td width="128" ><label <?php genCaptionHtml($schema["u_menuid"],"") ?>>Menu ID</label></td>
	  <td align=left>&nbsp;<select <?php genSelectHtml($schema["u_menuid"],array("loadudflinktable","u_audittrailsetup:code:name:name<>''",":All")) ?> ></select></td>
	  </tr>
	
	<tr>
	  <td ><label <?php genCaptionHtml($schema["branchcode"]) ?> >Branch</label></td>
	  <td align=left>&nbsp;<select <?php if($_SESSION["branchtype"]=="B") echo "disabled" ?> <?php genSelectHtml($schema["branchcode"],array("loadudflinktable","branches:branchcode:branchname",":All")) ?> ></select></td>
	  <td align=left><label <?php genCaptionHtml($schema["userid"],"") ?>>User ID</label></td>
	  <td align=left>&nbsp;<select <?php genSelectHtml($schema["userid"],array("loadudflinktable","users:userid:username",":All")) ?> ></select></td>
	  </tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["document"]) ?> >Code/Document No.</label></td>
	  <td align=left>&nbsp;<input type"text" <?php genInputTextHtml($schema["document"]) ?> /></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["logins"],1) ?> /><label <?php genCaptionHtml($schema["logins"],"") ?> >User Login/Logout</label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Filter</a></td>
	  <td align=left>&nbsp;</td>
	  <td align=left>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["reportview"],1) ?> /><label <?php genCaptionHtml($schema["reportview"],"") ?> >Viewed Report/s</label></td>
	  </tr>
	
</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
<?php require("./bofrms/ajaxprocess.php"); ?>  
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString()  . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
