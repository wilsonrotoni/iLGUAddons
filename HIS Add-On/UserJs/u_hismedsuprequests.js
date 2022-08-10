// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
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
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

var itemcflcloseonselect=true;

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		try {
			if (getInput("u_fromdepartment")!="") setInput("u_fromdepartment",getInput("u_fromdepartment"),true);
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_fromdepartment")) return false;
		if (isInputEmpty("u_todepartment")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		if (isInputNegative("u_amount")) return false;
		
		if (getTableInput("T1","u_itemcode")!="") {
			page.statusbar.showWarning("An item is being added/edited.");
			return false;
		}
		
		if (!checkPricesGPSHIS()) return false;
	}
	return true;
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
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					return validatePatientItemGPSHIS(element,column,table,row);
					break;
				case "u_unitprice":
					setTableInputPrice(table,"u_price",getTableInputNumeric(table,"u_unitprice"));
					computePatientLineTotalGPSHIS(table);
					break;
				case "u_quantity":
				case "u_price":
					computePatientLineTotalGPSHIS(table);
					break;
			}
			break;
		default:
			switch(column) {
				case "u_refno":
					result = validatePatientGPSHIS();
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					return result;
					break;
				case "u_patientid":
					if (getInput("u_patientid")!="") {
						result = page.executeFormattedQuery("select code, name from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'and code='"+getInput("u_patientid")+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Patient. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
					}
					break;
				case "u_patientname":
					if (getInput("u_patientname")!="") {
						setInput("u_patientid","");
					} else {
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
				case "u_fromdepartment":
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY" || getInput("u_trxtype")=="DIALYSIS") {
						setInput("u_todepartment",getInput("u_fromdepartment"),true);
					}
					return setSectionData(column);
					break;
				case "u_todepartment":
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					break;
				case "u_reftype":
					setInput("u_refno","",true);
					disableInput("u_refno");
					disableInput("u_patientid");
					disableInput("u_patientname");
					setPrepaidGPSHIS();
					
					if (getInput("u_reftype")=="WI") {
						if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") {
							setInput("u_pricelist",getPrivate("dfltpricelist"));
							setInput("u_paymentterm",getPrivate("dfltpaymentterm"));
							setInput("u_prepaid",1);
							setInput("u_disccode",getPrivate("dfltdisccode"));
							enableInput("u_patientid");
							enableInput("u_patientname");
							focusInput("u_patientname");
						} else {
							page.statusbar.showError("Walk-In not allowed in this section.");
							return false;
						}
					} else {
						enableInput("u_refno");
						focusInput("u_refno");
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
	switch (table) {
		default:
			switch (column) {
				case "u_walkin":
					disableInput("u_refno");
					disableInput("u_reftype");
					disableInput("u_patientname");
					if (getInput("u_walkin")=="1") {
						enableInput("u_patientname");
						focusInput("u_patientname");
					} else {
						enableInput("u_refno");
						enableInput("u_reftype");
						focusInput("u_reftype");
					}
					break;
			}
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT1":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1")); 
			if ((getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") && getInput("u_stocklink")=="1") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a, stockcardsummary b where 	b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_active=1 and a.code not in ("+getTableInputGroupConCat("T1","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!itemcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_active=1 and u_department='"+getInput("u_todepartment")+"' and a.code not in ("+getTableInputGroupConCat("T1","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!itemcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_itemdescT1":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1")); 
			if ((getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") && getInput("u_stocklink")=="1") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_active=1 and a.name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!itemcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_active=1 and u_department='"+getInput("u_todepartment")+"' and a.name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!itemcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_refno":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where ((u_nursed=1 and u_department='"+getInput("u_fromdepartment")+"') or u_nursed=0) and docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where docstatus not in ('Discharged','Admitted')")); 
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where docstatus not in ('Discharged','Admitted')")); 
				}
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment Term")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_patientid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_active=1"));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=name";
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT1":
			if (!itemcflcloseonselect) {
				var items = value.split('`');
				if (items.length>0) {
					var itemcodes = utils.addslashes(value).replace(/`/g,"','");
					result = page.executeFormattedQuery("select code, name, u_uom, u_brandname, u_genericname from u_hisitems where code in ('"+itemcodes+"')");			
					if (result.getAttribute("result")!= "-1") {
						for (var iii=0; iii<result.childNodes.length; iii++) {
							if (getInput("u_reftype")=="IP") {
								var result2 = page.executeFormattedQuery("select u_name from u_hisips a inner join u_hisipallergicmeds b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_name='"+result.childNodes.item(iii).getAttribute("u_brandname")+"' or b.u_name='"+result.childNodes.item(iii).getAttribute("u_genericname")+"') where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"'");			
							} else {
								var result2 = page.executeFormattedQuery("select u_name from u_hisops a inner join u_hisopallergicmeds b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_name='"+result.childNodes.item(iii).getAttribute("u_brandname")+"' or b.u_name='"+result.childNodes.item(iii).getAttribute("u_genericname")+"') where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"'");			
							}
							if (result2.getAttribute("result")!= "-1") {
								if (parseInt(result2.getAttribute("result"))==0) {
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_quantity"] = formatNumericQuantity(1);
									insertTableRowFromArray("T1",data);
								} else {
									page.statusbar.showError("Patient is Allergic to " + result2.childNodes.item(iii).getAttribute("u_name"));
								}
							} else {
								page.statusbar.showError("Error retrieving Patient Allergic Info for Item "+result.childNodes.item(iii).getAttribute("name")+". Try Again, if problem persists, check the connection.");	
								return false;
							}
						}
						resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					} else {
						page.statusbar.showError("Error retrieving Item Codes. Try Again, if problem persists, check the connection.");	
						return false;
					}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
					
					return false;
				}
			}
			break;
		case "df_u_itemdescT1":
			if (!itemcflcloseonselect) {
				var items = value.split('`');
				if (items.length>0) {
					var itemdescs = utils.addslashes(value).replace(/`/g,"','");
					result = page.executeFormattedQuery("select code, name, u_uom, u_brandname, u_genericname from u_hisitems where name in ('"+itemdescs+"')");			
					if (result.getAttribute("result")!= "-1") {
						for (var iii=0; iii<result.childNodes.length; iii++) {
							if (getInput("u_reftype")=="IP") {
								var result2 = page.executeFormattedQuery("select u_name from u_hisips a inner join u_hisipallergicmeds b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_name='"+result.childNodes.item(iii).getAttribute("u_brandname")+"' or b.u_name='"+result.childNodes.item(iii).getAttribute("u_genericname")+"') where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"'");			
							} else {
								var result2 = page.executeFormattedQuery("select u_name from u_hisops a inner join u_hisopallergicmeds b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_name='"+result.childNodes.item(iii).getAttribute("u_brandname")+"' or b.u_name='"+result.childNodes.item(iii).getAttribute("u_genericname")+"') where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"'");			
							}
							if (result2.getAttribute("result")!= "-1") {
								if (parseInt(result2.getAttribute("result"))==0) {
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_quantity"] = formatNumericQuantity(1);
									insertTableRowFromArray("T1",data);
								} else {
									page.statusbar.showError("Patient is Allergic to " + result2.childNodes.item(iii).getAttribute("u_name"));
								}
							} else {
								page.statusbar.showError("Error retrieving Patient Allergic Info for Item "+result.childNodes.item(iii).getAttribute("name")+". Try Again, if problem persists, check the connection.");	
								return false;
							}
						}
						resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					} else {
						page.statusbar.showError("Error retrieving Item Descriptions. Try Again, if problem persists, check the connection.");	
						return false;
					}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
					
					return false;
				}
			}
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table,isinsert) {
	switch (table) {
		case "T1":
			if (isinsert) focusTableInput(table,"u_itemcode");
			else focusElement("divT1");
			break;
	}
}

function onTableAfterEditRowGPSHIS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_quantity");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			if (isTableInputNegative(table,"u_linetotal")) return false;
			break;
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
	switch (table) {
		case "T1": computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
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

