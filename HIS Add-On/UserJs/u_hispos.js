// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');
page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');


var tabpage_payment=-1;
var tab_payment="tab1";

var tabpage_check=-1;
var tab_check="tab1";

page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a" || getInput("docstatus")=="C") {	
		if (getInput("u_reftype")=="WI") enableInput("u_patientname");
	}
	if (getInput("u_terminalid")!="") {
		showPopupFrame("popupFrameQueue",true,null,false);
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="sc") {
		page.statusbar.showWarning("Document is not allowed to be updated.");
		return false;
	} else if (action=="a") {
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_doctime")) return false;
		if (isInputEmpty("u_payreftype")) return false;
		if (getInput("u_payreftype")=="DP") {
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else if (getInput("u_payreftype")=="SI") {
			if (isInputEmpty("u_reftype")) return false;
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else if (getInput("u_payreftype")=="PN") {
			if (isInputEmpty("u_reftype")) return false;
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (getInputType("u_department")!="hidden")	{
				if (isInputEmpty("u_department")) return false;
			}
			//if (isInputEmpty("u_payrefno")) return false;
			if (isInputEmpty("u_reftype")) return false;
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_refno")) return false;
			}
			if (isInput("u_firstname")) {
				if (isInputEmpty("u_lastname")) return false;	
				if (isInputEmpty("u_firstname")) return false;	
				if (getInput("u_middlename")=="") {
					if (window.confirm("Middle Name was not entered. Are you sure patient has no middle name?")==false) {
						focusInput("u_middlename");
						return false;
					}
				}			
			}
			if (isInputEmpty("u_patientname")) return false;
		}
		if (getInputType("u_colltype")!="hidden")	{
			if (isInputEmpty("u_colltype")) return false;
		}
		if (getInputNumeric("u_discamount")!=0) {
			if (isInputEmpty("u_disccode")) return false;
		}
		
		if (getInput("u_trxtype")!="CM") {
			if (getInput("u_payreftype")!="SI") {
				if (isInputNegative("u_totalamount")) return false;
				if (isInputNegative("u_dueamount")) return false;
			}
			if (getInputNumeric("u_checkamount")==0 && getInputNumeric("u_creditcardamount")==0 && getInputNumeric("u_otheramount")==0 && getInputNumeric("u_creditamount")==0) {
				if (isInputNegative("u_recvamount")) {
					if (tabpage_payment!=-1) selectTab(tab_payment,tabpage_payment);
					return false;
				}
			}
		}
		
		
		if (getInputNumeric("u_checkamount")>0) {
			if (isInputEmpty("u_bank")) {
				if (tabpage_check!=-1) selectTab(tab_check,tabpage_check);
				return false;
			}
			if (isInputEmpty("u_checkno")) {
				if (tabpage_check!=-1) selectTab(tab_check,tabpage_check);
				return false;
			}
		}
		if (isTable("T1")) {
			if (getTableInput("T1","u_creditcard")!="") {
				page.statusbar.showError("A credit card payment is being added/edited.");
				return false;
			}
		}
		if (isTable("T3")) {
			if (getTableInput("T3","u_inscode")!="") {
				page.statusbar.showError("A health benefit is being added/edited.");
				return false;
			}
		}
		
		if (getInputNumeric("u_balanceamount")>0) {
			if (isInputPositive("u_balanceamount")) return false;
		}
		
		if (getInputNumeric("u_chngamount")>getInputNumeric("u_recvamount")) {
			page.statusbar.showError("Change cannot be more than received.");
			return false;
		}
		
		if (getInputNumeric("u_balanceamount")<0 && getInputNumeric("u_chngamount")==0) {
			if (isInputNegative("u_balanceamount")) return false;
		}

		if (getInput("u_prepaid")=="3" && getInputNumeric("u_otheramount")>0 && getGlobal("roleid")!="FIN-ACCT") {
			page.statusbar.showError("You cannot have health benefits on final bill payment. Use Billing CM instead for health benefits.");
			return false;
		}

	} else if (action=="cnd") {
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
				return false;
			}
			if (getTableInput("T51","cancelreason")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","cancelreason");
				return false;
			}
			if (getTableInput("T51","remarks")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","remarks");
				return false;
			}
		}
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		//if (action=="a") OpenReportSelect('printer');
	}
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
	}
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
	switch (table) {
		case "T50":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T50","password");
			} else if (sc_press == "ENTER" && column=="password") {
				formSubmit();
			}
			break;
		case "T51":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T51","password");
			} else if (sc_press == "ENTER" && column=="password") {
				focusTableInput("T51","cancelreason");
			} else if (sc_press == "ENTER" && column=="cancelreason") {
				focusTableInput("T51","remarks");
			} else if (sc_press == "ENTER" && column=="remarks") {
				formSubmit('cnd');
			}
			break;
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T2":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					switch (getInput("u_payreftype")) {
						case "CHRG":
							break;
					}
					break;
				case "u_unitprice":
					setTableInputPrice(table,"u_price",getTableInputNumeric(table,"u_unitprice"));
					computePatientLineTotalGPSHIS(table);
					break;
				case "u_quantity":
				case "u_price":
					computePatientLineTotalGPSHIS(table);
					break;
			}
			break;
		case "T3":
			switch(column) {
				case "u_memberid":
					if (getTableInput(table,"u_hmo")!=6) return true;
					
					if (getTableInput(table,"u_memberid")!="") {
						result = page.executeFormattedQuery("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText(table,"u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and a.custno='"+getTableInput(table,"u_memberid")+"' and isvalid=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_memberid",result.childNodes.item(0).getAttribute("custno"));
								setTableInput(table,"u_membername",result.childNodes.item(0).getAttribute("custname"));
							} else {
								setTableInput(table,"u_memberid","");
								setTableInput(table,"u_membername","");
								page.statusbar.showError("Invalid Customer No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_memberid","");
							setTableInput(table,"u_membername","");
							page.statusbar.showError("Error retrieving Customer No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_memberid","");
						setTableInput(table,"u_membername","");
					}
					break;
			}
			break;
		case "T4":
			switch (column) {
				case "u_credited":
					computeCreditTotalGPSHIS();
					break;
			}
			break;
		default:
			switch (column) {
				case "u_patientid":
					if (isInput("u_firstname")) enableInput("u_firstname");
					if (isInput("u_middlename")) enableInput("u_middlename");
					if (isInput("u_lastname")) enableInput("u_lastname");
					if (isInput("u_extname")) enableInput("u_extname");
				
					if (getInput("u_patientid")!="") {
						result = page.executeFormattedQuery("select code, name, u_lastname, u_firstname, u_middlename, u_extname, u_address from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'and code='"+getInput("u_patientid")+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
								if (isInput("u_address")) setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
								if (isInput("u_firstname")) setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								if (isInput("u_middlename")) setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								if (isInput("u_lastname")) setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								if (isInput("u_extname")) setInput("u_extname",result.childNodes.item(0).getAttribute("u_extname"));
		
								if (isInput("u_firstname")) disableInput("u_firstname");
								if (isInput("u_middlename")) disableInput("u_middlename");
								if (isInput("u_lastname")) disableInput("u_lastname");
								if (isInput("u_extname")) disableInput("u_extname");
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								if (isInput("u_firstname")) setInput("u_firstname","");
								if (isInput("u_middlename")) setInput("u_middlename","");
								if (isInput("u_lastname")) setInput("u_lastname","");
								if (isInput("u_extname")) setInput("u_extname","");
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							if (isInput("u_firstname")) setInput("u_firstname","");
							if (isInput("u_middlename")) setInput("u_middlename","");
							if (isInput("u_lastname")) setInput("u_lastname","");
							if (isInput("u_extname")) setInput("u_extname","");
							page.statusbar.showError("Error retrieving Patient. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						if (isInput("u_firstname")) setInput("u_firstname","");
						if (isInput("u_middlename")) setInput("u_middlename","");
						if (isInput("u_lastname")) setInput("u_lastname","");
						if (isInput("u_extname")) setInput("u_extname","");
										
					}
					break;
				case "u_lastname":
				case "u_firstname":
				case "u_extname":
				case "u_middlename":
					setInput(column,utils.trim(getInput(column)));
					var name = getInput("u_lastname");
					if (getInput("u_firstname")!="") name += ", " + getInput("u_firstname");
					if (getInput("u_middlename")!="") name += " " + getInput("u_middlename");
					if (getInput("u_extname")!="") name += " " + getInput("u_extname");
					setInput("u_patientname",name);
					break;
				case "u_payrefno":
					enableInput("u_department");
					enableInput("u_disccode");
					enableInput("u_pricelist");
					enableInput("u_department");
					enableInput("u_reftype");
					enableInput("u_refno");
					clearTable("T4",true);	
					if (getInput("u_payrefno")!="") {
						var series = getInput("u_payrefno").split('`');
						if (series.length==2) {
							setInput("u_payreftype",series[0]);
							setInput("u_payrefno",series[1]);
							if (getInput("u_payreftype")=="PF") {
								setInput("u_colltype","PROFFEE");
							} else {
								setInput("u_colltype","HOSPBILL");
							}							
						}
						switch (getInput("u_payreftype")) {
							case "RS":
								result = page.executeFormattedQuery("select a.u_department, a.u_scdisc, a.u_pricelist, 'IP' as u_reftype, a.docno as u_refno, a.u_patientid, a.u_patientname, a.u_address, a.u_disccode, 0 as u_prediscamount, if(a.u_dpno='',a.u_dpamount,0) as u_amount, 2 as u_prepaid, 1 as u_ishb, 0 as u_ispf, 0 as u_ispm from u_hisips a where a.docno='"+getInput("u_payrefno")+"' and a.docstatus not in ('Cancelled','CN','Discharged')");	 
								break;
							case "SI":
								if (getInput("u_trxtype")=="CM") {
									result = page.executeFormattedQuery("select a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_dueamount-a.u_dpbal-a.u_paidamount as dueamount from u_hisbills a where a.docno='"+getInput("u_payrefno")+"' and a.docstatus not in ('D')");	 
								} else {
									result = page.executeFormattedQuery("select a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_dueamount-a.u_dpbal-a.u_paidamount as dueamount from u_hisbills a where a.docno='"+getInput("u_payrefno")+"' and a.docstatus not in ('D','C') and a.u_dueamount!=0");	 
								}
								break;
							case "PN":
								result = page.executeFormattedQuery("select a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, sum(a.u_pnamount-a.u_paidamount-a.u_btamount) as dueamount from u_hispronotes a where a.u_hmo=5 and a.docstatus not in ('CN','D','C') and a.u_billno='"+getInput("u_payrefno")+"' group by a.u_billno");	 
							
								break;
							case "CHRG":
								result = page.executeFormattedQuery("select a.u_department, a.u_disconbill, a.u_scdisc, a.u_pricelist, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, '' as u_address, a.u_disccode, if(a.u_scdisc=1,a.u_amountbefdisc-a.u_vatamount,a.u_amountbefdisc) as u_amountbefdisc, a.u_discamount, u_amount, a.u_prepaid, a.u_ishb, a.u_ispf, a.u_ispm from u_hisrequests a where a.docno='"+getInput("u_payrefno")+"' and a.u_prepaid in (1,2) and (a.u_payrefno='' and a.u_amount>0) and a.docstatus='O'");	 
								break;
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								if (getInput("u_payreftype")=="SI")	{
									setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
									setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
									setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
									setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
									//setInputAmount("u_totalamount",result.childNodes.item(0).getAttribute("u_dueamount"));
									//setInputAmount("u_dueamount",result.childNodes.item(0).getAttribute("u_dueamount"));
									disableInput("u_department");
									disableInput("u_disccode");
									disableInput("u_pricelist");
									disableInput("u_department");
									disableInput("u_reftype");
									disableInput("u_refno");
									disableInput("u_patientname");
									setInput("u_colltype","Final Bill");
									getCreditsGPSHIS();
									computeCreditTotalGPSHIS();
								} else if (getInput("u_payreftype")=="PN")	{
									setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
									setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
									setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
									setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
									//setInputAmount("u_totalamount",result.childNodes.item(0).getAttribute("u_dueamount"));
									//setInputAmount("u_dueamount",result.childNodes.item(0).getAttribute("u_dueamount"));
									disableInput("u_department");
									disableInput("u_disccode");
									disableInput("u_pricelist");
									disableInput("u_department");
									disableInput("u_reftype");
									disableInput("u_refno");
									disableInput("u_patientname");
									setInput("u_colltype","Promissory Note");
									getCreditsGPSHIS();
									computeCreditTotalGPSHIS();
								} else {
									setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
									setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
									setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
									setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
									setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
									setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
									setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
									setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
									setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
									if (isInput("u_address")) setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
									if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
									else uncheckedInput("u_scdisc");
	
									setInputAmount("u_totalamount",result.childNodes.item(0).getAttribute("u_amountbefdisc"));
									if (getInput("u_prepaid")=="1" && getInput("u_disccode")!="") {
										setInputAmount("u_discamount",result.childNodes.item(0).getAttribute("u_discamount"));
										setInputAmount("u_dueamount",result.childNodes.item(0).getAttribute("u_amount"));
									} else {
										setInputAmount("u_discamount",0);
										setInputAmount("u_dueamount",result.childNodes.item(0).getAttribute("u_amountbefdisc"));
									}
									
									
									if (getInput("u_reftype")=="WI") {
										disableInput("u_refno");	
									} else {
										enableInput("u_refno");	
									}
									
									if (getInput("u_prepaid")=="2") {
										setInput("u_colltype","Partial Payments");
									} else {
										if (result.childNodes.item(0).getAttribute("u_ishb")=="1") setInput("u_colltype","Hospital Fees");
										else if (result.childNodes.item(0).getAttribute("u_ispf")=="1") setInput("u_colltype","Professional Fees");
										else if (result.childNodes.item(0).getAttribute("u_ispm")=="1") setInput("u_colltype","Professional Materials");
										else setInput("u_colltype","");
									}
									disableInput("u_department");
									disableInput("u_disccode");
									disableInput("u_pricelist");
									disableInput("u_department");
									disableInput("u_reftype");
									disableInput("u_refno");
									disableInput("u_patientname");
									getCreditsGPSHIS();
								}
							} else {
								setInputAmount("u_totalamount",0);
								setInputAmount("u_discamount",0);
								setInputAmount("u_dueamount",0);
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInputAmount("u_totalamount",0);
							setInputAmount("u_discamount",0);
							setInputAmount("u_dueamount",0);
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_totalamount",0);
						setInputAmount("u_discamount",0);
						setInputAmount("u_dueamount",0);
						setInput("u_refno","",true);
						if (getInput("u_payreftype")=="CHRG") setInput("u_colltype","");
					}
					try {
						computeAmountRowGPSHIS();
					} catch (theError) {
					}
					break;
				case "u_refno":
					if (getInput("u_refno")!="") {
						if (getInput("u_reftype")=="IP") {
							result = page.executeFormattedQuery("select a.u_patientid, a.u_patientname, b.u_lastname, b.u_firstname, b.u_middlename, b.u_extname, b.u_address from u_hisips a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' and docstatus not in ('Discharged')");	 
						} else {
							result = page.executeFormattedQuery("select a.u_patientid, a.u_patientname, b.u_lastname, b.u_firstname, b.u_middlename, b.u_extname, b.u_address from u_hisops a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' and docstatus not in ('Discharged')");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_payrefno","");
								
								if (isInput("u_address")) setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
								if (isInput("u_firstname")) setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								if (isInput("u_middlename")) setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								if (isInput("u_lastname")) setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								if (isInput("u_extname")) setInput("u_extname",result.childNodes.item(0).getAttribute("u_extname"));
								getCreditsGPSHIS();
								
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInput("u_payrefno","");
								if (isInput("u_address")) setInput("u_address","");
								if (isInput("u_firstname")) setInput("u_firstname","");
								if (isInput("u_middlename")) setInput("u_middlename","");
								if (isInput("u_lastname")) setInput("u_lastname","");
								if (isInput("u_extname")) setInput("u_extname","");
								
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInput("u_payrefno","");
							if (isInput("u_address")) setInput("u_address","");
							if (isInput("u_firstname")) setInput("u_firstname","");
							if (isInput("u_middlename")) setInput("u_middlename","");
							if (isInput("u_lastname")) setInput("u_lastname","");
							if (isInput("u_extname")) setInput("u_extname","");
							
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_payrefno","");
						if (isInput("u_address")) setInput("u_address","");
						if (isInput("u_firstname")) setInput("u_firstname","");
						if (isInput("u_middlename")) setInput("u_middlename","");
						if (isInput("u_lastname")) setInput("u_lastname","");
						if (isInput("u_extname")) setInput("u_extname","");
					}
					try {
						computeAmountRowGPSHIS();
					} catch (theError) {
					}
					break;			
				case "u_recvamount":	
				case "u_checkamount":	
					try {
						computeAmountRowGPSHIS();
					} catch (theError) {
					}
					break;
				case "u_totalamount":
					setInputAmount("u_dueamount",getInputNumeric("u_totalamount") - getInputNumeric("u_discamount"));
					try {
						computeAmountRowGPSHIS();
					} catch (theError) {
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	switch (table) {
		case "T3":
			switch (column) {
				case "u_inscode":
					disableTableInput(table,"u_memberid");
					disableTableInput(table,"u_membername");
					disableTableInput(table,"u_membertype");
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select u_hmo from u_hishealthins where code='"+getTableInput(table,"u_inscode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								var u_hmo = result.childNodes.item(0).getAttribute("u_hmo");	
								if (u_hmo!=2) {
									setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
									result2 = page.executeFormattedQuery("select u_memberid, u_membername, u_membertype from u_hispatienthealthins where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getInput("u_patientid")+"' and u_inscode='"+getTableInput(table,"u_inscode")+"'");	 
									if (result2.getAttribute("result")!= "-1") {
										if (parseInt(result2.getAttribute("result"))>0) {
											setTableInput(table,"u_memberid",result2.childNodes.item(0).getAttribute("u_memberid"));
											setTableInput(table,"u_membername",result2.childNodes.item(0).getAttribute("u_membername"));
											setTableInput(table,"u_membertype",result2.childNodes.item(0).getAttribute("u_membertype"));
											
										} else {
											setTableInput(table,"u_memberid","");
											setTableInput(table,"u_membername","");
											setTableInput(table,"u_membertype","");
										}
									} else {
										setTableInput(table,"u_memberid","");
										setTableInput(table,"u_membername","");
										setTableInput(table,"u_membertype","");
										page.statusbar.showError("Error retrieving Health Benefits for this patient. Try Again, if problem persists, check the connection.");	
									}								
								} else {
									setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
								}
								switch (getTableInput(table,"u_hmo")) {
									case "0":
										enableTableInput(table,"u_memberid");
										enableTableInput(table,"u_membername");
										enableTableInput(table,"u_membertype");
										//focusTableInput(table,"u_memberid");
										//enableInput("u_caserate");
										break;
									case "1":
									case "4":
										enableTableInput(table,"u_memberid");
										enableTableInput(table,"u_membername");
										//focusTableInput(table,"u_memberid");
										break;
									case "6":
										enableTableInput(table,"u_memberid");
										//focusTableInput(table,"u_memberid");
										break;
								}					
								focusTableInput(table,"u_amount");
							} else {
								setTableInput(table,"u_hmo","-1");
								page.statusbar.showError("Invalid Health Benefits Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_hmo","-1");
							page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_hmo","-1");
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_payreftype":
					disableInput("u_payrefno");
					disableInput("u_patientid");
					disableInput("u_reftype");
					disableInput("u_refno");
					disableInput("u_totalamount");
					if (getInput("u_payreftype")=="DP") {
						setInput("u_prepaid",2);
						enableInput("u_reftype");
						enableInput("u_patientid");
						enableInput("u_totalamount");
						setInput("u_reftype","",true);
						setInput("u_colltype","Partial Payments");
						focusInput("u_patientid");
					} else if (getInput("u_payreftype")=="RS") {
						setInput("u_prepaid",2);
						enableInput("u_payrefno");
						enableInput("u_reftype");
						enableInput("u_refno");
						enableInput("u_totalamount");
						setInput("u_colltype","Partial Payments");
						focusInput("u_payrefno");
					} else if (getInput("u_payreftype")=="SI") {
						setInput("u_prepaid",3);
						enableInput("u_payrefno");
						enableInput("u_reftype");
						enableInput("u_refno");
						setInput("u_colltype","Final Bill");
						if (getInput("u_trxtype")=="CM") selectTab("tab1",1);
						else selectTab("tab1",2);
						focusInput("u_payrefno");
					} else if (getInput("u_payreftype")=="PN") {
						setInput("u_prepaid",3);
						enableInput("u_payrefno");
						enableInput("u_reftype");
						enableInput("u_refno");
						setInput("u_colltype","Promisorry Note Payment");
						if (getInput("u_trxtype")=="CM") selectTab("tab1",1);
						else selectTab("tab1",2);
						focusInput("u_payrefno");
					} else {
						enableInput("u_payrefno");
						enableInput("u_reftype");
						enableInput("u_refno");
						setInput("u_reftype","WI",true);
						if (getInput("u_payreftype")=="PF") {
							setInput("u_colltype","Professional Fees");
						} else {
							setInput("u_colltype","Partial Payments");
						}
						focusInput("u_payrefno");
					}
					setInput("u_payrefno","",true);
					break;
				case "u_reftype":
					setInput("u_payrefno","",true);
					setInput("u_refno","",true);
					disableInput("u_refno");
					disableInput("u_patientid");
					disableInput("u_patientname");
					if (isInput("u_firstname")) disableInput("u_firstname");
					if (isInput("u_middlename")) disableInput("u_middlename");
					if (isInput("u_lastname")) disableInput("u_lastname");
					if (isInput("u_extname")) disableInput("u_extname");
					if (getInput("u_reftype")=="WI") {
						enableInput("u_patientid");
						enableInput("u_patientname");
						if (isInput("u_firstname")) enableInput("u_firstname");
						if (isInput("u_middlename")) enableInput("u_middlename");
						if (isInput("u_lastname")) enableInput("u_lastname");
						if (isInput("u_extname")) enableInput("u_extname");
					} else enableInput("u_refno");
					break;
				case "u_pricelist":
					switch (getInput("u_payreftype")) {
						case "MEDSUP":	
							resetPatientPricesGPSHIS("T2","u_totalamount","u_vatamount","u_discamount");
							break;
					}
					break;
			}
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T4":
			switch (column) {
				case "u_selected":
					if (isTableInputChecked(table,column,row)) {
						setTableInputAmount(table,"u_credited",getTableInputNumeric(table,"u_balance",row),row);
						if (getTableInput(table,"u_reftype",row)!="CHRG") {
							enableTableInput(table,"u_credited",row);
						}
						focusTableInput(table,"u_credited",row);
					} else {
						setTableInputAmount(table,"u_credited",0,row);
						disableTableInput(table,"u_credited",row);
					}
					computeCreditTotalGPSHIS();
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_itemcodeT2":
		case "df_u_itemdescT2":
			if (isInputEmpty("u_payreftype")) return false;
			if (getInputType("u_department")!="hidden") {
				if (isInputEmpty("u_department")) return false;
			}
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_refno")) return false;
			}			
			if (isInputEmpty("u_pricelist")) return false;
			break;
		case "df_u_payrefno":
			break;
		case "df_u_refno":
			if (getInput("u_reftype")=="") {
				page.statusbar.showError("Reference Type must be selected.");
				focusInput("u_reftype");
				return false;
			}
			break;
		case "df_u_memberidT3":
			if (getTableInput("T3","u_hmo")!=6) {
				page.statusbar.showWarning("Please enter the member id manually.");
				return false;
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_itemcodeT2":
			switch (getInput("u_payreftype")) {
				case "LAB":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hislabtesttypes where u_active=1")); 
					break;
				case "SPLROOM":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisservices where u_group='PRC' and u_active=1")); 
					break;
				case "MISC":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisservices where u_group='MSC' and u_active=1")); 
					break;
				case "MEDSUP":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1")); 
					break;
				case "PF":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisservices where u_group='PRF' and u_active=1")); 
					break;
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			switch (getInput("u_payreftype")) {
				case "LAB":
					break;
				case "SPLROOM":
					break;
				case "MISC":
					break;
				case "MEDSUP":
					params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisitems"; 			
					params["params"] += "&cfladdwidth=1024"; 			
					params["params"] += "&cfladdheight=600"; 	
					break;
				case "PF":
					break;
			}
			break;
		case "df_u_itemdescT2":
			switch (getInput("u_payreftype")) {
				case "LAB":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hislabtesttypes where u_active=1")); 
					break;
				case "SPLROOM":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisservices where u_group='PRC' and u_active=1")); 
					break;
				case "MISC":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisservices where u_group='MSC' and u_active=1")); 
					break;
				case "MEDSUP":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1")); 
					break;
				case "PF":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisservices where u_group='PRF' and u_active=1")); 
					break;
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			switch (getInput("u_payreftype")) {
				case "LAB":
					break;
				case "SPLROOM":
					break;
				case "MISC":
					break;
				case "MEDSUP":
					params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisitems"; 			
					params["params"] += "&cfladdwidth=1024"; 			
					params["params"] += "&cfladdheight=600"; 	
					break;
				case "PF":
					break;
			}
			break;
		case "df_u_memberidT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText("T3","u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_startdate, u_patientname from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_startdate, u_patientname from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Registration No.`Date`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``"));
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_payrefno":
			switch (getInput("u_payreftype")) {
				case "RS":
					if (getInput("u_refno")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hisips a where a.docstatus not in ('Cancelled','CN','Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_patientname from u_hisips a where a.docstatus not in ('Cancelled','CN') and a.docno='"+getInput("u_refno")+"'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "PN":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_billno,a.u_docdate,a.u_patientname, sum(a.u_pnamount-a.u_btamount), sum(a.u_paidamount), sum(a.u_pnamount-a.u_paidamount-a.u_btamount) from u_hispronotes a where a.u_hmo=5 and a.docstatus not in ('CN','D','C') group by a.u_billno")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Bill Date`Patient Name`Due Amount`Paid`Balance")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("16`11`35`10`9`9")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``amount`amount`amount")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "SI":
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_docdate,a.u_patientname,a.u_dueamount, a.u_paidamount, a.u_dueamount-a.u_dpbal-a.u_paidamount from u_hisbills a where a.docstatus not in ('Cancelled','CN','D','C')")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Bill Date`Patient Name`Due Amount`Paid`Balance")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("16`11`35`10`9`9")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``amount`amount`amount")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "CHRG":
					if (getInput("u_refno")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_requestdate, a.u_patientname from u_hisrequests a where a.u_prepaid in (1,2) and (a.u_payrefno='' and a.u_amount>0) and a.docstatus='O'")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_requestdate,a.u_patientname from u_hisrequests a where a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_prepaid in (1,2) and (a.u_payrefno='' and a.u_amount>0) and a.docstatus='O'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "LAB":
					if (getInput("u_refno")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hislabtestrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_patientname from u_hislabtestrequests a where a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "SPLROOM":
					if (getInput("u_refno")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hissplroomrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_patientname from u_hissplroomrequests a where and a.u_reftype='"+getInput("u_reftype")+"' a.u_refno='"+getInput("u_refno")+"' and a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "MISC":
					if (getInput("u_refno")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hismiscrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hismiscrequests a where and a.u_reftype='"+getInput("u_reftype")+"' a.u_refno='"+getInput("u_refno")+"' and a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "MEDSUP":
					if (getInput("u_refno")=="" && getInput("u_reftype")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hismedsuprequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					} else if (getInput("u_reftype")!="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hismedsuprequests a where a.u_reftype='"+getInput("u_reftype")+"' and a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hismedsuprequests a where a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				case "PF":
					if (getInput("u_refno")=="") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hisconsultancyrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno, a.u_patientname from u_hisconsultancyrequests a where and a.u_reftype='"+getInput("u_reftype")+"' a.u_refno='"+getInput("u_refno")+"' and a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					}
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
				default:
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select concat('LAB`',docno) as docno, 'Examinations' as u_payreftype, a.docno as u_payrefno, a.u_patientname from u_hislabtestrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O' union all select concat('SPLROOM`',docno) as docno, 'Special Rooms' as u_payreftype, a.docno as u_payrefno, a.u_patientname from u_hissplroomrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O' union all select concat('MISC`',docno) as docno, 'Miscellaneous' as u_payreftype, a.docno as u_payrefno, a.u_patientname from u_hismiscrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O' union all select concat('MEDSUP`',docno) as docno, 'Medicines & Supplies' as u_payreftype, a.docno as u_payrefno, a.u_patientname from u_hismedsuprequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O' union all select concat('PF`',docno) as docno, 'Professional Fees' as u_payreftype, a.docno as u_payrefno, a.u_patientname from u_hisconsultancyrequests a where a.u_prepaid in (1,2) and a.u_payrefno='' and a.docstatus='O'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Series`Payment For`No.`Patient Name")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`20`15`50")); 			
					params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
					params["params"] += "&cflvisibilities="+utils.replaceSpecialChar(Base64.encode("false```")); 			
					params["params"] += "&cflsortby=u_patientname";
					break;
			}
			break;
		case "df_u_patientid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=name";
			break;
	}
	return params;
}

function onElementLnkBtnGetParamsGPSHIS(Id,params) {
	var idx="";
	if (Id.length>10) {
		if (Id.substr(0,12)=="df_u_refnoT4") {
			idx = Id.substring(Id.indexOf("T4r")+3,Id.length);
			if (getTableInput("T4","u_reftype",idx)!="CM" && getTableInput("T4","u_reftype",idx)!="DP" && getTableInput("T4","u_reftype",idx)!="CHRG") {
				params["params"] = ";&searchbydocno=Y";
				var tmp, refno='';
				refno = getTableInput("T4","u_refno",idx);
				tmp = refno.split("/");
				params["keys"] = getGlobal("company") + "`" + getGlobal("branch") + "`" +  tmp[0];
			}	
		}			
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_creditcard");
			break;
		case "T2":
			if (getTableInputType(table,"u_itemcode")!="hidden") {
				focusTableInput(table,"u_itemcode");
			} else {
				focusTableInput(table,"u_itemdesc");
			}
			break;
		case "T3":
			enableTableInput(table,"u_memberid");
			enableTableInput(table,"u_membername");
			enableTableInput(table,"u_membertype");
			focusTableInput(table,"u_inscode");
			break;
	}
	
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_creditcard")) return false;
			if (isTableInputEmpty(table,"u_creditcardno")) return false;
			if (isTableInputEmpty(table,"u_creditcardname")) return false;
			if (isTableInputEmpty(table,"u_expiredate")) return false;
			if (isTableInputEmpty(table,"u_approvalno")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T2":
			if (getInput("u_payreftype")=="DP" || getInput("u_payreftype")=="RS") {
				page.statusbar.showError("Item not required for downpayment/deposit.");
				return false;
			}
			if (getTableInput(table,"u_itemcode")) {
				if (isTableInputEmpty(table,"u_itemcode")) return false;
			}
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="3" && getTableInput(table,"u_hmo")!="5" && getTableInput(table,"u_hmo")!="7") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}
			
			if (getInput("u_prepaid")=="3" && getGlobal("roleid")!="FIN-ACCT") {
				page.statusbar.showError("You cannot have health benefits on final bill payment. Use Billing CM instead for health benefits.");
				return false;
			}
			
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeCreditCardTotalGPSHIS(); break;
		case "T2": 
			computePatientTotalAmountGPSHIS(table,"u_totalamount","u_vatamount","u_discamount"); 
			setInputAmount("u_dueamount",getInputNumeric("u_totalamount"));
			computeAmountRowGPSHIS(); 
			break;
		case "T3": computeOtherTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_creditcard")) return false;
			if (isTableInputEmpty(table,"u_creditcardno")) return false;
			if (isTableInputEmpty(table,"u_creditcardname")) return false;
			if (isTableInputEmpty(table,"u_expiredate")) return false;
			if (isTableInputEmpty(table,"u_approvalno")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T2":
			if (getInput("u_payreftype")=="DP" || getInput("u_payreftype")=="RS") {
				page.statusbar.showError("Item not required for downpayment.");
				return false;
			}
			if (getTableInput(table,"u_itemcode")) {
				if (isTableInputEmpty(table,"u_itemcode")) return false;
			}
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="3" && getTableInput(table,"u_hmo")!="5" && getTableInput(table,"u_hmo")!="7") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}

			if (getInput("u_prepaid")=="3" && getGlobal("roleid")!="FIN-ACCT") {
				page.statusbar.showError("You cannot have health benefits on final bill payment. Use Billing CM instead for health benefits.");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeCreditCardTotalGPSHIS(); break;
		case "T2": 
			computePatientTotalAmountGPSHIS(table,"u_totalamount","u_vatamount","u_discamount"); 
			setInputAmount("u_dueamount",getInputNumeric("u_totalamount"));
			computeAmountRowGPSHIS(); 
			break;
		case "T3": computeOtherTotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeCreditCardTotalGPSHIS(); break;
		case "T2": 
			computePatientTotalAmountGPSHIS(table,"u_totalamount","u_vatamount","u_discamount"); 
			setInputAmount("u_dueamount",getInputNumeric("u_totalamount"));
			computeAmountRowGPSHIS(); 
			break;
		case "T3": computeOtherTotalGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T4":
			params["focus"] = false;
			if (elementFocused.substring(0,15)=="df_u_creditedT1") {
				focusTableInput(table,"u_credited",row);
			}
			break;
	}
	return params;
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch(table) {
		case "T3":
			switch (getTableInput(table,"u_hmo")) {
				case "0":
					enableTableInput(table,"u_memberid");
					enableTableInput(table,"u_membername");
					enableTableInput(table,"u_membertype");
					focusTableInput(table,"u_memberid");
					//enableInput("u_caserate");
					break;
				case "1":
				case "4":
					enableTableInput(table,"u_memberid");
					enableTableInput(table,"u_membername");
					focusTableInput(table,"u_memberid");
					break;
				case "6":
					enableTableInput(table,"u_memberid");
					focusTableInput(table,"u_memberid");
					break;
			}
			break;
	}
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T2"), totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			totalamount += getTableInputNumeric("T2","u_linetotal",i);
		}
	}
	setInputAmount("u_totalamount",totalamount);
	setInputAmount("u_dueamount",getInputNumeric("u_totalamount") - getInputNumeric("u_discamount"));
	computeAmountRowGPSHIS();
}

function computeCreditCardTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamount += getTableInputNumeric("T1","u_amount",i);
		}
	}
	setInputAmount("u_creditcardamount",totalamount);
	computeAmountRowGPSHIS();
}

function computeOtherTotalGPSHIS() {
	var rc =  getTableRowCount("T3"), totalamount=0;
	
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			totalamount += getTableInputNumeric("T3","u_amount",i);
		}
	}
	setInputAmount("u_otheramount",totalamount);
	computeAmountRowGPSHIS();
}

function computeCreditTotalGPSHIS() {
	var rc =  getTableRowCount("T4"), creditamount=0, totalamount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			if (getInput("u_payreftype")=="SI") {
				switch (getTableInput("T4","u_reftype",i)) {
					case "DP":
					case "CM":
					//case "Credits/Partial Payments":
						creditamount -= getTableInputNumeric("T4","u_credited",i);
						break;
					default:
						if (getTableInput("T4","u_billno",i)=="") {
							totalamount += getTableInputNumeric("T4","u_credited",i);
						} else {
							creditamount -= getTableInputNumeric("T4","u_credited",i);
						}
						break;
				}
			} else if (getInput("u_payreftype")=="PN") {
				switch (getTableInput("T4","u_reftype",i)) {
					case "PN":
						totalamount += getTableInputNumeric("T4","u_credited",i);
						break;
				}
			} else {
				switch (getTableInput("T4","u_reftype",i)) {
					case "DP":
					case "CM":
						creditamount += getTableInputNumeric("T4","u_credited",i);
						break;
					default:
						totalamount += getTableInputNumeric("T4","u_credited",i);
						break;
				}
			}
		}
	}
	if (getInput("u_payreftype")=="SI") {
		setInputAmount("u_totalamount",totalamount);
		setInputAmount("u_discamount",0);
		setInputAmount("u_dueamount",totalamount);
	} else if (getInput("u_payreftype")=="PN") {
		setInputAmount("u_totalamount",totalamount);
		setInputAmount("u_discamount",0);
		setInputAmount("u_dueamount",totalamount);
	}
	setInputAmount("u_creditamount",creditamount);
	computeAmountRowGPSHIS();
}


function computeAmountRowGPSHIS() {
	var balanceamount=0,chngamount=0;
	balanceamount = getInputNumeric("u_dueamount") - (getInputNumeric("u_recvamount") + getInputNumeric("u_checkamount") + getInputNumeric("u_creditcardamount") + getInputNumeric("u_otheramount") + getInputNumeric("u_creditamount"));
	chngamount = (getInputNumeric("u_recvamount") + getInputNumeric("u_checkamount") + getInputNumeric("u_creditcardamount") + getInputNumeric("u_otheramount") + getInputNumeric("u_creditamount")) - getInputNumeric("u_dueamount");
	setInputAmount("u_balanceamount",balanceamount);
	if (getInputNumeric("u_recvamount")>0) setInputAmount("u_chngamount",chngamount);
	else setInputAmount("u_chngamount",0);
	if (getInput("u_prepaid")=="2") {
		setInputAmount("u_balance",getInputNumeric("u_dueamount"));
	} else {
		setInputAmount("u_balance",0);
	}
}

function getCreditsGPSHIS() {
	var data = new Array();
	clearTable("T4",true);
	if (getInput("u_payreftype")=="SI") {
		var result = page.executeFormattedQuery("select 'CM' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, a.U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' AS U_BILLNO, a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT*-1 as U_AMOUNT, a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hiscredits a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.U_BALANCE>0 union all select 'DP' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,'' AS U_BILLNO,a.DOCNO,a.U_DISCONBILL,0 AS  U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_DUEAMOUNT*-1 AS U_AMOUNT, a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hispos a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and u_prepaid in (2) and docstatus not in ('CN') and a.U_BALANCE>0 union all select b.U_FEETYPE as U_DOCTYPE, b.U_DOCTORID, c.NAME as U_DOCTORNAME, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID, a.U_DOCDATE AS U_STARTDATE,a.U_DOCTIME AS U_STARTTIME,'' AS U_BILLNO,concat(a.DOCNO,b.U_SUFFIX) as DOCNO,0 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, b.U_AMOUNT AS U_AMOUNT, b.U_NETAMOUNT-b.U_PAIDAMOUNT AS U_DUEAMOUNT from u_hisbills a, u_hisbillfees b left join u_hisdoctors c on c.code=b.u_doctorid where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and docstatus not in ('D','CN') and (b.U_NETAMOUNT-b.U_PAIDAMOUNT)<>0 union all select b.U_FEETYPE as U_DOCTYPE, b.U_DOCTORID, c.NAME as U_DOCTORNAME, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID, a.U_DOCDATE AS U_STARTDATE,a.U_DOCTIME AS U_STARTTIME,a.DOCNO as U_BILLNO, concat(a.DOCNO,b.U_SUFFIX) as DOCNO,0 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, b.U_PAIDAMOUNT*-1 AS U_AMOUNT, b.U_PAIDAMOUNT*-1 AS U_DUEAMOUNT from u_hisbills a, u_hisbillfees b left join u_hisdoctors c on c.code=b.u_doctorid  where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_patientid='"+getInput("u_patientid")+"' and docstatus = 'CN' and b.U_PAIDAMOUNT<>0 union all select 'CHRG' as U_DOCTYPE, '' AS U_DOCTORID, '' AS U_DOCTORNAME, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' AS U_BILLNO, a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT as U_AMOUNT, a.U_AMOUNT AS U_DUEAMOUNT from u_hischarges a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.U_PREPAID=0 AND a.U_BILLNO='' and a.DOCSTATUS not in ('CN')");
	} else if (getInput("u_payreftype")=="PN") {
		var result = page.executeFormattedQuery("select 'PN' as U_DOCTYPE, U_DOCTORID, b.NAME AS U_DOCTORNAME, U_FEETYPE, U_BILLNO, a.DOCNO, a.U_PNAMOUNT-a.U_BTAMOUNT as U_AMOUNT, a.U_PNAMOUNT-a.U_BTAMOUNT-a.U_PAIDAMOUNT AS U_DUEAMOUNT from u_hispronotes a left join u_hisdoctors b on b.code=a.u_doctorid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_billno='"+getInput("u_payrefno")+"' and a.u_hmo='5' and (a.U_PNAMOUNT-a.U_BTAMOUNT-a.U_PAIDAMOUNT)>0 and A.DOCSTATUS not in ('CN')");
	} else {
		var result = page.executeFormattedQuery("select 'CM' as U_DOCTYPE, '' AS U_DOCTORID, a.U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,'' as U_BILLNO,a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hiscredits a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.U_BALANCE>0 union all select 'DP' as U_DOCTYPE, '' AS U_DOCTORID, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,'' as U_BILLNO, a.DOCNO,a.U_DISCONBILL,0 AS  U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_DUEAMOUNT AS U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hispos a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and u_prepaid in (2) and docstatus not in ('CN') and a.U_BALANCE>0");
	}
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				if (getInput("u_payreftype")=="SI") data["u_selected"] = 1;
				else if (getInput("u_payreftype")=="PN") data["u_selected"] = 1;
				else data["u_selected"] = 0;
				data["u_billno"] = result.childNodes.item(iii).getAttribute("u_billno");
				data["u_refno"] = result.childNodes.item(iii).getAttribute("docno");
				data["u_reftype"] = result.childNodes.item(iii).getAttribute("u_doctype");
				data["u_doctorid"] = result.childNodes.item(iii).getAttribute("u_doctorid");
				data["u_reftype.text"] = data["u_reftype"];
				switch (data["u_reftype"]) {
					case "CHRG": data["u_reftype.text"] = "Charges"; break;
					case "CM": data["u_reftype.text"] = "Credit"; break;
					case "PN": 
						data["u_reftype.text"] = "Promisorry Note"; 
						break;
					case "DP": data["u_reftype.text"] = "Partial Payment"; break;
				}
				if (data["u_reftype"]=="Professional Fees" || data["u_reftype"]=="Professional Materials") {
					data["u_remarks"] = result.childNodes.item(iii).getAttribute("u_doctype").toUpperCase() + " - " + result.childNodes.item(iii).getAttribute("u_doctorname").toUpperCase();
				} else if(data["u_reftype"]=="CHRG") {
					data["u_remarks"] = "AFTER BILL CHARGES";
				} else if(data["u_reftype"]=="PN") {
					data["u_remarks"] = result.childNodes.item(iii).getAttribute("u_feetype").toUpperCase();
					if (data["u_doctorid"]!="") data["u_remarks"] = result.childNodes.item(iii).getAttribute("u_doctorname").toUpperCase() + " - " + result.childNodes.item(iii).getAttribute("u_feetype").toUpperCase();
				} else data["u_remarks"] = data["u_reftype"].toUpperCase();
				
				data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
				data["u_balance"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_dueamount"));
				if (getInput("u_payreftype")=="SI") {
					data["u_credited"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_dueamount"));
				} else if (getInput("u_payreftype")=="PN") {
					data["u_credited"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_dueamount"));
				} else {
					data["u_credited"] = formatNumericAmount(0);
				}
				insertTableRowFromArray("T4",data);
				if (getInput("u_payreftype")=="SI") {
					//if (data["u_reftype"]!="CM" && data["u_reftype"]!="DP") {
					//	disableTableInput("T4","u_selected",iii+1);
					//	disableTableInput("T4","u_credited",iii+1);
					//}
					if (data["u_reftype"]=="CHRG") {
						disableTableInput("T4","u_credited",iii+1);
					}
				} else if (getInput("u_payreftype")=="PN") {
				} else {
					//enableTableInput("T4","u_selected",iii+1);
					disableTableInput("T4","u_credited",iii+1);
				}
			}
		}
	} else {
		page.statusbar.showError("Error retrieving credits. Try Again, if problem persists, check the connection.");	
		return false;
	}	
}

function OpenLnkBtnDocNo(targetId) {
	switch (getInput("u_prepaid")) {
		case "1": OpenLnkBtnARInvoices(targetId); break;
		case "2": OpenLnkBtnIncomingPayments(targetId);	break;
		case "3": OpenLnkBtnIncomingPayments(targetId);	break;
	}
}

function OpenLnkBtnPayRefNo(targetId) {
	//var targetObjectId = 'u_hisphicclaims';
	switch (getInput("u_payreftype")) {
		case "CHRG":
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "SI":
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisbills' + '' + '&targetId=' + targetId ,targetId);
			break;
		default:
			page.statusbar.showWarning("No available details for this reference no.");
			break;
	}
	
}

function OpenLnkBtnCreditRefNo(targetId) {
	switch (getTableElementValue(targetId,"T4","u_reftype")) {
		case "CM":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hiscredits' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "DP":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hispos' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "CHRG":
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "PN":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hispronotes' + '' + '&targetId=' + targetId ,targetId);
			break;
		default:
			OpenLnkBtnJournalVouchers(targetId);
			break;
	}
}
function getNextQueGPSHIS(docno) {
	var msgcnt = 0;	
	showAjaxProcess();
	try {
		//alert('querying');
		http = getHTTPObject(); 
		//alert( "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"));
		http.open("GET", "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"), false);
		http.send(null);
		var result = parseInt(http.responseText.trim());
		hideAjaxProcess();
		if (result>0) {
			setInput("u_queue",result);
		} else alert(http.responseText.trim());
		http.send(null);
	} catch (theError) {
		hideAjaxProcess();
	}	
}
