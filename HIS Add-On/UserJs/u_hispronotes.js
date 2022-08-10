// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	try {
		if (getVar("formSubmitAction")=="a" && getInput("u_billno")=="") {
			setInput("u_billno",window.opener.getInput("docno"));
			setInput("u_reftype",window.opener.getInput("u_reftype"));
			setInput("u_refno",window.opener.getInput("u_refno"));
			setInput("u_patientid",window.opener.getInput("u_patientid"));
			setInput("u_patientname",window.opener.getInput("u_patientname"));
			setInput("u_docdate",window.opener.getInput("u_docdate"));
			setInput("u_gender",window.opener.getInput("u_gender"));
			setInput("u_age",window.opener.getInput("u_age"));
			setInput("u_startdate",window.opener.getInput("u_startdate"));
			setInput("u_enddate",window.opener.getInput("u_enddate"));
			setInput("u_icdcode",window.opener.getInput("u_icdcode"));
			u_ajaxloadu_hisbilldoctors("df_u_doctorid", window.opener.getInput("docno"),"",":");
			//u_ajaxloadu_hisbillcasecodes("df_u_casecode", window.opener.getInput("docno"),"",":");
			if (getInput("u_btrefno")!="") {
				setInput("u_doctorid",getInput("u_btdoctorid"));	
			} else {
				setInput("u_amount",window.opener.getInput("u_netamount"));
				setInput("u_netamount",window.opener.getInput("u_netamount"));
				setActualChargeGPSHIS();
			}
		}
	} catch (theError) {
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_billno")) return false;
		if (isInputEmpty("u_docdate")) return false;
//		if (isInputEmpty("u_docduedate")) return false;
		if (isInputEmpty("u_guarantorcode")) return false;
		if (getInput("u_hmo")=="-1") {
			page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
			setInput("u_guarantorcode");
			return false;
		}		
		if (isInputEmpty("u_reftype")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (getInput("u_type")=="Promissory Note" && getInput("u_hmo")!="5") {
			page.statusbar.showError("Health Benefit must be of type Collector if Promissory Note is selected.");	
			setInput("u_guarantorcode");
			return false;
		}
		switch (getInput("u_hmo")) {
			case "0":	//phic
				if (isInputEmpty("u_memberid")) return false;
				if (isInputEmpty("u_membername")) return false;
				if (isInputEmpty("u_membertype")) return false;
				//if (isInputEmpty("u_caserate")) return false;
				break;
			case "1":	//hmo
			case "4":	//company
			case "6":	//others
				if (isInputEmpty("u_memberid")) return false;
				if (isInputEmpty("u_membername")) return false;
				break;
		}		
		if (getInput("u_feetype")!="Hospital Fees" && getInput("u_feetype")!="Credits/Partial Payments" && getInput("u_feetype")!="After Bill Charges") {
			if (isInputEmpty("u_doctorid")) return false;
		}		
		/*if (getInput("u_casecode")!="" || getInput("u_caserate")!="") {
			if (isInputEmpty("u_casecode")) return false;
			if (isInputEmpty("u_caserate")) return false;
		}*/
		if (getInput("u_btrefno")!="" && getInput("u_type")=="Debit Memo") {
			page.statusbar.showError("You cannot use Debit Memo to transfer the balance.");
			return false;
		}
		
		if (isInputNegative("u_pnamount")) return false;
		//if (getInputNumeric("u_netamount")<0 && !isInputChecked("u_exclaim")) {
		//	if (isInputNegative("u_netamount")) return false;
		//}
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		if (success) {	
			window.opener.setKey("keys",window.opener.getInput("docno"));
			//window.opener.setInput("u_tab1selected",1);
			window.opener.formEdit();
			window.close();
		}
	} catch(TheError) {
	}
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_refno":
					if (getInput("u_refno")!="") {
						result = page.executeFormattedQuery("select u_patientid, u_patientname from u_hisips where docno='"+getInput("u_refno")+"' and docstatus not in ('Discharged')");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
					}
					break;
				case "u_memberid":
					if (getInput("u_hmo")!=6) return true;
					
					if (getInput("u_memberid")!="") {
						result = page.executeFormattedQuery("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getInputSelectedText("u_guarantorcode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and a.custno='"+getInput("u_memberid")+"' and isvalid=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_memberid",result.childNodes.item(0).getAttribute("custno"));
								setInput("u_membername",result.childNodes.item(0).getAttribute("custname"));
							} else {
								setInput("u_memberid","");
								setInput("u_membername","");
								page.statusbar.showError("Invalid Customer No.");	
								return false;
							}
						} else {
							setInput("u_memberid","");
							setInput("u_membername","");
							page.statusbar.showError("Error retrieving Customer No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_memberid","");
						setInput("u_membername","");
					}
					break;
				case "u_pnamount":
					computeAmountGPSHIS();
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
		default:
			switch (column) {
				case "docseries":
					if (getInput("docseries")!=-1) {
						setInput("u_type",getInputSelectedText("docseries"),true);
					}
					setDocNo(true,"docseries","docno");
					break;
				case "u_guarantorcode":
					disableInput("u_memberid");
					disableInput("u_membername");
					disableInput("u_membertype");
					//disableInput("u_caserate");
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_hmo from u_hishealthins where code='"+getInput("u_guarantorcode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
								result2 = page.executeFormattedQuery("select u_memberid, u_membername, u_membertype from u_hispatienthealthins where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getInput("u_patientid")+"' and u_inscode='"+getInput("u_guarantorcode")+"'");	 
								if (result2.getAttribute("result")!= "-1") {
									if (parseInt(result2.getAttribute("result"))>0) {
										setInput("u_memberid",result2.childNodes.item(0).getAttribute("u_memberid"));
										setInput("u_membername",result2.childNodes.item(0).getAttribute("u_membername"));
										setInput("u_membertype",result2.childNodes.item(0).getAttribute("u_membertype"));
									} else {
										setInput("u_memberid","");
										setInput("u_membername","");
										setInput("u_membertype","");
									}
								} else {
									setInput("u_hmo","-1");
									setInput("u_memberid","");
									setInput("u_membername","");
									setInput("u_membertype","");
									page.statusbar.showError("Error retrieving Health Benefits for this patient. Try Again, if problem persists, check the connection.");	
								}
								switch (getInput("u_hmo")) {
									case "0":
										enableInput("u_memberid");
										enableInput("u_membername");
										enableInput("u_membertype");
										focusInput("u_memberid");
										//enableInput("u_caserate");
										break;
									case "1":
									case "4":
										enableInput("u_memberid");
										enableInput("u_membername");
										focusInput("u_memberid");
										break;
									case "6":
										enableInput("u_memberid");
										focusInput("u_memberid");
										break;
								}
							} else {
								setInput("u_hmo","-1");
								setInput("u_memberid","");
								setInput("u_membername","");
								setInput("u_membertype","");
								page.statusbar.showError("Invalid Health Benefits Code.");	
								return false;
							}
						} else {
							setInput("u_hmo","-1");
							setInput("u_memberid","");
							setInput("u_membername","");
							setInput("u_membertype","");
							page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_hmo","-1");
						setInput("u_memberid","");
						setInput("u_membername","");
						setInput("u_membertype","");
					}
					break;
				case "u_feetype":
					disableInput("u_doctorid");
					if (getInput("u_feetype")!="Hospital Fees" && getInput("u_feetype")!="Credits/Partial Payments" && getInput("u_feetype")!="After Bill Charges") {
						enableInput("u_doctorid");
						focusInput("u_doctorid");
					} else {
						setInput("u_doctorid","");
					}
					setActualChargeGPSHIS();
					break;
				case "u_doctorid":
					setActualChargeGPSHIS();
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_type":
					if (getInput("docseries")!=-1) {
						setInputSelectedText("docseries",getInput("u_type"));
						setDocNo(true,"docseries","docno");
					}
					computeAmountGPSHIS();
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	if (Id=="df_u_memberid" && getInput("u_hmo")!=6) {
		page.statusbar.showWarning("Please enter the member id manually.");
		return false;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_memberid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getInputSelectedText("u_guarantorcode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	params = new Array();
	return params;
}

function computeAmountGPSHIS() {
	if (getInput("u_type")=="Debit Memo") {
		setInputAmount("u_netamount",getInputNumeric("u_amount")+getInputNumeric("u_pnamount"));
	} else {
		setInputAmount("u_netamount",getInputNumeric("u_amount")-getInputNumeric("u_pnamount"));
	}
}

function setActualChargeGPSHIS() {
	if (getInput("u_feetype")=="After Bill Charges") {
		var result = page.executeFormattedQuery("select sum(a.u_amount) as u_netamount from u_hischarges a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.U_PREPAID=0 AND a.U_BILLNO='' and a.DOCSTATUS not in ('CN')");	 
	} else {
		var result = page.executeFormattedQuery("select b.u_netamount-b.u_paidamount as u_netamount from u_hisbills a inner join u_hisbillfees b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"' and b.u_feetype='"+getInput("u_feetype")+"' and b.u_doctorid='"+getInput("u_doctorid")+"'");	 
	}
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			var u_netamount = parseFloat(result.childNodes.item(0).getAttribute("u_netamount"));
			if (u_netamount>=0) {
				setInput("u_type","Credit Memo");
				setInputSelectedText("docseries","Credit Memo");
			} else {
				//setInputAmount("u_amount",u_netamount*-1);
				//setInputAmount("u_pnamount",0);
				//setInputAmount("u_netamount",u_netamount*-1);
				setInput("u_type","Debit Memo");
				setInputSelectedText("docseries","Debit Memo");
			}
			setInputAmount("u_amount",u_netamount);
			setInputAmount("u_pnamount",0);
			setInputAmount("u_netamount",u_netamount);
		} else {
			setInputAmount("u_amount",0);
			setInputAmount("u_pnamount",0);
			setInputAmount("u_netamount",0);
			return false;
		}
	} else {
		setInputAmount("u_amount",0);
		setInputAmount("u_pnamount",0);
		setInputAmount("u_netamount",0);
		page.statusbar.showError("Error retrieving Fees. Try Again, if problem persists, check the connection.");	
		return false;
	}
}

