page.events.add.submit('onPageSubmitGPSPPSB');
page.elements.events.add.validate('onElementValidateGPSPPSB');
page.elements.events.add.click('onElementClickGPSPPSB');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPPSB');
page.tables.events.add.select('onTableSelectRowGPSPPSB');			

function onPageSubmitGPSPPSB(action) {
var div1 = document.getElementById('items');
var div2 = document.getElementById('Prev');
var div3 = document.getElementById('OK');
var div4 = document.getElementById('doc');
var div5 = document.getElementById('Next');	


	if (action=="cf") {
		if (isInputEmpty("bpcode")) return false;
		if (isInputEmpty("bpname")) return false;	
		if(getTableRowCount("T1") == 0) {
			clearTable("T10",true);
			clearTable("T20",true);
			div1.style.display='none';
			div2.style.visibility='hidden';
			div3.style.visibility='hidden';
                        div4.style.display='block';
                        div5.style.visibility='visible';
			getCFDocnoAccessGPSPPSB();
			showPopupFrame("popupFrameCopyFrom",true);
			return false;
		} else {
			if (window.confirm("All Record wil be Deleted. Continue?")==false)	return false;
			clearTable("T20",true);
			getCFDocnoAccessGPSPPSB();
			showPopupFrame("popupFrameCopyFrom",true);
		}
		return false;
	} else if(action=="cf_access") {
		var rc =  getTableRowCount("T10"),counterror = 0;
			for (i = 1; i <= rc; i++) {
				if(getTableInput("T10","rowstat",i) != "X") {
					if(getTableInput("T10","checked",i)=="Y") {
						if(getTableInputNumeric("T10","quantity",i) == 0){
							page.statusbar.showError("No Quantity");	
							return false;
						}
						if(getTableInputNumeric("T10","unitprice",i) == 0){
							page.statusbar.showError("No Unit Price");	
							return false;
						}
						if(getTableInput("T10","whse",i) == ""){
							page.statusbar.showError("No WareHouse");	
							return false;
						}
						
					}
				}
			}
		getCFAccessGPSPPSB();
		return false;
	}
	return true;
}

function onElementValidateGPSPPSB(element,column,table,row) {
switch (table) {
		case "T10":
			switch(column) {
				case "quantity":
					if(getTableInputNumeric(table,"openquantity",row) < getTableInputNumeric(table,"quantity",row)) {
						setTableInput(table,"quantity",formatNumber(0.00,"quantity"),row);
					}
					var total = getTableInputNumeric(table,"unitprice",row)*getTableInputNumeric(table,"quantity",row);
					setTableInput(table,"linetotal",formatNumber(total,"amount"),row);
					break;
				case "unitprice":
					var total = getTableInputNumeric(table,"unitprice",row)*getTableInputNumeric(table,"quantity",row);
					setTableInput(table,"linetotal",formatNumber(total,"amount"),row);
					break;
			}
			break;
		default:
			break;
	}
	return true;
}

function onElementClickGPSPPSB(element,column,table,row) {
	switch(table) {
		case "T10":
			switch (column) {
				case "checked":
					if (row==0) {
						if (isTableInputChecked(table,column)) { 
							checkedTableInput(table,column,-1);
							//computeTotalFees();
						} else {
							uncheckedTableInput(table,column,-1); 
							//computeTotalFees();
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
							//computeTotalFees();
						} else {
							uncheckedTableInput(table,column,-1); 
							//computeTotalFees();
						}
					}	
					break;
			}
			break;
		default:
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSPPSB(Id,params) {
switch (Id) {
		default:
			if (Id.substring(0,11)=="df_whseT10r") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT warehouse,warehousename FROM warehouses")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Ware House`Ware House Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`70")); 					
			}
			break;
			}
	return params;
}

function onTableSelectRowGPSPPSB(table,row) {
var params = new Array();
	switch (table) {
		case "T10":
				params["focus"] = false;
				if (elementFocused.substring(0,13)=="df_quantityT1") {
					focusTableInput(table,"u_1stcw",row);
				} else if (elementFocused.substring(0,14)=="df_unitpriceT1") {
					focusTableInput(table,"u_1stex",row);
				} else if (elementFocused.substring(0,10)=="df_whseT1") {
					focusTableInput(table,"u_1stgr",row);
				}
			break;
	}
	return params;
}

function setInsertItemsGPSPPSB() {
	var result, data = new Array();
	var rc =  getTableRowCount("T20");
	showAjaxProcess();
	clearTable("T1",true);
	clearTable("T10",true);
	for (i = 1; i <= rc; i++) {
		if(getTableInput("T20","rowstat",i) != "X") {
			if(getTableInput("T20","checked",i)=="Y") {
			result = page.executeFormattedQuery("SELECT a.branch,b.u_itemcode,b.u_itemdesc,b.u_openquantity,a.docno,b.lineid,b.docid,'PPSB_PR'as objectcode,a.u_remarks FROM u_ppsbpurchaserequests a INNER JOIN u_ppsbpurchaserequestitems b ON b.company = a.company AND b.branch = a.branch AND b.docid = a.docid WHERE a.company='" + getGlobal("company") + "' AND b.u_openquantity != 0 AND a.docno = '"+getTableInput("T20","docno",i)+"'");	 			
			if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (iii = 0; iii < result.childNodes.length; iii++) {
							//data["chkno"] = 0;
							data["branch"] = result.childNodes.item(iii).getAttribute("branch");
							data["itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
							data["itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
							data["quantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_openquantity"),"quantity");
							data["unitprice"] = formatNumber(0.00,"amount");
							data["linetotal"] = formatNumber(0.00,"amount");
							data["whse"] = "";
							data["openquantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_openquantity"),"quantity");
							data["docid"] = result.childNodes.item(iii).getAttribute("docid");
							data["lineid"] = result.childNodes.item(iii).getAttribute("lineid");
							data["docno"] = result.childNodes.item(iii).getAttribute("docno");
							data["objcode"] = result.childNodes.item(iii).getAttribute("objectcode");
							data["remarks"] = result.childNodes.item(iii).getAttribute("u_remarks");
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

function getCFAccessGPSPPSB() {
	var result, data = new Array();
	var rc =  getTableRowCount("T10"),count = 0;

	for (i = 1; i <= rc; i++) {
		if(getTableInput("T10","checked",i)=="Y") {
			if(getTableInput("T10","rowstat",i) != "X") {
				count++;
			}
		}
	}
	
	if (getTableRowCount("T10")==0) {
		page.statusbar.showError("No Record List");	
		return false;
	} else {
		if (count==0) {
			page.statusbar.showError("Select Items");	
			return false;
		} else {
			hidePopupFrame("popupFrameCopyFrom",true);
			showAjaxProcess();
			for (var i = 1; i <= getTableRowCount("T10"); i++) {
				if(getTableInput("T10","rowstat",i) != "X") {
					if(getTableInput("T10","checked",i)=="Y") {
						data["itemcode"] = getTableInput("T10","itemcode",i);
						data["itemdesc"] = getTableInput("T10","itemdesc",i);
						data["quantity"] = getTableInputNumeric("T10","quantity",i);
						data["openquantity"] = getTableInputNumeric("T10","quantity",i);
						data["unitprice"] = getTableInputNumeric("T10","unitprice",i);
						data["price"] = getTableInputNumeric("T10","unitprice",i);
						data["linetotal"] = getTableInputNumeric("T10","linetotal",i);
						data["whscode"] = getTableInput("T10","whse",i);
						data["basetypenm"] = getTableInput("T10","objcode",i);
						data["basedocno"] = getTableInput("T10","docno",i);
						data["basedocid"] = getTableInput("T10","docid",i);
						data["baselineid"] = getTableInput("T10","lineid",i);
						insertTableRowFromArray("T1",data);
					}
				}
			}
		}
	}
	hideAjaxProcess();
	return true;
}

function getCFDocnoAccessGPSPPSB() {
	var result, data = new Array();
	showAjaxProcess();
	clearTable("T20",true);
	result = page.executeFormattedQuery("SELECT a.branch,a.docno,a.u_docdate,CONCAT('Base On: ',a.docno) as remarks FROM u_ppsbpurchaserequests a INNER JOIN u_ppsbpurchaserequestitems b ON b.company = a.company AND b.branch = a.branch AND b.docid = a.docid WHERE a.company='" + getGlobal("company") + "' AND b.u_openquantity != 0 GROUP BY a.branch,a.docno");	 
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (iii = 0; iii < result.childNodes.length; iii++) {
						//data["chkno"] = 1;
						data["branch"] = result.childNodes.item(iii).getAttribute("branch");
						data["docno"] = result.childNodes.item(iii).getAttribute("docno");
						data["docdate"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("u_docdate"));
						data["remarks"] = result.childNodes.item(iii).getAttribute("remarks");
						insertTableRowFromArray("T20",data);
					}
				}
			} else {
				hideAjaxProcess();
				page.statusbar.showError("Error retrieving GRPO. Try Again, if problem persists, check the connection.");	
				return false;
			}
	hideAjaxProcess();
	return true;
}

function setNextGPSPPSB() {
	var div1 = document.getElementById('items');
	var div2 = document.getElementById('doc');
	var div3 = document.getElementById('Next');
	var div4 = document.getElementById('Prev');
	var div5 = document.getElementById('OK');
	var rc =  getTableRowCount("T20"),count = 0;

	for (i = 1; i <= rc; i++) {
		if(getTableInput("T20","checked",i)=="Y") {
			if(getTableInput("T20","rowstat",i) != "X") {
				count++;
			}
		}
	}
	
	if (getTableRowCount("T20")==0) {
		page.statusbar.showError("No Record List");	
		return false;
	} else {
		if (count==0) {
			page.statusbar.showError("Select Documents");	
			return false;
		} else {
			div1.style.display='block';
			div2.style.display='none';
			div3.style.visibility='hidden';
			div4.style.visibility='visible';
			div5.style.visibility='visible';
			setInsertItemsGPSPPSB();
		}
	}

}

function setPrevGPSPPSB() {
	var div1 = document.getElementById('items');
	var div2 = document.getElementById('doc');
	var div3 = document.getElementById('Next');
	var div4 = document.getElementById('Prev');
	var div5 = document.getElementById('OK');
	
	div1.style.display='none';
	div2.style.display='block';
	div3.style.visibility='visible';
	div4.style.visibility='hidden';
	div5.style.visibility='hidden';
	
}

function closepopupframe(){
        var div1 = document.getElementById('items');
	var div2 = document.getElementById('doc');
	var div3 = document.getElementById('Next');
	var div4 = document.getElementById('Prev');
	var div5 = document.getElementById('OK');
	
	div1.style.display='none';
	div2.style.display='none';
	div3.style.visibility='hidden';
	div4.style.visibility='hidden';
	div5.style.visibility='hidden';
        hidePopupFrame("popupFrameCopyFrom",true);
}
