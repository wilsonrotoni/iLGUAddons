<?php


        set_time_limit(0);
        
	$progid = "u_updateGEORPTASFAAS123remigrate";

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
        
        $page->restoreSavedValues();
        
        $page->objectcode = "u_updateGEORPTASFAAS123remigrate";
	$page->paging->formid = "./UDP.php?&objectcode=U_MIGRATEGEORPTAS";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Update Cancelled Property";
        $page->settimeout(0);
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
                $obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                $obju_Faas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                $obju_Faas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                $obju_Faas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
                $obju_Faas1d = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
                $obju_Faas1p = new documentlinesschema_br(null,$objConnection,"u_rpfaas1p");
                
                $obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
                $obju_Faas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
                $obju_Faas2b = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
                $obju_Faas2d = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
                $obju_Faas2e = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");
                $obju_Faas2p = new documentlinesschema_br(null,$objConnection,"u_rpfaas2p");
                    
                $obju_Faas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
                $obju_Faas3a = new documentschema_br(null,$objConnection,"u_rpfaas3a");
                $obju_Faas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");
                $obju_Faas3p = new documentlinesschema_br(null,$objConnection,"u_rpfaas3p");


                $obju_MachTypes = new masterdataschema(null,$objConnection,"u_rpmachtypes");
                $obju_MachConditions = new masterdataschema(null,$objConnection,"u_rpmachconditions");

                $obju_Faas1Prev = new documentschema_br(null,$objConnection,"u_rpfaas1");
                $obju_Faas2Prev = new documentschema_br(null,$objConnection,"u_rpfaas2");
                $obju_Faas3Prev = new documentschema_br(null,$objConnection,"u_rpfaas3");
                $objConnection->beginwork();
                $odbccon = @odbc_connect("GeoRecords2","","",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                
                if ($actionReturn) {
                     $odbcres = @odbc_exec($odbccon,"SELECT
                                                       
                                                         A.IsApproved
                                                        ,A.TDNId
                                                        ,A.PropertyTypeId
                                                        ,A.Location
                                                        ,PIN
                                                        ,A.IsCancelled
                                                        ,A.DateCancelled
                                                        ,SUBSTRING(CONVERT(varchar,A.DateCancelled,112),1,4) as ExpYear
                                                        ,CEILING(CONVERT(float,SUBSTRING(CONVERT(varchar,DateCancelled,112),5,2)) / 3) as ExpQtr
                                                        ,A.TDN
                                                        ,A.ARPNo
                                                        ,A.TCTDate
                                                        ,A.TCTNo
                                                        ,B.TDNModeCode
                                                        ,A.Phase
                                                        ,A.TitleNo
                                                        ,A.CadastralSurveyNo
                                                        ,A.LotNo
                                                        ,A.BlockNo
                                                        ,A.DisplayName
                                                        ,A.OwnerAddress
                                                        ,A.OwnerId AS Localtin
                                                        ,C.LastName
                                                        ,C.FirstName
                                                        ,C.MiddleName
                                                        ,D.OwnerTypeDescription as Ownerkind
                                                        ,A.PropAdminName
                                                        ,A.PropAdminContactNo
                                                        ,A.PropAdminAddress
                                                        ,a.BarangayCode
                                                        ,E.StreetName
                                                        ,'BACOOR' AS CITY
                                                        ,'CAVITE' AS PROVINCE
                                                        ,A.AppraiseAssessedBy
                                                        ,A.DateAppraised
                                                        ,A.RecomApprovalName
                                                        ,A.DateRecommendedApproval
                                                        ,A.DateApprovedByAssessor
                                                        ,A.CityMunAssessorName
                                                        ,A.Memoranda
                                                        ,A.Annotation
                                                        ,A.DateRecorded
                                                        ,A.RecordingPerson
                                                        ,A.PrevOwner
                                                        ,A.SupersededRecordingPerson
                                                        ,A.YearEffectivity
                                                        ,A.QuarterEffectivity
                                                        ,A.Taxability
                                                        ,A.TotalAssessedValue
                                                        ,A.RevisionYear 
                                                        FROM PATAS.TDN A 
                                                        LEFT JOIN PATAS.ATDNMode B ON A.TDNModeId = B.TDNModeId
                                                        LEFT JOIN DBO.AOwner C ON A.OwnerId = C.OwnerId
                                                        LEFT JOIN PATAS.AOwnerType D ON C.OwnerTypeId = D.OwnerTypeId
                                                        LEFT JOIN DBO.aStreet E ON A.StreetId = E.StreetId
                                                        where A.TDNID > 510669
                                                        ORDER BY A.TDNId ") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                
                                if ($ar["PropertyTypeId"]=="1") {
                                    //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                
                               if ($ar["PropertyTypeId"]=="1") {
                                $num_rows++;
                                $obju_Faas1->prepareadd();
                                $obju_Faas1->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                                $obju_Faas1->docseries = -1;
                                $obju_Faas1->docno = $ar["TDNId"];
                                $obju_Faas1->setudfvalue("u_cancelled",$ar["IsCancelled"]);
                                $obju_Faas1->setudfvalue("u_pin",$ar["PIN"]);
                                $obju_Faas1->setudfvalue("u_tdno",$ar["TDN"]);
                                $obju_Faas1->setudfvalue("u_varpno",$ar["ARPNo"]);
                                $obju_Faas1->setudfvalue("u_tctno",$ar["TCTNo"]);
                                $obju_Faas1->setudfvalue("u_tctdate",$ar["TCTDate"]);
                                $obju_Faas1->setudfvalue("u_location",$ar["Location"]);
                                
                                $obju_Faas1->setudfvalue("u_trxcode",$ar["TDNModeCode"]);
                                $obju_Faas1->setudfvalue("u_cadlotno",$ar["Phase"]);
                                $obju_Faas1->setudfvalue("u_titleno",$ar["TitleNo"]);
                                $obju_Faas1->setudfvalue("u_lotno",$ar["LotNo"]);
                                $obju_Faas1->setudfvalue("u_block",$ar["BlockNo"]);
                                $obju_Faas1->setudfvalue("u_surveyno",$ar["CadastralSurveyNo"]);
                                
                                if ($ar["LastName"]=="")$obju_Faas1->setudfvalue("u_ownertype","C");
                                else $obju_Faas1->setudfvalue("u_ownertype","I");
                                
                                $obju_Faas1->setudfvalue("u_ownercompanyname",$ar["DisplayName"]);
                                $obju_Faas1->setudfvalue("u_ownername",$ar["DisplayName"]);
                                $obju_Faas1->setudfvalue("u_ownerlastname",$ar["LastName"]);
                                $obju_Faas1->setudfvalue("u_ownerfirstname",$ar["Firstname"]);
                                $obju_Faas1->setudfvalue("u_ownermiddlename",$ar["Middlename"]);
                                $obju_Faas1->setudfvalue("u_ownertin",$ar["Localtin"]);
                                
                                $obju_Faas1->setudfvalue("u_owneraddress",$ar["OwnerAddress"]);
                                $obju_Faas1->setudfvalue("u_adminname",$ar["PropAdminName"]);
                                $obju_Faas1->setudfvalue("u_adminaddress",$ar["PropAdminAddress"]);
                                $obju_Faas1->setudfvalue("u_admintelno",$ar["PropAdminContactNo"]);
                                $obju_Faas1->setudfvalue("u_revisionyear",$ar["RevisionYear"]);

                                $obju_Faas1->setudfvalue("u_street",$ar["STREET"]);
                                $obju_Faas1->setudfvalue("u_oldbarangay",$ar["BarangayCode"]);
                                $obju_Faas1->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                $obju_Faas1->setudfvalue("u_city",$ar["CITY"]);
                                $obju_Faas1->setudfvalue("u_province",$ar["PROVINCE"]);

                               

                                $obju_Faas1->setudfvalue("u_taxable",$ar["Taxability"]);
                                $obju_Faas1->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                $obju_Faas1->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                $obju_Faas1->setudfvalue("u_effyear",$ar["YearEffectivity"]);
                               	
                                $obju_Faas1->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
                                $obju_Faas1->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
                                $obju_Faas1->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
                                $obju_Faas1->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
                                $obju_Faas1->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
                                $obju_Faas1->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
                                $obju_Faas1->setudfvalue("u_memoranda",$ar["Memoranda"]);
                                $obju_Faas1->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
                                $obju_Faas1->setudfvalue("u_expdate",$ar["DateCancelled"]);
                                
                                 if ($ar["IsCancelled"]==1) {
                                        $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(TDN),TDN,EndYear,EndQuarter,DateCancellation  FROM PATAS.TDNHistory WHERE ENDYEAR <> '' AND TDN = '".$ar["ARPNo"]."' GROUP BY TDN,EndYear,EndQuarter,DateCancellation ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                            if(odbc_fetch_row($odbcres2)) {

                                                   //Build tempory
                                                   for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                      $field_name = odbc_field_name($odbcres2, $j);
                                                     // $this->temp_fieldnames[$j] = $field_name;
                                                      $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                   }
                                                $obju_Faas1->setudfvalue("u_cancelled",1);
                                                $obju_Faas1->setudfvalue("u_expdate",$ar2["DateCancellation"]);
                                                $obju_Faas1->setudfvalue("u_expqtr",$ar2["EndQuarter"]);
                                                $obju_Faas1->setudfvalue("u_expyear",$ar2["EndYear"]);
                                           }	
                                }
                                
                                if ($ar["IsApproved"]==1) $obju_Faas1->docstatus = "Approved";
                                else $obju_Faas1->docstatus = "Encoding";
                                
                                if ($actionReturn) {
                                    $odbcres2 = @odbc_exec($odbccon,"SELECT NorthBoundary,SouthBoundary,EastBoundary,WestBoundary FROM PATAS.TDNLand WHERE TDNID = '".$ar["TDNId"]."'") or die("<B>Error!</B> Couldn't Run Query 15:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                        if(odbc_fetch_row($odbcres2)) {
                                               //Build tempory
                                            for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                               $field_name = odbc_field_name($odbcres2, $j);
                                              // $this->temp_fieldnames[$j] = $field_name;
                                               $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                            }
                                            $obju_Faas1->setudfvalue("u_north",$ar2["NorthBoundary"]);
                                            $obju_Faas1->setudfvalue("u_south",$ar2["SouthBoundary"]);
                                            $obju_Faas1->setudfvalue("u_east",$ar2["EastBoundary"]);
                                            $obju_Faas1->setudfvalue("u_west",$ar2["WestBoundary"]);
                                    }	
                                }
                                
                                if($actionReturn){
                                        $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNID AS PREVDOCNO,A.TDN AS PREVTD,A.PIN AS PREVPIN,A.OwnerName AS PREVOWNER,A.PreviousAssessedValue,B.YearEffectivity AS PREVEFFYEAR,B.QuarterEffectivity AS PREVEFFQTR,B.RecordingPerson AS PREVRECORDPERSON,B.DateRecorded AS PREVDATERECORD FROM PATAS.TDNHistory A
                                                                        LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo WHERE A.TDNID = '".$ar["TDNId"]."' AND A.TDN NOT IN('NEW DEC') ") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                         while(odbc_fetch_row($odbcres2)) {
                                                //Build tempory
                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                   $field_name = odbc_field_name($odbcres2, $j);
                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                }
                                                $obju_Faas1p->prepareadd();
                                                $obju_Faas1p->docid = $obju_Faas1->docid;
                                                $obju_Faas1p->lineid = getNextIDByBranch("u_rpfaas1p",$objConnection);
                                                $obju_Faas1p->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                $obju_Faas1p->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                $obju_Faas1p->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                $obju_Faas1p->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                $obju_Faas1p->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
                                                if($ar2["PREVEFFYEAR"]!="" && $ar2["PREVEFFQTR"]!="")$obju_Faas1p->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                $obju_Faas1p->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                $obju_Faas1p->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                                $actionReturn = $obju_Faas1p->add();
                                                
                                                if (!$actionReturn) break;
                                                
                                                $obju_Faas1->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                $obju_Faas1->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                $obju_Faas1->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                $obju_Faas1->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                $obju_Faas1->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
                                                if($ar2["PREVEFFYEAR"] !=""&&$ar2["PREVEFFQTR"]!= "") $obju_Faas1->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                $obju_Faas1->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                $obju_Faas1->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                        }	
                                }
                                
                              
                              		
                                $actionReturn = $obju_Faas1->add();

                                if ($actionReturn) {
                                        $faas1acnt = 0;
                                        $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNLandId,CONCAT(C.ClassificationCode,'-',AA.RevisionYear) AS ClassificationCode,B.ClassificationID,B.AssessmentLevel,D.ActualUseCode FROM PATAS.TDNLand A
                                                                            LEFT JOIN PATAS.TDN AA ON A.TDNID = AA.TDNId AND AA.PropertyTypeId = 1
                                                                            INNER JOIN PATAS.TDNLandDetail B ON A.TDNLandID = B.TDNLandId
                                                                            INNER JOIN PATAS.AClassification C ON B.ClassificationID = C.ClassificationId
                                                                            INNER JOIN PATAS.AActualUse D ON B.ActualUseId = D.ActualUseId
                                                                            WHERE A.TDNID = '".$obju_Faas1->docno."'
                                                                            GROUP BY B.TDNLandId,CONCAT(C.ClassificationCode,'-',AA.RevisionYear),B.ClassificationID,B.AssessmentLevel,D.ActualUseCode
                                                                            ORDER BY TDNLandId") or die("<B>Error!</B> Couldn't Run Query 16:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                         while(odbc_fetch_row($odbcres2)) {
                                                    
                                                //Build tempory
                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                   $field_name = odbc_field_name($odbcres2, $j);
                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                }
                                                $faas1acnt ++;
                                                $obju_Faas1a->prepareadd();
                                                $obju_Faas1a->docid = getNextIDByBranch("u_rpfaas1a",$objConnection);
                                                $obju_Faas1a->docno = getNextNoByBranch("u_rpfaas1a",'',$objConnection);
                                                //var_dump(array($obju_Faas1a->docid,$obju_Faas1a->docno));
                                                $obju_Faas1a->setudfvalue("u_arpno",$obju_Faas1->docno);
                                                $obju_Faas1a->setudfvalue("u_class",trim($ar2["ClassificationCode"]));
                                                
                                                $area =0;
                                                $totalunitvalue =0;
                                                $unitvalue =0;
                                                $adj=0;
                                                $basevalue =0;
                                                $assvalue =0;
                                                $odbcres3 = @odbc_exec($odbccon,"SELECT B.ClassificationID,B.TDNLandId,C.ClassificationCode,C.ClassificationName,B.LandDescription,D.AgriTypeDescription,B.AreaSQM,B.AreaHAS,B.Unit,B.UnitValue,B.MarketValue,LandAdjustments,LandAdjustedMarketValue,B.CornerLotRate,B.InteriorLotRate,B.TopographyRate,B.CostOfFillingRate
                                                                                    FROM PATAS.TDNLandDetail B
                                                                                    LEFT JOIN PATAS.AClassification C ON B.ClassificationID = C.ClassificationId 
                                                                                    LEFT JOIN PATAS.ALandAgriType D ON B.AgriLandID = D.AgriTypeId
                                                                                    WHERE B.TDNLandId = '".$ar2["TDNLandId"]."' AND B.ClassificationID	= '".$ar2["ClassificationID"]."'") or die("<B>Error!</B> Couldn't Run Query 17:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                   while(odbc_fetch_row($odbcres3)) {
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                            $field_name = odbc_field_name($odbcres3, $j);
                                                           // $this->temp_fieldnames[$j] = $field_name;
                                                            $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                         }
                                                         
                                                        $obju_Faas1b->prepareadd();
                                                        $obju_Faas1b->docid = $obju_Faas1a->docid;
                                                        $obju_Faas1b->lineid = getNextIDByBranch("u_rpfaas1b",$objConnection);
                                                        $obju_Faas1b->setudfvalue("u_subclass",trim($ar3["LandDescription"]));
                                                        if($ar3["Unit"] == "sqm.") {
                                                            $unitvalue = $ar3["UnitValue"];
                                                        } else {
                                                            $unitvalue = $ar3["UnitValue"] / 10000;
                                                            $obju_Faas1b->setudfvalue("u_unitvaluehas",$unitvalue);
                                                        } 
                                                        $obju_Faas1b->setudfvalue("u_unit",$ar3["Unit"]);
                                                        $obju_Faas1b->setudfvalue("u_sqmhas",floatval($ar3["AreaHAS"]));
                                                        $obju_Faas1b->setudfvalue("u_sqm",floatval($ar3["AreaSQM"]));
                                                        $obju_Faas1b->setudfvalue("u_unitvalue",$unitvalue);
                                                        $obju_Faas1b->setudfvalue("u_basevalue",$ar3["MarketValue"]);
                                                        $area += $ar3["AreaSQM"];
                                                        $totalunitvalue += $ar3["UnitValue"];
                                                        $basevalue += $ar3["MarketValue"];
                                                        
                                                        $actionReturn = $obju_Faas1b->add();
                                                        if (!$actionReturn) break;
                                                        
                                                        if($actionReturn){
                                                            if($ar3["LandAdjustments"] <> 0 ){
                                                                $obju_Faas1c->prepareadd();
                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["LandAdjustments"]);
                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["LandAdjustments"] / 100));

                                                                $actionReturn = $obju_Faas1c->add();
                                                                if (!$actionReturn) break;
                                                                $adj += $ar3["MarketValue"] * ($ar3["LandAdjustments"] / 100);
                                                            }
                                                        }
                                                        if($actionReturn){
                                                            if($ar3["CornerLotRate"] <> 0){
                                                                $obju_Faas1c->prepareadd();
                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Corner Lot");
                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["CornerLotRate"]);
                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["CornerLotRate"] / 100));

                                                                $actionReturn = $obju_Faas1c->add();
                                                                if (!$actionReturn) break;
                                                                $adj += $ar3["MarketValue"] * ($ar3["CornerLotRate"] / 100);
                                                            }
                                                        }
                                                        if($actionReturn){
                                                            if($ar3["InteriorLotRate"] <> 0){
                                                                $obju_Faas1c->prepareadd();
                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Interior Lot");
                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["InteriorLotRate"]);
                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["InteriorLotRate"] / 100));

                                                                $actionReturn = $obju_Faas1c->add();
                                                                if (!$actionReturn) break;
                                                                $adj += $ar3["MarketValue"] * ($ar3["InteriorLotRate"] / 100);
                                                            }
                                                        }
                                                        if($actionReturn){
                                                            if($ar3["TopographyRate"] <> 0){
                                                                $obju_Faas1c->prepareadd();
                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Topography");
                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["TopographyRate"]);
                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["TopographyRate"] / 100));

                                                                $actionReturn = $obju_Faas1c->add();
                                                                if (!$actionReturn) break;
                                                                $adj += $ar3["MarketValue"] * ($ar3["TopographyRate"] / 100);
                                                            }
                                                        }
                                                        if($actionReturn){
                                                            if($ar3["CostOfFillingRate"] <> 0){
                                                                $obju_Faas1c->prepareadd();
                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Cost of Filling");
                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["CostOfFillingRate"]);
                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["CostOfFillingRate"] / 100));

                                                                $actionReturn = $obju_Faas1c->add();
                                                                if (!$actionReturn) break;
                                                                $adj += $ar3["MarketValue"] * ($ar3["CostOfFillingRate"] / 100);
                                                            }
                                                        }
                                                }
                                               // var_dump($faas1acnt . " f ");
                                                if($faas1acnt <= 1 ){
                                                        $odbcres4 = @odbc_exec($odbccon,"SELECT B.TypeDescription,C.AgriTypeDescription,A.Quantity,A.UnitValue,(A.MarketValue/A.Quantity) AS UnitValue2,A.MarketValue 
                                                                                        FROM PATAS.TDNLandImprovements A
                                                                                        LEFT JOIN PATAS.AImprovementType B ON A.TypeId = B.TypeId
                                                                                        LEFT JOIN PATAS.ALandAgriTypeBU C ON A.ImprovementClass = C.AgriTypeId
                                                                                        WHERE A.TDNLandId = '".$ar2["TDNLandId"]."'") or die("<B>Error!</B> Couldn't Run Query 18:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                        while(odbc_fetch_row($odbcres4)) {
                                                             for ($j = 1; $j <= odbc_num_fields($odbcres4); $j++) {
                                                                 $field_name = odbc_field_name($odbcres4, $j);
                                                                // $this->temp_fieldnames[$j] = $field_name;
                                                                 $ar4[$field_name] = odbc_result($odbcres4, $field_name) . "";
                                                              }

                                                             $obju_Faas1d->prepareadd();
                                                             $obju_Faas1d->docid = $obju_Faas1a->docid;
                                                             $obju_Faas1d->lineid = getNextIDByBranch("u_rpfaas1d",$objConnection);
                                                             $obju_Faas1d->setudfvalue("u_planttype",trim($ar4["TypeDescription"]));
                                                             $obju_Faas1d->setudfvalue("u_class",$ar4["AgriTypeDescription"]);
                                                             $obju_Faas1d->setudfvalue("u_productive",$ar4["Quantity"]);
                                                             $obju_Faas1d->setudfvalue("u_totalcount",$ar4["Quantity"]);
                                                             $obju_Faas1d->setudfvalue("u_unitvalue",floatval($ar4["UnitValue2"]));
                                                             $obju_Faas1d->setudfvalue("u_marketvalue",$ar4["MarketValue"]);
                                                             $totalunitvalue += $ar4["UnitValue"];
                                                             $basevalue += $ar4["MarketValue"];

                                                             $actionReturn = $obju_Faas1d->add();
                                                             if (!$actionReturn) break;
                                                         }
                                                }
                                                
                                                $obju_Faas1a->setudfvalue("u_sqm",floatval($area));
                                                $obju_Faas1a->setudfvalue("u_basevalue",$basevalue);
                                                $obju_Faas1a->setudfvalue("u_adjvalue",$adj);
                                                $obju_Faas1a->setudfvalue("u_marketvalue",$basevalue+$adj);
                                                $obju_Faas1a->setudfvalue("u_actualuse",trim($ar2["ActualUseCode"]));
                                                $obju_Faas1a->setudfvalue("u_asslvl",$ar2["AssessmentLevel"]);
                                                $assvalue = ($basevalue+$adj) * ($ar2["AssessmentLevel"] / 100);
                                                $obju_Faas1a->setudfvalue("u_assvalue", round($assvalue / 10) * 10);

                                                if ($actionReturn) $actionReturn = $obju_Faas1a->add();
                                                if (!$actionReturn) break;
                                        }	

                                }
                                }else if ($ar["PropertyTypeId"]=="2") {
                                        //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                
                               
                                if ($ar["PropertyTypeId"]=="2") {
            //                                       
                                            $num_rows++;
                                            $obju_Faas2->prepareadd();
                                            $obju_Faas2->docid = getNextIDByBranch("u_rpfaas2",$objConnection);
                                            $obju_Faas2->docseries = -1;
                                            $obju_Faas2->docno = $ar["TDNId"];
                                            $obju_Faas2->setudfvalue("u_pin",$ar["PIN"]);
                                            $obju_Faas2->setudfvalue("u_prefix",substr($ar["PIN"],0,strlen($ar["PIN"])-5));
                                            $obju_Faas2->setudfvalue("u_suffix",substr($ar["PIN"],strlen($ar["PIN"])-4,4));
                                            $obju_Faas2->setudfvalue("u_tdno",$ar["TDN"]);
                                            $obju_Faas2->setudfvalue("u_varpno",$ar["ARPNo"]);
                                            $obju_Faas2->setudfvalue("u_trxcode",$ar["TDNModeCode"]);

                                            if ($ar["LastName"]=="")$obju_Faas2->setudfvalue("u_ownertype","C");
                                            else $obju_Faas2->setudfvalue("u_ownertype","I");

                                            $obju_Faas2->setudfvalue("u_ownercompanyname",$ar["DisplayName"]);
                                            $obju_Faas2->setudfvalue("u_ownername",$ar["DisplayName"]);
                                            $obju_Faas2->setudfvalue("u_ownerlastname",$ar["LastName"]);
                                            $obju_Faas2->setudfvalue("u_ownerfirstname",$ar["Firstname"]);
                                            $obju_Faas2->setudfvalue("u_ownermiddlename",$ar["Middlename"]);
                                            $obju_Faas2->setudfvalue("u_ownertin",$ar["Localtin"]);
                                            $obju_Faas2->setudfvalue("u_owneraddress",$ar["OwnerAddress"]);

                                            $obju_Faas2->setudfvalue("u_adminname",$ar["PropAdminName"]);
                                            $obju_Faas2->setudfvalue("u_adminaddress",$ar["PropAdminAddress"]);
                                            $obju_Faas2->setudfvalue("u_admintelno",$ar["PropAdminContactNo"]);

                                            $obju_Faas2->setudfvalue("u_street",$ar["STREET"]);
                                            $obju_Faas2->setudfvalue("u_oldbarangay",$ar["BarangayCode"]);
                                            $obju_Faas2->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                            $obju_Faas2->setudfvalue("u_city",$ar["CITY"]);
                                            $obju_Faas2->setudfvalue("u_province",$ar["PROVINCE"]);
                                            $obju_Faas2->setudfvalue("u_revisionyear",$ar["RevisionYear"]);

                                            $obju_Faas2->setudfvalue("u_taxable",$ar["Taxability"]);
                                            $obju_Faas2->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                            $obju_Faas2->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                            $obju_Faas2->setudfvalue("u_effyear",$ar["YearEffectivity"]);

                                            $obju_Faas2->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
                                            $obju_Faas2->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
                                            $obju_Faas2->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
                                            $obju_Faas2->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
                                            $obju_Faas2->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
                                            $obju_Faas2->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
                                            $obju_Faas2->setudfvalue("u_memoranda",$ar["Memoranda"]);
                                            $obju_Faas2->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
                                            $obju_Faas2->setudfvalue("u_cancelled",$ar["IsCancelled"]);
                                            $obju_Faas2->setudfvalue("u_expdate",$ar["DateCancellation"]);

                                            if ($ar["IsApproved"]==1) $obju_Faas2->docstatus = "Approved";
                                            else $obju_Faas2->docstatus = "Encoding";

                                             if ($ar["IsCancelled"]==1) {
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(TDN),TDN,EndYear,EndQuarter,DateCancellation  FROM PATAS.TDNHistory WHERE ENDYEAR <> '' AND TDN = '".$ar["ARPNo"]."' GROUP BY TDN,EndYear,EndQuarter,DateCancellation ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                        if(odbc_fetch_row($odbcres2)) {

                                                               //Build tempory
                                                               for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                                  $field_name = odbc_field_name($odbcres2, $j);
                                                                 // $this->temp_fieldnames[$j] = $field_name;
                                                                  $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                               }
                                                            $obju_Faas2->setudfvalue("u_cancelled",1);
                                                            $obju_Faas2->setudfvalue("u_expdate",$ar2["DateCancellation"]);
                                                            $obju_Faas2->setudfvalue("u_expqtr",$ar2["EndQuarter"]);
                                                            $obju_Faas2->setudfvalue("u_expyear",$ar2["EndYear"]);
                                                       }	
                                            }

                                            if ($actionReturn) {
                                                $odbcres2 = @odbc_exec($odbccon,"SELECT C.TDN AS LandRefNo,A.LandOwnerName,A.LandBlkNo,A.LandTitle,A.LandArea,A.LandSurveyNo,A.LandLotNo,A.KindOfBuilding,A.StructuralType,A.BldgPermitNo,A.DateIssued,A.CondoCertTitle,A.CertOfCompletionIssuedOn,A.CertOfOccupancyIssuedOn,A.NoStorey,A.DateConstructed,A.DateDemolished,A.DateIssued,A.DateOccupied FROM PATAS.TDNBuilding A
                                                                                LEFT JOIN PATAS.TDNLand B ON A.LandTDNId = B.TDNLandID
                                                                                LEFT JOIN PATAS.TDN C ON B.TDNID = C.TDNId
                                                                                WHERE A.TDNID = '".$ar["TDNId"]."'") or die("<B>Error!</B> Couldn't Run Query 15:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                    if(odbc_fetch_row($odbcres2)) {
                                                           //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                           $field_name = odbc_field_name($odbcres2, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                        }
                                                        $obju_Faas2->setudfvalue("u_landowner",$ar2["LandOwnerName"]);
                                                        $obju_Faas2->setudfvalue("u_surveyno",$ar2["LandSurveyNo"]);
                                                        $obju_Faas2->setudfvalue("u_tctno",$ar2["LandTitle"]);
                                                        $obju_Faas2->setudfvalue("u_lotno",$ar2["LandLotNo"]);
                                                        $obju_Faas2->setudfvalue("u_landtdno",$ar2["LandRefNo"]);
                                                        $obju_Faas2->setudfvalue("u_sqm",$ar2["LandArea"]);
                                                        $obju_Faas2->setudfvalue("u_block",$ar2["LandBlkNo"]);
                                                        $obju_Faas2->setudfvalue("u_building",$ar2["KindOfBuilding"]);
                                                        $obju_Faas2->setudfvalue("u_structuretype",$ar2["StructuralType"]);
                                                        $obju_Faas2->setudfvalue("u_bpno",$ar2["BldgPermitNo"]);
                                                        $obju_Faas2->setudfvalue("u_bpdate",$ar2["DateIssued"]);
                                                        $obju_Faas2->setudfvalue("u_cct",$ar2["CondoCertTitle"]);
                                                        $obju_Faas2->setudfvalue("u_ccidate",$ar2["CertOfCompletionIssuedOn"]);
                                                        $obju_Faas2->setudfvalue("u_coidate",$ar2["CertOfOccupancyIssuedOn"]);
                                                        $obju_Faas2->setudfvalue("u_floorcount",$ar2["NoStorey"]);
                                                        $obju_Faas2->setudfvalue("u_startyear",$ar2["DateConstructed"]);
                                                        $obju_Faas2->setudfvalue("u_endyear",$ar2["DateDemolished"]);
                                                        $obju_Faas2->setudfvalue("u_occyear",$ar2["DateOccupied"]);
                                                        $obju_Faas2->setudfvalue("u_renyear",$ar2["DateIssued"]);
                                                    }	
                                            }


                                             if($actionReturn){
                                                            $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNID AS PREVDOCNO,A.TDN AS PREVTD,A.PIN AS PREVPIN,A.OwnerName AS PREVOWNER,A.PreviousAssessedValue,B.YearEffectivity AS PREVEFFYEAR,B.QuarterEffectivity AS PREVEFFQTR,B.RecordingPerson AS PREVRECORDPERSON,B.DateRecorded AS PREVDATERECORD FROM PATAS.TDNHistory A
                                                                                            LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo WHERE A.TDNID = '".$ar["TDNId"]."' AND A.TDN NOT IN('NEW DEC')") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                             while(odbc_fetch_row($odbcres2)) {
                                                                    //Build tempory
                                                                    for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                                       $field_name = odbc_field_name($odbcres2, $j);
                                                                      // $this->temp_fieldnames[$j] = $field_name;
                                                                       $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                                    }

                                                                    $obju_Faas2p->prepareadd();
                                                                    $obju_Faas2p->docid = $obju_Faas2->docid;
                                                                    $obju_Faas2p->lineid = getNextIDByBranch("u_rpfaas2p",$objConnection);
                                                                    $obju_Faas2p->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                                    $obju_Faas2p->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                                    $obju_Faas2p->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                                    $obju_Faas2p->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                                    $obju_Faas2p->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
                                                                    if($ar2["PREVEFFYEAR"]!="" && $ar2["PREVEFFQTR"]!="")$obju_Faas2p->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                                    $obju_Faas2p->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                                    $obju_Faas2p->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                                                    $actionReturn = $obju_Faas2p->add();

                                                                    if (!$actionReturn) break;

                                                                    $obju_Faas2->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                                    $obju_Faas2->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                                    $obju_Faas2->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                                    $obju_Faas2->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                                    $obju_Faas2->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
                                                                    if($ar2["PREVEFFYEAR"] !=""&&$ar2["PREVEFFQTR"]!= "") $obju_Faas2->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                                    $obju_Faas2->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                                    $obju_Faas2->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                                            }	
                                            }		
                                            $actionReturn = $obju_Faas2->add();
                                            $rcdcnt = 0;
                                            if ($actionReturn) {
                                                $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(A.TDNBuildingId) AS CNT 
                                                                                    FROM PATAS.TDNBuildingDetail A
                                                                                    LEFT JOIN PATAS.TDNBuilding B ON A.TDNBuildingId = B.TDNBuildingId
                                                                                    WHERE B.TDNId = '".$obju_Faas2->docno."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                    while(odbc_fetch_row($odbcres2)) {
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                           $field_name = odbc_field_name($odbcres2, $j);
                                                           $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                        }
                                                        $rcdcnt =  $ar2["CNT"];
                                                    }
                                            }
                                            $adjmarketvalue = 0;
                                            if ($actionReturn) {
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT A.BuildingNo,A.SucceedingFloorNo,A.TDNBuildingId,b.TDNId,A.OrderNo as FloorNo,B.KindOfBuilding,B.StructuralType,C.ActualUseCode,F.ClassificationCode,F.ClassificationCode,D.StructuralDescription,A.Area,B.BldgAge,B.DateConstructed,A.UnitValue,100 AS PercCompleted,A.MarketValue,A.Adjustment,A.AdjustmentValue,B.DepreciationRate,B.DepreciationCost  
                                                                                        FROM PATAS.TDNBuildingDetail A
                                                                                        LEFT JOIN PATAS.TDNBuilding B ON A.TDNBuildingId = B.TDNBuildingId
                                                                                        LEFT JOIN PATAS.TDN E ON B.TDNId = E.TDNId
                                                                                        LEFT JOIN PATAS.AClassification F ON E.ClassificationID = F.ClassificationId
                                                                                        LEFT JOIN PATAS.AActualUse C ON A.ActualUseId = C.ActualUseId
                                                                                        LEFT JOIN PATAS.ABuildingStructuralType D ON A.BuildingStructuralTypeId = D.BuildingStructuralTypeID WHERE B.TDNId ='".$obju_Faas2->docno."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                     while(odbc_fetch_row($odbcres2)) {

                                                            //Build tempory
                                                            for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                               $field_name = odbc_field_name($odbcres2, $j);
                                                              // $this->temp_fieldnames[$j] = $field_name;
                                                               $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                            }


                                                            $obju_Faas2a->prepareadd();
                                                            $obju_Faas2a->docid = getNextIDByBranch("u_rpfaas2a",$objConnection);
                                                            $obju_Faas2a->docno = getNextNoByBranch("u_rpfaas2a",'',$objConnection);
                                                            //var_dump(array($obju_Faas2a->docid,$obju_Faas2a->docno));
                                                            $obju_Faas2a->setudfvalue("u_arpno",$obju_Faas2->docno);
                                                            $obju_Faas2a->setudfvalue("u_class",trim($ar2["ClassificationCode"]));
                                                            $obju_Faas2a->setudfvalue("u_actualuse",trim($ar2["ActualUseCode"]));

                                                            //$obju_Faas2a->setudfvalue("u_taxability",$ar2["TAXABILITY_BV"]);
                                                            $obju_Faas2a->setudfvalue("u_kind",$ar2["KindOfBuilding"]);
                                                            $obju_Faas2a->setudfvalue("u_bldgno",$ar2["BuildingNo"]);
                                                            $obju_Faas2a->setudfvalue("u_floor",$ar2["FloorNo"]);
                                                            $obju_Faas2a->setudfvalue("u_structuretype",$ar2["StructuralType"]);
        //                                                    $obju_Faas2a->setudfvalue("u_subclass",$ar2["BLDGCLASS"]);
                                                            $obju_Faas2a->setudfvalue("u_sqm",$ar2["Area"]);
                                                            $obju_Faas2a->setudfvalue("u_startyear",$ar2["DateConstructed"]);
                                                            $obju_Faas2a->setudfvalue("u_age",$ar2["BldgAge"]);
                                                            $obju_Faas2a->setudfvalue("u_unitvalue",$ar2["UnitValue"]);
                                                            $obju_Faas2a->setudfvalue("u_completeperc",$ar2["PercCompleted"]);
                                                            $obju_Faas2a->setudfvalue("u_floorbasevalue",$ar2["MarketValue"]);
                                                            $obju_Faas2a->setudfvalue("u_flooradjperc",$ar2["Adjustment"]);
                                                            $obju_Faas2a->setudfvalue("u_flooradjvalue",$ar2["AdjustmentValue"]);
                                                            $obju_Faas2a->setudfvalue("u_floordeprevalue",$ar2["DepreciationCost"] / $rcdcnt);
                                                            $adjmarketvalue = ($ar2["MarketValue"] + $ar2["AdjustmentValue"]) -($ar2["DepreciationCost"] / $rcdcnt);
                                                            $obju_Faas2a->setudfvalue("u_flooradjmarketvalue",$adjmarketvalue);

                                                            $basevalue=$ar2["MarketValue"];
                                                            $adjvalue=$ar2["AdjustmentValue"];
                                                            $deprevalue=$ar2["DepreciationCost"] / $rcdcnt;
                                                            $adjmarketvalue=$adjmarketvalue;

                                                            $odbcres3 = @odbc_exec($odbccon,"SELECT B.AdditionalItemDescription,B.UnitValue,B.AdditionalItemMarketValue,B.Area,B.CostPerSQM,B.PercentCost,B.UnitsOrExcess,B.AdditionalItemCost,B.FormulaType,B.FloorNo FROM PATAS.TDNBuildingDetail A
                                                                                                INNER JOIN PATAS.TDNBuildingAdditionalItem B ON A.TDNBuildingId = B.TDNBuildingID AND A.SucceedingFloorNo = B.FloorNo
                                                                                                WHERE B.TDNBuildingId = '".$ar2["TDNBuildingId"]."' AND B.FloorNo = '".$ar2["SucceedingFloorNo"]."' ") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
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
                                                                    $obju_Faas2b->setudfvalue("u_itemdesc",trim($ar3["AdditionalItemDescription"]));

                                                                    if($ar3["FormulaType"] == "PercentUnitValue" ){
                                                                        $obju_Faas2b->setudfvalue("u_sqm",$ar3["Area"]);
                                                                        $obju_Faas2b->setudfvalue("u_unitvalue",$ar3["UnitValue"] * ($ar3["PercentCost"] / 100));
                                                                        $obju_Faas2b->setudfvalue("u_quantity",$ar3["UnitsOrExcess"]);
                                                                        $obju_Faas2b->setudfvalue("u_basevalue",$ar3["AdditionalItemCost"]);
                                                                        $obju_Faas2b->setudfvalue("u_adjmarketvalue",$ar3["AdditionalItemCost"]);
                                                                    }else if($ar3["FormulaType"] == "CostPerUnitLiter" ){
                                                                        $obju_Faas2b->setudfvalue("u_sqm",1);
                                                                        $obju_Faas2b->setudfvalue("u_unitvalue",$ar3["CostPerSQM"]);
                                                                        $obju_Faas2b->setudfvalue("u_quantity",$ar3["UnitsOrExcess"]);
                                                                        $obju_Faas2b->setudfvalue("u_basevalue",$ar3["AdditionalItemCost"]);
                                                                        $obju_Faas2b->setudfvalue("u_adjmarketvalue",$ar3["AdditionalItemCost"]);
                                                                    }else if($ar3["FormulaType"] == "CostPerSQM" ){
                                                                        $obju_Faas2b->setudfvalue("u_sqm",$ar3["Area"]);
                                                                        $obju_Faas2b->setudfvalue("u_unitvalue",$ar3["CostPerSQM"]);
                                                                        $obju_Faas2b->setudfvalue("u_quantity",$ar3["UnitsOrExcess"]);
                                                                        $obju_Faas2b->setudfvalue("u_basevalue",$ar3["AdditionalItemCost"]);
                                                                        $obju_Faas2b->setudfvalue("u_adjmarketvalue",$ar3["AdditionalItemCost"]);
                                                                    }else if($ar3["FormulaType"] == "MarketValueOnly" ){
                                                                        $obju_Faas2b->setudfvalue("u_sqm",$ar3["Area"]);
                                                                        $obju_Faas2b->setudfvalue("u_unitvalue",$ar3["AdditionalItemCost"] / ($ar3["Area"]));
                                                                        $obju_Faas2b->setudfvalue("u_quantity",$ar3["UnitsOrExcess"]);
                                                                        $obju_Faas2b->setudfvalue("u_basevalue",$ar3["AdditionalItemCost"]);
                                                                        $obju_Faas2b->setudfvalue("u_adjmarketvalue",$ar3["AdditionalItemCost"]);
                                                                    }

                                                                    $obju_Faas2b->setudfvalue("u_startyear",$ar2["DateConstructed"]);
                                                                    $obju_Faas2b->setudfvalue("u_completeperc",100);
                                                                    $obju_Faas2b->setudfvalue("u_adjperc",0);
                                                                    $obju_Faas2b->setudfvalue("u_adjvalue",0);
                                                                    $obju_Faas2b->setudfvalue("u_deprevalue",0);

                                                                    $basevalue+=$ar3["AdditionalItemCost"];
                                                                    $adjmarketvalue+=$ar3["AdditionalItemCost"];
                                                                    $actionReturn = $obju_Faas2b->add();
                                                                    if (!$actionReturn) break;
                                                            }
                                                            if (!$actionReturn) break;	

                                                            $odbcres3 = @odbc_exec($odbccon,"SELECT * FROM (SELECT TDNBuildingId,CONCAT('FLOORING - ',FlooringDescription) as Descriptions FROM PATAS.TDNBuildingFlooring
                                                                                                            UNION ALL SELECT TDNBuildingId,CONCAT('WALL & PARTITIONS - ',WallsPartitionsDescription) as Descriptions FROM PATAS.TDNBuildingWallsPartitions
                                                                                                            UNION ALL SELECT TDNBuildingId,CONCAT('ROOFING - ',RoofingDescription) as Descriptions FROM PATAS.TDNBuildingRoofing
                                                                                                            ) AS A WHERE a.TDNBuildingId='".$ar2["TDNBuildingId"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                             while(odbc_fetch_row($odbcres3)) {

                                                                    //Build tempory
                                                                    for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                                       $field_name = odbc_field_name($odbcres3, $j);
                                                                      // $this->temp_fieldnames[$j] = $field_name;
                                                                       $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                                    }

                                                                    $obju_Faas2d->prepareadd();
                                                                    $obju_Faas2d->docid = $obju_Faas2->docid;
                                                                    $obju_Faas2d->lineid = getNextIDByBranch("u_rpfaas2d",$objConnection);
                                                                    $obju_Faas2d->setudfvalue("u_selected",1);
                                                                    $obju_Faas2d->setudfvalue("u_prop",$ar3["Descriptions"]);

                                                                    $actionReturn = $obju_Faas2d->add();
                                                                    if (!$actionReturn) break;
                                                            }
                                                            if (!$actionReturn) break;			

                                                            $odbcres3 = @odbc_exec($odbccon,"SELECT * FROM (SELECT TDNBuildingId,CONCAT('FLOORING - ',FlooringDescription) as Descriptions,BuildingFloor FROM PATAS.TDNBuildingFlooring
                                                                                                            UNION ALL SELECT TDNBuildingId,CONCAT('WALL & PARTITIONS - ',WallsPartitionsDescription) as Descriptions,BuildingFloor FROM PATAS.TDNBuildingWallsPartitions) AS A WHERE A.TDNBuildingId='".$ar2["TDNBuildingId"]."' and A.BuildingFloor='".$ar2["SucceedingFloorNo"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
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
                                                                    $obju_Faas2e->setudfvalue("u_prop",$ar3["Descriptions"]);

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
                                                }
                                            }

                                $i++;
                                if (!$actionReturn) break;
                                }else if ($ar["PropertyTypeId"]=="3") {
                                            $num_rows++;
                                            $obju_Faas3->prepareadd();
                                            $obju_Faas3->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                                            $obju_Faas3->docseries = -1;
                                            $obju_Faas3->docno = $ar["TDNId"];
                                            $obju_Faas3->setudfvalue("u_pin",$ar["PIN"]);
                                            $obju_Faas3->setudfvalue("u_prefix",substr($ar["PIN"],0,strlen($ar["PIN"])-5));
                                            $obju_Faas3->setudfvalue("u_suffix",substr($ar["PIN"],strlen($ar["PIN"])-4,4));
                                            $obju_Faas3->setudfvalue("u_tdno",$ar["TDN"]);
                                            $obju_Faas3->setudfvalue("u_varpno",$ar["ARPNo"]);
                                            $obju_Faas3->setudfvalue("u_trxcode",$ar["TDNModeCode"]);

                                            if ($ar["LastName"]=="")$obju_Faas3->setudfvalue("u_ownertype","C");
                                                else $obju_Faas3->setudfvalue("u_ownertype","I");

                                            $obju_Faas3->setudfvalue("u_ownercompanyname",$ar["DisplayName"]);
                                            $obju_Faas3->setudfvalue("u_ownername",$ar["DisplayName"]);
                                            $obju_Faas3->setudfvalue("u_ownerlastname",$ar["LastName"]);
                                            $obju_Faas3->setudfvalue("u_ownerfirstname",$ar["Firstname"]);
                                            $obju_Faas3->setudfvalue("u_ownermiddlename",$ar["Middlename"]);
                                            $obju_Faas3->setudfvalue("u_ownertin",$ar["Localtin"]);
                                            $obju_Faas3->setudfvalue("u_owneraddress",$ar["OwnerAddress"]);

                                            $obju_Faas3->setudfvalue("u_adminname",$ar["PropAdminName"]);
                                            $obju_Faas3->setudfvalue("u_adminaddress",$ar["PropAdminAddress"]);
                                            $obju_Faas3->setudfvalue("u_admintelno",$ar["PropAdminContactNo"]);

                                            $obju_Faas3->setudfvalue("u_street",$ar["STREET"]);
                                            $obju_Faas3->setudfvalue("u_oldbarangay",$ar["BarangayCode"]);
                                            $obju_Faas3->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                            $obju_Faas3->setudfvalue("u_city",$ar["CITY"]);
                                            $obju_Faas3->setudfvalue("u_province",$ar["PROVINCE"]);
                                            $obju_Faas3->setudfvalue("u_revisionyear",$ar["RevisionYear"]);


                                            $obju_Faas3->setudfvalue("u_taxable",$ar["Taxability"]);
                                            $obju_Faas3->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                            $obju_Faas3->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                            $obju_Faas3->setudfvalue("u_effyear",$ar["YearEffectivity"]);

                                            $obju_Faas3->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
                                            $obju_Faas3->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
                                            $obju_Faas3->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
                                            $obju_Faas3->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
                                            $obju_Faas3->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
                                            $obju_Faas3->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
                                            $obju_Faas3->setudfvalue("u_memoranda",$ar["Memoranda"]);
                                            $obju_Faas3->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
                                            $obju_Faas3->setudfvalue("u_cancelled",$ar["IsCancelled"]);
                                            $obju_Faas3->setudfvalue("u_expdate",$ar["DateCancellation"]);

                                            if ($ar["IsApproved"]==1) $obju_Faas3->docstatus = "Approved";
                                                else $obju_Faas3->docstatus = "Encoding";

                                           if ($ar["IsCancelled"]==1) {
                                                $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(TDN),TDN,EndYear,EndQuarter,DateCancellation  FROM PATAS.TDNHistory WHERE ENDYEAR <> '' AND TDN = '".$ar["ARPNo"]."' GROUP BY TDN,EndYear,EndQuarter,DateCancellation ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                    if(odbc_fetch_row($odbcres2)) {
                                                           //Build tempory
                                                           for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                              $field_name = odbc_field_name($odbcres2, $j);
                                                             // $this->temp_fieldnames[$j] = $field_name;
                                                              $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                           }
                                                        $obju_Faas3->setudfvalue("u_cancelled",1);
                                                        $obju_Faas3->setudfvalue("u_expdate",$ar2["DateCancellation"]);
                                                        $obju_Faas3->setudfvalue("u_expqtr",$ar2["EndQuarter"]);
                                                        $obju_Faas3->setudfvalue("u_expyear",$ar2["EndYear"]);
                                                   }	
                                            }

                                            if ($actionReturn) {
                                                $odbcres2 = @odbc_exec($odbccon,"SELECT TDNId,LandOwner,LandOwnerPIN,BuildingOwner,BuildingOwnerPIN FROM PATAS.TDNMachine A
                                                                                WHERE A.TDNID = '".$ar["TDNId"]."'") or die("<B>Error!</B> Couldn't Run Query 15:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                    if(odbc_fetch_row($odbcres2)) {
                                                           //Build tempory
                                                        for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                           $field_name = odbc_field_name($odbcres2, $j);
                                                          // $this->temp_fieldnames[$j] = $field_name;
                                                           $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                        }
                                                        $obju_Faas3->setudfvalue("u_landowner",$ar2["LandOwner"]);
                                                        $obju_Faas3->setudfvalue("u_bldgowner",$ar2["BuildingOwner"]);
                                                        $obju_Faas3->setudfvalue("u_landpin",$ar2["LandOwnerPIN"]);
                                                        $obju_Faas3->setudfvalue("u_bldgpin",$ar2["BuildingOwnerPIN"]);
                                                    }	
                                            }

                                             if($actionReturn){
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNID AS PREVDOCNO,A.TDN AS PREVTD,A.PIN AS PREVPIN,A.OwnerName AS PREVOWNER,A.PreviousAssessedValue,B.YearEffectivity AS PREVEFFYEAR,B.QuarterEffectivity AS PREVEFFQTR,B.RecordingPerson AS PREVRECORDPERSON,B.DateRecorded AS PREVDATERECORD FROM PATAS.TDNHistory A
                                                                                    LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo WHERE A.TDNID = '".$ar["TDNId"]."' ") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                                     while(odbc_fetch_row($odbcres2)) {
                                                            //Build tempory
                                                            for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                               $field_name = odbc_field_name($odbcres2, $j);
                                                              // $this->temp_fieldnames[$j] = $field_name;
                                                               $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                            }
                                                            $obju_Faas3p->prepareadd();
                                                            $obju_Faas3p->docid = $obju_Faas3->docid;
                                                            $obju_Faas3p->lineid = getNextIDByBranch("u_rpfaas1p",$objConnection);
                                                            $obju_Faas3p->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                            $obju_Faas3p->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                            $obju_Faas3p->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                            $obju_Faas3p->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                            $obju_Faas3p->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
                                                            if($ar2["PREVEFFYEAR"]!="" && $ar2["PREVEFFQTR"]!="")$obju_Faas3p->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                            $obju_Faas3p->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                            $obju_Faas3p->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                                            $actionReturn = $obju_Faas3p->add();

                                                            if (!$actionReturn) break;

                                                            $obju_Faas3->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                            $obju_Faas3->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                            $obju_Faas3->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                            $obju_Faas3->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                            $obju_Faas3->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
                                                            if($ar2["PREVEFFYEAR"] !=""&&$ar2["PREVEFFQTR"]!= "") $obju_Faas3->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                            $obju_Faas3->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                            $obju_Faas3->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                                    }	
                                            }

                                            $actionReturn = $obju_Faas3->add();

                                            if ($actionReturn) {
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT A.UnitValue,A.MarketValue,C.TDNId,B.ActualUseCode,a.KindOfMachinery,A.IsTaxable,A.BrandModel,A.Capacity,Year(A.AcquiredDate),ConditionWhenAcquired,EconomicLifeEstimated,A.YearInstalled,A.RCN,A.YearOfInitialOperation,OriginalCost,ConversionFactor,Quantity,DepreciationRate,a.DepreciationTotalValue,DepreciatedValue,A.NoOfYearsUsed FROM PATAS.TDNMachineDetail A
                                                                                    INNER JOIN PATAS.TDNMachine C ON C.TDNMachineId = A.TDNMachineId
                                                                                    INNER JOIN PATAS.AActualUse B ON A.ActualUseId = B.ActualUseId
                                                                                    WHERE C.TDNId ='".$obju_Faas3->docno."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
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
                                                            $obju_Faas3a->setudfvalue("u_arpno",$obju_Faas3->docno);
                                                            $obju_Faas3a->setudfvalue("u_actualuse",trim($ar2["ActualUseCode"]));

                                                            $obju_Faas3a->setudfvalue("u_taxable",$ar2["IsTaxable"]);
                                                            $obju_Faas3a->setudfvalue("u_machine",$ar2["KindOfMachinery"]);
                                                            $obju_Faas3a->setudfvalue("u_brand",$ar2["BrandModel"]);
            //                                                $obju_Faas3a->setudfvalue("u_model",$ar2["MODEL"]);
                                                            $obju_Faas3a->setudfvalue("u_capacity",$ar2["Capacity"]);
                                                            $obju_Faas3a->setudfvalue("u_acqdate",$ar2["AcquiredDate"]);

                                                            $obju_Faas3a->setudfvalue("u_condition",$ar2["ConditionWhenAcquired"]);
                                                            $obju_Faas3a->setudfvalue("u_estlife",$ar2["EconomicLifeEstimated"]);
            //                                                $obju_Faas3a->setudfvalue("u_remlife",$ar2["ESTIMATEDECOLIFE"]-$ar2["REMAININGECOLIFE"]);
                                                            $obju_Faas3a->setudfvalue("u_insyear",$ar2["YearInstalled"]);
                                                            $obju_Faas3a->setudfvalue("u_inityear",$ar2["YearOfInitialOperation"]);

                                                            if($ar2["OriginalCost"] > 0 )$obju_Faas3a->setudfvalue("u_orgcost",$ar2["OriginalCost"]);
                                                            else $obju_Faas3a->setudfvalue("u_orgcost",$ar2["UnitValue"]);

                                                            $obju_Faas3a->setudfvalue("u_cnvfactor",$ar2["ConversionFactor"]);
                                                            $obju_Faas3a->setudfvalue("u_quantity",$ar2["Quantity"]);
                                                            $obju_Faas3a->setudfvalue("u_rcn",$ar2["RCN"]);
                                                            $obju_Faas3a->setudfvalue("u_useyear",$ar2["NoOfYearsUsed"]);
                                                            //$obju_Faas3a->setudfvalue("u_depreratefr",$ar2["ADJMARKETVALUE"]);
                                                            //$obju_Faas3a->setudfvalue("u_deprerateto",$ar2["ADJMARKETVALUE"]);
                                                            $obju_Faas3a->setudfvalue("u_depreperc",$ar2["DepreciationRate"]);
                                                            $obju_Faas3a->setudfvalue("u_deprevalue",$ar2["DepreciationTotalValue"]);

                                                            if($ar2["DepreciatedValue"] > 0 )$obju_Faas3a->setudfvalue("u_remvalue",$ar2["DepreciatedValue"]);
                                                            else $obju_Faas3a->setudfvalue("u_remvalue",$ar2["MarketValue"]);

                                                            $obju_Faas3a->privatedata["header"] = $obju_Faas3;
                                                            if ($actionReturn) $actionReturn = $obju_Faas3a->add();
                                                            if (!$actionReturn) break;
                                                    }	
                                            }
                                }
                                
                        if (!$actionReturn) break;
                            if ($actionReturn) {
                                $objConnection->commit();
//                                var_dump($num_rows);
                            } else {
                                   $objConnection->rollback();
                                   echo $_SESSION["errormessage"];
                           }
                    }
                }
        }
        return $actionReturn;
 }
 
}
 
 require("./inc/formactions.php");
 
?>


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
   
	function onFormSubmit(action) {
            if(action=="migraterptgeo"){
                    if(confirm("Update Geodata Real Property Records?")) return true;
                    else  return false;
            }
	}  
        function onFormSubmitted(action) {
		showAjaxProcess();
                return true;
	}
       
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
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
	  <td class="labelPageHeader" >&nbsp;Update Geodata Records&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>"> </td>
	</tr>
</table></td>
</tr>
<tr class="fillerRow10px"><td></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
<td>
      <div style="border:0px solid gray" >
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td >&nbsp;</td>
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migraterptgeo');return false;">Update GeoData Records</a></td>
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

<?php // $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
<?php require("./bofrms/ajaxprocess.php"); ?>	
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