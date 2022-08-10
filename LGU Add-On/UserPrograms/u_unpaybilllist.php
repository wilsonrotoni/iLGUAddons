<?php
	$progid = "u_unpaybilllist";

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
	$onhold=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_UNPAYBILLLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_unpaybilllist";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Business Permit Application";
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_acctno"] = createSchemaUpper("u_acctno");
	$schema["u_duedate"] = createSchemaDate("u_duedate");
	$schema["u_profitcenter"] = createSchemaUpper("u_profitcenter");
	$schema["u_custname"] = createSchemaUpper("u_custname");
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("docno");
        $objGrid->addcolumn("u_custno");
	$objGrid->addcolumn("u_custname");
	$objGrid->addcolumn("u_dueamount");
	$objGrid->addcolumn("u_remarks");
	$objGrid->addcolumn("u_appno");
	$objGrid->addcolumn("u_docdate");
	$objGrid->addcolumn("u_duedate");
	$objGrid->addcolumn("u_profitcenter");
        
	$objGrid->columntitle("docno","Bill No.");
	$objGrid->columntitle("u_appno","Reference No");
	$objGrid->columntitle("u_custno","Account No");
	$objGrid->columntitle("u_custname","Business/Customer Name");
	$objGrid->columntitle("u_docdate","Bill Date");
	$objGrid->columntitle("u_duedate","Due Date");
	$objGrid->columntitle("u_dueamount","Due Amount");
	$objGrid->columntitle("u_remarks","Remarks");
	$objGrid->columntitle("u_profitcenter","Profit Center");
        
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_appno",15);
	$objGrid->columnwidth("u_custno",10);
	$objGrid->columnwidth("u_custname",40);
	$objGrid->columnwidth("u_docdate",12);
	$objGrid->columnwidth("u_duedate",12);
	$objGrid->columnwidth("u_remarks",40);
	$objGrid->columnwidth("u_dueamount",12);
	$objGrid->columnwidth("u_profitcenter",10);
        
	$objGrid->columnalignment("u_dueamount","right");
        
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_appno",true);
	$objGrid->columnsortable("u_custno",true);
	$objGrid->columnsortable("u_custname",true);
	$objGrid->columnsortable("u_docdate",true);
	$objGrid->columnsortable("u_duedate",true);
	$objGrid->columnsortable("u_profitcenter",true);
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
        
        $objGrid->addcolumn("action");
	$objGrid->columntitle("action","");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","[Pay Online]");
	$objGrid->columnwidth("action",12);
	$objGrid->columnvisibility("action",false);
        
	$objGrid->automanagecolumnwidth = false;
        $objGrid->selectionmode = 2;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_payqtr asc,u_docdate";
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
	
	$filterExp = genSQLFilterDate("A.U_DUEDATE",$filterExp,$httpVars['df_u_duedate'],null,"<=");
	$filterExp = genSQLFilterString("A.U_CUSTNO",$filterExp,$httpVars['df_u_acctno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_CUSTNAME",$filterExp,$httpVars['df_u_custname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_PROFITCENTER",$filterExp,$httpVars['df_u_profitcenter']);
	$docdate = '';
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	if ($page->getitemstring("u_acctno")!="" || $page->getitemstring("u_custname")!="" ) {
	        $objrs->queryopenext("SELECT A.U_PROFITCENTER,IF(A.U_PROFITCENTER = 'BPL',C.U_ONHOLD,0) AS U_HOLD, A.U_PAYMODE,A.U_PAYQTR,A.DOCNO, A.U_APPNO,A.U_CUSTNO, A.U_CUSTNAME, A.U_DOCDATE, A.U_DUEDATE, A.U_REMARKS, B.NAME AS U_PROFITCENTERNAME, A.U_DUEAMOUNT FROM U_LGUBILLS A INNER JOIN U_BPLTAXBILL D ON A.COMPANY = D.COMPANY AND A.BRANCH = D.BRANCH AND A.U_APPNO = D.DOCNO  LEFT JOIN U_BPLAPPS C ON A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH AND D.U_PREVAPPNO = C.DOCNO INNER JOIN U_LGUPROFITCENTERS B ON B.CODE=A.U_PROFITCENTER WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_DUEAMOUNT>0 AND A.DOCSTATUS='O' AND A.U_MODULE = 'Business Permit Tax Bill' $filterExp
                                            UNION ALL
                                            SELECT A.U_PROFITCENTER,IF(A.U_PROFITCENTER = 'BPL',C.U_ONHOLD,0) AS U_HOLD, A.U_PAYMODE,A.U_PAYQTR,A.DOCNO, A.U_APPNO,A.U_CUSTNO, A.U_CUSTNAME, A.U_DOCDATE, A.U_DUEDATE, A.U_REMARKS, B.NAME AS U_PROFITCENTERNAME, A.U_DUEAMOUNT FROM U_LGUBILLS A INNER JOIN U_BPLAPPS C ON A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH AND A.U_APPNO = C.DOCNO INNER JOIN U_LGUPROFITCENTERS B ON B.CODE=A.U_PROFITCENTER WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_DUEAMOUNT>0 AND A.DOCSTATUS='O' AND A.U_MODULE = 'Business Permit' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			
                        if($objrs->fields["U_PROFITCENTER"] == 'BPL' && $objrs->fields["U_HOLD"] == 1 ) {
                            $onhold = true;
                            break;
                        }
                        
			$objGrid->addrow();
			if($objrs->fields["U_PAYMODE"] == "A") $docdate = '2021-01-01';
                        else if($objrs->fields["U_PAYMODE"] == "S" && $objrs->fields["U_PAYQTR"] == "2" ) $docdate = '2021-01-01';
                        else if($objrs->fields["U_PAYMODE"] == "Q" && $objrs->fields["U_PAYQTR"] == "1") $docdate = '2021-01-01';
                        else $docdate = $objrs->fields["U_DOCDATE"];
                        
                        if (currentdateDB() >= $docdate) $objGrid->setitem(null,"checked",1);
                        else $objGrid->setitem(null,"checked",0);
                        
			$objGrid->setitem(null,"indicator",$indicator);
			$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
			$objGrid->setitem(null,"u_appno",$objrs->fields["U_APPNO"]);
			$objGrid->setitem(null,"u_custno",$objrs->fields["U_CUSTNO"]);
			$objGrid->setitem(null,"u_custname",$objrs->fields["U_CUSTNAME"]);
			$objGrid->setitem(null,"u_docdate",formatDateToHttp($objrs->fields["U_DOCDATE"]));
			$objGrid->setitem(null,"u_duedate",formatDateToHttp($objrs->fields["U_DUEDATE"]));
			$objGrid->setitem(null,"u_remarks",$objrs->fields["U_REMARKS"]);
			$objGrid->setitem(null,"u_profitcenter",$objrs->fields["U_PROFITCENTERNAME"]);
			$objGrid->setitem(null,"u_dueamount",formatNumericAmount($objrs->fields["U_DUEAMOUNT"]));
			$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
			if (!$page->paging_fetch()) break;
		}	
        }

	
	resetTabindex();
	setTabindex($schema["u_street"]);
	setTabindex($schema["u_barangay"]);
	setTabindex($schema["u_municipality"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",35,185,false);
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
                        if (window.opener.getVar("objectcode")=="u_lgupos"){
                                setInput("u_acctno",window.opener.getInput("u_custno"),true);
                                formSearchNow();
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
	function setBillNosLGUPOS() {
                var data = new Array();
                var billno="";
                rc =  getTableRowCount("T1");
                for (i = 1; i <= rc; i++) {
                    if(getTableInput("T1","rowstat",i) != "X") {
                        if(getTableInput("T1","checked",i)=="1") {
                            if (billno!="") billno += ", ";
                            billno += "'" + (getTableInput("T1","docno",i) + "'");
                            
                        }
                    }
                }
                if (getInput("u_billtopay")!="") {
                        if (billno!="") billno = ", " + billno;
                        billno = getInput("u_billtopay") + billno;
                }
                showAjaxProcess();
                window.opener.clearTable("T1",true);
                window.opener.clearTable("T6",true);
                if (billno!="") {
                        result = page.executeFormattedQuery("SELECT A.DOCNO,A.U_REMARKS,A.U_DUEAMOUNT from U_LGUBILLS A where A.DOCSTATUS NOT IN('CN') AND A.COMPANY='"+getGlobal("company")+"' AND A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO in ("+billno+")");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (parseFloat(result.childNodes.item(0).getAttribute("u_dueamount"))==0) {
                                        page.statusbar.showError("Bill already settled.");	
                                        return false;
                                }
                                for (var iii=0; iii<result.childNodes.length; iii++) {
                                        data["u_billno"] = result.childNodes.item(iii).getAttribute("docno");
                                        data["u_remarks"] = result.childNodes.item(iii).getAttribute("u_remarks");
                                        data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_dueamount"));
                                         window.opener.insertTableRowFromArray("T6",data);
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
	  <td class="labelPageHeader" >&nbsp;List of Unpaid Bills - Business&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="80%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
<div style="border:1px solid gray" >
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="220" ><label <?php genCaptionHtml($schema["u_duedate"],"") ?>>Due As of</label></td>
          <td width="250" ><label <?php genCaptionHtml($schema["u_acctno"],"") ?>>Account No</label></td>
          <td width="350"  align=left><label <?php genCaptionHtml($schema["u_custname"],"") ?>>Business/Customer Name</label></td>
          <td  align=left width="350"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Profit Center</label></td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_duedate"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_acctno"]) ?> /></td>
          <td  align=left>&nbsp;<input type="text" size="35" <?php genInputTextHtml($schema["u_custname"]) ?> /></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_profitcenter"],array("loadudflinktable","u_lguprofitcenters:code:name",":[All]"),null,null,null,"width:100px") ?> ></select></td>
          </tr>
       <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
          <td align=left></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" href="" onClick="setBillNosLGUPOS();return false;">Confirm</a> <?php if ($onhold) { ?>&nbsp;&nbsp; <label class="lblobjs" style="color:red"><b>Business is currently On-Hold.</b></label> <?php } ?></td>
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
