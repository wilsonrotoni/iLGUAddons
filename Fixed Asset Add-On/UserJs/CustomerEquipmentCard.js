// page events
//page.events.add.load('onPageLoadGPSFixedAsset');
//page.events.add.resize('onPageResizeGPSFixedAsset');
//page.events.add.submit('onPageSubmitGPSFixedAsset');
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
page.elements.events.add.lnkbtngetparams('onElementLnkBtnGetParamsGPSFixedAsset');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFixedAsset');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFixedAsset');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFixedAsset');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFixedAsset');
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
	var result;	
	switch(table) {
		default:
			switch(column) {
				case "emprefno":
					result = page.executeFormattedQuery("select * from u_fa where code='"+getInput("emprefno")+"'");	 
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							if (result.childNodes.item(0).getAttribute("u_bookvalue")+result.childNodes.item(0).getAttribute("u_salvagevalue")==0) {
								setInput("empbranch","");	
								setInput("empdepartment","");	
								setInput("empid","");	
								setInput("empname","");	
								setInput("internalsn","");	
								setInput("manufsn","");	
								setInput("itemcode","");	
								setInput("itemname","");	
								page.statusbar.showError("Asset is already closed.");
								return false;
							}
							setInput("emprefno",result.childNodes.item(0).getAttribute("code"));	
							setInput("itemcode",result.childNodes.item(0).getAttribute("u_itemcode"));	
							setInput("itemname",result.childNodes.item(0).getAttribute("u_itemdesc"));	
							setInput("empbranch",result.childNodes.item(0).getAttribute("u_branch"));	
							setInput("empdepartment",result.childNodes.item(0).getAttribute("u_department"));	
							setInput("empid",result.childNodes.item(0).getAttribute("u_empid"));	
							setInput("empname",result.childNodes.item(0).getAttribute("u_empname"));	
							setInput("internalsn",result.childNodes.item(0).getAttribute("u_serialno"));	
							setInput("manufsn",result.childNodes.item(0).getAttribute("u_mfrserialno"));	
						} else {
							setInput("empbranch","");	
							setInput("empdepartment","");	
							setInput("empid","");	
							setInput("empname","");	
							setInput("internalsn","");	
							setInput("manufsn","");	
							setInput("itemcode","");	
							setInput("itemname","");	
							page.statusbar.showError("Unable to retrieve Fixed Asset profile.");
							return false;
						}
					} else {
						setInput("empbranch","");	
						setInput("empdepartment","");	
						setInput("empid","");	
						setInput("empname","");	
						setInput("internalsn","");	
						setInput("manufsn","");	
						setInput("itemcode","");	
						setInput("itemname","");	
						alert(result.childNodes.item(0).getAttribute("error"));
						return false;
					}
					
					break;
			}
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

function onElementCFLGetParamsGPSFixedAsset(id,params) {
	switch(id) {
		case "df_emprefno": 
			params["params"] = "UDT:U_FA;-WHERE U_ONHOLD=0"; 
			break;
	}
	return params;
}

function onElementLnkBtnGetParamsGPSFixedAsset(id,params) {
	switch (id) {
		case "df_emprefno":
			params["keys"] = getGlobal("company") + "`" + getGlobal("mainbranch") + "`" +  getInput("emprefno");
			params["params"] = "&objectcode=U_FA&searchbykey=Y";
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
}

function OpenLnkBtnu_FA(targetObjectId) {
	OpenLnkBtn(1024,600,'./UDO.php?',targetObjectId);
}

