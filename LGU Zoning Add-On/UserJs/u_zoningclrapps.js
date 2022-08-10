// page events
page.events.add.load('onPageLoadGPSLGUZoning');
//page.events.add.resize('onPageResizeGPSLGUZoning');
page.events.add.submit('onPageSubmitGPSLGUZoning');
//page.events.add.cfl('onCFLGPSLGUZoning');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUZoning');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUZoning');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUZoning');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUZoning');
page.elements.events.add.validate('onElementValidateGPSLGUZoning');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUZoning');
//page.elements.events.add.changing('onElementChangingGPSLGUZoning');
page.elements.events.add.change('onElementChangeGPSLGUZoning');
page.elements.events.add.click('onElementClickGPSLGUZoning');
//page.elements.events.add.cfl('onElementCFLGPSLGUZoning');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUZoning');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUZoning');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUZoning');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUZoning');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUZoning');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUZoning');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUZoning');
page.tables.events.add.delete('onTableDeleteRowGPSLGUZoning');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUZoning');
//page.tables.events.add.select('onTableSelectRowGPSLGUZoning');

function onPageLoadGPSLGUZoning() {
    if (getInput("docstatus")=="D") {
            enableInput("docno");
    }
    
}

function onPageResizeGPSLGUZoning(width,height) {
}

function onPageSubmitGPSLGUZoning(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_businessname")) return false;
		//if (isInputEmpty("u_lastname")) return false;
		//if (isInputEmpty("u_firstname")) return false;
                if(getInput("u_appnature")!='Others') if (isInputNegative("u_orsfamt")) return false;
	}
	return true;
}

function onCFLGPSLGUZoning(Id) {
	return true;
}

function onCFLGetParamsGPSLGUZoning(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUZoning() {
}

function onElementFocusGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUZoning(element,event,column,table,row) {
}

function onElementValidateGPSLGUZoning(element,column,table,row) {
	var result;
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
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_docdate, u_bpno, u_businessname, u_address from u_zoningclrapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
								setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
								setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
							} else {
								setInput("u_appdate","");
								page.statusbar.showError("Invalid Application No.");	
								return false;
							}
						} else {
							setInput("u_appdate","");
							page.statusbar.showError("Error retrieving application record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_appdate","");
					}
					break;
                               
                                break;
                                    
			}
	}
	return true;
}

function onElementGetValidateParamsGPSLGUZoning(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUZoning(element,column,table,row) {
     switch (table) {
		default:
                    switch(column) {
                        case "u_istup":
                            getZoningApplicationFees(); 
                        break;
                    }
                break;
            }
	return true;
}

function onElementClickGPSLGUZoning(element,column,table,row) {
        switch (table) {
		default:
                    switch(column) {
                        case "u_appnature":
                            getZoningApplicationFees();
                            if(getInput(column)=="Others") focusInput("u_appnatureothers");
                        break;
                    }
                break;
	}
	return true;
}

function onElementCFLGPSLGUZoning(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUZoning(Id,params) {
	switch (Id) {
		case "df_u_natureofbusiness":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME FROM U_BPLNATURE")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Business Nature")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
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

function onTableResetRowGPSLGUZoning(table) {
}

function onTableBeforeInsertRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1":
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;  
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
    return true;
}

function onTableAfterInsertRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeUpdateRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1":
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
    }
	return true;
}

function onTableAfterUpdateRowGPSLGUZoning(table,row) {
     switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeDeleteRowGPSLGUZoning(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUZoning(table,row) {
     switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeSelectRowGPSLGUZoning(table,row) {
	return true;
}

function onTableSelectRowGPSLGUZoning(table,row) {
}


function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
		}
	}
 
	setInputAmount("u_orsfamt",total);
}

function u_approveGPSLGUZoning() {
	if (getTableRowCount("T1")==0 && getInput("u_appnature")!='Others') {
		page.statusbar.showError("At least one fee must be entered.");
		return false;
	}
        if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	setInput("docstatus","AP");
	formSubmit('sc');
}

function u_disapproveGPSLGUZoning() {
	setInput("docstatus","DA");
	formSubmit('sc');
}

function u_PaymentHistoryGPSGLGUZoning() {
        OpenPopup(1024,500,"./udp.php?&objectcode=u_zoningledger&df_refno2="+getInput("u_acctno")+"","Business Ledger");
}

function getZoningApplicationFees() {
    if(getInput("u_appnature")=="New Application" && getInput("u_istup") == 0) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_AMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_AMOUNT <>0");	
    else if (getInput("u_appnature")=="New Application" && getInput("u_istup") == 1) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_TUPNEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_TUPNEWAMOUNT <>0");	
    else if(getInput("u_appnature")=="Renewal" && getInput("u_istup") == 0) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_RENEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_RENEWAMOUNT <>0");	
    else if(getInput("u_appnature")=="Renewal" && getInput("u_istup") == 1) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_TUPRENEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_TUPRENEWAMOUNT <>0");	
    if (result.getAttribute("result")!= "-1") {
        if (parseInt(result.getAttribute("result"))>0) {
                clearTable("T1",true);
                var data = new Array();
                    for (var iii=0; iii<result.childNodes.length; iii++) {
                            data["u_year"] = getInput("u_year");
                            data["u_feecode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
                            data["u_feedesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
                            data["u_seqno"] = result.childNodes.item(iii).getAttribute("u_seqno");
                            data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                            insertTableRowFromArray("T1",data);
                    }
                computeTotalAssessment();
        } else {
                page.statusbar.showError("Invalid Automatic Fees. Please check the set up for zoning fees");	
                return false;
        }
    } else {
            page.statusbar.showError("Error retrieving Fees. Try Again, if problem persists, check the connection.");	
            return false;
    }
}