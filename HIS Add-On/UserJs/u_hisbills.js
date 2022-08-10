// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');
page.events.add.reportgetparams('onReportGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
page.elements.events.add.keydown('onElementKeyDownGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');
page.elements.events.add.lnkbtngetparams('onLnkBtnGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.beforeEdit('onTableBeforeEditRowGPSHIS');

healthinsmodified = false;
healthinsordered = false;

function onPageLoadGPSHIS() {
	if (getTableRowCount("T112")>0 || getTableRowCount("T113")>0) showPopupFrame("popupFramePendingItems",true);
	if (getInput("docstatus")=="D" && parseInt(getPrivate("billage"))>2) {
		if (window.confirm("Current Pre-Billing Document Date/Time is not updated. Refresh?")==true) {
			setTimeout("formSubmit('d')",2000);
		}
	}
	if (getPrivate("reftypetobill")!="" && getPrivate("refnotobill")!="") {
		//alert(getPrivate("reftypetobill")+":"+getPrivate("refnotobill"))
		setInput("u_reftype",getPrivate("reftypetobill"));
		setInput("u_refno",getPrivate("refnotobill"),true);
	}
	if (getInput("u_terminalid")!="") {
		showPopupFrame("popupFrameQueue",true,null,false);
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onReportGetParamsGPSHIS(p_formattype,p_params) {
	var params = new Array(),paramids= new Array(),paramtypes= new Array(),paramvaluetypes= new Array(),paramaliases= new Array();
	var docstatus = getInput("docstatus");
	if (getVar("formSubmitAction")=="a") docstatus = "";
	if (p_params!=null) params = p_params;
	params = getReportLayout(getGlobal("progid2"),p_formattype,params,docstatus);
	
	params["source"] = "aspx";
	if (params["querystring"]==undefined) {
		params["querystring"] = "";
		params["querystring"] += generateQueryString("date1",formatDateToDB(getInput("u_startdate")));
		if (getGlobal("serverdate")>getInput("u_docdate")) params["querystring"] += generateQueryString("date2",formatDateToDB(getGlobal("serverdate")));
		else params["querystring"] += generateQueryString("date2",formatDateToDB(getInput("u_docdate")));
		params["querystring"] += generateQueryString("branch",getGlobal("branch"));
		params["querystring"] += generateQueryString("bpcode",getInput("u_patientid"));
		params["querystring"] += generateQueryString("docno",getInput("docno"));
		params["querystring"] += generateQueryString("refno",getInput("u_refno"));
		
	}	
	
	return params;
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc")	{
		if (getInput("docstatus")=="O" && (getInput("u_trxtype")=="IP" && getInput("u_mgh")=="0" )) {
			page.statusbar.showError("This bill can only be save as draft for pre-bill purpose only. Patient must be tag as May Go Home.");
			return false;
		}
		setInput("u_enddate",getInput("u_docdate"));
		if ((action == "a" && getInput("docstatus")!="D") || ((getPrivate("docstatus")=="D") && (getInput("docstatus")!="D"))){
			if (getInput("docstatus")!="D") {
				if (getTableRowCount("T112")>0) {
					showPopupFrame("popupFramePendingItems");
					alert("You cannot post the bill if Pending Requests still exists.");
					return false;
				}
				if (window.confirm("You cannot change this document after you have added it. Continue?")==false) return false;
			}
		}	
	} else if (action=="cnd") {
		if (getInputNumeric("u_amount")!=getInputNumeric("u_netamount")) {
			page.statusbar.showError("Please cancel all Health Benefits and DM/CM/PN Documents before cancelling this bill");
			return false;
		}
		if (isTableInput("T51","userid")) {
			if (getTableInput("T51","userid")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","userid");
				return false;
			}
			if (getTableInput("T51","cancelreason")=="") {
				showPopupFrame("popupFrameCancel",true);
				focusTableInput("T51","cancelreason");
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

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
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
				focusTableInput("T51","cancelreason");
			} else if (sc_press == "ENTER" && column=="cancelreason") {
				focusTableInput("T51","remarks");
			} else if (sc_press == "ENTER" && column=="remarks") {
				formSubmit('cnd');
			}
			break;
	}
	
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		default:
			switch(column) {
				case "u_docdate":
					if (getInput("u_docdate")!="") {
						if (getInput("u_refno")!="") formEntry();
					}
					break;
				case "u_refno":
					if (getInput("u_refno")!="") {
						var result2 = page.executeFormattedSearch("select docno from u_hisbills where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_reftype='"+getInput("u_reftype")+"' and u_refno='"+getInput("u_refno")+"' and docstatus not in ('CN')");
						if (result2!="") {
							setKey("keys",result2);
							formEdit();
							return true;
						}
	
						if (getInput("u_reftype")=="IP") {
							result = page.executeFormattedQuery("select u_patientid, u_patientname, u_gender, u_age_y, u_mgh, u_startdate, u_icdcode, u_billcount, u_treatment, u_phicmemberid from u_hisips where docno='"+getInput("u_refno")+"' and u_billno=''");	 
						} else {
							result = page.executeFormattedQuery("select u_patientid, u_patientname, u_gender, u_age_y, u_mgh, u_startdate, u_icdcode, u_billcount, u_treatment, u_phicmemberid from u_hisops where docno='"+getInput("u_refno")+"' and u_billno=''");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
								setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
								setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
								setInput("u_mgh",result.childNodes.item(0).getAttribute("u_mgh"));
								setInput("u_age",result.childNodes.item(0).getAttribute("u_age_y"));
								setInput("u_startdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_startdate")));
								setInput("u_icdcode",result.childNodes.item(0).getAttribute("u_icdcode"));
								setInput("u_billcount",parseInt(result.childNodes.item(0).getAttribute("u_billcount"))+1);
								if (isInput("u_treatment")) setInput("u_treatment",result.childNodes.item(0).getAttribute("u_treatment"));
								if (isInput("u_phicmemberid")) setInput("u_phicmemberid",result.childNodes.item(0).getAttribute("u_phicmemberid"));
								if (getInput("u_docdate")!="") formEntry();
							} else {
								setInput("u_patientid","");
								setInput("u_patientname","");
								page.statusbar.showError("Invalid Reference No.");	
								return false;
							}
						} else {
							setInput("u_patientid","");
							setInput("u_patientname","");
							page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_patientid","");
						setInput("u_patientname","");
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
	switch (table) {
		case "T7":
			switch (column) {
				case "u_inscode":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select u_hmo from u_hishealthins where code='"+getTableInput(table,"u_inscode")+"'");	 
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								var u_hmo = result.childNodes.item(0).getAttribute("u_hmo");	
								if (u_hmo!=2) {
									setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
									result2 = page.executeFormattedQuery("select u_memberid, u_membername, u_membertype from u_hispatienthealthins where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getInput("u_patientid")+"' and u_inscode='"+getTableInput(table,"u_inscode")+"'");	 
									if (result2.getAttribute("result")!= "-1") {
										if (parseInt(result2.getAttribute("result"))>0) {
											setTableInput(table,"u_memberid",result2.childNodes.item(0).getAttribute("u_memberid"));
											setTableInput(table,"u_membername",result2.childNodes.item(0).getAttribute("u_membername"));
											setTableInput(table,"u_membertype",result2.childNodes.item(0).getAttribute("u_membertype"));
										} /*else {
											setTableInput(table,"u_hmo","-1");
											setTableInput(table,"u_memberid","");
											setTableInput(table,"u_membername","");
											setTableInput(table,"u_membertype","");
											page.statusbar.showError("Invalid Health Benefits for this patient.");	
											return false;
										}*/
									} else {
										//setTableInput(table,"u_hmo","-1");
										setTableInput(table,"u_memberid","");
										setTableInput(table,"u_membername","");
										setTableInput(table,"u_membertype","");
										page.statusbar.showError("Error retrieving Health Benefits for this patient. Try Again, if problem persists, check the connection.");	
									}								
								} else {
									setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
								}
							} else {
								setTableInput(table,"u_hmo","-1");
								page.statusbar.showError("Invalid Health Benefits Code.");	
								return false;
							}
						} else {
							setTableInput(table,"u_hmo","-1");
							page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_hmo","-1");
					}
					break;
			}
			break;
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where (u_billing=1 or u_bedno<>'') and u_billno='' and docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where u_billing=1 and u_billno='' and docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
	}
	return params;
}

function onLnkBtnGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "u_hisinclaims":
			params["keys"] = getTableInput("T7","u_claimno",getTableSelectedRow("T7"))
			break;
		case "u_hispronotes":
			params["keys"] = getTableInput("T8","u_pnno",getTableSelectedRow("T8"))
			break;
		case "u_hisphicclaims":
			var rc =  getTableRowCount("T8");
			for (i = 1; i <= rc; i++) {
				if (isTableRowDeleted("T8",i)==false) {
					if (getTableInput("T8","u_guarantorcode",i)=="PHIC" && getTableInput("T8","u_claimno",i)!="" && getTableInput("T8","u_status",i)!="CN") {
						params["keys"] = getTableInput("T8","u_claimno",i);
						break;
					}
				}
			}
			break;
	}
	return params;
}


function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T7":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="5") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}
			break;
		case "T8":
			if (getInput("docstatus")=="D" || getVar("formSubmitAction")=="a") {
				alert("Add billing before creating cm/dm/pronotes document.");	
				return false;
			}
			if (getInput("docstatus")=="CN") {
				alert("Cannot create health benefit deduction on cancelled bill.");	
				return false;
			}
			targetObjectId = '';
			OpenLnkBtn(1024,320,'./udo.php?objectcode=u_hispronotes' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T7":
			healthinsmodified = true;
			break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T7":
			if (isTableInputEmpty(table,"u_inscode")) return false;
			if (getTableInput(table,"u_hmo")=="0") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
				if (isTableInputEmpty(table,"u_membertype")) return false;
			} else if (getTableInput(table,"u_hmo")!="2" && getTableInput(table,"u_hmo")!="5") {
				if (isTableInputEmpty(table,"u_memberid")) return false;
				if (isTableInputEmpty(table,"u_membername")) return false;
			}
			if (getTableInput(table,"u_hmo")=="-1") {
				page.statusbar.showError("Un error occured when selecting the Health Benefit Code, please re-select.");	
				setTableInput(table,"u_inscode");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T7":
			healthinsmodified = true;
			break;
	}
}

function onTableBeforeEditRowGPSHIS(table,row) {
	var targetObjectId = '';
	switch (table) {
		case "T7":
			if (getInput("docstatus")=="D" || getVar("formSubmitAction")=="a") {
				alert("Add billing before creating health benefit deduction document.");	
				return false;
			}
			if (getInput("docstatus")=="CN") {
				alert("Cannot create health benefit deduction on cancelled bill.");	
				return false;
			}
		
			if (getTableRowStatus(table,row)!="N" && healthinsordered==false && healthinsmodified==false) {
				if (row>1) {
					if (getTableInput(table,"u_status",row-1)!="C") {
						page.statusbar.showWarning("You must finalize the prior health benefits before proceeding with this health benefits.");
						return false;
					}
				}
				targetObjectId = 'u_hisinclaims';
				OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisinclaims' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			} else {
				page.statusbar.showWarning("Please update page prior to health benefits computation.");
			}
			return false;
			break;
		case "T8":
			targetObjectId = 'u_hispronotes';
			OpenLnkBtn(1024,310,'./udo.php?objectcode=u_hispronotes' + '' + '&targetId=' + targetObjectId ,targetObjectId);
			return false;
			break;
	}
	return true;
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function u_moveRowUpHealthBenefitsGPSHIS() {
	var table = "T7";
	var rc = getTableRowCount(table),sr = 0;	
	if (healthinsmodified) {
		page.statusbar.showWarning("Please update page prior to moving up/dn health benefits.");
		return;
	}
	if (rc==0) return;
	sr = getTableSelectedRow(table);
	var keys,u_inscode,u_claimno,u_status,u_amount,u_hmo,u_insdesc,u_memberid,u_membername,u_membertype,u_membertypedesc;
	var keys2,u_inscode2,u_claimno2,u_status2,u_amount2,u_hmo2,u_insdesc2,u_memberid2,u_membername2,u_membertype2,u_membertypedesc2;
	if (sr!=1) {
		keys = getTableKey(table,"keys",sr);
		u_inscode = getTableInput(table,"u_inscode",sr);
		u_claimno = getTableInput(table,"u_claimno",sr);
		u_status = getTableInput(table,"u_status",sr);
		u_amount = getTableInputNumeric(table,"u_amount",sr);
		u_hmo = getTableInput(table,"u_hmo",sr);
		u_memberid = getTableInput(table,"u_memberid",sr);
		u_membername = getTableInput(table,"u_membername",sr);
		u_membertype = getTableInput(table,"u_membertype",sr);
		u_insdesc = page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode+"'");
		u_membertypedesc = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype+"'");

		if (u_status!="")	 {
			page.statusbar.showError("You cannot move health benefits, if status is already set.");
			return;
		}

		keys2 = getTableKey(table,"keys",sr - 1);
		u_inscode2 = getTableInput(table,"u_inscode",sr - 1);
		u_claimno2 = getTableInput(table,"u_claimno",sr - 1);
		u_status2 = getTableInput(table,"u_status",sr - 1);
		u_amount2 = getTableInputNumeric(table,"u_amount",sr - 1);
		u_hmo2 = getTableInput(table,"u_hmo",sr - 1);
		u_memberid2 = getTableInput(table,"u_memberid",sr - 1);
		u_membername2 = getTableInput(table,"u_membername",sr - 1);
		u_membertype2 = getTableInput(table,"u_membertype",sr - 1);
		u_insdesc2 = u_inscode2 + " - " + page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode2+"'");
		u_membertypedesc2 = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype2+"'");
		
		if (u_status2!="")	 {
			page.statusbar.showError("You cannot move health benefits, if status is already set.");
			return;
		}
		
		setTableKey(table,"keys",keys,sr - 1);
		setTableInput(table,"u_inscode",u_inscode,sr - 1,u_insdesc);
		setTableInput(table,"u_claimno",u_claimno,sr - 1);
		setTableInput(table,"u_status",u_status,sr - 1);
		setTableInputAmount(table,"u_amount",u_amount,sr - 1);
		setTableInput(table,"u_hmo",u_hmo,sr - 1);
		setTableInput(table,"u_memberid",u_memberid,sr - 1);
		setTableInput(table,"u_membername",u_membername,sr - 1);
		setTableInput(table,"u_membertype",u_membertype,sr - 1,u_membertypedesc);
		
		setTableKey(table,"keys",keys2,sr);
		setTableInput(table,"u_inscode",u_inscode2,sr,u_insdesc2);
		setTableInput(table,"u_claimno",u_claimno2,sr);
		setTableInput(table,"u_status",u_status2,sr);
		setTableInputAmount(table,"u_amount",u_amount2,sr);
		setTableInput(table,"u_hmo",u_hmo2,sr);
		setTableInput(table,"u_memberid",u_memberid2,sr);
		setTableInput(table,"u_membername",u_membername2,sr);
		setTableInput(table,"u_membertype",u_membertype2,sr,u_membertypedesc2);
			
		selectTableRow(table,sr - 1);
		healthinsordered=true;
	}			
}

function u_moveRowDnHealthBenefitsGPSHIS() {
	var table = "T7";
	var rc = getTableRowCount(table),sr=0;
	if (healthinsmodified) {
		page.statusbar.showWarning("Please update page prior to moving up/dn health benefits.");
		return;
	}
	if (rc==0) return;
	sr = getTableSelectedRow(table);	
	var keys,u_inscode,u_hmo,u_insdesc,u_memberid,u_membername,u_membertype,u_membertypedesc;
	var keys2,u_inscode2,u_hmo2,u_insdesc2,u_memberid2,u_membername2,u_membertype2,u_membertypedesc2;
	if (sr!=rc) {
		keys = getTableKey(table,"keys",sr);
		u_inscode = getTableInput(table,"u_inscode",sr);
		u_claimno = getTableInput(table,"u_claimno",sr);
		u_status = getTableInput(table,"u_status",sr);
		u_amount = getTableInputNumeric(table,"u_amount",sr);
		u_hmo = getTableInput(table,"u_hmo",sr);
		u_memberid = getTableInput(table,"u_memberid",sr);
		u_membername = getTableInput(table,"u_membername",sr);
		u_membertype = getTableInput(table,"u_membertype",sr);
		u_insdesc = u_inscode + " - " + page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode+"'");
		u_membertypedesc = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype+"'");
		
		if (u_status!="")	 {
			page.statusbar.showError("You cannot move health benefits, if status is already set.");
			return;
		}
		
		keys2 = getTableKey(table,"keys",sr + 1);
		u_inscode2 = getTableInput(table,"u_inscode",sr + 1);
		u_claimno2 = getTableInput(table,"u_claimno",sr + 1);
		u_status2 = getTableInput(table,"u_status",sr + 1);
		u_amount2 = getTableInputNumeric(table,"u_amount",sr + 1);
		u_hmo2 = getTableInput(table,"u_hmo",sr + 1);
		u_memberid2 = getTableInput(table,"u_memberid",sr + 1);
		u_membername2 = getTableInput(table,"u_membername",sr + 1);
		u_membertype2 = getTableInput(table,"u_membertype",sr + 1);
		u_insdesc2 = u_inscode2 + " - " + page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode2+"'");
		u_membertypedesc2 = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype2+"'");

		if (u_status2!="")	 {
			page.statusbar.showError("You cannot move health benefits, if status is already set.");
			return;
		}

		setTableKey(table,"keys",keys,sr + 1);
		setTableInput(table,"u_inscode",u_inscode,sr + 1,u_insdesc);
		setTableInput(table,"u_claimno",u_claimno,sr + 1);
		setTableInput(table,"u_status",u_status,sr + 1);
		setTableInputAmount(table,"u_amount",u_amount,sr + 1);
		setTableInput(table,"u_hmo",u_hmo,sr + 1);
		setTableInput(table,"u_memberid",u_memberid,sr + 1);
		setTableInput(table,"u_membername",u_membername,sr + 1);
		setTableInput(table,"u_membertype",u_membertype,sr + 1,u_membertypedesc);
		
		setTableKey(table,"keys",keys2,sr);
		setTableInput(table,"u_inscode",u_inscode2,sr,u_insdesc2);
		setTableInput(table,"u_claimno",u_claimno2,sr);
		setTableInput(table,"u_status",u_status2,sr);
		setTableInputAmount(table,"u_amount",u_amount2,sr);
		setTableInput(table,"u_hmo",u_hmo2,sr);
		setTableInput(table,"u_memberid",u_memberid2,sr);
		setTableInput(table,"u_membername",u_membername2,sr);
		setTableInput(table,"u_membertype",u_membertype2,sr,u_membertypedesc2);
			
		selectTableRow(table,sr + 1);
		healthinsordered=true;
	}	
}

function u_deleteHealthBenefitsGPSHIS() {
	var table = "T7";
	var sr = getTableSelectedRow(table);	
	if (sr==0) return;
	if (getTableInput(table,"u_status",sr)!="")	 {
		page.statusbar.showError("You cannot delete health benefits, if status is already set.");
		return false;
	}
	deleteTableRow(table);
	return false;
}

function OpenLnkBtnTrxNo(targetId) {
	switch (getTableElementValue(targetId,"T11","u_doctype")) {
		case "CHRG":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hischarges' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "ADJ":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hispriceadjs' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "CM":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hiscredits' + '' + '&targetId=' + targetId ,targetId);
			break;
		case "DP":
			OpenLnkBtn(1024,680,'./udo.php?objectcode=u_hispos' + '' + '&targetId=' + targetId ,targetId);
			break;
	}
}

function u_phicHealthBenefitsGPSHIS() {
	if (getInput("docstatus")=="CN") {
		alert("Cannot create Philhealth deduction on cancelled bill.");	
		return false;
	}
	
	var targetObjectId = 'u_hisphicclaims';
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisphicclaims' + '' + '&edtopt=integrated&targetId=' + targetObjectId ,targetObjectId);
}

function u_pkgHealthBenefitsGPSHIS() {
	if (getInput("docstatus")=="CN") {
		alert("Cannot create Package deduction on cancelled bill.");	
		return false;
	}
	
	var targetObjectId = 'u_hispkgclaims';
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispkgclaims' + '' + '&edtopt=integrated&targetId=' + targetObjectId ,targetObjectId);
}

function u_btGPSHIS() {
	var rc = getTableSelectedRow("T8");
	if (rc==0) {
		page.statusbar.showWarning("Please select the document to transfer the balance.");
		return false;
	}

	if (getTableInput("T8","u_status",rc)=="CN") {
		page.statusbar.showWarning("You cannot transfer the balance if document was cancelled.");
		return false;
	}
	targetObjectId = '';
	OpenLnkBtn(1024,320,'./udo.php?objectcode=u_hispronotes' + '' + '&edtopt=bt&btrefno='+getTableInput("T8","u_pnno",rc)+'&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnClaimNo(targetId) {
	var targetObjectId = 'u_hisphicclaims';
	switch (getTableElementValue(targetId,"T8","u_guarantorcode")) {
		case "PHIC":
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisphicclaims' + '' + '&edtopt=integrated&targetId=' + targetId ,targetId);
			break;
		default:
			OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispkgclaims' + '' + '&edtopt=integrated&targetId=' + targetId ,targetId);
			//page.statusbar.showWarning("No available details for this claim mo.");
			break;
	}
	
}

function OpenLnkBtnReqNoGPSHIS(targetId) {
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisrequests' + '&df_u_trxtype=' + getTableElementValue(targetId,"T112","trxtype") + '&edtopt=integrated&targetId=' + targetId ,targetId);
}

function u_addchrgGPSHIS() {
	var targetObjectId = 'U_HISCHARGES';
	OpenLnkBtn(1024,500,'./udo.php?objectcode=U_HISCHARGES' + '&df_u_trxtype=BILLING&targetId=' + targetObjectId ,targetObjectId);
}
function u_addadjGPSHIS() {
	var targetObjectId = 'U_HISPRICEADJS';
	rc = getTableSelectedRow("T11");
	if (rc==0) {
		page.statusbar.showWarning("Please select the charge slip to adjust.");
		return false;
	}
	if (getTableInput("T11","u_doctype",rc)!="CHRG") {
		page.statusbar.showWarning("Please select a charge slip to adjust.");
		return false;
	}
	OpenLnkBtn(1024,500,'./udo.php?objectcode=U_HISPRICEADJS' + '&df_u_trxtype=BILLING&targetId=' + targetObjectId ,targetObjectId);
}

function getNextQueGPSHIS(docno) {
	var msgcnt = 0;	
	showAjaxProcess();
	try {
		//alert('querying');
		http = getHTTPObject(); 
		//alert( "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"));
		http.open("GET", "udp.php?objectcode=u_ajaxnextqueueno&terminalid="+getInput("u_terminalid"), false);
		http.send(null);
		var result = parseInt(http.responseText.trim());
		hideAjaxProcess();
		if (result>0) {
			setInput("u_queue",result);
		} else alert(http.responseText.trim());
		http.send(null);
	} catch (theError) {
		hideAjaxProcess();
	}	
}