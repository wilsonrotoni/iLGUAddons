// page events
page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
page.events.add.submit('onPageSubmitGPSBPLS');
//page.events.add.cfl('onCFLGPSBPLS');
//page.events.add.cflgetparams('onCFLGetParamsGPSBPLS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBPLS');

// element events
//page.elements.events.add.focus('onElementFocusGPSBPLS');
//page.elements.events.add.keydown('onElementKeyDownGPSBPLS');
page.elements.events.add.validate('onElementValidateGPSBPLS');
page.elements.events.add.validateparams('onElementGetValidateParamsGPSBPLS');
//page.elements.events.add.changing('onElementChangingGPSBPLS');
page.elements.events.add.change('onElementChangeGPSBPLS');
page.elements.events.add.click('onElementClickGPSBPLS');
//page.elements.events.add.cfl('onElementCFLGPSBPLS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
page.tables.events.add.select('onTableSelectRowGPSBPLS');

function onPageLoadGPSBPLS() {
    var data = new Array();
    clearTable("T2",true);
    if (getVar("formSubmitAction")=="a") {
		try {
//                    alert(window.opener.getInput("docno"));
			setInput("u_appno",window.opener.getInput("docno"),true);
                        if (getInput("u_appno")!="") {
                            var result = page.executeFormattedQuery("select * from u_bplapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getInput("u_appno")+"'");
                            if (parseInt(result.getAttribute("result"))>0) {
//                                       
                                   setInput("u_gender",window.opener.getInput("u_gender"),true); 
                                   setInput("u_lastname",window.opener.getInput("u_lastname"),true); 
                                   setInput("u_firstname",window.opener.getInput("u_firstname"),true); 
                                   setInput("u_middlename",window.opener.getInput("u_middlename"),true); 
                                   setInput("u_businessname",window.opener.getInput("u_businessname"),true); 
//                                			
                            }
                        }
		} catch ( e ) {
		
                }
}
}

function onPageResizeGPSBPLS(width,height) {
}

function onPageSubmitGPSBPLS(action) {
    if (action=="a" || action=="sc") {  
//                if (isInputNegative("u_asstotal")) return false;
		if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
		
	}
	return true;
}

function onCFLGPSBPLS(Id) {
	return true;
}

function onCFLGetParamsGPSBPLS(Id,params) {
	return params;
}

function onTaskBarLoadGPSBPLS() {
}

function onElementFocusGPSBPLS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSBPLS(element,event,column,table,row) {
}

function onElementValidateGPSBPLS(element,column,table,row) {
    switch (table) {
		case "T1":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
//			break;
		
	}
	return true;
}

function onElementGetValidateParamsGPSBPLS(table,row,column) {
	
	return true;
}

function onElementChangingGPSBPLS(element,column,table,row) {
	return true;
}

function onElementChangeGPSBPLS(element,column,table,row) {
    switch (table) {
		default:
			switch(column) {
				case "docseries":
					setDocNo(true,null,null,'u_appdate');
					break;
				case "u_businesschar":
					if (getPrivate("bplkindcharlink")=="1") {
						u_ajaxloadu_bplkinds("df_u_businesskind",element.value,'',":");
					}
                                        
											
					break;
		
			}
	}
    
	return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
	return true;
}

function onElementCFLGPSBPLS(element) {
	return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
    switch (Id) {
		case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table) {
    switch (table) {
		
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSBPLS(table,row) {
    switch (table) {
//		case "T1": setBusinessLineRequirements(); setBusinessLineFees(); computeAnnualTax(); break;
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeUpdateRowGPSBPLS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
}

function onTableBeforeDeleteRowGPSBPLS(table,row) {
	return true;
}

function onTableDeleteRowGPSBPLS(table,row) {
    switch (table) {
//		case "T1": setBusinessLineRequirements(); setBusinessLineFees(); computeAnnualTax(); break;
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeSelectRowGPSBPLS(table,row) {
	return true;
}

function onTableSelectRowGPSBPLS(table,row) {
}
function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0,btax=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
			
		}
	}	
//	setInputAmount("u_btaxamount",btax);
	setInputAmount("u_asstotal",total);
}
function u_generatebillGPSBPLS() {
//	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
//	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
//	if (isInputEmpty("u_paymode",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
	
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}
function u_reassessGPSBPLS() {

	setInput("docstatus","Encoding");
	formSubmit('sc');
}

