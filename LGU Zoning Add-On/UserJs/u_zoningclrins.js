// page events
//page.events.add.load('onPageLoadGPSLGUZoning');
//page.events.add.resize('onPageResizeGPSLGUZoning');
page.events.add.submit('onPageSubmitGPSLGUZoning');
//page.events.add.cfl('onCFLGPSLGUZoning');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUZoning');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUZoning');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUZoning');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUZoning');
page.elements.events.add.validate('onElementValidateGPSLGUZoning');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUZoning');
//page.elements.events.add.changing('onElementChangingGPSLGUZoning');
//page.elements.events.add.change('onElementChangeGPSLGUZoning');
//page.elements.events.add.click('onElementClickGPSLGUZoning');
//page.elements.events.add.cfl('onElementCFLGPSLGUZoning');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUZoning');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUZoning');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUZoning');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUZoning');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUZoning');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUZoning');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUZoning');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUZoning');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUZoning');
//page.tables.events.add.select('onTableSelectRowGPSLGUZoning');

function onPageLoadGPSLGUZoning() {
}

function onPageResizeGPSLGUZoning(width,height) {
}

function onPageSubmitGPSLGUZoning(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_issdate")) return false;
		if (isInputEmpty("u_appno")) return false;
		if (isInputEmpty("u_inspector")) return false;
	}
	return true;
}

function onCFLGPSLGUZoning(Id) {
	return true;
}

function onCFLGetParamsGPSLGUZoning(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUZoning() {
}

function onElementFocusGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUZoning(element,event,column,table,row) {
}

function onElementValidateGPSLGUZoning(element,column,table,row) {
	var result;
	switch (table) {
		default:
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("SELECT u_docdate,u_bpno,u_businessname,CONCAT(u_baddressno,u_bbldgno,u_bunitno,u_bfloorno, u_bbldgname,u_bblock, u_blotno,u_bphaseno, u_bstreet, u_bvillage,u_bbrgy,u_bcity,u_bprovince ) AS u_address FROM ( select u_docdate, u_bpno, u_businessname,  IF(IFNULL(a.u_baddressno,'')='','',CONCAT(a.u_baddressno,', ')) AS u_baddressno, IF(IFNULL(a.u_bbldgno,'') = '','',CONCAT(a.u_bbldgno,' ')) AS u_bbldgno,IF(IFNULL(a.u_bunitno,'') = '','',CONCAT(a.u_bunitno,', ')) AS u_bunitno, IF(IFNULL(a.u_bfloorno,'') = '','',CONCAT(a.u_bfloorno,', ')) AS u_bfloorno,IF(IFNULL(a.u_bbldgname,'') = '','',CONCAT(a.u_bbldgname,', ')) AS u_bbldgname, IF(IFNULL(a.u_bblock,'')='','',CONCAT('BLK ',a.u_bblock,' ')) AS u_bblock, IF(IFNULL(a.u_blotno,'')='','',CONCAT('LOT ',a.u_blotno,' ')) AS u_blotno, IF(IFNULL(a.u_bphaseno,'')='','',CONCAT('PHASE ',a.u_bphaseno,', ')) AS u_bphaseno, IF(IFNULL(a.u_bstreet,'')='','',CONCAT(a.u_bstreet,', ')) AS u_bstreet, IF(IFNULL(a.u_bvillage,'')='','',CONCAT(a.u_bvillage,', ')) AS u_bvillage, IF(IFNULL(a.u_bbrgy,'')='','',CONCAT(a.u_bbrgy,', ')) AS u_bbrgy, IF(IFNULL(a.u_bcity,'')='','',CONCAT(a.u_bcity,', ')) AS u_bcity, IF(IFNULL(a.u_bprovince,'')='','',CONCAT(a.u_bprovince,' ')) AS u_bprovince from u_zoningclrapps a where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"' and u_insno = '')  AS X");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
								setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
								setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
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

function onElementGetValidateParamsGPSLGUZoning(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUZoning(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUZoning(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_businessname, u_acctno from u_zoningclrapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_insno = '' and docstatus not in ('Retired')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No.`Name of Business`Account No")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`35`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSLGUZoning(table) {
}

function onTableBeforeInsertRowGPSLGUZoning(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUZoning(table,row) {
}

function onTableBeforeUpdateRowGPSLGUZoning(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUZoning(table,row) {
}

function onTableBeforeDeleteRowGPSLGUZoning(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUZoning(table,row) {
}

function onTableBeforeSelectRowGPSLGUZoning(table,row) {
	return true;
}

function onTableSelectRowGPSLGUZoning(table,row) {
}

function recommendforApprovalGPSLGUZoning() {
	setInput("u_recommendbystatus","For Approval");
	formSubmit();
}

function recommenddisapprovedGPSLGUZoning() {
	setInput("u_recommendbystatus","Disapproved");
	formSubmit();
}

function approvedGPSLGUZoning() {
	setInput("u_dispositionbystatus","Approved");
	formSubmit();
}

function disapprovedGPSLGUZoning() {
	setInput("u_dispositionbystatus","Disapproved");
	formSubmit();
}
