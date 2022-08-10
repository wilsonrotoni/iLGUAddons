// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

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
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_fromdepartment")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_tfindate")) return false;
		if (isInputEmpty("u_tfintime")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_tfno")) return false;
		if (isInputNegative("u_amount")) return false;
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
		default:
			switch(column) {
				case "u_tfno":
					clearTable("T1",true);
					setRequestNoFieldAttributesGPSHIS(true);
					if (getInput("u_tfno")!="") {
						result = page.executeFormattedQuery("select a.u_fromdepartment, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_disccode, a.u_pricelist, a.u_scdisc, a.u_disconbill, a.u_discamount, a.u_vatamount as u_hdrvatamount, a.u_amount, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_discperc, b.u_scdiscamount, b.u_vatcode, b.u_vatrate, b.u_vatamount, b.u_unitprice, b.u_price, b.u_linetotal from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and docno='"+getInput("u_tfno")+"' and u_todepartment='"+getInput("u_todepartment")+"' and docstatus = 'O' ");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								for (iii = 0; iii < result.childNodes.length; iii++) {
									if (iii==0) {
										setInput("u_fromdepartment",result.childNodes.item(0).getAttribute("u_fromdepartment"));
										setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
										setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
										setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
										setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
										setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
										setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
										setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
										setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
										setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
										setInputAmount("u_discamount",result.childNodes.item(0).getAttribute("u_discamount"));
										setInputAmount("u_vatamount",result.childNodes.item(0).getAttribute("u_hdrvatamount"));
										setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
										
										if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
										else uncheckedInput("u_scdisc");
									}
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
									data["u_scdiscamount"] = formatNumericPercent(result.childNodes.item(iii).getAttribute("u_scdiscamount"));
									data["u_discperc"] = formatNumericPercent(result.childNodes.item(iii).getAttribute("u_discperc"));
									data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
									data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
									data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
									data["u_price"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_price"));
									data["u_vatamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_vatamount"));
									data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_linetotal"));
									insertTableRowFromArray("T1",data);
								}
								setRequestNoFieldAttributesGPSHIS(false);
							} else {
								setInput("u_refno","");
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Delivery No.");	
								return false;
							}
						} else {
							setInput("u_refno","");
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Delivery No. Try Again, if problem persists, check the connection.");	
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
		case "df_u_tfno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_fromdepartment,u_patientname from u_hismedsuptfs where u_todepartment='"+getInput("u_todepartment")+"' and docstatus ='O'")); 
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
	var params = new Array();
	return params;
}

function setRequestNoFieldAttributesGPSHIS(enable) {
	if (enable) {
		enableInput("u_todepartment");
	} else {
		disableInput("u_todepartment");
	}
}
