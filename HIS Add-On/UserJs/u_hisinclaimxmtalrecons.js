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
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="?") {
		//if (isInputEmpty("u_inscode")) return false;
	} else if (action=="a" || action=="sc") {
		if (isInputEmpty("u_xmtalno")) return false;
		if (isInputEmpty("u_claimstatus")) return false;
		if (isInputEmpty("u_inscode")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_preparedby")) return false;
		if (isInputNegative("u_totalamount")) return false;
		if (!checkLinesGPSHIS()) return false;
		if (window.confirm("You cannot chage this after you have added it. Continue?")==false)	return false;
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
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_xmtalno":
					if (getInput(column)!="") formSubmit('?');
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
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_inscode":
				case "u_membergroup":
					clearTable("T1");
					setInput("u_insname",getInputSelectedText("u_inscode"));
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_selected":
					if (isTableInputChecked(table,column,row)) {
						enableTableInput(table,"u_reconremarks",row);
						focusTableInput(table,"u_reconremarks",row);
					} else {
						setTableInput(table,"u_reconremarks","",row);
						disableTableInput(table,"u_reconremarks",row);
					}
					computeTotalGPSHIS();
					break;
			}
			break;
		default:
			switch (column) {
				case "u_package":
					clearTable("T1");
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_xmtalno":
			var filterExp="";
			filterExp = filterExp + " AND a.u_package='"+getInput("u_package")+"'";
			if (getInput("u_inscode")!="") filterExp = filterExp + " AND a.u_inscode='"+getInput("u_inscode")+"'";
			if (getInput("u_membergroup")!="") filterExp = filterExp + " AND a.u_membergroup='"+getInput("u_membergroup")+"'";
			if (getInput("u_reftype")!="") filterExp = filterExp + " AND a.u_reftype='"+getInput("u_reftype")+"'";
			if (getInput("u_startdate")!="" && getInput("u_enddate")!="") filterExp = filterExp + " AND a.u_docdate>='"+formatDateToDB(getInput("u_startdate"))+"' AND a.u_docdate<='"+formatDateToDB(getInput("u_enddate"))+"'";
			else if (getInput("u_startdate")!="") filterExp = filterExp + " AND a.u_docdate='"+formatDateToDB(getInput("u_startdate"))+"'";
			else if (getInput("u_enddate")!="") filterExp = filterExp + " AND a.u_docdate='"+formatDateToDB(getInput("u_enddate"))+"'";

			//if (filterExp!="") filterExp = " AND " + filterExp;
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.docno,a.u_docdate, a.u_refno, a.u_insname from u_hisinclaimxmtals a inner join u_hisinclaimxmtalitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_status='' where a.docstatus not in ('CN') "+filterExp+" group by a.docno")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Transmittal No.`Date`Ref No.`Health Benefit")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`12`10`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
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
			if (elementFocused.substring(0,19)=="df_u_reconremarksT1") {
				focusTableInput(table,"u_reconremarks",row);
			}
			break;
	}
	return params;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), hf=0, pf=0, total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (isTableInputChecked("T1","u_selected",i)) {
				if (isInputChecked("u_package")) {
					total += getTableInputNumeric("T1","u_total",i);
				} else {
					hf += getTableInputNumeric("T1","u_room",i) + getTableInputNumeric("T1","u_med",i) + getTableInputNumeric("T1","u_lab",i) + getTableInputNumeric("T1","u_or",i);
					pf += getTableInputNumeric("T1","u_pf",i);
					total += getTableInputNumeric("T1","u_room",i) + getTableInputNumeric("T1","u_med",i) + getTableInputNumeric("T1","u_lab",i) + getTableInputNumeric("T1","u_or",i) + getTableInputNumeric("T1","u_pf",i);
				}
			}
		}
	}
	setInputAmount("u_totalhfamount",hf);	
	setInputAmount("u_totalpfamount",pf);	
	setInputAmount("u_totalamount",total);	
}

function checkLinesGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (isTableInputChecked("T1","u_selected",i)) {
				if (getTableInput("T1","u_reconremarks",i)=="") {
					selectTableRow("T1",i);
					focusTableInput("T1","u_reconremarks",i);
					page.statusbar.showError("Returned/Denied Remarks is required.");
					return false;
				}
			}
		}
	}
	return true;
}

function OpenLnkBtnRefNoGPSHIS(targetId) {
	switch (getTableElementValue(targetId,"T1","u_reftype")) {
		case "BILLINS":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hisinclaims' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "BILLPNS":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hispronotes' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "POS":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hispos' + '' + '&targetId=' + targetId ,targetId);
			break;
	}
}

function OpenLnkBtnu_hisinclaimxmtals(targetId) {
	OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hisinclaimxmtals' + '' + '&targetId=' + targetId ,targetId);
}