onElementValidate// page events
//page.events.add.load('onPageLoadGPSMotorViolation');
//page.events.add.resize('onPageResizeGPSMotorViolation');
//page.events.add.submit('onPageSubmitGPSMotorViolation');
//page.events.add.cfl('onCFLGPSMotorViolation');
//page.events.add.cflgetparams('onCFLGetParamsGPSMotorViolation');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSMotorViolation');

// element events
//page.elements.events.add.focus('onElementFocusGPSMotorViolation');
//page.elements.events.add.keydown('onElementKeyDownGPSMotorViolation');
page.elements.events.add.validate('onElementValidateGPSMotorViolation');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSMotorViolation');
//page.elements.events.add.changing('onElementChangingGPSMotorViolation');
//page.elements.events.add.change('onElementChangeGPSMotorViolation');
//page.elements.events.add.click('onElementClickGPSMotorViolation');
//page.elements.events.add.cfl('onElementCFLGPSMotorViolation');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSMotorViolation');

// table events
//page.tables.events.add.reset('onTableResetRowGPSMotorViolation');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSMotorViolation');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSMotorViolation');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSMotorViolation');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSMotorViolation');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSMotorViolation');
//page.tables.events.add.delete('onTableDeleteRowGPSMotorViolation');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSMotorViolation');
//page.tables.events.add.select('onTableSelectRowGPSMotorViolation');

function onPageLoadGPSMotorViolation() {
}

function onPageResizeGPSMotorViolation(width,height) {
}

function onPageSubmitGPSMotorViolation(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document  
    	
	return true;
}

function onCFLGPSMotorViolation(Id) {
	return true;
}

function onCFLGetParamsGPSMotorViolation(Id,params) {
	return params;
}

function onTaskBarLoadGPSMotorViolation() {
}

function onElementFocusGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSMotorViolation(element,event,column,table,row) {
}

function onElementValidateGPSMotorViolation(element,column,table,row) {
	
	switch (table) { 
		default:
			switch(column) {
				case "code":
                                    setInput("u_licenseno",getInput("code"));
							}
							
					
                                    break;
				
											
	}
	switch (table) { 
		default:
			switch(column) {
				case "u_firstname":
				
                                    setInput("name",getInput("u_firstname"));
									 //setInput("name",getInput("u_lastname"));
							}
							
			

                                    break;
									
				
											
	}
	
	
			
	return true;
}

function onElementGetValidateParamsGPSMotorViolation(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementChangeGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementClickGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementCFLGPSMotorViolation(element) {
	return true;
}

function onElementCFLGetParamsGPSMotorViolation(element,params) {
	return params;
}

function onTableResetRowGPSMotorViolation(table) {
}

function onTableBeforeInsertRowGPSMotorViolation(table) {
	return true;
}

function onTableAfterInsertRowGPSMotorViolation(table,row) {
}

function onTableBeforeUpdateRowGPSMotorViolation(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSMotorViolation(table,row) {
}

function onTableBeforeDeleteRowGPSMotorViolation(table,row) {
	return true;
}

function onTableDeleteRowGPSMotorViolation(table,row) {
}

function onTableBeforeSelectRowGPSMotorViolation(table,row) {
	return true;
}

function onTableSelectRowGPSMotorViolation(table,row) {
}
function formSearchViolationName() {
			//if (isInputEmpty("u_lastname")) return false;
			//if (isInputEmpty("u_firstname")) return false;
			//if (isInputEmpty("u_middlename")) return false;
		formSearchNow();
	}

