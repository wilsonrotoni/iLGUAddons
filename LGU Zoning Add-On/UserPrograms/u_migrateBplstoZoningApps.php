<?php
	$progid = "u_migrateBplstoZoningApps";

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
            
        $page->objectcode = "u_migrateBplstoZoningApps";
	$page->paging->formid = "./UDP.php?&objectcode=u_migrateBplstoZoningApps";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Migrate GEO BPLS";
        
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        if($action=="migratebplgeo"){   
                $objRs = new recordset(null,$objConnection);
                $obju_ZoningApps = new documentschema_br(null,$objConnection,"u_zoningclrapps");
                $obju_ZoningAppFees = new documentlinesschema_br(null,$objConnection,"u_zoningclrappfees");
                
                $objConnection->beginwork();
                $objRs->queryopen(" SELECT B.DOCID,B.U_APPTYPE,B.U_APPDATE,B.DOCNO as U_BUSINESSID,B.U_APPNO,B.U_RETIRED,B.U_YEAR,B.U_TRADENAME,B.U_TLASTNAME,C.BUSINESSNATURE,
                                    B.U_LASTNAME,B.U_FIRSTNAME,B.U_MIDDLENAME,B.U_OWNERADDRESS,B.U_TELNO,
                                    B.U_BBRGY,B.U_BBLDGNO,B.U_BBLOCK,B.U_BLOTNO,B.U_BVILLAGE,B.U_BSTREET,B.U_BPHASENO,B.U_BADDRESSNO,B.U_BBLDGNAME,
                                    B.U_BUNITNO,B.U_BFLOORNO,B.U_CITY,B.U_BPROVINCE,B.U_BTELNO FROM U_BPLMDS A
                                    INNER JOIN U_BPLAPPS B ON A.CODE = B.U_APPNO AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.U_APPREFNO = B.DOCNO
                                    INNER JOIN (SELECT A.COMPANY,A.BRANCH,A.DOCID, GROUP_CONCAT(DISTINCT U_BUSINESSNATURE) AS BUSINESSNATURE FROM U_BPLAPPLINES A
                                                INNER JOIN U_BPLLINES B ON A.U_BUSINESSLINE = B.CODE
                                                GROUP BY A.DOCID) AS C ON B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH AND B.DOCID = C.DOCID ");
                while ($objRs->queryfetchrow("NAME")) {
                    $obju_ZoningApps->prepareadd();
                    $obju_ZoningApps->docno = getNextNoByBranch("u_zoningclrapps","",$objConnection);
                    $obju_ZoningApps->docid = getNextIdByBranch("u_zoningclrapps",$objConnection);

                    if($objRs->fields["U_RETIRED"] == "1")  $obju_ZoningApps->docstatus = "RT";
                    else  $obju_ZoningApps->docstatus = "AP";
                    if($objRs->fields["U_APPTYPE"] == "NEW") $obju_ZoningApps->setudfvalue("u_appnature","New Application");
                    else $obju_ZoningApps->setudfvalue("u_appnature","Renewal");

                    $obju_ZoningApps->setudfvalue("u_year",$objRs->fields["U_YEAR"]);
                    $obju_ZoningApps->setudfvalue("u_docdate",$objRs->fields["U_APPDATE"]);
                    $obju_ZoningApps->setudfvalue("u_acctno",$objRs->fields["U_APPNO"]);
                    $obju_ZoningApps->setudfvalue("u_bplappno",$objRs->fields["U_BUSINESSID"]);

                    $obju_ZoningApps->setudfvalue("u_businessname",$objRs->fields["U_TRADENAME"]);
                    $obju_ZoningApps->setudfvalue("u_authrep",$objRs->fields["U_TLASTNAME"]);
                    $obju_ZoningApps->setudfvalue("u_natureofbusiness",$objRs->fields["BUSINESSNATURE"]);
                    $obju_ZoningApps->setudfvalue("u_lastname",$objRs->fields["U_LASTNAME"]);
                    $obju_ZoningApps->setudfvalue("u_firstname",$objRs->fields["U_FIRSTNAME"]);
                    $obju_ZoningApps->setudfvalue("u_middlename",$objRs->fields["U_MIDDLENAME"]);
                    $obju_ZoningApps->setudfvalue("u_owneraddress",$objRs->fields["U_OWNERADDRESS"]);
                    $obju_ZoningApps->setudfvalue("u_contactno",$objRs->fields["U_TELNO"]);


                    $obju_ZoningApps->setudfvalue("u_bbrgy",$objRs->fields["U_BBRGY"]);
                    $obju_ZoningApps->setudfvalue("u_bbldgno",$objRs->fields["U_BBLDGNO"]);
                    $obju_ZoningApps->setudfvalue("u_bblock",$objRs->fields["U_BBLOCK"]);
                    $obju_ZoningApps->setudfvalue("u_blotno",$objRs->fields["U_BLOTNO"]);
                    $obju_ZoningApps->setudfvalue("u_bvillage",$objRs->fields["U_BVILLAGE"]);
                    $obju_ZoningApps->setudfvalue("u_bbldgname",$objRs->fields["U_BBLDGNAME"]);
                    $obju_ZoningApps->setudfvalue("u_bunitno",$objRs->fields["U_BUNITNO"]);
                    $obju_ZoningApps->setudfvalue("u_bfloorno",$objRs->fields["U_BFLOORNO"]);
                    $obju_ZoningApps->setudfvalue("u_bstreet",$objRs->fields["U_BSTREET"]);
                    $obju_ZoningApps->setudfvalue("u_bcity","BACOOR");
                    $obju_ZoningApps->setudfvalue("u_bprovince","CAVITE");
                    $obju_ZoningApps->setudfvalue("u_bphaseno",$objRs->fields["U_BPHASENO"]);
                    $obju_ZoningApps->setudfvalue("u_btelno",$objRs->fields["U_BTELNO"]);
                    $obju_ZoningApps->setudfvalue("u_baddressno",$objRs->fields["U_BADDRESSNO"]);

                    if (!$actionReturn) break;
                    if ($actionReturn) {
                                $totalassessment = 0;
                                $objRs2 = new recordset(null,$objConnection);
                                $objRs2->queryopen("SELECT U_YEAR,U_FEECODE,U_FEEDESC,U_AMOUNT,U_SEQNO FROM U_BPLAPPFEES 
                                                      WHERE DOCID = '".$objRs->fields["DOCID"]."' AND COMPANY = '".$_SESSION['company']."' AND BRANCH = '".$_SESSION['branch']."' AND U_FEECODE IN ('17','0429') ");
                                while ($objRs2->queryfetchrow("NAME")) {
                                        $obju_ZoningAppFees->prepareadd();
                                        $obju_ZoningAppFees->docid = $obju_ZoningApps->docid;
                                        $obju_ZoningAppFees->lineid = getNextIDByBranch("u_zoningclrappfees",$objConnection);
                                        $obju_ZoningAppFees->setudfvalue("u_year",$objRs2->fields["U_YEAR"]);
                                        $obju_ZoningAppFees->setudfvalue("u_feecode",$objRs2->fields["U_FEECODE"]);
                                        $obju_ZoningAppFees->setudfvalue("u_feedesc",$objRs2->fields["U_FEEDESC"]);
                                        $obju_ZoningAppFees->setudfvalue("u_amount",$objRs2->fields["U_AMOUNT"]);
                                        $obju_ZoningAppFees->setudfvalue("u_seqno",$objRs2->fields["U_SEQNO"]);

                                        $actionReturn = $obju_ZoningAppFees->add();
                                        $totalassessment +=  $objRs2->fields["U_AMOUNT"] ; 
                                        if (!$actionReturn) break;
                                }   
                    }
                    $obju_ZoningApps->setudfvalue("u_orsfamt",$totalassessment);
                    $actionReturn = $obju_ZoningApps->add();
                    if (!$actionReturn) break;
            }
                    if ($actionReturn) {
                        $objConnection->commit();
//                                var_dump($num_rows);
                    } else {
                           $objConnection->rollback();
                           echo $_SESSION["errormessage"];
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
	  <td class="labelPageHeader" >&nbsp;Migrate BPLS to Zoning Apps&nbsp;</td>
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