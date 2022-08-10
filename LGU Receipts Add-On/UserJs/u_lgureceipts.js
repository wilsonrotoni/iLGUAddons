// page events
//page.events.add.load('onPageLoadGPSLGUReceipts');
//page.events.add.resize('onPageResizeGPSLGUReceipts');
page.events.add.submit('onPageSubmitGPSLGUReceipts');
//page.events.add.cfl('onCFLGPSLGUReceipts');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUReceipts');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUReceipts');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUReceipts');
page.elements.events.add.keydown('onElementKeyDownGPSLGUReceipts');
page.elements.events.add.validate('onElementValidateGPSLGUReceipts');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUReceipts');
//page.elements.events.add.changing('onElementChangingGPSLGUReceipts');
//page.elements.events.add.change('onElementChangeGPSLGUReceipts');
//page.elements.events.add.click('onElementClickGPSLGUReceipts');
//page.elements.events.add.cfl('onElementCFLGPSLGUReceipts');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUReceipts');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUReceipts');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUReceipts');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUReceipts');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUReceipts');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUReceipts');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUReceipts');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUReceipts');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUReceipts');
//page.tables.events.add.select('onTableSelectRowGPSLGUReceipts');

function onPageLoadGPSLGUReceipts() {
}

function onPageResizeGPSLGUReceipts(width,height) {
}

function onPageSubmitGPSLGUReceipts(action) {
    if (action=="a" || action=="sc") {
            //if (isInputEmpty("u_cashierby")) return false;
            var rc = getTableRowCount("T1");
            if(rc==0){
                page.statusbar.showError("Receipt Details is required.");
                return false;
            }
            if (isInputEmpty("u_purcahseddate")) return false;
        }
    return true;
}

function onCFLGPSLGUReceipts(Id) {
	return true;
}

function onCFLGetParamsGPSLGUReceipts(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUReceipts() {
}

function onElementFocusGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUReceipts(element,event,column,table,row) {
        var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		
		case "F4":
			formSubmit();
			break;
		default:
			switch (table) {
			}
			break;
	}
}

function onElementValidateGPSLGUReceipts(element,column,table,row) {
            switch (table) {
                case "T1":
                     switch (column) {
                        case "u_receiptfrm":
                        case "u_receiptto":
                            setTableInput(table,"u_noofreceipt",getTableInputNumeric(table,"u_receiptto")-getTableInputNumeric(table,"u_receiptfrm") + 1);
                            setTableInput(table,"u_available",getTableInputNumeric(table,"u_receiptto")-getTableInputNumeric(table,"u_receiptfrm") + 1);
                            setTableInput(table,"u_refno",getTableInput(table,"u_form") + "-" + getTableInput(table,"u_receiptfrm") + "-" + getTableInput(table,"u_receiptto"));
                             break;
                     }
                    break;
                default:
                    switch (column) {
                        case "u_cntperbundle":
                        case "u_receiptfrm":
                        case "u_receiptto":
                        case "u_bundlecnt":
                            setInput("u_receiptto",(getInputNumeric("u_receiptfrm")-1)+(getInputNumeric("u_bundlecnt")*getInputNumeric("u_cntperbundle")));
                            setInput("u_noofreceipt",getInputNumeric("u_receiptto")-getInputNumeric("u_receiptfrm") + 1);
                           
                        clearTable("T1",true);
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
	return params;
}

function onTableResetRowGPSLGUReceipts(table) {
}

function onTableBeforeInsertRowGPSLGUReceipts(table) {
        switch(table){
            case "T1":
                if(getTableInput("T1","u_issuedto") != ""){
                    page.statusbar.showError("You cannot change this item. Item was being issued to '["+getTableInput("T1","u_issuedto")+"']");
                    return false;
                }
                if (isTableInputEmpty(table,"u_purchaseddate")) return false;
                if (isTableInputEmpty(table,"u_form")) return false;
                if (isTableInputEmpty(table,"u_receiptfrm")) return false;
                if (isTableInputEmpty(table,"u_receiptto")) return false;
                if (isTableInputNegative(table,"u_available")) return false;
                if (isTableInputNegative(table,"u_noofreceipt")) return false;
                break;
        }
	return true;
}

function onTableAfterInsertRowGPSLGUReceipts(table,row) {
}

function onTableBeforeUpdateRowGPSLGUReceipts(table,row) {
        switch(table){
            case "T1":
                if (isTableInputEmpty(table,"u_purchaseddate")) return false;
                if (isTableInputEmpty(table,"u_form")) return false;
                if (isTableInputEmpty(table,"u_receiptfrm")) return false;
                if (isTableInputEmpty(table,"u_receiptto")) return false;
                if (isTableInputZero(table,"u_available")) return false;
                if (isTableInputZero(table,"u_noofreceipt")) return false;
                if(getTableInput("T1","u_issuedto") != ""){
                    page.statusbar.showError("You cannot change this item. Item was being issued to '["+getTableInput("T1","u_issuedto")+"]'");
                    return false;
                }
                break;
        }
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
function formApplyReceipts() {
    
      if (isInputEmpty("u_purchaseddate",null,null,"tab1",0)) return false;
      if (isInputEmpty("u_form",null,null,"tab1",0)) return false;
      if (isInputEmpty("u_receiptfrm",null,null,"tab1",0)) return false;
      if (isInputEmpty("u_receiptto",null,null,"tab1",0)) return false;
      if (isInputNegative("u_noofreceipt")) return false;
      if (isInputEmpty("u_bundlecnt",null,null,"tab1",0)) return false;
      if (isInputEmpty("u_cntperbundle",null,null,"tab1",0)) return false;
      
      var rc = 0, cntperbundle=0, receiptfrm=0, receiptto=0 ;
        rc = parseInt(getInput("u_bundlecnt"));
        cntperbundle = parseInt(getInput("u_cntperbundle"));
        receiptfrm = parseInt( getInput("u_receiptfrm"));
        receiptto =  parseInt(getInput("u_receiptto"));
        clearTable("T1",true);
      var data = new Array();
	for (i = 0; i < rc; i++) {
            
                    data["u_purchaseddate"] = getInput("u_purchaseddate");
                    data["u_form"] = getInput("u_form");
                    data["u_receiptfrm"] = receiptfrm + (i * cntperbundle);
                    data["u_receiptto"] = (receiptfrm - 1 ) + ((i+1)*cntperbundle);
                    data["u_available"] = cntperbundle;
                    data["u_noofreceipt"] = cntperbundle;
                    data["u_refno"] = getInput("u_form") + "-" + (receiptfrm + (i * cntperbundle)) + "-" + ((receiptfrm - 1 ) + ((i+1)*cntperbundle));
                    insertTableRowFromArray("T1",data);
		
	}
        
	
      
}
