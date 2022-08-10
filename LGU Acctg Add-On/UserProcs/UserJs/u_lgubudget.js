// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUAcctg');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctg');
page.elements.events.add.validate('onElementValidateGPSLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctg');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
page.elements.events.add.change('onElementChangeGPSLGUAcctg');
//page.elements.events.add.click('onElementClickGPSLGUAcctg');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctg');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctg');
page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSLGUAcctg');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');


function OpenLnkBtnu_GLDeptBudget(targetId) {
	if (targetId.indexOf("T1r")!="-1") {
		if (getTableRowStatus("T1",targetId.substring(targetId.indexOf("T1r")+3,targetId.length))!="N") {
			OpenLnkBtn(800,460,'./udo.php?objectcode=u_profitcenterglbudget&targetId=' + targetId ,targetId);
		} else page.statusbar.showWarning("Link is available after page have been added/updated.");
	} else page.statusbar.showWarning("Click the link on specific row instead.");
//	getInput('reftype'+targetId.substring(targetId.indexOf("T1r"),targetId.length))

		
}

function onPageLoadGPSLGUAcctg() {
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	if (action=="a" || action=="sc") {
		
		if (isInputEmpty("u_yr")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
						 
		if (getTableInput("T1","u_glacctno")!="")	{
			page.statusbar.showError("An item is currently being added/edited.");
			return false;
		}
		
	}
	return true;
}

function onCFLGPSLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctg() {
}

function onElementFocusGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctg(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctg(element,column,table,row) {
	var amount=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_glacctno":
				case "u_glacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_glacctno") result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and  postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_expgroupno",result.childNodes.item(0).getAttribute("u_expgroupno"));
								setTableInput(table,"u_expclass",result.childNodes.item(0).getAttribute("u_expclass"));
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_expgroupno","");
								setTableInput(table,"u_expclass","");
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_expgroupno","");
							setTableInput(table,"u_expclass","");
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_expgroupno","");
						setTableInput(table,"u_expclass","");
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
					}
					break;
				case "u_m1":
				case "u_m2":
				case "u_m3":
				case "u_m4":
				case "u_m5":
				case "u_m6":
				case "u_m7":
				case "u_m8":
				case "u_m9":
				case "u_m10":
				case "u_m11":
				case "u_m12":
					if (row>0) {
						setTableInputAmount(table,"u_yr",getTableInputNumeric(table,"u_m1",row)+getTableInputNumeric(table,"u_m2",row)+getTableInputNumeric(table,"u_m3",row)+getTableInputNumeric(table,"u_m4",row)+getTableInputNumeric(table,"u_m5",row)+getTableInputNumeric(table,"u_m6",row)+getTableInputNumeric(table,"u_m7",row)+getTableInputNumeric(table,"u_m8",row)+getTableInputNumeric(table,"u_m9",row)+getTableInputNumeric(table,"u_m10",row)+getTableInputNumeric(table,"u_m11",row)+getTableInputNumeric(table,"u_m12",row),row);
					} else { 
						setTableInputAmount(table,"u_yr",getTableInputNumeric(table,"u_m1")+getTableInputNumeric(table,"u_m2")+getTableInputNumeric(table,"u_m3")+getTableInputNumeric(table,"u_m4")+getTableInputNumeric(table,"u_m5")+getTableInputNumeric(table,"u_m6")+getTableInputNumeric(table,"u_m7")+getTableInputNumeric(table,"u_m8")+getTableInputNumeric(table,"u_m9")+getTableInputNumeric(table,"u_m10")+getTableInputNumeric(table,"u_m11")+getTableInputNumeric(table,"u_m12"));
					}
					break;
				case "u_yr":
					if (row>0) {
						amount = utils.divide(getTableInputNumeric(table,"u_yr",row),12);
						setTableInputAmount(table,"u_m1",amount,row);
						setTableInputAmount(table,"u_m2",amount,row);
						setTableInputAmount(table,"u_m3",amount,row);
						setTableInputAmount(table,"u_m4",amount,row);
						setTableInputAmount(table,"u_m5",amount,row);
						setTableInputAmount(table,"u_m6",amount,row);
						setTableInputAmount(table,"u_m7",amount,row);
						setTableInputAmount(table,"u_m8",amount,row);
						setTableInputAmount(table,"u_m9",amount,row);
						setTableInputAmount(table,"u_m10",amount,row);
						setTableInputAmount(table,"u_m11",amount,row);
						setTableInputAmount(table,"u_m12",amount,row);
						computeTotalGPSLGUAcctg();
					} else {
						amount = utils.divide(getTableInputNumeric(table,"u_yr"),12);
						setTableInputAmount(table,"u_m1",amount);
						setTableInputAmount(table,"u_m2",amount);
						setTableInputAmount(table,"u_m3",amount);
						setTableInputAmount(table,"u_m4",amount);
						setTableInputAmount(table,"u_m5",amount);
						setTableInputAmount(table,"u_m6",amount);
						setTableInputAmount(table,"u_m7",amount);
						setTableInputAmount(table,"u_m8",amount);
						setTableInputAmount(table,"u_m9",amount);
						setTableInputAmount(table,"u_m10",amount);
						setTableInputAmount(table,"u_m11",amount);
						setTableInputAmount(table,"u_m12",amount);
					}
					break;
				case "u_q1":
				case "u_q2":
				case "u_q3":
				case "u_q4":
					setTableInputAmount(table,"u_yr",getTableInputNumeric(table,"u_q1")+getTableInputNumeric(table,"u_q2")+getTableInputNumeric(table,"u_q3")+getTableInputNumeric(table,"u_q4"));
					break;
			}
			break;
		default:
			/*switch(column) {
			}*/
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_yr":
					setInput("code",getInput("u_yr") + "-" + getInput("u_profitcenter"));
					var code = page.executeFormattedSearch("select code from u_lgubudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					break;
				case "u_profitcenter":
					setInput("name",getInputSelectedText("u_profitcenter"));
					setInput("code",getInput("u_yr") + "-" + getInput("u_profitcenter"));
					var code = page.executeFormattedSearch("select code from u_lgubudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where u_expclass <>'' and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where u_expclass <>'' and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onElementLnkBtnGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		default:
			if (id.length>10) {
				switch (id.substring(5,8)) {
					case "m1T":
					case "m2T":
					case "m3T":
					case "m4T":
					case "m5T":
					case "m6T":
					case "m7T":
					case "m8T":
					case "m9T":
					case "m10":
					case "m11":
					case "m12":
					case "yrT":
						var code = getInput("code") + "-" + getInput('u_glacctno'+id.substring(id.indexOf("T1r"),id.length)) + "-" + id.substring(5,7);
						params["keys"] = code;
						break;
				}
			}
			break;
	}
	return params;
}




function onTableResetRowGPSLGUAcctg(table) {
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			if (elementFocused.substring(0,9)=="df_u_yrT1") {
				focusTableInput(table,"u_yr",row);
			} else if (elementFocused.substring(0,9)=="df_u_m1T1") {
				focusTableInput(table,"u_m1",row);
			} else if (elementFocused.substring(0,9)=="df_u_m2T1") {
				focusTableInput(table,"u_m2",row);
			} else if (elementFocused.substring(0,9)=="df_u_m3T1") {
				focusTableInput(table,"u_m3",row);
			} else if (elementFocused.substring(0,9)=="df_u_m4T1") {
				focusTableInput(table,"u_m4",row);
			} else if (elementFocused.substring(0,9)=="df_u_m5T1") {
				focusTableInput(table,"u_m5",row);
			} else if (elementFocused.substring(0,9)=="df_u_m6T1") {
				focusTableInput(table,"u_m6",row);
			} else if (elementFocused.substring(0,9)=="df_u_m7T1") {
				focusTableInput(table,"u_m7",row);
			} else if (elementFocused.substring(0,9)=="df_u_m8T1") {
				focusTableInput(table,"u_m8",row);
			} else if (elementFocused.substring(0,9)=="df_u_m9T1") {
				focusTableInput(table,"u_m9",row);
			} else if (elementFocused.substring(0,10)=="df_u_m10T1") {
				focusTableInput(table,"u_m10",row);
			} else if (elementFocused.substring(0,10)=="df_u_m11T1") {
				focusTableInput(table,"u_m11",row);
			} else if (elementFocused.substring(0,10)=="df_u_m12T1") {
				focusTableInput(table,"u_m12",row);
			}
			
			break;
	}
	return params;
}

function computeTotalGPSLGUAcctg() {
	var rc = 0, ps=0,mooe=0,fe=0,co=0,totalamount=0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamount += getTableInputNumeric("T1","u_yr",i);
			switch (getTableInput("T1","u_expclass",i)) {
				case "PS": ps += getTableInputNumeric("T1","u_yr",i); break;
				case "MOOE": mooe += getTableInputNumeric("T1","u_yr",i); break;
				case "FE": fe += getTableInputNumeric("T1","u_yr",i); break;
				case "CO": co += getTableInputNumeric("T1","u_yr",i); break;
			}
		}
	}

	setInputAmount("u_totalps",ps);
	setInputAmount("u_totalmooe",mooe);
	setInputAmount("u_totalfe",fe);
	setInputAmount("u_totalco",co);
	setInputAmount("u_totalamount",totalamount);
}


