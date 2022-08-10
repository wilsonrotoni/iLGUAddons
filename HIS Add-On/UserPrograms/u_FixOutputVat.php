<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/collections.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentitems.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	$objARInvoices = new marketingdocuments (NULL,$objConnection,"arinvoices");
	$objARCreditMemos = new marketingdocuments (NULL,$objConnection,"arcreditmemos");
	$objARInvoiceItems = new marketingdocumentitems (NULL,$objConnection,"arinvoiceitems");
	$objARCreditMemoItems = new marketingdocumentitems (NULL,$objConnection,"arcreditmemoitems");
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs->fields["BRANCH"];
	$ctr=0;

	if ($actionReturn){
		if ($objARInvoices->getbykey("CI000123")) {
			$objARInvoices->vatamount=0;
			$objARInvoiceItems->queryopen($objARInvoiceItems->selectstring()." and DOCID='$objARInvoices->docid'");
			while ($objARInvoiceItems->queryfetchrow()) {
				$objARInvoiceItems->vatcode = "VATOX";
				$objARInvoiceItems->vatrate = 0;
				$objARInvoiceItems->vatamount = 0;
				$objARInvoiceItems->privatedata["header"] = $objARInvoices;
				$actionReturn = $objARInvoiceItems->update($objARInvoiceItems->lineid,$objARInvoiceItems->rcdversion);
				if (!$actionReturn) break;
			}
			if ($actionReturn && $objARInvoices->sbo_post_flag==1) {
				$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
				if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
				if ($actionReturn) $objARInvoices->sbo_post_flag=0;
			}
			if ($actionReturn) $actionReturn = $objARInvoices->update($objARInvoices->docno,$objARInvoices->rcdversion);
		} else $actionReturn = raiseError("Unable to find AR Invoice [CI000123]");	
	} 
	if ($actionReturn){
		if ($objARInvoices->getbykey("CI001391")) {
			$objARInvoices->vatamount=0;
			$objARInvoiceItems->queryopen($objARInvoiceItems->selectstring()." and DOCID='$objARInvoices->docid'");
			while ($objARInvoiceItems->queryfetchrow()) {
				$objARInvoiceItems->vatcode = "VATOX";
				$objARInvoiceItems->vatrate = 0;
				$objARInvoiceItems->vatamount = 0;
				$objARInvoiceItems->privatedata["header"] = $objARInvoices;
				$actionReturn = $objARInvoiceItems->update($objARInvoiceItems->lineid,$objARInvoiceItems->rcdversion);
				if (!$actionReturn) break;
			}
			if ($actionReturn && $objARInvoices->sbo_post_flag==1) {
				$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
				if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
				if ($actionReturn) $objARInvoices->sbo_post_flag=0;
			}
			if ($actionReturn) $actionReturn = $objARInvoices->update($objARInvoices->docno,$objARInvoices->rcdversion);
		} else $actionReturn = raiseError("Unable to find AR Invoice [CI001391]");	
	} 
	if ($actionReturn){
		if ($objARInvoices->getbykey("CI001392")) {
			$objARInvoices->vatamount=0;
			$objARInvoiceItems->queryopen($objARInvoiceItems->selectstring()." and DOCID='$objARInvoices->docid'");
			while ($objARInvoiceItems->queryfetchrow()) {
				$objARInvoiceItems->vatcode = "VATOX";
				$objARInvoiceItems->vatrate = 0;
				$objARInvoiceItems->vatamount = 0;
				$objARInvoiceItems->privatedata["header"] = $objARInvoices;
				$actionReturn = $objARInvoiceItems->update($objARInvoiceItems->lineid,$objARInvoiceItems->rcdversion);
				if (!$actionReturn) break;
			}
			if ($actionReturn && $objARInvoices->sbo_post_flag==1) {
				$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
				if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
				if ($actionReturn) $objARInvoices->sbo_post_flag=0;
			}
			if ($actionReturn) $actionReturn = $objARInvoices->update($objARInvoices->docno,$objARInvoices->rcdversion);
		} else $actionReturn = raiseError("Unable to find AR Invoice [CI001392]");	
	} 
	
	if ($actionReturn) {
		$objRs->queryopen("select docno,u_trxtype,u_department,docstatus from u_hispos where u_prepaid=1");
		while ($objRs->queryfetchrow("NAME")) {
			if ($objARInvoices->getbykey($objRs->fields["docno"])) {
				$objARInvoices->vatamount=0;
				$objARInvoiceItems->queryopen($objARInvoiceItems->selectstring()." and DOCID='$objARInvoices->docid'");
				while ($objARInvoiceItems->queryfetchrow()) {
					if ($objRs->fields["u_trxtype"]=="CM" || $objRs->fields["u_department"]!="PHA") { 
						$objARInvoiceItems->vatcode = "VATOX";
						$objARInvoiceItems->vatrate = 0;
						$objARInvoiceItems->vatamount = 0;
					} else {
						$objARInvoiceItems->vatcode = "VATOUT";
						$objARInvoiceItems->vatrate = 12;
						$objARInvoiceItems->vatamount = $objARInvoiceItems->linetotal - round($objARInvoiceItems->linetotal/1.12,2);
						$objARInvoices->vatamount+=$objARInvoiceItems->vatamount;
					}
					$objARInvoiceItems->privatedata["header"] = $objARInvoices;
					$actionReturn = $objARInvoiceItems->update($objARInvoiceItems->lineid,$objARInvoiceItems->rcdversion);
					if (!$actionReturn) break;
				}
				if ($actionReturn && $objARInvoices->sbo_post_flag==1) {
					$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CS','AR') and docno='$objARInvoices->docno'",false);
					if ($actionReturn) $objARInvoices->sbo_post_flag=0;
				}
				if ($actionReturn) $actionReturn = $objARInvoices->update($objARInvoices->docno,$objARInvoices->rcdversion);
			} else $actionReturn = raiseError("Unable to find AR Invoice [".$objRs->fields["docno"]."]");
			if (!$actionReturn) break;

			if ($objRs->fields["docstatus"]=="CN") {
				if ($objARCreditMemos->getbykey($objRs->fields["docno"])) {
					$objARCreditMemos->vatamount=0;
					$objARCreditMemoItems->queryopen($objARCreditMemoItems->selectstring()." and DOCID='$objARCreditMemos->docid'");
					while ($objARCreditMemoItems->queryfetchrow()) {
						if ($objRs->fields["u_trxtype"]=="CM" || $objRs->fields["u_department"]!="PHA") {
							$objARCreditMemoItems->vatcode = "VATOX";
							$objARCreditMemoItems->vatrate = 0;
							$objARCreditMemoItems->vatamount = 0;
						} else {
							$objARCreditMemoItems->vatcode = "VATOUT";
							$objARCreditMemoItems->vatrate = 12;
							$objARCreditMemoItems->vatamount = $objARCreditMemoItems->linetotal - round($objARCreditMemoItems->linetotal/1.12,2);
							$objARCreditMemos->vatamount+=$objARCreditMemoItems->vatamount;
						}
						$objARCreditMemoItems->privatedata["header"] = $objARCreditMemos;
						$actionReturn = $objARCreditMemoItems->update($objARCreditMemoItems->lineid,$objARCreditMemoItems->rcdversion);
						if (!$actionReturn) break;
					}
					if ($actionReturn && $objARCreditMemos->sbo_post_flag==1) {
						$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='$objARCreditMemos->docno'",false);
						if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='$objARCreditMemos->docno'",false);
						if ($actionReturn) $objARCreditMemos->sbo_post_flag=0;
					}
					if ($actionReturn) $actionReturn = $objARCreditMemos->update($objARCreditMemos->docno,$objARCreditMemos->rcdversion);
				} else $actionReturn = raiseError("Unable to find AR Credit Memo [".$objRs->fields["docno"]."]");
			}
			$ctr++;
			//echo $objRs->fields["docno"].", ".$objRs->fields["u_trxtype"].", ".$objRs->fields["docstatus"]."<br>";
		}
		$objRs->queryopen("select docno,u_trxtype,u_department,u_prepaid,u_payreftype from u_hiscredits");
		while ($objRs->queryfetchrow("NAME")) {
			if ($objARCreditMemos->getbykey($objRs->fields["docno"])) {
				$objARCreditMemos->vatamount=0;
				$objARCreditMemoItems->queryopen($objARCreditMemoItems->selectstring()." and DOCID='$objARCreditMemos->docid'");
				while ($objARCreditMemoItems->queryfetchrow()) {
					if ($objRs->fields["u_department"]!="PHA" || $objRs->fields["u_prepaid"]!="1" || $objRs->fields["u_payreftype"]=="CM") {
						$objARCreditMemoItems->vatcode = "VATOX";
						$objARCreditMemoItems->vatrate = 0;
						$objARCreditMemoItems->vatamount = 0;
					} else {
						$objARCreditMemoItems->vatcode = "VATOUT";
						$objARCreditMemoItems->vatrate = 12;
						$objARCreditMemoItems->vatamount = $objARCreditMemoItems->linetotal - round($objARCreditMemoItems->linetotal/1.12,2);
						$objARCreditMemos->vatamount+=$objARCreditMemoItems->vatamount;
					}
					$objARCreditMemoItems->privatedata["header"] = $objARCreditMemos;
					$actionReturn = $objARCreditMemoItems->update($objARCreditMemoItems->lineid,$objARCreditMemoItems->rcdversion);
					if (!$actionReturn) break;
				}
				if ($actionReturn && $objARCreditMemos->sbo_post_flag==1) {
					$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='$objARCreditMemos->docno'",false);
					if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('CM') and docno='$objARCreditMemos->docno'",false);
					if ($actionReturn) $objARCreditMemos->sbo_post_flag=0;
				}
				if ($actionReturn) $actionReturn = $objARCreditMemos->update($objARCreditMemos->docno,$objARCreditMemos->rcdversion);
			} else $actionReturn = raiseError("Unable to find AR Credit Memo [".$objRs->fields["docno"]."]");		
		}
	}	

	
	if ($actionReturn) {
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
