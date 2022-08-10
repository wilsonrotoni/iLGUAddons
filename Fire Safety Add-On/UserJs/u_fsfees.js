// page events
//page.events.add.load('onPageLoadGPSFireSafety');
//page.events.add.resize('onPageResizeGPSFireSafety');
//page.events.add.submit('onPageSubmitGPSFireSafety');
//page.events.add.cfl('onCFLGPSFireSafety');
//page.events.add.cflgetparams('onCFLGetParamsGPSFireSafety');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFireSafety');

// element events
//page.elements.events.add.focus('onElementFocusGPSFireSafety');
//page.elements.events.add.keydown('onElementKeyDownGPSFireSafety');
page.elements.events.add.validate('onElementValidateGPSFireSafety');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFireSafety');
//page.elements.events.add.changing('onElementChangingGPSFireSafety');
//page.elements.events.add.change('onElementChangeGPSFireSafety');
//page.elements.events.add.click('onElementClickGPSFireSafety');
//page.elements.events.add.cfl('onElementCFLGPSFireSafety');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFireSafety');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFireSafety');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFireSafety');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFireSafety');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFireSafety');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFireSafety');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFireSafety');
//page.tables.events.add.delete('onTableDeleteRowGPSFireSafety');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFireSafety');
//page.tables.events.add.select('onTableSelectRowGPSFireSafety');

function onPageLoadGPSFireSafety() {
}

function onPageResizeGPSFireSafety(width,height) {
}

function onPageSubmitGPSFireSafety(action) {
	return true;
}

function onCFLGPSFireSafety(Id) {
	return true;
}

function onCFLGetParamsGPSFireSafety(Id,params) {
	return params;
}

function onTaskBarLoadGPSFireSafety() {
}

function onElementFocusGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSFireSafety(element,event,column,table,row) {
}

function onElementValidateGPSFireSafety(element,column,table,row) {
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

function onElementGetValidateParamsGPSFireSafety(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementChangeGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementClickGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementCFLGPSFireSafety(element) {
	return true;
}

function onElementCFLGetParamsGPSFireSafety(Id,params) {
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

function onTableResetRowGPSFireSafety(table) {
}

function onTableBeforeInsertRowGPSFireSafety(table) {
	return true;
}

function onTableAfterInsertRowGPSFireSafety(table,row) {
}

function onTableBeforeUpdateRowGPSFireSafety(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFireSafety(table,row) {
}

function onTableBeforeDeleteRowGPSFireSafety(table,row) {
	return true;
}

function onTableDeleteRowGPSFireSafety(table,row) {
}

function onTableBeforeSelectRowGPSFireSafety(table,row) {
	return true;
}

function onTableSelectRowGPSFireSafety(table,row) {
}

