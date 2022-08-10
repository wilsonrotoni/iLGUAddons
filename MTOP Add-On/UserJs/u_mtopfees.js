// page events
//page.events.add.load('onPageLoadGPSMTOP');
//page.events.add.resize('onPageResizeGPSMTOP');
//page.events.add.submit('onPageSubmitGPSMTOP');
//page.events.add.cfl('onCFLGPSMTOP');
//page.events.add.cflgetparams('onCFLGetParamsGPSMTOP');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSMTOP');

// element events
//page.elements.events.add.focus('onElementFocusGPSMTOP');
//page.elements.events.add.keydown('onElementKeyDownGPSMTOP');
page.elements.events.add.validate('onElementValidateGPSMTOP');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSMTOP');
//page.elements.events.add.changing('onElementChangingGPSMTOP');
//page.elements.events.add.change('onElementChangeGPSMTOP');
//page.elements.events.add.click('onElementClickGPSMTOP');
//page.elements.events.add.cfl('onElementCFLGPSMTOP');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSMTOP');

// table events
//page.tables.events.add.reset('onTableResetRowGPSMTOP');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSMTOP');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSMTOP');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSMTOP');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSMTOP');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSMTOP');
//page.tables.events.add.delete('onTableDeleteRowGPSMTOP');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSMTOP');
//page.tables.events.add.select('onTableSelectRowGPSMTOP');

function onPageLoadGPSMTOP() {
}

function onPageResizeGPSMTOP(width,height) {
}

function onPageSubmitGPSMTOP(action) {
	return true;
}

function onCFLGPSMTOP(Id) {
	return true;
}

function onCFLGetParamsGPSMTOP(Id,params) {
	return params;
}

function onTaskBarLoadGPSMTOP() {
}

function onElementFocusGPSMTOP(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSMTOP(element,event,column,table,row) {
}

function onElementValidateGPSMTOP(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "code":
				case "name":
					if (getTableInput(table, column)!="") {
						if (column=="code") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table, column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+utils.addslashes(getTableInput(table, column))+"%'");	
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

function onElementGetValidateParamsGPSMTOP(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSMTOP(element,column,table,row) {
	return true;
}

function onElementChangeGPSMTOP(element,column,table,row) {
	return true;
}

function onElementClickGPSMTOP(element,column,table,row) {
	return true;
}

function onElementCFLGPSMTOP(element) {
	return true;
}

function onElementCFLGetParamsGPSMTOP(Id,params) {
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

function onTableResetRowGPSMTOP(table) {
}

function onTableBeforeInsertRowGPSMTOP(table) {
	return true;
}

function onTableAfterInsertRowGPSMTOP(table,row) {
}

function onTableBeforeUpdateRowGPSMTOP(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSMTOP(table,row) {
}

function onTableBeforeDeleteRowGPSMTOP(table,row) {
	return true;
}

function onTableDeleteRowGPSMTOP(table,row) {
}

function onTableBeforeSelectRowGPSMTOP(table,row) {
	return true;
}

function onTableSelectRowGPSMTOP(table,row) {
}

