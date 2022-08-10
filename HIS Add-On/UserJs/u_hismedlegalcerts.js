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
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
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
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_refno")) return false;
		if (isInputEmpty("u_patientid")) return false;
		if (isInputEmpty("u_patientname")) return false;
		if (isInputEmpty("u_doctorid")) return false;
		
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
							result = page.executeFormattedQuery("select a.u_startdate, a.u_enddate, a.u_endtime, a.u_patientid, a.u_patientname, a.u_doctorid, b.u_gender, a.u_age_y, b.u_civilstatus, a.u_roomno, b.u_address, if(a.u_finalremarks<>'',a.u_finalremarks,if(a.u_remarks2<>'',a.u_remarks2,a.u_remarks)) as u_remarks, a.u_orproc, c.u_prcno, c.u_ptrno from u_hisips a inner join u_hispatients b on b.company=a.company and b.branch=a.branch and b.code=a.u_patientid left join u_hisdoctors c on c.code=a.u_doctorid where a.docno='"+getInput("u_refno")+"'");	 
						} else {
							result = page.executeFormattedQuery("select a.u_startdate, a.u_enddate, a.u_endtime, a.u_patientid, a.u_patientname, a.u_doctorid, b.u_gender, a.u_age_y, b.u_civilstatus, '' as u_roomno, b.u_address, if(a.u_finalremarks<>'',a.u_finalremarks,if(a.u_remarks2<>'',a.u_remarks2,a.u_remarks)) as u_remarks, a.u_orproc, c.u_prcno, c.u_ptrno from u_hisops a inner join u_hispatients b on b.company=a.company and b.branch=a.branch and b.code=a.u_patientid left join u_hisdoctors c on c.code=a.u_doctorid where a.docno='"+getInput("u_refno")+"'");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
								setInput("u_age",result.childNodes.item(0).getAttribute("u_age_y"));
								setInput("u_civilstatus",result.childNodes.item(0).getAttribute("u_civilstatus"));
								setInput("u_doctorid",result.childNodes.item(0).getAttribute("u_doctorid"));
								setInput("u_prcno",result.childNodes.item(0).getAttribute("u_prcno"));
								setInput("u_ptrno",result.childNodes.item(0).getAttribute("u_ptrno"));
								setInput("u_roomno",result.childNodes.item(0).getAttribute("u_roomno"));
								setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
								setInput("u_initialremarks",result.childNodes.item(0).getAttribute("u_remarks"));
								setInput("u_orproc",result.childNodes.item(0).getAttribute("u_orproc"));
								setInput("u_startdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_startdate")));
								setInput("u_enddate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_enddate")));
								setInput("u_endtime",formatTimeToHttp(result.childNodes.item(0).getAttribute("u_endtime")));
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								setInput("u_gender","");
								setInput("u_age",0);
								setInput("u_civilstatus","");
								setInput("u_doctorid","");
								setInput("u_roomno","");
								setInput("u_startdate","");
								setInput("u_enddate","");
								setInput("u_endtime","");
								setInput("u_address","");
								setInput("u_initialremarks","");
								setInput("u_orproc","");
								setInput("u_remarks","");
								setInput("u_prcno","");
								setInput("u_ptrno","");
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							setInput("u_gender","");
							setInput("u_age",0);
							setInput("u_civilstatus","");
							setInput("u_doctorid","");
							setInput("u_roomno","");
							setInput("u_startdate","");
							setInput("u_enddate","");
							setInput("u_endtime","");
							setInput("u_address","");
							setInput("u_initialremarks","");
							setInput("u_orproc","");
							setInput("u_remarks","");
							setInput("u_prcno","");
							setInput("u_ptrno","");
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_gender","");
						setInput("u_age",0);
						setInput("u_civilstatus","");
						setInput("u_doctorid","");
						setInput("u_roomno","");
						setInput("u_startdate","");
						setInput("u_enddate","");
						setInput("u_endtime","");
						setInput("u_address","");
						setInput("u_initialremarks","");
						setInput("u_orproc","");
						setInput("u_remarks","");
						setInput("u_prcno","");
						setInput("u_ptrno","");
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
	switch (column) {
		case "u_reftype":
			setInput("u_refno","",true);
			break;
		case "u_doctorid":
			if (getInput("u_doctorid")!="") {
				result = page.executeFormattedQuery("select a.u_prcno, a.u_ptrno from u_hisdoctors a where a.code='"+getInput("u_doctorid")+"'");	 
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("u_prcno",result.childNodes.item(0).getAttribute("u_prcno"));
						setInput("u_ptrno",result.childNodes.item(0).getAttribute("u_ptrno"));
					} else {
						setInput("u_prcno","");
						setInput("u_ptrno","");
						page.statusbar.showError("Invalid Doctor.");	
						return false;
					}
				} else {
					setInput("u_prcno","");
					setInput("u_ptrno","");
					page.statusbar.showError("Error retrieving Doctor Try Again, if problem persists, check the connection.");	
					return false;
				}
			} else {
				setInput("u_prcno","");
				setInput("u_ptrno","");
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
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_startdate,u_patientname from u_hisips")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_startdate,u_patientname from u_hisops")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`12`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``")); 			
			params["params"] += "&cflsortby=u_patientname";
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
	return params;
}

