// page events
page.events.add.load('onPageLoadGPSLGUReceipts');
//page.events.add.resize('onPageResizeGPSLGUReceipts');
page.events.add.submit('onPageSubmitGPSLGUReceipts');
page.events.add.submitreturn('onPageSubmitReturnGPSLGUReceipts');
//page.events.add.cfl('onCFLGPSLGUReceipts');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUReceipts');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUReceipts');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUReceipts');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUReceipts');
page.elements.events.add.validate('onElementValidateGPSLGUReceipts');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUReceipts');
//page.elements.events.add.changing('onElementChangingGPSLGUReceipts');
page.elements.events.add.change('onElementChangeGPSLGUReceipts');
//page.elements.events.add.click('onElementClickGPSLGUReceipts');
//page.elements.events.add.cfl('onElementCFLGPSLGUReceipts');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUReceipts');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUReceipts');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUReceipts');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUReceipts');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUReceipts');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUReceipts');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUReceipts');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUReceipts');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUReceipts');
//page.tables.events.add.select('onTableSelectRowGPSLGUReceipts');

function onPageLoadGPSLGUReceipts() {
    if (getVar("formSubmitAction")=="a") {
		try {
                    //alert(window.opener.getInput("u_tin"));
			setInput("u_cashier",window.opener.getInput("code"),true);
//			
//			setInput("u_pin",window.opener.getTableInput("T1","u_pinno",window.opener.getTableSelectedRow("T1")),true);
//			setInput("u_tdno",window.opener.getTableInput("T1","u_tdno",window.opener.getTableSelectedRow("T1")),true);
		} catch ( e ) {
		}
	}
}

function onPageResizeGPSLGUReceipts(width,height) {
}

function onPageSubmitGPSLGUReceipts(action) {
        if (action=="a" || action=="sc") {
            if (isInputEmpty("u_datereturn")) return false;
            if (isInputEmpty("u_cashier")) return false;
            if (isInputEmpty("u_receiptlineid")) return false;
            if (isInputNegative("u_receiptlineid")) return false;
        }
	return true;
}
function onPageSubmitReturnGPSLGUReceipts(action,sucess,error) {
        
	if (sucess) {
		try {
			//if (window.opener.getVar("objectcode")=="u_motorregserials") {
                                window.opener.resetTableRow("T102");
				window.opener.setInput("code",window.opener.getInput("code"),true);
				window.close();
			//}
		} catch (theError) {
		}
	}
	return true;
}

function onCFLGPSLGUReceipts(Id) {
	return true;
}

function onCFLGetParamsGPSLGUReceipts(id,params) {
        
	return params;
}

function onTaskBarLoadGPSLGUReceipts() {
}

function onElementFocusGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUReceipts(element,event,column,table,row) {
}

function onElementValidateGPSLGUReceipts(element,column,table,row) {
        switch (table) {
            default:
                switch (column) {
                    case "code":
                            var result = page.executeFormattedQuery("select username from users where userid='"+getInput(column)+"'");
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                    setInput("name",result.childNodes.item(0).getAttribute("username"));
                                } else {
                                    setInput("name","");
                                    setInput("code","");
                                    page.statusbar.showError("Invalid User");	
                                    return false;
                                }
                            } else {
                                    setInput("name","");
                                    setInput("code","");
                                    page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                    return false;
                            }
                    break;
                    
                    case "u_receiptfrm":
                    case "u_receiptto":
                        if(getInput(column)!=""){
                            if(column=="u_receiptfrm") var result = page.executeFormattedQuery("select * from u_lgureceiptitems where u_receiptfrm='"+getInput(column)+"' and u_form like '%"+getInput("u_form")+"%' and u_available > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"'");
                            else var result = page.executeFormattedQuery("select * from u_lgureceiptitems where u_receiptto='"+getInput(column)+"' and u_form like '%"+getInput("u_form")+"%' and u_available > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"'");
                            
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                    setInput("u_docseries",result.childNodes.item(0).getAttribute("u_refno"));
                                    setInput("u_receiptlineid",result.childNodes.item(0).getAttribute("lineid"));
                                    setInput("u_receiptfrm",result.childNodes.item(0).getAttribute("u_receiptfrm"));
                                    setInput("u_receiptto",result.childNodes.item(0).getAttribute("u_receiptto"));
                                    setInput("u_form",result.childNodes.item(0).getAttribute("u_form"));
                                    setInput("u_quantity",result.childNodes.item(0).getAttribute("u_available"));
                                } else {
                                    setInput("u_docseries","");
                                    setInput("u_receiptlineid","");
                                    setInput("u_receiptfrm","");
                                    setInput("u_receiptto","");
                                    setInput("u_form","");
                                    setInput("u_quantity","");
                                    page.statusbar.showError("Invalid Receipt");	
                                    return false;
                                }
                            } else {
                                    setInput("u_docseries","");
                                    setInput("u_receiptlineid","");
                                    setInput("u_receiptfrm","");
                                    setInput("u_receiptto","");
                                    setInput("u_form","");
                                    setInput("u_quantity","");
                                    page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                    return false;
                            }
                        }
                        
                    break;
                    case "u_receiptlineid":
                        if (getInput(column)!="") {
                            var result = page.executeFormattedQuery("select * from u_lgureceiptitems where lineid='"+getInput(column)+"' and u_available > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"' ");
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                    
                                    setInput("u_docseries",result.childNodes.item(0).getAttribute("u_refno"));
                                    setInput("u_receiptfrm",result.childNodes.item(0).getAttribute("u_receiptfrm"));
                                    setInput("u_receiptto",result.childNodes.item(0).getAttribute("u_receiptto"));
                                    setInput("u_form",result.childNodes.item(0).getAttribute("u_form"));
                                    setInput("u_quantity",result.childNodes.item(0).getAttribute("u_available"));
                                } else {
                                    setInput("u_docseries","");
                                    setInput("u_receiptlineid","");
                                    setInput("u_receiptfrm","");
                                    setInput("u_receiptto","");
                                    setInput("u_form","");
                                    setInput("u_quantity","");
                                    page.statusbar.showError("Invalid Receipt ID");	
                                    return false;
                                }
                            } else {
                                    setInput("u_docseries","");
                                    setInput("u_receiptlineid","");
                                    setInput("u_receiptfrm","");
                                    setInput("u_receiptto","");
                                    setInput("u_form","");
                                    setInput("u_quantity","");
                                    page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                    return false;
                            }
                        }
                    break;
                }
                break;
        }
	return true;
}

function onElementGetValidateParamsGPSLGUReceipts(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUReceipts(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUReceipts(element,params) {
        var params = new Array();
            switch (element) {
                case "df_u_receiptfrm":
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_receiptfrm,u_receiptto,u_available from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"'")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("From`To`Available")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`20`10")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
                    break;
                case "df_u_receiptto":
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_receiptto,u_receiptfrm,u_available from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"'")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("To`From`Available")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`20`10")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
                    break;
                case "df_u_receiptlineid":
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select lineid,u_receiptfrm,u_receiptto,u_form,u_available from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"'")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Receipt ID`From`To`Form Type`Available")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`20`20`10`10")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 	
                    break;
        }
	return params;
}

function onTableResetRowGPSLGUReceipts(table) {
}

function onTableBeforeInsertRowGPSLGUReceipts(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUReceipts(table,row) {
}

function onTableBeforeUpdateRowGPSLGUReceipts(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUReceipts(table,row) {
}

function onTableBeforeDeleteRowGPSLGUReceipts(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUReceipts(table,row) {
}

function onTableBeforeSelectRowGPSLGUReceipts(table,row) {
	return true;
}

function onTableSelectRowGPSLGUReceipts(table,row) {
}
