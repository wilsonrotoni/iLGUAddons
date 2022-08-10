// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUAcctg');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctg');
page.elements.events.add.validate('onElementValidateGPSLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctg');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
page.elements.events.add.change('onElementChangeGPSLGUAcctg');
//page.elements.events.add.click('onElementClickGPSLGUAcctg');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctg');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctg');
//page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSLGUAcctg');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');


function onPageLoadGPSLGUAcctg() {
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	if (action=="a" || action=="sc") {
		
		if (isInputEmpty("u_profitcenter")) return false;
		
	}
	return true;
}

function onCFLGPSLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctg() {
}

function onElementFocusGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctg(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctg(element,column,table,row) {
	var amount=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_glacctno":
				case "u_glacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_glacctno") result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and u_expclass <>'' and formatcode = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and u_expclass <>'' and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								//setTableInput(table,"u_expgroupno",result.childNodes.item(0).getAttribute("u_expgroupno"));
								//setTableInput(table,"u_expclass",result.childNodes.item(0).getAttribute("u_expclass"));
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								//setTableInput(table,"u_expgroupno","");
								//setTableInput(table,"u_expclass","");
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							//setTableInput(table,"u_expgroupno","");
							//setTableInput(table,"u_expclass","");
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						//setTableInput(table,"u_expgroupno","");
						//setTableInput(table,"u_expclass","");
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_profitcenter":
					setInput("code",getInput("u_profitcenter"));
					setInput("name",getInputSelectedText("u_profitcenter"));
					var code = page.executeFormattedSearch("select code from u_lgupcsubsidiaries where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where postable=1 and budget=1 and u_expclass <>''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where postable=1 and budget=1 and u_expclass <>''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onElementLnkBtnGetParamsGPSLGUAcctg(id,params) {
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
	var params = new Array();
	return params;
}

