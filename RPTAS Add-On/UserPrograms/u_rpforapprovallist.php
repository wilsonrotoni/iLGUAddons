<?php
	

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_rpforapprovallist";
        
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
	include_once("./classes/documentschema_br.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./boschema/checkprinting.php");
	include_once("./utils/businessobjects.php");
	include_once("./sls/enumdocstatus.php");

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
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	global $indicator;
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_RPFORAPPROVALLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_rpforapprovallist";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Business Permit Application";
	$page->reportlayouts = true;
	$page->getsettings();
        $page->settings->setschema($boschema_checkprinting);
        
        function onFormAction($action) {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		$actionReturn = true;
                
                if(strtolower($action)=="u_approved") {
			$obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
			$obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
			$obju_Faas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
			//$obju_FADepreScheds = new masterdatalinesschema(NULL,$objConnection,"u_fadeprescheds");
			$objRs = new recordset(null,$objConnection);
			
			$objConnection->beginwork();
			for($i=0;$i<= $objGrid->recordcount;$i++) {
				if($page->getitemstring("chxboxT1r".$i) == "1") {
                                    if ($page->getcolumnstring("T1",$i,"u_kind") == "LAND"){
                                        if ($obju_Faas1->getbykey($page->getcolumnstring("T1",$i,"docno"))) {
                                            $obju_Faas1->docstatus = "Approved";
                                            if ($actionReturn) $actionReturn = $obju_Faas1->update($obju_Faas1->docno,$obju_Faas1->rcdversion);
                                        }else $actionReturn = raiseError("Unable to retrieve Faas Land [".$page->getcolumnstring("T1",$i,"docno")."].");
                                    }else  if ($page->getcolumnstring("T1",$i,"u_kind") == "BUILDING"){
                                        if ($obju_Faas2->getbykey($page->getcolumnstring("T1",$i,"docno"))) {
                                            $obju_Faas2->docstatus = "Approved";
                                        if ($actionReturn) $actionReturn = $obju_Faas2->update($obju_Faas2->docno,$obju_Faas2->rcdversion);
                                            
                                        }else $actionReturn = raiseError("Unable to retrieve Fixed Asset [".$page->getcolumnstring("T1",$i,"docno")."].");
                                    }else {
                                        if ($obju_Faas3->getbykey($page->getcolumnstring("T1",$i,"docno"))) {
                                            $obju_Faas3->docstatus = "Approved";
                                        if ($actionReturn) $actionReturn = $obju_Faas3->update($obju_Faas3->docno,$obju_Faas3->rcdversion);
                                            
                                        }else $actionReturn = raiseError("Unable to retrieve Fixed Asset [".$page->getcolumnstring("T1",$i,"docno")."].");
                                    }
				}
				if (!$actionReturn) break;
			}
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			onFormAction("edit",$filter);
		}
                
                
                
		return $actionReturn;
	}
        
        $filter="";
        
	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_maxrowcount"] = createSchemaUpper("u_maxrowcount");
	$schema["u_varpno"] = createSchemaUpper("u_varpno");
	$schema["u_tdno"] = createSchemaUpper("u_tdno");
	$schema["u_pin"] = createSchemaUpper("u_pin");
	$schema["u_tctno"] = createSchemaUpper("u_tctno");
	$schema["u_ownername"] = createSchemaUpper("u_ownername");
	$schema["u_prevarpnofrom"] = createSchemaUpper("u_prevarpnofrom");
	$schema["u_prevarpnoto"] = createSchemaUpper("u_prevarpnoto");
	$schema["u_surveyno"] = createSchemaUpper("u_surveyno");
	$schema["u_taxclass"] = createSchemaUpper("u_taxclass");
	$schema["u_kind"] = createSchemaUpper("u_kind");
	$schema["u_adminname"] = createSchemaUpper("u_adminname");
	$schema["u_barangay"] = createSchemaUpper("u_barangay");
	$schema["u_oldbarangay"] = createSchemaUpper("u_oldbarangay");
	$schema["u_municipality"] = createSchemaUpper("u_municipality");
	$schema["u_cadlotno"] = createSchemaUpper("u_cadlotno");
	$schema["u_revisionyear"] = createSchemaUpper("u_revisionyear");
	$schema["u_location"] = createSchemaUpper("u_location");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	$schema["docstatus"]["attributes"] = "disabled";
        
	$objGrid = new grid("T1",$httpVars,true);
        
        $objGrid->addcolumn("chxbox");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_kind");
	$objGrid->addcolumn("u_varpno");
	$objGrid->addcolumn("u_revisionyear");
        $objGrid->addcolumn("u_ownername");
	$objGrid->addcolumn("u_barangay");
	$objGrid->addcolumn("u_oldbarangay");
	$objGrid->addcolumn("u_tctno");
	$objGrid->addcolumn("u_surveyno");
	$objGrid->addcolumn("u_lot");
	$objGrid->addcolumn("u_block");
	$objGrid->addcolumn("u_sqm");
	$objGrid->addcolumn("u_marketvalue");
	$objGrid->addcolumn("u_assvalue");
	$objGrid->addcolumn("u_prevarpno2");
        
	$objGrid->columntitle("docno","ID No");
	$objGrid->columntitle("u_varpno","ARP No");
	$objGrid->columntitle("u_revisionyear","GR Year");
	$objGrid->columntitle("u_tdno","TD Number");
	$objGrid->columntitle("u_pin","PIN");
	$objGrid->columntitle("u_assvalue","Assessed Value");
	$objGrid->columntitle("u_tctno","Title Number");
	$objGrid->columntitle("u_kind","Kind");
	$objGrid->columntitle("u_ownername","Owner Name");
	$objGrid->columntitle("u_adminname","Administrator Name");
	$objGrid->columntitle("u_prevtdno","Previous TD");
	$objGrid->columntitle("u_prevarpno2","Previous ARP No");
	$objGrid->columntitle("u_surveyno","Survey No");
	$objGrid->columntitle("u_barangay","Barangay");
	$objGrid->columntitle("u_oldbarangay","Old Brgy");
	$objGrid->columntitle("u_location","Location");
	$objGrid->columntitle("u_cadlotno","Cad Lot No");
	$objGrid->columntitle("u_sqm","Area(sqm)");
	$objGrid->columntitle("u_marketvalue","Market Value");
	$objGrid->columntitle("u_lot","Lot No");
	$objGrid->columntitle("u_block","Block No");
	$objGrid->columntitle("u_municipality","Municipality");
	$objGrid->columntitle("u_city","City");
	$objGrid->columntitle("u_province","Province");
	$objGrid->columntitle("docstatus","Status");
        
	$objGrid->columnwidth("indicator",1);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_noticeno",10);
	$objGrid->columnwidth("u_varpno",15);
	$objGrid->columnwidth("u_revisionyear",6);
	$objGrid->columnwidth("u_tdno",15);
	$objGrid->columnwidth("u_pin",20);
	$objGrid->columnwidth("u_marketvalue",13);
	$objGrid->columnwidth("u_assvalue",13);
	$objGrid->columnwidth("u_tctno",15);
	$objGrid->columnwidth("u_kind",8);
	$objGrid->columnwidth("u_ownername",40);
	$objGrid->columnwidth("u_adminname",30);
	$objGrid->columnwidth("u_prevtdno",15);
	$objGrid->columnwidth("u_prevarpno2",15);
	$objGrid->columnwidth("u_surveyno",15);
	$objGrid->columnwidth("u_barangay",15);
	$objGrid->columnwidth("u_oldbarangay",6);
	$objGrid->columnwidth("u_location",40);
	$objGrid->columnwidth("u_cadlotno",50);
	$objGrid->columnwidth("u_sqm",10);
	$objGrid->columnwidth("u_lot",6);
	$objGrid->columnwidth("u_block",6);
	$objGrid->columnwidth("u_municipality",15);
	$objGrid->columnwidth("u_city",15);
	$objGrid->columnwidth("u_province",15);
	$objGrid->columnwidth("docstatus",12);
        
	$objGrid->columnalignment("indicator","center");
	$objGrid->columnalignment("u_assvalue","right");
	$objGrid->columnalignment("u_sqm","right");
	$objGrid->columnalignment("u_marketvalue","right");
        
//        $objGrid->columnlnkbtn("docno","OpenLnkBtnu_faas()");
        
        $objGrid->columninput("chxbox","type","checkbox");
	$objGrid->columninput("chxbox","value",1);
	$objGrid->columnattributes("chxbox","enable");
	$objGrid->columnwidth("chxbox",2);
	$objGrid->columntitle("chxbox","");
	$objGrid->dataentry = false;
	$objGrid->automanagecolumnwidth = false;
	
		
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

	
	
	if ($page->getitemstring("opt")=="Print") {
		$page->setitem("docstatus","Printing");
		$schema["docstatus"]["attributes"]="disabled";
		$objGrid->columnvisibility("docstatus",false);
		$objGrid->columnvisibility("print",true);
	
	} else {
		$objGrid->columnvisibility("action",true);
	}
        function onAfterDefault() { 
            global $objConnection;
            global $page;
            //var_dump($schema["docstatus"]);
        }

	
		
	require("./inc/formactions.php");
	if (!isset($httpVars['docstatus'])) {
		$httpVars['df_docstatus'] = "Assessed";
	}
        
	$filterExp = "";
	$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	$filterExp = genSQLFilterString("A.U_BARANGAY",$filterExp,$httpVars['df_u_barangay']);
	$filterExp = genSQLFilterString("A.U_OLDBARANGAY",$filterExp,$httpVars['df_u_oldbarangay']);
	$filterExp = genSQLFilterString("A.U_PREVARPNO2",$filterExp,$httpVars['df_u_prevarpnofrom'],$httpVars['df_u_prevarpnoto']);
	$filterExp = genSQLFilterString("A.U_REVISIONYEAR",$filterExp,$httpVars['df_u_revisionyear'],null,null,false);
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
        if ($_SESSION["errormessage"]=="") {
                $objGrid->clear();
                $objRs->setdebug();
                if ($page->getitemstring("u_revisionyear")!= "") {
                        if ($page->getitemstring("u_kind")=="") {
                            $objrs->queryopen("SELECT A.U_REVISIONYEAR,A.U_LOCATION,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,A.U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, A.U_PIN, A.U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM( SELECT A.U_REVISIONYEAR,IF(A.U_LOCATION='',A.U_SUBDIVISION,A.U_LOCATION) AS U_LOCATION,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,A.U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp  UNION ALL select A.U_REVISIONYEAR,IF(A.U_LOCATION='',A.U_SUBDIVISION,A.U_LOCATION) AS U_LOCATION,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,A.U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) AS U_PIN, 'BUILDING' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp UNION ALL select A.U_REVISIONYEAR,IF(A.U_LOCATION='',A.U_SUBDIVISION,A.U_LOCATION) AS U_LOCATION,0 AS U_TOTALAREASQM,'' AS U_BLOCK,'' AS U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,'' AS U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) AS U_PIN, 'MACHINERY' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, '' as U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ) AS A ORDER BY U_PREVARPNO2");
                        } else if ($page->getitemstring("u_kind")=="L") {
                            $objrs->queryopen("select A.U_REVISIONYEAR,IF(A.U_LOCATION='',A.U_SUBDIVISION,A.U_LOCATION) AS U_LOCATION,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,A.U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS,A.U_MARKETVALUE FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ORDER BY U_PREVARPNO2");
                        } else if ($page->getitemstring("u_kind")=="B") {
                            $objrs->queryopen("select A.U_REVISIONYEAR,IF(A.U_LOCATION='',A.U_SUBDIVISION,A.U_LOCATION) AS U_LOCATION,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,A.U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'BUILDING' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS,A.U_MARKETVALUE FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ORDER BY U_PREVARPNO2");
                        } else if ($page->getitemstring("u_kind")=="M") {
                            $objrs->queryopen("select A.U_REVISIONYEAR,IF(A.U_LOCATION='',A.U_SUBDIVISION,A.U_LOCATION) AS U_LOCATION,0 AS U_TOTALAREASQM, '' AS U_BLOCK, '' AS U_LOTNO,A.U_TEMPORARY,A.U_ASSVALUE,'' AS U_TCTNO,A.DOCNO,A.U_NOTICENO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'MACHINERY' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_PREVARPNO2, A.U_CADLOTNO, '' as U_SURVEYNO, A.U_BARANGAY, A.U_OLDBARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS,A.U_MARKETVALUE FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ORDER BY U_PREVARPNO2");
                        }
                            while ($objrs->queryfetchrow("NAME")) {
                                $indicator;
                                $objGrid->addrow();
                                $objGrid->setitem(null,"indicator",$indicator);
                                $objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
                                $objGrid->setitem(null,"u_noticeno",$objrs->fields["U_NOTICENO"]);
                                $objGrid->setitem(null,"u_varpno",$objrs->fields["U_VARPNO"]);
                                $objGrid->setitem(null,"u_revisionyear",$objrs->fields["U_REVISIONYEAR"]);
                                $objGrid->setitem(null,"u_tdno",$objrs->fields["U_TDNO"]);
                                $objGrid->setitem(null,"u_pin",$objrs->fields["U_PIN"]);
                                $objGrid->setitem(null,"u_assvalue",formatNumericAmount($objrs->fields["U_ASSVALUE"]));
                                $objGrid->setitem(null,"u_marketvalue",formatNumericAmount($objrs->fields["U_MARKETVALUE"]));
                                $objGrid->setitem(null,"u_tctno",$objrs->fields["U_TCTNO"]);
                                $objGrid->setitem(null,"u_ownername",$objrs->fields["U_OWNERNAME"]);
                                $objGrid->setitem(null,"u_adminname",$objrs->fields["U_ADMINNAME"]);
                                $objGrid->setitem(null,"u_prevtdno",$objrs->fields["U_PREVTDNO"]);
                                $objGrid->setitem(null,"u_prevarpno2",$objrs->fields["U_PREVARPNO2"]);
                                $objGrid->setitem(null,"u_surveyno",$objrs->fields["U_SURVEYNO"]);
                                $objGrid->setitem(null,"u_barangay",$objrs->fields["U_BARANGAY"]);
                                $objGrid->setitem(null,"u_oldbarangay",$objrs->fields["U_OLDBARANGAY"]);
                                $objGrid->setitem(null,"u_cadlotno",$objrs->fields["U_CADLOTNO"]);
                                $objGrid->setitem(null,"u_location",$objrs->fields["U_LOCATION"]);
                                $objGrid->setitem(null,"u_sqm",formatNumericAmount($objrs->fields["U_TOTALAREASQM"]));
                                $objGrid->setitem(null,"u_lot",$objrs->fields["U_LOTNO"]);
                                $objGrid->setitem(null,"u_block",$objrs->fields["U_BLOCK"]);
                                $objGrid->setitem(null,"u_municipality",$objrs->fields["U_MUNICIPALITY"]);
                                $objGrid->setitem(null,"u_city",$objrs->fields["U_CITY"]);
                                $objGrid->setitem(null,"u_province",$objrs->fields["U_PROVINCE"]);
                                $objGrid->setitem(null,"u_kind",$objrs->fields["U_KIND"]);
                                $objGrid->setitem(null,"docstatus",iif($objrs->fields["U_CANCELLED"]==1,"Cancelled",$objrs->fields["DOCSTATUS"]));
                                if ($objrs->fields["DOCSTATUS"]!="Encoding" && $objrs->fields["U_CANCELLED"]==0) {
                                        $objGrid->setitem(null,"action","New Faas");
                                }

                                if( $objrs->fields["U_TEMPORARY"]==1){
                                    $indicator="Temporary";
                                }else if($objrs->fields["U_CANCELLED"]==1){
                                    $indicator="Cancelled";
                                }else{
                                    $indicator="Active";
                                }

                                $objGrid->setitem(null,"indicator",$indicator);
                                $objGrid->setkey(null,$objrs->fields["DOCNO"]); 
                                if (!$page->paging_fetch()) break;
                            }
                }
        }
            
			
	//}
	
//	resetTabindex();
//	setTabindex($schema["u_varpno"]);		
//	$page->resize->addgrid("T1",10,220,false);
//	$page->toolbar->setaction("print",false);
        $page->resize->addgridobject($objGrid,10,250,false);
	
	$rptcols = 6; 
        $indicator = '';
        function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
            global $page;
            global $objGrid;
            global $indicator;
            
		switch ($column) {
                    
			case "indicator":
                                $indicator = $label;
				switch ($label) {
                                        case "Cancelled": $style="background-color:#d13d3d"; break;
					case "Temporary": $style="background-color:#3369FF";break;
					case "Active": $style="background-color:#39383b";break;
//                                        default : $style="background-color:#39383b";break;
				}
                                if($column == 'indicator') $label="&nbsp&nbsp";
                              
				break;
                                
                        default :
                            switch ($indicator){
                                    case "Cancelled": $style="color:#d13d3d"; break;
                                    case "Temporary": $style="color:#3369FF";break;
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
		focusInput(getInput("u_varpno"));
		//"u_street");
	}

	function onFormSubmit(action) {
                switch (action) {
                    case "checkprinted":
                            setKey("keys",getTableInput("T100","docno")+"`"+getTableInput("T100","checkno"));
                            hidePopupFrame('popupFrameCheckPrintedStatus');
                    break;
                    case "u_approved":
                            if (isInputEmpty("u_revisionyear")) return false;
                            if (getTableSelectedRow("T1")==0) {
                                page.statusbar.showWarning("No selected Faas.");
                                return false;
                            }
                            var none=true;
                            if(getTableRowCount("T1")>0) {
                                    for(i=1;i<=getTableRowCount("T1");i++) {
                                            if(isTableInputChecked("T1","chxbox",i)){
                                                    none = false; 
                                            }
                                    }
                                    if(none) {
                                            setStatusMsg("Please select item(s) to process."); 
                                            return false;				
                                    }
                            }
                    break;
                    case "check":
                            if(getTableRowCount("T1")>0) {
                                    if(getInput("chxstat")=="0") setInput("chxstat","1");
                                    else if(getInput("chxstat")=="1") setInput("chxstat","0");

                                    for(i=1;i<=getTableRowCount("T1");i++) {
                                            if(getInput("chxstat")=="1") {
                                                    checkedTableInput("T1","chxbox",i,true); 
                                            }else if(getInput("chxstat")=="0") {
                                                    uncheckedTableInput("T1","chxbox",i,true); 
                                            }
                                            onElementClick(getTableInput("T1","chxbox",i),"chxbox","T1",i);
                                    }
                            }
                        return false;
                        break;
                   default:
                        if (isInputEmpty("u_revisionyear")) return false;
                        break;
                }
		return true;
	}


        function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				//if (getInput("opt")=="Print") {
				//	setTimeout("OpenReportSelect('printer')",100);
				//} else {
                                if (getTableInput("T1","docno",p_rowIdx)!="") {
					setKey("keys",getTableInput("T1","docno",p_rowIdx),p_rowIdx);
					switch (getTableInput("T1","u_kind",p_rowIdx)) {
						case "LAND":
							OpenPopup(1460,800,"./udo.php?&objectcode=u_rpfaas1&sf_keys="+getTableKey("T1","keys",p_rowIdx)+"&formAction=e","UpdPays");	
                                                        break;
						case "BUILDING":
							OpenPopup(1460,800,"./udo.php?&objectcode=u_rpfaas2&sf_keys="+getTableKey("T1","keys",p_rowIdx)+"&formAction=e","UpdPays");
							break;
						case "MACHINERY":
                                                        OpenPopup(1460,800,"./udo.php?&objectcode=u_rpfaas3&sf_keys="+getTableKey("T1","keys",p_rowIdx)+"&formAction=e","UpdPays");
                                                        break;
					}
                                }else page.statusbar.showWarning("No application record for the selected Faas.");
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
	
	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
                                        case "chxbox":
                                            if (row==0) {
                                                if (isTableInputChecked(table,column,row)) { 
//                                                        checkedTableInput(table,column,-1);
                                                } else {
                                                }
                                            }	
                                        break;
				}	
				break;
		}
	}

	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				/*if (isTableInputChecked(table,"chxbox",row)) {
					focusTableInput(table,"depredate",row,500);				
				}*/	
				break;
		}		
		return params;
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
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;RPT For Approval List&nbsp;</td>
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
                <td width="180" ><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
                <td width="805" align=left ><select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></td>
		<td width="805" align=left></td>
	</tr>
        <tr>    
                <td><label <?php genCaptionHtml($schema["u_kind"],"") ?>>Property Type</label></td>
		<td align=left valign="bottom"><select <?php genSelectHtml($schema["u_kind"],array("loadenumkindofproperty","",":[All]")) ?> ></select></td>
		<td >&nbsp;</td>
	</tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_barangay"],"") ?>>Barangay</label></td>
		<td align=left valign="bottom"><select <?php genSelectHtml($schema["u_barangay"],array("loadudflinktable","u_barangays:name:name",":[All]")) ?> ></select></td>
		<td >&nbsp;</td>
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_oldbarangay"],"") ?>>OLD Barangay</label></td>
		<td align=left valign="bottom"><select <?php genSelectHtml($schema["u_oldbarangay"],array("loadudflinktable","u_oldbarangays:code:name",":[All]")) ?> ></select></td>
		<td >&nbsp;</td>
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_revisionyear"],"") ?>>Revision Year</label></td>
                <td align=left valign="bottom"><select <?php genSelectHtml($schema["u_revisionyear"],array("loadudflinktable","u_gryears:code:name",":[All]")) ?> ></select></td>
                <td >&nbsp;</td>
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_prevarpnofrom"],"") ?>>Previous ARP From</label></td>
                <td align=left valign="bottom"><input type="text" size="25"<?php genInputTextHtml($schema["u_prevarpnofrom"]) ?> /></td>
                <td >&nbsp;</td>
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_prevarpnoto"],"") ?>>Previous ARP To</label></td>
                <td align=left valign="bottom"><input type="text" size="25"<?php genInputTextHtml($schema["u_prevarpnoto"]) ?> /></td>
                <td >&nbsp;</td>
        </tr>
        <tr class="fillerRow10px"><td></td></tr>
        <tr> 
                <td width="180">&nbsp;</td>
                <td width="805" align=left><a class="button" href="" onClick="<?php echo @$toolbarframe ?>formEntry();return false;">Retrieve</a></td>
                <td width="805" align=left></td>
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
<tr class="fillerRow10px">
	<td width="100%" align=left><a class="button" href="" onClick="<?php echo @$toolbarframe ?>formSubmit('check');return false;">Check / Uncheck All</a></td>
	<input type="hidden" readonly="readonly" size=5 name="df_chxstat" id="df_chxstat" value="1" />
</tr>

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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_GenericListToolbarapproved.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
