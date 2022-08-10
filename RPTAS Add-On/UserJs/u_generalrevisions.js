// page events
//page.events.add.load('onPageLoadGPSRPTAS');
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
page.elements.events.add.change('onElementChangeGPSRPTAS');
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

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
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSRPTAS');
var isupdate = false;
var pin = "";
function onPageLoadGPSRPTAS() {
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
    
	if (action=="a" || action=="sc") {
                if (isInputEmpty("u_revisionyearfrom")) return false;
                if (isInputEmpty("u_revisionyearto")) return false;
                if (isInputEmpty("u_kind")) return false;
                if (isInputEmpty("u_effdate")) return false;
                if (isInputEmpty("u_barangay")) return false;
                if (isInputNegative("u_rpucount")) return false;
                if (isInputEmpty("u_assessedby")) return false;
                if (isInputEmpty("u_recommendby")) return false;
                if (isInputEmpty("u_approvedby")) return false;
                if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
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
               
		default:
			switch (column) {
                                case "u_revisionyearfrom":
                                case "u_oldbarangay":
                                case "u_kind":
                                    setInput("code",getInput("u_revisionyearfrom")+getInput("u_revisionyearto")+getInput("u_oldbarangay")+"-"+getInput("u_kind"));
                                    setInput("name",getInput("u_revisionyearfrom")+getInput("u_revisionyearto")+getInput("u_oldbarangay")+"-"+getInput("u_kind"));
                                break;
                                case "u_revisionyearto":
                                    if(getInput(column)!=""){
                                        var result = page.executeFormattedQuery("select u_effdate from u_gryears where code = '"+getInput(column)+"'");
                                        if (result.getAttribute("result")!= "-1") {
                                            if (parseInt(result.getAttribute("result"))>0) {
                                                setInput("u_effdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_effdate")));
                                            } else {
                                                setInput("u_effdate","");
                                            }
                                        } else {
                                            setInput("u_effdate","");
                                        }
                                    }
                                    setInput("code",getInput("u_revisionyearfrom")+getInput("u_revisionyearto")+getInput("u_oldbarangay")+"-"+getInput("u_kind"));
                                    setInput("name",getInput("u_revisionyearfrom")+getInput("u_revisionyearto")+getInput("u_oldbarangay")+"-"+getInput("u_kind"));
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
                    switch (column) {
                        case "u_revisionyearfrom":
                        case "u_revisionyearto":
                        case "u_kind":
                        case "u_oldbarangay":
                            setInput("u_rpucount",0);
                            setInput("u_totalareasqm",formatNumericAmount(0));
                            setInput("u_totalassval",formatNumericAmount(0));
                            setInput("u_totalmarketval",formatNumericAmount(0));
                            break;
                    }
    }
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

		case "df_u_tdno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno,docno,u_pin,u_type,u_ownername from (select u_tdno,docno,u_pin, 'Land ' as u_type, if(u_ownertype = 'C',u_ownercompanyname,u_ownername) as u_ownername from u_rpfaas1 where u_cancelled <> 1 and u_pin <> '' order by u_pin) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD No.`ID No.`PIN`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`19`8`32")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;

		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_pin,docno,u_tdno,u_type,u_ownername from (select u_pin,docno,u_tdno, 'Land ' as u_type, if(u_ownertype = 'C',u_ownercompanyname,u_ownername) as u_ownername from u_rpfaas1 where u_cancelled <> 1 and u_pin <> '' order by u_pin) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Reference No.`Type`Declared Owner")); 			
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
     updateBilldate();
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
}

function onTableBeforeEditRowGPSRPTAS(table,row) {
	return true;
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
    var params = Array();
    switch (table) {
        case"T1":
            pin = getTableInput(table,"u_pin",row);
            openfaas();
            params["focus"] = false;
            break;
    }
   
    return params;
}

function u_executeGeneralRevisionGPSRPTAS() {
    if (isInputEmpty("u_revisionyearfrom")) return false;
    if (isInputEmpty("u_revisionyearto")) return false;
    if (isInputEmpty("u_kind")) return false;
    if (isInputEmpty("u_barangay")) return false;
    if (isInputNegative("u_rpucount")) return false;
    if (window.confirm("Execute general revision. Continue?")==false) return false;
    formSubmit('executegr');
    return true;
}

function SearchProperty(){
        if (isInputEmpty("u_revisionyearfrom")) return false;
        if (isInputEmpty("u_kind")) return false;
        if (isInputEmpty("u_oldbarangay")) return false;
        showAjaxProcess();
        if (getInput("u_kind") == "1") {
                var result = page.executeFormattedQuery("SELECT COUNT(*) AS CNT,SUM(U_TOTALAREASQM) AS U_TOTALAREASQM,SUM(U_ASSVALUE) AS U_TOTALASSVAL,SUM(U_MARKETVALUE) AS U_TOTALMARKETVAL FROM U_RPFAAS1 WHERE COMPANY = '"+getGlobal("company")+"' AND BRANCH = '"+getGlobal("branch")+"' AND U_CANCELLED = 0 AND U_REVISIONYEAR = '"+getInput("u_revisionyearfrom")+"' AND U_OLDBARANGAY = '"+getInput("u_oldbarangay")+"'");	
        } else if (getInput("u_kind") == "2") {
                var result = page.executeFormattedQuery("SELECT COUNT(*) AS CNT,SUM(U_TOTALAREASQM) AS U_TOTALAREASQM,SUM(U_ASSVALUE) AS U_TOTALASSVAL,SUM(U_MARKETVALUE) AS U_TOTALMARKETVAL FROM U_RPFAAS2 WHERE COMPANY = '"+getGlobal("company")+"' AND BRANCH = '"+getGlobal("branch")+"' AND U_CANCELLED = 0 AND U_REVISIONYEAR = '"+getInput("u_revisionyearfrom")+"' AND U_OLDBARANGAY = '"+getInput("u_oldbarangay")+"'");	
        } else if (getInput("u_kind") == "3") {
                var result = page.executeFormattedQuery("SELECT COUNT(*) AS CNT,SUM(0) AS U_TOTALAREASQM,SUM(U_ASSVALUE) AS U_TOTALASSVAL,SUM(U_MARKETVALUE) AS U_TOTALMARKETVAL FROM U_RPFAAS3 WHERE COMPANY = '"+getGlobal("company")+"' AND BRANCH = '"+getGlobal("branch")+"' AND U_CANCELLED = 0 AND U_REVISIONYEAR = '"+getInput("u_revisionyearfrom")+"' AND U_OLDBARANGAY = '"+getInput("u_oldbarangay")+"'");	
        }
        if (result.getAttribute("result")!= "-1") {
                if (parseInt(result.getAttribute("result"))>0) {
                        setInput("u_rpucount",result.childNodes.item(0).getAttribute("cnt"));
                        setInput("u_totalareasqm",formatNumericAmount(result.childNodes.item(0).getAttribute("u_totalareasqm")));
                        setInput("u_totalassval",formatNumericAmount(result.childNodes.item(0).getAttribute("u_totalassval")));
                        setInput("u_totalmarketval",formatNumericAmount(result.childNodes.item(0).getAttribute("u_totalmarketval")));
                }
        }
        hideAjaxProcess();
         
        
}

