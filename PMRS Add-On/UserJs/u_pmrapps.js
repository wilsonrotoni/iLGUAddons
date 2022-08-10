// page events
page.events.add.load('onPageLoadGPSPMRS');
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
//page.elements.events.add.click('onElementClickGPSPMRS');
//page.elements.events.add.cfl('onElementCFLGPSPMRS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPMRS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSPMRS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPMRS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPMRS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPMRS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPMRS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPMRS');
page.tables.events.add.delete('onTableDeleteRowGPSPMRS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPMRS');
//page.tables.events.add.select('onTableSelectRowGPSPMRS');

function onPageLoadGPSPMRS() {
	if (getVar("formSubmitAction")=="a") {
		selectTab("tab1",1);
		if (getInput("u_apptype")=="RENEW" && getInput("u_publicmarket")!="" && getInput("u_stallno")!="" && getInput("u_section")=="") {
			setInput("u_stallno",getInput("u_stallno"),true);
		}
	}
}

function onPageResizeGPSPMRS(width,height) {
}

function onPageSubmitGPSPMRS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_publicmarket",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_stallno",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
		if (getInput("u_civilstatus")=="Married") {
			if (isInputEmpty("u_slastname",null,null,"tab1",0)) return false;
			if (isInputEmpty("u_sfirstname",null,null,"tab1",0)) return false;
			//if (isInputEmpty("u_smiddlename",null,null,"tab1",0)) return false;
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
				case "u_appdate":
					if (getInput(column)!="") {
						setInput("u_year",getInput("u_appdate").substr(6,4));
					} else {
						setInput("u_year",0);
					}
					break;
				case "u_bpno":
				case "u_tradename":
					if (getInput(column)!="") {
						if (column=="u_bpno") var result = page.executeFormattedQuery("select docno, u_businessname, u_lastname, u_firstname, u_middlename, u_street, u_brgy, u_city, u_province, u_telno, u_email from u_bplapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_year='"+getInput("u_year")+"' and docno='"+getInput(column)+"'");	
						else var result = page.executeFormattedQuery("select docno, u_businessname, u_lastname, u_firstname, u_middlename, u_street, u_brgy, u_city, u_province, u_telno, u_email from u_bplapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'  and u_year='"+getInput("u_year")+"' and u_tradename like '"+getInput(column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bpno",result.childNodes.item(0).getAttribute("docno"));
								setInput("u_tradename",result.childNodes.item(0).getAttribute("u_businessname"));
								setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								setInput("u_street",result.childNodes.item(0).getAttribute("u_street"));
								setInput("u_brgy",result.childNodes.item(0).getAttribute("u_brgy"));
								setInput("u_city",result.childNodes.item(0).getAttribute("u_city"));
								setInput("u_province",result.childNodes.item(0).getAttribute("u_province"));
								setInput("u_telno",result.childNodes.item(0).getAttribute("u_telno"));
								setInput("u_email",result.childNodes.item(0).getAttribute("u_email"));
							} else {
								setInput("u_bpno","");
								setInput("u_tradename","");
								setInput("u_lastname","");
								setInput("u_firstname","");
								setInput("u_middlename","");
								setInput("u_street","");
								setInput("u_brgy","");
								setInput("u_city","");
								setInput("u_province","");
								setInput("u_telno","");
								setInput("u_email","");
								page.statusbar.showError("Invalid Business Permit.");	
								return false;
							}
						} else {
							setInput("u_bpno","");
							setInput("u_tradename","");
							setInput("u_lastname","");
							setInput("u_firstname","");
							setInput("u_middlename","");
							setInput("u_street","");
							setInput("u_brgy","");
							setInput("u_city","");
							setInput("u_province","");
							setInput("u_telno","");
							setInput("u_email","");
							page.statusbar.showError("Error retrieving business permit record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bpno","");
						setInput("u_tradename","");
						setInput("u_lastname","");		
						setInput("u_firstname","");
						setInput("u_middlename","");
						setInput("u_street","");
						setInput("u_brgy","");
						setInput("u_city","");
						setInput("u_province","");
						setInput("u_telno","");
						setInput("u_email","");
					}						
					break;
				case "u_stallno":
					if (getInput("u_publicmarket")!="" && getInput("u_stallno")!="") {
						if (getInput("u_apptype")=="NEW") {
							var result = page.executeFormattedQuery("select b.u_section, b.u_amount from u_pmrpms a inner join u_pmrpmstalls b on b.code=a.code where a.code='"+getInput("u_publicmarket")+"' and b.u_stallno='"+getInput("u_stallno")+"'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_section",result.childNodes.item(0).getAttribute("u_section"));
									setInputAmount("u_rentalfee",result.childNodes.item(0).getAttribute("u_amount"));
									setRentalFees();
								} else {
									setInput("u_section","");
									setInputAmount("u_rentalfee",0);
									page.statusbar.showError("Invalid Stall No.");	
									setRentalFees();
									return false;
								}
							} else {
								setInput("u_section","");
								setInputAmount("u_rentalfee",0);
								setRentalFees();
								page.statusbar.showError("Error retrieving stall record. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							var result = page.executeFormattedQuery("select b.u_section, b.u_amount, c.u_bpno, c.u_tradename, c.u_businessnature, c.u_lastname, c.u_firstname, c.u_middlename, c.u_age, c.u_civilstatus, c.u_brgy, c.u_street, c.u_city, c.u_province, c.u_telno, c.u_email, c.u_slastname, c.u_sfirstname, c.u_smiddlename, c.u_expdate from u_pmrpms a inner join u_pmrpmstalls b on b.code=a.code inner join u_pmrapps c on c.company='"+getGlobal("company")+"' and c.branch='"+getGlobal("branch")+"' and c.u_publicmarket=a.code and c.u_stallno=b.u_stallno where a.code='"+getInput("u_publicmarket")+"' and b.u_stallno='"+getInput("u_stallno")+"' order by c.u_expdate desc");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_section",result.childNodes.item(0).getAttribute("u_section"));
									setInputAmount("u_rentalfee",result.childNodes.item(0).getAttribute("u_amount"));
									setInput("u_bpno",result.childNodes.item(0).getAttribute("u_bpno"));
									setInput("u_tradename",result.childNodes.item(0).getAttribute("u_tradename"));
									setInput("u_businessnature",result.childNodes.item(0).getAttribute("u_businessnature"));
									setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
									setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
									setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
									setInput("u_age",result.childNodes.item(0).getAttribute("u_age"));
									setInput("u_civilstatus",result.childNodes.item(0).getAttribute("u_civilstatus"));
									setInput("u_brgy",result.childNodes.item(0).getAttribute("u_brgy"));
									setInput("u_street",result.childNodes.item(0).getAttribute("u_street"));
									setInput("u_city",result.childNodes.item(0).getAttribute("u_city"));
									setInput("u_province",result.childNodes.item(0).getAttribute("u_province"));
									setInput("u_telno",result.childNodes.item(0).getAttribute("u_telno"));
									setInput("u_email",result.childNodes.item(0).getAttribute("u_email"));
									setInput("u_slastname",result.childNodes.item(0).getAttribute("u_slastname"));
									setInput("u_sfirstname",result.childNodes.item(0).getAttribute("u_sfirstname"));
									setInput("u_smiddlename",result.childNodes.item(0).getAttribute("u_smiddlename"));
									setInput("u_prevexpdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_expdate")));
									setRentalFees();
								} else {
									setInput("u_section","");
									setInputAmount("u_rentalfee",0);
									setInput("u_year",parseInt(formatDateToDB(getInput("u_appdate")).substr(0,4)));
									page.statusbar.showError("Invalid Stall No.");	
									setRentalFees();
									return false;
								}
							} else {
								setInput("u_section","");
								setInputAmount("u_rentalfee",0);
								setRentalFees();
								page.statusbar.showError("Error retrieving stall record. Try Again, if problem persists, check the connection.");	
								return false;
							}
						}							
					} else {
						setInput("u_section","");
						setInputAmount("u_rentalfee",0);
						setRentalFees();
					}						
					break;
				case "u_rentalfee":
					setRentalFees();
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
	switch (table) {
		default:
			switch(column) {
				case "u_publicmarket":
					if (getInput("u_publicmarket")!="" && getInput("u_stallno")!="") {
						var result = page.executeFormattedQuery("select b.u_amount from u_pmrpms a inner join u_pmrpmstalls b on b.code=a.code where where a.code='"+getInput("u_publicmarket")+"' and b.u_stallno='"+getInput("u_stallno")+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_rentalfee",result.childNodes.item(0).getAttribute("u_fee"));
								setRentalFees();
							} else {
								setInputAmount("u_rentalfee",0);
								page.statusbar.showError("Invalid Stall No.");	
								setRentalFees();
								return false;
							}
						} else {
							setInputAmount("u_rentalfee",0);
							setRentalFees();
							page.statusbar.showError("Error retrieving stall record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						
					} else {
						setInputAmount("u_rentalfee",0);
						setRentalFees();
					}						
					break;
				case "u_rights":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select a.u_fee from u_pmrrights a where a.code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_rightsfee",result.childNodes.item(0).getAttribute("u_fee"));
								setRightsFee();
							} else {
								setInputAmount("u_rightsfee",0);
								page.statusbar.showError("Invalid Rights");	
								setRightsFee();
								return false;
							}
						} else {
							setInputAmount("u_rightsfee",0);
							setRightsFee();
							page.statusbar.showError("Error retrieving rights record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						
					} else {
						setInputAmount("u_rightsfee",0);
						setRightsFee();
					}						
					break;
			}
			break;
	}		
	return true;
}

function onElementClickGPSPMRS(element,column,table,row) {
	return true;
}

function onElementCFLGPSPMRS(element) {
	return true;
}

function onElementCFLGetParamsGPSPMRS(Id,params) {
	switch (Id) {
		case "df_u_bpno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_businessname, u_lastname, u_firstname, u_middlename from u_bplapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_year='"+getInput("u_year")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Permit No.`Business Name/Franchise`Last Name`First Name`Middle Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`35`15`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_tradename":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_businessname,docno, u_lastname, u_firstname, u_middlename from u_bplapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_year='"+getInput("u_year")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Business Name`Permit No.`Last Name`First Name`Middle Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`15`15`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_stallno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_stallno, u_section from u_pmrpmstalls where code='"+getInput("u_publicmarket")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Stall No.`Section")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`25")); 			
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

function onTableResetRowGPSPMRS(table) {
}

function onTableBeforeInsertRowGPSPMRS(table) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSPMRS(table,row) {
	switch (table) {
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeUpdateRowGPSPMRS(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSPMRS(table,row) {
	switch (table) {
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeDeleteRowGPSPMRS(table,row) {
	return true;
}

function onTableDeleteRowGPSPMRS(table,row) {
	switch (table) {
		case "T1": computeTotalAssessment(); break;
	}
}

function onTableBeforeSelectRowGPSPMRS(table,row) {
	return true;
}

function onTableSelectRowGPSPMRS(table,row) {
}

function setRightsFee() {
	var rentalfeecoderc=0, rc = getTableRowCount("T1");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("rightsfeecode")) {
				setTableInputAmount("T1","u_amount",getInputNumeric("u_rightsfee"),xxx);
				rentalfeecoderc=xxx;
			}
		}
	}
	if (rentalfeecoderc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("rightsfeecode");
		data["u_feedesc"] = getPrivate("rightsfeedesc");
		data["u_common"] = 1;
		data["u_amount"] = formatNumericAmount(getInputNumeric("u_rightsfee"));
		insertTableRowFromArray("T1",data);
	}
	computeTotalAssessment();
}

function setRentalFees() {
	var rentalfeecoderc=0, rc = getTableRowCount("T1");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInput("T1","u_feecode",xxx)==getPrivate("rentalfeecode")) {
				setTableInputAmount("T1","u_amount",getInputNumeric("u_rentalfee"),xxx);
				rentalfeecoderc=xxx;
			}
		}
	}
	if (rentalfeecoderc==0) {
		var data = new Array();
		data["u_feecode"] = getPrivate("rentalfeecode");
		data["u_feedesc"] = getPrivate("rentalfeedesc");
		data["u_common"] = 1;
		data["u_amount"] = formatNumericAmount(getInputNumeric("u_rentalfee"));
		insertTableRowFromArray("T1",data);
	}
	computeTotalAssessment();
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

function u_forAssessmentGPSPMRS() {
	if (isInputEmpty("u_publicmarket",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_stallno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//	if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	setInput("docstatus","Assessing");
	formSubmit('sc');
}

function u_forApprovalGPSPMRS() {
	if (isInputEmpty("u_publicmarket",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_stallno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//	if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		selectTab("tab1",2);
		return false;
	}
	setInput("docstatus","Assessed");
	formSubmit('sc');
}

function u_forApproveGPSPMRS() {
	if (isInputEmpty("u_publicmarket",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_stallno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//	if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function u_approveGPSPMRS() {
	if (isInputEmpty("u_publicmarket",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_stallno",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
//	if (isInputEmpty("u_middlename",null,null,"tab1",0)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function u_disapproveGPSPMRS() {
	setInput("docstatus","Disapproved");
	formSubmit('');
}

function u_reassessGPSPMR() {
	
	setInput("docstatus","Assessing");
	formSubmit('sc');
}

