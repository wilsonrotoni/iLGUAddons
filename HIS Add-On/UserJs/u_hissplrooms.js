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
			setInput("u_requestno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		//if (isInputEmpty("u_reqdate")) return false;
		//if (isInputEmpty("u_reqtime")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_roomno")) return false;
		if (isInputEmpty("u_bedno")) return false;
		if (isInputEmpty("u_itemcode")) return false;
		if (getInput("docstatus")=="C") {
			//if (isInputEmpty("u_enddate")) return false;
			//if (isInputEmpty("u_endtime")) return false;
			//if (isInputNegative("u_quantity")) return false;
			if (isInputNegative("u_amount")) return false;
		}
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
				case "u_refno":
					result = validatePatientGPSHIS();
					if (result) {
						setInput("u_itemcode","",true);
						resetPatientTotalAmount("","u_amount","u_vatamount","u_discamount");
						resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					}
					return result;
				case "u_roomno":
					if (getInput("u_roomno")!="") {
						result = page.executeFormattedQuery("select a.code, a.name, a.u_isshared, a.u_chargingrate, a.u_charginguom from u_hisrooms a, u_hisroombeds b where b.code=a.code and b.u_status='Vacant' and a.code='"+getInput("u_roomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								if (getInput("u_isroomshared")==0) setInput("u_bedno",result.childNodes.item(0).getAttribute("code"));
								else setInput("u_bedno","");
								//setInput("u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								//setInput("u_uom",result.childNodes.item(0).getAttribute("u_charginguom"));
								//computeTimeGPSHIS();
							} else {
								setInput("u_bedno","");
								setInput("u_roomdesc","");
								setTableInputAmount(table,"u_chargingrate",1);
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
						result = page.executeFormattedQuery("select a.code, b.name, a.u_bedno, b.u_isshared, b.u_chargingrate, b.u_charginguom from u_hisroombeds a, u_hisrooms b where a.code=b.code and a.u_bedno='"+getInput("u_bedno")+"' and a.u_status='Vacant'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
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
				case "u_requestno":
					setRequestNoFieldAttributesGPSHIS(true);
					if (getInput("u_requestno")!="") {
						result = page.executeFormattedQuery("select a.u_department, a.u_roomno, a.u_roomdesc, a.u_bedno, a.u_itemcode, a.u_itemdesc, a.u_isroomshared, a.u_rate, a.u_rateuom, a.u_quantity, a.u_uom, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_scdisc, a.u_discperc, a.u_unitprice, a.u_scdiscamount, a.u_disconbill, a.u_paymentterm, a.u_disccode, a.u_pricelist, a.u_prepaid, a.u_amount from u_hissplroomrequests a where a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid=0 or (a.u_prepaid!=0 and a.u_payrefno<>'')) and a.docstatus='O'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("u_roomno"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("u_roomdesc"));
								setInput("u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isroomshared"));
								//setInput("u_rateuom",result.childNodes.item(0).getAttribute("u_rateuom"));
								//setInput("u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setInput("u_itemcode",result.childNodes.item(0).getAttribute("u_itemcode"));
								setInput("u_itemdesc",result.childNodes.item(0).getAttribute("u_itemdesc"));
								setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
								setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
								setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
								setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
								setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
								//setInputAmount("u_rate",result.childNodes.item(0).getAttribute("u_rate"));
								//setInputQuantity("u_quantity",result.childNodes.item(0).getAttribute("u_quantity"));
								setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInputAmount("u_amount",0);
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
							setRequestNoFieldAttributesGPSHIS(false);

						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInputAmount("u_amount",0);
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInputAmount("u_amount",0);
					}
					//computeTimeGPSHIS();
					break;
				case "u_startdate":
				case "u_enddate":
				case "u_starttime":
				case "u_endtime":
					//if (getInput("u_enddate")!="" || getInput("u_endtime")!="") setInput("docstatus","C");
					//else setInput("docstatus","O");
					//computeTimeGPSHIS();
					break;
				case "u_rate":
				case "u_quantity":
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
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
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
		case "df_u_requestno":
			/*if (getInput("u_roomno")=="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_roomno, a.u_roomdesc, a.u_patientname from u_hissplroomrequests a where (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O'")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_roomno, a.u_roomdesc,a.u_patientname from u_hissplroomrequests a where a.u_roomno='"+getInput("u_roomno")+"' and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O'")); 
			}*/
			if (getInput("u_department")=="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_roomno, a.u_roomdesc, a.u_patientname from u_hissplroomrequests a where (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O'")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_roomno, a.u_roomdesc,a.u_patientname from u_hissplroomrequests a where a.u_department='"+getInput("u_department")+"' and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O'")); 
			}
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

function setRequestNoFieldAttributesGPSHIS(enable) {
	if (enable) {
		enableInput("u_department");
		enableInput("u_reftype");
		enableInput("u_refno");
		enableInput("u_disccode");
		enableInput("u_pricelist");
	} else {
		disableInput("u_department");
		disableInput("u_reftype");
		disableInput("u_refno");
		disableInput("u_disccode");
		disableInput("u_pricelist");
	}
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