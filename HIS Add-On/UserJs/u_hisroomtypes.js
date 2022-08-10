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
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_type")) return false;
		if (getTableInput("T1","u_inscode")!="") {
			page.statusbar.showError("An insurance benefit item is being added/edited.");
			return false;
		}
		
		
	}
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
	switch(table) {
		case "T110":
			switch(column) {
				case "price":
					if (getTableInput(table,"scdiscpricelist",row)!="") {
						setInputAmount("u_scdiscamount",utils.multiply(getTableInputNumeric(table,"price",row),getTableInputNumeric(table,"scdiscperc",row)));
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
	switch (table) {
		default:
			/*
			switch (column) {
				case "u_series":
					if (getInput("u_series")!=-1) {
						setInput("u_type",getInputSelectedText("u_series"));
					}
					setDocNo(true,"u_series","code");
					break;
			}
			*/
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			/*
			switch (column) {
				case "u_type":
					if (getInput("u_series")!=-1) {
						setInputSelectedText("u_series",getInput("u_type"));
						setDocNo(true,"u_series","code");
					}
					break;
			}
			break;
			*/
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
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInputNumeric(table,"u_amount")<=0 && getTableInputNumeric(table,"u_perc")<=0) {
				page.statusbar.showError("Amount or % must be entered.");
				focusTableInput(table,"u_amount");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInputNumeric(table,"u_amount")<=0 && getTableInputNumeric(table,"u_perc")<=0) {
				page.statusbar.showError("Amount or % must be entered.");
				focusTableInput(table,"u_amount");
				return false;
			}
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
		var params = new Array();
	switch (table) {
		case "T101":
			setElementHTMLById("divEditor",getTableInput("T101","remarks",row));
			break;
		case "T110":
			params["focus"] = false;
			focusTableInput(table,"price",row);
			break;
	}
	return params;
}

