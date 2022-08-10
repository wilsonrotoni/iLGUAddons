<?php



//        
//	$progid = "u_migrategeorptas";
//
//	if(!empty($_POST)) extract($_POST);
//	if(!empty($_GET)) extract($_GET);
//	$httpVars = array_merge($_POST,$_GET);
//	
//	include_once("../common/classes/connection.php");
//	include_once("../common/classes/recordset.php");
//	include_once("../common/classes/grid.php");
//	include_once("./classes/masterdataschema.php");
//	include_once("./classes/masterdatalinesschema.php");
//	include_once("./classes/documentschema_br.php");
//	include_once("./classes/documentlinesschema_br.php");
//	include_once("./classes/masterdatalinesschema_br.php");
//	include_once("./utils/companies.php");
//	include_once("./inc/formutils.php"); 
////	include_once("./inc/formaccess.php");
//        
//
//
//        global $objConnection;
//        global $page;
//        $actionReturn = true;


                    
            
//                    $obju_OldBarangays = new masterdataschema(null,$objConnection,"u_oldbarangays");
//                    $obju_Signatories = new masterdataschema(null,$objConnection,"u_rpassessors");
//                    
//                    $obju_RPTrxCodes = new masterdataschema(null,$objConnection,"u_rptrxcodes");
//                    $obju_GrYears = new masterdataschema(null,$objConnection,"u_gryears");
//                    $obju_Lands = new masterdataschema(null,$objConnection,"u_rplands");
//                    $obju_RPLandTrees = new masterdataschema(null,$objConnection,"u_rplandtrees");
//                    $obju_RPLandTreeValue = new masterdataschema(null,$objConnection,"u_rplandtreesvalue");
//                    $obju_RPLandTreeValues = new masterdatalinesschema(null,$objConnection,"u_rplandtreesvalues");
//                    $obju_LandSubclasses = new masterdatalinesschema(null,$objConnection,"u_rplandsubclasses");
//                    $obju_LandClasses = new masterdataschema(null,$objConnection,"u_rplandclasses");
//                    $obju_ActUses = new masterdataschema(null,$objConnection,"u_rpactuses");
//                    $obju_ActUsesAssLevel = new masterdatalinesschema(null,$objConnection,"u_rpactusesasslvl");
//                    $obju_Machineries = new masterdataschema(null,$objConnection,"u_rpmachineries");
//                    $obju_Improvements = new masterdataschema(null,$objConnection,"u_rpimprovements");
//                    $obju_Improvementfmvs = new masterdatalinesschema(null,$objConnection,"u_rpimprovementfmvs");
//
//                    $obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
//                    $obju_Faas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
//                    $obju_Faas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
//                    $obju_Faas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
//                    $obju_Faas1d = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
//                    $obju_Faas1p = new documentlinesschema_br(null,$objConnection,"u_rpfaas1p");
//
//                    $obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
//                    $obju_Faas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
//                    $obju_Faas2b = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
//                    $obju_Faas2d = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
//                    $obju_Faas2e = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");
//
//                    $obju_Faas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
//                    $obju_Faas3a = new documentschema_br(null,$objConnection,"u_rpfaas3a");
//                    $obju_Faas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");
//
//                    $obju_BldgPropGroups = new masterdataschema(null,$objConnection,"u_rpbldgpropgroups");
//                    $obju_BldgProps = new masterdataschema(null,$objConnection,"u_rpbldgprops");
//                    $obju_BldgExtraItems = new masterdataschema(null,$objConnection,"u_rpbldgextraitems");
//
//                    $obju_BldgStructTypes = new masterdataschema(null,$objConnection,"u_rpbldgstructypes");
//                    $obju_BldgKinds = new masterdataschema(null,$objConnection,"u_rpbldgkinds");
//                    $obju_BldgClasses = new masterdataschema(null,$objConnection,"u_rpbldgclasses");
//                    $obju_BldgStructureClassRates = new masterdataschema(null,$objConnection,"u_rpbldgstructclassrates");
//
//                    $obju_MachTypes = new masterdataschema(null,$objConnection,"u_rpmachtypes");
//                    $obju_MachConditions = new masterdataschema(null,$objConnection,"u_rpmachconditions");
//
//                    $obju_Faas1Prev = new documentschema_br(null,$objConnection,"u_rpfaas1");
//                    $obju_Faas2Prev = new documentschema_br(null,$objConnection,"u_rpfaas2");
//                    $obju_Faas3Prev = new documentschema_br(null,$objConnection,"u_rpfaas3");
                    
                    
//                $objConnection->beginwork();
                
                $odbccon = @odbc_connect("GeoRecords","","",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                 $odbcres = @odbc_exec($odbccon,"SELECT
                                                       
                                                         A.IsApproved
                                                        ,A.TDNId
                                                        ,A.PropertyTypeId
                                                        ,A.Location
                                                        ,PIN
                                                        ,A.IsCancelled
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
                                                        FROM PATAS.TDN A 
                                                        LEFT JOIN PATAS.ATDNMode B ON A.TDNModeId = B.TDNModeId
                                                        LEFT JOIN DBO.AOwner C ON A.OwnerId = C.OwnerId
                                                        LEFT JOIN PATAS.AOwnerType D ON C.OwnerTypeId = D.OwnerTypeId
                                                        LEFT JOIN DBO.aStreet E ON A.StreetId = E.StreetId
                                                        where a.PropertyTypeId = 1
                                                        ORDER BY A.TDNId") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                echo $ar["TDNId"]. " ";
                         }
                        
//                
//                if ($actionReturn) $actionReturn = $obju_Signatories->executesql("delete from u_rpassessors",false);
//                if ($actionReturn) $actionReturn = $obju_OldBarangays->executesql("delete from u_oldbarangays",false);
//                if ($actionReturn) $actionReturn = $obju_RPTrxCodes->executesql("delete from u_rptrxcodes",false);
//                if ($actionReturn) $actionReturn = $obju_GrYears->executesql("delete from u_gryears",false);
//              if ($actionReturn) $actionReturn = $obju_Lands->executesql("delete from u_rplands",false);
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
//               
//                if ($actionReturn) {
//                    $objRs->queryopen("select count(*) as cnt from u_rpfaas1");
//                    $objRs->queryfetchrow();
//                
//                
//                if ($objRs->fields[0]==0) {
//                     $odbcres = @odbc_exec($odbccon,"SELECT
//                                                       
//                                                         A.IsApproved
//                                                        ,A.TDNId
//                                                        ,A.PropertyTypeId
//                                                        ,A.Location
//                                                        ,PIN
//                                                        ,A.IsCancelled
//                                                        ,A.TDN
//                                                        ,A.ARPNo
//                                                        ,A.TCTDate
//                                                        ,A.TCTNo
//                                                        ,B.TDNModeCode
//                                                        ,A.Phase
//                                                        ,A.TitleNo
//                                                        ,A.CadastralSurveyNo
//                                                        ,A.LotNo
//                                                        ,A.BlockNo
//                                                        ,A.DisplayName
//                                                        ,A.OwnerAddress
//                                                        ,A.OwnerId AS Localtin
//                                                        ,C.LastName
//                                                        ,C.FirstName
//                                                        ,C.MiddleName
//                                                        ,D.OwnerTypeDescription as Ownerkind
//                                                        ,A.PropAdminName
//                                                        ,A.PropAdminContactNo
//                                                        ,A.PropAdminAddress
//                                                        ,a.BarangayCode
//                                                        ,E.StreetName
//                                                        ,'BACOOR' AS CITY
//                                                        ,'CAVITE' AS PROVINCE
//                                                        ,A.AppraiseAssessedBy
//                                                        ,A.DateAppraised
//                                                        ,A.RecomApprovalName
//                                                        ,A.DateRecommendedApproval
//                                                        ,A.DateApprovedByAssessor
//                                                        ,A.CityMunAssessorName
//                                                        ,A.Memoranda
//                                                        ,A.Annotation
//                                                        ,A.DateRecorded
//                                                        ,A.RecordingPerson
//                                                        ,A.PrevOwner
//                                                        ,A.SupersededRecordingPerson
//                                                        ,A.YearEffectivity
//                                                        ,A.QuarterEffectivity
//                                                        ,A.Taxability
//                                                        ,A.TotalAssessedValue
//                                                        FROM PATAS.TDN A 
//                                                        LEFT JOIN PATAS.ATDNMode B ON A.TDNModeId = B.TDNModeId
//                                                        LEFT JOIN DBO.AOwner C ON A.OwnerId = C.OwnerId
//                                                        LEFT JOIN PATAS.AOwnerType D ON C.OwnerTypeId = D.OwnerTypeId
//                                                        LEFT JOIN DBO.aStreet E ON A.StreetId = E.StreetId
//                                                        where a.PropertyTypeId = 1 and A.YearEffectivity < 2012
//                                                        ORDER BY A.YearEffectivity,A.TDNId DESC") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                
//                               if ($ar["PropertyTypeId"]=="1") {
//                                $num_rows++;
//                                $obju_Faas1->prepareadd();
//                                $obju_Faas1->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
//                                $obju_Faas1->docseries = -1;
//                                $obju_Faas1->docno = $ar["TDNId"];
//                                $obju_Faas1->setudfvalue("u_cancelled",$ar["IsCancelled"]);
//                                $obju_Faas1->setudfvalue("u_pin",$ar["PIN"]);
//                                $obju_Faas1->setudfvalue("u_tdno",$ar["TDN"]);
//                                $obju_Faas1->setudfvalue("u_varpno",$ar["ARPNo"]);
//                                $obju_Faas1->setudfvalue("u_tctno",$ar["TCTNo"]);
//                                $obju_Faas1->setudfvalue("u_tctdate",$ar["TCTDate"]);
//                                $obju_Faas1->setudfvalue("u_location",$ar["Location"]);
//                                
//                                $obju_Faas1->setudfvalue("u_trxcode",$ar["TDNModeCode"]);
//                                $obju_Faas1->setudfvalue("u_phase",$ar["Phase"]);
//                                $obju_Faas1->setudfvalue("u_titleno",$ar["TitleNo"]);
//                                $obju_Faas1->setudfvalue("u_lotno",$ar["LotNo"]);
//                                $obju_Faas1->setudfvalue("u_block",$ar["BlockNo"]);
//                                $obju_Faas1->setudfvalue("u_cadlotno",$ar["CadastralSurveyNo"]);
//                                
//                                 if ($ar["LastName"]=="")$obju_Faas1->setudfvalue("u_ownertype","C");
//                                else $obju_Faas1->setudfvalue("u_ownertype","I");
//                                
//                                $obju_Faas1->setudfvalue("u_ownercompanyname",$ar["DisplayName"]);
//                                $obju_Faas1->setudfvalue("u_ownername",$ar["DisplayName"]);
//                                $obju_Faas1->setudfvalue("u_ownerlastname",$ar["LastName"]);
//                                $obju_Faas1->setudfvalue("u_ownerfirstname",$ar["Firstname"]);
//                                $obju_Faas1->setudfvalue("u_ownermiddlename",$ar["Middlename"]);
//                                $obju_Faas1->setudfvalue("u_ownertin",$ar["Localtin"]);
//                                
//                                $obju_Faas1->setudfvalue("u_owneraddress",$ar["OwnerAddress"]);
//                                $obju_Faas1->setudfvalue("u_adminname",$ar["PropAdminName"]);
//                                $obju_Faas1->setudfvalue("u_adminaddress",$ar["PropAdminAddress"]);
//                                $obju_Faas1->setudfvalue("u_admintelno",$ar["PropAdminContactNo"]);
//
//                                $obju_Faas1->setudfvalue("u_street",$ar["STREET"]);
//                                $obju_Faas1->setudfvalue("u_oldbarangay",$ar["BarangayCode"]);
//                                $obju_Faas1->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
//                                $obju_Faas1->setudfvalue("u_city",$ar["CITY"]);
//                                $obju_Faas1->setudfvalue("u_province",$ar["PROVINCE"]);
//
//                               
//
//                                $obju_Faas1->setudfvalue("u_taxable",$ar["Taxability"]);
//                                $obju_Faas1->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
//                                $obju_Faas1->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
//                                $obju_Faas1->setudfvalue("u_effyear",$ar["YearEffectivity"]);
//                               	
//                                $obju_Faas1->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
//                                $obju_Faas1->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
//                                $obju_Faas1->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
//                                $obju_Faas1->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
//                                $obju_Faas1->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
//                                $obju_Faas1->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
//                                $obju_Faas1->setudfvalue("u_memoranda",$ar["Memoranda"]);
//                                $obju_Faas1->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
//                                
//                                 if ($ar["IsCancelled"]==1) {
//                                        $odbcres2 = @odbc_exec($odbccon,"SELECT COUNT(TDN),TDN,EndYear,EndQuarter,DateCancellation  FROM PATAS.TDNHistory WHERE ENDYEAR <> '' AND TDN = '".$ar["ARPNo"]."' GROUP BY TDN,EndYear,EndQuarter,DateCancellation ") or die("<B>Error!</B> Couldn't Run Query 13:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                            if(odbc_fetch_row($odbcres2)) {
//
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
//                                
//                                if ($ar["IsApproved"]==1) $obju_Faas1->docstatus = "Approved";
//                                else $obju_Faas1->docstatus = "Encoding";
//                                
//                                if ($actionReturn) {
//                                    $odbcres2 = @odbc_exec($odbccon,"SELECT NorthBoundary,SouthBoundary,EastBoundary,WestBoundary FROM PATAS.TDNLand WHERE TDNID = '".$ar["TDNId"]."'") or die("<B>Error!</B> Couldn't Run Query 15:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                        if(odbc_fetch_row($odbcres2)) {
//                                               //Build tempory
//                                            for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
//                                               $field_name = odbc_field_name($odbcres2, $j);
//                                              // $this->temp_fieldnames[$j] = $field_name;
//                                               $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
//                                            }
//                                            $obju_Faas1->setudfvalue("u_north",$ar2["NorthBoundary"]);
//                                            $obju_Faas1->setudfvalue("u_south",$ar2["SouthBoundary"]);
//                                            $obju_Faas1->setudfvalue("u_east",$ar2["EastBoundary"]);
//                                            $obju_Faas1->setudfvalue("u_west",$ar2["WestBoundary"]);
//                                    }	
//                                }
//                                
//                                if($actionReturn){
//                                        $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNID AS PREVDOCNO,A.TDN AS PREVTD,A.PIN AS PREVPIN,A.OwnerName AS PREVOWNER,A.PreviousAssessedValue,B.YearEffectivity AS PREVEFFYEAR,B.QuarterEffectivity AS PREVEFFQTR,B.RecordingPerson AS PREVRECORDPERSON,B.DateRecorded AS PREVDATERECORD FROM PATAS.TDNHistory A
//                                                                        LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo WHERE A.TDNID = '".$ar["TDNId"]."' AND A.TDN NOT IN('NEW DEC') ") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                         if(odbc_fetch_row($odbcres2)) {
//                                                //Build tempory
//                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
//                                                   $field_name = odbc_field_name($odbcres2, $j);
//                                                  // $this->temp_fieldnames[$j] = $field_name;
//                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
//                                                }
//                                                $obju_Faas1p->prepareadd();
//                                                $obju_Faas1p->docid = $obju_Faas1->docid;
//                                                $obju_Faas1p->lineid = getNextIDByBranch("u_rpfaas1p",$objConnection);
//                                                $obju_Faas1p->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
//                                                $obju_Faas1p->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
//                                                $obju_Faas1p->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
//                                                $obju_Faas1p->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
//                                                $obju_Faas1p->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
//                                                if($ar2["PREVEFFYEAR"]!="" && $ar2["PREVEFFQTR"]!="")$obju_Faas1p->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
//                                                $obju_Faas1p->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
//                                                $obju_Faas1p->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
//                                                $actionReturn = $obju_Faas1p->add();
//                                                
//                                                if (!$actionReturn) break;
//                                                
//                                                $obju_Faas1->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
//                                                $obju_Faas1->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
//                                                $obju_Faas1->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
//                                                $obju_Faas1->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
//                                                $obju_Faas1->setudfvalue("u_prevvalue",$ar2["PreviousAssessedValue"]);
//                                                if($ar2["PREVEFFYEAR"] !=""&&$ar2["PREVEFFQTR"]!= "") $obju_Faas1->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
//                                                $obju_Faas1->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
//                                                $obju_Faas1->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
//                                        }	
//                                }
//                                
//                              
//                              		
//                                $actionReturn = $obju_Faas1->add();
//
//                                if ($actionReturn) {
//                                        $faas1acnt = 0;
//                                        $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNLandId,B.ClassificationID,C.ClassificationCode,B.AssessmentLevel,D.ActualUseCode FROM PATAS.TDNLand A
//                                                                            INNER JOIN PATAS.TDNLandDetail B ON A.TDNLandID = B.TDNLandId
//                                                                            INNER JOIN PATAS.AClassification C ON B.ClassificationID = C.ClassificationId
//                                                                            INNER JOIN PATAS.AActualUse D ON B.ActualUseId = D.ActualUseId
//                                                                            WHERE A.TDNID = '".$obju_Faas1->docno."'
//                                                                            GROUP BY B.TDNLandId,B.ClassificationID,ClassificationCode,B.AssessmentLevel,D.ActualUseCode") or die("<B>Error!</B> Couldn't Run Query 16:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                         while(odbc_fetch_row($odbcres2)) {
//                                                    
//                                                //Build tempory
//                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
//                                                   $field_name = odbc_field_name($odbcres2, $j);
//                                                  // $this->temp_fieldnames[$j] = $field_name;
//                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
//                                                }
//                                                $faas1acnt ++;
//                                                $obju_Faas1a->prepareadd();
//                                                $obju_Faas1a->docid = getNextIDByBranch("u_rpfaas1a",$objConnection);
//                                                $obju_Faas1a->docno = getNextNoByBranch("u_rpfaas1a",'',$objConnection);
//                                                //var_dump(array($obju_Faas1a->docid,$obju_Faas1a->docno));
//                                                $obju_Faas1a->setudfvalue("u_arpno",$obju_Faas1->docno);
//                                                $obju_Faas1a->setudfvalue("u_class",trim($ar2["ClassificationCode"]));
//                                                
//                                                $area =0;
//                                                $totalunitvalue =0;
//                                                $unitvalue =0;
//                                                $adj=0;
//                                                $basevalue =0;
//                                                $assvalue =0;
//                                                $odbcres3 = @odbc_exec($odbccon,"SELECT C.ClassificationCode,C.ClassificationName,B.LandDescription,B.AreaSQM,B.Unit,B.UnitValue,B.MarketValue,LandAdjustments,LandAdjustedMarketValue,B.CornerLotRate,B.InteriorLotRate,B.TopographyRate,B.CostOfFillingRate
//                                                                                    FROM PATAS.TDNLandDetail B
//                                                                                    LEFT JOIN PATAS.AClassification C ON B.ClassificationID = C.ClassificationId 
//                                                                                    WHERE B.TDNLandId = '".$ar2["TDNLandId"]."' AND B.ClassificationID	= '".$ar2["ClassificationID"]."'") or die("<B>Error!</B> Couldn't Run Query 17:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                                   while(odbc_fetch_row($odbcres3)) {
//                                                        for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
//                                                            $field_name = odbc_field_name($odbcres3, $j);
//                                                           // $this->temp_fieldnames[$j] = $field_name;
//                                                            $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
//                                                         }
//                                                         
//                                                        $obju_Faas1b->prepareadd();
//                                                        $obju_Faas1b->docid = $obju_Faas1a->docid;
//                                                        $obju_Faas1b->lineid = getNextIDByBranch("u_rpfaas1b",$objConnection);
//                                                        $obju_Faas1b->setudfvalue("u_subclass",trim($ar3["LandDescription"]));
//                                                        if($ar3["Unit"] == "sqm.") $unitvalue = $ar3["UnitValue"];
//                                                        else $unitvalue = $ar3["UnitValue"] / 10000;
//                                                        $obju_Faas1b->setudfvalue("u_sqm",floatval($ar3["AreaSQM"]));
//                                                        $obju_Faas1b->setudfvalue("u_unitvalue",$unitvalue);
//                                                        $obju_Faas1b->setudfvalue("u_basevalue",$ar3["MarketValue"]);
//                                                        $area += $ar3["AreaSQM"];
//                                                        $totalunitvalue += $ar3["UnitValue"];
//                                                        $basevalue += $ar3["MarketValue"];
//                                                        
//                                                        $actionReturn = $obju_Faas1b->add();
//                                                        if (!$actionReturn) break;
//                                                        
//                                                        if($actionReturn){
//                                                            if($ar3["LandAdjustments"] <> 0 ){
//                                                                $obju_Faas1c->prepareadd();
//                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
//                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
//                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["LandAdjustments"]);
//                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["LandAdjustments"] / 100));
//
//                                                                $actionReturn = $obju_Faas1c->add();
//                                                                if (!$actionReturn) break;
//                                                                $adj += $ar3["MarketValue"] * ($ar3["LandAdjustments"] / 100);
//                                                            }
//                                                        }
//                                                        if($actionReturn){
//                                                            if($ar3["CornerLotRate"] <> 0){
//                                                                $obju_Faas1c->prepareadd();
//                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
//                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
//                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Corner Lot");
//                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["CornerLotRate"]);
//                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["CornerLotRate"] / 100));
//
//                                                                $actionReturn = $obju_Faas1c->add();
//                                                                if (!$actionReturn) break;
//                                                                $adj += $ar3["MarketValue"] * ($ar3["CornerLotRate"] / 100);
//                                                            }
//                                                        }
//                                                        if($actionReturn){
//                                                            if($ar3["InteriorLotRate"] <> 0){
//                                                                $obju_Faas1c->prepareadd();
//                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
//                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
//                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Interior Lot");
//                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["InteriorLotRate"]);
//                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["InteriorLotRate"] / 100));
//
//                                                                $actionReturn = $obju_Faas1c->add();
//                                                                if (!$actionReturn) break;
//                                                                $adj += $ar3["MarketValue"] * ($ar3["InteriorLotRate"] / 100);
//                                                            }
//                                                        }
//                                                        if($actionReturn){
//                                                            if($ar3["TopographyRate"] <> 0){
//                                                                $obju_Faas1c->prepareadd();
//                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
//                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
//                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Topography");
//                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["TopographyRate"]);
//                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["TopographyRate"] / 100));
//
//                                                                $actionReturn = $obju_Faas1c->add();
//                                                                if (!$actionReturn) break;
//                                                                $adj += $ar3["MarketValue"] * ($ar3["TopographyRate"] / 100);
//                                                            }
//                                                        }
//                                                        if($actionReturn){
//                                                            if($ar3["CostOfFillingRate"] <> 0){
//                                                                $obju_Faas1c->prepareadd();
//                                                                $obju_Faas1c->docid = $obju_Faas1a->docid;
//                                                                $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
//                                                                $obju_Faas1c->setudfvalue("u_adjfactor","Cost of Filling");
//                                                                $obju_Faas1c->setudfvalue("u_adjperc",$ar3["CostOfFillingRate"]);
//                                                                $obju_Faas1c->setudfvalue("u_adjvalue",$ar3["MarketValue"] * ($ar3["CostOfFillingRate"] / 100));
//
//                                                                $actionReturn = $obju_Faas1c->add();
//                                                                if (!$actionReturn) break;
//                                                                $adj += $ar3["MarketValue"] * ($ar3["CostOfFillingRate"] / 100);
//                                                            }
//                                                        }
//                                                }
//                                               // var_dump($faas1acnt . " f ");
//                                                if($faas1acnt <= 1 ){
//                                                        $odbcres4 = @odbc_exec($odbccon,"SELECT B.TypeDescription,C.AgriTypeDescription,A.Quantity,A.UnitValue,(A.MarketValue/A.Quantity) AS UnitValue2,A.MarketValue 
//                                                                                        FROM PATAS.TDNLandImprovements A
//                                                                                        LEFT JOIN PATAS.AImprovementType B ON A.TypeId = B.TypeId
//                                                                                        LEFT JOIN PATAS.ALandAgriTypeBU C ON A.ImprovementClass = C.AgriTypeId
//                                                                                        WHERE A.TDNLandId = '".$ar2["TDNLandId"]."'") or die("<B>Error!</B> Couldn't Run Query 18:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                                                        while(odbc_fetch_row($odbcres4)) {
//                                                             for ($j = 1; $j <= odbc_num_fields($odbcres4); $j++) {
//                                                                 $field_name = odbc_field_name($odbcres4, $j);
//                                                                // $this->temp_fieldnames[$j] = $field_name;
//                                                                 $ar4[$field_name] = odbc_result($odbcres4, $field_name) . "";
//                                                              }
//
//                                                             $obju_Faas1d->prepareadd();
//                                                             $obju_Faas1d->docid = $obju_Faas1a->docid;
//                                                             $obju_Faas1d->lineid = getNextIDByBranch("u_rpfaas1d",$objConnection);
//                                                             $obju_Faas1d->setudfvalue("u_planttype",trim($ar4["TypeDescription"]));
//                                                             $obju_Faas1d->setudfvalue("u_class",$ar4["AgriTypeDescription"]);
//                                                             $obju_Faas1d->setudfvalue("u_productive",$ar4["Quantity"]);
//                                                             $obju_Faas1d->setudfvalue("u_totalcount",$ar4["Quantity"]);
//                                                             $obju_Faas1d->setudfvalue("u_unitvalue",floatval($ar4["UnitValue2"]));
//                                                             $obju_Faas1d->setudfvalue("u_marketvalue",$ar4["MarketValue"]);
//                                                             $totalunitvalue += $ar4["UnitValue"];
//                                                             $basevalue += $ar4["MarketValue"];
//
//                                                             $actionReturn = $obju_Faas1d->add();
//                                                             if (!$actionReturn) break;
//                                                         }
//                                                }
//                                                
//                                                $obju_Faas1a->setudfvalue("u_sqm",floatval($area));
//                                                $obju_Faas1a->setudfvalue("u_basevalue",$basevalue);
//                                                $obju_Faas1a->setudfvalue("u_adjvalue",$adj);
//                                                $obju_Faas1a->setudfvalue("u_marketvalue",$basevalue+$adj);
//                                                $obju_Faas1a->setudfvalue("u_actualuse",trim($ar2["ActualUseCode"]));
//                                                $obju_Faas1a->setudfvalue("u_asslvl",$ar2["AssessmentLevel"]);
//                                                $assvalue = ($basevalue+$adj) * ($ar2["AssessmentLevel"] / 100);
//                                                $obju_Faas1a->setudfvalue("u_assvalue", round($assvalue / 10) * 10);
//
//                                                if ($actionReturn) $actionReturn = $obju_Faas1a->add();
//                                                if (!$actionReturn) break;
//                                        }	
//
//                                }
//                        }
//                        if (!$actionReturn) break;
//                            if ($actionReturn) {
//                                $objConnection->commit();
////                                var_dump($num_rows);
//                            } else {
//                                   $objConnection->rollback();
//                                   echo $_SESSION["errormessage"];
//                           }
//                    }
//                }
//                
//         
////        if ($objRs->fields[0]==0 && $actionReturn) {
//// 
//// 	$objRsFees = new recordset(null,$objConnection);
////	$objRsFees->queryopen("select A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A 
////							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
////							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
////							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
////							LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
////							LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE");
////	if (!$objRsFees->queryfetchrow("NAME")) {
////		return raiseError("No setup found for real property tax fees.");
////	}	
////	
////	$odbcres = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.LOCAL_TIN, a.PAIDBY, a.PAYMENTDATE, a.VALUEDATE, a.AMOUNT, a.PAYMODE_CT, a.CHECKNO, a.RECEIPTNO, a.PRINT_BV, a.STATUS_CT, a.REMARK, a.USERID, a.TRANSDATE, a.PAYGROUP_CT, a.ORB_ID, a.DCL_BV, a.RCDNUMBER, a.AFTYPE
////        FROM PAYMENT a where PAYMENTDATE>='2017-01-01' AND PAYMODE_CT='CSH' AND STATUS_CT='SAV' ORDER BY PAYMENTDATE, RECEIPTNO") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
////	while(odbc_fetch_row($odbcres)) {	
////		//Build tempory
////		for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
////		   $field_name = odbc_field_name($odbcres, $j);
////		  // $this->temp_fieldnames[$j] = $field_name;
////		   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
////		}
////		//if (!$obju_Pos->getbykey($ar["RECEIPTNO"])) {
////			$num_rows++;
////			//var_dump($ar["PAYMENT_ID"]);
////			$obju_Taxes->prepareadd();
////			$obju_Taxes->docseries = -1;
////			$obju_Taxes->docno = getNextNoByBranch("u_lgubills",'',$objConnection);
////			$obju_Taxes->docid = getNextIDByBranch("u_rptaxes",$objConnection);
////			$obju_Taxes->docstatus = "C";
////			$obju_Taxes->setudfvalue("u_migrated",1);
////			$obju_Taxes->setudfvalue("u_paymode","A");
////			$obju_Taxes->setudfvalue("u_tin",$ar["LOCAL_TIN"]);
////			$obju_Taxes->setudfvalue("u_declaredowner",$ar["PAIDBY"]);
////			$obju_Taxes->setudfvalue("u_assdate",$ar["PAYMENTDATE"]);
////			$obju_Taxes->setudfvalue("u_ornumber",$ar["RECEIPTNO"]);
////			$obju_Taxes->setudfvalue("u_ordate",$ar["PAYMENTDATE"]);
////			$obju_Taxes->setudfvalue("u_paidamount",$ar["AMOUNT"]);
////			
////			
////			$tax=0;
////			$penalty=0;
////			$sef=0;
////			$sefpenalty=0;
////			$taxdisc=0;
////			$sefdisc=0;
////			$total=0;
////			$odbcres3 = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.PROPERTYKIND_CT, c.PINNO, a.TAXTRANS_ID, b.TDNO, a.TAXYEAR, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='REG',a.AMOUNT,0)) AS TAX, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='PEN',a.AMOUNT,0)) AS TAXPEN, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='DED',a.AMOUNT,0)) AS TAXDISC,
////                                                        SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='REG',a.AMOUNT,0)) AS SEF, SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='PEN',a.AMOUNT,0)) AS SEFPEN, SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='DED',a.AMOUNT,0)) AS SEFDISC
////                                                        FROM PAYMENTCLASSDETAIL a LEFT JOIN RPTASSESSMENT b ON b.TAXTRANS_ID=a.TAXTRANS_ID LEFT JOIN PROPERTY c ON c.PROP_ID=b.PROP_ID WHERE a.PAYMENT_ID='".$ar["PAYMENT_ID"]."' GROUP BY PAYMENT_ID, a.PROPERTYKIND_CT, c.PINNO, a.TAXTRANS_ID, b.TDNO, a.TAXYEAR") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
////			 while(odbc_fetch_row($odbcres3)) {
////				//Build tempory
////				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
////				   $field_name = odbc_field_name($odbcres3, $j);
////				  // $this->temp_fieldnames[$j] = $field_name;
////				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
////				}
////				
////				$obju_Taxes->setudfvalue("u_yearfrom",$ar3["TAXYEAR"]);
////				$obju_Taxes->setudfvalue("u_yearto",$ar3["TAXYEAR"]);
////			
////				//if ($ar["PAYMENT_ID"]==21297) var_dump( $ar3);
////				$obju_TaxArps->prepareadd();
////				$obju_TaxArps->docid = $obju_Taxes->docid;
////				$obju_TaxArps->lineid = getNextIDByBranch("u_rptaxarps",$objConnection);
////				$obju_TaxArps->setudfvalue("u_selected",1);
////				$obju_TaxArps->setudfvalue("u_kind",iif($ar3["PROPERTYKIND_CT"]=="L","LAND",iif($ar3["PROPERTYKIND_CT"]=="B","BUILDING","MACHINERY")));
////				$obju_TaxArps->setudfvalue("u_pinno",$ar3["PINNO"]);
////				$obju_TaxArps->setudfvalue("u_arpno",$ar3["TAXTRANS_ID"]);
////				$obju_TaxArps->setudfvalue("u_tdno",$ar3["TDNO"]);
////				$obju_TaxArps->setudfvalue("u_assvalue",0);
////				$obju_TaxArps->setudfvalue("u_yrfr",$ar3["TAXYEAR"]);
////				$obju_TaxArps->setudfvalue("u_yrto",$ar3["TAXYEAR"]);
////				$obju_TaxArps->setudfvalue("u_taxdue",$ar3["TAX"]);
////				$obju_TaxArps->setudfvalue("u_penalty",$ar3["TAXPEN"]);
////				$obju_TaxArps->setudfvalue("u_billdate",$ar["PAYMENTDATE"]);
////				$obju_TaxArps->setudfvalue("u_sef",$ar3["SEF"]);
////				$obju_TaxArps->setudfvalue("u_sefpenalty",$ar3["SEFPEN"]);
////				$obju_TaxArps->setudfvalue("u_discperc",($ar3["TAXDISC"]/$ar3["TAX"])*100);
////				$obju_TaxArps->setudfvalue("u_taxdisc",$ar3["TAXDISC"]*-1);
////				$obju_TaxArps->setudfvalue("u_sefdisc",$ar3["SEFDISC"]*-1);
////				$obju_TaxArps->setudfvalue("u_taxtotal",$ar3["TAX"]+$ar3["TAXDISC"]+$ar3["TAXPEN"]);
////				$obju_TaxArps->setudfvalue("u_seftotal",$ar3["SEF"]+$ar3["SEFDISC"]+$ar3["SEFPEN"]);
////				$tax+=$ar3["TAX"];
////				$penalty+=$ar3["TAXPEN"];
////				$sef+=$ar3["SEF"];
////				$sefpenalty+=$ar3["SEFPEN"];
////				$taxdisc+=$ar3["TAXDISC"]*-1;
////				$sefdisc+=$ar3["SEFDISC"]*-1;
////				$total+=$ar3["TAX"]-$ar3["TAXDISC"]+$ar3["TAXPEN"]+$ar3["SEF"]+$ar3["SEFDISC"]+$ar3["SEFPEN"];
////				$obju_TaxArps->privatedata["header"] = $obju_Taxes;
////				$actionReturn = $obju_TaxArps->add();
////				if (!$actionReturn) break;			
////				
////				$obju_Taxes->setudfvalue("u_yearfrom",$ar["TAXYEAR"]);
////				$obju_Taxes->setudfvalue("u_yearto",$ar["TAXYEAR"]);
////					
////
////				
////			}
////			if (!$actionReturn) break;			
////
////			$obju_Taxes->setudfvalue("u_tax",$tax);
////			$obju_Taxes->setudfvalue("u_seftax",$sef);
////			$obju_Taxes->setudfvalue("u_discamount",$taxdisc);
////			$obju_Taxes->setudfvalue("u_sefdiscamount",$sefdisc);
////			$obju_Taxes->setudfvalue("u_penalty",$penalty);
////			$obju_Taxes->setudfvalue("u_sefpenalty",$sefpenalty);
////			$obju_Taxes->setudfvalue("u_totaltaxamount",$total);
////			if ($ar["PAYMENT_ID"]==21297) var_dump(array($tax,$penalty,$sef,$sefpenalty,$taxdisc,$sefdisc,$total));
////			if ($obju_Taxes->getudfvalue("u_totaltaxamount")!=$total) {
////					$actionReturn = raiseError("Mismatch Payment Form Total [".$obju_Taxes->getudfvalue("u_totaltaxamount")."] and Payment details total [".$totalamount."] for Payment ID [".$obju_Pos->docno."].");
////			}
////			$actionReturn = $obju_Taxes->add();
////				
////			if (!$actionReturn) break;
////			
////			
////			$obju_Pos->prepareadd();
////			$obju_Pos->docseries = -1;
////			$obju_Pos->docno = "ITAX:".$ar["PAYMENT_ID"];;
////			$obju_Pos->setudfvalue("u_orno",$ar["RECEIPTNO"]);
////			$obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
////			$obju_Pos->setudfvalue("u_custno",$ar["LOCAL_TIN"]);
////			$obju_Pos->setudfvalue("u_custname",$ar["PAIDBY"]);
////			$obju_Pos->setudfvalue("u_status",iif($ar["STATUS_CT"]=="CNL","CN","C"));
////			$obju_Pos->setudfvalue("u_date",$ar["PAYMENTDATE"]);
////			$obju_Pos->setudfvalue("u_paidamount",$ar["AMOUNT"]);
////			$obju_Pos->setudfvalue("u_totalamount",$ar["AMOUNT"]);
////			$obju_Pos->setudfvalue("u_totalbefdisc",$ar["AMOUNT"]);
////			$obju_Pos->setudfvalue("u_trxtype","S");
////			$obju_Pos->setudfvalue("u_cashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
////			$obju_Pos->setudfvalue("u_chequeamount",iif($ar["PAYMODE_CT"]!="CSH",$ar["AMOUNT"],0));
////			$obju_Pos->setudfvalue("u_collectedcashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
////			$obju_Pos->setudfvalue("u_profitcenter","RP");
////			$obju_Pos->setudfvalue("u_module","Real Property Tax");
////			$obju_Pos->setudfvalue("u_paymode",$obju_Taxes->getudfvalue("u_paymode"));
////			if ($obju_Taxes->getudfvalue("u_totaltaxamount")>0) {
////				$obju_Pos->setudfvalue("u_billno",$obju_Taxes->docno);
////				$obju_Pos->setudfvalue("u_billdate",$obju_Taxes->getudfvalue("u_assdate"));
////				$obju_Pos->setudfvalue("u_billduedate",$obju_Taxes->getudfvalue("u_assdate"));
////			}	
////			$obju_Pos->setudfvalue("u_doccnt",1);
////			//$obju_Pos->setudfvalue("u_remark",$ar["REMARK"]);
////
////			$totalamount=0;
////			$odbcres3 = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.ITAXTYPE_CT, SUM(a.AMOUNTPAID) AS AMOUNTPAID, SUM(IIF(a.CASETYPE_CT='PEN',0,a.AMOUNTPAID)) AS TAX, SUM(IIF(a.CASETYPE_CT='PEN',a.AMOUNTPAID,0)) AS PEN
////                                                        FROM PAYMENTDETAIL a WHERE a.PAYMENT_ID='".$ar["PAYMENT_ID"]."' GROUP BY PAYMENT_ID, ITAXTYPE_CT") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
////			 while(odbc_fetch_row($odbcres3)) {
////			
////				//Build tempory
////				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
////				   $field_name = odbc_field_name($odbcres3, $j);
////				  // $this->temp_fieldnames[$j] = $field_name;
////				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
////				}
////				
////				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="BSC" && $ar3["TAX"]>0) {
////					if ($objRsFees->fields["U_RPPROPTAXFEECODE"]=="") return raiseError("No setup found for Real Property Tax.");
////					$obju_PosItems->prepareadd();
////					$obju_PosItems->docid = $obju_Pos->docid;
////					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
////					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXFEECODE"]);
////					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXFEEDESC"]);
////					$obju_PosItems->setudfvalue("u_quantity",1);
////					$obju_PosItems->setudfvalue("u_unitprice",$ar3["TAX"]);
////					$obju_PosItems->setudfvalue("u_price",$ar3["TAX"]);
////					$obju_PosItems->setudfvalue("u_linetotal",$ar3["TAX"]);
////					$totalamount+=$ar3["TAX"];
////					$obju_PosItems->privatedata["header"] = $obju_Pos;
////					$actionReturn = $obju_PosItems->add();
////				
////
////                                }	
////				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="SEF" && $ar3["TAX"]>0) {
////					if ($objRsFees->fields["U_RPSEFFEECODE"]=="") return raiseError("No setup found for SEF.");
////					$obju_PosItems->prepareadd();
////					$obju_PosItems->docid = $obju_Pos->docid;
////					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
////					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFFEECODE"]);
////					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFFEEDESC"]);
////					$obju_PosItems->setudfvalue("u_quantity",1);
////					$obju_PosItems->setudfvalue("u_unitprice",$ar3["TAX"]);
////					$obju_PosItems->setudfvalue("u_price",$ar3["TAX"]);
////					$obju_PosItems->setudfvalue("u_linetotal",$ar3["TAX"]);
////					$totalamount+=$ar3["TAX"];
////					$obju_PosItems->privatedata["header"] = $obju_Pos;
////				
////                                    $actionReturn = $obju_PosItems->add();
////				}	
////
////				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="BSC" && $ar3["PEN"]>0) {
////					if ($objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]=="") return raiseError("No setup found for Real Property Tax - Penalty.");
////					$obju_PosItems->prepareadd();
////					$obju_PosItems->docid = $obju_Pos->docid;
////					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
////					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]);
////					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXPENALTYFEEDESC"]);
////					$obju_PosItems->setudfvalue("u_quantity",1);
////					$obju_PosItems->setudfvalue("u_unitprice",$ar3["PEN"]);
////					$obju_PosItems->setudfvalue("u_price",$ar3["PEN"]);
////					$obju_PosItems->setudfvalue("u_linetotal",$ar3["PEN"]);
////					$totalamount+=$ar3["PEN"];
////					$obju_PosItems->privatedata["header"] = $obju_Pos;
////					$actionReturn = $obju_PosItems->add();
////
////                                        }	
////				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="SEF" && $ar3["PEN"]>0) {
////					if ($objRsFees->fields["U_RPSEFPENALTYFEECODE"]=="") return raiseError("No setup found for SEF - Penalty.");
////					$obju_PosItems->prepareadd();
////					$obju_PosItems->docid = $obju_Pos->docid;
////					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
////					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFPENALTYFEECODE"]);
////					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFPENALTYFEEDESC"]);
////					$obju_PosItems->setudfvalue("u_quantity",1);
////					$obju_PosItems->setudfvalue("u_unitprice",$ar3["PEN"]);
////					$obju_PosItems->setudfvalue("u_price",$ar3["PEN"]);
////					$obju_PosItems->setudfvalue("u_linetotal",$ar3["PEN"]);
////					$totalamount+=$ar3["PEN"];
////					$obju_PosItems->privatedata["header"] = $obju_Pos;
////					$actionReturn = $obju_PosItems->add();
////
////                                        }	
////				
////			}
////			if (!$actionReturn) break;			
////			
////			if ($obju_Pos->getudfvalue("u_totalamount")!=$totalamount) {
////					$actionReturn = raiseError("Mismatch Header Total [".$obju_Pos->getudfvalue("u_totalamount")."] and Payment details total [".$totalamount."] for Payment ID [".$obju_Pos->docno."].");
////			}
////			$actionReturn = $obju_Pos->add();
////
////                        if (!$actionReturn) break;
////
////		//} else {
////			//echo $ar["RECEIPTNO"] ."</br>";
////                        	 //var_dump($actionReturn);
////		}	
////            }
//        }	
//
//                
//                    
//                    
     
?>


