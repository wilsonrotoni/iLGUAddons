// page events
page.events.add.load('onPageLoadGPSLGUPurchasing');
//page.events.add.resize('onPageResizeGPSLGUPurchasing');
//page.events.add.submit('onPageSubmitGPSLGUPurchasing');
//page.events.add.cfl('onCFLGPSLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUPurchasing');
//page.elements.events.add.validate('onElementValidateGPSLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUPurchasing');
//page.elements.events.add.changing('onElementChangingGPSLGUPurchasing');
//page.elements.events.add.change('onElementChangeGPSLGUPurchasing');
//page.elements.events.add.click('onElementClickGPSLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLGPSLGUPurchasing');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUPurchasing');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUPurchasing');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowGPSLGUPurchasing');

function onPageLoadGPSLGUPurchasing() {
	parent.document.getElementById('iframeBody').style.overflow = "auto";
}

function onPageResizeGPSLGUPurchasing(width,height) {
}

function onPageSubmitGPSLGUPurchasing(action) {
	return true;
}

function onCFLGPSLGUPurchasing(Id) {
	return true;
}

function onCFLGetParamsGPSLGUPurchasing(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUPurchasing() {
}

function onElementFocusGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUPurchasing(element,event,column,table,row) {
}

function onElementValidateGPSLGUPurchasing(element,column,table,row) {
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

function onElementGetValidateParamsGPSLGUPurchasing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUPurchasing(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUPurchasing(Id,params) {
	switch (Id) {
              case "df_u_stockallocglacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where budget=1 and u_expclass <>'' and postable=0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
	return params;
}

function onTableResetRowGPSLGUPurchasing(table) {
}

function onTableBeforeInsertRowGPSLGUPurchasing(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeUpdateRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeDeleteRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeSelectRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowGPSLGUPurchasing(table,row) {
}

