// page events
//page.events.add.load('onPageLoadGPSHIS');
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
//page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
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

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (getInputType("u_department")!="hidden") {
			if (isInputEmpty("u_department")) return false;
		}
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		if (isInputEmpty("u_startdate")) return false;
		if (isInput("u_starttime")) {
			if (isInputEmpty("u_starttime")) return false;
		}
		if (getInput("u_prepaid")=="1") {
			if (isInputEmpty("u_payrefno")) return false;
		}
		if (isInputNegative("u_amount")) return false;
		/*if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
			if (isInputEmpty("u_requestno")) return false;
		} else {*/
			if (getTableInput("T1","u_itemcode")!="") {
				page.statusbar.showError("An item is being added/edited.");
				return false;
			}
		/*}*/
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
	var data = new Array();
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
						if (getTableInput(table,"u_itemcode")!="") {
							if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
								result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemcode='"+getTableInput(table,"u_itemcode")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 and b.u_itemcode='"+getTableInput(table,"u_itemcode")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemcode='"+getTableInput(table,"u_itemcode")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and  a.u_fromdepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C' and b.u_itemcode='"+getTableInput(table,"u_itemcode")+"') as x group by name, code having u_quantity>0");
							} else {
								result = page.executeFormattedQuery("select code, name from u_hisitems where code='"+getTableInput(table,"u_itemcode")+"' and u_active=1");	 
							}
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
									setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
									setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
									if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
										setTableInputQuantity(table,"u_quantity",result.childNodes.item(0).getAttribute("u_quantity"));
									} else {
										setTableInputQuantity(table,"u_quantity",1);
									}
									var numperuomsa = 1; //result.childNodes.item(0).getAttribute("qtyperuomsa");
									if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
										setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_price"));
										setTableInputPrice(table,"u_price",result.childNodes.item(0).getAttribute("u_price"));
									} else {
										result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getGlobal("branch") + ";PRICELIST:"+getInput("u_pricelist") +";GETVAT:SALES");
										setTableInput(table,"u_vatcode",result.getAttribute("taxcode"));
										setTableInput(table,"u_vatrate",result.getAttribute("taxrate"));
										setTableInput(table,"u_unitprice",result.getAttribute("price"));
										setTableInput(table,"u_price",result.getAttribute("price"));
										setTableInputPrice(table,"u_price",(getTableInputNumeric(table,"u_price")/numperuomsa));
									}
									setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
									setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_linetotal"),getTableInputNumeric(table,"u_vatrate")));
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
							if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
								result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemdesc='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 and b.u_itemdesc='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemdesc='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C' and b.u_itemdesc='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"') as x group by name, code having u_quantity>0");
							} else {
								result = page.executeFormattedQuery("select code, name from u_hisitems where name='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"' and u_active=1");	 
							}
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
									setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
									setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
									if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
										setTableInputQuantity(table,"u_quantity",result.childNodes.item(0).getAttribute("u_quantity"));
									} else {
										setTableInputQuantity(table,"u_quantity",1);
									}
									var numperuomsa = 1; //result.childNodes.item(0).getAttribute("qtyperuomsa");
									if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
										setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_price"));
										setTableInputPrice(table,"u_price",result.childNodes.item(0).getAttribute("u_price"));
									} else {
										result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getGlobal("branch") + ";PRICELIST:"+getInput("u_pricelist") +";GETVAT:SALES");
										setTableInput(table,"u_vatcode",result.getAttribute("taxcode"));
										setTableInput(table,"u_vatrate",result.getAttribute("taxrate"));
										setTableInput(table,"u_unitprice",result.getAttribute("price"));
										setTableInput(table,"u_price",result.getAttribute("price"));
										setTableInputPrice(table,"u_price",(getTableInputNumeric(table,"u_price")/numperuomsa));
									}
									setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
									setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_linetotal"),getTableInputNumeric(table,"u_vatrate")));
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
				case "u_unitprice":
					setTableInputPrice(table,"u_price",getTableInputNumeric(table,"u_unitprice"));
					setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
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
				case "u_requestno":
					clearTable("T1",true);
					if (getInput("u_requestno")!="") {
						if ((getInput("u_trxtype")=="PHARMACY" && getPrivate("chrgpharma")=="0") || (getInput("u_trxtype")=="LABORATORY" && getPrivate("chrglab")=="0") || (getInput("u_trxtype")=="CSR" && getPrivate("chrgcsr")=="0")) {
							result = page.executeFormattedQuery("select a.u_fromdepartment, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_scdisc, a.u_disconbill, a.u_amount, a.u_vatamount as u_hdrvatamount, a.u_discamount, a.u_prepaid, a.u_payrefno, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate, b.u_vatamount, b.u_linetotal from u_hismedsuprequests a, u_hismedsuprequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_prepaid=1 and a.u_payrefno<>'' and a.docstatus = 'O' ");	 						
						} else if ((getInput("u_trxtype")=="PHARMACY" && getPrivate("chrgpharma")=="1") || (getInput("u_trxtype")=="LABORATORY" && getPrivate("chrglab")=="1") || (getInput("u_trxtype")=="CSR" && getPrivate("chrgcsr")=="0")) {
							result = page.executeFormattedQuery("select a.u_fromdepartment, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_scdisc, a.u_disconbill, a.u_amount, a.u_vatamount as u_hdrvatamount, a.u_discamount, a.u_prepaid, a.u_payrefno, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate, b.u_vatamount, b.u_linetotal from u_hismedsuprequests a, u_hismedsuprequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.u_todepartment='"+getInput("u_department")+"' and (u_prepaid<>1 or (u_prepaid=1 and u_payrefno<>'')) and a.docstatus = 'O' ");	 						
						} else if (getInput("u_trxtype")=="RADIOLOGY" || getInput("u_trxtype")=="DIALYSIS") {
							result = page.executeFormattedQuery("select a.u_fromdepartment, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_scdisc, a.u_disconbill, a.u_amount, a.u_vatamount as u_hdrvatamount, a.u_discamount, a.u_prepaid, a.u_payrefno, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate, b.u_vatamount, b.u_linetotal from u_hismedsuprequests a, u_hismedsuprequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_prepaid=1 and a.u_payrefno<>'' and a.docstatus = 'O' ");	 						
						}
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
										
										if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
										else uncheckedInput("u_scdisc");
									}
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
									data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_discperc"] = formatNumericPercent(result.childNodes.item(iii).getAttribute("u_discperc"));
									data["u_scdiscamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_scdiscamount"));
									data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
									data["u_price"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_price"));
									data["u_vatcode"] = result.childNodes.item(iii).getAttribute("u_vatcode");
									data["u_vatrate"] = result.childNodes.item(iii).getAttribute("u_vatrate");
									data["u_vatamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_vatamount"));
									data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_linetotal"));
									insertTableRowFromArray("T1",data);
								}
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Request No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Request No. Try Again, if problem persists, check the connection.");	
							return false;
						}
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
				case "u_reftype":
					if (getInput("u_reftype")=="WI") {
						/*
						if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
						} else {
							page.statusbar.showError("Walk-In not allowed in this section.");
							return false;
						}*/
					}
					setInput("u_refno","",true);
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

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_trxtype")=="IP"  || getInput("u_trxtype")=="OP") {
				if (isInputEmpty("u_department")) return false;
			}
			break;
		case "df_u_itemcodeT1":
		case "df_u_itemdescT1":
			//if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (getInputType("u_department")!="hidden") {
					if (isInputEmpty("u_department")) return false;
				}
				if (getInput("u_reftype")!="WI") {
					if (isInputEmpty("u_refno")) return false;
				}
			//}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select  u_itemcode, u_itemdesc, sum(u_quantity) as u_quantity from ( select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' union all select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_intransit=0 union all select b.u_itemdesc, b.u_itemcode, (b.u_quantity-b.u_rtqty)*-1 as u_quantity from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_quantity*-1 as u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.docstatus='C') as x where u_itemcode not in ("+getTableInputGroupConCat("T1","u_itemcode")+") group by u_itemdesc, u_itemcode having u_quantity>0"));
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Item Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
				params["params"] += "&cflselectionmode=2"; 			
				
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1 and code not in ("+getTableInputGroupConCat("T1","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Item Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			}
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisitems"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=600"; 	
			break;
		case "df_u_itemdescT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select  u_itemdesc, u_itemcode, sum(u_quantity) as u_quantity from ( select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' union all select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_intransit=0 union all select b.u_itemdesc, b.u_itemcode, (b.u_quantity-b.u_rtqty)*-1 as u_quantity from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_quantity*-1 as u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.docstatus='C') as x where u_itemdesc not in ("+getTableInputGroupConCat("T1","u_itemdesc")+") group by u_itemdesc, u_itemcode having u_quantity>0"));
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item Code`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
				params["params"] += "&cflselectionmode=2"; 			
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1 and name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			}
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisitems"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=600"; 	
			break;
		case "df_u_refno":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where u_nursed=1 and u_department='"+getInput("u_department")+"' and docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where u_department='"+getInput("u_department")+"' and docstatus not in ('Discharged','Admitted')")); 
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
				}
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			if ((getInput("u_trxtype")=="PHARMACY" && getPrivate("chrgpharma")=="0") || (getInput("u_trxtype")=="LABORATORY" && getPrivate("chrglab")=="0") || (getInput("u_trxtype")=="CSR" && getPrivate("chrgcsr")=="0")) {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_patientname, u_payrefno from u_hismedsuprequests where u_todepartment='"+getInput("u_department")+"' and u_prepaid=1 and u_payrefno<>'' and docstatus ='O'")); 
			} else if ((getInput("u_trxtype")=="PHARMACY" && getPrivate("chrgpharma")=="1") || (getInput("u_trxtype")=="LABORATORY" && getPrivate("chrglab")=="1") || (getInput("u_trxtype")=="CSR" && getPrivate("chrgcsr")=="1")) {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_patientname, u_payrefno from u_hismedsuprequests where u_todepartment='"+getInput("u_department")+"' and (u_prepaid<>1 or (u_prepaid=1 and u_payrefno<>'')) and docstatus ='O'")); 
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_patientname, u_payrefno from u_hismedsuprequests where u_todepartment='"+getInput("u_department")+"' and u_prepaid=1 and u_payrefno<>'' and docstatus ='O'")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`OR No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`15")); 			
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
			if (items.length>1) {
				var itemcodes = value.replace(/`/g,"','");
				result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemcode in ('"+itemcodes+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 and b.u_itemcode in ('"+itemcodes+"')  union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemcode in ('"+itemcodes+"')  union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and  a.u_fromdepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C' and b.u_itemcode in ('"+itemcodes+"')) as x group by name, code having u_quantity>0");			
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
						data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
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
			if (items.length>1) {
				var itemdescs = utils.addslashes(value).replace(/`/g,"','");
				result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemdesc in ('"+itemdescs+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 and b.u_itemdesc in ('"+itemdescs+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemdesc in ('"+itemdescs+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_department")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C' and b.u_itemdesc in ('"+itemdescs+"')) as x group by name, code having u_quantity>0");			
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
						data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
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
	switch (table) {
		case "T1":
			if (getTableInputType(table,"u_itemcode")=="text") {
				focusTableInput(table,"u_itemcode");
			} else if (getTableInputType(table,"u_itemdesc")=="text") {
				focusTableInput(table,"u_itemdesc");
			}
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
		case "T1":
			computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
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
		case "T1":
			computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount"); break;
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

