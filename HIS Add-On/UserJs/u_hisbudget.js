// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
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
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');
page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');


function OpenLnkBtnu_GLDeptBudget(targetId) {
	if (targetId.indexOf("T1r")!="-1") {
		if (getTableRowStatus("T1",targetId.substring(targetId.indexOf("T1r")+3,targetId.length))!="N") {
			OpenLnkBtn(800,460,'./udo.php?objectcode=u_deptglbudget&targetId=' + targetId ,targetId);
		} else page.statusbar.showWarning("Link is available after page have been added/updated.");
	} else page.statusbar.showWarning("Click the link on specific row instead.");
//	getInput('reftype'+targetId.substring(targetId.indexOf("T1r"),targetId.length))

		
}

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_yr")) return false;
		if (isInputEmpty("u_department")) return false;
						 
		if (getTableInput("T1","u_glacctno")!="")	{
			page.statusbar.showError("An item is currently being added/edited.");
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
	var amount=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_glacctno":
				case "u_glacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_glacctno") result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where (formatcode like '4%' or formatcode like '5%' or formatcode like '6%') and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where (formatcode like '4%' or formatcode like '5%' or formatcode like '6%') and  postable=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
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
					setTableInputAmount(table,"u_yr",getTableInputNumeric(table,"u_m1")+getTableInputNumeric(table,"u_m2")+getTableInputNumeric(table,"u_m3")+getTableInputNumeric(table,"u_m4")+getTableInputNumeric(table,"u_m5")+getTableInputNumeric(table,"u_m6")+getTableInputNumeric(table,"u_m7")+getTableInputNumeric(table,"u_m8")+getTableInputNumeric(table,"u_m9")+getTableInputNumeric(table,"u_m10")+getTableInputNumeric(table,"u_m11")+getTableInputNumeric(table,"u_m12"));
					break;
				case "u_yr":
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
			switch(column) {
				case "u_yr":
					setInput("code",getInput("u_yr") + "-" + getInput("u_department"));
					var code = page.executeFormattedSearch("select code from u_hisbudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					break;
				case "u_department":
					setInput("name",getInputSelectedText("u_department"));
					setInput("code",getInput("u_yr") + "-" + getInput("u_department"));
					var code = page.executeFormattedSearch("select code from u_hisbudget where code='"+ getInput("code")+"'");
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

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(id,params) {
	switch (id) {	
		case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where (formatcode like '4%' or formatcode like '5%' or formatcode like '6%') and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where (formatcode like '4%' or formatcode like '5%' or formatcode like '6%') and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onElementLnkBtnGetParamsGPSHIS(id,params) {
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




function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
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

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

