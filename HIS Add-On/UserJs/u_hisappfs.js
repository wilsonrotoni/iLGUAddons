// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
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
	if (action=="?") {
		if (isInputEmpty("u_docdate")) return false;
	} else if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;
		if (getInput("u_gltype")=="A") {
			if (isInputEmpty("u_glacctno")) return false;
			if (isInputEmpty("u_glacctname")) return false;
		} else {
			if (isInputEmpty("u_bank")) return false;
			if (isInputEmpty("u_bankacctno")) return false;
		}
		if (isInputEmpty("u_preparedby")) return false;
		if (isInputNegative("u_totalpayment")) return false;
		if ((action == "a" && getInput("docstatus")!="D") || ((getPrivate("docstatus")=="D") && (getInput("docstatus")!="D"))){
			if (getInput("docstatus")!="D") {
				if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
			}
		}	
		
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			//params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
	}
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
			switch (column) {
				case "u_docdate":
					if (getInput("u_docdate")!="") formSubmit('?');
					break;
				case "u_glacctno":
				case "u_glacctname":
					if (column="u_glacctname") {
						var result = page.executeFormattedQuery("select acctname, formatcode from chartofaccounts where postable=1 and acctname like '"+getInput(column)+"%'");		
					} else {
						var result = page.executeFormattedQuery("select acctname, formatcode from chartofaccounts where postable=1 and formatcode = '"+getInput(column)+"'");		
					}
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							setInput("u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
							setInput("u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
						} else {
							setInput("u_glacctno","");
							setInput("u_glacctname","");
							page.statusbar.showError("Invalid G/L Account.");	
							return false;
						}
					} else {
						setInput("u_glacctno","");
						setInput("u_glacctname","");
						page.statusbar.showError("Error retrieving g/l account record. Try Again, if problem persists, check the connection.");	
						return false;
					}
					
					break;
			}
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
		case "T1":
			break;
		default:
			switch (column) {
				case "u_bank":
					ajaxloadhousebankaccounts("df_u_bankacctno",getGlobal("branch"),"PH",getInput("u_bank"),'',":");
					break;
				case"u_bankacctno":
					var result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getInput("u_bank"),getInput("u_bankacctno"));
					if (result.getAttribute("active")==0) {
						setStatusMsg("Bank Account is not active anymore.");
						return false;
					}
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1": 
			switch (column) {
				case "u_selected":
					computeTotalGPSHIS(); 
					break;
			}
			break;
		default:
			switch (column) {
				case "u_gltype":
					disableInput("u_glacctno");
					disableInput("u_glacctname");
					disableInput("u_bank");
					disableInput("u_bankacctno");
					if (getInput("u_gltype")=="A") {
						enableInput("u_glacctno");
						enableInput("u_glacctname");
						setInput("u_bank","");
						setInput("u_bankacctno","");
						focusInput("u_glacctno");
					} else {
						enableInput("u_bank");
						enableInput("u_bankacctno");
						setInput("u_glacctno","");
						setInput("u_glacctname","");
						focusInput("u_bank");
					}
					break;
			}
			break;
				
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_glacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`50")); 			
			break;
		case "df_u_glacctname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`20")); 			
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
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			//if (elementFocused.substring(0,16)=="df_u_deductionT1") {
			//}
			//if (isPopupFrameOpen("popupFrameARs") && row>0) showARs();
			//focusTableInput(table,"u_deduction",row);
			break;
	}
	return params;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), payment=0,wtax=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (isTableInputChecked("T1","u_selected",i)) {
				wtax += getTableInputNumeric("T1","u_wtax",i);
				payment += getTableInputNumeric("T1","u_payment",i);
			}
		}
	}
	setInputAmount("u_totalwtax",wtax);	
	setInputAmount("u_totalpayment",payment);	
	setInputAmount("u_totalbalance",getInputNumeric("u_totalamount") - wtax - payment);	
}


function OpenLnkBtnRefNo(targetId) {
	switch (getTableElementValue(targetId,"T1","u_reftype")) {
		case "APINVOICE":
			OpenLnkBtnAPInvoices(targetId);
			break;
		case "APCREDITMEMO":
			OpenLnkBtnAPCreditMemos(targetId);
			break;
	}
}

function OpenLnkBtnDocNo(targetId) {
	OpenLnkBtnJournalVouchers(targetId)	;
	
}