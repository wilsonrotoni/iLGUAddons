<?php
	$progid = "u_migrategeobpls";

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
            
        $page->objectcode = "U_MIGRATEGEOBPLS";
	$page->paging->formid = "./UDP.php?&objectcode=U_MIGRATEGEOBPLS";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO BPLS";
        
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        if($action=="migratebplgeo"){   
                $objRs = new recordset(null,$objConnection);
                $obju_BplBarangays = new masterdataschema(null,$objConnection,"u_businessbarangays");
                $obju_BplNature = new masterdataschema(null,$objConnection,"u_bplnature");
                $obju_BplLines = new masterdataschema(null,$objConnection,"u_bpllines");
                $obju_BplMds = new masterdataschema_br(null,$objConnection,"u_bplmds");
                $obju_BplApps = new documentschema_br(null,$objConnection,"u_bplapps");
                $obju_BplAppLines = new documentlinesschema_br(null,$objConnection,"u_bplapplines");
                $obju_BplAppFees = new documentlinesschema_br(null,$objConnection,"u_bplappfees");
                $obju_LguStreets = new masterdataschema(null,$objConnection,"u_streets");
                $obju_LGUSubdivisions = new masterdataschema(null,$objConnection,"u_subdivisions");
                
                $obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
                $obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
                $obju_Bills = new documentschema_br(null,$objConnection,"u_lgubills");
                $obju_BillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
                
                $objConnection->beginwork();
                
                $odbccon = @odbc_connect("GeoTOS","sa2","12345678",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database111. Error Code:  ".odbc_error());
//                $odbccon1 = @odbc_connect("GeoTOS","","",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                //if ($actionReturn) $actionReturn = $obju_BplBarangays->executesql("delete from u_businessbarangays",false);
//                if ($actionReturn) $actionReturn = $obju_BplNature->executesql("delete from u_bplnature",false);
//                if ($actionReturn) $actionReturn = $obju_BplLines->executesql("delete from u_bpllines",false);
//                if ($actionReturn) $actionReturn = $obju_BplMds->executesql("delete from u_bplmds",false);
//                if ($actionReturn) $actionReturn = $obju_BplApps->executesql("delete from u_bplapps",false);
//                if ($actionReturn) $actionReturn = $obju_BplAppLines->executesql("delete from u_bplapplines",false);
//                if ($actionReturn) $actionReturn = $obju_BplAppFees->executesql("delete from u_bplappfees",false);
//                if ($actionReturn) $actionReturn = $obju_LguStreets->executesql("delete from u_streets",false);
//                if ($actionReturn) $actionReturn = $obju_LGUSubdivisions->executesql("delete from u_subdivisions",false);
                
                
               //APPREFNO OF BPLMDS  = Businessdetail_Renewal.Year + '-' + Businessdetail_Renewal.BusinessDetail_RenewalID
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT BarangayName FROM DBO.aBarangay") or die("<B>Error!</B> Couldn't Run Query2:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_BplBarangays->prepareadd();
//                                $obju_BplBarangays->code = $ar["BarangayName"];
//                                $obju_BplBarangays->name = $ar["BarangayName"];
//                                $actionReturn = $obju_BplBarangays->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT a.STREETID,a.STREETNAME,a.BarangayId,b.BarangayName FROM GeoRecords4.DBO.aStreet a 
//                                                        LEFT JOIN GeoRecords4.DBO.aBarangay b ON A.BarangayId = B.BarangayId") or die("<B>Error!</B> Couldn't Run Query2:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_LguStreets->prepareadd();
//                                $obju_LguStreets->code = $ar["STREETID"];
//                                $obju_LguStreets->name = $ar["STREETNAME"];
//                                $obju_LguStreets->setudfvalue("u_brgy",$ar["BarangayName"]);
//                                $actionReturn = $obju_LguStreets->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT SubdivisionName FROM DBO.aSubdivision group by SubdivisionName") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_LGUSubdivisions->prepareadd();
//                                $obju_LGUSubdivisions->code = $ar["SubdivisionName"];
//                                $obju_LGUSubdivisions->name = $ar["SubdivisionName"];
//                                $actionReturn = $obju_LGUSubdivisions->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT CONCAT(A.BusinessNatureCode,A.BusinessNatureID) AS NatureCode,A.BusinessNatureDescription FROM BPLS.aBusinessNature A") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_BplNature->prepareadd();
//                                $obju_BplNature->code = trim($ar["NatureCode"]);
//                                $obju_BplNature->name = $ar["BusinessNatureDescription"];
//                                $actionReturn = $obju_BplNature->add();
//                                if (!$actionReturn) break;
//                        }		
//                }
//                if ($actionReturn) {
//                        $odbcres = @odbc_exec($odbccon,"SELECT A.BusinessLineID,A.BusinessLineCode,A.BusinessLineDescription,B.BusinessNatureID,B.BusinessNatureDescription FROM BPLS.ABUSINESSLINE A
//                                                         LEFT JOIN BPLS.aBusinessNature B ON A.BusinessNatureID = B.BusinessNatureID") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                         while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                $obju_BplLines->prepareadd();
//                                $obju_BplLines->code = $ar["BusinessLineID"];
//                                $obju_BplLines->name = $ar["BusinessLineDescription"];
//                                $obju_BplLines->setudfvalue("u_blinecode",$ar["BusinessLineCode"]);
//                                $obju_BplLines->setudfvalue("u_businessnature",$ar["BusinessNatureDescription"]);
//                                $actionReturn = $obju_BplLines->add();
//                                if (!$actionReturn) break;
//                        }		
//                }	
//            
                if ($actionReturn) {
                        $query = "SELECT 
                                        CASE 
                                        WHEN  AAA.DATEOR != '' THEN AAA.DATEOR
                                        WHEN  AA.YEAR != '' THEN CONCAT(AA.YEAR,CASE WHEN SUBSTRING(CONVERT(varchar, A.ApplicationDate,23),5,7) = '-02-29' THEN '-02-28' ELSE SUBSTRING(CONVERT(varchar, A.ApplicationDate,23),5,7) END)
                                        ELSE  CONVERT(varchar, A.ApplicationDate,23)
                                        END AS APPDATE,

                                        CASE 
                                        WHEN AA.YEAR != '' THEN AA.YEAR 
                                        ELSE 
                                        SUBSTRING(CONVERT(varchar, A.ApplicationDate,23),1,4)
                                        END AS APPYEAR,

                                        CASE 
                                        WHEN AA.YEAR != '' THEN CONCAT(AA.YEAR,'-',A.BusinessID)
                                        ELSE 
                                        CONCAT(SUBSTRING(CONVERT(varchar, A.ApplicationDate,23),1,4),'-',A.BusinessID)
                                        END AS DOCNO2,

                                        AAA.DATEOR, 
									    AA.YEAR,
                                        A.BusinessID,
                                        A.ApplicationDate,
                                       
                                        A.AccountNo, 
                                        A.BusinessName,
                                        concat(A.BusinessName,'-',A.AccountNo) as UniqueBusinessName,
                                        A.Retired,
                                        A.DateofClosure,
                                        A.BuildingID,
                                        A.PrefixName,
                                        A.UnitNo,
                                        A.Phone,
                                        A.LandMark,
                                        A.PhaseNo,
                                        A.AccessRoad,
                                        A.AddressNo,
                                        A.BusinessAddress,
                                        A.PIN,
                                        A.LotNo,
                                        A.Area,
                                        A.OnHold,
                                        A.FloorNo,
                                        A.BlockNo,
                                        A.Remarks,
                                        B.FirstName,
                                        B.LastName,
                                        B.MiddleName,
                                        B.FullName,
                                        B.CorporateName,
                                        B.OwnerAddress,
                                        B.EMail AS OwnerEmail,
                                        B.ContactNo AS OwnerContactNo,
                                        B.TIN,
                                        B.OwnerName,
                                        C.SEC_DATE,
                                        C.SEC, 
                                        C.DTI, 
                                        C.DTI_DATE, 
                                        C.LessorsName,
                                        C.LessorsAddress,
                                        C.NameOfPresident,
                                        C.BookkeeperTelNo,
                                        C.MonthlyRentals,
                                        D.BuildingDescription,
                                        E.StreetName,
                                        F.BarangayName,
                                        UPPER(G.BusinessTypeDescription) AS BusinessTypeDescription,
                                        H.SubdivisionName,
                                        CASE 
                                        WHEN  I.OfficeTypeDescription = 'micro' THEN 'Micro'
                                        WHEN  I.OfficeTypeDescription = 'small' THEN 'Small'
                                        WHEN  I.OfficeTypeDescription = 'medium' THEN 'Medium'
                                        WHEN  I.OfficeTypeDescription = 'large' THEN 'Large'
                                        ELSE ''
                                        END AS OfficeTypeDescription

                                        FROM GeoRecords.BPLS.BusinessRecord_HDR A
                                        LEFT JOIN (SELECT MAX(Year) as year,BusinessID FROM GeoRecords.bpls.BusinessDetail where Year >= 0 group by BusinessID) as  AA ON A.BusinessID = AA.BusinessID
                                        LEFT JOIN (SELECT SUBSTRING(CONVERT(varchar, ORDate,23),1,4) as ORYEAR, CONVERT(varchar, MIN(ORDate),23) AS DATEOR,BusinessID FROM GeoTos.BPLS.BusinessCollection_Hdr GROUP BY BusinessID,SUBSTRING(CONVERT(varchar, ORDate,23),1,4)) AAA ON A.BusinessID = AAA.BusinessID AND AA.Year = AAA.ORYEAR 
                                        LEFT JOIN GeoRecords.BPLS.aOwner B ON A.OwnerID = B.OwnerId
                                        LEFT JOIN GeoRecords.BPLS.BusinessRecord_DTL C ON A.BusinessID = C.BusinessID
                                        LEFT JOIN GeoRecords.DBO.aBuilding D ON A.BuildingID = D.BuildingId
                                        LEFT JOIN GeoRecords.DBO.aStreet E ON A.StreetID = E.StreetId
                                        LEFT JOIN GeoRecords.DBO.aBarangay F ON A.BarangayID = F.BarangayId
                                        LEFT JOIN GeoRecords.BPLS.BusinessType G ON A.BusinessTypeID = G.BusinessTypeID
                                        LEFT JOIN GeoRecords.DBO.aSubdivision H ON A.SubdivisionID = H.SubdivisionID
                                        LEFT JOIN GeoRecords.BPLS.OfficeType I ON A.OfficeTypeId = I.OfficeTypeID 
                                        WHERE A.ApplicationDate IS NOT NULL
                                        ORDER BY BusinessID  ";
                        
                        $odbcres = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name; 
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                
                                if ($obju_BplApps->getbykey($ar["DOCNO2"])) {
                                     if ($actionReturn) {
                                        $totalassessment = 0;
                                        $totalbusinesstax = 0;
                                        $query = "Select Year,BusinessID,BusinessLineID,a.FeeID,B.FeeDescription,max(TaxBase) TaxBase,sum(AmountDue) AmountDue,sum(Interest) Interest,sum(Surcharge) Surcharge,min(RowNumber) RowNumber,PaymentMode from (
                                                    Select Year,BusinessID,BusinessLineID,FeeID,TaxBase,AmountDue,Interest,Surcharge,RowNumber,PaymentMode from GeoTOS.BPLS.[BusinessLedger]  WHERE businessid = '".$ar["BusinessID"]."' AND year = '".$ar["YEAR"]."' and (Cancelled <> 1 or Cancelled is null)
                                                    union all 
                                                    select Year,a.BusinessID,BusinessLineID,FeeID,TaxBase,Amount,Interest,Surcharge,RowNumber,PaymentMode from GeoRecords.bpls.BusinessAssessment a 
                                                        inner join (SELECT a.BusinessID,a.BusinessName,B.ORNumber FROM GeoRecords.BPLS.BusinessRecord_HDR A
							left JOIN GeoTOS.BPLS.BusinessLedger B on a.BusinessID = b.BusinessID and b.businessid = '".$ar["BusinessID"]."' AND year = '".$ar["YEAR"]."'
							where b.ORNumber is null
							group by a.BusinessID,B.ORNumber,a.BusinessName) b on a.BusinessID = b.BusinessID  WHERE a.businessid = '".$ar["BusinessID"]."' AND year = '".$ar["YEAR"]."'  ) as a
                                                    INNER JOIN GeoRecords.BPLS.aBusinessFee B ON A.FeeID = B.FeeID
                                                    Group By A.FEEID,B.FeeDescription,A.BusinessID,YEAR ,BusinessLineID,A.PaymentMode 
                                                    Order By A.BusinessID,Year,RowNumber";
                                        $odbcres2 = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                         while(odbc_fetch_row($odbcres2)) {
                                                //Build tempory
                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                   $field_name = odbc_field_name($odbcres2, $j);
                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                }
                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_year",$ar2["Year"]);
                                                $obju_BplAppFees->setudfvalue("u_feecode",$ar2["FeeID"]);
                                                $obju_BplAppFees->setudfvalue("u_feedesc",$ar2["FeeDescription"]);
                                                $obju_BplAppFees->setudfvalue("u_amount",$ar2["AmountDue"]);
                                                $obju_BplAppFees->setudfvalue("u_surcharge",$ar2["Surcharge"]);
                                                $obju_BplAppFees->setudfvalue("u_interest",$ar2["Interest"]);
                                                $obju_BplAppFees->setudfvalue("u_taxbase",$ar2["TaxBase"]);
                                                $obju_BplAppFees->setudfvalue("u_businessline",$ar2["BusinessLineID"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",$ar2["RowNumber"]);
                                                if ($ar2["FeeID"] == 5 || $ar2["FeeID"] == 1) {
                                                    $totalbusinesstax +=  $ar2["AmountDue"] ;
                                                }
                                                if ($ar2["FeeID"] == 2) {
                                                }
                                                
                                                $actionReturn = $obju_BplAppFees->add();
                                                $totalassessment +=  $ar2["AmountDue"] ; 
                                                if (!$actionReturn) break;
                                                
//                                                if($actionReturn){
//                                                    $obju_BplApps->setudfvalue("u_asstotal",$totalassessment);
//                                                    $obju_BplApps->setudfvalue("u_btaxamount",$totalbusinesstax);
//                                                    $actionReturn = $obju_BplApps->update($obju_BplApps->docno,$obju_BplApps->rcdversion);;
//                                                }
                                        }		
                                    }
                                    
                                    if ($actionReturn) {
                                        $objConnection->commit();
//                                        var_dump($num_rows);
                                    } else {
                                        $myfile = fopen("../Addons/GPS/BPLS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                                        $txt = $_SESSION["errormessage"]."\n";
                                        fwrite($myfile, $txt);
                                        fclose($myfile);
                                        $objConnection->rollback();
                                        echo $_SESSION["errormessage"];
                                    }  
                                }
                                
                                
                                if (!$obju_BplMds->getbykey($ar["AccountNo"])) {
                                    
                                $obju_BplApps->prepareadd();
                                $obju_BplApps->docid = getNextIDByBranch("u_bplapps",$objConnection);
                                $obju_BplApps->docseries = -1;
                                $obju_BplApps->docno = $ar["DOCNO2"];
                                $obju_BplApps->docstatus = "Approved";
                                $obju_BplApps->setudfvalue("u_year",$ar["APPYEAR"]);
                                $obju_BplApps->setudfvalue("u_appdate",$ar["APPDATE"]);
                                $obju_BplApps->setudfvalue("u_appno",$ar["AccountNo"]);
                                $obju_BplApps->setudfvalue("u_retired",$ar["Retired"]);
                                $obju_BplApps->setudfvalue("u_onhold",$ar["OnHold"]);
                                $obju_BplApps->setudfvalue("u_retireddate",$ar["DateofClosure"]);
                                $obju_BplApps->setudfvalue("u_lastname",$ar["LastName"]);
                                $obju_BplApps->setudfvalue("u_firstname",$ar["FirstName"]);
                                $obju_BplApps->setudfvalue("u_middlename",$ar["MiddleName"]);
                                $obju_BplApps->setudfvalue("u_email",$ar["OwnerEmail"]);
                                $obju_BplApps->setudfvalue("u_telno",$ar["OwnerContactNo"]);
                                $obju_BplApps->setudfvalue("u_owneraddress",$ar["OwnerAddress"]);
                                $obju_BplApps->setudfvalue("u_tin",$ar["TIN"]);
                                $obju_BplApps->setudfvalue("u_businessname",$ar["UniqueBusinessName"]);
                                $obju_BplApps->setudfvalue("u_tradename",$ar["BusinessName"]);
                                
                                $obju_BplApps->setudfvalue("u_bbrgy",$ar["BarangayName"]);
                                $obju_BplApps->setudfvalue("u_bvillage",$ar["SubdivisionName"]);
                                $obju_BplApps->setudfvalue("u_bbldgno",$ar["PrefixName"]);
                                $obju_BplApps->setudfvalue("u_bbldgname",$ar["BuildingDescription"]);
                                $obju_BplApps->setudfvalue("u_bblock",$ar["BlockNo"]);
                                $obju_BplApps->setudfvalue("u_bunitno",$ar["UnitNo"]);
                                $obju_BplApps->setudfvalue("u_blotno",$ar["LotNo"]);
                                $obju_BplApps->setudfvalue("u_bfloorno",$ar["FloorNo"]);
                                $obju_BplApps->setudfvalue("u_bstreet",$ar["StreetName"]);
                                $obju_BplApps->setudfvalue("u_bcity","BACOOR");
                                $obju_BplApps->setudfvalue("u_bprovince","CAVITE");
                                $obju_BplApps->setudfvalue("u_bphaseno",$ar["PhaseNo"]);
                                $obju_BplApps->setudfvalue("u_btelno",$ar["Phone"]);
                                $obju_BplApps->setudfvalue("u_blandmark",$ar["LandMark"]);
                                
                                $obju_BplApps->setudfvalue("u_corpname",$ar["CorporateName"]);
                                $obju_BplApps->setudfvalue("u_regno",$ar["DTI"]);
                                $obju_BplApps->setudfvalue("u_regdate",$ar["DTI_DATE"]);
                                $obju_BplApps->setudfvalue("u_secregno",$ar["SEC"]);
                                $obju_BplApps->setudfvalue("u_secregdate",$ar["SEC_DATE"]);
                                
                                if($ar["BusinessTypeDescription"] == "SINGLE PROPRIETORSHIP") $obju_BplApps->setudfvalue("u_orgtype","SINGLE");
                                else $obju_BplApps->setudfvalue("u_orgtype",$ar["BusinessTypeDescription"]);
                               
                                $obju_BplApps->setudfvalue("u_llastname",$ar["LessorsName"]);
                                $obju_BplApps->setudfvalue("u_lessoraddress",$ar["LessorsAddress"]);
                                $obju_BplApps->setudfvalue("u_ltelno",$ar["BookkeeperTelNo"]);
                                $obju_BplApps->setudfvalue("u_businessarea",$ar["Area"]);
                                $obju_BplApps->setudfvalue("u_tlastname",$ar["NameOfPresident"]);
                                $obju_BplApps->setudfvalue("u_monthlyrental",$ar["MonthlyRentals"]);
                                
                                $obju_BplApps->setudfvalue("u_baccessroad",$ar["AccessRoad"]);
                                $obju_BplApps->setudfvalue("u_baddressno",$ar["AddressNo"]);
                                $obju_BplApps->setudfvalue("u_baddress",$ar["BusinessAddress"]);
                                $obju_BplApps->setudfvalue("u_pin",$ar["PIN"]);
                                $obju_BplApps->setudfvalue("u_businesscategory",$ar["OfficeTypeDescription"]);
                                $obju_BplApps->setudfvalue("u_remarks",$ar["Remarks"]);
                                
                                if (!$actionReturn) break;
                                
                                if ($actionReturn) {
                                    $query = "SELECT DISTINCT YEAR,TYPE,BusinessID,B.BusinessLineID,B.BusinessLineDescription,AMOUNT,EffectivityType FROM(
                                                                     SELECT  DISTINCT YEAR,'NEW' AS TYPE,BusinessID,BusinessLineID,CAPITAL AS AMOUNT,EffectivityType FROM Georecords.BPLS.BusinessDetail_New A
                                                                     UNION ALL 
                                                                     SELECT  DISTINCT YEAR,'RENEW' AS TYPE,BusinessID,BusinessLineID,GROSS AS AMOUNT,EffectivityType FROM Georecords.BPLS.BusinessDetail_Renewal A ) AS AA
                                                                     INNER JOIN Georecords.BPLS.aBusinessLine B ON AA.BusinessLineID = B.BusinessLineID 
                                                                     WHERE AA.BUSINESSID = '".$ar["BusinessID"]."' AND AA.YEAR = '".$ar["YEAR"]."' ORDER BY BusinessID,YEAR,TYPE DESC";
                                    $odbcres1 = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                         while(odbc_fetch_row($odbcres1)) {
                                                //Build tempory
                                                for ($j = 1; $j <= odbc_num_fields($odbcres1); $j++) {
                                                   $field_name = odbc_field_name($odbcres1, $j);
                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                   $ar1[$field_name] = odbc_result($odbcres1, $field_name) . "";
                                                }
                                                $obju_BplAppLines->prepareadd(); 
                                                $obju_BplAppLines->docid = $obju_BplApps->docid;
                                                $obju_BplAppLines->lineid = getNextIDByBranch("u_bplapplines",$objConnection);
                                                $obju_BplAppLines->setudfvalue("u_year",$ar1["YEAR"]);
                                                $obju_BplAppLines->setudfvalue("u_businessline",$ar1["BusinessLineID"]);
                                                
                                                $obju_BplApps->setudfvalue("u_apptype",$ar1["TYPE"]);
                                                if($ar1["TYPE"] == "NEW"){
                                                    $obju_BplAppLines->setudfvalue("u_capital",$ar1["AMOUNT"]);
                                                }else{
                                                    $obju_BplAppLines->setudfvalue("u_nonessential",$ar1["AMOUNT"]);
                                                }
                                                $actionReturn = $obju_BplAppLines->add();
                                                if (!$actionReturn) break;
                                        }		
                                }
                                
                                if ($actionReturn) {
                                        $totalassessment = 0;
                                        $totalbusinesstax = 0;
                                        $query = "Select Year,BusinessID,BusinessLineID,a.FeeID,B.FeeDescription,max(TaxBase) TaxBase,sum(AmountDue) AmountDue,sum(Interest) Interest,sum(Surcharge) Surcharge,min(RowNumber) RowNumber,PaymentMode from (
                                                    Select Year,BusinessID,BusinessLineID,FeeID,TaxBase,AmountDue,Interest,Surcharge,RowNumber,PaymentMode from GeoTOS.BPLS.[BusinessLedger]  WHERE businessid = '".$ar["BusinessID"]."' AND year = '".$ar["YEAR"]."' and (Cancelled <> 1 or Cancelled is null)
                                                    union all 
                                                    select Year,a.BusinessID,BusinessLineID,FeeID,TaxBase,Amount,Interest,Surcharge,RowNumber,PaymentMode from GeoRecords.bpls.BusinessAssessment a 
                                                        inner join (SELECT a.BusinessID,a.BusinessName,B.ORNumber FROM GeoRecords.BPLS.BusinessRecord_HDR A
							left JOIN GeoTOS.BPLS.BusinessLedger B on a.BusinessID = b.BusinessID
							where b.ORNumber is null
							group by a.BusinessID,B.ORNumber,a.BusinessName) b on a.BusinessID = b.BusinessID  WHERE a.businessid = '".$ar["BusinessID"]."' AND year = '".$ar["YEAR"]."'  ) as a
                                                    INNER JOIN GeoRecords.BPLS.aBusinessFee B ON A.FeeID = B.FeeID
                                                    Group By A.FEEID,B.FeeDescription,A.BusinessID,YEAR ,BusinessLineID,A.PaymentMode 
                                                    Order By A.BusinessID,Year,RowNumber";
                                        $odbcres2 = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                                         while(odbc_fetch_row($odbcres2)) {
                                                //Build tempory
                                                for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
                                                   $field_name = odbc_field_name($odbcres2, $j);
                                                  // $this->temp_fieldnames[$j] = $field_name;
                                                   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
                                                }
                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_year",$ar2["Year"]);
                                                $obju_BplAppFees->setudfvalue("u_feecode",$ar2["FeeID"]);
                                                $obju_BplAppFees->setudfvalue("u_feedesc",$ar2["FeeDescription"]);
                                                $obju_BplAppFees->setudfvalue("u_amount",$ar2["AmountDue"]);
                                                $obju_BplAppFees->setudfvalue("u_surcharge",$ar2["Surcharge"]);
                                                $obju_BplAppFees->setudfvalue("u_interest",$ar2["Interest"]);
                                                $obju_BplAppFees->setudfvalue("u_taxbase",$ar2["TaxBase"]);
                                                $obju_BplAppFees->setudfvalue("u_businessline",$ar2["BusinessLineID"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",$ar2["RowNumber"]);
                                                if ($ar2["FeeID"] == 5 || $ar2["FeeID"] == 1) {
                                                    $totalbusinesstax +=  $ar2["AmountDue"] ;
                                                    $obju_BplApps->setudfvalue("u_paymode",$ar2["PaymentMode"]);
                                                }
                                                if ($ar2["FeeID"] == 2) {
                                                    $obju_BplApps->setudfvalue("u_envpaymode",$ar2["PaymentMode"]);
                                                }
                                                
                                                $actionReturn = $obju_BplAppFees->add();
                                                $totalassessment +=  $ar2["AmountDue"] ; 
                                                if (!$actionReturn) break;
                                        }		
                                }
                                
                                if($actionReturn){
                                    $obju_BplApps->setudfvalue("u_asstotal",$totalassessment);
                                    $obju_BplApps->setudfvalue("u_btaxamount",$totalbusinesstax);
                                    $actionReturn = $obju_BplApps->add();
                                }
                                
                                    if ($actionReturn) {
                                        $objConnection->commit();
//                                        var_dump($num_rows);
                                    } else {
                                        $myfile = fopen("../Addons/GPS/BPLS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                                        $txt = $_SESSION["errormessage"]."\n";
                                        fwrite($myfile, $txt);
                                        fclose($myfile);
                                        $objConnection->rollback();
                                        echo $_SESSION["errormessage"];
                                   }  
                                }
                        }
                        
                }
                
               //UPDATE REGISTRATION DATE BY CODE
//                if ($actionReturn) {
//                        $query = "SELECT AccountNo,ApplicationDate FROM GeoRecords.BPLS.BusinessRecord_HDR";
//                            $odbcres = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
//                        while(odbc_fetch_row($odbcres)) {
//                                //Build tempory
//                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
//                                   $field_name = odbc_field_name($odbcres, $j);
//                                  // $this->temp_fieldnames[$j] = $field_name;
//                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
//                                }
//                                if ($obju_BplMds->getbykey($ar["AccountNo"])) {
//                                        $obju_BplMds->setudfvalue("u_regdate",$ar["ApplicationDate"]);
//                                        $actionReturn = $obju_BplMds->update($obju_BplMds->code,$obju_BplMds->rcdversion);
//                                } else $actionReturn = raiseError("Unable to find Account No. [".$ar["AccountNo"]."]");
//                        }
//                        if ($actionReturn) {
//                            $objConnection->commit();
//                            var_dump($num_rows);
//                        } else {
//                            $myfile = fopen("../Addons/GPS/BPLS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
//                            $txt = $_SESSION["errormessage"]."\n";
//                            fwrite($myfile, $txt);
//                            fclose($myfile);
//                            $objConnection->rollback();
//                            echo $_SESSION["errormessage"];
//                       }
//                        
//                }
                    
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
           if(action=="migratebplgeo"){
                    if(confirm("Migrate Geodata Business Permit Records?")){
                        showAjaxProcess();
                        return true;
                    }else{
                        return false;
                    } 
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
	  <td class="labelPageHeader" >&nbsp;Migrate GEO BPLS&nbsp;</td>
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
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migratebplgeo');return false;">Migrate Data</a></td>
                <!--<td  >&nbsp;<a class="button" href="" onClick="formSearchNow('migratebplgeo');return false;">Wilson</a></td>-->
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