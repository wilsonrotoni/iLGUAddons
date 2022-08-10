// page events
//page.events.add.load('onPageLoadGPSPMRSMobile');
//page.events.add.resize('onPageResizeGPSPMRSMobile');
page.events.add.submit('onPageSubmitGPSPMRSMobile');
//page.events.add.cfl('onCFLGPSPMRSMobile');
//page.events.add.cflgetparams('onCFLGetParamsGPSPMRSMobile');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPMRSMobile');

// element events
//page.elements.events.add.focus('onElementFocusGPSPMRSMobile');
//page.elements.events.add.keydown('onElementKeyDownGPSPMRSMobile');
page.elements.events.add.validate('onElementValidateGPSPMRSMobile');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPMRSMobile');
//page.elements.events.add.changing('onElementChangingGPSPMRSMobile');
//page.elements.events.add.change('onElementChangeGPSPMRSMobile');
//page.elements.events.add.click('onElementClickGPSPMRSMobile');
//page.elements.events.add.cfl('onElementCFLGPSPMRSMobile');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPMRSMobile');

// table events
//page.tables.events.add.reset('onTableResetRowGPSPMRSMobile');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPMRSMobile');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPMRSMobile');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPMRSMobile');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPMRSMobile');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPMRSMobile');
page.tables.events.add.delete('onTableDeleteRowGPSPMRSMobile');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPMRSMobile');
//page.tables.events.add.select('onTableSelectRowGPSPMRSMobile');

function onPageLoadGPSPMRSMobile() {
}

function onPageResizeGPSPMRSMobile(width,height) {
}

function onPageSubmitGPSPMRSMobile(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
    if (action=="a" || action=="sc") {
		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_userid")) return false;
		if (isInputEmpty("u_terminalid")) return false;
		if (isInputNegative("u_totalamount")) return false;
	}
	return true;
}

function onCFLGPSPMRSMobile(Id) {
	return true;
}

function onCFLGetParamsGPSPMRSMobile(Id,params) {
	return params;
}

function onTaskBarLoadGPSPMRSMobile() {
}

function onElementFocusGPSPMRSMobile(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPMRSMobile(element,event,column,table,row) {
}

function onElementValidateGPSPMRSMobile(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_vendorcode":
				case "u_vendorname":
					if (getTableInput(table,column)!="") {
						if (column=="u_vendorcode") var result = page.executeFormattedQuery("select code, name from u_pmrambulantvendors where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name from u_pmrambulantvendors where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and name = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_vendorcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_vendorname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_vendorcode","");
								setTableInput(table,"u_vendorname","");
								page.statusbar.showError("Invalid Ambulant Vendor!");	
								return false;
							}
						} else {
							setTableInput(table,"u_vendorcode","");
							setTableInput(table,"u_vendorname","");
							page.statusbar.showError("Error retrieving vambulant vendor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_vendorcode","");
						setTableInput(table,"u_vendorname","");
					}
					
					break;
				case "u_quantity":
				case "u_unitprice":
					setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity")*getTableInputNumeric(table,"u_unitprice"));
					break;
                                case "u_feecode":
                                        if (getTableInput(table,column)!="") {
                                                var result = page.executeFormattedQuery("select u_denomination from u_pmrcashtickets where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_unitprice",formatNumericAmount(result.childNodes.item(0).getAttribute("u_denomination")));
							} else {
								setTableInput(table,"u_unitprice",formatNumericAmount(0));
								page.statusbar.showError("Invalid Fee Code");	
								return false;
							}
						} else {
							setTableInput(table,"u_unitprice",formatNumericAmount(0));
							page.statusbar.showError("Error retrieving Denominations. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_unitprice",formatNumericAmount(0));
					}
                                    break;
			}
			break;
		default:
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSPMRSMobile(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPMRSMobile(element,column,table,row) {
	return true;
}

function onElementChangeGPSPMRSMobile(element,column,table,row) {
	return true;
}

function onElementClickGPSPMRSMobile(element,column,table,row) {
	return true;
}

function onElementCFLGPSPMRSMobile(element) {
	return true;
}

function onElementCFLGetParamsGPSPMRSMobile(element,params) {
	switch (element) {	
		case "df_u_vendorcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_pmrambulantvendors where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Vendor Code`Vendor Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_vendornameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name code  from u_pmrambulantvendors where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Vendor Name`Vendor Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSPMRSMobile(table) {
}

function onTableBeforeInsertRowGPSPMRSMobile(table) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_trxno")) return false;
			if (isTableInputEmpty(table,"u_vendorcode")) return false;
			if (isTableInputEmpty(table,"u_vendorname")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSPMRSMobile(table,row) {
	switch (table) {
		case "T1": 
			computeTotal();
			break;
	}
	
}

function onTableBeforeUpdateRowGPSPMRSMobile(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_trxno")) return false;
			if (isTableInputEmpty(table,"u_vendorcode")) return false;
			if (isTableInputEmpty(table,"u_vendorname")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSPMRSMobile(table,row) {
	switch (table) {
		case "T1": 
			computeTotal();
			break;
	}
	
}

function onTableBeforeDeleteRowGPSPMRSMobile(table,row) {
	return true;
}

function onTableDeleteRowGPSPMRSMobile(table,row) {
	switch (table) {
		case "T1": 
			computeTotal();
			break;
	}
}

function onTableBeforeSelectRowGPSPMRSMobile(table,row) {
	return true;
}

function onTableSelectRowGPSPMRSMobile(table,row) {
}

function computeTotal() {
	var rc = getTableRowCount("T1"),totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamount+= getTableInputNumeric("T1","u_linetotal",i);
		}
	}	
	setInputAmount("u_totalamount",totalamount);
}


