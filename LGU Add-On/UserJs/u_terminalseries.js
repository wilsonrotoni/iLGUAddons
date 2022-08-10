// page events
page.events.add.load('onPageLoadGPSLGU');
//page.events.add.resize('onPageResizeGPSLGU');
page.events.add.submit('onPageSubmitGPSLGU');
page.events.add.submitreturn('onPageSubmitReturnGPSLGU');
//page.events.add.cfl('onCFLGPSLGU');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGU');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGU');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGU');
//page.elements.events.add.keydown('onElementKeyDownGPSLGU');
page.elements.events.add.validate('onElementValidateGPSLGU');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGU');
//page.elements.events.add.changing('onElementChangingGPSLGU');
page.elements.events.add.change('onElementChangeGPSLGU');
//page.elements.events.add.click('onElementClickGPSLGU');
//page.elements.events.add.cfl('onElementCFLGPSLGU');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGU');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGU');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGU');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGU');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGU');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGU');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGU');
//page.tables.events.add.delete('onTableDeleteRowGPSLGU');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGU');
//page.tables.events.add.select('onTableSelectRowGPSLGU');

function onPageLoadGPSLGU() {
    if (getVar("formSubmitAction")=="a") {
		try {
                    //alert(window.opener.getInput("u_tin"));
			setInput("u_cashier",window.opener.getInput("u_userid"),true);
			setInput("u_registerid",window.opener.getInput("code"),true);
//			
//			setInput("u_pin",window.opener.getTableInput("T1","u_pinno",window.opener.getTableSelectedRow("T1")),true);
//			setInput("u_tdno",window.opener.getTableInput("T1","u_tdno",window.opener.getTableSelectedRow("T1")),true);
		} catch ( e ) {
		}
	}
}

function onPageResizeGPSLGU(width,height) {
}

function onPageSubmitGPSLGU(action) {
        if (action=="a" || action=="sc") {
//            if (isInputEmpty("u_dateissued")) return false;
            if (isInputEmpty("u_cashier")) return false;
            if (isInputNegative("u_startno")) return false;
            if (isInputEmpty("u_registerid")) return false;
//            if (isInputNegative("u_receiptlineid")) return false;
        }
	return true;
}
function onPageSubmitReturnGPSLGU(action,sucess,error) {
        
	if (sucess) {
		try {
			//if (window.opener.getVar("objectcode")=="u_motorregserials") {
                                window.opener.resetTableRow("T101");
				window.opener.setInput("code",window.opener.getInput("code"),true);
				window.close();
			//}
		} catch (theError) {
		}
	}
	return true;
}

function onCFLGPSLGU(Id) {
	return true;
}

function onCFLGetParamsGPSLGU(id,params) {
        
	return params;
}

function onTaskBarLoadGPSLGU() {
}

function onElementFocusGPSLGU(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGU(element,event,column,table,row) {
}

function onElementValidateGPSLGU(element,column,table,row) {
        switch (table) {
            default:
                switch (column) {
                    case "u_cashier":
                        if(getInput(column)!=""){
                            var result = page.executeFormattedQuery("select u_issuedto,u_receiptfrm,u_receiptto from u_lgureceiptitems where u_issuedto='"+getInput(column)+"' and u_receiptfrm = '"+getInput("u_startno")+"'");
                                if (result.getAttribute("result")!= "-1") {
                                    if (parseInt(result.getAttribute("result"))>0) {
                                        setInput("u_cashier",result.childNodes.item(0).getAttribute("userid"));
                                    } else {
                                        setInput("u_startno",0);
                                        setInput("u_nextno",0);
                                        setInput("u_lastno",0);
                                        setInput("u_docseriesname","");
                                    }
                                } else {
                                    setInput("u_startno",0);
                                    setInput("u_nextno",0);
                                    setInput("u_lastno",0);
                                    setInput("u_docseriesname","");
                                }
                        } else {
                                    setInput("u_startno",0);
                                    setInput("u_nextno",0);
                                    setInput("u_lastno",0);
                                    setInput("u_docseriesname","");
                        }
                    break;
                    
                    case "u_startno":
                        if(getInput(column)!=""){
                          var result = page.executeFormattedQuery("select * from u_lgureceiptitems where u_receiptfrm='"+getInput(column)+"' and u_form like '%"+getInput("u_docseriesname")+"%' and company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"' and u_issuedto='"+getInput("u_cashier")+"' ");
//                            else var result = page.executeFormattedQuery("select * from u_lgureceiptitems where u_receiptto='"+getInput(column)+"' and u_form like '%"+getInput("u_form")+"%'");
                            
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
//                                    setInput("docseries",-1);
//                                    setInput("docno",result.childNodes.item(0).getAttribute("u_issuedocno") + '-' + getInput("u_registerid"));
                                    setInput("u_issuedocno",result.childNodes.item(0).getAttribute("u_issuedocno"));
                                    setInput("u_docseries",result.childNodes.item(0).getAttribute("u_refno"));
                                    setInput("u_cashier",result.childNodes.item(0).getAttribute("u_issuedto"));
                                    setInput("u_startno",result.childNodes.item(0).getAttribute("u_receiptfrm"));
                                    setInput("u_lastno",result.childNodes.item(0).getAttribute("u_receiptto"));
                                    setInput("u_nextno",(result.childNodes.item(0).getAttribute("u_receiptto") - result.childNodes.item(0).getAttribute("u_available")) + 1  );
                                    setInput("u_docseriesname",result.childNodes.item(0).getAttribute("u_form"));
                                } else {
//                                    setInput("docseries",0);
//                                    setInput("docno","");
                                    setInput("u_docseries","");
                                    setInput("u_startno","");
                                    setInput("u_lastno","");
                                    setInput("u_nextno","");
                                    setInput("u_docseriesname","");
                                    page.statusbar.showError("Invalid Receipt");	
                                    return false;
                                }
                            } else {
//                                    setInput("docseries",0);
//                                    setInput("docno","");
                                    setInput("u_docseries","");
                                    setInput("u_startno","");
                                    setInput("u_lastno","");
                                    setInput("u_nextno","");
                                    setInput("u_docseriesname","");
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

function onElementGetValidateParamsGPSLGU(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGU(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGU(element,column,table,row) {
        switch (table) {
            default:
                switch (column) {
                     case "u_cashier":
                            
                         break;
                }
        }
	return true;
}

function onElementClickGPSLGU(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGU(element) {
	return true;
}

function onElementCFLGetParamsGPSLGU(element,params) {
        var params = new Array();
            switch (element) {
                case "df_u_startno":
                    if(isInputEmpty("u_cashier")) return false;
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_receiptfrm,u_receiptto,u_noofreceipt,u_form from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_cashier")+"'")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("From`To`No`Series")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`20`10`30")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 	
                    break;
        }
	return params;
}

function onTableResetRowGPSLGU(table) {
}

function onTableBeforeInsertRowGPSLGU(table) {
	return true;
}

function onTableAfterInsertRowGPSLGU(table,row) {
}

function onTableBeforeUpdateRowGPSLGU(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGU(table,row) {
}

function onTableBeforeDeleteRowGPSLGU(table,row) {
	return true;
}

function onTableDeleteRowGPSLGU(table,row) {
}

function onTableBeforeSelectRowGPSLGU(table,row) {
	return true;
}

function onTableSelectRowGPSLGU(table,row) {
}
