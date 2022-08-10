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
                focusInput("u_lastname");
                setTableInput("T1","u_year",getInput("u_year"));
                setTableInput("T2","u_year",getInput("u_year"));
		if (getInput("u_apptype")!="") {
			//setAppTypeTaxes();
			//setBarangayFees();
		}
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
                if (isInputEmpty("u_acctno")) return false;
                if(getInput("u_apptype")=="NEW") if (isInputEmpty("u_appno",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_businessname")) return false;
		if (isInputEmpty("u_apptype")) return false;
		if (isInputEmpty("u_assdate")) return false;
		if (isInputEmpty("u_paymode")) return false;
		if (isInputNegative("u_asstotal")) return false;
//                if (isInputEmpty("u_reqappno")) return false;
//                if (isInputEmpty("u_businesschar")) return false;
		
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
	//alert(keyModifiers(event));
	switch (sc_press) {
                case "F2":
			if(getInput("docstatus") == "Approved"){
				u_cashierGPSRPTAS();
			}
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
                                                
                                        if (getInput("u_apptype")=="RENEW" || getInput("u_apptype")=="ADJUSTMENT") {
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
                                        } else if (getInput("u_apptype")=="RENEW" || getInput("u_apptype")=="ADJUSTMENT") {

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
//                                        var	rc = getTableRowCount("T2");
//                                        for (xxx = 1; xxx <=rc; xxx++) {
//                                                if (isTableRowDeleted("T2",xxx)==false) {
//                                                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode")) {
//                                                                alert
//                                                                setTableInputAmount("T2","u_amount",btaxamount,xxx);
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
//                                                insertTableRowFromArray("T2",data);
//                                        }
//                                        computeTotalAssessment();
                                break;
                        }
                    break;
               
		case "T2":
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
		
		default:
			switch (column) {
                                case "u_taxpayername":
                                case "u_businessname":
                                case "u_owneraddress":
                                    setInput(column,getInput(column).toUpperCase());
                                break;
//                                case "u_businessarea":
//                                    var rc = getTableRowCount("T2"),sqm=0;
//                                    sqm= getInputNumeric("u_businessarea",i)*15;
//                                                                                           //	alert(sqm);
//                                    for (i = 1; i <= rc; i++) {
//                                        if (isTableRowDeleted("T2",i)==false) {
//                                            if (getTableInput("T2","u_feecode",i)==getPrivate("garbagefeecode")) {
//                                                setTableInputAmount("T2","u_amount",sqm,i);
//                                            }
//                                        }
//                                    }
//                                                
//                                break;
				case "u_acctno":
                                         if (getInput(column)!="") {
                                                var result = page.executeFormattedQuery("select name,u_tradename, u_firstname,u_lastname,u_middlename,u_btelno,u_bbldgno,u_bbldgname,u_bunitno,u_bstreet,u_bbrgy,u_bfloorno,u_bblock,u_bvillage from u_bplmds where code='"+getInput(column)+"'");	
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                                setInput("u_businessname",result.childNodes.item(0).getAttribute("name"));
                                                                setInput("u_taxpayername",result.childNodes.item(0).getAttribute("u_firstname"));
                                                                setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_lastname"));
                                                                
                                                        } else {
                                                                setInput("u_businessname","");
                                                                setInput("u_taxpayername","");
                                                                setInput("u_owneraddress","");
                                                                page.statusbar.showError("Invalid Account No. Please process renew instead");	
                                                                return false;
                                                        }
                                                } else {
                                                                setInput("u_businessname","");
                                                                setInput("u_taxpayername","");
                                                                setInput("u_owneraddress","");
                                                        page.statusbar.showError("Error retrieving Account No. Try Again, if problem persists, check the connection.");	
                                                        return false;
                                                }
                                         }
                                        break;
				case "u_appno":
                                         if (getInput(column)!="") {
                                             
                                                clearTable("T1",true);
//                                                clearTable("T2",true);
                                                showAjaxProcess();
                                                var data = new Array();
                                                
                                                var rc = getTableRowCount("T2");
//                                                for (xxx1 = 1; xxx1 <=rc; xxx1++) {
//                                                    if (isTableRowDeleted("T2",xxx1)==false) {
//                                                        if (getTableInput("T2","u_feecode",xxx1)=="0001" ) {
//                                                            deleteTableRow("T2",xxx1);
//                                                        }
//                                                    }
//                                                }
                                                
                                                var result = page.executeFormattedQuery("select b.u_year,b.u_feecode,b.u_feedesc,b.u_amount,b.u_surcharge,b.u_interest,b.u_common,b.u_regulatory,b.u_businessline,b.u_seqno from u_bplapps  a left join u_bplappfees b on a.company = b.company and a.branch = b.branch and a.docid = b.docid where a.docno='"+getInput(column)+"'  and a.company = '"+getGlobal("company")+"' and a.branch = '"+getGlobal("branch")+"'");	
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                                for (var iii=0; iii<result.childNodes.length; iii++) {
                                                                        var rcdcnt = 0;
                                                                        for (xxx = 1; xxx <=rc; xxx++) {
                                                                            if (isTableRowDeleted("T2",xxx)==false) {
                                                                                if (getTableInput("T2","u_feecode",xxx)==result.childNodes.item(iii).getAttribute("u_feecode") && getTableInput("T2","u_year",xxx) == result.childNodes.item(iii).getAttribute("u_year")) {
                                                                                    setTableInputAmount("T2","u_amount",result.childNodes.item(iii).getAttribute("u_amount"),xxx);
                                                                                    setTableInputAmount("T2","u_surcharge",result.childNodes.item(iii).getAttribute("u_surcharge"),xxx);
                                                                                    setTableInputAmount("T2","u_interest",result.childNodes.item(iii).getAttribute("u_interest"),xxx);
                                                                                    rcdcnt=xxx;
                                                                                }
                                                                            }
                                                                        }
                                                                        if (rcdcnt==0) {
                                                                            var data = new Array();
                                                                            data["u_year"] = result.childNodes.item(iii).getAttribute("u_year");
                                                                            data["u_feecode"] = result.childNodes.item(iii).getAttribute("u_feecode");
                                                                            data["u_feedesc"] = result.childNodes.item(iii).getAttribute("u_feedesc");
                                                                            data["u_amount"] =  formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                                                            data["u_surcharge"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_surcharge"));
                                                                            data["u_interest"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_interest"));
                                                                            data["u_common"] = result.childNodes.item(iii).getAttribute("u_common");
                                                                            data["u_regulatory"] = result.childNodes.item(iii).getAttribute("u_regulatory");
                                                                            data["u_businessline"] = result.childNodes.item(iii).getAttribute("u_businessline");
                                                                            data["u_seqno"] = result.childNodes.item(iii).getAttribute("u_seqno");
                                                                            insertTableRowFromArray("T2",data);
                                                                        }
                                                                        
                                                                }
                                                        }
                                                }
                                                
                                                var result = page.executeFormattedQuery("select a.u_empcount,a.u_fempcount,a.u_mempcount,a.u_apptype,a.u_businesschar,a.u_businesskind,a.u_tradename,a.u_appno,a.u_orgtype, case when a.u_orgtype = 'single' then CONCAT(a.U_LASTNAME ,', ',a.U_FIRSTNAME ,' ', a.U_MIDDLENAME) when a.u_orgtype = 'cooperative' then a.u_corpname  when a.u_orgtype = 'corporation' and a.u_firstname = '' and a.u_lastname = '' then a.u_corpname when a.u_orgtype = 'corporation' and a.u_firstname <> '' or a.u_lastname <> '' then CONCAT(a.U_LASTNAME ,'  ',a.U_FIRSTNAME ,' ', a.U_MIDDLENAME) else a.u_corpname end as u_ownername , CONCAT(IF(IFNULL(A.U_BBLOCK,'') = '','',concat('BLK ',A.U_BBLOCK,' ')),IF(IFNULL(A.U_BLOTNO,'') = '','',concat('LOT ',A.U_BLOTNO,' ')), IF(IFNULL(A.U_BPHASENO,'') = '','',concat(A.U_BPHASENO,' ')),IF(IFNULL(A.U_BSTREET,'') = '','',concat(A.U_BSTREET,' ')), IF(IFNULL(A.U_BVILLAGE,'') = '','',concat(A.U_BVILLAGE,' ')),IF(IFNULL(A.U_BBRGY,'') = '','',concat(A.U_BBRGY,' ')),A.U_BCITY,' ',A.U_BPROVINCE) AS u_baddress,b.u_year,b.u_businessline,b.u_taxclass,b.u_capital,b.u_nonessential,b.u_btaxlinetotal,b.u_lastyrgrsales from u_bplapps  a left join u_bplapplines b on a.company = b.company and a.branch = b.branch and a.docid = b.docid where a.docno='"+getInput(column)+"'  and a.company = '"+getGlobal("company")+"' and a.branch = '"+getGlobal("branch")+"'");	
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                                for (var iii=0; iii<result.childNodes.length; iii++) {
                                                                        var data = new Array();
                                                                        data["u_year"] = result.childNodes.item(iii).getAttribute("u_year");
                                                                        data["u_businessline"] = result.childNodes.item(iii).getAttribute("u_businessline");
                                                                        data["u_taxclass"] = result.childNodes.item(iii).getAttribute("u_taxclass");
                                                                        data["u_capital"] =  formatNumericAmount(result.childNodes.item(iii).getAttribute("u_capital"));
                                                                        data["u_nonessential"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_nonessential"));
                                                                        data["u_btaxlinetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_btaxlinetotal"));
                                                                        data["u_lastyrgrsales"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_lastyrgrsales"));
                                                                        insertTableRowFromArray("T1",data);
                                                                }
                                                                setInput("u_apptype",result.childNodes.item(0).getAttribute("u_apptype"));
                                                                setInput("u_businessname",result.childNodes.item(0).getAttribute("u_tradename"));
                                                                setInput("u_taxpayername",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_baddress"));
                                                                setInput("u_orgtype",result.childNodes.item(0).getAttribute("u_orgtype"));
                                                                setInput("u_acctno",result.childNodes.item(0).getAttribute("u_appno"));
                                                                setInput("u_businesschar",result.childNodes.item(0).getAttribute("u_businesschar"));
                                                                setInput("u_businesskind",result.childNodes.item(0).getAttribute("u_businesskind"));
                                                                setInput("u_empcount",result.childNodes.item(0).getAttribute("u_empcount"));
                                                                setInput("u_fempcount",result.childNodes.item(0).getAttribute("u_fempcount"));
                                                                setInput("u_mempcount",result.childNodes.item(0).getAttribute("u_mempcount"));
                                                                
                                                        } else {
                                                                setInput("u_apptype","");
                                                                setInput("u_businessname","");
                                                                setInput("u_taxpayername","");
                                                                setInput("u_owneraddress","");
                                                                setInput("u_orgtype","");
                                                                setInput("u_acctno","");
                                                                setInput("u_businesschar","");
                                                                setInput("u_businesskind","");
                                                                setInput("u_empcount","");
                                                                setInput("u_fempcount","");
                                                                setInput("u_mempcount","");
                                                                
                                                                page.statusbar.showError("Invalid Account No. Please process renew instead");
                                                                hideAjaxProcess();
                                                                return false;
                                                        }
                                                } else {
                                                                setInput("u_apptype","");
                                                                setInput("u_businessname","");
                                                                setInput("u_taxpayername","");
                                                                setInput("u_owneraddress","");
                                                                setInput("u_orgtype","");
                                                                setInput("u_acctno","");
                                                                setInput("u_businesschar","");
                                                                setInput("u_businesskind","");
                                                                setInput("u_empcount","");
                                                                setInput("u_fempcount","");
                                                                setInput("u_mempcount","");
                                                        page.statusbar.showError("Error retrieving Account No. Try Again, if problem persists, check the connection.");	
                                                        hideAjaxProcess();
                                                        return false;
                                                }
                                                
                                                if (getInput("u_apptype")=="NEW") {
                                                    disableTableInput("T1","u_essential");
                                                    disableTableInput("T1","u_nonessential");
                                                    enableTableInput("T1","u_capital");
                                                } else {
                                                    disableTableInput("T1","u_capital");
                                                    enableTableInput("T1","u_essential");
                                                    enableTableInput("T1","u_nonessential");
                                                }
                                                hideAjaxProcess();
                                                computeCedulaTotalGPSBPLS();
                                                
                                         }
                                        break;
				case "u_reqappno":
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select u_isonlineapp,u_businessname,u_lastname,u_firstname,u_middlename,u_owneraddress,u_contactno,u_bbrgy,u_bbldgno,u_bblock,u_blotno,u_bvillage,u_bstreet,u_bphaseno,u_baddressno,u_bbldgname,u_bunitno,u_bfloorno,u_btelno,u_year,u_feecode,u_feedesc,sum(u_amount) u_amount, sum(u_surcharge)  as u_surcharge, u_seqno from ( select a.u_isonlineapp,a.u_businessname,a.u_lastname,a.u_firstname,a.u_middlename,a.u_owneraddress,a.u_contactno,a.u_bbrgy,a.u_bbldgno,a.u_bblock,a.u_blotno,a.u_bvillage,a.u_bstreet,a.u_bphaseno,a.u_baddressno,a.u_bbldgname,a.u_bunitno,a.u_bfloorno,a.u_btelno,b.u_year,b.u_feecode,b.u_feedesc,b.u_amount, 0 as u_surcharge,b.u_seqno from u_bldgapps a inner join u_bldgappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' and docstatus not in('CN','D') and b.u_feecode != '0506' union all select a.u_isonlineapp,a.u_businessname,a.u_lastname,a.u_firstname,a.u_middlename,a.u_owneraddress,a.u_contactno,a.u_bbrgy,a.u_bbldgno,a.u_bblock,a.u_blotno,a.u_bvillage,a.u_bstreet,a.u_bphaseno,a.u_baddressno,a.u_bbldgname,a.u_bunitno,a.u_bfloorno,a.u_btelno,b.u_year,'0012' u_feecode,'Building Permit Fee' u_feedesc,0 u_amount , b.u_amount as u_surcharge,b.u_seqno from u_bldgapps a inner join u_bldgappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' and docstatus not in('CN','D') and b.u_feecode = '0506' ) as x group by u_feecode ");	
                                                    var data = new Array();
                                                    var rc = getTableRowCount("T2");
                                                    var reqtotal = 0;
                                                    showAjaxProcess();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (var iii=0; iii<result.childNodes.length; iii++) {
                                                                    var feerc = 0;
                                                                    for (xxx = 1; xxx <=rc; xxx++) {
                                                                        if (isTableRowDeleted("T2",xxx)==false) {
                                                                            if (getTableInput("T2","u_feecode",xxx)==result.childNodes.item(iii).getAttribute("u_feecode") ) {
                                                                            setTableInputAmount("T2","u_amount",result.childNodes.item(iii).getAttribute("u_amount"),xxx);
                                                                            if (getTableInput("T2","u_year",xxx) == result.childNodes.item(iii).getAttribute("u_year")){
                                                                                setTableInputAmount("T2","u_surcharge",result.childNodes.item(iii).getAttribute("u_surcharge"),xxx);
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
                                                                        insertTableRowFromArray("T2",data);
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
                                                                        if (isTableRowDeleted("T2",xxx)==false) {
                                                                            if (getTableInput("T2","u_feecode",xxx)==result.childNodes.item(iii).getAttribute("u_feecode") ) {
                                                                            setTableInputAmount("T2","u_amount",result.childNodes.item(iii).getAttribute("u_amount"),xxx);
                                                                            if (getTableInput("T2","u_year",xxx) == result.childNodes.item(iii).getAttribute("u_year")){
                                                                                setTableInputAmount("T2","u_surcharge",result.childNodes.item(iii).getAttribute("u_surcharge"),xxx);
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
                                                                        insertTableRowFromArray("T2",data);
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

                    if (getInput("u_apptype") == "RENEW" || getInput("u_apptype")=="ADJUSTMENT") {
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
                    } else if (getInput("u_apptype") == "RENEW" || getInput("u_apptype")=="ADJUSTMENT") {

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
                                        var     rc = getTableRowCount("T2");
                                        var     rc2 = getTableRowCount("T1");
                                            for (xxx2 = 1; xxx2 <=rc; xxx2++) {
                                                    if (isTableRowDeleted("T2",xxx2)==false) {
                                                            if (getTableInput("T2","u_year",xxx2) > getInput(column) ) {
                                                                deleteTableRow("T2",xxx2);
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
                                            var lastpayyear = parseInt(getInputNumeric("lastpayyear") + 1);
                                            while (lastpayyear<=getInputNumeric(column)) {
                                                  var result = page.executeFormattedQuery("select code, name, u_amount,u_regulatory,u_seqno from u_bplfees  order by u_seqno asc");
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx1 = 0; xxx1 < result.childNodes.length; xxx1++) {
                                                                var feectr = 0;
                                                                for (xxx2 = 1; xxx2 <=rc; xxx2++) {
                                                                    if (isTableRowDeleted("T2",xxx2)==false) {
                                                                        if(getTableInput("T2","u_year",xxx2) == lastpayyear && getTableInput("T2","u_feecode",xxx2) == result.childNodes.item(xxx1).getAttribute("code")) {
                                                                            feectr = xxx2;
                                                                        } 
                                                                    }
                                                                }
                                                                if (feectr == 0) {
                                                                    var data = new Array();
                                                                    data["u_year"] = lastpayyear;
                                                                    data["u_feecode"] = result.childNodes.item(xxx1).getAttribute("code");
                                                                    data["u_feedesc"] = result.childNodes.item(xxx1).getAttribute("name");
                                                                    data["u_common"] = 1;
                                                                    data["u_amount"] = formatNumericAmount(result.childNodes.item(xxx1).getAttribute("u_amount"));
                                                                    data["u_surcharge"] = formatNumericAmount(0);
                                                                    data["u_interest"] = formatNumericAmount(0);
                                                                    data["u_seqno"] = result.childNodes.item(xxx1).getAttribute("u_seqno");
                                                                    data["u_regulatory"] = result.childNodes.item(xxx1).getAttribute("u_regulatory");
                                                                    insertTableRowFromArray("T2",data);
                                                                }
                                                            }
                                                        }		
                                                    }
                                                 
                                                 lastpayyear = lastpayyear +1;
                                             }
                                            
                                            
                                        computeAnnualTax(); 
                                        computeCedulaTotalGPSBPLS();
                                    }
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
                                    
			}
	}
	return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
                            
				case "u_withmiscellaneous": 
                                    if(isInputChecked(column)){
                                            setAdjustmentMiscFees();
                                    } else {
                                        var rc =  getTableRowCount("T2");
                                        for (xxx = rc; xxx <= rc + 1; xxx ++) {
                                                if (isTableRowDeleted("T2",xxx)==false) {
                                                        if (getTableInput("T2","u_common",xxx)=="6") {
                                                                deleteTableRow("T2",xxx);
                                                        }
                                                }
                                        }
                                    }
                                     computeTotalAssessment();
                                break;
				case "u_orgtype": 
                                    computeCedulaTotalGPSBPLS(); 
                                break;
				case "u_apptype":
					if (getInput("u_apptype")=="NEW") {
						disableTableInput("T1","u_essential");
						disableTableInput("T1","u_nonessential");
						enableTableInput("T1","u_capital");
						disableInput("u_acctno");
					} else {
						enableInput("u_acctno");
                                                focusInput("u_acctno");
						disableTableInput("T1","u_capital");
						enableTableInput("T1","u_essential");
						enableTableInput("T1","u_nonessential");
					}
					setAppTypeTaxes();
					break;
				case "u_paymode":
					setInput("u_envpaymode",getInput("u_paymode"));
					break;
			}
                break;
                
                case "T3":
                    switch(column) {
                            case "u_selected":
                                var feecode =getTableInput("T3","u_feecode",row);
                                var year =getTableInputNumeric("T3","u_year",row);
                                var businessline =getTableInputNumeric("T3","u_businessline",row);
                                var selected =getTableInput("T3","u_selected",row);
                                var ctr = 0;
                                var rc =  getTableRowCount("T2");
                                for (xxx = 1; xxx <= rc; xxx++) {
                                    if (isTableRowDeleted("T2",xxx)==false) {
                                        if (getTableInput("T2","u_feecode",xxx)== feecode &&  year == getTableInput("T2","u_year",xxx) && businessline == getTableInput("T2","u_businessline",xxx) ) {
                                               ctr = 1;
                                        }
                                    }
                                }
                                if (ctr == 0){
                                   
                                    uncheckedTableInput(table,"u_selected",row);
                                    setStatusMsg("Fee Code, Year or Line of Busine for this line credit, doesn't match in any of the fees in the assessment.",4000,1);
                               
                                }
                            break;
                    }
                    computeTotalAssessment();
                break;
	}
	return true;
}

function onElementCFLGPSBPLS(element) {
	return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,u_businessname,u_year,docstatus  from u_bplapps where docstatus = 'Encoding' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No`Account No`Business Name`Year`Status")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`8`8")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
		case "df_u_reqappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,'Zoning' as u_office,u_businessname,u_year,docstatus  from u_zoningclrapps where docstatus != 'D' and u_bplappno = '' and u_acctno like '%"+getInput("u_acctno")+"%' union all select docno,'Building' as u_office,u_businessname,u_year,docstatus  from u_bldgapps where docstatus != 'D' and u_bplappno = '' and u_acctno like '%"+getInput("u_acctno")+"%' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No`Department`Business Name`Year`Status")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`8`8")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
		case "df_u_feecodeT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name  from u_lgufees a,u_lgusetup b where a.code != b.u_annualtax")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,a.code  from u_lgufees a,u_lgusetup b where a.code != b.u_annualtax")); 
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
			} else if (getInput("u_apptype")=="RENEW" || getInput("u_apptype")=="ADJUSTMENT") {
				if (getTableInputNumeric(table,"u_essential")+getTableInputNumeric(table,"u_nonessential")==0) {
					if (isTableInputNegative(table,"u_essential")) return false;
				}
			} else {
				if (isInputEmpty("u_apptype")) return false;
			}
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			break;
		case "T2":
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
	
	}
	return true;
}

function onTableAfterInsertRowGPSBPLS(table,row) {
	switch (table) {
		case "T1":  setBusinessLineFees(); computeAnnualTax(); computeCedulaTotalGPSBPLS(); u_ComputePenaltySurchargeGPSBPLS(); break;
		case "T2": computeTotalAssessment();break;
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
			} else if (getInput("u_apptype")=="RENEW" || getInput("u_apptype")=="ADJUSTMENT") {
				if (getTableInputNumeric(table,"u_essential")+getTableInputNumeric(table,"u_nonessential")==0) {
					if (isTableInputNegative(table,"u_essential")) return false;
				}
			} else {
				if (isInputEmpty("u_apptype")) return false;
			}
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);   
			break;
		case "T2": 
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
		
	}
	return true;
}

function onTableAfterUpdateRowGPSBPLS(table,row) {
	switch (table) {
		case "T1":  setBusinessLineFees(); computeAnnualTax(); computeCedulaTotalGPSBPLS(); u_ComputePenaltySurchargeGPSBPLS(); break;
		case "T2": computeTotalAssessment(); break;
		
	}
}

function onTableBeforeDeleteRowGPSBPLS(table,row) {
	return true;
}

function onTableDeleteRowGPSBPLS(table,row) {
	switch (table) {
		case "T1": 
                        var     rc = getTableRowCount("T2");
                        for (xxx = 1; xxx <=rc; xxx++) {
                                if (isTableRowDeleted("T2",xxx)==false) {
                                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode") && getTableInput("T2","u_businessline",xxx) == getTableInput(table,"u_businessline",row) && getTableInput("T2","u_year",xxx) == getTableInput(table,"u_year",row) ) {
                                            deleteTableRow("T2",xxx);
                                        }
                                }
                        }

                    setBusinessLineFees(); 
                    computeAnnualTax(); 
                    computeCedulaTotalGPSBPLS();
                    u_ComputePenaltySurchargeGPSBPLS();
                        
                    break;
		case "T2": computeTotalAssessment();  break;
		
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
		case "T2":
                        focusTableInput(table,"u_amount");
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
	if (isInputEmpty("u_apptype")) return false;
	if (isInputEmpty("u_assdate")) return false;
	if (isInputEmpty("u_businessname")) return false;
	

	setInput("u_preassbill","1");
	formSubmit();
	
}
function u_forAssessmentGPSBPLS() {
	if (isInputEmpty("u_apptype")) return false;
	if (isInputEmpty("u_assdate")) return false;
	if (isInputEmpty("u_businessname")) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;

	/*if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		return false;
	}*/
	setInput("docstatus","Assessing");
	formSubmit('sc');
}

function u_forApprovalGPSBPLS() {
	if (isInputEmpty("u_apptype")) return false;
	if (isInputEmpty("u_assdate")) return false;
	if (isInputEmpty("u_businessname")) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		return false;
	}
	if (getTableRowCount("T2")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Assessed");
	formSubmit('sc');
}

function u_forApproveGPSBPLS() {
	if (isInputEmpty("u_apptype")) return false;
	if (isInputEmpty("u_assdate")) return false;
	if (isInputEmpty("u_businessname")) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		return false;
	}
	if (getTableRowCount("T2")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function u_approveGPSBPLS() {
	if (isInputEmpty("u_apptype")) return false;
	if (isInputEmpty("u_assdate")) return false;
	if (isInputEmpty("u_businessname")) return false;
	if (isInputEmpty("u_paymode")) return false;
        if (isInputNegative("u_empcount")) return false;
//	if (isInputEmpty("u_reqappno")) return false;
//        if (isInputEmpty("u_businesschar")) return false;
        if(getInput("u_apptype")=="NEW") if (isInputNegative("u_capital",null,null,"tab1",1)) return false;
        if(getInput("u_apptype")=="RENEW" ) if (isInputNegative("u_nonessential",null,null,"tab1",1)) return false;
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
	
	if (getTableRowCount("T1")==0) {
		page.statusbar.showError("At least one line of business must be entered.");
		return false;
	}
	if (getTableRowCount("T2")==0) {
		page.statusbar.showError("At least one fee or charges must be entered.");
		return false;
	}
        if (getInput("u_reqappno")!="") {
            var result = page.executeFormattedQuery("select u_acctno from u_bldgapps where docno ='"+getInput("u_reqappno")+"' ");
                if (parseInt(result.getAttribute("result"))>0) {
                    for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                        if (result.childNodes.item(0).getAttribute("u_acctno") != "" && result.childNodes.item(0).getAttribute("u_acctno") != getInput("u_acctno")) {
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
                        if (result.childNodes.item(0).getAttribute("u_acctno") != "" &&  result.childNodes.item(0).getAttribute("u_acctno") != getInput("u_acctno")) {
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


function u_reassessGPSBPLS() {
	if (isInputEmpty("u_approverremarks",null,null,"tab1",3)) return false;
	setInput("docstatus","Encoding");
	formSubmit('sc');
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
        
        totalbrgygross = capitalbrgy + essentialbrgy + nonessentialbrgy;
        if (totalbrgygross > 0) {
            if( totalbrgygross <= 100000){
                brgyclearanceamount = 200;
            }else if( totalbrgygross > 100000 ){
                brgyclearanceamount = 500;
            }
            
            var rc = getTableRowCount("T2"),bgrytaxcoderc=0;
            for (xxx = 1; xxx <=rc; xxx++) {
                if (isTableRowDeleted("T2",xxx)==false) {
                    if (getTableInput("T2","u_feecode",xxx)=="0488"  && getTableInput("T2","u_year",xxx) == getInput("u_year")) {
                            setTableInputAmount("T2","u_amount",brgyclearanceamount,xxx);
                            bgrytaxcoderc=xxx;
                    }
                }
            }
            if (bgrytaxcoderc==0 && getInput("u_apptype") != "ADJUSTMENT") {
                    var data = new Array();
                    data["u_year"] = getInput("u_year");
                    data["u_feecode"] = "0488";
                    data["u_feedesc"] = "Barangay Clearance for Business";
                    data["u_common"] = 1;
                    data["u_amount"] = formatNumericAmount(brgyclearanceamount);
                    data["u_surcharge"] = formatNumericAmount(0);
                    data["u_interest"] = formatNumericAmount(0);
                    data["u_regulatory"] = 0;
                    insertTableRowFromArray("T2",data);
            }
        }
       
       
        
	setAnnualTax();
}

function setAnnualTax() {
	var annualtaxcoderc=0, electricalcertrc = 0, capital = 0, essential = 0, nonessential = 0, grosssales = 0, baseperc=100, taxamount=0, taxrate=0, excessamount = 0, fromamount = 0;
        var capitalamount = 0,annualtaxamount = 0;
	grosssales = getInputNumeric("u_essential")+getInputNumeric("u_nonessential");
        
        
        //compute total business tax
	var rc2 = getTableRowCount("T1"),btaxamount=0,surcharge=0,interest=0,taxbase=0;
        var rc = getTableRowCount("T2");
        
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                        annualtaxcoderc = 0;
			btaxamount = getTableInputNumeric("T1","u_btaxlinetotal",i);             
			surcharge = getTableInputNumeric("T1","u_surcharge",i);             
			interest = getTableInputNumeric("T1","u_interest",i);     
			taxbase = getTableInputNumeric("T1","u_capital",i) + getTableInputNumeric("T1","u_nonessential",i);     
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T2",xxx)==false) {
                                if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode") && getTableInput("T2","u_businessline",xxx) == getTableInput("T1","u_businessline",i) && getTableInput("T2","u_year",xxx) == getTableInput("T1","u_year",i)) {
                                        setTableInputAmount("T2","u_amount",btaxamount,xxx);
                                        setTableInputAmount("T2","u_surcharge",surcharge,xxx);
                                        setTableInputAmount("T2","u_interest",interest,xxx);
                                        setTableInputAmount("T2","u_taxbase",taxbase,xxx);
                                        setTableInput("T2","u_businessline",getTableInput("T1","u_businessline",i),xxx);
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
                                insertTableRowFromArray("T2",data);
                        }
		}
	}
        
	computeTotalAssessment();
	
}


function setBusinessTaxes() {
	var mayorspermitrc=0, sanitaryinspectionfeerc=0,environmentalfeerc = 0,rc = getTableRowCount("T2");
	
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T2",xxx)==false) {
				if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
						if (getPrivate("bplkindcharlink")=="1") {
							if (getInputNumeric("u_businesskindmayorstax")>0) setTableInputAmount("T2","u_amount",getInputNumeric("u_businesskindmayorstax"),xxx);
							else setTableInputAmount("T2","u_amount",getInputNumeric("u_businesscharmayorstax"),xxx);
						}else {
							setTableInputAmount("T2","u_amount",getInputNumeric("u_businesskindmayorstax"),xxx);
						}
					mayorspermitrc=xxx;
				}
				if (getTableInput("T2","u_feecode",xxx)==getPrivate("garbagefeecode")) {
					if (getTableInput("T2","u_year",xxx)>getInput("lastpayyear")) {
						if (getPrivate("bplkindcharlink")=="1") {
							if (getInputNumeric("u_businesskindenvironmenttax")>0) setTableInputAmount("T2","u_amount",getInputNumeric("u_businesskindenvironmenttax"),xxx);
							else setTableInputAmount("T2","u_amount",getInputNumeric("u_businesscharenvironmenttax"),xxx);
						}else {
							setTableInputAmount("T2","u_amount",getInputNumeric("u_businesskindenvironmenttax"),xxx);
						}
					} 
					environmentalfeerc=xxx;
				}
				if (getTableInput("T2","u_feecode",xxx)==getPrivate("sanitaryinspectionfeecode")) {
					if (getPrivate("bplkindcharlink")=="1") {
						if (getInputNumeric("u_businesskindtax")>0) setTableInputAmount("T2","u_amount",getInputNumeric("u_businesskindtax"),xxx);
						else setTableInputAmount("T2","u_amount",getInputNumeric("u_businesschartax"),xxx);
					} else {
						setTableInputAmount("T2","u_amount",getInputNumeric("u_businesskindtax"),xxx);
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
		insertTableRowFromArray("T2",data);
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
		insertTableRowFromArray("T2",data);
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
		insertTableRowFromArray("T2",data);
	}
	u_ComputePenaltySurchargeGPSBPLS();
	computeTotalAssessment();
}

function setAppTypeTaxes() {
	var rc = getTableRowCount("T2");
	var platefeerc = 0,tcrc = 0;
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_feecode",xxx)==getPrivate("platefeecode")) {
				if (getInput("u_apptype")=="NEW") {
					setTableInputAmount("T2","u_amount",getInputNumeric("u_platefee"),xxx);
				} else {
					setTableInputAmount("T2","u_amount",250,xxx);
				}
				platefeerc=xxx;
			}
//                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("tcfeecode")) {
//				if (getInput("u_apptype")=="RENEW") {
//					setTableInputAmount("T2","u_amount",100,xxx);
//				} else {
//					setTableInputAmount("T2","u_amount",0,xxx);
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
		insertTableRowFromArray("T2",data);
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
//		insertTableRowFromArray("T2",data);
//	}
}

function setRequirementsTaxes(table,row) {
	var rc = getTableRowCount("T2");
	var feerc = 0;
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_feecode",xxx)==getTableInput(table,"u_feecode",row)) {
				if (getTableInputNumeric(table,"u_check",row)==1) {
					setTableInputAmount("T2","u_amount",getTableInputNumeric(table,"u_chkfee",row),xxx);
				} else {
					setTableInputAmount("T2","u_amount",getTableInputNumeric(table,"u_uchkfee",row),xxx);
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
		insertTableRowFromArray("T2",data);
	}
}

function setAdditionalRequirementsTaxes() {
/*	var rc2 = getTableRowCount("T2");
	for (yyy = 1; yyy <=rc2; yyy++) {
		if (isTableRowDeleted("T2",yyy)==false) {
			if (getTableInput("T2","u_feecode",yyy)!="") {
				var rc = getTableRowCount("T2");
				var feerc = 0;
				for (xxx = 1; xxx <=rc; xxx++) {
					if (isTableRowDeleted("T2",xxx)==false) {
						if (getTableInput("T2","u_feecode",xxx)==getTableInput("T2","u_feecode",yyy)) {
							if (getTableInputNumeric("T2","u_check",yyy)==1)
								setTableInputAmount("T2","u_amount",getTableInputNumeric("T2","u_chkfee",yyy),xxx);
							} else {
								setTableInputAmount("T2","u_amount",getTableInputNumeric("T2","u_uchkfee",yyy),xxx);
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
					insertTableRowFromArray("T2",data);
				}
				
			}
		}
	}
	*/
}

function setCategoryTaxes() {
	var sanitarypermitrc=0, fireinspectionfeerc=0,rc = getTableRowCount("T2");
	if (getPrivate("bplcategsanitarypermitlink")=="1" || getPrivate("bplcategfireinsfeelink")=="1") {
		for (xxx = 1; xxx <=rc; xxx++) {
			if (isTableRowDeleted("T2",xxx)==false) {
				if (getPrivate("bplcategsanitarypermitlink")=="1") {
					if (getTableInput("T2","u_feecode",xxx)==getPrivate("sanitarypermitcode")) {
						setTableInputAmount("T2","u_amount",getInputNumeric("u_sanitarypermitfee"),xxx);
						sanitarypermitrc=xxx;
					}
				}	
				if (getPrivate("bplcategfireinsfeelink")=="1") {
					if (getTableInput("T2","u_feecode",xxx)==getPrivate("fireinspectionfeecode")) {
						setTableInputAmount("T2","u_amount",getInputNumeric("u_fireinspectionfee"),xxx);
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
				insertTableRowFromArray("T2",data);
			}
		}	
		if (getPrivate("bplcategfireinsfeelink")=="1") {
			if (fireinspectionfeerc==0) {
				var data = new Array();
				data["u_feecode"] = getPrivate("fireinspectionfeecode");
				data["u_feedesc"] = getPrivate("fireinspectionfeedesc");
				data["u_common"] = 1;
				data["u_amount"] = formatNumericAmount(getInputNumeric("u_fireinspectionfee"));
				insertTableRowFromArray("T2",data);
			}
		}	
		computeTotalAssessment();
	}	
}

function setEmployeeTaxes() {
	var pforc=0, sfherc=0,rc = getTableRowCount("T2");
        
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_feecode",xxx)==getPrivate("pfofeecode")) {
				setTableInputAmount("T2","u_amount",getInputNumeric("u_pfofee")*(getInputNumeric("u_empcount")),xxx);
				pforc=xxx;
			}
//			if (getTableInput("T2","u_feecode",xxx)==getPrivate("sfhefeecode")) {
//				setTableInputAmount("T2","u_amount",getInputNumeric("u_sfhefee")*(getInputNumeric("u_empcount")+getInputNumeric("u_emplgucount")),xxx);
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
		insertTableRowFromArray("T2",data);
	}
//	if (sfherc==0) {
//		var data = new Array();
//		data["u_feecode"] = getPrivate("sfhefeecode");
//		data["u_feedesc"] = getPrivate("sfhefeedesc");
//		data["u_common"] = 1;
//		data["u_amount"] = formatNumericAmount(getInputNumeric("u_sfhefee")*(getInputNumeric("u_empcount")+getInputNumeric("u_emplgucount")));
//		insertTableRowFromArray("T2",data);
//	}
	computeTotalAssessment();
}
	
function setBarangayFees() {
	var garbagefeecoderc=0, rc = getTableRowCount("T2");
	for (xxx = 1; xxx <=rc; xxx++) {
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_feecode",xxx)==getPrivate("garbagefeecode")) {
				if (getInput("u_bunittype")=="Commercial") {
					//setTableInputAmount("T2","u_amount",getInputNumeric("u_garbagefeec"),xxx);
				} else {
					//setTableInputAmount("T2","u_amount",getInputNumeric("u_garbagefeer"),xxx);
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
		insertTableRowFromArray("T2",data);
	}
	computeTotalAssessment();
}

function setBusinessLineFees() {
	var rc =  getTableRowCount("T2");
	for (xxx = rc; xxx >=1; xxx--) {
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_common",xxx)=="2") {
				deleteTableRow("T2",xxx);
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
				insertTableRowFromArray("T2",data);
			}
		}		
	} else {
		page.statusbar.showError("Error retrieving additional requirements. Try Again, if problem persists, check the connection.");	
		return false;
	}
	computeTotalAssessment();
}

function computeTotalAssessment() {
	var rc = getTableRowCount("T2"),total=0,totalpenalty = 0 ,totalinterest = 0 ,btax=0,btax1=0,taxrc=0, firefee = 0, firetotalamount = 0,firegrandtotal = 0;
	var rc2 = getTableRowCount("T3"),totalcredit=0,totalpenaltycredit = 0 ,totalinterestcredit = 0 ,btaxcredit=0;
        var fireinspectionfeerc = 0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
                        if(getTableInput("T2","u_regulatory",i) == 1 && getTableInput("T2","u_year",i) == getInput("u_year")){
                                //firefee+= getTableInputNumeric("T2","u_amount",i) + getTableInputNumeric("T2","u_surcharge",i) + getTableInputNumeric("T2","u_interest",i);
                                firefee+= getTableInputNumeric("T2","u_amount",i);
                        }
		}
	}
        if (firefee > 0) {
                firetotalamount  = (firefee) *.15;
                if (firetotalamount < 500)  firetotalamount = 500;
                if (getPrivate("incfirebusiness")=="1") {
                     for (xxx = 1; xxx <=rc; xxx++) {
                        if (isTableRowDeleted("T2",xxx)==false) {
                            if (getTableInput("T2","u_feecode",xxx)==getPrivate("fireinspectionfeecode") && getTableInput("T2","u_year",xxx) >= getPrivate("incfirestartyear")) {
                                if (getTableInput("T2","u_year",xxx) >= getPrivate("incfirestartyear")) setTableInputAmount("T2","u_amount",firetotalamount,xxx);
                                else setTableInputAmount("T2","u_amount",0,xxx);
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
                            insertTableRowFromArray("T2",data);
                    }
                }
        }
        
       
        for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			total+= getTableInputNumeric("T2","u_amount",i);
			totalpenalty+= getTableInputNumeric("T2","u_surcharge",i);
			totalinterest+= getTableInputNumeric("T2","u_interest",i);
			if (getTableInput("T2","u_feecode",i)==getPrivate("annualtaxcode")) {
				btax+= getTableInputNumeric("T2","u_amount",i);
			}
			if (getTableInput("T2","u_feecode",i)==getPrivate("fireinspectionfeecode")) {
				firegrandtotal+= getTableInputNumeric("T2","u_amount",i);
			}
		}
	}
        for (xxx = 1; xxx <= rc2; xxx++) {
		if (isTableRowDeleted("T3",xxx)==false) {
                    if (getTableInputNumeric("T3","u_selected",xxx)=="1") {
                        totalcredit+= getTableInputNumeric("T3","u_amount",xxx);
			totalpenaltycredit+= getTableInputNumeric("T3","u_surcharge",xxx);
			totalinterestcredit+= getTableInputNumeric("T3","u_interest",xxx);
			if (getTableInput("T3","u_feecode",xxx)==getPrivate("annualtaxcode")) {
				btaxcredit+= getTableInputNumeric("T3","u_amount",xxx);
			}
                    }
		}
	}

	setInputAmount("u_fireasstotal",firegrandtotal);
	setInputAmount("u_btaxamount",btax - btaxcredit);
	setInputAmount("u_asstotal",total + totalinterest + totalpenalty - (totalcredit + totalinterestcredit + totalpenaltycredit));
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
        OpenPopup(1280,600,"./udp.php?&objectcode=u_bplbusinessledger&df_refno2="+getInput("u_acctno")+"","Business Ledger");
}
function u_OLDPaymentHistoryGPSBPLS() {
        OpenPopup(1280,600,"./udp.php?&objectcode=u_bplbusinessledgerold2&df_refno2="+getInput("u_acctno")+"","Old Business Ledger");
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
	var feecoderc = 0, rc = getTableRowCount("T2"), feeamt = 0;
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
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_feecode",xxx)=="0055") {
			      setTableInputAmount("T2","u_amount",feeamt,xxx);
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
		insertTableRowFromArray("T2",data);
	}
	
	computeTotalAssessment();
}

function setWeightsandMeasureFees() {
	//alert(getInput("u_weightstax"));
	var feecoderc = 0, rc = getTableRowCount("T2"), feeamt = 0;
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
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_feecode",xxx)=="0369") {
			      setTableInputAmount("T2","u_amount",feeamt,xxx);
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
		insertTableRowFromArray("T2",data);
	}
	
	computeTotalAssessment();
}


function u_ComputePenaltySurchargeGPSBPLS() {
        var penaltyfeecoderc = 0, surchargefeecoderc = 0, rc = getTableRowCount("T2"), feeamt = 0,btaxamount = 0, totalamount = 0, mayorsfeeamount = 0;
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
		if (isTableRowDeleted("T2",xxx)==false) {
                    var duedate1 = formatDateToDB("01/20/" + getTableInput("T2","u_year",xxx)).replace(/-/g,"");
                    var duedate2 = formatDateToDB("04/20/" + getTableInput("T2","u_year",xxx)).replace(/-/g,"");
                    var duedate3 = formatDateToDB("07/20/" + getTableInput("T2","u_year",xxx)).replace(/-/g,"");
                    var duedate4 = formatDateToDB("10/20/" + getTableInput("T2","u_year",xxx)).replace(/-/g,"");
//                    if (getInput("u_decisiondate") != "")  var paydate =  formatDateToDB(getInput("u_decisiondate")).replace(/-/g,"");
//                    else  
                    var paydate =  formatDateToDB(getInput("u_assdate")).replace(/-/g,"");
                    var duemonth1 = parseInt(duedate1.substr(0,6));
                    var duemonth2 = parseInt(duedate2.substr(0,6));
                    var duemonth3 = parseInt(duedate3.substr(0,6));
                    var duemonth4 = parseInt(duedate4.substr(0,6));
                    var paymonth = parseInt(paydate.substr(0,6));
                    var payday = parseInt(paydate.substr(6,2));
                    
                    //due to covid adjusted due date is implemented and also less 12 % for the interest
                    var adjduedate = formatDateToDB("06/30/2021").replace(/-/g,"");
                  
                    
                    
                    if (getInput("u_apptype") != "RENEW") return true;
                        penaltyperc = 0.25;
                        var dueyear = getTableInput("T2","u_year",xxx);
                        var intpercvalue=0;
                    
                    //Annually Payment and Previous Years Tax
                    if (getInput("u_paymode") == "A" || payyear < dueyear) {
                        if (paydate>duedate1) {
                            //Mayor's Permit Tax
                            if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
                            
                            //Business Tax 
                            if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode")) {
                                
                                if (payyear > dueyear) {
                                    intpercvalue = ((payyear-dueyear) * 12  ) - (parseInt(duedate1.substr(4,2)) - parseInt(paydate.substr(4,2)));
                                } else { 
                                    intpercvalue = paymonth-duemonth1;
                                }
                                
                                if (paymonth>=duemonth1 && payday > 20) intperc = (.02*(intpercvalue + 1));
                                else intperc = (.02*(intpercvalue));
                                
                                if (getInput("lastpayyear") == getTableInput("T2","u_year",xxx)) {
                                    switch (getInput("lastpayqtr")) {
                                        case "1":
                                            intperc = intperc - .06; //for average computation. value = .12
                                            break;
                                        case "2":
                                            intperc = intperc - .12; //for average computation. value = .15
                                            break;
                                        case "3":
                                            intperc = intperc - .18;
                                            break;
                                    }
                                }
                                
                                if (intperc > .72) intperc  = .72;
                                
                                setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
                                setTableInputAmount("T2","u_interest",getTableInputNumeric("T2","u_amount",xxx) * intperc,xxx);
                            }
                        } else {
                            setTableInputAmount("T2","u_surcharge",0,xxx);
                            setTableInputAmount("T2","u_interest",0,xxx);
                        }
                        
                    } else if (getInput("u_paymode") == "S" && payyear == dueyear) {
                        //Mayor's Permit Tax
                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
                        
                        //Business Tax     
                        if (getTableInput("T2","u_feecode",xxx)!=getPrivate("annualtaxcode")) return true;
                        
                        if (paydate>duedate3 ) {
                            
                            intpercvalue = paymonth-duemonth3;
                            
                            if (paymonth>=duemonth3 && payday > 20) intperc = (.02*(intpercvalue + 1));
                            else intperc = (.02*(intpercvalue));
                                
                            if (intperc > .72) intperc  = .72;
                            
                            setTableInputAmount("T2","u_surcharge",(getTableInputNumeric("T2","u_amount",xxx) * .5) * penaltyperc,xxx);
                            setTableInputAmount("T2","u_interest",(getTableInputNumeric("T2","u_amount",xxx) * .5 ) * intperc,xxx);
                            
                        } else {
                            setTableInputAmount("T2","u_surcharge",0,xxx);
                            setTableInputAmount("T2","u_interest",0,xxx);
                        }
                        
                        
                    } else if (getInput("u_paymode") == "Q" && payyear == dueyear) {
                         //Mayor's Permit Tax
                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
                         
                        //Business Tax 
                        if (getTableInput("T2","u_feecode",xxx)!=getPrivate("annualtaxcode")) return true;  
                        
                         if (paydate>duedate1 && paydate <= duedate2) {
                            intpercvalue = paymonth-duemonth1;
                            
                            if (paymonth>=duemonth1 && payday > 20) intperc = (.02*(intpercvalue + 1));
                            else intperc = (.02*(intpercvalue));
                                
                            if (intperc > .72) intperc  = .72;
                            
                            setTableInputAmount("T2","u_surcharge",(getTableInputNumeric("T2","u_amount",xxx) * .25) * penaltyperc,xxx);
                            setTableInputAmount("T2","u_interest",(getTableInputNumeric("T2","u_amount",xxx) * .25 ) * intperc,xxx);
                            
                         } else if (paydate>duedate2 && paydate <= duedate3 ) {
                            intpercvalue = paymonth-duemonth2;
                            
                            if (paymonth>=duemonth2 && payday > 20) intperc = (.02*(intpercvalue + 1));
                            else intperc = (.02*(intpercvalue));
                                
                            if (intperc > .72) intperc  = .72;
                            
                            setTableInputAmount("T2","u_surcharge",(getTableInputNumeric("T2","u_amount",xxx) * .5) * penaltyperc,xxx);
                            setTableInputAmount("T2","u_interest",(getTableInputNumeric("T2","u_amount",xxx) * .5 ) * intperc,xxx);
                            
                        } else  if (paydate>duedate3 && paydate <= duedate4 ) {
                            intpercvalue = paymonth-duemonth3;
                            
                            if (paymonth>=duemonth3 && payday > 20) intperc = (.02*(intpercvalue + 1));
                            else intperc = (.02*(intpercvalue));
                                
                            if (intperc > .72) intperc  = .72;
                            
                            setTableInputAmount("T2","u_surcharge",(getTableInputNumeric("T2","u_amount",xxx) * .75) * penaltyperc,xxx);
                            setTableInputAmount("T2","u_interest",(getTableInputNumeric("T2","u_amount",xxx) * .75 ) * intperc,xxx);
                            
                        } else  if (paydate>duedate4 ) {
                            intpercvalue = paymonth-duemonth4;
                            
                            if (paymonth>=duemonth4 && payday > 20) intperc = (.02*(intpercvalue + 1));
                            else intperc = (.02*(intpercvalue));
                                
                            if (intperc > .72) intperc  = .72;
                            
                            setTableInputAmount("T2","u_surcharge",(getTableInputNumeric("T2","u_amount",xxx)) * penaltyperc,xxx);
                            setTableInputAmount("T2","u_interest",(getTableInputNumeric("T2","u_amount",xxx)) * intperc,xxx);
                            
                        } else {
                            setTableInputAmount("T2","u_surcharge",0,xxx);
                            setTableInputAmount("T2","u_interest",0,xxx);
                        }
                    }
                    
//                    if (paydate>duedate1 && paydate>adjduedate && getInput("u_apptype") == "RENEW") {
//                        
//                        penaltyperc = 0.25;
//                        if(paymonth>duemonth1) intperc= (.02*(paymonth-duemonth1));
//                        penaltyamtbtax = btaxamount * penaltyperc;
//                        surchargeamtbtax = btaxamount * intperc ;
//                        
//                        //For bacoor 1st quarter no penalty discount
//                        if ((getInput("u_year") == getTableInput("T2","u_year",xxx)) && parseInt(paydate.substr(4,2)) <= 3) {
//                             penaltyperc = 0;
//                        }
//                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
//                                setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
//                                penaltyfeecoderc=xxx;
//                        }
//                        
//                            if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode")) {
//                                var dueyear = getTableInput("T2","u_year",xxx);
//                                var intpercvalue=0;
//                                if (payyear > dueyear) {
//                                    intpercvalue = ((payyear-dueyear) * 12  ) - (parseInt(duedate1.substr(4,2)) - parseInt(paydate.substr(4,2)));
//                                } else { 
//                                    intpercvalue = paymonth-duemonth1;
//                                }
//                                if (paymonth>duemonth1) intperc = (.02*(intpercvalue + 1));
//                                if (getInput("lastpayyear") == getTableInput("T2","u_year",xxx)) {
//                                    switch (getInput("lastpayqtr")) {
////                                        case "1":
////                                            intperc = intperc - .12;
////                                            break;
////                                        case "2":
////                                            intperc = intperc - .15;
////                                            break;
////                                        case "3":
////                                            intperc = intperc - .18;
////                                            break;
//                                    }
//                                }
//                                
////                                alert(intperc);
////                                
//                                //Bacoor memorandum, because of covid all surcharge less 12%
////                                if (intperc >= .12) intperc = intperc - .12;
////                                else intperc = 0;
//                                
//                                //For bacoor 1st quarter no penalty discount
//                                if ((getInput("u_year") == getTableInput("T2","u_year",xxx)) && parseInt(paydate.substr(4,2)) <= 3) {
//                                       intperc = 0;
//                                }
//                                if (intperc > .72) intperc  = .72;
//                                setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
//                                setTableInputAmount("T2","u_interest",getTableInputNumeric("T2","u_amount",xxx) * intperc,xxx);
//                                penaltyfeecoderc=xxx;
//                        }
//                        
//                    } else {
//                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
//                            setTableInputAmount("T2","u_surcharge",0,xxx);
//                        }
//                        if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode")) {
//                            setTableInputAmount("T2","u_surcharge",0,xxx);
//                            setTableInputAmount("T2","u_interest",0,xxx);
//                        }
//                        
//                    }
//			if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
//			      mayorsfeeamount += getTableInputNumeric("T2","u_amount",xxx);
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
//                      if (isTableRowDeleted("T2",xxx)==false) {
//                                if (getTableInput("T2","u_feecode",xxx)==getPrivate("annualtaxcode")) {
//                                    var dueyear = getTableInput("T2","u_year",xxx);
//                                    var intpercvalue=0;
//                                    if (payyear > dueyear) {
//                                        intpercvalue = ((payyear-dueyear) * 12  ) - (parseInt(duedate1.substr(4,2)) - parseInt(paydate.substr(4,2)));
//                                    } else { 
//                                        intpercvalue = paymonth-duemonth1;
//                                    }
//                                    if(paymonth>duemonth1) intperc = (.02*(intpercvalue));
//                                    setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
//                                    setTableInputAmount("T2","u_interest",getTableInputNumeric("T2","u_amount",xxx) * intperc,xxx);
//                                    penaltyfeecoderc=xxx;
//                                }
//                                if (getTableInput("T2","u_feecode",xxx)==getPrivate("mayorspermitcode")) {
//                                      setTableInputAmount("T2","u_surcharge",getTableInputNumeric("T2","u_amount",xxx) * penaltyperc,xxx);
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
    if (getInput("u_apptype")!= "RENEW") page.statusbar.showWarning("New/Adjustment application is not allowed for this action"); 
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
            amount = 10;
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
        var duedate = getInput("u_year") + "0120";
//        if (getInput("u_decisiondate") != "")  var paydate =  formatDateToDB(getInput("u_decisiondate")).replace(/-/g,"");
//        else  
            var paydate =  formatDateToDB(getInput("u_assdate")).replace(/-/g,"");
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
            if (amount < 10)
                amount = 10;
        } else {
            if (amount < 500)
                amount = 500;
        }
    }
    
    if (getInput("u_apptype") == "ADJUSTMENT")  amount = 0;

    setInputAmount("u_ctcasstotal", amount);
}

function u_cashierGPSRPTAS() {
        OpenPopup(1280,700,"./udo.php?&objectcode=u_lgupos","Payment");
}

function setAdjustmentMiscFees() {
	var rc =  getTableRowCount("T2");
	for (xxx = rc; xxx >=1; xxx--) {
		if (isTableRowDeleted("T2",xxx)==false) {
			if (getTableInput("T2","u_common",xxx)=="6") {
				deleteTableRow("T2",xxx);
			}
		}
	}        
        for (yyy = getInputNumeric("u_assyearto"); yyy <=getInputNumeric("u_year"); yyy++) {
            var result = page.executeFormattedQuery("select code, name, u_amount,u_regulatory,u_seqno from u_bpladjustfees  order by u_seqno asc");
                if (result.getAttribute("result")!= "-1") {
                    if (parseInt(result.getAttribute("result"))>0) {
                        for (xxx1 = 0; xxx1 < result.childNodes.length; xxx1++) {
                            var feectr = 0;
                            for (xxx2 = 1; xxx2 <=rc; xxx2++) {
                                if (isTableRowDeleted("T2",xxx2)==false) {
                                    if(getTableInput("T2","u_year",xxx2) == yyy && getTableInput("T2","u_feecode",xxx2) == result.childNodes.item(xxx1).getAttribute("code")) {
                                        feectr = xxx2;
                                    } 
                                }
                            }
                            if (feectr == 0) {
                                var data = new Array();
                                data["u_year"] = yyy;
                                data["u_feecode"] = result.childNodes.item(xxx1).getAttribute("code");
                                data["u_feedesc"] = result.childNodes.item(xxx1).getAttribute("name");
                                data["u_common"] = 6;
                                data["u_amount"] = formatNumericAmount(result.childNodes.item(xxx1).getAttribute("u_amount"));
                                data["u_surcharge"] = formatNumericAmount(0);
                                data["u_interest"] = formatNumericAmount(0);
                                data["u_regulatory"] = result.childNodes.item(xxx1).getAttribute("u_regulatory");
                                insertTableRowFromArray("T2",data);
                            }
                        }
                    }		
                } else {
                    page.statusbar.showError("Error retrieving misc fees. Try Again, if problem persists, check the connection.");	
                    return false;
                }
        }
	
	computeTotalAssessment();
}