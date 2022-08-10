// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
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
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_doctime")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_doctorid")) return false;
		if (isInputEmpty("u_alerttype")) return false;
		if (isInputEmpty("u_remarks")) return false;
		
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
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
	switch (table) {
		default:
			switch(column) {
				case "u_refno":
					if (getInput("u_refno")!="") {
						if (getInput("u_reftype")=="IP") {
							result = page.executeFormattedQuery("select a.u_patientid, a.u_patientname, a.u_doctorid, b.u_gender, a.u_age_y, a.u_roomno, a.u_bedno from u_hisips a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' and a.docstatus not in ('Discharged')");	 
						} else {
							result = page.executeFormattedQuery("select a.u_patientid, a.u_patientname, a.u_doctorid, b.u_gender, a.u_age_y, '' as u_roomno, '' as u_bedno from u_hisops a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' and a.docstatus not in ('Discharged')");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
								setInput("u_age",result.childNodes.item(0).getAttribute("u_age_y"));
								setInput("u_doctorid",result.childNodes.item(0).getAttribute("u_doctorid"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("u_roomno"));
								setInput("u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInput("u_gender","");
								setInput("u_age",0);
								setInput("u_doctorid","");
								setInput("u_roomno","");
								setInput("u_bedno","");
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInput("u_gender","");
							setInput("u_age",0);
							setInput("u_doctorid","");
							setInput("u_roomno","");
							setInput("u_bedno","");
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_gender","");
						setInput("u_age",0);
						setInput("u_doctorid","");
						setInput("u_roomno","");
						setInput("u_bedno","");
					}
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

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getVar("formSubmitAction")=="a") {
				page.statusbar.showWarning("Please add the document before attaching files.");	
				return false;
			}
			uploadAttachment();
			return false;
			break;	
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			//document.images['PictureImg'].src = getTableInput("T1","u_filepath",row);			
			document.images['PictureImg'].style.display = "none";
			var video = document.getElementById('video');
			video.style.display = "none";
			video.pause();
			switch (getTableInput("T1","u_filetype",row)) {
				case "img":
					document.images['PictureImg'].src = getTableInput("T1","u_filepath",row);			
					document.images['PictureImg'].style.display = "block";
					break;
				case "video":
					var video = document.getElementById('video');
			    	video.setAttribute("src", getTableInput("T1","u_filepath",row));
					video.style.display = "block";
					video.play();
					break;
				default:
					var objTag = document.getElementById("contentarea");
					objTag.setAttribute('data', getTableInput("T1","u_filepath",row));
					objTag.style.display = "block";
					break;
			}
			
			break;
	}
	return params;
}

function uploadAttachment() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,mp4,pdf";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Medical Records/" + getInput("docno") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function deleteAttachmentGPSHIS() {
	var rc = getTableSelectedRow("T1");	
	if (getTableSelectedRow("T1")>0) {
		if (getTableInput("T1","u_picturename",rc).substring(0,16)=='Medical Records/') {
			if (window.confirm("Delete this attachment. Continue?")==false) return false;
			if (ajaxdeleteattachment(getTableInput("T1","u_filepath",rc))) {
				formEdit();
			}
		} else page.statusbar.showWarning("You can only delete Medical Records Attachments.");
		
	} else page.statusbar.showWarning("No selected attachment to delete.");
}


