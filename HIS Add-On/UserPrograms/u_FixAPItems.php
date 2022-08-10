<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./utils/stockcard.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentitems.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs = new recordset (NULL,$objConnection);
	$objAPInvoices = new marketingdocuments(null,$objConnection,"apinvoices");
	$objAPInvoiceItems = new marketingdocumentitems(null,$objConnection,"apinvoiceitems");
	
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs->fields["BRANCH"];
	$ctr=0;

	if ($actionReturn){
		if ($objAPInvoices->getbykey("RR24641")) {
			$objAPInvoiceItems->setdebug();
			$objAPInvoiceItems->queryopen($objAPInvoiceItems->selectstring()." AND DOCID='$objAPInvoices->docid' and ITEMCOST=0");
			while ($objAPInvoiceItems->queryfetchrow()) {
				$objAPInvoiceItems->sbnids = "0|NONE|".($objAPInvoiceItems->quantity*$objAPInvoiceItems->numperuom)."|u_expdate|";
				$objAPInvoiceItems->sbncnt = ($objAPInvoiceItems->quantity*$objAPInvoiceItems->numperuom);
				$objAPInvoiceItems->privatedata["header"] = $objAPInvoices;
				$actionReturn = onCustomEventmarketingdocumentitemsUpdateItems("N",$objAPInvoiceItems);
				if ($actionReturn) $actionReturn = $objAPInvoiceItems->update($objAPInvoiceItems->lineid,$objAPInvoiceItems->rcdversion);
				if (!$actionReturn) break;
			}
		}	
	} 

	if ($actionReturn) {
		///$objConnection->rollback();
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
