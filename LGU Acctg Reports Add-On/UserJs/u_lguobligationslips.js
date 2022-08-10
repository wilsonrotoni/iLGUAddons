  // page events
//page.events.add.load('onPageLoadGPSLGUAcctgReports');
//page.events.add.resize('onPageResizeGPSLGUAcctgReports');
//page.events.add.submit('onPageSubmitGPSLGUAcctgReports');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctgReports');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctgReports');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctgReports');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctgReports');
//page.elements.events.add.validate('onElementValidateGPSLGUAcctgReports');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctgReports');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctgReports');
//page.elements.events.add.change('onElementChangeGPSLGUAcctgReports');
//page.elements.events.add.click('onElementClickGPSLGUAcctgReports');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctgReports');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctgReports');
//page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSLGUAcctgReports');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctgReports');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctgReports');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctgReports');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctgReports');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctgReports');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctgReports');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctgReports');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctgReports');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctgReports');



function onPageLoadGPSLGUAcctgReports() {
}

function onPageResizeGPSLGUAcctgReports(width,height) {
}

function onPageSubmitGPSLGUAcctgReports(action) {
	
	return true;
}

function onCFLGPSLGUAcctgReports(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctgReports(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctgReports() {
}

function onElementFocusGPSLGUAcctgReports(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctgReports(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctgReports(element,column,table,row) {
	
	return true;
}

function onElementGetValidateParamsGPSLGUAcctgReports(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctgReports(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctgReports(element,column,table,row) {
	
	return true;
}

function onElementClickGPSLGUAcctgReports(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctgReports(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctgReports(id,params) {
	
	return params;
}

function onElementLnkBtnGetParamsGPSLGUAcctgReports(id,params) {
	
	return params;
}




function onTableResetRowGPSLGUAcctgReports(table) {
}

function onTableBeforeInsertRowGPSLGUAcctgReports(table,row) {
	switch (table) {
		case "T1":
			//if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isInputEmpty("u_date")) return false;
			if (isInputEmpty("u_profitcenter")) return false;
			var budgetamount = 0;
			var obliamount = 0;
			var date = getInput("u_date").substr(0,2);
			var yr = getInput("u_date").substr(6,4);
			var datefr = yr + '-01-01';
                        
			if(date <= '03'){
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");	
			}else if(date > '03' && date <= '06' ){
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) + SUM(B.U_Q2) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");
			}else if(date > '06' && date <= '09'){
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) + SUM(B.U_Q2) + SUM(B.U_Q3) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");	
			}else{
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) + SUM(B.U_Q2) + SUM(B.U_Q3) + SUM(B.U_Q4) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");
			}
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
						budgetamount = result.childNodes.item(0).getAttribute("amount");
				}else{
					budgetamount= 0 ;
				}
			}else{
				page.statusbar.showError("Error retrieving data. Try Again, if problem persists, check the connection.");	
				return false;
			}  
                        
                        var result2 = page.executeFormattedQuery("SELECT SUM(U_DEBIT) AS OBLIAMOUNT FROM U_LGUOBLIGATIONSLIPS A INNER JOIN U_LGUOBLIGATIONSLIPACCTS B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' AND A.U_DATE BETWEEN '"+datefr+"' AND '"+formatDateToDB(getInput("u_date"))+"' AND A.COMPANY = '"+ getGlobal("company") +"' AND A.BRANCH = '"+ getGlobal("branch") +"'");	
			if (result2.getAttribute("result")!= "-1") {
				if (parseInt(result2.getAttribute("result"))>0) {
                                    obliamount = result2.childNodes.item(0).getAttribute("obliamount");
                                }
                            }
                        if(formatNumericAmount(budgetamount - obliamount ) == 0){
                             if(window.confirm("No Budget amount found for ["+getInput("u_profitcenter")+"] GL["+getTableInput(table,"u_glacctname")+"] \nYou want to proceed?"));
                             else return false;
                        }else{
                            if(getTableInputNumeric(table,"u_debit") > (budgetamount - obliamount)){
                                if(window.confirm("Debit amount["+getTableInput(table,"u_debit")+"] is larger than the remaining budget amount["+formatNumericAmount(budgetamount - obliamount)+"] for GL['"+getTableInput(table,"u_glacctname")+"'] \nYou want to proceed?")) ;
                                else return false; 
                            }
                        }
                       
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctgReports(table,row) {
	
}

function onTableBeforeUpdateRowGPSLGUAcctgReports(table,row) {
	switch (table) {
		case "T1":
			if (isInputEmpty("u_date")) return false;
			if (isInputEmpty("u_profitcenter")) return false;
			var budgetamount = 0;
			var obliamount = 0;
			var date = getInput("u_date").substr(0,2);
			var yr = getInput("u_date").substr(6,4);
			var datefr = yr + '-01-01';
                        
			if(date <= '03'){
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");	
			}else if(date > '03' && date <= '06' ){
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) + SUM(B.U_Q2) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");
			}else if(date > '06' && date <= '09'){
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) + SUM(B.U_Q2) + SUM(B.U_Q3) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");	
			}else{
                            var result = page.executeFormattedQuery("SELECT SUM(B.U_Q1) + SUM(B.U_Q2) + SUM(B.U_Q3) + SUM(B.U_Q4) AS AMOUNT FROM U_LGUBUDGET A INNER JOIN U_LGUBUDGETGLS B ON A.CODE = B.CODE AND A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND A.U_YR = '"+yr+"' AND A.COMPANY = '"+ getGlobal("company")+"' AND A.BRANCH = '"+ getGlobal("branch") +"' AND B.U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' GROUP BY A.CODE");
			}
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
						budgetamount = result.childNodes.item(0).getAttribute("amount");
				}else{
					budgetamount= 0 ;
				}
			}else{
				page.statusbar.showError("Error retrieving data. Try Again, if problem persists, check the connection.");	
				return false;
			}  
                        
                        var result2 = page.executeFormattedQuery("SELECT SUM(U_DEBIT) AS OBLIAMOUNT FROM U_LGUOBLIGATIONSLIPS A INNER JOIN U_LGUOBLIGATIONSLIPACCTS B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID WHERE A.U_PROFITCENTER = '"+getInput("u_profitcenter")+"' AND U_GLACCTNO = '"+getTableInput(table,"u_glacctno")+"' AND A.U_DATE BETWEEN '"+datefr+"' AND '"+formatDateToDB(getInput("u_date"))+"' AND A.COMPANY = '"+ getGlobal("company") +"' AND A.BRANCH = '"+ getGlobal("branch") +"'");	
			if (result2.getAttribute("result")!= "-1") {
				if (parseInt(result2.getAttribute("result"))>0) {
                                    obliamount = result2.childNodes.item(0).getAttribute("obliamount");
                                }
                            }
                        if(formatNumericAmount(budgetamount - obliamount ) == 0){
                             if(window.confirm("No Budget amount found for ["+getInput("u_profitcenter")+"] GL["+getTableInput(table,"u_glacctname")+"] \nYou want to proceed?"));
                             else return false;
                        }else{
                            if(getTableInputNumeric(table,"u_debit") > (budgetamount - obliamount)){
                                if(window.confirm("Debit amount["+getTableInput(table,"u_debit")+"] is larger than the remaining budget amount["+formatNumericAmount(budgetamount - obliamount)+"] for GL['"+getTableInput(table,"u_glacctname")+"'] \nYou want to proceed?")) ;
                                else return false; 
                            }
                        }
			break;
		
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctgReports(table,row) {
	
}

function onTableBeforeDeleteRowGPSLGUAcctgReports(table,row) {
	
	return true;
}

function onTableDeleteRowGPSLGUAcctgReports(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctgReports(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctgReports(table,row) {
	
	return params;
}

function CheckBudgetperMonth(){
	
}

