// page events
//page.events.add.load('onPageLoadGPSPMRSMobile');
//page.events.add.resize('onPageResizeGPSPMRSMobile');
//page.events.add.submit('onPageSubmitGPSPMRSMobile');
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
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPMRSMobile');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPMRSMobile');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPMRSMobile');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPMRSMobile');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPMRSMobile');
//page.tables.events.add.delete('onTableDeleteRowGPSPMRSMobile');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPMRSMobile');
//page.tables.events.add.select('onTableSelectRowGPSPMRSMobile');

function onPageLoadGPSPMRSMobile() {
}

function onPageResizeGPSPMRSMobile(width,height) {
}

function onPageSubmitGPSPMRSMobile(action) {
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
				case "code":
				case "name":
					if (getTableInput(table, column)!="") {
						if (column=="code") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table, column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table, column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"code",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"name",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_denomination",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"code","");
								setTableInput(table,"name","");
								setTableInputAmount(table,"u_denomination",0);
								page.statusbar.showError("Invalid Fee.");	
								return false;
							}
						} else {
							setTableInput(table,"code","");
							setTableInput(table,"name","");
							setTableInputAmount(table,"u_denomination",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"code","");
						setTableInput(table,"name","");
						setTableInputAmount(table,"u_denomination",0);
					}
					break;
			}
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

function onElementCFLGetParamsGPSPMRSMobile(Id,params) {
	switch (Id) {
		case "df_codeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee Code`Fee Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_nameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee Description`Fee Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSPMRSMobile(table) {
}

function onTableBeforeInsertRowGPSPMRSMobile(table) {
	return true;
}

function onTableAfterInsertRowGPSPMRSMobile(table,row) {
}

function onTableBeforeUpdateRowGPSPMRSMobile(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSPMRSMobile(table,row) {
}

function onTableBeforeDeleteRowGPSPMRSMobile(table,row) {
	return true;
}

function onTableDeleteRowGPSPMRSMobile(table,row) {
}

function onTableBeforeSelectRowGPSPMRSMobile(table,row) {
	return true;
}

function onTableSelectRowGPSPMRSMobile(table,row) {
}

