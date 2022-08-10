// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

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
//page.elements.events.add.cfl('onElementCFLGPSHIS');
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
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="?") {
		if (isInputEmpty("u_custgroup")) return false;
		if (isInputEmpty("u_docdate")) return false;
	} else if (action=="a" || action=="sc") {
		if (isInputEmpty("u_custgroup")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_glacctno")) return false;
		if (isInputEmpty("u_glacctname")) return false;
		if (isInputEmpty("u_preparedby")) return false;
		if (isInputNegative("u_totaldeduction")) return false;
		if ((action == "a" && getInput("docstatus")!="D") || ((getPrivate("docstatus")=="D") && (getInput("docstatus")!="D"))){
			if (getInput("docstatus")!="D") {
				if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
			}
		}	
		
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			//params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
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
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_deduction":
					if (getTableInputNumeric(table,"u_balance",row)>0) {
						if (getTableInputNumeric(table,"u_deduction",row)>getTableInputNumeric(table,"u_balance",row)) {
							page.statusbar.showWarning("You cannot deduct more than the balance.");	
							setTableInputAmount(table,"u_deduction",getTableInputNumeric(table,"u_balance",row),row);
						}
					} else {
						if ((getTableInputNumeric(table,"u_deduction",row)*-1)>(getTableInputNumeric(table,"u_balance",row)*-1) || getTableInputNumeric(table,"u_deduction",row)>0) {
							page.statusbar.showWarning("You cannot deduct more than the balance.");	
							setTableInputAmount(table,"u_deduction",getTableInputNumeric(table,"u_balance",row),row);
						}
					}
					computeTotalGPSHIS();
					break;
			}
			break;
		default:
			switch (column) {
				case "u_docdate":
					if (getInput("u_custgroup")!="" && getInput("u_docdate")!="") formSubmit('?');
					break;
				case "u_glacctno":
				case "u_glacctname":
					if (column="u_glacctname") {
						var result = page.executeFormattedQuery("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1 and acctname like '"+getInput(column)+"%'");		
					} else {
						var result = page.executeFormattedQuery("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getInput(column)+"'");		
					}
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							setInput("u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
							setInput("u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
						} else {
							setInput("u_glacctno","");
							setInput("u_glacctname","");
							page.statusbar.showError("Invalid G/L Account.");	
							return false;
						}
					} else {
						setInput("u_glacctno","");
						setInput("u_glacctname","");
						page.statusbar.showError("Error retrieving g/l account record. Try Again, if problem persists, check the connection.");	
						return false;
					}
					
					break;
			}
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
		case "T1":
			break;
		default:
			switch (column) {
				case "u_custgroup":
					setInput("u_custgroupname",getInputSelectedText(column));
					clearTable("T1",true);
					if (getInput("u_custgroup")!="" && getInput("u_docdate")!="") formSubmit('?');
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_glacctno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`50")); 			
			break;
		case "df_u_glacctname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`20")); 			
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
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			//if (elementFocused.substring(0,16)=="df_u_deductionT1") {
			//}
			if (isPopupFrameOpen("popupFrameARs") && row>0) showARs();
			focusTableInput(table,"u_deduction",row);
			break;
	}
	return params;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), deduction=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			deduction += getTableInputNumeric("T1","u_deduction",i);
		}
	}
	setInputAmount("u_totaldeduction",deduction);	
}


function OpenLnkBtnARs(retrieve) {
	if (retrieve==null) retrieve = false;
	showPopupFrame("popupFrameARs");
	if (retrieve) {
		if (getTableSelectedRow("T10")>0) showARs();
	}
}

function showARs(showprocess) {
	if (showprocess==null) showprocess = true;
	var row = getTableSelectedRow("T1"), data = new Array();
	var groupby = '', isgroup = false;
	if (row==0) return;

	if (showprocess) showAjaxProcess();
	clearTable("T10",true);
	setTableInput("T11","custname",getTableInput("T1","u_custname",row));
	var result = page.executeFormattedQuery("select b.othertrxtype, sum(b.debit-b.credit) as totalamount, sum(b.balanceamount) as balanceamount from customers a inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.itemtype='C' and b.itemno=a.custno and b.balanceamount<>0 inner join journalvouchers c on c.company=b.company and c.branch=b.branch and c.docid=b.docid and c.docdate<='"+formatDateToDB(getInput("u_docdate"))+"' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.custno='"+getTableInput("T1","u_custno",row)+"' group by b.othertrxtype having balanceamount>0 order by b.othertrxtype");	 
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
				var data = new Array();
				//data["docno"] = result.childNodes.item(xxxi).getAttribute("docno");
				//data["refno"] = result.childNodes.item(xxxi).getAttribute("reference1");
				data["feetype"] = result.childNodes.item(xxxi).getAttribute("othertrxtype");
				//data["docdate"] = formatDateToHttp(result.childNodes.item(xxxi).getAttribute("docdate"));
				data["amount"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("totalamount"));
				data["balance"] = formatNumericQuantity(result.childNodes.item(xxxi).getAttribute("balanceamount"));
				insertTableRowFromArray("T10",data);
			}
		}
	} else {
		if (showprocess) hideAjaxProcess();
		page.statusbar.showError("Error retrieving A/Rs. Try Again, if problem persists, check the connection.");	
		return false;
	}
	if (showprocess) hideAjaxProcess();
}

function OpenLnkBtnDocNo(targetId) {
	OpenLnkBtnJournalVouchers(targetId)	;
	
}