<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./inc/formutils.php");
	
	function onAddEventjournalvoucheritemsGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($objTable->getudfvalue("u_facapitalize")=="Y" && $objTable->privatedata["header"]->docstatus!="D") {
			$actionReturn = onCustomEventjournalvoucheritemsFAJVItemGPSFixedAsset($objTable);
		}	
		//if ($actionReturn) $actionReturn = raiseError("onAddEventjournalvoucheritemsGPSFixedAsset");
		return $actionReturn;
	}

	function onUpdateEventjournalvoucheritemsGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($objTable->getudfvalue("u_facapitalize")=="Y" && $objTable->privatedata["header"]->docstatus!="D" && $objTable->privatedata["header"]->docstatus!=$objTable->privatedata["header"]->fields["DOCSTATUS"]) {
			$actionReturn = onCustomEventjournalvoucheritemsFAJVItemGPSFixedAsset($objTable);
		}	
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventjournalvoucheritemsGPSFixedAsset");
		return $actionReturn;
	}
	
	function onDeleteEventjournalvoucheritemsGPSFixedAsset($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($objTable->getudfvalue("u_facapitalize")=="Y" && $objTable->privatedata["header"]->docstatus!="D") {
			$actionReturn = onCustomEventjournalvoucheritemsFAJVItemGPSFixedAsset($objTable,true);
		}	
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventjournalvoucheritemsGPSFixedAsset");
		return $actionReturn;
	}

	function onCustomEventjournalvoucheritemsFAJVItemGPSFixedAsset($objTable,$delete=false) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		$objRs = new recordset(NULL,$objConnection);
		$obju_FAXMTAL = new masterdatalinesschema_br(null,$objConnection,"u_faxmtal");
		if (!$delete) {
			$obju_FAXMTAL->prepareadd();
			$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
			$obju_FAXMTAL->setudfvalue("u_itemdesc",$objTable->itemname);
			$obju_FAXMTAL->setudfvalue("u_srcdoc",$objTable->privatedata["header"]->docno);
			$obju_FAXMTAL->setudfvalue("u_srcname","Journal Voucher");
			$obju_FAXMTAL->setudfvalue("u_srcline",$objTable->lineid);
			$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->privatedata["header"]->docdate);
			$obju_FAXMTAL->setudfvalue("u_amount",$objTable->debit-$objTable->credit);
			$obju_FAXMTAL->setudfvalue("u_cost",$objTable->debit-$objTable->credit);
			$obju_FAXMTAL->setudfvalue("u_profitcenter",$objTable->profitcenter);
			$obju_FAXMTAL->setudfvalue("u_projcode",$objTable->projcode);
			$obju_FAXMTAL->setudfvalue("u_contraglacctno",$objTable->itemno);
			$actionReturn = $obju_FAXMTAL->add();
		} else {
			if ($obju_FAXMTAL->getbysql("U_SRCDOC='".$objTable->privatedata["header"]->docno."' AND U_SRCLINE='$objTable->lineid'")) {
				if ($obju_FAXMTAL->getudfvalue("u_status")=="") {
					$obju_FAXMTAL->delete();
				} else $actionReturn = raiseError("Journal Voucher No. [".$objTable->privatedata["header"]->docno."] and Line ID [$objTable->lineid] was already acquired in Fixed Asset module.");
			} else $actionReturn = raiseError("Unable to retrieve Journal Voucher No. [".$objTable->privatedata["header"]->docno."] and Line ID [$objTable->lineid] in Fixed Asset Aquisition table.");
		}		
		
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventjournalvoucheritemsFAJVItemGPSFixedAsset");
		return $actionReturn;
	}	
	

	
?>