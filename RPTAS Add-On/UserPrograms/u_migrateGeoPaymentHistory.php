<?php


        set_time_limit(0);
        
	$progid = "u_migrateGeoPaymentHistory";

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
        
        $page->objectcode = "u_migrateGeoPaymentHistory";
	$page->paging->formid = "./UDP.php?&objectcode=u_migrateGeoPaymentHistory";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate Payment History";
        $page->settimeout(0);
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
            
                $obju_PaymentHistory = new documentschema_br(null,$objConnection,"u_paymenthistory");
                
                $objConnection->beginwork();
                $odbccon = @odbc_connect("GeoTOS","sa2","12345678",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                
                if ($actionReturn) {
                     $odbcres = @odbc_exec($odbccon,"SELECT a.*,b.username,UPPER(b.FullName) AS FullName FROM GeoTOS.PATAS.PaymentPosting  a
                                                        LEFT JOIN SysMngr.dbo.tbl_UserProfile b on a.UserId = b.SystemUserId
                                                        where a.PaymentPostingId > 2109109
                                                        order by PaymentPostingID desc  ") or die("<B>Error!</B> Couldn't Run Query 12:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                         while(odbc_fetch_row($odbcres)) {
                                //Build tempory
                                for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
                                   $field_name = odbc_field_name($odbcres, $j);
                                  // $this->temp_fieldnames[$j] = $field_name;
                                   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
                                }
                                $obju_PaymentHistory->prepareadd();
                                $obju_PaymentHistory->docid = getNextIDByBranch("u_paymenthistory",$objConnection);
                                $obju_PaymentHistory->docseries = -1;
                                $obju_PaymentHistory->docno = "GeoData: ". $ar["PaymentPostingId"];
                                $obju_PaymentHistory->setudfvalue("u_refno",$ar["TDNId"]);
                                $obju_PaymentHistory->setudfvalue("u_tdno",$ar["TDN"]);
                                $obju_PaymentHistory->setudfvalue("u_payyear",$ar["PayYear"]);
                                $obju_PaymentHistory->setudfvalue("u_orno",$ar["ORNo"]);
                                $obju_PaymentHistory->setudfvalue("u_paiddate",$ar["PayDate"]);
                                $obju_PaymentHistory->setudfvalue("u_startyear",$ar["StartYr"]);
                                $obju_PaymentHistory->setudfvalue("u_startqtr",$ar["StartQtr"]);
                                $obju_PaymentHistory->setudfvalue("u_endyear",$ar["EndYr"]);
                                $obju_PaymentHistory->setudfvalue("u_endqtr",$ar["EndQtr"]);
                                $obju_PaymentHistory->setudfvalue("u_assvalue",$ar["AssessedValue"]);
                                $obju_PaymentHistory->setudfvalue("u_basic",$ar["Basic"]);
                                $obju_PaymentHistory->setudfvalue("u_basicdisc",$ar["BasicDis"]);
                                $obju_PaymentHistory->setudfvalue("u_basicpen",$ar["BasicPen"]);
                                $obju_PaymentHistory->setudfvalue("u_sef",$ar["SEF"]);
                                $obju_PaymentHistory->setudfvalue("u_sefdisc",$ar["SEFDis"]);
                                $obju_PaymentHistory->setudfvalue("u_sefpen",$ar["SEFPen"]);
                                $obju_PaymentHistory->setudfvalue("u_totalamount",$ar["PayAmount"]);
                                $obju_PaymentHistory->setudfvalue("u_userid",$ar["UserId"]);
                                $obju_PaymentHistory->setudfvalue("u_class",$ar["PropClass"]);
                                $obju_PaymentHistory->setudfvalue("u_iscancelled",$ar["IsCancelled"]);
                                $obju_PaymentHistory->setudfvalue("u_cancelleddate",$ar["DateCancelled"]);
                                $obju_PaymentHistory->setudfvalue("u_cancelledby",$ar["CancelledBy"]);
                                $obju_PaymentHistory->setudfvalue("u_paidby",$ar["PaidBy"]);
                                $obju_PaymentHistory->setudfvalue("u_issuedby",$ar["FullName"]);
                                $actionReturn = $obju_PaymentHistory->add();
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
                    if(confirm("MIgrate GEO Records Payment History?")) return true;
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
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migraterptgeo');return false;">Migrate Payments History</a></td>
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