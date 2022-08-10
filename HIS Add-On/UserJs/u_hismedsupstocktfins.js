// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_todepartment")) return false;
		if (isInputEmpty("u_fromdepartment")) return false;
		if (isInputEmpty("u_tfindate")) return false;
		if (isInputEmpty("u_tfno")) return false;
		//if (isInputNegative("u_totalamount")) return false;
		
		if (getInput("u_todepartment").substr(0,3)=="BR-") {
			if (getInput("u_intransit")!="1") {
				page.statusbar.showError("You can only do In-Transit on a Branch.");
				return false;
			}
		}

		if (getTableRowCount("T1",true)==0)	{
			page.statusbar.showError("An item is required.");
			return false;
		}
		
		if (isTableInput("T1","u_itemcode")) {
			if (getTableInput("T1","u_itemcode")!="") {
				page.statusbar.showError("An item is being added/edited.");
				return false;
			}
		}
		
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
	}
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
}

function onElementValidateGPSHIS(element,column,table,row) {
	var data = new Array();
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
					if (getTableInput(table,"u_itemcode")!="") {
						result = page.executeFormattedQuery("select code, name, u_uom from u_hisitems where code='"+getTableInput(table,"u_itemcode")+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInputQuantity(table,"u_quantity",1);
								
								result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getGlobal("branch") + ";PRICELIST:{BS};GETVAT:PURCHASE");
								setTableInput(table,"u_vatcode",result.getAttribute("taxcode"));
								setTableInput(table,"u_vatrate",result.getAttribute("taxrate"));
								setTableInput(table,"u_unitprice",result.getAttribute("unitprice"));
								setTableInput(table,"u_price",result.getAttribute("price"));
								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
								setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_linetotal"),getTableInputNumeric(table,"u_vatrate")));
					
							} else {
								setTableInput(table,"u_itemdesc","");
								page.statusbar.showError("Invalid Item Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemdesc","");
							page.statusbar.showError("Error retrieving Item Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_itemdesc":
					if (getTableInput(table,"u_itemdesc")!="") {
						result = page.executeFormattedQuery("select code, name, u_uom from u_hisitems where name='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInputQuantity(table,"u_quantity",1);

								result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getGlobal("branch") + ";PRICELIST:{BS};GETVAT:SALES");
								setTableInput(table,"u_vatcode",result.getAttribute("taxcode"));
								setTableInput(table,"u_vatrate",result.getAttribute("taxrate"));
								setTableInput(table,"u_unitprice",result.getAttribute("unitprice"));
								setTableInput(table,"u_price",result.getAttribute("price"));
								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
								setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_linetotal"),getTableInputNumeric(table,"u_vatrate")));
				
							} else {
								setTableInput(table,"u_itemcode","");
								page.statusbar.showError("Invalid Item Description.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							page.statusbar.showError("Error retrieving Item Description. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_unitprice":
					setTableInputPrice(table,"u_price",getTableInputNumeric(table,"u_unitprice"));
					setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
					setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_linetotal"),getTableInputNumeric(table,"u_vatrate")));					
					break;
				case "u_quantity":
				case "u_price":
					computePatientLineTotalGPSHIS(table);
					break;
			}
			break;
		default:
			switch(column) {
				case "u_tfno":
					clearTable("T1",true);
					if (getInput("u_tfno")!="") {
						result = page.executeFormattedQuery("select a.u_fromdepartment, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_unitprice, b.u_linetotal from u_hismedsupstocktfs a, u_hismedsupstocktfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_tfno")+"' and a.u_todepartment='"+getInput("u_todepartment")+"' and a.u_intransit=1 and a.docstatus = 'O' and b.u_quantity>0");	 						
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								for (iii = 0; iii < result.childNodes.length; iii++) {
									if (iii==0) {
										setInput("u_fromdepartment",result.childNodes.item(iii).getAttribute("u_fromdepartment"));
									}
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
									data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
									data["u_price"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
									data["u_linetotal"] = formatNumericAmount(utils.multiply(result.childNodes.item(iii).getAttribute("u_quantity"),result.childNodes.item(iii).getAttribute("u_unitprice")));
									insertTableRowFromArray("T1",data);
								}
							} else {
								page.statusbar.showError("Invalid Transfer No.");	
								return false;
							}
							computeTotalGPSHIS();
						} else {
							page.statusbar.showError("Error retrieving Transfer No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
					}
					break;

			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	var result, data = new Array();
	switch (table) {
		case "T1":
			break;
		default:
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1 and code not in ("+getTableInputGroupConCat("T1","u_itemcode")+")")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=code";
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 		
			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1 and name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=name";
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 		
			break;
		case "df_u_tfno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_tfdate, u_fromdepartment from u_hismedsupstocktfs where u_intransit=1 and u_todepartment='"+getInput("u_todepartment")+"' and docstatus ='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`From Section`Remarks")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("12`12`30`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
			//if (isTableInputNegative(table,"u_unitprice")) return false;
			//if (isTableInputNegative(table,"u_price")) return false;
			//if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
		case "T2":
			if (getVar("formSubmitAction")=="a") {
				page.statusbar.showWarning("Please add the document before attaching files.");	
				return false;
			}
			uploadAttachment();
			return false;
			break;	
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
			//if (isTableInputNegative(table,"u_unitprice")) return false;
			//if (isTableInputNegative(table,"u_price")) return false;
			//if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	return params;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), vatamount=0, totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			vatamount += getTableInputNumeric("T1","u_vatamount",i);
			totalamount += getTableInputNumeric("T1","u_linetotal",i);
		}
	}
	if (isInput("u_vatamount")) setInputAmount("u_vatamount",vatamount);	
	if (isInput("u_amountbefvat")) setInputAmount("u_amountbefvat",totalamount - vatamount);	
	if (isInput("u_totalamount")) setInputAmount("u_totalamount",totalamount);	
}


