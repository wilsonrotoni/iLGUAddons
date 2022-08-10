// page events
page.events.add.load('onPageLoadGPSBPLS');
//page.events.add.resize('onPageResizeGPSBPLS');
page.events.add.submit('onPageSubmitGPSBPLS');
//page.events.add.cfl('onCFLGPSBPLS');
//page.events.add.cflgetparams('onCFLGetParamsGPSBPLS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSBPLS');

// element events
//page.elements.events.add.focus('onElementFocusGPSBPLS');
page.elements.events.add.keydown('onElementKeyDownGPSBPLS');
page.elements.events.add.validate('onElementValidateGPSBPLS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSBPLS');
//page.elements.events.add.changing('onElementChangingGPSBPLS');
//page.elements.events.add.change('onElementChangeGPSBPLS');
//page.elements.events.add.click('onElementClickGPSBPLS');
//page.elements.events.add.cfl('onElementCFLGPSBPLS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSBPLS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSBPLS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSBPLS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSBPLS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSBPLS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSBPLS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSBPLS');
page.tables.events.add.delete('onTableDeleteRowGPSBPLS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSBPLS');
//page.tables.events.add.select('onTableSelectRowGPSBPLS');

function onPageLoadGPSBPLS() {
	if (getVar("formSubmitAction")=="a") {
                // focusInput("u_lastname");
                setTableInput("T1","u_year",getInput("u_year"));
                
            }
        }

        function onPageResizeGPSBPLS(width,height) {
        }

    function onPageSubmitGPSBPLS(action) {
            if (action=="a" || action=="sc") {
                if (isInputEmpty("u_projname",null,null,"tab1",0)) return false;
                if (isInputNegative("u_duration",null,null,"tab1",0)) return false;
                if (isInputNegative("u_amount",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_bldgno",null,null,"tab1",0)) return false;
                if (isInputNegative("u_year",null,null,"tab1",0)) return false;
                //if (isInputEmpty("u_paymode",null,null,"tab1",1)) return false;
                if (isInputEmpty("u_docdate",null,null,"tab1",0)) return false;
                if (isInputEmpty("u_ownername",null,null,"tab1",0)) return false;
                
            }
         return true;
     }

     function onCFLGPSBPLS(Id) {
         return true;
     }

     function onCFLGetParamsGPSBPLS(Id,params) {
         return params;
     }

     function onTaskBarLoadGPSBPLS() {
     }

     function onElementFocusGPSBPLS(element,column,table,row) {
         return true;
     }

     function onElementKeyDownGPSBPLS(element,event,column,table,row) {
        var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);

        switch (sc_press) {
            case "F4":
            u_OpenPopSearchBPASApp();
            break;
            case "F7":
            if (getVar("formSubmitAction")=="sc") u_approveGPSLGUBPLS();
            break;
            case "F9":
            if (getVar("formSubmitAction")=="sc") u_disapproveGPSLGUBPLS();
            break;
        }
    }

    function onElementValidateGPSBPLS(element,column,table,row) {
     switch (table) {
        case "T1":
        switch (column) {
           case "u_feecode":
           case "u_feedesc":
           if (getTableInput(table, column)!="") {
              if (column=="u_feecode") var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where code='"+getTableInput(table, column)+"'");	
              else var result = page.executeFormattedQuery("select code, name, u_amount from u_lgufees where name like '"+getTableInput(table, column)+"%'");	
              if (result.getAttribute("result")!= "-1") {
                 if (parseInt(result.getAttribute("result"))>0) {
                    setTableInput(table,"u_feecode",result.childNodes.item(0).getAttribute("code"));
                    setTableInput(table,"u_feedesc",result.childNodes.item(0).getAttribute("name"));
                    setTableInputAmount(table,"u_amount",result.childNodes.item(0).getAttribute("u_amount"));
                } else {
                    setTableInput(table,"u_feecode","");
                    setTableInput(table,"u_feedesc","");
                    setTableInputAmount(table,"u_amount",0);
                    page.statusbar.showError("Invalid Fee.");	
                    return false;
                }
            } else {
             setTableInput(table,"u_feecode","");
             setTableInput(table,"u_feedesc","");
             setTableInputAmount(table,"u_amount",0);
             page.statusbar.showError("Error retrieving fee record. Try Again, if problem persists, check the connection.");	
             return false;
         }
     } else {
      setTableInput(table,"u_feecode","");
      setTableInput(table,"u_feedesc","");
      setTableInputAmount(table,"u_amount",0);
  }
  break;
}
break;
default:
switch (column) {
   case "u_amount":
   computeContractorsTax();
   break;
}
}			
return true;
}

function onElementGetValidateParamsGPSBPLS(table,row,column) {
 var params = "";
 return params;
}

function onElementChangingGPSBPLS(element,column,table,row) {
 return true;
}

function onElementChangeGPSBPLS(element,column,table,row) {
 return true;
}

function onElementClickGPSBPLS(element,column,table,row) {
 return true;
}

function onElementCFLGPSBPLS(element) {
 return true;
}

function onElementCFLGetParamsGPSBPLS(Id,params) {
 switch (Id) {
    case "df_u_feecodeT1":
    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code, name from u_lgufees")); 
    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee Code`Fee Description")); 			
    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
    break;
    case "df_u_feedescT1":
    params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name, code from u_lgufees")); 
    params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Fee Description`Fee Code")); 			
    params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`15")); 			
    params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
    break;
}		
return params;
}

function onTableResetRowGPSBPLS(table) {
}

function onTableBeforeInsertRowGPSBPLS(table) {
 switch (table) {
  case "T1": 
  if (getTableInput(table,"u_year") == "-") {
     page.statusbar.showError("Year is required.");
     focusTableInput(table,"u_year");
     return false;
 }
 if (isTableInputEmpty(table,"u_year")) return false;
 if (isTableInputEmpty(table,"u_feecode")) return false;
 if (isTableInputEmpty(table,"u_feedesc")) return false;
              // setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
              break;
          }
          return true;
      }

      function onTableAfterInsertRowGPSBPLS(table,row) {
      	switch (table) {
              case "T1": computeTotalAssessment(); break;

          }
      }

      function onTableBeforeUpdateRowGPSBPLS(table,row) {
      	switch (table) {
              case "T1": 
              if (getTableInput(table,"u_year") == "-") {
                 page.statusbar.showError("Year is required.");
                 focusTableInput(table,"u_year");
                 return false;
             }
             if (isTableInputEmpty(table,"u_year")) return false;
             if (isTableInputEmpty(table,"u_feecode")) return false;
             if (isTableInputEmpty(table,"u_feedesc")) return false;
              // setTableInputDefault(table,"u_year",getTableInput(table,"u_year",row),row);
              break;
          }
          return true;
      }

      function onTableAfterUpdateRowGPSBPLS(table,row) {
      	switch (table) {
              case "T1": computeTotalAssessment(); break;
          }
      }

      function onTableBeforeDeleteRowGPSBPLS(table,row) {
      	return true;
      }

      function onTableDeleteRowGPSBPLS(table,row) {
          switch (table) {
              case "T1": computeTotalAssessment(); break;
          }
      }

      function onTableBeforeSelectRowGPSBPLS(table,row) {
      	return true;
      }

      function onTableSelectRowGPSBPLS(table,row) {
      }

      function u_OpenPopSearchBPASApp(){
      	OpenPopup(1280,550,"./udp.php?&objectcode=u_searchgeobpasapp","GeoData BPAS");
      }

      function computeContractorsTax() {
      	var  annualtaxamount = 0;

      	var grosssales1 = 0, baseperc1 = 100, taxamount1 = 0, taxrate1 = 0, fromamount1 = 0, excessamount1 = 0, compbased = '';
      	grosssales1 = getInputNumeric("u_amount") ;
      	var result1 = page.executeFormattedQuery("select b.u_excessamount,a.u_baseperc, b.u_amount, b.u_rate,b.u_from from u_bpltaxclasses a, u_bpltaxclasstaxes b where b.code=a.code and " + grosssales1 + " >= b.u_from and (" + grosssales1 + " <=b.u_to or b.u_to=0) and a.code = 'G'");
      	if (result1.getAttribute("result1") != "-1") {
      		if (parseInt(result1.getAttribute("result")) > 0) {
      			baseperc1 = parseFloat(result1.childNodes.item(0).getAttribute("u_baseperc"));
      			taxamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_amount"));
      			taxrate1 = parseFloat(result1.childNodes.item(0).getAttribute("u_rate"));
      			excessamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_excessamount"));
      			fromamount1 = parseFloat(result1.childNodes.item(0).getAttribute("u_from"));
      		}
      	} else {
      		page.statusbar.showError("Error retrieving tax classificaton. Try Again, if problem persists, check the connection.");
      		return false;
      	}

      	if (taxamount1 > 0 && excessamount1 == 0 && taxrate1 == 0) {
      		annualtaxamount = taxamount1 * (baseperc1 / 100);
      	} else if (taxamount1 == 0 && excessamount1 == 0 && taxrate1 > 0) {
      		annualtaxamount = taxrate1 * grosssales1 * (baseperc1 / 100);
      	} else if (taxamount1 > 0 && excessamount1 > 0 && taxrate1 > 0) {
      		annualtaxamount = ((((grosssales1 - fromamount1) / excessamount1) * taxrate1) + taxamount1) * (baseperc1 / 100);
      	}
      	if (annualtaxamount < 220)
      		annualtaxamount = 220;

      	var rc = getTableRowCount("T1"),contractorstaxcoderc=0;
      	for (xxx = 1; xxx <=rc; xxx++) {
      		if (isTableRowDeleted("T1",xxx)==false) {
      			if (getTableInput("T1","u_feecode",xxx)==getPrivate("contractorstaxfeecode")) {
      				setTableInputAmount("T1","u_amount",annualtaxamount,xxx);
      				contractorstaxcoderc=xxx;
      			}
      		}
      	}
      	if (contractorstaxcoderc==0) {
      		var data = new Array();
      		data["u_year"] = getInput("u_year");
      		data["u_feecode"] = getPrivate("contractorstaxfeecode");
      		data["u_feedesc"] = getPrivate("contractorstaxfeedesc");
      		data["u_amount"] = formatNumericAmount(annualtaxamount);
      		data["u_surcharge"] = formatNumericAmount(0);
      		data["u_interest"] = formatNumericAmount(0);
      		insertTableRowFromArray("T1",data);
      	}

      	computeTotalAssessment();

      }
      function computeTotalAssessment() {
      	var rc = getTableRowCount("T1"),total=0,totalpenalty = 0 ,totalinterest = 0 ;

      	for (i = 1; i <= rc; i++) {
      		if (isTableRowDeleted("T1",i)==false) {
      			total+= getTableInputNumeric("T1","u_amount",i);
      			totalpenalty+= getTableInputNumeric("T1","u_surcharge",i);
      			totalinterest+= getTableInputNumeric("T1","u_interest",i);

      		}
      	}

      	setInputAmount("u_totalamount",total + totalinterest + totalpenalty);
      }

    function u_approveGPSLGUBPLS() {
        if (isInputEmpty("u_apptype",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_docdate",null,null,"tab1",0)) return false;
        if (isInputEmpty("u_projname",null,null,"tab1",0)) return false;
        if (isInputNegative("u_amount",null,null,"tab1",0)) return false;
    
    if (getTableRowCount("T1")==0) {
        page.statusbar.showError("At least one line of business must be entered.");
        selectTab("tab1",1);
        return false;
    }

    setInput("docstatus","AP");
    formSubmit();
    }

    function u_disapproveGPSLGUBPLS() {
        setInput("docstatus","DA");
        formSubmit('sc');
    }
    function u_reassessmentGPSLGUBPLS() {
        setInput("docstatus","O");
        formSubmit('sc');
    }