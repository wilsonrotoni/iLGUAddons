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
		if (getInput("docstatus")!="D") {
			if (getPrivate("approver")!="1") {
				page.statusbar.showError("You must be an approver to add/update this document.");
				return false;
			}
		} else {
			if (getPrivate("encoder")!="1" && getPrivate("approver")!="1") {
				page.statusbar.showError("You must be an encoder/approver to save/update as draft this document.");
				return false;
			}
		}

		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_bank")) return false;
		if (isInputEmpty("u_bankacctno")) return false;
		if (isInputNegative("u_amount")) return false;
		if (getInput("u_jevauto")!="-1") {
			if (getInput("docstatus")!="D") {
				if (isInputEmpty("u_jevseries")) return false;
				if (isInputEmpty("u_jevno")) return false;
			}
		}
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
	switch (table) {
		default:
			switch (column) {
				case "u_jevno":
					if (isInputEmpty("u_date")) return false;
					if (isInputEmpty("u_jevseries")) return false;
					var year = formatDateToDB(getInput("u_date")).substr(0,4);
					var month = formatDateToDB(getInput("u_date")).substr(5,2);
					setInput("u_jevno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("u_jevseries")+getInput("u_jevno").padL(4,"0"));
					break;
				case "u_cashglacctno":
					if (getInput(column)!="") {
						if (getTableInput(table,"u_cashglacctno").length==8) {
							var s1="",s2="",s3="",s4="";
							s1 = getTableInput(table,"u_cashglacctno").substr(0,1);
							s2 = getTableInput(table,"u_cashglacctno").substr(1,2);
							s3 = getTableInput(table,"u_cashglacctno").substr(3,2);
							s4 = getTableInput(table,"u_cashglacctno").substr(5,3);
							setTableInput(table,"u_cashglacctno",s1+"-"+s2+"-"+s3+"-"+s4);
						}
						result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where formatcode = '"+getInput(column)+"' and postable=1 and ctrlacct=0");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_cashglacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setInput("u_cashglacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setInput("u_cashglacctno","");
								setInput("u_cashglacctname","");
								page.statusbar.showError("Invalid G/L Account No.");	
								return false;
							}
						} else {
							setInput("u_cashglacctno","");
							setInput("u_cashglacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_cashglacctno","");
						setInput("u_cashglacctname","");
					}
					break;
			}
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
			switch (column) {
				case "u_bank":
					ajaxloadhousebankaccounts("df_u_bankacctno",getGlobal("branch"),"PH",getInput("u_bank"),'',":");
					//setInput("u_tfbankbranch","");
					break;
				case"u_bankacctno":
					result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getInput("u_bank"),getInput("u_bankacctno"));
					//setInput("u_tfbankbranch",result.getAttribute("u_tfbankbranch"));
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
		case "df_u_cashglacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
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
}

