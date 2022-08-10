// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
//page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
//page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
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
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	switch (element) {
		case "df_itemcodeT1":
		case "df_itemdescT1":
			if (isTableInputEmpty("T1","whscode")) return false;
			break;
		
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_itemcodeT1":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1")); 
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 where  u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 			
			/*
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			*/
			break;
		case "df_itemdescT1":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1")); 
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 where  u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code`In-Stock")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 			

			/*
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			*/
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_itemcodeT1":
		case "df_itemdescT1":
			var items = value.split('`');
			if (items.length>0) {
				value = value.replace(/`/g,"','");
				if (Id=="df_itemcodeT1") {
					result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, a.u_manageby, ifnull(d.price,0) as price from u_hisitems a left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where code in ('"+value+"')");			
				} else {
					result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, a.u_manageby, ifnull(d.price,0) as price from u_hisitems a left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where a.name in ('"+utils.addslashes(value)+"') and u_active=1 ");			
				}
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["itemmanageby"] = result.childNodes.item(iii).getAttribute("u_manageby");
						data["uom"] = result.childNodes.item(iii).getAttribute("u_uompu");
						data["numperuom"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_numperuompu"));
						data["whscode"] = getTableInput("T1","whscode");
						data["whsmanagebymethod"] = getTableInput("T1","whsmanagebymethod");
						data["dropship"] = getTableInput("T1","dropship");
						data["vatcode"] = "VATIX";
						if (getInput("wtaxcode")!="") {
							data["wtaxliable"] = "1";
						} else {	
							data["wtaxliable"] = "0";
						}
						if (isTableInputUnique("T1","itemcode",data["itemcode"],getTableInputCaption("T1","itemdesc")+" ["+data["itemdesc"]+"] already exists.")) {
							data["quantity"] = formatNumericQuantity(1);
							//data["u_instock"] = result.childNodes.item(iii).getAttribute("instockqty");
							data["unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("price"));
							data["price"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("price"));
							//var result = ajaxxmlgetitemprice(data["u_itemcode"],"BRANCH:" + getInput("u_branch") + ";PRICELIST:{BS}");
							//data["u_unitprice"] = result.getAttribute("price");
							data["linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("price"));
							insertTableRowFromArray("T1",data);
						}
					}
					//resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
					//computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
					//computeTotalGPSHIS();
				} else {
					page.statusbar.showError("Error retrieving Items. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				return false;
			}
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
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
	return params;
}

