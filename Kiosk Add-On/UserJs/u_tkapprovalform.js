// page events
page.events.add.load('onPageLoadGPSKiosk');
//page.events.add.resize('onPageResizeGPSKiosk');
page.events.add.submit('onPageSubmitGPSKiosk');
page.events.add.submitreturn('onPageSubmitReturnGPSKiosk');
//page.events.add.cfl('onCFLGPSKiosk');
//page.events.add.cflgetparams('onCFLGetParamsGPSKiosk');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSKiosk');

// element events
//page.elements.events.add.focus('onElementFocusGPSKiosk');
//page.elements.events.add.keydown('onElementKeyDownGPSKiosk');
page.elements.events.add.validate('onElementValidateGPSKiosk');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSKiosk');
//page.elements.events.add.changing('onElementChangingGPSKiosk');
//page.elements.events.add.change('onElementChangeGPSKiosk');
page.elements.events.add.click('onElementClickGPSKiosk');
page.elements.events.add.cfl('onElementCFLGPSKiosk');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSKiosk');

// table events
//page.tables.events.add.reset('onTableResetRowGPSKiosk');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSKiosk');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSKiosk');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSKiosk');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSKiosk');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSKiosk');
//page.tables.events.add.delete('onTableDeleteRowGPSKiosk');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSKiosk');
//page.tables.events.add.select('onTableSelectRowGPSKiosk');

function onPageLoadGPSKiosk() {
	if(getElementValueById("formSubmitAction") == "a") {
		showRequestLists();
	}

}

function onPageResizeGPSKiosk(width,height) {
}

function onPageSubmitGPSKiosk(action) {
if (action=="a") {
		if (isInputEmpty("u_approverby")) return false;
		if (isInputEmpty("u_date")) return false;
		//if (isInputEmpty("u_profitcenter")) return false;
		if (getTableRowCount("T1")=="0") {
			if (window.confirm("No Employee Request. Continue?")==false)	return false;
		}
	}
	return true;
}

function onPageSubmitReturnGPSKiosk(action,success,error) {
		try {
				//window.opener.setKey("keys",window.opener.getInput("code"));
				window.opener.location.reload();
			} catch(TheError) {
		}
			if (success) window.close();
}

function onCFLGPSKiosk(Id) {
	return true;
}

function onCFLGetParamsGPSKiosk(Id,params) {
	return params;
}

function onTaskBarLoadGPSKiosk() {
}

function onElementFocusGPSKiosk(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSKiosk(element,event,column,table,row) {
}

function onElementValidateGPSKiosk(element,column,table,row) {
switch (table) {
	default:
			switch(column) {
				case "u_approverby":
				case "u_date":
				case "u_profitcenter":
				if (getInput("u_date")!="" && getInput("u_approverby")!="" && getInput("u_profitcenter")!="") {
						result = page.executeFormattedQuery("SELECT DAYNAME('"+formatDateToDB(getInput("u_date"))+"') as code");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_dayname",result.childNodes.item(0).getAttribute("code"));
								addCodeGPSKiosk();
								showRequestLists();
								var docno = page.executeFormattedSearch("select code from u_tkapprovalform where company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' and code='"+ getInput("code") +"'");
											if(docno != "" && getElementValueById("formAction")=="") {
												  showAjaxProcess();
												  setKey("keys",docno+"`0");
												  setElementValueById("formAction","e");
												  document.formData.submit();
												  return false;
												  hideAjaxProcess();
											}
							} else {
								setInput("u_date","");
								setInput("u_approverby","");
								page.statusbar.showError("Error retrieving Day. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("u_date","");
							setInput("u_approverby","");
							page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
							return false;
						}
				}
				break;
				case "u_typeofrequest":
				showRequestLists();
				break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSKiosk(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSKiosk(element,column,table,row) {
	return true;
}

function onElementChangeGPSKiosk(element,column,table,row) {
	return true;
}

function onElementClickGPSKiosk(element,column,table,row) {
switch(table) {
	case "T1":
		switch (column) {
			case "u_approved":
				if(getTableInput(table,"u_approved",row) == 1) {
					setTableInput(table,"u_denied",0,row);
				}
				break;
			case "u_denied":
				if(getTableInput(table,"u_denied",row) == 1) {
					setTableInput(table,"u_approved",0,row);
				}
				break;
		}
		break;
	default:
		break;
}				
	return true;
}

function onElementCFLGPSKiosk(Id) {
switch (Id) {
		case "df_u_empnameT1":		
			if (isInputEmpty("u_approverby")) return false;
			if (isInputEmpty("u_date")) return false;	
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSKiosk(Id,params) {
switch (Id) {
		case "df_u_empnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT a.name,a.code FROM u_premploymentinfo a WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby=name";
			break;
			}
	return params;
}

function onTableResetRowGPSKiosk(table) {
}

function onTableBeforeInsertRowGPSKiosk(table) {
switch(table){
		case "T1":
			if(getTableInput(table,"u_typeofrequest") == "-") {
				page.statusbar.showError("Type of Request is required.");
				return false;
				}
			if (isTableInputEmpty(table,"u_approverby")) return false;
		break;
	}
	return true;
}

function onTableAfterInsertRowGPSKiosk(table,row) {
}

function onTableBeforeUpdateRowGPSKiosk(table,row) {
switch(table){
		case "T1":
			if(getTableInput(table,"u_typeofrequest") == "-") {
				page.statusbar.showError("Type of Request is required.");
				return false;
				}
			if (isTableInputEmpty(table,"u_approverby")) return false;
		break;
	}
	return true;
}

function onTableAfterUpdateRowGPSKiosk(table,row) {
}

function onTableBeforeDeleteRowGPSKiosk(table,row) {
	return true;
}

function onTableDeleteRowGPSKiosk(table,row) {
}

function onTableBeforeSelectRowGPSKiosk(table,row) {
	return true;
}

function onTableSelectRowGPSKiosk(table,row) {
}

function addCodeGPSKiosk() {
var code="";
	if (getInput("u_date")!="") code += formatDateToDB(getInput("u_date")).replace(/-/g,"");
	if (getInput("u_approverby")!="") code += getInput("u_approverby").toUpperCase();
	if (getInput("u_profitcenter")!="") code += getInput("u_profitcenter").toUpperCase();
	if (getInput("u_typeofrequest")!=0) code += getInput("u_typeofrequest");
	setInput("code",code);	
	setInput("name",code);	
	
}

function showRequestLists1(){
	var result, data = new Array();
	showAjaxProcess();
	clearTable("T1",true);
	if (getInput("u_typeofrequest") == 1 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,'Regular OT' as u_typeofrequest,a.u_status FROM u_tkrequestformot a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND b.u_employstatus2 = '"+getGlobal("userid")+"' AND a.u_appdate <= '"+formatDateToDB(getInput("u_date"))+"' AND a.u_status = 1");
	} else if (getInput("u_typeofrequest") == 2 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,CONCAT(u_leavetype,' - ',u_leavereason) as u_typeofrequest,a.u_status FROM u_tkrequestformleave a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND b.u_employstatus2 = '"+getGlobal("userid")+"' AND a.u_appdate <= '"+formatDateToDB(getInput("u_date"))+"' AND a.u_status = 1 AND a.u_leavestatus = 'Successful'");
	} else if (getInput("u_typeofrequest") == 3 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,u_loantype as u_typeofrequest,a.u_status FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND b.u_employstatus2 = '"+getGlobal("userid")+"' AND a.u_appdate <= '"+formatDateToDB(getInput("u_date"))+"' AND a.u_status = 1");
	} else if (getInput("u_typeofrequest") == 4 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,'Time Adjustment' as u_typeofrequest,a.u_status FROM u_tkrequestformtimeadjustment a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND b.u_employstatus2 = '"+getGlobal("userid")+"' AND a.u_appdate <= '"+formatDateToDB(getInput("u_date"))+"' AND a.u_status = 1");
	} else if (getInput("u_typeofrequest") == 5 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,'Official Business' as u_typeofrequest,a.u_status FROM u_tkrequestformass a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND b.u_employstatus2 = '"+getGlobal("userid")+"' AND a.u_appdate <= '"+formatDateToDB(getInput("u_date"))+"' AND a.u_status = 1");
	} else if (getInput("u_typeofrequest") == 0 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,'Over Time Request' as u_typeofrequest,a.u_status FROM u_tkrequestformot WHERE company = '' AND branch = ''");
	}
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					data["u_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
					data["u_empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
					data["u_empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
					data["u_typeofrequest"] = result.childNodes.item(xxxi).getAttribute("u_typeofrequest");
					data["u_status"] = formatNumber(result.childNodes.item(xxxi).getAttribute("u_status"),"amount");
					data["u_codes"] = result.childNodes.item(xxxi).getAttribute("code");
					data["u_approved"] = 0;
					data["u_denied"] = 1;
					insertTableRowFromArray("T1",data);
						
					}
				}
			} else {
				hideAjaxProcess();
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
	hideAjaxProcess();
	return true;
}

function showRequestLists(){
	var result, data = new Array();
	showAjaxProcess();
	clearTable("T1",true);
	if (getInput("u_typeofrequest") == 1 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,CONCAT('Regular OT - ',u_otreason) as u_typeofrequest,CONCAT('OT Hrs :',ROUND(a.u_othrs,2),' OT Dates : ',DATE_FORMAT(a.u_otdate,'%m/%d/%y')) as remarks,a.u_status FROM u_tkrequestformot a INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_status = '"+getInput("u_approverby")+"' AND a.u_appdate = '"+formatDateToDB(getInput("u_date"))+"' AND c.u_employstatus2 = '"+getInput("name")+"'");
	} else if (getInput("u_typeofrequest") == 2 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,CONCAT(u_leavetype,' - ',u_leavereason) as u_typeofrequest,CONCAT('Leave Days :',ROUND(a.u_leavedays,2),' Leave Dates : ',DATE_FORMAT(a.u_leavefrom,'%m/%d/%y'),' and ',DATE_FORMAT(a.u_leaveto,'%m/%d/%y')) as remarks,a.u_status FROM u_tkrequestformleave a INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_status = '"+getInput("u_approverby")+"' AND a.u_appdate = '"+formatDateToDB(getInput("u_date"))+"' AND c.u_employstatus2 = '"+getInput("name")+"' AND a.u_leavestatus = 'Successful'");
	} else if (getInput("u_typeofrequest") == 3 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,CONCAT(u_loantype,' - ',u_loanreason) as u_typeofrequest,'' as remarks,a.u_status FROM u_tkrequestformloan a INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_status = '"+getInput("u_approverby")+"' AND a.u_appdate = '"+formatDateToDB(getInput("u_date"))+"' AND c.u_employstatus2 = '"+getInput("name")+"'");
	} else if (getInput("u_typeofrequest") == 4 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,CONCAT('Time Adjustment',' - ',u_tareason) as u_typeofrequest,'' as remarks,a.u_status FROM u_tkrequestformtimeadjustment a INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_status = '"+getInput("u_approverby")+"' AND a.u_appdate = '"+formatDateToDB(getInput("u_date"))+"' AND c.u_employstatus2 = '"+getInput("name")+"'");
	} else if (getInput("u_typeofrequest") == 5 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,CONCAT('Official Business',' - ',u_assreason) as u_typeofrequest,CONCAT('Work Hrs :',ROUND(a.u_tk_wd,2),' O.B. Dates : ',DATE_FORMAT(a.u_tkdate,'%m/%d/%y')) as remarks,a.u_status FROM u_tkrequestformass a INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.u_status = '"+getInput("u_approverby")+"' AND a.u_appdate = '"+formatDateToDB(getInput("u_date"))+"' AND c.u_employstatus2 = '"+getInput("name")+"'");
	} else if (getInput("u_typeofrequest") == 0 ) {
	result = page.executeFormattedQuery("SELECT a.code,a.u_appdate,a.u_empid,a.u_empname,'Over Time Request' as u_typeofrequest,a.u_status FROM u_tkrequestformot a WHERE company = '' AND branch = ''");
	}
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					data["u_date"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("u_appdate"));
					data["u_empid"] = result.childNodes.item(xxxi).getAttribute("u_empid");
					data["u_empname"] = result.childNodes.item(xxxi).getAttribute("u_empname");
					data["u_typeofrequest"] = result.childNodes.item(xxxi).getAttribute("u_typeofrequest");
					data["u_remarks"] = result.childNodes.item(xxxi).getAttribute("remarks");
					data["u_status"] = formatNumber(result.childNodes.item(xxxi).getAttribute("u_status"),"amount");
					data["u_codes"] = result.childNodes.item(xxxi).getAttribute("code");
					data["u_approved"] = 0;
					data["u_denied"] = 1;
					insertTableRowFromArray("T1",data);
					}
				}
			} else {
				hideAjaxProcess();
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
	hideAjaxProcess();
	return true;
}

