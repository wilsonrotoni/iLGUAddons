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
page.elements.events.add.changing('onElementChangingGPSHIS');
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
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
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
	var data = new Array();
	switch (table) {
		default:
			switch(column) {
				case "u_mobileno":
				case "u_custname":
					if (getTableInput(table,column)!="") {
						if (column=="u_mobileno") result = page.executeFormattedQuery("select u_contactno, name, u_contactnetwork from u_customers where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_contactno = '"+getTableInput(table,column)+"' and u_contactno<>''");	
						else result = page.executeFormattedQuery("select u_contactno, name, u_contactnetwork from u_customers where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and name like '"+getTableInput(table,column)+"%' and u_contactno<>''");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_mobileno",result.childNodes.item(0).getAttribute("u_contactno"));
								setTableInput(table,"u_custname",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_network",result.childNodes.item(0).getAttribute("u_contactnetwork"));
							} else {
								if (column=="u_mobileno") {	
									if (getTableInput(table,column).length!=11 || getTableInput(table,column).substr(0,2)!="09") {
										page.statusbar.showError("Contact No. must be 11 digits and must start with 09");
										return false;
									}
									setTableInput(table,"u_custname","");
									setTableInput(table,"u_network","");
								} 
								//setTableInput(table,"u_mobileno","");
								//setTableInput(table,"u_custname","");
								//setTableInput(table,"u_network","");
								//page.statusbar.showError("Invalid Account No.");	
							}
						} else {
							setTableInput(table,"u_mobileno","");
							setTableInput(table,"u_custname","");
							setTableInput(table,"u_network","");
							page.statusbar.showError("Error retrieving customer record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_mobileno","");
						setTableInput(table,"u_custname","");
						setTableInput(table,"u_network","");
					}
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	var data = new Array();
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_contacts":
					switch (getInput("u_contacts")) {
						case "Active In-Patients":
							clearTable("T1",true);
							showAjaxProcess();
							if (getInput("u_contacts")=="Active In-Patients") var result = page.executeFormattedQuery("select a.u_smsmobileno, a.u_patientid, a.u_patientname, a.u_smsnetwork from u_hisips a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docstatus='Active' and a.u_smsnetwork<>'' and length(a.u_smsmobileno)=11 and a.u_smsmobileno REGEXP '^[0-9]+$' and a.u_smsmobileno like '09%' group by a.u_smsmobileno order by a.u_patientname");
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									for (var iii=0; iii<result.childNodes.length; iii++) {
										data["u_selected"] = 1;
										data["u_mobileno"] = result.childNodes.item(iii).getAttribute("u_smsmobileno");
										data["u_custno"] = result.childNodes.item(iii).getAttribute("u_patientid");
										data["u_custname"] = result.childNodes.item(iii).getAttribute("u_patientname");
										data["u_network"] = result.childNodes.item(iii).getAttribute("u_smsnetwork");
										insertTableRowFromArray("T1",data);
									}
								}
								hideAjaxProcess();
							} else {
								hideAjaxProcess();
								page.statusbar.showError("Error retrieving in-patient records. Try Again, if problem persists, check the connection.");	
								return false;
							}				
							setInput("u_message","");
							//setMessageLength();
							break;
					}
					break;
			}
	}
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
	/*
	switch (Id) {
		case "df_u_mobilenoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_contactno, name from u_customers where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_contactno<>''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Mobile No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 
			//params["params"] += ";&lookupSortBy=u_custname";
			break;
		case "df_u_custnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, u_contactno from u_customers where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_contactno<>''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer Name`Mobile No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 
			//params["params"] += ";&lookupSortBy=u_custname";
			break;
	}
	*/
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

