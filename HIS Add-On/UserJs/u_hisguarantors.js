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
//page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_type")) return false;
		if (isInputEmpty("name")) return false;
		if (getInput("u_type")=="Account") {
			if (isInputEmpty("u_glacctno")) return false;
			if (isInputEmpty("u_glacctname")) return false;
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
	switch (table) {
		default:
			switch(column) {
				case "u_glacctno":
					if (getInput("u_glacctno") != "") { 
						if (getInput("u_hmo")!="Account")	{
							result = ajaxxmlvalidatechartofaccounts(element.value,"POSTABLE:1;CTRLACCT:1;ASSET");
							if (result.getAttribute("result") == '0') {
								setStatusMsg('Invalid Control Account!');
								return false;
							}	
						} else {
							result = ajaxxmlvalidatechartofaccounts(element.value,"POSTABLE:1;CTRLACCT:0");
							if (result.getAttribute("result") == '0') {
								setStatusMsg('Invalid G/L Account!');
								return false;
							}	
						}
						setInput("u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_glacctname","");
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
			switch (column) {
				case "u_series":
					if (getInput("u_series")!=-1) {
						setInput("u_type",getInputSelectedText("u_series"));
					}
					setDocNo(true,"u_series","code");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_type":
					if (getInput("u_series")!=-1) {
						setInputSelectedText("u_series",getInput("u_type"));
						setDocNo(true,"u_series","code");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_glacctno":
			if (getInput("u_type")!="Account")	params["params"] = "POSTABLE:1;CTRLACCT:1;ASSET";
			else params["params"] = "POSTABLE:1;CTRLACCT:0";
			break;
		case "df_u_icdcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisicds where u_level>2")); 
			break;
		case "df_u_icddescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisicds where u_level>2")); 
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
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
}

