// page events
//page.events.add.load('onPageLoadGPSRPTAS');
//page.events.add.resize('onPageResizeGPSRPTAS');
page.events.add.submit('onPageSubmitGPSRPTAS');
//page.events.add.cfl('onCFLGPSRPTAS');
//page.events.add.cflgetparams('onCFLGetParamsGPSRPTAS');

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
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSRPTAS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSRPTAS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSRPTAS');
//page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
page.tables.events.add.select('onTableSelectRowGPSRPTAS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSRPTAS');
var isupdate = false;
var pin = "";
function onPageLoadGPSRPTAS() {
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
    
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_pin")) return false;
		if (isInputEmpty("u_ownername")) return false;


               
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
	var data = new Array(),tax=0,sef=0,penalty=0,sefpenalty=0;
	switch (table) {
                case "T1":
                        switch (column) {
                            case "u_pin":
                            case "u_ownername":
                            isupdate = true;
                            break;
                        }
		default:
			switch (column) {
                                
				case "u_subdno":
				case "u_tdno":
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_ownertin, docno, u_pin, type, u_ownername,u_sqm,u_assvalue from (select a.u_ownertin, a.docno,a.u_pin, 'Land' as type,  if(a.u_ownertype = 'C',a.u_ownercompanyname,a.u_ownername) as u_ownername,sum(b.u_sqm) as u_sqm,a.u_assvalue  from u_rpfaas1 a left join u_rpfaas1a b on a.docno = b.u_arpno and a.company = b.company and a.branch = b.branch where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_tdno='"+getInput("u_tdno")+"' ) as x");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
                                                            
								setInput("u_pin",result.childNodes.item(0).getAttribute("u_pin"));
								setInput("u_sqm",result.childNodes.item(0).getAttribute("u_sqm"));
								setInput("u_assvalue",result.childNodes.item(0).getAttribute("u_assvalue"));
								setInput("u_ownername",result.childNodes.item(0).getAttribute("u_ownername"));
								setInput("u_arpno",result.childNodes.item(0).getAttribute("docno"));
                                                             
                                                               
                                                                            if(getInputNumeric("u_subdno") > 0){
                                                                                isupdate = true;
                                                                                clearTable("T1",true);
                                                                                var subdno = getInputNumeric("u_subdno");
                                                                                var data = new Array();
                                                                                    for (xxx = 0; xxx < subdno; xxx++) {
                                                                                            data["u_arpno"] = 'DRAFT - ' + xxx  ;
                                                                                            data["u_pin"] =getInput("u_pin") + '-'+xxx;
                                                                                            data["u_tdno"] ='DRAFT';
                                                                                            data["u_ownername"] =getInput("u_ownername");
                                                                                            data["u_ownertin"] = result.childNodes.item(0).getAttribute("u_ownertin") ;
                                                                                            data["u_sqm"] = formatNumericAmount(getInput("u_sqm") / subdno );
                                                                                            data["u_assvalue"] = formatNumericAmount(getInput("u_assvalue") / subdno) ;
                                                                                            insertTableRowFromArray("T1",data);
                                                                                         }
                                                                            }
                                                                                 
                                                                                   
								
							} else {
								setInputAmount("u_pin","");
								setInputAmount("u_tdno","");
								page.statusbar.showError("Invalid TD No.");	
								return false;
							}
						} else {
                                                        setInputAmount("u_pin","");
							setInputAmount("u_tdno","");
							page.statusbar.showError("Error retrieving faas record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                                 
					}						
					break;
				
			}
			break;
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
		case "T1":
			switch (column) {
                            case "u_pin":
                            case "u_ownername":
                                isupdate = true;
                                break;
                        }
                    
                        }
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
	
	return true;
}

function onElementCFLGPSRPTAS(element) {
	return true;
}

function onElementCFLGetParamsGPSRPTAS(Id,params) {
	switch (Id) {

		case "df_u_tdno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno,docno,u_pin,u_type,u_ownername from (select u_tdno,docno,u_pin, 'Land ' as u_type, if(u_ownertype = 'C',u_ownercompanyname,u_ownername) as u_ownername from u_rpfaas1 where u_cancelled <> 1 and u_pin <> '' order by u_pin) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD No.`ID No.`PIN`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`19`8`32")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;

		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_pin,docno,u_tdno,u_type,u_ownername from (select u_pin,docno,u_tdno, 'Land ' as u_type, if(u_ownertype = 'C',u_ownercompanyname,u_ownername) as u_ownername from u_rpfaas1 where u_cancelled <> 1 and u_pin <> '' order by u_pin) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Reference No.`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table) {
	
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
    
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
    	
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
     updateBilldate();
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
}

function onTableBeforeEditRowGPSRPTAS(table,row) {
	return true;
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
    var params = Array();
    switch (table) {
        case"T1":
            pin = getTableInput(table,"u_pin",row);
            openfaas();
            params["focus"] = false;
            break;
    }
   
    return params;
}


function openfaas() {
        var result = page.executeFormattedQuery("select docno from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_pin='"+pin+"' group by u_pin order by datecreated desc ");	
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
                       OpenPopup(1024,600,"./udo.php?&objectcode=u_rpfaas1&sf_keys="+ result.childNodes.item(0).getAttribute("docno")+"&formAction=e","UpdPays");
                }
            }
			
}

function u_subdivideGPSRPTAS() {

    if(isupdate){
          setStatusMsg("Please save/update prior Subdivision.",4000,1);
          return false;   
     }else{
         setInput("docstatus","C");
         formSubmit('subdivide');
     }
     return true;
}

