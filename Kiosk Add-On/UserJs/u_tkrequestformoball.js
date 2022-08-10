// page events
page.events.add.load('onPageLoadGPSKiosk');
//page.events.add.resize('onPageResizeGPSKiosk');
page.events.add.submit('onPageSubmitGPSKiosk');
page.events.add.submitreturn('onPageSubmitReturnGPSKiosk');
//page.events.add.cfl('onCFLGPSKiosk');
page.events.add.cflgetparams('onCFLGetParamsGPSKiosk');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSKiosk');

// element events
//page.elements.events.add.focus('onElementFocusGPSKiosk');
//page.elements.events.add.keydown('onElementKeyDownGPSKiosk');
page.elements.events.add.validate('onElementValidateGPSKiosk');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSKiosk');
//page.elements.events.add.changing('onElementChangingGPSKiosk');
//page.elements.events.add.change('onElementChangeGPSKiosk');
//page.elements.events.add.click('onElementClickGPSKiosk');
//page.elements.events.add.cfl('onElementCFLGPSKiosk');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSKiosk');

// table events
//page.tables.events.add.reset('onTableResetRowGPSKiosk');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSKiosk');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSKiosk');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSKiosk');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSKiosk');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSKiosk');
//page.tables.events.add.delete('onTableDeleteRowGPSKiosk');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSKiosk');
//page.tables.events.add.select('onTableSelectRowGPSKiosk');

function onPageLoadGPSKiosk() {
	if(getInput("code") == "XXXXX") {
		alert("SESSION expired.. system autolog-out..");
		logOut();
	}
}

function onPageResizeGPSKiosk(width,height) {
}

function onPageSubmitGPSKiosk(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_empname")) return false;
		if (isInputEmpty("u_appdate")) return false;
		if (isInputEmpty("u_profitcenter")) return false;
		if (isInputEmpty("u_assreason")) return false;
		if (getTableRowCount("T1")=="0") {
			page.statusbar.showError("Invalid No Record Data.. Try Again");	
			return false;
		}
	} else if (action=="ccn") {
		if (window.confirm("Are you Sure. Continue?")==false)	return false;
		setInput("u_status",-1);
		formSubmit('sc');
	} else if (action=="ah") {
		showPopupFrame("popupFrameApprovedHistory",true);
		return false;
	} else if (action=="add_data") {
		AutoDaysProcess();
		return false;
	} else if (action=="d") {
		if (window.confirm("Are you Sure. Continue?")==false)	return false;
	}
	return true;
}

function onPageSubmitReturnGPSKiosk(action,success,error) {
		try {
				//window.opener.setKey("keys",window.opener.getInput("code"));
				window.opener.location.reload();
			} catch(TheError) {
		}
			if (success) window.close();
}

function onCFLGPSKiosk(Id) {
	return true;
}

function onCFLGetParamsGPSKiosk(elementId,params) {
	switch (elementId) {
		case "find":
			params["params"] += ";-WHERE:u_empid = '"+getGlobal("userid")+"'";	
			break;
	}
	return params;
}

function onTaskBarLoadGPSKiosk() {
}

function onElementFocusGPSKiosk(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSKiosk(element,event,column,table,row) {
}

function onElementValidateGPSKiosk(element,column,table,row) {
switch (table) {
	case "T1":
		switch(column) {
			case "u_type_filter":
				if (getTableInput(table,"u_type_filter")=="Days") {
					enableTableInput(table,"u_obfromtime");
					enableTableInput(table,"u_obtotime");
					enableTableInput(table,"u_ob_tot_hrs");
					disableTableInput(table,"u_ob_wd");
					disableTableInput(table,"u_ob_ot");
					setTableInput(table,"u_obfromtime","");
					setTableInput(table,"u_obtotime","");
					setTableInputAmount(table,"u_ob_tot_hrs",0);
					setTableInputAmount(table,"u_ob_wd",0);
					setTableInputAmount(table,"u_ob_ot",0);
				} else if (getTableInput(table,"u_type_filter")=="AM") {
					enableTableInput(table,"u_obfromtime");
					disableTableInput(table,"u_obtotime");
					enableTableInput(table,"u_ob_tot_hrs");
					disableTableInput(table,"u_ob_wd");
					disableTableInput(table,"u_ob_ot");
					setTableInput(table,"u_obfromtime","");
					setTableInput(table,"u_obtotime","");
					setTableInputAmount(table,"u_ob_tot_hrs",0);
					setTableInputAmount(table,"u_ob_wd",0);
					setTableInputAmount(table,"u_ob_ot",0);
				} else if (getTableInput(table,"u_type_filter")=="PM") {
					disableTableInput(table,"u_obfromtime");
					enableTableInput(table,"u_obtotime");
					enableTableInput(table,"u_ob_tot_hrs");
					disableTableInput(table,"u_ob_wd");
					disableTableInput(table,"u_ob_ot");
					setTableInput(table,"u_obfromtime","");
					setTableInput(table,"u_obtotime","");
					setTableInputAmount(table,"u_ob_tot_hrs",0);
					setTableInputAmount(table,"u_ob_wd",0);
					setTableInputAmount(table,"u_ob_ot",0);
				} else if (getTableInput(table,"u_type_filter")=="-") {
					disableTableInput(table,"u_obfromtime");
					disableTableInput(table,"u_obtotime");
					disableTableInput(table,"u_ob_tot_hrs");
					disableTableInput(table,"u_ob_wd");
					disableTableInput(table,"u_ob_ot");
					setTableInput(table,"u_obfromtime","");
					setTableInput(table,"u_obtotime","");
					setTableInputAmount(table,"u_ob_tot_hrs",0);
					setTableInputAmount(table,"u_ob_wd",0);
					setTableInputAmount(table,"u_ob_ot",0);
				}
				break;
			case "u_obfromtime":
			case "u_obtotime":
			case "u_ob_tot_hrs":
				if (getTableInput(table,"u_type_filter")=="Days") {
					if (getTableInput(table,"u_obfromtime")!="" && getTableInput(table,"u_ob_tot_hrs")!=0) {
						var daysFrom = formatDateToDB(getTableInput(table,"u_date_filter"))+" "+formatTimeToDB(getTableInput(table,"u_obfromtime"));
						var result = page.executeFormattedQuery("SELECT DATE_FORMAT(DATE_ADD(DATE_FORMAT('"+daysFrom+"','%Y-%m-%d %H:%i:%s'),INTERVAL "+getTableInput(table,"u_ob_tot_hrs")+" HOUR),'%H:%i:%s') as timeto,u_whpd FROM u_premploymentinfo WHERE company='" + getGlobal("company") + "' AND branch='" + getGlobal("branch") + "' AND code = '"+getGlobal("userid")+"'");
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									total_hrs = getTableInput(table,"u_ob_tot_hrs")-1;
									wd_hrs = result.childNodes.item(0).getAttribute("u_whpd");
									
									if((total_hrs-wd_hrs/2) < 0) {
										page.statusbar.showError("Error Total Hrs is Less Than in Working Hrs or Half Day Working Hrs");	
										return false;
									}
									
									if(total_hrs-wd_hrs >= 0) {
										ex_hrs = total_hrs-wd_hrs;
									} else {
										ex_hrs = 0;
									}
									setTableInput(table,"u_obtotime",formatTimeToHttp(result.childNodes.item(0).getAttribute("timeto")));
									setTableInputAmount(table,"u_ob_wd",wd_hrs);
									setTableInputAmount(table,"u_ob_ot",ex_hrs);
								} else {
									setTableInput(table,"u_obfromtime","");
									setTableInput(table,"u_obtotime","");
									setTableInputAmount(table,"u_ob_tot_hrs",0);
									setTableInputAmount(table,"u_ob_wd",0);
									setTableInputAmount(table,"u_ob_ot",0);
									page.statusbar.showError("Error retrieving Day. Try Again, if problem persists, check the connection.");	
									return false;
								}
							} else {
								setTableInput(table,"u_obfromtime","");
								setTableInput(table,"u_obtotime","");
								setTableInputAmount(table,"u_ob_tot_hrs",0);
								setTableInputAmount(table,"u_ob_wd",0);
								setTableInputAmount(table,"u_ob_ot",0);
								page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
								return false;
							}
					} else if (getTableInput(table,"u_obfromtime")!="" && getTableInput(table,"u_obtotime")!="") {
						var daysFrom = formatDateToDB(getTableInput(table,"u_date_filter"))+" "+formatTimeToDB(getTableInput(table,"u_obfromtime"));
						var daysTo = formatDateToDB(getTableInput(table,"u_date_filter"))+" "+formatTimeToDB(getTableInput(table,"u_obtotime"));
						var result = page.executeFormattedQuery("SELECT (TIMESTAMPDIFF(MINUTE,'"+daysFrom+"','"+daysTo+"')/60) as totalhrs,u_whpd FROM u_premploymentinfo WHERE company='" + getGlobal("company") + "' AND branch='" + getGlobal("branch") + "' AND code = '"+getGlobal("userid")+"'");
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									total_hrs = result.childNodes.item(0).getAttribute("totalhrs")-1;
									wd_hrs = result.childNodes.item(0).getAttribute("u_whpd");
									
									if((total_hrs-wd_hrs/2) < 0) {
										page.statusbar.showError("Error Total Hrs is Less Than in Working Hrs or Half Day Working Hrs");	
										return false;
									}
									
									if(total_hrs-wd_hrs >= 0) {
										ex_hrs = total_hrs-wd_hrs;
									} else {
										ex_hrs = 0;
									}
									setTableInputAmount(table,"u_ob_tot_hrs",total_hrs);
									setTableInputAmount(table,"u_ob_wd",wd_hrs);
									setTableInputAmount(table,"u_ob_ot",ex_hrs);
								} else {
									setTableInput(table,"u_obfromtime","");
									setTableInput(table,"u_obtotime","");
									setTableInputAmount(table,"u_ob_tot_hrs",0);
									setTableInputAmount(table,"u_ob_wd",0);
									setTableInputAmount(table,"u_ob_ot",0);
									page.statusbar.showError("Error retrieving Day. Try Again, if problem persists, check the connection.");	
									return false;
								}
							} else {
								setTableInput(table,"u_obfromtime","");
								setTableInput(table,"u_obtotime","");
								setTableInputAmount(table,"u_ob_tot_hrs",0);
								setTableInputAmount(table,"u_ob_wd",0);
								setTableInputAmount(table,"u_ob_ot",0);
								page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
								return false;
							}
					}
				}
				break;
			}
	break;
	default:
		switch(column) {
			case "u_empname":
				if (getInput("u_empname")!="") {
					result = page.executeFormattedQuery("SELECT a.name,a.code FROM u_premploymentinfo a LEFT OUTER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND b.u_branch = '"+getInput("u_profitcenter")+"' AND a.name = '"+getInput("u_empname")+"' AND a.u_currentstatus NOT IN('IA','')");	
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							setInput("u_empid",result.childNodes.item(0).getAttribute("code"));
							setInput("u_empname",result.childNodes.item(0).getAttribute("name"));
						} else {
							setInput("u_empid","");
							setInput("u_empname","");
							page.statusbar.showError("Error retrieving Employee. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_empid","");
						setInput("u_empname","");
						page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
						return false;
					}
				} else {
					setInput("u_empid","");
					setInput("u_empname","");
				}
			break;
		}
	}
}

function onElementGetValidateParamsGPSKiosk(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSKiosk(element,column,table,row) {
	return true;
}

function onElementChangeGPSKiosk(element,column,table,row) {
	return true;
}

function onElementClickGPSKiosk(element,column,table,row) {
	return true;
}

function onElementCFLGPSKiosk(element) {
	return true;
}

function onElementCFLGetParamsGPSKiosk(Id,params) {
switch (Id) {
		case "df_u_empname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT a.name,a.code FROM u_premploymentinfo a LEFT OUTER JOIN u_premploymentdeployment b ON b.company = a.company AND b.branch = a.branch AND b.code = a.code WHERE a.company='" + getGlobal("company") + "' and a.branch='" + getGlobal("branch") + "' AND b.u_branch = '"+getInput("u_profitcenter")+"' AND a.u_currentstatus NOT IN('IA','')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`20")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cflsortby=name";
			break;
			}
	return params;
}

function onTableResetRowGPSKiosk(table) {
}

function onTableBeforeInsertRowGPSKiosk(table) {
	switch(table){
		case "T1":
			if (isTableInputEmpty(table,"u_date_filter")) return false;
			if (isTableInputEmpty(table,"u_type_filter")) return false;
			
			if(getTableInput(table,"u_ob_tot_hrs") == 0) {
				page.statusbar.showError("Invalid Zero Total Hrs.. Try Again");	
				return false;
			}
			
			if(searchTableInput(table,"u_date_filter",getTableInput(table,"u_date_filter"),true,1) != "0") {
				page.statusbar.showError("You Have That Request Date : "+getTableInput(table,"u_date_filter"));
				resetTableRow(table,true);
				return false;
			}
			
			if (getTableInput(table,"u_type_filter")=="Days") {
				if (isTableInputEmpty(table,"u_obfromtime")) return false;
			} else if (getTableInput(table,"u_type_filter")=="AM") {
				if (isTableInputEmpty(table,"u_obfromtime")) return false;
			} else if (getTableInput(table,"u_type_filter")=="PM") {
				if (isTableInputEmpty(table,"u_obtotime")) return false;
			}
			var checklockec = page.executeFormattedSearch("SELECT b.u_kiosklocked FROM u_premploymentinfo a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"' AND '"+formatDateToDB(getTableInput(table,"u_date_filter"))+"' BETWEEN b.u_from_date AND b.u_to_date");
			if(checklockec == "LOCK") {
				page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
				return false;
			}
		break;
	}
	return true;
}

function onTableAfterInsertRowGPSKiosk(table,row) {
}

function onTableBeforeUpdateRowGPSKiosk(table,row) {
	switch(table){
		case "T1":
			if (isTableInputEmpty(table,"u_date_filter")) return false;
			if (isTableInputEmpty(table,"u_type_filter")) return false;
			
			if(getTableInput(table,"u_ob_tot_hrs") == 0) {
				page.statusbar.showError("Invalid Zero Total Hrs.. Try Again");	
				return false;
			}
			
			if (getTableInput(table,"u_type_filter")=="Days") {
				if (isTableInputEmpty(table,"u_obfromtime")) return false;
			} else if (getTableInput(table,"u_type_filter")=="AM") {
				if (isTableInputEmpty(table,"u_obfromtime")) return false;
			} else if (getTableInput(table,"u_type_filter")=="PM") {
				if (isTableInputEmpty(table,"u_obtotime")) return false;
			}
			var checklockec = page.executeFormattedSearch("SELECT b.u_kiosklocked FROM u_premploymentinfo a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"' AND '"+formatDateToDB(getTableInput(table,"u_date_filter"))+"' BETWEEN b.u_from_date AND b.u_to_date");
			if(checklockec == "LOCK") {
				page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
				return false;
			}
		break;
	}
	return true;
}

function onTableAfterUpdateRowGPSKiosk(table,row) {
}

function onTableBeforeDeleteRowGPSKiosk(table,row) {
	return true;
}

function onTableDeleteRowGPSKiosk(table,row) {
}

function onTableBeforeSelectRowGPSKiosk(table,row) {
	return true;
}

function onTableSelectRowGPSKiosk(table,row) {
}

function AutoDaysProcess() {
	if (isInputEmpty("ob_type")) return false;
	if (isInputEmpty("ob_hrs")) return false;
	if (isInputEmpty("ob_place")) return false;
	if (getInput("date1")!="" && getInput("date2")!="" && getInput("date2")!="0") {
		result = page.executeFormattedQuery("SELECT TIMESTAMPDIFF(DAY,'"+formatDateToDB(getInput("date1"))+"','"+formatDateToDB(getInput("date2"))+"')+1 as code");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				computeDaysGPSKiosk(result.childNodes.item(0).getAttribute("code"));
			} else {
				page.statusbar.showError("Error retrieving Days. Try Again");		
				return false;
			}
		} else {
			page.statusbar.showError("Error retrieving Days. Try Again, if problem persists, check the connection.");	
			return false;
		}
	}	
	
}

function computeDaysGPSKiosk(number) {
var result, data = new Array();;
var rc = number;
var daysnumber = 0;
	clearTable("T1",true);
	for (i = 1; i <= rc; i++) {
		result = page.executeFormattedQuery("SELECT DATE_ADD('"+formatDateToDB(getInput("date1"))+"',INTERVAL "+i+"-1 DAY) as dates");	 
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					data["u_date_filter"] = formatDateToHttp(result.childNodes.item(0).getAttribute("dates"));
					data["u_type_filter"] = getInput("ob_type");
					
					if (getInput("ob_type")=="Days") {
						if (isInputEmpty("ob_time1")) return false;
						if(getInput("ob_time2")=="") {
							if (getInput("ob_time1")!="" && getInput("ob_hrs")!=0) {
								var daysFrom = result.childNodes.item(0).getAttribute("dates")+" "+formatTimeToDB(getInput("ob_time1"));
								var result2 = page.executeFormattedQuery("SELECT DATE_FORMAT(DATE_ADD(DATE_FORMAT('"+daysFrom+"','%Y-%m-%d %H:%i:%s'),INTERVAL "+getInput("ob_hrs")+" HOUR),'%H:%i:%s') as timeto,u_whpd FROM u_premploymentinfo WHERE company='" + getGlobal("company") + "' AND branch='" + getGlobal("branch") + "' AND code = '"+getGlobal("userid")+"'");
									if (result2.getAttribute("result")!= "-1") {
										if (parseInt(result2.getAttribute("result"))>0) {
											total_hrs = getInput("ob_hrs");
											wd_hrs = result2.childNodes.item(0).getAttribute("u_whpd");
											
											if((total_hrs-wd_hrs/2) < 0) {
												page.statusbar.showError("Error Total Hrs is Less Than in Working Hrs or Half Day Working Hrs");	
												return false;
											}
											
											if(total_hrs-wd_hrs >= 0) {
												ex_hrs = total_hrs-wd_hrs;
											} else {
												ex_hrs = 0;
											}
											data["u_obfromtime"] = formatTimeToHttp(getInput("ob_time1"));
											data["u_obtotime"] = formatTimeToHttp(result2.childNodes.item(0).getAttribute("timeto"));
											data["u_ob_tot_hrs"] = total_hrs;
											data["u_ob_wd"] = formatNumber(wd_hrs,"amount");
											data["u_ob_ot"] = formatNumber(ex_hrs,"amount");
										} else {
											page.statusbar.showError("Error retrieving Day. Try Again, if problem persists, check the connection.");	
											return false;
										}
									} else {
										page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
										return false;
									}
								}
							} else if(getInput("ob_time2")!="") {
								if (getInput("ob_time1")!="" && formatTimeToDB(getInput("ob_time1")) <= formatTimeToDB(getInput("ob_time2"))) {
								var daysFrom = result.childNodes.item(0).getAttribute("dates")+" "+formatTimeToDB(getInput("ob_time1"));
								var daysTo = result.childNodes.item(0).getAttribute("dates")+" "+formatTimeToDB(getInput("ob_time2"));
								var result2 = page.executeFormattedQuery("SELECT if(TIMESTAMPDIFF(MINUTE,DATE_FORMAT('"+daysFrom+"','%Y-%m-%d %H:%i:%s'),DATE_FORMAT('"+daysTo+"','%Y-%m-%d %H:%i:%s')) >= 0,TIMESTAMPDIFF(MINUTE,DATE_FORMAT('"+daysFrom+"','%Y-%m-%d %H:%i:%s'),DATE_FORMAT('"+daysTo+"','%Y-%m-%d %H:%i:%s')),TIMESTAMPDIFF(MINUTE,DATE_FORMAT('"+daysFrom+"','%Y-%m-%d %H:%i:%s'),DATE_ADD(DATE_FORMAT('"+daysTo+"','%Y-%m-%d %H:%i:%s'),INTERVAL 1 DAY))) as totalhrs,u_whpd FROM u_premploymentinfo WHERE company='" + getGlobal("company") + "' AND branch='" + getGlobal("branch") + "' AND code = '"+getGlobal("userid")+"'");
									if (result2.getAttribute("result")!= "-1") {
										if (parseInt(result2.getAttribute("result"))>0) {
											total_hrs = result2.childNodes.item(0).getAttribute("totalhrs")/60;
											wd_hrs = result2.childNodes.item(0).getAttribute("u_whpd");
											
											if((total_hrs-wd_hrs/2) < 0) {
												page.statusbar.showError("Error Total Hrs is Less Than in Working Hrs or Half Day Working Hrs");	
												return false;
											}
											
											if(total_hrs-wd_hrs >= 0) {
												ex_hrs = total_hrs-wd_hrs;
											} else {
												ex_hrs = 0;
											}
											data["u_obfromtime"] = formatTimeToHttp(getInput("ob_time1"));
											data["u_obtotime"] = (getInput("ob_time2"));
											data["u_ob_tot_hrs"] = formatNumber(total_hrs,"amount");
											data["u_ob_wd"] = formatNumber(wd_hrs,"amount");
											data["u_ob_ot"] = formatNumber(ex_hrs,"amount");
										} else {
											page.statusbar.showError("Error retrieving Day. Try Again, if problem persists, check the connection.");	
											return false;
										}
									} else {
										page.statusbar.showError("Error retrieving Network. Try Again, if problem persists, check the connection.");	
										return false;
									}
								} else {
									page.statusbar.showError("Invalid Time Entry..");	
									return false;
								}
							}
						} else if (getInput("ob_type")=="AM") {
							if(getInput("ob_time1") != "") {
								data["u_obfromtime"] = formatTimeToHttp(getInput("ob_time1"));
								data["u_obtotime"] = "";
								data["u_ob_tot_hrs"] = getInput("ob_hrs");
								data["u_ob_wd"] = 0;
								data["u_ob_ot"] = 0;
							} else {
								page.statusbar.showError("Invalid Time Entry.. From Time");	
								return false;
							}
						} else if (getInput("ob_type")=="PM") {
							if(getInput("ob_time2") != "") {
								data["u_obfromtime"] = "";
								data["u_obtotime"] = getInput("ob_time2");
								data["u_ob_tot_hrs"] = getInput("ob_hrs");
								data["u_ob_wd"] = 0;
								data["u_ob_ot"] = 0;
							} else {
								page.statusbar.showError("Invalid Time Entry.. To Time");	
								return false;
							}
						}
					data["u_pot"] = getInput("ob_place");
					insertTableRowFromArray("T1",data);
					daysnumber++;
					
				}
			}
	}
}