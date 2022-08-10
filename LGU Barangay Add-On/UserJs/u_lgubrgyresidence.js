// page events
//page.events.add.load('onPageLoadGPSLGUBarangay');
//page.events.add.resize('onPageResizeGPSLGUBarangay');
//page.events.add.submit('onPageSubmitGPSLGUBarangay');
//page.events.add.cfl('onCFLGPSLGU Barangay');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUBarangay');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUBarangay');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUBarangay');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUBarangay');
//page.elements.events.add.validate('onElementValidateGPSLGUBarangay');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUBarangay');
//page.elements.events.add.changing('onElementChangingGPSLGUBarangay');
//page.elements.events.add.change('onElementChangeGPSLGUBarangay');
//page.elements.events.add.click('onElementClickGPSLGUBarangay');
//page.elements.events.add.cfl('onElementCFLGPSLGUBarangay');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUBarangay');

// table events
//page.tables.events.add.reset('onTableResetRowGPSLGUBarangay');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUBarangay');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUBarangay');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUBarangay');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUBarangay');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUBarangay');
//page.tables.events.add.delete('onTableDeleteRowGPSLGUBarangay');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUBarangay');
//page.tables.events.add.select('onTableSelectRowGPSLGUBarangay');

function onPageLoadGPSLGUBarangay() {
}

function onPageResizeGPSLGUBarangay(width,height) {
}

function onPageSubmitGPSLGUBarangay(action) {
	return true;
}

function onCFLGPSLGUBarangay(Id) {
	return true;
}

function onCFLGetParamsGPSLGUBarangay(Id,params) {
	return params;
}

function onTaskBarLoadGPSLGUBarangay() {
}

function onElementFocusGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSLGUBarangay(element,event,column,table,row) {
}

function onElementValidateGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSLGUBarangay(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementChangeGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementClickGPSLGUBarangay(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUBarangay(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUBarangay(element,params) {
	return params;
}

function onTableResetRowGPSLGUBarangay(table) {
}

function onTableBeforeInsertRowGPSLGUBarangay(table) {
	return true;
}

function onTableAfterInsertRowGPSLGUBarangay(table,row) {
}

function onTableBeforeUpdateRowGPSLGUBarangay(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSLGUBarangay(table,row) {
}

function onTableBeforeDeleteRowGPSLGUBarangay(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUBarangay(table,row) {
}

function onTableBeforeSelectRowGPSLGUBarangay(table,row) {
	return true;
}

function onTableSelectRowGPSLGUBarangay(table,row) {
}

function uploadPhoto() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshPhoto()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,JPG,JPEG,GIF,PNG";
	iframeFileUpload.document.getElementById("basename").value = "photo";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/LGUBARANGAY/" + getInput("docno") + "/";
	showPopupFrame('popupFrameFileUpload');
}
function refreshPhoto() {
	var src = document.images['PhotoImg'].src;
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}
function takePhoto(targetObjectId,targetOptions) {

	if (targetOptions==null) targetOptions = "view";
	OpenPopup(670,280,'./udp.php?objectcode=u_capturephoto' + '' + '&targetId=' + targetObjectId + '&targetOptions=' + targetOptions,targetObjectId);
}
function setPhoto(p_value) {
	setElementValueById("pf_photodata",p_value);
	document.getElementById('PhotoImg').src = p_value;
}

