// page events
//page.events.add.load('onPageLoadGPSKiosk');
//page.events.add.resize('onPageResizeGPSKiosk');
//page.events.add.submit('onPageSubmitGPSKiosk');
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
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSKiosk');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSKiosk');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSKiosk');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSKiosk');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSKiosk');
//page.tables.events.add.delete('onTableDeleteRowGPSKiosk');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSKiosk');
page.tables.events.add.select('onTableSelectRowGPSKiosk');

function onPageLoadGPSKiosk() {
}

function onPageResizeGPSKiosk(width,height) {
}

function onPageSubmitGPSKiosk(action) {
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
	case "T1":
		switch(column) {
			case "u_stagename":
				if (getTableInput(table,"u_stagename",row)!="") {
						result = page.executeFormattedQuery("SELECT userid,username FROM users WHERE userid = '"+getTableInput(table,"u_stagename",row)+"'");
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_stagename",result.childNodes.item(0).getAttribute("userid"),row);
								setTableInput(table,"u_stagedesc",result.childNodes.item(0).getAttribute("username"),row);
							} else {
								setTableInput(table,"u_stagename","",row);
								page.statusbar.showError("Invalid Employee ID");	
								return false;
							}
						} else {
							setTableInput(table,"u_stagename","",row);
							page.statusbar.showError("Error retrieving ID.. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_stagename","",row);
						setTableInput(table,"u_stagedesc","",row);
					}
				break;
			}
		break;
		default:
			switch(column) {
				case "code":
					var docno = page.executeFormattedSearch("select code from u_tkapproverfile where company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' and code='"+ getInput("code") +"'");
						if(docno != "" && getElementValueById("formAction")=="") {
							  showAjaxProcess();
							  setKey("keys",docno+"`0");
							  setElementValueById("formAction","e");
							  document.formData.submit();
							  return false;
							  hideAjaxProcess();
						}
				break;
				case "u_noofapp":
					showApproverLists();
				break;
				case "u_admin":
					if (getInput("u_admin")!="") {
						result = page.executeFormattedQuery("SELECT userid,username FROM users WHERE userid = '"+getInput("u_admin")+"' AND usertype = ''");
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_admin",result.childNodes.item(0).getAttribute("userid"));
								setInput("u_adminname",result.childNodes.item(0).getAttribute("username"));
							} else {
								setInput("u_admin","");
								page.statusbar.showError("Invalid Employee Admin");	
								return false;
							}
						} else {
							setInput("u_admin","");
							page.statusbar.showError("Error retrieving Admins.. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_admin","");
						setInput("u_adminname","");
					}
				break;
				case "u_adminname":
					if (getInput("u_adminname")!="") {
						result = page.executeFormattedQuery("SELECT userid,username FROM users WHERE username = '"+getInput("u_adminname")+"' AND usertype = ''");
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_admin",result.childNodes.item(0).getAttribute("userid"));
								setInput("u_adminname",result.childNodes.item(0).getAttribute("username"));
							} else {
								setInput("u_adminname","");
								page.statusbar.showError("Invalid Employee Admin");	
								return false;
							}
						} else {
							setInput("u_adminname","");
							page.statusbar.showError("Error retrieving Admins.. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_admin","");
						setInput("u_adminname","");
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
		case "df_u_admin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT userid,username FROM users WHERE usertype = ''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("User No.`User Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby=userid";
			break;
		case "df_u_adminname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT username,userid FROM users WHERE usertype = ''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("User Name`User No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby=username";
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
var params = new Array();
	switch (table) {
		case "T1":
				params["focus"] = false;
				if (elementFocused.substring(0,16)=="df_u_stagenameT1") {
					focusTableInput(table,"u_stagename",row);
				}
			break;
	}
	return params;
}

function showApproverLists(){
	var result, data = new Array();
	var total = 0;
	showAjaxProcess();
	clearTable("T1",true);
	total = getInputNumeric("u_noofapp");
	for (xxxi = 0; xxxi < total; xxxi++) {
			data["u_stageno"] = xxxi+1;
			data["u_stagename"] = "";
			data["u_valid"] = 0;
			insertTableRowFromArray("T1",data);
		}
	hideAjaxProcess();
	return true;
}