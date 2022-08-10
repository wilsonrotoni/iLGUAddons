// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
//page.events.add.submit('onPageSubmitGPSHIS');
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
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
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
			switch(column) {
				case "code":
				case "name":
					if (getTableInput(table,column)!="") {
						if (column=="code") {	
							result = page.executeFormattedQuery("select code,name,u_uom,u_department from u_hisitems where u_group='MED' and code='"+getTableInput(table,column)+"' and u_active=1");	 
						} else {
							result = page.executeFormattedQuery("select code,name,u_uom,u_department from u_hisitems where u_group='MED' and name like '"+getTableInput(table,column)+"%' and u_active=1");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"code",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"name",result.childNodes.item(0).getAttribute("name"));
							} else {
								setTableInput(table,"code","");
								setTableInput(table,"name","");
								page.statusbar.showError("Invalid Item.");	
								return false;
							}
						} else {
							setTableInput(table,"code","");
							setTableInput(table,"name","");
							page.statusbar.showError("Error retrieving Item. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"code","");
						setTableInput(table,"name","");
					}
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
		case "df_codeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisitems where u_group='MED' and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Code`Item Description")); 			
			/*params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisbrands"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=600"; 	*/
			break;
		case "df_nameT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisitems where u_group='MED' and u_active=1")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Item Description`Item Code")); 			
			/*params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisbrands"; 			
			params["params"] += "&cfladdwidth=600"; 			
			params["params"] += "&cfladdheight=600"; 	*/
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"code")) return false;
			if (isTableInputEmpty(table,"code")) return false;
			if (isTableInputEmpty(table,"name")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (!isTableInputUnique(table,"code")) return false;
			if (isTableInputEmpty(table,"code")) return false;
			if (isTableInputEmpty(table,"name")) return false;
			if (isTableInputNegative(table,"u_price")) return false;
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

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	return params;
}

