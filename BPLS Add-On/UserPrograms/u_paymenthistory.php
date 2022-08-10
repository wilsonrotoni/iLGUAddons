<?php
	$progid = "u_paymenthistory";

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

	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	$retired = false;
	$page->restoreSavedValues();	

	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
	if ($page->getitemstring("opt")!="Print") {
		$enumdocstatus["Encoding"]="Encoding";
		//$enumdocstatus["Assessing"]="Assessing";
		//$enumdocstatus["Assessed"]="Assessed";
		$enumdocstatus["Approved"]="Approved";
		$enumdocstatus["Disapproved"]="Disapproved";
		$enumdocstatus["Expired"]="Expired";
                $enumdocstatus["Retired"]="Retired";
	}	
	$enumdocstatus["Printing"]="Printing";
	$enumdocstatus["Paid"]="Paid";
        
        $enumviewby = array();
	$enumviewby["Acctno"]=" By Account No";
	$enumviewby["Applications"]="By Application";
	
	$page->objectcode = "U_PAYMENTHISTORY";
	$page->paging->formid = "./UDP.php?&objectcode=u_paymenthistory";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Payment History";
	

	
	$schema["docno"] = createSchemaUpper("docno");
	$schema["acctno"] = createSchemaUpper("acctno");
	$schema["profitcenter"] = createSchemaUpper("profitcenter");
	$schema["viewby"] = createSchemaUpper("viewby");
		
	$objGrid = new grid("T1",$httpVars);
        
//        $objGrid->addcolumn("action");
//	$objGrid->columntitle("action","*");
//	$objGrid->columnalignment("action","center");
//	$objGrid->columninput("action","type","link");
//	$objGrid->columninput("action","caption","");
//	$objGrid->columnwidth("action",5);
//	$objGrid->columnvisibility("action",false);
        
	$objGrid->addcolumn("docstatus");
	$objGrid->addcolumn("u_appno");
	$objGrid->addcolumn("u_custno");
	$objGrid->addcolumn("u_billdate");
	$objGrid->addcolumn("u_custname");
	$objGrid->addcolumn("u_remarks");
	$objGrid->addcolumn("u_dueamount");
	$objGrid->addcolumn("u_orno");
	$objGrid->addcolumn("u_paiddate");
	$objGrid->addcolumn("u_paidamount");
	$objGrid->addcolumn("u_paidby");
        
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columntitle("u_appno","Reference No.");
	$objGrid->columntitle("u_custno","Account No.");
	$objGrid->columntitle("u_billdate","Bill Date");
	$objGrid->columntitle("u_custname","Customer Name");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("u_dueamount","Amount Due");
	$objGrid->columntitle("u_orno","OR Number");
	$objGrid->columntitle("u_paiddate","Date Paid");
	$objGrid->columntitle("u_paidamount","Amount Paid");
	$objGrid->columntitle("u_paidby","Paid By");
        
	$objGrid->columnwidth("docstatus",12);
	$objGrid->columnwidth("u_appno",14);
	$objGrid->columnwidth("u_custno",14);
	$objGrid->columnwidth("u_billdate",12);
	$objGrid->columnwidth("u_custname",30);
	$objGrid->columnwidth("u_remarks",30);
	$objGrid->columnwidth("u_dueamount",12);
	$objGrid->columnwidth("u_orno",14);
	$objGrid->columnwidth("u_paiddate",12);
	$objGrid->columnwidth("u_paidamount",12);
	$objGrid->columnwidth("u_paidby",30);
        
        
	$objGrid->columnalignment("u_paidamount","right");
	$objGrid->columnalignment("u_dueamount","right");
   
	$objGrid->automanagecolumnwidth = false;
	
        function loadenumsearchby($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumviewby;
		reset($enumviewby);
		while (list($key, $val) = each($enumviewby)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
        
	if ($lookupSortBy == "") {
		$lookupSortBy = "";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "indicator":
				switch ($label) {
                                        case "Expired": $style="background-color:red"; break;
				}
				$label="&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";	
				break;
		}
		
	}
	
	if ($page->getitemstring("opt")=="Print") {
		if (!isset($httpVars["df_docstatus"])) {
			$page->setitem("docstatus","Printing");
		}
		//$schema["docstatus"]["attributes"]="disabled";
		$objGrid->columnvisibility("docstatus",false);
		$objGrid->columnvisibility("print",true);
	
	} else {
		$objGrid->columnvisibility("action",true);
                $objGrid->columnvisibility("action2",true);
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
	
	if($page->getitemstring("viewby")=="Acctno" || $page->getitemstring("viewby")=="") $filterExp = genSQLFilterString("A.U_CUSTNO",$filterExp,$httpVars['df_acctno']);
        else  $filterExp = genSQLFilterString("A.U_APPNO",$filterExp,$httpVars['df_docno']);
	$filterExp = genSQLFilterString("A.U_PROFITCENTER",$filterExp,$httpVars['df_profitcenter']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
//        var_dump($filterExp);   
	
        if($page->getitemstring("viewby")=="Acctno" || $page->getitemstring("viewby")==""){
            $objrs->queryopenext("SELECT C.U_STATUS,A.U_APPNO,A.U_CUSTNO,A.U_CUSTNAME,A.U_DOCDATE,A.U_REMARKS,A.U_DUEAMOUNT,C.DOCNO,C.U_DATE AS PAIDDATE,C.U_PAIDAMOUNT,C.U_CUSTNAME AS PAIDBY  FROM U_LGUBILLS A LEFT JOIN U_LGUPOS C ON A.DOCNO = C.U_BILLNO AND A.BRANCH = C.BRANCH AND A.COMPANY = C.COMPANY WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ORDER BY A.U_APPNO", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        }else{
            $objrs->queryopenext("SELECT C.U_STATUS,A.U_APPNO,A.U_CUSTNO,A.U_CUSTNAME,A.U_DOCDATE,A.U_REMARKS,A.U_DUEAMOUNT,C.DOCNO,C.U_DATE AS PAIDDATE,C.U_PAIDAMOUNT,C.U_CUSTNAME AS PAIDBY  FROM U_LGUBILLS A LEFT JOIN U_LGUPOS C ON A.DOCNO = C.U_BILLNO AND A.BRANCH = C.BRANCH AND A.COMPANY = C.COMPANY WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ORDER BY A.U_APPNO", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        }
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGrid->addrow();
                        
			$objGrid->setitem(null,"u_appno",$objrs->fields["U_APPNO"]);
			$objGrid->setitem(null,"u_custno",$objrs->fields["U_CUSTNO"]);
			$objGrid->setitem(null,"u_billdate",formatDateToHttp($objrs->fields["U_DOCDATE"]));
			$objGrid->setitem(null,"u_paiddate",formatDateToHttp($objrs->fields["PAIDDATE"]));
			$objGrid->setitem(null,"u_custname",$objrs->fields["U_CUSTNAME"]);
			$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
			$objGrid->setitem(null,"u_dueamount",$objrs->fields["U_DUEAMOUNT"]);
			$objGrid->setitem(null,"u_paidamount",$objrs->fields["U_PAIDAMOUNT"]);
			$objGrid->setitem(null,"u_orno",$objrs->fields["DOCNO"]);
			$objGrid->setitem(null,"u_paidby",$objrs->fields["PAIDBY"]);
                        
			if ($objrs->fields["U_STATUS"]=="O" || $objrs->fields["U_STATUS"]=="C") {
				$objGrid->setitem(null,"docstatus","Paid");
			} elseif ($objrs->fields["U_STATUS"]=="CN" ) {
				$objGrid->setitem(null,"docstatus","Cancelled");
			}else $objGrid->setitem(null,"docstatus","Un Paid");
                        
			
			if (!$page->paging_fetch()) break;
		}	
	
	resetTabindex();
	setTabindex($schema["u_lastname"]);
	setTabindex($schema["u_firstname"]);
	setTabindex($schema["u_middlename"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,170,false);
	$page->toolbar->setaction("print",false);
	$rptcols = 6; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<!--<link rel="stylesheet" type="text/css" href="css/txtobjs2.css">-->
<!--<link rel="stylesheet" type="text/css" href="css/txtobjs.css">-->
<link rel="stylesheet" type="text/css" href="css/txtobjs2_<?php echo @$_SESSION["theme"] ; ?>.css">
<!--<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">-->
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard2.css">
<link rel="stylesheet" type="text/css" href="css/standard2_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css"> 
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
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
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	var autoprint = false;
	var autoprintdocno = "";
	
	function onPageLoad() {
		focusInput(getInput("u_focusinput"));
            if (getVar("formSubmitAction")=="a")    {
		try {
			if (window.opener.getVar("objectcode")=="U_BPLAPPS") {
                            setInput("acctno",window.opener.getInput("u_appno"),true);
                            setInput("docno",window.opener.getInput("docno"),true);
                            setInput("profitcenter","BPL",true);
                            formSearchNow();
			} 
		} catch (theError) {
		}
            }
        }

	function onFormSubmit(action) {
		if (action=="checkprinted") {
			setKey("keys",getTableInput("T100","docno")+"`"+getTableInput("T100","checkno"));
			hidePopupFrame('popupFrameCheckPrintedStatus');
		}
		return true;
	}

	function onFormSubmitReturn(action,success,error) {
		if (success) {
			try {
				if (window.opener.getVar("objectcode")=="U_LGUPOS") {
					window.close();
				}
			} catch (theError) {
			}
		}	
		return true;
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onReport(p_formattype) {
		if (getTableSelectedRow("T1")==0) {
			page.statusbar.showWarning("No selected application.");
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
			if (autoprint) {
				params["querystring"] += generateQueryString("docno",autoprintdocno);
			} else {
				params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
			}				
		}	
		params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\mayor_permit.rpt"; 
		//params["recordselectionformula"]="{u_bplapps.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}

	function onReportReturn(p_formattype) {
		var row = getTableSelectedRow("T1");
	//	page.statusbar.showWarning("previewed");
		setTableInput("T100","docno",getTableInput("T1","docno",row));
		setTableInput("T100","u_businessname",getTableInput("T1","u_businessname",row));
		setTableInput("T100","u_lastname",getTableInput("T1","u_lastname",row));
		setTableInput("T100","u_firstname",getTableInput("T1","u_firstname",row));
		setTableInput("T100","u_middlename",getTableInput("T1","u_middlename",row));
		showPopupFrame('popupFrameCheckPrintedStatus',true);
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				if (getInput("opt")=="Print") {
					setTimeout("OpenReportSelect('printer')",100);
				} else {
					if (getTableInput("T1","u_apprefno",p_rowIdx)!="") {
						setKey("keys",getTableInput("T1","u_apprefno",p_rowIdx),p_rowIdx);
						return formView(null,"./UDO.php?&objectcode=U_BPLAPPS");
					} else page.statusbar.showWarning("No application record for the selected business. This was uploaded as opening record.");
				}	
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
			case "profitcenter": 
			case "viewby": 
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
			case "u_appdatefr": 
			case "u_appdateto": 
			case "docno": 
				setInput("u_businessname","");
				setInput("u_lastname","");
				setInput("u_firstname","");
				setInput("u_middlename","");
				formPageReset(); 
				clearTable("T1");
				break;	
			case "u_businessname": 
			case "u_lastname": 
			case "u_firstname": 
			case "u_middlename": 
				setInput("docstatus","");
				setInput("u_appdatefr","");
				setInput("u_appdateto","");
				setInput("u_businesskind","");
				setInput("u_taxclass","");
				setInput("u_businesschar","");
				setInput("u_businesscategory","");
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
						setInput("u_bpno",getTableInput("T1","docno",row));
						setInput("u_apptype","RENEW");
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
                                        case "action2":
						setInput("u_bpno",getTableInput("T1","docno",row));
						setInput("u_apptype","RETIRED");
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
                                                
                                        
				}	
				break;
		}
	}
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			
			case "df_docno":
			case "df_acctno":
			case "df_profitcenter":
			case "df_viewby":
                        return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("docno");
			inputs.push("acctno");
			inputs.push("profitcenter");
			inputs.push("viewby");
			
			
		return inputs;
	}
	
	
	function formAddNew() {
		setInput("u_businessname",getInput("u_businessname"));
		setInput("u_apptype","NEW");
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "docno":
			case "acctno":
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
	
	function formSearchBusinessName() {
		if (isInputEmpty("u_businessname")) return false;
		formSearchNow();
	}
	function formSearchOwnerName() {
		//if (isInputEmpty("u_lastname")) return false;
		//if (isInputEmpty("u_firstname")) return false;
		//if (isInputEmpty("u_middlename")) return false;
		formSearchNow();
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_bpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Payment History&nbsp;</td>
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
        
        <tr>
          <td width="200" ><label <?php genCaptionHtml($schema["acctno"],"") ?>>Account No</label></td>
          <td width="200"  align=left><label <?php genCaptionHtml($schema["docno"],"") ?>>Reference No</label></td>
          <td width="200"  align=left><label <?php genCaptionHtml($schema["viewby"],"") ?>>View By</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["profitcenter"],"") ?>>Profit Center</label></td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["acctno"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["docno"]) ?> /></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["viewby"],array("loadenumsearchby","",""),null,null,null,"width:120px") ?> ></select></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["profitcenter"],array("loadudflinktable","u_lguprofitcenters:code:name",":[All]"),null,null,null,"width:120px") ?> ></select></td>
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
    </table>
    </div>
</td>
</tr></table>
</td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<tr class="fillerRow10px"><td></td></tr>
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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
