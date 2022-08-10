// page events
page.events.add.load('onPageLoadGPSLGUBuilding');
//page.events.add.resize('onPageResizeGPSLGUBuilding');
page.events.add.submit('onPageSubmitGPSLGUBuilding');
//page.events.add.cfl('onCFLGPSLGUBuilding');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUBuilding');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUBuilding');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUBuilding');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUBuilding');
page.elements.events.add.validate('onElementValidateGPSLGUBuilding');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUBuilding');
//page.elements.events.add.changing('onElementChangingGPSLGUBuilding');
//page.elements.events.add.change('onElementChangeGPSLGUBuilding');
//page.elements.events.add.click('onElementClickGPSLGUBuilding');
//page.elements.events.add.cfl('onElementCFLGPSLGUBuilding');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUBuilding');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUBuilding');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUBuilding');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUBuilding');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUBuilding');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUBuilding');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUBuilding');
page.tables.events.add.delete('onTableDeleteRowGPSLGUBuilding');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUBuilding');
page.tables.events.add.select('onTableSelectRowGPSLGUBuilding');

function onPageLoadGPSLGUBuilding() {
    if (getVar("formSubmitAction")=="a") {
		selectTab("tab1",0);
                setTableInput("T1","u_year",getInput("u_year"));
	}else{
            selectTab("tab1",0);
        }
    if (getInput("docstatus")=="D") {
            enableInput("docno");
    }
}

function onPageResizeGPSLGUBuilding(width,height) {
}

function onPageSubmitGPSLGUBuilding(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_businessname")) return false;
		if (isInputEmpty("u_bbrgy")) return false;
		if(getInput("u_appnature")!='Others') if (isInputNegative("u_orsfamt")) return false;
	}
	return true;
}

function onCFLGPSLGUBuilding(Id) {
	return true;
}

function onCFLGetParamsGPSLGUBuilding(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUBuilding() {
}

function onElementFocusGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUBuilding(element,event,column,table,row) {
}

function onElementValidateGPSLGUBuilding(element,column,table,row) {
	switch (table) {
                case "T1":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select a.code, a.name, a.u_amount,b.u_seqno from u_lgufees a inner join u_bldgfees b on a.code = b.code  where a.code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select a.code, a.name, a.u_amount, b.u_seqno from u_lgufees a inner join u_bldgfees b on a.code = b.code where a.name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_seqno",result.childNodes.item(0).getAttribute("u_seqno"));
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
                            case "u_quantity":
                                setBuildingPermitFee();
                                break;
                        }
                        break;
		case "T3":
			switch (column) {
                            case "u_quantity":
                                setMechanicalFee();
                                break;
                        }
                        break;
		case "T4":
			switch (column) {
                            case "u_quantity":
                                setPlumbingFee();
                                break;
                        }
                        break;
		case "T5":
			switch (column) {
                            case "u_quantity":
                                setElectricalFee();
                                break;
                        }
                        break;
		case "T6":
			switch (column) {
                            case "u_quantity":
                                setSignageFee();
                                break;
                        }
                        break;
		case "T8":
			switch (column) {
                            case "u_quantity":
                               setMechanicalFeeGroup();
                               setMechanicalFee();
                                break;
                        }
                        break;
		case "T10":
			switch (column) {
                            case "u_quantity":
                               setElectricalFeeGroup();
                               setElectricalFee();
                                break;
                        }
                        break;
		default:
			switch (column) {
				case "u_bdlgcategory":
                                    if (getInput(column)!="") {
                                        showAjaxProcess();
                                        getBldgCategory();
                                        getMechCategory();
                                        getElectricalCategory();
                                        getPlumbingCategory();
                                        getSignageCategory();
                                        hideAjaxProcess();
                                    }
                                break;
				case "u_zoningappno":
                                            if (getInput(column)!="") {
                                                        var result = page.executeFormattedQuery("select * from u_zoningclrapps where docno = '"+getInput(column)+"'");	
                                                        if (result.getAttribute("result")!= "-1") {
                                                                if (parseInt(result.getAttribute("result"))>0) {
                                                                    if(result.childNodes.item(0).getAttribute("docstatus") == "DA") {
                                                                        if(confirm("Zoning Department Disapproved this application. you want to continue?")){
                                                                        } else {
                                                                            return false;
                                                                        }
                                                                    }
                                                                    setInput("docseries","-1");
                                                                    setInput("docno",result.childNodes.item(0).getAttribute("docno"));
                                                                    setInput("u_isonlineapp",result.childNodes.item(0).getAttribute("u_isonlineapp"));
                                                                    setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
                                                                    setInput("u_authrep",result.childNodes.item(0).getAttribute("u_authrep"));
                                                                    setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
                                                                    setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
                                                                    setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
                                                                    setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
                                                                    setInput("u_contactno",result.childNodes.item(0).getAttribute("u_contactno"));
                                                                    setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_owneraddress"));
                                                                    setInput("u_bbrgy",result.childNodes.item(0).getAttribute("u_bbrgy"));
                                                                    setInput("u_bbldgno",result.childNodes.item(0).getAttribute("u_bbldgno"));
                                                                    setInput("u_bblock",result.childNodes.item(0).getAttribute("u_bblock"));
                                                                    setInput("u_blotno",result.childNodes.item(0).getAttribute("u_blotno"));
                                                                    setInput("u_bstreet",result.childNodes.item(0).getAttribute("u_bstreet"));
                                                                    setInput("u_bphaseno",result.childNodes.item(0).getAttribute("u_bphaseno"));
                                                                    setInput("u_baddressno",result.childNodes.item(0).getAttribute("u_baddressno"));
                                                                    setInput("u_bvillage",result.childNodes.item(0).getAttribute("u_bvillage"));
                                                                    setInput("u_bbldgname",result.childNodes.item(0).getAttribute("u_bbldgname"));
                                                                    setInput("u_bunitno",result.childNodes.item(0).getAttribute("u_bunitno"));
                                                                    setInput("u_bfloorno",result.childNodes.item(0).getAttribute("u_bfloorno"));
                                                                    setInput("u_btelno",result.childNodes.item(0).getAttribute("u_btelno"));
                                                                } else {
                                                                    setInput("docno","");
                                                                    setInput("u_isonlineapp","");
                                                                    setInput("u_businessname","");
                                                                    setInput("u_authrep","");
                                                                    setInput("u_lastname","");
                                                                    setInput("u_firstname","");
                                                                    setInput("u_middlename","");
                                                                    setInput("u_gender","");
                                                                    setInput("u_contactno","");
                                                                    setInput("u_owneraddress","");
                                                                    setInput("u_lastname","");
                                                                    setInput("u_middlename","");
                                                                    setInput("u_bbrgy","");
                                                                    setInput("u_bbldgno","");
                                                                    setInput("u_bblock","");
                                                                    setInput("u_blotno","");
                                                                    setInput("u_bstreet","");
                                                                    setInput("u_bphaseno","");
                                                                    setInput("u_baddressno","");
                                                                    setInput("u_bvillage","");
                                                                    setInput("u_bbldgname","");
                                                                    setInput("u_bunitno","");
                                                                    setInput("u_bfloorno","");
                                                                    setInput("u_btelno","");
                                                                    page.statusbar.showError("Invalid Application Serial No.");
                                                                    return false;
                                                                }
                                                        } 
                                                }
                                        break;
				case "u_businessname":
//                                            if (getInput(column)!="") {
//                                                        var result = page.executeFormattedQuery("select u_businessname, docno from u_bplreqapps where u_businessname = '"+addslashes(getInput(column))+"' and u_year = '"+ getInput("u_year")+"'");	
//                                                        if (result.getAttribute("result")!= "-1") {
//                                                                if (parseInt(result.getAttribute("result"))>0) {
//                                                                    if(result.childNodes.item(0).getAttribute("docno")!=getInput("docno")){
//                                                                           if(confirm("Businessname["+result.childNodes.item(0).getAttribute("u_businessname")+"] already registered with application number of [" + result.childNodes.item(0).getAttribute("docno")+"]")){
//                                                                           }else{
//                                                                                var result = page.executeFormattedQuery("select u_businessname from u_bplreqapps where docno = '"+getInput("docno")+"'");
//                                                                                if (result.getAttribute("result")!= "-1") {
//                                                                                    if (parseInt(result.getAttribute("result"))>0) {
//                                                                                           setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
//                                                                                    }
//                                                                                }
//                                                                           }
//                                                                    }
//                                                                      
//                                                                }
//                                                        } 
//                                                }
                                        break;
			}
			break;
	}	
	return true;
}

function onElementGetValidateParamsGPSLGUBuilding(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUBuilding(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUBuilding(Id,params) {
        switch (Id) {
                case "df_u_natureofbusiness":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME FROM U_BPLNATURE")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Business Nature")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_bldgfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_bldgfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_zoningappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_year,u_businessname,docstatus from u_zoningclrapps where docstatus = 'AP'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Reference No`Year`Business Name`Status")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}	
	return params;
}

function onTableResetRowGPSLGUBuilding(table) {
}

function onTableBeforeInsertRowGPSLGUBuilding(table,row) {
        switch (table) {
		case "T1":
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUBuilding(table,row) { 
        switch (table) {
		case "T1": computeTotalEngineeringAssessment(); setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row); break;
	}
}

function onTableBeforeUpdateRowGPSLGUBuilding(table,row) {
	switch (table) {
		case "T1": 
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;  
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUBuilding(table,row) {
        switch (table) {
		case "T1": computeTotalEngineeringAssessment(); setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row); break;
	}
}

function onTableBeforeDeleteRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUBuilding(table,row) {
        switch (table) {
		case "T1": computeTotalEngineeringAssessment();break;
	}
}

function onTableBeforeSelectRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableSelectRowGPSLGUBuilding(table,row) {
     var params = new Array();
	switch (table) {
		case "T2":
		case "T3":
		case "T4":
		case "T5":
		case "T6":
		case "T7":
		case "T8":
		case "T9":
		case "T10":
		case "T11":
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
	setInputAmount("u_orsfamt",total);
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

function setBuildingPermitFee() {
	var buildingpermitfeerc=0;
	var buildingfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T2");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T2",xxx1)==false) {
                        setTableInputAmount("T2","u_linetotal",getTableInputNumeric("T2","u_quantity",xxx1) * getTableInputNumeric("T2","u_unitprice",xxx1),xxx1);
                        buildingfeetotal += getTableInputNumeric("T2","u_linetotal",xxx1);
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
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("buildingfeecode");
		data["u_feedesc"] = getPrivate("buildingfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(buildingfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}
function setMechanicalFeeGroup() {
	var mechanicalfeerc=0;
	var mechanicalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T3");
        var rc8 = getTableRowCount("T8");
        
        for (i8 = 1; i8 <=rc8; i8++) {
            if (isTableRowDeleted("T8",i8)==false) {
                if (getTableInput("T8","u_quantity",i8) > 0) {
                    var total = getTableInputNumeric("T8","u_quantity",i8);
                    var result = page.executeFormattedQuery("select code,u_desc,u_maxunit from u_mechanicalcomputeitems where code = '"+getTableInput("T8","u_code",i8)+"' order by u_seqno");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                
                                for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
                                    if (isTableRowDeleted("T3",xxx1)==false) {
                                        if (getTableInput("T3","u_code",xxx1)==result.childNodes.item(xxx).getAttribute("code") && getTableInput("T3","u_desc",xxx1)==result.childNodes.item(xxx).getAttribute("u_desc")) {
                                            if (total > 0) {
                                                if (total > result.childNodes.item(xxx).getAttribute("u_maxunit")) {
                                                    setTableInput("T3","u_quantity",result.childNodes.item(xxx).getAttribute("u_maxunit"),xxx1);
                                                } else {
                                                    setTableInput("T3","u_quantity",total,xxx1);
                                                }
                                                total = total - result.childNodes.item(xxx).getAttribute("u_maxunit");
                                            } else {
                                                setTableInput("T3","u_quantity",0,xxx1);
                                            }
                                        }
                                    }
                                }
                                
                            }
                        }
                    }
                }
            }
        }
}


function setMechanicalFee() {
	var mechanicalfeerc=0;
	var mechanicalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T3");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T3",xxx1)==false) {
                        var result = page.executeFormattedQuery("select u_maxunit from u_mechanicalcomputeitems where code = '"+getTableInput("T3","u_code",xxx1)+"' and u_desc = '"+getTableInput("T3","u_desc",xxx1)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (result.childNodes.item(0).getAttribute("u_maxunit") > 0) {
                                    if (getTableInputNumeric("T3","u_quantity",xxx1) > result.childNodes.item(0).getAttribute("u_maxunit")) {
                                        setTableInput("T3","u_quantity",0,xxx1);
                                        page.statusbar.showError("Invalid quantity");
                                    }
                                }
                            } 
                        } 
                        setTableInputAmount("T3","u_linetotal",getTableInputNumeric("T3","u_quantity",xxx1) * getTableInputNumeric("T3","u_unitprice",xxx1),xxx1);
                        mechanicalfeetotal += getTableInputNumeric("T3","u_linetotal",xxx1);
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
		data["u_year"] = getInput("u_year");
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
        var rc2 = getTableRowCount("T4");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T4",xxx1)==false) {
                        setTableInputAmount("T4","u_linetotal",getTableInputNumeric("T4","u_quantity",xxx1) * getTableInputNumeric("T4","u_unitprice",xxx1),xxx1);
                        plumbingfeetotal += getTableInputNumeric("T4","u_linetotal",xxx1);
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
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("plumbingfeecode");
		data["u_feedesc"] = getPrivate("plumbingfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(plumbingfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}
function setElectricalFeeGroup() {
	var mechanicalfeerc=0;
	var mechanicalfeetotal=0;
        var rc2 = getTableRowCount("T5");
        var rc8 = getTableRowCount("T10");
        for (i8 = 1; i8 <=rc8; i8++) {
            if (isTableRowDeleted("T10",i8)==false) {
                if (getTableInput("T10","u_quantity",i8) > 0) {
                    var total = getTableInputNumeric("T10","u_quantity",i8);
                    var result = page.executeFormattedQuery("select code,u_desc,u_minkva,u_maxkva from u_electricalcomputeitems where code = '"+getTableInput("T10","u_code",i8)+"' order by u_seqno");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
                                    if (isTableRowDeleted("T5",xxx1)==false) {
                                        if (getTableInput("T5","u_code",xxx1)==result.childNodes.item(xxx).getAttribute("code") && getTableInput("T5","u_desc",xxx1)==result.childNodes.item(xxx).getAttribute("u_desc")) {
                                            if (total > 0) {
                                                if (total >= result.childNodes.item(xxx).getAttribute("u_minkva") && total <= result.childNodes.item(xxx).getAttribute("u_maxkva")) {
                                                    setTableInput("T5","u_quantity",total,xxx1);
                                                } else {
                                                    setTableInput("T5","u_quantity",0,xxx1);
                                                }
                                            } else {
                                                setTableInput("T5","u_quantity",0,xxx1);
                                            }
                                        }
                                    }
                                }
                                
                            }
                        }
                    }
                }
            }
        }
}
function setElectricalFee() {
	var electricalfeerc=0;
	var electricalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T5");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T5",xxx1)==false) {
                        var result = page.executeFormattedQuery("select u_isfixamount,u_minkva,u_addperkva from u_electricalcomputeitems where code = '"+getTableInput("T5","u_code",xxx1)+"' and u_desc = '"+getTableInput("T5","u_desc",xxx1)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (result.childNodes.item(0).getAttribute("u_isfixamount") == 1 && getTableInputNumeric("T5","u_quantity",xxx1) > 0 ) {
                                     setTableInputAmount("T5","u_linetotal",getTableInputNumeric("T5","u_unitprice",xxx1),xxx1);
                                } else if (result.childNodes.item(0).getAttribute("u_minkva") > 0 && result.childNodes.item(0).getAttribute("u_addperkva") > 0) {
                                    if (getTableInputNumeric("T5","u_quantity",xxx1) > result.childNodes.item(0).getAttribute("u_minkva")) {
                                            setTableInputAmount("T5","u_linetotal",getTableInputNumeric("T5","u_unitprice",xxx1) + ((getTableInputNumeric("T5","u_quantity",xxx1) - result.childNodes.item(0).getAttribute("u_minkva")) * result.childNodes.item(0).getAttribute("u_addperkva")),xxx1); 
                                    } else {
                                            setTableInputAmount("T5","u_linetotal",getTableInputNumeric("T5","u_quantity",xxx1) * getTableInputNumeric("T5","u_unitprice",xxx1),xxx1);
                                    }
                                } else {
                                    setTableInputAmount("T5","u_linetotal",getTableInputNumeric("T5","u_quantity",xxx1) * getTableInputNumeric("T5","u_unitprice",xxx1),xxx1);
                                }
                            } 
                        } 
                        electricalfeetotal += getTableInputNumeric("T5","u_linetotal",xxx1);
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
		data["u_year"] = getInput("u_year");
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
        var rc2 = getTableRowCount("T6");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T6",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_minprice from u_signagecomputeitems where code = '"+getTableInput("T6","u_code",xxx1)+"' and u_desc = '"+getTableInput("T6","u_desc",xxx1)+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            if (result.childNodes.item(0).getAttribute("u_minprice") > 0) {
                                var linetotal = 0;
                                linetotal = getTableInputNumeric("T6","u_quantity",xxx1) * getTableInputNumeric("T6","u_unitprice",xxx1);
                                if (result.childNodes.item(0).getAttribute("u_minprice") > parseFloat(linetotal) && getTableInputNumeric("T6","u_quantity",xxx1) > 0 ) { 
                                    setTableInputAmount("T6","u_linetotal",result.childNodes.item(0).getAttribute("u_minprice"),xxx1);
                                } else {
                                    setTableInputAmount("T6","u_linetotal",linetotal,xxx1);
                                }
                            }
                        } 
                    }
                        signagefeetotal += getTableInputNumeric("T6","u_linetotal",xxx1);
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
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("signagefeecode");
		data["u_feedesc"] = getPrivate("signagefeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(signagefeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}


function u_approveGPSLGUBuilding() {
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee must be entered.");
		return false;
	}
        if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	setInput("docstatus","AP");
	formSubmit('sc');
}

function u_disapproveGPSLGUBuilding() {
	setInput("docstatus","DA");
	formSubmit('sc');
}

function getBldgCategory() {
	var buildingpermitfeerc=0;
	var buildingfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T2");
        
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T2",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_code,u_desc,u_quantity,u_unitprice,u_linetotal from u_bldgappcategorybldg where code = '"+getInput("u_bdlgcategory")+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                if (getTableInput("T2","u_code",xxx1) == result.childNodes.item(xxx).getAttribute("u_code") && getTableInput("T2","u_desc",xxx1) == result.childNodes.item(xxx).getAttribute("u_desc")){
                                setTableInput("T2","u_quantity",result.childNodes.item(xxx).getAttribute("u_quantity"),xxx1);
                                setTableInputAmount("T2","u_unitprice",result.childNodes.item(xxx).getAttribute("u_unitprice"),xxx1);
                                setTableInputAmount("T2","u_linetotal",result.childNodes.item(xxx).getAttribute("u_linetotal"),xxx1);
                                }
                            }
                        } 
                    }
                        buildingfeetotal += getTableInputNumeric("T2","u_linetotal",xxx1);
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
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("buildingfeecode");
		data["u_feedesc"] = getPrivate("buildingfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(buildingfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}

function getMechCategory() {
	var mechanicalpermitfeerc=0;
	var mechanicalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T3");
        
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T3",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_code,u_desc,u_quantity,u_unitprice,u_linetotal from u_bldgappcategorymech where code = '"+getInput("u_bdlgcategory")+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                if (getTableInput("T3","u_code",xxx1) == result.childNodes.item(xxx).getAttribute("u_code") && getTableInput("T3","u_desc",xxx1) == result.childNodes.item(xxx).getAttribute("u_desc")){
                                setTableInput("T3","u_quantity",result.childNodes.item(xxx).getAttribute("u_quantity"),xxx1);
                                setTableInputAmount("T3","u_unitprice",result.childNodes.item(xxx).getAttribute("u_unitprice"),xxx1);
                                setTableInputAmount("T3","u_linetotal",result.childNodes.item(xxx).getAttribute("u_linetotal"),xxx1);
                                }
                            }
                        } 
                    }
                        mechanicalfeetotal += getTableInputNumeric("T3","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("mechanicalfeecode")) {
                                setTableInputAmount("T1","u_amount",mechanicalfeetotal,xxx);
				mechanicalpermitfeerc=xxx;
			}
		}
	}
	
	if (mechanicalpermitfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("mechanicalfeecode");
		data["u_feedesc"] = getPrivate("mechanicalfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(mechanicalfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}

function getPlumbingCategory() {
	var plumbingpermitfeerc=0;
	var plumbingfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T4");
        
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T4",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_code,u_desc,u_quantity,u_unitprice,u_linetotal from u_bldgappcategoryplumbing where code = '"+getInput("u_bdlgcategory")+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                if (getTableInput("T4","u_code",xxx1) == result.childNodes.item(xxx).getAttribute("u_code") && getTableInput("T4","u_desc",xxx1) == result.childNodes.item(xxx).getAttribute("u_desc")){
                                setTableInput("T4","u_quantity",result.childNodes.item(xxx).getAttribute("u_quantity"),xxx1);
                                setTableInputAmount("T4","u_unitprice",result.childNodes.item(xxx).getAttribute("u_unitprice"),xxx1);
                                setTableInputAmount("T4","u_linetotal",result.childNodes.item(xxx).getAttribute("u_linetotal"),xxx1);
                                }
                            }
                        } 
                    }
                        plumbingfeetotal += getTableInputNumeric("T4","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("plumbingfeecode")) {
                                setTableInputAmount("T1","u_amount",plumbingfeetotal,xxx);
				plumbingpermitfeerc=xxx;
			}
		}
	}
	
	if (plumbingpermitfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("plumbingfeecode");
		data["u_feedesc"] = getPrivate("plumbingfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(plumbingfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}

function getElectricalCategory() {
	var electricalpermitfeerc=0;
	var electricalfeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T5");
        
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T5",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_code,u_desc,u_quantity,u_unitprice,u_linetotal from u_bldgappcategoryelectrical where code = '"+getInput("u_bdlgcategory")+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                if (getTableInput("T5","u_code",xxx1) == result.childNodes.item(xxx).getAttribute("u_code") && getTableInput("T5","u_desc",xxx1) == result.childNodes.item(xxx).getAttribute("u_desc")){
                                setTableInput("T5","u_quantity",result.childNodes.item(xxx).getAttribute("u_quantity"),xxx1);
                                setTableInputAmount("T5","u_unitprice",result.childNodes.item(xxx).getAttribute("u_unitprice"),xxx1);
                                setTableInputAmount("T5","u_linetotal",result.childNodes.item(xxx).getAttribute("u_linetotal"),xxx1);
                                }
                            }
                        } 
                    }
                        electricalfeetotal += getTableInputNumeric("T5","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("electricalfeecode")) {
                                setTableInputAmount("T1","u_amount",electricalfeetotal,xxx);
				electricalpermitfeerc=xxx;
			}
		}
	}
	
	if (electricalpermitfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("electricalfeecode");
		data["u_feedesc"] = getPrivate("electricalfeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(electricalfeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}

function getSignageCategory() {
	var signagepermitfeerc=0;
	var signagefeetotal=0;
        var rc = getTableRowCount("T1");
        var rc2 = getTableRowCount("T6");
        
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T6",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_code,u_desc,u_quantity,u_unitprice,u_linetotal from u_bldgappcategorysignage where code = '"+getInput("u_bdlgcategory")+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                if (getTableInput("T6","u_code",xxx1) == result.childNodes.item(xxx).getAttribute("u_code") && getTableInput("T6","u_desc",xxx1) == result.childNodes.item(xxx).getAttribute("u_desc")){
                                setTableInput("T6","u_quantity",result.childNodes.item(xxx).getAttribute("u_quantity"),xxx1);
                                setTableInputAmount("T6","u_unitprice",result.childNodes.item(xxx).getAttribute("u_unitprice"),xxx1);
                                setTableInputAmount("T6","u_linetotal",result.childNodes.item(xxx).getAttribute("u_linetotal"),xxx1);
                                }
                            }
                        } 
                    }
                        signagefeetotal += getTableInputNumeric("T6","u_linetotal",xxx1);
                }
	}
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("signagefeecode")) {
                                setTableInputAmount("T1","u_amount",signagefeetotal,xxx);
				signagepermitfeerc=xxx;
			}
		}
	}
	
	if (signagepermitfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("signagefeecode");
		data["u_feedesc"] = getPrivate("signagefeedesc");
		data["u_common"] = 1;
                data["u_amount"] = formatNumericAmount(signagefeetotal);
		insertTableRowFromArray("T1",data);
	}
	computeTotalEngineeringAssessment();
}

function u_PaymentHistoryGPSGLGUBuilding() {
        OpenPopup(1024,500,"./udp.php?&objectcode=u_bldgledger&df_refno2="+getInput("u_acctno")+"","Business Ledger");
}

function u_reassessGPSLGUBuilding() {
	if (isInputEmpty("u_remarks",null,null,"tab1",6)) return false;
	setInput("docstatus","D");
	formSubmit('sc');
}