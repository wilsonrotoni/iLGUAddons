<?php
	$progid = "u_migrateGEORPTASMachinery";

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
        
        $page->objectcode = "u_migrateGEORPTASMachinery";
	$page->paging->formid = "./UDP.php?&objectcode=u_migrateGEORPTASMachinery";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO RPTAS Machinery";
        
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if ($action=="migraterptgeo") {   
                    
                    $obju_OldBarangays = new masterdataschema(null,$objConnection,"u_oldbarangays");
                    $obju_Signatories = new masterdataschema(null,$objConnection,"u_rpassessors");
                    
                    $obju_Machineries = new masterdataschema(null,$objConnection,"u_rpmachineries");


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
                
                $odbccon = @odbc_connect("GeoRecords","","",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                
//                if ($actionReturn) $actionReturn = $obju_Machineries->executesql("delete from u_rpmachineries",false);
//                if ($actionReturn) $actionReturn = $obju_MachTypes->executesql("delete from u_rpmachtypes",false);
               // if ($actionReturn) $actionReturn = $obju_MachConditions->executesql("delete from u_rpmachconditions",false);

               //APPREFNO OF BPLMDS  = Businessdetail_Renewal.Year + '-' + Businessdetail_Renewal.BusinessDetail_RenewalID
            
//                if ($actionReturn) {
//	
//		$odbcres = @odbc_exec($odbccon,"SELECT B.ActualUseCode,b.ActualUseName,a.AssessmentLevel,a.RevisionYear FROM PATAS.AMachineAssessmentLevel A
//INNER JOIN PATAS.AActualUse B ON A.ActualUseId = B.ActualUseId WHERE RevisionYear = '2011'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//
//                                 while(odbc_fetch_row($odbcres)) {
//
//                                        //Build tempory
//                                        for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                           $field_name = odbc_field_name($odbcres, $j);
//                                          // $this->temp_fieldnames[$j] = $field_name;
//                                           $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                        }
//                                        $obju_Machineries->prepareadd();
//                                        $obju_Machineries->code = $ar["ActualUseCode"];
//                                        $obju_Machineries->name = $ar["ActualUseName"];
//                                        $obju_Machineries->setudfvalue("u_assesslevel",$ar["AssessmentLevel"]);
//                                        $obju_Machineries->setudfvalue("u_gryear",$ar["RevisionYear"]);
//                                        $actionReturn = $obju_Machineries->add();
//                                        if (!$actionReturn) break;
//                                }		
//                }
                
//               if ($actionReturn) {
//                 $odbcres = @odbc_exec($odbccon,"SELECT KindOfMachinery FROM PATAS.TDNMachineDetail WHERE KindOfMachinery <> '' GROUP BY KindOfMachinery") or die("<B>Error!</B> Couldn't Run Query 10:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                  while(odbc_fetch_row($odbcres)) {
//                         //Build tempory
//                         for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                            $field_name = odbc_field_name($odbcres, $j);
//                           // $this->temp_fieldnames[$j] = $field_name;
//                            $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                         }
//                         $obju_MachTypes->prepareadd();
//                         $obju_MachTypes->code = trim($ar["KindOfMachinery"]);
//                         $obju_MachTypes->name = $ar["KindOfMachinery"];
//                         $actionReturn = $obju_MachTypes->add();
//                         if (!$actionReturn) break;
//                        }		
//                }
                
//                if ($actionReturn) {
//                   $objRs->queryopen("select count(*) as cnt from u_rpfaas3");
//                    $objRs->queryfetchrow();
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
                                                        where a.PropertyTypeId = 3
                                                        ORDER BY A.TDNId ") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
		if ($ar["PropertyTypeId"]=="3") {
                               $num_rows++;
                                $obju_Faas3->prepareadd();
                                $obju_Faas3->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                                $obju_Faas3->docseries = -1;
                                $obju_Faas3->docno = $ar["TDNId"];
                                $obju_Faas3->setudfvalue("u_pin",$ar["PIN"]);
                                $obju_Faas3->setudfvalue("u_prefix",substr($ar["PIN"],0,strlen($ar["PIN"])-5));
                                $obju_Faas3->setudfvalue("u_suffix",substr($ar["PIN"],strlen($ar["PIN"])-4,4));
                                $obju_Faas3->setudfvalue("u_cancelled",$ar["IsCancelled"]);
                                $obju_Faas3->setudfvalue("u_temporarytdn",$ar["IsTemporaryPIN"]);
                                $obju_Faas3->setudfvalue("u_temporary",$ar["IsTemporaryTDN"]);
                                $obju_Faas3->setudfvalue("u_tdno",$ar["TDN"]);
                                $obju_Faas3->setudfvalue("u_varpno",$ar["ARPNo"]);
                                $obju_Faas3->setudfvalue("u_class",$ar["ClassificationName"]);
                                $obju_Faas3->setudfvalue("u_actualuse",$ar["ActualUseName"]);
                                $obju_Faas3->setudfvalue("u_location",$ar["Location"]);
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
                                
                                if ($ar["RevisionYear"]=="2003"){
                                    $obju_Faas3->setudfvalue("u_effyear",$ar["AssessmentEffectivity"]);
                                    if ($ar["QuarterEffectivity"] != "1" || $ar["QuarterEffectivity"] != "2" || $ar["QuarterEffectivity"] != "3" || $ar["QuarterEffectivity"] != "4" ) {
                                        $obju_Faas3->setudfvalue("u_effdate",$ar["AssessmentEffectivity"]."-01-01");
                                        $obju_Faas3->setudfvalue("u_effqtr",1);
                                    } else {
                                        $obju_Faas3->setudfvalue("u_effdate",$ar["AssessmentEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                        $obju_Faas3->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                    }
                                } else {
                                        $obju_Faas3->setudfvalue("u_effyear",$ar["YearEffectivity"]);
                                        if ($ar["QuarterEffectivity"] != "1" || $ar["QuarterEffectivity"] != "2" || $ar["QuarterEffectivity"] != "3" || $ar["QuarterEffectivity"] != "4" ) {
                                            $obju_Faas3->setudfvalue("u_effdate",$ar["YearEffectivity"]."-01-01");
                                            $obju_Faas3->setudfvalue("u_effqtr",1);
                                        } else {
                                            $obju_Faas3->setudfvalue("u_effdate",$ar["YearEffectivity"]."-".$ar["QuarterEffectivity"]."-01");
                                            $obju_Faas3->setudfvalue("u_effqtr",$ar["QuarterEffectivity"]);
                                        }
                                }
                                
                                    $obju_Faas3->setudfvalue("u_recordedby",$ar["RecordingPerson"]);
                                    $obju_Faas3->setudfvalue("u_recordeddate",$ar["DateRecorded"]);
                                    $obju_Faas3->setudfvalue("u_assessedby",$ar["AppraiseAssessedBy"]);
                                    $obju_Faas3->setudfvalue("u_assesseddate",$ar["DateAppraised"]);
                                    $obju_Faas3->setudfvalue("u_recommendby",$ar["RecomApprovalName"]);
                                    $obju_Faas3->setudfvalue("u_recommenddate",$ar["DateRecommendedApproval"]);
                                    $obju_Faas3->setudfvalue("u_approvedby",$ar["CityMunAssessorName"]);
                                    $obju_Faas3->setudfvalue("u_approveddate",$ar["DateApprovedByAssessor"]);
                                    $obju_Faas3->setudfvalue("u_memoranda",$ar["Memoranda"]);
                                    $obju_Faas3->setudfvalue("u_assvalue",$ar["TotalAssessedValue"]);
                                    $obju_Faas3->setudfvalue("u_totalareasqm",$ar["TotalArea"]);
                                    $obju_Faas3->setudfvalue("u_marketvalue",$ar["TotalMarketValue"]);
                                    $obju_Faas3->setudfvalue("u_expdate",$ar["DateCancelled"]);
                                    $obju_Faas3->setudfvalue("u_releaseddate",$ar["DateRegistration"]);
                                    $obju_Faas3->setudfvalue("u_expqtr",$ar2["ExpQtr"]);
                                    $obju_Faas3->setudfvalue("u_expyear",$ar2["ExpYear"]);
                                    $obju_Faas3->docstatus = "Approved";


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
                                    $odbcres2 = @odbc_exec($odbccon,"SELECT B.TDNID AS PREVDOCNO,A.TDN AS PREVTD,A.PIN AS PREVPIN,A.OwnerName AS PREVOWNER,A.PreviousImprovementValue,B.YearEffectivity AS PREVEFFYEAR,B.QuarterEffectivity AS PREVEFFQTR,B.RecordingPerson AS PREVRECORDPERSON,B.DateRecorded AS PREVDATERECORD FROM PATAS.TDNHistory A
                                                                     LEFT JOIN PATAS.TDN B ON A.TDN = B.ARPNo AND A.RevisionYear = B.RevisionYear  WHERE A.TDNID = '".$ar["TDNId"]."' AND A.TDN NOT IN('NEW DEC')") or die("<B>Error!</B> Couldn't Run Query 14:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                     while(odbc_fetch_row($odbcres2)) {
                                            //Build tempory
                                            for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                               $field_name = odbc_field_name($odbcres2, $j);
                                              // $this->temp_fieldnames[$j] = $field_name;
                                               $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                            }

                                            $obju_Faas3p->prepareadd();
                                            $obju_Faas3p->docid = $obju_Faas3->docid;
                                            $obju_Faas3p->lineid = getNextIDByBranch("u_rpfaas2p",$objConnection);
                                            $obju_Faas3p->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                            $obju_Faas3p->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                            $obju_Faas3p->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                            $obju_Faas3p->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                            $obju_Faas3p->setudfvalue("u_prevvalue",$ar2["PreviousImprovementValue"]);
                                            if($ar2["PREVEFFYEAR"]!="" && $ar2["PREVEFFQTR"]!="")$obju_Faas3p->setudfvalue("u_preveffdate",$ar2["PREVEFFYEAR"]."-".$ar2["PREVEFFQTR"]."-01");
                                            $obju_Faas3p->setudfvalue("u_prevrecordeddate",$ar2["PREVDATERECORD"]);
                                            $obju_Faas3p->setudfvalue("u_prevrecordedby",$ar2["PREVRECORDPERSON"]);
                                            $actionReturn = $obju_Faas3p->add();

                                            if (!$actionReturn) break;

                                            $obju_Faas3->setudfvalue("u_prevarpno",$ar2["PREVDOCNO"]);
                                            $obju_Faas3->setudfvalue("u_prevtdno",$ar2["PREVTD"]);
                                            $obju_Faas3->setudfvalue("u_prevpin",$ar2["PREVPIN"]);
                                            $obju_Faas3->setudfvalue("u_prevowner",$ar2["PREVOWNER"]);
                                            $obju_Faas3->setudfvalue("u_prevvalue",$ar2["PreviousImprovementValue"]);
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
                                                                    where a.IsCancelled <> 1 and b.PropertyTypeId = 3 group by a.TDNId,b.PropertyTypeId order by a.TDNId") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                        while(odbc_fetch_row($odbcres)) {
                                               //Build tempory
                                               for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                                  $field_name = odbc_field_name($odbcres, $j);
                                                 // $this->temp_fieldnames[$j] = $field_name;
                                                  $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                               }
                                               $actionReturn = true;
                                               if ($ar["PropertyTypeId"]=="3") {
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
                    if(confirm("Migrate Geodata Real Property Records?"))  return true;
                    else  return false;
            }
	}  
        function onFormSubmitted(action) {
		showAjaxProcess();
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
	  <td class="labelPageHeader" >&nbsp;Migrate GEO RPTAS Machinery&nbsp;</td>
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