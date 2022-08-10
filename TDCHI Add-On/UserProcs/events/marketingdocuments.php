<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/itempricelists.php");
	include_once("./inc/formutils.php");
	include_once("./utils/suppliers.php");
	include_once("./utils/wtaxes.php");
	include_once("./utils/trxlog.php");

	function onAddEventmarketingdocumentsGPSTDCHI($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		switch(strtoupper($objTable->objectcode)) {
			 case "APINVOICE" :
			 	if ($objTable->docstatus!="D") {
					 $actionReturn = onCustomEventmarketingdocumentsUpdatePurchasePriceGPSTDCHI($objTable);
				}	
				break;
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onAddEventmarketingdocumentsGPSTDCHI");
		return $actionReturn;
	}

	function onUpdateEventmarketingdocumentsGPSTDCHI($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		switch(strtoupper($objTable->objectcode)) {
			 case "APINVOICE" :
			 	if ($objTable->docstatus!=$objTable->fields["DOCSTATUS"] && $objTable->fields["DOCSTATUS"]=="D") {
					 $actionReturn = onCustomEventmarketingdocumentsUpdatePurchasePriceGPSTDCHI($objTable);
				}	
				break;
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventmarketingdocumentsGPSTDCHI");
		return $actionReturn;
	}
	
	function onDeleteEventmarketingdocumentsGPSTDCHI($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		return $actionReturn;
	}
	

	function onCustomEventmarketingdocumentsUpdatePurchasePriceGPSTDCHI($objTable) {
		global $objConnection;
		$actionReturn=true;

		if (!$objTable->doctype=="I") return true;
		
		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		$obju_HISItems = new masterdataschema(null,$objConnection,"u_hisitems");
		$objItemPriceLists = new itempricelists(null,$objConnection);
		/*
		$companydata = getcurrentcompanydata("VATINCLUSIVE");
		$supplierdata = getsupplierdata($objTable->userfields["u_hauler"]["value"],"SUPPNAME,WTAXCODE,PARENTBPCODE,PARENTBPTYPE,U_NONVAT");
		$wtaxdata = array();
		if ($supplierdata["WTAXCODE"]!="") {
			$wtaxdata = getwtaxdata($supplierdata["WTAXCODE"],"WTAXCATEGORY,WTAXTYPE,WTAXBASEAMOUNTPERC");
			$wtaxdata["RATE"] = getwtaxrate($supplierdata["WTAXCODE"],$objTable->docdate);
		}	
		*/
		//$objRs->setdebug();
		$objRs->queryopen("select A.ITEMCODE, ROUND(A.PRICE,6) AS PRICE from APINVOICEITEMS A INNER JOIN ITEMS B ON B.ITEMCODE=A.ITEMCODE INNER JOIN ITEMPRICELISTS C ON C.ITEMCODE=B.ITEMCODE AND C.PRICELIST='12' AND ROUND(C.PRICE/B.NUMPERUOMPU,6) <> ROUND(A.PRICE/A.NUMPERUOM,6) where A.COMPANY='".$_SESSION["company"]."' and A.BRANCH='".$_SESSION["branch"]."' and A.ISINVUOM=0 and A.PRICE<>0 and A.DOCID='".$objTable->docid."' UNION ALL 
select A.ITEMCODE,ROUND(A.PRICE*A.NUMPERUOM,6) AS PRICE from APINVOICEITEMS A INNER JOIN ITEMS B ON B.ITEMCODE=A.ITEMCODE INNER JOIN ITEMPRICELISTS C ON C.ITEMCODE=B.ITEMCODE AND C.PRICELIST='12' AND ROUND(C.PRICE/B.NUMPERUOMPU,6) <> ROUND(A.PRICE,6) where A.COMPANY='".$_SESSION["company"]."' and A.BRANCH='".$_SESSION["branch"]."' and A.ISINVUOM=1 and A.PRICE<>0 and A.DOCID='".$objTable->docid."'");
		//var_dump($objRs->sqls);
		while ($objRs->queryfetchrow("NAME")) {
			//var_dump(array($objRs->fields["ITEMCODE"],$objRs->fields["PRICE"]));
			if ($obju_HISItems->getbykey($objRs->fields["ITEMCODE"])) {
				if (!$objItemPriceLists->getbykey($objRs->fields["ITEMCODE"],"12")) {
					$objItemPriceLists->prepareadd();
					$objItemPriceLists->itemcode = $objRs->fields["ITEMCODE"];
					$objItemPriceLists->pricelist = "12";
					
				}
				$objItemPriceLists->price = $objRs->fields["PRICE"];
				if ($objItemPriceLists->rowstat=="N") $actionReturn = $objItemPriceLists->add();
				$actionReturn = $objItemPriceLists->update($objItemPriceLists->itemcode, $objItemPriceLists->pricelist, $objItemPriceLists->rcdversion);
				if ($actionReturn) {
					if ($obju_HISItems->getudfvalue("u_salespricing")==1) {
						$objRs2->queryopen("select PRICELIST from PRICELISTS where BPTYPE='C'");
						while ($objRs2->queryfetchrow("NAME")) {
							if (!$objItemPriceLists->getbykey($objRs->fields["ITEMCODE"],$objRs2->fields["PRICELIST"])) {
								$objItemPriceLists->prepareadd();
								$objItemPriceLists->itemcode = $objRs->fields["ITEMCODE"];
								$objItemPriceLists->pricelist = $objRs2->fields["PRICELIST"];
								
							}
							if ($objRs2->fields["PRICELIST"]=="1") {
								$objItemPriceLists->price = round($objRs->fields["PRICE"]*(1+($obju_HISItems->getudfvalue("u_salesmarkup2")/100)),6);
							} else {
								$objItemPriceLists->price = round($objRs->fields["PRICE"]*(1+($obju_HISItems->getudfvalue("u_salesmarkup")/100)),6);
							}	
							$obju_HISItems->setudfvalue("u_scdiscamount",$objItemPriceLists->price*.2);
							if ($obju_HISItems->getudfvalue("u_isstat")==1) {
								$obju_HISItems->setudfvalue("u_scdiscamount",($objItemPriceLists->price + ($objItemPriceLists->price*($obju_HISItems->getudfvalue("u_statperc")/100)))*.2);
							}
							if ($objItemPriceLists->rowstat=="N") $actionReturn = $objItemPriceLists->add();
							$actionReturn = $objItemPriceLists->update($objItemPriceLists->itemcode, $objItemPriceLists->pricelist, $objItemPriceLists->rcdversion);
							if (!$actionReturn) break;
						}
						if ($actionReturn) $obju_HISItems->update($obju_HISItems->code,$obju_HISItems->rcdversion);
					}
				}			
			}	
			if (!$actionReturn) break;
		}
		
		
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventmarketingdocumentsUpdatePurchasePriceGPSTDCHI()");
		return $actionReturn;
	}	
	
?>
