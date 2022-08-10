<?php
	$progid = "u_rptdnhistory";

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
	
	$page->objectcode = "U_TDNHISTORY";
	$page->paging->formid = "./UDP.php?&objectcode=u_rptdnhistory";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "RP TDN History";
	

	
	

    
	$schema["refno2"] = createSchemaUpper("refno2");
        
        $schema["refno"] = createSchemaUpper("refno");
        $schema["revisionyear"] = createSchemaUpper("revisionyear");
        $schema["tdno"] = createSchemaUpper("tdno");
        $schema["pinno"] = createSchemaUpper("pinno");
        $schema["assvalue"] = createSchemaUpper("assvalue");
        $schema["declaredowner"] = createSchemaUpper("declaredowner");
        $schema["startyear"] = createSchemaUpper("startyear");
        $schema["startqtr"] = createSchemaUpper("startqtr");
        $schema["endyear"] = createSchemaUpper("endyear");
        $schema["endqtr"] = createSchemaUpper("endqtr");
        $schema["recordeddate"] = createSchemaUpper("recordeddate");
                        
        $schema["faasrefno"] = createSchemaUpper("faasrefno");
        $schema["faasrevisionyear"] = createSchemaUpper("faasrevisionyear");
        $schema["faastdno"] = createSchemaUpper("faastdno");
        $schema["faaspinno"] = createSchemaUpper("faaspinno");
        $schema["faasassvalue"] = createSchemaUpper("faasassvalue");
        $schema["faasdeclaredowner"] = createSchemaUpper("faasdeclaredowner");
        $schema["faasstartyear"] = createSchemaUpper("faasstartyear");
        $schema["faasstartqtr"] = createSchemaUpper("faasstartqtr");
        $schema["faasendyear"] = createSchemaUpper("faasendyear");
        $schema["faasendqtr"] = createSchemaUpper("faasendqtr");
        $schema["faasrecordeddate"] = createSchemaUpper("faasrecordeddate");
        
//	$schema["acctno"] = createSchemaUpper("acctno");
//	$schema["profitcenter"] = createSchemaUpper("profitcenter");
//	$schema["viewby"] = createSchemaUpper("viewby");
	$schema["refno"]["attributes"]="disabled";
        $schema["revisionyear"]["attributes"]="disabled";
        $schema["tdno"]["attributes"]="disabled";
        $schema["pinno"]["attributes"]="disabled";
        $schema["assvalue"]["attributes"]="disabled";
        $schema["declaredowner"]["attributes"]="disabled";
        $schema["startyear"]["attributes"]="disabled";
        $schema["startqtr"]["attributes"]="disabled";
        $schema["endyear"]["attributes"]="disabled";
        $schema["endqtr"]["attributes"]="disabled";
        $schema["recordeddate"]["attributes"]="disabled";
        
        
	$objGrid = new grid("T1",$httpVars);
        
//        $objGrid->addcolumn("action");
//	$objGrid->columntitle("action","*");
//	$objGrid->columnalignment("action","center");
//	$objGrid->columninput("action","type","link");
//	$objGrid->columninput("action","caption","");
//	$objGrid->columnwidth("action",5);
//	$objGrid->columnvisibility("action",false);
        
	$objGrid->addcolumn("u_tdno");
	$objGrid->addcolumn("u_revisionyear");
	$objGrid->addcolumn("u_pinno");
	$objGrid->addcolumn("u_assvalue");
	$objGrid->addcolumn("u_declaredowner");
	$objGrid->addcolumn("u_startyear");
	$objGrid->addcolumn("u_startqtr");
	$objGrid->addcolumn("u_endyear");
	$objGrid->addcolumn("u_endqtr");
	$objGrid->addcolumn("u_recordeddate");
        
        
	$objGrid->columntitle("u_tdno","TD No");
	$objGrid->columntitle("u_revisionyear","GR Year");
	$objGrid->columntitle("u_pinno","Pin No");
	$objGrid->columntitle("u_assvalue","Assessed Value");
	$objGrid->columntitle("u_declaredowner","Declared Owner");
	$objGrid->columntitle("u_startyear","Start Year");
	$objGrid->columntitle("u_startqtr","Start Qtr");
	$objGrid->columntitle("u_endyear","End Year");
	$objGrid->columntitle("u_endqtr","End Qtr");
	$objGrid->columntitle("u_recordeddate","Recorded Date");
        
        $objGrid->columnwidth("u_tdno",12);
	$objGrid->columnwidth("u_pinno",25);
	$objGrid->columnwidth("u_revisionyear",8);
	$objGrid->columnwidth("u_recordeddate",12);
	$objGrid->columnwidth("u_startyear",8);
	$objGrid->columnwidth("u_startqtr",8);
	$objGrid->columnwidth("u_endyear",8);
	$objGrid->columnwidth("u_endqtr",8);
	$objGrid->columnwidth("u_assvalue",12);
	$objGrid->columnwidth("u_declaredowner",40);
        
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
                $objrs->queryopenext("SELECT U_REVISIONYEAR,U_TDNO,U_PINNO,U_PREVVALUE,U_DECLAREDOWNER,U_STARTYEAR,U_STARTQTR,U_ENDYEAR,U_ENDQTR,U_RECOREDDATE,U_CANCELLEDDATE,U_CANCELLEDTOTD FROM U_TDNHISTORY WHERE COMPANY = '$company' AND BRANCH = '$branch' AND U_REFNO = '".$page->getvarstring("df_refno2")."' ", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGrid->addrow();
			$objGrid->setitem(null,"u_revisionyear",$objrs->fields["U_REVISIONYEAR"]);
			$objGrid->setitem(null,"u_tdno",$objrs->fields["U_TDNO"]);
			$objGrid->setitem(null,"u_pinno",$objrs->fields["U_PINNO"]);
			$objGrid->setitem(null,"u_assvalue",formatNumericAmount($objrs->fields["U_PREVVALUE"]));
			$objGrid->setitem(null,"u_declaredowner",$objrs->fields["U_DECLAREDOWNER"]);
			$objGrid->setitem(null,"u_startyear",$objrs->fields["U_STARTYEAR"]);
			$objGrid->setitem(null,"u_startqtr",$objrs->fields["U_STARTQTR"]);
			$objGrid->setitem(null,"u_endyear",$objrs->fields["U_ENDYEAR"]);
			$objGrid->setitem(null,"u_endqtr",$objrs->fields["U_ENDQTR"]);
			$objGrid->setitem(null,"u_recordeddate",formatDateToHttp($objrs->fields["U_RECORDEDDATE"]));
                        
                        $objGrid->setitem(null,"indicator",$indicator);
			if (!$page->paging_fetch()) break;
		}	
	
//	resetTabindex();
//	setTabindex($schema["u_lastname"]);
//	setTabindex($schema["u_firstname"]);
//	setTabindex($schema["u_middlename"]);
//	setTabindex($schema["u_birthdate"]);
//	setTabindex($schema["u_patientid"]);		
//	
	$page->resize->addgrid("T1",20,360,false);
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
                           
			} 
		} catch (theError) {
		}
            }
        }

//	function onFormSubmit(action) {
//		if (action=="checkprinted") {
//			setKey("keys",getTableInput("T100","docno")+"`"+getTableInput("T100","checkno"));
//			hidePopupFrame('popupFrameCheckPrintedStatus');
//		}
//		return true;
//	}
//
//	function onFormSubmitReturn(action,success,error) {
//		if (success) {
//			try {
//				if (window.opener.getVar("objectcode")=="U_LGUPOS") {
//					window.close();
//				}
//			} catch (theError) {
//			}
//		}	
//		return true;
//	}
//
//	function formPrintTo(element) {
//		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
//		return false;
//	}
//
//	function onReport(p_formattype) {
//		if (getTableSelectedRow("T1")==0) {
//			page.statusbar.showWarning("No selected application.");
//			return false;
//		}
//		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
//			page.statusbar.showWarning("Check Printing Status window is still open.");
//			return false;
//		}
//		return true;
//	}
//	
//	function onReportGetParams(p_formattype,p_params) {
//		var params = new Array();
//		if (p_params!=null) params = p_params;
//		//params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);
//
//		params["source"] = "aspx";
//		params["dbtype"] = "mysql";
//		//params["reportaction"] = getInput("reportaction");
//		params["action"] = "processReport.aspx"
//		if (params["querystring"]==undefined) {
//			params["querystring"] = "";
//			if (autoprint) {
//				params["querystring"] += generateQueryString("docno",autoprintdocno);
//			} else {
//				params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
//			}				
//		}	
//		params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\mayor_permit.rpt"; 
//		//params["recordselectionformula"]="{u_bplapps.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
//		//page.statusbar.showWarning(params["source"]);
////		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
//		return params;
//	}
//
//	function onReportReturn(p_formattype) {
//		var row = getTableSelectedRow("T1");
//	//	page.statusbar.showWarning("previewed");
//		setTableInput("T100","docno",getTableInput("T1","docno",row));
//		setTableInput("T100","u_businessname",getTableInput("T1","u_businessname",row));
//		setTableInput("T100","u_lastname",getTableInput("T1","u_lastname",row));
//		setTableInput("T100","u_firstname",getTableInput("T1","u_firstname",row));
//		setTableInput("T100","u_middlename",getTableInput("T1","u_middlename",row));
//		showPopupFrame('popupFrameCheckPrintedStatus',true);
//	}
//
        function onSelectRow(p_tableId,p_rowIdx) {
                var params = Array();
                switch (p_tableId) {
                    case "T1":

                        params["focus"] = false;
                    break;
                }
                return params;
        }
	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				if (getInput("opt")=="Print") {
					setTimeout("OpenReportSelect('printer')",100);
				} else {
                                    setInput("tdno",getTableInput("T1","u_tdno",p_rowIdx));
                                    setInput("revisionyear",getTableInput("T1","u_revisionyear",p_rowIdx));
                                    setInput("pinno",getTableInput("T1","u_pinno",p_rowIdx));
                                    setInput("assvalue",getTableInput("T1","u_assvalue",p_rowIdx));
                                    setInput("declaredowner",getTableInput("T1","u_declaredowner",p_rowIdx));
                                    setInput("startyear",getTableInput("T1","u_startyear",p_rowIdx));
                                    setInput("startqtr",getTableInput("T1","u_startqtr",p_rowIdx));
                                    setInput("endyear",getTableInput("T1","u_endyear",p_rowIdx));
                                    setInput("endqtr",getTableInput("T1","u_endqtr",p_rowIdx));
                                    setInput("recordeddate",getTableInput("T1","u_recordeddate",p_rowIdx));
//                                    alert(getTableInput("T1","u_tdno",p_rowIdx));
//					if (getTableInput("T1","u_apprefno",p_rowIdx)!="") {
//						setKey("keys",getTableInput("T1","u_apprefno",p_rowIdx),p_rowIdx);
//						return formView(null,"./UDO.php?&objectcode=U_BPLAPPS");
//					} else page.statusbar.showWarning("No application record for the selected business. This was uploaded as opening record.");
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
//	
//	function onLnkBtnGetParams(elementId) {
//		var params = new Array();
//		switch (elementId) {
//			case "u_hispatients":
//				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
//				break;
//		}
//		return params;
//	}	
//	function onElementChange(element,column,table,row) {
//		switch (column) {
//			case "profitcenter": 
//			case "viewby": 
//				formPageReset(); 
//				clearTable("T1");
//				break;	
//		}
//		return true;
//	}	
//	
//	function onElementValidate(element,column,table,row) {
//		switch (column) {
//                        case "refno":   
//			case "page_vr_limit":
//				formPageReset(); 
//				clearTable("T1");
//				break;
//		}
//		return true;
//	}	
//	
	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
                                    case "u_tdno":
//                                    alert(column);
                                        break;
                                        
//                                    alert(column);
//					case "print":
//						setTimeout("OpenReportSelect('printer')",100);
//
//						/*if (row==0) row = undefined;
//						OpenPopupViewMap("viewmap","CUSTOMER",getTableInput(table,"street",row),getTableInput(table,"barangay",row),getTableInput(table,"city",row),getTableInput(table,"zip",row),getTableInput(table,"county",row),getTableInput(table,"province",row),getTableInput(table,"country",row),"");
//						*/
//						break;
//					case "action":
//						setInput("u_bpno",getTableInput("T1","docno",row));
//						setInput("u_apptype","RENEW");
//						setKey("keys","");
//						return formView(null,"<?php echo $page->formid; ?>");
//						break;
//                                        case "action2":
//						setInput("u_bpno",getTableInput("T1","docno",row));
//						setInput("u_apptype","RETIRED");
//						setKey("keys","");
//						return formView(null,"<?php echo $page->formid; ?>");
//						break;
                                                
                                        
				}	
				break;
		}
	}
//		
//	function onReadOnlyAccess(elementId) {
//		switch (elementId) {
//			
//			case "df_refno2":
//			case "df_refno":
//			case "df_acctno":
//			case "df_profitcenter":
//			case "df_viewby":
//                        return false;
//		}
//		return true;
//	}
//
//	function onPageSaveValues(p_action) {
//		var inputs = new Array();
//			inputs.push("refno");
//			inputs.push("refno2");
//		return inputs;
//	}
//	
//	
//	function formAddNew() {
//		setInput("u_businessname",getInput("u_businessname"));
//		setInput("u_apptype","NEW");
//		return formView(null,"<?php echo $page->formid; ?>");
//	}
//	
//	function formSearchNow() {
//		acceptText();
//		
//		formSearch('','<?php echo $page->paging->formid; ?>');
//	}
//
//	function onElementKeyDown(element,event,column,table,row) {
//		switch (column) {
//			case "docno":
//			case "acctno":
//				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
//				if (sc_press=="ENTER") {
//					formSearchNow();	
//				} else if (sc_press=="UP" || sc_press=="DN") {
//					var rc=getTableSelectedRow("T1");
//					selectTableRow("T1",rc+1);
//				}
//				break;
//		}
//	}	
//	
//	function formSearchBusinessName() {
//		if (isInputEmpty("u_businessname")) return false;
//		formSearchNow();
//	}
//	function formSearchOwnerName() {
//		//if (isInputEmpty("u_lastname")) return false;
//		//if (isInputEmpty("u_firstname")) return false;
//		//if (isInputEmpty("u_middlename")) return false;
//		formSearchNow();
//	}
	
	
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
	  <td class="labelPageHeader" >&nbsp;RP TDN History&nbsp;</td>
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
                <td colspan="2"><label class="lblobjs"><b>TDN History</b></label></td>
                <td width="120" ><label class="lblobjs"><b>Compare FAAS Record</b></label></td>
		<td width="168" align=left></td>
            </tr>
            <tr>
		  <td width="100"><label <?php genCaptionHtml($schema["refno"],"") ?>>Reference No</label></td>
		  <td width="168">&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["refno"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasrefno"],"") ?>>Reference No</label></td>
                  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["faasrefno"]) ?> /></td>
            </tr>
            <tr>
		  <td ><label <?php genCaptionHtml($schema["revisionyear"],"") ?>>Revision Year</label></td>
		  <td >&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["revisionyear"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasrevisionyear"],"") ?>>Revision Year</label></td>
                  <td align=left>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["faasrevisionyear"]) ?> /></td>
            </tr>
            <tr>
		  <td ><label <?php genCaptionHtml($schema["tdno"],"") ?>>TD No</label></td>
		  <td >&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["tdno"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faastdno"],"") ?>>TD No</label></td>
                  <td align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["faastdno"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["pinno"],"") ?>>PIN No</label></td>
		  <td >&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["pinno"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faaspinno"],"") ?>>PIN No</label></td>
                  <td align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["faaspinno"]) ?> /></td>
            </tr>
            <tr>
		  <td ><label <?php genCaptionHtml($schema["assvalue"],"") ?>>Assessed Value</label></td>
		  <td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["assvalue"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasassvalue"],"") ?>>Assessed Value</label></td>
                  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["faasassvalue"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["declaredowner"],"") ?>>Declared Owner</label></td>
		  <td>&nbsp;<input type="text" size="40" <?php genInputTextHtml($schema["declaredowner"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasdeclaredowner"],"") ?>>Declared Owner</label></td>
                  <td align=left>&nbsp;<input type="text" size="40" <?php genInputTextHtml($schema["faasdeclaredowner"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["startyear"],"") ?>>Start Year</label></td>
		  <td>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["startyear"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasstartyear"],"") ?>>Start Year</label></td>
                  <td align=left>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["faasstartyear"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["startqtr"],"") ?>>Start Quarter</label></td>
		  <td>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["startqtr"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasstartqtr"],"") ?>>Start Quarter</label></td>
                  <td align=left>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["faasstartqtr"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["endyear"],"") ?>>End Year</label></td>
		  <td>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["endyear"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasendyear"],"") ?>>End Year</label></td>
                  <td align=left>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["faasendyear"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["endqtr"],"") ?>>End Quarter</label></td>
		  <td>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["endqtr"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasendqtr"],"") ?>>End Quarter</label></td>
                  <td align=left>&nbsp;<input type="text" size="8" <?php genInputTextHtml($schema["faasendqtr"]) ?> /></td>
            </tr>
            <tr>
		  <td><label <?php genCaptionHtml($schema["recordeddate"],"") ?>>Date Recorded</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["recordeddate"]) ?> /></td>
                  <td><label <?php genCaptionHtml($schema["faasrecordeddate"],"") ?>>Date Recorded</label></td>
                  <td align=left>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["faasrecordeddate"]) ?> /></td>
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
