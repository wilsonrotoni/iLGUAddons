// page events
page.events.add.load('onPageLoadGPSHIS');
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
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
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
	if (getVar("formType")=="lnkbtn") page.resize(0,310,true);
	focusInput("u_empid");
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_empid")) return false;
		if (isInputEmpty("u_empname")) return false;
		if (isInputNegative("u_amount")) return false;
		if (isInputEmpty("u_purpose")) return false;
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
		default:
			switch (column) {
				case "u_empid":
				case "u_empname":
					if (getInput(column)!="") {
						if (column=="u_empid") {
							result = page.executeFormattedQuery("select a.custno, a.custname from customers a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.isvalid=1 and a.custno = '"+getInput(column)+"'");	
						} else {
							result = page.executeFormattedQuery("select a.custno, a.custname from customers a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.isvalid=1 and a.custname like '"+getInput(column)+"%'");	
						}
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_empid",result.childNodes.item(0).getAttribute("custno"));
								setTableInput(table,"u_empname",result.childNodes.item(0).getAttribute("custname"));
							} else {
								setTableInput(table,"u_empid","");
								setTableInput(table,"u_empname","");
								page.statusbar.showError("Invalid Employee No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_empid","");
							setTableInput(table,"u_empname","");
							page.statusbar.showError("Error retrieving Employee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_empid","");
						setTableInput(table,"u_empname","");
					}					
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
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_empid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno, a.custname  from customers a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.custgroup='7' and a.isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Employee No.`Employee Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_empname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custname, a.custno  from customers a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.custgroup='7' and a.isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Employee Name`Employee No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
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
}

