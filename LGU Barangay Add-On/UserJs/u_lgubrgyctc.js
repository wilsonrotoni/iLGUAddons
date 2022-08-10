// page events
page.events.add.load('onPageLoadGPSLGUBarangay');
//page.events.add.resize('onPageResizeGPSLGUBarangay');
page.events.add.submit('onPageSubmitGPSLGUBarangay');
//page.events.add.cfl('onCFLGPSLGUBarangay');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUBarangay');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUBarangay');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUBarangay');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUBarangay');
page.elements.events.add.validate('onElementValidateGPSLGUBarangay');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUBarangay');
//page.elements.events.add.changing('onElementChangingGPSLGUBarangay');
page.elements.events.add.change('onElementChangeGPSLGUBarangay');
//page.elements.events.add.click('onElementClickGPSLGUBarangay');
//page.elements.events.add.cfl('onElementCFLGPSLGUBarangay');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUBarangay');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUBarangay');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUBarangay');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUBarangay');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUBarangay');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUBarangay');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUBarangay');
page.tables.events.add.delete('onTableDeleteRowGPSLGUBarangay');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUBarangay');
//page.tables.events.add.select('onTableSelectRowGPSLGUBarangay');

function onPageLoadGPSLGUBarangay() {
if (getVar("formSubmitAction")=="a") {
		selectTab("tab1",0);
		if (getInput("u_apptype")!="I") {
		
		}
	}
	
}

function onPageResizeGPSLGUBarangay(width,height) {
}

function onPageSubmitGPSLGUBarangay(action) {

  if (action=="a" || action=="sc") {
    if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_date",null,null,"tab1",0)) return false;	
		if (isInputEmpty("u_lastname",null,null,"tab1",1)) return false;
		if (isInputEmpty("u_firstname",null,null,"tab1",1)) return false;
		
	}

	return true;
}

function onCFLGPSLGUBarangay(Id) {
	return true;
}

function onCFLGetParamsGPSLGUBarangay(Id,params) {

	return params;
}

function onTaskBarLoadGPSLGUBarangay() {
}

function onElementFocusGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUBarangay(element,event,column,table,row) {
}

function onElementValidateGPSLGUBarangay(element,column,table,row) {
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
			break;
		default:
                    switch (column) {
			case "u_refno":
                             if (getInput(column)!="") {
                                var result = page.executeFormattedQuery("select * from u_lgubrgyresidence where docno = '"+getInput(column)+"'");	
                                   if (result.getAttribute("result")!= "-1") {
                                       if (parseInt(result.getAttribute("result"))>0) {
                                               setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
                                               setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
                                               setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
                                              
                                               setInput("u_sitio",result.childNodes.item(0).getAttribute("u_sitio"));
                                       }else{
                                           page.statusbar.showError("Invalid reference number.");	
                                           return false;
                                       }
                                   }else{
                                       page.statusbar.showError("Error retrieving record. Try Again, if problem persists, check the connection.");	
                                       return false;
                                   }
                               }
			
                        break;
			
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSLGUBarangay(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUBarangay(element,column,table,row) {
switch (table) {
		default:
			switch(column) {
				case "docseries":
					setDocNo(true,null,null,'u_date');
					break;
			}
	}
	return true;
}

function onElementClickGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUBarangay(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUBarangay(Id,params) {
    switch (Id) {
        case "df_u_feecodeT1":
            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgubrgyfees")); 
            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
            break;
        case "df_u_feedescT1":
            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgubrgyfees")); 
            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
            break;
        case "df_u_refno":
            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_name, u_sitio, u_dateofbirth from (select docno,concat(u_lastname,', ',u_firstname,' ',u_middlename) as u_name, u_sitio, u_dateofbirth from u_lgubrgyresidence) as x")); 
            params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Ref No.`Full Name`Sitio/Purok`Date of Birth")); 			
            params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`35`25`15")); 			
            params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
            break;
            
    }		
    return params;
}

function onTableResetRowGPSLGUBarangay(table) {
}

function onTableBeforeInsertRowGPSLGUBarangay(table) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUBarangay(table,row) {
switch (table) {
	case "T1": computeTotalAssessment(); break;
	
	}
}

function onTableBeforeUpdateRowGPSLGUBarangay(table,row) {
switch (table) {
	
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}

	return true;
}

function onTableAfterUpdateRowGPSLGUBarangay(table,row) {
switch (table) {
	case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeDeleteRowGPSLGUBarangay(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUBarangay(table,row) {
switch (table) {
	case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeSelectRowGPSLGUBarangay(table,row) {
	return true;
}

function onTableSelectRowGPSLGUBarangay(table,row) {
}

function u_forpaymentGPSLGUBarangay() {
    if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
    if (isInputEmpty("u_date",null,null,"tab1",0)) return false;
    if (isTableInputNegative("u_ctcapps","u_asstotal")) return false;
    //if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
  var rc = getTableRowCount("T1"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
		}
	}
	if (total < 1){
	 page.statusbar.showError("Total amount is invalid");
	  selectTab("tab1",1);
      return false;
	}
    if (getTableRowCount("T1")==0) {
      page.statusbar.showError("At least one fee or charges must be entered.");
      selectTab("tab1",1);
      return false;
    }
    if (window.confirm("Are you sure?")==false) {
		return false;
    }
	setInput("docstatus","Paid");
	formSubmit("sc");
}

function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
			if (getTableInput("T1","u_feecode",i)==getPrivate("annualtaxcode")) {
				btax+= getTableInputNumeric("T1","u_amount",i);
			}
		}
	}
	setInput("forpayment","1");
	setInputAmount("u_asstotal",total);
}

