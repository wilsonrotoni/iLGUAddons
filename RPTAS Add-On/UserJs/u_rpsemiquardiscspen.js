// page events
//page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
//page.events.add.submit('onPageSubmitGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSRPTAS');

// element events
//page.elements.events.add.focus('onElementFocusGPSRPTAS');
//page.elements.events.add.keydown('onElementKeyDownGPSRPTAS');
//page.elements.events.add.validate('onElementValidateGPSRPTAS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSRPTAS');
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
page.elements.events.add.change('onElementChangeGPSRPTAS');
//page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
//page.tables.events.add.select('onTableSelectRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
	return true;
}

function onCFLGPSRPTAS(Id) {
	return true;
}

function onCFLGetParamsGPSRPTAS(Id,params) {
	return params;
}

function onTaskBarLoadGPSRPTAS() {
}

function onElementFocusGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSRPTAS(element,event,column,table,row) {
}

function onElementValidateGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSRPTAS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementChangeGPSRPTAS(element,column,table,row) {
        switch (table) {
                    case "T1":
                            switch (column) {
                                    case "u_monthfr":
                                    case "u_monthto":
                                    case "u_qtr":
                                    case "u_paymode":
                                            setTableInput(table,"code",getTableInput(table,"u_paymode")+"-"+getTableInput(table,"u_qtr")+"_"+(getTableInput(table,"u_monthfr")).padStart1(5,'0')+"_"+(getTableInput(table,"u_monthto")).padStart1(5,'0'));
                                            setTableInput(table,"name",getTableInput(table,"u_paymode")+"-"+getTableInput(table,"u_qtr")+"_"+(getTableInput(table,"u_monthfr")).padStart1(5,'0')+"_"+(getTableInput(table,"u_monthto")).padStart1(5,'0'));
                                            break;
                            }
                            break;
        }	
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(element,params) {
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
        switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_class")) return false;
			if (isTableInputEmpty(table,"u_type")) return false;
			break;
	}	
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
    switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_class")) return false;
			if (isTableInputEmpty(table,"u_type")) return false;
			break;
	}	
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

if (!String.prototype.padStart1) {
    String.prototype.padStart1 = function padStart1(targetLength, padString) {
        targetLength = targetLength >> 0; //truncate if number, or convert non-number to 0;
        padString = String(typeof padString !== 'undefined' ? padString : ' ');
        if (this.length >= targetLength) {
            return String(this);
        } else {
            targetLength = targetLength - this.length;
            if (targetLength > padString.length) {
                padString += padString.repeat(targetLength / padString.length); //append to original to ensure we are longer than needed
            }
            return padString.slice(0, targetLength) + String(this);
        }
    }
}