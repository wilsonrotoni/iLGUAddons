<?php
        
 	$objRs1 = new recordset(null,$objConnection);
        
        $objRs1->queryopen("select A.U_ITDEPT from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["itdept"] = $objRs1->fields["U_ITDEPT"];
	}

	if (isEditMode()) {
		
		$objRs = new recordset(null,$objConnection);
		if ($page->getitemnumeric("u_partialpay")==0) {
			$objRs->queryopen("SELECT B.DOCNO, B.DOCSTATUS FROM U_LGUBILLS A INNER JOIN U_BPLAPPS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.U_APPNO WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$page->getitemstring("u_billno")."' and B.DOCSTATUS='Printing'");
			if ($objRs->queryfetchrow("NAME")) {
				$page->toolbar->addbutton("u_printbusinesspermit","Print Business Permit","printBusinessPermit()","left");
				$page->privatedata["bpno"] = $objRs->fields["DOCNO"];
			}
		}
		// && $page->getitemstring("u_trxtype")=="S"
		if ($page->getitemstring("u_status")=="O" && $page->getitemdecimal("u_creditedamount")==0) {
			$page->toolbar->addbutton("u_submit","Cancel","formCancelSales()","right");
		}	
		if ($page->getitemstring("u_status")=="D") {
			$page->toolbar->addbutton("u_submit","F9 - Save","formSubmitOpen()","right");
			$page->toolbar->addbutton("u_submitdraft","Update Draft","formSubmitDraft()","right");
		}
		//var_dump($page->getitemstring("u_status"));
		/*if ($_SESSION["suser"]=="Y" && $page->getitemstring("u_status")!="P" && $page->getitemstring("u_autopost")==0) {
				if ($page->getitemstring("u_status")=="D") {
				$objGrids[0]->columninput("u_freebie","type","checkbox");
				$objGrids[0]->columninput("u_freebie","value",1);
				
				$objGrids[0]->columninput("u_tofollow","type","checkbox");
				$objGrids[0]->columninput("u_tofollow","value",1);
				
				$objGrids[0]->columninput("u_selected","type","checkbox");
				$objGrids[0]->columninput("u_selected","value",1);
			
				$objGrids[0]->columninput("u_package","type","checkbox");
				$objGrids[0]->columninput("u_package","value",1);
				$page->toolbar->setaction("update",false);
			}
		} else if ($page->getitemstring("u_status")=="D") {	
			$objGrids[0]->columninput("u_freebie","type","checkbox");
			$objGrids[0]->columninput("u_freebie","value",1);
			
			$objGrids[0]->columninput("u_tofollow","type","checkbox");
			$objGrids[0]->columninput("u_tofollow","value",1);
			
			$objGrids[0]->columninput("u_selected","type","checkbox");
			$objGrids[0]->columninput("u_selected","value",1);
		
			$objGrids[0]->columninput("u_package","type","checkbox");
			$objGrids[0]->columninput("u_package","value",1);
		} else if ($page->getitemstring("u_status")!="D" || $page->getitemstring("u_autopost")==1) {
			$page->businessobject->items->seteditable("u_date",false);
			$page->businessobject->items->seteditable("u_orno",false);
			$page->businessobject->items->seteditable("u_custno",false);
			$page->businessobject->items->seteditable("u_custname",false);
			$page->businessobject->items->seteditable("u_billno",false);
			
			$page->businessobject->items->seteditable("u_ar",false);
			$page->businessobject->items->seteditable("u_collectedcashamount",false);
			$page->businessobject->items->seteditable("u_tfrefno",false);
			$page->businessobject->items->seteditable("u_tfbank",false);
			$page->businessobject->items->seteditable("u_tfbankbranch",false);
			$page->businessobject->items->seteditable("u_tfbankacctno",false);
			$page->businessobject->items->seteditable("u_tfamt",false);
			
			$objGrids[0]->dataentry=false;
			$objGrids[1]->dataentry=false;
			$objGrids[2]->dataentry=false;
			$objGrids[3]->dataentry=false;
			$page->toolbar->setaction("update",false);
		} else {
			$page->toolbar->setaction("update",false);
		}
		*/
		
		if ($page->getitemstring("u_status")!="D") {
			$page->businessobject->items->seteditable("u_billno",false);
			$page->businessobject->items->seteditable("u_partialpay",false);
			$objGrids[0]->dataentry=false;
		} 
		
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_orno",false);
		//$page->businessobject->items->seteditable("u_custno",false);
		$page->businessobject->items->seteditable("u_custname",false);
		$page->businessobject->items->seteditable("u_profitcenter",false);
		$page->businessobject->items->seteditable("u_billno",false);
		$page->businessobject->items->seteditable("u_partialpay",false);
		
		$page->businessobject->items->seteditable("u_ar",false);
		$page->businessobject->items->seteditable("u_collectedcashamount",false);
		$page->businessobject->items->seteditable("u_tfrefno",false);
		$page->businessobject->items->seteditable("u_tfbank",false);
		$page->businessobject->items->seteditable("u_tfbankbranch",false);
		$page->businessobject->items->seteditable("u_tfbankacctno",false);
		$page->businessobject->items->seteditable("u_tfamt",false);
		
		$objGrids[0]->dataentry=false;
		$objGrids[1]->dataentry=false;
		$objGrids[2]->dataentry=false;
		$objGrids[3]->dataentry=false;
		$page->toolbar->setaction("update",false);
	} else {
		$objGrids[0]->columninput("u_freebie","type","checkbox");
		$objGrids[0]->columninput("u_freebie","value",1);
		
		$objGrids[0]->columninput("u_tofollow","type","checkbox");
		$objGrids[0]->columninput("u_tofollow","value",1);
		
		$objGrids[0]->columninput("u_selected","type","checkbox");
		$objGrids[0]->columninput("u_selected","value",1);
	
		$objGrids[0]->columninput("u_package","type","checkbox");
		$objGrids[0]->columninput("u_package","value",1);

		$page->toolbar->addbutton("u_submit","F9 - Save","formSubmitOpen()","right");
		$page->toolbar->addbutton("u_submitdraft","Save as Draft","formSubmitDraft()","right");
                $page->toolbar->addbutton("u_queuing","Queuing","u_queuingGPSLGU()","left");
//		$page->toolbar->addbutton("u_submitdraft","Save as Draft","formSubmitDraft()","right");
//		$page->toolbar->addbutton("u_closeregister","Open Register","u_openRegisterGPSPOSAsddon()","right");
	}
	
	$objRs->queryopen("SELECT U_REF FROM U_LGUQUE WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_CTR='".$page->getitemstring("u_terminalid")."' and U_DATE='".currentdateDB()."' ORDER BY DOCNO DESC LIMIT 1");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_queue",$objRs->fields["U_REF"]);
	}

?> 

