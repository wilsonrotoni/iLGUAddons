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
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	try {
		if (window.opener.getInput("docstatus")!="C") {
			tinymce.init({
				selector: "div.editable",
				inline: true,
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste textcolor"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview"
		
			});
		}
	} catch (theError) {
	}
	if (getVar("formSubmitAction")=="a") setTestCasesGPSHIS();
	
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		setInput("u_remarks",getElementHTMLById("divEditor"));
	}
	if (action=="a") {
		setInput("u_labtestno",window.opener.getInput("docno"));
		setInput("code",window.opener.getInput("docno") + "-" + getInput("name"));
		if (isInputEmpty("name")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		window.opener.setKey("keys",getInput("u_labtestno"));
		//window.opener.setInput("u_tab1selected",1);
		window.opener.formEdit();
	} catch(TheError) {
	}
	if (success) window.close();
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
		case "T1":
			switch (column) {
				case "u_result":
					if (getTableInput(table,"u_formula",row)!="") {
						if (getTableInput(table,"u_result",row)!="") {
							result=getTableInput(table,"u_formula",row).replace(/{result}/,getTableInput(table,"u_result",row));
							try {
								result = (eval(result));
							} catch (theError) {
								page.statusbar.showError("Error parsing formula:"+theError.message+". please enter only numbers.");
								return false;
							}
							setTableInput(table,"u_formularesult",result,row);
						} else {
							setTableInput(table,"u_formularesult","",row);
						}
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_template":
					setTestCasesGPSHIS();
					break;
			}
			
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
	switch (table) {
		case "T102":
			switch (column) {
				case "u_template":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedSearch("select u_remarks from u_hislabtesttypenotes where code='"+getTableInput(table,column)+"'");
						setElementHTMLById("divEditorT102",result);
					}
					break;				
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T2":
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
			params["focus"] = false;
			if (elementFocused.substring(0,13)=="df_u_resultT1") {
				focusTableInput(table,"u_result",row);
			} else if (elementFocused.substring(0,14)=="df_u_remarksT1") {
				focusTableInput(table,"u_remarks",row);
			}
			break;
		case "T2":
			document.images['PictureImg'].style.display = "none";
			var video = document.getElementById('video');
			video.style.display = "none";
			video.pause();
			switch (getTableInput("T2","u_filetype",row)) {
				case "img":
					document.images['PictureImg'].src = getTableInput("T2","u_filepath",row);			
					document.images['PictureImg'].style.display = "block";
					break;
				case "video":
					var video = document.getElementById('video');
			    	video.setAttribute("src", getTableInput("T2","u_filepath",row));
					video.style.display = "block";
					video.play();
					break;
			}
			break;
	}
	return params;
}

function setTestCasesGPSHIS() {
	var result, data = new Array();
	showAjaxProcess();
	clearTable("T1");
	//if (getInput("u_type")!="" && getInput("u_gender")!="" && getInput("u_startdate")!="") {
		result = page.executeFormattedQuery("select a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test, a.u_normalrange, a.u_formula, a.u_formulanormalrange, a.u_units from u_hislabtesttypecases a where a.code='"+window.opener.getInput("u_testtype")+"' and a.u_template='"+getInput("u_template")+"' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='"+window.opener.getInput("u_gender")+"')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<="+window.opener.getInput("u_age")+")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>="+window.opener.getInput("u_age")+")) order by a.u_seq");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					data["u_seq"] = result.childNodes.item(iii).getAttribute("u_seq");
					data["u_seq2"] = result.childNodes.item(iii).getAttribute("u_seq2");
					data["u_group"] = result.childNodes.item(iii).getAttribute("u_group");
					data["u_print"] = result.childNodes.item(iii).getAttribute("u_print");
					data["u_test"] = result.childNodes.item(iii).getAttribute("u_test");
					data["u_normalrange"] = result.childNodes.item(iii).getAttribute("u_normalrange");
					data["u_units"] = result.childNodes.item(iii).getAttribute("u_units");
					data["u_formula"] = result.childNodes.item(iii).getAttribute("u_formula");
					data["u_formulanormalrange"] = result.childNodes.item(iii).getAttribute("u_formulanormalrange");
					insertTableRowFromArray("T1",data);
				}
			}
		} else {
			hideAjaxProcess();
			page.statusbar.showError("Error retrieving Type of Test. Try Again, if problem persists, check the connection.");	
			return false;
		}
	//}
	hideAjaxProcess();
	return true;
}

function uploadAttachment() {
	iframeFileUpload.document.getElementById("callbackfunc").value = "refreshAttachment()";
	iframeFileUpload.document.getElementById("extensions").value = "jpeg,jpg,gif,png,mp4";
	iframeFileUpload.document.getElementById("filepath").value = "../Images/" + getGlobal("company") + "/" + getGlobal("branch") + "/HIS/Laboratory Sub-Tests/" + getInput("code") + "/Attachments/";
	showPopupFrame('popupFrameFileUpload',true);
}

function refreshAttachment() {
	hidePopupFrame('popupFrameFileUpload');
	setTimeout("formEdit()",1000);
}

function deleteAttachmentGPSHIS() {
	var rc = getTableSelectedRow("T2");	
	if (getTableSelectedRow("T2")>0) {
		if (ajaxdeleteattachment(getTableInput("T2","u_filepath",rc))) {
			formEdit();
		}
	} else page.statusbar.showWarning("No selected attachment to delete.");
}

function u_sltemplateGPSHIS() {
	try {
		if (window.opener.isInputEmpty("u_doctorid2")) return false;
		selectTab("tab1",2);
		u_ajaxloadu_hislabtesttypenotes("df_u_templateT102",window.opener.getInput("u_testtype"),window.opener.getInput("u_doctorid2"),'',":[Select]");
		showPopupFrame("popupFrameNotesTemplate",true);
	} catch (theError) {
	}
	
}

function u_ajaxloadu_hislabtesttypenotes(p_elementid, p_u_type, p_u_doctorid,p_value,p_filler) {
	http = getHTTPObject(); // We create the HTTP Object
	document.getElementById(p_elementid).innerHTML = "<option>Loading..</option>"
	//alert("udp.php?ajax=1&objectcode=u_ajaxslu_hislabtesttypenotes&u_type=" + p_u_type + "&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler);
	http.open("GET", "udp.php?ajax=1&objectcode=u_ajaxslu_hislabtesttypenotes&u_type=" + p_u_type + "&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	document.getElementById(p_elementid).innerHTML = http.responseText;
	document.getElementById('ajaxPending').value = "";	
	
}
