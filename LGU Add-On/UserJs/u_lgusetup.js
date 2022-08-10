// page events
page.events.add.load('onPageLoadGPSLGU');
//page.events.add.resize('onPageResizeGPSLGU');
//page.events.add.submit('onPageSubmitGPSLGU');
//page.events.add.cfl('onCFLGPSLGU');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGU');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGU');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGU');
//page.elements.events.add.keydown('onElementKeyDownGPSLGU');
//page.elements.events.add.validate('onElementValidateGPSLGU');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGU');
//page.elements.events.add.changing('onElementChangingGPSLGU');
//page.elements.events.add.change('onElementChangeGPSLGU');
//page.elements.events.add.click('onElementClickGPSLGU');
//page.elements.events.add.cfl('onElementCFLGPSLGU');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGU');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGU');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGU');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGU');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGU');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGU');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGU');
//page.tables.events.add.delete('onTableDeleteRowGPSLGU');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGU');
//page.tables.events.add.select('onTableSelectRowGPSLGU');

function onPageLoadGPSLGU() {
	parent.document.getElementById('iframeBody').style.overflow = "auto";
}

function onPageResizeGPSLGU(width,height) {
}

function onPageSubmitGPSLGU(action) {
	return true;
}

function onCFLGPSLGU(Id) {
	return true;
}

function onCFLGetParamsGPSLGU(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGU() {
}

function onElementFocusGPSLGU(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGU(element,event,column,table,row) {
}

function onElementValidateGPSLGU(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_penaltycode":
				case "u_penaltydesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_penaltycode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"' and u_penalty=1");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%' and u_penalty=1");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_penaltycode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_penaltydesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_penaltycode","");
								setTableInput(table,"u_penaltydesc","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_penaltycode","");
							setTableInput(table,"u_penaltydesc","");
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_penaltycode","");
						setTableInput(table,"u_penaltydesc","");
					}						
					break;
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSLGU(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGU(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGU(element,column,table,row) {
	return true;
}

function onElementClickGPSLGU(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGU(element) {
	return true;
}

function onElementCFLGetParamsGPSLGU(Id,params) {
	switch (Id) {
		case "df_u_penaltycodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees where u_penalty=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Penalty Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_penaltydescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees where u_penalty=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Penalty Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
	return params;
}

function onTableResetRowGPSLGU(table) {
}

function onTableBeforeInsertRowGPSLGU(table) {
	return true;
}

function onTableAfterInsertRowGPSLGU(table,row) {
}

function onTableBeforeUpdateRowGPSLGU(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGU(table,row) {
}

function onTableBeforeDeleteRowGPSLGU(table,row) {
	return true;
}

function onTableDeleteRowGPSLGU(table,row) {
}

function onTableBeforeSelectRowGPSLGU(table,row) {
	return true;
}

function onTableSelectRowGPSLGU(table,row) {
}

