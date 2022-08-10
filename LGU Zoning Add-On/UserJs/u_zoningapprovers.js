// page events
//page.events.add.load('onPageLoadGPSLGUZoning');
//page.events.add.resize('onPageResizeGPSLGUZoning');
//page.events.add.submit('onPageSubmitGPSLGUZoning');
//page.events.add.cfl('onCFLGPSLGUZoning');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUZoning');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUZoning');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUZoning');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUZoning');
page.elements.events.add.validate('onElementValidateGPSLGUZoning');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUZoning');
//page.elements.events.add.changing('onElementChangingGPSLGUZoning');
//page.elements.events.add.change('onElementChangeGPSLGUZoning');
//page.elements.events.add.click('onElementClickGPSLGUZoning');
//page.elements.events.add.cfl('onElementCFLGPSLGUZoning');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUZoning');

// table events
page.tables.events.add.reset('onTableResetRowGPSLGUZoning');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUZoning');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUZoning');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUZoning');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUZoning');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUZoning');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUZoning');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUZoning');
//page.tables.events.add.select('onTableSelectRowGPSLGUZoning');
//page.tables.events.add.afterEdit('onTableAfterEditRowGPSLGUZoning');

function onPageLoadGPSLGUZoning() {
}

function onPageResizeGPSLGUZoning(width,height) {
}

function onPageSubmitGPSLGUZoning(action) {
	return true;
}

function onCFLGPSLGUZoning(Id) {
	return true;
}

function onCFLGetParamsGPSLGUZoning(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUZoning() {
}

function onElementFocusGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUZoning(element,event,column,table,row) {
}

function onElementValidateGPSLGUZoning(element,column,table,row) {
	switch (table) {
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

function onElementGetValidateParamsGPSLGUZoning(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUZoning(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUZoning(Id,params) {
	return params;
}

function onTableResetRowGPSLGUZoning(table) {
	switch (table) {
		case "T1":
			enableTableInput(table,"name");
			focusTableInput(table,"name");
			break;
	}		
}

function onTableBeforeInsertRowGPSLGUZoning(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"name")) return false;
			break;
	}		
	return true;
}

function onTableAfterInsertRowGPSLGUZoning(table,row) {
}

function onTableBeforeUpdateRowGPSLGUZoning(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"name")) return false;
			break;
	}		
	return true;
}

function onTableAfterUpdateRowGPSLGUZoning(table,row) {
}

function onTableBeforeDeleteRowGPSLGUZoning(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUZoning(table,row) {
}

function onTableBeforeSelectRowGPSLGUZoning(table,row) {
	return true;
}

function onTableSelectRowGPSLGUZoning(table,row) {
}

function onTableAfterEditRowGPSLGUZoning(table,row) {
	switch (table) {
		case "T1":
			if (getTableRowStatus(table,row)!="N") {
				disableTableInput(table,"name");
			} else focusTableInput(table,"name");	
			break;
	}		
}
