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
page.elements.events.add.change('onElementChangeGPSFixedAsset');
page.elements.events.add.click('onElementClickGPSFixedAsset');
page.elements.events.add.cfl('onElementCFLGPSFixedAsset');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFixedAsset');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFixedAsset');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFixedAsset');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFixedAsset');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFixedAsset');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFixedAsset');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFixedAsset');
//page.tables.events.add.delete('onTableDeleteRowGPSFixedAsset');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFixedAsset');
page.tables.events.add.select('onTableSelectRowGPSFixedAsset');

function onPageLoadGPSFixedAsset() {
}

function onPageResizeGPSFixedAsset(width,height) {
}

function onPageSubmitGPSFixedAsset(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;	
		var none=true;
		for(i=1;i<=getTableRowCount("T1");i++) {
			if(isTableInputChecked("T1","u_selected",i)){
				if (getTableInputNumeric("T1","u_cost",i)<0) {
					if (isTableInputNegative("T1","u_cost",i)) {
						selectTableRow("T1",i);
						focusTableInput("T1","u_cost",i);
						return false;
					}	
				}
				if (isTableInputEmpty("T1","u_assettype",i)) {
					selectTableRow("T1",i);
					focusTableInput("T1","u_assettype",i);
					return false;
				}	
				if (isTableInputEmpty("T1","u_assetcode",i)) {
					selectTableRow("T1",i);
					focusTableInput("T1","u_assetcode",i);
					return false;
				}	
				if (getTableInput("T1","u_assettype",i)=="F") {
					if (isTableInputEmpty("T1","u_branch",i)) {
						selectTableRow("T1",i);
						focusTableInput("T1","u_branch",i);
						return false;
					}	
					if (isTableInputEmpty("T1","u_department",i)) {
						selectTableRow("T1",i);
						focusTableInput("T1","u_department",i);
						return false;
					}	
					if (isTableInputEmpty("T1","u_empid",i)) {
						selectTableRow("T1",i);
						focusTableInput("T1","u_empid",i);
						return false;
					}	
				}	
				none = false;
			}
		}
		if(none) {
			setStatusMsg("Please select item(s) to process."); 
			return false;				
		}		
		if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
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
	switch (table) {
		case "T1":
			switch (column) {
				case "u_contraglacctno":
					if (getTableInput(table,"u_contraglacctno",row)!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setTableInput(table,"u_contraglacctno",result.childNodes.item(0).getAttribute("formatcode"),row);
					} else {
					}
					break;
				case "u_empid":
					if (getTableInput(table,column,row) != "") { 
						if (getTableInput(table,"u_assettype",row)!="F") {
							setTableInput(table,"u_empid","",row);
							setTableInput(table,"u_empname","",row);
							page.statusbar.showError("Not allowed for Child Asset.");
							return false;	
						}
						result = ajaxxmlvalidateemployees(element.value);
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_empid","",row);
							setTableInput(table,"u_empname","",row);
							setStatusMsg('Invalid Employee ID!');
							return false;
						}	
						setTableInput(table,"u_empid",result.childNodes.item(0).getAttribute("empid"),row);
						setTableInput(table,"u_empname",result.childNodes.item(0).getAttribute("fullname"),row);
					} else {
						setTableInput(table,"u_empid","",row);
						setTableInput(table,"u_empname","",row);
					}
					break;
				case "u_profitcenter":
					if (getTableInput("T1","u_profitcenter",row) != "") { 
						result = ajaxxmlvalidateprofitcenterdistributionrules(element.value);
						if (result.getAttribute("result") == '0') {
							setStatusMsg('Invalid Distribution Rule!');
							return false;
						}	
						setTableInput("T1","u_profitcenter",result.childNodes.item(0).getAttribute("drcode"),row);
					}
					break;
				case "u_projcode":
					if (getTableInput("T1","u_projcode",row) != "") { 
						result = ajaxxmlvalidateprojects(element.value);
						if (result.getAttribute("result") == '0') {
							setStatusMsg('Invalid Project!');
							return false;
						}	
						setTableInput("T1","u_projcode",result.childNodes.item(0).getAttribute("projcode"),row);
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
	var result;
	switch(table) {
		case "T1":
			switch (column) {
				case "u_assettype":
					setTableInput(table,"u_assetcode","",row);
					setTableInput(table,"u_branch","",row);
					setTableInput(table,"u_department","",row);
					setTableInput(table,"u_empid","",row);
					setTableInput(table,"u_empname","",row);
					if (getTableInput(table,"u_assettype",row)=="F") {
						if (getTableInput(table,"u_itemcode",row)!="") {	 
							var result = page.executeFormattedSearch("select u_faclass from items where itemcode='"+getTableInput(table,"u_itemcode",row)+"'");	
							if (result!="") {
								setTableInput(table,"u_assetcode",result,row);
								disableTableInput("T1","u_assetcode",row);
							} else {
								enableTableInput("T1","u_assetcode",row);
							}
						} else {
							enableTableInput("T1","u_assetcode",row);
						}
						enableTableInput("T1","u_profitcenter",row);
						enableTableInput("T1","u_projcode",row);
						enableTableInput("T1","u_branch",row);
						enableTableInput("T1","u_department",row);
						enableTableInput("T1","u_empid",row);
					} else {
						enableTableInput("T1","u_assetcode",row);
						disableTableInput("T1","u_profitcenter",row);
						disableTableInput("T1","u_projcode",row);
						disableTableInput("T1","u_branch",row);
						disableTableInput("T1","u_department",row);
						disableTableInput("T1","u_empid",row);
					}
					break;
				case "u_branch":
					if (getTableInput(table,"u_branch",row)!="") {
						if (getTableInput(table,"u_assettype",row)!="F") {
							page.statusbar.showError("Not allowed for Child Asset.");
							return false;	
						}
					}
					break;
				case "u_department":
					if (getTableInput(table,"u_department",row)!="") {
						if (getTableInput(table,"u_assettype",row)!="F") {
							page.statusbar.showError("Not allowed for Child Asset.");
							return false;	
						}
					}
					break;
			}
	}
	return true;
}

function onElementClickGPSFixedAsset(element,column,table,row) {
	var result;
	switch(table) {
		case "T1":
			switch (column) {
				case "u_selected":
					setTableInput("T1","u_assettype","",row);
					setTableInput("T1","u_assetcode","",row);
					setTableInput("T1","u_profitcenter","",row);
					setTableInput("T1","u_projcode","",row);
					if (getPrivate("famgmnt")!="BR") setTableInput("T1","u_branch","",row);
					setTableInput("T1","u_department","",row);
					setTableInput("T1","u_empid","",row);
					if (isTableInputChecked(table,column,row)) {
						enableTableInput("T1","u_contraglacctno",row);
						enableTableInput("T1","u_cost",row);
						enableTableInput("T1","u_assettype",row);
						enableTableInput("T1","u_assetcode",row);
						enableTableInput("T1","u_profitcenter",row);
						enableTableInput("T1","u_projcode",row);
						if (getPrivate("famgmnt")!="BR") enableTableInput("T1","u_branch",row);
						enableTableInput("T1","u_department",row);
						enableTableInput("T1","u_empid",row);
						//focusTableInput("T1","depredate",row,500);
					} else {
						disableTableInput("T1","u_contraglacctno",row);
						disableTableInput("T1","u_cost",row);
						disableTableInput("T1","u_assettype",row);
						disableTableInput("T1","u_assetcode",row);
						disableTableInput("T1","u_profitcenter",row);
						disableTableInput("T1","u_projcode",row);
						if (getPrivate("famgmnt")!="BR") disableTableInput("T1","u_branch",row);
						disableTableInput("T1","u_department",row);
						disableTableInput("T1","u_empid",row);
					}
			}
			break;
		default:
			break;
	}					
	return true;
}

function onElementCFLGPSFixedAsset(id) {
	switch (id) {
		default:
			if (id.substring(0,17)=="df_u_assetcodeT1r") {
				if (getTableInput("T1","u_assettype",id.substring(17))=="") {
					page.statusbar.showError("Select Type of Asset.");
					focusTableInput("T1","u_assettype",id.substring(17));
					return false;
				}
			}
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSFixedAsset(id,params) {
	switch (id) {
		default:
			if (id.substring(0,22)=="df_u_contraglacctnoT1r") {
				params["params"] = "POSTABLE:1;CTRLACCT:0";
			}
			if (id.substring(0,17)=="df_u_assetcodeT1r") {
				if (getTableInput("T1","u_assettype",id.substring(17))=="F") params["params"] = "UDT:U_FACLASS";
				else params["params"] = "UDT:U_FA";
			}
			break;
	}
	return params;
}

function onTableResetRowGPSFixedAsset(table) {
}

function onTableBeforeInsertRowGPSFixedAsset(table) {
	return true;
}

function onTableAfterInsertRowGPSFixedAsset(table,row) {
}

function onTableBeforeUpdateRowGPSFixedAsset(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFixedAsset(table,row) {
}

function onTableBeforeDeleteRowGPSFixedAsset(table,row) {
	return true;
}

function onTableDeleteRowGPSFixedAsset(table,row) {
}

function onTableBeforeSelectRowGPSFixedAsset(table,row) {
	return true;
}

function onTableSelectRowGPSFixedAsset(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			break;
	}
	return params;
}

