<?php
	$progid = "u_listofregisters";

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

	unset($enumdocstatus["D"],$enumdocstatus["CN"]);
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
	
	$page->objectcode = "U_LISTOFREGISTERS";
	$page->paging->formid = "./UDP.php?&objectcode=u_listofregisters";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "List of Registers";
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_cashier"] = createSchemaUpper("u_cashier");
	$schema["u_opendate"] = createSchemaDate("u_opendate");
	$schema["u_closedate"] = createSchemaDate("u_closedate");
	$schema["u_postingdate"] = createSchemaDate("u_postingdate");
        
	$schema["u_cashier"]["attributes"]="disabled";
        
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("code");
        $objGrid->addcolumn("u_userid");
	$objGrid->addcolumn("u_opendate");
	$objGrid->addcolumn("u_closedate");
	$objGrid->addcolumn("u_postingdate");
	$objGrid->addcolumn("u_salesamount");
	$objGrid->addcolumn("u_seqnocount");
	$objGrid->addcolumn("u_status");
	$objGrid->addcolumn("u_isremitted");
        
	$objGrid->columntitle("code","Register Id");
	$objGrid->columntitle("u_userid","Username");
	$objGrid->columntitle("u_opendate","Open Date");
	$objGrid->columntitle("u_closedate","Closed Date");
	$objGrid->columntitle("u_postingdate","Remittance Date");
	$objGrid->columntitle("u_salesamount","Collected Amount");
	$objGrid->columntitle("u_seqnocount","Total Transactions");
	$objGrid->columntitle("u_status","Status");
	$objGrid->columntitle("u_isremitted","Remitted");
        
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("code",25);
	$objGrid->columnwidth("u_userid",15);
	$objGrid->columnwidth("u_opendate",25);
	$objGrid->columnwidth("u_closedate",25);
	$objGrid->columnwidth("u_postingdate",25);
	$objGrid->columnwidth("u_salesamount",15);
	$objGrid->columnwidth("u_seqnocount",15);
	$objGrid->columnwidth("u_status",15);
	$objGrid->columnwidth("u_isremitted",15);
        
	$objGrid->columnalignment("u_dueamount","right");
        
	$objGrid->columnsortable("u_opendate",true);
	$objGrid->columnsortable("u_closedate",true);
	$objGrid->columnsortable("u_postingdate",true);
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
        
	$objGrid->automanagecolumnwidth = false;
        $objGrid->selectionmode = 1;
	
	$objGrid->columnalignment("u_salesamount","right");
	$objGrid->columnalignment("u_seqnocount","right");
        
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_opendate desc";
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
//        function onBeforeDefaultGPSPOS() { 
//            global $objConnection;
//            global $page;
//            $actionReturn = true;
//            
//            $page->setitem("u_cashier",$_SESSION["userid"]);
//            
//            return true;
//        }
		
	require("./inc/formactions.php");
	
	if (!isset($httpVars['df_u_cashier'])) {
		$page->setitem("u_cashier",$_SESSION["userid"]);
	}
       
	
	$filterExp = "";
	
	$filterExp = genSQLFilterString("U_DATE",$filterExp,formatDateToDB($httpVars['df_u_opendate']),null,null,true);
	$filterExp = genSQLFilterString("U_CLOSEDATE",$filterExp,formatDateToDB($httpVars['df_u_closedate']),null,null,true);
	$filterExp = genSQLFilterString("U_POSTINGDATE",$filterExp,formatDateToDB($httpVars['df_u_postingdate']),null,null,true);
	$filterExp = genSQLFilterString("U_STATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterString("U_USERID",$filterExp,$httpVars['df_u_cashier']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
        $objrs->queryopenext("SELECT CODE,U_USERID,CONCAT(U_DATE,' ',U_TIME) AS U_OPENDATE,CONCAT(U_CLOSEDATE,' ',U_CLOSETIME) AS U_CLOSEDATE,U_POSTINGDATE,U_SALESAMOUNT,U_SEQNOCOUNT,U_STATUS,IF(U_ISREMITTED = 1,'Yes','No') as U_ISREMITTED FROM U_LGUPOSREGISTERS WHERE COMPANY = '$company' AND BRANCH = '$branch'  $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        $page->paging_recordcount($objrs->recordcount());
        while ($objrs->queryfetchrow("NAME")) {

                $objGrid->addrow();

                $objGrid->setitem(null,"indicator",$indicator);
                $objGrid->setitem(null,"code",$objrs->fields["CODE"]);
                $objGrid->setitem(null,"u_userid",$objrs->fields["U_USERID"]);
                $objGrid->setitem(null,"u_opendate",$objrs->fields["U_OPENDATE"]);
                $objGrid->setitem(null,"u_closedate",$objrs->fields["U_CLOSEDATE"]);
                $objGrid->setitem(null,"u_postingdate",formatDateToHttp($objrs->fields["U_POSTINGDATE"]));
                $objGrid->setitem(null,"u_salesamount",formatNumericAmount($objrs->fields["U_SALESAMOUNT"]));
                $objGrid->setitem(null,"u_seqnocount",$objrs->fields["U_SEQNOCOUNT"]);
                $objGrid->setitem(null,"u_status",$objrs->fields["U_STATUS"]);
                $objGrid->setitem(null,"u_isremitted",$objrs->fields["U_ISREMITTED"]);
                $objGrid->setkey(null,$objrs->fields["CODE"]); 
                if (!$page->paging_fetch()) break;
        }	
        

	
	resetTabindex();
	setTabindex($schema["u_street"]);
	setTabindex($schema["u_barangay"]);
	setTabindex($schema["u_municipality"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,165,false);
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
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
            focusInput(getInput("u_focusinput"));
            if (getVar("formSubmitAction")=="a") {
                try {
                        if (window.opener.getVar("objectcode")=="U_RPTAXES"){
                            if(window.opener.getInput("u_declaredowner") != ""){
                                setInput("u_duedate","",true);
                                setInput("u_profitcenter","RP",true);
                                setInput("u_custname",window.opener.getInput("u_declaredowner"),true);
                                formSearchNow();
                            }
                        }else{
                            focusInput("u_acctno");
                        }
                } catch ( e ) {
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
				//setKey("keys",getTableInput("T1","docno",p_rowIdx),p_rowIdx);
				if (getTableInput("T1","code",p_rowIdx)!="") {
						setKey("keys",getTableInput("T1","code",p_rowIdx),p_rowIdx);
						return formView(null,"./UDO.php?&objectcode=U_LGUPOSREGISTERS");
                                } else page.statusbar.showWarning("No record for the selected details. This was uploaded as opening record.");
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
	
	function onLnkBtnGetParams(elementId,params) {
		var params = new Array();
		switch (elementId) {
			case "u_hispatients":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
                                
                        case "action":
                                params[""]
                                
                            break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "docstatus": 
			case "u_cashier": 
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
			case "u_opendate": 
			case "u_closedate": 
			case "u_postingdate": 
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
                                         case "checked":
                                            if (row==0) {
                                                if (isTableInputChecked(table,column)) { 
                                                        checkedTableInput(table,column,-1);
                                                } else {
                                                        uncheckedTableInput(table,column,-1); 
                                                }
                                            }	
                                        break;
					case "print":
						setTimeout("OpenReportSelect('printer')",100);

						/*if (row==0) row = undefined;
						OpenPopupViewMap("viewmap","CUSTOMER",getTableInput(table,"street",row),getTableInput(table,"barangay",row),getTableInput(table,"city",row),getTableInput(table,"zip",row),getTableInput(table,"county",row),getTableInput(table,"province",row),getTableInput(table,"country",row),"");
						*/
						break;
					case "action":
                                                setInput("u_billtopay",getTableInput("T1","docno",row));
                                                OpenPopup(1024,600,"./udp.php?&objectcode=u_epayment&docno="+getTableInput("T1","docno",row)+"","Epayment Multipay");
                                               //setInput("u_bpno",getTableInput("T1","docno",row));
//						setInput("u_apptype","RENEW");
//						setKey("keys","");
//						return formView(null,"<?php echo $page->formid; ?>");
                                                
						break;
				}	
				break;
		}
	}
        
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_u_opendate":
			case "df_u_closedate":
			case "df_u_postingdate":
                            return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
                var inputs = new Array();
                inputs.push("opt");
                inputs.push("u_opendate");
                inputs.push("u_closedate");
                inputs.push("u_postingdate");
                inputs.push("u_cashier");
                inputs.push("docstatus");
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
			case "u_opendate":
			case "u_closedate":
			case "u_postingdate":
			case "u_cashier":
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
        
     
        
//        function LoadCreateEpayment(p_amount, p_tnxid, p_expdate){
//            return u_ajaxxmlcreatepayment("udp.php?&objectcode=u_epayment&amount=" + p_amount + "&tnxid=" + p_tnxid + "&expdate=" + p_expdate);
//        }
	

	
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
<input type="hidden" <?php genInputHiddenDFHtml("u_billtopay") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;List of Registers&nbsp;</td>
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
          <td width="220" ><label <?php genCaptionHtml($schema["u_opendate"],"") ?>>Open Date</label></td>
          <td width="220" ><label <?php genCaptionHtml($schema["u_closedate"],"") ?>>Closed Date</label></td>
          <td width="220"  align=left><label <?php genCaptionHtml($schema["u_postingdate"],"") ?>>Remittance Date</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
          <td align=left><label <?php genCaptionHtml($schema["u_cashier"],"") ?>>Cashier</label></td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_opendate"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_closedate"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_postingdate"]) ?> /></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></select></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_cashier"],array("loadudflinktable","users:userid:username",":[All]")) ?> ></select></td>
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
