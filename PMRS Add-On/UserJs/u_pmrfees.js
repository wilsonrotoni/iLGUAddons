// page events
//page.events.add.load('onPageLoadGPSPMRS');
//page.events.add.resize('onPageResizeGPSPMRS');
//page.events.add.submit('onPageSubmitGPSPMRS');
//page.events.add.cfl('onCFLGPSPMRS');
//page.events.add.cflgetparams('onCFLGetParamsGPSPMRS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPMRS');

// element events
//page.elements.events.add.focus('onElementFocusGPSPMRS');
//page.elements.events.add.keydown('onElementKeyDownGPSPMRS');
page.elements.events.add.validate('onElementValidateGPSPMRS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPMRS');
//page.elements.events.add.changing('onElementChangingGPSPMRS');
//page.elements.events.add.change('onElementChangeGPSPMRS');
//page.elements.events.add.click('onElementClickGPSPMRS');
//page.elements.events.add.cfl('onElementCFLGPSPMRS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPMRS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSPMRS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPMRS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPMRS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPMRS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPMRS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPMRS');
//page.tables.events.add.delete('onTableDeleteRowGPSPMRS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPMRS');
//page.tables.events.add.select('onTableSelectRowGPSPMRS');

function onPageLoadGPSPMRS() {
}

function onPageResizeGPSPMRS(width,height) {
}

function onPageSubmitGPSPMRS(action) {
	return true;
}

function onCFLGPSPMRS(Id) {
	return true;
}

function onCFLGetParamsGPSPMRS(Id,params) {
	return params;
}

function onTaskBarLoadGPSPMRS() {
}

function onElementFocusGPSPMRS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPMRS(element,event,column,table,row) {
}

function onElementValidateGPSPMRS(element,column,table,row) {
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
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"code","");
								setTableInput(table,"name","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee.");	
								return false;
							}
						} else {
							setTableInput(table,"code","");
							setTableInput(table,"name","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"code","");
						setTableInput(table,"name","");
						setTableInputAmount(table,"u_amount",0);
					}
					break;
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSPMRS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPMRS(element,column,table,row) {
	return true;
}

function onElementChangeGPSPMRS(element,column,table,row) {
	return true;
}

function onElementClickGPSPMRS(element,column,table,row) {
	return true;
}

function onElementCFLGPSPMRS(element) {
	return true;
}

function onElementCFLGetParamsGPSPMRS(Id,params) {
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

function onTableResetRowGPSPMRS(table) {
}

function onTableBeforeInsertRowGPSPMRS(table) {
	return true;
}

function onTableAfterInsertRowGPSPMRS(table,row) {
}

function onTableBeforeUpdateRowGPSPMRS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSPMRS(table,row) {
}

function onTableBeforeDeleteRowGPSPMRS(table,row) {
	return true;
}

function onTableDeleteRowGPSPMRS(table,row) {
}

function onTableBeforeSelectRowGPSPMRS(table,row) {
	return true;
}

function onTableSelectRowGPSPMRS(table,row) {
}

