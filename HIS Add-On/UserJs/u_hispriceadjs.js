// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');
page.events.add.cflreturn('onCFLReturnGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
page.elements.events.add.click('onElementClickGPSHIS');
page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');
page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	//document.images['PictureImg'].src = "";
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISBILLS") {
				setInput("u_chargeno",window.opener.getTableInput("T11","u_docno",window.opener.getTableSelectedRow("T11")));
				setTimeout("setChargeNoGPSHIS()",1000);
			}
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_department")) return false;
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
			if (isInputEmpty("u_patientid")) return false;
			if (isInputEmpty("u_patientname")) return false;
		} else {
			if (isInputEmpty("u_patientname")) return false;
		}
		if (isInputEmpty("u_startdate")) return false;
		if (isInputEmpty("u_starttime")) return false;
		/*if (getTableRowCount("T1",true)==0) {
			page.statusbar.showWarning("At least one item must be selected.");
			return false;
		}*/
		
		if (!checkPricesGPSHIS()) return false;
		if (getInputNumeric("u_amount")==0) {
			if (window.confirm(getInputCaption("u_amount") + " is zero. Continue?")==false)	return false;
		}
		
		if (isInputEmpty("u_remarks")) return false;

		if (!checkauthenticateGPSHIS()) return false;

	} else if (action=="cnd") {
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
				return false;
			}
			if (getTableInput("T51","remarks")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","remarks");
				return false;
			}
		}

	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,sucess,error) {
	if (sucess) {
		try {
			if (window.opener.getVar("objectcode").toLowerCase()=="u_hisbills") {
				window.opener.formEntry();
			}
		} catch (theError) {
		}
	}
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "find":
			params["params"] += ";-WHERE:U_TRXTYPE='"+getInput("u_trxtype")+"'";
			break;
	}
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
	switch (table) {
		case "T50":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T50","password");
			} else if (sc_press == "ENTER" && column=="password") {
				formSubmit();
			}
			break;
		case "T51":
			var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
			if (sc_press == "ENTER" && column=="userid") {
				focusTableInput("T51","password");
			} else if (sc_press == "ENTER" && column=="password") {
				focusTableInput("T51","remarks");
			} else if (sc_press == "ENTER" && column=="remarks") {
				formSubmit();
			}
			break;
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch(column) {
				case "u_actprice":
					setTableInputQuantity("T1","u_adjprice",getTableInputNumeric("T1","u_actprice",i)-getTableInputNumeric("T1","u_unitprice",i),i);
					setTableInputQuantity("T1","u_linetotal",getTableInputNumeric("T1","u_adjprice",i)*getTableInputNumeric("T1","u_quantity",i),i);
					computeTotalAdjGPSHIS();
					break;
			}
			break;
		default:
			switch(column) {
				case "u_chargeno":
					setChargeNoFieldAttributesGPSHIS(true);
					clearTable("T1",true);
					if (getInput("u_chargeno")!="") {
						return setChargeNoGPSHIS();
					} else {
						setInput("u_refno","");
						setInput("u_patientid","");
						setInput("u_patientname","");
						setInput("u_paymentterm","");
						setInput("u_disccode","");
						setInput("u_pricelist","");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	var result;
	switch (table) {
		case "T1":
			break;
		default:
			switch (column) {
				case "u_department":
					var result = setSectionData();
					if (result) {
						showAjaxProcess();
						clearTable("T1",true);
						hideAjaxProcess();
					}
					return result;
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_selected":
					if (row>0) {
						if (isTableInputChecked(table,column,row)) {
							enableTableInput("T1","u_actprice",row);
							focusTableInput("T1","u_actprice",row);
						} else {
							disableTableInput("T1","u_actprice",row);
						}
					}
					break;
			}
			break;
		default:
			break;
	}
	return true;
}


function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_chargeno":
			if (isInputEmpty("u_department")) return false;
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	return params;
}

function onCFLReturnGPSHIS(Id,value) {
	return true;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeEditRowGPSHIS(table,row) {
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
	var params = new Array();
	switch (table) {
		case "T1":
			params["focus"] = false;
			if (elementFocused.substring(0,15)=="df_u_actpriceT1") {
				focusTableInput(table,"u_actprice",row);
			}
			break;
	}
	return params;

}

function computeTotalAdjGPSHIS() {
	var rc =  getTableRowCount("T1"), amount=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_selected",i)=="1") {
				amount+=getTableInputNumeric("T1","u_linetotal",i);
			}
		}
	}
	setInputAmount("u_amount",amount);
	return true;
}

function checkPricesGPSHIS() {
	var rc =  getTableRowCount("T1"),ctr=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (getTableInput("T1","u_selected",i)=="1") {
				ctr++;	
				setTableInputQuantity("T1","u_adjprice",getTableInputNumeric("T1","u_actprice",i)-getTableInputNumeric("T1","u_unitprice",i),i);
				setTableInputQuantity("T1","u_linetotal",getTableInputNumeric("T1","u_adjprice",i)*getTableInputNumeric("T1","u_quantity",i),i);
				if (getTableInputNumeric("T1","u_adjprice",i)==0) {
					page.statusbar.showError("Actual price is required.");	
					selectTab("tab1-1",0);
					selectTableRow("T1",i);
					focusTableInput("T1","u_actprice",i);
					return false;	
				}
				//computePatientLineTotalGPSHIS("T1",i);
			}
		}
	}
	if (ctr==0) {
		page.statusbar.showError("At least one item must be adjusted.");	
		return false;	
	}
	computeTotalAdjGPSHIS();
	return true;
}

function setChargeNoGPSHIS(){
	var data1 = new Array();
	var data2 = new Array();
	var data3 = new Array();
	var result = page.executeFormattedQuery("select a.u_department, a.u_scdisc, a.u_disconbill, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_pricelist, a.u_disccode, a.u_birthdate, a.u_gender, a.u_startdate as u_startdate, a.u_starttime as u_requesttime, a.u_isstat, a.u_prepaid, a.u_amount, a.u_payrefno, a.u_payreftype, a.u_doctorid, b.u_itemgroup, b.u_itemcode, b.u_itemdesc, b.u_uom, b.u_quantity - b.u_rtqty as u_quantity, b.u_unitprice, b.u_discamount, b.u_price, b.u_vatamount, b.u_linetotal, b.u_vatcode, b.u_vatrate, b.u_isstat as u_isstat2, b.u_statperc, b.u_statunitprice, b.u_scdiscamount, b.u_discperc, b.u_doctorid as u_pfid, b.u_doctorname as u_pfname, '' as u_packagecode, 0 as u_packageqty, 0 as u_qtyperpack, '' as u_packdepartment, '' as u_packdepartmentname, b.u_ispackage, b.u_template, b.u_isfoc, b.lineid, a.u_adjno from u_hischarges a, u_hischargeitems b, u_hisitems c where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='"+getInput("u_chargeno")+"' and a.docstatus='O' and b.u_rtqty<b.u_quantity");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			var valid=true;
			for (xxx = 0; xxx < result.childNodes.length; xxx++) {
				if (xxx==0) {
					if (result.childNodes.item(0).getAttribute("u_adjno")!="") {
						alert("Charge Invoice already adjustment, please cancel the adjustment ["+result.childNodes.item(0).getAttribute("u_adjno")+"] first.");
						return false;
					}
					setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
					setInput("u_reftype",result.childNodes.item(0).getAttribute("u_reftype"));
					setInput("u_refno",result.childNodes.item(0).getAttribute("u_refno"));
					setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
					setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
					setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
					setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
					setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
					setInput("u_prepaid",result.childNodes.item(0).getAttribute("u_prepaid"));
					setInput("u_startdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_startdate")));
					
					if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
					else uncheckedInput("u_scdisc");

				}
				data1["u_selected"] = 0;
				data1["u_itemgroup"] = result.childNodes.item(xxx).getAttribute("u_itemgroup");
				data1["u_itemcode"] = result.childNodes.item(xxx).getAttribute("u_itemcode");
				data1["u_itemdesc"] = result.childNodes.item(xxx).getAttribute("u_itemdesc");
				data1["u_uom"] = result.childNodes.item(xxx).getAttribute("u_uom");
				data1["u_quantity"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_quantity"),"quantity");
				//data1["u_vatcode"] = result.childNodes.item(xxx).getAttribute("u_vatcode");
				//data1["u_vatrate"] = result.childNodes.item(xxx).getAttribute("u_vatrate");
				if (result.childNodes.item(xxx).getAttribute("u_isstat")=="1") {
					data1["u_unitprice"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_statunitprice"),"amount");
				} else {
					data1["u_unitprice"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_unitprice"),"amount");
				}
				//data1["u_vatamount"] = formatNumber(result.childNodes.item(xxx).getAttribute("u_vatamount"),"amount");
				data1["u_linetotal"] = formatNumber(0,"amount");
				data1["u_doctorid"] = result.childNodes.item(xxx).getAttribute("u_pfid");
				data1["u_doctorname"] = result.childNodes.item(xxx).getAttribute("u_pfname");
				data1["u_chargelineid"] = result.childNodes.item(xxx).getAttribute("lineid");
				insertTableRowFromArray("T1",data1);
				disableTableInput("T1","u_actprice",getTableRowCount("T1"));
			}
			setChargeNoFieldAttributesGPSHIS(false);
		} else {
			setInput("u_refno","");
			setInput("u_patientid","");
			setInput("u_patientname","");
			page.statusbar.showError("Invalid Reference No.");	
			return false;
		}
	} else {
		setInput("u_refno","");
		setInput("u_patientid","");
		setInput("u_patientname","");
		page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
		return false;
	}	
	return true;
}

function setChargeNoFieldAttributesGPSHIS(enable) {
	if (enable) {
		//enableInput("u_department");
		enableInput("u_reftype");
		enableInput("u_refno");
		enableInput("u_disccode");
		enableInput("u_pricelist");
		//enableInput("u_quantity");
	} else {
		//disableInput("u_department");
		disableInput("u_reftype");
		disableInput("u_refno");
		disableInput("u_disccode");
		disableInput("u_pricelist");
		//disableInput("u_quantity");
	}
}


function OpenLnkBtnRequestNoGPSHIS(targetId) {
	OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
}

