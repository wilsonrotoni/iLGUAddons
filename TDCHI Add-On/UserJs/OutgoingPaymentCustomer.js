page.events.add.load('onPageLoadGPSTDCHI');
page.events.add.submit('onPageSubmitGPSTDCHI');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSTDCHI');
page.elements.events.add.validate('onElementValidateGPSTDCHI');
page.elements.events.add.cfl('onElementCFLGPSTDCHI');

function onPageLoadGPSTDCHI() {
	if (getVar("formSubmitAction")=="a") {
		if (getGlobal("roleid")=="FIN-CASHIER")	{
			setInputSelectedText("docseries","Refund Voucher",true);
			setInputSelectedText("cashacct","Cash on Hand",true);
			setInput("u_cashierid",getGlobal("userid"));
			setInput("u_chqexp","PATIENT REFUND");
		}
	}
}

function onPageSubmitGPSTDCHI(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_chqexp",null,null,"tab1",11)) {
			hideAjaxProcess();
			return false;
		}
		if (getInput("u_chqexp")=="PATIENT REFUND" && getInput("bpcode")!="HO-POS") {
			if (isInputEmpty("refno")) return false;
		}
		if (getInput("collfor")=="DP") {
			if (getInput("bpcode").substring(0,4)=="ADE-") {
				if (getInput("docdate")==getInput("taxdate")) {
					hideAjaxProcess();
					page.statusbar.showError("Liquidation Date cannot be same as Posting Date.");
					focusInput("taxdate");
					return false;
				}
			}
		}
	}
	return true;
}

function onElementValidateGPSTDCHI(element,column,table,row) {
	var data = new Array(),rc=0;
	switch (table) {
		default:
			switch(column) {
				case "refno":
					if (getInput("refno")!="") {
						if (getInput("u_chqexp")=="PATIENT REFUND") {
							result = page.executeFormattedQuery("select docno from u_hisbills where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getInput("refno")+"' and u_patientid='"+getInput("bpcode")+"' and docstatus IN ('O','CN') union all select docno from u_hispos where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getInput("refno")+"' and u_patientid='"+getInput("bpcode")+"' and u_balance<>0 union all select docno from u_hiscredits where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getInput("refno")+"' and u_patientid='"+getInput("bpcode")+"' and u_balance<>0");	 						
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("refno",result.childNodes.item(0).getAttribute("docno"));
									rc = getTableRowCount("T3");
									for (iii = 1; iii <= rc; iii++) {
										if (getTableInput("T3","bprefno",iii)==getInput("refno") || getTableInput("T3","refno",iii)==getInput("refno")) {
											checkedTableInput("T3","rowchk",iii);
										}
									}
									computeAmountDue();
								} else {
									page.statusbar.showError("Invalid Bill No.");	
									return false;
								}
							} else {
								page.statusbar.showError("Error retrieving Bill record. Try Again, if problem persists, check the connection.");	
								return false;
							}
							
						}
					}
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSTDCHI(Id) {
	switch (Id) {
		case "df_refno":
			if (getInput("u_chqexp")!="PATIENT REFUND") {
				if (window.confirm("Are you going to issue patient refund?.\nContinue?")==false) return false;
				setInput("u_chqexp","PATIENT REFUND");
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSTDCHI(Id,params) {
	switch (Id) {
		case "df_bpcode":
			if (getInput("collfor")=="DP") params["params"] += ";-WHERE:A.CUSTGROUP IN ('22','14')";
			break;
		case "df_refno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_docdate, u_dueamount, u_dueamount-u_paidamount from u_hisbills where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("bpcode")+"' and docstatus  in ('O','CN') union all select docno, u_docdate, u_dueamount, u_balance from u_hispos where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("bpcode")+"' and u_balance<>0 union all select docno, u_startdate, u_amount, u_balance from u_hiscredits where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("bpcode")+"' and u_balance<>0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Due Amount`Balance")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("12`12`12`12")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`amount`amount")); 			
			break;
			
	}
	return params;
}
