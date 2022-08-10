// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');

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
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	//document.images['PictureImg'].src = "";
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISLABTESTTYPES") {
				setInput("u_type",window.opener.getInput("code"),true);
				enableInput("u_gender");
				enableInput("u_age");
			} else if (window.opener.getVar("objectcode")=="U_HISTRXLIST") {
				if (getInput("u_department")!="") setInput("u_department",getInput("u_department"),true);
				//setRequestnoGPSHIS();
				var rc = window.opener.getTableSelectedRow("T1");
				setInput("u_reqdate",window.opener.getTableInput("T1","u_requestdate2",rc));
				setInput("u_reqtime",window.opener.getTableInput("T1","u_requesttime2",rc));
				if (getInput("u_reqdate")=="") {
					setInput("u_reqdate",window.opener.getTableInput("T1","u_requestdate",rc));
					setInput("u_reqtime",window.opener.getTableInput("T1","u_requesttime",rc));
				}
				setInput("u_requestno",window.opener.getTableInput("T1","docno",rc));
				setInput("u_type",window.opener.getTableInput("T1","u_template",rc));
				setInput("u_reftype",window.opener.getTableInput("T1","u_reftype",rc));
				setInput("u_refno",window.opener.getTableInput("T1","u_refno",rc));
				setInput("u_patientid",window.opener.getTableInput("T1","u_patientid",rc));
				setInput("u_patientname",window.opener.getTableInput("T1","u_patientname",rc));
				setInput("u_birthdate",window.opener.getTableInput("T1","u_birthdate",rc));
				setInput("u_gender",window.opener.getTableInput("T1","u_gender",rc));
				setInput("u_age",window.opener.getTableInput("T1","u_age",rc));
				setInput("u_paymentterm",window.opener.getTableInput("T1","u_paymentterm",rc));
				setInput("u_disccode",window.opener.getTableInput("T1","u_disccode",rc));
				setInput("u_pricelist",window.opener.getTableInput("T1","u_pricelist",rc));
				setTimeout('setTestCasesGPSHIS()',1000);
			} else {
				if (getInput("u_department")!="") setInput("u_department",getInput("u_department"),true);
				//setRequestnoGPSHIS();
				setInput("u_reqdate",window.opener.getInput("u_reqdate"));
				setInput("u_reqtime",window.opener.getInput("u_reqtime"));
				if (getInput("u_reqdate")=="") {
					setInput("u_reqdate",window.opener.getInput("u_startdate"));
					setInput("u_reqtime",window.opener.getInput("u_starttime"));
				}
				setInput("u_requestno",window.opener.getInput("docno"));
				setInput("u_type",window.opener.getTableInput("T4","u_template",window.opener.getTableSelectedRow("T4")));
				setInput("u_reftype",window.opener.getInput("u_reftype"));
				setInput("u_refno",window.opener.getInput("u_refno"));
				setInput("u_patientid",window.opener.getInput("u_patientid"));
				setInput("u_patientname",window.opener.getInput("u_patientname"));
				setInput("u_birthdate",window.opener.getInput("u_birthdate"));
				setInput("u_gender",window.opener.getInput("u_gender"));
				setInput("u_age",window.opener.getInput("u_age"));
				setInput("u_paymentterm",window.opener.getInput("u_paymentterm"));
				setInput("u_disccode",window.opener.getInput("u_disccode"));
				setInput("u_pricelist",window.opener.getInput("u_pricelist"));
				setTimeout('setTestCasesGPSHIS()',1000);
			}
		} catch(theError) {
		}
	} else {
		if (window.opener.getVar("objectcode")=="U_HISLABTESTTYPES") {
			setInput("u_type",window.opener.getInput("code"),true);
			enableInput("u_gender");
			enableInput("u_age");
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		setInput("u_disconbill",1);	
		if (getInput("docseries")=="-1" && getInput("docno")=="-1" ) {
			if (isInputEmpty("u_type")) return false;
		} else {
			if (isInputEmpty("u_department")) return false;
			if (isInputEmpty("u_type")) return false;
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_refno")) return false;
			}
			if (isInputEmpty("u_startdate")) return false;
			if (isInputEmpty("u_starttime")) return false;
			if (isInputEmpty("u_reqdate")) return false;
			if (isInputEmpty("u_reqtime")) return false;
			if (getInput("u_reftype")!="WI") {
				if (isInputEmpty("u_patientid")) return false;
			}
			if (isInputEmpty("u_patientname")) return false;
			if (isInputEmpty("u_gender")) return false;
			if (isInputEmpty("u_birthdate")) return false;
			//if (isInputEmpty("u_doctorid")) return false;
			//if (isInputEmpty("u_testby")) return false;
			//if (isInputNegative("u_amount")) return false;
		}
	} else if (action=="cld") {
		var result = page.executeFormattedSearch("select substring(now(),1,16)");
		if (result!="") {
			setInput("u_enddate",formatDateToHttp(result.substring(0,10)));
			setInput("u_endtime",result.substring(11));
		}
		
		if (isInputEmpty("u_testby")) return false;
		if (isInputEmpty("u_doctorid2")) return false;
		if (isInputEmpty("u_enddate")) return false;
		if (isInputEmpty("u_endtime")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			//window.opener.formSearchNow();
			//window.close();
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
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"' AND DOCNO<>-1 AND DOCSERIES<>-1";
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
	switch (table) {
		case "T1":
			switch (column) {
				case "u_template":
					if (getTableInput(table,column,row)!="") {
						result = page.executeFormattedQuery("select a.u_template, a.u_normalrange, a.u_units, a.u_formula, a.u_formulanormalrange, a.u_formulaunits from u_hislabtesttypecases a where code = '"+getInput("u_type")+"' and a.u_test='"+getTableInput(table,"u_test",row)+"' and a.u_template<>'' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+")) and a.u_template like '"+getTableInput(table,column,row)+"%'");	
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
					if (getTableInput(table,"u_result",row)!="") {
						var normalrange = getTableInput(table,"u_normalrange",row).replace(/Up to/g,"1 - ").replace(/UP TO/g,"1 - ");
						
						var result = parseFloat(getTableInput(table,"u_result",row));
						if (isNaN(result)) result = 0;
						if (normalrange.indexOf('-')!=-1) {
							var normalranges = normalrange.split("-");
							normalranges[0] = parseFloat(normalranges[0].trim());
							if (isNaN(normalranges[0])) normalranges[0] = 0;
							normalranges[1] = parseFloat(normalranges[1].trim());
							if (isNaN(normalranges[1])) normalranges[1] = 0;
							if (result>normalranges[1]) setTableInput(table,"u_level","HIGH",row);
							else if (result<normalranges[0]) setTableInput(table,"u_level","LOW",row);
							else setTableInput(table,"u_level","NORMAL",row);
						}
					} else {
						setTableInput(table,"u_level","LOW",row);
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
					setTestCasesGPSHIS();
					break;
				case "u_requestno":
					setRequestNoFieldAttributesGPSHIS(true);
					clearTable("T4",true);
					clearTable("T5",true);
					clearTable("T6",true);
					if (getInput("u_requestno")!="") {
						return setRequestNoGPSHIS();
					} else {
						setInput("u_refno","");
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_gender","");
						setInput("u_birthdate","");
						setInput("u_age",0);
						setTestCasesGPSHIS();
					}
					break;
				case "u_template":
				case "u_age":
					setTestCasesGPSHIS();
					break;
				case "u_quantity":
				case "u_price":
					computePatientLabTotalGPSHIS(table);
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
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	var result;
	switch (table) {
		case "T1":
			break;
		case "T102":
			switch (column) {
				case "u_template":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedSearch("select REPLACE(u_remarks,'\n','<br>') from u_hislabtesttypenotes where code='"+getTableInput(table,column)+"'");
						setTableInput("T102","remarks",result.replace(/<br>/g,'\n'));
						/*
						result = page.executeFormattedSearch("select u_remarks from u_hislabtesttypenotes where code='"+getTableInput(table,column)+"'");
						setElementHTMLById("divEditorT102",result);
						*/
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
						//computePatientTotalAmountGPSHIS("T1,T2,T3","u_amount","u_vatamount","u_discamount","u_linetotal");
						u_ajaxloadu_hislabtesttypesbysection("df_u_typeT4",element.value,'',":");
						hideAjaxProcess();
					}
					return result;
					break;
				case "u_type":
				case "u_gender":
					if (getInput("docseries")=="-1" && getInput("docno")=="-1" ) {
						return setTestCasesGPSHIS();
					} else {
						//result = validatePatientLabGPSHIS(element,column,table,row);
						//if (result) 
						return setTestCasesGPSHIS();
						//return result;
					}
					break;
				case "u_pricelist":
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount","EXAM");
					break;
				case "u_disccode":
					result = setDiscountData();
					//if (result) result = validatePatientLabGPSHIS(element,"u_type",table,row);
					return result;
					break;
				case "u_doctorid2":
					setElementHTMLById("divEditorT102","");
					u_ajaxloadu_hislabtesttypenotes("df_u_templateT102",getInput("u_type"),element.value,'',":[Select]");
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_isstat":
					resetPatientPricesGPSHIS("","u_amount","u_vatamount","u_discamount","EXAM");
					break;
			}
	}
	return true;
}


function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where u_nursed=1 and docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_requestno":
			if (getInput("u_department")=="") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,b.name as 'Section', a.u_patientname, c.name as 'Test Type' from u_hislabtestrequests a, u_hislabtestrequesttests d, u_hissections b, u_hislabtesttypes c where d.company=a.company and d.branch=a.branch and d.docid=a.docid and c.code=d.u_type and b.code=a.u_department and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O' and d.u_rendered=0")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,b.name as 'Section',a.u_patientname, c.name as 'Test Type' from u_hislabtestrequests a, u_hislabtestrequesttests d, u_hissections b, u_hislabtesttypes c where d.company=a.company and d.branch=a.branch and d.docid=a.docid and c.code=d.u_type and  b.code=a.u_department and a.u_department='"+getInput("u_department")+"' and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O' and d.u_rendered=0")); 
			}
			break;
		default:
			if (Id.substring(0,16)=="df_u_templateT1r") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_template, a.u_normalrange, a.u_units from u_hislabtesttypecases a where code = '"+getInput("u_type")+"' and a.u_test='"+getTableInput("T1","u_test",Id.substring(16))+"' and a.u_template<>'' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+"))")); 
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

function onTableResetRowGPSHIS(table) {
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
		case "T101":
			var targetObjectId = '';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabsubtests' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeEditRowGPSHIS(table,row) {
	var targetObjectId = '';
	switch (table) {
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
	}
	return params;
}

function setTestCasesGPSHIS() {
	var result, data = new Array();
	showAjaxProcess();
	clearTable("T1",true);
	//getInput("u_gender")!="" && 
	if (getInput("u_type")!="" && getInput("u_startdate")!="") {
		result = page.executeFormattedQuery("select a.u_itemcode, b.u_specimen, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test, a.u_normalrange, a.u_formula, a.u_formulanormalrange, a.u_units, a.u_formulaunits, a.u_lis from u_hislabtesttypecases a, u_hislabtesttypes b, u_hischarges c, u_hischargeitems d where d.company=c.company and d.branch=c.branch and d.docid=c.docid and c.company='"+getGlobal("company")+"' and c.branch='"+getGlobal("branch")+"' and c.docno='"+getInput("u_requestno")+"' and d.u_template='"+getInput("u_type")+"' and a.u_itemcode=d.u_itemcode and b.code=a.code and a.code='"+getInput("u_type")+"' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+")) and a.u_mtemplate='"+getInput("u_template")+"' group by a.u_itemcode, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test order by a.u_seq, a.u_seq2, a.u_group");	 
		//alert("select a.u_itemcode, b.u_specimen, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test, a.u_normalrange, a.u_formula, a.u_formulanormalrange, a.u_units, a.u_formulaunits, a.u_lis from u_hislabtesttypecases a, u_hislabtesttypes b, u_hischarges c, u_hischargeitems d where d.company=c.company and d.branch=c.branch and d.docid=c.docid and c.company='"+getGlobal("company")+"' and c.branch='"+getGlobal("branch")+"' and c.docno='"+getInput("u_requestno")+"' and d.u_template='"+getInput("u_type")+"' and a.u_itemcode=d.u_itemcode and b.code=a.code and a.code='"+getInput("u_type")+"' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+getInput("u_age")+")) and a.u_mtemplate='"+getInput("u_template")+"' group by a.u_itemcode, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test order by a.u_itemcode, a.u_seq, a.u_seq2");
		if (result.getAttribute("result")!= "-1") {
			//alert(parseInt(result.getAttribute("result")));
			if (parseInt(result.getAttribute("result"))>0) {
				for (xxxi = 0; xxxi < result.childNodes.length; xxxi++) {
					if (xxxi==0) {
						setInput("u_specimen",result.childNodes.item(xxxi).getAttribute("u_specimen"));	
					}
					
					data["u_itemcode"] = result.childNodes.item(xxxi).getAttribute("u_itemcode");
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
					data["u_lis"] = result.childNodes.item(xxxi).getAttribute("u_lis");
					insertTableRowFromArray("T1",data);
				}
			}
		} else {
			hideAjaxProcess();
			page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
			return false;
		}
	}
	hideAjaxProcess();
	return true;
}

function setRequestNoGPSHIS(){
	var data1 = new Array();
	var data2 = new Array();
	var data3 = new Array();
	var result = page.executeFormattedQuery("select a.u_department, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_payrefno, a.u_doctorid, 'EXAM' as u_type, b.u_type as u_itemcode, c.name as u_itemdesc, '' as u_uom, b.u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, b.u_linetotal, b.u_vatcode, b.u_vatrate, b.u_statperc, b.u_statunitprice, b.u_scdiscamount, b.u_discperc from u_hisrequests a, u_hisrequesttests b, u_hisitems c where c.code=b.u_type and b.company=a.company and b.branch=a.branch and b.docid=a.docid and c.u_template='"+getInput("u_type")+"' and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O' and b.u_renderedqty<b.u_quantity union all select a.u_department, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_payrefno, a.u_doctorid, 'MEDSUP' as u_type, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, b.u_linetotal, b.u_vatcode, b.u_vatrate, 0 as u_statperc, 0 as u_statunitprice, b.u_scdiscamount, b.u_discperc from u_hisrequests a, u_hisrequestmedsups b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O' and b.u_renderedqty<b.u_quantity union all select a.u_department, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_requestdate, a.u_requesttime, a.u_isstat, a.u_prepaid, a.u_payrefno, a.u_doctorid, 'MISC' as u_type, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, b.u_linetotal, b.u_vatcode, b.u_vatrate, 0 as u_statperc, 0 as u_statunitprice, b.u_scdiscamount, b.u_discperc from u_hisrequests a, u_hisrequestmiscs b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_requestno")+"' and (a.u_prepaid=0 or a.u_prepaid=1 and a.u_payrefno<>'') and a.docstatus='O' and b.u_renderedqty<b.u_quantity");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				if (iii==0) {
					setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
					//setInput("u_type",result.childNodes.item(0).getAttribute("u_type"));
					setInput("u_doctorid",result.childNodes.item(0).getAttribute("u_doctorid"));
					setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
					setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
					setInput("u_reqdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_requestdate")));
					setInput("u_reqtime",result.childNodes.item(0).getAttribute("u_requesttime").substring(0,5));
					setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
					setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
					setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
					setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
					setInput("u_age",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
					setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
					setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
					setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
					setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
					setInput("u_payrefno",result.childNodes.item(0).getAttribute("u_payrefno"));
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

					if (result.childNodes.item(0).getAttribute("u_isstat")=="1") checkedInput("u_isstat");
					else uncheckedInput("u_isstat");
				}
				if (result.childNodes.item(iii).getAttribute("u_type")=="EXAM") {
					data1["u_type"] = result.childNodes.item(iii).getAttribute("u_itemcode");
					data1["u_type.text"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
					data1["u_quantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_quantity"),"quantity");
					data1["u_vatcode"] = result.childNodes.item(iii).getAttribute("u_vatcode");
					data1["u_vatrate"] = result.childNodes.item(iii).getAttribute("u_vatrate");
					data1["u_unitprice"] = formatNumber(result.childNodes.item(iii).getAttribute("u_unitprice"),"price");
					data1["u_discperc"] = formatNumber(result.childNodes.item(iii).getAttribute("u_discperc"),"percent");
					data1["u_statperc"] = formatNumber(result.childNodes.item(iii).getAttribute("u_statperc"),"percent");
					data1["u_statunitprice"] = formatNumber(result.childNodes.item(iii).getAttribute("u_statunitprice"),"price");
					data1["u_scdiscamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_scdiscamount"),"amount");
					data1["u_discamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_discamount"),"amount");
					data1["u_vatamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_vatamount"),"amount");
					data1["u_price"] = formatNumber(result.childNodes.item(iii).getAttribute("u_price"),"price");
					data1["u_linetotal"] = formatNumber(result.childNodes.item(iii).getAttribute("u_linetotal"),"amount");
					insertTableRowFromArray("T4",data1);
				} else if (result.childNodes.item(iii).getAttribute("u_type")=="MEDSUP") {
					data2["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
					data2["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
					data2["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
					data2["u_quantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_quantity"),"quantity");
					data2["u_vatcode"] = result.childNodes.item(iii).getAttribute("u_vatcode");
					data2["u_vatrate"] = result.childNodes.item(iii).getAttribute("u_vatrate");
					data2["u_unitprice"] = formatNumber(result.childNodes.item(iii).getAttribute("u_unitprice"),"price");
					data2["u_discperc"] = formatNumber(result.childNodes.item(iii).getAttribute("u_discperc"),"percent");
					data2["u_scdiscamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_scdiscamount"),"amount");
					data2["u_discamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_discamount"),"amount");
					data2["u_vatamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_vatamount"),"amount");
					data2["u_price"] = formatNumber(result.childNodes.item(iii).getAttribute("u_price"),"price");
					data2["u_linetotal"] = formatNumber(result.childNodes.item(iii).getAttribute("u_linetotal"),"amount");
					insertTableRowFromArray("T5",data2);
				} else if (result.childNodes.item(iii).getAttribute("u_type")=="MISC") {
					data3["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
					data3["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
					data2["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
					data3["u_quantity"] = formatNumber(result.childNodes.item(iii).getAttribute("u_quantity"),"quantity");
					data3["u_vatcode"] = result.childNodes.item(iii).getAttribute("u_vatcode");
					data3["u_vatrate"] = result.childNodes.item(iii).getAttribute("u_vatrate");
					data3["u_unitprice"] = formatNumber(result.childNodes.item(iii).getAttribute("u_unitprice"),"price");
					data3["u_discperc"] = formatNumber(result.childNodes.item(iii).getAttribute("u_discperc"),"percent");
					data3["u_scdiscamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_scdiscamount"),"amount");
					data3["u_discamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_discamount"),"amount");
					data3["u_vatamount"] = formatNumber(result.childNodes.item(iii).getAttribute("u_vatamount"),"amount");
					data3["u_price"] = formatNumber(result.childNodes.item(iii).getAttribute("u_price"),"price");
					data3["u_linetotal"] = formatNumber(result.childNodes.item(iii).getAttribute("u_linetotal"),"amount");
					insertTableRowFromArray("T6",data3);
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
			computePatientTotalAmountGPSHIS("T4,T5,T6","u_amount","u_vatamount","u_discamount","u_linetotal","EXAM,MEDSUP,MISC");
		} else {
			setInput("u_refno","");
			setInput("u_patientid","");
			setInput("u_patientname","");
			setInput("u_gender","");
			setInput("u_birthdate","");
			setInput("u_age",0);
			setTestCasesGPSHIS();
			page.statusbar.showError("Invalid Reference No.");	
			return false;
		}
		//setTimeout("setTestCasesGPSHIS()",1000);
		setTestCasesGPSHIS();
	} else {
		setInput("u_refno","");
		setInput("u_patientid","");
		setInput("u_patientname","");
		setInput("u_gender","");
		setInput("u_birthdate","");
		setInput("u_age",0);
		setTestCasesGPSHIS();
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
		enableInput("u_type");
		enableInput("u_reqdate");
		enableInput("u_reqtime");
		enableInput("u_disccode");
		enableInput("u_pricelist");
		//enableInput("u_quantity");
	} else {
		disableInput("u_isstat");
		disableInput("u_department");
		disableInput("u_reftype");
		disableInput("u_refno");
		disableInput("u_type");
		disableInput("u_reqdate");
		disableInput("u_reqtime");
		disableInput("u_disccode");
		disableInput("u_pricelist");
		//disableInput("u_quantity");
	}
}

function reopenGPSHIS() {
	if (isInputEmpty("u_reopenremarks")) return false;
	setInput("docstatus","O");
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
	selectTab("tab1",2);
	showPopupFrame("popupFrameNotesTemplate",true);
}

function u_ajaxloadu_hislabtesttypenotes(p_elementid, p_u_type, p_u_doctorid,p_value,p_filler) {
	http = getHTTPObject(); // We create the HTTP Object
	document.getElementById(p_elementid).innerHTML = "<option>Loading..</option>"
	http.open("GET", "udp.php?ajax=1&objectcode=u_ajaxslu_hislabtesttypenotes&u_type=" + p_u_type + "&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	document.getElementById(p_elementid).innerHTML = http.responseText;
	document.getElementById('ajaxPending').value = "";	
	
}

function OpenLnkBtnu_hisexamcases(targetId) {
	OpenLnkBtn(450,380,'./udo.php?objectcode=u_hisexamcases' + '' + '&targetId=' + targetId ,targetId);
	
}