<?php
	$progid = "u_lguqueuedisplay";

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
//	include_once("./inc/formaccess.php");

	$enumkindofproperty = array();
	$enumkindofproperty["L"]="Land";
	$enumkindofproperty["B"]="Building and Other Structure";
	$enumkindofproperty["M"]="Machinery";

	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
	$enumdocstatus["Encoding"]="Encoding";
	$enumdocstatus["Assessed"]="Assessed";
	$enumdocstatus["Recommended"]="Recommended";
	$enumdocstatus["Approved"]="Approved";
	$enumdocstatus["Active"]="Active";
	$enumdocstatus["Cancelled"]="Cancelled";
	
	$ctr = array("","","","","");
	$ref = array("","","","","");
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_RPLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_lguqueuedisplay";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Business Permit Application";
	
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_varpno"] = createSchemaUpper("u_varpno");
	$schema["u_tdno"] = createSchemaUpper("u_tdno");
	$schema["u_pin"] = createSchemaUpper("u_pin");
	$schema["u_ownername"] = createSchemaUpper("u_ownername");
	$schema["u_taxclass"] = createSchemaUpper("u_taxclass");
	$schema["u_kind"] = createSchemaUpper("u_kind");
	$schema["u_adminname"] = createSchemaUpper("u_adminname");
	$schema["u_street"] = createSchemaUpper("u_street");
	$schema["u_barangay"] = createSchemaUpper("u_barangay");
	$schema["u_municipality"] = createSchemaUpper("u_municipality");
	$schema["u_cadlotno"] = createSchemaUpper("u_cadlotno");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	
	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_varpno");
	$objGrid->addcolumn("u_tdno");
	$objGrid->addcolumn("u_pin");
	$objGrid->addcolumn("u_kind");
	$objGrid->addcolumn("u_ownername");
	$objGrid->addcolumn("u_adminname");
	$objGrid->addcolumn("u_street");
	$objGrid->addcolumn("u_barangay");
	$objGrid->addcolumn("u_cadlotno");
	$objGrid->addcolumn("u_municipality");
	$objGrid->addcolumn("u_city");
	$objGrid->addcolumn("u_province");
	$objGrid->addcolumn("docstatus");
	$objGrid->columntitle("docno","ID No.");
	$objGrid->columntitle("u_varpno","ARP No.");
	$objGrid->columntitle("u_tdno","TD No.");
	$objGrid->columntitle("u_pin","PIN");
	$objGrid->columntitle("u_kind","Kind");
	$objGrid->columntitle("u_ownername","Owner Name");
	$objGrid->columntitle("u_adminname","Administrator Name");
	$objGrid->columntitle("u_street","Street");
	$objGrid->columntitle("u_barangay","Barangay");
	$objGrid->columntitle("u_cadlotno","CAD Lot No.");
	$objGrid->columntitle("u_municipality","Municipality");
	$objGrid->columntitle("u_city","City");
	$objGrid->columntitle("u_province","Province");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columnwidth("indicator",-1);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_tdno",15);
	$objGrid->columnwidth("u_pin",20);
	$objGrid->columnwidth("u_kind",8);
	$objGrid->columnwidth("u_ownername",35);
	$objGrid->columnwidth("u_adminname",20);
	$objGrid->columnwidth("u_street",15);
	$objGrid->columnwidth("u_barangay",15);
	$objGrid->columnwidth("u_cadlotno",10);
	$objGrid->columnwidth("u_municipality",15);
	$objGrid->columnwidth("u_city",15);
	$objGrid->columnwidth("u_province",15);
	$objGrid->columnwidth("docstatus",12);
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_tdno",true);
	$objGrid->columnsortable("u_cadlotno",true);
	$objGrid->columnsortable("u_varpno",true);
	$objGrid->columnsortable("u_pin",true);
	$objGrid->columnsortable("u_ownername",true);
	$objGrid->columnsortable("u_adminname",true);
	$objGrid->columnsortable("u_street",true);
	$objGrid->columnsortable("u_barangay",true);
	$objGrid->columnsortable("u_municipality",true);
	$objGrid->columnsortable("u_city",true);
	$objGrid->columnsortable("u_province",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->addcolumn("print");
	$objGrid->columntitle("print","");
	$objGrid->columnalignment("print","center");
	$objGrid->columninput("print","type","link");
	$objGrid->columninput("print","caption","Print");
	$objGrid->columnwidth("print",5);
	$objGrid->columnvisibility("print",false);
	$objGrid->addcolumn("action");
	$objGrid->columntitle("action","");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","");
	$objGrid->columnwidth("action",7);
	$objGrid->columnvisibility("u_municipality",false);
	$objGrid->columnvisibility("u_city",false);
	$objGrid->columnvisibility("u_province",false);
	$objGrid->columnvisibility("action",false);
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
	$objGrid->automanagecolumnwidth = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "DOCNO";
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
	
	if (!isset($httpVars["df_opt"])) {
		$page->setitem("opt",1);
	} else {
		$page->setitem("opt",$page->getitemnumeric("opt")+15);
	}
	
			
	$filterExp = "";
	
	//$filterExp = genSQLFilterDate("B.U_APPDATE",$filterExp,$httpVars['df_u_arpno']);
	if ($page->getitemstring("docstatus")=="Active") {
		$filterExp = genSQLFilterNumeric("A.U_CANCELLED",$filterExp,'0');
		$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,'Assessed,Recommended,Approved',null,true);
	} elseif ($page->getitemstring("docstatus")=="Cancelled") {
		$filterExp = genSQLFilterNumeric("A.U_CANCELLED",$filterExp,'1');
	} else {
		$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	}
	$filterExp = genSQLFilterString("A.U_VARPNO",$filterExp,$httpVars['df_u_varpno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_TDNO",$filterExp,$httpVars['df_u_tdno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_PIN",$filterExp,$httpVars['df_u_pin'],null,null,true);
	$filterExp = genSQLFilterString("A.U_OWNERNAME",$filterExp,$httpVars['df_u_ownername'],null,null,true);
	$filterExp = genSQLFilterString("A.U_ADMINNAME",$filterExp,$httpVars['df_u_adminname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_STREET",$filterExp,$httpVars['df_u_street'],null,null,true);
	$filterExp = genSQLFilterString("A.U_BARANGAY",$filterExp,$httpVars['df_u_barangay'],null,null,true);
	$filterExp = genSQLFilterString("A.U_MUNICIPALITY",$filterExp,$httpVars['df_u_municipality']);
	$filterExp = genSQLFilterString("A.U_CADLOTNO",$filterExp,$httpVars['df_u_cadlotno'],null,null,true);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();

	$objrs->queryopen("select count(*) from u_bplapps where docstatus in ('Encoding','Assessing','Assessed','Approved','Disapproved')");
	if ($objrs->queryfetchrow()) {
		if ($page->getitemnumeric("opt")> $objrs->fields[0] ) $page->setitem("opt",1);
	}
	
	$idx=0;
	$objrs->queryopen("select U_CTR, U_REF from U_LGUQUE WHERE U_DATE='".currentdateDB()."' ORDER BY DOCNO DESC LIMIT 5");
	while ($objrs->queryfetchrow("NAME")) {
		$ctr[$idx] = $objrs->fields["U_CTR"];
		$ref[$idx] = $objrs->fields["U_REF"];
		$idx++;
	}
	
	//var_dump($ctr);
	/*
	//if ($page->getitemstring("u_adminname")!="" || $page->getitemstring("u_street")!="" || $page->getitemstring("u_barangay")!="" || $page->getitemstring("u_municipality")!="" || $page->getitemstring("u_city")!="" || $page->getitemstring("u_province")!="" || $page->getitemstring("u_arpno")!="" || $page->getitemstring("docstatus")!="" || $page->getitemstring("u_pin")!="" || $page->getitemstring("u_ownername")!="" || $page->getitemstring("u_kind")!="") {
	if ($page->getitemstring("u_kind")=="") {
		$objrs->queryopenext("select A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_CADLOTNO, A.U_STREET, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp UNION ALL select A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'BUILDING' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_CADLOTNO, A.U_STREET, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp UNION ALL select A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'MACHINERY' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_CADLOTNO, A.U_STREET, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} elseif ($page->getitemstring("u_kind")=="L") {
		$objrs->queryopenext("select A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_CADLOTNO, A.U_STREET, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} elseif ($page->getitemstring("u_kind")=="B") {
		$objrs->queryopenext("select A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'BUILDING' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_CADLOTNO, A.U_STREET, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} elseif ($page->getitemstring("u_kind")=="M") {
		$objrs->queryopenext("select A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'MACHINERY' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_CADLOTNO, A.U_STREET, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	}
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			
			//$indicator="";
			//if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
			
			$objGrid->setitem(null,"indicator",$indicator);
			$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
			$objGrid->setitem(null,"u_varpno",$objrs->fields["U_VARPNO"]);
			$objGrid->setitem(null,"u_tdno",$objrs->fields["U_TDNO"]);
			$objGrid->setitem(null,"u_pin",$objrs->fields["U_PIN"]);
			$objGrid->setitem(null,"u_ownername",$objrs->fields["U_OWNERNAME"]);
			$objGrid->setitem(null,"u_adminname",$objrs->fields["U_ADMINNAME"]);
			$objGrid->setitem(null,"u_street",$objrs->fields["U_STREET"]);
			$objGrid->setitem(null,"u_barangay",$objrs->fields["U_BARANGAY"]);
			$objGrid->setitem(null,"u_cadlotno",$objrs->fields["U_CADLOTNO"]);
			$objGrid->setitem(null,"u_municipality",$objrs->fields["U_MUNICIPALITY"]);
			$objGrid->setitem(null,"u_city",$objrs->fields["U_CITY"]);
			$objGrid->setitem(null,"u_province",$objrs->fields["U_PROVINCE"]);
			$objGrid->setitem(null,"u_kind",$objrs->fields["U_KIND"]);
			$objGrid->setitem(null,"docstatus",iif($objrs->fields["U_CANCELLED"]==1,"Cancelled",$objrs->fields["DOCSTATUS"]));
			if ($objrs->fields["DOCSTATUS"]!="Encoding" && $objrs->fields["U_CANCELLED"]==0) {
				$objGrid->setitem(null,"action","New Faas");
			}
			
			$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
			if (!$page->paging_fetch()) break;
		}	
	//}
	
	resetTabindex();
	setTabindex($schema["u_street"]);
	setTabindex($schema["u_barangay"]);
	setTabindex($schema["u_municipality"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	//$page->resize->addgrid("T1",20,285,false);
	*/
	$page->toolbar->setaction("print",false);
	
	$rptcols = 6; 
	
	/*
	<iframe id="gameIFrame1" style="width: 200px; height: 200px;   display: block; position: absolute;" src="http://www.guguncube.com">
</iframe>

<iframe id="gameIFrame2" style="width: 100px; height: 100px; top: 50px;    left: 50px;    display: block;    position: absolute;" src="http://www.guguncube.com">
</iframe>
*/
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
<style type "text/css">
<!--
/* @group Blink */
.blink {
	-webkit-animation: blink .75s linear infinite;
	-moz-animation: blink .75s linear infinite;
	-ms-animation: blink .75s linear infinite;
	-o-animation: blink .75s linear infinite;
	 animation: blink .75s linear infinite;
}
@-webkit-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-moz-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-ms-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-o-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
/* @end */
-->
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
		setTimeout("formSearchNow()",3000);
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
					switch (getTableInput("T1","u_kind",p_rowIdx)) {
						case "LAND":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS1");
							break;
						case "BUILDING":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS2");
							break;
						case "MACHINERY":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS3");
							break;
					}
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
			case "docstatus": 
			case "u_kind": 
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
			case "u_varpno": 
			case "u_tdno": 
			case "u_pin": 
			case "u_ownername": 
			case "u_adminname": 
			case "u_street": 
			case "u_barangay": 
			case "u_municipality": 
			case "u_cadlotno": 
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
						setInput("getprevarpno",getTableInput("T1","docno",row));
						setKey("keys","");
						switch (getTableInput("T1","u_kind",row)) {
							case "LAND":
								return formView(null,"./UDO.php?&objectcode=U_RPFAAS1");
								break;
							case "BUILDING":
								return formView(null,"./UDO.php?&objectcode=U_RPFAAS2");
								break;
							case "MACHINERY":
								return formView(null,"./UDO.php?&objectcode=U_RPFAAS3");
								break;
						}
						break;
				}	
				break;
		}
	}
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_u_pin":
			case "df_u_taxclass":
			case "df_u_ownername":
			case "df_u_kind":
			case "df_u_adminname":
			case "df_u_street":
			case "df_u_barangay":
			case "df_u_municipality":
			case "df_u_cadlotno":
			case "df_u_varpno":
			case "df_u_tdno":
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("opt");
			inputs.push("getprevarpno");
			inputs.push("u_varpno");
			inputs.push("u_tdno");
			inputs.push("u_pin");
			inputs.push("u_ownername");
			inputs.push("u_adminname");
			inputs.push("u_street");
			inputs.push("u_barangay");
			inputs.push("u_municipality");
			inputs.push("u_cadlotno");
			inputs.push("u_province");
			inputs.push("docstatus");
			inputs.push("u_kind");
			
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_varpno":
			case "u_tdno":
			case "u_pin":
			case "u_ownername":
			case "u_adminname":
			case "u_street":
			case "u_barangay":
			case "u_municipality":
			case "u_cadlotno":
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
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("getprevarpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
	<td>
		<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ref[1] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ctr[1] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%" rowspan="2" >
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="280" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><label class="<?php iif($ctr[0]!="","tab blink","") ?>">Counter</label></b></font></td>
                        <td align="center" rowspan="2" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><label class="tab blink"><?php echo $ref[0] ?></label></b></font></td>
                    </tr>
                	<tr height="100">
                    	<td align="center" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><label class="tab blink"><?php echo $ctr[0] ?></label></b></font></td>
                    </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ref[2] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ctr[2] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ref[3] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ctr[3] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%" rowspan="20" valign="top"><img id="PhotoImg" height="570" src="..\AddOns\GPS\LGU Add-On\UserPrograms\imgs\ads.png" width="100%" align="absmiddle" border=1></td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ref[4] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b><?php echo $ctr[4] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr>
                    	<td align="center" width="240"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Section</b></font></td>
                        <td align="center" ><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Application No.</b></font></td>
                        <td align="center" width="150"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Status</b></font></td>
                    </tr>
 	<?php 	
	$ctr=0;
	$objrs->queryopen("select 'Business Permit' as u_profitcenter, docno, docstatus from u_bplapps where docstatus in ('Encoding','Assessing','Assessed','Approved','Disapproved') order by lastupdated limit " . ($page->getitemnumeric("opt")-1) . ",15");
	while ($objrs->queryfetchrow("NAME")) {
		$ctr++;
	?>                   
                	<tr>
                    	<td align="center" width="240"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><?php echo $objrs->fields["u_profitcenter"] ?></font></td>
                        <td align="center" ><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><?php echo $objrs->fields["docno"] ?></font></td>
                        <td align="center" width="150"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><?php echo $objrs->fields["docstatus"] ?></font></td>
                    </tr>
    <?php } 
	while ($ctr<15) { $ctr++; ?>
    	
                	<tr>
                    	<td align="center" width="240"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;</font></td>
                        <td align="center" ><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;</font></td>
                        <td align="center" width="150"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;</font></td>
                    </tr>
    
	<?php }	?>                
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
		</table>
	</td>
</tr>	
<tr class="fillerRow10px"><td></td></tr>
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
