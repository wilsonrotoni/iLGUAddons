// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');

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
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

var oEditor;

	/*
(function() {
    //Setup some private variables

})();
*/

var medsupcflcloseonselect=true;
var authenticateuseronadd = true;

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISOPLIST") {
				setInput("u_requestdepartment",window.opener.getTableInput("T1","u_department",window.opener.getTableSelectedRow("T1")),true);
				setInput("u_reftype","OP",true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
				focusInput("u_department");
			} else if (window.opener.getVar("objectcode")=="U_HISIPLIST") {
				setInput("u_requestdepartment",window.opener.getTableInput("T1","u_department",window.opener.getTableSelectedRow("T1")),true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
				focusInput("u_department");
			} else if (window.opener.getVar("objectcode")=="U_HISPLIST") {
				setInput("u_reftype",window.opener.getInput("u_reftype"),true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
				if (getInput("u_trxtype")!="OP" && getInput("u_trxtype")!="IP" && getInput("u_trxtype")!="NURSE" && getInput("u_trxtype")!="ER") {
					if (getInput("u_requestdepartment")!="") {
						setInput("u_department",getInput("u_requestdepartment"),true);
						focusTableInput("T1","u_itemdesc");
					}
				} else focusInput("u_department");
			}
		} catch(theError) {
			if (getInput("u_department")==getInput("u_requestdepartment") && getInput("u_allowintsecreq")=="0") {
				setInput("u_reftype","WI",true);
				//if (getInput("u_trxtype")!="LABORATORY" && getInput("u_trxtype")!="RADIOLOGY") {
					setInput("u_patientname","CASH - " + getInputSelectedText("u_department"));
				//}
			}
			if (getPrivate("requestbyrefno")!="") {
				setInput("u_reftype",getPrivate("requestbyreftype"),true);
				setInput("u_refno",getPrivate("requestbyrefno"),true);
				if (getInput("u_trxtype")!="OP" && getInput("u_trxtype")!="IP" && getInput("u_trxtype")!="NURSE" && getInput("u_trxtype")!="ER") {
					if (getInput("u_requestdepartment")!="") {
						setInput("u_department",getInput("u_requestdepartment"),true);
						focusTableInput("T1","u_itemdesc");
					}
				} else {
					if (getPrivate("requesttodepartment")!="") {
						setInput("u_department",getPrivate("requesttodepartment"),true);
						focusTableInput("T1","u_itemdesc");
					} else {
						focusInput("u_department");
					}
				}
			}
		}
	}
	if (getInput("u_department")!="") {
		setTableInputDefault("T1","u_itemgroup",getInput("u_department"));
		setTableInput("T1","u_itemgroup",getInput("u_department"));
	}
	setImageGPSHIS();	
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		acceptText();
		if (getInput("u_prepaid")=="1" && (getInputNumeric("u_ishb")+getInputNumeric("u_ispf")+getInputNumeric("u_ispm"))>1) {
			alert("You cannot mix Hospital and Professional Fees & Materials for Cash term requests.");
			return false;
		}
		if (isInputEmpty("u_requestdepartment")) return false;
		if (isInputEmpty("u_department")) return false;
		//if (isInputEmpty("u_type")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		if (isInputEmpty("u_requestdate")) return false;
		if (isInputEmpty("u_requesttime")) return false;
		if (getInput("u_requestdepartment")!=getInput("u_department")) {
			if (isInputEmpty("u_duedate")) return false;
			if (isInputEmpty("u_duetime")) return false;
		}
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_gender")) return false;
			if (isInputEmpty("u_birthdate")) return false;
		}

		if (getInput("u_department")=="LAB" && getInput("u_reftype")=="WI") {
			if (isInputEmpty("u_gender")) return false;
			if (isInputEmpty("u_birthdate")) return false;
		}

		if (getInput("u_prepaid")=="2" && getInput("u_disconbill")=="1" && getInput("u_disccode")!="") {
			page.statusbar.showError("You cannot select discount for partial payments, except for third party items and fees.");
			return false;
		}
		
		/*if (getInput("u_reftype")!="IP") {
			if (isInputEmpty("u_doctorid")) return false;
		}*/
		//if (isInputEmpty("u_requestby")) return false;
		/*if (getTableRowCount("T1",true)==0) {
			page.statusbar.showWarning("At least one test is required.");
			focusTableInput("T1","u_type");
			return false;
		}*/
		if (isTable("T1")) {
			if (getTableInput("T1","u_itemcode")!="") {
				page.statusbar.showWarning("An item is being added/edited.");
				return false;
			}
		}
		if (!checkPricesGPSHIS()) return false;

		if (isTable("T3")) {
			if (getTableInput("T3","u_inscode")!="") {
				page.statusbar.showError("A health benefit is being added/edited.");
				return false;
			}
		}
		
		if (getInputNumeric("u_amount")==0) {
			if (window.confirm(getInputCaption("u_amount") + " is zero. Continue?")==false)	return false;
		}
		
		if (getInputNumeric("u_otheramount")!=0) {
			if (getInput("u_prepaid")==0) {
				page.statusbar.showError("health benefit is not applicable for charge request.");
				return false;
			}
			if (getInputNumeric("u_amount")!=getInputNumeric("u_otheramount")) {
				page.statusbar.showError("A health benefit amount must be equal the requested amount.");
				return false;
			}
			//if (window.confirm("You cannot change this after you have added it. Continue?")==false)	return false;
		}
		
		if (authenticateuseronadd) {
			if (!checkauthenticateGPSHIS()) return false;
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

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			if (window.opener.getVar("objectcode").toLowerCase()=="u_hisbills") {
				window.opener.setKey("keys",window.opener.getInput("docno"));
				//window.opener.setInput("u_tab1selected",1);
				window.opener.formEdit();
				window.close();
			} else if (window.opener.getVar("objectcode").toLowerCase()=="u_hispos") {
				window.opener.setInput("u_payrefno",getInput("docno"),true);
				window.close();
			} else if (window.opener.getVar("objectcode").toLowerCase()=="u_hisplist") {
				if (action=="a") {
					var row = window.opener.getTableSelectedRow("T1");
					if (row>0) window.opener.setTableInput("T1","u_req","?",row);
				}
				if (action=="a") OpenReportSelect('preview');
			} else {
				window.opener.formSearchNow();
				if (action=="a") OpenReportSelect('preview');
				//window.close();
			}
		} catch (theError) {
			//if (action=="a") OpenReportSelect('printer');
		}
	}
}

function onCFLGPSHIS(Id) {
	if (Id=="itemtemplates") {
		if (isInputEmpty("u_department")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		
	}
	if (Id=="itempackages") {
		if (isInputEmpty("u_department")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		
	}
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
		case "itemtemplates":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_histemplates where u_department='"+getInput("u_department")+"' and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "itempackages":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hispackages where u_department='"+getInput("u_department")+"' and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
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
		case "T1":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "CTRL+M" && (column=="u_itemdesc" || column=="u_quantity")) {
				if (getTableInput("T1","u_pricing")!="-1") {
					if (window.confirm("Are you sure to enable manual pricing. Continue?")==false)	return;
					setTableInput("T1","u_pricing",-1);
					enableTableInput("T1","u_unitprice");
				}
			}
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
	}
	
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					return validatePatientItemGPSHIS(element,column,table,row);
					break;
				case "u_unitprice":
					setTableInputPrice(table,"u_price",utils.subtractPerc(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_discperc")));
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
					break;
				case "u_quantity":
					if (row>0) {
						if (getTableInput(table,"u_ispackage",row)=="1") {
							showAjaxProcess();
							var rc =  getTableRowCount("T2");
							for (i = 1; i <= rc; i++) {
								if (isTableRowDeleted("T2",i)==false) {
									if (getTableInput("T2","u_packagecode",i)==getTableInput(table,"u_itemcode",row)) {
										setTableInputQuantity("T2","u_packageqty",getTableInputNumeric(table,"u_quantity",row),i);
										setTableInputQuantity("T2","u_quantity",getTableInputNumeric("T2","u_packageqty",i)*getTableInputNumeric("T2","u_qtyperpack",i),i);
									}
								}
							}
							hideAjaxProcess();
						}
					}
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
					break;				
				case "u_price":
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
					break;
				case "u_doctorname":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select a.code, a.name from u_hisdoctors a where a.u_active=1 and a.name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_doctorid",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_doctorname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_doctorid","");
								setTableInput(table,"u_doctorname","");
								page.statusbar.showError("Invalid Doctor.");	
								return false;
							}
						} else {
							setTableInput(table,"u_doctorid","");
							setTableInput(table,"u_doctorname","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_doctorid","");
						setTableInput(table,"u_doctorname","");
					}
					break;
			}
			break;
		case "T3":
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
			}
			break;
		default:
			switch(column) {
				case "u_refno":
					result = validatePatientGPSHIS();
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					return result;
					break;
				case "u_patientid":
					if (getInput("u_patientid")!="") {
						result = page.executeFormattedQuery("select code, name, u_birthdate, u_gender from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'and code='"+getInput("u_patientid")+"' and u_active=1");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
								setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
								setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInput("u_birthdate","");
								setInput("u_gender","");
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInput("u_birthdate","");
							setInput("u_gender","");
							page.statusbar.showError("Error retrieving Patient. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_birthdate","");
						setInput("u_gender","");
					}
					break;
				case "u_patientname":
					if (getInput("u_patientname")!="") {
						setInput("u_patientid","");
						setInput("u_birthdate","");
						setInput("u_gender","");
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_birthdate","");
						setInput("u_gender","");
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
		case "T1":
			switch (column) {
				case "u_itemgroup":
					focusTableInput(table,"u_itemdesc");
					break
			}
			break;
		case "T3":
			switch (column) {
				case "u_inscode":
					disableTableInput(table,"u_memberid");
					disableTableInput(table,"u_membername");
					disableTableInput(table,"u_membertype");
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select u_hmo from u_hishealthins where code='"+getTableInput(table,"u_inscode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								var u_hmo = result.childNodes.item(0).getAttribute("u_hmo");	
								if (u_hmo!=2) {
									setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
									result2 = page.executeFormattedQuery("select u_memberid, u_membername, u_membertype from u_hispatienthealthins where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getInput("u_patientid")+"' and u_inscode='"+getTableInput(table,"u_inscode")+"'");	 
									if (result2.getAttribute("result")!= "-1") {
										if (parseInt(result2.getAttribute("result"))>0) {
											setTableInput(table,"u_memberid",result2.childNodes.item(0).getAttribute("u_memberid"));
											setTableInput(table,"u_membername",result2.childNodes.item(0).getAttribute("u_membername"));
											setTableInput(table,"u_membertype",result2.childNodes.item(0).getAttribute("u_membertype"));
											
										} else {
											setTableInput(table,"u_memberid","");
											setTableInput(table,"u_membername","");
											setTableInput(table,"u_membertype","");
										}
									} else {
										setTableInput(table,"u_memberid","");
										setTableInput(table,"u_membername","");
										setTableInput(table,"u_membertype","");
										page.statusbar.showError("Error retrieving Health Benefits for this patient. Try Again, if problem persists, check the connection.");	
									}								
								} else {
									setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
								}
								switch (getTableInput(table,"u_hmo")) {
									case "0":
										enableTableInput(table,"u_memberid");
										enableTableInput(table,"u_membername");
										enableTableInput(table,"u_membertype");
										//focusTableInput(table,"u_memberid");
										//enableInput("u_caserate");
										break;
									case "1":
									case "4":
										enableTableInput(table,"u_memberid");
										enableTableInput(table,"u_membername");
										//focusTableInput(table,"u_memberid");
										break;
									case "6":
										enableTableInput(table,"u_memberid");
										//focusTableInput(table,"u_memberid");
										break;
								}					
								focusTableInput(table,"u_amount");
							} else {
								setTableInput(table,"u_hmo","-1");
								page.statusbar.showError("Invalid Health Benefits Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_hmo","-1");
							page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_hmo","-1");
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_reftype":
					setInput("u_refno","",true);
					disableInput("u_refno");
					disableInput("u_patientid");
					disableInput("u_patientname");
					disableInput("u_gender");
					disableInput("u_birthdate");
					setPrepaidGPSHIS();
					
					if (getInput("u_reftype")=="WI") {
						if (getInput("u_allowintsecreq")=="0") {
							setInput("u_pricelist",getPrivate("dfltpricelist"));
							setInput("u_paymentterm",getPrivate("dfltpaymentterm"));
							setInput("u_prepaid",1);
							setInput("u_disccode",getPrivate("dfltdisccode"));
							enableInput("u_patientid");
							enableInput("u_patientname");
							enableInput("u_gender");
							enableInput("u_birthdate");
							focusInput("u_patientname");
						} else {
							page.statusbar.showError("Walk-In not allowed in this section.");
							return false;
						}
					} else {
						enableInput("u_refno");
						focusInput("u_refno");
					}
					break;
				case "u_requestdepartment":
					var result = setSectionData(column,true);
					if (result && getInput("u_allowintsecreq")=="0") {
						if (getInput("u_refno")=="") setInput("u_prepaid",1);
						setInput("u_department",getInput("u_requestdepartment"),true);
						disableInput("u_department");
					} else {
						if (getInput("u_refno")=="") setInput("u_prepaid",0);
						enableInput("u_department");
					}
					break;
				case "u_department":
					resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					var result = setSectionData(column,true);
					if (result) {
						if (getInput("u_refno")!="") setInput("u_refno",getInput("u_refno"),true);
						showAjaxProcess();
						clearTable("T1",true);
						computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
						hideAjaxProcess();
					}
					return result;
					break;
				case "u_disccode":
					if (getInput("u_prepaid")=="2" && getInput("u_disconbill")=="1") {
						page.statusbar.showError("You cannot select discount for partial payments, except for third party items and fees.");
						return false;
					}
					
					result = setDiscountData();
					//resetPatientTotalAmount("T1","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					return result;
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_isautodisc":
					if (getTableInput(table,"u_itemgroup")!="PRF" && getTableInput(table,"u_itemgroup")!="PRM") {
						page.statusbar.showError("Only Professional Fee and Materials are allowed for manual discounts.");
						return false;
					}
					if (isTableInputChecked(table,"u_isautodisc")) {
						setTableInputPrice(table,"u_price",utils.subtractPerc(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_discperc")));
						computePatientLineTotalGPSHIS(table);
						disableTableInput(table,"u_price");
					} else {
						enableTableInput(table,"u_price");
					}
					break;
				case "u_isfoc":
					computePatientLineTotalGPSHIS(table);
					break;
				case "u_isstat":
					if (isTableInputChecked(table,"u_isstat",row) && getTableInputNumeric(table,"u_statunitprice",row)==0) {
						page.statusbar.showError("Item is not allowed for stat.");
						return false;
					}
					computePatientItemPriceGPSHIS(table,row);
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 

					break;
			}
			break;
		default:
			switch (column) {
				case "u_isstat":
					if (getInput(column)=="1") {
						checkedTableInput("T1","u_isstat",null,"u_statunitprice",0,">");
						checkedTableInput("T1","u_isstat",-1,"u_statunitprice",0,">");
					} else {
						uncheckedTableInput("T1","u_isstat",null,"u_statunitprice",0,">");
						uncheckedTableInput("T1","u_isstat",-1,"u_statunitprice",0,">");
					}
					//computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount");
					break;
				case "u_prepaid":
					if (getInput("u_prepaid")=="1") {
						if (window.confirm("Are you sure you patient want to pay cash for this request. Continue?")==false)	return false;
					} else if (getInput("u_prepaid")=="2") {
						if (window.confirm("Are you sure you patient want to pay partial payment for this request. Continue?")==false)	return false;
					}
					break;
			}
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_itemcodeT1":
		case "df_u_itemdescT1":
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_requestdepartment")) return false;
				if (isInputEmpty("u_department")) return false;
				if (isInputEmpty("u_refno")) return false;
			}
			break;
		case "df_u_memberidT3":
			if (getTableInput("T3","u_hmo")!=6) {
				page.statusbar.showWarning("Please enter the member id manually.");
				return false;
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_trxtype")=="NURSE") {
				if (getInput("u_requestdepartment")=="") {
					if (getInput("u_reftype")=="IP") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc from u_hisips where u_nursed=1 and docstatus not in ('Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc from u_hisops where docstatus not in ('Discharged','Admitted')")); 
					}
				} else {
					if (getInput("u_reftype")=="IP") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc from u_hisips where u_nursed=1 and u_department='"+getInput("u_requestdepartment")+"' and docstatus not in ('Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc from u_hisops where docstatus not in ('Discharged','Admitted')")); 
					}
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc  from u_hisips where docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')), u_scdisc  from u_hisops where docstatus not in ('Discharged','Admitted')")); 
				}
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment`Term`SC")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`15`15`3")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_patientid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hispatients where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_active=1"));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=name";
			break;
		case "df_u_itemcodeT2":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1")); 
			if ((getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") && getInput("u_stocklink")=="1") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name, b.instockqty from u_hisitems a, stockcardsummary b where 	b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.code not in ("+getTableInputGroupConCat("T2","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.code not in ("+getTableInputGroupConCat("T2","u_itemcode")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				}
			}
			break;
		case "df_u_itemdescT2":
			//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisitems where u_active=1")); 
			if ((getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="RADIOLOGY") && getInput("u_stocklink")=="1") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code, b.instockqty from u_hisitems a, stockcardsummary b where b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_fromdepartment")+"' and b.instockqty>0 and u_active=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`10")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``quantity")); 			
			} else if (getInput("u_trxtype")=="RADIOLOGY") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				//if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				//}
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.code  from u_hisitems a where u_type='MEDSUP' and u_active=1 and a.name not in ("+getTableInputGroupConCat("T1","u_itemdesc")+")")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
				//if (!medsupcflcloseonselect) {
					params["params"] += "&cflselectionmode=2"; 			
					params["params"] += "&cflcloseonselect=0"; 			
				//}
			}
			break;
		case "df_u_itemcodeT1":
			var itemgroupexp="";
			if (getTableInput("T1","u_itemgroup")!="") itemgroupexp=" and u_group='"+getTableInput("T1","u_itemgroup")+"'";
			var departmentexp=" and  (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')";
			if (getInput("u_showallitems")=="0") departmentexp=" and u_department='"+getInput("u_department")+"'";
			
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1 "+itemgroupexp+departmentexp)); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflcloseonselect=0";
			break;
		case "df_u_itemdescT1":
			var itemgroupexp="";
			if (getTableInput("T1","u_itemgroup")!="") itemgroupexp=" and a.u_group='"+getTableInput("T1","u_itemgroup")+"'";
			var departmentexp=" and  (isnull(a.u_department) or a.u_department='' or a.u_department='"+getInput("u_department")+"')";
			if (getInput("u_showallitems")=="0") departmentexp=" and a.u_department='"+getInput("u_department")+"'";
			if (getInput("u_stocklink")=="1" && getInput("u_department")!="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.u_genericname, a.code, b.instockqty from u_hisitems a left outer join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' where a.u_active=1 "+itemgroupexp+departmentexp)); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Generic Name`Item Code`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`30`15`8")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```quantity")); 			
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.u_genericname, a.code from u_hisitems a where a.u_active=1 "+itemgroupexp+departmentexp)); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Generic Name`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`30`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			}
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflcloseonselect=0";			
			break;
		case "df_u_doctornameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_hisdoctors where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_memberidT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.custno,a.custname from customers a inner join customergroups b on b.custgroup=a.custgroup and b.groupname='"+getTableInputSelectedText("T3","u_inscode")+"' where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and isvalid=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Customer No.`Customer Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=custname";
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hislabsubtests":
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT1":
		case "df_u_itemdescT1":
			var items = value.split('`');
			if (items.length>0) {
				value = value.replace(/`/g,"','");
				if (Id=="df_u_itemcodeT1") {
					result = page.executeFormattedQuery("select code, name, u_uom, u_group, u_salespricing, u_statperc, u_allowdiscount, u_billdiscount, u_ispackage, u_template, u_nonvatcs from u_hisitems where u_active=1 and code in ('"+value+"')");			
				} else {
					result = page.executeFormattedQuery("select code, name, u_uom, u_group, u_salespricing, u_statperc, u_allowdiscount, u_billdiscount, u_ispackage, u_template, u_nonvatcs from u_hisitems where u_active=1 and name in ('"+utils.addslashes(value)+"')");			
				}
				if (result.getAttribute("result")!= "-1") {
					for (var iii=0; iii<result.childNodes.length; iii++) {
						data["u_itemcode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
						data["u_itemgroup"] = result.childNodes.item(iii).getAttribute("u_group");
						data["u_template"] = result.childNodes.item(iii).getAttribute("u_template");
						data["u_quantity"] = formatNumericQuantity(1);
						data["u_ispackage"] = result.childNodes.item(iii).getAttribute("u_ispackage");
						data["u_pricing"] = result.childNodes.item(iii).getAttribute("u_salespricing");
						data["u_isstat"] = 0;
						if (result.childNodes.item(iii).getAttribute("u_statperc")>0) {
							data["u_isstat"] = getInput("u_isstat");
						}
						data["u_statperc"] = formatNumericPercent(result.childNodes.item(iii).getAttribute("u_statperc"));
						data["u_iscashdisc"] = result.childNodes.item(iii).getAttribute("u_allowdiscount");
						data["u_isbilldisc"] = result.childNodes.item(iii).getAttribute("u_billdiscount");
						data["u_nonvatcs"] = result.childNodes.item(iii).getAttribute("u_nonvatcs");
						if (isTableInputUnique("T1","u_itemcode",data["u_itemcode"],getTableInputCaption("T1","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
							if (data["u_ispackage"]=="1") {
								if (setPatientPackageItemsGPSHIS("T2",data["u_itemcode"],1)) {
									insertTableRowFromArray("T1",data);
								}
							} else insertTableRowFromArray("T1",data);
						} else break;
					}
					resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
					computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
				} else {
					page.statusbar.showError("Error retrieving Items. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				return false;
			}
			break;
		case "itemtemplates":
			clearTable("T1",true);
			clearTable("T2",true);
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
			result = page.executeFormattedQuery("select a.code, a.name, a.u_uom, a.u_group, a.u_salespricing, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, a.u_ispackage, a.u_template, b.u_quantity from u_histemplateitems b inner join u_hisitems a on a.code=b.u_itemcode where b.code = '"+utils.addslashes(value)+"'");			
			if (result.getAttribute("result")!= "-1") {
				var valid=true;
				for (var xxx=0; xxx<result.childNodes.length; xxx++) {
					data["u_itemcode"] = result.childNodes.item(xxx).getAttribute("code");
					data["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("name");
					data["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
					data["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_group");
					data["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
					data["u_quantity"] = formatNumericQuantity(result.childNodes.item(xxx).getAttribute("u_quantity"));
					data["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
					data["u_pricing"] = result.childNodes.item(xxx).getAttribute("u_salespricing");
					data["u_isstat"] = 0;
					if (result.childNodes.item(xxx).getAttribute("u_statperc")>0) {
						data["u_isstat"] = getInput("u_isstat");
					}
					data["u_statperc"] = formatNumericPercent(result.childNodes.item(xxx).getAttribute("u_statperc"));
					data["u_iscashdisc"] = result.childNodes.item(xxx).getAttribute("u_allowdiscount");
					data["u_isbilldisc"] = result.childNodes.item(xxx).getAttribute("u_billdiscount");
					if (isTableInputUnique("T1","u_itemcode",data["u_itemcode"],getTableInputCaption("T1","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
						if (data["u_ispackage"]=="1") {
							if (setPatientPackageItemsGPSHIS("T2",data["u_itemcode"],1)) {
								insertTableRowFromArray("T1",data);
							}
						} else insertTableRowFromArray("T1",data);
					} else break;
				}
				resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
				computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
			} else {
				page.statusbar.showError("Error retrieving template. Try Again, if problem persists, check the connection.");	
				return false;
			}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
			return false;
			break;
		case "itempackages":
			clearTable("T1",true);
			clearTable("T2",true);
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
			result = page.executeFormattedQuery("select a.code, a.name, a.u_uom, a.u_group, a.u_salespricing, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, a.u_ispackage, a.u_template, b.u_qtyperpack, b.u_price from u_hispackageitems b inner join u_hisitems a on a.code=b.u_itemcode where b.code = '"+utils.addslashes(value)+"'");			
			if (result.getAttribute("result")!= "-1") {
				var valid=true;
				for (var xxx=0; xxx<result.childNodes.length; xxx++) {
					data["u_itemcode"] = result.childNodes.item(xxx).getAttribute("code");
					data["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("name");
					data["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
					data["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_group");
					data["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
					data["u_quantity"] = formatNumericQuantity(result.childNodes.item(xxx).getAttribute("u_qtyperpack"));
					data["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
					data["u_pricing"] = result.childNodes.item(xxx).getAttribute("u_salespricing");
					data["u_unitprice"] = formatNumericPrice(result.childNodes.item(xxx).getAttribute("u_price"));
					data["u_isstat"] = 0;
					data["u_packagedprice"] = 1;
					if (result.childNodes.item(xxx).getAttribute("u_statperc")>0) {
						data["u_isstat"] = getInput("u_isstat");
					}
					data["u_statperc"] = formatNumericPercent(result.childNodes.item(xxx).getAttribute("u_statperc"));
					data["u_iscashdisc"] = result.childNodes.item(xxx).getAttribute("u_allowdiscount");
					data["u_isbilldisc"] = result.childNodes.item(xxx).getAttribute("u_billdiscount");
					if (isTableInputUnique("T1","u_itemcode",data["u_itemcode"],getTableInputCaption("T1","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
						if (data["u_ispackage"]=="1") {
							if (setPatientPackageItemsGPSHIS("T2",data["u_itemcode"],1)) {
								insertTableRowFromArray("T1",data);
							}
						} else insertTableRowFromArray("T1",data);
					} else break;
				}
				resetPatientPricesGPSHIS("T1","u_amount","u_vatamount","u_discamount","MISC");
				computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
			} else {
				page.statusbar.showError("Error retrieving template. Try Again, if problem persists, check the connection.");	
				return false;
			}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
			return false;
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			setTableInput(table,"u_isstat",getInput("u_isstat"));
			enableTableInput(table,"u_itemcode");
			enableTableInput(table,"u_itemdesc");
			disableTableInput(table,"u_unitprice");			
			disableTableInput(table,"u_price");			
			if (getTableInputType(table,"u_itemcode")=="text") focusTableInput(table,"u_itemcode");
			else focusTableInput(table,"u_itemdesc");
			break;
		case "T3":
			enableTableInput(table,"u_memberid");
			enableTableInput(table,"u_membername");
			enableTableInput(table,"u_membertype");
			focusTableInput(table,"u_inscode");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_itemgroup")=="PRF" || getTableInput(table,"u_itemgroup")=="PRM") {
				if (isTableInputEmpty(table,"u_doctorname")) return false;
				if (isTableInputEmpty(table,"u_doctorid")) return false;
			}
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				setPatientPackageItemsGPSHIS("T2",getTableInput(table,"u_itemcode"),getTableInputNumeric(table,"u_quantity"));
				hideAjaxProcess();
			}
			break;
		case "T3":
			if (getInput("u_prepaid")==0) {
				page.statusbar.showError("health benefit is not applicable for charge request.");
				return false;
			}
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="3" && getTableInput(table,"u_hmo")!="5" && getTableInput(table,"u_hmo")!="7") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}
			
			if (getInput("u_prepaid")=="3") {
				page.statusbar.showError("You cannot have health benefits on final bill payment. Use Billing CM instead for health benefits.");
				return false;
			}
			
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			if (getInput("u_prepaid")=="1" && (getInputNumeric("u_ishb")+getInputNumeric("u_ispf")+getInputNumeric("u_ispm"))>1) {
				alert("You cannot mix Hospital, Professional Fees & Materials for Cash term requests.");
				return false;
			}
			break;
		case "T3": computeOtherTotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_itemgroup")=="PRF" || getTableInput(table,"u_itemgroup")=="PRM") {
				if (isTableInputEmpty(table,"u_doctorname")) return false;
				if (isTableInputEmpty(table,"u_doctorid")) return false;
			}
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				var rc =  getTableRowCount("T2");
				for (i = 1; i <= rc; i++) {
					if (isTableRowDeleted("T2",i)==false) {
						if (getTableInput("T2","u_packagecode",i)==getTableInput(table,"u_itemcode")) {
							setTableInputQuantity("T2","u_packageqty",getTableInputNumeric(table,"u_quantity"),i);
							setTableInputQuantity("T2","u_quantity",getTableInputNumeric("T2","u_packageqty",i)*getTableInputNumeric("T2","u_qtyperpack",i),i);
						}
					}
				}
				hideAjaxProcess();
			}
			break;
		case "T3":
			if (getInput("u_prepaid")==0) {
				page.statusbar.showError("health benefit is not applicable for charge request.");
				return false;
			}
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="3" && getTableInput(table,"u_hmo")!="5" && getTableInput(table,"u_hmo")!="7") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}

			if (getInput("u_prepaid")=="3") {
				page.statusbar.showError("You cannot have health benefits on final bill payment. Use Billing CM instead for health benefits.");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			if (getTableInput(table,"u_ispackage")=="1" || getTableInputNumeric(table,"u_chrgqty")!=0) {
				disableTableInput(table,"u_itemcode");
				disableTableInput(table,"u_itemdesc");
			} else {
				focusTableInput(table,"u_quantity");
			}

			if (isTableInputChecked(table,"u_isautodisc")) {
				disableTableInput(table,"u_price");
			} else {
				enableTableInput(table,"u_price");
			}
			if (getTableInput(table,"u_pricing")=="-1") {
				enableTableInput(table,"u_unitprice");
			} else {
				disableTableInput(table,"u_unitprice");
			}
			break;
		case "T3":
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
	}
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			if (getInput("u_prepaid")=="1" && (getInputNumeric("u_ishb")+getInputNumeric("u_ispf")+getInputNumeric("u_ispm"))>1) {
				alert("You cannot mix Hospital, Professional Fees & Materials for Cash term requests.");
				return false;
			}
			break;
		case "T3": computeOtherTotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				clearTable("T2",true,"u_packagecode",getTableInput(table,"u_itemcode"));
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
		case "T3": computeOtherTotalGPSHIS(); break;
	}
}

function onTableBeforeEditRowGPSHIS(table,row) {
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			if (elementFocused.substring(0,15)=="df_u_quantityT1") {
				focusTableInput(table,"u_quantity",row);
			}
			break;
	}
	return params;
}

function computeOtherTotalGPSHIS() {
	var rc =  getTableRowCount("T3"), totalamount=0;
	
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			totalamount += getTableInputNumeric("T3","u_amount",i);
		}
	}
	setInputAmount("u_otheramount",totalamount);
	computeAmountRowGPSHIS();
}

function checkPricesGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_isfoc",i)=="0" && getTableInputNumeric("T1","u_linetotal",i)<=0) {
				page.statusbar.showError("Line Total is required.");	
				selectTab("tab1",0);
				selectTableRow("T1",i);
				return false;	
			}
		}
	}
	computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal"); 
	return true;
}

function u_templatesGPSHIS() {
	var targetObjectId="itemtemplates";
	OpenCFL(800,355,'./cflfs.php?' + '' + '&targetId=' + targetObjectId,targetObjectId);
}

function u_packagesGPSHIS() {
	var targetObjectId="itempackages";
	OpenCFL(800,355,'./cflfs.php?' + '' + '&targetId=' + targetObjectId,targetObjectId);
}


function OpenLnkBtnPayRefNoGPSHIS(targetId) {
	//if (getInput("u_requesttype")=="REQ") {
	//	OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
	//} else {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hispos' + '' + '&targetId=' + targetId ,targetId);
	//}
	
}

function setImageGPSHIS(){

    var http = new XMLHttpRequest();
	var photo = "../Images/"+getGlobal("company")+"/"+getGlobal("branch")+"/HIS/Patients/"+getInput("u_patientid")+"/photo.png";
	
	if (getInput("u_patientid")!="") {
		http.open('HEAD', photo, false);
		http.send();
		//alert(photo+":"+http.status);
		if (http.status == 200) {
			document.images['PhotoImg'].src = photo;	
		} else {
			var photo = "../Images/"+getGlobal("company")+"/"+getGlobal("branch")+"/HIS/Patients/"+getInput("u_patientid")+"/photo.jpg";
			
			http.open('HEAD', photo, false);
			http.send();
			//alert(photo+":"+http.status);
			if (http.status == 200) {
				document.images['PhotoImg'].src = photo;	
			} else {
				document.images['PhotoImg'].src = "./imgs/photo.jpg";	
			}
		}
	} else {
		document.images['PhotoImg'].src = "./imgs/photo.jpg";
	}
}

