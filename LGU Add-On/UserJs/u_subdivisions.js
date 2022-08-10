// page events
//page.events.add.load('onPageLoadGPSLGU');
//page.events.add.resize('onPageResizeGPSLGU');
//page.events.add.submit('onPageSubmitGPSLGU');
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
page.tables.events.add.reset('onTableResetRowGPSLGU');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGU');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGU');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGU');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGU');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGU');
//page.tables.events.add.delete('onTableDeleteRowGPSLGU');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGU');
//page.tables.events.add.select('onTableSelectRowGPSLGU');

function onPageLoadGPSLGU() {
}

function onPageResizeGPSLGU(width,height) {
}

function onPageSubmitGPSLGU(action) {
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
	switch(table) {
		case "T1":
			switch (column) {
				case "name":
					setTableInput(table,"code",getTableInput(table,"name"));
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
}

