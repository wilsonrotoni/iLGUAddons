// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
//page.events.add.submit('onPageSubmitGPSLGUAcctg');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctg');
//page.elements.events.add.validate('onElementValidateGPSLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctg');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
page.elements.events.add.change('onElementChangeGPSLGUAcctg');
//page.elements.events.add.click('onElementClickGPSLGUAcctg');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctg');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctg');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');

function onPageLoadGPSLGUAcctg() {
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	return true;
}

function onCFLGPSLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctg() {
}

function onElementFocusGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctg(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_expclass":
					switch (getInput(column)) {
						case "PS": setInput("u_expgroupno","1.1"); break;
						case "MOOE": setInput("u_expgroupno","1.2"); break;
						case "FE": setInput("u_expgroupno","1.3"); break;
						case "CO": setInput("u_expgroupno","2.0"); break;
						default: setInput("u_expgroupno",""); break;
					}
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(element,params) {
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
}

