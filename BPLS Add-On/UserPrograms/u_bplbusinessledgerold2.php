<?php
	$progid = "u_bplbusinessledgerold2";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
        
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
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
        include_once("./sls/enumyear.php");

        unset($enumyear["-"]);
	
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
	
	$page->objectcode = "u_bplbusinessledgerold2";
	$page->paging->formid = "./UDP.php?&objectcode=u_bplbusinessledgerold2";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Old Payment History";
	$page->reportlayouts = true;
	$page->getsettings();
        $page->settings->setschema($boschema_checkprinting);
	

	
	$schema["refno"] = createSchemaUpper("refno");
	$schema["refno2"] = createSchemaUpper("refno2");
	$schema["yr"] = createSchemaUpper("yr");
//	$schema["acctno"] = createSchemaUpper("acctno");
//	$schema["profitcenter"] = createSchemaUpper("profitcenter");
//	$schema["viewby"] = createSchemaUpper("viewby");
		
	$objGrid = new grid("T1",$httpVars);
        
//        $objGrid->addcolumn("action");
//	$objGrid->columntitle("action","*");
//	$objGrid->columnalignment("action","center");
//	$objGrid->columninput("action","type","link");
//	$objGrid->columninput("action","caption","");
//	$objGrid->columnwidth("action",5);
//	$objGrid->columnvisibility("action",false);
        
	$objGrid->addcolumn("indicator");
	$objGrid->addcolumn("u_ordates");
	$objGrid->addcolumn("u_ornos");
	$objGrid->addcolumn("u_feeid");
	$objGrid->addcolumn("u_feedesc");
	$objGrid->addcolumn("u_period");
	$objGrid->addcolumn("u_taxbase");
	$objGrid->addcolumn("u_taxamount");
	$objGrid->addcolumn("u_oramount1");
	$objGrid->addcolumn("u_oramount2");
	$objGrid->addcolumn("u_oramount3");
	$objGrid->addcolumn("u_oramount4");
        
	$objGrid->columntitle("indicator","*");
	$objGrid->columntitle("u_ordates","OR Date\s");
	$objGrid->columntitle("u_ornos","OR Number\s");
	$objGrid->columntitle("u_feeid","Fee Id\Bus. Line");
	$objGrid->columntitle("u_feedesc","Fee\Bus. Line Desc.");
	$objGrid->columntitle("u_taxbase","Tax Base");
	$objGrid->columntitle("u_oramount1","OR Amount 1");
	$objGrid->columntitle("u_oramount2","OR Amount 2");
	$objGrid->columntitle("u_oramount3","OR Amount 3");
	$objGrid->columntitle("u_oramount4","OR Amount 4");
	$objGrid->columntitle("u_taxamount","Tax Amount");
	$objGrid->columntitle("u_period","Period");
        
        $objGrid->columnwidth("indicator",1);
        $objGrid->columnwidth("u_ordates",10);
	$objGrid->columnwidth("u_ornos",10);
	$objGrid->columnwidth("u_feeid",20);
	$objGrid->columnwidth("u_feedesc",30);
	$objGrid->columnwidth("u_taxbase",12);
	$objGrid->columnwidth("u_oramount1",12);
	$objGrid->columnwidth("u_oramount2",12);
	$objGrid->columnwidth("u_oramount3",12);
	$objGrid->columnwidth("u_oramount4",12);
	$objGrid->columnwidth("u_period",10);
        
        
	$objGrid->columnalignment("indicator","center");
	$objGrid->columnalignment("u_payyear","right");
	$objGrid->columnalignment("u_taxbase","right");
	$objGrid->columnalignment("u_oramount1","right");
	$objGrid->columnalignment("u_oramount2","right");
	$objGrid->columnalignment("u_oramount3","right");
	$objGrid->columnalignment("u_oramount4","right");
        
//	$objGrid->columnvisibility("indicator",false);
   
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
	
	$filterExp = genSQLFilterString("U_REFNO",$filterExp,$httpVars['df_refno']);
	$filterExp = genSQLFilterString("A.U_PERIOD",$filterExp,$httpVars['df_yr'],null,null,true);
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
                $objrs->queryopenext("SELECT U_PREVACCTNO,U_ACCTNO,U_BUSINESSLINECODE,U_BUSINESSLINEDESC,U_PERIOD,CONCAT(IF(IFNULL(a.U_ORNO1,'')='','',IF(a.U_ORNO1='//','',CONCAT(a.U_ORNO1,' '))),IF(IFNULL(a.U_ORNO2,'')='','',IF(a.U_ORNO2='//','',CONCAT(a.U_ORNO2,' '))),IF(IFNULL(a.U_ORNO3,'')='','',IF(a.U_ORNO3='//','',CONCAT(a.U_ORNO3,' '))),IF(IFNULL(a.U_ORNO4,'')='','',IF(a.U_ORNO4='//','',CONCAT(a.U_ORNO4,' ')))) AS U_ORNOS, CONCAT(IF(IFNULL(a.U_DATEPAID1,'')='','',IF(a.U_DATEPAID1='/  /','',CONCAT(a.U_DATEPAID1,' '))),IF(IFNULL(a.U_DATEPAID2,'')='','',IF(a.U_DATEPAID2='/  /','',CONCAT(a.U_DATEPAID2,' '))),IF(IFNULL(a.U_DATEPAID3,'')='','',IF(a.U_DATEPAID3='/  /','',CONCAT(a.U_DATEPAID3,' '))),IF(IFNULL(a.U_DATEPAID4,'')='','',IF(a.U_DATEPAID4='/  /','',CONCAT(a.U_DATEPAID4,' ')))) AS U_ORDATES,U_TAXBASE,U_TAXAMOUNT,U_ORAMOUNT1,U_ORAMOUNT2,U_ORAMOUNT3,U_ORAMOUNT4 FROM u_oldpaymenthistory A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND U_ACCTNO = '".$page->getvarstring("df_refno2")."' $filterExp  ORDER BY U_PERIOD DESC, U_BUSINESSLINEDESC", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			
        
			$objGrid->addrow();
			$objGrid->setitem(null,"u_ordates",$objrs->fields["U_ORDATES"]);
			$objGrid->setitem(null,"u_ornos",$objrs->fields["U_ORNOS"]);
			$objGrid->setitem(null,"u_period",$objrs->fields["U_PERIOD"]);
			$objGrid->setitem(null,"u_feeid",$objrs->fields["U_BUSINESSLINECODE"]);
			$objGrid->setitem(null,"u_feedesc",$objrs->fields["U_BUSINESSLINEDESC"]);
			$objGrid->setitem(null,"u_taxbase",formatNumericAmount($objrs->fields["U_TAXBASE"]));
			$objGrid->setitem(null,"u_oramount1",formatNumericAmount($objrs->fields["U_ORAMOUNT1"]));
			$objGrid->setitem(null,"u_oramount2",formatNumericAmount($objrs->fields["U_ORAMOUNT2"]));
			$objGrid->setitem(null,"u_oramount3",formatNumericAmount($objrs->fields["U_ORAMOUNT3"]));
			$objGrid->setitem(null,"u_oramount4",formatNumericAmount($objrs->fields["U_ORAMOUNT4"]));
			$objGrid->setitem(null,"u_taxamount",formatNumericAmount($objrs->fields["U_TAXAMOUNT"]));
                        $objGrid->setitem(null,"indicator",$indicator);
			if (!$page->paging_fetch()) break;
		}	
	
	resetTabindex();
	setTabindex($schema["u_lastname"]);
	setTabindex($schema["u_firstname"]);
	setTabindex($schema["u_middlename"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,120,false);
	
	$rptcols = 6;
        
        function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
            global $page;
            global $objGrid;
            global $indicator;
            
		switch ($column) {
                    
			case "indicator":
                                $indicator = $label;
				switch ($label) {
                                        case "Cancelled": $style="background-color:#d13d3d"; break;
					case "Active": $style="background-color:#39383b";break;
//                                        default : $style="background-color:#39383b";break;
				}
                                if($column == 'indicator') $label="&nbsp";
                              
				break;
                                
                        default :
                            switch ($indicator){
                                    case "Cancelled": $style="color:#d13d3d"; break;
                                    case "Active": $style="color:#39383b";break;
                            }
                            break;

		}
		
	}
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
			if (window.opener.getVar("objectcode")=="U_RPTAXBILL") {
//                            formSearchNow();
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
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
		return true;
	}
	
	function onReportGetParams(p_formattype,p_params) {
		var params = new Array();
                if (p_params!=null) params = p_params;
		params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);
		params["source"] = "aspx";
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
                        params["querystring"] += generateQueryString("u_year",getInput("yr"));
                        params["querystring"] += generateQueryString("u_appno",getInput("refno2"));
		}
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
//				if (getInput("opt")=="Print") {
//					setTimeout("OpenReportSelect('printer')",100);
//				} else {
//					if (getTableInput("T1","u_apprefno",p_rowIdx)!="") {
//						setKey("keys",getTableInput("T1","u_apprefno",p_rowIdx),p_rowIdx);
//						return formView(null,"./UDO.php?&objectcode=U_BPLAPPS");
//					} else page.statusbar.showWarning("No application record for the selected business. This was uploaded as opening record.");
//				}	
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
			case "yr":
				formPageReset(); 
				clearTable("T1");
                                formSearchNow();
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
                        case "refno":   
			case "page_vr_limit":
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
			
			case "df_refno2":
			case "df_refno":
			case "df_acctno":
			case "df_profitcenter":
			case "df_viewby":
			case "df_yr":
                        return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("refno");
			inputs.push("refno2");
			inputs.push("yr");
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
			case "yr":
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
<input type="hidden" <?php genInputHiddenDFHtml("refno2") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Old Payment History&nbsp;</td>
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
          <td width="56" align=left><label <?php genCaptionHtml($schema["yr"],"") ?>>Year</label></td>
          <td >&nbsp;<select <?php genSelectHtml($schema["yr"],array("loadenumyear","",":[Select]")) ?> ></select></td>
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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_GenericListToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
<script>
resizeObjects();
</script>
