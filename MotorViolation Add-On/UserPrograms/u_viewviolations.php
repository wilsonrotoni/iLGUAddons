<?php
	$progid = "u_viewviolations";

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

	
	$page->restoreSavedValues();	
	
	 $enumviewby = array();
	$enumviewby["feedesc"]=" By Violation.";
	//$enumviewby["Applications"]="By Application";
	
	
	
	$page->objectcode = "u_viewviolations";
	$page->paging->formid = "./UDP.php?&objectcode=u_viewviolations";
	$page->formid = "./UDO.php?&objectcode=u_motorviolationapps";
	$page->objectname = "History of Payments";
	

	//$schema["code"] = createSchemaUpper("code");
		$schema["refno"] = createSchemaUpper("refno");
	$schema["refno2"] = createSchemaUpper("refno2");
	//$schema["feedesc"] = createSchemaUpper("feedesc");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["u_appdate"] = createSchemaUpper("u_appdate");
	$schema["u_feedesc"] = createSchemaUpper("u_feedesc");
	$schema["u_feecode"] = createSchemaUpper("u_feecode");
	$schema["u_vehicletype"] = createSchemaUpper("u_vehicletype");
	$schema["u_offense"] = createSchemaUpper("u_offense");
	$schema["u_ticketby"] = createSchemaUpper("u_ticketby");
	$schema["u_plateno"] = createSchemaUpper("u_plateno");
	$schema["u_amount"] = createSchemaUpper("u_amount");
	$schema["u_discount"] = createSchemaUpper("u_discount");
	$schema["u_totalamount"] = createSchemaUpper("u_totalamount");
	$schema["u_licenseno"] = createSchemaUpper("u_feecode");
	$schema["u_penaltyamount"] = createSchemaUpper("u_penaltyamount");
	//$schema["u_lastname"] = createSchemaUpper("u_lastname");
	//$schema["u_firstname"] = createSchemaUpper("u_firstname");
	//$schema["u_middlename"] = createSchemaUpper("u_middlename");
	//$schema["u_appdatefr"] = createSchemaDate("u_appdatefr");
	//$schema["u_appdateto"] = createSchemaDate("u_appdateto");
	

	
	$objGrid = new grid("T1",$httpVars);
        
   
        
	//$objGrid->addcolumn("indicator");
	//$objGrid->addcolumn("code");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("docstatus");
	$objGrid->addcolumn("u_appdate");
	$objGrid->addcolumn("u_feedesc");
	//$objGrid->addcolumn("u_feecode");
	//$objGrid->addcolumn("u_businessname");
	$objGrid->addcolumn("u_offense");
	$objGrid->addcolumn("u_vehicletype");
	$objGrid->addcolumn("u_ticketby");
	$objGrid->addcolumn("u_plateno");
	$objGrid->addcolumn("u_amount");
	$objGrid->addcolumn("u_discount");
	$objGrid->addcolumn("u_penaltyamount");
	$objGrid->addcolumn("u_totalamount");
	
	//$objGrid->addcolumn("u_licenseno");
	
        
	//$objGrid->columntitle("indicator","");
		//$objGrid->columntitle("code","Reference No.");
	$objGrid->columntitle("docno","Reference No.");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columntitle("u_appdate","App.Date");
	$objGrid->columntitle("u_feedesc","Violation");
	//$objGrid->columntitle("u_feecode","Fee Code");
	$objGrid->columntitle("u_vehicletype","Vehicle Type");
	$objGrid->columntitle("u_offense","Offense");
	$objGrid->columntitle("u_ticketby","Officer");
	$objGrid->columntitle("u_plateno","Plate No.");
	$objGrid->columntitle("u_amount","Amount");
	$objGrid->columntitle("u_discount","Discount");
	$objGrid->columntitle("u_totalamount","Total");
	$objGrid->columntitle("u_penaltyamount","Penalty");
    
		 
	//$objGrid->columnwidth("indicator",-1);
	//$objGrid->columnwidth("code",30);
	
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("docstatus",8);
	$objGrid->columnwidth("u_appdate",10);
	$objGrid->columnwidth("u_feedesc",30);
	//$objGrid->columnwidth("u_feecode",10);
	$objGrid->columnwidth("u_vehicletype",10);
	$objGrid->columnwidth("u_offense",10);
	$objGrid->columnwidth("u_ticketby",15);
	$objGrid->columnwidth("u_plateno",10);
	$objGrid->columnwidth("u_amount",8);
	$objGrid->columnwidth("u_discount",8);
	$objGrid->columnwidth("u_penaltyamount",8);
	$objGrid->columnwidth("u_totalamount",8);
	
    
	
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columnsortable("u_appdate",true);
	$objGrid->columnsortable("u_feedesc",true);
	//$objGrid->columnsortable("u_feecode",true);
	//$objGrid->columnsortable("u_businessname",true);
	$objGrid->columnsortable("u_vehicletype",true);
	$objGrid->columnsortable("u_offense",true);
	$objGrid->columnsortable("u_ticketby",true);
    $objGrid->columnsortable("u_plateno",true);  
	$objGrid->columnsortable("u_amount",true);	
	$objGrid->columnsortable("u_discount",true);
	$objGrid->columnsortable("u_penaltyamount",true); 
	$objGrid->columnsortable("u_totalamount",true);
   // $objGrid->columnsortable("u_licenseno",true);   
	
	//$objGrid->addcolumn("action2");
	//$objGrid->columntitle("action2","Action");
	//$objGrid->columnalignment("action2","center");
	//$objGrid->columninput("action2","type","link");
	//$objGrid->columninput("action2","caption","");
	//$objGrid->columnwidth("action2",15);
	//$objGrid->columnvisibility("action2",false);
	
	// $objGrid->addcolumn("action");
	//$objGrid->columntitle("action","Action");
	//$objGrid->columnalignment("action","center");
	//$objGrid->columninput("action","type","link");
	//$objGrid->columninput("action","caption","");
	//$objGrid->columnwidth("action",25);
	//$objGrid->columnvisibility("action",false);
        
	//$objGrid->columnlnkbtn("code","OpenLnkBtnu_hispatients()");
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "docno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "indicator":
				switch ($label) {
					case "Expired": $style="background-color:red";break;
				}
				$label="&nbsp";	
				break;
		}
		
	}
	
	if ($page->getitemstring("opt")=="Print") {
		if (!isset($httpVars["df_docstatus"])) {
			$page->setitem("docstatus","Printing");
		}
		//$schema["docstatus"]["attributes"]="disabled";
		//$objGrid->columnvisibility("docstatus",false);
		$objGrid->columnvisibility("print",true);
	
	} else {
		//$objGrid->columnvisibility("action",true);
        //$objGrid->columnvisibility("action2",true);
	}

	function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		$keys = explode("`",$page->getkey("keys"));
		$obju_motorviolationapps = new documentschema_br(null,$objConnections,"u_motorviolationapps"); 
		$objConnection->beginwork();
		if ($obju_motorviolationapps->getbykey($keys[0])) {
				$obju_motorviolationapps->docstatus = "Open";
				$obju_motorviolationapps->setudfvalue("u_printed",1);
				$obju_motorviolationapps->setudfvalue("u_printeddatetime",date('Y-m-d h:i:s'));
				$actionReturn = $obju_motorviolationapps->update($obju_motorviolationapps->docno,$obju_motorviolationapps->rcdversion);
		} else $actionReturn = raiseError("Unable to find Application No. [".$keys[0]."]");
		if ($actionReturn) {
			$objConnection->commit();
		} else {
			$objConnection->rollback();
		}
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
	
	
	
	//$filterExp = genSQLFilterDate("A.U_APPDATE",$filterExp,$httpVars['df_u_appdatefr'],$httpVars['df_u_appdateto']);
	//$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_docno'],null,null,true);
	//$filterExp = genSQLFilterString("A.U_LICENSENO",$filterExp,$httpVars['df_u_licenseno'],null,null,true);
	//$filterExp = genSQLFilterString("B.U_FEEDESC",$filterExp,$httpVars['df_u_feedesc'],null,null,true);
	//$filterExp = genSQLFilterString("A.U_VEHICLETYPE",$filterExp,$httpVars['df_u_vehicletype'],null,null,true);
	//$filterExp = genSQLFilterString("B.U_OFFENSE",$filterExp,$httpVars['df_u_offense'],null,null,true);
	//$filterExp = genSQLFilterString("A.U_TICKETBY",$filterExp,$httpVars['df_u_ticketby'],null,null,true);
	//$filterExp = genSQLFilterString("A.U_PLATENO",$filterExp,$httpVars['df_u_plateno'],null,null,true);
	
	//$filterExp = genSQLFilterString("U_FEEDESC",$filterExp,$httpVars['df_feedesc']);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
	$filterExp = "";
	
	$filterExp = genSQLFilterString("U_REFNO",$filterExp,$httpVars['df_refno']);
	//$filterExp = genSQLFilterString("B.U_FEECODE",$filterExp,$httpVars['df_u_feecode'],null,null,true);
	$filterExp = genSQLFilterString("B.U_FEEDESC",$filterExp,$httpVars['df_u_feedesc'],null,null,true);
	
	//$filterExp = genSQLFilterString("A.U_LICENSENO",$filterExp,$httpVars['df_u_licenseno']);
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	
	$objrs->setdebug();
    //var_dump($filterExp);
	if ($page->getitemstring("DOCNO")!="") {
		$search=true;
	}
	//if ($page->getitemstring("u_businessname")!="" || $page->getitemstring("u_lastname")!="" || $page->getitemstring("u_firstname")!="" || $page->getitemstring("u_middlename")!="" || $page->getitemstring("u_appdate")!="" || $page->getitemstring("docstatus")!="" || $page->getitemstring("u_businesskind")!="" || $page->getitemstring("u_taxclass")!="" || $page->getitemstring("u_businesschar")!="" || $page->getitemstring("u_businesscategory")!="") {
		
		$objrs->queryopenext("SELECT 
		C.DOCSTATUS, A.DOCNO AS DOCNO, A.U_APPDATE AS U_APPDATE,A.U_LICENSENO AS U_LICENSENO,
		B.U_FEEDESC AS U_FEEDESC,A.U_VEHICLETYPE AS U_VEHICLETYPE,
		B.U_OFFENSE AS U_OFFENSE, A.U_TICKETBY AS U_TICKETBY, A.U_PLATENO AS U_PLATENO,
		C.U_TOTALAMOUNT AS U_AMOUNT, B.U_DISCOUNT AS U_DISCOUNT, D.U_TOTALAMOUNT AS U_TOTALAMOUNT,D.U_PENALTYAMOUNT
		FROM U_MOTORVIOLATIONAPPS A 
		INNER JOIN U_MOTORVIOLATIONAPPFEES B ON A.DOCID=B.DOCID AND A.BRANCH=B.BRANCH AND A.COMPANY=B.COMPANY
		INNER JOIN U_LGUBILLS C ON A.U_LICENSENO=C.U_CUSTNO AND B.BRANCH=C.BRANCH AND C.COMPANY=C.COMPANY 
		INNER JOIN U_LGUPOS D ON C.DOCNO=D.U_BILLNO AND C.BRANCH=D.BRANCH AND C.COMPANY=D.COMPANY
		WHERE A.DOCID=B.DOCID AND  A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_LICENSENO = '".$page->getvarstring("df_refno2")."' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
			//var_dump($objrs->sqls);										
	$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			if ($search) {
				if ($page->getitemstring("docno")==$objrs->fields["NAME"]) {
					$match=true;
					//$matchstatus=$objrs->fields["DOCSTATUS"];
				}
			}
			$objGrid->addrow();
			
			//$indicator="";
			//if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
			
			$objGrid->setitem(null,"indicator",$indicator);
			//$objGrid->setitem(null,"code",$objrs->fields["CODE"]);
			$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
			$objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
			$objGrid->setitem(null,"u_appdate",formatDateToHttp($objrs->fields["U_APPDATE"]));
			$objGrid->setitem(null,"u_feedesc",$objrs->fields["U_FEEDESC"]);
			$objGrid->setitem(null,"u_feecode",$objrs->fields["U_FEECODE"]);
			$objGrid->setitem(null,"u_licenseno",$objrs->fields["U_LICENSENO"]);
			$objGrid->setitem(null,"u_vehicletype",$objrs->fields["U_VEHICLETYPE"]);
			$objGrid->setitem(null,"u_offense",$objrs->fields["U_OFFENSE"]);\
			$objGrid->setitem(null,"u_ticketby",$objrs->fields["U_TICKETBY"]);
			$objGrid->setitem(null,"u_plateno",$objrs->fields["U_PLATENO"]);
			$objGrid->setitem(null,"u_amount",formatNumericAmount($objrs->fields["U_AMOUNT"]));
			$objGrid->setitem(null,"u_discount",formatNumericAmount($objrs->fields["U_DISCOUNT"]));
			$objGrid->setitem(null,"u_penaltyamount",formatNumericAmount($objrs->fields["U_PENALTYAMOUNT"]));
			$objGrid->setitem(null,"u_totalamount",formatNumericAmount($objrs->fields["U_TOTALAMOUNT"]));
			

			
            //$objGrid->setitem(null,"action2","ADD VIOLATION");
			//if ($objrs->fields["U_YEAR"]<date('Y')) {
				//$objGrid->setitem(null,"docstatus","Expired");
				$objGrid->setitem(null,"action","Add Violation");
			 ////elseif ($objrs->fields["DOCSTATUS"]=="Paid" && date('m')=="12") {
				if ($objrs->fields["DOCSTATUS"]==('O')) {
				//$objGrid->setitem(null,"docstatus","Expired");
				$objGrid->setitem(null,"docstatus","PENDING");
			}elseif ($objrs->fields["DOCSTATUS"]==('C')) {
				//$objGrid->setitem(null,"docstatus","Expired");
				$objGrid->setitem(null,"docstatus","PAID");
			} 
		
			
			
			//} elseif ($objrs->fields["DOCSTATUS"]=="Paid") {
				//$objGrid->setitem(null,"docstatus","Paid");
				//$objGrid->setitem(null,"action","Update");
			//}else $objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
                        
                       // if ($objrs->fields["U_RETIRED"]== 1) {
                            //$objGrid->setitem(null,"docstatus","Retired");
				//$objGrid->setitem(null,"action","");
                               // $objGrid->setitem(null,"action2","");
                               // $retired = true;
                        //}
			$objGrid->setkey(null,$objrs->fields["docno"]); 
			if (!$page->paging_fetch()) break;
			
		}	
	//}
	
	resetTabindex();
	setTabindex($schema["u_lastname"]);
	setTabindex($schema["u_firstname"]);
	setTabindex($schema["u_middlename"]);
	setTabindex($schema["u_licenseno"]);
	setTabindex($schema["u_appdate"]);	
	setTabindex($schema["u_licenseno"]);
	setTabindex($schema["u_feedesc"]);	
	
	$page->resize->addgrid("T1",20,290,false);
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

	var autoprint = false;
	var autoprintcode = "";
	
	function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_lastname");
		try {
			if (window.opener.getVar("objectcode")=="U_LGUPOS") {
				autoprint = true;
				autoprintcode = window.opener.getPrivate("docno");
				searchTableInput("T1","docno",autoprintcode,true);
				setTimeout("OpenReportSelect('printer')",100);
			} else {
				if (getInput("docstatus")!="") {
					setTimeout("formSearchNow()",60000);
				}
			}
		} catch (theError) {
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
				params["querystring"] += generateQueryString("docno",autoprintcode);
			} else {
				params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
			}				
		}	
		params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\mayor_permit.rpt"; 
		//params["recordselectionformula"]="{u_motorviolators.code} = '"+getTableInput("T1","code",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}

	function onReportReturn(p_formattype) {
		var row = getTableSelectedRow("T1");
	//	page.statusbar.showWarning("previewed");
		setTableInput("T100","docno",getTableInput("T1","docno",row));
		//setTableInput("T100","u_businessname",getTableInput("T1","u_businessname",row));
		setTableInput("T100","u_licenseno",getTableInput("T1","u_licenseno",row));
		setTableInput("T100","u_appdate",getTableInput("T1","u_appdate",row));
		setTableInput("T100","u_feedesc",getTableInput("T1","u_feedesc",row));
		setTableInput("T100","u_feecode",getTableInput("T1","u_feedesc",row));
		setTableInput("T100","u_offense",getTableInput("T1","offense",row));
		setTableInput("T100","u_vehicletype",getTableInput("T1","u_vehicletype",row));
		setTableInput("T100","u_ticketby",getTableInput("T1","u_ticketby",row));
		setTableInput("T100","u_plateno",getTableInput("T1","u_plateno",row));
		showPopupFrame('popupFrameCheckPrintedStatus',true);
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				//if (getInput("opt")=="Print") {
					//setTimeout("OpenReportSelect('printer')",100);
				//} else {
					if (getTableInput("T1","docno",p_rowIdx)!="") {
						setKey("keys",getTableInput("T1","docno",p_rowIdx),p_rowIdx);
						return formView(null,"./UDO.php?&objectcode=u_motorviolationapps");
					} else page.statusbar.showWarning("No application record for the selected business. This was uploaded as opening record.");
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
			//case "docstatus": 
			case "docno": 
			case "u_appdate": 
			case "u_feedesc": 
			case "u_feecode": 
			case "u_offense": 
			case "u_vehicletype": 
			case "u_ticketby":
			case "u_plateno":
			case "u_licenseno":
				setInput("docno","");
				setInput("u_appdate","");
				setInput("u_feedesc","");
				setInput("u_feecode","");
				setInput("u_offense","");
				setInput("u_vehicletype","");
				setInput("u_ticketby","");
				setInput("u_plateno","");
				setInput("u_licenseno","");
				
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
			case "u_appdate": 
			//case "u_appdateto": 
			case "docno":
			case "u_feedesc":
			case "u_feecode":			
			case "u_licenseno":			
				setInput("docno","");
				setInput("u_appdate","");
				setInput("u_feedesc","");
				setInput("u_feecode","");
				setInput("u_offense","");
				setInput("u_vehicletype","");
				setInput("u_ticketby","");
				setInput("u_plateno","");
				setInput("u_licenseno","");
				formPageReset(); 
				clearTable("T1");
				break;	
			case "docno": 
			case "u_appdate": 
			case "u_feedesc":
			case "u_feecode":			
			case "u_offense": 
			case "u_vehicletype": 
			case "u_ticketby":
			case "u_plateno":
			case "u_licenseno":
			
				setInput("docno","");
				setInput("u_appdate","");
				setInput("u_feedesc","");
				setInput("u_feecode","");
				setInput("u_offense","");
				setInput("u_vehicletype","");
				setInput("u_ticketby","");
				setInput("u_plateno","");
				setInput("u_licenseno","");
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
						setInput("docno",getTableInput("T1","docno",row));
						//setInput("u_apptype","RENEW");
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
						
						
                        //case "action2":
						//setInput("u_bpno",getTableInput("T1","code",row));
						//setInput("u_apptype","RETIRED");
						//setKey("keys","");
						//return formView(null,"<?php echo $page->formid; ?>");
						//break;
                                                
                                        
				}	
				break;
		}
	}
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			//case "df_docstatus":
			case "df_docno":
			case "df_u_appdate":
			case "df_u_feedesc":
			case "df_u_feecode":
			case "df_u_vehicletype":
			case "df_u_ticketby":
			case "df_u_plateno":
			case "df_feedesc":
			case "df_u_licenseno":
			
			
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
	var inputs = new Array();
		inputs.push("opt");
		//	inputs.push("u_appdatefr");
		//	inputs.push("u_appdateto");
		//	inputs.push("u_mvno");
		inputs.push("refno");
		inputs.push("refno2");
		inputs.push("docno");
		//	inputs.push("u_apptype");
		inputs.push("u_feedesc");
		inputs.push("u_feecode");
		inputs.push("u_licenseno");
			//inputs.push("u_lastname");
			//inputs.push("u_firstname");
			//inputs.push("u_middlename");
		
			
		return inputs;
	}
	
	
	function formAddNew() {
	setInput("u_feedesc",getInput("u_feedesc"));
	setInput("u_feecode",getInput("u_feecode"));
	setInput("u_licenseno",getInput("u_licenseno"));
		setInput("docstatus","Open");
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
			case "docno": 
			case "u_appdate": 
			case "u_feedesc": 
			case "u_feecode": 
			case "u_offense": 
			case "u_vehicletype": 
			case "u_ticketby":
			case "u_plateno":
			case "u_licenseno":
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
	
	function formSearchViolation() {
		if (isInputEmpty("u_feedesc")) return false;
		if (isInputEmpty("u_feecode")) return false;
		
		formSearchNow();
	}
	function formSearchViolatorName() {
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
<input type="hidden" <?php genInputHiddenDFHtml("refno2") ?> >
<!--<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >-->
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;History of Violations&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="width:500px;border:1px solid gray<?php if ($page->getitemstring("code")!="" && $page->getitemstring("opt")=="Print") echo ';display:none'; ?>" >
    <table class="tableFreeForm" width="700" cellpadding="0" cellspacing="0" border="0">
        
       <!-- <tr>
          <td width="350" ><label <?php genCaptionHtml($schema["u_appdatefr"],"") ?>>Date</label></td>
          <td  align=left>&nbsp;</td>
          </tr>
        <tr>
          <td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_appdatefr"]) ?> /><label <?php genCaptionHtml($schema["u_appdateto"],"") ?>>To</label>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_appdateto"]) ?> /></td>
          <td  align=left>&nbsp;</td>
          </tr>-->
       <!--<tr>
          <td width="350" ><label <?php genCaptionHtml($schema["code"],"") ?>>Application No.</label></td>
         <td  align=left><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
          </tr>-->
        <tr>
          <!--<td >&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["code"]) ?> /></td>
         <!-- <td  align=left>&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:300px") ?> >
          </select></td>
          </tr>-->
		  <tr>
          <td width="200" ><label <?php genCaptionHtml($schema["u_feedesc"],"") ?>>Violation Name</label></td>
		   <td width="350" ><label <?php genCaptionHtml($schema["u_feecode"],"") ?>>Violation Code</label></td>
         <!-- <td  align=left><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>-->
          </tr>
		  
        <tr>
           <td >&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_feedesc"]) ?> /></td>
		    <td >&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_feecode"]) ?> /></td>
		   
		   <!--<td >&nbsp;<select <?php genSelectHtml($schema["u_feedesc"],array("loadenumyear","",":[Select]")) ?> ></select></td>-->
         <!-- <td  align=left>&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:300px") ?> >
          </select></td>-->
          </tr>
		  
		  
		  
		  
       <!-- <tr>
          <td ><label <?php genCaptionHtml($schema["u_businesskind"],"") ?>>Kind of Business</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["u_taxclass"],"") ?>>Tax Classification</label></td>
          </tr>
        <tr>
          <td >&nbsp;<select <?php genSelectHtml($schema["u_businesskind"],array("loadudflinktable","u_bplkinds:code:name",":[All]"),null,null,null,"width:300px") ?> ></select></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_taxclass"],array("loadudflinktable","u_bpltaxclasses:code:name",":[All]"),null,null,null,"width:300px") ?> >
          </select></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_businesschar"],"") ?>>Business Character</label></td>
          <td  align=left><label <?php genCaptionHtml($schema["u_businesscategory"],"") ?>>Business Category</label></td>
          </tr>
        <tr>
          <td >&nbsp;<select <?php genSelectHtml($schema["u_businesschar"],array("loadudflinktable","u_bplcharacters:code:name",":[All]"),null,null,null,"width:300px") ?> ></select></td>
          <td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_businesscategory"],array("loadudflinktable","u_bplcategories:code:name",":[All]"),null,null,null,"width:300px") ?> ></select></td>
          </tr>   --> 
		  
        <tr>
          <td >&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
		  <!--<td><a class="button" href="" onClick="formSearchViolatorName();return false;">Search</a></td>-->
          <td align=left></td>
          </tr>
         <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
        </tr>
    </table>
    </div>
</td>
<td>
</td>
<td>
  <?php if ($page->getitemstring("opt")=="Print") { ?>
	<div style="display:none">
  <?php } ?>		
<div class="tabber" id="tab1">
	  <!--<div class="tabbertab" title="Business Name">
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="350" align=left><input type="text" size="50" <?php genInputTextHtml($schema["u_businessname"]) ?> /></td>
        </tr>
        <tr class="fillerRow5px">
          <td align=left></td>
        </tr>
        <tr>
          <td align=left><a class="button" href="" onClick="formSearchBusinessName();return false;">Search</a><?php if($search && !$match || $retired) {?>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Create</a><?php } ?><?php if($search && $match && $expired) {?>&nbsp;<a class="button" href="" onClick="formRenew();return false;">Renew</a><?php } ?><?php if($search && $match && !$expired && ($matchstatus=="Paid" || $matchstatus=="Printing")) {?>&nbsp;<a class="button" href="" onClick="formUpdate();return false;">Additional</a>&nbsp;<a class="button" href="" onClick="formTransfer();return false;">Transfer</a>&nbsp;<a class="button" href="" onClick="formAmmendment();return false;">Ammendment</a><?php } ?></td>
        </tr>
    </table>
    </div>	-->
	 <!-- <div class="tabbertab" title="Owner Name">
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="138" ><label <?php genCaptionHtml($schema["u_lastname"],"") ?>>Last Name</label></td>
          <td width="212" align=left><input type="text" <?php genInputTextHtml($schema["u_lastname"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_firstname"],"") ?>>First Name</label></td>
          <td  align=left><input type="text" <?php genInputTextHtml($schema["u_firstname"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_middlename"],"") ?>>Middle Name</label></td>
          <td  align=left><input type="text" <?php genInputTextHtml($schema["u_middlename"]) ?> /></td>
          </tr>

        <tr>
          <td >&nbsp;</td>
          <td align=left>
              <a class="button" href="" onClick="formSearchViolatorName();return false;">Search</a>
              <?php if($search && !$match ) {//?>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Create</a><?php } ?>
              <?php if($search && $match && $expired) {//?>&nbsp;<a class="button" href="" onClick="formRenew();return false;">Add Violation</a><?php } ?>
              <?php if($search && $match && !$expired) {?>&nbsp;
              <a class="button" href="" onClick="formUpdate();return false;">Additional</a>&nbsp;
              <a class="button" href="" onClick="formTransfer();return false;">Transfer</a>&nbsp;
              <a class="button" href="" onClick="formAmmendment();return false;">Ammendment</a><?php } ?>
          </td>
          </tr>
    </table>
    </div>	
</div> -->
	  <?php if ($page->getitemstring("opt")=="Print") { ?>
		</div>
	  <?php } ?>		
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
		<tr><td width="168"><label <?php genCaptionHtml($schema["docno"],"T100") ?> >Transaction No.</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["docno"],"T100") ?> /></td>
			
		</tr>
		<!--><tr><td width="168"><label <?php genCaptionHtml($schema["u_licenseno"],"T100") ?> >License No.</label></td>
			<td >&nbsp;<input type="text" disabled size="45" <?php genInputTextHtml($schema["u_licenseno"],"T100") ?> /></td>
		</tr>-->
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_lastname"],"T100") ?> >Last Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_lastname"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_firstname"],"T100") ?> >First Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_firstname"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_middlename"],"T100") ?> >Middle Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_middlename"],"T100") ?> /></td>
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
