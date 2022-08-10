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
//page.tables.events.add.delete('onTableDeleteRowLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUPurchasing');
page.tables.events.add.select('onTableSelectRowLGUPurchasing');

function onPageLoadLGUPurchasing() {
    if (getInput("docstatus")=="D") {
		enableInput("docno");
    }
    
    if (getInput("u_doctype")=="S") document.getElementById("divService").style.display = "block";
     else document.getElementById("divItem").style.display = "block";
    //hidePopupFrame('popupFrameCopyFrom');
}

function onPageResizeLGUPurchasing(width,height) {
}

function onPageSubmitLGUPurchasing(action) {
    
var div1 = document.getElementById('items');
var div2 = document.getElementById('Prev');
var div3 = document.getElementById('OK');
var div4 = document.getElementById('doc');
var div5 = document.getElementById('Next');	

    if (action=="a" || action=="sc") {
                
                if (getInput("docstatus")!="D") {
			if (getPrivate("approver")!="1") {
                            page.statusbar.showError("You must be an approver to add/update this document.");
                            return false;
			}
                        if(getInput("u_forrecordingonly")==1){
                            setInput("docstatus","C");
                        }
                        //if (isInputEmpty("u_jevseries")) return false;	
                        //if (isInputEmpty("u_jevno")) return false;	
		} else {
			if (getPrivate("encoder")!="1" && getPrivate("approver")!="1") {
                            page.statusbar.showError("You must be an encoder/approver to save/update as draft this document.");
                            return false;
			}
		}
		if (isInputEmpty("u_date")) return false;
		if (isInputEmpty("u_duedate")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
		if (isInputEmpty("u_profitcentername")) return false;
                if (isInputEmpty("u_empid")) return false;
		if (isInputEmpty("u_empname")) return false;	
		
                if (isInputNegative("u_totalamount")) return false;
                
    }
    if(action == "cf"){
                div1.style.display='none';
                div2.style.visibility='hidden';
                div3.style.visibility='hidden';
                div4.style.display='block';
                div5.style.visibility='visible';
                if (isInputEmpty("u_bpcode")) return false;
		if (isInputEmpty("u_bpname")) return false;
                clearTable("T10",true);
                clearTable("T20",true);
                getCFDocnoAccessGPSLGUPurchasing();
                showPopupFrame("popupFrameCopyFrom",true);
                return false;
//		if(getTableRowCount("T1") == 0) {
//			clearTable("T10",true);
//			clearTable("T20",true);
//			getCFDocnoAccessGPSLGUPurchasing();
//			showPopupFrame("popupFrameCopyFrom",true);
//			return false;
//		} else {
//			if (window.confirm("All Record wil be Deleted. Continue?")==false) return false;
//			clearTable("T20",true);
//			getCFDocnoAccessGPSLGUPurchasing();
//			showPopupFrame("popupFrameCopyFrom",true);
//                        return false;
//		}
    }else if(action=="cf_access") {
		var rc =  getTableRowCount("T10"),counterror = 0;
			for (i = 1; i <= rc; i++) {
				if(getTableInput("T10","rowstat",i) != "X") {
					if(getTableInput("T10","checked",i)=="Y") {
						if(getTableInputNumeric("T10","quantity",i) == 0){
							page.statusbar.showError("No Quantity");	
							return false;
						}
						if(getTableInputNumeric("T10","unitprice",i) == 0){
							page.statusbar.showError("No Unit Price");	
							return false;
						}
						if(getTableInput("T10","whse",i) == ""){
							page.statusbar.showError("No WareHouse");	
							return false;
						}
						
					}
				}
			}
		getCFAccessGPSLGUPurchasing();
		return false;
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
                                    setTableInputPrice(table,"u_unitcost",parseFloat(getTableInput(table,"u_cost"))/parseFloat(getTableInput(table,"u_quantity")));
                                break;
                            case "u_quantity":
                            case "u_unitcost":
                                setTableInput(table,"u_openquantity",getTableInput(table,"u_quantity"));
                                setTableInputAmount(table,"u_cost",getTableInputNumeric(table,"u_unitcost")*getTableInputNumeric(table,"u_quantity"));
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
                                case "u_linetotal":
                                    setTableInputAmount(table,"u_unitprice",parseFloat(getTableInputNumeric(table,"u_linetotal"))/parseFloat(getTableInputNumeric(table,"u_quantity")));
                                break;
                                case "u_quantity":
                                    if (getTableInputNumeric(table,"u_availableqty") > 0 ) {
                                        if (getTableInputNumeric(table,"u_quantity") > getTableInputNumeric(table,"u_availableqty") ) {
                                            page.statusbar.showError("Qty must be less than or equal to Available Qty.");
                                            setTableInputAmount(table,"u_quantity",0);
                                            return false;
                                        } 
                                    }
                                case "u_unitprice":
                                    setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_quantity"));
                                break;
                                case "u_itemcode":
                                case "u_itemdesc":
                                            if (getTableInput(table,column)!="") {
                                                    if (column=="u_itemcode") result = page.executeFormattedQuery("select code, name, u_uom,  u_unitprice,u_availableqty,u_instockqty from u_lguitems where code = '"+getTableInput(table,column)+"' and u_isstock = 1");	
                                                    else  result = page.executeFormattedQuery("select code, name, u_uom,  u_unitprice,u_availableqty,u_instockqty from u_lguitems where name like '"+utils.addslashes(getTableInput(table,column))+"%' and u_isstock = 1");	
                                                    if (result.getAttribute("result")!= "-1") {
                                                            if (parseInt(result.getAttribute("result"))>0) {
                                                                    setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
                                                                    setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
                                                                    setTableInput(table,"u_unitissue",result.childNodes.item(0).getAttribute("u_uom"));
                                                                    setTableInputAmount(table,"u_quantity",1);
                                                            } else {
                                                                    setTableInput(table,"u_itemcode","");
                                                                    setTableInput(table,"u_itemdesc","");
                                                                    setTableInput(table,"u_unitissue","");
                                                                    setTableInputAmount(table,"u_quantity",0);
                                                                    page.statusbar.showError("Invalid Item.");	
                                                                    return false;
                                                            }
                                                    } else {
                                                            setTableInput(table,"u_itemcode","");
                                                            setTableInput(table,"u_itemdesc","");
                                                            setTableInput(table,"u_unitissue","");
                                                            setTableInputAmount(table,"u_quantity",0);
                                                            page.statusbar.showError("Error retrieving item record. Try Again, if problem persists, check the connection.");	
                                                            return false;
                                                    }
                                                    result = page.executeFormattedQuery("SELECT u_avgprice,u_availableqty FROM u_lgustockcardsummary WHERE u_whscode = '"+getTableInput(table,"u_whscode")+"' and u_itemcode = '"+getTableInput(table,"u_itemcode")+"'");	 
                                                    if (result.getAttribute("result")!= "-1") {
                                                            if (parseInt(result.getAttribute("result"))>0) {
                                                                setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_avgprice"));
                                                                setTableInputAmount(table,"u_availableqty",result.childNodes.item(0).getAttribute("u_availableqty"));
                                                                setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_quantity"));
                                                            } else {
                                                                setTableInputPrice(table,"u_unitprice",0);
                                                                setTableInputAmount(table,"u_availableqty",0);
                                                                setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_quantity"));
                                                            }
                                                    }
                                            } else {
                                                        setTableInput(table,"u_itemcode","");
                                                        setTableInput(table,"u_itemdesc","");
                                                        setTableInput(table,"u_unitissue","");
                                                        setTableInputAmount(table,"u_quantity",0);
                                                        setTableInputAmount(table,"u_availableqty",0);
                                                        setTableInputAmount(table,"u_instockqty",0);
                                                        setTableInputPrice(table,"u_unitprice",0);
                                                        setTableInputAmount(table,"u_linetotal",0);
                                            }
                                        
                                break;
                                case "u_whscode":
                                    if (getTableInput(table,column)!="") {
                                        result = page.executeFormattedQuery("SELECT warehouse FROM warehouses WHERE warehouse = '"+getTableInput(table,column)+"'");	 
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                } else {
                                                        setTableInput(table,"u_whscode","");
                                                        page.statusbar.showError("Invalid warehouse.");		
                                                        return false;
                                                }
                                        } else {
                                               setTableInput(table,"u_whscode","");
                                                page.statusbar.showError("Error retrieving warehouse. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                        result = page.executeFormattedQuery("SELECT u_avgprice,u_availableqty FROM u_lgustockcardsummary WHERE u_whscode = '"+getTableInput(table,"u_whscode")+"' and u_itemcode = '"+getTableInput(table,"u_itemcode")+"'");	 
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                    setTableInputPrice(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_avgprice"));
                                                    setTableInputAmount(table,"u_availableqty",result.childNodes.item(0).getAttribute("u_availableqty"));
                                                    setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_quantity"));
                                                } else {
                                                    setTableInputPrice(table,"u_unitprice",0);
                                                    setTableInputAmount(table,"u_availableqty",0);
                                                    setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_unitprice")*getTableInputNumeric(table,"u_quantity"));
                                                }
                                        }
                                    }
                                break;
                        }
                    break;
                 case "T10":
			switch(column) {
				case "quantity":
					if(getTableInputNumeric(table,"openquantity",row) < getTableInputNumeric(table,"quantity",row)) {
						setTableInput(table,"quantity",formatNumber(0.00,"quantity"),row);
					}
					var total = getTableInputNumeric(table,"unitprice",row)*getTableInputNumeric(table,"quantity",row);
					setTableInput(table,"linetotal",formatNumber(total,"amount"),row);
					break;
				case "unitprice":
					var total = getTableInputNumeric(table,"unitprice",row)*getTableInputNumeric(table,"quantity",row);
					setTableInput(table,"linetotal",formatNumber(total,"amount"),row);
					break;
			}
			break;
		
                default:
                    switch (column) {
                         case "u_profitcenter":
                         case "u_profitcentername":
                                if (getInput(column)!="") {
                                    if (column == "u_profitcenter")   result = page.executeFormattedQuery("select department, departmentname from departments where department = '"+getInput(column)+"'");	
                                    else result = page.executeFormattedQuery("select department, departmentname from departments where departmentname = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setInput("u_profitcenter",result.childNodes.item(0).getAttribute("department"));
                                                        setInput("u_profitcentername",result.childNodes.item(0).getAttribute("departmentname"));
                                                } else {
                                                        setInput("u_profitcenter","");
                                                        setInput("u_profitcentername","");
                                                        page.statusbar.showError("Invalid Department.");	
                                                        return false;
                                                }
                                        } else {
                                                setInput("u_profitcenter","");
                                                setInput("u_profitcentername","");
                                                page.statusbar.showError("Error retrieving department record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_profitcenter","");
                                        setInput("u_profitcentername","");
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
                        case "u_empid":
                        case "u_empname":
					if (getInput(column)!="") {
                                            if (column=="u_empid") result = page.executeFormattedQuery("select empid, fullname from employees where empid = '"+getInput(column)+"'");	
                                            else  result = page.executeFormattedQuery("select empid, fullname from employees where fullname like '"+utils.addslashes(getInput(column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_empid",result.childNodes.item(0).getAttribute("empid"));
								setInput("u_empname",result.childNodes.item(0).getAttribute("fullname"));
							} else {
								setInput("u_empid","");
								setInput("u_empname","");
								page.statusbar.showError("Invalid Employee.");	
								return false;
							}
						} else {
							setInput("u_empid","");
							setInput("u_empname","");
							page.statusbar.showError("Error retrieving employee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_empid","");
						setInput("u_empname","");
					}
					break;
                    }
                    break;
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
                 case "T10":
			switch (column) {
				case "checked":
					if (row==0) {
						if (isTableInputChecked(table,column)) { 
							checkedTableInput(table,column,-1);
							//computeTotalFees();
						} else {
							uncheckedTableInput(table,column,-1); 
							//computeTotalFees();
						}
					}	
					break;
			}
			break;
		case "T20":
			switch (column) {
				case "checked":
					if (row==0) {
						if (isTableInputChecked(table,column)) { 
							checkedTableInput(table,column,-1);
							//computeTotalFees();
						} else {
							uncheckedTableInput(table,column,-1); 
							//computeTotalFees();
						}
					}	
					break;
			}
			break;
		default:
			switch (column) {
                            case "u_forrecordingonly":
                                if (isInputChecked("u_forrecordingonly")) {
                                    setInput("u_date","");
                                    setInput("u_duedate","");
                                    focusInput("u_date");
                                } else {
                                    var today = new Date();
                                    setInput("u_date",(today.getMonth()+1)+'/'+(String(today.getDate()).padStart1(2,'0')) + '/' + today.getFullYear());
                                    setInput("u_duedate",(today.getMonth()+1)+'/'+(String(today.getDate()).padStart1(2,'0')) + '/' + today.getFullYear());
                                }
                                     
                            break;
                            case "u_proctype":
                                    if (getInput(column)=="Program") {
                                        enableInput("u_projcode");
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
                default:
                case "df_u_whscodeT1":
                           params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT warehouse,warehousename FROM warehouses")); 
                           params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Ware House`Ware House Name")); 			
                           params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`70")); 					
                            
			break;
                case "df_u_empid":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select empid, fullname from employees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Employee Id`Employee Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;  
                case "df_u_empname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select fullname, empid from employees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Emloyee Name`Employee Id")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15 ")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;  
                case "df_u_requestedbyname":
		case "df_u_reviewedby":
		case "df_u_approvedby":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name  from u_lgusignatories")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Signatory`Position")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;      
                case "df_u_glacctnoT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
                 case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where budget=1 and u_expclass <>'' and postable=0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
                case "df_u_projcode":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lguprojs where u_profitcenter='"+getInput("u_profitcenter")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Program/Project`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
                case "df_u_obrno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_date, u_bpname, u_remarks,u_checkamount from u_lguobligationslips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Payee`Remarks`OBR Amount")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`10`30`30`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date```")); 			
			break;
		case "df_u_profitcenter":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select department,departmentname  from departments")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Department`Department Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_profitcentername":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select departmentname,department  from departments")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Department Name`Department")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50'15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
                case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lguitems where u_isstock = 1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item No.`Item Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_lguitems where u_isstock = 1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
            }
    
	return params;
}

function onTableResetRowLGUPurchasing(table) {
}

function onTableBeforeInsertRowLGUPurchasing(table) {
    switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputEmpty(table,"u_itemdesc")) return false;
			if (isTableInputEmpty(table,"u_whscode")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
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
			if (isTableInputEmpty(table,"u_whscode")) return false;
			if (isTableInputNegative(table,"u_quantity")) return false;
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
     switch (table) {
		case "T1": computeTotalGPSLGUPurchasing(); break;
		case "T2": computeTotalGPSLGUPurchasing(); break;
            }
	return true;
}

function onTableDeleteRowLGUPurchasing(table,row) {
}

function onTableBeforeSelectRowLGUPurchasing(table,row) {
	return true;
}

function onTableSelectRowLGUPurchasing(table,row) {
    var params = new Array();
	switch (table) {
		case "T10":
				if (elementFocused.substring(0,13)=="df_quantityT1") {
					focusTableInput(table,"u_1stcw",row);
				} else if (elementFocused.substring(0,14)=="df_unitpriceT1") {
					focusTableInput(table,"u_1stex",row);
				} else if (elementFocused.substring(0,10)=="df_whseT1") {
					focusTableInput(table,"u_1stgr",row);
				}
                                params["focus"] = false;
			break;
	}
	return params;
}

function computeTotalGPSLGUPurchasing() {
	var rc = 0, totalamountitems = 0,totalamountservice = 0, rc1 = 0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			totalamountitems += getTableInputNumeric("T1","u_linetotal",i);
		}
	}
        
	setInputAmount("u_totalamount",totalamountitems + totalamountservice );
}


function OpenLnkBtnu_employee(targetObjectId) {
	OpenLnkBtn(1080,680,'./Employee.php?' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function CopyFromPRGPSLGUPurchasing() {
            OpenPopup(1040,390,"./udp.php?&objectcode=u_copyfrompr&df_doctype="+getInput("u_doctype")+"","Copy From Purchase Request");
}


if (!String.prototype.padStart1) {
    String.prototype.padStart1 = function padStart1(targetLength, padString) {
        targetLength = targetLength >> 0; //truncate if number, or convert non-number to 0;
        padString = String(typeof padString !== 'undefined' ? padString : ' ');
        if (this.length >= targetLength) {
            return String(this);
        } else {
            targetLength = targetLength - this.length;
            if (targetLength > padString.length) {
                padString += padString.repeat(targetLength / padString.length); //append to original to ensure we are longer than needed
            }
            return padString.slice(0, targetLength) + String(this);
        }
    }
}