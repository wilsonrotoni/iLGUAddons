<?php 

	include_once("./classes/projectgroups.php");
	include_once("./classes/projects.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/companies.php");
	include_once("./classes/customers.php");
	include_once("./classes/suppliers.php");
	
	include_once("./utils/sdk.php");

	function onBeforeAddEventmasterdataschema_brGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hispatients":
			
				if ($actionReturn) $actionReturn = onCustomEventUpdateBarangaymasterdataschema_brGPSHIS($objTable);
				if ($actionReturn) $actionReturn = onCustomEventUpdateCitymasterdataschema_brGPSHIS($objTable);
				if ($actionReturn) {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select custgroup from customergroups where groupname='Patients'");
					if (!$objRs->queryfetchrow("NAME")) {
						return raiseError("Customer group Patients must be define.");
					}
					$custgroup = $objRs->fields["custgroup"];
					
					$gldata= getBranchGLAcctNo($thisbranch,"U_ARACCTPATIENT");
					if ($gldata["formatcode"]=="") {
						return raiseError("G/L Acct [Account Receivable - Patient] is not maintained.");
					}				
					
					$objCustomers = new customers(null,$objConnection);
					$objCustomers->prepareadd();
					$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
					$objCustomers->custno = $objTable->code;
					$objCustomers->custname = $objTable->name;
					$objCustomers->firstname = $objTable->getudfvalue("u_firstname");
					$objCustomers->middlename = $objTable->getudfvalue("u_middlename");
					$objCustomers->lastname = $objTable->getudfvalue("u_lastname");
					$objCustomers->custclass = "I";
					$objCustomers->currency = $_SESSION["currency"];
					$objCustomers->custgroup = $custgroup;
					$objCustomers->paymentterm = 1;
					$objCustomers->debtpayacctno = $gldata["formatcode"];
					$objCustomers->advanceacctno = $gldata["formatcode"];
					$actionReturn = $objCustomers->add();
				}	
				break;
			case "u_hishealthins":
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select custgroup from customergroups where groupname='Health Insurance'");
				if (!$objRs->queryfetchrow("NAME")) {
					return raiseError("Customer group Health Insurance must be define.");
				}
				$custgroup = $objRs->fields["custgroup"];
				
				$gldata= getBranchGLAcctNo($thisbranch,"U_ARACCTHEALTHINS");
				if ($gldata["formatcode"]=="") {
					return raiseError("G/L Acct [Account Receivable - Health Insurance] is not maintained.");
				}				
				
				$objCustomers = new customers(null,$objConnection);
				$objCustomers->prepareadd();
				$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
				$objCustomers->custno = $objTable->code;
				$objCustomers->custname = $objTable->name;
				$objCustomers->custclass = "C";
				$objCustomers->currency = $_SESSION["currency"];
				$objCustomers->custgroup = $custgroup;
				$objCustomers->debtpayacctno = $gldata["formatcode"];
				$objCustomers->advanceacctno = $gldata["formatcode"];
				$actionReturn = $objCustomers->add();
				break;
			case "u_hisphicsetup":
				$objCompanies = new companies(null,$objConnection);
				$objCompanies->queryopen();
				if ($objCompanies->queryfetchrow()) {
					$objCompanies->companyname = $objTable->getudfvalue("u_companyname");
					$actionReturn = $objCompanies->update($objCompanies->companycode,$objCompanies->rcdversion);
				}	
				break;
		}		
		return $actionReturn;
	}

	function onBeforeUpdateEventmasterdataschema_brGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hispatients":
				if ($actionReturn) $actionReturn = onCustomEventUpdateBarangaymasterdataschema_brGPSHIS($objTable);
				if ($actionReturn) $actionReturn = onCustomEventUpdateCitymasterdataschema_brGPSHIS($objTable);
				if ($actionReturn) {
					$objCustomers = new customers(null,$objConnection);
					$actionReturn = $objCustomers->prepareupdate($objTable->code);
					if ($actionReturn) {
						$objCustomers->custname = $objTable->name;
						$objCustomers->firstname = $objTable->getudfvalue("u_firstname");
						$objCustomers->middlename = $objTable->getudfvalue("u_middlename");
						$objCustomers->lastname = $objTable->getudfvalue("u_lastname");
						$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
					}	
				}
				if ($actionReturn && $objTable->name!=$objTable->fields["NAME"]) {
					$objRs = new recordset(null,$objConnection);
					$actionReturn = $objRs->executesql("update u_hisips set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisops set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisrequests set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hischarges set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hiscredits set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisbills set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hispos set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hismedrecs set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hispronotes set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisinclaims set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hislabtests set u_patientname='".$objTable->name."' where u_patientid='".$objTable->code."'",false);
				}
				
				if ($actionReturn && $objTable->getudfvalue("u_gender")!=$objTable->fields["U_GENDER"]) {
					$objRs = new recordset(null,$objConnection);
					$actionReturn = $objRs->executesql("update u_hisips set u_gender='".$objTable->getudfvalue("u_gender")."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisops set u_gender='".$objTable->getudfvalue("u_gender")."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisrequests set u_gender='".$objTable->getudfvalue("u_gender")."' where u_patientid='".$objTable->code."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisbills set u_gender='".$objTable->getudfvalue("u_gender")."' where u_patientid='".$objTable->code."'",false);
				}
				
				if ($actionReturn && $objTable->getudfvalue("u_birthdate")!=$objTable->fields["U_BIRTHDATE"]) {
					$objRs = new recordset(null,$objConnection);
					$obju_HISIPs = new documentschema_br(null,$objConnection,"u_hisips");
					$obju_HISOPs = new documentschema_br(null,$objConnection,"u_hisops");
					$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
					$obju_HISPronotes = new documentschema_br(null,$objConnection,"u_hispronotes");
					$obju_HISInClaims = new documentschema_br(null,$objConnection,"u_hisinclaims");
					$obju_HISIPs->queryopen($obju_HISIPs->selectstring()." AND U_PATIENTID='".$objTable->code."'");
					while ($obju_HISIPs->queryfetchrow()) {
						$obju_HISIPs->setudfvalue("u_birthdate",$objTable->getudfvalue("u_birthdate"));
						$age = getAgeOnDate($obju_HISIPs->getudfvalue("u_birthdate"),$obju_HISIPs->getudfvalue("u_startdate"));
						$obju_HISIPs->setudfvalue("u_age_y",$age[0]);
						$obju_HISIPs->setudfvalue("u_age_m",$age[1]);
						$obju_HISIPs->setudfvalue("u_age_d",$age[2]);
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						if ($actionReturn) {
							$obju_HISBills->queryopen($obju_HISBills->selectstring()." AND U_REFTYPE='IP' AND U_REFNO='".$obju_HISIPs->docno."'");
							while ($obju_HISBills->queryfetchrow()) {
								$obju_HISBills->setudfvalue("u_age",$age[0]);
								$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
								if (!$actionReturn) break;
							}
						}
						if ($actionReturn) {
							$obju_HISPronotes->queryopen($obju_HISPronotes->selectstring()." AND U_REFTYPE='IP' AND U_REFNO='".$obju_HISIPs->docno."'");
							while ($obju_HISPronotes->queryfetchrow()) {
								$obju_HISPronotes->setudfvalue("u_age",$age[0]);
								$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
								if (!$actionReturn) break;
							}
						}
						if ($actionReturn) {
							$obju_HISInClaims->queryopen($obju_HISInClaims->selectstring()." AND U_REFTYPE='IP' AND U_REFNO='".$obju_HISIPs->docno."'");
							while ($obju_HISInClaims->queryfetchrow()) {
								$obju_HISInClaims->setudfvalue("u_age",$age[0]);
								$actionReturn = $obju_HISInClaims->update($obju_HISInClaims->docno,$obju_HISInClaims->rcdversion);
								if (!$actionReturn) break;
							}
						}
						if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisinclaimxmtalitems set u_age='".$age[0]."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_patientid='".$objTable->code."' and u_startdate='".$obju_HISIPs->getudfvalue("u_startdate")."'",false);
						if (!$actionReturn) break;
					}
					if ($actionReturn) {
						$obju_HISOPs->queryopen($obju_HISOPs->selectstring()." AND U_PATIENTID='".$objTable->code."'");
						while ($obju_HISOPs->queryfetchrow()) {
							$obju_HISOPs->setudfvalue("u_birthdate",$objTable->getudfvalue("u_birthdate"));
							$age = getAgeOnDate($obju_HISOPs->getudfvalue("u_birthdate"),$obju_HISOPs->getudfvalue("u_startdate"));
							$obju_HISOPs->setudfvalue("u_age_y",$age[0]);
							$obju_HISOPs->setudfvalue("u_age_m",$age[1]);
							$obju_HISOPs->setudfvalue("u_age_d",$age[2]);
							$actionReturn = $obju_HISOPs->update($obju_HISOPs->docno,$obju_HISOPs->rcdversion);
							if ($actionReturn) {
								$obju_HISBills->queryopen($obju_HISBills->selectstring()." AND U_REFTYPE='OP' AND U_REFNO='".$obju_HISOPs->docno."'");
								while ($obju_HISBills->queryfetchrow()) {
									$obju_HISBills->setudfvalue("u_age",$age[0]);
									$actionReturn = $obju_HISBills->update($obju_HISBills->docno,$obju_HISBills->rcdversion);
									if (!$actionReturn) break;
								}
							}
							if ($actionReturn) {
								$obju_HISPronotes->queryopen($obju_HISPronotes->selectstring()." AND U_REFTYPE='OP' AND U_REFNO='".$obju_HISOPs->docno."'");
								while ($obju_HISPronotes->queryfetchrow()) {
									$obju_HISPronotes->setudfvalue("u_age",$age[0]);
									$actionReturn = $obju_HISPronotes->update($obju_HISPronotes->docno,$obju_HISPronotes->rcdversion);
									if (!$actionReturn) break;
								}
							}
							if ($actionReturn) {
								$obju_HISInClaims->queryopen($obju_HISInClaims->selectstring()." AND U_REFTYPE='OP' AND U_REFNO='".$obju_HISOPs->docno."'");
								while ($obju_HISInClaims->queryfetchrow()) {
									$obju_HISInClaims->setudfvalue("u_age",$age[0]);
									$actionReturn = $obju_HISInClaims->update($obju_HISInClaims->docno,$obju_HISInClaims->rcdversion);
									if (!$actionReturn) break;
								}
							}
							if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisinclaimxmtalitems set u_age='".$age[0]."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_patientid='".$objTable->code."' and u_startdate='".$obju_HISOPs->getudfvalue("u_startdate")."'",false);
							if (!$actionReturn) break;
						}
					}
				}
				
				break;
			case "u_hisitems":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$objItems->itemdesc = $objTable->name;
					$objItems->isvalid = $objTable->getudfvalue("u_active");
					//$objItems->itemgroup = $objTable->getudfvalue("u_itemgroup");
					//$objItems->manageby = "2";
					$actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				}	
				break;
			case "u_hislabtesttypes":
				$objItems = new items(null,$objConnection);
				
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$objItems->itemdesc = $objTable->name;
					$objItems->isvalid = $objTable->getudfvalue("u_active");
					//$objItems->itemgroup = $objTable->getudfvalue("u_itemgroup");
					//$objItems->manageby = "2";
					$actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				}	
				break;
			case "u_hisservices":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$objItems->itemdesc = $objTable->name;
					$objItems->isvalid = $objTable->getudfvalue("u_active");
					//$objItems->itemgroup = $objTable->getudfvalue("u_itemgroup");
					//$objItems->manageby = "2";
					$actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				}	
				break;
			case "u_hisroomtypes":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$objItems->itemdesc = $objTable->name;
					//$objItems->isvalid = $objTable->getudfvalue("u_active");
					//$objItems->itemgroup = $objTable->getudfvalue("u_itemgroup");
					//$objItems->manageby = "2";
					$actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				}	
				break;
			case "u_hisphicsetup":
				$objCompanies = new companies(null,$objConnection);
				$objCompanies->queryopen();
				if ($objCompanies->queryfetchrow()) {
					$objCompanies->companyname = $objTable->getudfvalue("u_companyname");
					$actionReturn = $objCompanies->update($objCompanies->companycode,$objCompanies->rcdversion);
				}	
				if ($actionReturn && $objTable->getudfvalue("u_interval")!=$objTable->fields["U_INTERVAL"]) {
					$objRs = new recordset(null,$objConnection);
					$objRs->setdebug();
					$actionReturn = $objRs->executesql("update u_hisphicicds set u_interval='".$objTable->getudfvalue("u_interval")."' where u_interval='".$objTable->fields["U_INTERVAL"]."'",false);
					if ($actionReturn) $actionReturn = $objRs->executesql("update u_hisphicrvs set u_interval='".$objTable->getudfvalue("u_interval")."' where u_interval='".$objTable->fields["U_INTERVAL"]."'",false);
				}	
				break;
		}		
		return $actionReturn;
	}

	function onBeforeDeleteEventmasterdataschema_brGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hisicdchapters":
				$actionReturn = onCustomEventmasterdataschema_brUpdateHISICDGPSHIS($objTable,1,true);
				break;
			case "u_hisicdblocks":
				$actionReturn = onCustomEventmasterdataschema_brUpdateHISICDGPSHIS($objTable,2,true);
				break;
			case "u_hisicdcodes":
				$actionReturn = onCustomEventmasterdataschema_brUpdateHISICDGPSHIS($objTable,null,true);
				break;
			case "u_hisitems":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$actionReturn = $objItems->delete();
				}	
				break;
			case "u_hislabtesttypes":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$actionReturn = $objItems->delete();
				}	
				break;
			case "u_hisservices":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$actionReturn = $objItems->delete();
				}	
				break;
		}		
		return $actionReturn;
	}
	
	function onCustomEventmasterdataschema_brUpdateHISICDGPSHIS($objTable,$level,$delete=false) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		$obju_HISICDs = new masterdataschema_br(null,$objConnection,"u_hisicds");

		
		if ($delete) {
			if ($obju_HISICDs->getbykey($objTable->code)) {
				$actionReturn = $obju_HISICDs->delete();
			}	
		} else {
			if (!$obju_HISICDs->getbykey($objTable->code)) {
				$obju_HISICDs->prepareadd();
				$obju_HISICDs->code = $objTable->code;
			}	
			$obju_HISICDs->name = $objTable->name;
			if (isset($level)) {
				$obju_HISICDs->setudfvalue("u_level",$level);
				if ($level==2) $obju_HISICDs->setudfvalue("u_chapter",$objTable->getudfvalue("u_chapter"));
			} else {
				$obju_HISICDs->setudfvalue("u_level",$objTable->getudfvalue("u_level"));
				$obju_HISICDs->setudfvalue("u_chapter",$objTable->getudfvalue("u_chapter"));
				$obju_HISICDs->setudfvalue("u_block",$objTable->getudfvalue("u_block"));
				$obju_HISICDs->setudfvalue("u_classplace",$objTable->getudfvalue("u_classplace"));
				$obju_HISICDs->setudfvalue("u_terminalnode",$objTable->getudfvalue("u_terminalnode"));
				$obju_HISICDs->setudfvalue("u_mortality1",$objTable->getudfvalue("u_mortality1"));
				$obju_HISICDs->setudfvalue("u_mortality2",$objTable->getudfvalue("u_mortality2"));
				$obju_HISICDs->setudfvalue("u_mortality3",$objTable->getudfvalue("u_mortality3"));
				$obju_HISICDs->setudfvalue("u_mortality4",$objTable->getudfvalue("u_mortality4"));
				$obju_HISICDs->setudfvalue("u_morbidity",$objTable->getudfvalue("u_morbidity"));
			}	

			if ($obju_HISICDs->rowstat=="N") $actionReturn = $obju_HISICDs->add();
			else $actionReturn = $obju_HISICDs->update($obju_HISICDs->code,$obju_HISICDs->rcdversion);
		}
		
		return $actionReturn;
	}	

function onCustomEventUpdateBarangaymasterdataschema_brGPSHIS($objTable,$fieldname="u_barangay") {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue($fieldname)=="") return true;
	
	$obju_HISAddrBrgys = new masterdataschema("u_hisaddrbrgys",$objConnection,"u_hisaddrbrgys");
	if (!$obju_HISAddrBrgys->getbysql("CODE='".addslashes($objTable->getudfvalue($fieldname))."'")) {
		$obju_HISAddrBrgys->prepareadd();
		$obju_HISAddrBrgys->code = $objTable->getudfvalue($fieldname);
		$obju_HISAddrBrgys->name = $obju_HISAddrBrgys->code;
		$actionReturn = $obju_HISAddrBrgys->add();
	}
	
	return $actionReturn;
}

function onCustomEventUpdateCitymasterdataschema_brGPSHIS($objTable,$fieldname="u_city") {
	global $objConnection;
	$actionReturn = true;
	if ($objTable->getudfvalue($fieldname)=="") return true;
	
	$obju_HISAddrTownCities = new masterdataschema("u_hisaddrtowncities",$objConnection,"u_hisaddrtowncities");
	if (!$obju_HISAddrTownCities->getbysql("CODE='".addslashes($objTable->getudfvalue($fieldname))."'")) {
		$obju_HISAddrTownCities->prepareadd();
		$obju_HISAddrTownCities->code = $objTable->getudfvalue($fieldname);
		$obju_HISAddrTownCities->name = $obju_HISAddrTownCities->code;
		$actionReturn = $obju_HISAddrTownCities->add();
	}
	
	return $actionReturn;
}

?>