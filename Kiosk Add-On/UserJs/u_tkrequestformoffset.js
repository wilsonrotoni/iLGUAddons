// page events
page.events.add.load('onPageLoadGPSKiosk');
//page.events.add.resize('onPageResizeGPSKiosk');
page.events.add.submit('onPageSubmitGPSKiosk');
page.events.add.submitreturn('onPageSubmitReturnGPSKiosk');
//page.events.add.cfl('onCFLGPSKiosk');
page.events.add.cflgetparams('onCFLGetParamsGPSKiosk');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSKiosk');

// element events
//page.elements.events.add.focus('onElementFocusGPSKiosk');
//page.elements.events.add.keydown('onElementKeyDownGPSKiosk');
page.elements.events.add.validate('onElementValidateGPSKiosk');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSKiosk');
//page.elements.events.add.changing('onElementChangingGPSKiosk');
//page.elements.events.add.change('onElementChangeGPSKiosk');
//page.elements.events.add.click('onElementClickGPSKiosk');
//page.elements.events.add.cfl('onElementCFLGPSKiosk');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSKiosk');

// table events
//page.tables.events.add.reset('onTableResetRowGPSKiosk');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSKiosk');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSKiosk');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSKiosk');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSKiosk');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSKiosk');
page.tables.events.add.delete('onTableDeleteRowGPSKiosk');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSKiosk');
//page.tables.events.add.select('onTableSelectRowGPSKiosk');

function onPageLoadGPSKiosk() {
	if(getInput("code") == "XXXXX") {
		alert("SESSION expired.. system autolog-out..");
		logOut();
	}
}

function onPageResizeGPSKiosk(width,height) {
}

function onPageSubmitGPSKiosk(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_empname")) return false;
		if (isInputEmpty("u_appdate")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
		if (isInputEmpty("u_assreason")) return false;
		if (getTableRowCount("T1")=="0") {
			page.statusbar.showError("Invalid No Record Data.. Try Again");	
			return false;
		}
		
		if(getInput("u_status") != -1) {
				var allowed_balance = page.executeFormattedSearch("SELECT u_offset_convert_ot FROM u_prglobalsetting");
				var remainingbalance = page.executeFormattedSearch("SELECT SUM(IFNULL(x.balance,0)) as balance FROM (SELECT SUM(b.u_hours) as balance FROM u_prpayslipform a INNER JOIN u_prpayslipformovertime b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.u_empid = '"+getGlobal("userid")+"' UNION ALL SELECT SUM(-1*b.u_hours) as balance FROM u_tkrequestformoffset a INNER JOIN u_tkrequestformoffsetlist b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.u_empid = '"+getGlobal("userid")+"'AND a.u_status = 1) as x");
				if(allowed_balance == 1 ) {
					if(remainingbalance-getInputNumeric("u_tk_wd") < 0) {
						page.statusbar.showError("No Available Off-set for the Request.. Remaining Balance [ '"+remainingbalance+"' ]");		
						return false;
					}
				}
				
			}
	} else if (action=="ccn") {
		if (window.confirm("Are you Sure. Continue?")==false)	return false;
		setInput("u_status",-1);
		formSubmit('sc');
	} else if (action=="ah") {
		showPopupFrame("popupFrameApprovedHistory",true);
		return false;
	} else if (action=="d") {
		if (window.confirm("Are you Sure. Continue?")==false)	return false;
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

function onCFLGetParamsGPSKiosk(elementId,params) {
	switch (elementId) {
		case "find":
			params["params"] += ";-WHERE:u_empid = '"+getGlobal("userid")+"'";	
			break;
	}
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
			case "u_empname":
					if (getInput("u_empname")!="") {
						result = page.executeFormattedQuery("SELECT a.name,a.code FROM u_premploymentinfo a LEFT OUTER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND b.u_branch = '"+getInput("u_profitcenter")+"' AND a.name = '"+getInput("u_empname")+"' AND a.u_currentstatus NOT IN('IA','')");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_empid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_empname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_empid","");
								setInput("u_empname","");
								page.statusbar.showError("Error retrieving Employee. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("u_empid","");
							setInput("u_empname","");
							page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_empid","");
						setInput("u_empname","");
					}
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
	return true;
}

function onElementCFLGPSKiosk(element) {
	return true;
}

function onElementCFLGetParamsGPSKiosk(Id,params) {
switch (Id) {
		case "df_u_empname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT a.name,a.code FROM u_premploymentinfo a LEFT OUTER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND b.u_branch = '"+getInput("u_profitcenter")+"' AND a.u_currentstatus NOT IN('IA','')")); 
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
			if (isTableInputEmpty(table,"u_date_filter")) return false;
			if (isTableInputEmpty(table,"u_type_filter")) return false;
			
			if(getTableInput(table,"u_hours") == 0) {
				page.statusbar.showError("Invalid Zero Total Hrs.. Try Again");	
				return false;
			}
			
			var checklockec = page.executeFormattedSearch("SELECT b.u_kiosklocked FROM u_premploymentinfo a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"' AND '"+formatDateToDB(getTableInput(table,"u_date_filter"))+"' BETWEEN b.u_from_date AND b.u_to_date");
			if(checklockec == "LOCK") {
				page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
				return false;
			}
			var workinghrs = page.executeFormattedSearch("SELECT a.u_whpd FROM u_premploymentinfo a WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND a.code = '"+getGlobal("userid")+"' AND a.u_currentstatus NOT IN('IA','')");
			if (getTableInput(table,"u_type_filter")=="DAYS") {
				if(workinghrs != getTableInputNumeric(table,"u_hours")) {
					page.statusbar.showError("Error Hrs is Not Equal in your Working Hrs");		
					return false;
				}
			}
		break;
	}
	return true;
}

function onTableAfterInsertRowGPSKiosk(table,row) {
switch(table){
	case "T1":
		computeTotalHrsGPSKiosk();
	break;
	}
}

function onTableBeforeUpdateRowGPSKiosk(table,row) {
switch(table){
		case "T1":
			if (isTableInputEmpty(table,"u_date_filter")) return false;
			if (isTableInputEmpty(table,"u_type_filter")) return false;
			
			if(getTableInput(table,"u_hours") == 0) {
				page.statusbar.showError("Invalid Zero Total Hrs.. Try Again");	
				return false;
			}
			
			var checklockec = page.executeFormattedSearch("SELECT b.u_kiosklocked FROM u_premploymentinfo a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"' AND '"+formatDateToDB(getTableInput(table,"u_date_filter"))+"' BETWEEN b.u_from_date AND b.u_to_date");
			if(checklockec == "LOCK") {
				page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
				return false;
			}
			var workinghrs = page.executeFormattedSearch("SELECT a.u_whpd FROM u_premploymentinfo a WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND a.code = '"+getGlobal("userid")+"' AND a.u_currentstatus NOT IN('IA','')");
			if (getTableInput(table,"u_type_filter")=="DAYS") {
				if(workinghrs != getTableInputNumeric(table,"u_hours")) {
					page.statusbar.showError("Error Hrs is Not Equal in your Working Hrs");		
					return false;
				}
			}
		break;
	}
	return true;
}

function onTableAfterUpdateRowGPSKiosk(table,row) {
switch(table){
	case "T1":
		computeTotalHrsGPSKiosk();
	break;
	}
}

function onTableBeforeDeleteRowGPSKiosk(table,row) {
	return true;
}

function onTableDeleteRowGPSKiosk(table,row) {
switch(table){
	case "T1":
		computeTotalHrsGPSKiosk();
	break;
	}
}

function onTableBeforeSelectRowGPSKiosk(table,row) {
	return true;
}

function onTableSelectRowGPSKiosk(table,row) {
}

function computeTotalHrsGPSKiosk() {
	var rc1 =  getTableRowCount("T1");
	var totalhrs = 0;
	
	for (var i = 1; i <= rc1; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalhrs += getTableInputNumeric("T1","u_hours",i);
		}
	}
	
	setInputAmount("u_tk_wd",totalhrs);
}