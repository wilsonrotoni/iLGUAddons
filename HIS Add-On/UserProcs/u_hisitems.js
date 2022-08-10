// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

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
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	setTypeAttributesGPSHIS();
	setSalesPricingGPSHIS();
	focusInput("name");
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("code")) return false;
		if (isInputEmpty("name")) return false;
		if (isInputEmpty("u_type")) return false;
		if (isInputEmpty("u_group")) return false;
		if (getInput("u_type")=="MEDSUP") {
			if (isInputEmpty("u_uom")) return false;
			if (isInputEmpty("u_uompu")) return false;
		}
		if (isInputEmpty("u_numperuompu")) return false;
		if (getInputNumeric("u_numperuompu")>1) {
			if (getInput("u_uom")==getInput("u_uompu")) {
				page.statusbar.showError("Unit and Purchasing Unit cannot be the same if Unit/Qty is more than 1.");
				return false;
			}
		}
		if (isInputChecked("u_ispackage")) {
			if (getInput("u_type")!="MISC") {
				page.statusbar.showError("Package item must be of type Miscellaneous.");
				return false;
			}
		}

		if (isInputChecked("u_isfixedasset")) {
			if (isInputEmpty("u_faclass")) return false;
		}

		if (isTable("T1")) {
			if (getTableInput("T1","u_inscode")!="") {
				page.statusbar.showError("An insurance benefit item is being added/edited.");
				return false;
			}
		}
		if (isTable("T2")) {
			if (getTableInput("T2","u_section")!="") {
				page.statusbar.showError("A section item is being added/edited.");
				return false;
			}
		}
		if (isTable("T3")) {
			if (isInputChecked("u_ispackage")) {
				if (getTableRowCount("T3",true)==0)	{
					page.statusbar.showError("At least 1 package item must be entered.");
					selectTab("tab1",1);
					return false;
				}
			}
			if (getTableInput("T3","u_itemcode")!="") {
				page.statusbar.showError("A package item is being added/edited.");
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
		case "T3":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") {	
							result = page.executeFormattedQuery("select code,name,u_uom,u_department from u_hisitems where code='"+getTableInput(table,column)+"' and u_ispackage=0 and u_active=1");	 
						} else {
							result = page.executeFormattedQuery("select code,name,u_uom,u_department from u_hisitems where name='"+getTableInput(table,column)+"' and u_ispackage=0 and u_active=1");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInput(table,"u_department",result.childNodes.item(0).getAttribute("u_department"));
							} else {
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_itemdesc","");
								setTableInput(table,"u_uom","");
								setTableInput(table,"u_department","");
								page.statusbar.showError("Invalid Item.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setTableInput(table,"u_uom","");
							setTableInput(table,"u_department","");
							page.statusbar.showError("Error retrieving Item. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
						setTableInput(table,"u_uom","");
						setTableInput(table,"u_department","");
					}
					break;
			}
			break;
		case "T110":
			switch(column) {
				case "price":
					if (getTableInput(table,"scdiscpricelist",row)!="") {
						setInputAmount("u_scdiscamount",utils.multiply(getTableInputNumeric(table,"price",row),getTableInputNumeric(table,"scdiscperc",row)));
					}
					if (getTableInput(table,"pricelist",row)==getPrivate("purchasingpricelist") && (getInput("u_salespricing")=="1" || getInput("u_salespricing")=="2")) {
						setSalesPricingGPSHIS(null,true);
					}
	
					break;
			}
			break;
		default:
			switch(column) {
				case "u_preferredsuppno":
					if (getInput("u_preferredsuppno")!="") {
						result = page.executeFormattedQuery("select suppno,suppname from suppliers where suppno='"+getInput("u_preferredsuppno")+"' and isvalid=1 and isonhold=0");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_preferredsuppno",result.childNodes.item(0).getAttribute("suppno"));
								setCaption("u_preferredsuppname",result.childNodes.item(0).getAttribute("suppname"));
							} else {
								setInput("u_preferredsuppno","");
								setCaption("u_preferredsuppname","");
								page.statusbar.showError("Invalid Supplier No.");	
								return false;
							}
						} else {
								setInput("u_preferredsuppno","");
								setCaption("u_preferredsuppname","");
							page.statusbar.showError("Error retrieving Supplier No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_preferredsuppno","");
						setCaption("u_preferredsuppname","");
					}
					break;
				case "u_brandname":
					if (getInput("u_brandname")!="") {
						result = page.executeFormattedQuery("select code from u_hisbrands where code='"+getInput("u_brandname")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_brandname",result.childNodes.item(0).getAttribute("code"));
							} else {
								page.statusbar.showError("Invalid Brand Name.");	
								return false;
							}
						} else {
							page.statusbar.showError("Error retrieving Brand Name. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
					break;
				case "u_genericname":
					if (getInput("u_genericname")!="") {
						result = page.executeFormattedQuery("select code from u_hisgenerics where code='"+getInput("u_genericname")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_genericname",result.childNodes.item(0).getAttribute("code"));
							} else {
								page.statusbar.showError("Invalid Generic Name.");	
								return false;
							}
						} else {
							page.statusbar.showError("Error retrieving Generic Name. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
					break;
				case "u_statperc":
					if (getInputNumeric("u_statperc")!=0) {
						var rc = getTableRowCount("T110");
						setInput("u_statamount",0);
						setInputAmount("u_scstatdiscamount",0);
						for (i = 1; i <= rc; i++) {
							if (isTableRowDeleted("T110",i)==false) {
								if (getTableInput("T110","scdiscpricelist",i)!="") {
									setInputAmount("u_scstatdiscamount",utils.multiply(getTableInputNumeric("T110","price",i)+(getTableInputNumeric("T110","price",i)*(getInputNumeric("u_statperc")/100)),getTableInputNumeric("T110","scdiscperc",i)));
								}
							}
						}
					} else if (getInputNumeric("u_statamount")==0) {
						setInputAmount("u_scstatdiscamount",0);
					}
					break;
				case "u_statamount":
					if (getInputNumeric("u_statamount")!=0) {
						var rc = getTableRowCount("T110");
						setInput("u_statperc",0);
						setInputAmount("u_scstatdiscamount",0);
						for (i = 1; i <= rc; i++) {
							if (isTableRowDeleted("T110",i)==false) {
								if (getTableInput("T110","scdiscpricelist",i)!="") {
									setInputAmount("u_scstatdiscamount",utils.multiply(getTableInputNumeric("T110","price",i)+getInputNumeric("u_statamount"),getTableInputNumeric("T110","scdiscperc",i)));
								}
							}
						}
					} else if (getInputNumeric("u_statperc")==0) {
						setInputAmount("u_scstatdiscamount",0);
					}
					break;
				case "u_salesmarkup":
					setSalesPricingGPSHIS(false,true);
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
		default:
			switch (column) {
				case "u_series":
					//if (getInput("u_series")!=-1) {
					//	setInput("u_type",getInputSelectedText("u_series"));
					//}
					setDocNo(true,"u_series","code");
					break;
				case "u_type":
					if (getInput("u_type")!="EXAM") {
						setInput("u_template","");
					}
					if (getInput("u_type")!="MEDSUP") {
						setInput("u_manageby","0");
						setInput("u_faclass","");
						setInput("u_isstock","0");
						setInput("u_isfixedasset","0");
					}
					setTypeAttributesGPSHIS();
					break;
				case "u_salespricing":
					setSalesPricingGPSHIS(true,true);
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
				case "u_isstock":
					if (!isInputChecked("u_isstock")) {
						setInput("u_manageby","0");
					}
					setTypeAttributesGPSHIS();
					break;
				case "u_isfixedasset":
					if (!isInputChecked("u_isfixedasset")) {
						setInput("u_faclass","");
					} else {
						setInput("u_isstock","0");
						setInput("u_manageby","0");
					}
					setTypeAttributesGPSHIS();
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_preferredsuppno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno,suppname from suppliers where isvalid=1 and isonhold=0")); 
			break;
		case "df_u_itemcodeT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisitems where u_ispackage=0 and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Item Description")); 			
			/*params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisbrands"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=600"; 	*/
			break;
		case "df_u_itemdescT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisitems where u_ispackage=0 and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item Code")); 			
			/*params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisbrands"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=600"; 	*/
			break;
		case "df_u_brandname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisbrands")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisbrands"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=600"; 	
			break;
		case "df_u_genericname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisgenerics")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisgenerics"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=600"; 	
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInputNumeric(table,"u_amount")<=0 && getTableInputNumeric(table,"u_perc")<=0) {
				page.statusbar.showError("Amount or % must be entered.");
				focusTableInput(table,"u_amount");
				return false;
			}
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_section")) return false;
			if (isTableInputNegative(table,"u_minqty")) return false;
			if (isTableInputNegative(table,"u_maxqty")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_qtyperpack")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInputNumeric(table,"u_amount")<=0 && getTableInputNumeric(table,"u_perc")<=0) {
				page.statusbar.showError("Amount or % must be entered.");
				focusTableInput(table,"u_amount");
				return false;
			}
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_section")) return false;
			if (isTableInputNegative(table,"u_minqty")) return false;
			if (isTableInputNegative(table,"u_maxqty")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_qtyperpack")) return false;
			break;
	}
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
	switch (table) {
		case "T110":
			params["focus"] = false;
			focusTableInput(table,"price",row);
			break;
	}
	return params;
}

function setTypeAttributesGPSHIS() {
	if (getTableInputNumeric("T10","instockqty")>0) {
		disableInput("u_type");
	}
	if (getTableInputNumeric("T10","instockqty")>0 || getInput("u_type")!="MEDSUP") {
		disableInput("u_isstock");
		disableInput("u_manageby");
		disableInput("u_isfixedasset");
		disableInput("u_faclass");
	} else {
		enableInput("u_isstock");
		enableInput("u_manageby");
		enableInput("u_isfixedasset");
		enableInput("u_faclass");
		if (!isInputChecked("u_isstock")) disableInput("u_manageby");
		if (!isInputChecked("u_isfixedasset")) {
			disableInput("u_faclass");
		} else {
			disableInput("u_isstock");
			disableInput("u_manageby");
		}
	}
	
	if (getInput("u_type")=="EXAM") {
		enableInput("u_template");
	} else {
		disableInput("u_template");
	}
}

function setSalesPricingGPSHIS(setattrib,setvalues) {
	var purchaseprice=0;
	if (setattrib==null) setattrib = true;
	if (setvalues==null) setvalues = false;
	
	rc = getTableRowCount("T110");
	switch (getInput("u_salespricing")) {
		case "0":
			if (setattrib) disableInput("u_salesmarkup");
			if (setvalues) setInputPercent("u_salesmarkup",0);
			break;
		case "1":
			if (setattrib) enableInput("u_salesmarkup");
			break;
		case "2":
		case "-1":
			if (setattrib) disableInput("u_salesmarkup");
			if (setvalues) setInputPercent("u_salesmarkup",0);
			break;
	}
	
	if (setvalues)  {
		if (getInput("u_salespricing")!="0") {
			for (i = 1; i <= rc; i++) {
				if (isTableRowDeleted("T110",i)==false) {
					if (getTableInput("T110","pricelist",i)==getPrivate("purchasingpricelist")) {
						purchaseprice=getTableInputNumeric("T110","price",i);
						break;
					}
				}
			}
		}
	}
	
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T110",i)==false) {
			if (getTableInput("T110","bptype",i)=="C") {
				switch (getInput("u_salespricing")) {
					case "-1":
						if (setattrib) disableTableInput("T110","price",i);
						if (setvalues) setTableInputPrice("T110","price",0,i);
						break;
					case "0":
						if (setattrib) enableTableInput("T110","price",i);
						if (setvalues) setTableInputPrice("T110","price",0,i);
						break;
					case "1":
						if (setattrib) disableTableInput("T110","price",i);
						if (setvalues) setTableInputPrice("T110","price",purchaseprice*(1+((getInputNumeric("u_salesmarkup")/100))),i);
						break;
					case "2":
						if (setattrib) disableTableInput("T110","price",i);
						if (setvalues) setTableInputPrice("T110","price",purchaseprice*(1+((parseFloat(getPrivate("markup"))/100))),i);
						break;
				}
				if (setvalues && getTableInput("T110","scdiscpricelist",i)!="") {
					setInputAmount("u_scdiscamount",utils.multiply(getTableInputNumeric("T110","price",i),getTableInputNumeric("T110","scdiscperc",i)));
				}
				
			}
		}
	}
	
}