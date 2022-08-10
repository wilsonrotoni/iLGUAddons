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
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
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
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a" && getInput("u_refno")=="") {
		try {
			var row = window.opener.getTableSelectedRow("T1");
			setInput("u_refno",window.opener.getTableInput("T1","docno",row));
			setInput("u_patientid",window.opener.getTableInput("T1","u_patientid",row));
			setInput("u_patientname",window.opener.getTableInput("T1","u_patientname",row));
			formSubmit("?");			
		} catch (theError) {
		}
	}
	if (getTableRowCount("T112")>0 || getTableRowCount("T113")>0) showPopupFrame("popupFramePendingItems",true);
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		
		if (getTableRowCount("T112")>0 || getTableRowCount("T113")>0) {
			alert("You cannot admit patient if Pending Requests still exists or you are still holding Medines & Supplies.");
			return false;
		}
						
		if (isInputEmpty("u_roomno")) return false;
		if (isInputEmpty("u_bedno")) return false;
		if (isInputNegative("u_rate")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		if (isInputEmpty("u_remarks")) return false;
		//if (isInputNegative("u_chargingrate")) return false;
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		if (success) {
			window.opener.setKey("keys",window.opener.getInput("docno"));
			//window.opener.setInput("u_tab1selected",1);
			window.opener.formEdit();
		}
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
		default:
			switch(column) {
				case "u_roomno":
					if (getInput("u_roomno")!="") {
						result = page.executeFormattedQuery("select a.code, a.name, a.u_isshared, a.u_charginguom, a.u_pricelist, a.u_roomtype, a.u_department from u_hisrooms a, u_hisroombeds b where b.code=a.code and (b.u_status='Vacant' or (b.u_status='Reserved' and b.u_patientname='"+getInput("u_patientname")+"')) and a.code='"+getInput("u_roomno")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_roomtype",result.childNodes.item(0).getAttribute("u_roomtype"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								if (getInput("u_isroomshared")==0) setInput("u_bedno",result.childNodes.item(0).getAttribute("code"));
								else setInput("u_bedno","");
								var result2 = ajaxxmlgetitemprice(result.childNodes.item(0).getAttribute("u_roomtype"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
								setInputAmount("u_rate",formatNumeric(result2.getAttribute("price"),'',0));
								
							} else {
								setInput("u_bedno","");
								setInput("u_rateuom","");
								page.statusbar.showError("Invalid Room No.");	
								return false;
							}
						} else {
							setInput("u_bedno","");
							setInput("u_rateuom","");
							page.statusbar.showError("Error retrieving Room No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bedno","");
						setInput("u_roomno","");
						setInput("u_rateuom","");
					}
					break;
				case "u_bedno":
					if (getInput("u_bedno")!="") {
						result = page.executeFormattedQuery("select a.code, a.u_bedno, b.name, b.u_isshared, b.u_charginguom, b.u_pricelist, b.u_roomtype, b.u_department from u_hisroombeds a, u_hisrooms b where a.code=b.code and a.u_bedno='"+getInput("u_bedno")+"' and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_bedno",result.childNodes.item(0).getAttribute("u_bedno"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("code"));
								setInput("u_roomdesc",result.childNodes.item(0).getAttribute("name"));
								setInput("u_roomtype",result.childNodes.item(0).getAttribute("u_roomtype"));
								setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
								setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
								setInput("u_rateuom",result.childNodes.item(0).getAttribute("u_charginguom"));
								setInput("u_isroomshared",result.childNodes.item(0).getAttribute("u_isshared"));
								var result2 = ajaxxmlgetitemprice(result.childNodes.item(0).getAttribute("u_roomtype"),"BRANCH:" + getInput("u_branch") + ";PRICELIST:"+getInput("u_pricelist")+"");
								setInputAmount("u_rate",formatNumeric(result2.getAttribute("price"),'',0));
								
							} else {
								setInput("u_roomno","");
								setInput("u_rateuom","");
								page.statusbar.showError("Invalid Bed No.");	
								return false;
							}
						} else {
							setInput("u_roomno","");
							setInput("u_rateuom","");
							page.statusbar.showError("Error retrieving Bed No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_bedno","");
						setInput("u_roomno","");
						setInput("u_rateuom","");
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
		case "df_u_roomno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.code, a.name,count(*) as 'Vacant Beds' from u_hisrooms a, u_hisroombeds b, u_hisroomtypes c where c.code=a.u_roomtype and c.u_type='Room & Board' and a.code=b.code and (b.u_status='Vacant' or (b.u_status='Reserved' and b.u_patientname='"+getInput("u_patientname")+"')) group by a.code having count(*) >0")); 
			break;
		case "df_u_bedno":
			if (getInput("u_roomno")=="")	params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))")); 
			else params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.u_bedno, b.name from u_hisroombeds a, u_hisrooms b, u_hisroomtypes c where c.code=b.u_roomtype and c.u_type='Room & Board' and b.code=a.code and a.code='"+getInput("u_roomno")+"' and (a.u_status='Vacant' or (a.u_status='Reserved' and a.u_patientname='"+getInput("u_patientname")+"'))" )); 
			
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
}

