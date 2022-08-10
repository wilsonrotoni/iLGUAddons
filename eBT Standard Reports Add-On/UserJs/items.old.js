// page events
page.events.add.load('onPageLoadGPSPoroco');
//page.events.add.resize('onPageResizeGPSPoroco');
//page.events.add.submit('onPageSubmitGPSPoroco');
//page.events.add.submitreturn('onPageSubmitReturnGPSPoroco');
//page.events.add.cfl('onCFLGPSPoroco');
//page.events.add.cflgetparams('onCFLGetParamsGPSPoroco');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPoroco');

// element events
//page.elements.events.add.focus('onElementFocusGPSPoroco');
//page.elements.events.add.keydown('onElementKeyDownGPSPoroco');
page.elements.events.add.validate('onElementValidateGPSPoroco');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPoroco');
//page.elements.events.add.changing('onElementChangingGPSPoroco');
//page.elements.events.add.change('onElementChangeGPSPoroco');
//page.elements.events.add.click('onElementClickGPSPoroco');
//page.elements.events.add.cfl('onElementCFLGPSPoroco');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPoroco');

// table events
//page.tables.events.add.reset('onTableResetRowGPSPoroco');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPoroco');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPoroco');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPoroco');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPoroco');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPoroco');
//page.tables.events.add.delete('onTableDeleteRowGPSPoroco');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPoroco');
//page.tables.events.add.select('onTableSelectRowGPSPoroco');

function onPageLoadGPSPoroco() {
	page.businessobject.editIfExistOnAdd = false;
	page.businessobject.alertIfExistOnAdd = false;
}

function onPageResizeGPSPoroco(width,height) {
}

function onPageSubmitGPSPoroco(action) {
	if ((action == "a") || (action == "sc")) {
	}
	return true;
}

function onPageSubmitReturnGPSPoroco(action,sucess,error) {
}

function onCFLGPSPoroco(Id) {
	return true;
}

function onCFLGetParamsGPSPoroco(Id,params) {
	return params;
}

function onTaskBarLoadGPSPoroco() {
}

function onElementFocusGPSPoroco(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPoroco(element,event,column,table,row) {
}

function onElementValidateGPSPoroco(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSPoroco(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPoroco(element,column,table,row) {
	return true;
}

function onElementChangeGPSPoroco(element,column,table,row) {
	return true;
}

function onElementClickGPSPoroco(element,column,table,row) {
	return true;
}

function onElementCFLGPSPoroco(element) {
	return true;
}

function onElementCFLGetParamsGPSPoroco(element,params) {
	return params;
}

function onTableResetRowGPSPoroco(table) {
}

function onTableBeforeInsertRowGPSPoroco(table) {
	return true;
}

function onTableAfterInsertRowGPSPoroco(table,row) {
}

function onTableBeforeUpdateRowGPSPoroco(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSPoroco(table,row) {
}

function onTableBeforeDeleteRowGPSPoroco(table,row) {
	return true;
}

function onTableDeleteRowGPSPoroco(table,row) {
}

function onTableBeforeSelectRowGPSPoroco(table,row) {
	return true;
}

function onTableSelectRowGPSPoroco(table,row) {
}


