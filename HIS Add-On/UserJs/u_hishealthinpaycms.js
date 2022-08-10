// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');

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
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_inscode")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_preparedby")) return false;
		if (getInputNumeric("u_variance")!=0) {
			page.statusbar.showError("Variance must be equal to zero.");
			return false;
		}
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
				case "u_discperc":
				case "u_wtaxperc":
					if (row>0) {
						if (getTableInputNumeric(table,column,row)<0) {
							page.statusbar.showWarning("You cannot enter negative percentage.");	
							setTableInputAmount(table,column,0,row);
						}
						computeTotalGPSHIS();
					}
					break;
				case "u_applied":
					if (row>0) {
						if (getTableInputNumeric(table,"u_balance",row)>0) {
							if (getTableInputNumeric(table,"u_applied",row)>getTableInputNumeric(table,"u_balance",row)) {
								page.statusbar.showWarning("You cannot apply more than the balance.");	
								setTableInputAmount(table,"u_applied",getTableInputNumeric(table,"u_balance",row),row);
							}
						} else {
							if (getTableInputNumeric(table,"u_applied",row)>(getTableInputNumeric(table,"u_balance",row)*-1)) {
								page.statusbar.showWarning("You cannot apply more than the balance.");	
								setTableInputAmount(table,"u_applied",getTableInputNumeric(table,"u_balance",row),row);
							}
						}
						computeTotalGPSHIS();
					}
					break;
			}
			break;
		default:
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
		case "T1":
			break;
		default:
			switch (column) {
				case "u_inscode":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select name, u_postdoctorpayable from u_hishealthins where code='"+getInput(column)+"'");			
						if (result.getAttribute("result")!= "-1") {					
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_insname",result.childNodes.item(0).getAttribute("name"));
								setInput("u_postdoctorpayable",result.childNodes.item(0).getAttribute("u_postdoctorpayable"));
							} else {
								setInput("u_postdoctorpayable","1");
								page.statusbar.showError("Invalid Health Benefit.");	
								return false;
							}
							break;
						} else {
							setInput("u_insname","");
							setInput("u_postdoctorpayable","1");
							page.statusbar.showError("Error retrieving document. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
					clearTable("T1",true);
					computeTotalGPSHIS();
					break;
			}
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_refnoT1":
			if (isInputEmpty("u_inscode")) return false;
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, docdate, otherbpname, balanceamount, othertrxtype, doctorname, otherdocno from (select a.docno,a.docdate, '' as otherbpname, a.balanceamount*-1 as balanceamount, '' as othertrxtype, '' as doctorname, '' as otherdocno from collections a where a.company='"+getGlobal("company")+"' and a.branchcode='"+getGlobal("branch")+"' and a.bpcode='"+getInput("u_inscode")+"' and a.collfor='DP' and a.balanceamount<>0 and a.docstat in ('O','C') union all select a.docno,b.docdate, b.otherbpname, a.balanceamount, a.othertrxtype, a.otherbpname as doctorname, b.otherdocno from journalvoucheritems a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.itemno='"+getInput("u_inscode")+"' and a.balanceamount<>0) as x where balanceamount<>0 ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Patient Name`Balance`Type`Doctor`Reference No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("11`9`25`11`15`20`12")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``amount```")); 			
			params["params"] += "&cflsortby=otherbpname";		
			params["params"] += "&cflselectionmode=2";		
			params["params"] += "&cflreturnonselect=1"; 
			params["params"] += "&cflautomanagecolumnwidth=0";		
			params["params"] += "&cflcloseonselect=0"; 
			params["params"] += "&cflsort2by=docdate"; 
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_refnoT1":
			result = page.executeFormattedQuery("select a.docno,a.docdate, '' as bprefno, '' as patientname, a.balanceamount*-1 as balanceamount, '' as feetype, '' as doctorname, '' as otherdocno, 'DOWNPAYMENT' as objectcode from collections a where a.company='"+getGlobal("company")+"' and a.branchcode='"+getGlobal("branch")+"' and a.bpcode='"+getInput("u_inscode")+"' and a.collfor='DP' and a.balanceamount<>0 and a.docno='"+value+"' union all select a.docno,b.docdate, b.reference1 as bprefno, b.otherbpname as patientname, a.balanceamount, a.othertrxtype as feetype, a.otherbpname as doctorname, b.otherdocno, 'JOURNALVOUCHER' as objectcode from journalvoucheritems a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.itemno='"+getInput("u_inscode")+"' and a.balanceamount<>0 and a.docno='"+value+"'");			
			if (result.getAttribute("result")!= "-1") {
				data["u_refno"] = result.childNodes.item(0).getAttribute("docno");
				data["u_refno2"] = result.childNodes.item(0).getAttribute("bprefno");
				data["u_refdate"] = formatDateToHttp(result.childNodes.item(0).getAttribute("docdate"));
				data["u_reftype"] = result.childNodes.item(0).getAttribute("objectcode");
				data["u_patientname"] = result.childNodes.item(0).getAttribute("patientname");
				data["u_feetype"] = result.childNodes.item(0).getAttribute("feetype");
				/*switch (data["u_feetype"]) {
					case "Hospital Fees": data["u_feetype.text"] = "HF"; break;
					case "Professional Fees": data["u_feetype.text"] = "PF"; break;
					case "Professional Materials": data["u_feetype.text"] = "PM"; break;
				}*/
				data["u_doctorname"] = result.childNodes.item(0).getAttribute("doctorname");
				data["u_balance"] = formatNumericAmount(result.childNodes.item(0).getAttribute("balanceamount"));
				data["u_applied"] = formatNumericAmount(result.childNodes.item(0).getAttribute("balanceamount"));
				if (isTableInputUnique("T1","u_refno",data["u_refno"],getTableInputCaption("T1","u_refno")+" ["+data["u_refno"]+"] already exists.")) {
					insertTableRowFromArray("T1",data);
				} else return false;
				if (data["u_feetype"]!="") {
					disableTableInput("T1","u_discperc",getTableRowCount("T1"));
					disableTableInput("T1","u_wtaxperc",getTableRowCount("T1"));
				}
				computeTotalGPSHIS();
			} else {
				page.statusbar.showError("Error retrieving document. Try Again, if problem persists, check the connection.");	
				return false;
			}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
			return false;
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_refno")) return false;
			if (isTableInputZero(table,"u_balance")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_refno")) return false;
			if (isTableInputZero(table,"u_balance")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeTotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeTotalGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			if (elementFocused.substring(0,14)=="df_u_appliedT1") {
				focusTableInput(table,"u_applied",row);
			}
			break;
	}
	return params;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), payment=0,applied=0,discamount=0,wtaxamount=0,directpfamount=0,variance=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			//variance += getTableInputNumeric("T1","u_applied",i);
			if (getTableInputNumeric("T1","u_balance",i)<0) {
				payment += getTableInputNumeric("T1","u_applied",i)*-1;
			} else {
				applied += getTableInputNumeric("T1","u_applied",i);
			}
			if (getTableInputNumeric("T1","u_balance",i)<0 && getTableInputNumeric("T1","u_applied",i)<0) {
				if (getTableInputNumeric("T1","u_discperc",i)>0) {
					discamount += (getTableInputNumeric("T1","u_discperc",i)/100 * (getTableInputNumeric("T1","u_applied",i)*-1));
				}
				if (getTableInputNumeric("T1","u_wtaxperc",i)>0) {
					wtaxamount += (getTableInputNumeric("T1","u_wtaxperc",i)/100 * (getTableInputNumeric("T1","u_applied",i)*-1));
				}
			}
			if (getInput("u_postdoctorpayable")==0 && (getTableInput("T1","u_feetype",i)=="Professional Fees" || getTableInput("T1","u_feetype",i)=="Professional Materials")) {			
				directpfamount += getTableInputNumeric("T1","u_applied",i);
			}
		}
	}
	setInputAmount("u_directpfamount",directpfamount);	
	setInputAmount("u_discamount",discamount);	
	setInputAmount("u_wtaxamount",wtaxamount);	
	setInputAmount("u_paidamount",payment);	
	setInputAmount("u_applied",applied);	
	setInputAmount("u_variance",getInputNumeric("u_applied")-getInputNumeric("u_paidamount")-getInputNumeric("u_wtaxamount")-getInputNumeric("u_discamount")-getInputNumeric("u_directpfamount"));	
}

