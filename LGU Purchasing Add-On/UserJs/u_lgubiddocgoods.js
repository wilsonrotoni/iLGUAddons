// page events
page.events.add.load('onPageLoadGPSLGUPurchasing');
//page.events.add.resize('onPageResizeGPSLGUPurchasing');
page.events.add.submit('onPageSubmitGPSLGUPurchasing');
page.events.add.submitreturn('onPageSubmitReturnGPSLGUPurchasing');
//page.events.add.cfl('onCFLGPSLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUPurchasing');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUPurchasing');
page.elements.events.add.validate('onElementValidateGPSLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUPurchasing');
//page.elements.events.add.changing('onElementChangingGPSLGUPurchasing');
//page.elements.events.add.change('onElementChangeGPSLGUPurchasing');
page.elements.events.add.click('onElementClickGPSLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLGPSLGUPurchasing');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUPurchasing');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUPurchasing');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowGPSLGUPurchasing');
var tabberOptions = {
  'manualStartup':true,
   'onClick': function(argsObj) { 
	    if (argsObj.tabber.id == 'tab1') {
			switch (getTabIDByName(argsObj.tabber.tabs[argsObj.index].headingText)) {
				case "Abstract of Bid as Read": 
				    if (getVar("formSubmitAction")=="a") {
						setStatusMsg("Please save this document first before you can proceed.",4000,1);
						return false;
					}	
					break;
			}
	    } 
  	return true;
	}
};
function onPageLoadGPSLGUPurchasing() {
    if (getVar("formSubmitAction")=="a") {
        setInput("u_philgepsno",window.opener.getInput("docno"));
        setInput("u_title",window.opener.getInput("u_projname"));
    }
}

function onPageResizeGPSLGUPurchasing(width,height) {
}
function onLnkBtnGetParamsLGUPurchasing(Id,params) {
	switch (Id) {
		case "editT1":
			params["keys"] = getTableInput("T101","docno",getTableSelectedRow("T101"));
			break;
	}
	return params;
}

function onPageSubmitGPSLGUPurchasing(action) {
    if (action=="a" || action=="sc") {
        if (isInputEmpty("u_philgepsno")) return false;
        if (isInputEmpty("u_title")) return false;
        if (isInputNegative("u_abc")) return false;
    }
	return true;
}

function onPageSubmitReturnGPSLGUPurchasing(action,success,error) {
        try {
		window.opener.setKey("keys",window.opener.getInput("docno"));
		window.opener.formEdit();
                window.close();
	} catch(TheError) {
	}
	//if (success) window.close();
}
function onCFLGPSLGUPurchasing(Id) {
	return true;
}

function onCFLGetParamsGPSLGUPurchasing(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUPurchasing() {
}

function onElementFocusGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUPurchasing(element,event,column,table,row) {
}

function onElementValidateGPSLGUPurchasing(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				
				case "u_expenseglacctno":
				case "u_expenseglacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_expenseglacctno") {
							if (getTableInput(table,"u_expenseglacctno").length==8) {
								var s1="",s2="",s3="",s4="";
								s1 = getTableInput(table,"u_expenseglacctno").substr(0,1);
								s2 = getTableInput(table,"u_expenseglacctno").substr(1,2);
								s3 = getTableInput(table,"u_expenseglacctno").substr(3,2);
								s4 = getTableInput(table,"u_expenseglacctno").substr(5,3);
								setTableInput(table,"u_expenseglacctno",s1+"-"+s2+"-"+s3+"-"+s4);
							}
							result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1 and formatcode = '"+getTableInput(table,column)+"'");	
						} else  result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_expenseglacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_expenseglacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_expenseglacctno","");
								setTableInput(table,"u_expenseglacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_expenseglacctno","");
							setTableInput(table,"u_expenseglacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_expenseglacctno","");
						setTableInput(table,"u_expenseglacctname","");
					}
					break;
			}
			break;
                           
            default:
                switch (column) {
                        case "u_abc":
                             if (getInputNumeric(column) > 0) {
                                 setInput("u_abcwords",toWordsconver(getInputNumeric(column)));
                             } else {
                                 setInput("u_abcwords","");
                             }
                        break;
                        case "u_biddocsfee":
                             if (getInputNumeric(column) > 0) {
                                 setInput("u_biddocsfeewords",toWordsconver(getInputNumeric(column)));
                             } else {
                                 setInput("u_biddocsfeewords","");
                             }
                        break;
                        case "u_bscash":
                             if (getInputNumeric(column) > 0) {
                                 setInput("u_bscashwords",toWordsconver(getInputNumeric(column)));
                             } else {
                                 setInput("u_bscashwords","");
                             }
                        break;
                        case "u_bstotal":
                             if (getInputNumeric(column) > 0) {
                                 setInput("u_bstotalwords",toWordsconver(getInputNumeric(column)));
                             } else {
                                 setInput("u_bstotalwords","");
                             }
                        break;
                        case "u_opt1code":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select u_option, u_optiondesc from u_lgubiddocoptions where code = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_itbopt1",result.childNodes.item(0).getAttribute("u_option"));
                                                        setInput("u_itbopt1desc",result.childNodes.item(0).getAttribute("u_optiondesc"));
                                                } else {
                                                        setInput("u_itbopt1","");
                                                        setInput("u_itbopt1desc","");
                                                        page.statusbar.showError("Invalid BidDocs Option.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_itbopt1","");
                                                setInput("u_itbopt1desc","");
                                                page.statusbar.showError("Error retrieving Option record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_itbopt1","");
                                        setInput("u_itbopt1desc","");
                                }
                        break;
                        case "u_opt2code":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select u_option, u_optiondesc from u_lgubiddocoptions where code = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_itbopt2",result.childNodes.item(0).getAttribute("u_option"));
                                                } else {
                                                        setInput("u_itbopt2","");
                                                        page.statusbar.showError("Invalid BidDocs Option.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_itbopt2","");
                                                page.statusbar.showError("Error retrieving Option record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_itbopt2","");
                                }
                        break;
                        case "u_opt3code":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select u_option, u_optiondesc from u_lgubiddocoptions where code = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_itbopt3",result.childNodes.item(0).getAttribute("u_option"));
                                                } else {
                                                        setInput("u_itbopt3","");
                                                        page.statusbar.showError("Invalid BidDocs Option.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_itbopt3","");
                                                page.statusbar.showError("Error retrieving Option record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_itbopt3","");
                                }
                        break;
                         case "u_opt4code":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select u_option, u_optiondesc from u_lgubiddocoptions where code = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_itbopt4",result.childNodes.item(0).getAttribute("u_option"));
                                                } else {
                                                        setInput("u_itbopt4","");
                                                        page.statusbar.showError("Invalid BidDocs Option.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_itbopt4","");
                                                page.statusbar.showError("Error retrieving Option record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_itbopt4","");
                                        setInput("u_itbopt4desc","");
                                }
                                break;
                         case "u_opt5code":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select u_option, u_optiondesc from u_lgubiddocoptions where code = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_itbopt5",result.childNodes.item(0).getAttribute("u_option"));
                                                } else {
                                                        setInput("u_itbopt5","");
                                                        page.statusbar.showError("Invalid BidDocs Option.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_itbopt5","");
                                                page.statusbar.showError("Error retrieving Option record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_itbopt5","");
                                }
                                break;
                         case "u_opt6code":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select u_option, u_optiondesc from u_lgubiddocoptions where code = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_itbopt6",result.childNodes.item(0).getAttribute("u_option"));
                                                } else {
                                                        setInput("u_itbopt6","");
                                                        page.statusbar.showError("Invalid BidDocs Option.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_itbopt6","");
                                                page.statusbar.showError("Error retrieving Option record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_itbopt6","");
                                }
                                break;
                }
                break;
                
                
	}
	return true;
}

function onElementGetValidateParamsGPSLGUPurchasing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUPurchasing(element, column, table, row) {
    switch (table) {
        case "T101":
            switch (column) {
                case "edit":
                    if (row == 0) {
                        if (getInput("docstatus") == "O" || getInput("docstatus") == "D") {
                            var targetObjectId = '';
                            OpenLnkBtn(560, 420, './udo.php?objectcode=u_lgubidabstractasread' + '' + '&targetId=' + targetObjectId, targetObjectId);
                        } else
                            page.statusbar.showWarning("You can only add if status is Open or Draft.");
                    } else {
                        targetObjectId = 'editT1';
                        OpenLnkBtn(560, 420, './udo.php?objectcode=u_lgubidabstractasread' + '' + '&targetId=' + targetObjectId, targetObjectId);
                    }
                    break;
            }
            break;
        default:
            switch (column) {

            }
            break;
    }
    return true;
}

function onElementCFLGPSLGUPurchasing(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUPurchasing(id,params) {
	switch (id) {	
		case "df_u_opt1code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, u_option from u_lgubiddocoptions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Option")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_opt2code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, u_option from u_lgubiddocoptions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Option")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_opt3code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, u_option from u_lgubiddocoptions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Option")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_opt4code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, u_option from u_lgubiddocoptions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Option")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_opt5code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, u_option from u_lgubiddocoptions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Option")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_opt6code":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, u_option from u_lgubiddocoptions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Option")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_barangay":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from bacooruatrpt.u_barangays")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSLGUPurchasing(table) {
}

function onTableBeforeInsertRowGPSLGUPurchasing(table) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeUpdateRowGPSLGUPurchasing(table,row) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeDeleteRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUPurchasing(table,row) {
}

function onTableBeforeSelectRowGPSLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowGPSLGUPurchasing(table,row) {
}

