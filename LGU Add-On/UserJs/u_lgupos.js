// page events
page.events.add.load('onPageLoadGPSPOS');
//page.events.add.resize('onPageResizeGPSPOS');
page.events.add.submit('onPageSubmitGPSPOS');
page.events.add.submitreturn('onPageSubmitReturnGPSPOS');
//page.events.add.cfl('onCFLGPSPOS');
//page.events.add.cflgetparams('onCFLGetParamsGPSPOS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSPOS');

// page reports
page.events.add.report('onReportGPSPOS'); 

// element events
//page.elements.events.add.focus('onElementFocusGPSPOS');
page.elements.events.add.keydown('onElementKeyDownGPSPOS');
page.elements.events.add.validate('onElementValidateGPSPOS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSPOS');
//page.elements.events.add.changing('onElementChangingGPSPOS');
page.elements.events.add.change('onElementChangeGPSPOS');
page.elements.events.add.click('onElementClickGPSPOS');
//page.elements.events.add.cfl('onElementCFLGPSPOS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSPOS');

// table events
page.tables.events.add.reset('onTableResetRowGPSPOS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSPOS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSPOS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSPOS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSPOS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSPOS');
page.tables.events.add.delete('onTableDeleteRowGPSPOS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSPOS');
page.tables.events.add.select('onTableSelectRowGPSPOS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSPOS');

function onReportGPSPOS(p_formattype) {
        if (getInput("u_status")=="C") {
            page.statusbar.showWarning("Register currently closed or already remitted.");
            return false;
        }
        return true;
}

function onPageLoadGPSPOS() {
      
        
	setInputAmount("totalamountdisplay",getInputNumeric("u_totalamount"));	
	//setInputAmount("totalamountpaymentdisplay",getInputNumeric("u_totalamount"));	
	//ajaxloadhousebankaccounts("df_u_tfbankacctno",getGlobal("branch"),"PH",getInput("u_tfbank"),'',":");
//	u_computeTotalGPSPOS(false);
	if (getVar("formSubmitAction")=="a" && getInput("docseries")=="-1") {
		enableInput("docno");
		enableInput("u_date");
	}
	if (getVar("formSubmitAction")=="a") {
                //u_ajaxloadu_lguposterminalseries("df_u_docseries",getInput("u_terminalid"),'',":",getInput("u_profitcenter"));
		if (getPrivate("billtopay")!="" && getPrivate("billtopay")!="pf_billtopay") {
			setInput("u_billno",getPrivate("billtopay"),true);
		} else {
			try {
				if (window.opener.getVar("objectcode")=="U_BPLTAXBILL") {
                                    setInput("u_custno",window.opener.getInput("u_acctno"));
                                    OpenPopup(900,350,"./udp.php?&objectcode=u_unpaybilllist","List of Unpaid Bills");
                                }
				if (window.opener.getVar("objectcode")=="U_RPTAXES") setInput("u_billno",window.opener.getInput("docno"),true);
				if (window.opener.getVar("objectcode")=="U_RPTAXBILL") setInput("u_billno",window.opener.getInput("docno"),true);
			} catch (theError) {
			}
		}
	}
	
	setCaption("totalquantity",getInput("u_totalquantity"));
	setT1FocusInputGPSPOS();
	if (getInput("u_trxtype")=="R") {
		setCaption("trxtype","TOTAL SALES RETURN");
		setCaption("u_collectedcashamount","Amount Returned");
	}
}

function onPageResizeGPSPOS(width,height) {
}

function onPageSubmitGPSPOS(action) {
        if (action=="sc") { 
                page.statusbar.showWarning("Document is not allowed to be updated.");
		return false;
        } else if (action=="a" ) {
		if (getInput("u_status")!="CN") {
			if (getInput("u_status")=="D" && getInput("u_profitcenter")!="RP") {
				page.statusbar.showWarning("Only Real Property is allowed to save as draft.");
				return false;
			}
			var tcount = 0;
			var rc =  getTableRowCount("T1");
			var packageitems = 0;
			for (iii = 1; iii <= rc; iii++) {
				if (isTableRowDeleted("T1",iii)==false) {
                                        tcount += 1;
					if (getTableInput("T1","u_selected",iii)=="1" && getTableInputNumeric("T1","u_itemmanageby",iii)==2 && getTableInput("T1","u_serialno",iii)=="" && getTableInput("T1","u_tofollow",iii)=="0") {
						page.statusbar.showError("Serial No. is required for item ["+getTableInput("T1","u_itemdesc",iii)+"]");
						selectTableRow("T1",iii);
						setT1FocusInputGPSPOS();
						return false;
					}
				}
				if (getTableInputNumeric("T1","u_package",iii)==1) packageitems ++;
			}
			
			if (packageitems>0 || getInputNumeric("u_noofpkgs")>0) {
				if (isInputNegative("u_noofpkgs")) return false;
				if (packageitems==0) {
					page.statusbar.showWarning("One or more items must be tag as package if No of Packages was entered.");
					return false;
				}
			}
			
			if (!isInputChecked("u_ar")) {
				if (getInputNumeric("u_partialpay")==0) {
					if (getInputNumeric("u_dueamount")!=0) {
						showPopupFrame('popupFramePayments',true);
						focusInput("u_collectedcashamount");
						page.statusbar.showWarning("Payments and Total Amount not tally.");
						return false;
					} 
				} else {
					if (window.confirm("Partial Payment will be posted. Continue?")==false) return false
				}
			}
                        
                        if (!isInputChecked("u_ismanualposting")) {
                             if (isInputEmpty("u_docseries")) return false;
                        }
			
			if (getInputNumeric("u_tfamt")!=0) {
				if (isInputEmpty("u_tfrefno")) return false;
				if (isInputEmpty("u_tfbank")) return false;
				if (isInputEmpty("u_tfbankacctno")) return false;
			}
			
			hidePopupFrame('popupFramePayments');
			
			/*if (getInputHandle("u_salespersonname").style.display!="none") {
				if (getInput("u_orno")=="") {
					if (window.confirm("O.R. No. was not entered. Continue?")==false) {
						focusInput("u_orno");
						return false;
					}
				}
			}	*/
			
			if (isInputNegative("u_totalamount")) return false;
			if (isInputEmpty("u_date")) return false;
//			if (isInputEmpty("u_submitdate")) return false;
			if (isInputEmpty("u_custno")) return false;
			if (isInputEmpty("u_custname")) return false;
			
			if (getInput("u_module")=="Public Market Rental") {
				if (isInputEmpty("u_collector")) return false;
			}
			/*if (getInputHandle("u_salespersonname").style.display!="none") {
				if (isInputEmpty("u_salespersonname")) return false;
			}*/
			
			if (getTableInput("T1","u_itemcode")!="") {
				setStatusMsg("An Item is being added/edited.");
				setT1FocusInputGPSPOS();
				return false;
			}
			if (getTableRowCount("T1")==0 || tcount == 0) {
				setStatusMsg("An Item is required.");
				setT1FocusInputGPSPOS();
				return false;
			}
                        
                        for (iii = 1; iii <= rc; iii++) {
				if (isTableRowDeleted("T1",iii)==false) {
					if (getTableInput("T1","u_itemcode",iii)=="0478" && getInput("u_barangay") == "") {
						page.statusbar.showError("Barangay is required for item ["+getTableInput("T1","u_itemdesc",iii)+"]");
						focusInput("u_barangay");
						return false;
					}
				}
			}
			//if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
		
		}
	} else if (action=="cnd") {
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
				return false;
			}
			if (getTableInput("T51","cancelreason")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","cancelreason");
				return false;
			}
			if (getTableInput("T51","remarks")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","remarks");
				return false;
			}
                        if (getInput("u_status")=="C") {
                            page.statusbar.showWarning("Invalid status["+getInput("u_status")+"]. Register currently closed or already remitted. ");
                            return false;
                        }
		}
	}
	return true;
}

function onPageSubmitReturnGPSPOS(action,sucess,error) {
	if (action=="a" && sucess) {
		OpenReportSelect('printer');
                focusInput("u_address");
	}
}

function onCFLGPSPOS(Id) {
	return true;
}

function onCFLGetParamsGPSPOS(Id,params) {
	return params;
}

function onTaskBarLoadGPSPOS() {
}

function onElementFocusGPSPOS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSPOS(element,event,column,table,row) {
	var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
	switch (sc_press) {
		case "ESC":
			if (isPopupFrameOpen("popupFramePayments")) {
				hidePopupFrame('popupFramePayments');
				setT1FocusInputGPSPOS();
			}
			break;
		case "ENTER":
                        if(column=="u_ctcgross") {
                            setCTCFees();
                        }
			break;
		case "F1":
			popupCommunitaxCertificate();
			break;
		case "F2":
			CopyFromCategories();
			break;
		case "F4":
			u_paymentGPSPOS();
			break;
		case "F6":
			u_returnGPSPOS();
			break;
		case "F7":
			u_salesGPSPOS();
			break;
		case "F9":
			formSubmit();
			break;
                case "CTRL+ENTER":
			formSubmit("?");
			break;
		default:
			switch (table) {
				case "T1":
					break;
				default:	
					switch (column) {
						case "u_totalamount2":
							if (sc_press=="ENTER") {
								setInputAmount(column,getInputNumeric(column),true);
								focusInput(column);
							}
							break;
							
					}
					break;
			}
			break;
	}
}

function onElementValidateGPSPOS(element,column,table,row) {
	var result,result2,payments=0,balance=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_barcode":
					if (getTableInput(table,column)!="") {
						result = ajaxxmlvalidateitembarcodes(element.value);
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setStatusMsg('Invalid Barcode!');
							return false;
						}	
						setTableInput(table,"u_barcode",result.childNodes.item(0).getAttribute("barcode"));
						setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("itemcode"));
						setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("itemdesc"));
						
						result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"PRICELIST:{BC}");
						setTableInputAmount(table,"u_srp",formatNumber(result.getAttribute("unitprice"),'0',''));
						setTableInput(table,"u_unitprice",result.getAttribute("unitprice"));
						setTableInput(table,"u_price",result.getAttribute("price"));
						setTableInput(table,"u_discperc",result.getAttribute("discperc"));
						setTableInput(table,"u_discamount",result.getAttribute("discamount"));
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
						//focusTableInput(table,"u_quantity",1500);
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_itemcode":
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") var result = page.executeFormattedQuery("select code, name, u_penalty, u_amount from u_lgufees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_penalty, u_amount from u_lgufees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_penalty",result.childNodes.item(0).getAttribute("u_penalty"));
								setTableInputQuantity(table,"u_quantity",1);
								setTableInputAmount(table,"u_unitprice",result.childNodes.item(0).getAttribute("u_amount"));
								setTableInputAmount(table,"u_price",result.childNodes.item(0).getAttribute("u_amount"));
								setTableInputPercent(table,"u_discperc",result.getAttribute("discperc"));
								setTableInputAmount(table,"u_discamount",result.getAttribute("discamount"));
								setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
								setTableInput(table,"u_itemmanageby",'0');
							} else {
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_itemdesc","");
								setTableInput(table,"u_penalty",0);
								setTableInputAmount(table,"u_unitprice",0);
								setTableInputAmount(table,"u_price",0);
								setTableInputAmount(table,"u_linetotal",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setTableInput(table,"u_penalty",0);
							setTableInputAmount(table,"u_unitprice",0);
							setTableInputAmount(table,"u_price",0);
							setTableInputAmount(table,"u_linetotal",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
						/*
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							u_displayPhotoGPSPOS(0);
							setStatusMsg('Invalid Item Code!');
							return false;
						}	
						setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("itemcode"));
						setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("itemdesc"));
						if (getInput("u_trxtype")=="S") {
							setTableInputQuantity(table,"u_quantity",1);
							if (getTableInput(table,"u_serialno")!="" && result.childNodes.item(0).getAttribute("serialstatus")=="1") {
								setTableInputQuantity(table,"u_quantity",-1);
							}
						} else {
							setTableInputQuantity(table,"u_quantity",-1);
							if (getTableInput(table,"u_serialno")!="" && result.childNodes.item(0).getAttribute("serialstatus")=="0") {
								resetTableRow(table);
								page.statusbar.showError("You cannot return Serial which is available in-stock.");
								return false;
							}
						}
						setTableInput(table,"u_vatcode",result.childNodes.item(0).getAttribute("taxcodesa"));
						setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("uomsa"));
						setTableInput(table,"u_numperuom",result.childNodes.item(0).getAttribute("numperuomsa"));
						setTableInputAmount(table,"u_itemcost",formatNumber(result.childNodes.item(0).getAttribute("avgcost"),'0',''));
						
						
						result2 = ajaxxmlvalidatetaxes(getTableInput(table,"u_vatcode"),"DOCDATE:" + getInput("u_date"));
						if (result2.getAttribute("result") == '0') setTableInputPercent(table,"u_vatrate",0);
						else setTableInputPercent(table,"u_vatrate",result2.childNodes.item(0).getAttribute("taxrate"));
						
						result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"PRICELIST:{BC}");
						setTableInputAmount(table,"u_srp",formatNumber(result.getAttribute("unitprice"),'0',''));
						setTableInput(table,"u_unitprice",result.getAttribute("unitprice"));
						setTableInput(table,"u_price",result.getAttribute("price"));
						setTableInput(table,"u_discperc",result.getAttribute("discperc"));
						setTableInput(table,"u_discamount",result.getAttribute("discamount"));
						setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
						
						if (getTableInput("T1","u_serialno")=="") focusTableInput(table,"u_quantity");
						else {
							disableTableInput("T1","u_quantity");
							focusTableInput(table,"u_discperc");
						}
						
						u_displayPhotoGPSPOS(0);
						*/
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
						setTableInput(table,"u_penalty",0);
						setTableInputAmount(table,"u_unitprice",0);
						setTableInputAmount(table,"u_price",0);
						setTableInputAmount(table,"u_linetotal",0);
						//u_displayPhotoGPSPOS(0);
					}
					break;
				case "u_quantity":
					if (getInput("u_trxtype")=="R") {
						setTableInputQuantity(table,"u_quantity",Math.abs(getTableInputNumeric(table,"u_quantity"))*-1);
					}
					u_computeT1LineTotalGPSPOS(column,element.value); 				
					break;					
				case "u_unitprice":
                                        if (getTableInput("T1","u_itemcode")=="0580") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0581") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0582") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0583") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0584") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0585") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0586") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0587") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        if  (getTableInput("T1","u_itemcode")=="0588") {page.statusbar.showWarning("Unit price for this item ['"+getTableInput("T1","u_itemdesc")+"'] cannot be modify. Please use quantity instead"); setTableInput("T1","u_unitprice", getTableInput("T1","u_linetotal"));}
                                        u_computeT1LineTotalGPSPOS(column,element.value); 	
					break;
				case "u_price":
				case "u_discperc":
				case "u_discamount":
				case "u_linetotal":
					u_computeT1LineTotalGPSPOS(column,element.value); 				
					break;
			}
			break;
		case "T5":
			switch (column) {
				case "u_amount":
					if (getTableInputNumeric(table,"u_amount",row)>getTableInputNumeric(table,"u_balanceamount",row)) {
						page.statusbar.showWarning("Applied amount cannot be more than balance.");
						setTableInputAmount(table,"u_amount",getTableInputNumeric(table,"u_balanceamount",row),row);
					}
					u_computeT5GPSPOS();
					break;
			}
			break;
                case "T6":
                    switch (column){
                        case "u_billno":
                                if (getTableInput(table,column)!="") {
                                        result = page.executeFormattedQuery("select D.U_INTEREST,A.U_CUSTNO,A.U_REMARKS,A.U_DUEAMOUNT, A.U_CUSTNAME, A.U_PROFITCENTER, A.U_MODULE, A.U_PAYMODE, A.U_DOCDATE, A.U_DUEDATE, B.U_ITEMCODE, B.U_ITEMDESC, B.U_AMOUNT-B.U_SETTLEDAMOUNT AS U_AMOUNT, D.U_PENALTYCODE, D.U_PENALTYDESC, A.U_ALERTMOBILENO from U_LGUBILLS A INNER JOIN U_LGUBILLITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND B.U_AMOUNT>B.U_SETTLEDAMOUNT INNER JOIN U_LGUPROFITCENTERS C ON C.CODE=A.U_PROFITCENTER INNER JOIN U_LGUFEES D ON D.CODE=B.U_ITEMCODE where A.DOCSTATUS NOT IN('CN') AND A.COMPANY='"+getGlobal("company")+"' AND A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO = '"+getTableInput(table,column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        if (parseFloat(result.childNodes.item(0).getAttribute("u_dueamount"))==0) {
                                                                page.statusbar.showError("Bill already settled.");	
                                                                return false;
                                                        }
                                                        setTableInput(table,"u_remarks",result.childNodes.item(0).getAttribute("u_remarks"));
                                                        setTableInput(table,"u_amount",formatNumericAmount(result.childNodes.item(0).getAttribute("u_dueamount")));
                                                       
                                                } else {
                                                        setTableInput(table,"u_remarks","");
                                                        setTableInput(table,"u_amount","");
                                                        page.statusbar.showError("Invalid Bill No.");	
                                                        return false;
                                                }
                                        } else {
                                                setTableInput(table,"u_remarks","");
                                                setTableInput(table,"u_amount","");
                                                page.statusbar.showError("Error retrieving bill record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setTableInput(table,"u_remarks","");
                                        setTableInput(table,"u_amount","");
                                }
                                break;
                    }
                    break;
		case "T101":
			switch (column) {
				case "u_barcode":
					if (getTableInput(table,column)!="") {
						result = ajaxxmlvalidateitembarcodes(element.value);
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setStatusMsg('Invalid Barcode!');
							return false;
						}	
						setTableInput(table,"u_barcode",result.childNodes.item(0).getAttribute("barcode"));
						setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("itemcode"));
						setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("itemdesc"));
						
						result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"PRICELIST:{BC}");
						setTableInputAmount(table,"u_srp",formatNumber(result.getAttribute("unitprice"),'0',''));
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_itemcode":
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") result = ajaxxmlvalidateitems(element.value,"CHECKSERIALNO");
						else result = ajaxxmlvalidateitems(element.value,"VALIDATECOLUMN:ITEMDESC;CHECKSERIALNO");
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setStatusMsg('Invalid Item Code!');
							return false;
						}	
						setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("itemcode"));
						setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("itemdesc"));
						setTableInput(table,"u_serialno",result.childNodes.item(0).getAttribute("serialno"));
						setTableInput(table,"u_itemclass",result.childNodes.item(0).getAttribute("itemclass"));
						
						setTableInput(table,"u_itemmanageby",result.childNodes.item(0).getAttribute("manageby"));
						setTableInput(table,"u_vatcode",result.childNodes.item(0).getAttribute("taxcodesa"));
						setTableInput(table,"u_uom",result.childNodes.item(0).getAttribute("uomsa"));
						setTableInput(table,"u_numperuom",result.childNodes.item(0).getAttribute("numperuomsa"));
						
						result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode"),"PRICELIST:{BC}");
						setTableInputAmount(table,"u_srp",formatNumber(result.getAttribute("unitprice"),'0',''));
						
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
			}
			break;			
		default:
			switch (column) {
                                case "u_custname":
                                     setInput("u_custname",getInput("u_custname").toUpperCase());
                                break;
				case "u_custno":
					if (isInput("u_custno")!="") {
						result = ajaxxmlvalidatebusinesspartners(element.value,"");	
						if (result.getAttribute("result") == '0') {
							setInput("u_custno","");
							setInput("u_custname","");
							setStatusMsg('Invalid Customer No.!');
							return false;
						}
						setInput("u_custno",result.childNodes.item(0).getAttribute("bpcode"));
						setInput("u_custname",result.childNodes.item(0).getAttribute("bpname"));
					} else {
						setInput("u_custno","");
						setInput("u_custname","");
					}
					break;
				case "u_collectedcashamount":
					if (getInput("u_trxtype")=="R") {
						setInputAmount("u_collectedcashamount",Math.abs(getInputNumeric("u_collectedcashamount"))*-1);
						setInputAmount("u_cashamount",getInputNumeric("u_collectedcashamount"));
						setInputAmount("u_changecashamount",getInputNumeric("u_totalamount")-(getInputNumeric("u_collectedcashamount")));
												
						setInputAmount("u_paidamount",getInputNumeric("u_cashamount"));
						setInputAmount("u_dueamount",getInputNumeric("u_totalamount") - getInputNumeric("u_paidamount"));
					} else {			
						if (getInputNumeric("u_totalamount")<0) {
							page.statusbar.showError("No Cash Payment needed.");	
							setInputAmount("u_collectedcashamount",0);
						}
						u_computePaymentGPSPOS();
					}
					break;
				case "u_tfamt":
					setInputAmount("u_tfamount",getInputNumeric("u_tfamt"));
					u_computePaymentGPSPOS();
					break;
				case "u_salespersonname":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select salespersonname, salesperson from salespersons where u_branch='"+getGlobal("branch")+"' and salespersonname like '"+getInput(column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_salespersonname",result.childNodes.item(0).getAttribute("salespersonname"));
								setInput("u_salesperson",result.childNodes.item(0).getAttribute("salesperson"));
							} else {
								setInput("u_salespersonname","");
								setInput("u_salesperson","");
								page.statusbar.showError("Invalid Sales Person");	
								return false;
							}
						} else {
							setInput("u_salespersonname","");
							setInput("u_salesperson","");
							page.statusbar.showError("Error retrieving salesperson record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_salespersonname","");
						setInput("u_salesperson","");
					}
					break;
				case "u_billno":
					if (getInput(column)!="") {
                                            if (getTableRowCount("T6",true)>0) {
                                                page.statusbar.showError("Multiple Bill no. exists!");
                                                selectTab("tab1",1);
                                                return false;
                                            }
						result = page.executeFormattedQuery("select docno,u_duedate,u_module from u_lgubills where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
                                                                if (result.childNodes.item(0).getAttribute("u_module") == "Real Property Tax Bill") { 
                                                                    if ( formatDateToDB(getInput("u_date")) <= result.childNodes.item(0).getAttribute("u_duedate")) { 
                                                                            setInput("u_billno",result.childNodes.item(0).getAttribute("docno"));
                                                                            setBillNosLGUPOS();
                                                                            u_setBillItemsGPSLGUPOS();
                                                                    } else {
                                                                            setInput("u_billno","");
                                                                            setBillNosLGUPOS();
                                                                            u_setBillItemsGPSLGUPOS();
                                                                            page.statusbar.showError("Invalid Bill No.");	
                                                                            return false;
                                                                    }
                                                                } else {
                                                                    setInput("u_billno",result.childNodes.item(0).getAttribute("docno"));
                                                                    setBillNosLGUPOS();
                                                                    u_setBillItemsGPSLGUPOS();
                                                                }
							} else {
								setInput("u_billno","");
								setBillNosLGUPOS();
								u_setBillItemsGPSLGUPOS();
								page.statusbar.showError("Invalid Bill No.");	
								return false;
							}
						} else {
							setInput("u_billno","");
							setBillNosLGUPOS();
							u_setBillItemsGPSLGUPOS();
							page.statusbar.showError("Error retrieving bill record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_billno","");
						setBillNosLGUPOS();
						u_setBillItemsGPSLGUPOS();
					}
					break;
                                        
                                case "u_cashierapptype":
					if (getInput(column)!="") {
                                                if (getInput("u_billno") != "") {
                                                    page.statusbar.showWarning("You cannot select application type. Bill number exist");	
                                                    return false;
                                                }
						//alert("select A.U_CUSTNO, A.U_CUSTNAME, A.U_PROFITCENTER, C.U_MODULE, A.U_DOCDATE, A.U_DUEDATE, B.U_ITEMCODE, B.U_ITEMDESC, B.U_AMOUNT, D.U_PENALTYCODE, D.U_PENALTYDESC from U_LGUBILLS A INNER JOIN U_LGUBILLITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID INNER JOIN U_LGUPROFITCENTERS C ON C.CODE=A.U_PROFITCENTER INNER JOIN U_LGUFEES D ON D.CODE=B.U_ITEMCODE where A.COMPANY='"+getGlobal("company")+"' AND A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO = '"+getInput(column)+"'");
						result = page.executeFormattedQuery("SELECT  B.U_ITEMCODE AS U_ITEMCODE, B.NAME AS U_ITEMDESC, B.U_AMOUNT FROM u_lgugroupset A INNER JOIN u_feessets B ON A.CODE = B.U_APPTYPE INNER JOIN U_LGUFEES D ON D.CODE=B.U_ITEMCODE  where B.U_APPTYPE = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								
                                                                clearTable("T1",true);
                                                                var penaltyperc=0;
								var intperc=0;
								//var penaltyamount=0;
								var data = new Array();
								var duedate = formatDateToDB(getInput("u_billduedate")).replace(/-/g,"");
								var paydate = formatDateToDB(getInput("u_date")).replace(/-/g,"");
								var duemonth = parseInt(duedate.substr(0,6));
								var paymonth = parseInt(paydate.substr(0,6));
                                                                
								for (var iii=0; iii<result.childNodes.length; iii++) {
									data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
									data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
									data["u_quantity"] = formatNumericQuantity(1);
									data["u_numperuom"] = formatNumericQuantity(1);
									data["u_unitprice"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
									data["u_price"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
									data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
									data["u_penalty"] = 0;
										insertTableRowFromArray("T1",data);
								}
								u_computeTotalGPSPOS();
								
							} else {
								
								page.statusbar.showError("Invalid Apptype.");	
								return false;
							}
						} else {
							
							page.statusbar.showError("Error retrieving Application Type. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						
                                        }
                                        break;
				case "u_totalamount2":
					u_computeTotalDiscountGPSPOS();
					break;
                                        
			}
			break;
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSPOS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSPOS(element,column,table,row) {
	return true;
}

function onElementChangeGPSPOS(element,column,table,row) {
	switch (table) {
		default:
			switch (column) {
				case "u_docseries":
                                    if (getInput(column)!="") {
                                        getDocnoPerSeriesGPSPOS(getInput(column));
                                    }
                                break;
				case "u_bplsseries":
                                    setDocNo(true,"u_bplsseries","u_bplapprefno","u_date");
                                break;
				case "u_tfbank":
					ajaxloadhousebankaccounts("df_u_tfbankacctno",getGlobal("branch"),"PH",getInput("u_tfbank"),'',":");
					setInput("u_tfbankbranch","");
					break;
				case"u_bankacctno":
					result = ajaxxmlgethousebankaccountbranch(getGlobal("branch"),"PH",getInput("u_tfbank"),getInput("u_tfbankacctno"));
					setInput("u_tfbankbranch",result.getAttribute("u_tfbankbranch"));
					break;
				case"u_profitcenter":
//					u_ajaxloadu_lguposterminalseries("df_u_docseries",getPrivate("userid"),'',":",getInput("u_profitcenter"));
//                                        getDocnoPerSeriesGPSPOS(getInput("u_docseries"));
//					setInput("u_tfbankbranch",result.getAttribute("u_tfbankbranch"));
					break;
			}
			break;
	}
		
	return true;
}

function onElementClickGPSPOS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_selected":
				case "u_freebie":
					u_computeTotalGPSPOS();
					break;
			}
			break;
		case "T5":
			switch (column) {
				case "u_selected":
					if (isTableInputChecked(table,column,row)) {
						enableTableInput(table,"u_amount",row);
						focusTableInput(table,"u_amount",row);
					} else {
						disableTableInput(table,"u_amount",row);
						setTableInputAmount(table,"u_amount",0,row);
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_ismanualposting":
                                     if (isInputChecked("u_ismanualposting")) {
                                            setInput("u_docseries","");
                                            disableInput("u_docseries");
                                            setInput("docno","");
                                            setInput("docseries","-1");
                                            enableInput("docno");
                                            focusInput("docno");
                                     } else {
                                            enableInput("u_docseries");
                                            setInput("docno","");
                                            disableInput("docno");
                                            focusInput("u_docseries");
                                     }
                                break;
				case "u_exactamount":
                                        if (isInputChecked("u_exactamount")) {
                                            setInputAmount("u_collectedcashamount",getInputNumeric("u_totalamount"));
                                        } else {
                                             setInputAmount("u_collectedcashamount",0);
                                        }
                                        u_computePaymentGPSPOS();
                                break;
				case "u_ar":
					document.getElementById("divCash").style.display = "none";
					document.getElementById("divCheck").style.display = "none";
					document.getElementById("divCreditCard").style.display = "none";
					document.getElementById("divOther").style.display = "none";
					document.getElementById("divBankTransfer").style.display = "none";
					document.getElementById("divDP").style.display = "none";
					if (isInputChecked("u_ar")) {
						setInput("paymenttype","A/R");
						setInputAmount("u_aramount",getInputNumeric("u_totalamount"));
					} else {
						setInputAmount("u_aramount",0);
					}
					break;
				case "paymenttype":
					if (isInputChecked("u_ar")) {
						page.statusbar.showWarning("A/R is checked, cannot accept other payment.");			   
						return false;
					}
					document.getElementById("divCash").style.display = "none";
					document.getElementById("divCheck").style.display = "none";
					document.getElementById("divCreditCard").style.display = "none";
					document.getElementById("divOther").style.display = "none";
					document.getElementById("divBankTransfer").style.display = "none";
					document.getElementById("divDP").style.display = "none";
					switch (getInput("paymenttype")) {
						case "Cash":
							document.getElementById("divCash").style.display = "block";
							focusInput("u_collectedcashamount");
							break;
						case "Check":
							document.getElementById("divCheck").style.display = "block";
							focusTableInput("T2","u_checkdate");
							break;
						case "CreditCard":
							document.getElementById("divCreditCard").style.display = "block";
							focusTableInput("T2","u_creditcard");
							break;
						case "Other":
							document.getElementById("divOther").style.display = "block";
							//if (getVar) setTableInput("T4","u_cashcard","0006");
							focusTableInput("T4","u_amount");
							break;
						case "BankTransfer":
							document.getElementById("divBankTransfer").style.display = "block";
							focusInput("u_tfrefno");
							break;
						case "Downpayment":
							document.getElementById("divDP").style.display = "block";
							focusTableInput("T5","u_amount",1);
							break;
					}
			}
			break;
	}
	return true;
}

function onElementCFLGPSPOS(element) {
	return true;
}

function onElementCFLGetParamsGPSPOS(element,params) {
	var params = new Array();
	switch (element) {	
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_salespersonname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select salespersonname, salesperson from salespersons where u_branch='"+getGlobal("branch")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_billno":
//			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT DOCNO,U_DOCDATE,U_DUEDATE,U_CUSTNAME,U_DUEAMOUNT,U_REMARKS FROM (select A.docno, A.u_docdate, A.u_duedate, if(A.u_profitcenter = 'BPL',if(E.u_orgtype = 'Single',CONCAT(E.U_LASTNAME,', ',E.U_FIRSTNAME),A.U_CUSTNAME),A.U_CUSTNAME) as U_CUSTNAME, A.u_dueamount, A.u_remarks from u_lgubills A  LEFT JOIN  U_BPLAPPS E ON A.U_APPNO = E.DOCNO AND A.COMPANY = E.COMPANY AND A.BRANCH = E.BRANCH where A.DOCSTATUS NOT IN('CN') AND A.company='lgu' and A.branch='main' and A.u_dueamount>0) as x WHERE TRUE  ")); 
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT DOCNO,U_DOCDATE,U_DUEDATE,U_CUSTNAME,U_DUEAMOUNT,U_REMARKS FROM u_lgubills  where DOCSTATUS NOT IN('CN') AND company='lgu' and branch='main' and u_dueamount>0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Due Date`Customer Name`Due Amount`Remarks")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("7`8`8`30`7`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`date``amount`")); 			
			break;
		case "df_u_billnoT6":
//			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT DOCNO,U_DOCDATE,U_DUEDATE,U_CUSTNAME,U_DUEAMOUNT,U_REMARKS FROM (select A.docno, A.u_docdate, A.u_duedate, if(A.u_profitcenter = 'BPL',if(E.u_orgtype = 'Single',CONCAT(E.U_LASTNAME,', ',E.U_FIRSTNAME),A.U_CUSTNAME),A.U_CUSTNAME) as U_CUSTNAME, A.u_dueamount, A.u_remarks from u_lgubills A  LEFT JOIN  U_BPLAPPS E ON A.U_APPNO = E.DOCNO AND A.COMPANY = E.COMPANY AND A.BRANCH = E.BRANCH where A.DOCSTATUS NOT IN('CN') AND A.company='lgu' and A.branch='main' and A.u_dueamount>0) as x WHERE TRUE  ")); 
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT DOCNO,U_DOCDATE,U_DUEDATE,U_CUSTNAME,U_DUEAMOUNT,U_REMARKS FROM u_lgubills  where DOCSTATUS NOT IN('CN') AND company='lgu' and branch='main' and u_dueamount>0")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Due Date`Customer Name`Due Amount`Remarks")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("7`8`8`30`7`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date`date``amount`")); 			
			break;
                        
	}
	return params;
}

function onTableResetRowGPSPOS(table) {
	switch (table) {
		case "T1":
			u_displayPhotoGPSPOS(0);
			enableTableInput("T1","u_quantity");
			setT1FocusInputGPSPOS();
			break;
		case "T101":
			setT101FocusInputGPSPOS();
			break;
	}
}

function onTableBeforeInsertRowGPSPOS(table) {
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_serialno")!="") {
				var rc =  getTableRowCount("T1");
				for (i = 1; i <= rc; i++) {
					if (isTableRowDeleted("T1",i)==false) {
						if ((getTableInput("T1","u_itemcode",i)==getTableInput(table,"u_itemcode") || (getTableInput("T1","u_itemclass",i)==getTableInput(table,"u_itemclass") && getTableInput(table,"u_itemclass")!="")) && getTableInput("T1","u_serialno",i)=="") {
							setTableInput("T1","u_itemcode",getTableInput(table,"u_itemcode"),i);
							setTableInput("T1","u_itemdesc",getTableInput(table,"u_itemdesc"),i);
							setTableInput("T1","u_uom",getTableInput(table,"u_uom"),i);
							setTableInput("T1","u_itemmanageby",getTableInput(table,"u_itemmanageby"),i);
							setTableInput("T1","u_serialno",getTableInput(table,"u_serialno"),i);
							resetTableRow(table);
							return false;
						}
						if (getTableInput("T1","u_itemcode",i)==getTableInput(table,"u_itemcode") && getTableInput("T1","u_serialno",i)==getTableInput(table,"u_serialno")) {
							page.statusbar.showError("Serial No. already selected.");
							resetTableRow(table);
							return false;
						}
						
					}
				}
			}
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputZero(table,"u_quantity")) return false;
			if (getTableInputNumeric("T1","u_price")==0 && !isTableInputChecked("T1","u_freebie")) {
				//if (window.confirm("Item Price is zero. Continue?")==false) {
					page.statusbar.showError("Unit Price is required.");
					focusTableInput(table,"u_unitprice");
					//setT1FocusInputGPSPOS();
					return false;
				//}
			}
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_checkdate")) return false;
			if (isTableInputEmpty(table,"u_bank")) return false;
			if (isTableInputEmpty(table,"u_checkno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_creditcard")) return false;
			if (isTableInputEmpty(table,"u_creditcardno")) return false;
			if (isTableInputEmpty(table,"u_creditcardname")) return false;
			if (isTableInputEmpty(table,"u_expiredate")) return false;
			if (isTableInputEmpty(table,"u_approvalno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T4":
			if (isTableInputEmpty(table,"u_cashcard")) return false;
			if (isTableInputEmpty(table,"u_refno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T6":
			if (isTableInputEmpty(table,"u_billno")) return false;
			break;
		case "T101":
			if (getTableInput(table,"u_serialno")!="") {
				var rc =  getTableRowCount("T101");
				for (i = 1; i <= rc; i++) {
					if (isTableRowDeleted("T101",i)==false) {
						if ((getTableInput("T101","u_itemcode",i)==getTableInput(table,"u_itemcode") || (getTableInput("T101","u_itemclass",i)==getTableInput(table,"u_itemclass") && getTableInput(table,"u_itemclass")!="")) && getTableInput("T101","u_serialno",i)=="") {
							setTableInput("T101","u_itemcode",getTableInput(table,"u_itemcode"),i);
							setTableInput("T101","u_itemdesc",getTableInput(table,"u_itemdesc"),i);
							setTableInput("T101","u_uom",getTableInput(table,"u_uom"),i);
							setTableInput("T101","u_itemmanageby",getTableInput(table,"u_itemmanageby"),i);
							setTableInput("T101","u_serialno",getTableInput(table,"u_serialno"),i);
							resetTableRow(table);
							return false;
						}
						if (getTableInput("T101","u_itemcode",i)==getTableInput(table,"u_itemcode") && getTableInput("T101","u_serialno",i)==getTableInput(table,"u_serialno")) {
							page.statusbar.showError("Serial No. already selected.");
							resetTableRow(table);
							return false;
						}
						
					}
				}
			}
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			break;			
	}
	return true;
}

function onTableAfterInsertRowGPSPOS(table,row) {
	var result, data = new Array();	
	switch (table) {
		case "T1":
//			if (getTableInput(table,"u_freebie",row)=="0") {
//				disableTableInput(table,"u_tofollow",row);
//				result = page.executeFormattedQuery("select a.docno, a.u_freebielimit, b.u_itemcode, b.u_itemdesc, b.u_itemclass, b.u_uom, d.manageby, b.u_quantity from u_pospromos a left join u_pospromoitems b on b.docid=a.docid left join items d on d.itemcode=b.u_itemcode inner join u_pospromobranches c on c.docid=a.docid and c.u_branchcode='"+getGlobal("branch")+"' where a.u_itemcode='"+getTableInput(table,"u_itemcode",row)+"' and '"+formatDateToDB(getInput("u_date"))+"'>=a.u_datefrom and '"+formatDateToDB(getInput("u_date"))+"'<=a.u_dateto and a.docstatus='O'");
//				if (result.getAttribute("result")!="-1") {
//					if (result.childNodes.length==0 && getTableInput(table,"u_itemclass",row)!="") {
//						result = page.executeFormattedQuery("select a.docno, a.u_freebielimit, b.u_itemcode, b.u_itemdesc, b.u_itemclass, b.u_uom, d.manageby, b.u_quantity from u_pospromos a left join u_pospromoitems b on b.docid=a.docid left join items d on d.itemcode=b.u_itemcode inner join u_pospromobranches c on c.docid=a.docid and c.u_branchcode='"+getGlobal("branch")+"' where a.u_itemclass='"+getTableInput(table,"u_itemclass",row)+"' and '"+formatDateToDB(getInput("u_date"))+"'>=a.u_datefrom and '"+formatDateToDB(getInput("u_date"))+"'<=a.u_dateto and a.docstatus='O'");
//					}
//				}
//				if (result.getAttribute("result")!= "-1") {
//					var cnt = result.childNodes.length;
//					if (cnt>0) {
//						setTableInput(table,"u_promocode",result.childNodes.item(0).getAttribute("docno"),row);
//						setTableInputAmount(table,"u_freebielimit",result.childNodes.item(0).getAttribute("u_freebielimit"),row);
//						if (getTableInputNumeric(table,"u_freebielimit",row)>0) {
//							setTableInput("T100","u_itemcode",getTableInput(table,"u_itemcode",row));
//							setTableInput("T100","u_itemdesc",getTableInput(table,"u_itemdesc",row));
//							setTableInput("T100","u_promocode",getTableInput(table,"u_promocode",row));
//							setTableInputAmount("T100","u_freebielimit",getTableInputNumeric(table,"u_freebielimit",row));
//							setTableInputAmount("T100","u_freebieamount",0)
//							setTableInput("T100","u_row",row);
//							showPopupFrame("popupFrameFreebies",true);
//							setT101FocusInputGPSPOS();
//						}
//					}
//					for (var iii=0; iii<cnt; iii++) {
//						var qty = parseInt(result.childNodes.item(iii).getAttribute("u_quantity"));
//						for (var xxx=0; xxx<qty; xxx++) {
//							data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
//							data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
//							data["u_itemclass"] = result.childNodes.item(iii).getAttribute("u_itemclass");
//							data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
//							data["u_itemmanageby"] = result.childNodes.item(iii).getAttribute("manageby");
//							data["u_vatcode"] = getTableInput(table,"u_vatcode");
//							data["u_vatrate"] = getTableInput(table,"u_vatrate");
//							data["u_promocode"] = result.childNodes.item(iii).getAttribute("docno");
//							data["u_quantity"] = formatNumericQuantity(1);
//							data["u_numperuom"] = formatNumericQuantity(1);
//							data["u_freebie"] = 1;
//							data["u_selected"] = 1;
//							insertTableRowFromArray("T1",data);
//							disableTableInput("T1","u_freebie",getTableRowCount("T1"));
//						}
//					}
//				}
//			}
			u_computeTotalGPSPOS();
			break;
		case "T2":
			u_computeT2GPSPOS();
			break;
		case "T3":
			u_computeT3GPSPOS();
			break;
		case "T4":
			u_computeT4GPSPOS();
			break;
		case "T6":
			setInput("u_billno","");
			setBillNosLGUPOS();
			u_setBillItemsGPSLGUPOS();
			break;
		case "T101":
			u_computeTotalFreebieGPSPOS();
			break;
	}
}

function onTableBeforeUpdateRowGPSPOS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			if (isTableInputZero(table,"u_quantity")) return false;
			if (!isTableInputChecked(table,"u_freebie")) {
				if (getTableInputNumeric("T1","u_price")==0) {
					if (window.confirm("Item Price is zero. Continue?")==false) {
						focusTableInput(table,"u_unitprice");
						//setT1FocusInputGPSPOS();
						return false;
					}
				}
			}
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_checkdate")) return false;
			if (isTableInputEmpty(table,"u_bank")) return false;
			if (isTableInputEmpty(table,"u_checkno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T3":
			if (isTableInputEmpty(table,"u_creditcard")) return false;
			if (isTableInputEmpty(table,"u_creditcardno")) return false;
			if (isTableInputEmpty(table,"u_creditcardname")) return false;
			if (isTableInputEmpty(table,"u_expiredate")) return false;
			if (isTableInputEmpty(table,"u_approvalno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
		case "T4":
			if (isTableInputEmpty(table,"u_cashcard")) return false;
			if (isTableInputEmpty(table,"u_refno")) return false;
			if (isTableInputZero(table,"u_amount")) return false;
			break;
                case "T6":
			if (isTableInputEmpty(table,"u_billno")) return false;
			break;
		case "T101":
			if (isTableInputEmpty(table,"u_itemcode")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSPOS(table,row) {
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_freebie",row)=="0") {
				disableTableInput(table,"u_tofollow",row);
			}
			u_computeTotalGPSPOS();
			break;
		case "T2":
			u_computeT2GPSPOS();
			break;
		case "T3":
			u_computeT3GPSPOS();
			break;
		case "T4":
			u_computeT4GPSPOS();
			break;
                case "T6":
                    setInput("u_billno","");
                    setBillNosLGUPOS();
                    u_setBillItemsGPSLGUPOS();
                    break;
		case "T101":
			u_computeTotalFreebieGPSPOS();
			break;
	}
}

function onTableBeforeDeleteRowGPSPOS(table,row) {
	return true;
}

function onTableDeleteRowGPSPOS(table,row) {
	switch (table) {
		case "T1":
			u_computeTotalGPSPOS();
			break;
		case "T2":
			u_computeT2GPSPOS();
			break;
		case "T3":
			u_computeT3GPSPOS();
			break;
		case "T4":
			u_computeT4GPSPOS();
			break;
                case "T6":
                    setInput("u_billno","");
                    setBillNosLGUPOS();
                    u_setBillItemsGPSLGUPOS();
                    break;
		case "T101":
			u_computeTotalFreebieGPSPOS();
			break;
	}
}

function onTableBeforeSelectRowGPSPOS(table,row) {
	return true;
}

function onTableSelectRowGPSPOS(table,row) {
	var imgpath = "", params = new Array();
	switch (table) {
		case "T1":
			u_displayPhotoGPSPOS(row);
                       
			break;
		case "T5":
			params["focus"] = false;
			break;
	}
	return params;
}

function onTableAfterEditRowGPSPOS(table,row) {
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_promocode")!="") {
				disableTableInput(table,"u_itemcode");
				disableTableInput(table,"u_itemdesc");
			}
			focusTableInput(table,"u_quantity");
			break;
	}
}

function u_computeT2GPSPOS(column,value,row) {
	var chequetotal=0;
	rc =  getTableRowCount("T2");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			chequetotal += getTableInputNumeric("T2","u_amount",i);
		}
	}
	setInputAmount("u_chequeamount",chequetotal);
	u_computePaymentGPSPOS();
}

function u_computeT3GPSPOS(column,value,row) {
	var creditcardtotal=0;
	rc =  getTableRowCount("T3");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T3",i)==false) {
			creditcardtotal += getTableInputNumeric("T3","u_amount",i);
		}
	}
	setInputAmount("u_creditcardamount",creditcardtotal);
	u_computePaymentGPSPOS();
}


function u_computeT4GPSPOS(column,value,row) {
	var othertotal=0;
	rc =  getTableRowCount("T4");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T4",i)==false) {
			othertotal += getTableInputNumeric("T4","u_amount",i);
		}
	}
	setInputAmount("u_otheramount",othertotal);
	u_computePaymentGPSPOS();
}

function u_computeT5GPSPOS() {
	var dptotal=0;
	rc =  getTableRowCount("T5");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T5",i)==false) {
			dptotal += getTableInputNumeric("T5","u_amount",i);
		}
	}
	setInputAmount("u_dpamount",dptotal);
	u_computePaymentGPSPOS();
}

function u_computePaymentGPSPOS() {
	var payments=0,balance=0;
	payments = getInputNumeric("u_chequeamount") + getInputNumeric("u_creditcardamount") + getInputNumeric("u_otheramount") + getInputNumeric("u_tfamount") + getInputNumeric("u_dpamount") + getInputNumeric("u_aramount");
	if (payments<getInputNumeric("u_totalamount")) {
		balance = getInputNumeric("u_totalamount") - payments;
		if (balance<=getInputNumeric("u_collectedcashamount")) {
			setInputAmount("u_cashamount",balance);
		} else setInputAmount("u_cashamount",getInputNumeric("u_collectedcashamount"));
	} else setInputAmount("u_cashamount",0);
	setInputAmount("u_changecashamount",getInputNumeric("u_totalamount")-(payments+getInputNumeric("u_collectedcashamount")));
	setInputAmount("u_paidamount",payments+getInputNumeric("u_cashamount"));
	setInputAmount("u_dueamount",getInputNumeric("u_totalamount") - getInputNumeric("u_paidamount"));
}

function u_computeTotalDiscountGPSPOS() {
	var rc =  getTableRowCount("T1"), totalamount = getInputNumeric("u_totalamount"), price = 0, totalamount2 = getInputNumeric("u_totalamount2"), linetotal=0;
	
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_selected",i)=="0") continue;
			
			if (getTableInput("T1","u_freebie",i)=="0") {
				price = utils.round(((getTableInputNumeric("T1","u_linetotal",i)/totalamount) * totalamount2) / getTableInputNumeric("T1","u_quantity",i),2);
				setTableInputPrice("T1","u_price",price,i);
				u_computeT1LineTotalGPSPOS("u_price",price,i);
				linetotal += getTableInputNumeric("T1","u_linetotal",i);
				if (i==rc && totalamount2!=linetotal) {
					setTableInputAmount("T1","u_linetotal",getTableInputNumeric("T1","u_linetotal",i)+(totalamount2-linetotal),i);
					u_computeT1LineTotalGPSPOS("u_linetotal",getTableInputNumeric("T1","u_linetotal",i),i);
				}
				//page.statusbar.showWarning(price);
			}
		}
	}
	u_computeTotalGPSPOS();
	hidePopupFrame('popupFrameTotalAmount');
}


function u_computeT1LineTotalGPSPOS(column,value,row) {
	var qty=0, unitprice=0, discount=0, discount2=0, discount3=0, discount4=0, discount5=0, price=0, discamount=0,vatamount=0,linetotal=0,vatrate=0;
//		if (row==null) row = ";
	if (column == "u_quantity") qty = value
	else qty = getTableInputNumeric("T1","u_quantity",row);
	if (column == "u_unitprice") unitprice = value
	else unitprice = getTableInputNumeric("T1","u_unitprice",row);
	if (column == "u_discperc") discount = value
	else discount = getTableInputNumeric("T1","u_discperc",row);
/*	if (column == "discperc2") discount2 = value
	else discount2 = getTableInputNumeric("T1","discperc2",row);
	if (column == "discperc3") discount3 = value
	else discount3 = getTableInputNumeric("T1","discperc3",row);
	if (column == "discperc4") discount4 = value
	else discount4 = getTableInputNumeric("T1","discperc4",row);
	if (column == "discperc5") discount5 = value
	else discount5 = getTableInputNumeric("T1","discperc5",row);*/
	if (column == "u_price") price = value
	else price = getTableInputNumeric("T1","u_price",row);
	if (column == "u_discamount") discamount = value
	else discamount = getTableInputNumeric("T1","u_discamount",row);
	switch (column) {
		case "u_quantity":
			discamount = unitprice * (discount/100);
			price = unitprice - discamount;
			setTableInputPrice("T1","u_price",price,row);
			setTableInputAmount("T1","u_discamount",discamount,row);
			break;
		case "u_unitprice":
			discamount = unitprice * (discount/100);
			price = unitprice - discamount;
			setTableInputPrice("T1","u_price",price,row);
			setTableInputAmount("T1","u_discamount",discamount,row);
			break;					
		case "u_discperc":
		case "u_discperc2":
		case "u_discperc3":
		case "u_discperc4":
		case "u_discperc5":
			price = unitprice * (1- (discount/100));
			price = price * (1- (discount2/100));
			price = price * (1- (discount3/100));
			price = price * (1- (discount4/100));
			price = price * (1- (discount5/100));
			discamount = unitprice - price;
			setTableInputPrice("T1","u_price",price,row);
			setTableInputAmount("T1","u_discamount",discamount,row);
			break;
		case "u_discamount":
			price = unitprice - discamount;
			discount = (1 - (price / unitprice)) * 100;
			setTableInputPrice("T1","u_price",price,row);
			setTableInputPercent("T1","u_discperc",discount,row);
			/*
			setTableInputPercent("T1","discperc2",0,row);
			setTableInputPercent("T1","u_discperc3",0,row);
			setTableInputPercent("T1","discperc4",0,row);
			setTableInputPercent("T1","discperc5",0,row);
			*/
			break;
		case "u_price":
			if (unitprice==0) {
				unitprice = getTableInputNumeric("T1","u_price",row);
				setTableInputPrice("T1","u_unitprice",unitprice,row);
			}
			discamount = unitprice - price;
			discount = (1 - (price / unitprice)) * 100;
			setTableInputPercent("T1","u_discperc",discount,row);
			/*
			setTableInputPercent("T1","discperc2",0,row);
			setTableInputPercent("T1","discperc3",0,row);
			setTableInputPercent("T1","discperc4",0,row);
			setTableInputPercent("T1","discperc5",0,row);
			*/
			setTableInputAmount("T1","u_discamount",discamount,row);
			break;
		case "u_linetotal":
			if (unitprice==0) {
				unitprice = getTableInputNumeric("T1","u_linetotal",row) / qty;
				setTableInputPrice("T1","u_unitprice",unitprice,row);
			}
			discamount = unitprice - (getTableInputNumeric("T1","u_linetotal",row) / qty);
			price = unitprice - discamount;
			discount = (1 - (price / unitprice)) * 100;
			setTableInputPrice("T1","u_price",price,row);
			setTableInputPercent("T1","u_discperc",discount,row);
			/*
			setTableInputPercent("T1","discperc2",0,row);
			setTableInputPercent("T1","discperc3",0,row);
			setTableInputPercent("T1","discperc4",0,row);
			setTableInputPercent("T1","discperc5",0,row);
			*/
			setTableInputAmount("T1","u_discamount",discamount,row);
			break;
	}
	linetotal = utils.multiply(qty,price);
	vatrate = getTableInputNumeric("T1","u_vatrate",row)/100;
	if (getVar("vatinclusive")=="0") setTableInputAmount("T1","u_vatamount",utils.multiply(linetotal,vatrate),row);
	else setTableInputAmount("T1","u_vatamount",linetotal - utils.divide(linetotal,1+vatrate),row);
	if (column!="u_linetotal") setTableInputAmount("T1","u_linetotal",utils.multiply(qty,price),row);
}

function u_computeTotalFreebieGPSPOS() {
	var freebieamount=0;
	rc =  getTableRowCount("T101");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T101",i)==false) {
			freebieamount += getTableInputNumeric("T101","u_srp",i);
		}
	}
	setTableInputAmount("T100","u_freebieamount",freebieamount);
}

function setFreebiesGPSPOS() {
	var data = new Array();	
	if (getTableInputNumeric("T100","u_freebielimit")<getTableInputNumeric("T100","u_freebieamount")) {
		page.statusbar.showWarning("Freebie Amount have exceeded the limit.");
		return false;
	}
	var rc =  getTableRowCount("T101");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T101",i)==false) {
			data["u_itemcode"] = getTableInput("T101","u_itemcode",i);
			data["u_itemdesc"] = getTableInput("T101","u_itemdesc",i);
			data["u_itemclass"] = getTableInput("T101","u_itemclass",i);
			data["u_uom"] = getTableInput("T101","u_uom",i);
			data["u_serialno"] = getTableInput("T101","u_serialno",i);
			data["u_itemmanageby"] = getTableInput("T101","u_itemmanageby",i);
			data["u_vatcode"] = getTableInput("T101","u_vatcode",i);
			data["u_vatrate"] = 0;
			data["u_promocode"] = getTableInput("T100","u_promocode");
			data["u_srp"] = getTableInput("T101","u_srp",i);
			data["u_quantity"] = formatNumericQuantity(1);
			data["u_numperuom"] =  getTableInput("T101","u_numperuom",i);
			data["u_tofollow"] =  getTableInput("T101","u_tofollow",i);
			data["u_freebie"] = 1;
			data["u_selected"] = 1;
			insertTableRowFromArray("T1",data);
		}
	}
	setTableInputAmount("T1","u_freebieamount",getTableInputNumeric("T100","u_freebieamount"),getTableInputNumeric("T100","u_row"));
	hidePopupFrame("popupFrameFreebies");
}


function u_computeTotalGPSPOS(p_usediscamount) {
	var totalbefdisc=0,vatamount=0,adddiscount=0,adddiscamount=0,taxamt=0,qty=0,price=0,amount=0,taxrate=0,rc=0,othercharges=0,roundamount=0,lineadddisc=0,wtaxamount=0,wtaxtxs=0,wtaxnet=0,wtaxenabled="0",totalamount=0,misccharges=0,amountafterdisc=0,taxableamount=0,totalquantity=0,penaltyamount=0;
	
	if (p_usediscamount==null) p_usediscamount = false;
	/*if (p_usediscamount==false)	adddiscount = getInputNumeric("u_discperc");
	else adddiscount = (getInputNumeric("u_discamount") / getInputNumeric("u_totalbefdisc")) * 100;
	*/
	adddiscount = 0;
	wtaxenabled = 0; //getVar("wtaxenabled");
	//page.console.insertMessage("computeT1 - start");
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_selected",i)=="0") continue;
			
			if (getTableInput("T1","u_freebie",i)=="0") {
			
				if (getTableInput("T1","u_penalty",i)=="1") penaltyamount += getTableInputNumeric("T1","u_linetotal",i);
				amount = getTableInputNumeric("T1","u_linetotal",i);
				
				taxrate = 0;
				
				taxrate = getTableInputNumeric("T1","u_vatrate",i)/100;
				if (getVar("vatinclusive")=="1") {
					amount = utils.divide(amount,1+taxrate);
					lineadddisc = (amount * (adddiscount/100));
					taxamt =  utils.roundbyformat((amount - lineadddisc) * taxrate,"amount");
				} else {
					lineadddisc = (amount * (adddiscount/100));
					taxamt =  utils.roundbyformat((amount - lineadddisc) * taxrate,"amount");
				}
				if (p_usediscamount==false) {
					totalbefdisc += getTableInputNumeric("T1","u_linetotal",i) - parseFloat(taxamt);
					adddiscamount += lineadddisc;
				}
				vatamount += parseFloat(taxamt);
			}
			
			totalquantity += getTableInputNumeric("T1","u_quantity",i);
			
			/*
			if (wtaxenabled=="1") {
				if (getTableInput("T1","wtaxliable",i)=="1") {
					wtaxtxs += taxamt;
					wtaxnet += amount - lineadddisc;
				}
			}
			*/
		}
	}
	if (p_usediscamount==false) {
		setInputAmount("u_totalbefdisc",totalbefdisc);
		//setInputAmount("discamount",adddiscamount);
	} else {
		totalbefdisc = getInputNumeric("u_totalbefdisc");
		//adddiscamount = getInputNumeric("discamount");
	}
	setInputAmount("u_vatamount",vatamount);
	setInputAmount("u_penaltyamount",penaltyamount);
	/*
	if (wtaxenabled=="1") {
		if (isTableInput("T5","wtaxtype",1)) {	
			if (getTableInput("T5","wtaxtype",1)=="G") setTableInputAmount("T5","taxableamount",(wtaxnet + wtaxtxs) * (getTableInputNumeric("T5","wtaxbaseamountperc",1)/100) ,1);
			else setTableInputAmount("T5","taxableamount",wtaxnet * (getTableInputNumeric("T5","wtaxbaseamountperc",1)/100) ,1);
			wtaxamount = getTableInputNumeric("T5","taxableamount",1) * (getTableInputNumeric("T5","wtaxrate",1)/100);
			setTableInputAmount("T5","wtaxamount", wtaxamount, 1);
			setInputAmount("wtaxamount",wtaxamount);
			if (getInput("wtaxcategory")=="P") wtaxamount = 0;
		}
	}
	*/
	//alert(totalbefdisc + " - " + adddiscamount + " + " + vatamount + " + " + " + " + othercharges + " - " + wtaxamount);
	totalamount = totalbefdisc - adddiscamount + vatamount + othercharges - wtaxamount;
	
	setInputQuantity("u_totalquantity",totalquantity);
	setInputAmount("u_totalamount",totalamount);
	setInputAmount("u_collectedcashamount",totalamount,true);
	setInputAmount("u_dueamount",getInputNumeric("u_totalamount") - getInputNumeric("u_paidamount"));
	setInputAmount("totalamountdisplay",totalamount);
	setInputAmount("totalamountpaymentdisplay",totalamount);	
	setCaption("totalquantity",getInput("u_totalquantity"));
	if (isInputChecked("u_ar")) {
		setInputAmount("u_aramount",totalamount);
	}
	
}

function u_displayPhotoGPSPOS(row) {
	var imgpath = "";
	if (row==0)	imgpath = u_ajaxItemListPictureFilePathGPSPOS(getTableInput("T1","u_itemcode"));
	else imgpath = u_ajaxItemListPictureFilePathGPSPOS(getTableInput("T1","u_itemcode",row));	
	document.images['PictureImg'].src = imgpath;
}

function u_ajaxItemListPictureFilePathGPSPOS(p_itemcode) {
	http = getHTTPObject(); // We create the HTTP Object
	http.open("GET", "ajaxItemListPictureFilePath.php?&itemcode=" + p_itemcode, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	return http.responseText;
}

function u_paymentGPSPOS() {
	showPopupFrame('popupFramePayments',true);
	focusInput("u_collectedcashamount");
}

function u_returnGPSPOS() {
	if (getInput("u_trxtype")=="R") {
		page.statusbar.showWarning("You are already in Sales Return Mode.");
		return false;
	}
	if (getTableInput("T1","u_itemcode")!="") {
		page.statusbar.showWarning("An Item is being added/edited. You cannot change to Sales Return Mode.");
		setT1FocusInputGPSPOS();
		return false;
	}
	
	if (getTableRowCount("T1",true)!=0) {
		page.statusbar.showWarning("An Item is already added. You cannot change to Sales Return Mode.");
		setT1FocusInputGPSPOS();
		return false;
	}
	
	setInput("u_trxtype","R");
	setCaption("trxtype","TOTAL SALES RETURN");
	setCaption("u_collectedcashamount","Amount Returned");
	setT1FocusInputGPSPOS();
}

function u_salesGPSPOS() {
	if (getInput("u_trxtype")=="S") {
		page.statusbar.showWarning("You are already in Sales Mode.");
		return false;
	}
	if (getTableInput("T1","u_itemcode")!="") {
		page.statusbar.showWarning("An Item is being added/edited. You cannot change to Sales Mode.");
		setT1FocusInputGPSPOS();
		return false;
	}
	
	if (getTableRowCount("T1",true)!=0) {
		page.statusbar.showWarning("An Item is already added. You cannot change to Sales Mode.");
		setT1FocusInputGPSPOS();
		return false;
	}
	setInput("u_trxtype","S");
	setCaption("trxtype","TOTAL SALES");
	setCaption("u_collectedcashamount","Amount Collected");
	setT1FocusInputGPSPOS();
}

function setT1FocusInputGPSPOS() {
	if (getTableInputType("T1","u_itemcode")=="text") focusTableInput("T1","u_itemcode");
	else focusTableInput("T1","u_itemdesc");
	if (isPopupFrameOpen("popupFrameFreebies"))setT101FocusInputGPSPOS();
}

function setT101FocusInputGPSPOS() {
	if (getTableInputType("T101","u_itemcode")=="text") focusTableInput("T101","u_itemcode");
	else focusTableInput("T101","u_itemdesc");
}

function formCancelSales() {
	showPopupFrame("popupFrameCancel",true);
}

function formSubmitOpen() {
	setInput("u_status","O");
	formSubmit();
}

function formSubmitDraft() {
	setInput("u_status","D");
	formSubmit();
}

function printBusinessPermit(targetObjectId,targetOptions) {

	if (targetOptions==null) targetOptions = "view";
	OpenPopup(800,300,'./udp.php?&objectcode=u_bpllist&df_opt=Print&df_docno=' + getPrivate("bpno") + '&targetId=' + targetObjectId + '&targetOptions=' + targetOptions,targetObjectId);
}

function getNextQueGPSPOS(docno) {
	var msgcnt = 0;	
	showAjaxProcess();
	try {
		//alert('querying');
		http = getHTTPObject(); 
		//alert( "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"));
		http.open("GET", "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"), false);
		http.send(null);
		var result = parseInt(http.responseText.trim());
		hideAjaxProcess();
		if (result>0) {
			setInput("u_queue",result);
		} else alert(http.responseText.trim());
		http.send(null);
	} catch (theError) {
		hideAjaxProcess();
	}	
}


function u_JSONSampleGPSPOS() {
    OpenPopup(1024,600,"./udp.php?&objectcode=u_epayment","Epayment Multipay");

}

function u_EpaymentSampleGPSPOS() {
	OpenPopup(800,600,"./udp.php?&objectcode=u_epaymentv2","Epayment V2");	
}

function getDocnoPerSeriesGPSPOS(seriesname) {
    var numlen = 0,prefix,suffix, nextno = 0,docno = 0,docno1 = 0,d = new Date();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();

    month = month.toString();
    year = year.toString();
    
//    alert(isdocseries);
//        var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME FROM U_TERMINALSERIES WHERE U_DOCSERIES = '"+seriesname+"' AND U_NEXTNO <= U_LASTNO AND COMPANY = '"+getGlobal("company")+"' AND BRANCH = '"+getGlobal("branch")+"' ");
        var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME,B.U_ISSUEDOCNO FROM U_LGUPOSREGISTERS A INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID WHERE B.U_DOCSERIES = '"+seriesname+"' AND B.U_NEXTNO <= B.U_LASTNO AND A.COMPANY = '"+getGlobal("company")+"' AND A.BRANCH = '"+getGlobal("branch")+"' AND A.U_STATUS = 'O' AND A.U_USERID = '"+getPrivate("userid")+"' ");
//    } else {
//        var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME FROM U_TERMINALSERIES WHERE U_DOCSERIESNAME = '"+getInput("u_docseries")+"' AND U_CASHIER = '"+getPrivate("userid")+"' AND DOCNO = '' U_NEXTNO <= U_LASTNO");
//    }
    	
        if (result.getAttribute("result")!= "-1") {
            if (parseInt(result.getAttribute("result"))>0) {
                    numlen = result.childNodes.item(0).getAttribute("u_numlen");
                    prefix = result.childNodes.item(0).getAttribute("u_prefix");
                    suffix = result.childNodes.item(0).getAttribute("u_suffix");
                    nextno = result.childNodes.item(0).getAttribute("u_nextno");

                    nextno = nextno.toString();

                    prefix = prefix.replace("{POS}",getInput("u_terminalid"));
                    suffix = suffix.replace("{POS}",getInput("u_terminalid"));
                    prefix = prefix.replace("[m]",month.padStart1(2,'0'));
                    suffix = suffix.replace("[m]",month.padStart1(2,'0'));
                    prefix = prefix.replace("[Y]",year);
                    suffix = suffix.replace("[Y]",year);
                    prefix = prefix.replace("[y]",year.padStart1(2,'0'));
                    suffix = suffix.replace("[y]",year.padStart1(2,'0'));

                    docno = prefix + nextno.padStart1(numlen,'0') + suffix;
                    setInput("u_seriesdocno",result.childNodes.item(0).getAttribute("u_docseriesname"));
                    setInput("u_issuedocno",result.childNodes.item(0).getAttribute("u_issuedocno"));
                    setInput("docno",docno);
            }
        }
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

function popupCommunitaxCertificate(){
    showPopupFrame('popupFrameCTCApplication',true);
    focusInput("u_ctctype");
}
function setCTCFees(){
    
    var ctcgrossfeecoderc=0,ctcbasicfeecoderc=0,grossamount = 0,basicamount = 0,ctcpenamount = 0, rc = getTableRowCount("T1");
    
    if (isInputEmpty("u_ctctype")) return false;
    if (isInputNegative("u_ctcgross")) return false;
    if(getInput("u_ctctype") == "I"){
           grossamount = Math.round(getInputNumeric("u_ctcgross") / 1000);
           basicamount = 10;
    
    } else{
           grossamount = Math.round(getInputNumeric("u_ctcgross") / 5000) * 2;
           basicamount = 500;
    
    }
    var interest = 0, interestperc = 0;
    var duedate = getPrivate("year") + "0101";
    var paydate = formatDateToDB(getInput("u_date")).replace(/-/g,"");
    var duemonth = parseInt(duedate.substr(0,6));
    var paymonth = parseInt(paydate.substr(0,6));
    var dueyear = parseInt(duedate.substr(0,4));
    var payyear = parseInt(paydate.substr(0,4));
    

    if(payyear>dueyear){
        interestperc = ((payyear-dueyear) * 12) - (parseInt(duedate.substr(4,2)) - parseInt(paydate.substr(4,2)));
    }else{
        interestperc = (paymonth-duemonth) + 1;
    }

    if (paydate>=duedate) {
            
            if(paymonth>2) interest =  (basicamount + grossamount) * (.02*interestperc);
            else interest = 0;
    }
    
    

    for (xxx = 1; xxx <=rc; xxx++) {
        if (isTableRowDeleted("T1",xxx)==false) {
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("ctcbasicfeecode")) {
                    setTableInputAmount("T1","u_unitprice",basicamount,xxx);
                    setTableInputAmount("T1","u_linetotal",basicamount,xxx);
                    ctcbasicfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("ctcgrossfeecode")) {
                    setTableInputAmount("T1","u_unitprice",grossamount,xxx);
                    setTableInputAmount("T1","u_linetotal",grossamount,xxx);
                    ctcgrossfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("ctcpenfeecode")) {
                    setTableInputAmount("T1","u_unitprice",interest,xxx);
                    setTableInputAmount("T1","u_linetotal",interest,xxx);
                    ctcgrossfeecoderc=xxx;
            }
        }
    }
  
     if(getPrivate("ctcbasicfeecode")!=""){
         if (ctcbasicfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("ctcbasicfeecode");
            data["u_itemdesc"] = getPrivate("ctcbasicfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(basicamount);
            data["u_linetotal"] = formatNumericAmount(basicamount);
            insertTableRowFromArray("T1",data);
        }
    }else{
        page.statusbar.showError("CTC Basic Fee not found. Please update settings.");
        return false;
    }
    
    if(getPrivate("ctcgrossfeecode")!=""){
        if (ctcgrossfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("ctcgrossfeecode");
            data["u_itemdesc"] = getPrivate("ctcgrossfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(grossamount);
            data["u_linetotal"] = formatNumericAmount(grossamount);
            insertTableRowFromArray("T1",data);
        }   
    }else{
        page.statusbar.showError("CTC Gross Fee not found. Please update settings.");
        return false;
    }
    
    if(getPrivate("ctcpenfeecode")!=""){
        if (ctcgrossfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("ctcpenfeecode");
            data["u_itemdesc"] = getPrivate("ctcpenfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(interest);
            data["u_linetotal"] = formatNumericAmount(interest);
            insertTableRowFromArray("T1",data);
        }   
    }else{
        page.statusbar.showError("CTC Penalty Fee not found. Please update settings.");
        return false;
    }
   
    u_computeTotalGPSPOS();
    hidePopupFrame('popupFrameCTCApplication');
}

function setTranserTaxFees(){
    
    if (isInputEmpty("u_ttdosdate")) return false;
    if (isInputEmpty("u_tttdno")) return false;
    if (isInputNegative("u_ttgross")) return false;
    
    
    var transfertaxfeecoderc=0,transfertaxintfeecoderc=0,transfertaxpenfeecoderc=0,processingfeecoderc=0; 
    var rc = getTableRowCount("T1");
    var transfertax = 0,surcharge = 0,interestperc = 0,interest = 0;
    var interestdate = 0;
    
    var duedate = addDays(getInput("u_ttdosdate"),59).replace(/-/g,"");
    var paydate = formatDateToDB(getInput("u_date")).replace(/-/g,"");

    var duemonth = parseInt(duedate.substr(0,6));
    var paymonth = parseInt(paydate.substr(0,6));

    var dueyear = parseInt(duedate.substr(0,4));
    var payyear = parseInt(paydate.substr(0,4));
    
    var dueday = parseInt(duedate.substr(6,7));
    
    transfertax = (getInputNumeric("u_ttgross") * .5) * .01;
    
    if(payyear>dueyear){
        interest = (((payyear-dueyear) * 12) - (parseInt(duedate.substr(4,2)) - parseInt(paydate.substr(4,2))));
    }else{
        interest = (paymonth-duemonth);
    }
    
    interestdate = (setDay(addMonths(addDays(getInput("u_ttdosdate"),59),interest),dueday)).replace(/-/g,"");
    
    if(paydate > interestdate) {
        interest = interest + 1;
    }
    
    if(interest>36) interest = 36;
    
    if (paydate>duedate) {
            surcharge = transfertax * 0.25;
            if(paymonth>=duemonth) interestperc = transfertax * (.02*(interest));
    }
    
    for (xxx = 1; xxx <=rc; xxx++) {
        if (isTableRowDeleted("T1",xxx)==false) {
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("transfertaxfeecode")) {
                    setTableInputAmount("T1","u_unitprice",transfertax,xxx);
                    setTableInputAmount("T1","u_linetotal",transfertax,xxx);
                    transfertaxfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("transfertaxpenfeecode")) {
                    setTableInputAmount("T1","u_unitprice",surcharge,xxx);
                    setTableInputAmount("T1","u_linetotal",surcharge,xxx);
                    transfertaxpenfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("transfertaxintfeecode")) {
                    setTableInputAmount("T1","u_unitprice",interestperc,xxx);
                    setTableInputAmount("T1","u_linetotal",interestperc,xxx);
                    transfertaxintfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("processingfeecode")) {
                    setTableInputAmount("T1","u_unitprice",formatNumericAmount(getPrivate("processingfeeamount")),xxx);
                    setTableInputAmount("T1","u_linetotal",formatNumericAmount(getPrivate("processingfeeamount")),xxx);
                    processingfeecoderc=xxx;
            }
        }
    }
    
    if (transfertaxfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("transfertaxfeecode");
            data["u_itemdesc"] = getPrivate("transfertaxfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(transfertax);
            data["u_linetotal"] = formatNumericAmount(transfertax);
            insertTableRowFromArray("T1",data);
    }
    
    if (transfertaxpenfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("transfertaxpenfeecode");
            data["u_itemdesc"] = getPrivate("transfertaxpenfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(surcharge);
            data["u_linetotal"] = formatNumericAmount(surcharge);
            insertTableRowFromArray("T1",data);
    }
    
    if (transfertaxintfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("transfertaxintfeecode");
            data["u_itemdesc"] = getPrivate("transfertaxintfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(interestperc);
            data["u_linetotal"] = formatNumericAmount(interestperc);
            insertTableRowFromArray("T1",data);
    }
    
    if (processingfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("processingfeecode");
            data["u_itemdesc"] = getPrivate("processingfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(getPrivate("processingfeeamount"));
            data["u_linetotal"] = formatNumericAmount(getPrivate("processingfeeamount"));
            insertTableRowFromArray("T1",data);
    }
    
    u_computeTotalGPSPOS();
    hidePopupFrame('popupFrameTransferTaxApplication');
}

function setFranchiseTaxFees(){
    
    if (isInputNegative("u_ftgross")) return false;
    
    
    var franchisetaxfeecoderc=0,franchisetaxintfeecoderc=0,franchisetaxpenfeecoderc=0,processingfeecoderc=0; 
    var rc = getTableRowCount("T1");
    var franchisetax = 0,surcharge = 0,interestperc = 0,interest = 0;
    
    var duedate = formatDateToDB(getInput("u_ftduedate")).replace(/-/g,"");
    var paydate = formatDateToDB(getInput("u_date")).replace(/-/g,"");

    var duemonth = parseInt(duedate.substr(0,6));
    var paymonth = parseInt(paydate.substr(0,6));

    var dueyear = parseInt(duedate.substr(0,4));
    var payyear = parseInt(paydate.substr(0,4));
    
    franchisetax = (getInputNumeric("u_ftgross") * .5) * .01;
    
    if(payyear>dueyear){
        interest = ((payyear-dueyear) * 12) - (parseInt(duedate.substr(4,2)) - parseInt(paydate.substr(4,2)));
    }else{
        interest = paymonth-duemonth;
    }
    
    if(interest>36) interest = 36;
    
    if (paydate>duedate) {
            surcharge = franchisetax * 0.25;
            if(paymonth>duemonth) interestperc = franchisetax * (.02*(interest));
    }
    
    for (xxx = 1; xxx <=rc; xxx++) {
        if (isTableRowDeleted("T1",xxx)==false) {
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("franchisetaxfeecode")) {
                    setTableInputAmount("T1","u_unitprice",franchisetax,xxx);
                    setTableInputAmount("T1","u_linetotal",franchisetax,xxx);
                    franchisetaxfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("franchisetaxpenfeecode")) {
                    setTableInputAmount("T1","u_unitprice",surcharge,xxx);
                    setTableInputAmount("T1","u_linetotal",surcharge,xxx);
                    franchisetaxpenfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("franchisetaxintfeecode")) {
                    setTableInputAmount("T1","u_unitprice",interestperc,xxx);
                    setTableInputAmount("T1","u_linetotal",interestperc,xxx);
                    franchisetaxintfeecoderc=xxx;
            }
            if (getTableInput("T1","u_itemcode",xxx)==getPrivate("processingfeecode")) {
                    setTableInputAmount("T1","u_unitprice",formatNumericAmount(getPrivate("processingfeeamount")),xxx);
                    setTableInputAmount("T1","u_linetotal",formatNumericAmount(getPrivate("processingfeeamount")),xxx);
                    processingfeecoderc=xxx;
            }
        }
    }
    
    if (franchisetaxfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("franchisetaxfeecode");
            data["u_itemdesc"] = getPrivate("franchisetaxfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(franchisetax);
            data["u_linetotal"] = formatNumericAmount(franchisetax);
            insertTableRowFromArray("T1",data);
    }
    
    if (franchisetaxpenfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("franchisetaxpenfeecode");
            data["u_itemdesc"] = getPrivate("franchisetaxpenfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(surcharge);
            data["u_linetotal"] = formatNumericAmount(surcharge);
            insertTableRowFromArray("T1",data);
    }
    
    if (franchisetaxintfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("franchisetaxintfeecode");
            data["u_itemdesc"] = getPrivate("franchisetaxintfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(interestperc);
            data["u_linetotal"] = formatNumericAmount(interestperc);
            insertTableRowFromArray("T1",data);
    }
    
    if (processingfeecoderc==0) {
            var data = new Array();
            data["u_itemcode"] = getPrivate("processingfeecode");
            data["u_itemdesc"] = getPrivate("processingfeename");
            data["u_quantity"] = 1;
            data["u_unitprice"] = formatNumericAmount(getPrivate("processingfeeamount"));
            data["u_linetotal"] = formatNumericAmount(getPrivate("processingfeeamount"));
            insertTableRowFromArray("T1",data);
    }
    
    u_computeTotalGPSPOS();
    hidePopupFrame('popupFrameFranchiseTaxApplication');
}

function popupTransferTax(){
    showPopupFrame('popupFrameTransferTaxApplication',true);
    focusInput("u_ttgross");
}

function popupFranchiseTax(){
    showPopupFrame('popupFrameFranchiseTaxApplication',true);
    focusInput("u_ttgross");
}

function addDays(date, days) {
  var result = new Date(date);
  result.setDate(result.getDate() + days);
  result = result.getFullYear() + "-" + ("0" + (result.getMonth() + 1)).slice(-2) + "-" + ("0" + (result.getDate() + 1)).slice(-2)
  return result;
}
function addMonths(date, months) {
  var result = new Date(date);
  result.setMonth(result.getMonth() + months);
  result = result.getFullYear() + "-" + ("0" + (result.getMonth() + 1)).slice(-2) + "-" + ("0" + (result.getDate() + 1)).slice(-2)
  return result;
}
function setDay(date, day) {
  var result = new Date(date);
  result.setDate(day);
  result = result.getFullYear() + "-" + ("0" + (result.getMonth() + 1)).slice(-2) + "-" + ("0" + (result.getDate())).slice(-2)
  return result;
}

function CopyFromCategories(){
   OpenPopup(1040,390,"./udp.php?&objectcode=u_feecategories","Fees & Charges Categories");
}

function setBillNosLGUPOS() {
	var billno="";
	var rc =  getTableRowCount("T6");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T6",i)==false) {
			if (billno!="") billno += ", ";
			billno += (getTableInput("T6","u_billno",i));
		}
	}
	if (getInput("u_billno")!="") {
		if (billno!="") billno = ", " + billno;
		billno = getInput("u_billno") + billno;
	}
	setInput("u_billnos",billno);
	
}

function u_setBillItemsGPSLGUPOS() {
	var data = new Array();
	var billno = "";
	if (getInput("u_billno")!="") {
		billno = "'"+getInput("u_billno")+"'";
	}
	var rc =  getTableRowCount("T6");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T6",i)==false) {
			if (billno!="") billno += ", ";
			billno += "'"+getTableInput("T6","u_billno",i)+"'";
		}
	}
	clearTable("T1",true);
        if (billno!="") {
               var result = page.executeFormattedQuery("select A.U_PAYQTR,B.U_BUSINESSLINE,B.LINEID,A.DOCNO,D.U_INTEREST,A.U_CUSTNO, A.U_CUSTNAME, A.U_PROFITCENTER, A.U_MODULE, A.U_PAYMODE, A.U_DOCDATE, A.U_DUEDATE, B.U_ITEMCODE, B.U_ITEMDESC, B.U_AMOUNT-B.U_SETTLEDAMOUNT AS U_AMOUNT, D.U_PENALTYCODE, D.U_PENALTYDESC, A.U_ALERTMOBILENO from U_LGUBILLS A INNER JOIN U_LGUBILLITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND B.U_AMOUNT>B.U_SETTLEDAMOUNT INNER JOIN U_LGUPROFITCENTERS C ON C.CODE=A.U_PROFITCENTER INNER JOIN U_LGUFEES D ON D.CODE=B.U_ITEMCODE where A.DOCSTATUS NOT IN('CN') AND A.COMPANY='"+getGlobal("company")+"' AND A.BRANCH='"+getGlobal("branch")+"' and A.DOCNO in ("+billno+")");	
                if (result.getAttribute("result")!= "-1") {
                    if (parseInt(result.getAttribute("result"))>0) {
                        if (parseFloat(result.childNodes.item(0).getAttribute("u_dueamount"))==0) {
                                page.statusbar.showError("Bill already settled.");	
                                return false;
                        }
                      
                        for (var iii=0; iii<result.childNodes.length; iii++) {
                            setInput("u_custno",result.childNodes.item(iii).getAttribute("u_custno"));
                            setInput("u_custname",result.childNodes.item(iii).getAttribute("u_custname"));
                            setInput("u_profitcenter",result.childNodes.item(iii).getAttribute("u_profitcenter"));
                            setInput("u_module",result.childNodes.item(iii).getAttribute("u_module"));
                            setInput("u_paymode",result.childNodes.item(iii).getAttribute("u_paymode"));
                            setInput("u_billdate",formatDateToHttp(result.childNodes.item(iii).getAttribute("u_docdate")));
                            setInput("u_billduedate",formatDateToHttp(result.childNodes.item(iii).getAttribute("u_duedate")));
                            setInput("u_contactno",result.childNodes.item(iii).getAttribute("u_alertmobileno"));

                            var penaltyperc=0;
                            var intperc=0;
                            var data = new Array();
                            var duedate = formatDateToDB(getInput("u_billduedate")).replace(/-/g,"");
                            var paydate = formatDateToDB(getInput("u_date")).replace(/-/g,"");
                            var duemonth = parseInt(duedate.substr(0,6));
                            var paymonth = parseInt(paydate.substr(0,6));

                            var dueyear = parseInt(duedate.substr(0,4));
                            var payyear = parseInt(paydate.substr(0,4));
                            var payday = parseInt(paydate.substr(6,2));
                            var intpercvalue = 0;
                            if(payyear>dueyear){
                                intpercvalue = ((payyear-dueyear) * 12) - (parseInt(duedate.substr(4,2)) - parseInt(paydate.substr(4,2)));
                            }else{
                                intpercvalue = paymonth-duemonth;
                            }
                            
                            switch(getInput("u_module")) {
                                    case "Real Property Tax Bill":
                                    case "Real Property Tax":
                                            break;
                                    default:	
                                            if (paydate>duedate) {
                                                //due to covid change penalty logic and computation
                                                if(payyear != 2020 && paymonth > 202106) {
                                                      penaltyperc = 0.25;
                                                      
                                                    if (paymonth>=duemonth && payday > 20) intperc = (.02*(intpercvalue + 1));
                                                    else intperc = (.02*(intpercvalue));				
                                                    //Bacoor memorandum, because of covid all surcharge less %

//                                                    switch (result.childNodes.item(0).getAttribute("u_payqtr")) {
//                                                            case "1":
//                                                                    intperc = intperc - .12;
//                                                                    break;
//                                                            case "2":
//                                                                    intperc = intperc - .06;
//                                                                    break;
//
//                                                    }
                                                }
                                            }
                                            break;
                            }
                            if (intperc > .72) intperc  = .72;
                            setInputPercent("u_penaltyperc",penaltyperc*100);
                            setInputPercent("u_intperc",intperc*100);
                                data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
                                data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
                                data["u_quantity"] = formatNumericQuantity(1);
                                data["u_numperuom"] = formatNumericQuantity(1);
                                data["u_basedocno"] = result.childNodes.item(iii).getAttribute("docno");
                                data["u_baselineid"] = result.childNodes.item(iii).getAttribute("lineid");
                                data["u_businessline"] = result.childNodes.item(iii).getAttribute("u_businessline");
                                data["u_unitprice"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                data["u_price"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                data["u_linetotal"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
                                data["u_penalty"] = 0;
                                        insertTableRowFromArray("T1",data);
                                /*if (penaltyperc>0 && result.childNodes.item(iii).getAttribute("u_penaltycode")!="") {
                                        data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_penaltycode");
                                        data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_penaltydesc");
                                        data["u_quantity"] = formatNumericQuantity(1);
                                        data["u_numperuom"] = formatNumericQuantity(1);
                                        data["u_unitprice"] = formatNumericAmount(parseFloat(result.childNodes.item(iii).getAttribute("u_amount"))*(penaltyperc+intperc));
                                        data["u_price"] = data["u_unitprice"];
                                        data["u_linetotal"] = data["u_unitprice"];
                                        data["u_penalty"] = 1;
                                        insertTableRowFromArray("T1",data);
                                        penaltyamount+=parseFloat(result.childNodes.item(iii).getAttribute("u_amount"))*(penaltyperc+intperc);
                                }*/
								
								
				
                                    if (penaltyperc>0 && result.childNodes.item(iii).getAttribute("u_penaltycode")!="") {
                                            var interest=parseInt(result.childNodes.item(iii).getAttribute("u_interest"));
                                            data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_penaltycode");
                                            data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_penaltydesc");
                                            data["u_quantity"] = formatNumericQuantity(1);
                                            data["u_numperuom"] = formatNumericQuantity(1);
                                            data["u_basedocno"] = "";
                                            data["u_baselineid"] =  result.childNodes.item(iii).getAttribute("lineid");
                                            data["u_businessline"] = result.childNodes.item(iii).getAttribute("u_businessline");
                                            if(interest==1) {
                                                    data["u_unitprice"] = formatNumericAmount(parseFloat(result.childNodes.item(iii).getAttribute("u_amount"))*(penaltyperc+intperc));
                                            } else {
                                                    data["u_unitprice"] = formatNumericAmount(parseFloat(result.childNodes.item(iii).getAttribute("u_amount"))*(penaltyperc));
                                            }
                                            data["u_price"] = data["u_unitprice"];
                                            data["u_linetotal"] = data["u_unitprice"];
                                            data["u_penalty"] = 1;
                                            insertTableRowFromArray("T1",data);
                                           //penaltyamount+=parseFloat(result.childNodes.item(iii).getAttribute("u_amount"))*(penaltyperc+intperc);
                                }			
                                
                        }
                       
                        u_computeTotalGPSPOS();
                        disableInput("u_cashierapptype");
                        disableInput("u_profitcenter");
                        if (getInput("u_module")=="Real Property Tax" && getInput("u_paymode")=="Q") {
                                disableInput("u_partialpay");
                        } else {
                                enableInput("u_partialpay");
                        }
                       // u_ajaxloadu_lguposterminalseries("df_u_docseries",getPrivate("userid"),'',":",getInput("u_profitcenter"));
                        getDocnoPerSeriesGPSPOS(getInput("u_docseries"));
                    } else {
                            setInput("u_profitcenter","");
                            setInput("u_module","");
                            setInput("u_paymode","");
                            setInput("u_billdate","");
                            setInput("u_billduedate","");
                            page.statusbar.showError("Invalid Bill No.");	
                            return false;
                    }
                } else {
                        setInput("u_profitcenter","");
                        setInput("u_module","");
                        setInput("u_paymode","");
                        setInput("u_billdate","");
                        setInput("u_billduedate","");
                        page.statusbar.showError("Error retrieving bill record. Try Again, if problem persists, check the connection.");	
                        return false;
                }
        }
         u_computeTotalGPSPOS();
        
}
function ListofUnpaidBillsBLDG() {
        OpenPopup(1280,450,"./udp.php?&objectcode=u_unpaybilllistbldg","List of Unpaid Bills - Building");
}
function ListofUnpaidBillsFireSafety() {
        OpenPopup(1280,450,"./udp.php?&objectcode=u_unpaybilllistFireSafety","List of Unpaid Bills - Fire Safety");
}
function ListofUnpaidBillsLocational() {
        OpenPopup(1280,450,"./udp.php?&objectcode=u_unpaybilllistLocational","List of Unpaid Bills - Locational");
}
function ListofUnpaidBills() {
        OpenPopup(1280,450,"./udp.php?&objectcode=u_unpaybilllist","List of Unpaid Bills - Business");
}
function loadGeoDataBPAS() {
        OpenPopup(1280,550,"./udp.php?&objectcode=u_searchgeobpas","GeoData BPAS");
}

function manualPostingGPSBPLS(){
       
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
