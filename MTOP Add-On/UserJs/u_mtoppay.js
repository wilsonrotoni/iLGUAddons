// page events
//page.events.add.load('onPageLoadGPSMTOP');
//page.events.add.resize('onPageResizeGPSMTOP');
page.events.add.submit('onPageSubmitGPSMTOP');
//page.events.add.cfl('onCFLGPSMTOP');
//page.events.add.cflgetparams('onCFLGetParamsGPSMTOP');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSMTOP');

// element events
//page.elements.events.add.focus('onElementFocusGPSMTOP');
//page.elements.events.add.keydown('onElementKeyDownGPSMTOP');
//page.elements.events.add.validate('onElementValidateGPSMTOP');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSMTOP');
//page.elements.events.add.changing('onElementChangingGPSMTOP');
//page.elements.events.add.change('onElementChangeGPSMTOP');
page.elements.events.add.click('onElementClickGPSMTOP');
//page.elements.events.add.cfl('onElementCFLGPSMTOP');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSMTOP');

// table events
//page.tables.events.add.reset('onTableResetRowGPSMTOP');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSMTOP');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSMTOP');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSMTOP');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSMTOP');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSMTOP');
//page.tables.events.add.delete('onTableDeleteRowGPSMTOP');
page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSMTOP');
page.tables.events.add.select('onTableSelectRowGPSMTOP');

function onPageLoadGPSMTOP() {
}

function onPageResizeGPSMTOP(width,height) {
}

function onPageSubmitGPSMTOP(action) {
    if (action=="a" || action=="sc") {
		if (isInputEmpty("u_operatorname")) return false;
		if (isInputNegative("u_totalamount")) return false;
	}
	return true;
}

function onCFLGPSMTOP(Id) {
	return true;
}

function onCFLGetParamsGPSMTOP(Id,params) {
	return params;
}

function onTaskBarLoadGPSMTOP() {
}

function onElementFocusGPSMTOP(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSMTOP(element,event,column,table,row) {
}

function onElementValidateGPSMTOP(element,column,table,row) {
   
	return true;
}

function onElementGetValidateParamsGPSMTOP(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSMTOP(element,column,table,row) {
	return true;
}

function onElementChangeGPSMTOP(element,column,table,row) {
	return true;
}

function onElementClickGPSMTOP(element,column,table,row) {
    switch (table) {
		case "T1":
			if (row==0) {
				if (isTableInputChecked(table,column)) {
					checkedTableInput(table,column,-1);
				} else {
					uncheckedTableInput(table,column,-1);
				}
			} else {
				
					var pin=getTableInput("T1","billno",row);
					var selected=getTableInput("T1","u_selected",row);
					var rc =  getTableRowCount("T1");
					for (xxx = 1; xxx <= rc; xxx++) {
						if (isTableRowDeleted("T1",xxx)==false) {
							if (getTableInput("T1","u_billno",xxx)==pin) {
								setTableInput("T1","u_selected",selected,xxx);
							}
						}
					}
				
			}
			computeTotal();
			break;
		
	}
	return true;
}

function onElementCFLGPSMTOP(element) {
	return true;
}

function onElementCFLGetParamsGPSMTOP(element,params) {
	return params;
}

function onTableResetRowGPSMTOP(table) {
}

function onTableBeforeInsertRowGPSMTOP(table) {
	return true;
}

function onTableAfterInsertRowGPSMTOP(table,row) {
}

function onTableBeforeUpdateRowGPSMTOP(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSMTOP(table,row) {
}

function onTableBeforeDeleteRowGPSMTOP(table,row) {
	return true;
}

function onTableDeleteRowGPSMTOP(table,row) {
}

function onTableBeforeSelectRowGPSMTOP(table,row) {
	return true;
}

function onTableSelectRowGPSMTOP(table,row) {
    var params = Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			break;
	}
	return params;
}
function formSearch(){
    getBills();
    return true;
}
function getBills() {
	var data = new Array(), total = 0,assval= 0;
        clearTable("T1",true);
	
        if (getInput("u_declaredowner")!="") {
		
                var result = page.executeFormattedQuery("Select u_appno,u_custname,u_custno,docno,u_totalamount from u_lgubills where u_profitcenter = 'MTOP' and u_settledamount = 0 AND DOCSTATUS IN('O') and company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_custname like '%"+getInput("u_declaredowner")+"%'");
                
                       
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				
				data["u_selected"] = 0;
				data["u_custname"] = result.childNodes.item(xxx).getAttribute("u_custname");
				data["u_bodyno"] = result.childNodes.item(xxx).getAttribute("u_custno");
				data["u_billno"] = result.childNodes.item(xxx).getAttribute("docno");
				data["u_appno"] = result.childNodes.item(xxx).getAttribute("u_appno");
				data["u_asstotal"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_totalamount"),2);
//                                assval = parseFloat(result.childNodes.item(xxx).getAttribute("u_totalamount"))
//                                total+= assval;
//                                alert(total);
                                insertTableRowFromArray("T1",data);

                        }
		}
			
	}
//	setInputAmount("u_totalamount",total);
}

function computeTotal() {
	var rc =  getTableRowCount("T1"), total=0;
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				total+= getTableInputNumeric("T1","u_asstotal",xxx);
				}
		}
	}
	setInputAmount("u_totalamount",total);
}
