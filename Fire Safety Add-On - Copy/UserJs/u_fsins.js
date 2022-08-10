// page events
//page.events.add.load('onPageLoadGPSFireSafety');
//page.events.add.resize('onPageResizeGPSFireSafety');
//page.events.add.submit('onPageSubmitGPSFireSafety');
//page.events.add.cfl('onCFLGPSFireSafety');
//page.events.add.cflgetparams('onCFLGetParamsGPSFireSafety');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFireSafety');

// element events
//page.elements.events.add.focus('onElementFocusGPSFireSafety');
//page.elements.events.add.keydown('onElementKeyDownGPSFireSafety');
page.elements.events.add.validate('onElementValidateGPSFireSafety');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFireSafety');
//page.elements.events.add.changing('onElementChangingGPSFireSafety');
//page.elements.events.add.change('onElementChangeGPSFireSafety');
//page.elements.events.add.click('onElementClickGPSFireSafety');
//page.elements.events.add.cfl('onElementCFLGPSFireSafety');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFireSafety');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFireSafety');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFireSafety');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFireSafety');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFireSafety');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFireSafety');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFireSafety');
//page.tables.events.add.delete('onTableDeleteRowGPSFireSafety');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFireSafety');
//page.tables.events.add.select('onTableSelectRowGPSFireSafety');

function onPageLoadGPSFireSafety() {
}

function onPageResizeGPSFireSafety(width,height) {
}

function onPageSubmitGPSFireSafety(action) {
	return true;
}

function onCFLGPSFireSafety(Id) {
	return true;
}

function onCFLGetParamsGPSFireSafety(Id,params) {
	return params;
}

function onTaskBarLoadGPSFireSafety() {
}

function onElementFocusGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSFireSafety(element,event,column,table,row) {
}

function onElementValidateGPSFireSafety(element,column,table,row) {
	var result;
	switch (table) {
		default:
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_docdate, u_apptype, u_bpno, u_businessname from u_fsapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
								setInput("u_gi_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
								setInput("u_gi_lstbpno",result.childNodes.item(0).getAttribute("u_bpno"));
								switch (result.childNodes.item(0).getAttribute("u_apptype")) {
									case "Occupancy Permit":
										setInput("u_noic_afop",1);
										setInput("u_noic_afbp",0);
										break;
									case "New Business Permit":
									case "Renewal of Business Permit":
										setInput("u_noic_afbp",1);
										setInput("u_noic_afop",0);
										break;
								}
							} else {
								setInput("u_appdate","");
								page.statusbar.showError("Invalid Application No.");	
								return false;
							}
						} else {
							setInput("u_appdate","");
							page.statusbar.showError("Error retrieving application record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_appdate","");
					}
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSFireSafety(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementChangeGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementClickGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementCFLGPSFireSafety(element) {
	return true;
}

function onElementCFLGetParamsGPSFireSafety(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_apptype, u_bpno from u_fsapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='P'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No.`Application For`Business Permit")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`20`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSFireSafety(table) {
}

function onTableBeforeInsertRowGPSFireSafety(table) {
	return true;
}

function onTableAfterInsertRowGPSFireSafety(table,row) {
}

function onTableBeforeUpdateRowGPSFireSafety(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFireSafety(table,row) {
}

function onTableBeforeDeleteRowGPSFireSafety(table,row) {
	return true;
}

function onTableDeleteRowGPSFireSafety(table,row) {
}

function onTableBeforeSelectRowGPSFireSafety(table,row) {
	return true;
}

function onTableSelectRowGPSFireSafety(table,row) {
}

function recommendforApprovalGPSFireSafety() {
	setInput("u_recommendbystatus","For Approval");
	formSubmit();
}

function recommenddisapprovedGPSFireSafety() {
	setInput("u_recommendbystatus","Disapproved");
	formSubmit();
}

function approvedGPSFireSafety() {
	setInput("u_dispositionbystatus","Approved");
	formSubmit();
}

function disapprovedGPSFireSafety() {
	setInput("u_dispositionbystatus","Disapproved");
	formSubmit();
}
