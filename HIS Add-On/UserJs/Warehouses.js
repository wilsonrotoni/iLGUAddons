// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
//page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_ibglacctno":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select a.formatcode, a.acctname from chartofaccounts a where a.postable=1 and a.ctrlacct=0 and a.cashacct=0 and a.cashinbankacct=0 and a.formatcode = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_ibglacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setInput("u_ibglacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setInput("u_ibglacctno","");
								setInput("u_ibglacctname","");
								page.statusbar.showError("Invalid G/L Acct No.");	
								return false;
							}
						} else {
							setInput("u_ibglacctno","");
							setInput("u_ibglacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_ibglacctno","");
						setInput("u_ibglacctname","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_ibglacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where postable=1 and ctrlacct=0 and cashacct=0 and cashinbankacct=0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Acct No.`G/L Acct Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	return params;
}


