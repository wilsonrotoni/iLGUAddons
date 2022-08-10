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
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISCONSULTANCYREQUESTINLIST") {
				setInput("u_requestno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
			}
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (getInputType("u_department")!="hidden") {
			if (isInputEmpty("u_department")) return false;
		}
		if (isInputEmpty("u_doctorid")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInput("u_starttime")) if (isInputEmpty("u_starttime")) return false;
		//if (isInputEmpty("u_reqdate")) return false;
		//if (isInputEmpty("u_reqtime")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		//if (isInputEmpty("u_itemcode")) return false;
		//if (isInputEmpty("u_itemdesc")) return false;
		if (isInputNegative("u_amount")) return false;
		
		if (getTableInput("T1","u_itemdesc")!="") {
			page.statusbar.showError("A material is being added/edited.");
			selectTab("tab1",0);
			focusTableInput("T1","u_itemdesc");
			return false;
		}

		if (getTableInput("T2","u_itemdesc")!="") {
			page.statusbar.showError("A medication is being added/edited.");
			selectTab("tab1",1);
			focusTableInput("T2","u_itemdesc");
			return false;
		}

		setInput("u_surgeonrefno","");
		setInput("u_surgeonid","");
		setInput("u_surgeonfee",0);
		if (getInput("u_doctortype")=="ANE") {
			var result = page.executeFormattedQuery("select docno, u_doctorid, u_amount from u_hisconsultancyfees where u_reftype='"+getInput("u_reftype")+"' and u_refno='"+getInput("u_refno")+"' and u_startdate='"+formatDateToDB(getInput("u_startdate"))+"' and u_starttime='"+getInput("u_starttime")+"' and u_doctortype='SUR'");	 
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					setInput("u_surgeonrefno",result.childNodes.item(0).getAttribute("docno"));
					setInput("u_surgeonid",result.childNodes.item(0).getAttribute("u_doctorid"));
					setInput("u_surgeonfee",result.childNodes.item(0).getAttribute("u_amount"));
				} else {
					page.statusbar.showError("No surgeon record with same patient and time.");	
					return false;
				}
			} else {
				page.statusbar.showError("Error retrieving Surgeon Info. Try Again, if problem persists, check the connection.");	
				return false;
			}		
			if (isInputEmpty("u_surgeonrefno")) return false;
		}
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
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
		case "T1":
			switch(column) {
				case "u_unitprice":
					setTableInputPrice(table,"u_price",getTableInputNumeric(table,"u_unitprice"));
					setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
					break;
				case "u_quantity":
				case "u_price":
					setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
					break;
			}
			break;
		case "T2":
			switch(column) {
				case "u_itemcode":
					if (getTableInput(table,"u_itemcode")!="") {
						result = page.executeFormattedQuery("select code, name from u_hisitems where code='"+getTableInput(table,"u_itemcode")+"' and u_group='MED' and u_active=1");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_itemdesc","");
								page.statusbar.showError("Invalid Item Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemdesc","");
							page.statusbar.showError("Error retrieving Item Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}					
					break;
				case "u_itemdesc":
					if (getTableInput(table,"u_itemdesc")!="") {
						result = page.executeFormattedQuery("select code, name from u_hisitems where name='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"' and u_group='MED' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_itemcode","");
								page.statusbar.showError("Invalid Item Description.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							page.statusbar.showError("Error retrieving Item Description. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
			}
			break;
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
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
					break;
				case "u_amount":
					if (getInputNumeric("u_unitprice")==0) setInputPrice("u_unitprice",getInputNumeric("u_amount") );	
					setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
					break;
				case "u_requestno":
					setRequestNoFieldAttributesGPSHIS(true);
					if (getInput("u_requestno")!="") {
						result = page.executeFormattedQuery("select a.u_itemcode, a.u_itemdesc, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_startdate, a.u_starttime, a.u_department, a.u_scdisc, a.u_scdiscamount, a.u_paymentterm, a.u_disccode, a.u_pricelist, a.u_disconbill, a.u_discperc, a.u_unitprice, a.u_discamount, a.u_vatamount, a.u_amount, a.u_prepaid, a.u_payrefno, a.u_doctorid, a.u_doctortype, a.u_birthdate from u_hisconsultancyrequests a where a.docno='"+getInput("u_requestno")+"' and a.docstatus='O'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_doctorid",result.childNodes.item(0).getAttribute("u_doctorid"),true);
								setInput("u_doctortype",result.childNodes.item(0).getAttribute("u_doctortype"));
								setInput("u_itemcode",result.childNodes.item(0).getAttribute("u_itemcode"));
								setInput("u_itemdesc",result.childNodes.item(0).getAttribute("u_itemdesc"));
								setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
								setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
								setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
								setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
								setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
								setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
								setInputPercent("u_discperc",result.childNodes.item(0).getAttribute("u_discperc"));
								setInputAmount("u_scdiscamount",result.childNodes.item(0).getAttribute("u_scdiscamount"));
								setInputAmount("u_discamount",result.childNodes.item(0).getAttribute("u_discamount"));
								setInputPrice("u_unitprice",result.childNodes.item(0).getAttribute("u_unitprice"));
								setInputAmount("u_vatamount",result.childNodes.item(0).getAttribute("u_vatamount"));
								setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
								setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
								setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
										
								if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
								else uncheckedInput("u_scdisc");
								
								if (getInput("u_startdate")!="") {
									setInput("u_age_y",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
								} else {
									setInput("u_age_y",0);
								}
								
								setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
								
								setRequestNoFieldAttributesGPSHIS(false);
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInputAmount("u_amount",0);
								setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInputAmount("u_amount",0);
							setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInputAmount("u_amount",0);
						setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
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
	var result, data = new Array();
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_department":
					resetPatientTotalAmount("","u_amount","u_vatamount","u_discamount");
					setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
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
					//setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
					//return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount");
					setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
					break;
				case "u_disccode":
					result = setDiscountData();
					//if (result) result = validatePatientPFGPSHIS(element,"u_itemcode",table,row);
					setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
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
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where u_nursed=1 and docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			if (getInput("u_doctorid")=="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, b.name as 'Doctor', a.u_patientname from u_hisconsultancyrequests a, u_hisdoctors b where b.code=a.u_doctorid and (a.u_prepaid=0 or (a.u_prepaid=1 and a.u_payrefno<>'')) and a.docstatus='O'")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,b.name as 'Doctor',a.u_patientname from u_hisconsultancyrequests a, u_hisdoctors b where b.code=a.u_doctorid and a.u_doctorid='"+getInput("u_doctorid")+"' and (a.u_prepaid=0 or (a.u_prepaid=1 and a.u_payrefno<>'')) and a.docstatus='O'")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Name of Doctor`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
		case "df_u_itemcodeT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_group='MED' and u_active=1 and code not in ("+getTableInputGroupConCat("T2","u_itemcode")+")"));
			break;
		case "df_u_itemdescT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_group='MED' and u_active=1 and name not in ("+getTableInputGroupConCat("T2","u_itemdesc")+")")); 
			break;
		}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_itemdesc");
			break;
		case "T2":
			if (getTableInputType(table,"u_itemcode")=="text") focusTableInput(table,"u_itemcode");
			else if (getTableInputType(table,"u_itemdesc")=="text") focusTableInput(table,"u_itemdesc");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getInput("u_prepaid")!=0) {
				page.statusbar.showWarning("You cannot add materials if consulation is not charged.");
				return false;
			}
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
		case "T2":
			if (getTableInputType(table,"u_itemcode")=="text") {
				if (isTableInputEmpty(table,"u_itemcode")) return false;
			}
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			if (isTableInputNegative(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_freq")) return false;
			if (isTableInputNegative(table,"u_howmuch")) return false;
			if (isTableInputNegative(table,"u_uom2")) return false;
			break;
		case "T3":
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
	switch (table) {
		case "T1":
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getInput("u_prepaid")!=0) {
				page.statusbar.showWarning("You cannot update materials if consulation is not charged.");
				return false;
			}
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
		case "T2":
			if (getTableInputType(table,"u_itemcode")=="text") {
				if (isTableInputEmpty(table,"u_itemcode")) return false;
			}
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			if (isTableInputNegative(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_freq")) return false;
			if (isTableInputNegative(table,"u_howmuch")) return false;
			if (isTableInputNegative(table,"u_uom2")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getInput("u_prepaid")!=0) {
				page.statusbar.showWarning("You cannot delete materials if consulation is not charged.");
				return false;
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			if (elementFocused.substring(0,13)=="df_u_resultT1") {
				focusTableInput(table,"u_result",row);
			} else if (elementFocused.substring(0,14)=="df_u_remarksT1") {
				focusTableInput(table,"u_remarks",row);
			}
			break;
		case "T2":
			document.images['PictureImg'].src = getTableInput("T2","u_filepath",row);			
			break;
	}
	return params;
}

function setRequestNoFieldAttributesGPSHIS(enable) {
	if (enable) {
		enableInput("u_department");
		enableInput("u_doctorid");
		enableInput("u_reftype");
		enableInput("u_refno");
		enableInput("u_disccode");
		enableInput("u_pricelist");
		enableInput("u_itemcode");
		enableInput("u_unitprice");
		enableInput("u_amount");
	} else {
		disableInput("u_department");
		disableInput("u_doctorid");
		disableInput("u_reftype");
		disableInput("u_refno");
		disableInput("u_disccode");
		disableInput("u_pricelist");
		disableInput("u_itemcode");
		disableInput("u_unitprice");
		disableInput("u_amount");
	}
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamount += getTableInputNumeric("T1","u_linetotal",i);
		}
	}
	setInputAmount("u_itemamount",totalamount);	
	setInputAmount("u_totalamount",getInputNumeric("u_itemamount")+getInputNumeric("u_amount") );	
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

