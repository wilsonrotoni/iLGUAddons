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
page.elements.events.add.click('onElementClickCTC');
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

function onPageResizeCTC(width, height) {
}

function onPageSubmitCTC(action) {
    if (action == "a" || action == "sc") {
        if (isInputEmpty("u_apptype"))
            return false;
        if (isInputEmpty("u_orno"))
            return false;
        if (isInputEmpty("u_ordate"))
            return false;
        if (isInputEmpty("u_docseries"))
            return false;
        if (isInputEmpty("u_custname"))
            return false;
        if (isInputNegative("u_totalamount"))
            return false;
        if (isInputNegative("u_gross"))
            return false;
        if (getInput("u_ischeque") ==1) {
            if (isInputEmpty("u_checkno"))
            return false; 
            if (isInputEmpty("u_checkdate"))
            return false; 
            if (isInputEmpty("u_checkbank"))
            return false;
        } 
        if (window.confirm("You cannot change this document after you have added it. Continue?") == false)
            return false;
    }
    return true;
}

function onPageSubmitReturnCTC(action, sucess, error) {
    if (action == "a" && sucess) {
        if (window.confirm("Print Official Receipt. Continue?"))
            OpenReportSelect('printer');
        focusInput("u_address");
    }
}

function onCFLCTC(Id) {
    return true;
}

function onCFLGetParamsCTC(Id, params) {

    return params;
}

function onTaskBarLoadCTC() {
}

function onElementFocusCTC(element, column, table, row) {
    return true;
}

function onElementKeyDownCTC(element, event, column, table, row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
    switch (sc_press) {
        case "ENTER":
            if (column == "u_gross") {
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

function onElementValidateCTC(element, column, table, row) {
    switch (table) {
        case "T1":
            switch (column) {
                case "u_feecode":
                case "u_feedesc":
                    if (getTableInput(table, column) != "") {
                        if (column == "u_feecode")
                            var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='" + getTableInput(table, column) + "'");
                        else
                            var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '" + getTableInput(table, column) + "%'");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result")) > 0) {
                                setTableInput(table, "u_feecode", result.childNodes.item(0).getAttribute("code"));
                                setTableInput(table, "u_feedesc", result.childNodes.item(0).getAttribute("name"));
                                setTableInputAmount(table, "u_amount", result.childNodes.item(0).getAttribute("u_amount"));
                            } else {
                                setTableInput(table, "u_feecode", "");
                                setTableInput(table, "u_feedesc", "");
                                setTableInputAmount(table, "u_amount", 0);
                                page.statusbar.showError("Invalid Fee");
                                return false;
                            }
                        } else {
                            setTableInput(table, "u_feecode", "");
                            setTableInput(table, "u_feedesc", "");
                            setTableInputAmount(table, "u_amount", 0);
                            page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");
                            return false;
                        }
                    } else {
                        setTableInput(table, "u_feecode", "");
                        setTableInput(table, "u_feedesc", "");
                        setTableInputAmount(table, "u_amount", 0);
                    }
                    break;
            }
            break;
    }
    return true;
}

function onElementGetValidateParamsCTC(table, row, column) {
    var params = "";
    return params;
}

function onElementChangingCTC(element, column, table, row) {
    return true;
}

function onElementChangeCTC(element, column, table, row) {
    switch (table) {
        default:
            switch (column) {
                case "u_docseries":
                    if (getInput(column) != "") {
                        getDocnoPerSeriesGPSPOS(getInput(column));
                    }
                    break;
                case "u_apptype":

                    if (getInput(column) == "I")
                        var result = page.executeFormattedQuery("SELECT U_DOCSERIES FROM U_LGUPOSREGISTERS A INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID WHERE B.U_DOCSERIESNAME = 'AF 0016' AND B.U_NEXTNO <= B.U_LASTNO AND A.COMPANY = '" + getGlobal("company") + "' AND A.BRANCH = '" + getGlobal("branch") + "' AND A.U_STATUS = 'O' AND A.U_USERID = '" + getPrivate("userid") + "' ");
                    else
                        var result = page.executeFormattedQuery("SELECT U_DOCSERIES FROM U_LGUPOSREGISTERS A INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID WHERE B.U_DOCSERIESNAME = 'AF 0017' AND B.U_NEXTNO <= B.U_LASTNO AND A.COMPANY = '" + getGlobal("company") + "' AND A.BRANCH = '" + getGlobal("branch") + "' AND A.U_STATUS = 'O' AND A.U_USERID = '" + getPrivate("userid") + "' ");
                    if (result.getAttribute("result") != "-1") {
                        if (parseInt(result.getAttribute("result")) > 0) {
                            setInput("u_docseries", result.childNodes.item(0).getAttribute("u_docseries"), true);
                        }
                    }
                    break;
            }
    }
    return true;
}

function onElementClickCTC(element, column, table, row) {
    switch (table) {

        default:
            switch (column) {
                case "u_ischeque":
                    if (getInput("u_ischeque") == "0") {
                        disableInput("u_checkno");
                        disableInput("u_checkdate");
                        disableInput("u_checkbank");
                        setInput("u_checkno","");
                        setInput("u_checkdate","");
                        setInput("u_checkbank","");
                    } else {
                        enableInput("u_checkno");
                        enableInput("u_checkdate");
                        enableInput("u_checkbank");
                        focusInput("u_checkno");
                    }
                    break;
            }
    }
    return true;
}

function onElementCFLCTC(element) {
    return true;
}

function onElementCFLGetParamsCTC(Id, params) {
    switch (Id) {
        case "df_u_feecodeT1":
            params["params"] = "&cflquery=" + utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees"));
            params["params"] += "&cfltitles=" + utils.replaceSpecialChar(Base64.encode("Fee`Description"));
            params["params"] += "&cflwidths=" + utils.replaceSpecialChar(Base64.encode("15`50"));
            params["params"] += "&cflformats=" + utils.replaceSpecialChar(Base64.encode("`"));
            break;
        case "df_u_feedescT1":
            params["params"] = "&cflquery=" + utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees"));
            params["params"] += "&cfltitles=" + utils.replaceSpecialChar(Base64.encode("Description`Fee"));
            params["params"] += "&cflwidths=" + utils.replaceSpecialChar(Base64.encode("50`15"));
            params["params"] += "&cflformats=" + utils.replaceSpecialChar(Base64.encode("`"));
            break;
    }
    return params;
}

function onTableResetRowCTC(table) {
}

function onTableBeforeInsertRowCTC(table) {
    switch (table) {

        case "T1":
            if (isTableInputEmpty(table, "u_feecode"))
                return false;
            if (isTableInputEmpty(table, "u_feedesc"))
                return false;
            //if (isTableInputNegative(table,"u_amount")) return false;
            break;
    }
    return true;
}

function onTableAfterInsertRowCTC(table, row) {
    switch (table) {
        case "T1":
            u_computeTotalCTC();
            break;

    }
}

function onTableBeforeUpdateRowCTC(table, row) {
    switch (table) {

        case "T1":
            if (isTableInputEmpty(table, "u_feecode"))
                return false;
            if (isTableInputEmpty(table, "u_feedesc"))
                return false;
            //if (isTableInputNegative(table,"u_amount")) return false;
            break;
    }

    return true;
}

function onTableAfterUpdateRowCTC(table, row) {
    switch (table) {
        case "T1":
            u_computeTotalCTC();
            break;
    }
}

function onTableBeforeDeleteRowCTC(table, row) {
    return true;
}

function onTableDeleteRowCTC(table, row) {
    switch (table) {
        case "T1":
            u_computeTotalCTC();
            break;
    }
}

function onTableBeforeSelectRowCTC(table, row) {
    return true;
}

function onTableSelectRowCTC(table, row) {
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


function getDocnoPerSeriesGPSPOS(seriesname) {
    var numlen = 0, prefix, suffix, nextno = 0, docno = 0, docno1 = 0, d = new Date();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();

    month = month.toString();
    year = year.toString();

    var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME,B.U_ISSUEDOCNO FROM U_LGUPOSREGISTERS A INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID WHERE B.U_DOCSERIES = '" + seriesname + "' AND B.U_NEXTNO <= B.U_LASTNO AND A.COMPANY = '" + getGlobal("company") + "' AND A.BRANCH = '" + getGlobal("branch") + "' AND A.U_STATUS = 'O' AND A.U_USERID = '" + getPrivate("userid") + "' ");
    if (result.getAttribute("result") != "-1") {
        if (parseInt(result.getAttribute("result")) > 0) {
            numlen = result.childNodes.item(0).getAttribute("u_numlen");
            prefix = result.childNodes.item(0).getAttribute("u_prefix");
            suffix = result.childNodes.item(0).getAttribute("u_suffix");
            nextno = result.childNodes.item(0).getAttribute("u_nextno");

            nextno = nextno.toString();

            prefix = prefix.replace("{POS}", getInput("u_terminalid"));
            suffix = suffix.replace("{POS}", getInput("u_terminalid"));
            prefix = prefix.replace("[m]", month.padStart1(2, '0'));
            suffix = suffix.replace("[m]", month.padStart1(2, '0'));
            prefix = prefix.replace("[Y]", year);
            suffix = suffix.replace("[Y]", year);
            prefix = prefix.replace("[y]", year.padStart1(2, '0'));
            suffix = suffix.replace("[y]", year.padStart1(2, '0'));

            docno = prefix + nextno.padStart1(numlen, '0') + suffix;
            setInput("u_seriesdocno", result.childNodes.item(0).getAttribute("u_docseriesname"));
            setInput("u_issuedocno", result.childNodes.item(0).getAttribute("u_issuedocno"));
            setInput("u_orno", docno);
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

function setCTCFees() {

    var ctcgrossfeecoderc = 0, ctcbasicfeecoderc = 0, grossamount = 0, basicamount = 0, ctcpenamount = 0, rc = getTableRowCount("T1");

    if (isInputEmpty("u_apptype"))
        return false;
    if (isInputNegative("u_gross"))
        return false;
    if (getInput("u_apptype") == "I") {
        grossamount = getInputNumeric("u_gross") / 1000;
        basicamount = 10;
        if (grossamount > 5000)
            grossamount = 5000;
    } else {
        grossamount = (getInputNumeric("u_gross") / 5000) * 2;
        basicamount = 500;
        if (grossamount > 10000)
            grossamount = 10000;
    }
    var interest = 0, interestperc = 0;
    var duedate = getPrivate("year") + "0120";
    var paydate = formatDateToDB(getInput("u_ordate")).replace(/-/g, "");
    var duemonth = parseInt(duedate.substr(0, 6));
    var paymonth = parseInt(paydate.substr(0, 6));
    var dueyear = parseInt(duedate.substr(0, 4));
    var payyear = parseInt(paydate.substr(0, 4));

    if (payyear > dueyear) {
        interestperc = ((payyear - dueyear) * 12) - (parseInt(duedate.substr(4, 2)) - parseInt(paydate.substr(4, 2)));
    } else {
        interestperc = (paymonth - duemonth) + 1;
    }
    
    if (paydate >= duedate) {

        if (paymonth > 2)
            interest = (basicamount + grossamount) * (.02 * interestperc);
        else
            interest = 0;
    }
    
    for (xxx = 1; xxx <= rc; xxx++) {
        if (isTableRowDeleted("T1", xxx) == false) {
            if (getTableInput("T1", "u_feecode", xxx) == getPrivate("ctcbasicfeecode")) {
                setTableInputAmount("T1", "u_amount", basicamount, xxx);
                ctcbasicfeecoderc = xxx;
            }
            if (getTableInput("T1", "u_feecode", xxx) == getPrivate("ctcgrossfeecode")) {
                setTableInputAmount("T1", "u_amount", grossamount, xxx);
                ctcgrossfeecoderc = xxx;
            }
            if (getTableInput("T1", "u_feecode", xxx) == getPrivate("ctcpenfeecode")) {
                setTableInputAmount("T1", "u_amount", interest, xxx);
                ctcgrossfeecoderc = xxx;
            }
        }
    }

    if (getPrivate("ctcbasicfeecode") != "") {
        if (ctcbasicfeecoderc == 0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("ctcbasicfeecode");
            data["u_feedesc"] = getPrivate("ctcbasicfeename");
            data["u_amount"] = formatNumericAmount(basicamount);
            insertTableRowFromArray("T1", data);
        }
    } else {
        page.statusbar.showError("CTC Basic Fee not found. Please update settings.");
        return false;
    }

    if (getPrivate("ctcgrossfeecode") != "") {
        if (ctcgrossfeecoderc == 0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("ctcgrossfeecode");
            data["u_feedesc"] = getPrivate("ctcgrossfeename");
            data["u_amount"] = formatNumericAmount(grossamount);
            insertTableRowFromArray("T1", data);
        }
    } else {
        page.statusbar.showError("CTC Gross Fee not found. Please update settings.");
        return false;
    }

    if (getPrivate("ctcpenfeecode") != "") {
        if (ctcgrossfeecoderc == 0) {
            var data = new Array();
            data["u_feecode"] = getPrivate("ctcpenfeecode");
            data["u_feedesc"] = getPrivate("ctcpenfeename");
            data["u_amount"] = formatNumericAmount(interest);
            insertTableRowFromArray("T1", data);
        }
    } else {
        page.statusbar.showError("CTC Penalty Fee not found. Please update settings.");
        return false;
    }

    u_computeTotalCTC();

}

function u_computeTotalCTC() {
    var totalamount = 0, vatamount = 0, adddiscount = 0, adddiscamount = 0, taxamt = 0, qty = 0, price = 0, amount = 0, taxrate = 0, rc = 0, othercharges = 0, roundamount = 0, lineadddisc = 0, wtaxamount = 0, wtaxtxs = 0, wtaxnet = 0, wtaxenabled = "0", totalamount = 0, misccharges = 0, amountafterdisc = 0, taxableamount = 0, totalquantity = 0, penaltyamount = 0;

    rc = getTableRowCount("T1");
    for (i = 1; i <= rc; i++) {
        if (isTableRowDeleted("T1", i) == false) {
            amount += getTableInputNumeric("T1", "u_amount", i);
        }
    }
    setInputAmount("u_totalamount", amount);
}
function PostTransaction() {
    setInput("docstatus", "C");
    formSubmit();
}

