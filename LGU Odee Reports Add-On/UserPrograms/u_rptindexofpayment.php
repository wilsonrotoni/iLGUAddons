<?php
	$progid = "u_rptindexofpayment";

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

	$enumdoctype = array();
	$enumdoctype["I"]="Item";
	$enumdoctype["S"]="Service";

//	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
//	$enumdocstatus["Encoding"]="Encoding";
//	$enumdocstatus["Assessed"]="Assessed";
//	$enumdocstatus["Recommended"]="Recommended";
//	$enumdocstatus["Approved"]="Approved";
//	$enumdocstatus["Cancelled"]="Cancelled";
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "u_rptindexofpayment";
	$page->paging->formid = "./UDP.php?&objectcode=u_rptindexofpayment";
	$page->formid = "./UDO.php?&objectcode=u_lgupurchaserequests";
	$page->objectname = "Purchase Request List";
	


	$schema["u_duedatefr"] = createSchemaDate("u_duedatefr");
	$schema["u_duedateto"] = createSchemaDate("u_duedateto");
	$schema["u_bpcode"] = createSchemaUpper("u_bpcode");
	$schema["u_bpname"] = createSchemaUpper("u_bpname");
	
        $schema["u_bpcode"]["cfl"] = "OpenCFLfs()";
	$schema["u_bpname"]["cfl"] = "OpenCFLfs()";
        
	if ($lookupSortBy == "") {
		$lookupSortBy = "DOCNO";
	} else	$lookupSortBy = strtolower($lookupSortBy);
//	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	function loadenumdoctype($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumdoctype;
		reset($enumdoctype);
		while (list($key, $val) = each($enumdoctype)) {
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
	
	$filterExp = genSQLFilterDate("U_DATE",$filterExp,$httpVars['df_u_docdatefr'],$httpVars['df_u_docdateto']);
	$filterExp = genSQLFilterDate("U_DUEDATE",$filterExp,$httpVars['df_u_duedatefr'],$httpVars['df_u_duedateto']);
	$filterExp = genSQLFilterString("DOCNO",$filterExp,$httpVars['df_docno']);
	$filterExp = genSQLFilterString("DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterString("U_PROFITCENTER",$filterExp,$httpVars['df_u_profitcenter']);
	$filterExp = genSQLFilterString("U_PROFITCENTERNAME",$filterExp,$httpVars['df_u_profitcentername']);
	$filterExp = genSQLFilterString("U_DOCTYPE",$filterExp,$httpVars['df_u_doctype']);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
//	if ($filterExp !="") $filterExp = " AND " . $filterExp;
//	$objrs = new recordset(null,$objConnection);
//	$objrs->queryopenext("select * from u_lgupurchaserequests  WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
//		//var_dump($objrs->sqls);
//		$page->paging_recordcount($objrs->recordcount());
//		while ($objrs->queryfetchrow("NAME")) {
//			
//			$objGrid->addrow();
//			$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
//			$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_DATE"]));
//			$objGrid->setitem(null,"u_duedate",formatDateToHttp($objrs->fields["U_DUEDATE"]));
//			$objGrid->setitem(null,"u_profitcenter",$objrs->fields["U_PROFITCENTER"]);
//			$objGrid->setitem(null,"u_projcode",$objrs->fields["U_PROJCODE"]);
//			$objGrid->setitem(null,"u_projname",$objrs->fields["U_PROJNAME"]);
//			$objGrid->setitem(null,"u_profitcentername",$objrs->fields["U_PROFITCENTERNAME"]);
//			$objGrid->setitem(null,"u_totalamount",formatNumericAmount($objrs->fields["U_TOTALAMOUNT"]));
//			$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
//                        if($objrs->fields["U_DOCTYPE"] == "I"){
//                            $objGrid->setitem(null,"u_doctype","Item");
//                        }else if($objrs->fields["U_DOCTYPE"] == "S"){
//                            $objGrid->setitem(null,"u_doctype","Service");
//                        }
//			
//			$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
//			if (!$page->paging_fetch()) break;
//		}	
	//}
	
	resetTabindex();
	
//	$page->resize->addgrid("T1",20,210,false);
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
					return formView(null,"./UDO.php?&objectcode=u_lgupurchaserequests");
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
//				var targetObjectId = 'u_lgupurchaserequests';
//				OpenLnkBtn(1024,570,'./udo.php?objectcode=u_lgupurchaserequests' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}
	function OpenPurchaseForm(p_rowIdx){
//            OpenPopup(1024,600,"./udo.php?&objectcode=u_rpfaas1&sf_keys="+arpno1+"&formAction=e","UpdPays");
            var targetObjectId = getTableKey("T1","keys",getTableSelectedRow("T1"));
            OpenLnkBtn(1064,650,'./udo.php?objectcode=u_lgupurchaserequests' + '' + '&targetId=' + targetObjectId ,targetObjectId);
         }
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "docno":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "docstatus": 
			case "u_doctype": 
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
			case "docno": 
			case "u_profitcenter": 
			case "u_profitcentername":  
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}
        function onElementCFLGetParams(element) {
            var params = new Array();
            switch (element.id) {
                    case "df_u_bpcode":
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno,suppname  from suppliers")); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Supplier Code`Supplier Name")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`70")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
//				params["params"] += "&cflsortby=b.u_unitcode";
                            break;
                    case "df_u_bpname":
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppname,suppno  from suppliers")); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Supplier Name.`Supplier Code")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("70`30")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
//				params["params"] += "&cflsortby=a.u_custno";
                            break;
            }     
            return params;
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
			case "df_u_doctype":
			case "df_u_profitcenter":
			case "df_u_profitcentername":
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
                        inputs.push("u_bpcode");
			inputs.push("u_bpname");
			inputs.push("u_duedatefr");
			inputs.push("u_duedateto");
			inputs.push("u_docdatefr");
			inputs.push("u_docdateto");
			inputs.push("docno");
			inputs.push("u_profitcenter");
			inputs.push("u_profitcentername");
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_doctype":
			case "docno":
			case "u_bpcode":
			case "u_bpname":
			case "docstatus":
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
        
        function PreviewReport(){
//            alert("wilson");
            window.open("http://94.237.70.74/index-of-payment/generated-report/"+getInput("u_bpname")+"/"+formatDateToDB(getInput("u_duedatefr"))+"/"+formatDateToDB(getInput("u_duedateto"))+"","toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=SomeSize,height=SomeSize");
//            OpenPopup(1024,600,"http://94.237.70.74/index-of-payment/generated-report/Meralco/2021-01-01/2021-01-31","UpdPays");
//            var targetObjectId = getTableKey("T1","keys",getTableSelectedRow("T1"));
//            OpenLnkBtn(1064,650,'./udo.php?objectcode=u_lgupurchaserequests' + '' + '&targetId=' + targetObjectId ,targetObjectId);
        }
	

	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<!--<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">-->
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
	  <td class="labelPageHeader" >&nbsp;Index of Payment&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:0px solid gray" >
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr class="fillerRow5px"><td></td></tr>
        
        <tr><td width="100" ><label <?php genCaptionHtml($schema["u_bpcode"],"") ?>>Supplier Code</label></td>
		<td width="400" align=left><input type="text" <?php genInputTextHtml($schema["u_bpcode"]) ?> /></td>
		<td width="1142" ></td>
		<td width="300" align=left>&nbsp;</td>
	</tr>
        <tr><td width="100" ><label <?php genCaptionHtml($schema["u_bpname"],"") ?>>Supplier Name</label></td>
		<td width="300" align=left><input type="text" size ="50" <?php genInputTextHtml($schema["u_bpname"]) ?> /></td>
		<td width="1142" ></td>
		<td width="300" align=left>&nbsp;</td>
	</tr>
        <tr class="fillerRow5px"><td></td></tr>
        <tr><td ><label <?php genCaptionHtml($schema["docno"],"") ?>>Date</label></td>
		<td align=left><input type="text" size="12" <?php genInputTextHtml($schema["u_duedatefr"]) ?> /> &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_duedateto"]) ?> /></td>
		<td  ></td>
		<td  align=left>&nbsp;</td>
	</tr>
        <tr class="fillerRow10px"><td></td></tr>
        <tr class="fillerRow10px"><td></td></tr>
        <tr>    <td></td>
		<td><a class="button" href="" onClick="PreviewReport();return false;">Preview Report</a></td>
		<td></td>
		<td>&nbsp;</td>
	</tr>
        
    </table>
    </div>
</td>
<td>
</td>
</tr></table>
</td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td></td></tr>
<?php // $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
//	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_GenericListToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
