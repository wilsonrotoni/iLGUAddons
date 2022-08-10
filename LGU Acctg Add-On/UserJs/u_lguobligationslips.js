// page events
page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUAcctg');
page.events.add.submitreturn('onPageSubmitReturnGPSLGUAcctg');
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
page.tables.events.add.afterEdit('onTableAfterEditRowGPSLGUAcctg');
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
		if (isInputEmpty("u_bpname")) return false;

		if (getInput("docstatus")!="D") {
			if (isInputEmpty("u_expclass")) return false;
			if (isInputEmpty("u_refno")) return false;
		}
		//if (isInputEmpty("u_profitcenter")) return false;
		if (getInput("u_profitcenter")=="") {
			rc =  getTableRowCount("T1");
			for (i = 1; i <= rc; i++) {
				if (isTableRowDeleted("T1",i)==false) {
					if (getTableInput("T1","u_profitcenter",i)=="") {
						selectTableRow("T1",i);
						page.statusbar.showError("Profit Center is required.");
						return false;
					}
				}
			}		
		}
		
		//if (isInputEmpty("u_jevseries")) return false;
		
		//if (getInput("docstatus")=="O") {
		//	if (isInputEmpty("u_jevno")) return false;
		//}
		
		//if (getInputNumeric("u_ob")==0) {
		//	if (isInputEmpty("u_checkbank")) return false;
		//	if (isInputEmpty("u_checkbankacctno")) return false;
		//	if (getInput("docstatus")=="O") {
		//		if (isInputEmpty("u_checkno")) return false;
		//	}
		//}
		if (getInput("u_adjobrno")!="") {
			if (isInputZero("u_checkamount")) return false;
		} else {
			if (isInputNegative("u_checkamount")) return false;
		}

		//if (isInputChecked("u_tf")) {
		//	if (isInputEmpty("u_tfbank")) return false;
		//	if (isInputEmpty("u_tfbankacctno")) return false;
		//}

		if (getInputNumeric("u_advanceamount")<0) {
			page.statusbar.showError("Advance amount cannot be negative.");
			return false;
		}
		if (getInputNumeric("u_advanceamount")>0) {
			if (isInputEmpty("u_bpcode")) return false;
		}
		
		if (getInput("docstatus")=="CN") {
			if (getInput("u_cancelleddate")=="" || getInput("u_cancelledremarks")=="") {
				showPopupFrame("popupFrameCancel",true);
				if (getInput("u_cancelleddate")=="") focusInput("u_cancelleddate");
				else if (getInput("u_cancelledremarks")=="") focusInput("u_cancelledremarks");
				return false;
			}
		}
	} else if (action=="cnd") {
		setInput("docstatus","CN");
		setInput("u_cancelledby",getGlobal("userid"));
		if (getInput("u_cancelleddate")=="" || getInput("u_cancelledremarks")=="") {
			showPopupFrame("popupFrameCancel",true);
			if (getInput("u_cancelleddate")=="") focusInput("u_cancelleddate");
			else if (getInput("u_cancelledremarks")=="") focusInput("u_cancelledremarks");
			return false;
		}
	}
	return true;
}

function onPageSubmitReturnGPSLGUAcctg(action,sucess,error) {
	if (!sucess && error.substring(0,13)=="Budget vs OBR") alert(error.replace(/`/g,"\r\n"));
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
	var data = new Array();
	switch (table) {
		case "T1":
			switch (column) {
				case "u_profitcenter":
					if (isInputEmpty("u_date")) return false;
					setTableInput(table,"u_slcode","");
					setTableInput(table,"u_sldesc","");
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcenter = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								setTableInput(table,"u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
								u_getBudgetBalanceLGUAcctg();
							} else {
								setTableInput(table,"u_profitcenter","");
								setTableInput(table,"u_profitcentername","");
								page.statusbar.showError("Invalid Profit Center.");	
								u_getBudgetBalanceLGUAcctg();
								return false;
							}
						} else {
							setTableInput(table,"u_profitcenter","");
							setTableInput(table,"u_profitcentername","");
							page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
							u_getBudgetBalanceLGUAcctg();
							return false;
						}
					} else {
						setTableInput(table,"u_profitcenter","");
						setTableInput(table,"u_profitcentername","");
						u_getBudgetBalanceLGUAcctg();
					}
					break;
				case "u_glacctno":
				case "u_glacctname":
					if (isInputEmpty("u_date")) return false;
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
								u_getBudgetBalanceLGUAcctg();
							} else {
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								u_getBudgetBalanceLGUAcctg();
								return false;
							}
						} else {
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							u_getBudgetBalanceLGUAcctg();
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
						u_getBudgetBalanceLGUAcctg();
					}
					break;
				case "u_slcode":
				case "u_sldesc":
					if (isInputEmpty("u_date")) return false;
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
								u_getBudgetBalanceLGUAcctg();
							} else {
								setTableInput(table,"u_slcode","");
								setTableInput(table,"u_sldesc","");
								page.statusbar.showError("Invalid S/L.");	
								u_getBudgetBalanceLGUAcctg();
								return false;
							}
						} else {
							setTableInput(table,"u_slcode","");
							setTableInput(table,"u_sldesc","");
							page.statusbar.showError("Error retrieving subsidiary record. Try Again, if problem persists, check the connection.");	
							u_getBudgetBalanceLGUAcctg();
							return false;
						}
					} else {
						setTableInput(table,"u_slcode","");
						setTableInput(table,"u_sldesc","");
						u_getBudgetBalanceLGUAcctg();
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
			}
			break;
		case "T2":
			switch (column) {
				case "u_amount":
					if (getTableInputNumeric(table,"u_amount",row)>0) {
						setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_amount",row)*-1,row);
					}
					/*if (getTableInputNumeric(table,"u_amount",row)<0) {
						setTableInput(table,"u_amount",0,row);
						page.statusbar.showWarning("You cannot enter negated amount.");
					}*/
					if ((getTableInputNumeric(table,"u_amount",row)*-1)>(getTableInputNumeric(table,"u_refbalance",row)*-1)) {
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
		default:
			switch(column) {
				case "u_bpcode":
					if (getInput(column)!="") {
						if (getInput("u_bptype")=="C") {
							result = page.executeFormattedQuery("select custno, custname from customers where custno = '"+getInput(column)+"'");
						} else {
							result = page.executeFormattedQuery("select suppno, suppname from suppliers where suppno = '"+getInput(column)+"'");
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								if (getInput("u_bptype")=="C") {
									setInput("u_bpcode",result.childNodes.item(0).getAttribute("custno"));
									setInput("u_bpname",result.childNodes.item(0).getAttribute("custname"));
								} else {
									setInput("u_bpcode",result.childNodes.item(0).getAttribute("suppno"));
									setInput("u_bpname",result.childNodes.item(0).getAttribute("suppname"));
								}
								getAdvGPSLGUAcctg();
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								getAdvGPSLGUAcctg();
								page.statusbar.showError("Invalid Payee Code.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							getAdvGPSLGUAcctg();
							page.statusbar.showError("Error retrieving business partner record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
						getAdvGPSLGUAcctg();
					}
					break;
				case "u_profitcenter":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select profitcenter, profitcentername, u_head, u_position from profitcenters where profitcenter = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								setInput("u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
								setInput("u_requestedbyname",result.childNodes.item(0).getAttribute("u_head"));
								setInput("u_requestedbyposition",result.childNodes.item(0).getAttribute("u_position"));
							} else {
								setInput("u_profitcenter","");
								setInput("u_profitcentername","");
								setInput("u_requestedbyname","");
								setInput("u_requestedbyposition","");
								page.statusbar.showError("Invalid Profit Center.");	
								return false;
							}
						} else {
							setInput("u_profitcenter","");
							setInput("u_profitcentername","");
							setInput("u_requestedbyname","");
							setInput("u_requestedbyposition","");
							page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_profitcenter","");
						setInput("u_profitcentername","");
						setInput("u_requestedbyname","");
						setInput("u_requestedbyposition","");
					}
					break;
				case "u_pono":
					if (isInputEmpty("u_date")) return false;
					setInput("u_billno","");
					setInput("u_adjobrno","");
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select a.docno, a.u_date, a.u_profitcenter, a.u_profitcentername, a.u_bpcode, a.u_bpname, a.u_totalamount, if(d.u_expenseglacctno<>'',d.u_expenseglacctno,d.u_glacctno) as u_glacctno, if(d.u_expenseglacctname<>'',d.u_expenseglacctname,d.u_glacctname) as u_glacctname, sum(b.u_cost) as u_totalcost from u_lgupurchaseorder a inner join u_lgupurchaseorderitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join u_lguitems c on c.code=b.u_itemcode inner join u_lguitemgroups d on d.code=c.u_itemgroup where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docstatus='D' and a.docno = '"+getInput(column)+"' group by u_glacctno union all select a.docno, a.u_date, a.u_profitcenter, a.u_profitcentername, a.u_bpcode, a.u_bpname, a.u_totalamount, b.u_glacctno, c.acctname as u_glacctname, sum(b.u_cost) as u_totalcost from u_lgupurchaseorder a inner join u_lgupurchaseorderservice b on b.company=a.company and b.branch=a.branch and b.docid=a.docid left join chartofaccounts c on c.formatcode=b.u_glacctno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docstatus='D' and a.docno = '"+getInput(column)+"' group by u_glacctno"); 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								//setInput("u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								//setInput("u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
								setInput("u_bptype","S");
								setInput("u_bpcode",result.childNodes.item(0).getAttribute("u_bpcode"));
								setInput("u_bpname",result.childNodes.item(0).getAttribute("u_bpname"));
								clearTable("T1",true);
								for (iii = 0; iii < result.childNodes.length; iii++) {
									data["u_profitcenter"] = result.childNodes.item(iii).getAttribute("u_profitcenter");
									data["u_profitcentername"] = result.childNodes.item(iii).getAttribute("u_profitcentername");
									data["u_glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctno");
									data["u_glacctname"] = result.childNodes.item(iii).getAttribute("u_glacctname");
									//data["u_glacctno"] = "5-02-03-990";
									data["u_slcode"] = "";
									data["u_debit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_totalcost"));
									data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_totalcost"));
									data["u_rlamt"] = "0.00";
									//alert("select sum(u_budgetamount) as u_budgetamount, sum(u_obramount) as u_obramount, ifnull(sum(u_budgetamount),0)-ifnull(sum(u_obramount),0) as u_balance from (select xd.u_rlamt as u_budgetamount, 0 as u_obramount from u_lgubudget xc inner join u_lgubudgetgls xd on xd.company=xc.company and xd.branch=xc.branch and xd.code=xc.code and xd.u_glacctno ='"+data["u_glacctno"]+"' and xd.u_slcode='"+data["u_slcode"]+"' where xc.company='"+getGlobal("company")+"' and xc.branch='"+getGlobal("branch")+"' and xc.u_yr='"+formatDateToDB(getInput("u_date")).substr(0,4)+"' and xc.u_profitcenter='"+data["u_profitcenter"]+"' union all select 0 as u_budgetamount, b.u_debit-b.u_credit as u_obramount from u_lguobligationslips a inner join u_lguobligationslipaccts b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_profitcenter='"+data["u_profitcenter"]+"' and b.u_glacctno='"+data["u_glacctno"]+"' and b.u_slcode='"+data["u_slcode"]+"' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and year(a.u_date)='"+formatDateToDB(getInput("u_date")).substr(0,4)+"') as xxx");
									var result2 = page.executeFormattedQuery("select sum(u_budgetamount) as u_budgetamount, sum(u_obramount) as u_obramount, ifnull(sum(u_budgetamount),0)-ifnull(sum(u_obramount),0) as u_balance from (select xd.u_rlamt as u_budgetamount, 0 as u_obramount from u_lgubudget xc inner join u_lgubudgetgls xd on xd.company=xc.company and xd.branch=xc.branch and xd.code=xc.code and xd.u_glacctno ='"+data["u_glacctno"]+"' and xd.u_slcode='"+data["u_slcode"]+"' where xc.company='"+getGlobal("company")+"' and xc.branch='"+getGlobal("branch")+"' and xc.u_yr='"+formatDateToDB(getInput("u_date")).substr(0,4)+"' and xc.u_profitcenter='"+data["u_profitcenter"]+"' union all select 0 as u_budgetamount, b.u_debit-b.u_credit-b.u_billedamount as u_obramount from u_lguobligationslips a inner join u_lguobligationslipaccts b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_profitcenter='"+data["u_profitcenter"]+"' and b.u_glacctno='"+data["u_glacctno"]+"' and b.u_slcode='"+data["u_slcode"]+"' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and year(a.u_date)='"+formatDateToDB(getInput("u_date")).substr(0,4)+"') as xxx");
									if (result2.getAttribute("result")!= "-1") {
										if (parseInt(result2.getAttribute("result"))>0) {
											data["u_rlamt"] = formatNumericAmount(result2.childNodes.item(0).getAttribute("u_balance"));
										}
									}		
		
									insertTableRowFromArray("T1",data);
								}
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								page.statusbar.showError("Invalid P.O. No.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							page.statusbar.showError("Error retrieving purchase order record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
					}
					break;
				case "u_billno":
					if (isInputEmpty("u_date")) return false;
					setInput("u_pono","");
					setInput("u_adjobrno","");
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select a.docno, a.u_date, a.u_profitcenter, a.u_profitcentername, a.u_bpcode, a.u_bpname, a.u_totalamount, if(d.u_expenseglacctno<>'',d.u_expenseglacctno,d.u_glacctno) as u_glacctno, if(d.u_expenseglacctname<>'',d.u_expenseglacctname,d.u_glacctname) as u_glacctname, sum(b.u_cost) as u_totalcost, e.u_obrno, f.u_expclass, f.u_billcount from u_lgusplitpo a inner join u_lgusplitpoitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join u_lguitems c on c.code=b.u_itemcode inner join u_lguitemgroups d on d.code=c.u_itemgroup inner join u_lgupurchaseorder e on e.company=b.company and e.branch=b.branch and e.docno=b.u_basedocno inner join u_lguobligationslips f on f.company=e.company and f.branch=e.branch and f.docno=e.u_obrno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docstatus='D' and a.docno = '"+getInput(column)+"' group by u_glacctno union all select a.docno, a.u_date, a.u_profitcenter, a.u_profitcentername, a.u_bpcode, a.u_bpname, a.u_totalamount, b.u_glacctno, c.acctname as u_glacctname, sum(b.u_cost) as u_totalcost, e.u_obrno, f.u_expclass, f.u_billcount from u_lgusplitpo a inner join u_lgusplitposervice b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join chartofaccounts c on c.formatcode=b.u_glacctno inner join u_lgupurchaseorder e on e.company=b.company and e.branch=b.branch and e.docno=b.u_basedocno inner join u_lguobligationslips f on f.company=e.company and f.branch=e.branch and f.docno=e.u_obrno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docstatus='D' and a.docno = '"+getInput(column)+"' group by u_glacctno");	 //
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								//setInput("u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								//setInput("u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
								setInput("u_bptype","S");
								setInput("u_bpcode",result.childNodes.item(0).getAttribute("u_bpcode"));
								setInput("u_bpname",result.childNodes.item(0).getAttribute("u_bpname"));
								setInput("u_mainosno",result.childNodes.item(0).getAttribute("u_obrno"));
								setInput("u_billcount",parseInt(result.childNodes.item(0).getAttribute("u_billcount"))+1);
								setInput("docseries",-1);
								setInput("docno",getInput("u_mainosno")+"/"+getInputNumeric("u_billcount"));
								setInput("u_expclass",result.childNodes.item(0).getAttribute("u_expclass"));
								isableInput("docseries");
								disableInput("docseries");
								disableInput("docno");
								disableInput("u_expclass");
								disableInput("u_refno");
								clearTable("T1",true);
								for (iii = 0; iii < result.childNodes.length; iii++) {
									data["u_profitcenter"] = result.childNodes.item(iii).getAttribute("u_profitcenter");
									data["u_profitcentername"] = result.childNodes.item(iii).getAttribute("u_profitcentername");
									data["u_glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctno");
									data["u_glacctname"] = result.childNodes.item(iii).getAttribute("u_glacctname");
									data["u_debit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_totalcost"));
									data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_totalcost"));
									insertTableRowFromArray("T1",data);
								}
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								page.statusbar.showError("Invalid Bill No.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							page.statusbar.showError("Error retrieving purchase order record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
					}
					break;
				case "u_adjobrno":
					if (isInputEmpty("u_date")) return false;
					setInput("u_pono","");
					setInput("u_billno","");
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select a.docno, a.u_bptype, a.u_bpcode, a.u_bpname, a.u_dueamount, a.u_adjcount, a.u_expclass, b.u_profitcenter, b.u_profitcentername, b.u_glacctno, b.u_glacctname, b.u_slcode, b.u_sldesc, b.u_debit-b.u_adjamount as u_debit, b.u_credit, b.u_amount, b.u_remarks from u_lguobligationslips a inner join u_lguobligationslipaccts b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docstatus='O' and u_mainosno='' and a.docno = '"+getInput(column)+"'");	 						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								//setInput("u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
								//setInput("u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
								setInput("u_bptype",result.childNodes.item(0).getAttribute("u_bptype"));
								setInput("u_bpcode",result.childNodes.item(0).getAttribute("u_bpcode"));
								setInput("u_bpname",result.childNodes.item(0).getAttribute("u_bpname"));
								setInput("u_mainosno",getInput("u_adjobrno"));
								setInput("u_adjcount",parseInt(result.childNodes.item(0).getAttribute("u_adjcount"))+1);
								setInput("docseries",-1);
								setInput("docno",getInput("u_adjobrno")+"/"+getInputNumeric("u_adjcount"));
								setInput("u_expclass",result.childNodes.item(0).getAttribute("u_expclass"));
								setInput("u_refno",getInput("u_adjobrno"));
								disableInput("docseries");
								disableInput("docno");
								disableInput("u_expclass");
								disableInput("u_refno");
								clearTable("T1",true);
								for (iii = 0; iii < result.childNodes.length; iii++) {
									data["u_profitcenter"] = result.childNodes.item(iii).getAttribute("u_profitcenter");
									data["u_profitcentername"] = result.childNodes.item(iii).getAttribute("u_profitcentername");
									data["u_glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctno");
									data["u_glacctname"] = result.childNodes.item(iii).getAttribute("u_glacctname");
									data["u_slcode"] = result.childNodes.item(iii).getAttribute("u_slcode");
									data["u_sldesc"] = result.childNodes.item(iii).getAttribute("u_sldesc");
									data["u_remarks"] = result.childNodes.item(iii).getAttribute("u_remarks");
									//data["u_debit"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_debit"));
									//data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_debit"));
									insertTableRowFromArray("T1",data);
								}
							} else {
								setInput("u_bpcode","");
								setInput("u_bpname","");
								page.statusbar.showError("Invalid OBR No.");	
								return false;
							}
						} else {
							setInput("u_bpcode","");
							setInput("u_bpname","");
							page.statusbar.showError("Error retrieving obr order record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpcode","");
						setInput("u_bpname","");
					}
					break;
					
					//select docno, u_date, u_remarks, u_dueamount from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O' and u_mainosno=''
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
				case "u_checkamount":
					computeTotalGPSLGUAcctg();
					break;
				case "u_date":
					setDocNo(null,null,null,"u_date");
					//setInput("u_jevno","");
					//if (formatDateToDB(getInput("u_date"))<getPrivate("migratedate")) {
					//	setInput("u_ob",1);	
					//} else {
					//	setInput("u_ob",0);
					//}
					break;
				case "u_lvatperc":
				case "u_levatperc":
				case "u_levat2perc":
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
				case "u_checkbank":
					ajaxloadhousebankaccounts("df_u_checkbankacctno",getGlobal("branch"),"PH",getInput("u_checkbank"),'',":");
					break;
				/*case"u_bankacctno":
					result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getInput("u_tfbank"),getInput("u_tfbankacctno"));
					setInput("u_tfbankbranch",result.getAttribute("u_tfbankbranch"));
					break;*/
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
		case "df_u_profitcenter":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter, profitcentername from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_pono":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_profitcenter, u_profitcentername, u_bpname, u_totalamount from u_lgupurchaseorder where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='D'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("P.O. No.`Date`Profit Center`Profit Center Name`Supplier Name`Total Amount")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`10`25`25`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`````amount")); 			
			break;
		case "df_u_billno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_profitcenter, u_profitcentername, u_bpname, u_totalamount from u_lgusplitpo where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='D'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Bill No.`Date`Profit Center`Profit Center Name`Supplier Name`Total Amount")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`10`25`25`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`````amount")); 			
			break;
		case "df_u_adjobrno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_remarks, u_dueamount from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O' and u_mainosno=''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("OBR No.`Date`Remarks`Due Amount")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``amount")); 			
			break;
		case "df_u_refundglacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_profitcenterT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter, profitcentername from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
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
		case "df_u_remarksT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_acct from u_lgusubsidiaryaccts where code='"+getTableInput("T1","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Subsidiary")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_requestedbyname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lgusignatories")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name`Position")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`50")); 			
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
	}
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1":
			enableTableInput("T1","u_profitcenter");
			enableTableInput("T1","u_profitcentername");
			enableTableInput("T1","u_glacctno");
			enableTableInput("T1","u_glacctname");
			enableTableInput("T1","u_slcode");
			enableTableInput("T1","u_sldesc");
			break;

	}
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1": 
			if (getInput("u_adjobrno")!="") {
				page.statusbar.showError("You are not allowed to add new item for adjustment.");
				return false;	
			} else {
				if (isTableInputEmpty(table,"u_profitcenter")) return false; 
				if (isTableInputEmpty(table,"u_glacctno")) return false; 
				if (isTableInputEmpty(table,"u_glacctname")) return false; 
				if (isTableInputZero(table,"u_amount")) {
					page.statusbar.showError("Debit or Credit must be entered.");	
					focusTableInput(table,"u_debit");
					return false;
				}
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

function onTableAfterEditRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": 
			if (getInput("u_adjobrno")!="") {
				disableTableInput("T1","u_profitcenter");
				disableTableInput("T1","u_profitcentername");
				disableTableInput("T1","u_glacctno");
				disableTableInput("T1","u_glacctname");
				disableTableInput("T1","u_slcode");
				disableTableInput("T1","u_sldesc");
			}
			break;
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_profitcenter")) return false; 
			if (isTableInputEmpty(table,"u_glacctno")) return false; 
			if (isTableInputEmpty(table,"u_glacctname")) return false; 
			if (getInput("u_adjobrno")!="") {
				if (getInputNumeric(table,"u_credit")!=0) {
					page.statusbar.showError("Credit is not allowed for adjustment. Negate the amount of Debit instead.");	
					return false;
				}
			}
			if (isTableInputZero(table,"u_amount")) {
				page.statusbar.showError("Debit or Credit must be entered.");	
				focusTableInput(table,"u_debit");
				return false;
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
	switch (table) {
		case "T2":
		case "T3":
			params["focus"] = false;
			if (elementFocused.substring(0,13)=="df_u_amountT2") {
				focusTableInput(table,"u_amount",row);
			}
			break;
	}
	return params;
}

function computeTotalGPSLGUAcctg(adjusted) {
	if (adjusted==null) adjusted=false;
	var rc = 0, rc2 = 0, dueamount=0, advanceamount=0, vatableamount=0, evat1=0,evat2=0;
	
	advanceamount = getInputNumeric("u_checkamount");
	
	if (isInputChecked("u_tf")) dueamount = getInputNumeric("u_checkamount");

	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			//if (getTableInput("T1","u_glacctno",i)=='1-03-05-020' || getTableInput("T1","u_glacctno",i)=='1-03-05-010') {
			//	advanceamount += getTableInputNumeric("T1","u_amount",i)*-1;
			//} else {
				dueamount += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
				if (getTableInputNumeric("T1","u_amount",i)>0) {
					vatableamount += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
					if (getTableInputNumeric("T1","u_evat",i)==1) {
						evat1 += getTableInputNumeric("T1","u_amount",i) - getTableInputNumeric("T1","u_wtax",i);
					} else {
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
				evat2 += getTableInputNumeric("T3","u_refevat2",i)*(getTableInputNumeric("T3","u_amount",i)/getTableInputNumeric("T3","u_refamount",i));
			}
		}
	}

	setInputAmount("u_lbaseamount",vatableamount);
	setInputAmount("u_levatbaseamount",evat1);
	setInputAmount("u_levat2baseamount",evat2);

	if (!adjusted) {
		if (getInputNumeric("u_lvatperc")>0 || getInputNumeric("u_levatperc")>0) {
			if (rc+rc2>0) {
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
	
	rc =  getTableRowCount("T2");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			dueamount += getTableInputNumeric("T2","u_amount",i);
		}
	}
	setInputAmount("u_dueamount",dueamount);
	setInputAmount("u_advanceamount",advanceamount - dueamount);
}

function getAdvGPSLGUAcctg() {
	return true;
	
	var data = new Array();
	clearTable("T2",true);
	clearTable("T3",true);
	
	if (getInput("u_bpcode")!="") {
		var result = page.executeFormattedQuery("select a.docdate, b.docno, a.reference1, a.reference2, b.credit-b.debit as amount, c.u_evat1, c.u_evat2, b.balanceamount*-1 as balanceamount  from journalvouchers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.balanceamount<>0 left join u_lguaps c on c.company=a.company and c.branch=a.branch and c.u_jevno=a.docno where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and b.itemno='"+getInput("u_bpcode")+"' and a.docstatus not in ('D')");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					data["u_selected"] = 0;
					data["u_refdate"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("docdate"));
					data["u_refno"] = result.childNodes.item(iii).getAttribute("docno");
					data["u_refno2"] = result.childNodes.item(iii).getAttribute("reference1");
					data["u_refamount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("amount"));
					data["u_refbalance"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("balanceamount"));
					data["u_amount"] = formatNumericAmount(0);
					if (result.childNodes.item(iii).getAttribute("reference2")=="Check Disbursement" || result.childNodes.item(iii).getAttribute("reference2")=="") {
						insertTableRowFromArray("T2",data);
						disableTableInput("T2","u_amount",iii+1);
					} else {
						data["u_refevat1"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_evat1"));
						data["u_refevat2"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_evat2"));
						insertTableRowFromArray("T3",data);
						disableTableInput("T3","u_amount",iii+1);
					}
				}
			}
		} else {
			page.statusbar.showError("Error retrieving journals. Try Again, if problem persists, check the connection.");	
			return false;
		}
	}
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

function u_getBudgetBalanceLGUAcctg() {
	if (getInput("u_date")!="" && getTableInput("T1","u_profitcenter")!="" && getTableInput("T1","u_glacctno")!="") {
		var result = page.executeFormattedQuery("select sum(u_budgetamount) as u_budgetamount, sum(u_obramount) as u_obramount, ifnull(sum(u_budgetamount),0)-ifnull(sum(u_obramount),0) as u_balance from (select xd.u_rlamt as u_budgetamount, 0 as u_obramount from u_lgubudget xc inner join u_lgubudgetgls xd on xd.company=xc.company and xd.branch=xc.branch and xd.code=xc.code and xd.u_glacctno ='"+getTableInput("T1","u_glacctno")+"' and xd.u_slcode='"+getTableInput("T1","u_slcode")+"' where xc.company='"+getGlobal("company")+"' and xc.branch='"+getGlobal("branch")+"' and xc.u_yr='"+formatDateToDB(getInput("u_date")).substr(0,4)+"' and xc.u_profitcenter='"+getTableInput("T1","u_profitcenter")+"' union all select 0 as u_budgetamount, b.u_debit-b.u_credit-b.u_billedamount as u_obramount from u_lguobligationslips a inner join u_lguobligationslipaccts b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_profitcenter='"+getTableInput("T1","u_profitcenter")+"' and b.u_glacctno='"+getTableInput("T1","u_glacctno")+"' and b.u_slcode='"+getTableInput("T1","u_slcode")+"' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and year(a.u_date)='"+formatDateToDB(getInput("u_date")).substr(0,4)+"') as xxx");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				setTableInputAmount("T1","u_rlamt",result.childNodes.item(0).getAttribute("u_balance"));
			}
		} else {
			page.statusbar.showError("Error retrieving budget balance. Try Again, if problem persists, check the connection.");	
			return false;
		}		
	} else {
		setTableInputAmount("T1","u_rlamt",0);
	}
}