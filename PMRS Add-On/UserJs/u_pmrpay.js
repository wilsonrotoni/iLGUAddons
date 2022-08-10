// page events
//page.events.add.load('onPageLoadGPSPMRS');
//page.events.add.resize('onPageResizeGPSPMRS');
page.events.add.submit('onPageSubmitGPSPMRS');
//page.events.add.cfl('onCFLGPSPMRS');
//page.events.add.cflgetparams('onCFLGetParamsGPSPMRS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPMRS');

// element events
//page.elements.events.add.focus('onElementFocusGPSPMRS');
//page.elements.events.add.keydown('onElementKeyDownGPSPMRS');
page.elements.events.add.validate('onElementValidateGPSPMRS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPMRS');
//page.elements.events.add.changing('onElementChangingGPSPMRS');
page.elements.events.add.change('onElementChangeGPSPMRS');
page.elements.events.add.click('onElementClickGPSPMRS');
//page.elements.events.add.cfl('onElementCFLGPSPMRS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPMRS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSPMRS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPMRS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPMRS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPMRS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPMRS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPMRS');
//page.tables.events.add.delete('onTableDeleteRowGPSPMRS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPMRS');
//page.tables.events.add.select('onTableSelectRowGPSPMRS');

function onPageLoadGPSPMRS() {
}

function onPageResizeGPSPMRS(width,height) {
}

function onPageSubmitGPSPMRS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_refno",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_date",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_custno",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_stallno",null,null,"tab1",0)) return false;
                if (isInputNegative("u_totalamount")) return false;
                if(action=="a"){
                    if (confirm("You cannot change this document after you have added it. Continue?")==false) return false;
                }
                
               
	}
	return true;
}

function onCFLGPSPMRS(Id) {
	return true;
}

function onCFLGetParamsGPSPMRS(Id,params) {
	return params;
}

function onTaskBarLoadGPSPMRS() {
}

function onElementFocusGPSPMRS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPMRS(element,event,column,table,row) {
}

function onElementValidateGPSPMRS(element,column,table,row) {
	switch (table) {
		
		default:
			switch (column) {
                            
				case "u_refno":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select docno, concat(u_lastname,', ',u_firstname) as u_custname, u_stallno, u_publicmarket from u_pmrapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'  and docno='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_custname",result.childNodes.item(0).getAttribute("u_custname"));
								setInput("u_stallno",result.childNodes.item(0).getAttribute("u_stallno"));
								setInput("u_bldg",result.childNodes.item(0).getAttribute("u_publicmarket"));
                                                                getPmrBill();
								
							} else {
								setInput("u_custname","");
								setInput("u_stallno","");
								setInput("u_bldg","");
								
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_custname","");
                                                        setInput("u_stallno","");
                                                        setInput("u_bldg","");
							page.statusbar.showError("Error retrieving reference no. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_custname","");
                                                setInput("u_stallno","");
                                                setInput("u_bldg","");
					}						
					break;
			}		
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSPMRS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPMRS(element,column,table,row) {
	return true;
}

function onElementChangeGPSPMRS(element,column,table,row) {
	return true;
}

function onElementClickGPSPMRS(element,column,table,row) {
	switch (table) {
		case "T1":
			if (row==0) {
				if (isTableInputChecked(table,column)) {
					checkedTableInput(table,column,-1);
				} else {
					uncheckedTableInput(table,column,-1);
				}
			} 
			computeTotal();
			break;
		
	}
	return true;
}

function onElementCFLGPSPMRS(element) {
	return true;
}

function onElementCFLGetParamsGPSPMRS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_lastname,u_firstname,u_publicmarket, u_stallno, u_rentalfee, u_year from u_pmrapps ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Permit No.`Lastname`Firstname`Building`Stall No`Rental Fee`Year")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`20`20`15`5`10`5")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``````")); 				
			break;
	}		
	return params;
}

function onTableResetRowGPSPMRS(table) {
}

function onTableBeforeInsertRowGPSPMRS(table) {
	return true;
}

function onTableAfterInsertRowGPSPMRS(table,row) {
}

function onTableBeforeUpdateRowGPSPMRS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSPMRS(table,row) {
}

function onTableBeforeDeleteRowGPSPMRS(table,row) {
	return true;
}

function onTableDeleteRowGPSPMRS(table,row) {
}

function onTableBeforeSelectRowGPSPMRS(table,row) {
	return true;
}

function onTableSelectRowGPSPMRS(table,row) {
    var params = Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			break;
	}
	return params;
}

function getPmrBill() {
    
        clearTable("T1",true);
        var result = page.executeFormattedQuery("select docno,u_totalamount, u_custname,u_remarks from u_lgubills  where u_appno = '"+getInput("u_refno")+"' and company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"' AND DOCSTATUS NOT IN ('CN','D')");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                            var data = new Array();
                            data["u_billno"] = result.childNodes.item(xxx).getAttribute("docno");
                            data["u_amount"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_totalamount"));
                            data["u_custname"] = result.childNodes.item(xxx).getAttribute("u_custname");
                            data["u_remarks"] = result.childNodes.item(xxx).getAttribute("u_remarks");
                            insertTableRowFromArray("T1",data);
                        }
                    }
                }
	
}

function computeTotal() {
	var rc =  getTableRowCount("T1"), total=0;
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				total+= getTableInputNumeric("T1","u_amount",xxx);
				}
		}
	}
	setInputAmount("u_totalamount",total);
}

function u_forCancelGPSPMS() {
     if (confirm("You want to cancel this transaction?")==false){
         return false;
     }else {
        setInput("docstatus","CN");
	formSubmit('sc');
     }
	
}

