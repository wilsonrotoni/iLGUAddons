<?php


        set_time_limit(0);
        
	$progid = "u_updateGEORPTASAgriLand";

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
        
        $page->objectcode = "u_updateGEORPTASAgriLand";
	$page->paging->formid = "./UDP.php?&objectcode=U_MIGRATEGEORPTAS";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Update Land Assessment";
        $page->settimeout(0);
        
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        $objRs = new recordset(null,$objConnection);
        
        if($action=="migraterptgeo"){   
            
                $obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                $obju_Faas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                $obju_Faas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                $obju_Faas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
                $obju_Faas1d = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
                $obju_Faas1p = new documentlinesschema_br(null,$objConnection,"u_rpfaas1p");
                $objConnection->beginwork();
                $odbccon = @odbc_connect("GeoRecords","","",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                if ($actionReturn) {
                        $faas1acnt = 0;
                        $odbcres2 = @odbc_exec($odbccon,"SELECT A.TDNID,B.TDNLandId,CONCAT(C.ClassificationCode,'-',AA.RevisionYear) AS ClassificationCode,AA.RevisionYear,B.ClassificationID,B.AssessmentLevel,D.ActualUseCode FROM PATAS.TDNLand A
                                                            LEFT JOIN PATAS.TDN AA ON A.TDNID = AA.TDNId AND AA.PropertyTypeId = 1
                                                            INNER JOIN PATAS.TDNLandDetail B ON A.TDNLandID = B.TDNLandId
                                                            LEFT JOIN PATAS.AClassification C ON B.ClassificationID = C.ClassificationId
                                                            LEFT JOIN PATAS.AActualUse D ON B.ActualUseId = D.ActualUseId
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
                                $obju_Faas1a->setudfvalue("u_arpno",$ar2["TDNID"]);
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
                                                                    WHERE B.TDNLandId = '".$ar2["TDNLandId"]."' AND (B.ClassificationID  IS NULL OR B.ClassificationID = '".$ar2["ClassificationID"]."') ") or die("<B>Error!</B> Couldn't Run Query 17:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
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
                    if(confirm("Re-migrate Geodata FAAS Land Assessment?")) return true;
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
	  <td class="labelPageHeader" >&nbsp;Update Geodata Records - Land Assessment&nbsp;</td>
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
                <td  >&nbsp;<a class="button" href="" onClick="formSubmit('migraterptgeo');return false;">Update GeoData Records</a></td>
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