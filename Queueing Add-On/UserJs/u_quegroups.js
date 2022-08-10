// page events
//page.events.add.load('onPageLoadGPSQueueing');
//page.events.add.resize('onPageResizeGPSQueueing');
//page.events.add.submit('onPageSubmitGPSQueueing');
//page.events.add.cfl('onCFLGPSQueueing');
//page.events.add.cflgetparams('onCFLGetParamsGPSQueueing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSQueueing');

// element events
//page.elements.events.add.focus('onElementFocusGPSQueueing');
//page.elements.events.add.keydown('onElementKeyDownGPSQueueing');
//page.elements.events.add.validate('onElementValidateGPSQueueing');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSQueueing');
//page.elements.events.add.changing('onElementChangingGPSQueueing');
//page.elements.events.add.change('onElementChangeGPSQueueing');
//page.elements.events.add.click('onElementClickGPSQueueing');
page.elements.events.add.cfl('onElementCFLGPSQueueing');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSQueueing');

// table events
page.tables.events.add.reset('onTableResetRowGPSQueueing');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSQueueing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSQueueing');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSQueueing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSQueueing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSQueueing');
//page.tables.events.add.delete('onTableDeleteRowGPSQueueing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSQueueing');
//page.tables.events.add.select('onTableSelectRowGPSQueueing');

function onPageLoadGPSQueueing() {
}

function onPageResizeGPSQueueing(width,height) {
}

function onPageSubmitGPSQueueing(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
    	
	return true;
}

function onCFLGPSQueueing(Id) {
	return true;
}

function onCFLGetParamsGPSQueueing(Id,params) {
	return params;
}

function onTaskBarLoadGPSQueueing() {
}

function onElementFocusGPSQueueing(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSQueueing(element,event,column,table,row) {
}

function onElementValidateGPSQueueing(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSQueueing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSQueueing(element,column,table,row) {
	return true;
}

function onElementChangeGPSQueueing(element,column,table,row) {
	return true;
}

function onElementClickGPSQueueing(element,column,table,row) {
	return true;
}

function onElementCFLGPSQueueing(element) {
	if (element=="df_u_colorT1") {
		setTableInput("T999","colorpicker",getTableInput("T1","u_color"));
		showPopupFrame("popupFrameColorPicker",true);
		return false;
	}
	return true;
}

function onElementCFLGetParamsGPSQueueing(element,params) {
	return params;
}

function onTableResetRowGPSQueueing(table) {
	switch (table) {
		case "T1":
			if (isPopupFrameOpen("popupFrameColorPicker")) {
				hidePopupFrame("popupFrameColorPicker");
			}
			break;
	}
}

function onTableBeforeInsertRowGPSQueueing(table) {
	switch (table) {
		case "T1":
			if (isPopupFrameOpen("popupFrameColorPicker")) {
				page.statusbar.showError("Color picker is currently opened.");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSQueueing(table,row) {
}

function onTableBeforeUpdateRowGPSQueueing(table,row) {
	switch (table) {
		case "T1":
			if (isPopupFrameOpen("popupFrameColorPicker")) {
				page.statusbar.showError("Color picker is currently opened.");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSQueueing(table,row) {
}

function onTableBeforeDeleteRowGPSQueueing(table,row) {
	return true;
}

function onTableDeleteRowGPSQueueing(table,row) {
}

function onTableBeforeSelectRowGPSQueueing(table,row) {
	return true;
}

function onTableSelectRowGPSQueueing(table,row) {
}

function selectColorGPSQueueing() {
	setTableInput("T1","u_color",getTableInput("T999","colorpicker"));
	hidePopupFrame("popupFrameColorPicker",true);
}

