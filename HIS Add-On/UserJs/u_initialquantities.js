// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_date")) return false;
		//if (isInputEmpty("u_terminal")) return false;
		
		/*if (getInput("docstatus")=="C") {
			
			var rc =  getTableRowCount("T1");
			for (i = 1; i <= rc; i++) {
				if (isTableRowDeleted("T1",i)==false) {
					if (getTableInputNumeric("T1","u_varqty",i)!=0 && getTableInput("T1","u_glacctno",i)=="") {
						selectTab("tab1",1);
						selectTableRow("T1",i);
						focusTableInput("T1","u_glacctno",i);
						page.statusbar.showWarning("G/L Acct No. is required.");
						return false;
					}
				}
			}
			var rc =  getTableRowCount("T2");
			for (i = 1; i <= rc; i++) {
				if (isTableRowDeleted("T2",i)==false) {
					if (getTableInputNumeric("T2","u_varqty",i)!=0 && getTableInput("T2","u_glacctno",i)=="") {
						selectTab("tab1",0);
						selectTableRow("T2",i);
						focusTableInput("T2","u_glacctno",i);
						page.statusbar.showWarning("G/L Acct No. is required.");
						return false;
					}
				}
			}
		}*/
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
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
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") result = page.executeFormattedQuery("select a.itemcode, a.itemdesc, a.itemclass, a.uom from items a where a.isinventory=1 and a.manageby='0' and a.itemcode = '"+getTableInput(table,column)+"'");	
						else result = page.executeFormattedQuery("select a.itemcode, a.itemdesc, a.itemclass, a.uom from items a where a.isinventory=1 and a.manageby='0' and a.itemdesc like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("itemcode"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("itemdesc"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("uom"));
								setTableInput(table,"u_itemclass",result.childNodes.item(0).getAttribute("itemclass"));
							} else {
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_itemdesc","");
								setTableInput(table,"u_itemclass","");
								setTableInput(table,"u_uom","");
								page.statusbar.showError("Invalid Item");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setTableInput(table,"u_itemclass","");
							setTableInput(table,"u_uom","");
							page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
						setTableInput(table,"u_itemclass","");
						setTableInput(table,"u_uom","");
					}
					break;
				case "u_actqtypu":
					setTableInputQuantity(table,"u_varqtypu",getTableInputNumeric(table,"u_actqtypu",row)-getTableInputNumeric(table,"u_sysqtypu",row),row);
					break;
				case "u_actqtyiu":
					setTableInputQuantity(table,"u_varqtyiu",getTableInputNumeric(table,"u_actqtyiu",row)-getTableInputNumeric(table,"u_sysqtyiu",row),row);
					break;
			}
			break;
		default:
			switch(column) {
				case "u_date":
					u_getStocksGPSHIS();
					break;
				case "u_suppno":
					if (getInput(column)!="") {
						if (column=="u_suppno") result = page.executeFormattedQuery("select a.suppno, a.suppname from suppliers a where a.suppno = '"+getInput(column)+"'");	
						else result = page.executeFormattedQuery("select a.suppno, a.suppname from suppliers a where a.suppname like '"+getInput(column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								
								var result2 = page.executeFormattedSearch("select docno from u_initialquantities where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_suppno='"+result.childNodes.item(0).getAttribute("suppno")+"' and docstatus='O'");
								if (result2!="") {
									alert('An initial quantity document with open status exists with this supplier. System will redirect you to this document');
									setKey("keys",result2);
									formEdit();
									return true;
								}
								
								setInput("u_suppno",result.childNodes.item(0).getAttribute("suppno"));
								setInput("u_suppname",result.childNodes.item(0).getAttribute("suppname"));
								u_getStocksGPSHIS();
							} else {
								setInput("u_suppno","");
								setInput("u_suppname","");
								u_getStocksGPSHIS();
								page.statusbar.showError("Invalid Supplier");	
								return false;
							}
						} else {
							setInput("u_suppno","");
							setInput("u_suppname","");
							u_getStocksGPSHIS();
							page.statusbar.showError("Error retrieving supplier record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_suppno","");
						setInput("u_suppname","");
						u_getStocksGPSHIS();
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
	switch (table) {
		case "T1":
			break;
		default:
			switch(column) {
				case "u_itemgroup":
				case "u_itemclass":
				case "u_department":
					u_getStocksGPSHIS();
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			break;
		default:
			switch(column) {
				case "u_ob":
				case "u_showall":
					u_getStocksGPSHIS();
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_suppno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno, suppname from suppliers")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Supplier`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select itemcode, itemdesc from items where isinventory=1 and manageby='0'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select itemdesc, itemcode from items where isinventory=1 and manageby='0'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		default:
			if (Id.substr(0,15)=="df_u_glacctnoT2") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where postable=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Acct No.`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 
			}
			break;
	}		
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch(table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputEmpty(table,"u_batch")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch(table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputEmpty(table,"u_batch")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	switch(table) {
		case "T1":
			var params = new Array();
			if (elementFocused.substring(0,15)=="df_u_actqtypuT1") {
				focusTableInput(table,"u_actqtypu",row);
			} else if (elementFocused.substring(0,15)=="df_u_actqtyiuT1") {
				focusTableInput(table,"u_actqtyiu",row);
			} else if (elementFocused.substring(0,15)=="df_u_itemcostT1") {
				focusTableInput(table,"u_itemcost",row);
			}
			params["focus"] = false;
			break;
	}
	return params;
}


function u_getStocksGPSHIS() {
	var data = Array(),data2 = Array(), data3 = Array(), stocks = Array();
	showAjaxProcess();
	clearTable("T1",true);
	var filterExp1 = "";
	var filterExp2 = "";
	var obExp = "";
	if (getInput("u_itemgroup")!="") {
		filterExp1 += " and b.itemgroup='"+getInput("u_itemgroup")+"'";
	}
	if (getInput("u_itemclass")!="") {
		filterExp1 += " and b.itemclass='"+getInput("u_itemclass")+"'";
	}
	//filterExp2 += " and a.warehouse = '"+getInput("u_department")+"'";
	if (getInput("u_showall")=="1") {
		obExp = " union all select b.itemcode, b.itemdesc, b.taxcodepu, b.numperuompu, b.uompu, b.uom,  0 as qty from u_hisitems a inner join items b on b.itemcode=a.code and b.isvalid=1 and b.isinventory=1 where a.u_isstock=1 " + filterExp1;
	} else if (getInput("u_ob")=="1") {
		obExp = " union all select b.itemcode, b.itemdesc, b.taxcodepu, b.numperuompu, b.uompu, b.uom,  0 as qty from u_hisitems a inner join items b on b.itemcode=a.code and b.isvalid=1 and b.isinventory=1 where a.u_profitcenter in ('','"+getInput("u_department")+"') " + filterExp1;
	}
	if (getInput("u_date")!="" && getInput("u_department")!="") {
		//alert("select x.itemcode, x.itemdesc, x.numperuompu, x.uompu, x.taxcodepu, x.uom, sum(x.qty) as qty, ifnull(if(x.taxcodepu='VATINP',xb.price/1.12,xb.price),0) as cost from (select b.itemcode, b.itemdesc, b.taxcodepu, b.numperuompu, b.uompu, b.uom,  sum(a.qty) as qty from stockcard a inner join items b on b.itemcode=a.itemcode " + filterExp1 + " where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"'  and a.warehouse = '"+getInput("u_department")+"' and a.refdate<='"+formatDateToDB(getInput("u_date"))+"' group by b.itemcode " +obExp+ ") as x left join itempricelists xb on xb.itemcode=x.itemcode and xb.pricelist='12' group by x.itemcode order by x.itemdesc");
		var result = page.executeFormattedQuery("select x.itemcode, x.itemdesc, x.numperuompu, x.uompu, x.taxcodepu, x.uom, sum(x.qty) as qty, ifnull(if(x.taxcodepu='VATINP',xb.price/1.12,xb.price),0) as cost from (select b.itemcode, b.itemdesc, b.taxcodepu, b.numperuompu, b.uompu, b.uom,  sum(a.qty) as qty from stockcard a inner join items b on b.itemcode=a.itemcode " + filterExp1 + " where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"'  and a.warehouse = '"+getInput("u_department")+"' and a.refdate<='"+formatDateToDB(getInput("u_date"))+"' group by b.itemcode " +obExp+ ") as x left join itempricelists xb on xb.itemcode=x.itemcode and xb.pricelist='12' group by x.itemcode order by x.itemdesc");
	//																																																																					   select x.itemcode, x.itemdesc, x.numperuompu, x.uompu, x.barcodepu, x.taxcodepu, x.uom, x.qtywh, x.qtysa, ifnull(if(x.taxcodepu='VATINP',xb.price/1.12,xb.price),0) as cost from (select b.itemcode, b.itemdesc, b.taxcodepu, b.numperuompu, b.uompu, b.barcodepu, b.uom, truncate(sum(if(a.warehouse='WH',a.qty,0))/b.numperuompu,2) as qtywh, truncate(sum(if(a.warehouse='SA',a.qty,0)),2) as qtysa from items b left join stockcard a on a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' "+filterExp2+" and b.itemcode=a.itemcode "+filterExp1+" and a.refdate<='"+formatDateToDB(getInput("u_date"))+"' where b.isvalid=1 group by b.itemcode) as x left join itembarcodepricelists xb on xb.barcode=x.itemcode and xb.pricelist='12' order by x.itemdesc");	
	//and u_date<'2016-05-01'  
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (var iii=0; iii<result.childNodes.length; iii++) {
					data["u_itemcode"] = result.childNodes.item(iii).getAttribute("itemcode");
					data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("itemdesc");
					//data["u_itemclass"] = result.childNodes.item(iii).getAttribute("itemclass");
					data["u_sysuompu"] = result.childNodes.item(iii).getAttribute("uompu");
					data["u_sysuomiu"] = result.childNodes.item(iii).getAttribute("uom");
					data["u_actuompu"] = result.childNodes.item(iii).getAttribute("uompu");
					data["u_actuomiu"] = result.childNodes.item(iii).getAttribute("uom");
					data["u_varuompu"] = result.childNodes.item(iii).getAttribute("uompu");
					data["u_varuomiu"] = result.childNodes.item(iii).getAttribute("uom");
					data["u_itemcost"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("cost"));
					data["u_qtyperuom"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("numperuompu"));
					data["u_sysqtypu"] = formatNumericQuantity(Math.floor(parseFloat(result.childNodes.item(iii).getAttribute("qty"))/parseFloat(result.childNodes.item(iii).getAttribute("numperuompu"))));
					data["u_sysqtyiu"] = formatNumericQuantity(parseFloat(result.childNodes.item(iii).getAttribute("qty")) % parseFloat(result.childNodes.item(iii).getAttribute("numperuompu")));
					data["u_actqtypu"] = formatNumericQuantity(Math.floor(parseFloat(result.childNodes.item(iii).getAttribute("qty"))/parseFloat(result.childNodes.item(iii).getAttribute("numperuompu"))));
					data["u_actqtyiu"] = formatNumericQuantity(parseFloat(result.childNodes.item(iii).getAttribute("qty")) % parseFloat(result.childNodes.item(iii).getAttribute("numperuompu")));
					data["u_varqtypu"] = formatNumericQuantity(0);
					data["u_varqtyiu"] = formatNumericQuantity(0);
					//data["u_varqtypu"] = formatNumericQuantity(Math.floor(parseFloat(result.childNodes.item(iii).getAttribute("qty"))/parseFloat(result.childNodes.item(iii).getAttribute("numperuompu")))*-1);
					//data["u_varqtyiu"] = formatNumericQuantity(parseFloat(result.childNodes.item(iii).getAttribute("qty")) % parseFloat(result.childNodes.item(iii).getAttribute("numperuompu"))*-1);
					insertTableRowFromArray("T1",data);
					if (data["u_sysuompu"]==data["u_sysuomiu"]) {
						disableTableInput("T1","u_actqtyiu",getTableRowCount("T1"));
					}
				}
			}
			if (getTableRowCount("T1",true)) selectTab("tab1",1);
			else if (getTableRowCount("T2",true)) selectTab("tab1",0);
														   
		} else {
			hideAjaxProcess();
			page.statusbar.showError("Error retrieving stockcard records. Try Again, if problem persists, check the connection.");	
			return false;
		}
	}
	hideAjaxProcess();

}

function postGPSHIS() {
	setInput("docstatus","C");
	formSubmit();
}

