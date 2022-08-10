// page events
page.events.add.load('onPageLoadGPSLGUAcctg');
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
page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');

/*function onPageReportGetParamsGPSLGUAcctg(formattype,params) {
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
}*/

function onPageLoadGPSLGUAcctg() {
	if (getInput("docstatus")=="D" && getInput("docseries")=="-1") {
		enableInput("docno");
	}
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
		
		if (getTableInput("T1","u_glacctno")!="") {
			page.statusbar.showError("An item is currently being added/edited.");
			return false;
		}
		
		if (getInput("docstatus")!="D") {
			if (getInputNumeric("u_totaldebit")+getInputNumeric("u_totalcredit")==0) {
				page.statusbar.showError("Debit and Credit must have values.");
				return false;
			}
			if (getInputNumeric("u_variance")!=0) {
				page.statusbar.showError("Total Debit and Credit must be equal.");
				return false;
			}
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
							if (getInput("u_ob")==1) {
								result = page.executeFormattedQuery("select formatcode, acctname, cashinbankacct from chartofaccounts where postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
							} else {
								result = page.executeFormattedQuery("select formatcode, acctname, cashinbankacct from chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
							}
						} else {
							if (getInput("u_ob")==1) {
								result = page.executeFormattedQuery("select formatcode, acctname, cashinbankacct from chartofaccounts where postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");
							} else {
								result = page.executeFormattedQuery("select formatcode, acctname, cashinbankacct from chartofaccounts where ctrlacct=0 and postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");
							}
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
								setTableInput(table,"u_isbank",result.childNodes.item(0).getAttribute("cashinbankacct"));
							} else {
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								setTableInput(table,"u_isbank","0");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							setTableInput(table,"u_isbank","0");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
						setTableInput(table,"u_isbank","0");
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
			}
			break;
		default:
			switch(column) {
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
				case "u_date":
					if (getInput("docseries")!=-1) {
						setDocNo(null,null,null,"u_date");
					} else {
						setInput("docno","");
					}
					break;
				case "docno":
					if (isInputEmpty("u_date")) return false;
					var year = formatDateToDB(getInput("u_date")).substr(0,4);
					var month = formatDateToDB(getInput("u_date")).substr(5,2);
					if (getInput("docno").length>4) {
						setInput("docno","MOB-"+getGlobal("branch")+"-"+year+"-"+month+"-GJ"+getInput("docno"));
					} else {
						setInput("docno","MOB-"+getGlobal("branch")+"-"+year+"-"+month+"-GJ"+getInput("docno").padL(4,"0"));
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
		case "T1":
			switch (column) {
				case "u_bank":
					ajaxloadhousebankaccounts("df_u_bankacctnoT1",getGlobal("branch"),"PH",getTableInput("T1","u_bank"),'',":");
					break;
				case"u_bankacctno":
					var result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getTableInput("T1","u_bank"),getTableInput("T1","u_bankacctno"));
					/*setTableInput("T2","u_bankbranch",result.getAttribute("bankbranch"));
					if (result.getAttribute("active")==0) {
						setStatusMsg("Bank Account is not active anymore.");
						return false;
					}*/
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		case "df_u_profitcenter":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter, profitcentername from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnoT1":
			if (getInput("u_ob")==1) {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where postable=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
			}
			break;
		case "df_u_glacctnameT1":
			if (getInput("u_ob")==1) {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where postable=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 		
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 		
			}
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
			if (getTableInputNumeric(table,"u_debit")+getTableInputNumeric(table,"u_credit")==0) {
				if (getInputNumeric("u_variance")>0) setTableInputAmount(table,"u_credit",getInputNumeric("u_variance"));
				if (getInputNumeric("u_variance")<0) setTableInputAmount(table,"u_debit",getInputNumeric("u_variance")*-1);
				/*page.statusbar.showError("Debit or Credit must be entered.");
				focusTableInput(table,"u_debit");*/
				//return false;
			}
			if (getTableInputNumeric(table,"u_isbank")=="1") {
				if (isTableInputEmpty(table,"u_bank")) return false; 
				if (isTableInputEmpty(table,"u_bankacctno")) return false; 
			}
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
			if (getTableInputNumeric(table,"u_debit")+getTableInputNumeric(table,"u_credit")==0) {
				page.statusbar.showError("Debit or Credit must be entered.");
				focusTableInput(table,"u_debit");
				return false;
			}
			if (getTableInputNumeric(table,"u_isbank")=="1") {
				if (isTableInputEmpty(table,"u_bank")) return false; 
				if (isTableInputEmpty(table,"u_bankacctno")) return false; 
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableAfterEditRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": 
			ajaxloadhousebankaccounts("df_u_bankacctnoT1",getGlobal("branch"),"PH",getTableInput("T1","u_bank"),getTableInput("T1","u_bankacctno",row),":");
			break;
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

function computeTotalGPSLGUAcctg() {
	var rc = 0, debit=0, credit=0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			debit += getTableInputNumeric("T1","u_debit",i);
			credit += getTableInputNumeric("T1","u_credit",i);
		}
	}

	setInputAmount("u_totaldebit",debit);
	setInputAmount("u_totalcredit",credit);
	setInputAmount("u_variance",debit-credit);
}

