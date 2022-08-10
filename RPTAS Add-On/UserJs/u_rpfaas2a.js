// page events
page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
page.events.add.submitreturn('onPageSubmitReturnGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSRPTAS');

// element events
//page.elements.events.add.focus('onElementFocusGPSRPTAS');
//page.elements.events.add.keydown('onElementKeyDownGPSRPTAS');
page.elements.events.add.validate('onElementValidateGPSRPTAS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSRPTAS');
page.elements.events.add.changing('onElementChangingGPSRPTAS');
page.elements.events.add.change('onElementChangeGPSRPTAS');
//page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
//page.tables.events.add.select('onTableSelectRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
	if (getVar("formSubmitAction")=="a") {
		setInput("u_arpno",window.opener.getInput("docno"));
		setInput("u_suffix",window.opener.getInput("suffix"));
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_arpno")) return false;
		if (isInputEmpty("u_class")) return false;
		if (isInputEmpty("u_actualuse")) return false;
		if (isInputNegative("u_assvalue")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSRPTAS(action,success,error) {
	try {
		window.opener.setKey("keys",window.opener.getInput("docno"));
		window.opener.formEdit();
                window.close();
	} catch(TheError) {
	}
	//if (success) window.close();
}

function onCFLGPSRPTAS(Id) {
	return true;
}

function onCFLGetParamsGPSRPTAS(Id,params) {
	return params;
}

function onTaskBarLoadGPSRPTAS() {
}

function onElementFocusGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSRPTAS(element,event,column,table,row) {
}

function onElementValidateGPSRPTAS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
                                case "u_age":
                                        if (getTableInput(table,column)!="") {
                                                    var result = page.executeFormattedQuery("select u_depreval from u_rpbldgdepre where u_structuretype='"+getInput("u_structuretype")+"' and u_subclass='"+getInput("u_subclass")+"' and "+getTableInput(table,column)+" between u_yrfr and u_yrto ");	
                                                    if (result.getAttribute("result")!= "-1") {
                                                            if (parseInt(result.getAttribute("result"))>0) {
                                                                    setTableInputAmount(table,"u_deprevalue",(result.childNodes.item(0).getAttribute("u_depreval")/100)*getTableInputNumeric(table,"u_adjmarketvalue"));
                                                            } else {
                                                                    setTableInputAmount(table,"u_deprevalue",0);
                                                            }
                                                        setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue")*getTableInputNumeric(table,"u_quantity")*(getTableInputNumeric(table,"u_completeperc")/100));
                                                        setTableInputAmount(table,"u_adjmarketvalue",getTableInputNumeric(table,"u_basevalue")+getTableInputNumeric(table,"u_adjvalue")-getTableInputNumeric(table,"u_deprevalue"));
                                                    } else {
                                                            setTableInputAmount(table,"u_deprevalue",0);
                                                            setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue")*getTableInputNumeric(table,"u_quantity")*(getTableInputNumeric(table,"u_completeperc")/100));
                                                            setTableInputAmount(table,"u_adjmarketvalue",getTableInputNumeric(table,"u_basevalue")+getTableInputNumeric(table,"u_adjvalue")-getTableInputNumeric(table,"u_deprevalue"));
                                                            page.statusbar.showError("Error retrieving building depreciation rate . Try Again, if problem persists, check the connection.");	
                                                            return false;
                                                    }
                                        } else {
                                                setTableInputAmount(table,"u_deprevalue",0);
                                                setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue")*getTableInputNumeric(table,"u_quantity")*(getTableInputNumeric(table,"u_completeperc")/100));
                                                setTableInputAmount(table,"u_adjmarketvalue",getTableInputNumeric(table,"u_basevalue")+getTableInputNumeric(table,"u_adjvalue")-getTableInputNumeric(table,"u_deprevalue"));
                                        }
                                break;
                                
				case "u_adjperc":
					setTableInputAmount(table,"u_adjvalue",getTableInputNumeric(table,"u_basevalue")*(getTableInputNumeric(table,"u_adjperc")/100));
					setTableInputAmount(table,"u_adjmarketvalue",getTableInputNumeric(table,"u_basevalue")+getTableInputNumeric(table,"u_adjvalue")-getTableInputNumeric(table,"u_deprevalue"));
					break;
				case "u_completeperc":
				case "u_sqm":
				case "u_unitvalue":
				case "u_quantity":
				case "u_deprevalue":
					setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue")*getTableInputNumeric(table,"u_quantity")*(getTableInputNumeric(table,"u_completeperc")/100));
					setTableInputAmount(table,"u_adjvalue",getTableInputNumeric(table,"u_basevalue")*(getTableInputNumeric(table,"u_adjperc")/100));
					setTableInputAmount(table,"u_adjmarketvalue",getTableInputNumeric(table,"u_basevalue")+getTableInputNumeric(table,"u_adjvalue")-getTableInputNumeric(table,"u_deprevalue"));
					break;
			}
			break;
		default:
			switch (column) {
                                case "u_age":
                                        if (getTableInput(table,column)!="") {
                                                    var result = page.executeFormattedQuery("select u_depreval from u_rpbldgdepre where u_structuretype='"+getInput("u_structuretype")+"' and u_subclass='"+getInput("u_subclass")+"' and "+getInput("u_age")+" between u_yrfr and u_yrto ");	
                                                    if (result.getAttribute("result")!= "-1") {
                                                            if (parseInt(result.getAttribute("result"))>0) {
                                                                    setInputPercent("u_floordeprevalue",(result.childNodes.item(0).getAttribute("u_depreval")/100)*getInputNumeric("u_floorbasevalue"));
                                                            } else {
                                                                    setInputPercent("u_floordeprevalue",0);
                                                            }
                                                            setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue"));
                                                            setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
                                                            computeBaseValueGPSRPTAS();
                                                    } else {
                                                            setInputPercent("u_floordeprevalue",0);
                                                            setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue"));
                                                            setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
                                                            computeBaseValueGPSRPTAS();
                                                            page.statusbar.showError("Error retrieving building depreciation rate . Try Again, if problem persists, check the connection.");	
                                                            return false;
                                                    }
                                            } else {
                                                    setInputPercent("u_floordeprevalue",0);
                                                    setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue"));
                                                    setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
                                                    computeBaseValueGPSRPTAS();
                                            }
                                      break;
				case "u_flooradjperc":
					setInputAmount("u_flooradjvalue",getInputNumeric("u_floorbasevalue")*(getInputNumeric("u_flooradjperc")/100));
					setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
					computeBaseValueGPSRPTAS();
					break;
				case "u_completeperc":
				case "u_sqm":
				case "u_unitvalue":
				case "u_floordeprevalue":
					setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue")*(getInputNumeric("u_completeperc")/100));
					setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
					computeBaseValueGPSRPTAS();
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSRPTAS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSRPTAS(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_actualuse":
					break;
			}
			break;
	}
	return true;
}

function onElementChangeGPSRPTAS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select u_unitvalue,u_linkdetails,u_adjperc,u_completeperc from u_rpbldgextraitems where code='"+getTableInput(table,"u_itemdesc")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
                                                            if(result.childNodes.item(0).getAttribute("u_linkdetails")==1){
                                                                setTableInputPercent(table,"u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue")*getInputNumeric("u_unitvalue"));
                                                            }else{
                                                                setTableInputPercent(table,"u_completeperc",result.childNodes.item(0).getAttribute("u_completeperc"));
                                                                setTableInputPercent(table,"u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
                                                                setTableInputPercent(table,"u_adjperc",result.childNodes.item(0).getAttribute("u_adjperc"));
                                                            }
								setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_quantity")*getTableInputNumeric(table,"u_unitvalue"));
							} else {
								setTableInputPercent(table,"u_unitvalue",0);
								setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_quantity")*getTableInputNumeric(table,"u_unitvalue"));
							}
						} else {
							setTableInputPercent(table,"u_unitvalue",0);
							setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_quantity")*getTableInputNumeric(table,"u_unitvalue"));
							page.statusbar.showError("Error retrieving building extra item record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInputPercent(table,"u_unitvalue",0);
						setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_quantity")*getTableInputNumeric(table,"u_unitvalue"));
					}						
					break;
			}
			break;
		default:
			switch (column) {
				
				case "u_gryear":
				case "u_kind":
				case "u_structuretype":
				case "u_subclass":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select u_unitvalue from u_rpbldgstructclassrates where u_structuretype='"+getInput("u_structuretype")+"' and u_subclass='"+getInput("u_subclass")+"' and u_kind='"+getInput("u_kind")+"' and u_revisionyear = '"+getInput("u_gryear")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputPercent("u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
							} else {
								setInputPercent("u_unitvalue",0);
							}
							setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue"));
							setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
							computeBaseValueGPSRPTAS();
						} else {
							setInputPercent("u_unitvalue",0);
							setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue"));
							setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
							computeBaseValueGPSRPTAS();
							page.statusbar.showError("Error retrieving building structure/ class rate record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputPercent("u_unitvalue",0);
						setInputAmount("u_floorbasevalue",getInputNumeric("u_sqm")*getInputNumeric("u_unitvalue"));
						setInputAmount("u_flooradjmarketvalue",getInputNumeric("u_floorbasevalue")+getInputNumeric("u_flooradjvalue")-getInputNumeric("u_floordeprevalue"));
						computeBaseValueGPSRPTAS();
					}						
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(element,params) {
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_sqm")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS(); break;
	}		
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_sqm")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS(); break;
	}		
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS(); break;
	}		
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

function computeBaseValueGPSRPTAS() {
	var rc = getTableRowCount("T1"),basevalue=0,deprevalue=0,adjvalue=0,adjmarketvalue=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			basevalue+= getTableInputNumeric("T1","u_basevalue",i);
			deprevalue+= getTableInputNumeric("T1","u_deprevalue",i);
			adjvalue+= getTableInputNumeric("T1","u_adjvalue",i);
			adjmarketvalue+= getTableInputNumeric("T1","u_adjmarketvalue",i);
		}
	}	
	setInputAmount("u_basevalue",basevalue+getInputNumeric("u_floorbasevalue"));
	setInputAmount("u_deprevalue",deprevalue+getInputNumeric("u_floordeprevalue"));
	setInputAmount("u_adjvalue",adjvalue+getInputNumeric("u_flooradjvalue"));
	setInputAmount("u_adjmarketvalue",adjmarketvalue+getInputNumeric("u_flooradjmarketvalue"));
}

