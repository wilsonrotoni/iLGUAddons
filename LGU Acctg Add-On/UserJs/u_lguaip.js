// page events
//page.events.add.load('onPageLoadGPSLGUAcctg');
//page.events.add.resize('onPageResizeGPSLGUAcctg');
page.events.add.submit('onPageSubmitGPSLGUAcctg');
//page.events.add.cfl('onCFLGPSLGUAcctg');
//page.events.add.cflgetparams('onCFLGetParamsGPSLGUAcctg');
page.events.add.reportgetparams('onPageReportGetParamsGPSLGUAcctg');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSLGUAcctg');

// element events
//page.elements.events.add.focus('onElementFocusGPSLGUAcctg');
//page.elements.events.add.keydown('onElementKeyDownGPSLGUAcctg');
page.elements.events.add.validate('onElementValidateGPSLGUAcctg');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSLGUAcctg');
//page.elements.events.add.changing('onElementChangingGPSLGUAcctg');
page.elements.events.add.change('onElementChangeGPSLGUAcctg');
//page.elements.events.add.click('onElementClickGPSLGUAcctg');
//page.elements.events.add.cfl('onElementCFLGPSLGUAcctg');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSLGUAcctg');
//page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSLGUAcctg');

// table events
page.tables.events.add.reset('onTableResetRowGPSLGUAcctg');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSLGUAcctg');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSLGUAcctg');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSLGUAcctg');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSLGUAcctg');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSLGUAcctg');
page.tables.events.add.delete('onTableDeleteRowGPSLGUAcctg');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSLGUAcctg');
//page.tables.events.add.select('onTableSelectRowGPSLGUAcctg');

function onPageReportGetParamsGPSLGUAcctg(formattype,params) {
	var paramids= new Array(),paramtypes= new Array(),paramvaluetypes= new Array(),paramaliases= new Array();
	var docstatus = getInput("docstatus");
	if (getVar("formSubmitAction")=="a") docstatus = "";
	params = getReportLayout(getGlobal("progid2"),formattype,params,docstatus);
	params["source"] = "aspx";
	if (params["reportname"]!=undefined) {
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			if (params["reportname"]=="AIP") {
				params["querystring"] += generateQueryString("yr",getInput("u_yr"));
				params["querystring"] += generateQueryString("profitcenter",getInput("u_profitcenter"));
			} else {
				params["querystring"] += generateQueryString("docno",getInput("docno"));
			}
		}	
	} else {
		params["querystring"] += generateQueryString("yr",getInput("u_yr"));
		params["querystring"] += generateQueryString("profitcenter",getInput("u_profitcenter"));
	}
	//alert(params["querystring"]);
	return params;
}

function onPageLoadGPSLGUAcctg() {
}

function onPageResizeGPSLGUAcctg(width,height) {
}

function onPageSubmitGPSLGUAcctg(action) {
	if (action=="a" || action=="sc") {
		
		if (isInputEmpty("u_yr")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
		
		if (getTableInput("T1","u_refcode")!="" || getTableInput("T1","u_description")!="")	{
			page.statusbar.showError("An item is currently being added/edited.");
			return false;
		}
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
	var amount=0;	
	switch (table) {
		case "T1":
			switch (column) {
				case "u_ps":
				case "u_mooe":
				case "u_co":
					setTableInputAmount(table,"u_total",getTableInputNumeric(table,"u_ps")+getTableInputNumeric(table,"u_mooe")+getTableInputNumeric(table,"u_co"));
					break;
				case "u_total":
					if (getTableInputNumeric(table,"u_ps")+getTableInputNumeric(table,"u_mooe")+getTableInputNumeric(table,"u_co")!=0) {
						setTableInputAmount(table,"u_total",getTableInputNumeric(table,"u_ps")+getTableInputNumeric(table,"u_mooe")+getTableInputNumeric(table,"u_co"));
						page.statusbar.showWarning("You cannot set Total manually if PS+MOOE+CO is not equal to zero.");
					}
					break;
			}
			break;
		default:
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
	switch (table) {
		default:
			switch(column) {
				case "u_yr":
					var docno = page.executeFormattedSearch("select docno from u_lguaip where u_yr='"+ getInput("u_yr")+"' and u_profitcenter='"+ getInput("u_profitcenter")+"'");
					if (docno!="") {
						setKey("keys",docno);
						formEdit();
						return false;
					}
					break;
				case "u_profitcenter":
					setInput("u_profitcentername",getInputSelectedText("u_profitcenter"));
					var docno = page.executeFormattedSearch("select docno from u_lguaip where u_yr='"+ getInput("u_yr")+"' and u_profitcenter='"+ getInput("u_profitcenter")+"'");
					if (docno!="") {
						setKey("keys",docno);
						formEdit();
						return false;
					}
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSLGUAcctg(element,column,table,row) {
	return true;
}

function onElementCFLGPSLGUAcctg(element) {
	return true;
}

function onElementCFLGetParamsGPSLGUAcctg(id,params) {
	return params;
}

function onElementLnkBtnGetParamsGPSLGUAcctg(id,params) {
	return params;
}




function onTableResetRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1":
			focusTableInput(table,"u_refcode");
			break;
	}
}

function onTableBeforeInsertRowGPSLGUAcctg(table) {
	switch (table) {
		case "T1":
			//if (!isTableInputUnique(table,"u_refcode")) return false;
			//if (isTableInputEmpty(table,"u_refcode")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1":
			//if (!isTableInputUnique(table,"u_refcode")) return false;
			//if (isTableInputEmpty(table,"u_refcode")) return false;
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeDeleteRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableDeleteRowGPSLGUAcctg(table,row) {
	switch (table) {
		case "T1": computeTotalGPSLGUAcctg(); break;
	}
}

function onTableBeforeSelectRowGPSLGUAcctg(table,row) {
	return true;
}

function onTableSelectRowGPSLGUAcctg(table,row) {
	var params = new Array();
	return params;
}

function OpenPopupDownloadGPSLGUAcctg() {
	targetObjectId = "";
	targetOptions = "";
	OpenPopup(650,130,'./udp.php?objectcode=u_downloadAIP' + '' + '&targetId=' + targetObjectId + '&targetOptions=' + targetOptions,targetObjectId);
}

function computeTotalGPSLGUAcctg() {
	var rc = 0, ps=0,mooe=0,fe=0,co=0,totalamount=0;
	
	rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			ps += getTableInputNumeric("T1","u_ps",i);
			mooe += getTableInputNumeric("T1","u_mooe",i);
			co += getTableInputNumeric("T1","u_co",i);
			totalamount += getTableInputNumeric("T1","u_total",i);
		}
	}

	setInputAmount("u_ps",ps);
	setInputAmount("u_mooe",mooe);
	setInputAmount("u_co",co);
	setInputAmount("u_totalamount",totalamount);
}

