<?php

require_once("./utils/taxes.php"); 
require_once("./utils/wtaxes.php"); 

function onBeforeAddEventdocumentschema_brGPSFixedAsset($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_famanacquis":
		case "u_faacquis":
			$objRs = new recordset(null,$objConnection);
			$obju_FA = new masterdataschema(null,$objConnection,"u_fa");
			$obju_FAChilds = new masterdatalinesschema(null,$objConnection,"u_fachilds");
			$obju_FAAssignees = new masterdatalinesschema(null,$objConnection,"u_faassignees");
			$obju_FAClass = new masterdataschema(null,$objConnection,"u_faclass");
			$obju_FADepreDepts = new masterdatalinesschema(null,$objConnection,"u_fadepredepts");
			if ($objTable->dbtable=="u_famanacquis") {
				$obju_FAManAcquiItems = new documentlinesschema_br(null,$objConnection,"u_famanacquiitems");
			} else {
				$obju_FAManAcquiItems = new documentlinesschema_br(null,$objConnection,"u_faacquiitems");
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
			if ($objTable->dbtable=="u_famanacquis") $objJvHdr->remarks = "Fixed Asset Manual Acquisition";
			else $objJvHdr->remarks = "Fixed Asset Acquisition";
			
			$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
			
			$objTable->setudfvalue("u_jvno",$objJvHdr->docno);			
			
			if ($objTable->dbtable=="u_famanacquis") {
				$obju_FAManAcquiItems->queryopen($obju_FAManAcquiItems->selectstring() . " and DOCID='$objTable->docid'");
			} else {
				$obju_FAManAcquiItems->queryopen($obju_FAManAcquiItems->selectstring() . " and DOCID='$objTable->docid' and u_selected=1");
			}	
			while ($obju_FAManAcquiItems->queryfetchrow()) {
				$assettype = "F";
				if ($objTable->dbtable=="u_famanacquis") {
					if ($obju_FAManAcquiItems->getudfvalue("u_itemcode")!="") {
						$objRs->queryopen("select U_FACLASS FROM ITEMS WHERE ITEMCODE='".$obju_FAManAcquiItems->getudfvalue("u_itemcode")."'");
						if (!$objRs->queryfetchrow("NAME")) {
							return raiseError("Unable to find item [".$obju_FAManAcquiItems->getudfvalue("u_itemcode")."].");
						}
						if (!$obju_FAClass->getbykey($objRs->fields["U_FACLASS"])) {
							return raiseError("Unable to find asset class [".$objRs->fields["U_FACLASS"]."].");
						}
					} else {
						if (!$obju_FAClass->getbykey($objTable->getudfvalue("u_faclass"))) {
							return raiseError("Unable to find asset class [".$objRs->fields["U_FACLASS"]."].");
						}
					}	
				} else {
					$assettype = $obju_FAManAcquiItems->getudfvalue("u_assettype");
					if ($assettype=="F") {
						if (!$obju_FAClass->getbykey($obju_FAManAcquiItems->getudfvalue("u_assetcode"))) {
							return raiseError("Unable to find asset class [".$obju_FAManAcquiItems->getudfvalue("u_assetcode")."].");
						}
					 } else	{
						if (!$obju_FA->getbykey($obju_FAManAcquiItems->getudfvalue("u_assetcode"))) {
							return raiseError("Unable to find asset master [".$obju_FAManAcquiItems->getudfvalue("u_assetcode")."].");
						}
						if (!$obju_FAClass->getbykey($obju_FA->getudfvalue("u_faclass"))) {
							return raiseError("Unable to find asset class [".$obju_FAManAcquiItems->getudfvalue("u_assetcode")."].");
						}
					 }
				}			
				if ($assettype=="F") {	
					$obju_FA->prepareadd();
					$obju_FAClass->setudfvalue("u_lastseries",intval($obju_FAClass->getudfvalue("u_lastseries")) + 1);
					$obju_FA->code = $obju_FAClass->getudfvalue("u_prefixseries");
					$obju_FA->code = str_replace("{FA}",$obju_FAClass->code,$obju_FA->code);
					$obju_FA->code = str_replace("{FAC}",$obju_FAClass->code,$obju_FA->code);
					$obju_FA->code = str_replace("{FAN}",$obju_FAClass->name,$obju_FA->code);
					if ($obju_FAManAcquiItems->getudfvalue("u_acquidate")!="") {
						$obju_FA->code = str_replace("[Y]", date('Y',strtotime($obju_FAManAcquiItems->getudfvalue("u_acquidate"))), $obju_FA->code);
						$obju_FA->code = str_replace("[y]", date('y',strtotime($obju_FAManAcquiItems->getudfvalue("u_acquidate"))), $obju_FA->code);
						$obju_FA->code = str_replace("[m]", date('m',strtotime($obju_FAManAcquiItems->getudfvalue("u_acquidate"))), $obju_FA->code);
					} else {
						$obju_FA->code = str_replace("[Y]", date('Y',strtotime($objTable->getudfvalue("u_docdate"))), $obju_FA->code);
						$obju_FA->code = str_replace("[y]", date('y',strtotime($objTable->getudfvalue("u_docdate"))), $obju_FA->code);
						$obju_FA->code = str_replace("[m]", date('m',strtotime($objTable->getudfvalue("u_docdate"))), $obju_FA->code);
					}	
					$obju_FA->code .= str_pad( intval($obju_FAClass->getudfvalue("u_lastseries")), $obju_FAClass->getudfvalue("u_numseries"), "0", STR_PAD_LEFT);
					$obju_FA->name = $obju_FAClass->name;
					$obju_FA->setudfvalue("u_itemcode",$obju_FAManAcquiItems->getudfvalue("u_itemcode"));
					$obju_FA->setudfvalue("u_itemdesc",$obju_FAManAcquiItems->getudfvalue("u_itemdesc"));
					$obju_FA->setudfvalue("u_faclass",$obju_FAClass->code);
					
					$obju_FAManAcquiItems->setudfvalue("u_facode",$obju_FA->code);
					
					if ($objTable->dbtable=="u_famanacquis") {
						$obju_FA->setudfvalue("u_srcdoc",$objTable->docno);
						$obju_FA->setudfvalue("u_srcname","Manual Acquisition");
						if ($obju_FAManAcquiItems->getudfvalue("u_srcdoc")!="") $obju_FA->setudfvalue("u_srcdoc",$obju_FAManAcquiItems->getudfvalue("u_srcdoc"));
						if ($obju_FAManAcquiItems->getudfvalue("u_srcname")!="") $obju_FA->setudfvalue("u_srcname",$obju_FAManAcquiItems->getudfvalue("u_srcname"));
					} else {
						$obju_FA->setudfvalue("u_srcdoc",$obju_FAManAcquiItems->getudfvalue("u_srcdoc"));
						$obju_FA->setudfvalue("u_srcname",$obju_FAManAcquiItems->getudfvalue("u_srcname"));
					}	
					$obju_FA->setudfvalue("u_acquidate",$objTable->getudfvalue("u_docdate"));
					if ($obju_FAManAcquiItems->getudfvalue("u_acquidate")!="") $obju_FA->setudfvalue("u_acquidate",$obju_FAManAcquiItems->getudfvalue("u_acquidate"));
					//$obju_FA->setudfvalue("u_depredate",$objTable->getudfvalue("u_docdate"));
					$obju_FA->setudfvalue("u_branch",$obju_FAManAcquiItems->getudfvalue("u_branch"));
					$obju_FA->setudfvalue("u_department",$obju_FAManAcquiItems->getudfvalue("u_department"));
					$obju_FA->setudfvalue("u_empid",$obju_FAManAcquiItems->getudfvalue("u_empid"));
					$obju_FA->setudfvalue("u_empname",$obju_FAManAcquiItems->getudfvalue("u_empname"));
					$obju_FA->setudfvalue("u_cost",$obju_FAManAcquiItems->getudfvalue("u_cost"));
					$obju_FA->setudfvalue("u_accumdepre",$obju_FAManAcquiItems->getudfvalue("u_accumdepre"));
					$obju_FA->setudfvalue("u_salvagevalue",$obju_FAManAcquiItems->getudfvalue("u_salvagevalue"));
					$obju_FA->setudfvalue("u_bookvalue",$obju_FA->getudfvalue("u_cost") - $obju_FA->getudfvalue("u_accumdepre") - $obju_FA->getudfvalue("u_salvagevalue"));
					if (bcsub($obju_FA->getudfvalue("u_bookvalue"),0,14)<0) {
						return raiseError("Asset [".$obju_FA->getudfvalue("u_itemcode")."/".$obju_FA->getudfvalue("u_itemdesc")."] cannot have negative bookvalue [".bcsub($obju_FA->getudfvalue("u_bookvalue"),0,14)."].");
					}
					
					$obju_FA->setudfvalue("u_profitcenter",$obju_FAManAcquiItems->getudfvalue("u_profitcenter"));
					$profitcenters = explode("/",$obju_FAManAcquiItems->getudfvalue("u_profitcenter"));
					if (count($profitcenters)>1) {
						$avgweight = round(100/count($profitcenters),2);
						for ($ctr=0; $ctr<count($profitcenters); $ctr++) {
							$obju_FADepreDepts->prepareadd();
							$obju_FADepreDepts->code = $obju_FA->code;
							$obju_FADepreDepts->lineid = getNextIdByBranch("u_fadepredepts",$objConnection);
							$obju_FADepreDepts->setudfvalue("u_profitcenter",$profitcenters[$ctr]);
							if ($ctr==(count($profitcenters)-1)) $obju_FADepreDepts->setudfvalue("u_weightperc",100-($avgweight*$ctr)); 
							else $obju_FADepreDepts->setudfvalue("u_weightperc",$avgweight); 
							$obju_FADepreDepts->privatedata["header"] = $obju_FA;
							$actionReturn = $obju_FADepreDepts->add();
							if (!$actionReturn) break;
						}				
						$obju_FA->setudfvalue("u_deptweightperc",100); 
					}	
					
					$obju_FA->setudfvalue("u_projcode",$obju_FAManAcquiItems->getudfvalue("u_projcode"));
					$obju_FA->setudfvalue("u_serialno",$obju_FAManAcquiItems->getudfvalue("u_serialno"));
					$obju_FA->setudfvalue("u_mfrserialno",$obju_FAManAcquiItems->getudfvalue("u_mfrserialno"));
					$obju_FA->setudfvalue("u_property1",$obju_FAManAcquiItems->getudfvalue("u_property1"));
					$obju_FA->setudfvalue("u_property2",$obju_FAManAcquiItems->getudfvalue("u_property2"));
					$obju_FA->setudfvalue("u_property3",$obju_FAManAcquiItems->getudfvalue("u_property3"));
					$obju_FA->setudfvalue("u_property4",$obju_FAManAcquiItems->getudfvalue("u_property4"));
					$obju_FA->setudfvalue("u_property5",$obju_FAManAcquiItems->getudfvalue("u_property5"));
					$obju_FA->setudfvalue("u_property6",$obju_FAManAcquiItems->getudfvalue("u_property6"));
					$obju_FA->setudfvalue("u_property7",$obju_FAManAcquiItems->getudfvalue("u_property7"));
					$obju_FA->setudfvalue("u_property8",$obju_FAManAcquiItems->getudfvalue("u_property8"));
					$obju_FA->setudfvalue("u_property9",$obju_FAManAcquiItems->getudfvalue("u_property9"));
					$obju_FA->setudfvalue("u_property10",$obju_FAManAcquiItems->getudfvalue("u_property10"));
					$obju_FA->setudfvalue("u_depreacct",$obju_FAClass->getudfvalue("u_depreacct"));
					$obju_FA->setudfvalue("u_accumdepreacct",$obju_FAClass->getudfvalue("u_accumdepreacct"));
					$obju_FA->setudfvalue("u_life",$obju_FAManAcquiItems->getudfvalue("u_life"));
					if ($objTable->dbtable=="u_famanacquis" && $obju_FAManAcquiItems->getudfvalue("u_depredate")!="") {
						$obju_FA->setudfvalue("u_startdate",$obju_FAManAcquiItems->getudfvalue("u_depredate"));
						$obju_FA->setudfvalue("u_enddate",dateadd("d",-1,dateadd("m",$obju_FAManAcquiItems->getudfvalue("u_life"),$obju_FAManAcquiItems->getudfvalue("u_depredate"))));
						/*if ($obju_FAManAcquiItems->getudfvalue("u_depredate")<$objTable->getudfvalue("u_docdate")) {
							$age = datedifference("m", $obju_FAManAcquiItems->getudfvalue("u_depredate"), $objTable->getudfvalue("u_docdate")) ;
							if ($obju_FAManAcquiItems->getudfvalue("u_life")>$age) {
								$obju_FA->setudfvalue("u_depredate",dateadd("m",$age+1,$obju_FAManAcquiItems->getudfvalue("u_depredate")));
								$obju_FA->setudfvalue("u_remainlife",$obju_FAManAcquiItems->getudfvalue("u_life")-$age-1);							
							}
							//var_dump(array($obju_FAManAcquiItems->getudfvalue("u_depredate"),$objTable->getudfvalue("u_docdate"),$age));
							//var_dump(array($obju_FA->getudfvalue("u_depredate"),$obju_FA->getudfvalue("u_remainlife")));
							//var_dump(array(dateadd("m",$obju_FA->getudfvalue("u_remainlife")-1,$obju_FA->getudfvalue("u_depredate"))));
							//return raiseError("1");
						} else {*/
							$obju_FA->setudfvalue("u_depredate",$obju_FAManAcquiItems->getudfvalue("u_depredate"));
							$obju_FA->setudfvalue("u_remainlife",$obju_FAManAcquiItems->getudfvalue("u_life"));
						/*}*/	
					}
					
					$obju_FAChilds->prepareadd();
					$obju_FAChilds->code = $obju_FA->code;
					$obju_FAChilds->setudfvalue("u_itemcode",$obju_FA->getudfvalue("u_itemcode"));
					$obju_FAChilds->setudfvalue("u_itemdesc",$obju_FA->getudfvalue("u_itemdesc"));
					$obju_FAChilds->setudfvalue("u_srcdoc",$obju_FA->getudfvalue("u_srcdoc"));
					$obju_FAChilds->setudfvalue("u_srcname",$obju_FA->getudfvalue("u_srcname"));
					$obju_FAChilds->setudfvalue("u_acquidate",$obju_FA->getudfvalue("u_acquidate"));
					if ($objTable->dbtable=="u_famanacquis") {
						$obju_FAChilds->setudfvalue("u_amount",$obju_FA->getudfvalue("u_cost"));
					} else {
						$obju_FAChilds->setudfvalue("u_amount",$obju_FAManAcquiItems->getudfvalue("u_amount"));
					}	
					$obju_FAChilds->setudfvalue("u_cost",$obju_FA->getudfvalue("u_cost"));
					$obju_FAChilds->setudfvalue("u_serialno",$obju_FA->getudfvalue("u_serialno"));
					$obju_FAChilds->setudfvalue("u_mfrserialno",$obju_FA->getudfvalue("u_mfrserialno"));
					$obju_FAChilds->setudfvalue("u_property1",$obju_FA->getudfvalue("u_property1"));
					$obju_FAChilds->setudfvalue("u_property2",$obju_FA->getudfvalue("u_property2"));
					$obju_FAChilds->setudfvalue("u_property3",$obju_FA->getudfvalue("u_property3"));
					$obju_FAChilds->setudfvalue("u_property4",$obju_FA->getudfvalue("u_property4"));
					$obju_FAChilds->setudfvalue("u_property5",$obju_FA->getudfvalue("u_property5"));
					$obju_FAChilds->setudfvalue("u_property6",$obju_FA->getudfvalue("u_property6"));
					$obju_FAChilds->setudfvalue("u_property7",$obju_FA->getudfvalue("u_property7"));
					$obju_FAChilds->setudfvalue("u_property8",$obju_FA->getudfvalue("u_property8"));
					$obju_FAChilds->setudfvalue("u_property9",$obju_FA->getudfvalue("u_property9"));
					$obju_FAChilds->setudfvalue("u_property10",$obju_FA->getudfvalue("u_property10"));
					$obju_FAChilds->privatedata["header"] = $obju_FA;
					
					$obju_FAAssignees->prepareadd();
					$obju_FAAssignees->code = $obju_FA->code;
					$obju_FAAssignees->setudfvalue("u_assigneddate",$obju_FA->getudfvalue("u_acquidate"));
					$obju_FAAssignees->setudfvalue("u_branch",$obju_FA->getudfvalue("u_branch"));
					$obju_FAAssignees->setudfvalue("u_department",$obju_FA->getudfvalue("u_department"));
					$obju_FAAssignees->setudfvalue("u_empid",$obju_FA->getudfvalue("u_empid"));
					$obju_FAAssignees->setudfvalue("u_empname",$obju_FA->getudfvalue("u_empname"));
					$obju_FAAssignees->setudfvalue("u_profitcenter",$obju_FA->getudfvalue("u_profitcenter"));
					$obju_FAAssignees->setudfvalue("u_projcode",$obju_FA->getudfvalue("u_projcode"));
					$obju_FAAssignees->privatedata["header"] = $obju_FA;
					//var_dump($obju_FA->code);
					if ($actionReturn) $actionReturn = $obju_FAClass->update($obju_FAClass->code,$obju_FAClass->rcdversion);
					if ($actionReturn) $actionReturn = $obju_FAAssignees->add();
					if ($actionReturn) $actionReturn = $obju_FAChilds->add();
					
					if ($actionReturn) $actionReturn = $obju_FA->add();
					//var_dump($actionReturn);
					//if ($actionReturn) $actionReturn = raiseError('on add u_fa');
				} else {
					$obju_FA->setudfvalue("u_cost",$obju_FA->getudfvalue("u_cost") + $obju_FAManAcquiItems->getudfvalue("u_cost"));
					$obju_FA->setudfvalue("u_bookvalue",$obju_FA->getudfvalue("u_bookvalue") + $obju_FAManAcquiItems->getudfvalue("u_cost"));
					
					$obju_FAChilds->prepareadd();
					$obju_FAChilds->code = $obju_FA->code;
					$obju_FAChilds->lineid = getNextIdByBranch("u_fachilds",$objConnection);
					$obju_FAChilds->setudfvalue("u_itemcode",$obju_FAManAcquiItems->getudfvalue("u_itemcode"));
					$obju_FAChilds->setudfvalue("u_itemdesc",$obju_FAManAcquiItems->getudfvalue("u_itemdesc"));
					$obju_FAChilds->setudfvalue("u_srcdoc",$obju_FAManAcquiItems->getudfvalue("u_srcdoc"));
					$obju_FAChilds->setudfvalue("u_srcname",$obju_FAManAcquiItems->getudfvalue("u_srcname"));
					$obju_FAChilds->setudfvalue("u_acquidate",$obju_FAManAcquiItems->getudfvalue("u_acquidate"));
					$obju_FAChilds->setudfvalue("u_amount",$obju_FAManAcquiItems->getudfvalue("u_amount"));
					$obju_FAChilds->setudfvalue("u_cost",$obju_FAManAcquiItems->getudfvalue("u_cost"));
					$obju_FAChilds->setudfvalue("u_serialno",$obju_FAManAcquiItems->getudfvalue("u_serialno"));
					$obju_FAChilds->setudfvalue("u_mfrserialno",$obju_FAManAcquiItems->getudfvalue("u_mfrserialno"));
					$obju_FAChilds->setudfvalue("u_property1",$obju_FAManAcquiItems->getudfvalue("u_property1"));
					$obju_FAChilds->setudfvalue("u_property2",$obju_FAManAcquiItems->getudfvalue("u_property2"));
					$obju_FAChilds->setudfvalue("u_property3",$obju_FAManAcquiItems->getudfvalue("u_property3"));
					$obju_FAChilds->setudfvalue("u_property4",$obju_FAManAcquiItems->getudfvalue("u_property4"));
					$obju_FAChilds->setudfvalue("u_property5",$obju_FAManAcquiItems->getudfvalue("u_property5"));
					$obju_FAChilds->setudfvalue("u_property6",$obju_FAManAcquiItems->getudfvalue("u_property6"));
					$obju_FAChilds->setudfvalue("u_property7",$obju_FAManAcquiItems->getudfvalue("u_property7"));
					$obju_FAChilds->setudfvalue("u_property8",$obju_FAManAcquiItems->getudfvalue("u_property8"));
					$obju_FAChilds->setudfvalue("u_property9",$obju_FAManAcquiItems->getudfvalue("u_property9"));
					$obju_FAChilds->setudfvalue("u_property10",$obju_FAManAcquiItems->getudfvalue("u_property10"));
					$obju_FAChilds->privatedata["header"] = $obju_FA;
					
					$obju_FAManAcquiItems->setudfvalue("u_facode",$obju_FA->code);
										
					if ($actionReturn) $actionReturn = $obju_FAChilds->add();
					
					if ($actionReturn) $actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
				}				
				if ($actionReturn) {
					if ($obju_FAClass->getudfvalue("u_purchacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Purchase Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FAManAcquiItems->getudfvalue("u_branch");
					//$objJvDtl->department = $obju_FAManAcquiItems->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FAManAcquiItems->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FAManAcquiItems->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_purchacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_purchacct"));
					$objJvDtl->debit = $obju_FAManAcquiItems->getudfvalue("u_cost");
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				if ($actionReturn) {
					$glacctno = array("formatcode"=>"","acctname"=>"");
					$glacctno["formatcode"] = $obju_FAManAcquiItems->getudfvalue("u_contraglacctno");
					if ($glacctno["formatcode"]=="") {
						switch ($obju_FAChilds->getudfvalue("u_srcname")) {
							case "Goods Issue":
								$glacctno = getItemGLAcctNo($_SESSION["branch"],$obju_FAManAcquiItems->getudfvalue("u_itemcode"),$obju_FAManAcquiItems->getudfvalue("u_srcwhs"),"INVOFFSETDECACCT");
								break;
							case "Goods Receipt PO":
								$glacctno = getItemGLAcctNo($_SESSION["branch"],$obju_FAManAcquiItems->getudfvalue("u_itemcode"),$obju_FAManAcquiItems->getudfvalue("u_srcwhs"),"INVENTORYACCT");
								break;
							default:
								$glacctno = getItemGLAcctNo($_SESSION["branch"],$obju_FAManAcquiItems->getudfvalue("u_itemcode"),$obju_FAManAcquiItems->getudfvalue("u_srcwhs"),"EXPENSEACCT");
								break;
						}
					} else $glacctno["acctname"] = getchartofaccountname($glacctno["formatcode"]);
					if ($glacctno["formatcode"]=="") return raiseError("Fixed Asset [".$obju_FAClass->code."] Contra Account must be entered. No default setup maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FAManAcquiItems->getudfvalue("u_srcbr");
					//$objJvDtl->department = $obju_FAManAcquiItems->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FAManAcquiItems->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FAManAcquiItems->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $glacctno["formatcode"];
					$objJvDtl->itemname = $glacctno["acctname"];
					$objJvDtl->credit = $obju_FAManAcquiItems->getudfvalue("u_cost");
					$objJvDtl->grossamount = $objJvDtl->credit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				if ($actionReturn && $objTable->dbtable=="u_faacquis") {
//					$objRs->shareddatatype = "BRANCH";
//					$objRs->shareddatacode = $obju_FAManAcquiItems->getudfvalue("u_srcbr");
//					$actionReturn = $objRs->executesql("update u_faxmtal set u_status='A' where company='".$_SESSION["company"]."' and branch='".$obju_FAManAcquiItems->getudfvalue("u_srcbr")."' and lineid='".$obju_FAManAcquiItems->getudfvalue("u_xmtallineid")."'",true);
				}	
				if ($actionReturn) {
					$actionReturn = $obju_FAManAcquiItems->update($obju_FAManAcquiItems->docid,$obju_FAManAcquiItems->lineid,$obju_FAManAcquiItems->rcdversion);
				}
				if (!$actionReturn) break;
			}
			if ($actionReturn) $actionReturn = $objJvHdr->add();

			break;
		case "u_fachnguselife":
			$obju_FA = new masterdataschema(null,$objConnection,"u_fa");
			if ($obju_FA->getbykey($objTable->getudfvalue("u_facode"))) {
				$obju_FA->setudfvalue("u_depredate",$objTable->getudfvalue("u_docdate"));
				$obju_FA->setudfvalue("u_remainlife",$objTable->getudfvalue("u_newlifem"));
				$actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
			} else $actionReturn = raiseError("Unable to retrieve Asset record [".$objTable->getudfvalue("u_facode")."]."); 
			
			break;
		case "u_fatransfers":
			$objRs = new recordset(null,$objConnection);
			$obju_FA = new masterdataschema(null,$objConnection,"u_fa");			
			$obju_FAClass = new masterdataschema(null,$objConnection,"u_faclass");
			$obju_FAAssignees = new masterdatalinesschema(null,$objConnection,"u_faassignees");
			$objJvHdr = new journalvouchers(null,$objConnection);
			$objJvDtl = new journalvoucheritems(null,$objConnection);
			if (!$obju_FAClass->getbykey($objTable->getudfvalue("u_faclass"))) {
				return raiseError("Unable to find asset class [".$objTable->getudfvalue("u_faclass")."].");
			}
				
			if ($obju_FA->getbykey($objTable->getudfvalue("u_facode"))) {
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
				$objJvHdr->remarks = "Fixed Asset Transfer";
				
				$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
				
				if ($actionReturn) {
					if ($obju_FAClass->getudfvalue("u_purchacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Purchase Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $objTable->getudfvalue("u_tftobranch");
					//$objJvDtl->department = $objTable->getudfvalue("u_tftodepartment");
					//$objJvDtl->profitcenter = $objTable->getudfvalue("u_tftoprofitcenter");
					//$objJvDtl->projcode = $objTable->getudfvalue("u_tftoprojcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_purchacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_purchacct"));
					$objJvDtl->debit = $objTable->getudfvalue("u_bookvalue");
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				if ($actionReturn) {
					if ($obju_FAClass->getudfvalue("u_purchacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Purchase Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $objTable->getudfvalue("u_branch");
					//$objJvDtl->department = $objTable->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $objTable->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $objTable->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_purchacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_purchacct"));
					$objJvDtl->credit = $objTable->getudfvalue("u_bookvalue");
					$objJvDtl->grossamount = $objJvDtl->credit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				
				if ($actionReturn) $objJvHdr->add();
				
				if ($actionReturn) {
					$objTable->setudfvalue("u_jvno",$objJvHdr->docno);			
				
					$obju_FA->setudfvalue("u_branch",$objTable->getudfvalue("u_tftobranch"));
					$obju_FA->setudfvalue("u_department",$objTable->getudfvalue("u_tftodepartment"));
					$obju_FA->setudfvalue("u_empid",$objTable->getudfvalue("u_tftoempid"));
					$obju_FA->setudfvalue("u_empname",$objTable->getudfvalue("u_tftoempname"));
					$obju_FA->setudfvalue("u_profitcenter",$objTable->getudfvalue("u_tftoprofitcenter"));
					$obju_FA->setudfvalue("u_projcode",$objTable->getudfvalue("u_tftoprojcode"));
					
					$obju_FAAssignees->prepareadd();
					$obju_FAAssignees->code = $obju_FA->code;
					$obju_FAAssignees->lineid = getNextIdByBranch("u_faasignees",$objConnection);
					$obju_FAAssignees->setudfvalue("u_assigneddate",$objTable->getudfvalue("u_docdate"));
					$obju_FAAssignees->setudfvalue("u_branch",$obju_FA->getudfvalue("u_branch"));
					$obju_FAAssignees->setudfvalue("u_department",$obju_FA->getudfvalue("u_department"));
					$obju_FAAssignees->setudfvalue("u_empid",$obju_FA->getudfvalue("u_empid"));
					$obju_FAAssignees->setudfvalue("u_empname",$obju_FA->getudfvalue("u_empname"));
					$obju_FAAssignees->setudfvalue("u_profitcenter",$obju_FA->getudfvalue("u_profitcenter"));
					$obju_FAAssignees->setudfvalue("u_projcode",$obju_FA->getudfvalue("u_projcode"));
					$obju_FAAssignees->privatedata["header"] = $obju_FA;
					
					if ($actionReturn) $actionReturn = $obju_FAAssignees->add();
					
					if ($actionReturn) $actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
				}	
			} else $actionReturn = raiseError("Unable to retrieve Asset record [".$objTable->getudfvalue("u_facode")."]."); 
			break;
		case "u_faretires":
			$objRs = new recordset(null,$objConnection);
			$obju_FA = new masterdataschema(null,$objConnection,"u_fa");			
			$obju_FAClass = new masterdataschema(null,$objConnection,"u_faclass");
			$objJvHdr = new journalvouchers(null,$objConnection);
			$objJvDtl = new journalvoucheritems(null,$objConnection);
			if (!$obju_FAClass->getbykey($objTable->getudfvalue("u_faclass"))) {
				return raiseError("Unable to find asset class [".$objTable->getudfvalue("u_faclass")."].");
			}
				
			if ($obju_FA->getbykey($objTable->getudfvalue("u_facode"))) {
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
				$objJvHdr->remarks = "Fixed Asset Retirement";
				
				$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
				
				if ($actionReturn && $obju_FA->getudfvalue("u_accumdepre")>0) {
					if ($obju_FAClass->getudfvalue("u_accumdepreacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Accumulated Depreciation Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
					//$objJvDtl->department = $obju_FA->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_accumdepreacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_accumdepreacct"));
					$objJvDtl->debit = $obju_FA->getudfvalue("u_accumdepre");
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				if ($actionReturn && ($obju_FA->getudfvalue("u_cost") - $obju_FA->getudfvalue("u_accumdepre")) >0) {
					if ($obju_FAClass->getudfvalue("u_lossonretireacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Loss on Retirement Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FAClass->getudfvalue("u_branch");
					//$objJvDtl->department = $obju_FAClass->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FAClass->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FAClass->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_lossonretireacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_lossonretireacct"));
					$objJvDtl->debit = ($obju_FA->getudfvalue("u_cost") - $obju_FA->getudfvalue("u_accumdepre"));
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				if ($actionReturn) {
					if ($obju_FAClass->getudfvalue("u_purchacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Purchase Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
					//$objJvDtl->department = $obju_FA->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_purchacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_purchacct"));
					$objJvDtl->credit = $obju_FA->getudfvalue("u_cost");
					$objJvDtl->grossamount = $objJvDtl->credit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				
				if ($actionReturn) $actionReturn = $objJvHdr->add();
				
				if ($actionReturn) {
					$objTable->setudfvalue("u_jvno",$objJvHdr->docno);			
					$obju_FA->setudfvalue("u_accumdepre",0);
					$obju_FA->setudfvalue("u_bookvalue",0);
					$obju_FA->setudfvalue("u_salvagevalue",0);
					$actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
				}	
			} else $actionReturn = raiseError("Unable to retrieve Asset record [".$objTable->getudfvalue("u_facode")."]."); 
			break;			
		case "u_fasales":
			$objRs = new recordset(null,$objConnection);
			$obju_FA = new masterdataschema(null,$objConnection,"u_fa");			
			$obju_FAClass = new masterdataschema(null,$objConnection,"u_faclass");
			$objJvHdr = new journalvouchers(null,$objConnection);
			$objJvDtl = new journalvoucheritems(null,$objConnection);
			if (!$obju_FAClass->getbykey($objTable->getudfvalue("u_faclass"))) {
				return raiseError("Unable to find asset class [".$objTable->getudfvalue("u_faclass")."].");
			}
				
			if ($obju_FA->getbykey($objTable->getudfvalue("u_facode"))) {
			
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
				$objJvHdr->remarks = "Fixed Asset Sales";
				
				$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
				
				if ($actionReturn) {
					$taxrate = 0;
					$wtaxamount = 0;
					$vatamount = 0;
					if ($objTable->getudfvalue("u_taxcode")!="") {
						$taxrate = gettaxrate($objTable->getudfvalue("u_taxcode"),$objTable->getudfvalue("u_docdate"));
						$netsales = roundAmount($objTable->getudfvalue("u_price")/(1+($taxrate/100)));
						$vatamount = $objTable->getudfvalue("u_price") - $netsales;
						$taxglacctno = getTaxGLAcctNo($obju_FA->getudfvalue("u_branch"),$objTable->getudfvalue("u_taxcode"));
					}
					if ($objTable->getudfvalue("u_wtaxcode")!="") {
						$wtaxdata = getwtaxdata($objTable->getudfvalue("u_wtaxcode"),"WTAXCATEGORY,WTAXTYPE,WTAXBASEAMOUNTPERC,WTAXDESC");
						$wtaxdata["RATE"] = getwtaxrate($objTable->getudfvalue("u_wtaxcode"),$objTable->getudfvalue("u_docdate"));				
						
						if ($wtaxdata["WTAXTYPE"]=="N") {
							$taxableamount = $netsales;
							$wtaxamount = round(($wtaxdata["RATE"]/100)*($netsales * ($wtaxdata["WTAXBASEAMOUNTPERC"]/100)),2);
						} else {
							$taxableamount = $objTable->getudfvalue("u_price");
							$wtaxamount = round(($wtaxdata["RATE"]/100)*($objTable->getudfvalue("u_price") * ($wtaxdata["WTAXBASEAMOUNTPERC"]/100)),2);
						}	

						$wtaxglacctno = getWTaxGLAcctNo($obju_FA->getudfvalue("u_branch"),$objTable->getudfvalue("u_wtaxcode"));
					}	

					
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->lineno = 1;
					$objJvDtl->itemtype = "C";
					$objJvDtl->itemno = $objTable->getudfvalue("u_bpcode");
					$objJvDtl->itemname = $objTable->getudfvalue("u_bpname");
					$objJvDtl->debit = $objTable->getudfvalue("u_price") - $wtaxamount;
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();

					
					if ($actionReturn && $wtaxamount>0) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
						$objJvDtl->department = $obju_FA->getudfvalue("u_department");
						$objJvDtl->itemtype = "A";
						$objJvDtl->itemno = $wtaxglacctno["formatcode"];
						$objJvDtl->itemname = $wtaxglacctno["acctname"];
						$objJvDtl->debit = $wtaxamount;
						$objJvDtl->grossamount = $objJvDtl->debit;
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();

					}
				}
				
				if ($actionReturn && $obju_FA->getudfvalue("u_accumdepre")>0) {
					if ($obju_FAClass->getudfvalue("u_accumdepreacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Accumulated Depreciation Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
					//$objJvDtl->department = $obju_FA->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_accumdepreacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_accumdepreacct"));
					$objJvDtl->debit = $obju_FA->getudfvalue("u_accumdepre");
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				}
				if ($actionReturn && ($objTable->getudfvalue("u_gainloss")-$vatamount)>0) {
					if ($obju_FAClass->getudfvalue("u_gainonsaleacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Gain on Sale Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
					$objJvDtl->department = $obju_FA->getudfvalue("u_department");
					$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
					$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_gainonsaleacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_gainonsaleacct"));
					$objJvDtl->credit = $objTable->getudfvalue("u_gainloss")-$vatamount;
					$objJvDtl->grossamount = $objJvDtl->credit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
				} elseif ($actionReturn && ($objTable->getudfvalue("u_gainloss")-$vatamount)<0) {
					
					if ($obju_FAClass->getudfvalue("u_lossonsaleacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Loss on Sale Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
					$objJvDtl->department = $obju_FA->getudfvalue("u_department");
					$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
					$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_lossonsaleacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_lossonsaleacct"));
					$objJvDtl->debit = ($objTable->getudfvalue("u_gainloss")-$vatamount)*-1;
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					
				}
				
				if ($actionReturn) {
				
					if ($obju_FAClass->getudfvalue("u_purchacct")=="") return raiseError("Fixed Asset Class [".$obju_FAClass->code."] Purchase Account is not maintained.");
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
					//$objJvDtl->department = $obju_FA->getudfvalue("u_department");
					//$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
					//$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $obju_FAClass->getudfvalue("u_purchacct");
					$objJvDtl->itemname = getchartofaccountname($obju_FAClass->getudfvalue("u_purchacct"));
					$objJvDtl->credit = $objTable->getudfvalue("u_cost");
					$objJvDtl->grossamount = $objJvDtl->credit;
					$objJvHdr->totaldebit += $objJvDtl->debit ;
					$objJvHdr->totalcredit += $objJvDtl->credit ;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					
					if ($actionReturn && $vatamount>0) {
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdr->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
						//$objJvDtl->department = $obju_FA->getudfvalue("u_department");
						//$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
						//$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
						$objJvDtl->itemtype = "A";
						$objJvDtl->itemno = $taxglacctno["formatcode"];
						$objJvDtl->itemname = $taxglacctno["acctname"];
						$objJvDtl->credit = $vatamount;
						$objJvDtl->grossamount = $objJvDtl->credit;
						$objJvHdr->totaldebit += $objJvDtl->debit ;
						$objJvHdr->totalcredit += $objJvDtl->credit ;
						$objJvDtl->privatedata["header"] = $objJvHdr;
						$actionReturn = $objJvDtl->add();

					}
					
										
				}
				
				if ($actionReturn) $actionReturn = $objJvHdr->add();
				
				if ($actionReturn) {
					$objTable->setudfvalue("u_arinvno",$objJvHdr->docno);			
					$obju_FA->setudfvalue("u_accumdepre",0);
					$obju_FA->setudfvalue("u_bookvalue",0);
					$obju_FA->setudfvalue("u_salvagevalue",0);
					$actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
				}	
			} else $actionReturn = raiseError("Unable to retrieve Asset record [".$objTable->getudfvalue("u_facode")."]."); 
			break;						
	}
	//if ($actionReturn) $actionReturn = raiseError("onAddEventdocumentschema_brGPSFixedAsset");
	return $actionReturn;
}

/*
function onAddEventdocumentschema_brGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_famanacquis":
			break;
	}
	return $actionReturn;
}
*/




/*
function onBeforeUpdateEventdocumentschema_brGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fatransfers":
			break;
	}
	return $actionReturn;
}
*/

/*
function onUpdateEventdocumentschema_brGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_famanacquis":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_famanacquis":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_famanacquis":
			break;
	}
	return $actionReturn;
}
*/

?>