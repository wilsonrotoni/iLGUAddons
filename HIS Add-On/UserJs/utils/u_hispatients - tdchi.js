var healthinsmodified = false;
var autoprepaid = true;

function setSectionData(column,request) {
	if (request==null) request = false;
	if (column==null) column = "u_department";

	/*if (getVar("objectcode")=="U_HISMEDSUPREQUESTS") {
		if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="HEARTSTATION" || getInput("u_trxtype")=="XRAY" || getInput("u_trxtype")=="CTSCAN" || getInput("u_trxtype")=="ULTRASOUND") {
			return true;
		}
	}*/
	if (getInput(column)!="") {
		result = page.executeFormattedQuery("select u_disconbill, u_showallitems, u_allowintsecreq, u_allowintsecpos, u_stocklink from u_hissections where code='"+getInput(column)+"'");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				if (column=="u_requestdepartment") {
					setInput("u_allowintsecreq",result.childNodes.item(0).getAttribute("u_allowintsecreq"));
				}
				if (column=="u_department") {
					if (isInput("u_allowintsecpos")) setInput("u_allowintsecpos",result.childNodes.item(0).getAttribute("u_allowintsecpos"));
					setInput("u_disconbill",result.childNodes.item(0).getAttribute("u_disconbill"));
					setInput("u_showallitems",result.childNodes.item(0).getAttribute("u_showallitems"));
					setInput("u_stocklink",result.childNodes.item(0).getAttribute("u_stocklink"));
				}
			} else {
				page.statusbar.showError("Invalid Section for this patient.");	
				return false;
			}
		} else {
			page.statusbar.showError("Error retrieving Section. Try Again, if problem persists, check the connection.");	
			return false;
		}
	} else {
		setInput("u_disconbill",1);
		setInput("u_showallitems",1);
	}
	if (getInput("u_disconbill")=="") setSectionData();
	return true;
}

function setDiscountData() {
	if (getInput("u_disccode")!="") {
		result = page.executeFormattedQuery("select u_scdisc from u_hishealthins where code='"+getInput("u_disccode")+"'");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
				else uncheckedInput("u_scdisc");
			} else {
				uncheckedInput("u_scdisc");
				page.statusbar.showError("Invalid Discount.");	
				return false;
			}
		} else {
			uncheckedInput("u_scdisc");
			page.statusbar.showError("Error retrieving Discount. Try Again, if problem persists, check the connection.");	
			return false;
		}
	} else {
		uncheckedInput("u_scdisc");
	}
	return true;
}

function setPatientRegistrationData(reftype) {
	if (reftype==null) reftype = "IP";
	var result, data = new Array();
	clearTable("T8",true);
	if (isInput("u_firstname")) enableInput("u_firstname");
	if (isInput("u_middlename")) enableInput("u_middlename");
	if (isInput("u_lastname")) enableInput("u_lastname");
	if (isInput("u_extname")) enableInput("u_extname");
						
	if (getInput("u_patientid")!="") {
		result = page.executeFormattedQuery("select a.code, a.name, a.u_firstname, a.u_middlename, a.u_lastname, a.u_extname, a.u_birthdate, a.u_gender, a.u_religion, a.u_civilstatus, a.u_visitcount, a.u_disccode, a.u_scdisc, a.u_newbornstat, a.u_telno, a.u_mobileno, a.u_email, a.u_address, a.u_street, a.u_barangay, a.u_city, a.u_province, a.u_country, a.u_zip, a.u_phicmemberid, a.u_hmo as u_hmoname, a.u_hmomemberid, b.u_inscode, b.u_hmo, b.u_scdisc as u_scdisc2, b.u_memberid, b.u_membername, b.u_membertype, a.u_occupation, a.u_spousename, a.u_spouseaddress, a.u_spousetelno, a.u_fathername, a.u_fatheraddress, a.u_fathertelno, a.u_mothername, a.u_motheraddress, a.u_mothertelno from u_hispatients a left join u_hispatienthealthins b on b.company=a.company and b.branch=a.branch and b.code=a.code where a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.code='"+getInput("u_patientid")+"'");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					if (iii==0) {
						var result2 = parseFloat(page.executeFormattedSearch("select balance from customers where custno='"+getInput("u_patientid")+"'"));	 
						if (result2>0) {
							if (confirm("Patient have outstanding balance of "+result2+". Continue?")==false) {
								setInput("u_patientid","");
								return false;
							}
						}
						if (reftype!="OP") {
							var result3 = page.executeFormattedSearch("select docno from u_hisips where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("u_patientid")+"' and u_enddate is null");	 
							if (result3!="") {
								alert("Patient still have active In-Patient record.");
								setInput("u_patientid","");
								return false;
							}
						} else {
							var result4 = page.executeFormattedSearch("select docno from u_hisops where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_patientid='"+getInput("u_patientid")+"' and u_enddate is null");	 
							if (result4!="") {
								if (confirm("Patient still have active Out-Patient record. Continue?")==false) {
									setInput("u_patientid","");
									return false;
								}
							}
						}
						
						setInput("u_patientid",result.childNodes.item(0).getAttribute("code"));
						setInput("u_patientname",result.childNodes.item(0).getAttribute("name"));
						if (isInput("u_firstname")) setInput("u_firstname",result.childNodes.item(0).getAttribute("u_firstname"));
						if (isInput("u_middlename")) setInput("u_middlename",result.childNodes.item(0).getAttribute("u_middlename"));
						if (isInput("u_lastname")) setInput("u_lastname",result.childNodes.item(0).getAttribute("u_lastname"));
						if (isInput("u_extname")) setInput("u_extname",result.childNodes.item(0).getAttribute("u_extname"));
						setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
						setInput("u_religion",result.childNodes.item(0).getAttribute("u_religion"));
						if (isInput("u_birthdate")) setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
						if (isInput("u_age_y") && isInput("u_birthdate")) setInput("u_age_y",parseInt(utils.divide(datedifference("m",getInput("u_birthdate"), getInput("u_startdate")),12)));
						if (isInput("u_telno")) setInput("u_telno",result.childNodes.item(0).getAttribute("u_telno"));
						if (isInput("u_mobileno")) setInput("u_mobileno",result.childNodes.item(0).getAttribute("u_mobileno"));
						if (isInput("u_email")) setInput("u_email",result.childNodes.item(0).getAttribute("u_email"));
						if (isInput("u_occupation")) setInput("u_occupation",result.childNodes.item(0).getAttribute("u_occupation"));
						if (isInput("u_address")) setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
						if (isInput("u_street")) setInput("u_street",result.childNodes.item(0).getAttribute("u_street"));
						if (isInput("u_barangay")) setInput("u_barangay",result.childNodes.item(0).getAttribute("u_barangay"));
						if (isInput("u_city")) setInput("u_city",result.childNodes.item(0).getAttribute("u_city"));
						if (isInput("u_province")) setInput("u_province",result.childNodes.item(0).getAttribute("u_province"));
						if (isInput("u_country")) setInput("u_country",result.childNodes.item(0).getAttribute("u_country"));
						if (isInput("u_zip")) setInput("u_zip",result.childNodes.item(0).getAttribute("u_zip"));
						if (isInput("u_civilstatus")) setInput("u_civilstatus",result.childNodes.item(0).getAttribute("u_civilstatus"));
						if (isInput("u_disccode")) setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
						if (isInput("u_phicmemberid")) setInput("u_phicmemberid",result.childNodes.item(0).getAttribute("u_phicmemberid"));
						if (isInput("u_hmo")) setInput("u_hmo",result.childNodes.item(0).getAttribute("u_hmoname"));
						if (isInput("u_hmomemberid")) setInput("u_hmomemberid",result.childNodes.item(0).getAttribute("u_hmomemberid"));
						if (isInput("u_oldpatient")) {
							if (parseInt(result.childNodes.item(0).getAttribute("u_visitcount"))>0) {
								setInput("u_oldpatient",1);
							} else {
								setInput("u_oldpatient",0);
								if (isInput("u_newbornstat")) setInput("u_newbornstat",result.childNodes.item(0).getAttribute("u_newbornstat"),true);
							}
						}
						if (isInput("u_scdisc")) {
							if (result.childNodes.item(iii).getAttribute("u_scdisc2")=="1") checkedInput("u_scdisc");
							else uncheckedInput("u_scdisc");
						}
						if (isInput("u_firstname")) disableInput("u_firstname");
						if (isInput("u_middlename")) disableInput("u_middlename");
						if (isInput("u_lastname")) disableInput("u_lastname");
						if (isInput("u_extname")) disableInput("u_extname");
						
						if (isInput("u_spousename")) setInput("u_spousename",result.childNodes.item(0).getAttribute("u_spousename"));
						if (isInput("u_spouseaddress")) setInput("u_spouseaddress",result.childNodes.item(0).getAttribute("u_spouseaddress"));
						if (isInput("u_spousetelno")) setInput("u_spousetelno",result.childNodes.item(0).getAttribute("u_spousetelno"));
						if (isInput("u_fathername")) setInput("u_fathername",result.childNodes.item(0).getAttribute("u_fathername"));
						if (isInput("u_fatheraddress")) setInput("u_fatheraddress",result.childNodes.item(0).getAttribute("u_fatheraddress"));
						if (isInput("u_fathertelno")) setInput("u_fathertelno",result.childNodes.item(0).getAttribute("u_fathertelno"));
						if (isInput("u_mothername")) setInput("u_mothername",result.childNodes.item(0).getAttribute("u_mothername"));
						if (isInput("u_motheraddress")) setInput("u_motheraddress",result.childNodes.item(0).getAttribute("u_motheraddress"));
						if (isInput("u_mothertelno")) setInput("u_mothertelno",result.childNodes.item(0).getAttribute("u_mothertelno"));
						

					}
					try {
						if (result.childNodes.item(iii).getAttribute("u_inscode")!="") {
							data["u_inscode"] = result.childNodes.item(iii).getAttribute("u_inscode");
							data["u_hmo"] = result.childNodes.item(iii).getAttribute("u_hmo");
							data["u_scdisc"] = result.childNodes.item(iii).getAttribute("u_scdisc2");
							data["u_memberid"] = result.childNodes.item(iii).getAttribute("u_memberid");
							data["u_membername"] = result.childNodes.item(iii).getAttribute("u_membername");
							data["u_membertype"] = result.childNodes.item(iii).getAttribute("u_membertype");
							insertTableRowFromArray("T8",data);
						}
					} catch (theError) {
					}
				}
			} else {
				setInput("u_patientname","");
				if (isInput("u_firstname")) setInput("u_firstname","");
				if (isInput("u_middlename")) setInput("u_middlename","");
				if (isInput("u_lastname")) setInput("u_lastname","");
				if (isInput("u_extname")) setInput("u_extname","");
				if (isInput("u_disccode")) setInput("u_disccode","");
				if (isInput("u_newbornstat")) setInput("u_newbornstat",0,true);
				if (isInput("u_scdisc")) uncheckedInput("u_scdisc");
				page.statusbar.showError("Invalid Patient ID.");	
				return false;
			}
		} else {
			setInput("u_patientname","");
			if (isInput("u_firstname")) setInput("u_firstname","");
			if (isInput("u_middlename")) setInput("u_middlename","");
			if (isInput("u_lastname")) setInput("u_lastname","");
			if (isInput("u_extname")) setInput("u_extname","");
			if (isInput("u_disccode")) setInput("u_disccode","");
			if (isInput("u_scdisc")) uncheckedInput("u_scdisc");
			if (isInput("u_newbornstat")) setInput("u_newbornstat",0,true);
			page.statusbar.showError("Error retrieving Patient ID. Try Again, if problem persists, check the connection.");	
			return false;
		}
	} else {
		setInput("u_patientid","");
		setInput("u_patientname","");
		if (isInput("u_firstname")) setInput("u_firstname","");
		if (isInput("u_middlename")) setInput("u_middlename","");
		if (isInput("u_lastname")) setInput("u_lastname","");
		if (isInput("u_extname")) setInput("u_extname","");
		if (isInput("u_disccode")) setInput("u_disccode","");
		if (isInput("u_scdisc")) uncheckedInput("u_scdisc");
		if (isInput("u_newbornstat")) setInput("u_newbornstat",0,true);
	}
	return true;
}

function validatePatientHealthBenefit(element,column,table,row) {
	disableTableInput(table,"u_memberid");
	disableTableInput(table,"u_membername");
	disableTableInput(table,"u_membertype");
	if (getTableInput(table,column)!="") {
		if (isInputEmpty("u_patientid")) return false;
		result = page.executeFormattedQuery("select u_hmo, u_scdisc from u_hishealthins where code='"+getTableInput(table,"u_inscode")+"'");	 
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				setTableInput(table,"u_hmo",result.childNodes.item(0).getAttribute("u_hmo"));
				result2 = page.executeFormattedQuery("select u_memberid, u_membername, u_membertype from u_hispatienthealthins where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and code='"+getInput("u_patientid")+"' and u_inscode='"+getTableInput(table,"u_inscode")+"'");	 
				if (result2.getAttribute("result")!= "-1") {
					if (parseInt(result2.getAttribute("result"))>0) {
						setTableInput(table,"u_scdisc",result.childNodes.item(0).getAttribute("u_scdisc"));
						setTableInput(table,"u_memberid",result2.childNodes.item(0).getAttribute("u_memberid"));
						setTableInput(table,"u_membername",result2.childNodes.item(0).getAttribute("u_membername"));
						setTableInput(table,"u_membertype",result2.childNodes.item(0).getAttribute("u_membertype"));
					} else {
						setTableInput(table,"u_memberid","");
						setTableInput(table,"u_membername","");
						setTableInput(table,"u_membertype","");
					}
				} else {
					//setTableInput(table,"u_hmo","-1");
					setTableInput(table,"u_scdisc","0");
					setTableInput(table,"u_memberid","");
					setTableInput(table,"u_membername","");
					setTableInput(table,"u_membertype","");
					page.statusbar.showError("Error retrieving Health Benefits for this patient. Try Again, if problem persists, check the connection.");	
				}
				switch (getTableInput(table,"u_hmo")) {
					case "0":
						enableTableInput(table,"u_memberid");
						enableTableInput(table,"u_membername");
						enableTableInput(table,"u_membertype");
						focusTableInput(table,"u_memberid");
						//enableInput("u_caserate");
						break;
					case "1":
					case "4":
						enableTableInput(table,"u_memberid");
						enableTableInput(table,"u_membername");
						focusTableInput(table,"u_memberid");
						break;
					case "6":
						enableTableInput(table,"u_memberid");
						focusTableInput(table,"u_memberid");
						break;
				}
				
			} else {
				setTableInput(table,"u_hmo","-1");
				setTableInput(table,"u_scdisc","0");
				setTableInput(table,"u_memberid","");
				setTableInput(table,"u_membername","");
				setTableInput(table,"u_membertype","");
				page.statusbar.showError("Invalid Health Benefits Code.");	
				return false;
			}
		} else {
			setTableInput(table,"u_hmo","-1");
			setTableInput(table,"u_scdisc","0");
			setTableInput(table,"u_memberid","");
			setTableInput(table,"u_membername","");
			setTableInput(table,"u_membertype","");
			page.statusbar.showError("Error retrieving Health Benefits Code. Try Again, if problem persists, check the connection.");	
			return false;
		}
	} else {
		setTableInput(table,"u_hmo","-1");
		setTableInput(table,"u_scdisc","0");
		setTableInput(table,"u_memberid","");
		setTableInput(table,"u_membername","");
		setTableInput(table,"u_membertype","");
	}	
	return true;
}

function validatePatientGPSHIS(element,column,table,row) {
	var prepaid=0;
	/*if (getInput("u_trxtype")=="IP"  || getInput("u_trxtype")=="OP") {
		if (isInput("u_department")) {
			if (isInputEmpty("u_department")) return false;
		}
	}*/
	if (isInput("u_prepaid")) disableInput("u_prepaid");
	if (getInput("u_refno")!="") {
		var docstatusExp = "";
		if (getInput("u_trxtype")!="BILLING") docstatusExp = "and a.docstatus not in ('Discharged')";
		if (getInput("u_reftype")=="IP") {	
			result = page.executeFormattedQuery("select a.u_department, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_doctorid, a.u_doctorservice, a.u_disccode, a.u_scdisc, a.u_pricelist, if (ifnull(d.u_prepaid,-1)<>-1,ifnull(d.u_prepaid,-1),a.u_prepaid) as u_prepaid, a.u_startdate, a.u_address, a.u_phicmemberid, b.u_birthdate, b.u_gender, a.u_mgh, a.u_billno from u_hisips a left join u_hisbilltermsupds c on c.u_reftype='IP' and c.u_refno=a.docno left join u_hisbilltermsupdsections d on d.company=c.company and d.branch=c.branch and d.docid=c.docid and d.u_department='"+getInput("u_department")+"', u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' " + docstatusExp);
		} else {
			result = page.executeFormattedQuery("select a.u_department, a.u_patientid, a.u_patientname, a.u_paymentterm, a.u_doctorid, a.u_doctorservice, a.u_disccode, a.u_scdisc, a.u_pricelist, if (ifnull(d.u_prepaid,-1)<>-1,ifnull(d.u_prepaid,-1),a.u_prepaid) as u_prepaid, a.u_startdate, '' as u_address, '' as u_phicmemberid, b.u_birthdate, b.u_gender, 0 as u_mgh, a.u_billno from u_hisops a left join u_hisbilltermsupds c on c.u_reftype='IP' and c.u_refno=a.docno left join u_hisbilltermsupdsections d on d.company=c.company and d.branch=c.branch and d.docid=c.docid and d.u_department='"+getInput("u_department")+"', u_hispatients b where b.company=a.company and b.branch=a.branch and b.code=a.u_patientid and a.docno='"+getInput("u_refno")+"' " + docstatusExp);	 
		}
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				if (autoprepaid) {
					switch (getVar("objectcode").toUpperCase()) {
						case "U_HISREQUESTS":
						case "U_HISPACKAGEREQUESTS":
							prepaid = result.childNodes.item(0).getAttribute("u_prepaid");
							if (prepaid==2) {
								if (result.childNodes.item(0).getAttribute("u_mgh")=="1") {
									page.statusbar.showError("Patient was already tag May Go Home. Partial Payments not allowed.");
									setInput("u_refno","");
									return false;
								}
								if (result.childNodes.item(0).getAttribute("u_billno")=="1") {
									page.statusbar.showError("Patient was already billed. Partial Payments not allowed.");
									setInput("u_refno","");
									return false;
								}
								alert('This patient is already cash to cash.');
							}
							break;
						case "U_HISCHARGES":
							if (result.childNodes.item(0).getAttribute("u_mgh")=="1" && getInput("u_trxtype")!="BILLING") {
								page.statusbar.showError("Patient was already tag May Go Home. Charges not allowed.");
								setInput("u_refno","");
								return false;
							}
							if (result.childNodes.item(0).getAttribute("u_billno")=="1") {
								page.statusbar.showError("Patient was already billed. Charges not allowed.");
								setInput("u_refno","");
								return false;
							}
							prepaid = result.childNodes.item(0).getAttribute("u_prepaid");
							if (prepaid!=0 && getInput("u_trxtype")!="BILLING") {
								page.statusbar.showError("Patient is not allowed to bill charges.");
								setInput("u_refno","");
								return false;
							}
							break;
					}
				}
				setInput("u_patientid",result.childNodes.item(0).getAttribute("u_patientid"));
				setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
				setInput("u_paymentterm",result.childNodes.item(0).getAttribute("u_paymentterm"));
				setInput("u_pricelist",result.childNodes.item(0).getAttribute("u_pricelist"));
				if (getVar("objectcode").toUpperCase()=="U_HISPACKAGEREQUESTS") {
					setInput("u_disccode","");
				} else {
					if (getInput("u_prepaid")=="2" && getInput("u_disconbill")=="1") {
						setInput("u_disccode","");
					} else {
						setInput("u_disccode",result.childNodes.item(0).getAttribute("u_disccode"));
					}
				}
				switch (getVar("objectcode").toUpperCase()) {
					case "U_HISPACKAGEREQUESTS":
					case "U_HISREQUESTS":
						if (getInput("u_requestdepartment")=="") setInput("u_requestdepartment",result.childNodes.item(0).getAttribute("u_department"));
						break;
				}
				if (prepaid=="") prepaid=0;
				if (isInput("u_allowintsecreq")) {
					if (getInput("u_allowintsecreq")=="0") {
						if (prepaid==0)	prepaid=1;
					}
				}
				if (autoprepaid && isInput("u_prepaid")) setInput("u_prepaid",prepaid);
				if (isInput("u_gender")) setInput("u_gender",result.childNodes.item(0).getAttribute("u_gender"));
				if (isInput("u_birthdate")) setInput("u_birthdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_birthdate")));
				if (isInput("u_age")) {
					if (isInput("u_startdate")) setInput("u_age",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
				}
				if (isInput("u_age_y")) {
					if (isInput("u_startdate")) setInput("u_age_y",parseInt(datedifference("m",getInput("u_birthdate"),getInput("u_startdate"))/12));
				}
				if (isInput("u_doctorid")) setInput("u_doctorid",result.childNodes.item(0).getAttribute("u_doctorid"),true);
				if (isInput("u_doctorservice")) setInput("u_doctorservice",result.childNodes.item(0).getAttribute("u_doctorservice"));

				switch (getVar("objectcode").toUpperCase()) {
					case "U_HISCONSULTANCYREQUESTS":
					case "U_HISCONSULTANCYFEES":
						if (isInput("u_itemcode")) {
							setInput("u_itemcode",result.childNodes.item(0).getAttribute("u_doctorservice"));
						}
						break;
				}
				
				if (isInput("u_refdate")) setInput("u_refdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_startdate")));
				if (isInput("u_address")) setInput("u_address",result.childNodes.item(0).getAttribute("u_address"));
				if (isInput("u_phicmemberid")) setInput("u_phicmemberid",result.childNodes.item(0).getAttribute("u_phicmemberid"));
				if (result.childNodes.item(0).getAttribute("u_scdisc")=="1") checkedInput("u_scdisc");
				else uncheckedInput("u_scdisc");
		
				if (getVar("objectcode").toUpperCase()=="U_HISREQUESTS" || getVar("objectcode").toUpperCase()=="U_HISPACKAGEREQUESTS") {
					if (isInput("u_prepaid")) {
						if (getInput("u_prepaid")=="0" && getInput("u_requestdepartment")==getInput("u_department") && getInput("u_department")!="") {
							//setInput("u_patientid","");
							//setInput("u_patientname","");
							//setInput("u_disccode","");
							//setInput("u_pricelist","");
							//setInput("u_refno","");
							alert("Request is only allowed for Cash Transaction or Requesting to another section. Instead use charges screen directly.");	
							//return false;
						}
					}
					if (getInput("u_prepaid")!="0" && getInput("u_allowintsecpos")=="0" && getInput("u_requestdepartment")!=getInput("u_department")) {
						setInput("u_refno","");
						page.statusbar.showError("Cannot request cash transaction to " + getInputSelectedText("u_department") + ". Patient must transact directly to the "+ getInputSelectedText("u_department")+".");
						return false;
					}
					if (getInput("u_prepaid")=="0") enableInput("u_prepaid");
				}
				if (getVar("objectcode").toUpperCase()=="U_HISMEDSUPTFS") {						
					if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="HEARTSTATION" || getInput("u_trxtype")=="XRAY" || getInput("u_trxtype")=="CTSCAN" || getInput("u_trxtype")=="ULTRASOUND") {
						if (result.childNodes.item(0).getAttribute("u_prepaid")=="1") {
							page.statusbar.showError("Patient is only allowed to make cash transactions.");
							return false;
						} else if (result.childNodes.item(0).getAttribute("u_prepaid")=="-1") {
							if (getInput("u_reftype")=="IP") {
								if (getPrivate("prepaidip")=="1") {
									page.statusbar.showError("Patient is only allowed to make cash transactions.");
									return false;
								}
							} else {
								if (getPrivate("prepaidop")=="1") {
									page.statusbar.showError("Patient is only allowed to make cash transactions.");
									return false;
								}
							}
						}
						
						setInput("u_todepartment",result.childNodes.item(0).getAttribute("u_department"));
					}
				}
				
			} else {
				setInput("u_patientid","");
				setInput("u_patientname","");
				setInput("u_disccode","");
				setInput("u_pricelist","");
				if (isInput("u_address")) setInput("u_address","");
				if (isInput("u_gender")) setInput("u_gender","");
				if (isInput("u_birthdate")) setInput("u_birthdate","");
				if (isInput("u_age")) setInput("u_age",0);
				if (isInput("u_age_y")) setInput("u_age_y",0);
				if (isInput("u_doctorid")) setInput("u_doctorid","");
				uncheckedInput("u_scdisc");
				page.statusbar.showError("Invalid Reference No.");	
				return false;
			}
		} else {
			setInput("u_patientid","");
			setInput("u_patientname","");
			setInput("u_pricelist","");
			setInput("u_disccode","");
			if (isInput("u_address")) setInput("u_address","");
			if (isInput("u_gender")) setInput("u_gender","");
			if (isInput("u_birthdate")) setInput("u_birthdate","");
			if (isInput("u_age")) setInput("u_age",0);
			if (isInput("u_age_y")) setInput("u_age_y",0);
			if (isInput("u_doctorid")) setInput("u_doctorid","");
			uncheckedInput("u_scdisc");
			page.statusbar.showError("Error retrieving Reference No. Try Again, if problem persists, check the connection.");	
			return false;
		}

	} else {
		setInput("u_patientid","");
		setInput("u_patientname","");
		setInput("u_disccode","");
		setInput("u_pricelist","");
		if (isInput("u_address")) setInput("u_address","");
		if (isInput("u_gender")) setInput("u_gender","");
		if (isInput("u_birthdate")) setInput("u_birthdate","");
		if (isInput("u_age_y")) setInput("u_age_y",0);
		if (isInput("u_doctorid")) setInput("u_doctorid","");
		uncheckedInput("u_scdisc");
	}
	return true;				
}

function setSeniorCitizenGPSHIS(table) {
	var rc =  getTableRowCount(table);
	healthinsmodified = true;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted(table,i)==false) {
			if (getTableInput(table,"u_hmo",i)=="2") {
				setInput("u_disccode",getTableInput(table,"u_inscode",i));
				if (getTableInput(table,"u_scdisc",i)=="1") checkedInput("u_scdisc");
				else uncheckedInput("u_scdisc");
				return;
			}
		}
	}
	uncheckedInput("u_scdisc");
	setInput("u_disccode","");
}

function validatePatientItemGPSHIS(element,column,table,row,type) {
	var value=utils.addslashes(getTableInput(table,column));
	if (type==null) type = "MEDSUP";
	if (isInput("u_department") && getInputType("u_department")!="hidden") {
		if (isInputEmpty("u_department")) return false;
	}
	if (isInput("u_refno")) {
		if (getInput("u_reftype")!="WI") {
			if (isInputEmpty("u_refno")) return false;
		}
	}
	if (isInput("u_pricelist")) {
		if (isInputEmpty("u_pricelist")) return false;
	}
	
	if (value) {
		switch (getVar("objectcode").toUpperCase()) {
			case "U_HISPACKAGEREQUESTS":
			case "U_HISREQUESTS":
			case "U_HISCHARGES":
			case "U_HISLABTESTREQUESTS":
			case "U_HISMEDSUPREQUESTS":
			case "U_HISMEDSUPS":
				var itemgroupexp="";
				if (isTableInput(table,"u_itemgroup")) {
					if (getTableInput(table,"u_itemgroup")!="") itemgroupexp=" and u_group='"+getTableInput(table,"u_itemgroup")+"'";
				}
				var departmentexp=" and  (isnull(u_department) or u_department='' or u_department='"+getInput("u_department")+"')";
				if (isInput("u_showallitems")) {
					if (getInput("u_showallitems")=="0") departmentexp=" and u_department='"+getInput("u_department")+"'";
				}
				
				if (column=="u_itemcode") result = page.executeFormattedQuery("select a.code, a.name, a.u_group, a.u_salespricing, a.u_ispackage, a.u_template, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, if(a.u_allowdiscount=1,b.u_globalperc,0) as u_globalperc, a.u_scdiscamount from u_hisitems a inner join itemgroups c on c.itemgroup=a.u_group left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode=c.u_discgroup where a.code='"+value+"' and a.u_active=1 "+itemgroupexp+departmentexp);	 
				else result = page.executeFormattedQuery("select a.code, a.name, a.u_group, a.u_salespricing, a.u_ispackage, a.u_template, a.u_statperc, a.u_allowdiscount, a.u_billdiscount, if(a.u_allowdiscount=1,b.u_globalperc,0) as u_globalperc, a.u_scdiscamount from u_hisitems a inner join itemgroups c on c.itemgroup=a.u_group left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode=c.u_discgroup where a.name like '"+value+"%' and a.u_active=1 "+itemgroupexp+departmentexp);
				break;
			case "U_HISMEDSUPTFS":
				if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
					if (column=="u_itemcode") {
						result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and b.u_itemcode='"+value+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_intransit=0 and b.u_itemcode='"+value+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and b.u_itemcode='"+value+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and  a.u_fromdepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.docstatus='C' and b.u_itemcode='"+value+"') as x group by name, code having u_quantity>0");
					} else {
						result = page.executeFormattedQuery("select  u_itemdesc as name, u_itemcode as code, u_price, u_uom, sum(u_quantity) as u_quantity from ( select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and b.u_itemdesc='"+value+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_todepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.u_intransit=0 and b.u_itemdesc='"+value+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, (b.u_quantity-b.u_rtqty)*-1 as u_quantity, b.u_uom from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_department='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and b.u_itemdesc='"+value+"' union all select b.u_itemdesc, b.u_itemcode, b.u_price, b.u_quantity*-1 as u_quantity, b.u_uom from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.u_fromdepartment='"+getInput("u_fromdepartment")+"' and a.u_reftype='"+getInput("u_reftype")+"' and a.u_refno='"+getInput("u_refno")+"' and a.docstatus='C' and b.u_itemdesc='"+value+"') as x group by name, code having u_quantity>0");
					}
				} else {
					if (column=="u_itemcode") {
						result = page.executeFormattedQuery("select code, name from u_hisitems where code='"+value+"' and u_active=1");	 
					} else {
						result = page.executeFormattedQuery("select code, name from u_hisitems where name='"+value+"' and u_active=1");	 
					}
				}
				break;
			case "U_HISPOS":
				switch (getInput("u_payreftype")) {
					case "MISC":	
						if (column=="u_itemcode") result = page.executeFormattedQuery("select a.code, a.name, if(a.u_allowdiscount=1,b.u_globalperc,0) as u_globalperc, a.u_scdiscamount from u_hisservices a left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode='MISC' where a.code='"+value+"' and a.u_active=1");	 
						else result = page.executeFormattedQuery("select a.code, a.name, b.u_globalperc, a.u_scdiscamount from u_hisservices a left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode='MISC' where a.name='"+value+"' and a.u_active=1");
						break;
					case "MEDSUP":	
						if (column=="u_itemcode") result = page.executeFormattedQuery("select a.code, a.name, if(a.u_allowdiscount=1,ifnull(b.u_globalperc,0),0) as u_globalperc, a.u_scdiscamount from u_hisitems a inner join itemgroups c on c.itemgroup=a.u_group left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode=c.u_discgroup where a.code='"+value+"' and a.u_active=1");	 
						else {
							result = page.executeFormattedQuery("select a.code, a.name, if(a.u_allowdiscount=1,ifnull(b.u_globalperc,0),0) as u_globalperc, a.u_scdiscamount from u_hisitems a inner join itemgroups c on c.itemgroup=a.u_group left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode=c.u_discgroup where a.name='"+value+"' and a.u_active=1");
						}
						break;
				}
				break;
			default:	
				if (column=="u_itemcode") result = page.executeFormattedQuery("select a.code, a.name, if(a.u_allowdiscount=1,b.u_globalperc,0) as u_globalperc, a.u_scdiscamount from u_hisservices a left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode='MISC' where a.code='"+value+"' and a.u_active=1");	 
				else result = page.executeFormattedQuery("select a.code, a.name, if(a.u_allowdiscount=1,b.u_globalperc,0) as u_globalperc, a.u_scdiscamount from u_hisservices a left join u_hishealthinhfs b on b.code='"+getInput("u_disccode")+"' and b.u_chrgcode='MISC' where a.name='"+value+"' and a.u_active=1");
				break;
		}

		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				if (isInput("u_testtype")) {
					if (getInput("u_testtype")=="") {
					} else if (result.childNodes.item(0).getAttribute("u_template")!="" && getInput("u_testtype")!=result.childNodes.item(0).getAttribute("u_template")) {
						//page.statusbar.showWarning("You cannot mix ["+result.childNodes.item(0).getAttribute("name")+"] which has different result template.");
						//return false;
					}
				}
				
				if (getInput("u_reftype")=="IP") {
					var result2 = page.executeFormattedQuery("select b.u_name from u_hisitems c inner join u_hisips a on a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"' inner join u_hisipallergicmeds b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_name=c.u_brandname or b.u_name=c.u_genericname) where c.code='"+result.childNodes.item(0).getAttribute("code")+"'");			
				} else {
					var result2 = page.executeFormattedQuery("select b.u_name from u_hisitems c inner join u_hisops a on a.company='"+getGlobal("company")+"' and a.branch='"+getGlobal("branch")+"' and a.docno='"+getInput("u_refno")+"' inner join u_hisopallergicmeds b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and (b.u_name=c.u_brandname or b.u_name=c.u_genericname) where c.code='"+result.childNodes.item(0).getAttribute("code")+"'");			
				}
				if (result2.getAttribute("result")!= "-1") {
					if (parseInt(result2.getAttribute("result"))==0) {
				
						setTableInput(table,"u_itemcode",result.childNodes.item(0).getAttribute("code"));
						setTableInput(table,"u_itemdesc",result.childNodes.item(0).getAttribute("name"));
						if (isTableInput(table,"u_template")) setTableInput(table,"u_template",result.childNodes.item(0).getAttribute("u_template"));
						if (isTableInput(table,"u_itemgroup")) setTableInput(table,"u_itemgroup",result.childNodes.item(0).getAttribute("u_group"));
						if (isTableInput(table,"u_pricing")) setTableInput(table,"u_pricing",result.childNodes.item(0).getAttribute("u_salespricing"));
						if (isTableInput(table,"u_ispackage")) setTableInput(table,"u_ispackage",result.childNodes.item(0).getAttribute("u_ispackage"));
						if (isTableInput(table,"u_isstat")) {
							setTableInput(table,"u_isstat",0);
							if (result.childNodes.item(0).getAttribute("u_statperc")>0) {
								 setTableInput(table,"u_isstat",getInput("u_isstat"));
							}
						}
						if (isTableInput(table,"u_statperc")) setTableInputPercent(table,"u_statperc",result.childNodes.item(0).getAttribute("u_statperc"));
						if (isTableInput(table,"u_iscashdisc")) setTableInput(table,"u_iscashdisc",result.childNodes.item(0).getAttribute("u_allowdiscount"));
						if (isTableInput(table,"u_isbilldisc")) setTableInput(table,"u_isbilldisc",result.childNodes.item(0).getAttribute("u_billdiscount"));
						switch (getVar("objectcode").toUpperCase()) {
							case "U_HISMEDSUPTFS":
								if (getInput("u_trxtype")=="IP" || getInput("u_trxtype")=="OP" || getInput("u_trxtype")=="SPLROOM','ER") {
									setTableInputQuantity(table,"u_quantity",result.childNodes.item(0).getAttribute("u_quantity"));
								}
								break;
						}
						computePatientItemPriceGPSHIS(table,row,type);
						
						if (isTableInput(table,"u_isautodisc")){
							if (isTableInputChecked(table,"u_isautodisc")) {
								disableTableInput(table,"u_price");
							} else {
								enableTableInput(table,"u_price");
							}
						}
						
						if (isTableInput(table,"u_pricing")){
							if (getTableInput(table,"u_pricing")=="-1") {
								enableTableInput(table,"u_unitprice");
							} else {
								disableTableInput(table,"u_unitprice");
							}						
						}
					} else {
						page.statusbar.showError("Patient is Allergic to " + result2.childNodes.item(0).getAttribute("u_name"));
						return false;
					}
				} else {
					page.statusbar.showError("Error retrieving Patient Allergic Info for Item "+result.childNodes.item(0).getAttribute("name")+". Try Again, if problem persists, check the connection.");	
					return false;
				}

				setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity") * getTableInputNumeric(table,"u_price"));
				setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_linetotal"),getTableInputNumeric(table,"u_vatrate")));
			} else {
				setTableInput(table,"u_vatcode","");
				setTableInputPercent(table,"u_vatrate",0);
				setTableInput(table,"u_itemcode","");
				setTableInput(table,"u_itemdesc","");
				setTableInputPrice(table,"u_unitprice",0);
				setTableInputPrice(table,"u_price",0);
				setTableInputAmount(table,"u_vatamount",0);
				setTableInputAmount(table,"u_linetotal",0);
				if (isTableInput(table,"u_prediscprice")) setTableInputPrice(table,"u_prediscprice",0);
				if (isTableInput(table,"u_predisclinetotal")) setTableInputAmount(table,"u_predisclinetotal",0);
				if (column=="u_itemcode") page.statusbar.showError("Invalid Item Code.");	
				else page.statusbar.showError("Invalid Item Description.");	
				return false;
			}
		} else {
			setTableInput(table,"u_vatcode","");
			setTableInputPercent(table,"u_vatrate",0);
			setTableInput(table,"u_itemcode","");
			setTableInput(table,"u_itemdesc","");
			setTableInputPrice(table,"u_unitprice",0);
			setTableInputPrice(table,"u_price",0);
			setTableInputAmount(table,"u_vatamount",0);
			setTableInputAmount(table,"u_linetotal",0);
			if (isTableInput(table,"u_prediscprice")) setTableInputPrice(table,"u_prediscprice",0);
			if (isTableInput(table,"u_predisclinetotal")) setTableInputAmount(table,"u_predisclinetotal",0);
			if (column=="u_itemcode") page.statusbar.showError("Error retrieving Item Code. Try Again, if problem persists, check the connection.");	
			else page.statusbar.showError("Error retrieving Item Description. Try Again, if problem persists, check the connection.");	
			return false;
		}
	} else {
		setTableInput(table,"u_vatcode","");
		setTableInputPercent(table,"u_vatrate",0);
		setTableInput(table,"u_itemcode","");
		setTableInput(table,"u_itemdesc","");
		setTableInputPrice(table,"u_unitprice",0);
		setTableInputPrice(table,"u_price",0);
		setTableInputAmount(table,"u_vatamount",0);
		setTableInputAmount(table,"u_linetotal",0);
		if (isTableInput(table,"u_prediscprice")) setTableInputPrice(table,"u_prediscprice",0);
		if (isTableInput(table,"u_predisclinetotal")) setTableInputAmount(table,"u_predisclinetotal",0);
	}	
	return true;
}

function resetPatientPricesGPSHIS(table,totalamountfield,vatamountfield,discamountfield,chrgcode,totalbefvatfield,totalbefdiscfield) {
	var price=0, rc =  0, discamount=0, vatamount=0, totalamount=0, totalbefdisc=0, prediscamount=0, linetotalfield="", itemcodefield="", ifGotStat = false, isStat = false; isSC = isInputChecked("u_scdisc"),isFOC=false,ifGotFOC=false;;
	if (chrgcode == null) chrgcode = "";
	if (totalbefvatfield == null) totalbefvatfield = "u_amountbefvat";
	if (totalbefdiscfield == null) totalbefdiscfield = "u_amountbefdisc";
	var statOnly = false;
	if (table=="") {
		if (isInput("u_itemcode")) itemcodefield = "u_itemcode";
		else if(isInput("u_type")) itemcodefield = "u_type";
		if (isInput("u_linetotal")) linetotalfield = "u_linetotal";
		else if(isInput("u_price")) linetotalfield = "u_price";
		else if(isInput("u_amount")) linetotalfield = "u_amount";
	}
	showAjaxProcess();
	tables = table.split(',');
	chrgcodes = chrgcode.split(',');
	for (ii = 0; ii < tables.length; ii++) {
		rc =  getTableRowCount(tables[ii]);
		if (isTableInput(tables[ii],"u_itemcode")) itemcodefield = "u_itemcode";
		else if(isTableInput(tables[ii],"u_type")) itemcodefield = "u_type";
		if (isTableInput(tables[ii],"u_linetotal")) linetotalfield = "u_linetotal";
		else if(isTableInput(tables[ii],"u_price")) linetotalfield = "u_price";
		if (isTableInput(tables[ii],"u_isfoc")) {
			ifGotFOC=true;
		}
		ifGotStat = isTableInput(tables[ii],"u_isstat");
		isStat = false;
		for (i = 1; i <= rc; i++) {
			if (isTableRowDeleted(tables[ii],i)==false) {
				isFOC=false;
				if (ifGotFOC) {
					if (getTableInput(tables[ii],"u_isfoc",i)==1) isFOC=true;
				}
				if (ifGotStat) {
					isStat = isTableInputChecked(tables[ii],"u_isstat",i);
				}
				if (getInput("u_pricelist")!="") {
					var result2 = ajaxxmlgetitemprice(getTableInput(tables[ii],itemcodefield,i),"BRANCH:" + getGlobal("branch") + ";PRICELIST:"+getInput("u_pricelist")+";GETVAT:SALES" + ";u_gpshis:1;u_disccode:" + getInput("u_disccode") +";u_chrgcode:"+chrgcodes[ii]+";u_scdisc:"+utils.booltonumber(isInputChecked("u_scdisc"))+";u_isstat:"+utils.booltonumber(isStat)+";u_pricing:"+getTableInputNumeric(tables[ii],"u_pricing",i)+";u_isautodisc:"+getTableInputNumeric(tables[ii],"u_isautodisc",i)+";u_unitprice:"+getTableInputNumeric(tables[ii],"u_unitprice",i)+";u_price:"+getTableInputNumeric(tables[ii],"u_price",i));
					//alert(tables[ii]);
					if (getInput("u_prepaid")=="1" && getInput("u_trxtype")=="PHARMACY" && getPrivate("phavatpos")=="1") {
						setTableInput(tables[ii],"u_vatcode","VATOUT",i);
						setTableInput(tables[ii],"u_vatrate",12,i);
					} else {
						setTableInput(tables[ii],"u_vatcode","VATOX",i);
						setTableInput(tables[ii],"u_vatrate",0,i);
					}
					setTableInput(tables[ii],"u_unitprice",result2.getAttribute("unitprice"),i);
					setTableInput(tables[ii],"u_price",result2.getAttribute("price"),i);
					setTableInput(tables[ii],"u_discamount",result2.getAttribute("discamount"),i);
					setTableInput(tables[ii],"u_discperc",result2.getAttribute("discperc"),i);
					setTableInputPercent(tables[ii],"u_statperc",result2.getAttribute("u_statperc"),i);
					setTableInput(tables[ii],"u_iscashdisc",result2.getAttribute("u_iscashdisc"),i);
					setTableInput(tables[ii],"u_isbilldisc",result2.getAttribute("u_isbilldisc"),i);
					if (isTableInput(tables[ii],"u_pricing")) setTableInput(tables[ii],"u_pricing",result2.getAttribute("u_pricing"),i);
												//alert(tables[ii]);
					if (isTableInput(tables[ii],"u_scdiscamount")) setTableInputAmount(tables[ii],"u_scdiscamount",result2.getAttribute("u_scdiscamount"),i);
					//alert(tables[ii]);
					if (ifGotStat && isTableInput(tables[ii],"u_statunitprice")) {
						setTableInputPrice(tables[ii],"u_statunitprice",result2.getAttribute("u_statunitprice"),i);
					}
					if (linetotalfield=="u_linetotal") {
						setTableInputAmount(tables[ii],"u_linetotal",getTableInputNumeric(tables[ii],"u_price",i)*getTableInputNumeric(tables[ii],"u_quantity",i),i);
					}
					if (isSC) {
						setTableInputAmount(tables[ii],"u_vatamount",utils.computeVAT(getTableInputNumeric(tables[ii],"u_quantity",i) * getTableInputNumeric(tables[ii],"u_unitprice",i),getTableInputNumeric(tables[ii],"u_vatrate",i)),i);
					} else {
						setTableInputAmount(tables[ii],"u_vatamount",utils.computeVAT(getTableInputNumeric(tables[ii],linetotalfield,i),getTableInputNumeric(tables[ii],"u_vatrate",i)),i);
					}
				} else {
					setTableInputAmount(tables[ii],"u_unitprice",0,i);
					setTableInputAmount(tables[ii],"u_price",0,i);
					if (linetotalfield=="u_linetotal") setTableInputAmount(tables[ii],"u_linetotal",0,i);
					setTableInputAmount(tables[ii],"u_vatamount",0,i);
				}
				if (isFOC) {
					if (linetotalfield=="u_linetotal") setTableInputAmount(tables[ii],"u_linetotal",0,i);
					setTableInputAmount(tables[ii],"u_vatamount",0,i);
				}
				totalamount += getTableInputNumeric(tables[ii],linetotalfield,i);
				//alert(tables[ii] + ":" + totalamount);
				vatamount += getTableInputNumeric(tables[ii],"u_vatamount",i);
				if (!isFOC) {
					if (isTableInput(tables[ii],"u_discamount")) discamount += getTableInputNumeric(tables[ii],"u_discamount",i) * getTableInputNumeric(tables[ii],"u_quantity",i);
					if (ifGotStat && isStat && isTableInput(tables[ii],"u_statunitprice")) {
						totalbefdisc += utils.round(getTableInputNumeric(tables[ii],"u_statunitprice",i),2) * getTableInputNumeric(tables[ii],"u_quantity",i);
					} else {
						totalbefdisc += utils.round(getTableInputNumeric(tables[ii],"u_unitprice",i),2) * getTableInputNumeric(tables[ii],"u_quantity",i);
					}
				}
			}
		}
		if (getTableInput(tables[ii],itemcodefield)!="" && getInput("u_pricelist")!="") {
			var result2 = ajaxxmlgetitemprice(getTableInput(tables[ii],itemcodefield),"BRANCH:" + getGlobal("branch") + ";PRICELIST:"+getInput("u_pricelist")+";GETVAT:SALES" + ";u_gpshis:1;u_disccode:" + getInput("u_disccode") +";u_chrgcode:"+chrgcodes[ii]+";u_scdisc:"+utils.booltonumber(isInputChecked("u_scdisc"))+";u_isstat:"+getTableInput(tables[ii],"u_isstat")+";u_pricing:"+getTableInputNumeric(tables[ii],"u_pricing")+";u_isautodisc:"+getTableInputNumeric(tables[ii],"u_isautodisc")+";u_unitprice:"+getTableInputNumeric(tables[ii],"u_unitprice")+";u_price:"+getTableInputNumeric(tables[ii],"u_price"));
			isFOC=false;
			if (ifGotFOC) {
				if (getTableInput(tables[ii],"u_isfoc")=="1") isFOC=true;
			}
			if (getInput("u_prepaid")!="1") {
				setTableInput(tables[ii],"u_vatcode","VATOX");
				setTableInput(tables[ii],"u_vatrate",0);
			} else {
				setTableInput(tables[ii],"u_vatcode",result2.getAttribute("taxcode"));
				setTableInput(tables[ii],"u_vatrate",result2.getAttribute("taxrate"));
			}
			setTableInput(tables[ii],"u_unitprice",result2.getAttribute("unitprice"));
			setTableInput(tables[ii],"u_price",result2.getAttribute("price"));
			setTableInput(tables[ii],"u_discamount",result2.getAttribute("discamount"));
			setTableInput(tables[ii],"u_discperc",result2.getAttribute("discperc"));
			setTableInputPercent(tables[ii],"u_statperc",result2.getAttribute("u_statperc"));
			setTableInput(tables[ii],"u_iscashdisc",result2.getAttribute("u_iscashdisc"));
			setTableInput(tables[ii],"u_isbilldisc",result2.getAttribute("u_isbilldisc"));
			if (isTableInput(tables[ii],"u_scdiscamount")) setTableInputAmount(tables[ii],"u_scdiscamount",result2.getAttribute("u_scdiscamount"));
			if (ifGotStat && isTableInput(tables[ii],"u_statunitprice")) {
				setTableInputPrice(tables[ii],"u_statunitprice",result2.getAttribute("u_statunitprice"));
			}

			if (linetotalfield=="u_linetotal") {
				setTableInputAmount(tables[ii],"u_linetotal",getTableInputNumeric(tables[ii],"u_price")*getTableInputNumeric(tables[ii],"u_quantity"));
			}
			setTableInputAmount(tables[ii],"u_vatamount",utils.computeVAT(getTableInputNumeric(tables[ii],linetotalfield),getTableInputNumeric(tables[ii],"u_vatrate")));

			if (isFOC) {
				if (linetotalfield=="u_linetotal") setTableInputAmount(tables[ii],"u_linetotal",0);
				setTableInputAmount(tables[ii],"u_vatamount",0);
			}


		} else {
			if (ifGotStat && isTableInput(tables[ii],"u_statunitprice")) {
				setTableInputAmount(tables[ii],"u_statunitprice",0);
			}
			setTableInputAmount(tables[ii],"u_unitprice",0);
			setTableInputAmount(tables[ii],"u_price",0);
			if (linetotalfield=="u_linetotal") setTableInputAmount(tables[ii],"u_linetotal",0);
			setTableInputAmount(tables[ii],"u_vatamount",0);
		}
	}
	setInputAmount(totalamountfield,totalamount);	
	setInputAmount(vatamountfield,vatamount);	
	setInputAmount(discamountfield,discamount);
	if (isInput(totalbefdiscfield)) setInputAmount(totalbefdiscfield,totalamount+discamount);	
	if (isInput(totalbefvatfield)) setInputAmount(totalbefvatfield,totalamount-vatamount);	
	if (isInput("u_prediscamount")) {
		setInputAmount("u_prediscamount",prediscamount);
	}
	hideAjaxProcess();
}

function setPrepaidGPSHIS() {
	if (getInput("u_reftype")=="IP") {
		if (getPrivate("prepaidip")=="1") {
			checkedInput("u_prepaid");
		} else {
			uncheckedInput("u_prepaid");
		}
	} else if (getInput("u_reftype")=="OP") {
		if (getPrivate("prepaidop")=="1") {
			checkedInput("u_prepaid");
		} else {
			uncheckedInput("u_prepaid");
		}
	} else checkedInput("u_prepaid");
	/*
	if (getInput("u_trxtype")=="PHARMACY" || getInput("u_trxtype")=="LABORATORY" || getInput("u_trxtype")=="CSR" || getInput("u_trxtype")=="XRAY" || getInput("u_trxtype")=="CTSCAN" || getInput("u_trxtype")=="ULTRASOUND") {
		if (getInput("u_reftype")=="IP") {
			if (getPrivate("prepaidip")=="1") {
				setInput("u_disconbill",0);
			} else {
				setInput("u_disconbill",1);
			}
		} else {
			if (getPrivate("prepaidop")=="1") {
				setInput("u_disconbill",0);
			} else {
				setInput("u_disconbill",1);
			}
		}
	}*/
}

function computePatientLabTotalGPSHIS(table) {
	setInputAmount("u_amount",getInputNumeric("u_quantity") * getInputNumeric("u_price"));
	setInputAmount("u_vatamount",utils.computeVAT(getInputNumeric("u_amount"),getInputNumeric("u_vatrate")));
}

function computeCreditLimitGPSHIS(table) {
	var rc = getTableRowCount(table),creditlimit=0;
	for (i = 1; i <= rc; i++) {
		if (isTableRowDeleted(table,i)==false) {
			creditlimit += getTableInputNumeric(table,"u_creditlimit",i);
		}
	}
	setInputAmount("u_creditlimit",creditlimit);
	setInputAmount("u_availablecreditlimit",getInputNumeric("u_creditlimit") - getInputNumeric("u_totalcharges"));
	setInputAmount("u_availablecreditperc", 100-((utils.divide(getInputNumeric("u_totalcharges"),getInputNumeric("u_creditlimit")))*100));
}

function resetPatientTotalAmount(table,totalamountfield,vatamountfield,discamountfield,totalbefvatfield) {
	if (totalbefvatfield == null) totalbefvatfield = "u_amountbefvat";
	if (table!="") {
		resetTableRow(table);
		clearTable(table,true);
	}
	setInputAmount(totalamountfield,0);	
	setInputAmount(vatamountfield,0);	
	setInputAmount(discamountfield,0);	
	if (isInput(totalbefvatfield)) setInputAmount(totalbefvatfield,0);	
	
}

function computePatientItemPriceGPSHIS(table,row,type) {
	if (row==0) row=null;
	if (type==null) type="MEDSUP";
	if (getInput("u_pricelist")!="") {
		var result = ajaxxmlgetitemprice(getTableInput(table,"u_itemcode",row),"BRANCH:" + getGlobal("branch") + ";PRICELIST:"+getInput("u_pricelist") +";GETVAT:SALES" + ";u_gpshis:1;u_disccode:" + getInput("u_disccode") +";u_chrgcode:"+type+";u_scdisc:"+utils.booltonumber(isInputChecked("u_scdisc"))+";u_isstat:"+utils.booltonumber(isTableInputChecked(table,"u_isstat",row))+";u_pricing:"+getTableInputNumeric(table,"u_pricing",row)+";u_isautodisc:"+utils.booltonumber(isTableInputChecked(table,"u_isautodisc",row))+";u_unitprice:"+getTableInputNumeric(table,"u_unitprice",row)+";u_price:"+getTableInputNumeric(table,"u_price",row)+";u_department:"+getInput("u_department"));
		if (getInput("u_prepaid")!="1") {
			setTableInput(table,"u_vatcode","VATOX",row);
			setTableInput(table,"u_vatrate",0,row);
		} else {
			try {
				if (getInput("u_prepaid")=="1" && getInput("u_trxtype")=="PHARMACY" && getPrivate("phavatpos")=="1") {
					setTableInput(table,"u_vatcode","VATOUT",row);
					setTableInput(table,"u_vatrate",12.00,row);
				} else {
					setTableInput(table,"u_vatcode",result.getAttribute("taxcode"),row);
					setTableInput(table,"u_vatrate",result.getAttribute("taxrate"),row);
				}
			} catch (theError) {
				setTableInput(table,"u_vatcode",result.getAttribute("taxcode"),row);
				setTableInput(table,"u_vatrate",result.getAttribute("taxrate"),row);
			}
		}
		setTableInput(table,"u_unitprice",result.getAttribute("unitprice"),row);
		setTableInput(table,"u_price",result.getAttribute("price"),row);
		setTableInput(table,"u_discperc",result.getAttribute("discperc"),row);
		setTableInputPercent(table,"u_statperc",result.getAttribute("u_statperc"),row);
		setTableInput(table,"u_discamount",result.getAttribute("discamount"),row);
		setTableInputAmount(table,"u_scdiscamount",result.getAttribute("u_scdiscamount"),row);
		if (isTableInput(table,"u_statunitprice")) setTableInputPrice(table,"u_statunitprice",result.getAttribute("u_statunitprice"));
		if (isTableInput(table,"u_pricing")) setTableInput(table,"u_pricing",result.getAttribute("u_pricing"),row);
		if (isTableInput(table,"u_iscashdisc")) setTableInput(table,"u_iscashdisc",result.getAttribute("u_iscashdisc"),row);
		if (isTableInput(table,"u_isbilldisc")) setTableInput(table,"u_isbilldisc",result.getAttribute("u_isbilldisc"),row);
	} else {
		setTableInput(table,"u_vatcode","",row);
		setTableInputPercent(table,"u_vatrate",0,row);
		setTableInputPrice(table,"u_unitprice",0,row);
		setTableInputPrice(table,"u_price",0,row);
		setTableInputPercent(table,"u_discperc",0,row);
		setTableInputAmount(table,"u_discamount",0,row);
		setTableInputAmount(table,"u_scdiscamount",0,row);
		setTableInputAmount(table,"u_statunitprice",0,row);
	}	
}

function computePatientLineTotalGPSHIS(table,row) {
	if (row==0) row= null;
	var ifGotStat = isTableInput(table,"u_isstat"), isStat = false;
	if (ifGotStat) {
		isStat = isTableInputChecked(table,"u_isstat",row);
	}
	setTableInputAmount(table,"u_linetotal",getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_price",row),row);
	if (ifGotStat && isStat && isTableInput(table,"u_statunitprice")) {
		setTableInputAmount(table,"u_discamount",getTableInputNumeric(table,"u_statunitprice",row) - getTableInputNumeric(table,"u_price",row),row);
	} else {
		try {
			if (isInputChecked("u_scdisc")) {
				setTableInputAmount(table,"u_discamount",getTableInputNumeric(table,"u_unitprice",row)-utils.computeVAT(getTableInputNumeric(table,"u_unitprice",row),getTableInputNumeric(table,"u_vatrate",row)) - getTableInputNumeric(table,"u_price",row),row);
			} else {
				setTableInputAmount(table,"u_discamount",getTableInputNumeric(table,"u_unitprice",row) - getTableInputNumeric(table,"u_price",row),row);
			}
		} catch (theError) {
			setTableInputAmount(table,"u_discamount",getTableInputNumeric(table,"u_unitprice",row) - getTableInputNumeric(table,"u_price",row),row);
		}
	}
	if (isTableInput(table,"u_vatamount")) {
		try {
			if (isInputChecked("u_scdisc")) {
				setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_unitprice",row),getTableInputNumeric(table,"u_vatrate",row)),row);
			} else {
				setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_price",row),getTableInputNumeric(table,"u_vatrate",row)),row);
			}
		} catch (theError) {
			setTableInputAmount(table,"u_vatamount",utils.computeVAT(getTableInputNumeric(table,"u_quantity",row) * getTableInputNumeric(table,"u_price",row),getTableInputNumeric(table,"u_vatrate",row)),row);
		}
	}
	if (isTableInput(table,"u_isfoc")) {
		if (isTableInputChecked(table,"u_isfoc",row)) {
			setTableInputAmount(table,"u_linetotal",0,row);
			setTableInputAmount(table,"u_vatamount",0,row);
		}
	}
	
}


function computePatientTotalAmountGPSHIS(table,totalamountfield,vatamountfield,discamountfield,linetotalfield,totalbefvatfield,totalbefdiscfield) {
	if (linetotalfield == null) linetotalfield = "u_linetotal";
	if (totalbefvatfield == null) totalbefvatfield = "u_amountbefvat";
	if (totalbefdiscfield == null) totalbefdiscfield = "u_amountbefdisc";
	var rc = 0, discamount=0, totalamount=0, vatamount=0, prediscamount=0, totalbefdisc=0,ifGotFOC=false,isFOC=false,isCredit=false,ifGotSelected=false,isSelected=false;
	var isRequest = isInput("u_ishb"), ishb=0, ispf=0, ispm=0;
	var ifGotStat = false, isStat = false;
	if (isInput("u_payrefno") && isInput("u_balance")) {
		if (getInput("u_payrefno")!="" && getInput("u_prepaid")=="1") isCredit=true;
	}
	var tables = table.split(',');
	
	for (var ii=0;ii<tables.length;ii++) {
		rc =  getTableRowCount(tables[ii]);
		
		ifGotSelected = isTableInput(tables[ii],"u_selected");
		ifGotFOC = isTableInput(tables[ii],"u_isfoc");
		ifGotStat = isTableInput(tables[ii],"u_isstat");
		isRequest = isTableInput(tables[ii],"u_itemgroup");
		
		for (iii = 1; iii <= rc; iii++) {
			if (isTableRowDeleted(tables[ii],iii)==false) {
				if (isRequest) {
					if (getTableInput(tables[ii],"u_itemgroup",iii)=="PRF") ispf++;
					else if (getTableInput(tables[ii],"u_itemgroup",iii)=="PRM") ispm++;
					else ishb++;
				}
				isFOC=false;
				isStat = false;
				if (ifGotFOC) {
					if (getTableInput(tables[ii],"u_isfoc",iii)==1) isFOC=true;
				}
				if (ifGotStat) {
					isStat = isTableInputChecked(tables[ii],"u_isstat",iii);
				}
				
				if (ifGotSelected) isSelected = getTableInput(tables[ii],"u_selected",iii)=="1";
				
				if (!ifGotSelected || (ifGotSelected && isSelected)) {
					totalamount += getTableInputNumeric(tables[ii],linetotalfield,iii);
					vatamount += getTableInputNumeric(tables[ii],"u_vatamount",iii);
					if (!isFOC) {
						//alert(getTableInputNumeric(tables[ii],"u_discamount",iii) );
						discamount += getTableInputNumeric(tables[ii],"u_discamount",iii) * getTableInputNumeric(tables[ii],"u_quantity",iii);
						if (ifGotStat && isStat && isTableInput(tables[ii],"u_statunitprice")) {
							totalbefdisc += utils.round(getTableInputNumeric(tables[ii],"u_statunitprice",iii),2) * getTableInputNumeric(tables[ii],"u_quantity",iii);
						} else {
							totalbefdisc += utils.round(getTableInputNumeric(tables[ii],"u_unitprice",iii),2) * getTableInputNumeric(tables[ii],"u_quantity",iii);
						}
					}
				}
			}
		}
	}
	setInputAmount(totalamountfield,totalamount);	
	setInputAmount(vatamountfield,vatamount);	
	setInputAmount(discamountfield,discamount);	
	if (isInput(totalbefdiscfield)) setInputAmount(totalbefdiscfield,totalbefdisc);	
	if (isInput(totalbefvatfield)) setInputAmount(totalbefvatfield,totalamount - vatamount);	
	if (isCredit) setInputAmount("u_balance",totalamount);	
	if (isRequest) {
		setInput("u_ishb",0);
		setInput("u_ispf",0);
		setInput("u_ispm",0);
		if (ishb>0) setInput("u_ishb",1);
		if (ispf>0) setInput("u_ispf",1);
		if (ispm>0) setInput("u_ispm",1);
	}
}

function setPatientPackageItemsGPSHIS(p_table, p_itemcode,p_qty) {
	var data = new Array();
	var result = page.executeFormattedQuery("select u_itemcode, u_itemdesc, u_uom, u_qtyperpack, u_department from u_hisitempacks where code='"+p_itemcode+"'");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				data["u_packagecode"] = p_itemcode;
				data["u_itemcode"] = result.childNodes.item(iii).getAttribute("u_itemcode");
				data["u_itemdesc"] = result.childNodes.item(iii).getAttribute("u_itemdesc");
				data["u_uom"] = result.childNodes.item(iii).getAttribute("u_uom");
				data["u_qtyperpack"] = formatNumber(result.childNodes.item(iii).getAttribute("u_qtyperpack"),"quantity");
				data["u_packageqty"] = formatNumber(p_qty,"quantity");
				data["u_quantity"] = formatNumber(p_qty*parseFloat(result.childNodes.item(iii).getAttribute("u_qtyperpack")),"quantity");
				data["u_department"] = result.childNodes.item(iii).getAttribute("u_department");
				insertTableRowFromArray(p_table,data);
			}
		} else {
			page.statusbar.showError("No. Package Items");	
			hideAjaxProcess();
			return false;
		}
	} else {
		hideAjaxProcess();
		page.statusbar.showError("Error retrieving Package Items. Try Again, if problem persists, check the connection.");	
		return false;
	}				
	return true;
}


function savePatientAbbreviationGPSHIS(key,abbrev,value) {
	var msgcnt = 0;	
	try {
		http = getHTTPObject(); 
		http.open("GET", "udp.php?objectcode=u_ajaxsetabbreviations&key="+key+"&abbrev="+abbrev+"&value="+value, false);
		http.send(null);
		var result = http.responseText.trim();
		alert(result);
		http.send(null);
	} catch (theError) {
	}	
}

function authenticateGPSHIS(userid,password) {
	var msgcnt = 0;	
	try {
		showAjaxProcess('Authenticating...');
		http = getHTTPObject(); 
		http.open("GET", "udp.php?objectcode=u_ajaxauthenticate&userid="+userid.replaceSpecialChar()+"&password="+password.replaceSpecialChar(), false);
		http.send(null);
		hideAjaxProcess();
		if (http.responseText=="ok") {
			return true;
		} else {
			alert(http.responseText);
			return false;
		}
	} catch (theError) {
		hideAjaxProcess();
		alert("Network Error:"+theError.message);
		return false;
	}	
}

function checkauthenticateGPSHIS(userid,password) {
	if (isTableInput("T50","userid")) {
		if (getTableInput("T50","userid")=="") {
			showPopupFrame("popupFrameAuthentication",true);
			focusTableInput("T50","userid");
			return false;
		} else hidePopupFrame("popupFrameAuthentication");
		if (!authenticateGPSHIS(getTableInput("T50","userid"),getTableInput("T50","password"))) {
			setTableInput("T50","userid","");
			showPopupFrame("popupFrameAuthentication",true);
			focusTableInput("T50","userid");
			return false;
		}
	}
	return true;
}

