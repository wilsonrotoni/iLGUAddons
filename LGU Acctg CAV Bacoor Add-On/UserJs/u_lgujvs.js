// page events
//page.events.add.load('onPageLoadGPSLGUAcctgCAVBacoor');
//page.events.add.resize('onPageResizeGPSLGUAcctgCAVBacoor');
//page.events.add.submit('onPageSubmitGPSLGUAcctgCAVBacoor');
//page.events.add.cfl('onCFLGPSLGUAcctgCAVBacoor');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctgCAVBacoor');
//page.events.add.reportgetparams('onPageReportGetParamsGPSLGUAcctgCAVBacoor');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctgCAVBacoor');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctgCAVBacoor');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctgCAVBacoor');
page.elements.events.add.validate('onElementValidateGPSLGUAcctgCAVBacoor');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctgCAVBacoor');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctgCAVBacoor');
//page.elements.events.add.change('onElementChangeGPSLGUAcctgCAVBacoor');
//page.elements.events.add.click('onElementClickGPSLGUAcctgCAVBacoor');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctgCAVBacoor');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctgCAVBacoor');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.afterEdit('onTableAfterEditRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctgCAVBacoor');

/*function onPageReportGetParamsGPSLGUAcctgCAVBacoor(formattype,params) {
		var paramids= new Array(),paramtypes= new Array(),paramvaluetypes= new Array(),paramaliases= new Array();
		var docstatus = getInput("docstatus");
		if (getVar("formSubmitAction")=="a") docstatus = "";
		params = getReportLayout(getGlobal("progid2"),formattype,params,docstatus);
		params["source"] = "aspx";
		if (params["reportname"]!=undefined) {
			if (params["querystring"]==undefined) {
				params["querystring"] = "";
				if (params["reportname"]=="JEV") {
					params["querystring"] += generateQueryString("docno",getInput("u_jevno"));
				} else {
					params["querystring"] += generateQueryString("docno",getInput("docno"));
				}
			}	
		}
		return params;
}*/

function onPageLoadGPSLGUAcctgCAVBacoor() {
}

function onPageResizeGPSLGUAcctgCAVBacoor(width,height) {
}

function onPageSubmitGPSLGUAcctgCAVBacoor(action) {
	if (action=="a" || action=="sc") {
	}
	return true;
}

function onCFLGPSLGUAcctgCAVBacoor(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctgCAVBacoor(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctgCAVBacoor() {
}

function onElementFocusGPSLGUAcctgCAVBacoor(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctgCAVBacoor(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctgCAVBacoor(element,column,table,row) {
	switch (table) {
		case "T1":
			break;
		default:
			switch(column) {
				case "docno":
					if (isInputEmpty("u_date")) return false;
					var year = formatDateToDB(getInput("u_date")).substr(0,4);
					var month = formatDateToDB(getInput("u_date")).substr(5,2);
					if (getInput("docno").length>4) {
						setInput("docno",getGlobal("branch")+"-"+year+"-"+month+"-GJ"+getInput("docno"));
					} else {
						setInput("docno",getGlobal("branch")+"-"+year+"-"+month+"-GJ"+getInput("docno").padL(4,"0"));
					}
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctgCAVBacoor(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctgCAVBacoor(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctgCAVBacoor(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUAcctgCAVBacoor(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctgCAVBacoor(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctgCAVBacoor(id,params) {
	return params;
}

function onTableResetRowGPSLGUAcctgCAVBacoor(table) {
}

function onTableBeforeInsertRowGPSLGUAcctgCAVBacoor(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUAcctgCAVBacoor(table,row) {
}

function onTableBeforeUpdateRowGPSLGUAcctgCAVBacoor(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctgCAVBacoor(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctgCAVBacoor(); break;
	}
}

function onTableAfterEditRowGPSLGUAcctgCAVBacoor(table,row) {
}

function onTableBeforeDeleteRowGPSLGUAcctgCAVBacoor(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctgCAVBacoor(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctgCAVBacoor(); break;
	}
}

function onTableBeforeSelectRowGPSLGUAcctgCAVBacoor(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctgCAVBacoor(table,row) {
	var params = new Array();
	return params;
}

