// page events
//page.events.add.load('onPageLoadLGUPurchasing');
//page.events.add.resize('onPageResizeLGUPurchasing');
//page.events.add.submit('onPageSubmitLGUPurchasing');
//page.events.add.cfl('onCFLLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownLGUPurchasing');
page.elements.events.add.validate('onElementValidateLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsLGUPurchasing');
//page.elements.events.add.changing('onElementChangingLGUPurchasing');
//page.elements.events.add.change('onElementChangeLGUPurchasing');
//page.elements.events.add.click('onElementClickLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLLGUPurchasing');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowLGUPurchasing');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowLGUPurchasing');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowLGUPurchasing');

function onPageLoadLGUPurchasing() {
}

function onPageResizeLGUPurchasing(width,height) {
}

function onPageSubmitLGUPurchasing(action) {
	return true;
}

function onCFLLGUPurchasing(Id) {
	return true;
}

function onCFLGetParamsLGUPurchasing(Id,params) {
	return params;
}

function onTaskBarLoadLGUPurchasing() {
}

function onElementFocusLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementKeyDownLGUPurchasing(element,event,column,table,row) {
}

function onElementValidateLGUPurchasing(element,column,table,row) {
        switch(table) {
		case "T1":
			switch (column) {
				case "name":
					setTableInput(table,"code",getTableInput(table,"name"));
					break;
			}
			break;
	}
}

function onElementGetValidateParamsLGUPurchasing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementChangeLGUPurchasing(element,column,table,row) {
   
}

function onElementClickLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementCFLLGUPurchasing(element) {
	return true;
}

function onElementCFLGetParamsLGUPurchasing(Id,params) {
    
    
}

function onTableResetRowLGUPurchasing(table) {
}

function onTableBeforeInsertRowLGUPurchasing(table) {
	return true;
}

function onTableAfterInsertRowLGUPurchasing(table,row) {
}

function onTableBeforeUpdateRowLGUPurchasing(table,row) {
	return true;
}

function onTableAfterUpdateRowLGUPurchasing(table,row) {
}

function onTableBeforeDeleteRowLGUPurchasing(table,row) {
	return true;
}

function onTableDeleteRowLGUPurchasing(table,row) {
}

function onTableBeforeSelectRowLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowLGUPurchasing(table,row) {
}

