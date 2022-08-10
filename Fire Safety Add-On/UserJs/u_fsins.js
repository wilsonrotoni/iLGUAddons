// page events
//page.events.add.load('onPageLoadGPSFireSafety');
//page.events.add.resize('onPageResizeGPSFireSafety');
//page.events.add.submit('onPageSubmitGPSFireSafety');
//page.events.add.cfl('onCFLGPSFireSafety');
//page.events.add.cflgetparams('onCFLGetParamsGPSFireSafety');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFireSafety');

// element events
//page.elements.events.add.focus('onElementFocusGPSFireSafety');
//page.elements.events.add.keydown('onElementKeyDownGPSFireSafety');
page.elements.events.add.validate('onElementValidateGPSFireSafety');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFireSafety');
//page.elements.events.add.changing('onElementChangingGPSFireSafety');
//page.elements.events.add.change('onElementChangeGPSFireSafety');
//page.elements.events.add.click('onElementClickGPSFireSafety');
//page.elements.events.add.cfl('onElementCFLGPSFireSafety');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFireSafety');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFireSafety');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFireSafety');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFireSafety');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFireSafety');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFireSafety');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFireSafety');
//page.tables.events.add.delete('onTableDeleteRowGPSFireSafety');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFireSafety');
//page.tables.events.add.select('onTableSelectRowGPSFireSafety');

function onPageLoadGPSFireSafety() {
}

function onPageResizeGPSFireSafety(width,height) {
}

function onPageSubmitGPSFireSafety(action) {
	return true;
}

function onCFLGPSFireSafety(Id) {
	return true;
}

function onCFLGetParamsGPSFireSafety(Id,params) {
	return params;
}

function onTaskBarLoadGPSFireSafety() {
}

function onElementFocusGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSFireSafety(element,event,column,table,row) {
}

function onElementValidateGPSFireSafety(element,column,table,row) {
	var result;
	switch (table) {
		default:
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("SELECT u_appnature,u_docdate,u_ownername, u_authrep,u_owneraddress, u_projectname, u_noofflrs, u_totflrareabldg, u_bldgappno, CONCAT(u_baddressno,u_bbldgno,u_bunitno,u_bfloorno, u_bbldgname,u_bblock, u_blotno,u_bphaseno, u_bstreet, u_bvillage,u_bbrgy,u_bcity,u_bprovince ) AS u_address FROM ( select u_appnature,u_docdate, CONCAT(U_LASTNAME , ',' , U_FIRSTNAME , U_MIDDLENAME) AS U_OWNERNAME, u_authrep,u_owneraddress, u_projectname, u_noofflrs, u_totflrareabldg, u_bldgappno,  IF(IFNULL(a.u_baddressno,'')='','',CONCAT(a.u_baddressno,', ')) AS u_baddressno, IF(IFNULL(a.u_bbldgno,'') = '','',CONCAT(a.u_bbldgno,' ')) AS u_bbldgno,IF(IFNULL(a.u_bunitno,'') = '','',CONCAT(a.u_bunitno,', ')) AS u_bunitno, IF(IFNULL(a.u_bfloorno,'') = '','',CONCAT(a.u_bfloorno,', ')) AS u_bfloorno,IF(IFNULL(a.u_bbldgname,'') = '','',CONCAT(a.u_bbldgname,', ')) AS u_bbldgname, IF(IFNULL(a.u_bblock,'')='','',CONCAT('BLK ',a.u_bblock,' ')) AS u_bblock, IF(IFNULL(a.u_blotno,'')='','',CONCAT('LOT ',a.u_blotno,' ')) AS u_blotno, IF(IFNULL(a.u_bphaseno,'')='','',CONCAT('PHASE ',a.u_bphaseno,', ')) AS u_bphaseno, IF(IFNULL(a.u_bstreet,'')='','',CONCAT(a.u_bstreet,', ')) AS u_bstreet, IF(IFNULL(a.u_bvillage,'')='','',CONCAT(a.u_bvillage,', ')) AS u_bvillage, IF(IFNULL(a.u_bbrgy,'')='','',CONCAT(a.u_bbrgy,', ')) AS u_bbrgy, IF(IFNULL(a.u_bcity,'')='','',CONCAT(a.u_bcity,', ')) AS u_bcity, IF(IFNULL(a.u_bprovince,'')='','',CONCAT(a.u_bprovince,' ')) AS u_bprovince from u_fsapps a where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"')  AS X ");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
								setInput("u_address",result.childNodes.item(0).getAttribute("u_owneraddress"));
								setInput("u_gi_owneroccupantno",result.childNodes.item(0).getAttribute("u_contactno"));
								setInput("u_gi_owneroccupantname",result.childNodes.item(0).getAttribute("u_ownername"));
								setInput("u_gi_representativename",result.childNodes.item(0).getAttribute("u_authrep"));
								setInput("u_gi_bldgname",result.childNodes.item(0).getAttribute("u_projectname"));
								setInput("u_gi_businessname",result.childNodes.item(0).getAttribute("u_projectname"));
								setInput("u_gi_noofstorey",result.childNodes.item(0).getAttribute("u_noofflrs"));
								setInput("u_gi_totalflrarea",result.childNodes.item(0).getAttribute("u_totflrareabldg"));
								setInput("u_gi_bldgpermitno",result.childNodes.item(0).getAttribute("u_bldgappno"));
								setInput("u_gi_address",result.childNodes.item(0).getAttribute("u_address"));
                                                                setInput("u_noic_buc",0);
                                                                setInput("u_noic_afop",0);
                                                                setInput("u_noic_afbp",0);
                                                                setInput("u_noic_os",0);
                                                                setInput("u_noic_pioo",0);
                                                                setInput("u_noic_vioctntcv",0);
                                                                setInput("u_noic_viocr",0);
                                                                
								switch (result.childNodes.item(0).getAttribute("u_appnature")) {
									case "Occupancy Permit":
										setInput("u_noic_afop",1);
										
										break;
									case "New Business Permit":
									case "Renewal of Business Permit":
										setInput("u_noic_afbp",1);
										
										break;
									case "FSEC":
										setInput("u_noic_buc",1);
										
										break;
									case "FSIC":
										setInput("u_noic_afop",1);
										break;
                                                                        case "Others":
										setInput("u_noic_os",1);
										break;
								}
							} else {
								setInput("u_appdate","");
								page.statusbar.showError("Invalid Application No.");	
								return false;
							}
						} else {
							setInput("u_appdate","");
							page.statusbar.showError("Error retrieving application record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_appdate","");
					}
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSFireSafety(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementChangeGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementClickGPSFireSafety(element,column,table,row) {
	return true;
}

function onElementCFLGPSFireSafety(element) {
	return true;
}

function onElementCFLGetParamsGPSFireSafety(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_appnature, u_appno from u_fsapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='Paid'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Reference No.`Application For`Application No")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`20`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSFireSafety(table) {
}

function onTableBeforeInsertRowGPSFireSafety(table) {
	return true;
}

function onTableAfterInsertRowGPSFireSafety(table,row) {
}

function onTableBeforeUpdateRowGPSFireSafety(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFireSafety(table,row) {
}

function onTableBeforeDeleteRowGPSFireSafety(table,row) {
	return true;
}

function onTableDeleteRowGPSFireSafety(table,row) {
}

function onTableBeforeSelectRowGPSFireSafety(table,row) {
	return true;
}

function onTableSelectRowGPSFireSafety(table,row) {
}

function recommendforApprovalGPSFireSafety() {
	setInput("u_recommendbystatus","For Approval");
	formSubmit();
}

function recommenddisapprovedGPSFireSafety() {
	setInput("u_recommendbystatus","Disapproved");
	formSubmit();
}

function approvedGPSFireSafety() {
	setInput("u_dispositionbystatus","Approved");
	formSubmit();
}

function disapprovedGPSFireSafety() {
	setInput("u_dispositionbystatus","Disapproved");
	formSubmit();
}
function passedGPSFireSafety() {
	setInput("u_inspectbystatus","Passed");
	formSubmit();
}
function failedGPSFireSafety() {
	setInput("u_inspectbystatus","Failed");
	formSubmit();
}
