// page events
//page.events.add.load('onPageLoadGPSLGUAcctgReports');
//page.events.add.resize('onPageResizeGPSLGUAcctgReports');
//page.events.add.submit('onPageSubmitGPSLGUAcctgReports');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctgReports');
//page.events.add.reportgetparams('onPageReportGetParamsGPSLGUAcctgReports');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctgReports');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctgReports');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctgReports');
//page.elements.events.add.validate('onElementValidateGPSLGUAcctgReports');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctgReports');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctgReports');
//page.elements.events.add.change('onElementChangeGPSLGUAcctgReports');
//page.elements.events.add.click('onElementClickGPSLGUAcctgReports');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctgReports');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctgReports');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctgReports');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctgReports');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctgReports');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctgReports');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctgReports');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctgReports');
page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctgReports');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctgReports');
page.tables.events.add.select('onTableSelectRowGPSLGUAcctgReports');


var osno = "";

function onPageLoadGPSLGUAcctg() {
	
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	
	return true;
}

function onCFLGPSLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctg() {
}

function onElementFocusGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctg(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		
			
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		
	}
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		
	}
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_glacctno");
			break;
	}
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {
		
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctgReports(table,row) {
	
	switch (table) {
		case "T4":
			computeTotalMultipleObligations();
			break;
	}
		

}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctgReports(table,row) {
	switch (table) {
		case "T4":
			computeTotalMultipleObligations();
			break;
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctgReports(table,row) {
	switch (table) {
		case "T4":
			computeTotalMultipleObligations();
			break;
	}
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctgReports(table,row) {
	var params = new Array();
	switch (table) {
		
		 case"T4":
            osno = getTableInput(table,"u_osno",row);

        break;
	}
	return params;
}

function openobligation() {
        OpenPopup(1024,600,"./udo.php?&objectcode=u_lguobligationslips&sf_keys="+osno+"&formAction=e","UpdPays");	
}

function computeTotalMultipleObligations() {
	var rc = getTableRowCount("T4"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			total+= getTableInputNumeric("T4","u_amount",i);
                      
		}
	}

	setInputAmount("u_osnototal",total);
}






