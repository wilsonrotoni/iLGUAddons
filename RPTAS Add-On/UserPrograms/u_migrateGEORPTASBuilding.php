<?php
	$progid = "u_migrateGEORPTASBuilding";

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
        
        $page->objectcode = "u_migrateGEORPTASBuilding";
	$page->paging->formid = "./UDP.php?&objectcode=u_migrateGEORPTASBuilding";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO RPTAS Building";
        
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
                    
                    $obju_GrYears = new masterdataschema(null,$objConnection,"u_gryears");
                    $obju_Lands = new masterdataschema(null,$objConnection,"u_rplands");
                    $obju_RPLandTrees = new masterdataschema(null,$objConnection,"u_rplandtrees");
                    $obju_RPLandTreeValue = new masterdataschema(null,$objConnection,"u_rplandtreesvalue");
                    $obju_RPLandTreeValues = new masterdatalinesschema(null,$objConnection,"u_rplandtreesvalues");
                    $obju_LandSubclasses = new masterdatalinesschema(null,$objConnection,"u_rplandsubclasses");
                    $obju_LandClasses = new masterdataschema(null,$objConnection,"u_rplandclasses");
                    $obju_ActUses = new masterdataschema(null,$objConnection,"u_rpactuses");
                    $obju_ActUsesAssLevel = new masterdatalinesschema(null,$objConnection,"u_rpactusesasslvl");
                    $obju_Machineries = new masterdataschema(null,$objConnection,"u_rpmachineries");
                    $obju_Improvements = new masterdataschema(null,$objConnection,"u_rpimprovements");
                    $obju_Improvementfmvs = new masterdatalinesschema(null,$objConnection,"u_rpimprovementfmvs");


                    $obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                    
                    $obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
                    $obju_Faas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
                    $obju_Faas2b = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
                    $obju_Faas2c = new documentlinesschema_br(null,$objConnection,"u_rpfaas2c");
                    $obju_Faas2d = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
                    $obju_Faas2e = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");
                    $obju_Faas2p = new documentlinesschema_br(null,$objConnection,"u_rpfaas2p");

                  

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
                
                $odbccon = @odbc_connect("GeoRecords","sa2","12345",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                
           
/*
                  if ($actionReturn) $actionReturn = $obju_Improvements->executesql("delete from u_rpimprovements",false);
                  if ($actionReturn) $actionReturn = $obju_Improvementfmvs->executesql("delete from u_rpimprovementfmvs",false);
//                if ($actionReturn) $actionReturn = $obju_Machineries->executesql("delete from u_rpmachineries",false);
//                if ($actionReturn) $actionReturn = $obju_BldgPropGroups->executesql("delete from u_rpbldgpropgroups",false);
//                if ($actionReturn) $actionReturn = $obju_BldgProps->executesql("delete from u_rpbldgprops",false);
//                if ($actionReturn) $actionReturn = $obju_BldgProps->executesql("delete from u_rpbldgextraitems",false);
//                if ($actionReturn) $actionReturn = $obju_BldgStructTypes->executesql("delete from u_rpbldgstructypes",false);
                  if ($actionReturn) $actionReturn = $obju_BldgKinds->executesql("delete from u_rpbldgkinds",false);
//                if ($actionReturn) $actionReturn = $obju_BldgClasses->executesql("delete from u_rpbldgclasses",false);
//                if ($actionReturn) $actionReturn = $obju_BldgClasses->executesql("delete from u_rpbldgstructclassrates",false);
//                if ($actionReturn) $actionReturn = $obju_MachTypes->executesql("delete from u_rpmachtypes",false);
//                if ($actionReturn) $actionReturn = $obju_MachTypes->executesql("delete from u_rpmachconditions",false);

               
                if ($actionReturn) {

                 $odbcres = @odbc_exec($odbccon,"SELECT A.ActualUseId,B.ActualUseCode,B.ActualUseName FROM PATAS.ABuildingAssessmentLevel A 
                                                    INNER JOIN PATAS.AActualUse B ON A.ActualUseId = B.ActualUseId
                                                    GROUP BY A.ActualUseId,B.ActualUseCode,B.ActualUseName
                                                    ORDER BY A.ActualUseId") or die("<B>Error!</B> Couldn't Run Query 8:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                  while(odbc_fetch_row($odbcres)) {

                         //Build tempory
                         for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                            $field_name = odbc_field_name($odbcres, $j);
                           // $this->temp_fieldnames[$j] = $field_name;
                            $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                         }

                         $obju_Improvements->prepareadd();
                         $obju_Improvements->code = trim($ar["ActualUseCode"]);
                         $obju_Improvements->name = trim($ar["ActualUseName"]);
//                         $obju_Improvements->setudfvalue("u_gryear",$ar["RevisionYear"]);
                         $actionReturn = $obju_Improvements->add();
                         if (!$actionReturn) break;
                            $odbcres3 = @odbc_exec($odbccon,"SELECT B.ActualUseCode,B.ActualUseName,A.LowerBound,A.UpperBound,A.AssessmentLevel,A.RevisionYear FROM PATAS.ABuildingAssessmentLevel A 
                                                            INNER JOIN PATAS.AActualUse B ON A.ActualUseId = B.ActualUseId
                                                            WHERE B.ActualUseCode = '".$ar["ActualUseCode"]."'
                                                            ORDER BY ActualUseCode") or die("<B>Error!</B> Couldn't Run Query 9:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                            while(odbc_fetch_row($odbcres3)) {

                                   for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                      $field_name = odbc_field_name($odbcres3, $j);
                                      $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                   }

                                   $obju_Improvementfmvs->prepareadd();
                                   $obju_Improvementfmvs->code = $obju_Improvements->code;
                                   $obju_Improvementfmvs->lineid = getNextIDByBranch("u_rpimprovementfmvs",$objConnection);
                                   $obju_Improvementfmvs->setudfvalue("u_fmvover",$ar3["LowerBound"]);
                                   $obju_Improvementfmvs->setudfvalue("u_fmvbutnotover",$ar3["UpperBound"]);
                                   $obju_Improvementfmvs->setudfvalue("u_assesslevel",$ar3["AssessmentLevel"]);
                                   $obju_Improvementfmvs->setudfvalue("u_gryear",$ar3["RevisionYear"]);
                                   $actionReturn = $obju_Improvementfmvs->add();
                                   if (!$actionReturn) break;
                           }
                       
                        }		
                }
                if ($actionReturn) {

                 $odbcres = @odbc_exec($odbccon,"SELECT BuildingTypeDescription FROM PATAS.ABuildingType group by BuildingTypeDescription") or die("<B>Error!</B> Couldn't Run Query 10:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                  while(odbc_fetch_row($odbcres)) {

                         //Build tempory
                         for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                            $field_name = odbc_field_name($odbcres, $j);
                           // $this->temp_fieldnames[$j] = $field_name;
                            $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                         }

                         $obju_BldgKinds->prepareadd();
                         $obju_BldgKinds->code = trim($ar["BuildingTypeDescription"]);
                         $obju_BldgKinds->name = $ar["BuildingTypeDescription"];
                         $actionReturn = $obju_BldgKinds->add();
                         if (!$actionReturn) break;
                        }		
                } */
                
//                if ($actionReturn) {
//                    $objRs->queryopen("select count(*) as cnt from u_rpfaas2");
//                    $objRs->queryfetchrow();
//                
//                
//                if ($objRs->fields[0]==0) { 
                     $odbcres = @odbc_exec($odbccon,"SELECT
                                                         A.IsApproved
                                                        ,A.TDNId
                                                        ,A.PropertyTypeId
                                                        ,A.IsTemporaryPIN
							,A.IsTemporaryTDN
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
                                                        ,A.AssessmentEffectivity
                                                        ,A.Taxability
                                                        ,A.TotalAssessedValue
                                                        ,A.TotalArea
                                                        ,A.TotalMarketValue
                                                        ,A.RevisionYear
                                                        ,A.DateRegistration
                                                        ,A.ClassificationId
                                                        ,AC.ClassificationName
                                                        ,AC2.ActualUseName
                                                        ,A.ActualUseId
                                                        
                                                        FROM PATAS.TDN A 
                                                        LEFT JOIN PATAS.ATDNMode B ON A.TDNModeId = B.TDNModeId
                                                        LEFT JOIN DBO.AOwner C ON A.OwnerId = C.OwnerId
                                                        LEFT JOIN PATAS.AOwnerType D ON C.OwnerTypeId = D.OwnerTypeId
                                                        LEFT JOIN DBO.aStreet E ON A.StreetId = E.StreetId
                                                        LEFT JOIN PATAS.AClassification AC ON A.ClassificationId = AC.ClassificationId
                                                        LEFT JOIN PATAS.AActualUse AC2 ON A.ActualUseId = AC2.ActualUseId
                                                        where a.PropertyTypeId = 2 and A.TDNId = '211602'
                                                        ORDER BY A.TDNId ") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
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
                                    $obju_Faas2->setudfvalue("u_cancelled",$ar["IsCancelled"]);
                                    $obju_Faas2->setudfvalue("u_temporarytdn",$ar["IsTemporaryPIN"]);
                                    $obju_Faas2->setudfvalue("u_temporary",$ar["IsTemporaryTDN"]);
                                    $obju_Faas2->setudfvalue("u_tdno",$ar["TDN"]);
                                    $obju_Faas2->setudfvalue("u_varpno",$ar["ARPNo"]);
                                    $obju_Faas2->setudfvalue("u_class",$ar["ClassificationName"]);
                                    $obju_Faas2->setudfvalue("u_actualuse",$ar["ActualUseName"]);
                                    $obju_Faas2->setudfvalue("u_location",$ar["Location"]);
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
                                    $obju_Faas2->setudfvalue("u_revisionyear",$ar["RevisionYear"]);

                                    $obju_Faas2->setudfvalue("u_street",$ar["StreetName"]);
                                    $obju_Faas2->setudfvalue("u_oldbarangay",$ar["BarangayCode"]);
                                    $obju_Faas2->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
                                    $obju_Faas2->setudfvalue("u_city",$ar["CITY"]);
                                    $obju_Faas2->setudfvalue("u_province",$ar["PROVINCE"]);

                                    $obju_Faas2->setudfvalue("u_taxable",$ar["Taxability"]);
                                    
                                    if ($ar["RevisionYear"]=="2003"){
                                    $obju_Faas2->setudfvalue("u_effyear",$ar["AssessmentEffectivity"]);
                                    if ($ar["QuarterEffectivity"] != "1" || $ar["QuarterEffectivity"] != "2" || $ar["QuarterEffectivity"] != "3" || $ar["QuarterEffectivity"] != "4" ) {
                                        $obju_Faas2->setudfvalue("u_effdate",$ar["AssessmentEffectivity"]."-01-01");
                                        $obju_Faas2->setudfvalue("u_effqtr",1);
                                    } else {
                                        $obju_Faas2->setudfvalue("u_effdate",$ar["AssessmentEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                        $obju_Faas2->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                    }
                                    } else {
                                        $obju_Faas2->setudfvalue("u_effyear",$ar["YearEffectivity"]);
                                        if ($ar["QuarterEffectivity"] != "1" || $ar["QuarterEffectivity"] != "2" || $ar["QuarterEffectivity"] != "3" || $ar["QuarterEffectivity"] != "4" ) {
                                            $obju_Faas2->setudfvalue("u_effdate",$ar["YearEffectivity"]."-01-01");
                                            $obju_Faas2->setudfvalue("u_effqtr",1);
                                        } else {
                                            $obju_Faas2->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                            $obju_Faas2->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                        }
                                    }

                                    $obju_Faas2->setudfvalue("u_recordedby",$ar["RecordingPerson"]);
                                    $obju_Faas2->setudfvalue("u_recordeddate",$ar["DateRecorded"]);
                                    $obju_Faas2->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
                                    $obju_Faas2->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
                                    $obju_Faas2->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
                                    $obju_Faas2->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
                                    $obju_Faas2->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
                                    $obju_Faas2->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
                                    $obju_Faas2->setudfvalue("u_memoranda",$ar["Memoranda"]);
                                    $obju_Faas2->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
                                    $obju_Faas2->setudfvalue("u_totalareasqm",$ar["TotalArea"]);
                                    $obju_Faas2->setudfvalue("u_marketvalue",$ar["TotalMarketValue"]);
                                    $obju_Faas2->setudfvalue("u_expdate",$ar["DateCancelled"]);
                                    $obju_Faas2->setudfvalue("u_releaseddate",$ar["DateRegistration"]);
                                    $obju_Faas2->setudfvalue("u_expqtr",$ar2["ExpQtr"]);
                                    $obju_Faas2->setudfvalue("u_expyear",$ar2["ExpYear"]);
                                    $obju_Faas2->docstatus = "Approved";
//
//                                     if ($ar["IsCancelled"]==1) {
//                                        $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(TDN),TDN,EndYear,EndQuarter,DateCancellation  FROM PATAS.TDNHistory WHERE ENDYEAR <> '' AND TDN = '".$ar["ARPNo"]."' GROUP BY TDN,EndYear,EndQuarter,DateCancellation ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                            if(odbc_fetch_row($odbcres2)) {
//
//                                                   //Build tempory
//                                                   for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
//                                                      $field_name = odbc_field_name($odbcres2, $j);
//                                                     // $this->temp_fieldnames[$j] = $field_name;
//                                                      $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
//                                                   }
//                                                $obju_Faas2->setudfvalue("u_cancelled",1);
//                                                $obju_Faas2->setudfvalue("u_expdate",$ar2["DateCancellation"]);
//                                                $obju_Faas2->setudfvalue("u_expqtr",$ar2["EndQuarter"]);
//                                                $obju_Faas2->setudfvalue("u_expyear",$ar2["EndYear"]);
//                                           }	
//                                    }

                                    if ($actionReturn) {
                                        $odbcres2 = @odbc_exec($odbccon,"SELECT C.TDN AS LandRefNo,A.LandOwnerName,A.LandBlkNo,A.BldgName,A.LandTitle,A.LandArea,A.LandSurveyNo,A.LandLotNo,A.KindOfBuilding,A.StructuralType,A.BldgPermitNo,A.DateIssued,A.CondoCertTitle,A.CertOfCompletionIssuedOn,A.CertOfOccupancyIssuedOn,A.NoStorey,A.DateConstructed,A.DateDemolished,A.DateIssued,A.DateOccupied FROM PATAS.TDNBuilding A
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
                                                $obju_Faas2->setudfvalue("u_bldgname",$ar2["BldgName"]);
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
                                                    $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNID AS PREVDOCNO,A.TDN AS PREVTD,A.PIN AS PREVPIN,A.OwnerName AS PREVOWNER,A.PreviousImprovementValue,B.YearEffectivity AS PREVEFFYEAR,B.QuarterEffectivity AS PREVEFFQTR,B.RecordingPerson AS PREVRECORDPERSON,B.DateRecorded AS PREVDATERECORD FROM PATAS.TDNHistory A
                                                                                     LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo AND A.RevisionYear = B.RevisionYear  WHERE A.TDNID = '".$ar["TDNId"]."' AND A.TDN NOT IN('NEW DEC')") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
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
                                                            $obju_Faas2p->setudfvalue("u_prevvalue",$ar2["PreviousImprovementValue"]);
                                                            if($ar2["PREVEFFYEAR"]!="" && $ar2["PREVEFFQTR"]!="")$obju_Faas2p->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                                            $obju_Faas2p->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                                            $obju_Faas2p->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                                            $actionReturn = $obju_Faas2p->add();

                                                            if (!$actionReturn) break;

                                                            $obju_Faas2->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                                            $obju_Faas2->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                                            $obju_Faas2->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                                            $obju_Faas2->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                                            $obju_Faas2->setudfvalue("u_prevvalue",$ar2["PreviousImprovementValue"]);
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
                                            $odbcres2 = @odbc_exec($odbccon,"SELECT E.RevisionYear,E.Taxability,ISNULL(AUGrouping,BuildingNo) AS BuildingNo,A.SucceedingFloorNo,A.TDNBuildingId,b.TDNId,SUBSTRING(A.SucceedingFloorNo,1,3) as FloorNo,B.KindOfBuilding,CONCAT('CLASS ',RIGHT(TRIM(B.StructuralType),1)) as Class,B.StructuralType,C.ActualUseCode,F.ClassificationCode,F.ClassificationCode,D.StructuralDescription,A.Area,B.BldgAge,B.DateConstructed,A.UnitValue,100 AS PercCompleted,A.MarketValue,A.Adjustment,A.AdjustmentValue,B.DepreciationRate,B.DepreciationCost  
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
                                                    $obju_Faas2a->setudfvalue("u_gryear",trim($ar2["RevisionYear"]));

                                                    $obju_Faas2a->setudfvalue("u_taxable",$ar2["Taxability"]);
                                                    $obju_Faas2a->setudfvalue("u_kind",$ar2["KindOfBuilding"]);
                                                    $obju_Faas2a->setudfvalue("u_bldgno",$ar2["BuildingNo"]);
                                                    $obju_Faas2a->setudfvalue("u_bldgdescriptions",$ar2["KindOfBuilding"]);
                                                    $obju_Faas2a->setudfvalue("u_floor",$ar2["FloorNo"]);
                                                    $obju_Faas2a->setudfvalue("u_structuretype",$ar2["StructuralType"]);
                                                    $obju_Faas2a->setudfvalue("u_subclass",$ar2["Class"]);
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
                                         if ($actionReturn) {
                                            $odbcres2 = @odbc_exec($odbccon,"SELECT E.Taxability,C.ActualUseCode,SUM(MarketValue) as MarketValue,ROUND(SUM(AssessmentLevel) / COUNT(*),-1) as Asslvl,ROUND(SUM(AssessedValue),-1) AS AssessedValue
                                                                                FROM PATAS.TDNBuildingDetail A
                                                                                LEFT JOIN PATAS.TDNBuilding B ON A.TDNBuildingId = B.TDNBuildingId
                                                                                LEFT JOIN PATAS.TDN E ON B.TDNId = E.TDNId
                                                                                LEFT JOIN PATAS.AClassification F ON E.ClassificationID = F.ClassificationId
                                                                                LEFT JOIN PATAS.AActualUse C ON A.ActualUseId = C.ActualUseId
                                                                                LEFT JOIN PATAS.ABuildingStructuralType D ON A.BuildingStructuralTypeId = D.BuildingStructuralTypeID  
                                                                                WHERE B.TDNId ='".$obju_Faas2->docno."'
                                                                                GROUP BY A.TDNBuildingId,C.ActualUseCode,E.Taxability ") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                             while(odbc_fetch_row($odbcres2)) {

                                                    //Build tempory
                                                    for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                       $field_name = odbc_field_name($odbcres2, $j);
                                                      // $this->temp_fieldnames[$j] = $field_name;
                                                       $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                    }


                                                    $obju_Faas2c->prepareadd();
                                                    $obju_Faas2c->docid = $obju_Faas2->docid;
                                                    $obju_Faas2c->lineid = getNextIDByBranch("u_rpfaas2c",$objConnection);
                                                    //var_dump(array($obju_Faas2a->docid,$obju_Faas2a->docno));
                                                    $obju_Faas2c->setudfvalue("u_actualuse",trim($ar2["ActualUseCode"]));
                                                    $obju_Faas2c->setudfvalue("u_taxable",$ar2["Taxability"]);
                                                    $obju_Faas2c->setudfvalue("u_asslvl",$ar2["Asslvl"]);
                                                    $obju_Faas2c->setudfvalue("u_assvalue",$ar2["AssessedValue"]);
                                                    $obju_Faas2c->setudfvalue("u_marketvalue",$ar2["MarketValue"]);
                                                    $actionReturn = $obju_Faas2c->add();
                                            }	
                                        }
                                        
                                    }

                                if ($actionReturn) {
                                    $objConnection->commit();
                                } else {
                                    $myfile = fopen("../Addons/GPS/RPTAS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                                    $txt = $_SESSION["errormessage"]."\n";
                                    fwrite($myfile, $txt);
                                    fclose($myfile);
                                    $objConnection->rollback();
                                    echo $_SESSION["errormessage"];
                               }  
                        }
                    
                    if ($actionReturn) {
                     $odbcres = @odbc_exec($odbccon,"SELECT MAX(PAYYEAR) as paidyear,MAX(EndQtr) as paidqtr,a.TDNId,b.PropertyTypeId from GeoTos3.patas.PaymentPosting  a 
                                                     LEFT JOIN GeoRecords2.PATAS.TDN b on a.TDNId = b.TDNId
                                                     where a.IsCancelled <> 1 group by a.TDNId,b.PropertyTypeId order by a.TDNId") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                $actionReturn = true;
                                if ($ar["PropertyTypeId"]=="1") {
                                    if ($obju_Faas1->getbykey($ar["TDNId"])) {
                                        $obju_Faas1->setudfvalue("u_bilyear",$ar["paidyear"]);
                                        $obju_Faas1->setudfvalue("u_bilqtr",$ar["paidqtr"]);
                                        $actionReturn = $obju_Faas1->update($obju_Faas1->docno,$obju_Faas1->rcdversion);
                                    } 
//                                    else $actionReturn = raiseError("Unable to find FAAS Land No. [".$ar["TDNId"]."]");
                                }else if ($ar["PropertyTypeId"]=="2") {
                                    if ($obju_Faas2->getbykey($ar["TDNId"])) {
                                        $obju_Faas2->setudfvalue("u_bilyear",$ar["paidyear"]);
                                        $obju_Faas2->setudfvalue("u_bilqtr",$ar["paidqtr"]);
                                        $actionReturn = $obju_Faas2->update($obju_Faas2->docno,$obju_Faas2->rcdversion);
                                    } 
//                                    else $actionReturn = raiseError("Unable to find FAAS Building No. [".$ar["TDNId"]."]");   
                                }else if ($ar["PropertyTypeId"]=="3") {
                                    if ($obju_Faas3->getbykey($ar["TDNId"])) {
                                        $obju_Faas3->setudfvalue("u_bilyear",$ar["paidyear"]);
                                        $obju_Faas3->setudfvalue("u_bilqtr",$ar["paidqtr"]);
                                        $actionReturn = $obju_Faas3->update($obju_Faas3->docno,$obju_Faas3->rcdversion);
                                    } 
//                                    else $actionReturn = raiseError("Unable to find FAAS Machine No. [".$ar["TDNId"]."]");
                                }
                                
                            if ($actionReturn) {
                                $objConnection->commit();
                            } else {
                                $myfile = fopen("../Addons/GPS/RPTAS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                                $txt = $_SESSION["errormessage"]."\n";
                                fwrite($myfile, $txt);
                                fclose($myfile);
                                $objConnection->rollback();
                                echo $_SESSION["errormessage"];
                           }  
                    }
            }	
        }
        return $actionReturn;
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
                    if(confirm("Migrate Geodata Real Property Records?")) return true;
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
	  <td class="labelPageHeader" >&nbsp;Migrate GEO RPTAS Building&nbsp;</td>
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
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migraterptgeo');return false;">Migrate Data</a></td>
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