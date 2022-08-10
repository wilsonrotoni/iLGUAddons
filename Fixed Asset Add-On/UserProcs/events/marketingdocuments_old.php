<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./inc/formutils.php");
	
	function onAddEventmarketingdocumentsGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		switch($objTable->objectcode) {
			case "APINVOICE":
				if ($objTable->docstatus!="D") {
					$actionReturn = onCustomEventmarketingdocumentsFAAPInvoiceGPSFixedAsset($objTable);
				}	
				break;
			case "PURCHASEDELIVERY":
				if ($objTable->docstatus!="D") {
					$actionReturn = onCustomEventmarketingdocumentsFAPurchaseDeliveryGPSFixedAsset($objTable);
				}	
				break;				
			case "GOODSISSUE":
				if ($objTable->docstatus!="D") {
					$actionReturn = onCustomEventmarketingdocumentsFAGoodsIssueGPSFixedAsset($objTable);
				}	
				break;								
			//if ($actionReturn) $objConnection->commit();
			//else $objConnection->rollback();
		}
		//if ($actionReturn) $actionReturn = raiseError("onAddEventmarketingdocumentsGPSFixedAsset");
		return $actionReturn;
	}

	function onUpdateEventmarketingdocumentsGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		switch($objTable->objectcode) {
			case "APINVOICE":
				if ($objTable->docstatus!=$objTable->fields["DOCSTATUS"] && $objTable->fields["DOCSTATUS"]=="D") {
					$actionReturn = onCustomEventmarketingdocumentsFAAPInvoiceGPSFixedAsset($objTable);
				}	
				break;
			case "PURCHASEDELIVERY":
				if ($objTable->docstatus!=$objTable->fields["DOCSTATUS"] && $objTable->fields["DOCSTATUS"]=="D") {
					$actionReturn = onCustomEventmarketingdocumentsFAPurchaseDeliveryGPSFixedAsset($objTable);
				}	
				break;				
			case "GOODSISSUE":
				if ($objTable->docstatus!=$objTable->fields["DOCSTATUS"] && $objTable->fields["DOCSTATUS"]=="D") {
					$actionReturn = onCustomEventmarketingdocumentsFAGoodsIssueGPSFixedAsset($objTable);
				}	
				break;								
			//if ($actionReturn) $objConnection->commit();
			//else $objConnection->rollback();
		}
		//if ($actionReturn) $actionReturn = raiseError("onAddEventmarketingdocumentsGPSFixedAsset");
		return $actionReturn;
	}
	
	function onCustomEventmarketingdocumentsFAAPInvoiceGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		$objRs = new recordset(NULL,$objConnection);
		$obju_FAXMTAL = new masterdatalinesschema_br(null,$objConnection,"u_faxmtal");
		if ($objTable->doctype=="S") {
			$objRs->setdebug();
			$objRs->queryopen("SELECT A.ITEMDESC, A.GLACCTNO, A.DOCID, A.LINEID, A.PRICE, (A.LINETOTAL-A.VATAMOUNT-A.LINETOTALDISC) AS ITEMCOST, A.DRCODE AS PROFITCENTER, A.PROJCODE, A.U_FAPROPERTY1, A.U_FAPROPERTY2, A.U_FAPROPERTY3, A.U_FAPROPERTY4, A.U_FAPROPERTY5 FROM APINVOICEITEMS A WHERE A.DOCID='$objTable->docid' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.DOCTYPE='S' AND A.U_FACAPITALIZE='Y' AND A.DOCID='$objTable->docid'");
			while ($objRs->queryfetchrow("NAME")) {
				$obju_FAXMTAL->prepareadd();
				$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
				$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMDESC"]);
				$obju_FAXMTAL->setudfvalue("u_srcdoc",$objTable->docno);
				$obju_FAXMTAL->setudfvalue("u_srcname","A/P Invoice");
				$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["LINEID"]);
				$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
				$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["ITEMCOST"]);
				$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
				$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
				$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
				$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
				$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
				$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
				$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
				$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
				$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);
				$actionReturn = $obju_FAXMTAL->add();
				if (!$actionReturn) break;
			}
		}	
		if ($actionReturn) {
			$objRs->queryopen("SELECT A.ITEMCODE, A.ITEMDESC, A.WHSCODE, A.GLACCTNO, A.DOCID, A.LINEID, A.QUANTITY, A.PRICE, ((A.LINETOTAL-A.VATAMOUNT-A.LINETOTALDISC)/A.QUANTITY) AS ITEMCOST, A.DRCODE AS PROFITCENTER, A.PROJCODE, A.U_FAPROPERTY1, A.U_FAPROPERTY2, A.U_FAPROPERTY3, A.U_FAPROPERTY4, A.U_FAPROPERTY5 FROM APINVOICEITEMS A, ITEMS B WHERE B.ITEMCODE=A.ITEMCODE AND A.DOCID='$objTable->docid' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.DOCTYPE='I' AND B.U_ISFIXEDASSET=1 AND A.ITEMMANAGEBY='0' AND A.BASETYPE<>'PURCHASEDELIVERY'");
			while ($objRs->queryfetchrow("NAME")) {
				//var_dump($objRs->fields["ITEMCODE"]);
				for ($ctr=1;$ctr<=$objRs->fields["QUANTITY"];$ctr++) {
					$obju_FAXMTAL->prepareadd();
					$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
					$obju_FAXMTAL->setudfvalue("u_itemcode",$objRs->fields["ITEMCODE"]);
					$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMDESC"]);
					$obju_FAXMTAL->setudfvalue("u_srcdoc",$objTable->docno);
					$obju_FAXMTAL->setudfvalue("u_srcname","A/P Invoice");
					$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["LINEID"]);
					$obju_FAXMTAL->setudfvalue("u_srcwhs",$objRs->fields["WHSCODE"]);
					$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
					$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["ITEMCOST"]);
					$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
					$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
					$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
					$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
					$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
					$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
					$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
					$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
					$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);
					$actionReturn = $obju_FAXMTAL->add();
					if (!$actionReturn) break;
				}	
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("SELECT A.ITEMCODE, A.ITEMNAME, A.WAREHOUSE, A.REFNO, A.REFLINEID, ((B.LINETOTAL-B.VATAMOUNT-B.LINETOTALDISC)/B.QUANTITY) AS ITEMCOST, B.DRCODE AS PROFITCENTER, B.PROJCODE, B.GLACCTNO, A.SERIALNO, A.MFRSERIALNO, B.U_FAPROPERTY1, B.U_FAPROPERTY2, B.U_FAPROPERTY3, B.U_FAPROPERTY4, B.U_FAPROPERTY5 FROM SERIALTRXS A, APINVOICEITEMS B, ITEMS C WHERE C.ITEMCODE=B.ITEMCODE AND B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID='$objTable->docid' AND B.LINEID=A.REFLINEID AND A.REFTYPE='AP' AND A.REFNO = '$objTable->docno' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND B.DOCTYPE='I' AND C.U_ISFIXEDASSET=1 AND B.ITEMMANAGEBY='2' AND B.BASETYPE<>'PURCHASEDELIVERY'");
			while ($objRs->queryfetchrow("NAME")) {
				$obju_FAXMTAL->prepareadd();
				$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
				$obju_FAXMTAL->setudfvalue("u_itemcode",$objRs->fields["ITEMCODE"]);
				$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMNAME"]);
				$obju_FAXMTAL->setudfvalue("u_srcdoc",$objRs->fields["REFNO"]);
				$obju_FAXMTAL->setudfvalue("u_srcname","A/P Invoice");
				$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["REFLINEID"]);
				$obju_FAXMTAL->setudfvalue("u_srcwhs",$objRs->fields["WAREHOUSE"]);
				$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
				$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["ITEMCOST"]);
				$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
				$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
				$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
				$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
				$obju_FAXMTAL->setudfvalue("u_serialno",$objRs->fields["SERIALNO"]);
				$obju_FAXMTAL->setudfvalue("u_mfrserialno",$objRs->fields["MFRSERIALNO"]);
				$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
				$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
				$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
				$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
				$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);						
				$actionReturn = $obju_FAXMTAL->add();
				if (!$actionReturn) break;
			}
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventmarketingdocumentsFAAPInvoiceGPSFixedAsset");
		return $actionReturn;
	}	
	
	function onCustomEventmarketingdocumentsFAPurchaseDeliveryGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		$objRs = new recordset(NULL,$objConnection);
		$obju_FAXMTAL = new masterdatalinesschema_br(null,$objConnection,"u_faxmtal");
		$objRs->setdebug();
		if ($actionReturn) {
			$objRs->queryopen("SELECT A.ITEMCODE, A.ITEMDESC, A.WHSCODE, A.GLACCTNO, A.DOCID, A.LINEID, A.QUANTITY, A.PRICE, ((A.LINETOTAL-A.VATAMOUNT-A.LINETOTALDISC)/A.QUANTITY) AS ITEMCOST, A.DRCODE AS PROFITCENTER, A.PROJCODE, A.U_FAPROPERTY1, A.U_FAPROPERTY2, A.U_FAPROPERTY3, A.U_FAPROPERTY4, A.U_FAPROPERTY5 FROM PURCHASEDELIVERYITEMS A, ITEMS B WHERE B.ITEMCODE=A.ITEMCODE AND A.DOCID='$objTable->docid' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.DOCTYPE='I' AND B.U_ISFIXEDASSET=1 AND A.ITEMMANAGEBY='0' AND A.BASETYPE<>'APINVOICE'");
			while ($objRs->queryfetchrow("NAME")) {
				for ($ctr=1;$ctr<=$objRs->fields["QUANTITY"];$ctr++) {
					$obju_FAXMTAL->prepareadd();
					$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
					$obju_FAXMTAL->setudfvalue("u_itemcode",$objRs->fields["ITEMCODE"]);
					$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMDESC"]);
					$obju_FAXMTAL->setudfvalue("u_srcdoc",$objTable->docno);
					$obju_FAXMTAL->setudfvalue("u_srcname","Goods Receipt PO");
					$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["LINEID"]);
					$obju_FAXMTAL->setudfvalue("u_srcwhs",$objRs->fields["WHSCODE"]);
					$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
					$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["ITEMCOST"]);
					$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
					$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
					$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
					$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
					$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
					$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
					$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
					$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
					$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);						
					$actionReturn = $obju_FAXMTAL->add();
					if (!$actionReturn) break;
				}	
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$objRs->queryopen("SELECT A.ITEMCODE, A.ITEMNAME, A.WAREHOUSE, A.REFNO, A.REFLINEID, B.PRICE, ((B.LINETOTAL-B.VATAMOUNT-B.LINETOTALDISC)/B.QUANTITY) AS ITEMCOST, B.DRCODE AS PROFITCENTER, B.PROJCODE, B.GLACCTNO, A.SERIALNO, A.MFRSERIALNO, B.U_FAPROPERTY1, B.U_FAPROPERTY2, B.U_FAPROPERTY3, B.U_FAPROPERTY4, B.U_FAPROPERTY5 FROM SERIALTRXS A, PURCHASEDELIVERYITEMS B, ITEMS C WHERE C.ITEMCODE=B.ITEMCODE AND B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID='$objTable->docid' AND B.LINEID=A.REFLINEID AND A.REFTYPE='PDN' AND A.REFNO = '$objTable->docno' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND B.DOCTYPE='I' AND C.U_ISFIXEDASSET=1 AND B.ITEMMANAGEBY='2' AND B.BASETYPE<>'APINVOICE'");
			while ($objRs->queryfetchrow("NAME")) {
				$obju_FAXMTAL->prepareadd();
				$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
				$obju_FAXMTAL->setudfvalue("u_itemcode",$objRs->fields["ITEMCODE"]);
				$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMNAME"]);
				$obju_FAXMTAL->setudfvalue("u_srcdoc",$objRs->fields["REFNO"]);
				$obju_FAXMTAL->setudfvalue("u_srcname","Goods Receipt PO");
				$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["REFLINEID"]);
				$obju_FAXMTAL->setudfvalue("u_srcwhs",$objRs->fields["WAREHOUSE"]);
				$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
				$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["ITEMCOST"]);
				$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
				$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
				$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
				$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
				$obju_FAXMTAL->setudfvalue("u_serialno",$objRs->fields["SERIALNO"]);
				$obju_FAXMTAL->setudfvalue("u_mfrserialno",$objRs->fields["MFRSERIALNO"]);
				$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
				$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
				$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
				$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
				$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);						
				$actionReturn = $obju_FAXMTAL->add();
				
				$actionReturn = $obju_FAXMTAL->add();
				if (!$actionReturn) break;
			}
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventmarketingdocumentsFAPurchaseDeliveryGPSFixedAsset");
		return $actionReturn;
	}	
	
	function onCustomEventmarketingdocumentsFAGoodsIssueGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
	
		$objRs = new recordset(NULL,$objConnection);
		$obju_FAXMTAL = new masterdatalinesschema_br(null,$objConnection,"u_faxmtal");
		$objRs->setdebug();
		if ($objTable->trxtype=="") {
			if ($actionReturn) {
				//$objRs->setdebug();
				$objRs->queryopen("SELECT A.ITEMCODE, A.ITEMDESC, A.WHSCODE, A.LINEID, A.QUANTITY, A.ITEMCOST, A.DRCODE AS PROFITCENTER, A.PROJCODE, A.U_FAPROPERTY1, A.U_FAPROPERTY2, A.U_FAPROPERTY3, A.U_FAPROPERTY4, A.U_FAPROPERTY5, A.GLACCTNO FROM GOODSISSUEITEMS A WHERE A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.ITEMMANAGEBY='0' AND A.U_FACAPITALIZE='Y' AND A.DOCID='$objTable->docid'");
				//var_dump($objRs->sqls);
				while ($objRs->queryfetchrow("NAME")) {
					for ($ctr=1;$ctr<=$objRs->fields["QUANTITY"];$ctr++) {
						//var_dump($objRs->fields);
						$obju_FAXMTAL->prepareadd();
						$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
						$obju_FAXMTAL->setudfvalue("u_itemcode",$objRs->fields["ITEMCODE"]);
						$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMDESC"]);
						$obju_FAXMTAL->setudfvalue("u_srcdoc",$objTable->docno);
						$obju_FAXMTAL->setudfvalue("u_srcname","Goods Issue");
						$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["LINEID"]);
						$obju_FAXMTAL->setudfvalue("u_srcwhs",$objRs->fields["WHSCODE"]);
						$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
						$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["ITEMCOST"]);
						$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
						$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
						$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
						$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
						$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
						$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
						$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
						$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
						$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);						
						$actionReturn = $obju_FAXMTAL->add();
						if (!$actionReturn) break;
					}	
					if (!$actionReturn) break;
				}
			}
			if ($actionReturn) {
				$objRs->queryopen("SELECT A.ITEMCODE, A.ITEMNAME, A.WAREHOUSE, A.REFNO, A.REFLINEID, B.PRICE, B.ITEMCOST, B.DRCODE AS PROFITCENTER, B.PROJCODE, B.GLACCTNO, A.SERIALNO, A.MFRSERIALNO, B.U_FAPROPERTY1, B.U_FAPROPERTY2, B.U_FAPROPERTY3, B.U_FAPROPERTY4, B.U_FAPROPERTY5 FROM SERIALTRXS A, GOODSISSUEITEMS B, ITEMS C WHERE C.ITEMCODE=B.ITEMCODE AND B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID='$objTable->docid' AND B.LINEID=A.REFLINEID AND A.REFTYPE='GI' AND A.REFNO = '$objTable->docno' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND B.ITEMMANAGEBY='2' AND B.U_FACAPITALIZE='Y'");
				while ($objRs->queryfetchrow("NAME")) {
					$obju_FAXMTAL->prepareadd();
					$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
					$obju_FAXMTAL->setudfvalue("u_itemcode",$objRs->fields["ITEMCODE"]);
					$obju_FAXMTAL->setudfvalue("u_itemdesc",$objRs->fields["ITEMNAME"]);
					$obju_FAXMTAL->setudfvalue("u_srcdoc",$objRs->fields["REFNO"]);
					$obju_FAXMTAL->setudfvalue("u_srcname","Goods Receipt PO");
					$obju_FAXMTAL->setudfvalue("u_srcline",$objRs->fields["REFLINEID"]);
					$obju_FAXMTAL->setudfvalue("u_srcwhs",$objRs->fields["WAREHOUSE"]);
					$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->docdate);
					$obju_FAXMTAL->setudfvalue("u_amount",$objRs->fields["PRICE"]);
					$obju_FAXMTAL->setudfvalue("u_cost",$objRs->fields["ITEMCOST"]);
					$obju_FAXMTAL->setudfvalue("u_profitcenter",$objRs->fields["PROFITCENTER"]);
					$obju_FAXMTAL->setudfvalue("u_projcode",$objRs->fields["PROJCODE"]);
					$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objRs->fields["GLACCTNO"]);
					$obju_FAXMTAL->setudfvalue("u_serialno",$objRs->fields["SERIALNO"]);
					$obju_FAXMTAL->setudfvalue("u_mfrserialno",$objRs->fields["MFRSERIALNO"]);
					$obju_FAXMTAL->setudfvalue("u_property1",$objRs->fields["U_FAPROPERTY1"]);
					$obju_FAXMTAL->setudfvalue("u_property2",$objRs->fields["U_FAPROPERTY2"]);
					$obju_FAXMTAL->setudfvalue("u_property3",$objRs->fields["U_FAPROPERTY3"]);
					$obju_FAXMTAL->setudfvalue("u_property4",$objRs->fields["U_FAPROPERTY4"]);
					$obju_FAXMTAL->setudfvalue("u_property5",$objRs->fields["U_FAPROPERTY5"]);						
					$actionReturn = $obju_FAXMTAL->add();
					if (!$actionReturn) break;
				}
			}
		}	
		
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventmarketingdocumentsFAGoodsIssueGPSFixedAsset");
		return $actionReturn;
	}	
	
?>