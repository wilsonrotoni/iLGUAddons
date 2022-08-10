// page events
page.events.add.submitreturn('onPageSubmitReturnGPSMotorViolation');
page.events.add.load('onPageLoadGPSMotorViolation');
//page.events.add.resize('onPageResizeGPSMotorViolation');
page.events.add.submit('onPageSubmitGPSMotorViolation');
//page.events.add.cfl('onCFLGPSMotorViolation');
//page.events.add.cflgetparams('onCFLGetParamsGPSMotorViolation');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSMotorViolation');

// element events
//page.elements.events.add.focus('onElementFocusGPSMotorViolation');
page.elements.events.add.keydown('onElementKeyDownGPSMotorViolation');
page.elements.events.add.validate('onElementValidateGPSMotorViolation');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSMotorViolation');
//page.elements.events.add.changing('onElementChangingGPSMotorViolation');
page.elements.events.add.change('onElementChangeGPSMotorViolation');
page.elements.events.add.click('onElementClickGPSMotorViolation');
//page.elements.events.add.cfl('onElementCFLGPSMotorViolation');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSMotorViolation');


// table events
//page.tables.events.add.reset('onTableResetRowGPSMotorViolation');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSMotorViolation');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSMotorViolation');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSMotorViolation');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSMotorViolation');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSMotorViolation');
page.tables.events.add.delete('onTableDeleteRowGPSMotorViolation');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSMotorViolation');
page.tables.events.add.select('onTableSelectRowGPSMotorViolation');


function onPageLoadGPSMotorViolation() {
}

function onPageResizeGPSMotorViolation(width,height) {
}

function onPageSubmitGPSMotorViolation(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document 

 if (action=="a") {
                if (isInputEmpty("u_appdate")) return false;
		if (isInputEmpty("u_lastname")) return false;	
		if (isInputEmpty("u_firstname")) return false;	
		//if (isInputEmpty("u_docseries")) return false;	
		if (isInputEmpty("u_vehicletype")) return false;	
		if (isInputEmpty("u_plateno")) return false;	
		if (isInputEmpty("u_ticketby")) return false;	
		if (isInputEmpty("u_feecode")) return false;	
		if (isInputNegative("u_amount")) return false;	
		if (isInputNegative("u_totalamount")) return false;	
		//if (isInputNegative("u_gross")) return false;
                //if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	}
	return true;	
 
}
//function onPageSubmitReturnGPSMotorViolation(action,sucess,error) {
	//if (action=="a" && sucess) {
          //  if(window.confirm("Print Official Receipt. Continue?")) OpenReportSelect('printer');
	//}
//}

function onCFLGPSMotorViolation(Id) {
	return true;
}

function onCFLGetParamsGPSMotorViolation(Id,params) {
	return params;
}

function onTaskBarLoadGPSMotorViolation() {
}

function onElementFocusGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSMotorViolation(element,event,column,table,row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		case "ENTER":
                        if(column=="u_gross") {
                            setCTCFees();
                        }
			break;
		case "F4":
			PostTransaction();
			break;
	
		
	}
}

function onElementValidateGPSMotorViolation(element,column,table,row) {
	switch (table) { 
		default:
			switch(column) {
				case "u_tickeyby":
                                    if (getInput(column)!="") {
							var result = page.executeFormattedQuery("select code,name from u_motorofficers where code='"+getInput(column)+"'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("code",result.childNodes.item(0).getAttribute("code"));
									setInput("name",result.childNodes.item(0).getAttribute("u_middlename"));
									
									
								} else {
									setInput("code",result.childNodes.item(0).getAttribute("code"));
									setInput("name",result.childNodes.item(0).getAttribute("u_middlename"));
									page.statusbar.showError("Invalid License No");	
									return false;
								}
							} else {
                                  setInput("code",result.childNodes.item(0).getAttribute("code"));
									setInput("name",result.childNodes.item(0).getAttribute("u_middlename"));
								page.statusbar.showError("Error retrieving License No. record. Try Again, if problem persists, check the connection.");	
								return false;
							}
							
					} 
                                    break;
									
				
				
			case "u_licenseno":
                                    if (getInput(column)!="") {
							var result = page.executeFormattedQuery("select u_firstname,u_middlename,u_lastname from u_motorviolators where code='"+getInput(column)+"'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
									setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
									setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
									
								} else {
									setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
									setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
									setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
									page.statusbar.showError("Invalid License No");	
									return false;
								}
							} else {
                                   setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
									setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
									setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								page.statusbar.showError("Error retrieving License No. record. Try Again, if problem persists, check the connection.");	
								return false;
							}
							
					} 
                                    break;
									
				}
				
				
				
				case "T1":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name from u_motorviolations where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name from u_motorviolations where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								//setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								//setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							//setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						//setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			
			
			
	}
	return true;
}

function onElementGetValidateParamsGPSMotorViolation(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementChangeGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementClickGPSMotorViolation(element,column,table,row) {
	return true;
}

function onElementCFLGPSMotorViolation(element) {
	return true;
}

function onElementCFLGetParamsGPSMotorViolation(Id,params) {
	switch (Id) {
		
		case "df_u_licenseno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,u_firstname,u_middlename,u_lastname from u_motorviolators ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Licenseno`Firstname`Middlename`Lastname`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30`15`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
			case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_motorviolations")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_motorviolations")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
		case "df_u_ticketby":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_motorofficers")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
	}
	
	
	return params;
}

function onTableResetRowGPSMotorViolation(table) {
}

function onTableBeforeInsertRowGPSMotorViolation(table) {
	
	switch (table) {
		
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			//if (isTableInputNegative(table,"u_total")) return false;
			break;
		
	}
	return true;
}

function onTableAfterInsertRowGPSMotorViolation(table,row) {
	switch (table) {
		
		case "T1": computeTotalAssessment();break;
		
		
	}
			
}

function onTableBeforeUpdateRowGPSMotorViolation(table,row) {
	
	
	
	return true;
}

function onTableAfterUpdateRowGPSMotorViolation(table,row) {
	switch (table) {
		
		
		case "T1": computeTotalAssessment();break;
		
	}
}

function onTableBeforeDeleteRowGPSMotorViolation(table,row) {
	return true;
}

function onTableDeleteRowGPSMotorViolation(table,row) {
	switch (table) {
		
		
		case "T1": computeTotalAssessment();break;
		
	}
	
}

function onTableBeforeSelectRowGPSMotorViolation(table,row) {
	return true;
}

function onTableSelectRowGPSMotorViolation(table,row) {
	
	return true;

}


function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0,discount=0,total2=0,total3=0,discount2=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			discount= getTableInputNumeric("T1","u_discount",i);
			total= getTableInputNumeric("T1","u_amount",i);
			//total2+= getTableInputNumeric("T1","u_total",i);
			
			total3+= getTableInputNumeric("T1","u_amount",i);
			discount2+= getTableInputNumeric("T1","u_discount",i);
			
			setTableInputAmount("T1","u_total",formatNumericAmount(total-discount),i);
			//total3= getTableInputNumeric("T1","u_total",i);
			
			
			//total2+= getTableInputNumeric("T1","u_total",i);
			//if (getTableInput("T1","u_feecode",i)==getPrivate("annualtaxcode")) {
				//btax+= getTableInputNumeric("T1","u_amount",i);
			//}
//                        if (getTableInput("T5","u_feecode",i)!=getPrivate("annualtaxcode") && getTableInput("T5","u_feecode",i)!=137) {
//				btax1+= getTableInputNumeric("T5","u_amount",i);
//                      	}
                      
		}
	}
        
//         for (i = 1; i <= rc; i++) {
//		if (isTableRowDeleted("T5",i)==false) {
//                     if (getTableInput("T5","u_feecode",i)==137) {
//				setTableInputAmount("T5","u_amount",formatNumericAmount(btax1*.05),i);
//                                taxrc=i;
//			}
//                }
//          }
//        
//       if(taxrc==0){
//           var data = new Array();
//				data["u_feecode"] = 137;
//				data["u_feedesc"] = 'OTHERS';
//				data["u_amount"] = formatNumericAmount(btax1*.05);
//				insertTableRowFromArray("T5",data);
//       }
     
	//setInputAmount("u_btaxamount",btax);
	setInputAmount("u_totalamount",total3-discount2);
    //setInputAmount("u_total",false);
	
	
}
function OpenLnkBtnu_u_licenseno(targetObjectId) {
	//OpenLnkBtn(1080,680,'./UDP.php?&objectcode=U_VIEWVIOLATIONS' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	OpenLnkBtn(1224,500,"./UDP.php?&objectcode=U_VIEWVIOLATIONS&df_refno2="+getInput("u_licenseno")+"","history of Violations");
}
function PostTransaction() {
    setInput("docstatus","C");
    formSubmit();
}
function u_reassessGPSMotorViolation() {
	if (isInputEmpty("u_remarks",null,null,"tab1",4)) return false;
	setInput("docstatus","O");
	formSubmit('sc');
}


	