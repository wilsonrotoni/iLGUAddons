// page events
page.events.add.load('onPageLoadGPSLGUAcctg');
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
page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
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
	if (getInput("docstatus")=="D") {
		enableInput("docno");
	}
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	if (action=="a" || action=="sc") {
		
		if (getInput("docstatus")!="D") {
			if (getPrivate("approver")!="1") {
				page.statusbar.showError("You must be an approver to add this document.");
				return false;
			}
		} else {
			if (getPrivate("encoder")!="1" && getPrivate("approver")!="1") {
				page.statusbar.showError("You must be an encoder/approver to update this document.");
				return false;
			}
		}
		
		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_bpname")) return false;
		if (isInputEmpty("u_jevseries")) return false;
		
		if (getInput("docstatus")=="O") {
			if (isInputEmpty("u_jevno")) return false;
		}
		
		if (getInputNumeric("u_ob")==0) {
			if (getInput("docstatus")=="O") {
				if (isInputEmpty("u_checkbank")) return false;
				if (isInputEmpty("u_checkbankacctno")) return false;
				if (getInput("u_paytype")=="CHQ") {
					if (isInputEmpty("u_checkno")) return false;
				} else if (getInput("u_paytype")=="TF") {
					if (isInputEmpty("u_tfno")) return false;
				}
			}
		}
		//if (isInputNegative("u_checkamount")) return false;

		if (isInputChecked("u_tf")) {
			if (isInputEmpty("u_tfbank")) return false;
			if (isInputEmpty("u_tfbankacctno")) return false;
		}

		if (getInputNumeric("u_advanceamount")<0) {
			page.statusbar.showError("Advance amount cannot be negative.");
			return false;
		}
		if (getInputNumeric("u_advanceamount")>0) {
			if (isInputEmpty("u_bpcode")) return false;
		}
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
				case "u_debit":
					setTableInputAmount(table,"u_credit",0);
					setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_debit")-getTableInputNumeric(table,"u_credit"));
					break;
				case "u_credit":
					setTableInputAmount(table,"u_debit",0);
					setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_debit")-getTableInputNumeric(table,"u_credit"));
					break;
				case "u_amount":
					setTableInputAmount(table,"u_eeshare",0);
					setTableInputAmount(table,"u_ershare",0);
					break;
				case "u_eeshare":
				case "u_ershare":
					setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_eeshare")+getTableInputNumeric(table,"u_ershare"));
					setTableInputAmount(table,"u_debit",getTableInputNumeric(table,"u_eeshare")+getTableInputNumeric(table,"u_ershare"));
					break;
				case "u_profitcenter":
					setTableInput(table,"u_slcode","");
					setTableInput(table,"u_sldesc","");
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcenter = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								setTableInput(table,"u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
							} else {
								setTableInput(table,"u_profitcenter","");
								setTableInput(table,"u_profitcentername","");
								page.statusbar.showError("Invalid Profit Center.");	
								return false;
							}
						} else {
							setTableInput(table,"u_profitcenter","");
							setTableInput(table,"u_profitcentername","");
							page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_profitcenter","");
						setTableInput(table,"u_profitcentername","");
					}
					break;
				case "u_slcode":
				case "u_sldesc":
					if (getTableInput(table,column)!="") {
						var profitcenter = getInput("u_profitcenter");
						if (getTableInput("T1","u_profitcenter")!="") profitcenter = getTableInput("T1","u_profitcenter");
						if (profitcenter=="") {
							page.statusbar.showError('Profit Center is required.');
							return false;
						}
						if (isTableInputEmpty("T1","u_glacctno")) return false;
						if (column=="u_slcode") result = page.executeFormattedQuery("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+profitcenter+"' and u_glacctno='"+getTableInput(table,"u_glacctno")+"' and u_code = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+profitcenter+"' and u_glacctno='"+getTableInput(table,"u_glacctno")+"' and u_description like '"+getTableInput(table,column)+"%'");	
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
		case "T2":
			switch (column) {
				case "u_amount":
					if (getTableInputNumeric(table,"u_amount",row)<0) {
						setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_amount",row)*-1,row);
					}
					/*if (getTableInputNumeric(table,"u_amount",row)<0) {
						setTableInput(table,"u_amount",0,row);
						page.statusbar.showWarning("You cannot enter negated amount.");
					}*/
					if (getTableInputNumeric(table,"u_amount",row)>getTableInputNumeric(table,"u_refbalance",row)) {
						setTableInput(table,"u_amount",getTableInputNumeric(table,"u_refbalance",row),row);
						page.statusbar.showWarning("You cannot apply more than the balance amount.");
					}
					computeTotalGPSLGUAcctg();
					break;
			}
			break;
		case "T3":
			switch (column) {
				case "u_amount":
					if (getTableInputNumeric(table,"u_refbalance",row)<0 && getTableInputNumeric(table,"u_amount",row)>0) {
						setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_amount",row)*-1,row);
					}
					if (getTableInputNumeric(table,"u_refbalance",row)>0 && getTableInputNumeric(table,"u_amount",row)<0) {
						setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_amount",row)*-1,row);
					}
					if (getTableInputNumeric(table,"u_refbalance",row)<0) {
						if ((getTableInputNumeric(table,"u_amount",row)*-1)>(getTableInputNumeric(table,"u_refbalance",row)*-1)) {
							setTableInput(table,"u_amount",getTableInputNumeric(table,"u_refbalance",row),row);
							page.statusbar.showWarning("You cannot apply more than the balance amount.");
						}
					} else {
						if (getTableInputNumeric(table,"u_amount",row)>getTableInputNumeric(table,"u_refbalance",row)) {
							setTableInput(table,"u_amount",getTableInputNumeric(table,"u_refbalance",row),row);
							page.statusbar.showWarning("You cannot apply more than the balance amount.");
						}
					}
					computeTotalGPSLGUAcctg();
					break;
			}
			break;
		case "T4":
			switch (column) {
				case "u_osno":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select docno,u_checkamount from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O' and docno = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_osno",result.childNodes.item(0).getAttribute("docno"));
								setTableInput(table,"u_amount",formatNumericAmount(result.childNodes.item(0).getAttribute("u_checkamount")));
							} else {
								setTableInput(table,"u_osno","");
								setTableInput(table,"u_amount","");
								page.statusbar.showError("Invalid Obligation Request No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_osno","");
							setTableInput(table,"u_amount","");
							page.statusbar.showError("Error retrieving obligation request record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_osno","");
						setTableInput(table,"u_amount","");
					}
					break;
			}
			break;
		default:
			switch(column) {
				case "u_bpcode":
					if (getInput(column)!="") {
						if (getInput("u_bptype")=="C") {
							result = page.executeFormattedQuery("select custno, custname, '' as city, 0 as u_vatable, 0 as u_evatperc, 0 as u_ewtaxperc, 0 as u_retperc from customers where custno = '"+getInput(column)+"'");
						} else {
							result = page.executeFormattedQuery("select a.suppno, a.suppname, b.city, a.u_vatable, a.u_evatperc, a.u_ewtaxperc, a.u_retperc from suppliers a left join addresses b on b.reftype='SUPPLIER' and b.refid=a.suppno and b.addresstype=0 and b.addressname=a.dfltbillto where a.suppno = '"+getInput(column)+"'");
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								if (getInput("u_bptype")=="C") {
									setInput("u_bpcode",result.childNodes.item(0).getAttribute("custno"));
									setInput("u_bpname",result.childNodes.item(0).getAttribute("custname"));
									setInput("u_bpcity",result.childNodes.item(0).getAttribute("city"));
								} else {
									setInput("u_bpcode",result.childNodes.item(0).getAttribute("suppno"));
									setInput("u_bpname",result.childNodes.item(0).getAttribute("suppname"));
									setInput("u_bpcity",result.childNodes.item(0).getAttribute("city"));
									setInput("u_vatable",result.childNodes.item(0).getAttribute("u_vatable"));
									setInputPercent("u_levatperc",result.childNodes.item(0).getAttribute("u_evatperc"));
									setInputPercent("u_levat2perc",result.childNodes.item(0).getAttribute("u_ewtaxperc"));
									setInputPercent("u_lretperc",result.childNodes.item(0).getAttribute("u_retperc"));
								}
								if(getInput("u_advall")=="0") getAdvGPSLGUAcctg();
								computeBTGPSLGUAcctg();
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								setInput("u_bpcity","");
								setInput("u_vatable",1);
								setInputPercent("u_levatperc",0);
								setInputPercent("u_levat2perc",0);
								setInputPercent("u_lretperc",0);
								if(getInput("u_advall")=="0") getAdvGPSLGUAcctg();
								computeBTGPSLGUAcctg();
								page.statusbar.showError("Invalid Payee Code.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							setInput("u_bpcity","");
							setInput("u_vatable",1);
							setInputPercent("u_levatperc",0);
							setInputPercent("u_levat2perc",0);
							setInputPercent("u_lretperc",0);
							if(getInput("u_advall")=="0") getAdvGPSLGUAcctg();
							computeBTGPSLGUAcctg();
							page.statusbar.showError("Error retrieving business partner record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
						setInput("u_bpcity","");
						setInput("u_vatable",1);
						setInputPercent("u_levatperc",0);
						setInputPercent("u_levat2perc",0);
						setInputPercent("u_lretperc",0);
						if(getInput("u_advall")=="0") getAdvGPSLGUAcctg();
						computeBTGPSLGUAcctg();
					}
					break;
				case "u_apbpcode":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select suppno, suppname from suppliers where suppno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_apbpcode",result.childNodes.item(0).getAttribute("suppno"));
								setInput("u_apbpname",result.childNodes.item(0).getAttribute("suppname"));
								getPayGPSLGUAcctg();
							} else {
								setInput("u_apbpcode","");
								setInput("u_apbpname","");
								getPayGPSLGUAcctg();
								page.statusbar.showError("Invalid Payee Code.");	
								return false;
							}
						} else {
							setInput("u_apbpcode","");
							setInput("u_apbpname","");
							getPayGPSLGUAcctg();
							page.statusbar.showError("Error retrieving supplier record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_apbpcode","");
						setInput("u_apbpname","");
						getPayGPSLGUAcctg();
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
				case "u_refundglacctno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where formatcode = '"+getInput(column)+"' and postable=1 and ctrlacct=0");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_refundglacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setInput("u_refundglacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setInput("u_refundglacctno","");
								setInput("u_refundglacctname","");
								page.statusbar.showError("Invalid G/L Account No.");	
								return false;
							}
						} else {
							setInput("u_refundglacctno","");
							setInput("u_refundglacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_refundglacctno","");
						setInput("u_refundglacctname","");
					}
					break;
				case "u_requestedbyname":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, name from u_lgusignatories where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_requestedbyname",result.childNodes.item(0).getAttribute("code"));
								setInput("u_requestedbyposition",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_requestedbyname","");
								setInput("u_requestedbyposition","");
								page.statusbar.showError("Invalid Signatory.");	
								return false;
							}
						} else {
							setInput("u_requestedbyname","");
							setInput("u_requestedbyposition","");
							page.statusbar.showError("Error retrieving signatory record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_requestedbyname","");
						setInput("u_requestedbyposition","");
					}
					break;
				case "u_osno":
					if (getInput(column)!="") {
						if (getTableRowCount("T4",true)>0) {
							page.statusbar.showError("Multiple Obligation Request No exists!");
							selectTab("tab1",3);
							return false;
						}
						result = page.executeFormattedQuery("select c.docno, c.u_bptype, c.u_bpcode, c.u_bpname, b.city, ifnull(a.u_vatable,0) as u_vatable, ifnull(a.u_evatperc,0) as u_evatperc , ifnull(a.u_ewtaxperc,0) as u_ewtaxperc, ifnull(a.u_retperc,0) as u_retperc from u_lguobligationslips c left join suppliers a on a.suppno=c.u_bpcode left join addresses b on b.reftype='SUPPLIER' and b.refid=a.suppno and b.addresstype=0 and b.addressname=a.dfltbillto where c.company='"+getGlobal("company")+"' and c.branch='"+getGlobal("branch")+"' and c.docstatus='O' and c.docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_osno",result.childNodes.item(0).getAttribute("docno"));
								setInput("u_bptype",result.childNodes.item(0).getAttribute("u_bptype"));
								setInput("u_bpcode",result.childNodes.item(0).getAttribute("u_bpcode"));
								setInput("u_bpname",result.childNodes.item(0).getAttribute("u_bpname"));
								setInput("u_bpcity",result.childNodes.item(0).getAttribute("city"));
								setInput("u_vatable",result.childNodes.item(0).getAttribute("u_vatable"));
								setInputPercent("u_levatperc",result.childNodes.item(0).getAttribute("u_evatperc"));
								setInputPercent("u_levat2perc",result.childNodes.item(0).getAttribute("u_ewtaxperc"));
								setInputPercent("u_lretperc",result.childNodes.item(0).getAttribute("u_retperc"));
								setOSNOSLGUAcctg();
								u_setOBRAccountsGPSLGUAcctg();
							} else {
								setInput("u_osno","");
								setOSNOSLGUAcctg();
								u_setOBRAccountsGPSLGUAcctg();
								page.statusbar.showError("Invalid Obligation Request No.");	
								return false;
							}
						} else {
							setInput("u_osno","");
							setOSNOSLGUAcctg();
							u_setOBRAccountsGPSLGUAcctg();
							page.statusbar.showError("Error retrieving obligation request record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_osno","");
						setOSNOSLGUAcctg();
						u_setOBRAccountsGPSLGUAcctg();
					}
					break;
				case "u_checkno":
					setCHECKNOSLGUAcctg();
					break;
				case "u_checkamount":
				case "u_refundamount":
					computeTotalGPSLGUAcctg();
					break;
				case "u_date":
					setInput("u_jevno","");
					if (formatDateToDB(getInput("u_date"))<getPrivate("migratedate")) {
						setInput("u_ob",1);	
					} else {
						setInput("u_ob",0);
					}
					if (getInputNumeric("docseries")!=-1) {
						setDocNo(null,null,null,"u_date");
					} else {
						setInput("docno","");
					}
					
					break;
				case "u_bpcity":
					computeBTGPSLGUAcctg();
					computeTotalGPSLGUAcctg();
					break;
				case "u_lvatperc":
				case "u_levatperc":
				case "u_levat2perc":
				case "u_lretperc":
				case "u_lbtperc1":
				case "u_lbtperc2":
					computeTotalGPSLGUAcctg();
					break;
				case "u_lvatamount":
				case "u_levatamount":
				case "u_levat2amount":
				case "u_lretamount":
				case "u_lbtamount":
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
	switch (table) {
		default:
			switch (column) {
				case "u_taxdeduction":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_evatperc, u_ewtaxperc, u_retperc from u_lgutaxes where code = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputPercent("u_levatperc",result.childNodes.item(0).getAttribute("u_evatperc"));
								setInputPercent("u_levat2perc",result.childNodes.item(0).getAttribute("u_ewtaxperc"));
								setInputPercent("u_lretperc",result.childNodes.item(0).getAttribute("u_retperc"));
								computeTotalGPSLGUAcctg();
							} else {
								setInputPercent("u_levatperc",0);
								setInputPercent("u_levat2perc",0);
								setInputPercent("u_lretperc",0);
								computeTotalGPSLGUAcctg();
								page.statusbar.showError("Invalid Taxes/Retention");	
								return false;
							}
						} else {
							setInputPercent("u_levatperc",0);
							setInputPercent("u_levat2perc",0);
							setInputPercent("u_lretperc",0);
							computeTotalGPSLGUAcctg();
							page.statusbar.showError("Error retrieving obligation request record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputPercent("u_levatperc",0);
						setInputPercent("u_levat2perc",0);
						setInputPercent("u_lretperc",0);
						computeTotalGPSLGUAcctg();
					}
			}
			break;
	}
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_jevseries":
					setInput("u_jevno","");
					break;
				case "u_tfbank":
					ajaxloadhousebankaccounts("df_u_tfbankacctno",getGlobal("branch"),"PH",getInput("u_tfbank"),'',":");
					break;
			}
			break;
	}
		
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		case "T2":
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
			}
			break;
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
			}
			break;
		default:
			switch (column) {
				case "u_vatable":
					computeTotalGPSLGUAcctg();
					break;
				case "u_tf":
					if (isInputChecked("u_tf")) {
						enableInput("u_tfbank");
						enableInput("u_tfbankacctno");
						clearTable("T1",true);
					} else {
						disableInput("u_tfbank");
						disableInput("u_tfbankacctno");
					}
					computeTotalGPSLGUAcctg();
					break;
				case "u_advall":
					getAdvGPSLGUAcctg();
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
			if (getInput("u_bptype")=="C") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select custno, custname from customers")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Payee Code`Payee Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno, suppname from suppliers")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Payee Code`Payee Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 
			}
			break;
		case "df_u_apbpcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select suppno, suppname from suppliers")); 
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
		case "df_u_refundglacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_osno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_bpname, u_remarks,u_checkamount from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Payee`Remarks`OBR Amount")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`30`30`10")); 			
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
		case "df_u_profitcenterT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter, profitcentername from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_slcodeT1":
			var profitcenter = getInput("u_profitcenter");
			if (getTableInput("T1","u_profitcenter")!="") profitcenter = getTableInput("T1","u_profitcenter");
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+profitcenter+"' and u_glacctno='"+getTableInput("T1","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_sldescT1":
			var profitcenter = getInput("u_profitcenter");
			if (getTableInput("T1","u_profitcenter")!="") profitcenter = getTableInput("T1","u_profitcenter");
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_description, u_code  from u_lgupcsubsidiaryaccts where code='"+profitcenter+"' and u_glacctno='"+getTableInput("T1","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_remarksT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_acct from u_lgusubsidiaryaccts where code='"+getTableInput("T1","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Subsidiary")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_osnoT4":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_bpname, u_remarks from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Payee`Remarks")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`35`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date```")); 			
			break;
		case "df_u_requestedbyname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lgusignatories")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name`Position")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`50")); 			
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
			if (isInputChecked("u_tf")) {
				page.statusbar.showError("You cannot add accounts if fund transfer is selected.");	
				return false;
			}
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (isTableInputZero(table,"u_amount")) {
				page.statusbar.showError("Debit or Credit must be entered.");	
				focusTableInput(table,"u_debit");
				return false;
			}
			break;
		case "T4": 
			if (isTableInputEmpty(table,"u_osno")) return false; 
			break;
		case "T5": 
			if (isTableInputEmpty(table,"u_checkno")) return false; 
			if (isTableInputNegative(table,"u_checkamount")) return false; 
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1":
			computeBTGPSLGUAcctg();
			computeTotalGPSLGUAcctg(); break;
		case "T4":
			setInput("u_osno","");
			setOSNOSLGUAcctg();
			u_setOBRAccountsGPSLGUAcctg();
			break;
		case "T5": 
			setCHECKNOSLGUAcctg();
			computeTotalGPSLGUAcctg(); break;	
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (isTableInputZero(table,"u_amount")) {
				page.statusbar.showError("Debit or Credit must be entered.");	
				focusTableInput(table,"u_debit");
				return false;
			}
			break;
		case "T4": 
			if (isTableInputEmpty(table,"u_osno")) return false; 
			break;
		case "T5": 
			if (isTableInputEmpty(table,"u_checkno")) return false; 
			if (isTableInputNegative(table,"u_checkamount")) return false; 
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1":
			computeBTGPSLGUAcctg();
			computeTotalGPSLGUAcctg(); break;
		case "T4":
			setInput("u_osno","");
			setOSNOSLGUAcctg();
			u_setOBRAccountsGPSLGUAcctg();
			break;
		case "T5": 
			setCHECKNOSLGUAcctg();
			computeTotalGPSLGUAcctg(); break;	
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1":
			computeBTGPSLGUAcctg();
			computeTotalGPSLGUAcctg(); break;
		case "T4":
			setInput("u_osno","");
			setOSNOSLGUAcctg();
			u_setOBRAccountsGPSLGUAcctg();
			break;
		case "T5":
			setCHECKNOSLGUAcctg();
			computeTotalGPSLGUAcctg(); break;	
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

function computeBTGPSLGUAcctg() {
	var vatableamount=0;
	if (getPrivate("city")!=getInput("u_bpcity")) {
		var rc =  getTableRowCount("T1");
		for (i = 1; i <= rc; i++) {
			if (isTableRowDeleted("T1",i)==false) {
				if (getTableInputNumeric("T1","u_amount",i)>0) {
					vatableamount += getTableInputNumeric("T1","u_amount",i);
				}
			}
		}
		var result = page.executeFormattedQuery("select u_perc1, u_perc2, u_amount from u_lguobztaxes where u_gross<="+vatableamount+" order by u_gross desc limit 1");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				setInputPercent("u_lbtperc1",result.childNodes.item(0).getAttribute("u_perc1"));
				setInputPercent("u_lbtperc2",result.childNodes.item(0).getAttribute("u_perc2"));
				setInputAmount("u_lbtamount",result.childNodes.item(0).getAttribute("u_amount"));
			} else {
				setInputPercent("u_lbtperc1",0);	
				setInputPercent("u_lbtperc2",0);	
				setInputAmount("u_lbtamount",0);	
			}
		} else {
			page.statusbar.showError("Error retrieving journals. Try Again, if problem persists, check the connection.");	
			return false;
		}
	} else {
		setInputPercent("u_lbtperc1",0);	
		setInputPercent("u_lbtperc2",0);	
		setInputAmount("u_lbtamount",0);	
	}
}
	
function computeTotalGPSLGUAcctg(adjusted) {
	if (adjusted==null) adjusted=false;
	var rc = 0, rc2 = 0, dueamount=0, advanceamount=0, vatableamount=0, evat1=0,evat2=0, refundamount=0;
	
	advanceamount = getInputNumeric("u_checkamount");

	rc =  getTableRowCount("T5");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T5",i)==false) {
			advanceamount += getTableInputNumeric("T5","u_checkamount",i);
		}
	}
	
	if (isInputChecked("u_tf")) dueamount = advanceamount;

	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			//if (getTableInput("T1","u_glacctno",i)=='1-03-05-020' || getTableInput("T1","u_glacctno",i)=='1-03-05-010') {
			//	advanceamount += getTableInputNumeric("T1","u_amount",i)*-1;
			//} else {
				dueamount += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
				if (getTableInputNumeric("T1","u_amount",i)>0) {
					vatableamount += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
					if (getTableInputNumeric("T1","u_evat",i)==0 || getTableInputNumeric("T1","u_evat",i)==1) {
						evat1 += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
					}
					
					if (getTableInputNumeric("T1","u_evat",i)==0 || getTableInputNumeric("T1","u_evat",i)==2) {
						evat2 += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
					}
				}
			//}
		}
	}

	rc2 =  getTableRowCount("T3");
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			dueamount += getTableInputNumeric("T3","u_amount",i);
			if (getTableInputNumeric("T3","u_amount",i)>0) {
				vatableamount += getTableInputNumeric("T3","u_amount",i);
				evat1 += getTableInputNumeric("T3","u_refevat1",i)*(getTableInputNumeric("T3","u_amount",i)/getTableInputNumeric("T3","u_refamount",i));
				evat2 += getTableInputNumeric("T3","u_refevat1",i)*(getTableInputNumeric("T3","u_amount",i)/getTableInputNumeric("T3","u_refamount",i));
			}
		}
	}

	setInputAmount("u_lbaseamount",vatableamount);
	setInputAmount("u_levatbaseamount",evat1);
	setInputAmount("u_levat2baseamount",evat2);

	if (!adjusted) {
		if (getInputNumeric("u_lvatperc")>0 || getInputNumeric("u_levatperc")>0 || getInputNumeric("u_lretperc")>0 || getInputNumeric("u_lbtperc1")>0 || getInputNumeric("u_lbtperc2")>0) {
			if (rc+rc2>0) {
				if (getInput("u_vatable")=="1") {
					vatableamount = utils.round(vatableamount / 1.12,2);
					evat1 = utils.round(evat1 / 1.12,2);
					evat2 = utils.round(evat2 / 1.12,2);
				}
				setInputAmount("u_lvatamount",(getInputNumeric("u_lvatperc")/100) * vatableamount);
				setInputAmount("u_levatamount",(getInputNumeric("u_levatperc")/100) * evat1);
				setInputAmount("u_levat2amount",(getInputNumeric("u_levat2perc")/100) * evat2);//((getInputNumeric("u_lbtperc2")/100) * (
				setInputAmount("u_lretamount",(getInputNumeric("u_lretperc")/100) * getInputNumeric("u_lbaseamount"));
				if (getInputNumeric("u_lbtperc2")!=0 && getInputNumeric("u_lbtperc1")!=0) {
					setInputAmount("u_lbtamount",(getInputNumeric("u_lbtperc2")/100) *((getInputNumeric("u_lbtperc1")/100) * (getInputNumeric("u_lbaseamount")-getInputNumeric("u_levatamount")-getInputNumeric("u_levat2amount"))));
				}
				setInputAmount("u_lamount",getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount")+getInputNumeric("u_lretamount")+getInputNumeric("u_lbtamount"));
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
				setInputAmount("u_lretamount",0);
				setInputAmount("u_lbtamount",0)
				setInputAmount("u_lamount",getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount"));
				//advanceamount += getInputNumeric("u_lamount");
			}
		} else {
			setInputAmount("u_lamount",getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount")+getInputNumeric("u_lretamount")+getInputNumeric("u_lbtamount"));
		}
	} else {
		setInputAmount("u_lamount",getInputNumeric("u_lvatamount")+getInputNumeric("u_levatamount")+getInputNumeric("u_levat2amount"));
		dueamount -= getInputNumeric("u_lamount");
	}
	
	rc =  getTableRowCount("T2");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			dueamount -= getTableInputNumeric("T2","u_amount",i);
		}
	}
	setInputAmount("u_dueamount",dueamount + getInputNumeric("u_refundamount"));
	setInputAmount("u_advanceamount",advanceamount - dueamount - getInputNumeric("u_refundamount"));
}

function getAdvGPSLGUAcctg() {
	var data = new Array();
	clearTable("T2",true);
	if (getInput("u_apbpcode")=="") clearTable("T3",true);
	
	if (getInput("u_bpcode")!="" || getInput("u_advall")=="1") {
		if (getInput("u_advall")=="0") {
			var result = page.executeFormattedQuery("select a.docdate, b.docno, b.itemno, b.itemname, a.reference1, a.reference2, b.debit-b.credit as amount, c.u_evat1, c.u_evat2, b.balanceamount as balanceamount  from journalvouchers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.balanceamount<>0 left join u_lguaps c on c.company=a.company and c.branch=a.branch and c.u_jevno=a.docno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and b.itemno='"+getInput("u_bpcode")+"' and a.docstatus not in ('D')");
		} else {
			var result = page.executeFormattedQuery("select a.docdate, b.docno, b.itemno, b.itemname, a.reference1, a.reference2, b.debit-b.credit as amount, c.u_evat1, c.u_evat2, b.balanceamount as balanceamount  from journalvouchers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.balanceamount<>0 left join u_lguaps c on c.company=a.company and c.branch=a.branch and c.u_jevno=a.docno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and b.itemtype='C' and a.docstatus not in ('D') order by b.itemname, a.docdate");
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
					data["u_amount"] = formatNumericAmount(0);
					if (result.childNodes.item(iii).getAttribute("reference2")=="Check Disbursement" || result.childNodes.item(iii).getAttribute("reference2")=="Cash Disbursement" || result.childNodes.item(iii).getAttribute("reference2")=="") {
						data["u_refamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("amount"));
						data["u_refbalance"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("balanceamount"));
						insertTableRowFromArray("T2",data);
						disableTableInput("T2","u_amount",iii+1);
					} else {
						if (getInput("u_apbpcode")=="") {	
							data["u_refamount"] = formatNumericAmount(parseFloat(result.childNodes.item(iii).getAttribute("amount"))*-1);
							data["u_refbalance"] = formatNumericAmount(parseFloat(result.childNodes.item(iii).getAttribute("balanceamount"))*-1);
							data["u_refevat1"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_evat1"));
							data["u_refevat2"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_evat2"));
							insertTableRowFromArray("T3",data);
							disableTableInput("T3","u_amount",iii+1);
						}
					}
				}
			}
		} else {
			page.statusbar.showError("Error retrieving journals. Try Again, if problem persists, check the connection.");	
			return false;
		}
	}
}

function getPayGPSLGUAcctg() {
	var data = new Array();
	clearTable("T3",true);
	
	if (getInput("u_apbpcode")!="") {
		var result = page.executeFormattedQuery("select a.docdate, b.docno, a.reference1, a.reference2, b.credit-b.debit as amount, c.u_evat1, c.u_evat2, b.balanceamount*-1 as balanceamount  from journalvouchers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.balanceamount<>0 left join u_lguaps c on c.company=a.company and c.branch=a.branch and c.u_jevno=a.docno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and b.itemno='"+getInput("u_apbpcode")+"' and a.docstatus not in ('D') and a.reference2='Accounts Payable'");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					data["u_selected"] = 0;
					data["u_refdate"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("docdate"));
					data["u_refno"] = result.childNodes.item(iii).getAttribute("docno");
					data["u_refno2"] = result.childNodes.item(iii).getAttribute("reference1");
					data["u_amount"] = formatNumericAmount(0);
					data["u_refamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("amount"));
					data["u_refbalance"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("balanceamount"));
					data["u_refevat1"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_evat1"));
					data["u_refevat2"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_evat2"));
					insertTableRowFromArray("T3",data);
					disableTableInput("T3","u_amount",iii+1);
				}
			}
		} else {
			page.statusbar.showError("Error retrieving journals. Try Again, if problem persists, check the connection.");	
			return false;
		}
	}
}

function setOSNOSLGUAcctg() {
	var osnos="";
	rc =  getTableRowCount("T4");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			if (osnos!="") osnos += ", ";
			osnos += (getTableInput("T4","u_osno",i));
		}
	}
	if (getInput("u_osno")!="") {
		if (osnos!="") osnos = ", " + osnos;
		osnos = getInput("u_osno") + osnos;
	}
	setInput("u_osnos",osnos);
	
}

function setCHECKNOSLGUAcctg() {
	var checknos="";
	rc =  getTableRowCount("T5");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T5",i)==false) {
			if (checknos!="") checknos += ", ";
			checknos += (getTableInput("T5","u_checkno",i));
		}
	}
	if (getInput("u_checkno")!="") {
		if (checknos!="") checknos = ", " + checknos;
		checknos = getInput("u_checkno") + checknos;
	}
	setInput("u_checknos",checknos);
	
}

function u_copyGLGPSLGUAcctg() {
	var data = new Array(),rc = getTableSelectedRow("T1");
	var prefix="";
	var tagno="";
	if (getTableInput("T1","u_glacctno")!="") {
		page.statusbar.showWarning("You cannot copy if an item is currently being added/edited.");
		return;
	}
	if (rc>0) {

		setTableInput("T1","u_glacctno",getTableInput("T1","u_glacctno",rc));
		setTableInput("T1","u_glacctname",getTableInput("T1","u_glacctname",rc));
		setTableInput("T1","u_evat",getTableInput("T1","u_evat",rc));
		focusTableInput("T1","u_amount");
	}
}

function u_setOBRAccountsGPSLGUAcctg() {
	var data = new Array();
	var osno = "";
	if (getInput("u_osno")!="") {
		osno = "'"+getInput("u_osno")+"'";
	}
	rc =  getTableRowCount("T4");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			if (osno!="") osno += ", ";
			osno += "'"+getTableInput("T4","u_osno",i)+"'";
		}
	}
	clearTable("T1",true);
	if (osno!="") {
		var result = page.executeFormattedQuery("select b.u_profitcenter, b.u_profitcentername, b.u_glacctno, b.u_glacctname, b.u_slcode, b.u_sldesc, sum(b.u_debit-b.u_billedamount+b.u_adjamount) as u_debit, sum(b.u_credit) as u_credit, sum(b.u_debit-b.u_billedamount+b.u_adjamount)-sum(b.u_credit) as u_amount from u_lguobligationslips a inner join u_lguobligationslipaccts b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno in ("+osno+") group by b.u_profitcenter, b.u_glacctno, b.u_slcode");
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
					data["u_debit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_debit"));
					data["u_credit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_credit"));
					insertTableRowFromArray("T1",data);
				}
			}
		} else {
			page.statusbar.showError("Error retrieving budget balance. Try Again, if problem persists, check the connection.");	
			return false;
		}		
	}
}

