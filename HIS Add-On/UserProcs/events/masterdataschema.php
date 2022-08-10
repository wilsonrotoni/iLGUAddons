<?php 

	include_once("./classes/projectgroups.php");
	include_once("./classes/projects.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/items.php");
	include_once("./classes/itempricelists.php");
	include_once("./classes/suppliers.php");
	include_once("./utils/companies.php");
	include_once("./utils/sdk.php");

	function onBeforeAddEventmasterdataschemaGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hisicdchapters":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,1);
			case "u_hisicdblocks":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,2);
				break;
			case "u_hisicdcodes":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable);
				break;
			case "u_hismdrps":
				$objRs = new recordset(null,$objConnection);
				$objItemPriceLists = new itempricelists(null,$objConnection);
				$obju_HISItems = new masterdataschema(null,$objConnection,"u_hisitems");
				if ($obju_HISItems->getbykey($objTable->code)) {
					$obju_HISItems->setudfvalue("u_salespricing",-1);
					$obju_HISItems->setudfvalue("u_salesmarkup",0);
					$obju_HISItems->setudfvalue("u_salesmarkup2",0);
					$obju_HISItems->setudfvalue("u_scdiscamount",$objTable->getudfvalue("u_price")*.20);
					if ($actionReturn) {
						$objRs->queryopen("select pricelist from pricelists where bptype='C'");
						while ($objRs->queryfetchrow("NAME")) {
							if (!$objItemPriceLists->getbykey($objTable->code,$objRs->fields["pricelist"])) {
								$objItemPriceLists->prepareadd();
								$objItemPriceLists->itemcode = $objTable->code;
								$objItemPriceLists->pricelist = $objRs->fields["pricelist"];
							} 
							$objItemPriceLists->price = $objTable->getudfvalue("u_price");
							if ($objItemPriceLists->rowstat=="N") $actionReturn = $objItemPriceLists->add();
							else $actionReturn = $objItemPriceLists->update($objItemPriceLists->itemcode,$objItemPriceLists->pricelist,$objItemPriceLists->rcdversion);
							if (!$actionReturn) break;
						}
					}
					if ($actionReturn) $actionReturn = $obju_HISItems->update($obju_HISItems->code,$obju_HISItems->rcdversion);
				} else {
					return raiseError("MDRP Item [".$objTable->code."] does not exists in items.");
				}	
				break;
			case "u_hisroomtypes":
				$companydata = getcurrentcompanydata('DFLTTAXCODEITEMPU,DFLTTAXCODEEXEMPTITEMPU,DFLTTAXCODEITEMSA,DFLTTAXCODEEXEMPTITEMSA,DFLTCOSTMETHOD,DFLTGLMETHOD');
				if ($companydata["DFLTTAXCODEITEMPU"]=="") return raiseError("Default Tax Code for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMPU"]=="") return raiseError("Default Tax Code Exempt for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEITEMSA"]=="") return raiseError("Default Tax Code for Sales not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMSA"]=="") return raiseError("Default Tax Code Exempt for Sales not defined in General Settings"); 

				$objItems = new items(null,$objConnection);
				$objItems->prepareadd();
				$objItems->itemcode = $objTable->code;
				$objItems->itemdesc = $objTable->name;
				switch ($objTable->getudfvalue("u_type")) {
					case "Room & Board": $objItems->itemgroup = "ROOM"; break;
					default: $objItems->itemgroup = "SPLROOM"; break;
				}	
				$objItems->itemtype = "S";
				$objItems->isinventory = 0;
				$objItems->ispurchaseitem = 0;
				$objItems->issalesitem = 1;
				$objItems->glmethod = "I";
				$objItems->costmethod = 0;
				if ($objTable->getudfvalue("u_vatable")=="1") {
					$objItems->taxcodepu = $companydata["DFLTTAXCODEITEMPU"];
					$objItems->taxcodesa = $companydata["DFLTTAXCODEITEMSA"];
				} else {
					$objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
					$objItems->taxcodesa = $companydata["DFLTTAXCODEEXEMPTITEMSA"];
				}	
				$objItems->manageby = "0";
				$objItems->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
				//$objItems->isvalid = $objTable->getudfvalue("u_active");
				$actionReturn = $objItems->add();
				break;
			case "u_hishealthins":
				if ($objTable->getudfvalue("u_hmo")!="2" && $objTable->getudfvalue("u_hmo")!="6" && $objTable->getudfvalue("u_hmo")!="7") {
					$objRs = new recordset(null,$objConnection);
					$custgroup = $objTable->getudfvalue("u_group");
					if ($custgroup=="") {
						$objRs->queryopen("select custgroup from customergroups where groupname='Health Insurance'");
						if (!$objRs->queryfetchrow("NAME")) {
							return raiseError("Customer group Health Insurance must be define.");
						}
						$custgroup = $objRs->fields["custgroup"];
						$objTable->setudfvalue("u_group",$custgroup);
					} 	
					$gldata = array();
					$gldata["formatcode"] = $objTable->getudfvalue("u_glacctno");
					if ($objTable->getudfvalue("u_glacctno")=="") {
						$gldata= getBranchGLAcctNo($thisbranch,"U_ARACCTHEALTHINS");
						if ($gldata["formatcode"]=="") {
							return raiseError("G/L Acct [Account Receivable - Health Insurance] is not maintained.");
						}				
					}					
					$objCustomers = new customers(null,$objConnection);
					$objCustomers->prepareadd();
					$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
					$objCustomers->custno = $objTable->code;
					$objCustomers->custname = $objTable->name;
					$objCustomers->custclass = "C";
					$objCustomers->currency = $_SESSION["currency"];
					$objCustomers->custgroup = $custgroup;
					$objCustomers->paymentterm = 1;
					$objCustomers->debtpayacctno = $gldata["formatcode"];
					$objCustomers->advanceacctno = $gldata["formatcode"];
					$actionReturn = $objCustomers->add();
				}	
				break;
			case "u_hisguarantors":
				if ($objTable->getudfvalue("u_type")!="Account") {
					$objRs = new recordset(null,$objConnection);
					$objRs->queryopen("select custgroup from customergroups where groupname='Guarantors'");
					if (!$objRs->queryfetchrow("NAME")) {
						return raiseError("Customer group Guarantor must be define.");
					}
					$custgroup = $objRs->fields["custgroup"];
					
					$gldata = array();
					$gldata["formatcode"] = $objTable->getudfvalue("u_glacctno");
					if ($objTable->getudfvalue("u_glacctno")=="") {
						$gldata= getBranchGLAcctNo($thisbranch,"U_ARACCTGUARANTOR");
						if ($gldata["formatcode"]=="") {
							return raiseError("G/L Acct [Account Receivable - Guarantor] is not maintained.");
						}				
					}				
					$objCustomers = new customers(null,$objConnection);
					$objCustomers->prepareadd();
					$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
					$objCustomers->custno = $objTable->code;
					$objCustomers->custname = $objTable->name;
					$objCustomers->custclass = "C";
					$objCustomers->currency = $_SESSION["currency"];
					$objCustomers->custgroup = $custgroup;
					$objCustomers->paymentterm = 1;
					$objCustomers->debtpayacctno = $gldata["formatcode"];
					$objCustomers->advanceacctno = $gldata["formatcode"];
					$actionReturn = $objCustomers->add();
				}	
				break;
			case "u_hisdoctors":
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select suppgroup from suppliergroups where groupname='Doctors'");
				if (!$objRs->queryfetchrow("NAME")) {
					return raiseError("Supplier group Doctors must be define.");
				}
				$suppgroup = $objRs->fields["suppgroup"];
				
				$gldata= getBranchGLAcctNo($thisbranch,"U_APACCTDOCTOR");
				if ($gldata["formatcode"]=="") {
					return raiseError("G/L Acct [Account Payable - Doctors] is not maintained.");
				}				
				
				$objSuppliers = new suppliers(null,$objConnection);
				$objSuppliers->prepareadd();
				//$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
				$objSuppliers->suppno = $objTable->code;
				$objSuppliers->suppname = $objTable->name;
				$objSuppliers->currency = $_SESSION["currency"];
				$objSuppliers->suppclass = "I";
				$objSuppliers->paymentterm = 1;
				$objSuppliers->suppgroup = $suppgroup;
				$objSuppliers->taxid = $objTable->getudfvalue("u_tinno");
				$objSuppliers->debtpayacctno = $gldata["formatcode"];
				$objSuppliers->advanceacctno = $gldata["formatcode"];
				$objSuppliers->setudfvalue("u_vatable", $objTable->getudfvalue("u_vatable"));
				$objSuppliers->wtaxcode = $objTable->getudfvalue("u_wtaxcode");
				$objSuppliers->wtaxliable = iif($objTable->getudfvalue("u_wtaxcode")!="",1,0);
				$actionReturn = $objSuppliers->add();
				break;
		}		
		//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventmasterdataschemaGPSHIS");
		return $actionReturn;
	}

	function onAddEventmasterdataschemaGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hisitems":
				$companydata = getcurrentcompanydata('DFLTTAXCODEITEMPU,DFLTTAXCODEEXEMPTITEMPU,DFLTTAXCODEITEMSA,DFLTTAXCODEEXEMPTITEMSA,DFLTCOSTMETHOD,DFLTGLMETHOD');
				if ($companydata["DFLTTAXCODEITEMPU"]=="") return raiseError("Default Tax Code for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMPU"]=="") return raiseError("Default Tax Code Exempt for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEITEMSA"]=="") return raiseError("Default Tax Code for Sales not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMSA"]=="") return raiseError("Default Tax Code Exempt for Sales not defined in General Settings"); 

				$objItems = new items(null,$objConnection);
				if (!$objItems->getbykey($objTable->code)) {
					$objItems->prepareadd();
					$objItems->itemcode = $objTable->code;
				}	
				$objItems->itemdesc = $objTable->name;
				$objItems->barcode = $objTable->getudfvalue("u_barcode");
				$objItems->itemgroup = $objTable->getudfvalue("u_group");
				$objItems->itemclass = $objTable->getudfvalue("u_class");
				$objItems->itemsubclass = $objTable->getudfvalue("u_subclass");
				$objItems->uom = $objTable->getudfvalue("u_uom");
				$objItems->make = $objTable->getudfvalue("u_make");
				$objItems->preferredsuppno = $objTable->getudfvalue("u_preferredsuppno");
				$objItems->uompu = $objTable->getudfvalue("u_uompu");
				$objItems->numperuompu = $objTable->getudfvalue("u_numperuompu");
				$objItems->isinventory = $objTable->getudfvalue("u_isstock");
				switch ($objTable->getudfvalue("u_type")) {
					case "MEDSUP":
						$objItems->itemtype = "PA";
						$objItems->setudfvalue("u_isfixedasset",$objTable->getudfvalue("u_isfixedasset"));
						if ($objTable->getudfvalue("u_isfixedasset")=="1") {
							$objItems->isinventory = 0;
							$objItems->setudfvalue("u_faclass",$objTable->getudfvalue("u_faclass"));
						}	
						$objItems->ispurchaseitem = 1;
						$objItems->issalesitem = 1;
						$objItems->glmethod = "I";//$companydata["DFLTGLMETHOD"];
						break;
					default:	
						$objItems->itemtype = "S";
						$objItems->isinventory = 0;
						$objItems->ispurchaseitem = 0;
						$objItems->issalesitem = 1;
						$objItems->glmethod = "I";//$companydata["DFLTGLMETHOD"];
						break;
				}		
				$objItems->costmethod = $companydata["DFLTCOSTMETHOD"];
				if ($objTable->getudfvalue("u_vatable")=="1") {
					//if ($objTable->getudfvalue("u_group")=="MED") $objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
					//else 
					$objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
					$objItems->taxcodesa = $companydata["DFLTTAXCODEITEMSA"];
				} else {
					$objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
					$objItems->taxcodesa = $companydata["DFLTTAXCODEEXEMPTITEMSA"];
				}	
				$objItems->manageby = $objTable->getudfvalue("u_manageby");
				//$objItems->manageby = "0";
				
				$objItems->isvalid = $objTable->getudfvalue("u_active");
				$objItems->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
				$objItems->setudfvalue("u_soacategory",$objTable->getudfvalue("u_soacategory"));
				if ($objItems->rowstat=="N") $actionReturn = $objItems->add();
				else $actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				break;
		}		
		//if ($actionReturn) $actionReturn = raiseError("onAddEventmasterdataschemaGPSHIS");
		return $actionReturn;
	}
	
	function onBeforeUpdateEventmasterdataschemaGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hisicdchapters":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,1);
				break;
			case "u_hisicdblocks":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,2);
				break;
			case "u_hisicdcodes":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable);
				break;
			case "u_hisitems":
				$companydata = getcurrentcompanydata('DFLTTAXCODEITEMPU,DFLTTAXCODEEXEMPTITEMPU,DFLTTAXCODEITEMSA,DFLTTAXCODEEXEMPTITEMSA,DFLTCOSTMETHOD,DFLTGLMETHOD');
				if ($companydata["DFLTTAXCODEITEMPU"]=="") return raiseError("Default Tax Code for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMPU"]=="") return raiseError("Default Tax Code Exempt for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEITEMSA"]=="") return raiseError("Default Tax Code for Sales not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMSA"]=="") return raiseError("Default Tax Code Exempt for Sales not defined in General Settings"); 
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$objItems->itemdesc = $objTable->name;
					$objItems->barcode = $objTable->getudfvalue("u_barcode");
					$objItems->isvalid = $objTable->getudfvalue("u_active");
					$objItems->itemgroup = $objTable->getudfvalue("u_group");
					$objItems->itemclass = $objTable->getudfvalue("u_class");
					$objItems->itemsubclass = $objTable->getudfvalue("u_subclass");
					$objItems->uom = $objTable->getudfvalue("u_uom");
					$objItems->make = $objTable->getudfvalue("u_make");
					$objItems->preferredsuppno = $objTable->getudfvalue("u_preferredsuppno");
					$objItems->uompu = $objTable->getudfvalue("u_uompu");
					$objItems->numperuompu = $objTable->getudfvalue("u_numperuompu");
					$objItems->isinventory = $objTable->getudfvalue("u_isstock");
					switch ($objTable->getudfvalue("u_type")) {
						case "MEDSUP":
							$objItems->itemtype = "PA";
							$objItems->setudfvalue("u_isfixedasset",$objTable->getudfvalue("u_isfixedasset"));
							if ($objTable->getudfvalue("u_isfixedasset")=="1") {
								$objItems->isinventory = 0;
								$objItems->setudfvalue("u_faclass",$objTable->getudfvalue("u_faclass"));
							}	
							$objItems->ispurchaseitem = 1;
							$objItems->issalesitem = 1;
							$objItems->glmethod = "I";//$companydata["DFLTGLMETHOD"];
							break;
						default:	
							$objItems->itemtype = "S";
							$objItems->isinventory = 0;
							$objItems->ispurchaseitem = 0;
							$objItems->issalesitem = 1;
							$objItems->glmethod = "I";//$companydata["DFLTGLMETHOD"];
							break;
					}		
					$objItems->setudfvalue("u_soacategory",$objTable->getudfvalue("u_soacategory"));
					$objItems->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
					$objItems->costmethod = $companydata["DFLTCOSTMETHOD"];
					if ($objTable->getudfvalue("u_vatable")=="1") {
						//if ($objTable->getudfvalue("u_group")=="MED") $objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
						//else 
						$objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
						$objItems->taxcodesa = $companydata["DFLTTAXCODEITEMSA"];
					} else {
						$objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
						$objItems->taxcodesa = $companydata["DFLTTAXCODEEXEMPTITEMSA"];
					}	
					$objItems->manageby = $objTable->getudfvalue("u_manageby");
					
					//$objItems->itemgroup = $objTable->getudfvalue("u_itemgroup");
					//$objItems->manageby = "2";
					$actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				}	
				break;
			case "u_hismdrps":
				$objRs = new recordset(null,$objConnection);
				$objItemPriceLists = new itempricelists(null,$objConnection);
				$obju_HISItems = new masterdataschema(null,$objConnection,"u_hisitems");
				if ($obju_HISItems->getbykey($objTable->code)) {
					$obju_HISItems->setudfvalue("u_scdiscamount",$objTable->getudfvalue("u_price")*.20);
					$obju_HISItems->setudfvalue("u_salespricing",-1);
					$obju_HISItems->setudfvalue("u_salesmarkup",0);
					$obju_HISItems->setudfvalue("u_salesmarkup2",0);
					if ($actionReturn) {
						$objRs->queryopen("select pricelist from pricelists where bptype='C'");
						while ($objRs->queryfetchrow("NAME")) {
							if (!$objItemPriceLists->getbykey($objTable->code,$objRs->fields["pricelist"])) {
								$objItemPriceLists->prepareadd();
								$objItemPriceLists->itemcode = $objTable->code;
								$objItemPriceLists->pricelist = $objRs->fields["pricelist"];
							} 
							$objItemPriceLists->price = $objTable->getudfvalue("u_price");
							if ($objItemPriceLists->rowstat=="N") $actionReturn = $objItemPriceLists->add();
							else $actionReturn = $objItemPriceLists->update($objItemPriceLists->itemcode,$objItemPriceLists->pricelist,$objItemPriceLists->rcdversion);
							if (!$actionReturn) break;
						}
					}
					if ($actionReturn) $actionReturn = $obju_HISItems->update($obju_HISItems->code,$obju_HISItems->rcdversion);
				} else {
					return raiseError("MDRP Item [".$objTable->code."] does not exists in items.");
				}	
				break;
			case "u_hisroomtypes":
				$companydata = getcurrentcompanydata('DFLTTAXCODEITEMPU,DFLTTAXCODEEXEMPTITEMPU,DFLTTAXCODEITEMSA,DFLTTAXCODEEXEMPTITEMSA,DFLTCOSTMETHOD,DFLTGLMETHOD');
				if ($companydata["DFLTTAXCODEITEMPU"]=="") return raiseError("Default Tax Code for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMPU"]=="") return raiseError("Default Tax Code Exempt for Purchasing not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEITEMSA"]=="") return raiseError("Default Tax Code for Sales not defined in General Settings"); 
				if ($companydata["DFLTTAXCODEEXEMPTITEMSA"]=="") return raiseError("Default Tax Code Exempt for Sales not defined in General Settings"); 

				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$objItems->itemdesc = $objTable->name;
					if ($objTable->getudfvalue("u_vatable")=="1") {
						$objItems->taxcodepu = $companydata["DFLTTAXCODEITEMPU"];
						$objItems->taxcodesa = $companydata["DFLTTAXCODEITEMSA"];
					} else {
						$objItems->taxcodepu = $companydata["DFLTTAXCODEEXEMPTITEMPU"];
						$objItems->taxcodesa = $companydata["DFLTTAXCODEEXEMPTITEMSA"];
					}	
					$objItems->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_profitcenter"));
					//$objItems->isvalid = $objTable->getudfvalue("u_active");
					//$objItems->itemgroup = $objTable->getudfvalue("u_itemgroup");
					//$objItems->manageby = "2";
					$actionReturn = $objItems->update($objItems->itemcode,$objItems->rcdversion);
				}	
				break;
			case "u_hishealthins":
				if ($objTable->getudfvalue("u_hmo")!="2" && $objTable->getudfvalue("u_hmo")!="6" && $objTable->getudfvalue("u_hmo")!="7") {
					$objCustomers = new customers(null,$objConnection);
					$actionReturn = $objCustomers->prepareupdate($objTable->code);
					if ($actionReturn) {
						$objCustomers->custname = $objTable->name;
						$objCustomers->custgroup = $objTable->getudfvalue("u_group");
						$gldata = array();
						$gldata["formatcode"] = $objTable->getudfvalue("u_glacctno");
						if ($objTable->getudfvalue("u_glacctno")=="") {
							$gldata= getBranchGLAcctNo($thisbranch,"U_ARACCTHEALTHINS");
							if ($gldata["formatcode"]=="") {
								return raiseError("G/L Acct [Account Receivable - Health Insurance] is not maintained.");
							}				
						}								
						$objCustomers->debtpayacctno = $gldata["formatcode"];
						$objCustomers->advanceacctno = $gldata["formatcode"];
						$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
					} else return raiseError("Unable to find customer record for health benefit.");	
				}	
				break;
			case "u_hisguarantors":
				if ($objTable->getudfvalue("u_type")!="Account") {
					$objCustomers = new customers(null,$objConnection);
					$actionReturn = $objCustomers->prepareupdate($objTable->code);
					if ($actionReturn) {
						$objCustomers->custname = $objTable->name;
						$gldata = array();
						$gldata["formatcode"] = $objTable->getudfvalue("u_glacctno");
						if ($objTable->getudfvalue("u_glacctno")=="") {
							$gldata= getBranchGLAcctNo($thisbranch,"U_ARACCTHEALTHINS");
							if ($gldata["formatcode"]=="") {
								return raiseError("G/L Acct [Account Receivable - Health Insurance] is not maintained.");
							}				
						}								
						$objCustomers->debtpayacctno = $gldata["formatcode"];
						$objCustomers->advanceacctno = $gldata["formatcode"];
						$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
					}	
				}		
				break;
			case "u_hisdoctors":
				$objSuppliers = new suppliers(null,$objConnection);
				$actionReturn = $objSuppliers->prepareupdate($objTable->code);
					if ($actionReturn) {
						$objSuppliers->suppname = $objTable->name;
						$objSuppliers->taxid = $objTable->getudfvalue("u_tinno");
						$objSuppliers->setudfvalue("u_vatable", $objTable->getudfvalue("u_vatable"));
						$objSuppliers->wtaxcode = $objTable->getudfvalue("u_wtaxcode");
						$objSuppliers->wtaxliable = iif($objTable->getudfvalue("u_wtaxcode")!="",1,0);
						$actionReturn = $objSuppliers->update($objSuppliers->suppno,$objSuppliers->rcdversion);
					}	
					if ($actionReturn && $objTable->fields["U_WTAXCODE"]!=$objTable->getudfvalue("u_wtaxcode") || $objTable->fields["U_VATABLE"]!=$objTable->getudfvalue("u_vatable")) {
						$objRs = new recordset(null,$objConnection);
						$objRs2 = new recordset(null,$objConnection);
						$objRs->queryopen("select a.u_doctorpayablecutoff, ifnull(bx.rate,0) as taxrate, ifnull(cx.rate,0) as wtaxrate from u_hissetup a left join taxes b on b.taxcode='VATPF' left join taxrates bx on bx.taxcode=b.taxcode left join wtaxes c on c.wtaxcode='".$objTable->getudfvalue("u_wtaxcode")."' left join wtaxrates cx on cx.wtaxcode=c.wtaxcode where code='SETUP'");
						if ($objRs->queryfetchrow("NAME")) {
							$cutoffdate = $objRs->fields["u_doctorpayablecutoff"]; 
							$taxrate = $objRs->fields["taxrate"]; 
							$wtaxrate = $objRs->fields["wtaxrate"]; 
							$objRs->queryopen("select docno, totalamount from apinvoices where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and bpcode='".$objTable->code."' and docdate>'$cutoffdate' and othertrxtype='Professional Fees'");
							while ($objRs->queryfetchrow("NAME")) {
								$totalamount = $objRs->fields["totalamount"];
								$vatamount = 0;
								$taxable = $totalamount;
								$vatcode = "VATIX";
								if ($objTable->getudfvalue("u_vatable")=="1") {
									$vatcode = "VATPF";
									$taxable = round($totalamount/(1+($taxrate/100)),2);
									$vatamount = $totalamount - $taxable;
								}
								$totalbefdisc = $totalamount - $vatamount;
								$wtaxamount = $taxable * ($wtaxrate/100);
								$actionReturn = $objRs2->executesql("update apinvoices a left join apinvoicewtaxitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join apinvoiceitems c on c.company=a.company and c.branch=a.branch and c.docid=a.docid set a.wtaxcode='".$objTable->getudfvalue("u_wtaxcode")."', a.totalbefdisc=$totalbefdisc, a.vatamount=$vatamount, a.wtaxamount=$wtaxamount, b.wtaxcode='".$objTable->getudfvalue("u_wtaxcode")."', b.wtaxrate=$wtaxrate, b.taxableamount=$taxable,b.wtaxamount=$wtaxamount,c.vatcode='$vatcode', c.vatrate=$taxrate,c.vatamount=$vatamount where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$objRs->fields["docno"]."'",false);
								if (!$actionReturn) break;
							}
							
							if ($actionReturn) {
								$objRs->queryopen("select docno, totalamount from apcreditmemos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and bpcode='".$objTable->code."' and docdate>'$cutoffdate' and othertrxtype='Professional Fees'");
								while ($objRs->queryfetchrow("NAME")) {
									$totalamount = $objRs->fields["totalamount"];
									$vatamount = 0;
									$taxable = $totalamount;
									$vatcode = "VATIX";
									if ($objTable->getudfvalue("u_vatable")=="1") {
										$vatcode = "VATPF";
										$taxable = round($totalamount/(1+($taxrate/100)),2);
										$vatamount = $totalamount - $taxable;
									}
									$totalbefdisc = $totalamount - $vatamount;
									$wtaxamount = $taxable * ($wtaxrate/100);
									$actionReturn = $objRs2->executesql("update apcreditmemos a left join apcreditmemowtaxitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join apcreditmemoitems c on c.company=a.company and c.branch=a.branch and c.docid=a.docid set a.wtaxcode='".$objTable->getudfvalue("u_wtaxcode")."', a.totalbefdisc=$totalbefdisc, a.vatamount=$vatamount, a.wtaxamount=$wtaxamount, b.wtaxcode='".$objTable->getudfvalue("u_wtaxcode")."', b.wtaxrate=$wtaxrate, b.taxableamount=$taxable,b.wtaxamount=$wtaxamount,c.vatcode='$vatcode', c.vatrate=$taxrate,c.vatamount=$vatamount where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docno='".$objRs->fields["docno"]."'",false);
									if (!$actionReturn) break;
								}
							}
						} else return raiseError("Unable to retrieve doctor's payable cut-off.");
					}
				break;
		}		
		//if ($actionReturn)  $actionReturn = raiseError("onBeforeUpdateEventmasterdataschemaGPSHIS");
		return $actionReturn;
	}

	function onBeforeDeleteEventmasterdataschemaGPSHIS($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_hisicdchapters":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,1,true);
				break;
			case "u_hisicdblocks":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,2,true);
				break;
			case "u_hisicdcodes":
				$actionReturn = onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,null,true);
				break;
			case "u_hisitems":
				$objItems = new items(null,$objConnection);
				$actionReturn = $objItems->prepareupdate($objTable->code);
				if ($actionReturn) {
					$actionReturn = $objItems->delete();
				}	
				break;
			case "u_hismdrps":
				$objRs = new recordset(null,$objConnection);
				$objItemPriceLists = new itempricelists(null,$objConnection);
				$obju_HISItems = new masterdataschema(null,$objConnection,"u_hisitems");
				if ($obju_HISItems->getbykey($objTable->code)) {
					$obju_HISItems->setudfvalue("u_salespricing",0);
					$obju_HISItems->setudfvalue("u_salesmarkup",0);
					$obju_HISItems->setudfvalue("u_scdiscamount",0);
					if ($actionReturn) {
						$objRs->queryopen("select pricelist from pricelists where bptype='C'");
						while ($objRs->queryfetchrow("NAME")) {
							if (!$objItemPriceLists->getbykey($objTable->code,$objRs->fields["pricelist"])) {
								$actionReturn = $objItemPriceLists->delete();
							}	
							if (!$actionReturn) break;
						}
					}
					if ($actionReturn) $actionReturn = $obju_HISItems->update($obju_HISItems->code,$obju_HISItems->rcdversion);
				} else {
					return raiseError("MDRP Item [".$objTable->code."] does not exists in items.");
				}	
				break;
		}		
		return $actionReturn;
	}
	
	function onCustomEventmasterdataschemaUpdateHISICDGPSHIS($objTable,$level,$delete=false) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		$obju_HISICDs = new masterdataschema(null,$objConnection,"u_hisicds");

		
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


?>
