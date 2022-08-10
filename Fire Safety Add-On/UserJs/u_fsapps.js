// page events
page.events.add.load('onPageLoadGPSFireSafety');
//page.events.add.resize('onPageResizeGPSFireSafety');
page.events.add.submit('onPageSubmitGPSFireSafety');
//page.events.add.cfl('onCFLGPSFireSafety');
//page.events.add.cflgetparams('onCFLGetParamsGPSFireSafety');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSFireSafety');

// element events
//page.elements.events.add.focus('onElementFocusGPSFireSafety');
page.elements.events.add.keydown('onElementKeyDownGPSFireSafety');
page.elements.events.add.validate('onElementValidateGPSFireSafety');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSFireSafety');
//page.elements.events.add.changing('onElementChangingGPSFireSafety');
//page.elements.events.add.change('onElementChangeGPSFireSafety');
page.elements.events.add.click('onElementClickGPSFireSafety');
//page.elements.events.add.cfl('onElementCFLGPSFireSafety');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSFireSafety');

// table events
//page.tables.events.add.reset('onTableResetRowGPSFireSafety');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSFireSafety');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSFireSafety');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSFireSafety');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSFireSafety');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSFireSafety');
page.tables.events.add.delete('onTableDeleteRowGPSFireSafety');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSFireSafety');
page.tables.events.add.select('onTableSelectRowGPSFireSafety');

function onPageLoadGPSFireSafety() {
    focusInput("u_apptype");
}

function onPageResizeGPSFireSafety(width,height) {
}

function onPageSubmitGPSFireSafety(action) {
    	if (action=="a" || action=="sc") {
                
		if (isInputEmpty("u_appnature")) return false;
		if (isInputEmpty("u_appno")) return false;
		if (isInputEmpty("u_bldgappno")) return false;
		if (isInputEmpty("u_docdate")) return false;
		if (isInputEmpty("u_projectname",null,null,"",0)) return false;
		if (isInputNegative("u_totalamount")) return false;
	}
       
        
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
        var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);

	switch (sc_press) {
//		case "ESC":
//			if (isPopupFrameOpen("popupFramePayments")) {
//				hidePopupFrame('popupFramePayments');
//				setT1FocusInputGPSPOS();
//			}
//			break;
//		case "F4":
//			u_AcceptPaymenGPSFireSafety();
//			break;
//		case "F6":
//			u_returnGPSPOS();
//			break;
//		case "F7":
//			u_salesGPSPOS();
//			break;
//		case "F9":
//			formSubmit();
//			break;
//		default:
//			switch (table) {
//				case "T1":
//					break;
//				default:	
//					switch (column) {
//						case "u_totalamount2":
//							if (sc_press=="ENTER") {
//								setInputAmount(column,getInputNumeric(column),true);
//								focusInput(column);
//							}
//							break;
//							
//					}
//					break;
//			}
//			break;
	}
}

function onElementValidateGPSFireSafety(element,column,table,row) {
    switch (table) {
        case "T1":
                switch (column) {
                    case "u_amount":
                            computeTotalAssessment();
                            break;
                }
        break;
        default:
            switch (column) {
                case "u_bpno":
                   
                    if (getInput(column)!="") {
                                var result = page.executeFormattedQuery("select DOCID, U_BUSINESSNAME,U_FIREASSTOTAL,U_APPTYPE,OWNERNAME,CONTACTNO, CONCAT(u_baddressno,u_bbldgno,u_bunitno,u_bfloorno, u_bbldgname,u_bblock, u_blotno,u_bphaseno, u_bstreet, u_bvillage,u_bbrgy,u_bcity,u_bprovince ) AS baddress FROM (select docid,u_businessname,u_fireasstotal,u_apptype,if(u_orgtype='SINGLE',concat(u_lastname,', ',u_firstname,' ',u_middlename),if(u_tradename='',u_businessname,u_tradename)) as ownername, IF(IFNULL(a.u_baddressno,'')='','',CONCAT(a.u_baddressno,', ')) AS u_baddressno, IF(IFNULL(a.u_bbldgno,'') = '','',CONCAT(a.u_bbldgno,' ')) AS u_bbldgno,IF(IFNULL(a.u_bunitno,'') = '','',CONCAT(a.u_bunitno,', ')) AS u_bunitno, IF(IFNULL(a.u_bfloorno,'') = '','',CONCAT(a.u_bfloorno,', ')) AS u_bfloorno,IF(IFNULL(a.u_bbldgname,'') = '','',CONCAT(a.u_bbldgname,', ')) AS u_bbldgname, IF(IFNULL(a.u_bblock,'')='','',CONCAT('BLK ',a.u_bblock,' ')) AS u_bblock, IF(IFNULL(a.u_blotno,'')='','',CONCAT('LOT ',a.u_blotno,' ')) AS u_blotno, IF(IFNULL(a.u_bphaseno,'')='','',CONCAT('PHASE ',a.u_bphaseno,', ')) AS u_bphaseno, IF(IFNULL(a.u_bstreet,'')='','',CONCAT(a.u_bstreet,', ')) AS u_bstreet, IF(IFNULL(a.u_bvillage,'')='','',CONCAT(a.u_bvillage,', ')) AS u_bvillage, IF(IFNULL(a.u_bbrgy,'')='','',CONCAT(a.u_bbrgy,', ')) AS u_bbrgy, IF(IFNULL(a.u_bcity,'')='','',CONCAT(a.u_bcity,', ')) AS u_bcity, IF(IFNULL(a.u_bprovince,'')='','',CONCAT(a.u_bprovince,' ')) AS u_bprovince ,if(u_telno='',u_btelno,u_telno) as contactno from u_bplapps A where docno='"+getInput(column)+"' and company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"') AS X");	
                   
                                if (result.getAttribute("result")!= "-1") {
                                        if (parseInt(result.getAttribute("result"))>0) {    
                                                if(result.childNodes.item(0).getAttribute("u_apptype")=="NEW"){
                                                    setInput("u_apptype","New Business Permit");
                                                }else if(result.childNodes.item(0).getAttribute("u_apptype")=="RENEW"){
                                                    setInput("u_apptype","Renewal of Business Permit");
                                                }else{
                                                    setInput("u_apptype","");
                                                }
                                                var result1 = page.executeFormattedQuery("select group_concat(u_businessline) as u_busline from u_bplapplines where docid = '"+result.childNodes.item(0).getAttribute("docid")+"'  and company = '"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"' group by docid ");	
                                                 if (result1.getAttribute("result")!= "-1") {
                                                     if (parseInt(result1.getAttribute("result"))>0) {
                                                         setInput("u_classification",result1.childNodes.item(0).getAttribute("u_busline"));
                                                     }
                                                 }
                                                setInput("u_contactno",result.childNodes.item(0).getAttribute("contactno"));
                                                setInput("u_address",result.childNodes.item(0).getAttribute("baddress"));
                                                setInput("u_ownerrep",result.childNodes.item(0).getAttribute("ownername"));
                                                setInput("u_businessname",result.childNodes.item(0).getAttribute("u_businessname"));
                                                setInputAmount("u_orfcamt",result.childNodes.item(0).getAttribute("u_fireasstotal"));
                                        }else{
                                                setInput("u_apptype","");
                                                setInput("u_businessname","");
                                                setInputAmount("u_orfcamt",0);
                                                page.statusbar.showError("Invalid Business Permit No");	
                                                return false;
                                        }
                                } else {
                                        setInput("u_apptype","");
                                        setInput("u_businessname","");
                                        setInputAmount("u_orfcamt",0);
                                        page.statusbar.showError("Error retrieving Business Permit record. Try Again, if problem persists, check the connection.");	
                                        return false;
                                }
                        } else {
                                setInput("u_apptype","");
                                setInput("u_businessname","");
                                setInputAmount("u_orfcamt",0);
                        }						
                    break;
                  
                
            }
            break;
            
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
    switch (table) {
        case "T1":
                switch (column) {
                    case "u_check":
                            computeTotalAssessment();
                            break;
                }
            break;
        default:
             switch (column) {
                    case "u_appnature":
                            setRequirements();
                            break;
                }
            break;
    }
	return true;
}

function onElementCFLGPSFireSafety(element) {
	return true;
}

function onElementCFLGetParamsGPSFireSafety(Id,params) {
        switch (Id) {
          
		case "df_u_feecodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name  from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_feedescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code   from u_lgufees")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`Fee")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
                case "df_u_bstreet":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME,U_BRGY FROM U_STREETS  where  u_brgy = '"+getInput("u_bbrgy")+"' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Street`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_bvillage":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("SELECT NAME,u_barangay FROM u_subdivisions where  u_barangay = '"+getInput("u_bbrgy")+"' ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Subdivisions`Barangay")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30`30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
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
    switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeUpdateRowGPSFireSafety(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSFireSafety(table,row) {
    switch (table) {
		case "T1": computeTotalAssessment();break;
    }
}

function onTableBeforeDeleteRowGPSFireSafety(table,row) {
	return true;
}

function onTableDeleteRowGPSFireSafety(table,row) {
    switch (table) {
		case "T1": computeTotalAssessment();break;
    }   
}

function onTableBeforeSelectRowGPSFireSafety(table,row) {
	return true;
}

function onTableSelectRowGPSFireSafety(table,row) {
        var params = new Array();
	params["focus"] = false;
	return params;
}

function u_AcceptPaymenGPSFireSafety(){
    if(!confirm("Accept payment. Continue?")) return false;
    setInput("docstatus","P");
    formSubmit();
}
function computeTotalAssessment() {
	var rc = getTableRowCount("T1"),total=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
                        if (getTableInput("T1","u_check",i)==1) total+= getTableInputNumeric("T1","u_amount",i);
		}
	}
 
	setInputAmount("u_totalamount",total);
}

function u_approveGPSLGUFireSafety() {
        if (isInputEmpty("u_bldgappno")) return false;
        if (isInputEmpty("u_appnature",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_docdate",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_projectname",null,null,"tab1",0)) return false;
        if (isInputNegative("u_totalamount",null,null,"tab1",0)) return false;
    
    if (getTableRowCount("T1")==0) {
        page.statusbar.showError("At least one fee must be entered.");
        selectTab("tab1",1);
        return false;
    }
    setInput("docstatus","AP");
    formSubmit();
}

function u_disapproveGPSLGUFireSafety() {
        setInput("docstatus","DA");
        formSubmit('sc');
}
function u_reassessmentGPSLGUFireSafety() {
        setInput("docstatus","O");
        formSubmit('sc');
}
function u_googlemapGPSLGUFireSafety() {
	OpenPopupViewMap("viewmap","U_BPLAPPS",getInput("u_bphaseno") + " " + getInput("u_bblock") + " " + getInput("u_blotno") + " " +  getInput("u_bvillage") + " " + getInput("u_bstreet"),getInput("u_bbrgy"),getInput("u_bcity"),"","",getInput("u_bprovince"),"Philippines","");
}
function getAddressGIS() {
	if (getInput("u_latitude")!="" && getInput("u_longitude")!="") {
		var latlng = {lat: parseFloat(getInput("u_latitude")), lng: parseFloat(getInput("u_longitude"))};
	} else {
		var latlng = false;
	}	
	return latlng;
}

function setAddressGIS(lat,lng) {
	setInput("u_latitude",lat);
	setInput("u_longitude",lng);
}

function setRequirements() {
	clearTable("T2",true);
	var result = page.executeFormattedQuery("select a.code, a.name from u_fsreqs a where a.u_apptype = '"+getInput("u_appnature")+"' order by a.u_seq");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                            var data = new Array();
                            data["u_reqcode"] = result.childNodes.item(xxx).getAttribute("code");
                            data["u_reqdesc"] = result.childNodes.item(xxx).getAttribute("name");
                            insertTableRowFromArray("T2",data);
                        }
		}		
	} 
}