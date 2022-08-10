// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');

var defaultvalues = new Array();

function onPageLoadGPSHIS() {
	defaultvalues["seq"] = 0;
	defaultvalues["seq2"] = 5;
	defaultvalues["group"] = "";
	setTableInput("T1","u_seq2",defaultvalues["seq2"]);
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("code")) return false;
		if (isInputEmpty("name")) return false;
		if (isInputEmpty("u_department")) return false;
		if (getTableInput("T1","u_test")!="") {
			page.statusbar.showError("A test case item is being added/edited.");
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
	switch(table) {
		case "T1":
			switch (column) {
				case "u_itemcode":
				case "u_itemdesc":
					if (getTableInput(table,column)!="") {
						if (column=="u_itemcode") {
							result = page.executeFormattedQuery("select a.code, a.name from u_hisdoctors a where a.u_active=1 and a.code = '"+getTableInput(table,column)+"'");	
						} else {
							result = page.executeFormattedQuery("select a.code, a.name from u_hisitems a where a.u_active=1 and a.name like '"+getTableInput(table,column)+"%'");	
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"u_itemcode","");
								setTableInput(table,"u_itemdesc","");
								page.statusbar.showError("Invalid Item.");	
								return false;
							}
						} else {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							page.statusbar.showError("Error retrieving Item record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_seq":
					//setTableInputDefault(table,"u_seq",getTableInput(table,"u_seq"));
					break;
				case "u_group":
					//setTableInputDefault(table,"u_group",getTableInput(table,"u_group"));
					break;
			}
			break;
		default:
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
	switch (table) {
		default:
			switch (column) {
				case "u_series":
					setDocNo(true,"u_series","code");
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
	switch (Id) {
		case "df_u_itemcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_hisitems where u_active=1 ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_itemdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code  from u_hisitems where u_active=1 ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Item Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("35`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hislabtesttypenotes":
			params["keys"] = getTableInput("T101","code",getTableSelectedRow("T101"));
			break;
		case "u_hislabtests":
			params["keys"] = "-1";
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch(table) {
		case "T1":
			setTableInput(table,"u_seq",defaultvalues["seq"]);
			setTableInput(table,"u_seq2",defaultvalues["seq2"]);
			setTableInput(table,"u_group",defaultvalues["group"]);
			focusTableInput(table,"u_test");
			break;
	}
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_test")) return false;
			//if (isTableInputEmpty(table,"u_normalrange")) return false;
			break;
		case "T101":
			var targetObjectId = '';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtesttypenotes' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			defaultvalues["seq"] = getTableInput(table,"u_seq",row);
			defaultvalues["seq2"] =  getTableInputNumeric(table,"u_seq2",row) +5;
			defaultvalues["group"] = getTableInput(table,"u_group",row);
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (isTableInputEmpty(table,"u_test")) return false;
			//if (isTableInputEmpty(table,"u_normalrange")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeEditRowGPSHIS(table,row) {
	var targetObjectId = '';
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_itemdesc")=="") {
				focusTableInput(table,"u_itemdesc");
			}
			break;
		case "T101":
			targetObjectId = 'u_hislabtesttypenotes';
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtesttypenotes' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T101":
			var rtf = document.getElementById('divEditor');
			rtf .style.display = "none";
			var normal = document.getElementById('df_remarks');
			normal.style.display = "none";
			if (getTableInput("T101","format",row)=="rtf") {
				rtf.style.display = "block";
				setElementHTMLById("divEditor",getTableInput("T101","remarks",row));
			} else  {
				normal.style.display = "block";
				setInput("remarks",getTableInput("T101","remarks",row));
			}
			break;
	}
	return params;
}

function u_duplicateGPSHIS() {
	var row = getTableSelectedRow("T1");
	if (row>0) {
		setTableInput("T1","u_itemcode",getTableInput("T1","u_itemcode",row));
		setTableInput("T1","u_itemdesc",getTableInput("T1","u_itemdesc",row));
		setTableInput("T1","u_seq",getTableInput("T1","u_seq",row));
		setTableInput("T1","u_seq2",getTableInput("T1","u_seq2",row));
		setTableInput("T1","u_gender",getTableInput("T1","u_gender",row));
		setTableInput("T1","u_agefr",getTableInput("T1","u_agefr",row));
		setTableInput("T1","u_ageto",getTableInput("T1","u_ageto",row));
		setTableInput("T1","u_group",getTableInput("T1","u_group",row));
		setTableInput("T1","u_print",getTableInput("T1","u_print",row));
		setTableInput("T1","u_test",getTableInput("T1","u_test",row));
		setTableInput("T1","u_normalrange",getTableInput("T1","u_normalrange",row));
		setTableInput("T1","u_units",getTableInput("T1","u_units",row));
		setTableInput("T1","u_formula",getTableInput("T1","u_formula",row));
		setTableInput("T1","u_formulanormalrange",getTableInput("T1","u_formulanormalrange",row));
		setTableInput("T1","u_formulaunits",getTableInput("T1","u_formulaunits",row));
	}
}


function u_previewGPSHIS() {
	targetObjectId = 'u_hislabtests';
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '' + '&edtopt=testpreview&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_hisexamcases(targetId) {
	OpenLnkBtn(450,380,'./udo.php?objectcode=u_hisexamcases' + '' + '&targetId=' + targetId ,targetId);
	
}


function u_addrtfnotesGPSHIS() {
	var targetObjectId = '';
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtesttypenotes' + '&df_notes_format=rtf' + '&targetId=' + targetObjectId ,targetObjectId);
	return false;
}

function u_addnotesGPSHIS() {
	var targetObjectId = '';
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hislabtesttypenotes' + '&df_notes_format=normal' + '&targetId=' + targetObjectId ,targetObjectId);
	return false;
}
