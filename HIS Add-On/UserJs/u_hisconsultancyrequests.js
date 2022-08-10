// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHR');
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
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_department")) return false;
		if (isInputEmpty("u_doctorid")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		if (isInputEmpty("u_enddate")) return false;
		if (isInputEmpty("u_endtime")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		//if (isInputEmpty("u_itemcode")) return false;
		//if (isInputEmpty("u_itemdesc")) return false;
		if (isInputNegative("u_amount")) return false;
		if (isInputNegative("u_requestby")) return false;
		if (isInputNegative("u_requestdepartment")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSHR(action,sucess,error) {
	if (sucess) {
		try {
			if (window.opener.getVar("objectcode")=="U_CLINICCALENDAR" || window.opener.getVar("objectcode")=="u_cliniccalendar"){
				window.opener.submitMonthYear();
				window.close();
			}
		} catch (theError) {
		}
		try {
			window.opener.formSearchNow();
			window.close();
		} catch (theError) {
		}
		
	}
	
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
				case "u_startdate":
					if (getInput("u_startdate")!="") {
						setInput("u_age_y",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
					} else {
						setInput("u_age_y",0);
					}
					break;
				case "u_refno":
					result = validatePatientGPSHIS();
					if (result) {
						setInput("u_itemcode","",true);
						resetPatientTotalAmount("","u_amount","u_vatamount","u_discamount");
						//resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					}
					return result;
					break;
				case "u_unitprice":
					//resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
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
		case "T1":
			break;
		default:
			switch (column) {
				case "u_department":
					resetPatientTotalAmount("","u_amount","u_vatamount","u_discamount");
					return setSectionData();
					break;
				case "u_reftype":
					setInput("u_refno","",true);
					break;
				case "u_doctorid":
					//u_ajaxloadu_hisdoctorservicetypes("df_u_itemcode",element.value,'',":");
					setInput("u_doctortype","");
					/*if (getInput("u_doctorid")!="") {
						result = page.executeFormattedQuery("select a.u_type from u_hisdoctors a where a.code='"+getInput("u_doctorid")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_doctortype",result.childNodes.item(0).getAttribute("u_type"));
							} else {
								setInput("u_doctortype","");
								page.statusbar.showError("Invalid Doctor.");	
								return false;
							}
						} else {
							setInput("u_doctortype","");
							page.statusbar.showError("Error retrieving Doctor Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_doctortype","");
					}*/
					break;
				case "u_itemcode":
					//result = validatePatientPFGPSHIS(element,column,table,row);
					//if (result) setInput("u_itemdesc",getInputSelectedText(column));
					//else setInput("u_itemdesc","");
					//return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					break;
				case "u_disccode":
					result = setDiscountData();
					//if (result) result = validatePatientPFGPSHIS(element,"u_itemcode",table,row);
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
		case "df_u_itemcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisservices where u_type='Professional Fee' and u_active=1")); 
			break;
		case "df_u_itemdesc":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisservices where u_type='Professional Fee' and u_active=1")); 
			break;
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where u_nursed=1 and docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment Term")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T2":
			if (getVar("formSubmitAction")=="a") {
				page.statusbar.showWarning("Please add the document before attaching files.");	
				return false;
			}
			uploadAttachment();
			return false;
			break;	
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
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
	var params = new Array();
	switch (table) {
		case "T2":
			document.images['PictureImg'].src = getTableInput("T2","u_filepath",row);			
			break;
	}
	return params;
}

function uploadAttachment() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Laboratory Tests/" + getInput("docno") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

