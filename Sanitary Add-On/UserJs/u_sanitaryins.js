// page events
page.events.add.load('onPageLoadGPSSanitary');
//page.events.add.resize('onPageResizeGPSSanitary');
//page.events.add.submit('onPageSubmitGPSSanitary');
//page.events.add.cfl('onCFLGPSSanitary');
//page.events.add.cflgetparams('onCFLGetParamsGPSSanitary');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSSanitary');

// element events
//page.elements.events.add.focus('onElementFocusGPSSanitary');
//page.elements.events.add.keydown('onElementKeyDownGPSSanitary');
page.elements.events.add.validate('onElementValidateGPSSanitary');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSSanitary');
page.elements.events.add.changing('onElementChangingGPSSanitary');
//page.elements.events.add.change('onElementChangeGPSSanitary');
page.elements.events.add.click('onElementClickGPSSanitary');
//page.elements.events.add.cfl('onElementCFLGPSSanitary');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSSanitary');

// table events
//page.tables.events.add.reset('onTableResetRowGPSSanitary');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSSanitary');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSSanitary');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSSanitary');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSSanitary');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSSanitary');
//page.tables.events.add.delete('onTableDeleteRowGPSSanitary');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSSanitary');
//page.tables.events.add.select('onTableSelectRowGPSSanitary');

function onPageLoadGPSSanitary() {
	setCaption("u_sf1",getInput("u_sf1"));
	setCaption("u_sf2",getInput("u_sf2"));
	setCaption("u_sf3",getInput("u_sf3"));

}

function onPageResizeGPSSanitary(width,height) {
}

function onPageSubmitGPSSanitary(action) {
	return true;
}

function onCFLGPSSanitary(Id) {
	return true;
}

function onCFLGetParamsGPSSanitary(Id,params) {
	return params;
}

function onTaskBarLoadGPSSanitary() {
}

function onElementFocusGPSSanitary(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSSanitary(element,event,column,table,row) {
}

function onElementValidateGPSSanitary(element,column,table,row) {
	var result;
	switch (table) {
		default:
			switch(column) {
				case "u_appno":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select docno, u_docdate, u_apptype, u_bpno, u_businessname, u_owner, u_address, u_category, u_manager from u_sanitaryapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_appno",result.childNodes.item(0).getAttribute("docno"));
								setInput("u_appdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
								setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
								setInput("u_owner",result.childNodes.item(0).getAttribute("u_owner"));
								setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
								setInput("u_category",result.childNodes.item(0).getAttribute("u_category"));
								onElementChangingGPSSanitary(null,"u_category","",0);
								setInput("u_manager",result.childNodes.item(0).getAttribute("u_manager"));
							} else {
								setInput("u_appdate","");
								setInput("u_businessname","");
								setInput("u_owner","");
								setInput("u_address","");
								setInput("u_category","",true);
								setInput("u_manager","");
								page.statusbar.showError("Invalid Application No.");	
								return false;
							}
						} else {
							setInput("u_appdate","");
							setInput("u_businessname","");
							setInput("u_owner","");
							setInput("u_address","");
							setInput("u_category","",true);
							setInput("u_manager","");
							page.statusbar.showError("Error retrieving application record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_appdate","");
						setInput("u_businessname","");
						setInput("u_owner","");
						setInput("u_address","");
						setInput("u_category","",true);
						setInput("u_manager","");
					}
					break;
				case "u_inspectdate":
					setInput("u_totaldemerits",getInputNumeric("u_oth1")+getInputNumeric("u_oth2")+getInputNumeric("u_oth3")+getInputNumeric("u_cop")+getInputNumeric("u_mop")+getInputNumeric("u_tp")+getInputNumeric("u_hf")+getInputNumeric("u_ws")+getInputNumeric("u_lwm")+getInputNumeric("u_swm")+getInputNumeric("u_wof")+getInputNumeric("u_pof")+getInputNumeric("u_vc")+getInputNumeric("u_cnt")+getInputNumeric("u_pc")+getInputNumeric("u_ham")+getInputNumeric("u_coau")+getInputNumeric("u_scoau")+getInputNumeric("u_dc")+getInputNumeric("u_mic"));
					//setInput("u_ratingperc",100-(getInputNumeric("u_oth1")*5)-(getInputNumeric("u_oth2")*5)-(getInputNumeric("u_oth3")*5)-(getInputNumeric("u_cop")*5)-(getInputNumeric("u_mop")*5)-(getInputNumeric("u_tp")*5)-(getInputNumeric("u_hf")*5)-(getInputNumeric("u_ws")*5)-(getInputNumeric("u_lwm")*5)-(getInputNumeric("u_swm")*5)-(getInputNumeric("u_wof")*5)-(getInputNumeric("u_pof")*5)-(getInputNumeric("u_vc")*5)-(getInputNumeric("u_cnt")*5)-(getInputNumeric("u_pc")*5)-(getInputNumeric("u_ham")*5)-(getInputNumeric("u_coau")*5)-(getInputNumeric("u_scoau")*5)-(getInputNumeric("u_dc")*5)-(getInputNumeric("u_mic")*5));
					setInput("u_ratingperc",100-(getInputNumeric("u_totaldemerits")*5));
					if (getInputNumeric("u_ratingperc")>=90) {
						setInput("u_inspectbystatus","Excellent");
					} else if (getInputNumeric("u_ratingperc")>=70) {
						setInput("u_inspectbystatus","Very Satisfactory");
					} else if (getInputNumeric("u_ratingperc")>=50) {
						setInput("u_inspectbystatus","Satisfactory");
					} else {
						setInput("u_inspectbystatus","Failed");
					}				
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSSanitary(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSSanitary(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_category":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_sf1, u_sf2, u_sf3 from u_sanitarycategories where code = '"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_sf1",result.childNodes.item(0).getAttribute("u_sf1"));
								setInput("u_sf2",result.childNodes.item(0).getAttribute("u_sf2"));
								setInput("u_sf3",result.childNodes.item(0).getAttribute("u_sf3"));
								setCaption("u_sf1",result.childNodes.item(0).getAttribute("u_sf1"));
								setCaption("u_sf2",result.childNodes.item(0).getAttribute("u_sf2"));
								setCaption("u_sf3",result.childNodes.item(0).getAttribute("u_sf3"));
							} else {
								setInput("u_sf1","");
								setInput("u_sf2","");
								setInput("u_sf3","");
								page.statusbar.showError("Invalid sanitary category.");	
								return false;
							}
						} else {
							setInput("u_sf1","");
							setInput("u_sf2","");
							setInput("u_sf3","");
							page.statusbar.showError("Error retrieving sanitary category record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_sf1","");
						setInput("u_sf2","");
						setInput("u_sf3","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementChangeGPSSanitary(element,column,table,row) {
	return true;
}

function onElementClickGPSSanitary(element,column,table,row) {
	switch (column) {
		case "u_oth1":
		case "u_oth2":
		case "u_oth3":
		case "u_cop":			
		case "u_mop":			
		case "u_tp":			
		case "u_hf":			
		case "u_ws":			
		case "u_lwm":			
		case "u_swm":			
		case "u_wof":			
		case "u_pof":			
		case "u_vc":			
		case "u_cnt":			
		case "u_pc":			
		case "u_ham":			
		case "u_coau":			
		case "u_scoau":			
		case "u_dc":			
		case "u_mic":
			setInput("u_totaldemerits",getInputNumeric("u_oth1")+getInputNumeric("u_oth2")+getInputNumeric("u_oth3")+getInputNumeric("u_cop")+getInputNumeric("u_mop")+getInputNumeric("u_tp")+getInputNumeric("u_hf")+getInputNumeric("u_ws")+getInputNumeric("u_lwm")+getInputNumeric("u_swm")+getInputNumeric("u_wof")+getInputNumeric("u_pof")+getInputNumeric("u_vc")+getInputNumeric("u_cnt")+getInputNumeric("u_pc")+getInputNumeric("u_ham")+getInputNumeric("u_coau")+getInputNumeric("u_scoau")+getInputNumeric("u_dc")+getInputNumeric("u_mic"));
			//setInput("u_ratingperc",100-(getInputNumeric("u_oth1")*5)-(getInputNumeric("u_oth2")*5)-(getInputNumeric("u_oth3")*5)-(getInputNumeric("u_cop")*5)-(getInputNumeric("u_mop")*5)-(getInputNumeric("u_tp")*5)-(getInputNumeric("u_hf")*5)-(getInputNumeric("u_ws")*5)-(getInputNumeric("u_lwm")*5)-(getInputNumeric("u_swm")*5)-(getInputNumeric("u_wof")*5)-(getInputNumeric("u_pof")*5)-(getInputNumeric("u_vc")*5)-(getInputNumeric("u_cnt")*5)-(getInputNumeric("u_pc")*5)-(getInputNumeric("u_ham")*5)-(getInputNumeric("u_coau")*5)-(getInputNumeric("u_scoau")*5)-(getInputNumeric("u_dc")*5)-(getInputNumeric("u_mic")*5));
			setInput("u_ratingperc",100-(getInputNumeric("u_totaldemerits")*5));
			if (getInputNumeric("u_ratingperc")>=90) {
				setInput("u_inspectbystatus","Excellent");
			} else if (getInputNumeric("u_ratingperc")>=70) {
				setInput("u_inspectbystatus","Very Satisfactory");
			} else if (getInputNumeric("u_ratingperc")>=50) {
				setInput("u_inspectbystatus","Satisfactory");
			} else {
				setInput("u_inspectbystatus","Failed");
			}
			break;
	}
	return true;
}

function onElementCFLGPSSanitary(element) {
	return true;
}

function onElementCFLGetParamsGPSSanitary(Id,params) {
	switch (Id) {
		case "df_u_appno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_apptype, u_bpno from u_sanitaryapps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docstatus='O'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Application No.`Application For`Business Permit")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`20`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSSanitary(table) {
}

function onTableBeforeInsertRowGPSSanitary(table) {
	return true;
}

function onTableAfterInsertRowGPSSanitary(table,row) {
}

function onTableBeforeUpdateRowGPSSanitary(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSSanitary(table,row) {
}

function onTableBeforeDeleteRowGPSSanitary(table,row) {
	return true;
}

function onTableDeleteRowGPSSanitary(table,row) {
}

function onTableBeforeSelectRowGPSSanitary(table,row) {
	return true;
}

function onTableSelectRowGPSSanitary(table,row) {
}

