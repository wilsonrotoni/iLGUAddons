// page events
//page.events.add.load('onPageLoadGPSSMS');
//page.events.add.resize('onPageResizeGPSSMS');
//page.events.add.submit('onPageSubmitGPSSMS');
//page.events.add.cfl('onCFLGPSSMS');
//page.events.add.cflgetparams('onCFLGetParamsGPSSMS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSSMS');

// element events
//page.elements.events.add.focus('onElementFocusGPSSMS');
//page.elements.events.add.keydown('onElementKeyDownGPSSMS');
//page.elements.events.add.validate('onElementValidateGPSSMS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSSMS');
//page.elements.events.add.changing('onElementChangingGPSSMS');
//page.elements.events.add.change('onElementChangeGPSSMS');
//page.elements.events.add.click('onElementClickGPSSMS');
//page.elements.events.add.cfl('onElementCFLGPSSMS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSSMS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSSMS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSSMS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSSMS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSSMS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSSMS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSSMS');
//page.tables.events.add.delete('onTableDeleteRowGPSSMS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSSMS');
//page.tables.events.add.select('onTableSelectRowGPSSMS');

function onPageLoadGPSSMS() {
}

function onPageResizeGPSSMS(width,height) {
}

function onPageSubmitGPSSMS(action) {
	return true;
}

function onCFLGPSSMS(Id) {
	return true;
}

function onCFLGetParamsGPSSMS(Id,params) {
	return params;
}

function onTaskBarLoadGPSSMS() {
}

function onElementFocusGPSSMS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSSMS(element,event,column,table,row) {
}

function onElementValidateGPSSMS(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSSMS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSSMS(element,column,table,row) {
	return true;
}

function onElementChangeGPSSMS(element,column,table,row) {
	return true;
}

function onElementClickGPSSMS(element,column,table,row) {
	return true;
}

function onElementCFLGPSSMS(element) {
	return true;
}

function onElementCFLGetParamsGPSSMS(element,params) {
	return params;
}

function onTableResetRowGPSSMS(table) {
}

function onTableBeforeInsertRowGPSSMS(table) {
	return true;
}

function onTableAfterInsertRowGPSSMS(table,row) {
}

function onTableBeforeUpdateRowGPSSMS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSSMS(table,row) {
}

function onTableBeforeDeleteRowGPSSMS(table,row) {
	return true;
}

function onTableDeleteRowGPSSMS(table,row) {
}

function onTableBeforeSelectRowGPSSMS(table,row) {
	return true;
}

function onTableSelectRowGPSSMS(table,row) {
}

