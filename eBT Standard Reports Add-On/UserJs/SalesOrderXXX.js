// page events
//page.events.add.load('onPageLoadGPSBB');
//page.events.add.resize('onPageResizeGPSBB');
//page.events.add.submit('onPageSubmitGPSBB');
//page.events.add.cfl('onCFLGPSBB');
//page.events.add.cflgetparams('onCFLGetParamsGPSBB');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBB');

// element events
//page.elements.events.add.focus('onElementFocusGPSBB');
//page.elements.events.add.keydown('onElementKeyDownGPSBB');
page.elements.events.add.validate('onElementValidateGPSBB');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSBB');
//page.elements.events.add.changing('onElementChangingGPSBB');
//page.elements.events.add.change('onElementChangeGPSBB');
//page.elements.events.add.click('onElementClickGPSBB');
//page.elements.events.add.cfl('onElementCFLGPSBB');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBB');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBB');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBB');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBB');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBB');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBB');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBB');
//page.tables.events.add.delete('onTableDeleteRowGPSBB');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBB');
//page.tables.events.add.select('onTableSelectRowGPSBB');

function onPageLoadGPSBB() {
}

function onPageResizeGPSBB(width,height) {
}

function onPageSubmitGPSBB(action) {
	return true;
}

function onCFLGPSBB(Id) {
	return true;
}

function onCFLGetParamsGPSBB(Id,params) {
	return params;
}

function onTaskBarLoadGPSBB() {
}

function onElementFocusGPSBB(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSBB(element,event,column,table,row) {
}

function onElementValidateGPSBB(element,column,table,row) {
	switch(table) {
		default:
			switch (column) {
				case "bprefno":
					if (getInput("bprefno")!="") {
						var docno = page.executeFormattedSearch("select group_concat(docno separator ', ') from salesorders where bprefno='"+getInput("bprefno")+"'");
						if (docno!="") {
							if (window.confirm("Duplicate Reference No. in Sales Order(s) ["+docno+"] Continue?")==false) return false;
						}
					}
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSBB(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSBB(element,column,table,row) {
	return true;
}

function onElementChangeGPSBB(element,column,table,row) {
	return true;
}

function onElementClickGPSBB(element,column,table,row) {
	return true;
}

function onElementCFLGPSBB(element) {
	return true;
}

function onElementCFLGetParamsGPSBB(element,params) {
	return params;
}

function onTableResetRowGPSBB(table) {
}

function onTableBeforeInsertRowGPSBB(table) {
	return true;
}

function onTableAfterInsertRowGPSBB(table,row) {
}

function onTableBeforeUpdateRowGPSBB(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSBB(table,row) {
}

function onTableBeforeDeleteRowGPSBB(table,row) {
	return true;
}

function onTableDeleteRowGPSBB(table,row) {
}

function onTableBeforeSelectRowGPSBB(table,row) {
	return true;
}

function onTableSelectRowGPSBB(table,row) {
}

