<?php
	$progid = "u_pmrlist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");

	$enumkindofproperty = array();
	$enumkindofproperty["L"]="Land";
	$enumkindofproperty["B"]="Building and Other Structure";
	$enumkindofproperty["M"]="Machinery";

	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
	$enumdocstatus["Encoding"]="Encoding";
	$enumdocstatus["Assessed"]="Assessed";
	$enumdocstatus["Recommended"]="Recommended";
	$enumdocstatus["Approved"]="Approved";
//	$enumdocstatus["Cancelled"]="Cancelled";
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_RPLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_pmrlist";
	$page->formid = "./UDO.php?&objectcode=U_PMRAPPS";
	$page->objectname = "Public Market Rental";
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_lastname"] = createSchemaUpper("u_lastname");
	$schema["u_firstname"] = createSchemaUpper("u_firstname");
	$schema["u_middlename"] = createSchemaUpper("u_middlename");
	$schema["u_publicmarket"] = createSchemaUpper("u_publicmarket");
	$schema["u_section"] = createSchemaUpper("u_section");
	$schema["u_stallno"] = createSchemaUpper("u_stallno");
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("indicator");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_appdate");
	$objGrid->addcolumn("u_publicmarket");
	$objGrid->addcolumn("u_section");
	$objGrid->addcolumn("u_stallno");
	$objGrid->addcolumn("u_lastname");
	$objGrid->addcolumn("u_firstname");
	$objGrid->addcolumn("u_middlename");
	$objGrid->addcolumn("u_apprefno");
	$objGrid->addcolumn("docstatus");
	$objGrid->columntitle("indicator","");
	$objGrid->columntitle("docno","Reference No.");
	$objGrid->columntitle("u_appdate","Date");
	$objGrid->columntitle("u_publicmarket","Public Market");
	$objGrid->columntitle("u_section","Section");
	$objGrid->columntitle("u_stallno","Stall No.");
	$objGrid->columntitle("u_lastname","Last Name");
	$objGrid->columntitle("u_firstname","First Name");
	$objGrid->columntitle("u_middlename","Middle Name");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("docno",16);
	$objGrid->columnwidth("u_appdate",10);
	$objGrid->columnwidth("u_publicmarket",30);
	$objGrid->columnwidth("u_section",20);
	$objGrid->columnwidth("u_stallno",10);
	$objGrid->columnwidth("u_lastname",15);
	$objGrid->columnwidth("u_firstname",15);
	$objGrid->columnwidth("u_middlename",15);
	$objGrid->columnwidth("docstatus",12);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_appdate",true);
	$objGrid->columnsortable("u_publicmarket",true);
	$objGrid->columnsortable("u_section",true);
	$objGrid->columnsortable("u_lastname",true);
	$objGrid->columnsortable("u_firstname",true);
	$objGrid->columnsortable("u_middlename",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columnvisibility("u_apprefno",false);
	$objGrid->columnvisibility("indicator",false);
	$objGrid->addcolumn("print");
	$objGrid->columntitle("print","");
	$objGrid->columnalignment("print","center");
	$objGrid->columninput("print","type","link");
	$objGrid->columninput("print","caption","Print");
	$objGrid->columnwidth("print",5);
	$objGrid->columnvisibility("print",false);
	$objGrid->addcolumn("action");
	$objGrid->columntitle("action","");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","");
	$objGrid->columnwidth("action",5);
	$objGrid->columnvisibility("action",false);
	
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "DOCNO";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	function loadenumkindofproperty($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumkindofproperty;
		reset($enumkindofproperty);
		while (list($key, $val) = each($enumkindofproperty)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "indicator":
				switch ($label) {
					case "Expired": $style="background-color:gray";break;
				}
				$label="&nbsp";	
				break;
		}
		
	}
	
	if ($page->getitemstring("opt")=="Print") {
		$page->setitem("docstatus","Printing");
		$schema["docstatus"]["attributes"]="disabled";
		$objGrid->columnvisibility("docstatus",false);
		$objGrid->columnvisibility("print",true);
	
	} else {
		$objGrid->columnvisibility("action",true);
	}

	function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		$keys = explode("`",$page->getkey("keys"));
		$obju_BPLApps = new documentschema_br(null,$objConnections,"u_bplapps"); 
		$objConnection->beginwork();
		if ($obju_BPLApps->getbykey($keys[0])) {
				$obju_BPLApps->docstatus = "Paid";
				$obju_BPLApps->setudfvalue("u_printed",1);
				$obju_BPLApps->setudfvalue("u_printeddatetime",date('Y-m-d h:i:s'));
				$actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
		} else $actionReturn = raiseError("Unable to find Application No. [".$keys[0]."]");
		if ($actionReturn) {
			$objConnection->commit();
		} else {
			$objConnection->rollback();
		}
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
	
	$filterExp = "";
	
	$filterExp = genSQLFilterDate("B.U_APPDATE",$filterExp,$httpVars['df_u_arpno']);
	if ($page->getitemstring("docstatus")=="Expired") {
		$filterExp = genSQLFilterNumeric("A.U_YEAR",$filterExp,date('Y'),null,"<");
	} else {
		$filterExp = genSQLFilterString("B.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	}
	$filterExp = genSQLFilterString("A.U_LASTNAME",$filterExp,$httpVars['df_u_lastname']);
	$filterExp = genSQLFilterString("A.U_FIRSTNAME",$filterExp,$httpVars['df_u_firstname']);
	$filterExp = genSQLFilterString("A.U_MIDDLENAME",$filterExp,$httpVars['df_u_middlename']);
	$filterExp = genSQLFilterString("B.U_PUBLICMARKET",$filterExp,$httpVars['df_u_publicmarket']);
	$filterExp = genSQLFilterString("B.U_SECTION",$filterExp,$httpVars['df_u_section']);
	$filterExp = genSQLFilterString("B.U_STALLNO",$filterExp,$httpVars['df_u_stallno']);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
	if ($page->getitemstring("u_adminname")!="") {
		$search=true;
	}
	//if ($page->getitemstring("u_adminname")!="" || $page->getitemstring("u_street")!="" || $page->getitemstring("u_barangay")!="" || $page->getitemstring("u_municipality")!="" || $page->getitemstring("u_city")!="" || $page->getitemstring("u_province")!="" || $page->getitemstring("u_arpno")!="" || $page->getitemstring("docstatus")!="" || $page->getitemstring("u_pin")!="" || $page->getitemstring("u_ownername")!="" || $page->getitemstring("u_kind")!="") {
	$objrs->queryopenext("select ifnull(B.DOCNO,A.CODE) AS CODE, C.NAME AS U_PUBLICMARKETNAME, D.NAME AS U_SECTIONNAME, B.U_STALLNO, ifnull(B.U_APPDATE,A.U_APPDATE) as U_APPDATE, A.U_LASTNAME, A.U_FIRSTNAME, A.U_MIDDLENAME, ifnull(B.U_YEAR,A.U_YEAR) as U_YEAR, B.DOCSTATUS, A.U_APPREFNO from U_PMRMDS A LEFT JOIN U_PMRAPPS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.U_APPREFNO LEFT JOIN U_PMRPMS C ON C.CODE=B.U_PUBLICMARKET LEFT JOIN U_PMRSECTIONS D ON D.CODE=B.U_SECTION WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			/*if ($search) {
				if ($page->getitemstring("u_adminname")==$objrs->fields["NAME"]) {
					$match=true;
					$matchstatus=$objrs->fields["DOCSTATUS"];
				}
			}*/
			$objGrid->addrow();
			
			//$indicator="";
			//if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
			
			$objGrid->setitem(null,"indicator",$indicator);
			$objGrid->setitem(null,"docno",$objrs->fields["CODE"]);
			$objGrid->setitem(null,"u_appdate",formatDateToHttp($objrs->fields["U_APPDATE"]));
			$objGrid->setitem(null,"u_publicmarket",$objrs->fields["U_PUBLICMARKETNAME"]);
			$objGrid->setitem(null,"u_section",$objrs->fields["U_SECTIONNAME"]);
			$objGrid->setitem(null,"u_stallno",$objrs->fields["U_STALLNO"]);
			$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
			$objGrid->setitem(null,"u_firstname",$objrs->fields["U_FIRSTNAME"]);
			$objGrid->setitem(null,"u_middlename",$objrs->fields["U_MIDDLENAME"]);
			$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
			$objGrid->setitem(null,"u_apprefno",$objrs->fields["U_APPREFNO"]);
			if ($objrs->fields["U_YEAR"]<date('Y')) {
				$objGrid->setitem(null,"docstatus","Expired");
				$objGrid->setitem(null,"action","Renew");
			} else $objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
			$objGrid->setkey(null,$objrs->fields["CODE"]); 
			if (!$page->paging_fetch()) break;
		}	
	//}
	
	resetTabindex();
	setTabindex($schema["u_publicmarket"]);
	setTabindex($schema["u_section"]);
	setTabindex($schema["u_stallno"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,245,false);
	$page->toolbar->setaction("print",false);
	
	$rptcols = 6; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
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
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_street");
	}

	function onFormSubmit(action) {
		if (action=="checkprinted") {
			setKey("keys",getTableInput("T100","docno")+"`"+getTableInput("T100","checkno"));
			hidePopupFrame('popupFrameCheckPrintedStatus');
		}
		return true;
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onReport(p_formattype) {
		if (getTableSelectedRow("T1")==0) {
			page.statusbar.showWarning("No selected Check.");
			return false;
		}
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
		return true;
	}
	
	function onReportGetParams(p_formattype,p_params) {
		var params = new Array();
		if (p_params!=null) params = p_params;
		//params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);

		params["source"] = "aspx";
		params["dbtype"] = "mysql";
		//params["reportaction"] = getInput("reportaction");
		params["action"] = "processReport.aspx"
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
		}	
		params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\u_bplapp.rpt"; 
		params["recordselectionformula"]="{u_bplapps.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}

	function onReportReturn(p_formattype) {
		var row = getTableSelectedRow("T1");
	//	page.statusbar.showWarning("previewed");
		setTableInput("T100","docno",getTableInput("T1","docno",row));
		setTableInput("T100","u_adminname",getTableInput("T1","u_adminname",row));
		setTableInput("T100","u_street",getTableInput("T1","u_street",row));
		setTableInput("T100","u_barangay",getTableInput("T1","u_barangay",row));
		setTableInput("T100","u_municipality",getTableInput("T1","u_municipality",row));
		showPopupFrame('popupFrameCheckPrintedStatus',true);
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				//if (getInput("opt")=="Print") {
				//	setTimeout("OpenReportSelect('printer')",100);
				//} else {
					setKey("keys",getTableInput("T1","docno",p_rowIdx),p_rowIdx);
					return formView(null,"./UDO.php?&objectcode=U_PMRAPPS");
				//}	
				/*if (getTableInput("T1","u_expired",p_rowIdx)=="1") {
					page.statusbar.showError("Patient already expired. Cannot add new registration.");
				} else if (getTableInput("T1","u_active",p_rowIdx)=="0") {
					page.statusbar.showError("Patient record is not active. Cannot add new registration.");
				} else {
					if (getInput("u_trxtype")=="IP") {
						return formView(null,"./UDO.php?&objectcode=U_HISIPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					} else {
						return formView(null,"./UDO.php?&objectcode=U_HISOPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					}	
				}	*/
				//var targetObjectId = 'u_hisips';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "u_hispatients":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "docstatus": 
			case "u_publicmarket": 
			case "u_section": 
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				formPageReset(); 
				clearTable("T1");
				break;
			case "u_lastname": 
			case "u_firstname": 
			case "u_middlename": 
			case "u_stallno": 
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	
	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
					case "print":
						setTimeout("OpenReportSelect('printer')",100);

						/*if (row==0) row = undefined;
						OpenPopupViewMap("viewmap","CUSTOMER",getTableInput(table,"street",row),getTableInput(table,"barangay",row),getTableInput(table,"city",row),getTableInput(table,"zip",row),getTableInput(table,"county",row),getTableInput(table,"province",row),getTableInput(table,"country",row),"");
						*/
						break;
					case "action":
						setInput("u_pmno",getTableInput("T1","docno",row));
						setInput("u_apptype","RENEW");
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
				}	
				break;
		}
	}
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_u_lastname":
			case "df_u_firstname":
			case "df_u_middlename":
			case "df_u_publicmarket":
			case "df_u_section":
			case "df_u_stallno":
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("opt");
			inputs.push("u_lastname");
			inputs.push("u_firstname");
			inputs.push("u_middlename");
			inputs.push("u_publicmarket");
			inputs.push("u_section");
			inputs.push("u_stallno");
			inputs.push("docstatus");
			inputs.push("u_pmno");
			inputs.push("u_apptype");
			
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_lastname":
			case "u_firstname":
			case "u_middlename":
			case "u_publicmarket":
			case "u_section":
			case "u_stallno":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
		}
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
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pmno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Public Market Rental List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:1px solid gray" >
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="350" ><label <?php genCaptionHtml($schema["u_lastname"],"") ?>>Last Name</label></td>
          <td width="350"  align=left><label <?php genCaptionHtml($schema["u_publicmarket"],"") ?>>Public Market</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" <?php genInputTextHtml($schema["u_lastname"]) ?> /></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_publicmarket"],array("loadudflinktable","u_pmrpms:code:name",":[All]"),null,null,null,"width:300px") ?> >
          </select></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:300px") ?> >
          </select></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_firstname"],"") ?>>First Name</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["u_section"],"") ?>>Section</label></td>
          <td  align=left>&nbsp;</td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" <?php genInputTextHtml($schema["u_firstname"]) ?> /></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_section"],array("loadudflinktable","u_pmrsections:code:name",":[All]"),null,null,null,"width:300px") ?> >
          </select></td>
          <td  align=left>&nbsp;</td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_middlename"],"") ?>>Middle Name</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["u_stallno"],"") ?>>Stall No.</label></td>
          <td  align=left>&nbsp;</td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" <?php genInputTextHtml($schema["u_middlename"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" <?php genInputTextHtml($schema["u_stallno"]) ?> /></td>
          <td  align=left>&nbsp;</td>
          </tr>
         <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
          <td align=left></td>
        </tr>
        <tr>
          <td >&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
          <td align=left></td>
          <td align=left></td>
          </tr>
         <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
          <td align=left></td>
        </tr>
    </table>
    </div>
</td>
<td>
</td>
</tr></table>
</td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
<div <?php genPopWinHDivHtml("popupFrameCheckPrintedStatus","Check Printed Status",10,30,560,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["docno"],"T100") ?> >Reference No.</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["docno"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_adminname"],"T100") ?> >Business Name</label></td>
			<td >&nbsp;<input type="text" disabled size="45" <?php genInputTextHtml($schema["u_adminname"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_street"],"T100") ?> >Last Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_street"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_barangay"],"T100") ?> >First Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_barangay"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_municipality"],"T100") ?> >Middle Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_municipality"],"T100") ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="formSubmit('checkprinted');return false;" >Printed</a>&nbsp;<a class="button" href="" onClick="hidePopupFrame('popupFrameCheckPrintedStatus');return false;" >Close</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
