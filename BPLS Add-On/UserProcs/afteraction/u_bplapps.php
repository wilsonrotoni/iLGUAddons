<?php

	$objRs->queryopen("select A.U_BPLENCODER,A.U_FIREDEPT, A.U_BPLASSESSOR, A.U_BPLAPPROVER, A.U_BPLHOLD, A.U_BPLDELETE,  A.U_BPLRETIRE, A.U_BPLVIEWPAYMENTHISTORY  from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["bplencoder"] = $objRs->fields["U_BPLENCODER"];
		$page->privatedata["bplassessor"] = $objRs->fields["U_BPLASSESSOR"];
		$page->privatedata["bplapprover"] = $objRs->fields["U_BPLAPPROVER"];
		$page->privatedata["firedept"] = $objRs->fields["U_FIREDEPT"];
		$page->privatedata["bplretire"] = $objRs->fields["U_BPLRETIRE"];
		$page->privatedata["bplhold"] = $objRs->fields["U_BPLHOLD"];
		$page->privatedata["bpldelete"] = $objRs->fields["U_BPLDELETE"];
		$page->privatedata["bplviewpaymenthistory"] = $objRs->fields["U_BPLVIEWPAYMENTHISTORY"];
	}
        $page->privatedata["userid"] = $_SESSION["userid"] ;
        if ($_SESSION["userid"] != "bplcommon") {
            if ( $page->privatedata["bplassessor"]=="1" || $page->privatedata["bplapprover"]=="1") {
                if (($page->getitemstring("docstatus")=="Encoding" || $page->getitemstring("docstatus")=="Common" )) {
                            $page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSBPLS()","left");
                            $page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSBPLS()","left");
                            $page->businessobject->items->seteditable("u_approverremarks",true);
                }
            }
            if ($page->getitemstring("u_retired")!=1) $page->toolbar->addbutton("u_submit","F4 - SAVE","formSubmit()","left");
        } else {
            $page->toolbar->addbutton("u_submit","Submit","formSubmit()","left");
        }
//        var_dump($page->getitemstring("docstatus"));

	if ($page->privatedata["bplencoder"]=="1" && $page->getitemstring("u_preassbill")=="0" && $page->getitemstring("docstatus")=="Encoding" && $page->getitemstring("docstatus")=="Assessing") {
		$page->toolbar->addbutton("u_presassbill","Generate Pre-Assessment Bill","u_preAssBillGPSBPLS()","left");
	}
	if ($page->privatedata["bplassessor"]=="1" && ($page->getitemstring("docstatus")=="Encoding" || $page->getitemstring("docstatus")=="Assessed")){
		$page->toolbar->addbutton("u_ComputePenaltySurcharge","COMPUTE PENALTY","u_ComputePenaltySurchargeGPSBPLS()","right");
	}
        if ($page->getitemstring("u_appno")!= "") {
            $objRs->queryopen("SELECT MAX(U_PAYYEAR) as U_PAYYEAR FROM U_BPLLEDGER  WHERE COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' AND U_ACCTNO = '".$page->getitemstring("u_appno")."' AND U_ISCANCELLED <> 1");
            if ($objRs->queryfetchrow("NAME")) {
                    $page->setitem("lastpayyear",$objRs->fields["U_PAYYEAR"]);
                    $objRs->queryopen("SELECT MAX(U_QUARTER) as U_PAYQTR FROM U_BPLLEDGER  WHERE COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' AND U_ACCTNO = '".$page->getitemstring("u_appno")."' AND U_ISCANCELLED <> 1 AND U_PAYYEAR = '".$page->getitemstring("lastpayyear")."' and U_FEEID IN ('1','5','0001') ");
                        if ($objRs->queryfetchrow("NAME")) {
                                $page->setitem("lastpayqtr",$objRs->fields["U_PAYQTR"]);
                        }
            }
            $objRs->queryopen("select U_REGDATE from U_BPLMDS A WHERE COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' AND CODE = '".$page->getitemstring("u_appno")."'");
            if ($objRs->queryfetchrow("NAME")) {
                $page->setitem("regdate",formatDateToHttp($objRs->fields["U_REGDATE"]));
            }
            
        }
        if ($page->getitemstring("u_apptype") == "NEW") $page->businessobject->items->seteditable("u_assyearto",false);
        else $page->businessobject->items->seteditable("u_assyearto",true);    
        
        
        $objGrids[4]->addbutton("u_AddMultipleFees","[Add Multiple Fees]","u_MultipleFeesGPSBPLS()","left");
//        $objGrids[4]->dataentry = false;
        if ($page->privatedata["bplviewpaymenthistory"]=="1" ){
              $page->toolbar->addbutton("payments","PAYMENT HISTORY","u_PaymentHistoryGPSBPLS()","left");
        }
       
//        $page->toolbar->addbutton("oldpayments","Old Payment History","u_OLDPaymentHistoryGPSBPLS()","left");
//        $page->toolbar->addbutton("addpayments","Insert Payment History","showPopupInsertPaymentHistory()","left");
	$photopath ="";
        
//        $httpVars["viewpaymenthistory"] = false;
//        $httpVars["viewpaymenthistoryCaption"] = "Payment History 2 ";
//        $page->popup->addgroup("popupPrintTo");
//        $page->popup->additem("popupPrintTo","View Payment History","u_PaymentHistoryGPSBPLS()");
//        $page->popup->additem("popupPrintTo","View Old Payment History","u_OLDPaymentHistoryGPSBPLS()");
//        $page->popup->additem("popupPrintTo","Insert Payment History","u_InsertPaymentHistoryGPSBPLS()");
	if (isEditMode()) {
                if ($page->privatedata["bpldelete"]=="1" ){
                    $page->toolbar->addbutton("delete","Delete Record","showPopupDeleteBusiness()","right");
                }
                
                if ($page->privatedata["bplhold"]=="1" ){
                        if ($page->getitemstring("u_onhold") == 1) {
                            $page->toolbar->addbutton("unhold","Uncheck On-Hold","showPopupUnHoldBusiness()","right"); 
                        } else {
                            $page->toolbar->addbutton("onhold","Check On-Hold","u_OnHoldGPSBPLS()","right"); 
                        }
                }
                
                if ($page->privatedata["bplretire"]=="1" ){
                        if ($page->getitemstring("u_retired") == 1) {
                            $page->toolbar->addbutton("unretire","Un-Retire Business","showPopupUnRetireBusiness()","right"); 
                        } else {
                            $page->toolbar->addbutton("retire","Retire Business","u_PopUpRetireGPSBPLS()","right"); 
                        }
                }
                
                if ($page->getitemstring("u_apptype") == "NEW") $page->businessobject->items->seteditable("u_assyearto",false);
                else  { 
                    if ($page->getitemstring("docstatus") == "Encoding") $page->businessobject->items->seteditable("u_assyearto",true); 
                    else $page->businessobject->items->seteditable("u_assyearto",false);
                }    
                
                $page->businessobject->items->seteditable("u_appno",false);
		$objRs->queryopen("select DOCNO from U_BPLAPPS A WHERE COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and U_BUSINESSNAME='".addslashes($page->getitemstring("u_businessname"))."' and U_APPDATE<'".$page->getitemdate("u_appdate")."' AND DOCSTATUS in ('Approved','Paid') ORDER BY U_APPDATE DESC");
		
                if ($objRs->queryfetchrow("NAME")) {
                    $page->setitem("prevappno",$objRs->fields["DOCNO"]);
                    $page->setitem("u_prevappno",$objRs->fields["DOCNO"]);
		}
                
		
		if ($page->getitemstring("u_preassbill")=="1") {
                    $objGrids[2]->columninput("u_amount","type","label");
                    $objGrids[3]->columninput("u_amount","type","label");
                        
                    if ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) {   
                    } else {
                        if ($page->privatedata["bplencoder"]=="1") $page->toolbar->addbutton("re-assess-prebill","Re Assess Pre-Assessment Bill","u_prebillreassessGPSBPLS()","left");
                    }               
                }
		if ($_SESSION["userid"] != 'bplcommon') {
                       
                        if ($page->getitemstring("docstatus")=="Encoding" || $page->getitemstring("docstatus")=="Common" ) {
                                if ( $page->privatedata["bplassessor"]=="1" || $page->privatedata["bplapprover"]=="1") {
                                    $page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSBPLS()","left");
                                    $page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSBPLS()","left");
                                    $page->businessobject->items->seteditable("u_approverremarks",true);
                                    
                                    $page->toolbar->addbutton("u_ComputePenaltySurcharge","Compute Penalty","u_ComputePenaltySurchargeGPSBPLS()","right");
                                }
        //			if ($page->privatedata["bplencoder"]=="1") $page->toolbar->addbutton("forassessment","For Assessment","u_forAssessmentGPSBPLS()","left");
        //		} elseif ($page->getitemstring("docstatus")=="Assessing") { 
        //                    if ($page->privatedata["bplassessor"]=="1") $page->toolbar->addbutton("forapproval","F6 - For Approval","u_forApprovalGPSBPLS()","left");
                        } else if ($page->getitemstring("docstatus")=="Assessed") { 
//                                if ($page->privatedata["bplapprover"]=="1") {
        //				$page->toolbar->addbutton("approve","F7 - Approve","u_approveGPSBPLS()","left");
        //				$page->toolbar->addbutton("disapprove","F9 - Disapprove","u_disapproveGPSBPLS()","left");
        //				$page->businessobject->items->seteditable("u_approverremarks",true);
//                                }	
                        } else if ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" || $page->getitemstring("docstatus")=="CTO_Approved"  ) { 
                                $objGrids[0]->dataentry = false;
                                $objGrids[4]->dataentry = false;
                                $page->businessobject->items->seteditable("u_isonlineapp",false);
                                $page->businessobject->items->seteditable("u_reqappno",false);
                                if ($page->privatedata["bplapprover"]=="1") {
                                      
                                        $page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSBPLS()","left");
                                        $page->businessobject->items->seteditable("u_approverremarks",true);
                                } 
                        } else if ( $page->getitemstring("docstatus")=="Paid" || $page->getitemstring("docstatus")=="Printing" ){
                                $objGrids[0]->dataentry = true;
                                $objGrids[4]->dataentry = false;
                                $page->businessobject->items->seteditable("u_reqappno",false);
                                $page->toolbar->addbutton("billadjustments","AUDITED GROSS","showPopupAuditedGross()","left");
                        }
                }
                
		$qrpath = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/BPLSQR/".$page->getitemstring("u_appno").".png";
		$photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/BPLS/".$page->getitemstring("docno")."/photo";
		$page->privatedata["photopath"] = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/BPLS/".$page->getitemstring("docno");
		//var_dump($page->privatedata["photopath"]);
		if (file_exists($photopath . ".png")) $photopath .= ".png?version=".date('YmdHis');
		elseif (file_exists($photopath . ".jpg")) $photopath .= ".jpg?version=".date('YmdHis');
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif?version=".date('YmdHis');
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp?version=".date('YmdHis');
		else $photopath = "./imgs/photo.jpg";
	} 

	$page->privatedata["photodata"] = "";
	
	//$page->toolbar->setaction("print",false);
	$objRs = new recordset(null,$objConnection);
        $objRs->queryopen("select A.U_BPLPFOFEEAMT,A.U_BPLPLATEFEEAMT from U_LGUSETUP A");
        if ($objRs->queryfetchrow("NAME")) {
                $page->setitem("u_pfofee",$objRs->fields["U_BPLPFOFEEAMT"]);
                $page->setitem("u_platefee",$objRs->fields["U_BPLPLATEFEEAMT"]);
        }
        
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select A.U_INCFIREBUSINESS , A.U_INCFIRESTARTYEAR ,A.U_BPLKINDCHARLINK, A.U_BPLCATEGSANITARYPERMITLINK, A.U_BPLCATEGFIREINSFEELINK, A.U_ANNUALTAX, D.NAME AS U_ANNUALTAXDESC, A.U_GARBAGEFEE, B.NAME AS U_GARBAGEFEEDESC, A.U_MAYORSPERMIT, C.NAME AS U_MAYORSPERMITDESC, A.U_SANITARYINSPECTIONFEE, E.NAME AS U_SANITARYINSPECTIONFEEDESC, A.U_BPLSANITARYPERMIT, F.NAME AS U_BPLSANITARYPERMITDESC, A.U_BPLFIREINSFEE, G.NAME AS U_BPLFIREINSFEEDESC, A.U_BPLPFOFEE, H.NAME AS U_BPLPFOFEEDESC, A.U_BPLSFHEFEE, I.NAME AS U_BPLSFHEFEEDESC, A.U_BPLPLATEFEE, J.NAME AS U_BPLPLATEFEEDESC
						from U_LGUSETUP A
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_GARBAGEFEE
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_MAYORSPERMIT
							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_ANNUALTAX
							LEFT JOIN U_LGUFEES E ON E.CODE=A.U_SANITARYINSPECTIONFEE
							LEFT JOIN U_LGUFEES F ON F.CODE=A.U_BPLSANITARYPERMIT
							LEFT JOIN U_LGUFEES G ON G.CODE=A.U_BPLFIREINSFEE
							LEFT JOIN U_LGUFEES H ON H.CODE=A.U_BPLPFOFEE
							LEFT JOIN U_LGUFEES I ON I.CODE=A.U_BPLSFHEFEE
							LEFT JOIN U_LGUFEES J ON J.CODE=A.U_BPLPLATEFEE                                                    
							");
//	
//        $objRs->queryopen("select A.U_BPLKINDCHARLINK, A.U_BPLCATEGSANITARYPERMITLINK, A.U_BPLCATEGFIREINSFEELINK, A.U_ANNUALTAX, D.NAME AS U_ANNUALTAXDESC, A.U_GARBAGEFEE, B.NAME AS U_GARBAGEFEEDESC, A.U_MAYORSPERMIT, C.NAME AS U_MAYORSPERMITDESC, A.U_SANITARYINSPECTIONFEE, E.NAME AS U_SANITARYINSPECTIONFEEDESC, A.U_BPLSANITARYPERMIT, F.NAME AS U_BPLSANITARYPERMITDESC, A.U_BPLFIREINSFEE, G.NAME AS U_BPLFIREINSFEEDESC, A.U_BPLPFOFEE, H.NAME AS U_BPLPFOFEEDESC, A.U_BPLSFHEFEE, I.NAME AS U_BPLSFHEFEEDESC, A.U_BPLPLATEFEE, J.NAME AS U_BPLPLATEFEEDESC, A.U_BPLTAXCLEARANCE, k.NAME AS U_BPLTAXCLEARANCEFEEDESC
//						from U_LGUSETUP A
//							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_GARBAGEFEE
//							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_MAYORSPERMIT
//							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_ANNUALTAX
//							LEFT JOIN U_LGUFEES E ON E.CODE=A.U_SANITARYINSPECTIONFEE
//							LEFT JOIN U_LGUFEES F ON F.CODE=A.U_BPLSANITARYPERMIT
//							LEFT JOIN U_LGUFEES G ON G.CODE=A.U_BPLFIREINSFEE
//							LEFT JOIN U_LGUFEES H ON H.CODE=A.U_BPLPFOFEE
//							LEFT JOIN U_LGUFEES I ON I.CODE=A.U_BPLSFHEFEE
//							LEFT JOIN U_LGUFEES J ON J.CODE=A.U_BPLPLATEFEE
//                                                        LEFT JOIN U_LGUFEES K ON K.CODE=A.U_BPLTAXCLEARANCE
//							");
        
        if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["incfirebusiness"] = $objRs->fields["U_INCFIREBUSINESS"];
		$page->privatedata["incfirestartyear"] = $objRs->fields["U_INCFIRESTARTYEAR"];
		$page->privatedata["bplkindcharlink"] = $objRs->fields["U_BPLKINDCHARLINK"];
		$page->privatedata["bplcategsanitarypermitlink"] = $objRs->fields["U_BPLCATEGSANITARYPERMITLINK"];
		$page->privatedata["bplcategfireinsfeelink"] = $objRs->fields["U_BPLCATEGFIREINSFEELINK"];
		$page->privatedata["annualtaxcode"] = $objRs->fields["U_ANNUALTAX"];
		$page->privatedata["annualtaxdesc"] = $objRs->fields["U_ANNUALTAXDESC"];
		$page->privatedata["garbagefeecode"] = $objRs->fields["U_GARBAGEFEE"];
		$page->privatedata["garbagefeedesc"] = $objRs->fields["U_GARBAGEFEEDESC"];
		$page->privatedata["mayorspermitcode"] = $objRs->fields["U_MAYORSPERMIT"];
		$page->privatedata["mayorspermitdesc"] = $objRs->fields["U_MAYORSPERMITDESC"];
		$page->privatedata["sanitaryinspectionfeecode"] = $objRs->fields["U_SANITARYINSPECTIONFEE"];
		$page->privatedata["sanitaryinspectionfeedesc"] = $objRs->fields["U_SANITARYINSPECTIONFEEDESC"];
		$page->privatedata["sanitarypermitcode"] = $objRs->fields["U_BPLSANITARYPERMIT"];
		$page->privatedata["sanitarypermitdesc"] = $objRs->fields["U_BPLSANITARYPERMITDESC"];
		$page->privatedata["fireinspectionfeecode"] = $objRs->fields["U_BPLFIREINSFEE"];
		$page->privatedata["fireinspectionfeedesc"] = $objRs->fields["U_BPLFIREINSFEEDESC"];
		$page->privatedata["pfofeecode"] = $objRs->fields["U_BPLPFOFEE"];
		$page->privatedata["pfofeedesc"] = $objRs->fields["U_BPLPFOFEEDESC"];
		$page->privatedata["sfhefeecode"] = $objRs->fields["U_BPLSFHEFEE"];
		$page->privatedata["sfhefeedesc"] = $objRs->fields["U_BPLSFHEFEEDESC"];
		$page->privatedata["platefeecode"] = $objRs->fields["U_BPLPLATEFEE"];
		$page->privatedata["platefeedesc"] = $objRs->fields["U_BPLPLATEFEEDESC"];
		//$page->privatedata["tcfeecode"] = $objRs->fields["U_BPLTAXCLEARANCE"];
		//$page->privatedata["tcfeedesc"] = $objRs->fields["U_BPLTAXCLEARANCEFEEDESC"];
	}

	$objMaster->reportaction = "QS";
        if($page->getitemstring("u_retired") == "1" ){
            $pageHeaderStatusText = "Status: Retired";
        }else{
            $pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
        }
	
?> 

