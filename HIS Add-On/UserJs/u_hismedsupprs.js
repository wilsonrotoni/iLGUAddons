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
		if (isInputChecked("u_replenish")) {
			showPopupFrame("popupFrameFilter");
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_department")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_reqdate")) return false;
		if (isInputNegative("u_totalamount")) return false;
		
		if (getTableInput("T1","u_itemcode")!="") {
			page.statusbar.showError("An item is being added/edited.");
			return false;
		}
		if (!checkPricesGPSHIS()) return false;
		
		if (getInput("u_suppno")=="") {
			if (window.confirm("Supplier Name was not selected. Preferred Supplier will be use instead. Continue?")==false) return false;
		}

	} else if (action=="?") {
		if (isInputEmpty("u_department")) return false;
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
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") {
							result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, ifnull(b.instockqty,0) as instockqty, a.u_preferredsuppno, c.suppname as u_preferredsuppname, ifnull(d.price,0) as price from u_hisitems a left join  suppliers c on c.suppno=a.u_preferredsuppno left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where code='"+getTableInput(table,"u_itemcode")+"' and u_active=1");	
						} else {
							result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, ifnull(b.instockqty,0) as instockqty, a.u_preferredsuppno, c.suppname as u_preferredsuppname, ifnull(d.price,0) as price from u_hisitems a left join  suppliers c on c.suppno=a.u_preferredsuppno left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where a.name='"+getTableInput(table,"u_itemdesc")+"' and u_active=1");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInput(table,"u_uompu",result.childNodes.item(0).getAttribute("u_uompu"));
								setTableInputQuantity(table,"u_numperuom",result.childNodes.item(0).getAttribute("u_numperuompu"));
								if (getInput("u_suppno")!="") {
									setTableInput(table,"u_preferredsuppno",getInput("u_suppno"));
									setTableInput(table,"u_preferredsuppname",getInput("u_suppname"));
								} else {
									setTableInput(table,"u_preferredsuppno",result.childNodes.item(0).getAttribute("u_preferredsuppno"));
									setTableInput(table,"u_preferredsuppname",result.childNodes.item(0).getAttribute("u_preferredsuppname"));
								}
								if (getTableInput(table,"u_preferredsuppno")=="") {
									setTableInput(table,"u_preferredsuppno",getPrivate("prsuppno"));
									setTableInput(table,"u_preferredsuppname",getPrivate("prsuppname"));
								}
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputQuantity(table,"u_instock",result.childNodes.item(0).getAttribute("instockqty"));
								setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("price"));
								
								//result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:{BS}");
								//setTableInput(table,"u_unitprice",result.getAttribute("price"));
								
								setTableInputQuantity(table,"u_quantitypu",utils.divide(getTableInputNumeric(table,"u_quantity"),getTableInputNumeric(table,"u_numperuom")));
								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_unitprice") * getTableInputNumeric(table,"u_quantitypu"));
								
/*
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
						data["u_uompu"] = result.childNodes.item(iii).getAttribute("u_uompu");
						data["u_numperuom"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_numperuompu"));
						if (getInput("u_suppno")!="") {
							data["u_preferredsuppno"] = getInput("u_suppno");
							data["u_preferredsuppname"] = getInput("u_suppname");
						} else {
							data["u_preferredsuppno"] = result.childNodes.item(iii).getAttribute("u_preferredsuppno");
							data["u_preferredsuppname"] = result.childNodes.item(iii).getAttribute("u_preferredsuppname");
						}
						if (data["u_preferredsuppno"]=="") {
							data["u_preferredsuppno"] = getPrivate("prsuppno");
							data["u_preferredsuppname"] = getPrivate("prsuppname");
						}
						data["u_instock"] = result.childNodes.item(iii).getAttribute("instockqty");
						data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("price"));
						//var result = ajaxxmlgetitemprice(data["u_itemcode"],"BRANCH:" + getInput("u_branch") + ";PRICELIST:{BS}");
						//data["u_unitprice"] = result.getAttribute("price");
						data["u_quantitypu"] = formatNumericAmount(utils.divide(1,result.childNodes.item(iii).getAttribute("u_numperuompu")));
						data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("price")*(utils.divide(1,result.childNodes.item(iii).getAttribute("u_numperuompu"))));

*/
							} else {
								setTableInput(table,"u_itemdesc","");
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_preferredsuppno","");
								setTableInput(table,"u_preferredsuppname","");
								page.statusbar.showError("Invalid Item Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemdesc","");
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_preferredsuppno","");
							setTableInput(table,"u_preferredsuppname","");
							page.statusbar.showError("Error retrieving Item. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
						setTableInput(table,"u_preferredsuppno","");
						setTableInput(table,"u_preferredsuppname","");
					}
					break;
				case "u_unitprice":
					if (row==0) {
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
					} else {
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_unitprice",row),row);
						computeTotalGPSHIS();
					}
					break;
				case "u_quantity":
					if (row==0) {
						setTableInputAmount(table,"u_quantitypu",utils.divide(getTableInputNumeric(table,"u_quantity"),getTableInputNumeric(table,"u_numperuom")));
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantitypu") * getTableInputNumeric(table,"u_unitprice"));
					} else {
						setTableInputAmount(table,"u_quantitypu",utils.divide(getTableInputNumeric(table,"u_quantity",row),getTableInputNumeric(table,"u_numperuom",row)),row);
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantitypu",row) * getTableInputNumeric(table,"u_unitprice",row),row);
						computeTotalGPSHIS();
					}
					break;
				case "u_quantitypu":
					if (row==0) {
						setTableInputAmount(table,"u_quantity",utils.multiply(getTableInputNumeric(table,"u_quantitypu"),getTableInputNumeric(table,"u_numperuom")));
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantitypu") * getTableInputNumeric(table,"u_unitprice"));
					} else {
						setTableInputAmount(table,"u_quantity",utils.multiply(getTableInputNumeric(table,"u_quantitypu",row),getTableInputNumeric(table,"u_numperuom",row)),row);
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantitypu",row) * getTableInputNumeric(table,"u_unitprice",row),row);
						computeTotalGPSHIS();
					}
					break;
				case "u_preferredsuppno":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select suppname from suppliers where suppno='"+getTableInput(table,column)+"' and isvalid=1 and isonhold=0");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_preferredsuppname",result.childNodes.item(0).getAttribute("suppname"));
							} else {
								setTableInput(table,"u_preferredsuppname","");
								page.statusbar.showError("Invalid Supplier Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_preferredsuppname","");
							page.statusbar.showError("Error retrieving Supplier. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_preferredsuppname","");
					}
					break;
			}
			break;
		default:
			switch(column) {
				case "u_docdate":
					setInput("u_reqdate",getInput("u_docdate"));
					break;
				case "u_suppno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select suppno, suppname from suppliers where suppno='"+getInput(column)+"' and isvalid=1 and isonhold=0");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_suppno",result.childNodes.item(0).getAttribute("suppno"));
								setTableInput(table,"u_suppname",result.childNodes.item(0).getAttribute("suppname"));
							} else {
								setTableInput(table,"u_suppname","");
								page.statusbar.showError("Invalid Supplier Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_suppname","");
							page.statusbar.showError("Error retrieving Supplier. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_suppname","");
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
					disableInput("u_refno");
					disableInput("u_patientname");
					if (getInput("u_reftype")=="WI") {
						if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR") {
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
				case "u_pricelist":
					resetPricesGPSHIS();
					break;
				case"u_itemclass":
					clearTable("T1",true);
					computeTotalGPSHIS();
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
				case "u_isinvuom":
					if (row==0) {
						if (isTableInputChecked(table,column)) {
							setTableInputPrice(table,"u_unitprice",utils.divide(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_numperuom")));
							setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_unitprice"));
							
						} else {
							setTableInputPrice(table,"u_unitprice",utils.multiply(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_numperuom")));
							setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantitypu") * getTableInputNumeric(table,"u_unitprice"));
						}
					} else {
						if (isTableInputChecked(table,column,row)) {
							setTableInputPrice(table,"u_unitprice",utils.divide(getTableInputNumeric(table,"u_unitprice",row),getTableInputNumeric(table,"u_numperuom",row)),row);
							setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_unitprice",row),row);
							
						} else {
							setTableInputPrice(table,"u_unitprice",utils.multiply(getTableInputNumeric(table,"u_unitprice",row),getTableInputNumeric(table,"u_numperuom",row)),row);
							setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantitypu",row) * getTableInputNumeric(table,"u_unitprice",row),row);
						}
						computeTotalGPSHIS();
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_replenish":
					disableInput("u_itemclass");
					hideButton("btnRetrieve");
					if (isInputChecked("u_replenish")) {
						enableInput("u_itemclass");
						showButton("btnRetrieve");
					} else {
						setInput("u_itemclass","");
					}
					clearTable("T1",true);
					computeTotalGPSHIS();
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
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 where  u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 			
			/*
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			*/
			break;
		case "df_u_itemdescT1":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1")); 
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 where  u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code`In-Stock")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflcloseonselect=0"; 			

			/*
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			*/
			break;
		case "df_u_preferredsuppnoT1":
		case "df_u_suppno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno, suppname from suppliers where isvalid=1 and isonhold=0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Supplier No.`Supplier Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby="+utils.replaceSpecialChar("suppname"); 			
			break;
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged','Admitted')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
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
			if (items.length>0) {
				value = value.replace(/`/g,"','");
				if (Id=="df_u_itemcodeT1") {
					result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, ifnull(b.instockqty,0) as instockqty, a.u_preferredsuppno, c.suppname as u_preferredsuppname, ifnull(d.price,0) as price from u_hisitems a inner join  suppliers c on c.suppno=a.u_preferredsuppno left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where code in ('"+value+"')");			
				} else {
					result = page.executeFormattedQuery("select a.name,a.code, a.u_uom, a.u_uompu, a.u_numperuompu, ifnull(b.instockqty,0) as instockqty, a.u_preferredsuppno, c.suppname as u_preferredsuppname, ifnull(d.price,0) as price from u_hisitems a inner join  suppliers c on c.suppno=a.u_preferredsuppno left join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' and b.instockqty>0 left join itempricelists d on d.itemcode=a.code and d.pricelist='12' where a.name in ('"+utils.addslashes(value)+"') and u_active=1 ");			
				}
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
						data["u_uompu"] = result.childNodes.item(iii).getAttribute("u_uompu");
						data["u_numperuom"] = formatNumericQuantity(result.childNodes.item(iii).getAttribute("u_numperuompu"));
						if (isTableInputUnique("T1","u_itemcode",data["u_itemcode"],getTableInputCaption("T1","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
							if (getInput("u_suppno")!="") {
								data["u_preferredsuppno"] = getInput("u_suppno");
								data["u_preferredsuppname"] = getInput("u_suppname");
							} else {
								data["u_preferredsuppno"] = result.childNodes.item(iii).getAttribute("u_preferredsuppno");
								data["u_preferredsuppname"] = result.childNodes.item(iii).getAttribute("u_preferredsuppname");
							}
							if (data["u_preferredsuppno"]=="") {
								data["u_preferredsuppno"] = getPrivate("prsuppno");
								data["u_preferredsuppname"] = getPrivate("prsuppname");
							}
							data["u_instock"] = result.childNodes.item(iii).getAttribute("instockqty");
							data["u_unitprice"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("price"));
							//var result = ajaxxmlgetitemprice(data["u_itemcode"],"BRANCH:" + getInput("u_branch") + ";PRICELIST:{BS}");
							//data["u_unitprice"] = result.getAttribute("price");
							data["u_quantitypu"] = formatNumericAmount(utils.divide(1,result.childNodes.item(iii).getAttribute("u_numperuompu")));
							data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("price")*(utils.divide(1,result.childNodes.item(iii).getAttribute("u_numperuompu"))));
							insertTableRowFromArray("T1",data);
						}
					}
					//resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
					//computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
					computeTotalGPSHIS();
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
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
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
			computeTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
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
			} else if (elementFocused.substring(0,16)=="df_u_unitpriceT1") {
				focusTableInput(table,"u_unitprice",row);
			}
			break;
	}
	return params;
}

function resetPricesGPSHIS() {
	var rc =  getTableRowCount("T1"), totalamount=0;
	showAjaxProcess();
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getInput("u_pricelist")!="") {
				var result2 = ajaxxmlgetitemprice(getTableInput("T1","u_itemcode",i),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
				setTableInputPrice("T1","u_unitprice",formatNumeric(result2.getAttribute("price"),'',0),i);
				setTableInputPrice("T1","u_price",formatNumeric(result2.getAttribute("price"),'',0),i);
				setTableInputAmount("T1","u_linetotal",formatNumeric(result2.getAttribute("price"),'',0)*getTableInputNumeric("T1","u_quantity",i),i);
			} else {
				setTableInputPrice("T1","u_unitprice",0,i);
				setTableInputPrice("T1","u_price",0,i);
				setTableInputAmount("T1","u_linetotal",0,i);
			}
			totalamount += getTableInputNumeric("T1","u_linetotal",i);
		}
	}
	if (getTableInput("T1","u_itemcode")!="" && getInput("u_pricelist")!="") {
		var result2 = ajaxxmlgetitemprice(getTableInput("T1","u_itemcode"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
		setTableInputPrice("T1","u_unitprice",formatNumeric(result2.getAttribute("price"),'',0));
		setTableInputPrice("T1","u_price",formatNumeric(result2.getAttribute("price"),'',0));
		setTableInputAmount("T1","u_linetotal",formatNumeric(result2.getAttribute("price"),'',0)*getTableInputNumeric("T1","u_quantity"));
	} else {
		setTableInputPrice("T1","u_unitprice",0);
		setTableInputPrice("T1","u_price",0);
		setTableInputAmount("T1","u_linetotal",0);
	}
	setInputAmount("u_amount",totalamount);	
	hideAjaxProcess();
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

