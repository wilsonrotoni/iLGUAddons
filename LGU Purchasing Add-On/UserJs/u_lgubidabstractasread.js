// page events
page.events.add.load('onPageLoadGPSLGUPurchasing');
//page.events.add.resize('onPageResizeGPSLGUPurchasing');
page.events.add.submit('onPageSubmitGPSLGUPurchasing');
page.events.add.submitreturn('onPageSubmitReturnGPSLGUPurchasing');
//page.events.add.cfl('onCFLGPSLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUPurchasing');
page.elements.events.add.validate('onElementValidateGPSLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUPurchasing');
//page.elements.events.add.changing('onElementChangingGPSLGUPurchasing');
page.elements.events.add.change('onElementChangeGPSLGUPurchasing');
//page.elements.events.add.click('onElementClickGPSLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLGPSLGUPurchasing');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUPurchasing');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUPurchasing');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowGPSLGUPurchasing');

function onPageLoadGPSLGUPurchasing() {
    if (getVar("formSubmitAction")=="a") {
        if (window.opener.getVar("objectcode")=="u_lgubiddocinfra") {
            setInput("u_bidtype","Infra");
        } else if (window.opener.getVar("objectcode")=="u_lgubiddocgoods") {
            setInput("u_bidtype","Goods");
        }
        setInput("u_bidno",window.opener.getInput("docno"));
        
    }
}

function onPageResizeGPSLGUPurchasing(width,height) {
}

function onPageSubmitGPSLGUPurchasing(action) {
    if (action=="a" || action=="sc") {
        if (isInputEmpty("u_bidno")) return false;
        if (isInputEmpty("u_bidtype")) return false;
        if (isInputEmpty("u_bidder")) return false;
        if (isInputNegative("u_bidamount")) return false;
    }
	return true;
}

function onPageSubmitReturnGPSLGUPurchasing(action,success,error) {
        try {
		window.opener.setKey("keys",window.opener.getInput("docno"));
		window.opener.formEdit();
                window.close();
	} catch(TheError) {
	}
	//if (success) window.close();
}

function onCFLGPSLGUPurchasing(Id) {
	return true;
}

function onCFLGetParamsGPSLGUPurchasing(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUPurchasing() {
}

function onElementFocusGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUPurchasing(element,event,column,table,row) {
}

function onElementValidateGPSLGUPurchasing(element, column, table, row) {
    switch (table) {
        default:
            switch (column) {
                case "u_bidsecamount":
                case "u_reqbidsecamount":
                    if(getInputNumeric("u_bidsecamount") >= getInputNumeric("u_reqbidsecamount")) setInput("u_issufficient","Sufficient");
                    else setInput("u_issufficient","Insufficient");
                break;
                case "u_bidder":
//                    if (getInput(column) != "") {
//                        result = page.executeFormattedQuery("select suppno, suppname from suppliers where suppno = '" + getInput(column) + "'");
//                        if (result.getAttribute("result") != "-1") {
//                            if (parseInt(result.getAttribute("result")) > 0) {
//                                setInput("u_bidder", result.childNodes.item(0).getAttribute("suppname"));
//                            } else {
//                                setInput("u_bidder", "");
//                                page.statusbar.showError("Invalid Supplier.");
//                                return false;
//                            }
//                        } else {
//                            setInput("u_bidder", "");
//                            page.statusbar.showError("Error retrieving supplier record. Try Again, if problem persists, check the connection.");
//                            return false;
//                        }
//                    } else {
//                        setInput("u_bidder", "");
//                    }
//                    break;
            }
            break;
    }
    return true;
}

function onElementGetValidateParamsGPSLGUPurchasing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUPurchasing(element,column,table,row) {
        
        switch (table) {
        default:
            switch (column) {
                case "u_bidsec":
                    if (getInput(column) != "") {
                        result = page.executeFormattedQuery("select u_percentage from u_lguphilgepsbidsec where code = '" + addslashes(getInput(column)) + "' ");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result")) > 0) {
                                var perc = result.childNodes.item(0).getAttribute("u_percentage");
                                setInputAmount("u_reqbidsecamount", (window.opener.getInputNumeric("u_abc") * (perc / 100)), true);
                            } 
                        } else {
                            setInputAmount("u_reqbidsecamount", 0, true);
                            page.statusbar.showError("Error retrieving supplier record. Try Again, if problem persists, check the connection.");
                            return false;
                        }
                    } else {
                        setInput("u_bidsec", "");
                    }
                    break;
            }
            break;
    }
    return true;
}

function onElementClickGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUPurchasing(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUPurchasing(id,params) {
	switch (id) {	
		  case "df_u_bidder":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppname, suppno from suppliers")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Supplier Name`Supplier Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;  
	}
	return params;
}

function onTableResetRowGPSLGUPurchasing(table) {
}

function onTableBeforeInsertRowGPSLGUPurchasing(table) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeUpdateRowGPSLGUPurchasing(table,row) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeDeleteRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeSelectRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowGPSLGUPurchasing(table,row) {
}

function u_CopyTemplateGPSLGUPurchasing(){
     OpenPopup(1040,390,"./udp.php?&objectcode=u_copyfrombiddocinfra","Copy From Biddocs Infra");
}

function addslashes( str ) {  
    // Escapes single quote, double quotes and backslash characters in a string with backslashes    
    //   
    // version: 810.114  
    // discuss at: http://phpjs.org/functions/addslashes  
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)  
    // +   improved by: Ates Goral (http://magnetiq.com)  
    // +   improved by: marrtins  
    // +   improved by: Nate  
    // +   improved by: Onno Marsman  
    // *     example 1: addslashes("kevin's birthday");  
    // *     returns 1: 'kevin\'s birthday'  
   
    return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");  
}