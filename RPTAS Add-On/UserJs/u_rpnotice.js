// page events
//page.events.add.load('onPageLoadGPSRPTAS');
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
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
//page.elements.events.add.change('onElementChangeGPSRPTAS');
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

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
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
        if (action=="a" || action=="sc") {
            if (isInputEmpty("u_declaredowner")) return false;
            if (isInputNegative("u_totalassvalue")) return false;
        }
	return true;
}
function onPageSubmitReturnGPSRPTAS(action,sucess,error) {
	
	return true;
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
                            
                            case "u_tdno":
                                if(getInput("searchby")== "L") var result = page.executeFormattedQuery("select docno,u_trxcode,u_pin,u_barangay,'L' as u_kind,u_assvalue,u_marketvalue,if(u_location='',u_barangay,u_location) as u_location from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' and u_cancelled <> 1");
                                else if(getInput("searchby")== "B") var result = page.executeFormattedQuery("Select docno,u_trxcode,u_pin,u_barangay,'B' as u_kind,u_assvalue,u_marketvalue,if(u_location='',u_barangay,u_location) as u_location  from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' and u_cancelled <> 1");
                                else var result = page.executeFormattedQuery("Select docno,u_trxcode,u_pin,u_barangay,'M' as u_kind,u_assvalue,u_marketvalue,if(u_location='',u_barangay,u_location) as u_location from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' and u_cancelled <> 1");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                if (result.childNodes.item(0).getAttribute("u_kind")=="L") {
                                                        var result1 = page.executeFormattedQuery("SELECT MAX(A.U_ASSVALUE) AS U_MAXIMUM,B.NAME AS U_CLASS FROM U_RPFAAS1 C LEFT JOIN U_RPFAAS1A A ON A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH AND C.DOCNO = A.U_ARPNO LEFT JOIN U_RPLANDS B ON A.U_CLASS = B.CODE where C.COMPANY='"+getGlobal("company")+"' and C.BRANCH='"+getGlobal("branch")+"' and C.U_VARPNO='"+getTableInput(table,column)+"' GROUP BY C.DOCNO");
                                                        if (result1.getAttribute("result")!= "-1") {
                                                            if (parseInt(result1.getAttribute("result"))>0) {
                                                                setTableInput(table,"u_class",result1.childNodes.item(0).getAttribute("u_class"));
                                                            }
                                                        }
                                                } else if(result.childNodes.item(0).getAttribute("u_kind")=="B") {
                                                        var result1 = page.executeFormattedQuery("SELECT MAX(C.U_ASSVALUE) as U_MAXIMUM,B.NAME AS U_CLASS FROM U_RPFAAS2 A LEFT JOIN U_RPFAAS2C C ON A.DOCID = C.DOCID AND A.BRANCH = C.BRANCH AND A.COMPANY = C.COMPANY LEFT JOIN U_RPACTUSES B ON C.U_ACTUALUSE = B.CODE WHERE A.COMPANY='"+getGlobal("company")+"' and A.BRANCH='"+getGlobal("branch")+"' and A.U_VARPNO='"+getTableInput(table,column)+"' GROUP BY A.DOCID");
                                                        if (result1.getAttribute("result")!= "-1") {
                                                            if (parseInt(result1.getAttribute("result"))>0) {
                                                                setTableInput(table,"u_class",result1.childNodes.item(0).getAttribute("u_class"));
                                                            }
                                                        }
                                                } else {
                                                        var result1 = page.executeFormattedQuery("SELECT MAX(C.U_ASSVALUE) as U_MAXIMUM,B.NAME AS U_CLASS FROM U_RPFAAS3 A LEFT JOIN U_RPFAAS3B C ON A.DOCID = C.DOCID AND A.BRANCH = C.BRANCH AND A.COMPANY = C.COMPANY LEFT JOIN U_RPACTUSES B ON C.U_ACTUALUSE = B.CODE WHERE A.COMPANY='"+getGlobal("company")+"' and A.BRANCH='"+getGlobal("branch")+"' and A.U_VARPNO='"+getTableInput(table,column)+"' GROUP BY A.DOCID");
                                                        if (result1.getAttribute("result")!= "-1") {
                                                            if (parseInt(result1.getAttribute("result"))>0) {
                                                                setTableInput(table,"u_class",result1.childNodes.item(0).getAttribute("u_class"));
                                                            }
                                                        }
                                                }  
                                                setTableInput(table,"u_docno",result.childNodes.item(0).getAttribute("docno"));
                                                setTableInput(table,"u_trxcode",result.childNodes.item(0).getAttribute("u_trxcode"));
                                                setTableInput(table,"u_pin",result.childNodes.item(0).getAttribute("u_pin"));
                                                setTableInput(table,"u_location",result.childNodes.item(0).getAttribute("u_location"));
                                                setTableInput(table,"u_kind",result.childNodes.item(0).getAttribute("u_kind"));
                                                setTableInput(table,"u_assvalue",result.childNodes.item(0).getAttribute("u_assvalue"));
                                                setTableInput(table,"u_marketvalue",result.childNodes.item(0).getAttribute("u_marketvalue"));
                                            }else{
                                                setTableInput(table,"u_tdno","");
                                                setTableInput(table,"u_trxcode","");
                                                setTableInput(table,"u_pin","");
                                                setTableInput(table,"u_location","");
                                                setTableInput(table,"u_kind","");
                                                setTableInput(table,"u_assvalue","");
                                                setTableInput(table,"u_marketvalue","");
                                                page.statusbar.showError("Invalid ARP No.");	
                                                return false;
                                            }
                                    } else {
                                                setTableInput(table,"u_tdno","");
                                                setTableInput(table,"u_trxcode","");
                                                setTableInput(table,"u_pin","");
                                                setTableInput(table,"u_location","");
                                                setTableInput(table,"u_kind","");
                                                setTableInput(table,"u_assvalue","");
                                                setTableInput(table,"u_marketvalue","");
                                        page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_docno":
                                if(getInput("searchby")== "L") var result = page.executeFormattedQuery("select u_varpno,docno,u_trxcode,u_pin,u_barangay,'L' as u_kind,u_assvalue,u_marketvalue,if(u_location='',u_barangay,u_location) as u_location from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"'  and u_cancelled <> 1 ");
                                else if(getInput("searchby")== "B") var result = page.executeFormattedQuery("select u_varpno,docno,u_trxcode,u_pin,u_barangay,'B' as u_kind,u_assvalue,u_marketvalue,if(u_location='',u_barangay,u_location) as u_location  from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' and u_cancelled <> 1 ");
                                else var result = page.executeFormattedQuery("Select u_varpno,docno,u_trxcode,u_pin,u_barangay,'M' as u_kind,u_assvalue,u_marketvalue,if(u_location='',u_barangay,u_location) as u_location  from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' and u_cancelled <> 1 ");
                                    if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {
                                                if(result.childNodes.item(0).getAttribute("u_kind")=="L"){
                                                        var result1 = page.executeFormattedQuery("SELECT MAX(A.U_ASSVALUE) AS U_MAXIMUM,B.NAME AS U_CLASS FROM U_RPFAAS1A A LEFT JOIN U_RPLANDS B ON A.U_CLASS = B.CODE where A.COMPANY='"+getGlobal("company")+"' and A.BRANCH='"+getGlobal("branch")+"' and A.U_ARPNO='"+getTableInput(table,column)+"' GROUP BY A.U_ARPNO");
                                                        if (result1.getAttribute("result")!= "-1") {
                                                            if (parseInt(result1.getAttribute("result"))>0) {
                                                                setTableInput(table,"u_class",result1.childNodes.item(0).getAttribute("u_class"));
                                                            }
                                                        }
                                                }else if(result.childNodes.item(0).getAttribute("u_kind")=="B"){
                                                        var result1 = page.executeFormattedQuery("SELECT MAX(C.U_ASSVALUE) as U_MAXIMUM,B.NAME AS U_CLASS FROM U_RPFAAS2 A LEFT JOIN U_RPFAAS2C C ON A.DOCID = C.DOCID AND A.BRANCH = C.BRANCH AND A.COMPANY = C.COMPANY LEFT JOIN U_RPACTUSES B ON C.U_ACTUALUSE = B.CODE WHERE A.COMPANY='"+getGlobal("company")+"' and A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO='"+getTableInput(table,column)+"' GROUP BY A.DOCID");
                                                        if (result1.getAttribute("result")!= "-1") {
                                                            if (parseInt(result1.getAttribute("result"))>0) {
                                                                setTableInput(table,"u_class",result1.childNodes.item(0).getAttribute("u_class"));
                                                            }
                                                        }
                                                }else{
                                                        var result1 = page.executeFormattedQuery("SELECT MAX(C.U_ASSVALUE) as U_MAXIMUM,B.NAME AS U_CLASS FROM U_RPFAAS3 A LEFT JOIN U_RPFAAS3B C ON A.DOCID = C.DOCID AND A.BRANCH = C.BRANCH AND A.COMPANY = C.COMPANY LEFT JOIN U_RPACTUSES B ON C.U_ACTUALUSE = B.CODE WHERE A.COMPANY='"+getGlobal("company")+"' and A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO='"+getTableInput(table,column)+"' GROUP BY A.DOCID");
                                                        if (result1.getAttribute("result")!= "-1") {
                                                            if (parseInt(result1.getAttribute("result"))>0) {
                                                                setTableInput(table,"u_class",result1.childNodes.item(0).getAttribute("u_class"));
                                                            }
                                                        }
                                                }   
                                            setTableInput(table,"u_tdno",result.childNodes.item(0).getAttribute("u_varpno"));
                                            setTableInput(table,"u_docno",result.childNodes.item(0).getAttribute("docno"));
                                            setTableInput(table,"u_trxcode",result.childNodes.item(0).getAttribute("u_trxcode"));
                                            setTableInput(table,"u_pin",result.childNodes.item(0).getAttribute("u_pin"));
                                            setTableInput(table,"u_location",result.childNodes.item(0).getAttribute("u_barangay"));
                                            setTableInput(table,"u_kind",result.childNodes.item(0).getAttribute("u_kind"));
                                            setTableInput(table,"u_assvalue",result.childNodes.item(0).getAttribute("u_assvalue"));
                                            setTableInput(table,"u_marketvalue",result.childNodes.item(0).getAttribute("u_marketvalue"));
                                        }else{
                                            setTableInput(table,"u_tdno","");
                                            setTableInput(table,"u_trxcode","");
                                            setTableInput(table,"u_pin","");
                                            setTableInput(table,"u_location","");
                                            setTableInput(table,"u_kind","");
                                            setTableInput(table,"u_assvalue","");
                                            setTableInput(table,"u_marketvalue","");
                                            page.statusbar.showError("Invalid Tax Declaration #.");	
                                            return false;
                                        }
                                }else{
                                            setTableInput(table,"u_tdno","");
                                            setTableInput(table,"u_trxcode","");
                                            setTableInput(table,"u_pin","");
                                            setTableInput(table,"u_location","");
                                            setTableInput(table,"u_kind","");
                                            setTableInput(table,"u_assvalue","");
                                            setTableInput(table,"u_marketvalue","");
                                    page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
                                    return false;
                                }
                            break;
                        }
                break;
                        
                default :
                    switch(column){
                        
                        case "u_tin":
                                var result = page.executeFormattedQuery("select u_ownername from u_rpfaasmds where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getInput(column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_declaredowner",result.childNodes.item(0).getAttribute("u_ownername"));
                                            }else{
                                                setInput("u_declaredowner","");
                                                setInput("u_tin","");
                                                page.statusbar.showError("Invalid Tin #.");	
                                                return false;
                                            }
                                    } else {
                                        setInput("u_declaredowner","");
                                        setInput("u_tin","");
                                        page.statusbar.showError("Error checking record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                        break;
                        case "u_declaredowner":
                                var result = page.executeFormattedQuery("select docno from u_rpfaasmds where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownername='"+getInput(column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_tin",result.childNodes.item(0).getAttribute("docno"));
                                            }
                                    } 
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
	return true;
}

function onElementChangeGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
        switch (table) {
                case "T1":
//                    if (row==0) {
//                        if (isTableInputChecked(table,column)) {
//                            checkedTableInput(table,column,-1);
//                        } else {
//                            uncheckedTableInput(table,column,-1);
//                        }
//                    } else {
//                        
//                    }
//                    
                    computeTotalMarketAssessedValueGPSRPTAS();
                break;
        }
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
        switch (Id) {
            case "df_u_docnoT1":
                       if(getInput("searchby")=="L"){
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_varpno,'Land' as kind, u_pin, u_ownername from u_rpfaas1 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ID No.`ARP No.`Kind`PIN`Declared Owner")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`22`15`15`35")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 	
                       }else if(getInput("searchby")=="B"){
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_varpno,'Building' as kind, u_pin, u_ownername from u_rpfaas2 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ID No.`ARP No.`Kind`PIN`Declared Owner")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`22`15`15`35")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 	
                       }else{
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_varpno,'Machinery' as kind, u_pin, u_ownername from u_rpfaas3 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ID No.`ARP No.`Kind`PIN`Declared Owner")); 			
                            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`22`15`15`35")); 			
                            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 	
                       }
                    break;
            case "df_u_tdnoT1":
                        if(getInput("searchby")=="L"){
                                params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,'Land' as kind, u_pin, u_ownername from u_rpfaas1 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                                params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.`Kind`PIN`Declared Owner")); 			
                                params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
                                params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                                break;
                        }else if(getInput("searchby")=="B"){
                                params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,'Building' as kind, u_pin, u_ownername from u_rpfaas2 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                                params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.Kind`PIN`Declared Owner")); 			
                                params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
                                params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                                break;
                        }else{
                                params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,'Machinery' as kind, u_pin, u_ownername from u_rpfaas3 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                                params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.`Kind`PIN`Declared Owner")); 			
                                params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
                                params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                                break;
                        }
            case "df_u_tin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select docno,u_ownername from u_rpfaasmds" ),true); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TIN`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
                        break;
            case "df_u_declaredowner":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_ownername,docno from u_rpfaasmds" ),true); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Declared Owner`TIN")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
                        break;
        }
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
        switch (table) {
		case "T1":
			    if (isTableInputEmpty(table,"u_tdno")) return false;
                            if (isTableInputNegative(table,"u_pin")) return false;
            }   
        return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
        switch (table) {
            case "T1": computeTotalMarketAssessedValueGPSRPTAS(); break;
	}	
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
        switch (table) {
		case "T1":
                            if (isTableInputEmpty(table,"u_tdno")) return false;
                            if (isTableInputNegative(table,"u_pin")) return false;
            }   
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
    switch (table) {
            case "T1": computeTotalMarketAssessedValueGPSRPTAS(); break;
    }	
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
    switch (table) {
            case "T1": computeTotalMarketAssessedValueGPSRPTAS(); break;
    }	
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

function computeTotalMarketAssessedValueGPSRPTAS() {
	var rc = getTableRowCount("T1"),marketvalue=0,assvalue=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                        if (getTableInputNumeric("T1","u_selected",i)=="1") {
                            assvalue+= getTableInputNumeric("T1","u_assvalue",i);
                            marketvalue+= getTableInputNumeric("T1","u_marketvalue",i);
                        }
		}
	}	
	setInputAmount("u_totalassvalue",assvalue);
	setInputAmount("u_totalmarketvalue",marketvalue);
}
function openupdpays() {
	OpenPopup(1024,600,"./udo.php?&objectcode=u_rpupdpays&sf_keys="+getInput("code")+"&formAction=e","UpdPays");	
}
function OpenLnkBtnu_TIN() {
	OpenPopup(1024,600,"./udo.php?&objectcode=u_rpfaasmds&sf_keys="+getInput("u_tin")+"&formAction=e","UpdPays");	
}
