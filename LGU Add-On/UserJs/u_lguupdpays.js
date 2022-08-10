// page events
//page.events.add.load('onPageLoadGPSLGU');
//page.events.add.resize('onPageResizeGPSLGU');
page.events.add.submit('onPageSubmitGPSLGU');
//page.events.add.cfl('onCFLGPSLGU');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGU');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGU');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGU');
page.elements.events.add.keydown('onElementKeyDownGPSLGU');
//page.elements.events.add.validate('onElementValidateGPSLGU');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGU');
//page.elements.events.add.changing('onElementChangingGPSLGU');
//page.elements.events.add.change('onElementChangeGPSLGU');
//page.elements.events.add.click('onElementClickGPSLGU');
//page.elements.events.add.cfl('onElementCFLGPSLGU');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGU');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGU');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGU');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGU');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGU');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGU');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGU');
//page.tables.events.add.delete('onTableDeleteRowGPSLGU');
page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGU');
//page.tables.events.add.select('onTableSelectRowGPSLGU');

function onPageLoadGPSLGU() {
}

function onPageResizeGPSLGU(width,height) {
}

function onPageSubmitGPSLGU(action) {
        if (action=="a" || action=="sc") {
            //if (isInputEmpty("u_cashierby")) return false;
            if (isInputEmpty("u_remittancedate")) return false;
            if (isInputNegative("u_totalamount")) return false;
        }
    return true;
}

function onCFLGPSLGU(Id) {
	return true;
}

function onCFLGetParamsGPSLGU(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGU() {
}

function onElementFocusGPSLGU(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGU(element,event,column,table,row) {
    switch (column) {
			case "u_ordatefr":
			case "u_ordateto":
			case "u_orfr":
			case "u_orto":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
		}
}

function onElementValidateGPSLGU(element,column,table,row) {
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
	return true;
}

function onElementClickGPSLGU(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGU(element) {
	return true;
}

function onElementCFLGetParamsGPSLGU(element,params) {
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
        var params = new Array();
	params["focus"] = false;
	return params;
}

function onTableSelectRowGPSLGU(table,row) {
}

function formSearchNow() {
    
//        if (getInput("u_cashierby")=="" && getInput("u_asofdate")=="" && getInput("u_ordateto")=="" && getInput("u_orto")=="" && getInput("u_orfr")=="" ){
//            if (isInputEmpty("u_ordatefr")) return false;
//        }
        if(inputEmpty("u_cashierby")) return false;
        if(inputEmpty("u_asofdate")) return false;
        clearTable("T1",true);
        var result = page.executeFormattedQuery("");	
        if (result.getAttribute("result")!= "-1") {
                if (parseInt(result.getAttribute("result"))>0) {
                        clearTable("T1",true);
                        var data = new Array();
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                    data["u_ornumber"] = result.childNodes.item(xxx).getAttribute("docno");
                                    data["u_ordate"] = formatDateToHttp(result.childNodes.item(xxx).getAttribute("u_date"));
                                    data["u_amountpaid"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_paidamount"));
                                    data["u_cashierby"] = result.childNodes.item(xxx).getAttribute("createdby");
                                    data["u_remittancedate"] = formatDateToHttp(result.childNodes.item(xxx).getAttribute("u_remittancedate"));
                                    insertTableRowFromArray("T1",data);
                            }
                } else {
                        clearTable("T1",true);
                        setStatusMsg("No record found.",4000,1);
                        return false;
                }
        } else {
  
                clearTable("T1",true);
                page.statusbar.showError("Error retrieving record. Try Again, if problem persists, check the connection.");
                return false;
        }
        
   computeTotalAmount();
    
        
}

function ApplyRemittanceDate() {
    
   var rc =  getTableRowCount("T1");
   for (xxx = 1; xxx <= rc; xxx++) {
            if (isTableRowDeleted("T1",xxx)==false) {
                    setTableInput("T1","u_remittancedate",getInput("u_remittancedate"),xxx);
                }
            }
}

function computeTotalAmount() {
	var rc = getTableRowCount("T1"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                    //if(getTableInput("T1","u_status",i) == 'O' ){
                        total+= getTableInputNumeric("T1","u_amountpaid",i);
                    //}
			
		}
	}
        
	setInputAmount("u_totalamount",total);
}
