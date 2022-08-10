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
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSKiosk');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSKiosk');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSKiosk');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSKiosk');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSKiosk');
//page.tables.events.add.delete('onTableDeleteRowGPSKiosk');
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
		if (isInputEmpty("u_otdate")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
		if (isInputEmpty("u_otfromtime	")) return false;
		if (isInputEmpty("u_ottotime")) return false;
		if (isInputEmpty("u_otreason")) return false;
		var checklockec = page.executeFormattedSearch("SELECT b.u_kiosklocked FROM u_premploymentinfo a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"' AND '"+formatDateToDB(getInput("u_otdate"))+"' BETWEEN b.u_from_date AND b.u_to_date");
		if(checklockec == "LOCK") {
				page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
				return false;
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
				case "u_profitcenter":
					if (getInput("u_profitcenter")!="") {
						result = page.executeFormattedQuery("SELECT a.code,a.name FROM u_premploymentinfo a LEFT OUTER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND b.u_branch = '"+getInput("u_profitcenter")+"' AND a.code = '"+getGlobal("userid")+"' AND a.u_currentstatus NOT IN('IA','')");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_empid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_empname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_empname","");
								page.statusbar.showError("Error retrieving Name. Try Again");		
								return false;
							}
						} else {
							setInput("u_empname","");
							page.statusbar.showError("Error retrieving Name. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_empid","");
						setInput("u_empname","");
					}
				break;
				case "u_otfromtime":
				case "u_ottotime":
				case "u_otdate":
					if (getInput("u_otfromtime")!="" && getInput("u_ottotime")!="" && getInput("u_otdate")!="") {
					var daysFrom = formatDateToDB(getInput("u_otdate"))+" "+formatTimeToDB(getInput("u_otfromtime"));
					var daysTo = formatDateToDB(getInput("u_otdate"))+" "+formatTimeToDB(getInput("u_ottotime"));
					var totalothrs = page.executeFormattedSearch("SELECT TIMESTAMPDIFF(MINUTE,'"+daysFrom+"','"+daysTo+"')");
					
					if(totalothrs >= 0) {
						result = page.executeFormattedQuery("SELECT TIMESTAMPDIFF(MINUTE,'"+daysFrom+"','"+daysTo+"') as code,TIMESTAMPDIFF(HOUR,'"+daysFrom+"','"+daysTo+"') as hrs,(TIMESTAMPDIFF(MINUTE,'"+daysFrom+"','"+daysTo+"')-(TIMESTAMPDIFF(HOUR,'"+daysFrom+"','"+daysTo+"')*60)) as mins");
					} else {
						result = page.executeFormattedQuery("SELECT TIMESTAMPDIFF(MINUTE,'"+daysFrom+"',DATE_ADD('"+daysTo+"',INTERVAL 1 DAY)) as code,TIMESTAMPDIFF(HOUR,'"+daysFrom+"',DATE_ADD('"+daysTo+"',INTERVAL 1 DAY)) as hrs,(TIMESTAMPDIFF(MINUTE,'"+daysFrom+"',DATE_ADD('"+daysTo+"',INTERVAL 1 DAY))-(TIMESTAMPDIFF(HOUR,'"+daysFrom+"',DATE_ADD('"+daysTo+"',INTERVAL 1 DAY))*60)) as mins");	
					}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_othrs",result.childNodes.item(0).getAttribute("code")/60);
								setInputAmount("u_othours",result.childNodes.item(0).getAttribute("hrs"));
								setInputAmount("u_otmins",result.childNodes.item(0).getAttribute("mins"));
							} else {
								setInput("u_othrs",0);
								setInput("u_othours",0);
								setInput("u_otmins",0);
								page.statusbar.showError("Error retrieving Day. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("u_othrs",0);
							setInput("u_othours",0);
							setInput("u_otmins",0);
							page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
				break;
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
	return true;
}

function onTableAfterInsertRowGPSKiosk(table,row) {
}

function onTableBeforeUpdateRowGPSKiosk(table,row) {
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