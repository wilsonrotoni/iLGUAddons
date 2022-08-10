// page events
//page.events.add.load('onPageLoadGPSHIS');
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
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_lastname")) return false;
		if (isInputEmpty("u_firtname")) return false;
		if (getInput("u_middlename")=="") {
			if (window.confirm("Middle Name was not entered. Are you sure patient has no middle name?")==false) {
				focusInput("u_middlename");
				return false;
			}
		}
		if (isInputEmpty("u_roomno")) return false;
		if (isInputEmpty("u_bedno")) return false;
		if (isInputNegative("u_hours")) return false;
		if (isInputEmpty("u_enddate")) return false;
		if (isInputEmpty("u_endtime")) return false;
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
			switch(column) {
				case "u_patientid":
					if (getInput("u_patientid")!="") {
						result = page.executeFormattedQuery("select code, name, u_lastname, u_firstname, u_middlename, u_extname from u_hispatients where code='"+getInput("u_patientid")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
								setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								setInput("u_extname",result.childNodes.item(0).getAttribute("u_extname"));
							} else {
								setInput("u_patientname","");
								setInput("u_lastname","");
								setInput("u_firstname","");
								setInput("u_middlename","");
								setInput("u_extname","");
								page.statusbar.showError("Invalid Patient ID.");	
								return false;
							}
						} else {
							setInput("u_patientname","");
							setInput("u_lastname","");
							setInput("u_firstname","");
							setInput("u_middlename","");
							setInput("u_extname","");
							page.statusbar.showError("Error retrieving Patient ID. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_lastname","");
						setInput("u_firstname","");
						setInput("u_middlename","");
						setInput("u_extname","");
					}
					break;
				case "u_lastname":
				case "u_firstname":
				case "u_extname":
				case "u_middlename":
					setInput(column,utils.trim(getInput(column)));
					name = getInput("u_lastname");
					if (getInput("u_firstname")!="") name += ", " + getInput("u_firstname");
					if (getInput("u_middlename")!="") name += " " + getInput("u_middlename");
					if (getInput("u_extname")!="") name += " " + getInput("u_extname");
					setInput("u_patientname",name);
					break;
				case "u_roomno":
					if (getInput(table,"u_roomno")!="") {
						result = page.executeFormattedQuery("select a.code, a.u_isshared, a.u_charginguom from u_hisrooms a, u_hisroombeds b where b.code=a.code and b.u_status='Vacant' and a.code='"+getInput("u_roomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								if (getInput("u_isroomshared")==0) setInput("u_bedno",result.childNodes.item(0).getAttribute("code"));
								else setInput("u_bedno","");
							} else {
								setInput("u_bedno","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setInput("u_bedno","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bedno","");
						setInput("u_roomno","");
					}
					break;
				case "u_bedno":
					if (getInput("u_bedno")!="") {
						result = page.executeFormattedQuery("select a.code, a.u_bedno, b.u_isshared, b.u_charginguom from u_hisroombeds a, u_hisrooms b where a.code=b.code and a.u_bedno='"+getInput("u_bedno")+"' and a.u_status='Vacant'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
							} else {
								setInput("u_roomno","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setInput("u_roomno","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bedno","");
						setInput("u_roomno","");
					}
					break;
				case "u_hours":
					if (getInput("u_hours")!="") {
						result = page.executeFormattedSearch("select ('"+formatDateToDB(getInput("u_startdate"))+" " +getInput("u_starttime")+":00' + interval "+getInputNumeric("u_hours")+" hour)");
						if (result!="") {
							var datetime = result.split(' ');	
							setInput("u_enddate",formatDateToHttp(datetime[0]));	
							setInput("u_endtime",datetime[1].substr(0,5));	
							setInput("u_enddatetime",result);	
						} else {
							setInput("u_enddate","");	
							setInput("u_endtime","");	
							setInput("u_enddatetime","");	
						}
					} else {
						setInput("u_enddate",getInput("u_startdate"));	
						setInput("u_endtime",getInput("u_starttime"));	
						setInput("u_enddatetime",getInput("u_startdatetime"));	
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
		case "df_u_patientid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hispatients")); 
			break;
		case "df_u_roomno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name,count(*) as 'Vacant Beds' from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Room & Board' and a.code=b.code and b.u_status='Vacant' group by a.code having count(*) >0")); 
			break;
		case "df_u_bedno":
			if (getInput("u_roomno")=="")	params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.u_status='Vacant'")); 
			else params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.code='"+getInput("u_roomno")+"' and a.u_status='Vacant'" )); 
			
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

