<?php
        
 	$objRs1 = new recordset(null,$objConnection);
        
        $objRs1->queryopen("select A.U_ITDEPT from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["itdept"] = $objRs1->fields["U_ITDEPT"];
                $page->privatedata["userid"] = $_SESSION["userid"];
	}
        
        $objRs2 = new recordset(null,$objConnection);
        
        $objRs2->queryopen("SELECT A.U_CTCBASICFEE,B.NAME AS U_CTCBASICNAME,B.U_AMOUNT AS U_CTCBASICAMOUNT,A.U_CTCGROSSFEE,C.NAME AS U_CTCGROSSFEENAME,B.U_PENALTYCODE AS U_CTCPENFEECODE,B.U_PENALTYDESC AS U_CTCPENFEENAME
                                  ,A.U_TRANSFERTAXFEE,D.NAME AS U_TRANSFERTAXFEENAME,D.U_PENALTYCODE AS U_TRANSFERTAXPENFEECODE,D.U_PENALTYDESC AS U_TRANSFERTAXPENFEENAME
                                  ,A.U_TRANSFERTAXINTFEE,E.NAME AS U_TRANSFERTAXINTFEENAME
                                  ,A.U_FRANCHISETAXFEE,G.NAME AS U_FRANCHISETAXFEENAME,G.U_PENALTYCODE AS U_FRANCHISETAXPENFEECODE,G.U_PENALTYDESC AS U_FRANCHISETAXPENFEENAME
                                  ,A.U_FRANCHISETAXINTFEE,H.NAME AS U_FRANCHISETAXINTFEENAME
                                  ,A.U_PROCESSINGFEE,F.NAME AS U_PROCESSINGFEENAME,F.U_AMOUNT AS U_PROCESSINGFEEAMOUNT
                                FROM U_LGUSETUP A     
                                LEFT JOIN U_LGUFEES B ON B.CODE=A.U_CTCBASICFEE
                                LEFT JOIN U_LGUFEES C ON C.CODE=A.U_CTCGROSSFEE
                                LEFT JOIN U_LGUFEES D ON D.CODE=A.U_TRANSFERTAXFEE
                                LEFT JOIN U_LGUFEES E ON E.CODE=A.U_TRANSFERTAXINTFEE
                                LEFT JOIN U_LGUFEES F ON F.CODE=A.U_PROCESSINGFEE
                                LEFT JOIN U_LGUFEES G ON G.CODE=A.U_FRANCHISETAXFEE
                                LEFT JOIN U_LGUFEES H ON H.CODE=A.U_FRANCHISETAXINTFEE ");
        
	if ($objRs2->queryfetchrow("NAME")) {
		$page->privatedata["ctcbasicfeecode"] = $objRs2->fields["U_CTCBASICFEE"];
		$page->privatedata["ctcbasicfeename"] = $objRs2->fields["U_CTCBASICNAME"];
		$page->privatedata["ctcbasicfeeamount"] = $objRs2->fields["U_CTCBASICAMOUNT"];
                
		$page->privatedata["ctcpenfeecode"] = $objRs2->fields["U_CTCPENFEECODE"];
		$page->privatedata["ctcpenfeename"] = $objRs2->fields["U_CTCPENFEENAME"];
                
		$page->privatedata["ctcgrossfeecode"] = $objRs2->fields["U_CTCGROSSFEE"];
		$page->privatedata["ctcgrossfeename"] = $objRs2->fields["U_CTCGROSSFEENAME"];
                
		$page->privatedata["transfertaxfeecode"] = $objRs2->fields["U_TRANSFERTAXFEE"];
		$page->privatedata["transfertaxfeename"] = $objRs2->fields["U_TRANSFERTAXFEENAME"];
                
		$page->privatedata["transfertaxpenfeecode"] = $objRs2->fields["U_TRANSFERTAXPENFEECODE"];
		$page->privatedata["transfertaxpenfeename"] = $objRs2->fields["U_TRANSFERTAXPENFEENAME"];
                
		$page->privatedata["transfertaxintfeecode"] = $objRs2->fields["U_TRANSFERTAXINTFEE"];
		$page->privatedata["transfertaxintfeename"] = $objRs2->fields["U_TRANSFERTAXINTFEENAME"];
                
		$page->privatedata["franchisetaxfeecode"] = $objRs2->fields["U_FRANCHISETAXFEE"];
		$page->privatedata["franchisetaxfeename"] = $objRs2->fields["U_FRANCHISETAXFEENAME"];
                
		$page->privatedata["franchisetaxpenfeecode"] = $objRs2->fields["U_FRANCHISETAXPENFEECODE"];
		$page->privatedata["franchisetaxpenfeename"] = $objRs2->fields["U_FRANCHISETAXPENFEENAME"];
                
		$page->privatedata["franchisetaxintfeecode"] = $objRs2->fields["U_FRANCHISETAXINTFEE"];
		$page->privatedata["franchisetaxintfeename"] = $objRs2->fields["U_FRANCHISETAXINTFEENAME"];
                
		$page->privatedata["processingfeecode"] = $objRs2->fields["U_PROCESSINGFEE"];
		$page->privatedata["processingfeename"] = $objRs2->fields["U_PROCESSINGFEENAME"];
		$page->privatedata["processingfeeamount"] = $objRs2->fields["U_PROCESSINGFEEAMOUNT"];
                
		$page->privatedata["year"] = date('Y');
	}
        
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("select A.U_MANUALPOSTINGFLAG from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
                $page->privatedata["manualpostingflag"] = $objRs->fields["U_MANUALPOSTINGFLAG"];
	}
        if ($page->privatedata["manualpostingflag"] != 1){
                $page->businessobject->items->seteditable("u_ismanualposting",false);
        } else {
                $page->businessobject->items->seteditable("u_ismanualposting",true);
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
//			$page->toolbar->addbutton("u_submit","Cancel","formCancelSales()","right");
		}	
		if ($page->getitemstring("u_status")=="D") {
//			$page->toolbar->addbutton("u_submit","F9 - Save","formSubmitOpen()","right");
//			$page->toolbar->addbutton("u_submitdraft","Update Draft","formSubmitDraft()","right");
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
			$objGrids[5]->dataentry=false;
		} 
                if ($page->getitemstring("docstatus")=="CN") {
			$canceloption = false;
//			$page->businessobject->items->setvisible("u_cancelledby",true);
//			$page->businessobject->items->setvisible("u_arcmdocno",true);
//			$page->businessobject->items->setvisible("u_apcmdocno",true);
//			$page->businessobject->items->setvisible("u_jvcndocno",true);
//			$page->businessobject->items->setvisible("u_cancelledremarks",true);
		}
		
		$page->businessobject->items->seteditable("u_ismanualposting",false);
		$page->businessobject->items->seteditable("u_isonlinepayment",false);
		$page->businessobject->items->seteditable("u_docseries",false);
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_orno",false);
		$page->businessobject->items->seteditable("u_custno",false);
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
		//$page->toolbar->setaction("update",false);
	} else {
		$objGrids[0]->columninput("u_freebie","type","checkbox");
		$objGrids[0]->columninput("u_freebie","value",1);
		
		$objGrids[0]->columninput("u_tofollow","type","checkbox");
		$objGrids[0]->columninput("u_tofollow","value",1);
		
		$objGrids[0]->columninput("u_selected","type","checkbox");
		$objGrids[0]->columninput("u_selected","value",1);
	
		$objGrids[0]->columninput("u_package","type","checkbox");
		$objGrids[0]->columninput("u_package","value",1);

//		$page->toolbar->addbutton("u_submit","F9 - Save","formSubmitOpen()","right");
//		$page->toolbar->addbutton("u_submitdraft","Save as Draft","formSubmitDraft()","right");
                //$page->toolbar->addbutton("u_queuing","Create ePayment","u_JSONSampleGPSPOS()","left");
                //$page->toolbar->addbutton("u_epayment","Search ePayment","u_EpaymentSampleGPSPOS()","left");
//		$page->toolbar->addbutton("u_ctcapps","F1 - Community Tax Certificate","popupCommunitaxCertificate()","left");
		$page->toolbar->addbutton("u_categoryfees","F2 - Fees Categories","CopyFromCategories()","left");
		$page->toolbar->addbutton("u_unpaidbills","Unpaid Bills","ListofUnpaidBills()","left");
//		$page->toolbar->addbutton("u_searchgeobpas","GeoData BPAS","loadGeoDataBPAS()","left");
//		$page->toolbar->addbutton("u_closeregister","Open Register","u_openRegisterGPSPOSAsddon()","right");
                $httpVars["copydocumentfrom"] = true;
                $httpVars["copydocumentfromCaption"] = "Transactions ";
                $page->popup->addgroup("popupCopyDocumentFrom");
                $page->popup->additem("popupCopyDocumentFrom","Building Permit(Geodata)","loadGeoDataBPAS()");
                $page->popup->additem("popupCopyDocumentFrom","Fire Safety","ListofUnpaidBillsFireSafety()");
                $page->popup->additem("popupCopyDocumentFrom","Locational Clearance","ListofUnpaidBillsLocational()");
                $page->popup->additem("popupCopyDocumentFrom","Contractors Tax","ListofUnpaidBillsBLDG()");
                $page->popup->additem("popupCopyDocumentFrom","Transfer Tax","popupTransferTax()");
                $page->popup->additem("popupCopyDocumentFrom","Franchise Tax","popupFranchiseTax()");

//                $page->popup->additem("popupCopyDocumentFrom","Community Tax Certificate","popupCommunitaxCertificate()");
//                $page->popup->additem("popupCopyDocumentFrom","Fees & Charges Categories","CopyFromCategories()");
//                $page->popup->additem("popupCopyDocumentFrom","List of Unpaid Bills","ListofUnpaidBills()");
	}
	
	$objRs->queryopen("SELECT U_REF FROM U_LGUQUE WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_CTR='".$page->getitemstring("u_terminalid")."' and U_DATE='".currentdateDB()."' ORDER BY DOCNO DESC LIMIT 1");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_queue",$objRs->fields["U_REF"]);
	}

?> 

