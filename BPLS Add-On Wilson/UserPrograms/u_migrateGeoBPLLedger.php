<?php


        set_time_limit(0);
        
	$progid = "u_migrateGeoBPLLedger";

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
        
        $page->objectcode = "u_migrateGeoBPLLedger";
	$page->paging->formid = "./UDP.php?&objectcode=u_migrateGeoBPLLedger";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate Business Ledger";
        $page->settimeout(0);
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
            
                $obju_BplLedger = new documentschema_br(null,$objConnection,"u_bplledger");
                
                $objConnection->beginwork();
                $odbccon = @odbc_connect("GeoTOS","sa2","12345678",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                 if ($actionReturn) $actionReturn = $obju_BplLedger->executesql("delete from u_bplledger",false);
                if ($actionReturn) {
                        $odbcres = @odbc_exec($odbccon," SELECT  A.*,B.AccountNo,C.FeeDescription FROM GeoTOS.BPLS.BusinessLedger A 
                                                         LEFT JOIN GeoRecords.BPLS.aBusinessFee C ON A.FeeID = C.FeeID
                                                         LEFT JOIN GeoRecords.BPLS.BusinessRecord_HDR B ON A.BusinessID = B.BusinessID 
                                                         order by A.BusinessCollectionID ") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                $obju_BplLedger->prepareadd();
                                $obju_BplLedger->docno = getNextNoByBranch("u_bplledger","",$objConnection);
                                $obju_BplLedger->docid = getNextIDByBranch("u_bplledger",$objConnection);
                                $obju_BplLedger->setudfvalue("u_businesscollectionid",$ar["BusinessCollectionID"]);
                                $obju_BplLedger->setudfvalue("u_userid",$ar["UserID"]);
                                $obju_BplLedger->setudfvalue("u_assdate",$ar["AssessmentDate"]);
                                $obju_BplLedger->setudfvalue("u_duedate",$ar["DueDate"]);
                                $obju_BplLedger->setudfvalue("u_payyear",$ar["Year"]);
                                $obju_BplLedger->setudfvalue("u_revisionyear",$ar["RevisionYear"]);
                                $obju_BplLedger->setudfvalue("u_businessid",$ar["BusinessID"]);
                                $obju_BplLedger->setudfvalue("u_acctno",$ar["AccountNo"]);
                                $obju_BplLedger->setudfvalue("u_ordate",$ar["ORDate"]);
                                $obju_BplLedger->setudfvalue("u_orno",$ar["ORNumber"]);
                                $obju_BplLedger->setudfvalue("u_businesslineid",$ar["BusinessLineID"]);
                                $obju_BplLedger->setudfvalue("u_feeid",$ar["FeeID"]);
                                $obju_BplLedger->setudfvalue("u_feedesc",$ar["FeeDescription"]);
                                $obju_BplLedger->setudfvalue("u_taxbase",$ar["TaxBase"]);
                                $obju_BplLedger->setudfvalue("u_amountdue",$ar["AmountDue"]);
                                $obju_BplLedger->setudfvalue("u_interest",$ar["Interest"]);
                                $obju_BplLedger->setudfvalue("u_surcharge",$ar["Surcharge"]);
                                $obju_BplLedger->setudfvalue("u_amountpaid",$ar["AmountPaid"]);
                                $obju_BplLedger->setudfvalue("u_quarter",$ar["Quarter"]);
                                $obju_BplLedger->setudfvalue("u_origamount",$ar["OrigAmount"]);
                                $obju_BplLedger->setudfvalue("u_paymode",$ar["PaymentMode"]);
                                $obju_BplLedger->setudfvalue("u_rownumber",$ar["RowNumber"]);
                                $obju_BplLedger->setudfvalue("u_iscancelled",$ar["Cancelled"]);
                                $obju_BplLedger->setudfvalue("u_originalorno",$ar["OriginalORNumber"]);
                                $actionReturn = $obju_BplLedger->add();
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
                    if(confirm("MIgrate GEO Records Business Ledger?")) return true;
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
	  <td class="labelPageHeader" >&nbsp;Migrate Geodata Payment History&nbsp;</td>
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
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migraterptgeo');return false;">Migrate GeoData Business Ledger</a></td>
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