<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_HISRemoveTrxFromBill";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/customers.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	$post = false;
	$validate = false;
	$page->objectcode = $progid;
	$httpVars["df_errormessages"] = "";

		
	function onFormAction($action) {
		global $httpVars;
		global $objConnection;
		global $branch;
		global $branchdata;
		global $page;
		
		$actionReturn = true;
		
		if ($action!="u_process") return true;
		
		$objJVHdr = new journalvouchers(null,$objConnection);
		$objJVDtl = new journalvoucheritems(null,$objConnection);
		
		$obju_HisBills = new documentschema_br(null,$objConnection,"u_hisbills");
		$obju_HisBillTrxs = new documentlinesschema_br(null,$objConnection,"u_hisbilltrxs");
		$obju_HisBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
		$obju_HisBillMiscs = new documentlinesschema_br(null,$objConnection,"u_hisbillmiscs");
		$obju_HisBillMedSups = new documentlinesschema_br(null,$objConnection,"u_hisbillmedsups");
		$obju_HisBillLabTests = new documentlinesschema_br(null,$objConnection,"u_hisbilllabtests");
		$obju_HisBillSplRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillsplrooms");
		$obju_HisBillSections = new documentlinesschema_br(null,$objConnection,"u_hisbillsections");
		$obju_HisCharges = new documentschema_br(null,$objConnection,"u_hischarges");
		$amount = $page->getitemdecimal("u_amount");
		$objConnection->beginwork();
		if ($obju_HisBills->getbykey($page->getitemstring("u_billno")) && $objJVHdr->getbykey($page->getitemstring("u_billno"))) {
			if ($obju_HisBillFees->getbysql("DOCID='$obju_HisBills->docid' AND U_FEETYPE='Hospital Fees'") && 
						$objJVDtl->getbysql("DOCID='$objJVHdr->docid' AND OTHERTRXTYPE='Hospital Fees'")) {
				if ($obju_HisBillSections->getbysql("DOCID='$obju_HisBills->docid' AND U_DEPARTMENT='".$page->getitemstring("u_department")."'")) {
					if ($actionReturn) {
						$obju_HisBillMiscs->setdebug();
						$obju_HisBillMiscs->queryopen($obju_HisBillMiscs->selectstring()." AND DOCID='$obju_HisBills->docid' AND U_REFNO='".$page->getitemstring("u_docno")."'");
						while ($obju_HisBillMiscs->queryfetchrow()) {
							if ($obju_HisBillMiscs->getudfvalue("u_discamount")!=0) return raiseError("Itemize discount exists, cannot remove this transaction.");
							
							$obju_HisBillFees->setudfvalue("u_amount",$obju_HisBillFees->getudfvalue("u_amount")-$obju_HisBillMiscs->getudfvalue("u_amount"));
							$obju_HisBillFees->setudfvalue("u_netamount",$obju_HisBillFees->getudfvalue("u_netamount")-$obju_HisBillMiscs->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_amount",$obju_HisBills->getudfvalue("u_amount")-$obju_HisBillMiscs->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_netamount",$obju_HisBills->getudfvalue("u_netamount")-$obju_HisBillMiscs->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_dueamount",$obju_HisBills->getudfvalue("u_dueamount")-$obju_HisBillMiscs->getudfvalue("u_amount"));
							$obju_HisBillSections->setudfvalue("u_amount",$obju_HisBillSections->getudfvalue("u_amount")-$obju_HisBillMiscs->getudfvalue("u_amount"));
							$amount-=$obju_HisBillMiscs->getudfvalue("u_amount");
							$obju_HisBillMiscs->privatedata["header"] = $obju_HisBills;
							$actionReturn = $obju_HisBillMiscs->delete();
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn) {
						$obju_HisBillMedSups->queryopen($obju_HisBillMedSups->selectstring()." AND DOCID='$obju_HisBills->docid' AND U_REFNO='".$page->getitemstring("u_docno")."'");
						while ($obju_HisBillMedSups->queryfetchrow()) {
							if ($obju_HisBillMedSups->getudfvalue("u_discamount")!=0) return raiseError("Itemize discount exists, cannot remove this transaction.");
							$obju_HisBillFees->setudfvalue("u_amount",$obju_HisBillFees->getudfvalue("u_amount")-$obju_HisBillMedSups->getudfvalue("u_amount"));
							$obju_HisBillFees->setudfvalue("u_netamount",$obju_HisBillFees->getudfvalue("u_netamount")-$obju_HisBillMedSups->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_amount",$obju_HisBills->getudfvalue("u_amount")-$obju_HisBillMedSups->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_netamount",$obju_HisBills->getudfvalue("u_netamount")-$obju_HisBillMedSups->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_dueamount",$obju_HisBills->getudfvalue("u_dueamount")-$obju_HisBillMedSups->getudfvalue("u_amount"));
							$obju_HisBillSections->setudfvalue("u_amount",$obju_HisBillSections->getudfvalue("u_amount")-$obju_HisBillMedSups->getudfvalue("u_amount"));
							$amount-=$obju_HisBillMedSups->getudfvalue("u_amount");
							$obju_HisBillMedSups->privatedata["header"] = $obju_HisBills;
							$actionReturn = $obju_HisBillMedSups->delete();
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn) {
						$obju_HisBillLabTests->queryopen($obju_HisBillLabTests->selectstring()." AND DOCID='$obju_HisBills->docid' AND U_REFNO='".$page->getitemstring("u_docno")."'");
						while ($obju_HisBillLabTests->queryfetchrow()) {
							if ($obju_HisBillLabTests->getudfvalue("u_discamount")!=0) return raiseError("Itemize discount exists, cannot remove this transaction.");
							$obju_HisBillFees->setudfvalue("u_amount",$obju_HisBillFees->getudfvalue("u_amount")-$obju_HisBillLabTests->getudfvalue("u_amount"));
							$obju_HisBillFees->setudfvalue("u_netamount",$obju_HisBillFees->getudfvalue("u_netamount")-$obju_HisBillLabTests->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_amount",$obju_HisBills->getudfvalue("u_amount")-$obju_HisBillLabTests->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_netamount",$obju_HisBills->getudfvalue("u_netamount")-$obju_HisBillLabTests->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_dueamount",$obju_HisBills->getudfvalue("u_dueamount")-$obju_HisBillLabTests->getudfvalue("u_amount"));
							$obju_HisBillSections->setudfvalue("u_amount",$obju_HisBillSections->getudfvalue("u_amount")-$obju_HisBillLabTests->getudfvalue("u_amount"));
							$amount-=$obju_HisBillLabTests->getudfvalue("u_amount");
							$obju_HisBillLabTests->privatedata["header"] = $obju_HisBills;
							$actionReturn = $obju_HisBillLabTests->delete();
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn) {
						$obju_HisBillSplRooms->queryopen($obju_HisBillSplRooms->selectstring()." AND DOCID='$obju_HisBills->docid' AND U_REFNO='".$page->getitemstring("u_docno")."'");
						while ($obju_HisBillSplRooms->queryfetchrow()) {
							if ($obju_HisBillSplRooms->getudfvalue("u_discamount")!=0) return raiseError("Itemize discount exists, cannot remove this transaction.");
							$obju_HisBillFees->setudfvalue("u_amount",$obju_HisBillFees->getudfvalue("u_amount")-$obju_HisBillSplRooms->getudfvalue("u_amount"));
							$obju_HisBillFees->setudfvalue("u_netamount",$obju_HisBillFees->getudfvalue("u_netamount")-$obju_HisBillSplRooms->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_amount",$obju_HisBills->getudfvalue("u_amount")-$obju_HisBillSplRooms->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_netamount",$obju_HisBills->getudfvalue("u_netamount")-$obju_HisBillSplRooms->getudfvalue("u_amount"));
							$obju_HisBills->setudfvalue("u_dueamount",$obju_HisBills->getudfvalue("u_dueamount")-$obju_HisBillSplRooms->getudfvalue("u_amount"));
							$obju_HisBillSections->setudfvalue("u_amount",$obju_HisBillSections->getudfvalue("u_amount")-$obju_HisBillSplRooms->getudfvalue("u_amount"));
							$amount-=$obju_HisBillSplRooms->getudfvalue("u_amount");
							$obju_HisBillSplRooms->privatedata["header"] = $obju_HisBills;
							$actionReturn = $obju_HisBillSplRooms->delete();
							if (!$actionReturn) break;
						}
					}	
					if ($actionReturn && $amount==0) {
						$objJVDtl->debit = $objJVDtl->debit-$page->getitemdecimal("u_amount");
						$objJVDtl->grossamount = $objJVDtl->grossamount-$page->getitemdecimal("u_amount");
						$objJVDtl->balanceamount = $objJVDtl->balanceamount-$page->getitemdecimal("u_amount");
						$objJVDtl->linestatus = iif($objJVDtl->balanceamount==0,'C','O');
						$objJVHdr->totaldebit = $objJVHdr->totaldebit-$page->getitemdecimal("u_amount");
						$objJVHdr->totalcredit = $objJVHdr->totalcredit-$page->getitemdecimal("u_amount");
						$objJVDtl->privatedata["header"] = $objJVHdr;
						if ($actionReturn) $actionReturn = $objJVDtl->update($objJVDtl->lineid,$objJVDtl->rcdversion);
						if ($actionReturn) {
							if ($objJVDtl->getbysql("DOCID='$objJVHdr->docid' AND REFNO='".$page->getitemstring("u_docno")."' AND CREDIT=".$page->getitemdecimal("u_amount"))) {
								$objJVDtl->privatedata["header"] = $objJVHdr;
								$actionReturn = $objJVDtl->delete();
							}
						}
						if ($actionReturn) $actionReturn = $objJVHdr->update($objJVHdr->docno,$objJVHdr->rcdversion);
					} else raiseError("Unable to remove transaction in bill, unreconciled amount [".$amount."]");
					if ($actionReturn) $actionReturn = $obju_HisBillFees->update($obju_HisBillFees->docid,$obju_HisBillFees->lineid,$obju_HisBillFees->rcdversion);
					if ($actionReturn) $actionReturn = $obju_HisBillSections->update($obju_HisBillSections->docid,$obju_HisBillSections->lineid,$obju_HisBillSections->rcdversion);
					if ($actionReturn) $actionReturn = $obju_HisBills->update($obju_HisBills->docno,$obju_HisBills->rcdversion);
				} else return raiseError("Unable to find Bill Section [".$page->getitemstring("u_department")."]");		
			} else return raiseError("Unable to find Bill Fee and/or JV Dtl [Hospital Fees]");	
			if ($actionReturn) {
				if ($obju_HisBillTrxs->getbysql("DOCID='$obju_HisBills->docid' AND U_DOCNO='".$page->getitemstring("u_docno")."' AND U_DOCTYPE='CHRG'") && $obju_HisCharges->getbykey($page->getitemstring("u_docno"))) {
					$obju_HisBillTrxs->privatedata["header"] = $obju_HisBills;
					if ($actionReturn) {
						$obju_HisCharges->setudfvalue("u_billno","");
						$obju_HisCharges->docstatus='O';
						$actionReturn = $obju_HisCharges->update($obju_HisCharges->docno,$obju_HisCharges->rcdversion);
					}	
					if ($actionReturn) $actionReturn = $obju_HisBillTrxs->delete();
				} else return raiseError("Unable to find Bill Transaction [".$page->getitemstring("u_docno")."]");		
			}
			//if ($actionReturn) $actionReturn = raiseError("ok");
		} else return raiseError("Unable to find Bill and/or JV No [".$page->getitemstring("u_billno")."]");
		/*$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		$objConnection->beginwork();
		$datefr=$page->getitemdate("datefr");
		$dateto=$page->getitemdate("dateto");
		$u_billno=$page->getitemstring("u_billno");
		$itemcodeto=$page->getitemstring("itemcodeto");
		$itemdescto=addslashes($page->getitemstring("itemdescto"));
		if ($datefr!="" || $dateto !="") {
			if ($dateto=="") $dateto = $datefr;
		}

		$filterExp = "";
		$filterExp = genSQLFilterString("a.U_DEPARTMENT",$filterExp,$httpVars['df_department']);
		$filterExp = genSQLFilterString("b.U_ITEMCODE",$filterExp,$httpVars['df_u_billno']);
		$filterExp = genSQLFilterDate("a.U_STARTDATE",$filterExp,$page->getitemdate("datefr"),$page->getitemdate("dateTO"));
		if ($filterExp !="") $filterExp = " AND " . $filterExp;

		$filterExp2 = "";
		$filterExp2 = genSQLFilterString("a.U_DEPARTMENT",$filterExp2,$httpVars['df_department']);
		$filterExp2 = genSQLFilterString("b.U_ITEMCODE",$filterExp2,$httpVars['df_u_billno']);
		$filterExp2 = genSQLFilterDate("a.U_REQUESTDATE",$filterExp2,$page->getitemdate("datefr"),$page->getitemdate("dateTO"));
		if ($filterExp2 !="") $filterExp2 = " AND " . $filterExp2;

		$filterExp3 = "";
		$filterExp3 = genSQLFilterString("b.DRCODE",$filterExp3,$httpVars['df_department']);
		$filterExp3 = genSQLFilterString("b.ITEMCODE",$filterExp3,$httpVars['df_u_billno']);
		$filterExp3 = genSQLFilterDate("a.DOCDATE",$filterExp3,$page->getitemdate("datefr"),$page->getitemdate("dateTO"));
		if ($filterExp3 !="") $filterExp3 = " AND " . $filterExp3;
		
		//$objRs->setdebug();	
		//$objRs2->setdebug();	

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid from u_hisrequests a inner join u_hisrequestitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp2 group by a.docno");
			while ($objRs->queryfetchrow()) {
				$actionReturn = $objRs2->executesql("update u_hisrequestitems set u_itemcode='".$itemcodeto."',u_itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid from u_hischarges a inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp group by a.docno");
			while ($objRs->queryfetchrow()) {
				$actionReturn = $objRs2->executesql("update u_hischargeitems set u_itemcode='".$itemcodeto."',u_itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid from u_hiscredits a inner join u_hiscredititems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp group by a.docno");
			while ($objRs->queryfetchrow()) {
				$actionReturn = $objRs2->executesql("update u_hiscredititems set u_itemcode='".$itemcodeto."',u_itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from arinvoices a inner join arinvoiceitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update arinvoices set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR','SI') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR','SI') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update arinvoiceitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from arcreditmemos a inner join arcreditmemoitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update arinvoices set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update arcreditmemoitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}

		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from salesdeliveries a inner join salesdeliveryitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update salesdeliveries set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('DN') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('DN') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update salesdeliveryitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("select a.docno, a.docid, a.sbo_post_flag from salesreturns a inner join salesreturnitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' $filterExp3 group by a.docno");
			while ($objRs->queryfetchrow()) {
				if ($objRs->fields[2]=="1") {
					$actionReturn = $objRs2->executesql("update salesreturns set sbo_post_flag=0 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('RT') and docno='".$objRs->fields[0]."'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('RT') and docno='".$objRs->fields[0]."'",false);
				}
				if ($actionReturn) $actionReturn = $objRs2->executesql("update salesreturnitems set itemcode='".$itemcodeto."',itemdesc='".$itemdescto."' where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields[1]."'",false);
				if (!$actionReturn) break;
			}
		}
		*/		
		//var_dump($objRs->sqls);
		//var_dump($objRs2->sqls);
		//var_dump($datefr);
		//var_dump($dateto);
		//if ($actionReturn) $actionReturn = raiseError("onFormAction()");
		if ($actionReturn) {
			$objConnection->commit();
			modeDefault();
		} else $objConnection->rollback();


		return $actionReturn;
	}
	
	function onFormDefault() {
		global $page;
		global $branchdata;
		$page->setitem("u_billno","");
		$page->setitem("u_patientname","");
		$page->setitem("u_docno","");
		$page->setitem("u_docdate","");
		$page->setitem("u_docdesc","");
		$page->setitem("u_department","");
		$page->setitem("u_amount",formatNumericAmount(0));
		$page->setitem("u_balance",formatNumericAmount(0));
		
	}
	
	$page->reportlayouts = true;
	$page->settings->load();

	//var_dump($branchdata);
	
	$objrs = new recordset(NULL,$objConnection);
	$objrs2 = new recordset(NULL,$objConnection);
	$objrs->logtrx = false;
	
	$actionReturn=true;
	
		
	
	require("./inc/formactions.php");
	
	$schema_batchpost["u_billno"] = createSchema("u_billno");
	$schema_batchpost["u_patientname"] = createSchema("u_patientname");
	$schema_batchpost["u_docno"] = createSchema("u_docno");
	$schema_batchpost["u_department"] = createSchema("u_department");
	$schema_batchpost["u_docdesc"] = createSchema("u_docdesc");
	$schema_batchpost["u_docdate"] = createSchemaDate("u_docdate");
	$schema_batchpost["u_amount"] = createSchemaAmount("u_amount");
	$schema_batchpost["u_balance"] = createSchemaAmount("u_balance");

	$schema_batchpost["u_billno"]["cfl"] = "OpenCFLfs()";
	$schema_batchpost["u_docno"]["cfl"] = "OpenCFLfs()";
	
	$schema_batchpost["u_patientname"]["attributes"] = "disabled";
	$schema_batchpost["u_department"]["attributes"] = "disabled";
	$schema_batchpost["u_docdate"]["attributes"] = "disabled";
	$schema_batchpost["u_docdesc"]["attributes"] = "disabled";
	$schema_batchpost["u_amount"]["attributes"] = "disabled";
	$schema_batchpost["u_balance"]["attributes"] = "disabled";
	//$schema_batchpost["datefr"]["cfl"] = "Calendar";	
	//$schema_batchpost["dateto"]["cfl"] = "Calendar";	

	$schema_batchpost["u_billno"]["required"] = true;
	
	saveErrorMsg();
	//var_dump($sql);
	//var_dump($objGrid->recordcount);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo $page->theme ; ?>.css">
<STYLE>
</STYLE>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/directories.js"></SCRIPT>
<script type="text/javascript">
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
var tabberOptions = {
  'manualStartup':true,
  'onLoad': function(argsObj) {},
  'onClick': function(argsObj) {return true;},
  'addLinkId': true
};
</script>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>

<script>

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	function onPageLoad() {
	}
	
	function onFormSubmit(action) {
		if(action=="u_process") {
			if (isInputEmpty("u_billno")) return false; 
			if (isInputEmpty("itemcodeto")) return false; 
			if (window.confirm("You cannot reverse this removal of document. Continue?")==false) return false;
		}
		showAjaxProcess();
		return true;
	}	

	function onFormSubmitReturn(action,sucess,error) {
		if (sucess)	alert('Process ended successfully.');
		else alert(error);
	}

	function onElementValidate(element,column,table,row) {
		switch(table) {
			default:	
				switch(column) {
					case "u_billno":	
						if (getInput(column)!="") {
							result = page.executeFormattedQuery("select a.docno, a.u_reftype, a.u_refno, a.u_patientid, a.u_patientname, a.u_dueamount-a.u_dpbal-a.u_paidamount as dueamount from u_hisbills a where a.docno='"+getInput("u_billno")+"' and a.docstatus not in ('Cancelled','CN','D')");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_patientname",result.childNodes.item(0).getAttribute("u_patientname"));
									setInput("u_billno",result.childNodes.item(0).getAttribute("docno"));
									setInput("u_docno","");
									setInput("u_department","");
									setInput("u_docdate","");
									setInput("u_docdesc","");
									setInputAmount("u_amount",0);
									setInputAmount("u_balance",0);
								} else {
									setInput("u_patientname","");
									setInput("u_billno","");
									setInput("u_docno","");
									setInput("u_department","");
									setInput("u_docdate","");
									setInput("u_docdesc","");
									setInputAmount("u_amount",0);
									setInputAmount("u_balance",0);
									page.statusbar.showError("Invalid Bill No.");	
									return false;
								}
							} else {
								setInput("u_patientname","");
								setInput("u_billno","");
								setInput("u_docno","");
								setInput("u_department","");
								setInput("u_docdate","");
								setInput("u_docdesc","");
								setInputAmount("u_amount",0);
								setInputAmount("u_balance",0);
								page.statusbar.showError("Error retrieving item. Try Again, if problem persists, check the connection.");	
								return false;
							}						
						} else {
							setInput("u_patientname","");
							setInput("u_billno","");
							setInput("u_docno","");
							setInput("u_department","");
							setInput("u_docdate","");
							setInput("u_docdesc","");
							setInputAmount("u_amount",0);
							setInputAmount("u_balance",0);
						}	
						break;
					case "u_docno":
						if (getInput(column)!="") {
							result = page.executeFormattedQuery("select b.u_docno, b.u_docdate, b.u_department, b.u_docdesc, b.u_amount, b.u_balance from u_hisbills a inner join u_hisbilltrxs b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"' and b.u_docno='"+getInput("u_docno")+"'");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("u_docno",result.childNodes.item(0).getAttribute("u_docno"));
									setInput("u_department",result.childNodes.item(0).getAttribute("u_department"));
									setInput("u_docdate",formatDateToHttp(result.childNodes.item(0).getAttribute("u_docdate")));
									setInput("u_docdesc",result.childNodes.item(0).getAttribute("u_docdesc"));
									setInputAmount("u_amount",result.childNodes.item(0).getAttribute("u_amount"));
									setInputAmount("u_balance",result.childNodes.item(0).getAttribute("u_balance"));
								} else {
									setInput("u_docno","");
									setInput("u_department","");
									setInput("u_docdate","");
									setInput("u_docdesc","");
									setInputAmount("u_amount",0);
									setInputAmount("u_balance",0);
									page.statusbar.showError("Invalid Document No.");	
									return false;
								}
							} else {
								setInput("u_docno","");
								setInput("u_department","");
								setInput("u_docdate","");
								setInput("u_docdesc","");
								setInputAmount("u_amount",0);
								setInputAmount("u_balance",0);
								page.statusbar.showError("Error retrieving item. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("u_docno","");
							setInput("u_department","");
							setInput("u_docdate","");
							setInput("u_docdesc","");
							setInputAmount("u_amount",0);
							setInputAmount("u_balance",0);
						}
						break;
				}
				break;
		}
		return true;
	}
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {
			case "df_u_billno":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno, u_docdate, u_patientname,u_dueamount, u_paidamount,u_dueamount-u_paidamount as u_balance from u_hisbills where docstatus not in ('Cancelled','CN','D')")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Bill Date`Patient Name`Due Amount`Paid`Balance")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("16`11`35`10`9`9")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date``amount`amount`amount")); 			
				params["params"] += "&cflsortby=u_patientname";
				
				break;
			case "df_u_docno":
				params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select b.u_docno, b.u_docdate, b.u_department, b.u_docdesc, b.u_amount, b.u_balance from u_hisbills a inner join u_hisbilltrxs b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.docno='"+getInput("u_billno")+"'")); 
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Date`Section`Particular`Amount`Balance")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("16`11`25`20`9`9")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("`date```amount`amount")); 			
				break;
		}
		return params;
	}	
	
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<input type="hidden" id="batchpostingmode" name="batchpostingmode" value="<?php echo $companydata["BATCHPOSTINGMODE"];  ?>">	
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	

	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["u_billno"],"") ?> >Bill No:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema_batchpost["u_billno"]) ?> />&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema_batchpost["u_patientname"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["u_docno"],"") ?> >Document No:</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema_batchpost["u_docno"]) ?> />&nbsp;<select <?php genSelectHtml($schema_batchpost["u_department"],array("loadudflinktable","u_hissections:code:name",":")) ?> /></select></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["u_docdate"],"") ?> >Date</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema_batchpost["u_docdate"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["u_docdesc"],"") ?> >Description</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema_batchpost["u_docdesc"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["u_amount"],"") ?> >Amount</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema_batchpost["u_amount"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label <?php genCaptionHtml($schema_batchpost["u_balance"],"") ?> >Balance</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema_batchpost["u_balance"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="2">&nbsp;<a class="button" href="" onClick="formSubmit('u_process');return false;">Remove Transaction</a></td>
	  <td >&nbsp;</td>
	  </tr>
	
</table></td></tr>			
<?php if ($requestorId == "") { ?>
	<tr><td>&nbsp;</td></tr>
	<?php //require("./sboBatchPostingToolbar.php");  ?>
<?php } ?>    
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php require("./bofrms/ajaxprocess.php"); ?>
<?php $page->writePopupMenuHTML();?>
</body>

</html>
<?php $page->writeEndScript(); ?>
<?php	
	restoreErrorMsg();

	//$parentref = "parent.";
	//$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_DataMigrationToolbar.php");
?>
<?php 
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
