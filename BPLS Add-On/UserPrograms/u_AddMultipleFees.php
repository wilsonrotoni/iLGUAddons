<?php
	$progid = "u_AddMultipleFees";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
        
	include_once("../common/classes/connection.php");
	include_once("../common/classes/grid.php");
	include_once("../common/classes/recordset.php");
	include_once("./schema/bankdeposits.php");
	include_once("./schema/paymentcheques.php");
	include_once("./classes/payments.php");
	include_once("./classes/paymentcheques.php");
	include_once("./sls/countries.php");
	include_once("./sls/banks.php");
	include_once("./sls/housebankaccounts.php");
	include_once("./classes/documentschema_br.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./boschema/checkprinting.php");
	include_once("./utils/businessobjects.php");
	include_once("./sls/enumdocstatus.php");
        include_once("./sls/enumyear.php");

        unset($enumyear["-"]);
	
        
	$page->restoreSavedValues();	

	$page->objectcode = "u_AddMultipleFees";
	$page->paging->formid = "./UDP.php?&objectcode=u_AddMultipleFees";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Add Multiple Fees";
	
	
	$schema["refno"] = createSchemaUpper("refno");
	$schema["refno2"] = createSchemaUpper("refno2");
	$schema["yr"] = createSchemaUpper("yr");	
	$objGrid = new grid("T1",$httpVars);
        
	$objGrid->addcolumn("u_feecode");
	$objGrid->addcolumn("u_feedesc");
	$objGrid->addcolumn("u_amount");
	$objGrid->addcolumn("u_surcharge");
	$objGrid->addcolumn("u_seqno");
	$objGrid->addcolumn("u_regulatory");
        
	$objGrid->columntitle("u_feecode","Fee Code");
	$objGrid->columntitle("u_feedesc","Fee Description");
	$objGrid->columntitle("u_amount","Amount");
	$objGrid->columntitle("u_surcharge","Surcharge");
	$objGrid->columntitle("u_seqno","Seq No");
        
        $objGrid->columnwidth("u_feecode",10);
	$objGrid->columnwidth("u_feedesc",30);
	$objGrid->columnwidth("u_amount",12);
	$objGrid->columnwidth("u_surcharge",12);
	$objGrid->columnwidth("u_seqno",12);
        
	$objGrid->columnalignment("u_amount","right");
	$objGrid->columnalignment("u_surcharge","right");
	$objGrid->columnalignment("u_seqno","right");
        
	$objGrid->columnvisibility("u_regulatory",false);
        
        $objGrid->columninput("u_amount","type","text");
        $objGrid->columninput("u_surcharge","type","text");
         
	$objGrid->automanagecolumnwidth = false;
        $objGrid->selectionmode = 2;
        
        
	if ($lookupSortBy == "") {
		$lookupSortBy = "";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	
	require("./inc/formactions.php");
	
	$objrs = new recordset(null,$objConnection);
        $objrs->queryopenext("select code, name, u_amount,u_regulatory,u_seqno from u_bplfees where u_regulatory = 1 order by u_seqno asc ", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		$page->paging_recordcount($objrs->recordcount());
		while ($objrs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"u_feecode",$objrs->fields["code"]);
			$objGrid->setitem(null,"u_feedesc",$objrs->fields["name"]);
			$objGrid->setitem(null,"u_seqno",$objrs->fields["u_seqno"]);
			$objGrid->setitem(null,"u_regulatory",$objrs->fields["u_regulatory"]);
			$objGrid->setitem(null,"u_amount",formatNumericAmount($objrs->fields["u_amount"]));
			$objGrid->setitem(null,"u_surcharge",formatNumericAmount(0));
			if (!$page->paging_fetch()) break;
		}	
	
	resetTabindex();
	$page->resize->addgrid("T1",20,100,false);
	$page->toolbar->setaction("print",false);
	$rptcols = 6;
       
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
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
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	var autoprint = false;
	var autoprintdocno = "";

    function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
                                    case "checked":
                                        if (row==0) {
                                            if (isTableInputChecked(table,column)) { 
                                                    checkedTableInput(table,column,-1);
                                            } else {
                                                    uncheckedTableInput(table,column,-1); 
                                            }
                                        }	
                                    break;
                                }	
                        break;
		}
                return true;
	}
        function onSelectRow(p_tableId,p_rowIdx) {
            var params = Array();
            switch (p_tableId) {
                case "T1":
                  if (elementFocused.substring(0,13)=="df_u_amountT1") {
                        focusTableInput(p_tableId,"u_amount",p_rowIdx);
                  }
                    params["focus"] = false;
                break;
            }
            return params;
        }
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_yr":
                        return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
                inputs.push("yr");
		return inputs;
	}
	
        function setMultipleFees() {
                var data = new Array();
                var rc =  getTableRowCount("T1");
                var count = 0;
                if (isInputEmpty("yr")) return false;
                showAjaxProcess();
                for (i = 1; i <= rc; i++) {
                    if(getTableInput("T1","rowstat",i) != "X") {
                        if(getTableInput("T1","checked",i)=="1") {
                            data["u_year"] = getInput("yr");
                            data["u_feecode"] = getTableInput("T1","u_feecode",i);
                            data["u_feedesc"] = getTableInput("T1","u_feedesc",i);
                            data["u_common"] = 1;
                            data["u_regulatory"] = getTableInput("T1","u_regulatory",i);
                            data["u_surcharge"] = formatNumericAmount(getTableInput("T1","u_surcharge",i));
                            data["u_amount"] = formatNumericAmount(getTableInput("T1","u_amount",i));
                            window.opener.insertTableRowFromArray("T5",data);
                            count++;
                        }
                    }
                }
                if(count==0) return false;
                window.close();
                hideAjaxProcess();
            return true;
        }
        
        function onElementKeyDown(element,event,column,table,row) {
            switch (column) {
                case "u_filter":
                    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
                    if (sc_press=="ENTER") {
                        formSearchNow();
                    }else if (sc_press=="UP" || sc_press=="DN") {
                        var rc=getTableSelectedRow("T1");
                        selectTableRow("T1",rc+1);
                    }
                    break;
            }
        }
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_bpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("refno2") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Add Multiple Fees&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:0px solid gray" >
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width="56" align=left><label <?php genCaptionHtml($schema["yr"],"") ?>>Year</label></td>
          <td >&nbsp;<select <?php genSelectHtml($schema["yr"],array("loadenumyear","",":[Select]")) ?> ></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a class="button" href="" onClick="setMultipleFees();return false;">Confirm</a></td>
        </tr>
    </table>
    </div>
</td>
</tr></table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php // $page->writeRecordLimit(); ?>
<?php // if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>
    
<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
<script>
resizeObjects();
</script>
