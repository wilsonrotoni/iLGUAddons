// page events
//page.events.add.load('onPageLoadLGUPurchasing');
//page.events.add.resize('onPageResizeLGUPurchasing');
//page.events.add.submit('onPageSubmitLGUPurchasing');
//page.events.add.cfl('onCFLLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownLGUPurchasing');
page.elements.events.add.validate('onElementValidateLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsLGUPurchasing');
//page.elements.events.add.changing('onElementChangingLGUPurchasing');
page.elements.events.add.change('onElementChangeLGUPurchasing');
//page.elements.events.add.click('onElementClickLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLLGUPurchasing');
page.elements.events.add.cflgetparams('onElementCFLGetParamsLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowLGUPurchasing');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowLGUPurchasing');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowLGUPurchasing');

function onPageLoadLGUPurchasing() {
}

function onPageResizeLGUPurchasing(width,height) {
}

function onPageSubmitLGUPurchasing(action) {
	return true;
}

function onCFLLGUPurchasing(Id) {
	return true;
}

function onCFLGetParamsLGUPurchasing(Id,params) {
	return params;
}

function onTaskBarLoadLGUPurchasing() {
}

function onElementFocusLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementKeyDownLGUPurchasing(element,event,column,table,row) {
}

function onElementValidateLGUPurchasing(element,column,table,row) {
        switch (table) {
            case "T1":
                switch (column) {
                    
                    case "u_profitcenter":
                    case "u_profitcentername":
                            if (getTableInput(table,column)!="") {
                                 if (column=="u_profitcenter") result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcenter = '"+getTableInput(table,column)+"'");	
                                 else result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcentername = '"+getTableInput(table,column)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                    setTableInput(table,"u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
                                                    setTableInput(table,"u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
                                            } else {
                                                    setTableInput(table,"u_profitcenter","");
                                                    setTableInput(table,"u_profitcentername","");
                                                    page.statusbar.showError("Invalid Profit Center.");	
                                                    return false;
                                            }
                                    } else {
                                            setInput(table,"u_profitcenter","");
                                            setInput(table,"u_profitcentername","");
                                            page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
                                            return false;
                                    }
                            } else {
                                    setInput(table,"u_profitcenter","");
                                    setInput(table,"u_profitcentername","");
                            }
                        break;
                }
                
            break;
        }
	return true;
}

function onElementGetValidateParamsLGUPurchasing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementChangeLGUPurchasing(element,column,table,row) {
      switch (table) {
          default:
			switch (column) {
                            case "u_user":
                                setInput("code",getInput("u_user"));
                                setInput("name",getInput("u_user"));
                               
                                var code = page.executeFormattedSearch("select code from u_pcusers where code='"+ getInput("u_user")+"'");
                                    if (code!="") {
                                            setKey("keys",getInput("u_user"));
                                            formEdit();
                                            return false;
                                    }
                            break;
                        }
      }
	return true;
}

function onElementClickLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementCFLLGUPurchasing(element) {
	return true;
}

function onElementCFLGetParamsLGUPurchasing(Id,params) {
    
    switch (Id) {
               
                case "df_u_profitcenternameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcentername, profitcenter from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_profitcenterT1":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter,profitcentername  from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
               
            }
    
	return params;
    
}

function onTableResetRowLGUPurchasing(table) {
}

function onTableBeforeInsertRowLGUPurchasing(table) {
	return true;
}

function onTableAfterInsertRowLGUPurchasing(table,row) {
}

function onTableBeforeUpdateRowLGUPurchasing(table,row) {
	return true;
}

function onTableAfterUpdateRowLGUPurchasing(table,row) {
}

function onTableBeforeDeleteRowLGUPurchasing(table,row) {
	return true;
}

function onTableDeleteRowLGUPurchasing(table,row) {
}

function onTableBeforeSelectRowLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowLGUPurchasing(table,row) {
}

