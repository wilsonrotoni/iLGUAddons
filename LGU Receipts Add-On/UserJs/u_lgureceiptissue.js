// page events
//page.events.add.load('onPageLoadGPSLGUReceipts');
//page.events.add.resize('onPageResizeGPSLGUReceipts');
//page.events.add.submit('onPageSubmitGPSLGUReceipts');
//page.events.add.cfl('onCFLGPSLGUReceipts');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUReceipts');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUReceipts');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUReceipts');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUReceipts');
page.elements.events.add.validate('onElementValidateGPSLGUReceipts');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUReceipts');
//page.elements.events.add.changing('onElementChangingGPSLGUReceipts');
//page.elements.events.add.change('onElementChangeGPSLGUReceipts');
//page.elements.events.add.click('onElementClickGPSLGUReceipts');
//page.elements.events.add.cfl('onElementCFLGPSLGUReceipts');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUReceipts');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUReceipts');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUReceipts');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUReceipts');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUReceipts');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUReceipts');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUReceipts');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUReceipts');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUReceipts');
//page.tables.events.add.select('onTableSelectRowGPSLGUReceipts');

function onPageLoadGPSLGUReceipts() {
}

function onPageResizeGPSLGUReceipts(width,height) {
}

function onPageSubmitGPSLGUReceipts(action) {
	return true;
}

function onCFLGPSLGUReceipts(Id) {
	return true;
}

function onCFLGetParamsGPSLGUReceipts(id,params) {
        
	return params;
}

function onTaskBarLoadGPSLGUReceipts() {
}

function onElementFocusGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUReceipts(element,event,column,table,row) {
}

function onElementValidateGPSLGUReceipts(element,column,table,row) {
        switch (table) {
            default:
                switch (column) {
                    case "code":
                            if (getInput("u_isbrgy") == 1) var result = page.executeFormattedQuery("select name as name,code as Id from u_barangays where code='"+getInput(column)+"'");
                            else     var result = page.executeFormattedQuery("select username as name,userid as Id from users where userid='"+getInput(column)+"' and isvalid = 1");
                            if (result.getAttribute("result")!= "-1") {
                                if (parseInt(result.getAttribute("result"))>0) {
                                   setInput("name",result.childNodes.item(0).getAttribute("name"));
                                   var user = page.executeFormattedSearch("select code from u_lgureceiptissue where company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' and code='" + getInput("code") + "'");
                                   
                                    if(user != "" && getInput("code") != "" ) {
                                            setKey("keys",user+"`0");
                                            formSubmit('e',null,null,null,true);
                                    }
                                }else{
                                setInput("name","");
                                setInput("code","");
                                page.statusbar.showError("Invalid Record");	
                                return false;
                                }
                            }else{
                                setInput("name","");
                                setInput("code","");
                               page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                               return false;
                            }
                    break;
                }
                break;
        }
	return true;
}

function onElementGetValidateParamsGPSLGUReceipts(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUReceipts(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUReceipts(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUReceipts(element,params) {
        var params = new Array();
            switch (element) {
                case "df_code":
                    if(getInput("u_isbrgy") == 1)   params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name,'' from u_barangays")); 
                    else   params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select userid,username,groupid from users where isvalid = 1")); 
                    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ID`Name`Group")); 
                    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50`30")); 			
                    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 	
                    break;
        }
	return params;
}

function onTableResetRowGPSLGUReceipts(table) {
}

function onTableBeforeInsertRowGPSLGUReceipts(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUReceipts(table,row) {
}

function onTableBeforeUpdateRowGPSLGUReceipts(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUReceipts(table,row) {
}

function onTableBeforeDeleteRowGPSLGUReceipts(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUReceipts(table,row) {
}

function onTableBeforeSelectRowGPSLGUReceipts(table,row) {
	return true;
}

function onTableSelectRowGPSLGUReceipts(table,row) {
}
function formIssueReceipts(action){
        OpenPopup(550,350,"./udo.php?&objectcode=u_lgureceiptcashierissue&formAction=e","IssueOR");
}
function formReturnReceipts(action){
        OpenPopup(550,300,"./udo.php?&objectcode=u_lgureceiptcashierreturn&formAction=e","ReturnOR");
}
function formIssueMultipleReceipts(action){
        OpenPopup(1024,600,"./udo.php?&objectcode=u_lgureceiptmultipleissue&formAction=e","IssueMultipleOR");
}

