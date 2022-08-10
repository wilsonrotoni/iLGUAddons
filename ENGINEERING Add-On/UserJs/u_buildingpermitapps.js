// page events
page.events.add.submitreturn('onPageSubmitReturnGPSENGINEERING');
page.events.add.load('onPageLoadGPSENGINEERING');
page.events.add.resize('onPageResizeGPSENGINEERING');
page.events.add.submit('onPageSubmitGPSENGINEERING');
page.events.add.cfl('onCFLGPSENGINEERING');
page.events.add.cflgetparams('onCFLGetParamsGPSENGINEERING');

// taskbar events
page.taskbar.events.add.load('onTaskBarLoadGPSENGINEERING');

// element events
page.elements.events.add.focus('onElementFocusGPSENGINEERING');
page.elements.events.add.keydown('onElementKeyDownGPSENGINEERING');
page.elements.events.add.validate('onElementValidateGPSENGINEERING');
page.elements.events.add.validateparams('onElementGetValidateParamsGPSENGINEERING');
page.elements.events.add.changing('onElementChangingGPSENGINEERING');
page.elements.events.add.change('onElementChangeGPSENGINEERING');
page.elements.events.add.click('onElementClickGPSENGINEERING');
page.elements.events.add.cfl('onElementCFLGPSENGINEERING');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSENGINEERING');


// table events
page.tables.events.add.reset('onTableResetRowGPSENGINEERING');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSENGINEERING');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSENGINEERING');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSENGINEERING');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSENGINEERING');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSENGINEERING');
page.tables.events.add.delete('onTableDeleteRowGPSMotorViolation');
page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSENGINEERING');
page.tables.events.add.select('onTableSelectRowGPSENGINEERING');


function onPageLoadGPSENGINEERING() {
		disableTableInput("T7","u_fee");
		disableInput("u_asstotal");
		disableInput("u_reqappfeestotal");
		disableInput("u_buildingfeestotal");
			setTableInput("T12","u_amount","2.4");
		//disableInput("u_appno");
}

function onPageResizeGPSENGINEERING(width,height) {
}

function onPageSubmitGPSENGINEERING(action) {
	// action: a = add, sc = update/save changes, d = delete/remove, cld = close document, cnd = cancel document 

 if (action=="a") {
                if (isInputEmpty("u_appdate")) return false;	
		if (isInputEmpty("u_apptype")) return false;
		if (isInputEmpty("u_ownership")) return false;
                if (isInputEmpty("u_firstname")) return false;
                if (isInputEmpty("u_middlename")) return false;
				 if (isInputEmpty("u_lastname")) return false;
				  if (isInputEmpty("u_municipality")) return false;
				   if (isInputEmpty("u_lotno")) return false;
				    if (isInputEmpty("u_blkno")) return false;
					 if (isInputEmpty("u_tctno")) return false;
					  if (isInputEmpty("u_taxdecno")) return false;
					   if (isInputEmpty("u_lstreet")) return false;
					    if (isInputEmpty("u_lbarangay")) return false;
						 if (isInputEmpty("u_architechname1")) return false;
						  if (isInputEmpty("u_architechname2")) return false;
						   if (isInputEmpty("u_archiprcno1")) return false;
						    if (isInputEmpty("u_archiprcno2")) return false;
							 if (isInputEmpty("u_scopework")) return false;
							  if (isInputEmpty("u_charactercode")) return false;
							   if (isInputEmpty("u_characterdesc")) return false;
							   
								    if (isInputEmpty("u_proposeddateofconstruction")) return false;
									 if (isInputEmpty("u_expecteddatecompletion")) return false;
								
							
		//if (isInputEmpty("u_docseries")) return false;	
		
		//if (isInputNegative("u_gross")) return false;
                //if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;	
	}
	return true;	
 
}
//function onPageSubmitReturnGPSMotorViolation(action,sucess,error) {
	//if (action=="a" && sucess) {
          //  if(window.confirm("Print Official Receipt. Continue?")) OpenReportSelect('printer');
	//}
//}

function onCFLGPSENGINEERING(Id) {
	return true;
}

function onCFLGetParamsGPSENGINEERING(Id,params) {
	return params;
}

function onTaskBarLoadGPSENGINEERING() {
}

function onElementFocusGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSENGINEERING(element,event,column,table,row) {
    
}

function onElementValidateGPSENGINEERING(element,column,table,row) {
	
	switch (table) { 
		default:
		case "T2":
			switch (column) {
				case "u_charactercode":
				case "u_characterdesc":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_charactercode") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select code,name from u_buildingcharacteroccupancy where code='"+getTableInput(table,column)+"'");	
					else var result = page.executeFormattedQuery("select code,name from u_buildingcharacteroccupancy where name like'"+getTableInput(table,column)+"%'");
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_charactercode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_characterdesc",result.childNodes.item(0).getAttribute("name"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_charactercode","");
								setTableInput(table,"u_characterdesc","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_charactercode","");
							setTableInput(table,"u_characterdesc","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_charactercode","");
						setTableInput(table,"u_characterdesc","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			case "T11":
			switch (column) {
				case "u_code":
				case "u_name":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_code") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select code,name from u_buildingaccessoryfees where code='"+getTableInput(table,column)+"'");	
					else var result = page.executeFormattedQuery("select code,name from u_buildingaccessoryfees where name like'"+getTableInput(table,column)+"%'");
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_code",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_name",result.childNodes.item(0).getAttribute("name"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_code","");
								setTableInput(table,"u_name","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_code","");
							setTableInput(table,"u_name","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_code","");
						setTableInput(table,"u_name","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}						
					break;
			}
			
			
			case "T11":
			switch (column) {
				case "u_code":
				case "u_name":
				//case "u_area":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_code") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select a.code,a.name,b.u_fee,b.u_area from u_buildingaccessoryfees a inner join u_buildingaccessoryfeesarea b on a.code=b.code where a.code='"+getTableInput(table,"u_code")+"'");	
					
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_area",result.childNodes.item(0).getAttribute("u_area"));
								setTableInput(table,"u_fee",result.childNodes.item(0).getAttribute("u_fee"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}		
					//  setBuildingFees()
				//	 computeTotalAssessment()
					break;
			}
	
	case "T11":
			switch (column) {
				case "u_code":
			//	case "u_divisionname":
				case "u_area":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_area") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select a.code,a.name,b.u_fee,b.u_area from u_buildingaccessoryfees a inner join u_buildingaccessoryfeesarea b on a.code=b.code where a.code='"+getTableInput(table,"u_code")+"' and b.u_area='"+getTableInput(table,"u_area")+"'");	
					
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_area",result.childNodes.item(0).getAttribute("u_area"));
								setTableInput(table,"u_fee",result.childNodes.item(0).getAttribute("u_fee"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}	
			//		 setBuildingFees()
					// computeTotalAssessment()
					break;
			}
			
			case "T11":
                        switch (column){
                             case "u_code":
                             case "u_area":
                             case "u_fee":
							  case "u_unit":
							case "u_amount":
							 
						 if (getTableInput("T11","u_code")!="") {
                                        		var rc = getTableRowCount("T11"), area=0, fee = 0, total = 0, baseperc=0.1,gtotal=0;
                                                total = getTableInputNumeric("T11","u_fee")*getTableInputNumeric("T11","u_unit");
                                                 setTableInputAmount("T11","u_amount",total);
												 
                                            } 
											setAccessoryFees();
					 					computeTotalAssessment();
											
					break;
					
					
			}
					
			case "T11":
					setAccessoryFees();
					break;
					
					case "T10":
					computeTotalRequirementAssessment();
					computeTotalAssessment();
					break;
					
					
					
			

		case "T8":
			switch (column) {
				case "u_feecode":
				case "u_feedesc":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_feecode") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select code,name from u_buildingpermitfees where code='"+getTableInput(table,column)+"'");	
					else var result = page.executeFormattedQuery("select code,name from u_buildingpermitfees where name like'"+getTableInput(table,column)+"%'");
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_feecode","");
								setTableInput(table,"u_feedesc","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_feecode","");
							setTableInput(table,"u_feedesc","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_feecode","");
						setTableInput(table,"u_feedesc","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}	
					 setBuildingFees()
					 computeTotalAssessment()
					break;
			}
			
			
		 case "T2":
                switch (column) {
                    case "u_charactercode":
					//case "u_characterdesc":
				//	case "u_amount":
                           if (getTableInput(table,column)!="") {
							   
                            var result = page.executeFormattedQuery("select a.code,a.name,b.u_kind from u_buildingcharacteroccupancy a inner join u_buildingcharacteroccupancykind b on a.code=b.code where a.code='"+getTableInput(table,"u_charactercode")+"'");
							
							
							
								
                                if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {    
										//setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
										//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
                                                setTableInput(table,"u_characterkind",result.childNodes.item(0).getAttribute("u_kind"));
                                              
                                        } else {
											// setTableInput(table,"u_offense","");
											  //setTableInput(table,"u_vehicletype","");
                                                setTableInput(table,"u_kind","");
                                               
                                        }
                                } else {
										// setTableInput(table,"u_vehicletype","");
							// setTableInput(table,"u_offense","");
                                        setTableInput(table,"u_kind","");
                                        page.statusbar.showError("Error retrieving  record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                }
                        } else {
							// setTableInput(table,"u_vehicletype","");
							// setTableInput(table,"u_offense","");
                                setTableInput(table,"u_kind","");
                               
                        }						
                        break;
                }	
				
				case "T7":
			switch (column) {
				case "u_divisioncode":
				case "u_divisionname":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_divisioncode") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select code,name from u_buildingfees where code='"+getTableInput(table,column)+"'");	
					else var result = page.executeFormattedQuery("select code,name from u_buildingfees where name like'"+getTableInput(table,column)+"%'");
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_divisioncode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_divisionname",result.childNodes.item(0).getAttribute("name"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_divisioncode","");
								setTableInput(table,"u_divisionname","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_divisioncode","");
							setTableInput(table,"u_divisionname","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_divisioncode","");
						setTableInput(table,"u_divisionname","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}
					 setBuildingFees()
					 computeTotalAssessment()
					break;
			}
			case "T7":
			switch (column) {
				case "u_divisioncode":
				case "u_divisionname":
				//case "u_area":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_divisioncode") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select a.code,a.name,b.u_area,b.u_fee from u_buildingfees a inner join u_buildingfeesarea b on a.code=b.code where a.code='"+getTableInput(table,"u_divisioncode")+"'");	
					
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_area",result.childNodes.item(0).getAttribute("u_area"));
								setTableInput(table,"u_fee",result.childNodes.item(0).getAttribute("u_fee"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}		
					  setBuildingFees()
					 computeTotalAssessment()
					break;
			}
	

          case "T7":
			switch (column) {
				case "u_divisioncode":
			//	case "u_divisionname":
				case "u_area":
				//case "u_offense":
				//case "u_vehicletype":
				//case "u_amount":
					if (getTableInput(table,column)!="") {
						if (column=="u_area") 
						//if (column=="u_offense")
						
					var result = page.executeFormattedQuery("select a.code,a.name,b.u_area,b.u_fee from u_buildingfees a inner join u_buildingfeesarea b on a.code=b.code where a.code='"+getTableInput(table,"u_divisioncode")+"' and b.u_area='"+getTableInput(table,"u_area")+"'");	
					
							
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_area",result.childNodes.item(0).getAttribute("u_area"));
								setTableInput(table,"u_fee",result.childNodes.item(0).getAttribute("u_fee"));
							//	setTableInput(table,"u_offense",result.childNodes.item(0).getAttribute("u_offense"));
								//setTableInput(table,"u_vehicletype",result.childNodes.item(0).getAttribute("u_vehicletype"));
							//	setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
							} else {
								setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
								//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
								page.statusbar.showError("Invalid Fee");	
								return false;
							}
						} else {
							setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
							//setTableInput(table,"u_offense","");
								//setTableInput(table,"u_vehicletype","");
								//setTableInputAmount(table,"u_amount","");
						
							page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_area","");
								setTableInput(table,"u_fee","");
						//setTableInput(table,"u_offense","");
							//setTableInput(table,"u_vehicletype","");	
					//setTableInputAmount(table,"u_amount",0);
					}	
					 setBuildingFees()
					 computeTotalAssessment()
					break;
			}

	}

switch (table) {
                case "T7":
                        switch (column){
                             case "u_divisioncode":
                             case "u_totalarea":
                             case "u_fee":
							case "u_buildingfee":
							 
						 if (getTableInput("T7","u_divisioncode")=="A") {
                                        		var rc = getTableRowCount("T7"), area=0, fee = 0, total = 0, baseperc=0.1,gtotal=0;
                                                total = getTableInputNumeric("T7","u_totalarea")*getTableInputNumeric("T7","u_fee");
                                                 setTableInputAmount("T7","u_buildingfee",total);
												 
                                            } else  if (getTableInput("T7","u_divisioncode")=="B") {
												total = getTableInputNumeric("T7","u_totalarea")*getTableInputNumeric("T7","u_fee");
                                                 setTableInputAmount("T7","u_buildingfee",total);
												 
												} else  if (getTableInput("T7","u_divisioncode")=="C") {
												total = getTableInputNumeric("T7","u_totalarea")*getTableInputNumeric("T7","u_fee");
												gtotal = total*0.1;
                                                 setTableInputAmount("T7","u_buildingfee",gtotal);
												 
												 } else  if (getTableInput("T7","u_divisioncode")=="D") {
												total = getTableInputNumeric("T7","u_totalarea")*getTableInputNumeric("T7","u_fee");
												gtotal = total*0.1;
                                                 setTableInputAmount("T7","u_buildingfee",gtotal);
												 
											}	
											 setBuildingFees()
					 					computeTotalAssessment()
											
					break;
					
					
			}
			
			case "T12":
                        switch (column){
                             case "u_meters":
                             //case "u_amount":
							case "u_totallinegrade":
							 
						 if (getTableInput("T12","u_meters")) {
                                        		var rc = getTableRowCount("T12"), area=0, fee = 0, total = 0, baseperc=0.1,gtotal=0;
                                                total = getTableInputNumeric("T12","u_meters")*2.4;
                                                 setTableInputAmount("T12","u_totallinegrade",total);
												 
                                          
												 
											}
										setLinegradeFees()
					 					computeTotalAssessment()
											
//                                     
                                break;
						}
						 var rc2 = getTableRowCount("T12"),lineamount=0;
        var rc = getTableRowCount("T8");	
	
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T12",i)==false) {
			//alert(accamount);
                       linetaxcoderc = 0;
						lineamount+= getTableInputNumeric("T12","u_totallinegrade",i); 
			    
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T8",xxx)==false) {
                                if (getTableInput("T8","u_feecode",xxx)==getPrivate("linegradefeecode") ) {
                                        setTableInputAmount("T8","u_amount",lineamount,xxx);
                                        linetaxcoderc=xxx;	
                                }
                            }
                        }

                           if(getPrivate("linegradefeecode")!=""){
                        if ( linetaxcoderc==0) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("linegradefeecode");
                               data["u_feedesc"] = getPrivate("linegradefeename");
                               // data["u_common"] = 1;
                                data["u_amount"] = formatNumericAmount(lineamount);
                                insertTableRowFromArray("T8",data);
                        }
						   }
		}
	}
        
	computeTotalBuildingAssessment();
	computeTotalAssessment();
				
			break;
			default:
									switch (column) {
								case "u_mechappno":
                                           // clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_mechanicalpermitapps a inner join u_mechanicalpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
															//("T10").remove();
                                                           //clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						
									switch (column) {
								case "u_elecappno":
                                           // clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_electricalpermitapps a inner join u_electricalpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                           // clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						switch (column) {
								case "u_plumbingappno":
                                            //clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_plumbingpermitapps a inner join u_plumbingpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                        // clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						switch (column) {
								case "u_fencingappno":
                                           //  clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_fencingpermitapps a inner join u_fencingpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                           // clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
                        
						switch (column) {
								case "u_electronicsappno":
                                          // clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_electronicspermitapps a inner join u_electronicspermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                           // clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
							switch (column) {
								case "u_archiappno":
                                         //  clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_architecturalpermitapps a inner join u_architecturalpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                          //clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						switch (column) {
								case "u_civilstrucappno":
                                         //  clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_civilstrucpermitapps a inner join u_civilstrucpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                         //  clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
                        
              switch (column) {
								case "u_sanitaryappno":
                                             //clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_sanitarypermitapps a inner join u_sanitarypermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                            //clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						
						switch (column) {
								case "u_demolitionappno":
                                         // clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_demolitionpermitapps a inner join u_demolitionpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                          // clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						
						switch (column) {
								case "u_excavationappno":
                                            // clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_excavationpermitapps a inner join u_excavationpermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                       // clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						
						switch (column) {
								case "u_occupancyappno":
                                         // clearTable("T10",true);
                                            if (getInput(column)!="") {
                                                    var result = page.executeFormattedQuery("select b.u_feecode,b.u_feedesc,b.u_amount from u_occupancypermitapps a inner join u_occupancypermitappfees b on a.docid = b.docid and a.branch = b.branch and a.company = b.company where a.docno = '"+getInput(column)+"' ");	
                                                    var data = new Array();
                                                    if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                data["u_feecode"] = result.childNodes.item(xxx).getAttribute("u_feecode");
                                                                data["u_feedesc"] = result.childNodes.item(xxx).getAttribute("u_feedesc");
                                                                data["u_amount"] = result.childNodes.item(xxx).getAttribute("u_amount");
                                                               // data["u_seqno"] = result.childNodes.item(xxx).getAttribute("u_seqno");
                                                                insertTableRowFromArray("T10",data);
                                                            }
                                                        }else{
                                                         //  clearTable("T10",true);
                                                        }
                                                    }
                                                   computeTotalRequirementAssessment();
                                                    computeTotalAssessment();
													
                                            }
                                                computeTotalRequirementAssessment();
                                                 computeTotalAssessment();
                                        break;
						}
						case "u_totalarea":
					setFixedFees();
					break;
						
						
						
}
		

	
	
	
	return true;
}

function onElementGetValidateParamsGPSENGINEERING(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementChangeGPSENGINEERING(element,column,table,row) {
switch (table) {
	
	
}
	return true;
}

function onElementClickGPSENGINEERING(element,column,table,row) {
	return true;
}

function onElementCFLGPSENGINEERING(element) {
	return true;
}

function onElementCFLGetParamsGPSENGINEERING(Id,params) {
	switch (Id) {
		
	case "df_u_charactercodeT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select CODE from u_buildingcharacteroccupancy")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_characterdescT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select NAME from u_buildingcharacteroccupancy ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_characterkindT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_kind from u_buildingcharacteroccupancykind where code='"+getTableInput("T2","u_charactercode")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Kind")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_divisioncodeT7":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_buildingfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_divisionnameT7":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name from u_buildingfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_areaT7":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_area from u_buildingfeesarea where code='"+getTableInput("T7","u_divisioncode")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Area")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	
			case "df_u_feecodeT8":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_buildingpermitfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_feedescT8":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select NAME from u_buildingpermitfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			
			case "df_u_codeT11":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select CODE from u_buildingaccessoryfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			case "df_u_nameT11":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select NAME from u_buildingaccessoryfees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_areaT11":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_area from u_buildingaccessoryfeesarea where code='"+getTableInput("T11","u_code")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Area")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	
		//	case "df_u_feeT7":
		//	params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_fee from u_buildingfeesarea where code='"+getTableInput("T7","u_divisioncode")+"'")); 
		//	params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("`Fee")); 			
			//params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			//params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			//break;
	
	
		    case "df_u_civilengr":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,b.u_name from u_buildingpermitengineers a inner join u_buildingpermitengineersclass b on a.code=b.code 		            where a.code='1'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Engineer`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			case "df_u_electricalengr":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,b.u_name from u_buildingpermitengineers a inner join u_buildingpermitengineersclass b on a.code=b.code 		            where a.code='2'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Engineer`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			case "df_u_sanitarymasterplumber":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,b.u_name from u_buildingpermitengineers a inner join u_buildingpermitengineersclass b on a.code=b.code 		            where a.code='3'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Engineer`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			case "df_u_mechanicalengr":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,b.u_name from u_buildingpermitengineers a inner join u_buildingpermitengineersclass b on a.code=b.code 		            where a.code='4'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Engineer`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			case "df_u_electronicsengr":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select a.name,b.u_name from u_buildingpermitengineers a inner join u_buildingpermitengineersclass b on a.code=b.code 		            where a.code='5'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Engineer`Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_mechappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_mechanicalpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_elecappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_electricalpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_plumbingappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_plumbingpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_fencingappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_fencingpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_electronicsappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_electronicspermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_archiappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_architecturalpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_civilstrucappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_civilstrucpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_sanitaryappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_sanitarypermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_demolitionappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_demolitionpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_excavationappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_excavationpermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
			
			case "df_u_occupancyappno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_appno,concat(u_firstname,' ',u_middlename,' ',u_lastname) as Ownername from u_occupancypermitapps where docstatus='Approved'")); 
					params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Docno`Application No`Ownername`")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		
	}
	return params;
}

function onTableResetRowGPSENGINEERING(table) {
}

function onTableBeforeInsertRowGPSENGINEERING(table) {
	
	switch (table) {
		case "T11": 
			if (isTableInputEmpty(table,"u_name")) return false;
			//if (isTableInputEmpty(table,"u_feedesc")) return false;
			if (isTableInputNegative(table,"u_amount")) return false;
			break;
	}

	
	
	return true;
}

function onTableAfterInsertRowGPSENGINEERING(table,row) {
	switch (table) {
		
		
		case "T8": computeTotalBuildingAssessment();break;
		case "T11": setAccessoryFees();break;
		case "T10": computeTotalRequirementAssessment(); computeTotalAssessment();break;
		case "T7": setBuildingFees();break;
		case "T12": setLinegradeFees();break;
		
		
					 
	}
	
			
}

function onTableBeforeUpdateRowGPSENGINEERING(table,row) {
	
	
	
	return true;
}

function onTableAfterUpdateRowGPSENGINEERING(table,row) {
	
	switch (table) {
		
		
		case "T8": computeTotalBuildingAssessment();break;
		case "T11": setAccessoryFees();break;
		case "T10": computeTotalRequirementAssessment(); computeTotalAssessment();break;
		case "T7": setBuildingFees();break;
		case "T12": setLinegradeFees();break;
		//case "T10": computeTotalAssessment();break;
		
	}
}

function onTableBeforeDeleteRowGPSENGINEERING(table,row) {
	
	return true;
}

function onTableDeleteRowGPSENGINEERING(table,row) {
	
	switch (table) {
		
		
		case "T8": computeTotalBuildingAssessment();break;
		case "T11": setAccessoryFees();break;
		case "T10": computeTotalRequirementAssessment(); computeTotalAssessment();break;
		case "T7": setBuildingFees();break;
		case "T12": setLinegradeFees();break;
	//	case "T10": computeTotalAssessment();break;
	
	}
}

function onTableBeforeSelectRowGPSENGINEERING(table,row) {
	
	return true;
}

function onTableSelectRowGPSENGINEERING(table,row) {
	var params = new Array();
	switch (table) {
		case "T4":
		case "T5":
		case "T1":
		
			if (elementFocused.substring(0,100)=="df_u_reqfile") {
				focusTableInput(table,"u_reqfile",row);
			} else if (elementFocused.substring(0,100)=="df_u_reqplanfile") {
				focusTableInput(table,"u_reqplanfile",row);
			}
			params["focus"]=false;
			break;
	}
	return params;
	
	return true;

}

function u_forAssessmentGPSENGINEERING() {
	if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownership",null,null,"tab1",0)) return false;
	 if (isInputEmpty("u_appdate")) return false;	
		if (isInputEmpty("u_apptype")) return false;
                if (isInputEmpty("u_firstname")) return false;
                if (isInputEmpty("u_middlename")) return false;
				 if (isInputEmpty("u_lastname")) return false;
				  if (isInputEmpty("u_municipality")) return false;
				   if (isInputEmpty("u_lotno")) return false;
				    if (isInputEmpty("u_blkno")) return false;
					 if (isInputEmpty("u_tctno")) return false;
					  if (isInputEmpty("u_taxdecno")) return false;
					   if (isInputEmpty("u_lstreet")) return false;
					    if (isInputEmpty("u_lbarangay")) return false;
						 if (isInputEmpty("u_architechname1")) return false;
						  if (isInputEmpty("u_architechname2")) return false;
						   if (isInputEmpty("u_archiprcno1")) return false;
						    if (isInputEmpty("u_archiprcno2")) return false;
							 
	
	setInput("docstatus","Assessing");
	formSubmit('sc');
}

function u_forApprovalGPSENGINEERING() {
	if (isInputEmpty("u_scopework",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_charactercode",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_characterdesc",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_category",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_remarks",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_amount",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_amount",null,null,"tab1",0)) return false;
	
	//if (isInputEmpty("u_proposeddateofconstruction",null,null,"tab1",0)) return false;
	//if (isInputEmpty("u_expecteddatecompletion",null,null,"tab1",0)) return false;
	
	
	
	if (getTableRowCount("T3")==0) {
		page.statusbar.showError("Category details must be entered.");
		selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T2")==0) {
		page.statusbar.showError("Category details must be entered.");
		selectTab("tab1",1);
		return false;
	}
	
	setInput("docstatus","Assessed");
	formSubmit('sc');
}

function u_approveGPSENGINEERING() {
	 if (isInputEmpty("u_asstotal")) return false;
	
	//if (isInputEmpty("u_middlename",null,null,"tab1",1)) return false;
	if (getTableRowCount("T3")==0) {
		page.statusbar.showError("Category details must be entered.");
		selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T2")==0) {
		page.statusbar.showError("Character Code must be entered.");
		selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T6")==0) {
		page.statusbar.showError("Occupancy Classified is Required.");
		//selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T7")==0) {
		page.statusbar.showError("Division Code is Required");
		//selectTab("tab1",1);
		return false;
	}
	if (getTableRowCount("T8")==0) {
		page.statusbar.showError("Assessment is Required.");
		//selectTab("tab1",1);
		return false;
	}
	setInput("docstatus","Approved");
	formSubmit('sc');
}


function u_disapproveGPSENGINEERING() {
	setInput("docstatus","Disapproved");
	formSubmit('sc');
}

function u_reassessGPSENGINEERING() {
	//if (isInputEmpty("u_approverremarks",null,null,"tab1",3)) return false;
	setInput("docstatus","Encoding");
	formSubmit('sc');
}

function computeTotalAssessment() {
	var rc = getTableRowCount("T10"),total=0,discount=0,total2=0,total3=0,discount2=0, penalty2=0;
	//var rc = getTableRowCount("T8")
	//var rc = getTableRowCount("T7")
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T10",i)==false) {
			
					
			
			total3+getTableInputNumeric("T10","u_amount",i);
		
			
                      
		}
	}
      
	setInputAmount("u_asstotal",total3 + getInputNumeric("u_reqappfeestotal")+ getInputNumeric("u_buildingfeestotal"));
	
    //setInputAmount("u_total",false);
}

function computeTotalRequirementAssessment() {
	var rc = getTableRowCount("T10"),total=0;
	for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T10",i)==false) {
                total+= getTableInputNumeric("T10","u_amount",i);
            }
	}
	setInputAmount("u_reqappfeestotal",total );
	computeTotalAssessment();
	
}

function computeTotalBuildingAssessment() {
	
	var rc = getTableRowCount("T8"),total=0;
	for (i = 1; i <= rc; i++) {
            if (isTableRowDeleted("T8",i)==false) {
                total+= getTableInputNumeric("T8","u_amount",i);
            }
	}
	//alert(total)
	setInputAmount("u_buildingfeestotal",total );
	computeTotalAssessment();
}


function setAccessoryFees() {
	
	 var rc2 = getTableRowCount("T11"),accamount=0;
        var rc = getTableRowCount("T8");	
	
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T11",i)==false) {
			//alert(accamount);
                        acctaxcoderc = 0;
						 accamount+= getTableInputNumeric("T11","u_amount",i); 
			    
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T8",xxx)==false) {
                                if (getTableInput("T8","u_feecode",xxx)==getPrivate("accessorycode") ) {
                                        setTableInputAmount("T8","u_amount",accamount,xxx);
                                        acctaxcoderc=xxx;	
                                }
                            }
                        }

                           if(getPrivate("accessorycode")!=""){
                        if ( acctaxcoderc==0) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("accessorycode");
                               data["u_feedesc"] = getPrivate("accessoryname");
                               // data["u_common"] = 1;
                                data["u_amount"] = formatNumericAmount(accamount);
                                insertTableRowFromArray("T8",data);
                        }
						   }
		}
	}
        
	computeTotalBuildingAssessment();
	computeTotalAssessment();
	
}

function setLinegradeFees() {
	
	 var rc2 = getTableRowCount("T12"),lineamount=0;
        var rc = getTableRowCount("T8");	
	
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T12",i)==false) {
			//alert(accamount);
                       linetaxcoderc = 0;
						lineamount+= getTableInputNumeric("T12","u_totallinegrade",i); 
			    
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T8",xxx)==false) {
                                if (getTableInput("T8","u_feecode",xxx)==getPrivate("linegradefeecode") ) {
                                        setTableInputAmount("T8","u_amount",lineamount,xxx);
                                        linetaxcoderc=xxx;	
                                }
                            }
                        }

                           if(getPrivate("linegradefeecode")!=""){
                        if ( linetaxcoderc==0) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("linegradefeecode");
                               data["u_feedesc"] = getPrivate("linegradefeename");
                               // data["u_common"] = 1;
                                data["u_amount"] = formatNumericAmount(lineamount);
                                insertTableRowFromArray("T8",data);
                        }
						   }
		}
	}
        
	computeTotalBuildingAssessment();
	computeTotalAssessment();
	
}


function setBuildingFees() {
	
	 var rc2 = getTableRowCount("T7"),buildamount=0;
        var rc = getTableRowCount("T8");	
	
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T7",i)==false) {
			//alert(buildamount);
                        buildtaxcoderc = 0;
						 buildamount+= getTableInputNumeric("T7","u_buildingfee",i); 
			    
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T8",xxx)==false) {
                                if (getTableInput("T8","u_feecode",xxx)==getPrivate("buildingpermitfeecode") ) {
                                        setTableInputAmount("T8","u_amount",buildamount,xxx);
                                       buildtaxcoderc=xxx;	
                                }
                            }
                        }

                           if(getPrivate("accessorycode")!=""){
                        if ( buildtaxcoderc==0) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("buildingpermitfeecode");
                              	 data["u_feedesc"] = getPrivate("buildingpermitfeename");
                                data["u_amount"] = formatNumericAmount(accamount);
                                insertTableRowFromArray("T8",data);
                        }
						   }
		}
	}
        
	computeTotalBuildingAssessment();
	computeTotalAssessment();
	
}

function setFixedFees() {
	
	 var rc2 = getInputNumeric("u_totalarea");
	 var rc = getTableRowCount("T8");
	
	 
	
						
	for (i = 1; i <= rc2; i++) {
		if (isTableRowDeleted("T8",i)==false) {
			//alert(buildamount);
                    	 filingtaxcoderc = 0;
						 processtaxcoderc = 0;
						 inspectaxcoderc = 0;
						area= getInputNumeric("u_totalarea",i);
						 var filing1=0;
						 var processing1=0;
						 var inspection1=0;
						
			if (area<=500) {
               filing1= 500;
			   processing1= 300;
			   inspection1= 500;
			   
			}else if (area<=1000){
				filing1= 1000;
				 processing1= 500;
			   inspection1= 1000;
			
			}else if (area<=2000){
				filing1= 2000;
				 processing1= 1000;
			   inspection1= 2000;
			}else if (area<=3000){
				filing1= 3000;
				 processing1= 2000;
			   inspection1= 3000;
			}else if (area>3000){
				filing1=3000;
				 processing1= 2000;
			   inspection1= 3000;
			}
		
			    
                        for (xxx = 1; xxx <=rc; xxx++) {
                            if (isTableRowDeleted("T8",xxx)==false) {
                                if (getTableInput("T8","u_feecode",xxx)==getPrivate("filingfeecode")) {
                                        setTableInputAmount("T8","u_amount",filing1,xxx);
                                       filingtaxcoderc=xxx;	
                                }
								if (getTableInput("T8","u_feecode",xxx)==getPrivate("processingfeecode")) {
                                        setTableInputAmount("T8","u_amount",processing1,xxx);
                                       processtaxcoderc=xxx;	
                                }
								if (getTableInput("T8","u_feecode",xxx)==getPrivate("inspectionfeecode")) {
                                        setTableInputAmount("T8","u_amount",inspection1,xxx);
                                      inspectiontaxcoderc=xxx;	
                                }
								 
                            }
                        }

                           if(getPrivate("filingfeecode")!=""){
                     	   if ( filingtaxcoderc==0) {
							//  if (area>=500 && area==300) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("filingfeecode");
                              	data["u_feedesc"] = getPrivate("filingfeename");
                                data["u_amount"] = formatNumericAmount(filing1);
                                insertTableRowFromArray("T8",data);
                     //   }
						   }
						   }
						   
						   if(getPrivate("processingfeecode")!=""){
                     	   if ( processtaxcoderc==0) {
							//  if (area>=500 && area==300) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("processingfeecode");
                              	data["u_feedesc"] = getPrivate("processingfeename");
                                data["u_amount"] = formatNumericAmount(processing1);
                                insertTableRowFromArray("T8",data);
                     //   }
						   }
						   }
						      if(getPrivate("inspectionfeecode")!=""){
                     	   if ( inspectiontaxcoderc==0) {
							//  if (area>=500 && area==300) {
                                var data = new Array();
                                data["u_feecode"] = getPrivate("inspectionfeecode");
                              	data["u_feedesc"] = getPrivate("inspectionfeename");
                                data["u_amount"] = formatNumericAmount(inspection1);
                                insertTableRowFromArray("T8",data);
                     //   }
						   }
						   }
		}
	}
        
        
		
	
		
	computeTotalBuildingAssessment();
	computeTotalAssessment();
	
}



function OpenLnkBtnu_mechanicalpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_mechanicalpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_electricalpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_electricalpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_zoningpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_zoningpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	
}

function OpenLnkBtnu_plumbingpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_plumbingpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	
}

function OpenLnkBtnu_fencingpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_fencingpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_electronicspermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_electronicspermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_architecturalpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_architecturalpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_civilstrucpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_civilstrucpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_sanitarypermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_sanitarypermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_demolitionpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_demolitionpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_excavationpermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_excavationpermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_occupancypermitapps(targetObjectId) {
	OpenLnkBtn(1224,1200,'./udo.php?objectcode=u_occupancypermitapps' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}