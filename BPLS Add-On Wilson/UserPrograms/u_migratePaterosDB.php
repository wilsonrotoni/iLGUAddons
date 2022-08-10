<?php
	$progid = "u_migratePaterosDB";

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
            
        $page->objectcode = "u_migratePaterosDB";
	$page->paging->formid = "./UDP.php?&objectcode=u_migratePaterosDB";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO BPLS";
        
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        if($action=="migratebplgeo"){   
                $objRs = new recordset(null,$objConnection);
                
                $obju_BplApps = new documentschema_br(null,$objConnection,"u_bplapps");
                $obju_BplAppLines = new documentlinesschema_br(null,$objConnection,"u_bplapplines");
                $obju_BplAppFees = new documentlinesschema_br(null,$objConnection,"u_bplappfees");
                $obju_BplLedger = new documentschema_br(null,$objConnection,"u_bplledger");
                
                $objConnection->beginwork();
              
                if ($actionReturn) {
                        $objRs->queryopen("select *,trim(bizperlic_trd_name) as tradename,concat(year(bizperlic_regdate),'-',bizperlic_id) as docno,year(bizperlic_regdate) as u_year  from paterosgovdb.bizpermit_licensing order by bizperlic_id");
                        if (!$objRs->queryfetchrow("NAME")) { 
                            return raiseError("Error retrieving Business Permit Data. Try Again, if problem persists, check the connection.");
                        } else {
                            while ($objRs->queryfetchrow("NAME")) {
                                    $obju_BplApps->prepareadd();
                                    $obju_BplApps->docid = getNextIDByBranch("u_bplapps",$objConnection);
                                    $obju_BplApps->docseries = -1;
                                    $obju_BplApps->docno = $objRs->fields["docno"];
                                    $obju_BplApps->docstatus = "Approved";
                                    $obju_BplApps->setudfvalue("u_apptype",$objRs->fields["bizperlic_type"]);
                                    $obju_BplApps->setudfvalue("u_year",$objRs->fields["u_year"]);
                                    $obju_BplApps->setudfvalue("u_appdate",$objRs->fields["bizperlic_regdate"]);
                                    $obju_BplApps->setudfvalue("u_decisiondate",$objRs->fields["date_assess"]);
//                                    $obju_BplApps->setudfvalue("u_appno",$objRs->fields["code"]);
//                                    $obju_BplApps->setudfvalue("u_retired",$objRs->fields["code"]);
//                                    $obju_BplApps->setudfvalue("u_onhold",$ar["OnHold"]);
//                                    $obju_BplApps->setudfvalue("u_retireddate",$ar["DateofClosure"]);
                                    $obju_BplApps->setudfvalue("u_lastname",$objRs->fields["bizperlic_txpayer"]);
//                                    $obju_BplApps->setudfvalue("u_firstname",$ar["FirstName"]);
//                                    $obju_BplApps->setudfvalue("u_middlename",$ar["MiddleName"]);
//                                    $obju_BplApps->setudfvalue("u_email",$ar["OwnerEmail"]);
                                    $obju_BplApps->setudfvalue("u_telno",$objRs->fields["bizperlic_txp_tel"]);
                                    $obju_BplApps->setudfvalue("u_owneraddress",$objRs->fields["bizperlic_txp_add"]);
                                    $obju_BplApps->setudfvalue("u_tin",$objRs->fields["bizperlic_tin"]);
//                                    $obju_BplApps->setudfvalue("u_businessname",$ar["UniqueBusinessName"]);
                                    $obju_BplApps->setudfvalue("u_tradename",$objRs->fields["tradename"]);

                                    $obju_BplApps->setudfvalue("u_baddress",$objRs->fields["bizperlic_trd_add"]);
//                                    $obju_BplApps->setudfvalue("u_bvillage",$ar["SubdivisionName"]);
//                                    $obju_BplApps->setudfvalue("u_bbldgno",$ar["PrefixName"]);
//                                    $obju_BplApps->setudfvalue("u_bbldgname",$ar["BuildingDescription"]);
//                                    $obju_BplApps->setudfvalue("u_bblock",$ar["BlockNo"]);
//                                    $obju_BplApps->setudfvalue("u_bunitno",$ar["UnitNo"]);
//                                    $obju_BplApps->setudfvalue("u_blotno",$ar["LotNo"]);
//                                    $obju_BplApps->setudfvalue("u_bfloorno",$ar["FloorNo"]);
//                                    $obju_BplApps->setudfvalue("u_bstreet",$ar["StreetName"]);
                                    $obju_BplApps->setudfvalue("u_bcity","PATEROS");
                                    $obju_BplApps->setudfvalue("u_bprovince","METRO MANILA");
//                                    $obju_BplApps->setudfvalue("u_bphaseno",$ar["PhaseNo"]);
                                    $obju_BplApps->setudfvalue("u_btelno",$objRs->fields["bizperlic_trd_tel"]);
//                                    $obju_BplApps->setudfvalue("u_blandmark",$ar["LandMark"]);

//                                    $obju_BplApps->setudfvalue("u_corpname",$ar["CorporateName"]);
                                    $obju_BplApps->setudfvalue("u_regno",$objRs->fields["bizperlic_secdti"]);
                                    $obju_BplApps->setudfvalue("u_regdate",$objRs->fields["bizperlic_secdti_date"]);
                                    $obju_BplApps->setudfvalue("u_secregno",$objRs->fields["bizperlic_sss"]);
                                    $obju_BplApps->setudfvalue("u_empcount",$objRs->fields["bizperlic_empvol"]);

                                    if($objRs->fields["bizperlic_ownership"] == "SINGLE PROPRIETORSHIP") $obju_BplApps->setudfvalue("u_orgtype","SINGLE");
                                    else $obju_BplApps->setudfvalue("u_orgtype",$ar["BusinessTypeDescription"]);

                                    $obju_BplApps->setudfvalue("u_llastname",$objRs->fields["bizperlic_ownername1"]);
                                    $obju_BplApps->setudfvalue("u_tlastname",$objRs->fields["bizperlic_ownername2"]);
                                    $obju_BplApps->setudfvalue("u_lessoraddress",$objRs->fields["bizperlic_own1add"]);
//                                    $obju_BplApps->setudfvalue("u_ltelno",$ar["BookkeeperTelNo"]);
                                    $obju_BplApps->setudfvalue("u_businessarea",$objRs->fields["bizperlic_SQMtxt"]);
                                    $obju_BplApps->setudfvalue("u_monthlyrental",$objRs->fields["bizperlic_rentpermo1"]);

                                    $obju_BplApps->setudfvalue("u_capital",$objRs->fields["bizperlic_capital"]);
                                    $obju_BplApps->setudfvalue("u_nonessential",$objRs->fields["bizperlic_gross"]);
                                    $obju_BplApps->setudfvalue("u_paymode",$objRs->fields["bizassess_type"]);
                                    $obju_BplApps->setudfvalue("u_asstotal",$objRs->fields["bizassess_totalamt"]);
                                    
//                                    $obju_BplApps->setudfvalue("u_pin",$ar["PIN"]);
//                                    $obju_BplApps->setudfvalue("u_businesscategory",$ar["OfficeTypeDescription"]);
//                                    $obju_BplApps->setudfvalue("u_remarks",$ar["Remarks"]);
                                    $actionReturn = $obju_BplApps->add();
                                    
                                    if ($objRs->fields["bizperlic_nature1"] != '') {
                                            $obju_BplAppLines->prepareadd(); 
                                            $obju_BplAppLines->docid = $obju_BplApps->docid;
                                            $obju_BplAppLines->lineid = getNextIDByBranch("u_bplapplines",$objConnection);
                                            $obju_BplAppLines->setudfvalue("u_businessline",$objRs->fields["bizperlic_nature1"]);
                                            
                                            if($objRs->fields["bizperlic_type"] == "Renew"){
                                                $obju_BplAppLines->setudfvalue("u_nonessential",$objRs->fields["bizperlic_gross"]);
                                                $obju_BplAppLines->setudfvalue("u_lastyrgrsales",$objRs->fields["bizperlic_capital"]);
                                            }else{
                                                $obju_BplAppLines->setudfvalue("u_capital",$objRs->fields["bizperlic_capital"]);
                                            }
                                            $actionReturn = $obju_BplAppLines->add();
                                            if (!$actionReturn) break;
                                    }
                                    if ($objRs->fields["bizperlic_nature2"] != '') {
                                            $obju_BplAppLines->prepareadd(); 
                                            $obju_BplAppLines->docid = $obju_BplApps->docid;
                                            $obju_BplAppLines->lineid = getNextIDByBranch("u_bplapplines",$objConnection);
                                            $obju_BplAppLines->setudfvalue("u_businessline",$objRs->fields["bizperlic_nature2"]);
                                            $actionReturn = $obju_BplAppLines->add();
                                            if (!$actionReturn) break;
                                    }
                                    

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Business Tax");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_tax"]);
                                                $obju_BplAppFees->setudfvalue("u_surcharge",$objRs->fields["bizassess_penalty"]);
                                                $obju_BplAppFees->setudfvalue("u_interest",$objRs->fields["bizassess_interest"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",1);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Mayors Permit");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_mpermit"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",2);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Sanitary Permit");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_sanitary"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",3);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Health Certificate");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_health"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",4);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Garbage Fee");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_garbages"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",5);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Annual Inspection Fee");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_annualins"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",6);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Electrical & Plumbing Fee");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_electric"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",7);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Documentation Fee");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_docu"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",8);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Business Inspection Fee");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_inspec"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",9);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Business Plate/Sticker Fee");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_plate"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",10);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","OThers - Cigarettes");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_cigar"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",11);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","OThers - Beer");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_beer"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",12);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","OThers - Liqour");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_liqour"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",13);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;
                                    
                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","OThers - Rice");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_rice"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",14);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;

                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Tax on Delivery Trucks/Van");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_trackvan"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",15);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;
                                                
                                                $obju_BplAppFees->prepareadd();
                                                $obju_BplAppFees->docid = $obju_BplApps->docid;
                                                $obju_BplAppFees->lineid = getNextIDByBranch("u_bplappfees",$objConnection);
                                                $obju_BplAppFees->setudfvalue("u_feecode","");
                                                $obju_BplAppFees->setudfvalue("u_feedesc","Tax on LPG & Other Substance");
                                                $obju_BplAppFees->setudfvalue("u_amount",$objRs->fields["bizassess_lpg"]);
                                                $obju_BplAppFees->setudfvalue("u_seqno",16);
                                                $actionReturn = $obju_BplAppFees->add();
                                                if (!$actionReturn) break;
                                    
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
//               
//                if ($actionReturn) {
//                    $objConnection->commit();
//                    var_dump($num_rows);
//                } else {
//                    $myfile = fopen("../Addons/GPS/BPLS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
//                    $txt = $_SESSION["errormessage"]."\n";
//                    fwrite($myfile, $txt);
//                    fclose($myfile);
//                    $objConnection->rollback();
//                    echo $_SESSION["errormessage"];
//               }  
// 

              
                    
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
                    if(confirm("Migrate Pateros Business Permit Records?")){
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
	  <td class="labelPageHeader" >&nbsp;Migrate Pateros BPLS&nbsp;</td>
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