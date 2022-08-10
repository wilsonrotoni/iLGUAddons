// page events
page.events.add.load('onPageLoadGPSTECH');
//page.events.add.resize('onPageResizeGPSTECH');
//page.events.add.submit('onPageSubmitGPSTECH');
//page.events.add.cfl('onCFLGPSTECH');
//page.events.add.cflgetparams('onCFLGetParamsGPSTECH');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSTECH');

// element events
//page.elements.events.add.focus('onElementFocusGPSTECH');
//page.elements.events.add.keydown('onElementKeyDownGPSTECH');
//page.elements.events.add.validate('onElementValidateGPSTECH');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSTECH');
//page.elements.events.add.changing('onElementChangingGPSTECH');
page.elements.events.add.change('onElementChangeGPSTECH');
//page.elements.events.add.click('onElementClickGPSTECH');
//page.elements.events.add.cfl('onElementCFLGPSTECH');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSTECH');

// table events
//page.tables.events.add.reset('onTableResetRowGPSTECH');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSTECH');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSTECH');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSTECH');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSTECH');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSTECH');
//page.tables.events.add.delete('onTableDeleteRowGPSTECH');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSTECH');
//page.tables.events.add.select('onTableSelectRowGPSTECH');

function onPageLoadGPSTECH() {
	if (getVar("formSubmitAction")=="a") {
		enableInput("docseries");	
	}
}

function onPageResizeGPSTECH(width,height) {
}

function onPageSubmitGPSTECH(action) {
	return true;
}

function onCFLGPSTECH(Id) {
	return true;
}

function onCFLGetParamsGPSTECH(Id,params) {
	return params;
}

function onTaskBarLoadGPSTECH() {
}

function onElementFocusGPSTECH(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSTECH(element,event,column,table,row) {
}

function onElementValidateGPSTECH(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSTECH(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSTECH(element,column,table,row) {
	return true;
}

function onElementChangeGPSTECH(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_department":
					switch (getInput("u_department")) {
						case "LAB": setTableInput("T1","u_itemgroup","LAB"); break;
						case "RAD-X-RAY": setTableInput("T1","u_itemgroup","XRY"); break;
						case "PHA": setTableInput("T1","u_itemgroup","MED"); break;
						case "FIN-CSR": setTableInput("T1","u_itemgroup","SUP"); break;
						case "NSG-ER": setTableInput("T1","u_itemgroup","MSC"); break;
						case "NSG-OP": setTableInput("T1","u_itemgroup","MSC"); break;
						case "NSG-NS.1": setTableInput("T1","u_itemgroup","MSC"); break;
						case "NSG-NS.2": setTableInput("T1","u_itemgroup","MSC"); break;
						case "NSG-NS.3": setTableInput("T1","u_itemgroup","MSC"); break;
						case "NSG-NS.4": setTableInput("T1","u_itemgroup","MSC"); break;
						case "FIN-BILLING": setTableInput("T1","u_itemgroup","PRF"); break;
					}
					break;
			}
	}
	return true;
}

function onElementClickGPSTECH(element,column,table,row) {
	return true;
}

function onElementCFLGPSTECH(element) {
	return true;
}

function onElementCFLGetParamsGPSTECH(element,params) {
	return params;
}

function onTableResetRowGPSTECH(table) {
}

function onTableBeforeInsertRowGPSTECH(table) {
	return true;
}

function onTableAfterInsertRowGPSTECH(table,row) {
}

function onTableBeforeUpdateRowGPSTECH(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSTECH(table,row) {
}

function onTableBeforeDeleteRowGPSTECH(table,row) {
	return true;
}

function onTableDeleteRowGPSTECH(table,row) {
}

function onTableBeforeSelectRowGPSTECH(table,row) {
	return true;
}

function onTableSelectRowGPSTECH(table,row) {
}

