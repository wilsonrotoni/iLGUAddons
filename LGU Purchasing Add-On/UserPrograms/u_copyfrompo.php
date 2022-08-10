<?php
    $progid = "u_copyfrompo";
        
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
     
    $page->objectcode = "u_copyfrompo";
    $page->paging->formid = "./UDP.php?&objectcode=u_copyfrompo";
    $page->formid = "./UDO.php?&objectcode=u_lgureceipts";
    $page->objectname = "Copy From Purchase Order";
    
    $schema["u_filter"] = createSchemaUpper("u_filter");
    $schema["u_warehouse"] = createSchemaUpper("u_warehouse");
    $schema["u_itemtype"] = createSchemaUpper("u_itemtype");
    $schema["u_itemcode"] = createSchemaUpper("u_itemcode");
    $schema["doctype"] = createSchemaUpper("doctype");
    
    $objGridDetailDocno = new grid("T20",$httpVars);
    
    $objGridDetailDocno->addcolumn("branch");
    $objGridDetailDocno->addcolumn("docno");
    $objGridDetailDocno->addcolumn("u_date");
    $objGridDetailDocno->addcolumn("u_profitcentername");
    $objGridDetailDocno->addcolumn("u_remarks");
    $objGridDetailDocno->addcolumn("u_duedate");

   
    $objGridDetailDocno->columntitle("branch","Branch");
    $objGridDetailDocno->columntitle("docno","Document No.");
    $objGridDetailDocno->columntitle("u_date","Date");
    $objGridDetailDocno->columntitle("u_profitcentername","Profit Center");
    $objGridDetailDocno->columntitle("u_remarks","Remarks");
    $objGridDetailDocno->columntitle("u_duedate","Due Date");

    $objGridDetailDocno->columnwidth("branch",8);
    $objGridDetailDocno->columnwidth("docno",16);
    $objGridDetailDocno->columnwidth("u_date",8);
    $objGridDetailDocno->columnwidth("u_profitcentername",25);
    $objGridDetailDocno->columnwidth("u_remarks",25);
    $objGridDetailDocno->columnwidth("u_duedate",8);
    
    $objGridDetailDocno->columnsortable("docno",true);
    $objGridDetailDocno->columnsortable("u_profitcentername",true);

    $objGridDetailDocno->automanagecolumnwidth = true;
//    $objGridDetailDocno->width = 980;
    $objGridDetailDocno->height = 250;
    $objGridDetailDocno->selectionmode = 2;
    
    $objGridDetail = new grid("T10",$httpVars);
    //$objGridDetail->addcolumn("chkno");
    
    $objGridDetail->addcolumn("docno");
    $objGridDetail->addcolumn("branch");
    $objGridDetail->addcolumn("itemcode");
    $objGridDetail->addcolumn("itemdesc");
    $objGridDetail->addcolumn("itemsubgroup");
    $objGridDetail->addcolumn("whse");
    $objGridDetail->addcolumn("itemtype");
    $objGridDetail->addcolumn("unitissue");
    $objGridDetail->addcolumn("quantity");
    $objGridDetail->addcolumn("glacctno");
    $objGridDetail->addcolumn("openquantity");
    $objGridDetail->addcolumn("unitprice");
    $objGridDetail->addcolumn("linetotal");
    $objGridDetail->addcolumn("lineid");
    $objGridDetail->addcolumn("docid");
//    $objGridDetail->addcolumn("docno");
    $objGridDetail->addcolumn("objcode");
    $objGridDetail->addcolumn("remarks");

    //$objGridDetail->columntitle("chkno","*");
    $objGridDetail->columntitle("docno","Document No");
    $objGridDetail->columntitle("branch","Branch");
    $objGridDetail->columntitle("itemcode","Item Code");
    $objGridDetail->columntitle("itemdesc","Item Description");
    $objGridDetail->columntitle("whse","Ware House");
    $objGridDetail->columntitle("itemtype","Item Type");
    $objGridDetail->columntitle("quantity","Quantity");
    $objGridDetail->columntitle("openquantity","Rem Qty");
    $objGridDetail->columntitle("unitissue","Unit Issue");
    $objGridDetail->columntitle("unitprice","Unit Price");
    $objGridDetail->columntitle("linetotal","Line Total");
    $objGridDetail->columntitle("remarks","Remarks");


    //$objGridDetail->columnwidth("chkno",3);
    $objGridDetail->columnwidth("branch",10);
    $objGridDetail->columnwidth("itemcode",15);
    $objGridDetail->columnwidth("itemdesc",30);
    $objGridDetail->columnwidth("itemtype",10);
    $objGridDetail->columnwidth("quantity",10);
    $objGridDetail->columnwidth("openquantity",12);
    $objGridDetail->columnwidth("unitissue",12);
    $objGridDetail->columnwidth("unitprice",12);
    $objGridDetail->columnwidth("linetotal",12);
    $objGridDetail->columnwidth("whse",12);
    $objGridDetail->columnwidth("lineid",12);
    $objGridDetail->columnwidth("docid",12);
    $objGridDetail->columnwidth("docno",15);
    $objGridDetail->columnwidth("objcode",12);
    $objGridDetail->columnwidth("remarks",50);

    $objGridDetail->columnalignment("quantity","right");
    $objGridDetail->columnalignment("openquantity","right");
    $objGridDetail->columnalignment("unitprice","right");
    $objGridDetail->columnalignment("linetotal","right");

    $objGridDetail->selectionmode = 2;

    $objGridDetail->columnvisibility("lineid",false);
    $objGridDetail->columnvisibility("docid",false);
//    $objGridDetail->columnvisibility("docno",false);
    
    $objGridDetail->columnvisibility("itemsubgroup",false);
    $objGridDetail->columnvisibility("glacctno",false);
    $objGridDetail->columnvisibility("objcode",false);
    $objGridDetail->columnvisibility("remarks",false);
    
    
//    $objGridDetail->columnattributes("unitprice","disabled");

    $objGridDetail->columninput("quantity","type","text");
    $objGridDetail->columninput("whse","type","text");
    $objGridDetail->columninput("itemtype","type","text");
    $objGridDetail->automanagecolumnwidth = false;
    $objGridDetail->dataentry = false;
//    $objGridDetail->width = 760;
    $objGridDetail->height = 250;
    
    $schema["u_itemtype"]["cfl"] = "OpenCFLfs()";
    $schema["u_warehouse"]["cfl"] = "OpenCFLfs()";
    if ($lookupSortBy == "") {
            $lookupSortBy = "docno";
    } else  $lookupSortBy = strtolower($lookupSortBy);
    $objGridDetailDocno->setsort($lookupSortBy,$lookupSortAs);
    
    $filterExp = "";
    if ($httpVars['df_u_filter']!= "") {
       $filterExp = genSQLFilterString($lookupSortBy,$filterExp,$httpVars['df_u_filter'],null,null,true);
       if ($filterExp !="") $filterExp = " AND " . $filterExp; 
    }
    
    $objrs = new recordset(null,$objConnection);
    if ($page->getvarstring("df_doctype")=="I") {
         $objrs->queryopenext("SELECT a.branch,a.docno,a.u_date,CONCAT('Base On: ',a.docno) as remarks,a.u_duedate,a.u_profitcentername FROM u_lgupurchaseorder a INNER JOIN u_lgupurchaseorderitems b ON b.company = a.company AND b.branch = a.branch AND b.docid = a.docid WHERE a.company='$company' AND A.DOCSTATUS  = 'O'  $filterExp  GROUP BY a.branch,a.docno", $objGridDetailDocno->sortby,"",$objGridDetailDocno->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
    } else {
         $objrs->queryopenext("SELECT a.branch,a.docno,a.u_date,CONCAT('Base On: ',a.docno) as remarks,a.u_duedate,a.u_profitcentername FROM u_lgupurchaseorder a INNER JOIN u_lgupurchaseorderservice b ON b.company = a.company AND b.branch = a.branch AND b.docid = a.docid WHERE a.company='$company' AND A.DOCSTATUS  = 'O'  $filterExp  GROUP BY a.branch,a.docno", $objGridDetailDocno->sortby,"",$objGridDetailDocno->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
    }
    
        $page->paging_recordcount($objrs->recordcount());
        while ($objrs->queryfetchrow("NAME")) {
            $objGridDetailDocno->addrow();
            $objGridDetailDocno->setitem(null,"branch",$objrs->fields["branch"]);
            $objGridDetailDocno->setitem(null,"docno",$objrs->fields["docno"]);
            $objGridDetailDocno->setitem(null,"u_profitcentername",$objrs->fields["u_profitcentername"]);
            $objGridDetailDocno->setitem(null,"u_date",formatDateToHttp($objrs->fields["u_date"]));
            $objGridDetailDocno->setitem(null,"u_duedate",formatDateToHttp($objrs->fields["u_duedate"]));
            $objGridDetailDocno->setitem(null,"u_remarks",$objrs->fields["remarks"]);
            $objGridDetailDocno->setkey(null,$objrs->fields["DOCNO"]);
//            if (!$page->paging_fetch()) break;
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
                    case "itemtype":
                        if(getTableInput(table,"itemtype",row) == "C" || getTableInput(table,"itemtype",row) == "c"){
                            setTableInput(table,"itemtype","Consumption",row);
                        }else if(getTableInput(table,"itemtype",row) == "F" || getTableInput(table,"itemtype",row) == "f"){
                            setTableInput(table,"itemtype","Fixed Asset",row);
                        }else if(getTableInput(table,"itemtype",row) == "S" || getTableInput(table,"itemtype",row) == "s"){
                            setTableInput(table,"itemtype","Stock",row);
                        }else{
                            page.statusbar.showError("Invalid Item Type.");	
                            return false;
                        }
                    break;
                    case "quantity":
                    case "unitprice":
                        if(getTableInputNumeric(table,"openquantity",row) < getTableInputNumeric(table,"quantity",row)) {
                                setTableInput(table,"quantity",formatNumber(0.00,"quantity"),row);
                        }
                        var total = getTableInputNumeric(table,"unitprice",row)*getTableInputNumeric(table,"quantity",row);
                        setTableInput(table,"linetotal",formatNumber(total,"amount"),row);
                        break
                    case "whse":
                            result = page.executeFormattedQuery("SELECT warehouse FROM warehouses WHERE warehouse = '"+getTableInput(table,column,row)+"'");	 
                            if (result.getAttribute("result")!= "-1") {
                                    if (parseInt(result.getAttribute("result"))>0) {
                                    } else {
                                            setTableInput(table,"whse","",row);
                                            page.statusbar.showError("Invalid warehouse.");		
                                            return false;
                                    }
                            } else {
                                   setTableInput(table,"whse","",row);
                                    page.statusbar.showError("Error retrieving warehouse. Try Again, if problem persists, check the connection.");	
                                    return false;
                            }
                    break;
                }
            break;
        default:
            switch (column) {
                case "u_itemtype":
                       if(getInput("u_itemtype").toUpperCase() == "C" || getInput("u_itemtype").toUpperCase() == "CONSUMPTION"){
                           setInput("u_itemtype","Consumption");
                       }else if(getInput("u_itemtype").toUpperCase() == "F" || getInput("u_itemtype").toUpperCase() == "FIXED ASSET"){
                           setInput("u_itemtype","Fixed Asset");
                       }else if(getInput("u_itemtype").toUpperCase() == "S" || getInput("u_itemtype").toUpperCase() == "STOCK"){
                           setInput("u_itemtype","Stock");
                       }else{
                           page.statusbar.showError("Invalid Item Type.");	
                           return false;
                       }
                break;
                case "u_warehouse":
                           result = page.executeFormattedQuery("SELECT warehouse FROM warehouses WHERE warehouse = '"+getInput(column)+"'");	 
                           if (result.getAttribute("result")!= "-1") {
                                   if (parseInt(result.getAttribute("result"))>0) {
                                   } else {
                                           setInput("u_warehouse","");
                                           page.statusbar.showError("Invalid warehouse.");		
                                           return false;
                                   }
                           } else {
                                  setInput("u_warehouse","");
                                   page.statusbar.showError("Error retrieving warehouse. Try Again, if problem persists, check the connection.");	
                                   return false;
                           }
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
//                    
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
              case "T10":
                  
                if (elementFocused.substring(0,13)=="df_quantityT1") {
                      focusTableInput(p_tableId,"u_quantity",p_rowIdx);
                } else if (elementFocused.substring(0,14)=="df_unitpriceT1") {
                    focusTableInput(p_tableId,"u_unitprice",p_rowIdx);
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
			result = page.executeFormattedQuery("SELECT c.u_isstock,c.u_isfixedasset,c.u_isconsumption, a.branch,b.u_itemcode,b.u_itemdesc,b.u_itemsubgroup,'' as u_glacctno,b.u_unitissue,b.u_openquantity,a.docno,b.lineid,b.docid,'LGU_Purchase Order'as objectcode,a.u_remarks,b.u_unitcost,b.u_cost,b.u_remarks as u_detailremarks  FROM u_lgupurchaseorder a INNER JOIN u_lgupurchaseorderitems b ON b.company = a.company AND b.branch = a.branch AND b.docid = a.docid  LEFT JOIN U_LGUITEMS c on b.u_itemcode = c.code  WHERE a.company='" + getGlobal("company") + "' AND b.u_openquantity != 0 AND a.docno = '"+getTableInput("T20","docno",i)+"' union all SELECT  '' u_isstock, '' u_isfixedasset,'' u_isconsumption,a.branch,'' as u_itemcode,b.u_itemdesc,'' as u_itemsubgroup,b.u_glacctno,b.u_unitissue,b.u_openquantity,a.docno,b.lineid,b.docid,'LGU_Purchase Order'as objectcode,a.u_remarks,b.u_unitcost,b.u_cost,b.u_remarks as u_detailremarks  FROM u_lgupurchaseorder a INNER JOIN u_lgupurchaseorderservice b ON b.company = a.company AND b.branch = a.branch AND b.docid = a.docid WHERE a.company='" + getGlobal("company") + "' AND b.u_openquantity != 0 AND a.docno = '"+getTableInput("T20","docno",i)+"' ");	 			
			if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (iii = 0; iii < result.childNodes.length; iii++) {
							data["checked"] = "Y";
							data["branch"] = result.childNodes.item(iii).getAttribute("branch");
							data["itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
							data["itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
							data["itemsubgroup"] = result.childNodes.item(iii).getAttribute("u_itemsubgroup");
							data["quantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_openquantity"),"quantity");
                                                        data["remarks"] = result.childNodes.item(iii).getAttribute("u_detailremarks");
							data["unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitcost"));
							data["linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_cost"));
                                                        if(result.childNodes.item(iii).getAttribute("u_isconsumption") == 1) data["itemtype"] = "Consumption";
							if(result.childNodes.item(iii).getAttribute("u_isfixedasset") == 1) data["itemtype"] = "Fixed Asset";
                                                        if(result.childNodes.item(iii).getAttribute("u_isstock") == 1) data["itemtype"] = "Stock";
							data["openquantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_openquantity"),"quantity");
							data["docid"] = result.childNodes.item(iii).getAttribute("docid");
							data["lineid"] = result.childNodes.item(iii).getAttribute("lineid");
							data["unitissue"] = result.childNodes.item(iii).getAttribute("u_unitissue");
							data["glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctno");
                                                        data["docno"] = result.childNodes.item(iii).getAttribute("docno");
							data["objcode"] = result.childNodes.item(iii).getAttribute("objectcode");
							data["whse.cfl"] = "OpenCFLfs()";
							insertTableRowFromArray("T10",data);
						}
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving Subject. Try Again, if problem persists, check the connection.");	
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
            
            if (window.opener.getVar("objectcode")=="U_LGUPURCHASEDELIVERY") {
                    for (i = 1; i <= rc; i++) {
        //                alert(getTableInput("T10","checked",i));
                            if(getTableInput("T10","checked",i)=="Y") {
                                    if(getTableInput("T10","rowstat",i) != "X") {
                                            if(getTableInput("T10","itemtype",i)==""){
                                                page.statusbar.showError("Item type is required.");
                                                focusTableInput("T10","itemtype",i);
                                                return false;
                                            }
                                            if(getTableInput("T10","whse",i)=="" && getTableInput("T10","itemtype",i) == "Stock"){
                                                page.statusbar.showError("Ware house is required.");
                                                focusTableInput("T10","whse",i);
                                                return false;
                                            }
                                            count++;
                                    }
                            }
                    }
            }
            
            for (i = 1; i <= rc; i++) {
                if(getTableInput("T10","checked",i)=="Y") {
                    if(getTableInput("T10","rowstat",i) != "X") {
                            count++;
                    }
                }
            }
            
            if (rc==0) {
                    page.statusbar.showError("No Record found.");	
                    return false;
            } else {
                    if (count==0) {
                            page.statusbar.showError("Please Select Item/s.");	
                            return false;
                    } else {
//                           
							showAjaxProcess();
                            window.opener.clearTable("T1",true);
                            var remarks = "";
                            for (var iii = 1; iii <= getTableRowCount("T20"); iii++) {
                                    if(getTableInput("T20","rowstat",iii) != "X") {
                                            if(getTableInput("T20","checked",iii)==1) {
                                                     result = page.executeFormattedQuery("SELECT u_profitcenter,u_profitcentername,u_bpname,u_bpcode,u_procmode  FROM u_lgupurchaseorder WHERE company='" + getGlobal("company") + "' and docno = '"+getTableInput("T20","docno",iii)+"'");	 			
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                                for (iiii = 0; iiii < result.childNodes.length; iiii++) {
                                                                    window.opener.setInput("u_bpname",result.childNodes.item(iiii).getAttribute("u_bpname"));
                                                                    window.opener.setInput("u_bpcode",result.childNodes.item(iiii).getAttribute("u_bpcode"));
                                                                    window.opener.setInput("u_profitcenter",result.childNodes.item(iiii).getAttribute("u_profitcenter"));
                                                                    window.opener.setInput("u_profitcentername",result.childNodes.item(iiii).getAttribute("u_profitcentername"));
                                                                    window.opener.setInput("u_procmode",result.childNodes.item(iiii).getAttribute("u_procmode"));
                                                            }
                                                        }
                                                    }
                                                    remarks += "Base on Purchase Order " + getTableInput("T20","docno",iii) + ". ";
                                            }
                                    }
                            }
                            for (var i = 1; i <= getTableRowCount("T10"); i++) {
                                    if(getTableInput("T10","rowstat",i) != "X") {
                                            if(getTableInput("T10","checked",i)=="Y") {
                                                    data["u_itemcode"] = getTableInput("T10","itemcode",i);
                                                    data["u_itemdesc"] = getTableInput("T10","itemdesc",i);
                                                    data["u_itemsubgroup"] = getTableInput("T10","itemsubgroup",i);
                                                    data["u_itemtype"] = getTableInput("T10","itemtype",i);
                                                    data["u_quantity"] = getTableInputNumeric("T10","quantity",i);
                                                    data["u_remarks"] = getTableInput("T10","remarks",i);
                                                    data["u_openquantity"] = getTableInputNumeric("T10","quantity",i);
                                                    data["u_unitcost"] = formatNumericAmount(getTableInputNumeric("T10","unitprice",i));
                                                    data["u_cost"] = formatNumericAmount(getTableInputNumeric("T10","linetotal",i));
                                                    data["u_basetypenm"] = getTableInput("T10","objcode",i);
                                                    data["u_unitissue"] = getTableInput("T10","unitissue",i);
                                                    data["u_basedocno"] = getTableInput("T10","docno",i);
                                                    data["u_basedocid"] = getTableInput("T10","docid",i);
                                                    data["u_baselineid"] = getTableInput("T10","lineid",i);
                                                    data["u_itemtype"] = getTableInput("T10","itemtype",i);
                                                    data["u_whscode"] = getTableInput("T10","whse",i);
                                                    if(getInput("doctype")=="I"){
                                                        window.opener.insertTableRowFromArray("T1",data);
                                                    }else if(getInput("doctype")=="S"){
                                                        data["u_glacctno"] = getTableInput("T10","glacctno",i);
                                                        window.opener.insertTableRowFromArray("T2",data);
                                                    }
                                                   
                                            }
                                    }
                            }
                            window.opener.setInput("u_remarks",remarks);
                            window.close();
                    }
            }
            hideAjaxProcess();
            return true;
    }
    function onElementCFLGetParams(element) {
            var params = new Array();
            if ((element.id).substring(0,10) == "df_whseT10") {
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT warehouse,warehousename FROM warehouses")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Ware House`Ware House Name")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`70")); 	
            }
            switch (element.id) {
                    case "df_u_itemtype":
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT 'Stock' union all SELECT 'Fixed Asset' union all SELECT 'Consumption'")); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Type")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("70")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
//				params["params"] += "&cflsortby=b.u_unitcode";
                            break;
                    case "df_u_warehouse":
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT warehouse,warehousename FROM warehouses")); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Warehouse code.`Warehouse Name")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`70")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
//				params["params"] += "&cflsortby=a.u_custno";
                            break;
            }     
            return params;
    }
    
    function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("u_filter");
			inputs.push("doctype");
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
    function ApplytoGridWarehouseItemType() {
        for (var i = 1; i <= getTableRowCount("T10"); i++) {
            if(getTableInput("T10","rowstat",i) != "X") {
                    if(getTableInput("T10","checked",i)=="Y") {
                            setTableInput("T10","whse",getInput("u_warehouse"),i);
                            setTableInput("T10","itemtype",getInput("u_itemtype"),i);
                    }
            }
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
<input type="hidden" <?php genInputHiddenDFHtml("doctype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Purchase Order List&nbsp;</td>
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
                    <td ><label <?php genCaptionHtml($schema["u_warehouse"],"") ?>>Warehouse</label> &nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_warehouse"]) ?> /> &nbsp;<label <?php genCaptionHtml($schema["u_itemtype"],"") ?>>Item Type</label> &nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_itemtype"]) ?> /> &nbsp;<a class="button" href="" onClick="ApplytoGridWarehouseItemType();return false;">Apply</a></td>
                    <tr class="fillerRow10px"><td></td></tr>
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

