<?php


        set_time_limit(0);
        
	$progid = "u_updateBPLLedger";

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
        
        $page->objectcode = "u_updateBPLLedger";
	$page->paging->formid = "./UDP.php?&objectcode=u_updateBPLLedger";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Update Business Ledger";
        $page->settimeout(0);
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
            
                $obju_BplLedger = new documentschema_br(null,$objConnection,"u_bplledger");
                $objRs->queryopen("SELECT SUBSTRING(A.DOCNO,6,10) AS U_BUSINESSID,B.U_YEAR,A.U_APPNO,B.U_BUSINESSLINE,(B.U_CAPITAL + B.U_NONESSENTIAL) AS U_TAXBASE,C.U_ORNO,C.U_ORDATE,C.U_ASSDATE,C.U_DUEDATE FROM U_BPLAPPS A
                                    INNER JOIN U_BPLAPPLINES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID AND B.U_YEAR <='2020' AND B.U_BTAXLINETOTAL <=0
                                    INNER JOIN U_BPLLEDGER C ON A.U_APPNO = C.U_ACCTNO AND B.U_YEAR = C.U_PAYYEAR AND C.U_FEEID = '0001' AND C.U_BUSINESSLINEID != B.U_BUSINESSLINE AND A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH
                                    GROUP BY A.U_APPNO,B.U_YEAR,B.U_BUSINESSLINE ");
                while ($objRs->queryfetchrow("NAME")) {
                                $obju_BplLedger->prepareadd();
                                $obju_BplLedger->docno = getNextNoByBranch("u_bplledger","",$objConnection);
                                $obju_BplLedger->docid = getNextIDByBranch("u_bplledger",$objConnection);
                                $obju_BplLedger->setudfvalue("u_userid","manager-migrated");
                                $obju_BplLedger->setudfvalue("u_assdate",$objRs->fields["U_ASSDATE"]);
                                $obju_BplLedger->setudfvalue("u_duedate",$objRs->fields["U_DUEDATE"]);
                                $obju_BplLedger->setudfvalue("u_payyear",$objRs->fields["U_YEAR"]);
                                $obju_BplLedger->setudfvalue("u_revisionyear","2008");
                                $obju_BplLedger->setudfvalue("u_businessid",$objRs->fields["U_BUSINESSID"]);
                                $obju_BplLedger->setudfvalue("u_acctno",$objRs->fields["U_APPNO"]);
                                $obju_BplLedger->setudfvalue("u_ordate",$objRs->fields["U_ORDATE"]);
                                $obju_BplLedger->setudfvalue("u_orno",$objRs->fields["U_ORNO"]);
                                $obju_BplLedger->setudfvalue("u_businesslineid",$objRs->fields["U_BUSINESSLINE"]);
                                $obju_BplLedger->setudfvalue("u_feeid","0001");
                                $obju_BplLedger->setudfvalue("u_feedesc","Business Tax");
                                $obju_BplLedger->setudfvalue("u_taxbase",$objRs->fields["U_TAXBASE"]);
                                $obju_BplLedger->setudfvalue("u_amountdue",0);
                                $obju_BplLedger->setudfvalue("u_interest",0);
                                $obju_BplLedger->setudfvalue("u_surcharge",0);
                                $obju_BplLedger->setudfvalue("u_amountpaid",0);
                                $obju_BplLedger->setudfvalue("u_quarter",4);
                                $obju_BplLedger->setudfvalue("u_origamount",0);
                                $obju_BplLedger->setudfvalue("u_paymode","A");
                                $obju_BplLedger->setudfvalue("u_rownumber",0);
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
                    if(confirm("Update Business Ledger?")) return true;
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
	  <td class="labelPageHeader" >&nbsp;Update Payment History Zero Line Total&nbsp;</td>
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
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migraterptgeo');return false;">Update Business Ledger</a></td>
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