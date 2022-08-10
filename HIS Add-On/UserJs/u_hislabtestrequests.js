// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');

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
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

var oEditor;

	/*
(function() {
    //Setup some private variables

})();
*/

var medsupcflcloseonselect=true;

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_department")) return false;
		//if (isInputEmpty("u_type")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		if (isInputEmpty("u_requestdate")) return false;
		if (isInputEmpty("u_requesttime")) return false;
		if (isInputEmpty("u_gender")) return false;
		if (isInputEmpty("u_birthdate")) return false;
		/*if (getInput("u_reftype")!="IP") {
			if (isInputEmpty("u_doctorid")) return false;
		}*/
		if (isInputEmpty("u_requestby")) return false;
		if (isInputEmpty("u_requestdepartment")) return false;
		if (getTableRowCount("T1",true)==0) {
			page.statusbar.showWarning("At least one test is required.");
			focusTableInput("T1","u_type");
			return false;
		}
		if (getTableInput("T1","u_type")!="") {
			page.statusbar.showWarning("A test item is being added/edited.");
			return false;
		}
		if (!checkPricesGPSHIS()) return false;
		
		if (isInputNegative("u_amount")) return false;
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
	switch (table) {
		case "T1":
			switch (column) {
				case "u_quantity":
				case "u_price":
					computePatientLineTotalGPSHIS(table);
					break;
			}
			break;
		case "T2":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					return validatePatientItemGPSHIS(element,column,table,row,"MEDSUP");
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
		case "T3":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					return validatePatientItemGPSHIS(element,column,table,row,"MISC");
					break;
				case "u_unitprice":
					if (getTableInputNumeric(table,"u_price")==0) {
						setTableInputPrice(table,"u_price",getTableInputNumeric(table,"u_unitprice"));
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
				case "u_refno":
					result = validatePatientGPSHIS();
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC");
					return result;
					break;
				case "u_patientid":
					if (getInput("u_patientid")!="") {
						result = page.executeFormattedQuery("select code, name, u_birthdate, u_gender from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'and code='"+getInput("u_patientid")+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
								setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
								setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInput("u_birthdate","");
								setInput("u_gender","");
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInput("u_birthdate","");
							setInput("u_gender","");
							page.statusbar.showError("Error retrieving Patient. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_birthdate","");
						setInput("u_gender","");
					}
					break;
				case "u_patientname":
					if (getInput("u_patientname")!="") {
						setInput("u_patientid","");
						setInput("u_birthdate","");
						setInput("u_gender","");
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_birthdate","");
						setInput("u_gender","");
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
		case "T1":
			switch (column) {
				case "u_type":
					//return validatePatientLabItemGPSHIS(element,column,table,row);
					break;
			}
			break;
		default:
			switch (column) {
				case "u_reftype":
					setInput("u_refno","",true);
					disableInput("u_refno");
					disableInput("u_patientid");
					disableInput("u_patientname");
					disableInput("u_gender");
					disableInput("u_birthdate");
					setPrepaidGPSHIS();
					
					if (getInput("u_reftype")=="WI") {
						if (getInput("u_trxtype")=="RADIOLOGY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="HEARTSTATION") {
							setInput("u_pricelist",getPrivate("dfltpricelist"));
							setInput("u_paymentterm",getPrivate("dfltpaymentterm"));
							setInput("u_prepaid",1);
							setInput("u_disccode",getPrivate("dfltdisccode"));
							enableInput("u_patientid");
							enableInput("u_patientname");
							enableInput("u_gender");
							enableInput("u_birthdate");
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
				case "u_requestdepartment":
					if (getInput("u_trxtype")=="RADIOLOGY") {
						setInput("u_department",getInput("u_requestdepartment"),true);
					}
					break;
				case "u_department":
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					var result = setSectionData();
					if (result) {
						showAjaxProcess();
						clearTable("T1",true);
						computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal");
						u_ajaxloadu_hislabtesttypesbysection("df_u_typeT1",element.value,'',":");
						hideAjaxProcess();
					}
					return result;
					break;
				case "u_disccode":
					result = setDiscountData();
					//resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC");
					return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_isstat":
					resetPatientPricesGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC");
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
		case "df_u_refno":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (getInput("u_requestdepartment")=="") {
					if (getInput("u_reftype")=="IP") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where u_nursed=1 and docstatus not in ('Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where docstatus not in ('Discharged','Admitted')")); 
					}
				} else {
					if (getInput("u_reftype")=="IP") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where ((u_nursed=1 and u_department='"+getInput("u_requestdepartment")+"') or u_nursed=0) and docstatus not in ('Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where docstatus not in ('Discharged','Admitted')")); 
					}
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged','Admitted')")); 
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
		case "df_u_itemcodeT2":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1")); 
			if ((getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") && getInput("u_stocklink")=="1") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a, stockcardsummary b where 	b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.code not in ("+getTableInputGroupConCat("T2","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.code not in ("+getTableInputGroupConCat("T2","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_itemdescT2":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1")); 
			if ((getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") && getInput("u_stocklink")=="1") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_itemcodeT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_type='MISC' and u_active=1 and (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			break;
		case "df_u_itemdescT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_type='MISC' and u_active=1 and (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			break;
			
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hislabsubtests":
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_type");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_type")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T3":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				var data = new Array();
				var result = page.executeFormattedQuery("select u_itemcode, u_itemdesc, u_uom, u_qtyperpack, u_department from u_hisitempacks where code='"+getTableInput(table,"u_itemcode")+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (iii = 0; iii < result.childNodes.length; iii++) {
							data["u_packagecode"] = getTableInput(table,"u_itemcode");
							data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
							data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
							data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
							data["u_qtyperpack"] = formatNumber(result.childNodes.item(iii).getAttribute("u_qtyperpack"),"quantity");
							data["u_packageqty"] = formatNumber(getTableInput(table,"u_quantity"),"quantity");
							data["u_quantity"] = formatNumber(getTableInputNumeric(table,"u_quantity")*parseFloat(result.childNodes.item(iii).getAttribute("u_qtyperpack")),"quantity");
							data["u_department"] = result.childNodes.item(iii).getAttribute("u_department");
							insertTableRowFromArray("T4",data);
						}
					} else {
						page.statusbar.showError("No. Package Items");	
						hideAjaxProcess();
						return false;
					}
				} else {
					hideAjaxProcess();
					page.statusbar.showError("Error retrieving Package Items. Try Again, if problem persists, check the connection.");	
					return false;
				}				
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
		case "T2": 
		case "T3": 
			computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_type")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T3":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				var rc =  getTableRowCount("T4");
				for (i = 1; i <= rc; i++) {
					if (isTableRowDeleted("T4",i)==false) {
						if (getTableInput("T4","u_packagecode",i)==getTableInput(table,"u_itemcode")) {
							setTableInputQuantity("T4","u_packageqty",getTableInputNumeric(table,"u_quantity"),i);
							setTableInputQuantity("T4","u_quantity",getTableInputNumeric("T4","u_packageqty",i)*getTableInputNumeric("T4","u_qtyperpack",i),i);
						}
					}
				}
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
		case "T2": 
		case "T3": 
			computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T3":
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				clearTable("T4",true,"u_packagecode",getTableInput(table,"u_itemcode"));
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
		case "T2": 
		case "T3": 
			computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeEditRowGPSHIS(table,row) {
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	return params;
}

function resetPricesGPSHIS() {
	var rc =  getTableRowCount("T1"), totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getInput("u_pricelist")!="") {
				var result2 = ajaxxmlgetitemprice(getTableInput("T1","u_type",i),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
				setTableInputAmount("T1","u_amount",formatNumeric(result2.getAttribute("price"),'',0),i);
			} else {
				setTableInputAmount("T1","u_amount",0,i);
			}
			totalamount += getTableInputNumeric("T1","u_amount",i);
		}
	}
	if (getTableInput("T1","u_type")!="" && getInput("u_pricelist")!="") {
		var result2 = ajaxxmlgetitemprice(getTableInput("T1","u_type"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
		setTableInputAmount("T1","u_amount",formatNumeric(result2.getAttribute("price"),'',0));
	} else {
		setTableInputAmount("T1","u_amount",0);
	}
	setInputAmount("u_amount",totalamount);	
}

function checkPricesGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInputNumeric("T1","u_price",i)<=0) {
				page.statusbar.showError("Price is required.");	
				selectTableRow("T1",i);
				return false;	
			}
		}
	}
	return true;
}

