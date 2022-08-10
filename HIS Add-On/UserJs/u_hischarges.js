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
page.elements.events.add.changing('onElementChangingGPSHIS');
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
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

var authenticateuseronadd = true;

function onPageLoadGPSHIS() {
	//document.images['PictureImg'].src = "";
	if (isInput("u_testtype")) {
		if (getInput("u_enddate")=="") {
			tinymce.init({
				selector: "div.editable",
				inline: true,
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste textcolor"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview"
		
			});
		}
	}
	
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISLABTESTTYPES") {
				setInput("u_testtype",window.opener.getInput("code"),true);
				enableInput("u_gender");
				enableInput("u_age");
			} else if (window.opener.getVar("objectcode")=="U_HISOPLIST") {
				setInput("u_department",window.opener.getTableInput("T1","u_department",window.opener.getTableSelectedRow("T1")),true);
				setInput("u_reftype","OP",true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
			} else if (window.opener.getVar("objectcode")=="U_HISIPLIST") {
				setInput("u_department",window.opener.getTableInput("T1","u_department",window.opener.getTableSelectedRow("T1")),true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
			} else if (window.opener.getVar("objectcode")=="U_HISPLIST") {
				setInput("u_reftype",window.opener.getInput("u_reftype"),true);
				setInput("u_refno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")),true);
				focusTableInput("T4","u_itemdesc");
			} else if (window.opener.getVar("objectcode")=="U_HISBILLS") {
				setInput("u_reftype",window.opener.getInput("u_reftype"),true);
				setInput("u_refno",window.opener.getInput("u_refno"),true);
				setInput("u_startdate",window.opener.getInput("u_docdate"),true);
			} else if (window.opener.getVar("objectcode")=="U_HISPOS") {
			} else {
				if (getInput("u_requestno")=="") {
					if (getInput("u_department")!="") setInput("u_department",getInput("u_department"));
					//setRequestnoGPSHIS();
					if (isInput("u_testtype")) setInput("u_testtype",window.opener.getTableInput("T1","u_template",window.opener.getTableSelectedRow("T1")));
					setInput("u_requestno",window.opener.getTableInput("T1","docno",window.opener.getTableSelectedRow("T1")));
					setTimeout("setRequestNoGPSHIS()",2000);
				}
			}
		} catch(theError) {
			if (getPrivate("requestbyrefno")!="") {
				setInput("u_reftype",getPrivate("requestbyreftype"),true);
				setInput("u_refno",getPrivate("requestbyrefno"),true);
			}
		}
	} else {
		try {
			if (window.opener.getVar("objectcode")=="U_HISLABTESTTYPES") {
				setInput("u_testtype",window.opener.getInput("code"),true);
				enableInput("u_gender");
				enableInput("u_age");
			}
		} catch (theError) {
		}
		if (isInput("u_testtype")) {
			selectTab("tab1",1);
			selectTab("tab1-2",1);
			//setTimeout("focusExamGPSHIS()",1000);
		}
	}
	if (getInput("u_trxtype")=="BILLING") focusInput("u_department");
	if (getInput("u_department")!="") {
		setTableInputDefault("T4","u_itemgroup",getInput("u_department"));
		setTableInput("T4","u_itemgroup",getInput("u_department"));
	}

	setImageGPSHIS();
}

function focusExamGPSHIS() {
	if (getInput("u_testtype")!="") {
		selectTab("tab1",1);
		selectTab("tab1-2",1);
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		setInput("u_remarks_rtf",getElementHTMLById("divEditor"));
		if (getInput("docseries")=="-1" && getInput("docno")=="-1" ) {
			if (isInputEmpty("u_testtype")) return false;
		} else {
			if (isInputEmpty("u_department")) return false;
			//if (isInputEmpty("u_testtype")) return false;
			
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_refno")) return false;
				if (isInputEmpty("u_patientid")) return false;
				if (isInputEmpty("u_patientname")) return false;
				if (isInputEmpty("u_gender",null,null,"tab1",1)) return false;
				if (isInputEmpty("u_birthdate",null,null,"tab1",1)) return false;
			} else {
				if (isInputEmpty("u_patientname")) return false;
				if (isInputEmpty("u_payrefno")) return false;
			}
			
			if (isInputEmpty("u_startdate")) return false;
			if (isInputEmpty("u_starttime")) return false;
			if (getInput("u_requestno")!="") {
				if (isInputEmpty("u_reqdate",null,null,"tab1",1)) return false;
				if (isInputEmpty("u_reqtime",null,null,"tab1",1)) return false;
			}
			//if (isInputEmpty("u_doctorid")) return false;
			if (!checkPricesGPSHIS()) return false;
			if (getInputNumeric("u_amount")==0) {
				if (window.confirm(getInputCaption("u_amount") + " is zero. Continue?")==false)	return false;
			}
		}
		if (getInput("u_prepaid")!=0 && getInput("u_payrefno")=="") {
			page.statusbar.showError("You cannot render unpaid cash or partial payment. Change the mode of payment of the patient to charge.");
			return false;
		}
		
		if (action=="a" && isTableInput("T50","userid") && authenticateuseronadd) {
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
			if (window.opener.getVar("objectcode").toLowerCase()=="u_hisbills" || window.opener.getVar("objectcode").toLowerCase()=="u_hisips" || window.opener.getVar("objectcode").toLowerCase()=="u_hisops") {
				window.opener.setKey("keys",window.opener.getInput("docno"));
				//window.opener.setInput("u_tab1selected",1);
				window.opener.formEdit();
				//window.close();
			} else if (window.opener.getVar("objectcode").toLowerCase()=="u_hisplist") {
				if (action=="a") {
					var row = window.opener.getTableSelectedRow("T1");
					if (row>0) window.opener.setTableInput("T1","u_chrg","?",row);
				}
				if (action=="a" && getInput("u_trxtype")!="BILLING") OpenReportSelect('preview');
			} else if (window.opener.getVar("objectcode").toLowerCase()=="u_hisbills") {
				if (action=="a") {
					formEdit();	
				}
				if (action=="a" && getInput("u_trxtype")!="BILLING") OpenReportSelect('preview');
			} else {
				if (getInput("docno")!="-1") {
					window.opener.formSearchNow();
					if (action=="a" && getInput("u_trxtype")!="BILLING") OpenReportSelect('preview');
					//window.close();
				}
			}
			if (getInput("u_trxtype")=="BILLING") window.close();
		} catch (theError) {
			//if (action=="a" && getInput("u_trxtype")!="BILLING") OpenReportSelect('printer');
		}
	}
}

function onCFLGPSHIS(Id) {
	if (Id=="itemtemplates") {
		if (isInputEmpty("u_department")) return false;
		if (getInput("u_requestno")!="") {
			page.statusbar.showWarning("You cannot use template if request exists.");
			return false;
		}
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
		if (getInput("u_requestno")!="") {
			page.statusbar.showWarning("You cannot use template if request exists.");
			return false;
		}
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
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"' AND DOCNO<>-1 AND DOCSERIES<>-1";
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
		case "T4":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "CTRL+M" && (column=="u_itemdesc" || column=="u_quantity")) {
				if (getTableInput("T4","u_pricing")!="-1") {
					if (getInput("u_trxtype")!="BILLING") {
						if (window.confirm("Are you sure to enable manual pricing. Continue?")==false)	return;
					}
					setTableInput("T4","u_pricing",-1);
					enableTableInput("T4","u_unitprice");
					focusTableInput("T4","u_unitprice");
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
				formSubmit();
			}
			break;
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_template":
					if (getTableInput(table,column,row)!="") {
						result = page.executeFormattedQuery("select a.u_template, a.u_normalrange, a.u_units, a.u_formula, a.u_formulanormalrange, a.u_formulaunits from u_hislabtesttypecases a where code = '"+getTableInput(table,"u_maintemplate",row)+"' and a.u_test='"+getTableInput(table,"u_test",row)+"' and a.u_template<>'' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+")) and a.u_template like '"+getTableInput(table,column,row)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_template",result.childNodes.item(0).getAttribute("u_template"),row);
								setTableInput(table,"u_normalrange",result.childNodes.item(0).getAttribute("u_normalrange"),row);
								setTableInput(table,"u_units",result.childNodes.item(0).getAttribute("u_units"),row);
								setTableInput(table,"u_formula",result.childNodes.item(0).getAttribute("u_formula"),row);
								setTableInput(table,"u_formulanormalrange",result.childNodes.item(0).getAttribute("u_formulanormalrange"),row);
								setTableInput(table,"u_formulaunits",result.childNodes.item(0).getAttribute("u_formulaunits"),row);
								setTableInput(table,"u_result","",row);
								setTableInput(table,"u_formularesult","",row);
								focusTableInput(table,"u_result",row);
							} else {
								setTableInput(table,"u_template","",row);
								setTableInput(table,"u_normalrange","",row);
								setTableInput(table,"u_units","",row);
								setTableInput(table,"u_formula","",row);
								setTableInput(table,"u_formulanormalrange","",row);
								setTableInput(table,"u_formulaunits","",row);
								setTableInput(table,"u_result","",row);
								setTableInput(table,"u_formularesult","",row);
								page.statusbar.showError("Invalid Template.");	
								return false;
							}
						} else {
							setTableInput(table,"u_template","",row);
							setTableInput(table,"u_normalrange","",row);
							setTableInput(table,"u_units","",row);
							setTableInput(table,"u_formula","",row);
							setTableInput(table,"u_formulanormalrange","",row);
							setTableInput(table,"u_formulaunits","",row);
							setTableInput(table,"u_result","",row);
							setTableInput(table,"u_formularesult","",row);
							page.statusbar.showError("Error retrieving Template record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_template","",row);
						setTableInput(table,"u_normalrange","",row);
						setTableInput(table,"u_units","",row);
						setTableInput(table,"u_formula","",row);
						setTableInput(table,"u_formulanormalrange","",row);
						setTableInput(table,"u_formulaunits","",row);
						setTableInput(table,"u_result","",row);
						setTableInput(table,"u_formularesult","",row);
					}				
					break;
				case "u_result":
					if (getTableInput(table,"u_formula",row)!="") {
						if (getTableInput(table,"u_result",row)!="") {
							var formula = getTableInput(table,"u_formula",row);
							if (formula.indexOf('{result}')!=-1) {
								result=getTableInput(table,"u_formula",row).replace(/{result}/,getTableInput(table,"u_result",row));
							} else {
								result=getTableInput(table,"u_result",row) + getTableInput(table,"u_formula",row);
							}
							try {
								result = utils.round((eval(result)),2);
							} catch (theError) {
								page.statusbar.showError("Error parsing formula["+result+"]:"+theError.message+". please enter only numbers.");
								return false;
							}
							setTableInput(table,"u_formularesult",result,row);
						} else {
							setTableInput(table,"u_formularesult","",row);
						}
					}
					break;
			}
			break;
		case "T4":
			switch(column) {
				case "u_itemcode":
				case "u_itemdesc":
					return validatePatientItemGPSHIS(element,column,table,row);
					break;
				case "u_unitprice":
					setTableInputPrice(table,"u_price",utils.subtractPerc(getTableInputNumeric(table,"u_unitprice"),getTableInputNumeric(table,"u_discperc")));
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
					break;
				case "u_quantity":
					if (row>0) {
						if (getTableInput(table,"u_ispackage",row)=="1") {
							showAjaxProcess();
							var rc =  getTableRowCount("T5");
							for (i = 1; i <= rc; i++) {
								if (isTableRowDeleted("T5",i)==false) {
									if (getTableInput("T5","u_packagecode",i)==getTableInput(table,"u_itemcode",row)) {
										setTableInputQuantity("T5","u_packageqty",getTableInputNumeric(table,"u_quantity",row),i);
										setTableInputQuantity("T5","u_quantity",getTableInputNumeric("T5","u_packageqty",i)*getTableInputNumeric("T5","u_qtyperpack",i),i);
									}
								}
							}
							hideAjaxProcess();
						}
					}
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
					break;
				case "u_price":
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
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
		default:
			switch(column) {
				case "u_startdate":
					if (getInput(table,"u_startdate")!="") {
						setInput("u_age",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
					} else {
						setInput("u_age",0);
					}
					setTestCasesGPSHIS();
					break;
				case "u_reftype":
					setInput("u_refno","",true);
					break;
				case "u_refno":
					result = validatePatientGPSHIS();
					resetPatientTotalAmount("T4","u_amount","u_vatamount","u_discamount");
					resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC,PF");
					if (result) {
						if (isInput("u_testtype")) {
							return setTestCasesGPSHIS();
						} else {
							setTableInput(table,"u_doctorid","");
						}
					}
					return result;
					break;
				case "u_requestno":
					setRequestNoFieldAttributesGPSHIS(true);
					clearTable("T4",true);
					clearTable("T5",true);
					if (getInput("u_requestno")!="") {
						return setRequestNoGPSHIS();
					} else {
						setInput("u_refno","");
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_gender","");
						setInput("u_birthdate","");
						setInput("u_age",0);
						if (isInput("u_testtype")) setTestCasesGPSHIS();
					}
					break;
				case "u_template":
				case "u_age":
					if (isInput("u_testtype")) setTestCasesGPSHIS();
					break;
				case "u_pfperc":
					setInputAmount("u_pfamount",utils.divide(getInputNumeric("u_pfperc"),100)*getInputNumeric("u_amount"));
					setInputAmount("u_pfdiscamount",utils.divide(getInputNumeric("u_pfdiscperc"),100)*getInputNumeric("u_pfamount"));
					setInputAmount("u_pfnetamount",getInputNumeric("u_pfamount")-getInputNumeric("u_pfdiscamount"));
					break;
				case "u_pfamount":
					setInputAmount("u_pfperc",utils.divide(getInputNumeric("u_pfamount"),getInputNumeric("u_amount"))*100);
					setInputAmount("u_pfdiscamount",utils.divide(getInputNumeric("u_pfdiscperc"),100)*getInputNumeric("u_pfamount"));
					setInputAmount("u_pfnetamount",getInputNumeric("u_pfamount")-getInputNumeric("u_pfdiscamount"));
					break;
				case "u_pfdiscperc":
					setInputAmount("u_pfdiscamount",utils.divide(getInputNumeric("u_pfdiscperc"),100)*getInputNumeric("u_pfamount"));
					setInputAmount("u_pfnetamount",getInputNumeric("u_pfamount")-getInputNumeric("u_pfdiscamount"));
					break;
				case "u_pfdiscamount":
					setInputAmount("u_pfdiscperc",utils.divide(getInputNumeric("u_pfdiscamount"),getInputNumeric("u_pfamount"))*100);
					setInputAmount("u_pfnetamount",getInputNumeric("u_pfamount")-getInputNumeric("u_pfdiscamount"));
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
	var data = new Array(), result;
	switch (table) {
		case "T4":
			break;
		default:
			switch (column) {
				case "u_doctorid":
					if (getInput("u_trxtype")!="LABORATORY") {
						var result = page.executeFormattedQuery("select a.code, a.name, a.u_uom, a.u_group, a.u_salespricing, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, a.u_ispackage, a.u_template, b.u_dfltprfamount from u_hisdoctors b inner join u_hisitems a on a.code=b.u_dfltprfcode where b.code = '"+getInput("u_doctorid")+"'");			
						if (result.getAttribute("result")!= "-1") {
							clearTable("T4",true);	
							for (var xxx=0; xxx<result.childNodes.length; xxx++) {
								data["u_itemcode"] = result.childNodes.item(xxx).getAttribute("code");
								data["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("name");
								data["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
								data["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_group");
								data["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
								data["u_quantity"] = formatNumericQuantity(1);
								data["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
								data["u_pricing"] = result.childNodes.item(xxx).getAttribute("u_salespricing");
								data["u_isstat"] = 0;
								data["u_doctorid"] = getInput("u_doctorid");
								data["u_doctorname"] = getInputSelectedText("u_doctorid");
								data["u_unitprice"] = formatNumericPrice(result.childNodes.item(xxx).getAttribute("u_dfltprfamount"));
								data["u_isstat"] = 0;
								if (result.childNodes.item(xxx).getAttribute("u_statperc")>0) {
									data["u_isstat"] = getInput("u_isstat");
								}
								data["u_statperc"] = formatNumericPercent(result.childNodes.item(xxx).getAttribute("u_statperc"));
								data["u_iscashdisc"] = result.childNodes.item(xxx).getAttribute("u_allowdiscount");
								data["u_isbilldisc"] = result.childNodes.item(xxx).getAttribute("u_billdiscount");
								//if (isTableInputUnique("T4","u_itemcode",data["u_itemcode"],getTableInputCaption("T4","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
									//if (data["u_ispackage"]=="1") {
									//	if (setPatientPackageItemsGPSHIS("T5",data["u_itemcode"],1)) {
									//		insertTableRowFromArray("T4",data);
									//	}
									//} else 
									insertTableRowFromArray("T4",data);
								//} else break;
							}
							resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","MISC");
							computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");
						} else {
							page.statusbar.showError("Error retrieving Items. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
					break;
			}
			break;
	}
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	var result;
	switch (table) {
		case "T1":
			switch (column) {
				case "u_itemgroup":
					focusTableInput(table,"u_itemdesc");
					break
			}
			break;
		case "T102":
			switch (column) {
				case "u_template":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedSearch("select u_remarks from u_hislabtesttypenotes where code='"+getTableInput(table,column)+"'");
						setElementHTMLById("divEditorT102",result);
					}
					break;				
			}
			break;
		case "T103":
			switch (column) {
				case "u_template":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedSearch("select REPLACE(u_remarks,'\n','<br>') from u_hislabtesttypenotes where code='"+getTableInput(table,column)+"'");
						setTableInput("T103","remarks",result.replace(/<br>/g,'\n'));
					}
					break;				
			}
			break;
		default:
			switch (column) {
				case "u_department":
					var result = setSectionData();
					if (result) {
						showAjaxProcess();
						clearTable("T4",true);
						clearTable("T5",true);
						//computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal");
						/*if (isInput("u_testtype")) {
							u_ajaxloadu_hislabtesttypesbysection("df_u_testtype",element.value,'',":");
						}*/
						hideAjaxProcess();
					}
					return result;
					break;
				case "u_testtype":
				case "u_gender":
					if (getInput("docseries")=="-1" && getInput("docno")=="-1" ) {
						if (isInput("u_testtype")) return setTestCasesGPSHIS();
					} else {
						//result = validatePatientLabGPSHIS(element,column,table,row);
						//if (result) 
						if (isInput("u_testtype")) return setTestCasesGPSHIS();
						//return result;
					}
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC,PF");
					break;
				case "u_disccode":
					result = setDiscountData();
					if (result) resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","EXAM,MEDSUP,MISC,PF");
					return result;
					break;
				case "u_doctorid2":
					setElementHTMLById("divEditorT102","");
					u_ajaxloadu_hislabtesttypertfnotes("df_u_templateT102",getInput("u_testtype"),element.value,'',":[Select]");
					u_ajaxloadu_hislabtesttypenotes("df_u_templateT103",getInput("u_testtype"),element.value,'',":[Select]");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T4":
			switch (column) {
				case "u_selected":
					if (row>0) {
						showAjaxProcess();
						if (isTableInputChecked(table,column,row)) {
							enableTableInput("T4","u_quantity",row);
							checkedTableInput("T5","u_selected",-1,"u_packagecode",getTableInput(table,"u_itemcode",row));
							checkedTableInput("T1","u_selected",-1,"u_itemcode",getTableInput(table,"u_itemcode",row));
						} else {
							disableTableInput("T4","u_quantity",row);
							uncheckedTableInput("T5","u_selected",-1,"u_packagecode",getTableInput(table,"u_itemcode",row));
							uncheckedTableInput("T1","u_selected",-1,"u_itemcode",getTableInput(table,"u_itemcode",row));
						}
						computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");						
						hideAjaxProcess();		
					}
					break;
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
					if (isTableInputChecked(table,"u_isstat",row) && getTableInputNumeric(table,"u_statperc",row)==0) {
						page.statusbar.showError("Item is not allowed for stat.");
						return false;
					}
					computePatientItemPriceGPSHIS(table,row);
					computePatientLineTotalGPSHIS(table,row);
					if (row>0) computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 

					break;
			}
			break;
		default:
			switch (column) {
				case "u_isstat":
					if (getInput(column)=="1") {
						checkedTableInput("T4","u_isstat",null,"u_statperc",0,">");
						checkedTableInput("T4","u_isstat",-1,"u_statperc",0,">");
					} else {
						uncheckedTableInput("T4","u_isstat",null,"u_statperc",0,">");
						uncheckedTableInput("T4","u_isstat",-1,"u_statperc",0,">");
					}
					//computePatientTotalAmountGPSHIS("T1","u_amount","u_vatamount","u_discamount","u_linetotal");
					resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount");
					break;
			}
	}
	return true;
}


function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_itemcodeT4":
		case "df_u_itemdescT4":
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_department")) return false;
				if (isInputEmpty("u_refno")) return false;
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
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')) from u_hisips where u_nursed=1 and docstatus not in ('Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')) from u_hisops where docstatus not in ('Discharged','Admitted')")); 
					}
				} else {
					if (getInput("u_reftype")=="IP") {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')) from u_hisips where u_nursed=1 and u_department='"+getInput("u_department")+"' and docstatus not in ('Discharged')")); 
					} else {
						params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment')) from u_hisops where docstatus not in ('Discharged','Admitted')")); 
					}
				}
			} else {
				if (getInput("u_reftype")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment'))  from u_hisips where docstatus not in ('Discharged')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname,u_paymentterm, if(u_prepaid=0,'Charge',if(u_prepaid=1,'Cash','Partial Payment'))  from u_hisops where docstatus not in ('Discharged','Admitted')")); 
				}
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name`Payment`Term")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			if (getInput("u_department")=="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,b.name as 'Section', a.u_patientname from u_hisrequests a, u_hissections b where b.code=a.u_department and (a.u_prepaid=0 or a.u_amount=0 or (a.u_prepaid=1 and a.u_payrefno<>'')) and a.docstatus='O'")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,b.name as 'Section',a.u_patientname from u_hisrequests a, u_hissections b where b.code=a.u_department and a.u_department='"+getInput("u_department")+"' and (a.u_prepaid=0 or a.u_amount=0 or (a.u_prepaid=1 and a.u_payrefno<>'')) and a.docstatus='O'")); 
			}
			break;
		case "df_u_itemcodeT4":
			var itemgroupexp="";
			if (getTableInput("T4","u_itemgroup")!="") itemgroupexp=" and u_group='"+getTableInput("T4","u_itemgroup")+"'";
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
		case "df_u_itemdescT4":
			var itemgroupexp="";
			if (getTableInput("T4","u_itemgroup")!="") itemgroupexp=" and a.u_group='"+getTableInput("T4","u_itemgroup")+"'";
			var departmentexp=" and  (isnull(a.u_department) or a.u_department='' or a.u_department='"+getInput("u_department")+"')";
			if (getInput("u_showallitems")=="0") departmentexp=" and a.u_department='"+getInput("u_department")+"'";
			
			if (getInput("u_stocklink")=="1" && getInput("u_department")!="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.u_genericname, a.code, b.instockqty from u_hisitems a left outer join stockcardsummary b on b.company='"+getGlobal("company")+"' and b.branch='"+getGlobal("branch")+"' and b.itemcode=a.code and b.warehouse='"+getInput("u_department")+"' where a.u_active=1 "+itemgroupexp+departmentexp)); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Generic Name`Item Code`In-Stock")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`30`15`8")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```quantity")); 			
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name, a.u_genericname, a.code  from u_hisitems a where a.u_active=1 "+itemgroupexp+departmentexp)); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Generic Name`Item Code")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`30`15")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			}
			params["params"] += "&cflselectionmode=2"; 			
			params["params"] += "&cflreturnonselect=1"; 			
			params["params"] += "&cflcloseonselect=0";
			break;
		case "df_u_doctornameT4":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_hisdoctors where u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		default:
			if (Id.substring(0,16)=="df_u_templateT1r") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_template, a.u_normalrange, a.u_units from u_hislabtesttypecases a where code = '"+getTableInput("T1","u_maintemplate",Id.substring(16))+"' and a.u_test='"+getTableInput("T1","u_test",Id.substring(16))+"' and a.u_template<>'' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+"))")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Template`Normal Range`Units")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`10")); 					
			} else if (Id.substring(0,14)=="df_u_resultT1r") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_value from u_hisexamcasevalues where code = '"+getTableInput("T1","u_test",Id.substring(14))+"'")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Values")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 					
			}	
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hislabsubtests":
			params["keys"] = getTableInput("T101","code",getTableSelectedRow("T101"))
			break;
	}
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	var result, data = new Array();
	switch (Id) {
		case "df_u_itemcodeT4":
		case "df_u_itemdescT4":
			var items = value.split('`');
			if (items.length>0) {
				value = value.replace(/`/g,"','");
				if (Id=="df_u_itemcodeT4") {
					result = page.executeFormattedQuery("select code, name, u_uom, u_group, u_salespricing, u_statperc, u_allowdiscount, u_billdiscount, u_ispackage, u_template from u_hisitems where u_active=1 and code in ('"+value+"')");			
				} else {
					result = page.executeFormattedQuery("select code, name, u_uom, u_group, u_salespricing, u_statperc, u_allowdiscount, u_billdiscount, u_ispackage, u_template from u_hisitems where u_active=1 and name in ('"+utils.addslashes(value)+"')");			
				}
				if (result.getAttribute("result")!= "-1") {
					var valid=true;
					for (var xxx=0; xxx<result.childNodes.length; xxx++) {
						valid=true;
						if (isInput("u_testtype")) {
							if (getInput("u_testtype")=="") {
								setInput("u_testtype",result.childNodes.item(xxx).getAttribute("u_template"));
							}
						}
						if (valid) {
							data["u_itemcode"] = result.childNodes.item(xxx).getAttribute("code");
							data["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("name");
							data["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
							data["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_group");
							data["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
							data["u_quantity"] = formatNumericQuantity(1);
							data["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
							data["u_pricing"] = result.childNodes.item(xxx).getAttribute("u_salespricing");
							data["u_isstat"] = 0;
							if (result.childNodes.item(xxx).getAttribute("u_statperc")>0) {
								data["u_isstat"] = getInput("u_isstat");
							}
							data["u_statperc"] = formatNumericPercent(result.childNodes.item(xxx).getAttribute("u_statperc"));
							data["u_iscashdisc"] = result.childNodes.item(xxx).getAttribute("u_allowdiscount");
							data["u_isbilldisc"] = result.childNodes.item(xxx).getAttribute("u_billdiscount");
							if (isTableInputUnique("T4","u_itemcode",data["u_itemcode"],getTableInputCaption("T4","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
								if (data["u_ispackage"]=="1") {
									if (setPatientPackageItemsGPSHIS("T5",data["u_itemcode"],1)) {
										insertTableRowFromArray("T4",data);
									}
								} else insertTableRowFromArray("T4",data);
							} else break;
							if (isInput("u_testby")) setTestCasesGPSHIS("T4",getTableSelectedRow("T4"));
						}
					}
					/*
					if (isInput("u_testtype")) {
						if (getInput("u_testtype")!="") {
							setTestCasesGPSHIS();
						}
					}
					*/
					resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","MISC");
					computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");
				} else {
					page.statusbar.showError("Error retrieving Items. Try Again, if problem persists, check the connection.");	
					return false;
				}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
				return false;
			}
			break;
		case "itemtemplates":
			clearTable("T4",true);
			clearTable("T5",true);
			computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");
			result = page.executeFormattedQuery("select a.code, a.name, a.u_uom, a.u_group, a.u_salespricing, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, a.u_ispackage, a.u_template, b.u_quantity from u_histemplateitems b inner join u_hisitems a on a.code=b.u_itemcode where b.code = '"+utils.addslashes(value)+"'");			
			if (result.getAttribute("result")!= "-1") {
				var valid=true;
				for (var xxx=0; xxx<result.childNodes.length; xxx++) {
					valid=true;
					if (isInput("u_testtype")) {
						if (getInput("u_testtype")=="") {
							setInput("u_testtype",result.childNodes.item(xxx).getAttribute("u_template"));
						}
					}
					if (valid) {
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
						if (isTableInputUnique("T4","u_itemcode",data["u_itemcode"],getTableInputCaption("T4","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
							if (data["u_ispackage"]=="1") {
								if (setPatientPackageItemsGPSHIS("T5",data["u_itemcode"],1)) {
									insertTableRowFromArray("T4",data);
								}
							} else insertTableRowFromArray("T4",data);
						} else break;
						if (isInput("u_testby")) setTestCasesGPSHIS("T4",getTableSelectedRow("T4"));
					}
				}
				/*
				if (isInput("u_testtype")) {
					if (getInput("u_testtype")!="") {
						setTestCasesGPSHIS();
					}
				}
				*/
				resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","MISC");
				computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");
			} else {
				page.statusbar.showError("Error retrieving template. Try Again, if problem persists, check the connection.");	
				return false;
			}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
			return false;
			break;
		case "itempackages":
			clearTable("T4",true);
			clearTable("T5",true);
			computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");
			result = page.executeFormattedQuery("select a.code, a.name, a.u_uom, a.u_group, a.u_salespricing, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, a.u_ispackage, a.u_template, b.u_qtyperpack, b.u_price from u_hispackageitems b inner join u_hisitems a on a.code=b.u_itemcode where b.code = '"+utils.addslashes(value)+"'");			
			if (result.getAttribute("result")!= "-1") {
				var valid=true;
				for (var xxx=0; xxx<result.childNodes.length; xxx++) {
					valid=true;
					if (isInput("u_testtype")) {
						if (getInput("u_testtype")=="") {
							setInput("u_testtype",result.childNodes.item(xxx).getAttribute("u_template"));
						}
					}
					if (valid) {
						data["u_itemcode"] = result.childNodes.item(xxx).getAttribute("code");
						data["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("name");
						data["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
						data["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_group");
						data["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
						data["u_quantity"] = formatNumericQuantity(result.childNodes.item(xxx).getAttribute("u_qtyperpack"));
						data["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
						data["u_pricing"] = result.childNodes.item(xxx).getAttribute("u_salespricing");
						data["u_packagedprice"] = 1;
						data["u_unitprice"] = formatNumericPrice(result.childNodes.item(xxx).getAttribute("u_price"));
					data["u_isstat"] = 0;
						if (result.childNodes.item(xxx).getAttribute("u_statperc")>0) {
							data["u_isstat"] = getInput("u_isstat");
						}
						data["u_statperc"] = formatNumericPercent(result.childNodes.item(xxx).getAttribute("u_statperc"));
						data["u_iscashdisc"] = result.childNodes.item(xxx).getAttribute("u_allowdiscount");
						data["u_isbilldisc"] = result.childNodes.item(xxx).getAttribute("u_billdiscount");
						if (isTableInputUnique("T4","u_itemcode",data["u_itemcode"],getTableInputCaption("T4","u_itemdesc")+" ["+data["u_itemdesc"]+"] already exists.")) {
							if (data["u_ispackage"]=="1") {
								if (setPatientPackageItemsGPSHIS("T5",data["u_itemcode"],1)) {
									insertTableRowFromArray("T4",data);
								}
							} else insertTableRowFromArray("T4",data);
						} else break;
						if (isInput("u_testby")) setTestCasesGPSHIS("T4",getTableSelectedRow("T4"));
					}
				}
				/*
				if (isInput("u_testtype")) {
					if (getInput("u_testtype")!="") {
						setTestCasesGPSHIS();
					}
				}
				*/
				resetPatientPricesGPSHIS("T4","u_amount","u_vatamount","u_discamount","MISC");
				computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal");
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
		case "T4":
			setTableInput(table,"u_isstat",getInput("u_isstat"));
			enableTableInput(table,"u_itemcode");
			enableTableInput(table,"u_itemdesc");
			disableTableInput(table,"u_unitprice");			
			disableTableInput(table,"u_price");			
			focusTableInput(table,"u_itemdesc");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T2":
			if (getVar("formSubmitAction")=="a") {
				page.statusbar.showWarning("Please add the document before attaching files.");	
				return false;
			}
			uploadAttachment();
			return false;
			break;	
		case "T4":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_itemgroup")=="PRF" || getTableInput(table,"u_itemgroup")=="PRM") {
				if (getInput("u_doctorid")=="") {
					if (isTableInputEmpty(table,"u_doctorname")) return false;
					if (isTableInputEmpty(table,"u_doctorid")) return false;
				}
			}
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				setPatientPackageItemsGPSHIS("T5",getTableInput(table,"u_itemcode"),getTableInputNumeric(table,"u_quantity"));
				hideAjaxProcess();
			}
			if (isInput("u_testtype")) {
				if (getInput("u_testtype")=="") {
					setInput("u_testtype",getTableInput(table,"u_template"));
				}
			}
			break;
		case "T101":
			var targetObjectId = '';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabsubtests' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T4": 
			setTestCasesGPSHIS(table,row);
			computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T4":
			if (!isTableInputUnique(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
			if (getTableInput(table,"u_itemgroup")=="PRF" || getTableInput(table,"u_itemgroup")=="PRM") {
				if (getInput("u_doctorid")=="") {
					if (isTableInputEmpty(table,"u_doctorname")) return false;
					if (isTableInputEmpty(table,"u_doctorid")) return false;
				}
			}
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				var rc =  getTableRowCount("T5");
				for (i = 1; i <= rc; i++) {
					if (isTableRowDeleted("T5",i)==false) {
						if (getTableInput("T5","u_packagecode",i)==getTableInput(table,"u_itemcode")) {
							setTableInput("T5","u_selected",getTableInput(table,"u_selected"),i);
							setTableInputQuantity("T5","u_packageqty",getTableInputNumeric(table,"u_quantity"),i);
							setTableInputQuantity("T5","u_quantity",getTableInputNumeric("T5","u_packageqty",i)*getTableInputNumeric("T5","u_qtyperpack",i),i);
						}
					}
				}
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T4": 
			if (getTableInput(table,"u_ispackage")=="1" || getTableInputNumeric(table,"u_chrgqty")!=0) {
				disableTableInput(table,"u_itemcode");
				disableTableInput(table,"u_itemdesc");
			} else {
				focusTableInput(table,"u_quantity");
			}

			if (getTableInput(table,"u_template")!="") {
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
	}
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T4": 
			computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T4":
			if (getTableInput(table,"u_ispackage")=="1") {
				showAjaxProcess();
				clearTable("T5",true,"u_packagecode",getTableInput(table,"u_itemcode"));
				hideAjaxProcess();
			}
			if (getTableInput(table,"u_template")!="") {
				showAjaxProcess();
				clearTable("T1",true,"u_itemcode",getTableInput(table,"u_itemcode"));
				hideAjaxProcess();
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T4": 
			computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
			break;
	}
}

function onTableBeforeEditRowGPSHIS(table,row) {
	var targetObjectId = '';
	switch (table) {
		case "T4": 
			if (getVar("formSubmitAction")=="sc" && row>0 && getInput("u_trxtype")=="LABORATORY") {
				targetObjectId = 'u_hislabtests';
				OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtests' + '&df_u_requestno='+getInput("docno")+'&df_u_type=' +getTableInput(table,"u_template",row) + '&targetId=' + targetObjectId ,targetObjectId);
				return false;
			}
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
		case "T101":
			targetObjectId = 'u_hislabsubtests';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabsubtests' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
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
			if (elementFocused.substring(0,13)=="df_u_resultT1") {
				focusTableInput(table,"u_result",row);
			} else if (elementFocused.substring(0,14)=="df_u_remarksT1") {
				focusTableInput(table,"u_remarks",row);
			} else if (elementFocused.substring(0,18)=="df_u_normalrangeT1") {
				focusTableInput(table,"u_normalrange",row);
			} else if (elementFocused.substring(0,12)=="df_u_unitsT1") {
				focusTableInput(table,"u_units",row);
			} else if (elementFocused.substring(0,20)=="df_u_formularesultT1") {
				focusTableInput(table,"u_formularesult",row);
			} else if (elementFocused.substring(0,25)=="df_u_formulanormalrangeT1") {
				focusTableInput(table,"u_formulanormalrange",row);
			} else if (elementFocused.substring(0,19)=="df_u_formulaunitsT1") {
				focusTableInput(table,"u_formulaunits",row);
			}
			break;
		case "T2":
			document.images['PictureImg'].style.display = "none";
			var video = document.getElementById('video');
			video.style.display = "none";
			video.pause();
			switch (getTableInput("T2","u_filetype",row)) {
				case "img":
					document.images['PictureImg'].src = getTableInput("T2","u_filepath",row);			
					document.images['PictureImg'].style.display = "block";
					break;
				case "video":
					var video = document.getElementById('video');
			    	video.setAttribute("src", getTableInput("T2","u_filepath",row));
					video.style.display = "block";
					video.play();
					break;
				default:
					var objTag = document.getElementById("contentarea");
					objTag.setAttribute('data', getTableInput("T2","u_filepath",row));
					objTag.style.display = "block";
					break;
			}
			break;
		case "T4":
			params["focus"] = false;
			if (elementFocused.substring(0,15)=="df_u_quantityT4") {
				focusTableInput(table,"u_quantity",row);
			}
			break;
	}
	return params;
}

function checkPricesGPSHIS() {
	var rc =  getTableRowCount("T4");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			if (getTableInputNumeric("T4","u_quantity",i)<=0) {
				page.statusbar.showError("Quantity is required.");	
				try {selectTab("tab1-1",0);} catch (theError) {}
				selectTableRow("T4",i);
				focusTableInput("T4","u_quantity",i);
				return false;	
			}
			if (getTableInput("T4","u_isfoc",i)=="0" && getTableInputNumeric("T4","u_linetotal",i)<=0) {
				page.statusbar.showError("Line Total is required.");	
				try {selectTab("tab1",0);} catch (theError) {}
				selectTableRow("T4",i);
				return false;	
			}
			computePatientLineTotalGPSHIS("T4",i);
		}
	}
	computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal"); 
	return true;
}

function setTestCasesGPSHIS(table,row) {
	var result, data = new Array();
	//getInput("u_gender")!="" && 
	if (table==null) return true;
	if (isInput("u_testby")) {
		showAjaxProcess();
		//clearTable("T1",true);
		if (getTableInput(table,"u_template",row)!="" && getInput("u_startdate")!="") {
			result = page.executeFormattedQuery("select b.u_specimen, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test, a.u_normalrange, a.u_formula, a.u_formulanormalrange, a.u_units, a.u_formulaunits from u_hislabtesttypecases a, u_hislabtesttypes b where b.code=a.code and a.code='"+getTableInput(table,"u_template",row)+"' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+")) group by a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test order by a.u_seq, a.u_seq2");	 
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
						if (xxxi==0) {
							//setInput("u_specimen",result.childNodes.item(xxxi).getAttribute("u_specimen"));	
						}
						data["u_itemcode"] = getTableInput(table,"u_itemcode",row);
						data["u_maintemplate"] = getTableInput(table,"u_template",row);
						data["u_test"] = result.childNodes.item(xxxi).getAttribute("u_test");
						data["u_seq"] = result.childNodes.item(xxxi).getAttribute("u_seq");
						data["u_seq2"] = result.childNodes.item(xxxi).getAttribute("u_seq2");
						data["u_group"] = result.childNodes.item(xxxi).getAttribute("u_group");
						data["u_print"] = result.childNodes.item(xxxi).getAttribute("u_print");
						data["u_test"] = result.childNodes.item(xxxi).getAttribute("u_test");
						data["u_template.cfl"] = "OpenCFLfs()";
						data["u_result.cfl"] = "OpenCFLfs()";
						data["u_normalrange"] = result.childNodes.item(xxxi).getAttribute("u_normalrange");
						data["u_units"] = result.childNodes.item(xxxi).getAttribute("u_units");
						data["u_formula"] = result.childNodes.item(xxxi).getAttribute("u_formula");
						data["u_formulanormalrange"] = result.childNodes.item(xxxi).getAttribute("u_formulanormalrange");
						data["u_formulaunits"] = result.childNodes.item(xxxi).getAttribute("u_formulaunits");
						insertTableRowFromArray("T1",data);
						disableTableInput("T1","u_selected",getTableRowCount("T1"));
					}
				}
			} else {
				hideAjaxProcess();
				page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
				return false;
			}
		}
		hideAjaxProcess();
	}
	return true;
}

function setRequestNoGPSHIS(){
	var data1 = new Array();
	var data2 = new Array();
	var data3 = new Array();
	var result = page.executeFormattedQuery("select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_payrefno, a.u_payreftype, a.u_doctorid, 'ITEM' as u_type, b.u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity-b.u_chrgqty as u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, 0 as u_otherchrg, b.u_linetotal, b.u_vatcode, b.u_vatrate, b.u_isstat as u_isstat2, b.u_statperc, b.u_statunitprice, b.u_scdiscamount, b.u_discperc, b.u_doctorid as u_pfid, b.u_doctorname as u_pfname, '' as u_packagecode, 0 as u_packageqty, 0 as u_qtyperpack, '' as u_packdepartment, b.u_ispackage, b.u_template, b.u_isfoc, b.u_remarks from u_hisrequests a, u_hisrequestitems b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid=0 or a.u_amount=0 or (a.u_prepaid<>0 and a.u_payrefno<>'')) and a.docstatus='O' and b.u_chrgqty<b.u_quantity union all select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_payrefno, a.u_payreftype, a.u_doctorid, 'ITEMPACK' as u_type, '' as u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity-b.u_chrgqty as u_quantity, 0 as u_unitprice, 0 as u_discamount, 0 as u_price, 0 as u_vatamount, 0 as u_otherchrg, 0 as u_linetotal, 0 as u_vatcode, 0 as u_vatrate, 0 as u_isstat2, 0 as u_statperc, 0 as u_statunitprice, 0 as u_scdiscamount, 0 as u_discperc, '' as u_pfid, '' as u_pfname, b.u_packagecode, b.u_packageqty, b.u_qtyperpack, b.u_department as u_packdepartment, 0 as u_ispackage, '' as u_template, 0 as u_isfoc, '' as u_remarks from u_hisrequests a, u_hisrequestitempacks b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid=0 or a.u_amount=0 or (a.u_prepaid<>01 and a.u_payrefno<>'')) and a.docstatus='O' and b.u_chrgqty<b.u_quantity");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				if (xxx==0) {
					setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
					setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
					//setInput("u_type",result.childNodes.item(0).getAttribute("u_type"));
					if (isInput("u_doctorid")) setInput("u_doctorid",result.childNodes.item(0).getAttribute("u_doctorid"));
					setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
					setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
					if (isInput("u_reqdate")) setInput("u_reqdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_requestdate")));
					if (isInput("u_reqtime")) setInput("u_reqtime",result.childNodes.item(0).getAttribute("u_requesttime").substring(0,5));
					setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
					setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
					if (isInput("u_gender")) setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
					if (isInput("u_birthdate")) setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
					if (isInput("u_age")) setInput("u_age",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
					setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
					setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
					setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
					setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
					setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
					setInput("u_payreftype",result.childNodes.item(0).getAttribute("u_payreftype"));
					//setInput("u_vatcode",result.childNodes.item(0).getAttribute("u_vatcode"));
					//setInput("u_vatrate",result.childNodes.item(0).getAttribute("u_vatrate"));
					//setInputQuantity("u_quantity",result.childNodes.item(0).getAttribute("u_quantity"));
					//setInputPercent("u_discperc",result.childNodes.item(0).getAttribute("u_discperc"));
					//setInputPrice("u_unitprice",result.childNodes.item(0).getAttribute("u_unitprice"));
					//setInputPercent("u_statperc",result.childNodes.item(0).getAttribute("u_statperc"));
					//setInputPrice("u_statunitprice",result.childNodes.item(0).getAttribute("u_statunitprice"));
					//setInputAmount("u_scdiscamount",result.childNodes.item(0).getAttribute("u_scdiscamount"));
					//setInputAmount("u_discamount",result.childNodes.item(0).getAttribute("u_discamount"));
					//setInputAmount("u_vatamount",result.childNodes.item(0).getAttribute("u_vatamount"));
					//setInputAmount("u_price",result.childNodes.item(0).getAttribute("u_price"));
					//setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_linetotal"));
					if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
					else uncheckedInput("u_scdisc");

					if (result.childNodes.item(0).getAttribute("u_isstat")=="1") checkedInput("u_isstat");
					else uncheckedInput("u_isstat");
					
					setImageGPSHIS();
				}
				if (result.childNodes.item(xxx).getAttribute("u_type")=="ITEM") {
					data1["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_itemgroup");
					data1["u_template"] = result.childNodes.item(xxx).getAttribute("u_template");
					data1["u_itemcode"] = result.childNodes.item(xxx).getAttribute("u_itemcode");
					data1["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("u_itemdesc");
					data1["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
					data1["u_quantity"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_quantity"),"quantity");
					data1["u_vatcode"] = result.childNodes.item(xxx).getAttribute("u_vatcode");
					data1["u_vatrate"] = result.childNodes.item(xxx).getAttribute("u_vatrate");
					data1["u_unitprice"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_unitprice"),"price");
					data1["u_discperc"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_discperc"),"percent");
					data1["u_isstat"] = result.childNodes.item(xxx).getAttribute("u_isstat2");
					data1["u_statperc"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_statperc"),"percent");
					data1["u_statunitprice"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_statunitprice"),"price");
					data1["u_scdiscamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_scdiscamount"),"amount");
					data1["u_discamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_discamount"),"amount");
					data1["u_vatamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_vatamount"),"amount");
					data1["u_price"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_price"),"price");
					data1["u_linetotal"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_linetotal"),"amount");
					data1["u_ispackage"] = result.childNodes.item(xxx).getAttribute("u_ispackage");
					data1["u_doctorid"] = result.childNodes.item(xxx).getAttribute("u_pfid");
					data1["u_doctorname"] = result.childNodes.item(xxx).getAttribute("u_pfname");
					data1["u_isfoc"] = result.childNodes.item(xxx).getAttribute("u_isfoc");
					data1["u_remarks"] = result.childNodes.item(xxx).getAttribute("u_remarks");
					if (data1["u_ispackage"]=="1") data3["u_ispackage.text"] = "Yes";
					else data1["u_ispackage.text"] = "No";
					insertTableRowFromArray("T4",data1);
					disableTableInput("T4","u_isstat",getTableRowCount("T4"));
					if (isInput("u_testby")) setTestCasesGPSHIS("T4",getTableSelectedRow("T4"));
				} else if (result.childNodes.item(xxx).getAttribute("u_type")=="ITEMPACK") {
					data2["u_packagecode"] = result.childNodes.item(xxx).getAttribute("u_packagecode");
					data2["u_packageqty"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_packageqty"),"quantity");
					data2["u_qtyperpack"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_qtyperpack"),"quantity");
					data2["u_itemcode"] = result.childNodes.item(xxx).getAttribute("u_itemcode");
					data2["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("u_itemdesc");
					data2["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
					data2["u_quantity"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_quantity"),"quantity");
					data2["u_department"] = result.childNodes.item(xxx).getAttribute("u_department");
					//data2["u_department"] = result.childNodes.item(xxx).getAttribute("u_packdepartment");
					//data2["u_department.text"] = result.childNodes.item(xxx).getAttribute("u_packdepartmentname");
					insertTableRowFromArray("T5",data2);
					disableTableInput("T5","u_selected",getTableRowCount("T5"));
				}
			}
			/*
			if (getInput("u_type")!="" && getInput("u_pricelist")!="") {
				var result2 = ajaxxmlgetitemprice(getInput("u_type"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
				setInputAmount("u_amount",formatNumeric(result2.getAttribute("price"),'',0));
			} else {
				setInputAmount("u_amount",0);
			}
			setInputAmount("u_totalamount",getInputNumeric("u_amount")+getInputNumeric("u_statamount"));
			*/
			setRequestNoFieldAttributesGPSHIS(false);
			computePatientTotalAmountGPSHIS("T4","u_amount","u_vatamount","u_discamount","u_linetotal","EXAM,MEDSUP,MISC,PF");
		} else {
			setInput("u_refno","");
			setInput("u_patientid","");
			setInput("u_patientname","");
			if (isInput("u_gender")) setInput("u_gender","");
			if (isInput("u_birthdate")) setInput("u_birthdate","");
			if (isInput("u_age")) setInput("u_age",0);
			if (isInput("u_testtype")) setTestCasesGPSHIS();
			page.statusbar.showError("Invalid Reference No.");	
			return false;
		}
		//setTimeout("setTestCasesGPSHIS()",1000);
		if (isInput("u_testtype")) setTestCasesGPSHIS();
	} else {
		setInput("u_refno","");
		setInput("u_patientid","");
		setInput("u_patientname","");
		if (isInput("u_gender")) setInput("u_gender","");
		if (isInput("u_birthdate")) setInput("u_birthdate","");
		if (isInput("u_age")) setInput("u_age",0);
		if (isInput("u_testtype")) setTestCasesGPSHIS();
		page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
		return false;
	}	
	return true;
}

function setRequestNoFieldAttributesGPSHIS(enable) {
	if (enable) {
		enableInput("u_isstat");
		enableInput("u_department");
		enableInput("u_reftype");
		enableInput("u_refno");
		if (isInput("u_testtype")) enableInput("u_testtype");
		if (isInput("u_reqdate")) enableInput("u_reqdate");
		if (isInput("u_reqtime")) enableInput("u_reqtime");
		enableInput("u_disccode");
		enableInput("u_pricelist");
		//enableInput("u_quantity");
	} else {
		disableInput("u_isstat");
		disableInput("u_department");
		disableInput("u_reftype");
		disableInput("u_refno");
		if (isInput("u_testtype")) disableInput("u_testtype");
		if (isInput("u_reqdate")) disableInput("u_reqdate");
		if (isInput("u_reqtime")) disableInput("u_reqtime");
		disableInput("u_disccode");
		disableInput("u_pricelist");
		//disableInput("u_quantity");
	}
}

function releaseGPSHIS() {
	selectTab("tab1",1);
	selectTab("tab1-2",0);
	
	var result = page.executeFormattedSearch("select substring(now(),1,16)");
	if (result!="") {
		setInput("u_enddate",formatDateToHttp(result.substring(0,10)));
		setInput("u_endtime",result.substring(11));
	}
	if (isInputEmpty("u_testby")) return false;
	if (isInputEmpty("u_enddate")) return false;
	if (isInputEmpty("u_endtime")) return false;
	if (isInputEmpty("u_doctorid2")) return false;
	formSubmit();
}

function showreopenGPSHIS() {
	showPopupFrame('popupFrameReOpen',true)
	focusInput("u_reopenremarks");
}

function reopenGPSHIS() {
	if (isInputEmpty("u_reopenremarks")) return false;
	//setInput("u_enddate","");
	//setInput("u_endtime","");
	formSubmit();
}

function uploadAttachment() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,mp4,pdf";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Examinations/" + getInput("docno") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function deleteAttachmentGPSHIS() {
	var rc = getTableSelectedRow("T2");	
	if (getTableSelectedRow("T2")>0) {
		if (ajaxdeleteattachment(getTableInput("T2","u_filepath",rc))) {
			formEdit();
		}
	} else page.statusbar.showWarning("No selected attachment to delete.");
}

function u_sltemplateGPSHIS() {
	if (isInputEmpty("u_doctorid2")) return false;
	selectTab("tab1-2",2);
	u_ajaxloadu_hislabtesttypenotes("df_u_templateT103",getInput("u_testtype"),getInput("u_doctorid2"),'',":[Select]");	
	showPopupFrame("popupFrameNotesTemplate",true);
}

function u_slrtftemplateGPSHIS() {
	if (isInputEmpty("u_doctorid2")) return false;
	selectTab("tab1-2",3);
	setElementHTMLById("divEditorT102","");
	u_ajaxloadu_hislabtesttypertfnotes("df_u_templateT102",getInput("u_testtype"),getInput("u_doctorid2"),'',":[Select]");	
	showPopupFrame("popupFrameRTFNotesTemplate",true);
}

function u_ajaxloadu_hislabtesttypenotes(p_elementid, p_u_testtype, p_u_doctorid,p_value,p_filler) {
	http = getHTTPObject(); // We create the HTTP Object
	document.getElementById(p_elementid).innerHTML = "<option>Loading..</option>"
	http.open("GET", "udp.php?ajax=1&objectcode=u_ajaxslu_hislabtesttypenotes&u_type=" + p_u_testtype + "&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	document.getElementById(p_elementid).innerHTML = http.responseText;
	document.getElementById('ajaxPending').value = "";	
	
}

function u_ajaxloadu_hislabtesttypertfnotes(p_elementid, p_u_testtype, p_u_doctorid,p_value,p_filler) {
	http = getHTTPObject(); // We create the HTTP Object
	document.getElementById(p_elementid).innerHTML = "<option>Loading..</option>"
	http.open("GET", "udp.php?ajax=1&objectcode=u_ajaxslu_hislabtesttypertfnotes&u_type=" + p_u_testtype + "&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	document.getElementById(p_elementid).innerHTML = http.responseText;
	document.getElementById('ajaxPending').value = "";	
	
}

function OpenLnkBtnu_hisrequests(targetId) {
	OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
}

function OpenLnkBtnu_hisexamcases(targetId) {
	OpenLnkBtn(450,380,'./udo.php?objectcode=u_hisexamcases' + '' + '&targetId=' + targetId ,targetId);
	
}

function u_templatesGPSHIS() {
	var targetObjectId="itemtemplates";
	OpenCFL(800,355,'./cflfs.php?' + '' + '&targetId=' + targetObjectId,targetObjectId);
}

function u_packagesGPSHIS() {
	var targetObjectId="itempackages";
	OpenCFL(800,355,'./cflfs.php?' + '' + '&targetId=' + targetObjectId,targetObjectId);
}

function printNotesGPSHIS() {
	 /*p_form = document.forms[0];
	 var temptarget = p_form.target;
	 p_form.target = "pdfwin";
	 setVar('formAction',"u_print");*/
	var win_width = 800; //screen.width; // - 300;
	var win_height = screen.height;
	var win_left = screen.width - win_width; //(screen.width / 2) - (win_width / 2);
	var win_top = 0;

	 window.open("./udp.php?objectcode=u_printexamnotes&docno="+getInput("docno"),"pdfwin",'toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,resizable=yes,alwaysRaised,modal=yes,titlebar=no' 
	+ ",width=" + win_width + ",height=" + win_height
	+ ",screenX=" + win_left + ",left=" + win_left 
	+ ",screenY=" + win_top + ",top=" + win_top + ""); 
	/*p_form.submit();
	p_form.target = temptarget;
	 setVar('formAction',"");*/
	//hideAjaxProcess();
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
