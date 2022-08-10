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
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGU');
page.tables.events.add.select('onTableSelectRowGPSLGU');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSLGU');


function onPageLoadGPSLGU() {
	setTableInputAmount("T1","u_denomination",getInputNumeric("u_openamount"));
	setTableInputAmount("T2","u_amount",getInputNumeric("u_salesamount"));
}

function onPageResizeGPSLGU(width,height) {
}

function onPageSubmitGPSLGU(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_time")) return false;
		if (action=="sc" && getInput("u_status")!="C") {
                    clearTable("T3",true); 
		}
	}
	return true;
}

function onPageSubmitReturnGPSLGU(action,sucess,error) {
//	if (action=="a" && sucess) runCustom('./udo.php?objectcode=u_LGUPOS');
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
}

function onElementValidateGPSLGU(element,column,table,row) {
	var rc,openamount=0,closeamount=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_count":
					rc=getTableRowCount("T1");
					for (ii = 1; ii <= rc; ii++) {
						openamount += getTableInputNumeric(table,"u_count",ii) * getTableInputNumeric(table,"u_denomination",ii);
					}
					setInputAmount("u_openamount",openamount);
					setTableInputAmount(table,"u_denomination",openamount);
					break;
					
			}
			break;
		case "T2":
			switch (column) {
				case "u_count":
					rc=getTableRowCount("T2");
					for (ii = 1; ii <= rc; ii++) {
						closeamount += getTableInputNumeric(table,"u_count",ii) * getTableInputNumeric(table,"u_denomination",ii);
					}
					setInputAmount("u_closecashamount",closeamount);
					setInputAmount("u_cashvariance",closeamount - (getInputNumeric("u_openamount")+getInputNumeric("u_cashamount")));
					setTableInputAmount(table,"u_denomination",closeamount);
					break;
					
			}
			break;
                        
                default:
                    switch (column) {
                        case "code":
                            if (getInput(column)!="") {
                                    setKey("keys",getInput(column)+"`0");
                                    formSubmit('e',null,null,null,true);
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
	return true;
}

function onTableSelectRowGPSLGU(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
		case "T2":
			params["focus"] = false;
			focusTableInput(table,"u_count",row);
			break;
	}		
	return params;	
}

function onTableBeforeEditRowGPSLGU(table,row) {
	switch (table) {
		case "T101":
                    openupdpays(getTableInput(table,"docno",row));
                    return false;
                break;
	}
	return true;
}

function u_openRegisterGPSPOSAsddon() {
      
	if (isInputEmpty("u_date")) return false;
	if (isInputEmpty("u_time")) return false;
        if(getInput("u_requiredreceiptopeningregister") == 1){
            if (getTableRowCount("T4")==0) {
                setStatusMsg("Receipts setup is required.");
                return false;
            }
        }
	if (window.confirm("Are you sure you want to open the register. Continue?")==false) return;	
	formSubmit("a");	
}

function u_closeRegisterGPSPOSAsddon() {
	if (window.confirm("Are you sure you want to close the register. Continue?")==false) return;	
	setInput("u_status","C");
	formSubmit("sc");	
}
function u_AddPosSeriresGPSLGUAddon(action){
        OpenPopup(550,350,"./udo.php?&objectcode=u_terminalseries&formAction=e","UpdPays");
}
function u_reopenRegisterGPSPOSAsddon() {
	if (window.confirm("Are you sure you want to reopen the register. Continue?")==false) return;	
	setInput("u_status","O");
	formSubmit("sc");	
}
function u_PostTransactionGPSPOSAsddon() {
	if (window.confirm("Are you sure you want to post transactions. Continue?")==false) return;
        if (getInput("u_postingdate")!=getInput("u_closedate")) {
            if (window.confirm("WARNING!! REMITTANCE DATE is NOT EQUAL to CLOSING DATE. Continue?")==false) return;
        }
	setInput("u_isremitted",1);
	formSubmit("sc");	
}

function openupdpays(key) {
	OpenPopup(550,350,"./udo.php?&objectcode=u_terminalseries&sf_keys="+key+"&formAction=e","UpdPays");	
}
