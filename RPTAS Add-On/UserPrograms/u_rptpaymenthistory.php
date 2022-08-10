<?php
	$progid = "u_rptpaymenthistory";

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
	include_once("./boschema/checkprinting.php");

	
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
	
	$page->objectcode = "U_RPTPAYMENTHISTORY";
	$page->paging->formid = "./UDP.php?&objectcode=u_rptpaymenthistory";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "RPT Payment History";
	$page->reportlayouts = true;
	$page->getsettings();
        $page->settings->setschema($boschema_checkprinting);
	

	
	$schema["refno"] = createSchemaUpper("refno");
	$schema["refno2"] = createSchemaUpper("refno2");
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
	$objGrid->addcolumn("u_issuedby");
	$objGrid->addcolumn("u_tdno");
	$objGrid->addcolumn("u_orno");
	$objGrid->addcolumn("u_paiddate");
	$objGrid->addcolumn("u_payyear");
	$objGrid->addcolumn("u_startyear");
	$objGrid->addcolumn("u_startqtr");
	$objGrid->addcolumn("u_endyear");
	$objGrid->addcolumn("u_endqtr");
	$objGrid->addcolumn("u_assvalue");
	$objGrid->addcolumn("u_basic");
	$objGrid->addcolumn("u_basicdisc");
	$objGrid->addcolumn("u_basicpen");
	$objGrid->addcolumn("u_sef");
	$objGrid->addcolumn("u_sefdisc");
	$objGrid->addcolumn("u_sefpen");
	$objGrid->addcolumn("u_totalamount");
	$objGrid->addcolumn("u_iscancelled");
	$objGrid->addcolumn("u_cancelleddate");
	$objGrid->addcolumn("u_cancelledby");
	$objGrid->addcolumn("u_paidby");
        
	$objGrid->columntitle("indicator","*");
	$objGrid->columntitle("u_issuedby","Issued By");
	$objGrid->columntitle("u_tdno","TD No");
	$objGrid->columntitle("u_orno","OR Number");
	$objGrid->columntitle("u_payyear","Pay Year");
	$objGrid->columntitle("u_paiddate","Paid Date");
	$objGrid->columntitle("u_startyear","Start Year");
	$objGrid->columntitle("u_startqtr","Start Qtr");
	$objGrid->columntitle("u_endyear","End Year");
	$objGrid->columntitle("u_endqtr","End Qtr");
	$objGrid->columntitle("u_assvalue","Assessed Value");
	$objGrid->columntitle("u_basic","Basic");
	$objGrid->columntitle("u_basicdisc","Basic Discount");
	$objGrid->columntitle("u_basicpen","Basic Penalty");
	$objGrid->columntitle("u_sef","SEF");
	$objGrid->columntitle("u_sefdisc","SEF Discount");
	$objGrid->columntitle("u_sefpen","SEF Penalty");
	$objGrid->columntitle("u_totalamount","Total Amount");
	$objGrid->columntitle("u_iscancelled","Is Cancelled");
	$objGrid->columntitle("u_cancelleddate","Date Cancelled");
	$objGrid->columntitle("u_cancelledby","Cancelled By");
	$objGrid->columntitle("u_paidby","Paid By");
        
        $objGrid->columnwidth("indicator",1);
        $objGrid->columnwidth("u_issuedby",25);
        $objGrid->columnwidth("u_tdno",15);
	$objGrid->columnwidth("u_orno",10);
	$objGrid->columnwidth("u_payyear",8);
	$objGrid->columnwidth("u_paiddate",12);
	$objGrid->columnwidth("u_startyear",8);
	$objGrid->columnwidth("u_startqtr",8);
	$objGrid->columnwidth("u_endyear",8);
	$objGrid->columnwidth("u_endqtr",8);
	$objGrid->columnwidth("u_assvalue",12);
	$objGrid->columnwidth("u_basic",12);
	$objGrid->columnwidth("u_basicdisc",12);
	$objGrid->columnwidth("u_basicpen",12);
	$objGrid->columnwidth("u_sef",12);
	$objGrid->columnwidth("u_sefdisc",12);
	$objGrid->columnwidth("u_sefpen",12);
	$objGrid->columnwidth("u_totalamount",12);
	$objGrid->columnwidth("u_iscancelled",10);
	$objGrid->columnwidth("u_cancelleddate",15);
	$objGrid->columnwidth("u_cancelledby",40);
	$objGrid->columnwidth("u_paidby",50);
        
        
	$objGrid->columnalignment("indicator","center");
	$objGrid->columnalignment("u_payyear","right");
	$objGrid->columnalignment("u_startyear","right");
	$objGrid->columnalignment("u_endyear","right");
	$objGrid->columnalignment("u_assvalue","right");
	$objGrid->columnalignment("u_basic","right");
	$objGrid->columnalignment("u_basicdisc","right");
	$objGrid->columnalignment("u_basicpen","right");
	$objGrid->columnalignment("u_sef","right");
	$objGrid->columnalignment("u_sefdisc","right");
	$objGrid->columnalignment("u_sefpen","right");
	$objGrid->columnalignment("u_totlamount","right");
        
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
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
                $objrs->queryopenext("SELECT U_TDNO,U_ORNO,U_ISSUEDBY,U_PAYYEAR,U_PAIDDATE,U_STARTYEAR,U_STARTQTR,U_ENDYEAR,U_ENDQTR,U_ASSVALUE,U_BASIC,U_BASICDISC,U_BASICPEN,U_SEF,U_SEFDISC,U_SEFPEN,U_TOTALAMOUNT,U_ISCANCELLED,U_CANCELLEDDATE,U_CANCELLEDBY,U_PAIDBY FROM U_PAYMENTHISTORY WHERE COMPANY = '$company' AND BRANCH = '$branch' AND U_REFNO = '".$page->getvarstring("df_refno2")."' ORDER BY U_PAYYEAR,U_ORNO,U_ISCANCELLED DESC", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGrid->addrow();
			$objGrid->setitem(null,"u_tdno",$objrs->fields["U_TDNO"]);
			$objGrid->setitem(null,"u_orno",$objrs->fields["U_ORNO"]);
			$objGrid->setitem(null,"u_issuedby",$objrs->fields["U_ISSUEDBY"]);
			$objGrid->setitem(null,"u_payyear",$objrs->fields["U_PAYYEAR"]);
			$objGrid->setitem(null,"u_paiddate",formatDateToHttp($objrs->fields["U_PAIDDATE"]));
			$objGrid->setitem(null,"u_startyear",$objrs->fields["U_STARTYEAR"]);
			$objGrid->setitem(null,"u_startqtr",$objrs->fields["U_STARTQTR"]);
			$objGrid->setitem(null,"u_endyear",$objrs->fields["U_ENDYEAR"]);
			$objGrid->setitem(null,"u_endqtr",$objrs->fields["U_ENDQTR"]);
			$objGrid->setitem(null,"u_assvalue",formatNumericAmount($objrs->fields["U_ASSVALUE"]));
			$objGrid->setitem(null,"u_basic",formatNumericAmount($objrs->fields["U_BASIC"]));
			$objGrid->setitem(null,"u_basicdisc",formatNumericAmount($objrs->fields["U_BASICDISC"]));
			$objGrid->setitem(null,"u_basicpen",formatNumericAmount($objrs->fields["U_BASICPEN"]));
			$objGrid->setitem(null,"u_sef",formatNumericAmount($objrs->fields["U_SEF"]));
			$objGrid->setitem(null,"u_sefdisc",formatNumericAmount($objrs->fields["U_SEFDISC"]));
			$objGrid->setitem(null,"u_sefpen",formatNumericAmount($objrs->fields["U_SEFPEN"]));
			$objGrid->setitem(null,"u_totalamount",formatNumericAmount($objrs->fields["U_TOTALAMOUNT"]));
			$objGrid->setitem(null,"u_iscancelled",$objrs->fields["U_ISCANCELLED"]);
			$objGrid->setitem(null,"u_cancelleddate",$objrs->fields["U_CANCELLEDDATE"]);
			$objGrid->setitem(null,"u_cancelledby",$objrs->fields["U_CANCELLEDBY"]);
			$objGrid->setitem(null,"u_paidby",$objrs->fields["U_PAIDBY"]);
                        if($objrs->fields["U_ISCANCELLED"]==1){
                            $indicator="Cancelled";
                        } else if($objrs->fields["U_ISCANCELLED"]==2){
                            $indicator="Taxcredit";
                        } else {
                            $indicator="Active";
                        }
                        $objGrid->setitem(null,"indicator",$indicator);
			if (!$page->paging_fetch()) break;
		}	
	
	resetTabindex();
	setTabindex($schema["u_lastname"]);
	setTabindex($schema["u_firstname"]);
	setTabindex($schema["u_middlename"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,100,false);
	$page->toolbar->setaction("print",false);
	
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
					case "Taxcredit": $style="background-color:#3369FF";break;
					case "Active": $style="background-color:#39383b";break;
//                                        default : $style="background-color:#39383b";break;
				}
                                if($column == 'indicator') $label="&nbsp";
                              
				break;
                                
                        default :
                            switch ($indicator){
                                    case "Cancelled": $style="color:#d13d3d"; break;
                                    case "Taxcredit": $style="color:#3369FF";break;
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
//		if (getTableSelectedRow("T1")==0) {
//			page.statusbar.showWarning("No selected application.");
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
		params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);

		params["source"] = "aspx";
		params["dbtype"] = "mysql";
		//params["reportaction"] = getInput("reportaction");
		//params["action"] = "processReport.aspx"
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			if (autoprint) {
				params["querystring"] += generateQueryString("docno",autoprintdocno);
			} else {
				params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
			}				
		}	
		//params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\mayor_permit.rpt"; 
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
                        return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("refno");
			inputs.push("refno2");
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
<input type="hidden" <?php genInputHiddenDFHtml("refno2") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
            <td class="labelPageHeader" >&nbsp;RPT Payment History&nbsp;</td>
            <td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>"><input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
            <td class="labelPageHeader" align="right" >&nbsp;ID Number: <?php echo $page->getvarstring("df_refno2"); ?>&nbsp;</td>
        </tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<!--<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:0px solid gray" >
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="200"  align=left><label <?php genCaptionHtml($schema["refno"],"") ?>>Reference No</label></td>
          <td ></td>
          </tr>
        <tr>
          <td  align=left>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["refno"]) ?> /></td>
          <td >&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
          </tr>
    </table>
    </div>
</td>
</tr></table>
</td></tr>	-->
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
//	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("U_GenericListToolbar.php");
        include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
<script>
resizeObjects();
</script>
