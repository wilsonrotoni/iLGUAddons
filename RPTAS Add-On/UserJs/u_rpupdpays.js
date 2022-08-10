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
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
page.tables.events.add.select('onTableSelectRowGPSRPTAS');

function onPageLoadGPSRPTAS() {
	if (getVar("formSubmitAction")=="a") {
		try {
                    //alert(window.opener.getInput("u_tin"));
                    if (window.opener.getVar("objectcode")=="U_RPTAXBILL") {
                        setInput("u_tin",window.opener.getInput("u_tin"),true);
                        var result = page.executeFormattedQuery("select username from users where userid = '"+window.opener.getTableInput("T61","userid")+"'");
                        if (parseInt(result.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                setInput("u_authorizedby",result.childNodes.item(xxx).getAttribute("username"));
                            }
                        }
			setInput("u_pin",window.opener.getTableInput("T2","u_pin",window.opener.getTableSelectedRow("T2")),true);
			setInput("u_tdno",window.opener.getTableInput("T2","u_arpno",window.opener.getTableSelectedRow("T2")),true);
                        focusInput("u_tin");
                    }
		} catch ( e ) {
		}
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_date")) return false;
		//if (isInputEmpty("u_pin")) return false;
		if (isInputEmpty("u_declaredowner")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSRPTAS(action,sucess,error) {
	if (sucess) {
		try {
			//if (window.opener.getVar("objectcode")=="u_motorregserials") {
                                window.opener.resetTableRow("T1");
				window.opener.setInput("u_tin",window.opener.getInput("u_tin"),true);
				window.close();
			//}
		} catch (theError) {
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
	var data = new Array(),tax=0,sef=0,penalty=0,sefpenalty=0;
	switch (table) {
		case "T1":
			switch (column) {
				case "u_yrpaid2":
					if (getTableInputNumeric(table,"u_yrpaid2",row)<getTableInputNumeric(table,"u_yrfr2",row) && getTableInputNumeric(table,"u_yrpaid2",row)!=0) {
						page.statusbar.showError("Year of Last Payment cannot be less than Year From");
						return false;
					}
					if (getTableInputNumeric(table,"u_yrpaid2",row)>getTableInputNumeric(table,"u_yrto2",row) && getTableInputNumeric(table,"u_yrto2",row)!=0) {
						page.statusbar.showError("Year of Last Payment cannot be more than Year To");
						return false;
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_tdno":
				case "u_pin":
					setTimeout("getTaxDues()",1000);
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
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
	switch (Id) {
		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_pin, docno, type, u_ownername from (select u_pin,docno, 'Land' as type, u_ownername from u_rpfaas1 union all select u_pin,docno, 'Building' as type, u_ownername from u_rpfaas2 union all select u_pin,docno, 'Machinery' as type, u_ownername from u_rpfaas3) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Arp No.`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
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
	params["focus"] = false;
	return params;
}

function getTaxDues() {
	var data = new Array();
	clearTable("T1",true);
	//if (getInput("u_pin")!="") {
		var result = page.executeFormattedQuery("select 'L' as u_kind, docno, u_tdno, u_effqtr, u_effyear, u_expqtr, u_expyear, u_assvalue, u_bilyear, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location, u_ownername from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno like '%"+getInput("u_tdno")+"%' union all select 'B' as u_kind, docno, u_tdno, u_effqtr, u_effyear, u_expqtr, u_expyear, u_assvalue, u_bilyear, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location, u_ownername from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and  u_varpno like '%"+getInput("u_tdno")+"%' union all select 'M' as u_kind, docno, u_tdno, u_effqtr, u_effyear, u_expqtr, u_expyear, u_assvalue, u_bilyear, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location, u_ownername from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_varpno like '%"+getInput("u_tdno")+"%'");
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				if (xxx==0) {
					setInput("u_kind",result.childNodes.item(xxx).getAttribute("u_kind"));
					setInput("u_declaredowner",result.childNodes.item(xxx).getAttribute("u_ownername"));
					setInput("u_location",result.childNodes.item(xxx).getAttribute("u_location"));
				}	
				data["u_arpno"] = result.childNodes.item(xxx).getAttribute("docno");
				data["u_kind"] = result.childNodes.item(xxx).getAttribute("u_kind");
				data["u_tdno"] = result.childNodes.item(xxx).getAttribute("u_tdno");
				data["u_qtrfr"] = result.childNodes.item(xxx).getAttribute("u_effqtr");
				data["u_qtrto"] = result.childNodes.item(xxx).getAttribute("u_expqtr");
				data["u_yrfr"] = result.childNodes.item(xxx).getAttribute("u_effyear");
				data["u_yrto"] = result.childNodes.item(xxx).getAttribute("u_expyear");
				data["u_yrpaid"] = result.childNodes.item(xxx).getAttribute("u_bilyear");
				data["u_qtrfr2"] = result.childNodes.item(xxx).getAttribute("u_effqtr");
				data["u_qtrto2"] = result.childNodes.item(xxx).getAttribute("u_expqtr");
				data["u_yrfr2"] = result.childNodes.item(xxx).getAttribute("u_effyear");
				data["u_yrto2"] = result.childNodes.item(xxx).getAttribute("u_expyear");
				data["u_yrpaid2"] = result.childNodes.item(xxx).getAttribute("u_bilyear");
				data["u_assvalue"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_assvalue"));
				insertTableRowFromArray("T1",data);
				//assvalue+=parseFloat(result.childNodes.item(xxx).getAttribute("u_assvalue"));
			}
		}			
	//}
}


