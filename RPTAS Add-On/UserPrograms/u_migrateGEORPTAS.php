<?php


        set_time_limit(0);
        
	$progid = "u_migrategeorptas";

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
        
        $page->objectcode = "U_MIGRATEGEORPTAS";
	$page->paging->formid = "./UDP.php?&objectcode=U_MIGRATEGEORPTAS";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO RPTAS";
        $page->settimeout(0);
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
                    
            
                    $obju_OldBarangays = new masterdataschema(null,$objConnection,"u_oldbarangays");
                    $obju_Signatories = new masterdataschema(null,$objConnection,"u_rpassessors");
                    
                    $obju_RPTrxCodes = new masterdataschema(null,$objConnection,"u_rptrxcodes");
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
                
                $odbccon = @odbc_connect("GeoRecords2","","",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                
                
//                if ($actionReturn) $actionReturn = $obju_Signatories->executesql("delete from u_rpassessors",false);
//                if ($actionReturn) $actionReturn = $obju_OldBarangays->executesql("delete from u_oldbarangays",false);
//                if ($actionReturn) $actionReturn = $obju_RPTrxCodes->executesql("delete from u_rptrxcodes",false);
//                if ($actionReturn) $actionReturn = $obju_GrYears->executesql("delete from u_gryears",false);
//                if ($actionReturn) $actionReturn = $obju_Lands->executesql("delete from u_rplands",false);
////              if ($actionReturn) $actionReturn = $obju_LandSubclasses->executesql("delete from u_rplandsubclasses",false);
//                if ($actionReturn) $actionReturn = $obju_RPLandTrees->executesql("delete from u_rplandtrees",false);
//                if ($actionReturn) $actionReturn = $obju_RPLandTreeValue->executesql("delete from u_rplandtreesvalue",false);
//                if ($actionReturn) $actionReturn = $obju_RPLandTreeValues->executesql("delete from u_rplandtreesvalues",false);
//                if ($actionReturn) $actionReturn = $obju_LandClasses->executesql("delete from u_rplandclasses",false);
//                if ($actionReturn) $actionReturn = $obju_ActUses->executesql("delete from u_rpactuses",false);
//                if ($actionReturn) $actionReturn = $obju_ActUsesAssLevel->executesql("delete from u_rpactusesasslvl",false);
//                
//                if ($actionReturn) $actionReturn = $obju_Improvements->executesql("delete from u_rpimprovements",false);
//                if ($actionReturn) $actionReturn = $obju_Improvementfmvs->executesql("delete from u_rpimprovementfmvs",false);
//                if ($actionReturn) $actionReturn = $obju_Machineries->executesql("delete from u_rpmachineries",false);
//                if ($actionReturn) $actionReturn = $obju_BldgPropGroups->executesql("delete from u_rpbldgpropgroups",false);
//                if ($actionReturn) $actionReturn = $obju_BldgProps->executesql("delete from u_rpbldgprops",false);
//                if ($actionReturn) $actionReturn = $obju_BldgProps->executesql("delete from u_rpbldgextraitems",false);
//                if ($actionReturn) $actionReturn = $obju_BldgStructTypes->executesql("delete from u_rpbldgstructypes",false);
//                if ($actionReturn) $actionReturn = $obju_BldgKinds->executesql("delete from u_rpbldgkinds",false);
//                if ($actionReturn) $actionReturn = $obju_BldgClasses->executesql("delete from u_rpbldgclasses",false);
//                if ($actionReturn) $actionReturn = $obju_BldgClasses->executesql("delete from u_rpbldgstructclassrates",false);
//                if ($actionReturn) $actionReturn = $obju_MachTypes->executesql("delete from u_rpmachtypes",false);
//                if ($actionReturn) $actionReturn = $obju_MachTypes->executesql("delete from u_rpmachconditions",false);

               //APPREFNO OF BPLMDS  = Businessdetail_Renewal.Year + '-' + Businessdetail_Renewal.BusinessDetail_RenewalID
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT SignatoryName,SignatoryPosition FROM PATAS.ASignatories
//                                                        GROUP BY SignatoryName,SignatoryPosition") or die("<B>Error!</B> Couldn't Run Query 1:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_Signatories->prepareadd();
//                                $obju_Signatories->code = $ar["SignatoryName"];
//                                $obju_Signatories->name = $ar["SignatoryName"];
//                                $obju_Signatories->setudfvalue("u_position",$ar["SignatoryPosition"]);
//                                $obju_Signatories->setudfvalue("u_approve",1);
//                                $obju_Signatories->setudfvalue("u_assess",1);
//                                $obju_Signatories->setudfvalue("u_recommend",1);
//                                $actionReturn = $obju_Signatories->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT ClassificationCode,ClassificationName FROM PATAS.AClassification;") or die("<B>Error!</B> Couldn't Run Query 2:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_Lands->prepareadd();
//                                $obju_Lands->code = $ar["ClassificationCode"];
//                                $obju_Lands->name = $ar["ClassificationName"];
//                                $actionReturn = $obju_Lands->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT BarangayCode,BarangayName FROM PATAS.aBarangay") or die("<B>Error!</B> Couldn't Run Query 3:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_OldBarangays->prepareadd();
//                                $obju_OldBarangays->code = $ar["BarangayCode"];
//                                $obju_OldBarangays->name = $ar["BarangayName"];
//                                $actionReturn = $obju_OldBarangays->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT TDNMODECODE,TDNMODENAME FROM PATAS.ATDNMode") or die("<B>Error!</B> Couldn't Run Query 4:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_RPTrxCodes->prepareadd();
//                                $obju_RPTrxCodes->code = $ar["TDNMODECODE"];
//                                $obju_RPTrxCodes->name = $ar["TDNMODENAME"];
//                                $actionReturn = $obju_RPTrxCodes->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT REVISIONYEAR,IsDefaultYear FROM PATAS.PRevisionYear") or die("<B>Error!</B> Couldn't Run Query 5:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_GrYears->prepareadd();
//                                $obju_GrYears->code = $ar["REVISIONYEAR"];
//                                $obju_GrYears->name = $ar["REVISIONYEAR"];
//                                $obju_GrYears->setudfvalue("u_isactive",$ar["IsDefaultYear"]);
//                                $actionReturn = $obju_GrYears->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT AgriTypeId,AgriTypeDescription FROM PATAS.ALandAgriTypeBU") or die("<B>Error!</B> Couldn't Run Query 6:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_LandClasses->prepareadd();
//                                $obju_LandClasses->code = $ar["AgriTypeDescription"];
//                                $obju_LandClasses->name = $ar["AgriTypeDescription"];
//                                $actionReturn = $obju_LandClasses->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT distinct(TypeDescription) as TypeDescription  FROM PATAS.AImprovementType") or die("<B>Error!</B> Couldn't Run Query 7:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_RPLandTrees->prepareadd();
//                                $obju_RPLandTrees->code = $ar["TypeDescription"];
//                                $obju_RPLandTrees->name = $ar["TypeDescription"];
//                                $actionReturn = $obju_RPLandTrees->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//
//                 $odbcres = @odbc_exec($odbccon,"SELECT RevisionYear FROM PATAS.ALandImprovementAssessment GROUP BY RevisionYear") or die("<B>Error!</B> Couldn't Run Query 8:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                  while(odbc_fetch_row($odbcres)) {
//
//                         //Build tempory
//                         for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                            $field_name = odbc_field_name($odbcres, $j);
//                           // $this->temp_fieldnames[$j] = $field_name;
//                            $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                         }
//
//                         $obju_RPLandTreeValue->prepareadd();
//                         $obju_RPLandTreeValue->code = trim($ar["RevisionYear"]);
//                         $obju_RPLandTreeValue->setudfvalue("u_gryear",$ar["RevisionYear"]);
//                         $actionReturn = $obju_RPLandTreeValue->add();
//                         if (!$actionReturn) break;
//
//                            $odbcres3 = @odbc_exec($odbccon,"SELECT B.TypeDescription,C.AgriTypeDescription,A.RevisionYear,A.BaseValue FROM PATAS.ALandImprovementAssessment A  INNER JOIN PATAS.AImprovementType B ON A.TypeId = B.TypeId INNER JOIN PATAS.ALandAgriTypeBU C ON A.Class = C.AgriTypeId WHERE a.RevisionYear='".$ar["RevisionYear"]."'") or die("<B>Error!</B> Couldn't Run Query 9:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                            while(odbc_fetch_row($odbcres3)) {
//
//                                   for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
//                                      $field_name = odbc_field_name($odbcres3, $j);
//                                      $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
//                                   }
//
//                                   $obju_RPLandTreeValues->prepareadd();
//                                   $obju_RPLandTreeValues->code = $obju_RPLandTreeValue->code;
//                                   $obju_RPLandTreeValues->lineid = getNextIDByBranch("u_rplandtreesvalues",$objConnection);
//                                   $obju_RPLandTreeValues->setudfvalue("u_type",$ar3["TypeDescription"]);
//                                   $obju_RPLandTreeValues->setudfvalue("u_class",$ar3["AgriTypeDescription"]);
//                                   $obju_RPLandTreeValues->setudfvalue("u_unitvalue",$ar3["BaseValue"]);
//                                   $actionReturn = $obju_RPLandTreeValues->add();
//                                   if (!$actionReturn) break;
//                           }
//                       
//                        }		
//                }
//                if ($actionReturn) {
//
//                 $odbcres = @odbc_exec($odbccon,"SELECT ActualUseID,ActualUseCode,ActualUseName FROM PATAS.AActualUse WHERE ActualUseCode <> '' ") or die("<B>Error!</B> Couldn't Run Query 10:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                  while(odbc_fetch_row($odbcres)) {
//
//                         //Build tempory
//                         for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                            $field_name = odbc_field_name($odbcres, $j);
//                           // $this->temp_fieldnames[$j] = $field_name;
//                            $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                         }
//
//                         $obju_ActUses->prepareadd();
//                         $obju_ActUses->code = trim($ar["ActualUseCode"]);
//                         $obju_ActUses->name = $ar["ActualUseName"];
//                         $actionReturn = $obju_ActUses->add();
//                         if (!$actionReturn) break;
//
//                          $odbcres3 = @odbc_exec($odbccon,"SELECT a.REVISIONYEAR,a.AssessmentLevel,b.ActualUseCode,a.ActualUseId FROM PATAS.ALandAssessmentLevel a INNER JOIN PATAS.AActualUse B on a.ActualUseId = b.ActualUseId WHERE a.ActualUseId='".$ar["ActualUseID"]."'") or die("<B>Error!</B> Couldn't Run Query 11:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                          while(odbc_fetch_row($odbcres3)) {
//
//                                 for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
//                                    $field_name = odbc_field_name($odbcres3, $j);
//                                    $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
//                                 }
//
//                                 $obju_ActUsesAssLevel->prepareadd();
//                                 $obju_ActUsesAssLevel->code = $obju_ActUses->code;
//                                 $obju_ActUsesAssLevel->lineid = getNextIDByBranch("u_rpactusesasslvl",$objConnection);
//                                 $obju_ActUsesAssLevel->setudfvalue("u_gryear",$ar3["REVISIONYEAR"]);
//                                 $obju_ActUsesAssLevel->setudfvalue("u_assesslevel",$ar3["AssessmentLevel"]);
//
//                                 $actionReturn = $obju_ActUsesAssLevel->add();
//                                 if (!$actionReturn) break;
//                         }
//                         if (!$actionReturn) break;			
//
//                        }		
//                }
               
//                if ($actionReturn) {
//                    $objRs->queryopen("select count(*) as cnt from u_rpfaas1");
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
                                                        where a.PropertyTypeId = 1
                                                        ORDER BY A.TDNId ") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
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
                                $obju_Faas1->setudfvalue("u_claimant","no");
                                $obju_Faas1->setudfvalue("u_cancelled",$ar["IsCancelled"]);
                                $obju_Faas1->setudfvalue("u_temporarytdn",$ar["IsTemporaryPIN"]);
                                $obju_Faas1->setudfvalue("u_temporary",$ar["IsTemporaryTDN"]);
                                $obju_Faas1->setudfvalue("u_pin",$ar["PIN"]);
                                $obju_Faas1->setudfvalue("u_tdno",$ar["TDN"]);
                                $obju_Faas1->setudfvalue("u_varpno",$ar["ARPNo"]);
                                $obju_Faas1->setudfvalue("u_tctno",$ar["TCTNo"]);
                                $obju_Faas1->setudfvalue("u_tctdate",$ar["TCTDate"]);
                                $obju_Faas1->setudfvalue("u_location",$ar["Location"]);
                                $obju_Faas1->setudfvalue("u_class",$ar["ClassificationName"]);
                                $obju_Faas1->setudfvalue("u_actualuse",$ar["ActualUseName"]);
                                
                                $obju_Faas1->setudfvalue("u_trxcode",$ar["TDNModeCode"]);
                                $obju_Faas1->setudfvalue("u_phase",$ar["Phase"]);
                                $obju_Faas1->setudfvalue("u_titleno",$ar["TitleNo"]);
                                $obju_Faas1->setudfvalue("u_lotno",$ar["LotNo"]);
                                $obju_Faas1->setudfvalue("u_block",$ar["BlockNo"]);
                                $obju_Faas1->setudfvalue("u_surveyno",$ar["CadastralSurveyNo"]);
                                
                                if ($ar["LastName"]=="")$obju_Faas1->setudfvalue("u_ownertype","C");
                                else $obju_Faas1->setudfvalue("u_ownertype","I");
                                
                                if ($ar["RevisionYear"]=="2003"){
                                    $obju_Faas1->setudfvalue("u_effyear",$ar["AssessmentEffectivity"]);
                                    if ($ar["QuarterEffectivity"] != "1" || $ar["QuarterEffectivity"] != "2" || $ar["QuarterEffectivity"] != "3" || $ar["QuarterEffectivity"] != "4" ) {
                                        $obju_Faas1->setudfvalue("u_effdate",$ar["AssessmentEffectivity"]."-01-01");
                                        $obju_Faas1->setudfvalue("u_effqtr",1);
                                    } else {
                                        $obju_Faas1->setudfvalue("u_effdate",$ar["AssessmentEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                        $obju_Faas1->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                    }
                                } else {
                                    $obju_Faas1->setudfvalue("u_effyear",$ar["YearEffectivity"]);
                                    if ($ar["QuarterEffectivity"] != "1" || $ar["QuarterEffectivity"] != "2" || $ar["QuarterEffectivity"] != "3" || $ar["QuarterEffectivity"] != "4" ) {
                                        $obju_Faas1->setudfvalue("u_effdate",$ar["YearEffectivity"]."-01-01");
                                        $obju_Faas1->setudfvalue("u_effqtr",1);
                                    } else {
                                        $obju_Faas1->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                        $obju_Faas1->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                    }
                                }
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

                                $obju_Faas1->setudfvalue("u_street",$ar["StreetName"]);
                                $obju_Faas1->setudfvalue("u_oldbarangay",$ar["BarangayCode"]);
                                $obju_Faas1->setudfvalue("u_city",$ar["CITY"]);
                                $obju_Faas1->setudfvalue("u_province",$ar["PROVINCE"]);
                               

                                $obju_Faas1->setudfvalue("u_taxable",$ar["Taxability"]);
                               	
                                $obju_Faas1->setudfvalue("u_recordedby",$ar["RecordingPerson"]);
                                $obju_Faas1->setudfvalue("u_recordeddate",$ar["DateRecorded"]);
                                $obju_Faas1->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
                                $obju_Faas1->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
                                $obju_Faas1->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
                                $obju_Faas1->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
                                $obju_Faas1->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
                                $obju_Faas1->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
                                $obju_Faas1->setudfvalue("u_memoranda",$ar["Memoranda"]);
                                $obju_Faas1->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
                                $obju_Faas1->setudfvalue("u_totalareasqm",$ar["TotalArea"]);
                                $obju_Faas1->setudfvalue("u_marketvalue",$ar["TotalMarketValue"]);
                                $obju_Faas1->setudfvalue("u_expdate",$ar["DateCancelled"]);
                                $obju_Faas1->setudfvalue("u_releaseddate",$ar["DateRegistration"]);
                                $obju_Faas1->setudfvalue("u_expqtr",$ar2["ExpQtr"]);
                                $obju_Faas1->setudfvalue("u_expyear",$ar2["ExpYear"]);
                                $obju_Faas1->docstatus = "Approved";
                                
//                                
//                                 if ($ar["IsCancelled"]==1) {
//                                        $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(TDN),TDN,EndYear,EndQuarter,DateCancellation  FROM PATAS.TDNHistory WHERE ENDYEAR <> '' AND TDN = '".$ar["ARPNo"]."' GROUP BY TDN,EndYear,EndQuarter,DateCancellation ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                            if(odbc_fetch_row($odbcres2)) {
//                                                   //Build tempory
//                                                   for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
//                                                      $field_name = odbc_field_name($odbcres2, $j);
//                                                     // $this->temp_fieldnames[$j] = $field_name;
//                                                      $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
//                                                   }
//                                                $obju_Faas1->setudfvalue("u_cancelled",1);
//                                                $obju_Faas1->setudfvalue("u_expdate",$ar2["DateCancellation"]);
//                                                $obju_Faas1->setudfvalue("u_expqtr",$ar2["EndQuarter"]);
//                                                $obju_Faas1->setudfvalue("u_expyear",$ar2["EndYear"]);
//                                           }	
//                                }
                                
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
                                                                         LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo  AND A.RevisionYear = B.RevisionYear  WHERE A.TDNID = '".$ar["TDNId"]."' AND A.TDN NOT IN('NEW DEC')") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
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
                                        $odbcres2 = @odbc_exec($odbccon,"SELECT A.TDNID,B.TDNLandId,CONCAT(C.ClassificationCode,'-',AA.RevisionYear) AS ClassificationCode,AA.RevisionYear,B.ClassificationID,B.AssessmentLevel,D.ActualUseCode FROM PATAS.TDNLand A
                                                                            LEFT JOIN PATAS.TDN AA ON A.TDNID = AA.TDNId AND AA.PropertyTypeId = 1
                                                                            INNER JOIN PATAS.TDNLandDetail B ON A.TDNLandID = B.TDNLandId
                                                                            LEFT JOIN PATAS.AClassification C ON B.ClassificationID = C.ClassificationId
                                                                            LEFT JOIN PATAS.AActualUse D ON B.ActualUseId = D.ActualUseId
                                                                            WHERE A.TDNID = '".$obju_Faas1->docno."'
                                                                            GROUP BY AA.RevisionYear,A.TDNID,B.TDNLandId,CONCAT(C.ClassificationCode,'-',AA.RevisionYear),B.ClassificationID,B.AssessmentLevel,D.ActualUseCode
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
                                                $obju_Faas1a->setudfvalue("u_gryear",$ar2["RevisionYear"]);
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
                                                        if($ar3["AgriTypeDescription"] != '')  $obju_Faas1b->setudfvalue("u_subclass",trim($ar3["AgriTypeDescription"]));
                                                        $obju_Faas1b->setudfvalue("u_description",trim($ar3["LandDescription"]));
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
                        }
//                        if (!$actionReturn) break;
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

                
                    
                    
//        }
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
	  <td class="labelPageHeader" >&nbsp;Migrate GEO RPTAS Land&nbsp;</td>
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