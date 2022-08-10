// page events
//page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSRPTAS');

// element events
//page.elements.events.add.focus('onElementFocusGPSRPTAS');
page.elements.events.add.keydown('onElementKeyDownGPSRPTAS');
page.elements.events.add.validate('onElementValidateGPSRPTAS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSRPTAS');
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
//page.elements.events.add.change('onElementChangeGPSRPTAS');
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
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
        if (action=="a" || action=="sc") {
            //if (isInputEmpty("u_cashierby")) return false;
            if (isInputNegative("u_totalamount")) return false;
        }
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
    switch (column) {
			case "u_ordate":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
					computeTotalAmount();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
		}
}

function onElementValidateGPSRPTAS(element,column,table,row) {
        switch (table) {
            default:
                    switch (column) {
                        case "u_ordate":
                            clearTable("T1");
                            setInputAmount("u_totalamount",0);
                            setInputAmount("u_selectedtotalamount",0);
                            break;
                    }
                break;
        }
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
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
    switch (table) {
        case "T1":
                if (row==0) {
                    if (isTableInputChecked(table,column)) {
                            checkedTableInput(table,column,-1);
                    } else {
                            uncheckedTableInput(table,column,-1);
                    }
                }else{
                    
                }
                computeSelectedTotalAmount();
            break;
    }
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
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
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
        var params = new Array();
	params["focus"] = false;
	return params;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

function formSearchNow() {
    
      //  if (getInput("u_cashierby")=="" && getInput("u_ordatefr")=="" && getInput("u_ordateto")=="" && getInput("u_orto")=="" && getInput("u_orfr")=="" ){
            if (isInputEmpty("u_ordate")) return false;
      //  }
        
        clearTable("T1",true);
        var result = page.executeFormattedQuery("SELECT DOCNO,U_ISVALIDATED,U_ORNUMBER,U_ORDATE,U_PAIDBY,U_TDNO,U_YEARFROM,U_YEARTO,U_TOTALTAXAMOUNT,(U_DISCAMOUNT + U_SEFDISCAMOUNT) AS DISCOUNT, (U_PENALTY + U_SEFPENALTY) AS PENALTY , (U_TAX + U_SEFTAX) AS TAXAMOUNT FROM U_RPTAXES WHERE U_ISUPLOAD = 1 AND COMPANY = '"+getGlobal("company")+"' AND BRANCH = '"+getGlobal("branch")+"' AND U_ORDATE  = '"+formatDateToDB(getInput("u_ordate"))+"' ORDER BY U_ORNUMBER");	
        if (result.getAttribute("result")!= "-1") {
                if (parseInt(result.getAttribute("result"))>0) {
                        clearTable("T1",true);
                        var data = new Array();
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                    data["u_refno"] = result.childNodes.item(xxx).getAttribute("docno");
                                    data["u_selected"] = result.childNodes.item(xxx).getAttribute("u_isvalidated");
                                    data["u_ornumber"] = result.childNodes.item(xxx).getAttribute("u_ornumber");
                                    data["u_ordate"] = formatDateToHttp(result.childNodes.item(xxx).getAttribute("u_ordate"));
                                    data["u_paidby"] = result.childNodes.item(xxx).getAttribute("u_paidby");
                                    data["u_tdno"] = result.childNodes.item(xxx).getAttribute("u_tdno");
                                    data["u_yearfrom"] = result.childNodes.item(xxx).getAttribute("u_yearfrom");
                                    data["u_yearto"] = result.childNodes.item(xxx).getAttribute("u_yearto");
                                    data["u_totaltax"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("taxamount"));
                                    data["u_totaldiscount"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("discount"));
                                    data["u_totalpenalty"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("penalty"));
                                    data["u_totalamount"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_totaltaxamount"));
                                    insertTableRowFromArray("T1",data);
                            }
                } else {
                        clearTable("T1",true);
                        setStatusMsg("No record found.",4000,1);
                        return false;
                }
                
        } else {
  
                clearTable("T1",true);
                page.statusbar.showError("Error retrieving record. Try Again, if problem persists, check the connection.");
                return false;
        }
        
   computeTotalAmount();
   computeSelectedTotalAmount();
    
        
}

function ApplyRemittanceDate() {
    
   var rc =  getTableRowCount("T1");
   for (xxx = 1; xxx <= rc; xxx++) {
            if (isTableRowDeleted("T1",xxx)==false) {
                    setTableInput("T1","u_remittancedate",getInput("u_remittancedate"),xxx);
                }
            }
}

function computeTotalAmount() {
    var rc = getTableRowCount("T1"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                        total+= getTableInputNumeric("T1","u_totalamount",i);
		}
	}
	setInputAmount("u_totalamount",total);
}
function computeSelectedTotalAmount() {
    var rc = getTableRowCount("T1"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                    if (getTableInputNumeric("T1","u_selected",i)=="1") {
                         total+= getTableInputNumeric("T1","u_totalamount",i);
                    }
		}
	}
	setInputAmount("u_selectedtotalamount",total);
}
function u_approveGPSRPTAS() {
    
    var rc = getTableRowCount("T1");
    if (rc==0) {
        page.statusbar.showError("No record found.");
        return false;
    } else if (getInputNumeric("u_totalamount")!=getInputNumeric("u_selectedtotalamount")) {
        page.statusbar.showError("Please check all documents.");
        return false;
    }
    if (confirm("Are you sure you want to Approve. Continue?")) {
        setInput("docstatus","Approved");
        if (getInput("docstatus") == "O") { 
            formSubmit('a');
        } else {
            formSubmit('sc');
        }
       
    }
        
}
function u_disapproveGPSRPTAS() {
    var rc = getTableRowCount("T1");
    if (rc==0) {
        page.statusbar.showError("No record found.");
        return false;
    } else { 
        if (getInputNumeric("u_totalamount")!=getInputNumeric("u_selectedtotalamount")) {
            if (confirm("Are you sure you want to Disapprove. Continue?")) {
                setInput("docstatus","Disapproved");
                if (getInput("docstatus") == "O") { 
                    formSubmit('a');
                } else {
                    formSubmit('sc');
                }
            }
        } else {
            page.statusbar.showWarning("You cannot disapprove if selected total is equal to total amount.");
            return false;
        } 
    }
}
function u_pendingGPSRPTAS() {
    var rc = getTableRowCount("T1");
    if (rc==0) {
        page.statusbar.showError("No record found.");
        return false;
    } else { 
        if (confirm("Confirm pending. Continue?")) {
            setInput("docstatus","Pending");
            if (getInput("docstatus") == "O") { 
                formSubmit('a');
            } else {
                formSubmit('sc');
            }
        }
    }
}
