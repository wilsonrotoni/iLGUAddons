// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
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
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');


function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("name")) return false;
		if (isInputNegative("u_chargingrate")) return false;
		
		if (isInputChecked("u_isshared")) {
			if (getTableRowCount("T1",true)<=1) {
				page.statusbar.showError("At least two beds must be entered.");
				return false;
			}
		}
	}	return true;
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
	switch(table) {
		default:
			switch(column) {
				case "code":
					if (!isInputChecked("u_isshared")) {
						setTableInput("T1","u_bedno",getInput("code"),-1);
					}
					break;
			}
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
	var data = new Array();
	switch(table) {
		case "T1":
			break;
		default:
			switch(column) {
				case "u_isshared":
					/*clearTable("T1",true);	
					if (!isInputChecked("u_isshared")) {
						data["u_bedno"]=getInput("code");
						insertTableRowFromArray("T1",data);
					}*/
					break;
			}
			break;
		
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(element,params) {
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch(table) {
		case "T1":
			enableTableInput(table,"u_bedno");
			focusTableInput(table,"u_bedno");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch(table) {
		case "T1":
			if (!isInputChecked("u_isshared")) {
				page.statusbar.showError("You cannot add bed on unshared room.");	
				return false;
			}
			if (isTableInputEmpty(table,"u_bedno")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch(table) {
		case "T1":
			if (!isInputChecked("u_isshared")) {
				page.statusbar.showError("You cannot add bed on unshared room.");	
				return false;
			}
			if (isTableInputEmpty(table,"u_bedno")) return false;
			break;
	}
	return true;
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			if (getTableInput(table,"u_status")!="Vacant") {
				disableTableInput(table,"u_bedno");
			}
			break;
	}
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch(table) {
		case "T1":
			if (!isInputChecked("u_isshared")) {
				page.statusbar.showError("You cannot delete bed on unshared room.");	
				return false;
			}
			if (getTableInput(table,"u_status")!="Vacant") {
				page.statusbar.showError("You can only delete vacant room.");	
				return false;
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

