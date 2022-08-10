// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

healthinsmodified = false;
var authenticateuseronadd = true;

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		var u_patientid=getInput("u_patientid");
		if (u_patientid!="" && getInput("u_patientname")=="") {
			setInput("u_patientid","");
			setInput("u_patientid",u_patientid,true);
		}
	} else {
		if (getTableRowCount("T112")>0 || getTableRowCount("T113")>0) showPopupFrame("popupFramePendingItems",true);
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (!isInput("u_lastname") && !isInput("u_firstname")) {
			if (isInputEmpty("u_patientid")) return false;
		}
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_type")) return false;
		if (isInputEmpty("u_arrivedby")) return false;
		if (isInputEmpty("u_assistedby")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		if (isInputEmpty("u_department")) return false;
		if (isInputEmpty("u_remarks")) return false;
		if (getInput("u_doctorid")=="") {
			if (window.confirm("Doctor was not selected. Continue?")==false) return false;
		}
		if (getInput("u_doctorid")!="") {
			if (isInputEmpty("u_doctorservice")) return false;
		}
		
		if (getGlobal("roleid")=="DOCTOR") {
			if (isInputEmpty("u_complaint",null,null,"tab1",0)) return false;
			if (isInputEmpty("u_pastmedhistory",null,null,"tab1",0)) return false;
			if (isInputEmpty("u_historyillness",null,null,"tab1",0)) return false;
		}

		if (getGlobal("roleid")=="NSG-ER" || getGlobal("roleid")=="NSG-OPD") {
			if (isInputNegative("u_height_ft",null,null,"tab1",1)) return false;
			if (isInputNegative("u_weight_kg",null,null,"tab1",1)) return false;
		}
		
		if (isInputEmpty("u_pricelist")) return false;
		if (isInputEmpty("u_paymentterm",null,null,"tab1",6)) return false;
		
		if (action=="a" && getInput("u_specialprice")=="1") {
			if (confirm("Payment Term ["+getInputSelectedText("u_paymentterm")+"] has special pricing, Please make sure correct price list ["+getInputSelectedText("u_pricelist")+"] is selected. Continue?")==false) return false;
		}

		
		if (isInput("u_vsdate") && isInput("u_vstime")) {
			if (getInputNumeric("u_vsdate")!="" || getInputNumeric("u_vstime")!="" || getInputNumeric("u_bt_c")!=0 || getInputNumeric("u_bp_s")!=0 || getInputNumeric("u_bp_d")!=0 || getInputNumeric("u_rr")!=0 || getInputNumeric("u_hr")!=0 || getInputNumeric("u_o2sat")!=0) {
				if (isInputEmpty("u_vsdate")) return false;
				if (isInputEmpty("u_vstime")) return false;
				if (getInputNumeric("u_bp_s")!=0 || getInputNumeric("u_bp_d")!=0) {
					if (isInputNegative("u_bp_s")) return false;
					if (isInputNegative("u_bp_d")) return false;
				}
			}
		}
		
		if (isTable("T11")) { 
			if (getTableInput("T11","u_name")!="") {
				page.statusbar.showError("An allergic medicine is being added/edited.");
				selectTab("tab1",2);
				selectTab("tab2-1",1);
				focusTableInput("T11","u_name");
				return false;
			}
		}
		
		if (getInput("u_expired")=="0") {
			setInput("u_expiredate","");
			setInput("u_expiretime","");
			if (isInput("u_typeofexpire")) setInput("u_typeofexpire","");
			if (isInput("u_resultautopsied")) setInput("u_resultautopsied",-1);
		}
		if (getInput("u_discharged")=="0") {
			if (isInput("u_endmethod")) setInput("u_endmethod","");
			setInput("u_enddate","");
			setInput("u_endtime","");
			if (isInput("u_dischargedby")) setInput("u_dischargedby","");
			if (isInput("u_typeofdischarge")) setInput("u_typeofdischarge","");
			if (isInput("u_resultrecovered")) setInput("u_resultrecovered",-1);
			if (isInput("u_resultimproved")) setInput("u_resultimproved",-1);
		}
		
		/*if (getTableRowCount("T1",true)==0) {
			page.statusbar.showError("At least one reason must be entered.");
			return false;
		}*/
		/*
		if (getTableRowCount("T2",true)==0) {
			page.statusbar.showError("At least one room must be entered.");
			return false;
		}
		*/
		/*if (getTableInput("T1","u_icdcode")!="") {
			page.statusbar.showError("A reason item is being added/edited.");
			return false;
		}*/
	/*
		if (getTableInput("T2","u_roomno")!="") {
			page.statusbar.showError("A room item is being added/edited.");
			return false;
		}
*/
		//if (getInput("docstatus")=="Discharged") {
		//	if (isInputEmpty("u_enddate")) return false;
		//	if (isInputEmpty("u_endtime")) return false;
		//}
		
		if (authenticateuseronadd && action=="sc") {
			if (!checkauthenticateGPSHIS()) return false;
		}
		
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hisclinicgynes":
		case "u_hisclinicobs":
			params["params"] =  "OP`"+getInput("docno");	
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
	switch (table) {
		case "T50":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T50","password");
			} else if (sc_press == "ENTER" && column=="password") {
				formSubmit();
			}
			break;
		case "T51":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T51","password");
			} else if (sc_press == "ENTER" && column=="password") {
				focusTableInput("T51","cancelreason");
			} else if (sc_press == "ENTER" && column=="cancelreason") {
				focusTableInput("T51","remarks");
			} else if (sc_press == "ENTER" && column=="remarks") {
				formSubmit('cnd');
			}
			break;
		default:
			if (column=="u_remarks2") {
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press == "CTRL+F2") {
					var remarks = window.prompt("Please enter dictionary key for this final diagnosis.");
					if (remarks==null) return;
					savePatientAbbreviationGPSHIS("Final Diagnosis",remarks,getInput(column));
				}
			}
			break;
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch(table) {
		case "T1":
			switch(column) {
				case "u_icdcode":
						if (getTableInput(table,"u_icdcode")!="") {
							result = page.executeFormattedQuery("select code, name from u_hisicds where code='"+getTableInput(table,"u_icdcode")+"'");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setTableInput(table,"u_icdcode",result.childNodes.item(0).getAttribute("code"));
									setTableInput(table,"u_icddesc",result.childNodes.item(0).getAttribute("name"));
								} else {
									setTableInput(table,"u_icddesc","");
									page.statusbar.showError("Invalid ICD Code.");	
									return false;
								}
							} else {
								setTableInput(table,"u_icddesc","");
								page.statusbar.showError("Error retrieving ICD Code. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setTableInput(table,"u_icdcode","");
							setTableInput(table,"u_icddesc","");
						}
						break;
				case "u_icddesc":
						if (getTableInput(table,"u_icddesc")!="") {
							result = page.executeFormattedQuery("select code, name from u_hisicds where name='"+getTableInput(table,"u_icddesc")+"'");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setTableInput(table,"u_icdcode",result.childNodes.item(0).getAttribute("code"));
									setTableInput(table,"u_icddesc",result.childNodes.item(0).getAttribute("name"));
								} else {
									setTableInput(table,"u_icdcode","");
									page.statusbar.showError("Invalid ICD Description.");	
									return false;
								}
							} else {
								setTableInput(table,"u_icdcode","");
								page.statusbar.showError("Error retrieving ICD Description. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setTableInput(table,"u_icdcode","");
							setTableInput(table,"u_icddesc","");
						}
						break;
			}
			break;
		case "T2":
			switch(column) {
				case "u_roomno":
					if (getTableInput(table,"u_roomno")!="") {
						result = page.executeFormattedQuery("select a.code, a.u_isshared, a.u_charginguom from u_hisrooms a, u_hisroombeds b where b.code=a.code and b.u_status='Vacant' and a.code='"+getTableInput(table,"u_roomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_roomno",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setTableInput(table,"u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								if (getTableInput(table,"u_isroomshared")==0) setTableInput(table,"u_bedno",result.childNodes.item(0).getAttribute("code"));
								else setTableInput(table,"u_bedno","");
							} else {
								setTableInput(table,"u_bedno","");
								setTableInput(table,"u_rateuom","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_bedno","");
							setTableInput(table,"u_rateuom","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_bedno","");
						setTableInput(table,"u_roomno","");
						setTableInput(table,"u_rateuom","");
					}
					break;
				case "u_bedno":
					if (getTableInput(table,"u_bedno")!="") {
						result = page.executeFormattedQuery("select a.code, a.u_bedno, b.u_isshared, b.u_charginguom from u_hisroombeds a, u_hisrooms b where a.code=b.code and a.u_bedno='"+getTableInput(table,"u_bedno")+"' and a.u_status='Vacant'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setTableInput(table,"u_roomno",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setTableInput(table,"u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
							} else {
								setTableInput(table,"u_roomno","");
								setTableInput(table,"u_rateuom","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_roomno","");
							setTableInput(table,"u_rateuom","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_bedno","");
						setTableInput(table,"u_roomno","");
						setTableInputAmount(table,"u_chargingrate",1);
					}
					break;
			}
			break;
		case "T8":
			switch (column) {
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
			}
			break;
		case "T11":
			switch(column) {
				case "u_name":
					if (getTableInput(table,"u_name")!="") {
						result = page.executeFormattedQuery("select code from u_hisbrands where code='"+getTableInput(table,"u_name")+"' union all select code from u_hisgenerics where code='"+getTableInput(table,"u_name")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_name",result.childNodes.item(0).getAttribute("code"));
							} else {
								page.statusbar.showError("Invalid Brand/Generic Name.");	
								return false;
							}
						} else {
							page.statusbar.showError("Error retrieving Brand/Generic Name. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
					break;
			}
			break;			
		default:	
			switch(column) {
				case "u_patientid":
					return setPatientRegistrationData("OP");
					break;
				case "u_tfroomno":
					if (getInput("u_tfroomno")!="") {
						result = page.executeFormattedQuery("select a.code, a.name, a.u_isshared, a.u_charginguom, a.u_pricelist, a.u_roomtype, a.u_department from u_hisrooms a, u_hisroombeds b where b.code=a.code and (b.u_status='Vacant' or (b.u_status='Reserved' and b.u_patientname='"+getInput("u_patientname")+"')) and a.code='"+getInput("u_tfroomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tfroomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_tfisroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setInput("u_tfroomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_tfpricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_tfdepartment",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_tfrateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								if (getInput("u_tfisroomshared")==0) setInput("u_tfbedno",result.childNodes.item(0).getAttribute("code"));
								else setInput("u_tfbedno","");
								var result2 = ajaxxmlgetitemprice(result.childNodes.item(0).getAttribute("u_roomtype"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_tfpricelist")+"");
								setInputAmount("u_tfrate",formatNumeric(result2.getAttribute("price"),'',0));
								
							} else {
								setInput("u_tfbedno","");
								setInput("u_tfroomdesc","");
								setInput("u_tfrateuom","");
								page.statusbar.showError("Invalid Room No.");	
								return false;
							}
						} else {
							setInput("u_tfbedno","");
							setInput("u_tfroomdesc","");
							setInput("u_tfrateuom","");
							page.statusbar.showError("Error retrieving Room No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_tfbedno","");
						setInput("u_tfroomno","");
						setInput("u_tfroomdesc","");
						setInput("u_tfrateuom","");
					}
					break;
				case "u_tfbedno":
					if (getInput("u_tfbedno")!="") {
						result = page.executeFormattedQuery("select a.code, b.name, a.u_bedno, b.u_isshared, b.u_charginguom, b.u_pricelist, b.u_roomtype, b.u_department from u_hisroombeds a, u_hisrooms b where a.code=b.code and a.u_bedno='"+getInput("u_tfbedno")+"' and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tfbedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setInput("u_tfroomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_tfroomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_tfpricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_tfdepartment",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_tfrateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								setInput("u_tfisroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								var result2 = ajaxxmlgetitemprice(result.childNodes.item(0).getAttribute("u_roomtype"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_tfpricelist")+"");
								setInputAmount("u_tfrate",formatNumeric(result2.getAttribute("price"),'',0));
								
							} else {
								setInput("u_tfroomno","");
								setInput("u_tfroomdesc","");
								setInput("u_tfrateuom","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setInput("u_tfroomno","");
							setInput("u_tfroomdesc","");
							setInput("u_tfrateuom","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_tfbedno","");
						setInput("u_tfroomno","");
						setInput("u_tfroomdesc","");
						setInput("u_tfrateuom","");
					}
					break;
				case "u_weight_lb":
				case "u_weight_kg":
				case "u_height_ft":	
				case "u_height_in":	
				case "u_height_cm":	
					if (column=="u_weight_lb") setInputAmount("u_weight_kg",utils.divide(getInputNumeric("u_weight_lb"),2.20462));
					if (column=="u_weight_kg") setInputAmount("u_weight_lb",utils.multiply(getInputNumeric("u_weight_kg"),2.20462));
					if (column=="u_height_cm") {
						//ROUND(INT(CO2/30.48),1)
						setInput("u_height_ft",parseInt(utils.divide(getInputNumeric("u_height_cm"),30.48)));
						//ROUND(MOD(CO2,30.48)/2.54,1)
						setInputAmount("u_height_in",parseInt(utils.divide(getInputNumeric("u_height_cm") % 30.48,2.54)));
					}
					if (column=="u_height_ft" || column=="u_height_in") {
						//ROUND(((CM2*12)+CN2)*2.54,1
						setInputAmount("u_height_cm",((getInputNumeric("u_height_ft")*12)+getInputNumeric("u_height_in"))*2.54);
					}
					
					var bmi=0;
					bmi = (getInputNumeric("u_height_ft")*12)+getInputNumeric("u_height_in");
					bmi = bmi / 39.370;
					bmi = bmi * bmi;
					bmi = utils.divide(getInputNumeric("u_weight_kg"),bmi);
					if (bmi>0) {
						if (bmi<18) setInput("u_bmistatus","Underweight");
						else if (bmi<25) setInput("u_bmistatus","Desirable");
						else if (bmi<30) setInput("u_bmistatus","Overweight");
						else setInput("u_bmistatus","Obese");
					} else setInput("u_bmistatus","");
					setInputAmount("u_bmi",bmi);
					break;
				case "u_vsdate":
					setInput("u_vstime","");
					setInputAmount("u_bt_c",0);
					setInputAmount("u_bt_f",0);
					setInputAmount("u_bp_s",0);
					setInputAmount("u_bp_d",0);
					setInput("u_hr","");
					setInput("u_rr","");
					setInput("u_o2sat","");
					break;
				case "u_vstime":
					setInputAmount("u_bt_c",0);
					setInputAmount("u_bt_f",0);
					setInputAmount("u_bp_s",0);
					setInputAmount("u_bp_d",0);
					setInput("u_hr","");
					setInput("u_rr","");
					setInput("u_o2sat","");
					break;
				case "u_bt_c":
					if (getInputNumeric("u_bt_c")>0) setInputAmount("u_bt_f",32+(getInputNumeric("u_bt_c")*1.8));
					else setInputAmount("u_bt_f",0);
					break;
				case "u_bt_f":
					setInputAmount("u_bt_c",utils.divide(getInputNumeric("u_bt_f")-32,1.8));
					break;
				case "u_remarks2":
					if (getInput(column)!="") {
						var abbrev = page.executeFormattedSearch("select u_value from u_hisabbreviationitems where code='Final Diagnosis' and u_abbrev='"+utils.addslashes(getInput(column))+"'");
						if (abbrev!="") setInput(column,abbrev);
					}
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
		case "T8":
			switch (column) {
				case "u_inscode":
					return validatePatientHealthBenefit(element,column,table,row);
					break;
			}
			break;
		default:
			switch (column) {
				case "u_typeofdischarge":
					result = page.executeFormattedQuery("select u_method from u_hisdischargetypes where code='"+getInput("u_typeofdischarge")+"'");	 
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							setInput("u_endmethod",result.childNodes.item(0).getAttribute("u_method"));
						} else {
							setInput("u_endmethod",-1);
							page.statusbar.showError("Invalid Type of Discharge.");	
							return false;
						}
					} else {
						setInput("u_endmethod",-1);
						page.statusbar.showError("Error retrieving Type of Discharge. Try Again, if problem persists, check the connection.");	
					}
					break;
				case "u_paymentterm":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_prepaid, if(u_prepaid=1,0,1) as u_billing, u_specialprice from u_hispaymentterms where code='"+getInput(column)+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_billing",result.childNodes.item(0).getAttribute("u_billing"));
								setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
								setInput("u_specialprice",result.childNodes.item(0).getAttribute("u_specialprice"));
							} else {
								setInput("u_billing",0);
								setInput("u_prepaid",1);
								setInput("u_specialprice",0);
								page.statusbar.showError("Invalid Payment Term.");	
								return false;
							}
						} else {
							setInput("u_billing",0);
							setInput("u_prepaid",1);
							page.statusbar.showError("Error retrieving payment term. Try Again, if problem persists, check the connection.");	
						}						
					} else {
						setInput("u_billing",0);
						setInput("u_prepaid",1);
					}
					break;
				case "u_disccode":
					return setDiscountData();
					break;
				case "u_doctorid":
					//u_ajaxloadu_hisdoctorservicetypes("df_u_doctorservice",element.value,'',":");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(Id) {
	if (Id=="df_u_memberidT8" && getTableInput("T8","u_hmo")!=6) {
		page.statusbar.showWarning("Please enter the member id manually.");
		return false;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_patientid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hispatients")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=name";
			break;
		case "df_u_icdcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisicds where u_level>2")); 
			break;
		case "df_u_icddescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisicds where u_level>2")); 
			break;
		case "df_u_roomnoT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name,count(*) as 'Vacant Beds' from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Room & Board' and a.code=b.code and b.u_status='Vacant' group by a.code having count(*) >0")); 
			break;
		case "df_u_bednoT2":
			if (getTableInput("T2","u_roomno")=="")	params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.u_status='Vacant'")); 
			else params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.code='"+getTableInput("T2","u_roomno")+"' and a.u_status='Vacant'")); 
			
			break;
		case "df_u_tfroomno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name,count(*) as 'Vacant Beds' from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Room & Board' and a.code=b.code and (b.u_status='Vacant' or (b.u_status='Reserved' and b.u_patientname='"+getInput("u_patientname")+"')) group by a.code having count(*) >0")); 
			break;
		case "df_u_tfbedno":
			if (getInput("u_tfroomno")=="")	params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))")); 
			else params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.code='"+getInput("u_tfroomno")+"' and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))" )); 
			break;
		case "df_u_memberidT8":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText("T8","u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
		case "df_u_nameT11":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisbrands union all select code from u_hisgenerics")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Brand/Generic Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch(table) {
		case "T2":
			enableTableInput(table,"u_roomno");	
			enableTableInput(table,"u_bedno");	
			enableTableInput(table,"u_startdate");	
			enableTableInput(table,"u_starttime");	
			break;
		case "T8":
			disableTableInput(table,"u_memberid");
			disableTableInput(table,"u_membername");
			disableTableInput(table,"u_membertype");
			focusTableInput(table,"u_inscode");
			break;
		case "T11":
			enableTableInput(table,"u_name");	
			focusTableInput(table,"u_name");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch(table) {
		case "T1":
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			if (isTableInputEmpty(table,"u_doctorid")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_roomno")) return false;
			if (isTableInputEmpty(table,"u_bedno")) return false;
			if (isTableInputNegative(table,"u_rate")) return false;
			if (isTableInputNegative(table,"u_chargingrate")) return false;
			if (isTableInputEmpty(table,"u_startdate")) return false;
			if (isTableInputEmpty(table,"u_starttime")) return false;
			break;
		case "T8":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
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
		case "T11":
			if (isTableInputEmpty(table,"u_name")) return false;
			break;
		case "T13":
			if (isTableInputEmpty(table,"u_name")) return false;
			if (isTableInputEmpty(table,"u_quantity")) return false;
			break;
		case "T14":
			if (isTableInputEmpty(table,"u_date")) return false;
			if (isTableInputEmpty(table,"u_action")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T8": computeCreditLimitGPSHIS(table); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch(table) {
		case "T1":
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			if (isTableInputEmpty(table,"u_doctorid")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_roomno")) return false;
			if (isTableInputEmpty(table,"u_bedno")) return false;
			if (isTableInputNegative(table,"u_rate")) return false;
			if (isTableInputNegative(table,"u_chargingrate")) return false;
			if (isTableInputEmpty(table,"u_startdate")) return false;
			if (isTableInputEmpty(table,"u_starttime")) return false;
			break;
		case "T8":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
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
		case "T11":
			if (isTableInputEmpty(table,"u_name")) return false;
			break;
		case "T13":
			if (isTableInputEmpty(table,"u_name")) return false;
			if (isTableInputEmpty(table,"u_quantity")) return false;
			break;
		case "T14":
			if (isTableInputEmpty(table,"u_date")) return false;
			if (isTableInputEmpty(table,"u_action")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	
	switch (table) {
		case "T8": computeCreditLimitGPSHIS(table); break;
	}
	
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch(table) {
		case "T2":
			if (getTableRowStatus(table,row)=="E") {
				page.statusbar.showError("You cannot delete this room. Use room transfer or discharge the patient to vacant the room.");
				return false;
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	
	switch (table) {
		case "T8": computeCreditLimitGPSHIS(table); break;
	}
	
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch(table) {
		case "T2":
			if (getTableRowStatus(table,row)=="E") {
				disableTableInput(table,"u_roomno");	
				disableTableInput(table,"u_bedno");	
				disableTableInput(table,"u_startdate");	
				disableTableInput(table,"u_starttime");	
			}
			break;
		case "T8":
			switch (getTableInput(table,"u_hmo")) {
				case "0":
					enableTableInput(table,"u_memberid");
					enableTableInput(table,"u_membername");
					enableTableInput(table,"u_membertype");
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
		case "T11":
			if (getTableRowStatus(table,row)=="E") {
				disableTableInput(table,"u_name");	
			}
			break;
	}
}


function u_showExpiredGPSHIS() {
	showPopupFrame('popupFrameExpired',true);
	focusInput("u_expiredate");
}

function u_expiredGPSHIS() {
	if (isInputEmpty("u_expiredate")) return false;
	if (isInputEmpty("u_expiretime")) return false;
	if (isInputEmpty("u_typeofexpire")) return false;
	if (getInput("u_resultautopsied")=="-1") {
		page.statusbar.showError("Autopsied/Not Autopsied must be specific.");
		focusInput("u_resultautopsied");
		return false;
	}
	if (window.confirm("Patient expired. Continue?")==false) return false;
	setVar("resumeOnError",1);
	setInput("u_expired",1);
	formSubmit();
}

function u_admitpatientGPSHIS() {
	if (window.confirm("Patient will be admitted. Continue?")==false) return false;
	if (getInput("u_tfroomno")=="") {
		if (confirm("No room have been selected. Continue?")==false) return false;
	} else {
		if (isInputNegative("u_tfrate")) return false;
	}
	if (isInputEmpty("u_tfdepartment")) return false;
	if (isInputEmpty("u_tfroomdesc")) return false;
	if (isInputEmpty("u_tfpricelist")) return false;
	if (isInputEmpty("u_tfstartdate")) return false;
	if (isInputEmpty("u_tfstarttime")) return false;
	setInput("docstatus","Admitted");
	formSubmit();
}

function u_showDischargedGPSHIS() {
	if (getTableRowCount("T112")>0) {
		showPopupFrame("popupFramePendingItems");
		alert("You cannot discharge patient if Pending Requests still exists.");
		return false;
	}
	//if ((getInput("u_billno")!="" && getInput("u_predischarge")=="1") || (getInput("u_billing")==0 && !isInputChecked("u_forcebilling"))) {
	if ((getInput("u_billno")!="") || (getInput("u_billing")==0 && !isInputChecked("u_forcebilling"))) {		
		result = page.executeFormattedQuery("select substring(now(),1,10) as u_date, substring(now(),12,5) as u_time");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				setInput("u_enddate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_date")));
				setInput("u_endtime",result.childNodes.item(0).getAttribute("u_time"));
			} else {
				//page.statusbar.showError("Error retrieving Server Date. Try Again, if problem persists, check the connection.");	
				//return false;
			}
		} else {
			//page.statusbar.showError("Error retrieving Server Date. Try Again, if problem persists, check the connection.");	
			//return false;
		}	
		enableInput("u_enddate");
		enableInput("u_endtime");
		setInput("u_dischargedby",getGlobal("userid"));
		showPopupFrame('popupFrameDischarged',true);
		if (getInput("u_expired")=="1") {
			setInput("u_resultrecovered",0);
			disableInput("u_resultimproved");
		} else {
			setInput("u_resultrecovered",1);
		}
		focusInput("u_enddate");
	} else {
		alert("You cannot discharge patient if bill is not yet generated and settled.");
		return false;
	}
}

function u_dischargedGPSHIS() {
	if (getInput("u_billno")!="" || (getInput("u_billing")==0 && !isInputChecked("u_forcebilling"))) {
		if (isInputEmpty("u_enddate")) return false;
		if (isInputEmpty("u_endtime")) return false;
		if (isInputEmpty("u_dischargedby")) return false;
		if (isInputEmpty("u_typeofdischarge")) return false;
		if (getInput("u_resultrecovered")=="-1") {
			if (isInputNegative("u_resultrecovered")) return false;
		}
		if (getInput("u_resultrecovered")=="1") {
			if (getInput("u_resultimproved")=="-1") {
				page.statusbar.showError("Imprcved/Unimproved must be specific.");
				focusInput("u_resultimproved");
				return false;
			}
		}

		setInput("docstatus","Discharged");
		setVar("resumeOnError",1);
		setInput("u_discharged",1);
		formSubmit();
	} else {
		alert("You cannot discharge patient if bill is not yet generated.");
		return false;
	}
}

function OpenLnkBtnTrxNo(targetId) {
	switch (getTableElementValue(targetId,"T12","u_doctype")) {
		case "CHRG":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "CM":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hiscredits' + '' + '&targetId=' + targetId ,targetId);
			break;
	}
}

function OpenLnkBtnReqNoGPSHIS(targetId) {
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisrequests' + '&df_u_trxtype=' + getTableElementValue(targetId,"T112","trxtype") + '&edtopt=integrated&targetId=' + targetId ,targetId);
}

function OpenLnkBtnu_hislabtests(targetId) {
	var row = targetId.indexOf("T3r"),reftype="";
	if (getTableInput("T3",'u_status',targetId.substring(row+3,targetId.length))!="C") {
		page.statusbar.showWarning("You can only view the laboratory test result when status is Closed already.");	
		return false;	
	}
	OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hislabtests' + '' + '&targetId=' + targetId ,targetId);
	
}

function OpenLnkBtnu_hisconsultancyfees(targetId) {
	var row = targetId.indexOf("T4r"),reftype="";
	/*if (getTableInput("T3",'u_status',targetId.substring(row+3,targetId.length))!="C") {
		page.statusbar.showWarning("Yoou can only view the laboratory test result when status is Closed already.");	
		return false;	
	}
	*/
	OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hisconsultancyfees' + '' + '&targetId=' + targetId ,targetId);
	
}

function OpenLnkBtnu_hismedsups(targetId) {
	var row = targetId.indexOf("T5r"),reftype="";
	/*if (getTableInput("T3",'u_status',targetId.substring(row+3,targetId.length))!="C") {
		page.statusbar.showWarning("Yoou can only view the laboratory test result when status is Closed already.");	
		return false;	
	}
	*/
	if (getTableInputNumeric("T5",'u_amount',targetId.substring(row+3,targetId.length))>0) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismedsups' + '' + '&targetId=' + targetId ,targetId);
	} else {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismedsuprts' + '' + '&targetId=' + targetId ,targetId);
	}
	
}

function OpenLnkBtnu_hismiscs(targetId) {
	var row = targetId.indexOf("T6r"),reftype="";
	/*if (getTableInput("T3",'u_status',targetId.substring(row+3,targetId.length))!="C") {
		page.statusbar.showWarning("Yoou can only view the laboratory test result when status is Closed already.");	
		return false;	
	}
	*/
	if (getTableInputNumeric("T6",'u_amount',targetId.substring(row+3,targetId.length))>0) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismiscs' + '' + '&targetId=' + targetId ,targetId);
	} else {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hismisccrs' + '' + '&targetId=' + targetId ,targetId);
	}
	
}

function OpenLnkBtnu_hissplrooms(targetId) {
	var row = targetId.indexOf("T6r"),reftype="";
	/*if (getTableInput("T3",'u_status',targetId.substring(row+3,targetId.length))!="C") {
		page.statusbar.showWarning("Yoou can only view the laboratory test result when status is Closed already.");	
		return false;	
	}
	*/
	OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hissplrooms' + '' + '&targetId=' + targetId ,targetId);
	
}

function OpenLnkBtnu_hisclinicgynesGPSHIS() {
	var targetObjectId = 'u_hisclinicgynes';
	OpenLnkBtn(1024,630,'./udo.php?objectcode=u_hisclinicgynes' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_hisclinicobsGPSHIS() {
	var targetObjectId = 'u_hisclinicobs';
	OpenLnkBtn(1024,730,'./udo.php?objectcode=u_hisclinicobs' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}
