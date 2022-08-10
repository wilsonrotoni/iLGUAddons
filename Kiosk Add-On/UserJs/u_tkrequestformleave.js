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
page.elements.events.add.click('onElementClickGPSKiosk');
//page.elements.events.add.cfl('onElementCFLGPSKiosk');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSKiosk');

// table events
//page.tables.events.add.reset('onTableResetRowGPSKiosk');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSKiosk');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSKiosk');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSKiosk');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSKiosk');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSKiosk');
//page.tables.events.add.delete('onTableDeleteRowGPSKiosk');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSKiosk');
page.tables.events.add.select('onTableSelectRowGPSKiosk');

function onPageLoadGPSKiosk() {
	if(getInput("code") == "XXXXX") {
		alert("SESSION expired.. system autolog-out..");
		logOut();
	}
	showLeaveNotify();
}

function onPageResizeGPSKiosk(width,height) {
}

function onPageSubmitGPSKiosk(action) {
		if (action=="a" || action=="sc") {
			if (isInputEmpty("u_empname")) return false;
			if (isInputEmpty("u_appdate")) return false;
			if (isInputEmpty("u_profitcenter")) return false;
			if (isInputEmpty("u_leavereason")) return false;
			if (isInputEmpty("u_leavetype")) return false;
			
			var checklockec = page.executeFormattedSearch("SELECT b.u_kiosklocked FROM u_premploymentinfo a INNER JOIN u_prpayrollperioddates b ON b.company = a.company AND b.branch = a.branch AND REPLACE(b.code,LEFT(b.code,4),'') = a.u_payfrequency WHERE a.company = '"+getGlobal("company")+"' AND a.branch = '"+getGlobal("branch")+"' AND a.code = '"+getGlobal("userid")+"' AND '"+formatDateToDB(getInput("u_leavefrom"))+"' BETWEEN b.u_from_date AND b.u_to_date");
				if(checklockec == "LOCK") {
					page.statusbar.showError("This Period is already Locked.. Please Contact the Adminitrator or H.R.");		
					return false;
				}
				
			var daysleave = page.executeFormattedSearch("SELECT TIMESTAMPDIFF(DAY,'"+formatDateToDB(getInput("u_leavefrom"))+"','"+formatDateToDB(getInput("u_leaveto"))+"') as code");
				if(daysleave < 0) {
					page.statusbar.showError("From & To Date for this Request is invalid..");		
					return false;
				}
			
			if(getInputNumeric("u_leavedays") <= 0) {
					page.statusbar.showError("No Leave Days for the request..");		
					return false;
				}
				
			if(getTableRowCount("T1") <= 0) {
					page.statusbar.showError("No Leave Detail.. Please Add");		
					return false;
				}
			
			if(getInput("u_status") != -1) {
				var remainingbalance = page.executeFormattedSearch("SELECT SUM(x.balance-x.u_leavedays) as balance FROM (SELECT bb.u_date_filter,e.name,a.u_leavetype,a.u_leavereason,0 as balance,bb.u_leavedays,'B' as modes FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid bb ON bb.company = a.company AND bb.branch = a.branch AND bb.code = a.code INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid INNER JOIN u_tkleavemasterfiles e ON e.company = a.company AND e.branch = a.branch AND e.code = a.u_leavetype WHERE c.code = '"+getGlobal("userid")+"' AND a.u_status IN(1,0) UNION ALL SELECT u_dates,'Reset',u_leavetype,'' as reason,(-1*u_balance),0,'A' FROM u_tkleaveresetbalance WHERE u_empid = '"+getGlobal("userid")+"' UNION ALL SELECT a.u_date_filter,'Balance' as name,a.u_typeofleave,'' as reason,a.u_balance,0 as leavedays,'A' as modes FROM u_hremploymentinfoleavebal a INNER JOIN u_tkleavemasterfiles e ON e.company = a.company AND e.branch = a.branch AND e.code = a.u_typeofleave INNER JOIN u_prglobalsetting xx ON xx.company = a.company AND xx.branch = a.branch WHERE a.code = '"+getGlobal("userid")+"') as x WHERE x.u_leavetype IN(SELECT a.code FROM u_prglobalsetting b INNER JOIN u_tkleavemasterfiles a ON b.company = a.company AND b.branch = a.branch LEFT JOIN (SELECT branch,company,MAX(u_leavetype) as status1,code as codes FROM u_tkleavemasterfiles WHERE code = '"+getInput("u_leavetype")+"' GROUP BY u_leavetype) as x ON x.company = a.company AND x.branch = a.branch WHERE IF(b.u_leavetype = 1,IF(x.status1 = 'Y',a.u_leavetype = 'Y',a.code = x.codes),a.code = '"+getInput("u_leavetype")+"'))");
				if(remainingbalance-getInputNumeric("u_leavedays") < 0) {
						page.statusbar.showError("No Available Leave for the request.. Remaining Balance [ '"+remainingbalance+"' ]");		
						return false;
					}
				
			}
		} else if (action=="ccn") {
			if (window.confirm("Are you Sure. Continue?")==false)	return false;
			setInput("u_status",-1);
			formSubmit('sc');
		} else if (action=="ah") {
			showPopupFrame("popupFrameApprovedHistory",true);
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
				case "u_leavefrom":
				case "u_leaveto":
				if (getInput("u_leavefrom")!="" && getInput("u_leaveto")!="") {
					result = page.executeFormattedQuery("SELECT TIMESTAMPDIFF(DAY,'"+formatDateToDB(getInput("u_leavefrom"))+"','"+formatDateToDB(getInput("u_leaveto"))+"')+1 as code");	 
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							computeDaysGPSKiosk(result.childNodes.item(0).getAttribute("code"));
						} else {
							setInput("u_leavedays",0);
							page.statusbar.showError("Error retrieving Days. Try Again");		
							return false;
						}
					} else {
						setInput("u_leavedays",0);
						page.statusbar.showError("Error retrieving Days. Try Again, if problem persists, check the connection.");	
						return false;
					}
				} else {
					setInput("u_leavedays",0);
				}
				break;
				case "u_leavetype":
				if (getInput("u_leavetype")!="") {
					var remainingbalance = page.executeFormattedSearch("SELECT SUM(x.balance-x.u_leavedays) as balance FROM (SELECT bb.u_date_filter,e.name,a.u_leavetype,a.u_leavereason,0 as balance,bb.u_leavedays,'B' as modes FROM u_tkrequestformleave a INNER JOIN u_tkrequestformleavegrid bb ON bb.company = a.company AND bb.branch = a.branch AND bb.code = a.code INNER JOIN u_premploymentinfo c ON c.company = a.company AND c.branch = a.branch AND c.code = a.u_empid INNER JOIN u_tkleavemasterfiles e ON e.company = a.company AND e.branch = a.branch AND e.code = a.u_leavetype WHERE c.code = '"+getGlobal("userid")+"' AND a.u_status IN(1,0) UNION ALL SELECT u_dates,'Reset',u_leavetype,'' as reason,(-1*u_balance),0,'A' FROM u_tkleaveresetbalance WHERE u_empid = '"+getGlobal("userid")+"' UNION ALL SELECT a.u_date_filter,'Balance' as name,a.u_typeofleave,'' as reason,a.u_balance,0 as leavedays,'A' as modes FROM u_hremploymentinfoleavebal a INNER JOIN u_tkleavemasterfiles e ON e.company = a.company AND e.branch = a.branch AND e.code = a.u_typeofleave INNER JOIN u_prglobalsetting xx ON xx.company = a.company AND xx.branch = a.branch WHERE a.code = '"+getGlobal("userid")+"') as x WHERE x.u_leavetype IN(SELECT a.code FROM u_prglobalsetting b INNER JOIN u_tkleavemasterfiles a ON b.company = a.company AND b.branch = a.branch LEFT JOIN (SELECT branch,company,MAX(u_leavetype) as status1,code as codes FROM u_tkleavemasterfiles WHERE code = '"+getInput("u_leavetype")+"' GROUP BY u_leavetype) as x ON x.company = a.company AND x.branch = a.branch WHERE IF(b.u_leavetype = 1,IF(x.status1 = 'Y',a.u_leavetype = 'Y',a.code = x.codes),a.code = '"+getInput("u_leavetype")+"'))");
					
					result = page.executeFormattedQuery("SELECT u_leavestatus FROM u_tkleavemasterfiles WHERE code = '"+getInput("u_leavetype")+"'");	 
					if (result.getAttribute("result")!= "-1") {
						if (parseInt(result.getAttribute("result"))>0) {
							setInput("u_leavedaystatus",result.childNodes.item(0).getAttribute("u_leavestatus"));
							if(result.childNodes.item(0).getAttribute("u_leavestatus") == "LWOP") {
								setInput("u_leavebal",0);
							} else if(result.childNodes.item(0).getAttribute("u_leavestatus") == "WP") {
								setInput("u_leavebal",remainingbalance);
							}
						} else {
							setInput("u_leavedaystatus","");
							page.statusbar.showError("Error retrieving LWOP and WP. Try Again");		
							return false;
						}
					} else {
						setInput("u_leavedaystatus","");
						page.statusbar.showError("Error retrieving Days. Try Again, if problem persists, check the connection.");	
						return false;
					}
				}
				break;
			}
	}
	return true;
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
	switch (table) {
		case "T1":
			switch(column) {
				case "u_pm":
					if (getTableInput(table,"u_pm",row)== 1) {
						setTableInput(table,"u_am",0,row);
						setTableInput(table,"u_lv",0,row);
						setTableInput(table,"u_scheduleassign","HD_PM",row)
						setTableInputAmount(table,"u_leavedays",0.5,row);
						computeDaysGridGPSKiosk();
					} else if (getTableInput(table,"u_pm",row)== 0) {
						setTableInput(table,"u_pm",1,row);
						page.statusbar.showError("Error for No Check. Try Again");		
						return false;
					}
					break;
				case "u_am":
					if (getTableInput(table,"u_am",row)== 1) {
						setTableInput(table,"u_pm",0,row);
						setTableInput(table,"u_lv",0,row);
						setTableInput(table,"u_scheduleassign","HD_AM",row)
						setTableInputAmount(table,"u_leavedays",0.5,row);
						computeDaysGridGPSKiosk();
					} else if (getTableInput(table,"u_am",row)== 0) {
						setTableInput(table,"u_am",1,row);
						page.statusbar.showError("Error for No Check. Try Again");		
						return false;
					}
					break;
				case "u_lv":
					if (getTableInput(table,"u_lv",row)== 1) {
						setTableInput(table,"u_am",0,row);
						setTableInput(table,"u_pm",0,row);
						setTableInput(table,"u_scheduleassign","Leave",row)
						setTableInputAmount(table,"u_leavedays",1,row);
						computeDaysGridGPSKiosk();
					} else if (getTableInput(table,"u_lv",row)== 0) {
						setTableInput(table,"u_lv",1,row);
						page.statusbar.showError("Error for No Check. Try Again");		
						return false;
					}
					break;
				}
		break;
	}
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
		default:
			if (Id.substring(0,22)=="df_u_scheduleassignT1r") {
				if (getTableInput("T1","u_pm",Id.substring(24,22))== 1) {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_tkschedulemasterfiles WHERE company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' AND u_typeofschedule = 'HPM'"));
				} else if (getTableInput("T1","u_lv",Id.substring(24,22))== 1) {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_tkschedulelist WHERE company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' AND u_other = 'S'"));
				} else if (getTableInput("T1","u_am",Id.substring(24,22))== 1) {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_tkschedulemasterfiles WHERE company='" + getGlobal("company") + "' and branch='" + getGlobal("branch") + "' AND u_typeofschedule = 'HAM'"));
				}
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code`Decription")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`70")); 					
			}
			break;
			}
	return params;
}

function onTableResetRowGPSKiosk(table) {
}

function onTableBeforeInsertRowGPSKiosk(table) {
	return true;
}

function onTableAfterInsertRowGPSKiosk(table,row) {
}

function onTableBeforeUpdateRowGPSKiosk(table,row) {
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

function onBeforeEditRow(p_tableId,p_rowIdx) {
	switch(p_tableId){
			case "T1":
				if(getInput("u_status") == 0) {
					if (window.confirm("Confirm Delete?")==true) {
						deleteTableRow(p_tableId,p_rowIdx,false,true);
						computeDaysGridGPSKiosk();
					}
				}
				//OpenLnkBtnu_sistimesetup("df_u_classcodeT101r"+p_rowIdx);
			break;
		}
		return true;	
}

function onTableSelectRowGPSKiosk(table,row) {
var params = new Array();
	switch (table) {
		case "T1":
				params["focus"] = false;
				if (elementFocused.substring(0,21)=="df_u_scheduleassignT1") {
					focusTableInput(table,"u_scheduleassign",row);
				}
			break;
	}
	return params;
}

function computeDaysGPSKiosk(number) {
var result, data = new Array();;
var rc = number;
var daysnumber = 0;
	clearTable("T1",true);
	for (i = 1; i <= rc; i++) {
		result = page.executeFormattedQuery("SELECT DATE_ADD('"+formatDateToDB(getInput("u_leavefrom"))+"',INTERVAL "+i+"-1 DAY) as dates,'Leave' as scheduleassign,1 as days,DATE_FORMAT(DATE(DATE_ADD('"+formatDateToDB(getInput("u_leavefrom"))+"',INTERVAL "+i+"-1 DAY)), '%W %M %d, %Y') as datename");	 
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					data["u_date_filter"] = formatDateToHttp(result.childNodes.item(0).getAttribute("dates"));
					data["u_date_filter.text"] = result.childNodes.item(0).getAttribute("datename");
					data["u_pm"] = 0;
					data["u_am"] = 0;
					data["u_lv"] = 1;
					data["u_scheduleassign"] = result.childNodes.item(0).getAttribute("scheduleassign");
					data["u_leavedays"] = formatNumber(result.childNodes.item(0).getAttribute("days"),"amount");
					data["u_scheduleassign.cfl"] = "OpenCFLfs()";
					insertTableRowFromArray("T1",data);
					daysnumber++;
					
				}
			}
	}
	setInput("u_leavedays",daysnumber);
}

function computeDaysGridGPSKiosk() {
	var rc1 =  getTableRowCount("T1");
	var days =  0;
	
	for (i = 1; i <= rc1; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			days += getTableInputNumeric("T1","u_leavedays",i);
		}
	}
	
	setInput("u_leavedays",days);
}

function showLeaveNotify() {
	var result,result2;
	var data = new Array();
		result2 = page.executeFormattedQuery("CALL leave_running_balance('"+getGlobal("company")+"','"+getGlobal("branch")+"')");
		result = page.executeFormattedQuery("SELECT GROUP_CONCAT(a.leavetype,' : ',ROUND(a.balance,1) separator '\n') as msg FROM tmp_leavebalance a INNER JOIN u_premploymentinfo b ON b.code = a.empid INNER JOIN u_premploymentdeployment c ON c.company = b.company AND c.branch = b.branch AND c.code = b.code WHERE b.company = '"+getGlobal("company")+"' AND b.branch = '"+getGlobal("branch")+"' AND a.empid = '"+getGlobal("userid")+"' AND a.balance < 0 GROUP BY a.empid");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					msg_alert = result.childNodes.item(0).getAttribute("msg")+"\n Note : Negative value will be recalculated after your superior uploads your schedule. \n After the uploading if the balance is still negative it will be counted as unpaid leave.";
					alert(msg_alert);
				}
			}
}