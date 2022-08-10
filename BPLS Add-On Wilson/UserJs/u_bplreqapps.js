// page events
//page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
page.events.add.submit('onPageSubmitGPSBPLS');
//page.events.add.cfl('onCFLGPSBPLS');
//page.events.add.cflgetparams('onCFLGetParamsGPSBPLS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBPLS');

// element events
//page.elements.events.add.focus('onElementFocusGPSBPLS');
//page.elements.events.add.keydown('onElementKeyDownGPSBPLS');
page.elements.events.add.validate('onElementValidateGPSBPLS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSBPLS');
//page.elements.events.add.changing('onElementChangingGPSBPLS');
page.elements.events.add.change('onElementChangeGPSBPLS');
//page.elements.events.add.click('onElementClickGPSBPLS');
//page.elements.events.add.cfl('onElementCFLGPSBPLS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
page.tables.events.add.select('onTableSelectRowGPSBPLS');

var tabberOptions = {
  'manualStartup':true,
   'onClick': function(argsObj) { 
            if (argsObj.tabber.id == 'tab1') {
                        switch (getTabIDByName(argsObj.tabber.tabs[argsObj.index].headingText)) {
                                case "Planning Assessments": 
                                    if (getPrivate("plandept")=="1") {
                                        } else {
                                                setStatusMsg("Only authorized user can view this information.",4000,1);
                                                return false;
                                        }	
                                        break;
                                case "Engineering Assessments":
                                     if (getPrivate("engidept")=="1" ) {
                                        } else {
                                                setStatusMsg("Only authorized user can view this information.",4000,1);
                                                return false;
                                        }	
                                        break;  
                                case "Health Assessments":
                                     if (getPrivate("rhudept")=="1" ) {
                                        } else {
                                                setStatusMsg("Only authorized user can view this information.",4000,1);
                                                return false;
                                        }	
                                        break;  
                        }
            } 
        return true;
        }
};    

function onPageLoadGPSBPLS() {
}

function onPageResizeGPSBPLS(width,height) {
}

function onPageSubmitGPSBPLS(action) {
        if (action=="a" || action=="sc") {
              
		if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_bbrgy",null,null,"tab1",0)) return false;
		
	}
	return true;
}

function onCFLGPSBPLS(Id) {
	return true;
}

function onCFLGetParamsGPSBPLS(Id,params) {
	return params;
}

function onTaskBarLoadGPSBPLS() {
}

function onElementFocusGPSBPLS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSBPLS(element,event,column,table,row) {
}

function onElementValidateGPSBPLS(element,column,table,row) {
        switch (table) {
		case "T4":
			switch (column) {
                            case "u_quantity":
                                setBuildingPermitFee();
                                break;
                        }
                        break;
		case "T5":
			switch (column) {
                            case "u_quantity":
                                setMechanicalFee();
                                break;
                        }
                        break;
		case "T6":
			switch (column) {
                            case "u_quantity":
                                setPlumbingFee();
                                break;
                        }
                        break;
		case "T7":
			switch (column) {
                            case "u_quantity":
                                setElectricalFee();
                                break;
                        }
                        break;
		case "T8":
			switch (column) {
                            case "u_quantity":
                                setSignageFee();
                                break;
                        }
                        break;
		case "T1":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			break;
		case "T2":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			break;
		case "T3":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			break;
		default:
			switch (column) {
				case "u_businessname":
                                            if (getInput(column)!="") {
                                                        var result = page.executeFormattedQuery("select u_businessname, docno from u_bplreqapps where u_businessname = '"+addslashes(getInput(column))+"' and u_year = '"+ getInput("u_year")+"'");	
                                                        if (result.getAttribute("result")!= "-1") {
                                                                if (parseInt(result.getAttribute("result"))>0) {
                                                                    if(result.childNodes.item(0).getAttribute("docno")!=getInput("docno")){
                                                                           if(confirm("Businessname["+result.childNodes.item(0).getAttribute("u_businessname")+"] already registered with application number of [" + result.childNodes.item(0).getAttribute("docno")+"]")){
                                                                           }else{
                                                                                var result = page.executeFormattedQuery("select u_businessname from u_bplreqapps where docno = '"+getInput("docno")+"'");
                                                                                if (result.getAttribute("result")!= "-1") {
                                                                                    if (parseInt(result.getAttribute("result"))>0) {
                                                                                           setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
                                                                                    }
                                                                                }
                                                                           }
                                                                    }
                                                                      
                                                                }
                                                        } 
                                                }
                                        break;
				case "u_appdate":
					setDocNo(true,null,null,"u_appdate");
					break;
				
			}
			break;
	}	
	return true;
}

function onElementGetValidateParamsGPSBPLS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSBPLS(element,column,table,row) {
	return true;
}

function onElementChangeGPSBPLS(element,column,table,row) {
    
        switch (table) {
            default:
                    switch(column) {
                        case "u_sanitarychar":
                                        u_ajaxloadu_sanitarykinds("df_u_sanitarykind",element.value,'',":");
					setInputAmount("u_sanitarypermitfee",0);
                                      
					
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select a.u_amount as u_sanitarytax from u_sanitarycharacters a where a.code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_sanitarypermitfee",result.childNodes.item(0).getAttribute("u_sanitarytax"));
								setSanitaryTaxes();
							} else {
								setInputAmount("u_sanitarypermitfee",0);
								
								page.statusbar.showError("Invalid Character");	
								setSanitaryTaxes();
								return false;
							}
						} else {
							setInputAmount("u_sanitarypermitfee",0);
                                                      
							setSanitaryTaxes();
							page.statusbar.showError("Error retrieving Business Characters record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						
					} else {
						setInputAmount("u_sanitarypermitfee",0);
                                                
						setSanitaryTaxes();
					}						
					break;
                        case "u_sanitarykind":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_amount as u_sanitarytax  from u_sanitarycharacterkinds where code='"+getInput("u_sanitarychar")+"' and u_items='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_sanitarypermitfee",result.childNodes.item(0).getAttribute("u_sanitarytax"));
								setSanitaryTaxes();
							} else {
								setInputAmount("u_sanitarypermitfee",0);
								
								page.statusbar.showError("Invalid Kind");	
								setSanitaryTaxes();
								return false;
							}
						} else {
							setInputAmount("u_sanitarypermitfee",0);
							setSanitaryTaxes();
							page.statusbar.showError("Error retrieving Business Kind record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						
					} else {
						setInputAmount("u_sanitarypermitfee",0);
						setSanitaryTaxes();
					}						
					break;
                          
                    }
                break;
        }
	return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
	return true;
}

function onElementCFLGPSBPLS(element) {
	return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
	switch (Id) {
		case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feecodeT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feecodeT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table) {
        switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T2": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T3": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
	
}

function onTableAfterInsertRowGPSBPLS(table,row) {
        switch (table) {
		case "T1": computeTotalEngineeringAssessment();break;
		case "T2": computeTotalPlanAssessment();break;
		case "T3": computeTotalRhuAssessment();break;
	}
}

function onTableBeforeUpdateRowGPSBPLS(table,row) {
	 switch (table) {

		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T2": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T3": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
     switch (table) {
		case "T1": computeTotalEngineeringAssessment();break;
		case "T2": computeTotalPlanAssessment();break;
		case "T3": computeTotalRhuAssessment();break;
	}
}

function onTableBeforeDeleteRowGPSBPLS(table,row) {
	return true;
}

function onTableDeleteRowGPSBPLS(table,row) {
}

function onTableBeforeSelectRowGPSBPLS(table,row) {
	return true;
}

function onTableSelectRowGPSBPLS(table,row) {
        var params = new Array();
	switch (table) {
		case "T4":
		case "T5":
		case "T6":
		case "T7":
		case "T8":
			if (elementFocused.substring(0,13)=="df_u_quantity") {
				focusTableInput(table,"u_quantity",row);
			} 
			params["focus"]=false;
			break;
	}
	return params;
}

function computeTotalEngineeringAssessment() {
	var rc = getTableRowCount("T1"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
		}
	}
	setInputAmount("u_engiasstotal",total);
}
function computeTotalPlanAssessment() {
	var rc = getTableRowCount("T2"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			total+= getTableInputNumeric("T2","u_amount",i);
		}
	}
	setInputAmount("u_planasstotal",total);
}
function computeTotalRhuAssessment() {
	var rc = getTableRowCount("T3"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			total+= getTableInputNumeric("T3","u_amount",i);
		}
	}
	setInputAmount("u_rhuasstotal",total);
}
function addslashes(string) {
    return string.replace(/\\/g, '\\\\').
        replace(/\u0008/g, '\\b').
        replace(/\t/g, '\\t').
        replace(/\n/g, '\\n').
        replace(/\f/g, '\\f').
        replace(/\r/g, '\\r').
        replace(/'/g, '\\\'').
        replace(/"/g, '\\"');
}


function setSanitaryTaxes() {
	var sanitarypermitfeerc=0,rc = getTableRowCount("T3");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T3",xxx)==false) {
			if (getTableInput("T3","u_feecode",xxx)==getPrivate("sanitarypermitcode")) {
                                setTableInputAmount("T3","u_amount",getInputNumeric("u_sanitarypermitfee"),xxx);
				sanitarypermitfeerc=xxx;
			}
		}
	}
	
	if (sanitarypermitfeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("sanitarypermitcode");
		data["u_feedesc"] = getPrivate("sanitarypermitdesc");
		data["u_common"] = 1;
		if (getPrivate("bplkindcharlink")=="1") {
			if (getInputNumeric("u_businesskindtax")>0) data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindtax"));
			else  data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesschartax"));
		} else {
			data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindtax"));
		}			
		insertTableRowFromArray("T3",data);
	}
	computeTotalRhuAssessment();
}

function setBuildingPermitFee() {
	var buildingpermitfeerc=0;
	var buildingfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T4");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T4",xxx1)==false) {
                        setTableInputAmount("T4","u_linetotal",getTableInputNumeric("T4","u_quantity",xxx1) * getTableInputNumeric("T4","u_unitprice",xxx1),xxx1);
                        buildingfeetotal += getTableInputNumeric("T4","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("buildingfeecode")) {
                                setTableInputAmount("T1","u_amount",buildingfeetotal,xxx);
				buildingpermitfeerc=xxx;
			}
		}
	}
	
	if (buildingpermitfeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("buildingfeecode");
		data["u_feedesc"] = getPrivate("buildingfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(buildingfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}
function setMechanicalFee() {
	var mechanicalfeerc=0;
	var mechanicalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T5");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T5",xxx1)==false) {
                        var result = page.executeFormattedQuery("select u_maxunit from u_mechanicalcomputeitems where code = '"+getTableInput("T5","u_code",xxx1)+"' and u_desc = '"+getTableInput("T5","u_desc",xxx1)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (result.childNodes.item(0).getAttribute("u_maxunit") > 0) {
                                    if (getTableInputNumeric("T5","u_quantity",xxx1) > result.childNodes.item(0).getAttribute("u_maxunit")) {
                                        setTableInput("T5","u_quantity",0,xxx1);
                                        page.statusbar.showError("Invalid quantity");
                                    }
                                }
                            } 
                        } 
                        setTableInputAmount("T5","u_linetotal",getTableInputNumeric("T5","u_quantity",xxx1) * getTableInputNumeric("T5","u_unitprice",xxx1),xxx1);
                        mechanicalfeetotal += getTableInputNumeric("T5","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("mechanicalfeecode")) {
                                setTableInputAmount("T1","u_amount",mechanicalfeetotal,xxx);
				mechanicalfeerc=xxx;
			}
		}
	}
	
	if (mechanicalfeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("mechanicalfeecode");
		data["u_feedesc"] = getPrivate("mechanicalfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(mechanicalfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}
function setPlumbingFee() {
	var plumbingfeerc=0;
	var plumbingfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T6");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T6",xxx1)==false) {
                        setTableInputAmount("T6","u_linetotal",getTableInputNumeric("T6","u_quantity",xxx1) * getTableInputNumeric("T6","u_unitprice",xxx1),xxx1);
                        plumbingfeetotal += getTableInputNumeric("T6","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("plumbingfeecode")) {
                                setTableInputAmount("T1","u_amount",plumbingfeetotal,xxx);
				plumbingfeerc=xxx;
			}
		}
	}
	
	if (plumbingfeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("plumbingfeecode");
		data["u_feedesc"] = getPrivate("plumbingfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(plumbingfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}
function setElectricalFee() {
	var electricalfeerc=0;
	var electricalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T7");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T7",xxx1)==false) {
                        var result = page.executeFormattedQuery("select u_isfixamount,u_minkva,u_addperkva from u_electricalcomputeitems where code = '"+getTableInput("T7","u_code",xxx1)+"' and u_desc = '"+getTableInput("T7","u_desc",xxx1)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (result.childNodes.item(0).getAttribute("u_isfixamount") == 1 && getTableInputNumeric("T7","u_quantity",xxx1) > 0 ) {
                                     setTableInputAmount("T7","u_linetotal",getTableInputNumeric("T7","u_unitprice",xxx1),xxx1);
                                } else if (result.childNodes.item(0).getAttribute("u_minkva") > 0 && result.childNodes.item(0).getAttribute("u_addperkva") > 0) {
                                    if (getTableInputNumeric("T7","u_quantity",xxx1) > result.childNodes.item(0).getAttribute("u_minkva")) {
                                            setTableInputAmount("T7","u_linetotal",getTableInputNumeric("T7","u_unitprice",xxx1) + ((getTableInputNumeric("T7","u_quantity",xxx1) - result.childNodes.item(0).getAttribute("u_minkva")) * result.childNodes.item(0).getAttribute("u_addperkva")),xxx1); 
                                    }
                                } else {
                                    setTableInputAmount("T7","u_linetotal",getTableInputNumeric("T7","u_quantity",xxx1) * getTableInputNumeric("T7","u_unitprice",xxx1),xxx1);
                                }
                            } 
                        } 
                        electricalfeetotal += getTableInputNumeric("T7","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("electricalfeecode")) {
                                setTableInputAmount("T1","u_amount",electricalfeetotal,xxx);
				electricalfeerc=xxx;
			}
		}
	}
	
	if (electricalfeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("electricalfeecode");
		data["u_feedesc"] = getPrivate("electricalfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(electricalfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}
function setSignageFee() {
	var signagefeerc=0;
	var signagefeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T8");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T8",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_minprice from u_signagecomputeitems where code = '"+getTableInput("T8","u_code",xxx1)+"' and u_desc = '"+getTableInput("T8","u_desc",xxx1)+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            if (result.childNodes.item(0).getAttribute("u_minprice") > 0) {
                                var linetotal = 0;
                                linetotal = getTableInputNumeric("T8","u_quantity",xxx1) * getTableInputNumeric("T8","u_unitprice",xxx1);
                                if (result.childNodes.item(0).getAttribute("u_minprice") > parseFloat(linetotal) && getTableInputNumeric("T8","u_quantity",xxx1) > 0 ) { 
                                    setTableInputAmount("T8","u_linetotal",result.childNodes.item(0).getAttribute("u_minprice"),xxx1);
                                } else {
                                    setTableInputAmount("T8","u_linetotal",linetotal,xxx1);
                                }
                            }
                        } 
                    }
                        signagefeetotal += getTableInputNumeric("T8","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("signagefeecode")) {
                                setTableInputAmount("T1","u_amount",signagefeetotal,xxx);
				signagefeerc=xxx;
			}
		}
	}
	
	if (signagefeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("signagefeecode");
		data["u_feedesc"] = getPrivate("signagefeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(signagefeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}

