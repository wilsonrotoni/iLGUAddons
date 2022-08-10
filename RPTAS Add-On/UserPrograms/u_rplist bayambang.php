<?php
	

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_rplist";
        
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
	
	$page->objectcode = "U_RPLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_rplist";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Business Permit Application";
	$page->reportlayouts = true;
	$page->getsettings();
        $page->settings->setschema($boschema_checkprinting);

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_maxrowcount"] = createSchemaUpper("u_maxrowcount");
	$schema["u_varpno"] = createSchemaUpper("u_varpno");
	$schema["u_tdno"] = createSchemaUpper("u_tdno");
	$schema["u_pin"] = createSchemaUpper("u_pin");
	$schema["u_tctno"] = createSchemaUpper("u_tctno");
	$schema["u_ownername"] = createSchemaUpper("u_ownername");
	$schema["u_prevtdno"] = createSchemaUpper("u_prevtdno");
	$schema["u_surveyno"] = createSchemaUpper("u_surveyno");
	$schema["u_taxclass"] = createSchemaUpper("u_taxclass");
	$schema["u_kind"] = createSchemaUpper("u_kind");
	$schema["u_adminname"] = createSchemaUpper("u_adminname");
	$schema["u_barangay"] = createSchemaUpper("u_barangay");
	$schema["u_municipality"] = createSchemaUpper("u_municipality");
	$schema["u_cadlotno"] = createSchemaUpper("u_cadlotno");
	$schema["u_revisionyear"] = createSchemaUpper("u_revisionyear");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	
	$objGrid = new grid("T1",$httpVars);
        
        $objGrid->addcolumn("indicator");
        $objGrid->addcolumn("action");
//        $objGrid->addcolumn("print");
	$objGrid->addcolumn("docstatus");
	$objGrid->addcolumn("u_type");
	$objGrid->addcolumn("u_kind");
	$objGrid->addcolumn("u_varpno");
        $objGrid->addcolumn("u_tdno");
        $objGrid->addcolumn("u_ownername");
	$objGrid->addcolumn("u_barangay");
	$objGrid->addcolumn("u_assvalue");
	$objGrid->addcolumn("u_marketvalue");
	$objGrid->addcolumn("u_tctno");
	$objGrid->addcolumn("u_pin");
	$objGrid->addcolumn("u_surveyno");
	$objGrid->addcolumn("u_cadlotno");
	$objGrid->addcolumn("u_sqm");
	$objGrid->addcolumn("u_lot");
	$objGrid->addcolumn("u_block");
	$objGrid->addcolumn("u_adminname");
	$objGrid->addcolumn("u_effyear");
	$objGrid->addcolumn("u_bilyear");
	$objGrid->addcolumn("u_assdate");
	$objGrid->addcolumn("u_createddate");
	$objGrid->addcolumn("u_createdby");
	$objGrid->addcolumn("u_prevtdno");
	$objGrid->addcolumn("u_municipality");
	$objGrid->addcolumn("u_city");
	$objGrid->addcolumn("u_province");
	$objGrid->addcolumn("docno");
        
        $objGrid->columntitle("indicator","*");
	$objGrid->columntitle("docno","ID No");
	$objGrid->columntitle("u_noticeno","Notice No");
	$objGrid->columntitle("u_varpno","ARP No");
	$objGrid->columntitle("u_revisionyear","GR Year");
	$objGrid->columntitle("u_tdno","TD Number");
	$objGrid->columntitle("u_pin","PIN");
	$objGrid->columntitle("u_assvalue","Assessed Value");
	$objGrid->columntitle("u_marketvalue","Market Value");
	$objGrid->columntitle("u_tctno","Title Number");
	$objGrid->columntitle("u_kind","Kind");
	$objGrid->columntitle("u_ownername","Owner Name");
	$objGrid->columntitle("u_adminname","Administrator Name");
	$objGrid->columntitle("u_prevtdno","Previous TD");
	$objGrid->columntitle("u_prevarpno2","Previous ARP No");
	$objGrid->columntitle("u_surveyno","Survey No");
	$objGrid->columntitle("u_barangay","Barangay");
	$objGrid->columntitle("u_oldbarangay","Old Brgy Code");
	$objGrid->columntitle("u_cadlotno","Cad Lot No");
	$objGrid->columntitle("u_sqm","Area(Sqm)");
	$objGrid->columntitle("u_lot","Lot No");
	$objGrid->columntitle("u_block","Block No");
	$objGrid->columntitle("u_municipality","Municipality");
	$objGrid->columntitle("u_city","City");
	$objGrid->columntitle("u_province","Province");
	$objGrid->columntitle("docstatus","Status");
	$objGrid->columntitle("u_effyear","Effectivity Year");
	$objGrid->columntitle("u_bilyear","Paid Year");
	$objGrid->columntitle("u_assdate","Assessment Date");
	$objGrid->columntitle("u_createddate","Encoded Date");
	$objGrid->columntitle("u_createdby","Encoded By");
	$objGrid->columntitle("u_type","Type");
        
	$objGrid->columnwidth("indicator",1);
	$objGrid->columnwidth("docno",10);
	$objGrid->columnwidth("u_noticeno",10);
	$objGrid->columnwidth("u_varpno",15);
	$objGrid->columnwidth("u_revisionyear",8);
	$objGrid->columnwidth("u_tdno",15);
	$objGrid->columnwidth("u_pin",20);
	$objGrid->columnwidth("u_assvalue",15);
	$objGrid->columnwidth("u_marketvalue",15);
	$objGrid->columnwidth("u_tctno",15);
	$objGrid->columnwidth("u_kind",8);
	$objGrid->columnwidth("u_ownername",50);
	$objGrid->columnwidth("u_adminname",30);
	$objGrid->columnwidth("u_prevtdno",15);
	$objGrid->columnwidth("u_prevarpno2",15);
	$objGrid->columnwidth("u_surveyno",15);
	$objGrid->columnwidth("u_barangay",15);
	$objGrid->columnwidth("u_oldbarangay",12);
	$objGrid->columnwidth("u_cadlotno",50);
	$objGrid->columnwidth("u_sqm",15);
	$objGrid->columnwidth("u_lot",15);
	$objGrid->columnwidth("u_block",15);
	$objGrid->columnwidth("u_municipality",15);
	$objGrid->columnwidth("u_city",15);
	$objGrid->columnwidth("u_province",15);
	$objGrid->columnwidth("docstatus",12);
	$objGrid->columnwidth("u_effyear",15);
	$objGrid->columnwidth("u_bilyear",12);
	$objGrid->columnwidth("u_assdate",15);
	$objGrid->columnwidth("u_createddate",15);
	$objGrid->columnwidth("u_createdby",15);
	$objGrid->columnwidth("u_type",12);
        
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_tdno",true);
	$objGrid->columnsortable("u_cadlotno",true);
	$objGrid->columnsortable("u_varpno",true);
	$objGrid->columnsortable("u_pin",true);
	$objGrid->columnsortable("u_tctno",true);
	$objGrid->columnsortable("u_ownername",true);
	$objGrid->columnsortable("u_adminname",true);
	$objGrid->columnsortable("u_prevtdno",true);
	$objGrid->columnsortable("u_prevarpno2",true);
	$objGrid->columnsortable("u_surveyno",true);
	$objGrid->columnsortable("u_barangay",true);
	$objGrid->columnsortable("u_oldbarangay",true);
	$objGrid->columnsortable("u_municipality",true);
	$objGrid->columnsortable("u_city",true);
	$objGrid->columnsortable("u_province",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columnsortable("u_noticeno",true);
        
	$objGrid->columnalignment("indicator","center");
	$objGrid->columnalignment("u_assvalue","right");
	$objGrid->columnalignment("u_marketvalue","right");
	$objGrid->columnalignment("u_bilyear","right");
	$objGrid->columnalignment("u_effyear","right");
        
	
//	$objGrid->columntitle("print","");
//	$objGrid->columnalignment("print","center");
//	$objGrid->columninput("print","type","link");
//	$objGrid->columninput("print","caption","Print Faas/TD");
//	$objGrid->columnwidth("print",10);
//	$objGrid->columnvisibility("print",true);
        
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
        $objGrid->selectionmode = 2;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_tdno";
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
	//$filterExp = genSQLFilterDate("B.U_APPDATE",$filterExp,$httpVars['df_u_arpno']);
	if ($page->getitemstring("docstatus")=="Active") {
		$filterExp = genSQLFilterNumeric("A.U_CANCELLED",$filterExp,'0');
		//$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,'Assessed,Recommended,Approved',null,true);
	} elseif ($page->getitemstring("docstatus")=="Cancelled") {
		$filterExp = genSQLFilterNumeric("A.U_CANCELLED",$filterExp,'1');
	} else {
		$filterExp = genSQLFilterString("A.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	}
	$filterExp = genSQLFilterString("A.U_VARPNO",$filterExp,$httpVars['df_u_varpno']);
	$filterExp = genSQLFilterString("A.U_TDNO",$filterExp,$httpVars['df_u_tdno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_PIN",$filterExp,$httpVars['df_u_pin'],null,null,true);
	$filterExp = genSQLFilterString("A.U_TCTNO",$filterExp,$httpVars['df_u_tctno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_OWNERNAME",$filterExp,$httpVars['df_u_ownername'],null,null,true);
	$filterExp = genSQLFilterString("A.U_ADMINNAME",$filterExp,$httpVars['df_u_adminname'],null,null,true);
	$filterExp = genSQLFilterString("A.U_PREVTDNO",$filterExp,$httpVars['df_u_prevtdno'],null,null,true);
//	$filterExp = genSQLFilterString("A.U_PREVARPNO2",$filterExp,$httpVars['df_u_prevarpno2']);
	$filterExp = genSQLFilterString("A.U_SURVEYNO",$filterExp,$httpVars['df_u_surveyno'],null,null,true);
	$filterExp = genSQLFilterString("A.U_BARANGAY",$filterExp,$httpVars['df_u_barangay']);
//	$filterExp = genSQLFilterString("A.U_OLDBARANGAY",$filterExp,$httpVars['df_u_oldbarangay']);
	//$filterExp = genSQLFilterString("A.U_MUNICIPALITY",$filterExp,$httpVars['df_u_municipality']);
	$filterExp = genSQLFilterString("A.U_CADLOTNO",$filterExp,$httpVars['df_u_cadlotno'],null,null,true);
	//$filterExp = genSQLFilterString("A.U_LOCATION",$filterExp,$httpVars['df_u_location'],null,null,true);
//	$filterExp = genSQLFilterString("A.U_REVISIONYEAR",$filterExp,$httpVars['df_u_revisionyear'],null,null,false);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
	//$objrs->setdebug();
	if ($page->getitemstring("u_adminname")!="") {
		$search=true;
	}
	if ($page->getitemstring("u_ownername")!="" || $page->getitemstring("u_adminname")!="" || $page->getitemstring("u_barangay")!=""  || $page->getitemstring("u_tctno")!="" || $page->getitemstring("u_prevtdno")!="" || $page->getitemstring("u_surveyno")!="" || $page->getitemstring("u_pin")!="" || $page->getitemstring("u_varpno")!="" || $page->getitemstring("u_cadlotno")!="" || $page->getitemstring("u_tdno")!="" ) {
          if ($page->getitemstring("u_kind")=="") {
                    $objrs->queryopenext("SELECT A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,A.U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, A.U_KIND, A.U_OWNERNAME, A.U_ADMINNAME, A.U_PREVTDNO, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM( SELECT A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,A.U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp  UNION ALL select A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,A.U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) AS U_PIN, 'BUILDING' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp UNION ALL select A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,0 AS U_TOTALAREASQM,'' AS U_BLOCK,'' AS U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,'' AS U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) AS U_PIN, 'MACHINERY' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_CADLOTNO, '' as U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp ) AS A", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            } elseif ($page->getitemstring("u_kind")=="L") {
					$objrs->queryopenext("select A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,A.U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            } elseif ($page->getitemstring("u_kind")=="B") {
                    $objrs->queryopenext("select A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,A.U_TOTALAREASQM,A.U_BLOCK,A.U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,A.U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'BUILDING' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_CADLOTNO, A.U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            } elseif ($page->getitemstring("u_kind")=="M") {
                    $objrs->queryopenext("select A.U_BILYEAR,A.U_TAXABLE,A.U_EFFYEAR,A.U_ASSESSEDDATE,A.DATECREATED,A.CREATEDBY,0 AS U_TOTALAREASQM, '' AS U_BLOCK, '' AS U_LOTNO,A.U_ASSVALUE,A.U_MARKETVALUE,'' AS U_TCTNO,A.DOCNO,A.U_VARPNO, A.U_TDNO, if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'MACHINERY' AS U_KIND, A.U_OWNERNAME, A.U_ADMINNAME,A.U_PREVTDNO, A.U_CADLOTNO, '' as U_SURVEYNO, A.U_BARANGAY, A.U_MUNICIPALITY, A.U_CITY, A.U_PROVINCE, A.U_CANCELLED, A.DOCSTATUS FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            }
        }
		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			/*if ($search) {
				if ($page->getitemstring("u_adminname")==$objrs->fields["NAME"]) {
					$match=true;
					$matchstatus=$objrs->fields["DOCSTATUS"];
				}
			}*/
                       $indicator;
                       
			$objGrid->addrow();
                        
			//if ($objrs->fields["U_EXPIRED"]==1) $indicator="Expired";
			$objGrid->setitem(null,"indicator",$indicator);
			$objGrid->setitem(null,"docno",$objrs->fields["DOCNO"]);
			$objGrid->setitem(null,"u_varpno",$objrs->fields["U_VARPNO"]);
			$objGrid->setitem(null,"u_tdno",$objrs->fields["U_TDNO"]);
			$objGrid->setitem(null,"u_pin",$objrs->fields["U_PIN"]);
			$objGrid->setitem(null,"u_assvalue",formatNumericAmount($objrs->fields["U_ASSVALUE"]));
			$objGrid->setitem(null,"u_marketvalue",formatNumericAmount($objrs->fields["U_MARKETVALUE"]));
			$objGrid->setitem(null,"u_tctno",$objrs->fields["U_TCTNO"]);
			$objGrid->setitem(null,"u_ownername",$objrs->fields["U_OWNERNAME"]);
			$objGrid->setitem(null,"u_adminname",$objrs->fields["U_ADMINNAME"]);
			$objGrid->setitem(null,"u_prevtdno",$objrs->fields["U_PREVTDNO"]);
			$objGrid->setitem(null,"u_surveyno",$objrs->fields["U_SURVEYNO"]);
			$objGrid->setitem(null,"u_barangay",$objrs->fields["U_BARANGAY"]);
			$objGrid->setitem(null,"u_cadlotno",$objrs->fields["U_CADLOTNO"]);
			$objGrid->setitem(null,"u_sqm",$objrs->fields["U_TOTALAREASQM"]);
			$objGrid->setitem(null,"u_lot",$objrs->fields["U_LOTNO"]);
			$objGrid->setitem(null,"u_block",$objrs->fields["U_BLOCK"]);
			$objGrid->setitem(null,"u_municipality",$objrs->fields["U_MUNICIPALITY"]);
			$objGrid->setitem(null,"u_city",$objrs->fields["U_CITY"]);
			$objGrid->setitem(null,"u_province",$objrs->fields["U_PROVINCE"]);
			$objGrid->setitem(null,"u_kind",$objrs->fields["U_KIND"]);
			$objGrid->setitem(null,"u_type",iif($objrs->fields["U_TAXABLE"]==1,"Taxable","Exempt"));
			$objGrid->setitem(null,"u_bilyear",$objrs->fields["U_BILYEAR"]);
			$objGrid->setitem(null,"u_effyear",$objrs->fields["U_EFFYEAR"]);
			$objGrid->setitem(null,"u_assdate",$objrs->fields["U_ASSESSEDDATE"]);
			$objGrid->setitem(null,"u_createddate",$objrs->fields["DATECREATED"]);
			$objGrid->setitem(null,"u_createdby",$objrs->fields["CREATEDBY"]);
			$objGrid->setitem(null,"docstatus",iif($objrs->fields["U_CANCELLED"]==1,"Cancelled",$objrs->fields["DOCSTATUS"]));
			if ($objrs->fields["DOCSTATUS"]!="Encoding" && $objrs->fields["U_CANCELLED"]==0) {
				$objGrid->setitem(null,"action","New Faas");
			}
                        
                        if($objrs->fields["U_CANCELLED"]==1){
                            $indicator="Cancelled";
                        }else{
                            $indicator="Active";
                        }
                        
                        $objGrid->setitem(null,"indicator",$indicator);
			$objGrid->setkey(null,$objrs->fields["DOCNO"]); 
			if (!$page->paging_fetch()) break;
		}	
	//}
	
	resetTabindex();
	setTabindex($schema["u_varpno"]);		
	$page->resize->addgrid("T1",10,260,false);
	$page->toolbar->setaction("print",false);
	
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
					case "Active": $style="background-color:#39383b";break;
//                                        default : $style="background-color:#39383b";break;
				}
                                if($column == 'indicator') $label="&nbsp&nbsp";
                              
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
			page.statusbar.showWarning("No selected Faas.");
			return false;
		}
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
               
		return true;
	}
	
	function onReportGetParams(p_formattype,p_params) {
                //alert(p_formattype);
		var params = new Array();
                var rc =  getTableRowCount("T1"),selectedcnt = 0,cnt = 0;
                var docno = '';
                var refno = '';
                for (i = 1; i <= rc; i++) {
                    if(getTableInput("T1","rowstat",i) != "X") {
                            if(getTableInput("T1","checked",i)=="1") {
                                    selectedcnt += 1;
                            }
                    }
                }
                
                for (iii = 1; iii <= rc; iii++) {
                    if(getTableInput("T1","rowstat",iii) != "X") {
                            if(getTableInput("T1","checked",iii)=="1") {
                                    cnt += 1;
                                    if (cnt == selectedcnt) {
                                       docno = docno + getTableInput("T1","docno",iii);
									   refno = refno + getTableInput("T1","u_tdno",iii);
                                    } else {
                                       docno = docno + getTableInput("T1","docno",iii) + ",";
									   refno = refno + getTableInput("T1","u_tdno",iii) + ",";
                                    }
                            }
                    }
                }
		if (p_params!=null) params = p_params;
		params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);

		params["source"] = "aspx";
		//params["dbtype"] = "mysql";
		//params["reportaction"] = getInput("reportaction");
		//params["action"] = "processReport.aspx"
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			params["querystring"] += generateQueryString("docno",docno);
			params["querystring"] += generateQueryString("refno",refno);
		}
//                switch (getTableInput("T1","u_kind",getTableSelectedRow("T1"))) {
//                        case "LAND":
//                                params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\LGU Bacoor Add-On\\UserRpts\\RPTAS\\u_rpfaas1.rpt"; 
//                                break;
//                        case "BUILDING":
//                                params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\LGU Bacoor Add-On\\UserRpts\\RPTAS\\u_rpfaas2.rpt"; 
//                                break;
//                        case "MACHINERY":
//                                params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\LGU Bacoor Add-On\\UserRpts\\RPTAS\\u_rpfaas3.rpt"; 
//                                break;
//                }
		// params["recordselectionformula"]="{u_rpfaas1.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}

	function onReportReturn(p_formattype) {
		var row = getTableSelectedRow("T1");
	//	page.statusbar.showWarning("previewed");
		setTableInput("T100","docno",getTableInput("T1","docno",row));
		setTableInput("T100","u_adminname",getTableInput("T1","u_adminname",row));
		setTableInput("T100","u_surveyno",getTableInput("T1","u_surveyno",row));
		setTableInput("T100","u_barangay",getTableInput("T1","u_barangay",row));
		setTableInput("T100","u_municipality",getTableInput("T1","u_municipality",row));
		//showPopupFrame('popupFrameCheckPrintedStatus',true);
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
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS1");
							break;
						case "BUILDING":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS2");
							break;
						case "MACHINERY":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS3");
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
			case "u_revisionyear": 
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
			case "u_tctno": 
			case "u_ownername": 
			case "u_adminname": 
			case "u_prevtdno": 
			case "u_surveyno": 
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
			case "df_u_tctno":
			case "df_u_taxclass":
			case "df_u_ownername":
			case "df_u_kind":
			case "df_u_adminname":
			case "df_u_prevtdno":
			case "df_u_surveyno":
			case "df_u_barangay":
			case "df_u_oldbarangay":
			case "df_u_municipality":
			case "df_u_cadlotno":
			case "df_u_varpno":
			case "df_u_tdno":
                        case "u_revisionyear": 
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
			inputs.push("u_tctno");
			inputs.push("u_ownername");
			inputs.push("u_adminname");
			inputs.push("u_prevtdno");
			inputs.push("u_surveyno");
			inputs.push("u_barangay");
			inputs.push("u_oldbarangay");
			inputs.push("u_municipality");
			inputs.push("u_cadlotno");
			inputs.push("u_province");
			inputs.push("docstatus");
			inputs.push("u_kind");
                        inputs.push("u_revisionyear");
			
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
                if(getInput("u_ownername")=="" && getInput("u_adminname")=="" &&  getInput("u_barangay")=="" &&  getInput("u_oldbarangay")=="" &&  getInput("u_tctno")=="" &&  getInput("u_prevtdno")=="" &&  getInput("u_prevarpno2")=="" && getInput("u_surveyno")=="" && getInput("u_pin")=="" && getInput("u_varpno")=="" && getInput("u_cadlotno")==""){
                    page.statusbar.showError("Please input atleast one filter");	
                    return false;
                }
                    
		formSearch('','<?php echo $page->paging->formid; ?>');
	}
	function formClearFields() {
		setInput("docstatus","");
		setInput("u_kind","");
		setInput("u_ownername","");
		setInput("u_adminname","");
		setInput("u_barangay","");
		setInput("u_tctno","");
		setInput("u_prevtdno","");
		setInput("u_tdno","");
		setInput("u_surveyno","");
		setInput("u_pin","");
		setInput("u_varpno","");
		setInput("u_cadlotno","");
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_varpno":
			case "u_tdno":
			case "u_pin":
			case "u_tctno":
			case "u_ownername":
			case "u_adminname":
			case "u_prevtdno":
			case "u_surveyno":
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
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Real Property Tax List&nbsp;</td>
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
                <td width="300" ><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
                <td width="700" align=left ><select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]")) ?> ></td>
		<td width="300" ><label <?php genCaptionHtml($schema["u_prevtdno"],"") ?>>Previous TD No</label></td>
		<td width="500" align=left>&nbsp;<input type="text" size="25"<?php genInputTextHtml($schema["u_prevtdno"]) ?> /></td>
                <td width="150" align="center"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Legend:</label></td>
                <td width="300" align=center bgcolor="Black"><font size="2px" color="white">Active</font></td>
                <td width="50" >&nbsp;</td>
	</tr>
        <tr>    
                <td><label <?php genCaptionHtml($schema["u_kind"],"") ?>>Property Type</label></td>
		<td><select <?php genSelectHtml($schema["u_kind"],array("loadenumkindofproperty","",":[All]")) ?> ></select></td>
		<td><label <?php genCaptionHtml($schema["u_surveyno"],"") ?>>Survey No</label></td>
		<td>&nbsp;<input type="text" size="25"<?php genInputTextHtml($schema["u_surveyno"]) ?> /></td>
                <td >&nbsp;</td>
                <td bgcolor="#d13d3d" align=center><font size="2px" color="white">Cancelled</font></td>
	</tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_ownername"],"") ?>>Company/Owner Name/s</label></td>
                <td><input type="text" size="35" <?php genInputTextHtml($schema["u_ownername"]) ?> /></td>  
		<td><label <?php genCaptionHtml($schema["u_pin"],"") ?>>PIN</label></td>
		<td>&nbsp;<input type="text" size ="25" <?php genInputTextHtml($schema["u_pin"]) ?> /></td>
                <td >&nbsp;</td>
                <td  align=center></td>
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_adminname"],"") ?>>Administrator Name</label></td>
		<td><input type="text" size="40" <?php genInputTextHtml($schema["u_adminname"]) ?> /></td>
		<td><label <?php genCaptionHtml($schema["u_varpno"],"") ?>>ARP Number</label></td>
		<td>&nbsp;<input type="text" size ="25"<?php genInputTextHtml($schema["u_varpno"]) ?> /></td>
               
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_barangay"],"") ?>>Barangay</label></td>
		<td><select <?php genSelectHtml($schema["u_barangay"],array("loadudflinktable","u_barangays:name:name",":[All]")) ?> ></select></td>
		<td><label <?php genCaptionHtml($schema["u_cadlotno"],"") ?>>Cad Lot No</label></td>
		<td>&nbsp;<input type="text" size ="25"<?php genInputTextHtml($schema["u_cadlotno"]) ?> /></td>
        </tr>
        <tr>    
		
        </tr>
        <tr>    <td><label <?php genCaptionHtml($schema["u_tctno"],"") ?>>TCT Number</label></td>
                <td><input type="text" size="15"<?php genInputTextHtml($schema["u_tctno"]) ?> /></td>
                <td><label <?php genCaptionHtml($schema["u_tdno"],"") ?>>TD Number</label></td>
		<td>&nbsp;<input type="text" size ="25"<?php genInputTextHtml($schema["u_tdno"]) ?> /></td>
        </tr>
        <tr>
            <td ></td>
            <td >&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a> &nbsp;&nbsp;&nbsp;<a class="button" href="" onClick="formClearFields();return false;">Clear</a></td>
            <td></td>
            <td>&nbsp;</td>
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
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_ownerfirstname"],"T100") ?> >Last Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_ownerfirstname"],"T100") ?> /></td>
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
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("U_GenericListToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
