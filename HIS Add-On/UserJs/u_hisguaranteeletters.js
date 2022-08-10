// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
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
page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
    if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false; 
		if (isInputEmpty("u_startdate")) return false; 
		if (isInputEmpty("u_enddate")) return false; 
		if (isInputEmpty("u_inscode")) return false; 
		if (isInputEmpty("u_patientid")) return false; 
		if (isInputNegative("u_totalamount")) return false; 
		if (isInputNegated("u_balance")) return false; 
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
		case "T1":
			switch(column) {
				case "u_refno":
					if (getTableInput(table,"u_refno")!="") {
						if (getTableInput("T1","u_reftype")=="IP") {
							result = page.executeFormattedQuery("select docno, u_startdate from u_hisips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("u_patientid")+"' and docstatus not in ('Discharged') and docno='"+getTableInput(table,"u_refno")+"'");	 
						} else {
							result = page.executeFormattedQuery("select docno, u_startdate from u_hisops where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("u_patientid")+"' and docstatus not in ('Discharged','Admitted') and docno='"+getTableInput(table,"u_refno")+"'");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_refno",result.childNodes.item(0).getAttribute("docno"));
								setTableInput(table,"u_startdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_startdate")));
							} else {
								setTableInput(table,"u_refno","");
								setTableInput(table,"u_startdate","");
								page.statusbar.showError("Invalid Reference No.");
								return false;
							}
						} else {
							setTableInput(table,"u_refno","");
							setTableInput(table,"u_startdate","");
							page.statusbar.showError("Error retrieving registration record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_refno","");
						setTableInput(table,"u_startdate","");
					}
					break;
				
			}
			break;
		default:
			switch(column) {
				case "u_patientid":
					if (getInput("u_patientid")!="") {
						result = page.executeFormattedQuery("select code, name, u_birthdate, u_gender from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'and code='"+getInput("u_patientid")+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
								//setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
								//setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
								//checkOpenDocument();
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								//setInput("u_birthdate","");
								//setInput("u_gender","");
								page.statusbar.showError("Invalid Patient ID.");
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							//setInput("u_birthdate","");
							//setInput("u_gender","");
							page.statusbar.showError("Error retrieving Patient. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						//setInput("u_birthdate","");
						//setInput("u_gender","");
					}
					break;
				case "u_docdate":
					setInput("u_startdate",getInput("u_docdate"));
				case "u_totalamount":
					computeBalanceGPSHIS();
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
			switch(column) {
				case "u_reftype":
					setTableInput(table,"u_refno","");
					setTableInput(table,"u_startdate","");
					break;
			}
			break;
		default:
			switch(column) {
				case "u_inscode":
					setInput("u_insname",getInputSelectedText("u_inscode"));
					//checkOpenDocument();
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_patientid":
			if (isInputEmpty("u_inscode")) return false;
			break;
		case "df_u_refnoT1":
			if (isInputEmpty("u_patientid")) return false;
			if (isTableInputEmpty("T1","u_reftype")) return false;
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_patientid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_active=1"));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=name";
			break;
		case "df_u_refnoT1":
			if (getTableInput("T1","u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_startdate, u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc  from u_hisips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("u_patientid")+"' and docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_startdate, u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc  from u_hisops where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("u_patientid")+"' and docstatus not in ('Discharged','Admitted')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Patient Name`Payment`Term`SC")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`50`15`15`3")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date````")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;			
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_date");
			break;
	}
	return true;
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_date")) return false;
			if (isTableInputEmpty(table,"u_reftype")) return false;
			if (isTableInputEmpty(table,"u_refno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeBalanceGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_date")) return false;
			if (isTableInputEmpty(table,"u_reftype")) return false;
			if (isTableInputEmpty(table,"u_refno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeBalanceGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeBalanceGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function checkOpenDocument() {
	if (getInput("u_inscode")!="" && getInput("u_patientid")!="") {
		var result2 = page.executeFormattedSearch("select docno from u_hisguaranteeletters where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_inscode='"+getInput("u_inscode")+"' and u_patientid='"+getInput("u_patientid")+"' and docstatus in ('O') and u_balance>0");
		if (result2!="") {
			alert("Guarantee Letter exists with Open Status.");
			setKey("keys",result2);
			formEdit();
			return true;
		}
	}
}

function computeBalanceGPSHIS() {
	var rc =  getTableRowCount("T1"), totalcharges=0;
	
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalcharges += getTableInputNumeric("T1","u_amount",i);
		}
	}
	setInputAmount("u_totalcharges",totalcharges);
	setInputAmount("u_balance",getInputNumeric("u_totalamount")-totalcharges);
	
}

