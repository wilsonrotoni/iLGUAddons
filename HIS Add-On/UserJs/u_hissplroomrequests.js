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
page.elements.events.add.change('onElementChangeGPSHIS');
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
	if (getVar("formSubmitAction")=="a") {
		try {
			if (getInput("u_department")!="") setInput("u_department",getInput("u_department"),true);
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_requestdepartment")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		//if (isInputEmpty("u_reqdate")) return false;
		//if (isInputEmpty("u_reqtime")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_roomno")) return false;
		if (isInputEmpty("u_bedno")) return false;
		if (isInputEmpty("u_department")) return false;
		if (isInputEmpty("u_itemcode")) return false;
		//if (isInputNegative("u_quantity")) return false;
		//if (isInputEmpty("u_enddate")) return false;
		//if (isInputEmpty("u_endtime")) return false;
		if (isInputNegative("u_amount")) return false;
		
		if (isInputEmpty("u_requestby")) return false;
		
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
	var result;
	switch (table) {
		default:
			switch(column) {
				case "u_refno":
					result = validatePatientGPSHIS();
					setInput("u_itemcode","",true);
					resetPatientTotalAmount("","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					return result;
					break;
				case "u_roomno":
					if (getInput("u_roomno")!="") {
						result = page.executeFormattedQuery("select a.code, a.name, a.u_isshared, a.u_department, a.u_chargingrate, a.u_charginguom from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Special Room' and b.code=a.code and b.u_status='Vacant' and a.code='"+getInput("u_roomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								if (getInput("u_isroomshared")==0) setInput("u_bedno",result.childNodes.item(0).getAttribute("code"));
								else setInput("u_bedno","");
								//setInput("u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								//setInput("u_uom",result.childNodes.item(0).getAttribute("u_charginguom"));
								//computeTimeGPSHIS();
							} else {
								setInput("u_bedno","");
								setInput("u_roomdesc","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setInput("u_bedno","");
							setInput("u_roomdesc","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bedno","");
						setInput("u_roomno","");
						setInput("u_roomdesc","");
					}
					break;
				case "u_bedno":
					if (getInput("u_bedno")!="") {
						result = page.executeFormattedQuery("select a.code, b.name, a.u_bedno, b.u_isshared, b.u_department, b.u_chargingrate, b.u_charginguom from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Special Room' and a.code=b.code and a.u_bedno='"+getInput("u_bedno")+"' and a.u_status='Vacant'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								//setInput("u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								//setInput("u_uom",result.childNodes.item(0).getAttribute("u_charginguom"));
								//computeTimeGPSHIS();
							} else {
								setInput("u_roomno","");
								setInput("u_roomdesc","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setInput("u_roomno","");
							setInput("u_roomdesc","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bedno","");
						setInput("u_roomno","");
						setInput("u_roomdesc","");
					}
					break;
				case "u_startdate":
				case "u_starttime":
					//computeTimeGPSHIS();
					break;
				case "u_rate":
					//setInputAmount("u_amount",getInputNumeric("u_rate") * getInputNumeric("u_quantity"));
					break;
				case "u_quantity":
					//computeTimeGPSHIS();
					//setInputAmount("u_amount",getInputNumeric("u_rate") * getInputNumeric("u_quantity"));
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
	var result, data = new Array();
	switch (table) {
		default:
			switch (column) {
				case "u_department":
					resetPatientTotalAmount("","u_amount","u_vatamount","u_discamount");
					return setSectionData();
					break;
				case "u_reftype":
					setInput("u_refno","",true);
					break;
				case "u_itemcode":
					//result = validatePatientSplRoomGPSHIS(element,column,table,row);
					//return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					break;
				case "u_disccode":
					result = setDiscountData();
					//if (result) result = validatePatientSplRoomGPSHIS(element,"u_itemcode",table,row);
					return result;
					break;
			}
			break;
	}
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
		case "df_u_refno":
			if (getInput("u_requestdepartment")=="") {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where u_department='"+getInput("u_requestdepartment")+"' and docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
				}
			}
			
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_roomno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Special Room' and a.code=b.code and b.u_status='Vacant' group by a.code")); 
			break;
		case "df_u_bedno":
			if (getInput("u_roomno")=="") params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Special Room' and b.code=a.code"));
			else params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Special Room' and b.code=a.code and a.code='"+getInput("u_roomno")+"'")); 
			
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

function computeAmountGPSHIS() {
	if (getInput("u_startdate")!="" && getInput("u_starttime")!="" && getInput("u_enddate")!="" && getInput("u_endtime")!="" && getInput("u_uom")!="") {
		if (getInput("u_uom")=="Hour") {
			setInputQuantity("u_quantity",parseFloat(page.executeFormattedSearch("select CEIL(((TIME_TO_SEC(timediff('"+formatDateToDB(getInput("u_enddate"))+" "+getInput("u_endtime")+"','"+formatDateToDB(getInput("u_startdate"))+" "+getInput("u_starttime")+"'))/60)/60))")));
		} else {
			setInputQuantity("u_quantity",parseFloat(page.executeFormattedSearch("select CEIL((((TIME_TO_SEC(timediff('"+formatDateToDB(getInput("u_enddate"))+" "+getInput("u_endtime")+"','"+formatDateToDB(getInput("u_startdate"))+" "+getInput("u_starttime")+"'))/60)/60)/24))")));
		}
		setInputAmount("u_amount",getInputNumeric("u_rate") * getInputNumeric("u_quantity"));
	} else {
		setInputQuantity("u_quantity",0);
		setInputAmount("u_amount",getInputNumeric("u_rate") * getInputNumeric("u_quantity"));
	}
}

function computeTimeGPSHIS() {
	if (getInput("u_startdate")!="" && getInput("u_starttime")!="" && getInputNumeric("u_quantity")!=0 && getInput("u_uom")!="") {
		if (getInput("u_uom")=="Hour") {
			result = page.executeFormattedSearch("select ('"+formatDateToDB(getInput("u_startdate"))+" " +getInput("u_starttime")+":00' + interval "+(getInputNumeric("u_quantity")*60)+" minute)");
		} else {
			result = page.executeFormattedSearch("select ('"+formatDateToDB(getInput("u_startdate"))+" " +getInput("u_starttime")+":00' + interval "+(getInputNumeric("u_quantity")*24)+" hour)");
		}
		if (result!="") {
			var datetime = result.split(' ');	
			setInput("u_enddate",formatDateToHttp(datetime[0]));	
			setInput("u_endtime",datetime[1].substr(0,5));	
		} else {
			setInput("u_enddate","");	
			setInput("u_endtime","");	
		}
	} else {
		setInput("u_enddate","");	
		setInput("u_endtime","");	
	}
}