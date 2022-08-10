// page events
page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
page.events.add.submitreturn('onPageSubmitReturnGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSRPTAS');

// element events
//page.elements.events.add.focus('onElementFocusGPSRPTAS');
page.elements.events.add.keydown('onElementKeyDownGPSRPTAS');
page.elements.events.add.validate('onElementValidateGPSRPTAS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSRPTAS');
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
page.elements.events.add.change('onElementChangeGPSRPTAS');
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
page.tables.events.add.select('onTableSelectRowGPSRPTAS');
//page.tables.events.add.afterEdit('onTableAfterEditRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
    focusInput("u_arpno");
}
function onPageSubmitReturnGPSRPTAS(action,sucess,error) {
	if (action=="a" && sucess) {
            if(window.confirm("Print Real Property Tax Bill. Continue?")) OpenReportSelect('printer'); focusInput("u_address");
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
    var rc =  getTableRowCount("T1"),count = 0 ;
    var rc3 =  getTableRowCount("T3");
    if (action=="a" || action=="sc") {
//            if (isInputEmpty("u_tin")) return false;
            if (isInputEmpty("u_declaredowner")) return false;
            if (isInputEmpty("u_paymode")) return false;
            if (isInputNegative("u_totaltaxamount")) return false;
            if (action=="a" && getInput("u_partialpay")==1) {
                if (window.confirm("Partial Payment will be posted. Continue?")==false) return false;
            }
            if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
    }
    
     if (action=="a" && rc3 > 0) {
         	for (xxx = 1; xxx <= rc3; xxx++) {
                    if (isTableRowDeleted("T3",xxx)==false) {
                        if (getTableInputNumeric("T3","u_selected",xxx)=="1") {
                            count += count + 1;
                        }
                    }
                }
                if (count == 0){
                    if(confirm("There's an existing uncheck tax credit for this tax payer. Continue?")){
                    }else{
                        return false;
                    }
                }
                    
     }
	return true;
}

function onCFLGPSRPTAS(Id) {
	return true;
}

function onCFLGetParamsGPSRPTAS(Id,params) {
	return params;
}

function onTaskBarLoadGPSRPTAS() {
}

function onElementFocusGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSRPTAS(element,event,column,table,row) {
    var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		case "F2":
			if(getInput("u_savestatus") == 1){
				u_cashierGPSRPTAS();
			}
			break;
		case "F4":
			u_SearchPropertyGPSRPTAS();
			break;
		case "F8":
			formSubmit("a");
			break;
		case "CTRL+ENTER":
			formSubmit("?");
			break;
	}
}

function onElementValidateGPSRPTAS(element,column,table,row) {
	switch (table) {
            case "T1":
                switch (column) {
                    case "u_taxdue":
                    case "u_sef":
                    case "u_penalty":
                    case "u_sefpenalty":
                    case "u_taxdisc":
                    case "u_sefdisc":
                        var totalamount = 0, sef=0, tax=0, penalty=0, sefpenalty=0, taxdisc=0, sefdisc=0;
                        tax = getTableInputNumeric("T1","u_taxdue",row);
                        sef = getTableInputNumeric("T1","u_sef",row);
                        penalty = getTableInputNumeric("T1","u_penalty",row) ;
                        sefpenalty = getTableInputNumeric("T1","u_sefpenalty",row);
                        taxdisc = getTableInputNumeric("T1","u_taxdisc",row) ;
                        sefdisc = getTableInputNumeric("T1","u_sefdisc",row);
                        totalamount = formatNumericAmount(tax+penalty-taxdisc + sef+sefpenalty-sefdisc)
                        setTableInput("T1","u_linetotal",totalamount,row);
                        break;
                }
            break;
            default:
                    switch (column) {
                            
				case "u_assdate":
					if (getInput(column)!="") {
						setInput("u_year",getInput("u_assdate").substr(6,4));
					} else {
						setInput("u_year",0);
					}
					if (getInput("u_year")!=getPrivate("year")) {
                                            disableInput("u_advancepay");
					} else enableInput("u_advancepay");
					setInput("u_yearfrom",0);
					setInput("u_yearto",0);
                                        getTaxDues();
                                        getSemiQuarterly();
                                        computeTaxGPSRPTAS();
					break;
//				case "u_year":
//					getTaxDues();
//					break;
				
				case "u_arpno":
                                    if (isInputEmpty("u_assdate")) return false;
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_ownertin,u_tdno, docno, u_pin, type, u_ownername,u_owneraddress from (select u_ownertin,u_tdno, docno,u_pin, 'Land' as type, u_ownername, u_owneraddress from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getInput("u_arpno")+"' union all select u_ownertin,u_tdno, docno,u_pin, 'Building' as type, u_ownername, u_owneraddress from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getInput("u_arpno")+"' union all select u_ownertin,u_tdno, docno,u_pin, 'Machinery' as type, u_ownername, u_owneraddress from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getInput("u_arpno")+"') as x");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tdno",result.childNodes.item(0).getAttribute("u_tdno"));
                                                                setInput("u_tin",result.childNodes.item(0).getAttribute("u_ownertin"));
                                                                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                        setInput("u_paymode","A");
                                                                        setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_owneraddress"));
                                                                        setInput("u_declaredowner",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                }                                                         
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
                                                                getPropertyTax();
							} else {
								setInputAmount("u_tin","");
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
								getPropertyTax();
								page.statusbar.showError("Invalid ARP No.");	
								return false;
							}
						} else {
							setInputAmount("u_tin","");
							setInput("u_yearfrom",0);
							setInput("u_yearto",0);
							getPropertyTax();
							page.statusbar.showError("Error retrieving faas record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                                 
					}
					break;
                                case "u_pin":
				case "u_tdno":
                                    if (isInputEmpty("u_assdate")) return false;
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_ownertin, docno, u_pin, type, u_ownername,u_owneraddress from (select u_ownertin, docno,u_pin, 'Land' as type, u_ownername, u_owneraddress from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"' union all select u_ownertin, docno,u_pin, 'Building' as type, u_ownername, u_owneraddress from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"' union all select u_ownertin, docno,u_pin, 'Machinery' as type, u_ownername, u_owneraddress from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"') as x");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tin",result.childNodes.item(0).getAttribute("u_ownertin"));
                                                                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                        setInput("u_paymode","A");
                                                                        setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_owneraddress"));
                                                                        setInput("u_declaredowner",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                }                                                         
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
                                                                getPropertyTax();
							} else {
								setInputAmount("u_tin","");
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
								getPropertyTax();
								page.statusbar.showError("Invalid TD No.");	
								return false;
							}
						} else {
							setInputAmount("u_tin","");
							setInput("u_yearfrom",0);
							setInput("u_yearto",0);
							getPropertyTax();
							page.statusbar.showError("Error retrieving faas record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                                 
					}
					break;
				case "u_tin":
//				case "u_pin":
                                        if (isInputEmpty("u_assdate")) return false;
                                            var result = page.executeFormattedQuery("select u_tdno,u_ownertin, docno, u_pin, type, u_ownername from (select u_tdno,u_ownertin, docno,u_pin, 'Land' as type, u_ownername from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getInput("u_tin")+"' union all select u_tdno,u_ownertin, docno,u_pin, 'Building' as type, u_ownername from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getInput("u_tin")+"' union all select u_tdno,u_ownertin, docno,u_pin, 'Machinery' as type, u_ownername from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getInput("u_tin")+"') as x");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tdno",result.childNodes.item(0).getAttribute("u_tdno"));
                                                                 for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                        setInput("u_declaredowner",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                 }
                                                                 } else {
								setInputAmount("u_tin","");
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
								getTaxDues();
                                                                getSemiQuarterly();
								page.statusbar.showError("Invalid TIn No.");	
								return false;
							}
						} else {
							setInputAmount("u_tin","");
							setInput("u_yearfrom",0);
							setInput("u_yearto",0);
							getTaxDues();
                                                        getSemiQuarterly();
							page.statusbar.showError("Error retrieving faas record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                                
					var result = page.executeFormattedSearch("select docno from u_rptaxes where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and docstatus in ('D') and docno<>'"+getInput("docno")+"'");
					if (result!="") {
						alert('An existing payment form still in draft for this Tax Payer. System will retrieve the payment form.');
						setKey("keys",result);
						formEdit();
						return true;
					}
                                        
//                                        var result = page.executeFormattedSearch("select docno from u_rptaxes where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and docstatus in ('D') and docno<>'"+getInput("docno")+"'");
                                       
                                             
//					if (result2!="") {
//						
//						setKey("keys",result2);
//						
//					}

					setInput("u_yearfrom",0);
					setInput("u_yearto",0);
					getTaxDues();
                                        getSemiQuarterly();
					break;
				case "u_yearto":
					if (getInput("u_yearto")!=getPrivate("year")) {
						disableInput("u_advancepay");
						if (isInputChecked("u_advancepay")) {
							uncheckedInput("u_advancepay");
							advancePayGPSRPTAS();
						}	
					} else {
						if (getInput("u_year")!=getPrivate("year")) {
							disableInput("u_advancepay");
							if (isInputChecked("u_advancepay")) {
								uncheckedInput("u_advancepay");
								advancePayGPSRPTAS();
							}	
						} else enableInput("u_advancepay");
					}
					getTaxDues();
					break;
				
				case "u_idlelandrate":
					if (isInputChecked("u_idleland")) setInputAmount("u_taxidleland",utils.round(getInputNumeric("u_assvalue")*(getInputNumeric("u_idlelandrate")/100),2));
					else setInputAmount("u_taxidleland",0);
					computeTaxdueGPSRPTAS();
					break;
				case "u_discamount":
				case "u_penalty":
					computeTaxdueGPSRPTAS();
					break;
			}
                break;
	}			
	return true;
}

function onElementGetValidateParamsGPSRPTAS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementChangeGPSRPTAS(element,column,table,row) {
         switch (table) {
            default:
                switch(column) {
                    case "u_paymode":  
                        if (getInput(column)=="A") { 
                            getTaxDues(); 
                            computeTaxGPSRPTAS();
                        } else { 
                            getSemiQuarterly();
                        }
                    break;
                }
            break;
         }
                       
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
    switch (table) {
            case "T1":
                if (row==0) {
                        if (isTableInputChecked(table,column)) {
                                checkedTableInput(table,column,-1);
                        } else {
                                uncheckedTableInput(table,column,-1);
                        }
                } else {
                        if (getTableInputNumeric(table,"u_yrfr",row)==(getInputNumeric("u_year")+1) && getTableInput("T1","u_selected",row)=="0") {
                        } else {
                                
                                var pin=getTableInput("T1","u_pinno",row);
                                var billdate=formatDateToDB(getTableInput("T1","u_billdate",row)).replace(/-/g,"");
                                var selected=getTableInput("T1","u_selected",row);
                                var rc =  getTableRowCount("T1");
                                for (xxx = 1; xxx <= rc; xxx++) {
                                        if (isTableRowDeleted("T1",xxx)==false) {
                                            var arpbilldate = formatDateToDB(getTableInput("T1","u_billdate",xxx)).replace(/-/g,"");
                                            if (selected == 0) {
                                                if (getTableInput("T1","u_pinno",xxx)==pin && parseInt(arpbilldate)>= parseInt(billdate)) {
                                                    setTableInput("T1","u_selected",selected,xxx);
                                                }
                                            } else {
                                                if (getTableInput("T1","u_pinno",xxx)==pin && parseInt(arpbilldate) <= parseInt(billdate)) {
                                                    setTableInput("T1","u_selected",selected,xxx);
                                                }
                                            }
                                            
                                        }
                                }
                        }
                }
                u_ComputePenaltyDiscQuarSemiGPSRPTAS();
                computeTaxGPSRPTAS();
                break;
            case "T2":
                switch(column) {
                    case "viewtdnhistory":
                            if (row==0) {
//                                    var targetObjectId = '';
//                                    OpenLnkBtn(1080,600,'./udo.php?objectcode=u_tdnhistory' + '' + '&targetId=' + targetObjectId ,targetObjectId);
                            } else {
                                    selectTableRow("T2",row);	
                                    targetObjectId = 'viewtdnhistory';
                                    OpenPopup(1280,550,"./udp.php?&objectcode=u_rptdnhistory&df_refno2="+getTableInput("T2","u_docno",row)+"","RP TDN History");
                            }
                    break;
                    case "viewpaymenthistory":
                            if (row==0) {
//                                    var targetObjectId = '';
//                                    OpenLnkBtn(1080,600,'./udo.php?objectcode=u_tdnhistory' + '' + '&targetId=' + targetObjectId ,targetObjectId);
                            } else {
                                    selectTableRow("T2",row);	
                                    OpenPopup(1280,550,"./udp.php?&objectcode=u_rptpaymenthistory&df_refno2="+getTableInput("T2","u_docno",row)+"","RPT Payment History");
                            }
                    break;
                case "u_selected":
                    getTaxDues();
                    break;
                }
                if (row==0) {
                        if (isTableInputChecked(table,column)) {
                                checkedTableInput(table,column,-1);
                        } else {
                                uncheckedTableInput(table,column,-1);
                        }
                         getTaxDues();
                } else {
                    
                }
                setInput("u_yearfrom",0);
                setInput("u_yearto",0);
//                getTaxDues();
                break;
            case "T3":
                switch(column) {
                        case "u_selected":
                            var docno =getTableInput("T3","u_docno",row);
                            var year =getTableInputNumeric("T3","u_year",row);
                            var selected =getTableInput("T3","u_selected",row);
                            var ctr = 0;
                            var rc =  getTableRowCount("T1");
                            for (xxx = 1; xxx <= rc; xxx++) {
                                if (isTableRowDeleted("T1",xxx)==false) {
                                    if (getTableInput("T1","u_docno",xxx)== docno &&  year >= getTableInput("T1","u_yrfr",xxx) && year <= getTableInput("T1","u_yrto",xxx) ) {
                                           ctr = 1;
                                    }
                                }
                            }
                            if (ctr == 0){
                                setStatusMsg("Can't find same year in tax due",4000,1);
                                uncheckedTableInput(table,"u_selected",row);
                                return false;
                            }
                        break;
                }
            computeTaxGPSRPTAS();
            break;
            default:
                switch(column) {
                        case "u_advancepay":
                                advancePayGPSRPTAS();
                                break;
                        case "u_nodisc":
                                if(isInputChecked("u_nodisc")){
                                    noDiscount();
                                    computeTaxGPSRPTAS();
                                } else {
                                     getTaxDues();
                                }
                                break;
                        case "u_noepsf":
                                if(isInputChecked("u_noepsf")){
                                    noEPSF();
                                    computeTaxGPSRPTAS();
                                } else {
                                     getTaxDues();
                                }
                                break;
                        case "u_nopenaltycuryear":
                                if(isInputChecked("u_nopenaltycuryear")){
                                    noPenaltyCurYear();
                                    computeTaxGPSRPTAS();
                                } else {
                                     getTaxDues();
                                }
                                break;
                        case "noshtpenalty":
                                if(isInputChecked("noshtpenalty")){
                                    noSHTPenalty();
                                    computeTaxGPSRPTAS();
                                } else {
                                     getTaxDues();
                                }
                                break;
                        case "u_nopenalty":
                                if(isInputChecked("u_nopenalty")){
                                    noPenalty();
                                    computeTaxGPSRPTAS();
                                } else {
                                     getTaxDues();
                                }
                                break;
                        case "u_ismanualposting":
//                                if(isInputChecked("u_ismanualposting")){
//                                    showPopupFrame("popupFrameManualPosting",true);
//                                } else {
//                                    hidePopupFrame("popupFrameManualPosting",true);
//                                }
                                break;
                        case "u_isamnesty":
                                if(isInputChecked("u_isamnesty")){
                                    IsAmnesty();
                                    computeTaxGPSRPTAS();
                                } else {
                                     getTaxDues();
                                }
                                break;
                        case "u_yearbreak":
                                if(isInputChecked("u_yearbreak")){
                                    YearBreak();
                                    computeTaxGPSRPTAS();

                                }else{
                                    getTaxDues();
                                }

                                break;
                        case "u_idleland":
                                if (isInputChecked(column)) setInputAmount("u_taxidleland",utils.round(getInputNumeric("u_assvalue")*(getInputNumeric("u_idlelandrate")/100),2));
                                else setInputAmount("u_taxidleland",0);
                                computeTaxGPSRPTAS();
                                break;
                        
                }
                break;
    }
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
	switch (Id) {
		case "df_u_arpno":
                    if (getInput("u_searchby") == "1") {
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_varpno, u_ownername from u_rpfaas1 where company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'")); 
                    } else if (getInput("u_searchby") == "2") {
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_varpno, u_ownername from u_rpfaas2 where company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'")); 
                    } else {
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_varpno, u_ownername from u_rpfaas3 where company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'")); 
                    }
//			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno, u_pin, type, u_ownername from (select u_tdno, u_pin, 'Land' as type, u_ownername from u_rpfaas1 union all select u_tdno, u_pin, 'Building' as type, u_ownername from u_rpfaas2 union all select u_tdno, u_pin, 'Machinery' as type, u_ownername from u_rpfaas3) as x ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No`Owner Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_tdno":
                    if (getInput("u_searchby") == "1") {
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno, u_ownername from u_rpfaas1 where company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'")); 
                    } else if (getInput("u_searchby") == "2") {
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno, u_ownername from u_rpfaas2 where company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'")); 
                    } else {
                            params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno, u_ownername from u_rpfaas3 where company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'")); 
                    }
//			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno, u_pin, type, u_ownername from (select u_tdno, u_pin, 'Land' as type, u_ownername from u_rpfaas1 union all select u_tdno, u_pin, 'Building' as type, u_ownername from u_rpfaas2 union all select u_tdno, u_pin, 'Machinery' as type, u_ownername from u_rpfaas3) as x ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD No`Owner Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_tin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_ownertin,u_varpno, u_ownername,u_pin from (select u_varpno,u_ownertin, u_ownername,u_pin from u_rpfaas1 limit 10000 union all select u_varpno,u_ownertin, u_ownername,u_pin from u_rpfaas2 limit 10000 union all select u_varpno,u_ownertin, u_ownername,u_pin from u_rpfaas3) as x")); // group by u_ownertin
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TIN`Arp No`Declared Owner`PIN")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_pin, type, u_ownername from (select u_pin, 'Land' as type, u_ownername from u_rpfaas1 limit 10000 union all select u_pin, 'Building' as type, u_ownername from u_rpfaas2 limit 10000 union all select u_pin, 'Machinery' as type, u_ownername from u_rpfaas3) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSRPTAS(table) {
	switch (table) {
		
	}		
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	switch (table) {
		
	}		
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
	switch (table) {
		
	}		
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
        var params = new Array();
	switch (table) {
		case "T1":
                        if (getInput("u_ismanualposting") != 1) {
                                //page.statusbar.showError("You cannot change or edit this grid.");
                                //return false;
                        }
			if (elementFocused.substring(0,9)=="df_u_epsf") {
				focusTableInput(table,"u_epsf",row);
			} 
                        if (elementFocused.substring(0,11)=="df_u_taxdue") {
				focusTableInput(table,"u_taxdue",row);
			} 
                        if (elementFocused.substring(0,8)=="df_u_sef") {
				focusTableInput(table,"u_sef",row);
			} 
                        if (elementFocused.substring(0,12)=="df_u_penalty") {
				focusTableInput(table,"u_penalty",row);
			} 
                        if (elementFocused.substring(0,15)=="df_u_sefpenalty") {
				focusTableInput(table,"u_sefpenalty",row);
			} 
                        if (elementFocused.substring(0,12)=="df_u_taxdisc") {
				focusTableInput(table,"u_taxdisc",row);
			} 
                        if (elementFocused.substring(0,12)=="df_u_sefdisc") {
				focusTableInput(table,"u_sefdisc",row);
			}
			params["focus"]=false;
			break;
	}
	return params;
}

function onTableAfterEditRowGPSRPTAS(table,row) {
	switch (table) {
	}		
}

function computeTaxGPSRPTAS() {
	var rc =  getTableRowCount("T1"), epsf=0, idle=0, sht=0, shtpenalty=0, sef=0, tax=0, penalty=0,sefpenalty=0,discamount=0,sefdiscamount=0 ;
	
	var q1=0, q2=0, q3=0,q4=0,s1=0,s2=0 ;
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getTableInputNumeric("T1","u_yrfr",xxx) < getInputNumeric("u_yearfrom")) setInput("u_yearfrom",getTableInputNumeric("T1","u_yrfr",xxx));
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
				sht+= getTableInputNumeric("T1","u_sht",xxx);
				shtpenalty+= getTableInputNumeric("T1","u_shtpenalty",xxx);
                                idle+= getTableInputNumeric("T1","u_idle",xxx);
				epsf+= getTableInputNumeric("T1","u_epsf",xxx);
				tax+= getTableInputNumeric("T1","u_taxdue",xxx);
				sef+= getTableInputNumeric("T1","u_sef",xxx);
				penalty+= getTableInputNumeric("T1","u_penalty",xxx) ;
				sefpenalty+= getTableInputNumeric("T1","u_sefpenalty",xxx);
				discamount+= getTableInputNumeric("T1","u_taxdisc",xxx) ;
				sefdiscamount+= getTableInputNumeric("T1","u_sefdisc",xxx);
			}
		}
	}
        //for tax credit
        var rc2 =  getTableRowCount("T3"), idlecredit = 0, sefcredit=0, taxcredit=0, epsfcredit=0, shtcredit=0, penaltycredit=0,sefpenaltyredit=0 ;
        for (xxx = 1; xxx <= rc2; xxx++) {
		if (isTableRowDeleted("T3",xxx)==false) {
			if (getTableInputNumeric("T3","u_selected",xxx)=="1") {
                                shtcredit+= getTableInputNumeric("T3","u_sht",xxx);
                                idlecredit+= getTableInputNumeric("T3","u_idle",xxx);
                                epsfcredit+= getTableInputNumeric("T3","u_epsf",xxx);
                                taxcredit+= getTableInputNumeric("T3","u_taxdue",xxx);
				sefcredit+= getTableInputNumeric("T3","u_sef",xxx);
				penaltycredit+= getTableInputNumeric("T3","u_penalty",xxx);
				sefpenaltyredit+= getTableInputNumeric("T3","u_sefpenalty",xxx);
				
                        }
            }
        }
	setInputAmount("u_idlelandtotal",idle - idlecredit);
	setInputAmount("u_shttotal",sht - shtcredit);
	setInputAmount("u_shtpenaltytotal",shtpenalty);
	setInputAmount("u_epsftotal",epsf - epsfcredit);
	setInputAmount("u_tax",tax - taxcredit);
	setInputAmount("u_seftax",sef - sefcredit);
	setInputAmount("u_penalty",penalty - penaltycredit);
	setInputAmount("u_sefpenalty",sefpenalty - sefpenaltyredit);
	setInputAmount("u_discamount",discamount);
	setInputAmount("u_sefdiscamount",sefdiscamount);
	setInputAmount("u_linetotal",(tax - taxcredit)+(sef - sefcredit)+(penalty - penaltycredit)+(sefpenalty - sefpenaltyredit)-discamount-sefdiscamount);
	setInputAmount("u_totaltaxamount",(idle - idlecredit) +  (sht - shtcredit) + shtpenalty  + (epsf - epsfcredit) + (tax - taxcredit)+(sef - sefcredit)+(penalty - penaltycredit)+(sefpenalty - sefpenaltyredit)-discamount-sefdiscamount);
        focusInput("u_owneraddress");
}

function advancePayGPSRPTAS() {
	if (isInputChecked("u_advancepay")) {
		var data = new Array();
		var rc =  getTableRowCount("T2");
                if(getInput("u_noofadvanceyear")!=0 || getInput("u_noofadvanceyear")!="" ){
                    for(xxx1=1; xxx1<=getInput("u_noofadvanceyear"); xxx1++){
                        for (xxx2 = 1; xxx2 <= rc; xxx2++) {
                            if (isTableRowDeleted("T2",xxx2)==false) {
                                if (getTableInputNumeric("T2","u_selected",xxx2)=="1") {
                                    if (getInputNumeric("u_year")+xxx1 > getTableInputNumeric("T2","u_bilyear",xxx2)) {
                                        data["u_selected"] = 1;
                                        data["u_docno"] = getTableInput("T2","u_docno",xxx2);
                                        data["u_arpno"] = getTableInput("T2","u_arpno",xxx2);
                                        data["u_pinno"] = getTableInput("T2","u_pin",xxx2);
                                        data["u_yrfr"] = getInputNumeric("u_year")+xxx1;
                                        data["u_yrto"] = getInputNumeric("u_year")+xxx1;
                                        data["u_paymode"]= "A";
                                        data["u_payqtr"]= "4";
                                        data["u_billdate"]= "01/01/" + getInput("u_year");
                                        if (getTableInput("T2","u_kind",xxx2) == "LAND") {
                                            if (getTableInputNumeric("T2","u_assvalue",xxx2) > 50000) {
                                                    data["u_sht"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2)*.005);
                                            } else {
                                                    data["u_sht"] = "0.00";
                                            }
                                            data["u_epsf"] = formatNumericAmount(360.00);
                                        } else {
                                            data["u_sht"] = "0.00";
                                            data["u_epsf"] = "0.00";
                                        }
                                         if (getTableInput("T2","u_idle",xxx2) == "1") {
                                            data["u_idle"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2)*.01);
                                         } else {
                                            data["u_idle"] = "0.00";
                                         }
                                        data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2)*.01);
                                        data["u_sef"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2)*.01);
                                        data["u_penalty"] = formatNumericAmount(0);
                                        data["u_sefpenalty"] = formatNumericAmount(0);
                                        data["u_discperc"] = formatNumericAmount(getInputNumeric("u_advancediscperc"));
                                        data["u_taxdisc"] = formatNumericAmount(formatNumeric(data["u_taxdue"],0)*(getInputNumeric("u_advancediscperc")/100));
                                        data["u_sefdisc"] = formatNumericAmount(formatNumeric(data["u_sef"],0)*(getInputNumeric("u_advancediscperc")/100));
                                        data["u_linetotal"] = formatNumericAmount((formatNumeric(data["u_taxdue"],0) - formatNumeric(data["u_taxdisc"],0) ) * 2);
                                        insertTableRowFromArray("T1",data);
                                        setInput("u_paymode","A");
                                        //disableInput("u_paymode");
                                    }
                                }
                            }
                        }
                    }
                }else{
                    page.statusbar.showError("Invalid Advance year");
                    return false;
                }
		
	} else {
		var rc =  getTableRowCount("T1");
		for (xxx = 1; xxx <= rc; xxx++) {
			if (isTableRowDeleted("T1",xxx)==false) {
				if (getTableInputNumeric("T1","u_yrfr",xxx)>=(getInputNumeric("u_year")+1)) {
					deleteTableRow("T1",xxx);
				}
			}
		}
                setInput("u_paymode","A");
                enableInput("u_paymode");
                getTaxDues();
	}	
	computeTaxGPSRPTAS();
}

function getTaxDues() {
        
        showAjaxProcess();
   
	var data = new Array(),epsf = 0,idle = 0,sht = 0,shtpenalty = 0, sef=0, tax=0, penalty=0, sefpenalty=0, noofyrs=0, noofyrstopay=0, noofyrsepsf=0, noofyrstopayepsf=0, yearto=0, taxdisc=0, sefdisc=0;
	setInput("u_paymode",getInput("u_paymode"));
        
	clearTable("T1",true);
	setInput("u_advancepay",0);
	if (getInputNumeric("u_year")>0 ) {
            if (getInput("u_paymode")=="A") {
                    var discperc = 0 ;
                    var rc =  getTableRowCount("T2");
                    var result = page.executeFormattedQuery("Select u_discperc,u_dueday,u_discafterdue from u_rpdiscs where code = '"+formatDateToDB(getInput("u_assdate")).substr(5,2)+"'");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result"))>0) {
                                if(formatDateToDB(getInput("u_assdate")).substr(8,2) <= result.childNodes.item(0).getAttribute("u_dueday") ){
                                    discperc = parseFloat(result.childNodes.item(0).getAttribute("u_discperc"));
                                } else {
                                    discperc = parseFloat(result.childNodes.item(0).getAttribute("u_discafterdue"));
                                }
                            } else {
                             page.statusbar.showError("Invalid Discount");
                             return false;
                            }
                        }else{
                             page.statusbar.showError("Error retrieving discount. Try again, if problem persist, check the connection");
                             return false;
                        }
                
                for (xxx = 1; xxx <= rc; xxx++) {
                    if (isTableRowDeleted("T2",xxx)==false) {
                        if (getTableInputNumeric("T2","u_selected",xxx)=="1") {
                            var result = page.executeFormattedQuery("call sp_lgurptaxdue('lgu','main','"+getTableInput("T2","u_kind",xxx)+"','','','"+getTableInput("T2","u_docno",xxx)+"',"+getInput("u_year")+","+getInput("u_assdate").substr(0,2)+",'')");
                           
                            if (parseInt(result.getAttribute("result"))>0) {
                                for (xxx1 = 0; xxx1 < result.childNodes.length; xxx1++) {
                                        var div = 1;
                                        yearto = result.childNodes.item(xxx1).getAttribute("yrto");
                                        noofyrstopay = parseInt(result.childNodes.item(xxx1).getAttribute("yrto")) -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                        if (getInputNumeric("u_yearto")>0) {
                                                if (getInputNumeric("u_yearto")>=parseInt(result.childNodes.item(xxx1).getAttribute("yrfr"))) {
                                                } else continue;
                                                if (parseInt(result.childNodes.item(xxx1).getAttribute("yrto"))>getInputNumeric("u_yearto")) {
                                                        if (getInputNumeric("u_yearto") >= 2005) {
                                                            if (parseInt(result.childNodes.item(xxx1).getAttribute("yrfr"))>=2005) {
                                                                 noofyrstopayepsf = getInputNumeric("u_yearto") -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                                            } else {
                                                                 noofyrstopayepsf = getInputNumeric("u_yearto") -  2005 + 1;
                                                            }
                                                        } else {
                                                            noofyrstopayepsf = 0;
                                                        }
                                                        noofyrs = parseInt(result.childNodes.item(xxx1).getAttribute("yrto")) -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                                        noofyrstopay = getInputNumeric("u_yearto") -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                                        yearto = getInputNumeric("u_yearto");
                                                }	
                                        }
                                        if (xxx1==0)  setInput("u_yearfrom",result.childNodes.item(xxx1).getAttribute("yrfr"));
                                        if (xxx1==(result.childNodes.length-1) && xxx==rc) {
                                                setInput("u_yearto",yearto);
                                        }
                                        if (getInputNumeric("u_yearfrom")>parseInt(result.childNodes.item(xxx1).getAttribute("yrfr"))) {
                                                setInput("u_yearfrom",result.childNodes.item(xxx1).getAttribute("yrfr"));
                                        }
                                       
                                        if (result.childNodes.item(xxx1).getAttribute("lastpaymode") == "Q") div = 4;
                                        else if (result.childNodes.item(xxx1).getAttribute("lastpaymode") == "S") div = 2;
                                        data["u_selected"] = 1;
                                        data["u_pinno"] = result.childNodes.item(xxx1).getAttribute("pin");
                                        data["u_docno"] = result.childNodes.item(xxx1).getAttribute("arpno");
                                        data["u_arpno"] = result.childNodes.item(xxx1).getAttribute("tdno");
                                        data["u_noofyrs"] = noofyrstopay;
                                        data["u_yrfr"] = result.childNodes.item(xxx1).getAttribute("yrfr");
                                        data["u_yrto"] = yearto;
                                        data["u_paymode"] = result.childNodes.item(xxx1).getAttribute("lastpaymode");
                                        data["u_payqtr"] = result.childNodes.item(xxx1).getAttribute("payqtr");
                                        data["u_billdate"] = "01/01/"+getInput("u_year");
                                        tax = parseFloat(result.childNodes.item(xxx1).getAttribute("taxdue") / div);
                                        epsf = parseFloat(result.childNodes.item(xxx1).getAttribute("epsf") / div);
                                        sht = parseFloat(result.childNodes.item(xxx1).getAttribute("sht") / div);
                                        shtpenalty = parseFloat(result.childNodes.item(xxx1).getAttribute("shtpenalty") / div);
                                        idle = parseFloat(result.childNodes.item(xxx1).getAttribute("idle") / div);
                                        
                                        penalty = parseFloat(result.childNodes.item(xxx1).getAttribute("penalty"));
                                        sef = parseFloat(result.childNodes.item(xxx1).getAttribute("sefdue") / div);
                                        sefpenalty = parseFloat(result.childNodes.item(xxx1).getAttribute("sefpenalty"));
                                        
                                        //
                                        if (getInputNumeric("u_yearto")>0 && parseInt(result.childNodes.item(xxx1).getAttribute("yrto"))>getInputNumeric("u_yearto")) {
                                            if (getTableInput("T2","u_kind",xxx) == "LAND") {
                                                epsf = (360 * noofyrstopayepsf);
                                            } else {
                                                epsf = 0;
                                            }
//                                            if (getTableInput("T2","u_idle",xxx) == "1") {
//                                                epsf = (360 * noofyrstopayepsf);
//                                            } else {
//                                                epsf = 0;
//                                            }
                                                idle = (idle/noofyrs) * noofyrstopay;
                                                sht = (sht/noofyrs) * noofyrstopay;
                                                shtpenalty = (shtpenalty/noofyrs) * noofyrstopay;
                                                tax = (tax/noofyrs) * noofyrstopay;
                                                penalty = (penalty/noofyrs) * noofyrstopay;
                                                sef = (sef/noofyrs) * noofyrstopay;
                                                sefpenalty = (sefpenalty/noofyrs) * noofyrstopay;
                                        }
                                        if (epsf < 0 ) epsf = 0;
                                        if (sht < 0 ) sht = 0;
                                        if (shtpenalty < 0 ) shtpenalty = 0;
                                        if (idle < 0 ) idle = 0;
                                        data["u_discperc"] = "0.00";
                                        taxdisc = 0;
                                        sefdisc = 0;
                                        
                                        //For Discount
                                        if (formatDateToDB(getInput("u_assdate")).substr(0,4)==data["u_yrfr"]) {
                                                data["u_discperc"] = formatNumericAmount(discperc);
                                                if (formatDateToDB(getInput("u_assdate")).substr(5,2) <= 6) {
                                                    taxdisc = (parseFloat(data["u_discperc"]/100) * tax);
                                                    sefdisc = (parseFloat(data["u_discperc"]/100) * sef);
                                                } else {
                                                    penalty =  penalty - (parseFloat(data["u_discperc"]/100) * tax);
                                                    sefpenalty = sefpenalty - (parseFloat(data["u_discperc"]/100) * sef);
                                                }
                                                
                                        }
                                        taxdisc = parseFloat(taxdisc);
                                        sefdisc = parseFloat(sefdisc);
                                        if (result.childNodes.item(xxx1).getAttribute("lastpaymode") == "Q" || result.childNodes.item(xxx1).getAttribute("lastpaymode") == "S" ) {
                                            data["u_epsf"] = formatNumericAmount(0);
                                            data["u_idle"] = formatNumericAmount(0);
                                        } else {
                                            data["u_epsf"] = formatNumericAmount(epsf);
                                            data["u_idle"] = formatNumericAmount(idle);
                                        }
                                        data["u_shtpenalty"] = formatNumericAmount(shtpenalty);
                                        data["u_sht"] = formatNumericAmount(sht);
                                        data["u_taxdue"] = formatNumericAmount(tax);
                                        data["u_penalty"] = formatNumericAmount(penalty);
                                        data["u_sef"] = formatNumericAmount(sef);
                                        data["u_sefpenalty"] = formatNumericAmount(sefpenalty);
                                        data["u_taxdisc"] = formatNumericAmount(taxdisc);
                                        data["u_sefdisc"] = formatNumericAmount(sefdisc);
                                        penalty = Math.round(penalty * 100) / 100;
                                        taxdisc = Math.round(taxdisc * 100) / 100;
                                        sefpenalty = Math.round(sefpenalty * 100) / 100;
                                        sefdisc = Math.round(sefdisc * 100) / 100;
                                        data["u_linetotal"] = formatNumericAmount(tax+penalty-taxdisc + sef+sefpenalty-sefdisc);
                                        if (result.childNodes.item(xxx1).getAttribute("lastpaymode") == "Q"){
                                            var payqtr = parseInt(result.childNodes.item(xxx1).getAttribute("payqtr")) + 1;
                                            for (r = payqtr; r<=div; r++){
                                                switch(r){
                                                    case 2:
                                                        data["u_billdate"] = "04/01/"+yearto;
                                                        break;
                                                    case 3:
                                                        data["u_billdate"] = "07/01/"+yearto;
                                                        break;
                                                    case 4:
                                                        data["u_billdate"] = "10/01/"+yearto;
                                                        break;
                                                }
                                                data["u_payqtr"] = r;
                                                insertTableRowFromArray("T1",data);
                                            }
                                        } else {
                                            if (result.childNodes.item(xxx1).getAttribute("lastpaymode") == "S") {
                                                data["u_billdate"] = "07/01/"+yearto;
                                                data["u_payqtr"] = 4;
                                            }
                                            insertTableRowFromArray("T1",data);
                                        }
//                                        else if (result.childNodes.item(xxx1).getAttribute("lastpaymode") == "S") div = 2;
                                        
                                  
                                }
                            }
                        }
                    }
                }
			
	}
    }
    u_ComputePenaltyDiscQuarSemiGPSRPTAS();
    computeTaxGPSRPTAS();
    hideAjaxProcess();
    
}

function getPropertyTax() {
    showAjaxProcess();
    clearTable("T1",true);
    clearTable("T2",true);
    clearTable("T3",true);
    var data = new Array();
    var filterExp = "";
    if (getInput("u_tin")!="") filterExp += " and u_ownertin='"+getInput("u_tin")+"'";
    if (getInput("u_pin")!="") filterExp += " and u_pin='"+getInput("u_pin")+"'";
    var result = page.executeFormattedQuery("SELECT u_bilyear,u_bilqtr,u_pin, u_tdno, u_varpno, docno, u_property, u_ownertin, u_ownername, u_assvalue, u_barangay from (select u_bilyear,u_bilqtr,u_pin, u_tdno, u_varpno, docno, 'LAND' as u_property, u_ownertin, u_ownername, u_assvalue, u_barangay from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and (u_bilyear<u_expyear or u_bilyear=0 or u_expyear=0) union all select u_bilyear,u_bilqtr,u_pin, u_tdno, u_varpno, docno, 'BUILDING' as u_property, u_ownertin, u_ownername, u_assvalue, u_barangay from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and (u_bilyear<u_expyear or u_bilyear=0 or u_expyear=0) union all select u_bilyear,u_bilqtr,u_pin, u_tdno, u_varpno, docno, 'MACHINERY' as u_property, u_ownertin, u_ownername, u_assvalue, u_barangay from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and (u_bilyear<u_expyear or u_bilyear=0 or u_expyear=0) ) as a order by u_pin,u_varpno");
        if (parseInt(result.getAttribute("result"))>0) {
                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        data["u_kind"] = result.childNodes.item(xxx).getAttribute("u_property");
                        data["u_pin"] = result.childNodes.item(xxx).getAttribute("u_pin");
                        data["u_docno"] = result.childNodes.item(xxx).getAttribute("docno");
                        data["u_arpno"] = result.childNodes.item(xxx).getAttribute("u_varpno");
                        data["u_declaredowner"] = result.childNodes.item(xxx).getAttribute("u_ownername");
                        data["u_barangay"] = result.childNodes.item(xxx).getAttribute("u_barangay");
                        data["u_assvalue"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_assvalue"));
                        data["u_bilyear"] = result.childNodes.item(xxx).getAttribute("u_bilyear");
                        data["u_bilqtr"] = result.childNodes.item(xxx).getAttribute("u_bilqtr");
                        insertTableRowFromArray("T2",data);
                }
    }
    
    var result = page.executeFormattedQuery("select u_orrefno,u_year,u_tdno,u_taxdue,u_penalty,u_sef,u_sefpenalty,u_tin,code,u_epsf,u_sht from u_taxcredits where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and u_status='O' ");
        if (parseInt(result.getAttribute("result"))>0) {
                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        data["u_year"] = result.childNodes.item(xxx).getAttribute("u_year");
                        data["u_tdno"] = result.childNodes.item(xxx).getAttribute("u_tdno");
                        data["u_tin"] = result.childNodes.item(xxx).getAttribute("u_tin");
                        data["u_apprefno"] = result.childNodes.item(xxx).getAttribute("code");
                        data["u_orrefno"] = result.childNodes.item(xxx).getAttribute("u_orrefno");
                        data["u_epsf"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_epsf"));
                        data["u_sht"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_sht"));
                        data["u_taxdue"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_taxdue"));
                        data["u_penalty"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_penalty"));
                        data["u_sef"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_sef"));
                        data["u_sefpenalty"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_sefpenalty"));
                        insertTableRowFromArray("T3",data);
                }

        }
    hideAjaxProcess();
}

function noSHTPenalty() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_shtpenalty",formatNumericAmount(0.00),xxx);
			}
		}
	}
	
}
function noPenalty() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_penalty",formatNumericAmount(0.00),xxx);
                                    setTableInput("T1","u_sefpenalty",formatNumericAmount(0.00),xxx);
                                    setTableInput("T1","u_linetotal",formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx)+getTableInputNumeric("T1","u_penalty",xxx)-getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_sef",xxx)+getTableInputNumeric("T1","u_sefpenalty",xxx)-getTableInputNumeric("T1","u_sefdisc",xxx)),xxx);
			}
		}
	}
	
}
function noPenaltyCurYear() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1" && getTableInputNumeric("T1","u_yrto",xxx) == getInputNumeric("u_yearto")) {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_penalty",formatNumericAmount(0.00),xxx);
                                    setTableInput("T1","u_sefpenalty",formatNumericAmount(0.00),xxx);
                                    setTableInput("T1","u_linetotal",formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx)+getTableInputNumeric("T1","u_penalty",xxx)-getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_sef",xxx)+getTableInputNumeric("T1","u_sefpenalty",xxx)-getTableInputNumeric("T1","u_sefdisc",xxx)),xxx);
			}
		}
	}
	
}
function noEPSF() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_epsf",formatNumericAmount(0.00),xxx);
			}
		}
	}
	
}
function noDiscount() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_taxdisc",formatNumericAmount(0.00),xxx);
                                    setTableInput("T1","u_sefdisc",formatNumericAmount(0.00),xxx);
                                    setTableInput("T1","u_linetotal",formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx)+getTableInputNumeric("T1","u_penalty",xxx)-getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_sef",xxx)+getTableInputNumeric("T1","u_sefpenalty",xxx)-getTableInputNumeric("T1","u_sefdisc",xxx)),xxx);
			}
		}
	}
	
}
function IsAmnesty() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                 
                                    if (getPrivate("amnestyyear") > getTableInputNumeric("T1","u_yrto",xxx)){
                                        setTableInput("T1","u_penalty",formatNumericAmount(0.00),xxx);
                                        setTableInput("T1","u_sefpenalty",formatNumericAmount(0.00),xxx);
//                                        setTableInput("T1","u_taxdisc",formatNumericAmount(0.00),xxx);
//                                        setTableInput("T1","u_sefdisc",formatNumericAmount(0.00),xxx);
                                        setTableInput("T1","u_linetotal",formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx)+getTableInputNumeric("T1","u_penalty",xxx)-getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_sef",xxx)+getTableInputNumeric("T1","u_sefpenalty",xxx)-getTableInputNumeric("T1","u_sefdisc",xxx)),xxx);
                                    }
                        }
		}
	}
	
}

function getSemiQuarterly() {
        var data = new Array();
        var  linetotal = 0;
	var rc =  getTableRowCount("T1");
	for (xxx2 = 1; xxx2 <= rc; xxx2++) {
		if (isTableRowDeleted("T1",xxx2)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx2)=="1") {
				if (getTableInput("T1","u_paymode",xxx2) != "A") {
					page.statusbar.showWarning("Invalid payment mode for the selected property. Set to ANNUALLY first then change to other payment mode."); 
					return false;
				}
				//if (getInput("u_year")==getTableInputNumeric("T1","u_yrto",xxx2)){
                                    if(getInput("u_paymode")=="S"){
                                                data["u_selected"] = 1;
                                                data["u_docno"] = getTableInput("T1","u_docno",xxx2);
                                                data["u_arpno"] = getTableInput("T1","u_arpno",xxx2);
                                                data["u_pinno"] = getTableInput("T1","u_pinno",xxx2);
                                                data["u_assvalue"]=formatNumericAmount(getTableInputNumeric("T1","u_assvalue",xxx2) / 2);
                                                data["u_noofyrs"] = 1 ;
                                                data["u_yrfr"] = getTableInputNumeric("T1","u_yrfr",xxx2) ;
                                                data["u_yrto"] = getTableInputNumeric("T1","u_yrto",xxx2) ;
                                                data["u_paymode"] = "S" ;
                                                data["u_payqtr"]= "2";
                                                data["u_billdate"] = "01/01/"+getTableInputNumeric("T1","u_yrto",xxx2);
                                                data["u_idle"] = formatNumericAmount(getTableInputNumeric("T1","u_idle",xxx2));
                                                data["u_epsf"] = formatNumericAmount(getTableInputNumeric("T1","u_epsf",xxx2));
                                                data["u_sht"] = formatNumericAmount(getTableInputNumeric("T1","u_sht",xxx2) / 2);
                                                data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx2) / 2);
                                                data["u_sef"] = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx2) / 2 ) ;
                                                    for(xxx1=1;xxx1<=2;xxx1++){
                                                        if (xxx1==2) {
                                                                data["u_idle"] = formatNumericAmount(0);
                                                                data["u_epsf"] = formatNumericAmount(0);
                                                                data["u_billdate"]= "07/01/"+getTableInputNumeric("T1","u_yrto",xxx2);
                                                                data["u_payqtr"]= "4";
                                                                if (formatDateToDB(getInput("u_assdate")).replace(/-/g,"") > formatDateToDB(data["u_billdate"]).replace(/-/g,"")) data["u_selected"] = 1;
                                                                else data["u_selected"] = 0;
                                                        } 
                                                        linetotal = (Math.round(getTableInputNumeric("T1","u_taxdue",xxx2) / 2 * 100) / 100) * 2;
                                                        data["u_linetotal"] = formatNumericAmount(linetotal) ;
                                                        insertTableRowFromArray("T1",data);
                                                    }
                                                deleteTableRow("T1",xxx2);
                                    } else if (getInput("u_paymode")=="Q") {
                                            data["u_selected"] = 1;
                                            data["u_docno"] = getTableInput("T1","u_docno",xxx2);
                                            data["u_arpno"] = getTableInput("T1","u_arpno",xxx2);
                                            data["u_pinno"] = getTableInput("T1","u_pinno",xxx2);
                                            data["u_assvalue"]=formatNumericAmount(getTableInputNumeric("T1","u_assvalue",xxx2) / 4);
                                            data["u_noofyrs"] = 1 ;
                                            data["u_yrfr"] = getTableInputNumeric("T1","u_yrfr",xxx2) ;
                                            data["u_yrto"] = getTableInputNumeric("T1","u_yrto",xxx2) ;
                                            data["u_paymode"] = "Q" ;
                                            data["u_payqtr"]= "1";
                                            data["u_billdate"] = "01/01/"+getTableInputNumeric("T1","u_yrto",xxx2);
                                            data["u_idle"] = formatNumericAmount(getTableInputNumeric("T1","u_idle",xxx2));
                                            data["u_epsf"] = formatNumericAmount(getTableInputNumeric("T1","u_epsf",xxx2));
                                            data["u_sht"] = formatNumericAmount(getTableInputNumeric("T1","u_sht",xxx2) / 4);
                                            data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx2) / 4);
                                            data["u_sef"] = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx2) / 4 ) ;
                                           
                                            for (xxx1=1;xxx1<=4;xxx1++) {
                                              
                                            if (xxx1==2){
                                                        data["u_idle"] = formatNumericAmount(0);
                                                        data["u_epsf"] = formatNumericAmount(0);
                                                        data["u_billdate"]= "04/01/"+getTableInputNumeric("T1","u_yrto",xxx2);
                                                        data["u_payqtr"]= "2";
                                                        if (formatDateToDB(getInput("u_assdate")).replace(/-/g,"") > formatDateToDB(data["u_billdate"]).replace(/-/g,"")) data["u_selected"] = 1;
                                                        else data["u_selected"] = 0;
                                                    
                                            }else if (xxx1==3){
                                                        data["u_idle"] = formatNumericAmount(0);
                                                        data["u_epsf"] = formatNumericAmount(0);
                                                        data["u_billdate"]= "07/01/"+getTableInputNumeric("T1","u_yrto",xxx2);
                                                        data["u_payqtr"]= "3";
                                                        if (formatDateToDB(getInput("u_assdate")).replace(/-/g,"") > formatDateToDB(data["u_billdate"]).replace(/-/g,"")) data["u_selected"] = 1;
                                                        else data["u_selected"] = 0;
                                            }else if (xxx1==4){
                                                        data["u_idle"] = formatNumericAmount(0);
                                                        data["u_epsf"] = formatNumericAmount(0);
                                                        data["u_billdate"]= "10/01/"+getTableInputNumeric("T1","u_yrto",xxx2);
                                                        data["u_payqtr"]= "4";
                                                        if (formatDateToDB(getInput("u_assdate")).replace(/-/g,"") > formatDateToDB(data["u_billdate"]).replace(/-/g,"")) data["u_selected"] = 1;
                                                        else data["u_selected"] = 0;
                                            }
                                            linetotal = (Math.round(getTableInputNumeric("T1","u_taxdue",xxx2) / 4 * 100) / 100) * 2;
                                            data["u_linetotal"] = formatNumericAmount(linetotal) ;
                                            insertTableRowFromArray("T1",data);
                                        }
                                         deleteTableRow("T1",xxx2);
                                    }
                                   
                                   
                               // }
                                    
			}
		}
	}
        u_ComputePenaltyDiscQuarSemiGPSRPTAS();
	computeTaxGPSRPTAS();
        
}
function u_TaxCreditGPSRPTAS() {
            OpenPopup(600,300,"./udo.php?&objectcode=u_taxcredits&formAction=e","Tax Credit");
}

function u_ComputePenaltyDiscQuarSemiGPSRPTAS() {
        var data = new Array();
	var rc =  getTableRowCount("T1");
	
	
	for (xxx = 1; xxx <= rc; xxx++) {
            if (isTableRowDeleted("T1",xxx)==false) {
                if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
                    var discperc = 0,penperc = 0, sefpenalty = 0 , penalty= 0 , shtpenalty= 0 ,sefdisc= 0,taxdisc=0,intpercvalue = 0;
                    var duedate = formatDateToDB(getTableInput("T1","u_billdate",xxx)).replace(/-/g,"");
                    var paydate = formatDateToDB(getInput("u_assdate")).replace(/-/g,"");
                    var duemonth = parseInt(duedate.substr(0,6));
                    var paymonth = parseInt(paydate.substr(0,6));

                    var dueyear = parseInt(duedate.substr(0,4));
                    var payyear = parseInt(paydate.substr(0,4));
                    if (payyear>dueyear) intpercvalue = (((payyear-dueyear) * 12) - (parseInt(duedate.substr(4,2)) - parseInt(paydate.substr(4,2)))) + 1;
                    else intpercvalue = (paymonth-duemonth) + 1;
                    if (intpercvalue<=0) intpercvalue = 1;
                    
                    var r = page.executeFormattedQuery("select u_discperc,u_penperc,u_discafterdue,u_dueday from u_rpsemiquardiscspen where u_paymode = '"+getTableInput("T1","u_paymode",xxx)+"' and u_qtr = '"+getTableInput("T1","u_payqtr",xxx)+"' and "+intpercvalue+">= u_monthfr and "+intpercvalue+" <= u_monthto ");
                    if (parseInt(r.getAttribute("result"))>0) { 
                            if(formatDateToDB(getInput("u_assdate")).substr(8,2) <= r.childNodes.item(0).getAttribute("u_dueday") ){
                                discperc = parseFloat(r.childNodes.item(0).getAttribute("u_discperc"));
                            } else {
                                discperc = parseFloat(r.childNodes.item(0).getAttribute("u_discafterdue"));
                            }
                            penperc = formatNumericAmount(r.childNodes.item(0).getAttribute("u_penperc"));
                            sefpenalty = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx) * (penperc/100));
                            penalty = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx) * (penperc/100));
                            sefdisc = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx) * (discperc/100));
                            taxdisc = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx) * (discperc/100));
                            setTableInput("T1","u_sefpenalty",sefpenalty,xxx);
                            setTableInput("T1","u_penalty",penalty,xxx);
                            setTableInput("T1","u_sefdisc",sefdisc,xxx);
                            setTableInput("T1","u_taxdisc",taxdisc,xxx);
                            setTableInput("T1","u_linetotal",formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx)+getTableInputNumeric("T1","u_penalty",xxx)-getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_sef",xxx)+getTableInputNumeric("T1","u_sefpenalty",xxx)-getTableInputNumeric("T1","u_sefdisc",xxx)),xxx);
                    }
                    
                    //For SHT Penalty
                    if (intpercvalue > 0 && paydate > duedate && getTableInput("T1","u_paymode",xxx) != "A") {
                        if(formatDateToDB(getInput("u_assdate")).substr(8,2) <= 20 ){
                            shtpenalty = formatNumericAmount( (getTableInputNumeric("T1","u_sht",xxx) * .1) + (getTableInputNumeric("T1","u_sht",xxx) * (.01 * (intpercvalue - 1))) );
                        } else {
                            shtpenalty = formatNumericAmount( (getTableInputNumeric("T1","u_sht",xxx) * .1) + (getTableInputNumeric("T1","u_sht",xxx) * (.01 * intpercvalue )) );
                        }
                         setTableInput("T1","u_shtpenalty",formatNumericAmount(shtpenalty),xxx);
                    }
                   
                    
//
                }
            }
	}
	computeTaxGPSRPTAS();
}

function u_cashierGPSRPTAS() {
        OpenPopup(1024,700,"./udo.php?&objectcode=u_lgupos","Payment");
}

function u_SearchPropertyGPSRPTAS() {
        OpenPopup(1280,700,"./udp.php?&objectcode=u_billsearchproperty","Search Property");
}

function manualPostingGPSRPTAS(){
       
        if (isTableInput("T51","userid")) {
            if (getTableInput("T51","userid")=="") {
                    showPopupFrame("popupFrameManualPosting",true);
                    focusTableInput("T51","userid");
                    return false;
            }
            if (getTableInput("T51","remarks")=="") {
                    showPopupFrame("popupFrameManualPosting",true);
                    focusTableInput("T51","remarks");
                    return false;
            }
        }
      
        var result = page.executeFormattedQuery("SELECT username, u_manualpostingflag from users where userid = '"+getTableInput("T51","userid")+"' and hpwd = '"+MD5(getTableInput("T51","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        if (result.childNodes.item(0).getAttribute("u_manualpostingflag") == 0) {
                            page.statusbar.showError("You are not allowed for Manual Posting");
                            disableInput("u_assdate");
                            setInput("u_ismanualposting",0);
                            return false;
                        } else {
                            enableInput("u_assdate");
                            setInput("u_ismanualposting",1);
                            if (isPopupFrameOpen("popupFrameManualPosting")) {
                                hidePopupFrame('popupFrameManualPosting');
                            }
                        }
                        
                }
        } else {
            page.statusbar.showError("Invalid user or password.");
            disableInput("u_assdate");
            setInput("u_ismanualposting",0);
            return false;
        }
}

function AdjustPaidYearGPSRPTAS(){

        if (isTableInput("T61","userid")) {
            if (getTableInput("T61","userid")=="") {
                    showPopupFrame("popupFrameAdjustPaidYear",true);
                    focusTableInput("T61","userid");
                    return false;
            }
            if (getTableInput("T61","remarks")=="") {
                    showPopupFrame("popupFrameAdjustPaidYear",true);
                    focusTableInput("T61","remarks");
                    return false;
            }
        }
        var result = page.executeFormattedQuery("SELECT username, u_manualpostingflag from users where userid = '"+getTableInput("T61","userid")+"' and hpwd = '"+MD5(getTableInput("T61","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        if (result.childNodes.item(0).getAttribute("u_manualpostingflag") == 0) {
                            page.statusbar.showError("You are not allowed for Year Adjustment");
                            disableInput("u_assdate");
                            setInput("u_ismanualposting",0);
                            return false;
                        } else {
                            if (isPopupFrameOpen("popupFrameAdjustPaidYear")) {
                                hidePopupFrame('popupFrameAdjustPaidYear');
                            }
                            openupdpays();
                        }
                }
        } else {
            page.statusbar.showError("Invalid user or password.");
            disableInput("u_assdate");
            setInput("u_ismanualposting",0);
            return false;
        }
}

function showPopupManualPosting(){
    showPopupFrame("popupFrameManualPosting",true);
    focusTableInput("T51","userid");
}

function showPopupAdjustPaidYear(){
    focusTableInput("T61","userid");
    showPopupFrame("popupFrameAdjustPaidYear",true);
    setTableInput("T61","userid","");
    setTableInput("T61","password","");
}

function openupdpays() {
        
	OpenPopup(1280,600,"./udo.php?&objectcode=u_rpupdpays&sf_keys="+getInput("code")+"&formAction=e","UpdPays");	
}
