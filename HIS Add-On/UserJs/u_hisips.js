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
		if (isInputEmpty("u_arrivedby")) return false;
		if (isInputEmpty("u_admittedby")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		//if (isInputEmpty("u_remarks")) return false;
		
		/*if (getTableRowCount("T1",true)==0) {
			page.statusbar.showError("At least one reason must be entered.");
			return false;
		}*/
		if (isTable("T2")) { 
			if (getTableRowCount("T2",true)==0) {
				//if (confirm("No room have been selected. Continue?")==false) return false;
				page.statusbar.showError("Room must be entered.");
				selectTab("tab1",1);
				return false;
			}
	
			if (getTableInput("T2","u_roomno")!="") {
				page.statusbar.showError("A room item is being added/edited.");
				selectTab("tab1",1);
				return false;
			}
		}

		if (isInputEmpty("u_department")) return false;
		if (isInput("u_doctorname")) {
			if (isInputEmpty("u_doctorname")) return false;
		}
		if (isInputEmpty("u_doctorid",null,null,"tab1",2)) return false;
		if (isInput("u_doctorservice")) {
			if (isInputEmpty("u_doctorservice",null,null,"tab1",2)) return false;
		}
		
		if (getGlobal("roleid")=="DOCTOR") {
			if (isInputEmpty("u_complaint",null,null,"tab1",0)) return false;
			if (isInputEmpty("u_pastmedhistory",null,null,"tab1",0)) return false;
			if (isInputEmpty("u_historyillness",null,null,"tab1",0)) return false;
		}

		if (getGlobal("roleid")=="NSG-ER") {
			if (isTableInputEmpty("T100","u_complaint")) {
				showPopupFrame("popupFrameDoctorsDiagnosis",true);
				return false;
			}
			if (isTableInputEmpty("T100","u_pastmedhistory")) {
				showPopupFrame("popupFrameDoctorsDiagnosis",true); 
				return false;
			}
			if (isTableInputEmpty("T100","u_historyillness")) {
				showPopupFrame("popupFrameDoctorsDiagnosis",true);
				return false;
			}
			if (isInputEmpty("u_remarks",null,null,"tab1",0)) return false;
			
			if (getInput("u_ss_ams")=="0" && getInput("u_ss_diarrhea")=="0" && getInput("u_ss_hematemesis")=="0" && getInput("u_ss_palpitations")=="0" && getInput("u_ss_abdominalcramp")=="0" && getInput("u_ss_dizziness")=="0" && getInput("u_ss_hematuria")=="0" && getInput("u_ss_seizures")=="0" && getInput("u_ss_anorexia")=="0" && getInput("u_ss_dysphagia")=="0" && getInput("u_ss_hemoptysis")=="0" && getInput("u_ss_skinrashes")=="0" && getInput("u_ss_bleedinggums")=="0" && getInput("u_ss_dyspnea")=="0" && getInput("u_ss_irritability")=="0" && getInput("u_ss_stool")=="0" && getInput("u_ss_bodyweakness")=="0" && getInput("u_ss_dysuria")=="0" && getInput("u_ss_jaundice")=="0" && getInput("u_ss_sweating")=="0" && getInput("u_ss_blurring")=="0" && getInput("u_ss_epistaxis")=="0" && getInput("u_ss_lee")=="0" && getInput("u_ss_urgency")=="0" && getInput("u_ss_chestpain")=="0" && getInput("u_ss_fever")=="0" && getInput("u_ss_myalgia")=="0" && getInput("u_ss_vomiting")=="0" && getInput("u_ss_constipation")=="0" && getInput("u_ss_fou")=="0" && getInput("u_ss_orthopnea")=="0" && getInput("u_ss_weightloss")=="0" && getInput("u_ss_cough")=="0" && getInput("u_ss_headache")=="0" && getInput("u_ss_pain")=="0" && getInput("u_ss_others")=="0") {
				page.statusbar.showError("At least one Signs & Symptoms must be check.");
				return false;
			}
			
			if (getInput("u_ss_pain")=="1") {
				if (isInputEmpty("u_ss_pain_site",null,null,"tab1",0)) return false;
			}

			if (getInput("u_ss_others")=="1") {
				if (isInputEmpty("u_ss_others_note",null,null,"tab1",0)) return false;
			}

			if (isInputEmpty("u_vsdate",null,null,"tab1",3)) return false;
			if (isInputEmpty("u_vstime",null,null,"tab1",3)) return false;

			if (isInputNegative("u_bt_c",null,null,"tab1",3)) return false;
			if (isInputNegative("u_bp_s",null,null,"tab1",3)) return false;
			if (isInputNegative("u_bp_d",null,null,"tab1",3)) return false;
			if (isInputEmpty("u_hr",null,null,"tab1",3)) return false;
			if (isInputEmpty("u_rr",null,null,"tab1",3)) return false;
			if (isInputEmpty("u_o2sat",null,null,"tab1",3)) return false;

			if (getGlobal("roleid")=="NSG-ER" || getGlobal("roleid")=="HIS-ADMITTING") {
				if (isInputNegative("u_height_ft",null,null,"tab1",3)) return false;
				if (isInputNegative("u_weight_kg",null,null,"tab1",3)) return false;
			}
			
			if (getInput("u_gs_awake")=="0" && getInput("u_gs_altered")=="0") {
				page.statusbar.showError("At least one General Survey must be check.");
				return false;
			}
			
			if (getInput("u_gs_altered")=="1") {
				if (isInputEmpty("u_gs_altered_note",null,null,"tab1",3)) return false;
			}
			

		}
			
			/*if (getTableInput("T1","u_icdcode")!="") {
				page.statusbar.showError("A reason item is being added/edited.");
				return false;
			}*/
		if (isTable("T10")) { 
			if (getTableInput("T10","u_doctorid")!="") {
				page.statusbar.showError("A doctor is being added/edited.");
				return false;
			}
			var rc = getTableRowCount("T10"),rod=0;
			for (i = 1; i <= rc; i++) {
				if (isTableRowDeleted("T10",i)==false) {
					if (getTableInput("T10","u_rod",i)=="1") {
						rod++;
					}
				}
			}
			if (rod==0) {
				page.statusbar.showError("Resident on duty must be selected.");
				return false;
			} else if (rod>1) {
				page.statusbar.showError("Only one resident on duty must be selected.");
				return false;
			}
		}
		
		if (isInput("u_pricelist") && isInputEmpty("u_pricelist")) return false;
		if (isInput("u_paymentterm") && isInputEmpty("u_paymentterm",null,null,"tab1",7)) return false;

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
		
		if (isTable("T12")) { 
			if (getTableInput("T12","u_name")!="") {
				page.statusbar.showError("An allergic medicine is being added/edited.");
				selectTab("tab1",4);
				selectTab("tab2-1",1);
				focusTableInput("T12","u_name");
				return false;
			}
		}
		/*
		if (isInput("u_vsdate") && isInput("u_bmi")) {
			if (getInput("u_vsdate")=="" || getInputNumeric("u_bmi")==0) {
				if (confirm("Vital Signs & BMI must be entered diet list. Continue?")==false) {
					selectTab("tab1",3);
					return false;
				}
			}
		} else if (isInput("u_vsdate")) {
			if (getInput("u_vsdate")=="") {
				if (confirm("Vital Signs must be entered before Nurse Station will aceept the patient. Continue?")==false) {
					selectTab("tab1",3);
					return false;
				}
			}
		} else if (isInput("u_bmi")) {
			if (getInputNumeric("u_bmi")==0) {
				if (confirm("BMI must be entered before Nurse Station will aceept the patient. Continue?")==false) {
					selectTab("tab1",3);
					return false;
				}
			}
	   	}

		*/
		
		if (getInput("u_expired")=="0") {
			setInput("u_expiredate","");
			setInput("u_expiretime","");
			if (isInput("u_typeofexpire")) setInput("u_typeofexpire","");
			if (isInput("u_resultautopsied"))setInput("u_resultautopsied",-1);
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
	var data = new Array();
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
						result = page.executeFormattedQuery("select a.code, a.name, a.u_isshared, a.u_charginguom, a.u_pricelist, a.u_roomtype, a.u_department from u_hisrooms a, u_hisroombeds b where b.code=a.code and  (b.u_status='Vacant' or (b.u_status='Reserved' and b.u_patientname='"+getInput("u_patientname")+"')) and a.code='"+getTableInput(table,"u_roomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_roomno",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_roomtype",result.childNodes.item(0).getAttribute("u_roomtype"));
								setTableInput(table,"u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setTableInput(table,"u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								//setTableInput(table,"u_specialpriceref",getTableInputSelectedText(table,"u_pricelist"));
								setTableInput(table,"u_department",result.childNodes.item(0).getAttribute("u_department"));
								setTableInput(table,"u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								if (getTableInput(table,"u_isroomshared")==0) setTableInput(table,"u_bedno",result.childNodes.item(0).getAttribute("code"));
								else setTableInput(table,"u_bedno","");
								
								var result2 = ajaxxmlgetitemprice(result.childNodes.item(0).getAttribute("u_roomtype"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getTableInput(table,"u_pricelist")+"");
								setTableInputAmount(table,"u_rate",formatNumeric(result2.getAttribute("price"),'',0));
								
							} else {
								setTableInput(table,"u_bedno","");
								setTableInput(table,"u_rateuom","");
								setTableInput(table,"u_roomdesc","");
								setTableInput(table,"u_roomtype","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_bedno","");
							setTableInput(table,"u_roomdesc","");
							setTableInput(table,"u_roomtype","");
							setTableInput(table,"u_rateuom","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_bedno","");
						setTableInput(table,"u_roomno","");
						setTableInput(table,"u_roomtype","");
						setTableInput(table,"u_roomdesc","");
						setTableInput(table,"u_rateuom","");
					}
					break;
				case "u_bedno":
					if (getTableInput(table,"u_bedno")!="") {
						result = page.executeFormattedQuery("select a.code, b.name, a.u_bedno, b.u_isshared, b.u_charginguom, b.u_pricelist, b.u_roomtype, b.u_department from u_hisroombeds a, u_hisrooms b where a.code=b.code and a.u_bedno='"+getTableInput(table,"u_bedno")+"' and  (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setTableInput(table,"u_roomno",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_roomtype",result.childNodes.item(0).getAttribute("u_roomtype"));
								setTableInput(table,"u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setTableInput(table,"u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								//setTableInput(table,"u_specialpriceref",getTableInputSelectedText(table,"u_pricelist"));
								setTableInput(table,"u_department",result.childNodes.item(0).getAttribute("u_department"));
								setTableInput(table,"u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								var result2 = ajaxxmlgetitemprice(result.childNodes.item(0).getAttribute("u_roomtype"),"BRANCH:" + getGlobal("branch") + ";PRICELIST:"+getTableInput(table,"u_pricelist")+"");
								setTableInputAmount(table,"u_rate",formatNumeric(result2.getAttribute("price"),'',0));


							} else {
								setTableInput(table,"u_roomno","");
								setTableInput(table,"u_roomtype","");
								setTableInput(table,"u_roomdesc","");
								setTableInput(table,"u_rateuom","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setTableInput(table,"u_roomno","");
							setTableInput(table,"u_roomtype","");
							setTableInput(table,"u_roomdesc","");
							setTableInput(table,"u_rateuom","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_bedno","");
						setTableInput(table,"u_roomno","");
						setTableInput(table,"u_roomtype","");
						setTableInput(table,"u_roomdesc","");
						setTableInput(table,"u_rateuom","");
					}
					break;
				case "u_rate":
					setTableInputAmount(table,"u_amount",getTableInput(table,"u_quantity")*getTableInput(table,"u_rate"));
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
		case "T12":
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
				case "u_startdate":
				case "u_starttime":
					setRoomDateGPSHIS();
					break;
				case "u_patientid":
					return setPatientRegistrationData();
					break;
				case "u_diettype":	
					setInput("u_diettypedesc",getInputSelectedText("u_diettype"));
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
		case "T2":
			switch (column) {
				case "u_pricelist":
					if (getTableInput(table,"u_pricelist")!="") {
						var result2 = ajaxxmlgetitemprice(getTableInput(table,"u_roomtype"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getTableInput(table,"u_pricelist")+"");
						setTableInputAmount(table,"u_rate",formatNumeric(result2.getAttribute("price"),'',0));		
						if (getTableInputNumeric(table,"u_rate")==0) {
							page.statusbar.showError("Price List not applicable.");
							setTableInput(table,"u_pricelist","");
							return true;
						}
					} else setTableInputAmount(table,"u_rate",0);
					break;
			}
			break;
		case "T8":
			switch (column) {
				case "u_inscode":
					return validatePatientHealthBenefit(element,column,table,row);
					break;
			}
			break;
		case "T10":
			switch (column) {
				case "u_doctorid":
					result = page.executeFormattedQuery("select u_resident from u_hisdoctors where code='"+getTableInput(table,"u_doctorid")+"'");	 
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							if (result.childNodes.item(0).getAttribute("u_resident")=="1") checkedTableInput(table,"u_rod");	
							else uncheckedTableInput(table,"u_rod");
						} else {
							uncheckedTableInput(table,"u_rod");
							return false;
						}
					} else {
						page.statusbar.showError("Error retrieving Doctor Profile. Try Again, if problem persists, check the connection.");	
						return false;
					}
					//u_ajaxloadu_hisdoctorservicetypes("df_u_doctorserviceT10",element.value,'',":");
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
								/*if (getInput("u_specialprice")=="1") {
									setInputSelectedText("u_pricelist",getInput(column)+"-"+getInput("u_specialpriceref"));
								} else {
									setInputSelectedText("u_pricelist",getInput("u_specialpriceref"));
								}*/
							} else {
								setInput("u_billing",0);
								setInput("u_prepaid",1);
								setInput("u_specialprice",0);
								//setInputSelectedText("u_priceist",getInput("u_specialpriceref"));
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
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name,count(*) as 'Vacant Beds' from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Room & Board' and a.code=b.code and  (b.u_status='Vacant' or (b.u_status='Reserved' and b.u_patientname='"+getInput("u_patientname")+"')) group by a.code having count(*) >0")); 
			break;
		case "df_u_bednoT2":
			if (getTableInput("T2","u_roomno")=="")	params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))")); 
			else params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.code='"+getTableInput("T2","u_roomno")+"' and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))")); 
			
			break;
		case "df_u_memberidT8":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText("T8","u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
		case "df_u_nameT12":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisbrands union all select code from u_hisgenerics")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Brand/Generic Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisbrands"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=300"; 			
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
		case "T12":
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
			if (getInput("u_roomno")!="") {
				page.statusbar.showError("You cannot assign more than 1 room per patient.");
				return false;
			}
			if (isTableInputEmpty(table,"u_roomno")) return false;
			if (isTableInputEmpty(table,"u_bedno")) return false;
			if (isTableInputEmpty(table,"u_pricelist")) return false;
			if (isTableInputNegative(table,"u_rate")) return false;
			if (isTableInputNegative(table,"u_chargingrate")) return false;
			//if (isTableInputEmpty(table,"u_startdate")) return false;
			//if (isTableInputEmpty(table,"u_starttime")) return false;
			if (getInput("u_roomno")=="") {
				setTableInput(table,"u_startdate",getInput("u_startdate"));
				setTableInput(table,"u_starttime",getInput("u_starttime"));
			}
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
		case "T10":
			if (isTableInputEmpty(table,"u_doctorid")) return false;
			if (isTableInputEmpty(table,"u_doctorservice")) return false;
			if (getInput("u_doctorid")=="") checkedTableInput(table,"u_default");
			if (isTableInputChecked(table,"u_default")) setTableInput(table,"u_default",0,-1,"No");
			break;
		case "T12":
			if (isTableInputEmpty(table,"u_name")) return false;
			break;
		case "T14":
			if (isTableInputEmpty(table,"u_name")) return false;
			if (isTableInputEmpty(table,"u_quantity")) return false;
			break;
		case "T15":
			if (isTableInputEmpty(table,"u_date")) return false;
			if (isTableInputEmpty(table,"u_action")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setInput("u_roomno",getTableInput(table,"u_roomno",row));
			setInput("u_roomdesc",getTableInput(table,"u_roomdesc",row));
			setInput("u_roomtype",getTableInput(table,"u_roomtype",row));
			setInput("u_bedno",getTableInput(table,"u_bedno",row));
			setInput("u_roomrate",getTableInput(table,"u_rate",row));
			setInput("u_pricelist",getTableInput(table,"u_pricelist",row));
			setInput("u_department",getTableInput(table,"u_department",row));
			//setInput("u_specialpriceref",getTableInput(table,"u_specialpriceref",row));
			break;
		case "T8": computeCreditLimitGPSHIS(table); break;
		case "T10": setDoctorIDGPSHIS(); break;
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
			if (isTableInputEmpty(table,"u_pricelist")) return false;
			if (isTableInputNegative(table,"u_rate")) return false;
			if (isTableInputNegative(table,"u_chargingrate")) return false;
			//if (isTableInputEmpty(table,"u_startdate")) return false;
			//if (isTableInputEmpty(table,"u_starttime")) return false;
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
		case "T10":
			if (isTableInputEmpty(table,"u_doctorid")) return false;
			if (isTableInputEmpty(table,"u_doctorservice")) return false;
			if (isTableInputChecked(table,"u_default")) setTableInput(table,"u_default",0,-1,"No");
			break;
		case "T12":
			if (isTableInputEmpty(table,"u_name")) return false;
			break;
		case "T14":
			if (isTableInputEmpty(table,"u_name")) return false;
			if (isTableInputEmpty(table,"u_quantity")) return false;
			break;
		case "T15":
			if (isTableInputEmpty(table,"u_date")) return false;
			if (isTableInputEmpty(table,"u_action")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setInput("u_roomno",getTableInput(table,"u_roomno",row));
			setInput("u_roomdesc",getTableInput(table,"u_roomdesc",row));
			setInput("u_roomtype",getTableInput(table,"u_roomtype",row));
			setInput("u_bedno",getTableInput(table,"u_bedno",row));
			setInput("u_roomrate",getTableInput(table,"u_rate",row));
			setInput("u_pricelist",getTableInput(table,"u_pricelist",row));
			setInput("u_department",getTableInput(table,"u_department",row));
			//setInput("u_specialpriceref",getTableInput(table,"u_specialpriceref",row));
			break;
		case "T8": computeCreditLimitGPSHIS(table); break;
		case "T10": setDoctorIDGPSHIS(); break;
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
		case "T2":
			setInput("u_roomno","");
			setInput("u_roomdesc","");
			setInput("u_bedno","");
			setInput("u_roomrate",0);
			setInput("u_pricelist","");
			setInput("u_department","");
			break;
		case "T8": computeCreditLimitGPSHIS(table); break;
		case "T10": setDoctorIDGPSHIS(); break;
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
		case "T10":
			//u_ajaxloadu_hisdoctorservicetypes("df_u_doctorserviceT10",getTableInput(table,"u_doctorid",row),getTableInput(table,"u_doctorservice",row),":");
			break;
		case "T12":
			if (getTableRowStatus(table,row)=="E") {
				disableTableInput(table,"u_name");	
			}
			break;
	}
}

function setDoctorIDGPSHIS() {
	var rc = getTableRowCount("T10"), doctorid="",doctorservice="";
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T10",i)==false) {
			if (getTableInput("T10","u_default",i)=="1") {
				doctorid=getTableInput("T10","u_doctorid",i);
				doctorservice=getTableInput("T10","u_doctorservice",i);
				break;
			}
		}
	}
	setInput("u_doctorid",doctorid);
	setInput("u_doctorservice",doctorservice);
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
	//setInput("u_mgh",1);
	formSubmit();
}

function setRoomDateGPSHIS() {
	try {
		var rc =  getTableRowCount("T2");
		for (i = 1; i <= rc; i++) {
			if (isTableRowDeleted("T2",i)==false) {
				setTableInput("T2","u_startdate",getInput("u_startdate"),i);
				setTableInput("T2","u_starttime",getInput("u_starttime"),i);
				break;
			}
		}	
		setTableInput("T2","u_startdate",getInput("u_startdate"));
		setTableInput("T2","u_starttime",getInput("u_starttime"));
	} catch (theError) {
	}
}

function u_nursedGPSHIS() {
	setInput("u_nursed",1);
	setVar("resumeOnError",1);
	formSubmit();
}

function u_mghGPSHIS() {
	if (isInputEmpty("u_remarks2")) return false;
	setInput("u_mgh",1);
	setVar("resumeOnError",1);
	formSubmit();
}

function u_cancelmghGPSHIS() {
	if (getInput("u_billno")=="") {
		setVar("resumeOnError",1);
		setInput("u_mgh",0);
		formSubmit();
	} else {
		page.statusbar.showWarning("Bill already generated. Cancel Billing to untag May Go Home");
		return false;
	}
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
		}
		if (getInput("u_mgh")=="1") {
			setInput("u_resultrecovered",1);
		} else {
			disableInput("u_resultimproved");
		}
		focusInput("u_enddate");
	} else {
		alert("You cannot discharge patient if bill is not yet generated and settled.");
		return false;
	}
}

function u_cancelGPSHIS() {
	//if ((getInput("u_billno")!="" && getInput("u_predischarge")=="1") || (getInput("u_billing")==0 && !isInputChecked("u_forcebilling"))) {
	if (getInput("u_nursed")=="0") {
		if (confirm("Are you sure to cancel this admission. Please cancel admission fee if any prior to cancellation. Continue?")==false) return false;
		setInput("docstatus","Cancelled");
		setVar("resumeOnError",1);
		setInput("u_discharged",1);
		formSubmit();
	} else {
		page.statusbar.showWarning("Cancel not allowed if patient was already accepted in nurse station.");
		return false;
	}
}

function u_dischargedGPSHIS() {
	//if ((getInput("u_billno")!="" && getInput("u_predischarge")=="1") || (getInput("u_billing")==0 && !isInputChecked("u_forcebilling"))) {
	if ((getInput("u_billno")!="") || (getInput("u_billing")==0 && !isInputChecked("u_forcebilling"))) {
		if (getTableRowCount("T15",true)==0) {
			page.statusbar.showError("At least one course in the ward must be entered.");
			return false;
		}
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
		page.statusbar.showWarning("Bill not yet generated.");
		return false;
	}
}

function u_roomtfGPSHIS() {
	var targetId='u_hisroomtfs',row=getTableSelectedRow("T2");
	if (row==0) {
		page.statusbar.showWarning("Select the room to transfer from.");
		return false;	
	}
	if (getTableInput("T2","u_enddate",row)!="") {
		page.statusbar.showWarning("Select the room that is still occupied by the patient.");
		return false;	
	}
	
	OpenPopup(1024,480,'./udo.php?objectcode=u_hisroomtfs' + '' + '&targetId=' + targetId ,targetId);
}


function OpenLnkBtnTrxNo(targetId) {
	switch (getTableElementValue(targetId,"T13","u_doctype")) {
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
	OpenLnkBtn(1024,670,'./udo.php?objectcode=u_hisclinicobs' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}
