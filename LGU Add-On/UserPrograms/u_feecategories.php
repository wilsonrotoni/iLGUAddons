<?php
    $progid = "u_feecategories";
        
    if(!empty($_POST)) extract($_POST);
    if(!empty($_GET)) extract($_GET);
    $httpVars = array_merge($_POST,$_GET);
    
    include_once("../common/classes/connection.php");
    include_once("../common/classes/recordset.php");
    include_once("../common/classes/grid.php");
    include_once("./classes/documentschema_br.php");
    include_once("./sls/enumdocstatus.php");
    include_once("./utils/companies.php");
    include_once("./inc/formutils.php");
    include_once("./inc/formaccess.php");
    
    $page->restoreSavedValues();	
     
  
    $page->objectcode = "u_feecategories";
    $page->paging->formid = "./UDP.php?&objectcode=u_feecategories";
    $page->formid = "./UDO.php?&objectcode=u_lgureceipts";
    $page->objectname = "Fees and Charges Category";
    
    $schema["u_filter"] = createSchemaUpper("u_filter");
    $schema["u_itemcode"] = createSchemaUpper("u_itemcode");
    $schema["doctype"] = createSchemaUpper("doctype");
    
    $objGridDetailDocno = new grid("T20",$httpVars);
    
    $objGridDetailDocno->addcolumn("name");
    $objGridDetailDocno->columntitle("name","Category");
    $objGridDetailDocno->columnwidth("name",30);
    
    $objGridDetailDocno->columnsortable("name",true);

    $objGridDetailDocno->automanagecolumnwidth = true;
    $objGridDetailDocno->height = 250;
    $objGridDetailDocno->selectionmode = 2;
    
    $objGridDetail = new grid("T10",$httpVars);
    //$objGridDetail->addcolumn("chkno");
    
    $objGridDetail->addcolumn("feecode");
    $objGridDetail->addcolumn("feedesc");
    $objGridDetail->addcolumn("amount");

    //$objGridDetail->columntitle("chkno","*");
    $objGridDetail->columntitle("feecode","Fee Code");
    $objGridDetail->columntitle("feedesc","Description");
    $objGridDetail->columntitle("amount","Amount");

    $objGridDetail->columnwidth("itemcode",20);
    $objGridDetail->columnwidth("itemdesc",30);
    $objGridDetail->columnwidth("amount",12);
    
    $objGridDetail->columnalignment("amount","right");
    
    $objGridDetail->selectionmode = 2;

    $objGridDetail->columninput("amount","type","text");
    
    $objGridDetail->automanagecolumnwidth = true;
    $objGridDetail->dataentry = false;
//    $objGridDetail->width = 760;
    $objGridDetail->height = 280;
    
    if ($lookupSortBy == "") {
            $lookupSortBy = "name";
    } else  $lookupSortBy = strtolower($lookupSortBy);
    $objGridDetailDocno->setsort($lookupSortBy,$lookupSortAs);
    
    $filterExp = "";
    if($httpVars['df_u_filter']!= ""){
       $filterExp = genSQLFilterString($lookupSortBy,$filterExp,$httpVars['df_u_filter'],null,null,true);
       if ($filterExp !="") $filterExp = " WHERE " . $filterExp; 
    }
    
    $objrs = new recordset(null,$objConnection);
    
    $objrs->queryopenext("SELECT name From u_lgufeecategories  $filterExp ", $objGridDetailDocno->sortby,"",$objGridDetailDocno->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        
    $page->paging_recordcount($objrs->recordcount());
        while ($objrs->queryfetchrow("NAME")) {
            $objGridDetailDocno->addrow();
            $objGridDetailDocno->setitem(null,"name",$objrs->fields["name"]);
        }
    
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
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">
    
    function onPageLoad() {
//        focusInput(getInput("u_filter"));
        document.getElementById('Items').style.display = 'none';
        document.getElementById('Documents').style.display = 'block';
    }
   function onFormSubmitReturn(action,success,error) {

                try {
                        if (window.opener.getVar("objectcode")=="U_LGUPURCHASEORDER") {

                        }
                } catch (theError) {
                }
		return true;
    }
    function onFormSubmit(action) {
      return true;
    }
    
    function onElementValidate(element,column,table,row) {
        switch (table) {
            case "T10":
                switch (column) {
                    case "quantity":
                    case "unitprice":
                        if(getTableInputNumeric(table,"openquantity",row) < getTableInputNumeric(table,"quantity",row)) {
                                setTableInput(table,"quantity",formatNumber(0.00,"quantity"),row);
                        }
                        var total = getTableInputNumeric(table,"unitprice",row)*getTableInputNumeric(table,"quantity",row);
                        setTableInput(table,"linetotal",formatNumber(total,"amount"),row);
                       
                    break;
                }
            break;
        }
        
        return true;
    }
    function onElementClick(element,column,table,row) {
        switch (table) {
            case "T10":
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
            case "T20":
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
              case "T20":
                   if (isTableInputChecked(p_tableId,"checked",p_rowIdx)) { 
                       uncheckedTableInput(p_tableId,"checked",p_rowIdx); 
                    } else {
                        checkedTableInput(p_tableId,"checked",p_rowIdx);
                        
                    }
              break;
              case "T10":
                if (isTableInputChecked(p_tableId,"checked",p_rowIdx)) { 
                    uncheckedTableInput(p_tableId,"checked",p_rowIdx); 
                } else {
                    checkedTableInput(p_tableId,"checked",p_rowIdx);

                }
                if (elementFocused.substring(0,13)=="df_amountT1") {
                        focusTableInput(p_tableId,"u_amount",p_rowIdx);
                        checkedTableInput(p_tableId,"checked",p_rowIdx);
                }
                params["focus"] = false;
              break;
          }
          return params;
    }
   
    function closepopupframeGPSLGUPurchasing(){
        try {
         window.close();
        }catch(theError){
        }
    }
    function setPrevGPSLGUPurchasing(){
        document.getElementById('Items').style.display = 'none';
        document.getElementById('Documents').style.display = 'block';
    }
    function setNextGPSLGUPurchasing(){
        var rc =  getTableRowCount("T20"),count = 0;
        
        for (i = 1; i <= rc; i++) {
            if(getTableInput("T20","checked",i)=="1") {
                count++;
            }
	}
        if(count==0) return false;
        if(rc==0){
            page.statusbar.showError("No Record List");	
            return false;
        }else{
            document.getElementById('Items').style.display = 'block';
            document.getElementById('Documents').style.display = 'none';
            setInsertItemsGPSLGUPurchasing();
        }
    }
    
    function setInsertItemsGPSLGUPurchasing() {
	var result, data = new Array();
	var rc =  getTableRowCount("T20");
	showAjaxProcess();
	clearTable("T10",true);
       
	for (i = 1; i <= rc; i++) {
		if(getTableInput("T20","rowstat",i) != "X") {
			if(getTableInput("T20","checked",i)=="1") {
			result = page.executeFormattedQuery("SELECT u_feecode,u_feedesc,u_amount from u_lgufeecategoryfees WHERE code = '"+getTableInput("T20","name",i)+"' ");	 			
			if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (iii = 0; iii < result.childNodes.length; iii++) {
							//data["chkno"] = 0;
							data["feecode"] = result.childNodes.item(iii).getAttribute("u_feecode");
							data["feedesc"] = result.childNodes.item(iii).getAttribute("u_feedesc");
							data["amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
							insertTableRowFromArray("T10",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving Fees. Try Again, if problem persists, check the connection.");	
					return false;
				}
			}
		}
	}
	hideAjaxProcess();
	return true;
    }
    function getCFAccessGPSLGUPurchasing() {
            var result, data = new Array();
            var rc =  getTableRowCount("T10"),count = 0;

            for (i = 1; i <= rc; i++) {
//                alert(getTableInput("T10","checked",i));
                    if(getTableInput("T10","checked",i)=="Y") {
                            if(getTableInput("T10","rowstat",i) != "X") {
                                    count++;
                            }
                    }
            }
//            alert(count);
            if (rc==0) {
                    page.statusbar.showError("No Record List");	
                    return false;
            } else {
                    if (count==0) {
                            page.statusbar.showError("Please Select Item/s.");	
                            return false;
                    } else {
//                            closepopupframeGPSLGUPurchasing();
                            showAjaxProcess();
                            window.opener.clearTable("T1",true);
//                            var remarks = "";
//                            for (var iii = 1; iii <= getTableRowCount("T20"); iii++) {
//                                    if(getTableInput("T20","rowstat",iii) != "X") {
//                                            if(getTableInput("T20","checked",iii)==1) {
//                                                    result = page.executeFormattedQuery("SELECT u_profitcenter,u_profitcentername,u_procmode  FROM u_lgupurchaserequests WHERE company='" + getGlobal("company") + "' and docno = '"+getTableInput("T20","docno",iii)+"'");	 			
//                                                    if (result.getAttribute("result")!= "-1") {
//                                                        if (parseInt(result.getAttribute("result"))>0) {
//                                                                for (iiii = 0; iiii < result.childNodes.length; iiii++) {
//                                                                    window.opener.setInput("u_procmode",result.childNodes.item(iiii).getAttribute("u_procmode"));
//                                                                    window.opener.setInput("u_profitcenter",result.childNodes.item(iiii).getAttribute("u_profitcenter"));
//                                                                    window.opener.setInput("u_profitcentername",result.childNodes.item(iiii).getAttribute("u_profitcentername"));
//                                                            }
//                                                        }
//                                                    }
//                                                   
//                                                    remarks += "Base on Purchase Request " + getTableInput("T20","docno",iii) + ". ";
//                                                   
//                                            }
//                                    }
//                            }
                            
                            for (var i = 1; i <= getTableRowCount("T10"); i++) {
                                    if(getTableInput("T10","rowstat",i) != "X") {
                                            if(getTableInput("T10","checked",i)=="Y") {
                                                    data["u_itemcode"] = getTableInput("T10","feecode",i);
                                                    data["u_itemdesc"] = getTableInput("T10","feedesc",i);
                                                    data["u_quantity"] = 1;
                                                    data["u_unitprice"] = formatNumericAmount(getTableInputNumeric("T10","amount",i));
                                                    data["u_linetotal"] = formatNumericAmount(getTableInputNumeric("T10","amount",i));
                                                   
                                                    window.opener.insertTableRowFromArray("T1",data);
                                            }
                                    }
                            }
                          
                            window.close();
                    }
            }
            hideAjaxProcess();
            return true;
    }

    function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_filter");
		return inputs;
    }
    function formSearchNow() {
        acceptText();
        formSearch('','<?php echo $page->paging->formid; ?>');
    }
    function onElementKeyDown(element,event,column,table,row) {
        switch (column) {
            case "u_filter":
                var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
                if (sc_press=="ENTER") {
                    formSearchNow();
                }else if (sc_press=="UP" || sc_press=="DN") {
                    var rc=getTableSelectedRow("T10");
                    selectTableRow("T10",rc+1);
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
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGridDetailDocno->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGridDetailDocno->sortas;  ?>">
<!--<input type="hidden" <?php genInputHiddenDFHtml("doctype") ?> >-->
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Fees and Charges Category List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td>
    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr><td>
            <div id = "Documents" class="doc" title="Documents" style="display:none">
            <table class="tableFreeForm" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td ><label <?php genCaptionHtml($schema["u_filter"],"") ?>>Filter:</label> &nbsp;<input type="text" size="40" <?php genInputTextHtml($schema["u_filter"]) ?> /> &nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
                  <tr class="fillerRow10px"><td></td></tr>
                <tr><td><?php $objGridDetailDocno->draw() ?></td></tr>
                 <tr class="fillerRow10px"><td></td></tr>
                <tr>
                 <td align="right"><a id="Next" class="button" href="" onClick="setNextGPSLGUPurchasing();return false;" >Choose</a>&nbsp;<a id="Close" class="button" href="" onClick="closepopupframeGPSLGUPurchasing();return false;" >Cancel</a></td> 
                </tr>
            </table>
        </div>
        <div id = "Items" class="doc" title="Items" style="display:none">
            <table class="tableFreeForm" cellpadding="0" cellspacing="0" border="0">
                <tr>
                <tr><td><?php $objGridDetail->draw() ?></td></tr>
                 <tr class="fillerRow10px"><td></td></tr>
                <tr>
                 <td align="right">&nbsp;<a id="Prev" class="button" href="" onClick="setPrevGPSLGUPurchasing();return false;" >< Back</a>&nbsp;<a id="OK" class="button" href="" onClick="getCFAccessGPSLGUPurchasing();return false;" >Finish</a>&nbsp;<a id="Close" class="button" href="" onClick="closepopupframeGPSLGUPurchasing();return false;" >Cancel</a></td> 
                </tr>
            </table>
        </div>
    </td></tr></table>
</td></tr>	

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

