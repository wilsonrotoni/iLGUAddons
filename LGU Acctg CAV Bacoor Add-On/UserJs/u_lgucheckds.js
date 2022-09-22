// page events
page.events.add.load('onPageLoadGPSLGUAcctgCAVBacoor');
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
//page.elements.events.add.validate('onElementValidateGPSLGUAcctgCAVBacoor');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctgCAVBacoor');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctgCAVBacoor');
page.elements.events.add.change('onElementChangeGPSLGUAcctgCAVBacoor');
//page.elements.events.add.click('onElementClickGPSLGUAcctgCAVBacoor');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctgCAVBacoor');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctgCAVBacoor');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctgCAVBacoor');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctgCAVBacoor');

function onPageReportGetParamsGPSLGUAcctgCAVBacoor(formattype,params) {
		return params;
}

function onPageLoadGPSLGUAcctgCAVBacoor() {
	switch (getGlobal("branch")) {
		case "101": setInputSelectedText("docseries","GF",true); break;
		case "221": setInputSelectedText("docseries","SEF",true); break;
		case "401": setInputSelectedText("docseries","TF",true); break;
	}
}

function onPageResizeGPSLGUAcctgCAVBacoor(width,height) {
}

function onPageSubmitGPSLGUAcctgCAVBacoor(action) {

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
		default:
			switch(column) {
				case "docno":
					if (isInputEmpty("u_date")) return false;
					//if (isInputEmpty("u_checkbank")) return false;
					var year = formatDateToDB(getInput("u_date")).substr(2,2);
					var month = formatDateToDB(getInput("u_date")).substr(5,2);
					//var bank = getInput("u_checkbank");
					//if (bank=="") bank = "XXX";
					//if (getInput("docno").length>4) {
					//	setInput("docno",bank+"-"+getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("docno"));
					//} else {
					//	setInput("docno",bank+"-"+getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("docno").padL(4,"0"));
					//}
					if (getInput("docno").length>4) {
						setInput("docno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("docno"));
					} else {
						setInput("docno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("docno").padL(4,"0"));
					}
					break;
				case "u_jevno":
					if (getInput("u_jevno")!="" && getInput("u_jevno")!="?") {
						if (isInputEmpty("u_date")) return false;
						if (isInputEmpty("u_jevseries")) return false;
						var year = formatDateToDB(getInput("u_date")).substr(0,4);
						var month = formatDateToDB(getInput("u_date")).substr(5,2);
						//setInput("u_jevno",getGlobal("branch")+"-"+getInput("u_jevseries")+"-"+year+"-"+month+"-"+getInput("u_jevno").padL(4,"0"));
						if (getInput("u_jevno").length>4) {
							setInput("u_jevno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("u_jevno"));
						} else {
							setInput("u_jevno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("u_jevno").padL(4,"0"));
						}
						
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
	switch (table) {
		default:
			switch (column) {
				case "u_checkbank":
					ajaxloadhousebankaccounts("df_u_checkbankacctno",getGlobal("branch"),"PH",getInput("u_checkbank"),'',":");
					if (getInputNumeric("docseries")!=-1) {
					//	setDocNo(null,null,null,"u_date");
					} else {
					//	setInput("docno","");
					}
					setInput("u_jevseries","-1");
//					var bank = getInput("u_checkbank");
//					if (bank=="") bank = "XXX";
//					if (getInput("docno")!="") {
//						setInput("docno",bank+"-"+getInput("docno").substr(4,30));
//					}
//					break;
					break;
				case "u_checkbankacctno":
					setInputSelectedText("u_jevseries",getInput("u_checkbank")+":"+getInput("u_checkbankacctno"));
//					var bank = getInput("u_checkbank");
//					if (bank=="") bank = "XXX";
//					if (getInput("docno")!="") {
//						setInput("docno",bank+"-"+getInput("docno").substr(4,30));
//					}
//					break;
					break;
			}
			break;
	}
		
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
}

function onTableBeforeDeleteRowGPSLGUAcctgCAVBacoor(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctgCAVBacoor(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctgCAVBacoor(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctgCAVBacoor(table,row) {
	var params = new Array();
	return params;
}

