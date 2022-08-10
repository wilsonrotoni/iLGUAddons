// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUAcctg');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');
//page.events.add.reportgetparams('onPageReportGetParamsGPSLGUAcctg');

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
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSLGUAcctg');
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
		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_bpname")) return false;
		if (isInputEmpty("u_jevseries")) return false;
		if (isInputEmpty("u_jevno")) return false;

		//if (isInputEmpty("u_cashglacctno")) return false;
		//if (isInputEmpty("u_cashglacctname")) return false;
		//if (isInputNegative("u_cashamount")) return false;

		var rc =  getTableRowCount("T1");
		for (i = 1; i <= rc; i++) {
			if (isTableRowDeleted("T1",i)==false) {
				if (getTableInput("T1","u_glacctno",i)=='') {
					selectTableRow("T1",i);
					page.statusbar.showError("Invalid G/L Account No.");
					return false;	
				}
			}
		}


		if (getInputNumeric("u_variance")!=0) {
			page.statusbar.showError("Cannot have short/over.");
			return false;
		}
	}
	return true;
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
			}
			break;
		case "T3":
			switch (column) {
				case "u_amount":
					if (getTableInputNumeric(table,"u_amount",row)<0) {
						setTableInput(table,"u_amount",0,row);
						page.statusbar.showWarning("You cannot enter negated amount.");
					}
					if (getTableInputNumeric(table,"u_amount",row)>getTableInputNumeric(table,"u_refbalance",row)) {
						setTableInput(table,"u_amount",getTableInputNumeric(table,"u_refbalance",row),row);
						page.statusbar.showWarning("You cannot apply more than the balance amount.");
					}
					computeTotalGPSLGUAcctg();
					break;
			}
			break;
		default:
			switch(column) {
				case "u_bpcode":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select custno, custname from customers where custno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bpcode",result.childNodes.item(0).getAttribute("custno"));
								setInput("u_bpname",result.childNodes.item(0).getAttribute("custname"));
								//getAdvGPSLGUAcctg();
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								//getAdvGPSLGUAcctg();
								page.statusbar.showError("Invalid Payee Code.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							//getAdvGPSLGUAcctg();
							page.statusbar.showError("Error retrieving customer record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
						//getAdvGPSLGUAcctg();
					}
					break;
				case "u_advbpcode":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select custno, custname from customers where custno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_advbpcode",result.childNodes.item(0).getAttribute("custno"));
								setInput("u_advbpname",result.childNodes.item(0).getAttribute("custname"));
								getAdvGPSLGUAcctg();
							} else {
								setInput("u_advbpcode","");
								setInput("u_advbpname","");
								getAdvGPSLGUAcctg();
								page.statusbar.showError("Invalid Payee Code.");	
								return false;
							}
						} else {
							setInput("u_advbpcode","");
							setInput("u_advbpname","");
							getAdvGPSLGUAcctg();
							page.statusbar.showError("Error retrieving customer record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_advbpcode","");
						setInput("u_advbpname","");
						getAdvGPSLGUAcctg();
					}
					break;
				case "u_cashglacctno":
				case "u_cashglacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_cashglacctno") result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where cashacct=1 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where cashacct=1 and postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_cashglacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_cashglacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_cashglacctno","");
								setTableInput(table,"u_cashglacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_cashglacctno","");
							setTableInput(table,"u_cashglacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_cashglacctno","");
						setTableInput(table,"u_cashglacctname","");
					}
					break;
				case "u_cashamount":
					computeTotalGPSLGUAcctg();
					break;
				case "u_date":
					setInput("u_jevno","");
					break;
				case "u_jevno":
					if (isInputEmpty("u_date")) return false;
					if (isInputEmpty("u_jevseries")) return false;
					var year = formatDateToDB(getInput("u_date")).substr(0,4);
					var month = formatDateToDB(getInput("u_date")).substr(5,2);
					//setInput("u_jevno",getGlobal("branch")+"-"+getInput("u_jevseries")+"-"+year+"-"+month+"-"+getInput("u_jevno").padL(4,"0"));
					if (getInput("u_jevno").length>4) {
						setInput("u_jevno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("u_jevseries")+getInput("u_jevno"));
					} else {
						setInput("u_jevno",getGlobal("branch")+"-"+year+"-"+month+"-"+getInput("u_jevseries")+getInput("u_jevno").padL(4,"0"));
					}
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
		case "T2":
			switch (column) {
				case "u_bank":
					ajaxloadhousebankaccounts("df_u_bankacctnoT2",getGlobal("branch"),"PH",getTableInput("T2","u_bank"),'',":");
					break;
				case"u_bankacctno":
					var result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getTableInput("T2","u_bank"),getTableInput("T2","u_bankacctno"));
					/*setTableInput("T2","u_bankbranch",result.getAttribute("bankbranch"));
					if (result.getAttribute("active")==0) {
						setStatusMsg("Bank Account is not active anymore.");
						return false;
					}*/
					break;
			}
			break;
		default:
			switch (column) {
				case "u_jevseries":
					setInput("u_jevno","");
					break;
				case "u_checkbank":
					ajaxloadhousebankaccounts("df_u_checkbankacctno",getGlobal("branch"),"PH",getInput("u_checkbank"),'',":");
					break;
				/*case"u_bankacctno":
					result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getInput("u_tfbank"),getInput("u_tfbankacctno"));
					setInput("u_tfbankbranch",result.getAttribute("u_tfbankbranch"));
					break;*/
			}
			break;
	}
		
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		case "T3":
			switch (column) {
				case "u_selected":
					if (isTableInputChecked(table,column,row)) {
						setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_refbalance",row),row);
						enableTableInput(table,"u_amount",row);
						focusTableInput(table,"u_amount",row);
					} else {
						setTableInputAmount(table,"u_amount",0,row);
						disableTableInput(table,"u_amount",row);
					}
					computeTotalGPSLGUAcctg();
					break;
				case "u_tf":
					computeTotalGPSLGUAcctg();
					break;
			}
			break;
		default:
			switch (column) {
				case "u_advall":
					setInput("u_advbpcode","");
					setInput("u_advbpname","");
					getAdvGPSLGUAcctg();
					break;
			}
	}
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		case "df_u_bpcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select custno, custname from customers")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Payee Code`Payee Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_advbpcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select custno, custname from customers")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Payee Code`Payee Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_profitcenter":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter, profitcentername from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_cashglacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where cashacct=1 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
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
	}
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (isTableInputZero(table,"u_amount")) return false; 
			break;
		case "T2": 
			if (isTableInputEmpty(table,"u_bank")) return false; 
			if (isTableInputEmpty(table,"u_bankacctno")) return false; 
			if (isTableInputZero(table,"u_amount")) return false; 
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
		case "T2": computeTotalDepositGPSLGUAcctg(); break;
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (isTableInputZero(table,"u_amount")) return false; 
			break;
		case "T2": 
			if (isTableInputEmpty(table,"u_bank")) return false; 
			if (isTableInputEmpty(table,"u_bankacctno")) return false; 
			if (isTableInputZero(table,"u_amount")) return false; 
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
		case "T2": computeTotalDepositGPSLGUAcctg(); break;
	}
}

function onTableAfterEditRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T2": 
			ajaxloadhousebankaccounts("df_u_bankacctnoT2",getGlobal("branch"),"PH",getTableInput("T2","u_bank"),getTableInput("T2","u_bankacctno",row),":");
			break;
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
		case "T2": computeTotalDepositGPSLGUAcctg(); break;
	}
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
	var params = new Array();
	switch (table) {
		case "T2":
			params["focus"] = false;
			if (elementFocused.substring(0,13)=="df_u_amountT2") {
				focusTableInput(table,"u_amount",row);
			}
			break;
		case "T3":
			params["focus"] = false;
			if (elementFocused.substring(0,13)=="df_u_amountT3") {
				focusTableInput(table,"u_amount",row);
			}
			break;
	}
	return params;
}

function computeTotalGPSLGUAcctg() {
	var rc = 0, totalamount=0, cashamount=0, tf=0;
	
	cashamount = getInputNumeric("u_cashamount");

	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			//if (getTableInput("T1","u_glacctno",i)=='1-03-05-020' || getTableInput("T1","u_glacctno",i)=='1-03-05-010') {
			//	advanceamount += getTableInputNumeric("T1","u_amount",i)*-1;
			//} else {
				totalamount += getTableInputNumeric("T1","u_amount",i);
			//}
		}
	}

	rc =  getTableRowCount("T3");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			totalamount += getTableInputNumeric("T3","u_amount",i);
			if (getTableInput("T3","u_tf",i)==1) {
				tf += getTableInputNumeric("T3","u_amount",i);
			}
		}
	}


	setInputAmount("u_totalamount",totalamount);
	setInputAmount("u_variance",cashamount - totalamount + tf);
}

function computeTotalDepositGPSLGUAcctg() {
	var rc = 0, totalamount=0;
	
	rc =  getTableRowCount("T2");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			totalamount += getTableInputNumeric("T2","u_amount",i);
		}
	}

	setInputAmount("u_totaldeposit",totalamount);
}

function getAdvGPSLGUAcctg() {
	var data = new Array();
	clearTable("T3",true);
	if (getInput("u_advbpcode")!="" || getInputNumeric("u_advall")==1) {
		if (getInputNumeric("u_advall")==1) {
			var result = page.executeFormattedQuery("select a.docdate, b.docno, b.itemno, b.itemname, a.reference1, b.debit-b.credit as amount, b.balanceamount  from journalvouchers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.balanceamount<>0 where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and b.itemtype='C' and a.docstatus not in ('D')");
		} else {
			var result = page.executeFormattedQuery("select a.docdate, b.docno, b.itemno, b.itemname, a.reference1, b.debit-b.credit as amount, b.balanceamount  from journalvouchers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.balanceamount<>0 where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and b.itemno='"+getInput("u_advbpcode")+"' and a.docstatus not in ('D')");
		}
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					data["u_selected"] = 0;
					data["u_refdate"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("docdate"));
					data["u_bpcode"] = result.childNodes.item(iii).getAttribute("itemno");
					data["u_bpname"] = result.childNodes.item(iii).getAttribute("itemname");
					data["u_refno"] = result.childNodes.item(iii).getAttribute("docno");
					data["u_refno2"] = result.childNodes.item(iii).getAttribute("reference1");
					data["u_refamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("amount"));
					data["u_refbalance"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("balanceamount"));
					data["u_amount"] = formatNumericAmount(0);
					data["u_tf"] = 0;
					insertTableRowFromArray("T3",data);
					disableTableInput("T3","u_amount",iii+1);
				}
			}
		} else {
			page.statusbar.showError("Error retrieving advances. Try Again, if problem persists, check the connection.");	
			return false;
		}	
	}
}
/*
function getCollectionsGPSLGUAcctg() {
	if (isInputEmpty("u_datefrom")) return false;
	if (isInputEmpty("u_dateto")) return false;
	var data = new Array(),data2 = new Array();
	clearTable("T1",true);
	clearTable("T2",true);
	var result = page.executeFormattedQuery("select b.u_itemcode, b.u_itemdesc, ifnull(d.formatcode,'') as u_glacctno, ifnull(d.acctname,b.u_itemdesc) as u_glacctname, sum(b.u_linetotal) as u_amount from lgu.u_lgupos a inner join lgu.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join lgu.u_lgufees c on c.code=b.u_itemcode left join chartofaccounts d on d.formatcode=c.u_glacctcode where a.company='LGU' and a.branch='MAIN' and a.u_date between '"+formatDateToDB(getInput("u_datefrom"))+"' and '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('D') group by c.u_glacctcode, b.u_itemcode");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				data["u_index"] = 1;
				data["u_glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctno");
				data["u_glacctname"] = result.childNodes.item(iii).getAttribute("u_glacctname");
				data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
				insertTableRowFromArray("T1",data);
			}
		}
	} else {
		page.statusbar.showError("Error retrieving collections. Try Again, if problem persists, check the connection.");	
		return false;
	}	
	
	var result = page.executeFormattedQuery("select u_bank, u_bankacctno, sum(u_amount) as u_amount from u_lgubankdps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_date between '"+formatDateToDB(getInput("u_datefrom"))+"' and '"+formatDateToDB(getInput("u_dateto"))+"' and docstatus not in ('D') group by u_bank, u_bankacctno");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				data2["u_bank"] = result.childNodes.item(iii).getAttribute("u_bank");
				data2["u_bankacctno"] = result.childNodes.item(iii).getAttribute("u_bankacctno");
				data2["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
				insertTableRowFromArray("T2",data2);
			}
		}
	} else {
		page.statusbar.showError("Error retrieving bank deposits. Try Again, if problem persists, check the connection.");	
		return false;
	}	
	
}
*/

