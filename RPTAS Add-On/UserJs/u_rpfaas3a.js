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
page.elements.events.add.changing('onElementChangingGPSRPTAS');
page.elements.events.add.change('onElementChangeGPSRPTAS');
//page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
//page.tables.events.add.select('onTableSelectRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
	if (getVar("formSubmitAction")=="a") {
		setInput("u_arpno",window.opener.getInput("docno"));
		setInput("u_suffix",window.opener.getInput("suffix"));
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_arpno")) return false;
		if (isInputEmpty("u_actualuse")) return false;
		if (isInputEmpty("u_machine")) return false;
		if (isInputNegative("u_orgcost")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSRPTAS(action,success,error) {
	try {
		window.opener.setKey("keys",window.opener.getInput("docno"));
		window.opener.formEdit();
                window.close();
	} catch(TheError) {
	}
	//if (success) window.close();
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
				case "u_orgcost":
				case "u_depreperc":
					setInputAmount("u_deprevalue",getInputNumeric("u_orgcost")*(getInputNumeric("u_depreperc")/100));
					setInputAmount("u_remvalue",getInputNumeric("u_orgcost")-getInputNumeric("u_deprevalue"));
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSRPTAS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSRPTAS(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_actualuse":
					break;
			}
			break;
	}
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

function onElementCFLGetParamsGPSRPTAS(element,params) {
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_sqm")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS(); break;
	}		
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_sqm")) return false;
			if (isTableInputNegative(table,"u_unitvalue")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS(); break;
	}		
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1": computeBaseValueGPSRPTAS(); break;
	}		
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

function computeBaseValueGPSRPTAS() {
	var rc = getTableRowCount("T1"),basevalue=0,deprevalue=0,adjvalue=0,adjmarketvalue=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			basevalue+= getTableInputNumeric("T1","u_basevalue",i);
			deprevalue+= getTableInputNumeric("T1","u_deprevalue",i);
			adjvalue+= getTableInputNumeric("T1","u_adjvalue",i);
			adjmarketvalue+= getTableInputNumeric("T1","u_adjmarketvalue",i);
		}
	}	
	setInputAmount("u_basevalue",basevalue+getInputNumeric("u_floorbasevalue"));
	setInputAmount("u_deprevalue",deprevalue+getInputNumeric("u_floordeprevalue"));
	setInputAmount("u_adjvalue",adjvalue+getInputNumeric("u_flooradjvalue"));
	setInputAmount("u_adjmarketvalue",adjmarketvalue+getInputNumeric("u_flooradjmarketvalue"));
}

