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
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

var itemcflcloseonselect=true;

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_fromdepartment")) return false;
		if (isInputEmpty("u_todepartment")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_tfdate")) return false;
		if (isInputEmpty("u_tftime")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputNegative("u_amount")) return false;
		
		if (getTableInput("T1","u_itemcode")!="") {
			page.statusbar.showError("An item is being added/edited.");
			return false;
		}
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

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {		
				var items = value.split('`');
				if (items.length>1) {
					var itemcodes = value.replace(/`/g,"','");
					result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemcode in ('"+itemcodes+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 and b.u_itemcode in ('"+itemcodes+"')  union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemcode in ('"+itemcodes+"')  union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and  a.u_fromdepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C' and b.u_itemcode in ('"+itemcodes+"')) as x group by name, code having u_quantity>0");			
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
			} else {
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
			}
			break;
		case "df_u_itemdescT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {		
				var items = value.split('`');
				if (items.length>1) {
					var itemdescs = utils.addslashes(value).replace(/`/g,"','");
					result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemdesc in ('"+itemdescs+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 and b.u_itemdesc in ('"+itemdescs+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and b.u_itemdesc in ('"+itemdescs+"') union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C' and b.u_itemdesc in ('"+itemdescs+"')) as x group by name, code having u_quantity>0");			
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
			} else {
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
			}
			break;
	}
	return true;
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
				case "u_requestno":
					resetTableRow("T1");
					clearTable("T1",true);
					computePatientTotalAmountGPSHIS(table,"u_amount","u_vatamount","u_discamount");
					setRequestNoFieldAttributesGPSHIS(true);
					if (getInput("u_requestno")!="") {
						result = page.executeFormattedQuery("select a.u_fromdepartment, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_disccode, a.u_pricelist, a.u_amount, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_unitprice, b.u_price, b.u_linetotal from u_hismedsuprequests a, u_hismedsuprequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_prepaid=0 and a.docstatus = 'O' ");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								for (iii = 0; iii < result.childNodes.length; iii++) {
									if (iii==0) {
										setInput("u_todepartment",result.childNodes.item(0).getAttribute("u_fromdepartment"));
										setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
										setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
										setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
										setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
										setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
										setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
										setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
										setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
										setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
									}
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
									data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
									data["u_price"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_price"));
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
				case "u_fromdepartment":
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					return setSectionData(column);
				case "u_todepartment":
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					break;
				case "u_reftype":	
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
		case "df_u_itemcodeT1":
		case "df_u_itemdescT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (isInputEmpty("u_fromdepartment")) return false;
				if (isInputEmpty("u_todepartment")) return false;
				if (isInputEmpty("u_refno")) return false;
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select  u_itemcode, u_itemdesc, sum(u_quantity) as u_quantity from ( select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' union all select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 union all select b.u_itemdesc, b.u_itemcode, (b.u_quantity-b.u_rtqty)*-1 as u_quantity from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_quantity*-1 as u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C') as x where u_itemcode not in ("+getTableInputGroupConCat("T1","u_itemcode")+") group by u_itemdesc, u_itemcode having u_quantity>0"));
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
				params["params"] += "&cflselectionmode=2";
			} else {
				if (getInput("u_stocklink")=="1") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a, stockcardsummary b where 	b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code from u_hisitems a where u_active=1 and u_department='"+getInput("u_fromdepartment")+"'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				}
				if (!itemcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_itemdescT1":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select  u_itemdesc, u_itemcode, sum(u_quantity) as u_quantity from ( select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' union all select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.u_intransit=0 union all select b.u_itemdesc, b.u_itemcode, (b.u_quantity-b.u_rtqty)*-1 as u_quantity from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' union all select b.u_itemdesc, b.u_itemcode, b.u_quantity*-1 as u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_patientid='"+getInput("u_patientid")+"' and a.docstatus='C') as x where u_itemdesc not in ("+getTableInputGroupConCat("T1","u_itemdesc")+") group by u_itemdesc, u_itemcode having u_quantity>0"));
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
				params["params"] += "&cflselectionmode=2"; 			
			} else {
				if (getInput("u_stocklink")=="1") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code`In-Stock")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`10")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code from u_hisitems a where u_active=1 and u_department='"+getInput("u_fromdepartment")+"'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				}
				if (!itemcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_refno":
			if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_department='"+getInput("u_fromdepartment")+"' and docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm from u_hisops where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_department='"+getInput("u_fromdepartment")+"' and docstatus not in ('Discharged')")); 
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm  from u_hisips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm  from u_hisops where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus not in ('Discharged')")); 
				}
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment Term")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			if (getInput("u_todepartment")=="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_fromdepartment,u_patientname from u_hismedsuprequests where u_todepartment='"+getInput("u_fromdepartment")+"' and u_prepaid=0 and docstatus ='O'")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_fromdepartment,u_patientname from u_hismedsuprequests where u_todepartment='"+getInput("u_fromdepartment")+"' and u_fromdepartment='"+getInput("u_todepartment")+"' and u_prepaid=0 and docstatus ='O'")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`From Deppartment`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			/*if (getInput("u_requestno")!="") {
				page.statusbar.showError("You cannot add new item if request exists.");
				return false;
			}*/
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
			//if (isTableInputNegative(table,"u_quantity")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
			if (isTableInputNegative(table,"u_unitprice")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			//if (isTableInputNegative(table,"u_linetotal")) return false;
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
			break;
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
		enableInput("u_fromdepartment");
		enableInput("u_todepartment");
		enableInput("u_reftype");
		enableInput("u_refno");
		enableInput("u_disccode");
		enableInput("u_pricelist");
	} else {
		disableInput("u_fromdepartment");
		disableInput("u_todepartment");
		disableInput("u_reftype");
		disableInput("u_refno");
		disableInput("u_disccode");
		disableInput("u_pricelist");
	}
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

