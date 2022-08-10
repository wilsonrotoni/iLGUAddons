// page events
//page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
page.events.add.submitreturn('onPageSubmitReturnGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSRPTAS');

// element events
//page.elements.events.add.focus('onElementFocusGPSRPTAS');
//page.elements.events.add.keydown('onElementKeyDownGPSRPTAS');
page.elements.events.add.validate('onElementValidateGPSRPTAS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSRPTAS');
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
//page.elements.events.add.change('onElementChangeGPSRPTAS');
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
//page.tables.events.add.select('onTableSelectRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
        if (action=="a" || action=="sc") {
            if (isInputEmpty("u_tdno")) return false;
            if (isInputEmpty("u_datecancel")) return false;
            if (isInputEmpty("u_ownername")) return false;
            if (isInputNegative("u_assvalue")) return false;
            if (isInputNegative("u_endyear")) return false;
           
        }
	return true;
}
function onPageSubmitReturnGPSRPTAS(action,sucess,error) {
	
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
}

function onElementValidateGPSRPTAS(element,column,table,row) {
        switch (table) {
		case "T1":
			
                break;
                        
                default :
                    switch (column) {
                            case "u_tdno":
                                if(getInput("searchby")== "L") var result = page.executeFormattedQuery("select u_effyear,docno,u_trxcode,u_pin,u_barangay,'Land' as u_kind,u_assvalue,u_marketvalue,if(u_barangay!='',u_barangay,u_oldbarangay) as u_location,u_ownername,u_owneraddress,u_totalareasqm,b.name as u_oldbarangay from u_rpfaas1 a left join u_oldbarangays b on a.u_oldbarangay = b.code where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getInput(column)+"' and u_cancelled <> 1");
                                else if(getInput("searchby")== "B") var result = page.executeFormattedQuery("Select u_effyear,docno,u_trxcode,u_pin,u_barangay,'Building' as u_kind,u_assvalue,u_marketvalue,if(u_barangay!='',u_barangay,u_oldbarangay) as u_location,u_ownername,u_owneraddress,u_totalareasqm,b.name as u_oldbarangay  from u_rpfaas2 a left join u_oldbarangays b on a.u_oldbarangay = b.code where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getInput(column)+"' and u_cancelled <> 1");
                                else var result = page.executeFormattedQuery("Select u_effyear,docno,u_trxcode,u_pin,u_barangay,'Machine' as u_kind,u_assvalue,u_marketvalue,if(u_barangay!='',u_barangay,u_oldbarangay) as u_location ,u_ownername,u_owneraddress,0 as u_totalareasqm,b.name as u_oldbarangay  from u_rpfaas3 a left join u_oldbarangays b on a.u_oldbarangay = b.code where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno='"+getInput(column)+"' and u_cancelled <> 1");
                                    if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                               
                                                setInput("u_appno",result.childNodes.item(0).getAttribute("docno"));
                                                setInput("u_effyear",result.childNodes.item(0).getAttribute("u_effyear"));
                                                setInput("u_ownername",result.childNodes.item(0).getAttribute("u_ownername"));
                                                setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_owneraddress"));
                                                setInput("u_barangay",result.childNodes.item(0).getAttribute("u_location"));
                                                setInput("u_kind",result.childNodes.item(0).getAttribute("u_kind"));
                                                setInputAmount("u_area",result.childNodes.item(0).getAttribute("u_totalareasqm"));
                                                setInputAmount("u_assvalue",result.childNodes.item(0).getAttribute("u_assvalue"));
                                            }else{
                                                setInput("u_appno","");
                                                setInput("u_effyear","");
                                                setInput("u_tdno","");
                                                setInput("u_ownername","");
                                                setInput("u_owneraddress","");
                                                setInput("u_barangay","");
                                                setInput("u_kind","");
                                                setInput("u_area","");
                                                setInput("u_assvalue","");
                                                page.statusbar.showError("Invalid ARP No.");	
                                                return false;
                                            }
                                    } else {
                                                setInput("u_appno","");
                                                setInput("u_effyear","");
                                                setInput("u_tdno","");
                                                setInput("u_ownername","");
                                                setInput("u_owneraddress","");
                                                setInput("u_barangay","");
                                                setInput("u_kind","");
                                                setInputAmount("u_area",0);
                                                setInputAmount("u_assvalue",0);
                                        page.statusbar.showError("Error checking record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                    }
                                break;
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
                        
                    }
                    
                    computeTotalMarketAssessedValueGPSRPTAS();
                break;
        }
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
        switch (Id) {
            case "df_u_tdno":
                        if(getInput("searchby")=="L"){
                                params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,'Land' as kind, u_pin, u_ownername from u_rpfaas1 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                                params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.`Kind`PIN`Declared Owner")); 			
                                params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
                                params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                                break;
                        }else if(getInput("searchby")=="B"){
                                params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,'Building' as kind, u_pin, u_ownername from u_rpfaas2 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                                params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.Kind`PIN`Declared Owner")); 			
                                params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
                                params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                                break;
                        }else{
                                params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("Select u_varpno,'Machinery' as kind, u_pin, u_ownername from u_rpfaas3 where  docstatus in('Assessed','Approved') and u_cancelled <> 1" ),true); 
                                params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ARP No.`Kind`PIN`Declared Owner")); 			
                                params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
                                params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
                                break;
                        }
        }
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
        switch (table) {
		case "T1":
			    if (isTableInputEmpty(table,"u_tdno")) return false;
                            if (isTableInputNegative(table,"u_pin")) return false;
            }   
        return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
        switch (table) {
            case "T1": computeTotalMarketAssessedValueGPSRPTAS(); break;
	}	
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
        switch (table) {
		case "T1":
                            if (isTableInputEmpty(table,"u_tdno")) return false;
                            if (isTableInputNegative(table,"u_pin")) return false;
            }   
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
    switch (table) {
            case "T1": computeTotalMarketAssessedValueGPSRPTAS(); break;
    }	
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
    switch (table) {
            case "T1": computeTotalMarketAssessedValueGPSRPTAS(); break;
    }	
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
}

function computeTotalMarketAssessedValueGPSRPTAS() {
	var rc = getTableRowCount("T1"),marketvalue=0,assvalue=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                        if (getTableInputNumeric("T1","u_selected",i)=="1") {
                            assvalue+= getTableInputNumeric("T1","u_assvalue",i);
                            marketvalue+= getTableInputNumeric("T1","u_marketvalue",i);
                        }
		}
	}	
	setInputAmount("u_totalassvalue",assvalue);
	setInputAmount("u_totalmarketvalue",marketvalue);
}
function openupdpays() {
	OpenPopup(1024,600,"./udo.php?&objectcode=u_rpupdpays&sf_keys="+getInput("code")+"&formAction=e","UpdPays");	
}
function OpenLnkBtnu_TIN() {
	OpenPopup(1024,600,"./udo.php?&objectcode=u_rpfaasmds&sf_keys="+getInput("u_tin")+"&formAction=e","UpdPays");	
}
function CancelFAASGPSRPTAS() {
    if (window.confirm("You cannot change this document after you have cancel it. Continue?")==false) return false;
    setInput("docstatus","C");
    formSubmit('sc');
}