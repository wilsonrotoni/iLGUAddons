<?php
	$progid = "u_bplreqlist";

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
	$enumviewby["Acctno"]="Account No";
	$enumviewby["Applications"]="Applications";
	
	$page->objectcode = "u_bplreqlist";
	$page->paging->formid = "./UDP.php?&objectcode=u_bplreqlist";
	$page->formid = "./UDO.php?&objectcode=U_BPLREQAPPS";
	$page->objectname = "Business Permit Requirements Application";
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["acctno"] = createSchemaUpper("acctno");
	$schema["u_businesskind"] = createSchemaUpper("u_businesskind");
	$schema["u_businesschar"] = createSchemaUpper("u_businesschar");
	$schema["u_taxclass"] = createSchemaUpper("u_taxclass");
	$schema["u_businesscategory"] = createSchemaUpper("u_businesscategory");
	$schema["u_businessname"] = createSchemaUpper("u_businessname");
	$schema["viewby"] = createSchemaUpper("viewby");
	$schema["u_lastname"] = createSchemaUpper("u_lastname");
	$schema["u_firstname"] = createSchemaUpper("u_firstname");
	$schema["u_middlename"] = createSchemaUpper("u_middlename");
	$schema["u_appdatefr"] = createSchemaDate("u_appdatefr");
	$schema["u_appdateto"] = createSchemaDate("u_appdateto");
        $schema["u_bunitno"] = createSchemaUpper("u_bunitno");
	$schema["u_bstreet"] = createSchemaUpper("u_bstreet");
	$schema["u_bblock"] = createSchemaUpper("u_bblock");
        $schema["u_bfloorno"] = createSchemaUpper("u_bfloorno");
	$schema["u_bvillage"] = createSchemaUpper("u_bvillage");
	$schema["u_bbrgy"] = createSchemaUpper("u_bbrgy");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	
	$objGrid = new grid("T1",$httpVars);
        
        $objGrid->addcolumn("indicator");
        $objGrid->addcolumn("action");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_appdate");
	$objGrid->addcolumn("u_businessname");
	$objGrid->addcolumn("u_lastname");
	$objGrid->addcolumn("u_firstname");
	$objGrid->addcolumn("u_middlename");
	$objGrid->addcolumn("u_businessaddress");
	$objGrid->addcolumn("u_apprefno");
	$objGrid->addcolumn("docstatus");
        
	$objGrid->columntitle("indicator","Legend");
	$objGrid->columntitle("docno","Reference No.");
	$objGrid->columntitle("u_appdate","Date");
	$objGrid->columntitle("u_businessname","Business Name");
	$objGrid->columntitle("u_lastname","Last Name");
	$objGrid->columntitle("u_firstname","First Name");
	$objGrid->columntitle("u_middlename","Middle Name");
	$objGrid->columntitle("u_businessaddress","Business Address");
	$objGrid->columntitle("docstatus","Status");
        
	$objGrid->columnwidth("indicator",8);
	$objGrid->columnwidth("docno",14);
	$objGrid->columnwidth("u_appno",14);
	$objGrid->columnwidth("u_appdate",10);
	$objGrid->columnwidth("u_businessname",50);
	$objGrid->columnwidth("u_lastname",15);
	$objGrid->columnwidth("u_firstname",15);
	$objGrid->columnwidth("u_middlename",15);
	$objGrid->columnwidth("u_businessaddress",50);
	$objGrid->columnwidth("docstatus",12);
        
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_appno",true);
	$objGrid->columnsortable("u_appdate",true);
	$objGrid->columnsortable("u_businessname",true);
	$objGrid->columnsortable("u_lastname",true);
	$objGrid->columnsortable("u_firstname",true);
	$objGrid->columnsortable("u_middlename",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columnvisibility("u_apprefno",false);
	$objGrid->columnvisibility("indicator",true);
        
        $objGrid->columnalignment("indicator","center");
        
	$objGrid->addcolumn("print");
	$objGrid->columntitle("print","");
	$objGrid->columnalignment("print","center");
	$objGrid->columninput("print","type","link");
	$objGrid->columninput("print","caption","Print");
	$objGrid->columnwidth("print",5);
	$objGrid->columnvisibility("print",false);
        
//	$objGrid->addcolumn("action2");
//	$objGrid->columntitle("action2","*");
//	$objGrid->columnalignment("action2","center");
//	$objGrid->columninput("action2","type","link");
//	$objGrid->columninput("action2","caption","");
//	$objGrid->columnwidth("action2",15);
//	$objGrid->columnvisibility("action2",false);
        
	$objGrid->columntitle("action","*");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","");
	$objGrid->columnwidth("action",5);
	$objGrid->columnvisibility("action",false);
        
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
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
		$lookupSortBy = "DOCNO DESC";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	
	if ($page->getitemstring("opt")=="Print") {
		if (!isset($httpVars["df_docstatus"])) {
			$page->setitem("docstatus","Printing");
		}
		//$schema["docstatus"]["attributes"]="disabled";
		$objGrid->columnvisibility("docstatus",false);
		$objGrid->columnvisibility("indicator",false);
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
	
	
	$filterExp = genSQLFilterDate("B.U_APPDATE",$filterExp,$httpVars['df_u_appdatefr'],$httpVars['df_u_appdateto']);
	if ($page->getitemstring("docstatus")=="Expired") {
		$filterExp = genSQLFilterNumeric("A.U_YEAR",$filterExp,date('Y'),null,"<");
	}else if($page->getitemstring("docstatus")=="Retired") {
            $filterExp = genSQLFilterString("B.U_RETIRED",$filterExp,1);
        }else {
		$filterExp = genSQLFilterString("B.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	}
        
	$filterExp = genSQLFilterString("B.DOCNO",$filterExp,$httpVars['df_docno']);
	$filterExp = genSQLFilterString("B.U_APPNO",$filterExp,$httpVars['df_acctno']);
	//$filterExp = genSQLFilterString("B.U_BUSINESSKIND",$filterExp,$httpVars['df_u_businesskind']);
	$filterExp = genSQLFilterString("B.U_TAXCLASS",$filterExp,$httpVars['df_u_taxclass']);
	$filterExp = genSQLFilterString("B.U_BUSINESSCHAR",$filterExp,$httpVars['df_u_businesschar']);
	$filterExp = genSQLFilterString("B.U_BUSINESSCATEGORY",$filterExp,$httpVars['df_u_businesscategory']);
	
	$filterExp = genSQLFilterString("A.U_LASTNAME",$filterExp,$httpVars['df_u_lastname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_FIRSTNAME",$filterExp,$httpVars['df_u_firstname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_MIDDLENAME",$filterExp,$httpVars['df_u_middlename'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BUNITNO",$filterExp,$httpVars['df_u_bunitno'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BSTREET",$filterExp,$httpVars['df_u_bstreet'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BFLOORNO",$filterExp,$httpVars['df_u_bfloorno'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BBLOCK",$filterExp,$httpVars['df_u_bblock'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BVILLAGE",$filterExp,$httpVars['df_u_bvillage'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BBRGY",$filterExp,$httpVars['df_u_bbrgy'],null,null,true);
        
        if($page->getitemstring("viewby")=="Acctno" || $page->getitemstring("viewby")=="") $filterExp = genSQLFilterString("A.NAME",$filterExp,$httpVars['df_u_businessname'],null,null,true);
        else $filterExp = genSQLFilterString("B.U_BUSINESSNAME",$filterExp,$httpVars['df_u_businessname'],null,null,true);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
        
	$objrs = new recordset(null,$objConnection);
      
	if ($page->getitemstring("u_businessname")!="") {
		$search=true;
	}
        
	if ($page->getitemstring("u_businessname")!="" || $page->getitemstring("u_lastname")!="" || $page->getitemstring("u_firstname")!="" || $page->getitemstring("u_middlename")!="" || $page->getitemstring("u_appdatefr")!="" || $page->getitemstring("u_appdateto")!="" || $page->getitemstring("acctno")!="" || $page->getitemstring("docno")!="" || $page->getitemstring("u_bunitno")!="" || $page->getitemstring("u_bfloorno")!="" || $page->getitemstring("u_bvillage")!=""  || $page->getitemstring("u_bstreet")!=""  || $page->getitemstring("u_bblock")!=""  || $page->getitemstring("u_bbrgy")!="") {
            if($page->getitemstring("viewby")=="Acctno" || $page->getitemstring("viewby")==""){
                $objrs->queryopenext("select B.U_APPNO,A.U_RETIRED,ifnull(B.DOCNO,A.CODE) AS CODE, ifnull(B.U_APPDATE,A.U_APPDATE) as U_APPDATE, A.NAME, A.U_LASTNAME, A.U_FIRSTNAME, A.U_MIDDLENAME, ifnull(B.U_YEAR,A.U_YEAR) as U_YEAR, B.DOCSTATUS, A.U_APPREFNO, B.U_BUNITNO,B.U_BSTREET,B.U_BFLOORNO,IF(B.U_BBLOCK!='',CONCAT('BLK ',B.U_BBLOCK),'') AS U_BBLOCK,IF(B.U_BVILLAGE!='',CONCAT(B.U_BVILLAGE,', '),B.U_BVILLAGE) AS U_BVILLAGE,IF(B.U_BBRGY!='',CONCAT(B.U_BBRGY,', '),' ') AS U_BBRGY,B.U_BCITY,B.U_BPROVINCE  from U_BPLMDS A LEFT JOIN U_BPLAPPS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.U_APPREFNO WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            }else{
                $objrs->queryopenext("select * from U_BPLAPPSREQAPPS B WHERE B.COMPANY = '$company' AND B.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            }
        }
		//$objrs->queryopenext("select B.U_APPNO,A.U_RETIRED,ifnull(B.DOCNO,A.CODE) AS CODE, ifnull(B.U_APPDATE,A.U_APPDATE) as U_APPDATE, A.NAME, A.U_LASTNAME, A.U_FIRSTNAME, A.U_MIDDLENAME, ifnull(B.U_YEAR,A.U_YEAR) as U_YEAR, B.DOCSTATUS, A.U_APPREFNO from U_BPLMDS A LEFT JOIN U_BPLAPPS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.U_APPREFNO WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		//var_dump("select B.U_APPNO,b.U_RETIRED,B.DOCNO AS CODE, B.U_APPDATE as U_APPDATE, B.U_BUSINESSNAME AS NAME, B.U_LASTNAME, B.U_FIRSTNAME, B.U_MIDDLENAME, B.U_YEAR as U_YEAR, B.DOCSTATUS, b.DOCNO AS U_APPREFNO, B.U_BUNITNO,B.U_BSTREET,B.U_BFLOORNO,IF(B.U_BBLOCK!='',CONCAT('BLK ',B.U_BBLOCK),'') AS U_BBLOCK,IF(B.U_BVILLAGE!='',CONCAT(B.U_BVILLAGE,', '),B.U_BVILLAGE) AS U_BVILLAGE,IF(B.U_BBRGY!='',CONCAT(B.U_BBRGY,', '),' ') AS U_BBRGY,B.U_BCITY,B.U_BPROVINCE from U_BPLAPPS B WHERE B.COMPANY = '$company' AND B.BRANCH = '$branch' $filterExp");
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			if ($search) {
				if ($page->getitemstring("u_businessname")==$objrs->fields["NAME"]) {
					$match=true;
					$matchstatus=$objrs->fields["DOCSTATUS"];
				}
			}
                        
                        $indicator = "";
                        
			$objGrid->addrow();
                        
                        
			$objGrid->setitem(null,"u_appno",$objrs->fields["U_APPNO"]);
			$objGrid->setitem(null,"docno",$objrs->fields["CODE"]);
			$objGrid->setitem(null,"u_appdate",formatDateToHttp($objrs->fields["U_APPDATE"]));
			$objGrid->setitem(null,"u_businessname",$objrs->fields["NAME"]);
			$objGrid->setitem(null,"u_businessaddress", strtoupper($objrs->fields["U_BBLDGNAME"] . ' ' . $objrs->fields["U_BBLDGNO"] . ' ' . $objrs->fields["U_BUNITNO"] . ' ' . $objrs->fields["U_BFLOORNO"] . ' ' .  $objrs->fields["U_BBLOCK"] . ' ' .  $objrs->fields["U_BSTREET"]  . ' ' .  $objrs->fields["U_BVILLAGE"] .  $objrs->fields["U_BBRGY"] .  $objrs->fields["U_BCITY"] . ', ' .  $objrs->fields["U_BPROVINCE"]));
			$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
			$objGrid->setitem(null,"u_firstname",$objrs->fields["U_FIRSTNAME"]);
			$objGrid->setitem(null,"u_middlename",$objrs->fields["U_MIDDLENAME"]);
			$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
			$objGrid->setitem(null,"u_apprefno",$objrs->fields["U_APPREFNO"]);
                        $objGrid->setitem(null,"action2","Retire w/ Payment");
			if ($objrs->fields["U_YEAR"]<date('Y')) {
                                $indicator="Expired";
				$objGrid->setitem(null,"docstatus","Expired");
				$objGrid->setitem(null,"action","Renew");
                                $objGrid->setitem(null,"indicator",$indicator);
			} elseif ($objrs->fields["DOCSTATUS"]=="Paid" && date('m')=="12") {
				$objGrid->setitem(null,"docstatus","Paid");
				$objGrid->setitem(null,"action","Renew");
			} elseif ($objrs->fields["DOCSTATUS"]=="Paid") {
				$objGrid->setitem(null,"docstatus","Paid");
				$objGrid->setitem(null,"action","Update");
			}else $objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
                        
                        if ($objrs->fields["U_RETIRED"]== 1) {
                            $indicator="Retired";
                            $objGrid->setitem(null,"indicator",$indicator);
                            $objGrid->setitem(null,"docstatus","Retired");
                            $objGrid->setitem(null,"action","");
                            $objGrid->setitem(null,"action2","");
                            $retired = true;
                        }
                      
			$objGrid->setkey(null,$objrs->fields["CODE"]); 
			if (!$page->paging_fetch()) break;
		}	
	//}
                
        $indicator = '';       
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
            
            global $indicator;
		switch ($column) {
			case "indicator":
                                $indicator = $label;
				switch ($label) {
                                        case "Expired": $style="background-color:#d13d3d"; break;
                                        case "Retired": $style="background-color:#d13d3d"; break;
                                        default : $style="background-color:#4A8CFA"; break;
				}
				$label="&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";	
				break;
                        default :
                                switch ($indicator){
                                    case "Expired": $style="color:#d13d3d"; break;
                                    case "Retired": $style="color:#d13d3d";break;
                                    default : $style="color:#39383b"; break;
                                }
                                break;
		}
		
	}
	
	resetTabindex();
	setTabindex($schema["u_lastname"]);
	setTabindex($schema["u_firstname"]);
	setTabindex($schema["u_middlename"]);
	setTabindex($schema["u_birthdate"]);
	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,210,false);
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
		//"u_lastname");
		try {
			if (window.opener.getVar("objectcode")=="U_LGUPOS") {
				autoprint = true;
				autoprintdocno = window.opener.getPrivate("bpno");
				searchTableInput("T1","docno",autoprintdocno,true);
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
			case "docstatus": 
			case "u_appdatefr": 
			case "u_appdateto": 
			case "u_businesskind": 
			case "u_taxclass": 
			case "u_businesschar": 
			case "u_businesscategory": 
				setInput("u_businessname","");
				setInput("u_lastname","");
				setInput("u_firstname","");
				setInput("u_middlename","");
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
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_viewby":
			case "df_docno":
			case "df_acctno":
			case "df_u_businesskind":
			case "df_u_taxclass":
			case "df_u_businesschar":
			case "df_u_businesscategory":
			case "df_u_businessname":
			case "df_u_lastname":
			case "df_u_firstname":
			case "df_u_middlename":
			case "df_u_appdatefr":
			case "df_u_appdateto":
                            
			case "df_u_bunitno": 
			case "df_u_bstreet": 
			case "df_u_bfloorno": 
			case "df_u_bblock": 
			case "df_u_bvillage": 
			case "df_u_bbrgy": 
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("opt");
			inputs.push("u_appdatefr");
			inputs.push("u_appdateto");
			inputs.push("viewby");
			inputs.push("docno");
			inputs.push("acctno");
			inputs.push("u_bpno");
			inputs.push("u_apptype");
			inputs.push("docstatus");
			inputs.push("u_businesskind");
			inputs.push("u_taxclass");
			inputs.push("u_businesschar");
			inputs.push("u_businesscategory");
			inputs.push("u_businessname");
			inputs.push("u_lastname");
			inputs.push("u_firstname");
			inputs.push("u_middlename");
			inputs.push("u_bunitno");
			inputs.push("u_bstreet");
			inputs.push("u_bfloorno");
			inputs.push("u_bblock");
			inputs.push("u_bvillage");
			inputs.push("u_bbrgy");
			
		return inputs;
	}
	
	
	function formAddNew() {
		setInput("u_businessname",getInput("u_businessname"));
		setInput("u_apptype","NEW");
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
                
                if(getInput("acctno")=="" && getInput("docno")=="" &&  getInput("u_appdatefr")=="" &&  getInput("u_appdateto")=="" &&  getInput("u_businessname")=="" &&  getInput("u_lastname")=="" &&  getInput("u_firstname")=="" && getInput("u_middlename")=="" && getInput("u_bunitno")=="" && getInput("u_bfloorno")=="" && getInput("u_bvillage")=="" && getInput("u_bstreet")=="" && getInput("u_bblock")=="" && getInput("u_bbrgy")==""){
                    page.statusbar.showError("Please input atleast one filter");	
                    return false;
                }
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "acctno":
			case "docno":
			case "u_appdatefr":
			case "u_appdateto":
			case "u_businesschar":
			case "viewby":
			case "docstatus":
			case "u_businesscategory":
			case "u_taxclass":
			case "u_bunitno":
			case "u_bfloorno":
			case "u_bvillage":
			case "u_bstreet":
			case "u_bblock":
			case "u_bbrgy":
			case "u_lastname":
			case "u_firstname":
			case "u_middlename":
			case "u_birthdate":
			case "u_patientid":
			case "u_businessname":
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
	  <td class="labelPageHeader" >&nbsp;Business Permit Requirements Application List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="width:900px;<?php if ($page->getitemstring("docno")!="" && $page->getitemstring("opt")=="Print") echo ';display:none'; ?>" >
    <table width="937" border="0"  cellpadding="0" cellspacing="0" class="tableFreeForm">
       
        <tr>
          <td width="173"><label <?php genCaptionHtml($schema["docno"],"") ?>>Reference No.</label></td>
          <td width="328"  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["docno"]) ?> /></td>
          <td  width="204"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
          <td  align=left width="195">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:200px") ?> ></select></td>
        </tr>
        
         <tr>
          <td ><label <?php genCaptionHtml($schema["docno"],"") ?>>Application Date</label></td>
          <td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_appdatefr"]) ?> /><label <?php genCaptionHtml($schema["u_appdateto"],"") ?>>To</label>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_appdateto"]) ?> /></td>
          <td ></td>
          <td >&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
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
	  <div class="tabbertab" title="Business Name">
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="250" align=left><input type="text" size="50" <?php genInputTextHtml($schema["u_businessname"]) ?> /></td>
        </tr>
        <tr class="fillerRow5px">
          <td align=left></td>
        </tr>
        <tr>
          <td align=left><a class="button" href="" onClick="formSearchBusinessName();return false;">Search</a><?php if($search && !$match || $retired) {?>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Create</a><?php } ?><?php if($search && $match && $expired) {?>&nbsp;<a class="button" href="" onClick="formRenew();return false;">Renew</a><?php } ?><?php if($search && $match && !$expired && ($matchstatus=="Paid" || $matchstatus=="Printing")) {?>&nbsp;<a class="button" href="" onClick="formUpdate();return false;">Additional</a>&nbsp;<a class="button" href="" onClick="formTransfer();return false;">Transfer</a>&nbsp;<a class="button" href="" onClick="formAmmendment();return false;">Ammendment</a><?php } ?></td>
        </tr>
    </table>
    </div>	
	  <div class="tabbertab" title="Owner Name">
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
              <a class="button" href="" onClick="formSearchOwnerName();return false;">Search</a>
              <?php if($search && !$match ) {//?>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Create</a><?php } ?>
              <?php if($search && $match && $expired) {//?>&nbsp;<a class="button" href="" onClick="formRenew();return false;">Renew</a><?php } ?>
              <?php if($search && $match && !$expired) {?>&nbsp;
              <a class="button" href="" onClick="formUpdate();return false;">Additional</a>&nbsp;
              <a class="button" href="" onClick="formTransfer();return false;">Transfer</a>&nbsp;
              <a class="button" href="" onClick="formAmmendment();return false;">Ammendment</a><?php } ?>
          </td>
          </tr>
    </table>
    </div>	
    <div class="tabbertab" title="Business Address">
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="80" ><label <?php genCaptionHtml($schema["u_bunitno"],"") ?>>Unit No</label></td>
          <td width="100" align=left><input type="text" size="12"  <?php genInputTextHtml($schema["u_bunitno"]) ?> /></td>
          <td width="80" ><label <?php genCaptionHtml($schema["u_bstreet"],"") ?>>Street</label></td>
          <td width="100" align=left><input type="text" size="12" <?php genInputTextHtml($schema["u_bstreet"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_bfloorno"],"") ?>>Floor No</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bfloorno"]) ?> /></td>
           <td ><label <?php genCaptionHtml($schema["u_bblock"],"") ?>>Block</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bblock"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_bvillage"],"") ?>>Subdivision</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bvillage"]) ?> /></td>
           <td ><label <?php genCaptionHtml($schema["u_bbrgy"],"") ?>>Barangay</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bbrgy"]) ?> /></td>
          </tr>

    </table>
    </div>	
</div> 
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
		<tr><td width="168"><label <?php genCaptionHtml($schema["docno"],"T100") ?> >Reference No.</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["docno"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_businessname"],"T100") ?> >Business Name</label></td>
			<td >&nbsp;<input type="text" disabled size="45" <?php genInputTextHtml($schema["u_businessname"],"T100") ?> /></td>
		</tr>
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
