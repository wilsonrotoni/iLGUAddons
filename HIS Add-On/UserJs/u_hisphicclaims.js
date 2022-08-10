// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

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
//page.tables.events.add.select('onTableSelectRowGPSHIS');
page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

function onPageLoadGPSHIS() {
	try {
		if (getVar("formSubmitAction")=="a" && getInput("u_billno")=="") {
			setInput("u_billno",window.opener.getInput("docno"));
			setInput("u_docdate",window.opener.getInput("u_enddate"));
			setInput("u_reftype",window.opener.getInput("u_reftype"));
			setInput("u_patientid",window.opener.getInput("u_patientid"));
			setInput("u_patientname",window.opener.getInput("u_patientname"));
			setInput("u_gender",window.opener.getInput("u_gender"));
			setInput("u_age",window.opener.getInput("u_age"));
			setInput("u_startdate",window.opener.getInput("u_startdate"));
			setInput("u_enddate",window.opener.getInput("u_enddate"));
			setInput("u_refno",window.opener.getInput("u_refno"),true);

			if (getInput("u_billno")!="") {
				result = page.executeFormattedQuery("select e.u_memberid, e.u_membertype, e.u_membername from u_hisbills a left join u_hisbillins e on e.company=a.company and e.branch=a.branch and e.docid=a.docid and e.u_inscode='PHIC' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_billno")+"'");
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("u_phicno", result.childNodes.item(0).getAttribute("u_memberid"));
						setInput("u_memberid", result.childNodes.item(0).getAttribute("u_memberid"));
						setInput("u_membername", result.childNodes.item(0).getAttribute("u_membername"));
						setInput("u_membertype", result.childNodes.item(0).getAttribute("u_membertype"));
					}
				} else {
					page.statusbar.showError("Error retrieving Bill  record. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			}
			
			if (getInput("u_phicno")=="") {
				if (getInput("u_reftype")=="IP") {
					result = page.executeFormattedQuery("select a.u_starttime, a.u_endtime, a.u_roomtype, a.u_doctorid, a.u_remarks, e.u_memberid, e.u_membertype, e.u_membername from u_hisips a left join u_hisipins e on e.company=a.company and e.branch=a.branch and e.docid=a.docid and e.u_inscode='PHIC' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"'");
				} else {
					result = page.executeFormattedQuery("select a.u_starttime, a.u_endtime, '' as u_roomtype, a.u_doctorid, a.u_remarks, e.u_memberid, e.u_membertype, e.u_membername from u_hisops a left join u_hisopins e on e.company=a.company and e.branch=a.branch and e.docid=a.docid and e.u_inscode='PHIC' where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"'");
				}
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						setInput("u_phicno", result.childNodes.item(0).getAttribute("u_memberid"));
						setInput("u_memberid", result.childNodes.item(0).getAttribute("u_memberid"));
						setInput("u_membername", result.childNodes.item(0).getAttribute("u_membername"));
						setInput("u_membertype", result.childNodes.item(0).getAttribute("u_membertype"));
					}
				} else {
					page.statusbar.showError("Error retrieving Registration record. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			}
			
			var data2 = new Array();
			result = page.executeFormattedQuery("select a.code, a.u_icdgroup, ifnull(b.u_1stcr,0) as u_1stcr, ifnull(b.u_1stpf,0) as u_1stpf, ifnull(b.u_1sthc,0) as u_1sthc, ifnull(b.u_2ndcr,0) as u_2ndcr, ifnull(b.u_2ndpf,0) as u_2ndpf, ifnull(b.u_2ndhc,0) as u_2ndhc, ifnull(b.u_allowreferral,0) as u_allowreferral, ifnull(b.u_maxcycle,0) as u_maxcycle from u_hismedrecs aa inner join u_hismedrecicds bb on bb.company=aa.company and bb.branch=aa.branch and bb.docid=aa.docid inner join u_hisicds a on a.code=bb.u_icdcode left join u_hisphicicds b on b.code=a.code where aa.u_reftype='"+getInput("u_reftype")+"' and aa.u_refno='"+getInput("u_refno")+"'");	
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (iii = 0; iii < result.childNodes.length; iii++) {	
						data2["u_icdcode"] = result.childNodes.item(iii).getAttribute("code");
						data2["u_icdgroup"] = result.childNodes.item(iii).getAttribute("u_icdgroup");
						data2["u_1stcr"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_1stcr"));
						data2["u_1stcrpf"] = result.childNodes.item(iii).getAttribute("u_1stpf");
						data2["u_1stcrhc"] = result.childNodes.item(iii).getAttribute("u_1sthc");
						data2["u_2ndcr"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_2ndcr"));
						data2["u_2ndcrpf"] = result.childNodes.item(iii).getAttribute("u_2ndpf");
						data2["u_2ndcrhc"] = result.childNodes.item(iii).getAttribute("u_2ndhc");
						data2["u_allowreferral"] = result.childNodes.item(iii).getAttribute("u_allowreferral");
						data2["u_maxcycle"] = result.childNodes.item(iii).getAttribute("u_maxcycle");
						
						insertTableRowFromArray("T1",data2);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving ICD records. Try Again, if problem persists, check the connection.");	
				return false;
			}						
			
			var data = new Array();
			var result = page.executeFormattedQuery("select bb.u_date, bb.u_laterality, a.code, a.name, ifnull(b.u_1stcr,0) as u_1stcr, ifnull(b.u_1stpf,0) as u_1stpf, ifnull(b.u_1sthc,0) as u_1sthc, ifnull(b.u_2ndcr,0) as u_2ndcr, ifnull(b.u_2ndpf,0) as u_2ndpf, ifnull(b.u_2ndhc,0) as u_2ndhc, ifnull(b.u_withlateral,0) as u_withlateral, ifnull(b.u_repeatproc,0) as u_repeatproc, b.u_repeatproctype, ifnull(b.u_maxcycle,0) as u_maxcycle from u_hismedrecs aa inner join u_hismedrecirvs bb on bb.company=aa.company and bb.branch=aa.branch and bb.docid=aa.docid inner join u_hisrvs a on a.code=bb.u_rvscode left join u_hisphicrvs b on b.code=a.code where aa.u_reftype='"+getInput("u_reftype")+"' and aa.u_refno='"+getInput("u_refno")+"'");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (iii = 0; iii < result.childNodes.length; iii++) {					
						data["u_rvscode"] = result.childNodes.item(iii).getAttribute("code");
						data["u_rvsdesc"] = result.childNodes.item(iii).getAttribute("name");
						data["u_1stcr"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_1stcr"));
						data["u_1stcrpf"] = result.childNodes.item(iii).getAttribute("u_1stpf");
						data["u_1stcrhc"] = result.childNodes.item(iii).getAttribute("u_1sthc");
						data["u_2ndcr"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_2ndcr"));
						data["u_2ndcrpf"] = result.childNodes.item(iii).getAttribute("u_2ndpf");
						data["u_2ndcrhc"] = result.childNodes.item(iii).getAttribute("u_2ndhc");
						data["u_withlateral"] = result.childNodes.item(iii).getAttribute("u_withlateral");
						data["u_repeatproc"] = result.childNodes.item(iii).getAttribute("u_repeatproc");
						data["u_repeatproctype"] = result.childNodes.item(iii).getAttribute("u_repeatproctype");
						data["u_maxcycle"] = result.childNodes.item(iii).getAttribute("u_maxcycle");
						
						data["u_date"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("u_date"));
						data["u_laterality"] = result.childNodes.item(iii).getAttribute("u_laterality");
						
						insertTableRowFromArray("T1",data);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving RVS records. Try Again, if problem persists, check the connection.");	
				return false;
			}						

			result = page.executeFormattedQuery("select sum(u_acthc) as u_acthc, sum(u_bdischc) as u_bdischc from ( select sum(b.u_amount+b.u_comdisc) as u_acthc, sum(b.u_comdisc+b.u_discamount) as u_bdischc from u_hisbills a inner join u_hisbillrooms b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"' union all select sum(b.u_amount+b.u_comdisc) as u_acthc, sum(b.u_comdisc+b.u_discamount) as u_bdischc from u_hisbills a inner join u_hisbillsplrooms b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"' union all select sum(b.u_amount+b.u_comdisc) as u_acthc, sum(b.u_comdisc+b.u_discamount) as u_bdischc from u_hisbills a inner join u_hisbilllabtests b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"' union all select sum(b.u_amount+b.u_comdisc) as u_acthc, sum(b.u_comdisc+b.u_discamount) as u_bdischc from u_hisbills a inner join u_hisbillmedsups b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"' union all select sum(b.u_amount+b.u_comdisc) as u_acthc, sum(b.u_comdisc+b.u_discamount) as u_bdischc from u_hisbills a inner join u_hisbillmiscs b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"') as x");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					setInputAmount("u_acthc", result.childNodes.item(0).getAttribute("u_acthc"));
					//setInputAmount("u_bdischc", result.childNodes.item(0).getAttribute("u_bdischc"),true);
				}
			} else {
				page.statusbar.showError("Error retrieving Charge records. Try Again, if problem persists, check the connection.");	
				return false;
			}						
			setInputAmount("u_bdischc",getInputNumeric("u_bdischc")+window.opener.getInputNumeric("u_discamount"));
			setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));

			var claimpf = 0;
			if (parseFloat(getPrivate("claimpf"))>0) {
				claimpf = parseFloat(getPrivate("claimpf"));
			}
			var data3 = new Array();
			result = page.executeFormattedQuery("select c.code, c.name, b.u_itemcode, b.u_amount+b.u_comdisc as u_actpf, b.u_comdisc+b.u_discamount as u_bdiscpf,e.u_memberid from u_hisbills a inner join u_hisbillconsultancyfees b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join u_hisdoctors c on c.code=b.u_doctorid inner join u_hisitems d on d.code=b.u_itemcode and d.u_group='PRF' left join u_hisdoctorhealthins e on e.code=c.code where a.docno='"+getInput("u_billno")+"'");	
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					for (iii = 0; iii < result.childNodes.length; iii++) {	
						data3["u_doctorid"] = result.childNodes.item(iii).getAttribute("code");
						data3["u_doctorname"] = result.childNodes.item(iii).getAttribute("name");
						data3["u_doctorservice"] = result.childNodes.item(iii).getAttribute("u_itemcode");
						data3["u_doctorphicno"] = result.childNodes.item(iii).getAttribute("u_memberid");
						data3["u_actpf"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_actpf"));
						data3["u_bdiscpf"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_bdiscpf"));
						data3["u_claimpf"] = formatNumericAmount(claimpf);
						data3["u_balpf"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_actpf")-result.childNodes.item(iii).getAttribute("u_bdiscpf")-claimpf);
						insertTableRowFromArray("T2",data3);
					}
				}
			} else {
				page.statusbar.showError("Error retrieving Consultancies records. Try Again, if problem persists, check the connection.");	
				return false;
			}						
			
			disableInput("u_reftype");
			disableInput("u_refno");
			disableInput("u_lastname");
			disableInput("u_firstname");
			disableInput("u_middlename");
			disableInput("u_extname");
			//disableInput("u_phicno");
			disableInput("u_acthc");
			disableInput("u_bdischc");
			
			disableTableInput("T1","u_icdgroup");
			disableTableInput("T1","u_icdcode");
			disableTableInput("T1","u_rvsdesc");
			disableTableInput("T1","u_rvscode");
			disableTableInput("T1","u_date");
			disableTableInput("T1","u_laterality");

			disableTableInput("T2","u_doctorname");
			disableTableInput("T2","u_doctorservice");
			disableTableInput("T2","u_actpf");
			disableTableInput("T2","u_bdiscpf");
			//selectTab("tab1",2);
		}
		
	} catch (theError) {
	}
	
	try {
		if (getVar("formSubmitAction")=="a") {
			showAjaxProcess();
			var data4 = new Array();
			result = page.executeFormattedQuery("select a.u_docdate, datediff('"+formatDateToDB(getInput("u_docdate"))+"',a.u_docdate) as u_claimdays, b.u_is1stcr, b.u_is2ndcr, b.u_icdgroup, b.u_icdcode, b.u_rvsdesc, b.u_rvscode, b.u_date, b.u_laterality, b.u_repeatproc, b.u_repeatcycle from u_hisphicclaims a inner join u_hisphicclaimdiagnosis b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_is1stcr=1 or b.u_is2ndcr=1) where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"'  and a.u_patientname='"+getInput("u_patientname")+"' and a.docstatus<>'CN' order by a.u_docdate desc");
			if (result.getAttribute("result")!= "-1") {
				if (parseInt(result.getAttribute("result"))>0) {
					showPopupFrame("popupFrameClaimedBenefits",true);
					for (iii = 0; iii < result.childNodes.length; iii++) {	
						data4["u_is1stcr"] = "No";
						data4["u_is2ndcr"] = "No";
						data4["u_repeatproc"] = "No";
						if (result.childNodes.item(iii).getAttribute("u_is1stcr")=="1") data4["u_is1stcr"] = "Yes";
						if (result.childNodes.item(iii).getAttribute("u_is2ndcr")=="1") data4["u_is2ndcr"] = "Yes";
						if (result.childNodes.item(iii).getAttribute("u_repeatproc")=="1") data4["u_repeatproc"] = "Yes";
						data4["u_claimdate"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("u_docdate"));
						data4["u_claimdays"] = result.childNodes.item(iii).getAttribute("u_claimdays");
						data4["u_icdgroup"] = result.childNodes.item(iii).getAttribute("u_icdgroup");
						data4["u_icdcode"] = result.childNodes.item(iii).getAttribute("u_icdcode");
						data4["u_rvscode"] = result.childNodes.item(iii).getAttribute("u_rvscode");
						data4["u_rvsdesc"] = result.childNodes.item(iii).getAttribute("u_rvsdesc");
						data4["u_date"] = formatDateToHttp(result.childNodes.item(iii).getAttribute("u_date"));
						data4["u_repeatcycle"] = result.childNodes.item(iii).getAttribute("u_repeatcycle");
						data4["u_laterality"] = result.childNodes.item(iii).getAttribute("u_laterality");
						insertTableRowFromArray("T120",data4);
					}
				}
			} else {
				hideAjaxProcess();
				page.statusbar.showError("Error retrieving claimed benefits records. Try Again, if problem persists, check the connection.");	
				return false;
			}							
			hideAjaxProcess();
		}
	} catch (theError) {
	}
	//
	
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_pan")) return false;
		
		if (isInputEmpty("u_lastname")) return false;
		if (isInputEmpty("u_firstname")) return false;
		if (getInput("u_middlename")=="") {
			if (window.confirm("Middle Name was not entered. Are you sure patient has no middle name?")==false) {
				focusInput("u_middlename");
				return false;
			}
		}
		if (isInputEmpty("u_phicno")) return false;
		
		if (isInputEmpty("u_birthdate",null,null,"tab1",0)) return false;
		if (isInputEmpty("u_gender",null,null,"tab1",0)) return false;

		if (isInputEmpty("u_membertype",null,null,"tab1",0)) return false;
		
		if (!isInputChecked("u_ismember")) {
			if (isInputEmpty("u_memberid",null,null,"tab1",0)) return false;
			if (isInputEmpty("u_membername",null,null,"tab1",0)) return false;
		}

		if (isInputChecked("u_istransferred")) {
			if (isInputEmpty("u_hci_fr_name",null,null,"tab1",1)) return false;
		}
		
		if (isInputEmpty("u_startdate",null,null,"tab1",1)) return false;
		if (isInputEmpty("u_starttime",null,null,"tab1",1)) return false;
		if (isInputEmpty("u_enddate",null,null,"tab1",1)) return false;
		if (isInputEmpty("u_endtime",null,null,"tab1",1)) return false;

		if (getInput("u_expired")=="1") {
			if (isInputEmpty("u_expiredate",null,null,"tab1",1)) return false;
			if (isInputEmpty("u_expiretime",null,null,"tab1",1)) return false;
		}

		if (isInputChecked("u_isreferred")) {
			if (isInputEmpty("u_hci_to_name",null,null,"tab1",1)) return false;
		}
		
		if (getInput("u_billno")=="") {
			if (isInputEmpty("u_typeofdischarge",null,null,"tab1",1)) return false;
		}
		if (isInputEmpty("u_initialremarks",null,null,"tab1",1)) return false;
		
		if (getInputNumeric("u_claimpf")>getInputNumeric("u_totalpf")) {
			selectTab("tab1",4);
			page.statusbar.showError("PF Benefits to Claim cannot be more than Total Benefits allowed.");
			return false;
		}
		if (isTableInput("T1","u_icdcode")) {
			if (getTableInput("T1","u_icdcode")!="" || getTableInput("T1","u_rvscode")!="") {
				selectTab("tab1",2);
				page.statusbar.showError("A Diagnosis record is being added/edited.");
				return false;
			}
		}
		if (isTableInput("T2","u_doctorname")) {
			if (getTableInput("T2","u_doctorname")!="") {
				selectTab("tab1",4);
				page.statusbar.showError("A Professional Fee record is being added/edited.");
				return false;
			}
		}
		if (getVar("edtopt")=="integrated") {
			if (isInputNegative("u_totalamt",null,null,"tab1",2)) return false;
		} else {
			if (isInputNegative("u_totalamt",null,null,"tab1",4)) return false;
		}
		
		if ((action=="a" || (action=="sc" && getInput("docstatus")!="D")) && isTableInput("T50","userid")) {
			if (getTableInput("T50","userid")=="") {
				showPopupFrame("popupFrameAuthentication",true);
				focusTableInput("T50","userid");
				return false;
			}
		}
		
	} else if (action=="cnd") {
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

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		if (success) {	
			window.opener.setKey("keys",window.opener.getInput("docno"));
			//window.opener.setInput("u_tab1selected",1);
			window.opener.formEdit();
			window.close();
		}
	} catch(TheError) {
	}
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
				formSubmit();
			}
			break;
		default:
			switch (column) {
				case "u_lastname":
				case "u_firstname":
				case "u_middlename":
					var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
					if (sc_press=="TAB") {
						switch (column) {
							case "u_lastname": focusInput("u_firstname"); break;
							case "u_firstname": focusInput("u_middlename"); break;
							case "u_middlename": focusInput("u_extname"); break;
						}
					}
					break;
			}
			break;
	}
					
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_icdgroup":
					setTableInput(table,"u_icdcode","");
					setTableInput(table,"u_rvscode","");
					setTableInput(table,"u_rvsdesc","");
					setTableInput(table,"u_date","");
					setTableInput(table,"u_laterality","");
					setTableInputAmount(table,"u_1stcr",0);
					setTableInputAmount(table,"u_1stcrpf",0);
					setTableInputAmount(table,"u_1stcrhc",0);
					setTableInputAmount(table,"u_2ndcr",0);
					setTableInputAmount(table,"u_2ndcrpf",0);
					setTableInputAmount(table,"u_2ndcrhc",0);
					setTableInput(table,"u_allowreferral",0);
					setTableInput(table,"u_withlateral",0);
					break;
				case "u_icdcode":
					if (isInputEmpty("u_enddate",null,null,"tab1",1)) return false;
					setTableInput(table,"u_rvscode","");
					setTableInput(table,"u_rvsdesc","");
					setTableInput(table,"u_date","");
					setTableInput(table,"u_laterality","");
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select a.u_icdgroup, ifnull(b.u_1stcr,0) as u_1stcr, ifnull(b.u_1stpf,0) as u_1stpf, ifnull(b.u_1sthc,0) as u_1sthc, ifnull(b.u_2ndcr,0) as u_2ndcr, ifnull(b.u_2ndpf,0) as u_2ndpf, ifnull(b.u_2ndhc,0) as u_2ndhc, ifnull(b.u_allowreferral,0) as u_allowreferral, ifnull(b.u_maxcycle,0) as u_maxcycle, ifnull(b.u_interval,0) as u_interval, xa.docno as u_lastclaimno, xa.u_enddate as u_lastclaimdate from u_hisicds a left join u_hisphicicds b on b.code=a.code left join u_hisphicclaims xa on xa.company='"+getGlobal("company")+"' and xa.branch='"+getGlobal("branch")+"' and xa.u_patientname='"+getInput("u_patientname")+"' and xa.docstatus<>'CN' left join u_hisphicclaimdiagnosis xb on xb.company=xa.company and xb.branch=xa.branch and xb.docid=xa.docid and xb.u_icdgroup=a.u_icdgroup where a.code='"+getTableInput(table,column)+"' order by xa.u_enddate desc limit 1");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_icdgroup",result.childNodes.item(0).getAttribute("u_icdgroup"));
								setTableInputAmount(table,"u_1stcr",result.childNodes.item(0).getAttribute("u_1stcr"));
								setTableInputAmount(table,"u_1stcrpf",result.childNodes.item(0).getAttribute("u_1stpf"));
								setTableInputAmount(table,"u_1stcrhc",result.childNodes.item(0).getAttribute("u_1sthc"));
								setTableInputAmount(table,"u_2ndcr",result.childNodes.item(0).getAttribute("u_2ndcr"));
								setTableInputAmount(table,"u_2ndcrpf",result.childNodes.item(0).getAttribute("u_2ndpf"));
								setTableInputAmount(table,"u_2ndcrhc",result.childNodes.item(0).getAttribute("u_2ndhc"));
								setTableInput(table,"u_allowreferral",result.childNodes.item(0).getAttribute("u_allowreferral"));
								setTableInput(table,"u_maxcycle",result.childNodes.item(0).getAttribute("u_maxcycle"));
								setTableInput(table,"u_interval",result.childNodes.item(0).getAttribute("u_interval"));
								setTableInput(table,"u_lastclaimno",result.childNodes.item(0).getAttribute("u_lastclaimno"));
								setTableInput(table,"u_lastclaimdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_lastclaimdate")));
								setTableInput(table,"u_lastclaimdays",0);
								if (getTableInput(table,"u_lastclaimdate")!="") {
									setTableInput(table,"u_lastclaimdays",datedifference("d",getTableInput(table,"u_lastclaimdate"),getInput("u_enddate")));
								}
							} else {
								setTableInputAmount(table,"u_1stcr",0);
								setTableInputAmount(table,"u_1stcrpf",0);
								setTableInputAmount(table,"u_1stcrhc",0);
								setTableInputAmount(table,"u_2ndcr",0);
								setTableInputAmount(table,"u_2ndcrpf",0);
								setTableInputAmount(table,"u_2ndcrhc",0);
								setTableInput(table,"u_allowreferral",0);
								setTableInput(table,"u_withlateral",0);
								setTableInput(table,"u_maxcycle",0);
								setTableInput(table,"u_interval",0);
								setTableInput(table,"u_lastclaimno","");
								setTableInput(table,"u_lastclaimdate","");
								setTableInput(table,"u_lastclaimdays",0);
								page.statusbar.showError("Invalid ICD record. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setTableInputAmount(table,"u_1stcr",0);
							setTableInputAmount(table,"u_1stcrpf",0);
							setTableInputAmount(table,"u_1stcrhc",0);
							setTableInputAmount(table,"u_2ndcr",0);
							setTableInputAmount(table,"u_2ndcrpf",0);
							setTableInputAmount(table,"u_2ndcrhc",0);
							setTableInput(table,"u_allowreferral",0);
							setTableInput(table,"u_withlateral",0);
							setTableInput(table,"u_maxcycle",0);
							setTableInput(table,"u_interval",0);
							setTableInput(table,"u_lastclaimno","");
							setTableInput(table,"u_lastclaimdate","");
							setTableInput(table,"u_lastclaimdays",0);
							page.statusbar.showError("Error retrieving ICD record. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					} else {
						setTableInputAmount(table,"u_1stcr",0);
						setTableInputAmount(table,"u_1stcrpf",0);
						setTableInputAmount(table,"u_1stcrhc",0);
						setTableInputAmount(table,"u_2ndcr",0);
						setTableInputAmount(table,"u_2ndcrpf",0);
						setTableInputAmount(table,"u_2ndcrhc",0);
						setTableInput(table,"u_allowreferral",0);
						setTableInput(table,"u_withlateral",0);
						setTableInput(table,"u_maxcycle",0);
						setTableInput(table,"u_interval",0);
						setTableInput(table,"u_lastclaimno","");
						setTableInput(table,"u_lastclaimdate","");
						setTableInput(table,"u_lastclaimdays",0);
					}
					break;
				case "u_rvscode":
				case "u_rvsdesc":
					if (isInputEmpty("u_enddate",null,null,"tab1",1)) return false;
					setTableInput(table,"u_icdgroup","");
					setTableInput(table,"u_icdcode","");
					setTableInput(table,"u_date","");
					setTableInput(table,"u_laterality","");
					disableTableInput(table,"u_repeatproctype");
					disableTableInput(table,"u_repeatcycle");
					disableTableInput(table,"u_repeatdates");
					
					if (getTableInput(table,column)!="") {
						if (column=="u_rvscode") {
							result = page.executeFormattedQuery("select a.code, a.name, ifnull(b.u_1stcr,0) as u_1stcr, ifnull(b.u_1stpf,0) as u_1stpf, ifnull(b.u_1sthc,0) as u_1sthc, ifnull(b.u_2ndcr,0) as u_2ndcr, ifnull(b.u_2ndpf,0) as u_2ndpf, ifnull(b.u_2ndhc,0) as u_2ndhc, ifnull(b.u_withlateral,0) as u_withlateral, ifnull(b.u_repeatproc,0) as u_repeatproc, b.u_repeatproctype, ifnull(b.u_maxcycle,0) as u_maxcycle, ifnull(b.u_interval,0) as u_interval, ifnull(b.u_latinterval,0) as u_latinterval from u_hisrvs a left join u_hisphicrvs b on b.code=a.code where a.code='"+getTableInput(table,column)+"'");	
						} else {
							result = page.executeFormattedQuery("select a.code, a.name, ifnull(b.u_1stcr,0) as u_1stcr, ifnull(b.u_1stpf,0) as u_1stpf, ifnull(b.u_1sthc,0) as u_1sthc, ifnull(b.u_2ndcr,0) as u_2ndcr, ifnull(b.u_2ndpf,0) as u_2ndpf, ifnull(b.u_2ndhc,0) as u_2ndhc, ifnull(b.u_withlateral,0) as u_withlateral, ifnull(b.u_repeatproc,0) as u_repeatproc, b.u_repeatproctype, ifnull(b.u_maxcycle,0) as u_maxcycle, ifnull(b.u_interval,0) as u_interval, ifnull(b.u_latinterval,0) as u_latinterval from u_hisrvs a left join u_hisphicrvs b on b.code=a.code where a.name='"+getTableInput(table,column)+"'");	
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_rvscode",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_rvsdesc",result.childNodes.item(0).getAttribute("name"));
								setTableInputAmount(table,"u_1stcr",result.childNodes.item(0).getAttribute("u_1stcr"));
								setTableInputAmount(table,"u_1stcrpf",result.childNodes.item(0).getAttribute("u_1stpf"));
								setTableInputAmount(table,"u_1stcrhc",result.childNodes.item(0).getAttribute("u_1sthc"));
								setTableInputAmount(table,"u_2ndcr",result.childNodes.item(0).getAttribute("u_2ndcr"));
								setTableInputAmount(table,"u_2ndcrpf",result.childNodes.item(0).getAttribute("u_2ndpf"));
								setTableInputAmount(table,"u_2ndcrhc",result.childNodes.item(0).getAttribute("u_2ndhc"));
								setTableInput(table,"u_withlateral",result.childNodes.item(0).getAttribute("u_withlateral"));
								setTableInput(table,"u_repeatproc",result.childNodes.item(0).getAttribute("u_repeatproc"));
								setTableInput(table,"u_repeatproctype",result.childNodes.item(0).getAttribute("u_repeatproctype"));
								setTableInput(table,"u_maxcycle",result.childNodes.item(0).getAttribute("u_maxcycle"));
								setTableInput(table,"u_interval",result.childNodes.item(0).getAttribute("u_interval"));
								setTableInput(table,"u_latinterval",result.childNodes.item(0).getAttribute("u_latinterval"));
								
								var result = page.executeFormattedQuery("select 1 as u_type, xa.docno as u_lastclaimno, max(xa.u_enddate) as u_lastclaimdate from u_hisphicclaims xa  left join u_hisphicclaimdiagnosis xb on xb.company=xa.company and xb.branch=xa.branch and xb.docid=xa.docid and xb.u_rvscode='"+getTableInput(table,column)+"' WHERE xa.company='"+getGlobal("company")+"' and xa.branch='"+getGlobal("branch")+"' and xa.u_patientname='"+getInput("u_patientname")+"' and xa.docstatus<>'CN' union all select 2 as u_type, xa.docno as u_lastclaimno, max(xa.u_enddate) as u_lastclaimdate from u_hisphicclaims xa left join u_hisphicclaimdiagnosis xb on xb.company=xa.company and xb.branch=xa.branch and xb.docid=xa.docid and xb.u_rvscode='"+getTableInput(table,column)+"' and xb.u_laterality in ('Left','Both') WHERE xa.company='"+getGlobal("company")+"' and xa.branch='"+getGlobal("branch")+"' and xa.u_patientname='"+getInput("u_patientname")+"' and xa.docstatus<>'CN' union all select 3 as u_type, xa.docno as u_lastclaimno, max(xa.u_enddate) as u_lastclaimdate from u_hisphicclaims xa  left join u_hisphicclaimdiagnosis xb on xb.company=xa.company and xb.branch=xa.branch and xb.docid=xa.docid and xb.u_rvscode='"+getTableInput(table,column)+"' and xb.u_laterality in ('Right','Both') WHERE xa.company='"+getGlobal("company")+"' and xa.branch='"+getGlobal("branch")+"' and xa.u_patientname='"+getInput("u_patientname")+"' and xa.docstatus<>'CN'");
								
								if (parseInt(result.getAttribute("result"))>0) {
									for (iii = 0; iii < result.childNodes.length; iii++) {	
										switch (result.childNodes.item(iii).getAttribute("u_type")) {
											case "1":
												setTableInput(table,"u_lastclaimno",result.childNodes.item(iii).getAttribute("u_lastclaimno"));
												setTableInput(table,"u_lastclaimdate",formatDateToHttp(result.childNodes.item(iii).getAttribute("u_lastclaimdate")));
												setTableInput(table,"u_lastclaimdays",0);
												if (getTableInput(table,"u_lastclaimdate")!="") {
													setTableInput(table,"u_lastclaimdays",datedifference("d",getTableInput(table,"u_lastclaimdate"),getInput("u_enddate")));
												}
												break;
											case "2":
												setTableInput(table,"u_lastclaimllatno",result.childNodes.item(iii).getAttribute("u_lastclaimno"));
												setTableInput(table,"u_lastclaimllatdate",formatDateToHttp(result.childNodes.item(iii).getAttribute("u_lastclaimdate")));
												setTableInput(table,"u_lastclaimllatdays",0);
												if (getTableInput(table,"u_lastclaimllatdate")!="") {
													setTableInput(table,"u_lastclaimllatdays",datedifference("d",getTableInput(table,"u_lastclaimdate"),getInput("u_enddate")));
												}
												break;
											case "3":
												setTableInput(table,"u_lastclaimrlatno",result.childNodes.item(iii).getAttribute("u_lastclaimno"));
												setTableInput(table,"u_lastclaimrlatdate",formatDateToHttp(result.childNodes.item(iii).getAttribute("u_lastclaimdate")));
												setTableInput(table,"u_lastclaimrlatdays",0);
												if (getTableInput(table,"u_lastclaimrlatdate")!="") {
													setTableInput(table,"u_lastclaimrlatdays",datedifference("d",getTableInput(table,"u_lastclaimdate"),getInput("u_enddate")));
												}
												break;
										}
									}
								}
								
								if (isTableInputChecked(table,"u_repeatproc")) {
									enableTableInput(table,"u_repeatproctype");
									enableTableInput(table,"u_repeatcycle");
									enableTableInput(table,"u_repeatdates");
								}
								
							} else {
								setTableInput(table,"u_rvscode","");
								setTableInput(table,"u_rvsdesc","");
								setTableInputAmount(table,"u_1stcr",0);
								setTableInputAmount(table,"u_1stcrpf",0);
								setTableInputAmount(table,"u_1stcrhc",0);
								setTableInputAmount(table,"u_2ndcr",0);
								setTableInputAmount(table,"u_2ndcrpf",0);
								setTableInputAmount(table,"u_2ndcrhc",0);
								setTableInput(table,"u_allowreferral",0);
								setTableInput(table,"u_withlateral",0);
								setTableInput(table,"u_repeatproc",0);
								setTableInput(table,"u_repeatcycle",1);
								setTableInput(table,"u_repeatproctype","");
								setTableInput(table,"u_repeatdates","");
								setTableInput(table,"u_maxcycle",0);
								setTableInput(table,"u_interval",0);
								setTableInput(table,"u_latinterval",0);
								setTableInput(table,"u_lastclaimno","");
								setTableInput(table,"u_lastclaimdate","");
								setTableInput(table,"u_lastclaimdays",0);
								page.statusbar.showError("Invalid RVS record. Try Again, if problem persists, check the connection.");	
								return false;
							}
							
						} else {
							setTableInput(table,"u_rvscode","");
							setTableInput(table,"u_rvsdesc","");
							setTableInputAmount(table,"u_1stcr",0);
							setTableInputAmount(table,"u_1stcrpf",0);
							setTableInputAmount(table,"u_1stcrhc",0);
							setTableInputAmount(table,"u_2ndcr",0);
							setTableInputAmount(table,"u_2ndcrpf",0);
							setTableInputAmount(table,"u_2ndcrhc",0);
							setTableInput(table,"u_allowreferral",0);
							setTableInput(table,"u_withlateral",0);
							setTableInput(table,"u_repeatproc",0);
							setTableInput(table,"u_repeatcycle",1);
							setTableInput(table,"u_repeatproctype","");
							setTableInput(table,"u_repeatdates","");
							setTableInput(table,"u_maxcycle",0);
							setTableInput(table,"u_interval",0);
							setTableInput(table,"u_latinterval",0);
							setTableInput(table,"u_lastclaimno","");
							setTableInput(table,"u_lastclaimdate","");
							setTableInput(table,"u_lastclaimdays",0);
							page.statusbar.showError("Error retrieving RVS record. Try Again, if problem persists, check the connection.");	
							return false;
						}						
					} else {
						setTableInput(table,"u_rvscode","");
						setTableInput(table,"u_rvsdesc","");
						setTableInputAmount(table,"u_1stcr",0);
						setTableInputAmount(table,"u_1stcrpf",0);
						setTableInputAmount(table,"u_1stcrhc",0);
						setTableInputAmount(table,"u_2ndcr",0);
						setTableInputAmount(table,"u_2ndcrpf",0);
						setTableInputAmount(table,"u_2ndcrhc",0);
						setTableInput(table,"u_allowreferral",0);
						setTableInput(table,"u_withlateral",0);
						setTableInput(table,"u_repeatproc",0);
						setTableInput(table,"u_repeatcycle",1);
						setTableInput(table,"u_repeatproctype","");
						setTableInput(table,"u_repeatdates","");
						setTableInput(table,"u_maxcycle",0);
						setTableInput(table,"u_interval",0);
						setTableInput(table,"u_latinterval",0);
						setTableInput(table,"u_lastclaimno","");
						setTableInput(table,"u_lastclaimdate","");
						setTableInput(table,"u_lastclaimdays",0);
					}
					break;
				case "u_repeatcycle":
					if (getTableInputNumeric(table,"u_maxcycle")!=0) {
						if (getTableInputNumeric(table,"u_repeatcycle")>getTableInputNumeric(table,"u_maxcycle")) {
							page.statusbar.showError("Maximum cycle allowed is "+getTableInputNumeric(table,"u_maxcycle"));
							return false;
						}
					}
					break;
			}
			break;
		case "T2":
			switch (column) {
				case "u_doctorid":
				case "u_doctorname":
					if (getTableInput(table,column)!="") {
						if (column=="u_doctorid") {
							result = page.executeFormattedQuery("select a.code, a.name, b.u_memberid from u_hisdoctors a left join u_hisdoctorhealthins b on b.code=a.code and b.u_inscode='PHIC' where a.code='"+getTableInput(table,column)+"'");	
						} else {
							result = page.executeFormattedQuery("select a.code, a.name, b.u_memberid from u_hisdoctors a left join u_hisdoctorhealthins b on b.code=a.code and b.u_inscode='PHIC' where a.name='"+getTableInput(table,column)+"'");	
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_doctorid",result.childNodes.item(0).getAttribute("code"));
								setTableInput(table,"u_doctorname",result.childNodes.item(0).getAttribute("name"));
								setTableInput(table,"u_doctorphicno",result.childNodes.item(0).getAttribute("u_memberid"));
							} else {
								setTableInput(table,"u_doctorid","");
								setTableInput(table,"u_doctorname","");
								setTableInput(table,"u_doctorphicno","");
								page.statusbar.showError("Invalid Doctor.");	
								return false;
							}
						} else {
							setTableInput(table,"u_doctorid","");
							setTableInput(table,"u_doctorname","");
							setTableInput(table,"u_doctorphicno","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_doctorid","");
						setTableInput(table,"u_doctorname","");
						setTableInput(table,"u_doctorphicno","");
					}
					break;
				case "u_doctorservice":
					if (getTableInput(table,column)!="") {
						result = page.executeFormattedQuery("select code from u_hisservices where code='"+getTableInput(table,column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setTableInput(table,"u_doctorservice",result.childNodes.item(0).getAttribute("code"));
							} else {
								setTableInput(table,"u_doctorservice","");
								page.statusbar.showError("Invalid Service.");	
								return false;
							}
						} else {
							setTableInput(table,"u_doctorservice","");
							page.statusbar.showError("Error retrieving Service record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setTableInput(table,"u_doctorservice","");
					}
					break;
				case "u_actpf":	
				case "u_bdiscpf":	
				case "u_claimpf":	
					setTableInputAmount(table,"u_balpf",getTableInputNumeric(table,"u_actpf")-getTableInputNumeric(table,"u_bdiscpf")-getTableInputNumeric(table,"u_claimpf"));
					break;
			}
			break;
		default:
			switch (column) {
				case "u_refno":
					if (getInput("u_refno")!="") {
						if (getInput("u_reftype")=="IP") {	
							result = page.executeFormattedQuery("select a.u_department, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_doctorid, a.u_doctorservice, a.u_disccode, a.u_scdisc, a.u_pricelist, a.u_startdate, a.u_address, a.u_phicmemberid, a.u_age_y, b.u_lastname, b.u_firstname, b.u_middlename, b.u_extname, b.u_birthdate, b.u_gender from u_hisips a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"'");
						} else {
							result = page.executeFormattedQuery("select a.u_department, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_doctorid, a.u_doctorservice, a.u_disccode, a.u_scdisc, a.u_pricelist, a.u_startdate, '' as u_address, '' as u_phicmemberid, a.u_age_y, b.u_lastname, b.u_firstname, b.u_middlename, b.u_extname, b.u_birthdate, b.u_gender from u_hisops a, u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"'");	 
						}
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
								setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
								setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
								setInput("u_extname",result.childNodes.item(0).getAttribute("u_extname"));
								setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
								setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
								setInput("u_age",result.childNodes.item(0).getAttribute("u_age_y"));
							} else {
								page.statusbar.showError("Invalid Reg No.");	
								return false;
							}
						} else {
							page.statusbar.showError("Error retrieving Reg No. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						
					}
					break;
				case "u_lastname":
				case "u_firstname":
				case "u_extname":
				case "u_middlename":
					setInput(column,utils.trim(getInput(column)));
					var name = getInput("u_lastname");
					if (getInput("u_firstname")!="") name += ", " + getInput("u_firstname");
					if (getInput("u_middlename")!="") name += " " + getInput("u_middlename");
					if (getInput("u_extname")!="") name += " " + getInput("u_extname");
					setInput("u_patientname",name);
					break;
				case "u_birthdate":
				case "u_startdate":
					showAjaxProcess('Computing Age');
					result = u_ajaxxmlgetageondateGPSHIS(formatDateToDB(getInput("u_birthdate")),formatDateToDB(getInput("u_startdate")),"");
					if (result.getAttribute("result") == '1') {
						setInput("u_age",result.childNodes.item(0).getAttribute("y"));
					} else {
						hideAjaxProcess();
						setInput("u_age",0);
						page.statusbar.showError(result.firstChild.nodeValue);	
						return false;
					}
					hideAjaxProcess();
					break;
				case "u_hci_fr_name":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, u_address, u_street, u_barangay, u_city, u_province, u_zip from u_hishealthcareins where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_hci_fr_name",result.childNodes.item(0).getAttribute("code"));
								setInput("u_hci_fr_street",result.childNodes.item(0).getAttribute("u_street"));
								//setInput("u_hci_fr_barangay",result.childNodes.item(0).getAttribute("u_barangay"));
								setInput("u_hci_fr_city",result.childNodes.item(0).getAttribute("u_city"));
								setInput("u_hci_fr_province",result.childNodes.item(0).getAttribute("u_province"));
								setInput("u_hci_fr_zip",result.childNodes.item(0).getAttribute("u_zip"));
								setInput("u_hci_fr_address",result.childNodes.item(0).getAttribute("u_address"));
							} else {
								//setInput("u_hci_fr_name","");
								setInput("u_hci_fr_street","");
								//setInput("u_hci_fr_barangay","");
								setInput("u_hci_fr_city","");
								setInput("u_hci_fr_province","");
								setInput("u_hci_fr_zip","");
								setInput("u_hci_fr_address","");
								page.statusbar.showWarning("Health Care Institution record does not exists. System will automatically create the profile.");	
								//return false;
							}
						} else {
							setInput("u_hci_fr_name","");
							setInput("u_hci_fr_street","");
							//setInput("u_hci_fr_barangay","");
							setInput("u_hci_fr_city","");
							setInput("u_hci_fr_province","");
							setInput("u_hci_fr_zip","");
							setInput("u_hci_fr_address","");
							page.statusbar.showError("Error retrieving Health Care Institution record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_hci_fr_name","");
						setInput("u_hci_fr_street","");
						//setInput("u_hci_fr_barangay","");
						setInput("u_hci_fr_city","");
						setInput("u_hci_fr_province","");
						setInput("u_hci_fr_zip","");
						setInput("u_hci_fr_address","");
					}
					break;
				case "u_hci_to_name":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select code, u_address, u_street, u_barangay, u_city, u_province, u_zip from u_hishealthcareins where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_hci_to_name",result.childNodes.item(0).getAttribute("code"));
								setInput("u_hci_to_street",result.childNodes.item(0).getAttribute("u_street"));
								//setInput("u_hci_to_barangay",result.childNodes.item(0).getAttribute("u_barangay"));
								setInput("u_hci_to_city",result.childNodes.item(0).getAttribute("u_city"));
								setInput("u_hci_to_province",result.childNodes.item(0).getAttribute("u_province"));
								setInput("u_hci_to_zip",result.childNodes.item(0).getAttribute("u_zip"));
								setInput("u_hci_to_address",result.childNodes.item(0).getAttribute("u_address"));
							} else {
								//setInput("u_hci_to_name","");
								setInput("u_hci_to_street","");
								//setInput("u_hci_to_barangay","");
								setInput("u_hci_to_city","");
								setInput("u_hci_to_province","");
								setInput("u_hci_to_zip","");
								setInput("u_hci_to_address","");
								page.statusbar.showWarning("Health Care Institution record does not exists. System will automatically create the profile.");	
								//return false;
							}
						} else {
							setInput("u_hci_to_name","");
							setInput("u_hci_to_street","");
							//setInput("u_hci_to_barangay","");
							setInput("u_hci_to_city","");
							setInput("u_hci_to_province","");
							setInput("u_hci_to_zip","");
							setInput("u_hci_to_address","");
							page.statusbar.showError("Error retrieving Health Care Institution record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_hci_to_name","");
						setInput("u_hci_to_street","");
						//setInput("u_hci_to_barangay","");
						setInput("u_hci_to_city","");
						setInput("u_hci_to_province","");
						setInput("u_hci_to_zip","");
						setInput("u_hci_to_address","");
					}
					break;
				case "u_doctorname":
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select a.code, a.name, b.u_memberid from u_hisdoctors a left join u_hisdoctorhealthins b on b.code=a.code and b.u_inscode='PHIC' where a.name='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInput("u_doctorid",result.childNodes.item(0).getAttribute("code"));
								setInput("u_doctorname",result.childNodes.item(0).getAttribute("name"));
							} else {
								setInput("u_doctorid","");
								setInput("u_doctorname","");
								page.statusbar.showError("Invalid Doctor.");	
								return false;
							}
						} else {
							setInput("u_doctorid","");
							setInput("u_doctorname","");
							page.statusbar.showError("Error retrieving Doctor record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_doctorid","");
						setInput("u_doctorname","");
					}
					break;
				case "u_acthc":
				case "u_bdischc":
					if (getInputNumeric("u_bdischc")>=getInputNumeric("u_acthc")) {
						setInput("u_bdischc",0);	
					}
					if ((getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"))>getInputNumeric("u_totalhc")) setInputAmount("u_claimhc",getInputNumeric("u_totalhc"));
					else setInputAmount("u_claimhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));
					setInputAmount("u_balhc",(getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")) - getInputNumeric("u_claimhc"));
					
					setInputAmount("u_consumedhc",(getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")));	
					
					computeTotalGPSHIS();					
					
					break;
				case "u_hci_fr_street":
				case "u_hci_fr_city":
				case "u_hci_fr_province":
				case "u_hci_fr_zip":
					setFromAddressGPSHIS();
					break;
				case "u_hci_to_street":
				case "u_hci_to_city":
				case "u_hci_to_province":
				case "u_hci_to_zip":
					setToAddressGPSHIS();
					break;
				case "u_1stcrpf":
					if (getInputNumeric("u_1stcr")>getInputNumeric("u_1stcrpf")) {
						setInputAmount("u_1stcrhc",getInputNumeric("u_1stcr")-getInputNumeric("u_1stcrpf"));
					} else {
						setInputAmount("u_1stcrhc",getInputNumeric("u_1stcr"));
						setInputAmount("u_1stcrpf",0);
					}
					computeTotalGPSHIS();
					
					if (getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")>getInputNumeric("u_totalhc")) setInputAmount("u_claimhc",getInputNumeric("u_totalhc"));
					else setInputAmount("u_claimhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));
					setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc") - getInputNumeric("u_claimhc"));
					
					setInputAmount("u_consumedhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));	
				
					computeTotalGPSHIS();
					break;
				case "u_1stcrhc":
					if (getInputNumeric("u_1stcr")>getInputNumeric("u_1stcrhc")) {
						setInputAmount("u_1stcrpf",getInputNumeric("u_1stcr")-getInputNumeric("u_1stcrhc"));
					} else {
						setInputAmount("u_1stcrhc",getInputNumeric("u_1stcr"));
						setInputAmount("u_1stcrpf",0);
					}
					computeTotalGPSHIS();
					
					if (getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")>getInputNumeric("u_totalhc")) setInputAmount("u_claimhc",getInputNumeric("u_totalhc"));
					else setInputAmount("u_claimhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));
					setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc") - getInputNumeric("u_claimhc"));
					
					setInputAmount("u_consumedhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));	
				
					computeTotalGPSHIS();
					break;
				case "u_2ndcrpf":
					if (getInputNumeric("u_2ndcr")>getInputNumeric("u_2ndcrpf")) {
						setInputAmount("u_2ndcrhc",getInputNumeric("u_2ndcr")-getInputNumeric("u_2ndcrpf"));
					} else {
						setInputAmount("u_2ndcrpf",getInputNumeric("u_2ndcr"));
						setInputAmount("u_2ndcrhc",0);
					}
					computeTotalGPSHIS();
					
					if (getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")>getInputNumeric("u_totalhc")) setInputAmount("u_claimhc",getInputNumeric("u_totalhc"));
					else setInputAmount("u_claimhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));
					setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc") - getInputNumeric("u_claimhc"));
					
					setInputAmount("u_consumedhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));	
				
					computeTotalGPSHIS();
					break;
				case "u_2ndcrhc":
					if (getInputNumeric("u_2ndcr")>getInputNumeric("u_2ndcrhc")) {
						setInputAmount("u_2ndcrpf",getInputNumeric("u_2ndcr")-getInputNumeric("u_2ndcrhc"));
					} else {
						setInputAmount("u_2ndcrhc",getInputNumeric("u_2ndcr"));
						setInputAmount("u_2ndcrpf",0);
					}
					computeTotalGPSHIS();
					
					if (getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")>getInputNumeric("u_totalhc")) setInputAmount("u_claimhc",getInputNumeric("u_totalhc"));
					else setInputAmount("u_claimhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));
					setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc") - getInputNumeric("u_claimhc"));
					
					setInputAmount("u_consumedhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));	
				
					computeTotalGPSHIS();
					break;
			}
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
		default:
			switch (column) {
				case "u_typeofdischarge":
					disableInput("u_hci_to_name");
					disableInput("u_hci_to_street");
					disableInput("u_hci_to_city");
					disableInput("u_hci_to_province");
					disableInput("u_hci_to_zip");
					disableInput("u_expiredate");
					disableInput("u_expiretime");
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_method from u_hisdischargetypes where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								if (result.childNodes.item(0).getAttribute("u_method")=="Transferred") {
									setInput("u_isreferred",1);
									enableInput("u_hci_to_name");
									enableInput("u_hci_to_address");
									enableInput("u_hci_to_street");
									enableInput("u_hci_to_city");
									enableInput("u_hci_to_province");
									enableInput("u_hci_to_zip");
									focusInput("u_hci_to_name");
								} else {
									setInput("u_isreferred",0);
									setInput("u_hci_to_name","");
									setInput("u_hci_to_address","");
									setInput("u_hci_to_street","");
									setInput("u_hci_to_city","");
									setInput("u_hci_to_province","");
									setInput("u_hci_to_zip","");
								}
								if (result.childNodes.item(0).getAttribute("u_method")=="Expired") {
									setInput("u_expired",1);
									enableInput("u_expiredate");
									enableInput("u_expiretime");
									focusInput("u_expiredate");
								} else {
									setInput("u_expired",0);	
									setInput("u_expiredate","");
									setInput("u_expiretime","");
								}
							} else {
								setInput("u_hci_to_name","");
								setInput("u_hci_to_address","");
								setInput("u_hci_to_street","");
								setInput("u_hci_to_city","");
								setInput("u_hci_to_province","");
								setInput("u_hci_to_zip","");
								setInput("u_expiredate","");
								setInput("u_expiretime","");
								page.statusbar.showError("Invalid Discharge Type.");	
								return false;
							}
						} else {
							setInput("u_isreferred",0);
							
							setInput("u_hci_to_name","");
							setInput("u_hci_to_address","");
							setInput("u_hci_to_street","");
							setInput("u_hci_to_city","");
							setInput("u_hci_to_province","");
							setInput("u_hci_to_zip","");
							setInput("u_expired",0);	
							setInput("u_expiredate","");
							setInput("u_expiretime","");
							page.statusbar.showError("Error retrieving Discharge Type record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInput("u_isreferred",0);
						
						setInput("u_hci_to_name","");
						setInput("u_hci_to_address","");
						setInput("u_hci_to_street","");
						setInput("u_hci_to_city","");
						setInput("u_hci_to_province","");
						setInput("u_hci_to_zip","");
						setInput("u_expired",0);	
						setInput("u_expiredate","");
						setInput("u_expiretime","");
					}
					break;
				case "u_oth_a_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_a_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_a_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_a_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_a_cr",0);
								setInputAmount("u_oth_a_pf",0);
								setInputAmount("u_oth_a_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_a_cr",0);
							setInputAmount("u_oth_a_pf",0);
							setInputAmount("u_oth_a_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_a_cr",0);
						setInputAmount("u_oth_a_pf",0);
						setInputAmount("u_oth_a_hc",0);
					}				
					break;
				/*case "u_oth_b_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_b_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_b_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_b_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_b_cr",0);
								setInputAmount("u_oth_b_pf",0);
								setInputAmount("u_oth_b_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_b_cr",0);
							setInputAmount("u_oth_b_pf",0);
							setInputAmount("u_oth_b_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_b_cr",0);
						setInputAmount("u_oth_b_pf",0);
						setInputAmount("u_oth_b_hc",0);
					}				
					break;*/
				case "u_oth_c_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_c_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_c_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_c_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_c_cr",0);
								setInputAmount("u_oth_c_pf",0);
								setInputAmount("u_oth_c_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_c_cr",0);
							setInputAmount("u_oth_c_pf",0);
							setInputAmount("u_oth_c_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_c_cr",0);
						setInputAmount("u_oth_c_pf",0);
						setInputAmount("u_oth_c_hc",0);
					}				
					break;
				case "u_oth_d_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_d_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_d_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_d_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_d_cr",0);
								setInputAmount("u_oth_d_pf",0);
								setInputAmount("u_oth_d_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_d_cr",0);
							setInputAmount("u_oth_d_pf",0);
							setInputAmount("u_oth_d_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_d_cr",0);
						setInputAmount("u_oth_d_pf",0);
						setInputAmount("u_oth_d_hc",0);
					}				
					break;
				case "u_oth_e_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_e_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_e_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_e_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_e_cr",0);
								setInputAmount("u_oth_e_pf",0);
								setInputAmount("u_oth_e_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_e_cr",0);
							setInputAmount("u_oth_e_pf",0);
							setInputAmount("u_oth_e_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_e_cr",0);
						setInputAmount("u_oth_e_pf",0);
						setInputAmount("u_oth_e_hc",0);
					}				
					break;
				case "u_oth_f_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_f_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_f_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_f_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_f_cr",0);
								setInputAmount("u_oth_f_pf",0);
								setInputAmount("u_oth_f_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_f_cr",0);
							setInputAmount("u_oth_f_pf",0);
							setInputAmount("u_oth_f_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_f_cr",0);
						setInputAmount("u_oth_f_pf",0);
						setInputAmount("u_oth_f_hc",0);
					}				
					break;
				case "u_oth_g_code":	
					if (getInput(column)!="") {
						result = page.executeFormattedQuery("select u_rate,u_pf,u_hc from u_hisphicoths where code='"+getInput(column)+"'");	
						if (result.getAttribute("result")!= "-1") {
							if (parseInt(result.getAttribute("result"))>0) {
								setInputAmount("u_oth_g_cr",result.childNodes.item(0).getAttribute("u_rate"));
								setInputAmount("u_oth_g_pf",result.childNodes.item(0).getAttribute("u_pf"));
								setInputAmount("u_oth_g_hc",result.childNodes.item(0).getAttribute("u_hc"));
							} else {
								setInputAmount("u_oth_g_cr",0);
								setInputAmount("u_oth_g_pf",0);
								setInputAmount("u_oth_g_hc",0);
								page.statusbar.showError("Invalid Package.");	
								return false;
							}
						} else {
							setInputAmount("u_oth_g_cr",0);
							setInputAmount("u_oth_g_pf",0);
							setInputAmount("u_oth_g_hc",0);
							page.statusbar.showError("Error retrieving Package record. Try Again, if problem persists, check the connection.");	
							return false;
						}
					} else {
						setInputAmount("u_oth_g_cr",0);
						setInputAmount("u_oth_g_pf",0);
						setInputAmount("u_oth_g_hc",0);
					}				
					break;
					
			}
	}
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_is1stcr":
					if (row==0) {
						if (isTableInputChecked(table,"u_is1stcr")) {
							if (getTableInputNumeric(table,"u_1stcr")==0) {
								page.statusbar.showWarning("Is not applicable for 1st Case Rate.");
								return false;
							}
							uncheckedTableInput(table,"u_is2ndcr");
						}
					} else {
						if (isTableInputChecked(table,"u_is1stcr",row)) {
							if (getTableInputNumeric(table,"u_1stcr",row)==0) {
								page.statusbar.showWarning("Is not applicable for 1st Case Rate.");
								return false;
							}
							uncheckedTableInput(table,"u_is2ndcr",row);
							uncheckedTableInput(table,"u_is1stcr",-1);
							checkedTableInput(table,"u_is1stcr",row);
							setCaseRateGPSHIS();
						}
					}
					break;
				case "u_is2ndcr":
					if (row==0) {
						if (isTableInputChecked(table,"u_is2ndcr")) {
							if (getTableInputNumeric(table,"u_2ndcr")==0) {
								page.statusbar.showWarning("Is not applicable for 2nd Case Rate.");
								return false;
							}
							uncheckedTableInput(table,"u_is1stcr");
						}
					} else {
						if (isTableInputChecked(table,"u_is2ndcr",row)) {
							if (getTableInputNumeric(table,"u_2ndcr",row)==0) {
								page.statusbar.showWarning("Is not applicable for 2nd Case Rate.");
								return false;
							}
							uncheckedTableInput(table,"u_is1stcr",row);
							uncheckedTableInput(table,"u_is2ndcr",-1);
							checkedTableInput(table,"u_is2ndcr",row);
							setCaseRateGPSHIS();
						}
					}
					break;
				case "u_repeatproc":
					if (isTableInputChecked(table,column)) {
						enableTableInput(table,"u_repeatproctype");
						enableTableInput(table,"u_repeatcycle");
						enableTableInput(table,"u_repeatdates");
						focusTableInput(table,"u_repeatcycle");
					} else {
						disableTableInput(table,"u_repeatproctype");
						disableTableInput(table,"u_repeatcycle");
						disableTableInput(table,"u_repeatdates");
						setTableInput(table,"u_repeatcycle",1);
						setTableInput(table,"u_repeatproctype","");
						setTableInput(table,"u_repeatdates","");
					}
					break;
			}
			break;
		default:
			switch (column) {
				case "u_ismember":
					disableInput("u_memberid");
					disableInput("u_membername");
					if (!isInputChecked("u_ismember")) {
						enableInput("u_memberid");
						enableInput("u_membername");
						focusInput("u_memberid");
					} else {
						setInput("u_memberid","");
						setInput("u_membername","");
					}
					break;
				case "u_istransferred":
					disableInput("u_hci_fr_name");
					disableInput("u_hci_fr_address");
					disableInput("u_hci_fr_street");
					disableInput("u_hci_fr_city");
					disableInput("u_hci_fr_province");
					disableInput("u_hci_fr_zip");
					if (isInputChecked("u_istransferred")) {
						enableInput("u_hci_fr_name");
						enableInput("u_hci_fr_address");
						enableInput("u_hci_fr_street");
						enableInput("u_hci_fr_city");
						enableInput("u_hci_fr_province");
						enableInput("u_hci_fr_zip");
						focusInput("u_hci_fr_name");
					} else {
						setInput("u_hci_fr_name","");
						setInput("u_hci_fr_address","");
						setInput("u_hci_fr_street","");
						setInput("u_hci_fr_city","");
						setInput("u_hci_fr_province","");
						setInput("u_hci_fr_zip","");
					}
					break;
			}
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	switch (Id) {
		case "df_u_refno":
			if (isInputEmpty("u_reftype")) return false;
			break;
	}
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
	switch (Id) {
		case "df_u_refno":
			if (getInput("u_reftype")=="IP") {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where docstatus not in ('Discharged')")); 
			} else {
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where docstatus not in ('Discharged')")); 
			}
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cflsortby=u_patientname";
			break;
		case "df_u_hci_fr_name":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hishealthcareins")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Health Care Institutions")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hishealthcareins"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_hci_to_name":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hishealthcareins")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Health Care Institutions")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hishealthcareins"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=300"; 			
			break;
		case "df_u_doctorname":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisdoctors")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisdoctors"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=600"; 			
			break;
		case "df_u_icdgroupT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisicdgroups")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ICD Group")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
		case "df_u_icdcodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisicds where u_level>2 and u_icdgroup='"+getTableInput("T1","u_icdgroup")+"'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("ICD Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_rvscodeT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisrvs")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("RVS Code`Description")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_rvsdescT1":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisrvs")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Description`RVS Code")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			break;
		case "df_u_doctoridT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code,name from u_hisdoctors")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Doctor ID`Name of Doctor")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("10`50")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisdoctors"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=600"; 			
			break;
		case "df_u_doctornameT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select name,code from u_hisdoctors")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Name of Doctor`Doctor ID")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("50`10")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
			params["params"] += "&cfladdurl=./udo.php?objectcode=u_hisdoctors"; 			
			params["params"] += "&cfladdwidth=1024"; 			
			params["params"] += "&cfladdheight=600"; 			
			break;
		case "df_u_doctorserviceT2":
			params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select code from u_hisservices where u_group='PRF'")); 
			params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("Service")); 			
			params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("30")); 			
			params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`")); 			
			break;
	}
	return params;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			disableTableInput(table,"u_repeatproctype");
			disableTableInput(table,"u_repeatcycle");
			disableTableInput(table,"u_repeatdates");
			setTableInput(table,"u_repeatcycle",1);
		break;
	}
	
}

function onTableBeforeInsertRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getInput("u_billno")!="") {
				page.statusbar.showError("You cannot add this record, if its link with billing. Please update patient medical records instead.");
				return false;
			}
			if (getTableInput(table,"u_icdgroup")!="" || getTableInput(table,"u_icdcode")!="") {
				if (isTableInputEmpty(table,"u_icdgroup")) return false;
				if (isTableInputEmpty(table,"u_icdcode")) return false;
			}
			
			if (getTableInput(table,"u_rvscode")!="") {
				if (isTableInputEmpty(table,"u_date")) return false;
			} else {
				setTableInput(table,"u_date","");
			}
			
			if (getTableInput(table,"u_icdcode")=="" && getTableInput(table,"u_rvscode")=="") {
				page.statusbar.showError("ICD or RVS Code must be entered.");
				focusTableInput(table,"u_icdgroup");
				return false;
			}
			
			if (getTableInputNumeric(table,"u_withlateral")==1) {
				if (isTableInputEmpty(table,"u_laterality")) return false;
			} else {
				setTableInput(table,"u_laterality","");
			}
			
			if (isTableInputNegative(table,"u_repeatcycle")) return false;

			if (isTableInputChecked(table,"u_is1stcr")) setTableInput(table,"u_is1stcr",0,-1,"No");
			if (isTableInputChecked(table,"u_is2ndcr")) setTableInput(table,"u_is2ndcr",0,-1,"No");
			
			break;
		case "T2":
			if (getInput("u_billno")!="") {
				page.statusbar.showError("You cannot add this record, if its link with billing. Please update patient medical records instead.");
				return false;
			}
			if (isTableInputEmpty(table,"u_doctorname")) return false;
			if (isTableInputEmpty(table,"u_doctorservice")) return false;
			if (isTableInputEmpty(table,"u_doctorphicno")) return false;
			if (isTableInputNegative(table,"u_actpf")) return false;
			if (getTableInputNumeric(table,"u_balpf")<0) {
				page.statusbar.showError("Professional fee benefit cannot be more than actual charge less bef discount.");
				focusTableInput(table,"u_claimpf");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
	switch (table) {
		case "T1": setCaseRateGPSHIS(); break;
		case "T2": computeT2TotalGPSHIS(); break;
	}
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getTableInput(table,"u_icdgroup")!="" || getTableInput(table,"u_icdcode")!="") {
				if (isTableInputEmpty(table,"u_icdgroup")) return false;
				if (isTableInputEmpty(table,"u_icdcode")) return false;
			}

			if (getTableInput(table,"u_rvscode")!="") {
				if (isTableInputEmpty(table,"u_date")) return false;
			} else {
				setTableInput(table,"u_date","");
			}
			
			if (getTableInput(table,"u_icdcode")=="" && getTableInput(table,"u_rvscode")=="") {
				page.statusbar.showError("ICD or RVS Code must be entered.");
				focusTableInput(table,"u_icdgroup");
				return false;
			}

			if (getTableInputNumeric(table,"u_withlateral")==1) {
				if (isTableInputEmpty(table,"u_laterality")) return false;
			} else {
				setTableInput(table,"u_laterality","");
			}

			if (isTableInputNegative(table,"u_repeatcycle")) return false;

			if (isTableInputChecked(table,"u_is1stcr")) setTableInput(table,"u_is1stcr",0,-1,"No");
			if (isTableInputChecked(table,"u_is2ndcr")) setTableInput(table,"u_is2ndcr",0,-1,"No");
			
			break;
		case "T2":
			if (isTableInputEmpty(table,"u_doctorname")) return false;
			if (isTableInputEmpty(table,"u_doctorservice")) return false;
			if (isTableInputEmpty(table,"u_doctorphicno")) return false;
			if (isTableInputNegative(table,"u_actpf")) return false;
			if (getTableInputNumeric(table,"u_balpf")<0) {
				page.statusbar.showError("Professional fee benefit cannot be more than actual charge less bef discount.");
				focusTableInput(table,"u_claimpf");
				return false;
			}
			break;
	}
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
	switch (table) {
		case "T1": setCaseRateGPSHIS(); break;
		case "T2": computeT2TotalGPSHIS(); break;
	}
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1":
			if (getInput("u_billno")!="") {
				page.statusbar.showError("You cannot delete this record, if its link with billing.");
				return false;
			}
			break;
		case "T2":
			if (getInput("u_billno")!="") {
				page.statusbar.showError("You cannot delete this record, if its link with billing.");
				return false;
			}
			break;
	}
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
	switch (table) {
		case "T1": setCaseRateGPSHIS(); break;
		case "T2": computeT2TotalGPSHIS(); break;
	}
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

function onTableAfterEditRowGPSHIS(table,row) {
	switch (table) {
		case "T1": 
			if (isTableInputChecked(table,"u_repeatproc")) {
				enableTableInput(table,"u_repeatproctype");
				enableTableInput(table,"u_repeatcycle");
				enableTableInput(table,"u_repeatdates");
			} else {
				disableTableInput(table,"u_repeatproctype");
				disableTableInput(table,"u_repeatcycle");
				disableTableInput(table,"u_repeatdates");
				setTableInput(table,"u_repeatcycle",1);
			}
			break;
	}
}

function setCaseRateGPSHIS() {
	var rc = getTableRowCount("T1"), u_1stcrcode="", u_1stcr=0, u_1stcrpf=0, u_1stcrhc=0, u_2ndcrcode="", u_2ndcr=0, u_2ndcrpf=0, u_2ndcrhc=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T1",i)==false) {
			if (isTableInputChecked("T1","u_is1stcr",i)) {
			//if (getTableInput("T1","u_is1stcr",i)=="1") {
				if (getTableInput("T1","u_icdcode",i)!="") u_1stcrcode=getTableInput("T1","u_icdcode",i);
				else u_1stcrcode=getTableInput("T1","u_rvscode",i);
				u_1stcr=getTableInputNumeric("T1","u_1stcr",i) * getTableInputNumeric("T1","u_repeatcycle",i);
				u_1stcrpf=getTableInputNumeric("T1","u_1stcrpf",i) * getTableInputNumeric("T1","u_repeatcycle",i);
				u_1stcrhc=getTableInputNumeric("T1","u_1stcrhc",i) * getTableInputNumeric("T1","u_repeatcycle",i);
			}
			if (isTableInputChecked("T1","u_is2ndcr",i)) {
			//if (getTableInput("T1","u_is2ndcr",i)=="1") {
				if (getTableInput("T1","u_icdcode",i)!="") u_2ndcrcode=getTableInput("T1","u_icdcode",i);
				else u_2ndcrcode=getTableInput("T1","u_rvscode",i);
				u_2ndcr=getTableInputNumeric("T1","u_2ndcr",i) * getTableInputNumeric("T1","u_repeatcycle",i);
				u_2ndcrpf=getTableInputNumeric("T1","u_2ndcrpf",i) * getTableInputNumeric("T1","u_repeatcycle",i);
				u_2ndcrhc=getTableInputNumeric("T1","u_2ndcrhc",i) * getTableInputNumeric("T1","u_repeatcycle",i);
			}
		}
	}
	setInput("u_1stcrcode",u_1stcrcode);
	setInputAmount("u_1stcr",u_1stcr);
	setInputAmount("u_1stcrpf",u_1stcrpf);
	setInputAmount("u_1stcrhc",u_1stcrhc);
	setInput("u_2ndcrcode",u_2ndcrcode);
	setInputAmount("u_2ndcr",u_2ndcr);
	setInputAmount("u_2ndcrpf",u_2ndcrpf);
	setInputAmount("u_2ndcrhc",u_2ndcrhc);
	
	computeTotalGPSHIS();
	
	if (getInputNumeric("u_acthc")-getInputNumeric("u_bdischc")>getInputNumeric("u_totalhc")) setInputAmount("u_claimhc",getInputNumeric("u_totalhc"));
	else setInputAmount("u_claimhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));
	setInputAmount("u_balhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc") - getInputNumeric("u_claimhc"));
	
	setInputAmount("u_consumedhc",getInputNumeric("u_acthc")-getInputNumeric("u_bdischc"));	

	computeTotalGPSHIS();

}

function computeT2TotalGPSHIS() {
	var rc =  getTableRowCount("T2"), actpf=0, bdiscpf=0, claimpf=0, balpf=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted("T2",i)==false) {
			actpf += getTableInputNumeric("T2","u_actpf",i);
			bdiscpf += getTableInputNumeric("T2","u_bdiscpf",i);
			claimpf += getTableInputNumeric("T2","u_claimpf",i);
			balpf += getTableInputNumeric("T2","u_balpf",i);
		}
	}
	setInputAmount("u_actpf",actpf);	
	setInputAmount("u_bdiscpf",bdiscpf);	
	setInputAmount("u_claimpf",claimpf);	
	setInputAmount("u_balpf",balpf);	

	setInputAmount("u_consumedpf",actpf-bdiscpf);	
	
	computeTotalGPSHIS();
}

function computeTotalGPSHIS() {
	setInputAmount("u_totalamt",getInputNumeric("u_1stcr")+getInputNumeric("u_2ndcr"));	
	setInputAmount("u_totalpf",getInputNumeric("u_1stcrpf")+getInputNumeric("u_2ndcrpf"));	
	setInputAmount("u_totalhc",getInputNumeric("u_1stcrhc")+getInputNumeric("u_2ndcrhc"));	

	setInputAmount("u_consumedamt",getInputNumeric("u_consumedpf") + getInputNumeric("u_consumedhc"));	
	
}

function setFromAddressGPSHIS() {
	var address="";
	address = getInput("u_hci_fr_street");
	if (getInput("u_hci_fr_city")!="") address += ", " + getInput("u_hci_fr_city");
	if (getInput("u_hci_fr_province")!="") address += ", " + getInput("u_hci_fr_province");
	if (getInput("u_hci_fr_zip")!="") address += ", " + getInput("u_hci_fr_zip");
	setInput("u_hci_fr_address",address);
}

function setToAddressGPSHIS() {
	var address="";
	address = getInput("u_hci_to_street");
	if (getInput("u_hci_to_city")!="") address += ", " + getInput("u_hci_to_city");
	if (getInput("u_hci_to_province")!="") address += ", " + getInput("u_hci_to_province");
	if (getInput("u_hci_to_zip")!="") address += ", " + getInput("u_hci_to_zip");
	setInput("u_hci_to_address",address);
}

function u_ajaxxmlgetageondateGPSHIS(p_birthdate,p_date,p_params) {
	var parser = new DOMParser();
	var dom;
	document.getElementById('ajaxPending').value = "*";	
	http = getHTTPObject(); // We create the HTTP Object
	try { 	
		http.open("GET", "udp.php?&objectcode=u_ajaxxmlgetageondate&birthdate="+escape(p_birthdate)+"&date="+escape(p_date)+ "&params=" + p_params, false);
		http.send(null);
		document.getElementById('ajaxPending').value = "";	
		dom = parser.parseFromString(http.responseText, "text/xml");
	} catch (theError) {
		document.getElementById('ajaxPending').value = "";	
		dom = parser.parseFromString('<validate result="0">' + theError.message + '</validate>', "text/xml");
		//setTimeout("setStatusMsg('" + theError.message + "')",1000);
	}
	return dom.documentElement;
}

