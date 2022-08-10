<?php

	$objRs->queryopen("select A.U_MTOPENCODER, A.U_MTOPASSESSOR, A.U_MTOPAPPROVER,A.SUSER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["mtopencoder"] = $objRs->fields["U_MTOPENCODER"];
		$page->privatedata["mtopassessor"] = $objRs->fields["U_MTOPASSESSOR"];
		$page->privatedata["mtopapprover"] = $objRs->fields["U_MTOPAPPROVER"];
		$page->privatedata["suser"] = $objRs->fields["SUSER"];
	}
	
	if ($page->privatedata["suser"]=="Y" ){
				
				$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
	}

	
	if ($page->privatedata["mtopencoder"]=="1" || $page->privatedata["mtopassessor"]=="1" || $page->privatedata["mtopapprover"]=="1") {
		if ($page->getitemstring("docstatus")!="Approved" && $page->getitemstring("docstatus")!= "Disapproved" ) { 
				
				$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
			
			
		}	
	}

	if (isEditMode()) {	
		$page->businessobject->items->seteditable("u_franchiseno",false);

		$page->businessobject->items->seteditable("u_apptype",false);
		$page->businessobject->items->seteditable("u_franchiseseries",false);
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("u_caseseries",false);
		$page->businessobject->items->seteditable("u_caseno",false);
		if ($page->getitemstring("docstatus")=="Encoding") {
			if ($page->privatedata["mtopencoder"]=="1") $page->toolbar->addbutton("forassessment","For Assessment","u_forAssessmentGPSMTOP()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessing") { 
			if ($page->privatedata["mtopassessor"]=="1") $page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSMTOP()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
			if ($page->privatedata["mtopapprover"]=="1") {
				$page->toolbar->addbutton("approve","Approve","u_approveGPSMTOP()","left");
				$page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSMTOP()","left");
			}	
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$objGrids[0]->dataentry = false;
			//$objGrids[4]->dataentry = false;
			//$page->setvar("formAccess","R");
			if ($page->privatedata["mtopapprover"]=="1") {
				$page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSMTOP()","left");
//				$page->businessobject->items->seteditable("u_approverremarks",true);
			}	
		}
	} else {
		if ($page->getitemstring("u_apptype")=="RENEW" || $page->getitemstring("u_apptype")=="UPDATE") {
			$page->businessobject->items->seteditable("u_apptype",false);
			$page->businessobject->items->seteditable("u_franchiseseries",false);
		}
		if ($page->getitemstring("u_franchiseseries")!="-1") {
			$page->businessobject->items->seteditable("u_franchiseno",false);
		}
	}

	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select A.U_MTOPCOMBINEPERMITFRANCHISE, A.U_MTOPFRANCHISEREVOKEMONTH, A.U_MTOPFRANCHISEFEEAMT, A.U_MTOPFRANCHISEFEE, B.NAME AS U_FRANCHISEFEEDESC
						from U_LGUSETUP A
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_MTOPFRANCHISEFEE
							");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["combinepermitfranchise"] = $objRs->fields["U_MTOPCOMBINEPERMITFRANCHISE"];
		$page->privatedata["franchisefeecode"] = $objRs->fields["U_MTOPFRANCHISEFEE"];
		$page->privatedata["franchisefeedesc"] = $objRs->fields["U_FRANCHISEFEEDESC"];
		$page->privatedata["franchisefeeamt"] = $objRs->fields["U_MTOPFRANCHISEFEEAMT"];
		$page->privatedata["franchisefeerevokedate"] = date('Y-'.$objRs->fields["U_MTOPFRANCHISEREVOKEMONTH"].'-01');
	}
	
	$page->businessobject->items->setvisible("u_type",false);
	$page->businessobject->items->setvisible("u_caseno",false);
	$page->businessobject->items->setvisible("u_caseseries",false);
	if ($page->privatedata["combinepermitfranchise"]=="0") {
		$page->businessobject->items->setvisible("u_type",true);
		$page->businessobject->items->setvisible("u_caseno",true);
		$page->businessobject->items->setvisible("u_caseseries",true);
	}
	//$page->toolbar->setaction("print",false);
	
	$objMaster->reportaction = "QS";
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

