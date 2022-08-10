// page events
page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
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
//page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
//page.tables.events.add.select('onTableSelectRowGPSRPTAS');
var isupdate = false;
function onPageLoadGPSRPTAS() {
    if (getVar("formSubmitAction")=="a" || getVar("formSubmitAction")=="sc" ) {
		
			try {
				if (window.opener.getVar("objectcode")=="U_RPTAXES"){
                                    setInput("u_ownername",window.opener.getInput("u_declaredowner"),true); 
                                    setInput("u_ownertin",window.opener.getInput("u_tin"),true);
                                    setTableInput("T1","u_ownername",window.opener.getInput("u_declaredowner")); 
                                    setTableInput("T1","u_tin",getInput("u_ownertin"));
                                }
				if (window.opener.getVar("objectcode")=="U_RPFAAS1" || window.opener.getVar("objectcode")=="U_RPFAAS2" || window.opener.getVar("objectcode")=="U_RPFAAS3" ){
                                    switch (window.opener.getVar("objectcode")) {
                                        case "U_RPFAAS1":
                                            setTableInput("T1","u_kind","L");
                                            break ;
                                        case "U_RPFAAS2":
                                            setTableInput("T1","u_kind","B");
                                            break ;
                                        case "U_RPFAAS3":
                                            setTableInput("T1","u_kind","M");
                                            break ;
                                    }
                                    setInput("u_ownername",window.opener.getInput("u_ownercompanyname"),true); 
                                    setInput("u_ownertin",window.opener.getInput("u_ownertin"),true);
                                    setTableInput("T1","u_ownername",window.opener.getInput("u_ownercompanyname")); 
                                    setTableInput("T1","u_curtdno",window.opener.getInput("u_tdno")); 
                                    setTableInput("T1","u_tin",getInput("u_ownertin"));
                                }
			} catch (theError) {
			}
		
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
    var rc =  getTableRowCount("T1"),count = 0 ;
    if (action=="a" || action=="sc") {
                if(getInput("docstatus")=="C")return false;
		if (isInputEmpty("u_ownertin")) return false;
		if (isInputEmpty("u_ownername")) return false;
                if(getTableRowCount("T1")==0){
                     alert("Posting Details cannot be null");
                     return false;
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
}

function onElementValidateGPSRPTAS(element,column,table,row) {
        switch (table) {
                    case "T1":
                        switch (column) {
                            case "u_tdno":
                                var rc =  getTableRowCount("T2");
                                    for (xxx = 1; xxx <= rc; xxx++) {
                                        if (isTableRowDeleted("T2",xxx)==false) {
                                                setTableInput("T2","u_tdnumber",getTableInput(table,column),xxx);    
                                        }
                                    }
                            break;
                            case "u_curtdno":
                                if (getTableInput(table,column)!="") {
                                         var result = page.executeFormattedQuery("select docno,'L' as kind, u_barangay from u_rpfaas1 where u_tdno='"+getTableInput(table,column)+"' union all select docno,'B' as kind, u_barangay from u_rpfaas2 where u_tdno='"+getTableInput(table,column)+"' union all select docno,'M' as kind, u_barangay from u_rpfaas3 where u_tdno='"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_kind",result.childNodes.item(0).getAttribute("kind"));
								setTableInput(table,"u_barangay",result.childNodes.item(0).getAttribute("u_barangay"));
								setTableInput(table,"u_tdno",getTableInput(table,column));
                                                                var data = new Array();                                
                                                                var result1 = page.executeFormattedQuery("select u_class,u_actualuse from u_rpfaas1a where u_arpno='"+result.childNodes.item(0).getAttribute("docno")+"' union all select u_class,u_actualuse from u_rpfaas2a where u_arpno='"+result.childNodes.item(0).getAttribute("docno")+"' ");	
                                                                    clearTable("T2",true);                                    
                                                                    for (xxx1 = 0; xxx1 < result1.childNodes.length; xxx1++) {
                                                                        if(getTableInput("T1","u_tdno")==''){
                                                                             data["u_tdnumber"] = getTableInput(table,column);
                                                                        }else{
                                                                             data["u_tdnumber"] = getTableInput("T1","u_tdno");
                                                                        }
                                                                        data["u_class"] = result1.childNodes.item(xxx1).getAttribute("u_class");
                                                                        data["u_actualuse"] = result1.childNodes.item(xxx1).getAttribute("u_actualuse");
                                                                        insertTableRowFromArray("T2",data);
                                                                    } 
							} else {
								setTableInput(table,"u_kind","");
								setTableInput(table,"u_barangay","");
								//setTableInputAmount(table,"u_amount",0);
								page.statusbar.showError("Invalid Data");	
								return false;
							}
						} else {
							setTableInput(table,"u_kind","");
							setTableInput(table,"u_barangay","");
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                } else {
						setTableInput(table,"u_kind","");
						setTableInput(table,"u_barangay","");
                                }

                            break;
                            case "u_yearto":
                                if(getTableInput(table,"u_yearfr",row) > getTableInput(table,"u_yearto",row)){
                                     alert("Year from must be less than or equal to year to"); 
                                     setTableInput(table,"u_yearfr",0,row);
                                     setTableInput(table,"u_yearto",0,row);
                                     return false;
                                }
                                    setTableInput(table,"u_years",(getTableInputNumeric(table,"u_yearto",row) - getTableInputNumeric(table,"u_yearfr",row))  +  1,row);
                                break;
                        }
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
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
    switch (Id) {
		
		case "df_u_ownertin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_ownertin,u_varpno, u_ownername from (select u_varpno,u_ownertin, u_ownername from u_rpfaas1 union all select u_varpno,u_ownertin, u_ownername from u_rpfaas2 union all select u_varpno,u_ownertin, u_ownername from u_rpfaas3) as x")); // group by u_ownertin
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TIN`Arp No`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
                case "df_u_curtdnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno,u_pin,kind,u_ownername from (select u_tdno,u_pin,'LAND' as kind,u_ownername from u_rpfaas1 where u_tdno <> '' union all select u_tdno,u_pin,'BUILDING' as kind,u_ownername from u_rpfaas2 where u_tdno <> '' union all select u_tdno,u_pin,'MACHINERY' as kind,u_ownername from u_rpfaas3 where u_tdno <> '') as x"));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD`PIN`Kind`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
                case "df_u_tdnumberT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_tdno,a.u_ownername from u_rpprevpostingitems a inner join u_rpprevposting b on a.docid = b.docid and a.company = b.company and a.branch = b.branch  where b.docno = '"+getInput("docno")+"'"));
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		
	}	
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
            switch (table) {
                        case "T1": 
                                if(getInput("docstatus")=="C")return false;
                                if (isTableInputEmpty(table,"u_kind")) return false;
                                if (isTableInputEmpty(table,"u_tdno")) return false;
                                if (isTableInputEmpty(table,"u_curtdno")) return false;
                                if (isTableInputEmpty(table,"u_barangay")) return false;
                                if (isTableInputEmpty(table,"u_ownername")) return false;
                                if (isTableInputNegative(table,"u_assvalue")) return false;
                                if (isTableInputNegative(table,"u_yearfr")) return false;
                                if (isTableInputNegative(table,"u_yearto")) return false;
                                break;
                        case "T2": 
                                if (isTableInputEmpty(table,"u_class")) return false;
                                if (isTableInputEmpty(table,"u_actualuse")) return false;
                                if (isTableInputNegative(table,"u_assvalue")) return false;
                                break;
                    }
    return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
    switch (table) {
		case "T1":
		case "T2":
			isupdate = true;
                        setTableInputDefault(table,"u_tdno",getTableInput(table,"u_tdno",row),row);
                        setTableInputDefault(table,"u_barangay",getTableInput(table,"u_barangay",row),row);
                        setTableInputDefault(table,"u_curtdno",getTableInput(table,"u_curtdno",row),row);
                        setTableInputDefault(table,"u_kind",getTableInput(table,"u_kind",row),row);
                        setTableInputDefault(table,"u_ownername",getTableInput(table,"u_ownername",row),row);
                        setTableInputDefault(table,"u_tin",getTableInput(table,"u_tin",row),row);
                        break;
	}
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
    switch (table) {
		case "T1": 
                        if(getInput("docstatus")=="C")return false;
			if (isTableInputEmpty(table,"u_kind")) return false;
			if (isTableInputEmpty(table,"u_tdno")) return false;
                        if (isTableInputEmpty(table,"u_curtdno")) return false;
                        if (isTableInputEmpty(table,"u_barangay")) return false;
			if (isTableInputEmpty(table,"u_ownername")) return false;
                        if (isTableInputNegative(table,"u_assvalue")) return false;
                        if (isTableInputNegative(table,"u_yearfr")) return false;
                        if (isTableInputNegative(table,"u_yearto")) return false;
                        break;
                case "T2": 
                        if (isTableInputEmpty(table,"u_class")) return false;
                        if (isTableInputEmpty(table,"u_actualuse")) return false;
                        if (isTableInputNegative(table,"u_assvalue")) return false;
                        break;        

                break;
                        
            }
    return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
    switch (table) {
		case "T1":
		case "T2":
			isupdate = true;
                        setTableInputDefault(table,"u_ownername",getTableInput(table,"u_ownername",row),row);
                        setTableInputDefault(table,"u_tin",getTableInput(table,"u_tin",row),row);
                        break;
	}
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
}

function u_newfaas() {

    if(isupdate){
          setStatusMsg("Table was change please save/update first.",4000,1);
          return false;   
     }else{
        if(confirm("Click ok to continue..")){
            try {
                formSubmit('newfaas');
                window.opener.resetTableRow("T1");
                window.opener.setInput("u_tin",window.opener.getInput("u_tin"),true);
                window.close();
            } catch (theError) {
            }
         }
     }
     return true;
}

