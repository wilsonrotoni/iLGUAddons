// page events
//page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

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
		if (isInputEmpty("u_department")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_enddate")) return false;
	} else if (action=="a" || action=="sc") {
		if (isInputEmpty("u_department")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_enddate")) return false;
		if (isInputEmpty("u_preparedby")) return false;
		if (!checkPFGPSHIS()) return false;
		if (isInputNegative("u_totalamount")) return false;
	}
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
	}
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
				case "u_doctorname":
					if (getTableInput(table,column,row)!="") {
						switch (getInput("u_trxtype")) {
							case "LABORATORY":
								result = page.executeFormattedQuery("select name, code from u_hisdoctors where u_pathologistflag=1 and name like '"+getTableInput(table,column,row)+"%'");									
								break;
							case "RADIOLOGY":
								result = page.executeFormattedQuery("select name, code from u_hisdoctors where u_radiologistflag=1 and name like '"+getTableInput(table,column,row)+"%'");									
								break;
							case "HEARTSTATION":
								if (getTableInput("T1","u_reftype",row)=="IP") {
									result = page.executeFormattedQuery("select c.name, c.code from u_hisips a inner join u_hisipdoctors b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join u_hisdoctors c on c.code=b.u_doctorid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getTableInput("T1","u_refno",row)+"' and c.name like '"+getTableInput(table,column,row)+"%' union all select name, code from u_hisdoctors where u_cardiologistflag=1 and name like '"+getTableInput(table,column,row)+"%'");									
								} else {
									result = page.executeFormattedQuery("select name, code from u_hisdoctors where u_cardiologistflag=1 and name like '"+getTableInput(table,column,row)+"%'");									
								}
								break;
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_doctorid",result.childNodes.item(0).getAttribute("code"),row);
								setTableInput(table,"u_doctorname",result.childNodes.item(0).getAttribute("name"),row);
								focusTableInput(table,"u_pf",row);
							} else {
								setTableInput(table,"u_doctorid","",row);
								setTableInput(table,"u_doctorname","",row);
								page.statusbar.showError("Invalid Doctor.");	
								return false;
							}
						} else {
							setTableInput(table,"u_doctorid","",row);
							setTableInput(table,"u_doctorname","",row);
							page.statusbar.showError("Error retrieving doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_doctorid","",row);
						setTableInput(table,"u_doctorname","",row);
					}								
					break;
				case "u_pf":
					setTableInputAmount(table,"u_hf",getTableInputNumeric(table,"u_amount",row) - getTableInputNumeric(table,"u_pf",row),row);
					computeTotalGPSHIS();
					break;
			}
			break;
		default:
			switch (column) {
				case "u_startdate":
					clearTable("T1");
					break;
				case "u_enddate":
					setInput("u_docdate",getInput(column));
					clearTable("T1");
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
				case "u_department":
				case "u_reftype":
					clearTable("T1");
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
	if (Id.substring(0,18)=="df_u_doctornameT1r") {
		switch (getInput("u_trxtype")) {
			case "LABORATORY":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisdoctors where u_pathologistflag=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("40`10")); 			
				break;
			case "RADIOLOGY":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisdoctors where u_radiologistflag=1")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("40`10")); 			
				break;
			case "HEARTSTATION":
				//params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisdoctors where u_cardiologistflag=1")); 
				if (getTableInput("T1","u_reftype",Id.substring(18))=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select c.name as u_doctorname, b.u_doctorid, b.u_doctorservice, if(b.u_default=1,'Yes','') as u_ap, if(b.u_rod=1,'Yes','') as u_rod from u_hisips a inner join u_hisipdoctors b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join u_hisdoctors c on c.code=b.u_doctorid where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getTableInput("T1","u_refno",Id.substring(18))+"' union all select name, code, '' as u_doctorservice, '' as u_ap, '' as u_rod from u_hisdoctors where u_cardiologistflag=1")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID`Service`AP`ROD")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("40`10`20`3`3")); 			
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_hisdoctors where u_cardiologistflag=1")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
					params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("40`10")); 			
				}
				break;
		}
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
			if (elementFocused.substring(0,9)=="df_u_pfT1") {
				focusTableInput(table,"u_pf",row);
			} else if (elementFocused.substring(0,17)=="df_u_doctornameT1") {
				focusTableInput(table,"u_doctorname",row);
			}
			break;
	}
	return params;
}

function computeTotalGPSHIS() {
	var rc =  getTableRowCount("T1"), hf=0, pf=0, total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			hf += getTableInputNumeric("T1","u_hf",i);
			pf += getTableInputNumeric("T1","u_pf",i);
		}
	}
	setInputAmount("u_totalhf",hf);	
	setInputAmount("u_totalpf",pf);	
}

function OpenLnkBtnu_hischarges(targetId) {
	OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
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

function checkPFGPSHIS() {
	var rc =  getTableRowCount("T1");
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_doctorname",i)!="" || getTableInputNumeric("T1","u_pf",i)!=0) {
				if (getTableInput("T1","u_doctorname",i)=="" || getTableInputNumeric("T1","u_pf",i)==0) {
					selectTableRow("T1",i);
					if (getTableInput("T1","u_doctorname",i)=="") {
						page.statusbar.showError("Doctor is required.");	
						focusTableInput("T1","u_doctorname",i);
					} else {
						page.statusbar.showError("PF is required.");	
						focusTableInput("T1","u_pf",i);
					}
					return false;	
				}
			}
		}
	}
	return true;
}
