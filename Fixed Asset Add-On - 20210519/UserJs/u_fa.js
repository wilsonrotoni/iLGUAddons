// page events
//page.events.add.load('onPageLoadGPSFixedAsset');
//page.events.add.resize('onPageResizeGPSFixedAsset');
page.events.add.submit('onPageSubmitGPSFixedAsset');
//page.events.add.cfl('onCFLGPSFixedAsset');
//page.events.add.cflgetparams('onCFLGetParamsGPSFixedAsset');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFixedAsset');

// element events
//page.elements.events.add.focus('onElementFocusGPSFixedAsset');
//page.elements.events.add.keydown('onElementKeyDownGPSFixedAsset');
page.elements.events.add.validate('onElementValidateGPSFixedAsset');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFixedAsset');
//page.elements.events.add.changing('onElementChangingGPSFixedAsset');
//page.elements.events.add.change('onElementChangeGPSFixedAsset');
//page.elements.events.add.click('onElementClickGPSFixedAsset');
//page.elements.events.add.cfl('onElementCFLGPSFixedAsset');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFixedAsset');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFixedAsset');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFixedAsset');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFixedAsset');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFixedAsset');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFixedAsset');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFixedAsset');
page.tables.events.add.delete('onTableDeleteRowGPSFixedAsset');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFixedAsset');
page.tables.events.add.select('onTableSelectRowGPSFixedAsset');

function onPageLoadGPSFixedAsset() {
}

function onPageResizeGPSFixedAsset(width,height) {
}

function onPageSubmitGPSFixedAsset(action) {
	if (action=="a" || action=="sc") {
		if (getTableRowCount("T5",true)>0) {
			if (getInputNumeric("u_deptweightperc")!=100) {
				page.statusbar.showError("Weight Percentage for multiple departments must be 100 if departments are selected.");
				return false;
			}
			if (getTableInput("T5","u_department")!="") {
				page.statusbar.showError("A multiple department item is currently being added/updated.");
				return false;
			}
		}
	}
	return true;
}

function onCFLGPSFixedAsset(Id) {
	return true;
}

function onCFLGetParamsGPSFixedAsset(Id,params) {
	return params;
}

function onTaskBarLoadGPSFixedAsset() {
}

function onElementFocusGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSFixedAsset(element,event,column,table,row) {
}

function onElementValidateGPSFixedAsset(element,column,table,row) {
	var result;
	switch (table) {
		case "T5":
			switch (column) {
				case "u_projcode":
					if (getTableInput("T5","u_projcode") != "") { 
						result = ajaxxmlvalidateprojects(element.value);
						if (result.getAttribute("result") == '0') {
							setStatusMsg('Invalid Project!');
							return false;
						}	
						setTableInput("T5","u_projcode",result.childNodes.item(0).getAttribute("projcode"));
					}
					break;
				case "u_glacctno":
					if (getTableInput(table,"u_glacctno")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value,"POSTABLE:1;CTRLACCT:0;CASHACCT:0;CASHINBANKACCT:0");
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_glacctname","");
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setTableInput(table,"u_glacctno",result.childNodes.item(0).getAttribute("formatcode"));
						setTableInput(table,"u_glacctname",result.childNodes.item(0).getAttribute("acctname"));
					} else {
						setTableInput(table,"u_glacctname","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSFixedAsset(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementChangeGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementClickGPSFixedAsset(element,column,table,row) {
	return true;
}

function onElementCFLGPSFixedAsset(element) {
	return true;
}

function onElementCFLGetParamsGPSFixedAsset(id,params) {
	switch (id) {	
		case "df_u_glacctnoT5": 
			params["params"] = "POSTABLE:1;CTRLACCT:0;CASHACCT:0;CASHINBANKACCT:0";
			break;
	}
	return params;
}

function onTableResetRowGPSFixedAsset(table) {
}

function onTableBeforeInsertRowGPSFixedAsset(table) {
	switch (table) {
		case "T4":
			uploadAttachment();
			return false;
			break;	
		case "T5": 
			if (isTableInputEmpty(table,"u_department")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputNegative(table,"u_weightperc")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSFixedAsset(table,row) {
	switch (table) {
		case "T5": computeWeightPercTotalGPSFixedAsset(); break;
	}
}

function onTableBeforeUpdateRowGPSFixedAsset(table,row) {
	switch (table) {
		case "T5": 
			if (isTableInputEmpty(table,"u_department")) return false;
			if (isTableInputEmpty(table,"u_glacctno")) return false;
			if (isTableInputNegative(table,"u_weightperc")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSFixedAsset(table,row) {
	switch (table) {
		case "T5": computeWeightPercTotalGPSFixedAsset(); break;
	}
}

function onTableBeforeDeleteRowGPSFixedAsset(table,row) {
	switch (table) {
		case "T4":
			if (window.confirm("Delete this attachment. Continue?")==false) return false;
			if (ajaxBOMDeletePicture(getTableInput("T4","u_filepath",row))) {
				setTimeout("formEdit()",1000);
			}	
			return false;
			break;	
	}
	return true;
}

function onTableDeleteRowGPSFixedAsset(table,row) {
	switch (table) {
		case "T5": computeWeightPercTotalGPSFixedAsset(); break;
	}
}

function onTableBeforeSelectRowGPSFixedAsset(table,row) {
	return true;
}

function onTableSelectRowGPSFixedAsset(table,row) {
	var params = new Array();
	switch (table) {
		case "T4":
			document.images['PictureImg'].src = getTableInput("T4","u_filepath",row);			
			break;
	}
	return params;
}

function computeWeightPercTotalGPSFixedAsset() {
	var perctotal=0, rc = getTableRowCount("T5");
	for (idx = 1; idx <= rc; idx++) {
		if (isTableRowDeleted("T5",idx)==false) {
			perctotal += getTableInputNumeric("T5","u_weightperc",idx);
		}	
	}
	setInputPercent("u_deptweightperc",perctotal);
}

function uploadPhoto() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshPhoto()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,JPG,JPEG,GIF,PNG";
	iframeFileUpload.document.getElementById("basename").value = "photo";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/Fixed Asset/" + getInput("code") + "/" + getInput("u_empid") + "/";
	showPopupFrame('popupFrameFileUpload');
}

function refreshPhoto() {
	var src = document.images['PhotoImg'].src;
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function uploadAttachment() {
	iframeFileUpload2.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload2.document.getElementById("extensions").value = "jpeg,jpg,gif,png";
	iframeFileUpload2.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/Fixed Asset/" + getInput("code") + "/" + getInput("u_empid") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload2',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload2');
	setTimeout("formEdit()",1000);
}



