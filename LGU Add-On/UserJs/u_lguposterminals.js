// page events
//page.events.add.load('onPageLoadGPSLGU');
//page.events.add.resize('onPageResizeGPSLGU');
//page.events.add.submit('onPageSubmitGPSLGU');
//page.events.add.cfl('onCFLGPSLGU');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGU');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGU');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGU');
//page.elements.events.add.keydown('onElementKeyDownGPSLGU');
page.elements.events.add.validate('onElementValidateGPSLGU');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGU');
//page.elements.events.add.changing('onElementChangingGPSLGU');
//page.elements.events.add.change('onElementChangeGPSLGU');
//page.elements.events.add.click('onElementClickGPSLGU');
//page.elements.events.add.cfl('onElementCFLGPSLGU');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGU');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGU');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGU');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGU');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGU');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGU');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGU');
//page.tables.events.add.delete('onTableDeleteRowGPSLGU');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGU');
//page.tables.events.add.select('onTableSelectRowGPSLGU');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSLGU');

function onPageLoadGPSLGU() {
}

function onPageResizeGPSLGU(width,height) {
}

function onPageSubmitGPSLGU(action) {
	return true;
}

function onCFLGPSLGU(Id) {
	return true;
}

function onCFLGetParamsGPSLGU(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGU() {
}

function onElementFocusGPSLGU(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGU(element,event,column,table,row) {
}

function onElementValidateGPSLGU(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_penaltycode":
				case "u_penaltydesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_penaltycode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"' and u_penalty=1");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%' and u_penalty=1");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_penaltycode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_penaltydesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_penaltycode","");
								setTableInput(table,"u_penaltydesc","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_penaltycode","");
							setTableInput(table,"u_penaltydesc","");
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_penaltycode","");
						setTableInput(table,"u_penaltydesc","");
					}						
					break;
                                        
                                case "u_bglcode":
					if (getTableInput(table,column)!="") {
						
                                                if (getTableInput(table,"u_bglcode").length==8) {
                                                        var s1="",s2="",s3="",s4="";
                                                        s1 = getTableInput(table,"u_bglcode").substr(0,1);
                                                        s2 = getTableInput(table,"u_bglcode").substr(1,2);
                                                        s3 = getTableInput(table,"u_bglcode").substr(3,2);
                                                        s4 = getTableInput(table,"u_bglcode").substr(5,3);
                                                        setTableInput(table,"u_bglcode",s1+"-"+s2+"-"+s3+"-"+s4);
                                                }
                                                result = page.executeFormattedQuery("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_bglcode",result.childNodes.item(0).getAttribute("formatcode"));
							} else {
								setTableInput(table,"u_bglcode","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_bglcode","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_bglcode","");
					}
					break;
                                case "u_mglcode":
					if (getTableInput(table,column)!="") {
						
                                                if (getTableInput(table,"u_mglcode").length==8) {
                                                        var s1="",s2="",s3="",s4="";
                                                        s1 = getTableInput(table,"u_mglcode").substr(0,1);
                                                        s2 = getTableInput(table,"u_mglcode").substr(1,2);
                                                        s3 = getTableInput(table,"u_mglcode").substr(3,2);
                                                        s4 = getTableInput(table,"u_mglcode").substr(5,3);
                                                        setTableInput(table,"u_mglcode",s1+"-"+s2+"-"+s3+"-"+s4);
                                                }
                                                result = page.executeFormattedQuery("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_mglcode",result.childNodes.item(0).getAttribute("formatcode"));
							} else {
								setTableInput(table,"u_mglcode","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_mglcode","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_mglcode","");
					}
					break;
                                     case "u_pglcode":
					if (getTableInput(table,column)!="") {
						
                                                if (getTableInput(table,"u_pglcode").length==8) {
                                                        var s1="",s2="",s3="",s4="";
                                                        s1 = getTableInput(table,"u_pglcode").substr(0,1);
                                                        s2 = getTableInput(table,"u_pglcode").substr(1,2);
                                                        s3 = getTableInput(table,"u_pglcode").substr(3,2);
                                                        s4 = getTableInput(table,"u_pglcode").substr(5,3);
                                                        setTableInput(table,"u_pglcode",s1+"-"+s2+"-"+s3+"-"+s4);
                                                }
                                                result = page.executeFormattedQuery("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_pglcode",result.childNodes.item(0).getAttribute("formatcode"));
							} else {
								setTableInput(table,"u_pglcode","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_pglcode","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_pglcode","");
					}
					break;
                                     case "u_nglcode":
					if (getTableInput(table,column)!="") {
						
                                                if (getTableInput(table,"u_nglcode").length==8) {
                                                        var s1="",s2="",s3="",s4="";
                                                        s1 = getTableInput(table,"u_nglcode").substr(0,1);
                                                        s2 = getTableInput(table,"u_nglcode").substr(1,2);
                                                        s3 = getTableInput(table,"u_nglcode").substr(3,2);
                                                        s4 = getTableInput(table,"u_nglcode").substr(5,3);
                                                        setTableInput(table,"u_nglcode",s1+"-"+s2+"-"+s3+"-"+s4);
                                                }
                                                result = page.executeFormattedQuery("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_nglcode",result.childNodes.item(0).getAttribute("formatcode"));
							} else {
								setTableInput(table,"u_nglcode","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_nglcode","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_nglcode","");
					}
					break;
                                     case "u_glacctcode":
					if (getTableInput(table,column)!="") {
						
                                                if (getTableInput(table,"u_glacctcode").length==8) {
                                                        var s1="",s2="",s3="",s4="";
                                                        s1 = getTableInput(table,"u_glacctcode").substr(0,1);
                                                        s2 = getTableInput(table,"u_glacctcode").substr(1,2);
                                                        s3 = getTableInput(table,"u_glacctcode").substr(3,2);
                                                        s4 = getTableInput(table,"u_glacctcode").substr(5,3);
                                                        setTableInput(table,"u_glacctcode",s1+"-"+s2+"-"+s3+"-"+s4);
                                                }
                                                result = page.executeFormattedQuery("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_glacctcode",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_glacctcode","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_glacctcode","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_glacctcode","");
					}
					break;
			}
			break;
                    default:
                            switch (column) {
                                case "code":
                                        if (getInput(column)!="") {
                                            result = page.executeFormattedQuery("select userid, username from users where userid = '"+getInput(column)+"'");	
                                            if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        var user = page.executeFormattedSearch("select code,name from u_lguposterminals where company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' and code='" + getInput("code") + "'");
                                                        if (user != "" && getInput("code") != "" ) {
                                                            setKey("keys",user+"`0");
                                                            formSubmit('e',null,null,null,true);
                                                        }
                                                } else {
                                                    page.statusbar.showError("Invalid User Id.");	
                                                    return false;
                                                }
                                            }
                                        }
                                        
                                break;
                            }
                    break;
	}			
	return true;
}

function onElementGetValidateParamsGPSLGU(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGU(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGU(element,column,table,row) {
	return true;
}

function onElementClickGPSLGU(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGU(element) {
	return true;
}

function onElementCFLGetParamsGPSLGU(Id,params) {
	switch (Id) {
		case "df_u_penaltycodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees where u_penalty=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Penalty Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_penaltydescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees where u_penalty=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Penalty Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_bglcodeT1":
                case "df_u_mglcodeT1":
                case "df_u_pglcodeT1":
                case "df_u_nglcodeT1":
                case "df_u_glacctcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select userid, username from users")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("User Id`Username")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
//		case "df_u_pglcodeT1":
//			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1")); 
//			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
//			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
//			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
//			break;
//		case "df_u_nglcodeT1":
//			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from lgu_acctg.chartofaccounts where ctrlacct=0 and postable=1")); 
//			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
//			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
//			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
//			break;
	}		
	return params;
	return params;
}

function onTableResetRowGPSLGU(table) {
}

function onTableBeforeInsertRowGPSLGU(table) {
	return true;
}

function onTableAfterInsertRowGPSLGU(table,row) {
}

function onTableBeforeUpdateRowGPSLGU(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGU(table,row) {
}
function onTableBeforeEditRowGPSLGU(table,row) {
	switch (table) {
		case "T101":
                    openupdpays(getTableInput(table,"docno",row));
                    return false;
                break;
	}
	return true;
}

function onTableBeforeDeleteRowGPSLGU(table,row) {
	return true;
}

function onTableDeleteRowGPSLGU(table,row) {
}

function onTableBeforeSelectRowGPSLGU(table,row) {
	return true;
}

function onTableSelectRowGPSLGU(table,row) {
}

function u_AddPosSeriresGPSLGUAddon(action){
        OpenPopup(550,350,"./udo.php?&objectcode=u_terminalseries&formAction=e","UpdPays");
}

function openupdpays(key) {
	OpenPopup(550,350,"./udo.php?&objectcode=u_terminalseries&sf_keys="+key+"&formAction=e","UpdPays");	
}


