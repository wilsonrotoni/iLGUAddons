// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
//page.events.add.submit('onPageSubmitGPSHIS');
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
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
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
	switch (column) {
		case "code":
			setTableInput(table,"name",getTableInput(table,"code"));
			break;
		case "u_street":
		case "u_zip":
			setAddressGPSHIS(column);
			break;
		case "u_barangay":
			if (getTableInput(table,column)!="") {
				result = page.executeFormattedQuery("select code from u_hisaddrbrgys where code like '"+getTableInput(table,column)+"%'");	 
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setTableInput(table,column,result.childNodes.item(0).getAttribute("code"));
					} else {
						setTableInput(table,column,"");
						page.statusbar.showError("Invalid Barangay.");	
						return false;
					}
				} else {
					setTableInput(table,column,"");
					page.statusbar.showError("Error retrieving Barangay. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			}
			setAddressGPSHIS(column);
			break;
		case "u_city":
			if (getTableInput(table,column)!="") {
				result = page.executeFormattedQuery("select code from u_hisaddrtowncities where code like '"+getTableInput(table,column)+"%'");	 
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setTableInput(table,column,result.childNodes.item(0).getAttribute("code"));
					} else {
						setTableInput(table,column,"");
						page.statusbar.showError("Invalid Town/City.");	
						return false;
					}
				} else {
					setTableInput(table,column,"");
					page.statusbar.showError("Error retrieving Town/City. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			}
			setAddressGPSHIS(column);
			break;
		case "u_province":
			if (getTableInput(table,column)!="") {
				result = page.executeFormattedQuery("select provincename from provinces where provincename like '"+getTableInput(table,column)+"%'");	 
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setTableInput(table,column,result.childNodes.item(0).getAttribute("provincename"));
					} else {
						setTableInput(table,column,"");
						page.statusbar.showError("Invalid State/Province.");	
						return false;
					}
				} else {
					setTableInput(table,column,"");
					page.statusbar.showError("Error retrieving State/Province. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			}
			setAddressGPSHIS(column);
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
		case "df_u_barangayT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisaddrbrgys")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisaddrbrgys"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_cityT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisaddrtowncities")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisaddrtowncities"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_provinceT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select provincename from provinces")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			enableTableInput(table,"code");
			focusTableInput(table,"code");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_street")) return false;
			if (isTableInputEmpty(table,"u_city")) return false;
			if (isTableInputEmpty(table,"u_province")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_street")) return false;
			if (isTableInputEmpty(table,"u_city")) return false;
			if (isTableInputEmpty(table,"u_province")) return false;
			break;
	}
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

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getTableRowStatus(table,row)=="E") {
				disableTableInput(table,"code");
				focusTableInput(table,"u_street");
			}
			break;
	}
}


function setAddressGPSHIS() {
	var address="";
	address = getTableInput("T1","u_street");
	if (getTableInput("T1","u_barangay")!="") address += ", " + getTableInput("T1","u_barangay");
	if (getTableInput("T1","u_city")!="") address += ", " + getTableInput("T1","u_city");
	if (getTableInput("T1","u_province")!="") address += ", " + getTableInput("T1","u_province");
	if (getTableInput("T1","u_zip")!="") address += ", " + getTableInput("T1","u_zip");
	setTableInput("T1","u_address",address);
}
