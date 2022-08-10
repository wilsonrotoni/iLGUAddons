// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
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
page.tables.events.add.select('onTableSelectRowGPSHIS');

var u_hisbillinsrow=0;

function onPageLoadGPSHIS() {
	u_hisbillinsrow = window.opener.getTableSelectedRow("T7");
	if (getVar("formSubmitAction")=="a" && getInput("u_billno")=="") {
		setInput("u_billno",window.opener.getInput("docno"));
		setInput("u_reftype",window.opener.getInput("u_reftype"));
		setInput("u_refno",window.opener.getInput("u_refno"));
		setInput("u_patientid",window.opener.getInput("u_patientid"));
		setInput("u_patientname",window.opener.getInput("u_patientname"));
		setInput("u_gender",window.opener.getInput("u_gender"));
		setInput("u_age",window.opener.getInput("u_age"));
		setInput("u_docdate",window.opener.getInput("u_docdate"));
		setInput("u_startdate",window.opener.getInput("u_startdate"));
		setInput("u_enddate",window.opener.getInput("u_docdate"));
		setInput("u_inscode",window.opener.getTableInput("T7","u_inscode",u_hisbillinsrow));
		setInput("u_hmo",window.opener.getTableInput("T7","u_hmo",u_hisbillinsrow));
		setInput("u_memberid",window.opener.getTableInput("T7","u_memberid",u_hisbillinsrow));
		setInput("u_membername",window.opener.getTableInput("T7","u_membername",u_hisbillinsrow));
		setInput("u_membertype",window.opener.getTableInput("T7","u_membertype",u_hisbillinsrow));
		formEntry();
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (getInput("docstatus")!="D") {
			try {
				if (window.opener.getInput("docstatus")=="D") {
					alert("You cannot add claim/discount if billing is still in draft.");
					return false;
				}
			} catch (theError) {
				page.statusbar.showError("Cannot access the billing document screen. Please close the window and try again.");
				return false;
			}
		}
		if ((action=="a" || (action=="sc" && getInput("docstatus")!="D")) && isTableInput("T50","userid")) {
			if (getTableInput("T50","userid")=="") {
				showPopupFrame("popupFrameAuthentication",true);
				focusTableInput("T50","userid");
				return false;
			}
		}
		
	} else if (action=="cnd") {
		try {
			if (u_hisbillinsrow<window.opener.getTableRowCount("T7")) {
				if (window.opener.getTableInput("T7","u_status",u_hisbillinsrow+1)!="") {
					alert("You must cancel the all proceeding health benefits before cancelling this health benefits.");
					return false;
				}
			}
		} catch(theError) {
			page.statusbar.showError("Cannot access the billing document screen. Please close the window and try again.");
			return false;
		}
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
				return false;
			}
			if (getTableInput("T51","cancelreason")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","cancelreason");
				return false;
			}
			if (getTableInput("T51","remarks")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","remarks");
				return false;
			}
		}
		
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		if (success) {	
			window.opener.setKey("keys",window.opener.getInput("docno"));
			//window.opener.setInput("u_tab1selected",1);
			window.opener.formEdit();
			window.close();
		}
	} catch(TheError) {
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
	switch (table) {
		case "T50":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T50","password");
			} else if (sc_press == "ENTER" && column=="password") {
				formSubmit();
			}
			break;
		case "T51":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T51","password");
			} else if (sc_press == "ENTER" && column=="password") {
				focusTableInput("T51","cancelreason");
			} else if (sc_press == "ENTER" && column=="cancelreason") {
				focusTableInput("T51","remarks");
			} else if (sc_press == "ENTER" && column=="remarks") {
				formSubmit();
			}
			break;
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T2":
			switch (column) {
				case "u_thisamount":
					if (getTableInputNumeric(table,column,row)!=0) {
						if (isInputChecked("u_discalloc")) {
							setTableInputAmount(table,"u_thisamount",(getTableInput(table,"u_thisamount",row)/100)*getTableInput(table,"u_amount",row),row);
						}
						if (getTableInputNumeric(table,column,row)>getTableInputNumeric(table,"u_amount",row)) {
							setTableInputAmount(table,column,getTableInputNumeric(table,"u_amount",row),row);
							page.statusbar.showWarning("Benefit amount cannot be more than actual charges.");
						}
					}
					computeT2thisamountGPSHIS();
					break;
			}
			break;
		case "T3":
			switch (column) {
				case "u_thisamount":
					if (getTableInputNumeric(table,column,row)!=0) {
						if (isInputChecked("u_discalloc")) {
							setTableInputAmount(table,"u_thisamount",(getTableInput(table,"u_thisamount",row)/100)*getTableInput(table,"u_amount",row),row);
						}
						if (getTableInputNumeric(table,column,row)>getTableInputNumeric(table,"u_amount",row)) {
							setTableInputAmount(table,column,getTableInputNumeric(table,"u_amount",row),row);
							page.statusbar.showWarning("Benefit amount cannot be more than actual charges.");
						}
					}
					computeT3thisamountGPSHIS();
					break;
			}
			break;
		case "T4":
			switch (column) {
				case "u_thisamount":
					if (getTableInputNumeric(table,column,row)!=0) {
						if (isInputChecked("u_discalloc")) {
							setTableInputAmount(table,"u_thisamount",(getTableInput(table,"u_thisamount",row)/100)*getTableInput(table,"u_amount",row),row);
						}
						if (getTableInputNumeric(table,column,row)>getTableInputNumeric(table,"u_amount",row)) {
							setTableInputAmount(table,column,getTableInputNumeric(table,"u_amount",row),row);
							page.statusbar.showWarning("Benefit amount cannot be more than actual charges.");
						}
					}
					computeT4thisamountGPSHIS();
					break;
			}
			break;
		case "T5":
			switch (column) {
				case "u_thisamount":
					if (getTableInputNumeric(table,column,row)!=0) {
						if (isInputChecked("u_discalloc")) {
							setTableInputAmount(table,"u_thisamount",(getTableInput(table,"u_thisamount",row)/100)*getTableInput(table,"u_amount",row),row);
						}
						if (getTableInputNumeric(table,column,row)>getTableInputNumeric(table,"u_amount",row)) {
							setTableInputAmount(table,column,getTableInputNumeric(table,"u_amount",row),row);
							page.statusbar.showWarning("Benefit amount cannot be more than actual charges.");
						}
					}
					computeT5thisamountGPSHIS();
					break;
			}
			break;
		case "T6":
			switch (column) {
				case "u_thisamount":
					if (getTableInputNumeric(table,column,row)!=0) {
						if (isInputChecked("u_discalloc")) {
							setTableInputAmount(table,"u_thisamount",(getTableInput(table,"u_thisamount",row)/100)*getTableInput(table,"u_amount",row),row);
						}
						if (getTableInputNumeric(table,column,row)>getTableInputNumeric(table,"u_amount",row)) {
							setTableInput(table,column,getTableInputNumeric(table,"u_amount",row),row);
							page.statusbar.showWarning("Benefit amount cannot be more than actual charges.");
						}
					}
					computeT6thisamountGPSHIS(getTableInput(table,"u_doctorid",row),getTableInput(table,"u_doctortype",row));
					break;
			}
			break;
		default:
			switch(column) {
				case "u_refno":
					if (getInput("u_refno")!="") {
						result = page.executeFormattedQuery("select u_patientid, u_patientname from u_hisips where docno='"+getInput("u_refno")+"' and docstatus not in ('Discharged')");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								if (getInput("u_docdate")!="") formEntry();
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
					}
					break;
				case "u_rvscode":
					if (getInput("u_rvscode")!="") {
						result = page.executeFormattedQuery("select code, name, u_rvu from u_hisrvs where code='"+getInput("u_rvscode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_rvscode",result.childNodes.item(0).getAttribute("code"));
								setInput("u_rvsdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_rvu",result.childNodes.item(0).getAttribute("u_rvu"));
								if (getInputNumeric("u_rvu")>0) {
									formEntry();
								}
							} else {
								setInput("u_rvscode","");
								setInput("u_rvsdesc","");
								setInput("u_rvu",0);
								page.statusbar.showError("Invalid RVS No.");	
								return false;
							}
						} else {
							setInput("u_rvscode","");
							setInput("u_rvsdesc","");
							setInput("u_rvu",0);
							page.statusbar.showError("Error retrieving RVS No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_rvscode","");
						setInput("u_rvsdesc","");
						setInput("u_rvu",0);
					}
					break;
				case "u_pkgcode":
					if (getInput("u_pkgcode")!="") {
						result = page.executeFormattedQuery("select u_icdcode,u_icddesc,u_amount from  u_hishealthinpkgs where code='"+getInput("u_inscode")+"' and u_icdcode='"+getInput("u_pkgcode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_pkgcode",result.childNodes.item(0).getAttribute("u_icdcode"));
								setInput("u_pkgdesc",result.childNodes.item(0).getAttribute("u_icddesc"));
								setInputAmount("u_pkgamount",result.childNodes.item(0).getAttribute("u_amount"));
								if (getInputNumeric("u_rvu")>0) {
									formEntry();
								}
							} else {
								setInput("u_pkgcode","");
								setInput("u_pkgdesc","");
								setInput("u_pkgamount",0);
								page.statusbar.showError("Invalid Package Code");	
								return false;
							}
						} else {
							setInput("u_pkgcode","");
							setInput("u_pkgdesc","");
							setInputAmount("u_pkgamount",0);
							page.statusbar.showError("Error retrieving Package Code Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_pkgcode","");
						setInput("u_pkgdesc","");
						setInputAmount("u_pkgamount",0);
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
	switch (table) {
		default:
			switch(column) {
				case "u_inscode":
					formEntry();
					break;
			}
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
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_rvscode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisrvs")); 
			break;
		case "df_u_pkgcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_icdcode,u_icddesc,u_amount from  u_hishealthinpkgs where code='"+getInput("u_inscode")+"'")); 
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
	params = new Array();
	switch (table) {
		case "T2":	
		case "T3":	
		case "T4":	
		case "T5":	
		case "T6":	
			if (elementFocused.substring(0,15)=="df_u_thisamount") {
				focusTableInput(table,"u_thisamount",row);
			}
		
			params["focus"] = false;
	}
	return params;
}

function computeT2thisamountGPSHIS() {
	var rc =  getTableRowCount("T2"), thisamount=0,totalthisamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			thisamount += getTableInputNumeric("T2","u_thisamount",i);
		}
	}
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_chrgcode",i)=="ROOM") {
				setTableInputAmount("T1","u_insamount",thisamount,i);
			}
			totalthisamount += getTableInputNumeric("T1","u_insamount",i);
		}
	}
	setInputAmount("u_thisamount",totalthisamount);	
	
}

function computeT3thisamountGPSHIS() {
	var rc =  getTableRowCount("T3"), thisamount=0,totalthisamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			thisamount += getTableInputNumeric("T3","u_thisamount",i);
		}
	}
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_chrgcode",i)=="MED") {
				setTableInputAmount("T1","u_insamount",thisamount,i);
			}
			totalthisamount += getTableInputNumeric("T1","u_insamount",i);
		}
	}
	setInputAmount("u_thisamount",totalthisamount);	
	
}

function computeT4thisamountGPSHIS() {
	var rc =  getTableRowCount("T4"), supamount=0,labamount=0,miscamount=0,totalthisamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			switch (getTableInput("T4","u_reftype",i)) {
				case "LAB":labamount+=getTableInputNumeric("T4","u_thisamount",i);break;
				case "MISC":
				case "SPLROOM":
					miscamount+=getTableInputNumeric("T4","u_thisamount",i);break;
				case "SUP":supamount+=getTableInputNumeric("T4","u_thisamount",i);break;
			}
		}
	}
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			switch (getTableInput("T1","u_chrgcode",i)) {
				case "LAB":	setTableInputAmount("T1","u_insamount",labamount+miscamount+supamount,i);break;
				case "MISC": setTableInputAmount("T1","u_insamount",miscamount,i);break;
				case "EXAM": setTableInputAmount("T1","u_insamount",labamount,i);break;
				case "SUP":	setTableInputAmount("T1","u_insamount",supamount,i);break;
			}
			totalthisamount += getTableInputNumeric("T1","u_insamount",i);
		}
	}
	setInputAmount("u_thisamount",totalthisamount);	
	
}

function computeT5thisamountGPSHIS() {
	var rc =  getTableRowCount("T5"), thisamount=0,totalthisamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T5",i)==false) {
			thisamount += getTableInputNumeric("T5","u_thisamount",i);
		}
	}
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_chrgcode",i)=="OR") {
				setTableInputAmount("T1","u_insamount",thisamount,i);
			}
			totalthisamount += getTableInputNumeric("T1","u_insamount",i);
		}
	}
	setInputAmount("u_thisamount",totalthisamount);	
	
}

function computeT6thisamountGPSHIS(doctorid,doctortype) {
	var rc =  getTableRowCount("T6"), thisamount=0,totalthisamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T6",i)==false && getTableInput("T6","u_doctorid",i)==doctorid && getTableInput("T6","u_doctortype",i)==doctortype) {
			thisamount += getTableInputNumeric("T6","u_thisamount",i);
		}
	}
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_doctorid",i)==doctorid && getTableInput("T1","u_doctortype",i)==doctortype) {
				setTableInputAmount("T1","u_insamount",thisamount,i);
			}
			totalthisamount += getTableInputNumeric("T1","u_insamount",i);
		}
	}
	setInputAmount("u_thisamount",totalthisamount);	
	
}
