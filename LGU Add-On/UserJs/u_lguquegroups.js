// page events
//page.events.add.load('onPageLoadGPSPOS');
//page.events.add.resize('onPageResizeGPSPOS');
//page.events.add.submit('onPageSubmitGPSPOS');
//page.events.add.cfl('onCFLGPSPOS');
//page.events.add.cflgetparams('onCFLGetParamsGPSPOS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPOS');

// element events
//page.elements.events.add.focus('onElementFocusGPSPOS');
//page.elements.events.add.keydown('onElementKeyDownGPSPOS');
//page.elements.events.add.validate('onElementValidateGPSPOS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPOS');
//page.elements.events.add.changing('onElementChangingGPSPOS');
//page.elements.events.add.change('onElementChangeGPSPOS');
//page.elements.events.add.click('onElementClickGPSPOS');
page.elements.events.add.cfl('onElementCFLGPSPOS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPOS');

// table events
page.tables.events.add.reset('onTableResetRowGPSPOS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPOS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPOS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPOS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPOS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPOS');
//page.tables.events.add.delete('onTableDeleteRowGPSPOS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPOS');
//page.tables.events.add.select('onTableSelectRowGPSPOS');

function onPageLoadGPSPOS() {
}

function onPageResizeGPSPOS(width,height) {
}

function onPageSubmitGPSPOS(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
    	
	return true;
}

function onCFLGPSPOS(Id) {
	return true;
}

function onCFLGetParamsGPSPOS(Id,params) {
	return params;
}

function onTaskBarLoadGPSPOS() {
}

function onElementFocusGPSPOS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPOS(element,event,column,table,row) {
}

function onElementValidateGPSPOS(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSPOS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPOS(element,column,table,row) {
	return true;
}

function onElementChangeGPSPOS(element,column,table,row) {
	return true;
}

function onElementClickGPSPOS(element,column,table,row) {
	return true;
}

function onElementCFLGPSPOS(element) {
	if (element=="df_u_colorT1") {
		setTableInput("T999","colorpicker",getTableInput("T1","u_color"));
		showPopupFrame("popupFrameColorPicker",true);
		return false;
	}
	return true;
}

function onElementCFLGetParamsGPSPOS(element,params) {
	return params;
}

function onTableResetRowGPSPOS(table) {
	switch (table) {
		case "T1":
			if (isPopupFrameOpen("popupFrameColorPicker")) {
				hidePopupFrame("popupFrameColorPicker");
			}
			break;
	}
}

function onTableBeforeInsertRowGPSPOS(table) {
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

function onTableAfterInsertRowGPSPOS(table,row) {
}

function onTableBeforeUpdateRowGPSPOS(table,row) {
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

function onTableAfterUpdateRowGPSPOS(table,row) {
}

function onTableBeforeDeleteRowGPSPOS(table,row) {
	return true;
}

function onTableDeleteRowGPSPOS(table,row) {
}

function onTableBeforeSelectRowGPSPOS(table,row) {
	return true;
}

function onTableSelectRowGPSPOS(table,row) {
}

function selectColorGPSPOS() {
	setTableInput("T1","u_color",getTableInput("T999","colorpicker"));
	hidePopupFrame("popupFrameColorPicker",true);
}

