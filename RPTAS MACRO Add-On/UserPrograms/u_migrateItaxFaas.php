<?php
	$progid = "u_migrateitaxFaas";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
//	include_once("./inc/formaccess.php");
        
        $enumkindofproperty = array();
	$enumkindofproperty["L"]="Land";
	$enumkindofproperty["B"]="Building and Other Structure";
	$enumkindofproperty["M"]="Machinery";

	$search=false;
	$retired = false;
	$page->restoreSavedValues();
        
        $page->objectcode = "U_MIGRATEITAXFAAS";
	$page->paging->formid = "./UDP.php?&objectcode=U_MIGRATEITAXFAAS";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "RPT Migrate iTax Faas";
        
        $schema["u_approveddatefr"] = createSchemaDate("u_approveddatefr");
	$schema["u_approveddateto"] = createSchemaDate("u_approveddateto");
	$schema["u_barangay"] = createSchema("u_barangay");
	$schema["u_kind"] = createSchemaUpper("u_kind");
	$schema["u_effyear"] = createSchemaUpper("u_effyear");
	$schema["u_curtotalrecord"] = createSchemaUpper("u_curtotalrecord");
	$schema["u_itaxtotalrecord"] = createSchemaUpper("u_itaxtotalrecord");
        
        $schema["u_curtotalrecord"]["attributes"] = "disabled";
        $schema["u_itaxtotalrecord"]["attributes"] = "disabled";
        
        $objGridA = new grid("T1",$httpVars);
	$objGridA->addcolumn("u_tdno");
        $objGridA->addcolumn("u_kind");
	$objGridA->addcolumn("u_pin");
	$objGridA->addcolumn("u_effyear");
	$objGridA->addcolumn("u_ownername");
	$objGridA->addcolumn("u_barangay");
	$objGridA->addcolumn("u_approveddate");
	$objGridA->addcolumn("u_cancel");
	
	$objGridA->columntitle("u_kind","Kind");
	$objGridA->columntitle("u_tdno","Tax Dec #");
	$objGridA->columntitle("u_pin","PIN");
	$objGridA->columntitle("u_effyear","Effective Year");
	$objGridA->columntitle("u_ownername","Ownername");
	$objGridA->columntitle("u_barangay","Barangay");
	$objGridA->columntitle("u_approveddate","Date Approved");
	$objGridA->columntitle("u_cancel","Cancel");
        
	$objGridA->columnwidth("u_kind",10);
	$objGridA->columnwidth("u_tdno",20);
	$objGridA->columnwidth("u_pin",20);
	$objGridA->columnwidth("u_effyear",10);
	$objGridA->columnwidth("u_ownername",50);
	$objGridA->columnwidth("u_barangay",15);
	$objGridA->columnwidth("u_approveddate",15);
	$objGridA->columnwidth("u_cancel",10);
        
	$objGridA->columnsortable("u_kind",true);
	$objGridA->columnsortable("u_tdno",true);
	$objGridA->columnsortable("u_pin",true);
	$objGridA->columnsortable("u_effyear",true);
	$objGridA->columnsortable("u_ownername",true);
	$objGridA->columnsortable("u_barangay",true);
	$objGridA->columnsortable("u_approveddate",true);
	$objGridA->columnsortable("u_cancel",true);
        $objGridA->automanagecolumnwidth = true;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "u_tdno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGridA->setsort($lookupSortBy,$lookupSortAs);
        
        $objGridB = new grid("T2",$httpVars);
        $objGridB->addcolumn("u_tdno");
	$objGridB->addcolumn("u_kind");
	$objGridB->addcolumn("u_pin");
	$objGridB->addcolumn("u_effyear");
	$objGridB->addcolumn("u_ownername");
	$objGridB->addcolumn("u_barangay");
	$objGridB->addcolumn("u_approveddate");
	$objGridB->addcolumn("u_cancel");
	
	$objGridB->columntitle("u_kind","Kind");
	$objGridB->columntitle("u_tdno","Tax Dec #");
	$objGridB->columntitle("u_pin","PIN");
	$objGridB->columntitle("u_effyear","Effective Year");
	$objGridB->columntitle("u_ownername","Ownername");
	$objGridB->columntitle("u_barangay","Barangay");
	$objGridB->columntitle("u_approveddate","Approved Date");
	$objGridB->columntitle("u_cancel","Cancel");
        
	$objGridB->columnwidth("u_kind",10);
	$objGridB->columnwidth("u_tdno",20);
	$objGridB->columnwidth("u_pin",20);
	$objGridB->columnwidth("u_effyear",10);
	$objGridB->columnwidth("u_ownername",20);
	$objGridB->columnwidth("u_barangay",15);
	$objGridB->columnwidth("u_approveddate",15);
	$objGridB->columnwidth("u_cancel",10);
        
	$objGridB->columnsortable("u_kind",true);
	$objGridB->columnsortable("u_tdno",true);
	$objGridB->columnsortable("u_pin",true);
	$objGridB->columnsortable("u_effyear",true);
	$objGridB->columnsortable("u_ownername",true);
	$objGridB->columnsortable("u_barangay",true);
	$objGridB->columnsortable("u_approveddate",true);
	$objGridB->columnsortable("u_cancel",true);
	$objGridB->automanagecolumnwidth = true;
	
	if ($lookupSortBy1 == "") {
		$lookupSortBy1 = "u_tdno";
	} else	$lookupSortBy1 = strtolower($lookupSortBy1);
	$objGridB->setsort($lookupSortBy1,$lookupSortAs);
        
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

        function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
                if($action=="migratefaas"){
                    
                    $httpVars = array_merge($_POST,$_GET);
                    $objRs = new recordset(null,$objConnection);

                    $obju_Lands = new masterdataschema(null,$objConnection,"u_rplands");
                    $obju_LandSubclasses = new masterdatalinesschema(null,$objConnection,"u_rplandsubclasses");

                    $obju_LandClasses = new masterdataschema(null,$objConnection,"u_rplandclasses");

                    $obju_ActUses = new masterdataschema(null,$objConnection,"u_rpactuses");
                    $obju_Machineries = new masterdataschema(null,$objConnection,"u_rpmachineries");
                    $obju_Improvements = new masterdataschema(null,$objConnection,"u_rpimprovements");
                    $obju_Improvementfmvs = new masterdatalinesschema(null,$objConnection,"u_rpimprovementfmvs");

                    $obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                    $obju_Faas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                    $obju_Faas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                    $obju_Faas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");

                    $obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
                    $obju_Faas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
                    $obju_Faas2b = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
                    $obju_Faas2d = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
                    $obju_Faas2e = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");

                    $obju_Faas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
                    $obju_Faas3a = new documentschema_br(null,$objConnection,"u_rpfaas3a");
                    $obju_Faas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");

                    $obju_BldgPropGroups = new masterdataschema(null,$objConnection,"u_rpbldgpropgroups");
                    $obju_BldgProps = new masterdataschema(null,$objConnection,"u_rpbldgprops");
                    $obju_BldgExtraItems = new masterdataschema(null,$objConnection,"u_rpbldgextraitems");

                    $obju_BldgStructTypes = new masterdataschema(null,$objConnection,"u_rpbldgstructypes");
                    $obju_BldgKinds = new masterdataschema(null,$objConnection,"u_rpbldgkinds");
                    $obju_BldgClasses = new masterdataschema(null,$objConnection,"u_rpbldgclasses");
                    $obju_BldgStructureClassRates = new masterdataschema(null,$objConnection,"u_rpbldgstructclassrates");

                    $obju_MachTypes = new masterdataschema(null,$objConnection,"u_rpmachtypes");
                    $obju_MachConditions = new masterdataschema(null,$objConnection,"u_rpmachconditions");

                    $obju_Faas1Prev = new documentschema_br(null,$objConnection,"u_rpfaas1");
                    $obju_Faas2Prev = new documentschema_br(null,$objConnection,"u_rpfaas2");
                    $obju_Faas3Prev = new documentschema_br(null,$objConnection,"u_rpfaas3");

                    $objConnection->beginwork();

                    $odbccon = @odbc_connect("itax","sysdba","masterkey",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                        
                    if ($actionReturn) {
                            $filterExp1 = "";
                            $filterExp1 = genSQLFilterDate("A.APPROVEDDATE",$filterExp1,$httpVars['df_u_approveddatefr'],$httpVars['df_u_approveddateto']);
                            $filterExp1 = genSQLFilterString("w.DESCRIPTION",$filterExp1,$httpVars['df_u_barangay'],null,null,true);
                            $filterExp1 = genSQLFilterString("A.STARTYEAR",$filterExp1,$httpVars['df_u_effyear'],null,null,true);
                            if ($page->getitemstring("u_kind")!="") {
                                $filterExp1 = genSQLFilterString("B.PROPERTYKIND_CT",$filterExp1,$httpVars['df_u_kind'],null,null,true);
                            }

                            if ($filterExp1 !="") $filterExp1 = " AND " . $filterExp1;
                            $odbcres = @odbc_exec($odbccon,"SELECT  a.TAXTRANS_ID, a.PREVTAXTRANS_ID, a.PROP_ID, a.TDNO, a.TAXABILITY_BV, a.STARTQUARTER, a.STARTYEAR, a.ENDED_BV, a.ENDQUARTER, a.ENDYEAR, a.CANCELLATIONDATE, a.SUBMISSIONDATE, a.ASSESSEDBY, a.TRANSDATE, 
                                                                    a.USERID, a.SUBMITTEDBY, a.TRANSCODE_CT, a.MEMORANDA, a.APPROVEDBY, a.APPROVEDDATE, a.RECOMAPPROVEDBY, a.RECOMAPPROVEDDATE, a.ASSESSEDDATE, a.GRYEAR, a.ANNOTATION, a.FORAPPROVAL_BV, a.RPTRESTRICTION_CT, 
                                                                    a.PREDOMCLASSCODE_CT, a.PREDOMRPTDETAILID, a.SPECIALSTATUS_CT, a.CHANGEUSERID, a.TMCR_REMARK, a.SWORNSTATEMENT_BV, 
                                                                    b.PROP_ID, b.PREVPROP_ID, b.PINNO, b.NEWPINNO, b.PROPERTYKIND_CT, b.OWNERTYPE_CT, b.CERTIFICATETITLENO, b.CADASTRALLOTNO, b.STREET, b.BARANGAY_CT, w.DESCRIPTION as BARANGAY, b.MUNICIPAL_ID, x.DESCRIPTION as MUNICIPAL, b.PROVINCE_CT, y.DESCRIPTION as PROVINCE, b.EXPIRED_BV, b.EXPIREDYEAR, 
                                                                    b.USERID, b.TRANSDATE, b.LOTNO, b.BOUNDARYNORTH, b.BOUNDARYEAST, b.BOUNDARYSOUTH, b.BOUNDARYWEST, b.PROPSECTION, b.SURVEYNO, b.BLKNO, b.CERTIFICATETITLEDATE, b.PROPERTYREF_ID, b.SKETCH, b.PARCELNO, b.SECTION, 
                                                                    c.PROP_ID, c.LOCAL_TIN, c.VALIDFROM, c.VALIDUNTIL, c.USERID, c.TRANSDATE, d.LOCAL_TIN, d.OWNERNAME, d.OWNERADDRESS, d.FIRSTNAME, d.LASTNAME, d.MI, d.TINNO, d.CITIZENSHIP, d.CIVILSTATUS, d.COUNTRY_CT, d.ACTIVE_BV, d.USERID, d.TRANSDATE, d.DATEOFBIRTH, d.PLACEOFBIRTH, d.HEIGHT, d.WEIGHT, d.OCCUPATION, d.TELNO, d.ORGANIZATIONKIND_CT, 
                                                                    d.DATEREGINCORPORATION, d.PLACEOFINCORPORATION, d.ICRNO, d.EMAIL, d.GENDER, d.MERGE_BV, d.PARENT_TIN, e.NUMSTOREY, e.YEARCONSTRUCTED, e.YEAROCCUPIED, e.USERID, e.TRANSDATE, e.DESCRIPTION, e.PERMITNO, e.PERMITDATE, e.YEARRENOVATED, e.BLDGAGE, e.CCT, e.CCTCOMPISSUED, e.CCTOCCISSUED, e.YEARCOMPLETED
                                                                    FROM RPTASSESSMENT a 
                                                                    LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID 
                                                                    LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID 
                                                                    LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN 
                                                                    LEFT JOIN T_BARANGAY w ON w.CODE=b.BARANGAY_CT AND w.MUNICIPAL_ID=b.MUNICIPAL_ID AND w.PROVINCE_CT=b.PROVINCE_CT 
                                                                    LEFT JOIN T_MUNICIPALITY x ON x.MUNICIPAL_ID=b.MUNICIPAL_ID 
                                                                    LEFT JOIN T_PROVINCE y ON y.CODE=b.PROVINCE_CT 
                                                                    LEFT JOIN RPTBLDGINFO e ON e.TAXTRANS_ID=a.TAXTRANS_ID 
                                                                    WHERE B.PROVINCE_CT = '008' AND B.MUNICIPAL_ID = '13' $filterExp1 
                                                                    ORDER BY a.TAXTRANS_ID") or die("<B>Error!</B> Couldn't Run Query 1:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());

                            while(odbc_fetch_row($odbcres)) {

                            //Build tempory
                            for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                               $field_name = odbc_field_name($odbcres, $j);
                              // $this->temp_fieldnames[$j] = $field_name;
                               $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                            }

                            //var_dump( $ar);
                            if ($ar["PROPERTYKIND_CT"]=="B") {
                                $num_rows++;
                                $obju_Faas2->prepareadd();
                                $obju_Faas2->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                                $obju_Faas2->docseries = -1;
                                $obju_Faas2->docno = $ar["TAXTRANS_ID"];
                                $obju_Faas2->setudfvalue("u_pin",$ar["PINNO"]);
                                $obju_Faas2->setudfvalue("u_prefix",substr($ar["PINNO"],0,strlen($ar["PINNO"])-5));
                                $obju_Faas2->setudfvalue("u_suffix",substr($ar["PINNO"],strlen($ar["PINNO"])-4,4));
                                $obju_Faas2->setudfvalue("u_tdno",$ar["TDNO"]);
                                $obju_Faas2->setudfvalue("u_trxcode",$ar["TRANSCODE_CT"]);

                                //$obju_Faas2->setudfvalue("u_tctno",$ar["CERTIFICATETITLENO"]);
                                $obju_Faas2->setudfvalue("u_surveyno",$ar["SURVEYNO"]);
                                $obju_Faas2->setudfvalue("u_lotno",$ar["LOTNO"]);
                                $obju_Faas2->setudfvalue("u_block",$ar["BLKNO"]);
                                //$obju_Faas2->setudfvalue("u_cadlotno",$ar["CADASTRALLOTNO"]);

                                $obju_Faas2->setudfvalue("u_street",$ar["STREET"]);
                                $obju_Faas2->setudfvalue("u_barangay",strtoupper($ar["BARANGAY"]));
                                $obju_Faas2->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                $obju_Faas2->setudfvalue("u_province",$ar["PROVINCE"]);

                                //$obju_Faas2->setudfvalue("u_north",$ar["BOUNDARYNORTH"]);
                                //$obju_Faas2->setudfvalue("u_east",$ar["BOUNDARYEAST"]);
                                //$obju_Faas2->setudfvalue("u_south",$ar["BOUNDARYSOUTH"]);
                                //$obju_Faas2->setudfvalue("u_west",$ar["BOUNDARYWEST"]);

                                if ($ar["LASTNAME"]=="") {
                                        $obju_Faas2->setudfvalue("u_ownertype","C");
                                        $obju_Faas2->setudfvalue("u_ownercompanyname",$ar["OWNERNAME"]);
                                }	

                                $obju_Faas2->setudfvalue("u_ownerlastname",$ar["LASTNAME"]);
                                $obju_Faas2->setudfvalue("u_ownerfirstname",$ar["FIRSTNAME"]);
                                $obju_Faas2->setudfvalue("u_ownermiddlename",$ar["MI"]);
                                $obju_Faas2->setudfvalue("u_ownername",$ar["OWNERNAME"]);
                                $obju_Faas2->setudfvalue("u_ownertin",$ar["LOCAL_TIN"]);
                                $obju_Faas2->setudfvalue("u_ownertelno",$ar["TELNO"]);
                                $obju_Faas2->setudfvalue("u_owneraddress",$ar["OWNERADDRESS"]);

                                $obju_Faas2->setudfvalue("u_taxable",$ar["TAXABILITY_BV"]);
                                $obju_Faas2->setudfvalue("u_effdate",$ar["STARTYEAR"]."-".$ar["STARTQUARTER"]."-01");
                                $obju_Faas2->setudfvalue("u_effqtr",$ar["STARTQUARTER"]);
                                $obju_Faas2->setudfvalue("u_effyear",$ar["STARTYEAR"]);
                                if ($ar["ENDED_BV"]==1) {
                                    $obju_Faas2->setudfvalue("u_cancelled",1);
                                    $obju_Faas2->setudfvalue("u_expdate",$ar["CANCELLATIONDATE"]);
                                    $obju_Faas2->setudfvalue("u_expqtr",$ar["ENDQUARTER"]);
                                    $obju_Faas2->setudfvalue("u_expyear",$ar["ENDYEAR"]);
                                }	
                                $obju_Faas2->setudfvalue("u_recommendby",$ar["RECOMAPPROVEDBY"]);
                                $obju_Faas2->setudfvalue("u_recommenddate",$ar["RECOMAPPROVEDDATE"]);
                                $obju_Faas2->setudfvalue("u_approvedby",$ar["APPROVEDBY"]);
                                $obju_Faas2->setudfvalue("u_approveddate",$ar["APPROVEDDATE"]);
                                $obju_Faas2->setudfvalue("u_memoranda",$ar["MEMORANDA"]);
                                $obju_Faas2->docstatus = "Approved";

                                $obju_Faas2->setudfvalue("u_cct",$ar["CCT"]);
                                $obju_Faas2->setudfvalue("u_ccidate",$ar["CCTCOISSUED"]);
                                $obju_Faas2->setudfvalue("u_coidate",$ar["CCTOCISSUED"]);
                                $obju_Faas2->setudfvalue("u_floorcount",$ar["NUMSTOREY"]);
                                $obju_Faas2->setudfvalue("u_bpno",$ar["PERMITNO"]);
                                $obju_Faas2->setudfvalue("u_bpdate",$ar["PERMITDATE"]);
                                $obju_Faas2->setudfvalue("u_startyear",$ar["YEARCONSTRUCTED"]);
                                $obju_Faas2->setudfvalue("u_endyear",$ar["YEARCOMPLETED"]);
                                $obju_Faas2->setudfvalue("u_occyear",$ar["YEAROCCUPIED"]);
                                $obju_Faas2->setudfvalue("u_renyear",$ar["YEARRENOVATED"]);

                                if ($ar["PREVTAXTRANS_ID"]!="") {
                                    $obju_Faas2->setudfvalue("u_prevarpno",$ar["PREVTAXTRANS_ID"]);

                                    $odbcres2 = @odbc_exec($odbccon,"SELECT a.TDNO, a.STARTQUARTER, a.STARTYEAR, b.PINNO, d.OWNERNAME
                                                                    FROM RPTASSESSMENT a 
                                                                    LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID 
                                                                    LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID 
                                                                    LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN 
                                                                    WHERE a.TAXTRANS_ID='".$ar["PREVTAXTRANS_ID"]."'") or die("<B>Error!</B> Couldn't Run Query 2:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                     if(odbc_fetch_row($odbcres2)) {

                                            //Build tempory
                                            for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                               $field_name = odbc_field_name($odbcres2, $j);
                                              // $this->temp_fieldnames[$j] = $field_name;
                                               $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                            }

                                            $obju_Faas2->setudfvalue("u_prevpin",$ar2["PINNO"]);
                                            $obju_Faas2->setudfvalue("u_prevtdno",$ar2["TDNO"]);
                                            $obju_Faas2->setudfvalue("u_prevowner",$ar2["OWNERNAME"]);
                                            if ($ar2["STARTYEAR"]!="") $obju_Faas2->setudfvalue("u_preveffdate",$ar2["STARTYEAR"]."-".$ar2["STARTQUARTER"]."-01");
                                    }	
                                    if ($obju_Faas2Prev->getbykey($ar["PREVTAXTRANS_ID"])) {
                                            $obju_Faas2->setudfvalue("u_prevvalue",$obju_Faas2Prev->getudfvalue("u_assvalue"));
                                    }
                                }		
                                $actionReturn = $obju_Faas2->add();

                                $u_building="";
                                $u_structuretype="";
                                if ($actionReturn) {
                                        $odbcres2 = @odbc_exec($odbccon,"SELECT a.FLOOR_ID, a.TAXTRANS_ID, a.FLOORNUMBER, b.DESCRIPTION as BLDGKIND, c.DESCRIPTION as STRUCTURETYPE, d.DESCRIPTION as BLDGCLASS, a.CLASSCODE_CT, a.ACTUALUSE_CT, 
                                                                                a.PERCENTCOMPLETED, a.BLDGAGE, a.USERID, a.TRANSDATE, a.UNITVALUE, a.SFMVBLDGCOST_ID, a.TAXABILITY_BV, a.YEARCONREN, a.TAXDECDETAIL_ID, a.MARKETVALUE, a.AREA, 
                                                                                e.RATE, e.USERID, e.TRANSDATE, e.BLDGAGE, e.DEPVALUE, e.DEPMARKETVALUE, e.ADJRATE, e.ADJVALUE, e.ADJMARKETVALUE
                                                                                FROM RPTBLDGFLOOR a 
                                                                                LEFT JOIN RPTBLDGDEPRECIATION e ON e.TAXTRANS_ID=a.TAXTRANS_ID AND e.FLOORNUMBER=a.FLOORNUMBER AND e.SOURCE_CT='bldgfloor' 
                                                                                LEFT JOIN T_BLDGKIND b ON b.CODE=a.BLDGKIND_CT LEFT JOIN T_BLDGSTRUCTURETYPE c ON c.CODE=a.STRUCTURETYPE_CT 
                                                                                LEFT JOIN T_BLDGCLASS d ON d.CODE=a.BLDGCLASS_CT WHERE a.TAXTRANS_ID='".$obju_Faas2->docno."'") or die("<B>Error!</B> Couldn't Run Query 3:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                         while(odbc_fetch_row($odbcres2)) {

                                                //Build tempory
                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                   $field_name = odbc_field_name($odbcres2, $j);
                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                }


                                                $u_building = $ar2["BLDGKIND"];
                                                $u_structuretype = $ar2["STRUCTURETYPE"];

                                                $obju_Faas2a->prepareadd();
                                                $obju_Faas2a->docid = getNextIDByBranch("u_rpfaas2a",$objConnection);
                                                $obju_Faas2a->docno = getNextNoByBranch("u_rpfaas2a",'',$objConnection);
                                                //var_dump(array($obju_Faas2a->docid,$obju_Faas2a->docno));
                                                $obju_Faas2a->setudfvalue("u_arpno",$obju_Faas2->docno);
                                                $obju_Faas2a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
                                                $obju_Faas2a->setudfvalue("u_actualuse",trim($ar2["ACTUALUSE_CT"]));

                                                //$obju_Faas2a->setudfvalue("u_taxability",$ar2["TAXABILITY_BV"]);
                                                $obju_Faas2a->setudfvalue("u_floor",$ar2["FLOORNUMBER"]);
                                                $obju_Faas2a->setudfvalue("u_structuretype",$ar2["STRUCTURETYPE"]);
                                                $obju_Faas2a->setudfvalue("u_subclass",$ar2["BLDGCLASS"]);
                                                $obju_Faas2a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
                                                $obju_Faas2a->setudfvalue("u_sqm",$ar2["AREA"]);
                                                $obju_Faas2a->setudfvalue("u_startyear",$ar2["YEARCONREN"]);
                                                $obju_Faas2a->setudfvalue("u_age",$ar2["BLDGAGE"]);
                                                $obju_Faas2a->setudfvalue("u_unitvalue",$ar2["UNITVALUE"]);
                                                $obju_Faas2a->setudfvalue("u_completeperc",$ar2["PERCENTCOMPLETED"]*100);
                                                $obju_Faas2a->setudfvalue("u_floorbasevalue",$ar2["MARKETVALUE"]);
                                                $obju_Faas2a->setudfvalue("u_flooradjperc",$ar2["ADJRATE"]*100);
                                                $obju_Faas2a->setudfvalue("u_flooradjvalue",$ar2["ADJVALUE"]);
                                                $obju_Faas2a->setudfvalue("u_floordeprevalue",$ar2["DEPVALUE"]);
                                                $obju_Faas2a->setudfvalue("u_flooradjmarketvalue",$ar2["ADJMARKETVALUE"]);
                                                $basevalue=$ar2["MARKETVALUE"];
                                                $adjvalue=$ar2["ADJVALUE"];
                                                $deprevalue=$ar2["DEPVALUE"];
                                                $adjmarketvalue=$ar2["ADJMARKETVALUE"];

                                                $odbcres3 = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as BLDGCOMPONENT, c.DESCRIPTION as BLDGMATERIAL, a.USERID, a.TRANSDATE 
                                                                                 FROM RPTBLDGSTRUCTURALMATERIAL a 
                                                                                 LEFT JOIN T_BLDGCOMPONENT b ON b.CODE=a.BLDGCOMPONENT_CT 
                                                                                 LEFT JOIN T_BLDGMATERIAL c ON c.CODE=a.BLDGMATERIAL_CT WHERE a.TAXTRANS_ID='".$ar2["TAXTRANS_ID"]."' and a.FLOORNUMBER=0") or die("<B>Error!</B> Couldn't Run Query 4:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                 while(odbc_fetch_row($odbcres3)) {

                                                        //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                           $field_name = odbc_field_name($odbcres3, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                        }

                                                        $obju_Faas2d->prepareadd();
                                                        $obju_Faas2d->docid = $obju_Faas2a->docid;
                                                        $obju_Faas2d->lineid = getNextIDByBranch("u_rpfaas2d",$objConnection);
                                                        $obju_Faas2d->setudfvalue("u_selected",1);
                                                        $obju_Faas2d->setudfvalue("u_prop",trim($ar3["BLDGCOMPONENT"])."-".trim($ar3["BLDGMATERIAL"]));

                                                        $actionReturn = $obju_Faas2d->add();
                                                        if (!$actionReturn) break;
                                                }
                                                if (!$actionReturn) break;			

                                                $odbcres3 = @odbc_exec($odbccon,"SELECT a.EXTRA_ID, a.TAXTRANS_ID, a.SFMVBLDGEXTRA_ID, b.DESCRIPTION AS EXTRAITEM, a.AREA, a.UNITPRICE, a.EXTRAAMOUNT, a.USERID, a.TRANSDATE, a.FLOOR_ID, a.FLOORNUMBER, a.YEARCONREN, a.PERCENTCOMPLETED, a.BLDGAGE, a.TAXABILITY_BV, 
                                                                                a.ACTUALUSE_CT, a.CLASSCODE_CT, 
                                                                                e.RATE, e.USERID, e.TRANSDATE, e.BLDGAGE, e.DEPVALUE, e.DEPMARKETVALUE, e.ADJRATE, e.ADJVALUE, e.ADJMARKETVALUE
                                                                                FROM RPTBLDGEXTRA a  LEFT JOIN T_BLDGEXTRAITEM b ON b.CODE=a.EXTRAITEM_CT LEFT JOIN RPTBLDGDEPRECIATION e ON e.TAXTRANS_ID=a.TAXTRANS_ID AND e.FLOORNUMBER=a.FLOORNUMBER AND e.SOURCE_CT='bldgextra' AND e.FLOORITEM=b.DESCRIPTION
                                                                                WHERE a.FLOOR_ID='".$ar2["FLOOR_ID"]."'") or die("<B>Error!</B> Couldn't Run Query 5:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                 while(odbc_fetch_row($odbcres3)) {

                                                        //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                           $field_name = odbc_field_name($odbcres3, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                        }

                                                        $obju_Faas2b->prepareadd();
                                                        $obju_Faas2b->docid = $obju_Faas2a->docid;
                                                        $obju_Faas2b->lineid = getNextIDByBranch("u_rpfaas2b",$objConnection);
                                                        $obju_Faas2b->setudfvalue("u_itemdesc",trim($ar3["EXTRAITEM"]));
                                                        $obju_Faas2b->setudfvalue("u_sqm",$ar3["AREA"]);
                                                        $obju_Faas2b->setudfvalue("u_startyear",$ar3["YEARCONREN"]);
                                                        $obju_Faas2b->setudfvalue("u_completeperc",$ar3["PERCENTCOMPLETED"]*100);
                                                        $obju_Faas2b->setudfvalue("u_unitvalue",$ar3["UNITPRICE"]);
                                                        $obju_Faas2b->setudfvalue("u_quantity",$ar3["EXTRAAMOUNT"]/$ar3["UNITPRICE"]);
                                                        $obju_Faas2b->setudfvalue("u_basevalue",$ar3["EXTRAAMOUNT"]);
                                                        $obju_Faas2b->setudfvalue("u_adjperc",$ar3["ADJRATE"]*100);
                                                        $obju_Faas2b->setudfvalue("u_adjvalue",$ar3["ADJVALUE"]);
                                                        $obju_Faas2b->setudfvalue("u_deprevalue",$ar3["DEPVALUE"]);
                                                        $obju_Faas2b->setudfvalue("u_adjmarketvalue",$ar3["ADJMARKETVALUE"]);
                                                        $basevalue+=$ar3["MARKETVALUE"];
                                                        $adjvalue+=$ar3["ADJVALUE"];
                                                        $deprevalue+=$ar3["DEPVALUE"];
                                                        $adjmarketvalue+=$ar3["ADJMARKETVALUE"];

                                                        $actionReturn = $obju_Faas2b->add();
                                                        if (!$actionReturn) break;
                                                }
                                                if (!$actionReturn) break;			

                                                $odbcres3 = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as BLDGCOMPONENT, c.DESCRIPTION as BLDGMATERIAL, a.USERID, a.TRANSDATE 
                                                                                 FROM RPTBLDGSTRUCTURALMATERIAL a LEFT JOIN T_BLDGCOMPONENT b ON b.CODE=a.BLDGCOMPONENT_CT LEFT JOIN T_BLDGMATERIAL c ON c.CODE=a.BLDGMATERIAL_CT WHERE a.TAXTRANS_ID='".$ar2["TAXTRANS_ID"]."' and a.FLOORNUMBER='".$ar2["FLOORNUMBER"]."'") or die("<B>Error!</B> Couldn't Run Query 6:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                    while(odbc_fetch_row($odbcres3)) {

                                                        //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                           $field_name = odbc_field_name($odbcres3, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                        }

                                                        $obju_Faas2e->prepareadd();
                                                        $obju_Faas2e->docid = $obju_Faas2a->docid;
                                                        $obju_Faas2e->lineid = getNextIDByBranch("u_rpfaas2e",$objConnection);
                                                        $obju_Faas2e->setudfvalue("u_selected",1);
                                                        $obju_Faas2e->setudfvalue("u_prop",trim($ar3["BLDGCOMPONENT"])."-".trim($ar3["BLDGMATERIAL"]));

                                                        $actionReturn = $obju_Faas2e->add();
                                                        if (!$actionReturn) break;
                                                    }
                                                        if (!$actionReturn) break;			

                                                        $obju_Faas2a->setudfvalue("u_basevalue",$basevalue);
                                                        $obju_Faas2a->setudfvalue("u_adjvalue",$adjvalue);
                                                        $obju_Faas2a->setudfvalue("u_deprevalue",$deprevalue);
                                                        $obju_Faas2a->setudfvalue("u_adjmarketvalue",$adjmarketvalue);

                                                        if ($actionReturn) $actionReturn = $obju_Faas2a->add();
                                                        if (!$actionReturn) break;
                                        }	

                                        if ($actionReturn) {
                                                if ($obju_Faas2->getbykey($obju_Faas2->docno)) {
                                                        $obju_Faas2->setudfvalue("u_building",$u_building);
                                                        $obju_Faas2->setudfvalue("u_structuretype",$u_structuretype);
                                                        $actionReturn = $obju_Faas2->update($obju_Faas2->docno,$obju_Faas2->rcdversion);
                                                } else return raiseError("Unable to retrieve Building Arp No. to update building kind and structure type.");	
                                        }

                                    }

                                    } elseif ($ar["PROPERTYKIND_CT"]=="M") {
                                        $num_rows++;
                                        $obju_Faas3->prepareadd();
                                        $obju_Faas3->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                                        $obju_Faas3->docseries = -1;
                                        $obju_Faas3->docno = $ar["TAXTRANS_ID"];
                                        $obju_Faas3->setudfvalue("u_pin",$ar["PINNO"]);
                                        $obju_Faas3->setudfvalue("u_prefix",substr($ar["PINNO"],0,strlen($ar["PINNO"])-5));
                                        $obju_Faas3->setudfvalue("u_suffix",substr($ar["PINNO"],strlen($ar["PINNO"])-4,4));
                                        $obju_Faas3->setudfvalue("u_tdno",$ar["TDNO"]);
                                        $obju_Faas3->setudfvalue("u_trxcode",$ar["TRANSCODE_CT"]);

                                        //$obju_Faas3->setudfvalue("u_tctno",$ar["CERTIFICATETITLENO"]);
                                        //$obju_Faas3->setudfvalue("u_surveyno",$ar["SURVEYNO"]);
                                        //$obju_Faas3->setudfvalue("u_lotno",$ar["LOTNO"]);
                                        //$obju_Faas3->setudfvalue("u_block",$ar["BLKNO"]);
                                        //$obju_Faas3->setudfvalue("u_cadlotno",$ar["CADASTRALLOTNO"]);

                                        $obju_Faas3->setudfvalue("u_street",$ar["STREET"]);
                                        $obju_Faas3->setudfvalue("u_barangay",strtoupper($ar["BARANGAY"]));
                                        $obju_Faas3->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                        $obju_Faas3->setudfvalue("u_province",$ar["PROVINCE"]);

                                        //$obju_Faas3->setudfvalue("u_north",$ar["BOUNDARYNORTH"]);
                                        //$obju_Faas3->setudfvalue("u_east",$ar["BOUNDARYEAST"]);
                                        //$obju_Faas3->setudfvalue("u_south",$ar["BOUNDARYSOUTH"]);
                                        //$obju_Faas3->setudfvalue("u_west",$ar["BOUNDARYWEST"]);

                                        if ($ar["LASTNAME"]=="") {
                                                $obju_Faas3->setudfvalue("u_ownertype","C");
                                                $obju_Faas3->setudfvalue("u_ownercompanyname",$ar["OWNERNAME"]);
                                        }	

                                        $obju_Faas3->setudfvalue("u_ownerlastname",$ar["LASTNAME"]);
                                        $obju_Faas3->setudfvalue("u_ownerfirstname",$ar["FIRSTNAME"]);
                                        $obju_Faas3->setudfvalue("u_ownermiddlename",$ar["MI"]);
                                        $obju_Faas3->setudfvalue("u_ownername",$ar["OWNERNAME"]);
                                        $obju_Faas3->setudfvalue("u_ownertin",$ar["LOCAL_TIN"]);
                                        $obju_Faas3->setudfvalue("u_ownertelno",$ar["TELNO"]);
                                        $obju_Faas3->setudfvalue("u_owneraddress",$ar["OWNERADDRESS"]);

                                        $obju_Faas3->setudfvalue("u_taxable",$ar["TAXABILITY_BV"]);
                                        $obju_Faas3->setudfvalue("u_effdate",$ar["STARTYEAR"]."-".$ar["STARTQUARTER"]."-01");
                                        $obju_Faas3->setudfvalue("u_effqtr",$ar["STARTQUARTER"]);
                                        $obju_Faas3->setudfvalue("u_effyear",$ar["STARTYEAR"]);
                                        if ($ar["ENDED_BV"]==1) {
                                                $obju_Faas3->setudfvalue("u_cancelled",1);
                                                $obju_Faas3->setudfvalue("u_expdate",$ar["CANCELLATIONDATE"]);
                                                $obju_Faas3->setudfvalue("u_expqtr",$ar["ENDQUARTER"]);
                                                $obju_Faas3->setudfvalue("u_expyear",$ar["ENDYEAR"]);
                                        }	
                                        $obju_Faas3->setudfvalue("u_recommendby",$ar["RECOMAPPROVEDBY"]);
                                        $obju_Faas3->setudfvalue("u_recommenddate",$ar["RECOMAPPROVEDDATE"]);
                                        $obju_Faas3->setudfvalue("u_approvedby",$ar["APPROVEDBY"]);
                                        $obju_Faas3->setudfvalue("u_approveddate",$ar["APPROVEDDATE"]);
                                        $obju_Faas3->setudfvalue("u_memoranda",$ar["MEMORANDA"]);
                                        $obju_Faas3->docstatus = "Approved";

                                        if ($ar["PREVTAXTRANS_ID"]!="") {
                                                $obju_Faas3->setudfvalue("u_prevarpno",$ar["PREVTAXTRANS_ID"]);
                                                $odbcres2 = @odbc_exec($odbccon,"SELECT a.TDNO, a.STARTQUARTER, a.STARTYEAR, b.PINNO, d.OWNERNAME
                                                                                 FROM RPTASSESSMENT a LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN WHERE a.TAXTRANS_ID='".$ar["PREVTAXTRANS_ID"]."'") or die("<B>Error!</B> Couldn't Run Query 7:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                 if(odbc_fetch_row($odbcres2)) {

                                                        //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                           $field_name = odbc_field_name($odbcres2, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                        }

                                                        $obju_Faas3->setudfvalue("u_prevpin",$ar2["PINNO"]);
                                                        $obju_Faas3->setudfvalue("u_prevtdno",$ar2["TDNO"]);
                                                        $obju_Faas3->setudfvalue("u_prevowner",$ar2["OWNERNAME"]);
                                                        if ($ar2["STARTYEAR"]!="") $obju_Faas3->setudfvalue("u_preveffdate",$ar2["STARTYEAR"]."-".$ar2["STARTQUARTER"]."-01");
                                                }	
                                                if ($obju_Faas3Prev->getbykey($ar["PREVTAXTRANS_ID"])) {
                                                        $obju_Faas3->setudfvalue("u_prevvalue",$obju_Faas3Prev->getudfvalue("u_assvalue"));
                                                }
                                        }		
                                        $actionReturn = $obju_Faas3->add();

                                        if ($actionReturn) {
                                                $odbcres2 = @odbc_exec($odbccon,"SELECT a.MACHAPPRAISAL_ID, a.TAXTRANS_ID, a.MACHGROUP_CT, b.DESCRIPTION as MACHTYPE, a.CLASSCODE_CT, a.ACTUALUSE_CT, a.BRAND, a.MODEL, a.CAPACITY, a.YEARACQUIRED, d.DESCRIPTION as MACHCONDITION, a.ESTIMATEDECOLIFE, a.REMAININGECOLIFE, a.YEARINSTALLATION, a.YEAROPERATION, a.REMARK, a.NUMUNIT, a.ACQUISITIONCOST, a.FREIGHTCOST, a.INSURANCECOST, a.INSTALLATIONCOST, a.OTHERCOST, a.MARKETVALUE, a.DEPRECIATION, a.DEPMARKETVALUE, a.USERID, a.TRANSDATE, a.TAXABILITY_BV, a.IMPORTED_BV, a.TAXDECDETAIL_ID
                                                                                 FROM RPTMACHAPPRAISAL a LEFT JOIN T_MACHINETYPE b ON b.CODE=a.MACHTYPE_CT LEFT JOIN T_MACHINECONDITION d ON d.CODE=a.MACHCONDITION_CT WHERE a.TAXTRANS_ID='".$obju_Faas3->docno."'") or die("<B>Error!</B> Couldn't Run Query 8:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                 while(odbc_fetch_row($odbcres2)) {

                                                        //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                           $field_name = odbc_field_name($odbcres2, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                        }

                                                        $obju_Faas3a->prepareadd();
                                                        $obju_Faas3a->docid = getNextIDByBranch("u_rpfaas3a",$objConnection);
                                                        $obju_Faas3a->docno = getNextNoByBranch("u_rpfaas3a",'',$objConnection);
                                                        //var_dump(array($obju_Faas3a->docid,$obju_Faas3a->docno));
                                                        $obju_Faas3a->setudfvalue("u_arpno",$obju_Faas3->docno);
                                                        //$obju_Faas3a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
                                                        $obju_Faas3a->setudfvalue("u_actualuse",trim($ar2["ACTUALUSE_CT"]));

                                                        //$obju_Faas3a->setudfvalue("u_taxability",$ar2["TAXABILITY_BV"]);
                                                        $obju_Faas3a->setudfvalue("u_machine",$ar2["MACHTYPE"]);
                                                        $obju_Faas3a->setudfvalue("u_brand",$ar2["BRAND"]);
                                                        $obju_Faas3a->setudfvalue("u_model",$ar2["MODEL"]);
                                                        $obju_Faas3a->setudfvalue("u_capacity",$ar2["CAPACITY"]);
                                                        //$obju_Faas3a->setudfvalue("u_acqdate",$ar2["AREA"]);
                                                        $obju_Faas3a->setudfvalue("u_condition",$ar2["MACHCONDITION"]);
                                                        $obju_Faas3a->setudfvalue("u_estlife",$ar2["ESTIMATEDECOLIFE"]);
                                                        $obju_Faas3a->setudfvalue("u_remlife",$ar2["ESTIMATEDECOLIFE"]-$ar2["REMAININGECOLIFE"]);
                                                        $obju_Faas3a->setudfvalue("u_insyear",$ar2["YEARINSTALLATION"]);
                                                        $obju_Faas3a->setudfvalue("u_inityear",$ar2["YEARACQUIRED"]);
                                                        //$obju_Faas3a->setudfvalue("u_orgcost",$ar2["ACQUISITIONCOST"]*100);
                                                        $obju_Faas3a->setudfvalue("u_orgcost",$ar2["MARKETVALUE"]);
                                                        $obju_Faas3a->setudfvalue("u_cnvfactor",$ar2["ADJVALUE"]);
                                                        $obju_Faas3a->setudfvalue("u_quantity",$ar2["NUMUNIT"]);
                                                        //$obju_Faas3a->setudfvalue("u_rcn",$ar2["ADJMARKETVALUE"]);
                                                        $obju_Faas3a->setudfvalue("u_useyear",$ar2["YEAROPERATION"]);
                                                        //$obju_Faas3a->setudfvalue("u_depreratefr",$ar2["ADJMARKETVALUE"]);
                                                        //$obju_Faas3a->setudfvalue("u_deprerateto",$ar2["ADJMARKETVALUE"]);
                                                        $obju_Faas3a->setudfvalue("u_depreperc",$ar2["DEPRECIATION"]*100);
                                                        $obju_Faas3a->setudfvalue("u_deprevalue",$ar2["MARKETVALUE"]-$ar2["DEPMARKETVALUE"]);
                                                        $obju_Faas3a->setudfvalue("u_remvalue",$ar2["DEPMARKETVALUE"]);

                                                        $obju_Faas3a->privatedata["header"] = $obju_Faas3;
                                                        if ($actionReturn) $actionReturn = $obju_Faas3a->add();
                                                        if (!$actionReturn) break;
                                                }	

                                        }

                                    } elseif ($ar["PROPERTYKIND_CT"]=="L") {
                                            $num_rows++;
                                            $obju_Faas1->prepareadd();
                                            $obju_Faas1->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                                            $obju_Faas1->docseries = -1;
                                            $obju_Faas1->docno = $ar["TAXTRANS_ID"];
                                            $obju_Faas1->setudfvalue("u_pin",$ar["PINNO"]);
                                            $obju_Faas1->setudfvalue("u_tdno",$ar["TDNO"]);
                                            $obju_Faas1->setudfvalue("u_trxcode",$ar["TRANSCODE_CT"]);

                                            $obju_Faas1->setudfvalue("u_tctno",$ar["CERTIFICATETITLENO"]);
                                            $obju_Faas1->setudfvalue("u_surveyno",$ar["SURVEYNO"]);
                                            $obju_Faas1->setudfvalue("u_lotno",$ar["LOTNO"]);
                                            $obju_Faas1->setudfvalue("u_block",$ar["BLKNO"]);
                                            $obju_Faas1->setudfvalue("u_cadlotno",$ar["CADASTRALLOTNO"]);

                                            $obju_Faas1->setudfvalue("u_street",$ar["STREET"]);
                                            $obju_Faas1->setudfvalue("u_barangay",strtoupper($ar["BARANGAY"]));
                                            $obju_Faas1->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                            $obju_Faas1->setudfvalue("u_province",$ar["PROVINCE"]);

                                            $obju_Faas1->setudfvalue("u_north",$ar["BOUNDARYNORTH"]);
                                            $obju_Faas1->setudfvalue("u_east",$ar["BOUNDARYEAST"]);
                                            $obju_Faas1->setudfvalue("u_south",$ar["BOUNDARYSOUTH"]);
                                            $obju_Faas1->setudfvalue("u_west",$ar["BOUNDARYWEST"]);

                                            if ($ar["LASTNAME"]=="") {
                                                    $obju_Faas1->setudfvalue("u_ownertype","C");
                                                    $obju_Faas1->setudfvalue("u_ownercompanyname",$ar["OWNERNAME"]);
                                            }	

                                            $obju_Faas1->setudfvalue("u_ownerlastname",$ar["LASTNAME"]);
                                            $obju_Faas1->setudfvalue("u_ownerfirstname",$ar["FIRSTNAME"]);
                                            $obju_Faas1->setudfvalue("u_ownermiddlename",$ar["MI"]);
                                            $obju_Faas1->setudfvalue("u_ownername",$ar["OWNERNAME"]);
                                            $obju_Faas1->setudfvalue("u_ownertin",$ar["LOCAL_TIN"]);
                                            $obju_Faas1->setudfvalue("u_ownertelno",$ar["TELNO"]);
                                            $obju_Faas1->setudfvalue("u_owneraddress",$ar["OWNERADDRESS"]);

                                            $obju_Faas1->setudfvalue("u_taxable",$ar["TAXABILITY_BV"]);
                                            $obju_Faas1->setudfvalue("u_effdate",$ar["STARTYEAR"]."-".$ar["STARTQUARTER"]."-01");
                                            $obju_Faas1->setudfvalue("u_effqtr",$ar["STARTQUARTER"]);
                                            $obju_Faas1->setudfvalue("u_effyear",$ar["STARTYEAR"]);
                                            if ($ar["ENDED_BV"]==1) {
                                                    $obju_Faas1->setudfvalue("u_cancelled",1);
                                                    $obju_Faas1->setudfvalue("u_expdate",$ar["CANCELLATIONDATE"]);
                                                    $obju_Faas1->setudfvalue("u_expqtr",$ar["ENDQUARTER"]);
                                                    $obju_Faas1->setudfvalue("u_expyear",$ar["ENDYEAR"]);
                                            }	
                                            $obju_Faas1->setudfvalue("u_recommendby",$ar["RECOMAPPROVEDBY"]);
                                            $obju_Faas1->setudfvalue("u_recommenddate",$ar["RECOMAPPROVEDDATE"]);
                                            $obju_Faas1->setudfvalue("u_approvedby",$ar["APPROVEDBY"]);
                                            $obju_Faas1->setudfvalue("u_approveddate",$ar["APPROVEDDATE"]);
                                            $obju_Faas1->setudfvalue("u_memoranda",$ar["MEMORANDA"]);
                                            $obju_Faas1->docstatus = "Approved";
			
                                            if ($ar["PREVTAXTRANS_ID"]!="") {
                                                    $obju_Faas1->setudfvalue("u_prevarpno",$ar["PREVTAXTRANS_ID"]);
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT a.TDNO, a.STARTQUARTER, a.STARTYEAR, b.PINNO, d.OWNERNAME
                                                    FROM RPTASSESSMENT a LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN WHERE a.TAXTRANS_ID='".$ar["PREVTAXTRANS_ID"]."'") or die("<B>Error!</B> Couldn't Run Query 9:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                                             if(odbc_fetch_row($odbcres2)) {

                                                                                    //Build tempory
                                                                                    for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                                                       $field_name = odbc_field_name($odbcres2, $j);
                                                                                      // $this->temp_fieldnames[$j] = $field_name;
                                                                                       $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                                                    }

                                                                                    $obju_Faas1->setudfvalue("u_prevpin",$ar2["PINNO"]);
                                                                                    $obju_Faas1->setudfvalue("u_prevtdno",$ar2["TDNO"]);
                                                                                    $obju_Faas1->setudfvalue("u_prevowner",$ar2["OWNERNAME"]);
                                                                                    if ($ar2["STARTYEAR"]!="") $obju_Faas1->setudfvalue("u_preveffdate",$ar2["STARTYEAR"]."-".$ar2["STARTQUARTER"]."-01");
                                                                            }	
                                                                            if ($obju_Faas1Prev->getbykey($ar["PREVTAXTRANS_ID"])) {
                                                                                    $obju_Faas1->setudfvalue("u_prevvalue",$obju_Faas1Prev->getudfvalue("u_assvalue"));
                                                                            }
                                                                    }		
                                            $actionReturn = $obju_Faas1->add();
                                            if ($actionReturn) {
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT a.TAXDECDETAIL_ID, a.TAXTRANS_ID, a.ACTUALUSE_CT, a.MARKETVALUE, a.ASSESSEDVALUE, a.CLASSCODE_CT, a.GRYEAR, a.ASSESSMENTLEVEL, b.LANDAPPRAISAL_ID, b.SFMVLAND_ID, d.DESCRIPTION as CLASSLEVEL, b.SUBCLASS_CT, c.DESCRIPTION AS SUBCLASS, b.UNITVALUE, b.MARKETVALUE as BASEVALUE, b.AREA
                                                                                     FROM RPTASSESSMENTDETAIL a LEFT JOIN RPTLANDAPPRAISAL b ON b.TAXDECDETAIL_ID=a.TAXDECDETAIL_ID LEFT JOIN T_SUBCLASSIFICATION c ON c.CODE=b.SUBCLASS_CT LEFT JOIN T_CLASSLEVEL d ON d.CODE=b.CLASSLEVEL_CT WHERE a.TAXTRANS_ID='".$obju_Faas1->docno."'") or die("<B>Error!</B> Couldn't Run Query 10:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                     while(odbc_fetch_row($odbcres2)) {

                                                                //Build tempory
                                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                                   $field_name = odbc_field_name($odbcres2, $j);
                                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                                }

                                                                $obju_Faas1a->prepareadd();
                                                                $obju_Faas1a->docid = getNextIDByBranch("u_rpfaas1a",$objConnection);
                                                                $obju_Faas1a->docno = getNextNoByBranch("u_rpfaas1a",'',$objConnection);
                                                                //var_dump(array($obju_Faas1a->docid,$obju_Faas1a->docno));
                                                                $obju_Faas1a->setudfvalue("u_arpno",$obju_Faas1->docno);
                                                                $obju_Faas1a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));

                                                                if ($ar2["SUBCLASS_CT"]!="") {
                                                                        $obju_Faas1b->prepareadd();
                                                                        $obju_Faas1b->docid = $obju_Faas1a->docid;
                                                                        $obju_Faas1b->lineid = getNextIDByBranch("u_rpfaas1b",$objConnection);
                                                                        $obju_Faas1b->setudfvalue("u_class",trim($ar2["CLASSLEVEL"]));
                                                                        $obju_Faas1b->setudfvalue("u_subclass",trim($ar2["SUBCLASS"]));
                                                                        $obju_Faas1b->setudfvalue("u_sqm",floatval($ar2["AREA"]));
                                                                        $obju_Faas1b->setudfvalue("u_unitvalue",$ar2["UNITVALUE"]);
                                                                        $obju_Faas1b->setudfvalue("u_basevalue",$ar2["BASEVALUE"]);

                                                                        $actionReturn = $obju_Faas1b->add();
                                                                        if (!$actionReturn) break;
                                                                }	


                                                                $adj=0;
                                                                $odbcres3 = @odbc_exec($odbccon,"SELECT b.DESCRIPTION AS ADJUSTMENTTYPE, c.DESCRIPTION AS ADJUSTMENT, a.PERCENTADJUSTMENT, a.VALUEADJUSTMENT FROM RPTVALUEADJUSTMENT a LEFT JOIN T_ADJUSTMENTTYPE b ON b.CODE=a.ADJUSTMENTTYPE_CT LEFT JOIN T_ADJUSTMENT c ON c.CODE=a.ADJUSTMENT_CT WHERE a.LANDAPPRAISAL_ID='".$ar2["LANDAPPRAISAL_ID"]."'") or die("<B>Error!</B> Couldn't Run Query 11:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                                 while(odbc_fetch_row($odbcres3)) {

                                                                        //Build tempory
                                                                        for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                                           $field_name = odbc_field_name($odbcres3, $j);
                                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                                           $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                                        }

                                                                        $obju_Faas1c->prepareadd();
                                                                        $obju_Faas1c->docid = $obju_Faas1a->docid;
                                                                        $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);

                                                                        $obju_Faas1c->setudfvalue("u_adjtype",trim($ar3["ADJUSTMENTTYPE"]));
                                                                        $obju_Faas1c->setudfvalue("u_adjfactor",trim($ar3["ADJUSTMENT"]));
                                                                        $obju_Faas1c->setudfvalue("u_adjperc",$ar3["PERCENTADJUSTMENT"]*100);
                                                                        $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["VALUEADJUSTMENT"]);

                                                                        $actionReturn = $obju_Faas1c->add();
                                                                        if (!$actionReturn) break;
                                                                        $adj += $ar3["VALUEADJUSTMENT"];
                                                                }


                                                                $obju_Faas1a->setudfvalue("u_sqm",floatval($ar2["AREA"]));
                                                                $obju_Faas1a->setudfvalue("u_basevalue",$ar2["MARKETVALUE"]-$adj);
                                                                $obju_Faas1a->setudfvalue("u_adjvalue",$adj);
                                                                $obju_Faas1a->setudfvalue("u_marketvalue",$ar2["MARKETVALUE"]);
                                                                $obju_Faas1a->setudfvalue("u_actualuse",trim($ar2["ACTUALUSE_CT"]));
                                                                $obju_Faas1a->setudfvalue("u_asslvl",$ar2["ASSESSMENTLEVEL"] * 100);
                                                                $obju_Faas1a->setudfvalue("u_assvalue",$ar2["ASSESSEDVALUE"]);

                                                                if ($actionReturn) $actionReturn = $obju_Faas1a->add();
                                                                if (!$actionReturn) break;
                                                        }	

                                                }
                                        }	
                                                                $i++;
                                                                if (!$actionReturn) break;
                                                                //if ($num_rows>10) break;
                                 }
                        
                        }
                        if ( $actionReturn) {
                            $odbcres = @odbc_exec($odbccon,"SELECT b.TAXTRANS_ID, b.PROPERTYKIND_CT, MAX(b.TAXYEAR) AS TAXYEAR
                                                            FROM PAYMENT a INNER JOIN PAYMENTCLASSDETAIL b ON b.PAYMENT_ID=a.PAYMENT_ID 
                                                            where a.STATUS_CT='SAV' and b.itaxtype_ct='BSC' $filterExp1 
                                                            GROUP BY b.TAXTRANS_ID, b.PROPERTYKIND_CT 
                                                            ORDER BY b.taxtrans_id") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                            while(odbc_fetch_row($odbcres)) {	
                                    //Build tempory
                                    for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                       $field_name = odbc_field_name($odbcres, $j);
                                      // $this->temp_fieldnames[$j] = $field_name;
                                       $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                    }
                                    if ($ar["PROPERTYKIND_CT"]=="L") {
                                            if ($obju_Faas1->getbykey($ar["TAXTRANS_ID"])) {
                                                    $obju_Faas1->setudfvalue("u_bilyear",$ar["TAXYEAR"]);
                                                    $actionReturn = $obju_Faas1->update($obju_Faas1->docno,$obju_Faas1->rcdversion);
                                                    if (!$actionReturn) break;
                                            } else echo "Unable to retrieve Land ARP No.[".$ar["TAXTRANS_ID"]."] to update last payment year.<br>";
                                    } elseif ($ar["PROPERTYKIND_CT"]=="B") {
                                            if ($obju_Faas2->getbykey($ar["TAXTRANS_ID"])) {
                                                    $obju_Faas2->setudfvalue("u_bilyear",$ar["TAXYEAR"]);
                                                    $actionReturn = $obju_Faas2->update($obju_Faas2->docno,$obju_Faas2->rcdversion);
                                                    if (!$actionReturn) break;
                                            } else echo "Unable to retrieve BUILDING ARP No.[".$ar["TAXTRANS_ID"]."] to update last payment year.<br>";
                                    } elseif ($ar["PROPERTYKIND_CT"]=="M") {
                                            if ($obju_Faas3->getbykey($ar["TAXTRANS_ID"])) {
                                                    $obju_Faas3->setudfvalue("u_bilyear",$ar["TAXYEAR"]);
                                                    $actionReturn = $obju_Faas3->update($obju_Faas3->docno,$obju_Faas3->rcdversion);
                                                    if (!$actionReturn) break;
                                            } else echo "Unable to retrieve MACHINE ARP No.[".$ar["TAXTRANS_ID"]."] to update last payment year.<br>";
                                    }
                                    if (!$actionReturn) break;
                            }
                        }  
             
                        if ($actionReturn) {
                                $objConnection->commit();
                                var_dump($num_rows);
                        } else {
                                $objConnection->rollback();
                                echo $_SESSION["errormessage"];

                        }
                }
                
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
        
        $filterExp = "";
	
	$filterExp = genSQLFilterDate("A.U_APPROVEDDATE",$filterExp,$httpVars['df_u_approveddatefr'],$httpVars['df_u_approveddateto']);
	$filterExp = genSQLFilterString("A.U_BARANGAY",$filterExp,$httpVars['df_u_barangay'],null,null,true);
	$filterExp = genSQLFilterString("A.U_EFFYEAR",$filterExp,$httpVars['df_u_effyear'],null,null,true);
        
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
	$objrs = new recordset(null,$objConnection);
        if ($page->getitemstring("u_kind")=="") {
		$objrs->queryopenext("select A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, IF(A.U_OWNERCOMPANYNAME='',A.U_OWNERNAME,U_OWNERCOMPANYNAME) AS U_OWNERNAME,A.U_EFFYEAR, A.U_BARANGAY,A.U_APPROVEDDATE, IF(A.U_CANCELLED='1','Yes','No') AS U_CANCELLED FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp UNION ALL select A.U_TDNO,  if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'BUILDING' AS U_KIND, IF(A.U_OWNERCOMPANYNAME='',A.U_OWNERNAME,U_OWNERCOMPANYNAME) AS U_OWNERNAME,A.U_EFFYEAR, A.U_BARANGAY,A.U_APPROVEDDATE, IF(A.U_CANCELLED='1','Yes','No') AS U_CANCELLED FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp UNION ALL select A.U_TDNO,  if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'MACHINERY' AS U_KIND, IF(A.U_OWNERCOMPANYNAME='',A.U_OWNERNAME,U_OWNERCOMPANYNAME) AS U_OWNERNAME,A.U_EFFYEAR, A.U_BARANGAY,A.U_APPROVEDDATE, IF(A.U_CANCELLED='1','Yes','No') AS U_CANCELLED FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGridB->sortby,"",$objGridB->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} elseif ($page->getitemstring("u_kind")=="L") {
		$objrs->queryopenext("select A.U_TDNO, A.U_PIN, 'LAND' AS U_KIND, IF(A.U_OWNERCOMPANYNAME='',A.U_OWNERNAME,U_OWNERCOMPANYNAME) AS U_OWNERNAME,A.U_EFFYEAR, A.U_BARANGAY,A.U_APPROVEDDATE, IF(A.U_CANCELLED='1','Yes','No') AS U_CANCELLED FROM U_RPFAAS1 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGridB->sortby,"",$objGridB->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} elseif ($page->getitemstring("u_kind")=="B") {
		$objrs->queryopenext("select A.U_TDNO,  if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'BUILDING' AS U_KIND, IF(A.U_OWNERCOMPANYNAME='',A.U_OWNERNAME,U_OWNERCOMPANYNAME) AS U_OWNERNAME,A.U_EFFYEAR, A.U_BARANGAY,A.U_APPROVEDDATE, IF(A.U_CANCELLED='1','Yes','No') AS U_CANCELLED FROM U_RPFAAS2 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGridB->sortby,"",$objGridB->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	} elseif ($page->getitemstring("u_kind")=="M") {
		$objrs->queryopenext("select A.U_TDNO,  if(A.U_PIN='',concat(a.u_prefix,'-',a.u_suffix),a.u_pin) as U_PIN, 'MACHINERY' AS U_KIND, IF(A.U_OWNERCOMPANYNAME='',A.U_OWNERNAME,U_OWNERCOMPANYNAME) AS U_OWNERNAME,A.U_EFFYEAR, A.U_BARANGAY,A.U_APPROVEDDATE, IF(A.U_CANCELLED='1','Yes','No') AS U_CANCELLED FROM U_RPFAAS3 A  WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGridB->sortby,"",$objGridB->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
	}
     
		$page->paging_recordcount($objrs->recordcount());
                $curtotal = 0;
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGridB->addrow();
	
			$objGridB->setitem(null,"u_tdno",$objrs->fields["U_TDNO"]);
			$objGridB->setitem(null,"u_pin",$objrs->fields["U_PIN"]);
			$objGridB->setitem(null,"u_kind",$objrs->fields["U_KIND"]);
			$objGridB->setitem(null,"u_approveddate",formatDateToHttp($objrs->fields["U_APPROVEDDATE"]));
			$objGridB->setitem(null,"u_effyear",$objrs->fields["U_EFFYEAR"]);
			$objGridB->setitem(null,"u_ownername",$objrs->fields["U_OWNERNAME"]);
			$objGridB->setitem(null,"u_barangay",$objrs->fields["U_BARANGAY"]);
			$objGridB->setitem(null,"u_cancel",$objrs->fields["U_CANCELLED"]);
                        
			//$objGridA->setkey(null,$objrs->fields["u_ornumber"]); 
		 if (!$page->paging_fetch()) break;
		}
                $page->setitem("u_curtotalrecord",formatNumeric($objrs->recordcount()));
//	//}
//	
	resetTabindex();
	setTabindex($schema["u_approveddatefr"]);
	setTabindex($schema["u_approveddateto"]);
	setTabindex($schema["u_barangay"]);
	setTabindex($schema["u_effyear"]);
	setTabindex($schema["u_kind"]);
        
        //ITAX DATA
        
	$odbccon = @odbc_connect("itax","sysdba","masterkey",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
            if($page->getitemstring("u_approveddatefr")=="" && $page->getitemstring("u_approveddateto")=="" && $page->getitemstring("u_barangay")=="" && $page->getitemstring("u_effyear")=="" && $page->getitemstring("u_kind")==""){

            }else{
                
                $filterExp1 = "";
                $filterExp1 = genSQLFilterDate("A.APPROVEDDATE",$filterExp1,$httpVars['df_u_approveddatefr'],$httpVars['df_u_approveddateto']);
                $filterExp1 = genSQLFilterString("w.DESCRIPTION",$filterExp1,$httpVars['df_u_barangay'],null,null,true);
                $filterExp1 = genSQLFilterString("A.STARTYEAR",$filterExp1,$httpVars['df_u_effyear'],null,null,true);
                if ($page->getitemstring("u_kind")!="") {
                     $filterExp1 = genSQLFilterString("B.PROPERTYKIND_CT",$filterExp1,$httpVars['df_u_kind'],null,null,true);
                }
                
                if ($filterExp1 !="") $filterExp1 = " AND " . $filterExp1;
                $odbcres = @odbc_exec($odbccon,"SELECT a.TDNO, b.PINNO, b.PROPERTYKIND_CT, a.APPROVEDDATE, a.STARTYEAR, d.OWNERNAME, b.BARANGAY_CT, w.DESCRIPTION as BARANGAY, a.ENDED_BV
                FROM RPTASSESSMENT a 
                LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID 
                LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID 
                LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN 
                LEFT JOIN T_BARANGAY w ON w.CODE=b.BARANGAY_CT AND w.MUNICIPAL_ID=b.MUNICIPAL_ID AND w.PROVINCE_CT=b.PROVINCE_CT 
                LEFT JOIN T_MUNICIPALITY x ON x.MUNICIPAL_ID=b.MUNICIPAL_ID 
                LEFT JOIN T_PROVINCE y ON y.CODE=b.PROVINCE_CT 
                LEFT JOIN RPTBLDGINFO e ON e.TAXTRANS_ID=a.TAXTRANS_ID WHERE  B.PROVINCE_CT = '008' $filterExp1 ORDER BY a.TAXTRANS_ID ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());

                }
                 $itaxtotal = 0;
     
                while(odbc_fetch_row($odbcres)) {	
                        //Build tempory
                        for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                           $field_name = odbc_field_name($odbcres, $j);
                          // $this->temp_fieldnames[$j] = $field_name;
                           $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                        }
                                $objGridA->addrow();
                                $objGridA->setitem(null,"u_tdno",$ar["TDNO"]);
                                $objGridA->setitem(null,"u_pin",$ar["PINNO"]);
                                $objGridA->setitem(null,"u_kind",$ar["PROPERTYKIND_CT"]);
                                $objGridA->setitem(null,"u_approveddate",formatDateToHttp($ar["APPROVEDDATE"]));
                                $objGridA->setitem(null,"u_effyear",$ar["STARTYEAR"]);
                                $objGridA->setitem(null,"u_ownername",$ar["OWNERNAME"]);
                                $objGridA->setitem(null,"u_barangay",$ar["BARANGAY"]);
                                $objGridA->setitem(null,"u_cancel",iif($ar["ENDED_BV"]=="1","Yes","No"));
                                $itaxtotal = $itaxtotal + 1;
                }
            $page->setitem("u_itaxtotalrecord",formatNumeric($itaxtotal));


       $page->resize->addgrid("T1",20,290,false);
       $page->resize->addgrid("T2",20,290,false);
	
//?>

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

<script language="JavaScript">
    function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_street");
	}

	function onFormSubmit(action) {
            var rc = getTableRowCount("T1");
        
                if(action=="migratefaas"){
                    if(rc==0){
                        page.statusbar.showWarning("No Data found.");
                        return false;
                    }else{
                        if(confirm("Migrate this data?")){
                            return true;
                        }else{
                            return false;
                        } 
                    }
                    
                }    
            
            
	}

	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				formPageReset(); 
				clearTable("T1");
				clearTable("T2");
				break;
			case "u_approveddatefr": 
			case "u_approveddateto": 
			case "u_barangay": 
			case "u_effyear": 
				formPageReset(); 
				clearTable("T1");
				clearTable("T2");
				break;	
		}
		return true;
	}	
	


	function onPageSaveValues(p_action) {
		var inputs = new Array();
			
			inputs.push("u_approveddatefr");
			inputs.push("u_approveddateto");
			inputs.push("u_barangay");
			inputs.push("u_effyear");
			inputs.push("u_kind");
			inputs.push("u_curtotalrecord");
			inputs.push("u_itaxtotalrecord");
			
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		
		formSearch('','<?php echo $page->paging->formid; ?>');
	}
        function onElementChange(element,column,table,row) {
		switch (column) {
			case "u_kind": 
				formPageReset(); 
				clearTable("T1");
				clearTable("T2");
				break;	
		}
		return true;
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_approveddatefr":
			case "u_approveddateto":
			case "u_barangay":
			case "u_effyear":
			case "u_curtotalrecord":
			case "u_itaxtotalrecord":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
                                        var rc2=getTableSelectedRow("T2");
					selectTableRow("T2",rc2+1);
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
	  <td class="labelPageHeader" >&nbsp;Macro Migrate RPT Faas&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td>
</tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:0px solid gray" >
     
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
       
        <tr><td width="168" >&nbsp;<label <?php genCaptionHtml($schema["u_approveddatefr"],"") ?>>Approved date from</label></td>
		<td width="795" align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_approveddatefr"]) ?> /></td>
		<td width="124" ><label <?php genCaptionHtml($schema["u_itaxtotalrecord"],"") ?>>Itax Total Records</label></td>
		<td width="166" align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_itaxtotalrecord"]) ?> /></td>
	</tr>
	<tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_approveddateto"],"") ?>>Approved date to</label></td>
		<td  align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_approveddateto"]) ?> /></td>
		<td width="124" ><label <?php genCaptionHtml($schema["u_curtotalrecord"],"") ?>>Current Total Records</label></td>
		<td width="166" align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_curtotalrecord"]) ?> /></td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_barangay"],"") ?>>Barangay</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_barangay"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_effyear"],"") ?>>Effectivity Year</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_effyear"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
    </tr>
         <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_kind"],"") ?>>Property Kind</label></td>
		<td  align=left>&nbsp;<select <?php genSelectHtml($schema["u_kind"],array("loadenumkindofproperty","",":[All]"),null,null,null,"width:300px") ?> ></select></td>
		<td ></td>
		<td align=left>&nbsp;</td>
        </tr>

        <tr>
            <td >&nbsp;</td>
                   
        </tr>
    <tr><td >&nbsp;</td>
		<td  align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a> &nbsp;&nbsp;&nbsp; <a class="button" href="" onClick="formSubmit('migratefaas');return false;">Migrate Data</a></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    
	</table>
    </div>
</td>
<td>
</td>
</tr></table>
</td></tr>	

<tr>
    <td>
   <div class="tabber" id="tab1">
		<div class="tabbertab" title="RPT FAAS Itax">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td> <?php $objGridA->draw()?></td>
                        </tr>
                 
                    </table>  
                </div> 
                <div class="tabbertab" title="RPT FAAS Current ">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td > <?php $objGridB->draw()?></td>
                        </tr>
                 
                    </table>  
                </div> 

	</div>
        
    </td>
</tr>
<?php $page->writeRecordLimit(); ?>
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