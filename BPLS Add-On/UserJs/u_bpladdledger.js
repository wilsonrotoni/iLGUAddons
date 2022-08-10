// page events
page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
page.events.add.submit('onPageSubmitGPSBPLS');
page.events.add.submitreturn('onPageSubmitReturnGPSBPLS');
//page.events.add.cfl('onCFLGPSBPLS');
//page.events.add.cflgetparams('onCFLGetParamsGPSBPLS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBPLS');

// element events
//page.elements.events.add.focus('onElementFocusGPSBPLS');
//page.elements.events.add.keydown('onElementKeyDownGPSBPLS');
page.elements.events.add.validate('onElementValidateGPSBPLS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSBPLS');
//page.elements.events.add.changing('onElementChangingGPSBPLS');
//page.elements.events.add.change('onElementChangeGPSBPLS');
page.elements.events.add.click('onElementClickGPSBPLS');
//page.elements.events.add.cfl('onElementCFLGPSBPLS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
//page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
page.tables.events.add.select('onTableSelectRowGPSBPLS');

function onPageLoadGPSBPLS() {
    if (getVar("formSubmitAction") == "a") {
        try {
            if (window.opener.getVar("objectcode") == "u_bplbusinessledger") {
                var result = page.executeFormattedQuery("select username from users where userid = '" + window.opener.getTableInput("T91", "userid") + "'");
                if (parseInt(result.getAttribute("result")) > 0) {
                    for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        setInput("u_authorizedby", result.childNodes.item(xxx).getAttribute("username"));
                    }
                }
                setInput("u_businessname", window.opener.getInput("u_businessname"), true);
                setInput("u_apprefno", window.opener.getInput("docno"), true);
                setInput("u_accountno", window.opener.getInput("u_appno"), true);
//                        focusInput("u_tin");
            }
        } catch (e) {
        }
    }
}

function onPageResizeGPSBPLS(width, height) {
}

function onPageSubmitGPSBPLS(action) {
    if (action == "a" || action == "sc") {
        if (isInputEmpty("u_date"))
            return false;
        if (isInputEmpty("u_businessname"))
            return false;
        if (isInputEmpty("u_apprefno"))
            return false;
        if (isInputEmpty("u_accountno"))
            return false;
        if (isInputNegative("u_totalpaid"))
            return false;
        var linetotal = 0;
        
        for (i = 1; i <= getTableRowCount("T1"); i++) {
           
            if (getTableInputNumeric("T1", "u_year", i) <= 0) {
                if (isTableInputNegative("T1", "u_year", i)) {
                    selectTableRow("T1", i);
                    focusTableInput("T1", "u_year", i);
                    return false;
                }
            }
            if (getTableInput("T1", "u_feeid", i) == "0001" || getTableInput("T1", "u_feeid") == "0260") {
                 if (isTableInputEmpty("T1", "u_businessline", i)) {
                    selectTableRow("T1", i);
                    focusTableInput("T1", "u_businessline", i);
                    return false;
                }
                if (isTableInputNegative("T1", "u_taxbase", i)) {
                    selectTableRow("T1", i);
                    focusTableInput("T1", "u_taxbase", i);
                    return false;
                }
            }
            if (getTableInputNumeric("T1", "u_amountpaid", i) <= 0) {
                if (isTableInputNegative("T1", "u_amountpaid", i)) {
                    selectTableRow("T1", i);
                    focusTableInput("T1", "u_amountpaid", i);
                    return false;
                }
            }
            if (isTableInputEmpty("T1", "u_paymode", i)) {
                selectTableRow("T1", i);
                focusTableInput("T1", "u_paymode", i);
                return false;
            }
            if (getTableInputNumeric("T1", "u_payqtr", i) <= 0) {
                if (isTableInputNegative("T1", "u_payqtr", i)) {
                    selectTableRow("T1", i);
                    focusTableInput("T1", "u_payqtr", i);
                    return false;
                }
            }
            linetotal += getTableInputNumeric("T1", "u_amountpaid", i);
        }
        if (linetotal != getInputNumeric("u_totalpaid")) {
            alert("Paid amount [ "+getInput("u_totalpaid")+" ] is not equal to total grid amount [ "+formatNumericAmount(linetotal)+" ]");
            return false;
        }
        if (window.confirm("You cannot change this document after you have added it. Continue?") == false)
            return false;
    }
    return true;
}

function onPageSubmitReturnGPSBPLS(action, sucess, error) {
    if (sucess) {
        try {
            if (window.opener.getVar("objectcode") == "u_bplbusinessledger") {
                window.opener.setKey("keys", window.opener.getInput("docno"));
                window.opener.formEdit();
                window.close();
            }
        } catch (theError) {
        }
    }
    return true;
}

function onCFLGPSBPLS(Id) {
    return true;
}

function onCFLGetParamsGPSBPLS(Id, params) {
    return params;
}

function onTaskBarLoadGPSBPLS() {
}

function onElementFocusGPSBPLS(element, column, table, row) {
    return true;
}

function onElementKeyDownGPSBPLS(element, event, column, table, row) {
}

function onElementValidateGPSBPLS(element, column, table, row) {
    var data = new Array(), tax = 0, sef = 0, penalty = 0, sefpenalty = 0;
    switch (table) {
        case "T1":
            switch (column) {
                case "u_orno":
                    if (getTableInput(table, column) != "") {
                        var result = page.executeFormattedQuery("select docno, u_date, createdby from u_lgupos where docno='" + getTableInput(table, column) + "' and u_insertpaidhistory = 0");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result")) > 0) {
                                setTableInput(table, "u_orno", result.childNodes.item(0).getAttribute("docno"));
                                setTableInput(table, "u_ordate", formatDateToHttp(result.childNodes.item(0).getAttribute("u_date")));
                                setTableInput(table, "u_cashier", result.childNodes.item(0).getAttribute("createdby"));
                            } else {
                                setTableInput(table, "u_orno", "");
                                setTableInput(table, "u_ordate", "");
                                setTableInput(table, "u_cashier", "");
                                page.statusbar.showError("Invalid OR Number");
                                return false;
                            }
                        } else {
                            setTableInput(table, "u_orno", "");
                            setTableInput(table, "u_ordate", "");
                            setTableInput(table, "u_cashier", "");
                            page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");
                            return false;
                        }
                    } else {
                        setTableInput(table, "u_orno", "");
                        setTableInput(table, "u_ordate", "");
                        setTableInput(table, "u_cashier", "");
                    }
                break;
                case "u_feeid":
                case "u_feedesc":
                    if (getTableInput(table, column) != "") {
                        if (column == "u_feeid")
                            var result = page.executeFormattedQuery("select * from (select a.code, a.name ,  0 as u_rownumber  from u_lgufees a inner join u_lgusetup b on a.code = b.u_annualtax union all select a.code, a.name , b.u_seqno as u_rownumber from u_lgufees a  inner join u_bplfees b on a.code = b.code) as x where code='" + getTableInput(table, column) + "'");
                        else
                            var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '" + getTableInput(table, column) + "%'");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result")) > 0) {
                                setTableInput(table, "u_feeid", result.childNodes.item(0).getAttribute("code"));
                                setTableInput(table, "u_feedesc", result.childNodes.item(0).getAttribute("name"));
                                setTableInput(table, "u_rownumber", result.childNodes.item(0).getAttribute("u_rownumber"));
                            } else {
                                setTableInput(table, "u_feeid", "");
                                setTableInput(table, "u_feedesc", "");
                                page.statusbar.showError("Invalid Fee");
                                return false;
                            }
                        } else {
                            setTableInput(table, "u_feeid", "");
                            setTableInput(table, "u_feedesc", "");
                            setTableInput(table, "u_rownumber", "");
                            page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");
                            return false;
                        }
                    } else {
                        setTableInput(table, "u_feeid", "");
                        setTableInput(table, "u_feedesc", "");
                        setTableInput(table, "u_rownumber", "");
                    }
                    break;
               
            }
            break;
        default:
            switch (column) {
                case "u_orno":
                    if (getInput(column) != "") {
                        var data = new Array();
                        clearTable("T1", true);
                        var result = page.executeFormattedQuery("select a.createdby,a.docno, a.u_date,a.u_totalamount, b.u_itemcode, b.u_itemdesc, b.u_linetotal,ifnull(c.u_seqno,0) as u_rownumber from u_lgupos a inner join u_lgupositems b on a.docid = b.docid and a.company = b.company and a.branch = b.branch left join u_bplfees c on b.u_itemcode = c.code where a.docno = '" + getInput(column) + "' and u_insertpaidhistory = 0");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result")) > 0) {
                                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                       setInputAmount("u_totalpaid", result.childNodes.item(xxx).getAttribute("u_totalamount"));
                                       data["u_orno"] = result.childNodes.item(xxx).getAttribute("docno");
                                       data["u_ordate"] =  formatDateToHttp(result.childNodes.item(xxx).getAttribute("u_date"));
                                       data["u_feeid"] = result.childNodes.item(xxx).getAttribute("u_itemcode");
                                       data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_itemdesc");
                                       data["u_amountpaid"] = result.childNodes.item(xxx).getAttribute("u_linetotal");
                                       data["u_cashier"] = result.childNodes.item(xxx).getAttribute("createdby");
                                       data["u_rownumber"] = result.childNodes.item(xxx).getAttribute("u_rownumber");
                                       insertTableRowFromArray("T1", data);
                                }
                            } else {
                                setInput("u_orno", "");
                                setInputAmount("u_totalpaid", 0);
                                page.statusbar.showError("Invalid Or Number");
                                return false;
                            }
                        } else {
                            setInput("u_orno", "");
                            setInputAmount("u_totalpaid", 0);
                            page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");
                            return false;
                        }
                    } else {
                        setInput("u_orno", "");
                        setInputAmount("u_totalpaid", 0);
                    }
                break;
            }
            break;
    }
    return true;
}

function onElementGetValidateParamsGPSBPLS(table, row, column) {
    var params = "";
    return params;
}

function onElementChangingGPSBPLS(element, column, table, row) {
    return true;
}

function onElementChangeGPSBPLS(element, column, table, row) {
    return true;
}

function onElementClickGPSBPLS(element, column, table, row) {
    return true;
}

function onElementCFLGPSBPLS(element) {
    return true;
}

function onElementCFLGetParamsGPSBPLS(Id, params) {
    switch (Id) {
        case "df_u_orno":
        case "df_u_ornoT1":
            params["params"] = "&cflquery=" + utils.replaceSpecialChar(Base64.encode("select docno, u_date,u_totalamount,createdby from u_lgupos where company = '" + getGlobal("company") + "' and branch = '" + getGlobal("branch") + "' and u_insertpaidhistory = 0"));
            params["params"] += "&cfltitles=" + utils.replaceSpecialChar(Base64.encode("OR Number`OR Date`Total Paid`Cashier"));
            params["params"] += "&cflwidths=" + utils.replaceSpecialChar(Base64.encode("15`15`15`35"));
            params["params"] += "&cflformats=" + utils.replaceSpecialChar(Base64.encode("```"));
            break;
        case "df_u_feeidT1":
            params["params"] = "&cflquery=" + utils.replaceSpecialChar(Base64.encode("select * from (select a.code, a.name  from u_lgufees a inner join u_lgusetup b on a.code = b.u_annualtax union all select a.code, a.name  from u_lgufees a  inner join u_bplfees b on a.code = b.code) as x"));
            params["params"] += "&cfltitles=" + utils.replaceSpecialChar(Base64.encode("Fee`Description"));
            params["params"] += "&cflwidths=" + utils.replaceSpecialChar(Base64.encode("15`50"));
            params["params"] += "&cflformats=" + utils.replaceSpecialChar(Base64.encode("`"));
            break;
    }
    return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table, ROW) {
    switch (table) {
        case "T1":
            if (getTableInput(table, "u_year") == "-") {
                page.statusbar.showError("Year is required.");
                focusTableInput(table, "u_year");
                return false;
            }
            if (isTableInputEmpty(table, "u_year"))
                return false;
            if (isTableInputEmpty(table, "u_orno"))
                return false;
            if (isTableInputEmpty(table, "u_ordate"))
                return false;
            if (isTableInputEmpty(table, "u_feeid"))
                return false;
            if (isTableInputEmpty(table, "u_feedesc"))
                return false;
            if (isTableInputNegative(table, "u_amountpaid"))
                return false;
            if (isTableInputEmpty(table, "u_paymode"))
                return false;
            if (isTableInputNegative(table, "u_payqtr"))
                return false;
            if (isTableInputEmpty(table, "u_cashier"))
                return false;
            if (getTableInput("T1", "u_feeid") == "0001" || getTableInput("T1", "u_feeid") == "0260") {
                if (isTableInputNegative("T1", "u_taxbase")) 
                    return false;
                
                if (isTableInputEmpty("T1", "u_businessline")) {
                    return false;
                }
            }
           
            //setTableInputDefault(table, "u_year", getTableInput(table, "u_year", row), row);
            break;

    }
    return true;
}

function onTableAfterInsertRowGPSBPLS(table, row) {
}

function onTableBeforeUpdateRowGPSBPLS(table, row) {
    switch (table) {
        case "T1":
            if (getTableInput(table, "u_year") == "-") {
                page.statusbar.showError("Year is required.");
                focusTableInput(table, "u_year");
                return false;
            }
            if (isTableInputEmpty(table, "u_year"))
                return false;
            if (isTableInputEmpty(table, "u_orno"))
                return false;
            if (isTableInputEmpty(table, "u_ordate"))
                return false;
            if (isTableInputEmpty(table, "u_feeid"))
                return false;
            if (isTableInputEmpty(table, "u_feedesc"))
                return false;
            if (isTableInputNegative(table, "u_amountpaid"))
                return false;
            if (isTableInputEmpty(table, "u_paymode"))
                return false;
            if (isTableInputEmpty(table, "u_payqtr"))
                return false;
            if (isTableInputEmpty(table, "u_cashier"))
                return false;
            if (getTableInput(table, "u_feeid") == "0001" || getTableInput("T1", "u_feeid") == "0260") {
                if (isTableInputNegative(table, "u_taxbase")) 
                    return false;
                
                if (isTableInputEmpty(table, "u_businessline")) {
                    return false;
                }
            }
            setTableInputDefault(table, "u_year", getTableInput(table, "u_year", row), row);
            break;

    }
    return true;
}

function onTableAfterUpdateRowGPSBPLS(table, row) {
}

function onTableBeforeDeleteRowGPSBPLS(table, row) {
    return true;
}

function onTableDeleteRowGPSBPLS(table, row) {
}

function onTableBeforeSelectRowGPSBPLS(table, row) {
    return true;
}

function onTableSelectRowGPSBPLS(table, row) {
    var params = new Array();
    params["focus"] = false;
    return params;
}



function ApplytoGrid() {
    if (isInputNegative("u_year"))
        return false;
    if (isInputEmpty("u_paymode"))
        return false;
    if (getTableRowCount("T1") > 0) {
        for (i = 1; i <= getTableRowCount("T1"); i++) {
            setTableInput("T1", "u_year", getInput("u_year"), i);
            if (getInput("u_paymode")=="A") {
                 setTableInput("T1", "u_paymode", "A", i);
                 setTableInput("T1", "u_payqtr", 4, i);
            } else if (getInput("u_paymode")=="S1") {
                 setTableInput("T1", "u_paymode", "S", i);
                 setTableInput("T1", "u_payqtr", 2, i);
            }else if (getInput("u_paymode")=="S2") {
                 setTableInput("T1", "u_paymode", "S", i);
                 setTableInput("T1", "u_payqtr", 4, i);
            }else if (getInput("u_paymode")=="Q1") {
                 setTableInput("T1", "u_paymode", "Q", i);
                 setTableInput("T1", "u_payqtr", 1, i);
            }else if (getInput("u_paymode")=="Q2") {
                 setTableInput("T1", "u_paymode", "Q", i);
                 setTableInput("T1", "u_payqtr", 2, i);
            }else if (getInput("u_paymode")=="Q3") {
                 setTableInput("T1", "u_paymode", "Q", i);
                 setTableInput("T1", "u_payqtr", 3, i);
            }else if (getInput("u_paymode")=="Q4") {
                 setTableInput("T1", "u_paymode", "Q", i);
                 setTableInput("T1", "u_payqtr", 4, i);
            }
        }
    }
}


