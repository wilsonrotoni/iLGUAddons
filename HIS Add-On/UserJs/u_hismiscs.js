// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');

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
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

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
		if (getInputType("u_department")!="hidden") {
			if (isInputEmpty("u_department")) return false;
		}
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInput("u_starttime")) {
			if (isInputEmpty("u_starttime")) return false;
		}
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputNegative("u_amount")) return false;
		
		if (getTableInput("T1","u_itemcode")!="") {
			page.statusbar.showWarning("An item is being added/edited.");
			return false;
		}
		
		if (!checkPricesGPSHIS()) return false;
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
	switch (Id) {
		case "find":
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
	}
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
	var result, data = new Array();
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					return validatePatientItemGPSHIS(element,column,table,row);
				case "u_unitprice":
					if (getTableInputNumeric(table,"u_price")==0) {
						computePatientLineTotalGPSHIS(table);
					}
					break;
				case "u_quantity":
				case "u_price":
					computePatientLineTotalGPSHIS(table);
					break;
			}
			break;
		default:
			switch(column) {
				case "u_reftype":
					setInput("u_refno","",true);
					break;
				case "u_refno":
					result = validatePatientGPSHIS();
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					return result;
					break;
				case "u_requestno":
					setRequestNoFieldAttributesGPSHIS(true);
					clearTable("T1",true);
					if (getInput("u_requestno")!="") {
						result = page.executeFormattedQuery("select a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_disccode, a.u_scdisc, a.u_disconbill, a.u_pricelist, a.u_discamount, a.u_vatamount as hdrvatamount, a.u_amount, a.u_prepaid, a.u_payrefno, b.u_itemcode, b.u_itemdesc, b.u_scdiscamount, b.u_quantity, b.u_uom, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate, b.u_vatamount, b.u_linetotal from u_hismiscrequests a, u_hismiscrequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.u_department='"+getInput("u_department")+"' and (a.u_prepaid=0 or (a.u_prepaid=1 and a.u_payrefno<>''))  and a.docstatus = 'O' ");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								for (iii = 0; iii < result.childNodes.length; iii++) {
									if (iii==0) {
										setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
										setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
										setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
										setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
										setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
										setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
										setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
										setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
										setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
										setInputAmount("u_discamount",result.childNodes.item(0).getAttribute("u_discamount"));
										setInputAmount("u_vatamount",result.childNodes.item(0).getAttribute("u_hdrvatamount"));
										setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
										setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
										setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
										
										if (result.childNodes.item(iii).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
										else uncheckedInput("u_scdisc");
								
									}
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
									data["u_discperc"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_discperc"));
									data["u_scdiscamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_scdiscamount"));
									data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
									data["u_price"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_price"));
									data["u_vatcode"] = result.childNodes.item(iii).getAttribute("u_vatcode");
									data["u_vatrate"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_vatrate"));
									data["u_vatamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_vatamount"));
									data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_linetotal"));
									insertTableRowFromArray("T1",data);
								}
								setRequestNoFieldAttributesGPSHIS(false);
							} else {
								setInput("u_refno","");
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Request No.");	
								return false;
							}
						} else {
							setInput("u_refno","");
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Request No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_refno","");
						setInput("u_patientid","");
						setInput("u_patientname","");
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
					if (getInput("u_department")!="") {
						result = page.executeFormattedQuery("select u_disconbill from u_hissections where code='"+getInput("u_department")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
							} else {
								page.statusbar.showError("Invalid Section for this patient.");	
								return false;
							}
						} else {
							page.statusbar.showError("Error retrieving Section. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
					}
					break;
				case "u_disccode":
					result = setDiscountData();
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
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
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisservices where u_group='MSC' and u_active=1 and (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisservices where u_group='MSC' and u_active=1 and (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			break;
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment Term")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_requestdepartment,u_patientname from u_hismiscrequests where u_department='"+getInput("u_department")+"' and (u_prepaid=0 or (u_prepaid=1 and u_payrefno<>'')) and docstatus ='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`From Department`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT1":
			var items = value.split('`');
			if (items.length>0) {
				var itemcodes = value.replace(/`/g,"','");
				result = page.executeFormattedQuery("select code, name from u_hisservices where u_group='MSC' and u_active=1 and code in ('"+itemcodes+"')");			
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_quantity"] = formatNumericQuantity(1);
						insertTableRowFromArray("T1",data);
					}
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
				} else {
					page.statusbar.showError("Error retrieving Item Codes. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				
				return false;
			}
			break;
		case "df_u_itemdescT1":
			var items = value.split('`');
			if (items.length>0) {
				var itemdescs = utils.addslashes(value).replace(/`/g,"','");
				result = page.executeFormattedQuery("select code, name from u_hisservices where u_group='MSC' and u_active=1 and name in ('"+itemdescs+"')");			
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_quantity"] = formatNumericQuantity(1);
						insertTableRowFromArray("T1",data);
					}
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
				} else {
					page.statusbar.showError("Error retrieving Item Descriptions. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				
				return false;
			}
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getInput("u_requestno")!="") {
				page.statusbar.showError("You cannot add new item if request exists.");
				return false;
			}
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getInput("u_requestno")!="") {
				page.statusbar.showError("You cannot add new item if request exists.");
				return false;
			}
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getInput("u_requestno")!="") {
				page.statusbar.showError("You cannot add new item if request exists.");
				return false;
			}
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
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


function checkPricesGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInputNumeric("T1","u_linetotal",i)<=0) {
				page.statusbar.showError("Line Total is required.");	
				selectTableRow("T1",i);
				return false;	
			}
		}
	}
	return true;
}

