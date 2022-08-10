// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
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
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	//document.images['PictureImg'].src = "";
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISPLIST") {
				setInput("u_reftype",window.opener.getInput("u_reftype"),true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
				focusInput("u_requestno");
			} else {
				if (getInput("u_requestno")=="") {
					if (getInput("u_department")!="") setInput("u_department",getInput("u_department"));
					//setRequestnoGPSHIS();
					setInput("u_requestno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")));
					setTimeout("setRequestNoGPSHIS()",2000);
				}
			}
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_department")) return false;
		//if (isInputEmpty("u_testtype")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		if (getInput("u_requestno")!="") {
			if (isInputEmpty("u_reqdate",null,null,"tab1",1)) return false;
			if (isInputEmpty("u_reqtime",null,null,"tab1",1)) return false;
		}
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_gender",null,null,"tab1",1)) return false;
			if (isInputEmpty("u_birthdate",null,null,"tab1",1)) return false;
		}
		//if (isInputEmpty("u_doctorid")) return false;
		if (!checkPricesGPSHIS()) return false;
		if (getInputNumeric("u_amount")==0) {
			if (window.confirm(getInputCaption("u_amount") + " is zero. Continue?")==false)	return false;
		}
		
		if (getInputNumeric("u_amount")==getInputNumeric("u_requestamount") && getInput("u_startdate")==getInput("u_requestdate") && getInput("u_payrefno")!="" && getInput("u_prepaid")!="0") {
			if (getInput("u_requesttype")=="CHRG") {
				if (window.confirm("If you are crediting all paid rendered items on the same date, Please cancel the rendered document instead.")==false)	return false;
			} else {
				if (window.confirm("If you are crediting all paid request items on the same date, cashier must cancel the receipt then cancel the request document.")==false)	return false;
			}
		}
		if (isInputEmpty("u_remarks")) return false;

		if (!checkauthenticateGPSHIS()) return false;

	} else if (action=="cnd") {
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
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

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			if (window.opener.getVar("objectcode").toLowerCase()=="u_hisplist") {
				if (action=="a") {
					var row = window.opener.getTableSelectedRow("T1");
					if (row>0) window.opener.setTableInput("T1","u_req","?",row);
				}
				if (action=="a") OpenReportSelect('printer');
			} else {
				window.opener.formSearchNow();
				if (action=="a") OpenReportSelect('printer');
			}
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
				focusTableInput("T51","remarks");
			} else if (sc_press == "ENTER" && column=="remarks") {
				formSubmit();
			}
			break;
	}
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
					setTableInputPrice(table,"u_price",utils.subtractPerc(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_discperc")));
					computePatientLineTotalGPSHIS(table);
					break;
				case "u_quantity":
					if (row>0) {
						if (getTableInput(table,"u_ispackage",row)=="1") {
							showAjaxProcess();
							var rc =  getTableRowCount("T2");
							for (i = 1; i <= rc; i++) {
								if (isTableRowDeleted("T2",i)==false) {
									if (getTableInput("T2","u_packagecode",i)==getTableInput(table,"u_itemcode",row)) {
										setTableInputQuantity("T2","u_packageqty",getTableInputNumeric(table,"u_quantity",row),i);
										setTableInputQuantity("T2","u_quantity",getTableInputNumeric("T2","u_packageqty",i)*getTableInputNumeric("T2","u_qtyperpack",i),i);
									}
								}
							}
							hideAjaxProcess();
						}
					}				
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
					break;
				case "u_price":
					computePatientLineTotalGPSHIS(table);
					break;
				case "u_doctorname":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select a.code, a.name from u_hisdoctors a where a.u_active=1 and a.name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_doctorid",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_doctorname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_doctorid","");
								setTableInput(table,"u_doctorname","");
								page.statusbar.showError("Invalid Doctor.");	
								return false;
							}
						} else {
							setTableInput(table,"u_doctorid","");
							setTableInput(table,"u_doctorname","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_doctorid","");
						setTableInput(table,"u_doctorname","");
					}
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
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC,PF");
					return result;
					break;
				case "u_requestno":
					setRequestNoFieldAttributesGPSHIS(true);
					clearTable("T1",true);
					clearTable("T2",true);
					if (getInput("u_requestno")!="") {
						return setRequestNoGPSHIS();
					} else {
						//if (isInputEnabled("u_department")) setInput("u_department","",true);
						setInput("u_refno","");
						setInput("u_payrefno","");
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_paymentterm","");
						setInput("u_disccode","");
						setInput("u_pricelist","");
						setInput("u_payreftype","");
						setInput("u_requestdate","");
						setInputAmount("u_requestamount",0);
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
	var result;
	switch (table) {
		case "T1":
			switch (column) {
				case "u_itemgroup":
					focusTableInput(table,"u_itemdesc");
					break
			}
			break;
		default:
			switch (column) {
				case "u_department":
					var result = setSectionData();
					if (result) {
						showAjaxProcess();
						clearTable("T1",true);
						clearTable("T2",true);
						//computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal");
						if (isInput("u_testtype")) {
							u_ajaxloadu_hislabtesttypesbysection("df_u_testtype",element.value,'',":");
						}
						hideAjaxProcess();
					}
					return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC,PF");
					break;
				case "u_disccode":
					result = setDiscountData();
					if (result) resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC,PF");
					return result;
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_selected":
					if (row>0) {
						showAjaxProcess();
						if (isTableInputChecked(table,column,row)) {
							enableTableInput("T1","u_quantity",row);
							checkedTableInput("T2","u_selected",-1,"u_packagecode",getTableInput(table,"u_itemcode",row));
						} else {
							disableTableInput("T1","u_quantity",row);
							uncheckedTableInput("T2","u_selected",-1,"u_packagecode",getTableInput(table,"u_itemcode",row));
						}
						computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");						
						hideAjaxProcess();		
					}
					break;
				case "u_isautodisc":
					if (getTableInput(table,"u_itemgroup")!="PRF" && getTableInput(table,"u_itemgroup")!="PRM") {
						page.statusbar.showError("Only Professional Fee and Materials are allowed for manual discounts.");
						return false;
					}
					if (isTableInputChecked(table,"u_isautodisc")) {
						setTableInputPrice(table,"u_price",utils.subtractPerc(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_discperc")));
						computePatientLineTotalGPSHIS(table);
						disableTableInput(table,"u_price");
					} else {
						enableTableInput(table,"u_price");
					}
					break;
				case "u_isfoc":
					computePatientLineTotalGPSHIS(table);
					break;
				case "u_isstat":
					if (isTableInputChecked(table,"u_isstat",row) && getTableInputNumeric(table,"u_statperc",row)==0) {
						page.statusbar.showError("Item is not allowed for stat.");
						return false;
					}
					computePatientItemPriceGPSHIS(table,row);
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 

					break;
			}
			break;
		default:
			switch (column) {
				case "u_isstat":
					if (getInput(column)=="1") {
						checkedTableInput("T1","u_isstat",null,"u_statperc",0,">");
						checkedTableInput("T1","u_isstat",-1,"u_statperc",0,">");
					} else {
						uncheckedTableInput("T1","u_isstat",null,"u_statperc",0,">");
						uncheckedTableInput("T1","u_isstat",-1,"u_statperc",0,">");
					}
					//computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					break;
				case "u_requesttype":
					clearTable("T1",true);
					clearTable("T2",true);
					setInput("u_patientid","");
					setInput("u_patientname","");
					setInput("u_requestno","");
					setInput("u_payrefno","");
					enableInput("u_reftype");
					enableInput("u_refno");
					break;
			}
	}
	return true;
}


function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_requestno":
			if (isInputEmpty("u_department")) return false;
			break;
		case "df_u_itemcodeT4":
		case "df_u_itemdescT4":
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_department")) return false;
				if (isInputEmpty("u_refno")) return false;
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')) from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')) from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment`Term")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			var refnoExp = "";
			if (getInput("u_refno")!="") refnoExp = " and a.u_refno='"+getInput("u_refno")+"' "; 
			if (getInput("u_requesttype")=="REQ") {
				if (getInput("u_department")=="") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_requestdate, b.name, a.u_patientname, a.u_payrefno from u_hisrequests a, u_hissections b where b.code=a.u_department and (a.u_prepaid in (1,2) and a.u_payrefno<>'') and a.docstatus='O'" + refnoExp)); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_requestdate, b.name,a.u_patientname, a.u_payrefno from u_hisrequests a, u_hissections b where b.code=a.u_department and a.u_department='"+getInput("u_department")+"' and (a.u_prepaid in (1,2) and a.u_payrefno<>'') and a.docstatus='O'" + refnoExp)); 
				}
			} else {
				if (getInput("u_department")=="") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_startdate, b.name, a.u_patientname, a.u_payrefno from u_hischarges a, u_hissections b where b.code=a.u_department and a.docstatus='O'" + refnoExp)); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_startdate, b.name,a.u_patientname, a.u_payrefno from u_hischarges a, u_hissections b where b.code=a.u_department and a.u_department='"+getInput("u_department")+"' and a.docstatus='O'" + refnoExp)); 
				}
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Date`No.`Section`Patient Name`Receipt No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`12`20`35`12")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date```")); 			
			break;
		case "df_u_itemcodeT4":
			var itemgroupexp="";
			if (getTableInput("T1","u_itemgroup")!="") itemgroupexp=" and u_group='"+getTableInput("T1","u_itemgroup")+"'";
			var departmentexp=" and  (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')";
			if (getInput("u_showallitems")=="0") departmentexp=" and u_department='"+getInput("u_department")+"'";
			
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1 "+itemgroupexp+departmentexp)); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0";
			break;
		case "df_u_itemdescT4":
			var itemgroupexp="";
			if (getTableInput("T1","u_itemgroup")!="") itemgroupexp=" and u_group='"+getTableInput("T1","u_itemgroup")+"'";
			var departmentexp=" and  (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')";
			if (getInput("u_showallitems")=="0") departmentexp=" and u_department='"+getInput("u_department")+"'";
			
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_hisitems where u_active=1 "+itemgroupexp+departmentexp)); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0";
			break;
		case "df_u_doctornameT4":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_hisdoctors where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT4":
		case "df_u_itemdescT4":
			var items = value.split('`');
			if (items.length>0) {
				value = value.replace(/`/g,"','");
				if (Id=="df_u_itemcodeT4") {
					result = page.executeFormattedQuery("select code, name, u_group, u_salespricing, u_statperc, u_allowdiscount, u_billdiscount, u_ispackage, u_template from u_hisitems where u_active=1 and code in ('"+value+"')");			
				} else {
					result = page.executeFormattedQuery("select code, name, u_group, u_salespricing, u_statperc, u_allowdiscount, u_billdiscount, u_ispackage, u_template from u_hisitems where u_active=1 and name in ('"+value+"')");			
				}
				if (result.getAttribute("result")!= "-1") {
					var valid=true;
					for (var xxx=0; xxx<result.childNodes.length; xxx++) {
						valid=true;
						if (isInput("u_testtype")) {
							if (getInput("u_testtype")=="") {
								setInput("u_testtype",result.childNodes.item(xxx).getAttribute("u_template"));
							} else if (result.childNodes.item(xxx).getAttribute("u_template")!="" && getInput("u_testtype")!=result.childNodes.item(xxx).getAttribute("u_template")) {	
								page.statusbar.showWarning("You cannot mix ["+result.childNodes.item(xxx).getAttribute("name")+"] which has different result template.");
								valid=false;
							}
						}
						if (valid) {
							data["u_itemcode"] = result.childNodes.item(xxx).getAttribute("code");
							data["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("name");
							data["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_group");
							data["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
							data["u_quantity"] = formatNumericQuantity(1);
							data["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
							data["u_pricing"] = result.childNodes.item(xxx).getAttribute("u_salespricing");
							data["u_isstat"] = 0;
							if (result.childNodes.item(xxx).getAttribute("u_statperc")>0) {
								data["u_isstat"] = getInput("u_isstat");
							}
							data["u_statperc"] = formatNumericPercent(result.childNodes.item(xxx).getAttribute("u_statperc"));
							data["u_iscashdisc"] = result.childNodes.item(xxx).getAttribute("u_allowdiscount");
							data["u_isbilldisc"] = result.childNodes.item(xxx).getAttribute("u_billdiscount");
							if (isTableInputUnique("T1","u_itemcode",data["u_itemcode"],getTableInputCaption("T1","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
								if (data["u_ispackage"]=="1") {
									if (setPatientPackageItemsGPSHIS("T2",data["u_itemcode"],1)) {
										insertTableRowFromArray("T1",data);
									}
								} else insertTableRowFromArray("T1",data);
							} else break;
						}
					}
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
					computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
				} else {
					page.statusbar.showError("Error retrieving Items. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				return false;
			}
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			setTableInput(table,"u_isstat",getInput("u_isstat"));
			enableTableInput(table,"u_itemcode");
			enableTableInput(table,"u_itemdesc");
			disableTableInput(table,"u_unitprice");			
			disableTableInput(table,"u_price");			
			focusTableInput(table,"u_itemcode");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_itemgroup")=="PRF" || getTableInput(table,"u_itemgroup")=="PRM") {
				if (isTableInputEmpty(table,"u_doctorname")) return false;
				if (isTableInputEmpty(table,"u_doctorid")) return false;
			}
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				setPatientPackageItemsGPSHIS("T2",getTableInput(table,"u_itemcode"),getTableInputNumeric(table,"u_quantity"));
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_itemgroup")=="PRF" || getTableInput(table,"u_itemgroup")=="PRM") {
				if (isTableInputEmpty(table,"u_doctorname")) return false;
				if (isTableInputEmpty(table,"u_doctorid")) return false;
			}
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				var rc =  getTableRowCount("T2");
				for (i = 1; i <= rc; i++) {
					if (isTableRowDeleted("T2",i)==false) {
						if (getTableInput("T2","u_packagecode",i)==getTableInput(table,"u_itemcode")) {
							setTableInput("T2","u_selected",getTableInput(table,"u_selected"),i);
							setTableInputQuantity("T2","u_packageqty",getTableInputNumeric(table,"u_quantity"),i);
							setTableInputQuantity("T2","u_quantity",getTableInputNumeric("T2","u_packageqty",i)*getTableInputNumeric("T2","u_qtyperpack",i),i);
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
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				clearTable("T2",true,"u_packagecode",getTableInput(table,"u_itemcode"));
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeEditRowGPSHIS(table,row) {
	var targetObjectId = '';
	switch (table) {
		case "T1": 
			if (getTableInput(table,"u_ispackage")=="1" || getTableInputNumeric(table,"u_chrgqty")!=0) {
				disableTableInput(table,"u_itemcode");
				disableTableInput(table,"u_itemdesc");
			} else {
				focusTableInput(table,"u_quantity");
			}

			if (isTableInputChecked(table,"u_isautodisc")) {
				disableTableInput(table,"u_price");
			} else {
				enableTableInput(table,"u_price");
			}

			if (getTableInput(table,"u_pricing")=="-1") {
				enableTableInput(table,"u_unitprice");
			} else {
				disableTableInput(table,"u_unitprice");
			}
			break;
	}
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			if (elementFocused.substring(0,15)=="df_u_quantityT1") {
				focusTableInput(table,"u_quantity",row);
			}
			break;
	}
	return params;

}

function checkPricesGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_selected",i)=="1") {
				if (getTableInputNumeric("T1","u_quantity",i)<=0) {
					page.statusbar.showError("Quantity is required.");	
					selectTab("tab1-1",0);
					selectTableRow("T1",i);
					focusTableInput("T1","u_quantity",i);
					return false;	
				}
				if (getTableInput("T1","u_isfoc",i)=="0" && getTableInputNumeric("T1","u_linetotal",i)<=0) {
					page.statusbar.showError("Line Total is required.");	
					selectTab("tab1-1",0);
					selectTableRow("T1",i);
					return false;	
				}
				computePatientLineTotalGPSHIS("T1",i);
			}
		}
	}
	computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
	return true;
}

function setRequestNoGPSHIS(){
	var data1 = new Array();
	var data2 = new Array();
	var data3 = new Array();
	if (getInput("u_requesttype")=="REQ") {
		var result = page.executeFormattedQuery("select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_amount, a.u_payrefno, a.u_payreftype, a.u_doctorid, 'ITEM' as u_type, b.u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, (b.u_quantity-b.u_chrgqty) - b.u_rtqty as u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, b.u_linetotal, b.u_vatcode, b.u_vatrate, b.u_isstat as u_isstat2, b.u_statperc, b.u_statunitprice, b.u_scdiscamount, b.u_discperc, b.u_doctorid as u_pfid, b.u_doctorname as u_pfname, '' as u_packagecode, 0 as u_packageqty, 0 as u_qtyperpack, '' as u_packdepartment, '' as u_packdepartmentname, b.u_ispackage, b.u_template, b.u_isfoc from u_hisrequests a, u_hisrequestitems b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid in (1,2) and a.u_payrefno<>'') and a.docstatus='O' and (b.u_rtqty<(b.u_quantity-b.u_chrgqty)) union all select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_amount, a.u_payrefno, a.u_payreftype, a.u_doctorid, 'ITEMPACK' as u_type, '' as u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, (b.u_quantity-b.u_chrgqty) - b.u_rtqty as u_quantity, 0 as u_unitprice, 0 as u_discamount, 0 as u_price, 0 as u_vatamount, 0 as u_linetotal, 0 as u_vatcode, 0 as u_vatrate,0 as u_isstat2, 0 as u_statperc, 0 as u_statunitprice, 0 as u_scdiscamount, 0 as u_discperc, '' as u_pfid, '' as u_pfname, b.u_packagecode, b.u_packageqty, b.u_qtyperpack, b.u_department as u_packdepartment, d.name as u_packdepartmentname, 0 as u_ispackage, '' as u_template, 0 as u_isfoc from u_hisrequests a, u_hisrequestitempacks b, u_hisitems c, u_hissections d where d.code=b.u_department and c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid in (1,2) and a.u_payrefno<>'') and a.docstatus='O' and (b.u_rtqty<(b.u_quantity-b.u_chrgqty))");
	} else {
		var result = page.executeFormattedQuery("select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_startdate as u_requestdate, a.u_starttime as u_requesttime, a.u_isstat, a.u_prepaid, a.u_amount, a.u_payrefno, a.u_payreftype, a.u_doctorid, 'ITEM' as u_type, b.u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity - b.u_rtqty as u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, b.u_linetotal, b.u_vatcode, b.u_vatrate, b.u_isstat as u_isstat2, b.u_statperc, b.u_statunitprice, b.u_scdiscamount, b.u_discperc, b.u_doctorid as u_pfid, b.u_doctorname as u_pfname, '' as u_packagecode, 0 as u_packageqty, 0 as u_qtyperpack, '' as u_packdepartment, '' as u_packdepartmentname, b.u_ispackage, b.u_template, b.u_isfoc from u_hischarges a, u_hischargeitems b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.docstatus='O' and b.u_rtqty<b.u_quantity union all select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_startdate as u_requestdate, a.u_starttime as u_requesttime, a.u_isstat, a.u_prepaid, a.u_amount, a.u_payrefno, a.u_payreftype, a.u_doctorid, 'ITEMPACK' as u_type, '' as u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity - b.u_rtqty as u_quantity, 0 as u_unitprice, 0 as u_discamount, 0 as u_price, 0 as u_vatamount, 0 as u_linetotal, 0 as u_vatcode, 0 as u_vatrate,0 as u_isstat2, 0 as u_statperc, 0 as u_statunitprice, 0 as u_scdiscamount, 0 as u_discperc, '' as u_pfid, '' as u_pfname, b.u_packagecode, b.u_packageqty, b.u_qtyperpack, b.u_department as u_packdepartment, d.name as u_packdepartmentname, 0 as u_ispackage, '' as u_template, 0 as u_isfoc from u_hischarges a, u_hischargeitempacks b, u_hisitems c, u_hissections d where d.code=b.u_department and c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.docstatus='O' and b.u_rtqty<b.u_quantity");
	}
	/*
	*/
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			var valid=true;
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				if (xxx==0) {
					setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
					setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
					setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
					setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
					setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
					setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
					setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
					setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
					setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
					setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
					setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
					setInput("u_payreftype",result.childNodes.item(0).getAttribute("u_payreftype"));
					setInput("u_requestdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_requestdate")));
					setInputAmount("u_requestamount",result.childNodes.item(0).getAttribute("u_amount"));

					if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
					else uncheckedInput("u_scdisc");

					if (result.childNodes.item(0).getAttribute("u_isstat")=="1") checkedInput("u_isstat");
					else uncheckedInput("u_isstat");
				}
				if (result.childNodes.item(xxx).getAttribute("u_type")=="ITEM") {
					data1["u_selected"] = 0;
					data1["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_itemgroup");
					data1["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
					data1["u_itemcode"] = result.childNodes.item(xxx).getAttribute("u_itemcode");
					data1["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("u_itemdesc");
					data1["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
					data1["u_quantity"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_quantity"),"quantity");
					data1["u_vatcode"] = result.childNodes.item(xxx).getAttribute("u_vatcode");
					data1["u_vatrate"] = result.childNodes.item(xxx).getAttribute("u_vatrate");
					data1["u_unitprice"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_unitprice"),"price");
					data1["u_discperc"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_discperc"),"percent");
					data1["u_isstat"] = result.childNodes.item(xxx).getAttribute("u_isstat2");
					data1["u_statperc"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_statperc"),"percent");
					data1["u_statunitprice"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_statunitprice"),"price");
					data1["u_scdiscamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_scdiscamount"),"amount");
					data1["u_discamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_discamount"),"amount");
					data1["u_vatamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_vatamount"),"amount");
					data1["u_price"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_price"),"price");
					data1["u_linetotal"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_linetotal"),"amount");
					data1["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
					data1["u_doctorid"] = result.childNodes.item(xxx).getAttribute("u_pfid");
					data1["u_doctorname"] = result.childNodes.item(xxx).getAttribute("u_pfname");
					data1["u_isfoc"] = result.childNodes.item(xxx).getAttribute("u_isfoc");
					if (data1["u_ispackage"]=="1") data3["u_ispackage.text"] = "Yes";
					else data1["u_ispackage.text"] = "No";
					insertTableRowFromArray("T1",data1);
					disableTableInput("T1","u_isstat",getTableRowCount("T1"));
					computePatientLineTotalGPSHIS("T1",getTableRowCount("T1"));
				} else if (result.childNodes.item(xxx).getAttribute("u_type")=="ITEMPACK") {
					data2["u_selected"] = 0;
					data2["u_packagecode"] = result.childNodes.item(xxx).getAttribute("u_packagecode");
					data2["u_packageqty"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_packageqty"),"quantity");
					data2["u_qtyperpack"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_qtyperpack"),"quantity");
					data2["u_itemcode"] = result.childNodes.item(xxx).getAttribute("u_itemcode");
					data2["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("u_itemdesc");
					data2["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
					data2["u_quantity"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_quantity"),"quantity");
					data2["u_department"] = result.childNodes.item(xxx).getAttribute("u_department");
					//data2["u_department"] = result.childNodes.item(xxx).getAttribute("u_packdepartment");
					//data2["u_department.text"] = result.childNodes.item(xxx).getAttribute("u_packdepartmentname");
					insertTableRowFromArray("T2",data2);
					disableTableInput("T2","u_selected",getTableRowCount("T2"));
				}
			}
			/*
			if (getInput("u_type")!="" && getInput("u_pricelist")!="") {
				var result2 = ajaxxmlgetitemprice(getInput("u_type"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
				setInputAmount("u_amount",formatNumeric(result2.getAttribute("price"),'',0));
			} else {
				setInputAmount("u_amount",0);
			}
			setInputAmount("u_totalamount",getInputNumeric("u_amount")+getInputNumeric("u_statamount"));
			*/
			setRequestNoFieldAttributesGPSHIS(false);
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal","EXAM,MEDSUP,MISC,PF");
		} else {
			setInput("u_refno","");
			setInput("u_patientid","");
			setInput("u_patientname","");
			page.statusbar.showError("Invalid Reference No.");	
			return false;
		}
	} else {
		setInput("u_refno","");
		setInput("u_patientid","");
		setInput("u_patientname","");
		page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
		return false;
	}	
	return true;
}

function setRequestNoFieldAttributesGPSHIS(enable) {
	if (enable) {
		enableInput("u_isstat");
		//enableInput("u_department");
		enableInput("u_reftype");
		enableInput("u_refno");
		enableInput("u_disccode");
		enableInput("u_pricelist");
		//enableInput("u_quantity");
	} else {
		disableInput("u_isstat");
		//disableInput("u_department");
		disableInput("u_reftype");
		disableInput("u_refno");
		disableInput("u_disccode");
		disableInput("u_pricelist");
		//disableInput("u_quantity");
	}
}

function releaseGPSHIS() {
	selectTab("tab1",1);
	selectTab("tab1-2",0);
	if (isInputEmpty("u_testby")) return false;
	if (isInputEmpty("u_enddate")) return false;
	if (isInputEmpty("u_endtime")) return false;
	if (isInputEmpty("u_doctorid2")) return false;
	formSubmit();
}

function showreopenGPSHIS() {
	showPopupFrame('popupFrameReOpen',true)
	focusInput("u_reopenremarks");
}

function reopenGPSHIS() {
	if (isInputEmpty("u_reopenremarks")) return false;
	//setInput("u_enddate","");
	//setInput("u_endtime","");
	formSubmit();
}

function uploadAttachment() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,mp4,pdf";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Examinations/" + getInput("docno") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function deleteAttachmentGPSHIS() {
	var rc = getTableSelectedRow("T2");	
	if (getTableSelectedRow("T2")>0) {
		if (ajaxdeleteattachment(getTableInput("T2","u_filepath",rc))) {
			formEdit();
		}
	} else page.statusbar.showWarning("No selected attachment to delete.");
}

function u_sltemplateGPSHIS() {
	if (isInputEmpty("u_doctorid2")) return false;
	selectTab("tab1",1);
	showPopupFrame("popupFrameNotesTemplate",true);
}

function u_ajaxloadu_hislabtesttypenotes(p_elementid, p_u_testtype, p_u_doctorid,p_value,p_filler) {
	http = getHTTPObject(); // We create the HTTP Object
	document.getElementById(p_elementid).innerHTML = "<option>Loading..</option>"
	http.open("GET", "udp.php?ajax=1&objectcode=u_ajaxslu_hislabtesttypenotes&u_type=" + p_u_testtype + "&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	document.getElementById(p_elementid).innerHTML = http.responseText;
	document.getElementById('ajaxPending').value = "";	
	
}

function OpenLnkBtnRequestNoGPSHIS(targetId) {
	if (getInput("u_requesttype")=="REQ") {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
	} else {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
	}
	
}

function OpenLnkBtnu_hisexamcases(targetId) {
	OpenLnkBtn(450,380,'./udo.php?objectcode=u_hisexamcases' + '' + '&targetId=' + targetId ,targetId);
	
}