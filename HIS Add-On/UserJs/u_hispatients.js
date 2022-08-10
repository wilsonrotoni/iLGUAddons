// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
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
//page.tables.events.add.select('onTableSelectRowGPSHIS');

page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		try {
			setInput("u_lastname",window.opener.getInput("u_lastname"),true);
			setInput("u_firstname",window.opener.getInput("u_firstname"),true);
			setInput("u_middlename",window.opener.getInput("u_middlename"),true);
			setInput("u_birthdate",window.opener.getInput("u_birthdate"),true);
			
			var result2 = page.executeFormattedSearch("select code from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and name='"+getInput("name")+"'");
			if (result2!="") {
				alert("Patient Name already exists!");	
				window.opener.formSearchNow();
				setKey("keys",result2);
				formEdit();
				return true;
			}			
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_lastname")) return false;
		if (isInputEmpty("u_firstname")) return false;
		if (getInput("u_middlename")=="") {
			if (window.confirm("Middle Name was not entered. Are you sure patient has no middle name?")==false) {
				focusInput("u_middlename");
				return false;
			}
		}
		if (isInputEmpty("u_gender")) return false;
		if (isInputEmpty("u_birthdate")) return false;
		if (isInputChecked("u_newbornstat")) {
			if (isInputEmpty("u_birthtime")) return false;			
		}
		if (getTableInput("T4","u_icdcode")!="") {
			page.statusbar.showError("An ICD is being added/edited.");
			return false;
		}

		if (getTableInput("T2","u_inscode")!="") {
			page.statusbar.showError("An insurance benefit is being added/edited.");
			return false;
		}
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			window.opener.setInput("u_lastname",getInput("u_lastname"));
			window.opener.setInput("u_firstname",getInput("u_firstname"));
			window.opener.setInput("u_middlename",getInput("u_middlename"));
			window.opener.setInput("u_birthdate","");
			window.opener.formSearchNow();
		} catch (theError) {
		}
			
	}
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			params["params"] += "&cflsortby=name";
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
	var name="",address="";
	switch (table) {
		case "T2":
			switch(column) {
				case "u_memberid":
					if (getTableInput(table,"u_hmo")!=6) return true;
					
					if (getTableInput(table,"u_memberid")!="") {
						result = page.executeFormattedQuery("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText(table,"u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and a.custno='"+getTableInput(table,"u_memberid")+"' and isvalid=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_memberid",result.childNodes.item(0).getAttribute("custno"));
								setTableInput(table,"u_membername",result.childNodes.item(0).getAttribute("custname"));
							} else {
								setTableInput(table,"u_memberid","");
								setTableInput(table,"u_membername","");
								page.statusbar.showError("Invalid Customer No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_memberid","");
							setTableInput(table,"u_membername","");
							page.statusbar.showError("Error retrieving Customer No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_memberid","");
						setTableInput(table,"u_membername","");
					}
					break;
				case "u_memberlastname":
				case "u_memberfirstname":
				case "u_memberextname":
				case "u_membermiddlename":
					setTableInput(table,column,utils.trim(getTableInput(table,column)));
					name = getTableInput(table,"u_memberlastname");
					if (getTableInput(table,"u_memberfirstname")!="") name += ", " + getTableInput(table,"u_memberfirstname");
					if (getTableInput(table,"u_membermiddlename")!="") name += " " + getTableInput(table,"u_membermiddlename");
					if (getTableInput(table,"u_memberextname")!="") name += " " + getTableInput(table,"u_memberextname");
					setTableInput(table,"u_membername",name);
					break;
			}
			break;
		case "T4":
			switch(column) {
				case "u_icdcode":
				//case "u_icddesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_icdcode") {
							result = page.executeFormattedQuery("select code, name, u_casetype from u_hisicds where code='"+getTableInput(table,"u_icdcode")+"'");	 
						} else {
							result = page.executeFormattedQuery("select code, name, u_casetype from u_hisicds where name='"+getTableInput(table,"u_icddesc")+"'");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_icdcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_icddesc",result.childNodes.item(0).getAttribute("name"));
								//setTableInput(table,"u_casetype",result.childNodes.item(0).getAttribute("u_casetype"));
							} else {
								setTableInput(table,"u_icdcode","");
								setTableInput(table,"u_icddesc","");
								//setTableInput(table,"u_casetype","");
								if (column=="u_icdcode") {
									page.statusbar.showError("Invalid ICD Code.");	
								} else {
									page.statusbar.showError("Invalid ICD Description.");	
								}
								return false;
							}
						} else {
							setTableInput(table,"u_icdcode","");
							setTableInput(table,"u_icddesc","");
							//setTableInput(table,"u_casetype","");
							if (column=="u_icdcode") {
								page.statusbar.showError("Error retrieving ICD Code. Try Again, if problem persists, check the connection.");	
							} else {
								page.statusbar.showError("Error retrieving ICD Description. Try Again, if problem persists, check the connection.");	
							}
							return false;
						}
					} else {
						setTableInput(table,"u_icdcode","");
						setTableInput(table,"u_icddesc","");
						//setTableInput(table,"u_casetype","");
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_lastname":
				case "u_firstname":
				case "u_extname":
				case "u_middlename":
					setInput(column,utils.trim(getInput(column)));
					name = getInput("u_lastname");
					if (getInput("u_firstname")!="") name += ", " + getInput("u_firstname");
					if (getInput("u_middlename")!="") name += " " + getInput("u_middlename");
					if (getInput("u_extname")!="") name += " " + getInput("u_extname");
					setInput("name",name);
					break;
				case "u_religion":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code from u_hisreligions where code like '"+getInput(column)+"%'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput(column,result.childNodes.item(0).getAttribute("code"));
							} else {
								setInput(column,"");
								page.statusbar.showError("Invalid Religion.");	
								return false;
							}
						} else {
							setInput(column,"");
							page.statusbar.showError("Error retrieving Religion. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					}
					break;
				case "u_nationality":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code from u_hisnationalities where code like '"+getInput(column)+"%'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput(column,result.childNodes.item(0).getAttribute("code"));
							} else {
								setInput(column,"");
								page.statusbar.showError("Invalid Nationality.");	
								return false;
							}
						} else {
							setInput(column,"");
							page.statusbar.showError("Error retrieving Nationality. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					}
					break;
				case "u_street":
				case "u_fatherstreet":
				case "u_motherstreet":
				case "u_spousestreet":
				case "u_employerstreet":
				case "u_contactstreet":
				case "u_responsiblestreet":
				case "u_zip":
				case "u_fatherzip":
				case "u_motherzip":
				case "u_spousezip":
				case "u_employerzip":
				case "u_contactzip":
				case "u_responsiblezip":
					setAddressGPSHIS(column);
					break;
				case "u_barangay":
				case "u_fatherbarangay":
				case "u_motherbarangay":
				case "u_spousebarangay":
				case "u_employerbarangay":
				case "u_contactbarangay":
				case "u_responsiblebarangay":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code from u_hisaddrbrgys where code like '"+getInput(column)+"%'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput(column,result.childNodes.item(0).getAttribute("code"));
							} else {
								setInput(column,"");
								page.statusbar.showError("Invalid Barangay.");	
								return false;
							}
						} else {
							setInput(column,"");
							page.statusbar.showError("Error retrieving Barangay. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					}
					setAddressGPSHIS(column);
					break;
				case "u_city":
				case "u_fathercity":
				case "u_mothercity":
				case "u_spousecity":
				case "u_employercity":
				case "u_contactcity":
				case "u_responsiblecity":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code from u_hisaddrtowncities where code like '"+getInput(column)+"%'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput(column,result.childNodes.item(0).getAttribute("code"));
							} else {
								setInput(column,"");
								page.statusbar.showError("Invalid Town/City.");	
								return false;
							}
						} else {
							setInput(column,"");
							page.statusbar.showError("Error retrieving Town/City. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					}
					setAddressGPSHIS(column);
					break;
				case "u_province":
				case "u_fatherprovince":
				case "u_motherprovince":
				case "u_spouseprovince":
				case "u_employerprovince":
				case "u_contactprovince":
				case "u_responsibleprovince":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select provincename from provinces where provincename like '"+getInput(column)+"%'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput(column,result.childNodes.item(0).getAttribute("provincename"));
							} else {
								setInput(column,"");
								page.statusbar.showError("Invalid State/Province.");	
								return false;
							}
						} else {
							setInput(column,"");
							page.statusbar.showError("Error retrieving State/Province. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					}
					setAddressGPSHIS(column);
					break;
			}
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
		case "T2":
			switch (column) {
				case "u_inscode":
					disableTableInput(table,"u_memberid");
					disableTableInput(table,"u_membername");
					disableTableInput(table,"u_membertype");
					disableTableInput(table,"u_memberlastname");
					disableTableInput(table,"u_memberfirstname");
					disableTableInput(table,"u_membermiddlename");
					disableTableInput(table,"u_memberextname");
					disableTableInput(table,"u_memberbirthdate");
					disableTableInput(table,"u_relationtomember");
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select u_hmo, u_scdisc from u_hishealthins where code='"+getTableInput(table,"u_inscode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
								setTableInput(table,"u_scdisc",result.childNodes.item(0).getAttribute("u_scdisc"));
								switch (getTableInput(table,"u_hmo")) {
									case "0":
										enableTableInput(table,"u_memberid");
										//enableTableInput(table,"u_membername");
										enableTableInput(table,"u_membertype");
										enableTableInput(table,"u_memberlastname");
										enableTableInput(table,"u_memberfirstname");
										enableTableInput(table,"u_membermiddlename");
										enableTableInput(table,"u_memberextname");
										enableTableInput(table,"u_memberbirthdate");
										enableTableInput(table,"u_relationtomember");
										focusTableInput(table,"u_memberid");
										//enableInput("u_caserate");
										break;
									case "1":
									case "4":
										enableTableInput(table,"u_memberid");
										enableTableInput(table,"u_membername");
										focusTableInput(table,"u_memberid");
										break;
									case "6":
										enableTableInput(table,"u_memberid");
										focusTableInput(table,"u_memberid");
										break;
								}
								
							} else {
								setTableInput(table,"u_hmo","-1");
								setTableInput(table,"u_scdisc","0");
								page.statusbar.showError("Invalid Health Benefits Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_hmo","-1");
							setTableInput(table,"u_scdisc","0");
							page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_hmo","-1");
						setTableInput(table,"u_scdisc","0");
					}
					break;
				case "u_relationtomember":
					if (getTableInput("T2","u_relationtomember")=="MEMBER") {
						setTableInput(table,"u_memberlastname",getInput("u_lastname"));
						setTableInput(table,"u_memberfirstname",getInput("u_firstname"));	
						setTableInput(table,"u_membermiddlename",getInput("u_middlename"));
						setTableInput(table,"u_memberextname",getInput("u_extname"));
						setTableInput(table,"u_membername",getInput("name"));
						setTableInput(table,"u_memberbirthdate",getInput("u_birthdate"));
					} else {
						setTableInput(table,"u_memberlastname","");
						setTableInput(table,"u_memberfirstname","");
						setTableInput(table,"u_membermiddlename","");
						setTableInput(table,"u_memberextname","");
						setTableInput(table,"u_membername","");
						setTableInput(table,"u_memberbirthdate","");
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_series":
					setDocNo(true,"u_series","code");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_newbornstat":
					setInput("u_birthtime","");
					if (isInputChecked("u_newbornstat")) enableInput("u_birthtime");
					else disableInput("u_birthtime");
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	if (Id=="df_u_memberidT2" && getTableInput("T2","u_hmo")!=6) {
		page.statusbar.showWarning("Please enter the member id manually.");
		return false;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_religion":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisreligions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisreligions"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_nationality":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisnationalities")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisnationalities"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_barangay":
		case "df_u_fatherbarangay":
		case "df_u_motherbarangay":
		case "df_u_spousebarangay":
		case "df_u_employerbarangay":
		case "df_u_contactbarangay":
		case "df_u_responsiblebarangay":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisaddrbrgys")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisaddrbrgys"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_city":
		case "df_u_fathercity":
		case "df_u_mothercity":
		case "df_u_spousecity":
		case "df_u_employercity":
		case "df_u_contactcity":
		case "df_u_responsiblecity":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisaddrtowncities")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisaddrtowncities"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_province":
		case "df_u_fatherprovince":
		case "df_u_motherprovince":
		case "df_u_spouseprovince":
		case "df_u_employerprovince":
		case "df_u_contactprovince":
		case "df_u_responsibleprovince":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select provincename from provinces")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			break;
		case "df_u_memberidT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText("T2","u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
		case "df_u_icdcodeT4":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisicds where u_level>2")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ICD Code`ICD Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_icddescT4":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisicds where u_level>2")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ICD Description`ICD Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch(table) {
		case "T2":
			disableTableInput(table,"u_memberid");
			disableTableInput(table,"u_membername");
			disableTableInput(table,"u_membertype");
			disableTableInput(table,"u_memberlastname");
			disableTableInput(table,"u_memberfirstname");
			disableTableInput(table,"u_membermiddlename");
			disableTableInput(table,"u_memberextname");
			disableTableInput(table,"u_memberbirthdate");
			disableTableInput(table,"u_relationtomember");
			focusTableInput(table,"u_inscode");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T2":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
				if (isTableInputEmpty(table,"u_memberbirthdate")) return false;
				if (isTableInputEmpty(table,"u_relationtomember")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="3" && getTableInput(table,"u_hmo")!="5") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}
			break;
		case "T4":
			if (!isTableInputUnique(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T2": 
			setSeniorCitizenGPSHIS(table); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
				if (isTableInputEmpty(table,"u_memberbirthdate")) return false;
				if (isTableInputEmpty(table,"u_relationtomember")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="3" && getTableInput(table,"u_hmo")!="5") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}
			break;
		case "T4":
			if (!isTableInputUnique(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setSeniorCitizenGPSHIS(table); 
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T2": setSeniorCitizenGPSHIS(table); break;
	}
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch(table) {
		case "T2":
			switch (getTableInput(table,"u_hmo")) {
				case "0":
					enableTableInput(table,"u_memberid");
					//enableTableInput(table,"u_membername");
					enableTableInput(table,"u_membertype");
					enableTableInput(table,"u_memberlastname");
					enableTableInput(table,"u_memberfirstname");
					enableTableInput(table,"u_membermiddlename");
					enableTableInput(table,"u_memberextname");
					enableTableInput(table,"u_memberbirthdate");
					enableTableInput(table,"u_relationtomember");
					focusTableInput(table,"u_memberid");
					//enableInput("u_caserate");
					break;
				case "1":
				case "4":
					enableTableInput(table,"u_memberid");
					enableTableInput(table,"u_membername");
					focusTableInput(table,"u_memberid");
					break;
				case "6":
					enableTableInput(table,"u_memberid");
					focusTableInput(table,"u_memberid");
					break;
			}
			break;
	}
}


function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function setAddressGPSHIS(column) {
	var address="";
	switch (column) {
		case "u_street":
		case "u_barangay":
		case "u_city":
		case "u_province":
		case "u_country":
		case "u_zip":
			address = getInput("u_street");
			if (getInput("u_barangay")!="") address += ", " + getInput("u_barangay");
			if (getInput("u_city")!="") address += ", " + getInput("u_city");
			if (getInput("u_province")!="") address += ", " + getInput("u_province");
			if (getInput("u_country")!="") address += ", " + getInput("u_country");
			if (getInput("u_zip")!="") address += ", " + getInput("u_zip");
			setInput("u_address",address);
			break;
		case "u_fatherstreet":
		case "u_fatherbarangay":
		case "u_fathercity":
		case "u_fatherprovince":
		case "u_fathercountry":
		case "u_fatherzip":
			address = getInput("u_fatherstreet");
			if (getInput("u_fatherbarangay")!="") address += ", " + getInput("u_fatherbarangay");
			if (getInput("u_fathercity")!="") address += ", " + getInput("u_fathercity");
			if (getInput("u_fatherprovince")!="") address += ", " + getInput("u_fatherprovince");
			if (getInput("u_fathercountry")!="") address += ", " + getInput("u_fathercountry");
			if (getInput("u_fatherzip")!="") address += ", " + getInput("u_fatherzip");
			setInput("u_fatheraddress",address);
			break;
		case "u_motherstreet":
		case "u_motherbarangay":
		case "u_mothercity":
		case "u_motherprovince":
		case "u_mothercountry":
		case "u_motherzip":
			address = getInput("u_motherstreet");
			if (getInput("u_motherbarangay")!="") address += ", " + getInput("u_motherbarangay");
			if (getInput("u_mothercity")!="") address += ", " + getInput("u_mothercity");
			if (getInput("u_motherprovince")!="") address += ", " + getInput("u_motherprovince");
			if (getInput("u_mothercountry")!="") address += ", " + getInput("u_mothercountry");
			if (getInput("u_motherzip")!="") address += ", " + getInput("u_motherzip");
			setInput("u_motheraddress",address);
			break;
		case "u_spousestreet":
		case "u_spousebarangay":
		case "u_spousecity":
		case "u_spouseprovince":
		case "u_spousecountry":
		case "u_spousezip":
			address = getInput("u_spousestreet");
			if (getInput("u_spousebarangay")!="") address += ", " + getInput("u_spousebarangay");
			if (getInput("u_spousecity")!="") address += ", " + getInput("u_spousecity");
			if (getInput("u_spouseprovince")!="") address += ", " + getInput("u_spouseprovince");
			if (getInput("u_spousecountry")!="") address += ", " + getInput("u_spousecountry");
			if (getInput("u_spousezip")!="") address += ", " + getInput("u_spousezip");
			setInput("u_spouseaddress",address);
			break;
		case "u_employerstreet":
		case "u_employerbarangay":
		case "u_employercity":
		case "u_employerprovince":
		case "u_employercountry":
		case "u_employerzip":
			address = getInput("u_employerstreet");
			if (getInput("u_employerbarangay")!="") address += ", " + getInput("u_employerbarangay");
			if (getInput("u_employercity")!="") address += ", " + getInput("u_employercity");
			if (getInput("u_employerprovince")!="") address += ", " + getInput("u_employerprovince");
			if (getInput("u_employercountry")!="") address += ", " + getInput("u_employercountry");
			if (getInput("u_employerzip")!="") address += ", " + getInput("u_employerzip");
			setInput("u_employeraddress",address);
			break;
		case "u_contactstreet":
		case "u_contactbarangay":
		case "u_contactcity":
		case "u_contactprovince":
		case "u_contactcountry":
		case "u_contactzip":
			address = getInput("u_contactstreet");
			if (getInput("u_contactbarangay")!="") address += ", " + getInput("u_contactbarangay");
			if (getInput("u_contactcity")!="") address += ", " + getInput("u_contactcity");
			if (getInput("u_contactprovince")!="") address += ", " + getInput("u_contactprovince");
			if (getInput("u_contactcountry")!="") address += ", " + getInput("u_contactcountry");
			if (getInput("u_contactzip")!="") address += ", " + getInput("u_contactzip");
			setInput("u_contactaddress",address);
			break;
		case "u_responsiblestreet":
		case "u_responsiblebarangay":
		case "u_responsiblecity":
		case "u_responsibleprovince":
		case "u_responsiblecountry":
		case "u_responsiblezip":
			address = getInput("u_responsiblestreet");
			if (getInput("u_responsiblebarangay")!="") address += ", " + getInput("u_responsiblebarangay");
			if (getInput("u_responsiblecity")!="") address += ", " + getInput("u_responsiblecity");
			if (getInput("u_responsibleprovince")!="") address += ", " + getInput("u_responsibleprovince");
			if (getInput("u_responsiblecountry")!="") address += ", " + getInput("u_responsiblecountry");
			if (getInput("u_responsiblezip")!="") address += ", " + getInput("u_responsiblezip");
			setInput("u_responsibleaddress",address);
			break;
	}
}

function setFatherAddressGPSHIS() {
	setInput("u_fatheraddress",getInput("u_address"));
	setInput("u_fatherstreet",getInput("u_street"));
	setInput("u_fatherbarangay",getInput("u_barangay"));
	setInput("u_fathercity",getInput("u_city"));
	setInput("u_fatherprovince",getInput("u_province"));
	setInput("u_fathercountry",getInput("u_country"));
	setInput("u_fatherzip",getInput("u_zip"));
}

function setMotherAddressGPSHIS() {
	setInput("u_motheraddress",getInput("u_address"));
	setInput("u_motherstreet",getInput("u_street"));
	setInput("u_motherbarangay",getInput("u_barangay"));
	setInput("u_mothercity",getInput("u_city"));
	setInput("u_motherprovince",getInput("u_province"));
	setInput("u_mothercountry",getInput("u_country"));
	setInput("u_motherzip",getInput("u_zip"));
}

function setSpouseAddressGPSHIS() {
	setInput("u_spouseaddress",getInput("u_address"));
	setInput("u_spousestreet",getInput("u_street"));
	setInput("u_spousebarangay",getInput("u_barangay"));
	setInput("u_spousecity",getInput("u_city"));
	setInput("u_spouseprovince",getInput("u_province"));
	setInput("u_spousecountry",getInput("u_country"));
	setInput("u_spousezip",getInput("u_zip"));
}

function setContactAddressGPSHIS() {
	setInput("u_contactaddress",getInput("u_address"));
	setInput("u_contactstreet",getInput("u_street"));
	setInput("u_contactbarangay",getInput("u_barangay"));
	setInput("u_contactcity",getInput("u_city"));
	setInput("u_contactprovince",getInput("u_province"));
	setInput("u_contactcountry",getInput("u_country"));
	setInput("u_contactzip",getInput("u_zip"));
}

function setResponsibleAddressGPSHIS() {
	setInput("u_responsibleaddress",getInput("u_address"));
	setInput("u_responsiblestreet",getInput("u_street"));
	setInput("u_responsiblebarangay",getInput("u_barangay"));
	setInput("u_responsiblecity",getInput("u_city"));
	setInput("u_responsibleprovince",getInput("u_province"));
	setInput("u_responsiblecountry",getInput("u_country"));
	setInput("u_responsiblezip",getInput("u_zip"));
}

function takePhotoGPSHIS(targetObjectId,targetOptions) {

	if (targetOptions==null) targetOptions = "view";
	OpenPopup(670,280,'./udp.php?objectcode=u_capturephoto' + '' + '&targetId=' + targetObjectId + '&targetOptions=' + targetOptions,targetObjectId);
}

function setPhotoGPSHIS(p_value) {
	setElementValueById("pf_photodata",p_value);
	document.getElementById('PhotoImg').src = p_value;
}


function uploadPhotoGPSHIS() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshPhotoGPSHIS()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,JPG,JPEG,GIF,PNG";
	iframeFileUpload.document.getElementById("basename").value = "photo";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Patients/" + getInput("code") + "/";
	showPopupFrame('popupFrameFileUpload');
}

function refreshPhotoGPSHIS() {
	var src = document.images['PhotoImg'].src;
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function OpenLnkBtnExamDocNo(targetId) {
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
}

function OpenLnkBtnu_hispatientregs(targetId) {
	var row = targetId.indexOf("T1r"),reftype="";
	/*if (getTableInput("T3",'u_status',targetId.substring(row+3,targetId.length))!="C") {
		page.statusbar.showWarning("Yoou can only view the laboratory test result when status is Closed already.");	
		return false;	
	}
	*/
	if(getTableInput("T1",'u_reftype',targetId.substring(row+3,targetId.length))=="IP") {
		OpenLnkBtn(1280,600,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetId ,targetId);
	} else {
		OpenLnkBtn(1280,600,'./udo.php?objectcode=u_hisops' + '' + '&targetId=' + targetId ,targetId);
	}
	
}
