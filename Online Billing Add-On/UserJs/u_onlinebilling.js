// page events
page.events.add.load('onPageLoadOnlineBilling');
//page.events.add.resize('onPageResizeOnlineBilling');
page.events.add.submit('onPageSubmitOnlineBilling');
page.events.add.submitreturn('onPageSubmitReturnOnlineBilling');
//page.events.add.cfl('onCFLOnlineBilling');
//page.events.add.cflgetparams('onCFLGetParamsOnlineBilling');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadOnlineBilling');

// element events
//page.elements.events.add.focus('onElementFocusOnlineBilling');
page.elements.events.add.keydown('onElementKeyDownOnlineBilling');
page.elements.events.add.validate('onElementValidateOnlineBilling');
//page.elements.events.add.validateparams('onElementGetValidateParamsOnlineBilling');
//page.elements.events.add.changing('onElementChangingOnlineBilling');
page.elements.events.add.change('onElementChangeOnlineBilling');
//page.elements.events.add.click('onElementClickOnlineBilling');
//page.elements.events.add.cfl('onElementCFLOnlineBilling');
page.elements.events.add.cflgetparams('onElementCFLGetParamsOnlineBilling');

// table events
//page.tables.events.add.reset('onTableResetRowOnlineBilling');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowOnlineBilling');
page.tables.events.add.afterInsert('onTableAfterInsertRowOnlineBilling');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowOnlineBilling');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowOnlineBilling');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowOnlineBilling');
page.tables.events.add.delete('onTableDeleteRowOnlineBilling');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowOnlineBilling');
//page.tables.events.add.select('onTableSelectRowOnlineBilling');

function onPageLoadOnlineBilling() {
	focusInput("u_custname");
}

function onPageResizeOnlineBilling(width,height) {
}

function onPageSubmitOnlineBilling(action) {
        if (action=="a" || action=="sc") {
   		if (isInputEmpty("u_module")) return false;	
		if (isInputEmpty("u_docseries")) return false;	
		if (isInputEmpty("u_custname")) return false;	
		if (isInputNegative("u_totalamount")) return false;	
                if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	}
	return true;
}

function onPageSubmitReturnOnlineBilling(action,sucess,error) {
	if (action=="a" && sucess) {
            if(window.confirm("Print Official Receipt. Continue?")) OpenReportSelect('printer');  focusInput("u_address");
	}
}

function onCFLOnlineBilling(Id) {
	return true;
}

function onCFLGetParamsOnlineBilling(Id,params) {

	return params;
}

function onTaskBarLoadOnlineBilling() {
}

function onElementFocusOnlineBilling(element,column,table,row) {
	return true;
}

function onElementKeyDownOnlineBilling(element,event,column,table,row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		case "ENTER":
                        if(column=="u_gross") {
                            setOnlineBillingFees();
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

function onElementValidateOnlineBilling(element,column,table,row) {
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
	}			
	return true;
}

function onElementGetValidateParamsOnlineBilling(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingOnlineBilling(element,column,table,row) {
	return true;
}

function onElementChangeOnlineBilling(element,column,table,row) {
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

function onElementClickOnlineBilling(element,column,table,row) {
	return true;
}

function onElementCFLOnlineBilling(element) {
	return true;
}

function onElementCFLGetParamsOnlineBilling(Id,params) {
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

function onTableResetRowOnlineBilling(table) {
}

function onTableBeforeInsertRowOnlineBilling(table) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowOnlineBilling(table,row) {
switch (table) {
	case "T1": u_computeTotalOnlineBilling(); break;
	
	}
}

function onTableBeforeUpdateRowOnlineBilling(table,row) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}

	return true;
}

function onTableAfterUpdateRowOnlineBilling(table,row) {
switch (table) {
	case "T1": u_computeTotalOnlineBilling(); break;
	}
}

function onTableBeforeDeleteRowOnlineBilling(table,row) {
	return true;
}

function onTableDeleteRowOnlineBilling(table,row) {
switch (table) {
	case "T1": u_computeTotalOnlineBilling(); break;
	}
}

function onTableBeforeSelectRowOnlineBilling(table,row) {
	return true;
}

function onTableSelectRowOnlineBilling(table,row) {
}

//function u_forpaymentOnlineBilling() {
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

function setOnlineBillingFees(){
    
    var ctcgrossfeecoderc=0,ctcbasicfeecoderc=0,grossamount = 0,basicamount = 0,ctcpenamount = 0, rc = getTableRowCount("T1");
    
    if (isInputEmpty("u_apptype")) return false;
    if (isInputNegative("u_gross")) return false;
    if(getInput("u_apptype") == "I"){
			grossamount = getInputNumeric("u_gross") / 1000;
			basicamount = 10;
			if(grossamount > 5000) grossamount = 5000;
    } else{
			grossamount = (getInputNumeric("u_gross") / 5000) * 2;
			basicamount = 500;
			if(grossamount > 10000) grossamount = 10000;
    }
    var interest = 0, interestperc = 0;
    var duedate = getPrivate("year") + "0101";
    var paydate = formatDateToDB(getInput("u_ordate")).replace(/-/g,"");
    var duemonth = parseInt(duedate.substr(0,6));
    var paymonth = parseInt(paydate.substr(0,6));
    var dueyear = parseInt(duedate.substr(0,4));
    var payyear = parseInt(paydate.substr(0,4));
    
    if(payyear>dueyear){
        interestperc = ((payyear-dueyear) * 12) - (parseInt(duedate.substr(4,2)) - parseInt(paydate.substr(4,2)));
    }else{
        interestperc = (paymonth-duemonth) + 1;
    }

    if (paydate>=duedate) {
            
            if(paymonth>2) interest =  (basicamount + grossamount) * (.02*interestperc);
            else interest = 0;
    }
    for (xxx = 1; xxx <=rc; xxx++) {
        if (isTableRowDeleted("T1",xxx)==false) {
            if (getTableInput("T1","u_feecode",xxx)==getPrivate("ctcbasicfeecode")) {
                    setTableInputAmount("T1","u_amount",basicamount,xxx);
                    ctcbasicfeecoderc=xxx;
            }
            if (getTableInput("T1","u_feecode",xxx)==getPrivate("ctcgrossfeecode")) {
                    setTableInputAmount("T1","u_amount",grossamount,xxx);
                    ctcgrossfeecoderc=xxx;
            }
            if (getTableInput("T1","u_feecode",xxx)==getPrivate("ctcpenfeecode")) {
                    setTableInputAmount("T1","u_amount",interest,xxx);
                    ctcgrossfeecoderc=xxx;
            }
        }
    }
  
     if(getPrivate("ctcbasicfeecode")!=""){
         if (ctcbasicfeecoderc==0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("ctcbasicfeecode");
            data["u_feedesc"] = getPrivate("ctcbasicfeename");
            data["u_amount"] = formatNumericAmount(basicamount);
            insertTableRowFromArray("T1",data);
        }
    }else{
        page.statusbar.showError("OnlineBilling Basic Fee not found. Please update settings.");
        return false;
    }
    
    if(getPrivate("ctcgrossfeecode")!=""){
        if (ctcgrossfeecoderc==0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("ctcgrossfeecode");
            data["u_feedesc"] = getPrivate("ctcgrossfeename");
            data["u_amount"] = formatNumericAmount(grossamount);
            insertTableRowFromArray("T1",data);
        }   
    }else{
        page.statusbar.showError("OnlineBilling Gross Fee not found. Please update settings.");
        return false;
    }
    
    if(getPrivate("ctcpenfeecode")!=""){
        if (ctcgrossfeecoderc==0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("ctcpenfeecode");
            data["u_feedesc"] = getPrivate("ctcpenfeename");
            data["u_amount"] = formatNumericAmount(interest);
            insertTableRowFromArray("T1",data);
        }   
    }else{
        page.statusbar.showError("OnlineBilling Penalty Fee not found. Please update settings.");
        return false;
    }
   
    u_computeTotalOnlineBilling();
   
}

function u_computeTotalOnlineBilling() {
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

