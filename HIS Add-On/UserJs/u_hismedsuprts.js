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
		if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
			if (isInputEmpty("u_requestno")) return false;
		} else {
			if (getTableInput("T1","u_itemcode")!="") {
				page.statusbar.showError("An item is being added/edited.");
				return false;
			}
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
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") {
							result = page.executeFormattedQuery("select b.u_itemcode, b.u_itemdesc, b.u_quantity-b.u_rtqty as u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode = '"+utils.addslashes(getTableInput(table,column))+"' and a.docno='"+getInput("u_issueno")+"' and a.u_department='"+getInput("u_department")+"' and a.docstatus not in ('CN') ");
						} else {
							result = page.executeFormattedQuery("select b.u_itemcode, b.u_itemdesc, b.u_quantity-b.u_rtqty as u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc = '"+utils.addslashes(getTableInput(table,column))+"' and a.docno='"+getInput("u_issueno")+"' and a.u_department='"+getInput("u_department")+"' and a.docstatus not in ('CN') ");
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								if (result.childNodes.item(0).getAttribute("u_quantity")==0) {
									setTableInput(table,"u_itemcode","");
									setTableInput(table,"u_itemdesc","");
									page.statusbar.showError("All issued qty have been returned already.");	
									return false;
								}
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("u_itemcode"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("u_itemdesc"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInputQuantity(table,"u_quantity",result.childNodes.item(0).getAttribute("u_quantity"));
								setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_price"));
								setTableInputPrice(table,"u_price",result.childNodes.item(0).getAttribute("u_price"));
								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
							} else {
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_itemdesc","");
								if (column=="u_itemcode") page.statusbar.showError("Invalid Item Code.");	
								else page.statusbar.showError("Invalid Item Description.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							if (column=="u_itemcode") page.statusbar.showError("Error retrieving Item Code. Try Again, if problem persists, check the connection.");	
							else page.statusbar.showError("Error retrieving Item Description. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_quantity":
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
				case "u_issueno":
					clearTable("T1",true);
					if (getInput("u_issueno")!="") {
						result = page.executeFormattedQuery("select a.u_stocklink, a.u_department, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_pricelist, a.u_disccode, a.u_scdisc, a.u_disconbill, a.u_amount, a.u_vatamount as u_hdrvatamount, a.u_discamount, a.u_prepaid, a.u_payrefno from u_hismedsups a where a.docno='"+getInput("u_issueno")+"' and a.docstatus not in ('CN') ");
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
								setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
								setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
								setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
								setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
								setInput("u_stocklink",result.childNodes.item(0).getAttribute("u_stocklink"));
								
								if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
								else uncheckedInput("u_scdisc");
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Issue No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Issue No. Try Again, if problem persists, check the connection.");	
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
						if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
						} else {
							page.statusbar.showError("Walk-In not allowed in this section.");
							return false;
						}
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
				if (isInputEmpty("u_department")) return false;
				if (isInputEmpty("u_refno")) return false;
			//}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_rtqty, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc not in ("+getTableInputGroupConCat("T1","u_itemdesc")+") and  a.docno='"+getInput("u_issueno")+"' and a.u_department='"+getInput("u_department")+"' and a.docstatus not in ('CN') "));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Item Description`Issued Qty`Returned Qty`UoM")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("12`45`10`14`5")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity`quantity`")); 			
			params["params"] += "&cflselectionmode=2"; 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select b.u_itemdesc, b.u_itemcode, b.u_quantity, b.u_rtqty, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc not in ("+getTableInputGroupConCat("T1","u_itemdesc")+") and  a.docno='"+getInput("u_issueno")+"' and a.u_department='"+getInput("u_department")+"' and a.docstatus not in ('CN') "));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item Code`Issued Qty`Returned Qty`UoM")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("45`12`10`14`5")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity`quantity`")); 			
			params["params"] += "&cflselectionmode=2"; 			
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
		case "df_u_issueno":
			if (getInput("u_department")!="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_startdate, u_patientname from u_hismedsups where u_department='"+getInput("u_department")+"'  and docstatus not in ('CN')")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Patient Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`")); 			
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_startdate, u_department, u_patientname from u_hismedsups where docstatus not in ('CN')")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Department`Patient Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`20`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``")); 			
			}
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT1":
		case "df_u_itemdescT1":
			var items = value.split('`');
			if (items.length>1) {
				var values = utils.addslashes(value).replace(/`/g,"','");
				if (Id=="df_u_itemcodeT1") {
					result = page.executeFormattedQuery("select b.u_itemcode, b.u_itemdesc, b.u_quantity-b.u_rtqty as u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('"+values+"') and b.u_rtqty<b.u_quantity and a.docno='"+getInput("u_issueno")+"' and a.u_department='"+getInput("u_department")+"' and a.docstatus not in ('CN') ");			
				} else {
					result = page.executeFormattedQuery("select b.u_itemcode, b.u_itemdesc, b.u_quantity-b.u_rtqty as u_quantity, b.u_uom, b.u_scdiscamount, b.u_discperc, b.u_unitprice, b.u_price, b.u_vatcode, b.u_vatrate from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc in ('"+values+"') and b.u_rtqty<b.u_quantity and a.docno='"+getInput("u_issueno")+"' and a.u_department='"+getInput("u_department")+"' and a.docstatus not in ('CN') ");			
				}
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
						data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
						data["u_vatcode"] = result.childNodes.item(iii).getAttribute("u_vatcode");
						data["u_vatrate"] = result.childNodes.item(iii).getAttribute("u_vatrate");
						data["u_quantity"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
						data["u_scdiscamount"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_scdiscamount"));
						data["u_discperc"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_discperc"));
						data["u_unitprice"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_unitprice"));
						data["u_price"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_price"));
						data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_quantity") * result.childNodes.item(iii).getAttribute("u_price"));
						data["u_vatamount"] = formatNumericAmount(utils.computeVAT(result.childNodes.item(iii).getAttribute("u_quantity") * result.childNodes.item(iii).getAttribute("u_price"),result.childNodes.item(iii).getAttribute("u_vatrate")));
						
						insertTableRowFromArray("T1",data);
					}
					computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount");
				} else {
					page.statusbar.showError("Error retrieving Selected Values. Try Again, if problem persists, check the connection.");	
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

