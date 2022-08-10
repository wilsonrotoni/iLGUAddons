// page events
page.events.add.load('onPageLoadGPSQUEUING');
//page.events.add.resize('onPageResizeGPSQUEUING');
page.events.add.submit('onPageSubmitGPSQUEUING');
//page.events.add.cfl('onCFLGPSQUEUING');
//page.events.add.cflgetparams('onCFLGetParamsGPSQUEUING');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSQUEUING');

// element events
//page.elements.events.add.focus('onElementFocusGPSQUEUING');
page.elements.events.add.keydown('onElementKeyDownGPSQUEUING');
page.elements.events.add.validate('onElementValidateGPSQUEUING');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSQUEUING');
//page.elements.events.add.changing('onElementChangingGPSQUEUING');
page.elements.events.add.change('onElementChangeGPSQUEUING');
page.elements.events.add.click('onElementClickGPSQUEUING');
//page.elements.events.add.cfl('onElementCFLGPSQUEUING');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSQUEUING');

// table events
//page.tables.events.add.reset('onTableResetRowGPSQUEUING');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSQUEUING');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSQUEUING');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSQUEUING');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSQUEUING');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSQUEUING');
page.tables.events.add.delete('onTableDeleteRowGPSQUEUING');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSQUEUING');
page.tables.events.add.select('onTableSelectRowGPSQUEUING');

function onPageLoadGPSQUEUING() {
  
    disableInput("u_custname");
    disableInput("u_cashiername");
}

function onPageResizeGPSQUEUING(width,height) {
}

function onPageSubmitGPSQUEUING(action) {
    if (action=="a" || action=="sc") {
		if (isInputEmpty("u_custname",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_cashiername",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_billno",null,null,"tab1",0)) return false;
		
	}
	return true;
}

function onCFLGPSQUEUING(Id) {
	return true;
}

function onCFLGetParamsGPSQUEUING(Id,params) {
	return params;
}

function onTaskBarLoadGPSQUEUING() {
}

function onElementFocusGPSQUEUING(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSQUEUING(element,event,column,table,row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		
		
		case "ENTER":
			formSubmit();
			break;
		default:
			
			break;
	}
}

function onElementValidateGPSQUEUING(element,column,table,row) {
    var result;	
	switch (table) {
		
		
				
		default:
			switch (column) {
				
				case "u_billno":
					if (getInput(column)!="") {
						//alert("select A.U_CUSTNO, A.U_CUSTNAME, A.U_PROFITCENTER, C.U_MODULE, A.U_DOCDATE, A.U_DUEDATE, B.U_ITEMCODE, B.U_ITEMDESC, B.U_AMOUNT, D.U_PENALTYCODE, D.U_PENALTYDESC from U_LGUBILLS A INNER JOIN U_LGUBILLITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID INNER JOIN U_LGUPROFITCENTERS C ON C.CODE=A.U_PROFITCENTER INNER JOIN U_LGUFEES D ON D.CODE=B.U_ITEMCODE where A.COMPANY='"+getGlobal("company")+"' AND A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO = '"+getInput(column)+"'");
						result = page.executeFormattedQuery("select A.U_CUSTNO, A.U_CUSTNAME, A.U_PROFITCENTER, A.U_MODULE, A.U_PAYMODE, A.U_DOCDATE, A.U_DUEDATE, A.U_DUEAMOUNT, B.U_ITEMCODE, B.U_ITEMDESC, B.U_AMOUNT, D.U_PENALTYCODE, D.U_PENALTYDESC, D.U_INTEREST from U_LGUBILLS A INNER JOIN U_LGUBILLITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID INNER JOIN U_LGUPROFITCENTERS C ON C.CODE=A.U_PROFITCENTER INNER JOIN U_LGUFEES D ON D.CODE=B.U_ITEMCODE where A.COMPANY='"+getGlobal("company")+"' AND A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								
								
								
								setInput("u_custname",result.childNodes.item(0).getAttribute("u_custname"));
										
								
								
								//setInputAmount("u_penaltyamount",penaltyamount);
								
							} else {
								
								setInput("u_custname","");
								
								page.statusbar.showError("Invalid Bill No.");	
								return false;
							}
						} else {
							setInput("u_custname","");
								
								page.statusbar.showError("Invalid Bill No.");	
								return false;
							page.statusbar.showError("Error retrieving bill record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} 
                                        
					break;
				
			}
			break;
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSQUEUING(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSQUEUING(element,column,table,row) {
	return true;
}

function onElementChangeGPSQUEUING(element,column,table,row) {
	return true;
}

function onElementClickGPSQUEUING(element,column,table,row) {
	return true;
}

function onElementCFLGPSQUEUING(element) {
	return true;
}

function onElementCFLGetParamsGPSQUEUING(element,params) {
    var params = new Array();
	switch (element) {	
		
		case "df_u_billno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, datecreated, u_remarks, u_custname from u_lgubills where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_dueamount > 0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Bill No.`Date`Due Date`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("5`15`30`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                        params["params"] += "&cflsortby=datecreated";
            break;
	}
	return params;
}

function onTableResetRowGPSQUEUING(table) {
}

function onTableBeforeInsertRowGPSQUEUING(table) {
	return true;
}

function onTableAfterInsertRowGPSQUEUING(table,row) {
}

function onTableBeforeUpdateRowGPSQUEUING(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSQUEUING(table,row) {
}

function onTableBeforeDeleteRowGPSQUEUING(table,row) {
	return true;
}

function onTableDeleteRowGPSQUEUING(table,row) {
}

function onTableBeforeSelectRowGPSQUEUING(table,row) {
	return true;
}

function onTableSelectRowGPSQUEUING(table,row) {
}

