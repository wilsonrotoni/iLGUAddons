// page events
page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
page.events.add.submit('onPageSubmitGPSBPLS');
page.events.add.submitreturn('onPageSubmitReturnGPSBPLS');
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
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
//page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
//page.tables.events.add.select('onTableSelectRowGPSBPLS');

function onPageLoadGPSBPLS() {
        if (getVar("formSubmitAction")=="a") {
		try {
                    if (window.opener.getVar("objectcode")=="u_bpltaxbilllist") {
                        setInput("u_year",getPrivate("year"));
			setInput("u_acctno",window.opener.getTableInput("T1","u_appno",window.opener.getTableSelectedRow("T1")),true);
                        focusInput("u_year");
                    }
		} catch ( e ) {
		}
	}
}

function onPageResizeGPSBPLS(width,height) {
}

function onPageSubmitGPSBPLS(action) {
        if (action=="a" || action=="sc") {
            if (isInputNegative("u_year")) return false;
            if (isInputEmpty("u_acctno")) return false;
            if (getInput("u_feecode") == "0001") {
                if (isInputEmpty("u_businessline")) return false;
            }
            if (isInputNegative("u_amount")) return false;
            if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
        }
	return true;
}
function onPageSubmitReturnGPSBPLS(action,sucess,error) {
	if (sucess) {
		try {
//                                window.opener.resetTableRow("T1");
//                                window.opener.resetTableRow("T3");
//				window.opener.setInput("u_tin",window.opener.getInput("u_tin"),true);
				window.close();
		} catch (theError) {
		}
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
            default:
			switch (column) {
                            case "u_feecode":
                            case "u_feedesc":
                                if (getInput(column)!="") {
                                        if (column=="u_feecode") var result = page.executeFormattedQuery("select a.code, a.name,a.u_amount from u_lgufees a where a.code='"+getInput(column)+"'");	
                                        else var result = page.executeFormattedQuery("select a.code, a.name,a.u_amount  from u_lgufees a  name like '"+getInput(column)+"%'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_feecode",result.childNodes.item(0).getAttribute("code"));
                                                        setInput("u_feedesc",result.childNodes.item(0).getAttribute("name"));
                                                        setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
                                                        setInput("code",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                                                        setInput("name",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                                                } else {
                                                        setInput("u_feecode","");
                                                        setInput("u_feedesc","");
                                                        page.statusbar.showError("Invalid Fee");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_feecode","");
                                                setInput("u_feedesc","");
                                                page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_feecode","");
                                        setInput("u_feedesc","");
                                        setTableInputAmount(table,"u_amount",0);
                                }						
                                break;
                            case "u_acctno":
                                if(isInputNegative("u_year")) return false;
                                var result = page.executeFormattedQuery("select name,u_tradename, u_firstname,u_lastname,u_middlename,u_btelno,u_bbldgno,u_bbldgname,u_bunitno,u_bstreet,u_bbrgy,u_bfloorno,u_bblock,u_bvillage from u_bplmds where code='"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("code",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                                                setInput("name",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                                            }else{
                                                page.statusbar.showError("Invalid Account No.");	
                                                return false;
                                            }
                                    }else{
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
                                    }else{
                                        setInput("u_orrefno","");
                                        page.statusbar.showError("Error checking or record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
                            case "u_year":
                                setInput("code",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                                setInput("name",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                                break;
                           
                        }
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
                 switch (column) {
                     case "u_businessline":
                            setInput("code",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
                            setInput("name",getInput("u_acctno") + "_" + getInput("u_feecode")+ "_" + getInput("u_year")+ "_" + getInput("u_businessline"));
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
            case "df_u_acctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name,concat(u_lastname,', ',u_firstname,' ',u_middlename) as Ownername  from u_bplmds")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Account No`Business Name`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`40")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
            case "df_u_feecode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name  from u_lgufees a")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
            case "df_u_feedesc":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code  from u_lgufees a")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
            case "df_u_orrefno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select docno,u_date,u_custname,u_paidamount from u_lgupos where u_profitcenter IN ('BPL','') and u_status not in ('CN','D')" )); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Reciept No`Date`Customer Name`Amount Paid")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`35`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                        break;
        }
	return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table) {
        switch (table) {
		case "T1":
                           
            }   
        return true;
}

function onTableAfterInsertRowGPSBPLS(table,row) {
}

function onTableBeforeUpdateRowGPSBPLS(table,row) {
        switch (table) {
		case "T1":
                         
            }   
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
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
}

