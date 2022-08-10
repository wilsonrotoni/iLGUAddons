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
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
page.tables.events.add.reset('onTableResetRowGPSRPTAS');
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
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
    var rc = getTableRowCount("T2");
	if (action=="a" || action=="sc") {
            if (isInputEmpty("u_arpno")) return false;
            if (isInputEmpty("u_class")) return false;
            if (isInputEmpty("u_actualuse")) return false;
            if (isInputNegative("u_assvalue")) return false;
               
//                if(getInput("u_class")!="A" && rc!=0){
//                    if(confirm("There's an existing data in value adjustment. you want to delete?")){
//                        clearTable("T2",true);
//                        computeAdjValueGPSRPTAS();
//                        computeMarketValueGPSRPTAS();
//                    }else{
//                        return false;
//                    }
//                }
                
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
		case "T5":
			switch (column) {
				case "u_kind":
				case "u_description":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select b.u_unitvalue from u_rplandimprovements a left join u_rplandimprovementitems b on a.code = b.code where a.code='"+getTableInput("T5","u_kind")+"' and a.u_gryear = '"+getInput("u_gryear")+"' and b.u_description = '"+getTableInput("T5","u_description")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInputPercent(table,"u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
								setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
							} else {
								setTableInputPercent(table,"u_unitvalue",0);
							}
						} else {
							setTableInputPercent(table,"u_unitvalue",0);
							page.statusbar.showError("Error retrieving ipr record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInputPercent(table,"u_unitvalue",0);
						setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					}						
					break;
				case "u_sqm":
				case "u_unitvalue":
					setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					break;
			}
                break;
		case "T1":
			switch (column) {
				case "u_subclass":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select b.u_subclass, b.u_unitvalue, b.u_class  from u_rplands a left join u_rplandsubclasses b on a.code = b.code where a.code='"+getInput("u_class")+"' and a.u_gryear = '"+getInput("u_gryear")+"' and b.u_subclass = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_class",result.childNodes.item(0).getAttribute("u_class"));
								setTableInputPercent(table,"u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
								setTableInputPercent(table,"u_unitvaluehas",result.childNodes.item(0).getAttribute("u_unitvalue") * 10000);
								setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
							} else {
								setTableInputPercent(table,"u_unitvalue",0);
							}
						} else {
							setTableInputPercent(table,"u_unitvalue",0);
							page.statusbar.showError("Error retrieving ipr record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInputPercent(table,"u_unitvalue",0);
						setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					}						
					break;
				case "u_sqm":
				case "u_unitvalue":
					setTableInput(table,"u_sqmhas",getTableInputNumeric(table,"u_sqm")/10000);
					setTableInputAmount(table,"u_unitvaluehas",getTableInputNumeric(table,"u_unitvalue")*10000);
					setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					break;
			}
                break;
		case "T4":
			switch (column) {
				
				case "u_sqm":
				case "u_unitvalue":
				case "u_adjperc":
//					setTableInput(table,"u_sqmhas",getTableInputNumeric(table,"u_sqm")/10000);
					setTableInputAmount(table,"u_adjunitvalue",(getTableInputNumeric(table,"u_adjperc") / 100 ) * getTableInputNumeric(table,"u_unitvalue"));
					setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_adjunitvalue"));
					break;
			}
                break;
		case "T2":
			switch (column) {
				case "u_adjfactor":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select b.u_adjfactor, b.u_adjperc from u_rplandadjfactors a left join u_rplandadjfactoritems b on a.code = b.code where a.u_gryear = '"+getInput("u_gryear")+"' and b.code = '"+getTableInput(table,"u_adjtype")+"' and u_adjfactor='"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_adjfactor",result.childNodes.item(0).getAttribute("u_adjfactor"));
								setTableInputPercent(table,"u_adjperc",result.childNodes.item(0).getAttribute("u_adjperc"));
								setTableInputAmount(table,"u_adjvalue",getInputNumeric("u_basevalue")*(getTableInputNumeric(table,"u_adjperc")/100));
							} else {
								setTableInput(table,"u_adjfactor","");
								setTableInputPercent(table,"u_adjperc",0);
								
							}
						} else {
							setTableInput(table,"u_adjfactor","");
							setTableInputPercent(table,"u_adjperc",0);
							page.statusbar.showError("Error retrieving ipr record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_adjfactor","");
						setTableInputPercent(table,"u_adjperc",0);
						setTableInputAmount(table,"u_adjvalue",getInputNumeric("u_basevalue")*(getTableInputNumeric(table,"u_adjperc")/100));
					}						
					break;
				case "u_adjperc":
					setTableInputAmount(table,"u_adjvalue",getInputNumeric("u_basevalue")*(getTableInputNumeric(table,"u_adjperc")/100));
					break;
			}
                break;
		case "T3":
			switch (column) {
				case "u_productive":
                                    setTableInputAmount(table,"u_totalcount",getTableInputNumeric(table,"u_productive"));
                                    setTableInputAmount(table,"u_marketvalue",getTableInputNumeric(table,"u_totalcount") * getTableInputNumeric(table,"u_unitvalue"));
					break;
				case "u_unitvalue":
					setTableInputAmount(table,"u_marketvalue",getTableInputNumeric(table,"u_totalcount") * getTableInputNumeric(table,"u_unitvalue"));
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
				
			}
			break;
	}
	return true;
}

function onElementChangeGPSRPTAS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_class":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select b.u_subclass, b.u_unitvalue, b.u_class  from u_rplands a left join u_rplandsubclasses b on a.code = b.code where a.code='"+getInput("u_class")+"' and a.u_gryear = '"+getInput("u_gryear")+"' and b.u_class='"+getTableInput(table,"u_class")+"' and b.u_subclass = '"+getTableInput(table,"u_subclass")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInputPercent("T4","u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
								setTableInputPercent(table,"u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
								setTableInputPercent(table,"u_unitvaluehas",result.childNodes.item(0).getAttribute("u_unitvalue") * 10000);
								setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
							} else {
								setTableInputPercent(table,"u_unitvalue",0);
							}
						} else {
							setTableInputPercent("T4","u_unitvalue",0);
							setTableInputPercent(table,"u_unitvalue",0);
							setTableInputPercent(table,"u_unitvaluehas",0);
							page.statusbar.showError("Error retrieving record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
                                                setTableInputPercent("T4","u_unitvalue",0);
						setTableInputPercent(table,"u_unitvalue",0);
						setTableInputPercent(table,"u_unitvaluehas",0);
						setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					}						
					break;
				case "u_sqm":
				case "u_unitvalue":
                                        setTableInputNumeric(table,"u_sqmhas",getTableInputNumeric(table,"u_sqm")/10000);
                                        setTableInputAmount(table,"u_unitvaluehas",getTableInputNumeric(table,"u_unitvalue")*10000);
					setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					break;
			}
			break;
		case "T4":
			switch (column) {
				case "u_strip":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select u_adjperc from u_landstripping  where code='"+getTableInput(table,column)+"' ");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInputPercent(table,"u_adjperc",result.childNodes.item(0).getAttribute("u_adjperc"));
								setTableInputAmount(table,"u_adjunitvalue",(getTableInputNumeric(table,"u_adjperc")/100)*getTableInputNumeric(table,"u_unitvalue"));
								setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_adjunitvalue"));
							} else {
								setTableInputPercent(table,"u_adjperc",0);
								setTableInputPercent(table,"u_adjunitvalue",0);
								setTableInputPercent(table,"u_basevalue",0);
							}
						} else {
							setTableInputPercent(table,"u_adjperc",0);
							setTableInputPercent(table,"u_adjunitvalue",0);
							setTableInputPercent(table,"u_basevalue",0);
							page.statusbar.showError("Error retrieving record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInputPercent(table,"u_adjperc",0);
						setTableInputPercent(table,"u_adjunitvalue",0);
						setTableInputPercent(table,"u_basevalue",0);
						setTableInputAmount(table,"u_basevalue",getTableInputNumeric(table,"u_sqm")*getTableInputNumeric(table,"u_unitvalue"));
					}						
					break;
			}
			break;
		case "T3":
			switch (column) {
				case "u_planttype":
				case "u_class":
					if (getTableInput(table,column)!="") {
						var result = page.executeFormattedQuery("select u_unitvalue  from u_rplandtreesvalues  where u_type='"+getTableInput(table,"u_planttype")+"' and u_class='"+getTableInput(table,"u_class")+"' and code = '"+getInput("u_gryear")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInputPercent(table,"u_unitvalue",result.childNodes.item(0).getAttribute("u_unitvalue"));
								setTableInputAmount(table,"u_marketvalue",getTableInputNumeric(table,"u_totalcount")*getTableInputNumeric(table,"u_unitvalue"));
							} else {
								setTableInputPercent(table,"u_unitvalue",0);
							}
						} else {
							setTableInputPercent(table,"u_unitvalue",0);
							page.statusbar.showError("Error retrieving ipr record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInputPercent(table,"u_unitvalue",0);
						setTableInputAmount(table,"u_marketvalue",getTableInputNumeric(table,"u_totalcount")*getTableInputNumeric(table,"u_unitvalue"));
					}						
					break;
				case "u_totalcount":
				case "u_unitvalue":
					setTableInputAmount(table,"u_marketvalue",getTableInputNumeric(table,"u_totalcount")*getTableInputNumeric(table,"u_unitvalue"));
					break;
			}
			break;
                default : 
                        switch (column) {
                            case "u_adjvalue":
                                computeMarketValueGPSRPTAS();
                                break;
                            
                            case "u_gryear":
                                u_ajaxloadu_faas1aclass("df_u_class",element.value,'',":");
                            case "u_class":
                            case "u_actualuse":
                                    if (getInput("u_actualuse")!="") {
                                            var result = page.executeFormattedQuery("select a.code, a.name, b.u_assesslevel from u_rpactuses a left join u_rpactusesasslvl b on a.code = b.code  where a.code = '"+getInput("u_actualuse")+"' and b.u_gryear = '"+getInput("u_gryear")+"' ");	 
                                            if (result.getAttribute("result")!= "-1") {
                                                    if (parseInt(result.getAttribute("result"))>0) {
                                                            setInputPercent("u_asslvl",result.childNodes.item(0).getAttribute("u_assesslevel"));
                                                            computeMarketValueGPSRPTAS();
                                                    } else {
                                                            setInputPercent("u_asslvl",0);
                                                          //  page.statusbar.showError("Invalid Terminal");	
                                                            computeMarketValueGPSRPTAS();
                                                            //return false;
                                                    }
                                            } else {
                                                    setInputPercent("u_asslvl",0);
                                                    page.statusbar.showError("Error retrieving Terminal. Try Again, if problem persists, check the connection.");	
                                                    computeMarketValueGPSRPTAS();
                                                    return false;
                                            }						
                                    } else {
                                            setInputPercent("u_asslvl",0);
                                            computeMarketValueGPSRPTAS();
                                    }				
                                    break;
                        }
	}
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
            switch (table) {
                default:
			switch(column) {
                            case "isasslvlclass":
                                    if(isInputChecked("isasslvlclass")){
                                                if (getInput("u_class")!="") {
                                                    var result = page.executeFormattedQuery("select a.u_assesslevel from u_rplands a  where a.code = '"+getInput("u_class")+"'");	 
                                                    if (result.getAttribute("result")!= "-1") {
                                                            if (parseInt(result.getAttribute("result"))>0) {
                                                                    setInputPercent("u_asslvl",result.childNodes.item(0).getAttribute("u_assesslevel"));
                                                                    computeMarketValueGPSRPTAS();
                                                            } else {
                                                                    setInputPercent("u_asslvl",0);
                                                                  //  page.statusbar.showError("Invalid Terminal");	
                                                                    computeMarketValueGPSRPTAS();
                                                                    //return false;
                                                            }
                                                    } else {
                                                            setInputPercent("u_asslvl",0);
                                                            page.statusbar.showError("Error retrieving Terminal. Try Again, if problem persists, check the connection.");	
                                                            computeMarketValueGPSRPTAS();
                                                            return false;
                                                    }						
                                                } else {
                                                        setInputPercent("u_asslvl",0);
                                                        computeMarketValueGPSRPTAS();
                                                }
                                    }else{
                                            if (getInput("u_actualuse")!="") {
                                                 var result = page.executeFormattedQuery("select a.code, a.name, b.u_assesslevel from u_rpactuses a left join u_rpactusesasslvl b on a.code = b.code  where a.code = '"+getInput("u_actualuse")+"' and b.u_gryear = '"+getInput("u_gryear")+"' ");	 
                                                     if (result.getAttribute("result")!= "-1") {
                                                             if (parseInt(result.getAttribute("result"))>0) {
                                                                     setInputPercent("u_asslvl",result.childNodes.item(0).getAttribute("u_assesslevel"));
                                                                     computeMarketValueGPSRPTAS();
                                                             } else {
                                                                     setInputPercent("u_asslvl",0);
                                                                   //  page.statusbar.showError("Invalid Terminal");	
                                                                     computeMarketValueGPSRPTAS();
                                                                     //return false;
                                                             }
                                                     } else {
                                                             setInputPercent("u_asslvl",0);
                                                             page.statusbar.showError("Error retrieving Terminal. Try Again, if problem persists, check the connection.");	
                                                             computeMarketValueGPSRPTAS();
                                                             return false;
                                                     }						
                                             } else {
                                                     setInputPercent("u_asslvl",0);
                                                     computeMarketValueGPSRPTAS();
                                             }
                                    }
                                break;
                        }
            }
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
	switch (Id) {
		case "df_u_subclassT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_subclass,u_class  from u_rplandsubclasses where code='"+getInput("u_class")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Sub-Class`Class")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_adjfactorT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select b.u_adjfactor, b.u_adjperc  from u_rplandadjfactors a inner join u_rplandadjfactoritems b on a.code = b.code where a.code = '"+getTableInput("T2","u_adjtype")+"' and a.u_gryear = '"+getInput("u_gryear")+"' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Adjustment Factor`% Adjustment")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`percent")); 			
			break;
		case "df_u_descriptionT5":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select b.u_description,b.u_unitvalue from u_rplandimprovements a inner join u_rplandimprovementitems b on a.code = b.code where  b.code='"+getTableInput("T5","u_kind")+"' and a.u_gryear = '"+getInput("u_gryear")+"' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Unit Value")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`amount")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSRPTAS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_subclass");
			break;
		case "T2":
			focusTableInput(table,"u_adjtype");
			break;
		case "T3":
			focusTableInput(table,"u_planttype");
			break;
	}
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	switch (table) {
		case "T1":
			//if (isTableInputEmpty(table,"u_class")) return false;
			if (isTableInputEmpty(table,"u_subclass")) return false;
			if (isTableInputNegative(table,"u_sqm")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
                case "T3":
                        if (isTableInputEmpty(table,"u_planttype")) return false;
                        if (isTableInputEmpty(table,"u_class")) return false;
                        if (isTableInputNegative(table,"u_totalcount")) return false;
                        if (isTableInputNegative(table,"u_unitvalue")) return false;
                break;
		case "T2":
			if (isTableInputEmpty(table,"u_adjtype")) return false;
			if (isTableInputEmpty(table,"u_adjfactor")) return false;
//			if (isTableInputZero(table,"u_adjperc")) return false;
			break;
                case "T4":
			if (isTableInputEmpty(table,"u_strip")) return false;
			if (isTableInputNegative(table,"u_basevalue")) return false;
			break;
                case "T5":
                        if (isTableInputEmpty(table,"u_description")) return false;
                        if (isTableInputNegative(table,"u_sqm")) return false;
                        if (isTableInputNegative(table,"u_unitvalue")) return false;
                        break;
	}
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T2": computeAdjValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T3": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T4": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T5": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
	}		
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1":
			//if (isTableInputEmpty(table,"u_class")) return false;
			if (isTableInputEmpty(table,"u_subclass")) return false;
			if (isTableInputNegative(table,"u_sqm")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_planttype")) return false;
			if (isTableInputEmpty(table,"u_class")) return false;
			if (isTableInputNegative(table,"u_totalcount")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
		case "T4":
			if (isTableInputEmpty(table,"u_strip")) return false;
			if (isTableInputNegative(table,"u_basevalue")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_adjtype")) return false;
			if (isTableInputEmpty(table,"u_adjfactor")) return false;
//			if (isTableInputZero(table,"u_adjperc")) return false;
			break;
                case "T5":
                        if (isTableInputEmpty(table,"u_description")) return false;
                        if (isTableInputNegative(table,"u_sqm")) return false;
                        if (isTableInputNegative(table,"u_unitvalue")) return false;
                        break;
	}
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T2": computeAdjValueGPSRPTAS();computeMarketValueGPSRPTAS();break;
		case "T3": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS();break;
		case "T4": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS();break;
		case "T5": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS();break;
	}		
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T2": computeAdjValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T3": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T4": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
		case "T5": computeBaseValueGPSRPTAS();computeMarketValueGPSRPTAS(); break;
	}		
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

function computeBaseValueGPSRPTAS() {
	var rc = getTableRowCount("T1"),basevalue=0,sqm=0;
	var rc3 = getTableRowCount("T3");
	var rc4 = getTableRowCount("T4");
	var rc5 = getTableRowCount("T5");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			basevalue+= getTableInputNumeric("T1","u_basevalue",i);
			sqm+= getTableInputNumeric("T1","u_sqm",i);
		}
	}	
	for (i = 1; i <= rc3; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			basevalue+= getTableInputNumeric("T3","u_marketvalue",i);
		}
	}
        for (i = 1; i <= rc4; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			basevalue+= getTableInputNumeric("T4","u_basevalue",i);
			sqm+= getTableInputNumeric("T4","u_sqm",i);
		}
	}
        for (i = 1; i <= rc5; i++) {
		if (isTableRowDeleted("T5",i)==false) {
			basevalue+= getTableInputNumeric("T5","u_basevalue",i);
			sqm+= getTableInputNumeric("T5","u_sqm",i);
		}
	}
	setInputAmount("u_basevalue",basevalue);
	setInputQuantity("u_sqm",sqm);
	computeMarketValueGPSRPTAS();
}

function computeAdjValueGPSRPTAS() {
	var rc = getTableRowCount("T2"),adjvalue=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			adjvalue+= getTableInputNumeric("T2","u_adjvalue",i);
		}
	}	
	setInputAmount("u_adjvalue",adjvalue);
	computeMarketValueGPSRPTAS();
}

function computeMarketValueGPSRPTAS() {
    var num = getInputNumeric("u_marketvalue")*(getInputNumeric("u_asslvl")/100);
    var assval = Math.round(num / 10) * 10;
	setInputAmount("u_marketvalue",getInputNumeric("u_basevalue")+getInputNumeric("u_adjvalue"));
	setInputAmount("u_assvalue",assval);
	
}	


