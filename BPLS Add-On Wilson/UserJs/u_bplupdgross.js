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
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
//page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
page.tables.events.add.select('onTableSelectRowGPSBPLS');

function onPageLoadGPSBPLS() {
	if (getVar("formSubmitAction")=="a") {
		try {
//                    alert(window.opener.getVar("objectcode"));
                    if (window.opener.getVar("objectcode")=="U_BPLAPPS") {
                        var result = page.executeFormattedQuery("select username from users where userid = '"+window.opener.getTableInput("T71","userid")+"'");
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                setInput("u_authorizedby",result.childNodes.item(xxx).getAttribute("username"));
                            }
                        }
			setInput("u_paymode",window.opener.getInput("u_paymode"),true);
			setInput("u_apprefno",window.opener.getInput("docno"),true);
			setInput("u_accountno",window.opener.getInput("u_appno"),true);
                        focusInput("u_tin");
                    }
		} catch ( e ) {
		}
	}
}

function onPageResizeGPSBPLS(width,height) {
}

function onPageSubmitGPSBPLS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_businessname")) return false;
		if (isInputEmpty("u_apprefno")) return false;
		if (isInputEmpty("u_accountno")) return false;
                if(window.confirm("You cannot change this document after you have added it. Continue?") == false) return false; 
	}
	return true;
}

function onPageSubmitReturnGPSBPLS(action,sucess,error) {
	if (sucess) {
            try {
                if (window.opener.getVar("objectcode")=="U_BPLAPPS") {
                        window.opener.setKey("keys",window.opener.getInput("docno"));
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

function onCFLGetParamsGPSBPLS(Id,params) {
	return params;
}

function onTaskBarLoadGPSBPLS() {
}

function onElementFocusGPSBPLS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSBPLS(element,event,column,table,row) {
}

function onElementValidateGPSBPLS(element,column,table,row) {
	var data = new Array(),tax=0,sef=0,penalty=0,sefpenalty=0;
	switch (table) {
		case "T1":
			switch (column) {
				case "u_yrpaid2":
					if (getTableInputNumeric(table,"u_yrpaid2",row)<getTableInputNumeric(table,"u_yrfr2",row) && getTableInputNumeric(table,"u_yrpaid2",row)!=0) {
						page.statusbar.showError("Year of Last Payment cannot be less than Year From");
						return false;
					}
					if (getTableInputNumeric(table,"u_yrpaid2",row)>getTableInputNumeric(table,"u_yrto2",row) && getTableInputNumeric(table,"u_yrto2",row)!=0) {
						page.statusbar.showError("Year of Last Payment cannot be more than Year To");
						return false;
					}
					break;
                                        
                                case "u_auditedgross":
                                    var annualtaxcoderc=0, electricalcertrc = 0, capital = 0, essential = 0, nonessential = 0, baseperc=100, taxamount=0, taxrate=0, excessamount = 0, fromamount = 0;
                                        var capitalamount = 0,annualtaxamount = 0;
                                                
                                                var grosssales1 = 0, baseperc1=100, taxamount1=0, taxrate1=0, fromamount1 = 0, excessamount1 = 0,compbased = '';
                                                grosssales1 = getTableInputNumeric("T1","u_auditedgross",row);
                                                
                                                var result = page.executeFormattedQuery("select u_compbased,u_fixedamount from u_bpllines where code = '"+getTableInput("T1","u_businessline",row)+"'");
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            compbased = result.childNodes.item(0).getAttribute("u_compbased");
                                                        }   
                                                }
                                                
                                                if (compbased == 1) { 
                                                        taxamount1 = parseFloat(result.childNodes.item(0).getAttribute("u_fixedamount"));
                                                } else {
                                                        var result1 = page.executeFormattedQuery("select b.u_excessamount,a.u_baseperc, b.u_amount, b.u_rate,b.u_from from u_bpltaxclasses a, u_bpltaxclasstaxes b where b.code=a.code and "+grosssales1+" >= b.u_from and ("+grosssales1+" <=b.u_to or b.u_to=0) and a.code = '"+getTableInput("T1","u_taxclass",row)+"'");
                                                        if (result1.getAttribute("result1")!= "-1") {
                                                                if (parseInt(result1.getAttribute("result"))>0) {
                                                                        baseperc1 = parseFloat(result1.childNodes.item(0).getAttribute("u_baseperc"));
                                                                        taxamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_amount"));
                                                                        taxrate1 = parseFloat(result1.childNodes.item(0).getAttribute("u_rate"));
                                                                        excessamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_excessamount"));
                                                                        fromamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_from"));
                                                                }		
                                                        } else {
                                                                page.statusbar.showError("Error retrieving tax classificaton. Try Again, if problem persists, check the connection.");	
                                                                return false;
                                                        }
                                                }
                                                
                                         if (taxamount1>0 && excessamount1==0 && taxrate1==0) {
                                                annualtaxamount = taxamount1*(baseperc1/100);
                                            }else if(taxamount1==0 && excessamount1==0 && taxrate1>0 ) {
                                                annualtaxamount = taxrate1*grosssales1*(baseperc1/100);
                                            }else if(taxamount1>0 && excessamount1>0 && taxrate1>0 ){
                                                annualtaxamount = ((((grosssales1 - fromamount1) / excessamount1) * taxrate1 ) + taxamount1)*(baseperc1/100);
                                            }
                                            if(annualtaxamount<220) annualtaxamount = 220;
                                            setTableInputAmount("T1","u_auditedbtaxamount",annualtaxamount,row);
                                                        
                    break;
			}
			break;
		default:
			switch (column) {
				case "u_apprefno":
					setTimeout("getBusinessLines()",1000);
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSBPLS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSBPLS(element,column,table,row) {
	return true;
}

function onElementChangeGPSBPLS(element,column,table,row) {
	return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
	return true;
}

function onElementCFLGPSBPLS(element) {
	return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
	switch (Id) {
		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_pin, docno, type, u_ownername from (select u_pin,docno, 'Land' as type, u_ownername from u_rpfaas1 union all select u_pin,docno, 'Building' as type, u_ownername from u_rpfaas2 union all select u_pin,docno, 'Machinery' as type, u_ownername from u_rpfaas3) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Arp No.`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table) {
	return true;
}

function onTableAfterInsertRowGPSBPLS(table,row) {
}

function onTableBeforeUpdateRowGPSBPLS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
}

function onTableBeforeDeleteRowGPSBPLS(table,row) {
	return true;
}

function onTableDeleteRowGPSBPLS(table,row) {
}

function onTableBeforeSelectRowGPSBPLS(table,row) {
	return true;
}

function onTableSelectRowGPSBPLS(table,row) {
	var params = new Array();
	params["focus"] = false;
	return params;
}

function getBusinessLines() {
	var data = new Array();
	clearTable("T1",true);
	//if (getInput("u_pin")!="") {
		var result = page.executeFormattedQuery("select a.u_year,a.u_tradename,a.u_paymode,c.code as u_businesslinecode, c.name as u_businessline,b.u_taxclass,(b.u_capital + b.u_nonessential) as u_gross,b.u_btaxlinetotal from u_bplapps a inner join u_bplapplines b on a.company = b.company and a.branch = b.branch and a.docid = b.docid and a.u_year = b.u_year left join u_bpllines c on b.u_businessline = c.code where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno =  '"+getInput("u_apprefno")+"' ");
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				if (xxx==0) {
					setInput("u_businessname",result.childNodes.item(xxx).getAttribute("u_tradename"));
					setInput("u_year",result.childNodes.item(xxx).getAttribute("u_year"));
					setInput("u_paymode",result.childNodes.item(xxx).getAttribute("u_paymode"));
				}
                                var result1 = page.executeFormattedQuery("SELECT SUM(u_amountdue) as u_amountdue FROM U_BPLLEDGER  WHERE COMPANY='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_acctno =  '"+getInput("u_accountno")+"'  AND U_ISCANCELLED <> 1 and u_businesslineid = '"+result.childNodes.item(xxx).getAttribute("u_businesslinecode")+"' and u_payyear = '"+getInput("u_year")+"' and u_feeid in ('1','5','0001')");
                                if (parseInt(result1.getAttribute("result"))>0) {
                                    data["u_paidamount"] = formatNumericAmount(result1.childNodes.item(0).getAttribute("u_amountdue"));
                                    var result2 = page.executeFormattedQuery("SELECT MAX(U_QUARTER) as u_payqtr FROM U_BPLLEDGER  WHERE COMPANY='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_acctno =  '"+getInput("u_accountno")+"'  AND U_ISCANCELLED <> 1 and u_businesslineid = '"+result.childNodes.item(xxx).getAttribute("u_businesslinecode")+"' and u_payyear = '"+getInput("u_year")+"'  and u_feeid in ('1','5','0001')");
                                    if (parseInt(result2.getAttribute("result"))>0) {
                                          data["u_paidqtr"] = result2.childNodes.item(0).getAttribute("u_payqtr");
                                    }
                                }
				data["u_businesslinecode"] = result.childNodes.item(xxx).getAttribute("u_businesslinecode");
				data["u_businessline"] = result.childNodes.item(xxx).getAttribute("u_businessline");
				data["u_taxclass"] = result.childNodes.item(xxx).getAttribute("u_taxclass");
				data["u_unauditedgross"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_gross"));
				data["u_btaxamount"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_btaxlinetotal"));
				insertTableRowFromArray("T1",data);
				//assvalue+=parseFloat(result.childNodes.item(xxx).getAttribute("u_assvalue"));
			}
		}			
	//}
}


