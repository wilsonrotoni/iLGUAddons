// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUAcctg');
page.events.add.submitreturn('onPageSubmitReturnGPSLGUAcctg');

//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');
page.events.add.reportgetparams('onPageReportGetParamsGPSLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctg');
page.elements.events.add.validate('onElementValidateGPSLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctg');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
page.elements.events.add.change('onElementChangeGPSLGUAcctg');
page.elements.events.add.click('onElementClickGPSLGUAcctg');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctg');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctg');

// table events
page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');

function onPageReportGetParamsGPSLGUAcctg(formattype,params) {
		var paramids= new Array(),paramtypes= new Array(),paramvaluetypes= new Array(),paramaliases= new Array();
		var docstatus = getInput("docstatus");
		if (getVar("formSubmitAction")=="a") docstatus = "";
		params = getReportLayout(getGlobal("progid2"),formattype,params,docstatus);
		params["source"] = "aspx";
		if (params["reportname"]!=undefined) {
			if (params["querystring"]==undefined) {
				params["querystring"] = "";
				if (params["reportname"]=="JEV") {
					params["querystring"] += generateQueryString("docno",getInput("u_jevno"));
				} else {
					params["querystring"] += generateQueryString("docno",getInput("docno"));
				}
			}	
		}
		return params;
}


function onPageLoadGPSLGUAcctg() {
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	if (action=="a" || action=="sc") {
		if (getInput("docstatus")!="D") {
			if (getPrivate("approver")!="1") {
				page.statusbar.showError("You must be an approver to add/update this document.");
				return false;
			}
		} else {
			if (getPrivate("encoder")!="1" && getPrivate("approver")!="1") {
				page.statusbar.showError("You must be an encoder/approver to save/update as draft this document.");
				return false;
			}
		}

		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_bpcode")) return false;
		if (isInputEmpty("u_bpname")) return false;
		if (isInputEmpty("u_jevseries")) return false;
		
		if (getInput("u_stalecheckbank")!="" || getInput("u_stalecheckbankacctno")!="" || getInput("u_stalecheckno")!="" || getInputNumeric("u_stalecheckamount")!=0) {
			if (isInputEmpty("u_stalecheckbank")) return false;
			if (isInputEmpty("u_stalecheckbankacctno")) return false;
			if (isInputEmpty("u_stalecheckno")) return false;
			if (isInputNegative("u_stalecheckamount")) return false;
		}
		
		if (getInput("docstatus")=="O") {
			if (isInputEmpty("u_jevno")) return false;
		}
		if (isInputZero("u_dueamount")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSLGUAcctg(action,sucess,error) {
	if (!sucess && error.substring(0,13)=="OBR vs Actual") alert(error.replace(/`/g,"\r\n"));
}

function onCFLGPSLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctg() {
}

function onElementFocusGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctg(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_glacctno":
				case "u_glacctname":
					setTableInput(table,"u_slcode","");
					setTableInput(table,"u_sldesc","");
					if (getTableInput(table,column)!="") {
						if (column=="u_glacctno") {
							if (getTableInput(table,"u_glacctno").length==8) {
								var s1="",s2="",s3="",s4="";
								s1 = getTableInput(table,"u_glacctno").substr(0,1);
								s2 = getTableInput(table,"u_glacctno").substr(1,2);
								s3 = getTableInput(table,"u_glacctno").substr(3,2);
								s4 = getTableInput(table,"u_glacctno").substr(5,3);
								setTableInput(table,"u_glacctno",s1+"-"+s2+"-"+s3+"-"+s4);
							}
							result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						} else  result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
					}
					break;
				case "u_slcode":
				case "u_sldesc":
					if (getTableInput(table,column)!="") {
						if (isInputEmpty("u_profitcenter")) return false;
						if (isTableInputEmpty("T1","u_glacctno")) return false;
						if (column=="u_slcode") result = page.executeFormattedQuery("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput(table,"u_glacctno")+"' and u_code = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput(table,"u_glacctno")+"' and u_description like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_slcode",result.childNodes.item(0).getAttribute("u_code"));
								setTableInput(table,"u_sldesc",result.childNodes.item(0).getAttribute("u_description"));
							} else {
								setTableInput(table,"u_slcode","");
								setTableInput(table,"u_sldesc","");
								page.statusbar.showError("Invalid S/L.");	
								return false;
							}
						} else {
							setTableInput(table,"u_slcode","");
							setTableInput(table,"u_sldesc","");
							page.statusbar.showError("Error retrieving subsidiary record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_slcode","");
						setTableInput(table,"u_sldesc","");
					}
					break;
				case "u_amount":
					if (getInput("u_autonegatedvalues")=="1") { 
						if (getTableInput(table,"u_glacctno").substr(0,1)<=2 && getTableInputNumeric(table,"u_amount")>0) {
							setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_amount")*-1);
						}
					}
					break;
			}
			break;
		default:
			switch(column) {
				case "u_bpcode":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select suppno, suppname from suppliers where suppno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bpcode",result.childNodes.item(0).getAttribute("suppno"));
								setInput("u_bpname",result.childNodes.item(0).getAttribute("suppname"));
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								page.statusbar.showError("Invalid Supplier Code.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							page.statusbar.showError("Error retrieving supplier record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
					}
					break;
				case "u_profitcenter":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcenter = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								setInput("u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
							} else {
								setInput("u_profitcenter","");
								setInput("u_profitcentername","");
								page.statusbar.showError("Invalid Profit Center.");	
								return false;
							}
						} else {
							setInput("u_profitcenter","");
							setInput("u_profitcentername","");
							page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_profitcenter","");
						setInput("u_profitcentername","");
					}
					break;
				case "u_osno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select docno from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_osno",result.childNodes.item(0).getAttribute("docno"));
								u_setOBRAccountsGPSLGUAcctg();
							} else {
								setInput("u_osno","");
								u_setOBRAccountsGPSLGUAcctg();
								page.statusbar.showError("Invalid Obligation Request No.");	
								return false;
							}
						} else {
							setInput("u_osno","");
							u_setOBRAccountsGPSLGUAcctg();
							page.statusbar.showError("Error retrieving obligation request record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_osno","");
						u_setOBRAccountsGPSLGUAcctg();
					}
					break;
				case "u_date":
					setInput("u_jevno","");
					break;
				case "u_lvatperc":
				case "u_levatperc":
				case "u_levat2perc":
				case "u_stalecheckamount":
					computeTotalGPSLGUAcctg();
					break;
				case "u_lvatamount":
				case "u_levatamount":
				case "u_levat2amount":
					computeTotalGPSLGUAcctg(true);
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_jevseries":
					setInput("u_jevno","");
					break;
				case "u_stalecheckbank":
					ajaxloadhousebankaccounts("df_u_stalecheckbankacctno",getGlobal("branch"),"PH",getInput("u_stalecheckbank"),'',":");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_vatable":
					computeTotalGPSLGUAcctg();
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		case "df_u_bpcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno, suppname from suppliers")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Supplier Code`Supplier Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_profitcenter":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter, profitcentername from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_osno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_bpname, u_remarks from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Payee`Remarks")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`35`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date```")); 			
			break;
		case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_slcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput("T1","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_sldescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_description, u_code  from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput("T1","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_glacctno");
			break;
	}
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (isTableInputZero(table,"u_amount")) return false; 
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (isTableInputZero(table,"u_amount")) return false; 
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
	var params = new Array();
	return params;
}

function computeTotalGPSLGUAcctg(adjusted) {
	if (adjusted==null) adjusted=false;
	var rc = 0, dueamount=0, vatableamount=0, evat1=0, evat2=0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			dueamount += getTableInputNumeric("T1","u_amount",i);
			vatableamount += getTableInputNumeric("T1","u_amount",i);
			if (getTableInputNumeric("T1","u_evat",i)==1) {
				evat1 += getTableInputNumeric("T1","u_amount",i);
			} else {
				evat2 += getTableInputNumeric("T1","u_amount",i);
			}
		}
	}
	
	dueamount+=getInputNumeric("u_stalecheckamount");

	setInputAmount("u_evat1",evat1);
	setInputAmount("u_evat2",evat2);
	setInputAmount("u_dueamount",dueamount);
	vatableamount = dueamount;

	if (getInput("u_apwtaxcategory")=="Invoice") {
		setInputAmount("u_lbaseamount",dueamount);
		setInputAmount("u_levatbaseamount",evat1);
		setInputAmount("u_levat2baseamount",evat2);
		if (!adjusted) {
			if (getInputNumeric("u_lvatperc")>0 || getInputNumeric("u_levatperc")>0) {
				if (rc>0) {
					if (getInput("u_vatable")=="1") {
						vatableamount = utils.round(vatableamount / 1.12,2);
						evat1 = utils.round(evat1 / 1.12,2);
						evat2 = utils.round(evat2 / 1.12,2);
					}
					setInputAmount("u_lvatamount",(getInputNumeric("u_lvatperc")/100) * vatableamount);
					setInputAmount("u_levatamount",(getInputNumeric("u_levatperc")/100) * evat1);
					setInputAmount("u_levat2amount",(getInputNumeric("u_levat2perc")/100) * evat2);
					setInputAmount("u_lamount",getInputNumeric("u_lvatamount")+getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount"));
					dueamount -= getInputNumeric("u_lamount");
				} else {
					advanceamount = (advanceamount / (100 - (getInputNumeric("u_lvatperc")+getInputNumeric("u_levatperc"))))*100;
					vatableamount = advanceamount;
					setInput("u_vatable",0);
					//if (getInput("u_vatable")=="1") vatableamount = Math.round(advanceamount / 1.12,2);
					setInputAmount("u_lvatamount",(getInputNumeric("u_lvatperc")/100) * vatableamount);
					setInputAmount("u_levatamount",(getInputNumeric("u_levatperc")/100) * vatableamount);
					setInputAmount("u_levat2perc",0);
					setInputAmount("u_levat2amount",0);
					setInputAmount("u_lamount",getInputNumeric("u_lvatamount")+getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount"));
					//advanceamount += getInputNumeric("u_lamount");
				}
			}
		} else {
			setInputAmount("u_lamount",getInputNumeric("u_lvatamount")+getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount"));
			dueamount -= getInputNumeric("u_lamount");
		}
	}
}

function u_setOBRAccountsGPSLGUAcctg() {
	var data = new Array();
	var osno = "";
	if (getInput("u_osno")!="") {
		osno = "'"+getInput("u_osno")+"'";
	}
	/*rc =  getTableRowCount("T4");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			if (osno!="") osno += ", ";
			osno += "'"+getTableInput("T4","u_osno",i)+"'";
		}
	}*/
	clearTable("T1",true);
	if (osno!="") {
		var result = page.executeFormattedQuery("select b.u_profitcenter, b.u_profitcentername, b.u_glacctno, b.u_glacctname, b.u_slcode, b.u_sldesc, sum(b.u_debit) as u_debit, sum(b.u_credit) as u_credit, sum(b.u_debit)-sum(b.u_credit) as u_amount from u_lguobligationslips a inner join u_lguobligationslipaccts b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno in ("+osno+") group by b.u_profitcenter, b.u_glacctno, b.u_slcode");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					data["u_profitcenter"] = result.childNodes.item(iii).getAttribute("u_profitcenter");
					data["u_profitcentername"] = result.childNodes.item(iii).getAttribute("u_profitcentername");
					data["u_glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctno");
					data["u_glacctname"] = result.childNodes.item(iii).getAttribute("u_glacctname");
					data["u_slcode"] = result.childNodes.item(iii).getAttribute("u_slcode");
					data["u_sldesc"] = result.childNodes.item(iii).getAttribute("u_sldesc");
					data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
					//data["u_debit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_debit"));
					//data["u_credit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_credit"));
					insertTableRowFromArray("T1",data);
				}
			}
		} else {
			page.statusbar.showError("Error retrieving budget balance. Try Again, if problem persists, check the connection.");	
			return false;
		}		
	}
}

