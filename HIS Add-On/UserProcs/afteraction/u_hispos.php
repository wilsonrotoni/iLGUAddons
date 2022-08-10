<?php
	if ($page->getitemstring("u_trxtype")=="") {
		$page->objectdoctype = "INCOMINGPAYMENT";
		$pageHeader = "Cash Sales - Official Receipt";
	} else {
		$page->objectdoctype = "CREDITMEMO";
		$pageHeader = "Cash Sales - Credit Memo";
	}	
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_doctime",false);
		$page->businessobject->items->seteditable("u_patientid",false);
		$page->businessobject->items->seteditable("u_patientname",false);
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_payreftype",false);
		$page->businessobject->items->seteditable("u_payrefno",false);
		$page->businessobject->items->seteditable("u_recvamount",false);
		$page->businessobject->items->seteditable("u_bank",false);
		$page->businessobject->items->seteditable("u_checkno",false);
		$page->businessobject->items->seteditable("u_checkamount",false);
		$page->businessobject->items->seteditable("u_disccode",false);
		$page->businessobject->items->seteditable("u_pricelist",false);
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_colltype",false);
		$objGrids[0]->dataentry = false;
		$objGrids[2]->dataentry = false;
		$objGrids[3]->columnvisibility("u_selected",false);
//		if ($page->getitemstring("docstatus")=="C" && ($page->getitemstring("u_payreftype")=="RS" || $page->getitemstring("u_payreftype")=="SI")) {
		if ($page->getitemstring("u_automanage")=="1") {
			$canceloption = false;
		}
		if ($page->getitemstring("docstatus")=="CN") {
			$canceloption = false;
			$page->businessobject->items->setvisible("u_cancelledby",true);
			$page->businessobject->items->setvisible("u_arcmdocno",true);
			$page->businessobject->items->setvisible("u_apcmdocno",true);
			$page->businessobject->items->setvisible("u_jvcndocno",true);
			$page->businessobject->items->setvisible("u_cancelledremarks",true);
		}
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			$canceloption = false;
			$page->toolbar->setaction("update",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("find",false);
			$page->toolbar->setaction("navigation",false);
		}
		
	} else {
		if ($page->getitemstring("u_trxtype")=="PHARMACY") {
			$page->businessobject->items->seteditable("u_payreftype",false);
		}
		if ($page->getitemstring("u_reftype")=="WI") {
			$page->businessobject->items->seteditable("u_refno",false);
		}
		if ($page->getitemstring("u_trxtype")!="CM") {
			$page->businessobject->items->seteditable("u_docdate",false);
			$page->businessobject->items->seteditable("u_doctime",false);
		}	

	}
	$objRs = new recordset(null,$objConnection); 
	$objRs->queryopen("select A.CODE, IFNULL((B.NEXTID-1),0) AS U_QUEUENO from U_HISQUETERMINALS A LEFT JOIN DOCIDS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND DOCTYPE='u_hisque1".date('Ymd')."' where A.BRANCH='".$_SESSION["branch"]."' AND A.NAME='".$_SERVER['REMOTE_ADDR']."' AND A.U_QUEGROUP='1'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_terminalid",$objRs->fields["CODE"]);
		$page->setitem("u_autoqueue",1);
		$page->setitem("u_queue", $objRs->fields["U_QUEUENO"]);
	}
	
?>