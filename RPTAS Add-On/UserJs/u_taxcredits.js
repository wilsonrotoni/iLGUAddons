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
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
//page.elements.events.add.change('onElementChangeGPSRPTAS');
//page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
//page.tables.events.add.select('onTableSelectRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
        if (getVar("formSubmitAction")=="a") {
		try {
                    if (window.opener.getVar("objectcode")=="u_billsearchproperty") {
                        setInput("u_year",getPrivate("year"));
			setInput("u_tdno",window.opener.getTableInput("T1","u_varpno",window.opener.getTableSelectedRow("T1")),true);
			setInput("u_docno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
                        focusInput("u_year");
                    }
		} catch ( e ) {
		}
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
        if (action=="a" || action=="sc") {
            if (isInputNegative("u_year")) return false;
            if (isInputEmpty("u_tin")) return false;
            if (isInputEmpty("u_tdno")) return false;
            if (isInputNegative("u_taxdue")) return false;
            if (isInputNegative("u_sef")) return false;
            if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
        }
	return true;
}
function onPageSubmitReturnGPSRPTAS(action,sucess,error) {
	if (sucess) {
		try {
                                window.opener.resetTableRow("T1");
                                window.opener.resetTableRow("T3");
				window.opener.setInput("u_tin",window.opener.getInput("u_tin"),true);
				window.close();
		} catch (theError) {
		}
	}
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
            default:
			switch (column) {
                            case "u_taxdue":
                            case "u_sef":
                                          setInput("u_sef",formatNumericAmount(getInput("u_taxdue")));
                                      break;
                            case "u_penalty":
                            case "u_sefpenalty":
                                          setInput("u_sefpenalty",formatNumericAmount(getInput("u_penalty")));
                                      break;
                            case "u_tdno":
                                if(isInputNegative("u_year")) return false;
                                var result = page.executeFormattedQuery("select docno,u_ownertin,u_assvalue from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' union all select docno,u_ownertin,u_assvalue from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' union all select docno,u_ownertin,u_assvalue from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_assvalue",formatNumericAmount(result.childNodes.item(0).getAttribute("u_assvalue")));
                                                setInput("u_docno",result.childNodes.item(0).getAttribute("docno"));
                                                setInput("u_tin",result.childNodes.item(0).getAttribute("u_ownertin"));
                                                setInput("code",getInput(column) + "_" + getInput("u_year"));
                                                setInput("name",getInput(column) + "_" + getInput("u_year"));
                                            }else{
                                                setInput("u_assvalue",formatNumericAmount(0));
                                                setInput("u_docno","");
                                                setInput("u_tdno","");
                                                setInput("u_tin","");
                                                page.statusbar.showError("Invalid Tax Declaration No.");	
                                                return false;
                                            }
                                    }else{
                                        setInput("u_assvalue",formatNumericAmount(0));
                                        setInput("u_docno","");
                                        setInput("u_tdno","");
                                        setInput("u_tin","");
                                        page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_docno":
                                if(isInputNegative("u_year")) return false;
                                var result = page.executeFormattedQuery("select docno,u_ownertin,u_assvalue,u_varpno from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' union all select docno,u_ownertin,u_assvalue,u_varpno from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' union all select docno,u_ownertin,u_assvalue,u_varpno from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_assvalue",formatNumericAmount(result.childNodes.item(0).getAttribute("u_assvalue")));
                                                setInput("u_docno",result.childNodes.item(0).getAttribute("docno"));
                                                setInput("u_docno",result.childNodes.item(0).getAttribute("docno"));
                                                setInput("u_tin",result.childNodes.item(0).getAttribute("u_ownertin"));
                                                setInput("code",getInput(column) + "_" + getInput("u_year"));
                                                setInput("name",getInput(column) + "_" + getInput("u_year"));
                                            }else{
                                                setInput("u_assvalue",formatNumericAmount(0));
                                                setInput("u_docno","");
                                                setInput("u_tdno","");
                                                setInput("u_tin","");
                                                page.statusbar.showError("Invalid Tax Declaration No.");	
                                                return false;
                                            }
                                    }else{
                                        setInput("u_assvalue",formatNumericAmount(0));
                                        setInput("u_docno","");
                                        setInput("u_tdno","");
                                        setInput("u_tin","");
                                        page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_tdno":
                                if(isInputNegative("u_year")) return false;
                                var result = page.executeFormattedQuery("select docno,u_ownertin,u_assvalue from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' union all select docno,u_ownertin,u_assvalue from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' union all select docno,u_ownertin,u_assvalue from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getTableInput(table,column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_assvalue",formatNumericAmount(result.childNodes.item(0).getAttribute("u_assvalue")));
                                                setInput("u_docno",result.childNodes.item(0).getAttribute("docno"));
                                                setInput("u_tin",result.childNodes.item(0).getAttribute("u_ownertin"));
                                                setInput("code",getInput(column) + "_" + getInput("u_year"));
                                                setInput("name",getInput(column) + "_" + getInput("u_year"));
                                            }else{
                                                setInput("u_assvalue",formatNumericAmount(0));
                                                setInput("u_docno","");
                                                setInput("u_tdno","");
                                                setInput("u_tin","");
                                                page.statusbar.showError("Invalid Tax Declaration No.");	
                                                return false;
                                            }
                                    }else{
                                        setInput("u_assvalue",formatNumericAmount(0));
                                        setInput("u_docno","");
                                        setInput("u_tdno","");
                                        setInput("u_tin","");
                                        page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_tin":
                                if(isInputNegative("u_year")) return false;
                                var result = page.executeFormattedQuery("select docno,u_varpno,u_assvalue from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getTableInput(table,column)+"' union all select docno,u_varpno,u_assvalue from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getTableInput(table,column)+"' union all select docno,u_varpno,u_assvalue from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getTableInput(table,column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_assvalue",formatNumericAmount(result.childNodes.item(0).getAttribute("u_assvalue")));
                                                setInput("u_docno",result.childNodes.item(0).getAttribute("docno"));
                                                setInput("u_tdno",result.childNodes.item(0).getAttribute("u_varpno"));
                                                setInput("code",result.childNodes.item(0).getAttribute("u_varpno")+ "_" + getInput("u_year"));
                                                setInput("name",result.childNodes.item(0).getAttribute("u_varpno")+ "_" + getInput("u_year"));
                                            }else{
                                                setInput("u_assvalue",formatNumericAmount(0));
                                                setInput("u_docno","");
                                                setInput("u_tdno","");
                                                setInput("u_tin","");
                                                page.statusbar.showError("Invalid Tin #.");	
                                                return false;
                                            }
                                    }else{
                                        setInput("u_assvalue",formatNumericAmount(0));
                                        setInput("u_docno","");
                                        setInput("u_tdno","");
                                        setInput("u_tin","");
                                        page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_orrefno":
                                var result = page.executeFormattedQuery("select docno from u_lgupos where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' ");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_orrefno",result.childNodes.item(0).getAttribute("docno"));
                                            }
//                                            else{
//                                                setInput("u_orrefno","");
//                                                page.statusbar.showError("Invalid Or number.");	
//                                                return false;
//                                            }
                                    }else{
                                        setInput("u_orrefno","");
                                        page.statusbar.showError("Error checking or record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_year":
                                setInput("code",getInput("u_tdno") + "_" + getInput("u_year"));
                                setInput("name",getInput("u_tdno") + "_" + getInput("u_year"));
                                break;
                           
                        }
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
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
        switch (Id) {
            case "df_u_tdno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,kind,u_pin,u_ownername from (select u_varpno,'Land' as kind, u_pin, u_ownername from u_rpfaas1 where  docstatus in('Recommended','Approved') and u_cancelled = 0  union all select u_varpno,'Building' as kind, u_pin, u_ownername from u_rpfaas2 where  docstatus in('Recommended','Approved')  and u_cancelled = 0  union all select u_varpno,'Machinery' as kind, u_pin, u_ownername from u_rpfaas3 where  docstatus in('Recommended','Approved') and u_cancelled = 0 ) as x" ),true); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.`Kind`PIN`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                        break;
            case "df_u_tin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_ownertin,u_varpno,kind,u_pin,u_ownername from (select u_ownertin,u_varpno,'Land' as kind, u_pin, u_ownername from u_rpfaas1 where  docstatus in('Recommended','Approved') and u_cancelled = 0  union all select u_ownertin,u_varpno,'Building' as kind, u_pin, u_ownername from u_rpfaas2 where  docstatus in('Recommended','Approved') and u_cancelled = 0  union all select u_ownertin,u_varpno,'Machinery' as kind, u_pin, u_ownername from u_rpfaas3 where  docstatus in('Recommended','Approved') and u_cancelled = 0 ) as x" ),true); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TIN`ARP No.`Kind`PIN`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`22`15`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
                        break;
            case "df_u_orrefno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select docno,u_date,u_custname,u_paidamount from u_lgupos where u_profitcenter = 'RP' and u_status not in ('CN','D')" )); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Reciept No`Date`Customer Name`Amount Paid")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`35`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                        break;
        }
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
        switch (table) {
		case "T1":
                           
            }   
        return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
        switch (table) {
		case "T1":
                         
            }   
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

