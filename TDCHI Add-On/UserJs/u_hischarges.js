// page events
page.events.add.load('onPageLoadGPSTDCHI');
//page.events.add.resize('onPageResizeGPSTDCHI');
//page.events.add.submit('onPageSubmitGPSTDCHI');
//page.events.add.cfl('onCFLGPSTDCHI');
//page.events.add.cflgetparams('onCFLGetParamsGPSTDCHI');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSTDCHI');

// element events
//page.elements.events.add.focus('onElementFocusGPSTDCHI');
//page.elements.events.add.keydown('onElementKeyDownGPSTDCHI');
//page.elements.events.add.validate('onElementValidateGPSTDCHI');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSTDCHI');
//page.elements.events.add.changing('onElementChangingGPSTDCHI');
page.elements.events.add.change('onElementChangeGPSTDCHI');
//page.elements.events.add.click('onElementClickGPSTDCHI');
//page.elements.events.add.cfl('onElementCFLGPSTDCHI');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSTDCHI');

// table events
//page.tables.events.add.reset('onTableResetRowGPSTDCHI');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSTDCHI');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSTDCHI');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSTDCHI');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSTDCHI');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSTDCHI');
//page.tables.events.add.delete('onTableDeleteRowGPSTDCHI');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSTDCHI');
//page.tables.events.add.select('onTableSelectRowGPSTDCHI');

function onPageLoadGPSTDCHI() {
	//if (getVar("formSubmitAction")=="a") {
	//	enableInput("docseries");	
	//}
}

function onPageResizeGPSTDCHI(width,height) {
}

function onPageSubmitGPSTDCHI(action) {
	return true;
}

function onCFLGPSTDCHI(Id) {
	return true;
}

function onCFLGetParamsGPSTDCHI(Id,params) {
	return params;
}

function onTaskBarLoadGPSTDCHI() {
}

function onElementFocusGPSTDCHI(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSTDCHI(element,event,column,table,row) {
}

function onElementValidateGPSTDCHI(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSTDCHI(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSTDCHI(element,column,table,row) {
	return true;
}

function onElementChangeGPSTDCHI(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_department":
					switch (getInput("u_department")) {
						case "LAB": setTableInput("T4","u_itemgroup","LAB"); break;
						//case "RAD-X-RAY": setTableInput("T4","u_itemgroup","XRY"); break;
						case "PHA": setTableInput("T4","u_itemgroup","MED"); break;
						case "FIN-CSR": setTableInput("T4","u_itemgroup","SUP"); break;
						//case "NSG-ER": setTableInput("T4","u_itemgroup","MSC"); break;
						//case "NSG-OP": setTableInput("T4","u_itemgroup","MSC"); break;
						//case "NSG-NS.1": setTableInput("T4","u_itemgroup","MSC"); break;
						//case "NSG-NS.2": setTableInput("T4","u_itemgroup","MSC"); break;
						//case "NSG-NS.3": setTableInput("T4","u_itemgroup","MSC"); break;
						//case "NSG-NS.4": setTableInput("T4","u_itemgroup","MSC"); break;
						//case "FIN-BILLING": setTableInput("T4","u_itemgroup","PRF"); break;
					}
					break;
			}
	}
	return true;
}

function onElementClickGPSTDCHI(element,column,table,row) {
	return true;
}

function onElementCFLGPSTDCHI(element) {
	return true;
}

function onElementCFLGetParamsGPSTDCHI(element,params) {
	return params;
}

function onTableResetRowGPSTDCHI(table) {
}

function onTableBeforeInsertRowGPSTDCHI(table) {
	return true;
}

function onTableAfterInsertRowGPSTDCHI(table,row) {
}

function onTableBeforeUpdateRowGPSTDCHI(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSTDCHI(table,row) {
}

function onTableBeforeDeleteRowGPSTDCHI(table,row) {
	return true;
}

function onTableDeleteRowGPSTDCHI(table,row) {
}

function onTableBeforeSelectRowGPSTDCHI(table,row) {
	return true;
}

function onTableSelectRowGPSTDCHI(table,row) {
}

