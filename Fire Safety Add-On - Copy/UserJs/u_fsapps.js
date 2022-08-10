// page events
page.events.add.load('onPageLoadGPSFireSafety');
//page.events.add.resize('onPageResizeGPSFireSafety');
page.events.add.submit('onPageSubmitGPSFireSafety');
//page.events.add.cfl('onCFLGPSFireSafety');
//page.events.add.cflgetparams('onCFLGetParamsGPSFireSafety');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFireSafety');

// element events
//page.elements.events.add.focus('onElementFocusGPSFireSafety');
page.elements.events.add.keydown('onElementKeyDownGPSFireSafety');
page.elements.events.add.validate('onElementValidateGPSFireSafety');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFireSafety');
//page.elements.events.add.changing('onElementChangingGPSFireSafety');
//page.elements.events.add.change('onElementChangeGPSFireSafety');
//page.elements.events.add.click('onElementClickGPSFireSafety');
//page.elements.events.add.cfl('onElementCFLGPSFireSafety');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFireSafety');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFireSafety');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFireSafety');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFireSafety');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFireSafety');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFireSafety');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFireSafety');
//page.tables.events.add.delete('onTableDeleteRowGPSFireSafety');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFireSafety');
//page.tables.events.add.select('onTableSelectRowGPSFireSafety');

function onPageLoadGPSFireSafety() {
    focusInput("u_apptype");
}

function onPageResizeGPSFireSafety(width,height) {
}

function onPageSubmitGPSFireSafety(action) {
    	if (action=="a" || action=="sc") {
                
		if (isInputEmpty("u_apptype",null,null,"",0)) return false;
		if (isInputEmpty("u_businessname",null,null,"",0)) return false;
		if (isInputEmpty("u_docdate",null,null,"",0)) return false;
		if (isInputEmpty("u_orno",null,null,"",0)) return false;
		if (isInputEmpty("u_ordate",null,null,"",0)) return false;
		if (isInputNegative("u_orfcamt")) return false;
	}
       
        
	return true;
	
}

function onCFLGPSFireSafety(Id) {
	return true;
}

function onCFLGetParamsGPSFireSafety(Id,params) {
	return params;
}

function onTaskBarLoadGPSFireSafety() {
}

function onElementFocusGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSFireSafety(element,event,column,table,row) {
        var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);

	switch (sc_press) {
//		case "ESC":
//			if (isPopupFrameOpen("popupFramePayments")) {
//				hidePopupFrame('popupFramePayments');
//				setT1FocusInputGPSPOS();
//			}
//			break;
		case "F4":
			u_AcceptPaymenGPSFireSafety();
			break;
//		case "F6":
//			u_returnGPSPOS();
//			break;
//		case "F7":
//			u_salesGPSPOS();
//			break;
//		case "F9":
//			formSubmit();
//			break;
//		default:
//			switch (table) {
//				case "T1":
//					break;
//				default:	
//					switch (column) {
//						case "u_totalamount2":
//							if (sc_press=="ENTER") {
//								setInputAmount(column,getInputNumeric(column),true);
//								focusInput(column);
//							}
//							break;
//							
//					}
//					break;
//			}
//			break;
	}
}

function onElementValidateGPSFireSafety(element,column,table,row) {
    switch (table) {
        default:
            switch (column) {
                case "u_bpno":
                   
                    if (getInput(column)!="") {
                                var result = page.executeFormattedQuery("select u_businessname,u_fireasstotal,u_apptype from u_bplapps where docno='"+getInput(column)+"'");	
                   
                                if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {    
                                                if(result.childNodes.item(0).getAttribute("u_apptype")=="NEW"){
                                                    setInput("u_apptype","New Business Permit");
                                                }else if(result.childNodes.item(0).getAttribute("u_apptype")=="RENEW"){
                                                    setInput("u_apptype","Renewal of Business Permit");
                                                }else{
                                                    setInput("u_apptype","");
                                                }
                                                setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
                                                setInputAmount("u_orfcamt",result.childNodes.item(0).getAttribute("u_fireasstotal"));
                                               
                                        }else{
                                                setInput("u_apptype","");
                                                setInput("u_businessname","");
                                                setInputAmount("u_orfcamt",0);
                                                page.statusbar.showError("Invalid Business Permit No");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_apptype","");
                                        setInput("u_businessname","");
                                        setInputAmount("u_orfcamt",0);
                                        page.statusbar.showError("Error retrieving Business Permit record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                }
                        } else {
                                setInput("u_apptype","");
                                setInput("u_businessname","");
                                setInputAmount("u_orfcamt",0);
                        }						
                    break;
                  
                
            }
            break;
            
    }
    return true;
}

function onElementGetValidateParamsGPSFireSafety(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementChangeGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementClickGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementCFLGPSFireSafety(element) {
	return true;
}

function onElementCFLGetParamsGPSFireSafety(Id,params) {
        switch (Id) {
            case "df_u_bpno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_businessname,u_year,docstatus  from u_bplapps where docstatus in ('Approved','Paid','Printing')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No`Business Name`Year`Status")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`5`5")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
            
        }
	return params;
}

function onTableResetRowGPSFireSafety(table) {
}

function onTableBeforeInsertRowGPSFireSafety(table) {
	return true;
}

function onTableAfterInsertRowGPSFireSafety(table,row) {
}

function onTableBeforeUpdateRowGPSFireSafety(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFireSafety(table,row) {
}

function onTableBeforeDeleteRowGPSFireSafety(table,row) {
	return true;
}

function onTableDeleteRowGPSFireSafety(table,row) {
}

function onTableBeforeSelectRowGPSFireSafety(table,row) {
	return true;
}

function onTableSelectRowGPSFireSafety(table,row) {
}

function u_AcceptPaymenGPSFireSafety(){
    if(!confirm("Accept payment. Continue?")) return false;
    setInput("docstatus","P");
    formSubmit();
}
