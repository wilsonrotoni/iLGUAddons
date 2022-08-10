// page events
page.events.add.load('onPageLoadLGUMiscellaneous');
//page.events.add.resize('onPageResizeLGUMiscellaneous');
page.events.add.submit('onPageSubmitLGUMiscellaneous');
page.events.add.submitreturn('onPageSubmitReturnLGUMiscellaneous');
//page.events.add.cfl('onCFLLGUMiscellaneous');
//page.events.add.cflgetparams('onCFLGetParamsLGUMiscellaneous');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadLGUMiscellaneous');

// element events
//page.elements.events.add.focus('onElementFocusLGUMiscellaneous');
page.elements.events.add.keydown('onElementKeyDownLGUMiscellaneous');
page.elements.events.add.validate('onElementValidateLGUMiscellaneous');
//page.elements.events.add.validateparams('onElementGetValidateParamsLGUMiscellaneous');
//page.elements.events.add.changing('onElementChangingLGUMiscellaneous');
page.elements.events.add.change('onElementChangeLGUMiscellaneous');
//page.elements.events.add.click('onElementClickLGUMiscellaneous');
//page.elements.events.add.cfl('onElementCFLLGUMiscellaneous');
page.elements.events.add.cflgetparams('onElementCFLGetParamsLGUMiscellaneous');

// table events
//page.tables.events.add.reset('onTableResetRowLGUMiscellaneous');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowLGUMiscellaneous');
page.tables.events.add.afterInsert('onTableAfterInsertRowLGUMiscellaneous');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowLGUMiscellaneous');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowLGUMiscellaneous');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowLGUMiscellaneous');
page.tables.events.add.delete('onTableDeleteRowLGUMiscellaneous');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUMiscellaneous');
//page.tables.events.add.select('onTableSelectRowLGUMiscellaneous');

function onPageLoadLGUMiscellaneous() {
	focusInput("u_custname");
}

function onPageResizeLGUMiscellaneous(width,height) {
}

function onPageSubmitLGUMiscellaneous(action) {
        if (action=="a" || action=="sc") {
		if (isInputEmpty("u_orno")) return false;	
		if (isInputEmpty("u_ordate")) return false;	
		if (isInputEmpty("u_docseries")) return false;	
		if (isInputEmpty("u_custname")) return false;	
		if (isInputEmpty("u_name")) return false;	
		if (isInputEmpty("u_dateofdeath")) return false;	
		if (isInputNegative("u_totalamount")) return false;	
                if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	}
	return true;
}

function onPageSubmitReturnLGUMiscellaneous(action,sucess,error) {
	if (action=="a" && sucess) {
            if(window.confirm("Print Official Receipt. Continue?")) OpenReportSelect('printer'); focusInput("u_address");
	}
}

function onCFLLGUMiscellaneous(Id) {
	return true;
}

function onCFLGetParamsLGUMiscellaneous(Id,params) {

	return params;
}

function onTaskBarLoadLGUMiscellaneous() {
}

function onElementFocusLGUMiscellaneous(element,column,table,row) {
	return true;
}

function onElementKeyDownLGUMiscellaneous(element,event,column,table,row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		case "ENTER":
                        if(column=="u_totalamount") {
                            setBURIALFees();
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

function onElementValidateLGUMiscellaneous(element,column,table,row) {
switch (table) {
		case "T1":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			break;
                        
        default:
            switch (column) {
                case "u_totalamount":
                     setBURIALFees();
                     break;
            }
	}			
	return true;
}

function onElementGetValidateParamsLGUMiscellaneous(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingLGUMiscellaneous(element,column,table,row) {
	return true;
}

function onElementChangeLGUMiscellaneous(element,column,table,row) {
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

function onElementClickLGUMiscellaneous(element,column,table,row) {
	return true;
}

function onElementCFLLGUMiscellaneous(element) {
	return true;
}

function onElementCFLGetParamsLGUMiscellaneous(Id,params) {
    switch (Id) {
      case "df_u_feecodeT1":
        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
        params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
        params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
        params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
        break;
      case "df_u_feedescT1":
        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
        params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
        params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
        params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
        break;
	}		
	return params;
}

function onTableResetRowLGUMiscellaneous(table) {
}

function onTableBeforeInsertRowLGUMiscellaneous(table) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowLGUMiscellaneous(table,row) {
switch (table) {
	case "T1": computeTotalAssessment(); break;
	
	}
}

function onTableBeforeUpdateRowLGUMiscellaneous(table,row) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}

	return true;
}

function onTableAfterUpdateRowLGUMiscellaneous(table,row) {
switch (table) {
	case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeDeleteRowLGUMiscellaneous(table,row) {
	return true;
}

function onTableDeleteRowLGUMiscellaneous(table,row) {
switch (table) {
	case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeSelectRowLGUMiscellaneous(table,row) {
	return true;
}

function onTableSelectRowLGUMiscellaneous(table,row) {
}

//function u_forpaymentLGUMiscellaneous() {
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


function getDocnoPerSeriesGPSPOS(seriesname) {
    var numlen = 0,prefix,suffix, nextno = 0,docno = 0,docno1 = 0,d = new Date();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();

    month = month.toString();
    year = year.toString();
    
        var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME,B.U_ISSUEDOCNO FROM U_LGUPOSREGISTERS A INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID WHERE B.U_DOCSERIES = '"+seriesname+"' AND B.U_NEXTNO <= B.U_LASTNO AND A.COMPANY = '"+getGlobal("company")+"' AND A.BRANCH = '"+getGlobal("branch")+"' AND A.U_STATUS = 'O' AND A.U_USERID = '"+getPrivate("userid")+"' ");
            if (result.getAttribute("result")!= "-1") {
                if (parseInt(result.getAttribute("result"))>0) {
                        numlen = result.childNodes.item(0).getAttribute("u_numlen");
                        prefix = result.childNodes.item(0).getAttribute("u_prefix");
                        suffix = result.childNodes.item(0).getAttribute("u_suffix");
                        nextno = result.childNodes.item(0).getAttribute("u_nextno");

                        nextno = nextno.toString();

                        prefix = prefix.replace("{POS}",getInput("u_terminalid"));
                        suffix = suffix.replace("{POS}",getInput("u_terminalid"));
                        prefix = prefix.replace("[m]",month.padStart1(2,'0'));
                        suffix = suffix.replace("[m]",month.padStart1(2,'0'));
                        prefix = prefix.replace("[Y]",year);
                        suffix = suffix.replace("[Y]",year);
                        prefix = prefix.replace("[y]",year.padStart1(2,'0'));
                        suffix = suffix.replace("[y]",year.padStart1(2,'0'));

                        docno = prefix + nextno.padStart1(numlen,'0') + suffix;
                        setInput("u_seriesdocno",result.childNodes.item(0).getAttribute("u_docseriesname"));
                        setInput("u_issuedocno",result.childNodes.item(0).getAttribute("u_issuedocno"));
                        setInput("u_orno",docno);
                }
            }
}

if (!String.prototype.padStart1) {
    String.prototype.padStart1 = function padStart1(targetLength, padString) {
        targetLength = targetLength >> 0; //truncate if number, or convert non-number to 0;
        padString = String(typeof padString !== 'undefined' ? padString : ' ');
        if (this.length >= targetLength) {
            return String(this);
        } else {
            targetLength = targetLength - this.length;
            if (targetLength > padString.length) {
                padString += padString.repeat(targetLength / padString.length); //append to original to ensure we are longer than needed
            }
            return padString.slice(0, targetLength) + String(this);
        }
    }
}

function setBURIALFees(){
    
    var burialfeecoderc=0, rc = getTableRowCount("T1");
    
    if (isInputNegative("u_totalamount")) return false;
    
    for (xxx = 1; xxx <=rc; xxx++) {
        if (isTableRowDeleted("T1",xxx)==false) {
            if (getTableInput("T1","u_feecode",xxx)==getPrivate("burialpermitfeecode")) {
                    setTableInputAmount("T1","u_amount",getInput("u_totalamount"),xxx);
                    burialfeecoderc=xxx;
            }
        }
    }
  
    if(getPrivate("burialpermitfeecode")!=""){
         if (burialfeecoderc==0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("burialpermitfeecode");
            data["u_feedesc"] = getPrivate("burialpermitfeename");
            data["u_amount"] = formatNumericAmount(getInputNumeric("u_totalamount"));
            insertTableRowFromArray("T1",data);
        }
    }else{
        page.statusbar.showError("Setup for Burial Permit Fee Not Found. Please update settings.");
        return false;
    }
    
   
}

function PostTransaction() {
    setInput("docstatus","C");
    formSubmit();
}

