// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
//page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (column) {
		case "code":
			setTableInput(table,"name",getTableInput(table,"code"));
			break;
		case "u_rate":
			setTableInputAmount(table,"u_hc",getTableInputNumeric(table,"u_rate"));
			setTableInputAmount(table,"u_pf",0);
			break;
		case "u_hc":
			if (getTableInputNumeric(table,"u_hc")>getTableInputNumeric(table,"u_rate")) {
				setTableInputAmount(table,"u_hc",getTableInputNumeric(table,"u_rate"));	
				setTableInputAmount(table,"u_pf",0);	
			}
			setTableInputAmount(table,"u_pf",getTableInputNumeric(table,"u_rate")-getTableInputNumeric(table,"u_hc"));
			break;
		case "u_pf":
			if (getTableInputNumeric(table,"u_pf")>getTableInputNumeric(table,"u_rate")) {
				setTableInputAmount(table,"u_pf",getTableInputNumeric(table,"u_rate"));	
				setTableInputAmount(table,"u_hc",0);	
			}
			setTableInputAmount(table,"u_hc",getTableInputNumeric(table,"u_rate")-getTableInputNumeric(table,"u_pf"));
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			enableTableInput(table,"code");
			focusTableInput(table,"code");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_group")) return false;
			//if (isTableInputNegative(table,"u_rate")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_group")) return false;
			//if (isTableInputNegative(table,"u_rate")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getTableRowStatus(table,row)=="E") {
				disableTableInput(table,"code");
				focusTableInput(table,"u_group");
			}
			break;
	}
}


