// page events
//page.events.add.load('onPageLoadGPSPMRS');
//page.events.add.resize('onPageResizeGPSPMRS');
//page.events.add.submit('onPageSubmitGPSPMRS');
//page.events.add.cfl('onCFLGPSPMRS');
//page.events.add.cflgetparams('onCFLGetParamsGPSPMRS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPMRS');

// element events
//page.elements.events.add.focus('onElementFocusGPSPMRS');
//page.elements.events.add.keydown('onElementKeyDownGPSPMRS');
page.elements.events.add.validate('onElementValidateGPSPMRS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPMRS');
//page.elements.events.add.changing('onElementChangingGPSPMRS');
//page.elements.events.add.change('onElementChangeGPSPMRS');
//page.elements.events.add.click('onElementClickGPSPMRS');
//page.elements.events.add.cfl('onElementCFLGPSPMRS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPMRS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSPMRS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPMRS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPMRS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPMRS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPMRS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPMRS');
//page.tables.events.add.delete('onTableDeleteRowGPSPMRS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPMRS');
//page.tables.events.add.select('onTableSelectRowGPSPMRS');

function onPageLoadGPSPMRS() {
}

function onPageResizeGPSPMRS(width,height) {
}

function onPageSubmitGPSPMRS(action) {
	return true;
}

function onCFLGPSPMRS(Id) {
	return true;
}

function onCFLGetParamsGPSPMRS(Id,params) {
	return params;
}

function onTaskBarLoadGPSPMRS() {
}

function onElementFocusGPSPMRS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPMRS(element,event,column,table,row) {
}

function onElementValidateGPSPMRS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "code":
					setTableInput(table,"name",getTableInput(table,column));
					break;
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSPMRS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPMRS(element,column,table,row) {
	return true;
}

function onElementChangeGPSPMRS(element,column,table,row) {
	return true;
}

function onElementClickGPSPMRS(element,column,table,row) {
	return true;
}

function onElementCFLGPSPMRS(element) {
	return true;
}

function onElementCFLGetParamsGPSPMRS(Id,params) {
	return params;
}

function onTableResetRowGPSPMRS(table) {
}

function onTableBeforeInsertRowGPSPMRS(table) {
	return true;
}

function onTableAfterInsertRowGPSPMRS(table,row) {
}

function onTableBeforeUpdateRowGPSPMRS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSPMRS(table,row) {
}

function onTableBeforeDeleteRowGPSPMRS(table,row) {
	return true;
}

function onTableDeleteRowGPSPMRS(table,row) {
}

function onTableBeforeSelectRowGPSPMRS(table,row) {
	return true;
}

function onTableSelectRowGPSPMRS(table,row) {
}

