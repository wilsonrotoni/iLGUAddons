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
		
		switch (getInput("docstatus")) {
			case "Draft":
				if (getPrivate("encoder")!="1" && getPrivate("approver")!="1") {
					page.statusbar.showError("You must be an encoder/approver to save/update as draft this document.");
					return false;
				}
				if (isInputEmpty("u_yr")) return false;
				if (isInputEmpty("u_profitcenter")) return false;
				if (getTableInput("T1","u_itemno")!="")	{
					page.statusbar.showError("An item is currently being added/edited.");
					return false;
				}
				if (getTableInput("T2","u_glacctno")!="")	{
					page.statusbar.showError("A g/l is currently being added/edited.");
					return false;
				}
				break;
			case "For Revision":
			case "Disapproved":
				if (getPrivate("approver")!="1") {
					page.statusbar.showError("You must be an approver to approve/disapprove this document.");
					return false;
				}
				if (isInputEmpty("u_yr")) return false;
				if (isInputEmpty("u_profitcenter")) return false;
				if (isInputEmpty("u_decisionremarks","Decision Remarks is required.",null,"tab1",2)) return false;
				break;
			case "Approved":
				if (getPrivate("approver")!="1") {
					page.statusbar.showError("You must be an approver to approve/disapprove this document.");
					return false;
				}
				if (isInputEmpty("u_yr")) return false;
				if (isInputEmpty("u_profitcenter")) return false;
				break;
		}
		if (isInputEmpty("u_date")) return false;
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
				case "u_itemno":
				case "u_itemname":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemno") result = page.executeFormattedQuery("select code, name, u_uom, u_unitprice from u_lguitems where code = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select code, name, u_uom, u_unitprice from u_lguitems where name like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemno",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemname",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("u_uom"));
								setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_unitprice"));
								setTableInputAmount(table,"u_totalamount",getTableInputNumeric("u_unitprice")*getTableInputNumeric("u_yr"));
							} else {
								setTableInput(table,"u_itemno","");
								setTableInput(table,"u_itemname","");
								setTableInput(table,"u_uom","");
								setTableInputPrice(table,"u_unitprice",0);
								setTableInputAmount(table,"u_totalamount",0);
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemno","");
							setTableInput(table,"u_itemname","");
							setTableInput(table,"u_uom","");
							setTableInputPrice(table,"u_unitprice",0);
							setTableInputAmount(table,"u_totalamount",0);
							page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemno","");
						setTableInput(table,"u_itemname","");
						setTableInput(table,"u_uom","");
						setTableInputPrice(table,"u_unitprice",0);
						setTableInputAmount(table,"u_totalamount",0);
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
						setTableInput(table,"u_yr",getTableInputNumeric(table,"u_m1",row)+getTableInputNumeric(table,"u_m2",row)+getTableInputNumeric(table,"u_m3",row)+getTableInputNumeric(table,"u_m4",row)+getTableInputNumeric(table,"u_m5",row)+getTableInputNumeric(table,"u_m6",row)+getTableInputNumeric(table,"u_m7",row)+getTableInputNumeric(table,"u_m8",row)+getTableInputNumeric(table,"u_m9",row)+getTableInputNumeric(table,"u_m10",row)+getTableInputNumeric(table,"u_m11",row)+getTableInputNumeric(table,"u_m12",row),row);
						setTableInputAmount(table,"u_totalamount",getTableInputNumeric(table,"u_unitprice",row)*getTableInputNumeric(table,"u_yr",row),row);
						computeTotalGPSLGUAcctg();
					} else { 
						setTableInput(table,"u_yr",getTableInputNumeric(table,"u_m1")+getTableInputNumeric(table,"u_m2")+getTableInputNumeric(table,"u_m3")+getTableInputNumeric(table,"u_m4")+getTableInputNumeric(table,"u_m5")+getTableInputNumeric(table,"u_m6")+getTableInputNumeric(table,"u_m7")+getTableInputNumeric(table,"u_m8")+getTableInputNumeric(table,"u_m9")+getTableInputNumeric(table,"u_m10")+getTableInputNumeric(table,"u_m11")+getTableInputNumeric(table,"u_m12"));
						setTableInputAmount(table,"u_totalamount",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_yr"));
					}
					break;
				case "u_yr":
					if (row>0) {
						amount = utils.divide(getTableInputNumeric(table,"u_yr",row),12);
						setTableInput(table,"u_m1",amount,row);
						setTableInput(table,"u_m2",amount,row);
						setTableInput(table,"u_m3",amount,row);
						setTableInput(table,"u_m4",amount,row);
						setTableInput(table,"u_m5",amount,row);
						setTableInput(table,"u_m6",amount,row);
						setTableInput(table,"u_m7",amount,row);
						setTableInput(table,"u_m8",amount,row);
						setTableInput(table,"u_m9",amount,row);
						setTableInput(table,"u_m10",amount,row);
						setTableInput(table,"u_m11",amount,row);
						setTableInput(table,"u_m12",amount,row);
						setTableInputAmount(table,"u_totalamount",getTableInputNumeric(table,"u_unitprice",row)*getTableInputNumeric(table,"u_yr",row),row);
						computeTotalGPSLGUAcctg();
					} else {
						amount = utils.divide(getTableInputNumeric(table,"u_yr"),12);
						setTableInput(table,"u_m1",amount);
						setTableInput(table,"u_m2",amount);
						setTableInput(table,"u_m3",amount);
						setTableInput(table,"u_m4",amount);
						setTableInput(table,"u_m5",amount);
						setTableInput(table,"u_m6",amount);
						setTableInput(table,"u_m7",amount);
						setTableInput(table,"u_m8",amount);
						setTableInput(table,"u_m9",amount);
						setTableInput(table,"u_m10",amount);
						setTableInput(table,"u_m11",amount);
						setTableInput(table,"u_m12",amount);
						setTableInputAmount(table,"u_totalamount",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_yr"));
					}
					break;
				case "u_q1":
				case "u_q2":
				case "u_q3":
				case "u_q4":
					setTableInputAmount(table,"u_yr",getTableInputNumeric(table,"u_q1")+getTableInputNumeric(table,"u_q2")+getTableInputNumeric(table,"u_q3")+getTableInputNumeric(table,"u_q4"));
					break;
				case "u_unitprice":
					setTableInputAmount(table,"u_totalamount",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_yr"));
					break;
			}
			break;
		case "T2":
			switch (column) {
				case "u_glacctno":
				case "u_glacctname":
					if (getTableInput(table,column)!="") {
						if (isInputEmpty("u_profitcenter")) return false;
						if (column=="u_glacctno") result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and u_expclass <>'' and formatcode = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select formatcode, acctname, u_expgroupno, u_expclass from chartofaccounts where budget=1 and u_expclass <>'' and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								//setTableInput(table,"u_expgroupno",result.childNodes.item(0).getAttribute("u_expgroupno"));
								//setTableInput(table,"u_expclass",result.childNodes.item(0).getAttribute("u_expclass"));
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								//setTableInput(table,"u_expgroupno","");
								//setTableInput(table,"u_expclass","");
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							//setTableInput(table,"u_expgroupno","");
							//setTableInput(table,"u_expclass","");
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						//setTableInput(table,"u_expgroupno","");
						//setTableInput(table,"u_expclass","");
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
					}
					break;
				case "u_slcode":
				case "u_sldesc":
					if (getTableInput(table,column)!="") {
						if (isInputEmpty("u_profitcenter")) return false;
						if (isTableInputEmpty("T2","u_glacctno")) return false;
						if (column=="u_slcode") result = page.executeFormattedQuery("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput(table,"u_glacctno")+"' and u_code = '"+getTableInput(table,column)+"'");	
						else  result = page.executeFormattedQuery("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput(table,"u_glacctno")+"' and u_description like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_slcode",result.childNodes.item(0).getAttribute("u_code"));
								setTableInput(table,"u_sldesc",result.childNodes.item(0).getAttribute("u_description"));
							} else {
								setTableInput(table,"u_slcode","");
								setTableInput(table,"u_sldesc","");
								page.statusbar.showError("Invalid S/L.");	
								return false;
							}
						} else {
							setTableInput(table,"u_slcode","");
							setTableInput(table,"u_sldesc","");
							page.statusbar.showError("Error retrieving subsidiary record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_slcode","");
						setTableInput(table,"u_sldesc","");
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
						computeTotalGPSLGUAcctg();
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
			switch (column) {
				case "u_projcode":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, name from u_lguprojs where u_profitcenter='"+getInput("u_profitcenter")+"' and code = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_projcode",result.childNodes.item(0).getAttribute("code"));
								setInput("u_projname",result.childNodes.item(0).getAttribute("name"));
							} else {
								//setTableInput(table,"u_expgroupno","");
								//setTableInput(table,"u_expclass","");
								setInput("u_projcode","");
								setInput("u_projname","");
								page.statusbar.showError("Invalid Program/Project.");	
								return false;
							}
						} else {
							//setTableInput(table,"u_expgroupno","");
							//setTableInput(table,"u_expclass","");
							setInput("u_projcode","");
							setInput("u_projname","");
							page.statusbar.showError("Error retrieving project record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						//setTableInput(table,"u_expgroupno","");
						//setTableInput(table,"u_expclass","");
						setInput("u_glacctno","");
						setInput("u_glacctname","");
					}
					break;
			}
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
					/*setInput("code",getInput("u_yr") + "-" + getInput("u_profitcenter"));
					var code = page.executeFormattedSearch("select code from u_lgubudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}*/
					break;
				case "u_profitcenter":
					setInput("u_profitcentername",getInputSelectedText("u_profitcenter"));
					/*setInput("code",getInput("u_yr") + "-" + getInput("u_profitcenter"));
					var code = page.executeFormattedSearch("select code from u_lgubudget where code='"+ getInput("code")+"'");
					if (code!="") {
						setKey("keys",getInput("code"));
						formEdit();
						return false;
					}
					*/
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
		case "df_u_projcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lguprojs where u_profitcenter='"+getInput("u_profitcenter")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Program/Project`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_itemnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lguitems")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item No.`Item Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_itemnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_lguitems")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnoT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where postable=1 and budget=1 and u_expclass <>''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where postable=1 and budget=1 and u_expclass <>''")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_slcodeT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_code, u_description from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput("T2","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_sldescT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_description, u_code  from u_lgupcsubsidiaryaccts where code='"+getInput("u_profitcenter")+"' and u_glacctno='"+getTableInput("T2","u_glacctno")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Code")); 			
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
			if (!isTableInputUnique(table,"u_itemno")) return false;
			if (isTableInputEmpty(table,"u_itemno")) return false;
			if (isTableInputEmpty(table,"u_itemname")) return false;
			break;
		case "T2":
			//if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
		case "T2": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"u_itemno")) return false;
			if (isTableInputEmpty(table,"u_itemno")) return false;
			if (isTableInputEmpty(table,"u_itemname")) return false;
			break;
		case "T2":
			//if (!isTableInputUnique(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_glacctname")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
		case "T2": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
		case "T2": computeTotalGPSLGUAcctg(); break;
	}
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
		case "T2":
			params["focus"] = false;
			if (elementFocused.substring(0,9)=="df_u_yrT2") {
				focusTableInput(table,"u_yr",row);
			} else if (elementFocused.substring(0,9)=="df_u_m1T2") {
				focusTableInput(table,"u_m1",row);
			} else if (elementFocused.substring(0,9)=="df_u_m2T2") {
				focusTableInput(table,"u_m2",row);
			} else if (elementFocused.substring(0,9)=="df_u_m3T2") {
				focusTableInput(table,"u_m3",row);
			} else if (elementFocused.substring(0,9)=="df_u_m4T2") {
				focusTableInput(table,"u_m4",row);
			} else if (elementFocused.substring(0,9)=="df_u_m5T2") {
				focusTableInput(table,"u_m5",row);
			} else if (elementFocused.substring(0,9)=="df_u_m6T2") {
				focusTableInput(table,"u_m6",row);
			} else if (elementFocused.substring(0,9)=="df_u_m7T2") {
				focusTableInput(table,"u_m7",row);
			} else if (elementFocused.substring(0,9)=="df_u_m8T2") {
				focusTableInput(table,"u_m8",row);
			} else if (elementFocused.substring(0,9)=="df_u_m9T2") {
				focusTableInput(table,"u_m9",row);
			} else if (elementFocused.substring(0,10)=="df_u_m10T2") {
				focusTableInput(table,"u_m10",row);
			} else if (elementFocused.substring(0,10)=="df_u_m11T2") {
				focusTableInput(table,"u_m11",row);
			} else if (elementFocused.substring(0,10)=="df_u_m12T2") {
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
			totalamount += getTableInputNumeric("T1","u_totalamount",i);
		}
	}

	rc =  getTableRowCount("T2");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			totalamount += getTableInputNumeric("T2","u_yr",i);
		}
	}

	setInputAmount("u_totalamount",totalamount);
}

function u_forApprovalGPSLGUAcctg() {
	setInput("docstatus","For Approval");
	formSubmit();
}

function u_approvedGPSLGUAcctg() {
	setInput("docstatus","Approved");
	formSubmit();
}

function u_disapprovedGPSLGUAcctg() {
	setInput("docstatus","Disapproved");
	formSubmit();
}

function u_revisedGPSLGUAcctg() {
	setInput("docstatus","For Revision");
	formSubmit();
}
