// page events
page.events.add.load('onPageLoadCTC');
//page.events.add.resize('onPageResizeCTC');
page.events.add.submit('onPageSubmitCTC');
page.events.add.submitreturn('onPageSubmitReturnCTC');
//page.events.add.cfl('onCFLCTC');
//page.events.add.cflgetparams('onCFLGetParamsCTC');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadCTC');

// element events
//page.elements.events.add.focus('onElementFocusCTC');
page.elements.events.add.keydown('onElementKeyDownCTC');
page.elements.events.add.validate('onElementValidateCTC');
//page.elements.events.add.validateparams('onElementGetValidateParamsCTC');
//page.elements.events.add.changing('onElementChangingCTC');
page.elements.events.add.change('onElementChangeCTC');
//page.elements.events.add.click('onElementClickCTC');
//page.elements.events.add.cfl('onElementCFLCTC');
page.elements.events.add.cflgetparams('onElementCFLGetParamsCTC');

// table events
//page.tables.events.add.reset('onTableResetRowCTC');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowCTC');
page.tables.events.add.afterInsert('onTableAfterInsertRowCTC');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowCTC');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowCTC');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowCTC');
page.tables.events.add.delete('onTableDeleteRowCTC');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowCTC');
//page.tables.events.add.select('onTableSelectRowCTC');

function onPageLoadCTC() {
	focusInput("u_custname");
}

function onPageResizeCTC(width,height) {
}

function onPageSubmitCTC(action) {
        if (action=="a" || action=="sc") {
		if (isInputEmpty("u_barangay")) return false;	
		if (isInputEmpty("u_date")) return false;	
		if (isInputNegative("u_totalamount")) return false;	
                if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	}
	return true;
}

function onPageSubmitReturnCTC(action,sucess,error) {
	if (action=="a" && sucess) {
//            if(window.confirm("Print Official Receipt. Continue?")) OpenReportSelect('printer');  focusInput("u_address");
	}
}

function onCFLCTC(Id) {
	return true;
}

function onCFLGetParamsCTC(Id,params) {

	return params;
}

function onTaskBarLoadCTC() {
}

function onElementFocusCTC(element,column,table,row) {
	return true;
}

function onElementKeyDownCTC(element,event,column,table,row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		case "ENTER":
                        if(column=="u_gross") {
                            setCTCFees();
                        }
			break;
		case "F4":
			PostTransaction();
			break;
                case "CTRL+ENTER":
			formSubmit("?");
			break;
	
		
	}
}

function onElementValidateCTC(element,column,table,row) {
switch (table) {
		case "T1":
			switch (column) {
				 
                            case "u_usedqty":
                                if(getTableInput(table,column)!=""){
                                    if (getTableInputNumeric(table,column) > getTableInputNumeric(table,"u_availableqty")) {
                                        page.statusbar.showError("Invalid used quantity");
                                        return false;
                                    } 
                                }
                                break;
                            case "u_receiptfrm":
                            case "u_receiptto":
                                if(getTableInput(table,column)!=""){
                                    if(column=="u_receiptfrm") var result = page.executeFormattedQuery("select * from u_lgureceiptitems where u_receiptfrm='"+getTableInput(table,column)+"' and u_form like '%"+getTableInput(table,"u_form")+"%' AND U_ISSUEDTO = '"+getInput("u_barangay")+"' ");
                                    else var result = page.executeFormattedQuery("select * from u_lgureceiptitems where u_receiptto='"+getTableInput(table,column)+"' and u_form like '%"+getTableInput(table,"u_form")+"%' AND U_ISSUEDTO = '"+getInput("u_barangay")+"' ");

                                    if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {
                                            setTableInput(table,"u_docseries",result.childNodes.item(0).getAttribute("u_refno"));
                                            setTableInput(table,"u_receiptlineid",result.childNodes.item(0).getAttribute("lineid"));
                                            setTableInput(table,"u_receiptfrm",result.childNodes.item(0).getAttribute("u_receiptfrm"));
                                            setTableInput(table,"u_receiptto",result.childNodes.item(0).getAttribute("u_receiptto"));
                                            setTableInput(table,"u_form",result.childNodes.item(0).getAttribute("u_form"));
                                            setTableInput(table,"u_availableqty",result.childNodes.item(0).getAttribute("u_available"));
                                        } else {
                                            setTableInput(table,"u_docseries","");
                                            setTableInput(table,"u_receiptlineid",0);
                                            setTableInput(table,"u_receiptfrm","");
                                            setTableInput(table,"u_receiptto","");
                                            setTableInput(table,"u_form","");
                                            setTableInput(table,"u_availableqty",0);
                                            page.statusbar.showError("Invalid Receipt");	
                                            return false;
                                        }
                                    } else {
                                            setTableInput(table,"u_docseries","");
                                            setTableInput(table,"u_receiptlineid",0);
                                            setTableInput(table,"u_receiptfrm","");
                                            setTableInput(table,"u_receiptto","");
                                            setTableInput(table,"u_form","");
                                            setTableInput(table,"u_availableqty",0);
                                            page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                            return false;
                                    }

                                }

                            break;
                            case "u_receiptlineid":
                                if (getTableInput(column)!="") {
                                    var result = page.executeFormattedQuery("select * from u_lgureceiptitems where lineid='"+getTableInput(table,column)+"' AND U_ISSUEDTO = '"+getInput("u_barangay")+"'");
                                    if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {
                                            setTableInput(table,"u_docseries",result.childNodes.item(0).getAttribute("u_refno"));
                                            setTableInput(table,"u_receiptfrm",result.childNodes.item(0).getAttribute("u_receiptfrm"));
                                            setTableInput(table,"u_receiptto",result.childNodes.item(0).getAttribute("u_receiptto"));
                                            setTableInput(table,"u_form",result.childNodes.item(0).getAttribute("u_form"));
                                            setTableInput(table,"u_availableqty",result.childNodes.item(0).getAttribute("u_available"));
                                        } else {
                                            setTableInput(table,"u_docseries","");
                                            setTableInput(table,"u_receiptlineid",0);
                                            setTableInput(table,"u_receiptfrm","");
                                            setTableInput(table,"u_receiptto","");
                                            setTableInput(table,"u_form","");
                                            setTableInput(table,"u_availableqty",0);
                                            page.statusbar.showError("Invalid Receipt ID");	
                                            return false;
                                        }
                                    } else {
                                            setTableInput(table,"u_docseries","");
                                            setTableInput(table,"u_receiptlineid",0);
                                            setTableInput(table,"u_receiptfrm","");
                                            setTableInput(table,"u_receiptto","");
                                            setTableInput(table,"u_form","");
                                            setTableInput(table,"u_availableqty",0);
                                            page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                            return false;
                                    }
                                }
                    }
			break;
	}			
	return true;
}

function onElementGetValidateParamsCTC(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingCTC(element,column,table,row) {
	return true;
}

function onElementChangeCTC(element,column,table,row) {
        switch (table) {
		default:
			switch(column) {
                                case "u_docseries":
                                    if (getInput(column)!="") {
                                        getDocnoPerSeriesGPSPOS(getInput(column));
                                    }
                                break;
			}
	}
	return true;
}

function onElementClickCTC(element,column,table,row) {
	return true;
}

function onElementCFLCTC(element) {
	return true;
}

function onElementCFLGetParamsCTC(Id,params) {
    switch (Id) {
                case "df_u_receiptfrmT1":
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_receiptfrm,u_receiptto,u_noofreceipt from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_barangay")+"' ")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("From`To`No")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`20`10")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
                    break;
                case "df_u_receipttoT1":
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_receiptto,u_receiptfrm,u_noofreceipt from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_barangay")+"' ")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("To`From`No")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`20`10")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
                    break;
                case "df_u_receiptlineidT1":
                    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select lineid,u_receiptfrm,u_receiptto,u_form,u_noofreceipt from u_lgureceiptitems WHERE U_AVAILABLE > 0 AND U_ISSUEDTO = '"+getInput("u_barangay")+"'")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Receipt ID`From`To`Form Type`No")); 			
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`20`20`10`10")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 	
                    break;
	}		
	return params;
}

function onTableResetRowCTC(table) {
}

function onTableBeforeInsertRowCTC(table) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_receiptlineid")) return false;
			if (isTableInputNegative(table,"u_usedqty")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowCTC(table,row) {
switch (table) {
	case "T1": u_computeTotalCTC(); break;
	
	}
}

function onTableBeforeUpdateRowCTC(table,row) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_receiptlineid")) return false;
			if (isTableInputNegative(table,"u_usedqty")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}

	return true;
}

function onTableAfterUpdateRowCTC(table,row) {
switch (table) {
	case "T1": u_computeTotalCTC(); break;
	}
}

function onTableBeforeDeleteRowCTC(table,row) {
	return true;
}

function onTableDeleteRowCTC(table,row) {
switch (table) {
	case "T1": u_computeTotalCTC(); break;
	}
}

function onTableBeforeSelectRowCTC(table,row) {
	return true;
}

function onTableSelectRowCTC(table,row) {
}

//function u_forpaymentCTC() {
//    if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
//    if (isInputEmpty("u_date",null,null,"tab1",0)) return false;
//    if (isTableInputNegative("u_ctcapps","u_asstotal")) return false;
//    //if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
//  var rc = getTableRowCount("T1"),total=0;
//	for (i = 1; i <= rc; i++) {
//		if (isTableRowDeleted("T1",i)==false) {
//			total+= getTableInputNumeric("T1","u_amount",i);
//		}
//	}
//	if (total < 1){
//	 page.statusbar.showError("Total amount is invalid");
//	  selectTab("tab1",1);
//      return false;
//	}
//    if (getTableRowCount("T1")==0) {
//      page.statusbar.showError("At least one fee or charges must be entered.");
//      selectTab("tab1",1);
//      return false;
//    }
//    if (window.confirm("Are you sure?")==false) {
//		return false;
//    }
//	
//	formSubmit("sc");
//}




function u_computeTotalCTC() {
	var totalamount=0,vatamount=0,adddiscount=0,adddiscamount=0,taxamt=0,qty=0,price=0,amount=0,taxrate=0,rc=0,othercharges=0,roundamount=0,lineadddisc=0,wtaxamount=0,wtaxtxs=0,wtaxnet=0,wtaxenabled="0",totalamount=0,misccharges=0,amountafterdisc=0,taxableamount=0,totalquantity=0,penaltyamount=0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			amount += getTableInputNumeric("T1","u_amount",i);
		}
	}
	setInputAmount("u_totalamount",amount);
}
function PostTransaction() {
    setInput("docstatus","C");
    formSubmit();
}

