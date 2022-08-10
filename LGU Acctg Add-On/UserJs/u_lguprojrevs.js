// page events
//page.events.add.load('onPageLoadLGUAcctg');
//page.events.add.resize('onPageResizeLGUAcctg');
page.events.add.submit('onPageSubmitLGUAcctg');
//page.events.add.cfl('onCFLLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownLGUAcctg');
page.elements.events.add.validate('onElementValidateLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsLGUAcctg');
//page.elements.events.add.changing('onElementChangingLGUAcctg');
page.elements.events.add.change('onElementChangeLGUAcctg');
//page.elements.events.add.click('onElementClickLGUAcctg');
//page.elements.events.add.cfl('onElementCFLLGUAcctg');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsLGUAcctg');

// table events
//page.tables.events.add.reset('onTableResetRowLGUAcctg');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowLGUAcctg');
//page.tables.events.add.afterInsert('onTableAfterInsertRowLGUAcctg');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowLGUAcctg');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowLGUAcctg');
//page.tables.events.add.delete('onTableDeleteRowLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUAcctg');
//page.tables.events.add.select('onTableSelectRowLGUAcctg');

function onPageLoadLGUAcctg() {
}

function onPageResizeLGUAcctg(width,height) {
}

function onPageSubmitLGUAcctg(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_yr")) return false;
		if (isInputEmpty("u_appcode")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
	}
	return true;
}

function onCFLLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadLGUAcctg() {
}

function onElementFocusLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownLGUAcctg(element,event,column,table,row) {
}

function onElementValidateLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_estmooe":
				case "u_estco":
					setInputAmount("u_estbudget",getInputNumeric("u_estmooe")+getInputNumeric("u_estco"));
					break;
				
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_yr":
					setInput("code",getInput("u_yr") + "-" + getInput("u_appcode"));
					var code = page.executeFormattedSearch("select code from u_lguprojs where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					break;
				case "u_appcode":
					setInput("u_profitcentername",getInputSelectedText("u_appcode"));
					setInput("code",getInput("u_yr") + "-" + getInput("u_appcode"));
					var code = page.executeFormattedSearch("select code from u_lguprojs where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					
					break;
			}
			break;
	}
	return true;
}

function onElementClickLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsLGUAcctg(element,params) {
	return params;
}

function onTableResetRowLGUAcctg(table) {
}

function onTableBeforeInsertRowLGUAcctg(table) {
	return true;
}

function onTableAfterInsertRowLGUAcctg(table,row) {
}

function onTableBeforeUpdateRowLGUAcctg(table,row) {
	return true;
}

function onTableAfterUpdateRowLGUAcctg(table,row) {
}

function onTableBeforeDeleteRowLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowLGUAcctg(table,row) {
}

function onTableBeforeSelectRowLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowLGUAcctg(table,row) {
}

