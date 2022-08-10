// page events
//page.events.add.load('onPageLoadCTC');
//page.events.add.resize('onPageResizeCTC');
//page.events.add.submit('onPageSubmitCTC');
//page.events.add.cfl('onCFLCTC');
//page.events.add.cflgetparams('onCFLGetParamsCTC');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadCTC');

// element events
//page.elements.events.add.focus('onElementFocusCTC');
//page.elements.events.add.keydown('onElementKeyDownCTC');
page.elements.events.add.validate('onElementValidateCTC');
//page.elements.events.add.validateparams('onElementGetValidateParamsCTC');
//page.elements.events.add.changing('onElementChangingCTC');
//page.elements.events.add.change('onElementChangeCTC');
//page.elements.events.add.click('onElementClickCTC');
//page.elements.events.add.cfl('onElementCFLCTC');
page.elements.events.add.cflgetparams('onElementCFLGetParamsCTC');

// table events
//page.tables.events.add.reset('onTableResetRowCTC');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowCTC');
//page.tables.events.add.afterInsert('onTableAfterInsertRowCTC');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowCTC');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowCTC');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowCTC');
//page.tables.events.add.delete('onTableDeleteRowCTC');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowCTC');
//page.tables.events.add.select('onTableSelectRowCTC');

function onPageLoadCTC() {
}

function onPageResizeCTC(width,height) {
}

function onPageSubmitCTC(action) {
	return true;
}

function onCFLCTC(Id) {
	return true;
}

function onCFLGetParamsCTC(Id,params) {
	return params;
}

function onTaskBarLoadCTC() {
}

function onElementFocusCTC(element,column,table,row) {
	return true;
}

function onElementKeyDownCTC(element,event,column,table,row) {
}

function onElementValidateCTC(element,column,table,row) {
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

function onElementGetValidateParamsCTC(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingCTC(element,column,table,row) {
	return true;
}

function onElementChangeCTC(element,column,table,row) {
	return true;
}

function onElementClickCTC(element,column,table,row) {
	return true;
}

function onElementCFLCTC(element) {
	return true;
}

function onElementCFLGetParamsCTC(Id,params) {
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

function onTableResetRowCTC(table) {
}

function onTableBeforeInsertRowCTC(table) {
	return true;
}

function onTableAfterInsertRowCTC(table,row) {
}

function onTableBeforeUpdateRowCTC(table,row) {
	return true;
}

function onTableAfterUpdateRowCTC(table,row) {
}

function onTableBeforeDeleteRowCTC(table,row) {
	return true;
}

function onTableDeleteRowCTC(table,row) {
}

function onTableBeforeSelectRowCTC(table,row) {
	return true;
}

function onTableSelectRowCTC(table,row) {
}

