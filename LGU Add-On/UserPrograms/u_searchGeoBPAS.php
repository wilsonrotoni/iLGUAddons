<?php
	$progid = "u_searchGeoBPAS";

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
	$enumdocstatus["Cancelled"]="Cancelled";
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "u_searchGeoBPAS";
	$page->paging->formid = "./UDP.php?&objectcode=u_searchGeoBPAS";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Building Permit Application";
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_acctno"] = createSchemaUpper("u_acctno");
	$schema["u_duedate"] = createSchemaDate("u_duedate");
	$schema["u_profitcenter"] = createSchemaUpper("u_profitcenter");
	$schema["u_custname"] = createSchemaUpper("u_custname");
	
	$objGrid = new grid("T1",$httpVars);
        $objGrid->addcolumn("u_applicationno");
	$objGrid->addcolumn("u_custname");
	$objGrid->addcolumn("u_feeid");
	$objGrid->addcolumn("u_feedesc");
	$objGrid->addcolumn("u_lgushare");
	$objGrid->addcolumn("u_oboshare");
	$objGrid->addcolumn("u_dpwhshare");
	$objGrid->addcolumn("u_linetotal");
        
	$objGrid->columntitle("u_applicationno","Application No");
	$objGrid->columntitle("u_custname","Owner/Applicant Name");
	$objGrid->columntitle("u_feeid","Fee ID");
	$objGrid->columntitle("u_feedesc","Fee Description");
	$objGrid->columntitle("u_lgushare","LGU (80%)");
	$objGrid->columntitle("u_oboshare","OBO (15%)");
	$objGrid->columntitle("u_dpwhshare","DPWH (5%)");
	$objGrid->columntitle("u_linetotal","Line Total");
        
	$objGrid->columnwidth("u_applicationno",15);
	$objGrid->columnwidth("u_custname",30);
	$objGrid->columnwidth("u_feeid",15);
	$objGrid->columnwidth("u_feedesc",20);
	$objGrid->columnwidth("u_lgushare",12);
	$objGrid->columnwidth("u_oboshare",12);
	$objGrid->columnwidth("u_dpwhshare",12);
	$objGrid->columnwidth("u_linetotal",12);
        
	$objGrid->columnalignment("u_lgushare","right");
	$objGrid->columnalignment("u_oboshare","right");
	$objGrid->columnalignment("u_dpwhshare","right");
	$objGrid->columnalignment("u_linetotal","right");
        
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
        
	$objGrid->automanagecolumnwidth = true;
        $objGrid->selectionmode = 2;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_docdate";
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
	
//	if (!isset($httpVars['df_u_duedate'])) {
//		$page->setitem("u_duedate",getmonthend(dateadd("m",1,currentdateDB())));
//	}
	
	$filterExp = "";
	
//	$filterExp = genSQLFilterDate("A.U_DUEDATE",$filterExp,$httpVars['df_u_duedate'],null,"<=");
	$filterExp = genSQLFilterString("A.ApplicationNumber",$filterExp,$httpVars['df_u_acctno']);
	$filterExp = genSQLFilterString("D.OwnerName",$filterExp,$httpVars['df_u_custname'],null,null,true);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
        $odbccon = @odbc_connect("BPAS","bpas_ro","Qwerty123",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
        if ($page->getitemstring("u_acctno")!="" || $page->getitemstring("u_custname")!="" ) {
                    $odbcres = @odbc_exec($odbccon,"SELECT c.Location,a.ApplicationNumber,b.feeid,b.AssessedFees,
                                                        CASE
                                                        WHEN B.FEEID = '1254' THEN B.AmountDue
                                                        ELSE (b.Amountdue*.8) END as lgushare,
                                                        CASE
                                                        WHEN B.FEEID = '1254' THEN 0
                                                        ELSE (b.Amountdue*.15) END as oboshare,
                                                        CASE
                                                        WHEN B.FEEID = '1254' THEN 0
                                                        ELSE (b.Amountdue*.05) END as dpwhshare,
                                                        b.AmountDue as linetotal,a.OccupantId,a.BldgID,d.OwnerName from dbo.Permit a 
                                                    inner join dbo.AssessDtl b on a.PermitID = b.PermitID 
                                                    inner join dbo.Building c on a.BldgID = c.BldgID
                                                    inner join dbo.Owner d on c.OwnerId = d.Ownerid  $filterExp") or die("<B>Error!</B> Couldn't Run Query2:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                    while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                            for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                               $field_name = odbc_field_name($odbcres, $j);
                              // $this->temp_fieldnames[$j] = $field_name;
                               $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                            }
                            $objGrid->addrow();
                            $objGrid->setitem(null,"u_applicationno",$ar["ApplicationNumber"]);
                            $objGrid->setitem(null,"u_custname",$ar["OwnerName"]);
                            $objGrid->setitem(null,"u_feeid",$ar["feeid"]);
                            $objGrid->setitem(null,"u_feedesc",$ar["AssessedFees"]);
                            $objGrid->setitem(null,"u_lgushare",formatNumericAmount($ar["lgushare"]));
                            $objGrid->setitem(null,"u_oboshare",formatNumericAmount($ar["oboshare"]));
                            $objGrid->setitem(null,"u_dpwhshare",formatNumericAmount($ar["dpwhshare"]));
                            $objGrid->setitem(null,"u_linetotal",formatNumericAmount($ar["linetotal"]));
                    }	
        }

	
	resetTabindex();
	
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
				setInput("u_billtopay",getTableInput("T1","docno",p_rowIdx));
				return formView(null,"./UDO.php?&objectcode=U_LGUPOS");
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
			case "u_profitcenter": 
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
			case "u_duedate": 
			case "u_pin": 
			case "u_ownername": 
			case "u_adminname": 
			case "u_acctno": 
			case "u_barangay": 
			case "u_municipality": 
			case "u_city": 
			case "u_province": 
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
	function setLGUFees() {
                var data = new Array();
                var oboshare=0,dpwhshare=0;
                rc =  getTableRowCount("T1");
                showAjaxProcess();
                window.opener.clearTable("T1",true);
                for (i = 1; i <= rc; i++) {
                    if(getTableInput("T1","rowstat",i) != "X") {
                        if(getTableInput("T1","checked",i)=="1") {
                            result = page.executeFormattedQuery("SELECT B.CODE,B.NAME FROM U_GEODATAFEES A INNER JOIN U_LGUFEES B ON A.U_LGUFEEID = B.CODE WHERE A.CODE = '"+getTableInput("T1","u_feeid",i)+"'");	
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                    for (var iii=0; iii<result.childNodes.length; iii++) {
                                            data["u_itemcode"] = result.childNodes.item(iii).getAttribute("CODE");
                                            data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("NAME");
                                            data["u_quantity"] = 1;
                                            data["u_unitprice"] = getTableInputNumeric("T1","u_lgushare",i);
                                            data["u_linetotal"] = getTableInputNumeric("T1","u_lgushare",i);
                                            oboshare += getTableInputNumeric("T1","u_oboshare",i);
                                            dpwhshare += getTableInputNumeric("T1","u_dpwhshare",i);
                                            window.opener.insertTableRowFromArray("T1",data);
                                    }
                                }
                            }
                         window.opener.setInput("u_custname",getTableInput("T1","u_custname",i));
                         window.opener.setInput("u_bpasappno",getTableInput("T1","u_applicationno",i));
                            
                        }
                    }
                }
                if (oboshare > 0) {
                       result = page.executeFormattedQuery("SELECT B.CODE,B.NAME FROM U_LGUSETUP A INNER JOIN U_LGUFEES B ON A.U_OBOSHAREFEE = B.CODE");	
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                    for (var iii=0; iii<result.childNodes.length; iii++) {
                                            data["u_itemcode"] = result.childNodes.item(iii).getAttribute("CODE");
                                            data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("NAME");
                                            data["u_quantity"] = 1;
                                            data["u_unitprice"] = formatNumericAmount(oboshare);
                                            data["u_linetotal"] = formatNumericAmount(oboshare);
                                            window.opener.insertTableRowFromArray("T1",data);
                                    }
                                }
                            }
                }
                if (dpwhshare > 0) {
                        result = page.executeFormattedQuery("SELECT B.CODE,B.NAME FROM U_LGUSETUP A INNER JOIN U_LGUFEES B ON A.U_DPWHSHAREFEE = B.CODE");	
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                    for (var iii=0; iii<result.childNodes.length; iii++) {
                                            data["u_itemcode"] = result.childNodes.item(iii).getAttribute("CODE");
                                            data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("NAME");
                                            data["u_quantity"] = 1;
                                            data["u_unitprice"] = formatNumericAmount(dpwhshare);
                                            data["u_linetotal"] = formatNumericAmount(dpwhshare);
                                            window.opener.insertTableRowFromArray("T1",data);
                                    }
                                }
                        }
                }
                
            window.close();
            hideAjaxProcess();
            return true;
        }
        
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_u_pin":
			case "df_u_taxclass":
			case "df_u_ownername":
			case "df_u_profitcenter":
			case "df_u_acctno":
			case "df_u_custname":
			case "df_u_barangay":
			case "df_u_municipality":
			case "df_u_city":
			case "df_u_province":
			case "df_u_duedate":
                            return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
                        var inputs = new Array();
			inputs.push("opt");
			inputs.push("u_duedate");
			inputs.push("u_acctno");
			inputs.push("u_ownername");
			inputs.push("u_adminname");
			inputs.push("u_custname");
			inputs.push("u_barangay");
			inputs.push("u_municipality");
			inputs.push("u_city");
			inputs.push("u_province");
			inputs.push("u_billtopay");
			inputs.push("docstatus");
			inputs.push("u_profitcenter");
			
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
			case "u_duedate":
			case "u_pin":
			case "u_ownername":
			case "u_adminname":
			case "u_acctno":
			case "u_custname":
			case "u_barangay":
			case "u_municipality":
			case "u_city":
			case "u_province":
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
	  <td class="labelPageHeader" >&nbsp;GeoData Building Permit Application System&nbsp;</td>
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
          <td width="220" ><label <?php genCaptionHtml($schema["u_duedate"],"") ?>>Due As of</label></td>
          <td width="250" ><label <?php genCaptionHtml($schema["u_acctno"],"") ?>>Application No</label></td>
          <td width="350"  align=left><label <?php genCaptionHtml($schema["u_custname"],"") ?>>Owner/Applicant Name</label></td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_duedate"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_acctno"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="35" <?php genInputTextHtml($schema["u_custname"]) ?> /></td>
          </tr>
       <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
          <td align=left></td>
        </tr>
        <tr>
          <td >&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" href="" onClick="setLGUFees();return false;">Confirm</a></td>
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
