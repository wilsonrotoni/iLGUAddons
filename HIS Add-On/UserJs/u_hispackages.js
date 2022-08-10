// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
//page.events.add.cflreturn('onCFLReturnGPSHIS');

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
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("code")) return false;
		if (isInputEmpty("name")) return false;
		
		if (getTableInput("T1","u_itemcode")!="") {
			page.statusbar.showError("A package item is being added/edited.");
			return false;
		}
		//if (!checkPricesGPSHIS()) return false;
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			//params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
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
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") {
							result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_profitcenter, ifnull(d.price,0) as price from u_hisitems a left join  itempricelists d on d.itemcode=a.code and d.pricelist='1' where code='"+getTableInput(table,"u_itemcode")+"' and u_active=1");	
						} else {
							result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_profitcenter, ifnull(d.price,0) as price from u_hisitems a left join itempricelists d on d.itemcode=a.code and d.pricelist='1' where a.name like '%"+getTableInput(table,"u_itemdesc")+"%' and u_active=1");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInput(table,"u_department",result.childNodes.item(0).getAttribute("u_profitcenter"));
								setTableInputAmount(table,"u_price",result.childNodes.item(0).getAttribute("price"));
							} else {
								setTableInput(table,"u_itemdesc","");
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_uom","");
								setTableInput(table,"u_department","");
								setTableInputAmount(table,"u_price",0);
								page.statusbar.showError("Invalid Item Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemdesc","");
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_uom","");
							setTableInput(table,"u_department","");
							setTableInputAmount(table,"u_price",0);
							page.statusbar.showError("Error retrieving Item. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemdesc","");
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_uom","");
						setTableInput(table,"u_department","");
						setTableInputAmount(table,"u_price",0);
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_price":
					computeTotalProratedGPSHIS();
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
		default:
			switch (column) {
				case "u_pricelist":
					resetPricesGPSHIS();
					break;
			}
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
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_qtyperpack")) return false;
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
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_qtyperpack")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
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


/*
function resetPricesGPSHIS() {
	var rc =  getTableRowCount("T1"), totalamount=0;
	showAjaxProcess();
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getInput("u_pricelist")!="") {
				var result2 = ajaxxmlgetitemprice(getTableInput("T1","u_itemcode",i),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
				setTableInputPrice("T1","u_unitprice",formatNumeric(result2.getAttribute("price"),'',0),i);
				setTableInputPrice("T1","u_price",formatNumeric(result2.getAttribute("price"),'',0),i);
				setTableInputAmount("T1","u_linetotal",formatNumeric(result2.getAttribute("price"),'',0)*getTableInputNumeric("T1","u_quantity",i),i);
			} else {
				setTableInputPrice("T1","u_unitprice",0,i);
				setTableInputPrice("T1","u_price",0,i);
				setTableInputAmount("T1","u_linetotal",0,i);
			}
			totalamount += getTableInputNumeric("T1","u_linetotal",i);
		}
	}
	if (getTableInput("T1","u_itemcode")!="" && getInput("u_pricelist")!="") {
		var result2 = ajaxxmlgetitemprice(getTableInput("T1","u_itemcode"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
		setTableInputPrice("T1","u_unitprice",formatNumeric(result2.getAttribute("price"),'',0));
		setTableInputPrice("T1","u_price",formatNumeric(result2.getAttribute("price"),'',0));
		setTableInputAmount("T1","u_linetotal",formatNumeric(result2.getAttribute("price"),'',0)*getTableInputNumeric("T1","u_quantity"));
	} else {
		setTableInputPrice("T1","u_unitprice",0);
		setTableInputPrice("T1","u_price",0);
		setTableInputAmount("T1","u_linetotal",0);
	}
	setInputAmount("u_amount",totalamount);	
	hideAjaxProcess();
}
*/

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), price=0,exclusive=false,section="";
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			price += getTableInputNumeric("T1","u_price",i)*getTableInputNumeric("T1","u_qtyperpack",i);
			if (section=="") {
				section = getTableInput("T1","u_department",i);	
				exclusive=true;	
			} else if (section != getTableInput("T1","u_department",i)) {
				exclusive=false;	
			}
		}
	}
	setInputAmount("u_price",price);	
	if (exclusive) setInput("u_department",section);	
	else  setInput("u_department","");	
}

function computeTotalProratedGPSHIS() {
	var rc =  getTableRowCount("T1"), totalprice=0, price=getInputNumeric("u_price"), lastrow=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalprice += getTableInputNumeric("T1","u_price",i);
		}
	}

	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			setTableInputAmount("T1","u_price",utils.round((getTableInputNumeric("T1","u_price",i)/totalprice)*getInputNumeric("u_price"),0),i);	//getInputNumeric("u_price")*
			price -= getTableInputNumeric("T1","u_price",i);
			lastrow=i;
		}
	}
	if (price!=0) setTableInputAmount("T1","u_price",getTableInputNumeric("T1","u_price",lastrow)+price,lastrow);	
}

