// page events
//page.events.add.load('onPageLoadGPSFixedAsset');
//page.events.add.resize('onPageResizeGPSFixedAsset');
page.events.add.submit('onPageSubmitGPSFixedAsset');
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
	var result;
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_facode")) return false;
		if (isInputEmpty("u_faname")) return false;
		if (isInputNegative("u_newlifem")) return false;
		result = page.executeFormattedSearch("select count(*) from u_fadeprescheds where code='"+getInput("u_facode")+"' and u_posted=1");	
		if (parseInt(result)>=getInputNumeric("u_newlifem")) {
			page.statusbar.showError("New Life cannot be less than or equal use life ["+result+"].");
			return false;
		}
		if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	}
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
	switch(table) {
		default:
			switch(column) {
				case "u_facode":
					result = page.executeFormattedQuery("select * from u_fa where code='"+getInput("u_facode")+"' and (u_salvagevalue>0 or u_bookvalue>0)");	 
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							if (result.childNodes.item(0).getAttribute("u_bookvalue")==0) {
								setInput("u_faname","");	
								setInput("u_faclass","");	
								setInputAmount("u_cost",0);	
								setInputAmount("u_accumdepre",0);	
								setInputAmount("u_salvagevalue",0);	
								setInputAmount("u_bookvalue",0);	
								setInput("u_remainlifem",0);	
								page.statusbar.showError("Asset is already closed.");
								return false;
							}
							setInput("u_facode",result.childNodes.item(0).getAttribute("code"));	
							setInput("u_faname",result.childNodes.item(0).getAttribute("name"));	
							setInput("u_faclass",result.childNodes.item(0).getAttribute("u_faclass"));	
							setInputAmount("u_cost",result.childNodes.item(0).getAttribute("u_cost"));	
							setInputAmount("u_accumdepre",result.childNodes.item(0).getAttribute("u_accumdepre"));	
							setInputAmount("u_salvagevalue",result.childNodes.item(0).getAttribute("u_salvagevalue"));	
							setInputAmount("u_bookvalue",result.childNodes.item(0).getAttribute("u_bookvalue"));	
							setInput("u_remainlifem",result.childNodes.item(0).getAttribute("u_remainlife"));	
						} else {
							setInput("u_faname","");	
							setInput("u_faclass","");	
							setInputAmount("u_cost",0);	
							setInputAmount("u_accumdepre",0);	
							setInputAmount("u_salvagevalue",0);	
							setInputAmount("u_bookvalue",0);	
							setInput("u_remainlifem",0);	
							page.statusbar.showError("Unable to retrieve Fixed Asset profile.");
							return false;
						}
					} else {
						setInput("u_faname","");	
						setInput("u_faclass","");	
						setInputAmount("u_cost",0);	
						setInputAmount("u_accumdepre",0);	
						setInputAmount("u_salvagevalue",0);	
						setInputAmount("u_bookvalue",0);	
						setInput("u_remainlifem",0);	
						alert(result.childNodes.item(0).getAttribute("error"));
						return false;
					}
					break;
			}
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

function onElementCFLGetParamsGPSFixedAsset(id,params) {
	switch(id) {
		case "df_u_facode": 
			if (getPrivate("famgmnt")=="BR") {
				params["params"] = "UDT:U_FA;-WHERE: (U_SALVAGEVALUE>0 OR U_BOOKVALUE>0) AND U_BRANCH='"+getGlobal("branch")+"'"; 
			} else {
				params["params"] = "UDT:U_FA;-WHERE: (U_SALVAGEVALUE>0 OR U_BOOKVALUE>0)"; 
			}
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

