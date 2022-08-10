// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUPurchasing');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctg');
//page.elements.events.add.validate('onElementValidateGPSLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctg');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
//page.elements.events.add.change('onElementChangeGPSLGUAcctg');
//page.elements.events.add.click('onElementClickGPSLGUAcctg');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctg');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctg');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');

function uploadPhoto() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshPhoto()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,JPG,JPEG,GIF,PNG";
	iframeFileUpload.document.getElementById("basename").value = "photo";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/ITEMS/" + getInput("code") + "/";
	showPopupFrame('popupFrameFileUpload');
}

function refreshPhoto() {
	var src = document.images['PhotoImg'].src;
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function onPageLoadGPSLGUAcctg() {
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUPurchasing(action) {
	 if (action=="a" || action=="sc") {
                    if (isInputEmpty("u_uom")) return false;
                    if (isInputEmpty("u_costmethod")) return false;
                    if (isInputEmpty("u_itemgroup")) return false;
                    if(getInput("u_isfixedasset") == 1) if (isInputEmpty("u_faclass")) return false;
        } 
        return true;
}

function onCFLGPSLGUAcctg(Id) {
	return true;
}

function onCFLGetParamsGPSLGUAcctg(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUAcctg() {
}

function onElementFocusGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUAcctg(element,event,column,table,row) {
}

function onElementValidateGPSLGUAcctg(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "code":
					if (getTableInput(table,"code").length<4) {
						setTableInput(table,"code",getTableInput(table,"code").padL(4,"0"));
					}
					break;
				case "u_glacctno":
				case "u_glacctname":
					if (getTableInput(table,column)!="") {
						if (column=="u_glacctno") {
							if (getTableInput(table,"u_glacctno").length==8) {
								var s1="",s2="",s3="",s4="";
								s1 = getTableInput(table,"u_glacctno").substr(0,1);
								s2 = getTableInput(table,"u_glacctno").substr(1,2);
								s3 = getTableInput(table,"u_glacctno").substr(3,2);
								s4 = getTableInput(table,"u_glacctno").substr(5,3);
								setTableInput(table,"u_glacctno",s1+"-"+s2+"-"+s3+"-"+s4);
							}
							result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1 and formatcode = '"+getTableInput(table,column)+"'");	
						} else  result = page.executeFormattedQuery("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1 and acctname like '"+utils.addslashes(getTableInput(table,column))+"%'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
								setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
							} else {
								setTableInput(table,"u_glacctno","");
								setTableInput(table,"u_glacctname","");
								page.statusbar.showError("Invalid G/L Account.");	
								return false;
							}
						} else {
							setTableInput(table,"u_glacctno","");
							setTableInput(table,"u_glacctname","");
							page.statusbar.showError("Error retrieving chartofaccount record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_glacctno","");
						setTableInput(table,"u_glacctname","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSLGUAcctg(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	switch (id) {	
		case "df_u_glacctnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select formatcode, acctname from chartofaccounts where ctrlacct=0 and postable=1 and budget=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account No.`G/L Account Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_glacctnameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select acctname, formatcode from chartofaccounts where ctrlacct=0 and postable=1 and budget=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("G/L Account Description`G/L Account No.")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSLGUAcctg(table) {
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {	
		case "T1":
			if (isTableInputEmpty(table,"u_uom")) return false;
			if (isTableInputEmpty(table,"u_itemgroup")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
}

