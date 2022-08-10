<?php
 include_once("./classes/documentlinesschema_br.php");
 include_once("./classes/marketingdocuments.php");
 include_once("./classes/marketingdocumentitems.php");
 include_once("./classes/marketingdocumentcashcards.php");
// include_once("./classes/marketingdocumentcheques.php");
 include_once("./classes/marketingdocumentcreditcards.php");
 include_once("./classes/stocktransfers.php");
 include_once("./classes/stocktransferitems.php");
 include_once("./classes/journalvouchers.php");
 include_once("./classes/journalvoucheritems.php");
 include_once("./classes/collections.php");
 include_once("./classes/collectionscheques.php");
 include_once("./classes/collectionscreditcards.php");
 include_once("./classes/collectionsinvoices.php");
 include_once("./classes/customers.php");
 include_once("./utils/postingperiods.php");
 include_once("./utils/branches.php");
 include_once("./utils/customers.php");
 include_once("./utils/items.php");
 include_once("./utils/taxes.php");
 include_once("./classes/usermsgs.php");
 include_once("./JimacDataTrxSync.php");


function onBeforeAddEventdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisips":
			$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
			$obju_HISPatientRegs = new masterdatalinesschema_br(null,$objConnection,"u_hispatientregs");
			$obju_HISIPVs = new documentlinesschema_br(null,$objConnection,"u_hisipvs");
			$objRs = new recordset(null,$objConnection);
			/*
			$objRs->queryopen("select docno from u_hisips where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_patientid='".$objTable->getudfvalue("u_patientid")."' and u_enddate is null");
			if ($objRs->queryfetchrow("NAME")) {
				return raiseError("Patient still have active In-Patient [".$objRs->fields["docno"]."] record.");
			}
			$objRs->queryopen("select docno from u_hisops where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_patientid='".$objTable->getudfvalue("u_patientid")."' and u_enddate is null");
			if ($objRs->queryfetchrow("NAME")) {
				return raiseError("Patient still have active Out-Patient [".$objRs->fields["docno"]."] record.");
			}
				*/
			if ($objTable->getudfvalue("u_patientid")=="" && $objTable->getudfvalue("u_patientname")=="") {
				return raiseError("Cannot add registration with empty patient id and name.");
			}
			if ($objTable->getudfvalue("u_patientid")!="") {
				if ($obju_HISPatients->getbykey($objTable->getudfvalue("u_patientid"))) {
					$obju_HISPatientRegs->prepareadd();
					$obju_HISPatientRegs->code = $obju_HISPatients->code;
					$obju_HISPatientRegs->lineid = getNextIdByBranch("u_hispatientregs",$objConnection);					
					$obju_HISPatientRegs->setudfvalue("u_reftype","IP");
					$obju_HISPatientRegs->setudfvalue("u_refno",$objTable->docno);
					$obju_HISPatientRegs->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
					$obju_HISPatientRegs->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
					$obju_HISPatientRegs->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
					$obju_HISPatientRegs->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
					$obju_HISPatientRegs->setudfvalue("u_status",$objTable->docstatus);
					$obju_HISPatientRegs->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
					$obju_HISPatientRegs->setudfvalue("u_expiretime",$objTable->getudfvalue("u_expiretime"));
					$obju_HISPatientRegs->privatedata["header"] = $obju_HISPatients;
					$actionReturn = $obju_HISPatientRegs->add();
					
					if ($actionReturn && $objTable->getudfvalue("u_vsdate")!="") {
						$obju_HISIPVs->prepareadd();
						$obju_HISIPVs->docid = $objTable->docid;
						$obju_HISIPVs->lineid = getNextIdByBranch("u_hisipvs",$objConnection);	
						$obju_HISIPVs->setudfvalue("u_vsdate",$objTable->getudfvalue("u_vsdate"));
						$obju_HISIPVs->setudfvalue("u_vstime",$objTable->getudfvalue("u_vstime"));
						$obju_HISIPVs->setudfvalue("u_bt_c",$objTable->getudfvalue("u_bt_c"));
						$obju_HISIPVs->setudfvalue("u_bt_f",$objTable->getudfvalue("u_bt_f"));
						$obju_HISIPVs->setudfvalue("u_bp_s",$objTable->getudfvalue("u_bp_s"));
						$obju_HISIPVs->setudfvalue("u_bp_d",$objTable->getudfvalue("u_bp_d"));
						$obju_HISIPVs->setudfvalue("u_hr",$objTable->getudfvalue("u_hr"));
						$obju_HISIPVs->setudfvalue("u_rr",$objTable->getudfvalue("u_rr"));
						$obju_HISIPVs->setudfvalue("u_o2sat",$objTable->getudfvalue("u_o2sat"));
						$obju_HISIPVs->privatedata["header"] = $objTable;
						$actionReturn = $obju_HISIPVs->add();
					}
					
					if (intval($objTable->getudfvalue("u_visitno"))==0) {
						$obju_HISPatients->setudfvalue("u_visitcount",intval($obju_HISPatients->getudfvalue("u_visitcount"))+1);
						$objTable->setudfvalue("u_visitno",$obju_HISPatients->getudfvalue("u_visitcount"));
					}
					$obju_HISPatients->setudfvalue("u_visitcount",max($obju_HISPatients->getudfvalue("u_visitcount"),$objTable->getudfvalue("u_visitno")));
					
					if ($objTable->getudfvalue("u_expired")!=$obju_HISPatients->getudfvalue("u_expired")) {
						$obju_HISPatients->setudfvalue("u_expired",$objTable->getudfvalue("u_expired"));
					}
					if ($objTable->getudfvalue("u_expiredate")!=$obju_HISPatients->getudfvalue("u_expiredate")) {
						$obju_HISPatients->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
					}
					if ($objTable->getudfvalue("u_informantname")!="" && $obju_HISPatients->getudfvalue("u_informantname")=="") {
						$obju_HISPatients->setudfvalue("u_informantname",$objTable->getudfvalue("u_informantname"));
						$obju_HISPatients->setudfvalue("u_informantaddress",$objTable->getudfvalue("u_informantaddress"));
						$obju_HISPatients->setudfvalue("u_informantrelationship",$objTable->getudfvalue("u_informantrelationship"));
						$obju_HISPatients->setudfvalue("u_informanttelno",$objTable->getudfvalue("u_informanttelno"));
					}
					
					if ($objTable->getudfvalue("u_bmi")==0 && ($objTable->getudfvalue("u_height_ft")!=0 || $objTable->getudfvalue("u_height_in")!=0) && $objTable->getudfvalue("u_weight_kg")!=0 ) {
						$u_bmi = (floatval($objTable->getudfvalue("u_height_ft"))*12)+floatval($objTable->getudfvalue("u_height_in"));
						$u_bmi = $u_bmi / 39.370;
						$u_bmi = $u_bmi * $u_bmi;
						$u_bmi = objutils::divide(floatval($objTable->getudfvalue("u_weight_kg")),$u_bmi);
						if ($u_bmi>0) {
							if ($u_bmi<18) $objTable->setudfvalue("u_bmistatus","Underweight");
							else if ($u_bmi<25) $objTable->setudfvalue("u_bmistatus","Desirable");
							else if ($u_bmi<30) $objTable->setudfvalue("u_bmistatus","Overweight");
							else $objTable->setudfvalue("u_bmistatus","Obese");
						} else $objTable->setudfvalue("u_bmistatus","");
						$objTable->setudfvalue("u_bmi",$u_bmi);
					}
	
					$age = getAgeOnDate($obju_HISPatients->getudfvalue("u_birthdate"),$objTable->getudfvalue("u_startdate"));
					$objTable->setudfvalue("u_age_y",$age[0]);
					$objTable->setudfvalue("u_age_m",$age[1]);
					$objTable->setudfvalue("u_age_d",$age[2]);
					if ($actionReturn) $actionReturn = $obju_HISPatients->update($obju_HISPatients->code,$obju_HISPatients->rcdversion);
					//$actionReturn = raiseError("Unable to find Patient In-Patient Reference No. [".$objTable->docno."]");	
				} else $actionReturn = raiseError("Unable to find Patient ID[".$objTable->getudfvalue("u_patientid")."]");			
			}	
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select group_concat(b.name separator ', ') from u_hisipins a inner join u_hishealthins b on b.code=a.u_inscode where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
				if ($objRs->queryfetchrow()) $objTable->setudfvalue("u_healthins",$objRs->fields[0]);
				else $objTable->setudfvalue("u_healthins","");
			}
			
			break;
		case "u_hisops":
			$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
			$obju_HISPatientRegs = new masterdatalinesschema_br(null,$objConnection,"u_hispatientregs");
			$obju_HISOPVs = new documentlinesschema_br(null,$objConnection,"u_hisopvs");
			$objRs = new recordset(null,$objConnection);
			/*
			$objRs->queryopen("select docno from u_hisips where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_patientid='".$objTable->getudfvalue("u_patientid")."' and u_enddate is null");
			if ($objRs->queryfetchrow("NAME")) {
				return raiseError("Patient still have active In-Patient [".$objRs->fields["docno"]."] record.");
			}
			$objRs->queryopen("select docno from u_hisops where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_patientid='".$objTable->getudfvalue("u_patientid")."' and u_enddate is null");
			if ($objRs->queryfetchrow("NAME")) {
				return raiseError("Patient still have active Out-Patient [".$objRs->fields["docno"]."] record.");
			}
			*/
			if ($objTable->getudfvalue("u_patientid")=="" && $objTable->getudfvalue("u_patientname")=="") {
				return raiseError("Cannot add registration with empty patient id and name.");
			}
			if ($objTable->getudfvalue("u_patientid")!="") {
				if ($obju_HISPatients->getbykey($objTable->getudfvalue("u_patientid"))) {
					$obju_HISPatientRegs->prepareadd();
					$obju_HISPatientRegs->code = $obju_HISPatients->code;
					$obju_HISPatientRegs->lineid = getNextIdByBranch("u_hispatientregs",$objConnection);					
					$obju_HISPatientRegs->setudfvalue("u_reftype","OP");
					$obju_HISPatientRegs->setudfvalue("u_refno",$objTable->docno);
					$obju_HISPatientRegs->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
					$obju_HISPatientRegs->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
					$obju_HISPatientRegs->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
					$obju_HISPatientRegs->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
					$obju_HISPatientRegs->setudfvalue("u_status",$objTable->docstatus);
					$obju_HISPatientRegs->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
					$obju_HISPatientRegs->setudfvalue("u_expiretime",$objTable->getudfvalue("u_expiretime"));
					$obju_HISPatientRegs->privatedata["header"] = $obju_HISPatients;
					$actionReturn = $obju_HISPatientRegs->add();
					
					if ($actionReturn && $objTable->getudfvalue("u_vsdate")!="") {
						$obju_HISOPVs->prepareadd();
						$obju_HISOPVs->docid = $objTable->docid;
						$obju_HISOPVs->lineid = getNextIdByBranch("u_hisopvs",$objConnection);	
						$obju_HISOPVs->setudfvalue("u_vsdate",$objTable->getudfvalue("u_vsdate"));
						$obju_HISOPVs->setudfvalue("u_vstime",$objTable->getudfvalue("u_vstime"));
						$obju_HISOPVs->setudfvalue("u_bt_c",$objTable->getudfvalue("u_bt_c"));
						$obju_HISOPVs->setudfvalue("u_bt_f",$objTable->getudfvalue("u_bt_f"));
						$obju_HISOPVs->setudfvalue("u_bp_s",$objTable->getudfvalue("u_bp_s"));
						$obju_HISOPVs->setudfvalue("u_bp_d",$objTable->getudfvalue("u_bp_d"));
						$obju_HISOPVs->setudfvalue("u_hr",$objTable->getudfvalue("u_hr"));
						$obju_HISOPVs->setudfvalue("u_rr",$objTable->getudfvalue("u_rr"));
						$obju_HISOPVs->setudfvalue("u_o2sat",$objTable->getudfvalue("u_o2sat"));
						$obju_HISOPVs->privatedata["header"] = $objTable;
						$actionReturn = $obju_HISOPVs->add();
					}
					
					if (intval($objTable->getudfvalue("u_visitno"))==0) {
						$obju_HISPatients->setudfvalue("u_visitcount",intval($obju_HISPatients->getudfvalue("u_visitcount"))+1);
						$objTable->setudfvalue("u_visitno",$obju_HISPatients->getudfvalue("u_visitcount"));
					}
					$obju_HISPatients->setudfvalue("u_visitcount",max($obju_HISPatients->getudfvalue("u_visitcount"),$objTable->getudfvalue("u_visitno")));
					if ($objTable->getudfvalue("u_expired")!=$obju_HISPatients->getudfvalue("u_expired")) {
						$obju_HISPatients->setudfvalue("u_expired",$objTable->getudfvalue("u_expired"));
					}
					if ($objTable->getudfvalue("u_expiredate")!=$obju_HISPatients->getudfvalue("u_expiredate")) {
						$obju_HISPatients->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
					}
					if ($objTable->getudfvalue("u_informantname")!="" && $obju_HISPatients->getudfvalue("u_informantname")=="") {
						$obju_HISPatients->setudfvalue("u_informantname",$objTable->getudfvalue("u_informantname"));
						$obju_HISPatients->setudfvalue("u_informantaddress",$objTable->getudfvalue("u_informantaddress"));
						$obju_HISPatients->setudfvalue("u_informantrelationship",$objTable->getudfvalue("u_informantrelationship"));
						$obju_HISPatients->setudfvalue("u_informanttelno",$objTable->getudfvalue("u_informanttelno"));
					}
					$age = getAgeOnDate($obju_HISPatients->getudfvalue("u_birthdate"),$objTable->getudfvalue("u_startdate"));
					$objTable->setudfvalue("u_age_y",$age[0]);
					$objTable->setudfvalue("u_age_m",$age[1]);
					$objTable->setudfvalue("u_age_d",$age[2]);
					if ($actionReturn) $actionReturn = $obju_HISPatients->update($obju_HISPatients->code,$obju_HISPatients->rcdversion);
						//$actionReturn = raiseError("Unable to find Patient In-Patient Reference No. [".$objTable->docno."]");	
				} else $actionReturn = raiseError("Unable to find Patient ID[".$objTable->getudfvalue("u_patientid")."]");			
			}	
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select group_concat(b.name separator ', ') from u_hisopins a inner join u_hishealthins b on b.code=a.u_inscode where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
				if ($objRs->queryfetchrow()) $objTable->setudfvalue("u_healthins",$objRs->fields[0]);
				else $objTable->setudfvalue("u_healthins","");
			}
			break;		
		case "u_hisdietaries":
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_editby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}
			break;
		case "u_hispackagerequests":
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_requestby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}		
			$objRs = new recordset(null,$objConnection);
			$objRs2 = new recordset(null,$objConnection);
			$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
			$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hisrequestitems");
			$obju_HISRequestItemPacks = new documentlinesschema_br(null,$objConnection,"u_hisrequestitempacks");
			$obju_HISPackageRequestDocs = new documentlinesschema_br(null,$objConnection,"u_hispackagerequestdocs");
			$actionReturn = $objRs->executesql("delete from u_hispackagerequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) $actionReturn = $objRs->executesql("delete from u_hispackagerequestitempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);

			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					} else {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					}	
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							if ($obju_HISIPs->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_prepaid")!="1") {
								return raiseError("Patient already tag as May Go Home. You can only add request with term cash.");
							}
						}
						if ($obju_HISIPs->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_prepaid")!="1") {
							return raiseError("Patient bill already generated. You can only add request with term cash.");
						}
						$obju_HISIPs->setudfvalue("u_reqcnt",$obju_HISIPs->getudfvalue("u_reqcnt")+1);
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}
			}						
			
			if ($actionReturn) {
				$objRs->queryopen("select u_department from u_hispackagerequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' group by u_department");
				while ($objRs->queryfetchrow()) {
					$obju_HISRequests->prepareadd();
					$obju_HISRequests->docno = getNextSeriesNoByBranch("u_hisrequests",0,$objTable->getudfvalue("u_requestdate"),$objConnection,true);
					$obju_HISRequests->docid = getNextIdByBranch("u_hisrequests",$objConnection);
					$obju_HISRequests->docstatus = "O";
					$obju_HISRequests->setudfvalue("u_requestby",$objTable->getudfvalue("u_requestby"));
					$obju_HISRequests->setudfvalue("u_trxtype","OP");
					$obju_HISRequests->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
					$obju_HISRequests->setudfvalue("u_requestdepartment",$objTable->getudfvalue("u_requestdepartment"));
					$obju_HISRequests->setudfvalue("u_department",$objRs->fields[0]);
					$obju_HISRequests->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
					$obju_HISRequests->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
					$obju_HISRequests->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
					$obju_HISRequests->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
					$obju_HISRequests->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
					$obju_HISRequests->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
					$obju_HISRequests->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
					$obju_HISRequests->setudfvalue("u_requestdate",$objTable->getudfvalue("u_requestdate"));
					$obju_HISRequests->setudfvalue("u_requesttime",$objTable->getudfvalue("u_requesttime"));
					$obju_HISRequests->setudfvalue("u_duedate",$objTable->getudfvalue("u_duedate"));
					$obju_HISRequests->setudfvalue("u_duetime",$objTable->getudfvalue("u_duetime"));
					$obju_HISRequests->setudfvalue("u_scdisc",$objTable->getudfvalue("u_scdisc"));
					$obju_HISRequests->setudfvalue("u_isstat",$objTable->getudfvalue("u_isstat"));
					$obju_HISRequests->setudfvalue("u_disconbill",$objTable->getudfvalue("u_disconbill"));
					$obju_HISRequests->setudfvalue("u_ishb",$objTable->getudfvalue("u_ishb"));
					$obju_HISRequests->setudfvalue("u_ispf",$objTable->getudfvalue("u_ispf"));
					$obju_HISRequests->setudfvalue("u_ispm",$objTable->getudfvalue("u_ispm"));
					$obju_HISRequests->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
					$obju_HISRequests->setudfvalue("u_birthdate",$objTable->getudfvalue("u_birthdate"));
					$obju_HISRequests->setudfvalue("u_age",$objTable->getudfvalue("u_age"));
					$obju_HISRequests->setudfvalue("u_packageno",$objTable->docno);
					$u_amountbefdisc=0;
					$u_discamount=0;
					$u_vatamount=0;
					$u_amount=0;
					$objRs2->queryopen("select * from u_hispackagerequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_department='".$objRs->fields[0]."'");
					while ($objRs2->queryfetchrow("NAME")) {
						$obju_HISRequestItems->prepareadd();
						$obju_HISRequestItems->docid = $obju_HISRequests->docid;
						$obju_HISRequestItems->lineid = getNextIdByBranch("u_hisrequestitems",$objConnection);
						$obju_HISRequestItems->privatedata["header"] = $obju_HISRequests;
						$obju_HISRequestItems->setudfvalue("u_itemgroup",$objRs2->fields["U_ITEMGROUP"]);
						$obju_HISRequestItems->setudfvalue("u_itemcode",$objRs2->fields["U_ITEMCODE"]);
						$obju_HISRequestItems->setudfvalue("u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
						$obju_HISRequestItems->setudfvalue("u_quantity",$objRs2->fields["U_QUANTITY"]);
						$obju_HISRequestItems->setudfvalue("u_uom",$objRs2->fields["U_UOM"]);
						$obju_HISRequestItems->setudfvalue("u_unitprice",$objRs2->fields["U_UNITPRICE"]);
						$obju_HISRequestItems->setudfvalue("u_price",$objRs2->fields["U_PRICE"]);
						$obju_HISRequestItems->setudfvalue("u_discperc",$objRs2->fields["U_DISCPERC"]);
						$obju_HISRequestItems->setudfvalue("u_discamount",$objRs2->fields["U_DISCAMOUNT"]);
						$obju_HISRequestItems->setudfvalue("u_scdiscamount",$objRs2->fields["U_SCDISCAMOUNT"]);
						$obju_HISRequestItems->setudfvalue("u_scstatdiscamount",$objRs2->fields["U_SCSTATDISCAMOUNT"]);
						$obju_HISRequestItems->setudfvalue("u_linetotal",$objRs2->fields["U_LINETOTAL"]);
						$obju_HISRequestItems->setudfvalue("u_vatamount",$objRs2->fields["U_VATAMOUNT"]);
						$obju_HISRequestItems->setudfvalue("u_vatcode",$objRs2->fields["U_VATCODE"]);
						$obju_HISRequestItems->setudfvalue("u_vatrate",$objRs2->fields["U_VATRATE"]);
						$obju_HISRequestItems->setudfvalue("u_isstat",$objRs2->fields["U_ISSTAT"]);
						$obju_HISRequestItems->setudfvalue("u_statperc",$objRs2->fields["U_STATPERC"]);
						$obju_HISRequestItems->setudfvalue("u_statunitprice",$objRs2->fields["U_STATUNITPRICE"]);
						$obju_HISRequestItems->setudfvalue("u_pricing",$objRs2->fields["U_PRICING"]);
						$obju_HISRequestItems->setudfvalue("u_doctorid",$objRs2->fields["U_DOCTORID"]);
						$obju_HISRequestItems->setudfvalue("u_doctorname",$objRs2->fields["U_DOCTORNAME"]);
						$obju_HISRequestItems->setudfvalue("u_remarks",$objRs2->fields["U_REMARKS"]);
						$obju_HISRequestItems->setudfvalue("u_ispackage",$objRs2->fields["U_ISPACKAGE"]);
						$obju_HISRequestItems->setudfvalue("u_isfoc",$objRs2->fields["U_ISFOC"]);
						$obju_HISRequestItems->setudfvalue("u_isautodisc",$objRs2->fields["U_ISAUTODISC"]);
						$obju_HISRequestItems->setudfvalue("u_iscashdisc",$objRs2->fields["U_ISCASHDISC"]);
						$obju_HISRequestItems->setudfvalue("u_isbilldisc",$objRs2->fields["U_ISBILLDISC"]);
						$obju_HISRequestItems->setudfvalue("u_template",$objRs2->fields["U_TEMPLATE"]);
						$actionReturn = $obju_HISRequestItems->add();
						if (!$actionReturn) break;
						$u_amountbefdisc+=$objRs2->fields["U_LINETOTAL"]+($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
						$u_discamount+=$objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"];
						$u_vatamount+=$objRs2->fields["U_VATAMOUNT"];
						$u_amount+=$objRs2->fields["U_LINETOTAL"];
					}
				
					if ($actionReturn) {
						$obju_HISRequests->setudfvalue("u_amountbefdisc",$u_amountbefdisc);
						$obju_HISRequests->setudfvalue("u_discamount",$u_discamount);
						$obju_HISRequests->setudfvalue("u_vatamount",$u_vatamount);
						$obju_HISRequests->setudfvalue("u_amount",$u_amount);
						$actionReturn = $obju_HISRequests->add();
					}	

					if ($actionReturn) {
						$obju_HISPackageRequestDocs->prepareadd();
						$obju_HISPackageRequestDocs->docid = $objTable->docid;
						$obju_HISPackageRequestDocs->lineid = getNextIdByBranch("u_hispackagerequestdocs",$objConnection);
						$obju_HISPackageRequestDocs->setudfvalue("u_reqno",$obju_HISRequests->docno);
						$obju_HISPackageRequestDocs->setudfvalue("u_department",$obju_HISRequests->getudfvalue("u_department"));
						$obju_HISPackageRequestDocs->privatedata["header"] = $objTable;
						$actionReturn = $obju_HISPackageRequestDocs->add();
					}
					if (!$actionReturn) break;
				}
			}
			//if ($actionReturn) $actionReturn = raiseError("rey");
			break;
		case "u_hisrequests":
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_requestby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					} else {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					}	
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							if ($obju_HISIPs->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_prepaid")!="1") {
								return raiseError("Patient already tag as May Go Home. You can only add request with term cash.");
							}
						}
						if ($obju_HISIPs->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_prepaid")!="1") {
							return raiseError("Patient bill already generated. You can only add request with term cash.");
						}
						$obju_HISIPs->setudfvalue("u_reqcnt",$obju_HISIPs->getudfvalue("u_reqcnt")+1);
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}
			}			
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select sum(if(u_isfoc=1,0,if(u_isstat=1,u_statunitprice,u_unitprice))*u_quantity) as u_amountbefdisc, sum(if(u_isfoc,0,u_vatamount)) as u_vatamount, sum(if(u_isfoc,0,u_discamount)*u_quantity) as u_discamount, sum(if(u_isfoc,0,u_linetotal)) as u_amount from u_hisrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
				if ($objRs->queryfetchrow("NAME")) {
					$objTable->setudfvalue("u_amountbefdisc",$objRs->fields["u_amountbefdisc"]);
					if ($objTable->getudfvalue("u_scdisc")==1) {
						$objTable->setudfvalue("u_discamount",$objRs->fields["u_amountbefdisc"] - ($objRs->fields["u_amount"]+$objRs->fields["u_vatamount"]));
					} else {
						$objTable->setudfvalue("u_discamount",$objRs->fields["u_amountbefdisc"] - $objRs->fields["u_amount"]);
					}	
					$objTable->setudfvalue("u_vatamount",$objRs->fields["u_vatamount"]);
					$objTable->setudfvalue("u_amount",$objRs->fields["u_amount"]);
				}
			}				
					
			break;
		case "u_hischarges":
			//if (!isPostingDateValid($objTable->getudfvalue("u_startdate"),$objTable->getudfvalue("u_startdate"),$objTable->getudfvalue("u_startdate"))) return false;		
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_chargeby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}		
			
			$objRs = new recordset(null,$objConnection);
			$actionReturn = $objRs->executesql("delete from u_hischargeitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) $actionReturn = $objRs->executesql("delete from u_hischargeitempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) $actionReturn = $objRs->executesql("delete from u_hischargecases where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select sum(if(u_isfoc=1,0,if(u_isstat=1,u_statunitprice,u_unitprice))*u_quantity) as u_amountbefdisc, sum(if(u_isfoc,0,u_vatamount)) as u_vatamount, sum(if(u_isfoc,0,u_discamount)*u_quantity) as u_discamount, sum(if(u_isfoc,0,u_linetotal)) as u_amount from u_hischargeitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
				if ($objRs->queryfetchrow("NAME")) {
					$objTable->setudfvalue("u_amountbefdisc",$objRs->fields["u_amountbefdisc"]);
					$objTable->setudfvalue("u_discamount",$objRs->fields["u_amountbefdisc"] - $objRs->fields["u_amount"]);
					$objTable->setudfvalue("u_vatamount",$objRs->fields["u_vatamount"]);
					$objTable->setudfvalue("u_amount",$objRs->fields["u_amount"]);
				}
			}						
			
			
			$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
			$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hisrequestitems");
			$obju_HISRequestItemPacks = new documentlinesschema_br(null,$objConnection,"u_hisrequestitempacks");
			if ($actionReturn && $objTable->getudfvalue("u_requestno")!="") {
				if ($obju_HISRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
					if ($actionReturn) {
						$objRs->queryopen("select a.u_itemcode, a.u_itemdesc, a.u_quantity from u_hischargeitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							if ($obju_HISRequestItems->getbysql("DOCID='$obju_HISRequests->docid' AND U_ITEMCODE='".$objRs->fields["u_itemcode"]."'")) {
								$obju_HISRequestItems->setudfvalue("u_chrgqty",$obju_HISRequestItems->getudfvalue("u_chrgqty")+$objRs->fields["u_quantity"]);
								if ($obju_HISRequestItems->getudfvalue("u_chrgqty")>$obju_HISRequestItems->getudfvalue("u_quantity")) {
									return raiseError("Charge Qty [".$obju_HISRequestItems->getudfvalue("u_chrgqty")."] exceeded Requested Qty [".$obju_HISRequestItems->getudfvalue("u_quantity")."] for Request No.[".$objTable->getudfvalue("u_requestno")."] for item [".$objRs->fields["u_itemdesc"]."]");	
								}
								$actionReturn = $obju_HISRequestItems->update($obju_HISRequestItems->docid,$obju_HISRequestItems->lineid,$obju_HISRequestItems->rcdversion);
							} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."] for Item [".$objRs->fields["u_itemdesc"]."]");	
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn) {
						$objRs->queryopen("select a.u_packagecode, a.u_itemcode, a.u_itemdesc, a.u_quantity from u_hischargeitempacks a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							if ($obju_HISRequestItemPacks->getbysql("DOCID='$obju_HISRequests->docid' and U_PACKAGECODE='".$objRs->fields["u_packagecode"]."' and U_ITEMCODE='".$objRs->fields["u_itemcode"]."'")) {
								$obju_HISRequestItemPacks->setudfvalue("u_chrgqty",$obju_HISRequestItemPacks->getudfvalue("u_chrgqty")+$objRs->fields["u_quantity"]);
								if ($obju_HISRequestItemPacks->getudfvalue("u_chrgqty")>$obju_HISRequestItemPacks->getudfvalue("u_quantity")) {
									return raiseError("Charge Qty [".$obju_HISRequestItemPacks->getudfvalue("u_chrgqty")."] exceeded Requested Qty [".$obju_HISRequestItemPacks->getudfvalue("u_quantity")."] for Request No.[".$objTable->getudfvalue("u_requestno")."] for Package Items [".$objRs->fields["u_itemdesc"]."]");	
								}
								$actionReturn = $obju_HISRequestItemPacks->update($obju_HISRequestItemPacks->docid,$obju_HISRequestItemPacks->lineid,$obju_HISRequestItemPacks->rcdversion);
							} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."] for Package Item [".$objRs->fields["u_itemdesc"]."]");	
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn) {
						$objRs2 = new recordset(null,$objConnection);
						$objRs2->queryopen("select sum(u_openqty) from (select sum(u_quantity-u_chrgqty) as u_openqty from u_hisrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid' union all select sum(u_quantity-u_chrgqty) as u_openqty from u_hisrequestitempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid') as x");
						if ($objRs2->queryfetchrow()) {
							if ($objRs2->fields[0]==0) $obju_HISRequests->docstatus="C";
							else $obju_HISRequests->docstatus="O";
						}					
						$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
					}
	
					//if ($actionReturn) $actionReturn = raiseError("rey");
				} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."]");	
			}	
			if ($actionReturn && ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP")) {
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
				}	
				if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						if ($obju_HISIPs->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_trxtype")=="BILLING") {
							// allow billing to charge
						} elseif ($obju_HISIPs->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_batchcode")=="" && $obju_HISIPs->getudfvalue("u_trxtype")!="BILLING" && $objTable->getudfvalue("u_prepaid")!="1") {
							return raiseError("Patient already tag as May Go Home. You can only add charge/render with term cash.");
						}
					}
					if ($obju_HISIPs->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_prepaid")!="1" && $objTable->getudfvalue("u_trxtype")!="BILLING" && $objTable->getudfvalue("u_batchcode")=="") {
						return raiseError("Patient bill [".$obju_HISIPs->getudfvalue("u_billno")."] already generated. You can only add charge/render with term cash.");
					}
					$obju_HISIPMedSups->prepareadd();
					$obju_HISIPMedSups->docid = $obju_HISIPs->docid;
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
					} else {
						$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisoptrxs",$objConnection);
					}	
					$obju_HISIPMedSups->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
					$obju_HISIPMedSups->setudfvalue("u_docdate",$objTable->getudfvalue("u_startdate"));
					$obju_HISIPMedSups->setudfvalue("u_doctime",$objTable->getudfvalue("u_starttime"));
					$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
					if ($objTable->getudfvalue("u_payrefno")!="") {
						$obju_HISIPMedSups->setudfvalue("u_reftype","CASH");
						$obju_HISIPMedSups->setudfvalue("u_refno",$objTable->getudfvalue("u_payrefno"));
						 $obju_HISIPMedSups->setudfvalue("u_docdesc","Render");
					} elseif ($objTable->getudfvalue("u_requestno")!="") {
						$obju_HISIPMedSups->setudfvalue("u_reftype","REQ");
						$obju_HISIPMedSups->setudfvalue("u_refno",$objTable->getudfvalue("u_requestno"));
						$obju_HISIPMedSups->setudfvalue("u_docdesc","Charge");
					} else $obju_HISIPMedSups->setudfvalue("u_docdesc","Charge");
					$obju_HISIPMedSups->setudfvalue("u_docno",$objTable->docno);
					$obju_HISIPMedSups->setudfvalue("u_doctype","CHRG");
					
					$obju_HISIPMedSups->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
					$obju_HISIPMedSups->setudfvalue("u_balance",$objTable->getudfvalue("u_amount"));
					$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
					$actionReturn = $obju_HISIPMedSups->add();
					if ($actionReturn) {
						$obju_HISIPs->setudfvalue("u_chrgcnt",$obju_HISIPs->getudfvalue("u_chrgcnt")+1);
						/*if ($objTable->getudfvalue("u_payrefno")=="") {
							$obju_HISIPs->setudfvalue("u_totalcharges",$obju_HISIPs->getudfvalue("u_totalcharges")+$objTable->getudfvalue("u_amount"));
							$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit")-$obju_HISIPs->getudfvalue("u_totalcharges"));
							$obju_HISIPs->setudfvalue("u_availablecreditperc",100-(($obju_HISIPs->getudfvalue("u_totalcharges")-$obju_HISIPs->getudfvalue("u_totalcharges"))*100));
						}*/	
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					}
				} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				
			}	
			if ($actionReturn && $objTable->docno!="-1" && $objTable->docstatus!="D") {
				$actionReturn = onCustomEventdocumentschema_brPostChargesGPSHIS($objTable);
			}	
			
			break;
		case "u_hiscredits":
			//if (!isPostingDateValid($objTable->getudfvalue("u_startdate"),$objTable->getudfvalue("u_startdate"),$objTable->getudfvalue("u_startdate"))) return false;		
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_creditby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}		
			$objRs = new recordset(null,$objConnection); 
			$actionReturn = $objRs->executesql("delete from u_hiscredititems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) $actionReturn = $objRs->executesql("delete from u_hiscredititempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select sum(if(u_isfoc=1,0,if(u_isstat=1,u_statunitprice,u_unitprice))*u_quantity) as u_amountbefdisc, sum(if(u_isfoc,0,u_vatamount)) as u_vatamount, sum(if(u_isfoc,0,u_discamount)*u_quantity) as u_discamount, sum(if(u_isfoc,0,u_linetotal)) as u_amount from u_hiscredititems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
				if ($objRs->queryfetchrow("NAME")) {
					$objTable->setudfvalue("u_amountbefdisc",$objRs->fields["u_amountbefdisc"]);
					$objTable->setudfvalue("u_discamount",$objRs->fields["u_amountbefdisc"] - $objRs->fields["u_amount"]);
					$objTable->setudfvalue("u_vatamount",$objRs->fields["u_vatamount"]);
					$objTable->setudfvalue("u_amount",$objRs->fields["u_amount"]);
				}
			}						
			
			if ($actionReturn && $objTable->getudfvalue("u_requestno")!="") {
				if ($objTable->getudfvalue("u_requesttype")=="REQ") {
					$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
					$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hisrequestitems");
					$obju_HISRequestItemPacks = new documentlinesschema_br(null,$objConnection,"u_hisrequestitempacks");
				} else {
					$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hischarges");
					$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hischargeitems");
					$obju_HISRequestItemPacks = new documentlinesschema_br(null,$objConnection,"u_hischargeitempacks");
				}
				if ($obju_HISRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
					if ($actionReturn) {
						$objRs->queryopen("select a.u_itemcode, a.u_itemdesc, a.u_quantity from u_hiscredititems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							if ($obju_HISRequestItems->getbysql("DOCID='$obju_HISRequests->docid' AND U_ITEMCODE='".$objRs->fields["u_itemcode"]."'")) {
								$obju_HISRequestItems->setudfvalue("u_rtqty",$obju_HISRequestItems->getudfvalue("u_rtqty")+$objRs->fields["u_quantity"]);
								if ($objTable->getudfvalue("u_requesttype")=="REQ") {
									if (($obju_HISRequestItems->getudfvalue("u_chrgqty")+$obju_HISRequestItems->getudfvalue("u_rtqty"))>$obju_HISRequestItems->getudfvalue("u_quantity")) {
										return raiseError("Charge + Credit Qty [".($obju_HISRequestItems->getudfvalue("u_chrgqty")+$obju_HISRequestItems->getudfvalue("u_rtqty"))."] exceeded Requested Qty [".$obju_HISRequestItems->getudfvalue("u_quantity")."] for Request No.[".$objTable->getudfvalue("u_requestno")."] for [".$objRs->fields["u_itemdesc"]."]");	
									}
								} else {
									if ($obju_HISRequestItems->getudfvalue("u_rtqty")>$obju_HISRequestItems->getudfvalue("u_quantity")) {
										return raiseError("Return Qty [".($obju_HISRequestItems->getudfvalue("u_chrgqty")+$obju_HISRequestItems->getudfvalue("u_rtqty"))."] exceeded Charged Qty [".$obju_HISRequestItems->getudfvalue("u_quantity")."] for Charge No.[".$objTable->getudfvalue("u_requestno")."] for [".$objRs->fields["u_itemdesc"]."]");	
									}
								}	
								$actionReturn = $obju_HISRequestItems->update($obju_HISRequestItems->docid,$obju_HISRequestItems->lineid,$obju_HISRequestItems->rcdversion);
							} else return raiseError("Unable to retrieve Charge/Request No. No.[".$objTable->getudfvalue("u_requestno")."] for Item [".$objRs->fields["u_itemdesc"]."]");	
							if (!$actionReturn) break;
						}
					}
					if ($actionReturn) {
						$objRs->queryopen("select a.u_packagecode, a.u_itemcode, a.u_itemdesc, a.u_quantity from u_hiscredititempacks a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							if ($obju_HISRequestItemPacks->getbysql("DOCID='$obju_HISRequests->docid' and U_PACKAGECODE='".$objRs->fields["u_packagecode"]."' and U_ITEMCODE='".$objRs->fields["u_itemcode"]."'")) {
								$obju_HISRequestItemPacks->setudfvalue("u_rtqty",$obju_HISRequestItemPacks->getudfvalue("u_rtqty")+$objRs->fields["u_quantity"]);
								if ($objTable->getudfvalue("u_requesttype")=="REQ") {
									if (($obju_HISRequestItemPacks->getudfvalue("u_chrgqty")+$obju_HISRequestItemPacks->getudfvalue("u_rtqty"))>$obju_HISRequestItemPacks->getudfvalue("u_quantity")) {
										return raiseError("Charge + Credit Qty [".($obju_HISRequestItems->getudfvalue("u_chrgqty")+$obju_HISRequestItems->getudfvalue("u_rtqty"))."] exceeded Requested Qty [".$obju_HISRequestItemPacks->getudfvalue("u_quantity")."] for Request No.[".$objTable->getudfvalue("u_requestno")."] for Package Items [".$objRs->fields["u_itemdesc"]."]");	
									}
								} else {
									if ($obju_HISRequestItemPacks->getudfvalue("u_rtqty")>$obju_HISRequestItemPacks->getudfvalue("u_quantity")) {
										return raiseError("Credit Qty [".($obju_HISRequestItems->getudfvalue("u_chrgqty")+$obju_HISRequestItems->getudfvalue("u_rtqty"))."] exceeded Charged Qty [".$obju_HISRequestItemPacks->getudfvalue("u_quantity")."] for Charge No.[".$objTable->getudfvalue("u_requestno")."] for Package Items [".$objRs->fields["u_itemdesc"]."]");	
									}
								}	
								$actionReturn = $obju_HISRequestItemPacks->update($obju_HISRequestItemPacks->docid,$obju_HISRequestItemPacks->lineid,$obju_HISRequestItemPacks->rcdversion);
							} else return raiseError("Unable to retrieve Charge/Request No. No.[".$objTable->getudfvalue("u_requestno")."] for Package Item [".$objRs->fields["u_itemdesc"]."]");	
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn) {
						$objRs2 = new recordset(null,$objConnection);
						if ($objTable->getudfvalue("u_requesttype")=="REQ") {
							$objRs2->queryopen("select sum(u_openqty) from (select sum(u_quantity-(u_chrgqty+u_rtqty)) as u_openqty from u_hisrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid' union all select sum(u_quantity-(u_chrgqty+u_rtqty)) as u_openqty from u_hisrequestitempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid') as x");
						} else {
							$objRs2->queryopen("select sum(u_openqty) from (select sum(u_quantity-u_chrgqty) as u_openqty from u_hischargeitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid' union all select sum(u_quantity-u_chrgqty) as u_openqty from u_hischargeitempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid') as x");
						}	
						if ($objRs2->queryfetchrow()) {
							if ($objRs2->fields[0]==0) $obju_HISRequests->docstatus="C";
							else $obju_HISRequests->docstatus="O";
						}					
						$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
					}
	
					//if ($actionReturn) $actionReturn = raiseError("rey");
				} else return raiseError("Unable to retrieve Charge/Request No. No.[".$objTable->getudfvalue("u_requestno")."]");	

			}	
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
						$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
					} else {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
						$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
					}	
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							if ($objTable->getudfvalue("u_trxtype")!="BILLING" && $objTable->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_expired")=="0") {
								return raiseError("Patient already tag as May Go Home. Only Billing can add return/credit.");
							}
						}
						if ($obju_HISIPs->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_billno")!="") {
							return raiseError("Patient bill already generated. You can only add return/credit.");
						}
					
						$obju_HISIPMedSups->prepareadd();
						$obju_HISIPMedSups->docid = $obju_HISIPs->docid;
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
						} else {
							$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisoptrxs",$objConnection);
						}	
						$obju_HISIPMedSups->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
						$obju_HISIPMedSups->setudfvalue("u_docdate",$objTable->getudfvalue("u_startdate"));
						$obju_HISIPMedSups->setudfvalue("u_doctime",$objTable->getudfvalue("u_starttime"));
						$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
						$obju_HISIPMedSups->setudfvalue("u_reftype",$objTable->getudfvalue("u_requesttype"));
						$obju_HISIPMedSups->setudfvalue("u_refno",$objTable->getudfvalue("u_requestno"));
						if ($objTable->getudfvalue("u_requesttype")=="REQ")	$obju_HISIPMedSups->setudfvalue("u_docdesc","Credit");
						else $obju_HISIPMedSups->setudfvalue("u_docdesc","Return");
						$obju_HISIPMedSups->setudfvalue("u_docno",$objTable->docno);
						$obju_HISIPMedSups->setudfvalue("u_doctype","CM");
						
						$obju_HISIPMedSups->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
						$obju_HISIPMedSups->setudfvalue("u_balance",$objTable->getudfvalue("u_amount"));
						$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPMedSups->add();
						if ($actionReturn) {
							//if ($objTable->getudfvalue("u_payrefno")=="") {
							/*	$obju_HISIPs->setudfvalue("u_cmcnt",$obju_HISIPs->getudfvalue("u_cmcnt")+1);
								$obju_HISIPs->setudfvalue("u_totalcharges",$obju_HISIPs->getudfvalue("u_totalcharges")-$objTable->getudfvalue("u_amount"));
								$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit")-$obju_HISIPs->getudfvalue("u_totalcharges"));
								$obju_HISIPs->setudfvalue("u_availablecreditperc",100-(($obju_HISIPs->getudfvalue("u_totalcharges")-$obju_HISIPs->getudfvalue("u_totalcharges"))*100));*/
							//}	
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						}
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}
			}	
			if ($actionReturn) {
				$actionReturn = onCustomEventdocumentschema_brPostCreditsGPSHIS($objTable);
				//if ($actionReturn && $objTable->getudfvalue("u_stocklink")=="1" || $objTable->getudfvalue("u_prepaid")=="1") $actionReturn = onCustomEventdocumentschema_brPostMedSupReturnGPSHIS($objTable);
				//if ($actionReturn) $objTable->docstatus="C";
			}
			
			break;			
		case "u_hispriceadjs":
			//if (!isPostingDateValid($objTable->getudfvalue("u_startdate"),$objTable->getudfvalue("u_startdate"),$objTable->getudfvalue("u_startdate"))) return false;		
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_adjby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}		
			$objRs = new recordset(null,$objConnection); 
			$actionReturn = $objRs->executesql("delete from u_hispriceadjitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select sum(u_linetotal) as u_amount from u_hispriceadjitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
				if ($objRs->queryfetchrow("NAME")) {
					$objTable->setudfvalue("u_amount",$objRs->fields["u_amount"]);
				}
			}						
			
			if ($actionReturn && $objTable->getudfvalue("u_chargeno")!="") {
				$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
				$obju_HISChargeItems = new documentlinesschema_br(null,$objConnection,"u_hischargeitems");
				if ($obju_HISCharges->getbykey($objTable->getudfvalue("u_chargeno"))) {
					if ($obju_HISCharges->getudfvalue("u_adjno")=="") {
						$objRs->queryopen("select a.u_chargelineid, a.u_itemdesc, a.u_adjprice, a.u_linetotal from u_hispriceadjitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							if ($obju_HISChargeItems->getbysql("LINEID='".$objRs->fields["u_chargelineid"]."'")) {
								$obju_HISChargeItems->setudfvalue("u_adjprice",$objRs->fields["u_adjprice"]);
								$obju_HISChargeItems->setudfvalue("u_adjamt",$objRs->fields["u_linetotal"]);
								$actionReturn = $obju_HISChargeItems->update($obju_HISChargeItems->docid,$obju_HISChargeItems->lineid,$obju_HISChargeItems->rcdversion);
							} else return raiseError("Unable to retrieve Charge No.[".$objTable->getudfvalue("u_chargeno")."] for Item [".$objRs->fields["u_itemdesc"]."]");	
							if (!$actionReturn) break;
						}
					} else return raiseError("Charge No.[".$objTable->getudfvalue("u_chargeno")."] already have adjustment [".$obju_HISCharges->getudfvalue("u_adjno")."]");
					if ($actionReturn) {
						$obju_HISCharges->setudfvalue("u_adjno",$objTable->docno);
						$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
					}
				} else return raiseError("Unable to retrieve Charge/Request No. No.[".$objTable->getudfvalue("u_requestno")."]");	

			}	
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
						$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
					} else {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
						$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
					}	
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							if ($objTable->getudfvalue("u_trxtype")!="BILLING" && $objTable->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_expired")=="0") {
								return raiseError("Patient already tag as May Go Home. Only Billing can add adjustment.");
							}
						}
						if ($obju_HISIPs->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_billno")!="") {
							return raiseError("Patient bill already generated. You can only add adjustment.");
						}
					
						$obju_HISIPMedSups->prepareadd();
						$obju_HISIPMedSups->docid = $obju_HISIPs->docid;
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
						} else {
							$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisoptrxs",$objConnection);
						}	
						$obju_HISIPMedSups->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
						$obju_HISIPMedSups->setudfvalue("u_docdate",$objTable->getudfvalue("u_startdate"));
						$obju_HISIPMedSups->setudfvalue("u_doctime",$objTable->getudfvalue("u_starttime"));
						$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
						$obju_HISIPMedSups->setudfvalue("u_reftype",$objTable->getudfvalue("u_requesttype"));
						$obju_HISIPMedSups->setudfvalue("u_refno",$objTable->getudfvalue("u_requestno"));
						if ($objTable->getudfvalue("u_amount")>=0) $obju_HISIPMedSups->setudfvalue("u_docdesc","Debit Adjustment");
						else $obju_HISIPMedSups->setudfvalue("u_docdesc","Credit Adjustment");
						$obju_HISIPMedSups->setudfvalue("u_docno",$objTable->docno);
						$obju_HISIPMedSups->setudfvalue("u_doctype","ADJ");
						
						$obju_HISIPMedSups->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
						$obju_HISIPMedSups->setudfvalue("u_balance",$objTable->getudfvalue("u_amount"));
						$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPMedSups->add();
						if ($actionReturn) {
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						}
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}
			}	
			if ($actionReturn) {
				$actionReturn = onCustomEventdocumentschema_brPostAdjustmentsGPSHIS($objTable);
			}
			
			break;						
		case "u_hisroomrs":
			$obju_HISRooms = new masterdataschema_br(null,$objConnection,"u_hisrooms");
			$obju_HISRoomBeds = new masterdatalinesschema_br(null,$objConnection,"u_hisroombeds");
			if ($obju_HISRooms->getbykey($objTable->getudfvalue("u_roomno"))) {
				if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$objTable->getudfvalue("u_bedno")."'")) {
					if ($obju_HISRoomBeds->getudfvalue("u_status")=="Vacant") {
						$obju_HISRoomBeds->setudfvalue("u_status","Reserved");
						$obju_HISRoomBeds->setudfvalue("u_refno",$objTable->docno);
						$obju_HISRoomBeds->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
						$obju_HISRoomBeds->setudfvalue("u_date",$objTable->getudfvalue("u_startdate"));
						$obju_HISRoomBeds->setudfvalue("u_time",$objTable->getudfvalue("u_starttime"));
						$actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
						if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
					} else return raiseError("Bed No.[".$objTable->getudfvalue("u_bedno")."] is not vacant.");
				} else return raiseError("Unable to find Bed No.[".$objTable->getudfvalue("u_bedno")."].");
			} else return raiseError("Unable to find Room No.[".$objTable->getudfvalue("u_roomno")."].");
			break;			
		case "u_hisroomtfs":
			$objRs = new recordset(null,$objConnection);
			$obju_HISRooms = new masterdataschema_br(null,$objConnection,"u_hisrooms");
			$obju_HISRoomBeds = new masterdatalinesschema_br(null,$objConnection,"u_hisroombeds");
			$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
			$obju_HISIPRooms = new documentlinesschema_br(null,$objConnection,"u_hisiprooms");
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				$obju_HISIPs->setudfvalue("u_roomno",$objTable->getudfvalue("u_roomno"));
				$obju_HISIPs->setudfvalue("u_bedno",$objTable->getudfvalue("u_bedno"));
				$obju_HISIPs->setudfvalue("u_roomdesc",$objTable->getudfvalue("u_roomdesc"));
				$obju_HISIPs->setudfvalue("u_roomtype",$objTable->getudfvalue("u_roomtype"));
				$obju_HISIPs->setudfvalue("u_roomrate",$objTable->getudfvalue("u_rate"));
				$obju_HISIPs->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
				$obju_HISIPs->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
				$obju_HISIPs->setudfvalue("u_roomdate",$objTable->getudfvalue("u_startdate"));
				$obju_HISIPs->setudfvalue("u_roomtime",$objTable->getudfvalue("u_starttime"));
				$obju_HISIPRooms->prepareadd();
				$obju_HISIPRooms->docid = $obju_HISIPs->docid;
				$obju_HISIPRooms->lineid = getNextIdByBranch("u_hisiprooms",$objConnection);;
				$obju_HISIPRooms->setudfvalue("u_roomno",$objTable->getudfvalue("u_roomno"));
				$obju_HISIPRooms->setudfvalue("u_roomdesc",$objTable->getudfvalue("u_roomdesc"));
				$obju_HISIPRooms->setudfvalue("u_roomtype",$objTable->getudfvalue("u_roomtype"));
				$obju_HISIPRooms->setudfvalue("u_isroomshared",$objTable->getudfvalue("u_isroomshared"));
				$obju_HISIPRooms->setudfvalue("u_bedno",$objTable->getudfvalue("u_bedno"));
				$obju_HISIPRooms->setudfvalue("u_rate",$objTable->getudfvalue("u_rate"));
				$obju_HISIPRooms->setudfvalue("u_quantity",1);
				$obju_HISIPRooms->setudfvalue("u_amount",$objTable->getudfvalue("u_rate"));
				$obju_HISIPRooms->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
				$obju_HISIPRooms->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
				$obju_HISIPRooms->setudfvalue("u_rateuom",$objTable->getudfvalue("u_rateuom"));
				$obju_HISIPRooms->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
				$obju_HISIPRooms->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
				$obju_HISIPRooms->privatedata["header"] = $obju_HISIPs;
				$actionReturn = $obju_HISIPRooms->add();
				if ($actionReturn) {
					if ($obju_HISIPRooms->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_BEDNO='".$objTable->getudfvalue("u_bednofr")."' AND U_ENDDATE IS NULL")) {
						if ($obju_HISRooms->getbykey($objTable->getudfvalue("u_roomnofr"))) {
							if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$objTable->getudfvalue("u_bednofr")."'")) {
								//if ($obju_HISRoomBeds->getudfvalue("u_status")=="Occupied") {
									
									$obju_HISIPRooms->setudfvalue("u_enddate",$objTable->getudfvalue("u_startdate"));
									$obju_HISIPRooms->setudfvalue("u_endtime",$objTable->getudfvalue("u_starttime"));
									if ($objTable->getudfvalue("u_rateuom")=="Hour") {
										$objRs->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$obju_HISIPRooms->getudfvalue("u_enddate")." ".$obju_HISIPRooms->getudfvalue("u_endtime")."','".$obju_HISIPRooms->getudfvalue("u_startdate")." ".$obju_HISIPRooms->getudfvalue("u_starttime")."'))/60)/60)))");
										if ($objRs->queryfetchrow()) {
											$obju_HISIPRooms->setudfvalue("u_quantity",$objRs->fields[0]);
											$obju_HISIPRooms->setudfvalue("u_amount",$obju_HISIPRooms->getudfvalue("u_quantity")*$obju_HISIPRooms->getudfvalue("u_rate"));
										} else return raiseError("Unable to compute room fee.");
									} else {
										//$objRs->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$obju_HISIPRooms->getudfvalue("u_enddate")." ".$obju_HISIPRooms->getudfvalue("u_endtime")."','".$obju_HISIPRooms->getudfvalue("u_startdate")." ".$obju_HISIPRooms->getudfvalue("u_starttime")."'))/60)/60)/24))");
										//if ($objRs->queryfetchrow()) {
											$days = datedifference("d", $obju_HISIPRooms->getudfvalue("u_startdate"),$obju_HISIPRooms->getudfvalue("u_enddate")) ;
											$obju_HISIPRooms->setudfvalue("u_quantity",$days);
											$obju_HISIPRooms->setudfvalue("u_amount",$obju_HISIPRooms->getudfvalue("u_quantity")*$obju_HISIPRooms->getudfvalue("u_rate"));
										//} else return raiseError("Unable to compute room fee.");
									}	
									
									$obju_HISIPRooms->privatedata["header"] = $obju_HISIPs;
									if ($actionReturn) $actionReturn = $obju_HISIPRooms->update($obju_HISIPRooms->docid,$obju_HISIPRooms->lineid,$obju_HISIPRooms->rcdversion);
									
									$obju_HISRoomBeds->setudfvalue("u_status","For Cleaning");
									$obju_HISRoomBeds->setudfvalue("u_refno","");
									$obju_HISRoomBeds->setudfvalue("u_patientname","");
									$obju_HISRoomBeds->setudfvalue("u_date",$objTable->getudfvalue("u_startdate"));
									$obju_HISRoomBeds->setudfvalue("u_time",$objTable->getudfvalue("u_starttime"));
									$obju_HISRoomBeds->privatedata["header"] = $obju_HISRooms;
									if ($actionReturn) $actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
									if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
									
								//} else return raiseError("Bed No.[".$objTable->getudfvalue("u_bedno")."] is not occupied.");
							} else return raiseError("Unable to find Bed No.[".$objTable->getudfvalue("u_bednofr")."].");
						} else return raiseError("Unable to find Room No.[".$objTable->getudfvalue("u_roomnofr")."].");
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."] Bed No. [".$objTable->getudfvalue("u_bednofr")."]");	
				}
				if ($actionReturn) $actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
				if ($actionReturn) {
					$objUserMsgs =  new usermsgs(null,$objConnection);
					//var_dump((floatval($objTable->getudfvalue("u_total_cashvariance"))*-1));
					$objRs->queryopen("select a.USERID from userbranches a,users b where b.userid=a.userid and a.branchcode='".$_SESSION["branch"]."' and b.groupid in ('FIN-CSR')");
					while ($objRs->queryfetchrow("NAME")) {
						$msgid = date('Y-m-d H:i:s') . ".RoomTransfer." . $objRs->fields["USERID"] . " " .$_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $_SERVER["REMOTE_ADDR"];
						//var_dump($objRs->fields["USERID"]);
						$objUserMsgs->prepareadd();
						$objUserMsgs->msgid = $msgid;
						$objUserMsgs->userid = $objRs->fields["USERID"];
						$objUserMsgs->msgfrom = "system";
						$objUserMsgs->msgtype = "IBX";
						$objUserMsgs->msgsubtype = "RA";  
						$objUserMsgs->priority=1;
						$objUserMsgs->msgsubject = "Room Transfer : From (".$objTable->getudfvalue("u_bednofr").") TO (".$objTable->getudfvalue("u_bedno").").";
						$objUserMsgs->msgbody = "Patient Name: " . $objTable->getudfvalue("u_patientname");
						$objUserMsgs->status = "U";
						$objUserMsgs->msgdate = date('Y-m-d');
						$objUserMsgs->msgtime = date('H:i:s');
						$actionReturn = $objUserMsgs->add();
						if (!$actionReturn)	break;
					}				
				}
			} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			//if ($actionReturn) $actionReturn = raiseError(""); 
			break;
		case "u_hisoptfs":
			$objRs = new recordset(null,$objConnection);
			$obju_HISOPs = new documentschema_br(null,$objConnection,"u_hisops");
			if ($obju_HISOPs->getbykey($objTable->getudfvalue("u_refno"))) {
				$obju_HISOPs->docstatus = "Admitted";
				$obju_HISOPs->setudfvalue("u_tfroomno",$objTable->getudfvalue("u_roomno"));
				$obju_HISOPs->setudfvalue("u_tfroomdesc",$objTable->getudfvalue("u_roomdesc"));
				$obju_HISOPs->setudfvalue("u_tfroomtype",$objTable->getudfvalue("u_roomtype"));
				$obju_HISOPs->setudfvalue("u_tfbedno",$objTable->getudfvalue("u_bedno"));
				$obju_HISOPs->setudfvalue("u_tfrate",$objTable->getudfvalue("u_rate"));
				$obju_HISOPs->setudfvalue("u_tfrateuom",$objTable->getudfvalue("u_rateuom"));
				$obju_HISOPs->setudfvalue("u_tfpricelist",$objTable->getudfvalue("u_pricelist"));
				$obju_HISOPs->setudfvalue("u_tfdepartment",$objTable->getudfvalue("u_department"));
				$obju_HISOPs->setudfvalue("u_tfstartdate",$objTable->getudfvalue("u_startdate"));
				$obju_HISOPs->setudfvalue("u_tfstarttime",$objTable->getudfvalue("u_starttime"));
				if ($actionReturn) $actionReturn = $obju_HISOPs->update($obju_HISOPs->docno,$obju_HISOPs->rcdversion);
			} else {
				$actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			}	
			break;			
		case "u_hisbills":
			//if (!isPostingDateValid($objTable->getudfvalue("u_docdate"),$objTable->getudfvalue("u_docdate"),$objTable->getudfvalue("u_docdate"))) return false;		
			if ($actionReturn && $objTable->docstatus=="O") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_billby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostBillGPSHIS($objTable);
				if ($actionReturn && $objTable->docstatus !="CN" && $objTable->docstatus !="D") {
					if (bcsub($objTable->getudfvalue("u_dueamount"),$objTable->getudfvalue("u_paidamount"),14)==0) $objTable->docstatus ="C";
					else $objTable->docstatus ="O";
				}	
			}
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select group_concat(b.name separator ', ') from u_hisbillins a inner join u_hishealthins b on b.code=a.u_inscode where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
				if ($objRs->queryfetchrow()) $objTable->setudfvalue("u_healthins",$objRs->fields[0]);
				else $objTable->setudfvalue("u_healthins","");
			}
									
			break;	
		case "u_hisinclaims":
			if ($objTable->docstatus=="O") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_postedby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brUpdateBillBenefitGPSHIS($objTable);
			} else {
				
				$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
				$obju_HISBillIns = new documentlinesschema_br(null,$objConnection,"u_hisbillins");
				if ($obju_HISBills->getbykey($objTable->getudfvalue("u_billno"))) {
					if ($obju_HISBillIns->getbysql("DOCID='".$obju_HISBills->docid."' AND U_INSCODE='".$objTable->getudfvalue("u_inscode")."'")) {
						$obju_HISBillIns->setudfvalue("u_status","D");
						$obju_HISBillIns->setudfvalue("u_claimno",$objTable->docno);
						$obju_HISBillIns->setudfvalue("u_amount",$objTable->getudfvalue("u_thisamount"));
						$obju_HISBillIns->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillIns->update($obju_HISBillIns->docid,$obju_HISBillIns->lineid,$obju_HISBillIns->rcdversion);
					} //else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Health Insurance Claim No [".$objTable->docno."]");
					if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."]");	
				
			}
			break;	
			
		case "u_hispronotes":
			//if (!isPostingDateValid($objTable->getudfvalue("u_docdate"),$objTable->getudfvalue("u_docdate"),$objTable->getudfvalue("u_docdate"))) return false;		
			//if ($objTable->docstatus=="O") {
			//} else {
				$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
				$obju_HISBillPNs = new documentlinesschema_br(null,$objConnection,"u_hisbillpns");
				if ($objTable->getudfvalue("u_billno")!="") {
					if ($obju_HISBills->getbykey($objTable->getudfvalue("u_billno"))) {
						$obju_HISBillPNs->prepareadd();
						$obju_HISBillPNs->docid = $obju_HISBills->docid;
						$obju_HISBillPNs->lineid = getNextId("u_hisbillpns",$objConnection);
						$obju_HISBillPNs->setudfvalue("u_status","O");
						$obju_HISBillPNs->setudfvalue("u_guarantorcode",$objTable->getudfvalue("u_guarantorcode"));
						$obju_HISBillPNs->setudfvalue("u_pnno",$objTable->docno);
						$obju_HISBillPNs->setudfvalue("u_claimno",$objTable->getudfvalue("u_claimno"));
						$obju_HISBillPNs->setudfvalue("u_type",$objTable->getudfvalue("u_type"));
						$obju_HISBillPNs->setudfvalue("u_feetype",$objTable->getudfvalue("u_feetype"));
						$obju_HISBillPNs->setudfvalue("u_doctorid",$objTable->getudfvalue("u_doctorid"));
						$obju_HISBillPNs->setudfvalue("u_caserate",$objTable->getudfvalue("u_caserate"));
						$obju_HISBillPNs->setudfvalue("u_amount",$objTable->getudfvalue("u_pnamount"));
						$obju_HISBillPNs->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillPNs->add();
						//if ($obju_HISBillIns->getbysql("DOCID='".$obju_HISBills->docid."' AND U_GUARANTORCODE='".$objTable->getudfvalue("u_guarantorcode")."'")) {
						//} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Health Insurance Claim No [".$objTable->docno."]");
						if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
					} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."]");	
				}	
			//}
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateBillPronoteGPSHIS($objTable);
			break;	
		case "u_hismergepatients":	
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brMergePatientRecordsGPSHIS($objTable);
			break;
		case "u_hischargeprepaidrequests":
			if ($objTable->docstatus=="O") {
				if ($actionReturn) {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
						if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
							$obju_HISIPs->setudfvalue("u_forcebilling",1);
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
					} else {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
						if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
							$obju_HISIPs->setudfvalue("u_forcebilling",1);
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
					}	
				}				
				if ($actionReturn) {
					$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
					$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hisrequestitems");
					if ($obju_HISRequests->getbykey($objTable->getudfvalue("u_payrefno"))) {
						if ($obju_HISRequests->docstatus=="O") {
							if ($obju_HISRequests->getudfvalue("u_billchargeno")=="") {
								if ($obju_HISRequests->getudfvalue("u_prepaid")!="0" && $obju_HISRequests->getudfvalue("u_payrefno")=="") {
									if (floatval($obju_HISRequests->getudfvalue("u_amount")) == floatval($objTable->getudfvalue("u_dueamount"))) {
										$obju_HISRequestItems->queryopen($obju_HISRequestItems->selectstring()." AND DOCID='$obju_HISRequests->docid'");
										while ($obju_HISRequestItems->queryfetchrow()) {
											$obju_HISRequestItems->setudfvalue("u_vatcode","VATOX");
											$obju_HISRequestItems->setudfvalue("u_vatrate",0);
											$obju_HISRequestItems->setudfvalue("u_vatamount",0);
											$actionReturn = $obju_HISRequestItems->update($obju_HISRequestItems->docid,$obju_HISRequestItems->lineid,$obju_HISRequestItems->rcdversion);
											if (!$actionReturn) break;
											
										}
										if ($actionReturn) {
											$obju_HISRequests->setudfvalue("u_prepaid",0);
											$obju_HISRequests->setudfvalue("u_billchargeno",$objTable->docno);
											$obju_HISRequests->setudfvalue("u_billchargeby",$_SESSION["userid"]);
											$obju_HISRequests->setudfvalue("u_billchargeremarks",$objTable->getudfvalue("u_remarks"));
											$obju_HISRequests->setudfvalue("u_vatamount",0);
											$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
										}	
									} else $actionReturn = raiseError("Request amount [".$obju_HISRequests->getudfvalue("u_amount")."] was modified, charge amount [".$objTable->getudfvalue("u_dueamount")."].");	
								} else $actionReturn = raiseError("Request must be prepaid and unpaid.");	
							} else $actionReturn = raiseError("Request already charge [".$obju_HISRequests->getudfvalue("u_billchargeno")."].");
						} else $actionReturn = raiseError("Request Status must be open.");
					} else $actionReturn = raiseError("Unable to find Request No.[".$objTable->getudfvalue("u_requestno")."]");
				}	
				if ($actionReturn) $objTable->docstatus="C";
			}
			break;
		case "u_hispos":
			$objTable->setudfvalue("u_cashierid",$_SESSION["userid"]);
			$objRs = new recordset(null,$objConnection); 
			$actionReturn = $objRs->executesql("delete from u_hisposcredits where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn && $objTable->docstatus=="O") {
				if ($objTable->getudfvalue("u_payrefno")!="") {
					switch ($objTable->getudfvalue("u_payreftype")) {
						case "RS":
							$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
							if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_payrefno"))) {
								if ($obju_HISIPs->docstatus!="CN" && $obju_HISLabTestRequests->docstatus!="Cancelled") {
									if ($obju_HISIPs->getudfvalue("u_dpamount")>0 && $obju_HISIPs->getudfvalue("u_dpno")=="") {
										//if (floatval($obju_HISIPs->getudfvalue("u_dpamount")) == floatval($objTable->getudfvalue("u_totalamount"))) {
											$obju_HISIPs->setudfvalue("u_dpno",$objTable->docno);
											$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
										//} else $actionReturn = raiseError("Admission amount [".$obju_HISIPs->getudfvalue("u_dpamount")."] was modified, paid amount [".$objTable->getudfvalue("u_dueamount")."].");	
									} //else $actionReturn = raiseError("Admission must be unpaid.");
								} else $actionReturn = raiseError("Admission Status already cancelled.");
							} else $actionReturn = raiseError("Unable to find Admission No.[".$objTable->getudfvalue("u_payrefno")."]");
							break;
						case "SI":
							break;
						case "CHRG":
							//if ($objTable->getudfvalue("u_automanage")=="0") {
								$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
								if ($obju_HISRequests->getbykey($objTable->getudfvalue("u_payrefno"))) {
									if ($obju_HISRequests->docstatus=="O") {
										if ($obju_HISRequests->getudfvalue("u_prepaid")=="1" && $obju_HISRequests->getudfvalue("u_payrefno")=="") {
											if (bcsub($obju_HISRequests->getudfvalue("u_amount"),$objTable->getudfvalue("u_dueamount"),14)==0) {
												$obju_HISRequests->setudfvalue("u_payrefno",$objTable->docno);
												$obju_HISRequests->setudfvalue("u_payreftype",$objTable->getudfvalue("u_trxtype"));
												$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
											} else $actionReturn = raiseError("Request amount [".$obju_HISRequests->getudfvalue("u_amount")."] was modified, paid amount [".$objTable->getudfvalue("u_dueamount")."].");	
										} elseif ($obju_HISRequests->getudfvalue("u_prepaid")=="2" && $obju_HISRequests->getudfvalue("u_payrefno")=="") {
											if (floatval($obju_HISRequests->getudfvalue("u_amountbefdisc")) == floatval($objTable->getudfvalue("u_dueamount"))) {
												$obju_HISRequests->setudfvalue("u_payrefno",$objTable->docno);
												$obju_HISRequests->setudfvalue("u_payreftype",$objTable->getudfvalue("u_trxtype"));
												$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
											} else $actionReturn = raiseError("Request amount [".$obju_HISRequests->getudfvalue("u_amountbefdisc")."] was modified, paid amount [".$objTable->getudfvalue("u_dueamount")."].");	
										} elseif ($objTable->docno!=$obju_HISRequests->getudfvalue("u_payrefno")) {
											$actionReturn = raiseError("Request must be cash term and unpaid.");
										} 
									} else $actionReturn = raiseError("Request Status must be open.");
								} else $actionReturn = raiseError("Unable to find Request No.[".$objTable->getudfvalue("u_requestno")."]");
							//}	
							break;
					}
				}	
				if ($actionReturn) {
					//if ($objTable->getudfvalue("u_trxtype")=="PHARMACY") {
					if ($objTable->getudfvalue("u_prepaid")=="1") {
						$actionReturn = onCustomEventdocumentschema_brPostCashSalesGPSHIS($objTable);
						if ($actionReturn && ($objTable->getudfvalue("u_colltype")=="Professional Fees" || $objTable->getudfvalue("u_colltype")=="Professional Materials")) {
							$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('pos',$objTable);
						}
						if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostCashSalesHealthBenefitsGPSHIS($objTable);
						if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostPOSCreditsGPSHIS($objTable);
					} else if ($objTable->getudfvalue("u_prepaid")=="3") {
						$actionReturn = onCustomEventdocumentschema_brPostPaymentGPSHIS($objTable);
					} else $actionReturn = onCustomEventdocumentschema_brPostDPGPSHIS($objTable);
				}	
				if ($actionReturn) $objTable->docstatus="C";
				//if ($actionReturn) $actionReturn = raiseError("here");
			}
			break;
		case "u_hismedrecs":
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPs->setudfvalue("u_icdcode",$objTable->getudfvalue("u_icdcode"));
						$obju_HISIPs->setudfvalue("u_icddesc",$objTable->getudfvalue("u_icddesc"));
						$obju_HISIPs->setudfvalue("u_casetype",$objTable->getudfvalue("u_casetype"));
						$obju_HISIPs->setudfvalue("u_rvscode",$objTable->getudfvalue("u_rvscode"));
						$obju_HISIPs->setudfvalue("u_rvsdesc",$objTable->getudfvalue("u_rvsdesc"));
						$obju_HISIPs->setudfvalue("u_rvu",$objTable->getudfvalue("u_rvu"));
						//$obju_HISIPs->setudfvalue("u_procedures",$objTable->getudfvalue("u_procedures"));
						$obju_HISIPs->setudfvalue("u_finaldoctorid",$objTable->getudfvalue("u_doctorid"));
						$obju_HISIPs->setudfvalue("u_finaldoctorservice",$objTable->getudfvalue("u_doctorservice"));
						$obju_HISIPs->setudfvalue("u_finalremarks",$objTable->getudfvalue("u_remarks"));
						$obju_HISIPs->setudfvalue("u_finalorproc",$objTable->getudfvalue("u_orproc"));
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPs->setudfvalue("u_icdcode",$objTable->getudfvalue("u_icdcode"));
						$obju_HISIPs->setudfvalue("u_icddesc",$objTable->getudfvalue("u_icddesc"));
						$obju_HISIPs->setudfvalue("u_casetype",$objTable->getudfvalue("u_casetype"));
						$obju_HISIPs->setudfvalue("u_rvscode",$objTable->getudfvalue("u_rvscode"));
						$obju_HISIPs->setudfvalue("u_rvsdesc",$objTable->getudfvalue("u_rvsdesc"));
						$obju_HISIPs->setudfvalue("u_rvu",$objTable->getudfvalue("u_rvu"));
						//$obju_HISIPs->setudfvalue("u_procedures",$objTable->getudfvalue("u_procedures"));
						$obju_HISIPs->setudfvalue("u_finaldoctorid",$objTable->getudfvalue("u_doctorid"));
						$obju_HISIPs->setudfvalue("u_finaldoctorservice",$objTable->getudfvalue("u_doctorservice"));
						$obju_HISIPs->setudfvalue("u_finalremarks",$objTable->getudfvalue("u_remarks"));
						$obju_HISIPs->setudfvalue("u_finalorproc",$objTable->getudfvalue("u_orproc"));
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}	
			}	
			if ($actionReturn) {
				$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
				if ($obju_HISBills->getbykey($objTable->getudfvalue("u_reftype") . "-" .$objTable->getudfvalue("u_refno"))) {
					$obju_HISBills->setudfvalue("u_icdcode",$objTable->getudfvalue("u_icdcode"));
					$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);	
				}
			}
			break;			
		case "u_hisbilltermsupds":
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					$obju_HISIPBillTermsUpds = new documentlinesschema_br(null,$objConnection,"u_hisipbilltermsupds");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					$obju_HISIPBillTermsUpds = new documentlinesschema_br(null,$objConnection,"u_hisopbilltermsupds");
				}	
				if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
					if ($obju_HISIPs->getudfvalue("u_billtermno")=="") {
						$obju_HISIPBillTermsUpds->prepareadd();
						$obju_HISIPBillTermsUpds->docid = $obju_HISIPs->docid;
						$obju_HISIPBillTermsUpds->lineid = getNextIdByBranch("u_hisipbilltermsupds",$objConnection);
						$obju_HISIPBillTermsUpds->setudfvalue("u_date",$objTable->getudfvalue("u_date"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_time",$objTable->getudfvalue("u_time"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_billing",$objTable->getudfvalue("u_billing"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_remarks",$objTable->getudfvalue("u_remarks"));
						//$obju_HISIPBillTermsUpds->setudfvalue("u_billtermno",$objTable->docno);
						$obju_HISIPBillTermsUpds->setudfvalue("u_billtermby",$_SESSION["userid"]);
						$obju_HISIPBillTermsUpds->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPBillTermsUpds->add();
						if ($actionReturn) {
							$obju_HISIPs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
							$obju_HISIPs->setudfvalue("u_billing",$objTable->getudfvalue("u_billing"));
							$obju_HISIPs->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
							$obju_HISIPs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
							$obju_HISIPs->setudfvalue("u_scdisc",$objTable->getudfvalue("u_scdisc"));
							$obju_HISIPs->setudfvalue("u_billremarks",$objTable->getudfvalue("u_remarks"));
							$obju_HISIPs->setudfvalue("u_billtermno",$objTable->docno);
							$obju_HISIPs->setudfvalue("u_billtermby",$_SESSION["userid"]);
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						}
					} else $actionReturn = raiseError("Bill Terms already exists [".$obju_HISIPs->getudfvalue("u_billtermno")."]. Refresh Page and update again.");
				} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			}	
			break;						
		case "u_hismedsuptfs":
			$obju_HISMedSupRequests = new documentschema_br(null,$objConnection,"u_hismedsuprequests");
			if ($objTable->getudfvalue("u_requestno")!="") {
				if ($obju_HISMedSupRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
					$obju_HISMedSupRequests->docstatus = "C";
					if ($actionReturn) $actionReturn = $obju_HISMedSupRequests->update($obju_HISMedSupRequests->docno,$obju_HISMedSupRequests->rcdversion);
				} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."]");	
			}	
			if ($actionReturn && $objTable->getudfvalue("u_intransit")=="0") {
				if ($actionReturn && $objTable->getudfvalue("u_stocklink")=="1") $actionReturn = onCustomEventdocumentschema_brPostStockTransferGPSHIS($objTable);
				if ($actionReturn) $objTable->docstatus = "C";
			}
			break;
		case "u_hismedsuptfins":
			$obju_HISMedSupTfs = new documentschema_br(null,$objConnection,"u_hismedsuptfs");
			if ($obju_HISMedSupTfs->getbykey($objTable->getudfvalue("u_tfno"))) {
				$obju_HISMedSupTfs->docstatus = "C";
				if ($actionReturn) $actionReturn = $obju_HISMedSupTfs->update($obju_HISMedSupTfs->docno,$obju_HISMedSupTfs->rcdversion);
			} else return raiseError("Unable to retrieve Delivery No.[".$objTable->getudfvalue("u_tfno")."]");	
			if ($actionReturn && $objTable->getudfvalue("u_stocklink")=="1") $actionReturn = onCustomEventdocumentschema_brPostStockTransferGPSHIS($objTable);
			if ($actionReturn) $objTable->docstatus = "C";
			break;	
		case "u_hisinclaimxmtals":
			$objRs = new recordset(null,$objConnection);
			$actionReturn = $objRs->executesql("delete from u_hisinclaimxmtalitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) $objTable->docstatus = "C";
			//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSHIS");
			break;
		case "u_hisinclaimxmtalrecons":
			$objRs = new recordset(null,$objConnection);
			$actionReturn = $objRs->executesql("delete from u_hisinclaimxmtalreconitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			if ($actionReturn) $objTable->docstatus = "C";
			//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSHIS");
			break;
		case "u_hisdietscheds":
			$objRs = new recordset(null,$objConnection);
			$actionReturn = $objRs->executesql("delete from u_hisdietscheditems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=0",false);
			//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSHIS");
			break;
		case "u_hismedsupstocks":
			if ($objTable->getudfvalue("u_requestno")!="") {
				$objRs = new recordset(null,$objConnection);
				$obju_HISMedSupStockRequests = new documentschema_br(null,$objConnection,"u_hismedsupstockrequests");
				if ($obju_HISMedSupStockRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
					$actionReturn = $objRs->executesql("update u_hismedsupstockrequestitems set u_linestatus='C' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISMedSupStockRequests->docid' and u_reqtype='GI'",true);
					if ($actionReturn) {
						$objRs->queryopen("select count(*) from u_hismedsupstockrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISMedSupStockRequests->docid' and u_linestatus=''");
						if ($objRs->queryfetchrow()) {
							$obju_HISMedSupStockRequests->docstatus = iif($objRs->fields[0]>0,"O","C");
						}	
					}	
					$actionReturn = $obju_HISMedSupStockRequests->update($obju_HISMedSupStockRequests->docno,$obju_HISMedSupStockRequests->rcdversion);
				} else $actionReturn = raiseError("Unable to find Request No.[".$objTable->getudfvalue("u_requestno")."]");
			}
			if ($actionReturn && $objTable->docstatus=="O") {
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostGoodsIssueGPSHIS($objTable);
				if ($actionReturn) $objTable->docstatus="C";
			}
			break;
		case "u_hismedsupstocktfs":
			if ($objTable->docstatus!="D") {
				if ($objTable->getudfvalue("u_requestno")!="") {
					$objRs = new recordset(null,$objConnection);
					$obju_HISMedSupStockRequests = new documentschema_br(null,$objConnection,"u_hismedsupstockrequests");
					if ($obju_HISMedSupStockRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
						$actionReturn = $objRs->executesql("update u_hismedsupstockrequestitems set u_linestatus='C' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISMedSupStockRequests->docid' and u_reqtype='TF'",true);
						if ($actionReturn) {
							$objRs->queryopen("select count(*) from u_hismedsupstockrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISMedSupStockRequests->docid' and u_linestatus=''");
							if ($objRs->queryfetchrow()) {
								$obju_HISMedSupStockRequests->docstatus = iif($objRs->fields[0]>0,"O","C");
							}	
						}	
						if ($actionReturn) $actionReturn = $obju_HISMedSupStockRequests->update($obju_HISMedSupStockRequests->docno,$obju_HISMedSupStockRequests->rcdversion);
					} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."]");	
				}	
				if ($actionReturn && $objTable->getudfvalue("u_intransit")=="0") {
					if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostStockTransferGPSHIS($objTable);
					if ($actionReturn) $objTable->docstatus = "C";
				}
			}	
			break;
		case "u_hismedsupstocktfins":
			if ($objTable->getudfvalue("u_tfno")!="") {
				$obju_HISMedSupStockTfs = new documentschema_br(null,$objConnection,"u_hismedsupstocktfs");
				if ($obju_HISMedSupStockTfs->getbykey($objTable->getudfvalue("u_tfno"))) {
					$obju_HISMedSupStockTfs->docstatus = "C";
					$obju_HISMedSupStockTfs->setudfvalue("u_tfinno",$objTable->docno);
					if ($actionReturn) $actionReturn = $obju_HISMedSupStockTfs->update($obju_HISMedSupStockTfs->docno,$obju_HISMedSupStockTfs->rcdversion);
				} else return raiseError("Unable to retrieve Transfer No.[".$objTable->getudfvalue("u_tfno")."]");	
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostStockTransferGPSHIS($objTable);
			}	
			if ($actionReturn) $objTable->docstatus = "C";
			break;	
		case "u_hisalerts":
			$doctorname = "";
			
			$objRs = new recordset(null,$objConnection);
			$objUserMsgs =  new usermsgs(null,$objConnection);
			$objRs->queryopen("select name from u_hisdoctors where code='".$objTable->getudfvalue("u_doctorid")."'");
			if ($objRs->queryfetchrow("NAME")) {
				$doctorname = $objRs->fields["name"];;
			}
			$objRs->queryopen("select a.code, a.name, b.u_userid from u_hisalerttypes a, u_hisalerttypeusers b where b.code=a.code and a.code='".$objTable->getudfvalue("u_alerttype")."'");
			while ($objRs->queryfetchrow("NAME")) {
				
				$msgid = date('Y-m-d H:i:s') . ".HISALERT-." . $objRs->fields["code"] ."-". $objRs->fields["u_userid"] . " " .$_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $_SERVER["REMOTE_ADDR"];
				$objUserMsgs->prepareadd();
				$objUserMsgs->msgid = $msgid;
				$objUserMsgs->userid = $objRs->fields["u_userid"];
				$objUserMsgs->msgfrom = $_SESSION["userid"];
				$objUserMsgs->msgtype = "IBX";
				$objUserMsgs->msgsubtype = "RA";  
				$objUserMsgs->priority=1;
				$objUserMsgs->msgsubject = $objRs->fields["name"];
				$objUserMsgs->msgbody = "Patient Name: " . $objTable->getudfvalue("u_patientname");
				$objUserMsgs->msgbody .= "\r\nAge: " . $objTable->getudfvalue("u_age");
				$objUserMsgs->msgbody .= "\r\nSex: " . $objTable->getudfvalue("u_sex");
				$objUserMsgs->msgbody .= "\r\nRoom No: " . $objTable->getudfvalue("u_bedno");
				$objUserMsgs->msgbody .= "\r\nAttending Physician: " . $doctorname;

				$objUserMsgs->status = "U";
				$objUserMsgs->msgdate = date('Y-m-d');
				$objUserMsgs->msgtime = date('H:i:s');
				$actionReturn = $objUserMsgs->add();
				if (!$actionReturn)	break;
				
			}				
		
			break;
		case "u_hisardeductions":
			if ($objTable->docstatus=="O") {
				$actionReturn = onCustomEventdocumentschema_brARDeductionPRGPSHIS($objTable);
			}	
			break;
		case "u_hisappfs":
			if ($objTable->docstatus=="O") {
				$actionReturn = onCustomEventdocumentschema_brAPPfsPRGPSHIS($objTable);
			}	
			break;
		case "u_hishealthinpaycms":
			if ($objTable->docstatus=="O") {
				$actionReturn = onCustomEventdocumentschema_brPostHealthInPayCMGPSHIS($objTable);
			}	
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSHIS");
	return $actionReturn;
}



function onAddEventdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	global $page;
	global $httpVars;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisips":
			if ($objTable->getudfvalue("u_migrated")!="1") {
				$actionReturn = onCustomEventdocumentschema_brAdmissionFeesGPSHIS($objTable);
			}	
			break;
		case "u_hismedsuptfs":
			if ($objTable->getudfvalue("u_trxtype")=="IP" || $objTable->getudfvalue("u_trxtype")=="OP") {
				$objRs = new recordset(null,$objConnection);
				$objRs->setdebug(true);
				$objRs->queryopen("select  u_itemcode, u_itemdesc, sum(u_quantity) as u_quantity from ( select b.u_itemdesc,b.u_itemcode,b.u_quantity from u_hismedsuptfins a, u_hismedsuptfinitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_fromdepartment='".$objTable->getudfvalue("u_todepartment")."'  and a.u_todepartment='".$objTable->getudfvalue("u_fromdepartment")."' and a.u_reftype='".$objTable->getudfvalue("u_reftype")."' and a.u_refno='".$objTable->getudfvalue("u_refno")."' group by b.u_itemdesc, b.u_itemcode union all select b.u_itemdesc, b.u_itemcode, b.u_quantity*-1 as u_quantity from u_hismedsups a, u_hismedsupitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_department='".$objTable->getudfvalue("u_fromdepartment")."' and a.u_reftype='".$objTable->getudfvalue("u_reftype")."' and a.u_refno='".$objTable->getudfvalue("u_refno")."' group by b.u_itemdesc,b.u_itemcode union all select b.u_itemdesc, b.u_itemcode, b.u_quantity*-1 as u_quantity from u_hismedsuptfs a, u_hismedsuptfitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_fromdepartment='".$objTable->getudfvalue("u_fromdepartment")."' and a.u_todepartment='".$objTable->getudfvalue("u_todepartment")."' and a.u_reftype='".$objTable->getudfvalue("u_reftype")."' and a.u_refno='".$objTable->getudfvalue("u_refno")."' group by b.u_itemdesc,b.u_itemcode) as x group by u_itemdesc, u_itemcode having u_quantity<0");
				if ($objRs->queryfetchrow("NAME")) {
					//$actionReturn = raiseError("Negative Stock on [".$objRs->fields["u_itemcode"]."/".$objRs->fields["u_itemdesc"]."/".$objRs->fields["u_quantity"]."]");
				}
			}
			break;
		case "u_hismedsupprs":
			if ($objTable->docstatus=="O") {
				$actionReturn = onCustomEventdocumentschema_brStockPRGPSHIS($objTable);
			}	
			break;
		case "u_hisphicclaims":
			$actionReturn = onCustomEventUpdateHealthCareInsFromdocumentschema_brGPSHIS($objTable);
			if ($actionReturn) $actionReturn = onCustomEventUpdateHealthCareInsTodocumentschema_brGPSHIS($objTable);
			if ($actionReturn && $objTable->docstatus!="D") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_postedby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brPostPHICCMGPSHIS($objTable);
			}	
			break;
		case "u_hispkgclaims":
			if ($actionReturn && $objTable->docstatus!="D") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_postedby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brPostPKGCMGPSHIS($objTable);
			}	
			break;
		case "u_hisrequests":
			if ($actionReturn && $objTable->getudfvalue("u_otheramount")!=0) {
				global $resumeOnError;
				$actionReturn = onCustomEventdocumentschema_brPostRequestCreditsGPSHIS($objTable);
				if ($actionReturn) $resumeOnError = 1;
			}	
			break;	
		case "u_hischarges":
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable);
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brLISendFileGPSHIS($objTable);
			break;
		case "u_hiscredits":
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable);
			break;
		case "u_hislabtests":
			//if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brLISendFile2GPSHIS($objTable);
			break;
		case "u_hispos":
			if ($objTable->getudfvalue("u_prepaid")=="2" || $objTable->getudfvalue("u_prepaid")=="3") {
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable);
				
			}	
			break;

	}
	//if ($actionReturn) $actionReturn = raiseError("onAddEventdocumentschema_brGPSHIS");
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;

	switch ($objTable->dbtable) {
		case "u_hisips":
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_editby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");				
				}	
			}
		
			$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
			$obju_HISPatientRegs = new masterdatalinesschema_br(null,$objConnection,"u_hispatientregs");
			$obju_HISIPRooms = new documentlinesschema_br(null,$objConnection,"u_hisiprooms");
			$obju_HISIPVs = new documentlinesschema_br(null,$objConnection,"u_hisipvs");
			$obju_HISRooms = new masterdataschema_br(null,$objConnection,"u_hisrooms");
			$obju_HISRoomBeds = new masterdatalinesschema_br(null,$objConnection,"u_hisroombeds");
			$objRs = new recordset(null,$objConnection);
			
			if ($actionReturn && ($objTable->getudfvalue("u_vsdate")!=$objTable->fields["U_VSDATE"] || substr($objTable->getudfvalue("u_vstime"),0,5)!=substr($objTable->fields["U_VSTIME"],0,5))) {
				$obju_HISIPVs->prepareadd();
				$obju_HISIPVs->docid = $objTable->docid;
				$obju_HISIPVs->lineid = getNextIdByBranch("u_hisipvs",$objConnection);	
				$obju_HISIPVs->setudfvalue("u_vsdate",$objTable->getudfvalue("u_vsdate"));
				$obju_HISIPVs->setudfvalue("u_vstime",$objTable->getudfvalue("u_vstime"));
				$obju_HISIPVs->setudfvalue("u_bt_c",$objTable->getudfvalue("u_bt_c"));
				$obju_HISIPVs->setudfvalue("u_bt_f",$objTable->getudfvalue("u_bt_f"));
				$obju_HISIPVs->setudfvalue("u_bp_s",$objTable->getudfvalue("u_bp_s"));
				$obju_HISIPVs->setudfvalue("u_bp_d",$objTable->getudfvalue("u_bp_d"));
				$obju_HISIPVs->setudfvalue("u_hr",$objTable->getudfvalue("u_hr"));
				$obju_HISIPVs->setudfvalue("u_rr",$objTable->getudfvalue("u_rr"));
				$obju_HISIPVs->setudfvalue("u_o2sat",$objTable->getudfvalue("u_o2sat"));
				$obju_HISIPVs->privatedata["header"] = $objTable;
				$actionReturn = $obju_HISIPVs->add();
			}
			
			if ($objTable->getudfvalue("u_department")!=$objTable->fields["U_DEPARTMENT"]) {
				$objTable->setudfvalue("u_nursed",0);
			}
			if ($objTable->getudfvalue("u_diettype")!=$objTable->fields["U_DIETTYPE"]) {
				$objTable->setudfvalue("u_prevdiettype",$objTable->fields["U_DIETTYPE"]);
				$objTable->setudfvalue("u_prevdiettypedesc",$objTable->fields["U_DIETTYPEDESC"]);
			}
			if ($objTable->getudfvalue("u_dietremarks")!=$objTable->fields["U_DIETREMARKS"]) {
				$objTable->setudfvalue("u_prevdietremarks",$objTable->fields["U_DIETREMARKS"]);
			}
			if ($objTable->getudfvalue("u_nursed")!=$objTable->fields["U_NURSED"] && $objTable->getudfvalue("u_nursed")=="1") {
				$objTable->setudfvalue("u_nurseddate",currentdateDB());
				$objTable->setudfvalue("u_nursedtime",date('H:i'));
			}

			if ($actionReturn && (($objTable->docstatus=="Discharged" && $objTable->fields["DOCSTATUS"]!="Discharged") || ($objTable->docstatus=="Cancelled" && $objTable->fields["DOCSTATUS"]!="Cancelled"))) {
				$objTable->setudfvalue("u_enddate",currentdateDB());
				$objTable->setudfvalue("u_endtime",date('H:i'));
				while (true) {
					if ($obju_HISIPRooms->getbysql("DOCID='".$objTable->docid."' AND U_ENDDATE IS NULL")) {
						if ($obju_HISRooms->getbykey($obju_HISIPRooms->getudfvalue("u_roomno"))) {
							if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$obju_HISIPRooms->getudfvalue("u_bedno")."'")) {
								if ($obju_HISRoomBeds->getudfvalue("u_status")=="Occupied") {
									
									$obju_HISIPRooms->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
									$obju_HISIPRooms->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
									if ($objTable->getudfvalue("u_rateuom")=="Hour") {
										$objRs->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$obju_HISIPRooms->getudfvalue("u_enddate")." ".$obju_HISIPRooms->getudfvalue("u_endtime")."','".$obju_HISIPRooms->getudfvalue("u_startdate")." ".$obju_HISIPRooms->getudfvalue("u_starttime")."'))/60)/60)))");
									} else {
										$objRs->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$obju_HISIPRooms->getudfvalue("u_enddate")." ".$obju_HISIPRooms->getudfvalue("u_endtime")."','".$obju_HISIPRooms->getudfvalue("u_startdate")." ".$obju_HISIPRooms->getudfvalue("u_starttime")."'))/60)/60)/24))");
									}	
									if ($objRs->queryfetchrow()) {
										$obju_HISIPRooms->setudfvalue("u_quantity",$objRs->fields[0]);
										$obju_HISIPRooms->setudfvalue("u_amount",$obju_HISIPRooms->getudfvalue("u_quantity")*$obju_HISIPRooms->getudfvalue("u_rate"));
									} else $actionReturn = raiseError("Unable to compute room fee.");
									
									$obju_HISIPRooms->privatedata["header"] = $objTable;
									if ($actionReturn) $actionReturn = $obju_HISIPRooms->update($obju_HISIPRooms->docid,$obju_HISIPRooms->lineid,$obju_HISIPRooms->rcdversion);
									
									$obju_HISRoomBeds->setudfvalue("u_status","For Cleaning");
									$obju_HISRoomBeds->setudfvalue("u_refno","");
									$obju_HISRoomBeds->setudfvalue("u_patientname","");
									$obju_HISRoomBeds->setudfvalue("u_date",$objTable->getudfvalue("u_enddate"));
									$obju_HISRoomBeds->setudfvalue("u_time",$objTable->getudfvalue("u_endtime"));
									$obju_HISRoomBeds->privatedata["header"] = $obju_HISRooms;
									if ($actionReturn) $actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
									if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
									
								} else $actionReturn = raiseError("Bed No.[".$obju_HISIPRooms->getudfvalue("u_bedno")."] is not occupied.");
							} else $actionReturn = raiseError("Unable to find Bed No.[".$obju_HISIPRooms->getudfvalue("u_bedno")."].");
						} else $actionReturn = raiseError("Unable to find Room No.[".$obju_HISIPRooms->getudfvalue("u_roomno")."].");
					} else break;	
					if (!$actionReturn) break;
				}					
			}
			
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_patientid")!="") {
					if ($obju_HISPatients->getbykey($objTable->getudfvalue("u_patientid"))) {
						if ($obju_HISPatientRegs->getbysql("CODE='".$obju_HISPatients->code."' AND U_REFTYPE='IP' AND U_REFNO='".$objTable->docno."'")) {
							$obju_HISPatientRegs->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
							$obju_HISPatientRegs->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
							$obju_HISPatientRegs->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
							$obju_HISPatientRegs->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
							$obju_HISPatientRegs->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
							$obju_HISPatientRegs->setudfvalue("u_expiretime",$objTable->getudfvalue("u_expiretime"));
							$obju_HISPatientRegs->setudfvalue("u_status",$objTable->docstatus);
							$obju_HISPatientRegs->privatedata["header"] = $obju_HISPatients;
							$actionReturn = $obju_HISPatientRegs->update($obju_HISPatientRegs->code,$obju_HISPatientRegs->lineid,$obju_HISPatientRegs->rcdversion);
							if ($actionReturn & ($objTable->getudfvalue("u_expired")!=$obju_HISPatients->getudfvalue("u_expired") || $objTable->getudfvalue("u_expiredate")!=$obju_HISPatients->getudfvalue("u_expiredate"))) {
								if ($objTable->getudfvalue("u_expired")!=$obju_HISPatients->getudfvalue("u_expired")) {
									$obju_HISPatients->setudfvalue("u_expired",$objTable->getudfvalue("u_expired"));
								}
								if ($objTable->getudfvalue("u_expiredate")!=$obju_HISPatients->getudfvalue("u_expiredate")) {
									$obju_HISPatients->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
								}
								$actionReturn = $obju_HISPatients->update($obju_HISPatients->code,$obju_HISPatients->rcdversion);
							}
						} else	$actionReturn = raiseError("Unable to find Patient In-Patient Reference No. [".$objTable->docno."]");	
					} else $actionReturn = raiseError("Unable to find Patient ID[".$objTable->getudfvalue("u_patientid")."]");			
				}	
			}	
			if ($actionReturn && $objTable->getudfvalue("u_mgh")=="1" && $objTable->fields["U_MGH"]=="0") {
				$objTable->setudfvalue("u_mghdate",currentdateDB());
				$objTable->setudfvalue("u_mghtime",date('H:i'));
			} elseif ($actionReturn && $objTable->getudfvalue("u_mgh")=="0" && $objTable->fields["U_MGH"]=="1") {
				/*
				while (true) {
					if ($obju_HISIPRooms->getbysql("DOCID='".$objTable->docid."' AND U_ENDDATE='".$objTable->getudfvalue("u_mghdate")."' AND U_ENDTIME='".$objTable->getudfvalue("u_mghtime")."'")) {
						if ($obju_HISRooms->getbykey($obju_HISIPRooms->getudfvalue("u_roomno"))) {
							if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$obju_HISIPRooms->getudfvalue("u_bedno")."'")) {
								if ($obju_HISRoomBeds->getudfvalue("u_status")=="Vacant") {
									
									$obju_HISIPRooms->setudfvalue("u_enddate","");
									$obju_HISIPRooms->setudfvalue("u_endtime","");
									$obju_HISIPRooms->setudfvalue("u_quantity",0);
									$obju_HISIPRooms->setudfvalue("u_amount",0);
									$obju_HISIPRooms->privatedata["header"] = $objTable;
									if ($actionReturn) $actionReturn = $obju_HISIPRooms->update($obju_HISIPRooms->docid,$obju_HISIPRooms->lineid,$obju_HISIPRooms->rcdversion);
									
									$obju_HISRoomBeds->setudfvalue("u_status","Occupied");
									$obju_HISRoomBeds->setudfvalue("u_refno",$objTable->docno);
									$obju_HISRoomBeds->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
									$obju_HISRoomBeds->privatedata["header"] = $obju_HISRooms;
									if ($actionReturn) $actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
									if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
									
								} else $actionReturn = raiseError("Bed No.[".$obju_HISIPRooms->getudfvalue("u_bedno")."] is not vacant.");
							} else $actionReturn = raiseError("Unable to find Bed No.[".$obju_HISIPRooms->getudfvalue("u_bedno")."].");
						} else $actionReturn = raiseError("Unable to find Room No.[".$obju_HISIPRooms->getudfvalue("u_roomno")."].");
					} else break;	
					if (!$actionReturn) break;
				}	
				if ($actionReturn) {*/
					$objTable->setudfvalue("u_mghdate","");
					$objTable->setudfvalue("u_mghtime","");
				//} else $page->setitem("u_mgh",1);
			}
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select group_concat(b.name separator ', ') from u_hisipins a inner join u_hishealthins b on b.code=a.u_inscode where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
				if ($objRs->queryfetchrow()) $objTable->setudfvalue("u_healthins",$objRs->fields[0]);
				else $objTable->setudfvalue("u_healthins","");
			}
			break;
		case "u_hisops":
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_editby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}
		
			$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
			$obju_HISPatientRegs = new masterdatalinesschema_br(null,$objConnection,"u_hispatientregs");
			$obju_HISOPVs = new documentlinesschema_br(null,$objConnection,"u_hisopvs");
			if ($actionReturn && ($objTable->getudfvalue("u_vsdate")!=$objTable->fields["U_VSDATE"] || substr($objTable->getudfvalue("u_vstime"),0,5)!=substr($objTable->fields["U_VSTIME"],0,5))) {
				$obju_HISOPVs->prepareadd();
				$obju_HISOPVs->docid = $objTable->docid;
				$obju_HISOPVs->lineid = getNextIdByBranch("u_hisopvs",$objConnection);	
				$obju_HISOPVs->setudfvalue("u_vsdate",$objTable->getudfvalue("u_vsdate"));
				$obju_HISOPVs->setudfvalue("u_vstime",$objTable->getudfvalue("u_vstime"));
				$obju_HISOPVs->setudfvalue("u_bt_c",$objTable->getudfvalue("u_bt_c"));
				$obju_HISOPVs->setudfvalue("u_bt_f",$objTable->getudfvalue("u_bt_f"));
				$obju_HISOPVs->setudfvalue("u_bp_s",$objTable->getudfvalue("u_bp_s"));
				$obju_HISOPVs->setudfvalue("u_bp_d",$objTable->getudfvalue("u_bp_d"));
				$obju_HISOPVs->setudfvalue("u_hr",$objTable->getudfvalue("u_hr"));
				$obju_HISOPVs->setudfvalue("u_rr",$objTable->getudfvalue("u_rr"));
				$obju_HISOPVs->setudfvalue("u_o2sat",$objTable->getudfvalue("u_o2sat"));
				$obju_HISOPVs->privatedata["header"] = $objTable;
				$actionReturn = $obju_HISOPVs->add();
			}
			
			$visitcount=0;
			if ($actionReturn && $objTable->docstatus=="Admitted" && $objTable->fields["DOCSTATUS"]=="Active") {
				$actionReturn = onCustomEventdocumentschema_brAdmitPatientGPSHIS($objTable);
				if ($actionReturn) {
					$objTable->setudfvalue("u_enddate",$objTable->getudfvalue("u_tfstartdate"));
					$objTable->setudfvalue("u_endtime",$objTable->getudfvalue("u_tfstarttime"));
					$visitcount=-1;
				}	
			}
			if ($actionReturn && $objTable->docstatus=="Discharged" && $objTable->fields["DOCSTATUS"]!="Discharged") {
				if ($objTable->getudfvalue("u_enddate")=="") {
					$objTable->setudfvalue("u_enddate",currentdateDB());
					$objTable->setudfvalue("u_endtime",date('H:i'));
				}	
			}
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_patientid")!="") {
					if ($obju_HISPatients->getbykey($objTable->getudfvalue("u_patientid"))) {
						if ($obju_HISPatientRegs->getbysql("CODE='".$obju_HISPatients->code."' AND U_REFTYPE='OP' AND U_REFNO='".$objTable->docno."'")) {
							$obju_HISPatientRegs->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
							$obju_HISPatientRegs->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
							$obju_HISPatientRegs->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
							$obju_HISPatientRegs->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
							$obju_HISPatientRegs->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
							$obju_HISPatientRegs->setudfvalue("u_expiretime",$objTable->getudfvalue("u_expiretime"));
							$obju_HISPatientRegs->setudfvalue("u_status",$objTable->docstatus);
							$obju_HISPatientRegs->privatedata["header"] = $obju_HISPatients;
							$actionReturn = $obju_HISPatientRegs->update($obju_HISPatientRegs->code,$obju_HISPatientRegs->lineid,$obju_HISPatientRegs->rcdversion);
							if ($actionReturn & ($objTable->getudfvalue("u_expired")!=$obju_HISPatients->getudfvalue("u_expired") || $objTable->getudfvalue("u_expiredate")!=$obju_HISPatients->getudfvalue("u_expiredate"))) {
								if ($objTable->getudfvalue("u_expired")!=$obju_HISPatients->getudfvalue("u_expired")) {
									$obju_HISPatients->setudfvalue("u_expired",$objTable->getudfvalue("u_expired"));
								}
								if ($objTable->getudfvalue("u_expiredate")!=$obju_HISPatients->getudfvalue("u_expiredate")) {
									$obju_HISPatients->setudfvalue("u_expiredate",$objTable->getudfvalue("u_expiredate"));
								}
								$actionReturn = $obju_HISPatients->update($obju_HISPatients->code,$obju_HISPatients->rcdversion);
							}
							if ($visitcount<0 && !$actionReturn) $page->setitem("docstatus", "Active");
	
						} else	$actionReturn = raiseError("Unable to find Patient Out-Patient Reference No. [".$objTable->docno."]");	
					} else $actionReturn = raiseError("Unable to find Patient ID[".$objTable->getudfvalue("u_patientid")."]");			
				}	
			}
			if ($actionReturn && $objTable->getudfvalue("u_mgh")=="1" && $objTable->fields["U_MGH"]=="0") {
				$objTable->setudfvalue("u_mghdate",currentdateDB());
				$objTable->setudfvalue("u_mghtime",date('H:i'));
				if (!$actionReturn) $page->setitem("u_mgh",0);
			} elseif ($actionReturn && $objTable->getudfvalue("u_mgh")=="0" && $objTable->fields["U_MGH"]=="1") {
				if ($actionReturn) {
					$objTable->setudfvalue("u_mghdate","");
					$objTable->setudfvalue("u_mghtime","");
				} else $page->setitem("u_mgh",1);
			}						
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select group_concat(b.name separator ', ') from u_hisopins a inner join u_hishealthins b on b.code=a.u_inscode where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
				if ($objRs->queryfetchrow()) $objTable->setudfvalue("u_healthins",$objRs->fields[0]);
				else $objTable->setudfvalue("u_healthins","");
			}
			
			break;
		case "u_hislabtests":
			
			if ($actionReturn) {
				if ($objTable->docstatus=="C") {
					$objTable->setudfvalue("u_enddate",$page->getitemdate("u_enddate"));
					$objTable->setudfvalue("u_endtime",$page->getitemstring("u_endtime"));
				}
				if ($objTable->getudfvalue("u_enddate")=="" && $objTable->fields["U_ENDDATE"]!="") {
					$obju_HISLabTestReOpens = new documentlinesschema_br(null,$objConnection,"u_hislabtestreopens");
					$obju_HISLabTestReOpens->prepareadd();
					$obju_HISLabTestReOpens->docid = $objTable->docid;
					$obju_HISLabTestReOpens->lineid = getNextIdByBranch("u_hislabtestreopens",$objConnection);
					$obju_HISLabTestReOpens->setudfvalue("u_date",currentdateDB());
					$obju_HISLabTestReOpens->setudfvalue("u_time",date('H:i'));
					$obju_HISLabTestReOpens->setudfvalue("u_prevclosedate",$objTable->getudfvalue("u_enddate"));
					$obju_HISLabTestReOpens->setudfvalue("u_prevclosetime",$objTable->getudfvalue("u_endtime"));
					$obju_HISLabTestReOpens->setudfvalue("u_by",$_SESSION["userid"]);
					$obju_HISLabTestReOpens->setudfvalue("u_remarks",$objTable->getudfvalue("u_reopenremarks"));
					$actionReturn = $obju_HISLabTestReOpens->add();
					$objTable->setudfvalue("u_reopenremarks","");
					$objTable->setudfvalue("u_enddate","");
					$objTable->setudfvalue("u_endtime","");
				}
				/*if ($actionReturn) {
					if ($objTable->getudfvalue("u_refno")!="") {
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
							$obju_HISIPLabTests = new documentlinesschema_br(null,$objConnection,"u_hisiplabtests");
							if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
								if ($obju_HISIPLabTests->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
									$obju_HISIPLabTests->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
									$obju_HISIPLabTests->setudfvalue("u_refdate",$objTable->getudfvalue("u_startdate"));
									$obju_HISIPLabTests->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
									$obju_HISIPLabTests->setudfvalue("u_status",$objTable->docstatus);
									$obju_HISIPLabTests->privatedata["header"] = $obju_HISIPs;
									$actionReturn = $obju_HISIPLabTests->update($obju_HISIPLabTests->docid,$obju_HISIPLabTests->lineid,$obju_HISIPLabTests->rcdversion);
								} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."] Laboratory Test Reference No. [".$objTable->docno."]");	
							} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
						} else {
							$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
							$obju_HISIPLabTests = new documentlinesschema_br(null,$objConnection,"u_hisoplabtests");
							if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
								if ($obju_HISIPLabTests->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
									$obju_HISIPLabTests->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
									$obju_HISIPLabTests->setudfvalue("u_refdate",$objTable->getudfvalue("u_startdate"));
									$obju_HISIPLabTests->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
									$obju_HISIPLabTests->setudfvalue("u_status",$objTable->docstatus);
									$obju_HISIPLabTests->privatedata["header"] = $obju_HISIPs;
									$actionReturn = $obju_HISIPLabTests->update($obju_HISIPLabTests->docid,$obju_HISIPLabTests->lineid,$obju_HISIPLabTests->rcdversion);
								} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."] Laboratory Test Reference No. [".$objTable->docno."]");	
							} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
						}	
					}	
				}	
				*/
			}	
			break;
		case "u_hisconsultancyfees":
			if ($objTable->getudfvalue("u_reftype")=="IP") {
				$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
				$obju_HISIPConsultancyFees = new documentlinesschema_br(null,$objConnection,"u_hisipconsultancyfees");
				if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
					if ($obju_HISIPs->getudfvalue("u_billno")!="") return raiseError("Bill for [".$objTable->getudfvalue("u_refno")."] already generated, cannot add new charges.");
					if ($obju_HISIPConsultancyFees->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
						if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
							$actionReturn = $obju_HISIPConsultancyFees->delete();
						} else {
							$obju_HISIPConsultancyFees->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
							$obju_HISIPConsultancyFees->setudfvalue("u_refdate",$objTable->getudfvalue("u_startdate"));
							$obju_HISIPConsultancyFees->setudfvalue("u_description",$objTable->getudfvalue("u_itemdesc"));
							$obju_HISIPConsultancyFees->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
							$obju_HISIPConsultancyFees->setudfvalue("u_itemamount",$objTable->getudfvalue("u_itemamount"));
							$obju_HISIPConsultancyFees->setudfvalue("u_totalamount",$objTable->getudfvalue("u_totalamount"));
							$obju_HISIPConsultancyFees->privatedata["header"] = $obju_HISIPs;
							$actionReturn = $obju_HISIPConsultancyFees->update($obju_HISIPConsultancyFees->docid,$obju_HISIPConsultancyFees->lineid,$obju_HISIPConsultancyFees->rcdversion);
						}	
					}	
				} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			} else {
				$obju_HISOPs = new documentschema_br(null,$objConnection,"u_hisops");
				$obju_HISOPConsultancyFees = new documentlinesschema_br(null,$objConnection,"u_hisopconsultancyfees");
				if ($obju_HISOPs->getbykey($objTable->getudfvalue("u_refno"))) {
					if ($obju_HISOPs->getudfvalue("u_billno")!="") return raiseError("Bill for [".$objTable->getudfvalue("u_refno")."] already generated, cannot add new charges.");
					if ($obju_HISOPConsultancyFees->getbysql("DOCID='".$obju_HISOPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
						if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
							$actionReturn = $obju_HISOPConsultancyFees->delete();
						} else {
							$obju_HISOPConsultancyFees->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
							$obju_HISOPConsultancyFees->setudfvalue("u_refdate",$objTable->getudfvalue("u_startdate"));
							$obju_HISOPConsultancyFees->setudfvalue("u_description",$objTable->getudfvalue("u_itemdesc"));
							$obju_HISOPConsultancyFees->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
							$obju_HISOPConsultancyFees->setudfvalue("u_itemamount",$objTable->getudfvalue("u_itemamount"));
							$obju_HISOPConsultancyFees->setudfvalue("u_totalamount",$objTable->getudfvalue("u_totalamount"));
							$obju_HISOPConsultancyFees->privatedata["header"] = $obju_HISOPs;
							$actionReturn = $obju_HISOPConsultancyFees->update($obju_HISOPConsultancyFees->docid,$obju_HISOPConsultancyFees->lineid,$obju_HISOPConsultancyFees->rcdversion);
						}	
					}	
				} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			}
			// update all anesthesiologist	
			if ($actionReturn && $objTable->getudfvalue("u_doctortype")=="SUR") {
				$obju_HISConsultancyFees = new documentschema_br(null,$objConnection,"u_hisconsultancyfees");
				$obju_HISConsultancyFees->queryopen($obju_HISConsultancyFees->selectstring() . " AND U_SURGEONREFNO='".$objTable->docno."'");
				while ($obju_HISConsultancyFees->queryfetchrow()) {
					if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
						$obju_HISConsultancyFees->setudfvalue("u_surgeonfee",0);
					} else {
						$obju_HISConsultancyFees->setudfvalue("u_surgeonfee",$objTable->getudfvalue("u_amount"));
					}	
					$actionReturn = $obju_HISConsultancyFees->update($obju_HISConsultancyFees->docno,$obju_HISConsultancyFees->rcdversion);
					if (!$actionReturn) break;
				}
			}
			
			break;
		case "u_hisroomrs":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				$obju_HISRooms = new masterdataschema_br(null,$objConnection,"u_hisrooms");
				$obju_HISRoomBeds = new masterdatalinesschema_br(null,$objConnection,"u_hisroombeds");
				if ($obju_HISRooms->getbykey($objTable->getudfvalue("u_roomno"))) {
					if ($obju_HISRoomBeds->getbysql("U_BEDNO='".$objTable->getudfvalue("u_bedno")."'")) {
						if ($obju_HISRoomBeds->getudfvalue("u_status")=="Reserved") {
							$obju_HISRoomBeds->setudfvalue("u_status","Vacant");
							$obju_HISRoomBeds->setudfvalue("u_refno","");
							$obju_HISRoomBeds->setudfvalue("u_patientname","");
							$obju_HISRoomBeds->setudfvalue("u_date",currentdateDB());
							$obju_HISRoomBeds->setudfvalue("u_time",date('H:i'));
							$actionReturn = $obju_HISRoomBeds->update($obju_HISRoomBeds->code,$obju_HISRoomBeds->lineid,$obju_HISRoomBeds->rcdversion);
							if ($actionReturn) $actionReturn = $obju_HISRooms->update($obju_HISRooms->code,$obju_HISRooms->rcdversion);
						} else return raiseError("Bed No.[".$objTable->getudfvalue("u_bedno")."] is not reserved.");
					} else return raiseError("Unable to find Bed No.[".$objTable->getudfvalue("u_bedno")."].");
				} else return raiseError("Unable to find Room No.[".$objTable->getudfvalue("u_roomno")."].");
			}	
			break;			
		case "u_hismiscs":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					$obju_HISIPMiscs = new documentlinesschema_br(null,$objConnection,"u_hisipmiscs");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($obju_HISIPs->getudfvalue("u_billno")!="") {
							//$page->setitem("docstatus","O");
							return raiseError("Bill for [".$objTable->getudfvalue("u_refno")."] already generated. Cancellation cannot be undone");
						}	
						if ($obju_HISIPMiscs->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
							$obju_HISIPMiscs->setudfvalue("u_status","CN");
							$actionReturn = $obju_HISIPMiscs->update($obju_HISIPMiscs->docid,$obju_HISIPMiscs->lineid,$obju_HISIPMiscs->rcdversion);
						} else 	$actionReturn = raiseError("Unable to find Miscellaneous Document [".$objTable->docno."] in Reference No.[".$objTable->getudfvalue("u_refno")."]");
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					$obju_HISIPMiscs = new documentlinesschema_br(null,$objConnection,"u_hisopmiscs");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($obju_HISIPs->getudfvalue("u_billno")!="") {
							//$page->setitem("docstatus","O");
							return raiseError("Bill for [".$objTable->getudfvalue("u_refno")."] already generated. Cancellation cannot be undone");
						}	
						if ($obju_HISIPMiscs->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
							$obju_HISIPMiscs->setudfvalue("u_status","CN");
							$actionReturn = $obju_HISIPMiscs->update($obju_HISIPMiscs->docid,$obju_HISIPMiscs->lineid,$obju_HISIPMiscs->rcdversion);
						} else 	$actionReturn = raiseError("Unable to find Miscellaneous Document [".$objTable->docno."] in Reference No.[".$objTable->getudfvalue("u_refno")."]");
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}	
			}	
			break;			
		case "u_hissplrooms":
			if ($objTable->getudfvalue("u_reftype")=="IP") {
				$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
				$obju_HISIPSPLRooms = new documentlinesschema_br(null,$objConnection,"u_hisipsplrooms");
				if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
					if ($obju_HISIPSPLRooms->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
						$obju_HISIPSPLRooms->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
						$obju_HISIPSPLRooms->setudfvalue("u_refdate",$objTable->getudfvalue("u_startdate"));
						$obju_HISIPSPLRooms->setudfvalue("u_roomno",$objTable->getudfvalue("u_roomno"));
						$obju_HISIPSPLRooms->setudfvalue("u_bedno",$objTable->getudfvalue("u_bedno"));
						$obju_HISIPSPLRooms->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
						$obju_HISIPSPLRooms->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
						$obju_HISIPSPLRooms->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
						$obju_HISIPSPLRooms->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
						$obju_HISIPSPLRooms->setudfvalue("u_rate",$objTable->getudfvalue("u_rate"));
						$obju_HISIPSPLRooms->setudfvalue("u_rateuom",$objTable->getudfvalue("u_rateuom"));
						$obju_HISIPSPLRooms->setudfvalue("u_quantity",$objTable->getudfvalue("u_quantity"));
						$obju_HISIPSPLRooms->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
						$obju_HISIPSPLRooms->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPSPLRooms->update($obju_HISIPSPLRooms->docid,$obju_HISIPSPLRooms->lineid,$obju_HISIPSPLRooms->rcdversion);
					}	
				} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			} else {
				$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
				$obju_HISIPSPLRooms = new documentlinesschema_br(null,$objConnection,"u_hisopsplrooms");
				if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
					if ($obju_HISIPSPLRooms->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_REFNO='".$objTable->docno."'")) {
						$obju_HISIPSPLRooms->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
						$obju_HISIPSPLRooms->setudfvalue("u_refdate",$objTable->getudfvalue("u_startdate"));
						$obju_HISIPSPLRooms->setudfvalue("u_roomno",$objTable->getudfvalue("u_roomno"));
						$obju_HISIPSPLRooms->setudfvalue("u_bedno",$objTable->getudfvalue("u_bedno"));
						$obju_HISIPSPLRooms->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
						$obju_HISIPSPLRooms->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
						$obju_HISIPSPLRooms->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
						$obju_HISIPSPLRooms->setudfvalue("u_endtime",$objTable->getudfvalue("u_endtime"));
						$obju_HISIPSPLRooms->setudfvalue("u_rate",$objTable->getudfvalue("u_rate"));
						$obju_HISIPSPLRooms->setudfvalue("u_rateuom",$objTable->getudfvalue("u_rateuom"));
						$obju_HISIPSPLRooms->setudfvalue("u_quantity",$objTable->getudfvalue("u_quantity"));
						$obju_HISIPSPLRooms->setudfvalue("u_amount",$objTable->getudfvalue("u_amount"));
						$obju_HISIPSPLRooms->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPSPLRooms->update($obju_HISIPSPLRooms->docid,$obju_HISIPSPLRooms->lineid,$obju_HISIPSPLRooms->rcdversion);
					}	
				} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
			}	
			break;
		case "u_hisinclaims":
			if ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_postedby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brUpdateBillBenefitGPSHIS($objTable);
			} elseif ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brUpdateBillBenefitGPSHIS($objTable,true);
			} else {
				
				$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
				$obju_HISBillIns = new documentlinesschema_br(null,$objConnection,"u_hisbillins");
				if ($obju_HISBills->getbykey($objTable->getudfvalue("u_billno"))) {
					if ($obju_HISBillIns->getbysql("DOCID='".$obju_HISBills->docid."' AND U_INSCODE='".$objTable->getudfvalue("u_inscode")."'")) {
						//$obju_HISBillIns->setudfvalue("u_status","C");
						$obju_HISBillIns->setudfvalue("u_claimno",$objTable->docno);
						$obju_HISBillIns->setudfvalue("u_amount",$objTable->getudfvalue("u_thisamount"));
						$obju_HISBillIns->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillIns->update($obju_HISBillIns->docid,$obju_HISBillIns->lineid,$obju_HISBillIns->rcdversion);
					} //else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Health Insurance Claim No [".$objTable->docno."]");
					if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."]");	
				
			}
			break;	
		case "u_hismedsuptfs":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				$obju_HISMedSupRequests = new documentschema_br(null,$objConnection,"u_hismedsuprequests");
				if ($obju_HISMedSupRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
					$obju_HISMedSupRequests->docstatus = "O";
					if ($actionReturn) $actionReturn = $obju_HISMedSupRequests->update($obju_HISMedSupRequests->docno,$obju_HISMedSupRequests->rcdversion);
				} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."]");	
			}	
			break;
		case "u_hismedsupstocktfs":
			if ($objTable->docstatus!="D" && $objTable->fields["DOCSTATUS"]=="D") {
				if ($objTable->getudfvalue("u_requestno")!="") {
					$obju_HISMedSupStockRequests = new documentschema_br(null,$objConnection,"u_hismedsupstockrequests");
					if ($obju_HISMedSupStockRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
						$obju_HISMedSupStockRequests->docstatus = "C";
						if ($actionReturn) $actionReturn = $obju_HISMedSupStockRequests->update($obju_HISMedSupStockRequests->docno,$obju_HISMedSupStockRequests->rcdversion);
					} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."]");	
				}	
				if ($actionReturn && $objTable->getudfvalue("u_intransit")=="0") {
					if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostStockTransferGPSHIS($objTable);
					if ($actionReturn) $objTable->docstatus = "C";
				}
			}	
			break;
		case "u_hisbills":
			if ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_billby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostBillGPSHIS($objTable);
				if ($actionReturn && $objTable->docstatus !="CN") {
					if (bcsub($objTable->getudfvalue("u_dueamount"),$objTable->getudfvalue("u_paidamount"),14)==0) $objTable->docstatus ="C";
					else $objTable->docstatus ="O";
				}	
			} elseif ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="CN") {
				return raiseError("Cannot re-open cancelled bill.");
			} elseif ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="D") {
				return raiseError("Cannot cancel pre-bill. Refresh the page and remove draft instead.");
			} elseif ($objTable->docstatus=="CN" && ($objTable->fields["DOCSTATUS"]=="O" || $objTable->fields["DOCSTATUS"]=="C")) {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostBillGPSHIS($objTable,true);
			} elseif ($objTable->docstatus!="CN" && $objTable->docstatus !="D") {
				if (bcsub($objTable->getudfvalue("u_dueamount"),$objTable->getudfvalue("u_paidamount"),14)==0) $objTable->docstatus ="C";
				else $objTable->docstatus ="O";
			}
			if ($actionReturn) {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select group_concat(b.name separator ', ') from u_hisbillins a inner join u_hishealthins b on b.code=a.u_inscode where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
				if ($objRs->queryfetchrow()) $objTable->setudfvalue("u_healthins",$objRs->fields[0]);
				else $objTable->setudfvalue("u_healthins","");
			}
			break;	
		case "u_hispronotes":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				$actionReturn = onCustomEventdocumentschema_brUpdateBillPronoteGPSHIS($objTable,true);
			}
			break;	
		case "u_hisdietaries":
			if (isset($page)) {
				if ($page->getcolumnstring("T50",0,"userid")!="") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
					if (!$objRs->queryfetchrow()) {
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
						return raiseError("Invalid user or password.");
					}	
					$objTable->setudfvalue("u_editby",$page->getcolumnstring("T50",0,"userid"));
					$page->setcolumn("T50",0,"userid","");
					$page->setcolumn("T50",0,"password","");
				}	
			}		
			break;
		case "u_hispackagerequests":
			if (($objTable->docstatus=="CN") && $objTable->fields["DOCSTATUS"]=="O") {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				$objRs = new recordset(null,$objConnection);
				$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
				$obju_HISPackageRequestDocs = new documentlinesschema_br(null,$objConnection,"u_hispackagerequestdocs");
				$obju_HISPackageRequestDocs->queryopen($obju_HISPackageRequestDocs->selectstring()." AND DOCID='$objTable->docid'");
				while ($obju_HISPackageRequestDocs->queryfetchrow()) {
					if ($obju_HISRequests->getbykey($obju_HISPackageRequestDocs->getudfvalue("u_reqno"))) {
						if ($obju_HISRequests->getudfvalue("u_payrefno")=="") {
							$objRs->queryopen("select sum(u_chrgqty) from u_hisrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid'");
							if ($objRs->queryfetchrow()) {
								if ($objRs->fields[0]>0) return raiseError("Request No. [".$obju_HISPackageRequestDocs->getudfvalue("u_reqno")."] have one or more items have been rendered.");
							}
							$obju_HISRequests->docstatus="CN";
							$obju_HISRequests->setudfvalue("u_cancelledby",$objTable->getudfvalue("u_cancelledby"));
							$obju_HISRequests->setudfvalue("u_cancelledreason",$objTable->getudfvalue("u_cancelledreason"));
							$obju_HISRequests->setudfvalue("u_cancelledremarks",$objTable->getudfvalue("u_cancelledremarks"));
							$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
						} else return raiseError("Request No.[".$obju_HISPackageRequestDocs->getudfvalue("u_reqno")."] have been paid already.");
					} else return raiseError("Unable to find Request No.[".$obju_HISPackageRequestDocs->getudfvalue("u_reqno")."]");
					if (!$actionReturn) break;
				}
			}
			break;
		case "u_hisrequests":
			if (($objTable->docstatus=="CN" || $objTable->docstatus=="C") && $objTable->fields["DOCSTATUS"]=="O") {
				if ($objTable->docstatus=="CN") {
					if (isset($page)) {
						if ($page->getcolumnstring("T51",0,"userid")!="") {
							$objRs = new recordset(null,$objConnection);
							$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
							if (!$objRs->queryfetchrow()) {
								$page->setcolumn("T51",0,"userid","");
								$page->setcolumn("T51",0,"password","");
								return raiseError("Invalid user or password.");
							}	
							$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
							$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
							$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							$page->setcolumn("T51",0,"cancelreason","");
							$page->setcolumn("T51",0,"remarks","");
						}	
					}		
					if ($actionReturn && $objTable->getudfvalue("u_otheramount")!=0) $actionReturn = onCustomEventdocumentschema_brPostRequestCreditsGPSHIS($objTable,true);
				}	
			} else {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_editby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				if ($actionReturn) {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select sum(if(u_isfoc=1,0,if(u_isstat=1,u_statunitprice,u_unitprice))*u_quantity) as u_amountbefdisc, sum(if(u_isfoc,0,u_vatamount)) as u_vatamount, sum(if(u_isfoc,0,u_discamount)*u_quantity) as u_discamount, sum(if(u_isfoc,0,u_linetotal)) as u_amount from u_hisrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'",false);
					if ($objRs->queryfetchrow("NAME")) {
						$objTable->setudfvalue("u_amountbefdisc",$objRs->fields["u_amountbefdisc"]);
						if ($objTable->getudfvalue("u_scdisc")==1) {
							$objTable->setudfvalue("u_discamount",$objRs->fields["u_amountbefdisc"] - ($objRs->fields["u_amount"]+$objRs->fields["u_vatamount"]));
						} else {
							$objTable->setudfvalue("u_discamount",$objRs->fields["u_amountbefdisc"] - $objRs->fields["u_amount"]);
						}	
						$objTable->setudfvalue("u_vatamount",$objRs->fields["u_vatamount"]);
						$objTable->setudfvalue("u_amount",$objRs->fields["u_amount"]);
					}
				}						
				if ($actionReturn && $objTable->getudfvalue("u_otheramount")!=$objTable->fields["U_OTHERAMOUNT"] && $objTable->getudfvalue("u_otheramount")!=0) $actionReturn = onCustomEventdocumentschema_brPostRequestCreditsGPSHIS($objTable);
			}	
			break;
		case "u_hischarges":
		
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
				$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hisrequestitems");
				$obju_HISRequestItemPacks = new documentlinesschema_br(null,$objConnection,"u_hisrequestitempacks");
				if ($actionReturn && $objTable->getudfvalue("u_requestno")!="") {
					if ($obju_HISRequests->getbykey($objTable->getudfvalue("u_requestno"))) {
						if ($actionReturn) {
							$objRs->queryopen("select a.u_itemcode, a.u_itemdesc, a.u_quantity from u_hischargeitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
							while ($objRs->queryfetchrow("NAME")) {
								if ($obju_HISRequestItems->getbysql("DOCID='$obju_HISRequests->docid' AND U_ITEMCODE='".$objRs->fields["u_itemcode"]."'")) {
									$obju_HISRequestItems->setudfvalue("u_chrgqty",$obju_HISRequestItems->getudfvalue("u_chrgqty")-$objRs->fields["u_quantity"]);
									if ($obju_HISRequestItems->getudfvalue("u_chrgqty")<0) {
										return raiseError("Charge Qty [".$obju_HISRequestItems->getudfvalue("u_chrgqty")."] cannot be negative for Request No.[".$objTable->getudfvalue("u_requestno")."] for item [".$objRs->fields["u_itemdesc"]."]");	
									}
									$actionReturn = $obju_HISRequestItems->update($obju_HISRequestItems->docid,$obju_HISRequestItems->lineid,$obju_HISRequestItems->rcdversion);
								} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."] for Item [".$objRs->fields["u_itemdesc"]."]");	
								if (!$actionReturn) break;
							}
						}	
						if ($actionReturn) {
							$objRs->queryopen("select a.u_packagecode, a.u_itemcode, a.u_itemdesc, a.u_quantity from u_hischargeitempacks a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
							while ($objRs->queryfetchrow("NAME")) {
								if ($obju_HISRequestItemPacks->getbysql("DOCID='$obju_HISRequests->docid' and U_PACKAGECODE='".$objRs->fields["u_packagecode"]."' and U_ITEMCODE='".$objRs->fields["u_itemcode"]."'")) {
									$obju_HISRequestItemPacks->setudfvalue("u_chrgqty",$obju_HISRequestItemPacks->getudfvalue("u_chrgqty")-$objRs->fields["u_quantity"]);
									if ($obju_HISRequestItemPacks->getudfvalue("u_chrgqty")<0) {
										return raiseError("Charge Qty [".$obju_HISRequestItemPacks->getudfvalue("u_chrgqty")."] cannot be negative for Package Items [".$objRs->fields["u_itemdesc"]."]");	
									}
									$actionReturn = $obju_HISRequestItemPacks->update($obju_HISRequestItemPacks->docid,$obju_HISRequestItemPacks->lineid,$obju_HISRequestItemPacks->rcdversion);
								} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."] for Package Item [".$objRs->fields["u_itemdesc"]."]");	
								if (!$actionReturn) break;
							}
						}	
						if ($actionReturn) {
							$objRs2 = new recordset(null,$objConnection);
							$objRs2->queryopen("select sum(u_openqty) from (select sum(u_quantity-u_chrgqty) as u_openqty from u_hisrequestitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid' union all select sum(u_quantity-u_chrgqty) as u_openqty from u_hisrequestitempacks where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_HISRequests->docid') as x");
							if ($objRs2->queryfetchrow()) {
								if ($objRs2->fields[0]==0) $obju_HISRequests->docstatus="C";
								else $obju_HISRequests->docstatus="O";
							}					
							$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
						}
		
						//if ($actionReturn) $actionReturn = raiseError("rey");
					} else return raiseError("Unable to retrieve Request No.[".$objTable->getudfvalue("u_requestno")."]");	
				}					
				if ($actionReturn && ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP")) {
					if ($objTable->getudfvalue("u_reftype")=="IP") {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
						$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
					} else {
						$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
						$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
					}	
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						if ($obju_HISIPMedSups->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_DOCTYPE='CHRG' AND U_DOCNO='".$objTable->docno."'")) {
							$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
							$obju_HISIPMedSups->setudfvalue("u_balance",0);
							$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
							$actionReturn = $obju_HISIPMedSups->update($obju_HISIPMedSups->docid,$obju_HISIPMedSups->lineid,$obju_HISIPMedSups->rcdversion);
						} else {
							 $actionReturn = raiseError("Unable to find Transaction No.[".$objTable->docno."] in Reference No.[".$objTable->getudfvalue("u_refno")."]");
						} 
						if ($actionReturn) {
							/*if ($objTable->getudfvalue("u_payrefno")=="") {
								$obju_HISIPs->setudfvalue("u_totalcharges",$obju_HISIPs->getudfvalue("u_totalcharges")-$objTable->getudfvalue("u_amount"));
								$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit")+$obju_HISIPs->getudfvalue("u_totalcharges"));
								$obju_HISIPs->setudfvalue("u_availablecreditperc",100-(($obju_HISIPs->getudfvalue("u_totalcharges")+$obju_HISIPs->getudfvalue("u_totalcharges"))*100));
							}*/	
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						}	
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}				
				$actionReturn = onCustomEventdocumentschema_brPostChargesGPSHIS($objTable,true);
			}	
			if ($actionReturn && $objTable->getudfvalue("u_reopenremarks")!="" && $objTable->fields["U_REOPENREMARKS"]=="") {
				$obju_HISLabTestReOpens = new documentlinesschema_br(null,$objConnection,"u_hischargereopens");
				$obju_HISLabTestReOpens->prepareadd();
				$obju_HISLabTestReOpens->docid = $objTable->docid;
				$obju_HISLabTestReOpens->lineid = getNextIdByBranch("u_hischargereopens",$objConnection);
				$obju_HISLabTestReOpens->setudfvalue("u_date",currentdateDB());
				$obju_HISLabTestReOpens->setudfvalue("u_time",date('H:i'));
				$obju_HISLabTestReOpens->setudfvalue("u_prevclosedate",$objTable->getudfvalue("u_enddate"));
				$obju_HISLabTestReOpens->setudfvalue("u_prevclosetime",$objTable->getudfvalue("u_endtime"));
				$obju_HISLabTestReOpens->setudfvalue("u_by",$_SESSION["userid"]);
				$obju_HISLabTestReOpens->setudfvalue("u_remarks",$objTable->getudfvalue("u_reopenremarks"));
				$actionReturn = $obju_HISLabTestReOpens->add();
				$objTable->setudfvalue("u_enddate","");
				$objTable->setudfvalue("u_endtime","");
				$objTable->setudfvalue("u_reopenremarks","");
			}
			break;
		case "u_hiscredits":
			//$actionReturn = onCustomEventdocumentschema_brPostCreditsGPSHIS($objTable);
			break;	
		case "u_hispriceadjs":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				if ($actionReturn && $objTable->getudfvalue("u_chargeno")!="") {
					$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
					$obju_HISChargeItems = new documentlinesschema_br(null,$objConnection,"u_hischargeitems");
					if ($obju_HISCharges->getbykey($objTable->getudfvalue("u_chargeno"))) {
						if ($obju_HISCharges->getudfvalue("u_adjno")==$objTable->docno) {
							$objRs->queryopen("select a.u_chargelineid, a.u_itemdesc, a.u_linetotal from u_hispriceadjitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
							while ($objRs->queryfetchrow("NAME")) {
								if ($obju_HISChargeItems->getbysql("LINEID='".$objRs->fields["u_chargelineid"]."'")) {
									$obju_HISChargeItems->setudfvalue("u_adjamt",0);
									$obju_HISChargeItems->setudfvalue("u_adjprice",0);
									$actionReturn = $obju_HISChargeItems->update($obju_HISChargeItems->docid,$obju_HISChargeItems->lineid,$obju_HISChargeItems->rcdversion);
								} else return raiseError("Unable to retrieve Charge No.[".$objTable->getudfvalue("u_chargeno")."] for Item [".$objRs->fields["u_itemdesc"]."]");	
								if (!$actionReturn) break;
							}
						} else return raiseError("Charge No.[".$objTable->getudfvalue("u_chargeno")."] adjustment [".$obju_HISCharges->getudfvalue("u_adjno")."] is not the current document.");
						if ($actionReturn) {
							$obju_HISCharges->setudfvalue("u_adjno","");
							$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
						}
					} else return raiseError("Unable to retrieve Charge/Request No. No.[".$objTable->getudfvalue("u_requestno")."]");	
	
				}	
				if ($actionReturn) {
					if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
						if ($objTable->getudfvalue("u_reftype")=="IP") {
							$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
							$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
						} else {
							$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
							$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
						}	
						if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
							if ($objTable->getudfvalue("u_reftype")=="IP") {
								if ($objTable->getudfvalue("u_trxtype")!="BILLING" && $objTable->getudfvalue("u_mgh")=="1" && $objTable->getudfvalue("u_expired")=="0") {
									return raiseError("Patient already tag as May Go Home. Only Billing can add adjustment.");
								}
							}
							if ($obju_HISIPs->getudfvalue("u_billno")!="" && $objTable->getudfvalue("u_billno")!="") {
								return raiseError("Patient bill already generated. You can only add adjustment.");
							}
						
							if ($obju_HISIPMedSups->getbysql("DOCID='$obju_HISIPs->docid' AND U_DOCTYPE='ADJ' AND U_DOCNO='$objTable->docno'")) {
								$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
								$obju_HISIPMedSups->setudfvalue("u_balance",0);
								$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
								$actionReturn = $obju_HISIPMedSups->update($obju_HISIPMedSups->docid,$obju_HISIPMedSups->lineid,$obju_HISIPMedSups->rcdversion);
								if ($actionReturn) {
									$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
								}
							}	
						} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
					}
				}	
				if ($actionReturn) {
					$actionReturn = onCustomEventdocumentschema_brPostAdjustmentsGPSHIS($objTable,true);
				}
			}		
			break;
		case "u_hispos":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="C") {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username, u_cancelpayflag from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						if ($objRs->fields[1]==0) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("You are not allowed to cancel this document.");
						}
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				if ($objTable->getudfvalue("u_payrefno")!="") {
					switch ($objTable->getudfvalue("u_payreftype")) {
						case "RS":
							$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
							if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_payrefno"))) {
								//if ($obju_HISIPs->docstatus!="CN" && $obju_HISLabTestRequests->docstatus!="Cancelled") {
									if ($obju_HISIPs->getudfvalue("u_dpamount")>0 && $obju_HISIPs->getudfvalue("u_dpno")==$objTable->docno) {
										//if (floatval($obju_HISIPs->getudfvalue("u_dpamount")) == floatval($objTable->getudfvalue("u_totalamount"))) {
											$obju_HISIPs->setudfvalue("u_dpno","");
											$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
										//} else $actionReturn = raiseError("Admission amount [".$obju_HISIPs->getudfvalue("u_dpamount")."] was modified, paid amount [".$objTable->getudfvalue("u_dueamount")."].");	
									} //else $actionReturn = raiseError("Admission must be unpaid.");
								//} else $actionReturn = raiseError("Admission Status already cancelled.");
							} else $actionReturn = raiseError("Unable to find Admission No.[".$objTable->getudfvalue("u_payrefno")."]");
							break;
						case "SI":
							break;
						case "CHRG":
							if ($objTable->getudfvalue("u_automanage")=="0") {
								$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
								if ($obju_HISRequests->getbykey($objTable->getudfvalue("u_payrefno"))) {
									if ($obju_HISRequests->docstatus=="O") {
										if ($obju_HISRequests->getudfvalue("u_prepaid")=="1" && $obju_HISRequests->getudfvalue("u_payrefno")==$objTable->docno) {
											if (bcsub($obju_HISRequests->getudfvalue("u_amount"), $objTable->getudfvalue("u_dueamount"),14)==0) {
												$obju_HISRequests->setudfvalue("u_payrefno","");
												$obju_HISRequests->setudfvalue("u_payreftype","");
												$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
											} else $actionReturn = raiseError("Request amount [".$obju_HISRequests->getudfvalue("u_amount")."] was modified, paid amount [".$objTable->getudfvalue("u_dueamount")."].");	
										} elseif ($obju_HISRequests->getudfvalue("u_prepaid")=="2" && $obju_HISRequests->getudfvalue("u_payrefno")==$objTable->docno) {
											if (floatval($obju_HISRequests->getudfvalue("u_amountbefdisc")) == floatval($objTable->getudfvalue("u_dueamount"))) {
												$obju_HISRequests->setudfvalue("u_payrefno","");
												$obju_HISRequests->setudfvalue("u_payreftype","");
												$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
											} else $actionReturn = raiseError("Request amount [".$obju_HISRequests->getudfvalue("u_amountbefdisc")."] was modified, paid amount [".$objTable->getudfvalue("u_dueamount")."].");	
										} else $actionReturn = raiseError("Request must be cash term and paid.");
									} else $actionReturn = raiseError("Request Status must be open.");
								} else $actionReturn = raiseError("Unable to find Request No.[".$objTable->getudfvalue("u_requestno")."]");
							}	
							break;
					}
				}	
				if ($actionReturn) {
					//if ($objTable->getudfvalue("u_trxtype")=="PHARMACY") {
					if ($objTable->getudfvalue("u_prepaid")=="1") {
						$actionReturn = onCustomEventdocumentschema_brPostCashSalesGPSHIS($objTable,true);
						if ($actionReturn && ($objTable->getudfvalue("u_colltype")=="Professional Fees" || $objTable->getudfvalue("u_colltype")=="Professional Materials")) {
							$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('pos',$objTable,true);
						}
						if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostCashSalesHealthBenefitsGPSHIS($objTable,true);
						if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostPOSCreditsGPSHIS($objTable,true);
					} else if ($objTable->getudfvalue("u_prepaid")=="3") {
						$actionReturn = onCustomEventdocumentschema_brPostPaymentGPSHIS($objTable,true);
					} else {
						$actionReturn = onCustomEventdocumentschema_brPostDPGPSHIS($objTable,true);
					}	
					
				}	
			}
			break;
		case "u_hisconsultancyrequests":
			if ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {
				if ($objTable->getudfvalue("u_prepaid")=="1" && $objTable->getudfvalue("u_payrefno")!="") {
					$actionReturn = onCustomEventdocumentschema_brPostCashSalesGPSHIS($objTable,true,true);
					if ($actionReturn) {
						$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('pos',$objTable,true);
					}
					if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brPostCashSalesHealthBenefitsGPSHIS($objTable,true);
				}
			}
			break;
		case "u_hismedrecs":
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPs->setudfvalue("u_icdcode",$objTable->getudfvalue("u_icdcode"));
						$obju_HISIPs->setudfvalue("u_icddesc",$objTable->getudfvalue("u_icddesc"));
						$obju_HISIPs->setudfvalue("u_casetype",$objTable->getudfvalue("u_casetype"));
						$obju_HISIPs->setudfvalue("u_rvscode",$objTable->getudfvalue("u_rvscode"));
						$obju_HISIPs->setudfvalue("u_rvsdesc",$objTable->getudfvalue("u_rvsdesc"));
						$obju_HISIPs->setudfvalue("u_rvu",$objTable->getudfvalue("u_rvu"));
						//$obju_HISIPs->setudfvalue("u_procedures",$objTable->getudfvalue("u_procedures"));
						$obju_HISIPs->setudfvalue("u_finaldoctorid",$objTable->getudfvalue("u_doctorid"));
						$obju_HISIPs->setudfvalue("u_finaldoctorservice",$objTable->getudfvalue("u_doctorservice"));
						$obju_HISIPs->setudfvalue("u_finalremarks",$objTable->getudfvalue("u_remarks"));
						$obju_HISIPs->setudfvalue("u_finalorproc",$objTable->getudfvalue("u_orproc"));
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPs->setudfvalue("u_icdcode",$objTable->getudfvalue("u_icdcode"));
						$obju_HISIPs->setudfvalue("u_icddesc",$objTable->getudfvalue("u_icddesc"));
						$obju_HISIPs->setudfvalue("u_casetype",$objTable->getudfvalue("u_casetype"));
						$obju_HISIPs->setudfvalue("u_rvscode",$objTable->getudfvalue("u_rvscode"));
						$obju_HISIPs->setudfvalue("u_rvsdesc",$objTable->getudfvalue("u_rvsdesc"));
						$obju_HISIPs->setudfvalue("u_rvu",$objTable->getudfvalue("u_rvu"));
						//$obju_HISIPs->setudfvalue("u_procedures",$objTable->getudfvalue("u_procedures"));
						$obju_HISIPs->setudfvalue("u_finaldoctorid",$objTable->getudfvalue("u_doctorid"));
						$obju_HISIPs->setudfvalue("u_finaldoctorservice",$objTable->getudfvalue("u_doctorservice"));
						$obju_HISIPs->setudfvalue("u_finalremarks",$objTable->getudfvalue("u_remarks"));
						$obju_HISIPs->setudfvalue("u_finalorproc",$objTable->getudfvalue("u_orproc"));
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}	
			}	
			if ($actionReturn) {
				$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
				if ($obju_HISBills->getbykey($objTable->getudfvalue("u_reftype") . "-" .$objTable->getudfvalue("u_refno"))) {
					$obju_HISBills->setudfvalue("u_icdcode",$objTable->getudfvalue("u_icdcode"));
					$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);	
				}
			}
			break;			
		case "u_hisbilltermsupds":
			if ($actionReturn) {
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					$obju_HISIPBillTermsUpds = new documentlinesschema_br(null,$objConnection,"u_hisipbilltermsupds");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPBillTermsUpds->prepareadd();
						$obju_HISIPBillTermsUpds->docid = $obju_HISIPs->docid;
						$obju_HISIPBillTermsUpds->lineid = getNextIdByBranch("u_hisipbilltermsupds",$objConnection);
						$obju_HISIPBillTermsUpds->setudfvalue("u_date",$objTable->getudfvalue("u_date"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_time",$objTable->getudfvalue("u_time"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_remarks",$objTable->getudfvalue("u_remarks"));
						//$obju_HISIPBillTermsUpds->setudfvalue("u_billtermno",$objTable->docno);
						$obju_HISIPBillTermsUpds->setudfvalue("u_billtermby",$_SESSION["userid"]);
						$obju_HISIPBillTermsUpds->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPBillTermsUpds->add();
						if ($actionReturn) {
							$obju_HISIPs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
							$obju_HISIPs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
							$obju_HISIPs->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
							$obju_HISIPs->setudfvalue("u_scdisc",$objTable->getudfvalue("u_scdisc"));
							$obju_HISIPs->setudfvalue("u_billremarks",$objTable->getudfvalue("u_remarks"));
							$obju_HISIPs->setudfvalue("u_billtermno",$objTable->docno);
							$obju_HISIPs->setudfvalue("u_billtermby",$_SESSION["userid"]);
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						}
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					$obju_HISIPBillTermsUpds = new documentlinesschema_br(null,$objConnection,"u_hisopbilltermsupds");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						//$obju_HISIPBillTermsUpds->setdebug();
						$obju_HISIPBillTermsUpds->prepareadd();
						$obju_HISIPBillTermsUpds->docid = $obju_HISIPs->docid;
						$obju_HISIPBillTermsUpds->lineid = getNextIdByBranch("u_hisopbilltermsupds",$objConnection);
						$obju_HISIPBillTermsUpds->setudfvalue("u_date",$objTable->getudfvalue("u_date"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_time",$objTable->getudfvalue("u_time"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
						$obju_HISIPBillTermsUpds->setudfvalue("u_remarks",$objTable->getudfvalue("u_remarks"));
						//$obju_HISIPBillTermsUpds->setudfvalue("u_billtermno",$objTable->docno);
						$obju_HISIPBillTermsUpds->setudfvalue("u_billtermby",$_SESSION["userid"]);
						$obju_HISIPBillTermsUpds->privatedata["header"] = $obju_HISIPs;
						$actionReturn = $obju_HISIPBillTermsUpds->add();
						//var_dump($obju_HISIPBillTermsUpds->sqls );
						if ($actionReturn) {
							$obju_HISIPs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
							$obju_HISIPs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
							$obju_HISIPs->setudfvalue("u_scdisc",$objTable->getudfvalue("u_scdisc"));
							$obju_HISIPs->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
							$obju_HISIPs->setudfvalue("u_billremarks",$objTable->getudfvalue("u_remarks"));
							$obju_HISIPs->setudfvalue("u_billtermno",$objTable->docno);
							$obju_HISIPs->setudfvalue("u_billtermby",$_SESSION["userid"]);
							$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						}
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}	
			}	
			break;						
		case "u_hismedsupprs":
			if ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				$actionReturn = onCustomEventdocumentschema_brStockPRGPSHIS($objTable);
			}	
			break;
		case "u_hisphicclaims":
			$actionReturn = onCustomEventUpdateHealthCareInsFromdocumentschema_brGPSHIS($objTable);
			if ($actionReturn) $actionReturn = onCustomEventUpdateHealthCareInsTodocumentschema_brGPSHIS($objTable);
			if ($actionReturn && $objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_postedby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brPostPHICCMGPSHIS($objTable);
			} elseif ($actionReturn && $objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brPostPHICCMGPSHIS($objTable,true);
			}		
			break;
		case "u_hispkgclaims":
			if ($actionReturn && $objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				if (isset($page)) {
					if ($page->getcolumnstring("T50",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T50",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T50",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T50",0,"userid","");
							$page->setcolumn("T50",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_postedby",$page->getcolumnstring("T50",0,"userid"));
						$page->setcolumn("T50",0,"userid","");
						$page->setcolumn("T50",0,"password","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brPostPKGCMGPSHIS($objTable);
			} elseif ($actionReturn && $objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
				if (isset($page)) {
					if ($page->getcolumnstring("T51",0,"userid")!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select username from users where isvalid=1 and userid='".$page->getcolumnstring("T51",0,"userid")."' and hpwd='".md5($page->getcolumnstring("T51",0,"password"))."'");
						if (!$objRs->queryfetchrow()) {
							$page->setcolumn("T51",0,"userid","");
							$page->setcolumn("T51",0,"password","");
							return raiseError("Invalid user or password.");
						}	
						$objTable->setudfvalue("u_cancelledby",$page->getcolumnstring("T51",0,"userid"));
						$objTable->setudfvalue("u_cancelledreason",$page->getcolumnstring("T51",0,"cancelreason"));
						$objTable->setudfvalue("u_cancelledremarks",$page->getcolumnstring("T51",0,"remarks"));
						$page->setcolumn("T51",0,"userid","");
						$page->setcolumn("T51",0,"password","");
						$page->setcolumn("T51",0,"cancelreason","");
						$page->setcolumn("T51",0,"remarks","");
					}	
				}		
				$actionReturn = onCustomEventdocumentschema_brPostPKGCMGPSHIS($objTable,true);
			}		
			break;
		case "u_hisardeductions":
			if ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				$actionReturn = onCustomEventdocumentschema_brARDeductionPRGPSHIS($objTable);
			}	
			break;
		case "u_hisappfs":
			if ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				$actionReturn = onCustomEventdocumentschema_brAPPfsPRGPSHIS($objTable);
			}	
			break;
		case "u_hishealthinpaycms":
			if ($objTable->docstatus=="O" && $objTable->fields["DOCSTATUS"]=="D") {
				$actionReturn = onCustomEventdocumentschema_brPostHealthInPayCMGPSHIS($objTable);
			} elseif ($objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]=="O") {	
				$actionReturn = onCustomEventdocumentschema_brPostHealthInPayCMGPSHIS($objTable,true);
			}
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSHIS");
	return $actionReturn;
}



function onUpdateEventdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisips":
			if ($objTable->getudfvalue("u_bedno") != $objTable->fields["U_BEDNO"]) {
				$actionReturn = onCustomEventdocumentschema_brRoomTransferFeesGPSHIS($objTable);
			}			
			break;
		case "u_hisinclaimxmtals":
			if ($actionReturn && $objTable->docstatus=="CN" && $objTable->fields["DOCSTATUS"]!=$objTable->docstatus) {
				$objRs = new recordset(null,$objConnection);
				$obju_HISInClaims = new documentschema_br(null,$objConnection,"u_hisinclaims");			
				$obju_HISPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");	
				$obju_HISPOS = new documentschema_br(null,$objConnection,"u_hispos");			
				$obju_HISPOSIns = new documentlinesschema_br(null,$objConnection,"u_hisposins");			
				$objRs->queryopen("select U_REFTYPE,U_REFNO,U_STATUS from u_hisinclaimxmtalitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
				while ($objRs->queryfetchrow("NAME")) {
					if ($objRs->fields["U_STATUS"]!="") return raiseError("One or more claims have been [".$objRs->fields["U_STATUS"]."]. You cannot cancel this transmittal.");
					switch ($objRs->fields["U_REFTYPE"]) {
						case "BILLINS":	
							if ($obju_HISInClaims->getbykey($objRs->fields["U_REFNO"])) {
								$obju_HISInClaims->setudfvalue("u_xmtalno","");
								$obju_HISInClaims->setudfvalue("u_xmtaldate","");
								$actionReturn = $obju_HISInClaims->update($obju_HISInClaims->docno,$obju_HISInClaims->rcdversion);				
							} else $actionReturn = raiseError("Unable to find Health Benefit document no.[".$objRs->fields["U_REFNO"]."]");
							break;
						case "BILLPNS":
							if ($obju_HISPronotes->getbykey($objRs->fields["U_REFNO"])) {
								$obju_HISPronotes->setudfvalue("u_xmtalno","");
								$obju_HISPronotes->setudfvalue("u_xmtaldate","");
								$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);				
							} else $actionReturn = raiseError("Unable to find DM/CM/PN document no.[".$objRs->fields["U_REFNO"]."]");
							break;
						case "POS":
							if ($obju_HISPOS->getbykey($objRs->fields["U_REFNO"])) {
								if ($obju_HISPOSIns->getbysql("DOCID='$obju_HISPOS->docid' AND U_INSCODE='".$objTable->getudfvalue("u_inscode")."'")) {
									$obju_HISPOSIns->setudfvalue("u_xmtalno","");
									$obju_HISPOSIns->setudfvalue("u_xmtaldate","");
									$actionReturn = $obju_HISPOSIns->update($obju_HISPOSIns->docid,$obju_HISPOSIns->lineid,$obju_HISPOSIns->rcdversion);
									if ($actionReturn) $actionReturn = $obju_HISPOS->update($obju_HISPOS->docno,$obju_HISPOS->rcdversion);
								} else return raiseError("Unable to find Cash Sales/Credit Memo No.[".$objRs->fields["U_REFNO"]."] Health Benefit [".$objTable->getudfvalue("u_inscode")."].");
							} else $actionReturn = raiseError("Unable to find Cash Sales/Credit Memo document no.[".$objRs->fields["U_REFNO"]."]");
							break;
					}
				}
				if (!$actionReturn) break;
			}	
			//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventdocumentschema_brGPSHIS");
			break;
		case "u_hischarges":
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable);
			break;	
		case "u_hiscredits":
			if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable);
			break;	
		case "u_hispos":
			if ($objTable->getudfvalue("u_prepaid")=="2" || $objTable->getudfvalue("u_prepaid")=="3") {
				if ($actionReturn) $actionReturn = onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable);
			}	
			//if ($actionReturn) $actionReturn = raiseError("onUpdateEventdocumentschema_brGPSHIS");
			break;	
	}
	//if ($actionReturn) $actionReturn = raiseError("onUpdateEventdocumentschema_brGPSHIS");
	return $actionReturn;
}


function onBeforeDeleteEventdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hisbills":
			if ($objTable->docstatus=="O") {
				//return raiseError("Bill cannot be remove.");
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPs->setudfvalue("u_billno","");
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				} else {
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
					$obju_HISIPMiscs = new documentlinesschema_br(null,$objConnection,"u_hisopmiscs");
					if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
						$obju_HISIPs->setudfvalue("u_billno","");
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
				}	
			}	
			break;		
	}
	return $actionReturn;
}


/*
function onDeleteEventdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_hislabtests":
			break;
	}
	return $actionReturn;
}
*/
function onCustomEventdocumentschema_brPostDeliveryGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$customerdata = getcustomerdata($objTable->getudfvalue("u_patientid"),"CUSTNO,CUSTNAME,PAYMENTTERM");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$objTable->getudfvalue("u_patientid")."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	$objRsManageBy = new recordset(NULL,$objConnection);
	$objMarketingDocuments = new marketingdocuments(null,$objConnection,"SALESDELIVERIES");
	$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"SALESDELIVERYITEMS");
	
	$settings = getBusinessObjectSettings("SALESDELIVERY");
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	$objMarketingDocuments->objectcode = "SALESDELIVERY";
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];
	$objMarketingDocuments->docid = getNextIdByBranch("salesdeliveries",$objConnection);

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $objTable->getudfvalue("u_patientid");
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_refno");
	$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Delivery. " . $objTable->getudfvalue("u_remarks");
	$objMarketingDocuments->journalremark = "Delivery - " . $objMarketingDocuments->bpcode;
	$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];

	if ($actionReturn) {
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_PRICE, a.U_QUANTITY, a.U_LINETOTAL  from u_hismedsupitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
		while ($objRsItems->queryfetchrow("NAME")) {
			$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			$objMarketingDocumentItems->lineid = getNextIdByBranch("salesdeliveryitems",$objConnection);
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
			$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->price - $objMarketingDocumentItems->unitprice;
			$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
			$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
			$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
			$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
			$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->whscode = $objTable->getudfvalue("u_department");
			$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
			$objMarketingDocumentItems->linestatus = "O";
			if ($objMarketingDocumentItems->itemmanageby=="1") {
				$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
				$sbqty2 = $sbqty;
				$batchstatus = "";
				$batchnos = "";
				$batchqtys = "";
				$batchmfgs = "";
				$batchexpiries = "";
				$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
				while ($objRsManageBy->queryfetchrow("NAME")) {
					if ($objRsManageBy->fields["QTY"]==0) continue;
					if ($batchstatus!="") {
						$batchstatus .= "`";
						$batchnos .= "`";
						$batchqtys .= "`";
						$batchmfgs .= "`";
						$batchexpiries .= "`";
					}
					$batchstatus .= "1";
					$batchnos .= $objRsManageBy->fields["BATCH"];
					if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
						$batchqtys .= $objRsManageBy->fields["QTY"];
						$sbqty2 -= $objRsManageBy->fields["QTY"];
					} else {
						$batchqtys .= $sbqty2;
						$sbqty2 = 0;
					}	
					//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
					$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
					
					if ($sbqty2<=0) break;
				}
				if ($sbqty2!=0) {
					$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
				} else {
					$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
					//. "|u_mfgdate|" . $batchmfgs 
					$objMarketingDocumentItems->sbncnt = $sbqty;
				}	
			}
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
		$objMarketingDocuments->docstatus = "C";
		$actionReturn = $objMarketingDocuments->add();
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostDeliveryGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostMedSupReturnGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$customerdata = getcustomerdata($objTable->getudfvalue("u_patientid"),"CUSTNO,CUSTNAME,PAYMENTTERM");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$objTable->getudfvalue("u_patientid")."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	$objRsManageBy = new recordset(NULL,$objConnection);
	if ($objTable->getudfvalue("u_prepaid")=="0") {
		$tblhdr = "SALESRETURNS";
		$tbldtl = "SALESRETURNITEMS";
		$objcode = "SALESRETURN";
		$docstatus = "C";
	} else {
		$tblhdr = "ARCREDITMEMOS";
		$tbldtl = "ARCREDITMEMOITEMS";
		$objcode = "ARCREDITMEMO";
		$docstatus = "O";
	}
	if ($objTable->getudfvalue("u_stocklink")=="1") {
		$whscode = $objTable->getudfvalue("u_department");
		$dropship = 0;
	} else {
		$whscode = "DROPSHIP";
		$dropship = 1;
	}	
	
	$objMarketingDocuments = new marketingdocuments(null,$objConnection,$tblhdr);
	$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,$tbldtl);
	
	$settings = getBusinessObjectSettings($objcode);
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	$objMarketingDocuments->objectcode = $objcode;
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];
	$objMarketingDocuments->docid = getNextIdByBranch($tblhdr,$objConnection);

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $objTable->getudfvalue("u_patientid");
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_issueno");
	$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Returns/Credits. " . $objTable->getudfvalue("u_remarks");
	$objMarketingDocuments->journalremark = "Returns/Credits - " . $objMarketingDocuments->bpcode;
	$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];

	if ($actionReturn) {
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_PRICE, a.U_QUANTITY, a.U_LINETOTAL  from u_hismedsuprtitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
		while ($objRsItems->queryfetchrow("NAME")) {
			$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			$objMarketingDocumentItems->lineid = getNextIdByBranch($tbldtl,$objConnection);
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
			$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->price - $objMarketingDocumentItems->unitprice;
			$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
			$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
			$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
			$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
			$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->whscode = $whscode;
			$objMarketingDocumentItems->dropship = $dropship;
			$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
			$objMarketingDocumentItems->linestatus = "O";
			if ($whscode != "DROPSHIP") {
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					$sbqty2 = $sbqty;
					$batchstatus = "";
					$batchnos = "";
					$batchqtys = "";
					$batchmfgs = "";
					$batchexpiries = "";
					$objRsManageBy->queryopen("select BATCH, sum(quantity) as QTY, U_EXPDATE from (
	select db.itemcode, db.batch, db.quantity, db.u_expdate from salesdeliveries sd
	  inner join salesdeliveryitems sdi on sdi.company=sd.company and sdi.branch=sd.branch and sdi.docid=sd.docid and sdi.itemcode='$objMarketingDocumentItems->itemcode' and sdi.whscode='$objMarketingDocumentItems->whscode'
	  inner join documentbatches db on db.company=sd.company and db.branch=sd.branch and db.objectcode=sd.objectcode and db.docid=sd.docid and db.lineid=sdi.lineid
	  where sd.company='".$_SESSION["company"]."' and sd.branch='".$_SESSION["branch"]."' and sd.docno='".$objTable->getudfvalue("u_issueno")."'
		union all 
	select db.itemcode, db.batch, (db.quantity*-1) as quantity, db.u_expdate from salesreturns sd
	  inner join salesreturnitems sdi on sdi.company=sd.company and sdi.branch=sd.branch and sdi.docid=sd.docid and sdi.itemcode='$objMarketingDocumentItems->itemcode' and sdi.whscode='$objMarketingDocumentItems->whscode'
	  inner join documentbatches db on db.company=sd.company and db.branch=sd.branch and db.objectcode=sd.objectcode and db.docid=sd.docid and db.lineid=sdi.lineid
	  where sd.company='".$_SESSION["company"]."' and sd.branch='".$_SESSION["branch"]."' and sd.bprefno='".$objTable->getudfvalue("u_issueno")."'
		union all 
	select db.itemcode, db.batch, (db.quantity*-1) as quantity, db.u_expdate from arcreditmemos sd
	  inner join arcreditmemoitems sdi on sdi.company=sd.company and sdi.branch=sd.branch and sdi.docid=sd.docid and sdi.itemcode='$objMarketingDocumentItems->itemcode' and sdi.whscode='$objMarketingDocumentItems->whscode'
	  inner join documentbatches db on db.company=sd.company and db.branch=sd.branch and db.objectcode=sd.objectcode and db.docid=sd.docid and db.lineid=sdi.lineid
	  where sd.company='".$_SESSION["company"]."' and sd.branch='".$_SESSION["branch"]."' and sd.bprefno='".$objTable->getudfvalue("u_issueno")."'
	 ) as x group by batch");
					while ($objRsManageBy->queryfetchrow("NAME")) {
						if ($batchstatus!="") {
							$batchstatus .= "`";
							$batchnos .= "`";
							$batchqtys .= "`";
							$batchmfgs .= "`";
							$batchexpiries .= "`";
						}
						$batchstatus .= "1";
						$batchnos .= $objRsManageBy->fields["BATCH"];
						if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
							$batchqtys .= $objRsManageBy->fields["QTY"];
							$sbqty2 -= $objRsManageBy->fields["QTY"];
						} else {
							$batchqtys .= $sbqty2;
							$sbqty2 = 0;
						}	
						//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
						$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
						
						if ($sbqty2<=0) break;
					}
					if ($sbqty2!=0) {
						$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
					} else {
						$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
						//. "|u_mfgdate|" . $batchmfgs 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					}	
				}
			}	
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
		$objMarketingDocuments->docstatus = $docstatus;
		$actionReturn = $objMarketingDocuments->add();
	}
	if ($docstatus=="O" && $actionReturn) {
		$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
		$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
		$bpdata["balance"] = $objMarketingDocuments->dueamount*-1;
		$actionReturn = updatecustomerbalance($bpdata);
	}		
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostDeliveryGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostDPGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objCollections = new collections(null,$objConnection) ;
	$objCollectionsCheques = new collectionscheques(null,$objConnection) ;
	$objCollectionsCreditCards = new collectionscreditcards(null,$objConnection) ;
	$objCollectionsCashCards = new collectionscashcards(null,$objConnection) ;
	$objCollectionsInvoices = new collectionsinvoices(null,$objConnection) ;
	$objRs = new recordset(null,$objConnection) ;

	if ($objTable->getudfvalue("u_reftype")=="IP") {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
	} elseif ($objTable->getudfvalue("u_reftype")=="OP") {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
		$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
	}	

	$custno = $objTable->getudfvalue("u_patientid");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$settings = getBusinessObjectSettings("INCOMINGPAYMENT");
	if ($delete) {
		if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				if ($obju_HISIPMedSups->getbysql("DOCID='".$obju_HISIPs->docid."' AND U_DOCTYPE='DP' AND U_DOCNO='".$objTable->docno."'")) {
					$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
					$obju_HISIPMedSups->setudfvalue("u_balance",0);
					$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
					$actionReturn = $obju_HISIPMedSups->update($obju_HISIPMedSups->docid,$obju_HISIPMedSups->lineid,$obju_HISIPMedSups->rcdversion);
					/*if ($actionReturn) {
						$obju_HISIPs->setudfvalue("u_totalcharges",$obju_HISIPs->getudfvalue("u_totalcharges")+$objTable->getudfvalue("u_dueamount"));
						$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit")-$obju_HISIPs->getudfvalue("u_totalcharges"));
						$obju_HISIPs->setudfvalue("u_availablecreditperc",100-(($obju_HISIPs->getudfvalue("u_totalcharges")-$obju_HISIPs->getudfvalue("u_totalcharges"))*100));
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
					}*/
				} else $actionReturn = raiseError("Unable to find Transaction No.[".$objTable->docno."] in Reference No.[".$objTable->getudfvalue("u_refno")."]");	
			} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
		}	
			
	
		if ($actionReturn) {
			if ($objCollections->getbykey($_SESSION["branch"], $objTable->docno)) {
				if ($objCollections->collfor=="DP") {
					if ($actionReturn) {
						$bpdata = array();
						$bpdata["bpcode"] = $objCollections->bpcode;
						$bpdata["parentbpcode"] = "";
						$bpdata["parentbptype"] = "P";
						$bpdata["balance"] = $objCollections->paidamount * -1;
						$actionReturn = onCustomEventcollectionsUpdateCustomerBalances($bpdata);
					}	
				}
				if ($actionReturn) {
					$objCollections->cleared = -99;
					$objCollections->cancelleddate = $objCollections->docdate;
					$objCollections->cancelledby = $_SESSION["userid"];
					$objCollections->docstat = 'CN';
					if ($actionReturn) $actionReturn = $objCollections->update($objCollections->branchcode,$objCollections->docno,$objCollections->rcdversion);
				}		
				//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostDPGPSHIS");
				return $actionReturn;
			} else return raiseError("Unable to Find Collection Record.");
		}	
	} else {
		if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
			if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
				$obju_HISIPMedSups->prepareadd();
				$obju_HISIPMedSups->docid = $obju_HISIPs->docid;
				if ($objTable->getudfvalue("u_reftype")=="IP") {
					$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
				} else {
					$obju_HISIPMedSups->lineid = getNextIdByBranch("u_hisoptrxs",$objConnection);
				}	
				$obju_HISIPMedSups->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
				$obju_HISIPMedSups->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
				//$obju_HISIPMedSups->setudfvalue("u_doctime",$objTable->getudfvalue("u_starttime"));
				$obju_HISIPMedSups->setudfvalue("u_docstatus",$objTable->docstatus);
				if ($objTable->getudfvalue("u_payrefno")!="") {
					$obju_HISIPMedSups->setudfvalue("u_reftype","CHRG");
					$obju_HISIPMedSups->setudfvalue("u_refno",$objTable->getudfvalue("u_payrefno"));
				}	
				$obju_HISIPMedSups->setudfvalue("u_docdesc","Partial Payment");
				$obju_HISIPMedSups->setudfvalue("u_docno",$objTable->docno);
				$obju_HISIPMedSups->setudfvalue("u_doctype","DP");
				
				$obju_HISIPMedSups->setudfvalue("u_amount",$objTable->getudfvalue("u_dueamount")*-1);
				$obju_HISIPMedSups->setudfvalue("u_balance",$objTable->getudfvalue("u_dueamount")*-1);
				$obju_HISIPMedSups->privatedata["header"] = $obju_HISIPs;
				$actionReturn = $obju_HISIPMedSups->add();
				/*
				if ($actionReturn) {
					$obju_HISIPs->setudfvalue("u_totalcharges",$obju_HISIPs->getudfvalue("u_totalcharges")-$objTable->getudfvalue("u_dueamount"));
					$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit")-$obju_HISIPs->getudfvalue("u_totalcharges"));
					$obju_HISIPs->setudfvalue("u_availablecreditperc",100-(($obju_HISIPs->getudfvalue("u_totalcharges")-$obju_HISIPs->getudfvalue("u_totalcharges"))*100));
					$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
				}*/
			} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
		}	
	}	
	$objCollections->docdate = $objTable->getudfvalue("u_docdate");
	$objCollections->bpcode = $custno;
	$objCollections->bpname = $customerdata["CUSTNAME"];
	$objCollections->paidamount = $objTable->getudfvalue("u_dueamount");
	$objCollections->docno = $objTable->docno;
	$objCollections->othertrxtype = $objTable->getudfvalue("u_colltype");
	$objTable->setudfvalue("u_ardocno",$objCollections->docno);
	$objCollections->setudfvalue("u_cashierid",$_SESSION["userid"]);
	$objCollections->setudfvalue("u_doctime",$objTable->getudfvalue("u_doctime"));
	$objCollections->setudfvalue("u_colltype",$objTable->getudfvalue("u_colltype"));
	if ($actionReturn) $actionReturn = isPostingDateValid($objCollections->docdate,$objCollections->docduedate,$objCollections->taxdate);
	if ($actionReturn) {
		$objCollections->docid = getNextIdByBranch('collections',$objConnection);
		$objCollections->sbo_post_flag = $settings["autopost"];
		$objCollections->jeposting = $settings["jeposting"];
		$objCollections->objectcode = "INCOMINGPAYMENT";
		$objCollections->changeamount = 0;
		$objCollections->doctype = "C";
		if ($objTable->getudfvalue("u_payreftype")!="SI") {
			$objCollections->collfor = "DP";
			$objCollections->balanceamount = $objCollections->paidamount;
			$objCollections->dueamount = 0;
		} else {
			$objCollections->collfor = "SI";
			$objCollections->balanceamount = 0;
			$objCollections->dueamount = $objCollections->paidamount;
		}	
		$objCollections->bpcurrency = $customerdata["CURRENCY"];
		$objCollections->currency = $customerdata["CURRENCY"];
		$objCollections->docduedate = $objCollections->docdate;
		$objCollections->taxdate = $objCollections->docdate;
		$objCollections->branchcode = $_SESSION["branch"];
		$objCollections->colltype = "CSH";
		if ($objTable->getudfvalue("u_recvamount")>0) {
			$objCollections->cashacct = -1;
			$objCollections->collectedamount = $objCollections->paidamount;
			$objCollections->cashamount = $objTable->getudfvalue("u_recvamount") - $objTable->getudfvalue("u_chngamount");
		}
		if ($objTable->getudfvalue("u_checkamount")>0) {
			$objCollections->chequeacct = -2;
			$objCollections->chequeamount = $objTable->getudfvalue("u_checkamount");
		}
		if ($objTable->getudfvalue("u_creditcardamount")>0) {
			$objCollections->creditcardamount = $objTable->getudfvalue("u_creditcardamount");
		}
		if ($objTable->getudfvalue("u_creditamount")>0) {
			$objCollections->otheramount = $objTable->getudfvalue("u_creditamount");
		}
		//$objCollections->acctbranch = $_SESSION["branch"];
		$objCollections->valuedate = $objCollections->docdate;
		$objCollections->cleared = 1;
		/*(if ($objCollections->docseries!=-1) {
			$objCollections->docno = getNextSeriesNoByBranch("INCOMINGPAYMENT",$objCollections->docseries,$objCollections->docdate,$objConnection,true);
			
		}*/	
		$objCollections->postdate = $objCollections->docdate ." ". date('H:i:s');
		$objCollections->journalremark = "Incoming Payment - " . $objCollections->bpcode;
		
		if ($actionReturn && $objCollections->collfor == "SI") {
			$objCollectionsInvoices->prepareadd();
			$objCollectionsInvoices->docid = getNextIdByBranch('collectionsinvoices',$objConnection);
			$objCollectionsInvoices->docno = $objCollections->docno;
			$objCollectionsInvoices->reftype = "ARINVOICE";
			$objCollectionsInvoices->refbranch = $objCollections->branchcode;
			$objCollectionsInvoices->refno = $objTable->getudfvalue("u_payrefno");
			$objCollectionsInvoices->bpcode = $objCollections->bpcode;
			$objCollectionsInvoices->bpname = $objCollections->bpname;
			$objCollectionsInvoices->currency = $objCollections->currency;
			$objCollectionsInvoices->objectcode = $objCollections->objectcode;
			$objCollectionsInvoices->privatedata["header"] = $objCollections;
			$objCollectionsInvoices->amount = $objCollections->paidamount;
			$objCollectionsInvoices->balanceamount = $objCollectionsInvoices->amount;
			if ($actionReturn) $actionReturn = $objCollectionsInvoices->add();
		}
								
		if ($actionReturn && $objTable->getudfvalue("u_checkamount")>0) {
			$objCollectionsCheques->prepareadd();
			$objCollectionsCheques->docid = getNextIdByBranch('collectionscheques',$objConnection);
			$objCollectionsCheques->docno = $objCollections->docno;
			$objCollectionsCheques->objectcode = $objCollections->objectcode;
			$objCollectionsCheques->checkdate = $objCollections->docdate;
			$objCollectionsCheques->checkno = $objTable->getudfvalue("u_checkno");
			$objCollectionsCheques->bank = $objTable->getudfvalue("u_bank");
			$objCollectionsCheques->bankbranch = "";
			$objCollectionsCheques->amount = $objTable->getudfvalue("u_checkamount");
			$objCollectionsCheques->privatedata["header"] = $objCollections;
			if ($actionReturn) $actionReturn = $objCollectionsCheques->add();
		}

		if ($actionReturn && $objTable->getudfvalue("u_creditcardamount")>0) {
			$objRs->queryopen("select * from u_hisposcreditcards where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'"); 
			while ($objRs->queryfetchrow("NAME")) {
				$objCollectionsCreditCards->docid = getNextIdByBranch('collectionscreditcards',$objConnection);
				$objCollectionsCreditCards->docno = $objCollections->docno;
				$objCollectionsCreditCards->objectcode = $objCollections->objectcode;
				$objCollectionsCreditCards->creditcard = $objRs->fields["U_CREDITCARD"];
				$objCollectionsCreditCards->creditcardno = $objRs->fields["U_CREDITCARDNO"];
				$objCollectionsCreditCards->creditcardname = $objRs->fields["U_CREDITCARDNAME"];
				$objCollectionsCreditCards->cardexpiretext = $objRs->fields["U_EXPIREDATE"];
				$objCollectionsCreditCards->cardexpiredate = getmonthendDB("20" . substr($objCollectionsCreditCards->cardexpiretext, 3, 2) . "-" . substr($objCollectionsCreditCards->cardexpiretext, 0, 2) . "-01");
				$objCollectionsCreditCards->approvalno = $objRs->fields["U_APPROVALNO"];
				$objCollectionsCreditCards->amount = $objRs->fields["U_AMOUNT"];
				$objCollectionsCreditCards->privatedata["header"] = $objCollections;
				$actionReturn = $objCollectionsCreditCards->add();				
				if (!$actionReturn) break;
			}
		}
		
		if ($actionReturn && $objTable->getudfvalue("u_creditamount")>0) {
			$objRs->queryopen("select u_posarcashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Sales - A/R");
			
			$objCollectionsCashCards->docid = getNextIdByBranch("collectionscashcards",$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objRs->fields["u_posarcashcard"];
			$objCollectionsCashCards->refno = "-"; 
			$objCollectionsCashCards->amount = $objTable->getudfvalue("u_creditamount");
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			$actionReturn = $objCollectionsCashCards->add();
		}
		/*
		if ($objCollections->colltype=="CPN") {
			$cashcarddata = getcashcarddata($objCollections->userfields["u_cashcard"]["value"],"LINKTODOCNO");
			$objCollectionsCashCards->prepareadd();
			$objCollectionsCashCards->docid = getNextIdByBranch('collectionscashcards',$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objCollections->userfields["u_cashcard"]["value"];
			$objCollectionsCashCards->linktodocno = $cashcarddata["LINKTODOCNO"];
			if ($objCollectionsCashCards->linktodocno=="1") {
				$objCollectionsCashCards->linkdocno = $objCollections->acctno;
			}
			$objCollectionsCashCards->refbranch = $objCollections->acctbranch;
			$objCollectionsCashCards->refno = "-";
			$objCollectionsCashCards->amount = $objCollections->paidamount;
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			if ($actionReturn) $actionReturn = $objCollectionsCashCards->add();
		}
		*/
		if ($actionReturn) $actionReturn = $objCollections->add();
	}		
	
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostPaymentGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objCollections = new collections(null,$objConnection) ;
	$objCollectionsCheques = new collectionscheques(null,$objConnection) ;
	$objCollectionsCreditCards = new collectionscreditcards(null,$objConnection) ;
	$objCollectionsCashCards = new collectionscashcards(null,$objConnection) ;
	$objCollectionsInvoices = new collectionsinvoices(null,$objConnection) ;
	$objRs = new recordset(null,$objConnection) ;
	$objRs2 = new recordset(null,$objConnection) ;

	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISCNBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISPOs = new documentschema_br(null,$objConnection,"u_hispos");
	$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");
	$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
	$obju_HISPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
	$guarantorcode = "";

	if ($obju_HISBills->getbykey($objTable->getudfvalue("u_payrefno"))){
		if ($obju_HISBills->docstatus=="CN") return raiseError("You cannot cancel this receipt. Bill was already cancelled.");
		$objRs->queryopen("select * from u_hisposcredits where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
		$overstayingcharges=0;
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["U_REFTYPE"]=="DP") {
				if ($obju_HISPOs->getbykey($objRs->fields["U_REFNO"])) {
					if (!$delete) {
						$obju_HISPOs->setudfvalue("u_credited",$obju_HISPOs->getudfvalue("u_credited")-$objRs->fields["U_CREDITED"]);
						$obju_HISPOs->setudfvalue("u_balance",$obju_HISPOs->getudfvalue("u_balance")+$objRs->fields["U_CREDITED"]);
					} else {
						$obju_HISPOs->setudfvalue("u_credited",$obju_HISPOs->getudfvalue("u_credited")+$objRs->fields["U_CREDITED"]);
						$obju_HISPOs->setudfvalue("u_balance",$obju_HISPOs->getudfvalue("u_balance")-$objRs->fields["U_CREDITED"]);
					}	
					$actionReturn = $obju_HISPOs->update($obju_HISPOs->docno,$obju_HISPOs->rcdversion);
				} else return raiseError("Unable to find ".$objRs->fields["U_REFTYPE"]." [".$objRs->fields["U_REFNO"]."]");
			} elseif ($objRs->fields["U_REFTYPE"]=="CM") {
				if ($obju_HISCredits->getbykey($objRs->fields["U_REFNO"])) {
					if (!$delete) {
						$obju_HISCredits->setudfvalue("u_credited",$obju_HISPOs->getudfvalue("u_credited")-$objRs->fields["U_CREDITED"]);
						$obju_HISCredits->setudfvalue("u_balance",$obju_HISPOs->getudfvalue("u_balance")+$objRs->fields["U_CREDITED"]);
					} else {
						$obju_HISCredits->setudfvalue("u_credited",$obju_HISPOs->getudfvalue("u_credited")+$objRs->fields["U_CREDITED"]);
						$obju_HISCredits->setudfvalue("u_balance",$obju_HISPOs->getudfvalue("u_balance")-$objRs->fields["U_CREDITED"]);
					}	
					$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
				} else return raiseError("Unable to find ".$objRs->fields["U_REFTYPE"]." [".$objRs->fields["U_REFNO"]."]");
			} elseif ($objRs->fields["U_REFTYPE"]=="CHRG") {
				$overstayingcharges+=$objRs->fields["U_CREDITED"];
				if ($obju_HISCharges->getbykey($objRs->fields["U_REFNO"])) {
					if ($obju_HISCharges->getudfvalue("u_amount")<=$objRs->fields["U_CREDITED"]) {
						if (!$delete) {
							$obju_HISCharges->setudfvalue("u_billno",$objTable->docno);
							if ($obju_HISCharges->docstatus!="CN") $obju_HISCharges->docstatus = "C";
						} else {
							$obju_HISCharges->setudfvalue("u_billno","");
							if ($obju_HISCharges->docstatus!="CN") $obju_HISCharges->docstatus = "O";
						}	
						$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
					} else return raiseError("Cannot partialy settle after bill charges [".$obju_HISCharges->getudfvalue("u_amount")."] promisory note amount [".$objRs->fields["U_CREDITED"]."].");	
				} else return raiseError("Unable to find ".$objRs->fields["U_REFTYPE"]." [".$objRs->fields["U_REFNO"]."]");
			} elseif ($objRs->fields["U_REFTYPE"]=="PN") {
				if ($obju_HISPronotes->getbykey($objRs->fields["U_REFNO"])) {
					$guarantorcode = $obju_HISPronotes->getudfvalue("u_guarantorcode");
					if (!$delete) {
						$obju_HISPronotes->setudfvalue("u_paidamount",$obju_HISPronotes->getudfvalue("u_paidamount")+$objRs->fields["U_CREDITED"]);
					} else {
						$obju_HISPronotes->setudfvalue("u_paidamount",$obju_HISPronotes->getudfvalue("u_paidamount")-$objRs->fields["U_CREDITED"]);
					}	
					$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
				} else return raiseError("Unable to find ".$objRs->fields["U_REFTYPE"]." [".$objRs->fields["U_REFNO"]."]");
			} else {
				if ($objRs->fields["U_BILLNO"]=="") {
					if ($obju_HISBillFees->getbysql("DOCID='$obju_HISBills->docid' AND U_FEETYPE='".$objRs->fields["U_REFTYPE"]."' AND U_DOCTORID='".$objRs->fields["U_DOCTORID"]."'")) {
						if (!$delete) $obju_HISBillFees->setudfvalue("u_paidamount",$obju_HISBillFees->getudfvalue("u_paidamount")+$objRs->fields["U_CREDITED"]);
						else $obju_HISBillFees->setudfvalue("u_paidamount",$obju_HISBillFees->getudfvalue("u_paidamount")-$objRs->fields["U_CREDITED"]);
						$obju_HISBillFees->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
					} else return raiseError("Unable to find Bill No.[".$objTable->getudfvalue("u_payrefno")."] Fees [".$objRs->fields["U_REFTYPE"]."/".$objRs->fields["U_DOCTORID"]."]");
				} else {	// cancelled bill allocation
					if ($obju_HISCNBills->getbykey($objRs->fields["U_BILLNO"])){
						if ($obju_HISBillFees->getbysql("DOCID='$obju_HISCNBills->docid' AND U_FEETYPE='".$objRs->fields["U_REFTYPE"]."' AND U_DOCTORID='".$objRs->fields["U_DOCTORID"]."'")) {
							if (!$delete) {
								$obju_HISBillFees->setudfvalue("u_paidamount",$obju_HISBillFees->getudfvalue("u_paidamount")+$objRs->fields["U_CREDITED"]);
								$obju_HISCNBills->setudfvalue("u_paidamount",$obju_HISCNBills->getudfvalue("u_paidamount")+$objRs->fields["U_CREDITED"]);
							} else {
								$obju_HISBillFees->setudfvalue("u_paidamount",$obju_HISBillFees->getudfvalue("u_paidamount")-$objRs->fields["U_CREDITED"]);
								$obju_HISCNBills->setudfvalue("u_paidamount",$obju_HISCNBills->getudfvalue("u_paidamount")-$objRs->fields["U_CREDITED"]);
							}	
							/*if ($obju_HISBillFees->getudfvalue("u_paidamount")<0) {
								return raiseError("Cancelled Bill [".$objRs->fields["U_BILLNO"]."] Fees [".$objRs->fields["U_REFTYPE"]."/".$objRs->fields["U_DOCTORID"]."] cannot have negative [".$obju_HISBillFees->getudfvalue("u_paidamount")."] value.");
							}*/
							$obju_HISBillFees->privatedata["header"] = $obju_HISCNBills;
							$actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
							if ($actionReturn) $actionReturn = $obju_HISCNBills->update($obju_HISCNBills->docno,$obju_HISCNBills->rcdversion);
						} else return raiseError("Unable to find Bill No.[".$objTable->getudfvalue("u_payrefno")."] Fees [".$objRs->fields["U_REFTYPE"]."/".$objRs->fields["U_DOCTORID"]."]");
					} else return raiseError("Unable to find Cancelled Bill No.[".$objRs->fields["U_BILLNO"]."]");
				}	
			}	
			if (!$actionReturn) break;
		}
		if ($obju_HISBills->getbykey($objTable->getudfvalue("u_payrefno"))){
			if ($objTable->getudfvalue("u_payreftype")=="PN") {
			} else {
				if (!$delete) {
					if ($obju_HISBills->docstatus!="CN") {
						$obju_HISBills->setudfvalue("u_paidamount",$obju_HISBills->getudfvalue("u_paidamount")+($objTable->getudfvalue("u_totalamount")-$overstayingcharges));
						if ($obju_HISBills->getudfvalue("u_dueamount")-$obju_HISBills->getudfvalue("u_paidamount")==0) $obju_HISBills->docstatus ="C";
						else $obju_HISBills->docstatus ="O";
					} //else $actionReturn = raiseError("Bill Status must be opened.");
				} else {
					if ($obju_HISBills->docstatus!="CN") {
						$obju_HISBills->setudfvalue("u_paidamount",$obju_HISBills->getudfvalue("u_paidamount")-($objTable->getudfvalue("u_totalamount")-$overstayingcharges));
						if ($obju_HISBills->getudfvalue("u_dueamount")-$obju_HISBills->getudfvalue("u_paidamount")==0) $obju_HISBills->docstatus ="C";
						else $obju_HISBills->docstatus ="O";
					}	
				}
			}	
			if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
		}
	} else return raiseError("Unable to find Bill No.[".$objTable->getudfvalue("u_payrefno")."]");
	//if ($actionReturn) return raiseError("rey");
	if (!$actionReturn) return false;

	

	if ($objTable->getudfvalue("u_reftype")=="IP") {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
	} elseif ($objTable->getudfvalue("u_reftype")=="OP") {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
		$obju_HISIPMedSups = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
	}	
	
	
	if ($objTable->getudfvalue("u_payreftype")=="PN") {
		$custno = $guarantorcode;
	} else {
		
		$custno = $objTable->getudfvalue("u_patientid");
		if ($custno=="") {
			$branchdata = getcurrentbranchdata("POSCUSTNO");
			$custno = $branchdata["POSCUSTNO"];
		}	
	}
		
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$settings = getBusinessObjectSettings("INCOMINGPAYMENT");
	if ($delete) {
		if ($actionReturn) {
			if ($objCollections->getbykey($_SESSION["branch"], $objTable->docno)) {
				if ($actionReturn) {
					$objCollections->cleared = -99;
					$objCollections->cancelleddate = $objCollections->docdate;
					$objCollections->cancelledby = $_SESSION["userid"];
					$objCollections->docstat = 'CN';
					if ($actionReturn) $actionReturn = $objCollections->update($objCollections->branchcode,$objCollections->docno,$objCollections->rcdversion);
				}		
				//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostDPGPSHIS");
				return $actionReturn;
			} else return raiseError("Unable to Find Collection Record.");
		}	
	}	
	$objCollections->docdate = $objTable->getudfvalue("u_docdate");
	$objCollections->bpcode = $custno;
	$objCollections->bpname = $objTable->getudfvalue("u_patientname");
	if ($objCollections->bpname=="") $objCollections->bpname = $customerdata["CUSTNAME"];
	$objCollections->paidamount = $objTable->getudfvalue("u_dueamount");
	$objCollections->docno = $objTable->docno;
	$objCollections->refno = $objTable->getudfvalue("u_payrefno");
	$objCollections->othertrxtype = $objTable->getudfvalue("u_colltype");
	$objTable->setudfvalue("u_ardocno",$objCollections->docno);
	$objCollections->setudfvalue("u_cashierid",$_SESSION["userid"]);
	$objCollections->setudfvalue("u_doctime",$objTable->getudfvalue("u_doctime"));
	$objCollections->setudfvalue("u_colltype",$objTable->getudfvalue("u_colltype"));
	if ($actionReturn) $actionReturn = isPostingDateValid($objCollections->docdate,$objCollections->docduedate,$objCollections->taxdate);
	if ($actionReturn) {
		$objCollections->docid = getNextIdByBranch('collections',$objConnection);
		$objCollections->sbo_post_flag = $settings["autopost"];
		$objCollections->jeposting = $settings["jeposting"];
		$objCollections->objectcode = "INCOMINGPAYMENT";
		$objCollections->changeamount = 0;
		$objCollections->doctype = "C";
		$objCollections->collfor = "SI";
		$objCollections->balanceamount = 0;
		$objCollections->dueamount = $objCollections->paidamount;

		$objCollections->bpcurrency = $customerdata["CURRENCY"];
		$objCollections->currency = $customerdata["CURRENCY"];
		$objCollections->docduedate = $objCollections->docdate;
		$objCollections->taxdate = $objCollections->docdate;
		$objCollections->branchcode = $_SESSION["branch"];
		$objCollections->colltype = "CSH";
		if ($objTable->getudfvalue("u_recvamount")>0) {
			$objCollections->cashacct = -1;
			$objCollections->collectedamount = $objCollections->paidamount;
			$objCollections->cashamount = $objTable->getudfvalue("u_recvamount") - $objTable->getudfvalue("u_chngamount");
		}
		if ($objTable->getudfvalue("u_checkamount")>0) {
			$objCollections->chequeacct = -2;
			$objCollections->chequeamount = $objTable->getudfvalue("u_checkamount");
		}
		if ($objTable->getudfvalue("u_creditcardamount")>0) {
			$objCollections->creditcardamount = $objTable->getudfvalue("u_creditcardamount");
		}
		if ($objTable->getudfvalue("u_creditamount")>0) {
			$objCollections->otheramount = $objTable->getudfvalue("u_creditamount");
		}
		//$objCollections->acctbranch = $_SESSION["branch"];
		$objCollections->valuedate = $objCollections->docdate;
		$objCollections->cleared = 1;
		/*(if ($objCollections->docseries!=-1) {
			$objCollections->docno = getNextSeriesNoByBranch("INCOMINGPAYMENT",$objCollections->docseries,$objCollections->docdate,$objConnection,true);
		}*/	
		$objCollections->postdate = $objCollections->docdate ." ". date('H:i:s');
		$objCollections->journalremark = "Incoming Payment - " . $objCollections->bpcode;
		
		if ($actionReturn) {
			$objRs->queryopen("select * from u_hisposcredits where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1 and u_credited<>0");
			while ($objRs->queryfetchrow("NAME")) {
				$objCollectionsInvoices->prepareadd();
				$objCollectionsInvoices->docid = getNextIdByBranch('collectionsinvoices',$objConnection);
				$objCollectionsInvoices->docno = $objCollections->docno;
				switch ($objRs->fields["U_REFTYPE"]) {
					case "DP":
						$objCollectionsInvoices->reftype = "DOWNPAYMENT";
						$objCollectionsInvoices->othertrxtype = "Partial Payments";
						$objCollectionsInvoices->refno = $objRs->fields["U_REFNO"];
						break;
					case "CM":
						$objCollectionsInvoices->reftype = "ARCREDITMEMO";
						$objCollectionsInvoices->othertrxtype = "Credits";
						$objCollectionsInvoices->refno = $objRs->fields["U_REFNO"];
						break;
					case "CHRG":
						$objCollectionsInvoices->reftype = "ARINVOICE";
						$objCollectionsInvoices->othertrxtype = "Hospital Fees";
						$objCollectionsInvoices->refno = $objRs->fields["U_REFNO"];
						break;
					case "PN":
						$objCollectionsInvoices->reftype = "JOURNALVOUCHER";
						$objRs2->queryopen("select docno, u_docdate, u_feetype, u_jvdocno from u_hispronotes where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$objRs->fields["U_REFNO"]."'");
						if ($objRs2->queryfetchrow("NAME")) {
							$objCollectionsInvoices->othertrxtype = $objRs2->fields["u_feetype"];
							$objCollectionsInvoices->bprefno = $objRs2->fields["docno"];
							$objCollectionsInvoices->refdate = $objRs2->fields["u_docdate"];
							$objCollectionsInvoices->refno = $objRs2->fields["u_jvdocno"] . "/1"; 
						} else raiseError("Unable to find Health Benefit Package [".$objRs->fields["U_REFNO"]."] for info.");	
						break;
					default:
						$objCollectionsInvoices->reftype = "JOURNALVOUCHER";
						$objCollectionsInvoices->refno = $objRs->fields["U_REFNO"];
						$objRs2->queryopen("select b.otherdocno, b.docdate, c.othertrxtype, c.remarks from journalvouchers b, journalvoucheritems c where c.company=b.company and c.branch=b.branch and c.docid=b.docid and c.docno='".$objRs->fields["U_REFNO"]."' and b.company='".$_SESSION["company"]."' and b.branch='".$_SESSION["branch"]."'");
						if ($objRs2->queryfetchrow("NAME")) {
							$objCollectionsInvoices->othertrxtype = $objRs2->fields["othertrxtype"];
							$objCollectionsInvoices->bprefno = $objRs2->fields["otherdocno"];
							$objCollectionsInvoices->refdate = $objRs2->fields["docdate"];
						} else raiseError("Unable to find Journal Voucher Item [".$objRs->fields["U_REFNO"]."] for info.");	
						break;	
				}
				$objCollectionsInvoices->remarks = $objRs->fields["U_REMARKS"];
				$objCollectionsInvoices->refbranch = $objCollections->branchcode;
				$objCollectionsInvoices->bpcode = $objCollections->bpcode;
				$objCollectionsInvoices->bpname = $objCollections->bpname;
				$objCollectionsInvoices->currency = $objCollections->currency;
				$objCollectionsInvoices->objectcode = $objCollections->objectcode;
				$objCollectionsInvoices->privatedata["header"] = $objCollections;
				$objCollectionsInvoices->amount = $objRs->fields["U_CREDITED"];
				$objCollectionsInvoices->balanceamount = $objCollectionsInvoices->amount;
				if ($actionReturn) $actionReturn = $objCollectionsInvoices->add();
			}	
		}
								
		if ($actionReturn && $objTable->getudfvalue("u_checkamount")>0) {
			$objCollectionsCheques->prepareadd();
			$objCollectionsCheques->docid = getNextIdByBranch('collectionscheques',$objConnection);
			$objCollectionsCheques->docno = $objCollections->docno;
			$objCollectionsCheques->objectcode = $objCollections->objectcode;
			$objCollectionsCheques->checkdate = $objCollections->docdate;
			$objCollectionsCheques->checkno = $objTable->getudfvalue("u_checkno");
			$objCollectionsCheques->bank = $objTable->getudfvalue("u_bank");
			$objCollectionsCheques->bankbranch = "";
			$objCollectionsCheques->amount = $objTable->getudfvalue("u_checkamount");
			$objCollectionsCheques->privatedata["header"] = $objCollections;
			if ($actionReturn) $actionReturn = $objCollectionsCheques->add();
		}

		if ($actionReturn && $objTable->getudfvalue("u_creditcardamount")>0) {
			$objRs->queryopen("select * from u_hisposcreditcards where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'"); 
			while ($objRs->queryfetchrow("NAME")) {
				$objCollectionsCreditCards->docid = getNextIdByBranch('collectionscreditcards',$objConnection);
				$objCollectionsCreditCards->docno = $objCollections->docno;
				$objCollectionsCreditCards->objectcode = $objCollections->objectcode;
				$objCollectionsCreditCards->creditcard = $objRs->fields["U_CREDITCARD"];
				$objCollectionsCreditCards->creditcardno = $objRs->fields["U_CREDITCARDNO"];
				$objCollectionsCreditCards->creditcardname = $objRs->fields["U_CREDITCARDNAME"];
				$objCollectionsCreditCards->cardexpiretext = $objRs->fields["U_EXPIREDATE"];
				$objCollectionsCreditCards->cardexpiredate = getmonthendDB("20" . substr($objCollectionsCreditCards->cardexpiretext, 3, 2) . "-" . substr($objCollectionsCreditCards->cardexpiretext, 0, 2) . "-01");
				$objCollectionsCreditCards->approvalno = $objRs->fields["U_APPROVALNO"];
				$objCollectionsCreditCards->amount = $objRs->fields["U_AMOUNT"];
				$objCollectionsCreditCards->privatedata["header"] = $objCollections;
				$actionReturn = $objCollectionsCreditCards->add();				
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn && $objTable->getudfvalue("u_otheramount")>0) {
			$objCollections->setudfvalue("u_chqexp","BAD DEBTS");
			$objRs->queryopen("select B.CASHCARD, A.U_INSCODE, A.U_AMOUNT from u_hisposins A inner join cashcards b on b.pospaycode=a.u_inscode where A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.docid='$objTable->docid'"); 
			while ($objRs->queryfetchrow("NAME")) {
				$objCollectionsCashCards->docid = getNextIdByBranch('collectionscashcards',$objConnection);
				$objCollectionsCashCards->docno = $objCollections->docno;
				$objCollectionsCashCards->objectcode = $objCollections->objectcode;
				$objCollectionsCashCards->cashcard = $objRs->fields["CASHCARD"];
				$objCollectionsCashCards->refno = $objRs->fields["U_INSCODE"];
				$objCollectionsCashCards->amount = $objRs->fields["U_AMOUNT"];
				$objCollectionsCashCards->privatedata["header"] = $objCollections;
				$actionReturn = $objCollectionsCashCards->add();				
				if (!$actionReturn) break;
			}
		}
		
		/*if ($actionReturn && $objTable->getudfvalue("u_creditamount")>0) {
			$objRs->queryopen("select u_posarcashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Sales - A/R");
			
			$objCollectionsCashCards->docid = getNextIdByBranch("collectionscashcards",$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objRs->fields["u_posarcashcard"];
			$objCollectionsCashCards->refno = "-"; 
			$objCollectionsCashCards->amount = $objTable->getudfvalue("u_creditamount");
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			$actionReturn = $objCollectionsCashCards->add();
		}*/
		/*
		if ($objCollections->colltype=="CPN") {
			$cashcarddata = getcashcarddata($objCollections->userfields["u_cashcard"]["value"],"LINKTODOCNO");
			$objCollectionsCashCards->prepareadd();
			$objCollectionsCashCards->docid = getNextIdByBranch('collectionscashcards',$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objCollections->userfields["u_cashcard"]["value"];
			$objCollectionsCashCards->linktodocno = $cashcarddata["LINKTODOCNO"];
			if ($objCollectionsCashCards->linktodocno=="1") {
				$objCollectionsCashCards->linkdocno = $objCollections->acctno;
			}
			$objCollectionsCashCards->refbranch = $objCollections->acctbranch;
			$objCollectionsCashCards->refno = "-";
			$objCollectionsCashCards->amount = $objCollections->paidamount;
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			if ($actionReturn) $actionReturn = $objCollectionsCashCards->add();
		}
		*/
		if ($actionReturn) $actionReturn = $objCollections->add();
	}		
	
	
	return $actionReturn;
}


function onCustomEventdocumentschema_brPostCashSalesGPSHIS($objTable,$delete=false,$refund=false) {
	global $objConnection;
	$actionReturn = true;

	$custno = $objTable->getudfvalue("u_patientid");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	if (!$delete) {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARINVOICES");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARINVOICEITEMS");
		$objMarketingDocumentCashCards = new marketingdocumentcashcards(null,$objConnection,"ARINVOICECASHCARDS");
		//$objMarketingDocumentCheques = new marketingdocumentcheques(null,$objConnection,"ARINVOICECHEQUES");
		$objMarketingDocumentCreditCards = new marketingdocumentcreditcards(null,$objConnection,"ARINVOICECREDITCARDS");
		$settings = getBusinessObjectSettings("ARINVOICE");
	} else {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARCREDITMEMOS");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARCREDITMEMOITEMS");
		$objMarketingDocumentCashCards = new marketingdocumentcashcards(null,$objConnection,"ARCREDITMEMOCASHCARDS");
		//$objMarketingDocumentCheques = new marketingdocumentcheques(null,$objConnection,"ARINVOICECHEQUES");
		$objMarketingDocumentCreditCards = new marketingdocumentcreditcards(null,$objConnection,"ARCREDITMEMOCREDITCARDS");
		$settings = getBusinessObjectSettings("ARCREDITMEMO");
	}	
	
	
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	if (!$delete) $objMarketingDocuments->objectcode = "ARINVOICE";
	else $objMarketingDocuments->objectcode = "ARCREDITMEMO";
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];
	if (!$delete) $objMarketingDocuments->docid = getNextIdByBranch("arinvoices",$objConnection);
	else $objMarketingDocuments->docid = getNextIdByBranch("arcreditmemos",$objConnection);

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $custno;
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->currency = $customerdata["CURRENCY"];
	$objMarketingDocuments->trxtype = "POS";
	if ($refund) $objMarketingDocuments->trxtype = "";
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_refno");
	if (!$delete) {
		$objTable->setudfvalue("u_ardocno",$objMarketingDocuments->docno);
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Cash Sales. " . $objTable->getudfvalue("u_remarks");
		$objMarketingDocuments->journalremark = "Cash Sales - " . $objMarketingDocuments->bpcode;
	} else {
		$objTable->setudfvalue("u_arcmdocno",$objMarketingDocuments->docno);
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Cash Sales Cancelled. " . $objTable->getudfvalue("u_remarks");
		$objMarketingDocuments->journalremark = "Cash Sales Cancelled - " . $objMarketingDocuments->bpcode;
	}	
	$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];
	if ($actionReturn && $objTable->getudfvalue("u_payreftype")=="CHRG") {

		if ($actionReturn) {
			//$objRsItems->setdebug();
			$objRsItems->queryopen("select a.U_DEPARTMENT, c.U_ITEMGROUP, c.U_ITEMCODE, c.U_ITEMDESC, c.U_DISCAMOUNT, c.U_ISSTAT, c.U_STATUNITPRICE, c.U_UNITPRICE, c.U_PRICE, c.U_QUANTITY, c.U_VATCODE, c.U_VATRATE, c.U_VATAMOUNT, c.U_LINETOTAL, b.MANAGEBY, b.MANAGEBYMETHOD, b.U_PROFITCENTER  from u_hisrequests a, u_hisrequestitems c, items b where b.itemcode=c.u_itemcode and c.company=a.company and c.branch=a.branch and c.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$objTable->getudfvalue("u_payrefno")."'");
			//var_dump($objRsItems->sqls);
			while ($objRsItems->queryfetchrow("NAME")) {
				$objMarketingDocumentItems->prepareadd();
				$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
				$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
				$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
				if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
				$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
				$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
				$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
				if ($objTable->getudfvalue("u_disconbill")=="0" || $objRsItems->fields["U_ITEMGROUP"]=="PRF" || $objRsItems->fields["U_ITEMGROUP"]=="PRM") {
					if ($objRsItems->fields["U_ISSTAT"]==0) {
						$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
					} else {
						$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
					}	
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
				} else {
					if ($objRsItems->fields["U_ISSTAT"]==0) {
						$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
						$objMarketingDocumentItems->price = round($objRsItems->fields["U_UNITPRICE"],2);
					} else {
						$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
						$objMarketingDocumentItems->price = round($objRsItems->fields["U_STATUNITPRICE"],2);
					}	
				}	
				$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
				$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
				$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
				if ($objTable->getudfvalue("u_trxtype")=="CM") {
					$objMarketingDocumentItems->vatcode = "VATOX";
					$objMarketingDocumentItems->vatrate = 0;
					$objMarketingDocumentItems->vatamount = 0;
				} else {
					$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
					$objMarketingDocumentItems->vatrate = $objRsItems->fields["U_VATRATE"];
					$objMarketingDocumentItems->vatamount = $objRsItems->fields["U_VATAMOUNT"];
					if ($objTable->getudfvalue("u_scdisc")==1) {
						$objMarketingDocumentItems->linetotal = $objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount;
						$objMarketingDocumentItems->price = $objMarketingDocumentItems->linetotal/$objMarketingDocumentItems->quantity;
						$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
						$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
						$objMarketingDocumentItems->vatcode = "VATOX";
						$objMarketingDocumentItems->vatrate = 0;
						$objMarketingDocumentItems->vatamount = 0;
					}
				}	
				$objMarketingDocumentItems->drcode = iif($objRsItems->fields["U_PROFITCENTER"]!="",$objRsItems->fields["U_PROFITCENTER"],$objRsItems->fields["U_DEPARTMENT"]);
				$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->dropship = 1;
				$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
				$objMarketingDocumentItems->linestatus = "O";
				$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
				$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
				
				$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
				$actionReturn = $objMarketingDocumentItems->add();
				if (!$actionReturn) break;
			}
		}	
	}	
		
	if ($actionReturn && ($objTable->getudfvalue("u_otheramount")!=0 || $objTable->getudfvalue("u_creditamount")!=0)) {
		$aramount = 0;
		$objRs->queryopen("select b.cashcard, b.cashcardname, a.u_amount, a.u_hmo from u_hisposins a left join cashcards b on b.u_disccode=a.u_inscode where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["u_hmo"]!="2") {
				$aramount += $objRs->fields["u_amount"];
				continue;
			}	
			$objMarketingDocumentCashCards->lineid = getNextIdByBranch(strtolower($objMarketingDocumentCashCards->dbtable),$objConnection);
			$objMarketingDocumentCashCards->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentCashCards->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentCashCards->cashcard = $objRs->fields["cashcard"];
			$objMarketingDocumentCashCards->refno = "-"; 
			$objMarketingDocumentCashCards->amount = $objRs->fields["u_amount"];
			$objMarketingDocumentCashCards->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentCashCards->add();
			if (!$actionReturn) break;
			$objMarketingDocuments->settledamount += $objRs->fields["u_amount"];
		}

		$objRs->queryopen("select sum(u_credited) as u_credited from u_hisposcredits where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
		if ($objRs->queryfetchrow("NAME")) $aramount += $objRs->fields["u_credited"];
		if ($actionReturn && $aramount>0) {
			$objRs->queryopen("select u_posarcashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Sales - A/R");
			
			$objMarketingDocumentCashCards->lineid = getNextIdByBranch(strtolower($objMarketingDocumentCashCards->dbtable),$objConnection);
			$objMarketingDocumentCashCards->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentCashCards->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentCashCards->cashcard = $objRs->fields["u_posarcashcard"];
			$objMarketingDocumentCashCards->refno = "-"; 
			$objMarketingDocumentCashCards->amount = $aramount;
			$objMarketingDocumentCashCards->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentCashCards->add();
			if (!$actionReturn) break;
			$objMarketingDocuments->settledamount += $aramount;
		}
	}

	if ($actionReturn && $objTable->getudfvalue("u_creditcardamount")!=0) {
		$objRs->queryopen("select * from u_hisposcreditcards where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
		while ($objRs->queryfetchrow("NAME")) {
			$objMarketingDocumentCreditCards->lineid = getNextIdByBranch(strtolower($objMarketingDocumentCreditCards->dbtable),$objConnection);
			$objMarketingDocumentCreditCards->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentCreditCards->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentCreditCards->creditcard = $objRs->fields["U_CREDITCARD"];
			$objMarketingDocumentCreditCards->creditcardno = $objRs->fields["U_CREDITCARDNO"];
			$objMarketingDocumentCreditCards->creditcardholdername = $objRs->fields["U_CREDITCARDNAME"];
			$objMarketingDocumentCreditCards->cardexpiretext = $objRs->fields["U_EXPIREDATE"];
			$objMarketingDocumentCreditCards->cardexpiredate = getmonthendDB("20" . substr($objMarketingDocumentCreditCards->cardexpiretext, 3, 2) . "-" . substr($objMarketingDocumentCreditCards->cardexpiretext, 0, 2) . "-01");
			$objMarketingDocumentCreditCards->approvalno = $objRs->fields["U_APPROVALNO"];
			$objMarketingDocumentCreditCards->amount = $objRs->fields["U_AMOUNT"];
			$objMarketingDocumentCreditCards->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentCreditCards->add();				
			if (!$actionReturn) break;
			$objMarketingDocuments->settledamount += $objRs->fields["U_AMOUNT"];
		}
	}

	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = 0;
		
		$objMarketingDocuments->docstatus = "C";

		$objMarketingDocuments->cashamount = $objTable->getudfvalue("u_recvamount") - $objTable->getudfvalue("u_chngamount") + $objTable->getudfvalue("u_checkamount");
		$objMarketingDocuments->settledamount += $objMarketingDocuments->cashamount;
		if ($actionReturn && $objTable->getudfvalue("u_discamount")!=0) {
			$objRs->queryopen("select cashcard from cashcards where u_disccode='".$objTable->getudfvalue("u_disccode")."'");
			if ($objRs->queryfetchrow()) {
				$cashcard = $objRs->fields[0];
				$lineno=0;
				$discamount=0;
				$objRs->queryopen("select u_profitcenter, sum(u_discamount) as u_discamount from (
select if(d.u_profitcenter<>'',d.u_profitcenter,b.u_department) as u_profitcenter, (c.u_discamount*c.u_quantity) as u_discamount from u_hisrequests b
inner join u_hisrequestitems c on c.company=b.company and c.branch=b.branch and c.docid=b.docid
left outer join u_hisitems d on d.code=c.u_itemcode
where b.company='".$_SESSION["company"]."' and b.branch='".$_SESSION["branch"]."' and b.docno='".$objTable->getudfvalue("u_payrefno")."'

) as x group by u_profitcenter having u_discamount<>'0'");
				while ($objRs->queryfetchrow("NAME")) {
					$lineno++;
					$discamount=0;
					if ($lineno==$objRs->recordcount()) {
						$discamount = $objMarketingDocuments->totalamount - $objMarketingDocuments->settledamount;
					} else {
						$discamount = $objRs->fields["u_discamount"];
					}	
						
					$objMarketingDocumentCashCards->lineid = getNextIdByBranch(strtolower($objMarketingDocumentCashCards->dbtable),$objConnection);
					$objMarketingDocumentCashCards->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentCashCards->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentCashCards->profitcenter = $objRs->fields["u_profitcenter"];
					$objMarketingDocumentCashCards->cashcard = $cashcard;
					$objMarketingDocumentCashCards->refno = "-"; 
					$objMarketingDocumentCashCards->amount = $discamount;
					$objMarketingDocumentCashCards->privatedata["header"] = $objMarketingDocuments;
					$objMarketingDocuments->settledamount += $objMarketingDocumentCashCards->amount;
					$actionReturn = $objMarketingDocumentCashCards->add();
					if (!$actionReturn) break;
				}	
			} else return raiseError("Unable to retreive cashcard for discount[".$objTable->getudfvalue("u_disccode")."].");
		}
		
		$objMarketingDocuments->creditcardamount = $objTable->getudfvalue("u_creditcardamount");
		//$objMarketingDocuments->chequeamount = $objTable->getudfvalue("u_checkamount");
		if ($objMarketingDocuments->dueamount!=0) return raiseError("Cash Sales cannot have balance.");
		$actionReturn = $objMarketingDocuments->add();
	}
	/*if ($objTable->getudfvalue("u_disconbill")=="0") {
		if (bcsub($objMarketingDocuments->totalamount,$objTable->getudfvalue("u_dueamount"))!=0) {
			if ($actionReturn) $actionReturn = raiseError("Mismatch Actual Charges [".$objTable->getudfvalue("u_dueamount")."] and Cash Sales Due Amount[".$objMarketingDocuments->totalamount."]");
		}
	} else {
		if (bcsub($objMarketingDocuments->totalamount,$objTable->getudfvalue("u_totalamount"))!=0) {
			if ($actionReturn) $actionReturn = raiseError("Mismatch Actual Charges [".$objTable->getudfvalue("u_totalamount")."] and Cash Sales Total Amount[".$objMarketingDocuments->totalamount."]");
		}
	}*/	
	/*	
	if ($actionReturn) {
		$objRs->queryopen("select a.u_doctorid from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'  group by a.u_doctorid");
		while ($objRs->queryfetchrow("NAME")) {
			
			$supplierdata = getsupplierdata($objRs->fields["u_doctorid"],"SUPPNO,SUPPNAME,PAYMENTTERM");
			if ($supplierdata["SUPPNAME"]=="") return raiseError("Invalid Supplier [".$objRs->fields["u_doctorid"]."].");

			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APINVOICES");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APINVOICEITEMS");
			
			$settings = getBusinessObjectSettings("APINVOICE");
			
			$objMarketingDocuments->prepareadd();
			$objMarketingDocuments->objectcode = "APINVOICE";
			$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
			$objMarketingDocuments->jeposting = $settings["jeposting"];
			$objMarketingDocuments->docid = getNextIdByBranch("apinvoices",$objConnection);
		
			$objMarketingDocuments->docseries = getDefaultSeries($objMarketingDocuments->objectcode,$objConnection);
			$objMarketingDocuments->docno = getNextSeriesNoByBranch($objMarketingDocuments->objectcode,$objMarketingDocuments->docseries,$objMarketingDocuments->docdate,$objConnection);
			$objMarketingDocuments->bpcode = $supplierdata["SUPPNO"];
			$objMarketingDocuments->bpname = $supplierdata["SUPPNAME"];
			$objMarketingDocuments->doctype = "I";
			
			$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
			$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docduedate");
			$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
			$objMarketingDocuments->bprefno = $objTable->docno;
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Billing. " . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "A/P Billing - " . $objMarketingDocuments->bpcode;
			$objMarketingDocuments->paymentterm = $supplierdata["PAYMENTTERM"];
			
			$pfcode = "";
			$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' and a.u_doctorid='".$objRs->fields["u_doctorid"]."'");
			while ($objRsItems->queryfetchrow("NAME")) {
				if ($objRsItems->fields["U_ITEMCODE"]!="") $pfcode = $objRsItems->fields["U_ITEMCODE"];
				else $objRsItems->fields["U_ITEMCODE"] = $pfcode;
				$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
				$objMarketingDocumentItems->prepareadd();
				$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
				$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
				$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
				$objMarketingDocumentItems->lineid = getNextIdByBranch("apinvoiceitems",$objConnection);
				$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
				$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
				$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
				$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
				$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
				$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
				$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
				$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
				$objMarketingDocumentItems->vatamount = round($objMarketingDocumentItems->linetotal*($objMarketingDocumentItems->vatrate/100),2);
				$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
				$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
				$objMarketingDocumentItems->linestatus = "O";
				$objMarketingDocuments->vatamount += round(($objMarketingDocumentItems->linetotal - ($objMarketingDocumentItems->linetotal * ($objMarketingDocuments->discperc/100))) * ($objMarketingDocumentItems->vatrate/100),2);
				$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
				
				$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
				$actionReturn = $objMarketingDocumentItems->add();
				if (!$actionReturn) break;
			}			
			
			if ($actionReturn) {
				$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
				$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
				if ($objMarketingDocuments->dueamount>0) $objMarketingDocuments->docstatus = "O";
				else $objMarketingDocuments->docstatus = "C";
				$actionReturn = $objMarketingDocuments->add();
			}
			if ($actionReturn) {
				$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
				$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
				$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
				$bpdata["balance"] = $objMarketingDocuments->dueamount *-1;
				$actionReturn = updatesupplierbalance($bpdata);
			}			
			if (!$actionReturn) break;
		}
	}	
	*/
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostCashSalesGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostCashSalesHealthBenefitsGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset(null,$objConnection);
	$obju_HISDoctors = new masterdataschema(null,$objConnection,"u_hisdoctors");
	$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
	$obju_HISRequestItems = new documentlinesschema_br(null,$objConnection,"u_hisrequestitems");
	$obju_HISPOSIns = new documentlinesschema_br(null,$objConnection,"u_hisposins");
	if ($objTable->getudfvalue("u_colltype")=="Professional Fees" || $objTable->getudfvalue("u_colltype")=="Professional Materials") {
		if (!$obju_HISRequests->getbykey($objTable->getudfvalue("u_payrefno"))) return raiseError("Unable to find Requests No. [".$objTable->getudfvalue("u_payrefno")."]");
		if (!$obju_HISRequestItems->getbysql("DOCID='$obju_HISRequests->docid' AND U_DOCTORID<>''")) return raiseError("Unable to find Item with Doctor for Requests No. [".$objTable->getudfvalue("u_payrefno")."]");
		//if (!$obju_HISDoctors->getbykey($obju_HISRequests->getudfvalue("u_doctorid"))) return raiseError("Unable to find Doctor ID [".$obju_HISConsultancyrequests->getudfvalue("u_doctorid")."]");
	}

	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			
	$settings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $settings["autopost"];
	$objJvHdr->jeposting = $settings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
	$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Health Benefits";
	
	$objJvHdr->otherdocno = $objTable->docno;
	$objJvHdr->otherdocdate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->otherdocduedate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->otherbprefno = $objTable->getudfvalue("u_refno");
	$objJvHdr->otherbpcode = $objTable->getudfvalue("u_patientid");
	$objJvHdr->otherbpname = $objTable->getudfvalue("u_patientname");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_docdate") . ";" . $objTable->getudfvalue("u_remarks");
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');

	$cnlastno=0;
	$obju_HISPOSIns->queryopen($obju_HISPOSIns->selectstring()." and docid='$objTable->docid' and u_hmo<>2 order by lineid");
	while ($obju_HISPOSIns->queryfetchrow()) {
		if ($obju_HISPOSIns->getudfvalue("u_hmo")=="6") {
			$objRs->queryopen("select custname from customers where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and custno='".$obju_HISPOSIns->getudfvalue("u_memberid")."'");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("Unable to find Customer No [".$obju_HISPOSIns->getudfvalue("u_inscode")."]");

			$u_inscode = $obju_HISPOSIns->getudfvalue("u_memberid");
			$u_insname = $objRs->fields["custname"];
		} elseif ($obju_HISPOSIns->getudfvalue("u_hmo")=="7") {
			$objRs->queryopen("select b.u_glacctno, b.u_glacctname from u_hishealthins b where b.code='".$obju_HISPOSIns->getudfvalue("u_inscode")."'");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("Unable to find Health Benefit [".$obju_HISPOSIns->getudfvalue("u_inscode")."]");
			$u_inscode = $objRs->fields["u_glacctno"];
			$u_insname = $objRs->fields["u_glacctname"];
		} else {
			$objRs->queryopen("select b.name as u_insname from u_hishealthins b where b.code='".$obju_HISPOSIns->getudfvalue("u_inscode")."'");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("Unable to find Health Benefit [".$obju_HISPOSIns->getudfvalue("u_inscode")."]");

			$u_inscode = $obju_HISPOSIns->getudfvalue("u_inscode");
			$u_insname = $objRs->fields["u_insname"];
		}	
		$u_amount = $obju_HISPOSIns->getudfvalue("u_amount");
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
		if ($obju_HISPOSIns->getudfvalue("u_hmo")=="7") {
			$objJvDtl->itemtype = "A";
		} else {
			$objJvDtl->itemtype = "C";
		}	
		$objJvDtl->itemno = $u_inscode;
		$objJvDtl->itemname = $u_insname;
		if (!$delete) {
			if ($obju_HISPOSIns->getudfvalue("u_hmo")!="7") {
				$objJvHdr->sllastno ++;
				$objJvDtl->lineno = $objJvHdr->sllastno;
			}	
			$objJvDtl->reftype = "";
			$objJvDtl->refno = "";
			$objJvDtl->otherrefno = $objTable->docno;
		} else {
			if ($obju_HISPOSIns->getudfvalue("u_hmo")!="7") {
				$cnlastno++;
				$objJvDtl->reftype = "JOURNALVOUCHER";
				$objJvDtl->refno = $objTable->getudfvalue("u_jvdocno")."/".$cnlastno;
			}	
		}
		if ($objTable->getudfvalue("u_colltype")=="Professional Fees") {
			$objJvDtl->othertrxtype = "Professional Fees";
			$objJvDtl->otherbpcode = $obju_HISRequestItems->getudfvalue("u_doctorid");
			$objJvDtl->otherbpname = $obju_HISRequestItems->getudfvalue("u_doctorname");
		} elseif ($objTable->getudfvalue("u_colltype")=="Professional Materials") {
			$objJvDtl->othertrxtype = "Professional Materials";
			$objJvDtl->otherbpcode = $obju_HISRequestItems->getudfvalue("u_doctorid");
			$objJvDtl->otherbpname = $obju_HISRequestItems->getudfvalue("u_doctorname");
		}  else {
			
			$objRs->queryopen("select substring(name,1,30) as name from u_hissections where code='".$objTable->getudfvalue("u_department")."'");
			if ($objRs->queryfetchrow("NAME")) $objJvDtl->othertrxtype = $objRs->fields["name"];
			
		}		
		
		if (!$delete) {
			if ($u_amount>0) {
				$objJvDtl->debit = $u_amount;
				$objJvDtl->grossamount = $objJvDtl->debit;
			} else {
				$objJvDtl->credit = $u_amount*-1;
				$objJvDtl->grossamount = $objJvDtl->credit;
			}	
		} else {
			if ($u_amount>0) {
				$objJvDtl->credit = $u_amount;
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = $u_amount*-1;
				$objJvDtl->grossamount = $objJvDtl->debit;
			}	
		}	
		if ($objTable->getudfvalue("u_colltype")=="Professional Fees") {
			$objJvDtl->remarks = "Based on Cash Sales " . $objTable->docno .". ".$objTable->getudfvalue("u_patientname"). ". "."Professional Fees".". ".$objJvDtl->otherbpname;
		} elseif ($objTable->getudfvalue("u_colltype")=="Professional Materials") {
			$objJvDtl->remarks = "Based on Cash Sales " . $objTable->docno .". ".$objTable->getudfvalue("u_patientname"). ". "."Professional Materials".". ".$objJvDtl->otherbpname;
		} else {
			$objJvDtl->remarks = "Based on Cash Sales " . $objTable->docno .". ".$objTable->getudfvalue("u_patientname"). ". "."Hospital Fees".". ".$objJvDtl->otherbpname;
		}	
		
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		//var_dump(array($objJvHdr->totaldebit,$objJvHdr->totalcredit));
		$actionReturn = $objJvDtl->add();
		
		if (!$actionReturn)	break;
	}
	if ($actionReturn && ($objJvHdr->totaldebit!=0 || $objJvHdr->totalcredit!=0)) {
		$objRs->queryopen("select c.formatcode, c.acctname from u_hissetup a, cashcards b, chartofaccounts c where c.formatcode=b.glacctno and b.cashcard=a.u_posarcashcard");
		if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card and/or G/L Account defined for Cash Sales - A/R.");
		$glacctno = $objRs->fields["formatcode"];
		$glacctname = $objRs->fields["acctname"];
		
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->itemno = $glacctno;
		$objJvDtl->itemname = $glacctname;
		
		if (!$delete) {
			if (($objJvHdr->totaldebit-$objJvHdr->totalcredit)>0) {
				$objJvDtl->credit = ($objJvHdr->totaldebit-$objJvHdr->totalcredit);
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = ($objJvHdr->totalcredit-$objJvHdr->totaldebit);
				$objJvDtl->grossamount = $objJvDtl->debit;
			}	
		} else {
			if (($objJvHdr->totaldebit-$objJvHdr->totalcredit)>0) {
				$objJvDtl->credit = ($objJvHdr->totaldebit-$objJvHdr->totalcredit);
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = ($objJvHdr->totalcredit-$objJvHdr->totaldebit);
				$objJvDtl->grossamount = $objJvDtl->debit;
			}	
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
	//var_dump(array($objJvHdr->totaldebit,$objJvHdr->totalcredit));
	// for cancell reverse ap for cash portion
	$apamount = $objTable->getudfvalue("u_recvamount") + $objTable->getudfvalue("u_checkamount") + $objTable->getudfvalue("u_creditcardamount") - $objTable->getudfvalue("u_chngamount");
	if ($actionReturn && $delete && $apamount!=0 && ($objTable->getudfvalue("u_colltype")=="Professional Fees" || $objTable->getudfvalue("u_colltype")=="Professional Materials")) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "S";
		$objJvDtl->itemno = $obju_HISRequestItems->getudfvalue("u_doctorid");
		$objJvDtl->itemname = $obju_HISRequestItems->getudfvalue("u_doctorname");
		$objJvDtl->reftype = "APINVOICE";
		$objJvDtl->refno = $objTable->getudfvalue("u_apdocno");
		$objJvDtl->debit = $apamount;
		$objJvDtl->grossamount = $objJvDtl->debit;
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
		
		if ($actionReturn) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->itemtype = "S";
			$objJvDtl->itemno = $obju_HISRequestItems->getudfvalue("u_doctorid");
			$objJvDtl->itemname = $obju_HISRequestItems->getudfvalue("u_doctorname");
			$objJvDtl->reftype = "APCREDITMEMO";
			$objJvDtl->refno = $objTable->getudfvalue("u_apcmdocno");
			$objJvDtl->credit = $apamount;
			$objJvDtl->grossamount = $objJvDtl->credit;
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}
	
	}
	if ($actionReturn && ($objJvHdr->totaldebit!=0 || $objJvHdr->totalcredit!=0)) {
		if (!$delete) {
			$objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
		} else {
			$objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
		}	
		$actionReturn = $objJvHdr->add();				
	}	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostCashSalesHealthBenefitsGPSHIS()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostRequestCreditsGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue("u_prepaid")==0) return raiseError("You cannot use health benefit for Charge requests.");
	if ($objTable->getudfvalue("u_otheramount")==0) return true;
	if ($objTable->getudfvalue("u_otheramount")!=$objTable->getudfvalue("u_amount")) return raiseError("Health Benefit must be equal with total amount.");

	$objRs = new recordset(null,$objConnection);
	$obju_HISPOS = new documentschema_br(null,$objConnection,"u_hispos");
	$obju_HISPOSIns = new documentlinesschema_br(null,$objConnection,"u_hisposins");

	if ($delete) {
		if ($obju_HISPOS->getbykey($objTable->getudfvalue("u_payrefno"))) {
			$obju_HISPOS->docstatus = "CN";
			$obju_HISPOS->setudfvalue("u_cancelledby",$objTable->getudfvalue("u_cancelledby"));
			$obju_HISPOS->setudfvalue("u_cancelledreason",$objTable->getudfvalue("u_cancelledreason"));
			$obju_HISPOS->setudfvalue("u_cancelledremarks",$objTable->getudfvalue("u_cancelledremarks"));
			return $obju_HISPOS->update($obju_HISPOS->docno,$obju_HISPOS->rcdversion);
		} else return raiseError("Unable to find Credit Memo [".$objTable->getudfvalue("u_payrefno")."] to cancel.");
	}
	$obju_HISPOS->prepareadd();
	$obju_HISPOS->docid = getNextIdByBranch("u_hispos",$objConnection);
	$obju_HISPOS->docseries = getDefaultSeries("CREDITMEMO",$objConnection);
	$obju_HISPOS->setudfvalue("u_docdate",$objTable->getudfvalue("u_requestdate"));
	$obju_HISPOS->docno = getNextSeriesNoByBranch("CREDITMEMO",$obju_HISPOS->docseries,$obju_HISPOS->getudfvalue("u_docdate"),$objConnection);
	$obju_HISPOS->docstatus = "O";
	$obju_HISPOS->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
	$obju_HISPOS->setudfvalue("u_disconbill",$objTable->getudfvalue("u_disconbill"));
	$obju_HISPOS->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
	$obju_HISPOS->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
	$obju_HISPOS->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
	$obju_HISPOS->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
	$obju_HISPOS->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
	$obju_HISPOS->setudfvalue("u_automanage",1);
	$obju_HISPOS->setudfvalue("u_prepaid",1);
	$obju_HISPOS->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
	$obju_HISPOS->setudfvalue("u_payreftype","CHRG");
	$obju_HISPOS->setudfvalue("u_payrefno",$objTable->docno);
	$obju_HISPOS->setudfvalue("u_scdisc",$objTable->getudfvalue("u_scdisc"));
	$obju_HISPOS->setudfvalue("u_trxtype","CM");
	//$objTable->setudfvalue("u_payreftype","CM");
	//$objTable->setudfvalue("u_payrefno",$obju_HISPOS->docno);
	
	if ($objTable->getudfvalue("u_ishb")=="1") $obju_HISPOS->setudfvalue("u_colltype","Hospital Fees");
	else if ($objTable->getudfvalue("u_ispf")=="1") $obju_HISPOS->setudfvalue("u_colltype","Professional Fees");
	else if ($objTable->getudfvalue("u_ispm")=="1") $obju_HISPOS->setudfvalue("u_colltype","Professional Materials");

	$obju_HISPOS->setudfvalue("u_totalamount",$objTable->getudfvalue("u_amountbefdisc"));
	if ($obju_HISPOS->getudfvalue("u_prepaid")=="1" && $obju_HISPOS->getudfvalue("u_disccode")!="") {
		$obju_HISPOS->setudfvalue("u_discamount",$objTable->getudfvalue("u_discamount"));
		$obju_HISPOS->setudfvalue("u_dueamount",$objTable->getudfvalue("u_amount"));
	} else {
		$obju_HISPOS->setudfvalue("u_discamount",0);
		$obju_HISPOS->setudfvalue("u_dueamount",$objTable->getudfvalue("u_amountbefdisc"));
	}
	$obju_HISPOS->setudfvalue("u_balanceamount",$obju_HISPOS->getudfvalue("u_dueamount"));
	
	$objRs->queryopen("select * from u_hisrequestins where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
	while ($objRs->queryfetchrow("NAME")) {
		$obju_HISPOSIns->prepareadd();
		$obju_HISPOSIns->lineid = getNextIdByBranch("u_hisposins",$objConnection);
		$obju_HISPOSIns->docid = $obju_HISPOS->docid;
		$obju_HISPOSIns->setudfvalue("u_inscode",$objRs->fields["U_INSCODE"]);
		$obju_HISPOSIns->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
		$obju_HISPOSIns->setudfvalue("u_memberid",$objRs->fields["U_MEMBERID"]);
		$obju_HISPOSIns->setudfvalue("u_hmo",$objRs->fields["U_HMO"]);
		$obju_HISPOSIns->setudfvalue("u_membername",$objRs->fields["U_MEMBERNAME"]);
		$obju_HISPOSIns->setudfvalue("u_membertype",$objRs->fields["U_MEMBERTYPE"]);
		$obju_HISPOSIns->privatedata["header"] = $obju_HISPOS;
		$actionReturn = $obju_HISPOSIns->add();
		if (!$actionReturn) break;
		$obju_HISPOS->setudfvalue("u_otheramount",$obju_HISPOS->getudfvalue("u_otheramount") + $objRs->fields["U_AMOUNT"]);
		$obju_HISPOS->setudfvalue("u_balanceamount",$obju_HISPOS->getudfvalue("u_balanceamount") - $objRs->fields["U_AMOUNT"]);
	}
	if ($actionReturn) $actionReturn = $obju_HISPOS->add();
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostRequestCreditsGPSHIS()");
	return $actionReturn;
	
}


function onCustomEventdocumentschema_brPostPOSCreditsGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset(null,$objConnection);

	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			
	$settings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $settings["autopost"];
	$objJvHdr->jeposting = $settings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
	$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Credits/Partial Payments";
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');

	if (!$delete) {
		//$objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
	} else {
		//$objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
	}	

	$objRs->queryopen("select * from u_hisposcredits where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_selected=1");
	while ($objRs->queryfetchrow("NAME")) {
	
		$custno = $objTable->getudfvalue("u_patientid");
		if ($custno=="") {
			$branchdata = getcurrentbranchdata("POSCUSTNO");
			$custno = $branchdata["POSCUSTNO"];
		}	
		
		$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
		if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
		$objJvDtl->itemtype = "C";
		$objJvDtl->itemno = $customerdata["CUSTNO"];
		$objJvDtl->itemname = $customerdata["CUSTNAME"];
		if ($objRs->fields["U_REFTYPE"]=="CM") $objJvDtl->reftype = "ARCREDITMEMO";
		else $objJvDtl->reftype = "DOWNPAYMENT";
		$objJvDtl->refno = $objRs->fields["U_REFNO"];
		if (!$delete) {
			$objJvDtl->debit = $objRs->fields["U_CREDITED"];
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = $objRs->fields["U_CREDITED"];
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		//var_dump(array("debit",$objJvDtl->debit));
		$actionReturn = $objJvDtl->add();
		if (!$actionReturn)	break;
	}

	if ($actionReturn && ($objJvHdr->totaldebit!=0 || $objJvHdr->totalcredit!=0)) {
		$objRs->queryopen("select c.formatcode, c.acctname from u_hissetup a, cashcards b, chartofaccounts c where c.formatcode=b.glacctno and b.cashcard=a.u_posarcashcard");
		if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card and/or G/L Account defined for Cash Sales - A/R.");
		$glacctno = $objRs->fields["formatcode"];
		$glacctname = $objRs->fields["acctname"];
		
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->projcode = "";
		$objJvDtl->itemtype = "A";
		$objJvDtl->itemno = $glacctno;
		$objJvDtl->itemname = $glacctname;
		
		if (!$delete) {
			$objJvDtl->credit = $objJvHdr->totaldebit;
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objJvHdr->totalcredit;
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$actionReturn = $objJvDtl->add();
	}
	
	if ($actionReturn && ($objJvHdr->totaldebit!=0 || $objJvHdr->totalcredit!=0)) $actionReturn = $objJvHdr->add();				
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostPOSCreditsGPSHIS()");
	return $actionReturn;
}
	
function onCustomEventdocumentschema_brPostChargesGPSHIS($objTable,$delete=false,$refund=false) {
	global $objConnection;
	$actionReturn = true;
	
	$custno = $objTable->getudfvalue("u_patientid");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	$objRsManageBy = new recordset(null,$objConnection);
	
	$objRs->queryopen("select u_stocklink from u_hissections where code='".$objTable->getudfvalue("u_department")."'");
	if ($objRs->queryfetchrow("NAME")) {
		if (!$delete) $objTable->setudfvalue("u_stocklink",$objRs->fields["u_stocklink"]);
		else $objTable->setudfvalue("u_cancelstocklink",$objRs->fields["u_stocklink"]);
	}	
	
	
	if (!$delete) {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARINVOICES");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARINVOICEITEMS");
			$settings = getBusinessObjectSettings("ARINVOICE");
		} else {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"SALESDELIVERIES");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"SALESDELIVERYITEMS");
			$settings = getBusinessObjectSettings("SALESDELIVERY");
		}	
	} else {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARCREDITMEMOS");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARCREDITMEMOITEMS");
			$settings = getBusinessObjectSettings("ARCREDITMEMO");
		} else {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"SALESRETURNS");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"SALESRETURNITEMS");
			$settings = getBusinessObjectSettings("SALESRETURN");
		}	
	}	
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	if ($objTable->getudfvalue("u_batchcode")=="Daily Room & Board Fees") {
		$objMarketingDocuments->sbo_post_flag = 0;
	} else {
		$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	}	
	$objMarketingDocuments->jeposting = $settings["jeposting"];

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $custno;
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->currency = $customerdata["CURRENCY"];
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_refno");
	if (!$delete) {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments->objectcode = "ARINVOICE";
			$objMarketingDocuments->docid = getNextIdByBranch("arinvoices",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Invoice. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "A/R Invoice - " . $objMarketingDocuments->bpcode;
			$objTable->setudfvalue("u_ardocno",$objMarketingDocuments->docno);
		} else {
			$objMarketingDocuments->objectcode = "SALESDELIVERY";
			$objMarketingDocuments->docid = getNextIdByBranch("salesdeliveries",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Delivery. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "Delivery - " . $objMarketingDocuments->bpcode;
			$objTable->setudfvalue("u_dndocno",$objMarketingDocuments->docno);
		}	
	} else {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments->objectcode = "ARCREDITMEMO";
			$objMarketingDocuments->docid = getNextIdByBranch("arcreditmemos",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Credit Memo. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "A/R Credit Memo - " . $objMarketingDocuments->bpcode;
			$objTable->setudfvalue("u_arcmdocno",$objMarketingDocuments->docno);
		} else {
			$objMarketingDocuments->objectcode = "SALESRETURN";
			$objMarketingDocuments->docid = getNextIdByBranch("salesreturns",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Sales Return. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "Sales Return - " . $objMarketingDocuments->bpcode;
			$objTable->setudfvalue("u_rtdocno",$objMarketingDocuments->docno);
		}	
	}	

	if ($actionReturn ) {
		$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];
		//$objRsItems->setdebug();
		$objRsItems->queryopen("select c.U_ISSTAT, c.U_ITEMGROUP, c.U_ITEMCODE, c.U_ITEMDESC, c.U_DISCAMOUNT, c.U_STATUNITPRICE, c.U_UNITPRICE, c.U_PRICE, c.U_QUANTITY, c.U_VATCODE, c.U_VATRATE, c.U_VATAMOUNT, c.U_LINETOTAL, b.MANAGEBY, b.U_PROFITCENTER, b.MANAGEBYMETHOD  from u_hischargeitems c, items b where b.itemcode=c.u_itemcode and c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if (!$delete) {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesdeliveryitems",$objConnection);
			} else {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesreturnitems",$objConnection);
			}	
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			if ($objTable->getudfvalue("u_disconbill")=="0" || $objRsItems->fields["U_ITEMGROUP"]=="PRF" || $objRsItems->fields["U_ITEMGROUP"]=="PRM") {
				if ($objRsItems->fields["U_ISSTAT"]==0) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
				}	
				$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
			} else {
				if ($objRsItems->fields["U_ISSTAT"]==0) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
					$objMarketingDocumentItems->price = round($objRsItems->fields["U_UNITPRICE"],2);
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
					$objMarketingDocumentItems->price = round($objRsItems->fields["U_STATUNITPRICE"],2);
				}	
			}	
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
			$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
			$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
			$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
			$objMarketingDocumentItems->vatrate = $objRsItems->fields["U_VATRATE"];
			$objMarketingDocumentItems->vatamount = $objRsItems->fields["U_VATAMOUNT"];
			$objMarketingDocumentItems->drcode = iif($objRsItems->fields["U_PROFITCENTER"]!="",$objRsItems->fields["U_PROFITCENTER"],$objTable->getudfvalue("u_department"));
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
			if ((!$delete && $objTable->getudfvalue("u_stocklink")=="0") || ($delete && $objTable->getudfvalue("u_cancelstocklink")=="0")) {
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->dropship = 1;
			} else {
				$objMarketingDocumentItems->whscode = $objTable->getudfvalue("u_department");
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
				    if ($objMarketingDocuments->objectcode == "ARCREDITMEMO" || $objMarketingDocuments->objectcode == "SALESRETURN") {
						$objMarketingDocumentItems->sbnids = "0|NONE|" . $sbqty . "|u_expdate|"; 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					} else {
						$sbqty2 = $sbqty;
						$batchstatus = "";
						$batchnos = "";
						$batchqtys = "";
						$batchmfgs = "";
						$batchexpiries = "";
						$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
						while ($objRsManageBy->queryfetchrow("NAME")) {
							if ($objRsManageBy->fields["QTY"]==0) continue;
							if ($batchstatus!="") {
								$batchstatus .= "`";
								$batchnos .= "`";
								$batchqtys .= "`";
								$batchmfgs .= "`";
								$batchexpiries .= "`";
							}
							$batchstatus .= "1";
							$batchnos .= $objRsManageBy->fields["BATCH"];
							if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
								$batchqtys .= $objRsManageBy->fields["QTY"];
								$sbqty2 -= $objRsManageBy->fields["QTY"];
							} else {
								$batchqtys .= $sbqty2;
								$sbqty2 = 0;
							}	
							//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
							$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
							
							if ($sbqty2<=0) break;
						}
						if ($sbqty2!=0) {
							$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
						} else {
							$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
							//. "|u_mfgdate|" . $batchmfgs 
							$objMarketingDocumentItems->sbncnt = $sbqty;
						}	
					}	
					if (!$actionReturn) break;
				}
			}	
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn ) {
	//$objRsItems->setdebug();
		$objRsItems->queryopen("select c.U_ITEMCODE, c.U_ITEMDESC, c.U_QUANTITY, b.U_PROFITCENTER, b.MANAGEBY, b.MANAGEBYMETHOD, b.TAXCODESA as U_VATCODE  from u_hischargeitempacks c, items b where b.itemcode=c.u_itemcode and c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if (!$delete) {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesdeliveryitems",$objConnection);
			} else {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesreturnitems",$objConnection);
			}	
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			$objMarketingDocumentItems->unitprice = 0;
			$objMarketingDocumentItems->price = 0;
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
			$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
			$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
			$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
			$objMarketingDocumentItems->vatrate = 0;
			$objMarketingDocumentItems->vatamount = 0;
			$objMarketingDocumentItems->drcode = iif($objRsItems->fields["U_PROFITCENTER"]!="",$objRsItems->fields["U_PROFITCENTER"],$objTable->getudfvalue("u_department"));
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
			if ((!$delete && $objTable->getudfvalue("u_stocklink")=="0") || ($delete && $objTable->getudfvalue("u_cancelstocklink")=="0")) {
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->dropship = 1;
			} else {
				$objMarketingDocumentItems->whscode =  $objTable->getudfvalue("u_department");
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					$sbqty2 = $sbqty;
					$batchstatus = "";
					$batchnos = "";
					$batchqtys = "";
					$batchmfgs = "";
					$batchexpiries = "";
					$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
					while ($objRsManageBy->queryfetchrow("NAME")) {
						if ($objRsManageBy->fields["QTY"]==0) continue;
						if ($batchstatus!="") {
							$batchstatus .= "`";
							$batchnos .= "`";
							$batchqtys .= "`";
							$batchmfgs .= "`";
							$batchexpiries .= "`";
						}
						$batchstatus .= "1";
						$batchnos .= $objRsManageBy->fields["BATCH"];
						if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
							$batchqtys .= $objRsManageBy->fields["QTY"];
							$sbqty2 -= $objRsManageBy->fields["QTY"];
						} else {
							$batchqtys .= $sbqty2;
							$sbqty2 = 0;
						}	
						//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
						$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
						
						if ($sbqty2<=0) break;
					}
					if ($sbqty2!=0) {
						$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
					} else {
						$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
						//. "|u_mfgdate|" . $batchmfgs 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					}	
				}
			}	
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}			
	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
		//var_dump($objMarketingDocuments->dueamount);
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments->docstatus = iif($objMarketingDocuments->dueamount==0,"C","O");
		} else $objMarketingDocuments->docstatus = "C";	
		$objMarketingDocuments->settledamount = 0;
		$actionReturn = $objMarketingDocuments->add();
		
		if ($actionReturn && $objTable->getudfvalue("u_prepaid")!="1") {
			$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
			$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
			$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
			if (!$delete) $bpdata["balance"] = $objMarketingDocuments->dueamount;
			else $bpdata["balance"] = $objMarketingDocuments->dueamount*-1;
			$actionReturn = updatecustomerbalance($bpdata);
		}			
		
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostChargesGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostCreditsGPSHIS($objTable,$delete=false,$refund=false) {
	global $objConnection;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_prepaid")=="2" && $objTable->getudfvalue("u_requesttype")=="REQ") return true;
	
	$custno = $objTable->getudfvalue("u_patientid");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	
	$objTable->setudfvalue("u_stocklink",0);
	if ($objTable->getudfvalue("u_requesttype")!="REQ") {
		$objRs->queryopen("select u_stocklink from u_hissections where code='".$objTable->getudfvalue("u_department")."'");
		if ($objRs->queryfetchrow("NAME")) $objTable->setudfvalue("u_stocklink",$objRs->fields["u_stocklink"]);
	}	
	
	if (!$delete) {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARCREDITMEMOS");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARCREDITMEMOITEMS");
		$settings = getBusinessObjectSettings("ARCREDITMEMO");
	} else {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARINVOICES");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARINVOICEITEMS");
		$settings = getBusinessObjectSettings("ARINVOICE");
	}	
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $custno;
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->currency = $customerdata["CURRENCY"];
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_refno");
	if (!$delete) {
		$objMarketingDocuments->objectcode = "ARCREDITMEMO";
		$objMarketingDocuments->docid = getNextIdByBranch("arcreditmemos",$objConnection);
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Credit Memo. ";// . $objTable->getudfvalue("u_remarks");
		$objMarketingDocuments->journalremark = "A/R Credit Memo - " . $objMarketingDocuments->bpcode;
		$objTable->setudfvalue("u_arcmdocno",$objMarketingDocuments->docno);
	} else {
		$objMarketingDocuments->objectcode = "ARINVOICE";
		$objMarketingDocuments->docid = getNextIdByBranch("arinvoices",$objConnection);
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Invoice. ";// . $objTable->getudfvalue("u_remarks");
		$objMarketingDocuments->journalremark = "A/R Invoice - " . $objMarketingDocuments->bpcode;
		$objTable->setudfvalue("u_ardocno",$objMarketingDocuments->docno);
	}	

	if ($actionReturn ) {
		$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];
		//$objRsItems->setdebug();
		$objRsItems->queryopen("select c.U_ISSTAT, c.U_ITEMGROUP, c.U_ITEMCODE, c.U_ITEMDESC, c.U_DISCAMOUNT, c.U_STATUNITPRICE, c.U_UNITPRICE, c.U_PRICE, c.U_QUANTITY, c.U_VATCODE, c.U_VATRATE, c.U_VATAMOUNT, c.U_LINETOTAL, b.U_PROFITCENTER, b.MANAGEBY, b.MANAGEBYMETHOD  from u_hiscredititems c, items b where b.itemcode=c.u_itemcode and c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if (!$delete) {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
			} else {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
			}	
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			if ($objTable->getudfvalue("u_disconbill")=="0" || $objRsItems->fields["U_ITEMGROUP"]=="PRF" || $objRsItems->fields["U_ITEMGROUP"]=="PRM") {
				if ($objRsItems->fields["U_ISSTAT"]==0) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
				}	
				$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
			} else {
				if ($objRsItems->fields["U_ISSTAT"]==0) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
					$objMarketingDocumentItems->price = round($objRsItems->fields["U_UNITPRICE"],2);
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
					$objMarketingDocumentItems->price = round($objRsItems->fields["U_STATUNITPRICE"],2);
				}	
			}	
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
			$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
			$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
			if ($objTable->getudfvalue("u_payreftype")=="CM") {
				$objMarketingDocumentItems->vatcode = "VATOX";
				$objMarketingDocumentItems->vatrate = 0;
				$objMarketingDocumentItems->vatamount = 0;
			} else {
				$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
				$objMarketingDocumentItems->vatrate = $objRsItems->fields["U_VATRATE"];
				$objMarketingDocumentItems->vatamount = $objRsItems->fields["U_VATAMOUNT"];
				if ($objTable->getudfvalue("u_scdisc")==1) {
					$objMarketingDocumentItems->linetotal = $objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount;
					$objMarketingDocumentItems->price = $objMarketingDocumentItems->linetotal/$objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
					$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
					$objMarketingDocumentItems->vatcode = "VATOX";
					$objMarketingDocumentItems->vatrate = 0;
					$objMarketingDocumentItems->vatamount = 0;
				}
				
			}	
			$objMarketingDocumentItems->drcode = iif($objRsItems->fields["U_PROFITCENTER"]!="",$objRsItems->fields["U_PROFITCENTER"],$objTable->getudfvalue("u_department"));
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
			if ($objTable->getudfvalue("u_stocklink")=="0") {
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->dropship = 1;
			} else {
				$objMarketingDocumentItems->whscode = $objTable->getudfvalue("u_department");
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					if ($objMarketingDocuments->objectcode == "ARCREDITMEMO") {
						$objMarketingDocumentItems->sbnids = "0|NONE|" . $sbqty . "|u_expdate|"; 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					} else {
						$sbqty2 = $sbqty;
						$batchstatus = "";
						$batchnos = "";
						$batchqtys = "";
						$batchmfgs = "";
						$batchexpiries = "";
						$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
						while ($objRsManageBy->queryfetchrow("NAME")) {
							if ($objRsManageBy->fields["QTY"]==0) continue;
							if ($batchstatus!="") {
								$batchstatus .= "`";
								$batchnos .= "`";
								$batchqtys .= "`";
								$batchmfgs .= "`";
								$batchexpiries .= "`";
							}
							$batchstatus .= "1";
							$batchnos .= $objRsManageBy->fields["BATCH"];
							if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
								$batchqtys .= $objRsManageBy->fields["QTY"];
								$sbqty2 -= $objRsManageBy->fields["QTY"];
							} else {
								$batchqtys .= $sbqty2;
								$sbqty2 = 0;
							}	
							//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
							$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
							
							if ($sbqty2<=0) break;
						}
						if ($sbqty2!=0) {
							$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
						} else {
							$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
							//. "|u_mfgdate|" . $batchmfgs 
							$objMarketingDocumentItems->sbncnt = $sbqty;
						}	
					}	
				}
			}	
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn ) {
	//$objRsItems->setdebug();
		$objRsItems->queryopen("select c.U_ITEMCODE, c.U_ITEMDESC, c.U_QUANTITY, b.U_PROFITCENTER, b.MANAGEBY, b.MANAGEBYMETHOD, b.TAXCODESA as U_VATCODE  from  u_hiscredititempacks c, items b where b.itemcode=c.u_itemcode and c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if (!$delete) {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
			} else {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
			}	
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			$objMarketingDocumentItems->unitprice = 0;
			$objMarketingDocumentItems->price = 0;
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
			$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
			$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
			$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
			$objMarketingDocumentItems->vatrate = 0;
			$objMarketingDocumentItems->vatamount = 0;
			$objMarketingDocumentItems->drcode = iif($objRsItems->fields["U_PROFITCENTER"]!="",$objRsItems->fields["U_PROFITCENTER"],$objTable->getudfvalue("u_department"));
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
			if ($objTable->getudfvalue("u_stocklink")=="0") {
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->dropship = 1;
			} else {
				$objMarketingDocumentItems->whscode = $objTable->getudfvalue("u_department");
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					$sbqty2 = $sbqty;
					$batchstatus = "";
					$batchnos = "";
					$batchqtys = "";
					$batchmfgs = "";
					$batchexpiries = "";
					$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
					while ($objRsManageBy->queryfetchrow("NAME")) {
						if ($objRsManageBy->fields["QTY"]==0) continue;
						if ($batchstatus!="") {
							$batchstatus .= "`";
							$batchnos .= "`";
							$batchqtys .= "`";
							$batchmfgs .= "`";
							$batchexpiries .= "`";
						}
						$batchstatus .= "1";
						$batchnos .= $objRsManageBy->fields["BATCH"];
						if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
							$batchqtys .= $objRsManageBy->fields["QTY"];
							$sbqty2 -= $objRsManageBy->fields["QTY"];
						} else {
							$batchqtys .= $sbqty2;
							$sbqty2 = 0;
						}	
						//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
						$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
						
						if ($sbqty2<=0) break;
					}
					if ($sbqty2!=0) {
						$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
					} else {
						$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
						//. "|u_mfgdate|" . $batchmfgs 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					}	
				}
			}	
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	
	$discamount = ($objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount) - floatval($objTable->getudfvalue("u_amount"));
	if ($objTable->getudfvalue("u_disccode")!="" && $objTable->getudfvalue("u_disconbill")=="1" && $objTable->getudfvalue("u_prepaid")=="1" && $discamount!=0) {
		$itemdata = getitemsdata($objTable->getudfvalue("u_disccode"),"ITEMCODE,ITEMDESC,TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
		if ($itemdata["ITEMCODE"]=="") return raiseError("Unable to find discount [".$objTable->getudfvalue("u_disccode")."] in item master data.");
		$objMarketingDocumentItems->prepareadd();
		$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
		$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
		$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
		if (!$delete) {
			$objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
		} else {
			$objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
		}	
		$objMarketingDocumentItems->itemcode = $itemdata["ITEMCODE"];
		$objMarketingDocumentItems->itemdesc = $itemdata["ITEMDESC"];
		$objMarketingDocumentItems->quantity = 1;
		$objMarketingDocumentItems->unitprice = $discamount*-1;
		$objMarketingDocumentItems->price = $objMarketingDocumentItems->unitprice;
		$objMarketingDocumentItems->discamount = 0;
		$objMarketingDocumentItems->discperc = 0;
		$objMarketingDocumentItems->linetotal = $objMarketingDocumentItems->unitprice;
		$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
		$objMarketingDocumentItems->vatrate = 0;
		$objMarketingDocumentItems->vatamount = 0;
		$objMarketingDocumentItems->drcode = iif($itemdata["U_PROFITCENTER"]!="",$itemdata["U_PROFITCENTER"],$objTable->getudfvalue("u_department"));
		$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
		$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
		$objMarketingDocumentItems->whscode = "DROPSHIP";
		$objMarketingDocumentItems->dropship = 1;
		$objMarketingDocumentItems->linestatus = "O";
		$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
		$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
		$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
		
		$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
		$actionReturn = $objMarketingDocumentItems->add();	
	}
	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
		//var_dump($objMarketingDocuments->dueamount);
		$objMarketingDocuments->docstatus = iif($objMarketingDocuments->dueamount==0,"C","O");
		$objMarketingDocuments->settledamount = 0;
		$actionReturn = $objMarketingDocuments->add();
		
		if ($actionReturn) {
			$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
			$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
			$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
			if (!$delete) $bpdata["balance"] = $objMarketingDocuments->dueamount*-1;
			else $bpdata["balance"] = $objMarketingDocuments->dueamount;
			$actionReturn = updatecustomerbalance($bpdata);
		}			
		
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostCreditsGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostAdjustmentsGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	$custno = $objTable->getudfvalue("u_patientid");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	if ((!$delete && $objTable->getudfvalue("u_amount")<0) || ($delete && $objTable->getudfvalue("u_amount")>=0)) {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARCREDITMEMOS");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARCREDITMEMOITEMS");
		$settings = getBusinessObjectSettings("ARCREDITMEMO");
	} else {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARINVOICES");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARINVOICEITEMS");
		$settings = getBusinessObjectSettings("ARINVOICE");
	}	
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $custno;
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->currency = $customerdata["CURRENCY"];
	$objMarketingDocuments->doctype = "S";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_refno");
	if ((!$delete && $objTable->getudfvalue("u_amount")<0) || ($delete && $objTable->getudfvalue("u_amount")>=0)) {
		$objMarketingDocuments->objectcode = "ARCREDITMEMO";
		$objMarketingDocuments->docid = getNextIdByBranch("arcreditmemos",$objConnection);
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Credit Memo. ";// . $objTable->getudfvalue("u_remarks");
		$objMarketingDocuments->journalremark = "A/R Credit Memo - " . $objMarketingDocuments->bpcode;
		if (!$delete) $objTable->setudfvalue("u_arcmdocno",$objMarketingDocuments->docno);
		else $objTable->setudfvalue("u_arcmcndocno",$objMarketingDocuments->docno);
	} else {
		$objMarketingDocuments->objectcode = "ARINVOICE";
		$objMarketingDocuments->docid = getNextIdByBranch("arinvoices",$objConnection);
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Invoice. ";// . $objTable->getudfvalue("u_remarks");
		$objMarketingDocuments->journalremark = "A/R Invoice - " . $objMarketingDocuments->bpcode;
		if (!$delete) $objTable->setudfvalue("u_ardocno",$objMarketingDocuments->docno);
		else $objTable->setudfvalue("u_arcndocno",$objMarketingDocuments->docno);
	}	

	if ($actionReturn ) {
		$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];
		//$objRsItems->setdebug();
		$objRsItems->queryopen("select c.U_ITEMCODE, c.U_ITEMDESC, c.U_LINETOTAL, b.U_PROFITCENTER from u_hispriceadjitems c, items b where b.itemcode=c.u_itemcode and c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			if ((!$delete && $objTable->getudfvalue("u_amount")<0) || ($delete && $objTable->getudfvalue("u_amount")>=0)) {
				$gldata = getItemCodeGLAcctNo($_SESSION["branch"],$objRsItems->fields["U_ITEMCODE"],$objTable->getudfvalue("u_department"),"SALESCREDITACCT");
				if ($gldata["formatcode"]=="") return raiseError("Sales Credit Account is not maintained for item [".$objRsItems->fields["U_ITEMCODE"]."/".$objRsItems->fields["U_ITEMDESC"]."/".$objTable->getudfvalue("u_department")."].");
			} else {
				$gldata = getItemCodeGLAcctNo($_SESSION["branch"],$objRsItems->fields["U_ITEMCODE"],$objTable->getudfvalue("u_department"),"REVENUEACCT");
				if ($gldata["formatcode"]=="") return raiseError("Revenue Account is not maintained for item [".$objRsItems->fields["U_ITEMCODE"]."/".$objRsItems->fields["U_ITEMDESC"]."/".$objTable->getudfvalue("u_department")."].");
			}	
			
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if ((!$delete && $objTable->getudfvalue("u_amount")<0) || ($delete && $objTable->getudfvalue("u_amount")>=0)) {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
				if (!$delete) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_LINETOTAL"]*-1;
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_LINETOTAL"];
				}	
			} else {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
				if ($delete) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_LINETOTAL"]*-1;
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_LINETOTAL"];
				}	
			}	
			$objMarketingDocumentItems->glacctno = $gldata["formatcode"];
			$objMarketingDocumentItems->glacctname = $gldata["acctname"];
			$objMarketingDocumentItems->itemdesc = $gldata["acctname"];
			$objMarketingDocumentItems->price = $objMarketingDocumentItems->unitprice;
			$objMarketingDocumentItems->discamount = 0;
			$objMarketingDocumentItems->discperc = 0;
			$objMarketingDocumentItems->linetotal = $objMarketingDocumentItems->unitprice;
			$objMarketingDocumentItems->vatcode = "VATOX";
			$objMarketingDocumentItems->vatrate = 0;
			$objMarketingDocumentItems->vatamount = 0;
			$objMarketingDocumentItems->drcode = iif($objRsItems->fields["U_PROFITCENTER"]!="",$objRsItems->fields["U_PROFITCENTER"],$objTable->getudfvalue("u_department"));
			$objMarketingDocumentItems->whscode = "DROPSHIP";
			$objMarketingDocumentItems->dropship = 1;
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	
	$discamount = ($objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount) - floatval($objTable->getudfvalue("u_amount"));
	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
		//var_dump($objMarketingDocuments->dueamount);
		$objMarketingDocuments->docstatus = iif($objMarketingDocuments->dueamount==0,"C","O");
		$objMarketingDocuments->settledamount = 0;
		$actionReturn = $objMarketingDocuments->add();
		
		if ($actionReturn) {
			$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
			$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
			$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
			if ((!$delete && $objTable->getudfvalue("u_amount")<0) || ($delete && $objTable->getudfvalue("u_amount")>=0)) $bpdata["balance"] = $objMarketingDocuments->dueamount*-1;
			else $bpdata["balance"] = $objMarketingDocuments->dueamount;
			$actionReturn = updatecustomerbalance($bpdata);
		}			
		
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostAdjustmentsGPSHIS");
			
	return $actionReturn;
}

/*
function onCustomEventdocumentschema_brPostCreditsGPSHIS($objTable,$delete=false,$refund=false) {
	global $objConnection;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_prepaid")=="2" && $objTable->getudfvalue("u_requesttype")=="REQ") return true;
	
	$custno = $objTable->getudfvalue("u_patientid");
	if ($custno=="") {
		$branchdata = getcurrentbranchdata("POSCUSTNO");
		$custno = $branchdata["POSCUSTNO"];
	}	
	
	$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	if (!$delete) {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARCREDITMEMOS");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARCREDITMEMOITEMS");
			$settings = getBusinessObjectSettings("ARCREDITMEMO");
		} else {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"SALESRETURNS");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"SALESRETURNITEMS");
			$settings = getBusinessObjectSettings("SALESRETURN");
		}	
	} else {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARINVOICES");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARINVOICEITEMS");
			$settings = getBusinessObjectSettings("ARINVOICE");
		} else {
			$objMarketingDocuments = new marketingdocuments(null,$objConnection,"SALESDELIVERIES");
			$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"SALESDELIVERYITEMS");
			$settings = getBusinessObjectSettings("SALESDELIVERY");
		}	
	}	
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->bpcode = $custno;
	$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
	$objMarketingDocuments->currency = $customerdata["CURRENCY"];
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_startdate");
	$objMarketingDocuments->bprefno = $objTable->getudfvalue("u_refno");
	if (!$delete) {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments->objectcode = "ARCREDITMEMO";
			$objMarketingDocuments->docid = getNextIdByBranch("arcreditmemos",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Credit Memo. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "A/R Credit Memo - " . $objMarketingDocuments->bpcode;
		} else {
			$objMarketingDocuments->objectcode = "SALESRETURN";
			$objMarketingDocuments->docid = getNextIdByBranch("salesreturns",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Sales Return. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "Sales Return - " . $objMarketingDocuments->bpcode;
		}	
	} else {
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments->objectcode = "ARINVOICE";
			$objMarketingDocuments->docid = getNextIdByBranch("arinvoices",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " A/R Invoice. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "A/R Invoice - " . $objMarketingDocuments->bpcode;
		} else {
			$objMarketingDocuments->objectcode = "SALESDELIVERY";
			$objMarketingDocuments->docid = getNextIdByBranch("salesdeliveries",$objConnection);
			$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Delivery. ";// . $objTable->getudfvalue("u_remarks");
			$objMarketingDocuments->journalremark = "Delivery - " . $objMarketingDocuments->bpcode;
		}	
	}	

	if ($actionReturn ) {
		$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];
		//$objRsItems->setdebug();
		$objRsItems->queryopen("select a.U_DEPARTMENT, a.U_ISSTAT, c.U_ITEMCODE, c.U_ITEMDESC, c.U_DISCAMOUNT, c.U_STATUNITPRICE, c.U_UNITPRICE, c.U_PRICE, c.U_QUANTITY, c.U_VATCODE, c.U_VATRATE, c.U_VATAMOUNT, c.U_LINETOTAL, b.MANAGEBY, b.MANAGEBYMETHOD  from u_hiscredits a, u_hiscredititems c, items b where b.itemcode=c.u_itemcode and c.company=a.company and c.branch=a.branch and c.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$objTable->docno."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if (!$delete) {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesreturnitems",$objConnection);
			} else {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesdeliveryitems",$objConnection);
			}	
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			if ($objTable->getudfvalue("u_disconbill")=="0") {
				if ($objRsItems->fields["U_ISSTAT"]==0) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
				}	
				$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
			} else {
				if ($objRsItems->fields["U_ISSTAT"]==0) {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_UNITPRICE"];
				} else {
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_STATUNITPRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_STATUNITPRICE"];
				}	
			}	
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
			$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
			$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
			$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
			$objMarketingDocumentItems->vatrate = $objRsItems->fields["U_VATRATE"];
			$objMarketingDocumentItems->vatamount = $objRsItems->fields["U_VATAMOUNT"];
			$objMarketingDocumentItems->drcode = $objRsItems->fields["U_DEPARTMENT"];
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
			if ($objTable->getudfvalue("u_stocklink")=="0") {
				$objMarketingDocumentItems->whscode = "DROPSHIP";
			} else {
				$objMarketingDocumentItems->whscode = $objRsItems->fields["U_DEPARTMENT"];
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					$sbqty2 = $sbqty;
					$batchstatus = "";
					$batchnos = "";
					$batchqtys = "";
					$batchmfgs = "";
					$batchexpiries = "";
					$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
					while ($objRsManageBy->queryfetchrow("NAME")) {
						if ($objRsManageBy->fields["QTY"]==0) continue;
						if ($batchstatus!="") {
							$batchstatus .= "`";
							$batchnos .= "`";
							$batchqtys .= "`";
							$batchmfgs .= "`";
							$batchexpiries .= "`";
						}
						$batchstatus .= "1";
						$batchnos .= $objRsManageBy->fields["BATCH"];
						if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
							$batchqtys .= $objRsManageBy->fields["QTY"];
							$sbqty2 -= $objRsManageBy->fields["QTY"];
						} else {
							$batchqtys .= $sbqty2;
							$sbqty2 = 0;
						}	
						//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
						$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
						
						if ($sbqty2<=0) break;
					}
					if ($sbqty2!=0) {
						$actionReturn = raiseError("Incomplete Batch qty selection [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
					} else {
						$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
						//. "|u_mfgdate|" . $batchmfgs 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					}	
				}
			}	
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn ) {
	//$objRsItems->setdebug();
		$objRsItems->queryopen("select a.U_DEPARTMENT, c.U_ITEMCODE, c.U_ITEMDESC, c.U_QUANTITY, b.MANAGEBY, b.MANAGEBYMETHOD, b.TAXCODESA as U_VATCODE  from u_hiscredits a, u_hiscredititempacks c, items b where b.itemcode=c.u_itemcode and c.company=a.company and c.branch=a.branch and c.docid=a.docid and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$objTable->docno."'");
		//var_dump($objRsItems->sqls);
		while ($objRsItems->queryfetchrow("NAME")) {
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if (!$delete) {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesreturnitems",$objConnection);
			} else {
				if ($objTable->getudfvalue("u_prepaid")!="1") $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("salesdeliveryitems",$objConnection);
			}	
			$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
			$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
			$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
			$objMarketingDocumentItems->unitprice = 0;
			$objMarketingDocumentItems->price = 0;
			$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->unitprice - $objMarketingDocumentItems->price;
			$objMarketingDocumentItems->discperc = round(1-($objMarketingDocumentItems->price/$objMarketingDocumentItems->unitprice),2);
			$objMarketingDocumentItems->linetotal = round($objMarketingDocumentItems->price * $objMarketingDocumentItems->quantity,2);
			$objMarketingDocumentItems->vatcode = $objRsItems->fields["U_VATCODE"];
			$objMarketingDocumentItems->vatrate = 0;
			$objMarketingDocumentItems->vatamount = 0;
			$objMarketingDocumentItems->drcode = $objRsItems->fields["U_DEPARTMENT"];
			$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
			$objMarketingDocumentItems->itemmanageby = $objRsItems->fields["MANAGEBY"];
			if ($objTable->getudfvalue("u_stocklink")=="0") {
				$objMarketingDocumentItems->whscode = "DROPSHIP";
			} else {
				$objMarketingDocumentItems->whscode = $objRsItems->fields["U_DEPARTMENT"];
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					$sbqty2 = $sbqty;
					$batchstatus = "";
					$batchnos = "";
					$batchqtys = "";
					$batchmfgs = "";
					$batchexpiries = "";
					$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
					while ($objRsManageBy->queryfetchrow("NAME")) {
						if ($objRsManageBy->fields["QTY"]==0) continue;
						if ($batchstatus!="") {
							$batchstatus .= "`";
							$batchnos .= "`";
							$batchqtys .= "`";
							$batchmfgs .= "`";
							$batchexpiries .= "`";
						}
						$batchstatus .= "1";
						$batchnos .= $objRsManageBy->fields["BATCH"];
						if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
							$batchqtys .= $objRsManageBy->fields["QTY"];
							$sbqty2 -= $objRsManageBy->fields["QTY"];
						} else {
							$batchqtys .= $sbqty2;
							$sbqty2 = 0;
						}	
						//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
						$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
						
						if ($sbqty2<=0) break;
					}
					if ($sbqty2!=0) {
						$actionReturn = raiseError("Incomplete Batch qty selection [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
					} else {
						$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
						//. "|u_mfgdate|" . $batchmfgs 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					}	
				}
			}	
			$objMarketingDocumentItems->linestatus = "O";
			$objMarketingDocuments->totalquantity += $objMarketingDocumentItems->quantity;
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentItems->add();
			if (!$actionReturn) break;
		}
	}			
	if ($actionReturn) {
		$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
		$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
		//var_dump($objMarketingDocuments->dueamount);
		if ($objTable->getudfvalue("u_prepaid")!="1") {
			$objMarketingDocuments->docstatus = iif($objMarketingDocuments->dueamount==0,"C","O");
		} else $objMarketingDocuments->docstatus = "C";	
		$objMarketingDocuments->settledamount = 0;
		$actionReturn = $objMarketingDocuments->add();
		
		if ($actionReturn && $objTable->getudfvalue("u_prepaid")!="1") {
			$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
			$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
			$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
			if (!$delete) $bpdata["balance"] = $objMarketingDocuments->dueamount*-1;
			else $bpdata["balance"] = $objMarketingDocuments->dueamount;
			$actionReturn = updatecustomerbalance($bpdata);
		}			
		
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostCreditsGPSHIS");
			
	return $actionReturn;
}

*/

function onCustomEventdocumentschema_brPostStockTransferGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;

	$objGoodsIssues = new stocktransfers("STOCKTRANSFERS",$objConnection);
	$objGoodsIssueItems = new stocktransferitems("STOCKTRANSFERITEMS",$objConnection);
	$objRs = new recordset(NULL,$objConnection);
	$objRsItems = new recordset(NULL,$objConnection);
	$objRsManageBy = new recordset(NULL,$objConnection);

	$settings = getBusinessObjectSettings("STOCKTRANSFER");	

	$objGoodsIssues->prepareadd();
	$objGoodsIssues->objectcode = "STOCKTRANSFER";
	
	$objGoodsIssues->sbo_post_flag = $settings["autopost"];
	$objGoodsIssues->jeposting = $settings["jeposting"];
	if (strtolower($objTable->dbtable)=="u_hismedsuptfins" || strtolower($objTable->dbtable)=="u_hismedsupstocktfins") {
		$objGoodsIssues->docdate = $objTable->getudfvalue("u_tfindate");
	} else {
		$objGoodsIssues->docdate = $objTable->getudfvalue("u_tfdate");
	}	
	$objGoodsIssues->taxdate = $objGoodsIssues->docdate;
	$objGoodsIssues->docduedate = $objGoodsIssues->docdate;
	//$objGoodsReceipts->docseries = getDefaultSeries($objGoodsReceipts->objectcode,$objConnection); 
	$objGoodsIssues->docseries = -1;
	$objGoodsIssues->docstatus = "C";
	$objGoodsIssues->docid = getNextIdByBranch("stocktransfers",$objConnection);
	if (strtolower($objTable->dbtable)=="u_hismedsuptfins" || strtolower($objTable->dbtable)=="u_hismedsupstocktfins") {
		$objGoodsIssues->docno = $objTable->getudfvalue("u_tfno");
	} else {
		$objGoodsIssues->docno = $objTable->docno;
	}	
	//$objGoodsReceipts->docno = getNextSeriesNoByBranch($objGoodsReceipts->objectcode,$objGoodsReceipts->docseries,'',$objConnection,true);
	$objGoodsIssues->fromwhscode = $objTable->getudfvalue("u_fromdepartment");
	$objGoodsIssues->remarks = $objTable->getudfvalue("u_remarks");
	$objGoodsIssues->journalremark = "Stock Transfer";

	$strSQL = "SELECT A.MANAGEBYMETHOD, A.DROPSHIP FROM WAREHOUSES A WHERE A.BRANCH='".$_SESSION["branch"]."' and A.WAREHOUSE='$objGoodsIssues->fromwhscode'";
	$objRs->queryopen($strSQL);
	if ($objRs->queryfetchrow('NAME')) {
		$objGoodsIssues->dropship = $objRs->fields['DROPSHIP'];
		$objGoodsIssues->whsmanagebymethod = $objRs->fields['MANAGEBYMETHOD'];
	} else {
		return raiseError("[".$objGoodsIssues->docno."]:Invalid From Warehouse [".$_SESSION["branch"]."/$objGoodsIssues->fromwhscode].");
	}
	if (strtolower($objTable->dbtable)=="u_hismedsupstocktfins") {	
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_PRICE, a.U_UOM, a.U_QUANTITY, a.U_LINETOTAL  from u_hismedsupstocktfinitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
	} elseif (strtolower($objTable->dbtable)=="u_hismedsupstocktfs") {	
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_PRICE, a.U_UOM, a.U_QUANTITY, a.U_LINETOTAL  from u_hismedsupstocktfitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
	} elseif (strtolower($objTable->dbtable)=="u_hismedsuptfins") {	
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_PRICE, a.U_UOM, a.U_QUANTITY, a.U_LINETOTAL  from u_hismedsuptfinitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
	} elseif (strtolower($objTable->dbtable)=="u_hismedsupstocktfs") {	
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_PRICE, a.U_UOM, a.U_QUANTITY, a.U_LINETOTAL  from u_hismedsuptfitems a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
	} else return raiseError("Invalid table for stock transfer[".$objTable->dbtable."].");	
	while ($objRsItems->queryfetchrow("NAME")) {
		$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
		$objGoodsIssueItems->prepareadd();
		$objGoodsIssueItems->itemcode = $objRsItems->fields["U_ITEMCODE"]; 
		$objGoodsIssueItems->itemdesc = $objRsItems->fields["U_ITEMDESC"]; 
		$objGoodsIssueItems->quantity = $objRsItems->fields["U_QUANTITY"]; 
		$objGoodsIssueItems->uom = $objRsItems->fields["U_UOM"]; 
		$objGoodsIssueItems->numperuom = 1;
		$objGoodsIssueItems->openquantity = $objGoodsIssueItems->quantity; 
		$objGoodsIssueItems->unitprice = $objRsItems->fields["U_UNITPRICE"]; 
		$objGoodsIssueItems->price = $objRsItems->fields["U_PRICE"]; 
		objutils::AddBy($objGoodsIssueItems->linetotal,$objGoodsIssueItems->quantity * $objGoodsIssueItems->price);
		$objGoodsIssueItems->whscode = $objTable->getudfvalue("u_todepartment");
		
		$objGoodsIssueItems->itemmanageby = $itemdata["MANAGEBY"];
		$objGoodsIssueItems->itemmanagebymethod = $itemdata["MANAGEBYMETHOD"];

		$strSQL = "SELECT A.MANAGEBYMETHOD, A.DROPSHIP FROM WAREHOUSES A WHERE A.BRANCH='".$_SESSION["branch"]."' and A.WAREHOUSE='$objGoodsIssueItems->whscode'";
		$objRs->queryopen($strSQL);
		if ($objRs->queryfetchrow('NAME')) {
			$objGoodsIssueItems->dropship = $objRs->fields['DROPSHIP'];
			$objGoodsIssueItems->whsmanagebymethod = $objRs->fields['MANAGEBYMETHOD'];
		} else {
			$actionReturn = raiseError("[".$objGoodsIssues->docno."]:Invalid To Warehouse [".$_SESSION["branch"]."/$objGoodsIssueItems->whscode].");
			break;
		}
			
		$objGoodsIssueItems->sbnids = "";
		$objGoodsIssueItems->sbncnt = 0;
		if ($objGoodsIssueItems->itemmanageby=="1") {
			$sbqty = $objGoodsIssueItems->quantity * $objGoodsIssueItems->numperuom;
			$sbqty2 = $sbqty;
			$batchstatus = "";
			$batchnos = "";
			$batchqtys = "";
			$batchmfgs = "";
			$batchexpiries = "";
			$objRsManageBy->setdebug();
			$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objGoodsIssueItems->itemcode' AND A.WAREHOUSE='$objGoodsIssues->fromwhscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
			while ($objRsManageBy->queryfetchrow("NAME")) {
				if ($objRsManageBy->fields["QTY"]==0) continue;
				if ($batchstatus!="") {
					$batchstatus .= "`";
					$batchnos .= "`";
					$batchqtys .= "`";
					$batchmfgs .= "`";
					$batchexpiries .= "`";
				}
				$batchstatus .= "1";
				$batchnos .= $objRsManageBy->fields["BATCH"];
				if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
					$batchqtys .= $objRsManageBy->fields["QTY"];
					$sbqty2 -= $objRsManageBy->fields["QTY"];
				} else {
					$batchqtys .= $sbqty2;
					$sbqty2 = 0;
				}	
				//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
				$batchexpiries .= formatDateToHttp($objRsManageBy->fields["U_EXPDATE"]);
				
				if ($sbqty2<=0) break;
			}
			if ($sbqty2!=0) {
				$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objGoodsIssueItems->itemcode].");
			} else {
				$objGoodsIssueItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
				//. "|u_mfgdate|" . $batchmfgs 
				$objGoodsIssueItems->sbncnt = $sbqty;
			}	
		}			
		//$objSalesItems->assignhttpdatafields($idx);
		$objGoodsIssueItems->lineid = getNextIdByBranch("stocktransferitems",$objConnection);
		$objGoodsIssueItems->docid = $objGoodsIssues->docid;
		$objGoodsIssueItems->doctype = "I";
		$objGoodsIssueItems->objectcode = $objGoodsIssues->objectcode;
		$objGoodsIssueItems->linestatus = "C";
		
		if ($actionReturn) {
			objutils::AddBy($objGoodsIssues->totalamount,$objGoodsIssueItems->linetotal);
			$objGoodsIssueItems->privatedata["header"] = $objGoodsIssues;
			$actionReturn = $objGoodsIssueItems->add();
			//var_dump(array($objGoodsIssueItems->itemcode,$objGoodsIssueItems->itemmanageby,$objGoodsIssueItems->quantity,$objGoodsIssueItems->sbncnt,$objGoodsIssueItems->sbnids,$actionReturn));
		}	
	
		if (!$actionReturn) break;
	}
	
	if ($actionReturn) $actionReturn = $objGoodsIssues->add();
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostStockTransferGPSHIS");
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostGoodsIssueGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	$objRsManageBy = new recordset(NULL,$objConnection);
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"SALESDELIVERIES");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"SALESDELIVERYITEMS");
	} else {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"GOODSISSUES");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"GOODSISSUEITEMS");
	}	

	$objGoodsReceipts = new marketingdocuments(null,$objConnection,"GOODSRECEIPTS");
	$objGoodsReceiptItems = new marketingdocumentitems(null,$objConnection,"GOODSRECEIPTITEMS");
	
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$settings = getBusinessObjectSettings("SALESDELIVERY");
	} else {
		$settings = getBusinessObjectSettings("GOODSISSUE");
	}	
	
	$objMarketingDocuments->prepareadd();
	$objMarketingDocuments->docseries = -1;
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objMarketingDocuments->objectcode = "SALESDELIVERY";
		$objMarketingDocuments->bpcode = "HO-POS";
		$objMarketingDocuments->bpname = "Cash Sales";
		$objMarketingDocuments->currency = "PHP";
	} else {
		$objMarketingDocuments->objectcode = "GOODSISSUE";
	}	
	$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
	$objMarketingDocuments->jeposting = $settings["jeposting"];
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objMarketingDocuments->docid = getNextIdByBranch("salesdeliveries",$objConnection);
	} else {
		$objMarketingDocuments->docid = getNextIdByBranch("goodsissues",$objConnection);
	}	

	$objMarketingDocuments->docno = $objTable->docno;
	$objMarketingDocuments->doctype = "I";
	
	$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
	$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docdate");
	$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
	if ($objTable->getudfvalue("u_fromdepartment")==$objTable->getudfvalue("u_todepartment")) {
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_type") . " " . $objTable->getudfvalue("u_todepartment") . ". " . $objTable->getudfvalue("u_remarks");
	} else {
		$objMarketingDocuments->remarks = $objTable->getudfvalue("u_type") . ". " . $objTable->getudfvalue("u_remarks");
	}	
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objMarketingDocuments->journalremark = "Cost of Sales - " . $objTable->getudfvalue("u_fromdepartment");
	} else {
		$objMarketingDocuments->journalremark = "Expense - " . $objTable->getudfvalue("u_fromdepartment");
	} 	
	$objMarketingDocuments->whsref = $objTable->getudfvalue("u_todepartment");

	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objGoodsReceipts = new marketingdocuments(null,$objConnection,"SALESRETURNS");
		$objGoodsReceiptItems = new marketingdocumentitems(null,$objConnection,"SALESRETURNITEMS");
	} else {
		$objGoodsReceipts = new marketingdocuments(null,$objConnection,"GOODSRECEIPTS");
		$objGoodsReceiptItems = new marketingdocumentitems(null,$objConnection,"GOODSRECEIPTITEMS");
	}	
	
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$settings = getBusinessObjectSettings("SALESRETURN");
	} else {
		$settings = getBusinessObjectSettings("GOODSRECEIPT");
	}	
	
	$objGoodsReceipts->prepareadd();
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objGoodsReceipts->objectcode = "SALESRETURN";
		$objGoodsReceipts->bpcode = "HO-POS";
		$objGoodsReceipts->bpname = "Cash Sales";
		$objGoodsReceipts->currency = "PHP";
	} else {
		$objGoodsReceipts->objectcode = "GOODSRECEIPT";
	}	
	$objGoodsReceipts->sbo_post_flag = $settings["autopost"];
	$objGoodsReceipts->jeposting = $settings["jeposting"];
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objGoodsReceipts->docid = getNextIdByBranch("salesreturns",$objConnection);
	} else {
		$objGoodsReceipts->docid = getNextIdByBranch("goodsreceipts",$objConnection);
	}	

	$objGoodsReceipts->docseries = getDefaultSeries($objGoodsReceipts->objectcode,$objConnection);
	$objGoodsReceipts->doctype = "I";
	
	$objGoodsReceipts->docdate = $objTable->getudfvalue("u_docdate");
	$objGoodsReceipts->docduedate = $objTable->getudfvalue("u_docdate");
	$objGoodsReceipts->taxdate = $objTable->getudfvalue("u_docdate");
	if ($objTable->getudfvalue("u_fromdepartment")==$objTable->getudfvalue("u_todepartment")) {
		$objGoodsReceipts->remarks = $objTable->getudfvalue("u_type") . " " . $objTable->getudfvalue("u_todepartment") . ". " . $objTable->getudfvalue("u_remarks");
	} else {
		$objGoodsReceipts->remarks = $objTable->getudfvalue("u_type") . ". " . $objTable->getudfvalue("u_remarks");
	}	
	if ($objTable->getudfvalue("u_type")=="Sales") {
		$objGoodsReceipts->journalremark = "Cost of Sales - " . $objTable->getudfvalue("u_fromdepartment");
	} else {
		$objGoodsReceipts->journalremark = "Expense - " . $objTable->getudfvalue("u_fromdepartment");
	} 	
	$objGoodsReceipts->whsref = $objTable->getudfvalue("u_todepartment");
			
	if ($actionReturn) {
		$gldata = array();
		$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_UNITPRICE, a.U_QUANTITY, a.U_LINETOTAL, a.U_TODEPARTMENT, IF(b.U_PROFITCENTER='', b.WAREHOUSE, b.U_PROFITCENTER) AS U_PROFITCENTER1, IF(c.U_PROFITCENTER='', c.WAREHOUSE, c.U_PROFITCENTER) AS U_PROFITCENTER2  from u_hismedsupstockitems a left join warehouses b on b.company='".$_SESSION["company"]."' and b.branch='".$_SESSION["branch"]."' and b.warehouse='".$objTable->getudfvalue("u_todepartment")."' left join warehouses c on c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.warehouse=a.u_todepartment where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
		while ($objRsItems->queryfetchrow("NAME")) {
			$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
			if ($objRsItems->fields["U_QUANTITY"]>0) {
				$objMarketingDocumentItems->prepareadd();
				$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
				$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
				$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
				if ($objTable->getudfvalue("u_type")=="Sales") {
					$objMarketingDocumentItems->lineid = getNextIdByBranch("salesdeliveryitems",$objConnection);
				} else {
					$objMarketingDocumentItems->lineid = getNextIdByBranch("goodsissueitems",$objConnection);
				}	
				$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
				$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
				$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
				$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
				$objMarketingDocumentItems->price = $objRsItems->fields["U_UNITPRICE"];
				$objMarketingDocumentItems->discamount = $objMarketingDocumentItems->price - $objMarketingDocumentItems->unitprice;
				$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
				//$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
				//$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
				//$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
				//if ($objTable->getudfvalue("u_multipledepartment")=="1") {
				//	$objMarketingDocumentItems->drcode = $objRsItems->fields["U_TODEPARTMENT"];;
				//} else $objMarketingDocumentItems->drcode = $objTable->getudfvalue("u_todepartment");
				if ($objTable->getudfvalue("u_multipledepartment")=="1") {
					$objMarketingDocumentItems->drcode = $objRsItems->fields["U_PROFITCENTER2"];
				} else $objMarketingDocumentItems->drcode = $objRsItems->fields["U_PROFITCENTER1"];
				
				$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
				$objMarketingDocumentItems->whscode = $objTable->getudfvalue("u_fromdepartment");
				$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
				$objMarketingDocumentItems->linestatus = "O";
				switch ($objTable->getudfvalue("u_type")) {
					case "Sales":
						$gldata = getItemGLAcctNo($_SESSION["branch"],$objRsItems->fields["U_ITEMCODE"],$objTable->getudfvalue("u_todepartment"),"COSTOFGOODSSOLDACCT");
						if ($gldata["formatcode"]=="") return raiseError("Cost of sales g/l account was not maintained for item[".$objRsItems->fields["U_ITEMCODE"]."]" );
						$objMarketingDocumentItems->glacctno = $gldata["formatcode"];
						break;
				}		
				if ($objTable->getudfvalue("u_type")=="Capitalize") {
					$objMarketingDocumentItems->setudfvalue("u_facapitalize","Y");
				}
				if ($objMarketingDocumentItems->itemmanageby=="1") {
					$sbqty = $objMarketingDocumentItems->quantity * $objMarketingDocumentItems->numperuom;
					$sbqty2 = $sbqty;
					$batchstatus = "";
					$batchnos = "";
					$batchqtys = "";
					$batchmfgs = "";
					$batchexpiries = "";
					$objRsManageBy->queryopen("SELECT A.BATCH, SUM(A.QTY) AS QTY,U_EXPDATE FROM BATCHTRXS A WHERE A.ITEMCODE = '$objMarketingDocumentItems->itemcode' AND A.WAREHOUSE='$objMarketingDocumentItems->whscode' GROUP BY A.BATCH ORDER BY A.U_EXPDATE,A.BATCH");
					while ($objRsManageBy->queryfetchrow("NAME")) {
						if ($objRsManageBy->fields["QTY"]==0) continue;
						if ($batchstatus!="") {
							$batchstatus .= "`";
							$batchnos .= "`";
							$batchqtys .= "`";
							$batchmfgs .= "`";
							$batchexpiries .= "`";
						}
						$batchstatus .= "1";
						$batchnos .= $objRsManageBy->fields["BATCH"];
						if ($objRsManageBy->fields["QTY"]<=$sbqty2) {
							$batchqtys .= $objRsManageBy->fields["QTY"];
							$sbqty2 -= $objRsManageBy->fields["QTY"];
						} else {
							$batchqtys .= $sbqty2;
							$sbqty2 = 0;
						}	
						//$batchmfgs .= $objRsManageBy->fields["U_MFGDATE"];
						$batchexpiries .= $objRsManageBy->fields["U_EXPDATE"];
						
						if ($sbqty2<=0) break;
					}
					if ($sbqty2!=0) {
						$actionReturn = raiseError("Not enough in-stock [".($sbqty - $sbqty2)."/$sbqty] for [$objMarketingDocumentItems->itemcode].");
					} else {
						$objMarketingDocumentItems->sbnids = $batchstatus . "|" . $batchnos  . "|" . $batchqtys . "|u_expdate|" . $batchexpiries; 
						//. "|u_mfgdate|" . $batchmfgs 
						$objMarketingDocumentItems->sbncnt = $sbqty;
					}	
				}
				$objMarketingDocuments->totalamount += $objMarketingDocumentItems->linetotal;
				
				$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
				if ($actionReturn) $actionReturn = $objMarketingDocumentItems->add();
			} else {
				$objGoodsReceiptItems->prepareadd();
				$objGoodsReceiptItems->objectcode = $objGoodsReceipts->objectcode;
				$objGoodsReceiptItems->docid = $objGoodsReceipts->docid;
				$objGoodsReceiptItems->doctype = $objGoodsReceipts->doctype;
				if ($objTable->getudfvalue("u_type")=="Sales") {
					$objGoodsReceiptItems->lineid = getNextIdByBranch("salesreturnitems",$objConnection);
				} else {
					$objGoodsReceiptItems->lineid = getNextIdByBranch("goodsreceiptitems",$objConnection);
				}	
				$objGoodsReceiptItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
				$objGoodsReceiptItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
				$objGoodsReceiptItems->quantity = $objRsItems->fields["U_QUANTITY"]*-1;
				$objGoodsReceiptItems->unitprice = $objRsItems->fields["U_UNITPRICE"];
				$objGoodsReceiptItems->price = $objRsItems->fields["U_UNITPRICE"];
				$objGoodsReceiptItems->discamount = $objGoodsReceiptItems->price - $objGoodsReceiptItems->unitprice;
				$objGoodsReceiptItems->linetotal = $objRsItems->fields["U_LINETOTAL"]*-1;
				//$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
				//$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
				//$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
				$objGoodsReceiptItems->drcode = $objTable->getudfvalue("u_todepartment");
				$objGoodsReceiptItems->openquantity = $objGoodsReceiptItems->quantity;
				$objGoodsReceiptItems->whscode = $objTable->getudfvalue("u_fromdepartment");
				$objGoodsReceiptItems->itemmanageby = $itemdata["MANAGEBY"];
				$objGoodsReceiptItems->linestatus = "O";
				switch ($objTable->getudfvalue("u_type")) {
					case "Sales":
						$gldata = getItemGLAcctNo($_SESSION["branch"],$objRsItems->fields["U_ITEMCODE"],$objTable->getudfvalue("u_todepartment"),"COSTOFGOODSSOLDACCT");
						if ($gldata["formatcode"]=="") return raiseError("Cost of sales g/l account was not maintained for item[".$objRsItems->fields["U_ITEMCODE"]."]" );
						$objGoodsReceiptItems->glacctno = $gldata["formatcode"];
						break;
				}		
				if ($objTable->getudfvalue("u_type")=="Capitalize") {
					$objGoodsReceiptItems->setudfvalue("u_facapitalize","Y");
				}
				if ($objGoodsReceiptItems->itemmanageby=="1") {
					$sbqty = $objGoodsReceiptItems->quantity * $objGoodsReceiptItems->numperuom;
					$objGoodsReceiptItems->sbnids = "0|NONE|" . $sbqty . "|u_expdate|"; 
					$objGoodsReceiptItems->sbncnt = $sbqty;
				}
				$objGoodsReceipts->totalamount += $objGoodsReceiptItems->linetotal;
				
				$objGoodsReceiptItems->privatedata["header"] = $objGoodsReceipts;
				if ($actionReturn) $actionReturn = $objGoodsReceiptItems->add();
			}	
			if (!$actionReturn) break;
		}
	}	

	if ($actionReturn) {
		$objMarketingDocuments->docstatus = "C";
		$objTable->setudfvalue("u_gidocno",$objMarketingDocuments->docno);
		$actionReturn = $objMarketingDocuments->add();
	}
	if ($actionReturn && $objGoodsReceipts->totalamount!=0) {
		$objGoodsReceipts->docno = getNextSeriesNoByBranch($objGoodsReceipts->objectcode,$objGoodsReceipts->docseries,$objGoodsReceipts->docdate,$objConnection);
		$objGoodsReceipts->docstatus = "C";
		$objTable->setudfvalue("u_grdocno",$objGoodsReceipts->docno);
		$actionReturn = $objGoodsReceipts->add();
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostGoodsIssueGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostBillGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	if ($delete) {
		//if ($objTable->getudfvalue("u_paidamount")!=0) return raiseError("Cannot cancel bill if payment(s) exists.");
		if ($objTable->getudfvalue("u_discamount")!=0 || $objTable->getudfvalue("u_insamount")!=0 || $objTable->getudfvalue("u_hmoamount")!=0 || $objTable->getudfvalue("u_pnamount")!=0) return raiseError("Cannot cancel bill if deductions exists.");
	}
	if ($objTable->getudfvalue("u_reftype")=="IP") {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
			if ($objTable->docstatus=="O" || $objTable->docstatus=="C") {
				if ($obju_HISIPs->getudfvalue("u_mgh")==0) return raiseError("Cannot post bill if patient is not tag as May Go Home.");
				$obju_HISIPs->setudfvalue("u_billno",$objTable->docno);
				$obju_HISIPs->setudfvalue("u_billcount",$obju_HISIPs->getudfvalue("u_billcount")+1);
				$obju_HISIPs->setudfvalue("u_billdatetime",$objTable->getudfvalue("u_docdate")." ".$objTable->getudfvalue("u_doctime"));
				$obju_HISIPs->setudfvalue("u_billby",$_SESSION["userid"]);
			} else {
				//if ($obju_HISIPs->docstatus=="Discharged") return raiseError("Cannot cancel bill if patient was already discharged.");
				$obju_HISIPs->setudfvalue("u_billno","");
				$obju_HISIPs->setudfvalue("u_billdatetime","");
				$obju_HISIPs->setudfvalue("u_billby","");
			}	
			$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
		} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
	} else {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
		$obju_HISIPMiscs = new documentlinesschema_br(null,$objConnection,"u_hisopmiscs");
		if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
			if ($objTable->docstatus=="O" || $objTable->docstatus=="C") {
				$obju_HISIPs->setudfvalue("u_billno",$objTable->docno);
				$obju_HISIPs->setudfvalue("u_billcount",$obju_HISIPs->getudfvalue("u_billcount")+1);
				$obju_HISIPs->setudfvalue("u_billdatetime",$objTable->getudfvalue("u_docdate")." ".$objTable->getudfvalue("u_doctime"));
				$obju_HISIPs->setudfvalue("u_billby",$_SESSION["userid"]);
			} else {
				$obju_HISIPs->setudfvalue("u_billno","");
				$obju_HISIPs->setudfvalue("u_billdatetime","");
				$obju_HISIPs->setudfvalue("u_billby","");
				//if ($obju_HISIPs->docstatus=="Discharged") return raiseError("Cannot cancel bill if patient was already discharged.");
			}	
			$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
		} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
	}	
	if (!$actionReturn) return $actionReturn;
	$customerdata = getcustomerdata($objTable->getudfvalue("u_patientid"),"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$objTable->getudfvalue("u_patientid")."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	
	$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISPriceAdjs = new documentschema_br(null,$objConnection,"u_hispriceadjs");
	$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");
	$obju_HISPOS = new documentschema_br(null,$objConnection,"u_hispos");
	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");

	$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
	
	$settings = getBusinessObjectSettings("JOURNALVOUCHER");

	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);

	if ($delete) {
		if ($objJvHdr->getbykey($objTable->docno . "CN")) {
			$objTable->setudfvalue("u_jvcndocno",$objTable->docno . "CN");
			return true;
		}
	}
	
	if (!onCustomEventcollectionsPostDoctorPayableGPSHIS('bill',$objTable)) return false; 
	
	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $settings["autopost"];
	$objJvHdr->jeposting = $settings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
	$objJvHdr->docno = $objTable->docno . iif($delete,"CN","");
	//$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Billing";
	$objJvHdr->otherdocno = $objTable->docno;
	$objJvHdr->otherdocdate = $objTable->getudfvalue("u_startdate");
	$objJvHdr->otherdocduedate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->otherbpcode = $objTable->getudfvalue("u_patientid");
	$objJvHdr->otherbpname = $objTable->getudfvalue("u_patientname");
	$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_enddate") . 
		iif($objTable->getudfvalue("u_remarks")!="",";" . $objTable->getudfvalue("u_remarks"),"") . 
		iif($delete,"; Cancelled.",".");
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if (!$delete) {
		$objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
	} else {
		$objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
	}

	if ($actionReturn && $delete && $objTable->getudfvalue("u_paidamount")>0) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->itemtype = "C";
		$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
		$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
		$objJvHdr->sllastno++;
		$objJvDtl->lineno = $objJvHdr->sllastno;
		if ($objTable->getudfvalue("u_paidamount")>0) {
			$objJvDtl->credit = $objTable->getudfvalue("u_paidamount");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_paidamount")*-1;
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;
		$objJvDtl->othertrxtype = "Bill Payments";
		$objJvDtl->otherbpcode = "";
		$objJvDtl->otherbpname = "";
		
		$objJvDtl->remarks = "Bill Payments";
		$actionReturn = $objJvDtl->add();
		if ($actionReturn) {
			$objTable->setudfvalue("u_balance",$objTable->getudfvalue("u_paidamount"));
			$objTable->setudfvalue("u_paidamount",0);
		}
	}
		
	if ($actionReturn) {
		$objRs->queryopen("select b.CODE as U_DOCTORID, b.NAME as U_DOCTORNAME, a.U_FEETYPE, a.U_AMOUNT, a.U_SUFFIX, a.LINEID, a.RCDVERSION, a.U_PAIDAMOUNT from u_hisbillfees a left join u_hisdoctors b on b.code=a.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' ORDER BY a.lineid ASC");
		$pfno=0;
		$pfcode = "";
		$lineno=0;
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->itemtype = "C";
			$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
			$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
			if (!$delete) {
				$objJvHdr->sllastno++;
				$objJvDtl->lineno = $objJvHdr->sllastno;
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"]*-1;
					$objJvDtl->grossamount = $objJvDtl->credit;
				}	
			} else {
				$lineno++;
				$objJvDtl->reftype = "JOURNALVOUCHER";
				$objJvDtl->refno = $objTable->docno . "/" . $lineno;
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"]*-1;
					$objJvDtl->grossamount = $objJvDtl->debit;
				}	
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$objJvDtl->othertrxtype = $objRs->fields["U_FEETYPE"];
			$objJvDtl->otherbpcode = $objRs->fields["U_DOCTORID"];
			$objJvDtl->otherbpname = $objRs->fields["U_DOCTORNAME"];
			
			$objJvDtl->remarks = strtoupper($objRs->fields["U_FEETYPE"]) . iifappend($objRs->fields["U_DOCTORNAME"]," - ");
			$actionReturn = $objJvDtl->add();
			
			if ($delete && $actionReturn && $objRs->fields["U_PAIDAMOUNT"]!=0) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->itemtype = "C";
				$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
				$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
				$objJvDtl->reftype = "JOURNALVOUCHER";
				$objJvDtl->refno = $objTable->docno . "/" . $lineno;
				if ($objRs->fields["U_PAIDAMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_PAIDAMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objRs->fields["U_PAIDAMOUNT"]*-1;
					$objJvDtl->grossamount = $objJvDtl->credit;
				}	
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$objJvDtl->othertrxtype = $objRs->fields["U_FEETYPE"];
				$objJvDtl->otherbpcode = $objRs->fields["U_DOCTORID"];
				$objJvDtl->otherbpname = $objRs->fields["U_DOCTORNAME"];
				
				$objJvDtl->remarks = strtoupper($objRs->fields["U_FEETYPE"]) . iifappend($objRs->fields["U_DOCTORNAME"]," - ");
				$actionReturn = $objJvDtl->add();
			
				
				if ($obju_HISBillFees->getbykey($objTable->docid,$objRs->fields["LINEID"],$objRs->fields["RCDVERSION"])) {
					$obju_HISBillFees->setudfvalue("u_paidamount",0);
					$obju_HISBillFees->privatedata["header"] = $objTable;
					$actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
				} else return raiseError("Unable to find Bill Fees [".$objRs->fields["U_FEETYPE"]."/".$objRs->fields["U_DOCTORNAME"]."] to reverse payments");
				
			}
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn) {
		$objRs->queryopen("select a.U_DOCNO, a.U_DOCTYPE, a.U_AMOUNT, a.U_BALANCE, b.name as U_DEPARTMENTNAME, a.U_DOCSTATUS from u_hisbilltrxs a left join u_hissections b on b.code=a.u_department where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
		$pfno=0;
		$pfcode = "";
		while ($objRs->queryfetchrow("NAME")) {
			switch ($objRs->fields["U_DOCTYPE"]) {
				case "CHRG":
					if ($obju_HISCharges->getbykey($objRs->fields["U_DOCNO"])) {
						if (!$delete) {
							$obju_HISCharges->setudfvalue("u_billno",$objTable->docno);
							if ($obju_HISCharges->docstatus!="CN") $obju_HISCharges->docstatus = "C";
						} else {
							$obju_HISCharges->setudfvalue("u_billno","");
							if ($obju_HISCharges->docstatus!="CN") $obju_HISCharges->docstatus = "O";
						}	
						$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
					} else return raiseError("Unable to find Charge No [".$objRs->fields["U_DOCNO"]."].");
					break;
				case "ADJ":
					if ($obju_HISPriceAdjs->getbykey($objRs->fields["U_DOCNO"])) {
						if (!$delete) {
							$obju_HISPriceAdjs->setudfvalue("u_billno",$objTable->docno);
							if ($obju_HISPriceAdjs->docstatus!="CN") $obju_HISPriceAdjs->docstatus = "C";
						} else {
							$obju_HISPriceAdjs->setudfvalue("u_billno","");
							if ($obju_HISPriceAdjs->docstatus!="CN") $obju_HISPriceAdjs->docstatus = "O";
						}	
						$actionReturn = $obju_HISPriceAdjs->update($obju_HISPriceAdjs->docno,$obju_HISPriceAdjs->rcdversion);
					} else return raiseError("Unable to find Adjustment No [".$objRs->fields["U_DOCNO"]."].");
					break;
				case "CM":
					if ($obju_HISCredits->getbykey($objRs->fields["U_DOCNO"])) {
						$balance = ($objRs->fields["U_BALANCE"]*-1);
						if (!$delete) {
							$obju_HISCredits->setudfvalue("u_billno",$objTable->docno);
							if ($balance>0) {
								if ($obju_HISCredits->getudfvalue("u_balance")==$balance) {
									$obju_HISCredits->setudfvalue("u_balance",0);
									$obju_HISCredits->setudfvalue("u_credited",$obju_HISCredits->getudfvalue("u_credited")+$balance);
								} else return raiseError("Return/Credit No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISCredits->getudfvalue("u_balance")."] during creation of billing document.");
							}
						} else {
							if ($balance>0) {
								if ($obju_HISCredits->getudfvalue("u_balance")==0) {
									$obju_HISCredits->setudfvalue("u_balance",$balance);
									$obju_HISCredits->setudfvalue("u_credited",$obju_HISCredits->getudfvalue("u_credited")-$balance);
								} else return raiseError("Return/Credit No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISCredits->getudfvalue("u_balance")."] during creation of billing document.");
							}
							$obju_HISCredits->setudfvalue("u_billno","");
						}	
						$obju_HISCredits->docstatus = iif($obju_HISCredits->getudfvalue("u_balance")==0,"C","O");
						$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
					} else return raiseError("Unable to find Return/Credit No [".$objRs->fields["U_DOCNO"]."].");
					break;
				case "DP":
					if ($obju_HISPOS->getbykey($objRs->fields["U_DOCNO"])) {
						$balance = ($objRs->fields["U_BALANCE"]*-1);
						if (!$delete) {
							$obju_HISPOS->setudfvalue("u_billno",$objTable->docno);
							if ($balance>0) {
								if (bcsub($obju_HISPOS->getudfvalue("u_balance"),$balance,14)==0) {
									$obju_HISPOS->setudfvalue("u_balance",0);
									$obju_HISPOS->setudfvalue("u_credited",$obju_HISPOS->getudfvalue("u_credited")+$balance);
								} else return raiseError("Partial Payment No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISPOS->getudfvalue("u_balance")."] during creation of billing document.");
							}
						} else {
							if ($balance>0) {
								if ($obju_HISPOS->getudfvalue("u_balance")==0) {
									$obju_HISPOS->setudfvalue("u_balance",$balance);
									$obju_HISPOS->setudfvalue("u_credited",$obju_HISPOS->getudfvalue("u_credited")-$balance);
								} else return raiseError("Partial Payment No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISPOS->getudfvalue("u_balance")."] during creation of billing document.");
							}
							$obju_HISPOS->setudfvalue("u_billno","");
						}	
						$obju_HISPOS->docstatus = iif($obju_HISPOS->getudfvalue("u_balance")==0,"C","O");
						$actionReturn = $obju_HISPOS->update($obju_HISPOS->docno,$obju_HISPOS->rcdversion);
					} else return raiseError("Unable to find Partial Payment No [".$objRs->fields["U_DOCNO"]."].");
					break;
				case "BP":
					if ($obju_HISBills->getbykey($objRs->fields["U_DOCNO"])) {
						$balance = ($objRs->fields["U_BALANCE"]*-1);
						if (!$delete) {
							$obju_HISBills->setudfvalue("u_billno",$objTable->docno);
							if ($balance>0) {
								if (bcsub($obju_HISBills->getudfvalue("u_balance"),$balance,14)==0) {
									$obju_HISBills->setudfvalue("u_balance",0);
									$obju_HISBills->setudfvalue("u_credited",$obju_HISBills->getudfvalue("u_credited")+$balance);
								} else return raiseError("Bill Payment No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISBills->getudfvalue("u_balance")."] during creation of billing document.");
							}
						} else {
							if ($balance>0) {
								if ($obju_HISBills->getudfvalue("u_balance")==0) {
									$obju_HISBills->setudfvalue("u_balance",$balance);
									$obju_HISBills->setudfvalue("u_credited",$obju_HISBills->getudfvalue("u_credited")-$balance);
								} else return raiseError("Bill Payment No [".$objRs->fields["U_DOCNO"]."] balance [".$balance."] have changed to [".$obju_HISBills->getudfvalue("u_balance")."] during creation of billing document.");
							}
							$obju_HISBills->setudfvalue("u_billno","");
						}	
						$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
					} else return raiseError("Unable to Bill Payment No [".$objRs->fields["U_DOCNO"]."].");
					break;
			}	
			if (!$actionReturn) break;
					
			switch ($objRs->fields["U_DOCTYPE"]) {
				case "CHRG":
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
					$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
					$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
					$objJvDtl->reftype = "ARINVOICE";
					$objJvDtl->refno = $objRs->fields["U_DOCNO"];
					if (!$delete) {
						$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
						$objJvDtl->grossamount = $objJvDtl->debit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					if ($actionReturn && $objRs->fields["U_DOCSTATUS"]=="CN") {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->itemtype = "C";
						$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
						$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
						$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
						$objJvDtl->reftype = "ARCREDITMEMO";
						$objJvDtl->refno = $objRs->fields["U_DOCNO"];
						if (!$delete) {
							$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
							$objJvDtl->grossamount = $objJvDtl->debit;
						} else {
							$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
							$objJvDtl->grossamount = $objJvDtl->credit;
						}	
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();					
					}
					break;
				case "ADJ":
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
					$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
					$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
					if ($objRs->fields["U_AMOUNT"]>0) $objJvDtl->reftype = "ARINVOICE";
					else $objJvDtl->reftype = "ARCREDITMEMO";
					$objJvDtl->refno = $objRs->fields["U_DOCNO"];
					if (!$delete) {
						if ($objRs->fields["U_AMOUNT"]>0) {
							$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
							$objJvDtl->grossamount = $objJvDtl->credit;
						} else {
							$objJvDtl->debit = $objRs->fields["U_AMOUNT"]*-1;
							$objJvDtl->grossamount = $objJvDtl->debit;
						}	
					} else {
						if ($objRs->fields["U_AMOUNT"]>0) {
							$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
							$objJvDtl->grossamount = $objJvDtl->debit;
						} else {
							$objJvDtl->credit = $objRs->fields["U_AMOUNT"]*-1;
							$objJvDtl->grossamount = $objJvDtl->credit;
						}	
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					if ($actionReturn && $objRs->fields["U_DOCSTATUS"]=="CN") {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->itemtype = "C";
						$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
						$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
						$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
						if ($objRs->fields["U_AMOUNT"]>0) $objJvDtl->reftype = "ARINVOICE";
						else $objJvDtl->reftype = "ARCREDITMEMO";
						$objJvDtl->refno = $objRs->fields["U_DOCNO"];
						if (!$delete) {
							if ($objRs->fields["U_AMOUNT"]>0) {
								$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
								$objJvDtl->grossamount = $objJvDtl->debit;
							} else {
								$objJvDtl->credit = $objRs->fields["U_AMOUNT"]*-1;
								$objJvDtl->grossamount = $objJvDtl->credit;
							}	
						} else {
							if ($objRs->fields["U_AMOUNT"]>0) {
								$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
								$objJvDtl->grossamount = $objJvDtl->credit;
							} else {
								$objJvDtl->debit = $objRs->fields["U_AMOUNT"]*-1;
								$objJvDtl->grossamount = $objJvDtl->debit;
							}	
						}	
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();					
					}
					break;					
				case "CM":
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
					$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
					$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
					$objJvDtl->reftype = "ARCREDITMEMO";
					$objJvDtl->refno = $objRs->fields["U_DOCNO"];
					if (!$delete) {
						$objJvDtl->debit = $objRs->fields["U_AMOUNT"]*-1;
						$objJvDtl->grossamount = $objJvDtl->debit;
					} else {
						$objJvDtl->credit = $objRs->fields["U_AMOUNT"]*-1;
						$objJvDtl->grossamount = $objJvDtl->credit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					break;
				case "DP":
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
					$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
					$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
					$objJvDtl->reftype = "DOWNPAYMENT";
					$objJvDtl->refno = $objRs->fields["U_DOCNO"];
					if (!$delete) {
						$objJvDtl->debit = $objRs->fields["U_AMOUNT"]*-1;
						$objJvDtl->grossamount = $objJvDtl->debit;
					} else {
						$objJvDtl->credit = $objRs->fields["U_AMOUNT"]*-1;
						$objJvDtl->grossamount = $objJvDtl->credit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					break;
				case "BP":
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
					$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
					$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
					$objJvDtl->reftype = "JOURNALVOUCHER";
					$objJvDtl->refno = $objRs->fields["U_DOCNO"]."CN/1";
					if (!$delete) {
						$objJvDtl->debit = $objRs->fields["U_AMOUNT"]*-1;
						$objJvDtl->grossamount = $objJvDtl->debit;
					} else {
						$objJvDtl->credit = $objRs->fields["U_AMOUNT"]*-1;
						$objJvDtl->grossamount = $objJvDtl->credit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					break;
			}		
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
	// && abs($objJvHdr->totaldebit-$objJvHdr->totalcredit)<1
		if ($objJvHdr->totaldebit!=$objJvHdr->totalcredit ) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = "4110303";
			$objJvDtl->itemname = "Miscellaneous Income";
			$objJvDtl->remarks = strtoupper($objRs->fields["U_DEPARTMENTNAME"]);
			if ($objJvHdr->totaldebit>$objJvHdr->totalcredit) {
				$objJvDtl->credit = $objJvHdr->totaldebit-$objJvHdr->totalcredit;
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = $objJvHdr->totalcredit-$objJvHdr->totaldebit;
				$objJvDtl->grossamount = $objJvDtl->debit;
			}
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}

	}
	
	/*var_dump($objJvHdr->totaldebit);
	var_dump($objJvHdr->totalcredit);*/
	if ($actionReturn) $actionReturn = $objJvHdr->add();
	//if ($actionReturn && $objTable->docstatus=="O") $objTable->docstatus="C";

	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostBillGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostBillOldGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_reftype")=="IP") {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
			if ($objTable->docstatus=="O" || $objTable->docstatus=="C") {
				if ($obju_HISIPs->getudfvalue("u_mgh")==0) return raiseError("Cannot post bill if patient is not tag as May Go Home.");
				$obju_HISIPs->setudfvalue("u_billno",$objTable->docno);
				$obju_HISIPs->setudfvalue("u_billcount",$obju_HISIPs->getudfvalue("u_billcount")+1);
				$obju_HISIPs->setudfvalue("u_billdatetime",$objTable->getudfvalue("u_docdate")." ".$objTable->getudfvalue("u_doctime"));
				$obju_HISIPs->setudfvalue("u_billby",$_SESSION["userid"]);
			} else {
				if ($obju_HISIPs->docstatus=="Discharged") return raiseError("Cannot cancel bill if patient was already discharged.");
				$obju_HISIPs->setudfvalue("u_billno","");
				$obju_HISIPs->setudfvalue("u_billdatetime","");
				$obju_HISIPs->setudfvalue("u_billby","");
			}	
			$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
		} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
	} else {
		$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
		$obju_HISIPMiscs = new documentlinesschema_br(null,$objConnection,"u_hisopmiscs");
		if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
			if ($objTable->docstatus=="O" || $objTable->docstatus=="C") {
				$obju_HISIPs->setudfvalue("u_billno",$objTable->docno);
				$obju_HISIPs->setudfvalue("u_billcount",$obju_HISIPs->getudfvalue("u_billcount")+1);
				$obju_HISIPs->setudfvalue("u_billdatetime",$objTable->getudfvalue("u_docdate")." ".$objTable->getudfvalue("u_doctime"));
				$obju_HISIPs->setudfvalue("u_billby",$_SESSION["userid"]);
			} else {
				$obju_HISIPs->setudfvalue("u_billno","");
				$obju_HISIPs->setudfvalue("u_billdatetime","");
				$obju_HISIPs->setudfvalue("u_billby","");
				if ($obju_HISIPs->docstatus=="Discharged") return raiseError("Cannot cancel bill if patient was already discharged.");
			}	
			$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
		} else $actionReturn = raiseError("Unable to find Reference No.[".$objTable->getudfvalue("u_refno")."]");
	}	
	if (!$actionReturn) return $actionReturn;
	$customerdata = getcustomerdata($objTable->getudfvalue("u_patientid"),"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$objTable->getudfvalue("u_patientid")."].");
	
	$objRsItems = new recordset(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	if (!$delete) {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARINVOICES");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARINVOICEITEMS");
		
		$settings = getBusinessObjectSettings("ARINVOICE");
	} else {
		$objMarketingDocuments = new marketingdocuments(null,$objConnection,"ARCREDITMEMOS");
		$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"ARCREDITMEMOITEMS");
		
		$settings = getBusinessObjectSettings("ARCREDITMEMO");
	}	
	
	$objRs->queryopen("select a.U_DOCTORID, b.NAME as U_DOCTORNAME, a.U_FEETYPE, a.U_AMOUNT, a.U_SUFFIX from u_hisbillfees a left join u_hisdoctors b on b.code=a.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' ORDER BY a.u_feetype ASC");
	$pfno=0;
	$pfcode = "";
	while ($objRs->queryfetchrow("NAME")) {
		$pfno++;
		$objMarketingDocuments->prepareadd();
		$objMarketingDocuments->docseries = -1;
		if (!$delete) $objMarketingDocuments->objectcode = "ARINVOICE";
		else $objMarketingDocuments->objectcode = "ARCREDITMEMO";
		$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
		$objMarketingDocuments->jeposting = $settings["jeposting"];
		if (!$delete) $objMarketingDocuments->docid = getNextIdByBranch("arinvoices",$objConnection);
		else $objMarketingDocuments->docid = getNextIdByBranch("arcreditmemos",$objConnection);
	
		if ($objTable->getudfvalue("u_billasone")=="0") {
			$objMarketingDocuments->docno = $objTable->docno . $objRs->fields["U_SUFFIX"] . iif($delete,"CN","");
		} else $objMarketingDocuments->docno = $objTable->docno . iif($delete,"CN","");
		$objMarketingDocuments->bpcode = $objTable->getudfvalue("u_patientid");
		$objMarketingDocuments->bpname = $objTable->getudfvalue("u_patientname");
		$objMarketingDocuments->currency = $customerdata["CURRENCY"];
		$objMarketingDocuments->doctype = "I";
		
		$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
		$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docdate");
		$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
		$objMarketingDocuments->bprefno = $objTable->docno;
		$objMarketingDocuments->otherdocno = $objTable->docno;
		if ($objTable->getudfvalue("u_billasone")=="0") $objMarketingDocuments->othertrxtype = $objRs->fields["U_FEETYPE"];
		$objMarketingDocuments->otherdocdate = $objTable->getudfvalue("u_startdate");
		$objMarketingDocuments->otherdocduedate = $objTable->getudfvalue("u_docdate");
		$objMarketingDocuments->otherbprefno = $objTable->getudfvalue("u_refno");
		$objMarketingDocuments->otherbpcode = $objTable->getudfvalue("u_patientid");
		$objMarketingDocuments->otherbpname = $objTable->getudfvalue("u_patientname");
		$objMarketingDocuments->otherbpcode2 = $objRs->fields["U_DOCTORID"];
		$objMarketingDocuments->otherbpname2 = $objRs->fields["U_DOCTORNAME"];
		if (!$delete) {
			if ($objTable->getudfvalue("u_billasone")=="0") {
				$objMarketingDocuments->remarks = iif($objRs->fields["U_DOCTORID"]!="",$objRs->fields["U_DOCTORNAME"]." - ") .$objMarketingDocuments->remarks = $objRs->fields["U_FEETYPE"];
			}	
			$objMarketingDocuments->journalremark = "A/R Invoice - " . $objMarketingDocuments->bpcode;
		} else {
			if ($objTable->getudfvalue("u_billasone")=="0") {
				$objMarketingDocuments->remarks = "Based on A/R Invoice " . $objTable->docno . $objRs->fields["U_SUFFIX"]. ". ". iif($objRs->fields["U_DOCTORID"]!="",$objRs->fields["U_DOCTORNAME"]." - ") .$objMarketingDocuments->remarks = $objRs->fields["U_FEETYPE"];
			} else $objMarketingDocuments->remarks = "Based on A/R Invoice " . $objTable->docno . ". ";
			$objMarketingDocuments->journalremark = "A/R Credit Memo - " . $objMarketingDocuments->bpcode;
		}	
		$objMarketingDocuments->paymentterm = $customerdata["PAYMENTTERM"];	
		
		if ($objRs->fields["U_FEETYPE"]=="Hospital Fees") {
			if ($actionReturn) {
				$objRsItems->setdebug();
				$objRsItems->queryopen("select a.u_roomtype as U_ITEMCODE, b.itemdesc as U_ITEMDESC, a.U_RATE as U_PRICE, a.U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillrooms a, items b where b.itemcode=a.u_roomtype and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
				//var_dump($objRsItems->sqls);
				while ($objRsItems->queryfetchrow("NAME")) {
					$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
					$objMarketingDocumentItems->prepareadd();
					$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
					if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
					else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
					$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
					$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
					$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
					$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
					$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
					$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
					$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
					$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->whscode = "DROPSHIP";
					$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
					$objMarketingDocumentItems->linestatus = "O";
					$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
					$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
					
					$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
					$actionReturn = $objMarketingDocumentItems->add();
					if (!$actionReturn) break;
				}
			}	
					
			if ($actionReturn) {
				$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_RATE as U_PRICE, a.U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillsplrooms a where  a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
				while ($objRsItems->queryfetchrow("NAME")) {
					$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
					$objMarketingDocumentItems->prepareadd();
					$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
					if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
					else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
					$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
					$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
					$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
					$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
					$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
					$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
					$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
					$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->whscode = "DROPSHIP";
					$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
					$objMarketingDocumentItems->linestatus = "O";
					$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
					$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
					
					$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
					$actionReturn = $objMarketingDocumentItems->add();
					if (!$actionReturn) break;
				}
			}	
		
			if ($actionReturn) {
				$objRsItems->queryopen("select a.u_type as U_ITEMCODE, b.itemdesc as U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbilllabtests a, items b where b.itemcode=a.u_type and a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
				while ($objRsItems->queryfetchrow("NAME")) {
					$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
					$objMarketingDocumentItems->prepareadd();
					$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
					if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
					else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
					$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
					$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
					$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
					$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
					$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
					$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
					$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
					$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->whscode = "DROPSHIP";
					$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
					$objMarketingDocumentItems->linestatus = "O";
					$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
					$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
					
					$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
					$actionReturn = $objMarketingDocumentItems->add();
					if (!$actionReturn) break;
				}
			}	
		
			if ($actionReturn) {
				$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_PRICE, a.U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillmedsups a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
				while ($objRsItems->queryfetchrow("NAME")) {
					$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
					$objMarketingDocumentItems->prepareadd();
					$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
					if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
					else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
					$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
					$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
					$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
					$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
					$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
					$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
					$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
					$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->whscode = "DROPSHIP";
					$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
					$objMarketingDocumentItems->linestatus = "O";
					$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
					$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
					
					$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
					$actionReturn = $objMarketingDocumentItems->add();
					if (!$actionReturn) break;
				}
			}	
		
			if ($actionReturn) {
				$pfcode = "";
				$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillmiscs a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
				while ($objRsItems->queryfetchrow("NAME")) {
					if ($objRsItems->fields["U_ITEMCODE"]!="") $pfcode = $objRsItems->fields["U_ITEMCODE"];
					else $objRsItems->fields["U_ITEMCODE"] = $pfcode;
					$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
					$objMarketingDocumentItems->prepareadd();
					$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
					if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
					else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
					$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
					$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
					$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
					$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
					$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
					$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
					$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
					$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->whscode = "DROPSHIP";
					$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
					$objMarketingDocumentItems->linestatus = "O";
					$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
					$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
					
					$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
					$actionReturn = $objMarketingDocumentItems->add();
					if (!$actionReturn) break;
				}
			}	
		
		}
		
		if ($actionReturn) {
			if ($objRs->fields["U_FEETYPE"]=="Professional Fees") {
				$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' and a.U_DOCTORID='".$objRs->fields["U_DOCTORID"]."' and a.U_ITEMCODE<>''");
			} elseif ($objRs->fields["U_FEETYPE"]=="Professional Materials") {
				$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' and a.U_DOCTORID='".$objRs->fields["U_DOCTORID"]."' and a.U_ITEMCODE=''");
			} elseif ($objTable->getudfvalue("u_billasone")=="1") {
				$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'");
			}
			if ($objRs->fields["U_FEETYPE"]=="Professional Fees" || $objRs->fields["U_FEETYPE"]=="Professional Materials" || $objTable->getudfvalue("u_billasone")=="1") {
				while ($objRsItems->queryfetchrow("NAME")) {
					if ($objRsItems->fields["U_ITEMCODE"]!="") $pfcode = $objRsItems->fields["U_ITEMCODE"];
					else $objRsItems->fields["U_ITEMCODE"] = $pfcode;
					$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
					$objMarketingDocumentItems->prepareadd();
					$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
					$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
					$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
					if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("arinvoiceitems",$objConnection);
					else $objMarketingDocumentItems->lineid = getNextIdByBranch("arcreditmemoitems",$objConnection);
					$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
					$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
					$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
					$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
					$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
					$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
					$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
					$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
					$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
					$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
					$objMarketingDocumentItems->whscode = "DROPSHIP";
					$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
					$objMarketingDocumentItems->linestatus = "O";
					$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
					$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
					
					$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
					$actionReturn = $objMarketingDocumentItems->add();
					if (!$actionReturn) break;
				}			
			}	
		}
					
		if ($actionReturn) {
			$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
			$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
			if ($objMarketingDocuments->dueamount>0) $objMarketingDocuments->docstatus = "O";
			else $objMarketingDocuments->docstatus = "C";
			$actionReturn = $objMarketingDocuments->add();
		}
		
		if ($objTable->getudfvalue("u_billasone")=="1") {
			if ($actionReturn && bcsub($objMarketingDocuments->totalamount,$objTable->getudfvalue("u_amount"))!=0) {
				if (!$delete) {
					if ($actionReturn) $actionReturn = raiseError("Mismatch Billing Actual Charges [".$objTable->getudfvalue("u_amount")."] and A/R Total Amount[".$objMarketingDocuments->totalamount."]");
				} else {
					if ($actionReturn) $actionReturn = raiseError("Mismatch Billing Reversal Actual Charges [".$objTable->getudfvalue("u_amount")."] and A/R Credit Memo Total Amount[".$objMarketingDocuments->totalamount."]");
				}	
			}
		} else {				
			if ($actionReturn && bcsub($objMarketingDocuments->totalamount,$objRs->fields["U_AMOUNT"])!=0) {
				if (!$delete) {
					if ($actionReturn) $actionReturn = raiseError("Mismatch Billing Actual Charges [".$objRs->fields["U_AMOUNT"]."] and A/R Total Amount[".$objMarketingDocuments->totalamount."]");
				} else {
					if ($actionReturn) $actionReturn = raiseError("Mismatch Billing Reversal Actual Charges [".$objRs->fields["U_AMOUNT"]."] and A/R Credit Memo Total Amount[".$objMarketingDocuments->totalamount."]");
				}	
			}
		}	
		if ($actionReturn) {
			$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
			$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
			$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
			if (!$delete) $bpdata["balance"] = $objMarketingDocuments->dueamount;
			else $bpdata["balance"] = $objMarketingDocuments->dueamount*-1;
			$actionReturn = updatecustomerbalance($bpdata);
		}			
		
		if ($objTable->getudfvalue("u_billasone")=="1") {
			break;
		}	
		
	}

	if ($actionReturn && $objTable->getudfvalue("u_dpamount")!=0 && $objTable->getudfvalue("u_billasone")=="1") {
		$objCollections = new collections(null,$objConnection) ;
		$objCollectionsInvoices = new collectionsinvoices(null,$objConnection) ;
	
		$custno = $objTable->getudfvalue("u_patientid");
		
		$customerdata = getcustomerdata($custno,"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
		if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$custno."].");
		
		$settings = getBusinessObjectSettings("CREDITMEMO");
		if ($delete) {
			if ($objCollections->getbykey($_SESSION["branch"], $objTable->docno)) {
				if ($actionReturn) {
					$objCollections->cleared = -99;
					$objCollections->cancelleddate = $objCollections->docdate;
					$objCollections->cancelledby = $_SESSION["userid"];
					$objCollections->docstat = 'CN';
					if ($actionReturn) $actionReturn = $objCollections->update($objCollections->branchcode,$objCollections->docno,$objCollections->rcdversion);
				}		
			} else return raiseError("Unable to Find Credit Memo Record for ofsetting deposits.");
		} else {	
			$objCollections->docdate = $objTable->getudfvalue("u_docdate");
			$objCollections->bpcode = $custno;
			$objCollections->bpname = $customerdata["CUSTNAME"];
			//$objCollections->paidamount = $objTable->getudfvalue("u_dueamount");
			$objCollections->docno = $objTable->docno;
			//$objCollections->setudfvalue("u_colltype",$objTable->getudfvalue("u_colltype"));
			if ($actionReturn) $actionReturn = isPostingDateValid($objCollections->docdate,$objCollections->docduedate,$objCollections->taxdate);
			if ($actionReturn) {
				$objCollections->docid = getNextIdByBranch('collections',$objConnection);
				$objCollections->sbo_post_flag = $settings["autopost"];
				$objCollections->jeposting = $settings["jeposting"];
				$objCollections->objectcode = "INCOMINGPAYMENT";
				$objCollections->trxtype = "CM";
				$objCollections->changeamount = 0;
				$objCollections->doctype = "C";
				$objCollections->collfor = "SI";
				$objCollections->bpcurrency = $customerdata["CURRENCY"];
				$objCollections->currency = $customerdata["CURRENCY"];
				$objCollections->docduedate = $objCollections->docdate;
				$objCollections->taxdate = $objCollections->docdate;
				$objCollections->branchcode = $_SESSION["branch"];
				$objCollections->valuedate = $objCollections->docdate;
				$objCollections->cleared = 1;
				$objCollections->postdate = $objCollections->docdate ." ". date('H:i:s');
				$objCollections->journalremark = "Credit Memo - " . $objCollections->bpcode;
				
				$objRs->queryopen("select DOCNO,U_TOTALAMOUNT from u_hispos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='".$objTable->getudfvalue("u_reftype")."' and u_refno='".$objTable->getudfvalue("u_refno")."' and u_payreftype='RS'");
				while ($objRs->queryfetchrow("NAME")) {
					$objCollectionsInvoices->prepareadd();
					$objCollectionsInvoices->docid = getNextIdByBranch('collectionsinvoices',$objConnection);
					$objCollectionsInvoices->docno = $objCollections->docno;
					$objCollectionsInvoices->reftype = "DOWNPAYMENT";
					$objCollectionsInvoices->refbranch = $objCollections->branchcode;
					$objCollectionsInvoices->refno = $objRs->fields["DOCNO"];
					$objCollectionsInvoices->bpcode = $objCollections->bpcode;
					$objCollectionsInvoices->bpname = $objCollections->bpname;
					$objCollectionsInvoices->currency = $objCollections->currency;
					$objCollectionsInvoices->objectcode = $objCollections->objectcode;
					$objCollectionsInvoices->privatedata["header"] = $objCollections;
					$objCollectionsInvoices->amount = $objRs->fields["U_TOTALAMOUNT"]*-1;
					$actionReturn = $objCollectionsInvoices->add();
					if (!$actionReturn) break;
				}
	
				if ($actionReturn) {
					$objCollectionsInvoices->prepareadd();
					$objCollectionsInvoices->docid = getNextIdByBranch('collectionsinvoices',$objConnection);
					$objCollectionsInvoices->docno = $objCollections->docno;
					$objCollectionsInvoices->reftype = "ARINVOICE";
					$objCollectionsInvoices->refbranch = $objCollections->branchcode;
					$objCollectionsInvoices->refno = $objTable->docno;
					$objCollectionsInvoices->bpcode = $objCollections->bpcode;
					$objCollectionsInvoices->bpname = $objCollections->bpname;
					$objCollectionsInvoices->currency = $objCollections->currency;
					$objCollectionsInvoices->objectcode = $objCollections->objectcode;
					$objCollectionsInvoices->privatedata["header"] = $objCollections;
					$objCollectionsInvoices->amount = $objTable->getudfvalue("u_dpamount");
					if ($actionReturn) $actionReturn = $objCollectionsInvoices->add();
				}
										
				/*
				if ($objCollections->colltype=="CPN") {
					$cashcarddata = getcashcarddata($objCollections->userfields["u_cashcard"]["value"],"LINKTODOCNO");
					$objCollectionsCashCards->prepareadd();
					$objCollectionsCashCards->docid = getNextIdByBranch('collectionscashcards',$objConnection);
					$objCollectionsCashCards->docno = $objCollections->docno;
					$objCollectionsCashCards->objectcode = $objCollections->objectcode;
					$objCollectionsCashCards->cashcard = $objCollections->userfields["u_cashcard"]["value"];
					$objCollectionsCashCards->linktodocno = $cashcarddata["LINKTODOCNO"];
					if ($objCollectionsCashCards->linktodocno=="1") {
						$objCollectionsCashCards->linkdocno = $objCollections->acctno;
					}
					$objCollectionsCashCards->refbranch = $objCollections->acctbranch;
					$objCollectionsCashCards->refno = "-";
					$objCollectionsCashCards->amount = $objCollections->paidamount;
					$objCollectionsCashCards->privatedata["header"] = $objCollections;
					if ($actionReturn) $actionReturn = $objCollectionsCashCards->add();
				}
				*/
				if ($actionReturn) $actionReturn = $objCollections->add();
			}			
		}	
	/*
		$objJvHdr = new journalvouchers(null,$objConnection);
		$objJvDtl = new journalvoucheritems(null,$objConnection);
		
		$settings = getBusinessObjectSettings("JOURNALVOUCHER");
		
		$objJvHdr->prepareadd();
		$objJvHdr->objectcode = "JOURNALVOUCHER";
		$objJvHdr->sbo_post_flag = $settings["autopost"];
		$objJvHdr->jeposting = $settings["jeposting"];
		$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
		$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
		$objJvHdr->docduedate = $objJvHdr->docdate;
		$objJvHdr->taxdate = $objJvHdr->docdate;
		$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
		$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
		$objJvHdr->currency = $_SESSION["currency"];
		$objJvHdr->docstatus = "C";
		$objJvHdr->reference1 = $objTable->docno;
		$objJvHdr->reference2 = "Offset Deposits on Bill";
		$objJvHdr->setudfvalue("u_hisstartdate",$objTable->getudfvalue("u_startdate"));
		$objJvHdr->setudfvalue("u_hisenddate",$objTable->getudfvalue("u_docdate"));
		$objJvHdr->setudfvalue("u_hispatientid",$objTable->getudfvalue("u_patientid"));
		$objJvHdr->setudfvalue("u_hispatientname",$objTable->getudfvalue("u_patientname"));
		$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_enddate") . 
			iif($objTable->getudfvalue("u_remarks")!="",";" . $objTable->getudfvalue("u_remarks"),"") . 
			iif($delete,"; Cancelled.",".");
		
		$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
		
		$objRs->queryopen("select DOCNO,U_TOTALAMOUNT from u_hispos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='".$objTable->getudfvalue("u_reftype")."' and u_refno='".$objTable->getudfvalue("u_refno")."' and u_payreftype='RS'");
		while ($objRs->queryfetchrow("NAME")) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->itemtype = "C";
			$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
			$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
			$objJvDtl->reftype = "DP";
			$objJvDtl->refno = $objRs->fields["DOCNO"];
			if (!$delete) {
				$objJvDtl->debit = $objRs->fields["U_TOTALAMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->debit;
			} else {
				$objJvDtl->credit = $objRs->fields["U_TOTALAMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->credit;
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			if (!$actionReturn) break;
		}

		if ($actionReturn) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->itemtype = "C";
			$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
			$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
			$objJvDtl->reftype = "ARINVOICE";
			$objJvDtl->refno = $objTable->docno;
			if (!$delete) {
				$objJvDtl->credit = $objTable->getudfvalue("u_dpamount");
				$objJvDtl->grossamount = $objJvDtl->credit;
			} else {
				$objJvDtl->debit = $objTable->getudfvalue("u_dpamount");
				$objJvDtl->grossamount = $objJvDtl->debit;
			}
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}

		if ($actionReturn) $actionReturn = $objJvHdr->add();					
	*/
	}
	
	if ($delete) {
		$objJvHdr = new journalvouchers(null,$objConnection);
		$objJvDtl = new journalvoucheritems(null,$objConnection);
		
		$settings = getBusinessObjectSettings("JOURNALVOUCHER");
		
		$objJvHdr->prepareadd();
		$objJvHdr->objectcode = "JOURNALVOUCHER";
		$objJvHdr->sbo_post_flag = $settings["autopost"];
		$objJvHdr->jeposting = $settings["jeposting"];
		$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
		$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
		$objJvHdr->docduedate = $objJvHdr->docdate;
		$objJvHdr->taxdate = $objJvHdr->docdate;
		$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
		$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
		$objJvHdr->currency = $_SESSION["currency"];
		$objJvHdr->docstatus = "C";
		$objJvHdr->reference1 = $objTable->docno;
		$objJvHdr->reference2 = "Offset Billing Cancellation";
		$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_enddate") . 
			iif($objTable->getudfvalue("u_remarks")!="",";" . $objTable->getudfvalue("u_remarks"),"") . 
			iif($delete,"; Cancelled.",".");
		
		$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
		
		if ($objTable->getudfvalue("u_billasone")=="0") {
			$objRs->queryopen("select a.U_DOCTORID, b.NAME as U_DOCTORNAME, a.U_FEETYPE, a.U_AMOUNT, a.U_SUFFIX from u_hisbillfees a left join u_hisdoctors b on b.code=a.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' ORDER BY a.u_feetype ASC");
			while ($objRs->queryfetchrow("NAME")) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->itemtype = "C";
				$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
				$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
				$objJvDtl->reftype = "ARCREDITMEMO";
				$objJvDtl->refno = $objTable->docno . $objRs->fields["U_SUFFIX"] . "CN";
				$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->debit;
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
				if (!$actionReturn) break;
	
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->itemtype = "C";
				$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
				$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
				$objJvDtl->reftype = "ARINVOICE";
				$objJvDtl->refno = $objTable->docno . $objRs->fields["U_SUFFIX"];
				$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
				$objJvDtl->grossamount = $objJvDtl->credit;
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
				if (!$actionReturn) break;
			}		
		} else {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->itemtype = "C";
			$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
			$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
			$objJvDtl->reftype = "ARCREDITMEMO";
			$objJvDtl->refno = $objTable->docno;
			$objJvDtl->debit = $objTable->getudfvalue("u_amount");
			$objJvDtl->grossamount = $objJvDtl->debit;
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			
			if ($actionReturn) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->itemtype = "C";
				$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
				$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
				$objJvDtl->reftype = "ARINVOICE";
				$objJvDtl->refno = $objTable->docno;
				$objJvDtl->credit = $objTable->getudfvalue("u_amount");
				$objJvDtl->grossamount = $objJvDtl->credit;
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
			}	
		}
				
		if ($actionReturn) $actionReturn = $objJvHdr->add();						

	}
	
	/*	
	if ($actionReturn) {
		$objRs->queryopen("select a.u_doctorid from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid'  group by a.u_doctorid");
		while ($objRs->queryfetchrow("NAME")) {
			
			$supplierdata = getsupplierdata($objRs->fields["u_doctorid"],"SUPPNO,SUPPNAME,PAYMENTTERM");
			if ($supplierdata["SUPPNAME"]=="") return raiseError("Invalid Supplier [".$objRs->fields["u_doctorid"]."].");

			if (!$delete) {
				$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APINVOICES");
				$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APINVOICEITEMS");
			
				$settings = getBusinessObjectSettings("APINVOICE");
			} else {
				$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APCREDITMEMOS");
				$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APCREDITMEMOITEMS");
			
				$settings = getBusinessObjectSettings("APCREDITMEMO");
			}	
			
			$objMarketingDocuments->prepareadd();
			if (!$delete) $objMarketingDocuments->objectcode = "APINVOICE";
			else $objMarketingDocuments->objectcode = "APCREDITMEMO";
			$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
			$objMarketingDocuments->jeposting = $settings["jeposting"];
			if (!$delete) $objMarketingDocuments->docid = getNextIdByBranch("apinvoices",$objConnection);
			else $objMarketingDocuments->docid = getNextIdByBranch("apcreditmemos",$objConnection);
		
			$objMarketingDocuments->docseries = getDefaultSeries($objMarketingDocuments->objectcode,$objConnection);
			$objMarketingDocuments->docno = getNextSeriesNoByBranch($objMarketingDocuments->objectcode,$objMarketingDocuments->docseries,$objMarketingDocuments->docdate,$objConnection);
			$objMarketingDocuments->bpcode = $supplierdata["SUPPNO"];
			$objMarketingDocuments->bpname = $supplierdata["SUPPNAME"];
			$objMarketingDocuments->doctype = "I";
			
			$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
			$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docduedate");
			$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
			$objMarketingDocuments->bprefno = $objTable->docno;
			
			$objMarketingDocuments->otherdocno = $objTable->docno;
			$objMarketingDocuments->otherdocdate = $objTable->getudfvalue("u_startdate");
			$objMarketingDocuments->otherdocduedate = $objTable->getudfvalue("u_docdate");
			$objMarketingDocuments->otherbprefno = $objTable->getudfvalue("u_refno");
			$objMarketingDocuments->otherbpcode = $objTable->getudfvalue("u_patientid");
			$objMarketingDocuments->otherbpname = $objTable->getudfvalue("u_patientname");
			
			if (!$delete) {
				$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Billing. " . $objTable->getudfvalue("u_remarks");
				$objMarketingDocuments->journalremark = "A/P Billing - " . $objMarketingDocuments->bpcode;
			} else {
				$objMarketingDocuments->remarks = $objTable->getudfvalue("u_reftype"). " Billing Reversal. " . $objTable->getudfvalue("u_remarks");
				$objMarketingDocuments->journalremark = "A/P Credit Memo Billing - " . $objMarketingDocuments->bpcode;
			}	
			$objMarketingDocuments->paymentterm = $supplierdata["PAYMENTTERM"];
			
			$pfcode = "";
			$objRsItems->queryopen("select a.U_ITEMCODE, a.U_ITEMDESC, a.U_AMOUNT as U_PRICE, 1 as U_QUANTITY, a.U_AMOUNT as U_LINETOTAL  from u_hisbillconsultancyfees a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='$objTable->docid' and a.u_doctorid='".$objRs->fields["u_doctorid"]."'");
			while ($objRsItems->queryfetchrow("NAME")) {
				if ($objRsItems->fields["U_ITEMCODE"]!="") $pfcode = $objRsItems->fields["U_ITEMCODE"];
				else $objRsItems->fields["U_ITEMCODE"] = $pfcode;
				$itemdata = getitemsdata($objRsItems->fields["U_ITEMCODE"],"TAXCODESA,MANAGEBY,MANAGEBYMETHOD,U_PROFITCENTER");
				$objMarketingDocumentItems->prepareadd();
				$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
				$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
				$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
				if (!$delete) $objMarketingDocumentItems->lineid = getNextIdByBranch("apinvoiceitems",$objConnection);
				else $objMarketingDocumentItems->lineid = getNextIdByBranch("apcreditmemoitems",$objConnection);
				$objMarketingDocumentItems->itemcode = $objRsItems->fields["U_ITEMCODE"];
				$objMarketingDocumentItems->itemdesc = $objRsItems->fields["U_ITEMDESC"];
				$objMarketingDocumentItems->quantity = $objRsItems->fields["U_QUANTITY"];
				$objMarketingDocumentItems->unitprice = $objRsItems->fields["U_PRICE"];
				$objMarketingDocumentItems->price = $objRsItems->fields["U_PRICE"];
				$objMarketingDocumentItems->linetotal = $objRsItems->fields["U_LINETOTAL"];
				$objMarketingDocumentItems->vatcode = $itemdata["TAXCODESA"];
				$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
				$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
			$objMarketingDocumentItems->drcode = $itemdata["U_PROFITCENTER"];
				$objMarketingDocumentItems->openquantity = $objMarketingDocumentItems->quantity;
				$objMarketingDocumentItems->whscode = "DROPSHIP";
				$objMarketingDocumentItems->itemmanageby = $itemdata["MANAGEBY"];
				$objMarketingDocumentItems->linestatus = "O";
				$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
				$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
				
				$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
				$actionReturn = $objMarketingDocumentItems->add();
				if (!$actionReturn) break;
			}			
			
			if ($actionReturn) {
				$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
				$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
				if ($objMarketingDocuments->dueamount>0) $objMarketingDocuments->docstatus = "O";
				else $objMarketingDocuments->docstatus = "C";
				$actionReturn = $objMarketingDocuments->add();
			}
			if ($actionReturn) {
				$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
				$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
				$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
				if (!$delete) $bpdata["balance"] = $objMarketingDocuments->dueamount *-1;
				else $bpdata["balance"] = $objMarketingDocuments->dueamount;
				$actionReturn = updatesupplierbalance($bpdata);
			}			
			if (!$actionReturn) break;
		}
	}	
	*/
	//if ($actionReturn && $objTable->docstatus=="O") $objTable->docstatus="C";

	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostBillGPSHIS");
			
	return $actionReturn;
}

function onCustomEventdocumentschema_brUpdateBillBenefitGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
	$obju_HISBillIns = new documentlinesschema_br(null,$objConnection,"u_hisbillins");
	$obju_HISBillRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillrooms");
	$obju_HISBillSplRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillsplrooms");
	$obju_HISBillMedSups = new documentlinesschema_br(null,$objConnection,"u_hisbillmedsups");
	$obju_HISBillLabs = new documentlinesschema_br(null,$objConnection,"u_hisbilllabtests");
	$obju_HISBillConsultancyFees = new documentlinesschema_br(null,$objConnection,"u_hisbillconsultancyfees");
	$obju_HISBillMiscs = new documentlinesschema_br(null,$objConnection,"u_hisbillmiscs");
	$objRs = new recordset(null,$objConnection);
	$pfs = array();
	$pfs["total"] = 0;
	$objRs->queryopen("select * from u_hishealthins where code='".$objTable->getudfvalue("u_inscode")."'");
	if ($objRs->queryfetchrow("NAME")) {
		$hmo = $objRs->fields["U_HMO"];
	} else return raiseError("Unable to retrieve Health Insurance[".$objTable->getudfvalue("u_inscode")."]"); 

	if ($obju_HISBills->getbykey($objTable->getudfvalue("u_billno"))) {
		if ($obju_HISBillIns->getbysql("DOCID='".$obju_HISBills->docid."' AND U_INSCODE='".$objTable->getudfvalue("u_inscode")."'")) {
			if (!$delete){
				$obju_HISBillIns->setudfvalue("u_status","C");
				$obju_HISBillIns->setudfvalue("u_claimno",$objTable->docno);
				$obju_HISBillIns->setudfvalue("u_amount",$objTable->getudfvalue("u_thisamount"));
			} else {
				$obju_HISBillIns->setudfvalue("u_status","");
				$obju_HISBillIns->setudfvalue("u_claimno","");
				$obju_HISBillIns->setudfvalue("u_amount",0);
			}	
			$obju_HISBillIns->privatedata["header"] = $obju_HISBills;
			$actionReturn = $obju_HISBillIns->update($obju_HISBillIns->docid,$obju_HISBillIns->lineid,$obju_HISBillIns->rcdversion);
		} //else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Health Insurance Claim No [".$objTable->docno."]");
		
		
		if ($actionReturn) {
			$objRs->queryopen("select * from u_hisinclaimrooms where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
			while ($objRs->queryfetchrow("NAME")) {
				if ($obju_HISBillRooms->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
					if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
						return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
					}
					if ($hmo==0) {
						if (!$delete) {
							$obju_HISBillRooms->setudfvalue("u_insamount",floatval($obju_HISBillRooms->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillRooms->setudfvalue("u_insamount",floatval($obju_HISBillRooms->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
						}
					} elseif($hmo==2) {
						if (!$delete) {
							$obju_HISBillRooms->setudfvalue("u_discamount",floatval($obju_HISBillRooms->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillRooms->setudfvalue("u_discamount",floatval($obju_HISBillRooms->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} else {
						if (!$delete) {
							$obju_HISBillRooms->setudfvalue("u_hmoamount",floatval($obju_HISBillRooms->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillRooms->setudfvalue("u_hmoamount",floatval($obju_HISBillRooms->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					}
					$obju_HISBillRooms->setudfvalue("u_netamount",floatval($obju_HISBillRooms->getudfvalue("u_amount"))-floatval($obju_HISBillRooms->getudfvalue("u_discamount"))-floatval($obju_HISBillRooms->getudfvalue("u_hmoamount"))-floatval($obju_HISBillRooms->getudfvalue("u_insamount")));
					$obju_HISBillRooms->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillRooms->update($obju_HISBillRooms->docid,$obju_HISBillRooms->lineid,$obju_HISBillRooms->rcdversion);
					$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
					if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
					//var_dump(array($actionReturn,$obju_HISBillRooms->getudfvalue("u_amount"),$obju_HISBillRooms->getudfvalue("u_insamount"),$obju_HISBillRooms->getudfvalue("u_hmoamount")));
					if (!$actionReturn) break;
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Room Line ID [".$objRs->fields["U_LINEID"]."]");
			}
		}	
		if ($actionReturn) {
			$objRs->queryopen("select * from u_hisinclaimmedsups where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
			while ($objRs->queryfetchrow("NAME")) {
				if ($obju_HISBillMedSups->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
					if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
						return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
					}
					if ($hmo==0) {
						if (!$delete) {
							$obju_HISBillMedSups->setudfvalue("u_insamount",floatval($obju_HISBillMedSups->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillMedSups->setudfvalue("u_insamount",floatval($obju_HISBillMedSups->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} elseif ($hmo==2) {
						if (!$delete) {
							$obju_HISBillMedSups->setudfvalue("u_discamount",floatval($obju_HISBillMedSups->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillMedSups->setudfvalue("u_discamount",floatval($obju_HISBillMedSups->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} else {
						if (!$delete) {
							$obju_HISBillMedSups->setudfvalue("u_hmoamount",floatval($obju_HISBillMedSups->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillMedSups->setudfvalue("u_hmoamount",floatval($obju_HISBillMedSups->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					}
					$obju_HISBillMedSups->setudfvalue("u_netamount",floatval($obju_HISBillMedSups->getudfvalue("u_amount"))-floatval($obju_HISBillMedSups->getudfvalue("u_discamount"))-floatval($obju_HISBillMedSups->getudfvalue("u_hmoamount"))-floatval($obju_HISBillMedSups->getudfvalue("u_insamount")));
					$obju_HISBillMedSups->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillMedSups->update($obju_HISBillMedSups->docid,$obju_HISBillMedSups->lineid,$obju_HISBillMedSups->rcdversion);
					$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
					if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
					//var_dump(array($actionReturn,$obju_HISBillMedSups->getudfvalue("u_amount"),$obju_HISBillMedSups->getudfvalue("u_insamount"),$obju_HISBillMedSups->getudfvalue("u_hmoamount")));
					if (!$actionReturn) break;
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Medicines & Supplies ID [".$objRs->fields["U_LINEID"]."]");
			}
		}	
		if ($actionReturn) {
			$objRs->queryopen("select * from u_hisinclaimlabs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
			while ($objRs->queryfetchrow("NAME")) {
				switch ($objRs->fields["U_BILLTYPE"]) {
					case "LAB":
						if ($obju_HISBillLabs->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
							if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
								return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
							}
							if ($hmo==0) {
								if (!$delete) {
									$obju_HISBillLabs->setudfvalue("u_insamount",floatval($obju_HISBillLabs->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillLabs->setudfvalue("u_insamount",floatval($obju_HISBillLabs->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} elseif ($hmo==2) {
								if (!$delete) {
									$obju_HISBillLabs->setudfvalue("u_discamount",floatval($obju_HISBillLabs->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillLabs->setudfvalue("u_discamount",floatval($obju_HISBillLabs->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} else {
								if (!$delete) {
									$obju_HISBillLabs->setudfvalue("u_hmoamount",floatval($obju_HISBillLabs->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillLabs->setudfvalue("u_hmoamount",floatval($obju_HISBillLabs->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							}
							$obju_HISBillLabs->setudfvalue("u_netamount",floatval($obju_HISBillLabs->getudfvalue("u_amount"))-floatval($obju_HISBillLabs->getudfvalue("u_discamount"))-floatval($obju_HISBillLabs->getudfvalue("u_hmoamount"))-floatval($obju_HISBillLabs->getudfvalue("u_insamount")));
							$obju_HISBillLabs->privatedata["header"] = $obju_HISBills;
							$actionReturn = $obju_HISBillLabs->update($obju_HISBillLabs->docid,$obju_HISBillLabs->lineid,$obju_HISBillLabs->rcdversion);
							//var_dump(array($actionReturn,$objRs->fields["U_REFTYPE"],$obju_HISBillLabs->getudfvalue("u_amount"),$obju_HISBillLabs->getudfvalue("u_insamount"),$obju_HISBillLabs->getudfvalue("u_hmoamount")));
							$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
							if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
							if (!$actionReturn) break;
						} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] X-Ray/Laboratory/Diagnostic ID [".$objRs->fields["U_LINEID"]."]");
						break;
					case "MED":
					case "SUP":
						if ($obju_HISBillMedSups->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
							if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
								return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
							}
							if ($hmo==0) {
								if (!$delete) {
									$obju_HISBillMedSups->setudfvalue("u_insamount",floatval($obju_HISBillMedSups->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillMedSups->setudfvalue("u_insamount",floatval($obju_HISBillMedSups->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} elseif ($hmo==2) {
								if (!$delete) {
									$obju_HISBillMedSups->setudfvalue("u_discamount",floatval($obju_HISBillMedSups->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillMedSups->setudfvalue("u_discamount",floatval($obju_HISBillMedSups->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} else {
								if (!$delete) {
									$obju_HISBillMedSups->setudfvalue("u_hmoamount",floatval($obju_HISBillMedSups->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillMedSups->setudfvalue("u_hmoamount",floatval($obju_HISBillMedSups->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							}
							$obju_HISBillMedSups->setudfvalue("u_netamount",floatval($obju_HISBillMedSups->getudfvalue("u_amount"))-floatval($obju_HISBillMedSups->getudfvalue("u_hmoamount"))-floatval($obju_HISBillMedSups->getudfvalue("u_discamount"))-floatval($obju_HISBillMedSups->getudfvalue("u_insamount")));
							$obju_HISBillMedSups->privatedata["header"] = $obju_HISBills;
							$actionReturn = $obju_HISBillMedSups->update($obju_HISBillMedSups->docid,$obju_HISBillMedSups->lineid,$obju_HISBillMedSups->rcdversion);
							//var_dump(array($actionReturn,$objRs->fields["U_REFTYPE"],$obju_HISBillMedSups->getudfvalue("u_amount"),$obju_HISBillMedSups->getudfvalue("u_insamount"),$obju_HISBillMedSups->getudfvalue("u_hmoamount")));
							$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
							if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
							if (!$actionReturn) break;
						} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Medicines & Supplies ID [".$objRs->fields["U_LINEID"]."]");
						break;
					case "MISC":
						if ($obju_HISBillMiscs->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
							if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
								return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
							}
							if ($hmo==0) {
								if (!$delete) {
									$obju_HISBillMiscs->setudfvalue("u_insamount",floatval($obju_HISBillMiscs->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillMiscs->setudfvalue("u_insamount",floatval($obju_HISBillMiscs->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} elseif ($hmo==2) {
								if (!$delete) {
									$obju_HISBillMiscs->setudfvalue("u_discamount",floatval($obju_HISBillMiscs->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillMiscs->setudfvalue("u_discamount",floatval($obju_HISBillMiscs->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} else {
								if (!$delete) {
									$obju_HISBillMiscs->setudfvalue("u_hmoamount",floatval($obju_HISBillMiscs->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillMiscs->setudfvalue("u_hmoamount",floatval($obju_HISBillMiscs->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							}
							$obju_HISBillMiscs->setudfvalue("u_netamount",floatval($obju_HISBillMiscs->getudfvalue("u_amount"))-floatval($obju_HISBillMiscs->getudfvalue("u_hmoamount"))-floatval($obju_HISBillMiscs->getudfvalue("u_discamount"))-floatval($obju_HISBillMiscs->getudfvalue("u_insamount")));
							$obju_HISBillMiscs->privatedata["header"] = $obju_HISBills;
							$actionReturn = $obju_HISBillMiscs->update($obju_HISBillMiscs->docid,$obju_HISBillMiscs->lineid,$obju_HISBillMiscs->rcdversion);
							//var_dump(array($actionReturn,$objRs->fields["U_REFTYPE"],$obju_HISBillMedSups->getudfvalue("u_amount"),$obju_HISBillMedSups->getudfvalue("u_insamount"),$obju_HISBillMedSups->getudfvalue("u_hmoamount")));
							$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
							if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
							if (!$actionReturn) break;
						} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Miscellaneous ID [".$objRs->fields["U_LINEID"]."]");
						break;
					case "SPLROOM":
						if ($obju_HISBillSplRooms->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
							if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
								return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
							}
							if ($hmo==0) {
								if (!$delete) {
									$obju_HISBillSplRooms->setudfvalue("u_insamount",floatval($obju_HISBillSplRooms->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillSplRooms->setudfvalue("u_insamount",floatval($obju_HISBillSplRooms->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} elseif ($hmo==2) {
								if (!$delete) {
									$obju_HISBillSplRooms->setudfvalue("u_discamount",floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillSplRooms->setudfvalue("u_discamount",floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							} else {
								if (!$delete) {
									$obju_HISBillSplRooms->setudfvalue("u_hmoamount",floatval($obju_HISBillSplRooms->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
								} else {
									$obju_HISBillSplRooms->setudfvalue("u_hmoamount",floatval($obju_HISBillSplRooms->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
									$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
								}	
							}
							$obju_HISBillSplRooms->setudfvalue("u_netamount",floatval($obju_HISBillSplRooms->getudfvalue("u_amount"))-floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))-floatval($obju_HISBillSplRooms->getudfvalue("u_hmoamount"))-floatval($obju_HISBillSplRooms->getudfvalue("u_insamount")));
							$obju_HISBillSplRooms->privatedata["header"] = $obju_HISBills;
							$actionReturn = $obju_HISBillSplRooms->update($obju_HISBillSplRooms->docid,$obju_HISBillSplRooms->lineid,$obju_HISBillSplRooms->rcdversion);
							$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
							if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
							//var_dump(array($actionReturn,$objRs->fields["U_REFTYPE"],$obju_HISBillSplRooms->getudfvalue("u_amount"),$obju_HISBillSplRooms->getudfvalue("u_insamount"),$obju_HISBillSplRooms->getudfvalue("u_hmoamount")));
							if (!$actionReturn) break;
						} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Special Room ID [".$objRs->fields["U_LINEID"]."]");
						break;
				}		
				if (!$actionReturn) break;
			}
		}	
		
		if ($actionReturn) {
			$objRs->queryopen("select * from u_hisinclaimsplrooms where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."'");
			while ($objRs->queryfetchrow("NAME")) {
				if ($obju_HISBillSplRooms->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
					if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees'")) {
						return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Hospital Fees].");
					}
					if ($hmo==0) {
						if (!$delete) {
							$obju_HISBillSplRooms->setudfvalue("u_insamount",floatval($obju_HISBillSplRooms->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillSplRooms->setudfvalue("u_insamount",floatval($obju_HISBillSplRooms->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} elseif ($hmo==2) {
						if (!$delete) {
							$obju_HISBillSplRooms->setudfvalue("u_discamount",floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillSplRooms->setudfvalue("u_discamount",floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} else {
						if (!$delete) {
							$obju_HISBillSplRooms->setudfvalue("u_discamount",floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillSplRooms->setudfvalue("u_discamount",floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					}
					$obju_HISBillSplRooms->setudfvalue("u_netamount",floatval($obju_HISBillSplRooms->getudfvalue("u_amount"))-floatval($obju_HISBillSplRooms->getudfvalue("u_discamount"))-floatval($obju_HISBillSplRooms->getudfvalue("u_hmoamount"))-floatval($obju_HISBillSplRooms->getudfvalue("u_insamount")));
					$obju_HISBillSplRooms->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillSplRooms->update($obju_HISBillSplRooms->docid,$obju_HISBillSplRooms->lineid,$obju_HISBillSplRooms->rcdversion);
					//var_dump(array($actionReturn,$obju_HISBillSplRooms->getudfvalue("u_amount"),$obju_HISBillSplRooms->getudfvalue("u_insamount"),$obju_HISBillSplRooms->getudfvalue("u_hmoamount")));
					$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
					if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
					if (!$actionReturn) break;
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Medicines & Supplies ID [".$objRs->fields["U_LINEID"]."]");
			}
		}
		
		if ($actionReturn) {
			$objRs->queryopen("select A.U_LINEID, A.U_DOCTORID, B.NAME AS U_DOCTORNAME, A.U_THISAMOUNT from u_hisinclaimconsultancyfees A, u_hisdoctors B where B.code=A.U_DOCTORID and A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.docid='".$objTable->docid."'");
			while ($objRs->queryfetchrow("NAME")) {
				$pfs["total"]+=$objRs->fields["U_THISAMOUNT"];
				if ($obju_HISBillConsultancyFees->getbykey($obju_HISBills->docid,$objRs->fields["U_LINEID"])) {
					if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='".$objRs->fields["U_DOCTORID"]."' AND U_FEETYPE='Professional Fees'")) {
						return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [Professional Fees] Doctor ID[".$objRs->fields["U_DOCTORID"]."]");
					}
					if (!isset($pfs[$objRs->fields["U_DOCTORID"]])) {
						$pfs[$objRs->fields["U_DOCTORID"]]["code"]=$objRs->fields["U_DOCTORID"];
						$pfs[$objRs->fields["U_DOCTORID"]]["name"]=$objRs->fields["U_DOCTORNAME"];
						$pfs[$objRs->fields["U_DOCTORID"]]["pf"]=0;
						$pfs[$objRs->fields["U_DOCTORID"]]["lineno"]=substr($obju_HISBillFees->getudfvalue("u_suffix"),1);
						$pfs[$objRs->fields["U_DOCTORID"]]["suffix"]=$obju_HISBillFees->getudfvalue("u_suffix");
					}	
					$pfs[$objRs->fields["U_DOCTORID"]]["pf"]+=$objRs->fields["U_THISAMOUNT"];
					if ($hmo==0) {
						if (!$delete) {
							$obju_HISBillConsultancyFees->setudfvalue("u_insamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillConsultancyFees->setudfvalue("u_insamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} elseif ($hmo==2) {
						if (!$delete) {
							$obju_HISBillConsultancyFees->setudfvalue("u_discamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillConsultancyFees->setudfvalue("u_discamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					} else {
						if (!$delete) {
							$obju_HISBillConsultancyFees->setudfvalue("u_hmoamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$objRs->fields["U_THISAMOUNT"]);
						} else {
							$obju_HISBillConsultancyFees->setudfvalue("u_hmoamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
							$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-$objRs->fields["U_THISAMOUNT"]);
						}	
					}
					$obju_HISBillConsultancyFees->setudfvalue("u_netamount",floatval($obju_HISBillConsultancyFees->getudfvalue("u_amount"))-floatval($obju_HISBillConsultancyFees->getudfvalue("u_discamount"))-floatval($obju_HISBillConsultancyFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillConsultancyFees->getudfvalue("u_insamount")));
					$obju_HISBillConsultancyFees->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillConsultancyFees->update($obju_HISBillConsultancyFees->docid,$obju_HISBillConsultancyFees->lineid,$obju_HISBillConsultancyFees->rcdversion);
					$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
					if ($actionReturn) $actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
					//var_dump(array($actionReturn,$obju_HISBillConsultancyFees->lineid,$obju_HISBillConsultancyFees->getudfvalue("u_amount"),$obju_HISBillConsultancyFees->getudfvalue("u_insamount"),$obju_HISBillConsultancyFees->getudfvalue("u_hmoamount")));
					if (!$actionReturn) break;
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Professional Fee ID [".$objRs->fields["U_LINEID"]."]");
			}
		}			
		if ($actionReturn) {
			
			if ($hmo==0) {
				if (!$delete) {
					$obju_HISBills->setudfvalue("u_insamount",floatval($obju_HISBills->getudfvalue("u_insamount"))+floatval($objTable->getudfvalue("u_thisamount")));
				} else {
					$obju_HISBills->setudfvalue("u_insamount",floatval($obju_HISBills->getudfvalue("u_insamount"))-floatval($objTable->getudfvalue("u_thisamount")));
				}	
			} elseif ($hmo==2) {
				if (!$delete) {
					$obju_HISBills->setudfvalue("u_discamount",floatval($obju_HISBills->getudfvalue("u_discamount"))+floatval($objTable->getudfvalue("u_thisamount")));
				} else {
					$obju_HISBills->setudfvalue("u_discamount",floatval($obju_HISBills->getudfvalue("u_discamount"))-floatval($objTable->getudfvalue("u_thisamount")));
				}	
			} else {
				if (!$delete) {
					$obju_HISBills->setudfvalue("u_hmoamount",floatval($obju_HISBills->getudfvalue("u_hmoamount"))+floatval($objTable->getudfvalue("u_thisamount")));
				} else {
					$obju_HISBills->setudfvalue("u_hmoamount",floatval($obju_HISBills->getudfvalue("u_hmoamount"))-floatval($objTable->getudfvalue("u_thisamount")));
				}	
			}
			$obju_HISBills->setudfvalue("u_netamount",floatval($obju_HISBills->getudfvalue("u_amount"))-floatval($obju_HISBills->getudfvalue("u_discamount"))-floatval($obju_HISBills->getudfvalue("u_insamount"))-floatval($obju_HISBills->getudfvalue("u_hmoamount"))-floatval($obju_HISBills->getudfvalue("u_pnamount")));
			$obju_HISBills->setudfvalue("u_dueamount",floatval($obju_HISBills->getudfvalue("u_netamount"))-floatval($obju_HISBills->getudfvalue("u_dpamt")));	
			if ($obju_HISBills->getudfvalue("u_dueamount")-$obju_HISBills->getudfvalue("u_paidamount")==0) $obju_HISBills->docstatus ="C";
			else $obju_HISBills->docstatus ="O";
			
			$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
		}	
		
		if ($actionReturn && $objTable->getudfvalue("u_thisamount")!=0) {
			/*
			$pfs = array();
			$pf = 0;
			if ($objTable->getudfvalue("u_hmo")!="2") {
				$objRs->queryopen("select A.U_DOCTORID, B.NAME AS U_DOCTORNAME, SUM(A.U_THISAMOUNT) AS U_THISAMOUNT from u_hisinclaimconsultancyfees A, u_hisdoctors B where B.CODE=A.U_DOCTORID and A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.docid='".$objTable->docid."' GROUP BY A.U_DOCTORID ORDER BY A.U_DOCTORID");
				while ($objRs->queryfetchrow("NAME")) {
					$pfdata = array();
					$pfdata["code"] = $objRs->fields["U_DOCTORID"];
					$pfdata["name"] = $objRs->fields["U_DOCTORNAME"];
					$pfdata["pf"] = $objRs->fields["U_THISAMOUNT"];
					array_push($pfs,$pfdata);
					$pf+=$objRs->fields["U_THISAMOUNT"];
				}		
			}
				*/	
			$objCustomers = new customers(null,$objConnection);
			$obju_HisHealthIns = new masterdataschema(null,$objConnection,"u_hishealthins");
			$objJvHdr = new journalvouchers(null,$objConnection);
			$objJvDtl = new journalvoucheritems(null,$objConnection);
			if ($objTable->getudfvalue("u_hmo")!="2") {
				if (!$objCustomers->getbykey($objTable->getudfvalue("u_inscode"))) {
					return raiseError("Unable to retrieve customer record for [".$objTable->getudfvalue("u_inscode")."].");
				}
			} else {
				if (!$obju_HisHealthIns->getbykey($objTable->getudfvalue("u_inscode"))) {
					return raiseError("Unable to retrieve health benefit record for [".$objTable->getudfvalue("u_inscode")."].");
				}
			}			
			$settings = getBusinessObjectSettings("JOURNALVOUCHER");
			
			$objJvHdr->prepareadd();
			$objJvHdr->objectcode = "JOURNALVOUCHER";
			$objJvHdr->sbo_post_flag = $settings["autopost"];
			$objJvHdr->jeposting = $settings["jeposting"];
			$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
			$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
			$objJvHdr->docduedate = $objJvHdr->docdate;
			$objJvHdr->taxdate = $objJvHdr->docdate;
			$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
			$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
			$objJvHdr->currency = $_SESSION["currency"];
			$objJvHdr->docstatus = "C";
			$objJvHdr->reference1 = $objTable->docno;
			$objJvHdr->reference2 = "Health Benefits";
			
			if (!$delete) $objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
			else $objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
			
			$objJvHdr->otherdocno = $objTable->getudfvalue("u_billno");
			$objJvHdr->otherdocdate = $objTable->getudfvalue("u_startdate");
			$objJvHdr->otherdocduedate = $objTable->getudfvalue("u_enddate");
			$objJvHdr->otherbprefno = $objTable->getudfvalue("u_refno");
			$objJvHdr->otherbpcode = $objTable->getudfvalue("u_patientid");
			$objJvHdr->otherbpname = $objTable->getudfvalue("u_patientname");
			
			$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_enddate") . 
				iif($objTable->getudfvalue("u_remarks")!="",";" . $objTable->getudfvalue("u_remarks"),"") . 
				iif($delete,"; Cancelled.",".");
			
			$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
			if ($actionReturn && ($objTable->getudfvalue("u_thisamount") - $pfs["total"])>0) {
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
				if ($objTable->getudfvalue("u_hmo")!="2") {
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objCustomers->custno;
					$objJvDtl->itemname = $objCustomers->custname;
					if (!$delete) {
						$objJvDtl->lineno = 1;
						$objJvDtl->reftype = "";
						$objJvDtl->refno = "";
					} else {
						$objJvDtl->reftype = "JOURNALVOUCHER";
						$objJvDtl->refno = $objTable->getudfvalue("u_jvdocno")."/1";
					}	
					$objJvDtl->othertrxtype = "Hospital Fees";
				} else {
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_HisHealthIns->getudfvalue("u_glacctno");
					$objJvDtl->itemname = $obju_HisHealthIns->getudfvalue("u_glacctname");
					$objJvDtl->reftype = "";
					$objJvDtl->refno = "";
				}	
				if (!$delete) {
					$objJvDtl->debit = $objTable->getudfvalue("u_thisamount") - $pfs["total"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objTable->getudfvalue("u_thisamount") - $pfs["total"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				}	
				if ($obju_HISBills->getudfvalue("u_billasone")==1) {
					$objJvDtl->remarks = "Based on Journal Voucher " . $objTable->getudfvalue("u_billno") . ". ".$objTable->getudfvalue("u_patientname");
				} else {
					$objJvDtl->remarks = "Based on Journal Voucher " . $objTable->getudfvalue("u_billno") . "/1. ".$objTable->getudfvalue("u_patientname"). ". Hospital Fees.";
				}	
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				//var_dump(array("debit",$objJvDtl->debit));
				$actionReturn = $objJvDtl->add();
			}
			if ($actionReturn) {
				foreach($pfs as $pfkey=>$pfdata) {
					if ($pfkey=="total") continue;
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objCustomers->custno;
					$objJvDtl->itemname = $objCustomers->custname;
					if (!$delete) {
						$objJvDtl->lineno = $pfdata["lineno"];
						$objJvDtl->reftype = "";
						$objJvDtl->refno = "";
					} else {
						$objJvDtl->reftype = "JOURNALVOUCHER";
						$objJvDtl->refno = $objTable->getudfvalue("u_jvdocno")."/".$pfdata["lineno"];
					}	
					$objJvDtl->otherbpcode = $pfdata["code"];
					$objJvDtl->otherbpname = $pfdata["name"];
					$objJvDtl->othertrxtype = "Professional Fees";
					if (!$delete) {
						$objJvDtl->debit = $pfdata["pf"];
						$objJvDtl->grossamount = $objJvDtl->debit;
					} else {
						$objJvDtl->credit = $pfdata["pf"];
						$objJvDtl->grossamount = $objJvDtl->credit;
					}	
					if ($obju_HISBills->getudfvalue("u_billasone")==1) {
						$objJvDtl->remarks = "Based on Journal Voucher " . $objTable->getudfvalue("u_billno") . ". ".$objTable->getudfvalue("u_patientname");
					} else {
						$objJvDtl->remarks = "Based on Journal Voucher " . $objTable->getudfvalue("u_billno") . "/".$pfdata["lineno"]. ". ".$objTable->getudfvalue("u_patientname"). ". Professional Fees. " . $pfdata["name"];
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					//var_dump(array("debit",$objJvDtl->debit));
					$actionReturn = $objJvDtl->add();
					if (!$actionReturn) break;
				}
			}			

			if ($actionReturn) {
				if ($obju_HISBills->getudfvalue("u_billasone")==1) {
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
					$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
					$objJvDtl->reftype = "ARINVOICE";
					$objJvDtl->refno = $objTable->getudfvalue("u_billno");
					if (!$delete) {
						$objJvDtl->credit = $objTable->getudfvalue("u_thisamount");
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $objTable->getudfvalue("u_thisamount");
						$objJvDtl->grossamount = $objJvDtl->debit;
					}
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				} else {
					if ($objTable->getudfvalue("u_thisamount") - $pfs["total"]>0) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
						$objJvDtl->itemtype = "C";
						$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
						$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
						$objJvDtl->reftype = "JOURNALVOUCHER";
						$objJvDtl->refno = $objTable->getudfvalue("u_billno") . "/1";
						if (!$delete) {
							$objJvDtl->credit = $objTable->getudfvalue("u_thisamount") - $pfs["total"];
							$objJvDtl->grossamount = $objJvDtl->credit;
						} else {
							$objJvDtl->debit = $objTable->getudfvalue("u_thisamount") - $pfs["total"];
							$objJvDtl->grossamount = $objJvDtl->debit;
						}
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();
					}	
					if ($actionReturn) {					
						foreach($pfs as $pfkey=>$pfdata) {
							if ($pfkey=="total") continue;
							$objJvDtl->prepareadd();
							$objJvDtl->docid = $objJvHdr->docid;
							$objJvDtl->objectcode = $objJvHdr->objectcode;
							$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
							$objJvDtl->refbranch = $_SESSION["branch"];
							//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
							$objJvDtl->itemtype = "C";
							$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
							$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
							$objJvDtl->reftype = "ARINVOICE";
							$objJvDtl->refno = $objTable->getudfvalue("u_billno").$pfdata["suffix"];
							if (!$delete) {
								$objJvDtl->credit = $pfdata["pf"];
								$objJvDtl->grossamount = $objJvDtl->credit;
							} else {
								$objJvDtl->debit = $pfdata["pf"];
								$objJvDtl->grossamount = $objJvDtl->debit;
							}
							$objJvHdr->totaldebit += $objJvDtl->debit ;
							$objJvHdr->totalcredit += $objJvDtl->credit ;
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->add();
							
							if (!$actionReturn) break;
						}	
					}					
				}	
			}

			if ($actionReturn) $actionReturn = $objJvHdr->add();				
		}
		
	} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."]");
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brUpdateBillBenefitGPSHIS");
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostPHICCMGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_billno")=="") return true;
	
	$obju_HISPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
	$objRs = new recordset(null,$objConnection);
	
	$exclaimamt = floatval($objTable->getudfvalue("u_totalamt")) - (floatval($objTable->getudfvalue("u_claimhc"))+floatval($objTable->getudfvalue("u_claimpf")));

	if ($delete) {
		if (floatval($objTable->getudfvalue("u_claimhc"))!=0) {
			if ($obju_HISPronotes->getbysql("U_BILLNO='".$objTable->getudfvalue("u_billno")."' AND U_GUARANTORCODE='PHIC' AND U_FEETYPE='Hospital Fees' AND U_CLAIMNO='$objTable->docno' AND U_EXCLAIM=0")) {
				$obju_HISPronotes->docstatus="CN";
				$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
			} else return raiseError("Unable to find Credit Memo for Health Benefit [PHIC] and Hospital Fees.");
		}
		
		if ($actionReturn && $exclaimamt>0) {
			if ($obju_HISPronotes->getbysql("U_BILLNO='".$objTable->getudfvalue("u_billno")."' AND U_GUARANTORCODE='PHIC' AND U_FEETYPE='Hospital Fees' AND U_CLAIMNO='$objTable->docno' AND U_EXCLAIM=1")) {
				$obju_HISPronotes->docstatus="CN";
				$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
			} else return raiseError("Unable to find Credit Memo for Health Benefit [PHIC] and Hospital Fees - Excess Claims.");
		}
		if ($actionReturn) {
			$objRs->queryopen("select u_doctorid, u_doctorname, u_doctorservice, u_actpf, u_bdiscpf, u_claimpf, u_balpf from u_hisphicclaimpfs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_claimpf>0 group by u_doctorid");
			while ($objRs->queryfetchrow("NAME")) {
				if ($obju_HISPronotes->getbysql("U_BILLNO='".$objTable->getudfvalue("u_billno")."' AND U_GUARANTORCODE='PHIC' AND U_DOCTORID='".$objRs->fields["u_doctorid"]."' AND U_FEETYPE='Professional Fees' AND U_CLAIMNO='$objTable->docno' AND DOCSTATUS<>'CN'")) {
					$obju_HISPronotes->docstatus="CN";
					$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
				}// else return raiseError("Unable to find Credit Memo for Health Benefit [PHIC], Doctor [".$objRs->fields["u_doctorname"]."] and Professional Fees.");
				if (!$actionReturn) break;
			}
		}
			
	} else {
		if (floatval($objTable->getudfvalue("u_claimhc"))!=0) {
			$obju_HISPronotes->prepareadd();
			$obju_HISPronotes->docseries = getSeries("u_hispronotes","Credit Memo",$objConnection);
			$obju_HISPronotes->docno = getNextSeriesNoByBranch("u_hispronotes",$obju_HISPronotes->docseries,$objTable->getudfvalue("u_enddate"),$objConnection);
			$obju_HISPronotes->docid = getNextIdByBranch("u_hispronotes",$objConnection);
			$obju_HISPronotes->docstatus = "O";
			$obju_HISPronotes->setudfvalue("u_claimno",$objTable->docno);
			$obju_HISPronotes->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
			$obju_HISPronotes->setudfvalue("u_billno",$objTable->getudfvalue("u_billno"));
			$obju_HISPronotes->setudfvalue("u_guarantorcode","PHIC");
			$obju_HISPronotes->setudfvalue("u_hmo",0);
			$obju_HISPronotes->setudfvalue("u_type","Credit Memo");
			$obju_HISPronotes->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
			$obju_HISPronotes->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
			$obju_HISPronotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISPronotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$obju_HISPronotes->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
			$obju_HISPronotes->setudfvalue("u_age",$objTable->getudfvalue("u_age_y"));
			$obju_HISPronotes->setudfvalue("u_membertype",$objTable->getudfvalue("u_membertype"));
			if ($objTable->getudfvalue("u_ismember")==0) {
				$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_memberid"));
				$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_membername"));
			} else {
				$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_phicno"));
				$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_patientname"));
			}	
			$obju_HISPronotes->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
			$obju_HISPronotes->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
			$obju_HISPronotes->setudfvalue("u_feetype","Hospital Fees");
			$obju_HISPronotes->setudfvalue("u_amount",floatval($objTable->getudfvalue("u_acthc"))-floatval($objTable->getudfvalue("u_bdischc")));
			$obju_HISPronotes->setudfvalue("u_pnamount",floatval($objTable->getudfvalue("u_claimhc")));
			$obju_HISPronotes->setudfvalue("u_netamount",floatval($obju_HISPronotes->getudfvalue("u_amount"))-floatval($obju_HISPronotes->getudfvalue("u_pnamount")));
			$actionReturn = $obju_HISPronotes->add();
		}
		if ($actionReturn && $exclaimamt>0) {
			$obju_HISPronotes->prepareadd();
			$obju_HISPronotes->docseries = getSeries("u_hispronotes","Credit Memo",$objConnection);
			$obju_HISPronotes->docno = getNextSeriesNoByBranch("u_hispronotes",$obju_HISPronotes->docseries,$objTable->getudfvalue("u_enddate"),$objConnection);
			$obju_HISPronotes->docid = getNextIdByBranch("u_hispronotes",$objConnection);
			$obju_HISPronotes->docstatus = "O";
			$obju_HISPronotes->setudfvalue("u_claimno",$objTable->docno);
			$obju_HISPronotes->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
			$obju_HISPronotes->setudfvalue("u_billno",$objTable->getudfvalue("u_billno"));
			$obju_HISPronotes->setudfvalue("u_guarantorcode","PHIC");
			$obju_HISPronotes->setudfvalue("u_hmo",0);
			$obju_HISPronotes->setudfvalue("u_type","Credit Memo");
			$obju_HISPronotes->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
			$obju_HISPronotes->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
			$obju_HISPronotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISPronotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$obju_HISPronotes->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
			$obju_HISPronotes->setudfvalue("u_age",$objTable->getudfvalue("u_age_y"));
			$obju_HISPronotes->setudfvalue("u_membertype",$objTable->getudfvalue("u_membertype"));
			if ($objTable->getudfvalue("u_ismember")==0) {
				$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_memberid"));
				$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_membername"));
			} else {
				$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_phicno"));
				$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_patientname"));
			}	
			$obju_HISPronotes->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
			$obju_HISPronotes->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
			$obju_HISPronotes->setudfvalue("u_feetype","Hospital Fees");
			$obju_HISPronotes->setudfvalue("u_amount",0);
			$obju_HISPronotes->setudfvalue("u_pnamount",$exclaimamt);
			$obju_HISPronotes->setudfvalue("u_netamount",$exclaimamt*-1);
			$obju_HISPronotes->setudfvalue("u_exclaim",1);
			$actionReturn = $obju_HISPronotes->add();
		}
		if ($actionReturn) {
			$objRs->queryopen("select u_doctorid, u_doctorname, u_doctorservice, sum(u_actpf - u_bdiscpf) as u_actpf, sum(u_claimpf) as u_claimpf, u_balpf from u_hisphicclaimpfs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_claimpf>0 group by u_doctorid");
			while ($objRs->queryfetchrow("NAME")) {
				$obju_HISPronotes->prepareadd();
				$obju_HISPronotes->docseries = getSeries("u_hispronotes","Credit Memo",$objConnection);
				$obju_HISPronotes->docno = getNextSeriesNoByBranch("u_hispronotes",$obju_HISPronotes->docseries,$objTable->getudfvalue("u_enddate"),$objConnection);
				$obju_HISPronotes->docid = getNextIdByBranch("u_hispronotes",$objConnection);
				$obju_HISPronotes->docstatus = "O";
				$obju_HISPronotes->setudfvalue("u_claimno",$objTable->docno);
				$obju_HISPronotes->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
				$obju_HISPronotes->setudfvalue("u_billno",$objTable->getudfvalue("u_billno"));
				$obju_HISPronotes->setudfvalue("u_guarantorcode","PHIC");
				$obju_HISPronotes->setudfvalue("u_hmo",0);
				$obju_HISPronotes->setudfvalue("u_type","Credit Memo");
				$obju_HISPronotes->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
				$obju_HISPronotes->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
				$obju_HISPronotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
				$obju_HISPronotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
				$obju_HISPronotes->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
				$obju_HISPronotes->setudfvalue("u_age",$objTable->getudfvalue("u_age_y"));
				$obju_HISPronotes->setudfvalue("u_membertype",$objTable->getudfvalue("u_membertype"));
				if ($objTable->getudfvalue("u_ismember")==0) {
					$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_memberid"));
					$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_membername"));
				} else {
					$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_phicno"));
					$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_patientname"));
				}	
				$obju_HISPronotes->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
				$obju_HISPronotes->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
				$obju_HISPronotes->setudfvalue("u_feetype","Professional Fees");
				$obju_HISPronotes->setudfvalue("u_doctorid",$objRs->fields["u_doctorid"]);
				$obju_HISPronotes->setudfvalue("u_amount",$objRs->fields["u_actpf"]);
				$obju_HISPronotes->setudfvalue("u_pnamount",$objRs->fields["u_claimpf"]);
				$obju_HISPronotes->setudfvalue("u_netamount",floatval($obju_HISPronotes->getudfvalue("u_amount"))-floatval($obju_HISPronotes->getudfvalue("u_pnamount")));
				$actionReturn = $obju_HISPronotes->add();
				if (!$actionReturn) break;
			}
		}
	}
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostPHICCMGPSHIS()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostPKGCMGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_billno")=="") return true;
	
	$obju_HISPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
	$objRs = new recordset(null,$objConnection);
	
	if ($delete) {
		if (floatval($objTable->getudfvalue("u_pkghc"))!=0) {
			if ($obju_HISPronotes->getbysql("U_BILLNO='".$objTable->getudfvalue("u_billno")."' AND U_GUARANTORCODE='".$objTable->getudfvalue("u_guarantorcode")."' AND U_FEETYPE='Hospital Fees' AND U_CLAIMNO='$objTable->docno' AND U_EXCLAIM=0 AND DOCSTATUS<>'CN'")) {
				$obju_HISPronotes->docstatus="CN";
				$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
			} //else return raiseError("Unable to find Credit Memo for Health Benefit [".$objTable->getudfvalue("u_guarantorcode")."] and Hospital Fees.");
			if ($actionReturn) {
				if ($obju_HISPronotes->getbysql("U_BILLNO='".$objTable->getudfvalue("u_billno")."' AND U_GUARANTORCODE='".$objTable->getudfvalue("u_guarantorcode")."' AND U_FEETYPE='After Bill Charges' AND U_CLAIMNO='$objTable->docno' AND U_EXCLAIM=0 AND DOCSTATUS<>'CN'")) {
					$obju_HISPronotes->docstatus="CN";
					$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
				}
			}
		}
		
		if ($actionReturn) {
			$objRs->queryopen("select u_doctorid, u_feetype, u_actpf, u_pkgpf, u_balpf from u_hispkgclaimpfs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_pkgpf>0");
			while ($objRs->queryfetchrow("NAME")) {
				if ($obju_HISPronotes->getbysql("U_BILLNO='".$objTable->getudfvalue("u_billno")."' AND U_GUARANTORCODE='".$objTable->getudfvalue("u_guarantorcode")."' AND U_DOCTORID='".$objRs->fields["u_doctorid"]."' AND U_FEETYPE='".$objRs->fields["u_feetype"]."' AND U_CLAIMNO='$objTable->docno' AND DOCSTATUS<>'CN'")) {
					$obju_HISPronotes->docstatus="CN";
					$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
				}// else return raiseError("Unable to find Credit Memo for Health Benefit [PHIC], Doctor [".$objRs->fields["u_doctorname"]."] and Professional Fees.");
				if (!$actionReturn) break;
			}
		}
			
	} else {
		if (floatval($objTable->getudfvalue("u_pkghc"))!=0) {
			$u_pkghc = $objTable->getudfvalue("u_pkghc");
			if ($obju_HISBills->getbykey($objTable->getudfvalue("u_billno"))) {
				if ($obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='' AND U_FEETYPE='Hospital Fees' AND (U_NETAMOUNT-U_PAIDAMOUNT)>0")) {
					$obju_HISPronotes->prepareadd();
					$obju_HISPronotes->docseries = getSeries("u_hispronotes","Credit Memo",$objConnection);
					$obju_HISPronotes->docno = getNextSeriesNoByBranch("u_hispronotes",$obju_HISPronotes->docseries,$objTable->getudfvalue("u_enddate"),$objConnection);
					$obju_HISPronotes->docid = getNextIdByBranch("u_hispronotes",$objConnection);
					$obju_HISPronotes->docstatus = "O";
					$obju_HISPronotes->setudfvalue("u_claimno",$objTable->docno);
					$obju_HISPronotes->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
					$obju_HISPronotes->setudfvalue("u_billno",$objTable->getudfvalue("u_billno"));
					$obju_HISPronotes->setudfvalue("u_guarantorcode",$objTable->getudfvalue("u_guarantorcode"));
					$obju_HISPronotes->setudfvalue("u_hmo",$objTable->getudfvalue("u_hmo"));
					$obju_HISPronotes->setudfvalue("u_type","Credit Memo");
					$obju_HISPronotes->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
					$obju_HISPronotes->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
					$obju_HISPronotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
					$obju_HISPronotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
					$obju_HISPronotes->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
					$obju_HISPronotes->setudfvalue("u_age",$objTable->getudfvalue("u_age_y"));
					$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_memberid"));
					$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_membername"));
					$obju_HISPronotes->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
					$obju_HISPronotes->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
					$obju_HISPronotes->setudfvalue("u_feetype","Hospital Fees");
					$obju_HISPronotes->setudfvalue("u_amount",$obju_HISBillFees->getudfvalue("u_netamount")-$obju_HISBillFees->getudfvalue("u_paidamount"));
					if (($obju_HISBillFees->getudfvalue("u_netamount")-$obju_HISBillFees->getudfvalue("u_paidamount"))<=$u_pkghc) {
						$obju_HISPronotes->setudfvalue("u_pnamount",$obju_HISBillFees->getudfvalue("u_netamount")-$obju_HISBillFees->getudfvalue("u_paidamount"));
						$u_pkghc-=($obju_HISBillFees->getudfvalue("u_netamount")-$obju_HISBillFees->getudfvalue("u_paidamount"));
					} else {
						$obju_HISPronotes->setudfvalue("u_pnamount",$u_pkghc);
						$u_pkghc=0;
					}	
					$obju_HISPronotes->setudfvalue("u_netamount",floatval($obju_HISPronotes->getudfvalue("u_amount"))-floatval($obju_HISPronotes->getudfvalue("u_pnamount")));
					$actionReturn = $obju_HISPronotes->add();
				}	
			} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."]");	
			if ($actionReturn && $u_pkghc>0) {
				$obju_HISPronotes->prepareadd();
				$obju_HISPronotes->docseries = getSeries("u_hispronotes","Credit Memo",$objConnection);
				$obju_HISPronotes->docno = getNextSeriesNoByBranch("u_hispronotes",$obju_HISPronotes->docseries,$objTable->getudfvalue("u_enddate"),$objConnection);
				$obju_HISPronotes->docid = getNextIdByBranch("u_hispronotes",$objConnection);
				$obju_HISPronotes->docstatus = "O";
				$obju_HISPronotes->setudfvalue("u_claimno",$objTable->docno);
				$obju_HISPronotes->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
				$obju_HISPronotes->setudfvalue("u_billno",$objTable->getudfvalue("u_billno"));
				$obju_HISPronotes->setudfvalue("u_guarantorcode",$objTable->getudfvalue("u_guarantorcode"));
				$obju_HISPronotes->setudfvalue("u_hmo",$objTable->getudfvalue("u_hmo"));
				$obju_HISPronotes->setudfvalue("u_type","Credit Memo");
				$obju_HISPronotes->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
				$obju_HISPronotes->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
				$obju_HISPronotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
				$obju_HISPronotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
				$obju_HISPronotes->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
				$obju_HISPronotes->setudfvalue("u_age",$objTable->getudfvalue("u_age_y"));
				$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_memberid"));
				$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_membername"));
				$obju_HISPronotes->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
				$obju_HISPronotes->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
				$obju_HISPronotes->setudfvalue("u_feetype","After Bill Charges");
				$obju_HISPronotes->setudfvalue("u_amount",$u_pkghc);
				$obju_HISPronotes->setudfvalue("u_pnamount",$u_pkghc);
				$obju_HISPronotes->setudfvalue("u_netamount",floatval($obju_HISPronotes->getudfvalue("u_amount"))-floatval($obju_HISPronotes->getudfvalue("u_pnamount")));
				$actionReturn = $obju_HISPronotes->add();
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("select u_doctorid, u_feetype, u_actpf, u_pkgpf, u_balpf from u_hispkgclaimpfs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid' and u_pkgpf>0");
			while ($objRs->queryfetchrow("NAME")) {
				$obju_HISPronotes->prepareadd();
				$obju_HISPronotes->docseries = getSeries("u_hispronotes","Credit Memo",$objConnection);
				$obju_HISPronotes->docno = getNextSeriesNoByBranch("u_hispronotes",$obju_HISPronotes->docseries,$objTable->getudfvalue("u_enddate"),$objConnection);
				$obju_HISPronotes->docid = getNextIdByBranch("u_hispronotes",$objConnection);
				$obju_HISPronotes->docstatus = "O";
				$obju_HISPronotes->setudfvalue("u_claimno",$objTable->docno);
				$obju_HISPronotes->setudfvalue("u_docdate",$objTable->getudfvalue("u_docdate"));
				$obju_HISPronotes->setudfvalue("u_billno",$objTable->getudfvalue("u_billno"));
				$obju_HISPronotes->setudfvalue("u_guarantorcode",$objTable->getudfvalue("u_guarantorcode"));
				$obju_HISPronotes->setudfvalue("u_hmo",$objTable->getudfvalue("u_hmo"));
				$obju_HISPronotes->setudfvalue("u_type","Credit Memo");
				$obju_HISPronotes->setudfvalue("u_reftype",$objTable->getudfvalue("u_reftype"));
				$obju_HISPronotes->setudfvalue("u_refno",$objTable->getudfvalue("u_refno"));
				$obju_HISPronotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
				$obju_HISPronotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
				$obju_HISPronotes->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
				$obju_HISPronotes->setudfvalue("u_age",$objTable->getudfvalue("u_age_y"));
				$obju_HISPronotes->setudfvalue("u_memberid",$objTable->getudfvalue("u_memberid"));
				$obju_HISPronotes->setudfvalue("u_membername",$objTable->getudfvalue("u_membername"));
				$obju_HISPronotes->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
				$obju_HISPronotes->setudfvalue("u_enddate",$objTable->getudfvalue("u_enddate"));
				$obju_HISPronotes->setudfvalue("u_feetype",$objRs->fields["u_feetype"]);
				$obju_HISPronotes->setudfvalue("u_doctorid",$objRs->fields["u_doctorid"]);
				$obju_HISPronotes->setudfvalue("u_amount",$objRs->fields["u_actpf"]);
				$obju_HISPronotes->setudfvalue("u_pnamount",$objRs->fields["u_pkgpf"]);
				$obju_HISPronotes->setudfvalue("u_netamount",floatval($obju_HISPronotes->getudfvalue("u_amount"))-floatval($obju_HISPronotes->getudfvalue("u_pnamount")));
				$actionReturn = $obju_HISPronotes->add();
				if (!$actionReturn) break;
			}
		}
	}
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostPKGCMGPSHIS()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brUpdateBillPronoteGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
	$obju_HISBillPNs = new documentlinesschema_br(null,$objConnection,"u_hisbillpns");
	$obju_HISBillRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillrooms");
	$obju_HISBillSplRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillsplrooms");
	$obju_HISBillMedSups = new documentlinesschema_br(null,$objConnection,"u_hisbillmedsups");
	$obju_HISBillLabs = new documentlinesschema_br(null,$objConnection,"u_hisbilllabtests");
	$obju_HISBillConsultancyFees = new documentlinesschema_br(null,$objConnection,"u_hisbillconsultancyfees");
	$obju_HISBillMiscs = new documentlinesschema_br(null,$objConnection,"u_hisbillmiscs");
	$obju_HISDoctors = new masterdataschema(null,$objConnection,"u_hisdoctors");
	$obju_HISPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
	$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
	$objRs = new recordset(null,$objConnection);
	$afterbillcharges = array();
	$objRs->queryopen("select * from u_hishealthins where code='".$objTable->getudfvalue("u_guarantorcode")."'");
	if ($objRs->queryfetchrow("NAME")) {
		//$hmo = $objRs->fields["U_HMO"];
	} else return raiseError("Unable to retrieve Health Benefit [".$objTable->getudfvalue("u_guarantorcode")."]"); 

	if ($objTable->getudfvalue("u_migrated")=="0") {
		if ($obju_HISBills->getbykey($objTable->getudfvalue("u_billno"))) {
			if ($delete){
				if ($obju_HISBillPNs->getbysql("DOCID='".$obju_HISBills->docid."' AND U_PNNO='".$objTable->docno."'")) {
				
					$obju_HISBillPNs->setudfvalue("u_status","CN");
					$obju_HISBillPNs->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillPNs->update($obju_HISBillPNs->docid,$obju_HISBillPNs->lineid,$obju_HISBillPNs->rcdversion);
				} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Promossory Note No. [".$objTable->docno."]");
			}
			
			if ($objTable->getudfvalue("u_feetype")=="After Bill Charges") {
				$u_pnamount = $objTable->getudfvalue("u_pnamount");
				while ($u_pnamount!=0) {
					if ($delete){
						if ($obju_HISCharges->getbysql("u_reftype='".$objTable->getudfvalue("u_reftype")."' and u_refno='".$objTable->getudfvalue("u_refno")."' and U_PREPAID=0 AND U_BILLNO='".$objTable->docno."' and DOCSTATUS not in ('CN')")) {
							if ($obju_HISCharges->getudfvalue("u_amount")<=$u_pnamount) {
								$obju_HISCharges->setudfvalue("u_billno","");
								$obju_HISCharges->docstatus = "O";
								$u_pnamount-=$obju_HISCharges->getudfvalue("u_amount");
								$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
								if ($actionReturn) $afterbillcharges[$obju_HISCharges->docno] = $obju_HISCharges->getudfvalue("u_amount");
							} else return raiseError("Cannot partialy open after bill charges [".$obju_HISCharges->getudfvalue("u_amount")."] promisory note amount [$u_pnamount].");
						} else return raiseError("Insuficient charges for remaining promisory note amount [$u_pnamount].");
					} else {
						if ($obju_HISCharges->getbysql("u_reftype='".$objTable->getudfvalue("u_reftype")."' and u_refno='".$objTable->getudfvalue("u_refno")."' and U_PREPAID=0 AND U_BILLNO='' and DOCSTATUS not in ('CN')")) {
							if ($obju_HISCharges->getudfvalue("u_amount")<=$u_pnamount) {
								$obju_HISCharges->setudfvalue("u_billno",$objTable->docno);
								$obju_HISCharges->docstatus = "C";
								$u_pnamount-=$obju_HISCharges->getudfvalue("u_amount");
								$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
								if ($actionReturn) $afterbillcharges[$obju_HISCharges->docno] = $obju_HISCharges->getudfvalue("u_amount");
							} else return raiseError("Cannot partialy settle after bill charges [".$obju_HISCharges->getudfvalue("u_amount")."] promisory note amount [$u_pnamount].");
						} else return raiseError("Insuficient charges for remaining promisory note amount [$u_pnamount].");
					}	
					if (!$actionReturn) break;
				}
			} else {
				if ($objTable->getudfvalue("u_exclaim")=="0" && $objTable->getudfvalue("u_btrefno")=="") {
					if ($actionReturn) {
						//var_dump(array($objTable->getudfvalue("u_doctorid"),$objTable->getudfvalue("u_feetype")));
						if (!$obju_HISBillFees->getbysql("DOCID='".$obju_HISBills->docid."' AND U_DOCTORID='".$objTable->getudfvalue("u_doctorid")."' AND U_FEETYPE='".$objTable->getudfvalue("u_feetype")."'")) {
							return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."] Type [".$objTable->getudfvalue("u_feetype")."] Doctor ID[".$objTable->getudfvalue("u_doctorid")."]");
						}
					}
					if ($actionReturn) {
						$typesign = 1;
						$statsign = 1;
						if ($objTable->getudfvalue("u_type")=="Debit Memo") $typesign = -1;
						if ($delete) $statsign = -1;
						$amount = floatval($objTable->getudfvalue("u_pnamount"))*$typesign*$statsign;
						switch ($objTable->getudfvalue("u_hmo")) {
							case "0": 
								$obju_HISBills->setudfvalue("u_insamount",floatval($obju_HISBills->getudfvalue("u_insamount"))+$amount);
								$obju_HISBillFees->setudfvalue("u_insamount",floatval($obju_HISBillFees->getudfvalue("u_insamount"))+$amount); 
								if ($objTable->getudfvalue("u_caserate")=="1st") {
									$obju_HISBillFees->setudfvalue("u_caserate1",floatval($obju_HISBillFees->getudfvalue("u_caserate1"))+$amount); 
								} elseif ($objTable->getudfvalue("u_caserate")=="2nd") {
									$obju_HISBillFees->setudfvalue("u_caserate2",floatval($obju_HISBillFees->getudfvalue("u_caserate2"))+$amount); 
								}
								break;
							case "2": 
							case "7": 
								$obju_HISBills->setudfvalue("u_discamount",floatval($obju_HISBills->getudfvalue("u_discamount"))+$amount);
								$obju_HISBillFees->setudfvalue("u_discamount",floatval($obju_HISBillFees->getudfvalue("u_discamount"))+$amount); 
								break;
							case "1": 
							case "3": 
							case "4": 
							case "6": 
								$obju_HISBills->setudfvalue("u_hmoamount",floatval($obju_HISBills->getudfvalue("u_hmoamount"))+$amount);
								$obju_HISBillFees->setudfvalue("u_hmoamount",floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))+$amount); 
								break;
							case "5": 
								$obju_HISBills->setudfvalue("u_pnamount",floatval($obju_HISBills->getudfvalue("u_pnamount"))+$amount);
								$obju_HISBillFees->setudfvalue("u_pnamount",floatval($obju_HISBillFees->getudfvalue("u_pnamount"))+$amount); 
								break;
							default: 
								return raiseError("Invalid Health Benefit Type[".$objTable->getudfvalue("u_hmo")."]."); 
								break;
						}		
						$obju_HISBills->setudfvalue("u_netamount",floatval($obju_HISBills->getudfvalue("u_amount"))-floatval($obju_HISBills->getudfvalue("u_discamount"))-floatval($obju_HISBills->getudfvalue("u_hmoamount"))-floatval($obju_HISBills->getudfvalue("u_insamount"))-floatval($obju_HISBills->getudfvalue("u_pnamount")));
						$obju_HISBillFees->setudfvalue("u_netamount",floatval($obju_HISBillFees->getudfvalue("u_amount"))-floatval($obju_HISBillFees->getudfvalue("u_discamount"))-floatval($obju_HISBillFees->getudfvalue("u_hmoamount"))-floatval($obju_HISBillFees->getudfvalue("u_insamount"))-floatval($obju_HISBillFees->getudfvalue("u_pnamount")));
						/*if ($obju_HISBillFees->getudfvalue("u_netamount")<0) {
							return raiseError("Bill No.[".$objTable->getudfvalue("u_billno")."] Type [".$objTable->getudfvalue("u_feetype")."] Doctor ID[".$objTable->getudfvalue("u_doctorid")."] cannot be negative [".$obju_HISBillFees->getudfvalue("u_netamount")."]");
						}*/
						$obju_HISBills->setudfvalue("u_dueamount",floatval($obju_HISBills->getudfvalue("u_netamount"))-floatval($obju_HISBills->getudfvalue("u_dpamt")));	
						$actionReturn = $obju_HISBillFees->update($obju_HISBillFees->docid,$obju_HISBillFees->lineid,$obju_HISBillFees->rcdversion);
						if ($obju_HISBills->getudfvalue("u_dueamount")-$obju_HISBills->getudfvalue("u_paidamount")==0) $obju_HISBills->docstatus ="C";
						else $obju_HISBills->docstatus ="O";
						if ($actionReturn) $actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
					}	
				}
			}
										
			if ($actionReturn && $objTable->getudfvalue("u_pnamount")!=0) {
			
				$objCustomers = new customers(null,$objConnection);
				$obju_HisHealthIns = new masterdataschema(null,$objConnection,"u_hishealthins");
				$objJvHdr = new journalvouchers(null,$objConnection);
				$objJvDtl = new journalvoucheritems(null,$objConnection);
	
				if ($objTable->getudfvalue("u_hmo")=="6") {
					if (!$objCustomers->getbykey($objTable->getudfvalue("u_memberid"))) {
						return raiseError("Unable to retrieve customer record for [".$objTable->getudfvalue("u_memberid")."-".$objTable->getudfvalue("u_membername")."].");
					}
				} elseif ($objTable->getudfvalue("u_hmo")!="2" && $objTable->getudfvalue("u_hmo")!="7") {
					if (!$objCustomers->getbykey($objTable->getudfvalue("u_guarantorcode"))) {
						return raiseError("Unable to retrieve customer record for [".$objTable->getudfvalue("u_guarantorcode")."].");
					}
				} else {
					if (!$obju_HisHealthIns->getbykey($objTable->getudfvalue("u_guarantorcode"))) {
						return raiseError("Unable to retrieve health benefit record for [".$objTable->getudfvalue("u_guarantorcode")."].");
					}
				}
				
				if ($objTable->getudfvalue("u_feetype")!="Hospital Fees" && $objTable->getudfvalue("u_feetype")!="Credits/Partial Payments" && $objTable->getudfvalue("u_feetype")!="After Bill Charges") {
					if (!$obju_HISDoctors->getbykey($objTable->getudfvalue("u_doctorid"))) {
						return raiseError("Unable to retrieve doctor record for [".$objTable->getudfvalue("u_doctorid")."].");
					}
				}
				$settings = getBusinessObjectSettings("JOURNALVOUCHER");
				
				$objJvHdr->prepareadd();
				$objJvHdr->objectcode = "JOURNALVOUCHER";
				$objJvHdr->sbo_post_flag = $settings["autopost"];
				$objJvHdr->jeposting = $settings["jeposting"];
				$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
				$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
				$objJvHdr->docduedate = $objJvHdr->docdate;
				$objJvHdr->taxdate = $objJvHdr->docdate;
				$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
				$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
				$objJvHdr->currency = $_SESSION["currency"];
				$objJvHdr->docstatus = "C";
				$objJvHdr->reference1 = $objTable->docno;
				$objJvHdr->reference2 = $objTable->getudfvalue("u_type");
				
				if (!$delete) $objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
				else $objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
				
				$objJvHdr->otherdocno = $objTable->getudfvalue("u_billno");
				$objJvHdr->otherdocdate = $objTable->getudfvalue("u_startdate");
				$objJvHdr->otherdocduedate = $objTable->getudfvalue("u_enddate");
				$objJvHdr->otherbprefno = $objTable->getudfvalue("u_refno");
				$objJvHdr->otherbpcode = $objTable->getudfvalue("u_patientid");
				$objJvHdr->otherbpname = $objTable->getudfvalue("u_patientname");
				
				$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_enddate") . ";" . $objTable->getudfvalue("u_remarks");
				
				$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
				
				if ($actionReturn) {
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
					if ($objTable->getudfvalue("u_hmo")!="2" && $objTable->getudfvalue("u_hmo")!="7") {
						$objJvDtl->itemtype = "C";
						$objJvDtl->itemno = $objCustomers->custno;
						$objJvDtl->itemname = $objCustomers->custname;
						if (!$delete) {
							$objJvDtl->lineno = 1;
							$objJvDtl->reftype = "";
							$objJvDtl->refno = "";
						} else {
							$objJvDtl->reftype = "JOURNALVOUCHER";
							$objJvDtl->refno = $objTable->getudfvalue("u_jvdocno")."/1";
						}
						if ($obju_HISBills->getudfvalue("u_billasone")==0) {
							$objJvDtl->othertrxtype = $objTable->getudfvalue("u_feetype");
							if ($objTable->getudfvalue("u_feetype")=="After Bill Charges") $objJvDtl->othertrxtype = "Hospital Fees";
							if ($objTable->getudfvalue("u_feetype")!="Hospital Fees" && $objTable->getudfvalue("u_feetype")!="Credits/Partial Payments" && $objTable->getudfvalue("u_feetype")!="After Bill Charges") {
								$objJvDtl->otherbpcode = $obju_HISDoctors->code;
								$objJvDtl->otherbpname = $obju_HISDoctors->name;
							}	
						}	
					} else {
						$objJvDtl->itemtype = "A";
						$objJvDtl->itemno = $obju_HisHealthIns->getudfvalue("u_glacctno");
						$objJvDtl->itemname = $obju_HisHealthIns->getudfvalue("u_glacctname");
						$objJvDtl->reftype = "";
						$objJvDtl->refno = "";
					}	
					$objJvDtl->otherbpcode = $objTable->getudfvalue("u_doctorid");
					if ($objJvDtl->otherbpcode!="") {
						$objRs->queryopen("select B.NAME AS U_DOCTORNAME from u_hisdoctors B where B.CODE='".$objJvDtl->otherbpcode."'");
						if ($objRs->queryfetchrow("NAME")) $objJvDtl->otherbpname = $objRs->fields["U_DOCTORNAME"];
					}
					if ($objTable->getudfvalue("u_type")=="Debit Memo") {
						if (!$delete) {
							$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
							$objJvDtl->grossamount = $objJvDtl->credit;
						} else {
							$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
							$objJvDtl->grossamount = $objJvDtl->debit;
						}	
					} else {
						if (!$delete) {
							$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
							$objJvDtl->grossamount = $objJvDtl->debit;
						} else {
							$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
							$objJvDtl->grossamount = $objJvDtl->credit;
						}	
					}	
					if ($obju_HISBills->getudfvalue("u_billasone")==1) {
						$objJvDtl->remarks = "Based on Journal Voucher " . $objTable->getudfvalue("u_billno") . ". ".$objTable->getudfvalue("u_patientname");
					} else {
						$objJvDtl->remarks = "Based on Journal Voucher " . $objTable->getudfvalue("u_billno") . $obju_HISBillFees->getudfvalue("u_suffix").". ".$objTable->getudfvalue("u_patientname"). ". ".$objTable->getudfvalue("u_feetype").". ".$objJvDtl->otherbpname;
					}	
					
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					//var_dump(array("debit",$objJvDtl->debit));
					$actionReturn = $objJvDtl->add();
				}
	
				if ($actionReturn) {
					if ($objTable->getudfvalue("u_feetype")=="After Bill Charges") {
						foreach ($afterbillcharges as $key => $amount) {
							$objJvDtl->prepareadd();
							$objJvDtl->docid = $objJvHdr->docid;
							$objJvDtl->objectcode = $objJvHdr->objectcode;
							$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
							$objJvDtl->refbranch = $_SESSION["branch"];
							$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
							$objJvDtl->itemtype = "C";
							$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
							$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
							$objJvDtl->reftype = "ARINVOICE";
							$objJvDtl->refno = $key;
							if ($objTable->getudfvalue("u_type")=="Debit Memo") {
								if (!$delete) {
									$objJvDtl->debit = $amount;
									$objJvDtl->grossamount = $objJvDtl->debit;
								} else {
									$objJvDtl->credit = $amount;
									$objJvDtl->grossamount = $objJvDtl->credit;
								}	
							} else {
								if (!$delete) {
									$objJvDtl->credit = $amount;
									$objJvDtl->grossamount = $objJvDtl->credit;
								} else {
									$objJvDtl->debit = $amount;
									$objJvDtl->grossamount = $objJvDtl->debit;
								}	
							}	
							$objJvHdr->totaldebit += $objJvDtl->debit ;
							$objJvHdr->totalcredit += $objJvDtl->credit ;
							$objJvDtl->privatedata["header"] = $objJvHdr;
							$actionReturn = $objJvDtl->add();
							if (!$actionReturn) break;
						}	
					} else {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
						if ($objTable->getudfvalue("u_exclaim")=="1") {
							$gldata= getBranchGLAcctNo($thisbranch,"U_EXCLAIMACCT");
							if ($gldata["formatcode"]=="") {
								return raiseError("G/L Acct [Excess Claims Account] is not maintained.");
							}								
							$objJvDtl->itemtype = "A";
							$objJvDtl->itemno = $gldata["formatcode"];
							$objJvDtl->itemname = $gldata["acctname"];
							$objJvDtl->reftype = "";
							$objJvDtl->refno = "";
						} else {				
							$objJvDtl->itemtype = "C";
							if ($objTable->getudfvalue("u_btrefno")=="") {
								$objJvDtl->itemno = $objTable->getudfvalue("u_patientid");
								$objJvDtl->itemname = $objTable->getudfvalue("u_patientname");
								$objJvDtl->reftype = "JOURNALVOUCHER";
								if ($obju_HISBills->getudfvalue("u_billasone")==0) {
									$objJvDtl->refno = $objTable->getudfvalue("u_billno") . $obju_HISBillFees->getudfvalue("u_suffix");
								} else $objJvDtl->refno = $objTable->getudfvalue("u_billno");
							} else {
								if ($obju_HISPronotes->getbykey($objTable->getudfvalue("u_btrefno"))) {
									if (!$obju_HisHealthIns->getbykey($obju_HISPronotes->getudfvalue("u_guarantorcode"))) {
										return raiseError("Unable to retrieve health benefit record for [".$obju_HISPronotes->getudfvalue("u_guarantorcode")."].");
									}
									$objJvDtl->itemno = $obju_HisHealthIns->code;
									$objJvDtl->itemname = $obju_HisHealthIns->name;
									$objJvDtl->reftype = "JOURNALVOUCHER";
									$objJvDtl->refno = $obju_HISPronotes->getudfvalue("u_jvdocno")."/1";
									if (!$delete) {
										$obju_HISPronotes->setudfvalue("u_btamount",$obju_HISPronotes->getudfvalue("u_btamount")+$objTable->getudfvalue("u_pnamount"));
									} else {
										$obju_HISPronotes->setudfvalue("u_btamount",$obju_HISPronotes->getudfvalue("u_btamount")-$objTable->getudfvalue("u_pnamount"));
									}	
									$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
									if (!$actionReturn) return false;
								} else return raiseError("Unable to find document [".$objTable->getudfvalue("u_btrefno")."] to do balance transfer.");
							}	
						}	
						if ($objTable->getudfvalue("u_type")=="Debit Memo") {
							if (!$delete) {
								$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
								$objJvDtl->grossamount = $objJvDtl->debit;
							} else {
								$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
								$objJvDtl->grossamount = $objJvDtl->credit;
							}	
						} else {
							if (!$delete) {
								$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
								$objJvDtl->grossamount = $objJvDtl->credit;
							} else {
								$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
								$objJvDtl->grossamount = $objJvDtl->debit;
							}	
						}	
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();
					}	
				}
	
				if ($actionReturn) $actionReturn = $objJvHdr->add();				
			}
			
		} else return raiseError("Unable to retrieve Bill No.[".$objTable->getudfvalue("u_billno")."]");
	} elseif ($actionReturn && $objTable->getudfvalue("u_pnamount")!=0) {
		
		$objCustomers = new customers(null,$objConnection);
		$obju_HisHealthIns = new masterdataschema(null,$objConnection,"u_hishealthins");
		$objJvHdr = new journalvouchers(null,$objConnection);
		$objJvDtl = new journalvoucheritems(null,$objConnection);

		if ($objTable->getudfvalue("u_hmo")=="6") {
			if (!$objCustomers->getbykey($objTable->getudfvalue("u_memberid"))) {
				return raiseError("Unable to retrieve customer record for [".$objTable->getudfvalue("u_memberid")."-".$objTable->getudfvalue("u_membername")."].");
			}
		} elseif ($objTable->getudfvalue("u_hmo")!="2") {
			if (!$objCustomers->getbykey($objTable->getudfvalue("u_guarantorcode"))) {
				return raiseError("Unable to retrieve customer record for [".$objTable->getudfvalue("u_guarantorcode")."].");
			}
		} else {
			if (!$obju_HisHealthIns->getbykey($objTable->getudfvalue("u_guarantorcode"))) {
				return raiseError("Unable to retrieve health benefit record for [".$objTable->getudfvalue("u_guarantorcode")."].");
			}
		}
		
		if ($objTable->getudfvalue("u_feetype")!="Hospital Fees" && $objTable->getudfvalue("u_feetype")!="Credits/Partial Payments") {
			if (!$obju_HISDoctors->getbykey($objTable->getudfvalue("u_doctorid"))) {
				return raiseError("Unable to retrieve doctor record for [".$objTable->getudfvalue("u_doctorid")."].");
			}
		}
		$settings = getBusinessObjectSettings("JOURNALVOUCHER");
		
		$objJvHdr->prepareadd();
		$objJvHdr->objectcode = "JOURNALVOUCHER";
		$objJvHdr->sbo_post_flag = $settings["autopost"];
		$objJvHdr->jeposting = $settings["jeposting"];
		$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
		$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
		$objJvHdr->docduedate = $objJvHdr->docdate;
		$objJvHdr->taxdate = $objJvHdr->docdate;
		$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
		$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
		$objJvHdr->currency = $_SESSION["currency"];
		$objJvHdr->docstatus = "C";
		$objJvHdr->reference1 = $objTable->docno;
		$objJvHdr->reference2 = $objTable->getudfvalue("u_type");
		
		if (!$delete) $objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
		else $objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
		
		$objJvHdr->otherdocno = $objTable->getudfvalue("u_billno");
		$objJvHdr->otherdocdate = $objTable->getudfvalue("u_startdate");
		$objJvHdr->otherdocduedate = $objTable->getudfvalue("u_enddate");
		$objJvHdr->otherbprefno = $objTable->getudfvalue("u_refno");
		$objJvHdr->otherbpcode = $objTable->getudfvalue("u_patientid");
		$objJvHdr->otherbpname = $objTable->getudfvalue("u_patientname");
		
		$objJvHdr->remarks = $objTable->getudfvalue("u_patientname") . ";" . $objTable->getudfvalue("u_enddate") . ";" . $objTable->getudfvalue("u_remarks");
		
		$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
		
		if ($actionReturn) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
			if ($objTable->getudfvalue("u_hmo")!="2") {
				$objJvDtl->itemtype = "C";
				$objJvDtl->itemno = $objCustomers->custno;
				$objJvDtl->itemname = $objCustomers->custname;
				if (!$delete) {
					$objJvDtl->lineno = 1;
					$objJvDtl->reftype = "";
					$objJvDtl->refno = "";
				} else {
					$objJvDtl->reftype = "JOURNALVOUCHER";
					$objJvDtl->refno = $objTable->getudfvalue("u_jvdocno")."/1";
				}
				$objJvDtl->othertrxtype = $objTable->getudfvalue("u_feetype");
				if ($objTable->getudfvalue("u_feetype")!="Hospital Fees" && $objTable->getudfvalue("u_feetype")!="Credits/Partial Payments") {

					$objJvDtl->otherbpcode = $obju_HISDoctors->code;
					$objJvDtl->otherbpname = $obju_HISDoctors->name;
				}	
			} else {
				$objJvDtl->itemtype = "A";
				$objJvDtl->itemno = $obju_HisHealthIns->getudfvalue("u_glacctno");
				$objJvDtl->itemname = $obju_HisHealthIns->getudfvalue("u_glacctname");
				$objJvDtl->reftype = "";
				$objJvDtl->refno = "";
			}	
			$objJvDtl->otherbpcode = $objTable->getudfvalue("u_doctorid");
			if ($objJvDtl->otherbpcode!="") {
				$objRs->queryopen("select B.NAME AS U_DOCTORNAME from u_hisdoctors B where B.CODE='".$objJvDtl->otherbpcode."'");
				if ($objRs->queryfetchrow("NAME")) $objJvDtl->otherbpname = $objRs->fields["U_DOCTORNAME"];
			}
			if ($objTable->getudfvalue("u_type")=="Debit Memo") {
				if (!$delete) {
					$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->debit;
				}	
			} else {
				if (!$delete) {
					$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->credit;
				}	
			}	
			$objJvDtl->remarks = "Opening Balance. ".$objTable->getudfvalue("u_patientname"). ". ".$objTable->getudfvalue("u_feetype").". ".$objJvDtl->otherbpname;
			
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			//var_dump(array("debit",$objJvDtl->debit));
			$actionReturn = $objJvDtl->add();
		}

		if ($actionReturn) {
			
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
			$gldata= getBranchGLAcctNo($thisbranch,"U_AROPENINGACCT");
			if ($gldata["formatcode"]=="") {
				return raiseError("G/L Acct [Account Receivable - Opening] is not maintained.");
			}								
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = $gldata["formatcode"];
			$objJvDtl->itemname = $gldata["acctname"];
			$objJvDtl->reftype = "";
			$objJvDtl->refno = "";
			if ($objTable->getudfvalue("u_type")=="Debit Memo") {
				if (!$delete) {
					$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->credit;
				}	
			} else {
				if (!$delete) {
					$objJvDtl->credit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $objTable->getudfvalue("u_pnamount");
					$objJvDtl->grossamount = $objJvDtl->debit;
				}	
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
		}

		if ($actionReturn) $actionReturn = $objJvHdr->add();				
	}				
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brUpdateBillBenefitGPSHIS");
	return $actionReturn;
}

function onCustomEventdocumentschema_brStockPRGPSHIS($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	$objRs->setdebug(true);
	if ($objTable->getudfvalue("u_suppno")=="") {
		$objRs->queryopen("select u_preferredsuppno, u_preferredsuppname from u_hismedsuppritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' group by u_preferredsuppno");
		$prsufix = 1;
		while ($objRs->queryfetchrow("NAME")) {
			//var_dump($objRs->fields["u_preferredsuppname"]);
			$data = array();
			$data["header"]["RECORDACTION"] = "A";
			$data["header"]["RECORDTYPE"] = "PURCHASEREQUEST";
			$data["header"]["SUPPNO"] = $objRs->fields["u_preferredsuppno"];
			$data["header"]["DOCTYPE"] = "I";
			$data["header"]["TRANSDATE"] = $objTable->getudfvalue("u_docdate");
			$data["header"]["DUEDATE"] = $objTable->getudfvalue("u_reqdate");
			$data["header"]["BUSINESSDATE"] = $objTable->getudfvalue("u_docdate");
			$data["header"]["BRANCH"] = $_SESSION["branch"];
			$data["header"]["DOCNO"] = $objTable->docno . "/" . str_pad( $prsufix, 3, "0", STR_PAD_LEFT);
			$data["header"]["BPREFNO"] = $objTable->docno;
			//var_dump($data["header"]["DOCNO"]);
			$data["header"]["OWNER"] = "";
			$data["header"]["ISBARCODE"] = 0;
			$data["header"]["REMARKS"] = "";		
			$data["header"]["VATINCLUSIVE"] = 1;
								
			$objRs2->queryopen("select u_itemcode, u_itemdesc, u_quantity, u_uom, u_quantitypu, u_uompu, u_numperuom, u_unitprice, u_isinvuom from u_hismedsuppritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' and u_preferredsuppno='".$objRs->fields["u_preferredsuppno"]."' order by u_itemdesc");
			while ($objRs2->queryfetchrow("NAME")) {
				$data["lines"][$lineid]["ITEMCODE"] = $objRs2->fields["u_itemcode"];
				$data["lines"][$lineid]["ITEMDESC"] = $objRs2->fields["u_itemdesc"];
				if ($objRs2->fields["u_isinvuom"]=="1") {
					$data["lines"][$lineid]["UOM"] = $objRs2->fields["u_uompu"];
					$data["lines"][$lineid]["QUANTITY"] = $objRs2->fields["u_quantity"];
					$data["lines"][$lineid]["QTYPERUOM"] = 1;
					$data["lines"][$lineid]["PRICE"] = $objRs2->fields["u_unitprice"];
				} else {
					$data["lines"][$lineid]["UOM"] = $objRs2->fields["u_uompu"];
					$data["lines"][$lineid]["QUANTITY"] = $objRs2->fields["u_quantitypu"];
					$data["lines"][$lineid]["QTYPERUOM"] = $objRs2->fields["u_numperuom"];
					$data["lines"][$lineid]["PRICE"] = $objRs2->fields["u_unitprice"];
				}	
				$data["lines"][$lineid]["DISCOUNT"] = 0;
				$data["lines"][$lineid]["STDCOSTPRICE"] = 0;
				$data["lines"][$lineid]["WAREHOUSE"] = $objTable->getudfvalue("u_department");
				$lineid++;
			}
			//$page->console->insertVar($data);
			$actionReturn = processJimacPURCHASES(null,$data,false);
			if (!$actionReturn) break;
			unset($data);
			$prsufix ++;			
		}
	} else {
		$data = array();
		$data["header"]["RECORDACTION"] = "A";
		$data["header"]["RECORDTYPE"] = "PURCHASEREQUEST";
		$data["header"]["SUPPNO"] = $objTable->getudfvalue("u_suppno");
		$data["header"]["DOCTYPE"] = "I";
		$data["header"]["TRANSDATE"] = $objTable->getudfvalue("u_docdate");
		$data["header"]["DUEDATE"] = $objTable->getudfvalue("u_reqdate");
		$data["header"]["BUSINESSDATE"] = $objTable->getudfvalue("u_docdate");
		$data["header"]["BRANCH"] = $_SESSION["branch"];
		$data["header"]["DOCNO"] = $objTable->docno;
		$data["header"]["BPREFNO"] = $objTable->docno;
		//var_dump($data["header"]["DOCNO"]);
		$data["header"]["OWNER"] = "";
		$data["header"]["ISBARCODE"] = 0;
		$data["header"]["REMARKS"] = "";		
		$data["header"]["VATINCLUSIVE"] = 1;
							
		$objRs2->queryopen("select u_itemcode, u_itemdesc, u_uom, u_quantitypu, u_uompu, u_numperuom, u_unitprice, u_isinvuom, u_quantity, u_unitprice from u_hismedsuppritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objTable->docid."' order by u_itemdesc");
		while ($objRs2->queryfetchrow("NAME")) {
			$data["lines"][$lineid]["ITEMCODE"] = $objRs2->fields["u_itemcode"];
			$data["lines"][$lineid]["ITEMDESC"] = $objRs2->fields["u_itemdesc"];
			
			if ($objRs2->fields["u_isinvuom"]=="1") {
				$data["lines"][$lineid]["UOM"] = $objRs2->fields["u_uompu"];
				$data["lines"][$lineid]["QUANTITY"] = $objRs2->fields["u_quantity"];
				$data["lines"][$lineid]["QTYPERUOM"] = 1;
				$data["lines"][$lineid]["PRICE"] = $objRs2->fields["u_unitprice"];
			} else {
				$data["lines"][$lineid]["UOM"] = $objRs2->fields["u_uompu"];
				$data["lines"][$lineid]["QUANTITY"] = $objRs2->fields["u_quantitypu"];
				$data["lines"][$lineid]["QTYPERUOM"] = $objRs2->fields["u_numperuom"];
				$data["lines"][$lineid]["PRICE"] = $objRs2->fields["u_unitprice"];
			}	
			
			$data["lines"][$lineid]["DISCOUNT"] = 0;
			$data["lines"][$lineid]["STDCOSTPRICE"] = 0;
			$data["lines"][$lineid]["WAREHOUSE"] = $objTable->getudfvalue("u_department");
			$lineid++;
		}
		//$page->console->insertVar($data);
		$actionReturn = processJimacPURCHASES(null,$data,false);
		unset($data);	
	}	
	//if ($actionReturn) $actionReturn = raiseError("rey");
	return $actionReturn;
}

function onCustomEventdocumentschema_brAdmissionFeesGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$obju_HISMiscs = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISMiscItems = new documentlinesschema_br(null,$objConnection,"u_hischargeitems");

	$objRs = new recordset("transactions",$objConnection);
	
	$objRs->queryopen("select a.u_department, a.u_remarks, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, ifnull(c.price,0) as u_unitprice, i.taxcodesa, i.itemgroup from u_hismisctemplates a inner join u_hismisctemplateitems b on b.code=a.code inner join items i on i.itemcode=b.u_itemcode left join itempricelists c on c.itemcode=b.u_itemcode and c.pricelist='".$objTable->getudfvalue("u_pricelist")."' where a.code='Admission Fees'");
	if ($objRs->recordcount()>0) {
		$obju_HISMiscs->prepareadd();
		$obju_HISMiscs->setudfvalue("u_prepaid",0);
		$obju_HISMiscs->setudfvalue("u_reftype","IP");
		$obju_HISMiscs->setudfvalue("u_trxtype","IP");
		$obju_HISMiscs->setudfvalue("u_refno",$objTable->docno);
		$obju_HISMiscs->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
		$obju_HISMiscs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
		$obju_HISMiscs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
		$obju_HISMiscs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
		$obju_HISMiscs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
		$obju_HISMiscs->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
		$obju_HISMiscs->setudfvalue("u_startdate",$objTable->getudfvalue("u_startdate"));
		$obju_HISMiscs->setudfvalue("u_starttime",$objTable->getudfvalue("u_starttime"));
		$obju_HISMiscs->setudfvalue("u_remarks","Admission Fees");
		$obju_HISMiscs->setudfvalue("u_batchcode","Admission Fees");
		$obju_HISMiscs->docid = getNextIdByBranch('u_hischarges',$objConnection);
		$obju_HISMiscs->docseries = getDefaultSeries('u_hischarges',$objConnection);
		$obju_HISMiscs->docno = getNextSeriesNoByBranch('u_hischarges',$obju_HISMiscs->docseries,$obju_HISMiscs->getudfvalue("u_startdate"),$objConnection);
		$obju_HISMiscs->docstatus='O';
		$u_amount=0;
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["u_department"]!="") $obju_HISMiscs->setudfvalue("u_department",$objRs->fields["u_department"]);
			if ($objRs->fields["u_remarks"]!="") $obju_HISMiscs->setudfvalue("u_remarks",$objRs->fields["u_remarks"]);
			$obju_HISMiscItems->prepareadd();
			$obju_HISMiscItems->docid = $obju_HISMiscs->docid;
			$obju_HISMiscItems->lineid = getNextIdByBranch('u_hischargeitems',$objConnection);		
			$obju_HISMiscItems->setudfvalue("u_itemgroup", $objRs->fields["itemgroup"]);
			$obju_HISMiscItems->setudfvalue("u_itemcode", $objRs->fields["u_itemcode"]);
			$obju_HISMiscItems->setudfvalue("u_itemdesc", $objRs->fields["u_itemdesc"]);
			$obju_HISMiscItems->setudfvalue("u_uom", $objRs->fields["u_uom"]);
			$obju_HISMiscItems->setudfvalue("u_quantity", $objRs->fields["u_quantity"]);
			$obju_HISMiscItems->setudfvalue("u_unitprice", $objRs->fields["u_unitprice"]);
			$obju_HISMiscItems->setudfvalue("u_price", $objRs->fields["u_unitprice"]);
			$obju_HISMiscItems->setudfvalue("u_linetotal", $objRs->fields["u_quantity"] * $objRs->fields["u_unitprice"]);
			$obju_HISMiscItems->setudfvalue("u_vatcode", $objRs->fields["taxcodesa"]);
			$obju_HISMiscItems->privatedata["header"] =  $obju_HISMiscs;
			$actionReturn = $obju_HISMiscItems->add();
			if (!$actionReturn) break;
			$u_amount+=$objRs->fields["u_quantity"] * $objRs->fields["u_unitprice"];
		}
		$obju_HISMiscs->setudfvalue("u_amountbefdisc",$u_amount);
		$obju_HISMiscs->setudfvalue("u_amount",$u_amount);
		if ($actionReturn) $actionReturn = $obju_HISMiscs->add();
	}
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brAdmissionFeesGPSHIS");
	return $actionReturn;
}

function onCustomEventdocumentschema_brRoomTransferFeesGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$obju_HISMiscs = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISMiscItems = new documentlinesschema_br(null,$objConnection,"u_hischargeitems");

	$objRs = new recordset("transactions",$objConnection);
	
	$objRs->queryopen("select a.u_department, a.u_remarks, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, ifnull(c.price,0) as u_unitprice, i.taxcodesa, i.itemgroup from u_hismisctemplates a inner join u_hismisctemplateitems b on b.code=a.code inner join items i on i.itemcode=b.u_itemcode left join itempricelists c on c.itemcode=b.u_itemcode and c.pricelist='".$objTable->getudfvalue("u_pricelist")."' where a.code='Room Transfer Fees'");
	if ($objRs->recordcount()>0) {
		$obju_HISMiscs->prepareadd();
		$obju_HISMiscs->setudfvalue("u_prepaid",0);
		$obju_HISMiscs->setudfvalue("u_reftype","IP");
		$obju_HISMiscs->setudfvalue("u_trxtype","IP");
		$obju_HISMiscs->setudfvalue("u_refno",$objTable->docno);
		$obju_HISMiscs->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
		$obju_HISMiscs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
		$obju_HISMiscs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
		$obju_HISMiscs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
		$obju_HISMiscs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
		$obju_HISMiscs->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
		$obju_HISMiscs->setudfvalue("u_startdate",$objTable->getudfvalue("u_roomdate"));
		$obju_HISMiscs->setudfvalue("u_starttime",$objTable->getudfvalue("u_roomtime"));
		$obju_HISMiscs->setudfvalue("u_remarks","Room Transfer Fees");
		$obju_HISMiscs->setudfvalue("u_batchcode","Room Transfer Fees");
		$obju_HISMiscs->docid = getNextIdByBranch('u_hischarges',$objConnection);
		$obju_HISMiscs->docseries = getDefaultSeries('u_hischarges',$objConnection);
		$obju_HISMiscs->docno = getNextSeriesNoByBranch('u_hischarges',$obju_HISMiscs->docseries,$obju_HISMiscs->getudfvalue("u_startdate"),$objConnection);
		$obju_HISMiscs->docstatus='O';
		$u_amount=0;
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["u_department"]!="") $obju_HISMiscs->setudfvalue("u_department",$objRs->fields["u_department"]);
			if ($objRs->fields["u_remarks"]!="") $obju_HISMiscs->setudfvalue("u_remarks",$objRs->fields["u_remarks"]);
			$obju_HISMiscItems->prepareadd();
			$obju_HISMiscItems->docid = $obju_HISMiscs->docid;
			$obju_HISMiscItems->lineid = getNextIdByBranch('u_hischargeitems',$objConnection);		
			$obju_HISMiscItems->setudfvalue("u_itemgroup", $objRs->fields["itemgroup"]);
			$obju_HISMiscItems->setudfvalue("u_itemcode", $objRs->fields["u_itemcode"]);
			$obju_HISMiscItems->setudfvalue("u_itemdesc", $objRs->fields["u_itemdesc"]);
			$obju_HISMiscItems->setudfvalue("u_uom", $objRs->fields["u_uom"]);
			$obju_HISMiscItems->setudfvalue("u_quantity", $objRs->fields["u_quantity"]);
			$obju_HISMiscItems->setudfvalue("u_unitprice", $objRs->fields["u_unitprice"]);
			$obju_HISMiscItems->setudfvalue("u_price", $objRs->fields["u_unitprice"]);
			$obju_HISMiscItems->setudfvalue("u_linetotal", $objRs->fields["u_quantity"] * $objRs->fields["u_unitprice"]);
			$obju_HISMiscItems->setudfvalue("u_vatcode", $objRs->fields["taxcodesa"]);
			$obju_HISMiscItems->privatedata["header"] =  $obju_HISMiscs;
			$actionReturn = $obju_HISMiscItems->add();
			if (!$actionReturn) break;
			$u_amount+=$objRs->fields["u_quantity"] * $objRs->fields["u_unitprice"];
		}
		$obju_HISMiscs->setudfvalue("u_amountbefdisc",$u_amount);
		$obju_HISMiscs->setudfvalue("u_amount",$u_amount);
		if ($actionReturn) $actionReturn = $obju_HISMiscs->add();
	}
	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brAdmissionFeesGPSHIS");
	return $actionReturn;
}

function onCustomEventdocumentschema_brDailyRoomAndBoardFeesGPSHIS($objTable,$date,$time="00:00:00",$roomtype,$roomtypedesc,$roomrate=0) {
	global $objConnection;
	$actionReturn = true;
	
	$obju_HISMiscs = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISMiscItems = new documentlinesschema_br(null,$objConnection,"u_hischargeitems");

	$objRs = new recordset("transactions",$objConnection);

	$obju_HISMiscs->prepareadd();
	$obju_HISMiscs->setudfvalue("u_prepaid",0);
	$obju_HISMiscs->setudfvalue("u_reftype","IP");
	$obju_HISMiscs->setudfvalue("u_trxtype","IP");
	$obju_HISMiscs->setudfvalue("u_refno",$objTable->docno);
	$obju_HISMiscs->setudfvalue("u_department",$objTable->getudfvalue("u_department"));
	$obju_HISMiscs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
	$obju_HISMiscs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
	$obju_HISMiscs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
	$obju_HISMiscs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
	$obju_HISMiscs->setudfvalue("u_pricelist",$objTable->getudfvalue("u_pricelist"));
	$obju_HISMiscs->setudfvalue("u_startdate",$date);
	$obju_HISMiscs->setudfvalue("u_starttime",$time);
	$obju_HISMiscs->setudfvalue("u_remarks","Daily Room & Board Fees");
	$obju_HISMiscs->setudfvalue("u_batchcode","Daily Room & Board Fees");
	$obju_HISMiscs->docid = getNextIdByBranch('u_hischarges',$objConnection);
	$obju_HISMiscs->docseries = getDefaultSeries('u_hischarges',$objConnection);
	$obju_HISMiscs->docno = getNextSeriesNoByBranch('u_hischarges',$obju_HISMiscs->docseries,$obju_HISMiscs->getudfvalue("u_startdate"),$objConnection);
	$obju_HISMiscs->docstatus='O';

	$obju_HISMiscItems->prepareadd();
	$obju_HISMiscItems->docid = $obju_HISMiscs->docid;
	$obju_HISMiscItems->lineid = getNextIdByBranch('u_hischargeitems',$objConnection);		
	$obju_HISMiscItems->setudfvalue("u_itemgroup", "ROOM");
	$obju_HISMiscItems->setudfvalue("u_itemcode", $roomtype);
	$obju_HISMiscItems->setudfvalue("u_itemdesc", $roomtypedesc);
	$obju_HISMiscItems->setudfvalue("u_uom", "");
	$obju_HISMiscItems->setudfvalue("u_quantity", 1);
	$obju_HISMiscItems->setudfvalue("u_unitprice", $roomrate);
	$obju_HISMiscItems->setudfvalue("u_price", $roomrate);
	$obju_HISMiscItems->setudfvalue("u_linetotal", $roomrate);
	$obju_HISMiscItems->setudfvalue("u_vatcode", "VATOX");
	$obju_HISMiscItems->privatedata["header"] =  $obju_HISMiscs;
	$actionReturn = $obju_HISMiscItems->add();	
	$u_amount=$roomrate;
	if ($actionReturn) {
		$objRs->queryopen("select a.u_department, a.u_remarks, b.u_itemcode, b.u_itemdesc, b.u_quantity, b.u_uom, ifnull(c.price,0) as u_unitprice, i.taxcodesa, i.itemgroup from u_hismisctemplates a inner join u_hismisctemplateitems b on b.code=a.code inner join items i on i.itemcode=b.u_itemcode left join itempricelists c on c.itemcode=b.u_itemcode and c.pricelist='".$objTable->getudfvalue("u_pricelist")."' where a.code='Daily Room & Board Fees'");
		if ($objRs->recordcount()>0) {
			while ($objRs->queryfetchrow("NAME")) {
				if ($objRs->fields["u_department"]!="") $obju_HISMiscs->setudfvalue("u_department",$objRs->fields["u_department"]);
				if ($objRs->fields["u_remarks"]!="") $obju_HISMiscs->setudfvalue("u_remarks",$objRs->fields["u_remarks"]);
				$obju_HISMiscItems->prepareadd();
				$obju_HISMiscItems->docid = $obju_HISMiscs->docid;
				$obju_HISMiscItems->lineid = getNextIdByBranch('u_hischargeitems',$objConnection);		
				$obju_HISMiscItems->setudfvalue("u_itemgroup", $objRs->fields["itemgroup"]);
				$obju_HISMiscItems->setudfvalue("u_itemcode", $objRs->fields["u_itemcode"]);
				$obju_HISMiscItems->setudfvalue("u_itemdesc", $objRs->fields["u_itemdesc"]);
				$obju_HISMiscItems->setudfvalue("u_uom", $objRs->fields["u_uom"]);
				$obju_HISMiscItems->setudfvalue("u_quantity", $objRs->fields["u_quantity"]);
				$obju_HISMiscItems->setudfvalue("u_unitprice", $objRs->fields["u_unitprice"]);
				$obju_HISMiscItems->setudfvalue("u_price", $objRs->fields["u_unitprice"]);
				$obju_HISMiscItems->setudfvalue("u_linetotal", $objRs->fields["u_quantity"] * $objRs->fields["u_unitprice"]);
				$obju_HISMiscItems->setudfvalue("u_vatcode", $objRs->fields["taxcodesa"]);
				$obju_HISMiscItems->privatedata["header"] =  $obju_HISMiscs;
				$actionReturn = $obju_HISMiscItems->add();
				if (!$actionReturn) break;
				$u_amount+=$objRs->fields["u_quantity"] * $objRs->fields["u_unitprice"];
			}
			$obju_HISMiscs->setudfvalue("u_amountbefdisc",$u_amount);
			$obju_HISMiscs->setudfvalue("u_amount",$u_amount);
			if ($actionReturn) $actionReturn = $obju_HISMiscs->add();
		}
	}	
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brAdmissionFeesGPSHIS");
	return $actionReturn;
}

function onCustomEventdocumentschema_brAdmitPatientGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
	$obju_HISPatientRegs = new masterdatalinesschema_br(null,$objConnection,"u_hispatientregs");
	
	$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
	$obju_HISIPTrxs = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");	
	$obju_HISIPIns = new documentlinesschema_br(null,$objConnection,"u_hisipins");	
	$obju_HISIPRooms = new documentlinesschema_br(null,$objConnection,"u_hisiprooms");
	$obju_HISIPDoctors = new documentlinesschema_br(null,$objConnection,"u_hisipdoctors");
	$obju_HISIPVs = new documentlinesschema_br(null,$objConnection,"u_hisipvs");

	$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
	$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");
	
	$objCustomers = new customers(null,$objConnection);
	$objRs = new recordset("transactions",$objConnection);
	
	$obju_HISIPs->prepareadd();
	$obju_HISIPs->docid = getNextIdByBranch("u_hisips",$objConnection);
	$obju_HISIPs->docseries = getDefaultSeries("u_hisips",$objConnection);
	$obju_HISIPs->docstatus = "Active";
	$obju_HISIPs->setudfvalue("u_startdate",$objTable->getudfvalue("u_tfstartdate"));
	$obju_HISIPs->setudfvalue("u_starttime",$objTable->getudfvalue("u_tfstarttime"));
	$obju_HISIPs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
	$obju_HISIPs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
	$obju_HISIPs->setudfvalue("u_complaint",$objTable->getudfvalue("u_complaint"));
	$obju_HISIPs->setudfvalue("u_impression",$objTable->getudfvalue("u_impression"));
	$obju_HISIPs->setudfvalue("u_remarks",$objTable->getudfvalue("u_remarks"));
	$obju_HISIPs->setudfvalue("u_remarks2",$objTable->getudfvalue("u_remarks2"));
	$obju_HISIPs->setudfvalue("u_medication",$objTable->getudfvalue("u_medication"));
	$obju_HISIPs->setudfvalue("u_doctorid",$objTable->getudfvalue("u_doctorid"));
	$obju_HISIPs->setudfvalue("u_doctorservice",$objTable->getudfvalue("u_doctorservice"));
	$obju_HISIPs->setudfvalue("u_arrivedby",$objTable->getudfvalue("u_arrivedby"));
	$obju_HISIPs->setudfvalue("u_department",$objTable->getudfvalue("u_tfdepartment"));
	$obju_HISIPs->setudfvalue("u_admittedby",$objTable->getudfvalue("u_assistedby"));
	$obju_HISIPs->setudfvalue("u_paymentterm",$objTable->getudfvalue("u_paymentterm"));
	$obju_HISIPs->setudfvalue("u_billing",$objTable->getudfvalue("u_billing"));
	$obju_HISIPs->setudfvalue("u_prepaid",$objTable->getudfvalue("u_prepaid"));
	$obju_HISIPs->setudfvalue("u_gender",$objTable->getudfvalue("u_gender"));
	$obju_HISIPs->setudfvalue("u_visitno",$objTable->getudfvalue("u_visitno"));
	$obju_HISIPs->setudfvalue("u_oldpatient",$objTable->getudfvalue("u_oldpatient"));
	$obju_HISIPs->setudfvalue("u_disccode",$objTable->getudfvalue("u_disccode"));
	$obju_HISIPs->setudfvalue("u_scdisc",$objTable->getudfvalue("u_scdisc"));
	$obju_HISIPs->setudfvalue("u_confidential",$objTable->getudfvalue("u_confidential"));
	$obju_HISIPs->setudfvalue("u_allergies",$objTable->getudfvalue("u_allergies"));
	$obju_HISIPs->setudfvalue("u_informantname",$objTable->getudfvalue("u_informantname"));
	$obju_HISIPs->setudfvalue("u_informantrelationship",$objTable->getudfvalue("u_informantrelationship"));
	$obju_HISIPs->setudfvalue("u_informantaddress",$objTable->getudfvalue("u_informantaddress"));
	$obju_HISIPs->setudfvalue("u_informanttelno",$objTable->getudfvalue("u_informanttelno"));

	$obju_HISIPs->setudfvalue("u_vsdate",$objTable->getudfvalue("u_vsdate"));
	$obju_HISIPs->setudfvalue("u_vstime",$objTable->getudfvalue("u_vstime"));
	$obju_HISIPs->setudfvalue("u_bt_c",$objTable->getudfvalue("u_bt_c"));
	$obju_HISIPs->setudfvalue("u_bt_f",$objTable->getudfvalue("u_bt_f"));
	$obju_HISIPs->setudfvalue("u_bp_s",$objTable->getudfvalue("u_bp_s"));
	$obju_HISIPs->setudfvalue("u_bp_d",$objTable->getudfvalue("u_bp_d"));
	$obju_HISIPs->setudfvalue("u_hr",$objTable->getudfvalue("u_hr"));
	$obju_HISIPs->setudfvalue("u_rr",$objTable->getudfvalue("u_rr"));
	$obju_HISIPs->setudfvalue("u_o2sat",$objTable->getudfvalue("u_o2sat"));
	
	$obju_HISIPs->setudfvalue("u_height_ft",$objTable->getudfvalue("u_height_ft"));
	$obju_HISIPs->setudfvalue("u_height_in",$objTable->getudfvalue("u_height_in"));
	$obju_HISIPs->setudfvalue("u_height_cm",$objTable->getudfvalue("u_height_cm"));
	$obju_HISIPs->setudfvalue("u_weight_kg",$objTable->getudfvalue("u_weight_kg"));
	$obju_HISIPs->setudfvalue("u_weight_lb",$objTable->getudfvalue("u_weight_lb"));
	$obju_HISIPs->setudfvalue("u_bmi",$objTable->getudfvalue("u_bmi"));
	$obju_HISIPs->setudfvalue("u_bmistatus",$objTable->getudfvalue("u_bmistatus"));
	$obju_HISIPs->setudfvalue("u_religion",$objTable->getudfvalue("u_religion"));

	$obju_HISIPs->setudfvalue("u_creditlimit",$objTable->getudfvalue("u_creditlimit"));
	$obju_HISIPs->setudfvalue("u_totalcharges",$objTable->getudfvalue("u_totalcharges"));
	$obju_HISIPs->setudfvalue("u_availablecreditlimit",$objTable->getudfvalue("u_availablecreditlimit"));
	$obju_HISIPs->setudfvalue("u_availablecreditperc",$objTable->getudfvalue("u_availablecreditperc"));
	
	//$obju_HISIPs->setudfvalue("u_admittype",$objTable->getudfvalue("u_admittype"));
	$obju_HISIPs->docno = getNextSeriesNoByBranch("u_hisips",$obju_HISIPs->docseries,$obju_HISIPs->getudfvalue("u_startdate"),$objConnection);

	if ($actionReturn && $objTable->getudfvalue("u_doctorid")!="") {
		$obju_HISIPDoctors->prepareadd();
		$obju_HISIPDoctors->docid = $obju_HISIPs->docid;
		$obju_HISIPDoctors->lineid = getNextIdByBranch("u_hisipdoctors",$objConnection);;
		$obju_HISIPDoctors->setudfvalue("u_default",1);
		$obju_HISIPDoctors->setudfvalue("u_doctorid",$objTable->getudfvalue("u_doctorid"));
		$obju_HISIPDoctors->setudfvalue("u_doctorservice",$objTable->getudfvalue("u_doctorservice"));
		$obju_HISIPDoctors->setudfvalue("u_rod",1);
		$obju_HISIPDoctors->privatedata["header"] = $obju_HISIPs;
		$actionReturn = $obju_HISIPDoctors->add();
	}
	
	if ($actionReturn && $objTable->getudfvalue("u_tfroomno")!="") {
		$obju_HISIPs->setudfvalue("u_roomno",$objTable->getudfvalue("u_tfroomno"));
		$obju_HISIPs->setudfvalue("u_roomdesc",$objTable->getudfvalue("u_tfroomdesc"));
		$obju_HISIPs->setudfvalue("u_roomtype",$objTable->getudfvalue("u_tfroomtype"));
		$obju_HISIPs->setudfvalue("u_bedno",$objTable->getudfvalue("u_tfbedno"));
		$obju_HISIPs->setudfvalue("u_roomrate",$objTable->getudfvalue("u_tfrate"));
		$obju_HISIPs->setudfvalue("u_pricelist",$objTable->getudfvalue("u_tfpricelist"));
		$obju_HISIPs->setudfvalue("u_roomdate",$objTable->getudfvalue("u_tfstartdate"));
		$obju_HISIPs->setudfvalue("u_roomtime",$objTable->getudfvalue("u_tfstarttime"));
		$obju_HISIPRooms->prepareadd();
		$obju_HISIPRooms->docid = $obju_HISIPs->docid;
		$obju_HISIPRooms->lineid = getNextIdByBranch("u_hisiprooms",$objConnection);;
		$obju_HISIPRooms->setudfvalue("u_roomno",$objTable->getudfvalue("u_tfroomno"));
		$obju_HISIPRooms->setudfvalue("u_roomdesc",$objTable->getudfvalue("u_tfroomdesc"));
		$obju_HISIPRooms->setudfvalue("u_roomtype",$objTable->getudfvalue("u_tfroomtype"));
		$obju_HISIPRooms->setudfvalue("u_isroomshared",$objTable->getudfvalue("u_tfisroomshared"));
		$obju_HISIPRooms->setudfvalue("u_bedno",$objTable->getudfvalue("u_tfbedno"));
		$obju_HISIPRooms->setudfvalue("u_rate",$objTable->getudfvalue("u_tfrate"));
		$obju_HISIPRooms->setudfvalue("u_department",$objTable->getudfvalue("u_tfdepartment"));
		$obju_HISIPRooms->setudfvalue("u_pricelist",$objTable->getudfvalue("u_tfpricelist"));
		$obju_HISIPRooms->setudfvalue("u_rateuom",$objTable->getudfvalue("u_tfrateuom"));
		$obju_HISIPRooms->setudfvalue("u_startdate",$objTable->getudfvalue("u_tfstartdate"));
		$obju_HISIPRooms->setudfvalue("u_starttime",$objTable->getudfvalue("u_tfstarttime"));
		$obju_HISIPRooms->privatedata["header"] = $obju_HISIPs;
		$actionReturn = $obju_HISIPRooms->add();
	}			
	if ($actionReturn) {
		$objRs->setdebug();
		$objRs->queryopen("select * from u_hisopvs where company='$objTable->company' and branch='$objTable->branch' and docid='$objTable->docid'");
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["U_VSDATE"]==$objTable->getudfvalue("u_vsdate") && substr($objRs->fields["U_VSTIME"],0,5)==substr($objTable->getudfvalue("u_vstime"),0,5)) continue;
			$obju_HISIPVs->prepareadd();
			$obju_HISIPVs->docid = $obju_HISIPs->docid;
			$obju_HISIPVs->lineid = getNextIdByBranch("u_hisipvs",$objConnection);
			$obju_HISIPVs->setudfvalue("u_vsdate",$objRs->fields["U_VSDATE"]);
			$obju_HISIPVs->setudfvalue("u_vstime",$objRs->fields["U_VSTIME"]);
			$obju_HISIPVs->setudfvalue("u_bt_c",$objRs->fields["U_BT_C"]);
			$obju_HISIPVs->setudfvalue("u_bt_f",$objRs->fields["U_BT_F"]);
			$obju_HISIPVs->setudfvalue("u_bp_s",$objRs->fields["U_BP_S"]);
			$obju_HISIPVs->setudfvalue("u_bp_d",$objRs->fields["U_BP_D"]);
			$obju_HISIPVs->setudfvalue("u_hr",$objRs->fields["U_HR"]);
			$obju_HISIPVs->setudfvalue("u_rr",$objRs->fields["U_RR"]);
			$obju_HISIPVs->setudfvalue("u_o2sat",$objRs->fields["U_O2SAT"]);
			$obju_HISIPVs->privatedata["header"] = $obju_HISIPs;
			$actionReturn = $obju_HISIPVs->add();
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$objRs->queryopen("select * from u_hisoptrxs where company='$objTable->company' and branch='$objTable->branch' and docid='$objTable->docid'");
		while ($objRs->queryfetchrow("NAME")) {
			$obju_HISIPTrxs->prepareadd();
			$obju_HISIPTrxs->docid = $obju_HISIPs->docid;
			$obju_HISIPTrxs->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
			$obju_HISIPTrxs->setudfvalue("u_department",$objRs->fields["U_DEPARTMENT"]);
			$obju_HISIPTrxs->setudfvalue("u_docdate",$objRs->fields["U_DOCDATE"]);
			$obju_HISIPTrxs->setudfvalue("u_doctime",$objRs->fields["U_DOCTIME"]);
			$obju_HISIPTrxs->setudfvalue("u_docstatus",$objRs->fields["U_DOCSTATUS"]);
			$obju_HISIPTrxs->setudfvalue("u_reftype",$objRs->fields["U_REFTYPE"]);
			$obju_HISIPTrxs->setudfvalue("u_refno",$objRs->fields["U_REFNO"]);
			$obju_HISIPTrxs->setudfvalue("u_docdesc",$objRs->fields["U_DOCDESC"]);
			$obju_HISIPTrxs->setudfvalue("u_docno",$objRs->fields["U_DOCNO"]);
			$obju_HISIPTrxs->setudfvalue("u_doctype",$objRs->fields["U_DOCTYPE"]);
			$obju_HISIPTrxs->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
			$obju_HISIPTrxs->setudfvalue("u_balance",$objRs->fields["U_BALANCE"]);
			$obju_HISIPTrxs->privatedata["header"] = $obju_HISIPs;
			$actionReturn = $obju_HISIPTrxs->add();
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$objRs->queryopen("select * from u_hisopins where company='$objTable->company' and branch='$objTable->branch' and docid='$objTable->docid'");
		while ($objRs->queryfetchrow("NAME")) {
			$obju_HISIPIns->prepareadd();
			$obju_HISIPIns->docid = $obju_HISIPs->docid;
			$obju_HISIPIns->lineid = getNextIdByBranch("u_hisipins",$objConnection);
			$obju_HISIPIns->setudfvalue("u_inscode",$objRs->fields["U_INSCODE"]);
			$obju_HISIPIns->setudfvalue("u_scdisc",$objRs->fields["U_SCDISC"]);
			$obju_HISIPIns->setudfvalue("u_hmo",$objRs->fields["U_HMO"]);
			$obju_HISIPIns->setudfvalue("u_memberid",$objRs->fields["U_MEMBERID"]);
			$obju_HISIPIns->setudfvalue("u_membername",$objRs->fields["U_MEMBERNAME"]);
			$obju_HISIPIns->setudfvalue("u_membertype",$objRs->fields["U_MEMBERTYPE"]);
			$obju_HISIPIns->setudfvalue("u_creditlimit",$objRs->fields["U_CREDITLIMIT"]);
			$obju_HISIPIns->privatedata["header"] = $obju_HISIPs;
			$actionReturn = $obju_HISIPIns->add();
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) $actionReturn = $obju_HISIPs->add();
	if ($actionReturn) {
		$objRs->queryopen("select * from u_hisrequests where company='$objTable->company' and branch='$objTable->branch' and u_reftype='OP' and u_refno='".$objTable->docno."'");
		while ($objRs->queryfetchrow("NAME")) {
			if ($obju_HISRequests->getbykey($objRs->fields["DOCNO"])) {
				$obju_HISRequests->setudfvalue("u_reftype","IP");
				$obju_HISRequests->setudfvalue("u_refno",$obju_HISIPs->docno);
				$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
			} else return raiseError("Unable to find Request No[".$objRs->fields["DOCNO"]."]");
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn) {
		$objRs->queryopen("select * from u_hisoptrxs where company='$objTable->company' and branch='$objTable->branch' and docid='$objTable->docid'");
		while ($objRs->queryfetchrow("NAME")) {
			switch ($objRs->fields["U_DOCTYPE"]) {
				case "CHRG":
					if ($obju_HISCharges->getbykey($objRs->fields["U_DOCNO"])) {
						$obju_HISCharges->setudfvalue("u_reftype","IP");
						$obju_HISCharges->setudfvalue("u_refno",$obju_HISIPs->docno);
						$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
					} else return raiseError("Unable to find Charge No[".$objRs->fields["U_DOCNO"]."]");
					break;
				case "CM":
					if ($obju_HISCredits->getbykey($objRs->fields["U_DOCNO"])) {
						$obju_HISCredits->setudfvalue("u_reftype","IP");
						$obju_HISCredits->setudfvalue("u_refno",$obju_HISIPs->docno);
						$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
					} else return raiseError("Unable to find Credit No[".$objRs->fields["U_DOCNO"]."]");
					break;
			}
			if (!$actionReturn) break;
		}
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brAdmitPatientGPSHIS()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brMergePatientRecordsGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$obju_HISPatients = new masterdataschema_br(null,$objConnection,"u_hispatients");
	$obju_HISPatientRegs = new masterdatalinesschema_br(null,$objConnection,"u_hispatientregs");
	$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
	$obju_HISOPs = new documentschema_br(null,$objConnection,"u_hisops");
	$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
	$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
	$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");
	$obju_HISLabTests = new documentschema_br(null,$objConnection,"u_hislabtests");
	$obju_HISProNotes = new documentschema_br(null,$objConnection,"u_hispronotes");
	$obju_HISRoomTfs = new documentschema_br(null,$objConnection,"u_hisroomtfs");
	$obju_HISRoomRs = new documentschema_br(null,$objConnection,"u_hisroomrs");
	$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
	$obju_HISInClaims = new documentschema_br(null,$objConnection,"u_hisinclaims");
	$obju_HISPHICClaims = new documentschema_br(null,$objConnection,"u_hisphicclaims");
	$obju_HISInClaimXmtalItems = new documentlinesschema_br(null,$objConnection,"u_hisinclaimxmtalitems");
	
	$objCustomers = new customers(null,$objConnection);
	$objRs = new recordset("transactions",$objConnection);
	$visitcount=0;
	if ($obju_HISPatients->getbykey($objTable->getudfvalue("u_patientid2"))) {
		$visitcount = $obju_HISPatients->getudfvalue("u_visitcount");
		$obju_HISPatients->setudfvalue("u_active",0);
		$obju_HISPatients->setudfvalue("u_visitcount",0);
		$actionReturn = $obju_HISPatients->update($obju_HISPatients->code,$obju_HISPatients->rcdversion);
	} else return raiseError("Unable to Find Patient [".$objTable->getudfvalue("u_patientid2")."] record to merge.");

	if ($obju_HISPatients->getbykey($objTable->getudfvalue("u_patientid"))) {
		$obju_HISPatients->setudfvalue("u_active",1);
		$obju_HISPatients->setudfvalue("u_visitcount",$obju_HISPatients->getudfvalue("u_visitcount")+$visitcount);
		$actionReturn = $obju_HISPatients->update($obju_HISPatients->code,$obju_HISPatients->rcdversion);
	} else return raiseError("Unable to Find Patient [".$objTable->getudfvalue("u_patientid")."] record to merge with.");

	
	if ($actionReturn) {
		$obju_HISPatientRegs->queryopen($obju_HISPatientRegs->selectstring()." and CODE='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISPatientRegs->queryfetchrow()) {
			$obju_HISPatientRegs->code = $objTable->getudfvalue("u_patientid");
			$actionReturn = $obju_HISPatientRegs->update($objTable->getudfvalue("u_patientid2"),$obju_HISPatientRegs->lineid,$obju_HISPatientRegs->rcdversion);
			if (!$actionReturn) break;
		}
	}	
	if ($actionReturn) {
		$obju_HISIPs->queryopen($obju_HISIPs->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISIPs->queryfetchrow()) {
			$obju_HISIPs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISIPs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISOPs->queryopen($obju_HISOPs->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISOPs->queryfetchrow()) {
			$obju_HISOPs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISOPs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISOPs->update($obju_HISOPs->docno,$obju_HISOPs->rcdversion);
			if (!$actionReturn) break;
		}
	}
	
	if ($actionReturn) {
		$obju_HISBills->queryopen($obju_HISBills->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISBills->queryfetchrow()) {
			$obju_HISBills->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISBills->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISInClaims->queryopen($obju_HISInClaims->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISInClaims->queryfetchrow()) {
			$obju_HISInClaims->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISInClaims->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISInClaims->update($obju_HISInClaims->docno,$obju_HISInClaims->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISProNotes->queryopen($obju_HISProNotes->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISProNotes->queryfetchrow()) {
			$obju_HISProNotes->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISProNotes->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISProNotes->update($obju_HISProNotes->docno,$obju_HISProNotes->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISPHICClaims->queryopen($obju_HISPHICClaims->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISPHICClaims->queryfetchrow()) {
			$obju_HISPHICClaims->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISPHICClaims->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISPHICClaims->update($obju_HISPHICClaims->docno,$obju_HISPHICClaims->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISInClaimXmtalItems->queryopen($obju_HISInClaimXmtalItems->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISInClaimXmtalItems->queryfetchrow()) {
			$obju_HISInClaimXmtalItems->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISInClaimXmtalItems->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISInClaimXmtalItems->update($obju_HISInClaimXmtalItems->docid,$obju_HISInClaimXmtalItems->lineid,$obju_HISInClaimXmtalItems->rcdversion);
			if (!$actionReturn) break;
		}
	}
	
	
	
	if ($actionReturn) {
		$obju_HISLabTests->queryopen($obju_HISLabTests->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISLabTests->queryfetchrow()) {
			$obju_HISLabTests->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISLabTests->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISRequests->queryopen($obju_HISRequests->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISRequests->queryfetchrow()) {
			$obju_HISRequests->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISRequests->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISCharges->queryopen($obju_HISCharges->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISCharges->queryfetchrow()) {
			$obju_HISCharges->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISCharges->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISCredits->queryopen($obju_HISCredits->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISCredits->queryfetchrow()) {
			$obju_HISCredits->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISCredits->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISRoomTfs->queryopen($obju_HISRoomTfs->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISRoomTfs->queryfetchrow()) {
			$obju_HISRoomTfs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISRoomTfs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISRoomTfs->update($obju_HISRoomTfs->docno,$obju_HISRoomTfs->rcdversion);
			if (!$actionReturn) break;
		}
	}
	if ($actionReturn) {
		$obju_HISRoomRs->queryopen($obju_HISRoomRs->selectstring()." and U_PATIENTID='".$objTable->getudfvalue("u_patientid2")."'");
		while ($obju_HISRoomRs->queryfetchrow()) {
			$obju_HISRoomRs->setudfvalue("u_patientid",$objTable->getudfvalue("u_patientid"));
			$obju_HISRoomRs->setudfvalue("u_patientname",$objTable->getudfvalue("u_patientname"));
			$actionReturn = $obju_HISRoomRs->update($obju_HISRoomRs->docno,$obju_HISRoomRs->rcdversion);
			if (!$actionReturn) break;
		}
	}

	if ($actionReturn) {
		$balance=0;
		if ($objCustomers->getbykey($objTable->getudfvalue("u_patientid2"))) {
			$objCustomers->isvalid=0;
			$balance = $objCustomers->balance;
			$objCustomers->balance=0;
			$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
		} else return raiseError("Unable to Find Customer [".$objTable->getudfvalue("u_patientid")."] record to merge with.");
	
		if ($objCustomers->getbykey($objTable->getudfvalue("u_patientid"))) {
			$objCustomers->isvalid=1;
			$objCustomers->balance = $objCustomers->balance + $balance;
			$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
		} else return raiseError("Unable to Find Customer [".$objTable->getudfvalue("u_patientid2")."] record to merge.");
		$company = $_SESSION["company"];
		$branch = $_SESSION["branch"];
		$patientid2=$objTable->getudfvalue("u_patientid2");
		$patientid=$objTable->getudfvalue("u_patientid");
		$patientname=$objTable->getudfvalue("u_patientname");
		if ($actionReturn) $actionReturn = $objRs->executesql("update salesdeliveries set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and bpcode='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update salesreturns set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and bpcode='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update arinvoices set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and bpcode='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update arcreditmemos set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and bpcode='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update collections set bpcode='$patientid', bpname='$patientname' where company='$company' and branchcode='$branch' and bpcode='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update payments set bpcode='$patientid', bpname='$patientname' where company='$company' and branchcode='$branch' and bpcode='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update journalvoucheritems set itemno='$patientid', itemname='$patientname' where company='$company' and branch='$branch' and itemtype='C' and itemno='$patientid2'",false);
		if ($actionReturn) $actionReturn = $objRs->executesql("update journalentryitems set slacctno='$patientid', slacctname='$patientname' where company='$company' and branch='$branch' and sltype='C' and slacctno='$patientid2'",false);
	}
	

	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brMergePatientRecordsGPSHIS()");
	return $actionReturn;
}

function onCustomEventUpdateHealthCareInsFromdocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	if ($objTable->getudfvalue("u_hci_fr_name")=="") {
		return true;
	}
	$obju_HISHealthCareIns = new masterdataschema("u_hishealthcareins",$objConnection,"u_hishealthcareins");
	
	if (!$obju_HISHealthCareIns->getbysql("NAME='".addslashes($objTable->getudfvalue("u_hci_fr_name"))."'")) {
		$obju_HISHealthCareIns->prepareadd();
		$obju_HISHealthCareIns->code = $objTable->getudfvalue("u_hci_fr_name");
		$obju_HISHealthCareIns->name = $obju_HISHealthCareIns->code;
	} else {
		if ($obju_HISHealthCareIns->code == $objTable->getudfvalue("u_hci_fr_name") && $obju_HISHealthCareIns->getudfvalue("u_address") == $objTable->getudfvalue("u_hci_fr_address")) return true;
	}

	$obju_HISHealthCareIns->name = $obju_HISHealthCareIns->code;
	$obju_HISHealthCareIns->setudfvalue("u_street",$objTable->getudfvalue("u_hci_fr_street"));
	$obju_HISHealthCareIns->setudfvalue("u_city",$objTable->getudfvalue("u_hci_fr_city"));
	$obju_HISHealthCareIns->setudfvalue("u_province",$objTable->getudfvalue("u_hci_fr_province"));
	$obju_HISHealthCareIns->setudfvalue("u_zip",$objTable->getudfvalue("u_hci_fr_zip"));
	$obju_HISHealthCareIns->setudfvalue("u_address",$objTable->getudfvalue("u_hci_fr_address"));
	
	if ($obju_HISHealthCareIns->rowstat=="N") $actionReturn = $obju_HISHealthCareIns->add();
	else $actionReturn = $obju_HISHealthCareIns->update($obju_HISHealthCareIns->code,$obju_HISHealthCareIns->rcdversion);
	
	return $actionReturn;
}

function onCustomEventUpdateHealthCareInsTodocumentschema_brGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue("u_hci_to_name")=="") {
		return true;
	}
	$obju_HISHealthCareIns = new masterdataschema("u_hishealthcareins",$objConnection,"u_hishealthcareins");
	
	if (!$obju_HISHealthCareIns->getbysql("NAME='".addslashes($objTable->getudfvalue("u_hci_to_name"))."'")) {
		$obju_HISHealthCareIns->prepareadd();
		$obju_HISHealthCareIns->code = $objTable->getudfvalue("u_hci_to_name");
		$obju_HISHealthCareIns->name = $obju_HISHealthCareIns->code;
	} else {
		if ($obju_HISHealthCareIns->code == $objTable->getudfvalue("u_hci_to_name") && $obju_HISHealthCareIns->getudfvalue("u_address") == $objTable->getudfvalue("u_hci_to_address")) return true;
	}
	$obju_HISHealthCareIns->name = $obju_HISHealthCareIns->code;
	$obju_HISHealthCareIns->setudfvalue("u_street",$objTable->getudfvalue("u_hci_to_street"));
	$obju_HISHealthCareIns->setudfvalue("u_city",$objTable->getudfvalue("u_hci_to_city"));
	$obju_HISHealthCareIns->setudfvalue("u_province",$objTable->getudfvalue("u_hci_to_province"));
	$obju_HISHealthCareIns->setudfvalue("u_zip",$objTable->getudfvalue("u_hci_to_zip"));
	$obju_HISHealthCareIns->setudfvalue("u_address",$objTable->getudfvalue("u_hci_to_address"));
	
	if ($obju_HISHealthCareIns->rowstat=="N") $actionReturn = $obju_HISHealthCareIns->add();
	else $actionReturn = $obju_HISHealthCareIns->update($obju_HISHealthCareIns->code,$obju_HISHealthCareIns->rcdversion);
	
	return $actionReturn;
}

function onCustomEventdocumentschema_brARDeductionPRGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset(null,$objConnection);
	$obju_HISARDeductionItems = new documentlinesschema_br(null,$objConnection,"u_hisardeductionitems");
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);

	$settings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $settings["autopost"];
	$objJvHdr->jeposting = $settings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
	$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "A/R Deduction";
	
	if (!$delete) $objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
	//else $objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
	
	$objJvHdr->remarks = "A/R Deduction";
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->itemtype = "A";
		$objJvDtl->itemno = $objTable->getudfvalue("u_glacctno");
		$objJvDtl->itemname = $objTable->getudfvalue("u_glacctname");
		if (!$delete) {
			$objJvDtl->debit = $objTable->getudfvalue("u_totaldeduction");
			$objJvDtl->grossamount = $objJvDtl->debit;
		} else {
			$objJvDtl->credit = $objTable->getudfvalue("u_totaldeduction");
			$objJvDtl->grossamount = $objJvDtl->credit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;

		$actionReturn = $objJvDtl->add();
	}

	if ($actionReturn) {
		$obju_HISARDeductionItems->queryopen($obju_HISARDeductionItems->selectstring(). " AND DOCID='$objTable->docid' AND U_DEDUCTION<>0");
		while ($obju_HISARDeductionItems->queryfetchrow()) {
			//var_dump(array($obju_HISARDeductionItems->getudfvalue("u_custno"),$obju_HISARDeductionItems->getudfvalue("u_custname"),$obju_HISARDeductionItems->getudfvalue("u_deduction")));
			$u_deduction = $obju_HISARDeductionItems->getudfvalue("u_deduction");
			$objRs->queryopen("select b.docno , b.balanceamount from journalvoucheritems b inner join journalvouchers c on c.company=b.company and c.branch=b.branch and c.docid=b.docid and c.docdate<='".$objTable->getudfvalue("u_docdate")."' where b.company='".$_SESSION["company"]."' and b.branch='".$_SESSION["branch"]."' and b.itemtype='C' and b.itemno='".$obju_HISARDeductionItems->getudfvalue("u_custno")."' and b.othertrxtype='".$obju_HISARDeductionItems->getudfvalue("u_trxtype")."' and b.balanceamount<>0 order by b.balanceamount asc, c.docdate asc, b.docno asc");
			while ($objRs->queryfetchrow("NAME")) {			
				if ($objRs->fields["balanceamount"]>0) {
					if ($objRs->fields["balanceamount"]>$u_deduction) $amount = $u_deduction;
					else $amount = $objRs->fields["balanceamount"];
					//var_dump($amount);
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $obju_HISARDeductionItems->getudfvalue("u_custno");
					$objJvDtl->itemname = $obju_HISARDeductionItems->getudfvalue("u_custname");
					$objJvDtl->reftype = "JOURNALVOUCHER";
					$objJvDtl->refno = $objRs->fields["docno"];
					if (!$delete) {
						$objJvDtl->credit = $amount;
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $amount;
						$objJvDtl->grossamount = $objJvDtl->debit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					
					$u_deduction-=$amount;
					if ($u_deduction==0) break;
					
				} else {
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $obju_HISARDeductionItems->getudfvalue("u_custno");
					$objJvDtl->itemname = $obju_HISARDeductionItems->getudfvalue("u_custname");
					$objJvDtl->reftype = "JOURNALVOUCHER";
					$objJvDtl->refno = $objRs->fields["docno"];
					if (!$delete) {
						$objJvDtl->debit = $objRs->fields["balanceamount"]*-1;
						$objJvDtl->grossamount = $objJvDtl->debit;
					} else {
						$objJvDtl->credit = $objRs->fields["balanceamount"]*-1;
						$objJvDtl->grossamount = $objJvDtl->credit;
					}	
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					
					$u_deduction+=($objRs->fields["balanceamount"]*-1);
				}				
				if (!$actionReturn) break;
			}
			if (bcsub($u_deduction,0,14)!=0) return raiseError("Total deduction [".$obju_HISARDeductionItems->getudfvalue("u_deduction")."] and have variance [$u_deduction] for Customer [".$obju_HISARDeductionItems->getudfvalue("u_custname")."/".$obju_HISARDeductionItems->getudfvalue("u_trxtype")."].");
			if (!$actionReturn) break;
		}	
	}

	if ($actionReturn) $actionReturn = $objJvHdr->add();				

	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brARDeductionPRGPSHIS()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brAPPfsPRGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset(null,$objConnection);
	$obju_HISAPPfItems = new documentlinesschema_br(null,$objConnection,"u_hisappfitems");
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);

	$settings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $settings["autopost"];
	$objJvHdr->jeposting = $settings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_docdate");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
	$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "A/P Professional Fees";
	
	if (!$delete) $objTable->setudfvalue("u_jvdocno",$objJvHdr->docno);
	//else $objTable->setudfvalue("u_jvcndocno",$objJvHdr->docno);
	
	$objJvHdr->remarks = "A/P Professional Fees";
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	if ($actionReturn) {
		$objJvDtl->prepareadd();
		$objJvDtl->docid = $objJvHdr->docid;
		$objJvDtl->objectcode = $objJvHdr->objectcode;
		$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
		$objJvDtl->refbranch = $_SESSION["branch"];
		$objJvDtl->itemtype = "A";
		if ($objTable->getudfvalue("u_gltype")=="A") {
			$deposited = -1;
			$objRs->queryopen("SELECT CASHONHAND FROM CHARTOFACCOUNTS WHERE FORMATCODE='".$objTable->getudfvalue("u_glacctno")."'");
			if ($objRs->queryfetchrow()) {
				if ($objRs->fields[0]==1) $deposited = 0;
			}
			
			$objJvDtl->itemno = $objTable->getudfvalue("u_glacctno");
			$objJvDtl->itemname = $objTable->getudfvalue("u_glacctname");
			$objJvDtl->deposited = $deposited;
		} else {
			$gldata = getBranchHouseBankAccountGLAcctNo($_SESSION["branch"],"PH",$objTable->getudfvalue("u_bank"),$objTable->getudfvalue("u_bankacctno"));
			if ($gldata["acctname"]=="") return raiseError("Invalid Bank Account G/L [".$objTable->getudfvalue("u_bank")."/".$objTable->getudfvalue("u_bankacctno")."]");
			$objJvDtl->isbankacct = 1;
			$objJvDtl->country = "PH";
			$objJvDtl->bank = $objTable->getudfvalue("u_bank");
			$objJvDtl->bankacctno = $objTable->getudfvalue("u_bankacctno");
			$objJvDtl->itemno = $gldata["formatcode"];
			$objJvDtl->itemname = $gldata["acctname"];
		}	
		if (!$delete) {
			$objJvDtl->credit = $objTable->getudfvalue("u_totalpayment");
			$objJvDtl->grossamount = $objJvDtl->credit;
		} else {
			$objJvDtl->debit = $objTable->getudfvalue("u_totalpayment");
			$objJvDtl->grossamount = $objJvDtl->debit;
		}	
		$objJvHdr->totaldebit += $objJvDtl->debit ;
		$objJvHdr->totalcredit += $objJvDtl->credit ;
		$objJvDtl->privatedata["header"] = $objJvHdr;

		$actionReturn = $objJvDtl->add();
	}

	if ($actionReturn) {
		$obju_HISAPPfItems->queryopen($obju_HISAPPfItems->selectstring(). " AND DOCID='$objTable->docid' AND U_SELECTED=1");
		while ($obju_HISAPPfItems->queryfetchrow()) {
			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->itemtype = "S";
			$objJvDtl->itemno = $obju_HISAPPfItems->getudfvalue("u_doctorid");
			$objJvDtl->itemname = $obju_HISAPPfItems->getudfvalue("u_doctorname");
			$objJvDtl->reftype = $obju_HISAPPfItems->getudfvalue("u_reftype");
			$objJvDtl->refno = $obju_HISAPPfItems->getudfvalue("u_refno");
			if (!$delete) {
				if ($obju_HISAPPfItems->getudfvalue("u_amount")>0) {
					$objJvDtl->debit = $obju_HISAPPfItems->getudfvalue("u_amount");
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = $obju_HISAPPfItems->getudfvalue("u_amount")*-1;
					$objJvDtl->grossamount = $objJvDtl->credit;
				}	
			} else {
				if ($obju_HISAPPfItems->getudfvalue("u_amount")>0) {
					$objJvDtl->credit = $obju_HISAPPfItems->getudfvalue("u_amount");
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = $obju_HISAPPfItems->getudfvalue("u_amount")*-1;
					$objJvDtl->grossamount = $objJvDtl->debit;
				}	
			}	
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			
			if ($actionReturn && $obju_HISAPPfItems->getudfvalue("u_wtax")!=0) {
				$gldata = getWTaxGLAcctNo($_SESSION["branch"],$obju_HISAPPfItems->getudfvalue("u_wtaxcode"));
				if ($gldata["acctname"]=="") return raiseError("WTax [".$obju_HISAPPfItems->getudfvalue("u_wtaxcode")."] G/L was not maintained.");
				$objJvDtl->prepareadd();
				$objJvDtl->docid = $objJvHdr->docid;
				$objJvDtl->objectcode = $objJvHdr->objectcode;
				$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
				$objJvDtl->refbranch = $_SESSION["branch"];
				$objJvDtl->itemtype = "A";
				$objJvDtl->itemno = $gldata["formatcode"];
				$objJvDtl->itemname = $gldata["acctname"];
				$objJvDtl->suppno = $obju_HISAPPfItems->getudfvalue("u_doctorid");
				$objJvDtl->otherrefno = $obju_HISAPPfItems->getudfvalue("u_refno");
				if (!$delete) {
					if ($obju_HISAPPfItems->getudfvalue("u_wtax")>0) {
						$objJvDtl->credit = $obju_HISAPPfItems->getudfvalue("u_wtax");
						$objJvDtl->grossamount = $objJvDtl->credit;
					} else {
						$objJvDtl->debit = $obju_HISAPPfItems->getudfvalue("u_wtax")*-1;
						$objJvDtl->grossamount = $objJvDtl->debit;
					}	
				} else {
					if ($obju_HISAPPfItems->getudfvalue("u_wtax")>0) {
						$objJvDtl->debit = $obju_HISAPPfItems->getudfvalue("u_wtax");
						$objJvDtl->grossamount = $objJvDtl->debit;
					} else {
						$objJvDtl->credit = $obju_HISAPPfItems->getudfvalue("u_wtax")*-1;
						$objJvDtl->grossamount = $objJvDtl->credit;
					}	
				}	
				$objJvHdr->totaldebit += $objJvDtl->debit ;
				$objJvHdr->totalcredit += $objJvDtl->credit ;
				$objJvDtl->privatedata["header"] = $objJvHdr;
				$actionReturn = $objJvDtl->add();
			}				
			if (!$actionReturn) break;
		}	
	}

	if ($actionReturn) $actionReturn = $objJvHdr->add();				

	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brAPPfsPRGPSHIS()");
	return $actionReturn;
}

function onCustomEventdocumentschema_brPostHealthInPayCMGPSHIS($objTable,$delete=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objCollections = new collections(null,$objConnection) ;
	$objCollectionsCheques = new collectionscheques(null,$objConnection) ;
	$objCollectionsCreditCards = new collectionscreditcards(null,$objConnection) ;
	$objCollectionsCashCards = new collectionscashcards(null,$objConnection) ;
	$objCollectionsInvoices = new collectionsinvoices(null,$objConnection) ;
	$objRs = new recordset(null,$objConnection) ;
	$objRs2 = new recordset(null,$objConnection) ;

	$customerdata = getcustomerdata($objTable->getudfvalue("u_inscode"),"CUSTNO,CUSTNAME,PAYMENTTERM,CURRENCY");
	if ($customerdata["CUSTNAME"]=="") return raiseError("Invalid Customer [".$objTable->getudfvalue("u_inscode")."].");
	
	$settings = getBusinessObjectSettings("INCOMINGPAYMENT");
	if ($delete) {
		if ($actionReturn) {
			if ($objCollections->getbykey($_SESSION["branch"], $objTable->docno)) {
				if ($actionReturn) {
					$objCollections->cleared = -99;
					$objCollections->cancelleddate = $objCollections->docdate;
					$objCollections->cancelledby = $_SESSION["userid"];
					$objCollections->docstat = 'CN';
					if ($actionReturn) $actionReturn = $objCollections->update($objCollections->branchcode,$objCollections->docno,$objCollections->rcdversion);
				}		
				//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brPostDPGPSHIS");
				return $actionReturn;
			} else return raiseError("Unable to Find Collection Record.");
		}	
	}	
	$objCollections->docdate = $objTable->getudfvalue("u_docdate");
	$objCollections->bpcode = $customerdata["CUSTNO"];
	$objCollections->bpname = $customerdata["CUSTNAME"];
	$objCollections->paidamount = $objTable->getudfvalue("u_variance");
	$objCollections->docno = $objTable->docno;
	$objCollections->docstat = 'C';
	$objTable->setudfvalue("u_cmdocno",$objCollections->docno);
	if ($actionReturn) $actionReturn = isPostingDateValid($objCollections->docdate,$objCollections->docduedate,$objCollections->taxdate);
	if ($actionReturn) {
		$objCollections->docid = getNextIdByBranch('collections',$objConnection);
		$objCollections->sbo_post_flag = $settings["autopost"];
		$objCollections->jeposting = $settings["jeposting"];
		$objCollections->objectcode = "INCOMINGPAYMENT";
		$objCollections->changeamount = 0;
		$objCollections->doctype = "C";
		$objCollections->trxtype = "CM";
		$objCollections->collfor = "SI";
		$objCollections->balanceamount = 0;
		$objCollections->dueamount = $objCollections->paidamount;

		$objCollections->bpcurrency = $customerdata["CURRENCY"];
		$objCollections->currency = $customerdata["CURRENCY"];
		$objCollections->docduedate = $objCollections->docdate;
		$objCollections->taxdate = $objCollections->docdate;
		$objCollections->branchcode = $_SESSION["branch"];
		$objCollections->valuedate = $objCollections->docdate;
		$objCollections->cleared = 1;
		$objCollections->postdate = $objCollections->docdate ." ". date('H:i:s');
		$objCollections->journalremark = "Credit Memo - " . $objCollections->bpcode;
		
		if ($actionReturn) {
			$objRs->queryopen("select * from u_hishealthinpaycmdocs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$objTable->docid'");
			while ($objRs->queryfetchrow("NAME")) {
				$objCollectionsInvoices->prepareadd();
				$objCollectionsInvoices->docid = getNextIdByBranch('collectionsinvoices',$objConnection);
				$objCollectionsInvoices->docno = $objCollections->docno;
				$objCollectionsInvoices->reftype = $objRs->fields["U_REFTYPE"];
				$objCollectionsInvoices->refno = $objRs->fields["U_REFNO"];
				$objCollectionsInvoices->bprefno = $objRs->fields["U_REFNO2"];
				$objCollectionsInvoices->refdate = $objRs->fields["U_REFDATE"];
				$objCollectionsInvoices->refbranch = $objCollections->branchcode;
				$objCollectionsInvoices->bpcode = $objCollections->bpcode;
				$objCollectionsInvoices->bpname = $objCollections->bpname;
				$objCollectionsInvoices->currency = $objCollections->currency;
				$objCollectionsInvoices->objectcode = $objCollections->objectcode;
				$objCollectionsInvoices->privatedata["header"] = $objCollections;
				$objCollectionsInvoices->amount = $objRs->fields["U_APPLIED"];
				$objCollectionsInvoices->othertrxtype = $objRs->fields["U_FEETYPE"];
				$objCollectionsInvoices->balanceamount = $objCollectionsInvoices->amount;
				if ($actionReturn) $actionReturn = $objCollectionsInvoices->add();
			}	
		}

								
		if ($actionReturn && $objTable->getudfvalue("u_directpfamount")>0) {
			$objRs->queryopen("select u_bnkcmpfcashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Card - Direct PF");
			
			$objCollectionsCashCards->docid = getNextIdByBranch("collectionscashcards",$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objRs->fields["u_bnkcmpfcashcard"];
			$objCollectionsCashCards->refno = "-"; 
			$objCollectionsCashCards->amount = $objTable->getudfvalue("u_directpfamount");
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			$actionReturn = $objCollectionsCashCards->add();
		}
		
		if ($actionReturn && $objTable->getudfvalue("u_discamount")>0) {
			$objRs->queryopen("select u_bnkcmdccashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Card - Discount");
			
			$objCollectionsCashCards->docid = getNextIdByBranch("collectionscashcards",$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objRs->fields["u_bnkcmdccashcard"];
			$objCollectionsCashCards->refno = "-"; 
			$objCollectionsCashCards->amount = $objTable->getudfvalue("u_discamount");
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			$actionReturn = $objCollectionsCashCards->add();
		}

		if ($actionReturn && $objTable->getudfvalue("u_wtaxamount")>0) {
			$objRs->queryopen("select u_bnkcmwtcashcard from u_hissetup");
			if (!$objRs->queryfetchrow("NAME")) return raiseError("No Cash Card defined for Cash Card - W/Tax");
			
			$objCollectionsCashCards->docid = getNextIdByBranch("collectionscashcards",$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objRs->fields["u_bnkcmwtcashcard"];
			$objCollectionsCashCards->refno = "-"; 
			$objCollectionsCashCards->amount = $objTable->getudfvalue("u_wtaxamount");
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			$actionReturn = $objCollectionsCashCards->add();
		}
		
		/*
		if ($objCollections->colltype=="CPN") {
			$cashcarddata = getcashcarddata($objCollections->userfields["u_cashcard"]["value"],"LINKTODOCNO");
			$objCollectionsCashCards->prepareadd();
			$objCollectionsCashCards->docid = getNextIdByBranch('collectionscashcards',$objConnection);
			$objCollectionsCashCards->docno = $objCollections->docno;
			$objCollectionsCashCards->objectcode = $objCollections->objectcode;
			$objCollectionsCashCards->cashcard = $objCollections->userfields["u_cashcard"]["value"];
			$objCollectionsCashCards->linktodocno = $cashcarddata["LINKTODOCNO"];
			if ($objCollectionsCashCards->linktodocno=="1") {
				$objCollectionsCashCards->linkdocno = $objCollections->acctno;
			}
			$objCollectionsCashCards->refbranch = $objCollections->acctbranch;
			$objCollectionsCashCards->refno = "-";
			$objCollectionsCashCards->amount = $objCollections->paidamount;
			$objCollectionsCashCards->privatedata["header"] = $objCollections;
			if ($actionReturn) $actionReturn = $objCollectionsCashCards->add();
		}
		*/
		if ($actionReturn) $actionReturn = $objCollections->add();
	}		
	
	
	return $actionReturn;
}

function onCustomEventdocumentschema_brLISendFileGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset (NULL,$objConnection);
	$objRs->queryopen("select u_lissenddir, u_lislogsdir from u_hissetup where u_lissenddir<>''");
	if (!$objRs->queryfetchrow("NAME")) return true;
	
	$senddir = $objRs->fields["u_lissenddir"];
	$logsdir = $objRs->fields["u_lislogsdir"];
	if ($actionReturn) {   //lis
		$objRs = new recordset(null,$objConnection);
		$data = array();
		if ($objTable->getudfvalue("u_reftype")=="IP") {
			$objRs->queryopen("select a.u_lastname, a.u_firstname, a.u_middlename, a.u_birthdate, a.u_gender, a.u_civilstatus, c.name as u_doctorname, b.u_requestdate, b.u_requesttime, d.u_bedno from u_hispatients a left join u_hisrequests b on b.company=a.company and b.branch=a.branch and b.docno='".$objTable->getudfvalue("u_requestno")."' left join u_hisips d on d.company=a.company and d.branch=a.branch and d.docno=b.u_refno left join u_hisdoctors c on c.code=b.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='".$objTable->getudfvalue("u_patientid")."'");
		} else {
			$objRs->queryopen("select a.u_lastname, a.u_firstname, a.u_middlename, a.u_birthdate, a.u_gender, a.u_civilstatus, c.name as u_doctorname, b.u_requestdate, b.u_requesttime, '' as u_bedno from u_hispatients a left join u_hisrequests b on b.company=a.company and b.branch=a.branch and b.docno='".$objTable->getudfvalue("u_requestno")."' left join u_hisdoctors c on c.code=b.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='".$objTable->getudfvalue("u_patientid")."'");
		}	
		if ($objRs->queryfetchrow("NAME")) {
			array_push($data,"MSH|^~\&|HIS|".$_SESSION["companyname"]."|LMS||".date('YmdHis')."||OMG||P|2.5\r\n");
			array_push($data,"PID|1||".$objTable->getudfvalue("u_patientid")."||".$objRs->fields["u_lastname"]."^".$objRs->fields["u_firstname"]."^".$objRs->fields["u_middlename"]."||".date('Ymd',strtotime($objRs->fields["u_birthdate"]))."|".$objRs->fields["u_gender"]."\r\n");
			array_push($data,"PV1||".iif($objTable->getudfvalue("u_reftype")=="OP","O","I")."||||".$objRs->fields["u_bedno"]."|".$objRs->fields["u_doctorname"]."\r\n");
			array_push($data,"ORC|NW|".$objTable->docno."|||||||".date('YmdHis',strtotime($objTable->getudfvalue("u_startdate")." ".$objTable->getudfvalue("u_starttime")))."||".$objTable->getudfvalue("u_refno")."|".$objRs->fields["u_civilstatus"]."|".date('YmdHis',strtotime($objRs->fields["u_requestdate"]." ".$objRs->fields["u_requesttime"]))."|".$objTable->getudfvalue("u_payrefno")."\r\n");
			$objRs->queryopen("select c.U_ITEMCODE, c.U_ITEMDESC, b.U_SPECIMEN from u_hischargeitems c inner join u_hislabtesttypes b on b.code=c.u_template where c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."'");
			//var_dump($data);
	//var_dump($objRsItems->sqls);
			$ctr=0;
			while ($objRs->queryfetchrow("NAME")) {
				$ctr++; //"OBR|2|<ORDER NUMBER>||<TEST REQUEST CODE>^<TEST REQUEST NAME>|||<DATETIMEREQUEST>||||||||<SPECIMEN>"
				array_push($data,"OBR|".$ctr."|".$objTable->docno."||".$objRs->fields["U_ITEMCODE"]."^".$objRs->fields["U_ITEMDESC"]."|||||||||||".$objRs->fields["U_SPECIMEN"]."\r\n");
			}
			if ($ctr>0) {
				file_put_contents($senddir . $objTable->docno."-".date('YmdHis').".hl7",$data);	
				file_put_contents($logsdir . $objTable->docno."-order".date('YmdHis').".hl7",$data);	
			}	
		}				
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brLISendFileGPSHIS()");

	return $actionReturn;
}

function onCustomEventdocumentschema_brLISendFile2GPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset (NULL,$objConnection);
	$objRs->queryopen("select u_lissenddir, u_lislogsdir from u_hissetup where u_lissenddir<>''");
	if (!$objRs->queryfetchrow("NAME")) return true;
	
	$senddir = $objRs->fields["u_lissenddir"];
	$logsdir = $objRs->fields["u_lislogsdir"];
	if ($actionReturn) {   //lis
		$objRs = new recordset(null,$objConnection);
		$data = array();
		if ($objTable->getudfvalue("u_reftype")=="IP") {
			$objRs->queryopen("select a.u_lastname, a.u_firstname, a.u_middlename, a.u_birthdate, a.u_gender, a.u_civilstatus, c.name as u_doctorname, b.u_requestdate, b.u_requesttime, d.u_bedno from u_hispatients a left join u_hisrequests b on b.company=a.company and b.branch=a.branch and b.docno='".$objTable->getudfvalue("u_requestno")."' left join u_hisips d on d.company=a.company and d.branch=a.branch and d.docno=b.u_refno left join u_hisdoctors c on c.code=b.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='".$objTable->getudfvalue("u_patientid")."'");
		} else {
			$objRs->queryopen("select a.u_lastname, a.u_firstname, a.u_middlename, a.u_birthdate, a.u_gender, a.u_civilstatus, c.name as u_doctorname, b.u_requestdate, b.u_requesttime, '' as u_bedno from u_hispatients a left join u_hisrequests b on b.company=a.company and b.branch=a.branch and b.docno='".$objTable->getudfvalue("u_requestno")."' left join u_hisdoctors c on c.code=b.u_doctorid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.code='".$objTable->getudfvalue("u_patientid")."'");
		}
		if ($objRs->queryfetchrow("NAME")) {
			array_push($data,"MSH|^~\&|HIS|".$_SESSION["companyname"]."|LMS||".date('YmdHis')."||OMG||P|2.5\r\n");
			array_push($data,"PID|1||".$objTable->getudfvalue("u_patientid")."||".$objRs->fields["u_lastname"]."^".$objRs->fields["u_firstname"]."^".$objRs->fields["u_middlename"]."||".date('Ymd',strtotime($objTable->getudfvalue("u_birthdate")))."|".$objTable->getudfvalue("u_gender")."\r\n");
			array_push($data,"PV1||".iif($objTable->getudfvalue("u_reftype")=="OP","O","I")."||||".$objRs->fields["u_bedno"]."|".$objRs->fields["u_doctorname"]."\r\n");
			array_push($data,"ORC|NW|".$objTable->docno."|||||||".date('YmdHis',strtotime($objTable->getudfvalue("u_startdate")." ".$objTable->getudfvalue("u_starttime")))."||".$objTable->getudfvalue("u_refno")."|".$objRs->fields["u_civilstatus"]."|".date('YmdHis',strtotime($objRs->fields["u_requestdate"]." ".$objRs->fields["u_requesttime"]))."|".$objTable->getudfvalue("u_payrefno")."\r\n");
			$objRs->queryopen("select c.U_ITEMCODE, b.NAME as U_ITEMDESC from u_hislabtestcases c inner join u_hisitems b on b.code=c.u_itemcode where c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docid='".$objTable->docid."' group by c.U_ITEMCODE");
			//var_dump("PV1||".iif($objTable->getudfvalue("u_reftype")=="OP","O","I")."||||".$objRs->fields["u_bedno"]."|".$objRs->fields["u_doctorname"]."\r\n");
	//var_dump($objRsItems->sqls);
			$ctr=0;
			while ($objRs->queryfetchrow("NAME")) {
				$ctr++; //"OBR|2|<ORDER NUMBER>||<TEST REQUEST CODE>^<TEST REQUEST NAME>|||<DATETIMEREQUEST>||||||||<SPECIMEN>"
				array_push($data,"OBR|".$ctr."|".$objTable->docno."||".$objRs->fields["U_ITEMCODE"]."^".$objRs->fields["U_ITEMDESC"]."|||||||||||".$objTable->getudfvalue("u_specimen")."\r\n");
			}
			if ($ctr>0) {
				file_put_contents($senddir . $objTable->docno."-".date('YmdHis').".hl7",$data);	
				file_put_contents($logsdir . $objTable->docno."-order".date('YmdHis').".hl7",$data);	
			}	
		}				
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brLISendFile2GPSHIS()");

	return $actionReturn;
}

function onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS($objTable) {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue("u_reftype")=="IP" || $objTable->getudfvalue("u_reftype")=="OP") {
	
		$objRs = new recordset (NULL,$objConnection);
		
		if ($objTable->getudfvalue("u_reftype")=="IP") {
			$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
		} else {
			$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisops");
		}	
		
		if ($obju_HISIPs->getbykey($objTable->getudfvalue("u_refno"))) {
			$objRs->queryopen("select sum(U_DUEAMOUNT) from (
select SUM(IF(a.U_DISCONBILL=0,(b.U_QUANTITY-b.U_RTQTY)*b.U_PRICE,IF(b.U_ISSTAT=1,(b.U_QUANTITY-b.U_RTQTY)*b.U_STATUNITPRICE,(b.U_QUANTITY-b.U_RTQTY)*b.U_UNITPRICE))) as U_DUEAMOUNT from u_hischarges a inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$obju_HISIPs->company."' and a.branch='".$obju_HISIPs->branch."' and a.u_reftype='".$objTable->getudfvalue("u_reftype")."' and a.u_refno='".$obju_HISIPs->docno."' and a.u_prepaid in (0,2) and docstatus not in ('CN') 
 union all 
select a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hiscredits a where a.company='".$obju_HISIPs->company."' and a.branch='".$obju_HISIPs->branch."' and a.u_reftype='".$objTable->getudfvalue("u_reftype")."' and a.u_refno='".$obju_HISIPs->docno."' and a.U_PREPAID<>2
 union all 
select a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hispos a where a.company='".$obju_HISIPs->company."' and a.branch='".$obju_HISIPs->branch."' and a.u_reftype='".$objTable->getudfvalue("u_reftype")."' and a.u_refno='".$obju_HISIPs->docno."' and u_prepaid in (2) and docstatus not in ('CN') and u_balance<>0) as x");
			if ($objRs->queryfetchrow()) {
				$obju_HISIPs->setudfvalue("u_totalcharges",$objRs->fields[0]);
				$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit") - $obju_HISIPs->getudfvalue("u_totalcharges"));
				$obju_HISIPs->setudfvalue("u_availablecreditperc",100-((objutils::Divide($obju_HISIPs->getudfvalue("u_totalcharges"),$obju_HISIPs->getudfvalue("u_creditlimit")))*100));
				
				$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
			}		
		} else $actionReturn = raiseError("Unable to update total charges, cannot find Reference No.[".$objTable->getudfvalue("u_refno")."]");
	}
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventdocumentschema_brUpdateTotalChargesGPSHIS()");
	
	return $actionReturn;
}
	
?> 

