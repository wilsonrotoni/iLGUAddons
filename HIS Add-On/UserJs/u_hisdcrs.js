// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
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
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
//	setTableInputAmount("T1","u_denomination",getInputNumeric("u_openamount"));
//	setTableInputAmount("T2","u_amount",getInputNumeric("u_salesamount"));
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="?") {
			if (isInputEmpty("u_opendate")) return false;
			if (isInputEmpty("u_opentime")) return false;
			if (isInputEmpty("u_closedate")) return false;
			if (isInputEmpty("u_closetime")) return false;
	} else {
		if (action=="a") {
			if (getInput("u_mode")=="0") {
				if (isInputEmpty("u_cashierid")) return false;
				if (isInputEmpty("u_opendate")) return false;
				if (isInputEmpty("u_opentime")) return false;
			} else {
				if (isInputEmpty("u_opendate")) return false;
				if (isInputEmpty("u_opentime")) return false;
				if (isInputEmpty("u_closedate")) return false;
				if (isInputEmpty("u_closetime")) return false;
				
				if (getTableInput("T8","u_bankacctno")!="") {
					page.statusbar.showWarning("A bank deposit is curently being added/edited.");
					selectTab("tab1",5);
					return false;
				}
				if (getTableInput("T9","u_bankacctno")!="") {
					page.statusbar.showWarning("A check deposit is curently being added/edited.");
					selectTab("tab1",5);
					return false;
				}
			}
			if (window.confirm("Are you sure you want to open the register. Continue?")==false) return false;	
		} else if (action=="sc") {
			if (getTableInput("T8","u_bankacctno")!="") {
				page.statusbar.showWarning("A bank deposit is curently being added/edited.");
				selectTab("tab1",5);
				return false;
			}
			if (getTableInput("T9","u_bankacctno")!="") {
				page.statusbar.showWarning("A check deposit is curently being added/edited.");
				selectTab("tab1",5);
				return false;
			}
		}
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (getInput("u_mode")=="0") {
		if (action=="a" && sucess) runCustom('./udo.php?objectcode=u_hispos');
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
	var rc,openamount=0,closeamount=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_count":
					rc=getTableRowCount("T1");
					for (ii = 1; ii <= rc; ii++) {
						openamount += getTableInputNumeric(table,"u_count",ii) * getTableInputNumeric(table,"u_denomination",ii);
					}
					setInputAmount("u_openamount",openamount);
					setTableInputAmount(table,"u_denomination",openamount);
					break;
					
			}
			break;
		case "T2":
			switch (column) {
				case "u_count":
					rc=getTableRowCount("T2");
					for (ii = 1; ii <= rc; ii++) {
						if (getTableInputNumeric(table,"u_denomination",ii)>0) {
							closeamount += getTableInputNumeric(table,"u_count",ii) * getTableInputNumeric(table,"u_denomination",ii);
						} else {
							closeamount += getTableInputNumeric(table,"u_count",ii);
						}
					}
					setInputAmount("u_closeamount",closeamount);
					setInputAmount("u_cashvariance",closeamount - (getInputNumeric("u_openamount")+getInputNumeric("u_incashamount")-getInputNumeric("u_outcashamount")));
					setInputAmount("u_cashafterbankdp",closeamount - getInputNumeric("u_bankdpamount"));
					setTableInputAmount(table,"u_denomination",closeamount);
					break;
					
			}
			break;
		default:
			switch (column) {
				case "u_opendate":
					if (getInput("u_mode")=="1") {
						clearTable("T1");
						clearTable("T2");
					}
					clearTable("T3");
					clearTable("T4");
					clearTable("T5");
					clearTable("T6");
					clearTable("T7");					
					setInput("u_opentime","");
					break;
				case "u_closedate":
					if (getInput("u_mode")=="1") {
						clearTable("T1");
						clearTable("T2");
					}
					clearTable("T3");
					clearTable("T4");
					clearTable("T5");
					clearTable("T6");
					clearTable("T7");					
					setInput("u_closetime","");
					break;
				case "u_closetime":
					if (getInput("u_mode")=="1") {
						clearTable("T1");
						clearTable("T2");
					}
					clearTable("T3");
					clearTable("T4");
					clearTable("T5");
					clearTable("T6");
					clearTable("T7");					
					if (getInput("u_mode")=="0") {
						if (getInput("u_opendate")!="" && getInput("u_opentime")!="" && getInput("u_closedate")!="" && getInput("u_closetime")!="") {
							formSubmit("sc");							
						}
					}
					break;
				case "u_openamount":
					setInputAmount("u_cashvariance",getInputNumeric("u_closeamount") - (getInputNumeric("u_openamount")+getInputNumeric("u_incashamount")-getInputNumeric("u_outcashamount")));
					setInputAmount("u_cashafterbankdp",getInputNumeric("u_closeamount") - getInputNumeric("u_bankdpamount"));
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
	switch (table) {
		case "T8":
			switch (column) {
				case "u_bank":
					ajaxloadhousebankaccounts("df_u_bankacctnoT8",getGlobal("branch"),"PH",getTableInput("T8","u_bank"),'',":");
					break;
				case"u_bankacctno":
					var result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getTableInput("T8","u_bank"),getTableInput("T8","u_bankacctno"));
					if (result.getAttribute("active")==0) {
						setStatusMsg("Bank Account is not active anymore.");
						return false;
					}
					break;
			}
			break;
		case "T9":
			switch (column) {
				case "u_bank":
					ajaxloadhousebankaccounts("df_u_bankacctnoT9",getGlobal("branch"),"PH",getTableInput("T9","u_bank"),'',":");
					break;
				case"u_bankacctno":
					var result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getTableInput("T9","u_bank"),getTableInput("T9","u_bankacctno"));
					if (result.getAttribute("active")==0) {
						setStatusMsg("Bank Account is not active anymore.");
						return false;
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_cashierid":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select docno from U_HISDCRS where COMPANY='"+getGlobal("company")+"' AND BRANCH='"+getGlobal("branch")+"' AND  U_CASHIERID='"+getInput(column)+"' and U_STATUS='O'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								alert("Cashier still have open register record. System will not allow multiple register opened for each cashier.");
								setKey("keys",result.childNodes.item(0).getAttribute("docno"));
								formEdit();
							} else {
								setInput("u_cashiername",getInputSelectedText(column));
							}
						} else {
							page.statusbar.showError("Error retrieving cashier register history record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}				
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(element,params) {
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T8":
			if (isTableInputEmpty(table,"u_bank")) return false;
			if (isTableInputEmpty(table,"u_bankacctno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T9":
			if (isTableInputEmpty(table,"u_bank")) return false;
			if (isTableInputEmpty(table,"u_bankacctno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T8": 
			computeBankDepositsGPSHIS(); 
			break;
		case "T9": 
			computeCheckDepositsGPSHIS(); 
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T8":
			if (isTableInputEmpty(table,"u_bank")) return false;
			if (isTableInputEmpty(table,"u_bankacctno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T9":
			if (isTableInputEmpty(table,"u_bank")) return false;
			if (isTableInputEmpty(table,"u_bankacctno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T8": 
			computeBankDepositsGPSHIS(); 
			break;
		case "T9": 
			computeCheckDepositsGPSHIS(); 
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T8": 
			computeBankDepositsGPSHIS(); 
			break;
		case "T9": 
			computeCheckDepositsGPSHIS(); 
			break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
		case "T2":
			params["focus"] = false;
			focusTableInput(table,"u_count",row);
			break;
	}		
	return params;	
}

function u_refreshRegisterGPSPOSAsddon() {
	clearTable("T4");
	clearTable("T5");
	clearTable("T6");
	clearTable("T7");
	formSubmit("sc");	
}

function u_openRegisterGPSPOSAsddon() {
	formSubmit("a");	
}

function u_closeRegisterGPSPOSAsddon() {
	if (isInputEmpty("u_closedate")) return false;
	if (isInputEmpty("u_closetime")) return false;
	if (window.confirm("Are you sure you want to close the register. Continue?")==false) return;	
	setInput("u_status","C");
	formSubmit("sc");	
}

function computeBankDepositsGPSHIS() {
	var rc =  getTableRowCount("T8"),totalbd=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T8",i)==false) {
			totalbd+=getTableInputNumeric("T8","u_amount",i);
		}
	}
	setInputAmount("u_bankdpamount",totalbd);
	setInputAmount("u_cashafterbankdp",getInputNumeric("u_closeamount")-totalbd);
}

function computeCheckDepositsGPSHIS() {
	var rc =  getTableRowCount("T9"),totalbd=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T9",i)==false) {
			totalbd+=getTableInputNumeric("T9","u_amount",i);
		}
	}
	setInputAmount("u_bankdp2amount",totalbd);
	setInputAmount("u_checkafterbankdp",getInputNumeric("u_checkamount")-totalbd);
}
