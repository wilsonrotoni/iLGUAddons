// page events
//page.events.add.load('onPageLoadGPSFixedAsset');
//page.events.add.resize('onPageResizeGPSFixedAsset');
//page.events.add.submit('onPageSubmitGPSFixedAsset');
//page.events.add.cfl('onCFLGPSFixedAsset');
//page.events.add.cflgetparams('onCFLGetParamsGPSFixedAsset');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFixedAsset');

// element events
//page.elements.events.add.focus('onElementFocusGPSFixedAsset');
//page.elements.events.add.keydown('onElementKeyDownGPSFixedAsset');
page.elements.events.add.validate('onElementValidateGPSFixedAsset');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFixedAsset');
//page.elements.events.add.changing('onElementChangingGPSFixedAsset');
//page.elements.events.add.change('onElementChangeGPSFixedAsset');
//page.elements.events.add.click('onElementClickGPSFixedAsset');
//page.elements.events.add.cfl('onElementCFLGPSFixedAsset');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFixedAsset');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFixedAsset');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFixedAsset');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFixedAsset');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFixedAsset');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFixedAsset');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFixedAsset');
//page.tables.events.add.delete('onTableDeleteRowGPSFixedAsset');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFixedAsset');
//page.tables.events.add.select('onTableSelectRowGPSFixedAsset');

function onPageLoadGPSFixedAsset() {
}

function onPageResizeGPSFixedAsset(width,height) {
}

function onPageSubmitGPSFixedAsset(action) {
	return true;
}

function onCFLGPSFixedAsset(Id) {
	return true;
}

function onCFLGetParamsGPSFixedAsset(Id,params) {
	return params;
}

function onTaskBarLoadGPSFixedAsset() {
}

function onElementFocusGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSFixedAsset(element,event,column,table,row) {
}

function onElementValidateGPSFixedAsset(element,column,table,row) {
	var result;
	switch (table) {
		case "":
			switch (column) {
				case "u_purchacct":
					if (getInput("u_purchacct")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setInput("u_purchacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setInput("u_purchacct",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_purchacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_purchacctname","");
					}
					break;
				case "u_depreacct":
					if (getInput("u_depreacct")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setInput("u_depreacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setInput("u_depreacct",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_depreacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_depreacctname","");
					}
					break;
				case "u_accumdepreacct":
					if (getInput("u_accumdepreacct")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setInput("u_accumdepreacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setInput("u_accumdepreacct",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_accumdepreacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_accumdepreacctname","");
					}
					break;
				case "u_lossonsaleacct":
					if (getInput("u_lossonsaleacct")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setInput("u_lossonsaleacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setInput("u_lossonsaleacct",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_lossonsaleacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_lossonsaleacctname","");
					}
					break;
				case "u_gainonsaleacct":
					if (getInput("u_gainonsaleacct")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setInput("u_gainonsaleacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setInput("u_gainonsaleacct",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_gainonsaleacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_gainonsaleacctname","");
					}
					break;
				case "u_lossonretireacct":
					if (getInput("u_lossonretireacct")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setInput("u_lossonretireacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setInput("u_lossonretireacct",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_lossonretireacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_lossonretireacctname","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSFixedAsset(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementChangeGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementClickGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementCFLGPSFixedAsset(element) {
	return true;
}

function onElementCFLGetParamsGPSFixedAsset(element,params) {
	switch (element) {
		case "df_u_purchacct":
		case "df_u_depreacct":
		case "df_u_accumdepreacct":
		case "df_u_lossonsaleacct":
		case "df_u_gainonsaleacct":
		case "df_u_lossonretireacct":
			params["params"] = "POSTABLE:1;CTRLACCT:0";
			break;
	}
	return params;
}

function onTableResetRowGPSFixedAsset(table) {
}

function onTableBeforeInsertRowGPSFixedAsset(table) {
	return true;
}

function onTableAfterInsertRowGPSFixedAsset(table,row) {
}

function onTableBeforeUpdateRowGPSFixedAsset(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFixedAsset(table,row) {
}

function onTableBeforeDeleteRowGPSFixedAsset(table,row) {
	return true;
}

function onTableDeleteRowGPSFixedAsset(table,row) {
}

function onTableBeforeSelectRowGPSFixedAsset(table,row) {
	return true;
}

function onTableSelectRowGPSFixedAsset(table,row) {
}

