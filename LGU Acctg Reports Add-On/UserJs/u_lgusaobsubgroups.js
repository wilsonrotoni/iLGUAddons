// page events
//page.events.add.load('onPageLoadGPSLGUAcctgReports');
//page.events.add.resize('onPageResizeGPSLGUAcctgReports');
page.events.add.submit('onPageSubmitGPSLGUAcctgReports');
//page.events.add.cfl('onCFLGPSLGUAcctgReports');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctgReports');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctgReports');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctgReports');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctgReports');
page.elements.events.add.validate('onElementValidateGPSLGUAcctgReports');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctgReports');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctgReports');
page.elements.events.add.change('onElementChangeGPSLGUAcctgReports');
//page.elements.events.add.click('onElementClickGPSLGUAcctgReports');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctgReports');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctgReports');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctgReports');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctgReports');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctgReports');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctgReports');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctgReports');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctgReports');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctgReports');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctgReports');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctgReports');

function onPageLoadGPSLGUAcctgReports() {
}

function onPageResizeGPSLGUAcctgReports(width,height) {
}

function onPageSubmitGPSLGUAcctgReports(action) {
	if (action=="a" || action=="sc") {
		
		if (isInputEmpty("u_yr")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
						 
		if (getTableInput("T1","u_glacctno")!="")	{
			page.statusbar.showError("An item is currently being added/edited.");
			return false;
		}
		
	}
	return true;
}

function onCFLGPSLGUAcctgReports(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctgReports(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctgReports() {
}

function onElementFocusGPSLGUAcctgReports(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctgReports(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctgReports(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_glacctno":
				case "u_glacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_glacctno") result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and  postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								
								setTableInput(table,"u_expclass",result.childNodes.item(0).getAttribute("u_expclass"));
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								
								setTableInput(table,"u_expclass","");
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							
							setTableInput(table,"u_expclass","");
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						
						setTableInput(table,"u_expclass","");
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
					}
					break;
				
			}
			break;
		default:
			/*switch(column) {
			}*/
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctgReports(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctgReports(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctgReports(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_yr":
					setInput("code",getInput("u_yr") + "-" + getInput("u_profitcenter"));
					/* var code = page.executeFormattedSearch("select code from u_lgubudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					} */
					break;
				case "u_profitcenter":
					setInput("name",getInputSelectedText("u_profitcenter"));
					setInput("code",getInput("u_yr") + "-" + getInput("u_profitcenter"));
					/* var code = page.executeFormattedSearch("select code from u_lgubudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					} */
					
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctgReports(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctgReports(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctgReports(id,params) {
	switch (id) {	
		case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where u_expclass <>'' and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where u_expclass <>'' and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSLGUAcctgReports(table) {
}

function onTableBeforeInsertRowGPSLGUAcctgReports(table) {
	switch (table) {
		case "T1":
			//if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			if (isTableInputEmpty(table,"u_group")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctgReports(table,row) {
}

function onTableBeforeUpdateRowGPSLGUAcctgReports(table,row) {
	switch (table) {
		case "T1":
			//if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			if (isTableInputEmpty(table,"u_group")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctgReports(table,row) {
}

function onTableBeforeDeleteRowGPSLGUAcctgReports(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctgReports(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctgReports(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctgReports(table,row) {
}

