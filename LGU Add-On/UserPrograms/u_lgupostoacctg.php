<?php
	$progid = "u_lgupostoacctg";

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
        


	$search=false;
	$retired = false;
	$page->restoreSavedValues();
        
        $page->objectcode = "u_lgupostoacctg";
	$page->paging->formid = "./UDP.php?&objectcode=u_lgupostoacctg";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Treasury to Accounting Batch Processing";
        
        $schema["u_ordatefr"] = createSchemaDate("u_ordatefr");
	$schema["u_ordateto"] = createSchemaDate("u_ordateto");
	$schema["u_orfr"] = createSchemaUpper("u_orfr");
	$schema["u_orto"] = createSchemaUpper("u_orto");
	$schema["u_curtotalamount"] = createSchemaUpper("u_curtotalamount");
	
        $schema["u_curtotalamount"]["attributes"] = "disabled";
        
        $objGridA = new grid("T1",$httpVars);
	$objGridA->addcolumn("u_ornumber");
	$objGridA->addcolumn("u_paidby");
	$objGridA->addcolumn("u_paidamount");
	$objGridA->addcolumn("u_date");
	$objGridA->addcolumn("u_status");
	$objGridA->addcolumn("u_cashierid");
	
	$objGridA->columntitle("u_ornumber","Receipt No");
	$objGridA->columntitle("u_paidby","Paid By");
	$objGridA->columntitle("u_paidamount","Amount Paid");
	$objGridA->columntitle("u_date","Receipt Date");
	$objGridA->columntitle("u_status","Status");
	$objGridA->columntitle("u_cashierid","Cashier Id");
        
	$objGridA->columnwidth("u_ornumber",10);
	$objGridA->columnwidth("u_paidby",16);
	$objGridA->columnwidth("u_paidamount",10);
	$objGridA->columnwidth("u_date",10);
	$objGridA->columnwidth("u_status",10);
	$objGridA->columnwidth("u_cashierid",15);
        
	$objGridA->columnsortable("u_ornumber",true);
	$objGridA->columnsortable("u_paidby",true);
	$objGridA->columnsortable("u_paidamount",true);
	$objGridA->columnsortable("u_date",true);
	$objGridA->columnsortable("u_status",true);
	$objGridA->columnsortable("u_cashierid",true);
        $objGridA->height = 450;
        $objGridA->width = 1024;
	$objGridA->automanagecolumnwidth = true;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "ornumber";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGridA->setsort($lookupSortBy,$lookupSortAs);

        function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
                if($action=="migratepayment"){
                        
                    if($page->getitemstring("u_ordatefr") == "" && $page->getitemstring("u_orfr") == ""){
                        return raiseError("Date/Ornumber is Required.");
                    }
                        $httpVars = array_merge($_POST,$_GET);
                        $obju_LGUPosAcct = new documentschema_br(null,$objConnection,"u_lguposacctgsharing");
                        $objRs = new recordset(null,$objConnection);
                        
                        $objConnection->beginwork();
                        
                        $filterExp1 = "";
                        $filterExp1 = genSQLFilterDate("A.U_DATE",$filterExp1,$httpVars['df_u_ordatefr'],$httpVars['df_u_ordateto']);
                        $filterExp1 = genSQLFilterString("A.DOCNO",$filterExp1,$httpVars['df_u_orfr'],$httpVars['df_u_orto']);	
                        if ($filterExp1 !="") $filterExp1 = " AND " . $filterExp1;
                 
		$objRs->queryopen("SELECT A.U_STATUS,A.CREATEDBY,C.LINEID,A.U_DATE,A.DOCNO,C.U_ITEMCODE,C.U_ITEMDESC,SUM(C.U_LINETOTAL) AS U_LINETOTAL,B.U_BSHARE,B.U_MSHARE,B.U_PSHARE,B.U_NSHARE,B.U_BGLCODE,B.U_MGLCODE,B.U_PGLCODE,B.U_NGLCODE 
                                                FROM U_LGUPOS A
                                                INNER JOIN U_LGUPOSITEMS C ON A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH AND A.DOCID = C.DOCID AND C.U_LINETOTAL > 0
                                                INNER JOIN U_LGUFEES B ON C.U_ITEMCODE = B.CODE 
                                                WHERE A.DOCSTATUS NOT IN('CN','D') AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' $filterExp1 GROUP BY C.U_ITEMCODE");
 		
                while ($objRs->queryfetchrow("NAME")) {
                                    $linetotal = 0 ;
                                    if($actionReturn){
                                        if($objRs->fields["U_PSHARE"] > 0){
                                            if($objRs->fields["U_PGLCODE"] == "") return raiseError("Provincal Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                                            $obju_LGUPosAcct->prepareadd();
                                            $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                                            $obju_LGUPosAcct->docno = "P-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                                            $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                                            $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                                            $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_PGLCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_sharetype","P");
                                            $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                                            $obju_LGUPosAcct->setudfvalue("u_amount",($objRs->fields["U_PSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                                            $linetotal = $linetotal + (($objRs->fields["U_PSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                                            $actionReturn = $obju_LGUPosAcct->add();
                                            if (!$actionReturn) break;
                                        }
                                    }
                                    if($actionReturn){
                                        if($objRs->fields["U_NSHARE"] > 0){
                                            if($objRs->fields["U_NGLCODE"] == "") return raiseError("National Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                                            $obju_LGUPosAcct->prepareadd();
                                            $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                                            $obju_LGUPosAcct->docno = "N-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                                            $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                                            $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                                            $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_NGLCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_sharetype","N");
                                            $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                                            $obju_LGUPosAcct->setudfvalue("u_amount",($objRs->fields["U_NSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                                            $linetotal = $linetotal + (($objRs->fields["U_NSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                                            $actionReturn = $obju_LGUPosAcct->add();
                                            if (!$actionReturn) break;
                                        }
                                    }
                                    if($actionReturn){
                                        if($objRs->fields["U_BSHARE"] > 0){
                                            if($objRs->fields["U_BGLCODE"] == "") return raiseError("Barangay Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                                            $obju_LGUPosAcct->prepareadd();
                                            $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                                            $obju_LGUPosAcct->docno = "B-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                                            $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                                            $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                                            $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_BGLCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_sharetype","B");
                                            $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                                            $obju_LGUPosAcct->setudfvalue("u_amount",($objRs->fields["U_BSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                                            $linetotal = $linetotal + (($objRs->fields["U_BSHARE"] / 100) * $objRs->fields["U_LINETOTAL"]);
                                            $actionReturn = $obju_LGUPosAcct->add();
                                            if (!$actionReturn) break;
                                        }
                                    }
                                    if($actionReturn){
                                        if($objRs->fields["U_MSHARE"] > 0){
                                            if($objRs->fields["U_MGLCODE"] == "") return raiseError("Municipal Gl Code setup is required for Item[".$objRs->fields["U_ITEMDESC"]."]");
                                            $obju_LGUPosAcct->prepareadd();
                                            $obju_LGUPosAcct->docid = getNextIdByBranch("u_lguposacctgsharing",$objConnection);
                                            $obju_LGUPosAcct->docno = "M-".$objRs->fields["DOCNO"]."-".$objRs->fields["U_ITEMCODE"];
                                            $obju_LGUPosAcct->setudfvalue("u_date",$objRs->fields["U_DATE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_orno",$objRs->fields["DOCNO"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                                            $obju_LGUPosAcct->setudfvalue("u_reflinetotal",$objRs->fields["U_LINETOTAL"]);
                                            $obju_LGUPosAcct->setudfvalue("u_collector",$objRs->fields["CREATEDBY"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                                            $obju_LGUPosAcct->setudfvalue("u_glcode",$objRs->fields["U_MGLCODE"]);
                                            $obju_LGUPosAcct->setudfvalue("u_sharetype","M");
                                            $obju_LGUPosAcct->setudfvalue("u_status",$objRs->fields["U_STATUS"]);
                                            $obju_LGUPosAcct->setudfvalue("u_amount",$objRs->fields["U_LINETOTAL"] - $linetotal);
                                            $actionReturn = $obju_LGUPosAcct->add();
                                            if (!$actionReturn) break;
                                        }
                                    }
                                }
                        
                
                if ($actionReturn) {
                        $objConnection->commit();
                } else {
                        $objConnection->rollback();
                        echo $_SESSION["errormessage"];
       
                }
                
                    
                    
                }
                

		return $actionReturn;
	}
		
	require("./inc/formactions.php");
        
        $filterExp = "";
//	
	$filterExp = genSQLFilterDate("A.U_DATE",$filterExp,$httpVars['df_u_ordatefr'],$httpVars['df_u_ordateto']);
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_u_orfr'],$httpVars['df_u_orto']);	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
        

	$objrs = new recordset(null,$objConnection);

		$objrs->queryopenext("select A.DOCNO,A.U_CUSTNAME,A.U_PAIDAMOUNT,A.U_DATE,IF(A.U_STATUS='CN','Cancelled','Saved') AS U_STATUS,A.CREATEDBY from U_LGUPOS A  WHERE   A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
 //		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
                $curtotal = 0;
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGridA->addrow();
	
			$objGridA->setitem(null,"u_ornumber",$objrs->fields["DOCNO"]);
			$objGridA->setitem(null,"u_paidby",$objrs->fields["U_CUSTNAME"]);
			$objGridA->setitem(null,"u_paidamount",$objrs->fields["U_PAIDAMOUNT"]);
			$objGridA->setitem(null,"u_date",formatDateToHttp($objrs->fields["U_DATE"]));
			$objGridA->setitem(null,"u_status",$objrs->fields["U_STATUS"]);
			$objGridA->setitem(null,"u_cashierid",$objrs->fields["CREATEDBY"]);
			$curtotal+= iif($objrs->fields["U_STATUS"]=="CN",0,$objrs->fields["U_PAIDAMOUNT"]) ;
			//$objGridA->setkey(null,$objrs->fields["u_ornumber"]); 
		 if (!$page->paging_fetch()) break;
		}
                $page->setitem("u_curtotalamount",formatNumericAmount($curtotal));
//	//}
//	
	resetTabindex();
	setTabindex($schema["u_ordatefr"]);
	setTabindex($schema["u_ordateto"]);
	setTabindex($schema["u_orfr"]);
	setTabindex($schema["u_orto"]);
        
	
//?>

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
    function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_street");
	}

	function onFormSubmit(action) {
            var rc = getTableRowCount("T1");
        
                if(action=="migratepayment"){
                    if(rc==0){
                        page.statusbar.showWarning("No Data found.");
                        return false;
                    }else{
                        if(confirm("Migrate this data?")){
                            return true;
                        }else{
                            return false;
                        } 
                    }
                    
                }    
            
            
	}

	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				formPageReset(); 
				clearTable("T1");
				break;
			case "u_ordateto": 
			case "u_ordatefr": 
			case "u_orto": 
			case "u_orfr": 
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	


	function onPageSaveValues(p_action) {
		var inputs = new Array();
			
			inputs.push("u_ordateto");
			inputs.push("u_ordatefr");
			inputs.push("u_orto");
			inputs.push("u_orfr");
			inputs.push("u_curtotalamount");
			
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_ordatefr":
			case "u_ordateto":
			case "u_orfr":
			case "u_orto":
			case "u_curtotalamount":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
		}
	}	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
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
	  <td class="labelPageHeader" >&nbsp;Treasury to Accounting Batch Processing&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td>
</tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:0px solid gray" >
     
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;<label <?php genCaptionHtml($schema["u_ordatefr"],"") ?>>Date From</label></td>
		<td width="795" align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_ordatefr"]) ?> /></td>
		<td width="124" ></td>
		<td width="166" align=left>&nbsp;</td>
	</tr>
	<tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_ordateto"],"") ?>>Date To</label></td>
		<td  align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_ordateto"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_orfr"],"") ?>>Or # From</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_orfr"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
   <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_orto"],"") ?>>Or # To</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_orto"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_curtotalamount"],"") ?>>Total Amount per Page</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_curtotalamount"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
        <tr><td >&nbsp;</td> </tr>
    <tr><td >&nbsp;</td>
		<td  align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a> &nbsp;&nbsp;&nbsp; <a class="button" href="" onClick="formSubmit('migratepayment');return false;">Migrate Data</a></td>
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

<tr>
    <td>
   <div class="tabber" id="tab1">
		<div class="tabbertab" title="LGU PAYMENTS">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                        <td><label class="lblobjs"><b>Current Data</b></label> <?php $objGridA->draw()?></td>
                        <td> &nbsp;</td>
                        </tr>
                 
                    </table>  
                </div> 

	</div>
        
    </td>
</tr>
<?php $page->writeRecordLimit(); ?>
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