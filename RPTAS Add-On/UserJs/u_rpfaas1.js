// page events
page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSRPTAS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSRPTAS');

// element events
//page.elements.events.add.focus('onElementFocusGPSRPTAS');
//page.elements.events.add.keydown('onElementKeyDownGPSRPTAS');
page.elements.events.add.validate('onElementValidateGPSRPTAS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSRPTAS');
//page.elements.events.add.changing('onElementChangingGPSRPTAS');
page.elements.events.add.change('onElementChangeGPSRPTAS');
page.elements.events.add.click('onElementClickGPSRPTAS');
//page.elements.events.add.cfl('onElementCFLGPSRPTAS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSRPTAS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSRPTAS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSRPTAS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSRPTAS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
page.tables.events.add.select('onTableSelectRowGPSRPTAS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSRPTAS');
var isupdate = false;
var arpno = "";
var arpno1 = "";
var tabberOptions = {
  'manualStartup':true,
   'onClick': function(argsObj) { 
	    if (argsObj.tabber.id == 'tab1') {
			switch (getTabIDByName(argsObj.tabber.tabs[argsObj.index].headingText)) {
				case "Property Assessment": 
				    if (getVar("formSubmitAction")=="a") {
						setStatusMsg("Please save this FAAS prior assessing.",4000,1);
						return false;
					}	
					break;
			}
	    } 
  	return true;
	}
};

function onPageLoadGPSRPTAS() {
    if (getPrivate("getprevarpno")!="" && getPrivate("getprevarpno") != "pf_getprevarpno")	getPrevFaas("arpno",getPrivate("getprevarpno"));
    setDocNo(true,"u_tdseries","u_tdno","u_date");
    setDocNo(true,"u_tdseries","u_varpno","u_date");
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
	if (action=="a" || action=="sc") {
                if (isInputEmpty("u_ownertin",null,null,"tab1",0)) return false;
            	if (isInputEmpty("u_claimant",null,null,"tab1",0)) return false;
            	if (isInputEmpty("u_trxcode",null,null,"tab1",0)) return false;
				if (isInputEmpty("u_oldbarangay",null,null,"tab1",0)) return false;
            	//if (isInputEmpty("u_pin",null,null,"tab1",0)) return false;
	}
	return true;
}

function onCFLGPSRPTAS(Id) {
	return true;
}

function onCFLGetParamsGPSRPTAS(Id,params) {
	return params;
}

function onTaskBarLoadGPSRPTAS() {
}

function onElementFocusGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSRPTAS(element,event,column,table,row) {
}

function onElementValidateGPSRPTAS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch(column) {
				case "u_prevtdno":
				case "u_prevarpno":
					if (getTableInput(table,column)!="") {
						if (column=="u_prevarpno") var result = page.executeFormattedQuery("select * from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+getTableInput(table,column)+"' and u_expyear=0");	
						else var result = page.executeFormattedQuery("select * from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getTableInput(table,column)+"' and u_expyear=0");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_prevpin",result.childNodes.item(0).getAttribute("u_pin"));
								setTableInput(table,"u_prevarpno",result.childNodes.item(0).getAttribute("docno"));
								setTableInput(table,"u_prevarpno2",result.childNodes.item(0).getAttribute("u_varpno"));
								setTableInput(table,"u_prevtdno",result.childNodes.item(0).getAttribute("u_tdno"));
								setTableInput(table,"u_prevowner",result.childNodes.item(0).getAttribute("u_ownername"));
								setTableInput(table,"u_preveffdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_effdate")));
								setTableInput(table,"u_prevrecordedby",result.childNodes.item(0).getAttribute("u_recordedby"));
								setTableInput(table,"u_prevrecordeddate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_recordeddate")));
								setTableInput(table,"u_prevvalue",formatNumericAmount(result.childNodes.item(0).getAttribute("u_assvalue")));
							} else {
								if (getInput("docstatus")=="Encoding") {
								setTableInput(table,"u_prevpin","");
								setTableInput(table,"u_prevarpno","");
								setTableInput(table,"u_prevarpno2","");
								setTableInput(table,"u_prevtdno","");
								setTableInput(table,"u_prevowner","");
								setTableInput(table,"u_preveffdate","");
								setTableInput(table,"u_prevrecordedby","");
								setTableInput(table,"u_prevrecordeddate","");
								setTableInput(table,"u_prevvalue",formatNumericAmount(0));
								page.statusbar.showError("Invalid Arp/TD No.");	
								return false;
								}
							}
						} else {
							setTableInput(table,"u_prevpin","");
							setTableInput(table,"u_prevarpno","");
							setTableInput(table,"u_prevarpno2","");
							setTableInput(table,"u_prevtdno","");
							setTableInput(table,"u_prevowner","");
							setTableInput(table,"u_preveffdate","");
							setTableInput(table,"u_prevrecordedby","");
							setTableInput(table,"u_prevrecordeddate","");
							setTableInput(table,"u_prevvalue",formatNumericAmount(0));
							page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_prevpin","");
						setTableInput(table,"u_prevarpno","");
						setTableInput(table,"u_prevarpno2","");
						setTableInput(table,"u_prevtdno","");
						setTableInput(table,"u_prevowner","");
						setTableInput(table,"u_preveffdate","");
						setTableInput(table,"u_prevrecordedby","");
						setTableInput(table,"u_prevrecordeddate","");
						setTableInput(table,"u_prevvalue",formatNumericAmount(0));
					}
					break;
			}
			break;
		default:
			switch(column) {
				case "u_ownertin":
                                    if (getInput(column)!="") {
							var result = page.executeFormattedQuery("select u_adminname,u_ownername,u_ownercompanyname, u_ownerfirstname,u_ownerlastname,u_ownermiddlename,u_ownertype,u_ownertelno,u_owneraddress,u_adminlastname,u_adminfirstname,u_adminmiddlename,u_admintelno,u_adminaddress from u_rpfaasmds where docno='"+getInput(column)+"'");	
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_ownername",result.childNodes.item(0).getAttribute("u_ownername"));
									setInput("u_ownercompanyname",result.childNodes.item(0).getAttribute("u_ownercompanyname"));
									setInput("u_ownerfirstname",result.childNodes.item(0).getAttribute("u_ownerfirstname"));
									setInput("u_ownerlastname",result.childNodes.item(0).getAttribute("u_ownerlastname"));
									setInput("u_ownermiddlename",result.childNodes.item(0).getAttribute("u_ownermiddlename"));
									setInput("u_ownertype",result.childNodes.item(0).getAttribute("u_ownertype"));
									setInput("u_ownertelno",result.childNodes.item(0).getAttribute("u_ownertelno"));
									setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_owneraddress"));
									setInput("u_adminname",result.childNodes.item(0).getAttribute("u_adminname"));
									setInput("u_adminlastname",result.childNodes.item(0).getAttribute("u_adminlastname"));
									setInput("u_adminfirstname",result.childNodes.item(0).getAttribute("u_adminfirstname"));
									setInput("u_adminmiddlename",result.childNodes.item(0).getAttribute("u_adminmiddlename"));
									setInput("u_admintelno",result.childNodes.item(0).getAttribute("u_admintelno"));
									setInput("u_adminaddress",result.childNodes.item(0).getAttribute("u_adminaddress"));
								} else {
									setInput("u_ownername","");
									setInput("u_ownercompanyname","");
									setInput("u_ownerfirstname","");
									setInput("u_ownerlastname","");
									setInput("u_ownermiddlename","");
									setInput("u_ownertype","");
									setInput("u_ownertelno","");
									setInput("u_owneraddress","");
									setInput("u_adminlastname","");
									setInput("u_adminfirstname","");
									setInput("u_adminmiddlename","");
									setInput("u_admintelno","");
									setInput("u_adminaddress","");
									page.statusbar.showError("Invalid Local Tin. setup is required");	
									return false;
								}
							} else {
                                                                        setInput("u_ownercompanyname","");
									setInput("u_ownerfirstname","");
									setInput("u_ownerlastname","");
									setInput("u_ownermiddlename","");
									setInput("u_ownertype","");
									setInput("u_ownertelno","");
									setInput("u_owneraddress","");
									setInput("u_adminlastname","");
									setInput("u_adminfirstname","");
									setInput("u_adminmiddlename","");
									setInput("u_admintelno","");
									setInput("u_adminaddress","");
								page.statusbar.showError("Error retrieving Local Tin record. Try Again, if problem persists, check the connection.");	
								return false;
							}
							
					} 
                                    break;
				case "u_pin":
					if (getInput(column)!="") {
						if (getInput("u_pinchanged")=="1") return true;
						return getPrevFaas("",getInput(column));
					} else {
					}
					break;
				case "u_ownercompanyname":
					setInput("u_ownername",getInput("u_ownercompanyname"));
					break;
				case "u_ownerlastname":
				case "u_ownerfirstname":
				case "u_ownermiddlename":
					setInput("u_ownername",utils.trim(getInput("u_ownerlastname") + ", " + getInput("u_ownerfirstname") + " " + getInput("u_ownermiddlename")));
					break;
				case "u_adminlastname":
				case "u_adminfirstname":
				case "u_adminmiddlename":
					setInput("u_adminname",utils.trim(getInput("u_adminlastname") + ", " + getInput("u_adminfirstname") + " " + getInput("u_adminmiddlename")));
					break;
			}
	}
	return true;
}

function onElementGetValidateParamsGPSRPTAS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSRPTAS(element,column,table,row) {
	return true;
}

function onElementChangeGPSRPTAS(element,column,table,row) {
        switch (table) {
                   default:
                       switch (column) {
                            case "u_barangay":
                                if (getInput(column)!="") {
                                        //getNextTdNoGPSRPTAS(getInput(column));
                                    }
                            break;
                            case "u_trxcode":
                                       if (getInput(column)=="DC") {
                                           if(confirm("Fill the field with [NEW] in record of superseded?")){
                                               setInput("u_prevpin","NEW");
                                               setInput("u_prevtdno","NEW");
                                               setInput("u_prevowner","NEW");
                                           }
                                       } 
                                   break;
                               case "u_tdseries":
                                     if (getInput(column)!="") {
                                         var result = page.executeFormattedQuery("select DOCSERIESNAME from DOCSERIES where DOCSERIES="+getInput(column)+"");	
                                                if (result.getAttribute("result")!= "-1") {
                                                        if (parseInt(result.getAttribute("result"))>0) {
                                                            setInput("u_barangay",result.childNodes.item(0).getAttribute("docseriesname"));
                                                           
                                                        }
                                                }
                                     }
                                      setDocNo(true,"u_tdseries","u_tdno","u_date");
                                      setDocNo(true,"u_tdseries","u_varpno","u_date");
                               break;
                       }
                       break;
               }
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
	switch (table) {
		case "T103":
			switch (column) {
				case "edit":
					if (row==0) {
						if (getInput("docstatus")=="Encoding") {
							var targetObjectId = '';
							OpenLnkBtn(1080,600,'./udo.php?objectcode=u_rpfaas1a' + '' + '&targetId=' + targetObjectId ,targetObjectId);
						} else page.statusbar.showWarning("You can only add new appraisal if status is encoding.");
					} else {
						selectTableRow("T103",row);	
						targetObjectId = 'edit';
						OpenLnkBtn(1080,600,'./udo.php?objectcode=u_rpfaas1a' + '' + '&targetId=' + targetObjectId ,targetObjectId);
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_pinchanged":
					if (isInputChecked("u_pinchanged")) {
						enableInput("u_pin");
					} else {
						disableInput("u_pin");
						setInput("u_pin",getInput("u_prevpin"));
					}
					break;
			}
			break;
	}
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
	switch (Id) {
		case "df_u_prevarpnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_tdno,u_pin, u_ownername from u_rpfaas1 where  docstatus in('Recommended','Approved')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ID No.`TD No.`PIN`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`22`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
		case "df_u_prevtdnoT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno,u_varpno, u_pin, u_ownername from u_rpfaas1 where  docstatus in('Recommended','Approved')")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD No.`Arp No.`PIN`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
		case "df_u_ownertin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_ownercompanyname,u_ownerlastname, u_ownerfirstname,u_ownermiddlename from u_rpfaasmds ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Local Tin`Name/s`Lastname`Firstname`Middlenamer")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`30`15`15`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSRPTAS(Id,params) {
	switch (Id) {
		case "edit":
			params["keys"] = getTableInput("T103","docno",getTableSelectedRow("T103"));
			break;
	}
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	switch (table) {
		case "T101":
			if (getInput("docstatus")=="Encoding") {
				var targetObjectId = '';
				OpenLnkBtn(1024,600,'./udo.php?objectcode=u_rpfaas1a' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				return false;
			} else page.statusbar.showWarning("You can only add new appraisal if status is encoding.");
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
    switch (table) {
		case "T1":
			isupdate = true;
                }
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
    switch (table) {
		case "T1":
			isupdate = true;
	}
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableBeforeEditRowGPSRPTAS(table,row) {
	switch (table) {
		case "T2":
//			openfaas();
			break;
	}
	return true;
}
function onTableSelectRowGPSRPTAS(table,row) {
    switch (table) {
        case"T2":
            
            arpno = getTableInput(table,"u_arpno",row);
            openfaas();
           
            break;
        case"T1":
            arpno1 = getTableInput(table,"u_prevarpno",row);
//            openfaas1();
        break;
    }
    return true
}

function u_forRecommendGPSRPTAS() {
	if (isInputEmpty("u_trxcode",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_pin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownertin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownername",null,null,"tab1",0)) return false;
	if (isInputNegative("u_effqtr",null,null,"tab1",1)) return false;
	if (isInputNegative("u_effyear",null,null,"tab1",1)) return false;

	if (isInputEmpty("u_assessedby",null,null,"tab1",4)) return false;
	if (isInputEmpty("u_assesseddate",null,null,"tab1",4)) return false;
	//if (getInput("u_assessedby")=="") setInput("u_assessedby",getGlobal("username"));
	setInput("docstatus","Assessed");
	formSubmit('sc');
}

function u_forApprovalGPSRPTAS() {
	if (isInputEmpty("u_trxcode",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_pin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownertin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownername",null,null,"tab1",0)) return false;
	if (isInputNegative("u_effqtr",null,null,"tab1",1)) return false;
	if (isInputNegative("u_effyear",null,null,"tab1",1)) return false;
	
	if (isInputEmpty("u_recommendby")) return false;
	if (isInputEmpty("u_recommenddate")) return false;
	//if (getInput("u_recommendby")=="") setInput("u_recommendby",getGlobal("username"));
	setInput("docstatus","Recommended");
	formSubmit('sc');
}


function u_approveGPSRPTAS() {
	if (isInputEmpty("u_trxcode",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_pin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_oldbarangay",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownertin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownername",null,null,"tab1",0)) return false;
	if (isInputNegative("u_effqtr",null,null,"tab1",1)) return false;
	if (isInputNegative("u_effyear",null,null,"tab1",1)) return false;
	
	if (isInputEmpty("u_tdno")) return false;
	if (isInputEmpty("u_varpno")) return false;
	if (isInputEmpty("u_approvedby")) return false;
	if (isInputEmpty("u_approveddate")) return false;
	if (isInputEmpty("u_releaseddate")) return false;
	//if (getInput("u_approvedby")=="") setInput("u_approvedby",getGlobal("username"));
	setInput("docstatus","Approved");
	formSubmit('sc');
}

function getPrevFaas(field,value) {
	if (field=='arpno') {
		var result = page.executeFormattedQuery("select * from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and docno='"+value+"'");
	} else {
		var result = page.executeFormattedQuery("select * from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_pin='"+value+"' order by u_effdate desc");	
        }
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			if (result.childNodes.item(0).getAttribute("docstatus")=="Encoding") {
				setKey("keys",result.childNodes.item(0).getAttribute("docno"));
				alert("New assessment with status ["+result.childNodes.item(0).getAttribute("docstatus")+"] exists, assessment will be retrieve.");
				formEdit();
			} else if (result.childNodes.item(0).getAttribute("u_cancelled")=="1") {
				alert("Faas was already cancelled.");
				return false;
			} else {	
				if (field=="") alert('Previous record exists, information will be forwared.');
				disableInput("u_pin");
				if (field=='arpno') {
					setInput("u_pin",result.childNodes.item(0).getAttribute("u_pin"));
				}
				setInput("u_cadlotno",result.childNodes.item(0).getAttribute("u_cadlotno"));
				setInput("u_phase",result.childNodes.item(0).getAttribute("u_phase"));
				setInput("u_tctno",result.childNodes.item(0).getAttribute("u_tctno"));
				setInput("u_tctdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_tctdate")));
				setInput("u_surveyno",result.childNodes.item(0).getAttribute("u_surveyno"));
				setInput("u_lotno",result.childNodes.item(0).getAttribute("u_lotno"));
				setInput("u_block",result.childNodes.item(0).getAttribute("u_block"));
				setInput("u_cadlotno",result.childNodes.item(0).getAttribute("u_cadlotno"));
				setInput("u_ownertype",result.childNodes.item(0).getAttribute("u_ownertype"));
				setInput("u_ownercompanyname",result.childNodes.item(0).getAttribute("u_ownercompanyname"));
				setInput("u_ownerlastname",result.childNodes.item(0).getAttribute("u_ownerlastname"));
				setInput("u_ownerfirstname",result.childNodes.item(0).getAttribute("u_ownerfirstname"));
				setInput("u_ownermiddlename",result.childNodes.item(0).getAttribute("u_ownermiddlename"));
				setInput("u_ownername",result.childNodes.item(0).getAttribute("u_ownername"));
				setInput("u_owneraddress",result.childNodes.item(0).getAttribute("u_owneraddress"));
				setInput("u_ownertelno",result.childNodes.item(0).getAttribute("u_ownertelno"));
				setInput("u_ownertin",result.childNodes.item(0).getAttribute("u_ownertin"));
				setInput("u_adminlastname",result.childNodes.item(0).getAttribute("u_adminlastname"));
				setInput("u_adminfirstname",result.childNodes.item(0).getAttribute("u_adminfirstname"));
				setInput("u_adminmiddlename",result.childNodes.item(0).getAttribute("u_adminmiddlename"));
				setInput("u_adminaddress",result.childNodes.item(0).getAttribute("u_adminaddress"));
				setInput("u_admintelno",result.childNodes.item(0).getAttribute("u_admintelno"));
				setInput("u_admintin",result.childNodes.item(0).getAttribute("u_admintin"));
				setInput("u_street",result.childNodes.item(0).getAttribute("u_street"));
				setInput("u_barangay",result.childNodes.item(0).getAttribute("u_barangay"));
				setInput("u_municipality",result.childNodes.item(0).getAttribute("u_municipality"));
				setInput("u_city",result.childNodes.item(0).getAttribute("u_city"));
				setInput("u_province",result.childNodes.item(0).getAttribute("u_province"));
				setInput("u_north",result.childNodes.item(0).getAttribute("u_north"));
				setInput("u_east",result.childNodes.item(0).getAttribute("u_east"));
				setInput("u_south",result.childNodes.item(0).getAttribute("u_south"));
				setInput("u_west",result.childNodes.item(0).getAttribute("u_west"));
				setInput("u_taxable",result.childNodes.item(0).getAttribute("u_taxable"));
				setInput("u_assvalue",result.childNodes.item(0).getAttribute("u_assvalue"));
				setInput("u_prevpin",result.childNodes.item(0).getAttribute("u_pin"));
				setInput("u_prevarpno",result.childNodes.item(0).getAttribute("docno"));
				setInput("u_prevarpno2",result.childNodes.item(0).getAttribute("u_varpno"));
				setInput("u_prevtdno",result.childNodes.item(0).getAttribute("u_tdno"));
				setInput("u_prevowner",result.childNodes.item(0).getAttribute("u_ownername"));
				setInput("u_preveffdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_effdate")));
				setInput("u_prevrecordedby",result.childNodes.item(0).getAttribute("u_recordedby"));
				setInput("u_prevrecordeddate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_recordeddate")));
				setInput("u_prevvalue",formatNumericAmount(result.childNodes.item(0).getAttribute("u_assvalue")));
			}									
		}
	} else {
		setInput("u_pin","");
		page.statusbar.showError("Error checking land record. Try Again, if problem persists, check the connection.");	
		return false;
	}
	return true;
}

function u_googlemapGPSRPTAS() {
	OpenPopup(800,600,"./udp.php?&objectcode=u_maps","Google Map");	
}

function u_googleearthGPSRPTAS() {
	OpenPopup(800,600,"./udp.php?&objectcode=u_googleearth","Google Map");	
}

//WILSON
function u_reassessGPSRPTAS() {
	setInput("docstatus","Encoding");
	formSubmit('sc');
}
function u_consolidateGPSRPTAS() {

    if(isupdate){
          setStatusMsg("Please save this FAAS prior consolidation.",4000,1);
          return false;   
     }else{
         formSubmit('consolidate');
     }
     return true;
}

function openfaas() {
        OpenPopup(1024,600,"./udo.php?&objectcode=u_rpfaas2&sf_keys="+arpno+"&formAction=e","UpdPays");	
}

function openfaas1() {
        OpenPopup(1024,600,"./udo.php?&objectcode=u_rpfaas1&sf_keys="+arpno1+"&formAction=e","UpdPays");	
}

function AddNewTin(){
    OpenPopup(1024,350,"./udo.php?&objectcode=u_rpfaasmds","Real Property Owners");
}

function getNextTdNoGPSRPTAS(barangayname){
    var numlen = 0,prefix,suffix, nextno = 0,tdno = '',d = new Date();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();

    month = month.toString();
    year = year.toString();
    
//        var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME FROM U_TERMINALSERIES WHERE U_DOCSERIES = '"+seriesname+"' AND U_NEXTNO <= U_LASTNO AND COMPANY = '"+getGlobal("company")+"' AND BRANCH = '"+getGlobal("branch")+"' ");
        var result = page.executeFormattedQuery("SELECT U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO FROM U_BARANGAYS WHERE CODE = '"+barangayname+"'  ");
//    } else {
//        var result = page.executeFormattedQuery("SELECT DOCNO,U_NUMLEN,U_PREFIX,U_SUFFIX,U_NEXTNO,U_DOCSERIESNAME FROM U_TERMINALSERIES WHERE U_DOCSERIESNAME = '"+getInput("u_docseries")+"' AND U_CASHIER = '"+getPrivate("userid")+"' AND DOCNO = '' U_NEXTNO <= U_LASTNO");
//    }
    	
        if (result.getAttribute("result")!= "-1") {
            if (parseInt(result.getAttribute("result"))>0) {
                    numlen = result.childNodes.item(0).getAttribute("u_numlen");
                    prefix = result.childNodes.item(0).getAttribute("u_prefix");
                    suffix = result.childNodes.item(0).getAttribute("u_suffix");
                    nextno = result.childNodes.item(0).getAttribute("u_nextno");

                    nextno = nextno.toString();

                    prefix = prefix.replace("{POS}",getInput("u_terminalid"));
                    suffix = suffix.replace("{POS}",getInput("u_terminalid"));
                    prefix = prefix.replace("[m]",month.padStart1(2,'0'));
                    suffix = suffix.replace("[m]",month.padStart1(2,'0'));
                    prefix = prefix.replace("[Y]",year);
                    suffix = suffix.replace("[Y]",year);
                    prefix = prefix.replace("[y]",year.padStart1(2,'0'));
                    suffix = suffix.replace("[y]",year.padStart1(2,'0'));

                    tdno = prefix + nextno.padStart1(numlen,'0') + suffix;
                    setInput("u_tdno",tdno);
                    setInput("u_varpno",tdno);
            }
        }
}

if (!String.prototype.padStart1) {
    String.prototype.padStart1 = function padStart1(targetLength, padString) {
        targetLength = targetLength >> 0; //truncate if number, or convert non-number to 0;
        padString = String(typeof padString !== 'undefined' ? padString : ' ');
        if (this.length >= targetLength) {
            return String(this);
        } else {
            targetLength = targetLength - this.length;
            if (targetLength > padString.length) {
                padString += padString.repeat(targetLength / padString.length); //append to original to ensure we are longer than needed
            }
            return padString.slice(0, targetLength) + String(this);
        }
    }
}

function u_PrevPostingGPSRPTAS() {
        if(getInput("u_tdno") == "Cash Sales" || getInput("u_tin") == ""){
            setStatusMsg("Please select FAAS. Tin/Td # is required",4000,1);
        }else{
            OpenPopup(1024,700,"./udo.php?&objectcode=u_rpprevposting&formAction=e","Post Previous");
        } 
}

function u_ViewPaymentHistory() {
        OpenPopup(1280,550,"./udp.php?&objectcode=u_rptpaymenthistory&df_refno2="+getInput("docno")+"","RPT Payment History");
}


function u_AuctionGPSRPTAS() {
	if(confirm("For Auction TD ["+getInput("u_varpno")+"]. Continue?")){
            setInput("u_isauction",1);
            formSubmit();
        }
}

function showPopupAuctionTD(){
    showPopupFrame("popupFrameAuction",true);
    focusTableInput("T51","userid");
}

function unAuctionTDGPSRPTAS() {
    
        if (isTableInput("T51","userid")) {
            if (getTableInput("T51","userid")=="") {
                showPopupFrame("popupFrameAuction",true);
                focusTableInput("T51","userid");
                return false;
            }
        }
        
    var result = page.executeFormattedQuery("SELECT username, u_rptapprover from users where userid = '"+getTableInput("T51","userid")+"' and hpwd = '"+MD5(getTableInput("T51","password"))+"' ");
        if (parseInt(result.getAttribute("result"))>0) {
            for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                if (result.childNodes.item(0).getAttribute("u_rptapprover") == 0) {
                    page.statusbar.showError("You are not allowed for this action");
                    setInput("u_isauction",1);
                    return false;
                } else {
                    setInput("u_isauction",0);
                    if (isPopupFrameOpen("popupFrameAuction")) {
                        hidePopupFrame('popupFrameAuction');
                    }
                    formSubmit();
                    }

            }
        } else {
            page.statusbar.showError("Invalid user or password.");
            setInput("u_isauction",1);
            return false;
        }
}