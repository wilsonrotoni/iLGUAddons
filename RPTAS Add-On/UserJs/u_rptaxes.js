// page events
page.events.add.submitreturn('onPageSubmitReturnGPSRPTAS');
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
//page.elements.events.add.change('onElementChangeGPSRPTAS');
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
page.tables.events.add.delete('onTableDeleteRowGPSRPTAS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSRPTAS');
page.tables.events.add.select('onTableSelectRowGPSRPTAS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSRPTAS');

function onPageSubmitReturnGPSRPTAS(action,sucess,error) {

 if (sucess) {
   if (action == "sc" && getInput("docstatus"=="C")){
            OpenReportSelect('printer');
    }
 }
    
}

function onPageLoadGPSRPTAS() {
    if (getVar("formSubmitAction")=="a") {
               // u_ajaxloadu_lguposterminalseries("df_u_docseries",getInput("u_terminalid"),'',":","RP");
	}
}

function onPageResizeGPSRPTAS(width,height) {
}

function onPageSubmitGPSRPTAS(action) {
    var rc =  getTableRowCount("T1"),count = 0 ;
    var rc3 =  getTableRowCount("T3");
    if (action=="a" || action=="sc") {
            if (isInputEmpty("u_tin")) return false;
            if (isInputEmpty("u_declaredowner")) return false;
            if (isInputEmpty("u_paymode")) return false;
            if (isInputNegative("u_totaltaxamount")) return false;
    }
    if (action=="a" && getInput("u_partialpay")==1) {
            if (window.confirm("Partial Payment will be posted. Continue?")==false) return false;
    }
     if (action=="a" && rc3 > 0) {
         	for (xxx = 1; xxx <= rc3; xxx++) {
                    if (isTableRowDeleted("T3",xxx)==false) {
                        if (getTableInputNumeric("T3","u_selected",xxx)=="1") {
                            count += count + 1;
                        }
                    }
                }
                if (count == 0){
                    if(confirm("There's an existing uncheck tax credit for this tax payer. Continue?")){
                    }else{
                        return false;
                    }
                }
                    
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
                              
                                case "u_dpamount":
                                    if(isInputNegative(getTableInputNumeric(table,"u_dpamount",row))) return false;
                                    if(getInput("u_partialpay")=="1"){
                                            if(getTableInputNumeric(table,"u_dpamount",row) > getTableInputNumeric(table,"u_linetotal",row)){
                                                page.statusbar.showError("Applied amount must be less than line total");
                                                setTableInput(table,"u_dpamount",getTableInput(table,"u_linetotal",row),row);
                                            }else if(getTableInputNumeric(table,"u_dpamount",row) == getTableInputNumeric(table,"u_linetotal",row)){
                                                getTaxDues();
                                            }else{
                                                setTableInput(table,"u_taxdue",formatNumericAmount(getTableInputNumeric(table,"u_dpamount",row)/2 ),row); 
                                                setTableInput(table,"u_sef",formatNumericAmount(getTableInputNumeric(table,"u_dpamount",row)/2 ),row); 
                                                setTableInput(table,"u_penalty",formatNumericAmount(0),row); 
                                                setTableInput(table,"u_sefpenalty",formatNumericAmount(0),row); 
                                                setTableInput(table,"u_taxdisc",formatNumericAmount(0),row); 
                                                setTableInput(table,"u_sefdisc",formatNumericAmount(0),row); 
                                                setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
                                                setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
                                                computeTaxGPSRPTAS();
                                            }
                                    }else{
                                    page.statusbar.showError("Payments must be partial to modify this amount");
                                    setTableInput(table,"u_dpamount",getTableInput(table,"u_linetotal",row),row); 
                                    }
                                break;
                                case "u_ownername":
                                        setTableInput(table,"u_ownername",getTableInput(table,"u_ownername",row).toUpperCase(),row);
                                break;
                                case "u_taxdue":
                                        setTableInput(table,"u_sef",formatNumericAmount(getTableInput(table,"u_taxdue",row)),row);
                                case "u_sef":
//                                        setTableInput(table,"u_penaltyadj",formatNumericAmount(getTableInput(table,"u_sefpenaltyadj",row)),row);
                                        setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
					setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					setTableInput(table,"u_linetotal",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					setTableInput(table,"u_dpamount",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
                                        computeTaxGPSRPTAS();
					break;
				case "u_penaltyadj":
                                         setTableInput(table,"u_sefpenaltyadj",formatNumericAmount(getTableInput(table,"u_penaltyadj",row)),row);
                                case "u_sefpenaltyadj":
//                                        setTableInput(table,"u_penaltyadj",formatNumericAmount(getTableInput(table,"u_sefpenaltyadj",row)),row);
                                        setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
					setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
                                        setTableInput(table,"u_linetotal",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					setTableInput(table,"u_dpamount",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
                                        computeTaxGPSRPTAS();
					break;
				case "u_taxdiscadj":
					setTableInput(table,"u_sefdiscadj",formatNumericAmount(getTableInput(table,"u_taxdiscadj",row)),row);
				case "u_sefdiscadj":
                                        setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
					setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					setTableInput(table,"u_linetotal",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					setTableInput(table,"u_dpamount",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					computeTaxGPSRPTAS();
					break;
                                case "u_penalty":
					setTableInput(table,"u_sefpenalty",getTableInput(table,"u_penalty",row),row);
				case "u_sefpenalty":
					setTableInput(table,"u_penalty",getTableInput(table,"u_sefpenalty",row),row);
                                        setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
					setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					setTableInput(table,"u_linetotal",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					setTableInput(table,"u_dpamount",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
                                        computeTaxGPSRPTAS();
					break;	
                                case "u_discperc":
					setTableInput(table,"u_taxdisc",formatNumericAmount(parseFloat(getTableInputNumeric(table,"u_discperc",row)/100) * getTableInputNumeric(table,"u_taxdue",row)) ,row);
					setTableInput(table,"u_sefdisc",formatNumericAmount(parseFloat(getTableInputNumeric(table,"u_discperc",row)/100) * getTableInputNumeric(table,"u_sef",row)) ,row);
                                        setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
					setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					setTableInput(table,"u_linetotal",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					setTableInput(table,"u_dpamount",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
                                        computeTaxGPSRPTAS();
                                        break;
                                case "u_assvalue":
					setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
					setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					setTableInput(table,"u_billdate",getInput("u_assdate"),row);
//					setTableInput(table,"u_paymode",'Annually' ,row);
                                        setTableInput(table,"u_linetotal",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
					setTableInput(table,"u_dpamount",formatNumericAmount(getTableInputNumeric(table,"u_taxtotal",row) + getTableInputNumeric(table,"u_seftotal",row)),row);
                                        computeTaxGPSRPTAS();
                                   break;
                               case "u_paymode":
                                 
                                   if(getTableInput(table,"u_assvalue",row)<=0){page.statusbar.showError("Assessed value must be positive"); return false;}
                                   if (getTableInput(table,"u_paymode",row) == "1st Bi-Annually" || getTableInput(table,"u_paymode",row) == "2nd Bi-Annually" ){
                                       alert('Tax and Sef will be divided into two(2)');
                                            disableTableInput(table,"u_taxdue",row);
                                            disableTableInput(table,"u_sef",row);
                                            setTableInput(table,"u_taxdue",getTableInputNumeric(table,"u_taxdue",row) /2,row);
                                            setTableInput(table,"u_sef",getTableInputNumeric(table,"u_sef",row) /2,row);
                                            setTableInput(table,"u_sefpenalty",0,row);
                                            setTableInput(table,"u_penalty",0,row);
                                            setTableInput(table,"u_sefdisc",0,row);
                                            setTableInput(table,"u_taxdisc",0,row);
                                            setTableInput(table,"u_discperc",0,row);
                                            setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
                                            setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					
                                   }
                                   if (getTableInput(table,"u_paymode",row) == "1st Quarter" || getTableInput(table,"u_paymode",row) == "2nd Quarter"  || getTableInput(table,"u_paymode",row) == "3rd Quarter" || getTableInput(table,"u_paymode",row) == "4rth Quarter"  ){
                                       alert('Tax and Sef will be divided into four(4)');
                                            disableTableInput(table,"u_taxdue",row);
                                            disableTableInput(table,"u_sef",row);
                                            setTableInput(table,"u_taxdue",getTableInputNumeric(table,"u_taxdue",row) /4,row);
                                            setTableInput(table,"u_sef",getTableInputNumeric(table,"u_sef",row) /4,row);
                                            setTableInput(table,"u_sefpenalty",0,row);
                                            setTableInput(table,"u_penalty",0,row);
                                            setTableInput(table,"u_sefdisc",0,row);
                                            setTableInput(table,"u_taxdisc",0,row);
                                            setTableInput(table,"u_discperc",0,row);
                                            setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
                                            setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					
                                   }else{
                                         if (getTableInput(table,"u_paymode",row) == "Partial" || getTableInput(table,"u_paymode",row) == "Balance" ){
                                            enableTableInput(table,"u_taxdue",row);
                                            enableTableInput(table,"u_sef",row);
                                            setTableInput(table,"u_sefpenalty",0,row);
                                            setTableInput(table,"u_penalty",0,row);
                                            setTableInput(table,"u_sefdisc",0,row);
                                            setTableInput(table,"u_taxdisc",0,row);
                                            setTableInput(table,"u_discperc",0,row);
                                            setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
                                            setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
					
                                         }else{
                                            disableTableInput(table,"u_taxdue",row);
                                            disableTableInput(table,"u_sef",row);
                                         }
                                            
                                           
                                   }
                                   
                               break;
                               
                                case "u_yrto":
                                   setTableInput(table,"u_noofyrs",0,row);
                                   setTableInput(table,"u_taxdue",0,row);
                                   setTableInput(table,"u_sef",0,row);
                                   setTableInput(table,"u_sefpenalty",0,row);
                                   setTableInput(table,"u_penalty",0,row);
                                   setTableInput(table,"u_sefdisc",0,row);
                                   setTableInput(table,"u_taxdisc",0,row);
                                   setTableInput(table,"u_discperc",0,row);
                                   setTableInput(table,"u_seftotal",0,row);
                                   setTableInput(table,"u_taxtotal",0,row);
                                   
                                    if(getTableInput(table,"u_assvalue",row)<=0) page.statusbar.showError("Assessed value must be positive");
                                    setTableInput(table,"u_noofyrs",(getTableInput(table,"u_yrto",row)-getTableInput(table,"u_yrfr",row))+1 ,row);
                                    setTableInput(table,"u_taxdue",formatNumericAmount((getTableInputNumeric(table,"u_assvalue",row)*.01) * getTableInputNumeric(table,"u_noofyrs",row)) ,row);
                                    setTableInput(table,"u_sef",formatNumericAmount((getTableInputNumeric(table,"u_assvalue",row)*.01) * getTableInputNumeric(table,"u_noofyrs",row)) ,row);
                                    if(getTableInput(table,"u_yrto",row)<getTableInput(table,"u_yrfr",row)){
                                        page.statusbar.showError("Year from must be less than year to.");	
                                        return false;
                                    }else{
                                        if(isInputNegative(getTableInput(table,"u_yrfr",row))) return false;
                                        if(getTableInput(table,"u_yrfr",row)<=getPrivate("year")){
                                            var count =  getPrivate("year")-getTableInput(table,"u_yrfr",row);
                                            var  v_me = 12 - getInput("u_assdate").substr(0,2);
//                                            alert(count);
                                            if(count==1){
                                                setTableInput(table,"u_sefpenalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*(24-v_me))*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                                setTableInput(table,"u_penalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*(24-v_me))*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                            }else if(count==2){
                                                setTableInput(table,"u_sefpenalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*(36-v_me))*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                                setTableInput(table,"u_penalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*(36-v_me))*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                            }else if(count>=3){
                                                setTableInput(table,"u_sefpenalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*36)*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                                setTableInput(table,"u_penalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*36)*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                            }else{
                                               var result = page.executeFormattedQuery("Select u_discperc from u_rpdiscs where code = '"+formatDateToDB(getInput("u_assdate")).substr(5,2)+"'");
                
                                                        if (result.getAttribute("result") != "-1"){
                                                            if(parseInt(result.getAttribute("result"))>0){
                                                              var  discperc = parseFloat(result.childNodes.item(0).getAttribute("u_discperc"));
                                                              setTableInput(table,"u_discperc",formatNumericAmount(discperc),row);
                                                              setTableInput(table,"u_taxdisc",formatNumericAmount((parseFloat(getTableInputNumeric(table,"u_discperc",row))/100) * getTableInputNumeric(table,"u_taxdue",row)) ,row);
                                                              setTableInput(table,"u_sefdisc",formatNumericAmount((parseFloat(getTableInputNumeric(table,"u_discperc",row))/100) * getTableInputNumeric(table,"u_sef",row)) ,row);
                                                            }else{
                                                             page.statusbar.showError("Invalid Discount");
                                                             return false;
                                                            }
                                                        }else{
                                                             page.statusbar.showError("Error retrieving discount. Try again, if problem persist, check the connection");
                                                             return false;
                                                        }
                                                if (getInput("u_assdate").substr(0,2)<=3){
                                                    setTableInput(table,"u_sefpenalty",0.00,row);
                                                    setTableInput(table,"u_penalty",0.00,row);
                                                }else{
                                                    setTableInput(table,"u_sefpenalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*(getInput("u_assdate").substr(0,2)))*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                                    setTableInput(table,"u_penalty",formatNumericAmount(parseFloat(((getTableInputNumeric(table,"u_assvalue",row)*.01)*.02)*(getInput("u_assdate").substr(0,2)))*((getTableInputNumeric(table,"u_yrto",row)-getTableInputNumeric(table,"u_yrfr",row))+1)),row);
                                                }
                                            }
                                        }
                                    }
                                    setTableInput(table,"u_taxtotal",formatNumericAmount(getTableInputNumeric(table,"u_taxdue",row) + getTableInputNumeric(table,"u_penalty",row)  + getTableInputNumeric(table,"u_penaltyadj",row) -  getTableInputNumeric(table,"u_taxdisc",row) - getTableInputNumeric(table, "u_taxdiscadj",row)),row);
                                    setTableInput(table,"u_seftotal",formatNumericAmount(getTableInputNumeric(table,"u_sef",row) + getTableInputNumeric(table,"u_sefpenalty",row)  + getTableInputNumeric(table,"u_sefpenaltyadj",row) -  getTableInputNumeric(table,"u_sefdisc",row) - getTableInputNumeric(table, "u_sefdiscadj",row)),row);
                                    setTableInputDefault(table,"u_kind",getTableInput(table,"u_kind",row),row);
                                    setTableInputDefault(table,"u_pinno",getTableInput(table,"u_pinno",row),row);
                                    setTableInputDefault(table,"u_arpno",getTableInput(table,"u_arpno",row),row);
                                    setTableInputDefault(table,"u_class",getTableInput(table,"u_class",row),row);
                                    setTableInputDefault(table,"u_ownername",getTableInput(table,"u_ownername",row),row);
                                    setTableInputDefault(table,"u_tdno",getTableInput(table,"u_tdno",row),row);
                                    setTableInputDefault(table,"u_assvalue",getTableInput(table,"u_assvalue",row),row);
				    setTableInputDefault(table,"u_barangay",getTableInput(table,"u_barangay",row),row);
                                    break
			}
                        
			break;
		default:
			switch (column) {
                            
                                case "u_cashsalesno":
                                    if (isInputEmpty("u_tin")) return false;
                                    if(getInput("u_withfaas")!="1") {
                                        page.statusbar.showError("Only with faas record");
                                        return false;
                                    }
                                    getCashSales();
                                break;
                                case "u_paidamount":
                                    if(getInputNumeric("u_paidamount")<getInputNumeric("u_totaltaxamount")){
                                        page.statusbar.showError("Paid amount is less than total amount.");
                                        setInput("u_changeamount",0);
                                        setInput("u_paidamount",0);
                                        return false;
                                    }else{
                                        setInput("u_changeamount",formatNumericAmount(getInputNumeric("u_paidamount")-getInputNumeric("u_totaltaxamount")));
                                    }
                                break;
                                case "u_declaredowner":
                                     setTableInput(table,"u_declaredowner",getTableInput(table,"u_declaredowner",row).toUpperCase(),row);
                                break;
				case "u_assdate":
					if (getInput(column)!="") {
						setInput("u_year",getInput("u_assdate").substr(6,4));
					} else {
						setInput("u_year",0);
					}
					if (getInput("u_year")!=getPrivate("year")) {
						//disableInput("u_advancepay");
//						if (isInputChecked("u_advancepay")) {
//							uncheckedInput("u_advancepay");
//							advancePayGPSRPTAS();
//						}	
					} else enableInput("u_advancepay");
					setInput("u_yearfrom",0);
					setInput("u_yearto",0);
                                        getTaxDues();
                                        getSemiQuarterly();
                                        updateBilldate();
                                        computeTaxGPSRPTAS();
					break;
//				case "u_year":
//					getTaxDues();
//					break;
				case "u_pin":
				case "u_tdno":
                                    if (isInputEmpty("u_assdate")) return false;
					if (getInput(column)!="") {
						var result = page.executeFormattedQuery("select u_ownertin, docno, u_pin, type, u_ownername from (select u_ownertin, docno,u_pin, 'Land' as type, u_ownername from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"' union all select u_ownertin, docno,u_pin, 'Building' as type, u_ownername from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"' union all select u_ownertin, docno,u_pin, 'Machinery' as type, u_ownername from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"') as x");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tin",result.childNodes.item(0).getAttribute("u_ownertin"));
                                                                 for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                        setInput("u_declaredowner",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                        setInput("u_paidby",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                 }                                                         
                                                                                var data = new Array();
                                                                                var result2 = page.executeFormattedQuery("select u_billno from(select C.DOCNO AS u_BILLNO from u_rptaxes a inner join u_lgubills c on a.docno = c.u_appno and a.company = c.company and a.branch = c.branch inner join u_rptaxarps b on a.docid = b.docid and a.company = b.company and a.branch = b.branch and c.u_docdate = b.u_billdate where a.u_paymode != 'A' and c.docstatus in('O') and a.company = '"+getGlobal("company")+"' and a.branch = '"+getGlobal("branch")+"' and a.u_tin = '"+getInput("u_tin")+"' group by b.u_billdate) as x");
                                                                                if (result2.getAttribute("result")!= "-1") {
                                                                                    if (parseInt(result2.getAttribute("result"))>0) {
                                                                                        if(confirm('This taxpayer had an existing Quarterly or Semi Annually unpaid bill/s. would you like to retrieve the payment form?')){
                                                                                             clearTable("T1",true);
                                                                                              clearTable("T2",true);
                                                                                           for (xxx = 0; xxx < result2.childNodes.length; xxx++) {
                                                                                               
                                                                                                 var result3 = page.executeFormattedQuery("select u_linetotal,u_dpamount,u_partialpay,u_barangay,u_paymodebill,u_custname,u_class,u_arpno,u_noofyrs,u_yrfr,u_yrto,u_taxdue,u_penalty,u_sef,u_sefpenalty,u_discperc,u_taxdisc,u_sefdisc,u_taxtotal,u_seftotal,u_tdno,u_selected,u_pinno,u_kind,u_assvalue,u_penaltyadj,u_sefpenaltyadj,u_taxdiscadj,u_sefdiscadj,u_billdate,u_paymode,u_ownername,billno from(SELECT C.U_PARTIALPAY,B.U_LINETOTAL,B.U_DPAMOUNT,B.U_BARANGAY,A.U_PAYMODE as u_paymodebill,A.U_CUSTNAME,B.U_CLASS,B.U_ARPNO,B.U_NOOFYRS,B.U_YRFR,B.U_YRTO,B.U_TAXDUE,B.U_PENALTY,B.U_SEF,B.U_SEFPENALTY,B.U_DISCPERC,B.U_TAXDISC,B.U_SEFDISC,B.U_TAXTOTAL,B.U_SEFTOTAL,B.U_TDNO,B.U_SELECTED,B.U_PINNO,B.U_KIND,B.U_ASSVALUE,B.U_PENALTYADJ,B.U_SEFPENALTYADJ,B.U_TAXDISCADJ,B.U_SEFDISCADJ,B.U_BILLDATE,B.U_PAYMODE,B.U_OWNERNAME,A.DOCNO AS BILLNO FROM U_LGUBILLS A INNER JOIN U_RPTAXES C ON A.U_APPNO = C.DOCNO AND A.COMPANY = C.COMPANY AND A.BRANCH = C.BRANCH INNER JOIN U_RPTAXARPS B ON C.DOCID = B.DOCID AND A.U_DOCDATE = B.U_BILLDATE AND C.COMPANY = B.COMPANY AND C.BRANCH = B.BRANCH WHERE A.DOCNO = '"+result2.childNodes.item(xxx).getAttribute("u_billno")+"' AND A.COMPANY = '"+getGlobal("company")+"' AND A.BRANCH = '"+getGlobal("branch")+"') as x");

                                                                                                   if (parseInt(result3.getAttribute("result"))>0) {
                                                                                                      setInput("u_declaredowner",result3.childNodes.item(0).getAttribute("u_custname"));
                                                                                                      setInput("u_paymode",result3.childNodes.item(0).getAttribute("u_paymodebill"));
                                                                                                      setInput("u_partialpay",result3.childNodes.item(0).getAttribute("u_partialpay"));

                                                                                                      for (xxx1 = 0; xxx1 < result3.childNodes.length; xxx1++) {

                                                                                                            data["u_selected"] = 1;
                                                                                                            data["u_kind"] = result3.childNodes.item(xxx1).getAttribute("u_kind");
                                                                                                            data["u_pinno"] = result3.childNodes.item(xxx1).getAttribute("u_pinno");
                                                                                                            data["u_arpno"] = result3.childNodes.item(xxx1).getAttribute("u_arpno");
                                                                                                            data["u_class"] = result3.childNodes.item(xxx1).getAttribute("u_class");
                                                                                                            data["u_barangay"] = result3.childNodes.item(xxx1).getAttribute("u_barangay");
                                                                                                            data["u_ownername"] = result3.childNodes.item(xxx1).getAttribute("u_ownername");
                                                                                                            data["u_tdno"] = result3.childNodes.item(xxx1).getAttribute("u_tdno");
                                                                                                            data["u_assvalue"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_assvalue"));
                                                                                                            data["u_noofyrs"] = result3.childNodes.item(xxx1).getAttribute("u_noofyrs");
                                                                                                            data["u_yrfr"] = result3.childNodes.item(xxx1).getAttribute("u_yrfr");
                                                                                                            data["u_yrto"] = result3.childNodes.item(xxx1).getAttribute("u_yrto");
                                                                                                            data["u_taxdue"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_taxdue"));
                                                                                                            data["u_penalty"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_penalty"));
                                                                                                            data["u_penaltyadj"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_penaltyadj"));
                                                                                                            data["u_sef"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_sef"));
                                                                                                            data["u_sefpenalty"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_sefpenalty"));
                                                                                                            data["u_sefpenaltyadj"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_sefpenaltyadj"));
                                                                                                            data["u_discperc"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_discperc"));
                                                                                                            data["u_taxdisc"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_taxdisc"));
                                                                                                            data["u_taxdiscadj"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_taxdiscadj"));
                                                                                                            data["u_sefdisc"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_sefdisc"));
                                                                                                            data["u_sefdiscadj"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_sefdiscadj"));
                                                                                                            data["u_taxtotal"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_taxtotal"));
                                                                                                            data["u_seftotal"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_seftotal"));
                                                                                                            data["u_linetotal"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_linetotal"));
                                                                                                            data["u_dpamount"] = formatNumericAmount(result3.childNodes.item(xxx1).getAttribute("u_dpamount"));
                                                                                                            
                                                                                                            if(formatDateToHttp(result3.childNodes.item(xxx1).getAttribute("u_billdate"))>getInput("u_assdate")){
                                                                                                            data["u_billdate"] = formatDateToHttp(result3.childNodes.item(xxx1).getAttribute("u_billdate"));
                                                                                                            }else{
                                                                                                            data["u_billdate"] = getInput("u_assdate");
                                                                                                            }
                                                                                                            data["u_paymode"] = result3.childNodes.item(xxx1).getAttribute("u_paymode");
                                                                                                            data["u_billno"] = result3.childNodes.item(xxx1).getAttribute("billno");
                                                                                                            insertTableRowFromArray("T1",data);
                                                                                                     }
                                                                                                    }
                                                                                            }
                                                                                            computeTaxGPSRPTAS();
                                                                                            disableInput("u_assdate");
                                                                                            disableInput("u_advancepay");
                                                                                            disableInput("u_paymode");
                                                                                            break;
                                                                                        }
                                                                                             
                                                                                    }
                                                                                    
                                                                                }
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
                                                                getTaxDues();
                                                                getSemiQuarterly();
							} else {
								setInputAmount("u_tin","");
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
								getTaxDues();
                                                                getSemiQuarterly();
								page.statusbar.showError("Invalid TD No.");	
								return false;
							}
						} else {
							setInputAmount("u_tin","");
							setInput("u_yearfrom",0);
							setInput("u_yearto",0);
							getTaxDues();
                                                        getSemiQuarterly();
							page.statusbar.showError("Error retrieving faas record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                                 
					}
                               
					break;
				case "u_tin":
//				case "u_pin":
                                        if (isInputEmpty("u_assdate")) return false;
                                            var result = page.executeFormattedQuery("select u_tdno,u_ownertin, docno, u_pin, type, u_ownername from (select u_tdno,u_ownertin, docno,u_pin, 'Land' as type, u_ownername from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getInput("u_tin")+"' union all select u_tdno,u_ownertin, docno,u_pin, 'Building' as type, u_ownername from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getInput("u_tin")+"' union all select u_tdno,u_ownertin, docno,u_pin, 'Machinery' as type, u_ownername from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_ownertin='"+getInput("u_tin")+"') as x");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_tdno",result.childNodes.item(0).getAttribute("u_tdno"));
                                                                 for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                                                        setInput("u_declaredowner",result.childNodes.item(0).getAttribute("u_ownername"));
                                                                 }
                                                                 } else {
								setInputAmount("u_tin","");
								setInput("u_yearfrom",0);
								setInput("u_yearto",0);
								getTaxDues();
                                                                getSemiQuarterly();
								page.statusbar.showError("Invalid TIn No.");	
								return false;
							}
						} else {
							setInputAmount("u_tin","");
							setInput("u_yearfrom",0);
							setInput("u_yearto",0);
							getTaxDues();
                                                        getSemiQuarterly();
							page.statusbar.showError("Error retrieving faas record. Try Again, if problem persists, check the connection.");	
							return false;
						}
                                                
					var result = page.executeFormattedSearch("select docno from u_rptaxes where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and docstatus in ('D') and docno<>'"+getInput("docno")+"'");
					if (result!="") {
						alert('An existing payment form still in draft for this Tax Payer. System will retrieve the payment form.');
						setKey("keys",result);
						formEdit();
						return true;
					}
                                        
//                                        var result = page.executeFormattedSearch("select docno from u_rptaxes where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and docstatus in ('D') and docno<>'"+getInput("docno")+"'");
                                       
                                             
//					if (result2!="") {
//						
//						setKey("keys",result2);
//						
//					}

					setInput("u_yearfrom",0);
					setInput("u_yearto",0);
					getTaxDues();
                                        getSemiQuarterly();
					break;
				case "u_yearto":
					if (getInput("u_yearto")!=getPrivate("year")) {
						disableInput("u_advancepay");
						if (isInputChecked("u_advancepay")) {
							uncheckedInput("u_advancepay");
							advancePayGPSRPTAS();
						}	
					} else {
						if (getInput("u_year")!=getPrivate("year")) {
							disableInput("u_advancepay");
							if (isInputChecked("u_advancepay")) {
								uncheckedInput("u_advancepay");
								advancePayGPSRPTAS();
							}	
						} else enableInput("u_advancepay");
					}
					getTaxDues();
                                        getSemiQuarterly();
					break;
				case "u_rate":
					computeTaxGPSRPTAS();
					break;
				case "u_idlelandrate":
					if (isInputChecked("u_idleland")) setInputAmount("u_taxidleland",utils.round(getInputNumeric("u_assvalue")*(getInputNumeric("u_idlelandrate")/100),2));
					else setInputAmount("u_taxidleland",0);
					computeTaxdueGPSRPTAS();
					break;
				case "u_discamount":
				case "u_penalty":
					computeTaxdueGPSRPTAS();
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
            default:
            case "u_declaredowner":
                setTableInput("T1","u_ownername",getInput("u_declaredowner").toUpperCase(),row);
            break;
                        }
	return true;
}

function onElementClickGPSRPTAS(element,column,table,row) {
	switch (table) {
		case "T4":
                    switch(column) {
                            case "u_selected":
                            break;
                    }
                computeTaxGPSRPTAS();
                break;
		case "T3":
                    switch(column) {
                            case "u_selected":
                                var tdno =getTableInput("T3","u_tdno",row);
                                var year =getTableInput("T3","u_year",row);
                                var selected =getTableInput("T3","u_selected",row);
                                var ctr = 0;
                                 
                                var rc =  getTableRowCount("T1");
                                for (xxx = 1; xxx <= rc; xxx++) {
                                        if (isTableRowDeleted("T1",xxx)==false) {
                                       
                                                if (getTableInput("T1","u_tdno",xxx)== tdno && getTableInput("T1","u_yrfr",xxx) >= year && getTableInput("T1","u_yrto",xxx) <= year) {
                                                       // setTableInput("T1","u_selected",selected,xxx);
                                                       ctr = 1;
                                                     
                                                }
                                        }
                                }
                               
                                if (ctr == 0){
                                    setStatusMsg("Can't find same year in tax due",4000,1);
                                    uncheckedTableInput(table,"u_selected",row);
                                    return false;
                                }
                            break;
                    }
                computeTaxGPSRPTAS();
                break;
		case "T1":
			if (row==0) {
				if (isTableInputChecked(table,column)) {
					checkedTableInput(table,column,-1);
				} else {
					uncheckedTableInput(table,column,-1);
				}
			} else {
				if (getTableInputNumeric(table,"u_yrfr",row)==(getInputNumeric("u_year")+1) && getTableInput("T1","u_selected",row)=="0") {
				} else {
					var pin=getTableInput("T1","u_pinno",row);
					var selected=getTableInput("T1","u_selected",row);
					var rc =  getTableRowCount("T1");
					for (xxx = 1; xxx <= rc; xxx++) {
						if (isTableRowDeleted("T1",xxx)==false) {
							if (getTableInput("T1","u_pinno",xxx)==pin ) {
								setTableInput("T1","u_selected",selected,xxx);
							}
						}
					}
				}
                                //if i uncheck yung tax due i uncheck din sa tax credit yung the same td
                                if(getTableInput("T1","u_selected",row)=="0"){
                                   
                                        var tdno=getTableInput("T1","u_tdno",row);
					var selected=getTableInput("T1","u_selected",row);
					var rc =  getTableRowCount("T3");
					for (xxx = 1; xxx <= rc; xxx++) {
						if (isTableRowDeleted("T3",xxx)==false) {
							if (getTableInput("T3","u_tdno",xxx)==tdno ) {
								setTableInput("T3","u_selected",selected,xxx);
							}
						}
					}
                                }
			}
			computeTaxGPSRPTAS();
			break;
		default:
			switch(column) {
				case "iscurdate":
                                    
                                    if(isInputChecked("iscurdate")){
                                         setInput("u_assdate",getInput("u_curdate"));
                                    }else{
                                         setInput("u_assdate","");
                                    }
                                   
                                    
                                break;
				case "u_withfaas":
                                   if (isInputEmpty("u_assdate")) return false;
                                        setInput("u_nopenaltydisc",0);
					if(isInputChecked("u_withfaas")){
                                            clearTable("T1",true);
                                            clearTable("T2",true);
                                            setInput("u_tin","");
                                            setInput("u_tdno","");
                                            setInput("u_declaredowner","");
                                            disableInput("u_declaredowner");
                                            enableInput("u_tin"); 
                                            enableInput("u_tdno");
                                            
                                        }else{
                                            clearTable("T1",true);
                                            clearTable("T2",true);
                                            setInput("u_tin","Cash");
                                            setInput("u_tdno","Cash Sales");
                                            setInput("u_declaredowner","");
                                            enableInput("u_declaredowner");
                                            disableInput("u_tdno");
                                            disableInput("u_tin");
                                        }
					break;
				case "u_advancepay":
					advancePayGPSRPTAS();
					break;
                                case "u_nopenaltydisc":
                                        if(isInputChecked("u_nopenaltydisc")){
                                            noPenaltyDisc();
                                            computeTaxGPSRPTAS();
//                                        if(isInputChecked("u_withfaas")){
//                                            setStatusMsg("This is only for manual payment",4000,1);
//                                            return false;
//                                        }else{
//                                            noPenaltyDisc();
//                                            computeTaxGPSRPTAS();
//                                        }
                                        }
					
					break;
                                case "u_nodiscount":
                                        if(isInputChecked("u_nodiscount")){
                                            noDiscount();
                                            computeTaxGPSRPTAS();
                                        }
					
					break;
                                case "u_nopenalty":
                                        if(isInputChecked("u_nopenalty")){
                                            noPenalty();
                                            computeTaxGPSRPTAS();
                                        }
					
					break;
                                case "u_yearbreak":
                                        if(isInputChecked("u_yearbreak")){
                                     
                                            YearBreak();
                                            computeTaxGPSRPTAS();
                                        
                                        }else{
                                            getTaxDues();
                                        }
					
					break;
				case "u_idleland":
					if (isInputChecked(column)) setInputAmount("u_taxidleland",utils.round(getInputNumeric("u_assvalue")*(getInputNumeric("u_idlelandrate")/100),2));
					else setInputAmount("u_taxidleland",0);
					computeTaxGPSRPTAS();
					break;
                                 case "u_paymode":  
                                     if(getInput("u_paymode")=="A"){ getTaxDues(); computeTaxGPSRPTAS();
                                     }else { getSemiQuarterly();}
                                        
                                       
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
		case "df_u_arpno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_pin, type, u_ownername from (select docno,u_pin, 'Land' as type, u_ownername from u_rpfaas1 union all select docno,u_pin, 'Building' as type, u_ownername from u_rpfaas2 union all select docno,u_pin, 'Machinery' as type, u_ownername from u_rpfaas3) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Arp No.`PIN`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`22`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("```")); 			
			break;
		case "df_u_tdno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_tdno, docno, u_pin, type, u_ownername from (select u_tdno, docno,u_pin, 'Land' as type, u_ownername from u_rpfaas1 limit 10000 union all select u_tdno, docno,u_pin, 'Building' as type, u_ownername from u_rpfaas2 limit 10000 union all select u_tdno, docno,u_pin, 'Machinery' as type, u_ownername from u_rpfaas3) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TD No.`Arp No.`PIN`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`10`19`8`32")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
		case "df_u_tin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_ownertin,u_varpno, u_ownername,u_pin from (select u_varpno,u_ownertin, u_ownername,u_pin from u_rpfaas1 limit 10000 union all select u_varpno,u_ownertin, u_ownername,u_pin from u_rpfaas2 limit 10000 union all select u_varpno,u_ownertin, u_ownername,u_pin from u_rpfaas3) as x")); // group by u_ownertin
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("TIN`Arp No`Declared Owner`PIN")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("````")); 			
			break;
		case "df_u_cashsalesno":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_ornumber,u_declaredowner, u_paidby,u_paidamount from u_rptaxes where docstatus = 'C' and u_tdno='Cash Sales' and u_tin = 'Cash' and u_connecttotd = 0 ")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Reference No`OR Number`Declared Owner`Paid By,Amount Paid")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`15`50`50`15")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`````")); 			
				break;
		case "df_u_pin":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select u_pin, type, u_ownername from (select u_pin, 'Land' as type, u_ownername from u_rpfaas1 limit 10000 union all select u_pin, 'Building' as type, u_ownername from u_rpfaas2 limit 10000 union all select u_pin, 'Machinery' as type, u_ownername from u_rpfaas3) as x")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("PIN`Type`Declared Owner")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("22`15`35")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
	}		
	return params;
}

function onTableResetRowGPSRPTAS(table) {
}

function onTableBeforeInsertRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1":
                    if (isInputEmpty("u_assdate")) return false;
                    if (isInputChecked("u_withfaas")) {
                        page.statusbar.showError("Please uncheck with faas first before insert/update new item");
                        return false;
                    }else{
                            if (isTableInputEmpty(table,"u_kind")) return false;
			    if (isTableInputEmpty(table,"u_barangay")) return false;
                            if (isTableInputEmpty(table,"u_ownername")) return false;
                            if (isTableInputEmpty(table,"u_paymode")) return false;
                           // if (isTableInputEmpty(table,"u_tdno")) return false;
                            if (isTableInputEmpty(table,"u_arpno")) return false;
                            if (isTableInputEmpty(table,"u_class")) return false;
                            if (isTableInputNegative(table,"u_assvalue")) return false;
                            if (isTableInputNegative(table,"u_taxtotal")) return false;
                            if (isTableInputNegative(table,"u_seftotal")) return false;
                            setTableInputDefault(table,"u_kind",getTableInput(table,"u_kind",row),row);
                            setTableInputDefault(table,"u_pinno",getTableInput(table,"u_pinno",row),row);
                            setTableInputDefault(table,"u_arpno",getTableInput(table,"u_arpno",row),row);
                            setTableInputDefault(table,"u_class",getTableInput(table,"u_class",row),row);
                            setTableInputDefault(table,"u_ownername",getTableInput(table,"u_ownername",row),row);
                            setTableInputDefault(table,"u_tdno",getTableInput(table,"u_tdno",row),row);
                            setTableInputDefault(table,"u_assvalue",getTableInput(table,"u_assvalue",row),row);
                            setTableInputDefault(table,"u_barangay",getTableInput(table,"u_barangay",row),row);
                    }
                      
            }
	return true;
}

function onTableAfterInsertRowGPSRPTAS(table,row) {
    switch (table) {
        case "T1":
            computeTaxGPSRPTAS();
            updateBilldate();
            break;
    }
    return true;
    
     
}

function onTableBeforeUpdateRowGPSRPTAS(table,row) {
    	switch (table) {
		case "T1": 
                     if (isInputChecked("u_withfaas")) {
                         page.statusbar.showError("Please uncheck with faas first before insert/update new item");
                            return false;
                        }else{
			if (isTableInputEmpty(table,"u_kind")) return false;
			if (isTableInputEmpty(table,"u_ownername")) return false;
			if (isTableInputEmpty(table,"u_barangay")) return false;
                        if (isTableInputEmpty(table,"u_paymode")) return false;
			//if (isTableInputEmpty(table,"u_tdno")) return false;
			if (isTableInputEmpty(table,"u_arpno")) return false;
			if (isTableInputNegative(table,"u_assvalue")) return false;
                        if (isTableInputNegative(table,"u_taxtotal")) return false;
                        if (isTableInputNegative(table,"u_seftotal")) return false;
                        }
            }
	return true;
}

function onTableAfterUpdateRowGPSRPTAS(table,row) {
     switch (table) {
        case "T1":
            computeTaxGPSRPTAS();
            updateBilldate();
            break;
    }
    return true;
}

function onTableBeforeDeleteRowGPSRPTAS(table,row) {
	return true;
}

function onTableDeleteRowGPSRPTAS(table,row) {
     switch (table) {
        case "T1":
            computeTaxGPSRPTAS();
            updateBilldate();
            break;
    }
    return true;
}

function onTableBeforeEditRowGPSRPTAS(table,row) {
	switch (table) {
		case "T1":
                    if(getInput("u_withfaas")==1){
                       openupdpays();
                       return false;
                    }
			break;
	}
	return true;
}

function onTableBeforeSelectRowGPSRPTAS(table,row) {
	return true;
}

function onTableSelectRowGPSRPTAS(table,row) {
	var params = Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			break;
	}
	return params;
}
function getCashSales() {
    var data = new Array(), totalsef=0, totaltax=0,totaltax2=0, totalpenalty=0,totalsefpenalty=0, sef=0, tax=0, penalty=0, sefpenalty=0, noofyrs=0, noofyrstopay=0, yearto=0, taxdisc=0, sefdisc=0 , totalsefdisc=0, totaltaxdisc=0;
      
    
        var result = page.executeFormattedQuery("Select * from u_rptaxes where  docstatus = 'C' and u_tin = 'Cash' and u_connecttotd = '0' and docno = '"+getInput("u_cashsalesno")+"' and company ='"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'");	
            if (parseInt(result.getAttribute("result"))>0) {
                for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                    clearTable("T1",true);
                    clearTable("T2",true);
                    clearTable("T3",true);
                    setInput("u_assdate",formatDateToHttp(result.childNodes.item(xxx).getAttribute("u_assdate")));
                    setInput("u_yearfrom",result.childNodes.item(xxx).getAttribute("u_yearfrom"));
                    setInput("u_yearto",result.childNodes.item(xxx).getAttribute("u_yearto"));
                    setInput("u_ornumber","C-" + result.childNodes.item(xxx).getAttribute("u_ornumber"));
                    setInput("u_ordate",formatDateToHttp(result.childNodes.item(xxx).getAttribute("u_ordate")));
                    setInput("u_paidby",result.childNodes.item(xxx).getAttribute("u_paidby"));
                    setInput("u_paidamount",result.childNodes.item(xxx).getAttribute("u_paidamount"));
                    setInput("u_changeamount",result.childNodes.item(xxx).getAttribute("u_changeamount"));
                    setInput("u_q1totaltax",result.childNodes.item(xxx).getAttribute("u_q1totaltax"));
                    setInput("u_q2totaltax",result.childNodes.item(xxx).getAttribute("u_q2totaltax"));
                    setInput("u_q3totaltax",result.childNodes.item(xxx).getAttribute("u_q3totaltax"));
                    setInput("u_q4totaltax",result.childNodes.item(xxx).getAttribute("u_q4totaltax"));
                    setInput("u_s1totaltax",result.childNodes.item(xxx).getAttribute("u_s1totaltax"));
                    setInput("u_s2totaltax",result.childNodes.item(xxx).getAttribute("u_s2totaltax"));
                    setInput("u_tax",result.childNodes.item(xxx).getAttribute("u_tax"));
                    setInput("u_seftax",result.childNodes.item(xxx).getAttribute("u_seftax"));
                    setInput("u_discamount",result.childNodes.item(xxx).getAttribute("u_discamount"));
                    setInput("u_sefdiscamount",result.childNodes.item(xxx).getAttribute("u_sefdiscamount"));
                    setInput("u_penalty",result.childNodes.item(xxx).getAttribute("u_penalty"));
                    setInput("u_sefpenalty",result.childNodes.item(xxx).getAttribute("u_sefpenalty"));
                    setInput("u_totaltaxamount",result.childNodes.item(xxx).getAttribute("u_totaltaxamount"));
                    disableInput("u_partialpay");
                    disableInput("u_paymode");
                    disableInput("u_nopenaltydisc");
                    disableInput("u_yearbreak");
                    disableInput("u_withfaas");
                    var result1 = page.executeFormattedQuery("select u_ownertin, docno, u_pin, type, u_ownername from (select u_ownertin, docno,u_pin, 'Land' as type, u_ownername from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"' and u_cancelled != 1 union all select u_ownertin, docno,u_pin, 'Building' as type, u_ownername from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+"' union all select u_ownertin, docno,u_pin, 'Machinery' as type, u_ownername from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tdno='"+getInput("u_tdno")+" and u_cancelled != 1') as x");	
			if (parseInt(result1.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result1.childNodes.length; xxx++) {
                                    setInput("arpno",result1.childNodes.item(xxx).getAttribute("docno"));
                                    //alert(getInput("arpno"));
                            }
                        }
                     var result2 = page.executeFormattedQuery("Select * from u_rptaxarps where docid = '"+result.childNodes.item(xxx).getAttribute("docid")+"' and company ='"+getGlobal("company")+"' and branch = '"+getGlobal("branch")+"'");	
                        if (parseInt(result2.getAttribute("result"))>0) {
                            for (xxx = 0; xxx < result2.childNodes.length; xxx++) {
                                    
                            }
                        }
                    
                }

            }                	

	
    
        computeTaxGPSRPTAS();
}
function getTaxDues() {
        
        showAjaxProcess();
   
	var data = new Array(), totalsef=0, totaltax=0,totaltax2=0, totalpenalty=0,totalsefpenalty=0, sef=0, tax=0, penalty=0, sefpenalty=0, noofyrs=0, noofyrstopay=0, yearto=0, taxdisc=0, sefdisc=0 , totalsefdisc=0, totaltaxdisc=0;
	setInput("u_paymode",getInput("u_paymode"));
	
        if(getPrivate("dataentry")=='1'){
            clearTable("T1",true);
            clearTable("T2",true);
            clearTable("T3",true);
            enableInput("u_paymode");
        }
        if(getInput("u_withfaas")==1){
            clearTable("T1",true);
            clearTable("T2",true);
            clearTable("T3",true);
            enableInput("u_paymode");
        }
	setInput("u_advancepay",0);
	setInput("u_partialpay",0);
	if (getInputNumeric("u_year")>0 && getInput("u_tin")!="") {
            if(getInput("u_paymode")=="A"){
   
                var discperc = 0 ;
                //if(getPrivate("year") == getInput("u_assdate").substr(6,4)){
                     var result = page.executeFormattedQuery("Select u_discperc from u_rpdiscs where code = '"+formatDateToDB(getInput("u_assdate")).substr(5,2)+"'");
                
                        if (result.getAttribute("result") != "-1"){
                            if(parseInt(result.getAttribute("result"))>0){
                                discperc = parseFloat(result.childNodes.item(0).getAttribute("u_discperc"));
                                
                            }else{
                             page.statusbar.showError("Invalid Discount");
                             return false;
                            }
                        }else{
                             page.statusbar.showError("Error retrieving discount. Try again, if problem persist, check the connection");
                             return false;
                        }
                //}
                
                 
		var result = page.executeFormattedQuery("select u_class,u_arpno,u_orrefno,u_yrfr,u_yrto,u_tdno,u_taxdue,u_penalty,u_sef,u_sefpenalty,u_tin,code from u_taxbalance where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and u_status='O' ");
                var assvalue,pin,property,barangay,ownername;
                var taxdue=0,sef=0,penalty=0,sefpenalty=0,linetotal,dpamount,totaltax,totalsef;
                if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
                                 var result1 = page.executeFormattedQuery("Select u_assvalue,u_pin,'LAND' as property,u_barangay,u_ownername from u_rpfaas1 where docno = '"+result.childNodes.item(xxx).getAttribute("u_arpno")+"' and company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' union all Select u_assvalue,u_pin,'BUILDING' as property,u_barangay,u_ownername from u_rpfaas2 where docno = '"+result.childNodes.item(xxx).getAttribute("u_arpno")+"' and company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' union all Select u_assvalue,u_pin,'MACHINERY' as property,u_barangay,u_ownername from u_rpfaas3 where docno = '"+result.childNodes.item(xxx).getAttribute("u_arpno")+"' and company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"'");
                                        if (result1.getAttribute("result") != "-1"){
                                            if(parseInt(result1.getAttribute("result"))>0){
                                                assvalue = formatNumericAmount(result1.childNodes.item(0).getAttribute("u_assvalue"));
                                                pin = result1.childNodes.item(0).getAttribute("u_pin");
                                                property = result1.childNodes.item(0).getAttribute("property");
                                                barangay = result1.childNodes.item(0).getAttribute("u_barangay");
                                                ownername = result1.childNodes.item(0).getAttribute("u_ownername");
                                            }
                                        }
                                taxdue = parseFloat(result.childNodes.item(xxx).getAttribute("u_taxdue"));
                                sef = parseFloat(result.childNodes.item(xxx).getAttribute("u_sef"));
                                penalty = parseFloat(result.childNodes.item(xxx).getAttribute("u_penalty"));
                                sefpenalty = parseFloat(result.childNodes.item(xxx).getAttribute("u_sefpenalty"));
                             
                                
                                data["u_selected"] = 0;
                                data["u_isbalance"] = 1;
				data["u_yrfr"] = result.childNodes.item(xxx).getAttribute("u_yrfr");
				data["u_yrto"] = result.childNodes.item(xxx).getAttribute("u_yrto");
				data["u_tdno"] = result.childNodes.item(xxx).getAttribute("u_tdno");
				data["u_class"] = result.childNodes.item(xxx).getAttribute("u_class");
                                data["u_barangay"] = barangay;
                                data["u_ownername"] = ownername;
                                data["u_pinno"] = pin;
                                data["u_kind"] = property;
                                data["u_assvalue"] = assvalue;
                                data["u_paymode"] = "Balance";
                                data["u_noofyrs"] = (result.childNodes.item(xxx).getAttribute("u_yrto") - result.childNodes.item(xxx).getAttribute("u_yrfr")) + 1;
                                data["u_billdate"] = getInput("u_assdate");
				data["u_tin"] = result.childNodes.item(xxx).getAttribute("u_tin");
				data["u_balrefno"] = result.childNodes.item(xxx).getAttribute("code");
				data["u_taxdue"] = formatNumericAmount(taxdue);
				data["u_penalty"] = formatNumericAmount(penalty);
				data["u_sef"] = formatNumericAmount(sef);
				data["u_sefpenalty"] = formatNumericAmount(sefpenalty);
				data["u_taxtotal"] = formatNumericAmount(taxdue+penalty);
				data["u_seftotal"] = formatNumericAmount(sef+sefpenalty);
				data["u_linetotal"] = formatNumericAmount(parseFloat(data["u_taxtotal"])+parseFloat(data["u_seftotal"]));
				data["u_dpamount"] = formatNumericAmount(parseFloat(data["u_taxtotal"])+parseFloat(data["u_seftotal"]));
				data["u_arpno"] = result.childNodes.item(xxx).getAttribute("u_arpno");
				insertTableRowFromArray("T1",data);
			}
                    }
                var filterExp1 = "";
		if (getInput("u_tin")!="") filterExp1 += " and u_ownertin='"+getInput("u_tin")+"'";
		if (getInput("u_pin")!="") filterExp1 += " and u_pin='"+getInput("u_pin")+"'";
                var result1 = page.executeFormattedQuery("SELECT docno FROM U_RPFAAS1 WHERE COMPANY = 'LGU' AND BRANCH = 'MAIN'  "+filterExp1+" UNION ALL SELECT DOCNO FROM U_RPFAAS2 WHERE  COMPANY = 'LGU' AND BRANCH = 'MAIN'  "+filterExp1+"  UNION ALL SELECT DOCNO FROM U_RPFAAS3 WHERE  COMPANY = 'LGU' AND BRANCH = 'MAIN'  "+filterExp1+" ");

                if (parseInt(result1.getAttribute("result"))>0) {
                    for (xxx2 = 0; xxx2 < result1.childNodes.length; xxx2++) {
                        
                        var result = page.executeFormattedQuery("call sp_lgurptaxdue('lgu','main','','','','"+result1.childNodes.item(xxx2).getAttribute("docno")+"',"+getInput("u_year")+","+getInput("u_assdate").substr(0,2)+",'')");
                               
                            if (parseInt(result.getAttribute("result"))>0) {
                                for (xxx1 = 0; xxx1 < result.childNodes.length; xxx1++) {
                                        yearto = result.childNodes.item(xxx1).getAttribute("yrto");
                                        noofyrstopay = parseInt(result.childNodes.item(xxx1).getAttribute("yrto")) -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                        if (getInputNumeric("u_yearto")>0) {
                                                if (getInputNumeric("u_yearto")>=parseInt(result.childNodes.item(xxx1).getAttribute("yrfr"))) {
                                                } else continue;
                                                if (parseInt(result.childNodes.item(xxx1).getAttribute("yrto"))>getInputNumeric("u_yearto")) {
                                                        noofyrs = parseInt(result.childNodes.item(xxx1).getAttribute("yrto")) -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                                        noofyrstopay = getInputNumeric("u_yearto") -  parseInt(result.childNodes.item(xxx1).getAttribute("yrfr")) + 1;
                                                        yearto = getInputNumeric("u_yearto");
                                                        //alert(noofyrs+":"+noofyrstopay);
                                                }	
                                        }
                                        if (xxx1==0) {
                                                setInput("u_tin",result.childNodes.item(xxx1).getAttribute("tin"));
                                                setInput("u_declaredowner",result.childNodes.item(xxx1).getAttribute("ownername"));
                                                setInput("u_paidby",result.childNodes.item(xxx1).getAttribute("ownername"));
                                                setInput("u_yearfrom",result.childNodes.item(xxx1).getAttribute("yrfr"));
                                        } else if (xxx1==(result.childNodes.length-1)) {
                                                setInput("u_yearto",yearto);
                                        }	
                                        if (getInputNumeric("u_yearfrom")>parseInt(result.childNodes.item(xxx1).getAttribute("yrfr"))) {
                                                setInput("u_yearfrom",result.childNodes.item(xxx1).getAttribute("yrfr"));
                                        }
                                        if(result.childNodes.item(xxx1).getAttribute("property")=="LAND"){
                                                var r = page.executeFormattedQuery("select b.name as u_class,c.u_barangay,c.u_trxcode,c.u_effyear from u_rpfaas1a a left join u_rpfaas1 c on c.docno = a.u_arpno and a.company = c.company and a.branch = c.branch  left join u_rplands b on a.u_class = b.code where u_arpno = '"+result.childNodes.item(xxx1).getAttribute("arpno")+"' and a.company = '"+getGlobal("company")+"' and a.branch = '"+getGlobal("branch")+"' group by u_arpno order by u_Arpno");
                                                if (parseInt(r.getAttribute("result"))>0) {
                                                            if(r.childNodes.item(0).getAttribute("u_trxcode")=="DC" && r.childNodes.item(0).getAttribute("u_effyear")==getInput("u_year") ){
                                                                discperc = 0;
                                                            }
                                                            data["u_class"] = r.childNodes.item(0).getAttribute("u_class");
                                                            data["u_barangay"] = r.childNodes.item(0).getAttribute("u_barangay");
                                                }
                                         }else if(result.childNodes.item(xxx1).getAttribute("property")=="BUILDING"){
                                            var r = page.executeFormattedQuery("select b.name as u_class,c.u_barangay,c.u_trxcode,c.u_effyear from u_rpfaas2a a left join u_rpfaas2 c on c.docno = a.u_arpno and a.company = c.company and a.branch = c.branch  left join u_rplands b on a.u_class = b.code where u_arpno = '"+result.childNodes.item(xxx1).getAttribute("arpno")+"' and a.company = '"+getGlobal("company")+"' and a.branch = '"+getGlobal("branch")+"'  group by u_arpno order by u_Arpno");
                                                if (parseInt(r.getAttribute("result"))>0) {
                                                            if(r.childNodes.item(0).getAttribute("u_trxcode")=="DC" && r.childNodes.item(0).getAttribute("u_effyear")==getInput("u_year") ){
                                                                discperc = 0;
                                                            }
                                                            data["u_class"] = r.childNodes.item(0).getAttribute("u_class");
                                                            data["u_barangay"] = r.childNodes.item(0).getAttribute("u_barangay");
                                                }
                                        }else{
                                            var r = page.executeFormattedQuery("select b.name as u_class,c.u_barangay,c.u_trxcode,c.u_effyear from u_rpfaas3a a left join u_rpfaas3 c on c.docno = a.u_arpno and a.company = c.company and a.branch = c.branch  left join u_rpactuses b on a.u_actualuse = b.code where a.u_arpno = '"+result.childNodes.item(xxx1).getAttribute("arpno")+"' and a.company = '"+getGlobal("company")+"' and a.branch = '"+getGlobal("branch")+"'  group by u_arpno order by u_Arpno");
                                                if (parseInt(r.getAttribute("result"))>0) {
                                                            if(r.childNodes.item(0).getAttribute("u_trxcode")=="DC" && r.childNodes.item(0).getAttribute("u_effyear")==getInput("u_year") ){
                                                                discperc = 0;
                                                            }
                                                            data["u_class"] = r.childNodes.item(0).getAttribute("u_class");
                                                            data["u_barangay"] = r.childNodes.item(0).getAttribute("u_barangay");
                                                }
                                        }
                                        data["u_selected"] = 0;
                                        data["u_isbalance"] = 0;
                                        data["u_balrefno"] = "";
                                        data["u_kind"] = result.childNodes.item(xxx1).getAttribute("property");
                                        data["u_ownername"] = result.childNodes.item(xxx1).getAttribute("ownername");
                                        data["u_paidby"] = result.childNodes.item(xxx1).getAttribute("ownername");
                                        data["u_pinno"] = result.childNodes.item(xxx1).getAttribute("pin");
                                        data["u_arpno"] = result.childNodes.item(xxx1).getAttribute("arpno");
                                        data["u_tdno"] = result.childNodes.item(xxx1).getAttribute("tdno");
                                        data["u_noofyrs"] = noofyrstopay;
                                        data["u_yrfr"] = result.childNodes.item(xxx1).getAttribute("yrfr");
                                        data["u_yrto"] = yearto;
                                        data["u_assvalue"] = formatNumericAmount(result.childNodes.item(xxx1).getAttribute("assvalue"));
                                        tax = parseFloat(result.childNodes.item(xxx1).getAttribute("taxdue"));
                                        epsf = parseFloat(result.childNodes.item(xxx1).getAttribute("epsf"));
                                        penalty = parseFloat(result.childNodes.item(xxx1).getAttribute("penalty"));
                                        sef = parseFloat(result.childNodes.item(xxx1).getAttribute("sefdue"));
                                        sefpenalty = parseFloat(result.childNodes.item(xxx1).getAttribute("sefpenalty"));
                                        if (getInputNumeric("u_yearto")>0 && parseInt(result.childNodes.item(xxx1).getAttribute("yrto"))>getInputNumeric("u_yearto")) {
                                                epsf = (epsf/noofyrs) * noofyrstopay;
                                                tax = (tax/noofyrs) * noofyrstopay;
                                                penalty = (penalty/noofyrs) * noofyrstopay;
                                                sef = (sef/noofyrs) * noofyrstopay;
                                                sefpenalty = (sefpenalty/noofyrs) * noofyrstopay;
                                        }	
                                        data["u_discperc"] = "0.00";
                                        taxdisc = 0;
                                        sefdisc = 0;

                                        if (formatDateToDB(getInput("u_assdate")).substr(0,4)==data["u_yrfr"]) {
                                                data["u_discperc"] = formatNumericAmount(discperc);
                                                taxdisc = (parseFloat(data["u_discperc"]/100) * tax);
                                                sefdisc = (parseFloat(data["u_discperc"]/100) * sef);
                                        }
                                        taxdisc = parseFloat(taxdisc);
                                        sefdisc = parseFloat(sefdisc);
                                        data["u_epsf"] = formatNumericAmount(epsf);
                                        data["u_taxdue"] = formatNumericAmount(tax);
                                        data["u_penalty"] = formatNumericAmount(penalty);
                                        data["u_sef"] = formatNumericAmount(sef);
                                        data["u_sefpenalty"] = formatNumericAmount(sefpenalty);
                                        data["u_taxdisc"] = formatNumericAmount(taxdisc);
                                        data["u_sefdisc"] = formatNumericAmount(sefdisc);;
                                        data["u_taxtotal"] = formatNumericAmount(tax+penalty-taxdisc);
                                        data["u_seftotal"] = formatNumericAmount(sef+sefpenalty-sefdisc);
                                        data["u_linetotal"] = formatNumericAmount(tax+penalty-taxdisc + sef+sefpenalty-sefdisc);
                                        data["u_dpamount"] = formatNumericAmount(tax+penalty-taxdisc + sef+sefpenalty-sefdisc);


                                        data["u_billdate"] = getInput("u_assdate");

                                        if (getInput("u_year")!=data["u_yrfr"]) {
                                             data["u_paymode"] = "Annually";
                                             insertTableRowFromArray("T1",data);
                                        }else if (getInput("u_year")==data["u_yrfr"]) {
                                            if (getInput("u_paymode")=="A") {
                                                 data["u_paymode"] = "Annually";
                                                insertTableRowFromArray("T1",data);
                                            }
                                        }
                                        totaltax+=  tax;
                                        totalsef+= sef;
                                        totaltaxdisc+= taxdisc;
                                        totalsefdisc+= sefdisc;
                                        totalpenalty+= penalty;
                                        totalsefpenalty+= sefpenalty;
                                }
                        }
                    }
                }
                
		
		var filterExp = "";
		if (getInput("u_tin")!="") filterExp += " and u_ownertin='"+getInput("u_tin")+"'";
		//if (getInput("u_pin")!="") filterExp += " and u_pin='"+getInput("u_pin")+"'";
		//alert("select u_pin,u_tdno, docno, 'L' as u_property, u_ownertin, u_ownername, u_assvalue, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and u_expyear=0 union all select u_pin,u_tdno,docno, 'B' as u_property, u_ownertin, u_ownername, u_assvalue, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and u_expyear=0 union all select u_pin,u_tdno,docno, 'M' as u_property, u_ownertin, u_ownername, u_assvalue, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and u_expyear=0");
		var result = page.executeFormattedQuery("select u_pin,u_tdno, docno, 'LAND' as u_property, u_ownertin, u_ownername, u_assvalue, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location from u_rpfaas1 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and u_expyear=0 union all select u_pin,u_tdno,docno, 'BUILDING' as u_property, u_ownertin, u_ownername, u_assvalue, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location from u_rpfaas2 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and u_expyear=0 union all select u_pin,u_tdno,docno, 'MACHINERY' as u_property, u_ownertin, u_ownername, u_assvalue, concat(u_barangay,', ', if(u_city<>'',u_city,u_municipality),', ',u_province) as u_location from u_rpfaas3 where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' "+filterExp+" and u_bilyear<='"+getInput("u_year")+"' and u_expyear=0");
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				data["u_kind"] = result.childNodes.item(xxx).getAttribute("u_property");
				data["u_pin"] = result.childNodes.item(xxx).getAttribute("u_pin");
				data["u_arpno"] = result.childNodes.item(xxx).getAttribute("docno");
				data["u_tdno"] = result.childNodes.item(xxx).getAttribute("u_tdno");
				data["u_assvalue"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_assvalue"));
				insertTableRowFromArray("T2",data);
			}
	
		}
                
		var result = page.executeFormattedQuery("select u_orrefno,u_year,u_tdno,u_taxdue,u_penalty,u_sef,u_sefpenalty,u_tin,code from u_taxcredits where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_tin='"+getInput("u_tin")+"' and u_status='O' ");
		if (parseInt(result.getAttribute("result"))>0) {
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				data["u_year"] = result.childNodes.item(xxx).getAttribute("u_year");
				data["u_tdno"] = result.childNodes.item(xxx).getAttribute("u_tdno");
				data["u_tin"] = result.childNodes.item(xxx).getAttribute("u_tin");
				data["u_apprefno"] = result.childNodes.item(xxx).getAttribute("code");
				data["u_orrefno"] = result.childNodes.item(xxx).getAttribute("u_orrefno");
				data["u_taxdue"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_taxdue"));
				data["u_penalty"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_penalty"));
				data["u_sef"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_sef"));
				data["u_sefpenalty"] = formatNumericAmount(result.childNodes.item(xxx).getAttribute("u_sefpenalty"));
				insertTableRowFromArray("T3",data);
			}
	
		}	
               
	
            
	}
    }
  
    computeTaxGPSRPTAS();
    hideAjaxProcess();
    
}
function advancePayGPSRPTAS() {
	if (isInputChecked("u_advancepay")) {
		var data = new Array();
		var rc =  getTableRowCount("T2");
                if(getInput("u_noofadvanceyear")!=0 || getInput("u_noofadvanceyear")!="" ){
                    for(xxx1=1; xxx1<=getInput("u_noofadvanceyear"); xxx1++){
                        for (xxx2 = 1; xxx2 <= rc; xxx2++) {
			if (isTableRowDeleted("T2",xxx2)==false) {
				//data["u_selected"] = 1;
                                 if(getTableInput("T2","u_kind",xxx2)=="LAND"){
                                        var r = page.executeFormattedQuery("select b.name as u_class,c.u_barangay   from u_rpfaas1a a left join u_rpfaas1 c on c.docno = a.u_arpno left join u_rplands b on a.u_class = b.code where u_arpno = '"+getTableInput("T2","u_arpno",xxx2)+"' group by u_arpno order by u_Arpno");
                                        if (parseInt(r.getAttribute("result"))>0) {
                                                    data["u_class"] = r.childNodes.item(0).getAttribute("u_class");
                                                    data["u_barangay"] = r.childNodes.item(0).getAttribute("u_barangay");
                                        }
                                 }else if(getTableInput("T2","u_kind",xxx2)=="BUILDING"){
                                     var r = page.executeFormattedQuery("select b.name as u_class,c.u_barangay from u_rpfaas2a a left join u_rpfaas2 c on c.docno = a.u_arpno left join u_rplands b on a.u_class = b.code where u_arpno = '"+getTableInput("T2","u_arpno",xxx2)+"' group by u_arpno order by u_Arpno");
                                        if (parseInt(r.getAttribute("result"))>0) {
                                                    data["u_class"] = r.childNodes.item(0).getAttribute("u_class");
                                                    data["u_barangay"] = r.childNodes.item(0).getAttribute("u_barangay");
                                        }
                                }else{
                                     var r = page.executeFormattedQuery("select b.name as u_class,c.u_barangay from u_rpfaas3a a left join u_rpfaas3 c on c.docno = a.u_arpno left join u_rpactuses b on a.u_actualuse = b.code where a.u_arpno = '"+getTableInput("T2","u_arpno",xxx2)+"' group by u_arpno order by u_Arpno");
                                        if (parseInt(r.getAttribute("result"))>0) {
                                                    data["u_class"] = r.childNodes.item(0).getAttribute("u_class");
                                                    data["u_barangay"] = r.childNodes.item(0).getAttribute("u_barangay");
                                        }
                                }
				data["u_kind"] = getTableInput("T2","u_kind",xxx2);
				data["u_arpno"] = getTableInput("T2","u_arpno",xxx2);
				data["u_tdno"] = getTableInput("T2","u_tdno",xxx2);
				data["u_pinno"] = getTableInput("T2","u_pin",xxx2);
				data["u_noofyrs"] = 1;
                                data["u_billdate"] = getInput("u_assdate");
                                data["u_ownername"] = getInput("u_declaredowner");
                                data["u_paymode"] = "Annually";
				data["u_yrfr"] = getInputNumeric("u_year")+xxx1;
				data["u_yrto"] = getInputNumeric("u_year")+xxx1;
				data["u_assvalue"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2));
				data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2)*(getInputNumeric("u_rate")/100));
				data["u_sef"] = formatNumericAmount(getTableInputNumeric("T2","u_assvalue",xxx2)*(getInputNumeric("u_sefrate")/100));
				data["u_penalty"] = formatNumericAmount(0);
				data["u_sefpenalty"] = formatNumericAmount(0);
				data["u_discperc"] = formatNumericAmount(getInputNumeric("u_advancediscperc"));
				data["u_taxdisc"] = formatNumericAmount(formatNumeric(data["u_taxdue"],0)*(getInputNumeric("u_advancediscperc")/100));
				data["u_sefdisc"] = formatNumericAmount(formatNumeric(data["u_sef"],0)*(getInputNumeric("u_advancediscperc")/100));
				data["u_taxtotal"] = formatNumericAmount(formatNumeric(data["u_taxdue"],0)-formatNumeric(data["u_taxdisc"],0));
				data["u_seftotal"] = formatNumericAmount(formatNumeric(data["u_sef"],0)-formatNumeric(data["u_sefdisc"],0));
				data["u_linetotal"] = formatNumericAmount(formatNumeric(data["u_taxtotal"],0) * 2);
				data["u_dpamount"] = formatNumericAmount(formatNumeric(data["u_taxtotal"],0) * 2);
				insertTableRowFromArray("T1",data);
				setInput("u_paymode","A");
				disableInput("u_paymode");
                            }
                        }
                    }
                }else{
                    page.statusbar.showError("Invalid Advance year");
                    return false;
                }
		
	} else {
            
		var rc =  getTableRowCount("T1");
                
		for (xxx = 1; xxx <= rc; xxx++) {
			if (isTableRowDeleted("T1",xxx)==false) {
				if (getTableInputNumeric("T1","u_yrfr",xxx)>=(getInputNumeric("u_year")+1)) {
					deleteTableRow("T1",xxx);
					//break;
				}
			}
		}
                    
		//if (getInput("u_yearfrom")==getPrivate("year")) {
			setInput("u_paymode","A");
			enableInput("u_paymode");
                        getTaxDues();
//		//} else {
//			setInput("u_paymode","A");
//			disableInput("u_paymode");
//		//}			
	}	
	computeTaxGPSRPTAS();
}

function computeTaxGPSRPTAS() {
//	setInput("u_yearto",0);
	var rc =  getTableRowCount("T1"), epsf=0, sef=0, tax=0, penalty=0,sefpenalty=0,discamount=0,sefdiscamount=0 ;
	
	var q1=0, q2=0, q3=0,q4=0,s1=0,s2=0 ;
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
				epsf+= getTableInputNumeric("T1","u_epsf",xxx);
				tax+= getTableInputNumeric("T1","u_taxdue",xxx);
				sef+= getTableInputNumeric("T1","u_sef",xxx);
				penalty+= getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx);
				sefpenalty+= getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx);
				discamount+= getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx);
				sefdiscamount+= getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx);
                                if(getTableInput("T1","u_paymode",xxx)=="1st Quarter"){
                                    q1+= getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx) - (getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx)) - (getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx));
                                }
                                if(getTableInput("T1","u_paymode",xxx)=="2nd Quarter"){
                                    q2+= getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx) - (getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx)) - (getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx));
                                }
                                if(getTableInput("T1","u_paymode",xxx)=="3rd Quarter"){
                                    q3+= getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx) - (getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx)) - (getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx));
                                }
                                if(getTableInput("T1","u_paymode",xxx)=="4rth Quarter"){
                                    q4+= getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx) - (getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx)) - (getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx));
                                }
                                if(getTableInput("T1","u_paymode",xxx)=="1st Bi-Annually"){
                                    s1+= getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx) - (getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx)) - (getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx));
                                }
                                if(getTableInput("T1","u_paymode",xxx)=="2nd Bi-Annually"){
                                    s2+= getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_penalty",xxx) + getTableInputNumeric("T1","u_penaltyadj",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) + getTableInputNumeric("T1","u_sefpenaltyadj",xxx) - (getTableInputNumeric("T1","u_taxdisc",xxx) + getTableInputNumeric("T1","u_taxdiscadj",xxx)) - (getTableInputNumeric("T1","u_sefdisc",xxx) + getTableInputNumeric("T1","u_sefdiscadj",xxx));
                                }
                                
			}
		}
	}
        //for tax credit
        var rc2 =  getTableRowCount("T3"), sefcredit=0, taxcredit=0, penaltycredit=0,sefpenaltyredit=0 ;
        for (xxx = 1; xxx <= rc2; xxx++) {
		if (isTableRowDeleted("T3",xxx)==false) {
			if (getTableInputNumeric("T3","u_selected",xxx)=="1") {
                                taxcredit+= getTableInputNumeric("T3","u_taxdue",xxx);
				sefcredit+= getTableInputNumeric("T3","u_sef",xxx);
				penaltycredit+= getTableInputNumeric("T3","u_penalty",xxx);
				sefpenaltyredit+= getTableInputNumeric("T3","u_sefpenalty",xxx);
				
                        }
            }
        }
	setInputAmount("u_epsftotal",epsf);
	setInputAmount("u_tax",tax - taxcredit);
	setInputAmount("u_seftax",sef - sefcredit);
	setInputAmount("u_penalty",penalty - penaltycredit);
	setInputAmount("u_sefpenalty",sefpenalty - sefpenaltyredit);
	setInputAmount("u_discamount",discamount);
	setInputAmount("u_sefdiscamount",sefdiscamount);
	setInputAmount("u_totaltaxamount",epsf + (tax - taxcredit)+(sef - sefcredit)+(penalty - penaltycredit)+(sefpenalty - sefpenaltyredit)-discamount-sefdiscamount);
	setInputAmount("u_q1totaltax",q1);
	setInputAmount("u_q2totaltax",q2);
	setInputAmount("u_q3totaltax",q3);
	setInputAmount("u_q4totaltax",q4);
	setInputAmount("u_s1totaltax",s1);
	setInputAmount("u_s2totaltax",s2);
}

function openupdpays() {
	OpenPopup(1024,600,"./udo.php?&objectcode=u_rpupdpays&sf_keys="+getInput("code")+"&formAction=e","UpdPays");	
}

function u_paymentGPSRPTAS() {
//        OpenPopup(1024,700,"./udo.php?&objectcode=u_lgupos&formAction=e","Payment");
    if(getInput("u_paymode")=="A"){
        if (isInputEmpty("u_paidby")) return false;
        if (isInputEmpty("u_ornumber")) return false;
        if (isInputEmpty("ordate")) return false;
        if (isInputNegative("u_paidamount")) return false;
        if(confirm('Confirm payment?')){
            setInput("docstatus","C");
            formSubmit('sc');   
        }
    }else{
        page.statusbar.showError("Only Annual payment can proceed to this process, Click [Cashier] instead.");
        return false;
    }
}
function u_cashierGPSRPTAS() {
     if(getInput("u_partialpay")==0){
//          OpenPopup(1024,700,"./udo.php?&objectcode=u_lgupos&formAction=e","Payment");
          OpenPopup(1024,700,"./udp.php?&objectcode=u_unpaybilllist","Payment");
     }else{
        page.statusbar.showError("Only full payment can proceed to this process, Click [Payment] instead.");
        return false;
     }
}
function u_PrevPostingGPSRPTAS() {
        if(getInput("u_tdno") == "Cash Sales" || getInput("u_tin") == ""){
            setStatusMsg("Please select FAAS. Tin/Td # is required",4000,1);
        }else{
            OpenPopup(1024,700,"./udo.php?&objectcode=u_rpprevposting&formAction=e","Post Previous");
        } 
}
function u_TaxCreditGPSRPTAS() {
            OpenPopup(1024,500,"./udo.php?&objectcode=u_taxcredits&formAction=e","Tax Credit");
}
function updateBilldate() {
        rc = getTableRowCount("T1");

	if (getPrivate("dataentry")=="0" && getInput("u_withfaas")==0) {
           
		for (xxx = 1; xxx <=rc; xxx++) {
			if (isTableRowDeleted("T1",xxx)==false) {
                                setTableInput("T1","u_billdate",getInput("u_assdate"),xxx);
			}
		}
			
	}	
}
function noPenaltyDisc() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_penalty",0.00,xxx);
                                    setTableInput("T1","u_penaltyadj",0.00,xxx);
                                    setTableInput("T1","u_sefpenalty",0.00,xxx);
                                    setTableInput("T1","u_sefpenaltyadj",0.00,xxx);
                                    setTableInput("T1","u_discperc",0.00,xxx);
                                    setTableInput("T1","u_taxdisc",0.00,xxx);
                                    setTableInput("T1","u_sefdisc",0.00,xxx);
                                    setTableInput("T1","u_taxdiscadj",0.00,xxx);
                                    setTableInput("T1","u_sefdiscadj",0.00,xxx);
			}
		}
	}
	
}
function noPenalty() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_penalty",0.00,xxx);
                                    setTableInput("T1","u_penaltyadj",0.00,xxx);
                                    setTableInput("T1","u_sefpenalty",0.00,xxx);
                                    setTableInput("T1","u_sefpenaltyadj",0.00,xxx);
			}
		}
	}
	
}
function noDiscount() {
	//setInput("u_yearto",0);
	var rc =  getTableRowCount("T1");
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getInputNumeric("u_yearto")<getTableInputNumeric("T1","u_yrto",xxx)) setInput("u_yearto",getTableInputNumeric("T1","u_yrto",xxx));
                                    setTableInput("T1","u_discperc",0.00,xxx);
                                    setTableInput("T1","u_taxdisc",0.00,xxx);
                                    setTableInput("T1","u_sefdisc",0.00,xxx);
                                    setTableInput("T1","u_taxdiscadj",0.00,xxx);
                                    setTableInput("T1","u_sefdiscadj",0.00,xxx);
			}
		}
	}
	
}
function YearBreak() {
	var rc =  getTableRowCount("T1"),data = new Array();
	for (xxx = 1; xxx <= rc; xxx++) {
		if (isTableRowDeleted("T1",xxx)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
				if (getTableInputNumeric("T1","u_yrto",xxx) != getTableInputNumeric("T1","u_yrfr",xxx)) {
                                     var noofyears = (getTableInputNumeric("T1","u_yrto",xxx) - getTableInputNumeric("T1","u_yrfr",xxx)) + 1;
                                   
                                     for (xxx1 = 0; xxx1 < noofyears; xxx1++) {
                                                data["u_selected"] = 1;
                                                data["u_kind"] = getTableInput("T1","u_kind",xxx);
                                                data["u_arpno"] = getTableInput("T1","u_arpno",xxx);
                                                data["u_tdno"] = getTableInput("T1","u_tdno",xxx);
                                                data["u_pinno"] = getTableInput("T1","u_pinno",xxx);
                                                data["u_class"] = getTableInput("T1","u_class",xxx);
                                                data["u_barangay"]=getTableInput("T1","u_barangay",xxx);
                                                data["u_ownername"]=getTableInput("T1","u_ownername",xxx);
                                                data["u_paymode"]="Annually";
                                                data["u_assvalue"]=formatNumericAmount(getTableInputNumeric("T1","u_assvalue",xxx));
                                                data["u_noofyrs"] = 1 ;
                                                data["u_yrfr"] = getTableInputNumeric("T1","u_yrfr",xxx) + xxx1 ;
                                                data["u_yrto"] = getTableInputNumeric("T1","u_yrfr",xxx) + xxx1 ;
                                                data["u_billdate"] = getInput("u_assdate");
                                                data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx) / noofyears);
                                                data["u_sef"] = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx) / noofyears ) ;
                                                data["u_penalty"] = formatNumericAmount(getTableInputNumeric("T1","u_penalty",xxx) / noofyears);
                                                data["u_sefpenalty"] = formatNumericAmount(getTableInputNumeric("T1","u_sefpenalty",xxx) / noofyears ) ;
                                                data["u_taxtotal"] = formatNumericAmount(getTableInputNumeric("T1","u_taxtotal",xxx) / noofyears);
                                                data["u_seftotal"] = formatNumericAmount(getTableInputNumeric("T1","u_seftotal",xxx) / noofyears ) ;
                                                data["u_linetotal"] = formatNumericAmount(getTableInputNumeric("T1","u_linetotal",xxx) / noofyears ) ;
                                                data["u_dpamount"] = formatNumericAmount(getTableInputNumeric("T1","u_dpamount",xxx) / noofyears ) ;
                                                insertTableRowFromArray("T1",data);
                                     }
                                     deleteTableRow("T1",xxx);
                                }
                                
			}
		}
	}
	
}
function getSemiQuarterly() {

        
	//setInput("u_yearto",0);
        var data = new Array();
	var rc =  getTableRowCount("T1");
	for (xxx2 = 1; xxx2 <= rc; xxx2++) {
		if (isTableRowDeleted("T1",xxx2)==false) {
			if (getTableInputNumeric("T1","u_selected",xxx2)=="1") {
				if (getInput("u_year")==getTableInputNumeric("T1","u_yrto",xxx2) && getTableInput("T1","u_paymode",xxx2)=="Annually" ){
                                    if(getInput("u_paymode")=="S"){
                                        
                                                data["u_selected"] = 1;
                                                data["u_kind"] = getTableInput("T1","u_kind",xxx2);
                                                data["u_arpno"] = getTableInput("T1","u_arpno",xxx2);
                                                data["u_tdno"] = getTableInput("T1","u_tdno",xxx2);
                                                data["u_pinno"] = getTableInput("T1","u_pinno",xxx2);
                                                data["u_class"] = getTableInput("T1","u_class",xxx2);
                                                data["u_barangay"]=getTableInput("T1","u_barangay",xxx2);
                                                data["u_ownername"]=getTableInput("T1","u_ownername",xxx2);
                                                data["u_assvalue"]=formatNumericAmount(getTableInputNumeric("T1","u_assvalue",xxx2) / 2);
                                                data["u_noofyrs"] = 1 ;
                                                data["u_yrfr"] = getTableInputNumeric("T1","u_yrto",xxx2) ;
                                                data["u_yrto"] = getTableInputNumeric("T1","u_yrto",xxx2) ;
                                                data["u_billdate"] = getInput("u_assdate");
                                                data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx2) / 2);
                                                data["u_sef"] = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx2) / 2 ) ;
                                                    for(xxx1=1;xxx1<=2;xxx1++){
                                           
                                                        if (xxx1==1){
                                                                   data["u_paymode"] = "1st Bi-Annually";
                                                                   
                                                       }else if (xxx1==2){
                                                               if(getInput("u_assdate") < "07/01/"+getPrivate("year")){
                                                                     data["u_billdate"]= "07/01/"+getPrivate("year");
                                                               }
                                                                   
                                                                    data["u_paymode"] = "2nd Bi-Annually";
                                                       }    

                                                       data["u_taxtotal"] = formatNumericAmount((getTableInputNumeric("T1","u_taxdue",xxx2)/2));
                                                       data["u_seftotal"] = formatNumericAmount((getTableInputNumeric("T1","u_sef",xxx2)/2));
                                                       data["u_linetotal"] = formatNumericAmount((getTableInputNumeric("T1","u_taxdue",xxx2) / 2) * 2 ) ;
                                                       data["u_dpamount"] = formatNumericAmount((getTableInputNumeric("T1","u_taxdue",xxx2) / 2) * 2 ) ;
                                                
                                                       insertTableRowFromArray("T1",data);
                                                    }
                                                    deleteTableRow("T1",xxx2);
                                       
                                    }else if(getInput("u_paymode")=="Q"){
                                         
                                            data["u_selected"] = 1;
                                            data["u_kind"] = getTableInput("T1","u_kind",xxx2);
                                            data["u_arpno"] = getTableInput("T1","u_arpno",xxx2);
                                            data["u_tdno"] = getTableInput("T1","u_tdno",xxx2);
                                            data["u_pinno"] = getTableInput("T1","u_pinno",xxx2);
                                            data["u_class"] = getTableInput("T1","u_class",xxx2);
                                            data["u_barangay"]=getTableInput("T1","u_barangay",xxx2);
                                            data["u_ownername"]=getTableInput("T1","u_ownername",xxx2);
                                            data["u_assvalue"]=formatNumericAmount(getTableInputNumeric("T1","u_assvalue",xxx2) / 4);
                                            data["u_noofyrs"] = 1 ;
                                            data["u_yrfr"] = getTableInputNumeric("T1","u_yrto",xxx2) ;
                                            data["u_yrto"] = getTableInputNumeric("T1","u_yrto",xxx2) ;
                                            data["u_billdate"] = getInput("u_assdate");
                                            data["u_taxdue"] = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx2) / 4);
                                            data["u_sef"] = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx2) / 4 ) ;
                                            
                                        for(xxx1=1;xxx1<=4;xxx1++){
                                           
                                             if (xxx1==1){
                                                        data["u_paymode"] = "1st Quarter";
                                                      
                                            }else if (xxx1==2){
                                                    if(getInput("u_assdate") < "04/01/"+getPrivate("year")){
                                                          data["u_billdate"]= "04/01/"+getPrivate("year");
                                                    }
                                                
                                                       data["u_paymode"] = "2nd Quarter";
                                            }else if (xxx1==3){
                                                    if(getInput("u_assdate") < "07/01/"+getPrivate("year")){
                                                          data["u_billdate"]= "07/01/"+getPrivate("year");
                                                     }
                                                    
                                                       data["u_paymode"] = "3rd Quarter";
                                            }else{
                                                    
                                                    
                                                    if(getInput("u_assdate") < "10/01/"+getPrivate("year")){
                                                          data["u_billdate"]= "10/01/"+getPrivate("year");
                                                     }
                                                       data["u_paymode"] = "4rth Quarter";   
                                            }
                                            
                                            data["u_taxtotal"] = formatNumericAmount((getTableInputNumeric("T1","u_taxdue",xxx2)/4));
                                            data["u_seftotal"] = formatNumericAmount((getTableInputNumeric("T1","u_sef",xxx2)/4));
                                            data["u_linetotal"] = formatNumericAmount((getTableInputNumeric("T1","u_taxdue",xxx2) / 4) * 2 ) ;
                                            data["u_dpamount"] = formatNumericAmount((getTableInputNumeric("T1","u_taxdue",xxx2) / 4) * 2 ) ;
                                           
                                            insertTableRowFromArray("T1",data);
                                        }
                                         deleteTableRow("T1",xxx2);
                                    }
                                   
                                   
                                }
                                    
			}
		}
	}
	computeTaxGPSRPTAS();
}
function u_PenaltyDiscQuarSemiGPSRPTAS() {
        var data = new Array();
	var rc =  getTableRowCount("T1");
	
	
	for (xxx = 1; xxx <= rc; xxx++) {
            if (isTableRowDeleted("T1",xxx)==false) {
                if (getTableInputNumeric("T1","u_selected",xxx)=="1") {
                    var discperc = 0,penperc = 0, sefpenalty = 0 , penalty= 0 ,sefdisc= 0,taxdisc=0;
                     
                    var r = page.executeFormattedQuery("select u_discperc,u_penperc from u_rpsemiquardiscspen where u_paymode = '"+getTableInput("T1","u_paymode",xxx)+"' and '"+formatDateToDB(getTableInput("T1","u_billdate",xxx))+"'>= u_datefr and '"+formatDateToDB(getTableInput("T1","u_billdate",xxx))+"' <= u_dateto ");
                    if (parseInt(r.getAttribute("result"))>0) { 
                                    discperc = formatNumericAmount(r.childNodes.item(0).getAttribute("u_discperc"));
                                    penperc = formatNumericAmount(r.childNodes.item(0).getAttribute("u_penperc"));
                                    sefpenalty = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx) * (penperc/100));
                                    penalty = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx) * (penperc/100));
                                    sefdisc = formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx) * (discperc/100));
                                    taxdisc = formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx) * (discperc/100));
                                    setTableInput("T1","u_discperc",discperc,xxx);
                                    setTableInput("T1","u_sefpenalty",sefpenalty,xxx);
                                    setTableInput("T1","u_penalty",penalty,xxx);
                                    setTableInput("T1","u_sefdisc",sefdisc,xxx);
                                    setTableInput("T1","u_taxdisc",taxdisc,xxx);
                                    setTableInput("T1","u_seftotal",formatNumericAmount(getTableInputNumeric("T1","u_sef",xxx) + getTableInputNumeric("T1","u_sefpenalty",xxx) - getTableInputNumeric("T1","u_sefdisc",xxx)),xxx);
                                    setTableInput("T1","u_taxtotal",formatNumericAmount(getTableInputNumeric("T1","u_taxdue",xxx) + getTableInputNumeric("T1","u_penalty",xxx) - getTableInputNumeric("T1","u_taxdisc",xxx)),xxx);
                                    setTableInput("T1","u_linetotal",formatNumericAmount(getTableInputNumeric("T1","u_taxtotal",xxx) + getTableInputNumeric("T1","u_seftotal",xxx)),xxx);
                                    setTableInput("T1","u_dpamount",formatNumericAmount(getTableInputNumeric("T1","u_taxtotal",xxx) + getTableInputNumeric("T1","u_seftotal",xxx)),xxx);
                    }
//
                }
            }
	}
	computeTaxGPSRPTAS();
}

function OpenLnkBtnu_rptaxes(targetObjectId) {
	OpenLnkBtn(1080,680,'./udo.php?objectcode=u_rptaxes' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

