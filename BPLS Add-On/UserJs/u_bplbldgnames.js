// page events
//page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
//page.events.add.submit('onPageSubmitGPSBPLS');
//page.events.add.cfl('onCFLGPSBPLS');
//page.events.add.cflgetparams('onCFLGetParamsGPSBPLS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBPLS');

// element events
//page.elements.events.add.focus('onElementFocusGPSBPLS');
//page.elements.events.add.keydown('onElementKeyDownGPSBPLS');
page.elements.events.add.validate('onElementValidateGPSBPLS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSBPLS');
//page.elements.events.add.changing('onElementChangingGPSBPLS');
//page.elements.events.add.change('onElementChangeGPSBPLS');
//page.elements.events.add.click('onElementClickGPSBPLS');
//page.elements.events.add.cfl('onElementCFLGPSBPLS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
//page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
//page.tables.events.add.select('onTableSelectRowGPSBPLS');

function onPageLoadGPSBPLS() {
}

function onPageResizeGPSBPLS(width,height) {
}

function onPageSubmitGPSBPLS(action) {
	return true;
}

function onCFLGPSBPLS(Id) {
	return true;
}

function onCFLGetParamsGPSBPLS(Id,params) {
	return params;
}

function onTaskBarLoadGPSBPLS() {
}

function onElementFocusGPSBPLS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSBPLS(element,event,column,table,row) {
}

function onElementValidateGPSBPLS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_street":
					if (getTableInput(table, column)!="") {
						 var result = page.executeFormattedQuery("select name, u_brgy from u_streets where code='"+getTableInput(table, column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_street",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_brgy",result.childNodes.item(0).getAttribute("u_brgy"));
							} else {
								setTableInput(table,"u_street","");
								setTableInput(table,"u_brgy",0);
								page.statusbar.showError("Invalid street.");	
								return false;
							}
						} else {
							setTableInput(table,"u_street","");
							setTableInput(table,"u_brgy","");
							page.statusbar.showError("Error retrieving street record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_brgy","");
						setTableInput(table,"u_street","");
					}
					break;
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSBPLS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSBPLS(element,column,table,row) {
	return true;
}

function onElementChangeGPSBPLS(element,column,table,row) {
	return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
	return true;
}

function onElementCFLGPSBPLS(element) {
	return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
	switch (Id) {
		case "df_u_streetT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name,u_brgy from u_streets")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Name`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("1`")); 			
			break;
		case "df_u_brgyT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name from u_barangays")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table) {
	return true;
}

function onTableAfterInsertRowGPSBPLS(table,row) {
}

function onTableBeforeUpdateRowGPSBPLS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
}

function onTableBeforeDeleteRowGPSBPLS(table,row) {
	return true;
}

function onTableDeleteRowGPSBPLS(table,row) {
}

function onTableBeforeSelectRowGPSBPLS(table,row) {
	return true;
}

function onTableSelectRowGPSBPLS(table,row) {
}

