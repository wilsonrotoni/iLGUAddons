// page events
page.events.add.load('onPageLoadGPSLGUZoning');
//page.events.add.resize('onPageResizeGPSLGUZoning');
page.events.add.submit('onPageSubmitGPSLGUZoning');
//page.events.add.cfl('onCFLGPSLGUZoning');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUZoning');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUZoning');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUZoning');
page.elements.events.add.keydown('onElementKeyDownGPSLGUZoning');
page.elements.events.add.validate('onElementValidateGPSLGUZoning');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUZoning');
//page.elements.events.add.changing('onElementChangingGPSLGUZoning');
page.elements.events.add.change('onElementChangeGPSLGUZoning');
page.elements.events.add.click('onElementClickGPSLGUZoning');
//page.elements.events.add.cfl('onElementCFLGPSLGUZoning');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUZoning');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUZoning');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUZoning');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUZoning');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUZoning');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUZoning');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUZoning');
page.tables.events.add.delete('onTableDeleteRowGPSLGUZoning');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUZoning');
//page.tables.events.add.select('onTableSelectRowGPSLGUZoning');

function onPageLoadGPSLGUZoning() {
    if (getInput("docstatus")=="D") {
            enableInput("docno");
    }
    
}

function onPageResizeGPSLGUZoning(width,height) {
}

function onPageSubmitGPSLGUZoning(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_projectname")) return false;
		if (isInputEmpty("u_applicantno")) return false;
                if(getInput("u_appnature")!='Others') if (isInputNegative("u_totalamount")) return false;
	}
	return true;
}

function onCFLGPSLGUZoning(Id) {
	return true;
}

function onCFLGetParamsGPSLGUZoning(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUZoning() {
}

function onElementFocusGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUZoning(element,event,column,table,row) {
        var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
        switch (sc_press) {
            case "F4":
            u_OpenPopSearchBPASApp();
            break;
            case "F7":
            if (getVar("formSubmitAction")=="sc") u_approveGPSLGUZoning();
            break;
            case "F9":
            if (getVar("formSubmitAction")=="sc") u_disapproveGPSLGUZoning();
            break;
        }
    
}

function onElementValidateGPSLGUZoning(element,column,table,row) {
	var result;
	switch (table) {
                case "T1":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount,u_seqno from u_locationalfees where code='"+getTableInput(table,column)+"'");	
						else var result = page.executeFormattedQuery("select code, name, u_amount,u_seqno  from u_locationalfees where name like '"+getTableInput(table,column)+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
								setTableInputAmount(table,"u_linetotal",result.childNodes.item(0).getAttribute("u_amount"));
								setTableInputAmount(table,"u_seqno",result.childNodes.item(0).getAttribute("u_seqno"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_amount",0);
								setTableInputAmount(table,"u_linetotal",0);
								setTableInputAmount(table,"u_seqno",0);
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							setTableInputAmount(table,"u_amount",0);
                                                        setTableInputAmount(table,"u_linetotal",0);
                                                        setTableInputAmount(table,"u_seqno",0);
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
								setTableInputAmount(table,"u_linetotal",0);
					}						
					break;
                                        
                                case "u_quantity":
                                case "u_amount":
                                    setTableInputAmount("T1","u_linetotal",getTableInputNumeric("T1","u_amount") * getTableInput("T1","u_quantity"));
                                break;
			}
			break;
		default:
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_docdate, u_bpno, u_businessname, u_address from u_zoningclrapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
								setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
								setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
							} else {
								setInput("u_appdate","");
								page.statusbar.showError("Invalid Application No.");	
								return false;
							}
						} else {
							setInput("u_appdate","");
							page.statusbar.showError("Error retrieving application record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_appdate","");
					}
					break;
                               
                                break;
				case "u_applicantno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_applicanttype,u_lastname, u_firstname, u_middlename, u_address, u_contactno, u_corpname, u_corpaddress, u_corptelno, u_tin, u_ctcno from u_zoningapplicants where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_rightoverland",result.childNodes.item(0).getAttribute("u_applicanttype"));
								setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_address"));
								setInput("u_corpname",result.childNodes.item(0).getAttribute("u_corpname"));
								setInput("u_corpaddress",result.childNodes.item(0).getAttribute("u_corpaddress"));
								setInput("u_tctno",result.childNodes.item(0).getAttribute("u_tctno"));
							} else {
								page.statusbar.showError("Invalid Applicant No.");	
								return false;
							}
						} else {
							setInput("u_appdate","");
							page.statusbar.showError("Error retrieving applicant record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_appdate","");
					}
					break;
                               
                                break;
                            case "u_totflrareabldg":
                                    computeZoningClearanceFee(); 
                                    computeProcessingFee(); 
                                    computeTotalAssessment();
                                break;
                            case "u_surveycount":
                                    computeSurveyPermitFee(); 
                                    computeTotalAssessment();
                                break;
                            
                           
			}
	}
	return true;
}

function onElementGetValidateParamsGPSLGUZoning(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUZoning(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUZoning(element,column,table,row) {
     switch (table) {
		default:
                    switch(column) {
                        case "u_projtype":
                            computeZoningClearanceFee(); 
                            computeProcessingFee(); 
                            computeTotalAssessment();
                        break;
                    }
                break;
            }
	return true;
}

function onElementClickGPSLGUZoning(element,column,table,row) {
        switch (table) {
		default:
                    switch(column) {
                        case "u_appnature":
                            getZoningApplicationFees();
                            if(getInput(column)=="Others") focusInput("u_appnatureothers");
                        break;
                    }
                break;
	}
	return true;
}

function onElementCFLGPSLGUZoning(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUZoning(Id,params) {
	switch (Id) {
		case "df_u_applicantno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT docno, u_corpname, u_lastname, u_firstname FROM u_zoningapplicants")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Applicant No` Coporation Name` Lastname ` Firstname")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("20` 50` 30` 30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
		case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_locationalfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_locationalfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
                case "df_u_bstreet":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME,U_BRGY FROM U_STREETS  where  u_brgy = '"+getInput("u_bbrgy")+"' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Street`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_bvillage":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME,u_barangay FROM u_subdivisions where  u_barangay = '"+getInput("u_bbrgy")+"' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Subdivisions`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSLGUZoning(table) {
}

function onTableBeforeInsertRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1":
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;  
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}
    return true;
}

function onTableAfterInsertRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeUpdateRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1":
                        if (isTableInputEmpty(table,"u_year")) return false;
			if (isTableInputEmpty(table,"u_feecode")) return false;
			if (isTableInputEmpty(table,"u_feedesc")) return false;
                        setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
			//if (isTableInputNegative(table,"u_amount")) return false;
			break;
    }
	return true;
}

function onTableAfterUpdateRowGPSLGUZoning(table,row) {
    switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeDeleteRowGPSLGUZoning(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUZoning(table,row) {
     switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeSelectRowGPSLGUZoning(table,row) {
	return true;
}

function onTableSelectRowGPSLGUZoning(table,row) {
}


function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			total+= getTableInputNumeric("T1","u_linetotal",i);
		}
	}
 
	setInputAmount("u_totalamount",total);
}


function u_PaymentHistoryGPSGLGUZoning() {
        OpenPopup(1024,500,"./udp.php?&objectcode=u_zoningledger&df_refno2="+getInput("u_acctno")+"","Business Ledger");
}

function getZoningApplicationFees() {
//    if(getInput("u_appnature")=="New Application" && getInput("u_istup") == 0) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_AMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_AMOUNT <>0");	
//    else if (getInput("u_appnature")=="New Application" && getInput("u_istup") == 1) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_TUPNEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_TUPNEWAMOUNT <>0");	
//    else if(getInput("u_appnature")=="Renewal" && getInput("u_istup") == 0) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_RENEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_RENEWAMOUNT <>0");	
//    else if(getInput("u_appnature")=="Renewal" && getInput("u_istup") == 1) result = page.executeFormattedQuery("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_TUPRENEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_TUPRENEWAMOUNT <>0");	
//    if (result.getAttribute("result")!= "-1") {
//        if (parseInt(result.getAttribute("result"))>0) {
//                clearTable("T1",true);
//                var data = new Array();
//                    for (var iii=0; iii<result.childNodes.length; iii++) {
//                            data["u_year"] = getInput("u_year");
//                            data["u_feecode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
//                            data["u_feedesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
//                            data["u_seqno"] = result.childNodes.item(iii).getAttribute("u_seqno");
//                            data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
//                            insertTableRowFromArray("T1",data);
//                    }
//                computeTotalAssessment();
//        } else {
//                page.statusbar.showError("Invalid Automatic Fees. Please check the set up for zoning fees");	
//                return false;
//        }
//    } else {
//            page.statusbar.showError("Error retrieving Fees. Try Again, if problem persists, check the connection.");	
//            return false;
//    }
}

function u_googlemapGPSLGUZoning() {
	OpenPopupViewMap("viewmap","U_BPLAPPS",getInput("u_bphaseno") + " " + getInput("u_bblock") + " " + getInput("u_blotno") + " " +  getInput("u_bvillage") + " " + getInput("u_bstreet"),getInput("u_bbrgy"),getInput("u_bcity"),"","",getInput("u_bprovince"),"Philippines","");
}
function getAddressGIS() {
	if (getInput("u_latitude")!="" && getInput("u_longitude")!="") {
		var latlng = {lat: parseFloat(getInput("u_latitude")), lng: parseFloat(getInput("u_longitude"))};
	} else {
		var latlng = false;
	}	
	return latlng;
}

function setAddressGIS(lat,lng) {
	setInput("u_latitude",lat);
	setInput("u_longitude",lng);
}

function computeZoningClearanceFee(){
    var rc = getTableRowCount("T1"),amount=0;
    var zoningclearancefeerc = 0;
    var zoninglandusefeerc = 0;
    var grosssales1 = 0, landuseamount=0, taxamount1=0,  taxrate1=0, fromamount1 = 0, excessamount1 = 0,compbased = '';
  
    if (getInput("u_projtype") == 'A') {
        grosssales1 = getInputNumeric("u_totflrareabldg") * 16000;
    } else {
        grosssales1 = getInputNumeric("u_totflrareabldg") * 20000;
    }
    
        var result1 = page.executeFormattedQuery("select b.u_excessamount, a.u_landuseamount, b.u_amount, b.u_rate,b.u_from from u_zoningprojtypes a, u_zoningprojtypestaxes b where b.code=a.code and "+grosssales1+" >= b.u_from and ("+grosssales1+" <=b.u_to or b.u_to=0) and a.code = '"+getInput("u_projtype")+"'");
        if (result1.getAttribute("result1")!= "-1") {
                if (parseInt(result1.getAttribute("result"))>0) {
                        landuseamount = parseFloat(result1.childNodes.item(0).getAttribute("u_landuseamount"));
                        taxamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_amount"));
                        taxrate1 = parseFloat(result1.childNodes.item(0).getAttribute("u_rate"));
                        excessamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_excessamount"));
                        fromamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_from"));
                }		
        } else {
                page.statusbar.showError("Error retrieving tax amount. Try Again, if problem persists, check the connection.");	
                return false;
        }
        
        if (taxamount1>0 && excessamount1==0 && taxrate1==0) {
            amount = taxamount1;
        }else if(taxamount1==0 && excessamount1==0 && taxrate1>0 ) {
            amount = taxrate1*grosssales1;
        }else if(taxamount1>0 && excessamount1>0 && taxrate1>0 ){
            amount = ((((grosssales1 - fromamount1) / excessamount1) * taxrate1 ) + taxamount1);
        }
        
    
        //Zoning Clearance
        for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T1",i)==false) {
                if (getTableInput("T1","u_feecode",i)==getPrivate("zoningclearancefeecode")) {
                    setTableInputAmount("T1","u_amount",amount,i);
                    setTableInputAmount("T1","u_linetotal",amount * getTableInputNumeric("T1","u_quantity",i),i);
                    zoningclearancefeerc=i;
                }
            }
	}
        
        if (zoningclearancefeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("zoningclearancefeecode");
		data["u_feedesc"] = getPrivate("zoningclearancefeedesc");
                var result = page.executeFormattedQuery("SELECT u_seqno from u_locationalfees where code = '"+getPrivate("zoningclearancefeecode")+"'");
                if (parseInt(result.getAttribute("result"))>0) {
                        data["u_seqno"] = result.childNodes.item(0).getAttribute("u_seqno");
                }
                data["u_amount"] = formatNumericAmount(amount);
                data["u_linetotal"] = formatNumericAmount(amount);
		insertTableRowFromArray("T1",data);
	}
        
        //Zoning and Land Use
        for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T1",i)==false) {
                if (getTableInput("T1","u_feecode",i)==getPrivate("zoninglandusefeecode")) {
                    setTableInputAmount("T1","u_amount",landuseamount,i);
                    setTableInputAmount("T1","u_linetotal",landuseamount * getTableInputNumeric("T1","u_quantity",i),i);
                    zoningclearancefeerc=i;
                }
            }
	}
        
        if (zoningclearancefeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("zoninglandusefeecode");
		data["u_feedesc"] = getPrivate("zoninglandusefeedesc");
                var result = page.executeFormattedQuery("SELECT u_seqno from u_locationalfees where code = '"+getPrivate("zoninglandusefeecode")+"'");
                if (parseInt(result.getAttribute("result"))>0) {
                        data["u_seqno"] = result.childNodes.item(0).getAttribute("u_seqno");
                }
                data["u_amount"] = formatNumericAmount(landuseamount);
                data["u_linetotal"] = formatNumericAmount(landuseamount);
		insertTableRowFromArray("T1",data);
	}
        
        
        
}
function computeProcessingFee(){
    var rc = getTableRowCount("T1"),amount=0;
    var zoningprocessingfeerc = 0;
    
    if (getInput("u_projtype") == 'A') {
        amount = (getInputNumeric("u_totflrareabldg") * .65 );
    } else {
        amount = (getInputNumeric("u_totflrareabldg") * 2.25);
    }
    
        for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T1",i)==false) {
                if (getTableInput("T1","u_feecode",i)==getPrivate("zoningprocessingfeecode")) {
                    setTableInputAmount("T1","u_amount",amount,i);
                    setTableInputAmount("T1","u_linetotal",amount * getTableInputNumeric("T1","u_quantity",i),i);
                    zoningprocessingfeerc=i;
                }
            }
	}
        
        if (zoningprocessingfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("zoningprocessingfeecode");
		data["u_feedesc"] = getPrivate("zoningprocessingfeedesc");
                var result = page.executeFormattedQuery("SELECT u_seqno from u_locationalfees where code = '"+getPrivate("zoningprocessingfeecode")+"'");
                if (parseInt(result.getAttribute("result"))>0) {
                        data["u_seqno"] = result.childNodes.item(0).getAttribute("u_seqno");
                }
                data["u_amount"] = formatNumericAmount(amount);
                data["u_linetotal"] = formatNumericAmount(amount);
		insertTableRowFromArray("T1",data);
	}
}
function computeSurveyPermitFee(){
    var rc = getTableRowCount("T1"),amount=0;
    var zoningsurveyfeerc = 0;
    
        amount = (getInputNumeric("u_surveycount") * 100);
    
        for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T1",i)==false) {
                if (getTableInput("T1","u_feecode",i)==getPrivate("zoningsurveyfeecode")) {
                    setTableInputAmount("T1","u_amount",amount,i);
                    setTableInputAmount("T1","u_linetotal",amount * getTableInputNumeric("T1","u_quantity",i),i);
                    zoningsurveyfeerc=i;
                }
            }
	}
        
        if (zoningsurveyfeerc==0) {
		var data = new Array();
		data["u_year"] = getInput("u_year");
		data["u_feecode"] = getPrivate("zoningsurveyfeecode");
		data["u_feedesc"] = getPrivate("zoningsurveyfeedesc");
                var result = page.executeFormattedQuery("SELECT u_seqno from u_locationalfees where code = '"+getPrivate("zoningsurveyfeecode")+"'");
                if (parseInt(result.getAttribute("result"))>0) {
                        data["u_seqno"] = result.childNodes.item(0).getAttribute("u_seqno");
                }
                data["u_amount"] = formatNumericAmount(amount);
                data["u_quantity"] = 1;
                data["u_linetotal"] = formatNumericAmount(amount);
		insertTableRowFromArray("T1",data);
	}
}
function AddZoningApplicant(){
     OpenPopup(1024,350,"./udo.php?&objectcode=u_zoningapplicants","Zoning Applicants");
}

function u_approveGPSLGUZoning() {
        if (isInputEmpty("u_bldgappno")) return false;
        if (isInputEmpty("u_appnature",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_docdate",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_projectname",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_approvedby",null,null,"tab1",3)) return false;
        if (isInputEmpty("u_approveddate",null,null,"tab1",3)) return false;
        if (isInputEmpty("u_assessedby",null,null,"tab1",3)) return false;
        if (isInputEmpty("u_assesseddate",null,null,"tab1",3)) return false;
        if (isInputNegative("u_totalamount",null,null,"tab1",0)) return false;
    
    if (getTableRowCount("T1")==0) {
        page.statusbar.showError("At least one fee must be entered.");
        selectTab("tab1",1);
        return false;
    }
    setInput("docstatus","AP");
    formSubmit();
}

function u_disapproveGPSLGUZoning() {
        setInput("docstatus","DA");
        formSubmit('sc');
}
function u_reassessmentGPSLGUZoning() {
        setInput("docstatus","O");
        formSubmit('sc');
}
