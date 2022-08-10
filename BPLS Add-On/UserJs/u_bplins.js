// page events
//page.events.add.load('onPageLoadGPSLGUBuilding');
//page.events.add.resize('onPageResizeGPSLGUBuilding');
page.events.add.submit('onPageSubmitGPSLGUBuilding');
//page.events.add.cfl('onCFLGPSLGUBuilding');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUBuilding');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUBuilding');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUBuilding');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUBuilding');
page.elements.events.add.validate('onElementValidateGPSLGUBuilding');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUBuilding');
//page.elements.events.add.changing('onElementChangingGPSLGUBuilding');
//page.elements.events.add.change('onElementChangeGPSLGUBuilding');
//page.elements.events.add.click('onElementClickGPSLGUBuilding');
//page.elements.events.add.cfl('onElementCFLGPSLGUBuilding');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUBuilding');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUBuilding');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUBuilding');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUBuilding');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUBuilding');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUBuilding');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUBuilding');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUBuilding');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUBuilding');
//page.tables.events.add.select('onTableSelectRowGPSLGUBuilding');

function onPageLoadGPSLGUBuilding() {
}

function onPageResizeGPSLGUBuilding(width,height) {
}

function onPageSubmitGPSLGUBuilding(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_issdate")) return false;
		if (isInputEmpty("u_appno")) return false;
		if (isInputEmpty("u_inspector")) return false;
	}
	return true;
}

function onCFLGPSLGUBuilding(Id) {
	return true;
}

function onCFLGetParamsGPSLGUBuilding(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUBuilding() {
}

function onElementFocusGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUBuilding(element,event,column,table,row) {
}

function onElementValidateGPSLGUBuilding(element,column,table,row) {
	var result;
	switch (table) {
		default:
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("SELECT u_appdate,u_businessname,CONCAT(u_baddressno,u_bbldgno,u_bunitno,u_bfloorno, u_bbldgname,u_bblock, u_blotno,u_bphaseno, u_bstreet, u_bvillage,u_bbrgy,u_bcity,u_bprovince ) AS u_address FROM ( select u_appdate, u_businessname,  IF(IFNULL(a.u_baddressno,'')='','',CONCAT(a.u_baddressno,', ')) AS u_baddressno, IF(IFNULL(a.u_bbldgno,'') = '','',CONCAT(a.u_bbldgno,' ')) AS u_bbldgno,IF(IFNULL(a.u_bunitno,'') = '','',CONCAT(a.u_bunitno,', ')) AS u_bunitno, IF(IFNULL(a.u_bfloorno,'') = '','',CONCAT(a.u_bfloorno,', ')) AS u_bfloorno,IF(IFNULL(a.u_bbldgname,'') = '','',CONCAT(a.u_bbldgname,', ')) AS u_bbldgname, IF(IFNULL(a.u_bblock,'')='','',CONCAT('BLK ',a.u_bblock,' ')) AS u_bblock, IF(IFNULL(a.u_blotno,'')='','',CONCAT('LOT ',a.u_blotno,' ')) AS u_blotno, IF(IFNULL(a.u_bphaseno,'')='','',CONCAT('PHASE ',a.u_bphaseno,', ')) AS u_bphaseno, IF(IFNULL(a.u_bstreet,'')='','',CONCAT(a.u_bstreet,', ')) AS u_bstreet, IF(IFNULL(a.u_bvillage,'')='','',CONCAT(a.u_bvillage,', ')) AS u_bvillage, IF(IFNULL(a.u_bbrgy,'')='','',CONCAT(a.u_bbrgy,', ')) AS u_bbrgy, IF(IFNULL(a.u_bcity,'')='','',CONCAT(a.u_bcity,', ')) AS u_bcity, IF(IFNULL(a.u_bprovince,'')='','',CONCAT(a.u_bprovince,' ')) AS u_bprovince from u_bplapps a where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"' and u_insno = '')  AS X");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_appdate")));
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

function onElementGetValidateParamsGPSLGUBuilding(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUBuilding(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUBuilding(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_businessname, u_appno from u_bplapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_insno=''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No.`Name of Business`Account No")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`35`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSLGUBuilding(table) {
}

function onTableBeforeInsertRowGPSLGUBuilding(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUBuilding(table,row) {
}

function onTableBeforeUpdateRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUBuilding(table,row) {
}

function onTableBeforeDeleteRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUBuilding(table,row) {
}

function onTableBeforeSelectRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableSelectRowGPSLGUBuilding(table,row) {
}

function recommendforApprovalGPSLGUBuilding() {
	setInput("u_recommendbystatus","For Approval");
	formSubmit();
}

function recommenddisapprovedGPSLGUBuilding() {
	setInput("u_recommendbystatus","Disapproved");
	formSubmit();
}

function approvedGPSLGUBuilding() {
	setInput("u_dispositionbystatus","Approved");
	formSubmit();
}

function disapprovedGPSLGUBuilding() {
	setInput("u_dispositionbystatus","Disapproved");
	formSubmit();
}
