// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_lastname")) return false;
		if (isInputEmpty("u_firtname")) return false;
		if (getInput("u_middlename")=="") {
			if (window.confirm("Middle Name was not entered. Are you sure patient has no middle name?")==false) {
				focusInput("u_middlename");
				return false;
			}
		}
		//if (isInputEmpty("u_type")) return false;
		
		/*if (getTableInput("T2","u_servicetype")!="") {
			page.statusbar.showError("A Service Type is being added/edited.");
			return false;
		}*/

		if (getTableInput("T1","u_inscode")!="") {
			page.statusbar.showError("An Insurance is being added/edited.");
			return false;
		}
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
}

function onElementValidateGPSHIS(element,column,table,row) {
	var name="";
	switch (table) {
		default:
			switch (column) {
				case "u_lastname":
				case "u_firstname":
				case "u_extname":
				case "u_middlename":
				case "u_titles":
				setInput(column,utils.trim(getInput(column)));
				name = getInput("u_lastname");
				if (getInput("u_firstname")!="") name += ", " + getInput("u_firstname");
				if (getInput("u_middlename")!="") name += " " + getInput("u_middlename");
				if (getInput("u_extname")!="") name += " " + getInput("u_extname");
				if (getInput("u_titles")!="") name += ", " + getInput("u_titles");
				setInput("name",name);
				break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(element,params) {
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (isTableInputEmpty(table,"u_memberid")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_servicetype")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setServiceGPSHIS();
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (isTableInputEmpty(table,"u_memberid")) return false;
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_servicetype")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setServiceGPSHIS();
			break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T2":
			setServiceGPSHIS();
			break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function setServiceGPSHIS() {
	var rc =  getTableRowCount("T2"), specialization="";
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			if (specialization!="") {
				specialization += "/"; 	
			}
			specialization += getTableInput("T2","u_servicetype",i);
		}
	}
	setInput("u_specialization",specialization);	
}

function uploadPhotoGPSHIS() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshPhotoGPSHIS()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,JPG,JPEG,GIF,PNG";
	iframeFileUpload.document.getElementById("basename").value = "photo";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Doctors/" + getInput("code") + "/";
	showPopupFrame('popupFrameFileUpload');
}

function refreshPhotoGPSHIS() {
	var src = document.images['PhotoImg'].src;
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}
