// page events
page.events.add.load('onPageLoadGPSMTOP');
//page.events.add.resize('onPageResizeGPSMTOP');
page.events.add.submit('onPageSubmitGPSMTOP');
//page.events.add.cfl('onCFLGPSMTOP');
//page.events.add.cflgetparams('onCFLGetParamsGPSMTOP');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSMTOP');

// element events
//page.elements.events.add.focus('onElementFocusGPSMTOP');
//page.elements.events.add.keydown('onElementKeyDownGPSMTOP');
page.elements.events.add.validate('onElementValidateGPSMTOP');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSMTOP');
//page.elements.events.add.changing('onElementChangingGPSMTOP');
page.elements.events.add.change('onElementChangeGPSMTOP');
page.elements.events.add.click('onElementClickGPSMTOP');
page.elements.events.add.cfl('onElementCFLGPSMTOP');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSMTOP');

// table events
//page.tables.events.add.reset('onTableResetRowGPSMTOP');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSMTOP');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSMTOP');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSMTOP');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSMTOP');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSMTOP');
page.tables.events.add.delete('onTableDeleteRowGPSMTOP');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSMTOP');
page.tables.events.add.select('onTableSelectRowGPSMTOP');

function onPageLoadGPSMTOP() {
	if (getVar("formSubmitAction")=="a") {
		selectTab("tab1",1);
		if (getInput("u_franchiseseries")=="-1") {
			enableInput("u_franchiseno");
		}
		if (getInput("u_caseseries")=="-1") {
			enableInput("u_caseno");
		}
		if (getInput("u_apptype")!="") {
			setAppTypeTaxes();
		}
	}
	
	if (getPrivate("combinepermitfranchise")=="0") {
		setCaption("u_franchiseno","Body No.");
		switch (getInput("u_type")) {
			case "1":
				setCaption("docno","Permit No.");
				break;
			case "2":
				setCaption("docno","Franchise No.");
				break;
			default:
				setCaption("docno","No.");
				break;
		}
	}

}

function onPageResizeGPSMTOP(width,height) {
}

function onPageSubmitGPSMTOP(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_vehicletype",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_brgy",null,null,"tab1",0)) return false;
		if (getInput("u_lastname")=="" && getInput("u_dlastname")=="") {
			page.statusbar.showError("Operator and/or Driver name must be entered.");
			return false;
		}
               
		if (getInput("u_lastname")!=""){
			if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
	//		if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
		}
		if (getInput("u_dlastname")!=""){
			if (isInputEmpty("u_dfirstname",null,null,"tab1",0)) return false;
	//		if (isInputEmpty("u_dmiddlename",null,null,"tab1",0)) return false;
		}	
		
		if (getPrivate("combinepermitfranchise")=="0") {
			if (isInputEmpty("u_type",null,null,"tab1",0)) return false;
			if (getInput("u_type")=="2") {
				if (isInputEmpty("u_caseno",null,null,"tab1",0)) return false;
			}	
		}	
	}
	return true;
}

function onCFLGPSMTOP(Id) {
	return true;
}

function onCFLGetParamsGPSMTOP(Id,params) {
	return params;
}

function onTaskBarLoadGPSMTOP() {
}

function onElementFocusGPSMTOP(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSMTOP(element,event,column,table,row) {
}

function onElementValidateGPSMTOP(element,column,table,row) {
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
				case "docno":
					//setInput("u_franchiseno",getInput("docno").replace(new RegExp(getInput("u_year")+"-","g"),''));
					break;
				case "u_appdate":
					if (getInput("u_apptype")=="NEW") {
						if (getInput(column)!="") {
							setInput("u_year",getInput("u_appdate").substr(6,4));
						} else {
							setInput("u_year",0);
						}
						//setInput("u_franchiseno",getInput("docno").replace(new RegExp(getInput("u_year")+"-","g"),''));
					}	
					setAppTypeTaxes();
					break;
				case "u_franchiseno":
					if (getInput(column)!="") {
						if (getInput("u_apptype")!="NEW") {
						var result = page.executeFormattedQuery("select u_franchiseno, u_special, u_expdate, u_toda, u_brand, u_plateno, u_chassisno, u_engineno, u_lastname, u_firstname, u_middlename, u_street, u_brgy, u_city, u_province, u_telno, u_email, u_dlastname, u_dfirstname, u_dmiddlename, u_dstreet, u_dbrgy, u_dcity, u_dprovince, u_dtelno, u_demail from u_mtopapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_franchiseno='"+getInput(column)+"' and u_type='"+getInput("u_type")+"'  and docstatus in ('Approved') order by u_expdate desc");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_franchiseno",result.childNodes.item(0).getAttribute("u_franchiseno"));
								setInput("u_special",result.childNodes.item(0).getAttribute("u_special"));
								setInput("u_toda",result.childNodes.item(0).getAttribute("u_toda"));
								setInput("u_brand",result.childNodes.item(0).getAttribute("u_brand"));
								setInput("u_plateno",result.childNodes.item(0).getAttribute("u_plateno"));
								setInput("u_chassisno",result.childNodes.item(0).getAttribute("u_chassisno"));
								setInput("u_engineno",result.childNodes.item(0).getAttribute("u_engineno"));
								setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								//setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
								setInput("u_street",result.childNodes.item(0).getAttribute("u_street"));
								setInput("u_brgy",result.childNodes.item(0).getAttribute("u_brgy"));
								setInput("u_city",result.childNodes.item(0).getAttribute("u_city"));
								setInput("u_province",result.childNodes.item(0).getAttribute("u_province"));
								setInput("u_telno",result.childNodes.item(0).getAttribute("u_telno"));
								setInput("u_email",result.childNodes.item(0).getAttribute("u_email"));
								setInput("u_year",parseInt(result.childNodes.item(0).getAttribute("u_expdate").substr(0,4))+1);
								setInput("u_prevexpdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_expdate")));
								//setInput("docno",getInput("u_year")+"-"+result.childNodes.item(0).getAttribute("u_franchiseno"));
								disableInput("u_apptype");
								disableInput("u_special");
							} else {
								setInput("u_special","0");
								setInput("u_toda","");
								setInput("u_brand","");
								setInput("u_plateno","");
								setInput("u_chassisno","");
								setInput("u_engineno","");
								setInput("u_lastname","");
								setInput("u_firstname","");
								setInput("u_middlename","");
								setInput("u_street","");
								setInput("u_brgy","");
								setInput("u_city","");
								setInput("u_province","");
								setInput("u_telno","");
								setInput("u_email","");
								setInput("u_year",parseInt(formatDateToDB(getInput("u_appdate")).substr(0,4)));
								enableInput("u_special");
								if (window.confirm("Franchise/Body No. does not exists. Do you want to continue to Renew?")==false) {
									//setInput("docno","");
									setInput("u_franchiseno","");
									page.statusbar.showError("Invalid Franchise/Body No.");	
									enableInput("u_apptype");
									return false;
								} else {
									return true;	
								}	
							}
						} else {
							setInput("u_franchiseno","");
							setInput("u_special","0");
							setInput("u_toda","");
							setInput("u_brand","");
							setInput("u_plateno","");
							setInput("u_chassisno","");
							setInput("u_engineno","");
							setInput("u_lastname","");
							setInput("u_firstname","");
							setInput("u_middlename","");
							setInput("u_street","");
							setInput("u_brgy","");
							setInput("u_city","");
							setInput("u_province","");
							setInput("u_telno","");
							setInput("u_email","");
							setInput("u_year",parseInt(formatDateToDB(getInput("u_appdate")).substr(0,4)));
							//setInput("docno","");
							enableInput("u_apptype");
							enableInput("u_special");
							page.statusbar.showError("Error retrieving franchise record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						}
					} else {
						setInput("u_franchiseno","");
						setInput("u_special","0");
						setInput("u_toda","");
						setInput("u_brand","");
						setInput("u_plateno","");
						setInput("u_chassisno","");
						setInput("u_engineno","");
						setInput("u_lastname","");
						setInput("u_firstname","");
						setInput("u_middlename","");
						setInput("u_street","");
						setInput("u_brgy","");
						setInput("u_city","");
						setInput("u_province","");
						setInput("u_telno","");
						setInput("u_email","");
						setInput("u_year",parseInt(formatDateToDB(getInput("u_appdate")).substr(0,4)));
						//setInput("docno","");
						enableInput("u_apptype");
						enableInput("u_special");
					}						
					break;
				case "u_toda":
					if (getInput(column)!="") {
						if (getInput("u_special")=="0") {
							var result = page.executeFormattedQuery("select code, name from u_mtoptodas where code='"+getInput(column)+"'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_toda",result.childNodes.item(0).getAttribute("code"));
								} else {
									setInput("u_toda","");
									page.statusbar.showError("Invalid TODA");	
									return false;
								}
							} else {
								setInput("u_toda","");
								page.statusbar.showError("Error retrieving TODA record. Try Again, if problem persists, check the connection.");	
								return false;
							}
						}	
					} else {
						setInput("u_toda","");
					}						
					break;
				/*case "u_brgy":
				case "u_dbrgy":
					if (getInput(column)!="") {
						if (getInput("u_special")=="0") {
							var result = page.executeFormattedQuery("select code, name from u_barangays where code='"+getInput(column)+"'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput(column,result.childNodes.item(0).getAttribute("code"));
								} else {
									setInput(column,"");
									page.statusbar.showError("Invalid Barangay");	
									return false;
								}
							} else {
								setInput(column,"");
								page.statusbar.showError("Error retrieving barangay record. Try Again, if problem persists, check the connection.");	
								return false;
							}
						}	
					} else {
						setInput(column,"");
					}						
					break;*/
			}
	}			
	return true;
}

function onElementGetValidateParamsGPSMTOP(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSMTOP(element,column,table,row) {
	return true;
}

function onElementChangeGPSMTOP(element,column,table,row) {
	switch (table) {
		case "T1":
			break;
		default:
			switch(column) {
				case "docseries":
					//setInput("u_franchiseno",getInput("docno").replace(new RegExp(getInput("u_year")+"-","g"),''));
					switch (getInputSelectedText(column)) {
						case "Franchise":
							//setInput("u_type",2);
							break;
						case "Permit":
							//setInput("u_type",1);
							break;
						default:
							if (getInputSelectedText(column)!="Manual") {
							//	setInput("u_type",0);
							}
							break;
					}
					break;
				case "u_franchiseseries":
					setDocNo(true,"u_franchiseseries","u_franchiseno","u_appdate");
					break;
				case "u_caseseries":
					setDocNo(true,"u_caseseries","u_caseno","u_appdate");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSMTOP(element,column,table,row) {
	switch (table) {
		case "T1":
			break;
		default:
			switch(column) {
				case "u_type":
					disableInput("u_caseseries");
					disableInput("u_caseno");
                                            if (getTableInput("T1","u_feedesc")==""){
                                                u_AutoAssessGPSMTOP(getInput("u_apptype"),getInput("u_type"));
                                            }else{
                                                page.statusbar.showError("Fees in assessment is being added/edited");
                                                selectTab("tab1",2);
                                                return false;
                                            }
					switch (getInput(column)) {
						case "1":
							setInputSelectedText("docseries","Permit",true);
							setCaption("docno","Permit No.");
							setInput("u_caseseries","-1");
							setInput("u_caseno","");
							break;
						case "2":
							setInputSelectedText("docseries","Franchise",true);
							setCaption("docno","Franchise No.");
							setInput("u_caseseries","-1");
							enableInput("u_caseseries");
							enableInput("u_caseno");
							break;
					}
					setAppTypeTaxes();
					break;
				case "u_apptype":
                                    
                                    if (getTableInput("T1","u_feedesc")==""){
                                         u_AutoAssessGPSMTOP(getInput("u_apptype"),getInput("u_type"));
                                    }else{
                                         page.statusbar.showError("Fees in assessment is being added/edited");
                                         selectTab("tab1",2);
                                         return false;
                                    }
                                   /* if(getInput("u_feedesc")==""){
                                        
                                    }else{
                                       
                                    }*/
					setInput("u_franchiseseries",-1,true);
					if (getInput("u_apptype")=="NEW") {
						enableInput("u_franchiseseries");
						disableInput("u_franchiseno");
                                               
						//enableInput("docseries");
						//enableInput("docno");
						//setInput("docseries",-1);
						//setInput("docno","");
						setInput("u_franchiseno","");
						//focusInput("docseries");
					} else {
						disableInput("u_franchiseseries");
						
                                                enableInput("u_franchiseno");
                                                //disableInput("docseries");
						//disableInput("docno");
						//setInput("docseries",-1);
						//setInput("docno","");
						setInput("u_franchiseno","");
						focusInput("u_franchiseno");
					}
//					setAppTypeTaxes();
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSMTOP(Id) {
	switch (Id) {
		case "df_u_franchiseno":
			if (getInputNumeric("u_type")==0 && getPrivate("combinepermitfranchise")=="0") {
				page.statusbar.showError("Please select if Permit or Franchise");
				return false;
			}
			break;
	}	
	return true;
}

function onElementCFLGetParamsGPSMTOP(Id,params) {
	switch (Id) {
		case "df_u_franchiseno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_franchiseno, u_toda, u_plateno from u_mtopapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_type='"+getInput("u_type")+"' and docstatus in ('Approved')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Franchise No.`TODA`Plate No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`20`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_toda":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_mtoptodas")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TODA`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_brgy":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_barangays")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_dbrgy":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_barangays")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSMTOP(table) {
}

function onTableBeforeInsertRowGPSMTOP(table) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSMTOP(table,row) {
	switch (table) {
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeUpdateRowGPSMTOP(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSMTOP(table,row) {
	switch (table) {
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeDeleteRowGPSMTOP(table,row) {
	return true;
}

function onTableDeleteRowGPSMTOP(table,row) {
	switch (table) {
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeSelectRowGPSMTOP(table,row) {
	return true;
}

function onTableSelectRowGPSMTOP(table,row) {
    	var params = new Array();
	switch (table) {
		case "T2":
                        if (elementFocused.substring(0,11)=="df_u_amount") {
				focusTableInput(table,"u_amount",row);
			} else if (elementFocused.substring(0,12)=="df_u_issdate") {
				focusTableInput(table,"u_issdate",row);
			} else if (elementFocused.substring(0,13)=="df_u_payrefno") {
				focusTableInput(table,"u_payrefno",row);
			} else if (elementFocused.substring(0,12)=="df_u_remarks") {
				focusTableInput(table,"u_remarks",row);
			}
			params["focus"]=false;
			break;
	}
	return params;
}

function setAppTypeTaxes() {
	var rc = getTableRowCount("T1");
	var platefeerc = 0;
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("franchisefeecode")) {
				if (getInput("u_apptype")=="NEW" && (getInput("u_type")=="" || getInput("u_type")=="2")) {
					setTableInputAmount("T1","u_amount",getPrivate("franchisefeeamt"),xxx);
				} else if (getInput("u_apptype")=="RENEW" && (getInput("u_type")=="" || getInput("u_type")=="2") && formatDateToDB(getInput("u_appdate"))>= getPrivate("franchisefeerevokedate")) {
					setTableInputAmount("T1","u_amount",getPrivate("franchisefeeamt"),xxx);
				} else {
					setTableInputAmount("T1","u_amount",0,xxx);
				}
				platefeerc=xxx;
			}
		}
	}
	if (platefeerc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("franchisefeecode");
		data["u_feedesc"] = getPrivate("franchisefeedesc");
		data["u_common"] = 5;
		if (getInput("u_apptype")=="NEW" && (getInput("u_type")=="" || getInput("u_type")=="2")) {
			data["u_amount"] = formatNumericAmount(getPrivate("franchisefeeamt"));
		} else if (getInput("u_apptype")=="RENEW" && (getInput("u_type")=="" || getInput("u_type")=="2") && formatDateToDB(getInput("u_appdate"))>= getPrivate("franchisefeerevokedate")) {
			setTableInputAmount("T1","u_amount",getPrivate("franchisefeeamt"),xxx);
		} else {
			data["u_amount"] = formatNumericAmount(0);
		}			
		insertTableRowFromArray("T1",data);
	}
}

function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_amount",i);
		}
	}	
	setInputAmount("u_asstotal",total);
}

function u_forAssessmentGPSMTOP() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_toda",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_brand",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_model",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_plateno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_chassisno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_engineno",null,null,"tab1",0)) return false;
	
	if (getInput("u_lastname")=="" && getInput("u_dlastname")=="") {
		page.statusbar.showError("Operator and/or Driver name must be entered.");
		return false;
	}	
	if (getInput("u_lastname")!=""){
		if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	}
	if (getInput("u_dlastname")!=""){
		if (isInputEmpty("u_dfirstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_dmiddlename",null,null,"tab1",0)) return false;
	}

	setInput("docstatus","Assessing");
	formSubmit('sc');
}

function u_forApprovalGPSMTOP() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_toda",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_brand",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_model",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_plateno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_chassisno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_engineno",null,null,"tab1",0)) return false;
	if (getInput("u_lastname")=="" && getInput("u_dlastname")=="") {
		page.statusbar.showError("Operator and/or Driver name must be entered.");
		return false;
	}	
	if (getInput("u_lastname")!=""){
		if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	}
	if (getInput("u_dlastname")!=""){
		if (isInputEmpty("u_dfirstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_dmiddlename",null,null,"tab1",0)) return false;
	}

	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		selectTab("tab1",2);
		return false;
	}
	setInput("docstatus","Assessed");
	formSubmit('sc');
}

function u_forApproveGPSMTOP() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_toda",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_brand",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_model",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_plateno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_chassisno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_engineno",null,null,"tab1",0)) return false;

	if (getInput("u_lastname")=="" && getInput("u_dlastname")=="") {
		page.statusbar.showError("Operator and/or Driver name must be entered.");
		return false;
	}	
	if (getInput("u_lastname")!=""){
		if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	}
	if (getInput("u_dlastname")!=""){
		if (isInputEmpty("u_dfirstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_dmiddlename",null,null,"tab1",0)) return false;
	}

	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function u_approveGPSMTOP() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_toda",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_brand",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_model",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_plateno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_chassisno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_engineno",null,null,"tab1",0)) return false;

	if (getInput("u_lastname")=="" && getInput("u_dlastname")=="") {
		page.statusbar.showError("Operator and/or Driver name must be entered.");
		return false;
	}	
	if (getInput("u_lastname")!=""){
		if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	}
	if (getInput("u_dlastname")!=""){
		if (isInputEmpty("u_dfirstname",null,null,"tab1",0)) return false;
//		if (isInputEmpty("u_dmiddlename",null,null,"tab1",0)) return false;
	}

	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function u_disapproveGPSMTOP() {
	setInput("docstatus","Disapproved");
	formSubmit('sc');
}

function u_reassessGPSMTOP() {
	//if (isInputEmpty("u_approverremarks",null,null,"tab1",6)) return false;
	setInput("docstatus","Assessing");
	formSubmit('sc');
}
function u_AutoAssessGPSMTOP(apptype,type) {
   
   if(apptype=="NEW" && (type =="" || type ==1 )){
       result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_AMOUNT AS U_AMOUNT FROM u_mtopfees A where u_new = 1 AND A.U_AMOUNT <>0");	
   }else if(apptype=="RENEW" && (type =="" || type ==1 )){
       result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_RENEWAMOUNT AS U_AMOUNT FROM u_mtopfees A where u_renew = 1 AND A.U_RENEWAMOUNT <>0");	
   }else if(apptype=="UPDATE" && (type =="" || type ==1 )){
       result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_UPDATEAMOUNT AS U_AMOUNT FROM u_mtopfees A where u_update = 1 AND A.U_UPDATEAMOUNT <>0");	
   }else if(apptype=="NEW" && type ==2 ){
       result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_FRANNEWAMOUNT AS U_AMOUNT FROM u_mtopfees A where u_new = 1 AND A.U_FRANNEWAMOUNT <>0");	
   }else if(apptype=="RENEW" && type ==2 ){
       result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_FRANRENEWAMOUNT AS U_AMOUNT FROM u_mtopfees A where u_renew = 1 AND A.U_FRANRENEWAMOUNT <>0");	
   }else if(apptype=="UPDATE" && type ==2 ){
       result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_FRANUPDATEAMOUNT AS U_AMOUNT FROM u_mtopfees A where u_update = 1 AND A.U_FRANUPDATEAMOUNT <>0");	
   }
   
        if (result.getAttribute("result")!= "-1") {
                if (parseInt(result.getAttribute("result"))>0) {

                        clearTable("T1",true);
                        //var penaltyamount=0;
                        var data = new Array();

                        for (var iii=0; iii<result.childNodes.length; iii++) {
                                data["u_feecode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
                                data["u_feedesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
                                data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                insertTableRowFromArray("T1",data);
                        }
                        computeTotalAssessment();

                } else {

                        page.statusbar.showError("Invalid Automatic Fees. Please check the set up for mtop fees");	
                        return false;
                }
        } else {

                page.statusbar.showError("Error retrieving Fees. Try Again, if problem persists, check the connection.");	
                return false;
        } 

    }

	


