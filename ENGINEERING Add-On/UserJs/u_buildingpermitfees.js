// page events
//page.events.add.load('onPageLoadGPSENGINEERING');
//page.events.add.resize('onPageResizeGPSENGINEERING');
//page.events.add.submit('onPageSubmitGPSENGINEERING');
//page.events.add.cfl('onCFLGPSENGINEERING');
//page.events.add.cflgetparams('onCFLGetParamsGPSENGINEERING');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSENGINEERING');

// element events
//page.elements.events.add.focus('onElementFocusGPSENGINEERING');
//page.elements.events.add.keydown('onElementKeyDownGPSENGINEERING');
page.elements.events.add.validate('onElementValidateGPSENGINEERING');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSENGINEERING');
//page.elements.events.add.changing('onElementChangingGPSENGINEERING');
//page.elements.events.add.change('onElementChangeGPSENGINEERING');
//page.elements.events.add.click('onElementClickGPSENGINEERING');
//page.elements.events.add.cfl('onElementCFLGPSENGINEERING');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSENGINEERING');

// table events
//page.tables.events.add.reset('onTableResetRowGPSENGINEERING');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSENGINEERING');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSENGINEERING');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSENGINEERING');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSENGINEERING');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSENGINEERING');
//page.tables.events.add.delete('onTableDeleteRowGPSENGINEERING');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSENGINEERING');
//page.tables.events.add.select('onTableSelectRowGPSENGINEERING');

function onPageLoadGPSENGINEERING() {
}

function onPageResizeGPSENGINEERING(width,height) {
}

function onPageSubmitGPSENGINEERING(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
    	
	return true;
}

function onCFLGPSENGINEERING(Id) {
	return true;
}

function onCFLGetParamsGPSENGINEERING(Id,params) {
	return params;
}

function onTaskBarLoadGPSENGINEERING() {
}

function onElementFocusGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSENGINEERING(element,event,column,table,row) {
}

function onElementValidateGPSENGINEERING(element,column,table,row) {
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
								//setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"code","");
								setTableInput(table,"name","");
								//setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee.");	
								return false;
							}
						} else {
							setTableInput(table,"code","");
							setTableInput(table,"name","");
							//setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"code","");
						setTableInput(table,"name","");
						//setTableInputAmount(table,"u_amount",0);
					}
					break;
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSENGINEERING(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementChangeGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementClickGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementCFLGPSENGINEERING(element) {
	return true;
}

function onElementCFLGetParamsGPSENGINEERING(Id,params) {
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

function onTableResetRowGPSENGINEERING(table) {
}

function onTableBeforeInsertRowGPSENGINEERING(table) {
	return true;
}

function onTableAfterInsertRowGPSENGINEERING(table,row) {
}

function onTableBeforeUpdateRowGPSENGINEERING(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSENGINEERING(table,row) {
}

function onTableBeforeDeleteRowGPSENGINEERING(table,row) {
	return true;
}

function onTableDeleteRowGPSENGINEERING(table,row) {
}

function onTableBeforeSelectRowGPSENGINEERING(table,row) {
	return true;
}

function onTableSelectRowGPSENGINEERING(table,row) {
}

