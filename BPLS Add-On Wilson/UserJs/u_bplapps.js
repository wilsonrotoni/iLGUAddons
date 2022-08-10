// page events
page.events.add.submitreturn('onPageSubmitReturnGPSBPLS');
page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
page.events.add.submit('onPageSubmitGPSBPLS');
//page.events.add.cfl('onCFLGPSBPLS');
//page.events.add.cflgetparams('onCFLGetParamsGPSBPLS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBPLS');

// element events
//page.elements.events.add.focus('onElementFocusGPSBPLS');
page.elements.events.add.keydown('onElementKeyDownGPSBPLS');
page.elements.events.add.validate('onElementValidateGPSBPLS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSBPLS');
//page.elements.events.add.changing('onElementChangingGPSBPLS');
page.elements.events.add.change('onElementChangeGPSBPLS');
page.elements.events.add.click('onElementClickGPSBPLS');
//page.elements.events.add.cfl('onElementCFLGPSBPLS');
//page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSBPLS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
page.tables.events.add.select('onTableSelectRowGPSBPLS');
//alert(getPrivate("userid"));
if(getPrivate("userid")!="bplcommon"){
        var tabberOptions = {
          'manualStartup':true,
           'onClick': function(argsObj) { 
                    if (argsObj.tabber.id == 'tab1') {
                                switch (getTabIDByName(argsObj.tabber.tabs[argsObj.index].headingText)) {
                                        case "Business Activity": 
                                        case "Assessments": 
                                            if (getPrivate("bplassessor")=="1" || getPrivate("bplapprover")=="1") {
                                                } else {
                                                        setStatusMsg("Only the Assessor or Approver can view this information.",4000,1);
                                                        return false;
                                                }	
                                                break;
                                        case "Fire Dept Assessments":
                                             if (getPrivate("firedept")=="1" ) {
                                                } else {
                                                        setStatusMsg("Only authorized user can view this information.",4000,1);
                                                        return false;
                                                }	
                                                break;  
                                }
                    } 
                return true;
                }
        };    
}

function onPageSubmitReturnGPSBPLS(action,sucess,error) {
    if(getPrivate("userid")=="bplcommon"){
        if (sucess) {
            if (action=="a"){
                alert("This is your Reference No ["+getInput("docno")+"]");
                setTimeout("formSubmit('?')",7000); 
                setInput("u_businessname","");
                OpenReportSelect('printer');
            }
        }
    }
    if ((action=="a" || action=="sc") && sucess && getInput("docstatus") == "Approved") {
                OpenReportSelect('printer');
    }
}


function onPageLoadGPSBPLS() {
    if(getPrivate("userid")!="bplcommon"){
	if (getVar("formSubmitAction")=="a") {
		selectTab("tab1",0);
                focusInput("u_lastname");
                setTableInput("T1","u_year",getInput("u_year"));
                setTableInput("T5","u_year",getInput("u_year"));
		if (getInput("u_apptype")!="") {
			setAppTypeTaxes();
			setBarangayFees();
		}
	}else{
            selectTab("tab1",0);
        }
	if (getInput("docstatus")!="Approved") {
		if (getInput("u_apptype")=="NEW") {
			disableTableInput("T1","u_essential");
			disableTableInput("T1","u_nonessential");
			enableTableInput("T1","u_capital");
		} else {
			disableTableInput("T1","u_capital");
			enableTableInput("T1","u_essential");
			enableTableInput("T1","u_nonessential");
		}
	}
       
    }

}

function onPageResizeGPSBPLS(width,height) {
}

function onPageSubmitGPSBPLS(action) {
	if (action=="a" || action=="sc") {
                if(getInput("u_apptype")!="NEW") if (isInputEmpty("u_appno",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_bbrgy",null,null,"tab1",0)) return false;
                if (getInput("u_empcount") <= 0){
                    focusInput("u_mempcount");
                    page.statusbar.showError("Employee count is required");
                    return false;
                }	
		if (isInputEmpty("u_tradename",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_paymode",null,null,"tab1",1)) return false;
		if (isInputEmpty("u_ownertype",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_orgtype",null,null,"tab1",0)) return false;
		if (getInput("u_orgtype")=="SINGLE") {
                    if (isInputEmpty("u_lastname",null,null,"tab1",0)) return false;
                    if (isInputEmpty("u_firstname",null,null,"tab1",0)) return false;
		}
//		if (isInputChecked("u_sameasowneraddr")) {
//			if (isInputEmpty("u_brgy",null,null,"tab1",1)) return false;
//		} else {
//			if (isInputEmpty("u_bbrgy",null,null,"tab1",1)) return false;
//		}
	}
	return true;
}

function onCFLGPSBPLS(Id) {
	return true;
}

function onCFLGetParamsGPSBPLS(Id,params) {
	return params;
}

function onTaskBarLoadGPSBPLS() {
}

function onElementFocusGPSBPLS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSBPLS(element,event,column,table,row) {
     var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);

	switch (sc_press) {
//		case "ESC":
//			if (isPopupFrameOpen("popupFramePayments")) {
//				hidePopupFrame('popupFramePayments');
//				setT1FocusInputGPSPOS();
//			}
//			break;
                
		case "ALT+1":
                    selectTab("tab1",0);
                    focusInput("u_lastname");
                break;
		case "ALT+2":
                    selectTab("tab1",1);
                    focusInput("u_llastname");
                break;
		case "ALT+3":
                    selectTab("tab1",2);
                    focusInput("u_businesschar");
                break;
		case "ALT+4":
                    selectTab("tab1",3);
                      focusInput("u_paymode");  
                    focusInput("u_businesschar");                    
                break;
		case "ALT+5":
                    selectTab("tab1",4);
                    focusTableInput("T6","u_lastname"); 
                break;
		case "ALT+6":
                    selectTab("tab1",5);
                    focusInput("u_reqappno"); 
                break;
		case "ALT+7":
                    selectTab("tab1",6);
                    focusInput("u_remarks");  
                break;
		case "ALT+8":
                    selectTab("tab1",7);
                    focusTableInput("T2","u_remarks");  
                break;
		case "F4":
			formSubmit();
			break;
		case "F6":
			u_forApprovalGPSBPLS();
			break;
		case "F7":
			u_approveGPSBPLS();
			break;
		case "F9":
			u_disapproveGPSBPLS();
			break;
		case "ENTER":
//                            next.focus();
//                            var self = $(this)
//                              , form = self.parents('form:eq(0)')
//                              , focusable
//                              , next
//                              ;
//                          
//                                focusable = form.find('input,a,select,button,textarea').filter(':visible');
//                                next = focusable.eq(focusable.index(this)+1);
//                                if (next.length) {
//                                    next.focus();
//                                } else {
//                                    form.submit();
//                                }
//                                alert(next);
////                              return false;
                            
                    
			break;
//		default:
//			switch (table) {
//				case "T1":
//					break;
//				default:	
//					switch (column) {
//						case "u_totalamount2":
//							if (sc_press=="ENTER") {
//								setInputAmount(column,getInputNumeric(column),true);
//								focusInput(column);
//							}
//							break;
//							
//					}
//					break;
//			}
//			break;
	}
}

function onElementValidateGPSBPLS(element,column,table,row) {
	switch (table) {
                case "T1":
                        switch (column){
                             case "u_capital":
                             case "u_essential":
                             case "u_nonessential":
                                        var annualtaxcoderc=0, electricalcertrc = 0, capital = 0, essential = 0, nonessential = 0, baseperc=100, taxamount=0, taxrate=0, excessamount = 0, fromamount = 0;
                                        var capitalamount = 0,annualtaxamount = 0;
                                                
                                        if (getInput("u_apptype")=="RENEW") {
                                                var grosssales1 = 0, baseperc1=100, taxamount1=0, taxrate1=0, fromamount1 = 0, excessamount1 = 0,compbased = '';
                                                grosssales1 = getTableInputNumeric("T1","u_essential")+getTableInputNumeric("T1","u_nonessential");
                                                
                                                var result = page.executeFormattedQuery("select u_compbased,u_fixedamount from u_bpllines where code = '"+getTableInput("T1","u_businessline")+"'");
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            compbased = result.childNodes.item(0).getAttribute("u_compbased");
                                                        }   
                                                }
                                                
                                                if (compbased == 1) { 
                                                        taxamount1 = parseFloat(result.childNodes.item(0).getAttribute("u_fixedamount"));
                                                } else {
                                                        var result1 = page.executeFormattedQuery("select b.u_excessamount,a.u_baseperc, b.u_amount, b.u_rate,b.u_from from u_bpltaxclasses a, u_bpltaxclasstaxes b where b.code=a.code and "+grosssales1+" >= b.u_from and ("+grosssales1+" <=b.u_to or b.u_to=0) and a.code = '"+getTableInput("T1","u_taxclass")+"'");
                                                        if (result1.getAttribute("result1")!= "-1") {
                                                                if (parseInt(result1.getAttribute("result"))>0) {
                                                                        baseperc1 = parseFloat(result1.childNodes.item(0).getAttribute("u_baseperc"));
                                                                        taxamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_amount"));
                                                                        taxrate1 = parseFloat(result1.childNodes.item(0).getAttribute("u_rate"));
                                                                        excessamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_excessamount"));
                                                                        fromamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_from"));
                                                                }		
                                                        } else {
                                                                page.statusbar.showError("Error retrieving tax classificaton. Try Again, if problem persists, check the connection.");	
                                                                return false;
                                                        }
                                                }
                                                
                                                
                                                
                                                
                                        }
                                                        
                                        if (getInput("u_apptype")=="NEW") {
                                                annualtaxamount = getTableInputNumeric("T1","u_capital")*.01/20;
                                                if(annualtaxamount<220) annualtaxamount = 220;
                                                setTableInputAmount("T1","u_btaxlinetotal",annualtaxamount);
                                        } else if (getInput("u_apptype")=="RENEW") {

                                            if (taxamount1>0 && excessamount1==0 && taxrate1==0) {
                                                annualtaxamount = taxamount1*(baseperc1/100);
                                            }else if(taxamount1==0 && excessamount1==0 && taxrate1>0 ) {
                                                annualtaxamount = taxrate1*grosssales1*(baseperc1/100);
                                            }else if(taxamount1>0 && excessamount1>0 && taxrate1>0 ){
                                                annualtaxamount = ((((grosssales1 - fromamount1) / excessamount1) * taxrate1 ) + taxamount1)*(baseperc1/100);
                                            }
                                                if(annualtaxamount<220) annualtaxamount = 220;
                                                setTableInputAmount("T1","u_btaxlinetotal",annualtaxamount);
                                        }
                                                  
//                                        //compute total business tax
//                                        var rc2 = getTableRowCount("T1"),btaxamount=0;
//                                        for (i = 1; i <= rc2; i++) {
//                                                if (isTableRowDeleted("T1",i)==false) {
//                                                        btaxamount+= getTableInputNumeric("T1","u_btaxlinetotal",i);             
//                                                }
//                                        }
//
//                                        var	rc = getTableRowCount("T5");
//                                        for (xxx = 1; xxx <=rc; xxx++) {
//                                                if (isTableRowDeleted("T5",xxx)==false) {
//                                                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("annualtaxcode")) {
//                                                                alert
//                                                                setTableInputAmount("T5","u_amount",btaxamount,xxx);
//                                                                annualtaxcoderc=xxx;
//                                                        }
//                                                }
//                                        }
//                                        if (annualtaxcoderc==0) {
//                                                var data = new Array();
//                                                data["u_feecode"] = getPrivate("annualtaxcode");
//                                                data["u_feedesc"] = getPrivate("annualtaxdesc");
//                                                data["u_common"] = 1;
//                                                data["u_amount"] = formatNumericAmount(btaxamount);
//                                                insertTableRowFromArray("T5",data);
//                                        }
//                                        computeTotalAssessment();
                                break;
                        }
                    break;
                case "T3":
                        switch (column){
                             case "u_amount":
                                       var rc = getTableRowCount("T3"),total=0;
                                            for (i = 1; i <= rc; i++) {
                                                    if (isTableRowDeleted("T3",i)==false) {
                                                            total+= getTableInputNumeric("T3","u_amount",i);

                                                    }
                                                  
                                            }
                                              setTableInputAmount("T3","u_amount",total);
//                                                    alert(total);
                                    break;
                        }
		case "T5":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select a.code, a.name,a.u_amount,a.u_seqno,a.u_regulatory  from u_bplfees a where a.code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select a.code, a.name,a.u_amount,a.u_seqno,a.u_regulatory  from u_bplfees a  name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
								setTableInput(table,"u_seqno",result.childNodes.item(0).getAttribute("u_seqno"));
								setTableInput(table,"u_regulatory",result.childNodes.item(0).getAttribute("u_regulatory"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			break;
		case "T8":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			break;
		default:
			switch (column) {
                                case "u_lastname":
                                case "u_firstname":
                                case "u_middlename":
                                case "u_telno":
                                case "u_owneraddress":
                                case "u_tlastname":
                                case "u_bbldgno":
                                case "u_block":
                                case "u_blotno":
                                case "u_bstreet":
                                case "u_bphaseno":
                                case "u_baddressno":
                                case "u_corpname":
                                case "u_llastname":
                                case "u_remarks":
                                case "u_lessoraddress":
                                case "u_bvillage":
                                case "u_bbldgname":
                                case "u_bunitno":
                                case "u_bfloorno":
                                case "u_blandmark":
                                    setInput(column,getInput(column).toUpperCase());
                                break;
//                                case "u_businessarea":
//                                    var rc = getTableRowCount("T5"),sqm=0;
//                                    sqm= getInputNumeric("u_businessarea",i)*15;
//                                                                                           //	alert(sqm);
//                                    for (i = 1; i <= rc; i++) {
//                                        if (isTableRowDeleted("T5",i)==false) {
//                                            if (getTableInput("T5","u_feecode",i)==getPrivate("garbagefeecode")) {
//                                                setTableInputAmount("T5","u_amount",sqm,i);
//                                            }
//                                        }
//                                    }
//                                                
//                                break;
				case "u_appno":
                                         if (getInput(column)!="") {
                                                var result = page.executeFormattedQuery("select name,u_tradename, u_firstname,u_lastname,u_middlename,u_btelno,u_bbldgno,u_bbldgname,u_bunitno,u_bstreet,u_bbrgy,u_bfloorno,u_bblock,u_bvillage from u_bplmds where code='"+getInput(column)+"'");	
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                                setInput("u_businessname",result.childNodes.item(0).getAttribute("name"));
                                                                setInput("u_tradename",result.childNodes.item(0).getAttribute("u_tradename"));
                                                                setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
                                                                setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
                                                                setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
                                                                setInput("u_btelno",result.childNodes.item(0).getAttribute("u_btelno"));
                                                                setInput("u_bbldgno",result.childNodes.item(0).getAttribute("u_bbldgno"));
                                                                setInput("u_bbldgname",result.childNodes.item(0).getAttribute("u_bbldgname"));
                                                                setInput("u_bunitno",result.childNodes.item(0).getAttribute("u_bunitno"));
                                                                setInput("u_bstreet",result.childNodes.item(0).getAttribute("u_bstreet"));
                                                                setInput("u_bbrgy",result.childNodes.item(0).getAttribute("u_bbrgy"));
                                                                setInput("u_bfloorno",result.childNodes.item(0).getAttribute("u_bfloorno"));
                                                                setInput("u_bblock",result.childNodes.item(0).getAttribute("u_bblock"));
                                                                setInput("u_bvillage",result.childNodes.item(0).getAttribute("u_bvillage"));
                                                        } else {
                                                                setInput("u_businessname","");
                                                                setInput("u_tradename","");
                                                                setInput("u_firstname","");
                                                                setInput("u_lastname","");
                                                                setInput("u_middlename","");
                                                                setInput("u_btelno","");
                                                                setInput("u_bbldgno","");
                                                                setInput("u_bbldgname","");
                                                                setInput("u_bunitno","");
                                                                setInput("u_bstreet","");
                                                                setInput("u_bbrgy","");
                                                                setInput("u_bfloorno","");
                                                                setInput("u_bblock","");
                                                                setInput("u_bvillage","");
                                                                page.statusbar.showError("Invalid Account No. Please click new instead");	
                                                                return false;
                                                        }
                                                } else {
                                                                setInput("u_businessname","");
                                                                setInput("u_tradename","");
                                                                setInput("u_firstname","");
                                                                setInput("u_lastname","");
                                                                setInput("u_middlename","");
                                                                setInput("u_btelno","");
                                                                setInput("u_bbldgno","");
                                                                setInput("u_bbldgname","");
                                                                setInput("u_bunitno","");
                                                                setInput("u_bstreet","");
                                                                setInput("u_bbrgy","");
                                                                setInput("u_bfloorno","");
                                                                setInput("u_bblock","");
                                                                setInput("u_bvillage","");
                                                        page.statusbar.showError("Error retrieving Account No record. Try Again, if problem persists, check the connection.");	
                                                        return false;
                                                }
                                         }
                                        break;
				case "u_reqappno":
                                            clearTable("T9",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select u_isonlineapp,u_businessname,u_lastname,u_firstname,u_middlename,u_owneraddress,u_contactno,u_bbrgy,u_bbldgno,u_bblock,u_blotno,u_bvillage,u_bstreet,u_bphaseno,u_baddressno,u_bbldgname,u_bunitno,u_bfloorno,u_btelno,u_year,u_feecode,u_feedesc,sum(u_amount) u_amount, sum(u_surcharge)  as u_surcharge, u_seqno from ( select a.u_isonlineapp,a.u_businessname,a.u_lastname,a.u_firstname,a.u_middlename,a.u_owneraddress,a.u_contactno,a.u_bbrgy,a.u_bbldgno,a.u_bblock,a.u_blotno,a.u_bvillage,a.u_bstreet,a.u_bphaseno,a.u_baddressno,a.u_bbldgname,a.u_bunitno,a.u_bfloorno,a.u_btelno,b.u_year,b.u_feecode,b.u_feedesc,b.u_amount, 0 as u_surcharge,b.u_seqno from u_bldgapps a inner join u_bldgappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' and docstatus not in('CN','D') and b.u_feecode != '0506' union all select a.u_isonlineapp,a.u_businessname,a.u_lastname,a.u_firstname,a.u_middlename,a.u_owneraddress,a.u_contactno,a.u_bbrgy,a.u_bbldgno,a.u_bblock,a.u_blotno,a.u_bvillage,a.u_bstreet,a.u_bphaseno,a.u_baddressno,a.u_bbldgname,a.u_bunitno,a.u_bfloorno,a.u_btelno,b.u_year,'0012' u_feecode,'Building Permit Fee' u_feedesc,0 u_amount , b.u_amount as u_surcharge,b.u_seqno from u_bldgapps a inner join u_bldgappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' and docstatus not in('CN','D') and b.u_feecode = '0506' ) as x group by u_feecode ");	
                                                    var data = new Array();
                                                    var rc = getTableRowCount("T5");
                                                    var reqtotal = 0;
                                                    showAjaxProcess();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (var iii=0; iii<result.childNodes.length; iii++) {
                                                                    var feerc = 0;
                                                                    for (xxx = 1; xxx <=rc; xxx++) {
                                                                        if (isTableRowDeleted("T5",xxx)==false) {
                                                                            if (getTableInput("T5","u_feecode",xxx)==result.childNodes.item(iii).getAttribute("u_feecode") ) {
                                                                            setTableInputAmount("T5","u_amount",result.childNodes.item(iii).getAttribute("u_amount"),xxx);
                                                                            if (getTableInput("T5","u_year",xxx) == result.childNodes.item(iii).getAttribute("u_year")){
                                                                                setTableInputAmount("T5","u_surcharge",result.childNodes.item(iii).getAttribute("u_surcharge"),xxx);
                                                                            }
                                                                            reqtotal += parseInt(result.childNodes.item(iii).getAttribute("u_amount")) + parseInt(result.childNodes.item(iii).getAttribute("u_surcharge"));
                                                                            feerc=xxx;
                                                                            }
                                                                        }
                                                                    }
                                                                    if (feerc==0) {
                                                                        var data = new Array();
                                                                        data["u_year"] = result.childNodes.item(iii).getAttribute("u_year");
                                                                        data["u_feecode"] = result.childNodes.item(iii).getAttribute("u_feecode");
                                                                        data["u_feedesc"] = result.childNodes.item(iii).getAttribute("u_feedesc");
                                                                        data["u_regulatory"] = 1;
                                                                        data["u_surcharge"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_surcharge"));
                                                                        data["u_interest"] = formatNumericAmount(0);
                                                                        data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                                                        data["u_seqno"] = result.childNodes.item(iii).getAttribute("u_seqno");
                                                                        reqtotal += parseInt(result.childNodes.item(iii).getAttribute("u_amount")) +  parseInt(result.childNodes.item(iii).getAttribute("u_surcharge")) ;
                                                                        insertTableRowFromArray("T5",data);
                                                                    }
                                                            }
                                                            
                                                              setInput("u_isonlineapp",result.childNodes.item(0).getAttribute("u_isonlineapp"));
//                                                            setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
//                                                            setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
//                                                            setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
//                                                            setInput("u_bbrgy",result.childNodes.item(0).getAttribute("u_bbrgy"));
//                                                            setInput("u_bbldgno",result.childNodes.item(0).getAttribute("u_bbldgno"));
//                                                            setInput("u_bblock",result.childNodes.item(0).getAttribute("u_bblock"));
//                                                            setInput("u_blotno",result.childNodes.item(0).getAttribute("u_blotno"));
//                                                            setInput("u_bstreet",result.childNodes.item(0).getAttribute("u_bstreet"));
//                                                            setInput("u_bphaseno",result.childNodes.item(0).getAttribute("u_bphaseno"));
//                                                            setInput("u_baddressno",result.childNodes.item(0).getAttribute("u_baddressno"));
//                                                            setInput("u_bvillage",result.childNodes.item(0).getAttribute("u_bvillage"));
//                                                            setInput("u_bbldgname",result.childNodes.item(0).getAttribute("u_bbldgname"));
//                                                            setInput("u_bunitno",result.childNodes.item(0).getAttribute("u_bunitno"));
//                                                            setInput("u_bfloorno",result.childNodes.item(0).getAttribute("u_bfloorno"));
//                                                            setInput("u_btelno",result.childNodes.item(0).getAttribute("u_btelno"));
                                                        } else {
                                                                setInput("u_reqappno","");
                                                                setInputAmount("u_reqappfeestotal",reqtotal);
                                                                page.statusbar.showError("Invalid Serial No for Building Application ");
                                                                hideAjaxProcess();
                                                                return false;
                                                        }
                                                    } else {
                                                            setInput("u_reqappno","");
                                                            setInputAmount("u_reqappfeestotal",reqtotal);
                                                            page.statusbar.showError("Error retrieving Serial No record. Try Again, if problem persists, check the connection.");	
                                                            hideAjaxProcess();
                                                            return false;
                                                    }
                                                    
                                                    var result = page.executeFormattedQuery("select  a.u_businessname,a.u_lastname,a.u_firstname,a.u_middlename,a.u_owneraddress,a.u_contactno,a.u_bbrgy,a.u_bbldgno,a.u_bblock,a.u_blotno,a.u_bvillage,a.u_bstreet,a.u_bphaseno,a.u_baddressno,a.u_bbldgname,a.u_bunitno,a.u_bfloorno,a.u_btelno,b.u_year,b.u_feecode,b.u_feedesc,b.u_amount,0 as u_surcharge,b.u_seqno from u_zoningclrapps a inner join u_zoningclrappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' and docstatus not in('CN','D')");	
                                                    showAjaxProcess();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (var iii=0; iii<result.childNodes.length; iii++) {
                                                                    var feerc = 0;
                                                                    for (xxx = 1; xxx <=rc; xxx++) {
                                                                        if (isTableRowDeleted("T5",xxx)==false) {
                                                                            if (getTableInput("T5","u_feecode",xxx)==result.childNodes.item(iii).getAttribute("u_feecode") ) {
                                                                            setTableInputAmount("T5","u_amount",result.childNodes.item(iii).getAttribute("u_amount"),xxx);
                                                                            if (getTableInput("T5","u_year",xxx) == result.childNodes.item(iii).getAttribute("u_year")){
                                                                                setTableInputAmount("T5","u_surcharge",result.childNodes.item(iii).getAttribute("u_surcharge"),xxx);
                                                                            }
                                                                            reqtotal += parseInt(result.childNodes.item(iii).getAttribute("u_amount")) + parseInt(result.childNodes.item(iii).getAttribute("u_surcharge"));
                                                                            feerc=xxx;
                                                                            }
                                                                        }
                                                                    }
                                                                    if (feerc==0) {
                                                                        var data = new Array();
                                                                        data["u_year"] = result.childNodes.item(iii).getAttribute("u_year");
                                                                        data["u_feecode"] = result.childNodes.item(iii).getAttribute("u_feecode");
                                                                        data["u_feedesc"] = result.childNodes.item(iii).getAttribute("u_feedesc");
                                                                        data["u_regulatory"] = 1;
                                                                        data["u_surcharge"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_surcharge"));
                                                                        data["u_interest"] = formatNumericAmount(0);
                                                                        data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                                                        data["u_seqno"] = result.childNodes.item(iii).getAttribute("u_seqno");
                                                                        reqtotal += parseInt(result.childNodes.item(iii).getAttribute("u_amount")) +  parseInt(result.childNodes.item(iii).getAttribute("u_surcharge")) ;
                                                                        insertTableRowFromArray("T5",data);
                                                                    }
                                                            }
                                                            
                                                        } else {
                                                                setInput("u_reqappno","");
                                                                setInputAmount("u_reqappfeestotal",reqtotal);
                                                                page.statusbar.showError("Invalid Serial No for Zoning Application ");
                                                                hideAjaxProcess();
                                                                return false;
                                                        }
                                                    } else {
                                                            setInput("u_reqappno","");
                                                            setInputAmount("u_reqappfeestotal",reqtotal);
                                                            page.statusbar.showError("Error retrieving Serial No record. Try Again, if problem persists, check the connection.");	
                                                            hideAjaxProcess();
                                                            return false;
                                                    }
                                                    
                                                    setInput("u_reqappfeestotal",formatNumericAmount(reqtotal));
                                                    computeTotalAssessment();
                                                    hideAjaxProcess();

                                            }   
                                                setInput("u_reqappfeestotal",formatNumericAmount(reqtotal));
                                                computeTotalAssessment();
                                        break;
				case "u_businessname":
                                            if (getInput(column)!="") {
                                                        var result = page.executeFormattedQuery("select name, u_apprefno from u_bplmds where name = '"+addslashes(getInput(column))+"'");	
                                                        if (result.getAttribute("result")!= "-1") {
                                                                if (parseInt(result.getAttribute("result"))>0) {
                                                                    if(result.childNodes.item(0).getAttribute("u_apprefno")!=getInput("docno")){
                                                                           if(confirm("Businessname["+result.childNodes.item(0).getAttribute("name")+"] already registered with application number of [" + result.childNodes.item(0).getAttribute("u_apprefno")+"]")){
                                                                           }else{
                                                                                var result = page.executeFormattedQuery("select u_businessname from u_bplapps where docno = '"+getInput("docno")+"'");
                                                                                if (result.getAttribute("result")!= "-1") {
                                                                                    if (parseInt(result.getAttribute("result"))>0) {
                                                                                           setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
                                                                                    }
                                                                                }
                                                                           }
                                                                    }
                                                                      
                                                                }
                                                        } 
                                                }
                                        break;
                                case "u_tradename":
                                            setInput(column,getInput(column).toUpperCase());
                                            setInput("u_businessname",getInput("u_tradename") + "-" + getInput("u_appno"));
                                        break;
				case "u_appdate":
					//setDocNo(true,null,null,"u_appdate");
					break;
				case "u_mempcount":
				case "u_fempcount":
					setInput("u_empcount",getInputNumeric("u_mempcount")+getInputNumeric("u_fempcount"));
                                        setEmployeeTaxes();
					break;
				case "u_empcount":
                                case "u_emplgucount":
					setEmployeeTaxes();

					break;
			}
			break;
	}			
	return true;
}

function onElementGetValidateParamsGPSBPLS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSBPLS(element,column,table,row) {
	return true;
}

function onElementChangeGPSBPLS(element,column,table,row) {
	switch (table) {
            case "T1":
                switch (column) {
                    case "u_businessline":
                           if (getTableInput(table,column)!="") {
                                var result = page.executeFormattedQuery("SELECT A.NAME AS LINENAME,A.CODE AS LINECODE ,D.NAME AS NATURE,D.CODE AS naturecode FROM U_BPLLINES A INNER JOIN U_BPLNATURE B ON A.U_BUSINESSNATURE = B.NAME INNER JOIN U_BPLTAXCLASSNATURE C ON C.U_BUSINESSNATURE = B.NAME INNER JOIN U_BPLTAXCLASSES D ON C.CODE = D.CODE  where A.CODE = '"+getTableInput(table,column)+"'");	
                                if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {    
                                                setTableInput(table,"u_taxclass",result.childNodes.item(0).getAttribute("naturecode"));
                                        } else {
                                                setTableInput(table,"u_taxclass","");
                                               
                                        }
                                } else {
                                        setTableInput(table,"u_taxclass","");
                                        page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                }
                        } else {
                                setTableInput(table,"u_feecode","");
                               
                        }						
				case "u_taxclass":
                    var annualtaxcoderc = 0, electricalcertrc = 0, capital = 0, essential = 0, nonessential = 0, baseperc = 100, taxamount = 0, taxrate = 0, excessamount = 0, fromamount = 0;
                    var capitalamount = 0, annualtaxamount = 0;

                    if (getInput("u_apptype") == "RENEW") {
                        var grosssales1 = 0, baseperc1 = 100, taxamount1 = 0, taxrate1 = 0, fromamount1 = 0, excessamount1 = 0, compbased = '';
                        grosssales1 = getTableInputNumeric("T1", "u_essential") + getTableInputNumeric("T1", "u_nonessential");

                        var result = page.executeFormattedQuery("select u_compbased,u_fixedamount from u_bpllines where code = '" + getTableInput("T1", "u_businessline") + "'");
                        if (result.getAttribute("result") != "-1") {
                            if (parseInt(result.getAttribute("result")) > 0) {
                                compbased = result.childNodes.item(0).getAttribute("u_compbased");
                            }
                        }

                        if (compbased == 1) {
                            taxamount1 = parseFloat(result.childNodes.item(0).getAttribute("u_fixedamount"));
                        } else {
                            var result1 = page.executeFormattedQuery("select b.u_excessamount,a.u_baseperc, b.u_amount, b.u_rate,b.u_from from u_bpltaxclasses a, u_bpltaxclasstaxes b where b.code=a.code and " + grosssales1 + " >= b.u_from and (" + grosssales1 + " <=b.u_to or b.u_to=0) and a.code = '" + getTableInput("T1", "u_taxclass") + "'");
                            if (result1.getAttribute("result1") != "-1") {
                                if (parseInt(result1.getAttribute("result")) > 0) {
                                    baseperc1 = parseFloat(result1.childNodes.item(0).getAttribute("u_baseperc"));
                                    taxamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_amount"));
                                    taxrate1 = parseFloat(result1.childNodes.item(0).getAttribute("u_rate"));
                                    excessamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_excessamount"));
                                    fromamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_from"));
                                }
                            } else {
                                page.statusbar.showError("Error retrieving tax classificaton. Try Again, if problem persists, check the connection.");
                                return false;
                            }
                        }
                    }

                    if (getInput("u_apptype") == "NEW") {
                        annualtaxamount = getTableInputNumeric("T1", "u_capital") * .01 / 20;
                        if (annualtaxamount < 220)
                            annualtaxamount = 220;
                        setTableInputAmount("T1", "u_btaxlinetotal", annualtaxamount);
                    } else if (getInput("u_apptype") == "RENEW") {

                        if (taxamount1 > 0 && excessamount1 == 0 && taxrate1 == 0) {
                            annualtaxamount = taxamount1 * (baseperc1 / 100);
                        } else if (taxamount1 == 0 && excessamount1 == 0 && taxrate1 > 0) {
                            annualtaxamount = taxrate1 * grosssales1 * (baseperc1 / 100);
                        } else if (taxamount1 > 0 && excessamount1 > 0 && taxrate1 > 0) {
                            annualtaxamount = ((((grosssales1 - fromamount1) / excessamount1) * taxrate1) + taxamount1) * (baseperc1 / 100);
                        }
                        if (annualtaxamount < 220)
                            annualtaxamount = 220;
                        setTableInputAmount("T1", "u_btaxlinetotal", annualtaxamount);
                    }
                    break;
                }
            break;
		default:
			switch(column) {
				case "u_assyearto":
                                    if (getInput(column)!="") {
                                        if (getInput(column) < getInput("lastpayyear")) {
                                            page.statusbar.showError("Up-to-Year cannot be less than to last paid year");
                                            return false;
                                        } else if (getInput(column) > getInput("u_year")) {
                                            page.statusbar.showError("Up-to-Year cannot be greater than current year");
                                            return false;
                                        } 
                                        var     rc = getTableRowCount("T5");
                                        var     rc2 = getTableRowCount("T1");
                                            for (xxx2 = 1; xxx2 <=rc; xxx2++) {
                                                    if (isTableRowDeleted("T5",xxx2)==false) {
                                                            if (getTableInput("T5","u_year",xxx2) > getInput(column) ) {
                                                                deleteTableRow("T5",xxx2);
                                                            }
                                                    }
                                            }
                                            for (xxx3 = 1; xxx3 <=rc2; xxx3++) {
                                                    if (isTableRowDeleted("T1",xxx3)==false) {
                                                            if (getTableInput("T1","u_year",xxx3) > getInput(column) ) {
                                                                deleteTableRow("T1",xxx3);
                                                            }
                                                    }
                                            }
                                            
                                            
                                            for (xxx = getInputNumeric("lastpayyear") + 1; xxx <=getInput(column); xxx++) {
                                                var result = page.executeFormattedQuery("select code, name, u_amount,u_regulatory,u_seqno from u_bplfees  order by u_seqno asc");
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx1 = 0; xxx1 < result.childNodes.length; xxx1++) {
                                                                var feectr = 0;
                                                                for (xxx2 = 1; xxx2 <=rc; xxx2++) {
                                                                    if (isTableRowDeleted("T5",xxx2)==false) {
                                                                        if(getTableInput("T5","u_year",xxx2) == xxx && getTableInput("T5","u_feecode",xxx2) == result.childNodes.item(xxx1).getAttribute("code")) {
                                                                            feectr = xxx2;
                                                                        } 
                                                                    }
                                                                }
                                                                if (feectr == 0) {
                                                                    var data = new Array();
                                                                    data["u_year"] = xxx;
                                                                    data["u_feecode"] = result.childNodes.item(xxx1).getAttribute("code");
                                                                    data["u_feedesc"] = result.childNodes.item(xxx1).getAttribute("name");
                                                                    data["u_common"] = 1;
                                                                    data["u_amount"] = formatNumericAmount(result.childNodes.item(xxx1).getAttribute("u_amount"));
                                                                    data["u_surcharge"] = formatNumericAmount(0);
                                                                    data["u_interest"] = formatNumericAmount(0);
                                                                    data["u_regulatory"] = result.childNodes.item(xxx1).getAttribute("u_amount");
                                                                    insertTableRowFromArray("T5",data);
                                                                }
                                                            }
                                                        }		
                                                    }
                                            }
                                            
                                            
                                        computeAnnualTax(); 
                                        computeCedulaTotalGPSBPLS();
                                    }
                                break;
				case "docseries":
					//setDocNo(true,null,null,'u_appdate');
					break;
				case "u_businesschar":
					if (getPrivate("bplkindcharlink")=="1") {
						u_ajaxloadu_bplkinds("df_u_businesskind",element.value,'',":");
					}
					setInputAmount("u_businesskindtax",0);
					setInputAmount("u_businesskindenvironmenttax",0);
					setInputAmount("u_businesskindmayorstax",0);
                                        
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select a.u_amount as u_businesschartax,a.u_mayorsamount as u_businesscharmayorstax,a.u_environmentamount as u_businesscharenvironmenttax, ifnull(b.u_amount,0) as u_businesscategorytax from u_bplcharacters a left join u_bplcharactercategories b on b.code=a.code and b.u_category='"+getInput("u_businesscategory")+"' where a.code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_businesschartax",result.childNodes.item(0).getAttribute("u_businesschartax"));
								setInputAmount("u_businesscharmayorstax",result.childNodes.item(0).getAttribute("u_businesscharmayorstax"));
								setInputAmount("u_businesscharenvironmenttax",result.childNodes.item(0).getAttribute("u_businesscharenvironmenttax"));
								setInputAmount("u_businesscategorytax",result.childNodes.item(0).getAttribute("u_businesscategorytax"));
								setBusinessTaxes();
							} else {
								setInputAmount("u_businesschartax",0);
								setInputAmount("u_businesscharmayorstax",0);
								setInputAmount("u_businesscharenvironmenttax",0);
								setInputAmount("u_businesscategorytax",0);
								page.statusbar.showError("Invalid Business Character");	
								setBusinessTaxes();
								return false;
							}
						} else {
							setInputAmount("u_businesschartax",0);
                                                        setInputAmount("u_businesscharmayorstax",0);
                                                        setInputAmount("u_businesscharenvironmenttax",0);
                                                        setInputAmount("u_businesscategorytax",0);
							setBusinessTaxes();
							page.statusbar.showError("Error retrieving Business Characters record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						
					} else {
						setInputAmount("u_businesschartax",0);
                                                setInputAmount("u_businesscharmayorstax",0);
                                                setInputAmount("u_businesscharenvironmenttax",0);
                                                setInputAmount("u_businesscategorytax",0);
						setBusinessTaxes();
					}
                                        setInput("u_businesskind",getInput("u_businesskind"),true);
					break;
				case "u_businesskind":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_amount,u_mayorsamount,u_environmentamount from u_bplcharacterkinds where code='"+getInput("u_businesschar")+"' and u_kind='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_businesskindtax",result.childNodes.item(0).getAttribute("u_amount"));
								setInputAmount("u_businesskindenvironmenttax",result.childNodes.item(0).getAttribute("u_environmentamount"));
								setInputAmount("u_businesskindmayorstax",result.childNodes.item(0).getAttribute("u_mayorsamount"));
								setBusinessTaxes();
							} else {
								setInputAmount("u_businesskindtax",0);
								setInputAmount("u_businesskindenvironmenttax",0);
								setInputAmount("u_businesskindmayorstax",0);
								page.statusbar.showError("Invalid Business Kind");	
								setBusinessTaxes();
								return false;
							}
						} else {
							setInputAmount("u_businesskindtax",0);
							setInputAmount("u_businesskindenvironmenttax",0);
							setInputAmount("u_businesskindmayorstax",0);
							setBusinessTaxes();
							page.statusbar.showError("Error retrieving Business Kind record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						
					} else {
						setInputAmount("u_businesskindtax",0);
						setInputAmount("u_businesskindenvironmenttax",0);
						setInputAmount("u_businesskindmayorstax",0);
						setBusinessTaxes();
					}						
					break;
//				case "u_businesscategory":
//					if (getInput(column)!="") {
//						var result = page.executeFormattedQuery("select ifnull(b.u_amount,0) as u_amount, a.u_sanitarypermitfee, a.u_fireinspectionfee, a.u_sfhefee from u_bplcategories a left join u_bplcharactercategories b on b.code='"+getInput("u_businesschar")+"' and b.u_category=a.code where a.code='"+getInput(column)+"'");	
//						if (result.getAttribute("result")!= "-1") {
//							if (parseInt(result.getAttribute("result"))>0) {
//								setInputAmount("u_businesscategorytax",result.childNodes.item(0).getAttribute("u_amount"));
//								setInputAmount("u_sanitarypermitfee",result.childNodes.item(0).getAttribute("u_sanitarypermitfee"));
//								setInputAmount("u_fireinspectionfee",result.childNodes.item(0).getAttribute("u_fireinspectionfee"));
//								setInputAmount("u_sfhefee",result.childNodes.item(0).getAttribute("u_sfhefee"));
//								setBusinessTaxes();
//								setCategoryTaxes();
//								setEmployeeTaxes();
//							}
//						} else {
//							setInputAmount("u_businesscategorytax",0);
//							setInputAmount("u_sanitarypermitfee",0);
//							setInputAmount("u_fireinspectionfee",0);
//							setInputAmount("u_sfhefee",0);
//							setBusinessTaxes();
//							setCategoryTaxes();
//							setEmployeeTaxes();
//							page.statusbar.showError("Error retrieving Business Category record. Try Again, if problem persists, check the connection.");	
//							return false;
//						}
//						
//					} else {
//						setInputAmount("u_businesscategorytax",0);
//						setInputAmount("u_sanitarypermitfee",0);
//						setInputAmount("u_fireinspectionfee",0);
//						setInputAmount("u_sfhefee",0);
//						setBusinessTaxes();
//						setCategoryTaxes();
//						setEmployeeTaxes();
//					}						
//					break;
				case "u_taxclass":
					setAnnualTax();
					break;
				case "u_bbrgy":
                                        if(getPrivate("userid")!="bplcommon"){
					if (getInput(column)!="") {
//						var result = page.executeFormattedQuery("select u_garbagefeec, u_garbagefeer, u_electricalcert from u_barangays where code='"+getInput(column)+"'");	
//						if (result.getAttribute("result")!= "-1") {
//							if (parseInt(result.getAttribute("result"))>0) {
//								setInputAmount("u_garbagefeec",result.childNodes.item(0).getAttribute("u_garbagefeec"));
//								setInputAmount("u_garbagefeer",result.childNodes.item(0).getAttribute("u_garbagefeer"));
//								setBarangayFees();
//							} else {
//								setInputAmount("u_garbagefeec",0);
//								setInputAmount("u_garbagefeer",0);
//								page.statusbar.showError("Invalid Barangay Fees");	
//								setBarangayFees();
//								return false;
//							}
//						} else {
//							setInputAmount("u_garbagefeec",0);
//							setInputAmount("u_garbagefeer",0);
//							setBarangayFees();
//							page.statusbar.showError("Error retrieving Business Barangay record. Try Again, if problem persists, check the connection.");	
//							return false;
//						}
                                                u_ajaxloadu_bplsubdivisions("df_u_bvillage",element.value,'',":");
						
					} else {
						setInputAmount("u_garbagefeec",0);
						setInputAmount("u_garbagefeer",0);
						setBarangayFees();
					}
                                    }
					break;
                                    case "u_garbagetax":
                                            setGarbagesFees();
                                            break;
                                    case "u_weightstax":
                                            setWeightsandMeasureFees();
                                            break;
                                    
			}
	}
	return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
	switch (table) {
		case "T2":
		case "T3":
			switch (column) {
				case "u_check":
					if (getTableInput(table,"u_feecode",row)!="") setRequirementsTaxes(table,row);
					break;
                                   
                                        
			}
			break;
		default:
			switch(column) {
                            case "u_sameasowneraddr":
                                if(getInput("u_sameasowneraddr")==1){
                                    setInput("u_bldgno",getInput("u_bbldgno"));
                                    setInput("u_bldgname",getInput("u_bbldgname"));
                                    setInput("u_unitno",getInput("u_bunitno"));
                                    setInput("u_street",getInput("u_bstreet"));
                                    setInput("u_village",getInput("u_bvillage"));
                                    setInput("u_brgy",getInput("u_bbrgy"));
                                    setInput("u_city",getInput("u_bcity"));
                                    setInput("u_province",getInput("u_bprovince"));
                                }else{
                                    setInput("u_bldgno","");
                                    setInput("u_bldgname","");
                                    setInput("u_unitno","");
                                    setInput("u_street","");
                                    setInput("u_village","");
                                    setInput("u_brgy","");
                                    setInput("u_city","");
                                    setInput("u_province","");
                                }
                            break;
				case "u_orgtype": 
                                    computeCedulaTotalGPSBPLS(); 
                                break;
				case "u_apptype":
					if (getInput("u_apptype")=="NEW") {
						disableTableInput("T1","u_essential");
						disableTableInput("T1","u_nonessential");
						enableTableInput("T1","u_capital");
						disableInput("u_appno");
					} else {
						enableInput("u_appno");
                                                focusInput("u_appno");
						disableTableInput("T1","u_capital");
						enableTableInput("T1","u_essential");
						enableTableInput("T1","u_nonessential");
					}
					setAppTypeTaxes();
					break;
                                
				case "u_bunittype":
					setBarangayFees();
					break;
				case "u_paymode":
					setInput("u_envpaymode",getInput("u_paymode"));
					break;
			}
	}
	return true;
}

function onElementCFLGPSBPLS(element) {
	return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name,concat(u_lastname,', ',u_firstname,' ',u_middlename) as Ownername  from u_bplmds")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Account No`Business Name`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`40")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_reqappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,'Zoning' as u_office,u_businessname,u_year,docstatus  from u_zoningclrapps where docstatus != 'D' and u_bplappno = '' and u_acctno like '%"+getInput("u_appno")+"%' union all select docno,'Building' as u_office,u_businessname,u_year,docstatus  from u_bldgapps where docstatus != 'D' and u_bplappno = '' and u_acctno like '%"+getInput("u_appno")+"%' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No`Department`Business Name`Year`Status")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`8`8")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
		case "df_u_bstreet":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME,U_BRGY FROM U_STREETS")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Street`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_bvillage":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME,u_barangay FROM u_subdivisions")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Subdivisions`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_bbldgname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME FROM u_bplbldgnames where u_street LIKE '%"+getInput("u_bstreet")+"%' and u_brgy = '"+getInput("u_bbrgy")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Building")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("")); 			
			break;
		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT U_PIN,U_BARANGAY,U_OWNERNAME,U_TDNO FROM U_RPFAAS1 WHERE U_CANCELLED <> 1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Barangay`Owner Name`Td No")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`30`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
		case "df_u_feecodeT5":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name  from u_lgufees a,u_lgusetup b where a.code != b.u_annualtax")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT5":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code  from u_lgufees a,u_lgusetup b where a.code != b.u_annualtax")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feecodeT8":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT8":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table,row) {
	switch (table) {
		case "T1": 
                        if (getTableInput(table,"u_year") == "-") {
                            page.statusbar.showError("Year is required.");
                            focusTableInput(table,"u_year");
                            return false;
                        }
			if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_businessline")) return false;
			if (isTableInputNegative(table,"u_btaxlinetotal")) return false;
			if (getInput("u_apptype")=="NEW") {
				if (isTableInputNegative(table,"u_capital")) return false;
			} else if (getInput("u_apptype")=="RENEW") {
				if (getTableInputNumeric(table,"u_essential")+getTableInputNumeric(table,"u_nonessential")==0) {
					if (isTableInputNegative(table,"u_essential")) return false;
				}
			} else {
				if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
			}
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			break;
		case "T5":
                        if (getTableInput(table,"u_year") == "-") {
                            page.statusbar.showError("Year is required.");
                            focusTableInput(table,"u_year");
                            return false;
                        }
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T8":
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;

			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSBPLS(table,row) {
	switch (table) {
		case "T1": setBusinessLineRequirements(); setBusinessLineFees(); computeAnnualTax(); computeCedulaTotalGPSBPLS(); u_ComputePenaltySurchargeGPSBPLS(); break;
		case "T5": computeTotalAssessment();break;
		case "T8": computeTotalFireAssessment();break;
	}
}

function onTableBeforeUpdateRowGPSBPLS(table,row) {
	switch (table) {
		case "T1":
                        if (getTableInput(table,"u_year") == "-") {
                            page.statusbar.showError("Year is required.");
                            focusTableInput(table,"u_year");
                            return false;
                        }
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_businessline")) return false;
			if (isTableInputNegative(table,"u_btaxlinetotal")) return false;
			if (getInput("u_apptype")=="NEW") {
				if (isTableInputNegative(table,"u_capital")) return false;
			} else if (getInput("u_apptype")=="RENEW") {
				if (getTableInputNumeric(table,"u_essential")+getTableInputNumeric(table,"u_nonessential")==0) {
					if (isTableInputNegative(table,"u_essential")) return false;
				}
			} else {
				if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
			}
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);   
			break;
		case "T5": 
                        if (getTableInput(table,"u_year") == "-") {
                            focusTableInput(table,"u_year");
                            page.statusbar.showError("Year is required.");
                            return false;
                        }
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
		case "T8": 
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
	switch (table) {
		case "T1": setBusinessLineRequirements(); setBusinessLineFees(); computeAnnualTax(); computeCedulaTotalGPSBPLS(); u_ComputePenaltySurchargeGPSBPLS(); break;
		case "T5": computeTotalAssessment(); break;
		case "T8": computeTotalFireAssessment(); break;
	}
}

function onTableBeforeDeleteRowGPSBPLS(table,row) {
	return true;
}

function onTableDeleteRowGPSBPLS(table,row) {
	switch (table) {
		case "T1": 
                        var     rc = getTableRowCount("T5");
                        for (xxx = 1; xxx <=rc; xxx++) {
                                if (isTableRowDeleted("T5",xxx)==false) {
                                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("annualtaxcode") && getTableInput("T5","u_businessline",xxx) == getTableInput(table,"u_businessline",row) && getTableInput("T5","u_year",xxx) == getTableInput(table,"u_year",row) ) {
                                            deleteTableRow("T5",xxx);
                                        }
                                }
                        }
                    setBusinessLineRequirements(); 
                    setBusinessLineFees(); 
                    computeAnnualTax(); 
                    computeCedulaTotalGPSBPLS();
                    u_ComputePenaltySurchargeGPSBPLS();
                        
                    break;
		case "T5": computeTotalAssessment();  break;
		case "T8": computeTotalFireAssessment();  break;
	}
}

function onTableBeforeSelectRowGPSBPLS(table,row) {
	return true;
}

function onTableSelectRowGPSBPLS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
                        if (getInput("u_apptype") == "NEW") {
                            focusTableInput(table,"u_capital");
                            params["focus"]=false;
                        } else {
                            focusTableInput(table,"u_nonessential");
                            params["focus"]=false;
                        }
                break;
		case "T5":
                        focusTableInput(table,"u_amount");
                        params["focus"]=false;
                break;
		case "T2":
		case "T3":
		case "T4":
		case "T7":
			if (elementFocused.substring(0,11)=="df_u_amount") {
				focusTableInput(table,"u_amount",row);
			} else if (elementFocused.substring(0,12)=="df_u_issdate") {
				focusTableInput(table,"u_issdate",row);
			} else if (elementFocused.substring(0,13)=="df_u_payrefno") {
				focusTableInput(table,"u_payrefno",row);
			} else if (elementFocused.substring(0,12)=="df_u_remarks") {
				focusTableInput(table,"u_remarks",row);
			}
			params["focus"]=false;
			break;
	}
	return params;
}

function u_preAssBillGPSBPLS() {
	var amount=0,rc = 0;
	rc = getTableRowCount("T3");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T3",xxx)==false) {
			amount += getTableInputNumeric("T3","u_amount",xxx);
		}
	}
	rc = getTableRowCount("T4");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T4",xxx)==false) {
			amount += getTableInputNumeric("T4","u_amount",xxx);
		}
	}
	if (amount==0) {
		page.statusbar.showError("At least one requirement must have amount.");
		return false;
	}
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
	

	setInput("u_preassbill","1");
	formSubmit();
	
}
function u_forAssessmentGPSBPLS() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;

	/*if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		selectTab("tab1",3);
		return false;
	}*/
	setInput("docstatus","Assessing");
	formSubmit('sc');
}

function u_forApprovalGPSBPLS() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T5")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		selectTab("tab1",1);
		return false;
	}
	setInput("docstatus","Assessed");
	formSubmit('sc');
}

function u_forApproveGPSBPLS() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T5")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		selectTab("tab1",1);
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function u_approveGPSBPLS() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_appdate",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_businessname",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_paymode",null,null,"tab1",1)) return false;
        if(getInput("u_apptype")=="NEW") if (isInputNegative("u_capital",null,null,"tab1",1)) return false;
        if(getInput("u_apptype")=="RENEW") if (isInputNegative("u_nonessential",null,null,"tab1",1)) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T5")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		selectTab("tab1",1);
		return false;
	}
        if (getInput("u_reqappno")!="") {
            var result = page.executeFormattedQuery("select u_acctno from u_bldgapps where docno ='"+getInput("u_reqappno")+"' ");
                if (parseInt(result.getAttribute("result"))>0) {
                    for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        if (result.childNodes.item(0).getAttribute("u_acctno") != "" && result.childNodes.item(0).getAttribute("u_acctno") != getInput("u_appno")) {
                            selectTab("tab1",1);
                             focusInput("u_reqappno");
                            page.statusbar.showError("Account number doesnt match with Building application.");
                            return false;
                        } 
                    }
                } else {
                    page.statusbar.showError("Invalid Serial No.");
                    return false;
                }
            var result = page.executeFormattedQuery("select u_acctno from u_zoningclrapps where docno ='"+getInput("u_reqappno")+"' ");
                if (parseInt(result.getAttribute("result"))>0) {
                    for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        if (result.childNodes.item(0).getAttribute("u_acctno") != "" &&  result.childNodes.item(0).getAttribute("u_acctno") != getInput("u_appno")) {
                            selectTab("tab1",1);
                             focusInput("u_reqappno");
                            page.statusbar.showError("Account number doesnt match with Zoning application.");
                            return false;
                        } 
                    }
                } else {
                    page.statusbar.showError("Invalid Serial No.");
                    return false;
                }
        }
      
	setInput("docstatus","Approved");
	formSubmit();
}

function u_disapproveGPSBPLS() {
	setInput("docstatus","Disapproved");
	formSubmit('sc');
}
function u_retireGPSBPLS() {
	if(confirm("Retire Business ["+getInput("u_businessname")+"]. Continue?")){
           // setInput("U_APPTYPE","RETIRED");
            setInput("u_retired",1);
            formSubmit();
        }
}
function showPopupUnRetireBusiness(){
    showPopupFrame("popupFrameUnretire",true);
    focusTableInput("T61","userid");
}
function u_unretireGPSBPLS() {
    if(confirm("Un-Retire Business ["+getInput("u_businessname")+"]. Continue?")){
       
        
    var result = page.executeFormattedQuery("SELECT username, u_bplretire from users where userid = '"+getTableInput("T61","userid")+"' and hpwd = '"+MD5(getTableInput("T61","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                if (result.childNodes.item(0).getAttribute("u_bplretire") == 0) {
                    page.statusbar.showError("You are not allowed for this action");
                    setInput("u_retired",1);
                    return false;
                } else {
                    setInput("u_retired",0);
                    if (isPopupFrameOpen("popupFrameUnretire")) {
                        hidePopupFrame('popupFrameUnretire');
                    }
                    formSubmit();
                    }

            }
        } else {
            page.statusbar.showError("Invalid user or password.");
            setInput("u_retired",1);
            return false;
        }
    }
}

function u_OnHoldGPSBPLS() {
	if(confirm("On-Hold Business ["+getInput("u_businessname")+"]. Continue?")){
           // setInput("U_APPTYPE","RETIRED");
            setInput("u_onhold",1);
            formSubmit();
        }
}
function showPopupUnHoldBusiness(){
    showPopupFrame("popupFrameOnhold",true);
    focusTableInput("T51","userid");
}
function showPopupDeleteBusiness(){
    showPopupFrame("popupFrameDeleteRecord",true);
    focusTableInput("T81","userid");
}
function showPopupInsertPaymentHistory(){
    showPopupFrame("popupFrameInsertPaymentHistory",true);
    focusTableInput("T91","userid");
}

function u_InsertPaymentHistoryGPSBPLS(){

        if (isTableInput("T91","userid")) {
            if (getTableInput("T91","userid")=="") {
                    showPopupFrame("popupFrameInsertPaymentHistory",true);
                    focusTableInput("T91","userid");
                    return false;
            }
        }
        var result = page.executeFormattedQuery("SELECT username,u_bplinserthistory from users where userid = '"+getTableInput("T91","userid")+"' and hpwd = '"+MD5(getTableInput("T91","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        if (result.childNodes.item(0).getAttribute("u_bplinserthistory") == 0) {
                            page.statusbar.showError("You are not allowed for this function");
                            return false;
                        } else {
                            if (isPopupFrameOpen("popupFrameInsertPaymentHistory")) {
                                hidePopupFrame('popupFrameInsertPaymentHistory');
                            }
                           OpenPopup(1280,600,"./udo.php?&objectcode=u_bpladdledger&sf_keys="+getInput("code")+"&formAction=e","UpdPays");	
                        }
                }
        } else {
            page.statusbar.showError("Invalid user or password.");
            return false;
        }
}

function unHoldBusinessGPSBPLS() {
    if(confirm("Un-Hold Business ["+getInput("u_businessname")+"]. Continue?")){
        if (isTableInput("T51","userid")) {
            if (getTableInput("T51","userid")=="") {
                showPopupFrame("popupFrameOnhold",true);
                focusTableInput("T51","userid");
                return false;
            }
        }
        
    var result = page.executeFormattedQuery("SELECT username, u_bplhold from users where userid = '"+getTableInput("T51","userid")+"' and hpwd = '"+MD5(getTableInput("T51","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                if (result.childNodes.item(0).getAttribute("u_bplhold") == 0) {
                    page.statusbar.showError("You are not allowed for this action");
                    setInput("u_onhold",1);
                    return false;
                } else {
                    setInput("u_onhold",0);
                    if (isPopupFrameOpen("popupFrameOnhold")) {
                        hidePopupFrame('popupFrameOnhold');
                    }
                    formSubmit();
                    }

            }
        } else {
            page.statusbar.showError("Invalid user or password.");
            setInput("u_onhold",1);
            return false;
        }
    }
}

function u_PopUpRetireGPSBPLS() {
        showPopupFrame('popupFrameRetire',true);
}

function u_reassessGPSBPLS() {
	if (isInputEmpty("u_approverremarks",null,null,"tab1",3)) return false;
	setInput("docstatus","Encoding");
	formSubmit('sc');
}
function u_prebillreassessGPSBPLS() {
	setInput("docstatus","Encoding");
        setInput("u_preassbill","0");
        formSubmit('sc');
}

function uploadPhoto() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshPhoto()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,JPG,JPEG,GIF,PNG";
	iframeFileUpload.document.getElementById("basename").value = "photo";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/BPLS/" + getInput("docno") + "/";
	showPopupFrame('popupFrameFileUpload');
}

function refreshPhoto() {
	var src = document.images['PhotoImg'].src;
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function setBusinessLineRequirements() {
	clearTable("T4",true);
	clearTable("T7",true);
	//alert("select a.u_reqcode, a.u_reqdtls, a.u_office from u_bpllinereqs a where a.code in ("+getTableInputGroupConCat("T1","u_businessline")+") order by a.u_seqno");
	var result = page.executeFormattedQuery("select a.u_reqcode, a.u_reqdtls, a.u_office from u_bpllinereqs a where a.code in ("+getTableInputGroupConCat("T1","u_businessline")+") order by a.u_seqno");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				if (result.childNodes.item(xxx).getAttribute("u_reqdtls")=="1") {
					var data = new Array();
					data["u_reqcode"] = result.childNodes.item(xxx).getAttribute("u_reqcode");
					data["u_reqdesc"] = result.childNodes.item(xxx).getAttribute("u_reqcode");
					data["u_office"] = result.childNodes.item(xxx).getAttribute("u_office");
					insertTableRowFromArray("T4",data);
				} else {
					var data = new Array();
					data["u_reqcode"] = result.childNodes.item(xxx).getAttribute("u_reqcode");
					data["u_reqdesc"] = result.childNodes.item(xxx).getAttribute("u_reqcode");
					insertTableRowFromArray("T7",data);
				}
			}
		}		
	} else {
		page.statusbar.showError("Error retrieving additional requirements. Try Again, if problem persists, check the connection.");	
		return false;
	}
}

function computeAnnualTax() {
	var capital = 0, essential = 0, nonessential = 0;
	var capitalbrgy = 0, essentialbrgy = 0, nonessentialbrgy = 0,brgyclearanceamount = 0,totalbrgygross = 0;
	var	rc = getTableRowCount("T1");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			capital += getTableInputNumeric("T1","u_capital",xxx);
			essential += getTableInputNumeric("T1","u_essential",xxx);
			nonessential += getTableInputNumeric("T1","u_nonessential",xxx);
                        if (getTableInput("T1","u_year",xxx) == getInput("u_year")) {
                            capitalbrgy += getTableInputNumeric("T1","u_capital",xxx);
                            essentialbrgy += getTableInputNumeric("T1","u_essential",xxx);
                            nonessentialbrgy += getTableInputNumeric("T1","u_nonessential",xxx);
                        }
		}
	}
	setInputAmount("u_capital", capital);
	setInputAmount("u_essential", essential);
	setInputAmount("u_nonessential", nonessential);
        
        if((getInputNumeric("u_capital") + getInputNumeric("u_essential") + getInputNumeric("u_nonessential")) < 3000000){
            setInput("u_businesscategory","Micro");
        }else if((getInputNumeric("u_capital") + getInputNumeric("u_essential") + getInputNumeric("u_nonessential")) >= 3000000 && (getInputNumeric("u_capital") + getInputNumeric("u_essential") + getInputNumeric("u_nonessential")) <= 14999999){
            setInput("u_businesscategory","Small");
        }else if((getInputNumeric("u_capital") + getInputNumeric("u_essential") + getInputNumeric("u_nonessential")) >= 15000000 && (getInputNumeric("u_capital") + getInputNumeric("u_essential") + getInputNumeric("u_nonessential")) <= 100000000){
            setInput("u_businesscategory","Medium");
        }else if((getInputNumeric("u_capital") + getInputNumeric("u_essential") + getInputNumeric("u_nonessential")) > 100000000 ){
            setInput("u_businesscategory","Large");
        }else{
            setInput("u_businesscategory","");
        }
        totalbrgygross = capitalbrgy + essentialbrgy + nonessentialbrgy;
        if (totalbrgygross > 0) {
            if( totalbrgygross <= 100000){
                brgyclearanceamount = 200;
            }else if( totalbrgygross > 100000 ){
                brgyclearanceamount = 500;
            }
            
            var rc = getTableRowCount("T5"),bgrytaxcoderc=0;
            for (xxx = 1; xxx <=rc; xxx++) {
                if (isTableRowDeleted("T5",xxx)==false) {
                    if (getTableInput("T5","u_feecode",xxx)=="0488"  && getTableInput("T5","u_year",xxx) == getInput("u_year")) {
                            setTableInputAmount("T5","u_amount",brgyclearanceamount,xxx);
                            bgrytaxcoderc=xxx;
                    }
                }
            }
            if (bgrytaxcoderc==0) {
                    var data = new Array();
                    data["u_year"] = getInput("u_year");
                    data["u_feecode"] = "0488";
                    data["u_feedesc"] = "Barangay Clearance for Business";
                    data["u_common"] = 1;
                    data["u_amount"] = formatNumericAmount(brgyclearanceamount);
                    data["u_surcharge"] = formatNumericAmount(0);
                    data["u_interest"] = formatNumericAmount(0);
                    data["u_regulatory"] = 0;
                    insertTableRowFromArray("T5",data);
            }
        }
       
       
        
	setAnnualTax();
}

function setAnnualTax() {
	var annualtaxcoderc=0, electricalcertrc = 0, capital = 0, essential = 0, nonessential = 0, grosssales = 0, baseperc=100, taxamount=0, taxrate=0, excessamount = 0, fromamount = 0;
        var capitalamount = 0,annualtaxamount = 0;
	grosssales = getInputNumeric("u_essential")+getInputNumeric("u_nonessential");
        
//        var rc1 = getTableRowCount("T1");
//        for (xxx = 1; xxx <=rc1; xxx++) {
//            if (isTableRowDeleted("T1",xxx)==false) {
//                if (getInput("u_apptype")=="RENEW") {
//                        var grosssales1 = 0, baseperc1=100, taxamount1=0, taxrate1=0, fromamount1 = 0, excessamount1 = 0;
//                        grosssales1 = getTableInputNumeric("T1","u_essential",xxx)+getTableInputNumeric("T1","u_nonessential",xxx);
//                        var result = page.executeFormattedQuery("select b.u_excessamount,a.u_baseperc, b.u_amount, b.u_rate,b.u_from from u_bpltaxclasses a, u_bpltaxclasstaxes b where b.code=a.code and "+grosssales1+" >= b.u_from and ("+grosssales1+" <=b.u_to or b.u_to=0) and a.code = '"+getTableInput("T1","u_taxclass",xxx)+"'");
//                        if (result.getAttribute("result")!= "-1") {
//                                if (parseInt(result.getAttribute("result"))>0) {
//                                        baseperc1 = parseFloat(result.childNodes.item(0).getAttribute("u_baseperc"));
//                                        taxamount1 = parseFloat(result.childNodes.item(0).getAttribute("u_amount"));
//                                        taxrate1 = parseFloat(result.childNodes.item(0).getAttribute("u_rate"));
//                                        excessamount1 = parseFloat(result.childNodes.item(0).getAttribute("u_excessamount"));
//                                        fromamount1 = parseFloat(result.childNodes.item(0).getAttribute("u_from"));
//                                }		
//                        } else {
//                                page.statusbar.showError("Error retrieving tax classificaton. Try Again, if problem persists, check the connection.");	
//                                return false;
//                        }
//                }
//                
//                if (getInput("u_apptype")=="NEW") {
//                        annualtaxamount = getTableInputNumeric("T1","u_capital",xxx)*.01/20;
//                        if(annualtaxamount<220) annualtaxamount = 220;
//                        setTableInputAmount("T1","u_btaxlinetotal",annualtaxamount,xxx);
//                } else if (getInput("u_apptype")=="RENEW") {
//                    
//                    if (taxamount1>0 && excessamount1==0 && taxrate1==0) {
//                        annualtaxamount = taxamount1*(baseperc1/100);
//                    }else if(taxamount1==0 && excessamount1==0 && taxrate1>0 ) {
//                        annualtaxamount = taxrate1*grosssales1*(baseperc1/100);
//                    }else if(taxamount1>0 && excessamount1>0 && taxrate1>0 ){
//                        annualtaxamount = ((Math.floor((grosssales1 - fromamount1) / excessamount1) * taxrate1 ) + taxamount1)*(baseperc1/100);
//                    }
//                        if(annualtaxamount<220) annualtaxamount = 220;
//                        setTableInputAmount("T1","u_btaxlinetotal",annualtaxamount,xxx);
//                }
//            }
//        }
        
        //compute total business tax
	var rc2 = getTableRowCount("T1"),btaxamount=0,surcharge=0,interest=0,taxbase=0;
        var     rc = getTableRowCount("T5");
        
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                        annualtaxcoderc = 0;
			btaxamount = getTableInputNumeric("T1","u_btaxlinetotal",i);             
			surcharge = getTableInputNumeric("T1","u_surcharge",i);             
			interest = getTableInputNumeric("T1","u_interest",i);     
			taxbase = getTableInputNumeric("T1","u_capital",i) + getTableInputNumeric("T1","u_nonessential",i);     
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T5",xxx)==false) {
                                if (getTableInput("T5","u_feecode",xxx)==getPrivate("annualtaxcode") && getTableInput("T5","u_businessline",xxx) == getTableInput("T1","u_businessline",i) && getTableInput("T5","u_year",xxx) == getTableInput("T1","u_year",i)) {
                                        setTableInputAmount("T5","u_amount",btaxamount,xxx);
                                        setTableInputAmount("T5","u_surcharge",surcharge,xxx);
                                        setTableInputAmount("T5","u_interest",interest,xxx);
                                        setTableInputAmount("T5","u_taxbase",taxbase,xxx);
                                        setTableInput("T5","u_businessline",getTableInput("T1","u_businessline",i),xxx);
                                        annualtaxcoderc=xxx;
                                }
                            }
                        }
                        
                        if (annualtaxcoderc==0) {
                                var data = new Array();
                                data["u_year"] = getTableInput("T1","u_year",i);
                                data["u_feecode"] = getPrivate("annualtaxcode");
                                data["u_feedesc"] = getPrivate("annualtaxdesc");
                                data["u_common"] = 1;
                                data["u_amount"] = formatNumericAmount(btaxamount);
                                data["u_surcharge"] = formatNumericAmount(surcharge);
                                data["u_interest"] = formatNumericAmount(interest);
                                data["u_taxbase"] = formatNumericAmount(taxbase);
                                data["u_businessline"] = getTableInput("T1","u_businessline",i);
                                insertTableRowFromArray("T5",data);
                        }
		}
	}
        
	computeTotalAssessment();
	
}


function setBusinessTaxes() {
	var mayorspermitrc=0, sanitaryinspectionfeerc=0,environmentalfeerc = 0,rc = getTableRowCount("T5");
	
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
				if (getTableInput("T5","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
						if (getPrivate("bplkindcharlink")=="1") {
							if (getInputNumeric("u_businesskindmayorstax")>0) setTableInputAmount("T5","u_amount",getInputNumeric("u_businesskindmayorstax"),xxx);
							else setTableInputAmount("T5","u_amount",getInputNumeric("u_businesscharmayorstax"),xxx);
						}else {
							setTableInputAmount("T5","u_amount",getInputNumeric("u_businesskindmayorstax"),xxx);
						}
					mayorspermitrc=xxx;
				}
				if (getTableInput("T5","u_feecode",xxx)==getPrivate("garbagefeecode")) {
					if (getTableInput("T5","u_year",xxx)>getInput("lastpayyear")) {
						if (getPrivate("bplkindcharlink")=="1") {
							if (getInputNumeric("u_businesskindenvironmenttax")>0) setTableInputAmount("T5","u_amount",getInputNumeric("u_businesskindenvironmenttax"),xxx);
							else setTableInputAmount("T5","u_amount",getInputNumeric("u_businesscharenvironmenttax"),xxx);
						}else {
							setTableInputAmount("T5","u_amount",getInputNumeric("u_businesskindenvironmenttax"),xxx);
						}
					} 
					environmentalfeerc=xxx;
				}
				if (getTableInput("T5","u_feecode",xxx)==getPrivate("sanitaryinspectionfeecode")) {
					if (getPrivate("bplkindcharlink")=="1") {
						if (getInputNumeric("u_businesskindtax")>0) setTableInputAmount("T5","u_amount",getInputNumeric("u_businesskindtax"),xxx);
						else setTableInputAmount("T5","u_amount",getInputNumeric("u_businesschartax"),xxx);
					} else {
						setTableInputAmount("T5","u_amount",getInputNumeric("u_businesskindtax"),xxx);
					}					
					sanitaryinspectionfeerc=xxx;
				}
		}
	}
	if (environmentalfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("garbagefeecode");
		data["u_feedesc"] = getPrivate("garbagefeedesc");
		data["u_common"] = 1;
		data["u_regulatory"] = 1;
                if (getPrivate("bplkindcharlink")=="1") {
			if (getInputNumeric("u_businesskindenvironmenttax")>0) data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindenvironmenttax"));
			else  data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesscharenvironmenttax"));
		} else {
			data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindenvironmenttax"));
		}
		insertTableRowFromArray("T5",data);
	}
	if (mayorspermitrc==0) {
		var data = new Array();
                data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("mayorspermitcode");
		data["u_feedesc"] = getPrivate("mayorspermitdesc");
		data["u_common"] = 1;
		data["u_regulatory"] = 1;
                if (getPrivate("bplkindcharlink")=="1") {
			if (getInputNumeric("u_businesskindmayorstax")>0) data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindmayorstax"));
			else  data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesscharmayorstax"));
		} else {
			data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindmayorstax"));
		}
		insertTableRowFromArray("T5",data);
	}
	if (sanitaryinspectionfeerc==0) {
		var data = new Array();
                data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("sanitaryinspectionfeecode");
		data["u_feedesc"] = getPrivate("sanitaryinspectionfeedesc");
		data["u_common"] = 1;
		data["u_regulatory"] = 1;
		if (getPrivate("bplkindcharlink")=="1") {
			if (getInputNumeric("u_businesskindtax")>0) data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindtax"));
			else  data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesschartax"));
		} else {
			data["u_amount"] = formatNumericAmount(getInputNumeric("u_businesskindtax"));
		}			
		insertTableRowFromArray("T5",data);
	}
	u_ComputePenaltySurchargeGPSBPLS();
	computeTotalAssessment();
}

function setAppTypeTaxes() {
	var rc = getTableRowCount("T5");
	var platefeerc = 0,tcrc = 0;
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_feecode",xxx)==getPrivate("platefeecode")) {
				if (getInput("u_apptype")=="NEW") {
					setTableInputAmount("T5","u_amount",getInputNumeric("u_platefee"),xxx);
				} else {
					setTableInputAmount("T5","u_amount",250,xxx);
				}
				platefeerc=xxx;
			}
//                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("tcfeecode")) {
//				if (getInput("u_apptype")=="RENEW") {
//					setTableInputAmount("T5","u_amount",100,xxx);
//				} else {
//					setTableInputAmount("T5","u_amount",0,xxx);
//				}
//				tcrc=xxx;
//			}
		}
	}
	if (platefeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("platefeecode");
		data["u_feedesc"] = getPrivate("platefeedesc");
		data["u_common"] = 5;
		if (getInput("u_apptype")=="NEW") {
			data["u_amount"] = formatNumericAmount(getInputNumeric("u_platefee"));
		} else {
			data["u_amount"] = formatNumericAmount(250);
		}			
		insertTableRowFromArray("T5",data);
	}
//	if (tcrc==0) {
//		var data = new Array();
//		data["u_feecode"] = getPrivate("tcfeecode");
//		data["u_feedesc"] = getPrivate("tcfeedesc");
//		data["u_common"] = 5;
//		if (getInput("u_apptype")=="RENEW") {
//			data["u_amount"] = formatNumericAmount(100);
//		} else {
//			data["u_amount"] = formatNumericAmount(0);
//		}			
//		insertTableRowFromArray("T5",data);
//	}
}

function setRequirementsTaxes(table,row) {
	var rc = getTableRowCount("T5");
	var feerc = 0;
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_feecode",xxx)==getTableInput(table,"u_feecode",row)) {
				if (getTableInputNumeric(table,"u_check",row)==1) {
					setTableInputAmount("T5","u_amount",getTableInputNumeric(table,"u_chkfee",row),xxx);
				} else {
					setTableInputAmount("T5","u_amount",getTableInputNumeric(table,"u_uchkfee",row),xxx);
				}
				feerc=xxx;
			}
		}
	}
	if (feerc==0) {
		var data = new Array();
		data["u_feecode"] = getTableInput(table,"u_feecode",row);
		data["u_feedesc"] = getTableInput(table,"u_feedesc",row);
		data["u_common"] = 3;
		if (getTableInputNumeric(table,"u_check",row)==1) {
			data["u_amount"] = formatNumericAmount(getTableInputNumeric(table,"u_chkfee",row));
		} else {
			data["u_amount"] = formatNumericAmount(getTableInputNumeric(table,"u_uchkfee",row));
		}			
		insertTableRowFromArray("T5",data);
	}
}

function setAdditionalRequirementsTaxes() {
/*	var rc2 = getTableRowCount("T2");
	for (yyy = 1; yyy <=rc2; yyy++) {
		if (isTableRowDeleted("T2",yyy)==false) {
			if (getTableInput("T2","u_feecode",yyy)!="") {
				var rc = getTableRowCount("T5");
				var feerc = 0;
				for (xxx = 1; xxx <=rc; xxx++) {
					if (isTableRowDeleted("T5",xxx)==false) {
						if (getTableInput("T5","u_feecode",xxx)==getTableInput("T2","u_feecode",yyy)) {
							if (getTableInputNumeric("T2","u_check",yyy)==1)
								setTableInputAmount("T5","u_amount",getTableInputNumeric("T2","u_chkfee",yyy),xxx);
							} else {
								setTableInputAmount("T5","u_amount",getTableInputNumeric("T2","u_uchkfee",yyy),xxx);
							}
							feerc=xxx;
						}
					}
				}
				if (feerc==0) {
					var data = new Array();
					data["u_feecode"] = getTableInput("T2","u_feecode",yyy);
					data["u_feedesc"] = getTableInput("T2","u_feedesc",yyy);
					data["u_common"] = 3;
					if (getTableInputNumeric("T2","u_check",yyy)==1) {
						data["u_amount"] = formatNumericAmount(getTableInputNumeric("T2","u_chkfee",yyy));
					} else {
						data["u_amount"] = formatNumericAmount(getTableInputNumeric("T2","u_uchkfee",yyy));
					}			
					insertTableRowFromArray("T5",data);
				}
				
			}
		}
	}
	*/
}

function setCategoryTaxes() {
	var sanitarypermitrc=0, fireinspectionfeerc=0,rc = getTableRowCount("T5");
	if (getPrivate("bplcategsanitarypermitlink")=="1" || getPrivate("bplcategfireinsfeelink")=="1") {
		for (xxx = 1; xxx <=rc; xxx++) {
			if (isTableRowDeleted("T5",xxx)==false) {
				if (getPrivate("bplcategsanitarypermitlink")=="1") {
					if (getTableInput("T5","u_feecode",xxx)==getPrivate("sanitarypermitcode")) {
						setTableInputAmount("T5","u_amount",getInputNumeric("u_sanitarypermitfee"),xxx);
						sanitarypermitrc=xxx;
					}
				}	
				if (getPrivate("bplcategfireinsfeelink")=="1") {
					if (getTableInput("T5","u_feecode",xxx)==getPrivate("fireinspectionfeecode")) {
						setTableInputAmount("T5","u_amount",getInputNumeric("u_fireinspectionfee"),xxx);
						fireinspectionfeerc=xxx;
					}
				}	
			}
		}
		if (getPrivate("bplcategsanitarypermitlink")=="1") {
			if (sanitarypermitrc==0) {
				var data = new Array();
				data["u_feecode"] = getPrivate("sanitarypermitcode");
				data["u_feedesc"] = getPrivate("sanitarypermitdesc");
				data["u_common"] = 1;
				data["u_amount"] = formatNumericAmount(getInputNumeric("u_sanitarypermitfee"));
				insertTableRowFromArray("T5",data);
			}
		}	
		if (getPrivate("bplcategfireinsfeelink")=="1") {
			if (fireinspectionfeerc==0) {
				var data = new Array();
				data["u_feecode"] = getPrivate("fireinspectionfeecode");
				data["u_feedesc"] = getPrivate("fireinspectionfeedesc");
				data["u_common"] = 1;
				data["u_amount"] = formatNumericAmount(getInputNumeric("u_fireinspectionfee"));
				insertTableRowFromArray("T5",data);
			}
		}	
		computeTotalAssessment();
	}	
}

function setEmployeeTaxes() {
	var pforc=0, sfherc=0,rc = getTableRowCount("T5");
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_feecode",xxx)==getPrivate("pfofeecode")) {
				setTableInputAmount("T5","u_amount",getInputNumeric("u_pfofee")*(getInputNumeric("u_empcount")),xxx);
				pforc=xxx;
			}
//			if (getTableInput("T5","u_feecode",xxx)==getPrivate("sfhefeecode")) {
//				setTableInputAmount("T5","u_amount",getInputNumeric("u_sfhefee")*(getInputNumeric("u_empcount")+getInputNumeric("u_emplgucount")),xxx);
//				sfherc=xxx;
//			}
		}
	}
	if (pforc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("pfofeecode");
		data["u_feedesc"] = getPrivate("pfofeedesc");
		data["u_common"] = 1;
		data["u_regulatory"] = 1;
                data["u_amount"] = formatNumericAmount(getInputNumeric("u_pfofee")*(getInputNumeric("u_empcount")));
		insertTableRowFromArray("T5",data);
	}
//	if (sfherc==0) {
//		var data = new Array();
//		data["u_feecode"] = getPrivate("sfhefeecode");
//		data["u_feedesc"] = getPrivate("sfhefeedesc");
//		data["u_common"] = 1;
//		data["u_amount"] = formatNumericAmount(getInputNumeric("u_sfhefee")*(getInputNumeric("u_empcount")+getInputNumeric("u_emplgucount")));
//		insertTableRowFromArray("T5",data);
//	}
	computeTotalAssessment();
}
	
function setBarangayFees() {
	var garbagefeecoderc=0, rc = getTableRowCount("T5");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_feecode",xxx)==getPrivate("garbagefeecode")) {
				if (getInput("u_bunittype")=="Commercial") {
					//setTableInputAmount("T5","u_amount",getInputNumeric("u_garbagefeec"),xxx);
				} else {
					//setTableInputAmount("T5","u_amount",getInputNumeric("u_garbagefeer"),xxx);
				}
				garbagefeecoderc=xxx;
			}
		}
	}
	if (garbagefeecoderc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("garbagefeecode");
		data["u_feedesc"] = getPrivate("garbagefeedesc");
		data["u_common"] = 1;
		if (getInput("u_bunittype")=="Commercial") {
			//data["u_amount"] = formatNumericAmount(getInputNumeric("u_garbagefeec"));
		} else {
			//data["u_amount"] = formatNumericAmount(getInputNumeric("u_garbagefeer"));
		}
		insertTableRowFromArray("T5",data);
	}
	computeTotalAssessment();
}

function setBusinessLineFees() {
	var rc =  getTableRowCount("T5");
	for (xxx = rc; xxx >=1; xxx--) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_common",xxx)=="2") {
				deleteTableRow("T5",xxx);
			}
		}
	}
	//alert("select a.u_feecode, a.u_feedesc, a.u_amount from u_bpllinefees a where a.code in ("+getTableInputGroupConCat("T1","u_businessline")+")");
	var result = page.executeFormattedQuery("select a.u_feecode, a.u_feedesc, a.u_amount from u_bpllinefees a where a.code in ("+getTableInputGroupConCat("T1","u_businessline")+")");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				var data = new Array();
				data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
				data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
				data["u_amount"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_amount"));
				data["u_common"] = "2";
				insertTableRowFromArray("T5",data);
			}
		}		
	} else {
		page.statusbar.showError("Error retrieving additional requirements. Try Again, if problem persists, check the connection.");	
		return false;
	}
	computeTotalAssessment();
}

function computeTotalAssessment() {
	var rc = getTableRowCount("T5"),total=0,totalpenalty = 0 ,totalinterest = 0 ,btax=0,btax1=0,taxrc=0, firefee = 0, firetotalamount = 0;
        var fireinspectionfeerc = 0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T5",i)==false) {
                        if(getTableInput("T5","u_regulatory",i) == 1 && getTableInput("T5","u_year",i) == getInput("u_year")){
                                //firefee+= getTableInputNumeric("T5","u_amount",i) + getTableInputNumeric("T5","u_surcharge",i) + getTableInputNumeric("T5","u_interest",i);
                                firefee+= getTableInputNumeric("T5","u_amount",i);
                        }
		}
	}
        if (firefee > 0) {
                firetotalamount  = (firefee) *.15;
                if (firetotalamount < 500)  firetotalamount = 500;
                if (getPrivate("incfirebusiness")=="1") {
                     for (xxx = 1; xxx <=rc; xxx++) {
                        if (isTableRowDeleted("T5",xxx)==false) {
                            if (getTableInput("T5","u_feecode",xxx)==getPrivate("fireinspectionfeecode") && getTableInput("T5","u_year",xxx) == getInput("u_year")) {
                                if (getTableInput("T5","u_year",xxx) >= getPrivate("incfirestartyear")) setTableInputAmount("T5","u_amount",firetotalamount,xxx);
                                else setTableInputAmount("T5","u_amount",0,xxx);
                                fireinspectionfeerc=xxx;
                            }
                        }
                    }
                    if (fireinspectionfeerc==0) {
                            var data = new Array();
                            data["u_year"] = getInput("u_year");
                            data["u_feecode"] = getPrivate("fireinspectionfeecode");
                            data["u_feedesc"] = getPrivate("fireinspectionfeedesc");
                            data["u_common"] = 1;
                            data["u_surcharge"] = formatNumericAmount(0);
                            data["u_interest"] = formatNumericAmount(0);
                            data["u_seqno"] = 200;
                            data["u_amount"] = formatNumericAmount(firetotalamount);
                            insertTableRowFromArray("T5",data);
                    }
                }
        }
        
       
        for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T5",i)==false) {
			total+= getTableInputNumeric("T5","u_amount",i);
			totalpenalty+= getTableInputNumeric("T5","u_surcharge",i);
			totalinterest+= getTableInputNumeric("T5","u_interest",i);
			if (getTableInput("T5","u_feecode",i)==getPrivate("annualtaxcode")) {
				btax+= getTableInputNumeric("T5","u_amount",i);
			}
		}
	}

	setInputAmount("u_fireasstotal",firetotalamount);
	setInputAmount("u_btaxamount",btax);
	setInputAmount("u_asstotal",total + totalinterest + totalpenalty);
}

function computeTotalFireAssessment() {
	var rc = getTableRowCount("T8"),total=0,btax=0,btax1=0,taxrc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T8",i)==false) {
			total+= getTableInputNumeric("T8","u_amount",i);                 
		}
	}
   
	setInputAmount("u_fireasstotal",total);
}
function computeTotalRequirementAssessment() {
	var rc = getTableRowCount("T9"),total=0;
	for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T9",i)==false) {
                total+= getTableInputNumeric("T9","u_amount",i);
            }
	}
	setInputAmount("u_reqappfeestotal",total);
}

function takePhoto(targetObjectId,targetOptions) {

	if (targetOptions==null) targetOptions = "view";
	OpenPopup(670,280,'./udp.php?objectcode=u_capturephoto' + '' + '&targetId=' + targetObjectId + '&targetOptions=' + targetOptions,targetObjectId);
}

function setPhoto(p_value) {
	setElementValueById("pf_photodata",p_value);
	document.getElementById('PhotoImg').src = p_value;
}

function OpenLnkBtnu_bplapps(targetObjectId) {
	OpenLnkBtn(1080,680,'./udo.php?objectcode=u_bplapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}
function OpenLnkBtnu_bplreqapps(targetObjectId) {
	OpenLnkBtn(1400,680,'./udo.php?objectcode=u_zoningclrapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	OpenLnkBtn(1280,580,'./udo.php?objectcode=u_bldgapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}
function u_updatebusinessGPSBPLS() {
        OpenPopup(1024,600,"./udo.php?&objectcode=u_bplupdateapps&sf_keys="+getInput("docno")+"&formAction=e","UpdPays");
}
function u_PaymentHistoryGPSBPLS() {
        OpenPopup(1280,600,"./udp.php?&objectcode=u_bplbusinessledger&df_refno2="+getInput("u_appno")+"","Business Ledger");
}
function u_OLDPaymentHistoryGPSBPLS() {
        OpenPopup(1280,600,"./udp.php?&objectcode=u_bplbusinessledgerold2&df_refno2="+getInput("u_appno")+"","Old Business Ledger");
}
function u_MultipleFeesGPSBPLS() {
        OpenPopup(750,500,"./udp.php?&objectcode=u_addmultiplefees","Add Multiple Fees");
}
function openupdpays() {
    if (getTableSelectedRow("T101") > 0) OpenPopup(1024,600,"./udo.php?&objectcode=u_bplupdgross&sf_keys="+getTableInput("T101","docno",getTableSelectedRow("T101"))+"&formAction=e","UpdPays");
    else page.statusbar.showWarning("Please select the record again. if problem persists, check the connection.");
	
}
function ComputeReqAssessment(){
}
function addslashes(string) {
    return string.replace(/\\/g, '\\\\').
        replace(/\u0008/g, '\\b').
        replace(/\t/g, '\\t').
        replace(/\n/g, '\\n').
        replace(/\f/g, '\\f').
        replace(/\r/g, '\\r').
        replace(/'/g, '\\\'').
        replace(/"/g, '\\"');
}


function setGarbagesFees() {
	//alert(getInput("u_weightstax"));
	var feecoderc = 0, rc = getTableRowCount("T5"), feeamt = 0;
		if (getInput("u_garbagetax")!="") {
			var result = page.executeFormattedQuery("select u_amount from u_bplgarbagetax where code='"+getInput("u_garbagetax")+"'");	
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						feeamt = result.childNodes.item(0).getAttribute("u_amount");
					}else {
						feeamt = 0;
						page.statusbar.showError("Invalid Garbage Fees");	
						return false;
					}
				}else {
						feeamt = 0;
						page.statusbar.showError("Error retrieving Garbage Fees. Try Again, if problem persists, check the connection.");	
						return false;
				}
		}
			
	
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_feecode",xxx)=="0055") {
			      setTableInputAmount("T5","u_amount",feeamt,xxx);
			      feecoderc=xxx;
			}
		}
	}
	if (feecoderc==0){
		var data = new Array();
		data["u_feecode"] = "0055";
		data["u_feedesc"] = "GARBAGE FEE";
		data["u_common"] = 1;
		data["u_amount"] = formatNumericAmount(feeamt);
		insertTableRowFromArray("T5",data);
	}
	
	computeTotalAssessment();
}

function setWeightsandMeasureFees() {
	//alert(getInput("u_weightstax"));
	var feecoderc = 0, rc = getTableRowCount("T5"), feeamt = 0;
		if (getInput("u_weightstax")!="") {
			var result = page.executeFormattedQuery("select u_amount from u_bplweightstax where code='"+getInput("u_weightstax")+"'");	
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						feeamt = result.childNodes.item(0).getAttribute("u_amount");
					}else {
						feeamt = 0;
						page.statusbar.showError("Invalid Weights & Measure Fees");	
						return false;
					}
				}else {
						feeamt = 0;
						page.statusbar.showError("Error retrieving Weights & Measure Fees. Try Again, if problem persists, check the connection.");	
						return false;
				}
		}
			
	
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
			if (getTableInput("T5","u_feecode",xxx)=="0369") {
			      setTableInputAmount("T5","u_amount",feeamt,xxx);
			      feecoderc=xxx;
			}
		}
	}
	if (feecoderc==0){
		var data = new Array();
		data["u_feecode"] = "0369";
		data["u_feedesc"] = "WEIGHTS AND MEASURES";
		data["u_common"] = 1;
		data["u_amount"] = formatNumericAmount(feeamt);
		insertTableRowFromArray("T5",data);
	}
	
	computeTotalAssessment();
}


function u_ComputePenaltySurchargeGPSBPLS() {
        var penaltyfeecoderc = 0, surchargefeecoderc = 0, rc = getTableRowCount("T5"), feeamt = 0,btaxamount = 0, totalamount = 0, mayorsfeeamount = 0;
        var penaltyperc=0;
        var penaltyamtbtax=0;
        var surchargeamtbtax=0;
        var penaltyamtmayors=0;
        var surchargeamtmayors=0;
        var rc2 = getTableRowCount("T1");
        var intperc=0;
        
        var data = new Array();
        
        totalamount = getInputNumeric("u_asstotal");
        btaxamount = getInputNumeric("u_btaxamount");
        var payyear = getInput("u_year");
        for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T5",xxx)==false) {
                    var duedate1 = formatDateToDB("01/20/" + getTableInput("T5","u_year",xxx)).replace(/-/g,"");
                    var duedate2 = formatDateToDB("04/20/" + getTableInput("T5","u_year",xxx)).replace(/-/g,"");
                    var duedate3 = formatDateToDB("07/20/" + getTableInput("T5","u_year",xxx)).replace(/-/g,"");
                    var duedate4 = formatDateToDB("10/20/" + getTableInput("T5","u_year",xxx)).replace(/-/g,"");
                    if (getInput("u_decisiondate") != "")  var paydate =  formatDateToDB(getInput("u_decisiondate")).replace(/-/g,"");
                    else  var paydate =  formatDateToDB(getInput("u_appdate")).replace(/-/g,"");
                    var duemonth1 = parseInt(duedate1.substr(0,6));
                    var duemonth2 = parseInt(duedate2.substr(0,6));
                    var duemonth3 = parseInt(duedate3.substr(0,6));
                    var duemonth4 = parseInt(duedate4.substr(0,6));
                    var paymonth = parseInt(paydate.substr(0,6));
                    
                    //due to covid adjusted due date is implemented and also less 12 % for the interest
                    var adjduedate = formatDateToDB("06/30/2021").replace(/-/g,"");
                    
                    if (paydate>duedate1 && paydate>adjduedate && getInput("u_apptype") != "NEW") {
                        
                        penaltyperc = 0.25;
                        if(paymonth>duemonth1) intperc= (.02*(paymonth-duemonth1));
                        penaltyamtbtax = btaxamount * penaltyperc;
                        surchargeamtbtax = btaxamount * intperc ;
                        
                        //For bacoor 1st quarter no penalty discount
                        if ((getInput("u_year") == getTableInput("T5","u_year",xxx)) && parseInt(paydate.substr(4,2)) <= 3) {
                             penaltyperc = 0;
                        }
                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
                                setTableInputAmount("T5","u_surcharge",getTableInputNumeric("T5","u_amount",xxx) * penaltyperc,xxx);
                                penaltyfeecoderc=xxx;
                        }
                        
                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("annualtaxcode")) {
                                var dueyear = getTableInput("T5","u_year",xxx);
                                var intpercvalue=0;
                                if (payyear > dueyear) {
                                    intpercvalue = ((payyear-dueyear) * 12  ) - (parseInt(duedate1.substr(4,2)) - parseInt(paydate.substr(4,2)));
                                } else { 
                                    intpercvalue = paymonth-duemonth1;
                                }
                                if (paymonth>duemonth1) intperc = (.02*(intpercvalue));
                                if (getInput("lastpayyear") == getTableInput("T5","u_year",xxx)) {
                                    switch (getInput("lastpayqtr")) {
                                        case "1":
                                            intperc = intperc - .12;
                                            break;
                                        case "2":
                                            intperc = intperc - .15;
                                            break;
                                        case "3":
                                            intperc = intperc - .18;
                                            break;
                                    }
                                }
                                
                                //Bacoor memorandum, because of covid all surcharge less 12%
                                if (intperc >= .12) intperc = intperc - .12;
                                else intperc = 0;
                                
                                //For bacoor 1st quarter no penalty discount
                                if ((getInput("u_year") == getTableInput("T5","u_year",xxx)) && parseInt(paydate.substr(4,2)) <= 3) {
                                       intperc = 0;
                                }
                                setTableInputAmount("T5","u_surcharge",getTableInputNumeric("T5","u_amount",xxx) * penaltyperc,xxx);
                                setTableInputAmount("T5","u_interest",getTableInputNumeric("T5","u_amount",xxx) * intperc,xxx);
                                penaltyfeecoderc=xxx;
                        }
                        
                    } else {
                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
                            setTableInputAmount("T5","u_surcharge",0,xxx);
                        }
                        if (getTableInput("T5","u_feecode",xxx)==getPrivate("annualtaxcode")) {
                            setTableInputAmount("T5","u_surcharge",0,xxx);
                            setTableInputAmount("T5","u_interest",0,xxx);
                        }
                        
                    }
//			if (getTableInput("T5","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
//			      mayorsfeeamount += getTableInputNumeric("T5","u_amount",xxx);
//			}
		}
	}
        
//        if (paydate>duedate1) {
//                penaltyperc = 0.25;
//                if(paymonth>duemonth1) intperc= (.02*(paymonth-duemonth1));
//                penaltyamtbtax = btaxamount * penaltyperc;
//                surchargeamtbtax = btaxamount * intperc ;
//                
//                penaltyamtmayors = (mayorsfeeamount * penaltyperc);
//                //surchargeamtmayors = ((mayorsfeeamount*.25)* intperc);
//                for (xxx = 1; xxx <=rc2; xxx++) {
//                    if (isTableRowDeleted("T1",xxx)==false) {
//                            setTableInputAmount("T1","u_surcharge",getTableInputNumeric("T1","u_btaxlinetotal",xxx) * penaltyperc,xxx);
//                            setTableInputAmount("T1","u_interest",  getTableInputNumeric("T1","u_btaxlinetotal",xxx) * intperc,xxx);
//                    }
//                }
//                for (xxx = 1; xxx <=rc; xxx++) {
//                      if (isTableRowDeleted("T5",xxx)==false) {
//                                if (getTableInput("T5","u_feecode",xxx)==getPrivate("annualtaxcode")) {
//                                    var dueyear = getTableInput("T5","u_year",xxx);
//                                    var intpercvalue=0;
//                                    if (payyear > dueyear) {
//                                        intpercvalue = ((payyear-dueyear) * 12  ) - (parseInt(duedate1.substr(4,2)) - parseInt(paydate.substr(4,2)));
//                                    } else { 
//                                        intpercvalue = paymonth-duemonth1;
//                                    }
//                                    if(paymonth>duemonth1) intperc = (.02*(intpercvalue));
//                                    setTableInputAmount("T5","u_surcharge",getTableInputNumeric("T5","u_amount",xxx) * penaltyperc,xxx);
//                                    setTableInputAmount("T5","u_interest",getTableInputNumeric("T5","u_amount",xxx) * intperc,xxx);
//                                    penaltyfeecoderc=xxx;
//                                }
//                                if (getTableInput("T5","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
//                                      setTableInputAmount("T5","u_surcharge",getTableInputNumeric("T5","u_amount",xxx) * penaltyperc,xxx);
//                                      penaltyfeecoderc=xxx;
//                                }
//                              
//                      }
//                }
//                
//        }
//        else if (paydate>duedate2 && paydate <= duedate3) {
//                penaltyperc = 0.25;
//                if(paymonth>duemonth1) intperc= (.02*(paymonth-duemonth1));
//                penaltyamtbtax = ((btaxamount*.50)* penaltyperc) + ((totalamount-btaxamount)* penaltyperc);
//                surchargeamtbtax = ((btaxamount*.50)* intperc) + ((totalamount-btaxamount)* intperc);
//                
//                penaltyamtbtax = ((btaxamount*.50)* penaltyperc) + ((totalamount-btaxamount)* penaltyperc);
//                surchargeamtbtax = ((btaxamount*.50)* intperc) + ((totalamount-btaxamount)* intperc);
//
//         }else if (paydate>duedate3 && paydate <= duedate4) {
//                penaltyperc = 0.25;
//                if(paymonth>duemonth1) intperc= (.02*(paymonth-duemonth1));
//                penaltyamtbtax = ((btaxamount*.75)* penaltyperc) + ((totalamount-btaxamount)* penaltyperc);
//                surchargeamtbtax = ((btaxamount*.75)* intperc) + ((totalamount-btaxamount)* intperc);
//                
//                penaltyamtbtax = ((btaxamount*.75)* penaltyperc) + ((totalamount-btaxamount)* penaltyperc);
//                surchargeamtbtax = ((btaxamount*.75)* intperc) + ((totalamount-btaxamount)* intperc);
//       
//        }else if (paydate>duedate4 && paydate <= formatDateToDB("012/31/" + getInput("u_year")).replace(/-/g,"")) {
//                penaltyperc = 0.25;
//                if(paymonth>duemonth1) intperc= (.02*(paymonth-duemonth1));
//                penaltyamtbtax = ((btaxamount*1)* penaltyperc) + ((totalamount-btaxamount)* penaltyperc);
//                surchargeamtbtax = ((btaxamount*.1)* intperc) + ((totalamount-btaxamount)* intperc);
//                
//                 penaltyamtbtax = ((btaxamount*1)* penaltyperc) + ((totalamount-btaxamount)* penaltyperc);
//                surchargeamtbtax = ((btaxamount*.1)* intperc) + ((totalamount-btaxamount)* intperc);
//        }

        

	computeTotalAssessment();
}


function u_googlemapGPSBPLS() {
	OpenPopupViewMap("viewmap","U_BPLAPPS",getInput("u_bvillage") + " " + getInput("u_bstreet"),getInput("u_bbrgy"),getInput("u_bcity"),"","",getInput("u_bprovince"),"Philippines","");
	
}

function getAddressGIS() {
	if (getInput("u_gislat")!="" && getInput("u_gislng")!="") {
		var latlng = {lat: parseFloat(getInput("u_gislat")), lng: parseFloat(getInput("u_gislng"))};
	} else {
		var latlng = false;
	}	
	return latlng;
}

function setAddressGIS(lat,lng) {
	setInput("u_gislat",lat);
	setInput("u_gislng",lng);
}
function AddNewBPLMDS(){
    OpenPopup(1024,420,"./udo.php?&objectcode=u_bplmds","Business Permit Master Data");
}

function showPopupAuditedGross(){
    if (getInput("u_apptype")!= "RENEW") page.statusbar.showError("New application is not allowed for this action"); 
    else showPopupFrame("popupFrameAuditedGross",true); focusTableInput("T71","userid");
}
function u_BillAdjustmentsGPSBPLS() {
    //if(confirm("You cannot change this document after you click add. Continue?")){
        if (isTableInput("T71","userid")) {
            if (getTableInput("T71","userid")=="") {
                showPopupFrame("popupFrameAuditedGross",true);
                enableTableInput("T71","userid");
                enableTableInput("T71","password");
                focusTableInput("T71","userid");
                return false;
            }
        }
    var result = page.executeFormattedQuery("SELECT username, u_bplapprover from users where userid = '"+getTableInput("T71","userid")+"' and hpwd = '"+MD5(getTableInput("T71","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                if (result.childNodes.item(0).getAttribute("u_bplapprover") == 0) {
                    page.statusbar.showError("You are not allowed for this action");
                    return false;
                } else {
                    if (isPopupFrameOpen("popupFrameAuditedGross")) {
                        hidePopupFrame('popupFrameAuditedGross');
                    }
                    OpenPopup(1280,600,"./udo.php?&objectcode=u_bplupdgross&sf_keys="+getInput("code")+"&formAction=e","Audited Gross");
                }

            }
        } else {
            page.statusbar.showError("Invalid user or password.");
            return false;
        }
    //}
}

function u_deleteGPSBPLS() {
    //if(confirm("You cannot change this document after you click add. Continue?")){
        if (isTableInput("T81","userid")) {
            if (getTableInput("T81","userid")=="") {
                showPopupFrame("popupFrameDeleteRecord",true);
                enableTableInput("T81","userid");
                enableTableInput("T81","password");
                focusTableInput("T81","userid");
                return false;
            }
        }
     var result = page.executeFormattedQuery("SELECT username, u_bpldelete from users where userid = '"+getTableInput("T81","userid")+"' and hpwd = '"+MD5(getTableInput("T81","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                if (result.childNodes.item(0).getAttribute("u_bpldelete") == 0) {
                    page.statusbar.showError("You are not allowed for this action");
                    return false;
                } else {
                    if (confirm("Delete this record. Continue?")) {
                        setInput("docstatus","Delete");
                        if (isPopupFrameOpen("popupFrameDeleteRecord")) {
                            hidePopupFrame('popupFrameDeleteRecord');
                        }
                        formSubmit();
                    }
                }

            }
        } else {
            page.statusbar.showError("Invalid user or password.");
            return false;
        }
    //}
}

function computeCedulaTotalGPSBPLS() {
    var amount = 0;
    var grossamount = 0, basicamount = 0, ctcpenamount = 0, gross = 0;

    var rc = getTableRowCount("T1");
    for (xxx = 1; xxx <= rc; xxx++) {
        if (isTableRowDeleted("T1", xxx) == false) {
            if (getTableInput("T1", "u_year", xxx) == getInput("u_year")) {
                gross += (getTableInputNumeric("T1", "u_capital", xxx) + getTableInputNumeric("T1", "u_nonessential", xxx));
            }
        }
    }
    if (getInput("u_apptype") == "NEW") {
        if (getInput("u_orgtype") == "SINGLE")
            amount = 25;
        else
            amount = 500;
    } else {
        if (getInput("u_orgtype") == "SINGLE") {
            grossamount = (gross) / 1000;
            basicamount = 10;
            if (grossamount > 5000)
                grossamount = 5000;
        } else {
            grossamount = (gross / 5000) * 2;
            basicamount = 500;
            if (grossamount > 10000)
                grossamount = 10000;
        }

        var interest = 0, interestperc = 0;
        var duedate = getInput("u_year") + "0701";
        if (getInput("u_decisiondate") != "")  var paydate =  formatDateToDB(getInput("u_decisiondate")).replace(/-/g,"");
        else  var paydate =  formatDateToDB(getInput("u_appdate")).replace(/-/g,"");
        var duemonth = parseInt(duedate.substr(0, 6));
        var paymonth = parseInt(paydate.substr(0, 6));
        var dueyear = parseInt(duedate.substr(0, 4));
        var payyear = parseInt(paydate.substr(0, 4));

        if (payyear > dueyear) {
            interestperc = ((payyear - dueyear) * 12) - (parseInt(duedate.substr(4, 2)) - parseInt(paydate.substr(4, 2)));
        } else {
            interestperc = (paymonth - duemonth) + 1;
        }

        if (paydate >= duedate) {
            if (paymonth > 2)
                interest = (basicamount + grossamount) * (.02 * interestperc);
            else
                interest = 0;
        }

        amount = basicamount + grossamount + interest;
        if (getInput("u_orgtype") == "SINGLE") {
            if (amount < 25)
                amount = 25;
        } else {
            if (amount < 500)
                amount = 500;
        }
    }

    setInputAmount("u_ctcasstotal", amount);
}