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
page.elements.events.add.click('onElementClickGPSHIS');
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
	if (getVar("formSubmitAction")=="a") {
		if (getInput("u_type")=="Sales") setInputSelectedText("docseries","Sales",true);
		else setInputSelectedText("docseries","Primary",true);
		try {
			if (window.opener.getVar("objectcode")=="U_HISSTOCKTRXLIST") {
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
		if (isInputEmpty("u_fromdepartment")) return false;
		if (!isInputChecked("u_multipledepartment")) {
			if (isInputEmpty("u_todepartment")) return false;
		}
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_type")) return false;
		if (getInput("u_type")=="Inter-Branch Transfer") {
			if (getInput("u_todepartment").substr(0,3)!="BR-") {
				page.statusbar.showError("You can only do Inter-Branch Transfer on a Branch.");
				return false;
			}
		}
		if (getInput("u_todepartment").substr(0,3)=="BR-") {
			if (getInput("u_type")!="Inter-Branch Transfer") {
				page.statusbar.showError("You can only do Inter-Branch Transfer on a Branch.");
				return false;
			}
		}
		//if (isInputNegative("u_totalamount")) return false;
		/*if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
			if (isInputEmpty("u_requestno")) return false;
		} else {*/
			if (getTableInput("T1","u_itemcode")!="") {
				page.statusbar.showError("An item is being added/edited.");
				return false;
			}
		/*}*/
		if (!checkInStockGPSHIS()) return false;
	} else if (action=="?") {
		if (isInputEmpty("u_fromdepartment")) return false;
		if (isInputEmpty("u_todepartment")) return false;
		if (isInputChecked("u_cos")) {
			if (isInputEmpty("u_cosstartdate")) return false;
			if (isInputEmpty("u_cosenddate")) return false;
		}
	}

	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			if (action=="generateinstock") {
				iframeDownloader.location = "../Addons/GPS/HIS Add-On/UserPrograms/Export/"+getGlobal("sessionid")+".xls";
				//alert("Operation ended successfully.");
			}	
			if (window.opener.getVar("objectcode").toLowerCase()=="u_hisstocktrxlist") {
				if (action=="a") {
					window.opener.formSearchNow();
					//window.close();
				}
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
}

function onElementValidateGPSHIS(element,column,table,row) {
	var data = new Array();
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
					if (getTableInput(table,"u_itemcode")!="") {
						result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, b.instockqty, a.u_uompu, a.u_numperuompu from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and a.u_active=1 and a.code='"+getTableInput(table,"u_itemcode")+"'");
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInput(table,"u_uompu",result.childNodes.item(0).getAttribute("u_uompu"));
								setTableInput(table,"u_numperuompu",result.childNodes.item(0).getAttribute("u_numperuompu"));
								setTableInputQuantity(table,"u_instock",result.childNodes.item(0).getAttribute("instockqty"));
								var numperuomsa = 1; //result.childNodes.item(0).getAttribute("qtyperuomsa");
								result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:{BS}");
								setTableInput(table,"u_unitprice",result.getAttribute("price"));

								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
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
						result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, b.instockqty, a.u_uompu, a.u_numperuompu from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and a.u_active=1 and a.name='"+utils.addslashes(getTableInput(table,"u_itemdesc"))+"'");
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInput(table,"u_uompu",result.childNodes.item(0).getAttribute("u_uompu"));
								setTableInput(table,"u_numperuompu",result.childNodes.item(0).getAttribute("u_numperuompu"));
								setTableInputQuantity(table,"u_instock",result.childNodes.item(0).getAttribute("instockqty"));
								var numperuomsa = 1; //result.childNodes.item(0).getAttribute("qtyperuomsa");
								result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:{BS}");
								setTableInput(table,"u_unitprice",result.getAttribute("price"));
								
								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
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
					setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
					break;
				case "u_quantity":
					if (row==0) {
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
						setTableInputQuantity(table,"u_qtypu",utils.divide(getTableInputNumeric(table,"u_quantity"),getTableInputNumeric(table,"u_numperuompu")));
					} else {
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_unitprice",row),row);
						setTableInputQuantity(table,"u_qtypu",utils.divide(getTableInputNumeric(table,"u_quantity",row),getTableInputNumeric(table,"u_numperuompu",row)),row);
					}
					break;
				case "u_qtypu":
					if (row==0) {
						setTableInputQuantity(table,"u_quantity",getTableInputNumeric(table,"u_qtypu") * getTableInputNumeric(table,"u_numperuompu"));
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
					} else {
						setTableInputQuantity(table,"u_quantity",getTableInputNumeric(table,"u_qtypu",row) * getTableInputNumeric(table,"u_numperuompu",row),row);
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_unitprice",row),row);
					}
					break;
			}
			break;
		default:
			switch(column) {
				case "u_requestno":
					clearTable("T1",true);
					if (getInput("u_requestno")!="") {
						result = page.executeFormattedQuery("select a.u_fromdepartment, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, b.u_unitprice, b.u_linetotal, ifnull(c.instockqty,0) as instockqty from u_hismedsupstockrequests a, u_hismedsupstockrequestitems b left join stockcardsummary c on c.company=b.company and c.branch=b.branch and c.itemcode=b.u_itemcode and c.warehouse='"+getInput("u_fromdepartment")+"' and c.instockqty>0 where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and b.u_reqtype in ('GI') and a.docstatus = 'O' ");	 						
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								for (iii = 0; iii < result.childNodes.length; iii++) {
									if (iii==0) {
										setInput("u_todepartment",result.childNodes.item(iii).getAttribute("u_fromdepartment"));
									}
									if (parseFloat(result.childNodes.item(iii).getAttribute("instockqty"))>0) {
										data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
										data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
										data["u_reqqty"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_quantity"));
										data["u_instock"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("instockqty"));
										data["u_quantity"] = formatNumericQuantity(Math.min(result.childNodes.item(iii).getAttribute("instockqty"),result.childNodes.item(iii).getAttribute("u_quantity")));
										data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
										data["u_todepartment"] = result.childNodes.item(iii).getAttribute("u_fromdepartment");
										data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_unitprice"));
										data["u_linetotal"] = formatNumericAmount(utils.multiply(Math.min(result.childNodes.item(iii).getAttribute("instockqty"),result.childNodes.item(iii).getAttribute("u_quantity")),result.childNodes.item(iii).getAttribute("u_unitprice")));
										insertTableRowFromArray("T1",data);
									}
								}
							} else {
								page.statusbar.showError("Invalid Request No.");	
								return false;
							}
							computeTotalGPSHIS();
						} else {
							page.statusbar.showError("Error retrieving Request No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
					}
					break;
				case "u_cosstartdate":
					setInput("u_cosenddate",getInput("u_cosstartdate"));
					setInput("u_docdate",getInput("u_cosenddate"));
					break;
				case "u_cosenddate":
					setInput("u_docdate",getInput("u_cosenddate"));
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
					if (getInput("u_trxtype")!="WAREHOUSE") {
						setInput("u_todepartment",getInput("u_fromdepartment"));
					}
					break;
				case "u_todepartment":
					setTableInput("T1","u_todepartment",getInput("u_todepartment"),-1,getInputSelectedText("u_todepartment"));
					break;
				case "u_reftype":
					if (getInput("u_reftype")=="WI") {
						if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
						} else {
							page.statusbar.showError("Walk-In not allowed in this section.");
							return false;
						}
					}
					break;
				case "u_type":
					if (getInput("u_type")=="Sales") setInputSelectedText("docseries","Sales",true);
					else setInputSelectedText("docseries","Primary",true);
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
				case "u_multipledepartment":
					if (isInputChecked(column)) {
						disableInput("u_todepartment");
						enableTableInput("T1","u_todepartment");
					} else {
						enableInput("u_todepartment");
						disableTableInput("T1","u_todepartment");
					}
					break;
				case "u_cos":
					if (isInputChecked(column)) setInput("u_type","Sales",true);
					break;
			}
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_itemcodeT1":
		case "df_u_itemdescT1":
			//if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP") {
				if (isInputEmpty("u_fromdepartment")) return false;
			//}
			if (isInputChecked("u_multipledepartment")) {
				if (isTableInputEmpty("T1","u_todepartment")) return false;
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a, stockcardsummary b where 	b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 			
			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code`In-Stock")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 			
			break;
		case "df_u_requestno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_docdate, a.u_fromdepartment, a.u_remarks from u_hismedsupstockrequests a inner join u_hismedsupstockrequestitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.u_todepartment='"+getInput("u_fromdepartment")+"' and b.u_reqtype in ('GI') and b.u_linestatus='' and a.docstatus ='O' group by a.docno")); 
			
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`From Section`Remarks")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("12`12`30`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``")); 			
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
			//if (items.length>0) {
				value = value.replace(/`/g,"','");
				if (Id=="df_u_itemcodeT1") {
					result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, ifnull(b.instockqty,0) as instockqty, ifnull(d.price/a.u_numperuompu,0) as price from u_hisitems a left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where code in ('"+value+"')");			
				} else {
					result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, ifnull(b.instockqty,0) as instockqty, ifnull(d.price/a.u_numperuompu,0) as price from u_hisitems a left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where a.name in ('"+utils.addslashes(value)+"') and u_active=1 ");			
				}
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_uompu"] = result.childNodes.item(iii).getAttribute("u_uompu");
						data["u_numperuompu"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_numperuompu"));
						//if (isTableInputUnique("T1","u_itemcode",data["u_itemcode"],getTableInputCaption("T1","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
							data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
							data["u_reqqty"] = formatNumericQuantity(0);
							data["u_instock"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("instockqty"));
							data["u_quantity"] = formatNumericQuantity(1);
							data["u_qtypu"] = formatNumericQuantity(utils.divide(1,result.childNodes.item(iii).getAttribute("u_numperuompu")));
							
							data["u_todepartment"] = getTableInput("T1","u_todepartment");
							data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("price"));
							data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("price"));
							insertTableRowFromArray("T1",data);
						//}
					}
					//resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
					//computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
					computeTotalGPSHIS();
				} else {
					page.statusbar.showError("Error retrieving Items. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				return false;
			//}
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
			//if (isTableInputNegative(table,"u_unitprice")) return false;
			//if (isTableInputNegative(table,"u_linetotal")) return false;
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
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			//if (isTableInputEmpty(table,"u_whscode")) return false;
			//if (isTableInputNegative(table,"u_unitprice")) return false;
			//if (isTableInputNegative(table,"u_linetotal")) return false;
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
			if (elementFocused.substring(0,15)=="df_u_quantityT1") {
				focusTableInput(table,"u_quantity",row);
			} else if (elementFocused.substring(0,12)=="df_u_qtypuT1") {
				focusTableInput(table,"u_qtypu",row);
			} else if (elementFocused.substring(0,16)=="df_u_unitpriceT1") {
				focusTableInput(table,"u_unitprice",row);
			}
			break;
		case "T2":
			document.images['PictureImg'].src = getTableInput("T2","u_filepath",row);			
			break;
	}
	return params;
}

function checkInStockGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (iii = 1; iii <= rc; iii++) {
		if (isTableRowDeleted("T1",iii)==false) {
			if (getTableInputNumeric("T1","u_instock",iii)-getTableInputNumeric("T1","u_quantity",iii)<0) {
				page.statusbar.showError("Negative Stock detected.");	
				selectTableRow("T1",iii);
				focusTableInput("T1","u_quantity",iii);
				return false;	
			}
			/*if (getTableInputNumeric("T1","u_quantity",iii)<0) {			
				page.statusbar.showError("Negative Issuance detected.");	
				selectTableRow("T1",iii);
				focusTableInput("T1","u_quantity",iii);
				return false;	
			}*/
		}
	}
	return true;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamount += getTableInputNumeric("T1","u_linetotal",i);
		}
	}
	setInputAmount("u_totalamount",totalamount);	
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

