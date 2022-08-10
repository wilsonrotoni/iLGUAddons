// page events
//page.events.add.load('onPageLoadGPSKiosk');
//page.events.add.resize('onPageResizeGPSKiosk');
page.events.add.submit('onPageSubmitGPSKiosk');
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
//page.elements.events.add.click('onElementClickGPSKiosk');
//page.elements.events.add.cfl('onElementCFLGPSKiosk');
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
}

function onPageResizeGPSKiosk(width,height) {
}

function onPageSubmitGPSKiosk(action) {
	if (action=="a" || action=="sc") {
		if (getTableRowCount("T1")=="0") {
			page.statusbar.showError("Invalid No Record Data.. Try Again");	
			return false;
		}
	}
	return true;
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
				case "name":
				if (getInput("name")!="") {
						result = page.executeFormattedQuery("SELECT userid, username FROM users WHERE usertype = 'E' AND username = '"+getInput("name")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("code",result.childNodes.item(0).getAttribute("userid"));
								setInput("name",result.childNodes.item(0).getAttribute("username"));
								var docno = page.executeFormattedSearch("select code from u_tkrestrictusers where company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' and code = '"+ getInput("code") +"'");
								if(docno != "" && getElementValueById("formAction")=="") {
									  showAjaxProcess();
									  setKey("keys",docno+"`0");
									  setElementValueById("formAction","e");
									  document.formData.submit();
									  return false;
									  hideAjaxProcess();
								}
							} else {
								setInput("code","");
								setInput("name","");
								page.statusbar.showError("Error retrieving Employee. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("code","");
							setInput("name","");
							page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("code","");
						setInput("name","");
					}	
				break;
				case "u_username":
				if (getInput("u_username")!="") {
						result = page.executeFormattedQuery("SELECT userid, username FROM users WHERE usertype != 'E' AND username = '"+getInput("u_username")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_userid",result.childNodes.item(0).getAttribute("userid"));
								setInput("u_username",result.childNodes.item(0).getAttribute("username"));
							} else {
								setInput("u_userid","");
								setInput("u_username","");
								page.statusbar.showError("Error retrieving Employee. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("u_userid","");
							setInput("u_username","");
							page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_userid","");
						setInput("u_username","");
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
		case "df_u_username":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT username, userid FROM users WHERE usertype != 'E'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Username`User ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("70`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby=username";
			break;
		case "df_name":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT username, userid FROM users WHERE usertype = 'E'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Employee Name`Employee ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("70`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby=username";
			break;
			}
	return params;
}

function onTableResetRowGPSKiosk(table) {
}

function onTableBeforeInsertRowGPSKiosk(table) {
	switch(table){
		case "T1":
			if (isTableInputEmpty(table,"u_costcenter")) return false;
		break;
	}
	return true;
}

function onTableAfterInsertRowGPSKiosk(table,row) {
}

function onTableBeforeUpdateRowGPSKiosk(table,row) {
	switch(table){
		case "T1":
			if (isTableInputEmpty(table,"u_costcenter")) return false;
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

