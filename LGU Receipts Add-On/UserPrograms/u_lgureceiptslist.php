<?php
	$progid = "u_lgureceiptslist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/grid.php");
	include_once("../common/classes/recordset.php");
	include_once("./schema/bankdeposits.php");
	include_once("./schema/paymentcheques.php");
	include_once("./classes/payments.php");
	include_once("./classes/paymentcheques.php");
	include_once("./sls/countries.php");
	include_once("./sls/banks.php");
	include_once("./sls/housebankaccounts.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./boschema/checkprinting.php");
	include_once("./utils/businessobjects.php");
	include_once("./sls/enumdocstatus.php");
        
	$enumtypeofvehicle = array();
	$enumtypeofvehicle["Tricycle"]="Tricycle";
	$enumtypeofvehicle["Jeepney"]="Jeepney";
        
	$enumtype = array();
	$enumtype["In Vault"]="In Vault";
	$enumtype["Cashier on Hand"]="Cashier on Hand";
	
	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
	$enumdocstatus["Encoding"]="Encoding";
	$enumdocstatus["Assessed"]="Assessed";
	$enumdocstatus["Recommended"]="Recommended";
	$enumdocstatus["Approved"]="Approved";
	//$enumdocstatus["Cancelled"]="Cancelled";
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "u_lgureceiptlist";
	$page->paging->formid = "./UDP.php?&objectcode=u_lgureceiptslist";
	$page->formid = "./UDO.php?&objectcode=u_lgureceipts";
	$page->objectname = "RECEIPTS";
	$page->reportlayouts = true;
	$page->getsettings();
        $page->settings->setschema($boschema_checkprinting);
	

	$schema["u_vaulttotal"] = createSchemaNumeric("u_vaulttotal");
	$schema["u_cashieronhand"] = createSchemaNumeric("u_cashieronhand");
	$schema["u_datefr"] = createSchemaDate("u_datefr");
	$schema["u_dateto"] = createSchemaDate("u_dateto");
	$schema["u_orfr"] = createSchemaUpper("u_orfr");
	$schema["u_orto"] = createSchemaUpper("u_orto");
	$schema["u_cashier"] = createSchemaUpper("u_cashier");
	$schema["u_receipttype"] = createSchemaUpper("u_receipttype");
	$schema["u_receiptstatus"] = createSchemaUpper("u_receiptstatus");
	
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("u_typeofreceipt");
	$objGrid->addcolumn("u_orfr");
	$objGrid->addcolumn("u_orto");
        $objGrid->addcolumn("u_receiptno");
        $objGrid->addcolumn("u_receiptuse");
	$objGrid->addcolumn("u_available");
	$objGrid->addcolumn("u_issue");
	$objGrid->addcolumn("u_cashier");
        
	$objGrid->columntitle("u_typeofreceipt","Accountable From");
	$objGrid->columntitle("u_orfr","From");
	$objGrid->columntitle("u_orto","To");
	$objGrid->columntitle("u_receiptno","Count");
	$objGrid->columntitle("u_receiptuse","Used");
	$objGrid->columntitle("u_available","Available");
	$objGrid->columntitle("u_issue","Is Issued");
	$objGrid->columntitle("u_cashier","Issued To");
	
	$objGrid->columnwidth("u_typeofreceipt",15);
	$objGrid->columnwidth("u_orfr",16);
	$objGrid->columnwidth("u_orto",16);
	$objGrid->columnwidth("u_receiptno",10);
	$objGrid->columnwidth("u_receiptuse",10);
	$objGrid->columnwidth("u_available",10);
	$objGrid->columnwidth("u_issue",10);
	$objGrid->columnwidth("u_cashier",35);
	
        $schema["u_vaulttotal"]["attributes"]="disabled";
        $schema["u_cashieronhand"]["attributes"]="disabled";
        //$objGrid->columnsortable("u_typeofreceipt",true);
	//$objGrid->columnsortable("u_orfr",true);
	//$objGrid->columnsortable("u_orto",true);
	//objGrid->columnsortable("u_cashier",true);
//	$objGrid->height = 800;
//	$objGrid->width = 300;
	
//	var_dump($lookupSortBy);
	if ($lookupSortBy == "") {
		//$lookupSortBy = "ORDER BY A.U_FORM ASC";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	
	function loadenumtype($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumtype;
		reset($enumtype);
		while (list($key, $val) = each($enumtype)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

//	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
//		switch ($column) {
//			case "indicator":
//				switch ($label) {
//					case "Expired": $style="background-color:gray";break;
//				}
//				$label="&nbsp";	
//				break;
//		}
//		
//	}
//	
//	if ($page->getitemstring("opt")=="Print") {
//		$page->setitem("docstatus","Printing");
//		$schema["docstatus"]["attributes"]="disabled";
//		$objGrid->columnvisibility("docstatus",false);
//		$objGrid->columnvisibility("print",true);
//	
//	} else {
//		$objGrid->columnvisibility("action",true);
//                $objGrid->columnvisibility("action2",true);
//	}

	function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
//		$keys = explode("`",$page->getkey("keys"));
//		$obju_BPLApps = new documentschema_br(null,$objConnections,"u_bplapps"); 
//		$objConnection->beginwork();
//		if ($obju_BPLApps->getbykey($keys[0])) {
//				$obju_BPLApps->docstatus = "Paid";
//				$obju_BPLApps->setudfvalue("u_printed",1);
//				$obju_BPLApps->setudfvalue("u_printeddatetime",date('Y-m-d h:i:s'));
//				$actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
//		} else $actionReturn = raiseError("Unable to find Application No. [".$keys[0]."]");
//		if ($actionReturn) {
//			$objConnection->commit();
//		} else {
//			$objConnection->rollback();
//		}
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
	
	$filterExp = "";
	if ($page->getitemstring("u_orfr")!="" && $page->getitemstring("u_orto") == "") {
            $filterExp = $filterExp . $page->getitemstring("u_orfr") .  " >= U_RECEIPTFRM ";
            $filterExp = $filterExp ."AND ". $page->getitemstring("u_orfr") .  " <= U_RECEIPTTO " ;
        }
	$filterExp = genSQLFilterDate("A.U_PURCHASEDDATE",$filterExp,$httpVars['df_u_datefr'],$httpVars['df_u_dateto']);
	$filterExp = genSQLFilterString("A.U_ISSUEDTO",$filterExp,$httpVars['df_u_cashier'],null,null,true);
	$filterExp = genSQLFilterString("A.U_FORM",$filterExp,$httpVars['df_u_receipttype'],null,null,true);

//	if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
        if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
       
	$objrs = new recordset(null,$objConnection);
//	$objrs->setdebug();
//      var_dump($filterExp);
        if($page->getitemstring("u_receiptstatus") == "In Vault"){
                $objrs->queryopenext("SELECT A.U_PURCHASEDDATE,U_FORM AS FORM,U_RECEIPTFRM AS ORFRM,U_RECEIPTTO AS ORTO,U_NOOFRECEIPT AS CNT,COUNT(B.DOCNO) AS USED,(A.U_NOOFRECEIPT - COUNT(B.DOCNO) ) AS AVAILABLE, IF(U_ISSUEDTO != '', 'Yes','No') AS ISSUED,U_ISSUEDTO
                                        FROM U_LGURECEIPTITEMS A
                                        LEFT JOIN U_LGUPOS B ON B.U_DOCSERIES = U_REFNO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH  AND B.U_STATUS NOT IN ('D')
                                        WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND U_ISSUEDTO = '' $filterExp  GROUP BY A.U_REFNO ", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        }else if($page->getitemstring("u_receiptstatus") == "Cashier on Hand"){
                $objrs->queryopenext("SELECT A.U_PURCHASEDDATE,U_FORM AS FORM,U_RECEIPTFRM AS ORFRM,U_RECEIPTTO AS ORTO,U_NOOFRECEIPT AS CNT,COUNT(B.DOCNO) AS USED,(A.U_NOOFRECEIPT - COUNT(B.DOCNO) ) AS AVAILABLE, IF(U_ISSUEDTO != '', 'Yes','No') AS ISSUED,U_ISSUEDTO
                                        FROM U_LGURECEIPTITEMS A
                                        LEFT JOIN U_LGUPOS B ON B.U_DOCSERIES = U_REFNO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH  AND B.U_STATUS NOT IN ('D')
                                        WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND U_ISSUEDTO != '' $filterExp  GROUP BY A.U_REFNO  ", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        }else{
                $objrs->queryopenext("SELECT A.U_PURCHASEDDATE,U_FORM AS FORM,U_RECEIPTFRM AS ORFRM,U_RECEIPTTO AS ORTO,U_NOOFRECEIPT AS CNT,COUNT(B.DOCNO) AS USED,(A.U_NOOFRECEIPT - COUNT(B.DOCNO) ) AS AVAILABLE, IF(U_ISSUEDTO != '', 'Yes','No') AS ISSUED,U_ISSUEDTO
                                        FROM U_LGURECEIPTITEMS A
                                        LEFT JOIN U_LGUPOS B ON B.U_DOCSERIES = U_REFNO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH  AND B.U_STATUS NOT IN ('D')
                                        WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp  GROUP BY A.U_REFNO ", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
										
        }
        $vaulttotal = 0;
        $onhandtotal = 0;
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGrid->addrow();			
			$objGrid->setitem(null,"u_typeofreceipt",$objrs->fields["FORM"]);
			$objGrid->setitem(null,"u_orfr",$objrs->fields["ORFRM"]);
			$objGrid->setitem(null,"u_orto",$objrs->fields["ORTO"]);
			$objGrid->setitem(null,"u_receiptno",$objrs->fields["CNT"]);
			$objGrid->setitem(null,"u_receiptuse",$objrs->fields["USED"]);
			$objGrid->setitem(null,"u_available",$objrs->fields["AVAILABLE"]);
			$objGrid->setitem(null,"u_issue",$objrs->fields["ISSUED"]);
			$objGrid->setitem(null,"u_cashier",$objrs->fields["U_ISSUEDTO"]);
                        if($objrs->fields["ISSUED"] == "Yes"){
                            $onhandtotal += $objrs->fields["AVAILABLE"];
                        }else{
                            $vaulttotal += $objrs->fields["AVAILABLE"];
                        }
			$objGrid->setkey(null,$objrs->fields["ORFRM"]); 
			if (!$page->paging_fetch()) break;
		}
              
	$page->setitem("u_vaulttotal",$vaulttotal);
	$page->setitem("u_cashieronhand",$onhandtotal);
	
	resetTabindex();
        setTabindex($schema["u_datefr"]);
        setTabindex($schema["u_dateto"]);
        setTabindex($schema["u_orfr"]);
        setTabindex($schema["u_orto"]);
	setTabindex($schema["u_cashier"]);
	setTabindex($schema["u_receipttype"]);
	setTabindex($schema["u_receiptstatus"]);
	
	$page->resize->addgrid("T1",20,285,false);
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
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_street");
		//if (getInput("docstatus")!="") {
			setTimeout("formSearchNow()",60000);
		//}
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
//		if (getTableSelectedRow("T1")==0) {
//			page.statusbar.showWarning("No selected Check.");
//			return false;
//		}
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
//		params["dbtype"] = "mysql";
		//params["reportaction"] = getInput("reportaction");
//		params["action"] = "processReport.aspx"
                if (p_params!=null) params = p_params;
		params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);
//		if (params["querystring"]==undefined) {
//			params["querystring"] = "";
//			params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
//		}	
//		params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\u_bplapp.rpt"; 
//		params["recordselectionformula"]="{u_bplapps.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
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
//					setKey("keys",getTableInput("T1","u_apprefno",p_rowIdx),p_rowIdx);
//					return formView(null,"./UDO.php?&objectcode=U_MTOPAPPS");
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
			case "u_cashier": 
			case "u_receipttype": 
			case "u_receiptstatus": 
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
			case "u_datefr": 
			case "u_dateto": 
			case "u_orto": 
			case "u_orfr": 
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
						break;
					case "action":
						setInput("u_fcno",getTableInput("T1","u_franchiseno",row));
						if (getTableInput("T1","docstatus",row)=="Approved") {
							setInput("u_apptype","UPDATE");
						} else {
							setInput("u_apptype","RENEW");
						}	
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
                                        case "action2":
						setInput("u_fcno",getTableInput("T1","u_franchiseno",row));
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
			case "df_page_vr_limit":
			case "u_datefr": 
			case "u_dateto": 
			case "u_orto": 
			case "u_orfr": 
			case "u_cashier": 
			case "u_receipttype": 
			case "u_receiptstatus": 
                        return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
//			inputs.push("opt");
			inputs.push("u_datefr");
			inputs.push("u_dateto");
			inputs.push("u_orto");
			inputs.push("u_orfr");
			inputs.push("u_cashier");
			inputs.push("u_receipttype");
			inputs.push("u_receiptstatus");
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
//		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
//			page.statusbar.showWarning("Check Printing Status window is still open.");
//			return false;
//		}
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
                        case "u_datefr": 
			case "u_dateto": 
			case "u_orto": 
			case "u_orfr": 
			case "u_cashier": 
			case "u_receipttype": 
			case "u_receiptstatus": 
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
<FORM name="formData" method="post" enctype="multipart-form-data" action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_fcno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Receipts List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
         
	
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
         <tr>
            <td width="168"><label <?php genCaptionHtml($schema["u_orfr"],"") ?>>OR No.</label></td>
            <td >&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_orfr"]) ?> />&nbsp;</td>
        </tr>
        <tr>
            <td width="168"><label <?php genCaptionHtml($schema["u_datefr"],"") ?>>Purchase Date</label></td>
            <td >&nbsp;<input type="text" size="14" <?php genInputTextHtml($schema["u_datefr"]) ?> />&nbsp;<input type="text" size="14" <?php genInputTextHtml($schema["u_dateto"]) ?> /></td>
            <td >&nbsp;</td>
        </tr>
        <tr>
            <td width="168"><label <?php genCaptionHtml($schema["u_cashier"],"") ?>>Cashier Name</label></td>
            <td >&nbsp;<select <?php genSelectHtml($schema["u_cashier"],array("loadudflinktable","users:userid:username",":[All]"),null,null,null,"width:250px") ?> ></select></td>
        </tr>
        <tr>
             <td width="168"><label <?php genCaptionHtml($schema["u_receipttype"],"") ?>>Accountable Form</label></td>
             <td>&nbsp;<select <?php genSelectHtml($schema["u_receipttype"],array("loadudflinktable","u_lguforms:code:name",":[All]"),null,null,null,"width:250px") ?> ></select></td>
             <td width="168"><label <?php genCaptionHtml($schema["u_vaulttotal"],"") ?>>Total in Vault</label></td>
             <td width="168" align="left"><input type="text" size="15" <?php genInputTextHtml($schema["u_vaulttotal"]) ?> /></td>
        </tr>
        <tr>
             <td width="168"><label <?php genCaptionHtml($schema["u_receiptstatus"],"") ?>>Receipt Status</label></td>
             <td>&nbsp;<select <?php genSelectHtml($schema["u_receiptstatus"],array("loadenumtype","",":[All]"),null,null,null,"width:120px") ?> ></td>
             <td><label <?php genCaptionHtml($schema["u_cashieronhand"],"") ?>>Cashier on Hand</label></td>
             <td><input type="text" size="15" <?php genInputTextHtml($schema["u_cashieronhand"]) ?> /></td>
        </tr>
        <tr>
             <td width="168">&nbsp;</td>
            <td >&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
        </tr>
    </table>
    
</td>
<td>
</td>
</tr></table>
</td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<?php $page->resize->addgridobject($objGrid,20,200) ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	

	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("U_GenericListToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
