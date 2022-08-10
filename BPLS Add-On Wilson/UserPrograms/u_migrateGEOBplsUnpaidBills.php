<?php
	$progid = "u_migrateGEOBplsUnpaidBills";

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
            
        $page->objectcode = "u_migrateGEOBplsUnpaidBills";
	$page->paging->formid = "./UDP.php?&objectcode=u_migrateGEOBplsUnpaidBills";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO BPLS";
        
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $dueday = 0;
        if($action=="migratebplgeo"){   
                $objRs = new recordset(null,$objConnection);
                
                $obju_Bills = new documentschema_br(null,$objConnection,"u_lgubills");
                $obju_BillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
                
                $objRs_uLGUSetup = new recordset(null,$objConnection);
                $objRs_uLGUSetup->queryopen("select A.U_MUNICIPALITY,A.U_PROVINCE,A.U_BPLDUEDAY from U_LGUSETUP A");
                if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
                        $dueday = $objRs_uLGUSetup->fields["U_BPLDUEDAY"];
                }

                $objConnection->beginwork();
                
                $odbccon = @odbc_connect("GeoTOS","sa2","12345678",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database111. Error Code:  ".odbc_error());
                 
 
                if ($actionReturn) {
                        $totalassessment = 0;
                        $query = "SELECT 'Q' as Paymode,a.BusinessName,concat(x.year,'-',x.BusinessID) as Appno,a.AccountNo,x.BusinessID,x.PaidQtr,x.Year 
                                    FROM (
                                                    SELECT max(year) as Year,BusinessID,LastPayment,
                                                    CASE 
                                                    WHEN substring(CAST(LastPayment as varchar),1,6) = 'Mar 31' THEN 1
                                                    WHEN substring(CAST(LastPayment as varchar),1,6) = 'Jun 30' THEN 2
                                                    WHEN substring(CAST(LastPayment as varchar),1,6) = 'Sep 30' THEN 3
                                                    ELSE ''
                                                    END AS PaidQtr
                                                    FROM GeoRecords.BPLS.view_businessDetail A 
                                                    where substring(CAST(LastPayment as varchar),1,6) != 'Dec 31' 
                                                    group by BusinessID,LastPayment
                                                    ) AS X 
                                     INNER JOIN GeoRecords.bpls.BusinessRecord_HDR a on a.BusinessID = x.BusinessID AND Retired =0
                                     order by BusinessID";
//                        
                        $odbcres = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar2[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                $paidqtr =  $ar2["PaidQtr"] + 1;
                                for ($ctr = $paidqtr; $ctr <= 4; $ctr++) {
                                        $obju_Bills->prepareadd();
                                        $obju_Bills->docid = getNextIDByBranch("u_lgubills",$objConnection);
                                        $obju_Bills->docno = getNextNoByBranch("u_lgubills","",$objConnection);;
                                        $obju_Bills->docstatus = "O";
                                        $obju_Bills->setudfvalue("u_appno",$ar2["Appno"]);
                                        $obju_Bills->setudfvalue("u_custno",$ar2["AccountNo"]);
                                        $obju_Bills->setudfvalue("u_custname",$ar2["BusinessName"]);
                                        $obju_Bills->setudfvalue("u_paymode",$ar2["Paymode"]);
                                        $obju_Bills->setudfvalue("u_profitcenter","BPL");
                                        $obju_Bills->setudfvalue("u_module","Business Permit");
                                        $obju_Bills->setudfvalue("u_migrated",1);
                                        $obju_Bills->setudfvalue("u_migrateddate",currentdateDB());
                                        
                                        switch ($ctr) {
                                                        
                                                        case 2:
                                                                $obju_Bills->setudfvalue("u_docdate",$ar2["Year"]."-04-01");
                                                                $startdate = getmonthstartDB($ar2["Year"]."-04-01");
                                                                $obju_Bills->setudfvalue("u_duedate",dateadd("d",$dueday-1,$startdate));
                                                                $obju_Bills->setudfvalue("u_remarks","Business Permit & Licensing - Q2, ".$ar2["Year"] );
                                                                $obju_Bills->setudfvalue("u_payqtr",2);
                                                                break;
                                                        case 3:
                                                                $obju_Bills->setudfvalue("u_docdate",$ar2["Year"]."-07-01");
                                                                $startdate = getmonthstartDB($ar2["Year"]."-07-01");
                                                                $obju_Bills->setudfvalue("u_duedate",dateadd("d",$dueday-1,$startdate));
                                                                $obju_Bills->setudfvalue("u_payqtr",3);
                                                                $obju_Bills->setudfvalue("u_remarks","Business Permit & Licensing - Q3, ".$ar2["Year"] );
                                                                break;
                                                        case 4:
                                                                $obju_Bills->setudfvalue("u_docdate",$ar2["Year"]."-10-01");
                                                                $startdate = getmonthstartDB($ar2["Year"]."-10-01");
                                                                $obju_Bills->setudfvalue("u_duedate",dateadd("d",$dueday-1,$startdate));
                                                                $obju_Bills->setudfvalue("u_payqtr",4);
                                                                $obju_Bills->setudfvalue("u_remarks","Business Permit & Licensing - Q4, ".$ar2["Year"] );
                                                                break;
                                                }
                                        
                                        $query2 = " SELECT '0001' as itemcode, 'Business Tax' as itemdesc, Amount as AmountDue,TaxBase,BusinessLineID  FROM GeoRecords.BPLS.BusinessAssessment WHERE BusinessID = '".$ar2["BusinessID"]."' AND Quarter = '".$ctr."' and FeeID in (5)";
                                        $odbcres3 = @odbc_exec($odbccon,$query2) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query2 . "<br><br>Error Code:  ".odbc_error());
                                        $totalamount=0;
                                        while(odbc_fetch_row($odbcres3)) {
                                                    //Build tempory
                                                    for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                       $field_name = odbc_field_name($odbcres3, $j);
                                                      // $this->temp_fieldnames[$j] = $field_name;
                                                       $ar[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                    }
                                                    
                                                    $obju_BillItems->prepareadd();
                                                    $obju_BillItems->docid = $obju_Bills->docid;
                                                    $obju_BillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                                    $obju_BillItems->setudfvalue("u_itemcode",$ar["itemcode"]);
                                                    $obju_BillItems->setudfvalue("u_itemdesc",$ar["itemdesc"]);
                                                    $obju_BillItems->setudfvalue("u_amount",$ar["AmountDue"]);
                                                    $obju_BillItems->setudfvalue("u_taxbase",$ar["TaxBase"]);
                                                    $obju_BillItems->setudfvalue("u_seqno",10);
                                                    $obju_BillItems->setudfvalue("u_businessline",$ar["BusinessLineID"]);
                                                    $totalamount+=$ar["AmountDue"];

                                                    $obju_BillItems->privatedata["header"] = $obju_Bills;
                                                    $actionReturn = $obju_BillItems->add();
                                                    if (!$actionReturn) break;
                                         }
                                      
                                        if ($actionReturn && $totalamount > 0) {
                                            $obju_Bills->setudfvalue("u_totalamount",$totalamount);
                                            $obju_Bills->setudfvalue("u_dueamount",$totalamount);
                                            $actionReturn = $obju_Bills->add();
                                        }
                                        
                                        if (!$actionReturn) break;
                                }
                                
                                
                                if ($actionReturn) {
                                        $objConnection->commit();
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
                
                
                
                // For Environmental Fee    
                if ($actionReturn) {
                        $totalassessment = 0;
                        $query = "SELECT 'Q' as Paymode,a.BusinessName,concat(x.year,'-',x.BusinessID) as Appno,a.AccountNo,x.BusinessID,x.PaidQtr,x.Year 
                                    FROM (
                                                    SELECT max(year) as Year,BusinessID,LastPayment,
                                                    CASE 
                                                    WHEN substring(CAST(LastPayment as varchar),1,6) = 'Mar 31' THEN 1
                                                    WHEN substring(CAST(LastPayment as varchar),1,6) = 'Jun 30' THEN 2
                                                    WHEN substring(CAST(LastPayment as varchar),1,6) = 'Sep 30' THEN 3
                                                    ELSE ''
                                                    END AS PaidQtr
                                                    FROM GeoRecords.BPLS.view_businessDetail A 
                                                    where substring(CAST(LastPayment as varchar),1,6) != 'Dec 31' 
                                                    group by BusinessID,LastPayment
                                                    ) AS X 
                                     INNER JOIN GeoRecords.bpls.BusinessRecord_HDR a on a.BusinessID = x.BusinessID AND Retired = 0
                                     order by BusinessID";
//                        
                        $odbcres = @odbc_exec($odbccon,$query) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar2[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                $paidqtr =  $ar2["PaidQtr"] + 1;
                                for ($ctr = $paidqtr; $ctr <= 4; $ctr++) {
                                        $obju_Bills->prepareadd();
                                        $obju_Bills->docid = getNextIDByBranch("u_lgubills",$objConnection);
                                        $obju_Bills->docno = getNextNoByBranch("u_lgubills","",$objConnection);;
                                        $obju_Bills->docstatus = "O";
                                        $obju_Bills->setudfvalue("u_appno",$ar2["Appno"]);
                                        $obju_Bills->setudfvalue("u_custno",$ar2["AccountNo"]);
                                        $obju_Bills->setudfvalue("u_custname",$ar2["BusinessName"]);
                                        $obju_Bills->setudfvalue("u_paymode",$ar2["Paymode"]);
                                        $obju_Bills->setudfvalue("u_profitcenter","BPL");
                                        $obju_Bills->setudfvalue("u_module","Business Permit");
                                        $obju_Bills->setudfvalue("u_migrated",1);
                                        $obju_Bills->setudfvalue("u_migrateddate",currentdateDB());
                                        
                                        switch ($ctr) {
                                                        
                                                        case 2:
                                                                $obju_Bills->setudfvalue("u_docdate",$ar2["Year"]."-04-01");
                                                                $startdate = getmonthstartDB($ar2["Year"]."-04-01");
                                                                $obju_Bills->setudfvalue("u_duedate",dateadd("d",$dueday-1,$startdate));
                                                                $obju_Bills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q2, ".$ar2["Year"] );
                                                                $obju_Bills->setudfvalue("u_payqtr",2);
                                                                break;
                                                        case 3:
                                                                $obju_Bills->setudfvalue("u_docdate",$ar2["Year"]."-07-01");
                                                                $startdate = getmonthstartDB($ar2["Year"]."-07-01");
                                                                $obju_Bills->setudfvalue("u_duedate",dateadd("d",$dueday-1,$startdate));
                                                                $obju_Bills->setudfvalue("u_payqtr",3);
                                                                $obju_Bills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q3, ".$ar2["Year"] );
                                                                break;
                                                        case 4:
                                                                $obju_Bills->setudfvalue("u_docdate",$ar2["Year"]."-10-01");
                                                                $startdate = getmonthstartDB($ar2["Year"]."-10-01");
                                                                $obju_Bills->setudfvalue("u_duedate",dateadd("d",$dueday-1,$startdate));
                                                                $obju_Bills->setudfvalue("u_payqtr",4);
                                                                $obju_Bills->setudfvalue("u_remarks","Business Permit & Licensing(Environmental) - Q4, ".$ar2["Year"] );
                                                                break;
                                                }
                                        
                                        $query2 = "SELECT '0005' as itemcode, 'Environmental Fee' as itemdesc, Amount as AmountDue,TaxBase,BusinessLineID  FROM GeoRecords.BPLS.BusinessAssessment WHERE BusinessID = '".$ar2["BusinessID"]."' and Quarter = '".$ctr."' and FeeID in (2);";
                                        $odbcres3 = @odbc_exec($odbccon,$query2) or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query2 . "<br><br>Error Code:  ".odbc_error());
                                        $totalamount=0;
                                        while(odbc_fetch_row($odbcres3)) {
                                                    //Build tempory
                                                    for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
                                                       $field_name = odbc_field_name($odbcres3, $j);
                                                      // $this->temp_fieldnames[$j] = $field_name;
                                                       $ar[$field_name] = odbc_result($odbcres3, $field_name) . "";
                                                    }
                                                    
                                                    $obju_BillItems->prepareadd();
                                                    $obju_BillItems->docid = $obju_Bills->docid;
                                                    $obju_BillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
                                                    $obju_BillItems->setudfvalue("u_itemcode",$ar["itemcode"]);
                                                    $obju_BillItems->setudfvalue("u_itemdesc",$ar["itemdesc"]);
                                                    $obju_BillItems->setudfvalue("u_amount",$ar["AmountDue"]);
                                                    $obju_BillItems->setudfvalue("u_taxbase",$ar["TaxBase"]);
                                                    $obju_BillItems->setudfvalue("u_seqno",20);
                                                    $totalamount+=$ar["AmountDue"];

                                                    $obju_BillItems->privatedata["header"] = $obju_Bills;
                                                    $actionReturn = $obju_BillItems->add();
                                                    if (!$actionReturn) break;
                                         }
                                      
                                        if ($actionReturn && $totalamount > 0) {
                                            $obju_Bills->setudfvalue("u_totalamount",$totalamount);
                                            $obju_Bills->setudfvalue("u_dueamount",$totalamount);
                                            $actionReturn = $obju_Bills->add();
                                        }
                                        
                                        if (!$actionReturn) break;
                                }
                                
                                
                                if ($actionReturn) {
                                        $objConnection->commit();
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