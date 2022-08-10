// page events
page.events.add.load('onPageLoadLGUPurchasing');
//page.events.add.resize('onPageResizeLGUPurchasing');
page.events.add.submit('onPageSubmitLGUPurchasing');
//page.events.add.cfl('onCFLLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownLGUPurchasing');
page.elements.events.add.validate('onElementValidateLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsLGUPurchasing');
//page.elements.events.add.changing('onElementChangingLGUPurchasing');
page.elements.events.add.change('onElementChangeLGUPurchasing');
page.elements.events.add.click('onElementClickLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLLGUPurchasing');
page.elements.events.add.cflgetparams('onElementCFLGetParamsLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowLGUPurchasing');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowLGUPurchasing');
page.tables.events.add.afterInsert('onTableAfterInsertRowLGUPurchasing');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowLGUPurchasing');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowLGUPurchasing');
page.tables.events.add.delete('onTableDeleteRowLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowLGUPurchasing');

function onPageLoadLGUPurchasing() {
    if (getInput("docstatus")=="D") {
		enableInput("docno");
    }
     if (getInput("u_doctype")=="S") document.getElementById("divService").style.display = "block";
     else document.getElementById("divItem").style.display = "block";
                                     
   
}

function onPageResizeLGUPurchasing(width,height) {
}

function onPageSubmitLGUPurchasing(action) {
        if (action=="a" || action=="sc") {

                    if (getInput("docstatus")!="D") {
                            if (getPrivate("approver")!="1") {
                                    page.statusbar.showError("You must be an approver to add/update this document.");
                                    return false;
                            }
                    } else {
                            if (getPrivate("encoder")!="1" && getPrivate("approver")!="1") {
                                    page.statusbar.showError("You must be an encoder/approver to save/update as draft this document.");
                                    return false;
                            }
                    }
                    if (getInput("docstatus")!="D" && getPrivate("linkprograms") == 1) {
                        if (getGlobal("branch") != "300") {
                            if (isInputEmpty("u_projcode")) return false;
                        }
			if (isInputEmpty("u_refno")) return false;
                    }
                    if (isInputEmpty("u_date")) return false;
                    if (isInputEmpty("u_duedate")) return false;
                    if (isInputEmpty("u_profitcenter")) return false;
                    if (isInputEmpty("u_procmode")) return false;
                    if (isInputNegative("u_totalamount")) return false;

        } 
        return true;
}

function onCFLLGUPurchasing(Id) {
	return true;
}

function onCFLGetParamsLGUPurchasing(Id,params) {
	return params;
}

function onTaskBarLoadLGUPurchasing() {
}

function onElementFocusLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementKeyDownLGUPurchasing(element,event,column,table,row) {
}

function onElementValidateLGUPurchasing(element,column,table,row) {
    switch (table) {
                case "T2":
			switch (column) {
                            case "u_cost":
                                if (getTableInputNumeric(table,"u_quantity") > 0) {
                                    setTableInputPrice(table,"u_unitcost",parseFloat(getTableInput(table,"u_cost"))/parseFloat(getTableInput(table,"u_quantity")));
                                } else {
                                    setTableInputPrice(table,"u_unitcost",parseFloat(getTableInput(table,"u_cost")));
                                }
                                break;
                            case "u_quantity":
                            case "u_unitcost":
                                if (getTableInputNumeric(table,"u_quantity") > 0) {
                                    setTableInputAmount(table,"u_cost",getTableInputNumeric(table,"u_unitcost")*getTableInputNumeric(table,"u_quantity"));
                                } else {
                                    setTableInputAmount(table,"u_cost",getTableInputNumeric(table,"u_unitcost"));
                                }
                                setTableInput(table,"u_openquantity",getTableInput(table,"u_quantity"));

                                break;
                            case "u_glacctno":
                                if (getTableInput(table,column)!="") {
                                    var result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and formatcode = '"+getTableInput(table,column)+"'");
                                    if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
                                                } else {
                                                        setTableInput(table,"u_glacctno","");
                                                        page.statusbar.showError("Invalid G/L Account.");	
                                                        return false;
                                                }
                                    } else {
                                            setTableInput(table,"u_glacctno","");
                                            page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
                                            return false;
                                    }
                                }else{
                                    setTableInput(table,"u_glacctno","");
                                    setTableInput(table,"u_unitissue","");
                                    setTableInputAmount(table,"u_unitcost",0);
                                    setTableInputAmount(table,"u_cost",0);
                                }
                                break;
                        }
                        break;
		case "T1":
			switch (column) {
                            case "u_cost":
                                    setTableInputPrice(table,"u_unitcost",parseFloat(getTableInput(table,"u_cost"))/parseFloat(getTableInput(table,"u_quantity")));
                                break;
                            case "u_quantity":
                            case "u_unitcost":
                                    setTableInput(table,"u_openquantity",getTableInput(table,"u_quantity"));
                                    setTableInputAmount(table,"u_cost",getTableInputNumeric(table,"u_unitcost")*getTableInputNumeric(table,"u_quantity"));
                                    break;
                            case "u_itemcode":
                            case "u_itemdesc":
                                    if (getTableInput(table,column)!="") {
                                            if (column=="u_itemcode") result = page.executeFormattedQuery("select code, name, u_uom, u_unitprice,u_itemsubgroup from u_lguitems where code = '"+getTableInput(table,column)+"'");	
                                            else  result = page.executeFormattedQuery("select code, name, u_uom, u_unitprice,u_itemsubgroup from u_lguitems where name like '"+utils.addslashes(getTableInput(table,column))+"%'");	
                                            if (result.getAttribute("result")!= "-1") {
                                                    if (parseInt(result.getAttribute("result"))>0) {
                                                            setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
                                                            setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
                                                            setTableInput(table,"u_itemsubgroup",result.childNodes.item(0).getAttribute("u_itemsubgroup"));
                                                            setTableInput(table,"u_unitissue",result.childNodes.item(0).getAttribute("u_uom"));
                                                            setTableInputAmount(table,"u_unitcost",result.childNodes.item(0).getAttribute("u_unitprice"));
                                                            setTableInputAmount(table,"u_cost",getTableInputNumeric(table,"u_unitcost")*getTableInputNumeric(table,"u_quantity"));
                                                    } else {
                                                            setTableInput(table,"u_itemcode","");
                                                            setTableInput(table,"u_itemdesc","");
                                                            setTableInput(table,"u_itemsubgroup","");
                                                            setTableInput(table,"u_unitissue","");
                                                            setTableInputAmount(table,"u_unitcost",0);
                                                            setTableInputAmount(table,"u_cost",0);
                                                            page.statusbar.showError("Invalid Item.");	
                                                            return false;
                                                    }
                                            } else {
                                                    setTableInput(table,"u_itemcode","");
                                                    setTableInput(table,"u_itemdesc","");
                                                    setTableInput(table,"u_itemsubgroup","");
                                                    setTableInput(table,"u_unitissue","");
                                                    setTableInputAmount(table,"u_unitcost",0);
                                                    setTableInputAmount(table,"u_cost",0);
                                                    page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
                                                    return false;
                                            }
                                    }else{
                                            setTableInput(table,"u_itemcode","");
                                            setTableInput(table,"u_itemdesc","");
                                            setTableInput(table,"u_itemsubgroup","");
                                            setTableInput(table,"u_unitissue","");
                                            setTableInputAmount(table,"u_unitcost",0);
                                            setTableInputAmount(table,"u_cost",0);
                                    }
                                    break;
                        }
                    break;
                default:
                    switch (column) {
                        
                        case "u_profitcenter":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcenter = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
                                                        setInput("u_poprofitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
                                                        setInput("u_profitcentername",result.childNodes.item(0).getAttribute("profitcentername"));
                                                } else {
                                                        setInput("u_profitcenter","");
                                                        setInput("u_poprofitcenter","");
                                                        setInput("u_profitcentername","");
                                                        page.statusbar.showError("Invalid Profit Center.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_profitcenter","");
                                                setInput("u_poprofitcenter","");
                                                setInput("u_profitcentername","");
                                                page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_profitcenter","");
                                        setInput("u_poprofitcenter","");
                                        setInput("u_profitcentername","");
                                }
                                break;
                                
                        case "u_projcode":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, name,u_procmode from u_lguprojs where u_profitcenter='"+getInput("u_profitcenter")+"' and code = '"+getInput(column)+"' and u_totalpramount <> u_estbudget and branch = '"+getGlobal("branch")+"' and u_appcode not in (select u_refappcode from u_lguprojs where u_refappcode <> '' group by u_refappcode ) ");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_projcode",result.childNodes.item(0).getAttribute("code"));
								setInput("u_projname",result.childNodes.item(0).getAttribute("name"));
								setInput("u_procmode",result.childNodes.item(0).getAttribute("u_procmode"));
							} else {
								//setTableInput(table,"u_expgroupno","");
								//setTableInput(table,"u_expclass","");
								setInput("u_projcode","");
								setInput("u_projname","");
								setInput("u_procmode","");
								page.statusbar.showError("Invalid Program/Project.");	
								return false;
							}
						} else {
							//setTableInput(table,"u_expgroupno","");
							//setTableInput(table,"u_expclass","");
							setInput("u_projcode","");
							setInput("u_projname","");
							setInput("u_procmode","");
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
                        case "u_requestedbyname":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, name from u_lgusignatories where code = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_requestedbyname",result.childNodes.item(0).getAttribute("code"));
								setInput("u_requestedbyposition",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_requestedbyname","");
								setInput("u_requestedbyposition","");
								page.statusbar.showError("Invalid name of signatory.");	
								return false;
							}
						} else {
							setInput("u_requestedbyname","");
							setInput("u_requestedbyposition","");
							page.statusbar.showError("Error retrieving signatory name record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_requestedbyname","");
						setInput("u_requestedbyposition","");
					}
					break;
                        case "u_reviewedby":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, name from u_lgusignatories where code = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_reviewedby",result.childNodes.item(0).getAttribute("code"));
								setInput("u_reviewedbyposition",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_reviewedby","");
								setInput("u_reviewedbyposition","");
								page.statusbar.showError("Invalid name of signatory.");	
								return false;
							}
						} else {
							setInput("u_reviewedby","");
							setInput("u_reviewedbyposition","");
							page.statusbar.showError("Error retrieving signatory name record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_reviewedby","");
						setInput("u_reviewedbyposition","");
					}
					break;
                        case "u_approvedby":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, name from u_lgusignatories where code = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_approvedby",result.childNodes.item(0).getAttribute("code"));
								setInput("u_approvedbyposition",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_approvedby","");
								setInput("u_approvedbyposition","");
								page.statusbar.showError("Invalid name of signatory.");	
								return false;
							}
						} else {
							setInput("u_approvedby","");
							setInput("u_approvedbyposition","");
							page.statusbar.showError("Error retrieving signatory name record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_approvedby","");
						setInput("u_approvedbyposition","");
					}
					break;
                      
                    }
                    break
            }
            
	return true;
}

function onElementGetValidateParamsLGUPurchasing(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingLGUPurchasing(element,column,table,row) {
	return true;
}

function onElementChangeLGUPurchasing(element,column,table,row) {
     switch (table) {
		default:
			switch (column) {
                            case "u_doctype":
                                    var rc = getTableRowCount("T1"),cnt=0,cnt2=0,rc1 = getTableRowCount("T2");
                                    document.getElementById("divItem").style.display = "none";
                                    document.getElementById("divService").style.display = "none";
                                    if (getInput(column)=="S") {
                                            if(getTableInput("T1","u_itemdesc")!=""){
                                                page.statusbar.showError("An item is currently being added/edited.");
                                                focusTableInput("T1","u_itemdesc");
                                                document.getElementById("divItem").style.display = "block";    
                                                return false;
                                            }
                                            for (xxx = 1; xxx <=rc; xxx++) {
                                                if (isTableRowDeleted("T1",xxx)==false) {
                                                        cnt = cnt+1;
                                                }
                                            }
                                            if(cnt>0){
                                                    page.statusbar.showError("item exists. delete first before proceeding.");
                                                    document.getElementById("divItem").style.display = "block";   
                                                    return false;
                                            }else{
                                                    document.getElementById("divService").style.display = "block";
                                            }
                                    }
                                    if (getInput(column)=="I"){
                                            if(getTableInput("T2","u_glacctno")!=""){
                                                    page.statusbar.showError("An item is currently being added/edited.");
                                                    focusTableInput("T2","u_glacctno");
                                                    document.getElementById("divService").style.display = "block";    
                                                    return false;
                                            }
                                            for (xxx = 1; xxx <=rc1; xxx++) {
                                                if (isTableRowDeleted("T2",xxx)==false) {
                                                        cnt2 = cnt2+1;
                                                }
                                            }
                                            if(cnt2>0){
                                                    page.statusbar.showError("item exists. delete first before proceeding.");
                                                    document.getElementById("divService").style.display = "block";   
                                                    return false;
                                            }else{
                                                    document.getElementById("divItem").style.display = "block";
                                            }
                                    }
                                   
                            break;
                        }
        }
	return true;
}

function onElementClickLGUPurchasing(element,column,table,row) {
    switch (table) {
		default:
			switch (column) {
                            case "u_proctype":
                                    if (getInput(column)=="Program") {
                                        enableInput("u_projcode");
                                        focusInput("u_projcode");
                                    }else{
                                        setInput("u_projcode","");
                                        setInput("u_projname","");
                                        disableInput("u_projcode");
                                    }
                            break;
                        }
                break;
                }
	return true;
}

function onElementCFLLGUPurchasing(element) {
	return true;
}

function onElementCFLGetParamsLGUPurchasing(Id,params) {
    switch (Id) {
                 case "df_u_glacctnoT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
                case "df_u_projcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name,u_totalpramount,u_estbudget,u_actbudget,(u_actbudget-u_totalpramount) from u_lguprojs where u_profitcenter='"+getInput("u_profitcenter")+"' and  u_totalpramount <> u_estbudget and branch = '"+getGlobal("branch")+"' and u_appcode not in (select u_refappcode from u_lguprojs where u_refappcode <> '' group by u_refappcode ) ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Program/Project`Description`PR Amount`Estimated Budget`Actual Budget`Budget Balance")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`15`15`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``amount`amount`amount`amount")); 		
			break;
		case "df_u_requestedbyname":
		case "df_u_reviewedby":
		case "df_u_approvedby":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name  from u_lgusignatories")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Signatory`Position")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_profitcenter":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter,profitcentername  from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
                case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lguitems where u_itemsubgroup like '%"+getTableInput("T1","u_itemsubgroup")+"%' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item No.`Item Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_lguitems where u_itemsubgroup like '%"+getTableInput("T1","u_itemsubgroup")+"%'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
            }
    
	return params;
}

function onTableResetRowLGUPurchasing(table) {
}

function onTableBeforeInsertRowLGUPurchasing(table,row) {
    switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
                        setTableInputDefault(table,"u_itemsubgroup",getTableInput(table,"u_itemsubgroup",row),row);
                        if (getTableInput(table,"u_cost") <= 0) {
                            if(!confirm("Item Cost is zero. Continue?")) return false;
                        }
			break;
                case "T2":
                        if (isInputEmpty("u_profitcenter")) return false;
//			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
                        if (getTableInput(table,"u_cost") <= 0) {
                            if(!confirm("Item Cost is zero. Continue?")) return false;
                        }
                        break;
                }
	return true;
}

function onTableAfterInsertRowLGUPurchasing(table,row) {
    switch (table) {
		case "T1": computeTotalGPSLGUPurchasing(); break;
		case "T2": computeTotalGPSLGUPurchasing(); break;
            }
}

function onTableBeforeUpdateRowLGUPurchasing(table,row) {
     switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
                        setTableInputDefault(table,"u_itemsubgroup",getTableInput(table,"u_itemsubgroup",row),row);
                        if (getTableInput(table,"u_cost") <= 0) {
                            if(!confirm("Item Cost is zero. Continue?")) return false;
                        }
			break;
                case "T2":
                        if (isInputEmpty("u_profitcenter")) return false;
//                        if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
                         if (getTableInput(table,"u_cost") <= 0) {
                            if(!confirm("Item Cost is zero. Continue?")) return false;
                        }
                        break;
                        
                }
	return true;
}

function onTableAfterUpdateRowLGUPurchasing(table,row) {
     switch (table) {
		case "T1": computeTotalGPSLGUPurchasing(); break;
		case "T2": computeTotalGPSLGUPurchasing(); break;
            }
}

function onTableBeforeDeleteRowLGUPurchasing(table,row) {
     
}

function onTableDeleteRowLGUPurchasing(table,row) {
    switch (table) {
		case "T1": computeTotalGPSLGUPurchasing(); break;
		case "T2": computeTotalGPSLGUPurchasing(); break;
            }
	return true;
}

function onTableBeforeSelectRowLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowLGUPurchasing(table,row) {
}

function computeTotalGPSLGUPurchasing() {
	var rc = 0, totalamountitems = 0,totalamountservice = 0, rc1 = 0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamountitems += getTableInputNumeric("T1","u_cost",i);
		}
	}
        
        rc1 =  getTableRowCount("T2");
	for (i = 1; i <= rc1; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			totalamountservice += getTableInputNumeric("T2","u_cost",i);
		}
	}
        
	setInputAmount("u_totalamount",totalamountitems + totalamountservice );
}

function u_AddEditRemarksGPSLGUPurchasing(){
        if (getTableSelectedRow("T2") != "" && getInput("u_doctype") == "S") {
            setTableInput("T51","remarks",getTableInput("T2","u_remarks",getTableSelectedRow("T2")));
            showPopupFrame("popupFrameAddEditRemarks",true);
        } else if (getTableSelectedRow("T1") != "" && getInput("u_doctype") == "I") { 
            setTableInput("T51","remarks",getTableInput("T1","u_remarks",getTableSelectedRow("T1")));
            showPopupFrame("popupFrameAddEditRemarks",true);
        } else {
            page.statusbar.showError("No selected row.");	
            return false;
        }
}
function setRemarksGPSLGUPurchasing(){
        if (getTableSelectedRow("T2") != "" && getInput("u_doctype") == "S") {
            setTableInput("T2","u_remarks",getTableInput("T51","remarks"),getTableSelectedRow("T2"));
        } else if (getTableSelectedRow("T1") != "" && getInput("u_doctype") == "I") { 
            setTableInput("T1","u_remarks",getTableInput("T51","remarks"),getTableSelectedRow("T1"));
        } else {
            page.statusbar.showError("No selected row.");	
            return false;
        }
        hidePopupFrame("popupFrameAddEditRemarks");
}

function u_copyGLGPSLGUPurchasing() {
	var data = new Array(),rc = 0,rc2 = 0;
        if ( getInput("u_doctype") == "I") rc = getTableSelectedRow("T1") ;
        else  rc2= getTableSelectedRow("T2")  ;
       
	if (rc>0) {
                if (getTableInput("T1","u_itemcode")!="") {
                    page.statusbar.showWarning("You cannot copy if an item is currently being added/edited.");
                    return;
                }
		setTableInput("T1","u_itemcode",getTableInput("T1","u_itemcode",rc));
		setTableInput("T1","u_itemdesc",getTableInput("T1","u_itemdesc",rc));
		setTableInput("T1","u_unitissue",getTableInput("T1","u_unitissue",rc));
		setTableInput("T1","u_quantity",getTableInput("T1","u_quantity",rc));
		focusTableInput("T1","u_unitcost");
	} 
	if (rc2>0) {
                if (getTableInput("T2","u_glacctno")!="") {
                    page.statusbar.showWarning("You cannot copy if an item is currently being added/edited.");
                    return;
                }
		setTableInput("T2","u_glacctno",getTableInput("T2","u_glacctno",rc2));
		setTableInput("T2","u_itemdesc",getTableInput("T2","u_itemdesc",rc2));
		setTableInput("T2","u_unitissue",getTableInput("T2","u_unitissue",rc2));
		setTableInput("T2","u_quantity",getTableInput("T2","u_quantity",rc2));
		focusTableInput("T2","u_unitcost");
	} 
}