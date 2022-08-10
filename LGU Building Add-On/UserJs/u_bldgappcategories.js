// page events
//page.events.add.load('onPageLoadGPSLGUBuilding');
//page.events.add.resize('onPageResizeGPSLGUBuilding');
//page.events.add.submit('onPageSubmitGPSLGUBuilding');
//page.events.add.cfl('onCFLGPSLGUBuilding');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUBuilding');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUBuilding');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUBuilding');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUBuilding');
page.elements.events.add.validate('onElementValidateGPSLGUBuilding');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUBuilding');
//page.elements.events.add.changing('onElementChangingGPSLGUBuilding');
//page.elements.events.add.change('onElementChangeGPSLGUBuilding');
//page.elements.events.add.click('onElementClickGPSLGUBuilding');
//page.elements.events.add.cfl('onElementCFLGPSLGUBuilding');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUBuilding');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUBuilding');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUBuilding');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUBuilding');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUBuilding');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUBuilding');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUBuilding');
page.tables.events.add.delete('onTableDeleteRowGPSLGUBuilding');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUBuilding');
page.tables.events.add.select('onTableSelectRowGPSLGUBuilding');

function onPageLoadGPSLGUBuilding() {
}

function onPageResizeGPSLGUBuilding(width,height) {
}

function onPageSubmitGPSLGUBuilding(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_businessname")) return false;
		if (isInputEmpty("u_lastname")) return false;
		if (isInputEmpty("u_firstname")) return false;
		if(getInput("u_appnature")!='Others') if (isInputNegative("u_orsfamt")) return false;
	}
	return true;
}

function onCFLGPSLGUBuilding(Id) {
	return true;
}

function onCFLGetParamsGPSLGUBuilding(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUBuilding() {
}

function onElementFocusGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUBuilding(element,event,column,table,row) {
}

function onElementValidateGPSLGUBuilding(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
                            case "u_quantity":
                                setBuildingPermitFee();
                                break;
                        }
                        break;
		case "T2":
			switch (column) {
                            case "u_quantity":
                                setMechanicalFee();
                                break;
                        }
                        break;
		case "T3":
			switch (column) {
                            case "u_quantity":
                                setPlumbingFee();
                                break;
                        }
                        break;
		case "T4":
			switch (column) {
                            case "u_quantity":
                                setElectricalFee();
                                break;
                        }
                        break;
		case "T5":
			switch (column) {
                            case "u_quantity":
                                setSignageFee();
                                break;
                        }
                        break;
		default:
                        switch (column) {
				case "name":
                                    setInput("code",getInput("name"));
                        }
			break;
	}	
	return true;
}

function onElementGetValidateParamsGPSLGUBuilding(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUBuilding(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUBuilding(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUBuilding(Id,params) {
        switch (Id) {
		
	}	
	return params;
}

function onTableResetRowGPSLGUBuilding(table) {
}

function onTableBeforeInsertRowGPSLGUBuilding(table) {
        switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUBuilding(table,row) { 
        switch (table) {
		case "T1": computeTotalEngineeringAssessment();break;
	}
}

function onTableBeforeUpdateRowGPSLGUBuilding(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUBuilding(table,row) {
        switch (table) {
		case "T1": computeTotalEngineeringAssessment();break;
	}
}

function onTableBeforeDeleteRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUBuilding(table,row) {
        switch (table) {
		case "T1": computeTotalEngineeringAssessment();break;
	}
}

function onTableBeforeSelectRowGPSLGUBuilding(table,row) {
	return true;
}

function onTableSelectRowGPSLGUBuilding(table,row) {
     var params = new Array();
	switch (table) {
		case "T1":
		case "T2":
		case "T3":
		case "T4":
		case "T5":
			if (elementFocused.substring(0,13)=="df_u_quantity") {
				focusTableInput(table,"u_quantity",row);
			} 
			params["focus"]=false;
			break;
	}
	return params;
}


function computeTotalEngineeringAssessment() {
	var rc = getTableRowCount("T1"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
		}
	}
	setInputAmount("u_orsfamt",total);
}

function addslashes(string) {
    return string.replace(/\\/g, '\\\\').
        replace(/\u0008/g, '\\b').
        replace(/\t/g, '\\t').
        replace(/\n/g, '\\n').
        replace(/\f/g, '\\f').
        replace(/\r/g, '\\r').
        replace(/'/g, '\\\'').
        replace(/"/g, '\\"');
}

function setBuildingPermitFee() {
	var buildingpermitfeerc=0;
	var buildingfeetotal=0;
        var rc = getTableRowCount("T6");
        var rc2 = getTableRowCount("T1");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T1",xxx1)==false) {
                        setTableInputAmount("T1","u_linetotal",getTableInputNumeric("T1","u_quantity",xxx1) * getTableInputNumeric("T1","u_unitprice",xxx1),xxx1);
                        buildingfeetotal += getTableInputNumeric("T1","u_linetotal",xxx1);
                }
	}
        setInputAmount("u_bldgtotal",buildingfeetotal);
        
}

function setMechanicalFee() {
	var mechanicalfeerc=0;
	var mechanicalfeetotal=0;
        var rc = getTableRowCount("T6");
        var rc2 = getTableRowCount("T2");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T2",xxx1)==false) {
                        var result = page.executeFormattedQuery("select u_maxunit from u_mechanicalcomputeitems where code = '"+getTableInput("T2","u_code",xxx1)+"' and u_desc = '"+getTableInput("T2","u_desc",xxx1)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (result.childNodes.item(0).getAttribute("u_maxunit") > 0) {
                                    if (getTableInputNumeric("T2","u_quantity",xxx1) > result.childNodes.item(0).getAttribute("u_maxunit")) {
                                        setTableInput("T2","u_quantity",0,xxx1);
                                        page.statusbar.showError("Invalid quantity");
                                    }
                                }
                            } 
                        } 
                        setTableInputAmount("T2","u_linetotal",getTableInputNumeric("T2","u_quantity",xxx1) * getTableInputNumeric("T2","u_unitprice",xxx1),xxx1);
                        mechanicalfeetotal += getTableInputNumeric("T2","u_linetotal",xxx1);
                }
	}
        
        setInputAmount("u_mechtotal",mechanicalfeetotal);
        
}
function setPlumbingFee() {
	var plumbingfeerc=0;
	var plumbingfeetotal=0;
        var rc = getTableRowCount("T3");
        var rc2 = getTableRowCount("T3");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T3",xxx1)==false) {
                        setTableInputAmount("T3","u_linetotal",getTableInputNumeric("T3","u_quantity",xxx1) * getTableInputNumeric("T3","u_unitprice",xxx1),xxx1);
                        plumbingfeetotal += getTableInputNumeric("T3","u_linetotal",xxx1);
                }
	}
        setInputAmount("u_plumbingtotal",plumbingfeetotal);
}
function setElectricalFee() {
	var electricalfeerc=0;
	var electricalfeetotal=0;
        var rc = getTableRowCount("T6");
        var rc2 = getTableRowCount("T4");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T4",xxx1)==false) {
                        var result = page.executeFormattedQuery("select u_isfixamount,u_minkva,u_addperkva from u_electricalcomputeitems where code = '"+getTableInput("T4","u_code",xxx1)+"' and u_desc = '"+getTableInput("T4","u_desc",xxx1)+"'");	
                        if (result.getAttribute("result")!= "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if (result.childNodes.item(0).getAttribute("u_isfixamount") == 1 && getTableInputNumeric("T4","u_quantity",xxx1) > 0 ) {
                                     setTableInputAmount("T4","u_linetotal",getTableInputNumeric("T4","u_unitprice",xxx1),xxx1);
                                } else if (result.childNodes.item(0).getAttribute("u_minkva") > 0 && result.childNodes.item(0).getAttribute("u_addperkva") > 0) {
                                    if (getTableInputNumeric("T4","u_quantity",xxx1) > result.childNodes.item(0).getAttribute("u_minkva")) {
                                            setTableInputAmount("T4","u_linetotal",getTableInputNumeric("T4","u_unitprice",xxx1) + ((getTableInputNumeric("T4","u_quantity",xxx1) - result.childNodes.item(0).getAttribute("u_minkva")) * result.childNodes.item(0).getAttribute("u_addperkva")),xxx1); 
                                    }
                                } else {
                                    setTableInputAmount("T4","u_linetotal",getTableInputNumeric("T4","u_quantity",xxx1) * getTableInputNumeric("T4","u_unitprice",xxx1),xxx1);
                                }
                            } 
                        } 
                        electricalfeetotal += getTableInputNumeric("T4","u_linetotal",xxx1);
                }
	}
        setInputAmount("u_electricaltotal",electricalfeetotal);
	
}
function setSignageFee() {
	var signagefeerc=0;
	var signagefeetotal=0;
        var rc = getTableRowCount("T6");
        var rc2 = getTableRowCount("T5");
	for (xxx1 = 1; xxx1 <=rc2; xxx1++) {
		if (isTableRowDeleted("T5",xxx1)==false) {
                    var result = page.executeFormattedQuery("select u_minprice from u_signagecomputeitems where code = '"+getTableInput("T5","u_code",xxx1)+"' and u_desc = '"+getTableInput("T5","u_desc",xxx1)+"'");	
                    if (result.getAttribute("result")!= "-1") {
                        if (parseInt(result.getAttribute("result"))>0) {
                            if (result.childNodes.item(0).getAttribute("u_minprice") > 0) {
                                var linetotal = 0;
                                linetotal = getTableInputNumeric("T5","u_quantity",xxx1) * getTableInputNumeric("T5","u_unitprice",xxx1);
                                if (result.childNodes.item(0).getAttribute("u_minprice") > parseFloat(linetotal) && getTableInputNumeric("T5","u_quantity",xxx1) > 0 ) { 
                                    setTableInputAmount("T5","u_linetotal",result.childNodes.item(0).getAttribute("u_minprice"),xxx1);
                                } else {
                                    setTableInputAmount("T5","u_linetotal",linetotal,xxx1);
                                }
                            }
                        } 
                    }
                        signagefeetotal += getTableInputNumeric("T5","u_linetotal",xxx1);
                }
	}
        setInputAmount("u_signagetotal",signagefeetotal);
}

