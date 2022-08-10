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
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
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
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	try {
		if (getVar("formSubmitAction")=="a") {
			var rc = window.opener.getTableSelectedRow("T1");
			if (window.opener.getInput("u_requestdate")!=getInput("u_requestdate")) {
				alert("You cannot request if listing is not current date.");
				window.close();
			}
			setInput("u_department",window.opener.getTableInput("T1","u_department",rc));
			setInput("u_reftype",window.opener.getTableInput("T1","u_reftype",rc));
			setInput("u_refno",window.opener.getTableInput("T1","u_refno",rc));
			setInput("u_patientid",window.opener.getTableInput("T1","u_patientid",rc));
			setInput("u_patientname",window.opener.getTableInput("T1","u_patientname",rc));
			setInput("u_bedno",window.opener.getTableInput("T1","u_bedno",rc));
			setInput("u_religion",window.opener.getTableInput("T1","u_religion",rc));
			setInput("u_age",window.opener.getTableInput("T1","u_age",rc));
			setInput("u_height_ft",window.opener.getTableInput("T1","u_height_ft",rc));
			setInput("u_height_in",window.opener.getTableInput("T1","u_height_in",rc));
			setInputAmount("u_weight_kg",window.opener.getTableInput("T1","u_weight_kg",rc));
			if (window.opener.getInput("u_meal")!="") {
				setInput("u_meal",window.opener.getInput("u_meal"));
			}
		}
	} catch (theError) {
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_requestdate")) return false;
		if (isInputEmpty("u_requesttime")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_religion")) return false;
		if (isInputEmpty("u_meal")) return false;
		if (isInputEmpty("u_diettype")) return false;
		
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			window.opener.formSearchNow();
			window.close();
		} catch (theError) {
		}
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
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T2":
			switch(column) {
				case "u_icdcode":
				case "u_icddesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_icdcode") {
							result = page.executeFormattedQuery("select code, name from u_hisicds where code='"+getTableInput(table,"u_icdcode")+"'");	 
						} else {
							result = page.executeFormattedQuery("select code, name from u_hisicds where name='"+getTableInput(table,"u_icddesc")+"'");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_icdcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_icddesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_icdcode","");
								setTableInput(table,"u_icddesc","");
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
					}
					break;
			}
			break;
		case "T3":
			switch(column) {
				case "u_rvscode":
				case "u_rvsdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_rvscode") {
							result = page.executeFormattedQuery("select code, name, u_rvu from u_hisrvs where code='"+getTableInput(table,"u_rvscode")+"'");	 
						} else {
							result = page.executeFormattedQuery("select code, name, u_rvu from u_hisrvs where name='"+getTableInput(table,"u_rvsdesc")+"'");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_rvscode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_rvsdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_rvu",result.childNodes.item(0).getAttribute("u_rvu"));
							} else {
								setTableInput(table,"u_rvscode","");
								setTableInput(table,"u_rvsdesc","");
								setTableInput(table,"u_rvu",0);
								if (column=="u_rvscode") {
									page.statusbar.showError("Invalid RVS No.");	
								} else {
									page.statusbar.showError("Invalid RVS Description");	
								}
								return false;
							}
						} else {
							setTableInput(table,"u_rvscode","");
							setTableInput(table,"u_rvsdesc","");
							setTableInput(table,"u_rvu",0);
							if (column=="u_rvscode") {
								page.statusbar.showError("Error retrieving RVS No. Try Again, if problem persists, check the connection.");	
							} else {
								page.statusbar.showError("Error retrieving RVS Description. Try Again, if problem persists, check the connection.");	
							}
							return false;
						}
					} else {
						setTableInput(table,"u_rvscode","");
						setTableInput(table,"u_rvsdesc","");
						setTableInput(table,"u_rvu",0);
					}
					break;		
			}
			break;
		default:
			switch(column) {
				case "u_refno":
					if (getInput("u_refno")!="") {
						if (getInput("u_reftype")=="IP") {
							result = page.executeFormattedQuery("select a.u_patientid, a.u_patientname, b.u_birthdate, b.u_gender from u_hisips a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' and a.u_icdcode=''");	 
						} else {
							result = page.executeFormattedQuery("select a.u_patientid, a.u_patientname, b.u_birthdate, b.u_gender from u_hisops a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' and a.u_icdcode=''");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
					}
					break;
				case "u_icdcode":
					if (getInput("u_icdcode")!="") {
						result = page.executeFormattedQuery("select code, name from u_hisicds where code='"+getInput("u_icdcode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_icdcode",result.childNodes.item(0).getAttribute("code"));
								setInput("u_icddesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_icddesc","");
								page.statusbar.showError("Invalid ICD Code.");	
								return false;
							}
						} else {
							setInput("u_icddesc","");
							page.statusbar.showError("Error retrieving ICD Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_icdcode","");
						setInput("u_icddesc","");
					}
					break;
				case "u_rvscode":
					if (getInput("u_rvscode")!="") {
						result = page.executeFormattedQuery("select code, name, u_rvu from u_hisrvs where code='"+getInput("u_rvscode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_rvscode",result.childNodes.item(0).getAttribute("code"));
								setInput("u_rvsdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_rvu",result.childNodes.item(0).getAttribute("u_rvu"));
							} else {
								setInput("u_rvscode","");
								setInput("u_rvsdesc","");
								setInput("u_rvu",0);
								page.statusbar.showError("Invalid RVS No.");	
								return false;
							}
						} else {
							setInput("u_rvscode","");
							setInput("u_rvsdesc","");
							setInput("u_rvu",0);
							page.statusbar.showError("Error retrieving RVS No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_rvscode","");
						setInput("u_rvsdesc","");
						setInput("u_rvu",0);
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
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where u_icdcode=''")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where u_icdcode=''")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_icdcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisicds where u_level>2")); 
			break;
		case "df_u_rvscode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisrvs")); 
			break;
		case "df_u_icdcodeT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisicds where u_level>2")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ICD Code`ICD Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_icddescT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisicds where u_level>2")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ICD Description`ICD Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_rvscodeT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name,u_rvu from u_hisrvs")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("RVS No.`RVS Description`RVU")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`5")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_rvsdescT3":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code,u_rvu from u_hisrvs")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("RVS Description`RVS No.`RVU")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15`5")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getVar("formSubmitAction")=="a") {
				page.statusbar.showWarning("Please add the document before attaching files.");	
				return false;
			}
			uploadAttachment();
			return false;
			break;	
		case "T2":
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_rvscode")) return false;
			if (isTableInputEmpty(table,"u_rvsdesc")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setICDGPSHIS();
			break;
		case "T3":
			setMaxRVSGPSHIS();
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_rvscode")) return false;
			if (isTableInputEmpty(table,"u_rvsdesc")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setICDGPSHIS();
			break;
		case "T3":
			setMaxRVSGPSHIS();
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setICDGPSHIS();
			break;
		case "T3":
			setMaxRVSGPSHIS();
			break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			document.images['PictureImg'].src = getTableInput("T1","u_filepath",row);			
			break;
	}
	return params;
}

function setICDGPSHIS() {
	var rc =  getTableRowCount("T2"), icdcode="", icddesc="";
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			if (icdcode!="") {
				icdcode += ", "; 	
				icddesc += ", "; 	
			}
			icdcode += getTableInput("T2","u_icdcode",i);
			icddesc += getTableInput("T2","u_icddesc",i);
		}
	}
	setInput("u_icdcode",icdcode);	
	setInput("u_icddesc",icddesc);	
	
}

function setMaxRVSGPSHIS() {
	var rc =  getTableRowCount("T3"), rvscode="", rvsdesc="", rvu=0, procedures="";
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			if (getTableInputNumeric("T3","u_rvu",i)>rvu) {
				rvscode = getTableInput("T3","u_rvscode",i);
				rvsdesc = getTableInput("T3","u_rvsdesc",i);
				rvu = getTableInputNumeric("T3","u_rvu",i);
			}
		}
		if (procedures!="") {
			procedures += ", "; 	
		}
		procedures += getTableInput("T3","u_rvsdesc",i);
	}
	setInput("u_procedures",procedures);	
	setInput("u_rvscode",rvscode);	
	setInput("u_rvsdesc",rvsdesc);	
	setInput("u_rvu",rvu);	
	
}

function uploadAttachment() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Medical Records/" + getInput("docno") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function deleteAttachmentGPSHIS() {
	var rc = getTableSelectedRow("T1");	
	if (getTableSelectedRow("T1")>0) {
		if (ajaxdeleteattachment(getTableInput("T1","u_filepath",rc))) {
			formEdit();
		}
	} else page.statusbar.showWarning("No selected attachment to delete.");
}

function u_moveRowUpICDGPSHIS() {
	var table = "T2";
	var rc = getTableRowCount(table),sr = 0;	
	if (rc==0) return;
	sr = getTableSelectedRow(table);
	var keys,u_icdcode,u_icddesc;
	var keys2,u_icdcode2,u_icddesc2;
	if (sr!=1) {
		keys = getTableKey(table,"keys",sr);
		u_icdcode = getTableInput(table,"u_icdcode",sr);
		u_icddesc = getTableInput(table,"u_icddesc",sr);

		keys2 = getTableKey(table,"keys",sr - 1);
		u_icdcode2 = getTableInput(table,"u_icdcode",sr - 1);
		u_icddesc2 = getTableInput(table,"u_icddesc",sr - 1);
		
		setTableKey(table,"keys",keys,sr - 1);
		setTableInput(table,"u_icdcode",u_icdcode,sr - 1);
		setTableInput(table,"u_icddesc",u_icddesc,sr - 1);
		
		setTableKey(table,"keys",keys2,sr);
		setTableInput(table,"u_icdcode",u_icdcode2,sr);
		setTableInput(table,"u_icddesc",u_icddesc2,sr);
			
		selectTableRow(table,sr - 1);
		
		setICDGPSHIS();
	}			
}

function u_moveRowDnICDGPSHIS() {
	var table = "T2";
	var rc = getTableRowCount(table),sr=0;
	if (rc==0) return;
	sr = getTableSelectedRow(table);	
	var keys,u_icdcode,u_icddesc;
	var keys2,u_icdcode2,u_icddesc2;
	if (sr!=rc) {
		keys = getTableKey(table,"keys",sr);
		u_icdcode = getTableInput(table,"u_icdcode",sr);
		u_icddesc = getTableInput(table,"u_icddesc",sr);
		
		keys2 = getTableKey(table,"keys",sr + 1);
		u_icdcode2 = getTableInput(table,"u_icdcode",sr + 1);
		u_icddesc2 = getTableInput(table,"u_icddesc",sr + 1);

		setTableKey(table,"keys",keys,sr + 1);
		setTableInput(table,"u_icdcode",u_icdcode,sr + 1);
		setTableInput(table,"u_icddesc",u_icddesc,sr + 1);
		
		setTableKey(table,"keys",keys2,sr);
		setTableInput(table,"u_icdcode",u_icdcode2,sr);
		setTableInput(table,"u_icddesc",u_icddesc2,sr);
			
		selectTableRow(table,sr + 1);
		
		setICDGPSHIS();
	}	
	
}
