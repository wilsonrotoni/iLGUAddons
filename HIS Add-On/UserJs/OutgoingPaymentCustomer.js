
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');


function popupCopyDocumentFromu_HISCashAdvances() {
	OpenCFLfs('u_hiscashadvances');
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hiscashadvances":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_docdate, u_empname, u_amount  from u_hiscashadvances where docstatus='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Name of Employee`Amount")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`35`12")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``amount")); 			
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	switch (Id) {
		case "u_hiscashadvances":
			setInput("collfor","DP",true);
			selectTab("tab1",3);
			var result = page.executeFormattedQuery("select docno, u_docdate, u_empid, u_empname, u_amount  from u_hiscashadvances where docstatus='O' and docno = '"+value+"'");	
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					setInput("bpcode",result.childNodes.item(0).getAttribute("u_empid"),true);
					setInput("refno",result.childNodes.item(0).getAttribute("docno"));
					setInput("u_cano",result.childNodes.item(0).getAttribute("docno"));
					setInput("remarks","Based from Cash Advance No "+result.childNodes.item(0).getAttribute("docno"));
					setTableInputAmount("T1","amount",result.childNodes.item(0).getAttribute("u_amount"));
				} else {
					setInput("refno","");
					setInput("u_cano","");
					setInput("bpcode","");
					setInput("remarks","");
					setTableInputAmount("T1","amount",0);
					page.statusbar.showError("Invalid Cash Advance No.");	
					return false;
				}
			} else {
				setInput("refno","");
				setInput("u_cano","");
				setInput("bpcode","");
				setInput("remarks","");
				setTableInputAmount("T1","amount",0);
				page.statusbar.showError("Error retrieving Cash Advance record. Try Again, if problem persists, check the connection.");	
				return false;
			}
			break;
	}
	return true;
}