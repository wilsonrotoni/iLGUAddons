// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
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
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("name")) return false;
		if (getInput("u_hmo")=="2") {
			if (isInputEmpty("u_glacctno")) return false;
			if (isInputEmpty("u_glacctname")) return false;
		} else if (getInput("u_hmo")=="7") {
			if (isInputEmpty("u_glacctno")) return false;
			if (isInputEmpty("u_glacctname")) return false;
			if (isInputEmpty("u_department")) return false;
		} else {
			if (isInputEmpty("u_glacctno")) return false;
			if (isInputEmpty("u_glacctname")) return false;
			if (isInputEmpty("u_group")) return false;
		}
		if (getTableInput("T1","u_icdcode")!="") {
			page.statusbar.showError("A package item is being added/edited.");
			return false;
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

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
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
									if (window.confirm("Code entered is not a valid ICD. Continue?")==false) {
										setTableInput(table,"u_icddesc","");
										page.statusbar.showError("Invalid ICD Code.");	
										return false;
									} else {
										setTableInput(table,"u_icddesc",getTableInput(table,"u_icdcode"));
										focusTableInput(table,"u_amount");
										return true;
									}
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
		default:
			switch(column) {
				case "name":
					if (getInput("name") != "" && getInput("u_hmo")==6) {
						result = page.executeFormattedQuery("select groupname from customergroups where groupname='"+getInput("name")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("name",result.childNodes.item(0).getAttribute("groupname"));
							} else {
								setInput("name","");
								page.statusbar.showError("Invalid Customer Group Name.");	
								return false;
							}
						} else {
							setInput("name","");
							page.statusbar.showError("Error retrieving Customer Group. Try Again, if problem persists, check the connection.");	
							return false;
						}
					}
					break;
				case "u_glacctno":
					if (getInput("u_glacctno") != "") { 
						if (getInput("u_hmo")!="2" && getInput("u_hmo")!="7")	{
							result = ajaxxmlvalidatechartofaccounts(element.value,"POSTABLE:1;CTRLACCT:1;ASSET");
							if (result.getAttribute("result") == '0') {
								setStatusMsg('Invalid Control Account!');
								return false;
							}	
						} else {
							result = ajaxxmlvalidatechartofaccounts(element.value,"POSTABLE:1;CTRLACCT:0");
							if (result.getAttribute("result") == '0') {
								setStatusMsg('Invalid G/L Account!');
								return false;
							}	
						}
						setInput("u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
						setInput("u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setInput("u_glacctname","");
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
		default:
			switch (column) {
				case "u_series":
					if (getInput("u_series")!=-1) {
						switch (getInputSelectedText("u_series")) {
							case "National": setInput("u_hmo",0); break;
							case "HMO": setInput("u_hmo",1); break;
							case "LGU": setInput("u_hmo",3); break;
							case "Company": setInput("u_hmo",4); break;
							case "Collector": setInput("u_hmo",5); break;
							case "Discount": setInput("u_hmo",2); break;
							case "Others": setInput("u_hmo",6); break;
							case "Expense": setInput("u_hmo",7); break;
						}
					}
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
				case "u_hmo":
					if (getInput("u_series")!=-1) {
						switch (getInput("u_hmo")) {
							case "0": setInputSelectedText("u_series","National"); break;
							case "1": setInputSelectedText("u_series","HMO"); break;
							case "2": setInputSelectedText("u_series","Discount"); break;
							case "3": setInputSelectedText("u_series","LGU"); break;
							case "4": setInputSelectedText("u_series","Company"); break;
							case "5": setInputSelectedText("u_series","Collector"); break;
							case "6": setInputSelectedText("u_series","Others"); break;
							case "7": setInputSelectedText("u_series","Expense"); break;
						}
						setDocNo(true,"u_series","code");
					}
					
					if (getInput("u_hmo")!=2) {
						disableInput("u_priority");
					} else {
						enableInput("u_priority");
					}

					if (getInput("u_hmo")==6) {
						setInput("name","");
					}

					setInput("u_glacctno","");
					setInput("u_glacctname","");
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_glacctno":
			if (getInput("u_hmo")!="2" && getInput("u_hmo")!="7")	params["params"] = "POSTABLE:1;CTRLACCT:1;ASSET";
			else params["params"] = "POSTABLE:1;CTRLACCT:0";
			break;
		case "df_u_icdcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisicds where u_level>2")); 
			break;
		case "df_u_icddescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisicds where u_level>2")); 
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hishealthincts":
			params["keys"] = getTableInput("T101","code",getTableSelectedRow("T101"))
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_icdcode");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T101":
			var targetObjectId = '';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hishealthincts' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_icdcode")) return false;
			if (isTableInputEmpty(table,"u_icddesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
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
			targetObjectId = 'u_hishealthincts';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hishealthincts' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

