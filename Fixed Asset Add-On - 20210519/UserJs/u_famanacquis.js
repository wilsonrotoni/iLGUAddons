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
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFixedAsset');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFixedAsset');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFixedAsset');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFixedAsset');
//page.tables.events.add.delete('onTableDeleteRowGPSFixedAsset');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFixedAsset');
//page.tables.events.add.select('onTableSelectRowGPSFixedAsset');

function onPageLoadGPSFixedAsset() {
}

function onPageResizeGPSFixedAsset(width,height) {
}

function onPageSubmitGPSFixedAsset(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;	
		if (getTableRowCount("T1",true)==0) {
			page.statusbar.showError("An item is required.");
			return false;
		}
		if (getTableInput("T1","u_itemcode")!="") {
			page.statusbar.showError("An item is being added/edited.");
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
				case "u_itemcode":
					if (getTableInput(table,column) != "") { 
						result = ajaxxmlvalidateitems(element.value,"-WHERE: U_ISFIXEDASSET=1");
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_itemcode","");
							setTableInput(table,"u_itemdesc","");
							setStatusMsg('Invalid Item Code!');
							return false;
						}	
						setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("itemcode"));
						setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("itemdesc"));
					} else {
						setTableInput(table,"u_itemcode","");
						setTableInput(table,"u_itemdesc","");
					}
					break;
				case "u_contraglacctno":
					if (getTableInput(table,"u_contraglacctno")!="") {
						result = ajaxxmlvalidatechartofaccounts(element.value);
						if (result.getAttribute("result") == '0') {
							setStatusMsg('Invalid G/L Account No!');
							return false;
						}	
						setTableInput(table,"u_contraglacctno",result.childNodes.item(0).getAttribute("formatcode"));
					} else {
					}
					break;
				case "u_empid":
					if (getTableInput(table,column) != "") { 
						result = ajaxxmlvalidateemployees(element.value);
						if (result.getAttribute("result") == '0') {
							setTableInput(table,"u_empid","");
							setTableInput(table,"u_empname","");
							setStatusMsg('Invalid Employee ID!');
							return false;
						}	
						setTableInput(table,"u_empid",result.childNodes.item(0).getAttribute("empid"));
						setTableInput(table,"u_empname",result.childNodes.item(0).getAttribute("fullname"));
					} else {
						setTableInput(table,"u_empid","");
						setTableInput(table,"u_empname","");
					}
					break;
				 case "u_profitcenter":
                                if (getInput(column)!="") {
                                        result = page.executeFormattedQuery("select profitcenter, profitcentername from profitcenters where profitcenter = '"+getInput(column)+"'");	
                                        if (result.getAttribute("result")!= "-1") {
                                                if (parseInt(result.getAttribute("result"))>0) {
                                                        setTableInput(table,"u_profitcenter",result.childNodes.item(0).getAttribute("profitcenter"));
                                                } else {
                                                        setTableInput(table,"u_profitcenter","");
                                                        page.statusbar.showError("Invalid Profit Center.");	
                                                        return false;
                                                }
                                        } else {
                                                setTableInput(table,"u_profitcenter","");
                                                page.statusbar.showError("Error retrieving profitcenters record. Try Again, if problem persists, check the connection.");	
                                                return false;
                                        }
                                } else {
                                        setTableInput(table,"u_profitcenter","");
                                }
                                break;
				case "u_projcode":
					if (getTableInput("T1","u_projcode") != "") { 
						result = ajaxxmlvalidateprojects(element.value);
						if (result.getAttribute("result") == '0') {
							setStatusMsg('Invalid Project!');
							return false;
						}	
						setTableInput("T1","u_projcode",result.childNodes.item(0).getAttribute("projcode"));
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

function onElementCFLGetParamsGPSFixedAsset(element,params) {
	switch (element) {
		case "df_u_profitcenterT1":
                        params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select profitcenter,profitcentername  from profitcenters")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Profit Center`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`"));
			break;
		case "df_u_contraglacctnoT1":
			params["params"] = "POSTABLE:1;CTRLACCT:0";
			break;
		case "df_u_itemcodeT1":
			params["params"] = "-WHERE: U_ISFIXEDASSET=1";
			break;
	}
	return params;
}

function onTableResetRowGPSFixedAsset(table) {
}

function onTableBeforeInsertRowGPSFixedAsset(table) {
	switch (table) {
		case "T1":
			//if (isTableInputEmpty(table,"u_itemcode")) return false;
			//if (isTableInputEmpty(table,"u_contraglacctno")) return false;
			if (isTableInputNegative(table,"u_cost")) return false;
			if (isTableInputEmpty(table,"u_branch")) return false;
			//if (isTableInputEmpty(table,"u_department")) return false;
			//if (isTableInputEmpty(table,"u_empid")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSFixedAsset(table,row) {
}

function onTableBeforeUpdateRowGPSFixedAsset(table,row) {
	switch (table) {
		case "T1":
			//if (isTableInputEmpty(table,"u_itemcode")) return false;
			//if (isTableInputEmpty(table,"u_contraglacctno")) return false;
			if (isTableInputNegative(table,"u_cost")) return false;
			if (isTableInputEmpty(table,"u_branch")) return false;
			//if (isTableInputEmpty(table,"u_department")) return false;
			//if (isTableInputEmpty(table,"u_empid")) return false;
			break;
	}
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
}

