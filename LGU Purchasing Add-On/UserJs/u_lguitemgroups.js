// page events
//page.events.add.load('onPageLoadGPSLGUPurchasing');
//page.events.add.resize('onPageResizeGPSLGUPurchasing');H
//page.events.add.submit('onPageSubmitGPSLGUPurchasing');
//page.events.add.cfl('onCFLGPSLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUPurchasing');
page.elements.events.add.validate('onElementValidateGPSLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUPurchasing');
//page.elements.events.add.changing('onElementChangingGPSLGUPurchasing');
//page.elements.events.add.change('onElementChangeGPSLGUPurchasing');
//page.elements.events.add.click('onElementClickGPSLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLGPSLGUPurchasing');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUPurchasing');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUPurchasing');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowGPSLGUPurchasing');

function onPageLoadGPSLGUPurchasing() {
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
				
				case "u_expenseglacctno":
				case "u_expenseglacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_expenseglacctno") {
							if (getTableInput(table,"u_expenseglacctno").length==8) {
								var s1="",s2="",s3="",s4="";
								s1 = getTableInput(table,"u_expenseglacctno").substr(0,1);
								s2 = getTableInput(table,"u_expenseglacctno").substr(1,2);
								s3 = getTableInput(table,"u_expenseglacctno").substr(3,2);
								s4 = getTableInput(table,"u_expenseglacctno").substr(5,3);
								setTableInput(table,"u_expenseglacctno",s1+"-"+s2+"-"+s3+"-"+s4);
							}
							result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1 and formatcode = '"+getTableInput(table,column)+"'");	
						} else  result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_expenseglacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_expenseglacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_expenseglacctno","");
								setTableInput(table,"u_expenseglacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_expenseglacctno","");
							setTableInput(table,"u_expenseglacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_expenseglacctno","");
						setTableInput(table,"u_expenseglacctname","");
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

function onElementCFLGetParamsGPSLGUPurchasing(id,params) {
	switch (id) {	
		case "df_u_expenseglacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_expenseglacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1 and budget=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSLGUPurchasing(table) {
}

function onTableBeforeInsertRowGPSLGUPurchasing(table) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeUpdateRowGPSLGUPurchasing(table,row) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
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

