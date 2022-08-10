// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
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

function onPageLoadGPSHIS() {
	try {
		if (getVar("formSubmitAction")=="a" && getInput("u_billno")=="") {
			setInput("u_billno",window.opener.getInput("docno"));
			setInput("u_docdate",window.opener.getInput("u_enddate"));
			setInput("u_reftype",window.opener.getInput("u_reftype"));
			setInput("u_patientid",window.opener.getInput("u_patientid"));
			setInput("u_patientname",window.opener.getInput("u_patientname"));
			setInput("u_gender",window.opener.getInput("u_gender"));
			setInput("u_age",window.opener.getInput("u_age"));
			setInput("u_startdate",window.opener.getInput("u_startdate"));
			setInput("u_enddate",window.opener.getInput("u_enddate"));
			setInput("u_refno",window.opener.getInput("u_refno"),true);

			/*if (getInput("u_billno")!="") {
				result = page.executeFormattedQuery("select e.u_memberid, e.u_membertype, e.u_membername from u_hisbills a left join u_hisbillins e on e.company=a.company and e.branch=a.branch and e.docid=a.docid and e.u_inscode='PHIC' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_billno")+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("u_phicno", result.childNodes.item(0).getAttribute("u_memberid"));
						setInput("u_memberid", result.childNodes.item(0).getAttribute("u_memberid"));
						setInput("u_membername", result.childNodes.item(0).getAttribute("u_membername"));
						setInput("u_membertype", result.childNodes.item(0).getAttribute("u_membertype"));
					}
				} else {
					page.statusbar.showError("Error retrieving Bill  record. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			}*/
			
			var result = page.executeFormattedQuery("select sum(u_netamount) as u_acthc from (select b.u_netamount-b.u_paidamount as u_netamount from u_hisbills a inner join u_hisbillfees b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_feetype='Hospital Fees' where a.docno='"+getInput("u_billno")+"' union all select a.u_amount as u_netamount from u_hischarges a where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.U_PREPAID=0 AND a.U_BILLNO='' and a.DOCSTATUS not in ('CN')) as x ");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					setInputAmount("u_acthc", result.childNodes.item(0).getAttribute("u_acthc"));
					setInputAmount("u_balhc",getInputNumeric("u_acthc"));
					setInputAmount("u_act",getInputNumeric("u_balhc"));
					setInputAmount("u_bal",getInputNumeric("u_act"));
				}
			} else {
				page.statusbar.showError("Error retrieving Hospital Fee records. Try Again, if problem persists, check the connection.");	
				return false;
			}						

			var data3 = new Array(), actpf=0;
			result = page.executeFormattedQuery("select c.code, c.name, b.u_feetype, b.u_netamount-b.u_paidamount as u_actpf from u_hisbills a inner join u_hisbillfees b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_doctorid<>'' and (b.u_netamount-b.u_paidamount)>0 inner join u_hisdoctors c on c.code=b.u_doctorid where a.docno='"+getInput("u_billno")+"'");	
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (iii = 0; iii < result.childNodes.length; iii++) {	
						data3["u_doctorid"] = result.childNodes.item(iii).getAttribute("code");
						data3["u_doctorname"] = result.childNodes.item(iii).getAttribute("name");
						data3["u_feetype"] = result.childNodes.item(iii).getAttribute("u_feetype");
						data3["u_actpf"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_actpf"));
						data3["u_balpf"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_actpf"));
						insertTableRowFromArray("T1",data3);
						actpf+=parseFloat(result.childNodes.item(iii).getAttribute("u_actpf"));
					}
					setInputAmount("u_actpf", actpf);
					setInputAmount("u_balpf", actpf);					
					setInputAmount("u_act",getInputNumeric("u_balhc")+getInputNumeric("u_balpf"));
					setInputAmount("u_bal",getInputNumeric("u_act"));
				}
			} else {
				page.statusbar.showError("Error retrieving Professional Fees records. Try Again, if problem persists, check the connection.");	
				return false;
			}						
			focusInput("u_guarantorcode");
		}
		
	} catch (theError) {
	}
	
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {

		if (isInputEmpty("u_billno")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_guarantorcode")) return false;
		if (getInput("u_hmo")=="-1") {
			page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
			setInput("u_guarantorcode");
			return false;
		}		
		if (isInputEmpty("u_reftype")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (getInputSelectedText("docseries")=="Promissory Note" && getInput("u_hmo")!="5") {
			page.statusbar.showError("Health Benefit must be of type Collector if Promissory Note is selected.");	
			setInput("u_guarantorcode");
			return false;
		}
		switch (getInput("u_hmo")) {
			case "0":	//phic
				if (isInputEmpty("u_memberid")) return false;
				if (isInputEmpty("u_membername")) return false;
				//if (isInputEmpty("u_caserate")) return false;
				break;
			case "1":	//hmo
			case "4":	//company
			case "6":	//others
				if (isInputEmpty("u_memberid")) return false;
				if (isInputEmpty("u_membername")) return false;
				break;
		}		
		
		if (getInputNumeric("u_balhc")<0) {
			page.statusbar.showError("Hospital Package Benefits cannot be more than Net Charges.");
			return false;
		}

		if (getInputNumeric("u_balpf")<0) {
			page.statusbar.showError("PF Package Benefits cannot be more than Net Charges.");
			return false;
		}
		
		if (getTableInput("T50","userid")=="") {
			showPopupFrame("popupFrameAuthentication",true);
			focusTableInput("T50","userid");
			return false;
		}
		
	} else if (action=="cnd") {
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
				return false;
			}
			if (getTableInput("T51","cancelreason")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","cancelreason");
				return false;
			}
			if (getTableInput("T51","remarks")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","remarks");
				return false;
			}
		}
		
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		if (success) {	
			window.opener.setKey("keys",window.opener.getInput("docno"));
			//window.opener.setInput("u_tab1selected",1);
			window.opener.formEdit();
			window.close();
		}
	} catch(TheError) {
	}
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
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
				formSubmit();
			}
			break;
		default:
			switch (column) {
				case "u_lastname":
				case "u_firstname":
				case "u_middlename":
					var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
					if (sc_press=="TAB") {
						switch (column) {
							case "u_lastname": focusInput("u_firstname"); break;
							case "u_firstname": focusInput("u_middlename"); break;
							case "u_middlename": focusInput("u_extname"); break;
						}
					}
					break;
			}
			break;
	}
					
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_pkgpf":	
					setTableInputAmount(table,"u_balpf",getTableInputNumeric(table,"u_actpf")-getTableInputNumeric(table,"u_pkgpf"));
					break;
			}
			break;
		default:
			switch (column) {
				case "u_memberid":
					if (getInput("u_hmo")!=6) return true;
					
					if (getInput("u_memberid")!="") {
						result = page.executeFormattedQuery("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getInputSelectedText("u_guarantorcode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and a.custno='"+getInput("u_memberid")+"' and isvalid=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_memberid",result.childNodes.item(0).getAttribute("custno"));
								setInput("u_membername",result.childNodes.item(0).getAttribute("custname"));
							} else {
								setInput("u_memberid","");
								setInput("u_membername","");
								page.statusbar.showError("Invalid Customer No.");	
								return false;
							}
						} else {
							setInput("u_memberid","");
							setInput("u_membername","");
							page.statusbar.showError("Error retrieving Customer No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_memberid","");
						setInput("u_membername","");
					}
					break;
				case "u_pkghc":
					setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_pkghc"));
					computeTotalGPSHIS();					
					
					break;
			}
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
		default:
			switch (column) {
				case "u_guarantorcode":
					disableInput("u_memberid");
					disableInput("u_membername");
					//disableInput("u_caserate");
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_hmo from u_hishealthins where code='"+getInput("u_guarantorcode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
								result2 = page.executeFormattedQuery("select u_memberid, u_membername, u_membertype from u_hispatienthealthins where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getInput("u_patientid")+"' and u_inscode='"+getInput("u_guarantorcode")+"'");	 
								if (result2.getAttribute("result")!= "-1") {
									if (parseInt(result2.getAttribute("result"))>0) {
										setInput("u_memberid",result2.childNodes.item(0).getAttribute("u_memberid"));
										setInput("u_membername",result2.childNodes.item(0).getAttribute("u_membername"));
									} else {
										setInput("u_memberid","");
										setInput("u_membername","");
									}
								} else {
									setInput("u_hmo","-1");
									setInput("u_memberid","");
									setInput("u_membername","");
									page.statusbar.showError("Error retrieving Health Benefits for this patient. Try Again, if problem persists, check the connection.");	
								}
								switch (getInput("u_hmo")) {
									case "0":
										enableInput("u_memberid");
										enableInput("u_membername");
										focusInput("u_memberid");
										//enableInput("u_caserate");
										break;
									case "1":
									case "4":
										enableInput("u_memberid");
										enableInput("u_membername");
										focusInput("u_memberid");
										break;
									case "6":
										enableInput("u_memberid");
										focusInput("u_memberid");
										break;
								}
								if (getInput("u_hmo")=="5") setInputSelectedText("docseries","Promissory Note",true);
								else setInputSelectedText("docseries","Health Package",true);
							} else {
								setInput("u_hmo","-1");
								setInput("u_memberid","");
								setInput("u_membername","");
								page.statusbar.showError("Invalid Health Benefits Code.");	
								return false;
							}
						} else {
							setInput("u_hmo","-1");
							setInput("u_memberid","");
							setInput("u_membername","");
							page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_hmo","-1");
						setInput("u_memberid","");
						setInput("u_membername","");
					}
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
	if (Id=="df_u_memberid" && getInput("u_hmo")!=6) {
		page.statusbar.showWarning("Please enter the member id manually.");
		return false;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_memberid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getInputSelectedText("u_guarantorcode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getInput("u_billno")!="") {
				page.statusbar.showError("You cannot add this record, if its link with billing.");
				return false;
			}
			if (getTableInputNumeric(table,"u_balpf")<0) {
				page.statusbar.showError("Professional fee benefit cannot be more than actual charge less bef discount.");
				focusTableInput(table,"u_pkgpf");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": setCaseRateGPSHIS(); break;
		case "T2": computeT2TotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getTableInputNumeric(table,"u_balpf")<0) {
				page.statusbar.showError("Professional fee benefit cannot be more than actual charge less bef discount.");
				focusTableInput(table,"u_pkgpf");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeT1TotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getInput("u_billno")!="") {
				page.statusbar.showError("You cannot delete this record, if its link with billing.");
				return false;
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": computeT1TotalGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_pkgpf");
			break;
	}
}

function computeT1TotalGPSHIS() {
	var rc =  getTableRowCount("T1"), pkgpf=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			pkgpf += getTableInputNumeric("T1","u_pkgpf",i);
		}
	}
	setInputAmount("u_pkgpf",pkgpf);	
	setInputAmount("u_balpf",getInputNumeric("u_actpf")-getInputNumeric("u_pkgpf"));	

	computeTotalGPSHIS();
}

function computeTotalGPSHIS() {
	setInputAmount("u_pkg",getInputNumeric("u_pkghc")+getInputNumeric("u_pkgpf"));
	setInputAmount("u_bal",getInputNumeric("u_act")-getInputNumeric("u_pkg"));
	
}

function u_ajaxxmlgetageondateGPSHIS(p_birthdate,p_date,p_params) {
	var parser = new DOMParser();
	var dom;
	document.getElementById('ajaxPending').value = "*";	
	http = getHTTPObject(); // We create the HTTP Object
	try { 	
		http.open("GET", "udp.php?&objectcode=u_ajaxxmlgetageondate&birthdate="+escape(p_birthdate)+"&date="+escape(p_date)+ "&params=" + p_params, false);
		http.send(null);
		document.getElementById('ajaxPending').value = "";	
		dom = parser.parseFromString(http.responseText, "text/xml");
	} catch (theError) {
		document.getElementById('ajaxPending').value = "";	
		dom = parser.parseFromString('<validate result="0">' + theError.message + '</validate>', "text/xml");
		//setTimeout("setStatusMsg('" + theError.message + "')",1000);
	}
	return dom.documentElement;
}

